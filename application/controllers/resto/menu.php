<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
	var $data = null;
	public function manage_items($res_id=null){
        $this->load->model('resto/restaurant_model');
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');

        $restaurants = $this->restaurant_model->get_restaurants($res_id);
        $resto = $restaurants[0];

        $category = array();
        $category = $this->menu_model->get_restaurant_categories(null,$res_id);

        $subcategory = array();
        $subcategory = $this->menu_model->get_restaurant_subcategories(null,null,$res_id);

        $data = $this->syter->spawn('restaurants');
        $data['code'] = makeManageItems($resto->res_id,$category,$subcategory);
        $data['page_title'] = fa('fa-cutlery fa-fw')." ".$resto->res_name." Menu";
        $data['load_js'] = 'resto/menu.php';
        $data['use_js'] = 'menuJs';
        $this->load->view('page',$data);
    }
    public function category_form($res_id=null,$cat_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');
        $category = array();
        if($cat_id != null && $cat_id != 'add'){
            $categories = $this->menu_model->get_restaurant_categories($cat_id,$res_id);
            $category = $categories[0];
        }
        $data['code'] = makeCategoryLoad($res_id,$category);
        $data['load_js'] = 'resto/menu.php';
        $data['use_js'] = 'menuCategoryJs';
        $this->load->view('load',$data);
    }
    public function category_db(){
        $this->load->model('resto/menu_model');
        $image = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        }

        $info = pathinfo($_FILES['fileUpload']['name']);
        $ext = $info['extension'];
        $category = $this->input->post('code');

        $newname = $category.".".$ext;

        if (!file_exists("uploads/categories")) {
            mkdir("uploads/categories/", 0777, true);
        }

        $target = 'uploads/categories/'.$newname;
        move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);

        if($image != null){
            $items = array(
                "res_id"=>$this->input->post('res_id'),
                "code"=>$this->input->post('code'),
                "name"=>$this->input->post('name'),
                // "img"=>$image
                'image'=>$this->input->post('code').'.'.$ext
            );
        }else{
            $items = array(
                "res_id"=>$this->input->post('res_id'),
                "code"=>$this->input->post('code'),
                "name"=>$this->input->post('name')
                // "img"=>$image
                // 'image'=>$this->input->post('code').'.'.$ext
            );
        }
        if($image == null)
            unset($items['img']);
        if($this->input->post('cat_id')){
            $this->menu_model->update_restaurant_categories($items,$this->input->post('cat_id'));
            $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Item Category '.$this->input->post('name');
        }else{
            $id = $this->menu_model->add_restaurant_categories($items);
            $act = 'add';
            $msg = 'Added  new Category '.$this->input->post('name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function sub_category_form($res_id=null,$cat_id=null,$sub_cat_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');
        $subcategory = array();
        if($sub_cat_id != null && $sub_cat_id != 'add'){
            $subcategories = $this->menu_model->get_restaurant_subcategories($sub_cat_id,$cat_id,$res_id);
            $subcategory = $subcategories[0];
        }
        $data['code'] = makeSubCategoryLoad($res_id,$cat_id,$subcategory);
        $data['load_js'] = 'resto/menu.php';
        $data['use_js'] = 'menuCategoryJs';
        $this->load->view('load',$data);
    }
    public function sub_category_db(){
        $this->load->model('resto/menu_model');
        $image = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        }

        $info = pathinfo($_FILES['fileUpload']['name']);
        $ext = $info['extension'];
        $scategory = $this->input->post('code');

        $newname = $scategory.".".$ext;

        if (!file_exists("uploads/sub_categories")) {
            mkdir("uploads/sub_categories/", 0777, true);
        }

        $target = 'uploads/sub_categories/'.$newname;
        move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);

        $items = array(
            "res_id"=>$this->input->post('res_id'),
            "cat_id"=>$this->input->post('cat_id'),
            "code"=>$this->input->post('code'),
            "name"=>$this->input->post('name'),
            // "img"=>$image
            'image'=>$this->input->post('code').'.'.$ext
        );
        if($image == null)
            unset($items['img']);
        if($this->input->post('sub_cat_id')){
            $this->menu_model->update_restaurant_subcategories($items,$this->input->post('sub_cat_id'));
            $id = $this->input->post('sub_cat_id');
            $act = 'update';
            $msg = 'Updated Item Sub Category '.$this->input->post('name');
        }else{
            $id = $this->menu_model->add_restaurant_subcategories($items);
            $act = 'add';
            $msg = 'Added  new Sub Category '.$this->input->post('name');
        }

        echo json_encode(array("id"=>$id,"cat_id"=>$this->input->post('cat_id'),"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function item_list($res_id=null,$cat_id=null,$sub_cat_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');

        $items = array();
        $items = $this->menu_model->get_restaurant_items(null,$res_id,$cat_id,$sub_cat_id);

        $data['code'] = makeItemListLoad($res_id,$items);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesDetailsJs';
        $this->load->view('load',$data);
    }
    public function item_form($res_id=null,$item_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/restaurant_model');
        $this->load->model('resto/menu_model');
        $data = $this->syter->spawn('restaurants');
        $restaurants = $this->restaurant_model->get_restaurants($res_id);
        $resto = $restaurants[0];

        $category = array();
        $category = $this->menu_model->get_restaurant_categories(null,$res_id);

        $subcategory = array();
        $subcategory = $this->menu_model->get_restaurant_subcategories(null,null,$res_id);

        $item = array();
        if($item_id != null){
            $items = $this->menu_model->get_restaurant_items($item_id,$res_id);
            $item = $items[0];
        }
        $data['code'] = makeItemForm($res_id,$category,$item,$subcategory);
        $data['page_title'] = fa('fa-cutlery fa-fw')." ".$resto->res_name." Menu";
        $data['page_subtitle'] = "Add New Item";
        $data['load_js'] = 'resto/menu.php';
        $data['use_js'] = 'itemFormJs';
        $this->load->view('page',$data);
    }
    public function items_db(){
        $this->load->model('resto/menu_model');
        $this->load->model('resto/restaurant_model');
        $image = null;
        // if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
        //     $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        // }
        $ext = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
            $info = pathinfo($_FILES['fileUpload']['name']);
            if(isset($info['extension']))
            $ext = $info['extension'];
            $menu = $this->input->post('name');
        
            $newname = $menu.".".$ext;
            $res = $this->restaurant_model->get_restaurants($this->input->post('res_id'));
            $res_data = $res[0];

            if (!file_exists("uploads/".$res_data->res_id."/menu/")) {
                mkdir("uploads/".$res_data->res_id."/menu/", 0777, true);
            }
            $target = 'uploads/'.$res_data->res_id.'/menu/'.$newname;
            move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);
        }

        $items = array(
            "res_id"=>$this->input->post('res_id'),
            "code"=>$this->input->post('code'),
            "barcode"=>$this->input->post('barcode'),
            "name"=>$this->input->post('name'),
            "description"=>$this->input->post('description'),
            "cat_id"=>$this->input->post('category-drop'),
            "sub_cat_id"=>$this->input->post('sub-category-drop'),
            "price"=>$this->input->post('price'),
            "portion"=>$this->input->post('portion'),
            "portion_price"=>$this->input->post('portion_price'),
            // 'img'=>$image
            'image'=>$this->input->post('name').'.'.$ext
        );
        if($image == null)
            unset($items['image']);
        if($this->input->post('item_id')){
            $this->menu_model->update_restaurant_items($items,$this->input->post('item_id'));
            $id = $this->input->post('item_id');
            $act = 'update';
            $msg = 'Updated Item '.$this->input->post('name');
        }else{
            $id = $this->menu_model->add_restaurant_items($items);
            $act = 'add';
            $msg = 'Added  new Item '.$this->input->post('name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function combo_list($res_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');

        $combos = array();
        $combos = $this->menu_model->get_restaurant_combos(null,$res_id);

        $data['code'] = makeComboListLoad($res_id,$combos);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesDetailsJs';
        $this->load->view('load',$data);
    }
    public function combo_form($res_id=null,$combo_id=null){
        $this->load->helper('resto/menu_helper');
        $this->load->model('resto/menu_model');
        $this->load->model('resto/restaurant_model');
        $data = $this->syter->spawn('restaurants');
        $restaurants = $this->restaurant_model->get_restaurants($res_id);
        $resto = $restaurants[0];
        $combo = array();
        $combo_det = array();
        if($combo_id != null){
            $combos = $this->menu_model->get_restaurant_combos($combo_id,$res_id);
            $combo = $combos[0];
            $combo_det = $this->menu_model->get_restaurant_combo_details(null,null,$combo_id);
        }

        $data['code'] = makeComboForm($res_id,$combo,$combo_det);
        $data['page_title'] = fa('fa-cutlery fa-fw')." ".$resto->res_name." Menu";
        $data['page_subtitle'] = "Add New Combo";
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'resto/menu.php';
        $data['use_js'] = 'comboFormJs';
        $this->load->view('page',$data);
    }
    public function search_items(){
        $search = $this->input->post('search');
        $res_id = $this->input->post('res_id');
        $this->load->model('resto/menu_model');
        $found = $this->menu_model->search_restaurant_items($res_id,$search);
        $items = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $items[] = array('key'=>$res->code." ".$res->name,'value'=>$res->item_id);
            }
        }
        echo json_encode($items);
    }
    public function get_item_details($item_id=null,$res_id=null){
        $this->load->model('resto/menu_model');
        $items = $this->menu_model->get_restaurant_items($item_id,$res_id);
        $item = $items[0];
        $det['item_id'] = $item->item_id;
        $det['price'] = $item->price;
        $det['portion_price'] = 0;
        if($item->portion_price > 0)
            $det['portion_price'] = $item->portion_price;

        echo json_encode($det);
    }
    public function combos_db(){
        $this->load->model('resto/menu_model');
        $this->load->model('resto/restaurant_model');
        $image = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        }

        $info = pathinfo($_FILES['fileUpload']['name']);
        $ext = $info['extension'];
        $combo = $this->input->post('combo_code');

        $newname = $combo.".".$ext;
        $res = $this->restaurant_model->get_restaurants($this->input->post('res_id'));
        $res_data = $res[0];

        if (!file_exists("uploads/".$res_data->res_id."/combo/")) {
            mkdir("uploads/".$res_data->res_id."/combo/", 0777, true);
        }

        $target = 'uploads/'.$res_data->res_id.'/combo/'.$newname;
        move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);

        $items = array(
            "res_id"=>$this->input->post('res_id'),
            "combo_code"=>$this->input->post('combo_code'),
            "combo_barcode"=>$this->input->post('combo_barcode'),
            "combo_name"=>$this->input->post('combo_name'),
            "combo_desc"=>$this->input->post('combo_desc'),
            // 'img'=>$image
            'image'=>$this->input->post('combo_code').'.'.$ext
        );
        if($image == null)
            unset($items['img']);
        if($this->input->post('combo_id')){
            $this->menu_model->updates_restaurant_combos($items,$this->input->post('combo_id'));
            $id = $this->input->post('combo_id');
            $act = 'update';
            $msg = 'Updated Combo '.$this->input->post('combo_name');
        }else{
            $id = $this->menu_model->add_restaurant_combos($items);
            $act = 'add';
            $msg = 'Added  new Combo '.$this->input->post('combo_name');
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('combo_name'),"act"=>$act,'msg'=>$msg));
    }
    public function combos_details_db(){
        $this->load->model('resto/menu_model');
        $items = array(
            "combo_id"=>$this->input->post('combo-id-hid'),
            "item_id"=>$this->input->post('item-id-hid'),
            "qty"=>$this->input->post('qty'),
            "type"=>$this->input->post('type')
        );

        $row = "";
        $combo_det = $this->menu_model->get_restaurant_combo_details(null,$this->input->post('item-id-hid'),$this->input->post('combo-id-hid'));
        $msg = "";
        if(count($combo_det) > 0){
            $det = $combo_det[0];
            $this->menu_model->update_restaurant_combo_detail($items,$det->combo_det_id);
            $id = $det->combo_det_id;
            $act = "update";
            $msg = "Updated Item ".$this->input->post('item-search');

        }else{
            $id = $this->menu_model->add_restaurant_combo_detail($items);
            $act = "add";
            $msg = "Added New Item ".$this->input->post('item-search');

        }
        $this->make->sRow(array('id'=>'row-'.$id));
            $this->make->td($this->input->post('item-search'));
            $this->make->td($this->input->post('type'));
            $this->make->td(num($this->input->post('qty')));
            $this->make->td(num($this->input->post('item-price-hid')));
            $this->make->td(num($this->input->post('item-price-hid') * $this->input->post('qty')));
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('row'=>$row,'msg'=>$msg,'act'=>$act,'id'=>$id));
    }
    public function remove_detail_row(){
        $this->load->model('resto/menu_model');
        $this->menu_model->delete_restaurant_combo_detail($this->input->post('combo_det_id'));
        $json['msg'] = "Combo Item Deleted.";
        echo json_encode($json);
    }
    public function get_combo_total($asJson=true,$updateDB=true){
        $this->load->model('resto/menu_model');
        $combo_id = $this->input->post('combo_id');
        $details = $this->menu_model->get_restaurant_combo_detail_prices(null,null,$combo_id);
        $total = 0;
        foreach ($details as $res) {
            if($res->type == 'whole')
                $price = $res->price;
            else
                $price = $res->portion_price;
            $total += $price * $res->qty;
        }
        if($updateDB){
            $this->menu_model->updates_restaurant_combos(array('selling_price'=>$total),$combo_id);
        }

        if($asJson)
            echo json_encode(array('total'=>num($total)));
    }
    public function override_combo_total($asJson=true,$updateDB=true){
        $this->load->model('resto/menu_model');
        $total = $this->input->post('total');
        $combo_id = $this->input->post('combo_id');
        $a = $total;
        $b = str_replace( ',', '', $a );

        if( is_numeric( $b ) ) {
            $a = $b;
        }
        $this->menu_model->updates_restaurant_combos(array('selling_price'=>$a),$combo_id);
    }
}