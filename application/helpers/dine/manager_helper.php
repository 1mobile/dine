<?php
function managerReasonsPage()
{
	$CI =& get_instance();
	$CI->make->sDiv(array('id'=>'cashier-panel','style'=>'margin-top:30px;'));
		$CI->make->hidden('pop-reason',null);
		$CI->make->sDivRow();
			$buttons = array(
				"Wrong Items Ordered",
				"No Show Pick up",
				"No Show Delivery",
				"Took Too long",
				"Customer Didn't Like it",
				"Manager Comp",
				"Employee Training"
			);
			foreach ($buttons as $text) {
				$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
					$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red reason-btns double'));
				$CI->make->eDivCol();
			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
function managerLoginPage()
{
	$CI =& get_instance();
	$CI->make->sDiv();
		$CI->make->span('Manager Login',array('class'=>'', 'style'=>'font-size:40px;font-weight:bold;margin-left:10px;'));
	$CI->make->eDiv();
	$CI->make->sDiv();
		$CI->make->append(onScrNumPwdPad('pin','pin-login','cancel-btn'));
	$CI->make->eDiv();

	return $CI->make->code();
}
function managerPage(){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'manager'));
			$CI->make->sDivRow();
				/*MANAGER*/
				$CI->make->sDivCol(12,'left',0,array('class'=>'manager-btns'));
					$buttons = array("cash-drawer"	=> fa('fa-money fa-lg fa-fw')."<br> CASH DRAWER",
									 "end-of-day"	=> fa('fa-clock-o fa-lg fa-fw')."<br> END OF DAY",
									 "report"	=> fa('fa-file-text fa-lg fa-fw')."<br> REPORTS",
									 // "order"	=> fa('fa-pencil-square fa-lg fa-fw')."<br> ORDERS",
									 // "cash-drop"	=> fa('fa-money fa-lg fa-fw')."<br> CASH DROPS",
									 // "system"	=> fa('fa-gear fa-lg fa-fw')."<br> SYSTEM",
									 // "shutdown"	=> fa('fa-power-off fa-lg fa-fw')."<br> SHUTDOWN",
									 // "exit"	=> fa('fa-sign-out fa-lg fa-fw')."<br> EXIT"
									 );
					$CI->make->sDivRow();
					foreach ($buttons as $id => $text) {
							$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:0;'));
								$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red double'));
							$CI->make->eDivCol();
					}
						// $CI->make->sDivCol(2,'left',0,array("style"=>'margin-bottom:0;'));
						// // $CI->make->sDivCol(2,'left',0,array("style"=>'margin-bottom:10px;'));
						// 	$CI->make->button(fa('fa-gear fa-lg fa-fw')."<br> SYSTEM",array('id'=>'system-btn','class'=>'btn-block manager-btn-orange double'));
						// $CI->make->eDivCol();
						$off=2;
						if(MALL_ENABLED){
							$off=1;
						}
						$CI->make->sDivCol($off,'left',0,array("style"=>'margin-bottom:0;'));
							$CI->make->button(fa('fa-print fa-lg fa-fw')."<br> Prints",array('id'=>'printings-btn','class'=>'btn-block manager-btn-orange double'));
						$CI->make->eDivCol();
						// $CI->make->sDivCol($off,'left',0,array("style"=>'margin-bottom:0;'));
						// 	$CI->make->button(fa('fa-pencil-square fa-lg fa-fw')."<br> ORDERS",array('id'=>'order-btn','class'=>'btn-block manager-btn-orange double'));
						// $CI->make->eDivCol();
						if(MALL_ENABLED){
							$CI->make->sDivCol(1,'left',0,array("style"=>'margin-bottom:0;'));
								$CI->make->button(fa('fa-building-o fa-lg fa-fw')."<br> MALL",array('id'=>'mall-btn','mall'=>MALL,'class'=>'btn-block manager-btn-green double'));
							$CI->make->eDivCol();
						}
						$CI->make->sDivCol(1,'left',0,array("style"=>'margin-bottom:0'));
						// $CI->make->sDivCol(2,'left',0,array("style"=>'margin-bottom:10px;'));
							$CI->make->button(fa('fa-sign-out fa-lg fa-fw')."<br> EXIT",array('id'=>'exit-btn','class'=>'btn-block manager-btn-red-gray double'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();

				$CI->make->eDivCol();

			$CI->make->eDivRow();

			/* CONTENT BELOW MENU - start */
				$CI->make->sDiv(array('class'=>'manager-content'));

				$CI->make->eDiv();
			/* CONTENT BELOW MENU - end */

		$CI->make->eDiv();
	return $CI->make->code();
}
function managerSettingsPage(){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'manager-settings'));
			$CI->make->sDiv(array('class'=>'manager-system'));
				$CI->make->sBox('default',array('class'=>'box-solid'));
					$CI->make->sBoxBody(array(
						'style'=>'background-color:#015D56;min-height:100px;padding:0 0 10px 0'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4,'left',0,array());
								$CI->make->button('SETTINGS',array('class'=>'btn-block manager-settings-btn-orange no-raduis','id'=>'settings-btn'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(4,'left',0,array());
								$CI->make->button('DATABASE',array('class'=>'btn-block manager-settings-btn-red-gray no-raduis','id'=>'status-btn','ref'=>'status'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(4,'left',0,array());
								$CI->make->button('PRICING',array('class'=>'btn-block manager-settings-btn-red-gray no-raduis','id'=>'sched-btn','ref'=>'sched'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDiv(array('id'=>'settings-div','class'=>'loads-div','style'=>'display:none;'));
						$CI->make->eDiv();
						// $CI->make->sDivRow();
						// 	$CI->make->sDivCol(12,'left',0,array('class'=>'customer-btns'));
						// 		$CI->make->sDiv(array('id'=>'wrap'));
						// 		$CI->make->eDiv();
						// 	$CI->make->eDivCol();
						// $CI->make->eDivRow();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}
function managerEndOfDayPage(){
	$CI =& get_instance();

		// $CI->make->sDiv(array('id'=>'manager-settings'));
		// $CI->make->sDiv(array('id'=>'manager-dayend'));
			$CI->make->sDiv(array('class'=>'manager-div-center'));
				$CI->make->sBox('default',array('class'=>'box-solid'));
					$CI->make->sBoxBody(array(
						'style'=>'background-color:#f4ede0;min-height:550px;padding:0 0 10px 0'));
						$buttons = array("day-report"	=> fa('fa-print fa-lg fa-fw')."<br> End of Day Summary",
										 "day-xread"	=> fa('fa-file-o fa-lg fa-fw')."<br> X-Read",
										 "day-zread"	=> fa('fa-file-o fa-lg fa-fw')."<br> Z-Read",
										 );
						$CI->make->sDivRow(array('style'=>'margin:0;'));
						foreach ($buttons as $id => $text) {
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;padding:0px;'));
									$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-teal double'));
								$CI->make->eDivCol();
						}
						$CI->make->eDivRow();
						$CI->make->sDiv(array('id'=>'manager-content'));
						$CI->make->eDiv();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDiv();
		// $CI->make->eDiv();

	return $CI->make->code();
}
function managerEndofDayReport()
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		//////////////////////left side///////////////////
		$CI->make->sDivCol('4','left',1);
			$CI->make->sBox('default',array('class'=>'box-solid','style'=>'height:380px;'));
			$CI->make->sBoxHead(array('class'=>'bg-red','style'=>'background-color:#7a6a63;'));
				$CI->make->boxTitle('Current Day - All Pos Stations', array());
			$CI->make->eBoxHead();
			$CI->make->sBoxBody();
				$CI->make->sDivRow();
					$CI->make->sDivCol('6','left');
						$CI->make->span('Open Orders Count :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$CI->make->span('Open Orders Total :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						// $CI->make->span('Open PreAuth Count :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						$CI->make->span('Orders Settled :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$CI->make->span('Transactions :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$CI->make->span('Order Subtotal :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$CI->make->span('Order Tax :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						// $CI->make->span('Discounts :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						$CI->make->span('Voids Counts :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$CI->make->span('Voids Total :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						// $CI->make->span('Cash Paid Out :',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
					$CI->make->eDivCol();
					$CI->make->sDivCol('6','left');
						$date = date('Y-m-d');
						$open_order = $CI->manager_model->get_open_order($date);
						$CI->make->span(count($open_order),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$open_order_total = $CI->manager_model->get_open_order_total($date);
						$CI->make->span('P'.number_format($open_order_total[0]->total_amount,2),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						// $CI->make->span('0',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						$settled_order = $CI->manager_model->get_settled_order($date);
						// echo $CI->db->last_query();
						$CI->make->span(count($settled_order),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$trans = $CI->manager_model->get_transactions($date);
						$CI->make->span(count($trans),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$sub = $CI->manager_model->get_subtotal($date);
						$CI->make->span('P'.number_format($sub[0]->total_amount,2),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$tax = $CI->manager_model->get_taxtotal($date);
						$CI->make->span('P'.number_format($tax[0]->amount,2),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						//$tax = $CI->manager_model->get_disctotal($date);
						// $CI->make->span('0',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						$void = $CI->manager_model->get_voids($date);
						$CI->make->span(count($void),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						$CI->make->append('<br>');
						$void_total = $CI->manager_model->get_void_total($date);
						$CI->make->span('P'.number_format($void_total[0]->total_amount,2),array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
						// $CI->make->append('<br>');
						// $CI->make->span('0',array('class'=>'', 'style'=>'font-size:17px;font-weight:bold;'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		//////////////////////right side//////////////////////
		$CI->make->sDivCol('4','left');
			$CI->make->sDiv(
				array(
					'class'=>'div-report-txt',
					'style'=>'position:relative;
								height:380px;
								overflow:hidden;
								background-color:#fff;
								padding:10px 15px 10px 10px;'));
				$CI->make->span('END OF DAY REPORT',array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br><br>');
					$CI->make->span('GENERATED '.date('m/d/Y h:i:s A'),array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br>');
					$CI->make->span('----------------------------------------------------------',array('class'=>'', 'style'=>'font-size:14px;'));
					$CI->make->append('<br>');
					$CI->make->span('TRANSACTION SUMMARY',array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br>');
					$date = date('Y-m-d');
					$gtotal = $summary_total = 0;

					$cash_total = 0;
					$credit_total = 0;
					$check_total = 0;
					$debit_total = 0;
					$gc_total = 0;
					///////////////FOR CASH
					$get_cash = $CI->manager_model->get_payment_type($date,'cash');
					if(count($get_cash) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_cash_count = $CI->manager_model->get_payment_count($date,'cash');
							$CI->make->span('CASH('.count($get_cash_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_cash as $cval){
							if($cval->to_pay > $cval->amount){
								$cash_total += $cval->amount;
							}else{
								$cash_total += $cval->to_pay;
							}
							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($cash_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR CREDIT
					$get_credit = $CI->manager_model->get_payment_type($date,'credit');
					if(count($get_credit) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_credit_count = $CI->manager_model->get_payment_count($date,'credit');
							$CI->make->span('CREDIT CARD('.count($get_credit_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_credit as $cval){
							if($cval->to_pay > $cval->amount){
								$credit_total += $cval->amount;
							}else{
								$credit_total += $cval->to_pay;
							}
							// echo $cash_total."---";
						}

						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($credit_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR CHECK
					$get_check= $CI->manager_model->get_payment_type($date,'check');
					if(count($get_check) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_check_count = $CI->manager_model->get_payment_count($date,'check');
							$CI->make->span('CHECK('.count($get_check_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_check as $cval){
							if($cval->to_pay > $cval->amount){
								$check_total += $cval->amount;
							}else{
								$check_total += $cval->to_pay;
							}
							// echo $cash_total."---";
						}

						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($check_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR DEBIT
					$get_debit = $CI->manager_model->get_payment_type($date,'debit');
					if(count($get_debit) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_debit_count = $CI->manager_model->get_payment_count($date,'debit');
							$CI->make->span('DEBIT('.count($get_debit_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_debit as $cval){
							if($cval->to_pay > $cval->amount){
								$debit_total += $cval->amount;
							}else{
								$debit_total += $cval->to_pay;
							}
							// echo $cash_total."---";
						}

						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($debit_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR GC
					$get_gc= $CI->manager_model->get_payment_type($date,'gc');
					if(count($get_gc) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_gc_count = $CI->manager_model->get_payment_count($date,'gc');
							$CI->make->span('GIFTCARD('.count($get_gc_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_gc as $cval){
							if($cval->to_pay > $cval->amount){
								$gc_total += $cval->amount;
							}else{
								$gc_total += $cval->to_pay;
							}
							// echo $cash_total."---";
						}

						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($gc_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}

					$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$CI->make->span('===========',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$gtotal = $cash_total + $credit_total + $debit_total + $gc_total + $check_total;
							$CI->make->span('P'.number_format($gtotal,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();


					/////////////////////////TYPE SUMMARY
					$CI->make->append('<br>');
					$CI->make->span('SALES BY ORDER TYPE SUMMARY',array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br>');

					$counter_total = $dinein_total = $drivethru_total = $deliver_total = $pickup_total = $takeout_total = 0;

					///////////////FOR COUNTER
					$get_counter = $CI->manager_model->get_summary_type($date,'counter');
					if(count($get_counter) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_counter_count = $CI->manager_model->get_summary_count($date,'counter');
							$CI->make->span('COUNTER('.count($get_counter_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_counter as $cval){

								$counter_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($counter_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR DINEIN
					$get_dinein = $CI->manager_model->get_summary_type($date,'dinein');
					if(count($get_dinein) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_dinein_count = $CI->manager_model->get_summary_count($date,'dinein');
							$CI->make->span('DINE-IN('.count($get_dinein_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_dinein as $cval){

								$dinein_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($dinein_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR Drivethru
					$get_drive = $CI->manager_model->get_summary_type($date,'drivethru');
					if(count($get_drive) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_drivethru_count = $CI->manager_model->get_summary_count($date,'drivethru');
							$CI->make->span('DRIVE-THRU('.count($get_drivethru_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_drive as $cval){

								$drivethru_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($drivethru_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR Delivery
					$get_deliver = $CI->manager_model->get_summary_type($date,'delivery');
					if(count($get_deliver) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_deliver_count = $CI->manager_model->get_summary_count($date,'delivery');
							$CI->make->span('DELIVERY('.count($get_deliver_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_deliver as $cval){

								$deliver_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($deliver_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR Pickup
					$get_pickup = $CI->manager_model->get_summary_type($date,'pickup');
					if(count($get_pickup) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_pickup_count = $CI->manager_model->get_summary_count($date,'pickup');
							$CI->make->span('PICKUP('.count($get_pickup_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_pickup as $cval){

								$pickup_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($pickup_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR Takeout
					$get_takeout = $CI->manager_model->get_summary_type($date,'takeout');
					if(count($get_takeout) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$get_takeout_count = $CI->manager_model->get_summary_count($date,'takeout');
							$CI->make->span('TAKEOUT('.count($get_takeout_count).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($get_takeout as $cval){

								$takeout_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($takeout_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}

					$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$CI->make->span('===========',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$summary_total = $drivethru_total + $counter_total + $dinein_total + $deliver_total+ $pickup_total + $takeout_total;
							$CI->make->span('P'.number_format($summary_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();

					/////////////////////////STATION
					$CI->make->append('<br>');
					$CI->make->span('SALES BY STATION SUMMARY',array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br>');

					$get_terminals = $CI->manager_model->get_terminals();

					if(count($get_terminals) > 0){
						$total_all_terminals = 0;
						foreach ($get_terminals as $val) {
							$t_terminal = 0;
							$get_terminal_total = $CI->manager_model->get_terminal_total($date,$val->terminal_id);
							if(count($get_terminal_total) > 0){
								$CI->make->sDivRow();
								$CI->make->sDivCol('6','left');
									$CI->make->span('POS STATION '.$val->terminal_id.'('.count($get_terminal_total).')',array('class'=>'', 'style'=>'font-size:14px;'));
								$CI->make->eDivCol();
								foreach($get_terminal_total as $cval){

										$t_terminal += $cval->total_amount;

									// echo $cash_total."---";
								}


								$CI->make->sDivCol('6','right');
									$CI->make->span('P'.number_format($t_terminal,2),array('class'=>'', 'style'=>'font-size:14px;'));
								$CI->make->eDivCol();
								$CI->make->eDivRow();
							}
							$total_all_terminals += $t_terminal;
						}

						$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$CI->make->span('===========',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol('12','right');
								$CI->make->span('P'.number_format($total_all_terminals,2),array('class'=>'', 'style'=>'font-size:14px;'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();

					}

					/////////////////////////VOIDS
					$CI->make->append('<br>');
					$CI->make->span('VOID SUMMARY',array('class'=>'', 'style'=>'font-size:14px;font-weight:bold;'));
					$CI->make->append('<br>');

					$openvoid_total = $settledvoid_total = 0;
					///////////////FOR VOID OPEN
					$openvoid = $CI->manager_model->get_void_open($date);
					if(count($openvoid) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$CI->make->span('OPEN-VOID('.count($openvoid).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($openvoid as $cval){

								$openvoid_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($openvoid_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}
					///////////////FOR VOID SETTLED
					$settledvoid = $CI->manager_model->get_void_settled($date);
					if(count($settledvoid) > 0){
						$CI->make->sDivRow();
						$CI->make->sDivCol('6','left');
							$CI->make->span('SETTLED-VOID('.count($settledvoid).')',array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						foreach($settledvoid as $cval){

								$settledvoid_total += $cval->total_amount;

							// echo $cash_total."---";
						}


						$CI->make->sDivCol('6','right');
							$CI->make->span('P'.number_format($settledvoid_total,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
						$CI->make->eDivRow();
					}

					$CI->make->sDivRow();
					$CI->make->sDivCol('12','right');
						$CI->make->span('===========',array('class'=>'', 'style'=>'font-size:14px;'));
					$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol('12','right');
							$total_all_void = $openvoid_total + $settledvoid_total;
							$CI->make->span('P'.number_format($total_all_void,2),array('class'=>'', 'style'=>'font-size:14px;'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
			$CI->make->eDiv();
			// $CI->make->sDivRow(array(
			// 	'style'=>'position:relative;height:380px;overflow:hidden;background-color:#ffffff;',
			// 	'id'=>'report-txt-div'
			// ));
				// $CI->make->sDivCol('12','center');



					///////////////////////////END////////////////////////////////////
			// 	$CI->make->eDivCol();
			// $CI->make->eDivRow();
		$CI->make->eDivCol();

		$CI->make->sDivCol('2','right');
			// $CI->make->sDivRow();
				// $CI->make->sDivCol();
					$CI->make->button(fa('fa-print fa-lg fa-fw').' PRINT REPORT',array('id'=>'print-btn','class'=>'btn-block double manager-orders-btn-green','style'=>'background-color:#749a02'));
				// $CI->make->eDivCol();
			// $CI->make->eDivRow();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function managerXreadPage(){
	$CI =& get_instance();

	$CI->make->append('<br/>');
	$CI->make->sDivRow();
		$CI->make->sDivCol('4','left',3);
			$CI->make->sDiv(array(
					'class'=>'div-report-txt',
					'style'=>'position:relative;
								height:380px;
								overflow:hidden;
								background-color:#fff;
								padding:10px 15px 10px 10px;'));
				$CI->make->append('<pre id="read-txt"></pre>');
			$CI->make->eDiv();
		$CI->make->eDivCol();
		$CI->make->sDivCol('3','left');
			$CI->make->button(fa('fa-print fa-lg fa-fw')." PROCESS X-READ",array('id'=>'print-btn','class'=>'btn-block double manager-btn-green'));
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function managerZreadPage($allsales = array()){
	$CI =& get_instance();

	$CI->make->append('<br/>');
	$CI->make->sDivRow();
		$CI->make->sDivCol('4','left',3);
			$CI->make->sDiv(array(
					'class'=>'div-report-txt',
					'style'=>'position:relative;
								height:380px;
								overflow:hidden;
								background-color:#fff;
								padding:10px 15px 10px 10px;'));
				$CI->make->append('<pre id="read-txt"></pre>');
			$CI->make->eDiv();
		$CI->make->eDivCol();
		$CI->make->sDivCol('3','left');
			$CI->make->button(fa('fa-print fa-lg fa-fw')." PROCESS Z-READ",array('id'=>'print-btn','class'=>'btn-block double manager-btn-green'));
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function get_order($asJson=true,$sales_id=null){
	$CI =& get_instance();
        /*
         * -------------------------------------------
         *   Load receipt data
         * -------------------------------------------
        */

        $orders = $CI->cashier_model->get_trans_sales($sales_id);
        $order = array();
        $details = array();
        foreach ($orders as $res) {
            $order = array(
                "sales_id"=>$res->sales_id,
                'ref'=>$res->trans_ref,
                "type"=>$res->type,
                "table_id"=>$res->table_id,
                "guest"=>$res->guest,
                "user_id"=>$res->user_id,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "amount"=>$res->total_amount,
                "balance"=>$res->total_amount - $res->total_paid,
                "paid"=>$res->paid,
                // "pay_type"=>$res->pay_type,
                // "pay_amount"=>$res->pay_amount,
                // "pay_ref"=>$res->pay_ref,
                // "pay_card"=>$res->pay_card,
                "inactive"=>$res->inactive,
                "reason"=>$res->reason,
            );
        }

        $order_menus = $CI->cashier_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
        $order_mods = $CI->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
        $sales_discs = $CI->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
        $sales_tax = $CI->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
        foreach ($order_menus as $men) {
            $details[$men->line_id] = array(
                "id"=>$men->sales_menu_id,
                "menu_id"=>$men->menu_id,
                "name"=>$men->menu_name,
                "code"=>$men->menu_code,
                "price"=>$men->price,
                "qty"=>$men->qty,
                "discount"=>$men->discount
            );
            $mods = array();
            foreach ($order_mods as $mod) {
                if($mod->line_id == $men->line_id){
                    $mods[$mod->sales_mod_id] = array(
                        "id"=>$mod->mod_id,
                        "line_id"=>$mod->line_id,
                        "name"=>$mod->mod_name,
                        "price"=>$mod->price,
                        "qty"=>$mod->qty,
                        "discount"=>$mod->discount
                    );
                }
            }
            $details[$men->line_id]['modifiers'] = $mods;
        }
        $discounts = array();
        foreach ($sales_discs as $dc) {
            $items = array();
            if($dc->items != ""){
                $items = explode(',', $dc->items);
            }
            $discounts[$dc->disc_id] = array(
                    "name"  => $dc->name,
                    "code"  => $dc->code,
                    "bday"  => sql2Date($dc->bday),
                    "guest" => $dc->guest,
                    "disc_rate" => $dc->disc_rate,
                    "disc_code" => $dc->disc_code,
                    "disc_type" => $dc->type,
                    "items" => $items
                );
        }
        $tax = array();
        foreach ($sales_tax as $st) {
            $tax[$st->sales_tax_id] = array(
                    "sales_id"  => $st->sales_id,
                    "name"  => $st->name,
                    "rate" => $st->rate,
                    "amount" => $st->amount
                );
        }

        if($asJson)
            echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"tax"=>$tax));
        else
            return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"tax"=>$tax);
}
function systemSettingsPage($det = array()){
	$CI =& get_instance();
	$CI->make->sDiv(array('style'=>'height:220px;background-color:#fff;margin:10px;padding:10px;overflow:auto;'));
		$CI->make->sForm("manager/system_settings_db",array('id'=>'details_form'));
			// $CI->make->sDivRow(array('style'=>'margin:10px;'));
			// 	$CI->make->sDivCol(12, 'right');
			//
			// 	$CI->make->eDivCol();
			// $CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->hidden('tax_id',iSetObj($det,'tax_id'));
					$CI->make->input('Code','branch_code',iSetObj($det,'branch_code'),'Type Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
					$CI->make->input('Address','address',iSetObj($det,'address'),'Type Branch Address',array('class'=>'rOkay'));
					$CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array('class'=>'rOkay'));
					$CI->make->input('Website','website',iSetObj($det,'website'),'Website',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->input('Name','branch_name',iSetObj($det,'branch_name'),'Type Name',array('class'=>'rOkay'));
					$CI->make->input('Email','email',iSetObj($det,'email'),'Email Address',array('class'=>'rOkay'));
					$CI->make->input('Machine No.','machine_no',iSetObj($det,'machine_no'),'Machine Number',array('class'=>'rOkay'));
					$CI->make->sDiv(array('style'=>'margin:10px;margin-top:15px;'));
						$CI->make->button(fa('fa-save fa-fw').' Save',array('id'=>'save-btn','class'=>'btn-block manager-settings-btn-green no-raduis'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->input('Description','branch_desc',iSetObj($det,'branch_desc'),'Type Description',array('class'=>'rOkay'));
					$CI->make->input('Delivery No.','delivery_no',iSetObj($det,'delivery_no'),'Type Delivery Number',array('class'=>'rOkay'));
					$CI->make->input('Permit','permit_no',iSetObj($det,'permit_no'),'Permit Number',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->input('TIN','tin',iSetObj($det,'tin'),'TIN',array('class'=>'rOkay'));
					$CI->make->input('Contact No.','contact_no',iSetObj($det,'contact_no'),'Type Contact Number',array('class'=>'rOkay'));
					$CI->make->input('Serial #','serial',iSetObj($det,'serial'),'Serial Number',array('class'=>'rOkay'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eForm();

	$CI->make->eDiv();



	return $CI->make->code();
}
////////////////////////////////////////jed end
function managerOrdersPage(){
	$CI =& get_instance();

		// $CI->make->sDiv(array('id'=>'manager-settings'));
		$CI->make->sDiv(array('id'=>'manager-orders'));

					$CI->make->sDiv(array('class'=>'manager-orders-center'));
							$CI->make->sBox('default',array('class'=>'box-solid'));
								$CI->make->sBoxHead(array('class'=>'bg-red'));
									$CI->make->boxTitle('CURRENT DAY ORDERS', array());
								$CI->make->eBoxHead();
								$CI->make->sBoxBody();
									// $CI->make->append('<hr>');
									$CI->make->sDiv(array('class'=>'manager-orders-btns'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(8,'left',0,array('class'=>'open-all-btns'));

												$CI->make->sDivRow();
													$CI->make->sDiv(array('class'=>'manager-orders-menu'));
														$CI->make->sDivCol(6,'',0,array());
															// $CI->make->button(fa('fa-user fa-lg fa-fw')." OPEN ORDERS",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-blue double'));
															$CI->make->button(fa('fa-user fa-lg fa-fw')." OPEN ORDERS",array('ref'=>'my', 'status'=>'open', 'id'=>'open-orders-btn','class'=>'my-all-btns btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(6,'',0,array());
															// $CI->make->button(fa('fa-users fa-lg fa-fw')." ALL ORDERS",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-blue double'));
															$CI->make->button(fa('fa-arrow-down fa-lg fa-fw')." SETTLED ORDERS",array('ref'=>'all', 'status'=>'all', 'id'=>'all', 'id'=>'all-orders-btn','class'=>'my-all-btns btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
													$CI->make->eDiv();
												$CI->make->eDivRow();

												$CI->make->hidden('clickedmenu', '', array());

												$CI->make->sDivRow();
													$CI->make->sDivCol(12,'',0,array('class'=>'manager-orders-list','terminal'=>'my','types'=>'all', 'style'=>'height:450px; overflow: auto;'));

													$CI->make->eDivCol();

												$CI->make->eDivRow();

												// $CI->make->sDiv(array('class'=>'manager-orders-close-trx'));
													// $CI->make->sDivRow();
														// $CI->make->sDivCol(12,'',0,array());
															// $CI->make->button(fa('fa-times fa-lg fa-fw')." CLOSE",array('ref'=>'my', 'status'=>'open', 'id'=>'open-orders-btn','class'=>'my-all-btns btn-block manager-orders-btn-orange double'));
														// $CI->make->eDivCol();
													// $CI->make->eDivRow();
												// $CI->make->eDiv();

											$CI->make->eDivCol();
											$CI->make->sDivCol(4,'right',0,array('class'=>'open-all-btns'));
												$CI->make->sDiv(array('class'=>'manager-orders-main-btns'));

													$CI->make->sDivRow();
														$CI->make->sDivCol(6,'',0,array());
															$CI->make->button(fa('fa-print fa-lg fa-fw')." PRINT SELECTED",array('id'=>'print-selected-btn','class'=>'btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(6,'',0,array());
															$CI->make->button(fa('fa-print fa-lg fa-fw')." PRINT ALL",array('id'=>'print-all-btn','class'=>'btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
													$CI->make->sDivRow();
														$CI->make->sDivCol(12,'',0,array("style"=>"margin-top:10px;"));
															$CI->make->button(fa('fa-repeat fa-lg fa-fw')." RECALL",array('id'=>'recall-btn','class'=>'btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
													$CI->make->sDivRow();
														$CI->make->sDivCol(12,'',0,array("style"=>"margin-top:10px;"));
															$CI->make->button(fa('fa-list-alt fa-lg fa-fw')." VIEW TRANSACTIONS",array('id'=>'view-trx-btn','class'=>'btn-block manager-orders-btn-blue double'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
													// $CI->make->sDivRow();
													// 	$CI->make->sDivCol(6,'',0,array("style"=>"margin-top:10px;"));
													// 		$CI->make->button(fa('fa-times fa-lg fa-fw')." VOID SELECTED",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-blue double'));
													// 	$CI->make->eDivCol();
													// 	$CI->make->sDivCol(6,'',0,array("style"=>"margin-top:10px;"));
													// 		$CI->make->button(fa('fa-times fa-lg fa-fw')." VOID ALL",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-red double'));
													// 	$CI->make->eDivCol();
													// $CI->make->eDivRow();
													// $CI->make->sDivRow();
													// 	$CI->make->sDivCol(12,'',0,array("style"=>"margin-top:10px;"));
													// 		$CI->make->button(fa('fa-arrow-right fa-lg fa-fw')." TRANSFER SERVER",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-green double'));
													// 	$CI->make->eDivCol();
													// $CI->make->eDivRow();
													// $CI->make->sDivRow();
													// 	$CI->make->sDivCol(12,'',0,array("style"=>"margin-top:10px;"));
													// 		$CI->make->button(fa('fa-arrow-right fa-lg fa-fw')." TRANSFER DRIVER",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-teal double'));
													// 	$CI->make->eDivCol();
													// $CI->make->eDivRow();
													/*
													$CI->make->sDivRow();
														$CI->make->sDivCol(12,'',0,array("style"=>"margin-top:10px;"));
															$CI->make->button(fa('fa-sign-out fa-lg fa-fw')." FINISHED",array('id'=>'open-orders-btn','class'=>'btn-block manager-orders-btn-red-gray double'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
													*/

												$CI->make->eDiv();

												$CI->make->sDiv(array('class'=>'manager-orders-close-trx'));
													$CI->make->sDivRow();
														$CI->make->sDivCol(12,'',0,array());
															$CI->make->button(fa('fa-times fa-lg fa-fw')." CLOSE",array('id'=>'close-trx-btn','class'=>'my-all-btns btn-block manager-orders-btn-orange double'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
												$CI->make->eDiv();

											$CI->make->eDivCol();
										$CI->make->eDivRow();
									$CI->make->eDiv();
								$CI->make->eBoxBody();
							$CI->make->eBox();
					$CI->make->eDiv();
		$CI->make->eDiv();

	return $CI->make->code();
}
function managerReportPage()
{
	$CI =& get_instance();

	$CI->make->sDiv(array('class'=>'manager-div-center'));
		$CI->make->sBox('default',array('class'=>'box-solid'));
			$CI->make->sBoxBody(array(
				'style'=>'background-color:#f4ede0;min-height:550px;padding:0 0 10px 0'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(3,'center',0,array('class'=>'scrollbar-div','style'=>'position:relative;
								height:550px;
								overflow:hidden;'));
						$buttons = array(
							// "daily-sales" => fa('fa-calendar-o fa-2x')." Daily Sales",
							// "terminal-sales" => fa('fa-money fa-2x')." Terminal Sales",
							// "cashier-sales" => fa('fa-male fa-2x')." Cashier Sales",
							// "customer-statistics" => fa('fa-group fa-2x')." Customer Statistics",
							// "senior-citizen-dd" => fa('fa-smile-o fa-2x')." Senior Citizen Discount Report",
							// "voided-transactions" => fa('fa-book fa-2x')." Voided Transactions",
							// "daily-tax-dues" => fa('fa-bank fa-2x')." Daily Tax Dues",
							// "daily-menu-orders" => fa('fa-cutlery fa-2x')." Daily Menu Orders",
							// "daily-top-menu" => fa('fa-bar-chart fa-2x')." Daily Top Menu",
							"system-sales" => fa('fa-group fa-2x')." System Sales Report",
							"employee-sales" => fa('fa-newspaper-o fa-2x')." Employee Sales Report",
							"menu-sales" => fa('fa-suitcase fa-2x')." Menu Item Report",
							"fs-sales" => fa('fa-bullhorn fa-2x')." Food Server Sales",
							"hourly-sales" => fa('fa-clock-o fa-2x')." Hourly Sales Report",
						);
						foreach ($buttons as $id => $text) {
							$CI->make->button($text,array('id'=>$id,'class'=>'btn-manager-report btn-block manager-btn-teal triple'));
						}
					$CI->make->eDivCol();
					$CI->make->sDivCol(3,'center',0,array('id'=>'report-form-div','style'=>'margin-top:10px'));
					$CI->make->eDivCol();


					$CI->make->sDivCol(4,'',1,array());
						$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
							$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
							$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray'));
						$CI->make->eDivCol();
						$CI->make->sDiv(array('id'=>'report-view-div','style'=>'position:relative;
									height:380px;
									overflow:hidden;
									background-color:#fff;
									padding:10px 15px 10px 10px;
									margin-top:10px','class'=>'scrollbar-div'));
						$CI->make->eDiv();

					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eBoxBody();
		$CI->make->eBox();
	$CI->make->eDiv();

	return $CI->make->code();
}
function build_report()
{
	$CI =& get_instance();

	$arg_list = func_get_args();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			// $CI->make->sForm($arg_list[0],array('id'=>'report_form'));
			$CI->make->sForm(null,array('id'=>'report_form'));
				// $sel = '';
				$sel = str_replace("_","-",$arg_list[0]);
				foreach ($arg_list[1] as $func => $func_args) {
					if (is_array($func_args)) {
						if (strpos($func, "-")) {
							$func_a = explode("-", $func);
							$func = $func_a[0];
						}
						$str = call_user_func_array(array(&$CI->make,$func), $func_args);
					}
					else
						$str = $func_args;

					$CI->make->append($str);
					// $CI->make->append($arg_list[0]);
				}
				//joms
				$CI->make->button(
					fa('fa-share fa-lg fa-fw')." Submit",
					array(
						// 'class'=>'btn-block manager-btn-green no-raduis run_rep_btn',
						// 'id'=>'run-rep-'.$sel,
						'class'=>'btn-block manager-btn-green no-raduis report-submit-btn',
						'id' => 'run-rep-btn',
						'data-sel'=>$sel
					),
					$type='default');
				$CI->make->button(
					fa('fa-print fa-lg')." Print Report",
					array(
						'class'=>'btn-block manager-btn-black no-raduis report-submit-btn',
						'id'=>'gen-rep-btn',
						'data-sel'=>$sel
					),
					$type='default');
				// $CI->make->button(fa('fa-print fa-lg fa-fw')."Print",array('class'=>'btn-block manager-btn-green no-raduis print_as_receipt','id'=>'gen-rep-'.$sel,'data-sel'=>$sel),$type='default');
			$CI->make->eForm();
		$CI->make->eDivCol();
		// $CI->make->sDivCol(5);
		// 	$CI->make->append('<pre id="report_preview" style="width: 200px !important;">');
		// 		$CI->make->append('asdasdasd');
		// 	$CI->make->append('</pre>');
		// $CI->make->eDivCol();
		// $CI->make->sDivCol('5','left');
		// 	$CI->make->sDiv(
		// 		array(
		// 			'id'=>'report-view-div',
		// 			'style'=>'position:relative;
		// 						height:380px;
		// 						width:200px;
		// 						overflow:hidden;
		// 						background-color:#fff;
		// 						padding:10px 15px 10px 10px;'));
		// 				$CI->make->append('asdasdasdasdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd');
		// 	$CI->make->eDiv();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function view_report($data = array(),$rep_title,$cashier,$server,$date,$total_vat_sales,$total_vat_exempt_sales,$total_vat,$total_discount,$total_sales){
	$CI =& get_instance();

	$arg_list = func_get_args();
	$title = '';
	if($rep_title = 'daily-sales') $title = 'DAILY SALES REPORT';
	// else if($rep_title = 'cashier-sales') $title = 'CASHIER SALES REPORT';

	// $CI->make->sDivRow();
	// $CI->make->append("<?php header('Content-type: application/ms-excel');");
	// $CI->make->append("header('Content-Disposition: attachment; filename='report.xls');");
			$CI->make->sDiv(
				array(
					'style'=>'margin:50px 10px 0px 0px;
								position:relative;
								height:380px;
								overflow:hidden;
								background-color:#fff;
								padding:10px 15px 10px 10px;','id'=>'view-scrollbar-div'));
						$CI->make->append('<table border=1 class="table table-bordered table-striped data-table  dataTable">
						<thead style="border-bottom:1px solid; ">');
						$CI->make->append('<tr><th colspan="9"><h4>'.$title.'</h4>');
						$CI->make->append('<p>As of Date: '.sql2date($date).'</p>');
						$CI->make->append('<p>Cashier: '.$cashier.'</p>');
						$CI->make->append('<p>Terminal: '.$server.'</p>');
						$CI->make->append('</tr>');
						if($rep_title = 'daily-sales'){
							$title_hdr = array(
								'Receipt No.',
								'Terminal',
								'VAT Sales',
								'Vat Exempt Sales',
								'VAT',
								'Discounts',
								'Discount Type',
								'Total Sales',
								'Status');
						}else if($rep_title = 'cashier-sales'){
							$title_hdr = array('Receipt No.','Terminal','Vat Exempt Sales','VAT','Discounts','Discount Type','Total Sales','Status');
						}

						foreach($title_hdr as $hdr){
							$CI->make->append('<th>'.$hdr.'</th>');
						}
						$CI->make->append('</thead>');

						$CI->make->append('<tbody>');
							// $CI->make->append(count($data));
						if(count($data) > 1)
						foreach($data as $v){
							$CI->make->append('<tr>');
								$CI->make->append('<td>'.$v['receipt_no'].'</td>');
								$CI->make->append('<td>'.$v['terminal_name'].'</td>');
								$CI->make->append('<td>'.number_format($v['vat_sales'],2).'</td>');
								$CI->make->append('<td>'.number_format(0,2).'</td>');
								$CI->make->append('<td>'.number_format($v['vat'],2).'</td>');
								$CI->make->append('<td>'.number_format($v['discount'],2).'</td>');
								$CI->make->append('<td>'.number_format($v['vat_exempt'],2).'</td>');
								// $CI->make->append('<td>'.number_format($v['total'],2).'</td>');
								// $CI->make->append('<td>'.($v['status']==0? 'Sales':'Voided').'</td>');
								if ($v['status'] == 0) {
									$CI->make->append('<td>'.number_format($v['total'],2).'</td>');
									$CI->make->append('<td>Sales</td>');
								} else {
									$CI->make->append('<td>('.number_format($v['total'],2).')</td>');
									$CI->make->append('<td>Voided</td>');
								}
							$CI->make->append('</tr>');
						}

						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4">&nbsp;</td>');
							$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tr>');
						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4"><b>Total Vat Sales</b></td>');
							$CI->make->append('<td colspan="2">Php '.number_format($total_vat_sales,2).'</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tr>');
						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4"> <b>Total Vat Exempt Sales</b> </td>');
							$CI->make->append('<td colspan="2">Php '.number_format($total_vat_exempt_sales,2).'</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tr>');
						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4">  <b>Total VAT</b> </td>');
							$CI->make->append('<td colspan="2">Php '.number_format($total_vat,2).'</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tr>');
						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4">  <b>Total Discounts</b>  </td>');
							$CI->make->append('<td colspan="2">Php '.number_format($total_discount,2).'</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tr>');
						$CI->make->append('<tr>');
							if($rep_title = 'daily-sales')$CI->make->append('<td colspan="2">&nbsp;</td>');
							$CI->make->append('<td colspan="4">  <b>Total Sales</b>  </td>');
							$CI->make->append('<td colspan="2">Php '.number_format($total_sales,2).'</td>');
							$CI->make->append('<td></td>');
						$CI->make->append('</tbody>');
					$CI->make->append('</table></center>');
			$CI->make->eDiv();
	// $CI->make->eDivRow();
	return $CI->make->code();
}
?>