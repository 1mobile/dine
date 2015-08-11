<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Araneta extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/araneta_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = aranetaPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/araneta.php';
        $data['use_js'] = 'aranetaPageJs';
        $this->load->view('load',$data);
	}
	public function files(){
		$data = $this->syter->spawn(null);
		$data['code'] = filesPage();
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/araneta.php';
		$data['use_js'] = 'fileJs';
		$this->load->view('load',$data);
	}
	public function daily_files(){
		$date = $this->input->post('file_date');
		$mo = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
        $year = date('Y',strtotime($date));
        $month = date('M',strtotime($date));
        $m = date('m',strtotime($date));
        $d = date('d',strtotime($date));

		$path = "araneta_files/";
		$daily_path = $path."daily/";
		$araneta_db = $this->site_model->get_tbl('araneta');
		$araneta = $araneta_db[0];
		$file = $araneta->lessee_name;
		$summary_filename = "araneta_files/daily/".$year."/".$month."/".$file."S.".$mo[$m].$d; 
		$trans_list_filename = "araneta_files/trans_list/".$year."/".$month."/".$file."L.".$mo[$m].$d; 
		$monthly_filename = "araneta_files/monthly/".$year."/".$month."/".$file."C.".$mo[$m]."00"; 

		if(file_exists($summary_filename)){
			$fh = fopen($summary_filename, 'r');
			$theData = fread($fh, filesize($summary_filename));
			fclose($fh);
			$sum = "<pre>".$theData."</pre>";
		}
		else{
			$sum = "<center> file not found. </center>";
		}

		if(file_exists($trans_list_filename)){
			$fh = fopen($trans_list_filename, 'r');
			$theData = fread($fh, filesize($trans_list_filename));
			fclose($fh);
			$list = "<pre>".$theData."</pre>";
		}
		else{
			$list = "<center> file not found. </center>";
		}

		if(file_exists($monthly_filename)){
			$fh = fopen($monthly_filename, 'r');
			$theData = fread($fh, filesize($monthly_filename));
			fclose($fh);
			$mod = "<pre>".$theData."</pre>";
		}
		else{
			$mod = "<center> file not found. </center>";
		}

		echo json_encode(array('list'=>$list,'sum'=>$sum,'month'=>$mod));
	}
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('araneta');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = aranetaSettingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/araneta.php';
		$data['use_js'] = 'settingsJs';
		$this->load->view('load',$data);
	}
	public function settings_db(){
		$items = array(
			'lessee_name'=>$this->input->post('lessee_name'),
			'lessee_no'=>$this->input->post('lessee_no'),
			'space_code'=>$this->input->post('space_code'),
		);
		if ($this->input->post('araneta_id')) {
			$id = $this->input->post('araneta_id');
			$this->site_model->update_tbl('araneta','id',$items,$id);
			$msg = "Updated Settings.";
		} else {
			$id = $this->site_model->add_tbl('araneta',$items);
			$msg = "Updated Settings";
		}
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
}