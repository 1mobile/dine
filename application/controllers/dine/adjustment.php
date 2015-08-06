<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjustment extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dine/adjustment_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/adjustment_helper');
	}
	public function index()
	{
        $data = $this->syter->spawn('trans');
        $data['page_title'] = "Adjustments";
        $data['page_subtitle'] = "Transaction Adjustment";

        $adjustments = $this->adjustment_model->get_adjustments();
        $data['code'] = adjustments_display($adjustments);
        $this->load->view('page',$data);
	}
	public function form()
	{
        $data = $this->syter->spawn('trans');
        $data['page_title'] = "Create new adjustment";

        $this->session->unset_userdata('adj_cart');

        $data['code'] = adjustment_form();
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/adjustment.php';
        $data['use_js'] = 'adjustmentJs';
        $this->load->view('page',$data);
	}
	public function get_item_details($item_id=null,$asJson=true)
	{
		$this->load->model('dine/items_model');
		$json = array();
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];

        $json['item_id'] = $item->item_id;
        $json['uom'] = $item->uom;

        $opts = array();
        $opts[$item->uom] = $item->uom;
        if($item->no_per_pack > 0)
            $opts['Pack(@'.$item->no_per_pack.' '.$item->uom.')'] = $item->uom."-".'pack-'.$item->no_per_pack;
        if($item->no_per_case > 0)
            $opts['Case(@'.$item->no_per_case.' Packs)'] = $item->uom."-".'case-'.$item->no_per_case;

        $json['opts'] =  $opts;
        $json['ppack'] = $item->no_per_pack;
        $json['pcase'] = $item->no_per_case;
        echo json_encode($json);
	}
	public function adjustment_db()
	{
		$this->load->model('dine/items_model');
		$this->load->model('core/trans_model');

		$cart = $this->session->userdata('adj_cart');
		$user = $this->session->userdata('user');

		$ref = $this->trans_model->get_next_ref(ADJUSTMENT_TRANS);

		if (empty($cart)) {
            echo json_encode(array('msg'=>"Please select an item first before proceeding"));
            return false;
        }

		$this->trans_model->db->trans_start();
			$this->trans_model->save_ref(ADJUSTMENT_TRANS,$ref);
			$items = array(
				'type_id' => ADJUSTMENT_TRANS,
				'memo'=> $this->input->post('memo'),
				'trans_ref' => $ref,
				'user_id' => $user['id'],
			);
			$id = $this->adjustment_model->add_adjustment($items);

			$prepared = $prepared_moves = array();
			foreach ($cart as $val) {
				$val['item-id'] = abs($val['item-id']);

				$prepare = array(
					'adjustment_id' => $id,
					'item_id' => (int)$val['item-id'],
					'case' => 0,
					'pack' => 0
				);
				$prepare_moves = array(
                    'type_id' => RECEIVE_TRANS,
                    'trans_id' => $id,
                    'trans_ref' => $ref,
                    'item_id' => $val['item-id'],
                    'uom' => $val['item-uom'],
                    'pack_qty' => null,
                    'case_qty' => null,
                    'reg_date' => date('Y-m-d H:i:s')
                );

				if (strpos($val['select-uom'],'pack') !== false) {
					$converted_qty = $val['qty'] * $val['item-ppack'];
					$prepare['qty'] = (double) $converted_qty;
					$prepare['pack'] = (double) $val['qty'];
					$prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['pack_qty'] = (double) $val['qty'];
				} elseif (strpos($val['select-uom'],'case') !== false) {
					$converted_qty = $val['qty'] * $val['item-ppack'] * $val['item-pcase'];
					$prepare['qty'] = (double) $converted_qty;
					$prepare['case'] = (double) $val['qty'];
					$prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['case_qty'] = (double) $val['qty'];
				} else {
					$prepare['qty'] = (double)$val['qty'];
					$prepare_moves['qty'] = (double)$val['qty'];
				}

				$fr = explode('-', $val['from_loc']);
				$to = explode('-', $val['to_loc']);
				$prepare['from_loc'] = (int)$fr[0];
				$prepare['to_loc'] = (int)$to[0];

				if ($prepare['from_loc'] != $prepare['to_loc']) {
					# From Location
	                $prepare_moves['loc_id'] = $prepare['from_loc'];

					$last_stock = 0;
	                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$prepare['from_loc'],'item_id'=>$val['item-id']));
	                if (!empty($stocks->curr_item_qty))
	                    $last_stock = $stocks->curr_item_qty;
	                $prepare_moves['curr_item_qty'] = $last_stock - $prepare_moves['qty'];
	                $prepared_moves[] = $prepare_moves;

	                # To Location
					$prepare_moves['loc_id'] = $prepare['to_loc'];

					$last_stock = 0;
	                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$prepare['to_loc'],'item_id'=>$val['item-id']));
	                if (!empty($stocks->curr_item_qty))
	                    $last_stock = $stocks->curr_item_qty;
	                $prepare_moves['curr_item_qty'] = $last_stock + $prepare_moves['qty'];
	                $prepared_moves[] = $prepare_moves;

				} else {
					$last_stock = 0;
	                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$prepare['to_loc'],'item_id'=>$val['item-id']));
	                if (!empty($stocks->curr_item_qty))
	                    $last_stock = $stocks->curr_item_qty;
	                $prepare_moves['curr_item_qty'] = $last_stock + $prepare_moves['qty'];
					$prepared_moves[] = $prepare_moves;
				}

				$prepared[] = $prepare;
			}
			$this->items_model->add_item_moves_batch($prepared_moves);
			$this->adjustment_model->add_adjustment_detail_batch($prepared);
		$this->trans_model->db->trans_complete();

		echo json_encode(array('msg'=>$ref." processed"));
	}
}