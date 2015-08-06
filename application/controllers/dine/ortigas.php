<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ortigas extends CI_Controller {
	//TABLES USED 
	// 1. ortigas
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/ortigas_helper');
		$this->load->model('dine/cashier_model');
		$this->load->model('core/trans_model');
	}
	public function index(){
        $data = $this->syter->spawn(null);
        $data['code'] = ortigasPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/ortigas.php';
        $data['use_js'] = 'ortigasPageJs';
        $this->load->view('load',$data);
	}
	public function dailyFileRead(){
		$data = $this->syter->spawn(null);
		$reads = $this->site_model->get_tbl('ortigas_read_details',array(),array(),null,true,'*','read_date');
		$data['code'] = dailyFileReadPage($reads);
		// $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		// $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/ortigas.php';
		$data['use_js'] = 'dailyFileReadJS';
		$this->load->view('load',$data);
	}
	public function file_view($type='hourly'){
		$read_date = $_GET['date'];
		
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
	        $lastGT_ctr++;
		}
		$ext = str_pad(($lastGT_ctr+1), 3, "0",STR_PAD_LEFT);
		$file = $tenant_code.TERMINAL_NUMBER.$fd.".".$ext;
		$year = date('Y',strtotime($read_date));
		$month = date('M',strtotime($read_date));
		if($type == 'hourly'){
			$filename = "ortigas_files/hourly/".$year."/".$month."/"."H".$file;
		}
		else if($type == 'daily'){
			$filename = "ortigas_files/daily/".$year."/".$month."/"."D".$file;
		}
		else{
			$filename = "ortigas_files/invoice/".$year."/".$month."/"."I".$file;
		}
		if(file_exists($filename)){
			$myFile = $filename;
			$fh = fopen($myFile, 'r');
			$theData = fread($fh, filesize($myFile));
			fclose($fh);
			echo "<pre style='height:200px;overflow:auto;'>".$theData."</pre>";
		}
		else{
			echo "File not found.";
		}
	}		
	public function settings(){
		$data = $this->syter->spawn(null);
		$objs = $this->site_model->get_tbl('ortigas');
		$obj = array();
		if(count($objs) > 0){
			$obj = $objs[0];			
		}
		$data['code'] = ortigasSettingsPage($obj);
		$data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		$data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/ortigas.php';
		$data['use_js'] = 'ortigasSettingsJs';
		$this->load->view('load',$data);
	}
	public function settings_db(){
		$items = array(
			'tenant_code'=>$this->input->post('tenant_code'),
			'sales_type'=>$this->input->post('sales_type'),
		);
		if ($this->input->post('ortigas_id')) {
			$id = $this->input->post('ortigas_id');
			$this->site_model->update_tbl('ortigas','id',$items,$id);
			$msg = "Updated Settings.";
		} else {
			$id = $this->site_model->add_tbl('ortigas',$items);
			$msg = "Updated Settings";
		}
		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	public function daily_sales(){
		$data = $this->syter->spawn(null);
		$data['code'] = dailySalesPage();
		// $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		// $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/ortigas.php';
		$data['use_js'] = 'generateJs';
		$this->load->view('load',$data);
	}
	public function generate_daily_sales(){
		$date = $_GET['date'];
		/////////////////////////
		/////// GENERATE FILE NAME
			$File = "D";
			$objs = $this->site_model->get_tbl('ortigas');
			$obj = array();
			if(count($objs) > 0){
				$obj = $objs[0];			
			}
			$tenant_code = $obj->tenant_code;
			$fd = date('mdY',strtotime($date));
			$readCTR = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($date));
			$lastGT_ctr=0;
			if(count($readCTR) > 0){
			    foreach ($lastRead as $res) {
			        $lastGT_ctr++;
			    }           
			}
			$ext = str_pad(($lastGT_ctr+1), 3, "0",STR_PAD_LEFT);
			$File .= $tenant_code.TERMINAL_NUMBER.$fd.".".$ext;
		////////////////////////
		$lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));
		$old_gt_amnt = 0;
		$grand_total = 0;
		$read_date = $date;
		$date_to = $date;
		if(count($lastRead) == 0){
			$print_str = "no sales found.";
		}
		else{
		    foreach ($lastRead as $res) {
		        $date_from = $res->scope_from;
		        $date_to = $res->scope_to;
		        $old_gt_amnt = $res->old_total;
		        $grand_total = $res->grand_total;
		        $read_date = $res->read_date;
		        break;
		    }
		    $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
		    $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
		    #POPULATE 
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
			        }
			    }
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
			            $total_delivery_charges = 0;
			            $other_charges = 0;
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
			# PRODUCE FILE
			    $print_str = "";
			    # TENANT CODE
				    $print_str .= "01".$tenant_code."\r\n";
			    # POS TERMINAL NUMBER
				    $print_str .= "02".TERMINAL_NUMBER."\r\n";
			    # DATE
				    $date = date('mdY',strtotime($date));
				    $print_str .= "03".$date."\r\n";
			    # Old Accumulated Sales 
				    $print_str .= "04".$old_gt_amnt."\r\n";
			    # NEW Accumulated Sales 
				    $print_str .= "05".$grand_total."\r\n";
				# GROSS AMOUNT  
				    $print_str .= "06 GROSS AMOUNT\r\n";    
				# TOTAL DEDUCTIONS  
				    $print_str .= "07 TOTAL DEDUCTIONS\r\n";    
				# TOTAL PROMO SALES AMOUNT  
				    $print_str .= "08".num(0,2,'','')."\r\n";    
				# TOTAL PWD DISCOUNT  
				    $print_str .= "09".num($total_pwd_discs,2,'','')."\r\n";    
				# TOTAL REFUND AMOUNT
				    $print_str .= "10".num(0,2,'','')."\r\n";    
				# Total Returned Items Amount
				    $print_str .= "11".num(0,2,'','')."\r\n";    
				# Total Other Taxes
				    $print_str .= "12".num(0,2,'','')."\r\n";    
				# Total Service Charge Amount 
				    $print_str .= "13".num($total_service_charges,2,'','')."\r\n";    
				# Total Adjustment Discount 
				    $print_str .= "14".num(0,2,'','')."\r\n";    
				# Total Void Amount  
				    $print_str .= "15".num($total_void,2,'','')."\r\n";    
				# Total Discount Cards 
				    $print_str .= "16".num(0,2,'','')."\r\n";    
				# Total Delivery Charges 
				    $print_str .= "17".num($total_delivery_charges,2,'','')."\r\n"; 
				# Total Delivery Charges 
				    $print_str .= "17".num($total_delivery_charges,2,'','')."\r\n";        
		}
		echo "<pre>".$print_str."</pre>";
		// header("Content-Disposition: attachment; filename=\"" . basename($File) . "\"");
		// header("Content-Type: application/force-download");
		// // header("Content-Length: " . filesize($File));
		// header("Connection: close");
	}
	public function hourly_sales(){
		$data = $this->syter->spawn(null);
		$zreads = $this->site_model->get_tbl('read_details',array('read_type'=>Z_READ),array('read_date'=>'asc'));
		$data['code'] = hourlySalesPage($zreads);
		// $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		// $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/ortigas.php';
		$data['use_js'] = 'generateJs';
		$this->load->view('load',$data);
	}
	public function generate_hourly_sales(){
		$date = $this->input->post('date');
		/////////////////////////
		/////// GENERATE FILE NAME
			$File = "H";
			$objs = $this->site_model->get_tbl('ortigas');
			$obj = array();
			if(count($objs) > 0){
				$obj = $objs[0];			
			}
			$tenant_code = $obj->tenant_code;
			$fd = date('mdY',strtotime($date));
			$readCTR = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($date));
			$lastGT_ctr=0;
			if(count($readCTR) > 0){
			    foreach ($lastRead as $res) {
			        $lastGT_ctr++;
			    }           
			}
			$ext = str_pad(($lastGT_ctr+1), 3, "0",STR_PAD_LEFT);
			$File .= $tenant_code.TERMINAL_NUMBER.$fd.".".$ext;
		////////////////////////
		if (!file_exists("ortigas_files/hourly/")) {   
			mkdir("ortigas_files/hourly/", 0777, true);
		}	
		$filename = 'ortigas_files/hourly/'.$File;
		// if(!file_exists($filename)){
			$lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));
			$old_gt_amnt = 0;
			$grand_total = 0;
			$read_date = $date;
			$date_to = $date;
			$print_str = "";
			if(count($lastRead) == 0){
				$print_str = "no sales found.";
			}
			else{
			    foreach ($lastRead as $res) {
			        $date_from = $res->scope_from;
			        $date_to = $res->scope_to;
			        $old_gt_amnt = $res->old_total;
			        $grand_total = $res->grand_total;
			        $read_date = $res->read_date;
			        break;
			    }
			    $total_cover = $total_check = $total_sales = $total_count = 0;
			    $counter = 1;
			    $use_date = date('Y-m-d',strtotime($date_from));
			    $date_to = date('Y-m-d',strtotime($date_to));
				#TENANT CODE 
					$print_str .= "01".$tenant_code."\r\n";
			    # POS TERMINAL NUMBER
				    $print_str .= "02".TERMINAL_NUMBER."\r\n";
			    # DATE
				    $date = date('mdY',strtotime($date));
				    $print_str .= "03".$date."\r\n";	    

			    while (true) {
			        foreach(unserialize(TIMERANGES) as $k=>$v){
			            $net = $this->cashier_model->get_hourly_sales(null,$v['FTIME'],$v['TTIME'],$use_date,$use_date);
			            if (!empty($net)) {
			                $cover = (empty($net->sales_cover) ? 0 : $net->sales_cover);
			                $count = (empty($net->sales_count) ? 0 : $net->sales_count);
			                $check = (empty($net->sales_check) ? 0 : $net->sales_check);
			                $total = (empty($net->sales_total) ? 0 : $net->sales_total);

			                $avg_cover = round((empty($cover) ? 0 : $total/$cover),2);
			                $avg_check = round((empty($check) ? 0 : $total/$check),2);

			                # HOUR CODE 
			                	$print_str .= "04".date('G',strtotime($v['FTIME']))."\r\n";
			                # NET SALES 
			                	$print_str .= "05".num($total,2,'','')."\r\n";
			                # COUNT SALES 
			                	$print_str .= "06".num($count,2,'','')."\r\n";
			                # COVER SALES 
			                	$print_str .= "07".num($cover,2,'','')."\r\n";

			                // $print_str .= ($counter)." $use_date ".date('h:i A',strtotime($v['FTIME']))." - ".date('h:i A',strtotime($v['TTIME']))."\r\n"
			                //     .append_chars("Net Sales Total",'right',27," ").append_chars(number_format($total,2),'left',11," ")."\r\n"
			                //     .append_chars("Average $/Cover",'right',15," ").append_chars($cover,'left',11," ")
			                //         .append_chars(number_format($avg_cover,2),'left',12," ")."\r\n"
			                //     .append_chars("Average $/Check",'right',15," ").append_chars($check,'left',11," ")
			                //         .append_chars(number_format($avg_check,2),'left',12," ")."\r\n\r\n\r\n";

			                $total_cover += $cover;
			                $total_check += $check;
			                $total_sales += $total;
			                $total_count += $count;
			            } else {
			            	# HOUR CODE 
			                	$print_str .= "04".date('G',strtotime($v['FTIME']))."\r\n";
			                	$print_str .= "05".num(0,2,'','')."\r\n";
			                	$print_str .= "06".num(0,2,'','')."\r\n";
			                	$print_str .= "07".num(0,2,'','')."\r\n";
			                // $print_str .= $counter." ".date('Y-m-d',strtotime($use_date))." ".$v['FTIME']." - ".$v['TTIME']."\r\n"
			                //     .append_chars("Net Sales Total",'right',27," ").append_chars("0.00",'left',11," ")."\r\n"
			                //     .append_chars("Average $/Cover",'right',15," ").append_chars("0",'left',11," ")
			                //         .append_chars("0.00",'left',12," ")."\r\n"
			                //     .append_chars("Average $/Check",'right',15," ").append_chars("0.00",'left',11," ")
			                //         .append_chars("0.00",'left',12," ")."\r\n\r\n\r\n";
			            }
			            $counter++;
			        }
			        if (date('Y-m-d',strtotime($use_date)) == date('Y-m-d',strtotime($date_to))) {break;}
			        else {
			            $use_date = date('Y-m-d',strtotime($use_date." +1 day"));
			        }
			    }################ END OF WHILE
			    $print_str .= "08".num($total_sales,2,'','')."\r\n";
			    $print_str .= "09".num($total_count,2,'','')."\r\n";

				#################################
			}
	        $fp = fopen($filename, "w+");
	        fwrite($fp,$print_str);
	        fclose($fp);
	        echo $File."  Generated.";
		// }
        // header('Content-Type: application/download');
        // header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        // header("Content-Length: " . filesize($filename));
        // $fp = fopen($filename, "r");
        // fpassthru($fp);
        // fclose($fp);
	}
	public function invoice_sales(){
		$data = $this->syter->spawn(null);
		$zreads = $this->site_model->get_tbl('read_details',array('read_type'=>Z_READ),array('read_date'=>'asc'));
		$data['code'] = invoiceSalesPage($zreads);
		// $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
		// $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
		$data['load_js'] = 'dine/ortigas.php';
		$data['use_js'] = 'generateJs';
		$this->load->view('load',$data);
	}
	public function generate_invoice_sales(){
		$date = $this->input->post('date');
		/////////////////////////
		/////// GENERATE FILE NAME
			$File = "I";
			$objs = $this->site_model->get_tbl('ortigas');
			$obj = array();
			if(count($objs) > 0){
				$obj = $objs[0];			
			}
			$tenant_code = $obj->tenant_code;
			$fd = date('mdY',strtotime($date));
			$readCTR = $this->cashier_model->get_lastest_z_read(Z_READ,date2Sql($date));
			$lastGT_ctr=0;
			if(count($readCTR) > 0){
			    foreach ($lastRead as $res) {
			        $lastGT_ctr++;
			    }           
			}
			$ext = str_pad(($lastGT_ctr+1), 3, "0",STR_PAD_LEFT);
			$File .= $tenant_code.TERMINAL_NUMBER.$fd.".".$ext;
		////////////////////////
		$lastRead = $this->cashier_model->get_last_z_read_on_date(Z_READ,date2Sql($date));
		$old_gt_amnt = 0;
		$grand_total = 0;
		$read_date = $date;
		$date_to = $date;
		if(count($lastRead) == 0){
			echo "No sales found.";
		}
		else{
		    foreach ($lastRead as $res) {
		        $date_from = $res->scope_from;
		        $date_to = $res->scope_to;
		        $old_gt_amnt = $res->old_total;
		        $grand_total = $res->grand_total;
		        $read_date = $res->read_date;
		        break;
		    }
		    if (!file_exists("ortigas_files/invoice/")) {   
		    	mkdir("ortigas_files/invoice/", 0777, true);
		    }	
		    $filename = 'ortigas_files/invoice/'.$File;
		    $args["trans_sales.datetime  BETWEEN '".$date_from."' AND '".$date_to."'"] = array('use'=>'where','val'=>null,'third'=>false);
		    $trans_sales = $this->cashier_model->get_trans_sales(null,$args);
	    	$print_str = "";
	    	#TENANT CODE 
	    		$print_str .= "01".$tenant_code."\r\n";
	        # POS TERMINAL NUMBER
	    	    $print_str .= "02".$obj->sales_type."\r\n";
	        # DATE
	    	    $date = date('mdY',strtotime($date));
	    	    $print_str .= "03".$date."\r\n";
	    	# POS TERMINAL NUMBER
	    	    $print_str .= "04".TERMINAL_NUMBER."\r\n";
		    foreach ($trans_sales as $sale) {
		        if($sale->type_id == 10){
		            if($sale->trans_ref != "" && $sale->inactive == 0){
		                #invoice number 
		                	$print_str .= "05".$sale->trans_ref."\r\n";
		                #NET SALES
		                	$print_str .= "05".$sale->total_amount."\r\n";
		                #STATUS
		                	$print_str .= "0701\r\n";
		            }
		        }
		        else{
		            #invoice number 
	                	$print_str .= "05".$sale->void_ref."\r\n";
	                #NET SALES
	                	$print_str .= "05".$sale->total_amount."\r\n";
	                #STATUS
	                	$print_str .= "0704\r\n";
		        }
		    }
		    $fp = fopen($filename, "w+");
	        fwrite($fp,$print_str);
	        fclose($fp);
	        echo $File."  Generated.";
		    ########################################################################
	   }
	   ###############################################################
	}
}