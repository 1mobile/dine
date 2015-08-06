<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {
	var $data = null;
    public function order_list($branch_id=null,$res_id=null){
        $this->load->model('resto/dine_model');
        $this->load->model('site/site_model');
        $now = $this->site_model->get_db_now('sql');
        $orders = $this->dine_model->get_restaurant_orders(null,$branch_id,$res_id,'pending');
        $order = array();
        $ids = array();
        $code = "";
        foreach ($orders as $res) {
            $order[$res->order_id] = array(
              "tblID" => $res->tbl_id,
              "tblName" => $res->tbl_name,
              "name" => $res->name,
              "pax" => $res->pax,
              "ago" => ago($res->date,$now),
              "date" => sql2Date($res->date)
            );
            $ids[] = $res->order_id;
        }
        $order_details = $this->dine_model->get_restaurant_order_details($ids);
        foreach ($order_details as $det) {
          $line = array(
            "itemID"=>$det->order_detail_id,
            "itemName"=>$det->item_name,
            "orderID"=>$det->order_id,
            "qty"=>$det->qty,
            "type"=>$det->type
          );
          $order[$det->order_id]['details'][$det->order_detail_id] = $line; 
        }
        $ctr = 1;
        foreach ($order as $id => $opt) {
            if($ctr == 1){
                $this->make->sDiv(array('class'=>'ui-block-a'));
                $ctr++;
            }
            else if($ctr == 2){
                $this->make->sDiv(array('class'=>'ui-block-b'));
                $ctr++;
            }
            else{
                $this->make->sDiv(array('class'=>'ui-block-c'));
                $ctr = 1;
            }
              $this->make->sDiv(array('class'=>'ui-corner-all div-shadow','style'=>'margin:10px;','id'=>'order-'.$id));
                $this->make->sDiv(array('class'=>'ui-bar ui-bar-a ui-content-no-border ','style'=>'padding:10px;'));
                  $this->make->H(4,$opt['tblName'],array('style'=>'float:left'));
                $this->make->eDiv();
                $this->make->sDiv(array('class'=>'ui-body ui-body-a ui-content-no-border '));
                  $this->make->H(5,"Customer: ".$opt['name'],array('class'=>'color-pinecone','style'=>'margin:10px;'));
                  $this->make->H(5,"No. Of Guest: ".$opt['pax'],array('class'=>'color-pinecone','style'=>'margin:10px;'));
                    $this->make->sDiv();
                      $this->make->sTable(array('class'=>'color-pinecone','style'=>'width:90%;margin:6px;font-size:12px;'));
                          $this->make->sTablehead();
                            $this->make->sRow();
                                $this->make->th('Item Name',array('style'=>'text-align:left'));
                                $this->make->th('Qty',array('style'=>'text-align:right'));
                            $this->make->eRow();
                          $this->make->eTablehead();
                          $this->make->sTableBody();
                                foreach ($opt['details'] as $detail_id => $det) {
                                  $this->make->sRow();
                                      $this->make->th($det['itemName'],array('style'=>'text-align:left'));
                                      $this->make->th($det['qty'],array('style'=>'text-align:right'));
                                  $this->make->eRow();
                                }
                          $this->make->eTableBody();
                      $this->make->eTable();
                    $this->make->eDiv();
                    $this->make->sDiv();
                      $this->make->H(5,$opt['ago'],array('class'=>'color-pinecone','style'=>'margin:10px;'));
                    $this->make->eDiv();
                    $this->make->sDiv();
                        $this->make->A('Done','#',array('class'=>'ui-btn ui-btn-inline ui-icon-check ui-btn-icon-left done-order','ref'=>$id));
                        $this->make->A('Cancel','#',array('class'=>'ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-left cancel-order','ref'=>$id));
                    $this->make->eDiv();
                $this->make->eDiv();
              $this->make->eDiv();
            $this->make->eDiv();

        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function submit_order(){
       $this->load->model('resto/menu_model');
       $this->load->model('resto/dine_model');
       $res_id = $_POST['res_id'];
       $branch_id = $_POST['branch_id'];
       $dineData = json_decode($_POST['dineData']);
       $total = 0;
       foreach ($dineData->items as $key => $opt) {
          $total += $opt->qty * $opt->price;
       }
       $order = array();
       $order = array(
          "res_id"=>$res_id,
          "branch_id"=>$branch_id,
          "name"=>$dineData->name,
          "pax"=>$dineData->pax,
          "tbl_id"=>$dineData->table,
          "total"=>$total
       );
       $order_id = $this->dine_model->add_restaurant_orders($order);
       $order_details = array();
       foreach ($dineData->items as $key => $opt) {
          $order_details[] = array(
              "order_id"=>$order_id,
              "item_id"=>$opt->itemID,
              "item_name"=>$opt->itemName,
              "qty"=>$opt->qty,
              "price"=>$opt->price,
              "type"=>$opt->itemType
          );
       }
       // echo var_dump($order_details);
       $this->dine_model->add_restaurant_order_details_batch($order_details);
       // echo json_encode($item);
    }
    public function done(){
      $this->load->model('resto/dine_model');
      $order_id = $_POST['order_id'];
      $items = array(
        'status'=>'done'
      );
      $this->dine_model->update_restaurant_orders($items,$order_id);
    }
    public function cancel(){
      $this->load->model('resto/dine_model');
      $order_id = $_POST['order_id'];
      $items = array(
        'status'=>'cancelled'
      );
      $this->dine_model->update_restaurant_orders($items,$order_id);
    }
}