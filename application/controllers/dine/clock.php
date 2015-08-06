<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clock extends CI_Controller {
    #clock
    public function index(){
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        $this->load->helper('dine/clock_helper');
        $this->load->helper('core/on_screen_key_helper');
        $data = $this->syter->spawn(null);
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $date = date('Y-m-d');
        $get_in = $this->clock_model->get_shift_id($date,$user_id);
        $in = 'first_in';
        $countin = count($get_in);
        if($countin > 0){
            $in = 'in';
        }
        $data['code'] = timeClockPage($countin,$in);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/clock.php';
        $data['use_js'] = 'clockJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
    }

    public function transaction($type='status'){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');
        $act = 'in';
        $user_id = $user['id'];
        $date = date('Y-m-d');
        $get_in = $this->clock_model->get_shift_id($date,$user_id);

        if(count($get_in) > 0){
            $act = 'out';
            $date2 = date('Y-m-d',strtotime($get_in[0]->check_in));
            $get_sched = $this->clock_model->get_sched($date2,$user_id);
        }else{
            $date2 = date('Y-m-d');
            $get_sched = $this->clock_model->get_sched($date2,$user_id);
        }

        if(count($get_sched) > 0){

            if($get_sched[0]->work_hours == 0){
                     $txt = 'RESTDAY.';
            }else{
                $txt = date('l')." ".date('h:i A',strtotime($get_sched[0]->time_in)).' - '.date('h:i A',strtotime($get_sched[0]->time_out));
            }
        }else{
            $txt = "NO SCHEDULE";
        }


        $this->make->sDivRow(array('class'=>'clock-header'));
        //$this->make->sDiv(array('class'=>'clock-header','style'=>'border:solid blue 1px;'));
            $this->make->span('STATUS',array('class'=>'clock-header clock-txt'));
       // $this->make->eDiv();
        $this->make->eDivRow();

        if($type == 'status' && $act == 'in'){
            $this->make->sDivRow();
                $this->make->sDivCol('6','center');
                   $this->make->span('&nbsp;',array('style'=>'font-size:20px;font-weight:bold;'));
                $this->make->eDivCol();
                $this->make->sDivCol('6','center');
                    $this->make->span('&nbsp;',array('style'=>'font-size:20px;font-weight:bold;'));
                $this->make->eDivCol();
            $this->make->eDivRow();
            $this->make->sDivRow(array('class'=>'clock-body'));
                $this->make->sDivCol('8','center',2);
                    $this->make->button('CLOCK IN',array('class'=>'btn-block clock-btn-blue big','style'=>'margin:auto;margin-top:70px;border-radius: 25px;','id'=>'clockin','ref'=>'in'));
                $this->make->eDivCol();
            $this->make->eDivRow();
        }else{
            $this->make->sDivRow();
                $this->make->sDivCol('6','center');
                    $this->make->span(strtoupper('Date : '.date('m/d/Y',strtotime($get_in[0]->check_in))),array('style'=>'font-size:20px;font-weight:bold;'));
                $this->make->eDivCol();
                $this->make->sDivCol('6','center');
                    $this->make->span(strtoupper('Clock in : '.date('h:i a',strtotime($get_in[0]->check_in))),array('style'=>'font-size:20px;font-weight:bold;'));
                $this->make->eDivCol();
            $this->make->eDivRow();
            $this->make->sDivRow(array('class'=>'clock-body'));
                $this->make->sDivCol('8','center',2);
                    $this->make->button('CLOCK OUT',array('class'=>'btn-block clock-btn-red big','style'=>'margin:auto;margin-top:70px;border-radius: 25px;','id'=>'clockout','ref'=>'out'));
                $this->make->eDivCol();
            $this->make->eDivRow();
        }

        $this->make->sDivRow();
        $this->make->sDiv(array('class'=>'clock-header-bot'));
            $this->make->sDivRow();
                $this->make->span('SCHEDULE FOR TODAY :',array('style'=>'font-size:20px;font-weight:bold;'));
            $this->make->eDivRow();
            if($act == 'out'){
                $this->make->sDivRow();
                    if(count($get_sched) > 0){
                        $this->make->span(date('l',strtotime($get_in[0]->check_in))." ".date('h:i A',strtotime($get_sched[0]->time_in)).' - '.date('h:i A',strtotime($get_sched[0]->time_out)),array('style'=>'font-size:25px;'));
                    }
                    else
                        $this->make->span(date('l',strtotime($get_in[0]->check_in)),array('style'=>'font-size:25px;'));
                $this->make->eDivRow();
            }
            else{
                $this->make->sDivRow();
                    $this->make->span($txt,array('style'=>'font-size:25px;'));
                $this->make->eDivRow();
            }
            //$this->make->H(4,'SCHEDULE FOR TODAY :',array('style'=>'font-size:20px;font-weight:bold;'));
        $this->make->eDiv();
        $this->make->eDivRow();

        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }

   public function saveClockin(){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');

        $user_id = $user['id'];
        $date = date('Y-m-d');
        $get_sched = $this->clock_model->get_sched($date,$user_id);

        $cur_time = $this->input->post('cur_time');
        $conv_time = date('H:i:s',strtotime($cur_time));

        $error = null;
        //$status = "";
        $brk_out = "00:00:00";
        $time_out = "00:00:00";
        $count_in = 0;
        $shift_id = 0;
        if(count($get_sched) > 0){

            if($get_sched[0]->work_hours == 0){
                 $error = 'It is your Restday.';
            }else{

                $tgp = $get_sched[0]->timein_grace_period;
                $timein = $get_sched[0]->time_in;

                $time = explode(':', $tgp);
                $secs = ($time[0]*3600) + ($time[1]*60) + $time[2];
                $new_login = strtotime( $timein ) - $secs;
                //echo strtotime($timein).' + '.$secs.' = '.$new_login;
                $can_timein = date('H:i:s',$new_login);

                if($conv_time < $can_timein){
                    $error = 'Cannot clock in. Not yet time.';
                }else{
                    $error = "";
                //     //echo 'pde na';

                //     $c_in = $this->clock_model->check_in($date,$user_id);
                //     //echo $this->clock_model->db->last_query();
                //     if(count($c_in) > 0){
                //         $count_in = 1;
                //     }


                //     $items = array(
                //             'user_id'=>$user_id,
                //             'check_in'=>$date." ".$conv_time,
                //             'terminal_id'=>TERMINAL_ID
                //         );
                //     //var_dump($items);
                //     $shift_id = $this->clock_model->insert_clockin($items);
                //     $brk_out = $get_sched[0]->break_out;
                //     $time_out = $get_sched[0]->time_out;
                // //     //$status = 'okay';

                //     $this->session->set_userdata('today_in',$conv_time);
                }
            }

        }else{
            $error = 'No Schedule Found Today.';
        }

        echo json_encode(array('brk_out'=>$brk_out,'chk_out'=>$time_out,'error'=>$error,'count_in'=>$count_in,'shift_id'=>$shift_id));

    }

    public function cashdrawer($hstat='in'){
        $this->load->model('site/site_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/clock_helper');
        $this->load->helper('core/on_screen_key_helper');
        // $data = $this->syter->spawn(null);
        // $order = $this->get_order(false,$sales_id);

        $data['code'] = drawerPage($hstat);
        $data['add_css'] = array('css/cashier.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');

        $data['load_js'] = 'dine/clock.php';
        $data['use_js'] = 'drawerJS';
        // $data['noNavbar'] = true;
        $this->load->view('load',$data);
    }

    public function saveCashin(){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');

        $user_id = $user['id'];
        $date = date('Y-m-d');
        $cashin = $this->input->post('cashin');
        $cur_time = $this->input->post('cur_time');
        $conv_time = date('H:i:s',strtotime($cur_time));



        $error = null;
        // if(count($get_shift) > 0){
            /////////////////////save clock in
            // $c_in = $this->clock_model->check_in($date,$user_id);
            // //echo $this->clock_model->db->last_query();
            // if(count($c_in) > 0){
            //     $count_in = 1;
            // }

            $items = array(
                'user_id'=>$user_id,
                'check_in'=>$date." ".$conv_time,
                'terminal_id'=>TERMINAL_ID
            );
            //var_dump($items);
            $shift_id = $this->clock_model->insert_clockin($items);
            // $brk_out = $get_sched[0]->break_out;
            // $time_out = $get_sched[0]->time_out;

            $this->session->set_userdata('today_in',$conv_time);

            ///////////////////////////////end clock in
            $get_shift = $this->clock_model->get_shift_id($date,$user_id);
            $shift = $get_shift[0]->shift_id;
            //$cur_time = $this->input->post('cur_time');
            //$conv_time = date('H:i:s',strtotime($cur_time));


            $items_cash = array(
                'shift_id'=>$shift,
                'amount'=>$cashin,
                'user_id'=>$user_id,
                'trans_date'=>$date." ".$conv_time
            );


            $cash_id = $this->clock_model->insert_cashin($items_cash);
        // }else{
        //     $error = "You should clock in first.";
        // }

        site_alert('Clock IN Successfully with Cash In amount P'.$cashin.' successful.','success');
        echo json_encode(array('error'=>$error));

    }

    public function schedule(){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        //$date = date('Y-m-d');
        $act = $this->input->post('act');

        if($act == 'none'){
            $hdate = date('Y-m-d');
            // $hdate = $this->input->post('hdate');
        }else if($act == 'next'){
            $hdate = date('Y-m-d', strtotime('next monday', strtotime($this->input->post('hdate'))));
        }else if($act == 'prev'){
            $hdate = date('Y-m-d', strtotime('last monday', strtotime($this->input->post('hdate'))));
        }
        $range = $this->rangeWeek($hdate);
        $dates = $this->dateRange($range['start'],$range['end']);

        //echo date('Y-m-d', strtotime('next sunday', strtotime(phpNow())))."=========".date('Y-m-d', strtotime('last sunday', strtotime(phpNow())));

        $this->make->sDivRow(array('class'=>'clock-header'));
            $this->make->span('SCHEDULE',array('class'=>'clock-header clock-txt'));
        $this->make->eDivRow();
        // $this->make->sDivRow();
        //     $this->make->span('&nbsp;',array());
        // $this->make->eDivRow();
        //$this->make->append('<br>');
        //$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray double'));

        $this->make->sDivRow(array('style'=>'text-align:center;border:solid red 0px;height:420px;overflow:auto;padding-top:15px;'));
        $this->make->sDivCol('2','center');
            $this->make->button(fa('fa-chevron-circle-left fa-2x'),array('id'=>'prev-sched','class'=>'','style'=>'background-color:#7a6a63;'));
        $this->make->eDivCol();
        $this->make->sDivCol('8','center');
        $this->make->sDivRow();
        foreach ($dates as $date) {
            $day = date("l",strtotime($date));

            $date2 = date('Y-m-d',strtotime($date));
            $get_sched = $this->clock_model->get_sched($date2,$user_id);

            if(count($get_sched) > 0){

                if($get_sched[0]->work_hours == 0){
                    $time_in = 'Restday';
                    $time_out = '';
                }else{
                    $time_in = 'Start Time : '.date('h:i a',strtotime($get_sched[0]->time_in));
                    $time_out = 'End Time : '.date('h:i a',strtotime($get_sched[0]->time_out));

                }

            }else{
               // $error = 'No Schedule Found.';
                $time_in = 'No Schedule';
                $time_out = '';
            }

            // $this->make->sDivCol('1','center');
            // $this->make->eDivCol();
            $this->make->sDivCol('6','center');
            // $this->make->sDivRow();
            // $this->make->sDivCol('2','center');
            // $this->make->eDivCol();
            // $this->make->sDivCol('9','center');
                $this->make->sBox('default',array('class'=>'box-solid','style'=>'height:80px;'));
                    $this->make->sBoxBody();
                        $this->make->sDivRow();
                            $this->make->sDivCol(12,'left');
                                $this->make->span($day,array('style'=>'font-size:15px;font-weight:bold;color:#950000;'));
                                $this->make->span($date,array('style'=>'font-size:12px;font-weight:bold;color:#000000;float:right;'));
                            $this->make->eDivCol();
                        $this->make->eDivRow();
                        $this->make->sDivRow();
                            $this->make->sDivCol(12,'center');
                                $this->make->span($time_in,array('style'=>'font-size:15px;font-weight:bold;'));
                            $this->make->eDivCol();
                        $this->make->eDivRow();
                        $this->make->sDivRow();
                            $this->make->sDivCol(12,'center');
                                $this->make->span($time_out,array('style'=>'font-size:15px;font-weight:bold;'));
                            $this->make->eDivCol();
                        $this->make->eDivRow();
                    $this->make->eBoxBody();
                $this->make->eBox();
            // $this->make->eDivCol();
            // $this->make->sDivCol('1','center');
            // $this->make->eDivCol();
            // $this->make->eDivRow();
            $this->make->eDivCol();
            // $this->make->sDivCol('1','center');
            // $this->make->eDivCol();

        }
        $this->make->eDivRow();
        $this->make->eDivCol();
        $this->make->sDivCol('2','center');
            $this->make->button(fa('fa-chevron-circle-right fa-2x'),array('id'=>'next-sched','class'=>'','style'=>'background-color:#7a6a63;'));
        $this->make->eDivCol();
        $this->make->eDivCol();
        $this->make->eDivRow();



        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'hdate'=>$hdate));
    }

    public function dateRange($first, $last, $step = '+1 day', $format = 'm/d/Y' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function rangeWeek($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
        $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
        return $res;
    }
    public function timecard($fday=null,$lday=null){
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        $this->load->helper('dine/clock_helper');
        $this->load->helper('core/on_screen_key_helper');
        //$data = $this->syter->spawn(null);

        $user = $this->session->userdata('user');
        $user_id = $user['id'];

        $user_detail = $this->clock_model->get_user_details($user_id);
        $name = $user_detail[0]->fname." ".$user_detail[0]->fname." ".$user_detail[0]->lname;

        if($fday == null){
            $fday = date('Y-m-01'); // hard-coded '01' for first day
        }

        if($lday == null){
            $lday = date('Y-m-t');
        }

        $get_dtr =  $this->clock_model->get_user_dtrs($user_id,$fday,$lday);
        //echo $this->clock_model->db->last_query();
        $data['code'] = timecardPage($name,$get_dtr);
        $data['load_js'] = 'dine/clock.php';
        $data['use_js'] = 'timecardJS';
        $data['noNavbar'] = true;
        $this->load->view('load',$data);
    }
    public function saveCashout(){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');

        $user_id = $user['id'];
        $date = date('Y-m-d');
        $cashin = $this->input->post('cashin');
        $cur_time = $this->input->post('cur_time');
        $conv_time = date('H:i:s',strtotime($cur_time));
        /////////////save cashout
         $items = array(
                'user_id'=>$user_id,
                'amount'=>$cashin,
                'trans_date'=>$date,
                'terminal_id'=>TERMINAL_ID
            );

         //var_dump($items);

        $cash_id = $this->clock_model->insert_cashout($items);

        $get_shift = $this->clock_model->get_shift_id($date,$user_id);

        $error = null;
        if(count($get_shift) > 0){
            $shift = $get_shift[0]->shift_id;
            //$cur_time = $this->input->post('cur_time');
            //$conv_time = date('H:i:s',strtotime($cur_time));

            $items_update = array(
                'cashout_id'=>$cash_id,
                'check_out'=>$date." ".$conv_time
            );

            $this->clock_model->update_clockout($items_update,$shift);
        }else{
            $error = "You should clock in first.";
        }

        echo json_encode(array('error'=>$error));

    }

    public function search_dtr(){
        $this->load->model('site/site_model');
        $this->load->model('dine/clock_model');
        $this->load->helper('dine/clock_helper');
        //$data = $this->syter->spawn(null);
        $to = date('Y-m-d',strtotime($this->input->post('to')));
        $from = date('Y-m-d',strtotime($this->input->post('from')));

        $user = $this->session->userdata('user');
        $user_id = $user['id'];

        $user_detail = $this->clock_model->get_user_details($user_id);
        $name = $user_detail[0]->fname." ".$user_detail[0]->fname." ".$user_detail[0]->lname;


        $get_dtr =  $this->clock_model->get_user_dtrs($user_id,$from,$to);
        //echo $this->db->last_query();


        $code = timecardTable($name,$get_dtr);
        //echo $code;
        // $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function check_shift_xread()
    {
        $this->load->model('dine/clock_model');

        $userdata = $this->session->userdata('user');
        $x_read = $this->clock_model->get_xread_and_shift($userdata['id'],X_READ);

        $error = array();
        if (!empty($x_read)) {
            if (is_null($x_read[0]->xread_id))
                $error = array('error'=>'<h5>This shift has no X-Read data. Unable to proceed.</h5>');
            if (is_null($x_read[0]->cashout_id))
                $error = array('error'=>'<h5>Cash drawer has not been counted. Unable to proceed.</h5>');
        
        }
        echo json_encode($error);
    }
    public function clockOut(){
        $this->load->model('dine/clock_model');
        $this->load->model('site/site_model');

        $user = $this->session->userdata('user');

        $user_id = $user['id'];
        $date = date('Y-m-d');
        $cashin = $this->input->post('cashin');
        //$cur_time = $this->input->post('cur_time');
        $conv_time = date('H:i:s');

        $get_shift = $this->clock_model->get_shift_id($date,$user_id);

        $error = null;
        if(count($get_shift) > 0){
            $shift = $get_shift[0]->shift_id;
            //$cur_time = $this->input->post('cur_time');
            //$conv_time = date('H:i:s',strtotime($cur_time));

            $items_update = array(
                'check_out'=>$date." ".$conv_time
            );

            $this->clock_model->update_clockout($items_update,$shift);
        }else{
            $error = "You should clock in first.";
        }
        echo json_encode(array('error'=>$error));

    }
}