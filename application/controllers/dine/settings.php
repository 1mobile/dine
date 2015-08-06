<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
    public function promos(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $promo_list = $this->settings_model->get_promo_discounts();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Promos';
        $data['code'] = promo_list_form("settings/promo_form","promo_form","Promo",$promo_list,array('promo_name','promo_code'),'promo_code');

        $data['add_js'] = 'js/site_list_forms.js';
        $data['load_js'] = "dine/promos.php";
        $data['use_js'] = "promosJs";
        $this->load->view('page',$data);
    }
    public function promo_details_load($promo_id=null){
        $this->load->helper('site/site_forms_helper');
        $this->load->model('dine/settings_model');
        $promo = array();
        if($promo_id != null && $promo_id != 'add'){
            $promos = $this->settings_model->get_promo_discounts($promo_id);
            $promo = $promos[0];
        }

        $promo_scheds = array();
        if($promo_id != null && $promo_id != 'add'){
            $promo_scheds = $this->settings_model->get_promo_discount_schedules($promo_id);
        }
        $data['code'] = makePromoDetailsLoad($promo,$promo_id,$promo_scheds);
        $data['load_js'] = 'dine/promos.php';
        $data['use_js'] = 'promoDetailsJs';
        $this->load->view('load',$data);
    }
    public function promo_discount_sched_db(){
        $this->load->model('dine/settings_model');
        $promo_id = $this->input->post('promo_id');
        $day = $this->input->post('day');
        $items = array("time_on"=>$this->input->post('time-on'),
                        "time_off"=>$this->input->post('time-off'),
                        "day"=>$day,
                        "promo_id"=>$promo_id
                );
        $count = $this->settings_model->validate_discount_schedules($promo_id,$day);
        //echo 
        if(count($count) == 0){
            $id = $this->settings_model->add_promo_discount_schedules($items,$promo_id);
            echo json_encode(array('msg'=>'success'));
        }else{
            echo json_encode(array('msg'=>'error'));
        }
    }
    public function remove_promo_details(){
        $this->load->model('dine/settings_model');
        $promo_sched_id = $this->input->post('pr_sched_id');

        $this->settings_model->delete_promo_discount_schedule($promo_sched_id);
        echo json_encode(array('msg'=>'success'));
    }
    public function remove_promo_items(){
        $this->load->model('dine/settings_model');
        $promo_item_id = $this->input->post('pr_item_id');

        $this->settings_model->delete_promo_discount_item($promo_item_id);
        echo json_encode(array('msg'=>'success'));
    }
    public function promo_details_db(){
        $this->load->model('dine/settings_model');
        $promo_id = $this->input->post('promo_id');
        if($promo_id != null){
            $items = array("promo_code"=>$this->input->post('promo_code'),
                            "promo_name"=>$this->input->post('promo_name'),
                            "value"=>$this->input->post('value'),
                            "absolute"=>$this->input->post('absolute'),
                            "inactive"=>$this->input->post('inactive'),
                    );

            $id = $this->settings_model->update_promo_details($items,$promo_id);
        }else{
            $items = array("promo_code"=>$this->input->post('promo_code'),
                            "promo_name"=>$this->input->post('promo_name'),
                            "value"=>$this->input->post('value'),
                            "absolute"=>$this->input->post('absolute'),
                            "inactive"=>$this->input->post('inactive'),
                    );

            $id = $this->settings_model->add_promo_details($items);
        }
        echo json_encode(array('msg'=>'success'));
    }
    public function promo_detail_db(){
        $this->load->model('dine/settings_model');
        $items = array("item_id"=>$this->input->post('item'),
                        "promo_id"=>$this->input->post('promo_id')
                );
        $id = $this->settings_model->add_promo_item($items);
    }
    public function assign_load($promo_id){
        $this->load->helper('site/site_forms_helper');
        $this->load->model('dine/settings_model');
        $promo_items = array();
        if($promo_id != null && $promo_id != 'add'){
            $promo_items = $this->settings_model->get_promo_discount_items($promo_id);
        }

        $data['code'] = makeAssignItemsLoad($promo_items,$promo_id);
        $data['load_js'] = 'dine/promos.php';
        $data['use_js'] = 'assignedItemPromoJs';
        $this->load->view('load',$data);
    }
    public function assigned_item_db(){
        $this->load->model('dine/settings_model');
        $itm = $this->input->post('item');
        $promo_id = $this->input->post('promo_id');
        $items = array("item_id"=>$this->input->post('item'),
                        "promo_id"=>$this->input->post('promo_id')
                );
        // $id = $this->settings_model->add_promo_item($items);
        // echo json_encode(array('msg'=>'success'));

        $count = $this->settings_model->validate_promo_discount_items($promo_id,$itm);
        if(count($count) == 0){
            $id = $this->settings_model->add_promo_item($items,$promo_id);
            echo json_encode(array('msg'=>'success'));
        }else{
            echo json_encode(array('msg'=>'error'));
        }
   }

    public function uom(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $uom_list = $this->settings_model->get_uom();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'UOM Management';
        $data['code'] = site_list_form("settings/uom_form","uom_form","UOM",$uom_list,array('name','code'),'code');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function uom_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $uom = array();
        if($ref != null){
            $uoms = $this->settings_model->get_uom($ref);
            $uom = $uoms[0];
        }
        $data['code'] = makeUOMForm($uom);
        $this->load->view('load',$data);
    }
    public function uom_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code')
        );
        if($this->input->post('id')){
            $this->settings_model->update_uom($items,$this->input->post('code'));
            $id = $this->input->post('code');
            $act = 'update';
            $msg = 'Updated UOM. '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_uom($items);
            $id = $this->input->post('code');
            $act = 'add';
            $msg = 'Added  new UOM'.$this->input->post('name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
    public function discounts(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $discount_list = $this->settings_model->get_receipt_discount();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Discounts';
        $data['code'] = site_list_form("settings/discount_form","discount_form","Discounts",$discount_list,array('disc_name','disc_code'),'disc_id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function discount_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $disc = array();
        if($ref != null){
            $discounts = $this->settings_model->get_receipt_discount($ref);
            $disc = $discounts[0];
        }
        $data['code'] = makeDiscountForm($disc);
        $this->load->view('load',$data);
    }
    public function discount_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "disc_name"=>$this->input->post('name'),
            "disc_rate"=>$this->input->post('rate'),
            "disc_code"=>$this->input->post('code'),
        );
        if($this->input->post('disc_id')){
            $this->settings_model->update_receipt_discount($items, $this->input->post('disc_id'));
            $id = $this->input->post('disc_id');
            $act = 'update';
            $msg = 'Updated Discount: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_receipt_discount($items);
            $act = 'add';
            $msg = 'Added New Discount: '.$this->input->post('name');
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }

	//-----------Categories-----start-----allyn
	public function categories(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $category_list = $this->settings_model->get_category();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Category Management';
        // $data['code'] = site_list_form("settings/category_form","category_form","Category",$category_list,array('name','code'),'code');
        $data['code'] = site_list_form("settings/category_form","category_form","Categories",$category_list,array('name','code'),'cat_id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function category_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $category = array();
        if($ref != null){
            $categories = $this->settings_model->get_category($ref);
            $category = $categories[0];
        }
        $data['code'] = makeCategoryForm($category);
        $this->load->view('load',$data);
    }
    public function category_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('cat_id')){
            $this->settings_model->update_category($items, $this->input->post('cat_id'));
            $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Category: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_category($items);
            $act = 'add';
            $msg = 'Added New Category: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Categories-----end-----allyn
	//-----------Sub Categories-----start-----allyn
	public function subcategories(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $sub_cat_list = $this->settings_model->get_subcategory();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Sub Category Management';
        $data['code'] = site_list_form("settings/subcategory_form","subcategory_form","Sub Categories",$sub_cat_list,array('name','code'),'sub_cat_id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function subcategory_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $subcategory = array();
        if($ref != null){
            $subcategories = $this->settings_model->get_subcategory($ref);
            $subcategory = $subcategories[0];
        }
        $data['code'] = makeSubCategoryForm($subcategory);
        $this->load->view('load',$data);
    }
    public function subcategory_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "cat_id"=>$this->input->post('cat_id'),
            "name"=>$this->input->post('name'),
            "code"=>$this->input->post('code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('sub_cat_id')){
            $this->settings_model->update_subcategory($items, $this->input->post('sub_cat_id'));
            $id = $this->input->post('sub_cat_id');
            $act = 'update';
            $msg = 'Updated Sub Category: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_subcategory($items);
            $act = 'add';
            $msg = 'Added New Sub Category: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Sub Categories-----end-----allyn
	//-----------Suppliers-----start-----allyn
	public function suppliers(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $supplier_list = $this->settings_model->get_supplier();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Supplier Management';
        $data['code'] = site_list_form("settings/supplier_form","supplier_form","Suppliers",$supplier_list,'name','supplier_id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function supplier_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $supplier = array();
        if($ref != null){
            $suppliers = $this->settings_model->get_supplier($ref);
            $supplier = $suppliers[0];
        }
        $data['code'] = makeSupplierForm($supplier);
        $this->load->view('load',$data);
    }
    public function supplier_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "address"=>$this->input->post('address'),
            "contact_no"=>$this->input->post('contact_no'),
            "memo"=>$this->input->post('memo'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('supplier_id')){
            $this->settings_model->update_supplier($items, $this->input->post('supplier_id'));
            $id = $this->input->post('supplier_id');
            $act = 'update';
            $msg = 'Updated Supplier: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_supplier($items);
            $act = 'add';
            $msg = 'Added New Supplier: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name')." ".$this->input->post('code'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Suppliers-----end-----allyn
	//-----------Tax Rates-----start-----allyn
	public function tax_rates(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $tax_rates_list = $this->settings_model->get_tax_rates();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Tax Rate Management';
        $data['code'] = site_list_form("settings/tax_rate_form","tax_rate_form","Tax Rates",$tax_rates_list,'name','tax_id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function tax_rate_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $tax_rate = array();
        if($ref != null){
            $tax_rates = $this->settings_model->get_tax_rates($ref);
            $tax_rate = $tax_rates[0];
        }
        $data['code'] = makeTaxRateForm($tax_rate);
        $this->load->view('load',$data);
    }
    public function tax_rate_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "rate"=>$this->input->post('rate'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('tax_id')){
            $this->settings_model->update_tax_rates($items, $this->input->post('tax_id'));
            $id = $this->input->post('tax_id');
            $act = 'update';
            $msg = 'Updated Tax Rate: '.$this->input->post('name');
        }else{
            $id = $this->settings_model->add_tax_rates($items);
            $act = 'add';
            $msg = 'Added New Tax Rate: '.$this->input->post('name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Tax Rates-----end-----allyn
    // ---------- Receipt Discounts ---------- //
    public function receipt_discounts()
    {
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $receipt_discounts = $this->settings_model->get_receipt_discounts();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Receipt Discounts Management';
        $data['code'] = site_list_form(
                            "settings/receipt_discount_form"
                            , "receipt_discount_form"
                            , "Receipt Discounts"
                            , $receipt_discounts
                            , 'disc_name'
                            , 'disc_id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function receipt_discount_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $receipt_discount = array();
        if($ref != null){
            $receipt_discount = $this->settings_model->get_receipt_discounts($ref);
            $receipt_discount = $receipt_discount[0];
        }
        $data['code'] = makeReceiptDiscountForm($receipt_discount);
        $this->load->view('load',$data);
    }
    public function receipt_discount_db()
    {
        $this->load->model('dine/settings_model');
        $this->load->model('dine/main_model');
        $items = array(
            "disc_code"=>$this->input->post('disc_code'),
            "disc_name"=>$this->input->post('disc_name'),
            "disc_rate"=>$this->input->post('disc_rate'),
            "no_tax"=>(int)$this->input->post('no_tax'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('disc_id')){
            $this->settings_model->update_receipt_discount($items, $this->input->post('disc_id'));
            $id = $this->input->post('disc_id');
            $act = 'update';
            $msg = 'Updated Receipt Discount: '.$items['disc_name'];
            $this->main_model->update_tbl('receipt_discounts','disc_id',$items,$id);
        }else{
            $id = $this->settings_model->add_receipt_discount($items);
            $act = 'add';
            $msg = 'Added New Receipt Discount: '.$items['disc_name'];
            $this->main_model->add_trans_tbl('receipt_discounts',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$items['disc_name'],"act"=>$act,'msg'=>$msg));
    }
    // ---------- End of Receipt Discounts ---------- //
    public function seat_management(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Seating Management';
        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = makeTablesPage($branch);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] =  'seatingJs';
        $this->load->view('page',$data);
    }
    public function upload_image_seat_form(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');

        $branches = $this->settings_model->get_table_layout(1);
        $branch = $branches[0];

        $data['code'] = makeTableUploadForm($branch);
        $data['load_js'] = 'dine/settings.php';
        $data['use_js'] = 'uploadImageSeatJs';
        $this->load->view('load',$data);
    }
    public function upload_image_seat_db(){
        $this->load->model('dine/settings_model');
        $image = null;
        $upload = 'success';
        $msg = "";
        $src ="";

        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $info = pathinfo($_FILES['fileUpload']['name']);
            $ext = $info['extension'];
            $branch_id = $this->input->post('branch_id');
            $res_id = $this->input->post('res_id');
            $newname = "layout.".$ext;
            $target = "uploads/".$newname;
            if(move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $items = array(
                    'image'=>$newname
                );
                $this->settings_model->update_table_layout($items,1);
                $this->settings_model->delete_all_tables();
                // $id = 1;
                $msg = "Image Uploaded";
                $src = $target;
            }
            else{
                $mg = "Something went wrong.";
                $upload = "fail";
            }
        }

        echo json_encode(array("msg"=>$msg,"src"=>$src) );
    }
    public function get_tables($asJson=true){
        $this->load->model('dine/settings_model');
        $tables=array();
        $table_list = $this->settings_model->get_tables();
        foreach ($table_list as $res) {
            $tables[$res->tbl_id] = array(
                "name"=> $res->name,
                "top"=> $res->top,
                "left"=> $res->left
            );
        }
        if($asJson)
            echo json_encode($tables);
        else
            return $tables;
    }
    public function tables_form($tbl_id=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $table = array();
        if($tbl_id != null ){
            $tables = $this->settings_model->get_tables($tbl_id);
            $table = $tables[0];
        }
        $data['code'] = makeTableForm($table);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesTableJs';
        $this->load->view('load',$data);
    }
    public function tables_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "capacity"=>$this->input->post('capacity'),
            "name"=>$this->input->post('name'),
            "top"=>$this->input->post('top'),
            "left"=>$this->input->post('left')
        );
        if($this->input->post('delete')){
            $id = $this->input->post('delete');
            $this->settings_model->delete_tables($id);
            $msg = 'Deleted '.$this->input->post('name');
            $act = 'delete';
        }
        else{
            if($this->input->post('tbl_id')){
                $this->settings_model->update_tables($items,$this->input->post('tbl_id'));
                $id = $this->input->post('tbl_id');
                $act = 'update';
                $msg = 'Updated '.$this->input->post('name');
            }else{
                $id = $this->settings_model->add_tables($items);
                $act = 'add';
                $msg = 'Added '.$this->input->post('name');
            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Terminals-----start-----allyn
	public function terminals(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $terminal_list = $this->settings_model->get_terminal();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Terminal Management';
        $data['code'] = site_list_form("settings/terminal_form","terminal_form","Terminals",$terminal_list,array('terminal_name','terminal_code'),'terminal_id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function terminal_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $terminal = array();
        if($ref != null){
            $terminals = $this->settings_model->get_terminal($ref);
            $terminal = $terminals[0];
        }
        $data['code'] = makeTerminalForm($terminal);
        $this->load->view('load',$data);
    }
    public function terminal_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "terminal_code"=>$this->input->post('terminal_code'),
            "terminal_name"=>$this->input->post('terminal_name'),
            "ip"=>$this->input->post('ip'),
            "comp_name"=>$this->input->post('comp_name'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('terminal_id')){
            $this->settings_model->update_terminal($items, $this->input->post('terminal_id'));
            $id = $this->input->post('terminal_id');
            $act = 'update';
            $msg = 'Updated Terminal: '.$this->input->post('terminal_name');
        }else{
            $id = $this->settings_model->add_terminal($items);
            $act = 'add';
            $msg = 'Added New Terminal: '.$this->input->post('terminal_name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('terminal_name')." ".$this->input->post('terminal_code'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Terminals-----end-----allyn
	//-----------Currencies-----start-----allyn
	public function currencies(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $currency_list = $this->settings_model->get_currency();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Currency Management';
        $data['code'] = site_list_form("settings/currency_form","currency_form","Currencies",$currency_list,array('currency_desc','currency'),'id');
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function currency_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $currency = array();
        if($ref != null){
            $currencies = $this->settings_model->get_currency($ref);
            $currency = $currencies[0];
        }
        $data['code'] = makeCurrencyForm($currency);
        $this->load->view('load',$data);
    }
    public function currency_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "currency"=>$this->input->post('currency'),
            "currency_desc"=>$this->input->post('currency_desc'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('id')){
            $this->settings_model->update_currency($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Currency: '.$this->input->post('currency_desc');
        }else{
            $id = $this->settings_model->add_currency($items);
            $act = 'add';
            $msg = 'Added New Currency: '.$this->input->post('currency_desc');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('currency_desc')." ".$this->input->post('currency'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Currencies-----end-----allyn
	//-----------References-----start-----allyn
	public function references(){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $details = $this->settings_model->get_references();
		// $det = $details[0];
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'References';
        // $data['code'] = makeReferencesForm($det);
        $data['code'] = makeReferencesForm($details);
		// $data['code'] = site_list_form("settings/currency_form","currency_form","Currencies",$currency_list,array('currency_desc','currency'),'id');
		$data['load_js'] = 'dine/setup.php';
		$data['use_js'] = 'referencesJs';
        $this->load->view('page',$data);
    }
    public function references_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "next_ref"=>$this->input->post('next_ref')
        );

            $this->settings_model->update_references($items, $this->input->post('type_id'));
            // $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Reference : '.$this->input->post('name');

        echo json_encode(array('msg'=>$msg));
    }
	//-----------References-----end-----allyn
	//-----------Locations-----start-----allyn
	public function locations(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $location_list = $this->settings_model->get_location();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Location Management';
        // $data['code'] = site_list_form("settings/category_form","category_form","Locations",$location_list,array('name','code'),'code');
        $data['code'] = site_list_form("settings/location_form","location_form","Locations",$location_list,array('loc_name','loc_code'),'loc_id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function location_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $location = array();
        if($ref != null){
            $locations = $this->settings_model->get_location($ref);
            $location = $locations[0];
        }
        $data['code'] = makeLocationForm($location);
        $this->load->view('load',$data);
    }
    public function location_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "loc_name"=>$this->input->post('loc_name'),
            "loc_code"=>$this->input->post('loc_code'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('loc_id')){
            $this->settings_model->update_location($items, $this->input->post('loc_id'));
            $id = $this->input->post('loc_id');
            $act = 'update';
            $msg = 'Updated Location: '.$this->input->post('loc_name');
        }else{
            $id = $this->settings_model->add_location($items);
            $act = 'add';
            $msg = 'Added New Location: '.$this->input->post('loc_name');
		}
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('loc_name')." ".$this->input->post('loc_code'),"act"=>$act,'msg'=>$msg));
    }
	//-----------Locations-----end-----allyn


    //////////////////////////////////Jed start
   
    public function remove_item_assign(){
        $this->load->model('dine/settings_model');

        $ref = $this->input->post('ref');
        $del_item = $this->settings_model->delete_promo_item($ref);

    }
    public function remove_schedule(){
        $this->load->model('dine/settings_model');

        $ref = $this->input->post('ref');
        $del_sched = $this->settings_model->delete_promo_schedule($ref);

    }
    //////////////////////////////Jed end
    //-----------Denominations-----start-----jed
    public function denomination(){
        $this->load->model('dine/settings_model');
        $this->load->helper('site/site_forms_helper');
        $deno_list = $this->settings_model->get_denomination();
        $data = $this->syter->spawn('general_settings');
        $data['page_subtitle'] = 'Denomination Management';
        // $data['code'] = site_list_form("settings/category_form","category_form","Locations",$location_list,array('name','code'),'code');
        $data['code'] = site_list_form("settings/denomination_form","denomination_form","Denominations",$deno_list,array('desc'),'id');
        // $data['code'] = "";
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function denomination_form($ref=null){
        $this->load->helper('dine/settings_helper');
        $this->load->model('dine/settings_model');
        $deno = array();
        if($ref != null){
            $denomination = $this->settings_model->get_denomination($ref);
            $deno = $denomination[0];
        }
        $data['code'] = makeDenominationForm($deno);
        $this->load->view('load',$data);
    }
    public function denomination_db(){
        $this->load->model('dine/settings_model');
        $items = array(
            "desc"=>$this->input->post('desc'),
            "value"=>$this->input->post('value'),
            "img"=>null
        );
        if($this->input->post('id')){
            $this->settings_model->update_denomination($items, $this->input->post('id'));
            $id = $this->input->post('id');
            $act = 'update';
            $msg = 'Updated Denomination: '.$this->input->post('desc');
        }else{
            $id = $this->settings_model->add_denomination($items);
            $act = 'add';
            $msg = 'Added New Denomination: '.$this->input->post('desc');
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('desc'),"act"=>$act,'msg'=>$msg));
    }
    //-----------denominations-----end-----jed
}