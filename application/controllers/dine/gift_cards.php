<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gift_cards extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dine/gift_cards_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/gift_cards_helper');
	}
	public function index()
	{
		$data = $this->syter->spawn('gift_cards');

		$gc = $this->gift_cards_model->get_gift_cards();
		$data['code'] = gift_cards_display($gc);

		$this->load->view('page',$data);
	}
	public function gift_cards_setup($gc_id = null)
	{
		$data = $this->syter->spawn();

		if (is_null($gc_id)){
			$data['page_title'] = fa('fa-gift fa-fw')." Add New Gift Card";
		}else {
			$gc = $this->gift_cards_model->get_gift_cards($gc_id);
			$gc = $gc[0];
			if (!empty($gc->gc_id)) {
				// $data['page_title'] = fa('fa-user fa-fw')." ".iSetObj($gc,'lname'.', '.'fname');
				$data['page_title'] = fa('fa-gift fa-fw')." ".iSetObj($gc,'card_no');
				// if (!empty($gc->update_date))
					// $data['page_subtitle'] = "Last updated ".$gc->update_date;

			} else {
				header('Location:'.base_url().'gift_cards/gift_cards_setup');
			}
		}

		$data['code'] = gift_cards_form_container($gc_id);
		$data['load_js'] = "dine/gift_cards.php";
		$data['use_js'] = "giftCardFormContainerJs";

		$this->load->view('page',$data);
	}
	public function gift_cards_load($gc_id = null)
	{
		$details = array();
		if (!is_null($gc_id))
			$item = $this->gift_cards_model->get_gift_cards($gc_id);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = gift_cards_details_form($details,$gc_id);
		$data['load_js'] = "dine/gift_cards.php";
		$data['use_js'] = "giftCardDetailsJs";
		$this->load->view('load',$data);
	}
	public function gift_cards_details_db()
	{
		// if (!$this->input->post())
			// header("Location:".base_url()."items");

		$items = array(
			'card_no' => $this->input->post('card_no'),
			'amount' => $this->input->post('amount'),
			'inactive' => (int)$this->input->post('inactive'),
		);

		if ($this->input->post('gc_id')) {
			$id = $this->input->post('gc_id');
			$this->gift_cards_model->update_gift_cards($items,$id);
			$msg = "Updated Gift Card";
		} else {
			$id = $this->gift_cards_model->add_gift_cards($items);
			$msg = "Added New Gift Card";
		}

		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	#gift cards menu
    public function cashier_gift_cards(){
        $this->load->model('site/site_model');
        $this->load->model('dine/gift_cards_model');
		$this->load->helper('core/on_screen_key_helper');
        $this->load->helper('dine/gift_cards_helper');
        $data = $this->syter->spawn(null);
        $data['code'] = giftCardsPage();
        $data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');	
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/gift_cards.php';
        $data['use_js'] = 'giftCardsJs';
        $data['noNavbar'] = true; /*Hides the navbar. Comment-out this line to display the navbar.*/
        $this->load->view('cashier',$data);
    }
	public function load_gift_cards_details()
	{
		$details = array();
		// $cardno = $this->input->post('cardno');
		$cardno = str_replace('-', '', $this->input->post('cardno'));
		
		if (!is_null($cardno))
			$item = $this->gift_cards_model->get_gift_card_info($cardno);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = gift_cards_details_form($details,$details->gc_id);
		$data['load_js'] = "dine/gift_cards.php";
		$data['use_js'] = "giftCardDetailsJs";
		$this->load->view('load',$data);
	}
	public function gift_cards_list()
	{
		$gift_cards = $this->gift_cards_model->get_gift_cards();

		$data['code'] = giftCardsList($gift_cards);
		$data['load_js'] = "dine/gift_cards.php";
		$data['use_js'] = "giftCardDetailsJs";
		$this->load->view('load',$data);
	}
	public function validate_card_number(){
		$cardno = $this->input->post('cardno');
		$gc_count = 0;
		
		$gc_count = $this->gift_cards_model->get_all_gift_card_count($cardno);
		$gc_det = $gc_count[0];
		
		if(empty($cardno)){
			echo "empty";
		}else if($gc_det->total_count == 0){
			echo "none";
		}else if($gc_det->total_count > 0){
			echo "success||".$cardno;
		}

	}
}