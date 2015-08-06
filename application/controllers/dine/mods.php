<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mods extends CI_Controller {
	public function index(){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $mods = $this->mods_model->get_modifiers();
        $data['code'] = modListPage($mods);
        $this->load->view('page',$data);
    }
    public function form($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $data['code'] = modFormPage($mod_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modFormJs';
        $this->load->view('page',$data);
    }
    public function details_load($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $mod=array();
        if($mod_id != null){
            $mods = $this->mods_model->get_modifiers($mod_id);
            $mod=$mods[0];
        }
        $data['code'] = modDetailsLoad($mod,$mod_id);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'detailsLoadJs';
        $this->load->view('load',$data);
    }
    public function details_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "has_recipe"=>(int)$this->input->post('has_recipe'),
            "inactive"=>(int)$this->input->post('inactive'),
            "cost"=>$this->input->post('cost')
        );

        if($this->input->post('form_mod_id')){
            $this->mods_model->update_modifiers($items,$this->input->post('form_mod_id'));
            $id = $this->input->post('form_mod_id');
            $act = 'update';
            $msg = 'Updated Modifier '.$this->input->post('name');
            $this->main_model->update_tbl('modifiers','mod_id',$items,$id);
        }else{
            $id = $this->mods_model->add_modifiers($items);
            $act = 'add';
            $msg = 'Added  new Modifier '.$this->input->post('name');
            $this->main_model->add_trans_tbl('modifiers',$items);

        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function recipe_load($mod_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $details = $this->mods_model->get_modifier_recipe(null,$mod_id);

        $mods = $this->mods_model->get_modifiers($mod_id);
        $mod=$mods[0];

        $data['code'] = modRecipeLoad($mod_id,$details,$mod);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'recipeLoadJs';
        $this->load->view('load',$data);
    }
    public function search_items(){
        $search = $this->input->post('search');
        $this->load->model('dine/mods_model');
        $found = $this->mods_model->search_items($search);
        $items = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $items[] = array('key'=>$res->code." ".$res->name,'value'=>$res->item_id);
            }
        }
        echo json_encode($items);
    }
    public function get_item_details($item_id=null){
        $this->load->model('dine/items_model');
        $items = $this->items_model->get_item($item_id);
        $item = $items[0];
        $det['cost'] = $item->cost;
        $det['uom'] = $item->uom;
        echo json_encode($det);
    }
    public function recipe_db(){
        $this->load->model('dine/mods_model');
        $mod_id = $this->input->post('mod_id');
        $item_id = $this->input->post('item-id-hid');
        $gotItem = $this->mods_model->get_modifier_recipe(null,$mod_id,$item_id);
        $items = array(
            "mod_id"=>$mod_id,
            "item_id"=>$item_id,
            "uom"=>$this->input->post('item-uom-hid'),
            "qty"=>$this->input->post('qty'),
            "cost"=>$this->input->post('item-cost')
        );
        if(count($gotItem) > 0){
            $det = $gotItem[0];
            $this->mods_model->update_modifier_recipe($items,$det->mod_recipe_id);
            $id = $det->mod_recipe_id;
            $act = "update";
            $msg = "Updated Item ".$this->input->post('item-search');
        }else{
            $id = $this->mods_model->add_modifier_recipe($items);
            $act = "add";
            $msg = "Added New Item ".$this->input->post('item-search');
        }
        $this->make->sRow(array('id'=>'row-'.$id));
            $this->make->td($this->input->post('item-search'));
            $this->make->td(num($this->input->post('qty')));
            $this->make->td(num($this->input->post('item-cost')));
            $this->make->td(num($this->input->post('item-cost') * $this->input->post('qty')));
            $a = $this->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$id,'return'=>true));
            $this->make->td($a);
        $this->make->eRow();
        $row = $this->make->code();

        echo json_encode(array('row'=>$row,'msg'=>$msg,'act'=>$act,'id'=>$id));
    }
    public function remove_recipe_item(){
        $this->load->model('dine/mods_model');
        $this->mods_model->delete_modifier_recipe_item($this->input->post('mod_recipe_id'));
        $json['msg'] = "Item Deleted.";
        echo json_encode($json);
    }
    public function get_recipe_total($asJson=true,$updateDB=true){
        $this->load->model('dine/mods_model');
        $mod_id = $this->input->post('mod_id');
        $details = $this->mods_model->get_modifier_recipe_prices(null,$mod_id,null);
        $total = 0;
        foreach ($details as $res) {
            $total += $res->cost * $res->qty;
        }
        if($updateDB){
            $this->mods_model->update_modifiers(array('cost'=>$total),$mod_id);
        }

        if($asJson)
            echo json_encode(array('total'=>num($total)));
    }
    public function update_modifier_price($asJson=true,$updateDB=true){
        $this->load->model('dine/mods_model');
        $total = $this->input->post('total');
        $mod_id = $this->input->post('mod_id');
        $a = $total;
        $b = str_replace( ',', '', $a );

        if( is_numeric( $b ) ) {
            $a = $b;
        }
        $this->mods_model->update_modifiers(array('cost'=>$a),$mod_id);
    }
    public function groups(){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $data['page_subtitle'] = "Group Management";
        $grps = $this->mods_model->get_modifier_groups();
        $data['code'] = modGroupListPage($grps);
        $this->load->view('page',$data);
    }
    public function group_form($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $data = $this->syter->spawn('mods');
        $data['page_subtitle'] = "Group Management";
        $data['code'] = modGroupFormPage($mod_group_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'modGroupFormJs';
        $this->load->view('page',$data);
    }
    public function group_details_load($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $grp=array();
        if($mod_group_id != null){
            $grps = $this->mods_model->get_modifier_groups($mod_group_id);
            $grp=$grps[0];
        }
        $data['code'] = modGroupDetailsLoad($grp,$mod_group_id);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'groupDetailsLoadJs';
        $this->load->view('load',$data);
    }
    public function group_details_db(){
        $this->load->model('dine/mods_model');
        $this->load->model('dine/main_model');
        $items = array(
            "name"=>$this->input->post('name'),
            "mandatory"=>(int)$this->input->post('mandatory'),
            "multiple"=>(int)$this->input->post('multiple'),
            "inactive"=>(int)$this->input->post('inactive')
        );

        if($this->input->post('form_mod_group_id')){
            $this->mods_model->update_modifier_groups($items,$this->input->post('form_mod_group_id'));
            $id = $this->input->post('form_mod_group_id');
            $act = 'update';
            $msg = 'Updated Modifier Group '.$this->input->post('name');
            $this->main_model->update_tbl('modifier_groups','mod_group_id',$items,$id);
        }else{
            $id = $this->mods_model->add_modifier_groups($items);
            $act = 'add';
            $msg = 'Added  new Modifier Group '.$this->input->post('name');
            $this->main_model->add_trans_tbl('modifier_groups',$items);
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function group_modifiers_load($mod_group_id=null){
        $this->load->model('dine/mods_model');
        $this->load->helper('dine/mods_helper');
        $details = $this->mods_model->get_modifier_group_details(null,$mod_group_id);

        $data['code'] = groupModifiersLoad($mod_group_id,$details);
        $data['load_js'] = 'dine/mod.php';
        $data['use_js'] = 'groupRecipeLoadJs';
        $this->load->view('load',$data);
    }
    public function search_modifiers(){
        $search = $this->input->post('search');
        $this->load->model('dine/mods_model');
        $found = $this->mods_model->search_modifiers($search);
        $items = array();
        if(count($found) > 0 ){
            foreach ($found as $res) {
                $items[] = array('key'=>$res->name,'value'=>$res->mod_id);
            }
        }
        echo json_encode($items);
    }
    public function group_modifiers_details_db(){
        $this->load->model('dine/mods_model');
        $mod_group_id = $this->input->post('mod_group_id');
        $mod_id = $this->input->post('mod_id');
        $mod_text = $this->input->post('mod_text');
        $items = array(
            "mod_group_id" => $mod_group_id,
            "mod_id" => $mod_id
        );
        $gotDet = $this->mods_model->get_modifier_group_details(null,$mod_group_id,$mod_id);
        if(count($gotDet) > 0){
            $det = $gotDet[0];
            $this->mods_model->update_modifier_group_details($items,$det->id);
            $id = $det->id;
            $act = "update";
            $msg = "Updated Modifier ".$mod_text;
        }
        else{
            $id = $this->mods_model->add_modifier_group_details($items);
            $act = 'add';
            $msg = 'Added  Modifier '.$mod_text;
        }
        $li = $this->make->li(
                $this->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                $this->make->span($mod_text,array('class'=>'text','return'=>true))." ".
                $this->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del','id'=>'del-'.$id,'ref'=>$id)),
                array('return'=>true,'id'=>'li-'.$id)
             );
        echo json_encode(array("id"=>$id,"desc"=>$mod_text,"act"=>$act,'msg'=>$msg,'li'=>$li));
    }
    public function remove_group_modifier(){
        $this->load->model('dine/mods_model');
        $this->mods_model->delete_modifier_group_details($this->input->post('group_mod_id'));
        $json['msg'] = "Modifier Deleted.";
        echo json_encode($json);
    }
}