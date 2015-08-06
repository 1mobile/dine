<?php
function sess_initialize($name="",$arr=array()){
	$CI =& get_instance();
	if($CI->session->userdata($name)){
		$CI->session->unset_userdata($name);
	}
	if(count($arr) > 0){
		$CI->session->set_userdata($name,$arr);
	}
}
function sess($name=""){
	$CI =& get_instance();
	$arr = array();

	if($CI->session->userdata($name)){
		$arr = $CI->session->userdata($name);
	}
	
	return $arr;
}
function sess_add($name="",$row=null,$set_id=null){
	$CI =& get_instance();
	$wagon = array();
	if($CI->session->userData($name)){
		$wagon = $CI->session->userData($name);
	}	
	if($set_id != null){
		$wagon[$set_id] = $row;	
		$id = $set_id;
	}
	else{
		$wagon[] = $row;	
		$id = max(array_keys($wagon));
	}
	$CI->session->set_userData($name,$wagon);
	return array("row"=>$row,"id"=>$id);
}
function sess_clear($name=""){
	$CI =& get_instance();
	$CI->session->unset_userdata($name);
}
function sess_update($name=null,$update_id=null,$row=null){
	$CI =& get_instance();
	$wagon = $CI->session->userData($name);
	if(isset($wagon[$update_id])){
		$wagon[$update_id] = $row;
		$CI->session->set_userData($name,$wagon);
	}
	else{
		sess_add($name,$row,$update_id);
	}
	return array("row"=>$row,"id"=>$update_id);
}
function sess_delete($name=null,$id=null){
	$CI =& get_instance();
	$wagon = $CI->session->userData($name);
	if(isset($wagon[$id])){
		$row = $wagon[$id];
		unset($wagon[$id]);
		$CI->session->set_userData($name,$wagon);		
	}
}
?>