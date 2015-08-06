<?php
function makeRestaurantTypeForm($user=array()){
	$CI =& get_instance();

	$CI->make->sForm("resto/managements/restaurant_type_db",array('id'=>'restaurant_types_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(3);
				$CI->make->hidden('type_id',iSetObj($user,'type_id'));
				$CI->make->input('Type Code','type_code',iSetObj($user,'type_code'),'Type Code',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Type Name','type_name',iSetObj($user,'type_name'),'Type Name',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function makeRestaurantStaffForm($staff=array()){
	$CI =& get_instance();

	$CI->make->sForm("resto/managements/staffs_db",array('id'=>'staffs_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('staff_id',iSetObj($staff,'staff_id'));
				$CI->make->input('Name','staff_name',iSetObj($staff,'staff_name'),'Type name',array('class'=>'rOkay'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
?>