<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupons extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/coupons_helper');
	}
	public function index(){
		$data = $this->syter->spawn('coupons');
		$result = $this->site_model->get_tbl('coupons');
		$data['code'] = couponsPage($result);
		$this->load->view('page',$data);
	}
	public function form($coupon_id=null){
		$data = $this->syter->spawn('coupons');
		$details = array();
		if (!is_null($coupon_id)){
			$result = $this->site_model->get_tbl('coupons',array("coupon_id"=>$coupon_id));
			if(count($result) > 0){
				$details = $result[0];				
			}
		}
		$data['code'] = couponsForm($details,$coupon_id);
		$data['load_js'] = "dine/coupons.php";
		$data['use_js'] = "couponsFormJs";
		$this->load->view('page',$data);
	}
	public function db(){
	    $items = array(
			'card_no' => $this->input->post('card_no'),
			'amount' => $this->input->post('amount'),
			'expiration' => date2Sql($this->input->post('expiration')),
			'inactive' => (int)$this->input->post('inactive'),
		);

		if($this->input->post('coupon_id')){
		    $this->site_model->update_tbl('coupons','coupon_id',$items,$this->input->post('coupon_id'));
		    $id = $this->input->post('coupon_id');
		    $act = 'update';
		    $msg = 'Updated Coupon '.$this->input->post('card_no');
		}
		else{
			$id = $this->site_model->add_tbl('coupons',$items);
		    $act = 'add';
		    $msg = 'Added  new Coupon '.$this->input->post('card_no');
		}
		site_alert($msg,'success');
	}
}