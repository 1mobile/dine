<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Robinsons extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/robinsons_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $files = $this->cashier_model->get_rob_files();
        $data['code'] = robFiles($files);
        $this->load->view('load',$data);
	}
}