<?php
function timeClockPage($countin=0,$in='first_in'){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'clock'));
			$CI->make->sDivRow();

				$CI->make->sDivCol(3,'left',0,array('class'=>'clock-left'));
					$CI->make->button('CASHIER',array('id'=>'exit-btn', 'class'=>'btn-block clock-btn-green'));
					$CI->make->button('STATUS',array('class'=>'btn-block clock-btn-blue','id'=>'status-btn','ref'=>'status'));
					$CI->make->button('SCHEDULE',array('class'=>'btn-block clock-btn-blue','id'=>'sched-btn','ref'=>'sched'));
					if ($countin == 0)
						$CI->make->button('CASH DRAWER',array('class'=>'btn-block clock-btn-blue','id'=>'drawer-btn','ref'=>'drawer','disabled'=>true,'style'=>'display:none;'));
					else
						$CI->make->button('CASH DRAWER',array('class'=>'btn-block clock-btn-blue','id'=>'drawer-btn','ref'=>'drawer','style'=>'display:none;'));
					$CI->make->button('TIME CARD',array('class'=>'btn-block clock-btn-orange','id'=>'timecard-btn','ref'=>'timecard'));
					$CI->make->button('LOGOUT',array('id'=>'logout-btn', 'class'=>'btn-block clock-btn-red'));
				$CI->make->eDivCol();

				$CI->make->sDivCol(9);
					$CI->make->sDiv(array('class'=>'clock-center'));

						// $CI->make->sDiv(array('class'=>'orders-lists center-loads-div orders-div','style'=>'margin-top:10px;'));
						// $CI->make->eDiv();

						// $CI->make->sDiv(array('class'=>'orders-view-div center-loads-div'));
						// 	$CI->make->sDivRow();
						// 		$CI->make->sDivCol(6);
						// 			$CI->make->sDiv(array('class'=>'order-view-list','ref'=>null));
						// 			$CI->make->eDiv();
						// 		$CI->make->eDivCol();
						// 	$CI->make->eDivRow();
						// $CI->make->eDiv();
					$CI->make->eDiv();

					$CI->make->sDiv(array());
						$CI->make->sDivRow(array('class'=>'clock-bottom'));
							$CI->make->sDivCol(12);
								$CI->make->hidden('hidden_stat',$in,array('id'=>'hidden_stat'));
								$CI->make->hidden('hidden_date',date('Y-m-d'),array('id'=>'hidden_date'));
								$CI->make->sDiv(array('id'=>'time','class'=>'headline text-center', 'style'=>'font-size: 45px;'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
							$CI->make->sDivCol(12,'center',0,array("style"=>'margin-bottom:10px;'));
								$CI->make->span(date('l, F d, Y'),array('class'=>'headline', 'style'=>'font-size: 20px;'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();

				$CI->make->eDivCol();

			$CI->make->eDivRow();

			//-----KEYBOARD-----start
			$CI->make->sDivRow();
				$CI->make->sDivCol(12,'left',0,array('class'=>'customer-btns'));
					$CI->make->sDiv(array('id'=>'wrap'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			//-----KEYBOARD-----end


		$CI->make->eDiv();

	return $CI->make->code();
}
function drawerPage($hstat){
	$CI =& get_instance();;
		$CI->make->sDivRow(array('class'=>'clock-header'));
			$CI->make->hidden('fstat',$hstat,array('id'=>'fstat'));
            $CI->make->span('PLEASE INPUT AMOUNT',array('class'=>'clock-header clock-txt'));
        $CI->make->eDivRow();

		$CI->make->append(onScrNumDotPad('drawer-input','drawer-enter-btn'));
	return $CI->make->code();
}

function timecardPage($name="",$get_dtr=array()){
	$CI =& get_instance();;
		$CI->make->sDivRow(array('class'=>'clock-header'));
            $CI->make->span('TIME CARD',array('class'=>'clock-header clock-txt'));
        $CI->make->eDivRow();

        $CI->make->sDivRow();
        	$CI->make->sDivCol('1','center');
        	$CI->make->eDivCol();
        	$CI->make->sDivCol('10','left');
        		$CI->make->append('<br>');
        		$CI->make->sDivRow(array('style'=>'background-color:#e2dbce;border-radius:5px;'));
        		$CI->make->sDivCol('5','left');
        			$CI->make->sDivRow(array('style'=>'padding-top:12px;padding-left:10px;'));
		            $CI->make->span('NAME : ',array('style'=>'font-size:17px;font-weight:bold;color:#950000;'));
		            $CI->make->span($name,array('style'=>'font-size:17px;font-weight:bold;color:#000000;'));
        			$CI->make->eDivRow();
        		$CI->make->eDivCol();
        		$CI->make->sDivCol('3','left');
        			$CI->make->sDivRow(array('style'=>'padding-top:8px;'));
            		$CI->make->date('From','date-from','',null,array("class"=>'rOkay'));
            		$CI->make->eDivRow();
            	$CI->make->eDivCol();
            	$CI->make->sDivCol('3','left');
            		$CI->make->sDivRow(array('style'=>'padding-top:8px;padding-right:10px;'));
            		$CI->make->date('To','date-to','',null,array("class"=>'rOkay'));
            		$CI->make->eDiv();
            	$CI->make->eDivCol();
            	$CI->make->sDivCol('1','right');
        			$CI->make->sDivRow(array('style'=>'padding-top:32px;padding-right:20px;'));
		            //$CI->make->span('SEARCH : &nbsp;&nbsp;&nbsp;',array('style'=>'font-size:17px;font-weight:bold;color:#950000;'));
		            	$CI->make->button(fa('fa-search fa-fw'),array('id'=>'search-dtr','style'=>'background-color:#7a6a63;height:35px;'));
        			$CI->make->eDivRow();
        		$CI->make->eDivCol();
		        $CI->make->eDivRow();
        	$CI->make->eDivCol();
        	$CI->make->sDivCol('1','center');
        	$CI->make->eDivCol();
        $CI->make->eDivRow();

        $CI->make->append('<br>');

        $CI->make->sDivRow();
        	$CI->make->sDivCol('1','center');
        	$CI->make->eDivCol();
        	$CI->make->sDivCol('10','left');
        		$CI->make->sDivRow(array('style'=>'background-color:#e2dbce;border-radius:5px;'));
        			$CI->make->append('<br>');
        			$CI->make->sDivCol();
						$CI->make->sDiv(array('class'=>'dtr-details','style'=>'overflow:auto;;height:270px;'));
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
        	$CI->make->sDivCol('1','center');
        	$CI->make->eDivCol();
        $CI->make->eDivRow();




	return $CI->make->code();
}

function timecardTable($name="",$get_dtr=array()){
	$CI =& get_instance();;

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

	return $CI->make->code();
}


?>