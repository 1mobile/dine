<?php
function onScrNumPad(){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'scr-con'));
			
		$CI->make->eDiv();				
	return $CI->make->code();
}
?>