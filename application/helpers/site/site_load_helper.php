<?php
function makeLoader(){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'center'));
			$CI->make->sDiv(array('class'=>'headline text-center'));
				$CI->make->span('iPOS');
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'lockscreen-name','style'=>'margin-bottom:10px;'));
				$CI->make->span('Loading Database...',array('id'=>'loadTxt'));
			$CI->make->eDiv();
			$CI->make->sDiv(array('style'=>'width:500px;'));
			$CI->make->progressBar(100,0,null,0,"success",array('class'=>'active progress-striped','style'=>'height:50px;','id'=>'loadBar'));
			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}

?>