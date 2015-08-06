<?php
	$this->load->view('cashier/head');
	$this->load->view('cashier/body');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('cashier/foot');
?>