<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prints extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('dine/print_helper');
        $this->load->helper('core/string_helper');

        $this->load->model('dine/cashier_model');
        $this->load->model('dine/setup_model');
        $this->load->model('dine/menu_model');
        $this->load->model('dine/manager_model');
    }
    public function index(){
        $data['code'] = mainPage();
        $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/prints';
        $data['use_js'] = 'mainPageJS';
        $this->load->view('load',$data);
    }
    ##############
    #### PAGES
        public function date_and_time(){
            $data['code'] = datetimePage();
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'datetimeJS';
            $this->load->view('load',$data);
        }
        public function shift_sales(){
            $today = $this->site_model->get_db_now();
            $data['code'] = shiftsPage($today);
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'sfhitJS';
            $this->load->view('load',$data);
        }
        public function get_shifts(){
            $date = $this->input->post('calendar');
            $args["DATE(shifts.check_in) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
            $join['users'] = array('content'=>'shifts.user_id = users.id');
            $select = "shifts.*,users.fname,users.lname,users.mname,users.suffix,users.username";
            $results = $this->site_model->get_tbl('shifts',$args,array('check_in'=>'desc'),$join,true,$select);
            $shifts = array();
            foreach ($results as $res) {
                $in = "";
                if($res->check_in != "")
                    $in = toTime($res->check_in);

                $out = "";
                if($res->check_out != "")
                    $out = toTime($res->check_out);
                $shifts[$res->shift_id] = array(
                                                // 'name'=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,
                                                'name'=>$res->username,
                                                'in'=>$in,
                                                'out'=>$out,
                                                );
            }
            $code = "";
            $this->make->sDiv(array('class'=>'listings'));
                $this->make->sUl();
                foreach ($shifts as $id => $sh) {
                    // $color = 'teal';
                    // if($sh['out'] == "")
                    //     $color = 'orange';
                    // $this->make->sDivCol(6);
                    //     $this->make->sDiv(array('style'=>'margin:5px;'));
                    //     $this->make->sBox('solid',array('ref'=>$id,'id'=>'shift-box-'.$id,'class'=>'shift-box bg-'.$color,'style'=>'cursor:pointer;-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;padding:0px;'));
                    //         $this->make->sBoxBody();
                    //             $this->make->H(5,fa('fa-user')." ".$sh['name']);
                    //             $txt = $sh['in'];
                    //             if($sh['out'] != "")
                    //                 $txt.= " - ".$sh['out'];
                    //             $this->make->H(5,fa('fa-clock-o')." ".$txt);
                    //         $this->make->eBoxBody();
                    //     $this->make->eBox();
                    //     $this->make->eDiv();
                    // $this->make->eDivCol();
                    $this->make->sLi(array('ref'=>$id,'id'=>'shift-box-'.$id,'class'=>'shift-box bg-white','style'=>'cursor:pointer;padding:5px;padding-bottom:15px;padding-top:15px;border-bottom:1px solid #ddd;'));
                        $txt = fa('fa-clock-o')." ".$sh['in'];
                        if($sh['out'] != "")
                            $txt.= " - ".$sh['out'];
                        $this->make->H(5,fa('fa-user')." ".strtoupper($sh['name'])."<span class='pull-right'>".$txt."</span>",array('class'=>'name','style'=>'margin:0;padding:0;'));            
                    $this->make->eLi(); 
                }
                $this->make->eUl();
            $this->make->eDiv();
            $code = $this->make->code();
            echo json_encode(array('code'=>$code,'shifts'=>$shifts));
        }
        public function end_day_sales(){
            $today = date('m/d/Y',strtotime($this->site_model->get_db_now()." -1 days") );
            $data['code'] = dayReadsPage($today);
            $data['load_js'] = 'dine/prints';
            $data['use_js'] = 'dayReadsJS';
            $this->load->view('load',$data);
        }
    ##############
    #### REPORTS
        public function list_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $settled = $trans['sales']['settled']['orders'];
            usort($settled, function($a, $b) {
                return $a->trans_ref - $b->trans_ref;
            });

            $title_name = "Transactions List";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";

            $print_str .= "\r\n";
            // $total_vat = 0;
            // $total_taxable = 0;
            foreach ($settled as $key => $set){
                // $trans_menus = $this->menu_sales($sales_id,$curr);
                $trans_charges = $this->charges_sales($set->sales_id,$curr);
                $trans_discounts = $this->discounts_sales($set->sales_id,$curr);
                $trans_local_tax = $this->local_tax_sales($set->sales_id,$curr);
                $trans_tax = $this->tax_sales($set->sales_id,$curr);
                $trans_no_tax = $this->no_tax_sales($set->sales_id,$curr);
                $trans_zero_rated = $this->zero_rated_sales($set->sales_id,$curr);

                $print_str .= align_center($set->trans_ref,38," ")."\r\n";
                $print_str .= align_center($set->datetime,38," ")."\r\n";

                $net = $set->total_amount;
                $charges = $trans_charges['total']; 
                $discounts = $trans_discounts['total']; 
                $tax_disc = $trans_discounts['tax_disc_total']; 
                $no_tax_disc = $trans_discounts['no_tax_disc_total']; 
                $local_tax = $trans_local_tax['total']; 

                $tax = $trans_tax['total'];
                $no_tax = $trans_no_tax['total'];

                $zero_rated = $trans_zero_rated['total'];
                $no_tax -= $zero_rated;
                $net_no_adds = ($net)-$charges-$local_tax;

                $taxable = ( ($net_no_adds + $no_tax_disc) - ($tax + $no_tax)); 
                $print_str .= append_chars(substrwords('VAT SALES',18,""),"right",23," ")
                             .append_chars(numInt(($taxable)),"left",13," ")."\r\n";
                $total_net = $taxable + $no_tax + $zero_rated + $tax;
                $print_str .= append_chars(substrwords('VAT EXEMPT SALES',18,""),"right",23," ")
                             .append_chars(numInt(($no_tax)),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('ZERO RATED',13,""),"right",23," ")
                             .append_chars(numInt(($zero_rated)),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('VAT',18,""),"right",23," ")
                                         .append_chars(numInt(($tax)),"left",13," ")."\r\n";   
                $print_str .= append_chars("","right",23," ").append_chars("-----------","left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('Total',18,""),"right",23," ")
                                         .append_chars(numInt(($total_net)),"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('Charges',18,""),"right",23," ")
                                         .append_chars(numInt(($charges)),"left",13," ")."\r\n";                         
                $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
                                         .append_chars(numInt(($local_tax)),"left",13," ")."\r\n";                         
                $print_str .= append_chars(substrwords('Discounts',18,""),"right",23," ")
                                         .append_chars(numInt(($discounts)),"left",13," ")."\r\n";                         
                $print_str .= "======================================"."\r\n";
                $print_str .= append_chars(substrwords('NET SALES',18,""),"right",23," ")
                             .append_chars(numInt(($set->total_amount)),"left",13," ")."\r\n";                         

                $print_str .= "\r\n";
                // $total_vat += $tax;
                // $total_taxable += $taxable;
            }   
            // $print_str .= append_chars(substrwords('VAT',18,""),"right",23," ")
            //  .append_chars(numInt(($total_vat)),"left",13," ")."\r\n";                         
            // $print_str .= append_chars(substrwords('TAXABLE',18,""),"right",23," ")
            //  .append_chars(numInt(($total_taxable)),"left",13," ")."\r\n";                         

            ########### 
            $this->do_print($print_str,$asJson);
        }    
        public function void_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_removes = $this->removed_menu_sales($trans['all_ids'],$curr);
            $void = array();
            $cancel = array();
            if(isset($trans['sales']['void']['orders']) && count($trans['sales']['void']['orders']) > 0)
                $void = $trans['sales']['void']['orders'];
            if(isset($trans['sales']['cancel']['orders']) && count($trans['sales']['cancel']['orders']) > 0)
                $cancel = $trans['sales']['cancel']['orders'];

            $title_name = "VOID SALES REPORT";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";


            $print_str .= "\r\n".append_chars(substrwords('Voided Receipts',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";    
            $print_str .= "======================================"."\r\n";   
            $total_void_sales = 0;   
            if(count($void) > 0){
                foreach ($void as $v) {
                    $order = $trans['all_orders'];
                    $print_str .= append_chars(substrwords($v->trans_ref,18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($v->total_amount),"left",13," ")."\r\n";
                    if(isset($order[$v->void_ref])){
                        $ord = $order[$v->void_ref];
                        $print_str .= append_chars(substrwords("Receipt: ",18,""),"right",12," ").align_center($ord->trans_ref,10," ")
                                 .append_chars('',"left",13," ")."\r\n";
                    }             
                    if($v->table_name != ""){
                        $print_str .= append_chars(substrwords("Table: ",18,""),"right",12," ").align_center($v->table_name,10," ")
                                 .append_chars('',"left",13," ")."\r\n";
                    }
                    $server = $this->manager_model->get_server_details($v->user_id);
                    $cashier = $server[0]->username;       
                    $print_str .= append_chars(substrwords("Cashier: ",18,""),"right",12," ").align_center($cashier,10," ")
                             .append_chars('',"left",13," ")."\r\n";
                    if(isset($order[$v->void_ref])){
                        $ord = $order[$v->void_ref];
                        $print_str .= append_chars(substrwords("Reason: ",18,""),"right",12," ").align_center('',5," ")
                                 .append_chars('',"left",13," ")."\r\n";
                        $print_str .= append_chars("--".$ord->reason,"right",12," ").align_center('',5," ")
                                 .append_chars('',"left",13," ")."\r\n";
                        if($ord->void_user_id != ""){
                            $server = $this->manager_model->get_server_details($ord->void_user_id);
                            $voider = $server[0]->username;       
                            $print_str .= append_chars(substrwords("Approved By: ",18,""),"right",12," ").align_center($voider,10," ")
                                     .append_chars('',"left",13," ")."\r\n";
                        }
                    }
                    $total_void_sales += $v->total_amount;
                }                
            }  
            else{
                $print_str .= append_chars(substrwords("No Sales Found.",18,""),"right",18," ").align_center('',5," ")."\r\n";
            } 
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords("Total ",18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(numInt($total_void_sales),"left",13," ")."\r\n";

            $print_str .= "\r\n".append_chars(substrwords('Cancelled Orders',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";    
            $print_str .= "======================================"."\r\n"; 
            $total_void_sales = 0;
            if(count($cancel) > 0){
                foreach ($cancel as $v) {
                    $print_str .= append_chars(substrwords("Order #".$v->sales_id,18,""),"right",18," ").align_center('',5," ")
                             .append_chars(numInt($v->total_amount),"left",13," ")."\r\n";
                    $server = $this->manager_model->get_server_details($v->user_id);
                    $cashier = $server[0]->username;       
                    $print_str .= append_chars(substrwords("Cashier: ",18,""),"right",12," ").align_center($cashier,10," ")
                             .append_chars('',"left",13," ")."\r\n";

                    if($v->table_name != ""){
                        $print_str .= append_chars(substrwords("Table: ",18,""),"right",12," ").align_center($v->table_name,10," ")
                                 .append_chars('',"left",13," ")."\r\n";
                    }
                    $print_str .= append_chars(substrwords("Reason: ",18,""),"right",12," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                    $print_str .= append_chars("--".$v->reason,"right",12," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                    if($v->void_user_id != ""){
                        $server = $this->manager_model->get_server_details($v->void_user_id);
                        $voider = $server[0]->username;       
                        $print_str .= append_chars(substrwords("Approved By: ",18,""),"right",12," ").align_center($voider,10," ")
                                 .append_chars('',"left",13," ")."\r\n";
                    }

                    $total_void_sales += $v->total_amount;
                }                
            }  
            else{
                $print_str .= append_chars(substrwords("No Sales Found. ",18,""),"right",18," ").align_center('',5," ")."\r\n";
            }         
            $print_str .= "-----------------"."\r\n";
            $print_str .= append_chars(substrwords("Total ",18,""),"right",18," ").align_center('',5," ")
                                 .append_chars(numInt($total_void_sales),"left",13," ")."\r\n";

            $print_str .= "\r\n".append_chars(substrwords('Removed Items',18,""),"right",18," ").align_center(null,5," ")
                       .append_chars(null,"left",13," ")."\r\n";    
            $print_str .= "-----------------"."\r\n";
            
            if(count($trans_removes) > 0){
                foreach ($trans_removes as $v) {
                    $print_str .= append_chars(substrwords("Order #".$v['trans_id'],18,""),"right",18," ").align_center('',5," ")
                             .append_chars(null,"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords("Cashier: ",18,""),"right",12," ").align_center($v['cashier'],10," ")
                             .append_chars('',"left",13," ")."\r\n";
                    $print_str .= append_chars(substrwords("*".$v['item'],18,""),"right",18," ").align_center('',5," ")
                             .append_chars("","left",13," ")."\r\n";
                    $print_str .= " ".urldecode($v['reason'])."\r\n";         
                    $print_str .= append_chars(substrwords("Approved By: ",18,""),"right",12," ").align_center($v['manager'],10," ")
                             .append_chars('',"left",13," ")."\r\n";
                             
                }                
            }  
            else{
                $print_str .= append_chars(substrwords("No Menus Found. ",18,""),"right",18," ").align_center('',5," ");
            }
            $this->do_print($print_str,$asJson);
        }    
        public function menu_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            $gross = $trans_menus['gross']; 
            $net = $trans['net'];
            $charges = $trans_charges['total']; 
            $discounts = $trans_discounts['total']; 
            $local_tax = $trans_local_tax['total']; 
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            
            $title_name = "MENU ITEM SALES REPORT";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";

            $print_str .= "\r\n"; 
            #CATEGORIES
                $cats = $trans_menus['cats'];                 
                $menus = $trans_menus['menus'];
                $menu_total = $trans_menus['menu_total'];
                $total_qty = $trans_menus['total_qty'];
                usort($cats, function($a, $b) {
                    return $b['amount'] - $a['amount'];
                });
                foreach ($cats as $cat_id => $ca) {
                    if($ca['qty'] > 0){
                        $print_str .=
                             append_chars($ca['name'],'right',18," ")
                            .append_chars(num($ca['qty']),'right',10," ")
                            .append_chars(num($ca['amount']).'','left',10," ")."\r\n";
                        $print_str .= "======================================"."\r\n";    
                        foreach ($menus as $menu_id => $res) {
                            if($ca['cat_id'] == $res['cat_id']){
                            $print_str .=
                                append_chars($res['name'],'right',18," ")
                                .append_chars(num($res['qty']),'right',10," ")
                                .append_chars(num( ($res['qty'] / $total_qty) * 100).'%','left',10," ")."\r\n";
                            $print_str .=
                                append_chars(null,'right',18," ")
                                .append_chars(num($res['amount']),'right',10," ")
                                .append_chars(num( ($res['amount'] / $menu_total) * 100).'%','left',10," ")."\r\n";
                            }
                        }
                    $print_str .= "======================================"."\r\n";    
                    }
                }
            #SUBCATEGORIES    
            $print_str .= "\r\n"; 
                $subcats = $trans_menus['sub_cats'];                 
                $print_str .= "======================================"."\r\n";    
                $print_str .= append_chars('SUBCATEGORIES:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $print_str .= "======================================"."\r\n";    
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",18," ").align_center($val['qty'],7," ")
                               .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }      
            #MODIFIERS    
            $print_str .= "\r\n"; 
                $mods = $trans_menus['mods'];                 
                $print_str .= "======================================"."\r\n";    
                $print_str .= append_chars('MODIFIERS:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $print_str .= "======================================"."\r\n";    
                $qty = 0;
                $total = 0;
                foreach ($mods as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",18," ").align_center($val['qty'],7," ")
                               .append_chars(numInt($val['total_amt']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['total_amt'];
                 }             

            $print_str .= "\r\n======================================"."\r\n";
            $net_no_adds = $net-$charges-$local_tax;
            $print_str .= append_chars(substrwords('TOTAL SALES',18,""),"right",25," ")
                         .append_chars(numInt(($net)),"left",13," ")."\r\n";
            $txt = numInt(($charges));
            if($charges > 0)
                $txt = "(".numInt(($charges)).")";
            $print_str .= append_chars(substrwords('Charges',18,""),"right",25," ")
                         .append_chars($txt,"left",13," ")."\r\n";

            $txt = numInt(($local_tax));
            if($local_tax > 0)
                $txt = "(".numInt(($local_tax)).")";
            $print_str .= append_chars(substrwords('Local Tax',18,""),"right",25," ")
                         .append_chars($txt,"left",13," ")."\r\n";                          
            $print_str .= append_chars(substrwords('Discounts',18,""),"right",25," ")
                         .append_chars(numInt(($discounts)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('LESS VAT',18,""),"right",25," ")
                         .append_chars(numInt(($less_vat)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('GROSS SALES',18,""),"right",25," ")
                         .append_chars(numInt(($gross)),"left",13," ")."\r\n";
            $print_str .= "======================================"."\r\n";

            $this->do_print($print_str,$asJson);
        }    
        public function system_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            $tax_disc = $trans_discounts['tax_disc_total']; 
            $no_tax_disc = $trans_discounts['no_tax_disc_total']; 
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            
            
            
            $gross = $trans_menus['gross']; 
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total']; 
            $discounts = $trans_discounts['total']; 
            $local_tax = $trans_local_tax['total']; 
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            // $less_vat  = $no_tax_disc * 0.60;
            if($less_vat < 0)
                $less_vat = 0;

            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            
            $title_name = "SYSTEM SALES REPORT";
            if($post['title'] != "")
                $title_name = $post['title'];

            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";
            
            $loc_txt = numInt(($local_tax));
            // if($local_tax > 0)
            //     $loc_txt = "(".numInt(($local_tax)).")";
            $net_no_adds = $net-($charges+$local_tax);
            $nontaxable = $no_tax - $no_tax_disc;
            $taxable =   ($net_no_adds - ($tax + ($nontaxable+$zero_rated))  );
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            // $taxable = ($net_no_adds - ($tax + $no_tax + $zero_rated)); 
            $print_str .= "\r\n";
            $nsss = $taxable +  $nontaxable +  $zero_rated; 
            $print_str .= append_chars(substrwords('NET SALES',18,""),"right",23," ")
                         .append_chars(numInt(($nsss)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('VAT',18,""),"right",23," ")
                                     .append_chars(numInt($tax),"left",13," ")."\r\n";  
            $print_str .= append_chars(substrwords('Local Tax',18,""),"right",23," ")
                         .append_chars($loc_txt,"left",13," ")."\r\n";  
            $print_str .= append_chars("","right",23," ").append_chars("-----------","left",13," ")."\r\n";                         
            $print_str .= append_chars(substrwords('GROSS SALES',18,""),"right",23," ")
                                     .append_chars(numInt(($total_net)),"left",13," ")."\r\n";   
            $print_str .= append_chars(substrwords('VOID SALES',18,""),"right",23," ")
                         .append_chars(numInt(($void)),"left",13," ")."\r\n";

            

            $print_str .= "\r\n";
            #TRANS COUNT
                $types = $trans['types']; 
                $types_total = array();
                $guestCount = 0;
                foreach ($types as $type => $tp) {
                    foreach ($tp as $id => $opt){
                        if(isset($types_total[$type])){
                            $types_total[$type] += $opt->total_amount;
                        
                        }
                        else{
                            $types_total[$type] = $opt->total_amount;
                        }
                        if($opt->guest == 0)
                            $guestCount += 1;
                        else
                            $guestCount += $opt->guest;
                    }    
                }
                $print_str .= append_chars(substrwords('Trans Count:',18,""),"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $tc_total  = 0;
                $tc_qty = 0;
                foreach ($types_total as $typ => $tamnt) {
                    $print_str .= append_chars(substrwords($typ,18,""),"right",18," ").align_center(count($types[$typ]),5," ")
                                 .append_chars(numInt($tamnt),"left",13," ")."\r\n";             
                    $tc_total += $tamnt; 
                    $tc_qty += count($types[$typ]);
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('TC Total',18,""),"right",23," ")
                             .append_chars(numInt($tc_total),"left",13," ")."\r\n";
                $print_str .= append_chars(substrwords('GUEST Total',18,""),"right",23," ")
                             .append_chars(numInt($guestCount),"left",13," ")."\r\n";
                if($tc_total == 0 || $tc_qty == 0)
                    $avg = 0;
                else
                    $avg = $tc_total/$tc_qty;             
                $print_str .= append_chars(substrwords('AVG Check',18,""),"right",23," ")
                             .append_chars(numInt($avg),"left",13," ")."\r\n"; 
            $print_str .= "\r\n";
            #CHARGES
                $types = $trans_charges['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Charges:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n"; 
                foreach ($types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Charges',18,""),"right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($charges),"left",13," ")."\r\n"; 
            $print_str .= "\r\n";
            #Discounts
                $types = $trans_discounts['types'];
                $qty = 0;
                $print_str .= append_chars(substrwords('Discounts:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n"; 
                foreach ($types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Discounts',18,""),"right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($discounts),"left",13," ")."\r\n"; 
                $print_str .= append_chars(substrwords('VAT EXEMPT',18,""),"right",23," ")
                                         .append_chars(numInt($less_vat),"left",13," ")."\r\n";                
            $print_str .= "\r\n";
            #PAYMENTS
                $payments_types = $payments['types'];
                $payments_total = $payments['total'];
                $pay_qty = 0;
                $print_str .= append_chars(substrwords('Payment Breakdown:',18,""),"right",18," ").align_center(null,5," ")
                              .append_chars(null,"left",13," ")."\r\n"; 
                foreach ($payments_types as $code => $val) {
                    $print_str .= append_chars(substrwords(ucwords(strtolower($code)),18,""),"right",18," ").align_center($val['qty'],5," ")
                                  .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $pay_qty += $val['qty'];
                }
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars(substrwords('Total Payments',18,""),"right",18," ").align_center($pay_qty,5," ")
                              .append_chars(numInt($payments_total),"left",13," ")."\r\n"; 
            $print_str .= "\r\n";
            #CATEGORIES
                $cats = $trans_menus['cats'];                 
                $print_str .= append_chars('Menu Categories:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($cats as $id => $val) {
                    $print_str .= append_chars(substrwords($val['name'],18,""),"right",18," ").align_center($val['qty'],5," ")
                               .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }             
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("SubTotal","right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($total),"left",13," ")."\r\n";
                $print_str .= append_chars("Modifiers Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($trans_menus['mods_total']),"left",13," ")."\r\n";
                $print_str .= append_chars("Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($total+$trans_menus['mods_total']),"left",13," ")."\r\n";                            
            $print_str .= "\r\n";                  
            #SUBCATEGORIES
                $subcats = $trans_menus['sub_cats'];                 
                $print_str .= append_chars('Menu Subcategories:',"right",18," ").align_center('',5," ")
                             .append_chars('',"left",13," ")."\r\n";
                $qty = 0;
                $total = 0;
                foreach ($subcats as $id => $val) {
                    $print_str .= append_chars($val['name'],"right",18," ").align_center($val['qty'],5," ")
                               .append_chars(numInt($val['amount']),"left",13," ")."\r\n";
                    $qty += $val['qty'];
                    $total += $val['amount'];
                 }             
                $print_str .= "-----------------"."\r\n";
                $print_str .= append_chars("SubTotal","right",18," ").align_center($qty,5," ")
                              .append_chars(numInt($total),"left",13," ")."\r\n";
                $print_str .= append_chars("Modifiers Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($trans_menus['mods_total']),"left",13," ")."\r\n";
                $print_str .= append_chars("Total","right",18," ").align_center('',5," ")
                              .append_chars(numInt($total+$trans_menus['mods_total']),"left",13," ")."\r\n";               


            $print_str .= "\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= append_chars(substrwords('VATABLE SALES',18,""),"right",23," ")
                         .append_chars(numInt(($taxable)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('VAT EXEMPT SALES',18,""),"right",23," ")
                         .append_chars(numInt(($nontaxable)),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('ZERO RATED',13,""),"right",23," ")
                         .append_chars(numInt(($zero_rated)),"left",13," ")."\r\n";
            $print_str .= append_chars("","right",23," ").append_chars("-----------","left",13," ")."\r\n";     
            $print_str .= append_chars(substrwords('NET SALES',18,""),"right",23," ")
                                     .append_chars(numInt(($nsss)),"left",13," ")."\r\n";                             
            $print_str .= "\r\n";
            $print_str .= append_chars(substrwords('Invoice Start: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars(iSetObj($trans['first_ref'],'trans_ref'),"left",13," ")."\r\n";
            $print_str .= append_chars(substrwords('Invoice End: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars(iSetObj($trans['last_ref'],'trans_ref'),"left",13," ")."\r\n";    
            $print_str .= append_chars(substrwords('Invoice Ctr: ',18,""),"right",18," ").align_center('',5," ")
                         .append_chars($trans['ref_count'],"left",13," ")."\r\n";  
            if($title_name == "ZREAD"){
                // $gt = $this->old_grand_total($post['from']);
                $gt = $this->old_grand_net_total($post['from']);
                $print_str .= "\r\n";
                $print_str .= append_chars(substrwords('OLD GT: ',18,""),"right",18," ").align_center('',5," ")
                             // .append_chars(numInt( $gt['old_grand_total'] - ($charges + $local_tax) ),"left",13," ")."\r\n";    
                             .append_chars(numInt( $gt['old_grand_total']),"left",13," ")."\r\n";    
                $print_str .= append_chars(substrwords('NEW GT: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars( numInt($gt['old_grand_total']+$net_no_adds)  ,"left",13," ")."\r\n";                  
                             // .append_chars( numInt($gt['old_grand_total']+$net)  ,"left",13," ")."\r\n";                  
                             // .append_chars( numInt($gt['old_grand_total']+$gross)  ,"left",13," ")."\r\n";                  
                $print_str .= append_chars(substrwords('Z READ CTR: ',18,""),"right",18," ").align_center('',5," ")
                             .append_chars( $gt['ctr'] ,"left",13," ")."\r\n";                  
            }

            $print_str .= "======================================"."\r\n";
                                      


            $this->do_print($print_str,$asJson);
        }
        public function hourly_sales_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
            $curr = $this->search_current();
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            $net = $trans['net'];
            
            $title_name = "HOURLY SALES REPORT";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            $print_str .= align_center(sql2DateTime($post['from'])." - ".sql2DateTime($post['to']),38," ")."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";


            
            $ranges = array();
            foreach (unserialize(TIMERANGES) as $ctr => $time) {
                $key = date('H',strtotime($time['FTIME']));
                $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'net'=>0);
            }
            
            $dates = array();
            if(count($sales['settled']['orders']) > 0){
                foreach ($sales['settled']['orders'] as $sales_id => $val) {
                    $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
                }
                foreach ($sales['settled']['orders'] as $sales_id => $val) {
                    if(isset($dates[date2Sql($val->datetime)])){
                        $date_arr = $dates[date2Sql($val->datetime)];
                        $range = $date_arr['ranges'];
                        $H = date('H',strtotime($val->datetime));
                        if(isset($range[$H])){
                            $r = $range[$H];
                            $r['tc'] += 1;
                            $r['net'] += $val->total_amount;
                            $range[$H] = $r;
                        } 
                        $dates[date2Sql($val->datetime)]['ranges'] = $range;
                    }
                }
            }

            $ctr = 0;
            foreach ($dates as $date => $val) {
                $print_str .= align_center(sql2Date($date),38," ")."\r\n"; 
                $ranges = $val['ranges'];
                $print_str .= append_chars(substrwords("",18,""),"right",13," ")
                             .append_chars(substrwords('TC',12,""),"right",7," ")
                             .append_chars(substrwords('NET',12,""),"right",11," ")
                             .append_chars(substrwords('AVG',12,""),"right",11," ")."\r\n";
                foreach ($ranges as $key => $ran) {
                    if($ran['tc'] == 0 || $ran['net'] == 0)
                        $avg = 0;
                    else
                        $avg = $ran['net']/$ran['tc'];
                    $ctr += $ran['tc'];
                    $print_str .= append_chars(substrwords($ran['start']."-".$ran['end'],18,""),"right",13," ")
                                 .append_chars(substrwords($ran['tc'],12,""),"right",7," ")
                                 .append_chars(substrwords(numInt($ran['net']),12,""),"right",11," ")
                                 .append_chars(substrwords(numInt($avg),12,""),"right",11," ")."\r\n";
                }
                $print_str .= "\r\n";
            }
            $print_str .= "======================================"."\r\n";
            if($ctr == 0 || $net == 0)
                $tavg = 0;
            else
                $tavg = $net/$ctr;
            $print_str .= append_chars(substrwords("TOTAL",18,""),"right",13," ")
                         .append_chars(substrwords($ctr,12,""),"right",7," ")
                         .append_chars(substrwords(numInt($net),12,""),"right",11," ")
                         .append_chars(substrwords(numInt($tavg),12,""),"right",11," ")."\r\n";             
            $print_str .= "======================================"."\r\n";

            $this->do_print($print_str,$asJson);
        }
        public function cash_count_rep($asJson=false){
            $print_str = $this->print_header();
            $user = $this->session->userdata('user');
            $time = $this->site_model->get_db_now();
            $post = $this->set_post();
                        
            $title_name = "Cash Count";
            $print_str .= align_center($title_name,38," ")."\r\n";
            $print_str .= align_center("TERMINAL ".$post['terminal'],38," ")."\r\n";
            $print_str .= append_chars('Printed On','right',11," ").append_chars(": ".date2SqlDateTime($time),'right',19," ")."\r\n";
            $print_str .= append_chars('Printed BY','right',11," ").append_chars(": ".$user['full_name'],'right',19," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            if($post['employee'] != "All")
                $print_str .= align_center($post['employee'],38," ")."\r\n";
            $shit_id = $post['shift_id'];
            $totals = $this->shift_entries($shit_id);
            $cashout_id = $this->shift_cashout($shit_id);
            $print_str = $this->print_cashout_details($print_str,$totals,$cashout_id);

            // echo "<pre style='background-color:#fff'>$print_str</pre>";
            $this->do_print($print_str,$asJson); 
        }
        public function print_cashout_details($print_str="",$totals,$cashout_id){
            $cashout_header = $this->cashier_model->get_cashout_header($cashout_id); // returns row
            $cashout_details = $this->cashier_model->get_cashout_details($cashout_id); // returns rows array
            
            $sum_deps = $sum_withs = 0;

            /* Cash Deposits */
            $print_str .= "Cash Deposits\r\n";
            foreach ($totals['total_deps'] as $k => $dep) {
                $print_str .= append_chars("   ".($k+1),'right',8," ").append_chars(date('H:i:s',strtotime($dep->trans_date)),"right",13," ")
                    .append_chars(number_format($dep->amount,2),"left",15," ")."\r\n";
                $sum_deps += $dep->amount;
            }
            if ($sum_deps > 0)
                $print_str .= append_chars("------------","left",36," ")."\r\n";
            $print_str .= append_chars("Total Cash Deposits","right",21," ")
                .append_chars(number_format($sum_deps,2),"left",15," ")."\r\n\r\n";

            /* Cash Withdrawals */
            $print_str .= "Cash Withdrawals\r\n";
            foreach ($totals['total_withs'] as $k => $with) {
                $print_str .= append_chars("   ".($k+1)." ".date('H:i:s',strtotime($with->trans_date)),"right",21," ")
                    .append_chars(number_format(abs($with->amount),2),"left",15," ")."\r\n";
                $sum_withs += abs($with->amount);
            }
            if ($sum_withs > 0)
                $print_str .= append_chars("------------","left",36," ")."\r\n";
            $print_str .= append_chars("Total Cash Withdrawals","right",25," ")
                .append_chars(number_format($sum_withs,2),"left",11," ")."\r\n\r\n";


            /* Drawer */
            $print_str .= append_chars("Expected Drawer amount","right",25," ").append_chars(number_format($cashout_header->drawer_amount,2),"left",11," ")."\r\n";
            $print_str .= append_chars("Actual Drawer amount","right",25," ").append_chars(number_format($cashout_header->count_amount,2),"left",11," ")."\r\n";
            $print_str .= append_chars("-------------","right",36," ")."\r\n";
            $print_str .= append_chars("Variance","right",25," ").append_chars(number_format(abs($cashout_header->drawer_amount - $cashout_header->count_amount),2),"left",11," ")."\r\n";


            /* Cashout Details */
            $print_str .= "\r\nCashout Breakdown\r\n";
            foreach ($cashout_details as $value) {
                if (!empty($value->denomination))
                    $mid = $value->denomination." X ".($value->total/$value->denomination);
                elseif (!empty($value->reference))
                    $mid = $value->reference." ";
                else $mid = "";

                $print_str .= append_chars("[".ucwords($value->type)."] ".$mid,"right",21," ").
                    append_chars(number_format($value->total,2),"left",15," ")."\r\n";
            }

            $print_str .= "\r\n".append_chars("","right",36,"-");
            return $print_str;
        }
        public function do_print($print_str=null,$asJson=false){
            if (!$asJson) {
                echo json_encode(array("code"=>"<pre style='background-color:#fff'>$print_str</pre>"));
            }
            else{
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
        }
        public function print_header(){
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
                    'accrdn' => $bv->accrdn,
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
            $wrap = wordwrap($branch['address'],35,"|#|");
            $exp = explode("|#|", $wrap);
            foreach ($exp as $v) {
                $print_str .= align_center($v,38," ")."\r\n";
            }
            $print_str .= 
             align_center('TIN: '.$branch['tin'],38," ")."\r\n"
            .align_center('ACCRDN: '.$branch['accrdn'],38," ")."\r\n"
            // .$this->align_center('BIR # '.$branch['bir'],42," ")."\r\n"
            .align_center('MIN: '.$branch['machine_no'],38," ")."\r\n"
            // .align_center('SN: '.$branch['serial'],38," ")."\r\n"
            .align_center('PERMIT: '.$branch['permit_no'],38," ")."\r\n";
            $print_str .= "======================================"."\r\n";
            return $print_str;
        }
    ##############
    #### FUNCTIONS
        public function set_post(){
            $args = array();
            $from = "";
            $to = "";
            $date = "";
            $range = $this->input->post('calendar_range');
            $calendar = $this->input->post('calendar');
            // $calendar = '2015-08-18 07:23:02';
            // $range = '2015/08/11 12:00 AM to 2015/08/13 12:00 AM';
            $title = "";
            if($this->input->post('title'))
                $title = $this->input->post('title');
            // $title = 'ZREAD';
            if($range != ""){
                $daterange = $range;
                $dates = explode(" to ",$daterange);
                $from = date2SqlDateTime($dates[0]);
                $to = date2SqlDateTime($dates[1]);
                $args["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);                
            }
            if($calendar != ""){
                $date = $calendar;
                $rargs["DATE(read_details.read_date) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
                $select = "read_details.*";
                $results = $this->site_model->get_tbl('read_details',$rargs,array('scope_from'=>'asc'),"",true,$select);
                $args = array();
                $from = "";
                $to = "";
                foreach ($results as $res) {
                    $from = $res->scope_from;
                    break;
                }
                foreach ($results as $res) {
                    $to = $res->scope_to;
                }
                if($from != "" && $to != ""){
                    $args["trans_sales.datetime  BETWEEN '".$from."' AND '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                }
                else{
                    $args["DATE(trans_sales.datetime) = DATE('".date2Sql($date)."') "] = array('use'=>'where','val'=>null,'third'=>false);
                    $from = date('Y-m-d 00:00',strtotime($date));
                    $to = date('Y-m-d 24:00',strtotime($date));
                }

            }
            $emp = "All";
            if($this->input->post('cashier')){
                $args['trans_sales.user_id'] = $this->input->post('cashier');   
                $server = $this->manager_model->get_server_details($this->input->post('cashier'));
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;          
            }
            $shift = $this->input->post('shift_id');
            if($shift != ""){
                $join['users'] = array('content'=>'shifts.user_id = users.id');
                $jargs['shifts.shift_id'] = $shift; 
                $select = "shifts.*,users.fname,users.lname,users.mname,users.suffix,users.username";
                $results = $this->site_model->get_tbl('shifts',$jargs,array('check_in'=>'desc'),$join,true,$select);
                $res = $results[0];


                $server = $this->manager_model->get_server_details($res->user_id);
                $emp = $server[0]->fname." ".$server[0]->mname." ".$server[0]->lname." ".$server[0]->suffix;
                $from = date2SqlDateTime($res->check_in);
                
                if($res->check_out == ""){
                    $today = $this->site_model->get_db_now();
                    $to = date2SqlDateTime($today);
                }
                else
                    $to = date2SqlDateTime($res->check_out);

                
                $args = array();
                $args['trans_sales.shift_id'] = $shift;   
            }
            $terminal = TERMINAL_ID;
            $args['trans_sales.terminal_id'] = $terminal;
            return array('args'=>$args,'from'=>$from,'to'=>$to,'date'=>$date,'terminal'=>$terminal,"employee"=>$emp,"title"=>$title,"shift_id"=>$shift);            
        }   
        public function search_current(){
            $today = sql2Date($this->site_model->get_db_now());
            $use = false;
            if($this->input->post('calendar_range')){
                $daterange = $this->input->post('calendar_range');
                $dates = explode(" to ",$daterange);
                $from = sql2Date($dates[0]);
                $to = sql2Date($dates[1]);
                $s_date = strtotime($from);
                $e_date = strtotime($to);
                $date = strtotime($today);
                if($date >= $s_date && $date <= $e_date)
                    $use = true;
            }
            if($this->input->post('calendar')){
                $date = $this->input->post('calendar');
                $from = sql2Date($date);
                if($from == $today){
                    $use = true;
                }
                if($this->input->post('use_curr')){
                    $use = $this->input->post('use_curr');
                }
            }
            $shift = $this->input->post('shift_id');
            if($shift != ""){
               $use = true;
            }
            return $use;
        }  
        public function trans_sales($args=array(),$curr=false){
            $n_results = array();
            if($curr){
                $this->db = $this->load->database('default', TRUE);
                $n_results  = $this->cashier_model->get_trans_sales(null,$args);
            }
            $this->db = $this->load->database('main', TRUE);
            $results = $this->cashier_model->get_trans_sales(null,$args);
            $orders = array();
            if(count($n_results) > 0){
                foreach ($n_results as $nres) {
                    if(!isset($orders[$nres->sales_id])){
                        $orders[$nres->sales_id] = $nres;
                    }
                }                
            }
            foreach ($results as $res) {
                if(!isset($orders[$res->sales_id])){
                    $orders[$res->sales_id] = $res;
                }
            }
            $sales = array();
            $all_ids = array();
            $sales['void'] = array();
            $sales['cancel'] = array();
            $sales['settled'] = array();

            $sales['void']['ids'] = array();
            $sales['cancel']['ids'] = array();
            $sales['settled']['ids'] = array();
            
            $sales['void']['orders'] = array();
            $sales['cancel']['orders'] = array();
            $sales['settled']['orders'] = array();

            $net = 0;
            $void_amount = 0;
            $types = array();
            $ordsnums = array();
            $all_orders = array();
            foreach ($orders as $sales_id => $sale) {
                if($sale->type_id == 10){
                    if($sale->trans_ref != "" && $sale->inactive == 0){
                        $sales['settled']['ids'][] = $sales_id;
                        $net += $sale->total_amount;
                        $types[$sale->type][$sale->sales_id] = $sale;
                        $ordsnums[$sale->trans_ref] = $sale;
                        $sales['settled']['orders'][$sales_id] = $sale;
                    }
                    else if($sale->trans_ref == "" && $sale->inactive == 1){
                        $sales['cancel']['ids'][] = $sales_id;
                        $sales['cancel']['orders'][$sales_id] = $sale;
                    }
                }
                else{
                    $sales['void']['ids'][] = $sales_id;
                    $sales['void']['orders'][$sales_id] = $sale;
                    $void_amount += $sale->total_amount;
                }
                $all_ids[] = $sales_id;
                $all_orders[$sales_id] = $sale;
            }
            ksort($ordsnums);
            $first = array_shift(array_slice($ordsnums, 0, 1));
            $last = end($ordsnums);
            $ref_ctr = count($ordsnums);
            return array('all_ids'=>$all_ids,'all_orders'=>$all_orders,'sales'=>$sales,'net'=>$net,'void'=>$void_amount,'types'=>$types,'refs'=>$ordsnums,'first_ref'=>$first,'last_ref'=>$last,'ref_count'=>$ref_ctr);
        }    
        public function menu_sales($ids=array(),$curr=false){
            $cats = array();
            $this->db = $this->load->database('default', TRUE);
            $cat_res = $this->site_model->get_tbl('menu_categories');
            foreach ($cat_res as $ces) {
                $cats[$ces->menu_cat_id] = array('cat_id'=>$ces->menu_cat_id,'name'=>$ces->menu_cat_name,'qty'=>0,'amount'=>0);
            }
            $sub_cats = array();
            $sales_menu_cat = $this->menu_model->get_menu_subcategories();
            foreach ($sales_menu_cat as $rc) {
                $sub_cats[$rc->menu_sub_cat_id] = array('name'=>$rc->menu_sub_cat_name,'qty'=>0,'amount'=>0);
            }
            $menu_net_total = 0;
            $menu_qty_total = 0;
            $menus = array();
            $ids_used = array();
            if(count($ids) > 0){
                $select = 'trans_sales_menus.*,menus.menu_name,menus.cost as sell_price,menus.menu_cat_id as cat_id,menus.menu_sub_cat_id as sub_cat_id';
                $join = null;           
                $join['menus'] = array('content'=>'trans_sales_menus.menu_id = menus.menu_id');
                $n_menu_res = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids),array(),$join,true,$select);    
                }
                $this->db = $this->load->database('main', TRUE);
                $menu_res = $this->site_model->get_tbl('trans_sales_menus',array('sales_id'=>$ids),array(),$join,true,$select);    
                foreach ($menu_res as $ms) {
                    if(!in_array($ms->sales_id, $ids_used)){
                        $ids_used[] = $ms->sales_id;
                    }
                    if(!isset($menus[$ms->menu_id])){
                        $mn = array();
                        $mn['name'] = $ms->menu_name;
                        $mn['cat_id'] = $ms->cat_id;
                        $mn['qty'] = $ms->qty;
                        $mn['amount'] = $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }
                    else{
                        $mn = $menus[$ms->menu_id];
                        $mn['qty'] += $ms->qty;
                        $mn['amount'] += $ms->price * $ms->qty;
                        $menus[$ms->menu_id] = $mn;
                    }

                    if(isset($sub_cats[$ms->sub_cat_id])){
                        $sub = $sub_cats[$ms->sub_cat_id];
                        $sub['qty'] += $ms->qty;
                        $sub['amount'] += $ms->price * $ms->qty;
                        $sub_cats[$ms->sub_cat_id] = $sub;
                    }
                    if(isset($cats[$ms->cat_id])){
                        $cat = $cats[$ms->cat_id];
                        $cat['qty'] += $ms->qty;
                        $cat['amount'] += $ms->price * $ms->qty;
                        $cats[$ms->cat_id] = $cat;
                    }
                    $menu_net_total += $ms->price * $ms->qty;
                    $menu_qty_total += $ms->qty;
                }
                if(count($n_menu_res) > 0){
                    foreach ($n_menu_res as $ms) {
                        if(!in_array($ms->sales_id, $ids_used)){
                            if(!isset($menus[$ms->menu_id])){
                                $mn = array();
                                $mn['name'] = $ms->menu_name;
                                $mn['cat_id'] = $ms->cat_id;
                                $mn['qty'] = $ms->qty;
                                $mn['amount'] = $ms->price * $ms->qty;
                                $menus[$ms->menu_id] = $mn;
                            }
                            else{
                                $mn = $menus[$ms->menu_id];
                                $mn['qty'] += $ms->qty;
                                $mn['amount'] += $ms->price * $ms->qty;
                                $menus[$ms->menu_id] = $mn;
                            }
                            if(isset($sub_cats[$ms->sub_cat_id])){
                                $sub = $sub_cats[$ms->sub_cat_id];
                                $sub['qty'] += $ms->qty;
                                $sub['amount'] += $ms->price * $ms->qty;
                                $sub_cats[$ms->sub_cat_id] = $sub;
                            }
                            if(isset($cats[$ms->cat_id])){
                                $cat = $cats[$ms->cat_id];
                                $cat['qty'] += $ms->qty;
                                $cat['amount'] += $ms->price * $ms->qty;
                                $cats[$ms->cat_id] = $cat;
                            }
                            $menu_net_total += $ms->price * $ms->qty;
                            $menu_qty_total += $ms->qty;
                        }
                    }
                }
            }
            $total_md = 0;
            $mids_used = array();
            $mods = array();
            if(count($ids) > 0){
                $n_menu_cat_sale_mods=array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids));
                }    
                $this->db = $this->load->database('main', TRUE);
                $menu_cat_sale_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$ids));
                foreach ($menu_cat_sale_mods as $res) {
                    if(!in_array($res->sales_id, $mids_used)){
                        $mids_used[] = $res->sales_id;
                    }
                    if(!isset($mods[$res->mod_id])){
                        $mods[$res->mod_id] = array(
                            'name'=>$res->mod_name,
                            'price'=>$res->price,
                            'qty'=>$res->qty,
                            'total_amt'=>$res->qty * $res->price,
                        );
                    }
                    else{
                        $mod = $mods[$res->mod_id];
                        $mod['qty'] += $res->qty;
                        $mod['total_amt'] += $res->qty * $res->price;
                        $mods[$res->mod_id] = $mod;
                    }
                }
                if(count($n_menu_cat_sale_mods) > 0){
                    foreach ($n_menu_cat_sale_mods as $res) {
                        if(!in_array($res->sales_id, $mids_used)){
                            if(!isset($mods[$res->mod_id])){
                                $mods[$res->mod_id] = array(
                                    'name'=>$res->mod_name,
                                    'price'=>$res->price,
                                    'qty'=>$res->qty,
                                    'total_amt'=>$res->qty * $res->price,
                                );
                            }
                            else{
                                $mod = $mods[$res->mod_id];
                                $mod['qty'] += $res->qty;
                                $mod['total_amt'] += $res->qty * $res->price;
                                $mods[$res->mod_id] = $mod;
                            }
                        }
                    }
                }
                foreach ($mods as $modid => $md) {
                    $total_md += $md['total_amt'];
                }
            }

            return array('gross'=>$menu_net_total+$total_md,'menu_total'=>$menu_net_total,'total_qty'=>$menu_qty_total,'menus'=>$menus,'cats'=>$cats,'sub_cats'=>$sub_cats,'mods_total'=>$total_md,'mods'=>$mods);
        }
        public function charges_sales($ids=array(),$curr=false){
            $total_charges = 0;
            $charges = array();
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_charges.sales_id"] = $ids;
                $n_cesults = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs); 
                }
                $this->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_charges',$cargs);   
                
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    if(!isset($charges[$ces->charge_id])){
                        $charges[$ces->charge_id] = array(
                            'amount'=>$ces->amount,
                            'name'=>$ces->charge_name,
                            'code'=>$ces->charge_code,
                            'qty'=>1
                        );
                    }
                    else{
                        $ch = $charges[$ces->charge_id];
                        $ch['amount'] += $ces->amount;
                        $ch['qty'] += 1;
                        $charges[$ces->charge_id] = $ch;
                    }
                    $total_charges += $ces->amount;
                }
                if(count($n_cesults) > 0){
                    foreach ($n_cesults as $ces) {
                        if(!in_array($ces->sales_id, $ids_used)){
                            if(!isset($charges[$ces->charge_id])){
                                $charges[$ces->charge_id] = array(
                                    'amount'=>$ces->amount,
                                    'name'=>$ces->charge_name,
                                    'code'=>$ces->charge_code,
                                    'qty'=>1
                                );
                            }
                            else{
                                $ch = $charges[$ces->charge_id];
                                $ch['amount'] += $ces->amount;
                                $ch['qty'] += 1;
                                $charges[$ces->charge_id] = $ch;
                            }
                            $total_charges += $ces->amount;
                        }
                    } 
                }
            }
            return array('total'=>$total_charges,'types'=>$charges);
        }
        public function local_tax_sales($ids=array(),$curr=false){
            $total_local_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_local_tax.sales_id"] = $ids;
                $n_cesults = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_cesults = $this->site_model->get_tbl('trans_sales_local_tax',$cargs); 
                }
                $this->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_local_tax',$cargs);   
                
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_local_tax += $ces->amount;
                }
                if(count($n_cesults) > 0){
                    foreach ($n_cesults as $ces) {
                        if(!in_array($ces->sales_id, $ids_used)){
                            $total_local_tax += $ces->amount;
                        }
                    } 
                }
            }
            return array('total'=>$total_local_tax);
        }
        public function tax_sales($ids=array(),$curr=false){
            $total_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_tax.sales_id"] = $ids;
                $n_cesults = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_cesults = $this->site_model->get_tbl('trans_sales_tax',$cargs); 
                }
                $this->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_tax',$cargs);   
                
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_tax += $ces->amount;
                }
                if(count($n_cesults) > 0){
                    foreach ($n_cesults as $ces) {
                        if(!in_array($ces->sales_id, $ids_used)){
                            $total_tax += $ces->amount;
                        }
                    } 
                }
            }
            return array('total'=>$total_tax);
        }
        public function no_tax_sales($ids=array(),$curr=false){
            $total_no_tax = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_no_tax.sales_id"] = $ids;
                $n_cesults = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_cesults = $this->site_model->get_tbl('trans_sales_no_tax',$cargs); 
                }
                $this->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_no_tax',$cargs);   
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total_no_tax += $ces->amount;
                }
                if(count($n_cesults) > 0){
                    foreach ($n_cesults as $ces) {
                        if(!in_array($ces->sales_id, $ids_used)){
                            $total_no_tax += $ces->amount;
                        }
                    } 
                }
            }
            return array('total'=>$total_no_tax);
        }
        public function zero_rated_sales($ids=array(),$curr=false){
            $total = 0;
            $ids_used = array();
            if(count($ids) > 0){
                $cargs["trans_sales_zero_rated.sales_id"] = $ids;
                $n_cesults = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_cesults = $this->site_model->get_tbl('trans_sales_zero_rated',$cargs); 
                }
                $this->db = $this->load->database('main', TRUE);
                $cesults = $this->site_model->get_tbl('trans_sales_zero_rated',$cargs);   
                
                foreach ($cesults as $ces) {
                    if(!in_array($ces->sales_id, $ids_used)){
                        $ids_used[] = $ces->sales_id;
                    }
                    $total += $ces->amount;
                }
                if(count($n_cesults) > 0){
                    foreach ($n_cesults as $ces) {
                        if(!in_array($ces->sales_id, $ids_used)){
                            $total += $ces->amount;
                        }
                    } 
                }
            }
            return array('total'=>$total);
        }
        public function discounts_sales($ids=array(),$curr=false){
            $total_disc = 0;
            $discounts = array();
            $disc_codes = array();
            $ids_used = array();
            $taxable_disc = 0;
            $non_taxable_disc = 0;
            if(count($ids) > 0){
                $n_sales_discs = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));
                }
                $this->db = $this->load->database('main', TRUE);
                $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$ids));
                
                foreach ($sales_discs as $discs) {
                    if(!in_array($discs->sales_id, $ids_used)){
                        $ids_used[] = $discs->sales_id;
                    }
                    if(!isset($disc_codes[$discs->disc_code])){
                        $disc_codes[$discs->disc_code] = array('name'=>$discs->disc_name,'qty'=> 1,'amount'=>$discs->amount);
                    }
                    else{
                        $disc_codes[$discs->disc_code]['qty'] += 1;
                        $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                    }
                    $total_disc += $discs->amount;
                    if($discs->no_tax == 1){
                        $non_taxable_disc += $discs->amount;
                    }
                    else{
                        $taxable_disc += $discs->amount;
                    }
                }
                if(count($n_sales_discs) > 0){
                    foreach ($n_sales_discs as $discs) {
                        if(!in_array($discs->sales_id, $ids_used)){
                            if(!isset($disc_codes[$discs->disc_code])){
                                $disc_codes[$discs->disc_code] = array('name'=>$discs->disc_name,'qty'=> 1,'amount'=>$discs->amount);
                            }
                            else{
                                $disc_codes[$discs->disc_code]['qty'] += 1;
                                $disc_codes[$discs->disc_code]['amount'] += $discs->amount;
                            }
                            $total_disc += $discs->amount;
                            if($discs->no_tax == 1){
                                $non_taxable_disc += $discs->amount;
                            }
                            else{
                                $taxable_disc += $discs->amount;
                            }
                        }
                    }
                }  
            }
            $discounts['total']=$total_disc;
            $discounts['types']=$disc_codes;
            $discounts['tax_disc_total']=$taxable_disc;
            $discounts['no_tax_disc_total']=$non_taxable_disc;
            return $discounts;
        }
        public function payment_sales($ids=array(),$curr=false){
            $ret = array();
            $total = 0;
            $pays = array();
            $ids_used = array();
            $all_payments = array();
            if(count($ids) > 0){
                $n_payments=array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));

                }    
                $this->db = $this->load->database('main', TRUE);
                $payments = $this->cashier_model->get_all_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$ids));
                foreach ($payments as $py) {
                    if(!in_array($py->sales_id, $ids_used)){
                        $ids_used[] = $py->sales_id;
                    }
                    if($py->amount > $py->to_pay)
                        $amount = $py->to_pay;
                    else
                        $amount = $py->amount;
                    if(!isset($pays[$py->payment_type])){
                        $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                    }
                    else{
                        $pays[$py->payment_type]['qty'] += 1;
                        $pays[$py->payment_type]['amount'] += $amount;
                    }
                    $total += $amount;
                    $all_payments[] = $py;
                }
                if(count($n_payments) > 0){
                    foreach ($n_payments as $py) {
                        if(!in_array($py->sales_id, $ids_used)){
                            if($py->amount > $py->to_pay)
                                $amount = $py->to_pay;
                            else
                                $amount = $py->amount;
                            if(!isset($pays[$py->payment_type])){
                                $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
                            }
                            else{
                                $pays[$py->payment_type]['qty'] += 1;
                                $pays[$py->payment_type]['amount'] += $amount;
                            }
                            $total += $amount;
                            $all_payments[] = $py;
                        }
                    }
                }
            }
            $ret['total'] = $total;
            $ret['types'] = $pays;
            $ret['all_pays'] = $all_payments;

            return $ret;
        }  
        public function removed_menu_sales($ids=array(),$curr=false){
            $reasons = array();
            if(count($ids) > 0){
                $n_remove_sales = array();
                if($curr){
                    $this->db = $this->load->database('default', TRUE);
                    $n_remove_sales = $this->cashier_model->get_reasons(null,array("reasons.trans_id"=>$ids));
                }
                $this->db = $this->load->database('main', TRUE);
                $remove_sales = $this->cashier_model->get_reasons(null,array("reasons.trans_id"=>$ids));
                
                $ids_used = array();
                foreach ($remove_sales as $res) {
                    if(!in_array($res->id, $ids_used)){
                        $ids_used[] = $res->id;
                    }
                    if(!isset($reasons[$res->id])){
                        $reasons[$res->id] = array('item'=>$res->ref_name,'reason'=>$res->reason,'trans_id'=>$res->trans_id,'manager'=>$res->man_username,'cashier'=>$res->cas_username);
                    }
                }
                if(count($n_remove_sales) > 0){
                    foreach ($n_remove_sales as $res) {
                        if(!in_array($res->id, $ids_used)){
                            if(!isset($reasons[$res->id])){
                                $reasons[$res->id] = array('item'=>$res->ref_name,'reason'=>$res->reason,'trans_id'=>$res->trans_id,'manager'=>$res->man_username,'cashier'=>$res->cas_username);
                            }
                        }
                    }
                }    
            }
            return $reasons;
        }
        public function old_grand_total($date=""){
            $old_grand_total = 0;
            $ctr = 0;
            $this->db = $this->load->database('main', TRUE);
            $args['trans_sales.datetime <= '] = $date;
            $args['trans_sales.type_id'] = SALES_TRANS;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['trans_sales.pos_id'] = TERMINAL_ID;
            $join['trans_sales'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id','mode'=>'left');
            // $join['trans_sales_menu_modifiers'] = array('content'=>'trans_sales_menu_modifiers.sales_id = trans_sales.sales_id','mode'=>'left');
            $result = $this->site_model->get_tbl('trans_sales_menus',$args,array(),$join,true,'sum(trans_sales_menus.qty * trans_sales_menus.price) as total');
            if(count($result) > 0){
                
                $old_grand_total += $result[0]->total;
            }
            

            $gargs['trans_sales.datetime <= '] = $date;
            $gargs['trans_sales.type_id'] = SALES_TRANS;
            $gargs['trans_sales.inactive'] = 0;
            $gargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $gargs['trans_sales.pos_id'] = TERMINAL_ID;
            // $join['trans_sales'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id','mode'=>'left');
            $join['trans_sales'] = array('content'=>'trans_sales_menu_modifiers.sales_id = trans_sales.sales_id','mode'=>'left');
            $result = $this->site_model->get_tbl('trans_sales_menu_modifiers',$gargs,array(),$join,true,'sum(trans_sales_menu_modifiers.qty * trans_sales_menu_modifiers.price) as total');
            // echo $this->site_model->db->last_query();
            if(count($result) > 0){
                $old_grand_total += $result[0]->total;
            }
            $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
            $cargs['read_details.pos_id'] = TERMINAL_ID;
            $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
            foreach ($ctrresult as $res) {
                $ctr++;
            }
            // echo var_dump($old_grand_total);
            return array('old_grand_total'=>$old_grand_total,'ctr'=>$ctr);
        }
        public function old_grand_net_total($date=""){
            $old_grand_total = 0;
            $ctr = 0;
            $args['trans_sales.datetime < '] = $date;
            $args['trans_sales.type_id'] = SALES_TRANS;
            $args['trans_sales.inactive'] = 0;
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $args['trans_sales.terminal_id'] = TERMINAL_ID;
            
            $this->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales',$args,array(),null,true,'sum(trans_sales.total_amount) as total');
            if(count($result) > 0){
                $old_grand_total += $result[0]->total;
            }
            $true_grand_total = $old_grand_total;
            $hargs['trans_sales.datetime <= '] = $date;
            $hargs['trans_sales.type_id'] = SALES_TRANS;
            $hargs['trans_sales.inactive'] = 0;
            $hargs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $hargs['trans_sales.pos_id'] = TERMINAL_ID;
            $joinh['trans_sales'] = array('content'=>'trans_sales_charges.sales_id = trans_sales.sales_id','mode'=>'left');
            
            $this->site_model->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales_charges',$hargs,array(),$joinh,true,'sum(trans_sales_charges.amount) as total_charges');
            // echo $this->site_model->db->last_query();
            if(count($result) > 0){
                $old_grand_total -= $result[0]->total_charges;
            }
            $largs['trans_sales.datetime <= '] = $date;
            $largs['trans_sales.type_id'] = SALES_TRANS;
            $largs['trans_sales.inactive'] = 0;
            $largs["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $largs['trans_sales.pos_id'] = TERMINAL_ID;
            $joinl['trans_sales'] = array('content'=>'trans_sales_local_tax.sales_id = trans_sales.sales_id','mode'=>'left');
            $this->db = $this->load->database('main', TRUE);
            $result = $this->site_model->get_tbl('trans_sales_local_tax',$largs,array(),$joinl,true,'sum(trans_sales_local_tax.amount) as total_lt');
            if(count($result) > 0){
                $old_grand_total -= $result[0]->total_lt;
            }

            $cargs = array('read_type'=>Z_READ,'DATE(read_details.read_date) <= '=>date2Sql($date));
            // if($this->site_model->db->database == "dinemain")
            //     $cargs['read_details.pos_id'] = TERMINAL_ID;
            
            $ctrresult = $this->site_model->get_tbl('read_details',$cargs,array(),null,true,'id','read_date',null);
            foreach ($ctrresult as $res) {
                $ctr++;
            }
            return array('old_grand_total'=>$old_grand_total,'true_grand_total'=>$true_grand_total,'ctr'=>$ctr);
        }
        public function shift_cashout($shift_id=""){
            $cashout_id = "";
            $shift = $this->site_model->get_tbl('shifts',array('shift_id'=>$shift_id));
            if(count($shift) > 0){
                $sh = $shift[0];
                if($sh->cashout_id != ""){
                    $cashout_id = $sh->cashout_id;
                }
            }
            ####
            return $cashout_id;
        }
        public function shift_entries($shift_id=""){
            $this->load->model('dine/clock_model');
            
            $shift = null;
            $total_drops = 0;
            $total_deps = $total_withs = array();
            $total_sales = 0;
            $overAllTotal = 0;
            
            $entries = $this->clock_model->get_shift_entries(null,array("shift_entries.shift_id"=>$shift_id));
            if(count($entries) > 0){
                foreach ($entries as $res) {
                    $total_drops += $res->amount;

                    if ($res->amount > 0)
                        $total_deps[] = $res;
                    else
                        $total_withs[] = $res;
                }
                $overAllTotal += $total_drops;
            }
            $args = array(
                "trans_sales.type_id"=>SALES_TRANS,
                "trans_sales.shift_id"=>$shift_id,
                "trans_sales.inactive"=>0
            );
            $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
            $trans = $this->cashier_model->get_trans_sales(null,$args);
            if(count($trans) > 0){
                foreach ($trans as $res) {
                    $total_sales += $res->total_paid;
                }
                $overAllTotal += $total_sales;
            }
            return array(
                'total_drops'=>$total_drops,
                'total_deps'=>$total_deps,
                'total_withs'=>$total_withs,
                'total_sales'=>$total_sales,
                'overAllTotal'=>$overAllTotal
            );
        }
}