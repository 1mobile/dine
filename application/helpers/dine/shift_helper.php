<?php
function shiftPage($now=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'manager'));
			$CI->make->sDiv(array('class'=>'div-no-spaces'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						$CI->make->button(fa('fa-clock-o fa-2x fa-fw')." <br> TIME CLOCK",array('id'=>'time-btn','class'=>'btn-block manager-btn-teal double'));
					$CI->make->eDivCol();
					$CI->make->sDivCol(4);
						$CI->make->button(fa('fa-desktop fa-2x fa-fw')." <br> CASHIER",array('id'=>'cashier-btn','class'=>'btn-block manager-btn-green double'));
					$CI->make->eDivCol();
					// $CI->make->sDivCol(3);
					// 	$CI->make->button(fa('fa-list fa-2x fa-fw')." <br> TIME CARD",array('id'=>'timecard-btn','class'=>'btn-block manager-btn-orange double'));
					// $CI->make->eDivCol();
					$CI->make->sDivCol(4);
						$CI->make->button(fa('fa-power-off fa-2x fa-fw')." <br> EXIT",array('id'=>'lock-btn','class'=>'btn-block manager-btn-red double'));
						// $CI->make->button(fa('fa-lock fa-2x fa-fw')." <br> LOCK SCREEN",array('id'=>'lock-btn','class'=>'btn-block manager-btn-red double'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
			$CI->make->sDiv(array('style'=>'margin-top:0;'));
				$CI->make->sBox('default',array('class'=>'box-solid'));
					$CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:600px;padding:0'));
						$CI->make->sDiv(array('class'=>'loads-div'));
						$CI->make->eDiv();
						// $CI->make->sDiv(array('style'=>'background-color: #55453e;height:80px;position:absolute;bottom:0;left:0;right:0;'));
						// 	$CI->make->sDiv(array('id'=>'timer','class'=>'headline text-center','style'=>'font-size: 30px;'));
						// 	$CI->make->eDiv();
						// 	$CI->make->H(4,date('l, F d, Y',strtotime($now)),array('class'=>'headline text-center','style'=>'font-size:20px;'));
						// $CI->make->eDiv();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}
function timePage($shift=array(),$get_dtr=array(),$now=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->sDivRow(array('style'=>'background-color:#e2dbce;'));
					$CI->make->sDivCol(12,'left',0,array('style'=>'padding:0;margin:0;'));
						$CI->make->sDiv(array('class'=>'dtr-details','style'=>'overflow:auto;;height:580px;margin:10px;margin-left:25px;'));
						$CI->make->sTable(array('class'=>'table','id'=>'details-tbl'));
							$CI->make->sRow(array('style'=>'background-color:#7a6a63;'));
								$CI->make->th('DATE',array('style'=>'width:30px;text-align:center;'));
								$CI->make->th('TIME IN',array('style'=>'width:35px;text-align:center;'));
								$CI->make->th('TIME OUT',array('style'=>'width:35px;text-align:center;'));
							$CI->make->eRow();
							$rower = 1;
							foreach($get_dtr as $val){
							//while($rower <= 20){
								if($rower%2 == 0)
									$CI->make->sRow(array('style'=>'background-color:#d3cfcd;'));
								else
									$CI->make->sRow(array('style'=>'background-color:#ffffff;'));

									if($val->check_out == null){
										$chk_out = "---";
									}else{
										$chk_out = date('h:i:s A',strtotime($val->check_out));
									}

									$CI->make->th(date('F d,Y',strtotime($val->check_in)),array('style'=>'width:30px;text-align:center;'));
									$CI->make->th(date('h:i:s A',strtotime($val->check_in)),array('style'=>'width:35px;text-align:center;'));
									$CI->make->th($chk_out,array('style'=>'width:35px;text-align:center;'));
									// $CI->make->th('DATE',array('style'=>'width:30px;text-align:center;'));
									// $CI->make->th('TIME IN',array('style'=>'width:35px;text-align:center;'));
									// $CI->make->th('TIME OUT',array('style'=>'width:35px;text-align:center;'));
									$CI->make->eRow();
								$rower++;
							}
						$CI->make->eTable();
						$CI->make->eDiv();
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->sDiv(array('id'=>'timer','class'=>'headline text-center','style'=>'font-size: 30px;margin-top:20px;'));
				$CI->make->eDiv();
				$CI->make->H(4,date('l, F d, Y',strtotime($now)),array('class'=>'headline text-center','style'=>'font-size:20px;'));
				$CI->make->sDiv(array('style'=>'margin:20px;margin-top:50px;'));
					if(count($shift) > 0){
						$CI->make->button(fa('fa-flag fa-2x fa-fw')." <br> END SHIFT",array('id'=>'end-shift-btn','class'=>'btn-block manager-btn-red double text-center'));
					}
					else{
						$CI->make->button(fa('fa-flag fa-2x fa-fw')." <br> START A SHIFT",array('id'=>'start-shift-btn','class'=>'btn-block manager-btn-green double text-center'));
					}
				$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function startAmountPage(){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol();
				$CI->make->H(4,'Enter Starting Amount',array('class'=>'text-center headline','style'=>'font-size:30px;margin-top:20px;'));
				$CI->make->append(onScrNumPad('amount-input','enter-amount-btn','cancel-amount-btn'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
?>