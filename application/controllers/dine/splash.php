<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Splash extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('dine/splash_helper');
	}
	public function index(){
        $data = $this->syter->spawn(null,false);
        $data['code'] = splashPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
	}
	public function check_trans(){
		$trans_cart = array();
        if($this->session->userData('trans_cart')){
            $trans_cart = $this->session->userData('trans_cart');
        }
        echo json_encode(array('ctr'=>count($trans_cart)));
	}
	public function commercial(){
		$data = $this->syter->spawn(null,false);
        $data['code'] = commercialPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        // $data['load_js'] = 'dine/splash.php';
        // $data['use_js'] = 'splashJs';
        $this->load->view('load',$data);
	}
	public function transactions(){
        $data = $this->syter->spawn(null,false);
        $data['code'] = transactionPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');
        $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        //$data['add_css'] = 'css/cashier.css';
        $data['load_js'] = 'dine/splash.php';
        $data['use_js'] = 'splashTransJs';
        $data['noNavbar'] = true; /*displays the navbar. Uncomment this line to hide the navbar.*/
        $this->load->view('cashier',$data);
	}
	public function get_counter(){
		$counter = sess('counter');
		$counter['type'] = strtoupper($counter['type']);
		$trans_cart = sess('trans_cart');
		$charges = sess('trans_charge_cart');;
		// echo var_dump($trans_cart);
		$code = "";

		$this->make->sUl();
		foreach ($trans_cart as $line_id => $opt) {
			$qty = $this->make->span($opt['qty'],array('class'=>'qty','return'=>true));
            $name = $this->make->span($opt['name'],array('class'=>'name','return'=>true));
            $cost = $this->make->span($opt['cost'],array('class'=>'cost','return'=>true));
            $price = $opt['cost'];
            $this->make->li($qty." ".$name." ".$cost);
            if(isset($opt['remarks']) && $opt['remarks'] != ""){
                $remarks = $this->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                $this->make->li($remarks);
            }
            if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
                foreach ($opt['modifiers'] as $mod_id => $mod) {
                    $name = $this->make->span($mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
                    $cost = "";
                    if($mod['cost'] > 0 )
                        $cost = $this->make->span($mod['cost'],array('class'=>'cost','return'=>true));
                    $this->make->li($name." ".$cost);
                    $price += $mod['cost'];
                }
            }

		}
        if(count($charges) > 0){
            foreach ($charges as $charge_id => $ch) {
                $qty = $this->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
                $name = $this->make->span($ch['name'],array('class'=>'name','return'=>true));
                $tx = $ch['amount'];
                if($ch['absolute'] == 0)
                    $tx = $ch['amount']."%";
                $cost = $this->make->span($tx,array('class'=>'cost','return'=>true));
                $this->make->li($qty." ".$name." ".$cost);
            }
        }
		$this->make->eUl();
		$code = $this->make->code();
		echo json_encode(array('counter'=>$counter,'code'=>$code) );
	}

}