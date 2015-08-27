<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/cashier.php");

class Endofday extends Cashier {
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/cashier_model');
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->helper('core/string_helper');
        $this->load->helper('dine/endofday_helper');
    }
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = endOfDayPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'endofdayJs';
        $this->load->view('load',$data);
    }
    public function summary(){
        $data = $this->syter->spawn(null);
        $data['code'] = summaryPage();
        $data['add_css'] = array('css/morris/morris.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js');
        $data['load_js'] = 'dine/endofday.php';
        $data['use_js'] = 'summaryJs';
        $this->load->view('load',$data);
    }
    public function summary_orders(){
        $today = $this->site_model->get_db_now(null,true);
        $args = array();
        $args["DATE(trans_sales.datetime)"] = $today;
        $orders = array();
        $ords = $this->cashier_model->get_trans_sales(null,$args);
        $types = unserialize(SALE_TYPES);
        $set = $this->cashier_model->get_pos_settings();
        if(count($set) > 0){
            $types = array();
            $ids = explode(',',$set->controls);
            foreach($ids as $value){
                $text = explode('=>',$value);
                if($text[0] == 1){
                    $types[]='dinein';
                }elseif($text[0] == 7){
                    $types[]='drivethru';
                }else{
                    $types[]=$text[1];
                }
            }
        }

        $status = array('Open'=>'blue','Settled'=>'green','Cancel'=>'yellow','Void'=>'red');
        foreach ($types as $typ) {
            $open = 0;
            $settled = 0;
            $cancel = 0;
            $void = 0;
            foreach ($ords as $res) {
                if(strtolower($res->type) == strtolower($typ)){
                    if($res->type_id == 10){
                        if($res->trans_ref != "" && $res->inactive == 0){
                            $settled += $res->total_amount;
                        }
                        elseif($res->trans_ref == ""){
                            if($res->inactive == 0){
                                $open += $res->total_amount;
                            }
                            else{
                                $cancel += $res->total_amount;
                            }
                        }
                    }
                    else{
                        $void += $res->total_amount;
                    }
                }
            }
            $orders[$typ] = array('label'=>$typ,'open'=>$open,'settled'=>$settled,'cancel'=>$cancel,'void'=>$void);
        }
        $total_trans = 0;
        $stat = array();
        $total_sales = 0;
        foreach ($orders as $type => $opt) {
            foreach ($opt as $txt => $val) {
                if($txt != 'label'){
                    if(isset($stat[strtolower($txt)]))
                        $stat[strtolower($txt)] += $val;
                    else
                        $stat[strtolower($txt)] = $val;
                    $total_trans += $val;

                    if($txt == 'open' || $txt == 'settled')
                        $total_sales += $val;
                }
            }
        }
        foreach ($status as $txt => $color) {
            $this->make->sDiv(array('class'=>'clearfix'));
                $this->make->span($txt,array('class'=>'pull-left'));
                $this->make->span(small($stat[strtolower($txt)]."/".$total_trans),array('class'=>'pull-right'));
            $this->make->eDiv();
            $this->make->sDiv(array('style'=>'margin-bottom:10px;'));
                $this->make->progressBar($total_trans,$stat[strtolower($txt)],null,0,$color,array());
            $this->make->eDiv();
        }
        $this->make->sDiv(array('class'=>'clearfix'));
            $this->make->H(3,'Total',array('class'=>'pull-left'));
            $this->make->H(3,num($total_sales),array('class'=>'pull-right'));
           
        $this->make->eDiv();
        $code = $this->make->code();

        echo json_encode(array("orders"=>$orders,'types'=>$types,'code'=>$code));
    }
    ///////////////////////////////////////
    ////// END SHIFT
    ///////////////////////////////////////
        public function end_shift(){
            $data = $this->syter->spawn(null);
            // $receipts = "";
            // $receipts = $this->show_xread(false);
            $user = $this->session->userdata('user');
            $user_id = $user['id'];
            $now = $this->site_model->get_db_now('sql');
            $read_details = array();
            $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
            if(!empty($shift)){
                $in = $shift->check_in;
                $out = $now;
                $read_details = array(
                    'read_type'  => X_READ,
                    'read_date'  => date2Sql($in),
                    'cashier'    => $user_id,
                    'date_from'  => $in,
                    'date_to'    => $out,
                    'shift_id'    => $shift->shift_id,
                    // 'calendar_range'=> $in." to ".$out,
                    "title"=>"XREAD"
                );
                if($shift->cashout_id != ""){
                    $read_details['cashout_id'] = $shift->cashout_id;
                }
            }
            $data['code'] = endShift($read_details);
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/endofday.php';
            $data['use_js'] = 'endShiftJs';
            $this->load->view('load',$data);
        }
        public function read_shift_sales(){
            $user = $this->session->userdata('user');
            $user_id = $user['id'];
            // $user_id = 22;
            start_load(0);
            $now = $this->site_model->get_db_now('sql');
            $shift = $this->clock_model->get_curr_shift(date2Sql($now),$user_id);
            $error = "";
            if(empty($shift)){
                echo json_encode(array('error'=>'There is no shift.'));
                return false;
            }
            update_load(40);
            sleep(1);
            if($shift->cashout_id != ""){
                $in = $shift->check_in;
                $out = $now;
                $read_details = array(
                    'read_type' => X_READ,
                    'read_date' => date2Sql($in),
                    'user_id'   => $user_id,
                    'scope_from'=> $in,
                    'scope_to'  => $out
                );
                $id = $this->cashier_model->add_read_details($read_details);
                update_load(60);
                $log_user = $this->session->userdata('user');
                $this->logs_model->add_logs('Read',$log_user['id'],$log_user['full_name']." Read Shift.",null);
                $this->clock_model->update_clockout(array('xread_id'=>$id,'check_out'=>$out),$shift->shift_id);
                $this->logs_model->add_logs('Shift',$log_user['id'],$log_user['full_name']." Ended Shift.",$shift->shift_id);
                site_alert('Last Shift has successfully ended.','success');
                update_load(70);
                sleep(1);
                
                $this->load->library('../controllers/dine/main');
                update_load(85);
                sleep(1);
                $this->main->shifts_to_main($shift->shift_id);

                session_start();
                $check = sql2Date($shift->check_in);
                $time = $this->site_model->get_db_now();
                $today = sql2Date($time);
                if(strtotime($check) < strtotime($today)){
                    unset($_SESSION['load']);
                    unset($_SESSION['problem']);
                }    
                update_load(100);
            }
            else{
                $error = "You need to count the cash drawer first before ending the shift.";
                update_load(100);
            }
            echo json_encode(array('error'=>$error));
        }
    ///////////////////////////////////////
    ////// END DAY
    ///////////////////////////////////////
        public function end_day(){
            $data = $this->syter->spawn(null);
            $details = array();
            $datetime = $this->site_model->get_db_now('sql');
            $date_to = $datetime;
            $date_from = null;
            $error = null;
            $result = $this->cashier_model->get_latest_read_date(Z_READ);
            $got_z_read = true;
            if(!empty($result)){
                $date_from = $result->maxi;
                $max_date = $result->maxi;
                if($result->maxi == null)
                    $got_z_read = false;
            }
            else{
                $max_date = $date_to;
                $got_z_read = false;
            }
            
            // if($date_from != null){
            //     $check = $this->cashier_model->check_latest_read_date(Z_READ,date2Sql($date_from));

            //     if(!empty($check) && $check->today_zread != null){
            //         $date_from = $check->today_zread;
            //         // $max_date = $check->today_zread;
            //     }
            // }


            // if($date_from == null){
            //     $shifts_today = $this->cashier_model->get_x_read_details(date2Sql($datetime));
            //     foreach ($shifts_today as $res) {
            //         $date_from = $res->scope_from;
            //         break;
            //     }
            // }

            //FOR OVERNIGHT
            if($date_from != null){
                $shifts_today = $this->cashier_model->get_next_x_read_details($date_from);
                foreach ($shifts_today as $res) {
                    $date_from = $res->scope_from;
                    break;
                }
            }
            else{
                if($got_z_read){
                    $shifts_today = $this->cashier_model->get_next_x_read_details(date2Sql($datetime));
                    foreach ($shifts_today as $res) {
                        $date_from = $res->scope_from;
                        break;
                    }
                }
                else{
                    $first_shift = $this->site_model->get_tbl('read_details',array('read_type'=>1),array('scope_from'=>'asc'),null,true,'*',null,1); 
                    if(count($first_shift) > 0){
                        $date_from = $first_shift[0]->scope_from;
                    }
                }
            }
            if($date_from == null){
                $date_from = $datetime;
            }


            $date_in = date2Sql($date_from);
            $argss = array(
                    'trans_sales.inactive' => 0,
                    'trans_sales.type_id' => SALES_TRANS,
                    "trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'" => array('use'=>'where','val'=>null,'third'=>false)
                );
            $argss['FORMAT(trans_sales.total_paid,2) < FORMAT(trans_sales.total_amount,2)'] = array('use'=>'where','val'=>null,'third'=>false);
            $unsettled_sales = $this->cashier_model->get_trans_sales(null,$argss);

            $first_unclosed_shift = null;
            $unclosed_xread = $this->clock_model->get_shifts('DATE(check_in) = \''.(is_null($date_in) ? date('Y-m-d') : $date_in).'\' AND (check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\')');
            if (!empty($unclosed_xread)){
                $error = 'There are still shifts open. You need to close it all before proceeding to end of day';
                if($date_from == $datetime){
                    $first_unclosed_shift = $unclosed_xread[0]->check_in;
                }
            }
            if (!empty($unsettled_sales)){
                $error = 'There are still unsettled transactions. You need to settle it all before proceeding to end of day';
            }
            
            if($first_unclosed_shift != null)
                $date_from = $first_unclosed_shift;

            $read_date = date2Sql($date_from);
            $details = array(
                "date_to"=>$date_to,
                "max_date"=>$max_date,
                "date_from"=>$date_from,
                "calendar"=>$date_from,
                "read_date"=>$read_date,
                "use_curr"=>1,
                "title"=>"ZREAD"
            );
            $data['code'] = endDay($details,$error);
            $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/endofday.php';
            $data['use_js'] = 'endDayJs';
            $this->load->view('load',$data);
        }
        public function read_end_day_sales(){
            $details = array();
            start_load(0);
            $datetime = $this->site_model->get_db_now('sql');
            $end = $datetime;
            $start = null;
            $result = $this->cashier_model->get_latest_read_date(Z_READ);
            $got_z_read = true;
            if(!empty($result)){
                $start = $result->maxi;
                $max_date = $result->maxi;
                if($result->maxi == null)
                    $got_z_read = false;
            }
            else{
                $max_date = $end;
                $got_z_read = false;
            }
            update_load(30);
            sleep(1);
            // $check = $this->cashier_model->check_latest_read_date(Z_READ,date2Sql($datetime));
            // $increment = true;
            // if(!empty($check) && $check->today_zread != null){
            //     $start = $check->today_zread;
            //     $max_date = $check->today_zread;
            //     $increment = false;
            // }
            ########### PANSAMANTALA
            // $increment = true;
            // if($start != null){
            //     $check = $this->cashier_model->check_latest_read_date(Z_READ,date2Sql($start));
            //     if(!empty($check) && $check->today_zread != null){
            //         $start = $check->today_zread;
            //         $increment = false;
            //         // $max_date = $check->today_zread;
            //     }
            // }
            update_load(40);
            
            //FOR OVERNIGHT
            if($start != null){
                $shifts_today = $this->cashier_model->get_next_x_read_details($start);
                foreach ($shifts_today as $res) {
                    $start = $res->scope_from;
                    break;
                }
            }
            else{
                if($got_z_read){
                    $shifts_today = $this->cashier_model->get_next_x_read_details(date2Sql($datetime));
                    foreach ($shifts_today as $res) {
                        $start = $res->scope_from;
                        break;
                    }
                }
                else{
                    $first_shift = $this->site_model->get_tbl('read_details',array('read_type'=>1),array('scope_from'=>'asc'),null,true,'*',null,1); 
                    if(count($first_shift) > 0){
                        $start = $first_shift[0]->scope_from;
                    }
                }
            }
            // if($start == null){
            //     $shifts_today = $this->cashier_model->get_x_read_details(date2Sql($datetime));
            //     foreach ($shifts_today as $res) {
            //         $start = $res->scope_from;
            //         break;
            //     }
            // }
            $read_date = date2Sql($start);
            $z_read_id = $this->go_zread(false,$start,$end,$read_date);
            if($z_read_id){
               site_alert("Z Read for ".$read_date." successfully processed.",'success');
            }
            update_load(70);
            sleep(1);
            if(MALL_ENABLED){
                if(MALL == "robinsons"){
                    $rob = $this->send_to_rob($z_read_id,$increment);
                    if($rob['error'] == ""){
                        site_alert("File:".$rob['file']." Sales File successfully sent to RLC server.",'success');
                    }
                    else{
                        site_alert($rob['error'],'error');
                    }
                }
                else if(MALL == "ortigas"){
                    $this->ortigas_file($z_read_id);
                }
                else if(MALL == "araneta"){
                    $this->araneta_file($z_read_id);
                    $last_date = date("Y-m-t", strtotime($read_date));
                    $now_date = date("Y-m-d", strtotime($read_date));
                    if($last_date == $now_date){
                        $this->araneta_month_file($now_date);
                    }
                }
                else if (MALL == 'megamall') {
                    $this->sm_file($read_date,$z_read_id);
                }

            }
            update_load(100);
            // echo var_dump($rob);
        }    
}