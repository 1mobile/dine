<?php
function dashboardMain($lastGT=0,$todaySales=0,$todayTransNo=0){
	$CI =& get_instance();
	################################################
	########## BOXES
	################################################
		$CI->make->sDivRow();
			$CI->make->sDivCol(3);
		    	$CI->make->sDiv(array('class'=>'info-box'));
		        	$CI->make->span(fa('fa-desktop'),array('class'=>'info-box-icon  bg-blue'));
		        	$CI->make->sDiv(array('class'=>'info-box-content'));
		        		$CI->make->span('Last Grand Total',array('class'=>'info-box-text'));
		        		$CI->make->span('PHP '.num($lastGT),array('class'=>'info-box-number'));
		        	$CI->make->eDiv();
		        $CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->sDiv(array('class'=>'info-box '));
			    	$CI->make->span(fa('fa-money'),array('class'=>'info-box-icon  bg-green'));
			    	$CI->make->sDiv(array('class'=>'info-box-content'));
			    		$CI->make->span('Today Sales',array('class'=>'info-box-text'));
			    		$CI->make->span('PHP '.num($todaySales),array('class'=>'info-box-number'));
			    	$CI->make->eDiv();
			    $CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->sDiv(array('class'=>'info-box  '));
					$CI->make->span(fa('fa-users'),array('class'=>'info-box-icon bg-yellow'));
					$CI->make->sDiv(array('class'=>'info-box-content'));
						$CI->make->span('Today Transactions',array('class'=>'info-box-text'));
						$CI->make->span(num($todayTransNo),array('class'=>'info-box-number'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->sDiv(array('class'=>'info-box'));
					$CI->make->span(fa('fa-calendar'),array('class'=>'info-box-icon bg-aqua'));
					$CI->make->sDiv(array('class'=>'info-box-content'));
						$CI->make->span(null,array('class'=>'info-box-text','id'=>'box-day'));
						$CI->make->span('9:00 PM',array('class'=>'info-box-number','id'=>'box-time'));
						$CI->make->sDiv(array('class'=>'progress'));
							$CI->make->sDiv(array('class'=>'progress-bar','style'=>'width:100%'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->span(null,array('class'=>'progress-description','id'=>'box-date'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	################################################
	########## GRAPHS
	################################################
	$CI->make->sDivRow();
		$CI->make->sDivCol(6);
				$CI->make->sBox('default',array('class'=>'box-solid'));
					// $CI->make->sBoxHead();
					// 	$CI->make->boxTitle(fa('fa-money fa-fw').' Transactions Sales');
					// $CI->make->eBoxHead();
					$CI->make->sBoxBody();
						$CI->make->sDivRow(array('class'=>'chart-responsive'));
							// $CI->make->sDivCol(8);
							// 	$CI->make->sDiv(array('class'=>'chart','id'=>'bar-chart','style'=>'height:300px;'));
							// 	$CI->make->eDiv();
							// $CI->make->eDivCol();
							$CI->make->sDivCol(12);
								$CI->make->sDiv(array('id'=>'bars-div','class'=>'pad','style'=>'width:100%; position: relative; '));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eBoxBody();
				$CI->make->eBox();
		$CI->make->eDivCol();		
		$CI->make->sDivCol(6);
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxBody(array('class'=>'chart-responsive'));
					$CI->make->sDiv(array('id'=>'sales-chart','class'=>'chart','style'=>'height: 260px; position: relative;'));
					$CI->make->eDiv();
			$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();		
	$CI->make->eDivRow();

	return $CI->make->code();
}
?>