<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
    }
	public function index(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $this->load->helper('site/site_forms_helper');
        $data = $this->syter->spawn('menu');
        // $menus = $this->menu_model->get_menus();
        $th = array('ID','Name','Description','Category','Cost','Register Date','Inactive','');
        $data['code'] = create_rtable('menus','menu_id','menus-tbl',$th,'menu/search_menus_form');

        // $data['code'] = menuListPage($menus);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'listFormJs';
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $this->load->view('page',$data);
    }
    public function get_menus($id=null,$asJson=true){
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        $args = array();
        $total_rows = 20;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }
        if($this->input->post('menu_name')){
            $lk  =$this->input->post('menu_name');
            $args["(menus.menu_name like '%".$lk."%' OR menus.menu_short_desc like '%".$lk."%')"] = array('use'=>'where','val'=>"",'third'=>false);
        }
        // if($this->input->post('email')){
             // $args['users.email'] = array('use'=>'or_like','val'=>$this->input->post('email'));
        // }
        // if($this->input->post('email')){
            // $args['DATE(users.reg_date) = date('.date2Sql($this->input->post('reg_date')).')'] = array('use'=>'where','val'=>"",'third'=>false);
        // }
        if($this->input->post('inactive')){
            $args['menus.inactive'] = array('use'=>'where','val'=>$this->input->post('inactive'));
        }
        if($this->input->post('menu_cat_id')){
            $args['menus.menu_cat_id'] = array('use'=>'where','val'=>$this->input->post('menu_cat_id'));
        }
        $join["menu_categories"] = array('content'=>"menus.menu_cat_id = menu_categories.menu_cat_id");
        $count = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name',null,null,true);
        $page = paginate('menu/get_menus',$count,$total_rows,$pagi);
        $items = $this->site_model->get_tbl('menus',$args,array(),$join,true,'menus.*,menu_categories.menu_cat_name',null,$page['limit']);
        $json = array();
        if(count($items) > 0){
            foreach ($items as $res) {
                $link = $this->make->A(fa('fa-edit fa-lg'),base_url().'menu/form/'.$res->menu_id,array('return'=>'true'));
                $json[$res->menu_id] = array(
                    "id"=>$res->menu_id,   
                    "title"=>"[".$res->menu_code."] ".ucwords(strtolower($res->menu_name)),   
                    "desc"=>ucwords(strtolower($res->menu_short_desc)),   
                    "subtitle"=>ucwords(strtolower($res->menu_cat_name)),   
                    "price"=>"PHP ".num($res->cost),
                    "caption"=>sql2Date($res->reg_date),
                    "inactive"=>($res->inactive == 0 ? 'No' : 'Yes'),
                    "link"=>$link
                );
            }
        }
        echo json_encode(array('rows'=>$json,'page'=>$page['code'],'post'=>$post));
    }
    public function search_menus_form(){
        $data['code'] = menuSearchForm();
        $this->load->view('load',$data);
    }
    public function form($menu_id=null){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $data = $this->syter->spawn('menu');
        $data['code'] = menuFormPage($menu_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuFormJs';
        $this->load->view('page',$data);
    }
    public function upload_image_load($menu_id=null){
        $res = array();
        if($menu_id != null){
            $result = $this->site_model->get_image(null,$menu_id,'menus');
            if(count($result) > 0)
                $res = $result[0];
        }
        $data['code'] = menuImagesLoad($menu_id,$res);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuImageJs';
        $this->load->view('load',$data);
    }
    public function images_db(){
        $image = null;
        // if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
        //     $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        // }
        $ext = null;
        $msg = "";
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])){
            $info = pathinfo($_FILES['fileUpload']['name']);
            if(isset($info['extension']))
            $ext = $info['extension'];
            $menu = $this->input->post('form_menu_id');
            $newname = $menu.".".$ext;
            if (!file_exists("uploads/menus/")) {
                mkdir("uploads/menus/", 0777, true);
            }
            $target = 'uploads/menus/'.$newname;
            if(!move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $msg = "Image Upload failed";
            }
            else{
                $new_image = $target;
                $result = $this->site_model->get_image(null,$this->input->post('form_menu_id'),'menus');
                $items = array(
                    "img_file_name" => $newname,
                    "img_path" => $new_image,
                    "img_ref_id" => $this->input->post('form_menu_id'),
                    "img_tbl" => 'menus',
                );
                if(count($result) > 0){
                    $this->site_model->update_tbl('images','img_id',$items,$result[0]->img_id);
                }
                else{
                    $id = $this->site_model->add_tbl('images',$items,array('datetime'=>'NOW()'));
                }
            }
            ####
        }

        echo $msg;
    }
    public function details_load($menu_id=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $menu=array();
        if($menu_id != null){
            $menus = $this->menu_model->get_menus($menu_id);
            $menu=$menus[0];
        }
        $data['code'] = menuDetailsLoad($menu,$menu_id);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'detailsLoadJs';
        $this->load->view('load',$data);
    }
    public function details_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $items = array(
            "menu_code"=>$this->input->post('menu_code'),
            "menu_cat_id"=>$this->input->post('menu_cat_id'),
            "menu_sub_cat_id"=>$this->input->post('menu_sub_cat_id'),
            "menu_barcode"=>$this->input->post('menu_barcode'),
            "menu_sched_id"=>$this->input->post('menu_sched_id'),
            "menu_short_desc"=>$this->input->post('menu_short_desc'),
            "menu_name"=>$this->input->post('menu_name'),
            "cost"=>$this->input->post('cost'),
            "no_tax"=>(int)$this->input->post('no_tax'),
            "free"=>(int)$this->input->post('free'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('new')){
            $id = $this->menu_model->add_menus($items);
            $act = 'add';
            $msg = 'Added  new Menu '.$this->input->post('menu_name');
            $items['menu_id'] = $id;
            $this->main_model->add_trans_tbl('menus',$items);
            site_alert($msg,'success');
        }
        else{
            if($this->input->post('form_menu_id')){
                $this->menu_model->update_menus($items,$this->input->post('form_menu_id'));
                $id = $this->input->post('form_menu_id');
                $act = 'update';
                $msg = 'Updated Menu '.$this->input->post('menu_name');
                $this->main_model->update_tbl('menus','menu_id',$items,$id);
            }else{
                $id = $this->menu_model->add_menus($items);
                $act = 'add';
                $msg = 'Added  new Menu '.$this->input->post('menu_name');
                $items['menu_id'] = $id;
                $this->main_model->add_trans_tbl('menus',$items);
            }
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('menu_name'),"act"=>$act,'msg'=>$msg));
    }
    public function recipe_load($menu_id=null)
    {
        $det = $this->menu_model->get_recipe_items($menu_id);
        $data['code'] = menuRecipeLoad($menu_id,null,$det);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'recipeLoadJs';
        $this->load->view('load',$data);
    }
    public function recipe_search_item()
    {
        $search = $this->input->post('search');
        $results = $this->menu_model->search_items($search);
        $items = array();
        if(count($results) > 0 ){
            foreach ($results as $res) {
                $items[] = array('key'=>$res->code." ".$res->name,'value'=>$res->item_id);
            }
        }
        echo json_encode($items);
    }
    public function recipe_item_details($item_id=null)
    {
        $this->load->model('dine/items_model');
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];
        $det['cost'] = $item->cost;
        $det['uom'] = $item->uom;
        echo json_encode($det);
    }
    public function recipe_details_db()
    {
        $items = array(
            'menu_id' => $this->input->post('menu-id-hid'),
            'item_id' => $this->input->post('item-id-hid'),
            'uom' => $this->input->post('item-uom-hid'),
            'qty' => $this->input->post('qty'),
            'cost' => $this->input->post('item-cost')
        );

        $recipe_det = $this->menu_model->get_recipe_items($items['menu_id'],$items['item_id']);
        if (count($recipe_det) > 0) {
            $det = $recipe_det[0];
            $this->menu_model->update_recipe_item($items,$items['menu_id'],$items['item_id']);
            $id = $det->recipe_id;
            $item_name = $det->item_name;
            $act = "update";
            $msg = "Updated item: ".$item_name;
        } else {
            $this->load->model('dine/items_model');
            $detx = $this->items_model->get_item($items['item_id']);
            $detx = $detx[0];

            $item_name = $detx->name;
            $id = $this->menu_model->add_recipe_item($items);
            $act = "add";
            $msg = "Add new item: ".$this->input->post('item-search');
        }

        $this->make->sRow(array('id'=>'row-'.$id));
            $this->make->td($item_name);
            $this->make->td($items['uom']);
            $this->make->td($items['qty']);
            $this->make->td($items['cost']);
            $this->make->td($items['qty'] * $items['cost']);
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('id'=>$id,'row'=>$row,'msg'=>$msg,'act'=>$act));
    }
    public function override_price_total($asJson=true,$updateDB=true){
        $this->load->model('resto/menu_model');
        $total = $this->input->post('total');
        $menu_id = $this->input->post('menu_id');
        $a = $total;
        $b = str_replace( ',', '', $a );

        if( is_numeric( $b ) ) {
            $a = $b;
        }
        $this->menu_model->update_menus(array('cost'=>$a),$menu_id);
    }
    public function get_recipe_total()
    {
        $menu_id = $this->input->post('menu_id');
        $recipe_det = $this->menu_model->get_recipe_items($menu_id);
        $total = 0;
        foreach ($recipe_det as $val) {
            $total += ($val->item_cost * $val->qty);
        }
        echo json_encode(array('total'=>num($total)));
    }
    public function remove_recipe_item()
    {
        $recipe_id = $this->input->post('recipe_id');
        $this->menu_model->remove_recipe_item($recipe_id);
        $json['msg'] = "Recipe Item Deleted.";
        echo json_encode($json);
    }
    /**********     Menu Modifier Groups   **********/
    public function modifier_load($menu_id=null)
    {
        $det = $this->menu_model->get_menu_modifiers($menu_id);
        $data['code'] = menuModifierLoad($menu_id,$det);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'menuModifierJs';
        $this->load->view('load',$data);
    }
    public function modifier_search_item()
    {
        $search = $this->input->post('search');
        $results = $this->menu_model->search_modifier_groups($search);
        $items = array();
        if(count($results) > 0 ){
            foreach ($results as $res) {
                $items[] = array('key'=>$res->mod_group_id." ".$res->name,'value'=>$res->mod_group_id);
            }
        }
        echo json_encode($items);
    }
    public function menu_modifier_db()
    {
        if (!$this->input->post())
            header('Location:'.base_url().'menu');

        $items = array(
            'menu_id' => $this->input->post('menu-id-hid'),
            'mod_group_id' => $this->input->post('mod-group-id-hid'),
        );

        $det = $this->menu_model->get_menu_modifiers($items['menu_id'],$items['mod_group_id']);

        if (count($det) == 0) {
            $id = $this->menu_model->add_menu_modifier($items);

            $mod_group = $this->menu_model->get_modifier_groups(array('mod_group_id'=>$items['mod_group_id']));
            $mod_group = $mod_group[0];

            $this->make->sRow(array('id'=>'row-'.$id));
                $this->make->td(fa('fa-asterisk')." ".$mod_group->name);
                $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'ref'=>$id,'class'=>'del-item','return'=>true));
                $this->make->td($a);
            $this->make->eRow();

            $row = $this->make->code();

            echo json_encode(array('result'=>'success','msg'=>'Modifier group has been added','row'=>$row));
        } else
            echo json_encode(array('result'=>'error','msg'=>'Menu already has modifier group'));

    }
    public function remove_menu_modifier()
    {
        $id = $this->input->post('id');
        $this->menu_model->remove_menu_modifier($id);
        $json['msg'] = 'Removed modifier group';
        echo json_encode($json);
    }
    /*******   End of  Menu Modifier Groups   *******/
    public function categories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('site/site_forms_helper');
        $menu_categories = $this->menu_model->get_menu_categories();
        $data = $this->syter->spawn('menu');
        $data['page_subtitle'] = "Categories";
        $data['code'] = site_list_form("menu/categories_form","categories_form","Categories",$menu_categories,'menu_cat_name',"menu_cat_id");
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function categories_form($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_categories($ref);
            $cat = $cats[0];
        }
        $this->data['code'] = makeMenuCategoriesForm($cat);
        $this->load->view('load',$this->data);
    }
    public function categories_form_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $items = array();
        $items = array(
            "menu_cat_name"=>$this->input->post('menu_cat_name'),
            "menu_sched_id"=>$this->input->post('menu_sched_id'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('menu_cat_id')){
            $this->menu_model->update_menu_categories($items,$this->input->post('menu_cat_id'));
            $id = $this->input->post('menu_cat_id');
            $act = 'update';
            $msg = 'Updated Menu Category . '.$this->input->post('menu_cat_name');
            $this->main_model->update_tbl('menu_categories','menu_cat_id',$items,$id);
        }else{
            $id = $this->menu_model->add_menu_categories($items);
            $act = 'add';
            $msg = 'Added  new Menu Category '.$this->input->post('menu_cat_name');
            $this->main_model->add_trans_tbl('menu_categories',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('menu_cat_name'),"act"=>$act,'msg'=>$msg));
    }
    public function subcategories(){
        $this->load->model('dine/menu_model');
        $this->load->helper('site/site_forms_helper');
        $menu_subcategories = $this->menu_model->get_menu_subcategories();
        $data = $this->syter->spawn('menu');
        $data['page_subtitle'] = "Sub Categories";
        $data['code'] = site_list_form("menu/subcategories_form","subcategories_form","Sub Categories",$menu_subcategories,'menu_sub_cat_name',"menu_sub_cat_id");
        $data['add_js'] = 'js/site_list_forms.js';
        $this->load->view('page',$data);
    }
    public function subcategories_form($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $cat = array();
        if($ref != null){
            $cats = $this->menu_model->get_menu_subcategories($ref);
            $cat = $cats[0];
        }
        $this->data['code'] = makeMenuSubCategoriesForm($cat);
        $this->load->view('load',$this->data);
    }
    public function subcategories_form_db(){
        $this->load->model('dine/menu_model');
        $this->load->model('dine/main_model');
        $items = array();
        $items = array(
            "menu_sub_cat_name"=>$this->input->post('menu_sub_cat_name'),
            "inactive"=>(int)$this->input->post('inactive')
        );
        if($this->input->post('menu_sub_cat_id')){
            $this->menu_model->update_menu_subcategories($items,$this->input->post('menu_sub_cat_id'));
            $id = $this->input->post('menu_sub_cat_id');
            $act = 'update';
            $msg = 'Updated Menu Sub Category . '.$this->input->post('menu_sub_cat_name');
            $this->main_model->update_tbl('menu_subcategories','menu_sub_cat_id',$items,$id);
        }else{
            $id = $this->menu_model->add_menu_subcategories($items);
            $act = 'add';
            $msg = 'Added  new Menu Sub Category '.$this->input->post('menu_sub_cat_name');
            $this->main_model->add_trans_tbl('menu_subcategories',$items);
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('menu_sub_cat_name'),"act"=>$act,'msg'=>$msg));
    }
    public function schedules(){
        $this->load->model('dine/menu_model');
        $this->load->helper('site/site_forms_helper');
        $menu_schedules = $this->menu_model->get_menu_schedules();
        $data = $this->syter->spawn('menu');
        $data['page_subtitle'] = "Schedules";
        $data['code'] = site_list_form("menu/schedules_form","schedules_form","Schedules",$menu_schedules,'desc',"menu_sched_id");

        $data['add_js'] = 'js/site_list_forms.js';

        $this->load->view('page',$data);
    }
    public function schedules_form($ref=null){
        $this->load->helper('dine/menu_helper');
        $this->load->model('dine/menu_model');
        $sch = array();
        // if($ref == null)    $ref = $this->input->post('menu_sched_id');
        if($ref != null){
            $schs = $this->menu_model->get_menu_schedules($ref);
            // echo 'REF :: '.$ref;
            $sch = $schs[0];
        }
        $dets = $this->menu_model->get_menu_schedule_details($ref);

        $data['code'] = makeMenuSchedulesForm($sch,$dets);
        $data['load_js'] = 'dine/menu.php';
        $data['use_js'] = 'scheduleJs';
        $this->load->view('load',$data);
    }
    public function menu_sched_db(){
        $this->load->model('dine/menu_model');
        $items = array();
        $items = array("desc"=>$this->input->post('desc'),
                        "inactive"=>(int)$this->input->post('inactive')
            );
        $id = $this->input->post('menu_sched_id');
        $add = "add";
        if($id != ''){
            $this->menu_model->update_menu_schedules($items,$id);
            $add = "upd";
        }else{
            $id = $this->menu_model->add_menu_schedules($items);
        }

        echo json_encode(array("id"=>$id,"act"=>$add,"desc"=>$this->input->post('desc')));
    }
    public function menu_sched_details_db(){
        $this->load->model('dine/menu_model');
        $items = array();
        $items = array("day"=>$this->input->post('day'),
                        "time_on"=>date('H:i:s',strtotime($this->input->post('time_on'))),
                        "time_off"=>date('H:i:s',strtotime($this->input->post('time_off'))),
                        "menu_sched_id"=>$this->input->post('sched_id')
                        );
        // $id = $this->input->post('sched_id');
        $day = $this->input->post('day');

        $count = $this->menu_model->validate_menu_schedule_details($this->input->post('sched_id'),$day);
        if($count == 0){
            // if($id != '')    $this->menu_model->update_menu_schedule_details($items,$id);
            // else             $this->menu_model->add_menu_schedule_details($items);
            $id = $this->menu_model->add_menu_schedule_details($items);
            // echo json_encode(array("msg"=>'success'));
            echo json_encode(array("msg"=>'Successfully Added',"id"=>$this->input->post('sched_id')));
        }else{
            echo json_encode(array("msg"=>'error',"id"=>$this->input->post('sched_id')));
            // echo json_encode(array("msg"=>$count));
            // echo json_encode(array("msg"=>$this->db->last_query()));
        }
    }
    public function remove_schedule_promo_details(){
        $id = $this->input->post('pr_sched_id');
        $this->menu_model->delete_menu_schedule_details($id);
        echo json_encode(array("msg"=>'Successfully Deleted'));
    }
}