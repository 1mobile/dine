<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->helper('core/dashboard_helper');  
        $this->load->model('dine/cashier_model');         
    }
    public function index(){
        $data = $this->syter->spawn('dashboard');
        $today = $this->site_model->get_db_now();

        $lastZread = $this->cashier_model->get_lastest_z_read(Z_READ,$today);
        $lastGT = 0;
        if(count($lastZread) > 0){
            $lastGT = $lastZread[0]->grand_total;
        }

        $todaySales = 0;
        $todayTransNo = 0;
        $select = 'sum(total_amount) as today_sales,count(sales_id) as today_no_trans';
        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive"] = 0;
        $args["trans_sales.type_id"] = SALES_TRANS;
        $args["DATE(trans_sales.datetime) = '".date2Sql($today)."'"] = array('use'=>'where','val'=>null,'third'=>false);;
        $ts = $this->site_model->get_tbl('trans_sales',$args,array(),null,true,$select);
        if(count($ts) > 0){
            $todaySales = $ts[0]->today_sales;
            $todayTransNo = $ts[0]->today_no_trans;
        }

        $data['code'] = dashboardMain($lastGT,$todaySales,$todayTransNo);
        $data['sideBarHide'] = true;
        $data['add_css'] = array('css/morris/morris.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js');

        $data['load_js'] = 'dine/dashboard.php';
        $data['use_js'] = 'dashBoardJs';
        // $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
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
        $shift_sales = array();
        foreach ($ords as $res) {
            if($res->type_id == 10){
                if($res->trans_ref != "" && $res->inactive == 0){
                    if(isset($shift_sales[$res->shift_id])){
                        $shift_sales[$res->shift_id] += $res->total_amount;
                    }
                    else
                        $shift_sales[$res->shift_id] = $res->total_amount;
                }    
            }
        }
        $shifts = array();
        foreach ($shift_sales as $shift_id => $total) {
            if(!in_array($shift_id, $shifts))
                $shifts[] = $shift_id;
        }
        $shs = array();
        if(count($shifts) > 0){
            $select = "shifts.shift_id,users.fname,users.mname,users.lname,users.suffix";
            $joinTables['users'] = array('content'=>'shifts.user_id = users.id');
            $sh = $this->site_model->get_tbl('shifts',array('shift_id'=>$shifts),array(),$joinTables,true,$select);
            foreach ($sh as $res) {
                $shs[$res->shift_id] = array('label'=>$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,'value'=>numInt($shift_sales[$res->shift_id]) );
            }
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
                $this->make->span(small( num($stat[strtolower($txt)]) ."/".num($total_trans) ),array('class'=>'pull-right'));
            $this->make->eDiv();
            $this->make->sDiv(array('style'=>'margin-bottom:10px;'));
                $this->make->progressBar($total_trans,$stat[strtolower($txt)],null,0,$color,array());
            $this->make->eDiv();
        }
        $code = $this->make->code();
        echo json_encode(array("orders"=>$orders,'shift_sales'=>$shs,'types'=>$types,'code'=>$code));
    }
}