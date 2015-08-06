<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restaurants extends CI_Controller {
	var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->model('resto/restaurant_model');
        $this->load->helper('resto/restaurant_helper');
    }
    public function index(){
		$restaurant_list = $this->restaurant_model->get_restaurants();
        $data = $this->syter->spawn('restaurant');
        // echo var_dump($restaurant_list);
        $data['code'] = makeRestaurantPage($restaurant_list);
 		$this->load->view('page',$data);
	}
    public function setup($res_id=null){
        $this->load->helper('resto/branch_helper');
        $this->load->model('resto/branches_model');
        $branches = '';
        $resto = '';
        if($res_id != null){
            $restaurants = $this->restaurant_model->get_restaurants($res_id);
            if(count($restaurants) > 0){
                $branches = $this->branches_model->get_restaurant_branches(null,$res_id);
                $resto = $restaurants[0];
            }
        }
        $data = $this->syter->spawn('restaurant');
        // $data['code'] = makeBranchesPage($branches,$res_id);
        $data['code'] = makeRestaurantForm($branches,$res_id);
        $data['load_js'] = 'resto/restaurants.php';
        $data['use_js'] = 'restaurantJS';
        if($res_id != null)
            $data['page_title'] = fa('fa-cutlery fa-fw')." ".iSetObj($resto,'res_name');
        else
            $data['page_title'] = "Add New Restaurant";

        $this->load->view('page',$data);
    }
    public function setup_load($res_id=null){
        $detail = array();
        if($res_id != null){
            $details = $this->restaurant_model->get_restaurants($res_id);
            $detail = $details[0];
        }
        $data['code'] = displayDetailsPerResto($detail,$res_id);
        $data['load_js'] = 'resto/restaurants.php';
        $data['use_js'] = 'restaurantDetailsJS';
        $this->load->view('load',$data);
    }
    public function resto_details_db(){
        $image = null;
        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $image = file_get_contents($_FILES['fileUpload']['tmp_name']);
        }

        $info = pathinfo($_FILES['fileUpload']['name']);
        $ext = $info['extension'];
        $code = $this->input->post('res_id');

        $newname = $code.".".$ext;

        if (!file_exists("uploads/".$code)) {
            mkdir("uploads/".$code, 0777, true);
        }

        $target = 'uploads/'.$code.'/'.$newname;
        move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);

        $items = array(
            'res_code'=>$this->input->post('res_code'),
            'res_name'=>$this->input->post('res_name'),
            'res_desc'=>$this->input->post('res_desc'),
            'type_id'=>$this->input->post('type_id'),
            // 'res_logo'=>$image
            'image'=>$newname
        );
        if($image == null)
            unset($items['res_logo']);
        if($this->input->post('res_id')){
            $this->restaurant_model->update_restaurant($items,$this->input->post('res_id'));
            $id = $this->input->post('res_id');
            $act = 'update';
            $msg = 'Updated Restaurant '.$this->input->post('res_name');
        }else{
            $id = $this->restaurant_model->add_restaurant($items);
            $act = 'add';
            $msg = 'Added  new Restaurant '.$this->input->post('res_name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('res_name'),"act"=>$act,'msg'=>$msg));
    }
    public function tax_load($res_id=null){
        // $this->load->model('resto/restaurant_model');
        $tax = array();
        $taxes = $this->restaurant_model->get_restaurant_taxes($res_id);

        // if($res_id != null){
        // }
        $data['code'] = displayTaxPerResto($taxes,$res_id);
        $data['load_js'] = 'resto/restaurants.php';
        $data['use_js'] = 'restaurantTaxesJS';
        $this->load->view('load',$data);
    }
    public function discount_load($res_id=null){
        $discount = array();
        if($res_id != null || !empty($res_id)){
            $discounts = $this->restaurant_model->get_restaurant_discounts($res_id);
            // echo $res_id.' --- '.$this->db->last_query();
            if(!empty($discounts)) $discount = $discounts[0];
            else                  $discount = array();
        }
        $data['code'] = displayDiscountPerResto($discounts,$res_id);
        $data['load_js'] = 'resto/restaurants.php';
        $data['use_js'] = 'restaurantDiscountJS';
        $this->load->view('load',$data);
    }
    public function resto_tax_db(){
        // $this->load->model('resto/branches_model');
        $items = array(
            "res_id"=>$this->input->post('resID'),
            "name"=>$this->input->post('name'),
            "rate"=>$this->input->post('rate')
        );

        if($this->input->post('res_id')){
            // $this->branches_model->update_restaurant_branches($items,$this->input->post('res_id'));
            // $id = $this->input->post('res_id');
            // $act = 'update';
            // $msg = 'Updated Restaurant Tax. '.$this->input->post('branch_name');
        }else{
            $id = $this->restaurant_model->add_restaurant_tax($items);
            $act = 'add';
            $msg = 'Added  new Restaurant Tax '.$this->input->post('name');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('name'),"act"=>$act,'msg'=>$msg));
    }
    public function resto_disc_db(){
        // $this->load->model('resto/branches_model');
        $msg = "";
        $act = "";
        $id = "";
        $li = "";
        if($this->input->post('remove_disc')){
            $this->restaurant_model->delete_restaurant_discount($this->input->post('remove_disc'));
            $msg = "Disc deleted.";
        }
        else{
            $items = array(
                "res_id"=>$this->input->post('ressID'),
                "disc_code"=>$this->input->post('code'),
                "disc_name"=>$this->input->post('name'),
                "disc_rate"=>$this->input->post('rate')
            );
            $id = $this->restaurant_model->add_restaurant_discount($items);
            $details = $this->restaurant_model->get_restaurant_discount($id);
            $res = $details[0];

            $li = "";
            $li = $this->make->li(
                        $this->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                        $this->make->span($res->disc_code." ".$res->disc_name,array('class'=>'text','return'=>true))." ".
                        $this->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-disc','id'=>'del-disc-'.$res->disc_id,'ref'=>$res->disc_id)),
                        array('return'=>true)
                  );
            // echo $this->restaurant_model->last_query();
            $act = 'add';
            $msg = 'Added  new Restaurant Discount '.$this->input->post('name');
        }
        echo json_encode(array("id"=>$id,"act"=>$act,'msg'=>$msg,'li'=>$li));
    }
}