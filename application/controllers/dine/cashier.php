<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/reads.php");
class Cashier extends Reads {
	#CONTROL PANEL
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/cashier_model');
        $this->load->helper('core/string_helper');
        $this->load->model('site/site_model');
    }
    public function index(){
        $this->load->helper('dine/cashier_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null);
        sess_clear('trans_mod_cart');
        sess_clear('trans_cart');
        sess_clear('counter');
        sess_clear('trans_disc_cart');
        sess_clear('trans_charge_cart');
        // $time = $this->site_model->get_db_now();
        // $prev_date = date('Y-m-d', strtotime($time .' -1 day'));
        // $result = $this->cashier_model->get_latest_read_date(Z_READ);
        // $need_eod = false;
        // if(!empty($result)){
        //     if(strtotime($prev_date) > strtotime($result->maxi)){
        //         $need_eod = true;
        //     }
        // }

        $set = $this->cashier_model->get_pos_settings();

        $data['code'] = indexPage(false,$set);
        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'controlPanelJs';
        $this->load->view('cashier',$data);
    }
    public function index2(){
        $this->load->helper('dine/cashier_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null);

        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css','css/control_panel.css');
        $data['code'] = indexPage2();
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    function manager_call(){
        $this->load->helper('dine/manager_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null,false);
        $data['code'] = onScrNumPwdPadOnly('manager-call-pin-login');
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');
        $this->load->view('load',$data);
    }
    function manager_reasons(){
        $this->load->helper('dine/manager_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null,false);
        $data['code'] = managerReasonsPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js');
        $data['load_js'] = 'dine/manager';
        $data['use_js'] = 'managerReasonsJs';
        $this->load->view('load',$data);
    }
    function manager_go_login() {
        $this->load->model('dine/manager_model');
        $pin = $this->input->post('pin');
        $manager = $this->manager_model->get_manager_by_pin($pin);
        $man = array();
        if (!isset($manager->id)) {
            echo json_encode(array('error_msg'=>'Invalid manager pin','manager'=>$man));
        } else {
            $this->session->set_userdata('manager_privs',array('method'=>'page','id'=>$manager->id));
            $man = array(
                "manager_id"=>$manager->id,
                "manager_username"=>$manager->username
            );
            echo json_encode(array('success_msg'=>'Go','manager'=>$man));
        }

        // return false;
    }
    public function food_server_call(){
        // $this->load->helper('dine/manager_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null,false);
        $data['code'] = onScrNumPwdPadOnly('fs-call-pin-login');
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');
        $this->load->view('load',$data);
    }
    public function food_server_login() {
        $pin = $this->input->post('pin');
        $employee = $this->site_model->get_tbl('users',array("pin"=>$pin));
        $emp = array();
        if(count($employee) > 0){
            $em = $employee[0];
            $emp = array(
                "emp_id"=>$em->id,
                "emp_username"=>$em->username
            );
            echo json_encode(array('success_msg'=>'Go','emp'=>$emp));
        }
        else{
            echo json_encode(array('error_msg'=>'Invalid pin','emp'=>$emp));
        }
    }
    function record_delete_line($cart=null,$id=null,$type=null,$reason=null,$man_id=null,$man_user=null) {
        $this->load->model('dine/cashier_model');
        $counter = sess('counter');
        $wagon = $this->session->userData($cart);
        $ar = $wagon[$id];
        $log_user = $this->session->userdata('user');
        $res = array();
        $sales_id = null;
        $time = $this->site_model->get_db_now();

        if(count($counter['sales_id'])){
            $sales_id = $counter['sales_id'];
        }
        if($type == 'menu'){
            $res = array(
                'user_id'=>$log_user['id'],
                'manager_id'=>$man_id,
                'type'=>$type,
                'ref_id'=>$ar['menu_id'],
                'ref_name'=>$ar['name'],
                "reason"=>$reason,
                "trans_id"=>$sales_id,
                "datetime"=>date2Sql($time)
            );
        }
        if(!empty($res)){
            $this->cashier_model->add_reasons($res);
        }
    }
    public function _remap($method,$params=array()){
        $this->load->model('dine/clock_model');
        // if (!$this->session->userdata('today_in') ) {
            $user = $this->session->userdata('user');
            
            // $checker = $this->get_zread_data();
            // if($method != 'send_to_rob'){
            //     if (!empty($checker['details']) && date('Y-m-d',strtotime($checker['from'])) != date('Y-m-d')){
            //         header("Location:".base_url()."cashier/process_zread");
            //     }                
            // }

            $user_id = $user['role_id'];
            if($user_id == 1 || $user_id == 2){
                if($method == 'counter' || $method == 'tables' || $method == 'delivery' ){
                    $now = $this->site_model->get_db_now();
                    $user = $this->session->userdata('user');
                    $user_id = $user['id'];
                    $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
                    if(count($shift) > 0){
                        call_user_func_array(array($this,$method), $params);
                    }
                    else{
                        site_alert('You need to start a shift before selling.','error');
                        header("Location:".base_url()."shift");
                    }   
                }
                else                
                    call_user_func_array(array($this,$method), $params);
            }
            else{
                if($method != 'manager_call' && $method != 'manager_go_login' && $method != 'manager_reasons'){
                    // $date = $this->site_model->get_db_now(null,true);;
                    // $get_in = $this->clock_model->get_shift_id($date,$user_id);
                    // $in = 'first_in';
                    // $countin = count($get_in);
                    // if($countin > 0){
                    //     $in = 'in';
                    //     call_user_func_array(array($this,$method), $params);
                    // }
                    // else{
                    //     header("Location:".base_url()."clock");
                    // }
                    $now = $this->site_model->get_db_now();
                    $user = $this->session->userdata('user');
                    $user_id = $user['id'];
                    $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
                    if(count($shift) > 0){
                        if($method != 'index' && $method != 'summary' && $method != 'summary_orders' && $method != 'end_shift' && $method != 'read_shift_sales' && $method != 'end_day'){
                            $time = $this->site_model->get_db_now();
                            $yesterday = date('Y-m-d',strtotime($time. "-1 days"));
                            $unclosed_shifts = $this->clock_model->get_shift_id(null,$user_id,$yesterday);
                            $error = "";
                            // if(count($unclosed_shifts) > 0){
                            //     site_alert('You need to end the yesterdays shift before selling..','error');
                            //     header("Location:".base_url()."shift");
                            // }
                            // else{
                                call_user_func_array(array($this,$method), $params);
                            // }
                        }
                        else{
                            call_user_func_array(array($this,$method), $params);
                        }

                    }
                    else{
                        site_alert('You need to start a shift before selling.','error');
                        header("Location:".base_url()."shift");
                    }

                }
                else{
                    call_user_func_array(array($this,$method), $params);
                }
            }
        // } else {
        //     if (method_exists($this, $method))
        //         call_user_func_array(array($this,$method), $params);
        //     else
        //         show_404($method);
        // }
    }
    public function orders($terminal='my',$status='open',$types='all',$now='all_trans',$search_id='none',$server_id='0',$show='box'){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $args = array(
            "trans_sales.trans_ref"=>null,
            "trans_sales.terminal_id"=>TERMINAL_ID,
            "trans_sales.type_id"=>SALES_TRANS,
            "trans_sales.inactive"=>0,
        );
        if($now != 'all_trans'){
            // echo "here";
            $date = $this->site_model->get_db_now(null,true);
            // $args["DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d') <= "] = date('Y-m-d',strtotime($date. "-1 days"));
            $date_from = date('Y-m-d',strtotime($date. "-1 days"));
            $date_to = $date;
            $args["DATE(trans_sales.datetime)  BETWEEN DATE('".$date_from."') AND DATE('".$date_to."')"] = array('use'=>'where','val'=>null,'third'=>false);
            
        }
        else{
            $this->db = $this->load->database('main',true);
        }
        if($terminal != 'my')
            unset($args["trans_sales.terminal_id"]);
        if($status != 'open'){
            unset($args["trans_sales.trans_ref"]);
            if($status == 'settled'){
                $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                $args["trans_sales.inactive"] = 0;
            }else{
                 $args["trans_sales.inactive"] = 1;
            }
        }
        if($types != 'all'){
            $args["trans_sales.type"] = $types;
        }
        if($search_id != 'none'){
            $args["trans_sales.sales_id"] = array('use'=>'like','val'=>$search_id,'third'=>false);;
            if($status == 'settled'){
                $args["trans_sales.trans_ref"] = array('use'=>'or_like','val'=>$search_id,'third'=>false);
            }
        }
        if($server_id != 0){
            $args["trans_sales.waiter_id"] = $server_id;
        }
        $orders = $this->cashier_model->get_trans_sales(null,$args);
        // echo $this->cashier_model->db->last_query();
        $code = "";
        $ids = array();
        $time = $this->site_model->get_db_now();
        $this->make->sDivRow();
        $ord=array();
        $combine_cart = sess('trans_combine_cart');
        foreach ($orders as $res) {
            if($res->trans_ref == null and $res->inactive == 0){
                $status = "open";
            }else if($res->trans_ref != null and $res->inactive == 0){
                $status = "settled";
            }else{
                $status = "voided";
            }
            $ord[$res->sales_id] = array(
                "type"=>$res->type,
                "status"=>$status,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount
            );
            if($show == "box"){
                $this->make->sDivCol(6,'left',0);
                    $this->make->sDiv(array('class'=>'order-btn','id'=>'order-btn-'.$res->sales_id,'ref'=>$res->sales_id));
                        if($res->trans_ref == null and $res->inactive == 0){
                            $this->make->sBox('default',array('class'=>'box-solid'));
                        }else if($res->trans_ref != null and $res->inactive == 0){
                            $this->make->sBox('default',array('class'=>'box-solid bg-green'));
                        }else{
                            $this->make->sBox('default',array('class'=>'box-solid','style'=>'background-color: #ed4959;'));
                        }
                            $this->make->sBoxBody();
                                $this->make->sDivRow();
                                    $this->make->sDivCol(6);
                                        $splitTxt = '';
                                        if($res->split != 0){
                                            if($res->sales_id == $res->split){
                                                $splitTxt = fa('fa-code fa-lg fa-fw');
                                            }
                                            else{
                                                $splitTxt = '(From ORDER #'.$res->split.')';
                                            }
                                        }
                                        $this->make->H(5,"ORDER #".$res->sales_id." ".$splitTxt,array("style"=>'font-weight:700;'));
                                        if($res->trans_ref == null and $res->inactive == 0){
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                                        }else if($res->trans_ref != null and $res->inactive == 0){
                                            // $this->make->H(5,$res->trans_ref,array("style"=>'color:#fff'));
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                            // $this->make->H(6,'FS - '.strtoupper($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix),array("style"=>'color:#fff'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                        }else{
                                            // $this->make->H(5,$res->trans_ref,array("style"=>'color:#fff'));
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                            // $this->make->H(6,'FS - '.strtoupper($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix),array("style"=>'color:#fff'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                        }
                                        if($res->reason != null)
                                            $this->make->H(6,'('.ucwords($res->reason).')',array("style"=>'color:#fff'));
                                        $this->make->H(5,tagWord(strtoupper(ago($res->datetime,$time) ) ) );
                                    $this->make->eDivCol();
                                    $this->make->sDivCol(6);
                                        if($res->trans_ref != "")
                                            $this->make->H(4,'#'.$res->trans_ref,array('class'=>'text-center','style'=>'font-weight:bold;text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);-webkit-font-smoothing: antialiased !important;opacity: 0.8;'));
                                        else
                                            $this->make->H(4,'Order Total',array('class'=>'text-center'));
                                        $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                                        $tbl_n = "";
                                        if($res->table_id != ""){
                                            $tbl_n = " - ".strtoupper($res->table_name);
                                        }
                                        $this->make->H(5,strtoupper($res->type).$tbl_n,array('class'=>'text-center','style'=>'font-weight:bold;text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);-webkit-font-smoothing: antialiased !important;opacity: 0.8;'));
                                    $this->make->eDivCol();
                                $this->make->eDivRow();

                            $this->make->eBoxBody();
                        $this->make->eBox();
                    $this->make->eDiv();
                $this->make->eDivCol();
            }
            else if($show=='combineList'){
                $got = false;
                if(count($combine_cart) > 0){
                    foreach ($combine_cart as $key => $co) {
                        if($co['sales_id'] == $res->sales_id){
                            $got = true;
                            break;
                        }
                    }
                }
                if(!$got){
                    $this->make->sDivRow(array('class'=>'orders-list-div-btnish','id'=>'order-btnish-'.$res->sales_id));
                        $this->make->sDivCol(4);
                            $this->make->sDiv(array('style'=>'margin-left:10px;'));
                                $this->make->H(5,strtoupper($res->type)." #".$res->sales_id,array("style"=>'font-weight:700;'));
                                $tbl_n = "";
                                if($res->table_id != ""){
                                    $tbl_n = strtoupper($res->table_name);
                                    $this->make->H(5,$tbl_n,array('style'=>'font-weight:bold;text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);-webkit-font-smoothing: antialiased !important;opacity: 0.8;')); 
                                }
                                $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                            $this->make->eDiv();
                        $this->make->eDivCol();
                        $this->make->sDivCol(4);
                            $this->make->sDiv(array('style'=>'margin-left:10px;'));
                                $this->make->H(4,'BALANCE DUE',array('class'=>'text-center'));
                                $this->make->H(3,num($res->total_amount),array('class'=>'text-center','style'=>'margin-top:10px;'));
                            $this->make->eDiv();
                        $this->make->eDivCol();
                        $this->make->sDivCol(4);
                            $this->make->sDiv(array('class'=>'order-btn-right-container','style'=>'margin-left:10px;margin-right:10px;margin-top:15px;'));
                                $this->make->button(fa('fa-angle-double-right fa-lg fa-fw'),array('id'=>'add-to-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'add-btn-row btn-block counter-btn-green'));
                            $this->make->eDiv();
                        $this->make->eDivCol();
                    $this->make->eDivRow();
                }
            }
            $ids[] = $res->sales_id;
        }
        //}
        $this->make->eDivRow();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ord));
    }
    public function new_orders($terminal='my',$status='open',$types='all',$now='all_trans',$search_id='none',$server_id='0',$last_id=null){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $args = array(
            "trans_sales.trans_ref"=>null,
            "trans_sales.terminal_id"=>TERMINAL_ID,
            "trans_sales.type_id"=>SALES_TRANS,
            "trans_sales.inactive"=>0,
        );
        if($now != 'all_trans'){
            $date = $this->site_model->get_db_now(null,true);
            $args["DATE_FORMAT(trans_sales.datetime,'%Y-%m-%d')"] = $date;
        }
        else{
            $this->db = $this->load->database('main',true);
        }
        if($terminal != 'my')
            unset($args["trans_sales.terminal_id"]);
        if($status != 'open'){
            unset($args["trans_sales.trans_ref"]);
            if($status == 'settled'){
                $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                $args["trans_sales.inactive"] = 0;
            }else{
                 $args["trans_sales.inactive"] = 1;
            }
        }
        if($types != 'all'){
            $args["trans_sales.type"] = $types;
        }
        if($search_id != 'none'){
            $args["trans_sales.sales_id"] = array('use'=>'like','val'=>$search_id,'third'=>false);;
            if($status == 'settled'){
                $args["trans_sales.trans_ref"] = array('use'=>'or_like','val'=>$search_id,'third'=>false);
            }
        }
        if($server_id != 0){
            $args["trans_sales.waiter_id"] = $server_id;
        }
        if($last_id != ""){
            $args["trans_sales.sales_id >"] = $last_id;
        }
        $orders = $this->cashier_model->get_trans_sales(null,$args);
        $code = "";
        $ids = array();
        $time = $this->site_model->get_db_now();
        $ord=array();
        $combine_cart = sess('trans_combine_cart');
        foreach ($orders as $res) {
            if($res->trans_ref == null and $res->inactive == 0){
                $status = "open";
            }else if($res->trans_ref != null and $res->inactive == 0){
                $status = "settled";
            }else{
                $status = "voided";
            }
            $ord[$res->sales_id] = array(
                "type"=>$res->type,
                "status"=>$status,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount
            );
                $this->make->sDivCol(6,'left',0);
                    $this->make->sDiv(array('class'=>'order-btn','id'=>'order-btn-'.$res->sales_id,'ref'=>$res->sales_id));
                        if($res->trans_ref == null and $res->inactive == 0){
                            $this->make->sBox('default',array('class'=>'box-solid'));
                        }else if($res->trans_ref != null and $res->inactive == 0){
                            $this->make->sBox('default',array('class'=>'box-solid bg-green'));
                        }else{
                            $this->make->sBox('default',array('class'=>'box-solid','style'=>'background-color: #ed4959;'));
                        }
                            $this->make->sBoxBody();
                                $this->make->sDivRow();
                                    $this->make->sDivCol(6);
                                        $splitTxt = '';
                                        if($res->split != 0){
                                            if($res->sales_id == $res->split){
                                                $splitTxt = fa('fa-code fa-lg fa-fw');
                                            }
                                            else{
                                                $splitTxt = '(From ORDER #'.$res->split.')';
                                            }
                                        }
                                        $this->make->H(5,"ORDER #".$res->sales_id." ".$splitTxt,array("style"=>'font-weight:700;'));
                                        if($res->trans_ref == null and $res->inactive == 0){
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                            // $this->make->H(6,'FS - '.strtoupper($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix),array("style"=>'color:#888'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                                        }else if($res->trans_ref != null and $res->inactive == 0){
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                            // $this->make->H(6,'FS - '.strtoupper($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix),array("style"=>'color:#fff'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                        }else{
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                            // $this->make->H(6,'FS - '.strtoupper($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix),array("style"=>'color:#fff'));
                                            $this->make->H(6,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                        }
                                        if($res->reason != null)
                                            $this->make->H(6,'('.ucwords($res->reason).')',array("style"=>'color:#fff'));
                                        $this->make->H(5,tagWord(strtoupper(ago($res->datetime,$time) ) ) );
                                    $this->make->eDivCol();
                                    $this->make->sDivCol(6);
                                        $this->make->H(4,'Order Total',array('class'=>'text-center'));
                                        $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                                        $tbl_n = "";
                                        if($res->table_id != ""){
                                            $tbl_n = " - ".strtoupper($res->table_name);
                                        }
                                        $this->make->H(5,strtoupper($res->type).$tbl_n,array('class'=>'text-center','style'=>'font-weight:bold;text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);-webkit-font-smoothing: antialiased !important;opacity: 0.8;'));
                                    $this->make->eDivCol();
                                $this->make->eDivRow();

                            $this->make->eBoxBody();
                        $this->make->eBox();
                    $this->make->eDiv();
                $this->make->eDivCol();
            $ids[] = $res->sales_id;
        }
        //}
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ord));
    }
    public function order_view($sales_id=null,$prev=false){
        if($prev)
            $this->db = $this->load->database('main',true);

        $order = $this->get_order(false,$sales_id);
        $ord = $order['order'];
        $det = $order['details'];
        $discs = $order['discounts'];
        $charges = $order['charges'];
        $zero_rated = $order['zero_rated'];
        $total = 0;
        $totals = $this->total_trans(false,$det,$discs,$charges,$zero_rated);
        $this->make->H(3,strtoupper($ord['type'])." #".$ord['sales_id'],array('class'=>'receipt text-center'));
        $this->make->H(5,sql2DateTime($ord['datetime']),array('class'=>'receipt text-center'));
        $waiter_name = trim($ord['waiter_name']);
        if($waiter_name != "")
            $this->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-center'));
        $this->make->append('<hr>');
        $this->make->sDiv(array('class'=>'body'));
            $this->make->sUl();
                foreach ($det as $menu_id => $opt) {
                    $qty = $this->make->span($opt['qty'],array('class'=>'qty','return'=>true));
                    $name = $this->make->span($opt['name'],array('class'=>'name','return'=>true));
                    $cost = $this->make->span($opt['price'],array('class'=>'cost','return'=>true));
                    $price = $opt['price'];
                    $this->make->li($qty." ".$name." ".$cost);
                    if($opt['remarks'] != ""){
                        $remarks = $this->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                        $this->make->li($remarks);
                    }
                    if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
                        foreach ($opt['modifiers'] as $mod_id => $mod) {
                            $name = $this->make->span($mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                            $cost = "";
                            if($mod['price'] > 0 )
                                $cost = $this->make->span($mod['price'],array('class'=>'cost','return'=>true));
                            $this->make->li($name." ".$cost);
                            $price += $mod['price'];
                        }
                    }
                    $total += $opt['qty'] * $price  ;
                }

                if(count($charges) > 0){
                    foreach ($charges as $charge_id => $ch) {
                        $qty = $this->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
                        $name = $this->make->span($ch['name'],array('class'=>'name','return'=>true));
                        $tx = $ch['amount'];
                        if($ch['absolute'] == 0)
                            $tx = $ch['amount']."%";
                        $cost = $this->make->span($tx,array('class'=>'cost','return'=>true));
                        $this->make->li($qty." ".$name." ".$cost);
                    }
                }

            $this->make->eUl();
        $this->make->eDiv();
        $this->make->append('<hr>');
        $this->make->H(3,'TOTAL: '.num($totals['total']),array('class'=>'receipt text-center'));
        $this->make->H(4,'DISCOUNT: '.num($totals['discount']),array('class'=>'receipt text-center'));
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function change_order_to($sales_id=null,$old=false){
        $this->load->model('dine/cashier_model');
        $tbl_id = null;
        $type = null;
        $error = '';
        if($this->input->post('type'))
            $type = $this->input->post('type');

        if($this->input->post('tbl_id'))
            $tbl_id = $this->input->post('tbl_id');

        $this->cashier_model->update_trans_sales(array('type'=>$type,'table_id'=>$tbl_id),$sales_id);
        echo json_encode(array('error'=>$error));
    }
    public function void_order($sales_id=null,$old=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $approver = "";
        $reason = "";
        $error = '';
        if($this->input->post('reason'))
            $reason = $this->input->post('reason');
        if($this->input->post('approver'))
            $approver = $this->input->post('approver');

        if($old){
            $this->db = $this->load->database('main',true);
                $order = $this->get_order(false,$sales_id);
                $this->cashier_model->update_trans_sales(array('reason'=>$reason,'void_user_id'=>$approver,'inactive'=>1),$sales_id);
            $this->db = $this->load->database('default',true);
                $trans = $this->load_trans(false,$order,true);
                $void = $this->submit_trans(false,null,true,$sales_id);
                $this->finish_trans($void['id'],true,true);
                $this->cashier_model->update_trans_sales(array('reason'=>$reason,'void_user_id'=>$approver,'inactive'=>1),$sales_id);
            $this->db = $this->load->database('main',true);
                $print = $this->print_sales_receipt($sales_id,false);
        }
        else{
            $order = $this->get_order_header(false,$sales_id);
            
            if($order['paid'] == 0){
                $this->cashier_model->update_trans_sales(array('reason'=>$reason,'void_user_id'=>$approver,'inactive'=>1),$sales_id);
                $print = $this->print_sales_receipt($sales_id,false);
            }
            else{
                $order = $this->get_order(false,$sales_id);
                $trans = $this->load_trans(false,$order,true);
                $void = $this->submit_trans(false,null,true,$sales_id);
                $this->finish_trans($void['id'],true,true);
                $this->cashier_model->update_trans_sales(array('reason'=>$reason,'void_user_id'=>$approver,'inactive'=>1),$sales_id);
                $print = $this->print_sales_receipt($sales_id,false);
            }
        }
        echo json_encode(array('error'=>$error));
    }
    public function get_branch_details($asJson=true){
       $this->load->model('dine/setup_model');
       $details = $this->setup_model->get_branch_details();
       $det = array();
       foreach ($details as $res) {
           $det = array(
                    "id"=>$res->branch_id,
                    "code"=>$res->branch_code,
                    "name"=>$res->branch_name,
                    "desc"=>$res->branch_desc,
                    "contact_no"=>$res->contact_no,
                    "delivery_no"=>$res->delivery_no,
                    "address"=>$res->address,
                    "base_location"=>$res->base_location,
                    "currency"=>$res->currency,
                    "tin"=>$res->tin,
                    "machine_no"=>$res->machine_no,
                    "bir"=>$res->bir,
                    "permit_no"=>$res->permit_no,
                    "serial"=>$res->serial,
                    "accrdn"=>$res->accrdn,
                    "email"=>$res->email,
                    "website"=>$res->website,
                    "layout"=>base_url().'uploads/'.$res->image
                  );
       }
       if($asJson)
            echo json_encode($det);
        else
            return $det;
    }
    #TABLES
        public function tables(){
            $this->load->model('site/site_model');
            $this->load->model('dine/cashier_model');
            $this->load->helper('dine/cashier_helper');
            $this->load->helper('core/on_screen_key_helper');
            $data = $this->syter->spawn(null);
            sess_clear('trans_type_cart');
            $data['code'] = tablesPage();

            $data['add_css'] = array('css/cashier.css','css/onscrkeys.css','css/rtag.css');
            $data['add_js'] = array('js/on_screen_keys.js');
            $data['load_js'] = 'dine/cashier.php';
            $data['use_js'] = 'tablesJs';
            $data['noNavbar'] = true;
            $this->load->view('cashier',$data);
        }
        function transfer_tables(){
            $this->load->helper('dine/cashier_helper');
            $this->load->helper('core/on_screen_key_helper');
            $data = $this->syter->spawn(null,false);
            $tables = $this->get_tables(false);
            $data['code'] = tableTransfer($tables);
            $data['add_css'] = array('css/pos.css','css/onscrkeys.css', 'css/cashier.css');
            $data['add_js'] = array('js/on_screen_keys.js');
            $data['load_js'] = 'dine/cashier';
            $data['use_js'] = 'tableTransferJs';
            $this->load->view('load',$data);
        }
        function go_transfer_table($sales_id=null,$table_id=null){
            $this->load->model('dine/cashier_model');
            $error = "";
            $items = array('table_id'=>$table_id);
            $this->cashier_model->update_trans_sales($items,$sales_id);
            site_alert('Order #'.$sales_id." successfully transfered",'success');
        }
        public function get_tables($asJson=true,$tbl_id=null){
            $this->load->model('dine/cashier_model');
            $tbl = array();
            $occ = array();
            $occ_tbls = $this->cashier_model->get_occupied_tables();
            foreach ($occ_tbls as $det) {
              $occ[] = $det->table_id;
            }
            $tables = $this->cashier_model->get_tables();
            foreach ($tables as $res) {
                $status = 'green';
                if(in_array($res->tbl_id, $occ)){
                  $status = 'red';
                }
                $tbl[$res->tbl_id] = array(
                    "name"=> $res->name,
                    "top"=> $res->top,
                    "left"=> $res->left,
                    "stat"=> $status
                );
            }
            if($asJson)
                echo json_encode($tbl);
            else
                return $tbl;
        }
        public function check_occupied_tables($asJson=true){
            $this->load->model('dine/cashier_model');
            $tbls = $this->get_tables(false);
            $occ = array();
            $ucc = array();
            foreach ($tbls as $tbl_id => $val) {
                if($val['stat']=='red'){
                    $occ[] = array('id'=>$tbl_id,'name'=>$val['name']);
                }
                else{
                    $ucc[] = array('id'=>$tbl_id,'name'=>$val['name']);
                }
            }
            
            // $tbl = array();
            // $occ = array();
            // $occ_tbls = $this->cashier_model->get_occupied_tables();
            // foreach ($occ_tbls as $det) {
            //   $occ[] = array('id'=>$det->table_id,'name'=>$det->name);
            // }

            if($asJson)
                echo json_encode(array('occ'=>$occ,'ucc'=>$ucc));
            else
                return array('occ'=>$occ,'ucc'=>$ucc);
        }
        function get_table_orders($asJson=true,$tbl_id=null){
            $this->load->model('dine/cashier_model');
            $this->load->model('site/site_model');
            $args = array();
            $args["trans_sales.trans_ref  IS NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["trans_sales.inactive"] = 0;
            $args["trans_sales.table_id"] = $tbl_id;
            $orders = $this->cashier_model->get_trans_sales(null,$args);
            $time = $this->site_model->get_db_now();
            $this->make->sDivRow();
            $ord=array();
            foreach ($orders as $res) {
                $status = "open";
                if($res->trans_ref != "")
                    $status = "settled";
                $ord[$res->sales_id] = array(
                    "type"=>$res->type,
                    "status"=>$status,
                    "user_id"=>$res->user_id,
                    "name"=>$res->username,
                    "terminal_id"=>$res->terminal_id,
                    "terminal_name"=>$res->terminal_name,
                    "shift_id"=>$res->shift_id,
                    "datetime"=>$res->datetime,
                    "amount"=>$res->total_amount
                );
                $this->make->sDivCol(4,'left',0);
                        $this->make->sDiv(array('class'=>'order-btn','id'=>'order-btn-'.$res->sales_id,'ref'=>$res->sales_id));
                            if($res->trans_ref == null){
                                $this->make->sBox('default',array('class'=>'box-solid'));
                            }else{
                                $this->make->sBox('default',array('class'=>'box-solid bg-green'));
                            }
                                $this->make->sBoxBody();
                                    $this->make->sDivRow();
                                        $this->make->sDivCol(6);
                                            $this->make->sDiv(array('style'=>'margin-left:20px;'));
                                                $this->make->H(5,strtoupper($res->type)." #".$res->sales_id,array("style"=>'font-weight:700;'));
                                                if($res->trans_ref == null){
                                                    $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                                    $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                                                }else{
                                                    $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                                    $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                                }
                                                $this->make->H(5,tagWord(strtoupper(ago($res->datetime,$time) ) ) );
                                            $this->make->eDiv();
                                        $this->make->eDivCol();
                                        $this->make->sDivCol(6);
                                            $this->make->H(4,'Order Total',array('class'=>'text-center'));
                                            $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                                        $this->make->eDivCol();
                                    $this->make->eDivRow();
                                    $this->make->sDivRow();
                                        $this->make->sDivCol(6);
                                            $this->make->button(fa('fa-exchange fa-lg fa-fw').' Transfer Table',array('id'=>'transfer-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-orange'));
                                        $this->make->eDivCol();
                                        $this->make->sDivCol(6);
                                            $this->make->button(fa('fa-print fa-lg fa-fw').' Print Billing',array('id'=>'print-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'transfer-btns btn-block tables-btn-green'));
                                        $this->make->eDivCol();
                                    $this->make->eDivRow();
                                $this->make->eBoxBody();
                            $this->make->eBox();
                        $this->make->eDiv();
                $this->make->eDivCol();
            }
            $this->make->eDivRow();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'ids'=>$ord));
        }
    #CHARGES
        function get_charges($asJson=true){
            $this->load->model('dine/cashier_model');
            $this->load->model('dine/settings_model');
            $charges = $this->settings_model->get_charges();
            $discs = array();
            $this->make->sDivRow();
                foreach ($charges as $res) {
                    $text = num($res->charge_amount);
                    if($res->absolute == 0){
                        $text .= " %";
                    }
                    $this->make->sDivCol(12);
                        $this->make->button("[".strtoupper($res->charge_code)."] ".strtoupper($res->charge_name)." <br> ".$text,
                                            array('id'=>'charges-btn-'.$res->charge_id,'class'=>'disc-btn-row btn-block counter-btn-orange double'));
                    $this->make->eDivCol();
                    $ids[$res->charge_id] = array(
                        "charge_code"=>$res->charge_code,
                        "charge_name"=>$res->charge_name,
                        "charge_amount"=>$res->charge_amount,
                        "no_tax"=>$res->no_tax,
                        "absolute"=>$res->absolute
                    );
                }
            $this->make->eDivRow();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'ids'=>$ids));
        }
    #DISCOUNTS
        function get_discounts($asJson=true){
            $this->load->model('dine/cashier_model');
            $this->load->model('dine/settings_model');
            $trans_disc_cart = sess('trans_disc_cart');
            $typeCN = sess('trans_type_cart');
            $discounts = $this->settings_model->get_receipt_discounts();
            $discs = array();
            $this->make->sDivRow();
                foreach ($discounts as $res) {
                    $this->make->sDivCol(12);
                        $this->make->button("[".strtoupper($res->disc_code)."] ".strtoupper($res->disc_name),array('id'=>'item-disc-btn-'.$res->disc_code,'class'=>'disc-btn-row btn-block counter-btn-green'));
                    $this->make->eDivCol();
                    $ids[$res->disc_code] = array(
                        "disc_code"=>$res->disc_code,
                        "disc_id"=>$res->disc_id,
                        "disc_name"=>$res->disc_name,
                        "disc_rate"=>$res->disc_rate,
                        "no_tax"=>$res->no_tax
                    );
                    $guest = null;
                    if(isset($typeCN[0]['guest']))
                        $ids[$res->disc_code]['guest'] = $typeCN[0]['guest'];

                    if(isset($trans_disc_cart[$res->disc_code])){
                        $row = $trans_disc_cart[$res->disc_code];
                        $ids[$res->disc_code]['guest'] = $row['guest'];
                        $ids[$res->disc_code]['disc_type'] = $row['disc_type'];
                        foreach ($row['persons'] as $code => $per) {
                            $ids[$res->disc_code]['persons'][$code] = array(
                                'name' => $per['name'],
                                'code' => $per['code'],
                                'bday' => $per['bday']
                            );
                        }
                    }
                }
            $this->make->eDivRow();
            $code = $this->make->code();

            echo json_encode(array('code'=>$code,'ids'=>$ids));
        }
        public function remove_person_disc($disc=null,$code=null){
            $trans_disc_cart = sess('trans_disc_cart');
            $persons = array();
            if(isset($trans_disc_cart[$disc]['persons'])){
             $persons = $trans_disc_cart[$disc]['persons'];
            }
            unset($persons[$code]);
            $trans_disc_cart[$disc]['persons'] = $persons;
            sess_initialize('trans_disc_cart',$trans_disc_cart);
            echo json_encode($trans_disc_cart[$disc]);
        }
        public function load_disc_persons($disc=null){
            $trans_disc_cart = sess('trans_disc_cart');
                $persons = array();
            if(isset($trans_disc_cart[$disc]['persons'])){
                $persons = $trans_disc_cart[$disc]['persons'];
            }
            $this->make->sUl(array('class'=>'ul-hover-blue'));
            $items = array();
            foreach ($persons as $res) {
                $this->make->sLi(array('id'=>'disc-person-'.$res['code'],'class'=>'disc-person','style'=>'padding:5px;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #ddd;'));
                    $this->make->H(4,$res['code']." ".$res['name']." ".$res['bday'],array('style'=>'margin:0;padding:0;margin-left:10px;'));            
                $this->make->eLi();   
                $items[$res['code']] = array(
                    "name"=> $res['code'],
                    "bday"=> $res['bday'],
                    "disc"=> $disc
                );
            }
            $this->make->eUl();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'items'=>$items));
        }
        public function add_person_disc(){
           $trans_disc_cart = sess('trans_disc_cart');
           $persons = array();
           if(isset($trans_disc_cart[$this->input->post('disc-disc-code')]['persons'])){
             $persons = $trans_disc_cart[$this->input->post('disc-disc-code')]['persons'];
           }
           $error = "";
           $items = array();
           $bday = null;
           if($this->input->post('disc-cust-bday'))
               $bday = $this->input->post('disc-cust-bday');
           
           if(!isset($persons[$this->input->post('disc-cust-code')])){
               if(count($persons) >= $this->input->post('guests')){
                $error = "Person is in limit with the no of guest.";
               }
               else{
                   $persons[$this->input->post('disc-cust-code')] = array(
                        "name"  => $this->input->post('disc-cust-name'),
                        "code"  => $this->input->post('disc-cust-code'),
                        "bday"  => $bday
                   );                    
               }
           }
           else{
            $error = "Person is ALready added.";
           }
           $trans_disc_cart[$this->input->post('disc-disc-code')]['persons'] = $persons;
           sess_initialize('trans_disc_cart',$trans_disc_cart);

            $this->make->sUl(array('class'=>'ul-hover-blue'));
            $items = array();
            foreach ($persons as $res) {
                $this->make->sLi(array('id'=>'disc-person-'.$res['code'],'class'=>'disc-person','style'=>'padding:5px;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #ddd;'));
                    $this->make->H(4,$res['code']." ".$res['name']." ".$res['bday'],array('style'=>'margin:0;padding:0;margin-left:10px;'));            
                $this->make->eLi();   
                $items[$res['code']] = array(
                    "name"=> $res['code'],
                    "bday"=> $res['bday'],
                    "disc"=> $this->input->post('disc-disc-code')
                );
            }
            $this->make->eUl();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'items'=>$items,'error'=>$error));
        }
        // public function add_trans_disc($trans_id=null){
        //    $trans_disc_cart = sess('trans_disc_cart');
        //    $items = array();
        //    $addedAlready="";
        //    if($this->input->post('type') == 'item'){
        //         if(isset($trans_disc_cart[$this->input->post('disc-disc-id')])){
        //             $items = $trans_disc_cart[$this->input->post('disc-disc-id')]['items'];
        //         }
        //         if(!in_array($this->input->post('line'), $items)){
        //             $items[] = $this->input->post('line');
        //         }
        //         else{
        //             $addedAlready="yes";
        //         }
        //    }
        //    $bday = null;
        //    if($this->input->post('disc-cust-bday'))
        //        $bday = $this->input->post('disc-cust-bday');

        //    $row = array(
        //         "name"  => $this->input->post('disc-cust-name'),
        //         "code"  => $this->input->post('disc-cust-code'),
        //         "bday"  => $bday,
        //         "guest" => $this->input->post('disc-cust-guest'),
        //         "disc_rate" => $this->input->post('disc-disc-rate'),
        //         "disc_code" => $this->input->post('disc-disc-code'),
        //         "disc_type" => $this->input->post('type'),
        //         "no_tax" => $this->input->post('disc-no-tax'),
        //         "items" => $items
        //    );
        //    $sess = sess_add('trans_disc_cart',$row,$this->input->post('disc-disc-id'));
        //    $sess['addedAlready'] = $addedAlready;

        //    if($this->input->post('disc-no-tax') == 1){
        //      $trans_cart = sess('trans_cart');
        //      if($this->input->post('type') == 'item'){
        //          foreach ($trans_cart as $line_id => $opt) {
        //             if(in_array($line_id, $items)){
        //                 $opt['no_tax'] = 1;
        //                 $trans_cart[$line_id] = $opt;
        //             }
        //          }
        //      }
        //      else{
        //          foreach ($trans_cart as $line_id => $opt) {
        //             $opt['no_tax'] = 1;
        //             $trans_cart[$line_id] = $opt;
        //          }            
        //      }
        //      sess_initialize('trans_cart',$trans_cart);
        //      // echo var_dump($trans_cart);
        //    }
        //    echo json_encode($sess);
        // }
        public function add_trans_disc(){
           $trans_disc_cart = sess('trans_disc_cart');
           $disc_cart = array();
           $error = "";
           if(isset($trans_disc_cart[$this->input->post('disc-disc-code')])){
            $disc_cart = $trans_disc_cart[$this->input->post('disc-disc-code')];
           }
           if($this->input->post('guests') > 0){
            if(isset($disc_cart['persons']) && count($disc_cart['persons']) <= $this->input->post('guests')){
                $disc_cart['guest'] =  $this->input->post('guests'); 
                $disc_cart['disc_rate'] =  $this->input->post('disc-disc-rate'); 
                $disc_cart['disc_code'] =  $this->input->post('disc-disc-code'); 
                $disc_cart['disc_id'] =  $this->input->post('disc-disc-id'); 
                $disc_cart['disc_type'] =  $this->input->post('type'); 
                $disc_cart['no_tax'] =  $this->input->post('disc-no-tax'); 
                $trans_disc_cart[$this->input->post('disc-disc-code')] = $disc_cart;
                sess_initialize('trans_disc_cart',$trans_disc_cart);
                // echo var_dump($trans_disc_cart);
            }
            else{
                $error = "Invalid No. of Persons";
            }
           }
           else{
            $error = "Invalid total No. Of Guests";
           }
           echo json_encode(array('error'=>$error));
        }
        public function del_trans_disc($disc_code=null){
           $trans_disc_cart = sess('trans_disc_cart');
           unset($trans_disc_cart[$disc_code]);
           sess_initialize('trans_disc_cart',$trans_disc_cart);
           
        }
        // public function del_trans_disc($disc_id=null,$trans_id=null){
        //    if($trans_id != null){
        //      $trans_disc_cart = sess('trans_disc_cart');
        //      if(count($trans_disc_cart) > 0 ){
        //         if(isset($trans_disc_cart[$disc_id])){
        //             $items = $trans_disc_cart[$disc_id]['items'];
        //             if(($key = array_search($trans_id, $items)) !== false) {
        //                 unset($items[$key]);
        //                 #
        //                 if($trans_disc_cart[$disc_id]['no_tax'] == 1){
        //                     $trans_cart = sess('trans_cart');
        //                     foreach ($trans_cart as $line_id => $opt) {
        //                        if($line_id == $key){
        //                            $opt['no_tax'] = 0;
        //                            $trans_cart[$line_id] = $opt;
        //                        }
        //                     }
        //                     sess_initialize('trans_cart',$trans_cart);                    
        //                 }
        //                 #
        //             }
        //             $trans_disc_cart[$disc_id]['items'] = $items;
        //             $sess = sess_add('trans_disc_cart',$row,$disc_id);
                    
        //         }
        //      }
        //    }
        //    else{
        //        $trans_disc_cart = sess('trans_disc_cart');
        //        if($trans_disc_cart[$disc_id]['no_tax'] == 1){
        //            $trans_cart = sess('trans_cart');
        //            foreach ($trans_cart as $line_id => $opt) {
        //                 $opt['no_tax'] = 0;
        //                 $trans_cart[$line_id] = $opt;
        //            }
        //            sess_initialize('trans_cart',$trans_cart);
        //        }
        //        sess_delete('trans_disc_cart',$disc_id);
        //    }
        // }
    #COUNTER
    public function counter($type=null,$sales_id=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/cashier_helper');
        $data = $this->syter->spawn(null);
        $loaded = null;
        $order = array();

        $loc_res = $this->site_model->get_tbl('settings',array(),array(),null,true,'*',null,1);
        $local_tax = $loc_res[0]->local_tax;
        $kitchen_printer = "";
        if(iSetObj($loc_res[0],'kitchen_printer_name') != ""){
            $kitchen_printer = iSetObj($loc_res[0],'kitchen_printer_name');
        }

        if($sales_id != null){
            $order = $this->get_order(false,$sales_id);
            $trans = $this->load_trans(false,$order);
            $time = $trans['datetime'];
            $type = $type." #".$order['order']['sales_id'];
            $loaded = "loaded";
        }
        else{
            $trans = $this->new_trans(false,$type);
            $time = $trans['datetime'];
        }
        if(isset($order['order']))
            $order = $order['order'];
        $typeCN = sess('trans_type_cart');
        
        if(isset($typeCN[0]['table'])){
            $error = $this->check_tbl_activity($typeCN[0]['table'],false);
            if($error == ""){
                $this->update_tbl_activity($typeCN[0]['table']);
            }
            else{
                site_alert($error,'error');
                header("Location:".base_url()."cashier");
            }
        }

        $data['code'] = counterPage($type,$time,$loaded,$order,$typeCN,$local_tax,$kitchen_printer);
        // $data['add_css'] = 'css/cashier.css';
        $data['add_css'] = array('css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'counterJs';
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function update_tbl_activity($tbl_id=null,$remove=false){
        $active = $this->site_model->get_db_now();
        if(!$remove){
            $items = array(
                'tbl_id'=>$tbl_id,
                'pc_id'=>PC_ID,
                'last_activity'=>date2SqlDateTime($active)
            );
            $res = $this->site_model->get_tbl('table_activity',array('tbl_id'=>$tbl_id,'pc_id'=>PC_ID));
            if(count($res)>0){
                $this->site_model->update_tbl('table_activity','id',$items,$res[0]->id);            
            }
            else{
                $this->site_model->add_tbl('table_activity',$items);            
            }        
        }
        else{
            $this->site_model->delete_tbl('table_activity',array('pc_id'=>PC_ID));
        }
    }
    public function check_tbl_activity($tbl_id=null,$asJson=true){
        $error = "";
        $res = $this->site_model->get_tbl('table_activity',array('tbl_id'=>$tbl_id));
        if(count($res)>0){
            $error = "PC ".$res[0]->pc_id." is currently editing this table";            
        }
        else{
            $error = "";
        }
        if($asJson){
            echo json_encode(array('error'=>$error));
        }
        else{
            return $error;
        }
    }
    public function combine($type=null,$sales_id=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/cashier_helper');
        sess_clear('trans_combine_cart');
        $data = $this->syter->spawn(null);
        $order = $this->get_order(false,$sales_id);
        $trans = $this->load_trans(false,$order);
        $time = $trans['datetime'];
        $type = $type." #".$order['order']['sales_id'];
        sess_add('trans_combine_cart',array('sales_id'=>$order['order']['sales_id'],'balance'=>$order['order']['balance']));

        $data['code'] = combinePage($type,$time,$order['order']);
        $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'combineJs';
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function save_combine(){
        $trans_combine_cart = sess('trans_combine_cart');
        $main_sales_id = null;
        $trans_cart = array();
        $trans_mod_cart = array();
        $ctr = 1;
        $liner = 0;
        foreach ($trans_combine_cart as $key => $co) {
            $sales_id = $co['sales_id'];
            $order = $this->get_order(false,$sales_id);
            $header = $order['order'];
            $details = $order['details'];
            $com = "";
            if($ctr == 1){
                $main_sales_id = $sales_id;
                foreach ($details as $line_id => $menu){
                    $trans_cart[$line_id] = array(
                        "menu_id"=> $menu['menu_id'],
                        "name"=> $menu['name'],
                        "cost"=> $menu['price'],
                        "qty"=> $menu['qty'],
                        "remarks"=> $menu['remarks'],
                    );
                    if(count($menu['modifiers']) > 0){
                        foreach ($menu['modifiers'] as $mod) {
                            if($mod['line_id'] == $line_id){
                                $trans_mod_cart[] = array(
                                    "trans_id"=>$mod['line_id'],
                                    "mod_id"=>$mod['id'],
                                    "menu_id"=>$menu['menu_id'],
                                    "menu_name"=>$menu['name'],
                                    "name"=>$mod['name'],
                                    "cost"=>$mod['price'],
                                    "qty"=>$mod['qty']
                                );
                            }
                        }#END FOR EACH
                    }#END IF
                    $liner = $line_id;
                }#END MAIN FOR EACH
            }
            else{
                foreach ($details as $line_id => $menu){
                    $liner++;
                    $trans_cart[$liner] = array(
                        "menu_id"=> $menu['menu_id'],
                        "name"=> $menu['name'],
                        "cost"=> $menu['price'],
                        "qty"=> $menu['qty'],
                        "remarks"=> $menu['remarks'],
                    );
                    if(count($menu['modifiers']) > 0){
                        foreach ($menu['modifiers'] as $mod) {
                            if($mod['line_id'] == $line_id){
                                $trans_mod_cart[] = array(
                                    "trans_id"=>$liner,
                                    "mod_id"=>$mod['id'],
                                    "menu_id"=>$menu['menu_id'],
                                    "menu_name"=>$menu['name'],
                                    "name"=>$mod['name'],
                                    "cost"=>$mod['price'],
                                    "qty"=>$mod['qty']
                                );
                            }
                        }#END FOR EACH
                    }#END IF
                }#END MAIN FOR EACH
                $this->cashier_model->update_trans_sales(array('inactive'=>1,'reason'=>'combined to receipt# '.$main_sales_id),$sales_id);
                $com .= $sales_id.",";
            }#END IF
            $ctr++;
        }
        $sale = $this->submit_trans(false,null,false,null,$trans_cart,$trans_mod_cart);
        $com = substr($com, 0,-1);
        site_alert('Success! Reciept #'.$com.' combined to reciept#'.$sale['id'],'success');
    }
    public function split($type=null,$sales_id=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/cashier_helper');
        sess_clear('trans_split_cart');
        $data = $this->syter->spawn(null);
        $order = $this->get_order(false,$sales_id);
        $trans = $this->load_trans(false,$order);
        $time = $trans['datetime'];
        $type = $type." #".$order['order']['sales_id'];
        $data['code'] = splitPage($type,$time,$sales_id,$order['order']);
        $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'splitJs';
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function save_split($id=null){
        $trans_split_cart = sess('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');

        $ctr = 1;
        $error = "";
        foreach ($trans_cart as $trans_id => $tr) {
            if($tr['qty'] > 0){
                $error = "Please Assign All Items";
                break;
            }
        }

        if($error == ""){
            $split_into = "";

            foreach ($trans_split_cart as $num => $row) {
                if($ctr > 1){
                    $counter = sess('counter');
                    unset($counter['sales_id']);
                    $this->session->set_userData('counter',$counter);
                }
                $sale = $this->submit_trans(false,null,false,null,$row,null,false,$id);
                $this->print_sales_receipt($sale['id'],false);
                $ctr++;
                $split_into .= " #".$sale['id'].", ";
            }
            site_alert('Success! Transaction split into '.substr($split_into,0,-1),'success');
        }

        echo json_encode(array('error'=>$error));
    }
    public function even_split($num=null,$sales_id=null){
        sess_clear('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $ctr = 1;
        $error = "";
        $split_into = "";
        foreach ($trans_cart as $trans_id => $opt) {
            $opt['cost'] = $opt['cost']/$num;
            $trans_cart[$trans_id] = $opt;
        }
        foreach ($trans_mod_cart as $trans_mod_id => $mod) {
            $mod['cost'] = $mod['cost']/$num;
            $trans_mod_cart[$trans_mod_id] = $mod;
        }
        // for ($i=1; $i <= $num; $i++) {
        //     if($ctr > 1){
        //         $counter = sess('counter');
        //         unset($counter['sales_id']);
        //         $this->session->set_userData('counter',$counter);
        //     }
        //     // $sale = $this->submit_trans(false,null,false,null,$trans_cart,$trans_mod_cart);
        //     $ctr++;
        //     $split_into .= " #".$sale['id'].", ";
        // }
        sess_initialize("trans_cart",$trans_cart);
        sess_initialize("trans_mod_cart",$trans_mod_cart);
        $totals = $this->total_trans(false);
        $split_total = $totals['total'];
        $splits = array('total'=>$split_total,'by'=>$num);
        $this->print_sales_receipt($sales_id,false,false,false,$splits);
        // site_alert('Success! Transaction split into '.substr($split_into,0,-1),'success');
        echo json_encode(array('error'=>$error));
    }
    public function new_split_block($num=0){
        $code = "";
        $trans_split_cart = sess('trans_split_cart');
        // if(count($trans_split_cart) > 0){
        //     $num = max(array_keys($trans_split_cart)) + 1;
        // }
        // else{
        //     if($num > 0){
        //         $num += 1;
        //     }
        // }
        $this->make->sDivCol(4);
            $this->make->sDiv(array('class'=>'sel-div','id'=>'sel-div-'.$num));
                $this->make->sDiv(array('class'=>'sel-trans-list'));
                    $this->make->sUl(array("style"=>'padding-top:10px;'));
                        // $this->make->li('<span class="qty">100</span><span class="name">100</span><span class="cost">100</span>');
                    $this->make->eUl();
                $this->make->eDiv();
                $this->make->sDivRow();
                    $this->make->sDivCol(4);
                        $this->make->button(fa('fa-plus fa-lg fa-fw'),array('class'=>'add-btn btn-block counter-btn-green'));
                    $this->make->eDivCol();
                    $this->make->sDivCol(4);
                        $this->make->button(fa('fa-minus fa-lg fa-fw'),array('class'=>'del-btn btn-block counter-btn-red'));
                    $this->make->eDivCol();
                    $this->make->sDivCol(4);
                        $this->make->button(fa('fa-trash-o fa-lg fa-fw'),array('class'=>'remove-btn btn-block counter-btn-orange'));
                    $this->make->eDivCol();
                $this->make->eDivRow();
            $this->make->eDiv();
        $this->make->eDivCol();

        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'num'=>$num));
    }
    public function clear_split(){
        $code = "";
        $trans_split_cart = sess('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $upds = array();
        if(count($trans_split_cart) > 0){
            foreach ($trans_split_cart as $num => $trans) {
                foreach ($trans as $line_id => $row) {
                    $tr_cart = $trans_cart[$line_id];
                    $tr_cart['qty'] += $row['qty'];

                    $trans_cart[$line_id] = $tr_cart;
                    sess_update('trans_cart',$line_id,$trans_cart[$line_id]);
                    $upds[$line_id] = $tr_cart['qty'];
                }
            }
        }
        sess_clear('trans_split_cart');
        echo json_encode(array('content'=>$upds));
    }
    public function add_split_block($num=1,$line_id=null){
        $code = "";
        $trans_split_cart = sess('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $from_qty = 0;
        if(isset($trans_cart[$line_id])){
           if(!isset($trans_split_cart[$num][$line_id])){
               $trans_split_cart[$num][$line_id] = $trans_cart[$line_id];
               $trans_split_cart[$num][$line_id]['qty'] = 0;
           }
           $tr_cart = $trans_cart[$line_id];
           $tr_cart['qty'] -= 1;
           $from_qty = $tr_cart['qty'];
           $trans_cart[$line_id] = $tr_cart;

           $tr_spl_cart = $trans_split_cart[$num][$line_id];
           $tr_spl_cart['qty'] += 1;
           $split_qty = $tr_spl_cart['qty'];
           $trans_split_cart[$num][$line_id] = $tr_spl_cart;

           sess_update('trans_split_cart',$num,$trans_split_cart[$num]);
           sess_update('trans_cart',$line_id,$trans_cart[$line_id]);
           // echo var_dump(sess('trans_split_cart'));
        }
        // $code = $this->make->code();
        echo json_encode(array('from_qty'=>$from_qty,'split_qty'=>$split_qty));
    }
    public function minus_split_block($num=1,$line_id=null){
        $code = "";
        $trans_split_cart = sess('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $from_qty = 0;
        if(isset($trans_cart[$line_id])){
           if(!isset($trans_split_cart[$num][$line_id])){
               $trans_split_cart[$num][$line_id] = $trans_cart[$line_id];
           }
           $tr_cart = $trans_cart[$line_id];
           $tr_cart['qty'] += 1;
           $from_qty = $tr_cart['qty'];
           $trans_cart[$line_id] = $tr_cart;

           $tr_spl_cart = $trans_split_cart[$num][$line_id];
           $tr_spl_cart['qty'] -= 1;
           $split_qty = $tr_spl_cart['qty'];
           $trans_split_cart[$num][$line_id] = $tr_spl_cart;
           sess_update('trans_split_cart',$num,$trans_split_cart[$num]);
           sess_update('trans_cart',$line_id,$trans_cart[$line_id]);
           // echo var_dump(sess('trans_split_cart'));
        }
        // $code = $this->make->code();
        echo json_encode(array('from_qty'=>$from_qty,'split_qty'=>$split_qty));
    }
    public function remove_split_block($num=null){
        $code = "";
        $trans_split_cart = sess('trans_split_cart');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $upds = array();
        if(isset($trans_split_cart[$num]) ){
            foreach ($trans_split_cart[$num] as $line_id => $row) {
                $tr_cart = $trans_cart[$line_id];
                $tr_cart['qty'] += $row['qty'];

                $trans_cart[$line_id] = $tr_cart;
                sess_update('trans_cart',$line_id,$trans_cart[$line_id]);
                $upds[$line_id] = $tr_cart['qty'];
            }
        }

        if(isset($trans_split_cart[$num]))
            sess_delete('trans_split_cart',$num);
        echo json_encode(array('content'=>$upds));
    }
    #Delivery
    public function delivery(){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/cashier_helper');
        $data = $this->syter->spawn(null);
        $data['code'] = deliveryPage();
        $data['add_css'] = array('css/cashier.css','css/virtual_keyboard.css');
        $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'deliveryJs';
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function pickup(){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/cashier_helper');
        $data = $this->syter->spawn(null);
        $data['code'] = deliveryPage(array(),'pickup');
        $data['add_css'] = array('css/cashier.css','css/virtual_keyboard.css');
        $data['add_js'] = array('js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'deliveryJs';
        $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function search_customers($search=null){
        $this->load->model('dine/customers_model');
        $found = array();
        if($search != ""){
            $found = $this->customers_model->search_customers($search);
        }
        $results = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $results[$res->cust_id] = array('name'=>ucwords(strtolower($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix)),'phone'=>$res->phone);
            }
        }
        echo json_encode($results);
    }
    public function search_gift_card($search = null)
    {
        $this->load->model('dine/gift_cards_model');

        if (is_null($search)) {
            echo json_encode(array('error'=>'Please enter gift card code'));
            return false;
        }
        $search = str_replace("-", "", $search);
        $return = $this->gift_cards_model->get_gift_card_info($search,false);

        if (empty($return)) {
            echo json_encode(array('error'=>'Gift card does not exist'));
        } else {
            if ($return[0]->inactive == 1)
                echo json_encode(array('error'=>'Gift card has already been used'));
            else
                echo json_encode(array('gc_id'=>$return[0]->gc_id,'card_no'=>$return[0]->card_no,'amount'=>number_format($return[0]->amount,2)));
        }
        return false;
    }
    public function search_coupon($search = null)
    {
        if (is_null($search)) {
            echo json_encode(array('error'=>'Please enter coupon card code'));
            return false;
        }

        $search = str_replace("-", "", $search);
        $today = date2Sql($this->site_model->get_db_now('sql'));
        $args['card_no'] = $search;
        $return = $this->site_model->get_tbl('coupons',$args);

        if (empty($return)) {
            echo json_encode(array('error'=>'Coupon does not exist'));
        } else {
            if ($return[0]->inactive == 1)
                echo json_encode(array('error'=>'Coupon has already been used.'));
            else if( strtotime($today) > strtotime($return[0]->expiration)  ){
                echo json_encode(array('error'=>'Coupon has expired.'));
            }
            else
                echo json_encode(array('coupon_id'=>$return[0]->coupon_id,'card_no'=>$return[0]->card_no,'amount'=>number_format($return[0]->amount,2)));
        }
        return false;
    }
    public function get_customers($id=null){
        $this->load->model('dine/customers_model');
        $found = $this->customers_model->get_customer($id);
        $results = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $results[$res->cust_id] = array(
                    'cust_id'=>ucwords(strtolower($res->cust_id)),
                    'fname'=>ucwords(strtolower($res->fname)),
                    'lname'=>ucwords(strtolower($res->lname)),
                    'mname'=>ucwords(strtolower($res->mname)),
                    'suffix'=>ucwords(strtolower($res->suffix)),
                    'email'=>ucwords(strtolower($res->email)),
                    'phone'=>ucwords(strtolower($res->phone)),
                    'street_no'=>ucwords(strtolower($res->street_no)),
                    'street_address'=>ucwords(strtolower($res->street_address)),
                    'city'=>ucwords(strtolower($res->city)),
                    'region'=>ucwords(strtolower($res->region)),
                    'zip'=>$res->zip
                );
            }
        }
        echo json_encode($results);
    }
    public function get_waiters($id=null){
        $this->load->model('core/user_model');
        $found = $this->user_model->get_users($id);
        $results = array();
        if(count($found) > 0 ){
            $this->make->sDivRow();
                foreach ($found as $res) {
                    // ucwords(strtolower($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix))
                    $this->make->sDivCol(4);
                        $this->make->sDiv(array('style'=>'margin:2px'));
                        $this->make->button(ucwords(strtolower($res->username)),
                                            array('id'=>'waiters-btn-'.$res->id,'class'=>'btn-block counter-btn-silver'));
                        $this->make->eDiv();
                    $this->make->eDivCol();
                    $results[$res->id] = array(
                        'user_id'=>ucwords(strtolower($res->id)),
                        'fname'=>ucwords(strtolower($res->fname)),
                        'uname'=>ucwords(strtolower($res->username)),
                        'lname'=>ucwords(strtolower($res->lname)),
                        'mname'=>ucwords(strtolower($res->mname)),
                        'suffix'=>ucwords(strtolower($res->suffix)),
                        'full_name'=>ucwords(strtolower($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix))
                    );
                }
            $this->make->eDivRow();
        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$results));
    }
    public function new_trans($asJson=true,$type=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        sess_clear('trans_mod_cart');
        sess_clear('trans_cart');
        // sess_clear('trans_items_cart');
        sess_clear('counter');
        sess_clear('trans_disc_cart');
        sess_clear('trans_charge_cart');
        $time = $this->site_model->get_db_now();
        $user = $this->session->userdata('user');
        // $get_shift = $this->clock_model->get_shift_id(date2Sql($time),$user['id']);
        $get_shift = $this->clock_model->get_shift_id(null,$user['id']);
        $shift_id = 0;
        if(count($get_shift) > 0){
            $shift_id = $get_shift[0]->shift_id;
        }
        $counter = array(
            "datetime"=> $time,
            "sales_id"=> null,
            "shift_id"=> $shift_id,
            "terminal_id"=> TERMINAL_ID,
            "user_id"=> $user['id'],
            "type"=> $type
        );
        if($type != 'dinein')
            sess_clear('trans_type_cart');

        $this->session->set_userData('counter',$counter);
        if($asJson)
            echo json_encode($counter);
        else
            return $counter;
    }
    public function update_trans($update=null,$value=null,$unset=false){
        $counter = sess('counter');
        if($unset){
            if(isset($counter[$update])){
                unset($counter[$update]);
            }
        }
        else{
            $counter[$update] = $value;
            if($update == 'zero_rated'){
                $trans_cart = sess('trans_cart');
                foreach ($trans_cart as $line_id => $opt) {
                    $opt['no_tax'] = $value;
                    $trans_cart[$line_id] = $opt;
                }
                sess_initialize('trans_cart',$trans_cart);
            }
        }
        sess_initialize('counter',$counter);
        echo json_encode($counter);
    }
    public function load_trans($asJson=true,$trans=null,$noSalesId=false){
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        sess_clear('trans_mod_cart');
        sess_clear('trans_cart');
        sess_clear('counter');
        sess_clear('trans_disc_cart');
        sess_clear('trans_charge_cart');
        sess_clear('trans_type_cart');
        $time = $this->site_model->get_db_now();
        $user = $this->session->userdata('user');
        $get_shift = $this->clock_model->get_shift_id(null,$user['id']);
        // $get_shift = $this->clock_model->get_shift_id(date2Sql($time),$user['id']);
        $shift_id = 0;
        if(count($get_shift) > 0){
            $shift_id = $get_shift[0]->shift_id;
        }
        $order=$trans['order'];
        $details=$trans['details'];
        $discounts = $trans['discounts'];
        $charges = $trans['charges'];
        $zero_rated=$trans['zero_rated'];
        $sales_id = $order['sales_id'];
        if($noSalesId)
            $sales_id = "";
        $counter = array(
            "datetime"=> sql2DateTime($order['datetime']),
            "shift_id"=> $shift_id,
            "sales_id"=> $sales_id,
            "terminal_id"=> TERMINAL_ID,
            "user_id"=> $user['id'],
            "type"=> $order['type']
        );
        if(count($zero_rated) > 0){
            foreach ($zero_rated as $zid => $opt) {
                if($opt['amount'] > 0){
                  $counter['zero_rated'] = 1;
                  break;
                }
            }
        }
        if(isset($order['waiter_id'])){
            $counter['waiter_id'] = $order['waiter_id'];
        }
        $trans_type_cart = array();
        if($order['type'] == 'dinein'){
            $trans_type_cart[0]['type']='dinein';
            $trans_type_cart[0]['table']=$order['table_id'];
            $trans_type_cart[0]['guest']=$order['guest'];
        }

        $trans_cart = array();
        $trans_mod_cart = array();
        $trans_disc_cart = array();
        $trans_charge_cart = array();
        foreach ($details as $line_id => $menu) {
            $trans_cart[$line_id] = array(
                "menu_id"=> $menu['menu_id'],
                "name"=> $menu['name'],
                "cost"=> $menu['price'],
                "qty"=> $menu['qty'],
                "no_tax"=> $menu['no_tax'],
                "remarks"=> $menu['remarks'],
                "kitchen_slip_printed"=>$menu['kitchen_slip_printed']
            );
            if(isset($menu['retail']))
              $trans_cart[$line_id]['retail'] = $menu['retail'];
            if(isset($menu['modifiers']) && count($menu['modifiers']) > 0){
                foreach ($menu['modifiers'] as $mod) {
                    if($mod['line_id'] == $line_id){
                        $trans_mod_cart[] = array(
                            "trans_id"=>$mod['line_id'],
                            "mod_id"=>$mod['id'],
                            "menu_id"=>$menu['menu_id'],
                            "menu_name"=>$menu['name'],
                            "name"=>$mod['name'],
                            "cost"=>$mod['price'],
                            "qty"=>$mod['qty'],
                            "kitchen_slip_printed"=>$menu['kitchen_slip_printed']
                        );
                    }
                }#END FOR EACH
            }#END IF
        }
        if(count($discounts) > 0){
            foreach ($discounts as $disc_code => $dc) {
                $trans_disc_cart[$disc_code] = array(
                    // "name"  => $dc['name'],
                    // "code"  => $dc['code'],
                    "no_tax"  => $dc['no_tax'],
                    "guest" => $dc['guest'],
                    "disc_rate" => $dc['disc_rate'],
                    "disc_id" => $dc['disc_id'],
                    "disc_code" => $dc['disc_code'],
                    "disc_type" => $dc['disc_type'],
                    "persons" => $dc['persons'],
                    // "items" => $dc['items']
                );
            }
        }
        if(count($charges) > 0){
            foreach ($charges as $charge_id => $dc) {
                $trans_charge_cart[$charge_id] = array(
                    "name"  => $dc['name'],
                    "code"  => $dc['code'],
                    "amount"  => $dc['amount'],
                    "absolute" => $dc['absolute']
                );
            }
        }

        if(isset($counter['zero_rated']) && $counter['zero_rated'] > 0){
            foreach ($trans_cart as $line_id => $opt) {
                $opt['no_tax'] = 1;
                $trans_cart[$line_id] = $opt;
            }
        }

        $this->session->set_userData('trans_cart',$trans_cart);
        $this->session->set_userData('trans_mod_cart',$trans_mod_cart);
        if(count($trans_type_cart) > 0){
            $this->session->set_userData('trans_type_cart',$trans_type_cart);
        }
        if(count($trans_disc_cart) > 0){
            $this->session->set_userData('trans_disc_cart',$trans_disc_cart);
        }
        if(count($trans_charge_cart) > 0){
            $this->session->set_userData('trans_charge_cart',$trans_charge_cart);
        }
        $this->session->set_userData('counter',$counter);

        if($asJson)
            echo json_encode($counter);
        else
            return $counter;
    }
    public function get_trans_cart($asJson=true){
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $order = null;
        foreach ($trans_cart as $trans_id => $menu) {
            if(!isset($menu['retail'])){
                $order[$trans_id] =  array(
                    "menu_id"=> $menu['menu_id'],
                    "name"=> $menu['name'],
                    "cost"=> $menu['cost'],
                    "qty"=> $menu['qty'],
                    "remarks"=> $menu['remarks'],
                    "kitchen_slip_printed"=> $menu["kitchen_slip_printed"]
                );
                $mods = array();
                if(count($trans_mod_cart) > 0){
                    foreach ($trans_mod_cart as $id => $mod) {
                        if($mod['trans_id'] == $trans_id){
                            $mods[$id] = array(
                                "trans_id"=>$mod['trans_id'],
                                "mod_id"=>$mod['mod_id'],
                                "menu_id"=>$mod['menu_id'],
                                "menu_name"=>$mod['menu_name'],
                                "name"=>$mod['name'],
                                "cost"=>$mod['cost'],
                                "qty"=>$mod['qty'],
                                "kitchen_slip_printed"=>$mod["kitchen_slip_printed"]
                            );
                        }#IF
                    }#FOREACH
                }#IF
                $order[$trans_id]['modifiers'] = $mods;
            }
            else{
                $order[$trans_id] =  array(
                    "menu_id"=> $menu['menu_id'],
                    "name"=> $menu['name'],
                    "cost"=> $menu['cost'],
                    "qty"=> $menu['qty'],
                    "remarks"=> $menu['remarks'],
                    "retail"=>$menu['retail']
                );
            }

        }
        if($asJson)
            echo json_encode($order);
        else
            return $order;
    }
    public function get_trans_charges($asJson=true){
        $this->load->model('dine/settings_model');
        $trans_charge_cart = sess('trans_charge_cart');
        $counter = sess('counter');
        
        $charge = null;
        foreach ($trans_charge_cart as $charge_id => $dc) {
            $charge[$charge_id] = array(
                "name"  => $dc['name'],
                "code"  => $dc['code'],
                "amount"  => $dc['amount'],
                "absolute" => $dc['absolute']
            );
        }
        if(AUTO_ADD_SERVICE_CHARGE){
            if($counter['type'] == 'dinein'){
                if(!isset($charge[1])){
                    $serc = $this->settings_model->get_charges(1);
                    $sc = $serc[0];
                    $charge[$sc->charge_id] = array(
                        "name"  => $sc->charge_name,
                        "code"  => $sc->charge_code,
                        "amount"  => $sc->charge_amount,
                        "absolute" => $sc->absolute
                    );
                    sess_add("trans_charge_cart",$charge[$sc->charge_id],$sc->charge_id);
                }
            }
        }
        if($asJson)
            echo json_encode($charge);
        else
            return $charge;
    }
    public function get_order($asJson=true,$sales_id=null){
        /*
         * -------------------------------------------
         *   Load receipt data
         * -------------------------------------------
        */
        $this->load->model('dine/cashier_model');
        $orders = $this->cashier_model->get_trans_sales($sales_id);
        $order = array();
        $details = array();
        foreach ($orders as $res) {
            $order = array(
                "sales_id"=>$res->sales_id,
                'ref'=>$res->trans_ref,
                "type"=>$res->type,
                "table_id"=>$res->table_id,
                "table_name"=>$res->table_name,
                "guest"=>$res->guest,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "terminal_code"=>$res->terminal_code,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount,
                "balance"=>$res->total_amount - $res->total_paid,
                "paid"=>$res->paid,
                "printed"=>$res->printed,
                "inactive"=>$res->inactive,
                "waiter_id"=>$res->waiter_id,
                "void_ref"=>$res->void_ref,
                "reason"=>$res->reason,
                "waiter_name"=>ucwords(strtolower($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix)),
                "waiter_username"=>ucwords(strtolower($res->waiterusername))
                // "pay_type"=>$res->pay_type,
                // "pay_amount"=>$res->pay_amount,
                // "pay_ref"=>$res->pay_ref,
                // "pay_card"=>$res->pay_card,
            );
        }
        $order_menus = $this->cashier_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
        $order_items = $this->cashier_model->get_trans_sales_items(null,array("trans_sales_items.sales_id"=>$sales_id));
        $order_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
        $sales_payments = $this->cashier_model->get_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$sales_id));
        $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
        $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
        $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
        $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));
        $pays = array();
        foreach ($sales_payments as $py) {
            $pays[$py->payment_id] = array(
                    "sales_id"      => $py->sales_id,
                    "payment_type"  => $py->payment_type,
                    "amount"        => $py->amount,
                    "to_pay"        => $py->to_pay,
                    "reference"     => $py->reference,
                    "card_type"     => $py->card_type,
                    "card_number"   => $py->card_number,
                    "approval_code"   => $py->approval_code,
                    "user_id"       => $py->user_id,
                    "datetime"      => $py->datetime,
                );
        }
        foreach ($order_menus as $men) {
            $details[$men->line_id] = array(
                "id"=>$men->sales_menu_id,
                "menu_id"=>$men->menu_id,
                "name"=>$men->menu_name,
                "code"=>$men->menu_code,
                "price"=>$men->price,
                "qty"=>$men->qty,
                "no_tax"=>$men->no_tax,
                "discount"=>$men->discount,
                "remarks"=>$men->remarks,
                "kitchen_slip_printed"=>$men->kitchen_slip_printed
            );
            $mods = array();
            foreach ($order_mods as $mod) {
                if($mod->line_id == $men->line_id){
                    $mods[$mod->sales_mod_id] = array(
                        "id"=>$mod->mod_id,
                        "sales_mod_id"=>$mod->sales_mod_id,
                        "line_id"=>$mod->line_id,
                        "name"=>$mod->mod_name,
                        "price"=>$mod->price,
                        "qty"=>$mod->qty,
                        "discount"=>$mod->discount,
                        "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                    );
                }
            }
            $details[$men->line_id]['modifiers'] = $mods;
        }
        foreach ($order_items as $men){
            $details[$men->line_id] = array(
                "id"=>$men->sales_item_id,
                "menu_id"=>$men->item_id,
                "name"=>$men->name,
                "code"=>$men->code,
                "price"=>$men->price,
                "qty"=>$men->qty,
                "no_tax"=>$men->no_tax,
                "discount"=>$men->discount,
                "remarks"=>$men->remarks,
                "retail"=>1
            );
        }
        $discounts = array();
        foreach ($sales_discs as $dc) {
            $pcode = $dc->code;
            $bday = "";
            if($dc->bday != "")
                $bday = sql2Date($dc->bday);
            $persons[$pcode] = array(
                "name"  => $dc->name,
                "code"  => $dc->code,
                "bday"  => $bday,
                "amount" => $dc->amount,
                "disc_rate" => $dc->disc_rate,
            );
            // $
            // items = array();
            // if($dc->items != ""){
            //     $items = explode(',', $dc->items);
            // }
            $discounts[$dc->disc_code] = array(
                    // "name"  => $dc->name,
                    // "code"  => $dc->code,
                    // "bday"  => sql2Date($dc->bday),
                    "no_tax"  => $dc->no_tax,
                    "guest" => $dc->guest,
                    "disc_rate" => $dc->disc_rate,
                    "disc_id" => $dc->disc_id,
                    "disc_code" => $dc->disc_code,
                    "disc_type" => $dc->type,
                    
                    // "items" => $items
                    "persons" => $persons
            );
        }
        $tax = array();
        foreach ($sales_tax as $tx) {
            $tax[$tx->sales_tax_id] = array(
                    "sales_id"  => $tx->sales_id,
                    "name"  => $tx->name,
                    "rate" => $tx->rate,
                    "amount" => $tx->amount
                );
        }
        $no_tax = array();
        foreach ($sales_no_tax as $nt) {
            $no_tax[$nt->sales_no_tax_id] = array(
                "sales_id" => $nt->sales_id,
                "amount" => $nt->amount,
            );
        }
        $zero_rated = array();
        foreach ($sales_zero_rated as $zt) {
            $zero_rated[$zt->sales_zero_rated_id] = array(
                "sales_id" => $zt->sales_id,
                "amount" => $zt->amount,
            );
        }
        $local_tax = array();
        foreach ($sales_local_tax as $lt) {
            $local_tax[$lt->sales_local_tax_id] = array(
                "sales_id" => $lt->sales_id,
                "amount" => $lt->amount,
            );
        }
        $charges = array();
        foreach ($sales_charges as $ch) {
            $charges[$ch->charge_id] = array(
                    "name"  => $ch->charge_name,
                    "code"  => $ch->charge_code,
                    "amount"  => $ch->rate,
                    "absolute" => $ch->absolute,
                    "total_amount" => $ch->amount
                );
        }
        if($asJson)
            echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax));
        else
            return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax);
    }
    public function get_order_header($asJson=true,$sales_id=null,$args=array()){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');
        $orders = $this->cashier_model->get_trans_sales($sales_id,$args);
        $time = $this->site_model->get_db_now();
        foreach ($orders as $res) {
            $get_shift = $this->clock_model->get_shift_id(date2Sql($time),$res->user_id);
            $shift_id = $res->shift_id;
            if(count($get_shift) > 0){
                $shift_id = $get_shift[0]->shift_id;
            }
            $order = array(
                "sales_id"=>$res->sales_id,
                "type"=>$res->type,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$shift_id,
                "datetime"=>$res->datetime,
                "amount"=>numInt($res->total_amount),
                "balance"=>numInt($res->total_amount) - numInt($res->total_paid),
                "paid"=>numInt($res->paid)
            );
        }
        if($asJson)
            echo json_encode($order);
        else
            return $order;
    }
    public function get_menu_categories($asJson=true){
        $this->load->model('dine/menu_model');
        $categories = $this->menu_model->get_menu_categories(null,true);
        $json = array();
        foreach ($categories as $cat) {
            $json[$cat->menu_cat_id] = array(
                'name'=>$cat->menu_cat_name
            );
        }
        echo json_encode($json);
    }
    public function get_item_categories($asJson=true){
        $this->load->model('dine/settings_model');
        $categories = $this->settings_model->get_category(null,true);
        $json = array();
        foreach ($categories as $cat) {
            $json[$cat->cat_id] = array(
                'name'=>$cat->name
            );
        }
        echo json_encode($json);
    }
    public function get_item_lists(){
        $this->load->model('dine/items_model');
        $args = array();
        $search = "";
        $code = "";
        $title = "";
        $args['items.type'] = array(2,3);
        if($this->input->post('search')){
            $search = $this->input->post('search');
            $title = "Search Results for \"".$search."\"";
            $args['items.barcode'] = array('use'=>'like','val'=>$search);
            $args['items.code'] = array('use'=>'or_like','val'=>$search);
            $args['items.name'] = array('use'=>'or_like','val'=>$search);
        }
        if($this->input->post('cat_id')){
            $title = $this->input->post('cat_name');
            $args['items.cat_id'] = $this->input->post('cat_id');
        }

        $item_list = $this->items_model->get_item(null,$args);
        $this->make->sUl(array('class'=>'ul-hover-blue'));
        $items = array();
        foreach ($item_list as $res) {
            $this->make->sLi(array('id'=>'retail-item-'.$res->item_id,'class'=>'retail-item','style'=>'padding:5px;padding-bottom:10px;padding-top:10px;border-bottom:1px solid #ddd;'));
                $cost = $this->make->span($res->cost,array('class'=>'pull-right','return'=>true));            
                $this->make->H(4,$res->code." ".$res->name." ".$cost,array('style'=>'margin:0;padding:0;margin-left:10px;'));            
                $this->make->H(5,$res->barcode,array('style'=>'margin:0;padding:0;margin-left:10px;'));            
            $this->make->eLi();   
            $items[$res->item_id] = array(
                "name"=> $res->name,
                "cost"=> $res->cost
            );
        }
        $this->make->eUl();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'title'=>$title,'items'=>$items));
    }
    public function scan_code($code=null){
        $this->load->model('dine/items_model');
        $args['items.type'] = array(2,3);
        $args['items.barcode'] = $code;
        $item_list = $this->items_model->get_item(null,$args);
        // echo $this->items_model->db->last_query();
        $error = "";
        $item = array();
        if(count($item_list) > 0){
            foreach ($item_list as $res) {
                $item =  array(
                    "item_id" => $res->item_id,
                    "name"=> $res->name,
                    "cost"=> $res->cost
                );
                break;
            }    
        }
        else{
            $error = "Item not found.";
        }
        echo json_encode(array('error'=>$error,'item'=>$item));
    }
    public function get_menus($cat_id=null,$item_id=null,$asJson=true){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $menus = $this->menu_model->get_menus($item_id,$cat_id,true);
        $json = array();

        if(count($menus) > 0){
            $ids = array();
            foreach ($menus as $res) {
                $json[$res->menu_id] = array(
                    "name"=>$res->menu_name,
                    "category"=>$res->menu_cat_id,
                    "cost"=>$res->cost,
                    "no_tax"=>$res->no_tax,
                    "free"=>$res->free,
                );
                $ids[] = $res->menu_id;
            }
            $promos = $this->cashier_model->get_menu_promos($ids);
            $prs = array();
            $prm = array();
            foreach ($promos as $pr) {
                $prs[] = $pr->promo_id;
                $prm[$pr->item_id][] = array('id'=>$pr->promo_id,'val'=>$pr->value,'abs'=>$pr->absolute);
            }

            $time = $this->site_model->get_db_now();
            $day = strtolower(date('D',strtotime($time)));
            $sched = $this->cashier_model->get_menu_promo_schedule($prs,$day,date2SqlDateTime($time));

            $schs = array();
            foreach ($sched as $sc) {
                $schs[] = $sc->promo_id;
            }

            foreach ($json as $menu_id => $opt) {
                if(isset($prm[$menu_id])){
                    foreach ($prm[$menu_id] as $p) {
                        if(in_array($p['id'], $schs)){
                            if($p['abs'] == 0){
                                $opt['cost'] -= $pr->value;

                            }
                            else{
                                $opt['cost'] -=  ($pr->value / 100) * $opt['cost'];
                            }
                            $json[$menu_id] = $opt;
                            break;
                        }
                    }####
                }
            }

        }
        echo json_encode($json);
    }
    public function get_menu_modifiers($menu_id=null){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/mods_model');
        $menu_mods = $this->menu_model->get_menu_modifiers($menu_id);
        $group = array();
        $grp = array();
        if(count($menu_mods) > 0){
            foreach ($menu_mods as $res) {
                $group[$res->mod_group_id] = array(
                    "name"=>$res->mod_group_name,
                    "mandatory"=>$res->mandatory,
                    "multiple"=>$res->multiple
                );

                $grp[] = $res->mod_group_id;
            }
            $details = $this->mods_model->get_modifier_group_details(null,$grp);
            $dets = array();
            foreach ($details as $det) {
                $dets=array(
                    "name"=>$det->mod_name,
                    "cost"=>$det->mod_cost
                );
                $group[$det->mod_group_id]['details'][$det->mod_id] = $dets;
            }
        }
        echo json_encode($group);
    }
    public function add_trans_modifier(){
        $wagon = array();
        $error = null;
        $name  = 'trans_mod_cart';
        $id = null;
        $row = null;
        if($this->session->userData($name)){
            $wagon = $this->session->userData($name);
        }
        $row = $this->input->post();

        if(count($wagon) > 0){
            // foreach($wagon as $key => $det) {
            //     // echo $det['mod_id'].' == '.$row['mod_id'].' && '.$det['trans_id'].' == '.$row['trans_id'];
                // if($row['multiple'] == 0){
            //         // if($det['mod_id'] == $row['mod_id'] && $det['trans_id'] == $row['trans_id']){
            //         if($det['trans_id'] == $row['trans_id']){
            //             $error = 'You can only choose 1 on this modifier.';
            //             break;
            //         }
            //         else{
            //             $error = null;
            //         }
            //     }
            //     else{
            //        $error = null;
            //     }
            // }
            if($row['multiple'] < 1){
                $row['multiple'] = 1;
            }    
            $ctr=1;
            foreach ($wagon as $key => $det) {
                if($det['trans_id'] == $row['trans_id']){
                    $ctr++;
                }
            }
            if($ctr > $row['multiple']){
                $error = 'You can only choose up '.$row['multiple'].' on this modifier.';
            }


            if($error == null)
                    $wagon[] = $row;
        }
        else{
            $wagon[] = $row;
        }
        $id = max(array_keys($wagon));
        $this->session->set_userData($name,$wagon);
        echo json_encode(array("items"=>$row,"id"=>$id,"error"=>$error));
    }
    public function delete_trans_menu_modifier($trans_id=null){
        $wagon = array();
        $error = null;
        $name  = 'trans_mod_cart';
        $id = null;
        $row = null;
        $wagon = $this->session->userData($name);
        foreach ($wagon as $key => $det) {
            if($det['trans_id'] == $trans_id){
                unset($wagon[$key]);
            }
        }
        $this->session->set_userData($name,$wagon);
        echo json_encode(array("items"=>$row,"id"=>$id));
    }
    public function update_trans_qty($trans_id=null){
        $wagon = array();
        $error = null;
        $name  = 'trans_cart';
        $wagon = $this->session->userData($name);
        $row = $wagon[$trans_id];
        $char = $this->input->post('operator');
        $val = $this->input->post('value');
        switch($char){
            case "times":
                $row['qty'] *= $val;
                break;
            case "equal":
                $row['qty'] = $val;
                break;
            case "plus";
                $row['qty'] += $val;
                break;
            case "minus";
                $row['qty'] -= $val;
                if($row['qty'] <= 0)
                    $row['qty'] = 1;
                break;
        }
        $wagon[$trans_id] = $row;
        $this->session->set_userData($name,$wagon);
        echo json_encode(array("error"=>null,"qty"=>$row['qty']));
    }
    public function add_trans_remark($trans_id=null){
        $wagon = array();
        $error = null;
        $name  = 'trans_cart';
        $wagon = $this->session->userData($name);
        $row = $wagon[$trans_id];
        $remarks = $this->input->post('line-remarks');
        $row['remarks'] = $remarks;
        $wagon[$trans_id] = $row;
        $this->session->set_userData($name,$wagon);
        echo json_encode(array("error"=>null,"remarks"=>$row['remarks']));
    }
    public function remove_trans_remark($trans_id=null){
        $wagon = array();
        $error = null;
        $name  = 'trans_cart';
        $wagon = $this->session->userData($name);
        $row = $wagon[$trans_id];
        if(isset($row['remarks']))
            unset($row['remarks']);
        $this->session->set_userData($name,$wagon);
        echo json_encode(array("error"=>null));
    }
    public function trans_exempt_to_tax(){
        $trans_cart = sess('trans_cart');
        $error = "";
        $tax = $this->get_tax_rates(false);
        if(count($tax) > 0){
            foreach ($trans_cart as $trans_id => $v) {
                $v['no_tax'] = 1;
                $trans_cart[$trans_id] = $v;
            }
        }
        sess_initialize('trans_cart',$trans_cart);
        echo json_encode(array("error"=>$error));
    }
    public function total_trans($asJson=true,$cart=null,$disc_cart=null,$charge_cart=null,$zero_rated=null){
        $counter = sess('counter');
        if(is_array($zero_rated)){
             // && isset($zero_rated['amount']) && $zero_rated['amount'] > 0
             foreach ($zero_rated as $zid => $opt) {
                 if($opt['amount'] > 0){
                    $counter['zero_rated'] = 1;
                    break;
                 }
             }            
        }
        $trans_cart = array();
        if($this->session->userData('trans_cart')){
            $trans_cart = $this->session->userData('trans_cart');
        }
        $trans_mod_cart = array();
        if($this->session->userData('trans_mod_cart')){
            $trans_mod_cart = $this->session->userData('trans_mod_cart');
        }
        if(is_array($cart)){
            $trans_cart = $cart;
        }
        $total = 0;
        $discount = 0;
        $zero_rated = 0;
        $vat_sales = 0;
        $non_vat_sales = 0;
        if(count($trans_cart) > 0){
            foreach ($trans_cart as $trans_id => $trans){
                if(isset($trans['cost']))
                    $cost = $trans['cost'];
                if(isset($trans['price']))
                    $cost = $trans['price'];

                if(isset($trans['modifiers'])){
                    foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                        if($trans_id == $mod['line_id'])
                            $cost += $mod['price'];
                    }
                }

                else{
                    if(count($trans_mod_cart) > 0){
                        foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                            if($trans_id == $mod['trans_id'])
                                $cost += $mod['cost'];
                        }
                    }
                }
                if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                    $rate = 1.12;
                    $cost = num(($cost / $rate),2);
                    $zero_rated += $trans['qty'] * $cost;
                }
                $total += $trans['qty'] * $cost;
            }
        }
        $trans_disc_cart = sess('trans_disc_cart');
        if(is_array($disc_cart)){
            $trans_disc_cart = $disc_cart;
        }
        $discs = array();
        if(count($trans_disc_cart) > 0 ){
            $error_disc = 0;
            foreach ($trans_disc_cart as $disc_id => $row) {
                if(!isset($row['disc_type'])){
                    $error_disc = 1;
                }
                else{
                    if($row['disc_type'] == "")
                        $error_disc = 1;
                }
            }
            if($error_disc == 0){
                foreach ($trans_disc_cart as $disc_id => $row) {
                    $rate = $row['disc_rate'];
                    $guests = $row['guest'];
                    switch ($row['disc_type']) {
                        case "equal":
                                $divi = $total/$row['guest'];
                                $divi_less = $divi;
                                if($row['no_tax'] == 1){
                                    $divi_less = ($divi / 1.12);
                                }
                                $no_persons = count($row['persons']);
                                foreach ($row['persons'] as $code => $per) {
                                    $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                    $discount += ($rate / 100) * $divi_less;
                                }
                                $tl = $divi * ( abs($row['guest'] - $no_persons) );
                                $tdl = ($divi_less * $no_persons) - $discount;
                                $total = $tl + $tdl;

                                // $total = ($divi * $row['guest']) - $discount;
                                break;
                        default:
                            $no_citizens = count($row['persons']);
                            if($row['no_tax'] == 1)
                                $total = ($total / 1.12);                     
                            foreach ($row['persons'] as $code => $per) {
                                $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $total);
                                $discount += ($rate / 100) * $total;
                            }
                            $total -= $discount;
                    }
                }
            }
        }

        $trans_charge_cart = sess('trans_charge_cart');
        if(is_array($charge_cart)){
            $trans_charge_cart = $charge_cart;
        }
        #CHARGES
        $charges = array();
        $total_charges = 0;
        $net_total = $total;
        if(count($trans_charge_cart) > 0 ){
            // $tax = $this->get_tax_rates(false);
            // $am = 0;
            // if(count($tax) > 0){
            //     $taxable_amount = 0;
            //     $not_taxable_amount = 0;
            //     foreach ($trans_cart as $trans_id => $v) {
            //         if(isset($v['cost']))
            //             $cost = $v['cost'];
            //         if(isset($v['price']))
            //             $cost = $v['price'];
            //         ####################
            //         if(isset($v['modifiers'])){
            //             foreach ($v['modifiers'] as $trans_mod_id => $m) {
            //                 if($trans_id == $m['line_id']){
            //                     $cost += $m['price'];
            //                 }
            //             }
            //         }
            //         else{
            //             if(count($trans_mod_cart) > 0){
            //                 foreach ($trans_mod_cart as $trans_mod_id => $m) {
            //                     if($trans_id == $m['trans_id']){
            //                         $cost += $m['cost'];
            //                     }
            //                 }
            //             }
            //         }
            //         ####################
            //         foreach ($trans_disc_cart as $disc_id => $row) {
            //             $rate = $row['disc_rate'];
            //             switch ($row['disc_type']) {
            //                 case "equal":
            //                         // $divi = $cost/$row['guest'];
            //                         // $discount = ($rate / 100) * $divi;
            //                         // $cost -= $discount;

            //                         $divi = $cost/$row['guest'];
            //                         $divi_less = $divi;
            //                         if($row['no_tax'] == 1){
            //                             $divi_less = ($divi / 1.12);
            //                         }
            //                         $no_persons = count($row['persons']);
            //                         foreach ($row['persons'] as $code => $per) {
            //                             $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
            //                             $discount += ($rate / 100) * $divi_less;
            //                         }
            //                         $tl = $divi * ( abs($row['guest'] - $no_persons) );
            //                         $tdl = ($divi_less * $no_persons) - $discount;
            //                         $cost = $tl - $tdl;
            //                         // $cost = ($divi * $row['guest']) - $discount;
            //                         break;
            //                 default:
            //                     $no_citizens = count($row['persons']);
            //                     if($row['no_tax'] == 1)
            //                         $cost = ($cost / 1.12);                     
            //                     foreach ($row['persons'] as $code => $per) {
            //                         $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $cost);
            //                         $discount += ($rate / 100) * $cost;
            //                     }
            //                     $cost -= $discount;
            //                     // $discount = ($rate / 100) * $cost;
            //                     // $cost -= $discount;
            //             }
            //         }

            //         if($v['no_tax'] == 0){
            //             $taxable_amount += $cost * $v['qty'];
            //         }
            //         else{
            //             $not_taxable_amount += $cost * $v['qty'];
            //         }
            //     }

            //     $am = $taxable_amount;
            //     $trans_sales_tax = array();
            //     foreach ($tax as $tax_id => $tx) {
            //         $rate = ($tx['rate'] / 100);
            //         $tax_value = ($am / ($rate + 1) ) * $rate;
            //         $am -= $tax_value;
            //     }
            // }
            // else{
            //     $am = $total;
            // }
            $am = $net_total;
            // echo $am."<br>";
            foreach ($trans_charge_cart as $charge_id => $opt) {
                $charge_amount = $opt['amount'];
                if($opt['absolute'] == 0){
                    $charge_amount = ($opt['amount'] / 100) * $am;
                }
                $charges[$charge_id] = array('code'=>$opt['code'],
                                   'name'=>$opt['name'],
                                   'amount'=>$charge_amount,
                                   );
                $total_charges += $charge_amount;
            }
            $total += $total_charges;
        }

        $loc_res = $this->site_model->get_tbl('settings',array(),array(),null,true,'local_tax',null,1);
        $local_tax = $loc_res[0]->local_tax;
        $lt_amt = 0;
        if($local_tax > 0){
            $lt_amt = ($local_tax / 100) * $net_total;
            $total += $lt_amt;
        }
        if($asJson)
            echo json_encode(array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated,'local_tax'=>$lt_amt));
        else
            return array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated,'local_tax'=>$lt_amt);
    }
    /*    
        public function total_trans($asJson=true,$cart=null,$disc_cart=null,$charge_cart=null,$zero_rated=null){
            $counter = sess('counter');
            if(is_array($zero_rated)){
                 // && isset($zero_rated['amount']) && $zero_rated['amount'] > 0
                 foreach ($zero_rated as $zid => $opt) {
                     if($opt['amount'] > 0){
                        $counter['zero_rated'] = 1;
                        break;
                     }
                 }            
            }
            $trans_cart = array();
            if($this->session->userData('trans_cart')){
                $trans_cart = $this->session->userData('trans_cart');
            }
            $trans_mod_cart = array();
            if($this->session->userData('trans_mod_cart')){
                $trans_mod_cart = $this->session->userData('trans_mod_cart');
            }
            if(is_array($cart)){
                $trans_cart = $cart;
            }
            $total = 0;
            $discount = 0;
            $zero_rated = 0;
            if(count($trans_cart) > 0){
                foreach ($trans_cart as $trans_id => $trans){
                    if(isset($trans['cost']))
                        $cost = $trans['cost'];
                    if(isset($trans['price']))
                        $cost = $trans['price'];

                    if(isset($trans['modifiers'])){
                        foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                            if($trans_id == $mod['line_id'])
                                $cost += $mod['price'];
                        }
                    }

                    else{
                        if(count($trans_mod_cart) > 0){
                            foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                                if($trans_id == $mod['trans_id'])
                                    $cost += $mod['cost'];
                            }
                        }
                    }
                    if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                        $rate = 1.12;
                        $cost = num(($cost / $rate),2);
                        $zero_rated += $trans['qty'] * $cost;
                    }
                    $total += $trans['qty'] * $cost;
                }
            }
            $trans_disc_cart = sess('trans_disc_cart');
            if(is_array($disc_cart)){
                $trans_disc_cart = $disc_cart;
            }
            $discs = array();
            if(count($trans_disc_cart) > 0 ){
                foreach ($trans_disc_cart as $disc_id => $row) {
                    $rate = $row['disc_rate'];
                    switch ($row['disc_type']) {
                        case "item":
                                $item_cost = 0;
                                foreach ($row['items'] as $line) {
                                    if(isset($trans_cart[$line])){
                                        if(isset($trans_cart[$line]['cost']))
                                            $cost =  $trans_cart[$line]['cost'];
                                        if(isset( $trans_cart[$line]['price']))
                                            $cost =  $trans_cart[$line]['price'];
                                        $item_cost += $cost;
                                        ###
                                        if(isset($trans_cart[$line]['modifiers'])){
                                            foreach ($trans_cart[$line]['modifiers'] as $trans_mod_id => $mod) {
                                                if($line == $mod['line_id'])
                                                    $item_cost += $mod['price'];
                                            }
                                        }
                                        else{
                                            if(count($trans_mod_cart) > 0){
                                                foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                                                    if($line == $mod['trans_id']){
                                                        $item_cost += $mod['cost'];
                                                    }
                                                }
                                            }
                                        }
                                        ####
                                         $item_cost = $item_cost * $trans_cart[$line]['qty'];
                                    }
                                }
                                $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $item_cost,'items'=>$row['items']);
                                $discount += ($rate / 100) * $item_cost;
                                $total -= $discount;
                                break;
                        case "equal":
                                $divi = $total/$row['guest'];
                                $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi);
                                $discount += ($rate / 100) * $divi;
                                $total -= $discount;
                                break;
                        default:
                            $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $total);
                            $discount += ($rate / 100) * $total;
                            $total -= $discount;
                    }
                }
            }
            $trans_charge_cart = sess('trans_charge_cart');
            if(is_array($charge_cart)){
                $trans_charge_cart = $charge_cart;
            }
            #CHARGES
            $charges = array();
            $total_charges = 0;
            if(count($trans_charge_cart) > 0 ){
                $tax = $this->get_tax_rates(false);
                $am = 0;
                if(count($tax) > 0){
                    $taxable_amount = 0;
                    $not_taxable_amount = 0;
                    foreach ($trans_cart as $trans_id => $v) {
                        if(isset($v['cost']))
                            $cost = $v['cost'];
                        if(isset($v['price']))
                            $cost = $v['price'];
                        ####################
                        if(isset($v['modifiers'])){
                            foreach ($v['modifiers'] as $trans_mod_id => $m) {
                                if($trans_id == $m['line_id']){
                                    $cost += $m['price'];
                                }
                            }
                        }
                        else{
                            if(count($trans_mod_cart) > 0){
                                foreach ($trans_mod_cart as $trans_mod_id => $m) {
                                    if($trans_id == $m['trans_id']){
                                        $cost += $m['cost'];
                                    }
                                }
                            }
                        }
                        ####################
                        foreach ($trans_disc_cart as $disc_id => $row) {
                            $rate = $row['disc_rate'];
                            switch ($row['disc_type']) {
                                case "item":
                                        if( in_array($trans_id, $row['items'])){
                                            $discount = ($rate / 100) * $cost;
                                            $cost -= $discount;
                                        }
                                        break;
                                case "equal":
                                        $divi = $cost/$row['guest'];
                                        $discount = ($rate / 100) * $divi;
                                        $cost -= $discount;
                                        break;
                                default:
                                    $discount = ($rate / 100) * $cost;
                                    $cost -= $discount;
                            }
                        }

                        if($v['no_tax'] == 0){
                            $taxable_amount += $cost * $v['qty'];
                        }
                        else{
                            $not_taxable_amount += $cost * $v['qty'];
                        }
                    }

                    $am = $taxable_amount;
                    $trans_sales_tax = array();
                    foreach ($tax as $tax_id => $tx) {
                        $rate = ($tx['rate'] / 100);
                        $tax_value = ($am / ($rate + 1) ) * $rate;
                        $am -= $tax_value;
                    }
                }
                else{
                    $am = $total;
                }
                foreach ($trans_charge_cart as $charge_id => $opt) {
                    $charge_amount = $opt['amount'];
                    if($opt['absolute'] == 0){
                        $charge_amount = ($opt['amount'] / 100) * $am;
                    }
                    $charges[$charge_id] = array('code'=>$opt['code'],
                                       'name'=>$opt['name'],
                                       'amount'=>$charge_amount,
                                       );
                    $total_charges += $charge_amount;
                }
                $total += $total_charges;
            }

            if($asJson)
                echo json_encode(array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated));
            else
                return array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated);
        }*/
    public function get_tax_rates($asJson=true,$tax_id=null){
        $this->load->model('dine/settings_model');
        $taxes = $this->settings_model->get_tax_rates($tax_id);
        $tax = array();
        foreach ($taxes as $res) {
            $tax[$res->tax_id] = array(
                "name"=>$res->name,
                "rate"=>$res->rate
            );
        }
        if($asJson)
            echo json_encode($tax);
        else
            return $tax;
    }
    public function submit_trans($asJson=true,$submit=null,$void=false,$void_ref=null,$cart=null,$mod_cart=null,$print=false,$split_id=null,$printKitSlip=false){
        $this->load->model('dine/cashier_model');
        $counter = sess('counter');
        $trans_cart = sess('trans_cart');
        $trans_mod_cart = sess('trans_mod_cart');
        $trans_type_cart = sess('trans_type_cart');
        $trans_disc_cart = sess('trans_disc_cart');
        $trans_charge_cart = sess('trans_charge_cart');

        $totals  = $this->total_trans(false,$cart);
        $total_amount = $totals['total'];
        $charges = $totals['charges'];
        $local_tax = $totals['local_tax'];
        $error = null;
        $act = null;
        $sales_id = null;
        $type = null;
        $type_id = SALES_TRANS;
        $print_echo = array();
        if($void === true){
            $type_id = SALES_VOID_TRANS;
        }

        if($void_ref == null || $void_ref == 0)
            $void_ref = null;

        if(count($trans_cart) <= 0){
            $error = "Error! There are no items.";
        }
        else if(count($counter) <= 0){
            $error = "Error! Shift or User is invalid.";
        }
        // else if(!isset($counter['waiter_id'])){
        //     $error = "Please Select a Food Server.";
        // }
        else{
            if(count($trans_disc_cart) > 0){
                foreach ($trans_disc_cart as $disc_id => $row) {
                    if(!isset($row['disc_type'])){
                        $error = "Select Discount Type. If equally Divided or All Items.";
                    }
                    else{
                        if($row['disc_type'] == "")
                            $error = "Select Discount Type. If equally Divided or All Items.";
                    }
                }
                if($error != null){
                    if($asJson){
                        echo json_encode(array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type));
                        return false;
                    }
                    else{
                        return array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type);
                    }
                }
            }


            if(is_array($cart)){
                $trans_cart = $cart;
            }
            if(is_array($mod_cart)){
                $trans_mod_cart = $mod_cart;
            }
            $type = $counter['type'];
            #save sa trans_sales
            $table = null;
            $guest = 0;
            $customer = null;
            if(isset($trans_type_cart[0]['table'])){
                $table = $trans_type_cart[0]['table'];
            }
            if(isset($trans_type_cart[0]['guest'])){
                $guest = $trans_type_cart[0]['guest'];
            }
            if(isset($trans_type_cart[0]['customer_id'])){
                $customer = $trans_type_cart[0]['customer_id'];
            }
            $waiter = null;
            if(isset($counter['waiter_id'])){
                $waiter = $counter['waiter_id'];
            }
			$splid = 0;
            if($split_id != null)
                $splid = $split_id;
             // $total_amount = number_format($total_amount, 2, '.', '');
             $total_amount = $total_amount;
             $trans_sales = array(
                "user_id"       => $counter['user_id'],
                "type_id"       => $type_id,
                "shift_id"      => $counter['shift_id'],
                "terminal_id"   => $counter['terminal_id'],
                "type"          => $counter['type'],
                "datetime"      => date2SqlDateTime($counter['datetime']),
                "total_amount"  => $total_amount,
                "void_ref"      => $void_ref,
                "memo"          => null,
                "table_id"      => $table,
                "guest"         => $guest,
                "customer_id"   => $customer,
                "waiter_id"     => $waiter,
                "split"         => $splid
            );
            $user = $this->session->userdata('user');
            if(isset($counter['sales_id']) && $counter['sales_id'] != null){
                $sales_id = $counter['sales_id'];
                $this->cashier_model->update_trans_sales($trans_sales,$sales_id);
                $this->logs_model->add_logs('Sales Order',$user['id'],$user['full_name']." Updated Sales Order #".$sales_id,$sales_id);
                $this->cashier_model->delete_trans_sales_menus($sales_id);
                $this->cashier_model->delete_trans_sales_items($sales_id);
                $this->cashier_model->delete_trans_sales_menu_modifiers($sales_id);
                $this->cashier_model->delete_trans_sales_discounts($sales_id);
                $this->cashier_model->delete_trans_sales_charges($sales_id);
                $this->cashier_model->delete_trans_sales_tax($sales_id);
                $this->cashier_model->delete_trans_sales_no_tax($sales_id);
                $this->cashier_model->delete_trans_sales_zero_rated($sales_id);
                $this->cashier_model->delete_trans_sales_local_tax($sales_id);
                $act="update";
                if($submit === null || $submit == 0 || $submit == null)
                    site_alert('Transaction Updated.','success');
            }
            else{
                $sales_id = $this->cashier_model->add_trans_sales($trans_sales);
                $this->logs_model->add_logs('Sales Order',$user['id'],$user['full_name']." Added New Sales Order #".$sales_id,$sales_id);
                $act="add";
            }
            #save sa trans_sales_menus
            $trans_sales_menu = array();
            $trans_sales_items = array();
            foreach ($trans_cart as $trans_id => $v) {
                $remarks = null;
                if(isset($v['remarks']) && $v['remarks'] != ""){
                    $remarks = $v['remarks'];
                }
                $kitchen_slip_printed=0;
                if(isset($v['kitchen_slip_printed']) && $v['kitchen_slip_printed'] != ""){
                    $kitchen_slip_printed = $v['kitchen_slip_printed'];
                }
                if(!isset($v['retail'])){
                    $trans_sales_menu[] = array(
                        "sales_id" => $sales_id,
                        "line_id" => $trans_id,
                        "menu_id" => $v['menu_id'],
                        "price" => $v['cost'],
                        "qty" => $v['qty'],
                        "no_tax" => $v['no_tax'],
                        "discount"=> 0,
                        "remarks"=>$remarks,
                        "kitchen_slip_printed"=>$kitchen_slip_printed
                    );
                }
                else{
                    $trans_sales_items[] = array(
                        "sales_id" => $sales_id,
                        "line_id" => $trans_id,
                        "item_id" => $v['menu_id'],
                        "price" => $v['cost'],
                        "qty" => $v['qty'],
                        "no_tax" => $v['no_tax'],
                        "discount"=> 0,
                        "remarks"=>$remarks
                    );
                }
            }
            if(count($trans_sales_menu) > 0)
                $this->cashier_model->add_trans_sales_menus($trans_sales_menu);
            
            if(count($trans_sales_items) > 0)
                $this->cashier_model->add_trans_sales_items($trans_sales_items);
            #save sa trans_sales_menu_modifiers
            if(count($trans_mod_cart) > 0){
                $trans_sales_menu_modifiers = array();
                foreach ($trans_mod_cart as $trans_mod_id => $m) {
                    $kitchen_slip_printed=0;
                    if(isset($m['kitchen_slip_printed']) && $m['kitchen_slip_printed'] != ""){
                        $kitchen_slip_printed = $m['kitchen_slip_printed'];
                    }
                    if(isset($trans_cart[$m['trans_id']])){
                        $trans_sales_menu_modifiers[] = array(
                            "sales_id" => $sales_id,
                            "line_id" => $m['trans_id'],
                            "menu_id" => $m['menu_id'],
                            "mod_id" => $m['mod_id'],
                            "price" => $m['cost'],
                            "qty" => $m['qty'],
                            "discount"=> 0,
                            "kitchen_slip_printed"=>$kitchen_slip_printed
                        );
                    }
                }
                if(count($trans_sales_menu_modifiers) > 0)
                    $this->cashier_model->add_trans_sales_menu_modifiers($trans_sales_menu_modifiers);
            }
            #save sa trans_sales_discounts
            if(count($trans_disc_cart) > 0){
                $trans_sales_disc_cart = array();
                $total = 0;
                foreach ($trans_cart as $trans_id => $trans){
                    if(isset($trans['cost']))
                        $cost = $trans['cost'];
                    if(isset($trans['price']))
                        $cost = $trans['price'];

                    if(isset($trans['modifiers'])){
                        foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                            if($trans_id == $mod['line_id'])
                                $cost += $mod['price'];
                        }
                    }

                    else{
                        if(count($trans_mod_cart) > 0){
                            foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                                if($trans_id == $mod['trans_id'])
                                    $cost += $mod['cost'];
                            }
                        }
                    }
                    if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                        $rate = 1.12;
                        $cost = ($cost / $rate);
                        $zero_rated += $v['qty'] * $cost;
                    }
                    $total += $trans['qty'] * $cost;
                }

                foreach ($trans_disc_cart as $disc_id => $dc) {
                    $dit = "";
                    if(isset($dc['items'])){
                        foreach ($dc['items'] as $lines) {
                            $dit .= $lines.",";
                        }
                        if($dit != "")
                            $dit = substr($dit,0,-1);                        
                    }
                    

                    $discount = 0;
                    $rate = $dc['disc_rate'];
                    switch ($dc['disc_type']) {
                        // case "item":
                        //         $item_cost = 0;
                        //         foreach ($dc['items'] as $line) {
                        //             if(isset($trans_cart[$line])){
                        //                 if(isset($trans_cart[$line]['cost']))
                        //                     $cost =  $trans_cart[$line]['cost'];
                        //                 if(isset( $trans_cart[$line]['price']))
                        //                     $cost =  $trans_cart[$line]['price'];
                        //                 $item_cost += $cost;
                        //                 ###
                        //                 if(isset($trans_cart[$line]['modifiers'])){
                        //                     foreach ($trans_cart[$line]['modifiers'] as $trans_mod_id => $mod) {
                        //                         if($line == $mod['line_id'])
                        //                             $item_cost += $mod['price'];
                        //                     }
                        //                 }
                        //                 else{
                        //                     if(count($trans_mod_cart) > 0){
                        //                         foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                        //                             if($line == $mod['trans_id']){
                        //                                 $item_cost += $mod['cost'];
                        //                             }
                        //                         }
                        //                     }
                        //                 }
                        //                 ####
                        //                  $item_cost = $item_cost * $trans_cart[$line]['qty'];
                        //             }
                        //         }
                        //         $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $item_cost,'items'=>$dc['items']);
                        //         $discount = ($rate / 100) * $item_cost;
                        //         break;
                        // case "equal":
                        //         $divi = $total/$dc['guest'];
                        //         $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $divi);
                        //         $discount = ($rate / 100) * $divi;
                        //         break;
                        // default:
                        //     $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total);
                        //     $discount = ($rate / 100) * $total;
                        // case "equal":
                        //     $divi = $total/$dc['guest'];
                        //     $div_less = ($divi / 1.12);
                        //     $no_persons = count($dc['persons']);
                        //     // foreach ($dc['persons'] as $code => $per) {
                        //         $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $div_less);
                        //         $discount = ($rate / 100) * $div_less;
                        //     // }
                        //     break;
                        // default:
                        //     $no_citizens = count($dc['persons']);
                        //     $no_cost_total = ($total / 1.12);
                        //     // foreach ($dc['persons'] as $code => $per) {
                        //     $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $no_cost_total);
                        //     $discount = ($rate / 100) * $no_cost_total;
                        case "equal":
                            $divi = $total/$dc['guest'];
                            $divi_less = $divi;
                            if($dc['no_tax'] == 1){
                                $divi_less = ($divi / 1.12);
                            }
                            $no_persons = count($dc['persons']);
                            // foreach ($row['persons'] as $code => $per) {
                            $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $divi_less);
                            $discount = ($rate / 100) * $divi_less;
                            // }
                            // $total = ($divi * $row['guest']) - $discount;

                            break;
                        default:
                            $no_citizens = count($dc['persons']);
                            if($dc['no_tax'] == 1)
                                $total = ($total / 1.12);                     
                            $discs[] = array('type'=>$dc['disc_code'],'amount'=>($rate / 100) * $total);
                            $discount = ($rate / 100) * $total;
                            // }    
                    }
                    foreach ($dc['persons'] as $pcode => $oper) {
                        $dcBday = null;
                        if(isset($oper['bday']) && $oper['bday'] != "")
                            $dcBday = date2Sql($oper['bday']);
                        $trans_sales_disc_cart[] = array(
                            "sales_id"=>$sales_id,
                            "disc_id"=>$dc['disc_id'],
                            "disc_code"=>$dc['disc_code'],
                            "disc_rate"=>$dc['disc_rate'],
                            "no_tax"=>$dc['no_tax'],
                            "type"=>$dc['disc_type'],
                            "name"=>$oper['name'],
                            "bday"=>$dcBday,
                            "code"=>$oper['code'],
                            "items"=>$dit,
                            "guest"=>$dc['guest'],
                            "amount"=>$discount
                        );
                    }
                }
                if(count($trans_sales_disc_cart) > 0)
                    $this->cashier_model->add_trans_sales_discounts($trans_sales_disc_cart);
            }
            #save sa trans_sales_charges
            $total_charge = 0;
            if(count($trans_charge_cart) > 0){
                $trans_sales_charge_cart = array();
                foreach ($trans_charge_cart as $charge_id => $ch) {
                    $trans_sales_charge_cart[] = array(
                        "sales_id"=>$sales_id,
                        "charge_id"=>$charge_id,
                        "charge_code"=>$ch['code'],
                        "charge_name"=>$ch['name'],
                        "rate"=>$ch['amount'],
                        "absolute"=>$ch['absolute'],
                        "amount"=>$charges[$charge_id]['amount']
                    );
                    $total_charge += $charges[$charge_id]['amount'];
                }
                if(count($trans_sales_charge_cart) > 0)
                    $this->cashier_model->add_trans_sales_charges($trans_sales_charge_cart);
            }
            #SAVE SA TRANS_SALES_TAX
            // $total_amount
            $tax = $this->get_tax_rates(false);
            $zero_rated = 0;
            $total = 0;
            if(count($tax) > 0){
                $taxable_amount = 0;
                $not_taxable_amount = 0;
                foreach ($trans_cart as $trans_id => $v) {
                    $cost = $v['cost'];
                    if(count($trans_mod_cart) > 0){
                        foreach ($trans_mod_cart as $trans_mod_id => $m) {
                            if($trans_id == $m['trans_id']){
                                $cost += $m['cost'];
                            }
                        }
                    }
                    if($v['no_tax'] == 0){
                        if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                            $rate = 1.12;
                            $cost = ($cost / $rate);
                            $zero_rated += $v['qty'] * $cost;
                        }
                        $total += $v['qty'] * $cost;

                        $taxable_amount = $total;
                        foreach ($trans_disc_cart as $disc_id => $dc) {
                            $discount = 0;
                            $rate = $dc['disc_rate'];
                            switch ($dc['disc_type']) {
                                case "equal":
                                    $divi = $total/$dc['guest'];
                                    $no_tax_persons = count($dc['persons']);
                                    $tax_persons = abs($dc['guest'] - $no_persons);
                                    $taxable_amount = $divi * $tax_persons; 
                                    $divi_less = $divi;
                                    if($dc['no_tax'] == 1){
                                        $divi_less = ($divi / 1.12);
                                        // $discount = (($rate / 100) * $divi_less) * $no_tax_persons;
                                        $not_taxable_amount = $divi_less * $no_tax_persons;
                                    }
                                    else{
                                        $discount = (($rate / 100) * $divi) * $no_tax_persons;
                                        $taxable_amount += $divi - $discount;
                                        // $taxable_amount += $divi * $tax_persons;
                                    }                                
                                    break;
                                default:
                                    $no_citizens = count($dc['persons']);
                                    $no_cost_total = $total;
                                    if($dc['no_tax'] == 1){
                                        $no_cost_total = $total / 1.12;
                                        // $discount = ($rate / 100) * $total;
                                        // $total_discount = $discount * $no_citizens;
                                        $taxable_amount = 0;
                                        $not_taxable_amount = $no_cost_total;
                                    }
                                    else{
                                        $discount = ($rate / 100) * $total;
                                        $total_discount = $discount * $no_citizens;
                                        $taxable_amount = $total - $discount;
                                        $not_taxable_amount = 0;
                                    }
                            }
                        }
                    }
                    else{
                      if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                            $rate = 1.12;
                            $cost = ($cost / $rate);
                            $zero_rated += $v['qty'] * $cost;
                            $not_taxable_amount += $v['qty'] * $cost;
                      }
                      else{
                            $not_taxable_amount += $cost * $v['qty'];
                      }  
                    }
                    // if($v['no_tax'] == 0){
                    //     $taxable_amount += $cost * $v['qty'];
                    // }
                    // else{
                    // }
                }
                //remove charges
                

                $trans_sales_zero_rated[] = array(
                    "sales_id"=>$sales_id,
                    "amount"=>$zero_rated
                );
                // echo var_dump($trans_sales_zero_rated);
                // return false;
                if(count($trans_sales_zero_rated) > 0)
                    $this->cashier_model->add_trans_sales_zero_rated($trans_sales_zero_rated);
                $trans_sales_no_tax[] = array(
                    "sales_id"=>$sales_id,
                    "amount"=>$not_taxable_amount
                );
                if(count($trans_sales_no_tax) > 0)
                    $this->cashier_model->add_trans_sales_no_tax($trans_sales_no_tax);

                $am = $taxable_amount;
                $trans_sales_tax = array();

                foreach ($tax as $tax_id => $tx) {
                    $rate = ($tx['rate'] / 100);
                    $tax_value = ($am / ($rate + 1) ) * $rate;
                    // ($am / 1.12) * .12
                    $trans_sales_tax[] = array(
                        "sales_id"=>$sales_id,
                        "name"=>$tx['name'],
                        "rate"=>$tx['rate'],
                        "amount"=>$tax_value,
                    );
                    $am -= $tax_value;
                }
                
                if(count($trans_sales_tax) > 0)
                    $this->cashier_model->add_trans_sales_tax($trans_sales_tax);
            }
            ### LOCAL TAX 
            if($local_tax > 0){
                $trans_sales_local_tax[] = array(
                    "sales_id"=>$sales_id,
                    "amount"=>$local_tax
                );
                if(count($trans_sales_local_tax) > 0)
                    $this->cashier_model->add_trans_sales_local_tax($trans_sales_local_tax);
            }
            #print
            if ($print == "true" || $print === true){
                // $set = $this->cashier_model->get_pos_settings();
                // $return_print_str=false,$add_reprinted=true,$splits=null,$include_footer=true
                // $no_prints = $set->no_of_receipt_print;
                // $print_echo = $this->print_sales_receipt($sales_id,false,false,true,null,true,$no_prints);
                $print_echo = $this->print_sales_receipt($sales_id,false);
            }
            if ($printKitSlip == "true" || $printKitSlip === true){
                $pet = $this->cashier_model->get_pos_settings();
                $kitchen_printer = $pet->kitchen_printer_name;
                $kitchen_printer_no = $pet->kitchen_printer_name_no;
                $kitchen_printer_beverage = $pet->kitchen_beverage_printer_name;
                $kitchen_printer_beverage_no = $pet->kitchen_beverage_printer_name_no;
                if($kitchen_printer != ""){
                    $this->print_kitchen_order_slip($sales_id,$kitchen_printer,$kitchen_printer_no);            
                    $this->print_kitchen_order_slip_beverage($sales_id,$kitchen_printer_beverage,$kitchen_printer_beverage_no);            
                }
            }
        }
        $this->update_tbl_activity(null,true);
        if($asJson)
            echo json_encode(array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type));
        else
            return array('error'=>$error,'act'=>$act,'id'=>$sales_id,'type'=>$type);
    }
    #SETTLEMENT
    public function settle($sales_id=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/settings_model');
        $this->load->helper('dine/cashier_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null);
        $order = $this->get_order(false,$sales_id);
        
        if(isset($order['order']['table_id']) && $order['order']['table_id'] != ""){
            if(isset($order['order']['table_id'])){
                $error = $this->check_tbl_activity($order['order']['table_id'],false);
                if($error == ""){
                    $this->update_tbl_activity($order['order']['table_id']);
                }
                else{
                    site_alert($error,'error');
                    header("Location:".base_url()."cashier");
                }
            }
        }

        // $discounts = $this->settings_model->get_receipt_discounts();
        $totals = $this->total_trans(false,$order['details'],$order['discounts'],$order['charges'],$order['zero_rated']);
        
        $data['code'] = settlePage($order['order'],$order['details'],$order['discounts'],$totals,$order['charges']);
        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');

        $data['load_js'] = 'dine/cashier.php';
        $data['use_js'] = 'settleJs';
        // $data['noNavbar'] = true;
        $this->load->view('cashier',$data);
    }
    public function get_order_payments($asJson=true,$sales_id=null,$payment_id=null){
        $this->load->model('dine/cashier_model');
        $payments = $this->cashier_model->get_trans_sales_payments($payment_id,array('trans_sales_payments.sales_id'=>$sales_id));
        $pays = array();
        foreach ($payments as $res) {
            $pays[$res->payment_id] = array(
                "sales_id"=>$res->sales_id,
                "type"=>$res->payment_type,
                "amount"=>$res->amount,
                "reference"=>$res->reference,
                "datetime"=>$res->datetime,
                "user_id"=>$res->user_id,
                "username"=>$res->username,
                "to_pay"=>$res->to_pay,
                "card_type"=>$res->card_type
            );
        }
        if($asJson)
            echo json_encode($pays);
        else
            return $pays;
    }
    public function add_payment($sales_id=null,$amount=null,$type=null){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $order = $this->get_order_header(false,$sales_id);
        $error = "";
        $payments = $this->get_order_payments(false,$sales_id);
        $total_to_pay = $order['amount'];
        $paid = $order['paid'];
        $total_paid = 0;
        $balance = $order['balance'];
        if(count($payments) > 0){
            foreach ($payments as $pay_id => $pay) {
                $total_paid += $pay['amount'];
            }
        }
        if($total_to_pay >= $total_paid)
            $total_to_pay -= $total_paid;
        else
            $total_to_pay = 0;
        $change = 0;
        

        $log_user = $this->session->userdata('user');
        if($total_to_pay > 0){
            $payment = array(
                'sales_id'      =>  $sales_id,
                'payment_type'  =>  $type,
                'amount'        =>  $amount,
                'to_pay'        =>  $total_to_pay,
                "user_id"       =>  $log_user['id'],
                // 'reference'     =>  null,
                // 'card_type'     =>  null
            );


            if ($type=="credit") {
                $payment['card_type'] = $this->input->post('card_type');
                $ma = $this->input->post('card_number');
                for ($i=0,$x=strlen($ma); $i < $x-4; $i++) { $ma[$i] = "*"; }
                $payment['card_number'] = $ma;
                $payment['approval_code'] = $this->input->post('approval_code');
            } elseif ($type=="debit") {
                $payment['card_number'] = $this->input->post('card_number');
                $payment['approval_code'] = $this->input->post('approval_code');
            } elseif ($type=="gc") {
                $this->load->model('dine/gift_cards_model');
                $gc_id = $this->input->post('gc_id');
                $gc_code = $this->input->post('gc_code');

                $result = $this->gift_cards_model->get_gift_cards($gc_id,false);

                if (empty($result)) {
                    echo json_encode(array('error'=>'Gift card is invalid'));
                    return false;
                }

                $this->gift_cards_model->update_gift_cards(array('inactive'=>1),$gc_id);
                $payment['reference'] = $gc_code;
                $payment['amount'] = $result[0]->amount;
                $amount = $result[0]->amount;
            } elseif ($type=="coupon") {
                $coupon_id = $this->input->post('coupon_id');
                $coupon_code = $this->input->post('coupon_code');

                $today = date2Sql($this->site_model->get_db_now('sql'));
                $cargs['card_no'] = $coupon_code;
                $result = $this->site_model->get_tbl('coupons',$cargs);
                if (empty($result)) {
                    echo json_encode(array('error'=>'Coupon is invalid'));
                    return false;
                }
                $this->site_model->update_tbl('coupons','coupon_id',array('inactive'=>1),$coupon_id);
                $payment['reference'] = $coupon_code;
                $payment['amount'] = $result[0]->amount;
                $amount = $result[0]->amount;

            } elseif ($type=="chit") {
                $payment['user_id'] = $this->input->post('manager_id');
            }


            $curr_shift_id = $order['shift_id'];
            $time = $this->site_model->get_db_now();
            $get_curr_shift = $this->clock_model->get_shift_id(date2Sql($time),$log_user['id']);
            if(count($get_curr_shift) > 0){
                $curr_shift_id = $get_curr_shift[0]->shift_id;
            }
            $payment_id = $this->cashier_model->add_trans_sales_payments($payment);
            $new_total_paid = 0;
            if($amount > $total_to_pay){
                $new_total_paid = $order['amount'];
                $balance = 0;
            }
            else{
                $new_total_paid = $total_paid+$amount;
                // $balance = $total_to_pay - $amount;
                $balance = $balance - $amount;
            }

            

            // var_dump($payment);
            $this->cashier_model->update_trans_sales(array('total_paid'=>$new_total_paid,'user_id'=>$log_user['id'],'shift_id'=>$curr_shift_id),$sales_id);
            $log_user = $this->session->userdata('user');
            $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Added Payment ".$amount." on Sales Order #".$sales_id,$sales_id);

            if ($balance == 0) {
            //     // if ($paid == 0) {
                    $this->finish_trans($sales_id,true);
                    $set = $this->cashier_model->get_pos_settings();
                    $no_prints = $set->no_of_receipt_print;
                    $order_slip_prints = $set->no_of_order_slip_print;
                    $approved_by = null;
                    if($type == 'chit'){
                        $approved_by = $payment['user_id'];
                        $app = $this->site_model->get_user_details($approved_by);
                        $this->logs_model->add_logs('Sales Order',$app->id,$app->fname." ".$app->mname." ".$app->lname." ".$app->suffix." Approved CHIT Payment ".$amount." on Sales Order #".$sales_id,$sales_id);
                    }
                    $print_echo = $this->print_sales_receipt($sales_id,false,false,true,null,true,$no_prints,$order_slip_prints,$approved_by,false,true);
                    
                    if(MALL_ENABLED){
                        if(MALL == 'megamall'){
                            $this->sm_file($order['datetime']);
                        }
                    }

            //     // }
            }
            // if($paid == 0){
            //     $move = true;
            // }
            // else
            //     $move = false;
            // if(in_array($type, array([0]=>'cash'))){
            if ($type == 'cash') {
                if($amount > $total_to_pay){
                    $change = $amount - $total_to_pay;
                }
            }
        }
        else{
            $error = 'Amount Received.';
        }
        echo json_encode(array('error'=>$error,'change'=>$change,'tendered'=>$amount,'balance'=>num($balance) ));
    }
    public function delete_payment($payment_id=null,$sales_id=null){
        $this->load->model('dine/cashier_model');
        $this->cashier_model->delete_trans_sales_payments($payment_id);
        $payment = $this->get_order_payments(false,$sales_id);
        $order = $this->get_order_header(false,$sales_id);
        $error = "";
        $balance = 0;
        $total_paid = 0;
        foreach ($payment as $payment_id => $pay) {
            $total_paid += $pay['amount'];
        }
        $this->cashier_model->update_trans_sales(array('total_paid'=>$total_paid),$sales_id);
        echo json_encode(array('error'=>$error,'balance'=>$order['amount'] - $total_paid));
    }
    public function finish_trans($sales_id=null,$move=false,$void=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/items_model');
        $this->load->model('core/trans_model');
        $loc_id = 1;
        $trans_type = SALES_TRANS;
        if($void)
            $trans_type = SALES_VOID_TRANS;
        $ref = $this->trans_model->get_next_ref($trans_type);
        $this->trans_model->db->trans_start();
            $this->trans_model->save_ref($trans_type,$ref);
            $this->cashier_model->update_trans_sales(array('trans_ref'=>$ref,'paid'=>1),$sales_id);
            $log_user = $this->session->userdata('user');
            $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Settled Payment on Sales Order #".$sales_id." Reference #".$ref,$sales_id);
            if($move || $move == "true"){
                $opts = array(
                    "type_id" => $trans_type,
                    "trans_id" => $sales_id,
                    "trans_ref" => $ref,
                );
                if($void)
                    $rrr = true;
                else
                    $rrr = false;
                $items = $this->order_items_used($sales_id,$rrr);
                if(count($items) > 0 )
                    $this->items_model->move_items($loc_id,$items,$opts);
            }
            $this->update_tbl_activity(0,true);   
        $this->trans_model->db->trans_complete();
    }
    public function settle_transactions($sales_id=null){
        $payments = $this->get_order_payments(false,$sales_id);
        $this->make->sDiv(array('class'=>'pay-row-list','style'=>'padding:10px;'));
        $icons = array(
            "cash"=>'money',
            "credit"=>'credit-card',
            "debit"=>'credit-card',
            "gift"=>'gift',
            "check"=>'check-square-o',
        );
        $ids = array();
        foreach ($payments as $payment_id => $pay) {
            $this->make->sDiv(array('class'=>'pay-row-div bg-blue','id'=>'pay-row-div-'.$payment_id));
                $this->make->sDivRow();
                    $this->make->sDivCol(2,'left',0,array('style'=>'margin-right:20px;'));
                        $this->make->H(3,fa('fa-'.$icons[$pay['type']].' fa-3x fa-fw'),array('class'=>'headline'));
                    $this->make->eDivCol();
                    $this->make->sDivCol(2);
                        $this->make->H(5,strtoupper($pay['type']));
                        $this->make->H(5,strtoupper(sql2DateTime($pay['datetime'])));
                    $this->make->eDivCol();
                    $this->make->sDivCol(5);
                        $this->make->H(5,'Tendered: PHP '.strtoupper(num($pay['amount'])));
                        $change = 0;
                        if($pay['amount'] > $pay['to_pay'])
                            $change = $pay['amount'] - $pay['to_pay'];
                        $this->make->H(5,'Change:   PHP '.strtoupper(num($change)));
                        $this->make->H(5,strtoupper($pay['username']));
                    $this->make->eDivCol();
                    $this->make->sDivCol(2,'right',0,array('style'=>'margin-top:10px;'));
                        $this->make->button(fa('fa-ban fa-lg fa-fw').' VOID',array('id'=>'void-payment-btn-'.$payment_id,'ref'=>$payment_id,'class'=>'btn-block settle-btn-red double'));
                    $this->make->eDivCol();
                $this->make->eDivRow();
            $this->make->eDiv();
            $ids[] = $payment_id;
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }
	public function manager_settle_transactions($sales_id=null){
        // echo "Sales ID : ".$sales_id."<br>";
		$payments = $this->get_order_payments(false,$sales_id);
		// echo $this->db->last_query();
        $this->make->sDiv(array('class'=>'pay-row-list','style'=>'padding:10px; background-color:#0073b7;'));
        $icons = array(
            "cash"=>'money',
            "credit"=>'credit-card',
            "debit"=>'credit-card',
            "gift"=>'gift',
            "check"=>'check-square-o',
        );
        $ids = array();
        foreach ($payments as $payment_id => $pay) {
            $this->make->sDiv(array('class'=>'pay-row-div bg-blue','id'=>'pay-row-div-'.$payment_id));
                $this->make->sDivRow(array('class'=>'bg-blue'));
                    $this->make->sDivCol(2,'left',0,array('style'=>'margin-right:20px;', 'class'=>'bg-blue'));
                        $this->make->H(3,fa('fa-'.$icons[$pay['type']].' fa-3x fa-fw'),array('class'=>'headline'));
                    $this->make->eDivCol();
                    $this->make->sDivCol(2,'',0,array('class'=>'bg-blue'));
                        // $this->make->H(5,'Sales ID #'.$sales_id); // !!!
                        $this->make->H(5,strtoupper($pay['type']));
                        $this->make->H(5,strtoupper(sql2DateTime($pay['datetime'])));
                    $this->make->eDivCol();
                    $this->make->sDivCol(5,'',0,array('class'=>'bg-blue'));
                        $this->make->H(5,'Tendered: PHP '.strtoupper(num($pay['amount'])));
                        $change = 0;
                        if($pay['amount'] > $pay['to_pay'])
                            $change = $pay['amount'] - $pay['to_pay'];
                        $this->make->H(5,'Change:   PHP '.strtoupper(num($change)));
                        $this->make->H(5,strtoupper($pay['username']));
                    $this->make->eDivCol();
                    // $this->make->sDivCol(2,'right',0,array('style'=>'margin-top:10px;'));
                        // $this->make->button(fa('fa-ban fa-lg fa-fw').' VOID',array('id'=>'void-payment-btn-'.$payment_id,'ref'=>$payment_id,'class'=>'btn-block settle-btn-red double'));
                    // $this->make->eDivCol();
					$this->make->sDivCol(2,'right',0,array('style'=>'margin-top:10px;', 'class'=>'bg-blue'));
                       $this->make->H(5,"Sales ID #".$sales_id);
                    $this->make->eDivCol();
                $this->make->eDivRow();
            $this->make->eDiv();
            $ids[] = $payment_id;
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }
    public function order_items_used($sales_id=null,$add=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/mods_model');
        $this->load->model('dine/items_model');
        $order = $this->get_order(false,$sales_id);
        $details = $order['details'];
        $menus = array();
        $mods = array();
        $items = array();
        foreach ($details as $det) {
            if(!isset($det['retail'])){
                $menus[] = $det['menu_id'];
                if(count($det['modifiers']) > 0){
                    foreach ($det['modifiers'] as $mod_id => $mod) {
                        $mods[] = $mod['id'];
                    }
                }
            }
            else{
                $items[] = $det['menu_id'];
            }
        }
        $me = array();
        $itms = array();
        $menu_recipe = array();
        if(count($menus) > 0)
            $menu_recipe = $this->menu_model->get_recipe_items($menus);
        if(count($items) > 0){
            $menu_items = $this->items_model->get_item($items);  
            foreach ($menu_items as $itm) {
                $itms[$itm->item_id] = array('item_uom'=>$itm->uom);
            }      
        }
        
        foreach ($menu_recipe as $mn) {
            $me[$mn->menu_id][$mn->item_id] = array('item_uom'=>$mn->uom,'item_qty'=>$mn->qty);
        }
        $mods_recipe = array();
        if(count($mods) > 0)
            $mods_recipe = $this->mods_model->get_modifier_recipe(null,$mods);
        $mo = array();
        foreach ($mods_recipe as $mn) {
            $mo[$mn->mod_id][$mn->item_id] = array('item_uom'=>$mn->uom,'item_qty'=>$mn->qty);
        }
        $items = array();
        foreach ($details as $line_id => $det) {
            $mul = $det['qty'];
            if(!isset($det['retail'])){
                if(isset($me[$det['menu_id']])){
                   foreach ($me[$det['menu_id']] as $item_id => $opt) {
                     
                       if(isset($items[$item_id])){
                            if($add)
                                $items[$item_id]['qty'] += ($mul * $opt['item_qty']);
                            else
                                $items[$item_id]['qty'] += (($mul * $opt['item_qty']) * -1);

                            $items[$item_id]['uom'] = $opt['item_uom'];
                       }
                       else{
                            if($add)
                                $items[$item_id]['qty'] = ($mul * $opt['item_qty']);
                            else
                                $items[$item_id]['qty'] = (($mul * $opt['item_qty']) * -1);
                            $items[$item_id]['uom'] = $opt['item_uom'];
                       }

                   }
                }
                #
                if(count($det['modifiers']) > 0){
                    foreach ($det['modifiers'] as $mod_id => $mod) {
                        if(isset($mo[$mod['id']])){
                            foreach ($mo[$mod['id']] as $mod_item_id => $mopt) {
                               if(isset($items[$mod_item_id])){
                                    if($add)
                                        $items[$mod_item_id]['qty'] += ($mul * $mopt['item_qty']);
                                    else
                                        $items[$mod_item_id]['qty'] += (($mul * $mopt['item_qty']) * -1);
                                    $items[$mod_item_id]['uom'] = $mopt['item_uom'];
                               }
                               else{
                                    if($add)
                                        $items[$mod_item_id]['qty'] = ($mul * $mopt['item_qty']);
                                    else
                                        $items[$mod_item_id]['qty'] = (($mul * $mopt['item_qty']) * -1);
                                    $items[$mod_item_id]['uom'] = $mopt['item_uom'];
                               }
                           }
                        }
                        #
                    }
                }
                #
            }
            else{
               if(isset($itms[$det['menu_id']])){
                    if(isset($items[$det['menu_id']])){
                        if($add)
                            $items[$det['menu_id']]['qty'] += $det['qty'];
                        else
                            $items[$det['menu_id']]['qty'] += ($det['qty'] * -1);

                        $items[$det['menu_id']]['uom'] = $itms[$itm->item_id]['item_uom'];
                   }
                   else{
                        if($add)
                            $items[$det['menu_id']]['qty'] = $det['qty'];
                        else
                            $items[$det['menu_id']]['qty'] = ($det['qty'] * -1);
                        $items[$det['menu_id']]['uom'] = $itms[$itm->item_id]['item_uom'];
                   }
               } 
            }
        }
        #
        return $items;
    }
    public function reprint_receipt_previous($sales_id=null){
        $this->print_sales_receipt($sales_id,false,false,true,null,true,1,0,null,true);           
    }

    public function print_sales_receipt($sales_id=null,$asJson=true,$return_print_str=false,$add_reprinted=true,$splits=null,$include_footer=true,$no_prints=1,$order_slip_prints=0,$approved_by=null,$main_db=false,$openDrawer=false)
    {
        // // Load PHPRtfLite Class
        // require_once APPPATH."/third_party/PHPRtfLite.php";

        /*
         * -----------------------------------------------------------
         *      Start of Receipt Printing
         * -----------------------------------------------------------
        */
        if($main_db){
            $this->db = $this->load->database('main', TRUE);
        }

        $branch = $this->get_branch_details(false);
        $return = $this->get_order(false,$sales_id);
        $order = $return['order'];
        $details = $return['details'];
        $payments = $return['payments'];
        $discounts = $return['discounts'];
        $local_tax = $return['local_tax'];
        $charges = $return['charges'];
        $tax = $return['taxes'];
        $no_tax = $return['no_tax'];
        $zero_rated = $return['zero_rated'];
        $totalsss = $this->total_trans(false,$details,$discounts);
        $discs = $totalsss['discs'];
        $print_str = "\r\n\r\n\r\n\r\n";

        $wrap = wordwrap($branch['name'],25,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

        $wrap = wordwrap($branch['address'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

            // .$this->align_center(wordwrap($branch['address'],20,"|#|"),38," ")."\r\n"
            $print_str .= 
            $this->align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .$this->align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
            // .$this->align_center('BIR: '.$branch['bir'],42," ")."\r\n"
            .$this->align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
            // .$this->align_center('SN #'.$branch['serial'],38," ")."\r\n"
            .$this->align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n\r\n";
            // ."=========================================="."\r\n"
            // ;
        if (!empty($order['void_ref']) || $order['inactive'] == 1) {
            $print_str .= $this->align_center("***** VOIDED TRANSACTION *****",38," ")."\r\n";
            $print_str .= $order['reason']."\r\n\r\n";
        }
        $header_print_str = $print_str;
        $header_print_str .= "======================================"."\r\n";
             if (!empty($payments)){
                $header_print_str .= "Receipt # ".$order['ref']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center("Receipt # ".$order['ref']." - ".strtoupper($order['type']),42," ")."\r\n";
            }
            else{
                $header_print_str .= "Reference # ".$order['sales_id']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center(strtoupper($order['type'])." # ".$order['sales_id'],42," ")."\r\n";
            }
        $header_print_str .= "======================================"."\r\n";

        // $print_str .= $this->align_center(date('Y-m-d H:i:s',strtotime($order['datetime']))." ".$order['terminal_name']." ".$order['name'],42," ")."\r\n";
        $print_str .= $this->append_chars(ucwords($order['name']),"right",19," ").$this->append_chars(date('Y-m-d H:i:s',strtotime($order['datetime'])),"left",19," ")."\r\n"
            ."Terminal ID : ".$order['terminal_code']."\r\n"
            ."======================================"."\r\n";

        if (!empty($payments)){
            $print_str .= "Receipt # ".$order['ref']." - ".strtoupper($order['type'])."\r\n";
                // $this->align_center("Receipt # ".$order['ref']." - ".strtoupper($order['type']),42," ")."\r\n";
        }
        else{
            $print_str .= "Reference # ".$order['sales_id']." - ".strtoupper($order['type'])."\r\n";
                // $this->align_center(strtoupper($order['type'])." # ".$order['sales_id'],42," ")."\r\n";
        }

        if($order['waiter_id'] != ""){
            $print_str .= "FS - ".ucwords(strtolower($order['waiter_name']))."\r\n";
        }

        $orddetails = "";
        if($order['table_id'] != "" || $order['table_id'] != 0)
            $orddetails .= $order['table_name']." ";

        if($order['guest'] != 0)
            $orddetails .= "Guest #".$order['guest'];

        if($orddetails != "")
            $print_str .= $this->align_center($orddetails,38," ")."\r\n";
        

        $log_user = $this->session->userdata('user');
        if (!empty($payments)) {
            if($add_reprinted){
                if($order['printed'] >= 1){
                    $print_str .= $this->append_chars('[REPRINTED]',38," ")."\r\n";
                    $this->cashier_model->update_trans_sales(array('printed'=>$order['printed']+1),$order['sales_id']);
                    $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Reprinted Receipt on Sales Order #".$order['sales_id']." Reference #".$order['ref'],$order['sales_id']);
                }
                else{
                    $this->cashier_model->update_trans_sales(array('printed'=>1),$order['sales_id']);
                    if(!$return_print_str){
                        $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Printed Receipt on Sales Order #".$order['sales_id']." Reference #".$order['ref'],$order['sales_id']);
                    }
                }
            }
            else{
                $this->cashier_model->update_trans_sales(array('printed'=>1),$order['sales_id']);
                if(!$return_print_str){
                    $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Printed Receipt on Sales Order #".$order['sales_id']." Reference #".$order['ref'],$order['sales_id']);
                }
            }
        }
        else{
            $this->logs_model->add_logs('Sales Order',$log_user['id'],$log_user['full_name']." Printed Billing on Sales Order #".$order['sales_id'],$order['sales_id']);
        }


        $print_str .= $this->append_chars("","right",38,"=")."\r\n";

        /*

            foreach ($order_menus as $men) {
                $details[$men->line_id] = array(
                    "id"=>$men->sales_menu_id,
                    "menu_id"=>$men->menu_id,
                    "name"=>$men->menu_name,
                    "code"=>$men->menu_code,
                    "price"=>$men->price,
                    "qty"=>$men->qty,
                    "no_tax"=>$men->no_tax,
                    "discount"=>$men->discount
                );
                $mods = array();
                foreach ($order_mods as $mod) {
                    if($mod->line_id == $men->line_id){
                        $mods[$mod->sales_mod_id] = array(
                            "id"=>$mod->mod_id,
                            "line_id"=>$mod->line_id,
                            "name"=>$mod->mod_name,
                            "price"=>$mod->price,
                            "qty"=>$mod->qty,
                            "discount"=>$mod->discount
                        );
                    }
                }
                $details[$men->line_id]['modifiers'] = $mods;
            }
        */

        /* NEW BLOCK */
        $pre_total = 0;
        $post_details = array();
        $discs_items = array();
        foreach ($discs as $disc) {
            if(isset($disc['items']))
                $discs_items[$disc['type']] = $disc['items'];
        }

        $dscTxt = array();
        foreach ($details as $line_id => $val) {
            foreach ($discs_items as $type => $dissss) {
                if(in_array($line_id, $dissss)){
                    $qty = 1;
                    if(isset($dscTxt[$val['menu_id']][$type]['qty'])){
                        $qty = $dscTxt[$val['menu_id']][$type]['qty'] + 1;
                    }
                    $dscTxt[$val['menu_id']][$type] = array('txt' => '#'.$type,'qty' => $qty);
                }
            }
        }

        foreach ($details as $line_id => $val) {
            if (!isset($post_details[$val['menu_id']])) {
                $dscsacs = array();
                if(isset($dscTxt[$val['menu_id']])){
                    $dscsacs = $dscTxt[$val['menu_id']];
                }
                $remarksArr = array();
                if($val['remarks'] != '')
                    $remarksArr = array($val['remarks']." x ".$val['qty']);
                $post_details[$val['menu_id']] = array(
                    'name' => $val['name'],
                    'code' => $val['code'],
                    'price' => $val['price'],
                    'no_tax' => $val['no_tax'],
                    'discount' => $val['discount'],
                    'qty' => $val['qty'],
                    'discounted'=>$dscsacs,
                    'remarks'=>$remarksArr,
                    'modifiers' => array()
                );
            } else {
                if($val['remarks'] != "")
                    $post_details[$val['menu_id']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                $post_details[$val['menu_id']]['qty'] += $val['qty'];
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            $n_modifiers = $post_details[$val['menu_id']]['modifiers'];
            foreach ($modifs as $vv) {
                if (!isset($n_modifiers[$vv['id']])) {
                    $n_modifiers[$vv['id']] = array(
                        'name' => $vv['name'],
                        'price' => $vv['price'],
                        'qty' => $val['qty'],
                        'discount' => $vv['discount']
                    );
                } else {
                    $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                }
            }
            $post_details[$val['menu_id']]['modifiers'] = $n_modifiers;
        }
        /* END NEW BLOCK */
        $tot_qty = 0;
        foreach ($post_details as $val) {
            $tot_qty += $val['qty'];
            $print_str .= $this->append_chars($val['qty'],"right",4," ");

            if ($val['qty'] == 1) {
                $print_str .= $this->append_chars(substrwords($val['name'],21,""),"right",26," ").
                    $this->append_chars(number_format($val['price'],2),"left",8," ")."\r\n";
                $pre_total += $val['price'];
            } else {
                $print_str .= $this->append_chars(substrwords($val['name'],21,"")." @ ".$val['price'],"right",26," ").
                    $this->append_chars(number_format($val['price'] * $val['qty'],2),"left",8," ")."\r\n";
                $pre_total += $val['price'] * $val['qty'];
            }
            if(count($val['discounted']) > 0){
                foreach ($val['discounted'] as $dssstxt) {
                  $print_str .= "      ";
                  $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",23," ")."\r\n";
                }
            }
            if(isset($val['remarks']) && count($val['remarks']) > 0){
                foreach ($val['remarks'] as $rmrktxt) {
                    $print_str .= "     * ";
                    $print_str .= $this->append_chars(ucwords($rmrktxt),"right",23," ")."\r\n";
                }
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            foreach ($modifs as $vv) {
                $print_str .= "     * ".$vv['qty']." ";

                if ($vv['qty'] == 1) {
                    $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                        .$this->append_chars(number_format($vv['price'],2),"left",8," ")."\r\n";
                    $pre_total += $vv['price'];
                } else {
                    $print_str .= $this->append_chars(substrwords($vv['name'],18,"")." @ ".$vv['price'],"right",23," ")
                        .$this->append_chars(number_format($vv['price'] * $vv['qty'],2),"left",8," ")."\r\n";
                    $pre_total += $vv['price'] * $vv['qty'];
                }
            }
            


            //DISCOUNT PALATANDAAN
            // if(in_array($val[''], haystack))

        }

        $print_str .= $this->append_chars("","right",38,"=");

        // $vat = round($order['amount'] / (1 + BASE_TAX) * BASE_TAX,1);
        $vat = 0;
        if($tax > 0){
            foreach ($tax as $tx) {
               $vat += $tx['amount'];
            }
        }
        $no_tax_amt = 0;
        foreach ($no_tax as $k=>$v) {
            $no_tax_amt += $v['amount'];
        }

        $zero_rated_amt = 0;
        foreach ($zero_rated as $k=>$v) {
            $zero_rated_amt += $v['amount'];
        }
        if($zero_rated_amt > 0){
            $no_tax_amt = 0;
        }

        $print_str .= "\r\n".$this->append_chars(ucwords("TOTAL"),"right",28," ").$this->append_chars("P ".number_format(($pre_total),2),"left",10," ")."\r\n";
        $print_str .= $this->append_chars(ucwords("TOTAL QTY"),"right",28," ").$this->append_chars(number_format(($tot_qty),2),"left",10," ")."\r\n";
        // if(count($discs) > 0){
        //     foreach ($discs as $ds) {
        //         $print_str .= "\r\n".$this->append_chars(strtoupper($ds['type']),"right",28," ").$this->append_chars("P (".number_format($ds['amount'],2).")","left",10," ")."\r\n";
        //     }
        // }
        // $print_str .= "\r\n";
        $total_discounts = 0;
        foreach ($discounts as $dcs_ci => $dcs) {
            foreach ($dcs['persons'] as $code => $dcp) {
                // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                $total_discounts += $dcp['amount'];
            }
        }
        $total_discounts_non_vat = 0;
        foreach ($discounts as $dcs_ci => $dcs) {
           
            foreach ($dcs['persons'] as $code => $dcp) {
                // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                if($dcs['no_tax'] == 1){
                    $total_discounts_non_vat += $dcp['amount'];
                }
            }
        }
        $total_charges = 0;
        if(count($charges) > 0){
            foreach ($charges as $charge_id => $opt) {
                $total_charges += $opt['total_amount'];
            }
        }
        $local_tax_amt = 0;
        if(count($local_tax) > 0){
            foreach ($local_tax as $lt_id => $lt) {
                $local_tax_amt += $lt['amount'];
            }
        }    
        $vat_sales = ( ( ( $order['amount'] - num($total_charges + $local_tax_amt) ) - $vat)  - $no_tax_amt + $total_discounts_non_vat ) - $zero_rated_amt;
        // $vat_sales = ( ( ( $order['amount'] ) - $vat)  - $no_tax_amt + $total_discounts) - $zero_rated_amt;
        // echo "vat_sales= ((".$order['amount']." - ".$total_charges."))- ".$vat." )- ".$no_tax_amt." + ".$total_discounts." - ".$zero_rated_amt;
        if($vat_sales < 0){
            $vat_sales = 0;
        }
        $print_str .= "\r\n".$this->append_chars(ucwords("VAT SALES"),"right",28," ").$this->append_chars(num($vat_sales),"left",10," ")."\r\n";
        $print_str .= $this->append_chars(ucwords("VAT EXEMPT SALES"),"right",28," ").$this->append_chars(number_format($no_tax_amt,2),"left",10," ")."\r\n";
        $print_str .= $this->append_chars(ucwords("VAT ZERO RATED"),"right",28," ").$this->append_chars(number_format($zero_rated_amt,2),"left",10," ")."\r\n";
        
        
        
        #CONDITION TO NA PINADAGDAG NG TAGAYTAY - FOR SENIOR CITEZEN VIEW VAT PLUS DISCOUNT
        // if(count($discounts) >0){
        //     if(count($dcs['persons']) > 0){
        //         $print_str .= $this->append_chars(ucwords("Less VAT (12%)"),"right",28," ").$this->append_chars("(".number_format($pre_total - $no_tax_amt,2).")","left",10," ")."\r\n";
        //     }
        // }
        // else{
        //     if($tax > 0){
        //         foreach ($tax as $tx) {
        //            $print_str .= $this->append_chars($tx['name']."(".$tx['rate']."%)","right",28," ").$this->append_chars(number_format($tx['amount'],2),"left",10," ")."\r\n";
        //         }
        //     }
        // }


        #CONDITION TO NA PARA SA TAGUEGARAO
        if($tax > 0){
            foreach ($tax as $tx) {
               $print_str .= $this->append_chars($tx['name']."(".$tx['rate']."%)","right",28," ").$this->append_chars(number_format($tx['amount'],2),"left",10," ")."\r\n";
            }
        }
        if(count($local_tax) > 0){
            $local_tax_amt = 0;
            foreach ($local_tax as $lt_id => $lt) {
                $local_tax_amt += $lt['amount'];
            }
            $print_str .= $this->append_chars(ucwords("LOCAL TAX"),"right",28," ").$this->append_chars(number_format($local_tax_amt,2),"left",10," ")."\r\n";
        }
        if(count($discounts) >0){
            if(count($dcs['persons']) > 0){
                $print_str .= "\r\n";
                $print_str .= "======================================"."\r\n";
                $print_str .= "          Discount Details"."\r\n";
                $print_str .= "======================================"."\r\n";
                foreach ($discounts as $dcs_ci => $dcs) {
                    foreach ($dcs['persons'] as $code => $dcp) {
                        $print_str .= $this->append_chars($dcs_ci." (".$dcp['disc_rate']."%)","right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                        $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                        $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                    }
                }
                // $print_str .= "\r\n";
                $less_vat = ($pre_total - ($order['amount'] - num($total_charges + $local_tax_amt) ) ) - $total_discounts;
                // $print_str .= $this->append_chars(ucwords("Total Discount"),"right",28," ").$this->append_chars(number_format($total_discounts,2),"left",10," ")."\r\n";
                $print_str .= $this->append_chars(ucwords("Total Less VAT"),"right",28," ").$this->append_chars(number_format( $less_vat,2),"left",10," ")."\r\n";
                $print_str .= $this->append_chars(ucwords("Total Amount Discounted"),"right",28," ").$this->append_chars(number_format( ($total_discounts + $less_vat),2),"left",10," ")."\r\n";
            }
        }

        if(count($charges) > 0){
            $print_str .= "\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= "              CHARGES"."\r\n";
            $print_str .= "======================================"."\r\n";
            foreach ($charges as $charge_id => $opt) {
                $charge_amount = $opt['total_amount'];
                // if($opt['absolute'] == 0){
                //     $charge_amount = ($opt['amount'] / 100) * ($order['amount'] - $vat);
                // }
                $print_str .= $this->append_chars($opt['name'],"right",28," ").$this->append_chars(number_format($charge_amount,2),"left",10," ")."\r\n";
            }
            $print_str .= "======================================"."\r\n";
        }

        if (!empty($payments)) {

            $print_str .= "\r\n";
            // $print_str .= "\r\n"."======================================"."\r\n";
            $print_str .= $this->append_chars("Amount due","right",28," ").$this->append_chars("P ".number_format($order['amount'],2),"left",10," ")."\r\n";

            $pay_total = 0;
            $gft_ctr = 0;
            $nor_ctr = 0;
            foreach ($payments as $payment_id => $opt) {

                $print_str .= $this->append_chars(ucwords($opt['payment_type']),"right",28," ").$this->append_chars("P ".number_format($opt['amount'],2),"left",10," ")."\r\n";
                if (!empty($opt['reference'])) {
                    $print_str .= $this->append_chars("     Reference ".$opt['reference'],"right",38," ")."\r\n";
                }

                if (!empty($opt['card_number'])) {
                    $print_str .= $this->append_chars("  Card #: ".$opt['card_number'],"right",38," ")."\r\n";
                    if (!empty($opt['approval_code']))
                        $print_str .= $this->append_chars("  Approval #: ".$opt['approval_code'],"right",38," ")."\r\n";
                }
                $pay_total += $opt['amount'];
                if($opt['payment_type'] == 'gc'){
                    $gft_ctr++;
                }
                else
                    $nor_ctr++;
                
            }
            if($gft_ctr == 1 && $nor_ctr == 0)
                $print_str .= $this->append_chars("Change","right",28," ").$this->append_chars("P ".number_format(0,2),"left",10," ")."\r\n";
            else
                $print_str .= $this->append_chars("Change","right",28," ").$this->append_chars("P ".number_format($pay_total - $order['amount'],2),"left",10," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            if ($include_footer) {
                $print_str .= "\r\n\r\n"
                // .$this->align_center("This serves as customer slip.",38," ")."\r\n"
                // .$this->align_center("Please ask for an official receipt.",38," ")."\r\n"
                // .$this->align_center("Thank you and please come again.",38," ")."\r\n";

                // .$this->align_center("For feedback, please call us at",38," ")."\r\n"
                // .$this->align_center($branch['contact_no'],38," ")."\r\n"
                // .$this->align_center("Email : ".$branch['email'],38," ")."\r\n"
                // .$this->align_center(" Please visit us at ".$branch['website'],38," ")."\r\n";
                .$this->align_center("This serves as your official receipt.",38," ")."\r\n"
                .$this->align_center("Thank you for coming.",38," ")."\r\n"
                .$this->align_center("For feedback, please call us at",38," ")."\r\n"
                .$this->align_center($branch['contact_no'],38," ")."\r\n";
                if($branch['email'] != ""){
                    $print_str .= $this->align_center("Or Email us at",38," ")."\r\n" 
                               .$this->align_center($branch['email'],38," ")."\r\n";
                }
                if($branch['website'] != "")
                    $print_str .= $this->align_center("Please visit us at \r\n".$branch['website'],38," ")."\r\n";
            }

        } else {
            $print_str .= "\r\n".$this->append_chars("","right",38,"=");
            $print_str .= "\r\n\r\n".$this->append_chars("Billing Amount","right",28," ").$this->append_chars("P ".number_format($order['amount'],2),"left",10," ")."\r\n";
            if(is_array($splits)){
                $print_str .= $this->append_chars("Split Amount by ".$splits['by'],"right",28," ").$this->append_chars("P ".number_format($splits['total'],2),"left",10," ")."\r\n";
            }
            if ($include_footer) {
                $print_str .= "\r\n\r\n"
                // .$this->align_center("This serves as customer slip.",38," ")."\r\n"
                // .$this->align_center("Please ask for an official receipt.",38," ")."\r\n"
                // .$this->align_center("Thank you and please come again.",38," ")."\r\n";

                // .$this->align_center("This serves as your unofficial receipt.",38," ")."\r\n"
                // .$this->align_center("Thank you for coming.",38," ")."\r\n";


                .$this->align_center("For feedback, please call us at",38," ")."\r\n"
                .$this->align_center($branch['contact_no'],38," ")."\r\n";
                if($branch['email'] != ""){
                    $print_str .= $this->align_center("Or Email us at",38," ")."\r\n" 
                               .$this->align_center($branch['email'],38," ")."\r\n";
                }
                if($branch['website'] != "")
                    $print_str .= $this->align_center("Please visit us at \r\n".$branch['website'],38," ")."\r\n";
            }

        }

        if (!empty($payments)) {
            $print_str .= "\r\n";
            foreach ($discounts as $dcs_ci => $dcs) {
                if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                    $print_str .= $this->align_center("==========================================",42," ");
                    break;
                }
            }
            foreach ($discounts as $dcs_ci => $dcs) {
                if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                    foreach ($dcs['persons'] as $code => $dcp) {
                        // ."\r\n"
                        $print_str .= "\r\n".$this->append_chars("ID NO      : ".$dcp['code'],"right",28," ");
                        $print_str .= "\r\n".$this->append_chars("NAME       : ".$dcp['name'],"right",28," ");
                        $print_str .= "\r\n".$this->append_chars("ADDRESS    : ","right",28," ");
                        $print_str .= "\r\n".$this->append_chars("SIGNATURE  : ","right",28," ");
                        $print_str .= "\r\n".$this->append_chars("             _____________________________","right",28," ")."\r\n";
                        // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                    }
                }
            }
            foreach ($discounts as $dcs_ci => $dcs) {
                if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                    $print_str .= $this->align_center("==========================================",42," ");
                    break;
                }
            }
        }

        if($approved_by != null){
            $app = $this->site_model->get_user_details($approved_by);
            $approver = $app->fname." ".$app->mname." ".$app->lname." ".$app->suffix;
            $print_str .= $this->align_center("==========================================",42," ");
            $print_str .= "\r\n".$this->append_chars("Approved By : ".$approver,"right",28," ");
            $print_str .= "\r\n".$this->append_chars("             _____________________________","right",28," ")."\r\n";
            $print_str .= $this->align_center("==========================================",42," ");
        }


        if ($return_print_str) {
            return $print_str;
        }
        // echo "<pre>".$print_str."</pre>";
       

        $filename = "sales.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print.bat";
        $fh1 = fopen($batfile,'w+');
        $root = dirname(BASEPATH);
        $battxt ="NOTEPAD /P \"".realpath($root."/".$filename)."\"";

        if($openDrawer){
            $pet = $this->cashier_model->get_pos_settings();
            $open_drawer_printer = $pet->open_drawer_printer;
            if($open_drawer_printer != ""){
                $battxt = "NOTEPAD /PT \"".realpath($root."/".$filename)."\" \"".$open_drawer_printer."\"  ";   
            }            
        }

        fwrite($fh1, $battxt);
        fclose($fh1);
        session_write_close();
        // exec($filename);
        for ($i=0; $i < $no_prints; $i++) { 
            exec($batfile);
        }
        session_start();
        unlink($filename);
        unlink($batfile);

        if($order_slip_prints > 0){
            $this->print_order_slip($header_print_str,$post_details,$order_slip_prints);            
        }

        if ($asJson)
            echo json_encode(array('msg'=>'Receipt # '.(!empty($order['ref']) ? $order['ref'] : $sales_id).' has been printed'));
        else
            return array('msg'=>'Receipt # '.(!empty($order['ref']) ? $order['ref'] : $sales_id).' has been printed');
    }
    public function print_order_slip($header_print_str=null,$post_details=array(),$order_slip_prints=0){
        $print_str = $header_print_str;
        $print_str .=  $this->align_center('Order Slip',38," ")."\r\n";
        $tot_qty = 0;
        foreach ($post_details as $val) {
            $tot_qty += $val['qty'];
            $print_str .= $this->append_chars($val['qty'],"right",4," ");

            if ($val['qty'] == 1) {
                $print_str .= $this->append_chars(substrwords($val['name'],21,""),"right",26," ").
                    $this->append_chars(null,"left",8," ")."\r\n";
            } else {
                $print_str .= $this->append_chars(substrwords($val['name'],21,"")." @ ".$val['price'],"right",26," ").
                    $this->append_chars(null,"left",8," ")."\r\n";
            }
            if(count($val['discounted']) > 0){
                foreach ($val['discounted'] as $dssstxt) {
                  $print_str .= "      ";
                  $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",23," ")."\r\n";
                }
            }
            if(isset($val['remarks']) && count($val['remarks']) > 0){
                foreach ($val['remarks'] as $rmrktxt) {
                    $print_str .= "     * ";
                    $print_str .= $this->append_chars(ucwords($rmrktxt),"right",23," ")."\r\n";
                }
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            foreach ($modifs as $vv) {
                $print_str .= "     * ";

                if ($vv['qty'] == 1) {
                    $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                        .$this->append_chars(null,"left",8," ")."\r\n";
                } else {
                    $print_str .= $this->append_chars(substrwords($vv['name'],18,"")." @ ".$vv['price'],"right",23," ")
                        .$this->append_chars(null,"left",8," ")."\r\n";
                }
            }
        }
        $print_str .= "\r\n"."--------------------------------------"."\r\n";
        $print_str .= $this->append_chars(ucwords("TOTAL QTY"),"right",28," ").$this->append_chars(number_format(($tot_qty),2),"left",10," ")."\r\n";

        $filename = "order.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print.bat";
        $fh1 = fopen($batfile,'w+');
        $root = dirname(BASEPATH);

        fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        fclose($fh1);
        session_write_close();
        for ($i=0; $i < $order_slip_prints; $i++) { 
            exec($batfile);
        }
        session_start();
        unlink($filename);
        unlink($batfile);
    }
    public function print_kitchen_order_slip($sales_id=null,$kitchen_printer=null,$kitchen_printer_no=0){
        $return = $this->get_order(false,$sales_id);
        $branch = $this->get_branch_details(false);
        $order = $return['order'];
        $details = $return['details'];

        $discounts = $return['discounts'];
        $totalsss = $this->total_trans(false,$details,$discounts);
        $discs = $totalsss['discs'];


        $print_str = "\r\n\r\n\r\n\r\n";
        $wrap = wordwrap($branch['name'],25,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

        $wrap = wordwrap($branch['address'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

            // .$this->align_center(wordwrap($branch['address'],20,"|#|"),38," ")."\r\n"
            $print_str .= 
            $this->align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .$this->align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
            // .$this->align_center('BIR: '.$branch['bir'],42," ")."\r\n"
            .$this->align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
            // .$this->align_center('SN #'.$branch['serial'],38," ")."\r\n"
            .$this->align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n\r\n";
            // ."=========================================="."\r\n"
            // ;
        if (!empty($order['void_ref']) || $order['inactive'] == 1) {
            $print_str .= $this->align_center("***** VOIDED TRANSACTION *****",38," ")."\r\n";
            $print_str .= $order['reason']."\r\n\r\n";
        }
        $header_print_str = $print_str;
        $header_print_str .= "======================================"."\r\n";
            if (!empty($payments)){
                $header_print_str .= "Receipt # ".$order['ref']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center("Receipt # ".$order['ref']." - ".strtoupper($order['type']),42," ")."\r\n";
            }
            else{
                $header_print_str .= "Reference # ".$order['sales_id']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center(strtoupper($order['type'])." # ".$order['sales_id'],42," ")."\r\n";
            }
            $header_print_str .= $order['table_name']."\r\n";
            if($order['waiter_username'] != "")
                $header_print_str .= "FS: ".$order['waiter_username']."\r\n";
            $header_print_str .= $order['datetime']."\r\n";
        $header_print_str .= "======================================"."\r\n";
        $print_str = $header_print_str;
        ################################
        $discs_items = array();
        foreach ($discs as $disc) {
            if(isset($disc['items']))
                $discs_items[$disc['type']] = $disc['items'];
        }
        $dscTxt = array();
        foreach ($details as $line_id => $val) {
            foreach ($discs_items as $type => $dissss) {
                if(in_array($line_id, $dissss)){
                    $qty = 1;
                    if(isset($dscTxt[$val['menu_id']][$type]['qty'])){
                        $qty = $dscTxt[$val['menu_id']][$type]['qty'] + 1;
                    }
                    $dscTxt[$val['menu_id']][$type] = array('txt' => '#'.$type,'qty' => $qty);
                }
            }
        }
        $post_details = array();
        $update_line_ids = array();
        $update_line_mod_ids = array();
        $added_modifs = array();
        foreach ($details as $line_id => $val) {
            $modif_check = false;
            $category = $this->site_model->get_tbl('menus',array('menu_id'=>$val['menu_id']),array(),null,true,'menu_sub_cat_id');
            $cat = $category[0];
            if(BEVERAGE_ID != $cat->menu_sub_cat_id){
                if($val['kitchen_slip_printed'] == 0){
                        if (!isset($post_details[$val['menu_id']])) {
                            $dscsacs = array();
                            if(isset($dscTxt[$val['menu_id']])){
                                $dscsacs = $dscTxt[$val['menu_id']];
                            }
                            $remarksArr = array();
                            if($val['remarks'] != '')
                                $remarksArr = array($val['remarks']." x ".$val['qty']);
                            
                            $kitchen_slip_printed = $val['kitchen_slip_printed'];
                            if($val['kitchen_slip_printed'] == ""){
                                $kitchen_slip_printed = 0;
                            }

                            $post_details[$val['menu_id']] = array(
                                'name' => $val['name'],
                                'code' => $val['code'],
                                'price' => $val['price'],
                                'no_tax' => $val['no_tax'],
                                'discount' => $val['discount'],
                                'qty' => $val['qty'],
                                'discounted'=>$dscsacs,
                                'kitchen_slip_printed'=>$kitchen_slip_printed,
                                'remarks'=>$remarksArr,
                                'modifiers' => array()
                            );
                            $update_line_ids[]=$val['id'];
                        } else {
                            if($val['remarks'] != "")
                                $post_details[$val['menu_id']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                            $post_details[$val['menu_id']]['qty'] += $val['qty'];
                            $update_line_ids[]=$val['id'];
                        }
                        if (empty($val['modifiers']))
                            continue;
                        
                        $modif_check = true;
                        $modifs = $val['modifiers'];
                        $n_modifiers = $post_details[$val['menu_id']]['modifiers'];
                        foreach ($modifs as $vv) {
                            
                            $kitchen_slip_printed = $vv['kitchen_slip_printed'];
                            if($vv['kitchen_slip_printed'] == ""){
                                $kitchen_slip_printed = 0;
                            }

                            if (!isset($n_modifiers[$vv['id']])) {
                                $n_modifiers[$vv['id']] = array(
                                    'name' => $vv['name'],
                                    'price' => $vv['price'],
                                    'kitchen_slip_printed'=> $kitchen_slip_printed,
                                    'qty' => $val['qty'],
                                    'discount' => $vv['discount']
                                );
                                $update_line_mod_ids[]=$vv['sales_mod_id'];
                            } else {
                                $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                                $update_line_mod_ids[]=$vv['sales_mod_id'];
                            }
                        }
                        $post_details[$val['menu_id']]['modifiers'] = $n_modifiers;
                    
                }
                if (!empty($val['modifiers'])){
                    if(!$modif_check){
                        $modifs = $val['modifiers'];
                        foreach ($modifs as $vv) {
                            if($vv['kitchen_slip_printed'] == 0){
                                $kitchen_slip_printed = $vv['kitchen_slip_printed'];
                                if($vv['kitchen_slip_printed'] == ""){
                                    $kitchen_slip_printed = 0;
                                }
                                if(!isset($added_modifs['sales_mod_id'])){
                                    $added_modifs[$vv['id']] = array(
                                        'name' => $vv['name'],
                                        'price' => $vv['price'],
                                        'kitchen_slip_printed'=> $kitchen_slip_printed,
                                        'qty' => $val['qty'],
                                        'discount' => $vv['discount']
                                    );
                                    $update_line_mod_ids[]=$vv['sales_mod_id'];
                                }
                                else{
                                    $added_modifs[$vv['id']]['qty'] += $val['qty'];
                                    $update_line_mod_ids[]=$vv['sales_mod_id'];
                                }
                            }
                            ####
                        }
                        #####    
                    }
                    ###########
                }
            }
        }
        $print_str .=  $this->align_center('Order Slip',38," ")."\r\n";
        $tot_qty = 0;
        $needs_to_print = 0;
        foreach ($post_details as $menu_id => $val) {
                if($val['kitchen_slip_printed'] == 0){
                    $tot_qty += $val['qty'];
                    $print_str .= $this->append_chars($val['qty'],"right",4," ");
                    if ($val['qty'] == 1) {
                        $print_str .= $this->append_chars(substrwords($val['name'],21,""),"right",26," ").
                            $this->append_chars(null,"left",8," ")."\r\n";
                    } else {
                        $print_str .= $this->append_chars(substrwords($val['name'],21,"")." @ ".$val['price'],"right",26," ").
                            $this->append_chars(null,"left",8," ")."\r\n";
                    }
                    if(count($val['discounted']) > 0){
                        foreach ($val['discounted'] as $dssstxt) {
                          $print_str .= "      ";
                          $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",23," ")."\r\n";
                        }
                    }
                    if(isset($val['remarks']) && count($val['remarks']) > 0){
                        foreach ($val['remarks'] as $rmrktxt) {
                            $print_str .= "     * ";
                            $print_str .= $this->append_chars(ucwords($rmrktxt),"right",23," ")."\r\n";
                        }
                    }
                    $needs_to_print++;
                }


                if (empty($val['modifiers']))
                    continue;
                $modifs = $val['modifiers'];
                foreach ($modifs as $vv) {
                    if($vv['kitchen_slip_printed'] == 0){
                        $print_str .= "     * ".$vv['qty']." ";
                        if ($vv['qty'] == 1) {
                            $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                                .$this->append_chars(null,"left",8," ")."\r\n";
                        } else {
                            $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                                .$this->append_chars(null,"left",8," ")."\r\n";
                        }
                        $needs_to_print++;
                    }
                }
                ##########################
        }
        if(count($added_modifs) > 0){
            $print_str .= $this->append_chars('Modifiers Added.',"right",4," ")."\r\n";
            foreach ($added_modifs as $vv) {
                if($vv['kitchen_slip_printed'] == 0){
                    $print_str .= "     * ".$vv['qty']." ";
                    if ($vv['qty'] == 1) {
                        $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                            .$this->append_chars(null,"left",8," ")."\r\n";
                    } else {
                        $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                            .$this->append_chars(null,"left",8," ")."\r\n";
                    }
                    $needs_to_print++;
                }
            }
        } 

        $print_str .= "\r\n"."--------------------------------------"."\r\n";
        $print_str .= $this->append_chars(ucwords("TOTAL QTY"),"right",28," ").$this->append_chars(number_format(($tot_qty),2),"left",10," ")."\r\n";

        if($needs_to_print > 0){
            $filename = "order.txt";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $batfile = "print.bat";
            $fh1 = fopen($batfile,'w+');
            $root = dirname(BASEPATH);
            // $battxt = "NOTEPAD /P \"".realpath($root."/".$filename)."\" \r\n";
            $battxt = "NOTEPAD /PT \"".realpath($root."/".$filename)."\" \"".$kitchen_printer."\"  ";
            fwrite($fh1, $battxt);
            fclose($fh1);
            session_write_close();
            for ($i=0; $i < $kitchen_printer_no; $i++) { 
                exec($batfile);
            }
            // exec($batfile);
            session_start();
            unlink($filename);
            unlink($batfile);
        }
        ##########################
        ### UPDATE SLIP PRINTED
        ##########################
            if(count($update_line_ids) > 0){
                foreach ($update_line_ids as $sales_menu_id) {
                    $this->site_model->update_tbl('trans_sales_menus','sales_menu_id',array('kitchen_slip_printed'=>1),$sales_menu_id);      
                }   
            }
            if(count($update_line_mod_ids) > 0){
                foreach ($update_line_mod_ids as $sales_mod_id) {
                    $this->site_model->update_tbl('trans_sales_menu_modifiers','sales_mod_id',array('kitchen_slip_printed'=>1),$sales_mod_id);      
                }   
            }
    }
    public function print_kitchen_order_slip_beverage($sales_id=null,$kitchen_beverage_printer=null,$kitchen_printer_beverage_no=0){
        $return = $this->get_order(false,$sales_id);
        $branch = $this->get_branch_details(false);
        $order = $return['order'];
        $details = $return['details'];

        $discounts = $return['discounts'];
        $totalsss = $this->total_trans(false,$details,$discounts);
        $discs = $totalsss['discs'];


        $print_str = "\r\n\r\n\r\n\r\n";
        $wrap = wordwrap($branch['name'],25,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

        $wrap = wordwrap($branch['address'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

            // .$this->align_center(wordwrap($branch['address'],20,"|#|"),38," ")."\r\n"
            $print_str .= 
            $this->align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .$this->align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
            // .$this->align_center('BIR: '.$branch['bir'],42," ")."\r\n"
            .$this->align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
            // .$this->align_center('SN #'.$branch['serial'],38," ")."\r\n"
            .$this->align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n\r\n";
            // ."=========================================="."\r\n"
            // ;
        if (!empty($order['void_ref']) || $order['inactive'] == 1) {
            $print_str .= $this->align_center("***** VOIDED TRANSACTION *****",38," ")."\r\n";
            $print_str .= $order['reason']."\r\n\r\n";
        }
        $header_print_str = $print_str;
        $header_print_str .= "======================================"."\r\n";
             if (!empty($payments)){
                $header_print_str .= "Receipt # ".$order['ref']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center("Receipt # ".$order['ref']." - ".strtoupper($order['type']),42," ")."\r\n";
            }
            else{
                $header_print_str .= "Reference # ".$order['sales_id']." - ".strtoupper($order['type'])."\r\n";
                    // $this->align_center(strtoupper($order['type'])." # ".$order['sales_id'],42," ")."\r\n";
            }
            $header_print_str .= $order['table_name']."\r\n";
            if($order['waiter_username'] != "")
                $header_print_str .= "FS: ".$order['waiter_username']."\r\n";
            $header_print_str .= $order['datetime']."\r\n";

        $header_print_str .= "======================================"."\r\n";

        $print_str = $header_print_str;
        ################################
        $discs_items = array();
        foreach ($discs as $disc) {
            if(isset($disc['items']))
                $discs_items[$disc['type']] = $disc['items'];
        }

        $dscTxt = array();
        foreach ($details as $line_id => $val) {
            foreach ($discs_items as $type => $dissss) {
                if(in_array($line_id, $dissss)){
                    $qty = 1;
                    if(isset($dscTxt[$val['menu_id']][$type]['qty'])){
                        $qty = $dscTxt[$val['menu_id']][$type]['qty'] + 1;
                    }
                    $dscTxt[$val['menu_id']][$type] = array('txt' => '#'.$type,'qty' => $qty);
                }
            }
        }

        $post_details = array();
        $update_line_ids = array();
        $update_line_mod_ids = array();
        $added_modifs = array();
        foreach ($details as $line_id => $val) {
            $modif_check = false;
            $category = $this->site_model->get_tbl('menus',array('menu_id'=>$val['menu_id']),array(),null,true,'menu_sub_cat_id');
            $cat = $category[0];
            if(BEVERAGE_ID == $cat->menu_sub_cat_id){
                if($val['kitchen_slip_printed'] == 0){
                        if (!isset($post_details[$val['menu_id']])) {
                            $dscsacs = array();
                            if(isset($dscTxt[$val['menu_id']])){
                                $dscsacs = $dscTxt[$val['menu_id']];
                            }
                            $remarksArr = array();
                            if($val['remarks'] != '')
                                $remarksArr = array($val['remarks']." x ".$val['qty']);
                            
                            $kitchen_slip_printed = $val['kitchen_slip_printed'];
                            if($val['kitchen_slip_printed'] == ""){
                                $kitchen_slip_printed = 0;
                            }

                            $post_details[$val['menu_id']] = array(
                                'name' => $val['name'],
                                'code' => $val['code'],
                                'price' => $val['price'],
                                'no_tax' => $val['no_tax'],
                                'discount' => $val['discount'],
                                'qty' => $val['qty'],
                                'discounted'=>$dscsacs,
                                'kitchen_slip_printed'=>$kitchen_slip_printed,
                                'remarks'=>$remarksArr,
                                'modifiers' => array()
                            );
                            $update_line_ids[]=$val['id'];
                        } else {
                            if($val['remarks'] != "")
                                $post_details[$val['menu_id']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                            $post_details[$val['menu_id']]['qty'] += $val['qty'];
                            $update_line_ids[]=$val['id'];
                        }
                        if (empty($val['modifiers']))
                            continue;
                        
                        $modif_check = true;
                        $modifs = $val['modifiers'];
                        $n_modifiers = $post_details[$val['menu_id']]['modifiers'];
                        foreach ($modifs as $vv) {
                            
                            $kitchen_slip_printed = $vv['kitchen_slip_printed'];
                            if($vv['kitchen_slip_printed'] == ""){
                                $kitchen_slip_printed = 0;
                            }

                            if (!isset($n_modifiers[$vv['id']])) {
                                $n_modifiers[$vv['id']] = array(
                                    'name' => $vv['name'],
                                    'price' => $vv['price'],
                                    'kitchen_slip_printed'=> $kitchen_slip_printed,
                                    'qty' => $val['qty'],
                                    'discount' => $vv['discount']
                                );
                                $update_line_mod_ids[]=$vv['sales_mod_id'];
                            } else {
                                $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                                $update_line_mod_ids[]=$vv['sales_mod_id'];
                            }
                        }
                        $post_details[$val['menu_id']]['modifiers'] = $n_modifiers;
                    
                }
                if (!empty($val['modifiers'])){
                    if(!$modif_check){
                        $modifs = $val['modifiers'];
                        foreach ($modifs as $vv) {
                            if($vv['kitchen_slip_printed'] == 0){
                                $kitchen_slip_printed = $vv['kitchen_slip_printed'];
                                if($vv['kitchen_slip_printed'] == ""){
                                    $kitchen_slip_printed = 0;
                                }
                                if(!isset($added_modifs['sales_mod_id'])){
                                    $added_modifs[$vv['id']] = array(
                                        'name' => $vv['name'],
                                        'price' => $vv['price'],
                                        'kitchen_slip_printed'=> $kitchen_slip_printed,
                                        'qty' => $val['qty'],
                                        'discount' => $vv['discount']
                                    );
                                    $update_line_mod_ids[]=$vv['sales_mod_id'];
                                }
                                else{
                                    $added_modifs[$vv['id']]['qty'] += $val['qty'];
                                    $update_line_mod_ids[]=$vv['sales_mod_id'];
                                }
                            }
                            ####
                        }
                        #####    
                    }
                    ###########
                }
            }
        }

        $print_str .=  $this->align_center('Order Slip',38," ")."\r\n";
        $tot_qty = 0;
        $needs_to_print = 0;
        foreach ($post_details as $menu_id => $val) {
                if($val['kitchen_slip_printed'] == 0){
                    $tot_qty += $val['qty'];
                    $print_str .= $this->append_chars($val['qty'],"right",4," ");
                    if ($val['qty'] == 1) {
                        $print_str .= $this->append_chars(substrwords($val['name'],21,""),"right",26," ").
                            $this->append_chars(null,"left",8," ")."\r\n";
                    } else {
                        $print_str .= $this->append_chars(substrwords($val['name'],21,"")." @ ".$val['price'],"right",26," ").
                            $this->append_chars(null,"left",8," ")."\r\n";
                    }
                    if(count($val['discounted']) > 0){
                        foreach ($val['discounted'] as $dssstxt) {
                          $print_str .= "      ";
                          $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",23," ")."\r\n";
                        }
                    }
                    if(isset($val['remarks']) && count($val['remarks']) > 0){
                        foreach ($val['remarks'] as $rmrktxt) {
                            $print_str .= "     * ";
                            $print_str .= $this->append_chars(ucwords($rmrktxt),"right",23," ")."\r\n";
                        }
                    }
                    $needs_to_print++;
                }


                if (empty($val['modifiers']))
                    continue;
                $modifs = $val['modifiers'];
                foreach ($modifs as $vv) {
                    if($vv['kitchen_slip_printed'] == 0){
                        $print_str .= "     * ".$vv['qty']." ";
                        if ($vv['qty'] == 1) {
                            $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                                .$this->append_chars(null,"left",8," ")."\r\n";
                        } else {
                            $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                                .$this->append_chars(null,"left",8," ")."\r\n";
                        }
                        $needs_to_print++;
                    }
                }
                ##########################
        }
        if(count($added_modifs) > 0){
            $print_str .= $this->append_chars('Modifiers Added.',"right",4," ")."\r\n";
            foreach ($added_modifs as $vv) {
                if($vv['kitchen_slip_printed'] == 0){
                    $print_str .= "     * ".$vv['qty']." ";
                    if ($vv['qty'] == 1) {
                        $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                            .$this->append_chars(null,"left",8," ")."\r\n";
                    } else {
                        $print_str .= $this->append_chars(substrwords($vv['name'],18,""),"right",23," ")
                            .$this->append_chars(null,"left",8," ")."\r\n";
                    }
                    $needs_to_print++;
                }
            }
        } 

        $print_str .= "\r\n"."--------------------------------------"."\r\n";
        $print_str .= $this->append_chars(ucwords("TOTAL QTY"),"right",28," ").$this->append_chars(number_format(($tot_qty),2),"left",10," ")."\r\n";


        if($needs_to_print > 0){
            $filename = "order.txt";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $batfile = "print.bat";
            $fh1 = fopen($batfile,'w+');
            $root = dirname(BASEPATH);
            $battxt = "NOTEPAD /P \"".realpath($root."/".$filename)."\" \r\n";
            if($kitchen_beverage_printer != "")
                $battxt = "NOTEPAD /PT \"".realpath($root."/".$filename)."\" \"".$kitchen_beverage_printer."\"  ";
            fwrite($fh1, $battxt);
            fclose($fh1);
            session_write_close();
            for ($i=0; $i < $kitchen_printer_beverage_no; $i++) { 
                exec($batfile);
            }
            // exec($batfile);
            session_start();
            unlink($filename);
            unlink($batfile);
        }
        ##########################
        ### UPDATE SLIP PRINTED
        ##########################
            if(count($update_line_ids) > 0){
                foreach ($update_line_ids as $sales_menu_id) {
                    $this->site_model->update_tbl('trans_sales_menus','sales_menu_id',array('kitchen_slip_printed'=>1),$sales_menu_id);      
                }   
            }
            if(count($update_line_mod_ids) > 0){
                foreach ($update_line_mod_ids as $sales_mod_id) {
                    $this->site_model->update_tbl('trans_sales_menu_modifiers','sales_mod_id',array('kitchen_slip_printed'=>1),$sales_mod_id);      
                }   
            }
    }
    private function append_chars($string,$position = "right",$count = 0, $char = "")
    {
        $rep_count = $count - strlen($string);
        $append_string = "";
        for ($i=0; $i < $rep_count ; $i++) {
            $append_string .= $char;
        }
        if ($position == 'right')
            return $string.$append_string;
        else
            return $append_string.$string;
    }
    private function align_center($string,$count,$char = " ")
    {
        $rep_count = $count - strlen($string);
        for ($i=0; $i < $rep_count; $i++) {
            if ($i % 2 == 0) {
                $string = $char.$string;
            } else {
                $string = $string.$char;
            }
        }
        return $string;
    }
	public function manager_view_orders($terminal='my',$status='open',$types='all',$now=null,$show='box'){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $args = array(
            "trans_sales.trans_ref"=>null,
            "trans_sales.terminal_id"=>TERMINAL_ID,
            "trans_sales.type_id"=>SALES_TRANS,
            "trans_sales.inactive"=>0,
        );
        if($terminal != 'my')
            unset($args["trans_sales.terminal_id"]);
        if($status != 'open'){
            unset($args["trans_sales.trans_ref"]);
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        }
        if($types != 'all'){
            $args["trans_sales.type"] = $types;
        }
        $orders = $this->cashier_model->get_trans_sales(null,$args);
		// echo $this->cashier_model->db->last_query();
        $code = "";
        $ids = array();
        $time = $this->site_model->get_db_now();
        $this->make->sDivRow();
        $ord=array();
        $combine_cart = sess('trans_combine_cart');
        foreach ($orders as $res) {
            $status = "open";
            if($res->trans_ref != "")
                $status = "settled";
            $ord[$res->sales_id] = array(
                "type"=>$res->type,
                "status"=>$status,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount
            );
            if($show == "box"){
                $this->make->sDivCol(4,'left',0);
                    $this->make->sDiv(array('class'=>'order-btn','id'=>'order-btn-'.$res->sales_id,'ref'=>$res->sales_id));
                        if($res->trans_ref == null){
                            $this->make->sBox('default',array('class'=>'box-solid'));
                        }else{
                            $this->make->sBox('default',array('class'=>'box-solid bg-green'));
                        }
                            $this->make->sBoxBody();
                                $this->make->sDivRow();
                                    $this->make->sDivCol(6);
                                        $this->make->H(5,strtoupper($res->type)." #".$res->sales_id,array("style"=>'font-weight:700;'));
                                        if($res->trans_ref == null){
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                            $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                                        }else{
                                            $this->make->H(5,strtoupper($res->username),array("style"=>'color:#fff'));
                                            $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#fff'));
                                        }
                                        $this->make->H(5,tagWord(strtoupper(ago($res->datetime,$time) ) ) );
                                    $this->make->eDivCol();
                                    $this->make->sDivCol(6);
                                        $this->make->H(4,'Order Total',array('class'=>'text-center'));
                                        $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                                    $this->make->eDivCol();
                                $this->make->eDivRow();

                            $this->make->eBoxBody();
                        $this->make->eBox();
                    $this->make->eDiv();
                $this->make->eDivCol();
            }
            else if($show=='combineList'){
                $got = false;
                if(count($combine_cart) > 0){
                    foreach ($combine_cart as $key => $co) {
                        if($co['sales_id'] == $res->sales_id){
                            $got = true;
                            break;
                        }
                    }
                }
                if(!$got){
                    $this->make->sDivRow(array('class'=>'orders-list-div-btnish sel-row','id'=>'order-btnish-'.$res->sales_id, "ref"=>$res->sales_id, "type"=>$res->type));
                        $this->make->sDivCol(6);
                            $this->make->sDiv(array('style'=>'margin-left:10px;'));
                                $this->make->H(5,strtoupper($res->type)." #".$res->sales_id,array("style"=>'font-weight:700;'));
                                $this->make->H(5,strtoupper($res->username),array("style"=>'color:#888'));
                                $this->make->H(5,strtoupper($res->terminal_name),array("style"=>'color:#888'));
                            $this->make->eDiv();
                        $this->make->eDivCol();
                        $this->make->sDivCol(6);
                            $this->make->sDiv(array('style'=>'margin-left:10px;'));
								if($status != 'open')
									$this->make->H(4,'ORDER TOTAL',array('class'=>'text-center'));
								else
									$this->make->H(4,'BALANCE DUE',array('class'=>'text-center'));
                                // $this->make->H(3,num($res->total_amount),array('class'=>'text-center','style'=>'margin-top:10px;'));
                                $this->make->H(3,num($res->total_amount),array('class'=>'text-center'));
                            $this->make->eDiv();
                        $this->make->eDivCol();
                        // $this->make->sDivCol(4);
                            // $this->make->sDiv(array('class'=>'order-btn-right-container','style'=>'margin-left:10px;margin-right:10px;margin-top:15px;'));
                                // $this->make->button(fa('fa-angle-double-right fa-lg fa-fw'),array('id'=>'add-to-btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'add-btn-row btn-block counter-btn-green'));
                            // $this->make->eDiv();
                        // $this->make->eDivCol();
                    $this->make->eDivRow();
                }
            }
            $ids[] = $res->sales_id;
        }
        //}
        $this->make->eDivRow();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ord));
    }
    /**
     * get_xread_data
     * @access public
     * @return array(max_date,from,to,details)
    */
    public function print_xread()
    {
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $print_str = $this->show_xread(false);
        $filename = "xread.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print_xread.bat";
        $fh1 = fopen($batfile,"w+");
        $root = dirname(BASEPATH);

        // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        // fclose($fh1);

        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        $get_shift = $this->clock_model->get_shift_id(date2Sql($date),$user_id);

        if (empty($get_shift)) {
            echo json_encode(array('error'=>'Current shift is invalid'));
            return false;
        }

        $userdata = $this->session->userdata('user');
        $user_id = $userdata['id'];
        $id = $this->cashier_model->add_read_details(
            array(
                'read_type' => X_READ,
                'read_date' => date('Y-m-d'),
                'user_id'   => $user_id,
            )
        );

        $shift_id = $get_shift[0]->shift_id;
        $this->clock_model->update_clockout(array('xread_id'=>$id),$shift_id);

        session_write_close();
        exec($batfile);
        session_start();
        unlink($filename);
        // unlink($batfile);

        echo json_encode(array('msg'=>"X-Read successfully printed at ".date('Y-m-d H:i:s')));
    }
    public function show_xread($asJson=true)
    {
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/settings_model');
        $result = $this->cashier_model->get_latest_read_date(X_READ);

        $settings = $this->settings_model->get_store_proper_datetime();
        // $print_date = date('Y-m-d');
        $print_date = $this->site_model->get_db_now('sql');

        $userdata = $this->session->userdata('user');
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $date = date('Y-m-d');
        $get_in = $this->clock_model->get_shift_id($date,$user_id);
        $var_id = 0;
        if(count($get_in) > 0)
            $var_id = $get_in[0]->shift_id;

        $shifts = $this->cashier_model->get_user_shifts(
            // array(
            //     'DATE(shifts.check_in)'=>$print_date,
            //     'shifts.terminal_id'=>TERMINAL_ID,
            //     'users.id' => $userdata['id']
            // )
            array(
                // 'shifts.check_in >= '=> $settings['from'],
                'shifts.shift_id '=> $var_id,
                // 'shifts.terminal_id'=>TERMINAL_ID,
                // 'users.id' => $userdata['id']
            )
        );

        $main_str = "";

        // var_dump($shifts);
        // return false;
        $userdata = $this->session->userdata('user');
        foreach ($shifts as $shft_v) {
            $orders = $this->cashier_model->get_trans_sales(
                null,
                array(
                    // 'DATE(datetime)'=>$print_date,
                    'shift_id'=>$shft_v->shift_id,
                    'trans_sales.terminal_id'=>TERMINAL_ID,
                    'paid'=>1,
                    'trans_sales.user_id'=>$userdata['id'],
                    'trans_sales.type_id'=>SALES_TRANS,
                    'trans_sales.inactive'=>0),
                'asc');

            // return false;
            if (empty($orders))
                continue;

            $print_str = "\r\nSales Receipts FOR ".$shft_v->username." \r\nat Terminal ".TERMINAL_ID."\r\n"
                .$this->append_chars("","right",38,"-")."\r\n";
            $total = 0;
            foreach ($orders as $val) {
                // print_sales_receipt($sales_id=null,$asJson=true,$return_print_str=false,$add_reprinted=true,$split_amount=null,$include_footer=true)
                $print_str .= $this->print_sales_receipt($val->sales_id,false,true,false,0,false)."\r\n"
                    .$this->append_chars("","right",38,"-")."\r\n";
                $total += $val->total_amount;
            }

            $print_str = $print_str."\r\n\r\n\r\n"
                .$this->append_chars("","right",38,"-")."\r\n\r\n"
                ."Sales Receipts at Terminal ".TERMINAL_ID."\r\n"
                ."Print Date : ".date('Y-m-d H:i:s')."\r\n"
                ."Cashier    : ".$shft_v->username."\r\n"
                ."Check in   : ".$shft_v->check_in."\r\n"
                ."Check out  : ".$print_date."\r\n\r\n"
                ."Total Sales : P ".number_format($total,2)."\r\n"
                ."Cash Float  : P ".number_format($shft_v->cash_float,2)."\r\n\r\n"
                .$this->append_chars("","right",38,"-");

            $main_str .= $print_str;
        }

        if (empty($main_str)) {
            $main_str = $this->append_chars("","right",38,"-")."\r\n\r\n"
                ."SALES DATA (".$print_date."):\r\nNo transactions found for this date\r\n\r\n"
                .$this->append_chars("","right",38,"-");
        }
        // echo "<pre>$main_str</pre>";
        if ($asJson)
            echo json_encode(array('txt'=>$main_str));
        else
            return $main_str;
    }
    public function print_zread()
    {
        $result = $this->cashier_model->get_latest_read_date(Z_READ);

        // if (date('Y-m-d') == $result->maxi) {
        //     echo json_encode(array('error_msg'=>'Z-Read data for today exists. Duplicate reads prohibited.'));
        //     return false;
        // }

        $read = $this->show_zread(false);
        $print_str = $read['txt'];
        $total = $read['total'];
        $old_total = $read['old_total'];
        $date = $read['date'];
        $filename = "xread.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print_xread.bat";
        $fh1 = fopen($batfile,"w+");
        $root = dirname(BASEPATH);

        // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        // fclose($fh1);

        $userdata = $this->session->userdata('user');
        $user_id = $userdata['id'];
        $zread_id = $this->cashier_model->add_read_details(
            array(
                'read_date'   => $date,
                'read_type'   => Z_READ,
                'user_id'     => $user_id,
                'old_total'   => $old_total,
                'grand_total' => $total,
                'scope_from'  => $read['date_from'],
                'scope_to'    => $read['date_to']
            )
        );
        $log_user = $this->session->userdata('user');
        $this->logs_model->add_logs('Read',$log_user['id'],$log_user['full_name']." Processed End Of Day.",null);

        session_write_close();
        // exec($filename);
        exec($batfile);
        session_start();
        unlink($filename);
        // unlink($batfile);
        site_alert('End of Day has been processed','success');
        $error = $this->send_to_rob($zread_id);
        if($error == "")
            site_alert('Sales File successfully sent to RLC server.','success');
        else
            site_alert($error,'error');

        echo json_encode(array('msg'=>"Z-Read successfully printed at ".date('Y-m-d H:i:s')));
    }
    public function process_zread()
    {
        // $time = $this->site_model->get_db_now();
        // $prev_date = date('Y-m-d', strtotime($time .' -1 day'));
        // $result = $this->cashier_model->get_latest_read_date(Z_READ);
        // $range = createDateRangeArray($result->maxi,$prev_date);
        // foreach ($range as $rn) {
        //     if(date('Y-m-d', strtotime($rn) != date('Y-m-d', strtotime($result->maxi) ){
        //        $from = $rn." 04:00:00";
        //        $to = $rn." 23:00:00";
        //        $read = $this->show_zread(false,$from,$to);
        //     } 
        // }
        $read = $this->show_zread(false);

        $print_str = $read['txt'];
        $total = $read['total'];
        $old_total = $read['old_total'];
        $date = $read['date'];
        $filename = "xread.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print_xread.bat";
        $fh1 = fopen($batfile,"w+");
        $root = dirname(BASEPATH);

        // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        // fclose($fh1);

        $userdata = $this->session->userdata('user');
        $user_id = $userdata['id'];
        $zread_id = $this->cashier_model->add_read_details(
            array(
                'read_date'   => $date,
                'read_type'   => Z_READ,
                'user_id'     => $user_id,
                'old_total'   => $old_total,
                'grand_total' => $total,
                'scope_from'  => $read['date_from'],
                'scope_to'    => $read['date_to']
            )
        );
        session_write_close();
        // exec($filename);
        exec($batfile);
        session_start();
        unlink($filename);
        // unlink($batfile);
        site_alert('End of Day has been processed','success');
        $error = $this->send_to_rob($zread_id);
        if($error == "")
            site_alert('Sales File successfully sent to RLC server.','success');
        else
            site_alert($error,'error');

        echo json_encode(array('msg'=>"Z-Read successfully printed at ".date('Y-m-d H:i:s')));
    }
    public function send_to_rob_cash($id=null,$increment=true){
        $print_str  = "";
        $lastRead = $this->cashier_model->get_z_read($id);

        if(count($lastRead) > 0){
            foreach ($lastRead as $res) {
                $date_from = $res->scope_from;
                $date_to = $res->scope_to;
                $old_gt_amnt = $res->old_total;
                $grand_total = $res->grand_total;
                $read_date = $res->read_date;
            }           
        }
        #CREATE FILE NAME
        $file = substr(TENANT_CODE, -4).date('m',strtotime($read_date)).date('d',strtotime($read_date)).".".TERMINAL_NUMBER;
        $check = $this->cashier_model->get_rob_files($file); 
        $ctr = 1;
        $new = true;
        if(count($check) > 0){
            foreach ($check as $res) {
                if($increment)
                    $ctr = $res->print+1;
                else
                    $ctr = $res->print;
                $new = false;
            }
        }
        $filename =  substr(TENANT_CODE, -4).date('m',strtotime($read_date)).date('d',strtotime($read_date)).".".TERMINAL_NUMBER.$ctr;
        // $filename = $this->check_if_rlc_txt_exists($filename,$ctr,$read_date);

        $title_name = $filename;
        $time = $this->site_model->get_db_now();

        $args = array();
        $terminal = TERMINAL_ID;
        
        if(!empty($terminal)){
            $args['trans_sales.terminal_id'] = $terminal;
        }
        $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
        $orders = array();
        $orders['cancel'] = array(); 
        $orders['sale'] = array();
        $orders['void'] = array();
        $gross = 0;
        $gross_ids = array();
        $paid = 0;
        $paid_ctr = 0;
        $types = array();
        foreach ($trans_sales as $sale) {
            if($sale->type_id == 10){
                if($sale->trans_ref != "" && $sale->inactive == 0){
                    $orders['sale'][$sale->sales_id] = $sale;
                    $gross += $sale->total_amount;
                    $gross_ids[] = $sale->sales_id;
                    if($sale->total_paid > 0){
                        $paid += $sale->total_paid;
                        $paid_ctr ++;                   
                    }
                    $types[$sale->type][$sale->sales_id] = $sale;
                }
                else if($sale->trans_ref == "" && $sale->inactive == 1){
                    $orders['cancel'][$sale->sales_id] = $sale;
                }
            }
            else{
                $orders['void'][$sale->sales_id] = $sale;
                // $gross += $sale->total_amount;
                // $gross_ids[] = $sale->sales_id;
            }
        }

        #DISCOUNTS 
            $sales_discs = array();
            if(count($gross_ids) > 0)
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$gross_ids));

            $total_disc = 0;
            $disc_codes = array();
            foreach ($sales_discs as $discs) {
                if(!isset($disc_codes[$discs->disc_code])){
                    $disc_codes[$discs->disc_code] = array('qty'=> 1,'amount'=>$discs->amount);
                }
                else{
                    $disc_codes[$discs->disc_code]['qty'] += 1;
                    $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                }
                $total_disc += $discs->amount;
            }      
        
        #TENANT ID 
            $print_str .= "01".str_pad(TENANT_CODE, 16, "0",STR_PAD_LEFT)."\r\n";
        #POS TERMINAL NO. 
            $print_str .= "02".str_pad(TERMINAL_NUMBER, 16, "0",STR_PAD_LEFT)."\r\n";
        #GROSS SALES
            //REGULAR
            $total_regular_discs = 0;
            $reg_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code != "SNDISC" && $code != "PWDISC"){
                    $total_regular_discs += $dc['amount'];
                    $reg_disc_ctr += $dc['qty'];                                    
                }
            }
            $total_regular_discs = numInt($total_regular_discs);
            //SENIOR
            $total_senior_discs = 0;
            $seni_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code == "SNDISC"){
                    $total_senior_discs += $dc['amount'];
                    $seni_disc_ctr += $dc['qty'];                                    
                }
            }
            $total_senior_discs = numInt($total_senior_discs);
            //PWD
            $total_pwd_discs = 0;
            $pwd_disc_ctr = 0;
            foreach ($disc_codes as $code => $dc) {
                if($code == "PWDISC"){
                    $total_pwd_discs += $dc['amount'];
                    $pwd_disc_ctr += $dc['qty'];                                    
                }
            }
            $total_pwd_discs = numInt($total_pwd_discs);
            //LOCAL TAX
            $localTax=0;
            $gross += ($total_regular_discs+$total_senior_discs+$total_pwd_discs+$localTax);
            $gross = numInt($gross);
            $print_str .= "03".str_pad($gross, 16, "0",STR_PAD_LEFT)."\r\n";
            
            // $vat = ($gross/1.12) * .12;
            // $vat = numInt($vat);
            // $gross $total_senior_discs
            
            //NON VAT
            $no_tax = array();
            if(count($gross_ids) > 0)
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
            $ntctr = 0;
            $nttotal = 0;
            foreach ($no_tax as $nt) {
                $nttotal += $nt->amount;
                $ntctr++;
            }
            $vat = 0;
            if(count($gross_ids) > 0)
                $vat = ((($gross -$total_senior_discs-$total_pwd_discs-$nttotal)/1.12)*0.12);

            // $vat = $gross - $nttotal;
            // $vat = ($vat/1.12) * .12;
            $print_str .= "04".str_pad(numInt($vat), 16, "0",STR_PAD_LEFT)."\r\n";
        #VOID SALES
            $total_cancel = 0;
            $cn_ctr = 0;
            foreach ($orders['cancel'] as $cn) {
                $total_cancel += $cn->total_amount;
                $cn_ctr++;
            }
            $total_cancel = numInt($total_cancel);
            $print_str .= "05".str_pad($total_cancel, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "06".str_pad($cn_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
        #REGULAR DISCOUNTS
            
            $print_str .= "07".str_pad($total_regular_discs, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "08".str_pad($reg_disc_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
        #TOTAL VOIDED
            $total_void = 0;
            $vd_ctr = 0;
            foreach ($orders['void'] as $vd) {
                $total_void += $vd->total_amount;
                $vd_ctr++;
            }
            $total_void = numInt($total_void);
            $print_str .= "09".str_pad($total_void, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "10".str_pad($vd_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
        #SENIOR DISCOUNTS
            
            $print_str .= "11".str_pad($total_senior_discs, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "12".str_pad($seni_disc_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
        #SERVICE CHARGE
            $total_service_charge = 0;
            if(count($gross_ids) > 0){
                $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$gross_ids));
                $total_charge = 0;
                $charges_codes = array();
                foreach ($sales_charges as $chg) {
                    if(!isset($charges_codes[$chg->charge_code])){
                        $charges_codes[$chg->charge_code] = array('qty'=> 1,'amount'=>$chg->amount);
                    }
                    else{
                        $charges_codes[$chg->charge_code]['qty'] += 1;
                        $charges_codes[$chg->charge_code]['amount'] += $chg->amount;
                    }
                    $total_charge += $chg->amount;
                }
                $serv_chg_ctr = 0;
                foreach ($charges_codes as $code => $sv) {
                    if($code == "Service Charge"){
                        $total_service_charge += $sv['amount'];
                        $serv_chg_ctr += $sv['qty'];                                    
                    }
                }
                $total_service_charge = numInt($total_service_charge);
            }
            $print_str .= "13".str_pad($total_service_charge, 16, "0",STR_PAD_LEFT)."\r\n";
        #PREVIOUS EOD    
            $lastRead = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($read_date));
            $lastOLDGT=0;
            $lastNEWGT=0;
            $lastGT_ctr=0;
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    // $lastOLDGT = $res->old_total;
                    $lastNEWGT = $res->grand_total;
                    $lastGT_ctr++;
                }           
            }
            $print_str .= "14".str_pad($lastGT_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "15".str_pad(numInt($lastNEWGT), 16, "0",STR_PAD_LEFT)."\r\n";
        #CURRENT EOD    
            $print_str .= "16".str_pad($lastGT_ctr+1, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "17".str_pad(numInt($grand_total), 16, "0",STR_PAD_LEFT)."\r\n";
        #DATE    
            $print_str .= "18".str_pad(sql2Date($read_date), 16, "0",STR_PAD_LEFT)."\r\n";
        #NOVELTY SALES
            $print_str .= "19".str_pad(numInt(0), 16, "0",STR_PAD_LEFT)."\r\n";
        #MISCELLANEOUS SALES
            $print_str .= "20".str_pad(numInt(0), 16, "0",STR_PAD_LEFT)."\r\n";
        #LOCAL TAX
            $print_str .= "21".str_pad(numInt($localTax), 16, "0",STR_PAD_LEFT)."\r\n";    
        #CREDIT SALES
            $payments = $this->cashier_model->get_trans_sales_payments_group(null,array("trans_sales_payments.sales_id"=>$gross_ids));
            $pays = array();
            foreach ($payments as $py) {
                if(!isset($pays[$py->payment_type])){
                    $pays[$py->payment_type] = array('qty'=>$py->count,'amount'=>$py->total_paid);
                }
                else{
                    $pays[$py->payment_type]['qty'] += $py->count;
                    $pays[$py->payment_type]['amount'] += $py->total_paid;
                }
            }
            $credit_total = 0;
            $credit_qty = 0;
            foreach ($pays as $type => $pay) {
                if($type == "credit"){
                    $credit_total += $pay['amount'];
                    $credit_qty += $pay['qty'];                
                }
            }
            $print_str .= "22".str_pad(numInt($credit_total), 16, "0",STR_PAD_LEFT)."\r\n";  
        #VAT ON CREDIT SALES
            $print_str .= "23".str_pad(numInt( ($credit_total/1.12) * 0.12), 16, "0",STR_PAD_LEFT)."\r\n";  
        #NON-VAT SALES
            
            $print_str .= "24".str_pad(numInt($nttotal), 16, "0",STR_PAD_LEFT)."\r\n";
        #PHARMA SALES
            $print_str .= "25".str_pad(numInt(0), 16, "0",STR_PAD_LEFT)."\r\n";
        #NON PHARMA TAX
            $print_str .= "26".str_pad(numInt(0), 16, "0",STR_PAD_LEFT)."\r\n"; 
        #PWD DISCOUNTS
            
            
            $print_str .= "27".str_pad($total_pwd_discs, 16, "0",STR_PAD_LEFT)."\r\n";
        #KIOSK SOMETHING
            $menu_cat_sales = $this->cashier_model->get_trans_sales_categories(GC,array("trans_sales_menus.sales_id"=>$gross_ids));
            $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$gross_ids));
            $cats = array();
            foreach ($menu_cat_sales as $cat){
                $cost = $cat->price;
                foreach ($menu_cat_sale_mods as $cod) {
                    if($cat->sales_id == $cod->sales_id && $cod->line_id == $cat->line_id){
                        $cost += $cod->price;
                    }
                }
                $cost = $cost * $cat->qty;
                foreach ($sales_discs as $cis){
                    if($cis->sales_id == $cat->sales_id){
                        $rate = $cis->disc_rate;
                        switch ($cis->type) {
                            // case "item":
                            //             $items = explode(',',$cis->items);
                            //             foreach ($items as $lid) {
                            //                 if($cat->line_id == $lid){
                            //                     $discount = ($rate / 100) * $cost;
                            //                     $cost -= $discount;
                            //                 }
                            //             }
                            //             break;
                            // case "equal":
                            //             $divi = $cost/$cis->guest;
                            //             $discount = ($rate / 100) * $divi;
                            //             $cost -= $discount;
                            //             break;
                            // default:

                            //         $discount = ($rate / 100) * $cost;
                            //         $cost -= $discount;
                            //         break;

                            case "equal":
                                     $divi = $cost/$cis->guest;
                                     if($cis->no_tax == 1)
                                         $divi = ($divi / 1.12);
                                     $discount = ($rate / 100) * $divi;
                                     $cost = ($divi * $cis->guest) - $discount;
                                     break;
                            default:
                                 if($cis->no_tax == 1)
                                     $cost = ($cost / 1.12);                     
                                 $discount = ($rate / 100) * $cost;
                                 $cost -= $discount;       
                        }                           
                    }
                }
                if(!isset($cats[$cat->menu_cat_id])){
                    $cats[$cat->menu_cat_id] = array(
                        "cat_name"=>$cat->menu_cat_name,
                        "amount"=>$cost,
                        "qty"=>$cat->qty
                    );                      
                }
                else{
                    $cats[$cat->menu_cat_id]['amount'] += $cost;
                    $cats[$cat->menu_cat_id]['qty'] += $cat->qty;
                }
            }
            $kiosk_total = 0;
            foreach ($cats as $cat_id => $opt) {
                $kiosk_total += $opt['amount'];
            }
            $print_str .= "28".str_pad(numInt($kiosk_total), 16, "0",STR_PAD_LEFT)."\r\n";
        #REPRINTED TOTAL
            $total_reprinted = 0;
            $re_ctr = 0;
            foreach ($orders['sale'] as $vd) {
                if($vd->printed > 1){
                    $total_reprinted += $vd->total_amount;
                    $re_ctr++;                    
                }
            }
            $total_reprinted = numInt($total_reprinted);
            $print_str .= "29".str_pad($total_reprinted, 16, "0",STR_PAD_LEFT)."\r\n";
            $print_str .= "30".str_pad($re_ctr, 16, "0",STR_PAD_LEFT)."\r\n";
        // echo "<pre>".$print_str."</pre>";
        $rlc = $this->cashier_model->get_rob_path();
        $path =  $rlc->rob_path;
        $not_sent = 0;
        $error = "";
        if($path != ""){
            // $localFile = 'rob/'.$filename.".txt";
            // $ftpFile = $filename.".txt";
            // $filename = 'rob/'.$filename.".txt";

            $localFile = 'rob/'.$filename;
            $ftpFile = $filename;
            $filename = 'rob/'.$filename;
        
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);       
            $ftp_server = $path;
            
            $can = (pingAddress($ftp_server));
            if($can){
                $ftp_conn = ftp_connect($ftp_server) ;
                $login = ftp_login($ftp_conn, $rlc->rob_username, $rlc->rob_password);
                    if (ftp_put($ftp_conn, $ftpFile, $localFile, FTP_ASCII)) {
                        $error = "";
                        $not_sent = 0;
                    } else {
                        $error = "Sales File is not Sent To RLC server. Please Contact your POS vendor";
                        $sent = 1;
                    }
                ftp_close($ftp_conn); 
            }
            else{
                $error = "No connection to RLC Server";
                $not_sent = 1;
            }
        }
        else{
            $error = "No connection to RLC Server";
            $not_sent = 1;
        }
        
        if($new){
           $item = array(
            "code"=>$file,
            "file"=>$filename,
            "print"=>1,
            "inactive"=>(int)$not_sent
           );
           $id = $this->cashier_model->add_rob_files($item);
        }
        else{
           $id = $this->cashier_model->update_rob_files(array('print'=>$ctr,'file'=>$filename,"inactive"=>(int)$not_sent),$file);
        }
        return $error;
    }
    public function show_zread($asJson=true,$from=null,$to=null)
    {
        $this->load->model('dine/cashier_model');
        
        $zread_data = $this->get_zread_data($from,$to);

        $max_date = $zread_data['max_date'];
        $from = $zread_data['from'];
        $to = $zread_data['to'];
        $orders = $zread_data['details'];
        $datetime = $this->site_model->get_db_now('sql');
        $read_date = date('Y-m-d',strtotime($datetime));
        if($zread_data['from'] != null)
            $read_date = date2Sql($zread_data['from']);

        $print_str = "";
        $total = 0;
        foreach ($orders as $val) {
            $print_str .= $this->print_sales_receipt($val->sales_id,false,true,false,0,false)."\r\n\r\n"
                .$this->append_chars("","right",38,"-")."\r\n\r\n";
            $total += $val->total_amount;
        }

        if($total > 0){
           $print_str .= "Total Sales : P ".number_format($total,2)."\r\n";
        }
        
        $prev_sales = 0;
        if (!empty($max_date)) {
            // $resultx = $this->cashier_model->get_read_details(Z_READ,$max_date);
            $resultx = $this->cashier_model->get_last_new_gt(Z_READ,$max_date,$read_date);
            if (!empty($resultx[0]))
                $prev_sales = $resultx[0]->grand_total;
        }
        if($prev_sales == "")
            $prev_sales = 0;

        $new_grand_total = $total+$prev_sales;
        // if($total == 0){
        //     $new_grand_total = 0;
        // }
        
        $print_str = $print_str.($print_str == "" ? "" : "\r\n")
            .$this->append_chars("","right",38,"-")."\r\n\r\n"
            ."Z-READ DATA (".$read_date.")\r\n"
            ."Old GT : P ".number_format($prev_sales,2)."\r\n"
            ."New GT : P ".number_format($new_grand_total,2)."\r\n"
            ."\r\n".$this->append_chars("","right",38,"-");

        if ($asJson)
            echo json_encode(array('txt'=>$print_str,'old_total'=>$prev_sales,'total'=>$new_grand_total,'date'=>$read_date,'max_date'=>$max_date,'date_from'=>$from,'date_to'=>$to));
        else
            return array('txt'=>$print_str,'old_total'=>$prev_sales,'total'=>$new_grand_total,'date'=>$read_date,'max_date'=>$max_date,'date_from'=>$from,'date_to'=>$to);
    }
    /**
     * get_zread_data
     * @access public
     * @return array(max_date,from,to,details)
    */
    public function get_zread_data($from=null,$to=null)
    {
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/settings_model');
        $this->load->model('site/site_model');
        // $selector_from = $selector_to = $max_date = "";
        // if($from != null ){
            $result = $this->cashier_model->get_latest_read_date(Z_READ);
            $orders = null;
            $date_to = null;
            $date_from = null;
            if (!empty($result->maxi)) {
                $datetime = $this->site_model->get_db_now('sql');
                $date_from = $result->maxi;
                $date_to = $datetime;
                $orders = $this->cashier_model->get_trans_sales(
                    null,
                    array(
                        'trans_sales.datetime >=' => $date_from,
                        'trans_sales.datetime <=' => $date_to,
                        'trans_sales.inactive' => 0,
                        'trans_sales.type_id' => SALES_TRANS,
                        "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
                    ),
                    'asc'
                );
                foreach ($orders as $res) {
                    $date_from = $res->datetime;
                    break;
                }
                $max_date = $result->maxi;
            }else{
                $datetime = $this->site_model->get_db_now('sql');
                $date_to = $datetime;
                $orders = $this->cashier_model->get_trans_sales(
                        null,
                        array(
                            'trans_sales.datetime <=' => $date_to,
                            'trans_sales.inactive' => 0,
                            'trans_sales.type_id' => SALES_TRANS,
                            "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
                        ),
                        'asc'
                    );
                foreach ($orders as $res) {
                    $date_from = $res->datetime;
                    break;
                }
                $max_date = $date_to;
            }
        // }
        // else{
        //     $datetime = $this->site_model->get_db_now('sql');
        //     $date_from = $from;
        //     $date_to = $to;
        //     $orders = $this->cashier_model->get_trans_sales(
        //             null,
        //             array(
        //                 'trans_sales.datetime >=' => $date_from,
        //                 'trans_sales.datetime <=' => $date_to,
        //                 'trans_sales.inactive' => 0,
        //                 'trans_sales.type_id' => SALES_TRANS,
        //                 "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
        //             ),
        //             'asc'
        //         );
        //     $max_date = $date_to;
        // }
        return array('max_date'=>$max_date,'from'=>$date_from,'to'=>$date_to,'details'=>$orders);
    }
    // public function get_zread_data()
    // {
    //     $this->load->model('dine/cashier_model');
    //     $this->load->model('dine/settings_model');
    //     $this->load->model('site/site_model');
    //     // $selector_from = $selector_to = $max_date = "";
    //     $result = $this->cashier_model->get_latest_read_date(Z_READ);
    //     $orders = null;
    //     $date_to = null;
    //     $date_from = null;
    //     if (!empty($result->maxi)) {
    //         $datetime = $this->site_model->get_db_now('sql');
    //         $date_from = $result->maxi;
    //         $date_to = $datetime;
    //         $orders = $this->cashier_model->get_trans_sales(
    //             null,
    //             array(
    //                 'trans_sales.datetime >=' => $date_from,
    //                 'trans_sales.datetime <=' => $date_to,
    //                 'trans_sales.inactive' => 0,
    //                 'trans_sales.type_id' => SALES_TRANS,
    //                 "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
    //             ),
    //             'asc'
    //         );
    //         foreach ($orders as $res) {
    //             $date_from = $res->datetime;
    //             break;
    //         }
    //         $max_date = $result->maxi;
    //     }else{
    //         $datetime = $this->site_model->get_db_now('sql');
    //         $date_to = $datetime;
    //         $orders = $this->cashier_model->get_trans_sales(
    //                 null,
    //                 array(
    //                     'trans_sales.datetime <=' => $date_to,
    //                     'trans_sales.inactive' => 0,
    //                     'trans_sales.type_id' => SALES_TRANS,
    //                     "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
    //                 ),
    //                 'asc'
    //             );
    //         foreach ($orders as $res) {
    //             $date_from = $res->datetime;
    //             break;
    //         }
    //         $max_date = $date_to;
    //     }
    //     return array('max_date'=>$max_date,'from'=>$date_from,'to'=>$date_to,'details'=>$orders);
    // }
	public function manager_print_all_receipts($terminal='my',$status='open',$types='all',$now=null,$show='box', $asJson=true){
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $args = array(
            "trans_sales.trans_ref"=>null,
            "trans_sales.terminal_id"=>TERMINAL_ID,
            "trans_sales.type_id"=>SALES_TRANS,
            "trans_sales.inactive"=>0,
        );
        if($terminal != 'my')
            unset($args["trans_sales.terminal_id"]);
        if($status != 'open'){
            unset($args["trans_sales.trans_ref"]);
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        }
        if($types != 'all'){
            $args["trans_sales.type"] = $types;
        }
        $orders = $this->cashier_model->get_trans_sales(null,$args);
        $code = "";
        $ids = array();
        $time = $this->site_model->get_db_now();
        $ord=array();

		$print_str = "";

        foreach ($orders as $res) {
            $status = "open";
            if($res->trans_ref != "")
                $status = "settled";
            $ord[$res->sales_id] = array(
                "type"=>$res->type,
                "status"=>$status,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount
            );

			$print_str .= "SALES ID ".$res->sales_id."\r\n"
                .$this->print_sales_receipt($res->sales_id,false,true)."\r\n"
                .$this->append_chars("","right",46,"-");

            $ids[] = $res->sales_id;
        }

		$filename = "all_sales.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print.bat";
        $fh1 = fopen($batfile,'w+');
        $root = dirname(BASEPATH);

        fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        fclose($fh1);
        session_write_close();
        exec($batfile);
        session_start();
        unlink($filename);
        unlink($batfile);

		 if ($asJson)
				echo json_encode(array('txt'=>'<pre>'.$print_str.'</pre>', 'msg'=>'Successfully printed all receipts'));
			else
				return $print_str;
    }
    public function check_zread_okay($asJson=true)
    {
        $error = "";
        $zread_data = $this->get_zread_data();
        // if(count($zread_data['details']) <= 0)
        //     $error = "No Sales";
        
        $data_from = date('Y-m-d',strtotime($zread_data['from']));

        $unsettled_trans = $this->check_unsettled_sales(false,$data_from);
        $unclosed_xread = $this->check_unclosed_xread(false,$data_from);
        if (!empty($unclosed_xread) || !empty($unsettled_trans))
            $error = 'You need to settle all transactions to proceed in zreading';
        if($asJson)
            echo json_encode(array('error'=>$error));
        else
            echo json_encode(array('error'=>$error));
    }
    public function check_unclosed_xread($asJson=true,$date=null)
    {
        $this->load->model('dine/clock_model');
        // $shift = $this->clock_model->get_shifts('check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\'');
        $shift = $this->clock_model->get_shifts('DATE(check_in) = \''.(is_null($date) ? date('Y-m-d') : $date).'\' AND (check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\')');

        $return_array = array();

        if (!empty($shift))
            $return_array = array('error'=>'<h5>Some shifts have missing drawer count or Sales data.<br/>Unable to process Z-Read.</h5>');

        if ($asJson) {
            echo json_encode($return_array);
            return false;
        } else
            return $return_array;
    }
    public function check_unsettled_sales($asJson=true,$date=null)
    {
        $this->load->model('dine/cashier_model');
        $unsettled_sales = $this->cashier_model->get_trans_sales(null,
            array(
                'trans_sales.inactive' => 0,
                'trans_sales.total_amount <' => 'trans_sales.total_paid',
                'trans_sales.type_id' => SALES_TRANS,
                'date(datetime)' => (is_null($date) ? date('Y-m-d') : $date)
            )
        );

        $return_array = array();

        if (!empty($return_array))
            $return_array = array('error'=>'<h5>There are unsettled transactions for '.$date.'. Unable to proceed.</h5>');

        if ($asJson) {
            echo json_encode($return_array);
            return false;
        } else
            return $return_array;
    }
}