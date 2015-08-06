<?php
function indexPage($needEod=false,$set){
	$CI =& get_instance();
	$user = $CI->session->userdata('user');
		$CI->make->sDiv(array('id'=>'cashier-panel'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(2,'left',0,array('class'=>'cpanel-left'));
					$CI->make->H(5,'NEW ORDER',array('class'=>'headline text-center','style'=>'margin-bottom:10px;'));
					// $CI->make->button('DINE IN',array('id'=>'dine-in-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('DELIVERY',array('id'=>'delivery-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('COUNTER',array('id'=>'counter-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('RETAIL',array('id'=>'retail-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('PICKUP',array('id'=>'pickup-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('TAKEOUT',array('id'=>'takeout-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('DRIVE-THRU',array('id'=>'drive-thru-btn','class'=>'btn-block cpanel-btn-blue'));
					$ids = explode(',',$set->controls);
					foreach($ids as $value){
						$text = explode('=>',$value);
						if($text[0] == 1){
							$texts='dine-in';
						}else{
							$texts=$text[1];
						}
						$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-blue'));
					}


				$CI->make->eDivCol();
				$CI->make->sDivCol(8);
					$CI->make->sDiv(array('class'=>'cpanel-center'));
						$CI->make->sDivRow(array('class'=>'center-btns'));
							// $CI->make->sDivCol(2);
							// 	$CI->make->button('<span id="terminal_text">'.fa('fa-desktop fa-2x fa-fw').'<br> MY</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'terminal-btn','type'=>'my','btn'=>'terminal'));
							// $CI->make->eDivCol();
							$CI->make->hidden('terminal-btn',TERMINAL_ID,array('type'=>'my'));
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="status_text">'.fa('fa-arrow-up fa-2x fa-fw').'<br> OPEN</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'status-btn','type'=>'open','btn'=>'status'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="types_text">'.fa('fa-book fa-2x fa-fw').'<br> ALL TYPES</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'types-btn','type'=>'all','btn'=>'types'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="day_text">'.fa('fa-clock-o fa-2x fa-fw').'<br> TODAY</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'now-btn','type'=>'now','btn'=>'now'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-search fa-2x fa-fw').'<br> LOOKUP',array('class'=>'btn-block no-raduis cpanel-btn-red double','id'=>'look-btn'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(2);
							// 	$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> FOOD SERVER',array('class'=>'btn-block no-raduis cpanel-btn-red double','id'=>'server-btn'));
							// $CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-refresh fa-2x fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block no-raduis cpanel-btn-orange double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray double'));
							$CI->make->eDivCol();

						$CI->make->eDivRow();
						$CI->make->sDiv(array('class'=>'orders-lists center-loads-div orders-div','style'=>'margin-top:10px;'));
							$CI->make->sDiv(array('class'=>'orders-lists-load'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('style'=>'margin-top:10px;display:none;','id'=>'orders-search','class'=>''));
							$CI->make->append(onScrNumPad('search-order','go-search-order'));
						$CI->make->eDiv();
						$CI->make->sDiv(array('style'=>'margin-top:10px;display:none;','id'=>'server-search','class'=>''));
							
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->userDropSearch("SELECT FOOD SERVER NAME",'user',null,null,array('id'=>'user'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-search fa-2x fa-fw').'<br> SEARCH',array('id'=>'search-server-btn','class'=>'btn-block cpanel-btn-green double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow(array('style'=>'margin-bottom:225px;'));
							$CI->make->eDivRow();
							
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'orders-view-div center-loads-div'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(6);
									$CI->make->sDiv(array('class'=>'order-view-list','ref'=>null));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->sDivRow();
										$buttons = array("recall"	=> fa('fa-search fa-lg fa-fw')." Recall",
														 // "transfer"	=> fa('fa-exchange fa-lg fa-fw')." Transfer Server",
														 "split"	=> fa('fa-arrows-h fa-lg fa-fw')." Split",
														 "combine"	=> fa('fa-compress fa-lg fa-fw')." Combine",
														 "settle"	=> fa('fa-check-square-o fa-lg fa-fw')." Settle",
														 "receipt"	=> fa('fa-print fa-lg fa-fw')." Print Billing",
														 "cash"		=> fa('fa-money fa-lg fa-fw')." Settle Cash",
														 "credit"	=> fa('fa-credit-card fa-lg fa-fw')." Settle Credit"
														 );
										foreach ($buttons as $id => $text) {
											$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-blue'));
											$CI->make->eDivCol();
										}
										$buttons = array("void"		=> fa('fa-ban fa-lg fa-fw')." Void",
														 );
										foreach ($buttons as $id => $text) {
											$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-red'));
											$CI->make->eDivCol();
										}
										$buttons = array(
														 "back-order-list"		=> fa('fa-reply fa-lg fa-fw')." Back");
										foreach ($buttons as $id => $text) {
											$CI->make->sDivCol(12,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-red'));
											$CI->make->eDivCol();
										}
									$CI->make->eDivRow();
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'reasons-div center-loads-div'));
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
									$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red reason-btns double'));
									$CI->make->eDivCol();
								}
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('class'=>'btn-block cpanel-btn-orange cancel-reason-btn double'));
									$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(2,'left',0,array('class'=>'cpanel-right'));
					// $CI->make->H(5,'TRANSACTIONS',array('class'=>'headline text-center','style'=>'margin-bottom:10px;'));
					// $CI->make->button('NEW DELIVERY',array('class'=>'btn-block cpanel-btn-green'));
					$CI->make->button('CUSTOMERS',array('id'=>'customer-btn','class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('NO SALE',array('class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('PAYOUT',array('class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('ADD TIPS',array('class'=>'btn-block cpanel-btn-green'));
					$CI->make->button('GIFT CARDS',array('id'=>'gift-card-btn','class'=>'btn-block cpanel-btn-green'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow(array('class'=>'cpanel-bottom'));
				$CI->make->sDivCol(2);
					// $prev_day = date('Y-m-d',strtotime('-1 day'));

					// $result = $CI->cashier_model->get_latest_read_date(Z_READ);
			  //       $prev_day_trans = $CI->cashier_model->get_trans_sales(null,array('DATE(datetime)'=>$prev_day));
			  //       $latest_date = date('Y-m-d');
			  //       if (!empty($result->maxi)) {
			  //           $latest_date = date('Y-m-d',strtotime($result->maxi.' +1 day'));
			  //       } elseif (empty($result->maxi) && !empty($prev_day_trans)) {
			  //           $latest_date = $prev_day;
			  //       }

					// if ($latest_date != date('Y-m-d'))


					// $checker = $CI->get_zread_data();
					// if (!empty($checker['details']) && date('Y-m-d',strtotime($checker['from'])) != date('Y-m-d'))
					// 	$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> MANAGER <span class="label label-danger"> Z-Read </span>',array('id'=>'manager-btn','class'=>'btn-block eod-not-performed cpanel-btn-red double'));
					// else
						$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> MANAGER',array('id'=>'manager-btn','class'=>'btn-block cpanel-btn-red-gray double'));
					// if ($needEod)
					// 	$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> MANAGER <span class="label label-danger"> Z-Read </span>',array('id'=>'manager-btn','class'=>'btn-block eod-not-performed cpanel-btn-red double'));
					// else
					// 	$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> MANAGER',array('id'=>'manager-btn','class'=>'btn-block cpanel-btn-red-gray double'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					if ($user['role_id'] == 1 || $user['role_id'] == 2)
						$CI->make->button(fa('fa-cogs fa-2x fa-fw').'<br> BACK OFFICE',array('id'=>'back-office-btn','class'=>'btn-block cpanel-btn-red-gray double'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('id'=>'time','class'=>'headline text-center'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->button(fa('fa-inbox fa-2x fa-fw').'<br> Open Drawer',array('id'=>'open-drawer-btn','class'=>'btn-block cpanel-btn-orange double'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->button(fa('fa-clock-o fa-2x fa-fw').'<br> TIME CLOCK',array('id'=>'time-clock-btn','class'=>'btn-block cpanel-btn-red-gray double'));
					// $CI->make->button(fa('fa-power-off fa-2x fa-fw').'<br> LOGOUT',array('id'=>'logout-btn','class'=>'btn-block cpanel-btn-red double'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function counterPage($type=null,$time=null,$loaded=null,$order=array(),$typeCN=array(),$local_tax=0,$kitchen_printer=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				#LEFT
				$CI->make->sDivCol(2,'left',0,array('class'=>'counter-left'));
					$CI->make->button(fa('fa-barcode fa-lg fa-fw').'<br>RETAIL',array('id'=>'retail-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-tags fa-lg fa-fw').'<br> Quantity',array('id'=>'qty-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Discount',array('id'=>'add-discount-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Zero-Rated',array('id'=>'zero-rated-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-tag fa-lg fa-fw').'<br> Add Charges',array('id'=>'charges-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-text-width fa-lg fa-fw').'<br> Add Remarks',array('id'=>'remarks-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-times fa-lg fa-fw').'<br>REMOVE',array('id'=>'remove-btn','class'=>'btn-block counter-btn-red double '.$loaded));
					// $CI->make->button(fa('fa-keyboard-o fa-lg fa-fw').'<br>MISC',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-file fa-lg fa-fw').'<br>RECALL',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-circle-o fa-lg fa-fw').'<br> ORDER TAX EXEMPT',array('id'=>'tax-exempt-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-magnet fa-lg fa-fw').'<br>HOLD ALL',array('id'=>'hold-all-btn','class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-power-off fa-lg fa-fw').'<br>LOGOUT',array('id'=>'logout-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-reply fa-lg fa-fw').'<br>Back',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
				$CI->make->eDivCol();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
						    	$tableN = null;
							    if(isset($typeCN[0]['table_name']))
							    	$tableN = " - ".$typeCN[0]['table_name'];
							    if(isset($order['table_name']) && $order['table_name'] != "")
							    	$tableN = " - ".$order['table_name'];

								$waiter = '';

								if(isset($order['waiter_name'])){
									$waiter_name = trim($order['waiter_name']);
									$display = '';
									if($waiter_name != '')
										$waiter = 'FS: '.$order['waiter_name'];
								}
								$CI->make->H(3,strtoupper($type).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								$CI->make->H(5,$time,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$display='display:none;';
								// $CI->make->H(5,$waiter,array('id'=>'trans-server-txt','class'=>'addon-texts receipt text-center','style'=>'margin-top:5px;'.$display));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->sDiv(array('class'=>'foot-det'));
									$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
									// $CI->make->H(5,'LOCAL TAX: <span id="local-tax-txt">0.00</span>',array('class'=>'receipt text-center'));
									$lt_txt = "";
									if($local_tax > 0){
										$lt_txt = 'LOCAL TAX: <span id="local-tax-txt">0.00</span>';
									}
									$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span> '.$lt_txt,array('class'=>'receipt text-center'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div'));
						$CI->make->sDivRow();
							
							if($kitchen_printer != null){
								
								$CI->make->sDivCol(3);
									$CI->make->button(fa('fa-ban fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'false'));
								$CI->make->eDivCol();
								if(ORDERING_STATION){
									$CI->make->sDivCol(6);
										$CI->make->button(fa('fa-check fa-lg fa-fw').' Send',array('id'=>'send-trans-btn','class'=>'btn-block counter-btn-green double check_fs'));
									$CI->make->eDivCol();
								}
								else{
									$CI->make->sDivCol(6);
										$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double'));
									$CI->make->eDivCol();
								}
								if(ORDERING_STATION){
									$CI->make->sDivCol(3);
										$CI->make->button(fa('fa-print fa-lg fa-fw').'<br>ORDER SLIP',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true'));
									$CI->make->eDivCol();
								}
								else{
									$CI->make->sDivCol(3);
										$CI->make->button(fa('fa-ban fa-lg fa-fw').'<br>ORDER SLIP',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'false'));
									$CI->make->eDivCol();
								}

							}
							else{
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-print fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(8);
									$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double'));
								$CI->make->eDivCol();
							}


						$CI->make->eDivRow();
						$CI->make->sDivRow();
							if(ORDERING_STATION){
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
							}
							else{
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
							}
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(2,'left');
					$CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-cat-scroll-up','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'menu-cat-container'));
					$CI->make->eDiv();
					$CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-cat-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(4,'left',0);
					// $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
							// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'counter-right','style'=>'height:630px;overflow:hidden;'));
						#MENU
						$CI->make->sDiv(array('class'=>'menus-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'items-lists','style'=>'height:521px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#MODS
						$CI->make->sDiv(array('class'=>'mods-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'MODIFIERS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'mods-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#QTY
						$CI->make->sDiv(array('class'=>'qty-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Quantity',array('class'=>'receipt text-center title','style'=>'margin-bottom:20px'));
							$CI->make->sDiv(array('class'=>'qty-lists'));
								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->sDivCol(6);
										$CI->make->button('+1',array('value'=>'1','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('-1',array('value'=>'1','operator'=>'minus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+5',array('value'=>'5','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+10',array('value'=>'10','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x2',array('value'=>'2','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x10',array('value'=>'10','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDiv(array('class'=>'counter-center-btns'));
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->button('Reset',array('value'=>'1','operator'=>'equal','class'=>'btn-block edit-qty-btn counter-btn-red double'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->button('Finished',array('id'=>'qty-btn-done','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#DISCOUNT
						$CI->make->sDiv(array('class'=>'sel-discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'select-discounts-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->H(3,'RATE: % <span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'discounts-lists'));
								 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 $CI->make->sDivCol(12);
								    	$guestN = null;
									    if(isset($typeCN[0]['guest']))
									    	$guestN = $typeCN[0]['guest'];
								 	 	$CI->make->input(null,'disc-guests',$guestN,'Total No. Of Guests',array(),fa('fa-user'));
								 	 $CI->make->eDivCol();
								 $CI->make->eDivRow();
								 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
								 	 $CI->make->sDivCol(4);
								 	 	$CI->make->button('ALL ITEMS',array('ref'=>'all','class'=>'disc-btn-row btn-block counter-btn-teal'));
								 	 $CI->make->eDivCol();
								 	 $CI->make->sDivCol(4);
								 	 	$CI->make->button('EQUALLY DIVIDED',array('ref'=>'equal','class'=>'disc-btn-row btn-block counter-btn-orange'));
								 	 $CI->make->eDivCol();
								 	 $CI->make->sDivCol(4);
								 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
								 	 $CI->make->eDivCol();
								 $CI->make->eDivRow();
								 $CI->make->sForm("",array('id'=>'disc-form'));
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 	$CI->make->hidden('disc-disc-id',null);
									 	 	$CI->make->hidden('disc-disc-rate',null);
									 	 	$CI->make->hidden('disc-disc-code',null);
									 	 	$CI->make->hidden('disc-no-tax',null);
									 	 $CI->make->eDivCol();
									 	 // $CI->make->sDivCol(6);
									 	 	// $CI->make->input(null,'disc-cust-guest',null,'No. Of Guest',array('class'=>'rOkay','ro-msg'=>'Add No. Of Guest for Discount'),fa('fa-male'));
									 	 // $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-bday',null,'MM/DD/YYYY',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	$CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow();
										 $CI->make->sDivCol(12);
										 	$CI->make->sDiv(array('class'=>'disc-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
											$CI->make->eDiv();
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 $CI->make->eForm();
								 // $CI->make->sDivRow(array('style'=>'margin-top:20px;'));
								 // 	 $CI->make->sDivCol(12);
								 // 	 	$CI->make->button(fa('fa fa-plus fa-lg fa-fw').' SELECTED ITEM ONLY',array('ref'=>'item','class'=>'disc-btn-row btn-block counter-btn-green'));
								 // 	 	$CI->make->sUl(array('class'=>'item-disc-list','style'=>'margin-top:10px;'));
								 // 	 	$CI->make->eUl();
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#CHARGES
						$CI->make->sDiv(array('class'=>'charges-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Charges',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'charges-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#WAITER
						$CI->make->sDiv(array('class'=>'waiter-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Food Server',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							 $CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
							 	 	$CI->make->button(fa('fa-times fa-lg fa-fw').' REMOVE FOOD SERVER',array('id'=>'remove-waiter-btn','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
							 $CI->make->eDivRow();
							$CI->make->sDiv(array('class'=>'waiters-lists','style'=>"overflow:auto;height:460px;"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#REMARKS
						$CI->make->sDiv(array('class'=>'remarks-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'REMARKS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
								 	 $CI->make->sForm("",array('id'=>'remarks-form'));
								 	 	$CI->make->textarea(null,'line-remarks',null,null,array('class'=>'rOkay','ro-msg'=>'Add Remarks'));
								 	 	$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'add-remark-btn','class'=>'btn-block counter-btn-green'));
								 	 $CI->make->eForm();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						#RETAIL
						$CI->make->sDiv(array('class'=>'retail-div loads-div','style'=>'display:none'));
							$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							 	 $CI->make->sDivCol();
							 	 	$CI->make->sDiv(array('id'=>'scan-div'));
								 	 	$btn = $CI->make->button("SCAN CODE",array('id'=>'go-scan-code','return'=>true,'class'=>'btn-block counter-btn-orange'));
								 	 	$CI->make->pwdWithBtn(null,'scan-code',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),$btn);
								 	$CI->make->eDiv();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH ITEM",array('id'=>'go-search-item','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-item',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->append('<hr>');
					 	 	$CI->make->H(4,null,array('class'=>'retail-title text-center title','style'=>'margin-bottom:10px;display:none;'));
					 	 	$CI->make->sDiv(array('class'=>'retail-loads-div listings','style'=>'height:400px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
					// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();

			$CI->make->sDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function settlePage($ord=null,$det=null,$discs=null,$totals=null,$charges=null){
	$CI =& get_instance();
		
		$CI->make->sDiv(array('id'=>'settle','sales'=>$ord['sales_id'],'type'=>$ord['type'],'balance'=>num($ord['balance'])));
			$CI->make->sDivRow();
				$CI->make->sDivCol(5,'left',0,array('class'=>'settle-left'));
					$CI->make->sBox('default',array('class'=>'box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-red'));
							$CI->make->boxTitle('BALANCE DUE');
							$CI->make->boxTitle('PHP <span id="balance-due-txt">'.num($ord['balance']).'</span>',array('class'=>'pull-right','style'=>'margin-right:10px;'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody();
							$CI->make->sDiv(array('class'=>'order-view-list'));
						        $waiter = "";
						        $waiter_name = trim($ord['waiter_username']);
						        if($waiter_name != "")
						        	$waiter = 'FS: '.$ord['waiter_username'];
								$CI->make->H(3,strtoupper($ord['type'])." #".$ord['sales_id'],array('class'=>'receipt text-center'));
						        $CI->make->H(5,sql2DateTime($ord['datetime'])." ".$waiter,array('class'=>'receipt text-center'));
						            // $CI->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
						        $CI->make->append('<hr>');
						        $CI->make->sDiv(array('class'=>'body'));
						            $CI->make->sUl();
						                $total = 0;
						                foreach ($det as $menu_id => $opt) {
						                    $qty = $CI->make->span($opt['qty'],array('class'=>'qty','return'=>true));
						                    $name = $CI->make->span($opt['name'],array('class'=>'name','return'=>true));
						                    $cost = $CI->make->span($opt['price'],array('class'=>'cost','return'=>true));
						                    $price = $opt['price'];
						                    $CI->make->li($qty." ".$name." ".$cost);
						                    if($opt['remarks'] != ""){
						                        $remarks = $CI->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
						                        $CI->make->li($remarks);
						                    }
						                    if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
						                        foreach ($opt['modifiers'] as $mod_id => $mod) {
						                            $name = $CI->make->span($mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
						                            $cost = "";
						                            if($mod['price'] > 0 )
						                                $cost = $CI->make->span($mod['price'],array('class'=>'cost','return'=>true));
						                            $CI->make->li($name." ".$cost);
						                            $price += $mod['price'];
						                        }
						                    }
						                    $total += $opt['qty'] * $price  ;
						                }
						                if(count($charges) > 0){
						                    foreach ($charges as $charge_id => $ch) {
						                        $qty = $CI->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
						                        $name = $CI->make->span($ch['name'],array('class'=>'name','return'=>true));
						                        $tx = $ch['amount'];
						                        if($ch['absolute'] == 0)
						                            $tx = $ch['amount']."%";
						                        $cost = $CI->make->span($tx,array('class'=>'cost','return'=>true));
						                        $CI->make->li($qty." ".$name." ".$cost);
						                    }
						                }
						            $CI->make->eUl();
						        $CI->make->eDiv();
						        $CI->make->append('<hr>');
						        $CI->make->H(3,'TOTAL: PHP '.num($totals['total']),array('class'=>'receipt text-center'));
						        $lt_txt = "";
						        if($totals['local_tax'] > 0){
						        	$lt_txt = " LOCAL TAX: ".num($totals['local_tax']);
						        }
						        $CI->make->H(4,'DISCOUNT: '.num($totals['discount']).$lt_txt,array('class'=>'receipt text-center'));
							$CI->make->eDiv();
						$CI->make->eBoxBody();
						$CI->make->sBoxFoot();
							$CI->make->sDivRow();
								if($ord['balance'] == 0){
									$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-bars fa-lg fa-fw').' Transactions',array('id'=>'transactions-btn','class'=>'btn-block settle-btn double','disabled'=>'true'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-reply fa-lg fa-fw').' Recall',array('id'=>'recall-btn','type'=>$ord['type'],'sale'=>$ord['sales_id'],'class'=>'btn-block settle-btn-orange double','disabled'=>'true'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','type'=>$ord['type'],'class'=>'btn-block settle-btn-red double'));
									$CI->make->eDivCol();
								}else{
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-bars fa-lg fa-fw').' Transactions',array('id'=>'transactions-btn','class'=>'btn-block settle-btn double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-reply fa-lg fa-fw').' Recall',array('id'=>'recall-btn','type'=>$ord['type'],'sale'=>$ord['sales_id'],'class'=>'btn-block settle-btn-orange double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','type'=>$ord['type'],'class'=>'btn-block settle-btn-red double'));
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
						$CI->make->eBoxFoot();
					$CI->make->eBox();
				$CI->make->eDivCol();
				$CI->make->sDivCol(7,'left',0,array('class'=>'settle-right'));

					$CI->make->sBox('default',array('class'=>'loads-div select-payment-div box-solid bg-dark-green'));
						$CI->make->sBoxHead(array('class'=>'bg-dark-green'));
							$CI->make->boxTitle('&nbsp;');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-dark-green'));
								$buttons = array("cash"	=> fa('fa-money fa-lg fa-fw')."<br> CASH",
												 "credit-card"	=> fa('fa-credit-card fa-lg fa-fw')."<br> CREDIT CARD",
												 "debit-card"	=> fa('fa-credit-card fa-lg fa-fw')."<br> DEBIT CARD",
												 "gift-cheque"	=> fa('fa-gift fa-lg fa-fw')."<br> GIFT CHEQUE",
												 "coupon"	=> fa('fa-tags fa-lg fa-fw')."<br> Coupon",
												 // "sign-chit"	=> fa('fa-tag fa-lg fa-fw')."<br> SIGN CHIT",
												 // "check"	=> fa('fa-check-square-o fa-lg fa-fw')."<br> CHECK"
												 );
								$CI->make->sDivRow();
									// $CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
									// 	$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
									// 	if(count($discounts) > 0 ){
									// 		foreach ($discounts as $res) {
									// 			$CI->make->button(strtoupper($res->disc_code)." ".strtoupper($res->disc_name),array('ref'=>$res->disc_id,'opt'=>$res->disc_rate."-".$res->disc_code,'class'=>'disc-btns btn-block settle-btn-green double'));
									// 		}
									// 	}
									// $CI->make->eDivCol();
										$CI->make->H(3,'SELECT PAYMENT METHOD',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
										foreach ($buttons as $id => $text) {
											$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block settle-btn-green double'));
											$CI->make->eDivCol();
										}
								$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div cash-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' CASH PAYMENT');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-red-white'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(3);
									$CI->make->sDiv(array('class'=>'shorcut-btns'));
										$buttons = array(
													 "5"	=> 'PHP 5',
													 "10"	=> 'PHP 10',
													 "20"	=> 'PHP 20',
													 "50"	=> 'PHP 50',
													 "100"	=> 'PHP 100',
													 "200"	=> 'PHP 200',
													 "500"	=> 'PHP 500',
													 "1000"	=> 'PHP 1000'
													 );
										$CI->make->sDivRow(array('style'=>'margin-top:10px;margin-left:10px;'));
											foreach ($buttons as $id => $text) {
													$CI->make->sDivCol(12,'left',0);
														$CI->make->button($text,array('val'=>$id,'class'=>'amounts-btn btn-block settle-btn-red-gray'));
													$CI->make->eDivCol();
											}
										$CI->make->eDivRow();
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(9);
									$CI->make->append(onScrNumDotPad('cash-input','cash-enter-btn'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
						$CI->make->sBoxFoot();
							$CI->make->sDivRow();
								$CI->make->sDivCol(4,'left');
									$CI->make->button('Exact Amount',array('id'=>'cash-exact-btn','amount'=>num($ord['balance']),'class'=>'btn-block settle-btn double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left');
									$CI->make->button('Next Amount',array('id'=>'cash-next-btn','amount'=>num(round($ord['balance'])),'class'=>'btn-block settle-btn-red-gray double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-reply fa-lg fa-fw').' Change Method',array('id'=>'cancel-cash-btn','class'=>'btn-block settle-btn-red double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxFoot();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div debit-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' DEBIT PAYMENT');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDivCol(6);
									$CI->make->input('Card #','debit-card-num','','',array('maxlength'=>'30',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','debit-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Approval Code','debit-app-code','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-debit-target',
										'#debit-card-num',
										'debit-enter-btn',
										'cancel-debit-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div credit-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' CREDIT PAYMENT');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$buttons = array(
									"Master Card"	=> fa('fa-cc-mastercard fa-2x')."<br/>Master Card",
									"VISA"	=> fa('fa-cc-visa fa-2x')."<br/>VISA",
									"AmEx"	=> fa('fa-cc-amex fa-2x')."<br/>American Express",
									"Discover"	=> fa('fa-cc-discover fa-2x')."<br/>Discover",
								);
								foreach ($buttons as $id => $text) {
									$CI->make->sDivCol(3,'left',0,array('style'=>'padding:0;margin:0'));
										$CI->make->button($text,array('value'=>$id,'class'=>'credit-type-btn double settle-btn-teal btn-block'));
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->sDivRow(array('style'=>'margin:auto 0;padding:10px 0 8px;'));
								$CI->make->sDivCol(6,'left');
									$CI->make->hidden('credit-type-hidden','Master Card');
									$CI->make->input('Card #','credit-card-num','','',array('maxlength'=>'30',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Approval Code','credit-app-code','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','credit-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6,'left');
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-credit-target',
										'#credit-card-num',
										'credit-enter-btn',
										'cancel-credit-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div gc-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' GIFT CHEQUE');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(6);
									$CI->make->hidden('hid-gc-id');
									$CI->make->input('Gift Cheque code','gc-code','','',array(
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','gc-amount','','',array('readonly'=>'readonly',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-gc-target',
										'#gc-code',
										'gc-enter-btn',
										'cancel-gc-btn',
										'Change method',false));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div after-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-dark-green'));
							$CI->make->boxTitle('&nbsp;');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-dark-green'));
							$CI->make->sDiv(array('class'=>'body'));
								$CI->make->H(3,'AMOUNT TENDERED: PHP '.strong('<span id="amount-tendered-txt"></span>'),array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
								$CI->make->H(3,'CHANGE DUE: PHP '.strong('<span id="change-due-txt"></span>'),array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
							$CI->make->eDiv();
							$CI->make->sDivRow();
								$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-plus fa-lg fa-fw').' Additonal Payment',array('id'=>'add-payment-btn','class'=>'btn-block settle-btn-teal double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-print fa-lg fa-fw').' Print Receipt',array('id'=>'print-btn','class'=>'btn-block settle-btn-orange double','ref'=>$ord['sales_id']));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-check fa-lg fa-fw').' Finished',array('id'=>'finished-btn','class'=>'btn-block settle-btn-green double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div transactions-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-dark-green'));
							$CI->make->boxTitle('Transactions');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-red-white'));
							$CI->make->sDiv(array('class'=>'body'));

							$CI->make->eDiv();
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'left');
									$CI->make->button(fa('fa-times fa-lg fa-fw').' Close',array('id'=>'trsansactions-close-btn','class'=>'btn-block settle-btn-orange double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div coupon-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle('COUPON');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(6);
									$CI->make->hidden('hid-coupon-id');
									$CI->make->input('Coupon code','coupon-code','','',array(
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','coupon-amount','','',array('readonly'=>'readonly',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-coupon-target',
										'#coupon-code',
										'coupon-enter-btn',
										'cancel-coupon-btn',
										'Change method',false));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div sign-chit-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle('Sign Chit');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->H(3,'Enter Manager PIN',array('style'=>'text-align:center;margin:0px;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(12);
									$pad = onScrNumPwdPad('manager-call-pin-login','manager-submit-btn');
									$CI->make->append($pad);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();


				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function splitPage($type=null,$time=null,$sales_id=null,$ord=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter','sale'=>$sales_id));
			$CI->make->sDivRow();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;margin-top:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'bg-red','style'=>'padding:15px;'));
								$CI->make->H(5,'SPLIT ORDER',array('class'=>'headline text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
								$CI->make->H(3,strtoupper($type),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								$CI->make->H(5,$time,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$waiter_name = trim($ord['waiter_name']);
						        if($waiter_name != "")
						            $CI->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
								$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span>',array('class'=>'receipt text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(8,'left',0);
					$CI->make->sDiv(array('class'=>'counter-split-right','ref'=>''));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4);
								$CI->make->button(fa('fa-flask fa-lg fa-fw').'<br> Item Split',array('id'=>'select-items-btn','ref'=>'select-items','class'=>'split-bys btn-block counter-btn-red-gray double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(4);
								$CI->make->button(fa('fa-bars fa-lg fa-fw').'<br> Number of Guest',array('id'=>'even-split-btn','ref'=>'even-split','class'=>'split-bys btn-block counter-btn-red-gray double'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->button(fa('fa-users fa-lg fa-fw').'<br> Split By Guest',array('id'=>'split-by-guest-btn','ref'=>'split-by-guest','class'=>'split-bys btn-block counter-btn-red-gray double'));
							// $CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-save fa-lg fa-fw').'<br> Save',array('id'=>'save-split-btn','class'=>'btn-block counter-btn-green double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-retweet fa-lg fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block counter-btn-orange double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDiv(array('class'=>'actions-div'));
							#SPLIT BY ITEMS
							$CI->make->sDiv(array('class'=>'select-items-div loads-div','style'=>'display:none;'));
								$CI->make->sDivRow();
									$CI->make->sDivCol(4,'left',0,array('id'=>'add-btn-div'));
										$CI->make->sDiv(array('style'=>'margin:50px;'));
											$CI->make->button(fa('fa-plus fa-lg fa-fw').'<br> Add Partition',array('id'=>'add-sel-block-btn','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDiv();
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'even-split-div loads-div','style'=>'display:none;'));
								$CI->make->sDivRow(array('style'=>'margin-top:20px;'));
									$CI->make->sDivCol(4,'left');
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'left');
										$CI->make->H(1,'2',array('style'=>'margin-top:25px;font-size:78px;','id'=>'even-spit-num'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'left');
										$CI->make->button(fa('fa-caret-square-o-up fa-3x fa-fw'),array('id'=>'even-up-btn','num'=>'up','class'=>'btn-block counter-btn-red-gray double'));
										$CI->make->button(fa('fa-caret-square-o-down fa-3x fa-fw'),array('id'=>'even-down-btn','num'=>'down','class'=>'btn-block counter-btn-red-gray double'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function combinePage($type=null,$time=null,$ord=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
								$CI->make->H(3,strtoupper($type),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								$CI->make->H(5,$time,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$waiter_name = trim($ord['waiter_name']);
						        if($waiter_name != "")
						            $CI->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body body-taller'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
								$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span>',array('class'=>'receipt text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(8,'left',0,array('style'=>'margin-top:10px;'));
					$CI->make->sDiv(array('class'=>'counter-combine-right'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(2,'left',0,array('style'=>'margin-top:70px;'));
								$CI->make->sDiv(array('class'=>'type-container','style'=>'margin-right:10px;'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
							$CI->make->sDivCol(10,'left');
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('class'=>'orders-list-combine-div'));
												$CI->make->sDivRow();
													$CI->make->sDivCol(5);
														$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> MY ORDERS',array('ref'=>'my','class'=>'my-all-btns btn-block counter-btn-red-gray double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(5);
														$CI->make->button(fa('fa-users fa-lg fa-fw').'<br> ALL ORDERS',array('id'=>'all','class'=>'my-all-btns btn-block counter-btn-red-gray double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(2);
														$CI->make->button(fa('fa-refresh fa-lg fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block counter-btn-orange double'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
												$CI->make->sDivRow();
													$CI->make->sDivCol(12,'left',0,array('class'=>'orders-list-combine','terminal'=>'my','types'=>'all'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('class'=>'orders-to-combine-div'));
												$CI->make->sDivRow();
													$CI->make->sDivCol(8);
														$CI->make->button(fa('fa-compress fa-lg fa-fw').'<br> GO COMBINE',array('id'=>'combine-btn','class'=>'btn-block counter-btn-green double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(4);
														$CI->make->button(fa('fa-times fa-lg fa-fw').'<br> CLEAR',array('id'=>'clear-btn','class'=>'btn-block counter-btn-red double'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
												$CI->make->sDivRow();
													$CI->make->sDivCol(12,'left',0,array('class'=>'orders-to-combine'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
										$CI->make->eDivCol();
									$CI->make->eDivRow();
							$CI->make->eDivCol();
						$CI->make->eDivRow();

					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				// $CI->make->sDivCol(2,'left',0,array('style'=>'margin-top:10px;'));
				// 	$CI->make->sDiv(array('class'=>'type-container','style'=>'margin-right:10px;'));
				// 	$CI->make->eDiv();
				// $CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function tablesPage(){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));

				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'SELECT A TABLE',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
				$CI->make->sDiv(array('id'=>'image-con'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'no-guest-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'No. Of Guest',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->append(onScrNumDotPad('guest-input','guest-enter-btn'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'occupied-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'<span id="occ-num"></span> Is In Use',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-occ-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('class'=>'bg-orange'));
								$CI->make->H(3,'Table is currently in use. Choose from the following options to continue.',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-search fa-lg fa-fw').' RECALL',array('id'=>'exit-btn','class'=>'btn-block tables-btn-red double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->button(fa('fa-file fa-lg fa-fw').' Start New',array('id'=>'start-new-btn','class'=>'btn-block tables-btn-green double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-check-square-o fa-lg fa-fw').' Settle',array('id'=>'exit-btn','class'=>'btn-block tables-btn-orange double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDiv(array('class'=>'occ-orders-div'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function deliveryPage($det=null,$type='delivery'){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'Enter Customer Information',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();

				$CI->make->sDiv(array('style'=>'margin:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						$CI->make->sBox('default',array('class'=>'box-solid'));
							$CI->make->sBoxBody();
								$CI->make->sDiv(array('style'=>'height:250px;'));
									$CI->make->input(null,'search-customer',null,'Search number or Customer Name',array(),fa('fa-search'));
									$CI->make->sDiv(array('class'=>'listings'));
										$CI->make->sUl(array('id'=>'cust-search-list'));
										$CI->make->eUl();
									$CI->make->eDiv();
								$CI->make->eDiv();
							$CI->make->eBoxBody();
						$CI->make->eBox();
					$CI->make->eDivCol();
					$CI->make->sDivCol(8);
						$CI->make->sBox('default',array('class'=>'box-solid'));
							$CI->make->sBoxBody();
								$CI->make->sDiv(array('class'=>'cust-form'));
									$CI->make->sForm('customers/customer_details_db/true',array('id'=>'customer-form'));
									$CI->make->hidden('trans_type',$type);
									$CI->make->sDivRow();
										$CI->make->sDivCol(3);
											$CI->make->hidden('cust_id',iSetObj($det,'cust_id'));
											$CI->make->input('First Name','fname',iSetObj($det,'fname'),'Type First Name',array('class'=>'rOkay key-ins'));
											$CI->make->input('Phone','phone',iSetObj($det,'phone'),'Type Phone No.',array('class'=>'rOkay key-ins'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Middle Name','mname',iSetObj($det,'mname'),'Type Middle Name',array());
											$CI->make->input('Email Address','email',iSetObj($det,'email'),'Type Email Address',array('class'=>'rOkay key-ins'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Last Name','lname',iSetObj($det,'lname'),'Type Last Name',array('class'=>'rOkay key-ins'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Suffix','suffix',iSetObj($det,'suffix'),'Type Suffix',array('class'=>' key-ins'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
									$CI->make->sDivRow();
										$CI->make->sDivCol(3);
											$CI->make->input('Street No.','street_no',iSetObj($det,'street_no'),'Type Street No.',array('class'=>'rOkay key-ins'));
											$CI->make->input('Zip Code','zip',iSetObj($det,'zip'),'Type Zip Code',array('class'=>'rOkay key-ins'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Street Address','street_address',iSetObj($det,'street_address'),'Type Street Address',array('class'=>'rOkay key-ins'));
											$CI->make->sDiv(array('style'=>'margin:10px;'));
												$CI->make->button('Continue',array('id'=>'continue-btn','class'=>'btn-block tables-btn-green','style'=>'margin-top:18px;'),'primary');
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('City','city',iSetObj($det,'city'),'Type City',array('class'=>'rOkay key-ins'));
											$CI->make->sDiv(array('style'=>'margin:10px;'));
												$CI->make->button('Clear',array('id'=>'clear-btn','class'=>'btn-block tables-btn-red','style'=>'margin-top:18px;'),'primary');
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Region','region',iSetObj($det,'region'),'Type Region',array('class'=>'rOkay key-ins'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eBoxBody();
						$CI->make->eBox();
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->eDiv();

			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}
function tableTransfer($tables=array()){
	$CI =& get_instance();
	$CI->make->sDiv(array('id'=>'cashier-panel','style'=>'margin-top:30px;'));
		$CI->make->hidden('to-table',null);
		$CI->make->sDivRow();
		ksort($tables);
			foreach ($tables as $id => $opt) {
				$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
					$CI->make->button($opt['name'],array('ref'=>$id,'class'=>'btn-block cpanel-btn-red reason-btns double'));
				$CI->make->eDivCol();
			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
?>