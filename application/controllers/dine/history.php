<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends CI_Controller {
        public function __construct(){
        	parent::__construct();
                $this->load->helper('dine/history_helper');
        }
        public function index(){
                $data = $this->syter->spawn('trans');
                $data['page_title'] = fa('fa-file-text-o')." POS Read History";

                $join['users'] = array('content'=>'read_details.user_id = users.id');
                $select = 'read_details.*,users.fname,users.mname,users.lname,users.suffix';
                $result = $this->site_model->get_tbl('read_details',array(),array('read_date'=>'desc','read_type'=>'desc'),$join,true,'*');
                $data['code'] = readHistoryList($result);
                $data['load_js'] = 'dine/history.php';
                $data['use_js'] = 'historyJs';
                $this->load->view('page',$data);
        }
}