<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/prints.php");
class Reads extends Prints {
	public function __construct(){
		parent::__construct();
		$this->load->model('dine/cashier_model');
        $this->load->helper('core/string_helper');
        $this->load->helper('dine/login_helper');
        $this->load->model('site/site_model');
    }
    public function manual_send_to_rob(){
        $data = $this->syter->spawn('send_to_rob');
        $files = $this->cashier_model->get_rob_files();
        $data['code'] = robFiles($files);
        $this->load->view('page',$data);
    }
    public function send_to_rob_man($id){
        $file = $this->cashier_model->get_rob_files(null,$id);
        foreach ($file as $res) {
            $reads = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($res->date_created));
            foreach ($reads as $red) {
                $unsent_id = $red->id;
            }
            $rob = $this->send_to_rob($unsent_id);
            if($rob['error'] == ""){
                site_alert("File:".$rob['file']." Sales File successfully sent to RLC server","success");
            }
            else{
                site_alert($rob['error'],"error");
            }
       }
       // redirect(base_url()."reads/manual_send_to_rob",'refresh');
       redirect(base_url()."manager",'refresh');
    }
	public function go_auto_zread(){
		$data = $this->syter->spawn(null,false);
		$data['code'] = makeZreadAutoPage();
		$data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css');
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js','js/virtual_keyboard.js');
		$data['load_js'] = 'site/login';
		$data['use_js'] = 'autoZreadJs';
		$this->load->view('login',$data);
	}
	public function auto_zread(){
        $tries = 0;
        if($this->session->userdata('rob_sent')){
            $tries = $this->session->userdata('rob_sent');
        }
        $tries += 1;
		$time = $this->site_model->get_db_now();
		
        $txt ="";
        // echo var_dump($unsent);
        $rlc = $this->cashier_model->get_rob_path();
        $path =  $rlc->rob_path;
        if($path != ""){
            $unsent = $this->cashier_model->get_unsent_rob_files();
            if(count($unsent) > 0){
               foreach ($unsent as $res) {
                    
                    $reads = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($res->date_created));
                    foreach ($reads as $red) {
                        $unsent_id = $red->id;
                    }
                    $rob = $this->send_to_rob($unsent_id,false);
                    if($rob['error'] == ""){
                        $txt .= "File:".$rob['file']." Sales File successfully sent to RLC server <br>";
                    }
                    else{
                        $txt .= $rob['error']."<br>";
                    }
               }
            }
        }

        $last_zread = $this->cashier_model->get_last_z_read(Z_READ,date2Sql($time));
		$last_read_date = $last_zread[0]->read_date;
		$range = createDateRangeArray($last_read_date,date2Sql($time));
		
		if(date('Y-m-d',strtotime($time. "-1 days")) != $range[0]){
	        if(count($range) >= 3){
		        // echo "<pre>Processing Recent End of Day Please wait...</pre>";
		        $open_time = '6:00 AM';
		        $close_time = '5:00 AM';
		        $ctr = 1;
		        foreach ($range as $date) {
		        	if(date2Sql($last_read_date) != date2Sql($date) && date2Sql($time) != date2Sql($date)){
		        		$read_date = $date;
		        		if($ctr == 1){
		        			$start = $last_zread[0]->scope_to;
			        		$plus = date('Y-m-d',strtotime($start . "+1 days"));
			        		$end = date2SqlDateTime($plus." ".$close_time);
		        		}
		        		else{
			        		$start = date2SqlDateTime($date." ".$open_time);
			        		$plus = date('Y-m-d',strtotime($start . "+1 days"));
			        		$end = date2SqlDateTime($plus." ".$close_time);
		        		}
                        $ctr++;
                        
                        $zread_id = $this->go_zread($asJson=false,$start,$end,$read_date);
                        if($zread_id){
                            $txt .= "Z Read for ".$read_date." successfully processed. <br>";
                        }
                        if(MALL_ENABLED){
    				        if(MALL == "robinsons"){
                                $rob = $this->send_to_rob($zread_id);
        				        if($rob['error'] == ""){
        				            $txt .= "File:".$rob['file']." Sales File successfully sent to RLC server <br>";
        				        }
        				        else{
        				            $txt .= $rob['error']."<br>";
        				        }
                            }
                            else if(MALL == "ortigas"){
                                
                            }
                        }#####################
                        ### MALL END
                        ######################    
		        		// break;
		        	}	
		        }        	
			}
        }
        $txt .= "Redirecting...<br>";
        // sleep(10);
        echo $txt;
        $this->session->set_userdata('rob_sent',$tries);
        redirect(base_url()."site/login",'refresh');
	}
    public function manual_zread(){
        $start = '2015-06-10 06:13:35';
        $from = '2015-06-10 23:36:24';
        $read_date = '2015-06-10';
        $this->go_zread(false,$start,$from,$read_date);
    }
    public function manual_send_to_rob_hihi(){
        // $zread_id = 2;
        // $zread_id = 7;
        $zread_id = 8;
        $this->send_to_rob($zread_id);
    }
    public function manual_xread(){
        $this->load->model('dine/clock_model');
        $in = '2015-06-07 15:29:27';
        $out = '2015-06-07 23:42:04';
        $read_date = '2015-06-07'; 
        $shift_id = 14;
        $user_id = 50;
        $read_details = array(
            'read_type' => X_READ,
            'read_date' => $read_date,
            'user_id'   => $user_id,
            'scope_from'=> $in,
            'scope_to'  => $out
        );
        $id = $this->cashier_model->add_read_details($read_details);
        $this->clock_model->update_clockout(array('xread_id'=>$id,'check_out'=>$out),$shift_id);
    }
	public function go_zread($asJson=true,$start=null,$end=null,$read_date=null){
        $date_from = $start;
        $date_to = $end;
        $args =  array(
                    'trans_sales.datetime >=' => $date_from,
                    'trans_sales.datetime <=' => $date_to,
                    'trans_sales.inactive' => 0,
                    'trans_sales.type_id' => SALES_TRANS,
                    "trans_sales.trans_ref  IS NOT NULL" => array('use'=>'where','val'=>null,'third'=>false)
                );
        if($date_from == null){
            unset($args['trans_sales.datetime >=']);
        }
        $orders = $this->cashier_model->get_trans_sales(
            null,
            $args,
            'asc'
        );
        foreach ($orders as $res) {
            $date_from = $res->datetime;
            break;
        }
        $max_date = $start;
        $total = 0;
        foreach ($orders as $val) {
            $total += $val->total_amount;
        }
        $prev_sales = 0;
        if (!empty($max_date)) {
            $resultx = $this->cashier_model->get_last_new_gt(Z_READ,$max_date,$date_from);
            if (!empty($resultx[0]))
                $prev_sales = $resultx[0]->grand_total;
        }
        if($prev_sales == "")
            $prev_sales = 0;

        $new_grand_total = $total+$prev_sales;
        $user_id = 1;
        $read = date2Sql($date_from);
        if($read_date != null){
        	$read = date2Sql($read_date);
        }

       	// echo "(".$read.")";
        $zread_id = $this->cashier_model->add_read_details(
            array(
                'read_date'   => $read,
                'read_type'   => Z_READ,
                'user_id'     => $user_id,
                'old_total'   => $prev_sales,
                'grand_total' => $new_grand_total,
                'scope_from'  => $date_from,
                'scope_to'    => $date_to
            )
        );

        // TO MAIN SALES
        $this->load->library('../controllers/dine/main');
        update_load(70);
        sleep(1);
        $this->main->sales_to_main($date_from,$date_to);
        $this->main->reads_to_main($zread_id);

        
        if(!$asJson)
        	return $zread_id;
	}
    ##################
    ### MALLS 
    ##################
    	public function send_to_rob($id=null,$increment=true){
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
            
            //#########################################################################//
                $this->db = $this->load->database('main',true);
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
                $no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$gross_ids,"trans_sales_no_tax.amount >"=>0));
                $ntctr = 0;
                $nttotal = 0;
                foreach ($no_tax as $nt) {
                    $nttotal += $nt->amount;
                    $ntctr++;
                }
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
            // $path = $this->cashier_model->get_rob_path();
            // $filename = $path.$filename.".txt";
            // $fp = fopen($filename, "w+");
            // fwrite($fp,$print_str);
            // fclose($fp);
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
                    if($ftp_conn){
                        $login = ftp_login($ftp_conn, $rlc->rob_username, $rlc->rob_password);
                            if (ftp_put($ftp_conn, $ftpFile, $localFile, FTP_ASCII)) {
                                $error = "";
                                $not_sent = 0;
                            } else {
                                $error = "Sales File is not Sent To RLC server. Please Contact your POS vendor";
                                $not_sent = 1;
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
               $this->db = $this->load->database('default',true);
               $id = $this->cashier_model->add_rob_files($item);
            }
            else{
               $this->db = $this->load->database('default',true);
               $id = $this->cashier_model->update_rob_files(array('print'=>$ctr,'file'=>$filename,"inactive"=>(int)$not_sent),$file);
            }

            return array('error'=>$error,'file'=>$filename);
        }
        public function sm_file($read_date=null,$zread_id=null){
            // $read_date = '2015-09-01 07:57:27';
            $print_str = "";
            $args = array();
            $file_flg = null;
            if($zread_id != null){
                $lastRead = $this->cashier_model->get_z_read($zread_id);
                $zread = array();
                if(count($lastRead) > 0){
                    foreach ($lastRead as $res) {
                        $zread = array(
                            'from' => $res->scope_from,
                            'to'   => $res->scope_to,
                            'old_gt_amnt' => $res->old_total,
                            'grand_total' => $res->grand_total,
                            'read_date' => $res->read_date,
                            'id' => $res->id,
                            'user_id'=>$res->user_id
                        );
                        $read_date = $res->read_date;
                    }           
                }
                $args['trans_sales.datetime >= '] = $zread['from'];             
                $args['trans_sales.datetime <= '] = $zread['to'];
                $file_flg = "Z".date('mdY',strtotime($read_date)).".flg";
            }
            $file_txt = date('mdY',strtotime($read_date)).".txt";
            $file_txt2 = date('ymd',strtotime($read_date)).".txt";
            $file_txt3 = date('mdy',strtotime($read_date)).".txt";

            $file_csv = date('mdY',strtotime($read_date)).".csv";
            $file_csv2 = date('ymd',strtotime($read_date)).".csv";
            $file_csv3 = date('mdy',strtotime($read_date)).".csv";

            $year = date('Y',strtotime($read_date));
            $month = date('M',strtotime($read_date));
            if (!file_exists("C:/SM/".$year."/".$month."/")) {   
                mkdir("C:/SM/".$year."/".$month."/", 0777, true);
            }
            
            $text = "C:/SM/".$year."/".$month."/".$file_txt;
            $text2 = "C:/SM/".$year."/".$month."/".$file_txt2;
            $text3 = "C:/SM/".$year."/".$month."/".$file_txt3;

            $csv = "C:/SM/".$year."/".$month."/".$file_csv;
            $csv2 = "C:/SM/".$year."/".$month."/".$file_csv2;
            $csv3 = "C:/SM/".$year."/".$month."/".$file_csv3;

            $flg = null;
            if($file_flg != null)
                $flg = "C:/SM/".$year."/".$month."/".$file_flg;
            #### GET POS MACHINE DETAILS
                $mesults = $this->site_model->get_tbl('branch_details');
                $mes = $mesults[0];
                $serial_no = $mes->serial;
                $machine_no = $mes->machine_no;
            #### GET TRANS
                if($zread_id == null){
                    $last_zread = $this->cashier_model->get_last_z_read(Z_READ,$read_date);
                    $date_from = null;
                    if(count($last_zread) > 0 ){
                        $args['trans_sales.datetime >= '] = $last_zread[0]->scope_to;             
                    }
                    $args['trans_sales.datetime <= '] = $read_date;
                }

                $curr = true; 
                $trans = $this->trans_sales($args,$curr);
                $sales = $trans['sales'];
                $net = $trans['net'];

                $gt = $this->old_grand_net_total($read_date);
                $old_gt = $gt['true_grand_total'];
                $new_gt = $gt['true_grand_total'] + $net;
            #### DISCOUNTS
                $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
                $discounts = $trans_discounts['total']; 
                $tax_disc = $trans_discounts['tax_disc_total']; 
                $no_tax_disc = $trans_discounts['no_tax_disc_total']; 
                $sc_disc = 0;
                $pwd_disc = 0;
                $other_disc = 0;
                foreach ($trans_discounts['types'] as $code => $disc) {
                    if($code == 'SNDISC'){
                        $sc_disc += $disc['amount'];
                    }
                    else if($code == 'PWDISC'){
                        $pwd_disc += $disc['amount'];
                    }
                    else{
                        $other_disc += $disc['amount'];
                    }
                }
            #### VOIDS
                $total_void = 0;
                if(isset($trans['sales']['void']['orders']) && count($trans['sales']['void']['orders']) > 0){
                    $void = $trans['sales']['void']['orders'];
                    if(count($void) > 0){
                        foreach ($void as $v) {
                            $total_void += $v->total_amount;
                        }                
                    } 
                }
            #### TAX
                $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
                $tax = $trans_tax['total'];
            #### LOCAL TAX
                $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
                $local_tax = $trans_local_tax['total']; 
            #### CHARGES
                $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
                $charges_types = $trans_charges['types'];
                $charges = $trans_charges['total']; 
                $sc = 0;
                $oc = $local_tax;
                foreach ($charges_types as $id => $row) {
                    if($id == SERVICE_CHARGE_ID){
                        $sc += $row['amount'];
                    }
                    else{
                        $oc += $row['amount'];
                    }
                }
            #### NO TAX
                $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
                $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
                $no_tax = $trans_no_tax['total'];
                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;
                $vat_exempt_sales = $no_tax - $no_tax_disc;
            #### PAYMENTS
                
                $cash_pay_sales = 0;
                $cash_pay_ctr = 0;
                $gc_pay_sales = 0;
                $gc_pay_ctr = 0;
                $other_pay_sales = 0;
                $other_pay_ctr = 0;
                $cards = array(
                    'debit' => 0,
                    'Master Card' => 0,
                    'VISA' => 0,
                    'AmEx' => 0,
                    'jcb' => 0,
                    'diners' => 0,
                    'other' => 0
                );
                $card_ctr = array(
                    'debit' => 0,
                    'Master Card' => 0,
                    'VISA' => 0,
                    'AmEx' => 0,
                    'jcb' => 0,
                    'diners' => 0,
                    'other' => 0
                );
                  
                if(count($sales['settled']['ids']) > 0){
                    $pargs["trans_sales_payments.sales_id"] = $sales['settled']['ids'];
                    if($zread_id != null)
                        $this->site_model->db = $this->load->database('main', TRUE);
                    else
                        $this->site_model->db = $this->load->database('default', TRUE);
                    $pesults = $this->site_model->get_tbl('trans_sales_payments',$pargs); 
                    foreach ($pesults as $pes) {
                        if($pes->payment_type == 'cash'){
                            $cash_pay_sales += $pes->to_pay;
                            $cash_pay_ctr += 1;
                        }
                        elseif($pes->payment_type == 'credit'){
                            if(isset($cards[$pes->card_type])){
                                $cards[$pes->card_type] += $pes->to_pay;
                                $card_ctr[$pes->card_type] += 1;
                            }
                            else{
                                $cards['other'] += $pes->to_pay;
                                $card_ctr['other'] += 1;
                            }
                        }
                        elseif($pes->payment_type == 'gc'){
                            $gc_pay_sales += $pes->to_pay;
                            $gc_pay_ctr += 1;
                        }
                        else{
                            $other_pay_sales += $pes->to_pay;
                            $other_pay_ctr += 1;
                        }
                    }
                }
            #### PRINT
                $br_code = '01';
                $tenant_code = '123456789';
                $class_code = '01';
                $trade_code = 'SAP';
                $outlet_no = '01';
                // HEADER 1 - 5
                $print_str = commar($print_str,array($br_code,$tenant_code,$class_code,$trade_code,$outlet_no));
                // OLD GT AND NEW GT 6 - 7
                $print_str = commar($print_str,array(numInt($new_gt),numInt($old_gt) ));
                // SALES TYPE 8
                $print_str = commar($print_str,'SM01');
                // DEPARTMENT SUM 9
                $dept_sum = ($net + $discounts) - $tax;
                $print_str = commar($print_str,numInt($dept_sum));
                // REGULAR DISCOUNT 10
                $print_str = commar($print_str,numInt($other_disc));
                // EMPLOYEE DISCOUNT 11
                $print_str = commar($print_str,numInt(0));
                // SENIOR CITIZEN DISCOUNT 12
                $print_str = commar($print_str,numInt($sc_disc));
                // VIP DISCOUNT 13
                $print_str = commar($print_str,numInt(0));
                // PWD DISCOUNT 14
                $print_str = commar($print_str,numInt($pwd_disc));
                // GPC DISCOUNT 15
                $print_str = commar($print_str,numInt(0));
                // RESERVE DISCOUNT 16 - 21
                for ($i=0; $i <= 6; $i++) { 
                    $print_str = commar($print_str,numInt(0));
                }
                // VAT 22
                $print_str = commar($print_str,numInt($tax));
                // OTHER VAT 23
                $print_str = commar($print_str,numInt($local_tax));
                // ADJUSTMENTS 24 - 28
                $print_str = commar($print_str,array(numInt(0),numInt(0),numInt(0),numInt(0),numInt(0))); 
                // DAILY SALES 29
                $print_str = commar($print_str,numInt($net));
                // VOID 30
                $print_str = commar($print_str,numInt($total_void));
                // REFUND 31
                $print_str = commar($print_str,numInt(0));
                // SALES INCLUSIVE OF VAT 32
                $siov = $net - ($charges + $local_tax) + $discounts - $vat_exempt_sales;
                $print_str = commar($print_str,numInt($siov));
                // NON VAT SALES 33
                $print_str = commar($print_str,numInt($vat_exempt_sales));
                // PAYMENTS 34 - 44 
                    ##CHARGE SALES
                     $charges_sales = $gc_pay_sales + $cards['debit'] + $other_pay_sales + $cards['Master Card'] + $cards['VISA']
                                      + $cards['AmEx'] + $cards['diners'] + $cards['jcb'] + $cards['other'];
                     $print_str = commar($print_str,numInt($charges_sales));        
                    ##CASH SALES
                         $print_str = commar($print_str,numInt($cash_pay_sales));        
                    ##GC SALES
                         $print_str = commar($print_str,numInt($gc_pay_sales));        
                    ##DEBIT SALES
                         $print_str = commar($print_str,numInt($cards['debit']));        
                    ##OTHER SALES
                         $print_str = commar($print_str,numInt($other_pay_sales));     
                    ##MASTER CARD SALES
                         $print_str = commar($print_str,numInt($cards['Master Card']));        
                    ##VISA SALES
                         $print_str = commar($print_str,numInt($cards['VISA']));
                    ##AMERICAN EXPRESS SALES
                         $print_str = commar($print_str,numInt($cards['AmEx']));
                    ##DINERS SALES
                         $print_str = commar($print_str,numInt($cards['diners']));
                    ##JCB SALES
                         $print_str = commar($print_str,numInt($cards['jcb']));
                    ##OTHER CARD
                         $print_str = commar($print_str,numInt($cards['other']));                            
                // SERVICE CHARGE 45
                $print_str = commar($print_str,numInt($sc));
                // OTHER CHARGE 46
                $print_str = commar($print_str,numInt($oc));
                // OTHER CHARGE 47 - 49
                    $allIDS = $sales['settled']['ids'];
                    asort($allIDS);
                    foreach ($allIDS as $key) {
                        $print_str = commar($print_str,$key);
                        break;
                    }
                    $last_key = null;
                    foreach ($allIDS as $key) {
                       $last_key = $key;
                    }
                    $print_str = commar($print_str,$last_key);
                    $print_str = commar($print_str,count($allIDS));
                // BEGINNING INVOICE 50
                $print_str = commar($print_str,iSetObj($trans['first_ref'],'trans_ref'));
                // ENDING INVOICE 51
                $print_str = commar($print_str,iSetObj($trans['last_ref'],'trans_ref'));
                // PAYMENT COUNTER TRANS 52 - 61
                    ##CASH TRANSACTIONS
                         $print_str = commar($print_str,$cash_pay_ctr);
                    ##GC TRANSACTIONS
                         $print_str = commar($print_str,$gc_pay_ctr);
                    ##debit TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['debit']);
                    ##OTHER TENDER TRANSACTIONS
                         $print_str = commar($print_str,$other_pay_ctr);
                    ##MASTER CARD TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['Master Card']);
                    ##VISA TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['VISA']);
                    ##AMERICAN EXPRESS TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['AmEx']);
                    ##DINERS TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['diners']);
                    ##jcb TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['jcb']);
                    ##OTHER TRANSACTIONS
                         $print_str = commar($print_str,$card_ctr['other']);
                // MACHINE NO 62
                $print_str = commar($print_str,$machine_no);
                // SERIAL NO 63
                $print_str = commar($print_str,$serial_no);
                // SERIAL NO 64
                $zread_ctr = 0;
                if($zread_id != null)
                    $zread_ctr = $gt['ctr'];
                $print_str = commar($print_str,$zread_ctr);
                // SERIAL NO 65
                $dt = $read_date;
                if($zread_id != null){
                    $dt = $zread['to'];
                }
                $print_str = commar($print_str,date('His',strtotime($dt)) );
                // SERIAL NO 66
                $print_str = commar($print_str,date('mdY',strtotime($dt)) );

                $print_str = substr($print_str,0,-1);
            $fp = fopen($text, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $fp = fopen($text2, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $fp = fopen($text3, "w+");
            fwrite($fp,$print_str);
            fclose($fp);

            $fp = fopen($csv, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $fp = fopen($csv2, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            $fp = fopen($csv3, "w+");
            fwrite($fp,$print_str);
            fclose($fp);

            if($flg != null){
                $fp = fopen($flg, "w+");
                fwrite($fp,$print_str);
                fclose($fp);
            }
            // echo "<pre>$print_str</pre>";
        }    
        public function sm_file2(){
            $today = $this->site_model->get_db_now('sql');
            $print_str = "";
            ##CREATE FILE NAME
                // $filename = date('m',strtotime($today)).date('d',strtotime($today)).".txt";   
            $filename = date('mdY',strtotime($today)).".txt";
            ##GET DATETIME ARGS
                $date_from = null;
                $date_to = $today;
                $check_datetime = date2Sql($today)." 00:00:00";
                $result = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($today));
                $old_gt_amnt = 0;
                if(!empty($result)){
                    $res = $result[0];
                    $check_datetime = $res->scope_to;
                    $old_gt_amnt = $res->grand_total;
                }
                $largs["trans_sales.datetime >= '".date2Sqldatetime($check_datetime)."'"] = array('use'=>"where",'val'=>null,'third'=>false);
                $last_trans = $this->site_model->get_tbl('trans_sales',$largs,array('trans_sales.datetime'=>'asc'),null,true,'*',null,1);
                if(count($last_trans)>0){
                    $date_from = $last_trans[0]->datetime;
                }

            ##GET ORDERS
                $args = array();
                $terminal = TERMINAL_ID;
                if(!empty($terminal)){
                    $args['trans_sales.terminal_id'] = $terminal;
                }
                $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);   
                $trans_sales = $this->cashier_model->get_trans_sales(null,$args,'asc');
                $orders = array();
                $orders['cancel'] = array(); 
                $orders['sale'] = array();
                $orders['void'] = array();
                $gross = 0;
                $gross_ids = array();
                $all_ids = array();
                $paid = 0;
                $paid_ctr = 0;
                $total_sales = 0;
                $total_void = 0;
                $types = array();
                foreach ($trans_sales as $sale) {
                    if($sale->type_id == 10){
                        if($sale->trans_ref != "" && $sale->inactive == 0){
                            $orders['sale'][$sale->sales_id] = $sale;
                            $gross += $sale->total_amount;
                            $total_sales += $sale->total_amount;
                            $gross_ids[] = $sale->sales_id;
                            if($sale->total_paid > 0){
                                $paid += $sale->total_paid;
                                $paid_ctr ++;                   
                            }
                            $types[$sale->type][$sale->sales_id] = $sale;
                            $all_ids[] = $sale->sales_id;
                        }
                        else if($sale->trans_ref == "" && $sale->inactive == 1){
                            $orders['cancel'][$sale->sales_id] = $sale;
                        }
                    }
                    else{
                        $all_ids[] = $sale->sales_id;
                        $orders['void'][$sale->sales_id] = $sale;
                        $total_void += $sale->total_amount;
                        // $gross += $sale->total_amount;
                        // $gross_ids[] = $sale->sales_id;
                    }
                }
            ##HEADER
                $br_code = '01';
                $tenant_code = '123456789';
                $class_code = '01';
                $trade_code = 'SAP';
                $outlet_no = '01';
                $print_str = commar($print_str,array($br_code,$tenant_code,$class_code,$trade_code,$outlet_no));
            ##OLD GT && NEW GT
                $print_str = commar($print_str,array(numInt($old_gt_amnt),numInt($old_gt_amnt+$total_sales) ));
            ##SALES TYPE
                $print_str = commar($print_str,'SM01');

            ####################################################################################################################################
            ########################## COMPUTATIONS    
            ####################################################################################################################################    
                ##GET DISCOUNTS
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
                    //REGULAR
                        $total_regular_discs = 0;
                        $reg_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code != "SNDISC" && $code != "PWDISC"){
                                $total_regular_discs += $dc['amount'];
                                $reg_disc_ctr += $dc['qty'];                                    
                            }
                        }
                    //SENIOR
                        $total_senior_discs = 0;
                        $seni_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "SNDISC"){
                                $total_senior_discs += $dc['amount'];
                                $seni_disc_ctr += $dc['qty'];                                    
                            }
                        }
                    //PWD
                        $total_pwd_discs = 0;
                        $pwd_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "PWDISC"){
                                $total_pwd_discs += $dc['amount'];
                                $pwd_disc_ctr += $dc['qty'];                                    
                            }
                        }
                ##GET VAT
                    $vargs["trans_sales_tax.sales_id"] = $gross_ids;
                    $vesults = $this->site_model->get_tbl('trans_sales_tax',$vargs);
                    $total_vat = 0;
                    foreach ($vesults as $ves) {
                        if($ves->name == 'VAT'){
                            $total_vat += $ves->amount;
                        }
                    }
                ##GET NON VAT
                    $nvargs["trans_sales_no_tax.sales_id"] = $gross_ids;
                    $nvesults = $this->site_model->get_tbl('trans_sales_no_tax',$nvargs);
                    $total_non_vat = 0;
                    foreach ($nvesults as $nves) {
                        $total_non_vat += $nves->amount;
                    }    
                ##GET SERVICE CHARGES
                    $cargs["trans_sales_charges.sales_id"] = $gross_ids;
                    $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                    $total_service_charges = 0;
                    $other_charges = 0;
                    foreach ($cesults as $ces) {
                        if($ces->charge_id == 1){
                            $total_service_charges += $ces->amount;
                        }
                        else{
                            $other_charges += $ces->amount;
                        }
                    } 
                ##GET PAYMENTS
                    $pargs["trans_sales_payments.sales_id"] = $gross_ids;
                    $pesults = $this->site_model->get_tbl('trans_sales_payments',$pargs); 
                    $cash_pay_sales = 0;
                    $cash_pay_ctr = 0;
                    $gc_pay_sales = 0;
                    $gc_pay_ctr = 0;
                    $other_pay_sales = 0;
                    $other_pay_ctr = 0;
                    $cards = array(
                        'debit' => 0,
                        'Master Card' => 0,
                        'VISA' => 0,
                        'AmEx' => 0,
                        'jcb' => 0,
                        'diners' => 0,
                        'other' => 0
                    );
                    $card_ctr = array(
                        'debit' => 0,
                        'Master Card' => 0,
                        'VISA' => 0,
                        'AmEx' => 0,
                        'jcb' => 0,
                        'diners' => 0,
                        'other' => 0
                    );
                    foreach ($pesults as $pes) {
                        if($pes->payment_type == 'cash'){
                            $cash_pay_sales += $pes->to_pay;
                            $cash_pay_ctr += 1;
                        }
                        elseif($pes->payment_type == 'credit'){
                            if(isset($cards[$pes->card_type])){
                                $cards[$pes->card_type] += $pes->to_pay;
                                $card_ctr[$pes->card_type] += 1;
                            }
                            else{
                                $cards['other'] += $pes->to_pay;
                                $card_ctr['other'] += 1;
                            }
                        }
                        elseif($pes->payment_type == 'gc'){
                            $gc_pay_sales += $pes->to_pay;
                            $gc_pay_ctr += 1;
                        }
                        else{
                            $other_pay_sales += $pes->to_pay;
                            $other_pay_ctr += 1;
                        }
                    }
                ##GET POS MACHINE DETAILS
                    $mesults = $this->site_model->get_tbl('branch_details'); 
                    $mes = $mesults[0];
                    $serial_no = $mes->serial;
                    $machine_no = $mes->machine_no;
                ## ZREAD COUNTER
                    $lastRead = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($today));
                    $zread_ctr=0;
                    if(count($lastRead) > 0){
                        foreach ($lastRead as $res) {
                            $zread_ctr++;
                        }           
                    }
            ##DEPARTMENT SUM
                $print_str = commar($print_str,numInt(0));            
            ##REGULAR DISCOUNTS
                $print_str = commar($print_str,numInt($total_regular_discs));    
            ##EMPLOYEE DISCOUNTS
                $total_employee_discs = 0;
                $print_str = commar($print_str,numInt($total_employee_discs));            
            ##SENIOR CITIZEN DISCOUNTS
                $print_str = commar($print_str,numInt($total_senior_discs));
            ##VIP DISCOUNTS
                $print_str = commar($print_str,numInt(0));
            ##PWD DISCOUNTS
                $print_str = commar($print_str,numInt($total_pwd_discs));
            ##GPC DISCOUNTS
                $print_str = commar($print_str,numInt(0));
            ##RESERVE DISCOUNTS
                $other_discs = array(0,0,0,0,0,0);
                foreach($other_discs as $odis) { 
                    $print_str = commar($print_str,numInt($odis));                
                }
            ##VAT
                $print_str = commar($print_str,numInt($total_vat));
            ##OTHER TAX
                $other_tax = 0;
                $print_str = commar($print_str,numInt($other_tax));    
            ##ADJUSTMENTS
                $pos_adjustment = 0;
                $neg_adjustment = 0;
                $print_str = commar($print_str,array(numInt(0),numInt(0),numInt(0),numInt(0),numInt(0)));    
            ##DAILY SALES
                $print_str = commar($print_str,numInt($total_sales));
            ##TOTAL VOID
                $print_str = commar($print_str,numInt($total_void));
            ##TOTAL REFUND
                $print_str = commar($print_str,numInt(0));
            ##SALES INCLUSIVE OF VAT
                $siov = $total_sales - $total_service_charges - $other_tax + $total_regular_discs + $total_employee_discs + 
                        $other_discs[0] + $other_discs[1] + $other_discs[2] + $other_discs[3] + $other_discs[4] + $other_discs[0] + 
                        $pos_adjustment - $neg_adjustment - $total_non_vat;
                 $print_str = commar($print_str,numInt($siov));        
            ##NON VAT SALES
                 $print_str = commar($print_str,numInt($total_non_vat));        
            ##CHARGE SALES
                 $charges_sales = $gc_pay_sales + $cards['debit'] + $other_pay_sales + $cards['Master Card'] + $cards['VISA']
                                  + $cards['AmEx'] + $cards['diners'] + $cards['jcb'] + $cards['other'];
                 $print_str = commar($print_str,numInt($charges_sales));        
            ##CASH SALES
                 $print_str = commar($print_str,numInt($cash_pay_sales));        
            ##GC SALES
                 $print_str = commar($print_str,numInt($gc_pay_sales));        
            ##DEBIT SALES
                 $print_str = commar($print_str,numInt($cards['debit']));        
            ##OTHER SALES
                 $print_str = commar($print_str,numInt($other_pay_sales));        
            ##MASTER CARD SALES
                 $print_str = commar($print_str,numInt($cards['Master Card']));        
            ##VISA SALES
                 $print_str = commar($print_str,numInt($cards['VISA']));
            ##AMERICAN EXPRESS SALES
                 $print_str = commar($print_str,numInt($cards['AmEx']));
            ##DINERS SALES
                 $print_str = commar($print_str,numInt($cards['diners']));
            ##JCB SALES
                 $print_str = commar($print_str,numInt($cards['jcb']));
            ##OTHER CARD
                 $print_str = commar($print_str,numInt($cards['other']));                            
            ##SERVICE CHARGE 
                 $print_str = commar($print_str,numInt($total_service_charges));
            ##OTHER CHARGE 
                 $print_str = commar($print_str,numInt($other_charges));
            ##TRANSACTION COUNT
                 asort($all_ids);
                 foreach ($all_ids as $key) {
                     $print_str = commar($print_str,$key);
                     break;
                 }
                 $last_key = null;
                 foreach ($all_ids as $key) {
                    $last_key = $key;
                 }
                 $print_str = commar($print_str,$last_key);
                 $print_str = commar($print_str,count($all_ids));
            ##INVOICE COUNT
                 $ordsnums = array();
                 // $ref_ctr = 0;
                 foreach ($orders as $typ => $ord) {
                     if($typ != 'void' && $typ != 'cancel'){
                         foreach ($ord as $sales_id => $sale) {
                             // $ordsnums[$sales_id] = $sale;
                             $ordsnums[$sale->trans_ref] = $sale;
                             // $ref_ctr++;
                         }                    
                     }
                 }
                 ksort($ordsnums);
                 // echo var_dump($ordsnums);
                 $first = array_shift(array_slice($ordsnums, 0, 1));
                 $last = end($ordsnums);
                 $ref_ctr = count($ordsnums);
                 $print_str = commar($print_str,$first->trans_ref);
                 $print_str = commar($print_str,$last->trans_ref);
            ##CASH TRANSACTIONS
                 $print_str = commar($print_str,$cash_pay_ctr);
            ##GC TRANSACTIONS
                 $print_str = commar($print_str,$gc_pay_ctr);
            ##debit TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['debit']);
            ##OTHER TENDER TRANSACTIONS
                 $print_str = commar($print_str,$other_pay_ctr);
            ##MASTER CARD TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['Master Card']);
            ##VISA TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['VISA']);
            ##AMERICAN EXPRESS TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['AmEx']);
            ##DINERS TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['diners']);
            ##jcb TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['jcb']);
            ##OTHER TRANSACTIONS
                 $print_str = commar($print_str,$card_ctr['other']);
            ##MACHINE NO
                 $print_str = commar($print_str,$machine_no);
            ##SERAIL NO
                 $print_str = commar($print_str,$serial_no);
            ##ZREAD CTR
                 $print_str = commar($print_str,$zread_ctr+1);
            ##TRANS TIME
                 $print_str = commar($print_str,date('His',strtotime($today)) );
            ##TRANS DATE
                 $print_str = commar($print_str,date('mdY',strtotime($today)) );

            $print_str = substr($print_str,0,-1);
            // echo "<br><br>".$print_str;
            if(!file_exists("sm")) {
               mkdir("sm", 0777, true);
            }
            $filename = 'sm/'.$filename;
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
        }
        public function ortigas_file($id=null){
            $print_str  = "";
            $lastRead = $this->cashier_model->get_z_read($id);
            $zread = array();
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    $zread = array(
                        'from' => $res->scope_from,
                        'to'   => $res->scope_to,
                        'old_gt_amnt' => $res->old_total,
                        'grand_total' => $res->grand_total,
                        'read_date' => $res->read_date,
                        'id' => $res->id,
                        'user_id'=>$res->user_id
                    );
                    $read_date = $res->read_date;
                }           
            }
            ####################
            ### CREATE FILE NAME
                $objs = $this->site_model->get_tbl('ortigas');
                $obj = array();
                if(count($objs) > 0){
                    $obj = $objs[0];            
                }
                $tenant_code = $obj->tenant_code;
                $fd = date('mdY',strtotime($read_date));
                $readCTR = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($read_date));
                $lastGT_ctr=0;
                if(count($readCTR) > 0){
                    foreach ($lastRead as $res) {
                        $lastGT_ctr++;
                    }           
                }
                $ext = str_pad(($lastGT_ctr+1), 3, "0",STR_PAD_LEFT);
                $file = $tenant_code.TERMINAL_NUMBER.$fd.".".$ext;
            ####################
            ### HOURLY SALES 
                $this->ortigas_generate_hourly($file,$zread,$obj);
            ####################
            ### INVOICE SALES 
                $this->ortigas_generate_invoice($file,$zread,$obj);
            ####################
            ### DAILY SALES     
                $this->ortigas_generate_daily($file,$zread,$obj,$lastGT_ctr+1,true);
        }
        public function ortigas_generate_hourly($file,$zread=array(),$mall=array()){
            $year = date('Y',strtotime($zread['read_date']));
            $month = date('M',strtotime($zread['read_date']));
            if (!file_exists("ortigas_files/hourly/".$year."/".$month."/")) {   
                mkdir("ortigas_files/hourly/".$year."/".$month, 0777, true);
            }
            $filename = "ortigas_files/hourly/".$year."/".$month."/"."H".$file;
            #################
            ## GENERATE FILE
                $total_cover = $total_check = $total_sales = $total_count = 0;
                $counter = 1;
                $print_str = "";
                #TENANT CODE 
                    $print_str .= "01".iSetObj($mall,'tenant_code')."\r\n";
                # POS TERMINAL NUMBER
                    $print_str .= "02".TERMINAL_NUMBER."\r\n";
                # DATE
                    $date = date('mdY',strtotime($zread['read_date']));
                    $print_str .= "03".$date."\r\n";
                $time = unserialize(TIMERANGES);    
                $hour = array();
                foreach ($time as $tm) {
                   $h = date('G',strtotime($tm['FTIME']));
                   if($h == 0)
                      $h = 24;
                   $hour[$h] = array('from' => date('G',strtotime($tm['FTIME'])),
                                     'to'=>date('G',strtotime($tm['TTIME'])) ); 
                }

                $args["trans_sales.datetime  BETWEEN '".$zread['from']."' AND '".$zread['to']."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $args['trans_sales.inactive'] = 0;
                $args['trans_sales.type_id'] = SALES_TRANS;
                $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
                $sales = array();
                foreach ($hour as $code => $hr) {
                    $net = 0;
                    $count = 0;
                    $cover = 0;
                    foreach ($trans_sales as $res) {                           
                        $st = date('G',strtotime($res->datetime));
                        // echo $st."-------".$hr['from']."<br>";
                        if($st == $hr['from']){
                            // echo $code." ------ here<br>";
		                    if($res->type_id == 10){
		                        if($res->trans_ref != "" && $res->inactive == 0){
				                    $vargs["trans_sales_tax.sales_id"] = $res->sales_id;
				                    $vesults = $this->site_model->get_tbl('trans_sales_tax',$vargs);
				                    $total_vat = 0;
				                    foreach ($vesults as $ves) {
				                       if($ves->name == 'VAT'){
				                           $total_vat += $ves->amount;
				                       }
				                    }
				                    $cargs["trans_sales_charges.sales_id"] = $res->sales_id;
				                    $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
				                    $total_service_charges = 0;
				                    $total_delivery_charges = 0;
				                    $other_charges = 0;
				                    $total_charges = 0;
				                    foreach ($cesults as $ces) {
				                        $total_charges += $ces->amount; 
				                    }
		                            $net += ($res->total_amount - $total_charges) - $total_vat;                            
		                            $count += 1;
		                            $c = $res->guest;
		                            if($res->guest == 0)                         
		                                $c = 1;   
		                            $cover += $c;                            
		                        }
		                    }    	
                        }#########
                    } 

                    $sales[$code] = array('net'=>$net,'count'=>$count,'cover'=>$cover);
                    // $gross_ids[] = $res->sales_id;
                    // echo "#########################<br>";
                }
                $total_net = 0;
                $total_count = 0;
                ## GET VAT

                ksort($sales);
                foreach ($sales as $sc => $val) {
                    # HOUR CODE 
                        $print_str .= "04".str_pad($sc,2,0,STR_PAD_LEFT)."\r\n";
                    # NET SALES 
                        $print_str .= "05".num($val['net'],2,'','')."\r\n";
                    # COUNT SALES 
                        $print_str .= "06".$val['count']."\r\n";
                    # COVER SALES 
                        $print_str .= "07".$val['cover']."\r\n";
                    $total_net += $val['net'];
                    $total_count += $val['count'];
                }
                $print_str .= "08".num($total_net,2,'','')."\r\n";
                $print_str .= "09".$total_count."\r\n";
                $fp = fopen($filename, "w+");
                fwrite($fp,$print_str);
                fclose($fp);
            // echo "<pre>".$print_str."</pre>";
        }
        public function ortigas_generate_invoice($file,$zread=array(),$mall=array()){
           $year = date('Y',strtotime($zread['read_date']));
           $month = date('M',strtotime($zread['read_date']));
           if (!file_exists("ortigas_files/invoice/".$year."/".$month."/")) {   
               mkdir("ortigas_files/invoice/".$year."/".$month, 0777, true);
           }
           $filename = "ortigas_files/invoice/".$year."/".$month."/"."I".$file; 
           #################
           ## GENERATE FILE
                $args["trans_sales.datetime  BETWEEN '".$zread['from']."' AND '".$zread['to']."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $trans_sales = $this->cashier_model->get_trans_sales(null,$args,'asc');
                $print_str = "";
                #TENANT CODE 
                    $print_str .= "01".iSetObj($mall,'tenant_code')."\r\n";
                # POS TERMINAL NUMBER
                    $print_str .= "02".TERMINAL_NUMBER."\r\n";
                # DATE
                    $date = date('mdY',strtotime($zread['read_date']));
                    $print_str .= "03".$date."\r\n";
                # POS TERMINAL NUMBER
                    $print_str .= "04".TERMINAL_NUMBER."\r\n";
                    foreach ($trans_sales as $sale) {
                        if($sale->type_id == 10){
                            if($sale->trans_ref != "" && $sale->inactive == 0){

                            	$vargs["trans_sales_tax.sales_id"] = $sale->sales_id;
                            	$vesults = $this->site_model->get_tbl('trans_sales_tax',$vargs);
                            	$total_vat = 0;
                            	foreach ($vesults as $ves) {
                            	   if($ves->name == 'VAT'){
                            	       $total_vat += $ves->amount;
                            	   }
                            	}
                            	$cargs["trans_sales_charges.sales_id"] = $sale->sales_id;
                            	$cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                            	$total_service_charges = 0;
                            	$total_delivery_charges = 0;
                            	$other_charges = 0;
                            	$total_charges = 0;
                            	foreach ($cesults as $ces) {
                            	    $total_charges += $ces->amount; 
                            	}
                                #invoice number 
                                    $print_str .= "05".$sale->trans_ref."\r\n";
                                #NET SALES
                                    $print_str .= "05".num(($sale->total_amount - $total_charges) - $total_vat,2,'','')."\r\n";
                                #STATUS
                                    $print_str .= "0701\r\n";
                            }
                        }
                        // else{
                        //     #invoice number 
                        //         $print_str .= "05".$sale->void_ref."\r\n";
                        //     #NET SALES
                        //         $print_str .= "05".$sale->total_amount."\r\n";
                        //     #STATUS
                        //         $print_str .= "0704\r\n";
                        // }
                    }
                    $fp = fopen($filename, "w+");
                    fwrite($fp,$print_str);
                    fclose($fp);
        }
        public function ortigas_generate_daily($file,$zread=array(),$mall=array(),$zread_ctr=0,$save=false){
            $year = date('Y',strtotime($zread['read_date']));
            $month = date('M',strtotime($zread['read_date']));
            if (!file_exists("ortigas_files/daily/".$year."/".$month."/")) {   
                mkdir("ortigas_files/daily/".$year."/".$month, 0777, true);
            }
            $filename = "ortigas_files/daily/".$year."/".$month."/"."D".$file; 
            #################
            ## GET SALES
                $print_str = "";
                $args["trans_sales.datetime  BETWEEN '".$zread['from']."' AND '".$zread['to']."'"] = array('use'=>'where','val'=>null,'third'=>false);
                $trans_sales = $this->cashier_model->get_trans_sales(null,$args,'asc');
                $orders = array();
                $orders['cancel'] = array(); 
                $orders['sale'] = array();
                $orders['void'] = array();
                $gross = 0;
                $gross_ids = array();
                $all_ids = array();
                $paid = 0;
                $paid_ctr = 0;
                $total_sales = 0;
                $total_void = 0;
                $types = array();
                $cover = 0;
                foreach ($trans_sales as $sale) {
                   if($sale->type_id == 10){
                       if($sale->trans_ref != "" && $sale->inactive == 0){
                           $orders['sale'][$sale->sales_id] = $sale;
                           $gross += $sale->total_amount;
                           $total_sales += $sale->total_amount;
                           $gross_ids[] = $sale->sales_id;
                           if($sale->total_paid > 0){
                               $paid += $sale->total_paid;
                               $paid_ctr ++;                   
                           }
                           if($sale->guest > 0){
                             $cover += $sale->guest;
                           }
                           else{
                             $cover += 1;
                           }

                           $types[$sale->type][$sale->sales_id] = $sale;
                           $all_ids[] = $sale->sales_id;
                       }
                       else if($sale->trans_ref == "" && $sale->inactive == 1){
                           $orders['cancel'][$sale->sales_id] = $sale;
                       }
                   }
                   else{
                       $all_ids[] = $sale->sales_id;
                       $orders['void'][$sale->sales_id] = $sale;
                       $total_void += $sale->total_amount;
                   }
                }
                ## GET DISCOUNTS
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
                    //REGULAR
                        $total_regular_discs = 0;
                        $reg_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code != "SNDISC" && $code != "PWDISC"){
                                $total_regular_discs += $dc['amount'];
                                $reg_disc_ctr += $dc['qty'];                                    
                            }
                        }
                    //SENIOR
                        $total_senior_discs = 0;
                        $seni_disc_ctr = 0;
                        $senior = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "SNDISC"){
                                $total_senior_discs += $dc['amount'];
                                $seni_disc_ctr += $dc['qty'];       
                                $senior++;                             
                            }
                        }
                    //PWD
                        $total_pwd_discs = 0;
                        $pwd_disc_ctr = 0;
                        foreach ($disc_codes as $code => $dc) {
                            if($code == "PWDISC"){
                                $total_pwd_discs += $dc['amount'];
                                $pwd_disc_ctr += $dc['qty'];                                    
                            }
                        }
                ## GET VAT
                   $total_vat = 0;
                   if(count($gross_ids) > 0){
                    $vargs["trans_sales_tax.sales_id"] = $gross_ids;
                    $vesults = $this->site_model->get_tbl('trans_sales_tax',$vargs);
                    foreach ($vesults as $ves) {
                       if($ves->name == 'VAT'){
                           $total_vat += $ves->amount;
                       }
                    }
                   }
                ## GET NON VAT
                   $total_non_vat = 0;
                   if(count($gross_ids) > 0){
                    $nvargs["trans_sales_no_tax.sales_id"] = $gross_ids;
                    $nvesults = $this->site_model->get_tbl('trans_sales_no_tax',$nvargs);
                    foreach ($nvesults as $nves) {
                       $total_non_vat += $nves->amount;
                    }    
                   } 
                ## GET ZERO RATED
                    $zrctr = 0;
                    $zrtotal = 0;
                    if(count($gross_ids) > 0){
                        $zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$gross_ids,"trans_sales_zero_rated.amount >"=>0));
                        foreach ($zero_rated as $zt) {
                            $zrtotal += $zt->amount;
                            $zrctr++;
                        }
                    }
                ## GET SERVICE CHARGES
                    $total_service_charges = 0;
                    $total_delivery_charges = 0;
                    $other_charges = 0;
                    if(count($gross_ids) > 0){
                        $cargs["trans_sales_charges.sales_id"] = $gross_ids;
                        $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                        foreach ($cesults as $ces) {
                            if($ces->charge_id == 1){
                                $total_service_charges += $ces->amount;
                            }
                            if($ces->charge_id == 2){
                                $total_delivery_charges += $ces->amount;
                            }
                            else{
                                $other_charges += $ces->amount;
                            }
                        }     
                    }
                ## GET LOCAL TAX
                    $total_local_tax = 0;
                    if(count($gross_ids) > 0){
                        $targs["trans_sales_local_tax.sales_id"] = $gross_ids;
                        $tesults = $this->site_model->get_tbl('trans_sales_local_tax',$targs);   
                        foreach ($tesults as $tes) {
                            $total_local_tax += $tes->amount;
                        }
                    }
                ## GET ORTIGAS TAX READS
                    $orgs['read_date <= '] = date2Sql($zread['read_date']);
                    $orgs['no_tax = '] = 0;
                    $tax_read = $this->site_model->get_tbl('ortigas_read_details',$orgs,array('id'=>'desc'),null,true,'*',null,1);
                    $tax_old_gt = 0;
                    if(count($tax_read) > 0){
                        $tax_old_gt = $tax_read[0]->grand_total;
                    }
                ## GET ORTIGAS NON TAX READS
                    $orgs['read_date <= '] = date2Sql($zread['read_date']);
                    $orgs['no_tax = '] = 1;
                    $tax_read = $this->site_model->get_tbl('ortigas_read_details',$orgs,array('id'=>'desc'),null,true,'*',null,1);
                    $tax_non_old_gt = 0;
                    if(count($tax_read) > 0){
                        $tax_non_old_gt = $tax_read[0]->grand_total;
                    }        
            #################
            ## GENERATE FILE
                // $total_sales += $total_senior_discs;
                $net = ( (  ($total_sales + $total_senior_discs) - ($total_service_charges + $total_delivery_charges + $total_local_tax) ) - ($total_non_vat)) - $total_vat;
                $total_deduc = $total_pwd_discs + $total_regular_discs + ($total_service_charges + $total_delivery_charges) + $total_local_tax;
                // $gross = $net + $total_deduc + $total_vat;
                $gross = ($net + $total_vat) + $total_deduc;
                // $tv = (($gross - $total_deduc) * 0.12) / 1.12;
                $tv = $total_vat;
                $vatable = $gross - $tv;
                
                // echo $tv;
                # TENANT CODE
                    $print_str .= "01".iSetObj($mall,'tenant_code')."\r\n";
                # POS TERMINAL NUMBER
                    $print_str .= "02".TERMINAL_NUMBER."\r\n";
                # DATE
                    $date = date('mdY',strtotime($zread['read_date']));
                    $print_str .= "03".$date."\r\n";    
                # OLD TAX ACCUMULATED SALES 
                    $print_str .= "04".num($tax_old_gt,2,'','')."\r\n";
                # NEW TAX ACCUMULATED SALES 
                    $print_str .= "05".num($tax_old_gt + $net,2,'','')."\r\n";
                    // $print_str .= "05".num($tax_old_gt + ($net+$tv),2,'','')."\r\n";
                    $tax_new_gt = $tax_old_gt + $net;
                # TOTAL GROSS AMOUNT
                    $print_str .= "06".num($gross,2,'','')."\r\n";
                # TOTAL DEDUCTIONS AMOUNT
                    $print_str .= "07".num($total_deduc,2,'','')."\r\n";
                # TOTAL PROMO SALES
                    $print_str .= "08".num(0,2,'','')."\r\n";
                # TOTAL PWD DISCOUNT
                    $print_str .= "09".num($total_pwd_discs,2,'','')."\r\n";
                # TOTAL REFUND AMOUNT
                    $print_str .= "10".num(0,2,'','')."\r\n";
                # TOTAL RETURND ITEMS AMOUNT
                    $print_str .= "11".num(0,2,'','')."\r\n";
                # TOTAL OTHER TAXES
                    $print_str .= "12".num($total_local_tax,2,'','')."\r\n";
                # TOTAL SERVICE CHARGE AMOUNT
                    $print_str .= "13".num($total_service_charges,2,'','')."\r\n";
                # TOTAL ADJUSTMENT DISCOUNT
                    $print_str .= "14".num(0,2,'','')."\r\n";
                # TOTAL VOID AMOUNT
                    $print_str .= "15".num(0,2,'','')."\r\n";
                # TOTAL DISCOUNT CARDS
                    $print_str .= "16".num(0,2,'','')."\r\n";
                # TOTAL DELIVERY CHARGES
                    $print_str .= "17".num($total_delivery_charges,2,'','')."\r\n";
                # TOTAL GIFT CERTIFICATES
                    $print_str .= "18".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC DISCOUNT 1
                    $print_str .= "19".num($total_regular_discs,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC DISCOUNT 2
                    $print_str .= "20".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC DISCOUNT 3
                    $print_str .= "21".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC DISCOUNT 4
                    $print_str .= "22".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC DISCOUNT 5
                    $print_str .= "23".num(0,2,'','')."\r\n";
                # TOTAL OF ALL NON APPROVED STORE DISCOUNTS
                    $print_str .= "24".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 1
                    $print_str .= "25".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 2
                    $print_str .= "26".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 3
                    $print_str .= "27".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 4
                    $print_str .= "28".num(0,2,'','')."\r\n";
                # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 5
                    $print_str .= "29".num(0,2,'','')."\r\n";
                # TOTAL VAT/TAX AMOUNT
                    // $vat = $net-$total_non_vat-$total_vat;
                    // $print_str .= "30".num($vat,2,'',''). "-".num($vat) ."\r\n";
                    $print_str .= "30".num($tv, 2,'','')."\r\n";
                # TOTAL NET SALES AMOUNT
                    $print_str .= "31".num($net, 2,'','')."\r\n";
                # TOTAL COVER COUNT
                    $total_tax_cover = $cover;
                    if($total_tax_cover < 0)
                        $total_tax_cover *= -1;
                    $print_str .= "32".num($total_tax_cover, 0,'','')."\r\n";
                # TOTAL COVER COUNT
                    $print_str .= "33".num($zread_ctr, 0,'','')."\r\n";
                # TOTAL TRANS COUNT
                    $no_trans = count($gross_ids);
                    $print_str .= "34".num($no_trans, 0,'','')."\r\n";
                # SALES TYPE
                    $print_str .= "35".iSetObj($mall,'sales_type')."\r\n";
                # AMOUNT
                    $print_str .= "36".num($vatable, 0,'','')."\r\n";
                # OLD NON TAX ACCUMULATED SALES 
                    $print_str .= "37".num($tax_non_old_gt,2,'','')."\r\n";
                # NEW NON TAX ACCUMULATED SALES 
                    $total_non_deduc = $total_senior_discs;
                    $no_net_total = $total_non_vat - $total_non_deduc;
                    $non_gross = $no_net_total +  $total_non_deduc;

                    $print_str .= "38".num($tax_non_old_gt + ($no_net_total),2,'','')."\r\n";
                    $tax_non_new_gt = $tax_non_old_gt + $no_net_total;
                # TOTAL GROSS NON TAX 
                    $print_str .= "39".num($non_gross,2,'','')."\r\n";
                # TOTAL DEDUCTIONS
                    $print_str .= "40".num($total_non_deduc,2,'','')."\r\n";
                # TOTAL PROMO SALES AMOUNT
                    $print_str .= "41".num(0,2,'','')."\r\n";
                # TOTAL SENIOR CITIZEN DISCOUNT
                    $print_str .= "42".num($total_senior_discs,2,'','')."\r\n";
                ################### NON 
                    # TOTAL REFUND AMOUNT
                        $print_str .= "43".num(0,2,'','')."\r\n";
                    # TOTAL RETURND ITEMS AMOUNT
                        $print_str .= "44".num(0,2,'','')."\r\n";
                    # TOTAL OTHER TAXES
                        $print_str .= "45".num(0,2,'','')."\r\n";
                    # TOTAL SERVICE CHARGE AMOUNT
                        $print_str .= "46".num(0,2,'','')."\r\n";
                    # TOTAL ADJUSTMENT DISCOUNT
                        $print_str .= "47".num(0,2,'','')."\r\n";
                    # TOTAL VOID AMOUNT
                        $print_str .= "48".num(0,2,'','')."\r\n";
                    # TOTAL DISCOUNT CARDS
                        $print_str .= "49".num(0,2,'','')."\r\n";
                    # TOTAL DELIVERY CHARGES
                        $print_str .= "50".num(0,2,'','')."\r\n";
                    # TOTAL GIFT CERTIFICATES
                        $print_str .= "51".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC DISCOUNT 1
                        $print_str .= "52".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC DISCOUNT 2
                        $print_str .= "53".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC DISCOUNT 3
                        $print_str .= "54".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC DISCOUNT 4
                        $print_str .= "55".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC DISCOUNT 5
                        $print_str .= "56".num(0,2,'','')."\r\n";
                    # TOTAL OF ALL NON APPROVED STORE DISCOUNTS
                        $print_str .= "57".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 1
                        $print_str .= "58".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 2
                        $print_str .= "59".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 3
                        $print_str .= "60".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 4
                        $print_str .= "61".num(0,2,'','')."\r\n";
                    # TOTAL STORE SPECIFIC NON APPROVED DISCOUNT 5
                        $print_str .= "62".num(0,2,'','')."\r\n";
                # VAT
                    $print_str .= "63".num(0,2,'','')."\r\n";
                # TOTAL NET SALES
                    $print_str .= "64".num(($total_non_vat - $total_non_deduc),2,'','')."\r\n";
                # GRAND TOTAL NET SALES
                    $net_sales = $net + ($total_non_vat + $total_vat);
                    $print_str .= "65".num($net + $no_net_total,2,'','')."\r\n";
            if($save){
                if($tax_old_gt == ""){
                    $tax_old_gt = 0;
                }
                if($tax_new_gt == ""){
                    $tax_new_gt = 0;
                }
                $item_tax = array(
                    'zread_id' => $zread['id'],
                    'read_date'=> $zread['read_date'],
                    'user_id'=> $zread['user_id'],
                    'old_total'=> $tax_old_gt,
                    'grand_total'=> $tax_new_gt,
                    'scope_from'=> $zread['from'],
                    'scope_to'=> $zread['to'],
                    'no_tax'=> 0,
                );
                $this->site_model->add_tbl('ortigas_read_details',$item_tax,array('reg_date'=>'NOW()'));
                if($tax_non_old_gt == ""){
                    $tax_non_old_gt = 0;
                }
                if($tax_non_new_gt == ""){
                    $tax_non_new_gt = 0;
                }

                $item_no_tax = array(
                    'zread_id' => $zread['id'],
                    'read_date'=> $zread['read_date'],
                    'user_id'=> $zread['user_id'],
                    'old_total'=> $tax_non_old_gt,
                    'grand_total'=> $tax_non_new_gt,
                    'scope_from'=> $zread['from'],
                    'scope_to'=> $zread['to'],
                    'no_tax'=> 1,
                );
                $this->site_model->add_tbl('ortigas_read_details',$item_no_tax,array('reg_date'=>'NOW()'));
            }
            // echo "<pre>".$print_str."</pre>";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);                    
        }
        public function araneta_file($zread_id=null){
            $araneta_db = $this->site_model->get_tbl('araneta');
            $araneta = $araneta_db[0];
            $months = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
            // S = summary file , L = for transaaction list , C for monthly file
            $print_str  = "";
            $lastRead = $this->cashier_model->get_z_read($zread_id);
            $zread = array();
            if(count($lastRead) > 0){
                foreach ($lastRead as $res) {
                    $zread = array(
                        'from' => $res->scope_from,
                        'to'   => $res->scope_to,
                        'old_gt_amnt' => $res->old_total,
                        'grand_total' => $res->grand_total,
                        'read_date' => $res->read_date,
                        'id' => $res->id,
                        'user_id'=>$res->user_id
                    );
                    $read_date = $res->read_date;
                }           
            }
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            
            $args['trans_sales.datetime >= '] = $zread['from'];             
            $args['trans_sales.datetime <= '] = $zread['to'];             
            $trans = $this->trans_sales($args);
            #######################
            ## DAILY 
                $file = $araneta->lessee_name;
                $this->araneta_generate_daily($file,$zread,$trans,$araneta);
            #######################
            ## TRANS LIST 
                $file = $araneta->lessee_name;
                $this->araneta_generate_list($file,$zread,$trans,$araneta);
        }
        public function araneta_generate_list($file,$zread,$trans,$araneta){
            $print_str = "";
            $year = date('Y',strtotime($zread['read_date']));
            $month = date('M',strtotime($zread['read_date']));
            $m = date('m',strtotime($zread['read_date']));
            $d = date('d',strtotime($zread['read_date']));
            $mo = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
            if (!file_exists("C:/Araneta/".$year."/")) {   
                mkdir("C:/Araneta/".$year, 0777, true);
            }

            $filename = "C:/Araneta/".$year."/".$file."L.".$mo[$m].$d; 
            $curr = false;
            $sales = $trans['sales'];
            $settled = $trans['sales']['settled']['orders'];
            foreach ($settled as $sales_id => $set){
                $print_str .= $araneta->space_code." ";
                $print_str .= $araneta->lessee_no." ";
                $print_str .= date('m/d/y',strtotime($set->datetime))." ";
                $print_str .= date('H:i:s',strtotime($set->datetime))." ";
                $print_str .= $sales_id." ";
                $print_str .= $set->trans_ref." ";
                $print_str .= TERMINAL_ID." ";
                $print_str .= numInt(0)." ";#GROSS
                $trans_discounts = $this->discounts_sales($sales_id,$curr);
                $discounts = $trans_discounts['total'];
                $tax_disc = $trans_discounts['tax_disc_total']; 
                $no_tax_disc = $trans_discounts['no_tax_disc_total'];  
                $print_str .= numInt($discounts)." ";
                $print_str .= numInt(0)." ";#VOID
                $print_str .= numInt(0)." ";#REFUND
                $print_str .= numInt(0)." ";#ADJ
                $trans_tax = $this->tax_sales($sales_id,$curr);
                $tax = $trans_tax['total'];
                $print_str .= numInt($tax)." ";
                
                $trans_local_tax = $this->local_tax_sales($sales_id,$curr);
                $local_tax = $trans_local_tax['total']; 
                $trans_charges = $this->charges_sales($sales_id,$curr);
                $charges_types = $trans_charges['types'];
                $charges = $trans_charges['total']; 
                $sc = 0;
                $oc = $local_tax;
                foreach ($charges_types as $id => $row) {
                    if($id == SERVICE_CHARGE_ID){
                        $sc += $row['amount'];
                    }
                    else{
                        $oc += $row['amount'];
                    }
                }
                $print_str .= numInt($oc)." ";
                $print_str .= numInt($sc)." ";
                $trans_no_tax = $this->no_tax_sales($sales_id,$curr);
                $trans_zero_rated = $this->zero_rated_sales($sales_id,$curr);
                $no_tax = $trans_no_tax['total'];
                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;
                $no_tax = $no_tax - $no_tax_disc;
                $net_no_adds = $set->total_amount-($charges+$local_tax);
                // $taxable = ($net_no_adds - ($tax + $no_tax)); 
                $taxable =   ($net_no_adds - ($tax + ($no_tax+$zero_rated))  );
                $print_str .= numInt($taxable)." ";
                $print_str .= numInt($no_tax)." ";
                // $print_str .= numInt($set->total_amount          )." ";
                $print_str .= numInt($net_no_adds)." ";

                $payments = $this->payment_sales($sales_id,$curr);
                $payments_types = $payments['types'];
                $cash = 0;
                $opay = 0;
                foreach ($payments_types as $type => $val) {
                    if($type == 'cash' ){
                        $cash += $val['amount'];
                    }
                    else
                        $opay += $val['amount'];
                }
                $print_str .= numInt($opay)." ";
                $print_str .= numInt($cash)." ";
                $print_str .= "\r\n";
            }
            // echo "<pre>".$print_str."</pre>";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
        }    
        public function araneta_generate_daily($file,$zread,$trans,$araneta,$monthtype=false){
            $print_str = "";
            $mo = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
            $year = date('Y',strtotime($zread['read_date']));
            $month = date('M',strtotime($zread['read_date']));
            $m = date('m',strtotime($zread['read_date']));
            $d = date('d',strtotime($zread['read_date']));
            if(!$monthtype){
                if (!file_exists("C:/Araneta/".$year."/")) {   
                    mkdir("C:/Araneta/".$year."/", 0777, true);
                }
                $filename = "C:/Araneta/".$year."/".$file."S.".$mo[$m].$d; 
            }
            else{
                if (!file_exists("C:/Araneta/".$year."/")) {   
                    mkdir("C:/Araneta/".$year."/", 0777, true);
                }
                $filename = "C:/Araneta/".$year."/".$file."C.".$mo[$m]."00";    
            }
            

            $curr = false;
            $old_gt = $this->old_grand_net_total($zread['from']);
            $new_gt = $old_gt['old_grand_total'] + $trans['net'];
            $sales = $trans['sales'];
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $discounts = $trans_discounts['total']; 
            $tax_disc = $trans_discounts['tax_disc_total']; 
            $no_tax_disc = $trans_discounts['no_tax_disc_total']; 
            $total_void = 0;
            if(isset($trans['sales']['void']['orders']) && count($trans['sales']['void']['orders']) > 0){
                $void = $trans['sales']['void']['orders'];
                if(count($void) > 0){
                    foreach ($void as $v) {
                        $total_void += $v->total_amount;
                    }                
                } 
            }
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $tax = $trans_tax['total'];

            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $local_tax = $trans_local_tax['total']; 
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $charges_types = $trans_charges['types'];
            $charges = $trans_charges['total']; 
            $sc = 0;
            $oc = $local_tax;
            foreach ($charges_types as $id => $row) {
                if($id == SERVICE_CHARGE_ID){
                    $sc += $row['amount'];
                }
                else{
                    $oc += $row['amount'];
                }
            }
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);


            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $net = $trans['net'];
            // $net_no_adds = $trans['net']-$charges-$local_tax;
            // $taxable = ($net_no_adds - ($tax + $no_tax)); 

            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            $nontaxable = $no_tax - $no_tax_disc;
            $taxable =   ($net_no_adds - ($tax + ($nontaxable+$zero_rated))  );
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;

            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            $payments_types = $payments['types'];
            $cash = 0;
            $opay = 0;
            foreach ($payments_types as $type => $val) {
                if($type == 'cash' ){
                    $cash += $val['amount'];
                }
                else
                    $opay += $val['amount'];
            }
            #########################
            #### GENERATE TEXT FILE
                $print_str .= $araneta->space_code." ";
                $print_str .= $araneta->lessee_no." ";
                $print_str .= numInt($new_gt)." ";
                $print_str .= numInt($old_gt['old_grand_total'])." ";
                $print_str .= numInt($trans['net'])." ";
                $print_str .= numInt($discounts)." ";
                $print_str .= numInt(0)." ";
                $print_str .= numInt($total_void)." ";
                $print_str .= numInt(0)." ";
                $print_str .= numInt($trans['net'] + $total_void)." ";#GROSS
                $print_str .= numInt($tax)." ";
                $print_str .= numInt($oc)." ";
                $print_str .= numInt($sc)." ";
                $print_str .= numInt($taxable)." ";
                $print_str .= numInt($nontaxable)." ";
                $print_str .= numInt($opay)." ";
                $print_str .= numInt($cash)." ";
                
                $first_ref = iSetObj($trans['first_ref'],'trans_ref',$trans['first_ref']);
                $last_ref = iSetObj($trans['last_ref'],'trans_ref',$trans['last_ref']);

                $print_str .= $first_ref." ";
                $print_str .= $last_ref." ";
                $print_str .= $trans['ref_count']." ";
                $print_str .= "0 ";
                $print_str .= $old_gt['ctr']." ";
                $print_str .= date('m/d/y',strtotime($zread['read_date']))." ";
                $print_str .= date('H:i:s',strtotime($zread['from']))." ";
                $print_str .= date('H:i:s',strtotime($zread['to']))." ";
                for ($i=0; $i <= 10; $i++) { 
                    $print_str .= numInt(0)." ";
                }
            
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp); 
        }
        public function araneta_month_file($date=null){
            $araneta_db = $this->site_model->get_tbl('araneta');
            $araneta = $araneta_db[0];
            $months = array('01'=>'1','02'=>'2','03'=>'3','04'=>'4','05'=>'5','06'=>'6','07'=>'7','08'=>'8','09'=>'9','10'=>'A','11'=>'B','12'=>'C');
            // S = summary file , L = for transaaction list , C for monthly file
            // $time = $this->site_model->get_db_now();
            $rargs["MONTH(read_details.read_date) = MONTH('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
            $select = "read_details.*";
            $results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
            foreach ($results as $res) {
                $from = $res->scope_from;
                break;
            }
            foreach ($results as $res) {
                $to = $res->scope_to;
            }
            $read_date = date("Y-m-t", strtotime($from) );
            $zread = array(
                'from' => $from,
                'to'   => $to,
                'read_date' => $read_date,
            );

            $user = $this->session->userdata('user');
            $args['trans_sales.datetime >= '] = $zread['from'];             
            $args['trans_sales.datetime <= '] = $zread['to'];             
            $trans = $this->trans_sales($args);
            #######################
            ## DAILY 
                $file = $araneta->lessee_name;
                $this->araneta_generate_daily($file,$zread,$trans,$araneta,true);
        }
    ##################
    ### SCRIPT FIX
    ##################
        public function script_delete(){
           $this->load->model('core/trans_model');
           $this->db = $this->load->database('main', TRUE);
           $result = $this->site_model->get_tbl('trans_sales_payments',array('payment_type'=>'chit'));
           
           $ids = array();
           foreach ($result as $res) {
               if(!in_array($res->sales_id, $ids)){
                    $ids[] = $res->sales_id;
               }
           }
           $args['trans_sales.sales_id'] = $ids;
           $sales = $this->trans_model->get_trans_sales(null,$args,'ASC');
           $query = "";
           $in_str = "IN(";
           foreach ($sales as $res) {
              $in_str .= $res->sales_id.",";
           }
           $in_str = substr($in_str, 0,-1).")";
           $tbls = array(
                'trans_sales',
                'trans_sales_charges',
                'trans_sales_items',
                'trans_sales_discounts',
                'trans_sales_menu_modifiers',
                'trans_sales_menus',
                'trans_sales_no_tax',
                'trans_sales_payments',
                'trans_sales_tax',
                'trans_sales_zero_rated',
                'trans_sales_local_tax',
                'reasons',
           );
           foreach ($tbls as $txt) {
                if($txt == "reasons")
                   $query .= "DELETE FROM ".$txt." WHERE trans_id ".$in_str.";\r\n";
                else
                   $query .= "DELETE FROM ".$txt." WHERE sales_id ".$in_str.";\r\n";
           }
           echo "<pre>".$query."</pre>"; 
           $filename = "remove_chits.sql";
           $fp = fopen($filename, "w+");
           fwrite($fp,$query);
           fclose($fp);
           echo "file created"; 
        }  
        public function script_reref(){
           $this->load->model('core/trans_model');
           $start_ref = "00000001";
           // $start_ref = $this->next_ref(10,"00000009");
           $this->db = $this->load->database('main', TRUE);
           $args['trans_sales.type_id'] = SALES_TRANS;
           $args['trans_sales.inactive'] = 0;
           $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
           $sales = $this->trans_model->get_trans_sales(null,$args,'ASC');
           $query = "";
           $ctr = 1;
           foreach ($sales as $res) {
                // $query .= $res->sales_id;
                if($ctr == 1){
                    $next = $start_ref;
                }
                else{
                    $next = $this->next_ref(10,$next);
                }
                $update_trans_ref = $this->trans_model->update_trans_ref_string(array('trans_ref'=>$next),$res->sales_id);    
                $query .= $update_trans_ref.";\r\n";
                $ctr++;
           }
           $next = $this->next_ref(10,$next);
           $query .= "UPDATE trans_types SET next_ref = '".$next."' where type_id=10;";
           echo "<pre>".$query."</pre>"; 
           $filename = "reref.sql";
           $fp = fopen($filename, "w+");
           fwrite($fp,$query);
           fclose($fp);
           echo "file created";
        } 
        public function insert_reref(){
            $id_ctr=87;
            $shift_id=4;
            $user_id=9;

            $this->load->model('core/trans_model');
            $this->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales_payments',array('payment_type'=>'chit'));
            
            $ids = array();
            foreach ($result as $res) {
                if(!in_array($res->sales_id, $ids)){
                     $ids[] = $res->sales_id;
                }
            }

            $args = array();
            // $args['trans_sales.datetime >='] = '2015-07-17 18:00:00';
            // $args['trans_sales.datetime <='] = '2015-07-18 05:00:00';
            $args['trans_sales.sales_id'] = $ids;
            $result = $this->cashier_model->get_just_trans_sales(
                null,
                $args,
                'asc'
            );
            $orders = $result->result();
            $cols = $result->list_fields();
            $sales = array();
            $sales_ids = array();
            $s_id = array();
            foreach ($orders as $ord) {
                $row = array();
                $id_ctr += 1;
                $s_id[$ord->sales_id] = $id_ctr; 
                $sales_ids[] = $ord->sales_id;
                foreach ($cols as $col) {
                    if($col != "id"){
                        $row[$col] = $ord->$col;
                    }
                }
                $row['pos_id'] = TERMINAL_ID;
                $sales[] = $row;
            }
            $start_ref = "00000077";
            $ctr = 1;

            foreach ($sales as $key => $row) {
                
                if($ctr == 1){
                    $next = $start_ref;
                }
                else{
                    $next = $this->next_ref(10,$next);
                }

                $row['sales_id'] = $s_id[$row['sales_id']];
                $row['trans_ref'] = $next;
                $row['datetime'] = str_replace('07-18', '07-17', $row['datetime']);
                $row['update_date'] = str_replace('07-18', '07-17', $row['update_date']);
                $row['shift_id'] = $shift_id;
                $row['user_id'] = $user_id;
                $sales[$key] = $row;
                $ctr++;
            }
            $query = "";
            $ctr = 1;
            foreach ($sales as $row) {
                $write_string = $this->db->insert_string('trans_sales',$row);
                if($ctr != 1)
                    $query .= str_replace('INSERT INTO `trans_sales` (`sales_id`, `type_id`, `trans_ref`, `void_ref`, `type`, `user_id`, `shift_id`, `terminal_id`, `customer_id`, `total_amount`, `total_paid`, `memo`, `table_id`, `guest`, `datetime`, `update_date`, `paid`, `reason`, `printed`, `inactive`, `waiter_id`, `split`, `pos_id`) VALUES',"", $write_string);
                else
                    $query .= $write_string;
                $query .= ",\r\n";
                $ctr++;
            }
            $query = substr($query, 0,-3);
            $query .= ";\r\n";

            $tbl['trans_sales_charges'] = $this->cashier_model->get_trans_sales_charges(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_items'] = $this->cashier_model->get_trans_sales_items(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_discounts'] = $this->cashier_model->get_trans_sales_discounts(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_menu_modifiers'] = $this->cashier_model->get_trans_sales_menu_modifiers(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_menus'] = $this->cashier_model->get_trans_sales_menus(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_no_tax'] = $this->cashier_model->get_trans_sales_no_tax(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_payments'] = $this->cashier_model->get_trans_sales_payments(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_tax'] = $this->cashier_model->get_trans_sales_tax(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_zero_rated'] = $this->cashier_model->get_trans_sales_zero_rated(null,array('sales_id'=>$sales_ids));
            $tbl['trans_sales_local_tax'] = $this->cashier_model->get_trans_sales_local_tax(null,array('sales_id'=>$sales_ids));
            $tbl['reasons'] = $this->cashier_model->get_just_reasons($sales_ids);
            $details = array();
            foreach ($tbl as $table => $row) {
                $cl = $this->site_model->get_tbl_cols($table);
                unset($cl[0]);
                $dets = array();
                foreach ($row as $r) {
                    $det = array();
                    foreach ($cl as $c) {
                        if($c != "id"){
                            $det[$c] = $r->$c;
                        }
                    }
                    $det['pos_id'] = TERMINAL_ID;
                    $dets[] = $det;
                }####
                $details[$table]=$dets;
            }
            foreach ($details as $tbl => $det) {
                foreach ($det as $id => $row) {
                    if($tbl == "reasons")
                        $row['trans_id'] = $s_id[$row['trans_id']];
                    else
                        $row['sales_id'] = $s_id[$row['sales_id']];
                    $det[$id] = $row;
                }
                $details[$tbl] = $det;
            }
            foreach ($details as $tbl => $det) {
                $query .= "\r\n";
                foreach ($det as $id => $items) {
                    $write_string = $this->db->insert_string($tbl,$items);
                    $query .= $write_string;
                    $query .= ";\r\n";
                }
            }    

            echo "<pre>".$query."</pre>"; 
        }    
        public function script_update(){
           $this->load->model('core/trans_model');
           $sales = $this->trans_model->get_trans_sales(null,array("trans_ref"=>"00000009"),'ASC');
           // echo var_dump($sales);
           // $ctr = 1;
           // foreach ($sales as $res) {
           //      if($ctr > 1){
           //          if($ctr <= 800){
           //              if($ctr == 2){
           //                  $next_ref = '000007217';
           //                  $refs=$this->trans_model->write_ref(10,$next_ref,$res->user_id);
           //                  $this->trans_model->update_next_ref(10,$refs['ref']);       
           //                  $this->cashier_model->update_trans_sales(array('trans_ref'=>$next_ref),$res->sales_id);     
           //              }
           //              else{
           //                  $next_ref = $this->trans_model->get_next_ref();
           //                  $refs=$this->trans_model->write_ref(10,$next_ref,$res->user_id);
           //                  $this->trans_model->update_next_ref(10,$refs['ref']);  
           //                  $this->cashier_model->update_trans_sales(array('trans_ref'=>$next_ref),$res->sales_id);     
           //              } 

           //          }
           //      }    
           //      $ctr++;
           // }
           // echo "done";
           $ctr = 1;
           $next = "";
           $print_str = "";
           $write_str = "";
           $update_str = "";
           foreach ($sales as $res) {
                if($ctr > 1){
                    // if($ctr <= 800){
                        if($ctr == 2){
                            $next = $this->next_ref(10,'00000009');
                            $write_string = $this->trans_model->write_ref_string(10,$next,$res->user_id);
                            $write_str .= $write_string;
                            // $update_string = $this->trans_model->update_next_ref_string(10,$next);    
                            $update_trans_ref = $this->trans_model->update_trans_ref_string(array('trans_ref'=>$next),$res->sales_id);     
                        }
                        else{
                            $next = $this->next_ref(10,$next);
                            $write_string=$this->trans_model->write_ref_string(10,$next,$res->user_id);
                            $write_str .= str_replace('INSERT INTO `trans_refs` (`type_id`, `trans_ref`, `user_id`) VALUES',"", $write_string);
                            // $update_string = $this->trans_model->update_next_ref_string(10,$next);  
                            $update_trans_ref = $this->trans_model->update_trans_ref_string(array('trans_ref'=>$next),$res->sales_id);     
                        } 
                         $write_str .= ",\r\n";
                         $update_str .= $update_trans_ref.";\r\n";
                        // $print_str .= $update_trans_ref.";\r\n\r\n";
                    }
                // }    
                $ctr++;
           }
           $print_str .= substr($write_str,0,-3).";\r\n";
           $print_str .= $update_str."\r\n";
           echo '<pre>'.$print_str.'</pre>';

            $filename = "sql_fix.sql";
            $fp = fopen($filename, "w+");
            fwrite($fp,$print_str);
            fclose($fp);
            echo "file created";
        }
        public function get_next_ref(){
            $this->load->model('core/trans_model');
            $sales = $this->trans_model->get_trans_sales(null,array("trans_ref"=>"00000009"),'ASC');
            $total = count($sales);
            
            $next = "";
            for($i=1;$i<=$total;$i++){
                if($i == 1)
                    $next = $this->next_ref(10,'00000009');
                else{
                    $next = $this->next_ref(10,$next);
                }
                // echo $next."\r\n";
            }
            echo $next;
        }
        public function next_ref($trans_type,$ref){
            if (preg_match('/^(\D*?)(\d+)(.*)/', $ref, $result) == 1) 
            {
                list($all, $prefix, $number, $postfix) = $result;
                $dig_count = strlen($number); // How many digits? eg. 0003 = 4
                $fmt = '%0' . $dig_count . 'd'; // Make a format string - leading zeroes
                $nextval =  sprintf($fmt, intval($number + 1)); // Add one on, and put prefix back on

                $new_ref=$prefix.$nextval.$postfix;
            }
            else 
                $new_ref=$ref;
            return $new_ref;
        }
}