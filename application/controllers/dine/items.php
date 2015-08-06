<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dine/items_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/items_helper');
	}
	public function index()
	{
		$data = $this->syter->spawn('items');

		$items = $this->items_model->get_item();
		//echo $this->db->last_query();
		$data['code'] = items_display($items);

		$this->load->view('page',$data);
	}
	public function get_subcategories($cat_id = null)
	{
		$results = $this->site_model->get_custom_val('subcategories',
			array('sub_cat_id,name,code'),
			(is_null($cat_id) ? null : 'cat_id'),
			(is_null($cat_id) ? null : $cat_id),
			true);
		$echo_array = array();
		foreach ($results as $val) {
			$echo_array[$val->sub_cat_id] = "[ ".$val->code." ] ".$val->name;
		}
		echo json_encode($echo_array);
	}
	public function setup($item_id = null)
	{
		$data = $this->syter->spawn();

		if (is_null($item_id))
			$data['page_title'] = fa('fa-cutlery fa-fw')." Add new item";
		else {
			$item = $this->items_model->get_item($item_id);
			$item = $item[0];
			if (!empty($item->code)) {
				$data['page_title'] = fa('fa-cutlery fa-fw')." ".iSetObj($item,'name');
				if (!empty($item->update_date))
					$data['page_subtitle'] = "Last updated ".$item->update_date;

			} else {
				header('Location:'.base_url().'items/setup');
			}
		}

		$data['code'] = items_form_container($item_id);
		$data['load_js'] = "dine/items.php";
		$data['use_js'] = "itemFormContainerJs";

		$this->load->view('page',$data);
	}
	public function setup_load($item_id = null)
	{
		$details = array();
		if (!is_null($item_id))
			$item = $this->items_model->get_item($item_id);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = items_details_form($details,$item_id);
		$data['load_js'] = "dine/items.php";
		$data['use_js'] = "itemDetailsJs";
		$this->load->view('load',$data);
	}
	public function item_details_db()
	{
		// if (!$this->input->post())
			// header("Location:".base_url()."items");

		$items = array(
			'barcode' => $this->input->post('barcode'),
			'code' => $this->input->post('code'),
			'name' => $this->input->post('name'),
			'desc' => $this->input->post('desc'),
			'cat_id' => $this->input->post('cat_id'),
			'subcat_id' => $this->input->post('subcat_id'),
			'supplier_id' => $this->input->post('supplier_id'),
			'uom' => $this->input->post('uom'),
			'cost' => $this->input->post('cost'),
			'type' => $this->input->post('type'),
			'no_per_pack' => $this->input->post('no_per_pack'),
			'no_per_case' => $this->input->post('no_per_case'),
			'reorder_qty' => $this->input->post('reorder_qty'),
			'max_qty' => $this->input->post('max_qty'),
			'inactive' => (int)$this->input->post('inactive'),
		);

		if ($this->input->post('item_id')) {
			$id = $this->input->post('item_id');
			$this->items_model->update_item($items,$id);
			$msg = "Updated item: ".$items['name'];
		} else {
			$id = $this->items_model->add_item($items);
			$msg = "Added new item: ".$items['name'];
		}

		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	public function inventory()
	{
		$data = $this->syter->spawn('items');

		$query = $this->items_model->get_curr_item_inv_and_locs();
		$records = $query->result_array();

		$loc_fields = array();
		if (!empty($records)) {
			$xx = $records[0];
			foreach ($xx as $k => $v) {
				if (strpos($k, "!!Loc-") === false)
					continue;

				$loc_fields[$k] = str_replace("!!Loc-", "", $k);
			}
		}

		$data['code'] = item_inventory_and_location_container($records, $loc_fields);
		$data['page_title'] = "Item Inventory";
		$data['page_subtitle'] = "Current item count and location";
		$data['load_js'] = "dine/items.php";
		$data['use_js'] = "inventoryJS";
		$this->load->view('page',$data);
	}
	public function print_inventory(){
			$this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
			$this->load->model('dine/items_model');

			$get_inventory = $this->items_model->get_inventory_moves();
			$fields = $get_inventory->result_array();

			$fields = array();
			if (!empty($get_inventory)) {
				$row = $get_inventory[0];
				foreach ($row as $k => $v) {
					if (strpos($k, "!!Trans-") === false)
						continue;

					$fields[$k] = str_replace("!!Trans-", "", $k);
				}
			}

			/*Print Headers*/
			// Print "Item Code"
			// Print "Item Name"
			foreach ($fields as $i => $iv) {
				// Print $iv
			}

			foreach ($get_inventory as $inv) {
				// set initial column (eg. A) to be used as $sheet->getColumnDimension('A')

				// Print $inv['code']
				// Print $inv['name']

				$initial_column = "C";
				foreach ($fields as $kf => $kv) {
					// Print $inv[$kf]
					++$initial_column;
				}
			}



            //$date = $this->input->get('date_to');
            //$date_param = (is_null($date) ? date('Y-m-d') : $date);
            //$terminal = $this->input->get('terminal');
            //$cashier = $this->input->get('cashier');

            // $sheet->getColumnDimension('A')->setWidth(15);
            // $sheet->getColumnDimension('B')->setWidth(5);
            // $sheet->getColumnDimension('C')->setWidth(15);
            // //-----------------------------------------------------------------------------
            // //START HEADER
            // //-----------------------------------------------------------------------------
            // $rc = 1;
            // $filename='Hourly Sales Report';
            // $sheet->getCell('A'.$rc)->setValue($filename);
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            // $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');

            // if (!empty($cashier)){
            //     $cashier_name = $cashier;
            // }else{
            //     $cashier_name = 'All Cashier';
            // }
            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('Employee');
            // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue($cashier_name);

            // if (!empty($terminal)){
            //     $terminal_name = $terminal;
            // }else{
            //     $terminal_name = 'All Terminal';
            // }
            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('PC');
            // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue($terminal_name);

            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('Date');
            // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            // $sheet->getCell('B'.$rc)->setValue($date_param);

            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('Printed on');
            // $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            // $sheet->getCell('B'.$rc)->setValue(date("d M y g:i:s A"));
            // $rc++;
            // $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);

            // //-----------------------------------------------------------------------------
            // //END HEADER
            // //-----------------------------------------------------------------------------

            // $rc++;

            // $ctr=1;
            // $gtotal_net_sales = 0;
            // foreach(unserialize(TIMERANGES) as $k=>$v){
            //     $rc++;
            //     $sheet->getCell('B'.$rc)->setValue($ctr.' '.$v['FTIME'].' - '.$v['TTIME']);
            //     $rc++;
            //     $sheet->getCell('A'.$rc)->setValue('Net Sales Total');

            //     $net_sales_total = $this->settings_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$date);
            //     $net_sales_total = $net_sales_total[0];
            //     $col_a = $col_b = 0;
            //     // $sheet->getCell('C'.$rc)->setValue($col_a);
            //     $col_b = $net_sales_total;
            //     // $sheet->getCell('A'.$rc)->setValue('-->'.$col_b->total_per_hour);
            //     // $sheet->getCell('A'.$rc)->setValue($this->db->last_query());
            //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b->total_per_hour,2));
            //     $gtotal_net_sales += $col_b->total_per_hour;

            //     $rc++;
            //     $sheet->getCell('A'.$rc)->setValue('Average $/Cover');

            //     $col_a = $col_b = 0;
            //     $sheet->getCell('C'.$rc)->setValue($col_a);
            //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

            //     $rc++;
            //     $sheet->getCell('A'.$rc)->setValue('Average $/Check');

            //     $col_a = $col_b = 0;
            //     $sheet->getCell('C'.$rc)->setValue($col_a);
            //     $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

            //     $ctr++;
            // }

            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('TOTAL');
            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('Net Sales Total');
            // $sheet->getCell('D'.$rc)->setValue(number_format($gtotal_net_sales,2));


            // Redirect output to a client’s web browser (Excel2007)
            //clean the output buffer
            // ob_end_clean();

            // header('Content-type: application/vnd.ms-excel');
            // header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            // header('Cache-Control: max-age=0');
            // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            // $objWriter->save('php://output');

            $filename='inventory'.phpNow().'.xls';
	        header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="'.$filename.'"');
	        header('Cache-Control: max-age=0');
	        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	        $objWriter->save('php://output');
	}
}