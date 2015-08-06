<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shift extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        $this->load->helper('dine/clock_helper');
        $this->load->helper('dine/shift_helper');
        $this->load->helper('core/on_screen_key_helper');
    }
    public function index(){
        $data = $this->syter->spawn(null);
        $now = $this->site_model->get_db_now();
        
        $data['code'] = shiftPage($now);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'shiftJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }
    public function time(){
        $data = $this->syter->spawn(null);
        $now = $this->site_model->get_db_now();
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
        $get_dtr =  $this->clock_model->get_user_dtrs($user_id,date('Y-m-01'),date('Y-m-t'));
        $data['code'] = timePage($shift,$get_dtr,$now);
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'timeJs';
        $this->load->view('load',$data);
    }
    public function start_amount(){
        $data = $this->syter->spawn();
        $data['code'] = startAmountPage();
        $data['add_css'] = array('css/onscrkeys.css','css/virtual_keyboard.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/shift.php';
        $data['use_js'] = 'startAmountJs';
        $this->load->view('load',$data);   
    }
    public function timeIn(){
        $amount = 0;
        $error  = "";
        if($this->input->post('amount')){
            $amount = $this->input->post('amount');
        }
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $now = $this->site_model->get_db_now('sql');
        $items = array(
            'user_id'=>$user_id,
            'check_in'=>$now,
            'terminal_id'=>TERMINAL_ID
        );
        $log_user = $this->session->userdata('user');
        $shift_id = $this->clock_model->insert_clockin($items);
        $items_cash = array(
            'shift_id'=>$shift_id,
            'amount'=>$amount,
            'user_id'=>$user_id,
            'trans_date'=>$now
        );
        $cash_id = $this->clock_model->insert_cashin($items_cash);
        $this->logs_model->add_logs('Shift',$log_user['id'],$log_user['full_name']." Started Shift.",$shift_id);
        $this->logs_model->add_logs('Drawer',$log_user['id'],$log_user['full_name']." Cash in ".$amount,null);
        echo json_encode(array('error'=>$error));
    }
}