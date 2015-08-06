<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends CI_Controller {
    #manager
    public function __construct(){
        parent::__construct();
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/manager_helper');
        $this->load->helper('core/on_screen_key_helper');
        $this->load->helper('core/string_helper');
    }
    public function _remap($method,$params=array())
    {
        // if($this->session->userdata('user')){
        //     // return $CI->session->userdata('user');
            return call_user_func_array(array($this,$method),$params);
        // }
        // else{
        //     redirect('login','refresh');
        // }
        // if (!$this->session->userdata('manager_privs'))
        //     switch ($method) {
        //         case 'go_login':
        //             $this->go_login();
        //             break;

        //         default:
        //             $this->manager_login();
        //             break;
        //     }
        // else
        //     return call_user_func_array(array($this,$method),$params);
    }
    function manager_login()
    {
        $data = $this->syter->spawn(null,false);
        $data['code'] = managerLoginPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css');
        $data['add_js'] = array('js/on_screen_keys.js');
        $data['load_js'] = 'dine/manager';
        $data['use_js'] = 'managerLoginJs';

        $this->load->view('login',$data);
    }
    function go_login() {
        $pin = $this->input->post('pin');
        $manager = $this->manager_model->get_manager_by_pin($pin);

        if (!isset($manager->id)) {
            echo json_encode(array('error_msg'=>'Invalid manager pin'));
        } else {
            $this->session->set_userdata('manager_privs',array('method'=>'page','id'=>$manager->id));
            echo json_encode(array('success_msg'=>'Go'));
        }

        // return false;
    }
    function go_logout($new_header=null){
        $userdata = $this->session->userdata('manager_privs');
        if ($userdata['method'] == 'page')
            $this->session->unset_userdata('manager_privs');

        header('Location:'.base_url()."cashier");
        $redirect_location = "cashier";
        if (!is_null($new_header))
            $redirect_location = $new_header;

        header('Location:'.base_url().$redirect_location);
        // redirect(base_url()."cashier",'refresh');
    }
    public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = managerPage();
        $data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'managerJs';
        $data['noNavbar'] = true; /*Hides the navbar. Comment-out this line to display the navbar.*/
        $this->load->view('cashier',$data);
    }
	public function manager_settings(){
        $data = $this->syter->spawn(null);
        $data['code'] = managerSettingsPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'systemJS';
        $this->load->view('load',$data);
    }
	public function manager_end_of_day(){
        $data = $this->syter->spawn(null);
        $data['code'] = managerEndOfDayPage();
        // $data['add_css'] = array('css/pos.css','css/cashier.css','css/cashier.css');
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'endofdayJS';
        $this->load->view('load',$data);
    }
    public function manager_orders(){
        $data = $this->syter->spawn(null);
        $data['code'] = managerOrdersPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/manager.php';
        // $data['use_js'] = 'managerJs';
        $data['use_js'] = 'managerOrdersJS';
        $this->load->view('load',$data);
    }
    public function print_endofday_receipt($asJson=true)
    {
        $this->load->model('dine/manager_model');
        // // Load PHPRtfLite Class
        // require_once APPPATH."/third_party/PHPRtfLite.php";

        /*
         * -----------------------------------------------------------
         *      Start of Receipt Printing
         * -----------------------------------------------------------
        */
        // $return = $this->get_order(false,$sales_id);
        // $order = $return['order'];
        // $details = $return['details'];

        $print_str = "\r\n"
            .$this->align_center("END OF DAY REPORTTTT",46," ")."\r\n"
            .$this->align_center("",46," ")."\r\n"
            .$this->align_center("GENERATED ".date('m/d/Y h:i:s A'),46," ")."\r\n"
            ."---------------------------------------------"."\r\n\r\n"
            .$this->align_center("TRANSACTION SUMMARY",46," ")."\r\n";

        $date = date('Y-m-d');
        $gtotal = $summary_total = 0;

        $cash_total = 0;
        $credit_total = 0;
        $check_total = 0;
        $debit_total = 0;
        $gc_total = 0;
        ///////////////FOR CASH
        $get_cash = $this->manager_model->get_payment_type($date,'cash');
        if(count($get_cash) > 0){

            $get_cash_count = $this->manager_model->get_payment_count($date,'cash');

            foreach($get_cash as $cval){
                if($cval->to_pay > $cval->amount){
                    $cash_total += $cval->amount;
                }else{
                    $cash_total += $cval->to_pay;
                }
                // echo $cash_total."---";
            }

            $print_str .=  $this->append_chars('CASH('.count($get_cash_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($cash_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR CREDIT
        $get_credit = $this->manager_model->get_payment_type($date,'credit');
        if(count($get_credit) > 0){

            $get_credit_count = $this->manager_model->get_payment_count($date,'credit');

            foreach($get_credit as $cval){
                if($cval->to_pay > $cval->amount){
                    $credit_total += $cval->amount;
                }else{
                    $credit_total += $cval->to_pay;
                }
                // echo $cash_total."---";
            }

            $print_str .=  $this->append_chars('CREDIT('.count($get_credit_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($credit_total,2),"left",23," ")."\r\n";
        }
         ///////////////FOR DEBIT
        $get_debit = $this->manager_model->get_payment_type($date,'debit');
        if(count($get_debit) > 0){

            $get_debit_count = $this->manager_model->get_payment_count($date,'debit');

            foreach($get_debit as $cval){
                if($cval->to_pay > $cval->amount){
                    $debit_total += $cval->amount;
                }else{
                    $debit_total += $cval->to_pay;
                }
                // echo $cash_total."---";
            }

            $print_str .=  $this->append_chars('DEBIT('.count($get_debit_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($debit_total,2),"left",23," ")."\r\n";
        }
         ///////////////FOR GC
        $get_gc = $this->manager_model->get_payment_type($date,'gc');
        if(count($get_gc) > 0){

            $get_gc_count = $this->manager_model->get_payment_count($date,'gc');

            foreach($get_gc as $cval){
                if($cval->to_pay > $cval->amount){
                    $gc_total += $cval->amount;
                }else{
                    $gc_total += $cval->to_pay;
                }
                // echo $cash_total."---";
            }

            $print_str .=  $this->append_chars('GIFTCARD('.count($get_gc_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($gc_total,2),"left",23," ")."\r\n";
        }

        $print_str .= $this->append_chars('============',"left",46," ")."\r\n";
        $gtotal = $cash_total + $credit_total + $debit_total + $gc_total;
        $print_str .= $this->append_chars('P'.number_format($gtotal,2),"left",46," ")."\r\n\r\n";

        //////////////////////////////////summary type

        $print_str .= $this->align_center("SALES BY ORDER TYPE SUMMARY",46," ")."\r\n";

        $counter_total = $dinein_total = $drivethru_total = $deliver_total = $pickup_total = $takeout_total = 0;

        ///////////////FOR COUNTER
        $get_counter = $this->manager_model->get_summary_type($date,'counter');
        if(count($get_counter) > 0){
            $get_counter_count = $this->manager_model->get_summary_count($date,'counter');
            foreach($get_counter as $cval){

                    $counter_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('COUNTER('.count($get_counter_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($counter_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR DINEIN
        $get_dinein = $this->manager_model->get_summary_type($date,'dinein');
        if(count($get_dinein) > 0){
            $get_dinein_count = $this->manager_model->get_summary_count($date,'dinein');
            foreach($get_dinein as $cval){

                    $dinein_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('DINE-IN('.count($get_dinein_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($dinein_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR DRIVETHRU
        $get_drive = $this->manager_model->get_summary_type($date,'drivethru');
        if(count($get_drive) > 0){
            $get_drivethru_count = $this->manager_model->get_summary_count($date,'drivethru');
            foreach($get_drive as $cval){

                    $drivethru_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('DRIVE-THRU('.count($get_drivethru_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($drivethru_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR DELIVERY
        $get_deliver = $this->manager_model->get_summary_type($date,'delivery');
        if(count($get_deliver) > 0){
            $get_deliver_count = $this->manager_model->get_summary_count($date,'delivery');
            foreach($get_deliver as $cval){

                    $deliver_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('DELIVERY('.count($get_deliver_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($deliver_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR PICKUP
        $get_pickup = $this->manager_model->get_summary_type($date,'pickup');
        if(count($get_pickup) > 0){
            $get_pickup_count = $this->manager_model->get_summary_count($date,'pickup');
            foreach($get_pickup as $cval){

                    $pickup_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('PICKUP('.count($get_pickup_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($pickup_total,2),"left",23," ")."\r\n";
        }
        ///////////////FOR TAKEOUT
        $get_takeout = $this->manager_model->get_summary_type($date,'takeout');
        if(count($get_takeout) > 0){
            $get_takeout_count = $this->manager_model->get_summary_count($date,'takeout');
            foreach($get_takeout as $cval){

                    $takeout_total += $cval->total_paid;

            }

            $print_str .=  $this->append_chars('TAKEOUT('.count($get_takeout_count).')',"right",23," ").
                        $this->append_chars('P'.number_format($takeout_total,2),"left",23," ")."\r\n";
        }

        $print_str .= $this->append_chars('============',"left",46," ")."\r\n";
        $summary_total = $drivethru_total + $counter_total + $dinein_total;
        $print_str .= $this->append_chars('P'.number_format($summary_total,2),"left",46," ")."\r\n\r\n";

        //////////////////////////////////station summary

        $print_str .= $this->align_center("SALES BY STATION SUMMARY",46," ")."\r\n";

        $get_terminals = $this->manager_model->get_terminals();

        if(count($get_terminals) > 0){
            $total_all_terminals = 0;
            foreach ($get_terminals as $val) {
                $t_terminal = 0;
                $get_terminal_total = $this->manager_model->get_terminal_total($date,$val->terminal_id);
                if(count($get_terminal_total) > 0){
                    // $CI->make->sDivRow();
                    // $CI->make->sDivCol('6','left');
                    //     $CI->make->span('POS STATION '.$val->terminal_id.'('.count($get_terminal_total).')',array('class'=>'', 'style'=>'font-size:14px;'));
                    // $CI->make->eDivCol();
                    foreach($get_terminal_total as $cval){

                            $t_terminal += $cval->total_paid;

                        // echo $cash_total."---";
                    }

                    $print_str .=  $this->append_chars('POS STATION '.$val->terminal_id.'('.count($get_terminal_total).')',"right",23," ").
                        $this->append_chars('P'.number_format($t_terminal,2),"left",23," ")."\r\n";
                    // $CI->make->sDivCol('6','right');
                    //     $CI->make->span('P'.number_format($t_terminal,2),array('class'=>'', 'style'=>'font-size:14px;'));
                    // $CI->make->eDivCol();
                    // $CI->make->eDivRow();
                }
                $total_all_terminals += $t_terminal;
            }

            $print_str .= $this->append_chars('============',"left",46," ")."\r\n";
            $print_str .= $this->append_chars('P'.number_format($total_all_terminals,2),"left",46," ")."\r\n\r\n";
        }

        //////////////////////////////////void summary

        $print_str .= $this->align_center("VOID SUMMARY",46," ")."\r\n";

        $openvoid_total = $settledvoid_total = 0;
        ///////////////FOR VOID OPEN
        $openvoid = $this->manager_model->get_void_open($date);
        if(count($openvoid) > 0){
            foreach($openvoid as $cval){

                    $openvoid_total += $cval->total_amount;

                // echo $cash_total."---";
            }
            $print_str .=  $this->append_chars('OPEN-VOID('.count($openvoid).')',"right",23," ").
                        $this->append_chars('P'.number_format($openvoid_total,2),"left",23," ")."\r\n";

        }
        ///////////////FOR SETTLE VOID
        $settledvoid = $this->manager_model->get_void_settled($date);
        if(count($settledvoid) > 0){
            foreach($settledvoid as $cval){

                    $settledvoid_total += $cval->total_amount;

                // echo $cash_total."---";
            }
            $print_str .=  $this->append_chars('SETTLED-VOID('.count($settledvoid).')',"right",23," ").
                        $this->append_chars('P'.number_format($settledvoid_total,2),"left",23," ")."\r\n";

        }

        $print_str .= $this->append_chars('============',"left",46," ")."\r\n";
        $total_all_void = $openvoid_total + $settledvoid_total;
        $print_str .= $this->append_chars('P'.number_format($total_all_void,2),"left",46," ")."\r\n\r\n";

        ////////////////////////////end////////////////////////

        $filename = "endofday.txt";
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
        //unlink($filename);
        unlink($batfile);

        if ($asJson)
            echo json_encode(array('msg'=>'End of Day Report has been printed'));
        else
            return array('msg'=>'End of Day Report has been printed');
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
    public function manager_end_of_day_report()
    {
        $data = $this->syter->spawn(null);
        $data['code'] = managerEndofDayReport();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/bootbox2.min.js');
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'endofdayReportJS';
        $this->load->view('load',$data);
    }
    public function manager_xread(){
        $data = $this->syter->spawn(null);

        $data['code'] = managerXreadPage();
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'xreadJS';
        $this->load->view('load',$data);
    }
    public function manager_zread(){
        $data = $this->syter->spawn(null);

        $date = date('Y-m-d');
        $allsales = $this->manager_model->get_all_sales_today($date);

        $data['code'] = managerZreadPage($allsales);
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'zreadJS';
        $this->load->view('load',$data);
    }
    public function print_reading($asJson=true)
    {
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/cashier_model');

        $date = date('Y-m-d');
        if($this->input->post('read') == 'xread'){
            $allsales = $this->manager_model->get_all_sales_today($date,TERMINAL_ID);
        }else{
            $allsales = $this->manager_model->get_all_sales_today($date);
        }

        //echo count($allsales);
        $print_str = "";
        foreach($allsales as $val){


        $return = $this->get_order(false,$val->sales_id);
        $order = $return['order'];
        $details = $return['details'];
        $discounts = $return['discounts'];
        $tax = $return['tax'];

        $print_str .= "\r\n"
            .$this->align_center("CHOWKING ROBINSONS GALLERIA (0913)",46," ")."\r\n"
            .$this->align_center("owned by FRESH 'N FAMOUS",46," ")."\r\n"
            .$this->align_center("Level 1 Robinson's Galleria Ortigas QC",46," ")."\r\n"
            .$this->align_center("TIN# 000-333-173-015-VAT",46," ")."\r\n"
            .$this->align_center("POS01: SNTPA00061",46," ")."\r\n"
            .$this->align_center("BIR Permit No. 0511-116-9186-015",46," ")."\r\n";

        $void = "";
        if($order['inactive'] == 1){
            $void = "VOIDED";
            $reason = $order['reason'];
                $print_str .= $this->align_center('Receipt No. '.$order['ref']." (VOIDED)",46," ")."\r\n"
                           .$this->align_center($order['reason'],46," ")."\r\n";

        }else{
            $print_str .= $this->align_center('Receipt No. '.$order['ref'],46," ")."\r\n";
        }

        $print_str .= "============================================="."\r\n";

        if (!empty($order['pay_types']))
            $print_str .= $this->align_center(date('Y-m-d D H:i:s',strtotime($order['datetime']))." ".$order['terminal_name']." OR# ".$order['ref'],46," ")."\r\n";
        else
            $print_str .= $this->align_center(date('Y-m-d D H:i:s',strtotime($order['datetime'])),46," ")."\r\n";

        $print_str .= "============================================="."\r\n\r\n";


        foreach ($details as $val) {
            $print_str .= $this->append_chars(number_format($val['qty']),"right",6," ");

            if ($val['qty'] == 1) {
                $print_str .= $this->append_chars($val['name'],"right",30," ").
                    $this->append_chars(number_format($val['price'],2)."V","left",9," ")."\r\n";
            } else {
                $print_str .= $this->append_chars($val['name']." @ ".$val['price'],"right",30," ");
                    $this->append_chars(number_format($val['price'] * $val['qty'],2)."V","left",9," ")."\r\n";
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            foreach ($modifs as $vv) {
                $print_str .= "      ".$this->append_chars(number_format($val['qty']),"right",5," ");

                if ($vv['qty'] == 1) {
                    $print_str .= $this->append_chars($vv['name'],"right",25," ").
                        $this->append_chars(number_format($vv['price'],2)."V","left",9," ")."\r\n";
                } else {
                    $print_str .=  $this->append_chars($vv['name']." @ ".$vv['price'],"right",25," ").
                        $this->append_chars(number_format($vv['price'] * $vv['qty'],2)."V","left",9," ")."\r\n";
                }
            }
        }

        // $vat = round($order['amount'] / (1 + BASE_TAX) * BASE_TAX,1);
        $vat = 0;
        foreach ($tax as $vtax) {
            $vat += $vtax['amount'];
        }

        $print_str .= "\r\n".$this->append_chars(ucwords($order['type']),"right",35," ").$this->append_chars(number_format($order['amount'] - $vat,2),"left",10," ")."\r\n";
        foreach($tax as $vtax){
        $print_str .= $this->append_chars($vtax['name'],"right",35," ").$this->append_chars(number_format($vtax['amount'],2),"left",10," ")."\r\n";
        }
        if (!empty($order['pay_type'])) {
            $print_str .= "\r\n".$this->append_chars("Amount due","right",35," ").$this->append_chars("P".number_format($order['amount'],2),"left",10," ")."\r\n";
            $print_str .= $this->append_chars(ucwords($order['pay_type']),"right",35," ").$this->append_chars("P".number_format($order['pay_amount'],2),"left",10," ")."\r\n";
            if (!empty($order['pay_ref'])) {
                $print_str .= $this->append_chars("     Reference ".$order['pay_ref'],"right",45," ")."\r\n";
            }
            $print_str .= $this->append_chars("Change","right",35," ").$this->append_chars("P".number_format($order['pay_amount'] - $order['amount'],2),"left",10," ")."\r\n";


        } else {
            $print_str .= "\r\n".$this->align_center("=============================================",45," ");
            $print_str .= "\r\n".$this->append_chars("Amount due","right",35," ").$this->append_chars("P".number_format($order['amount'],2),"left",10," ")."\r\n";
            $print_str .= $this->align_center("=============================================",45," ");
        }
            $print_str .= "\r\n"
                .$this->align_center("This serves as your official receipt.",46," ")."\r\n"
                .$this->align_center("Thank you and please come again.",46," ")."\r\n"
                .$this->align_center("For feedback, please call us at",46," ")."\r\n"
                .$this->align_center("(02)XXX-XXXX or (XXX)XXX-XXXX",46," ")."\r\n"
                .$this->align_center("Email : feedback@xxxx.com.ph",46," ")."\r\n"
                .$this->align_center(" Please visit us at www.xxxxxx.com.ph",46," ")."\r\n\r\n"
                .$this->align_center("****************************************",46," ")."\r\n\r\n";


        }///////////////end ng foreach

        if($this->input->post('read') == 'xread'){
            $filename = "xreading.txt";
            $msg = "X-Reading has been print.";
        }else{
             $filename = "zreading.txt";
             $msg = "Z-Reading has been print.";
        }
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
        // unlink($filename);
        unlink($batfile);

        if ($asJson)
            echo json_encode(array('msg'=>$msg));
        else
            return array('msg'=>$msg);
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
                "guest"=>$res->guest,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount,
                "balance"=>$res->total_amount - $res->total_paid,
                "paid"=>$res->paid,
                // "pay_type"=>$res->pay_type,
                // "pay_amount"=>$res->pay_amount,
                // "pay_ref"=>$res->pay_ref,
                // "pay_card"=>$res->pay_card,
                "inactive"=>$res->inactive,
                "reason"=>$res->reason,
            );
        }

        $order_menus = $this->cashier_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
        $order_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
        foreach ($order_menus as $men) {
            $details[$men->line_id] = array(
                "id"=>$men->sales_menu_id,
                "menu_id"=>$men->menu_id,
                "name"=>$men->menu_name,
                "code"=>$men->menu_code,
                "price"=>$men->price,
                "qty"=>$men->qty,
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
        $discounts = array();
        foreach ($sales_discs as $dc) {
            $items = array();
            if($dc->items != ""){
                $items = explode(',', $dc->items);
            }
            $discounts[$dc->disc_id] = array(
                    "name"  => $dc->name,
                    "code"  => $dc->code,
                    "bday"  => sql2Date($dc->bday),
                    "guest" => $dc->guest,
                    "disc_rate" => $dc->disc_rate,
                    "disc_code" => $dc->disc_code,
                    "disc_type" => $dc->type,
                    "items" => $items
                );
        }
        $tax = array();
        foreach ($sales_tax as $st) {
            $tax[$st->sales_tax_id] = array(
                    "sales_id"  => $st->sales_id,
                    "name"  => $st->name,
                    "rate" => $st->rate,
                    "amount" => $st->amount
                );
        }
        if($asJson)
            echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"tax"=>$tax));
        else
            return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"tax"=>$tax);
    }
    public function system_settings(){
        $this->load->model('site/site_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/setup_model');
        $this->load->helper('core/on_screen_key_helper');
        $this->load->helper('dine/manager_helper');
        $data = $this->syter->spawn(null);

        $details = $this->setup_model->get_details(1);
        $det = $details[0];


        $data['code'] = systemSettingsPage($det);
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'systemSettingsJS';
        $this->load->view('load',$data);
    }
    public function system_settings_db(){
        $this->load->model('dine/setup_model');
        $items = array(
            "branch_code"=>$this->input->post('branch_code'),
            "branch_name"=>$this->input->post('branch_name'),
            "branch_desc"=>$this->input->post('branch_desc'),
            "contact_no"=>$this->input->post('contact_no'),
            "delivery_no"=>$this->input->post('delivery_no'),
            "address"=>$this->input->post('address'),
            "tin"=>$this->input->post('tin'),
            "machine_no"=>$this->input->post('machine_no'),
            "bir"=>$this->input->post('bir'),
            "permit_no"=>$this->input->post('permit_no'),
            "serial"=>$this->input->post('serial'),
            "email"=>$this->input->post('email'),
            "website"=>$this->input->post('website')
            // "currency"=>$this->input->post('currency')
        );

            $this->setup_model->update_details($items, 1);
            // $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Branch Details';

        echo json_encode(array('msg'=>$msg));
    }
    public function check_xread_okay()
    {
        $this->load->model('dine/clock_model');
        $user = $this->session->userdata('user');
        $user_id = $user['id'];
        $date = $this->site_model->get_db_now('sql');
        $get_shift = $this->clock_model->get_shift_id(date2Sql($date),$user_id);
        $shift = null;
        // $echo = null;
        if(count($get_shift) > 0){
            $shift = $get_shift[0]->shift_id;
            $cashout_id = $get_shift[0]->cashout_id;

            if (is_null($cashout_id) || $cashout_id = "")
                echo json_encode(array('error' => 'Please count cash drawer first before proceeding to X-Read'));
        } else {
            echo json_encode(array('error'=>'This cashier has no clock in data. Unable to proceed'));
        }
    }
    public function check_zread_okay()
    {
        $unsettled_trans = $this->check_unsettled_sales(false);
        $unclosed_xread = $this->check_unclosed_xread(false);

        if (!empty($unclosed_xread) || !empty($unsettled_trans))
            echo json_encode(!empty($unsettled_trans) ? $unsettled_trans : $unclosed_xread);
    }
    public function check_unclosed_xread($asJson=true)
    {
        $this->load->model('dine/clock_model');
        // $shift = $this->clock_model->get_shifts('check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\'');
        $shift = $this->clock_model->get_shifts('check_out IS NULL OR check_out = \'\' OR cashout_id IS NULL OR cashout_id =\'\'');

        $return_array = array();

        if (!empty($shift))
            $return_array = array('error'=>'<h5>Some shifts have missing drawer count or X-Read data.<br/>Unable to process Z-Read.</h5>');

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
    public function manager_reports(){
        $data = $this->syter->spawn(null);
        $data['code'] = managerReportPage();
        $data['add_css'] = array('css/datepicker/datepicker.css');//,'css/timepicker/bootstrap-timepicker.min.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js');//,'js/plugins/timepicker/bootstrap-timepicker.min.js');

        // $data['add_css'] = array('css/daterangepicker/daterangepicker-bs3.css');//,'css/timepicker/bootstrap-timepicker.min.css');
        // $data['add_js'] = array('js/plugins/daterangepicker/daterangepicker.js');//,'js/plugins/timepicker/bootstrap-timepicker.min.js');

        $data['add_css'][] = 'css/daterangepicker/daterangepicker-bs3.css';//,'css/timepicker/bootstrap-timepicker.min.css');
        $data['add_js'][] = 'js/plugins/daterangepicker/daterangepicker.js';//,'js/plugins/timepicker/bootstrap-timepicker.min.js');

        // $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['load_js'] = 'dine/manager.php';
        $data['use_js'] = 'managerReportsJs';
        $this->load->view('load',$data);
    }
    public function manager_report_form($report_title)
    {
        if ($report_title == "daily-sales") {
            $code = build_report("display_daily_sales",array(
                    'date' => array("Select date","date_to",date('Y-m-d'),null,array('class'=>'rOkay daterangepicker timepicker','ro-msg'=>'Please select a valid date')),
                    // 'input' => array('Date range','date_to','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'terminalDrop' => array("Terminal","terminal",null,null),
                    'userDrop' => array("Cashier","cashier",null,null),
                    'yesOrNoDrop' => array("Include voided?","voided",null,null),
                ));
        }elseif ($report_title == "system-sales"){
            $code = build_report("display_system_sales",array(
                    // 'date' => array("Select date","date_to",date('Y-m-d'),null,array('class'=>'rOkay daterangepicker','ro-msg'=>'Please select a valid date')),
                    // daterangepicker
                    // 'date' => array('Date','daterange','','',array('class'=>'rOkay ','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'terminalDrop' => array("Terminal","terminal",null,null),
                    'userDrop' => array("Cashier","cashier",null,null),
                ));
        }elseif ($report_title == "employee-sales"){
            $code = build_report("display_employee_sales",array(
                    'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'userDrop' => array('Employee','server_id','','',array('class'=>'rOkay')),
                    //'yesOrNoDrop' => array("Include inactive?","voided","no",null),
                ));
        }elseif ($report_title == "menu-sales"){
            $code = build_report("display_menu_sales",array(
                    'input' => array("Select date","daterange",null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;','ro-msg'=>'Please select a valid date'),null,fa('fa-calendar')),
                    // 'terminalDrop' => array("Terminal","terminal",null,null,array()),
                    'userDrop' => array("Cashier","user_id",null,null)
                ));
        }elseif ($report_title == "hourly-sales"){
            $code = build_report("display_hourly_sales",array(
                    'input' => array('Date range','date','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    // 'date' => array("Select date","date",date('Y-m-d'),null,array('class'=>'rOkay datepicker','ro-msg'=>'Please select a valid date')),
                    'userDropSearch' => array('Cashier','cashier','',''),
                ));
        } elseif ($report_title == "fs-sales") {
            $code = build_report('display_fs_sales',array(
                    'input' => array('Date range','daterange','','',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar')),
                    'userDropSearch' => array('Food Server','server_id','',''),
                    'yesOrNoDrop' => array("Include inactive?","voided","no",null),
                ));
        }

        echo json_encode(array('code'=>$code));
    }
	public function print_receipt($report_title=null){
        $this->load->model('dine/cashier_model');        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');
        $this->load->helper('core/string_helper');

        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'branch_name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
            );
        }

        $print_str = "\r\n\r\n";
        $wrap = wordwrap($branch['name'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= $this->align_center($v,38," ")."\r\n";
        }

        if($report_title == "display-system-sales"){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $date = $this->input->get('date_to');
            $date_param = (is_null($date) ? date('Y-m-d') : $date);
            $terminal = $this->input->get('terminal');
            $cashier = $this->input->get('cashier');

            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            //-----------------------------------------------------------------------------
            //START HEADER
            //-----------------------------------------------------------------------------
            $rc = 1;
            $filename='System Sales Report';
            $sheet->getCell('A'.$rc)->setValue($filename);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');

            if (!empty($cashier)){
                $cashier_name = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Employee');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($cashier_name);

            if (!empty($terminal)){
                $terminal_name = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('PC');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($terminal_name);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Date');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue($date_param);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Printed on');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue(date("d M y g:i:s A"));
            $rc++;
            $sheet->getStyle('A'.$rc.':'.'C'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            //-----------------------------------------------------------------------------
            //END HEADER
            //-----------------------------------------------------------------------------
            //-----------------------------------------------------------------------------
            //-----------------------------------------------------------------------------
            // get_gross_sales
            $where_array = array('date(datetime)' => $date_param,'trans_sales.paid'=>1,'trans_sales.type_id'=>SALES_TRANS);

            if (!empty($cashier)){
                $where_array['trans_sales.user_id'] = $cashier;
                // $c = $this->settings_model->get_cashier($cashier);
                // $cashier_name = $c[0];
                $cashier_name = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }

            if (!empty($terminal)){
                $where_array['trans_sales.terminal_id'] = $terminal;
            }else{
                $terminal_name = 'All Server';
            }

            // if ($is_voided == 'no')
            //     $where_array['trans_sales.inactive'] = 0;

            $gross_sales = $this->settings_model->get_gross_sales(null,$where_array);
            $gs = $gross_sales[0];
            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue($gs->gross_sales);
            //-----------------------------------------------------------------------------
            //START CONTENT
            //-----------------------------------------------------------------------------
            $vat = $gs->gross_sales * .12;
            $net_sales = $gs->gross_sales - $vat;
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Net Sales');
            $sheet->getCell('C'.$rc)->setValue(number_format($net_sales,2));

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('12% VAT');
            $sheet->getCell('C'.$rc)->setValue(number_format($vat,2));

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Local Tax');
            $local_tax = 0;
            $sheet->getCell('C'.$rc)->setValue(number_format($local_tax,2));

            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);

            // $rc++;
            $sheet->getCell('A'.$rc)->setValue('Gross Sales');
            $gross_sales = $gs->gross_sales;
            $sheet->getCell('C'.$rc)->setValue(number_format($gross_sales,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Carry Over');
            $carry_over = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($carry_over,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($carry_over,2));

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Deposit Total');
            $deposit_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($deposit_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($deposit_total,2));
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Begun Total');
            $begun_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($begun_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($begun_total,2));
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Paid Total');
            $paid_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($paid_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($paid_total,2));
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            $sheet->getCell('A'.$rc)->setValue('Outstanding');
            $outstanding_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($outstanding_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($outstanding_total,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Trans. Count');

            $_label = array('Dine In','Take Out','Delivery','Finish Waste');
            $_count = array($gs->qty,0,0,0);
            $_total = array($gs->gross_sales,0,0,0);

            $tc_qty_total = $tc_ttl_total = 0;

            for($i=0;$i<count($_label);$i++){
                $rc++;
                $sheet->getCell('A'.$rc)->setValue($_label[$i]);
                $sheet->getCell('B'.$rc)->setValue(number_format($_count[$i],2));
                $sheet->getCell('C'.$rc)->setValue(number_format($_total[$i],2));
                $tc_qty_total += $_count[$i];
                $tc_ttl_total += $_total[$i];
            }
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            // $rc++;
            $sheet->getCell('A'.$rc)->setValue('TC Total');
            $sheet->getCell('B'.$rc)->setValue(number_format($tc_qty_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($tc_ttl_total,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Guest Count');

            $_label = array('Dine In','Take Out','Delivery');
            $_count = array(0,0,0);
            $_total = array(0,0,0);

            $tc_qty_total = $tc_ttl_total = 0;

            for($i=0;$i<count($_label);$i++){
                $rc++;
                $sheet->getCell('A'.$rc)->setValue($_label[$i]);
                $sheet->getCell('B'.$rc)->setValue(number_format($_count[$i],2));
                $sheet->getCell('C'.$rc)->setValue(number_format($_total[$i],2));
                $tc_qty_total += $_count[$i];
                $tc_ttl_total += $_total[$i];
            }
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            // $rc++;
            $sheet->getCell('A'.$rc)->setValue('Average Cover');
            $sheet->getCell('B'.$rc)->setValue(number_format($tc_qty_total,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($tc_ttl_total,2));

            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Manager Void');
            $manager_void_total = 0;
            $sheet->getCell('C'.$rc)->setValue(number_format($manager_void_total,2));

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Refund Trans');
            $manager_refund_trans_total = 0;
            $sheet->getCell('C'.$rc)->setValue(number_format($manager_refund_trans_total,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Other Collection');
            $rc++;
            $tips_total = 0;
            $sheet->getCell('A'.$rc)->setValue('Tips');
            $sheet->getCell('C'.$rc)->setValue(number_format($tips_total,2));
            $tips_ttl_total = 0;
            $sheet->getCell('A'.$rc)->setValue('Total Tips');
            $sheet->getCell('C'.$rc)->setValue(number_format($tips_ttl_total,2));
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            $collection_total = $tips_total+$tips_ttl_total;
            $sheet->getCell('A'.$rc)->setValue('Ttl Collection');
            $sheet->getCell('C'.$rc)->setValue(number_format($collection_total,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Major Category');
            $category_list = $this->settings_model->get_category_list(null,$where_array);
            $gtotal_cat_qty = $gtotal_total_amount = 0;
            foreach($category_list as $v){
                $rc++;
                $sheet->getCell('A'.$rc)->setValue($v->menu_cat_name);
                $sheet->getCell('B'.$rc)->setValue(number_format($v->cat_qty,2));
                $sheet->getCell('C'.$rc)->setValue(number_format($v->trans_total_amnt,2));
                $gtotal_cat_qty += $v->cat_qty;
                $gtotal_total_amount += $v->trans_total_amnt;

            }
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            $sheet->getCell('A'.$rc)->setValue('Total');
            $sheet->getCell('B'.$rc)->setValue(number_format($gtotal_cat_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($gtotal_total_amount,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Loan');
            $loan_qty = $loan_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($loan_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($loan_total,2));
            $rc++;
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            //-----------------------------------------------------------------------------
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('CASH SALES');
            $cash_sales_qty = $cash_sales_total = 0;
            $cash_sales_qty = $gs->qty;
            $cash_sales_total = $gs->gross_sales;
            $sheet->getCell('B'.$rc)->setValue(number_format($cash_sales_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($cash_sales_total,2));
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Pickup');
            $pickup_qty = $pickup_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($pickup_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($pickup_total,2));
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Cash Drawer');
            $cash_drawer_qty = $cash_drawer_total = 0;
            $sheet->getCell('B'.$rc)->setValue(number_format($cash_drawer_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($cash_drawer_total,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Payment Details');
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('CASH');
            $cash_qty = $cash_total = 0;
            $cash_qty = $gs->qty;
            $cash_total = $gs->gross_sales;
            $sheet->getCell('B'.$rc)->setValue(number_format($cash_qty,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($cash_total,2));

            $grand_qty_total_payment = $grand_ttl_total_payment = 0;
            $sheet->getCell('A'.$rc)->setValue('CASH');
            $grand_qty_total_payment = $gs->qty;
            $grand_ttl_total_payment = $gs->gross_sales;
            $sheet->getCell('B'.$rc)->setValue(number_format($grand_qty_total_payment,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($grand_ttl_total_payment,2));

            $grand_qty_payment = $grand_ttl_payment = 0;
            $grand_qty_payment += $grand_qty_total_payment;
            $grand_ttl_payment += $grand_ttl_total_payment;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Total Payment');
            $sheet->getCell('B'.$rc)->setValue(number_format($grand_qty_payment,2));
            $sheet->getCell('C'.$rc)->setValue(number_format($grand_ttl_payment,2));
            //-----------------------------------------------------------------------------
            $rc++;
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Discount Details');
            $discount_list = $this->settings_model->get_discount_list(null,$where_array);
            $gtotal_discount_count = $gtotal_discount_value = 0;
            foreach($discount_list as $dl){
                $rc++;
                $sheet->getCell('A'.$rc)->setValue($dl->disc_name);
                $sheet->getCell('B'.$rc)->setValue($dl->discount_count);
                $sheet->getCell('C'.$rc)->setValue(number_format($dl->discount_value_total*-1,2));
                $gtotal_discount_count += $dl->discount_count;
                $gtotal_discount_value += $dl->discount_value_total*-1;
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Total Discount');
            $sheet->getCell('B'.$rc)->setValue($gtotal_discount_count);
            $sheet->getCell('C'.$rc)->setValue(number_format($gtotal_discount_value,2));
            $sheet->getStyle('A'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT');
            $sheet->getCell('B'.$rc)->setValue($gtotal_discount_count);
            $sheet->getCell('C'.$rc)->setValue(number_format($gtotal_discount_value/$gtotal_discount_count,2));

            //-----------------------------------------------------------------------------
            //END CONTENT
            //-----------------------------------------------------------------------------

            // Redirect output to a client’s web browser (Excel2007)
            //clean the output buffer
            ob_end_clean();

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } elseif($report_title == "display-employee-sales"){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $date = $this->input->get('date_to');
            $date_param = (is_null($date) ? date('Y-m-d') : $date);
            $terminal = $this->input->get('terminal');
            $cashier = $this->input->get('cashier');

            $rc = 1;

            $filename='Employee Sales Report';
            $sheet->getCell('A'.$rc)->setValue($filename);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');


            // Redirect output to a client’s web browser (Excel2007)
            //clean the output buffer
            ob_end_clean();

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } elseif($report_title == "display-menu-sales"){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $date = $this->input->get('date_to');
            $date_param = (is_null($date) ? date('Y-m-d') : $date);
            $terminal = $this->input->get('terminal');
            $cashier = $this->input->get('cashier');

            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(15);
            //-----------------------------------------------------------------------------
            //START HEADER
            //-----------------------------------------------------------------------------
            $rc = 1;
            $filename='Menu Item Sales Report';
            $sheet->getCell('A'.$rc)->setValue($filename);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');

            if (!empty($cashier)){
                $cashier_name = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Employee');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($cashier_name);

            if (!empty($terminal)){
                $terminal_name = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('PC');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($terminal_name);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Date');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue($date_param);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Printed on');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue(date("d M y g:i:s A"));
            $rc++;
            $sheet->getStyle('A'.$rc.':'.'C'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);

            //-----------------------------------------------------------------------------
            //END HEADER
            //-----------------------------------------------------------------------------
            $menu_list = $this->settings_model->get_menu_item_sales(null,$where_array);
            $rc++;
            foreach($menu_list as $menu){
                $rc++;
                $sheet->getCell('A'.$rc)->setValue($menu->menu_code);
                $sheet->getCell('B'.$rc)->setValue($menu->menu_name);
                $sheet->getCell('C'.$rc)->setValue($menu->menu_item_count);
                $rc++;
                $sheet->getCell('C'.$rc)->setValue(number_format($menu->cost,2));
            }


            // Redirect output to a client’s web browser (Excel2007)
            //clean the output buffer
            ob_end_clean();

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } elseif($report_title == "display-hourly-sales"){
            $this->load->library('Excel');
            $sheet = $this->excel->getActiveSheet();
            $date = $this->input->get('date_to');
            $date_param = (is_null($date) ? date('Y-m-d') : $date);
            $terminal = $this->input->get('terminal');
            $cashier = $this->input->get('cashier');

            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(5);
            $sheet->getColumnDimension('C')->setWidth(15);
            //-----------------------------------------------------------------------------
            //START HEADER
            //-----------------------------------------------------------------------------
            $rc = 1;
            $filename='Hourly Sales Report';
            $sheet->getCell('A'.$rc)->setValue($filename);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->setBold(true);
            $sheet->getStyle('A'.$rc.':'.'I'.$rc)->getFont()->getColor()->setRGB('FF0000');

            if (!empty($cashier)){
                $cashier_name = $cashier;
            }else{
                $cashier_name = 'All Cashier';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Employee');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($cashier_name);

            if (!empty($terminal)){
                $terminal_name = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('PC');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            $sheet->getCell('B'.$rc)->setValue($terminal_name);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Date');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue($date_param);

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Printed on');
            $sheet->getStyle('A'.$rc)->getFont()->setBold(true);
            // $sheet->getCell('B'.$rc)->setValue(date("Y-m-d H:i:s"));
            $sheet->getCell('B'.$rc)->setValue(date("d M y g:i:s A"));
            $rc++;
            $sheet->getStyle('A'.$rc.':'.'D'.$rc)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DASHED);

            //-----------------------------------------------------------------------------
            //END HEADER
            //-----------------------------------------------------------------------------

            $rc++;

            $ctr=1;
            $gtotal_net_sales = 0;
            foreach(unserialize(TIMERANGES) as $k=>$v){
                $rc++;
                $sheet->getCell('B'.$rc)->setValue($ctr.' '.$v['FTIME'].' - '.$v['TTIME']);
                $rc++;
                $sheet->getCell('A'.$rc)->setValue('Net Sales Total');

                $net_sales_total = $this->settings_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$date);
                $net_sales_total = $net_sales_total[0];
                $col_a = $col_b = 0;
                // $sheet->getCell('C'.$rc)->setValue($col_a);
                $col_b = $net_sales_total;
                // $sheet->getCell('A'.$rc)->setValue('-->'.$col_b->total_per_hour);
                // $sheet->getCell('A'.$rc)->setValue($this->db->last_query());
                $sheet->getCell('D'.$rc)->setValue(number_format($col_b->total_per_hour,2));
                $gtotal_net_sales += $col_b->total_per_hour;

                $rc++;
                $sheet->getCell('A'.$rc)->setValue('Average $/Cover');

                $col_a = $col_b = 0;
                $sheet->getCell('C'.$rc)->setValue($col_a);
                $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

                $rc++;
                $sheet->getCell('A'.$rc)->setValue('Average $/Check');

                $col_a = $col_b = 0;
                $sheet->getCell('C'.$rc)->setValue($col_a);
                $sheet->getCell('D'.$rc)->setValue(number_format($col_b,2));

                $ctr++;
            }

            $rc++;
            $sheet->getCell('A'.$rc)->setValue('TOTAL');
            $rc++;
            $sheet->getCell('A'.$rc)->setValue('Net Sales Total');
            $sheet->getCell('D'.$rc)->setValue(number_format($gtotal_net_sales,2));


            // Redirect output to a client’s web browser (Excel2007)
            //clean the output buffer
            ob_end_clean();

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
        } else if ($report_title == "fs-sales") {
            $daterange = $this->input->post('daterange');
            $server_id = $this->input->post('server_id');
            $void_included = $this->input->post('voided');
            try {
                $dates = explode(" to ",$daterange);
                $date_from = (empty($dates[0]) ? date('Y-m-d')." 00:00" : date('Y-m-d H:i',strtotime($dates[0])));
                $date_to = (empty($dates[1]) ? date('Y-m-d H:i') : date('Y-m-d H:i',strtotime($dates[1])));

                $fs_sums = $this->manager_model->get_fs_daily_sums($date_from,$date_to,$server_id,($void_included == 'no' ? false : true));
                $print_str .=
                    align_center("FOOD SERVER REPORT",38)."\r\n";
				if (!empty($server_id) && !empty($fs_sums)) {
                    $print_str .= append_chars("Employee",'right',18," ").$fs_sums[0]->waiter_id." - ".$fs_sums[0]->fname." ".$fs_sums[0]->lname."\r\n";
                } else {
                    $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";
                }
                $print_str .= append_chars("Inactive Included",'right',18," ").($void_included == 'no' ? 'NO' : 'YES')."\r\n"
                    .append_chars("Starting Datetime",'right',18," ").$date_from."\r\n"
                    .append_chars("Ending Datetime",'right',18," ").$date_to."\r\n"
                    .append_chars("Printed On",'right',18," ").date('Y-m-d H:i')."\r\n"
 					.append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                    .append_chars("",'right',38,"-")."\r\n\r\n";

                $total_orders = $sum_orders = 0;
                foreach ($fs_sums as $value) {
                    $print_str .=
                        append_chars($value->fname." ".$value->lname,'right',20," ")
                        .append_chars($value->total_orders,'right',8," ")
                        .append_chars(number_format($value->total_amount,2),'left',10," ")."\r\n";
                    $total_orders += $value->total_orders;
                    $sum_orders += $vaue->total_amount;
                }
				$print_str .=
                    "\r\n\r\n"
                    .append_chars("SALES COUNT","right",20," ")."P ".number_format($total_orders,2)."\r\n"
                    .append_chars("TOTAL SALES AMOUNT","right",20," ")."P ".number_format($sum_orders,2);
                var_dump($return_print_str);
                if (!empty($return_print_str)) {
                    echo json_encode(array("code"=>"<pre>$print_str</pre>"));
                }
            } catch (Exception $e) {

            }
        }
    }
    public function manager_report_view($report_title=null,$asJson=null)
    {
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/manager_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->helper('core/string_helper');

        // Get data for report header
        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
            );
        }
        $userdata = $this->session->userdata('user');

        $print_str = "\r\n\r\n";
        $wrap = wordwrap($branch['name'],35,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $print_str .= align_center($v,38," ")."\r\n";
        }
        // CONTENT
        // ---------------------------------------------------------------------//
        //
        if ($report_title == 'system_sales') {

        } elseif ($report_title == 'menu_sales') {

        } elseif ($report_title == 'fs-sales') {
            $daterange = $this->input->post('daterange');
            $server_id = $this->input->post('server_id');
            $void_included = $this->input->post('voided');

            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));

            $fs_sums = $this->manager_model->get_fs_daily_sums($date_from,$date_to,$server_id,($void_included == 'no' ? false : true));

            // echo $this->manager_model->db->last_query();
            $print_str .=
                align_center("FOOD SERVER REPORT",38)."\r\n";
            if (!empty($server_id) && !empty($fs_sums)) {
                $print_str .= append_chars("Employee",'right',18," ").$fs_sums[0]->waiter_id." - ".$fs_sums[0]->fname." ".$fs_sums[0]->lname."\r\n";
            } else {
                $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";
            }
            $print_str .= append_chars("Inactive Included",'right',18," ").($void_included == 'no' ? 'NO' : 'YES')."\r\n"
                .append_chars("Starting Date",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Date",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").date('Y-m-d H:i:s')."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n\r\n";

            $total_orders = $sum_orders = 0;
            foreach ($fs_sums as $value) {
                $print_str .=
                    append_chars($value->fname." ".$value->lname,'right',20," ")
                    .append_chars($value->total_orders,'right',8," ")
                    .append_chars(number_format($value->total_amount,2),'left',10," ")."\r\n";
                $total_orders += $value->total_orders;
                $sum_orders += $value->total_amount;
            }
            $print_str .=
                "\r\n\r\n"
                .append_chars("TOTAL SALES COUNT","right",20," ")."  ".number_format($total_orders)."\r\n"
                .append_chars("TOTAL SALES AMOUNT","right",20," ")."P ".number_format($sum_orders,2);

            // if (!empty($asJson)) {
            //     echo json_encode(array("code"=>"<pre>$print_str</pre>"));
            //     return false;
            // }

            // $filename = "report.txt";
            // $fp = fopen($filename, "w+");
            // fwrite($fp,$print_str);
            // fclose($fp);

            // $batfile = "print.bat";
            // $fh1 = fopen($batfile,'w+');
            // $root = dirname(BASEPATH);

            // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            // fclose($fh1);
            // session_write_close();
            // // exec($filename);
            // exec($batfile);
            // session_start();
            // unlink($filename);
            // unlink($batfile);
        } elseif ($report_title == 'hourly-sales') {

            // $date = date('Y-m-d',strtotime($this->input->post('date')));
            $date = $this->input->post('date');
            $dates = explode(" to ",$date);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            // var_dump($dates);
            // return false;
            $print_str .=
                align_center("HOURLY SALES REPORT",38)."\r\n";
            $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";

            $print_str .= append_chars("Inactive Included",'right',18," ")."NO\r\n"
                .append_chars("Starting Date",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Date",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").date('Y-m-d H:i:s')."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n\r\n";

            $total_cover = $total_check = $total_sales = 0;
            $counter = 1;
            $use_date = date('Y-m-d',strtotime($date_from));
            $date_to = date('Y-m-d',strtotime($date_to));

            while (true) {
                foreach(unserialize(TIMERANGES) as $k=>$v){
                    $net = $this->settings_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$use_date,$use_date);
                    // echo $this->settings_model->db->last_query()."<br/>";
                    // continue;
                    if (!empty($net)) {
                        $cover = (empty($net->sales_cover) ? 0 : $net->sales_cover);
                        $check = (empty($net->sales_check) ? 0 : $net->sales_check);
                        $total = (empty($net->sales_total) ? 0 : $net->sales_total);

                        $avg_cover = round((empty($cover) ? 0 : $total/$cover),2);
                        $avg_check = round((empty($check) ? 0 : $total/$check),2);

                        $print_str .= ($counter)." $use_date ".$v['FTIME']." - ".$v['TTIME']."\r\n"
                            .append_chars("Net Sales Total",'right',27," ").append_chars(number_format($total,2),'left',11," ")."\r\n"
                            .append_chars("Average $/Cover",'right',15," ").append_chars($cover,'left',11," ")
                                .append_chars(number_format($avg_cover,2),'left',12," ")."\r\n"
                            .append_chars("Average $/Check",'right',15," ").append_chars($check,'left',11," ")
                                .append_chars(number_format($avg_check,2),'left',12," ")."\r\n\r\n\r\n";

                        $total_cover += $cover;
                        $total_check += $check;
                        $total_sales += $total;
                    } else {
                        $print_str .= $counter." ".date('Y-m-d',strtotime($use_date))." ".$v['FTIME']." - ".$v['TTIME']."\r\n"
                            .append_chars("Net Sales Total",'right',27," ").append_chars("0.00",'left',11," ")."\r\n"
                            .append_chars("Average $/Cover",'right',15," ").append_chars("0",'left',11," ")
                                .append_chars("0.00",'left',12," ")."\r\n"
                            .append_chars("Average $/Check",'right',15," ").append_chars("0.00",'left',11," ")
                                .append_chars("0.00",'left',12," ")."\r\n\r\n\r\n";
                    }
                    $counter++;
                }
                if (date('Y-m-d',strtotime($use_date)) == date('Y-m-d',strtotime($date_to))) {break;}
                else {
                    $use_date = date('Y-m-d',strtotime($use_date." +1 day"));
                }
            }

            $t_avg_cover = round((empty($total_cover) ? 0 : $total_sales/$total_cover),2);
            $t_avg_check = round((empty($total_check) ? 0 : $total_sales/$total_cover),2);

            $print_str .= "TOTAL\r\n"
                .append_chars("Net Sales Total",'right',27," ").append_chars(number_format($total_sales,2),'left',11," ")."\r\n"
                .append_chars("Average $/Cover",'right',15," ").append_chars($total_cover,'left',11," ")
                    .append_chars(number_format($t_avg_cover,2),'left',12," ")."\r\n"
                .append_chars("Average $/Check",'right',15," ").append_chars($total_check,'left',11," ")
                    .append_chars(number_format($t_avg_check,2),'left',12," ")."\r\n\r\n";
        } elseif ($report_title == 'employee-sales') {

            $daterange = $this->input->post('daterange');
            $server_id = $this->input->post('server_id');
            //$void_included = $this->input->post('voided');

            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d')." 00:00" : date('Y-m-d H:i',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d H:i') : date('Y-m-d H:i',strtotime($dates[1])));

            if($dates[0] == $dates[1]){
                $date_from = date('Y-m-d',strtotime($dates[0]));
                $date_to = date('Y-m-d',strtotime($dates[1]));
            }

            $server = $this->manager_model->get_server_details($server_id);
            $print_str .=
            align_center("EMPLOYEE SALES REPORT",38)."\r\n";
            //if (!empty($server_id) && !empty($fs_sums)) {
                $print_str .= append_chars("Employee",'right',18," ").$server[0]->id."  ".$server[0]->fname." ".$server[0]->lname." - ".$server[0]->id."\r\n";
            // } else {
            //     $print_str .= append_chars("Employee",'right',18," ")."ALL\r\n";
            // }
            $print_str .= append_chars("Starting Datetime",'right',18," ").$date_from."\r\n"
                .append_chars("Ending Datetime",'right',18," ").$date_to."\r\n"
                .append_chars("Printed On",'right',18," ").date('Y-m-d H:i:s')."\r\n"
                .append_chars("Printed By",'right',18," ").$userdata['fname']." ".$userdata['lname']."\r\n"
                .append_chars("",'right',38,"-")."\r\n";

            $print_str .=
            align_center($server[0]->id."  ".$server[0]->fname." ".$server[0]->lname,38)."\r\n";

            $fs_sums = $this->manager_model->get_emp_daily_sums($date_from,$date_to,$server_id);
            // echo $this->db->last_query();

            if(!empty($server_id) && !empty($fs_sums)){
                $total_received = 0;
                $total_amount_received = 0;
                $get_disc = $this->manager_model->get_discount_all($date_from,$date_to,$server_id);
                $disc_total_amount = 0;
                $disc_total_count = 0;
                foreach($get_disc as $value){
                    $disc_total_count += $value->count_disc;
                    $disc_total_amount += $value->disc_amount;
                }

                $print_str .=
                    append_chars("\r\nCASHIER SALES TL",'right',20," ")
                    .append_chars($fs_sums[0]->total_orders,'right',8," ")
                    .append_chars(number_format($fs_sums[0]->total_amount+abs($disc_total_amount),2),'left',10," ")."\r\n";

                $print_str .= "\r\nPayment B/Down";
                $print_str .= "\r\nCash Payments";
                $print_str .= "\r\n=================";

                $cash_sales_sum = $this->manager_model->get_sales_sum_type($date_from,$date_to,$server_id,'cash');
                $print_str .=
                    append_chars("\r\nCash Sales",'right',20," ")
                    .append_chars($cash_sales_sum[0]->count_type,'right',8," ")
                    .append_chars(number_format($cash_sales_sum[0]->total_amount,2),'left',10," ")."\r\n";

                $print_str .= "-----------------";
                $print_str .=
                    append_chars("\r\nCASH TL",'right',20," ")
                    .append_chars($cash_sales_sum[0]->count_type,'right',8," ")
                    .append_chars(number_format($cash_sales_sum[0]->total_amount,2),'left',10," ")."\r\n";

                $print_str .= "\r\nCredit Cards";
                $print_str .= "\r\n=================";

                $credit_sales_sum = $this->manager_model->get_sales_sum_credit($date_from,$date_to,$server_id,'credit');
                $creadit_total_count = 0;
                $creadit_total_amount = 0;
                if(count($credit_sales_sum) > 0){
                    foreach($credit_sales_sum as $value){
                        $print_str .=
                            append_chars("\r\n".strtoupper($value->card_type),'right',20," ")
                            .append_chars($value->pay_count,'right',8," ")
                            .append_chars(number_format($value->total_amount,2),'left',10," ");

                        $creadit_total_count += $value->pay_count;
                        $creadit_total_amount += $value->total_amount;
                    }
                }else{
                    $print_str .= "\r\nNONE";
                }

                $print_str .= "\r\n-----------------";
                $print_str .=
                        append_chars("\r\nCredit Card TL",'right',20," ")
                        .append_chars($creadit_total_count,'right',8," ")
                        .append_chars(number_format($creadit_total_amount,2),'left',10," ")."\r\n";

                $print_str .= "\r\nGC Payments";
                $print_str .= "\r\n=================";

                $gc_sales_sum = $this->manager_model->get_sales_sum_gc($date_from,$date_to,$server_id,'gc');
                $gc_total_count = 0;
                $gc_total_amount = 0;
                if(count($gc_sales_sum) > 0){
                    foreach($gc_sales_sum as $value){
                        $print_str .=
                            append_chars("\r\n".strtoupper('GC '.$value->gc_amount),'right',20," ")
                            .append_chars($value->pay_count,'right',8," ")
                            .append_chars(number_format($value->total_amount,2),'left',10," ");

                        $gc_total_count += $value->pay_count;
                        $gc_total_amount += $value->total_amount;
                    }
                }else{
                    $print_str .= "\r\nNONE";
                }

                $print_str .= "\r\n-----------------";
                $print_str .=
                        append_chars("\r\nGC TL",'right',20," ")
                        .append_chars($gc_total_count,'right',8," ")
                        .append_chars(number_format($gc_total_amount,2),'left',10," ")."\r\n";

                $total_received = $cash_sales_sum[0]->count_type + $creadit_total_count + $gc_total_count;
                $total_amount_received = $cash_sales_sum[0]->total_amount + $creadit_total_amount + $gc_total_amount;

                $print_str .=
                        append_chars("\r\nTotal Received",'right',20," ")
                        .append_chars($total_received,'right',8," ")
                        .append_chars(number_format($total_amount_received+abs($disc_total_amount),2),'left',10," ")."\r\n";

                $print_str .=
                        append_chars("\r\nCITY DELIVERY",'right',20," ")
                        .append_chars(0,'right',8," ")
                        .append_chars(number_format(0,2),'left',10," ")."\r\n";

                $print_str .=
                        append_chars("\r\nIFC REDEEM",'right',20," ")
                        .append_chars(0,'right',8," ")
                        .append_chars(number_format(0,2),'left',10," ")."\r\n";

                $get_void = $this->manager_model->get_void_total_all($date_from,$date_to,$server_id);
                if(count($get_void) > 0){
                    $print_str .=
                        append_chars("\r\nManager Void",'right',20," ")
                        .append_chars($get_void[0]->total_orders,'right',8," ")
                        .append_chars(number_format($get_void[0]->total_amount*-1,2),'left',10," ")."\r\n";
                }else{
                    $print_str .=
                        append_chars("\r\nManager Void",'right',20," ")
                        .append_chars(0,'right',8," ")
                        .append_chars(number_format(0,2),'left',10," ")."\r\n";
                }

                // $get_open = $this->manager_model->get_open_total_all($date_from,$date_to,$server_id);
                // if(count($get_open) > 0){
                //     $print_str .=
                //         append_chars("Open Drawer",'right',18," ")
                //         .append_chars($get_open[0]->total_orders,'right',8," ")
                //         .append_chars(number_format($get_open[0]->total_amount,2),'left',10," ")."\r\n";
                // }else{
                    $print_str .=
                        append_chars("Open Drawer",'right',18," ")
                        .append_chars(0,'right',8," ")
                        .append_chars(number_format(0,2),'left',10," ")."\r\n";
                // }

                $get_open_void = $this->manager_model->get_open_voided_all($date_from,$date_to,$server_id);
                // echo $this->db->last_query();
                if(count($get_open_void) > 0){
                    $print_str .=
                        append_chars("Cancel Total",'right',18," ")
                        .append_chars($get_open_void[0]->total_orders,'right',8," ")
                        .append_chars(number_format($get_open_void[0]->total_amount,2),'left',10," ")."\r\n";
                }else{
                    $print_str .=
                        append_chars("Cancel Total",'right',18," ")
                        .append_chars(0,'right',8," ")
                        .append_chars(number_format(0,2),'left',10," ")."\r\n";
                }

                $print_str .= "\r\nDiscount BDown";
                $print_str .= "\r\n-----------------";

                
                $disc_total_count = 0;
                $disc_total_amount = 0;
                foreach($get_disc as $value){
                    $print_str .=
                        append_chars("\r\n".strtoupper($value->disc_rate."% ".$value->disc_code),'right',20," ")
                        .append_chars($value->count_disc,'right',8," ")
                        .append_chars(number_format($value->disc_amount*-1,2),'left',10," ");

                    $disc_total_count += $value->count_disc;
                    $disc_total_amount += $value->disc_amount;
                }

                $print_str .= "\r\n-----------------";
                $print_str .=
                        append_chars("\r\nTotal Discount",'right',20," ")
                        .append_chars($disc_total_count,'right',8," ")
                        .append_chars(number_format($disc_total_amount*-1,2),'left',10," ")."\r\n";

                $print_str .= "\r\n";
                $print_str .= "\r\n";

            }else{
                $print_str .=
                    "\r\n".align_center("NO SALES",38)."\r\n";
            }

            // if (!empty($asJson)) {
            //     echo json_encode(array("code"=>"$print_str"));
            //     return false;
            // }

            // $filename = "report.txt";
            // $fp = fopen($filename, "w+");
            // fwrite($fp,$print_str);
            // fclose($fp);

            // $batfile = "print.bat";
            // $fh1 = fopen($batfile,'w+');
            // $root = dirname(BASEPATH);

            // fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            // fclose($fh1);
            // session_write_close();
            // // exec($filename);
            // exec($batfile);
            // session_start();
            // unlink($filename);
            // unlink($batfile);
        }
        if (!empty($asJson)) {
            echo json_encode(array("code"=>"<pre>$print_str</pre>"));
            return false;
        }

        $filename = "report.txt";
        $fp = fopen($filename, "w+");
        fwrite($fp,$print_str);
        fclose($fp);

        $batfile = "print.bat";
        $fh1 = fopen($batfile,'w+');
        $root = dirname(BASEPATH);

        fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
        fclose($fh1);
        session_write_close();
        // exec($filename);
        exec($batfile);
        session_start();
        unlink($filename);
        unlink($batfile);
    }
    ///////////////////////
    ///TERITORYO NI JOMAR
    ///////////////////////
    public function print_system_sales_report($report_title=null,$asJson=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/manager_model');
        $this->load->model('core/site_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/setup_model');
        $this->load->helper('core/string_helper');
        #DITO YUNG HEADER
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            foreach ($branch_details as $bv) {
                $branch = array(
                    'id' => $bv->branch_id,
                    'res_id' => $bv->res_id,
                    'branch_code' => $bv->branch_code,
                    'name' => $bv->branch_name,
                    'branch_desc' => $bv->branch_desc,
                    'contact_no' => $bv->contact_no,
                    'delivery_no' => $bv->delivery_no,
                    'address' => $bv->address,
                    'base_location' => $bv->base_location,
                    'currency' => $bv->currency,
                    'inactive' => $bv->inactive,
                    'tin' => $bv->tin,
                    'machine_no' => $bv->machine_no,
                    'bir' => $bv->bir,
                    'permit_no' => $bv->permit_no,
                    'serial' => $bv->serial,
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $userdata = $this->session->userdata('user');

            $print_str = "\r\n\r\n";
            $wrap = wordwrap($branch['name'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,38," ")."\r\n";
            }
        #DITO NA YUNG SA REPORT
            $title_name = "System Sales Report";
            $date = '2014-10-29 14-21-36';
            $time = $this->site_model->get_db_now();

            $daterange = date2Sql('11/01/2014');
            // $daterange = $this->input->post('daterange');
            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));

            $old_gt_amnt = 0;
            $resultx = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($date));
            // echo $this->cashier_model->db->last_query();
            // echo var_dump($resultx);
            // return false;
            foreach ($resultx as $res) {
                $date_from = $res->scope_from;
                $date_to = $res->scope_to;
                $old_gt_amnt = $res->old_total;
                $grand_total = $res->grand_total;
                $read_date = $res->read_date;
                break;
            }
            // $date_to = ;

            // $dates = explode(" to ",$daterange);
            // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));

            $date_param = (is_null($date) ? date('Y-m-d') : $date);
            $terminal = $this->input->post('terminal');
            $cashier = $this->input->post('cashier');

            if (!empty($cashier)){
                $server = $this->manager_model->get_server_details($cashier);
                $cashier_name = $server[0]->fname.' '.$server[0]->lname;
            }else{
                $cashier_name = 'All Cashier';
            }

            // $where_array = array('date(datetime)' => $date_param,'trans_sales.paid'=>1,'trans_sales.type_id'=>SALES_TRANS);
            $where_array = array('trans_sales.paid'=>1,'trans_sales.type_id'=>SALES_TRANS,'trans_sales.inactive'=>0);
            // $where_array = array('trans_sales.paid'=>1,'trans_sales.type_id'=>SALES_TRANS,'trans_sales.user_id'=>$cashier,'trans_sales.terminal_id'=>$terminal);
            if($cashier != '')
                // $where_array = array_push($where_array,'trans_sales.user_id'=>$cashier);
                $where_array['trans_sales.waiter_id'] = $cashier;
            if($terminal != '')
                // $where_array = array_push($where_array,'trans_sales.terminal_id'=>$terminal);
                $where_array['trans_sales.terminal_id'] = $terminal;

            // $where_array = array('trans_sales.paid'=>1,'trans_sales.type_id'=>SALES_TRANS);
            // $print_str .= $this->append_chars(substrwords(var_dump($where_array),18,""),"right",17," ");
            if (!empty($terminal)){
                $terminal_name = $terminal;
            }else{
                $terminal_name = 'All Terminal';
            }

            $discount_list = $this->settings_model->get_discount_details(null,null,$date_from,$date_to,true);
            $gtotal_dsc_qty = $gtotal_dsc_amnt = 0;
            foreach($discount_list as $dsc){
                $gtotal_dsc_qty +=$dsc->sdisc_count;
                $gtotal_dsc_amnt +=$dsc->sdisc_total;
            }

            $void_bfore_sbmt_qty = $avg_void_b4_sbmt_qty = $void_after_sbmt_qty = $avg_void_aft_sub_qty = 0;
            $void_bfore_sbmt_amt = $avg_void_b4_sbmt_amt = $void_after_sbmt_amt = $avg_void_aft_sub_amt = 0;


            $before_date_range_voided = $this->settings_model->get_voided_transactions(null,$date_from,$date_to,false);
            $bdrv = $before_date_range_voided[0];
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),1800,""),"right",23," ");
            $after_date_range_voided = $this->settings_model->get_voided_transactions(null,$date_from,$date_to,true);
            $adrv = $after_date_range_voided[0];

            $bdrv_amnt = (is_null($bdrv->void_amnt)? 0:$bdrv->void_amnt);
            $bdrv_qty = (is_null($bdrv->void_count)? 0:$bdrv->void_count);

            $adrv_amnt = (is_null($adrv->void_amnt)? 0:$adrv->void_amnt);
            $adrv_qty = (is_null($adrv->void_count)? 0:$adrv->void_count);

            if($bdrv_amnt == 0 || $bdrv_qty == 0)
                $ave_void_b4 = 0;
            else
                $ave_void_b4 = $bdrv->void_amnt/$bdrv->void_count;

            if($adrv_amnt == 0 || $adrv_qty == 0)
                $ave_void_af = 0;
            else
                $ave_void_af = $adrv->void_amnt/$adrv->void_count;

            $print_str .= $this->align_center($title_name,38," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Employee',18,""),"right",17," ")
                         .$this->append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('PC',18,""),"right",17," ")
                         .$this->append_chars($terminal_name,"left",8," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Read Date',18,""),"right",17," ")
                         .$this->append_chars(sql2Date($read_date),"left",8," ")."\r\n";             
            $print_str .= $this->append_chars(substrwords('Date From',18,""),"right",17," ")
                         .$this->append_chars(sql2DateTime($date_from),"left",8," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Date To',18,""),"right",17," ")
                         .$this->append_chars(sql2DateTime($date_to),"left",8," ")."\r\n";             
            $print_str .= $this->append_chars(substrwords('Printed on',18,""),"right",17," ")
                         .$this->append_chars(date2SqlDateTime($time),"left",8," ")."\r\n";
            $userdata = $this->session->userdata('user');
            $print_str .= $this->append_chars(substrwords('Printed by',18,""),"right",17," ")
                         .$this->append_chars($userdata['fname'].' '.$userdata['lname'],"left",8," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            ////////////////////////////////////////////////////////////////////////////////////////////////////
            $gross_sales = $this->settings_model->get_gross_sales(null,$where_array,$date_from,$date_to,true);
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),1800,""),"right",23," ");
            $gs = $gross_sales[0];
            $vat = $gs->gross_sales * .12;
            $net_sales = $gs->gross_sales - $vat;

            // $print_str .= $this->append_chars(substrwords('Net Sales',18,""),"right",18," ").$this->align_center("asdasdads",5," ")
            //              .$this->append_chars($cashier_name,"left",8," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Net Sales',18,""),"right",23," ")
                         .$this->append_chars(number_format($net_sales,2),"left",13," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('12% VAT',18,""),"right",23," ")
                         .$this->append_chars(number_format($vat,2),"left",13," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Local Tax',18,""),"right",23," ")
                         .$this->append_chars(number_format(0,2),"left",13," ")."\r\n";

            $print_str .= "-----------------"."\r\n";
            $gross_sales = $gs->gross_sales;
            $print_str .= $this->append_chars(substrwords('Gross Sales',18,""),"right",23," ")
                         .$this->append_chars(number_format($gross_sales+abs($gtotal_dsc_amnt),2),"left",13," ")."\r\n\r\n";

            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $carry_over_qty = $carry_over_amnt = 0;
            $print_str .= $this->append_chars(substrwords('Carry Over',18,""),"right",18," ").$this->align_center($carry_over_qty,5," ")
                         .$this->append_chars(number_format($carry_over_amnt,2),"left",13," ")."\r\n";
            $deposit_total_qty = $deposit_total_amnt = 0;
            $print_str .= $this->append_chars(substrwords('Deposit Total',18,""),"right",18," ").$this->align_center($deposit_total_qty,5," ")
                         .$this->append_chars(number_format($deposit_total_amnt,2),"left",13," ")."\r\n";
            $begun_total_qty = $begun_total_amnt = 0;
            $begun_total_amnt = $gs->gross_sales;
            $begun_total_qty = $gs->qty;
            $print_str .= $this->append_chars(substrwords('Begun Total',18,""),"right",18," ").$this->align_center($begun_total_qty,5," ")
                         .$this->append_chars(number_format($begun_total_amnt,2),"left",13," ")."\r\n";
            $paid_total_amnt = $gs->paid_total;
            $paid_total_qty = $gs->qty;
            $print_str .= $this->append_chars(substrwords('Paid Total',18,""),"right",18," ").$this->align_center($paid_total_qty,5," ")
                         .$this->append_chars(number_format($paid_total_amnt,2),"left",13," ")."\r\n";
            $print_str .= "-----------------"."\r\n";
            $outstanding_total_qty = $begun_total_qty - $paid_total_qty;
            $outstanding_total_amnt = $begun_total_amnt - $paid_total_amnt;
            $print_str .= $this->append_chars(substrwords('Outstanding',18,""),"right",18," ").$this->align_center($outstanding_total_qty,5," ")
                         .$this->append_chars(number_format($outstanding_total_amnt,2),"left",13," ")."\r\n\r\n";

            $print_str .= $this->append_chars(substrwords('Trans. Count',18,""),"right",23," ");
            $trans_type_list = $this->settings_model->get_trans_types(null,$date_from,$date_to,true);
            $print_str .= "\r\n";
            $tc_total_qty = $tc_total_amnt = 0;
            foreach($trans_type_list as $ttype){
                $print_str .= $this->append_chars(substrwords($ttype->type,18,""),"right",18," ").$this->align_center($ttype->type_count,5," ")
                         .$this->append_chars(number_format($ttype->type_total,2),"left",13," ")."\r\n";
                $tc_total_qty +=$ttype->type_count;
                $tc_total_amnt +=$ttype->type_total;
            }
            $tc_asdasdasd = 0;
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('TC Total',18,""),"right",18," ").$this->align_center($tc_total_qty,5," ")
                         .$this->append_chars(number_format($tc_total_amnt,2),"left",13," ")."\r\n\r\n";
            $tc_asdasdasd = $tc_total_amnt;
            // if(is_null($tc_total_amnt) || is_null($tc_total_qty)){
            if($tc_total_amnt == 0 || $tc_total_qty == 0){
                $tc_chck = 0;
            }else{
                $tc_chck =$tc_total_amnt/$tc_total_qty;
            }
            $print_str .= $this->append_chars(substrwords('Average Check',18,""),"right",18," ").$this->align_center('',5," ")
                         .$this->append_chars(number_format($tc_chck,2),"left",13," ")."\r\n\r\n";

            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $print_str .= $this->append_chars(substrwords('Guest Count',18,""),"right",23," ");
            $_ave_list_a = array('Dine','Take Out','Delivery');
            $_ave_list_b = array(0,0,0);
            $_ave_list_c = array(0,0,0);
            $print_str .= "\r\n";
            $ave_total_qty = $ave_total_amnt = 0;
            // for($i=0;$i<count($_ave_list_a);$i++){
            //     $print_str .= $this->append_chars(substrwords($_ave_list_a[$i],18,""),"right",18," ").$this->align_center($_ave_list_b[$i],5," ")
            //              .$this->append_chars(number_format($_ave_list_c[$i],2),"left",13," ")."\r\n";
            //     $ave_total_qty += $_ave_list_b[$i];
            //     $ave_total_amnt += $_ave_list_c[$i];
            // }
            foreach($trans_type_list as $ttype){
                $print_str .= $this->append_chars(substrwords($ttype->type,18,""),"right",18," ").$this->align_center(0,5," ")
                         .$this->append_chars(number_format(0,2),"left",13," ")."\r\n";
                $tc_total_qty +=$ttype->type_count;
                $tc_total_amnt +=$ttype->type_total;
                // $tc_total_qty += $_tc_list_b[$i];
                // $tc_total_amnt += $_tc_list_c[$i];
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('Average Cover',18,""),"right",18," ").$this->align_center($ave_total_qty,5," ")
                         .$this->append_chars(number_format($ave_total_amnt,2),"left",13," ")."\r\n\r\n";
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $print_str .= $this->append_chars(substrwords('Other Collection',18,""),"right",23," ");
            $_col_list_a = array('Tips');
            $_col_list_b = array(0);
            $_col_list_c = array(0);
            $print_str .= "\r\n";
            $col_total_qty = $col_total_amnt = 0;
            for($i=0;$i<count($_col_list_a);$i++){
                $print_str .= $this->append_chars(substrwords($_col_list_a[$i],18,""),"right",18," ").$this->align_center($_col_list_b[$i],5," ")
                         .$this->append_chars(number_format($_col_list_c[$i],2),"left",13," ")."\r\n";
                $col_total_qty += $_col_list_b[$i];
                $col_total_amnt += $_col_list_c[$i];
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('Ttl Collection',18,""),"right",18," ").$this->align_center($col_total_qty,5," ")
                         .$this->append_chars(number_format($col_total_amnt,2),"left",13," ")."\r\n\r\n";
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $print_str .= $this->append_chars(substrwords('Major Categories',18,""),"right",23," ");
            $print_str .= "\r\n";

            $categories = $this->menu_model->get_menu_categories(null,true);
            $cats = array();
            foreach ($categories as $catv) {
                $cats[] = $catv;
            }
            $category_list = $this->settings_model->get_category_list(null,$where_array,$date_from,$date_to,true);
            // echo $this->settings_model->db->last_query();
            // return false;
            $catl = array();
            foreach($category_list as $v){
                $catl[$v->menu_cat_id] = array('name'=>$v->menu_cat_name,'total_amnt'=>$v->trans_total_amnt,'total_qty'=>$v->cat_qty);
            }
            // echo $this->db->last_query();
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),500,""),"right",500," ");
            // echo var_dump($catl);
            // return false;
            $gtotal_cat_qty = $gtotal_total_amount = 0;
            $menu_gtotal_qty = $menu_gtotal_amnt = 0;
            foreach ($categories as $catv) {
                if(isset($catl[$catv->menu_cat_id])){
                    $catr = $catl[$catv->menu_cat_id];
                    $print_str .= $this->append_chars(substrwords($catr['name'],18,""),"right",18," ").$this->align_center($catr['total_qty'],5," ")
                             .$this->append_chars(number_format($catr['total_amnt'],2),"left",13," ")."\r\n";                                        

                    $menu_gtotal_qty += $catr['total_qty'];
                    $menu_gtotal_amnt += $catr['total_amnt'];

                }
                else{
                    $print_str .= $this->append_chars(substrwords($catv->menu_cat_name,18,""),"right",18," ").$this->align_center(0,5," ")
                             .$this->append_chars(number_format(0,2),"left",13," ")."\r\n";                                        
                }

            }
                
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('Total',18,""),"right",18," ").$this->align_center($menu_gtotal_qty,5," ")
                         .$this->append_chars(number_format($menu_gtotal_amnt,2),"left",13," ")."\r\n\r\n";
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
            $loan_qty = $loan_amnt = 0;
            $print_str .= $this->append_chars(substrwords('Loan',18,""),"right",18," ").$this->align_center($loan_qty,5," ")
                         .$this->append_chars(number_format($loan_amnt,2),"left",13," ")."\r\n";
            $print_str .= "-----------------"."\r\n";
            $cash_sales_qty = $cash_sales_amnt = 0;
            $cash_sales_qty = $gs->qty;
            $cash_sales_amnt = $gs->gross_sales;
            $print_str .= $this->append_chars(substrwords('Cash Sales',18,""),"right",18," ").$this->align_center($cash_sales_qty,5," ")
                         .$this->append_chars(number_format($cash_sales_amnt,2),"left",13," ")."\r\n";
            $pickup_qty = $pickup_amnt = 0;
            $print_str .= $this->append_chars(substrwords('Pickup',18,""),"right",18," ").$this->align_center($pickup_qty,5," ")
                         .$this->append_chars(number_format($pickup_amnt,2),"left",13," ")."\r\n";
            $cash_drawer_qty = $cash_drawer_amnt = 0;
            $cash_drawer_qty += $cash_sales_qty + $pickup_qty;
            $cash_drawer_amnt += $cash_sales_amnt + $pickup_amnt;
            $print_str .= $this->append_chars(substrwords('Cash in Drawer',18,""),"right",18," ").$this->align_center($cash_drawer_qty,5," ")
                         .$this->append_chars(number_format($cash_drawer_amnt,2),"left",13," ")."\r\n\r\n";

            $print_str .= $this->append_chars(substrwords('Payment Details',18,""),"right",23," ");
            $category_list = $this->settings_model->get_payment_details(null,$where_array,$date_from,$date_to,true);
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),1800,""),"right",23," ");
            // echo $this->settings_model->db->last_query();
            // echo var_dump($category_list);
            // return false;
            $print_str .= "\r\n";
            $gtotal_ptype_qty = $gtotal_ptype_amnt = 0;
            $pays = array();
            foreach($category_list as $cat){
                $change = $cat->amount - $cat->to_pay;
                if($change < 0)
                    $change = 0;

                if(isset($pays[$cat->payment_type])){
                    $pays[$cat->payment_type]['amount'] += $cat->amount - $change;
                    $pays[$cat->payment_type]['qty'] += 1;

                }
                else{
                    $pays[$cat->payment_type] = array('name'=>$cat->payment_type,'amount'=>$cat->amount,'qty'=>1);
                }
            }
            $amount_change = 0;
            foreach($category_list as $cat){
                $change = $cat->amount - $cat->to_pay;
                if($change < 0)
                    $change = 0;
                $amount_change += $change;
            }
            $amount_total = 0;
            foreach($category_list as $cat){
                $amount_total += $cat->amount;
            }

            foreach ($pays as $type => $opt) {
                $print_str .= $this->append_chars(substrwords($type,18,""),"right",18," ").$this->align_center($opt['qty'],5," ")
                         .$this->append_chars(number_format($opt['amount'],2),"left",13," ")."\r\n";
                $gtotal_ptype_qty += $opt['qty'];
            }
            $gtotal_ptype_amnt =$amount_total - $amount_change;



            // echo var_dump($pays);
            // return false;
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('Total Payment',18,""),"right",18," ").$this->align_center($gtotal_ptype_qty,5," ")
                         .$this->append_chars(number_format($gtotal_ptype_amnt,2),"left",13," ")."\r\n\r\n";

            $print_str .= $this->append_chars(substrwords('Discount Details',18,""),"right",23," ");
            $discount_list = $this->settings_model->get_discount_details(null,null,$date_from,$date_to,true);
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),5000,""),"right",23," ");
            $print_str .= "\r\n";
            $gtotal_dsc_qty = $gtotal_dsc_amnt = 0;
            foreach($discount_list as $dsc){
                $print_str .= $this->append_chars(substrwords($dsc->disc_code,18,""),"right",18," ").$this->align_center($dsc->sdisc_count,5," ")
                         .$this->append_chars(number_format($dsc->sdisc_total,2),"left",13," ")."\r\n";
                $gtotal_dsc_qty +=$dsc->sdisc_count;
                $gtotal_dsc_amnt +=$dsc->sdisc_total;
            }
            $print_str .= "-----------------"."\r\n";
            $print_str .= $this->append_chars(substrwords('Total Discount',18,""),"right",18," ").$this->align_center($gtotal_dsc_qty,5," ")
                         .$this->append_chars(number_format($gtotal_dsc_amnt,2),"left",13," ")."\r\n\r\n";

            $print_str .= $this->append_chars(substrwords('Trans Typ Analys',18,""),"right",23," ");
            $print_str .= "\r\n";
            $print_str .= "-----------------"."\r\n";
            // $_tc_list_a = array('Dine','Take Out','Delivery','Finish Waste');
            // $_tc_list_b = array($gs->qty,0,0,0);
            // $_tc_list_c = array($gs->gross_sales,0,0,0);
            // $print_str .= "\r\n";
            $tc_total_qty = $tc_total_amnt = 0;
            // for($i=0;$i<count($_tc_list_a);$i++){
            //     $print_str .= $this->append_chars(substrwords($_tc_list_a[$i],18,""),"right",18," ").$this->align_center($_tc_list_b[$i],5," ")
            //              .$this->append_chars(number_format($_tc_list_c[$i],2),"left",13," ")."\r\n";
            //     $print_str .= $this->append_chars(substrwords($_tc_list_a[$i].' %',18,""),"right",18," ").$this->align_center('',5," ")
            //              .$this->append_chars(number_format($_tc_list_c[$i],2),"left",13," ")."\r\n";
            //     $print_str .= "\r\n";
            // }
            foreach($trans_type_list as $ttype){
                // $print_str .= $this->append_chars(substrwords($ttype->type,18,""),"right",18," ").$this->align_center($ttype->type_count,5," ")
                //          .$this->append_chars(number_format($ttype->type_total,2),"left",13," ")."\r\n";
                // $tc_total_qty +=$ttype->type_count;
                // $tc_total_amnt +=$ttype->type_total;
                // $tc_total_qty += $_tc_list_b[$i];
                // $tc_total_amnt += $_tc_list_c[$i];
                    if($ttype->type_count == 0 || $ttype->type_total == 0)
                    $avg = 0;
                    else
                    // $avg = $ttype->type_total/$ttype->type_count;
                    $avg = ($ttype->type_total/$tc_asdasdasd)*100;
                    $print_str .= $this->append_chars(substrwords($ttype->type,18,""),"right",18," ").$this->align_center($ttype->type_count,5," ")
                             .$this->append_chars(number_format($ttype->type_total,2),"left",13," ")."\r\n";
                    $print_str .= $this->append_chars(substrwords($ttype->type.' %',18,""),"right",18," ").$this->align_center('',5," ")
                             .$this->append_chars(number_format($avg,2),"left",13," ")."\r\n";
                    $print_str .= "\r\n";
            }
            

            $print_str .= $this->append_chars(substrwords('Void Before Subt',18,""),"right",18," ").$this->align_center($bdrv_qty,5," ")
                         .$this->append_chars(number_format($bdrv_amnt,2),"left",13," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Avg Void B4 Subt',18,""),"right",18," ").$this->align_center('',5," ")
                         .$this->append_chars(number_format($ave_void_b4,2),"left",13," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Void After Subt',18,""),"right",18," ").$this->align_center($adrv_qty,5," ")
                         .$this->append_chars(number_format($adrv_amnt,2),"left",13," ")."\r\n";
            $print_str .= $this->append_chars(substrwords('Avg Void Aft Sub',18,""),"right",18," ").$this->align_center('',5," ")
                         .$this->append_chars(number_format($ave_void_af,2),"left",13," ")."\r\n";
            $print_str .= "-----------------"."\r\n\r\n";

            $print_str .= "================="."\r\n";
            $print_str .= $this->append_chars(substrwords('NEW GT',18,""),"right",18," ").$this->align_center('',5," ")
                         // .$this->append_chars(number_format($gross_sales,2),"left",13," ")."\r\n";
                         .$this->append_chars(number_format($grand_total,2),"left",13," ")."\r\n";
            

            // echo $this->db->last_query();
            // $old_gt = $this->settings_model->get_gross_sales(null,$where_array,$date_from,$date_to,false);
            // $gs = $old_gt[0];
            // if($gs->gross_sales != 0)
            //     $old_gt_amnt = $gs->gross_sales;
            // else
            //     $old_gt_amnt = 0;
            // $print_str .= $this->append_chars(substrwords($this->db->last_query(),1800,""),"right",23," ");
            $print_str .= $this->append_chars(substrwords('OLD GT',18,""),"right",18," ").$this->align_center('',5," ")
                         .$this->append_chars(number_format($old_gt_amnt,2),"left",13," ")."\r\n";
            $print_str .= "================="."\r\n\r\n";

        #DITO YUNG MAGPRINT
            if ($asJson) {
                echo json_encode(array("code"=>"<pre>$print_str</pre>"));
                return false;
            }

            $filename = "report.txt";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);

            $batfile = "print.bat";
            $fh1 = fopen($batfile,'w+');
            $root = dirname(BASEPATH);

            fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            fclose($fh1);
            session_write_close();
            exec($filename);
            // exec($batfile);
            session_start();
            unlink($filename);
            unlink($batfile);
            echo json_encode(array("error"=>''));
    }
    ///////////////////////
    ///TERITORYO NI REY
    ///////////////////////
    public function print_menu_sales_report($report_title=null,$asJson=false){
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/manager_model');
        $this->load->model('core/site_model');
        $this->load->model('dine/settings_model');
        $this->load->model('dine/setup_model');
        $this->load->helper('core/string_helper');
        #DITO YUNG HEADER
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            foreach ($branch_details as $bv) {
                $branch = array(
                    'id' => $bv->branch_id,
                    'res_id' => $bv->res_id,
                    'branch_code' => $bv->branch_code,
                    'name' => $bv->branch_name,
                    'branch_desc' => $bv->branch_desc,
                    'contact_no' => $bv->contact_no,
                    'delivery_no' => $bv->delivery_no,
                    'address' => $bv->address,
                    'base_location' => $bv->base_location,
                    'currency' => $bv->currency,
                    'inactive' => $bv->inactive,
                    'tin' => $bv->tin,
                    'machine_no' => $bv->machine_no,
                    'bir' => $bv->bir,
                    'permit_no' => $bv->permit_no,
                    'serial' => $bv->serial,
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $userdata = $this->session->userdata('user');

            $print_str = "\r\n\r\n";
            $wrap = wordwrap($branch['name'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,38," ")."\r\n";
            }
        #DITO NA YUNG SA REPORT
            $time = $this->site_model->get_db_now();
            $daterange = $this->input->post('daterange');
            $dates = explode(" to ",$daterange);
            $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
            $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
            $args = array();
            $args['type_id'] = 10;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args["DATE(trans_sales.datetime) BETWEEN DATE('".$date_from."') AND DATE('".$date_to."')"] = array('use'=>'where','val'=>null,'third'=>false);

            if($this->input->post('user_id')){
                $args['trans_sales.user_id'] = $this->input->post('user_id');
            }

            $sales_menus = $this->cashier_model->get_menu_sales(null,$args);
			$user = $this->session->userdata('user');
            $emp = 'ALL';
            if($this->input->post('user_id')){
                $server = $this->manager_model->get_server_details($this->input->post('user_id'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
            }


            $title_name = "Menu Sales Report";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= "\r\n";
            $print_str .= append_chars('Item','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Time Period','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Terminal','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Employee','right',14," ").append_chars($emp,'right',19," ")."\r\n";
            // $print_str .= append_chars('Transaction Type','right',19," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Sales Type','right',14," ").append_chars('ALL','right',19," ")."\r\n";
            $print_str .= append_chars('Date Range','right',14," ").append_chars($date_from.' - '.$date_to,'right',19," ")."\r\n";
            $print_str .= append_chars('Printed On','right',14," ").append_chars(date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',14," ").append_chars($user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            if(count($sales_menus) > 0){
                $oaQty = 0;
                $oaVal = 0;
                $menus = array();
                foreach ($sales_menus as $res) {
                    $oaQty += $res->total_qty;
                    $oaVal += $res->total_amount;
                    if(!isset($menus[$res->menu_id])){
                        $menus[$res->menu_id] = array('name'=>$res->menu_name,'qty'=>$res->total_qty,'amount'=>$res->total_amount);
                    }
                    else{
                        $row = $menus[$res->menu_id];
                        $row['qty'] += $res->total_qty;
                        $row['amount'] += $res->total_amount;
                        $menus[$res->menu_id] = $row;
                    }
                    $sales_discs[$res->sales_id] = $res->sales_id;
                }

                foreach ($menus as $menu_id => $res) {
                    $print_str .=
                        append_chars($res['name'],'right',18," ")
                        .append_chars(num($res['qty']),'right',10," ")
                        .append_chars(num( ($res['qty'] / $oaQty) * 100).'%','left',10," ")."\r\n";
                    $print_str .=
                        append_chars(null,'right',18," ")
                        .append_chars(num($res['amount']),'right',10," ")
                        .append_chars(num( ($res['amount'] / $oaVal) * 100).'%','left',10," ")."\r\n";
                }
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('TOTAL SALES',"right",18," ")
                             .append_chars(num($oaQty),'right',10," ")
                             .append_chars(num($oaVal),"left",10," ")."\r\n";

                $slsd = array();

                foreach ($sales_discs as $sids => $val) {
                    $slsd[] = $sids;
                }

                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$slsd));
                $items_disc = 0;
                foreach ($sales_discs as $dsc) {
                    $items_disc += $dsc->amount;
                }

                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($items_disc),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL OTHER DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num($oaVal),"left",10," ")."\r\n";

            }
            else{
                $print_str .= align_center('NO Sales',38," ")."\r\n";
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars('TOTAL SALES',"right",18," ")
                             .append_chars(num(0),'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL ITEMS DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL OTHER DISC',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
                $print_str .= append_chars('TOTAL NET SALES',"right",18," ")
                             .append_chars(null,'right',10," ")
                             .append_chars(num(0),"left",10," ")."\r\n";
            }

        #DITO YUNG MAGPRINT
            if ($asJson) {
                echo json_encode(array("code"=>"<pre>$print_str</pre>"));
                return false;
            }

            $filename = "report.txt";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);

            $batfile = "print.bat";
            $fh1 = fopen($batfile,'w+');
            $root = dirname(BASEPATH);

            fwrite($fh1, "NOTEPAD /P \"".realpath($root."/".$filename)."\"");
            fclose($fh1);
            session_write_close();
            // exec($filename);
            exec($batfile);
            session_start();
            unlink($filename);
            unlink($batfile);
            echo json_encode(array("error"=>''));
    }
}