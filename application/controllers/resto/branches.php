<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branches extends CI_Controller {
    var $data = null;
    public function branch($res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/restaurant_model');
        $this->load->model('resto/branches_model');
        $restaurants = $this->restaurant_model->get_restaurants($res_id);
        $branches = $this->branches_model->get_restaurant_branches(null,$res_id);
        $resto = $restaurants[0];
        $data = $this->syter->spawn('restaurants');

        $data['code'] = makeBranchesPage($branches,$res_id);
        $data['load_js'] = "resto/branches.php";
        $data['use_js'] = "branchesJs";
        $data['page_title'] = fa('fa-cutlery fa-fw')." ".$resto->res_name." Branches";
        $this->load->view('page',$data);
    }
    public function details_load($branch_id=null,$res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $branch = array();
        if($branch_id != null && $branch_id != 'add'){
            $branches = $this->branches_model->get_restaurant_branches($branch_id);
            $branch = $branches[0];
        }

        $data['code'] = makeDetailsLoad($branch,$res_id);

        $data['add_js'] = 'js/plugins/map/gmap3.min.js';
        $data['load_js'] = 'resto/branches.php';
        $data['use_js'] = 'branchesDetailsJs';

        $this->load->view('load',$data);
    }
    public function branch_details_db(){
        $this->load->model('resto/branches_model');
        $items = array(
            "res_id"=>$this->input->post('res_id'),
            "branch_name"=>$this->input->post('branch_name'),
            "branch_desc"=>$this->input->post('branch_desc'),
            "delivery_no"=>$this->input->post('delivery_no'),
            "currency"=>$this->input->post('currency'),
            "contact_no"=>$this->input->post('contact_no'),
            "address"=>$this->input->post('address'),
            "base_location"=>$this->input->post('base_loc')
        );

        if($this->input->post('branch_id')){
            $this->branches_model->update_restaurant_branches($items,$this->input->post('branch_id'));
            $id = $this->input->post('branch_id');
            $act = 'update';
            $msg = 'Updated Restaurant Branch. '.$this->input->post('branch_name');
        }else{
            $id = $this->branches_model->add_restaurant_branches($items);
            $act = 'add';
            $msg = 'Added  new Restaurant Branch '.$this->input->post('branch_name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('branch_name'),"act"=>$act,'msg'=>$msg));
    }
    public function staffs_load($branch_id=null,$res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $staffs = array();
        if($branch_id != null && $branch_id != 'add'){
            $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        }
        $data['code'] = makeStaffLoad($branch_id,$staffs);
        $data['load_js'] = 'resto/branches.php';
        $data['use_js'] = 'branchesStaffJs';
        $this->load->view('load',$data);
    }
    public function branch_staffs_db(){
        $this->load->model('resto/branches_model');
        $li = "";
        $msg = "";
        $json = array();

        if($this->input->post('br_staff_id')){
            $this->branches_model->delete_restaurant_branch_staffs($this->input->post('br_staff_id'));
            $json['msg'] = "Staff Deleted.";
        }
        else{
            $branch_id = $this->input->post('branch_id');
            $user = $this->input->post('user');
            $check = $this->branches_model->get_restaurant_branch_staffs($user,$branch_id);
            if(count($check) > 0 ){
                $json['msg'] = "User is already added.";
                $json['found'] = 1;
                $json['id'] = 0;
            }
            else{
                $type = $this->input->post('type');
                $access = "";
                if($this->input->post('staff-access'))
                    $access = $this->input->post('staff-access');
                $items = array(
                    'branch_id'=>$branch_id,
                    'user_id'=>$user,
                    'staff_id'=>$type,
                    'access'=>$access
                );
                $id = $this->branches_model->add_restaurant_branch_staffs($items);
                $details = $this->branches_model->get_restaurant_branch_staffs($user,$branch_id);
                $res = $details[0];
                $json['msg'] = "Added ".$res->fname." ".$res->mname." ".$res->lname." ".$res->suffix." as ".$res->staff_name;
                $li = $this->make->li(
                            $this->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                            $this->make->span($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,array('class'=>'text','return'=>true))." ".
                            $this->make->span($res->staff_name,array('class'=>'label label-success li-info','return'=>true))." ".
                            $this->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-staff','id'=>'del-staff-'.$res->id,'ref'=>$res->id)),
                            array('return'=>true)
                      );
                $json['li'] = $li;
                $json['id'] = $res->id;
                $json['found'] = 0;
            }
        }
        echo json_encode($json);
    }
    public function tables_load($branch_id=null,$res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        // $tables = array();
        
        $branches = $this->branches_model->get_restaurant_branches($branch_id);
        $branch = $branches[0];
        // if($branch_id != null && $branch_id != 'add'){
        //     // $staffs = $this->branches_model->get_restaurant_branch_staffs(null,$branch_id);
        // }
        $data['code'] = makeTablesLoad($branch_id,$branch,$res_id);
        $data['add_css'] = 'css/rtag.css';
        $data['load_js'] = 'resto/branches.php';
        $data['use_js'] = 'branchesTableJs';
        $this->load->view('load',$data);
    }
    public function upload_image_form($branch_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        
        $branches = $this->branches_model->get_restaurant_branches($branch_id);
        $branch = $branches[0];
       
        $data['code'] = makeTableUploadForm($branch,$branch_id);
        $data['load_js'] = 'resto/branches.php';
        $data['use_js'] = 'branchesUploadImageTableJs';
        $this->load->view('load',$data);
    }
    public function upload_table_image(){
        $this->load->model('resto/branches_model');
        $image = null;
        $upload = 'success';
        $msg = "";

        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $info = pathinfo($_FILES['fileUpload']['name']);
            $ext = $info['extension'];
            $branch_id = $this->input->post('branch_id');
            $res_id = $this->input->post('res_id');
            $newname = $branch_id.".".$ext;
            if (!file_exists("uploads/".$res_id."/"."layout")) {
              mkdir("uploads/".$res_id."/"."layout", 0777, true);
            }
            $target = "uploads/".$res_id."/"."layout/".$newname;
            if(move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target)){
                $items = array(
                    'image'=>$this->input->post('branch_id').'.'.$ext
                );
                $this->branches_model->update_restaurant_branches($items,$this->input->post('branch_id'));
                $this->branches_model->delete_restaurant_branch_tables_by_branch($this->input->post('branch_id'));
                $id = $this->input->post('branch_id');
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
    public function tables_form($branch_id=null,$tbl_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $table = array();
        if($tbl_id != null ){
            $tables = $this->branches_model->get_restaurant_branch_tables($tbl_id,$branch_id);
            $table = $tables[0];
        }
        $data['code'] = makeTableForm($table,$branch_id);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesTableJs';
        $this->load->view('load',$data);
    }
    public function tables_db(){
        $this->load->model('resto/branches_model');
        $items = array(
            "branch_id"=>$this->input->post('branch_id'),
            "capacity"=>$this->input->post('capacity'),
            "name"=>$this->input->post('name'),
            "top"=>$this->input->post('top'),
            "left"=>$this->input->post('left')
        );
        if($this->input->post('delete')){
            $id = $this->input->post('delete');
            $this->branches_model->delete_restaurant_branch_tables($id);
            $msg = 'Deleted '.$this->input->post('name');
            $act = 'delete';
        }
        else{
            if($this->input->post('tbl_id')){
                $this->branches_model->update_restaurant_branch_tables($items,$this->input->post('tbl_id'));
                $id = $this->input->post('tbl_id');
                $act = 'update';
                $msg = 'Updated '.$this->input->post('name');
            }else{
                $id = $this->branches_model->add_restaurant_branch_tables($items);
                $act = 'add';
                $msg = 'Added '.$this->input->post('name');
            }
        }
        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function get_tables($branch_id=null,$asJson=true){
        $this->load->model('resto/branches_model');
        $tables=array();
        $table_list = $this->branches_model->get_restaurant_branch_tables(null,$branch_id);
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
    public function menu_load($branch_id=null,$res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $this->load->model('resto/menu_model');

        $items = $this->menu_model->get_restaurant_items(null,$res_id);
        $combos = $this->menu_model->get_restaurant_combos(null,$res_id);
        $item_list = $this->branches_model->get_restaurant_branch_menu_item(null,null,$branch_id);
        $combo_list = $this->branches_model->get_restaurant_branch_menu_combo(null,null,$branch_id);

        $data['code'] = makeMenuLoad($branch_id,$items,$combos,$item_list,$combo_list);
        $data['load_js'] = 'resto/branches.php';
        $data['use_js'] = 'branchesMenuJs';
        $this->load->view('load',$data);
    }
    public function branch_menu_item_form($branch_id=null,$item_id=null,$menu_item_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $this->load->model('resto/menu_model');
        if($menu_item_id != null){
            $details = $this->branches_model->get_restaurant_branch_menu_item($menu_item_id);
            $det = $details[0];
        }
        else{
            $details = $this->menu_model->get_restaurant_items($item_id);
            $det = $details[0];
        }

        $data['code'] = makeMenuItemForm($branch_id,$det);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesMenuJs';
        $this->load->view('load',$data);
    }
    public function branch_menu_item_db(){
        $this->load->model('resto/branches_model');
        $li = "";
        $msg = "";
        $json = array();

        if($this->input->post('remove_item')){
            $this->branches_model->delete_restaurant_branch_menu_item($this->input->post('remove_item'));
            $json['msg'] = "Item Removed from list";
        }
        else{
            $branch_id = $this->input->post('branch_id');
            $item_id = $this->input->post('item_id');
            if($this->input->post('menu_item_id')){
                $items = array(
                    'branch_id'=>$branch_id,
                    'item_id'=>$item_id,
                    'price'=>$this->input->post('price'),
                    'portion_price'=>$this->input->post('portion_price')
                );
                $this->branches_model->update_restaurant_branch_menu_item($items,$this->input->post('menu_item_id'));
                $id = $this->input->post('menu_item_id');
                $json['act'] = 'update';
                $json['msg'] = 'Updated Branch List';
                $json['found'] = 0;
                $json['price'] = $this->input->post('price');
                $json['id'] = $id;
            }
            else{
                $check = $this->branches_model->get_restaurant_branch_menu_item(null,$item_id,$branch_id);
                if(count($check) > 0 ){
                    $json['msg'] = "Item is already in the branch menu list.";
                    $json['found'] = 1;
                    $json['id'] = 0;
                }
                else{
                    $items = array(
                        'branch_id'=>$branch_id,
                        'item_id'=>$item_id,
                        'price'=>$this->input->post('price'),
                        'portion_price'=>$this->input->post('portion_price')
                    );
                    $id = $this->branches_model->add_restaurant_branch_menu_item($items);
                    $details = $this->branches_model->get_restaurant_branch_menu_item($id);
                    $res = $details[0];
                    $json['msg'] = "Added ".$res->item_name." to Branch Menu List";
                    $json['act'] = 'add';
                    $json['id'] = $res->menu_item_id;
                    $json['found'] = 0;
                    $div="";

                    $thumb = base_url().'img/noimage.png';
                    if($res->img  != ""){
                        $thumb = blob2Image($res->img);
                    }
                    $this->make->sDivCol(4);
                        $this->make->sDiv(array('class'=>'media'));

                            $img = $this->make->img($thumb,array('class'=>'media-object','return'=>true,'height'=>"60"));
                            $this->make->A($img,'branches/branch_menu_item_form/'.$res->branch_id."/".$res->item_id."/".$res->menu_item_id,array(
                                            'class'=>'add-item pull-left',
                                            'rata-title'=>'Update Item '.$res->item_code." ".$res->item_name,
                                            'rata-pass'=>'branches/branch_menu_item_db',
                                            'rata-form'=>'menu_item_form',
                                            'ref'=>$res->item_id,
                                            'id'=>'add-item-'.$res->menu_item_id
                                        ));
                            $this->make->sDiv(array('class'=>'media-body'));
                                // $sub = $CI->make->small('Something',array('return'=>true));
                                $this->make->H(5,strong($res->item_name),array('class'=>'media-object'));
                                $this->make->H(6,num($res->price),array('class'=>'media-object','id'=>'branch-item-price-'.$res->menu_item_id));
                                $a = $this->make->A('[Remove]','#',array('return'=>true,'class'=>'del-menu-item','id'=>'del-menu-item-'.$res->menu_item_id,'ref'=>$res->menu_item_id));
                                $this->make->H(6,$a,array('class'=>'media-object'));
                            $this->make->eDiv();
                        $this->make->eDiv();
                    $this->make->eDivCol();

                    $div = $this->make->code();
                    $json['div'] = $div;
                }
            }
        }
        echo json_encode($json);
    }
    public function branch_menu_combo_form($branch_id=null,$combo_id=null,$menu_combo_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $this->load->model('resto/menu_model');

        if($menu_combo_id != null){
            $details = $this->branches_model->get_restaurant_branch_menu_combo($menu_combo_id);
            $det = $details[0];
        }
        else{
            $details = $this->menu_model->get_restaurant_combos($combo_id);
            $det = $details[0];
        }
        $data['code'] = makeMenuComboForm($branch_id,$det);
        // $data['load_js'] = 'resto/branches.php';
        // $data['use_js'] = 'branchesMenuJs';
        $this->load->view('load',$data);
    }
    public function branch_menu_combo_db(){
        $this->load->model('resto/branches_model');
        $li = "";
        $msg = "";
        $json = array();

        if($this->input->post('remove_combo')){
            $this->branches_model->delete_restaurant_branch_menu_combo($this->input->post('remove_combo'));
            $json['msg'] = "Combo Removed from list";
        }
        else{
            $branch_id = $this->input->post('branch_id');
            $combo_id = $this->input->post('combo_id');
            if($this->input->post('menu_combo_id')){
                $items = array(
                    'branch_id'=>$branch_id,
                    'combo_id'=>$combo_id,
                    'selling_price'=>$this->input->post('selling_price'),

                );
                $this->branches_model->update_restaurant_branch_menu_combo($items,$this->input->post('menu_combo_id'));
                $id = $this->input->post('menu_combo_id');
                $json['act'] = 'update';
                $json['msg'] = 'Updated Branch List';
                $json['found'] = 0;
                $json['price'] = $this->input->post('selling_price');
                $json['id'] = $id;
            }
            else{
                $check = $this->branches_model->get_restaurant_branch_menu_combo(null,$combo_id,$branch_id);
                if(count($check) > 0 ){
                    $json['msg'] = "Combo is already in the branch menu list.";
                    $json['found'] = 1;
                    $json['id'] = 0;
                }
                else{
                    $items = array(
                       'branch_id'=>$branch_id,
                        'combo_id'=>$combo_id,
                        'selling_price'=>$this->input->post('selling_price'),
                    );
                    $id = $this->branches_model->add_restaurant_branch_menu_combo($items);
                    $details = $this->branches_model->get_restaurant_branch_menu_combo($id);
                    $res = $details[0];
                    $json['msg'] = "Added ".$res->combo_name." to Branch Menu List";
                    $json['act'] = 'add';
                    $json['id'] = $res->menu_combo_id;
                    $json['found'] = 0;
                    $div="";

                    $thumb = base_url().'img/noimage.png';
                    if($res->img  != ""){
                        $thumb = blob2Image($res->img);
                    }
                    $this->make->sDivCol(4);
                        $this->make->sDiv(array('class'=>'media'));
                            $img = $this->make->img($thumb,array('class'=>'media-object','return'=>true,'height'=>"60"));
                            $this->make->A($img,'branches/branch_menu_combo_form/'.$res->branch_id."/".$res->combo_id."/".$res->menu_combo_id,array(
                                            'class'=>'add-combo pull-left',
                                            'rata-title'=>'Update Combo '.$res->combo_code." ".$res->combo_name,
                                            'rata-pass'=>'branches/branch_menu_combo_db',
                                            'rata-form'=>'menu_combo_form',
                                            'ref'=>$res->combo_id,
                                            'id'=>'add-combo-'.$res->menu_combo_id
                                        ));
                            $this->make->sDiv(array('class'=>'media-body'));
                                // $sub = $CI->make->small('Something',array('return'=>true));
                                $this->make->H(5,strong($res->combo_name),array('class'=>'media-object'));
                                $this->make->H(6,num($res->selling_price),array('class'=>'media-object','id'=>'branch-combo-price-'.$res->menu_combo_id));
                                $a = $this->make->A('[Remove]','#',array('return'=>true,'class'=>'del-menu-combo','id'=>'del-menu-combo-'.$res->menu_combo_id,'ref'=>$res->menu_combo_id));
                                $this->make->H(6,$a,array('class'=>'media-object'));
                            $this->make->eDiv();
                        $this->make->eDiv();
                    $this->make->eDivCol();

                    $div = $this->make->code();
                    $json['div'] = $div;
                }
            }
        }
        echo json_encode($json);
    }
}