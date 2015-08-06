<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/cashier.php");
class Reprint extends Cashier {
    public function __construct(){
        parent::__construct();
        $this->load->helper('dine/reprint_helper');
        // $this->load->model('dine/cashier_model');
    }
    public function index(){
        $data = $this->syter->spawn('act_receipts');
        $data['page_title'] = 'Receipts';
        $data['code'] = printsPage();
        $data['load_js'] = 'dine/reprint';
        $data['use_js'] = 'printReceiptJs';
        $this->load->view('page',$data);
    }
    public function results(){
        $ref = $this->input->post('receipt');
        $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        $args['trans_ref'] = array('use'=>'or_like','val'=>$ref); 
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');
        $code = "";
        $ids = array();

        $this->make->sDiv(array('class'=>'list-group'));
        foreach ($results as $res) {
            $this->make->append('<a href="#" id="rec-'.$res->sales_id.'" class="rec list-group-item">');
                $this->make->sDiv();
                    $this->make->H(6,'Order No. <span class="pull-right"> total: '.$res->total_amount.'</span> '.$res->sales_id,array('style'=>'font-size:14px;margin:2px;'));
                $this->make->eDiv();
                    $this->make->p('Receipt No. '.$res->trans_ref.'<span class="pull-right">'.sql2Datetime($res->datetime).'</span>',array('style'=>'font-size:12px;margin:2px;'));
            $this->make->append('</a>');
            $ids[] = $res->sales_id;
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }
    public function view($sales_id=null,$noPrint=true){
        if($noPrint)
            $reprint = false;
        else
            $reprint = true;

        $print = $this->print_sales_receipt($sales_id,false,$noPrint,$reprint,null,true,1,0,null,true);   
        echo "<pre style='background-color:#fff'>";
            echo $print;
        echo "</pre>"; 
    }

}