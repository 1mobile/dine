<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managements extends CI_Controller {
    var $data = null;
    public function types(){
        $this->load->model('resto/management_model');
        $this->load->helper('site/site_forms_helper');
        $restaurant_type_list = $this->management_model->get_restaurant_types();
        $data = $this->syter->spawn('setup');
        $data['code'] = site_list_form("resto/managements/restaurant_types_form","restaurant_types_form","Types",$restaurant_type_list,'type_name',"type_id");
        $data['page_subtitle'] = "Types";
        $data['page_subtitle'] = 'Types Management';
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function restaurant_types_form($ref=null){
        $this->load->helper('resto/management_helper');
        $this->load->model('resto/management_model');
        $type = array();
        if($ref != null){
            $types = $this->management_model->get_restaurant_types($ref);
            $type = $types[0];
        }
        $this->data['code'] = makeRestaurantTypeForm($type);
        $this->load->view('load',$this->data);
    }
    public function restaurant_type_db(){
        $this->load->model('resto/management_model');
        $items = array();

        if($this->input->post('type_id')){
            $items = array(
                    "type_code"=>$this->input->post('type_code'),
                    "type_name"=>$this->input->post('type_name')
                );
            $this->management_model->update_restaurant_types($items,$this->input->post('type_id'));
            $id = $this->input->post('type_id');
            $act = 'update';
            $msg = 'Updated Restaurant Type. '.$this->input->post('type_code').' '.$this->input->post('type_name');
        }else{
            $items = array(
                    "type_code"=>$this->input->post('type_code'),
                    "type_name"=>$this->input->post('type_name')
                );
            $id = $this->management_model->add_restaurant_types($items);
            $act = 'add';
            $msg = 'Added  new Restaurant Type '.$this->input->post('type_code').' '.$this->input->post('type_name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('type_code').' '.$this->input->post('type_name'),"act"=>$act,'msg'=>$msg));
    }
    public function staffs(){
        $this->load->model('resto/management_model');
        $this->load->helper('site/site_forms_helper');
        $restaurant_staff_list = $this->management_model->get_restaurant_staffs();
        $data = $this->syter->spawn('setup');
        $data['code'] = site_list_form("resto/managements/staffs_form","staffs_form","Staffs",$restaurant_staff_list,'staff_name',"staff_id");
        $data['page_subtitle'] = 'Staff Management';
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function staffs_form($ref=null){
        $this->load->helper('resto/management_helper');
        $this->load->model('resto/management_model');
        $staff = array();
        if($ref != null){
            $staffs = $this->management_model->get_restaurant_staffs($ref);
            $staff = $staffs[0];
        }
        $this->data['code'] = makeRestaurantStaffForm($staff);
        $this->load->view('load',$this->data);
    }
    public function staffs_db(){
        $this->load->model('resto/management_model');
        $items = array();

        if($this->input->post('staff_id')){
            $items = array(
                    "staff_name"=>$this->input->post('staff_name')
                );
            $this->management_model->update_restaurant_staffs($items,$this->input->post('staff_id'));
            $id = $this->input->post('staff_id');
            $act = 'update';
            $msg = 'Updated Restaurant Staff. '.$this->input->post('staff_name');
        }else{
            $items = array(
                    "staff_name"=>$this->input->post('staff_name')
                );
            $id = $this->management_model->add_restaurant_staffs($items);
            $act = 'add';
            $msg = 'Added  new Restaurant Staff '.$this->input->post('staff_name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('staff_name'),"act"=>$act,'msg'=>$msg));
    }
}