<?php
function makeUOMForm($uom=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/uom_db",array('id'=>'uom_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('id',iSetObj($uom,'id'));
				if(!empty($uom))
					$CI->make->input('Code','code',iSetObj($uom,'code'),'Type Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
				else
					$CI->make->input('Code','code',iSetObj($uom,'code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Name','name',iSetObj($uom,'name'),'Type Name',array('class'=>'rOkay'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Categories-----start-----allyn
function makeCategoryForm($category=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/category_db",array('id'=>'category_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('cat_id',iSetObj($category,'cat_id'));
					$CI->make->input('Code','code',iSetObj($category,'code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Name','name',iSetObj($category,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($category,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Categories-----end-----allyn
//-----------Sub Categories-----start-----allyn
function makeSubCategoryForm($subcategory=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/subcategory_db",array('id'=>'subcategory_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('sub_cat_id',iSetObj($subcategory,'sub_cat_id'));
				$CI->make->categoriesDrop('Under Category','cat_id',iSetObj($subcategory,'cat_id'),'',array());
				$CI->make->input('Code','code',iSetObj($subcategory,'code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Name','name',iSetObj($subcategory,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($subcategory,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Sub Categories-----end-----allyn
//-----------Suppliers-----start-----allyn
function makeSupplierForm($supplier=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/supplier_db",array('id'=>'supplier_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(6);
				$CI->make->hidden('supplier_id',iSetObj($supplier,'supplier_id'));
				$CI->make->input('Name','name',iSetObj($supplier,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->textarea('Address','address',iSetObj($supplier,'address'),'Type Supplier Address',array('class'=>'rOkay'));
				$CI->make->input('Contact No.','contact_no',iSetObj($supplier,'contact_no'),'Type Contact Number',array('class'=>'rOkay'));
				$CI->make->textarea('Memo','memo',iSetObj($supplier,'memo'),'Type Memo',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($supplier,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
			// $CI->make->sDivCol(6);
				//For another column
			// $CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Suppliers-----end-----allyn
//-----------Tax Rates-----start-----allyn
function makeTaxRateForm($tax_rate=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/tax_rate_db",array('id'=>'tax_rate_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(6);
				$CI->make->hidden('tax_id',iSetObj($tax_rate,'tax_id'));
				$CI->make->input('Name','name',iSetObj($tax_rate,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->input('Rate','rate',iSetObj($tax_rate,'rate'),'Type Rate',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($tax_rate,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
// ------------- Receipt Discounts ------------- //
function makeReceiptDiscountForm($receipt_disc = null)
{
	$CI =& get_instance();
	$CI->make->sForm("settings/receipt_discount_db",array('id'=>'receipt_discount_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px'));
			$CI->make->sDivCol(6);
				$CI->make->hidden('disc_id',iSetObj($receipt_disc,'disc_id'));
				$CI->make->input('Code','disc_code',iSetObj($receipt_disc,'disc_code'),'Discount Code',array('class'=>'rOkay'));
				$CI->make->input('Name','disc_name',iSetObj($receipt_disc,'disc_name'),'Discount Name',array('class'=>'rOkay'));
				$CI->make->input('Rate'
					, 'disc_rate'
					, iSetObj($receipt_disc,'disc_rate')
					, 'Rate'
					, array('class'=>'rOkay','style'=>'width:85px')
				);
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($receipt_disc,'inactive'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Make No Tax','no_tax',iSetObj($receipt_disc,'no_tax'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
// ---------- End of Receipt Discounts --------- //
//-----------Tax Rates-----end-----allyn
function makeTablesPage($branch=null){
	$CI =& get_instance();
	$CI->make->sBox('primary');
		$CI->make->sBoxBody();
		    $CI->make->sDivRow();
				$CI->make->sDivCol(12,'right');
				$btnMsg = "Add an Image";
		    	if($branch->image != null)
					$btnMsg = "Change Image";
					$CI->make->A(fa('fa-picture-o').' '.$btnMsg,'settings/upload_image_seat_form/',array(
																'id'=>'change-img',
																'rata-title'=>'Restaurant Seating Image Upload',
																'rata-pass'=>'settings/upload_image_seat_db',
																'rata-form'=>'upload_image_form',
																'class'=>'btn btn-primary'
															));
				$CI->make->eDivCol();
		    $CI->make->eDivRow();
		    $CI->make->sDivRow();
		    	if($branch->image != null)
			    	$CI->make->hidden('imgSrc',base_url().'uploads/'.$branch->image);
		    	else
			    	$CI->make->hidden('imgSrc',null);
				$CI->make->sDivCol(12,'left',0,array('id'=>'imgCon'));
				$CI->make->eDivCol();
		    $CI->make->eDivRow();
	    $CI->make->eBoxBody();
	$CI->make->sBox();

	return $CI->make->code();
}
function makeTableForm($det=null){
	$CI =& get_instance();
		$CI->make->sForm("settings/tables_db",array('id'=>'table_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(2);
					$CI->make->hidden('tbl_id',iSetObj($det,'tbl_id'));
					$CI->make->input('Capacity','capacity',iSetObj($det,'capacity'),'Type Capacity',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(8);
					$CI->make->input('Name','name',iSetObj($det,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function makeTableUploadForm($det=null){
	$CI =& get_instance();
		$CI->make->sForm("settings/upload_image_seat_db",array('id'=>'upload_image_form','enctype'=>'multipart/form-data'));
			$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
				$CI->make->sDivCol();
					$CI->make->A(fa('fa-picture-o').' Select an Image','#',array(
															'id'=>'select-img',
															'class'=>'btn btn-primary'
														));
					$CI->make->append('<br>');
					$CI->make->H(4,'Warning! Changing image will delete all the set tables.',array('class'=>'label label-warning'));
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol();
					$thumb = base_url().'img/noimage.png';
					if(iSetObj($det,'image')  != ""){
						$thumb = base_url().'uploads/'.iSetObj($det,'image');
					}
					$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'height:220px;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
//-----------Terminals-----start-----allyn
function makeTerminalForm($terminal=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/terminal_db",array('id'=>'terminal_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('terminal_id',iSetObj($terminal,'terminal_id'));
				if(!empty($terminal))
					$CI->make->input('Code','terminal_code',iSetObj($terminal,'terminal_code'),'Type Terminal Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
				else
					$CI->make->input('Code','terminal_code',iSetObj($terminal,'terminal_code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Name','terminal_name',iSetObj($terminal,'terminal_name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->input('I.P. Address','ip',iSetObj($terminal,'ip'),'Type I.P. Address',array('class'=>'rOkay'));
				$CI->make->input('Computer Name','comp_name',iSetObj($terminal,'comp_name'),'Type Computer Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($terminal,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Terminals-----end-----allyn
//-----------Currencies-----start-----allyn
function makeCurrencyForm($currency=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/currency_db",array('id'=>'currency_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('id',iSetObj($currency,'id'));
				// if(!empty($currency))
					// $CI->make->input('Code','currency_code',iSetObj($currency,'currency_code'),'Type currency Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
				// else
					$CI->make->input('Currency','currency',iSetObj($currency,'currency'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Description','currency_desc',iSetObj($currency,'currency_desc'),'Type Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($currency,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Currencies-----end-----allyn
//-----------References-----start-----allyn
function makeReferencesForm($det=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("settings/references_db",array('id'=>'references_form'));
						foreach($det as $val){
							$CI->make->sDivRow(array('style'=>'margin:10px;'));
								$CI->make->sDivCol(6);
									$x = $CI->make->A(fa('fa-save'),'#', array('return'=>true, 'class'=>'save_btn', 'ref'=>$val->type_id, 'label'=>ucwords($val->name)));
									$CI->make->input(ucwords($val->name),'type-'.$val->type_id,$val->next_ref,'Type Code',array('class'=>'rOkay'), null, $x);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						}
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
//-----------References-----end-----allyn
//-----------Locations-----start-----allyn
function makeLocationForm($location=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/location_db",array('id'=>'location_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('loc_id',iSetObj($location,'loc_id'));
					$CI->make->input('Code','loc_code',iSetObj($location,'loc_code'),'Type Location Code',array('class'=>'rOkay'));
				$CI->make->input('Name','loc_name',iSetObj($location,'loc_name'),'Type Location Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($location,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//-----------Locations-----end-----allyn
function makeChargeForm($item=array()){
	$CI =& get_instance();

	$CI->make->sForm("charges/db",array('id'=>'charge_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('charge_id',iSetObj($item,'charge_id'));
				if(!empty($item))
					$CI->make->input('Code','charge_code',iSetObj($item,'charge_code'),'Type Code',array('class'=>'rOkay', 'readonly'=>'readonly'));
				else
					$CI->make->input('Code','charge_code',iSetObj($item,'charge_code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->input('Name','charge_name',iSetObj($item,'charge_name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->number('Amount','charge_amount',iSetObj($item,'charge_amount'),'Type Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Absolute','absolute',iSetObj($item,'absolute'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Tax Excempt','no_tax',iSetObj($item,'no_tax'),'',array('style'=>'width: 85px;'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($item,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
//---------------------jed
function makeDenominationForm($deno=array()){
	$CI =& get_instance();
	$CI->make->sForm("settings/denomination_db",array('id'=>'denomination_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('id',iSetObj($deno,'id'));
					$CI->make->input('Description','desc',iSetObj($deno,'desc'),'Type Description',array('class'=>'rOkay'));
				$CI->make->decimal('Value','value',iSetObj($deno,'value'),'Type Value',2,array('class'=>'rOkay'));
				//$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($location,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
?>