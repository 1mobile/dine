<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importer extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
        $data = $this->syter->spawn(null,false);
        $data['code'] = "";
        $data['load_js'] = 'dine/importer.php';
        $data['use_js'] = 'importJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
	}
}