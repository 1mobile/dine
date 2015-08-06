<?php
function endOfDayPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		$buttons = array(
						 // "day-report"	=> fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",
						 "day-xread"	=> fa('fa-laptop fa-lg fa-fw')."<br> End Shift",
						 "day-zread"	=> fa('fa-flag-checkered fa-lg fa-fw')."<br> End Day",
						 );
		$CI->make->sDivRow(array('style'=>'margin:0;'));
		$CI->make->sDivCol(4);
			$CI->make->button(fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",array('id'=>'day-report-btn','class'=>'btn-block manager-btn-green double'));
		$CI->make->eDivCol();
		foreach ($buttons as $id => $text) {
				$CI->make->sDivCol(4);
					$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
				$CI->make->eDivCol();
		}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	$CI->make->sBox('default',array('class'=>'box-solid'));
		$CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:535px;padding:0 0 10px 0;'));
			$CI->make->sDiv(array('id'=>'endofday-content'));
			$CI->make->eDiv();
		$CI->make->eBoxBody();
	$CI->make->eBox();
	return $CI->make->code();
}
function summaryPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces div-no-raduis'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(12);
				
				$CI->make->sDiv(array('style'=>'margin:10px;'));
					$CI->make->sBox('default',array('class'=>'box-solid'));
						$CI->make->sBoxHead();
							$CI->make->boxTitle(fa('fa-money fa-fw').' Transactions Sales');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody();
							$CI->make->sDivRow();
								$CI->make->sDivCol(8);
									$CI->make->sDiv(array('class'=>'chart','id'=>'bar-chart','style'=>'height:380px;'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
										
									$CI->make->sDiv(array('id'=>'bars-div','class'=>'pad'));
									$CI->make->eDiv();
									// $CI->make->progressBar($maxVal=100,$val=80,$percent=null,$minVal=0,$color="red",array());
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDiv();			

			$CI->make->eDivCol();		
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
function endShift($read_details=array()){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		
		if(!empty($read_details)){
			// $CI->make->sForm(base_url()."reports/x_read_rep",array('id'=>'eod_form'));
			$CI->make->sForm(base_url()."prints/system_sales_rep",array('id'=>'eod_form'));
				foreach ($read_details as $key => $val) {
					$CI->make->hidden($key,$val);
				}
			$CI->make->eForm();
		}
		$CI->make->sDivRow();
			#SALES RECEIPTS
				$CI->make->sDivCol(4);
					$CI->make->sDivRow();
						$CI->make->sDiv(array('style'=>'margin-top:10px;'));
							$CI->make->sDivCol(6);
								$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
							$CI->make->eDivCol();
						$CI->make->eDiv();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('style'=>'background-color:#ddd;height:420px;padding:10px;'));
								$CI->make->sDiv(array('id'=>'report-view-div','style'=>'margin:0 auto;position:relative;height:400px;overflow:hidden;width:315px;'));
									// $CI->make->append('<pre style="background-color:#fff">'.$receipts.'</pre>');
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					// $CI->make->sDivRow();
					// 	$CI->make->sDivCol(12);
					// 		$CI->make->button(fa('fa-print fa-2x'),array('id'=>'sale-read-print-btn','class'=>'btn-block no-raduis manager-btn-green'));
					// 	$CI->make->eDivCol();
					// $CI->make->eDivRow();
				$CI->make->eDivCol();
			#BUTTONS	
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('style'=>'margin:20px;'));
						$disabled = null;
						if(empty($read_details)){
							$CI->make->append('<div class="alert alert-danger alert-dismissable" style="margin:10px;padding:15px;">
		                                    <i class="fa fa-ban"></i>
		                                    <b>ALERT!</b> You have no shift.
		                                </div>');
							$disabled = 'disabled';
						}
						if(!empty($read_details) && !isset($read_details['cashout_id'])){
							$CI->make->append('<div class="alert alert-danger alert-dismissable" style="margin:10px;padding:15px;">
		                                    <i class="fa fa-ban"></i>
		                                    <b>ALERT!</b> You need to count cash drawer first before ending shift.
		                                </div>');
							$disabled = 'disabled';
						}
						$CI->make->button('END SHIFT',array('id'=>'end-shift-btn','class'=>'btn-block no-raduis manager-btn-green double '.$disabled,'style'=>'margin-bottom:10px;'));
						$CI->make->button('END AND PRINT SHIFT',array('id'=>'end-shift-print-btn','class'=>'btn-block no-raduis manager-btn-green double '.$disabled,'style'=>'margin-bottom:10px;'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
function endDay($read_details=array(),$error=null){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		if(!empty($read_details)){
			// $CI->make->sForm(base_url()."reports/z_read_rep",array('id'=>'eod_form'));
			$CI->make->sForm(base_url()."prints/system_sales_rep",array('id'=>'eod_form'));
				foreach ($read_details as $key => $val) {
					$CI->make->hidden($key,$val);
				}
			$CI->make->eForm();
		}
		$CI->make->sDivRow();
			#SALES RECEIPTS
				$CI->make->sDivCol(4);
					$CI->make->sDivRow();
						$CI->make->sDiv(array('style'=>'margin-top:10px;'));
							$CI->make->sDivCol(6);
								$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
							$CI->make->eDivCol();
						$CI->make->eDiv();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('style'=>'background-color:#ddd;height:420px;padding:10px;'));
								$CI->make->sDiv(array('id'=>'report-view-div','style'=>'margin:0 auto;position:relative;height:400px;overflow:hidden;width:315px;'));
									// $CI->make->append('<pre style="background-color:#fff">'.$receipts.'</pre>');
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					// $CI->make->sDivRow();
					// 	$CI->make->sDivCol(12);
					// 		$CI->make->button(fa('fa-print fa-2x'),array('id'=>'sale-read-print-btn','class'=>'btn-block no-raduis manager-btn-green'));
					// 	$CI->make->eDivCol();
					// $CI->make->eDivRow();
				$CI->make->eDivCol();
			#BUTTONS	
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('style'=>'margin:20px;'));
						$disabled = '';
						if($error != null){
							$CI->make->append('<div class="alert alert-danger alert-dismissable" style="margin:10px;padding:15px;">
		                                    <i class="fa fa-ban"></i>
		                                    <b>ALERT!</b>'.$error.'
		                                </div>');
							$disabled = 'disabled';
						}

						$CI->make->button('End Day',array('id'=>'end-day-btn','class'=>'btn-block no-raduis manager-btn-green double '.$disabled,'style'=>'margin-bottom:10px;'));
						$CI->make->button('End Day and Print',array('id'=>'end-day-print-btn','class'=>'btn-block no-raduis manager-btn-green double '.$disabled,'style'=>'margin-bottom:10px;'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
?>