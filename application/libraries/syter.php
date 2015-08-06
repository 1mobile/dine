<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syter{
	var $curr_page = null;
    var $access = null;
    function __construct($config=array()){
        if (count($config) > 0){
			$this->initialize($config);
		}
    }
    function initialize($config = array()){
		foreach ($config as $key => $val){
			if (isset($this->$key)){
				$this->$key = $val;
			}
		}
	}
	function spawn($curr_page=null,$check_login=true,$check_load=true){
		$CI =& get_instance();
		$setup = array();
		if($check_load){
			session_start();
			if(!isset($_SESSION['load'])){
				redirect(base_url().'site/loader','refresh');
			}
			else{
				if(isset($_SESSION['problem']) && $_SESSION['problem'] != ""){
					$setup['problem'] = $_SESSION['problem'];
				}
			}
		}
		$log = array();
		$img = base_url().'img/avatar.jpg';
		if($check_login){
			$log = $this->checkLogin();
			if($log['access'] == "all")
				$access = 'all';
			else
				$access = explode(",",$log['access']);
			$this->access = $access;

			$setup['user'] = $log;
			if(isset($log['img']))
				$img = $log['img'];
		}

		$setup['user_img'] = $img;
		$setup['css'] = $this->initialize_includes($CI->config->item('incCss'),'css');
		$setup['js'] = $this->initialize_includes($CI->config->item('incJs'),'js');

		$menu = $CI->config->item('sideNav');
		$setup['sideNav'] = $this->initialize_side_nav($menu);

		$page_title = "";
		if($curr_page != null){
			$page = $this->get_current_page($curr_page,$menu);
			$page_title = isset($page['title'])?$page['title']:'';
		}
		$setup['page_title'] = $page_title;

		return $setup;
	}
	function get_navs(){
		$CI =& get_instance();
		return $CI->config->item('sideNav');
	}
	function initialize_includes($incs,$type){
		$includes = "";
		if($type=='css'){
			foreach ($incs as $val) {
				$txt = '<link href="'.base_url().$val.'" rel="stylesheet">';
				$includes .= $txt;
			}
		}
		else{
			foreach ($incs as $val) {
				$txt = '<script src="'.base_url().$val.'" type="text/javascript"  language="JavaScript"></script>';
				$includes .= $txt;
			}
		}
		return $includes;
	}
	function initialize_side_nav($navs){
		$sidemenu = $this->build_menu($navs);
		return $sidemenu;
	}
	function build_menu($navs,$sub=false){
		$menu = "";
		foreach ($navs as $page_key => $nav) {
			if(!is_array($nav['path'])){
				if($this->checkAccess($page_key,$nav)){
					$menu .= "<li>";
						$menu .= $this->linkitize($nav,$sub);
					$menu .= "</li>";
				}

			}
			else{
				if($this->checkAccess($page_key,$nav)){
					$menu .= "<li class='treeview'>";
							$menu .= $this->linkitize($nav,$sub);
							$menu .= "<ul class='treeview-menu'>";
								$menu .= $this->build_menu($nav['path'],true);
							$menu .= "</ul>";
					$menu .= "</li>";
				}
			}
		}
		return $menu;
	}
	function get_current_page($curr_page,$navs){
		$page = array();
		foreach ($navs as $key => $nav) {
			if($key != $curr_page){
				if(is_array($nav['path']))
				$page = $this->get_current_page($curr_page,$nav['path']);
			}
			else{
				$page = $nav;
				break;
			}
		}
		return $page;
	}
	function linkitize($link,$sub=false){
		$text = "";
		$url = "#";
		if(!is_array($link['path']))
			$url = base_url().$link['path'];

		$text .= "<a href='".$url."'>";
			if($sub==true)
				$text .= "<i class='fa fa-angle-double-right'></i>";

			$text .= $link['title'];

			if(is_array($link['path']))
				$text .= "<i class='fa fa-angle-left pull-right'></i>";

		$text .= "</a>";

		return $text;
	}
	function checkLogin(){
		$CI =& get_instance();
		if($CI->session->userdata('user')){
			return $CI->session->userdata('user');
		}
		else{
			redirect('login','refresh');
		}
	}
	function checkAccess($pageKey=null,$nav){
		$ret = false;
		$access = $this->access;
		if(is_array($access)){
			
			if(isset($nav['exclude']) && $nav['exclude'] == 0){
				if(in_array($pageKey,$access)){
					$ret = true;
				}
			}
			else{
				$ret = true;
			}
		}
		else{
			$ret = true;
		}
		return $ret;
	}
}

/* End of file Access.php */
/* Location: ./application/libraries/Access.php */