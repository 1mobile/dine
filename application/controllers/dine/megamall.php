<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Megamall extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/megamall_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = megamallPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/megamall.php';
        $data['use_js'] = 'megamallPageJs';
        $this->load->view('load',$data);
	}
	public function files(){
		$data = $this->syter->spawn(null);
		$data['code'] = filesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/megamall.php';
		$data['use_js'] = 'fileJs';
		$this->load->view('load',$data);
	}
	public function daily_files(){
		$date = $this->input->post('file_date');

		$path = "C:/SM/";
		$file_txt = date('mdY',strtotime($date)).".txt";
        $year = date('Y',strtotime($date));
        $month = date('M',strtotime($date));
        $text = "C:/SM/".$year."/".$month."/".$file_txt;
		if(file_exists($text)){
			$fh = fopen($text, 'r');
			$theData = fread($fh, filesize($text));
			fclose($fh);
			$mod = "<pre>".$theData."</pre>";
		}
		else{
			$mod = "<center> file not found. </center>";
		}

		echo json_encode(array('daily'=>$mod));
	}
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('megamall');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = megamallSettingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/megamall.php';
		$data['use_js'] = 'settingsJs';
		$this->load->view('load',$data);
	}
	public function settings_db(){
		$items = array(
			'br_code'=>$this->input->post('br_code'),
			'tenant_no'=>$this->input->post('tenant_no'),
			'class_code'=>$this->input->post('class_code'),
			'outlet_no'=>$this->input->post('outlet_no'),
			'trade_code'=>$this->input->post('trade_code'),
		);
		if ($this->input->post('megamall_id')) {
			$id = $this->input->post('megamall_id');
			$this->site_model->update_tbl('megamall','id',$items,$id);
			$msg = "Updated Settings.";
		} else {
			$id = $this->site_model->add_tbl('megamall',$items);
			$msg = "Updated Settings";
		}
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
}