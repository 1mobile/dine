<?php
function drawerMain($overAllTotal=0){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'manager-dayend'));
			$CI->make->sDiv(array('class'=>'manager-dayend-center'));
				$CI->make->sBox('default',array('class'=>'box-solid'));
					$CI->make->sBoxBody(array(
						'style'=>'background-color:#015D56;min-height:600px;padding:0 0 10px 0'));
						$buttons = array(
										 "deposit"	=> fa('fa-download fa-lg fa-fw')."<br> Cash Deposit",
										 "withdraw"	=> fa('fa-upload fa-lg fa-fw')."<br> Cash Withdraw",
										 "cash-count"	=> fa('fa-keyboard-o fa-lg fa-fw')."<br> Cash Count",
										 );
						$CI->make->sDivRow(array('style'=>'margin:0;'));
						$CI->make->sDivCol(2,'left',0,array("style"=>'margin-bottom:10px;padding:0px;'));
								$CI->make->button(fa('fa-clock-o fa-lg fa-fw')."<br> Current Shift",array('id'=>'curr-shift-btn','class'=>'btn-block manager-btn-green double'));
						$CI->make->eDivCol();
						foreach ($buttons as $id => $text) {
							$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;padding:0px;'));
								$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
							$CI->make->eDivCol();
						}
						$CI->make->sDivCol(1,'left',0,array("style"=>'margin-bottom:10px;padding:0px;'));
								$CI->make->button(fa('fa-inbox fa-lg fa-fw')."<br> Open Drawer",array('id'=>'open-drawer-btn','class'=>'btn-block manager-btn-orange double'));
						$CI->make->eDivCol();
						// $CI->make->sDivCol(1,'left',0,array("style"=>'margin-bottom:10px;padding:0px;'));
						// 		$CI->make->button(fa('fa-book fa-lg fa-fw')."<br>Transactions",array('id'=>'history-btn','class'=>'btn-block manager-btn-red double'));
						// $CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDiv(array('id'=>'curr-shift-div','class'=>'draws-div','style'=>'display:none;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(2);
								$CI->make->eDivCol();
								$CI->make->sDivCol(8,'left');
									$CI->make->H(5,'Current Shift',array('class'=>'headline text-center','style'=>'margin-top:10px;margin-bottom:10px;'));
									$CI->make->sDiv(array('id'=>'curr-shift-list','class'=>"listings",'style'=>'height:180px;background-color:#fff;margin-right:10px;margin-left:20px;height:460px;'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('id'=>'deposit-div','class'=>'draws-div','style'=>'display:none;'));
							$CI->make->sDivRow();
									$CI->make->sDivCol(8,'left');
										$CI->make->H(5,'Current Shift Cash Deposits',array('class'=>'headline text-center','style'=>'margin-top:10px;margin-bottom:10px;'));
										$CI->make->sDiv(array('id'=>'deposit-list','class'=>"listings",'style'=>'height:180px;background-color:#fff;margin-right:10px;margin-left:20px;height:460px;'));
										$CI->make->eDiv();
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left',0,array('style'=>""));
										$CI->make->H(5,'Deposit Amount',array('class'=>'headline text-center','style'=>'margin-top:10px'));
										$CI->make->append(onScrNumDotPad('deposit-input','deposit-submit-btn'));
									$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('id'=>'withdraw-div','class'=>'draws-div','style'=>'display:none;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(8,'left');
									$CI->make->H(5,'Current Shift Cash Withdrawals',array('class'=>'headline text-center','style'=>'margin-top:10px;margin-bottom:10px;'));
									$CI->make->sDiv(array('id'=>'withdraw-list','class'=>"listings",'style'=>'height:180px;background-color:#fff;margin-right:10px;margin-left:20px;height:460px;'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left',0,array('style'=>""));
									$CI->make->H(5,'Withdraw Amount',array('class'=>'headline text-center','style'=>'margin-top:10px'));
									$CI->make->append(onScrNumDotPad('withdraw-input','withdraw-submit-btn'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('id'=>'cash-count-div','class'=>'draws-div','style'=>'display:none;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(8,'left',0,array('style'=>'margin-right:0px;padding-right:0px;'));
										$CI->make->sDiv(array('style'=>'margin-left:10px;'));
											
											$CI->make->sDivRow(array('style'=>'margin:0;'));
												$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
													$CI->make->hidden('overall-total',$overAllTotal);
													$CI->make->H(3,'DRAWER AMOUNT: <span class="drawer-amount">'.num($overAllTotal).'</span>',array('class'=>'headline','style'=>'margin-bottom:10px;font-size:18px;'));
												$CI->make->eDivCol();
												$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
													$CI->make->H(3,'DRAWER COUNT AMOUNT: <span class="drawer-count-amount">0.00</span>',array('class'=>'headline','style'=>'margin-bottom:10px;font-size:18px;'));
												$CI->make->eDivCol();
											$CI->make->eDivRow();
											$CI->make->sDiv(array('id'=>'count-tbl-div','class'=>'counts-div'));
												$CI->make->sDivRow(array('style'=>'margin:0;margin-top:0px;'));
													$buttons = array(
														 "credit"	=> fa('fa-credit-card fa-lg fa-fw')." Credit Card<br><span class='amt'>0.00</span>",
														 "debit"	=> fa('fa-credit-card fa-lg fa-fw')." Debit Card<br><span class='amt'>0.00</span>",
														 "gift"		=> fa('fa-gift fa-lg fa-fw')." Gift Cards<br><span class='amt'>0.00</span>",
														 "coupon"	=> fa('fa-gift fa-lg fa-fw')." Coupon<br><span class='amt'>0.00</span>",
														 "smac"	=> fa('fa-credit-card fa-lg fa-fw')." SMAC<br><span class='amt'>0.00</span>",
														 "eplus"	=> fa('fa-credit-card fa-lg fa-fw')." E-Plus<br><span class='amt'>0.00</span>",
														 // "chit"		=> fa('fa-tag fa-lg fa-fw')." Sign Chit<br><span class='amt'>0.00</span>",
														 // "cash"		=> fa('fa-money fa-lg fa-fw')." Cash <br> <span class='amt'>0.00</span>",
														 // "debit"	=> fa('fa-credit-card fa-lg fa-fw')." Debit Card<br><span class='amt'>0.00</span>",
													);
													$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
														$CI->make->sDivRow();
															$CI->make->sDivCol(8,'left',0,array('style'=>'padding-right:0px;'));
																$CI->make->button(fa('fa-money fa-lg fa-fw')." Cash <br> <span class='amt'>0.00</span>",array('ref'=>'cash','class'=>'cash-only count-type-btn btn-block manager-btn-teal double'));
															$CI->make->eDivCol();
															$CI->make->sDivCol(4,'left',0,array('style'=>'padding-left:0px;'));
																$CI->make->button(fa('fa-bars fa-lg fa-fw'),array('ref'=>'cash','class'=>'count-type-btn btn-block manager-btn-orange double'));
															$CI->make->eDivCol();
														$CI->make->eDivRow();
														$CI->make->sDiv(array('id'=>'cash-list','class'=>'listings','style'=>'height:280px;background-color:#fff;border:solid 1px #ddd'));
														$CI->make->eDiv();
													$CI->make->eDivCol();
													foreach ($buttons as $id => $text) {
														$CI->make->sDivCol(3,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
															$CI->make->button($text,array('ref'=>$id,'class'=>'count-type-btn btn-block manager-btn-teal double'));
															$CI->make->sDiv(array('id'=>$id.'-list','class'=>'listings','style'=>'height:280px;background-color:#fff;border:solid 1px #ddd'));
															$CI->make->eDiv();
														$CI->make->eDivCol();
													}


												$CI->make->eDivRow();
												$CI->make->sDivRow(array('style'=>'margin:0;margin-top:10px;'));
													$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-top:0px;'));
														$CI->make->button(fa('fa-print fa-lg fa-fw')." SAVE",array('class'=>'save-count-btn btn-block manager-btn-green double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(6,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
														$CI->make->button(fa('fa-print fa-lg fa-fw')." SAVE AND PRINT",array('class'=>'save-count-btn count-print btn-block manager-btn-green double'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
											$CI->make->sDiv(array('id'=>'count-cash-div','class'=>'counts-div','style'=>'display:none;'));
												$CI->make->sDivRow(array('style'=>'margin:0;'));
													$CI->make->sDivCol(12,'left',0,array("style"=>'padding:0px;margin-top:0px;'));
														$CI->make->sDiv(array("style"=>'margin-right:10px;margin-left:20px;'));
														$CI->make->sDivRow(array('style'=>'margin:0;margin-top:10px;'));
															$CI->make->sDivCol(8,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
																$CI->make->button(fa('fa-reply fa-lg fa-fw')." GO BACK",array('id'=>'cash-go-back-btn','class'=>'btn-block manager-btn-red'));
															$CI->make->eDivCol();
															$CI->make->sDivCol(2,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
																$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray'));
															$CI->make->eDivCol();
															$CI->make->sDivCol(2,'left',0,array("style"=>'padding:0px;margin-right:0px;'));
																$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray'));
															$CI->make->eDivCol();
														$CI->make->eDivRow();
														$CI->make->eDiv();
														$CI->make->sDiv(array('id'=>'deno-div','class'=>"listings",'style'=>'height:180px;background-color:#fff;margin-right:10px;margin-left:20px;height:460px;'));
														$CI->make->eDiv();
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
											
										$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left',0,array('style'=>""));
											$CI->make->H(3,'CREDIT AMOUNT',array('id'=>'amt-label','class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:18px;'));
											$CI->make->input(null,'count-input','','',array('class'=>'count-inputs','maxlength'=>'30',
												'style'=>'
													width:323px;
													height:45px;
													font-size:23px;
													font-weight:bold;
													text-align:right;
													border:none;
													border-radius:5px !important;
													box-shadow:none;
													margin:0 auto;
													',
												)
											);
											$CI->make->H(3,'REFERENCE #',array('class'=>'headline text-center refy','style'=>'margin-bottom:10px;font-size:18px;display:none;'));
											$CI->make->input(null,'ref-input','','',array('disabled'=>'disabled','class'=>'count-inputs refy','maxlength'=>'30',
												'style'=>'
													width:323px;
													height:45px;
													font-size:23px;
													font-weight:bold;
													text-align:right;
													border:none;
													border-radius:5px !important;
													box-shadow:none;
													margin:0 auto;
													display:none;
													',
												)
											);
									$CI->make->append(onScrNumOnlyTarget('count-key-tbl','#count-input','count-btn'));

								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}