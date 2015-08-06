<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charges extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('dine/settings_model');
		$this->load->helper('dine/settings_helper');
		$this->load->helper('site/site_forms_helper');
	}
	public function index(){
     	$data = $this->syter->spawn('charges');
     	$list = $this->settings_model->get_charges();
     	$data['code'] = site_list_form("charges/form","charge_form","Charges",$list,array('charge_code','charge_name'),'charge_id');
     	$data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
	}
	public function form($ref=null){
        $item = array();
        if($ref != null){
            $items = $this->settings_model->get_charges($ref);
            $item = $items[0];
        }
        $data['code'] = makeChargeForm($item);
        $this->load->view('load',$data);
    }
    public function db(){
        $this->load->model('dine/main_model');
        $items = array(
            "charge_code"=>$this->input->post('charge_code'),
            "charge_name"=>$this->input->post('charge_name'),
            "charge_amount"=>$this->input->post('charge_amount'),
            "no_tax"=>$this->input->post('no_tax'),
            "absolute"=>$this->input->post('absolute'),
            "inactive"=>$this->input->post('inactive'),
        );
        if($this->input->post('charge_id')){
            $this->settings_model->update_charges($items,$this->input->post('charge_id'));
            $id = $this->input->post('charge_id');
            $act = 'update';
            $msg = 'Updated Charge. '.$this->input->post('charge_name');
            $this->main_model->update_tbl('charges','charge_id',$items,$id);
        }else{
            $id = $this->settings_model->add_charges($items);
            $id = $this->input->post('charge_id');
            $act = 'add';
            $msg = 'Added  new Charge'.$this->input->post('charge_name');
            $this->main_model->add_trans_tbl('charges',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('charge_code')." ".$this->input->post('charge_name'),"act"=>$act,'msg'=>$msg));
    }
}