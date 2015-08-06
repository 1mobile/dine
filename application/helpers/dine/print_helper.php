<?php
function datetimePage(){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->sDiv(array('style'=>'margin:10px;'));
					$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;'));
						$CI->make->sBoxBody();
							$CI->make->sForm(null,array('id'=>'sform'));
								$CI->make->sDiv(array('style'=>'padding:5px;'));
									$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
								$CI->make->eDiv();
								
								$CI->make->sDiv(array('style'=>'padding:5px;'));
									$CI->make->userDrop('Employee','cashier',null,null,array(),'asd');
								$CI->make->eDiv();
							$CI->make->eForm();
							
							// $CI->make->sDiv(array('style'=>'padding:5px;'));
							// 	$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'print-btn','class'=>'btn-block manager-btn-green'));
							// $CI->make->eDiv();
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(8);
				$CI->make->sDiv(array('style'=>'margin:10px;'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(1);
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('style'=>'padding-top:50px;'));
							$CI->make->eDiv();
							$buttons = array("system_sales_rep"	=> "System Sales",
											 "menu_sales_rep"	=> "Menu Item Sales",
											 "void_sales_rep"	=> "Void Sales",
											 // "daily_sales_rep"	=> "Daily Sales",
											 "hourly_sales_rep"	=> "Hourly Sales",
											);
							foreach ($buttons as $id => $text) {
										$CI->make->button($text,array('ref'=>$id,'title'=>$text,'class'=>'rep-btns btn-block manager-btn-teal'));
							}	
						$CI->make->eDivCol();
						$CI->make->sDivCol(9);
							$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;'));
								$CI->make->sBoxBody(array('class'=>'print-containers','style'=>'padding:0px;position:relative;background-color:#ddd'));
									$CI->make->sDiv(array('style'=>'width:100%;position:absolute;top:0px;'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(10);
												$CI->make->button(fa('fa-print').'Print',array('id'=>'print-paper-btn','class'=>'btn-block load-types-btns manager-btn-green'));
											$CI->make->eDivCol();
											$CI->make->sDivCol(1);
												$CI->make->button(fa('fa-chevron-circle-up fa-lg'),array('id'=>'up-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
											$CI->make->eDivCol();
											$CI->make->sDivCol(1);
												$CI->make->button(fa('fa-chevron-circle-down fa-lg'),array('id'=>'down-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
											$CI->make->eDivCol();
										$CI->make->eDivRow();
									$CI->make->eDiv();
									$CI->make->sDiv(array('id'=>'print-load','style'=>'padding-top:60px;'));
										$CI->make->sDiv(array('id'=>'report-view-div','style'=>'position:relavtive;height:450px;width:300px;margin:0 auto;overflow:hidden;'));
										$CI->make->eDiv();
									$CI->make->eDiv();
									
								$CI->make->eBoxBody();
							$CI->make->eBox();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();	
}
function shiftsPage($today=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->sDiv(array('style'=>'margin:10px;'));
					$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;padding:0px;'));
						$CI->make->sBoxBody();
							$CI->make->date(null,'calendar',sql2Date($today),null,array('class'=>'rOkay'),fa('fa-calendar'));
							$CI->make->sDiv(array('id'=>'shifts-load','style'=>'margin-top:10px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(8);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->sDivRow();
									$CI->make->sDivCol(1);
									$CI->make->eDivCol();
									$CI->make->sDivCol(2);
										$CI->make->sDiv(array('style'=>'padding-top:50px;'));
										$CI->make->eDiv();
										$buttons = array(
														 "system_sales_rep"	=> "XREAD",
														 "menu_sales_rep"	=> "Menu Item Sales",
														 "void_sales_rep"	=> "Void Sales",
														 // "daily_sales_rep"	=> "Daily Sales",
														 "hourly_sales_rep"	=> "Hourly Sales",
														);
										foreach ($buttons as $id => $text) {
													$CI->make->button($text,array('ref'=>$id,'title'=>$text,'class'=>'rep-btns btn-block manager-btn-teal'));
										}	
									$CI->make->eDivCol();
									$CI->make->sDivCol(9);
										$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;'));
											$CI->make->sBoxBody(array('class'=>'print-containers','style'=>'padding:0px;position:relative;background-color:#ddd'));
												$CI->make->sDiv(array('style'=>'width:100%;position:absolute;top:0px;'));
													$CI->make->sDivRow();
														$CI->make->sDivCol(10);
															$CI->make->button(fa('fa-print').'Print',array('id'=>'print-paper-btn','class'=>'btn-block load-types-btns manager-btn-green'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(1);
															$CI->make->button(fa('fa-chevron-circle-up fa-lg'),array('id'=>'up-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(1);
															$CI->make->button(fa('fa-chevron-circle-down fa-lg'),array('id'=>'down-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
												$CI->make->eDiv();
												$CI->make->sDiv(array('id'=>'print-load','style'=>'padding-top:60px;overflow:auto;'));
													$CI->make->sDiv(array('id'=>'report-view-div','style'=>'position:relavtive;height:450px;width:300px;margin:0 auto;overflow:hidden;'));
													$CI->make->eDiv();
												$CI->make->eDiv();
												
											$CI->make->eBoxBody();
										$CI->make->eBox();
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();	
}
function dayReadsPage($today=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->sDiv(array('style'=>'margin:10px;'));
					$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;padding:0px;'));
						$CI->make->sBoxBody();
							
							$CI->make->sForm(null,array('id'=>'sform'));
								$CI->make->date('Read Date','calendar',sql2Date($today),null,array('class'=>'rOkay'),fa('fa-calendar'));
							$CI->make->eForm();
							
							$CI->make->sDiv(array('id'=>'reads-load','style'=>'margin-top:10px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(8);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->sDivRow();
									$CI->make->sDivCol(1);
									$CI->make->eDivCol();
									$CI->make->sDivCol(2);
										$CI->make->sDiv(array('style'=>'padding-top:50px;'));
										$CI->make->eDiv();
										$buttons = array(
														 "system_sales_rep"	=> "ZREAD",
														 "menu_sales_rep"	=> "Menu Item Sales",
														 "void_sales_rep"	=> "Void Sales",
														 // "daily_sales_rep"	=> "Daily Sales",
														 "hourly_sales_rep"	=> "Hourly Sales",
														);
										foreach ($buttons as $id => $text) {
													$CI->make->button($text,array('ref'=>$id,'title'=>$text,'class'=>'rep-btns btn-block manager-btn-teal'));
										}	
									$CI->make->eDivCol();
									$CI->make->sDivCol(9);
										$CI->make->sBox('solid',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;'));
											$CI->make->sBoxBody(array('class'=>'print-containers','style'=>'padding:0px;position:relative;background-color:#ddd'));
												$CI->make->sDiv(array('style'=>'width:100%;position:absolute;top:0px;'));
													$CI->make->sDivRow();
														$CI->make->sDivCol(10);
															$CI->make->button(fa('fa-print').'Print',array('id'=>'print-paper-btn','class'=>'btn-block load-types-btns manager-btn-green'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(1);
															$CI->make->button(fa('fa-chevron-circle-up fa-lg'),array('id'=>'up-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(1);
															$CI->make->button(fa('fa-chevron-circle-down fa-lg'),array('id'=>'down-paper-btn','class'=>'btn-block load-types-btns manager-btn-red-gray'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
												$CI->make->eDiv();
												$CI->make->sDiv(array('id'=>'print-load','style'=>'padding-top:60px;overflow:auto;'));
													$CI->make->sDiv(array('id'=>'report-view-div','style'=>'position:relavtive;height:450px;width:300px;margin:0 auto;overflow:hidden;'));
													$CI->make->eDiv();
												$CI->make->eDiv();
												
											$CI->make->eBoxBody();
										$CI->make->eBox();
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();	
}	
function mainPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('id'=>'prnt-main','class'=>'div-no-spaces','style'=>'background-color:#015D56;') );
		$CI->make->sDivRow();
				$buttons = array("date-time-sales"	=> fa('fa-clock-o fa-lg fa-fw')."<br> DATE & TIME",
								 "shift-sales"	=> fa('fa-user fa-lg fa-fw')."<br> SHIFTS",
								 "end-day-sales"	=> fa('fa-calendar fa-lg fa-fw')."<br> DAY ENDS",
								);
				foreach ($buttons as $id => $text) {
						$CI->make->sDivCol(4);
							$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block load-types-btns manager-btn-teal double'));
						$CI->make->eDivCol();
				}				 	
		$CI->make->eDivRow();
		$CI->make->sDiv(array('id'=>'prnt-loads','style'=>'border-top:5px solid #428bca') );

		$CI->make->eDiv();
	$CI->make->eDiv();
	return $CI->make->code();
}
?>