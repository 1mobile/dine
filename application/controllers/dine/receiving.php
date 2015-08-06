<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receiving extends CI_Controller {
	public function index(){
        $this->load->model('dine/receiving_model');
        $this->load->helper('dine/receiving_helper');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = "Receiving Transactions";

        $recs = $this->receiving_model->get_trans_receivings();
        $data['code'] = receivingListPage($recs);
        $this->load->view('page',$data);
    }
    public function form(){
        $this->load->model('dine/receiving_model');
        $this->load->helper('dine/receiving_helper');
        sess_clear('rec_cart');
        $data = $this->syter->spawn('trans');
        $data['page_title'] = "Receiving Transactions";

        $data['code'] = receivingFormPage();
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/receive.php';
        $data['use_js'] = 'receiveJs';
        $this->load->view('page',$data);
    }
    public function get_item_details($item_id=null,$asJson=true){
        $this->load->model('dine/receiving_model');
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
    public function save(){
        $this->load->model('dine/receiving_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $user = $this->session->userdata('user');
        $rec_cart = $this->session->userdata('rec_cart');
        $next_ref = $this->trans_model->get_next_ref(RECEIVE_TRANS);
        $items = array(
            "reference"=>$this->input->post('reference'),
            "memo"=>$this->input->post('memo'),
            "trans_ref"=>$next_ref,
            "type_id"=>RECEIVE_TRANS,
            "user_id"=>$user['id'],
            "supplier_id"=>$this->input->post('suppliers')
        );
        $errors = null;
        if (empty($rec_cart)) {
            $errors = 'no item';
            echo json_encode(array('msg'=>"Please select an item first before proceeding",'error'=>$errors));
            return false;
        }

        $this->trans_model->db->trans_start();
            $id = $this->receiving_model->add_trans_receivings($items);

            $prepared = $prepared_moves = array();
            $total = 0;
            $datetime = date('Y-m-d H:i:s');
            foreach ($rec_cart as $val) {
                $prepare = array(
                    'receiving_id' => $id,
                    'item_id'      => (int) $val['item-id'],
                    'case'         => null,
                    'pack'         => null,
                    'price'        => $val['cost']
                );
                $prepare_moves = array(
                    'type_id' => RECEIVE_TRANS,
                    'trans_id' => $id,
                    'trans_ref' => $next_ref,
                    'item_id' => $val['item-id'],
                    'uom' => $val['item-uom'],
                    'pack_qty' => null,
                    'case_qty' => null,
                    'reg_date' => $datetime,
                );

                $loc_id = explode('-', $val['loc_id']);
                $prepare_moves['loc_id'] = $loc_id[0];

                $last_stock = 0;
                $stocks = $this->items_model->get_latest_item_move(array('loc_id'=>$val['loc_id'],'item_id'=>$val['item-id']));
                if (!empty($stocks->curr_item_qty))
                    $last_stock = $stocks->curr_item_qty;


                if (strpos($val['select-uom'],'pack') !== false) {
                    $converted_qty = $val['qty'] * $val['item-ppack'];
                    $prepare['qty'] = (double) $converted_qty;
                    $prepare['pack'] = (double) $val['qty'];
                    $prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['pack_qty'] = (double) $val['qty'];
                    $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                } elseif (strpos($val['select-uom'],'case') !== false) {
                    $converted_qty = $val['qty'] * $val['item-ppack'] * $val['item-pcase'];
                    $prepare['qty'] = (double) $converted_qty;
                    $prepare['case'] = (double) $val['qty'];
                    $prepare_moves['qty'] = $converted_qty;
                    $prepare_moves['case_qty'] = (double) $val['qty'];
                    $prepare_moves['curr_item_qty'] = $last_stock + $converted_qty;
                } else {
                    $prepare['qty'] = (double)$val['qty'];
                    $prepare_moves['curr_item_qty'] = (double)$val['qty'] + $last_stock;
                }

                $prepared[] = $prepare;
                $prepared_moves[] = $prepare_moves;
                $total += $val['cost'];
            }
            $this->receiving_model->add_trans_receiving_batch($prepared);
            $this->receiving_model->update_trans_receivings(array('amount'=>$total),$id);
            $this->items_model->add_item_moves_batch($prepared_moves);
            $this->trans_model->save_ref(RECEIVE_TRANS,$next_ref);
            $this->trans_model->db->trans_complete();

            $this->session->unset_userdata('rec_cart');
            site_alert($next_ref." processed",'success');

        echo json_encode(array('msg'=>$next_ref." processed",'error'=>$errors));
    }
}