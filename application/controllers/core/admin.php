<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	var $data = null;
    public function roles(){
        $this->load->model('core/admin_model');
        $this->load->helper('site/site_forms_helper');
        $role_list = $this->admin_model->get_user_roles();
        $data = $this->syter->spawn('roles');
        $data['code'] = site_list_form("admin/roles_form","roles_form","Roles List",$role_list,array('role'),"id");
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function roles_form($ref=null){
        $this->load->helper('core/admin_helper');
        $this->load->model('core/admin_model');
        $role = array();
        $access = array();
        if($ref != null){
            $roles = $this->admin_model->get_user_roles($ref);
            $role = $roles[0];
            $access = explode(',',$role->access);
        }
        $navs = $this->syter->get_navs();
        $this->data['code'] = rolesForm($role,$access,$navs);
        $this->data['load_js'] = 'site/admin';
        $this->data['use_js'] = 'rolesJs';
        $this->load->view('load',$this->data);
    }
    public function roles_db(){
        $this->load->model('core/admin_model');
        $links = $this->input->post('roles');
        $role = $this->input->post('role');
        $desc = $this->input->post('description');
        $access = "";
        foreach ($links as $li) {
            $access .= $li.",";
        }
        $access = substr($access,0,-1);
        $items = array(
            "role"=>$role,
            "description"=>$desc,
            "access"=>$access
        );
        if($this->input->post('role_id')){
            $this->admin_model->update_user_roles($items,$this->input->post('role_id'));
            $id = $this->input->post('role_id');
            $act = 'update';
            $msg = 'Updated role '.$role;
        }
        else{
            $id = $this->admin_model->add_user_roles($items);
            $act = 'add';
            $msg = 'Added  new role '.$role;   
        }
        echo json_encode(array("id"=>$id,"desc"=>$role,"act"=>$act,'msg'=>$msg));
    }
    public function restart(){
        $this->load->helper('core/admin_helper');
        $data = $this->syter->spawn('restart');
        $data['page_title'] = fa('fa-refresh')." Restart POS";
        $data['code'] = restartPage();
        $data['load_js'] = 'site/admin.php';
        $data['use_js'] = 'restartJs';
        $this->load->view('page',$data);
    }
    public function go_restart(){
        $this->load->model('core/admin_model');
        $this->admin_model->restart();
        $this->db = $this->load->database('main', TRUE);
        $this->admin_model->restart();
        session_start();
        unset($_SESSION['load']);
        unset($_SESSION['problem']);
        $this->session->sess_destroy();
        
    }
}