<?php
function makeLoginBox(){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->sBox('primary');
					$CI->make->sBoxHead();
						$CI->make->boxTitle('Example');
					$CI->make->eBoxHead();
					
					$CI->make->sBoxBody();
						$CI->make->input('something','example',null,'example',array(),'<i class="fa fa-user"></i>');
					$CI->make->eBoxBody();

					$CI->make->sBoxFoot();
						$CI->make->button('something',array(),'primary');
					$CI->make->eBoxFoot();
				$CI->make->eBox();
			$CI->make->eDivCol();
		$CI->make->eDivRow();				
	return $CI->make->code();
}
?>