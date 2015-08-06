<?php
	$this->load->view('login/head');
	$this->load->view('login/body');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('login/foot');
?>