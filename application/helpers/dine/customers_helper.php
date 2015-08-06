<?php
function customers_display($list = array())
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							$CI->make->A(fa('fa-plus').' Add New Customer',base_url().'customers/cust_setup',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								// 'Code'=>'',
								'Name'=>'',
								'Address'=>'',
								'Email'=>'',
								'Phone'=>'',
								'Is Tax Exempted?'=>'',
								'Is Inactive?'=>'',
								''=>array('width'=>'10%','align'=>'right')
							);
							$rows = array();
							foreach ($list as $val) {
								$link = "";
								$link .= $CI->make->A(fa('fa-pencil fa-lg fa-fw'),base_url().'customers/cust_setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->lname.'", "'.$val->fname.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								$rows[] = array(
									// $val->code,
									$val->lname.', '.$val->fname.' '.$val->mname.' '.$val->suffix,
									// $val->street_no.' '.$val->street_address.', '.$val->city.', '.$val->region.' '.$val->zip,
									$val->street_no.' '.$val->street_address.', '.$val->city.', '.$val->region.' '.$val->zip,
									$val->email,
									$val->phone,
									($val->tax_exempt == 0 ? 'No' : 'Yes'),
									($val->inactive == 0 ? 'No' : 'Yes'),
									$link
								);
							}
							$CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function customers_form_container($cust_id)
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->hidden('cust_idx',$cust_id);
		$CI->make->sDivCol(12);
			$CI->make->sTab();
				$tabs = array(
					fa('fa-info-circle')." General Details" => array('href'=>'#details','class'=>'tab_link','load'=>'customers/customer_load','id'=>'details_link'),
				);
				$CI->make->tabHead($tabs,null,array());
				$CI->make->sTabBody();
					$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
					$CI->make->eTabPane();
				$CI->make->eTabBody();
			$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function customers_details_form($det, $cust_id)
{
	$CI =& get_instance();

	$CI->make->sForm("customers/customer_details_db",array('id'=>'customer_details_form'));
		if (!empty($cust_id)) {
			$CI->make->hidden('cust_id',$cust_id);
		}

		$CI->make->sDivRow(array('style'=>'margin:10px; padding-top:10px;'));
		// $CI->make->sDivRow(array("style"=>"height: 400px; margin-right: 0; margin-left: 0; width: 70%; overflow-x:scroll; overflow-y:scroll;"));
			$CI->make->sDivCol(4);
				// $CI->make->hidden('tax_id',iSetObj($det,'tax_id'));
				$CI->make->input('First Name','fname',iSetObj($det,'fname'),'Type First Name',array('class'=>'rOkay'));
				$CI->make->input('Middle Name','mname',iSetObj($det,'mname'),'Type Middle Name',array());
				$CI->make->input('Last Name','lname',iSetObj($det,'lname'),'Type Last Name',array('class'=>'rOkay'));
				$CI->make->input('Suffix','suffix',iSetObj($det,'suffix'),'Type Suffix',array('style'=>'width: 250px;'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(4);
				$CI->make->input('Phone','phone',iSetObj($det,'phone'),'Type Phone No.',array('class'=>'rOkay'));
				$CI->make->input('Email Address','email',iSetObj($det,'email'),'Type Email Address',array('class'=>'rOkay'));
				$CI->make->input('Street No.','street_no',iSetObj($det,'street_no'),'Type Street No.',array('class'=>'rOkay'));
				$CI->make->input('Street Address','street_address',iSetObj($det,'street_address'),'Type Street Address',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(4);
				// $CI->make->input('Street No.','street_no',iSetObj($det,'street_no'),'Type Street No.',array('class'=>'rOkay'));
				// $CI->make->input('Street Address','street_address',iSetObj($det,'street_address'),'Type Street Address',array('class'=>'rOkay'));
				$CI->make->input('City','city',iSetObj($det,'city'),'Type City',array('class'=>'rOkay'));
				$CI->make->input('Region','region',iSetObj($det,'region'),'Type Region',array('class'=>'rOkay'));
				$CI->make->input('Zip Code','zip',iSetObj($det,'zip'),'Type Zip Code',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Tax Exempted?','tax_exempt',iSetObj($det,'tax_exempt'));
				$CI->make->inactiveDrop('Is Inactive?','inactive',iSetObj($det,'inactive'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		
		// $CI->make->append('<br/>');
		
		$CI->make->sDivRow(array('style'=>'margin:10px; align: center;'));
			$CI->make->sDivCol(4);
			$CI->make->eDivCol();
			$CI->make->sDivCol(4, 'right');
				$CI->make->button(fa('fa-save').' Save Customer Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
			$CI->make->eDivCol();
			$CI->make->sDivCol(4);
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function customersPage(){
	$CI =& get_instance();
		// $CI->make->sDiv(array('id'=>'gc-cashier'));
		$CI->make->sDiv(array('id'=>'customer'));
		
			$CI->make->sDivRow();
				/*GIFT CARDS CASHIER*/
				$CI->make->sDivCol(12,'left',0,array('class'=>'customer-btns'));
					
					$buttons = array("new-customer"	=> fa('fa-user fa-lg fa-fw')."<br> NEW CUSTOMER",
									 "look-up"	=> fa('fa-search fa-lg fa-fw')."<br> LOOK-UP",
									 "disable-card"	=> fa('fa-file-text-o fa-lg fa-fw')."<br> ORDERS",
									 );
					$CI->make->sDivRow();
					foreach ($buttons as $id => $text) {
							$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
								$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block customer-btn-red double'));
							$CI->make->eDivCol();
					}
						$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
							$CI->make->button(fa('fa-sign-out fa-lg fa-fw')."<br> EXIT",array('id'=>'exit-btn','class'=>'btn-block customer-btn-red-gray double'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDivCol();
			$CI->make->eDivRow();		
			
			//-----NUMPAD AND CUSTOMER DETAILS-----start
			$CI->make->sDivRow();
				$CI->make->sDivCol(3,'left',0,array('id'=>'loginPin','class'=>'customer-left'));
					$CI->make->append(onScrNumPad('telno','telno-login'));
				$CI->make->eDivCol();
				
				$CI->make->sDivCol(8);
					$CI->make->sDiv(array('class'=>'customer-center'));
						//-----CUSTOMER DETAILS-----START
							$CI->make->sDiv(array('class'=>'customer_content_div'));
							
							$CI->make->eDiv();	
						//-----CUSTOMER DETAILS-----END
					$CI->make->eDiv();	
				$CI->make->eDivCol();
			$CI->make->eDivRow();		
			//-----NUMPAD AND CUSTOMER DETAILS-----end

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
function customersList($list = array())
{
	$CI =& get_instance();
	
	$CI->make->sForm("customers/customer_details_db",array('id'=>'customer_details_form'));
		if (!empty($cust_id)) {
			$CI->make->hidden('cust_id',$cust_id);
		}
		
		// $CI->make->sDivRow(array('style'=>'margin:10px; padding-top:10px;'));
		$CI->make->sDivRow();
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
					$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
						$CI->make->sRow();
	    					$CI->make->th('Name');
	    					$CI->make->th('Address');
	    					$CI->make->th('Email');
	    					$CI->make->th('Phone');
	    					$CI->make->th('Is Tax Exempted');
	    					$CI->make->th('Is Inactive');
	    					$CI->make->th();
	    				$CI->make->eRow();
						foreach ($list as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->cust_id));
    							$CI->make->td($val->lname.', '.$val->fname.' '.$val->mname);
    							$CI->make->td($val->street_no.' '.$val->street_address.' '.$val->city.' '.$val->region.' '.$val->zip);
    							$CI->make->td($val->email);
    							$CI->make->td($val->phone);
    							$CI->make->td(($val->tax_exempt ? 'Yes' : 'No'));
    							$CI->make->td(($val->inactive ? 'Yes' : 'No'));
    							$a = $CI->make->A(fa('fa-pencil fa-fw fa-lg'),base_url().'customers/cust_setup/'.$val->cust_id,array('class'=>'edit-line', 'ref'=>$val->cust_id, 'phoneref'=>$val->phone, 'return'=>true));
            					$CI->make->td($a);
								// $a = $CI->make->A(fa('fa-pencil fa-fw fa-lg'),'#',array('id'=>'edit-item-'.$val->cust_id, 'class'=>'edit-line', 'ref'=>$val->cust_id, 'return'=>true));
            					// $CI->make->td($a);
    						$CI->make->eRow();
    					}
					$CI->make->eTable();
				$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eForm();
	
	return $CI->make->code();
}