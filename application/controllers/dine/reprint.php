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
    public function allPrint(){
        $ref = $this->input->post('receipt');
        $sales = '112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,157,159,160,161,162,164,165,166,167,169,170,171,172,173,174,175,176,177,178';
        $ids = explode(',', $sales);
        $args['sales_id'] = $ids; 
        // $args['sales_id'] = array('use'=>'like','val'=>$ref); 
        // $args['trans_ref'] = array('use'=>'or_like','val'=>$ref); 
         $this->db = $this->load->database('main', TRUE);
        $results = $this->site_model->get_tbl('trans_sales',$args,array('trans_sales.datetime'=>'desc'),null,true,'trans_ref,sales_id,datetime,total_amount');
        $code = "";
        $ids = array();

        $this->make->sDiv(array('class'=>'list-group'));
        foreach ($results as $res) {
            // $this->make->append('<a href="#" id="rec-'.$res->sales_id.'" class="rec list-group-item">');
            //     $this->make->sDiv();
            //         $this->make->H(6,'Order No. <span class="pull-right"> total: '.$res->total_amount.'</span> '.$res->sales_id,array('style'=>'font-size:14px;margin:2px;'));
            //     $this->make->eDiv();
            //         $this->make->p('Receipt No. '.$res->trans_ref.'<span class="pull-right">'.sql2Datetime($res->datetime).'</span>',array('style'=>'font-size:12px;margin:2px;'));
            // $this->make->append('</a>');
            // $ids[] = $res->sales_id;
            $this->view($res->sales_id);
        }
        $this->make->eDiv();
        $code = $this->make->code();
        echo json_encode(array('code'=>$code,'ids'=>$ids));
    }

}