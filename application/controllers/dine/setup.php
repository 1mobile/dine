<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
	//-----------Branch Details-----start-----allyn
	public function details(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->helper('dine/setup_helper');
        $details = $this->setup_model->get_details(1);
		$det = $details[0];
        $set = $this->cashier_model->get_pos_settings();
        $splashes = $this->site_model->get_image(null,null,'splash_images');

        $data = $this->syter->spawn('setup');
        $data['page_subtitle'] = 'Edit Branch Setup';
        $data['code'] = makeDetailsForm($det,$set,$splashes);
        // $data['add_js'] = array('js/plugins/timepicker/bootstrap-timepicker.min.js');
        // $data['add_css'] = array('css/timepicker/bootstrap-timepicker.min.css');
		$data['load_js'] = 'dine/setup.php';
		$data['use_js'] = 'detailsJs';
        $this->load->view('page',$data);
    }
    public function upload_splash_images(){
        $this->load->helper('dine/setup_helper');
        $this->load->model('dine/settings_model');
        // $data['code'] = makeTableUploadForm($branch);
        
        $data['code'] = makeImageUploadForm();
        $data['load_js'] = 'dine/setup.php';
        $data['use_js'] = 'uploadSplashImagePopJs';
        $this->load->view('load',$data);
    }
    public function delete_splash_img($img_id=null){
        $error = "";
        if($img_id != ""){
            $this->site_model->delete_tbl('images',array('img_id'=>$img_id));
        }
        else{
            $error = "No Image selected";
        }
        echo json_encode(array('error'=>$error));
    }    
    public function upload_splash_images_db(){
        $this->load->model('dine/settings_model');
        $image = null;
        $upload = 'success';
        $msg = "";
        $src ="";

        if(is_uploaded_file($_FILES['fileUpload']['tmp_name'])) {
            $file = file_get_contents($_FILES['fileUpload']['tmp_name']);
            $items = array(
                "img_blob"=>$file,
                "img_tbl"=>'splash_images',
            );
            $id = $this->site_model->add_tbl('images',$items);
            $msg =  "Image uploaded";
            site_alert($msg,'success');
        }
        else{
            $msg =  "Invalid Image";
        }
        echo json_encode(array('msg'=>$msg));
    }
    public function details_db(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/main_model');

        // $img = '';
        // $img = $_FILES['complogo']['tmp_name'];
            // $img = file_get_contents($tmp_name);
        // if(is_uploaded_file($_FILES['complogo']['tmp_name'])){
        //     $tmp_name = $_FILES['complogo']['tmp_name'];
        //     $img = file_get_contents($tmp_name);
        // }
        // echo 'IMAGE : '.$img;
        $items = array(
            "branch_code"=>$this->input->post('branch_code'),
            "branch_name"=>$this->input->post('branch_name'),
            "branch_desc"=>$this->input->post('branch_desc'),
            "contact_no"=>$this->input->post('contact_no'),
            "delivery_no"=>$this->input->post('delivery_no'),
            "address"=>$this->input->post('address'),
            "tin"=>$this->input->post('tin'),
            "machine_no"=>$this->input->post('machine_no'),
            "bir"=>$this->input->post('bir'),
            "serial"=>$this->input->post('serial'),
            "permit_no"=>$this->input->post('permit_no'),
            // "serial"=>$this->input->post('serial'),
            "accrdn"=>$this->input->post('accrdn'),
            "email"=>$this->input->post('email'),
            "website"=>$this->input->post('website'),
            "store_open" => date("H:i:s",strtotime($this->input->post('store_open'))),
            "store_close" => date("H:i:s",strtotime($this->input->post('store_close'))),
            "rob_path" => $this->input->post('rob_path'),
            "rob_username" => $this->input->post('rob_username'),
            "rob_password" => $this->input->post('rob_password'),
            // "img"=>$img
            // "currency"=>$this->input->post('currency')
        );

            $this->setup_model->update_details($items, 1);
            $this->main_model->update_tbl('branch_details','branch_id',$items,1);
            // $id = $this->input->post('cat_id');
            $act = 'update';
            $msg = 'Updated Branch Details';

        echo json_encode(array('msg'=>$msg));
    }
    public function pos_settings_db(){
        $this->load->model('dine/setup_model');
        $this->load->model('dine/cashier_model');
        $this->load->model('dine/main_model');
        $ctrl = "";
        foreach($this->input->post('chk') as $val){
            $ctrl .= $val.','; 
        }

        $ctrl = substr($ctrl, 0, -1);
        //echo $ctrl;

        $items = array(
            "no_of_receipt_print" => (int)$this->input->post('no_of_receipt_print'),
            "no_of_order_slip_print" => (int)$this->input->post('no_of_order_slip_print'),
            "kitchen_printer_name" => $this->input->post('kitchen_printer_name'),
            "kitchen_printer_name_no" => (int)$this->input->post('kitchen_printer_name_no'),
            "kitchen_beverage_printer_name" => $this->input->post('kitchen_beverage_printer_name'),
            "kitchen_beverage_printer_name_no" => (int)$this->input->post('kitchen_beverage_printer_name_no'),
            "open_drawer_printer" => $this->input->post('open_drawer_printer'),
            "local_tax" => $this->input->post('local_tax'),
            "controls"=> $ctrl,
        );
        $this->cashier_model->update_pos_settings($items, 1);
        $this->main_model->update_tbl('settings','id',$items,1);
        $act = 'update';
        $msg = 'Updated Branch Details';
        echo json_encode(array('msg'=>$msg));
    }
	//-----------Branch Details-----end-----allyn
}