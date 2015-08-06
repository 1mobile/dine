<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ourmenu extends CI_Controller {
	var $data = null;
	  public function branch_details($branch_id=null,$resto_id=null){
        $this->load->model('resto/branches_model');
        $details = $this->branches_model->get_restaurant_branches($branch_id,$resto_id);
        $det = $details[0];
        $branch = array(
            'name'=>$det->branch_name,
            'desc'=>$det->branch_desc,
            'contact_no'=>$det->contact_no,
            'delivery_no'=>$det->delivery_no,
            'address'=>$det->address,
            'base_location'=>$det->base_location,
            'currency'=>$det->currency,
            'seat_layout'=>$det->image,
            'seat_layout_path'=>base_url().'uploads/'.$resto_id.'/layout/'.$det->image
        );
        echo json_encode(array('details'=>$branch));
    }
    public function categories($resto_id=null){
        $this->load->model('resto/menu_model');
            
        $this->make->sDiv(array('class'=>'ui-block-a')); 
           $this->make->sDiv(array('class'=>'img-block block-btn','title'=>'Combo Meals','ref'=>'combo'));
               $thumb = base_url().'img/combomeals.jpg';
               $this->make->img($thumb);
               $this->make->sDiv(array('class'=>'img-caption'));
                    $this->make->span('Combo Meals');
                $this->make->eDiv();
             $this->make->eDiv();
        $this->make->eDiv();

        $cats = $this->menu_model->get_restaurant_categories(null,$resto_id);
        $ctr = 2;
        foreach ($cats as $res) {
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
                 $this->make->sDiv(array('class'=>'img-block block-btn','title'=>$res->name,'ref'=>$res->cat_id));
                   $thumb = base_url().'images/noimage.png';
                   if($res->image  != ""){
                        $thumb = base_url().'uploads/'.$res->res_id.'/categories/'.$res->image;
                   } 
                   $this->make->img($thumb);
                   $this->make->sDiv(array('class'=>'img-caption'));
                        $this->make->span($res->name);
                    $this->make->eDiv();
                 $this->make->eDiv();
            $this->make->eDiv();
        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function subcategories($cat_id=null){
        $this->load->model('resto/menu_model');
        $cats = $this->menu_model->get_restaurant_subcategories(null,$cat_id);
        $ctr = 1;
        foreach ($cats as $res) {
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
                 $this->make->sDiv(array('class'=>'img-block sub-block-btn','title'=>$res->name,'ref'=>$res->sub_cat_id));
                   $thumb = base_url().'images/noimage.png';
                   if($res->image  != ""){
                        $thumb = base_url().'uploads/'.$res->res_id.'/subcategories/'.$res->image;
                   } 
                   $this->make->img($thumb);
                   $this->make->sDiv(array('class'=>'img-caption'));
                        $this->make->span($res->name);
                    $this->make->eDiv();
                 $this->make->eDiv();
            $this->make->eDiv();
        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function combos($resto_id=null){
        $this->load->model('resto/menu_model');
        $cats = $this->menu_model->get_restaurant_combos(null,$resto_id);
        $ctr = 1;
        foreach ($cats as $res) {
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
                 $this->make->sDiv(array('class'=>'img-block item-block-btn','title'=>$res->combo_name,'ref'=>$res->combo_id));
                   $thumb = base_url().'images/noimage.png';
                   if($res->image  != ""){
                        $thumb = base_url().'uploads/'.$res->res_id.'/combo/'.$res->image;
                   } 
                   $this->make->img($thumb);
                   $this->make->sDiv(array('class'=>'img-btn'));
                        $this->make->A(fa('fa-plus fa-lg'),'#',array('class'=>'add-btn','title'=>$res->combo_name,'item-type'=>'combo','item-price'=>$res->selling_price,'ref'=>$res->combo_id));
                        $this->make->A(fa('fa-search fa-lg'),'#',array('class'=>'view-btn','title'=>$res->combo_name,'ref'=>$res->combo_id));
                        // $this->make->span('sad');
                    $this->make->eDiv();
                   $this->make->sDiv(array('class'=>'img-caption'));
                        $this->make->span($res->combo_name);
                    $this->make->eDiv();
                 $this->make->eDiv();
            $this->make->eDiv();
        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function items($sub_cat_id=null){
        $this->load->model('resto/menu_model');
        $cats = $this->menu_model->get_restaurant_items(null,null,null,$sub_cat_id);
        $ctr = 1;
        foreach ($cats as $res) {
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
                 $this->make->sDiv(array('class'=>'img-block item-block-btn','title'=>$res->name,'ref'=>$res->item_id));
                   $thumb = base_url().'images/noimage.png';
                   if($res->image  != ""){
                        $thumb = base_url().'uploads/'.$res->res_id.'/menu/'.$res->image;
                   } 
                   $this->make->img($thumb);
                   $this->make->sDiv(array('class'=>'img-btn'));
                        $this->make->A(fa('fa-plus fa-lg'),'#',array('class'=>'add-btn','title'=>$res->name,'item-type'=>'item','item-price'=>$res->price,'ref'=>$res->item_id));
                        $this->make->A(fa('fa-search fa-lg'),'#',array('class'=>'view-btn','title'=>$res->name,'ref'=>$res->item_id));
                        // $this->make->span('sad');
                    $this->make->eDiv();

                   $this->make->sDiv(array('class'=>'img-caption'));
                        $this->make->span($res->name);
                    $this->make->eDiv();
                 $this->make->eDiv();
            $this->make->eDiv();
        }
        $code = $this->make->code();
        echo json_encode(array('code'=>$code));
    }
    public function item_details($item_id=null){
       $this->load->model('resto/menu_model');
       $item = array();
       $items = $this->menu_model->get_restaurant_items($item_id);
       $det = $items[0];
       $item['type'] = 'item';
       $item['code'] = $det->code;
       $item['barcode'] = $det->barcode;
       $item['name'] = $det->name;
       $item['category'] = $det->cat_name;
       $item['subcategory'] = $det->sub_cat_name;
       $item['desc'] = $det->description;
       $item['wholePrice'] = $det->price;
       $item['piecePrice'] = $det->portion_price;

       $thumb = base_url().'images/noimage.png';
       if(iSetObj($det,'image')  != ""){
            $thumb = base_url().'uploads/'.$det->res_id.'/menu/'.$det->image;
       }
       
       $this->make->img($thumb);
       $this->make->sDiv(array('class'=>'item-img-tags'));
            $this->make->span(fa('fa-heart-o fa-lg fa-fw').' 101'.' '.fa('fa-comment-o fa-lg fa-fw').' 10');
       $this->make->eDiv();
       $code = $this->make->code();
       $item['img'] = $code;

       // $this->make->P(tagWord($det->cat_name,'primary')." ".tagWord($det->sub_cat_name,'info'));
       $this->make->H(3,$item['name'],array('class'=>'item-title'));
       $this->make->P($item['desc'],array('style'=>'text-align:justify;'));
       $this->make->P(
          $this->make->span(strong('Price '),array('class'=>'color-pinecone','style'=>'font-size:14px;','return'=>true))."&nbsp;".
          $this->make->span(strong(num($det->price)),array('class'=>'color-cardinal','style'=>'font-size:24px;','return'=>true)).
          $this->make->A(fa('fa-plus')." Add to Order",'#',array('return'=>true,'id'=>'add-order-btn','ref'=>$item_id,'class'=>'ui-btn ui-mini ui-btn-inline')),
          array('class'=>'price-p')
        );
       if($det->portion_price > 0 )
           $this->make->P('Price Per Piece: '.$det->portion_price);
       $code = $this->make->code();
       $item['description'] = $code;
       
       $code = "";
       $item['details'] = $code;

       echo json_encode($item);
    }
    public function combo_details($combo_id=null){
       $this->load->model('resto/menu_model');
       $item = array();
       $items = $this->menu_model->get_restaurant_combos($combo_id);
       $det = $items[0];
       $item['type'] = 'combo';
       $item['code'] = $det->combo_code;
       $item['barcode'] = $det->combo_barcode;
       $item['name'] = $det->combo_name;
       $item['desc'] = $det->combo_desc;
       $item['wholePrice'] = $det->selling_price;

       $thumb = base_url().'images/noimage.png';
       if(iSetObj($det,'image')  != ""){
            $thumb = base_url().'uploads/'.$det->res_id.'/combo/'.$det->image;
       }
       
       $this->make->img($thumb);
       $this->make->sDiv(array('class'=>'item-img-tags'));
            $this->make->span(fa('fa-heart-o fa-lg fa-fw').' 101'.' '.fa('fa-comment-o fa-lg fa-fw').' 10');
       $this->make->eDiv();
       $code = $this->make->code();
       $item['img'] = $code;

       // $this->make->P(tagWord($det->cat_name,'primary')." ".tagWord($det->sub_cat_name,'info'));
       $this->make->H(3,$item['name'],array('class'=>'item-title'));
       $this->make->P($item['desc'],array('style'=>'text-align:justify;'));
       $this->make->P(
          $this->make->span(strong('Price '),array('class'=>'color-pinecone','style'=>'font-size:14px;','return'=>true))."&nbsp;".
          $this->make->span(strong(num($det->selling_price)),array('class'=>'color-cardinal','style'=>'font-size:24px;','return'=>true)).
          $this->make->A(fa('fa-plus')." Add to Order",'#',array('return'=>true,'id'=>'add-order-btn','ref'=>$combo_id,'class'=>'ui-btn ui-mini ui-btn-inline')),
          array('class'=>'price-p')
        );
       $code = $this->make->code();
       $item['description'] = $code;
       
       $code = "";
       $item['details'] = $code;

       echo json_encode($item);
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
    public function get_available_tables($branch_id=null,$asJson=true){
        $this->load->model('resto/branches_model');
        $this->load->model('resto/dine_model');
        $table_occ = $this->dine_model->get_restaurant_branch_occupied_tables(null,$branch_id);
        $occ = array();
        foreach ($table_occ as $det) {
          $occ[] = $det->tbl_id;
        }
        $tables=array();
        $table_list = $this->branches_model->get_restaurant_branch_tables(null,$branch_id);
        foreach ($table_list as $res) {
            $status = 'green';
            if(in_array($res->tbl_id, $occ)){
              $status = 'red';
            }
            $tables[$res->tbl_id] = array(
                "name"=> $res->name,
                "top"=> $res->top,
                "left"=> $res->left,
                "stat"=> $status
            );
        }
        if($asJson)
            echo json_encode($tables);
        else
            return $tables;
    }
   
}