<?php
function megamallPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		$buttons = array(
						 "files"	=> fa('fa-file-text fa-lg fa-fw')."<br> FILES",
						 // "hourly-sales"	=> fa('fa-clock-o fa-lg fa-fw')."<br> HOURLY SALES",
						 // "invoice-sales"=> fa('fa-file-text fa-lg fa-fw')."<br> INVOICE SALES",
						 "settings"		=> fa('fa-cogs fa-lg fa-fw')."<br> SETTINGS",
						 );
		$CI->make->sDivRow(array('style'=>'margin:0;'));
		// $CI->make->sDivCol(4);
		// 	$CI->make->button(fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",array('id'=>'day-report-btn','class'=>'btn-block manager-btn-green double'));
		// $CI->make->eDivCol();
			foreach ($buttons as $id => $text) {
					$CI->make->sDivCol(6);
						$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
					$CI->make->eDivCol();
			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	$CI->make->sBox('default',array('class'=>'box-solid'));
		$CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:535px;padding:0 0 10px 0;'));
			$CI->make->sDiv(array('id'=>'div-content'));
			$CI->make->eDiv();
		$CI->make->eBoxBody();
	$CI->make->eBox();
	return $CI->make->code();
}
function megamallSettingsPage($obj=array()){
	$CI =& get_instance();
		$CI->make->sForm('megamall/settings_db',array('id'=>'settings-form'));
			$CI->make->hidden('megamall_id',iSetObj($obj,'id'));
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->H(3,'Branch Code',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'br_code',iSetObj($obj,'br_code'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'LESSEE NAME must not be empty'));
						$CI->make->H(3,'Tenant NO',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'tenant_no',iSetObj($obj,'tenant_no'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'LESSEE NO must not be empty'));
						$CI->make->H(3,'Class Code',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'class_code',iSetObj($obj,'class_code'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'SPACE CODE code must not be empty'));
						$CI->make->H(3,'Outlet No',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'outlet_no',iSetObj($obj,'outlet_no'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'SPACE CODE code must not be empty'));
						$CI->make->H(3,'Trade Code',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'trade_code',iSetObj($obj,'trade_code'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'SPACE CODE code must not be empty'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->button(fa('fa-save fa-lg fa-fw')." SAVE DETAILS",array('id'=>'save-btn','class'=>'btn-block manager-btn-green'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();
}
function filesPage(){
	$CI =& get_instance();
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(6);
						$CI->make->H(3,'Select Date',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->date(null,'file_date','','',array('class'=>'count-inputs  big-man-input'));						
					$CI->make->eDivCol();
					$CI->make->sDivCol(6);
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->H(3,'Daily File',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
								$CI->make->sDiv(array('id'=>'daily-div','style'=>'height:350px;width:100%;overflow:auto;background-color:#fff'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
	return $CI->make->code();	
}

?>