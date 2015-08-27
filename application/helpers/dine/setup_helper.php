<?php
//-----------Branch Details-----start-----allyn
function makeDetailsForm($det=array(),$set=array(),$splashes=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			// $CI->make->sBox('primary');
				// $CI->make->sBoxBody();
					$CI->make->sTab();
						$tabs = array(
							fa('fa-info-circle')." Details"=>array('href'=>'#details'),
							fa('fa-cogs')." POS"=>array('href'=>'#setup'),
							fa('fa-image')." Images"=>array('href'=>'#image'),
						);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
							$CI->make->sForm("setup/details_db",array('id'=>'details_form'));
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(6);
										$CI->make->hidden('tax_id',iSetObj($det,'tax_id'));
										$CI->make->input('Code','branch_code',iSetObj($det,'branch_code'),'Type Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
										$CI->make->input('Name','branch_name',iSetObj($det,'branch_name'),'Type Name',array('class'=>'rOkay'));
										$CI->make->textarea('Description','branch_desc',iSetObj($det,'branch_desc'),'Type Description',array('class'=>'rOkay'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(6);
												$CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
													$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												$CI->make->eDiv();
											$CI->make->eDivCol();
											$CI->make->sDivCol(6);
												$CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
													$CI->make->input('Closing Time','store_close',iSetObj($det,'store_close'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												$CI->make->eDiv();
											$CI->make->eDivCol();
										$CI->make->eDivRow();
										// $CI->make->input('TIN','tin',iSetObj($det,'tin'),'TIN',array('class'=>'rOkay'));
										// $CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array());
										// $CI->make->input('Serial #','serial',iSetObj($det,'serial'),'Serial Number',array());
										$CI->make->input('Accreditation #','accrdn',iSetObj($det,'accrdn'),'Accreditation Number',array());
										$CI->make->input('Machine No.','machine_no',iSetObj($det,'machine_no'),'Machine Number',array());
										$CI->make->input('Permit#','permit_no',iSetObj($det,'permit_no'),'Permit Number',array());
										$CI->make->input('Email','email',iSetObj($det,'email'),'Email Address',array());
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->input('Contact No.','contact_no',iSetObj($det,'contact_no'),'Type Contact Number',array());
										$CI->make->input('Delivery No.','delivery_no',iSetObj($det,'delivery_no'),'Type Delivery Number',array());
										$CI->make->textarea('Address','address',iSetObj($det,'address'),'Type Branch Address',array('class'=>'rOkay'));
										$CI->make->sDivRow();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
											$CI->make->input('TIN','tin',iSetObj($det,'tin'),'TIN',array('class'=>'rOkay'));
											$CI->make->eDivCol();
											$CI->make->sDivCol(6);
												// $CI->make->sDiv(array('class'=>'bootstrap-timepicker'));
												// 	$CI->make->input('Opening Time','store_open',iSetObj($det,'store_open'),'',array('class'=>'rOkay timepicker'),null,fa('fa-clock-o'));
												// $CI->make->eDiv();
											// $CI->make->input('BIR #','bir',iSetObj($det,'bir'),'BIR',array());
											$CI->make->input('Serial #','serial',iSetObj($det,'serial'),'Serial Number',array());
											$CI->make->eDivCol();
										$CI->make->eDivRow();
										$CI->make->input('Website','website',iSetObj($det,'website'),'Website',array());
										
										$CI->make->input('RLC Path','rob_path',iSetObj($det,'rob_path'),'RLC PATH',array());
										$CI->make->input('RLC Username','rob_username',iSetObj($det,'rob_username'),'RLC Username',array());
										$CI->make->input('RLC Password','rob_password',iSetObj($det,'rob_password'),'RLC Password',array());
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(12, 'right');
											$CI->make->button(fa('fa-save fa-fw').' Save Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								// $CI->make->sDivRow(array('style'=>'margin:10px;'));
								// 	$CI->make->sDivCol(6);
								// 		$CI->make->currenciesDrop('Currency','currency',iSetObj($det,'currency'),'',array());
								// 	$CI->make->eDivCol();
							$CI->make->eForm();
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'setup','class'=>'tab-pane'));
							$CI->make->sForm("setup/pos_settings_db",array('id'=>'settings_form'));
								$CI->make->H(3,'Printing');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->number('No. of Receipt Prints on Settled','no_of_receipt_print',numInt(iSetObj($set,'no_of_receipt_print')),'',array('class'=>'rOkay'));
										$CI->make->number('No. of Prints of Order Slip on Settled','no_of_order_slip_print',numInt(iSetObj($set,'no_of_order_slip_print')),'',array('class'=>'rOkay'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->input('Kitchen Printer Name','kitchen_printer_name',iSetObj($set,'kitchen_printer_name'),'');
										$CI->make->input('Beverage Printer Name','kitchen_beverage_printer_name',iSetObj($set,'kitchen_beverage_printer_name'),'');
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->number('No. Of Kitchen Prints','kitchen_printer_name_no',iSetObj($set,'kitchen_printer_name_no'),'');
										$CI->make->number('No. Of Beverage Prints','kitchen_beverage_printer_name_no',iSetObj($set,'kitchen_beverage_printer_name_no'),'');
									$CI->make->eDivCol();
									$CI->make->sDivCol(3);
										$CI->make->input('Printer With Open Cashdrawer','open_drawer_printer',iSetObj($set,'open_drawer_printer'),'');
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->H(3,'Add On Charges');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$CI->make->sDivCol(3);
										$CI->make->decimal('Local Tax Percent','local_tax',numInt(iSetObj($set,'local_tax')),'',2,array('class'=>'rOkay'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->H(3,'Controls');
								$CI->make->append('<hr style="margin-top:0px;">');
								$CI->make->sDivRow();
									$ids = explode(',',$set->controls);
									for($i=1;$i<=7;$i++){

										$falser = false;
										foreach ($ids as $value) {

											$text = explode('=>',$value);
											
												if($text[0] == $i){
													$CI->make->sDivCol(3);
														$CI->make->checkbox(strtoupper($text[1]),'chk['.$text[0].']',$text[0]."=>".$text[1],array(),true);
													$CI->make->eDivCol();
													$falser = true;
													break;
												}
											

										}
										if(!$falser){

											if($i == 1){
												$txt = "DINE IN";
											}elseif($i == 2){
												$txt = "DELIVERY";
											}elseif($i == 3){
												$txt = "COUNTER";
											}elseif($i == 4){
												$txt = "RETAIL";
											}elseif($i == 5){
												$txt = "PICKUP";
											}elseif($i == 6){
												$txt = "TAKEOUT";
											}elseif($i == 7){
												$txt = "DRIVE-THRU";
											}

											$CI->make->sDivCol(3);
												$CI->make->checkbox($txt,'chk['.$i.']',$i."=>".strtolower($txt),array());
											$CI->make->eDivCol();
										}
									}
								$CI->make->eDivRow();
								// $CI->make->sDivRow();
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->checkbox('DINE IN','dinein',1,array());
								// 	$CI->make->eDivCol();
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->checkbox('DELIVERY','dinein',2,array());
								// 	$CI->make->eDivCol();
								// $CI->make->eDivRow();
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(12, 'right');
											$CI->make->button(fa('fa-save fa-fw').' Save',array('id'=>'save-pos-btn','class'=>'btn-block'),'primary');
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eForm();
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'image','class'=>'tab-pane'));
							$CI->make->H(3,'Splash Pages');
							$CI->make->append('<hr style="margin-top:0px;">');
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'right');
									$btnMsg = fa('fa-upload').' Upload Image';
									$CI->make->A($btnMsg,'setup/upload_splash_images/',array(
																				'id'=>'upload-splsh-img',
																				'rata-title'=>'Splash Image Upload',
																				'rata-pass'=>'setup/upload_splash_images_db',
																				'rata-form'=>'upload_image_form',
																				'class'=>'btn btn-primary'
																			));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								foreach ($splashes as $res) {
									$CI->make->sDivCol(4);
										$src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
										$CI->make->img($src,array('style'=>'width:100%;margin-bottom:0px;margin-top:10px;','class'=>'thumbnail'));
										$CI->make->A(fa('fa-trash').'Delete','#',array('class'=>'del-spl-btn btn btn-danger btn-block','ref'=>$res->img_id,'style'=>'margin:0px !important;'));
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
				// $CI->make->eBoxBody();
			// $CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function makeImageUploadForm($det=null){
	$CI =& get_instance();
		$CI->make->sForm("setup/upload_splash_images_db",array('id'=>'upload_image_form','enctype'=>'multipart/form-data'));
			$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				$CI->make->sDivCol();
					$CI->make->A(fa('fa-picture-o').' Select an Image','#',array(
															'id'=>'select-img',
															'class'=>'btn btn-primary'
														));
					$CI->make->append('<br>');
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol();
					$thumb = base_url().'img/noimage.png';
					// if(iSetObj($det,'image')  != ""){
					// 	$thumb = base_url().'uploads/'.iSetObj($det,'image');
					// }
					$CI->make->img('',array('class'=>'media-object thumbnail','id'=>'target','style'=>'width:100%;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
//-----------Branch References-----end-----allyn
?>