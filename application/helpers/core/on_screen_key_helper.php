<?php
function onScrNumPadOnly($inputName='pad-input'){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'scr-con'));
			$CI->make->sTable(array('class'=>'scr-pad','cellpadding'=>'5','cellspacing'=>'200'));
				$CI->make->sRow();
					$textbox = $CI->make->textbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
					$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(0,array('colspan'=>2,'class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('DEL',array('class'=>'scr-pad-key tsc_awb_large btn-del tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}
function onScrNumPwdPadOnly($inputName='pad-input'){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'scr-con'));
			$CI->make->sTable(array('class'=>'scr-pad','cellpadding'=>'5','cellspacing'=>'200'));
				$CI->make->sRow();
					$textbox = $CI->make->pwdbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
					$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(0,array('colspan'=>2,'class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('DEL',array('class'=>'scr-pad-key tsc_awb_large btn-del tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}
function onScrNumPad($inputName='pad-input',$enterBtn='enter-btn',$cancelBtn=''){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'scr-con'));
			$CI->make->sTable(array('class'=>'scr-pad','cellpadding'=>'5','cellspacing'=>'200'));
				$CI->make->sRow();
					$textbox = $CI->make->textbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
					$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(0,array('colspan'=>2,'class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('DEL',array('class'=>'scr-pad-key tsc_awb_large btn-del tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td('Enter',array('colspan'=>3,'id'=>$enterBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_green tsc_flat'));
				$CI->make->eRow();
				if ($cancelBtn != '') {
					$CI->make->sRow();
						$CI->make->td(fa('fa-reply fa-lg').' Back',array('colspan'=>3,'id'=>$cancelBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_red tsc_flat'));
					$CI->make->eRow();
				}
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}
function onScrNumPwdPad($inputName='pad-input',$enterBtn='enter-btn',$cancelBtn=''){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'scr-con'));
			$CI->make->sTable(array('class'=>'scr-pad','cellpadding'=>'5','cellspacing'=>'200'));
				$CI->make->sRow();
					$textbox = $CI->make->pwdbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
					$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(0,array('colspan'=>2,'class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('DEL',array('class'=>'scr-pad-key tsc_awb_large btn-del tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td('Enter',array('colspan'=>3,'id'=>$enterBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_green tsc_flat'));
				$CI->make->eRow();
				if ($cancelBtn != '') {
					$CI->make->sRow();
						$CI->make->td(fa('fa-reply fa-lg').' Back',array('colspan'=>3,'id'=>$cancelBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_red tsc_flat'));
					$CI->make->eRow();
				}
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}

function onScrNumDotPad($inputName='pad-input',$enterBtn='enter-btn'){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'screen-con'));
			$CI->make->sTable(array('class'=>'screen-pad','cellpadding'=>'5','cellspacing'=>'200'));
				$CI->make->sRow();
					$textbox = $CI->make->textbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
					$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td('.',array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(0,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('CLEAR',array('class'=>'scr-pad-key tsc_awb_large btn-clear tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td('Enter',array('colspan'=>3,'id'=>$enterBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_green tsc_flat'));
				$CI->make->eRow();
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}

function onScrNumOnlyTarget($tblId,$target,$enterBtn='enter-btn',$returnBtnId='',$returnBtnCaption='',$useDot=true){
	$CI =& get_instance();
		$CI->make->sDiv(array('class'=>'screen-con'));
			$CI->make->sTable(array('class'=>'screen-pad','cellpadding'=>'5','cellspacing'=>'200','id'=>$tblId,'target'=>$target));
				// $CI->make->sRow();
				// 	$textbox = $CI->make->textbox($inputName,null,null,array('class'=>'form-control pad-input','return'=>true));
				// 	$CI->make->td($textbox,array('colspan'=>3,'class'=>'scr-pad-input'));
				// $CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(1,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(2,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(3,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(4,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(5,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(6,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(7,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(8,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(9,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td(($useDot ? '.' : '-'),array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td(0,array('class'=>'scr-pad-key tsc_awb_large tsc_flat'));
					$CI->make->td('DEL',array('class'=>'scr-pad-key tsc_awb_large btn-del tsc_awb_yellow tsc_flat'));
				$CI->make->eRow();
				$CI->make->sRow();
					$CI->make->td('Enter',array('colspan'=>3,'id'=>$enterBtn,'class'=>'scr-pad-key btn-enter tsc_awb_large tsc_awb_green tsc_flat'));
				$CI->make->eRow();
				if ($returnBtnCaption != '') {
					$CI->make->sRow();
						$CI->make->td(fa('fa-reply fa-lg').' '.$returnBtnCaption,array('colspan'=>3,'id'=>$returnBtnId,'class'=>'scr-pad-key tsc_awb_large tsc_awb_red tsc_flat'));
					$CI->make->eRow();
				}
			$CI->make->eTable();
		$CI->make->eDiv();
	return $CI->make->code();
}
?>