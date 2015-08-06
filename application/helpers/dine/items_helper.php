<?php
function items_display($list = array())
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							$CI->make->A(fa('fa-plus').' Add New Item',base_url().'items/setup',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								'Code'=>'',
								'Name'=>'',
								'Category'=>'',
								'Subcategory'=>'',
								'Supplier'=>'',
								'Type'=>'',
								''=>array('width'=>'10%','align'=>'right')
							);
							$rows = array();
							foreach ($list as $val) {
								$link = "";
								$link .= $CI->make->A(fa('fa-pencil fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->item_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								$rows[] = array(
									$val->code,
									$val->name,
									$val->category,
									$val->subcategory,
									$val->supplier,
									$val->item_type,
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
function items_form_container($item_id)
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->hidden('item_idx',$item_id);
		$CI->make->sDivCol(12);
			$CI->make->sTab();
				$tabs = array(
					fa('fa-info-circle')." General Details" => array('href'=>'#details','class'=>'tab_link','load'=>'items/setup_load','id'=>'details_link'),
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
function items_details_form($info, $item_id)
{
	$CI =& get_instance();

	$CI->make->sForm("items/item_details_db",array('id'=>'item_details_form'));
		if (!empty($item_id)) {
			$CI->make->hidden('item_id',$item_id);
		}
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->input('Item Name','name',iSetObj($info,'name'),'Item Name',array('class'=>'rOkay'));
				$CI->make->textarea('Description','desc',iSetObj($info,'desc'),'Description',array('style'=>'height:181px;max-width:340px'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Code','code',iSetObj($info,'code'),'Item Code',array('class'=>'rOkay'));
				$CI->make->input('Barcode','barcode',iSetObj($info,'barcode'),'Barcode',array());
				$CI->make->categoriesDrop('Category','cat_id',iSetObj($info,'cat_id'),'Select Category',array('class'=>'rOkay'));
				$CI->make->itemSubcategoryDrop('Sub-category','subcat_id',iSetObj($info,'subcat_id'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->inactiveDrop('Inactive?','inactive',iSetObj($info,'inactive'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->append('<br/>');
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->suppliersDrop('Supplier','supplier_id',iSetObj($info,'supplier_id'));
				$CI->make->uomDrop('UOM','uom',iSetObj($info,'uom'),array('class'=>'rOkay'));
				$CI->make->itemTypeDrop('Item Type','type',iSetObj($info,'type'),'Item Type');
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Cost','cost',iSetObj($info,'cost'),'Item Cost',array('class'=>'rOkay'));
				$CI->make->input('No. per pack','no_per_pack',iSetObj($info,'no_per_pack'),'No. per pack');
				$CI->make->input('Packs per case','no_per_case',iSetObj($info,'no_per_case'),'Packs per case');
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Reorder quantity','reorder_qty',iSetObj($info,'reorder_qty'),'Reorder point');
				$CI->make->input('Max quantity','max_qty',iSetObj($info,'max_qty'),'Maximum item count');
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->append('<br/>');
		$CI->make->append('<br/>');
		$CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->button(fa('fa-save').' Save Item Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function item_inventory_and_location_container($records, $loc_fields)
{
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol(9);
		$CI->make->eDivCol();
		$CI->make->sDivCol(3);
			$CI->make->button(fa('fa-print').' Download Excel',array('id'=>'print-btn','class'=>'btn-block'),'primary');
		$CI->make->eDivCol();
    $CI->make->eDivRow();
    $CI->make->append('<br>');
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('info');
				$CI->make->sBoxBody();
						$th = array(
							'Item Code'=>'',
							'Item Name'=>'',
						);
						$rows = array();

						if (!empty($loc_fields)) {
							foreach ($loc_fields as $unf => $frm) {
								$th[$frm] = '';
							}
							$th[''] = array('width'=>'10%','align'=>'right');
						} else {
							$rows[] = array('No records found','');
						}


						foreach ($records as $val) {
							$item_array = array($val['code'],$val['name']);

							foreach ($loc_fields as $unfx => $frmx) {
								$item_array[] = (!empty($val[$unfx]) ? num($val[$unfx]) : null)." ".$val['uom'];
							}

							$item_array[] = '';
							$rows[] = $item_array;
						}
						$CI->make->listLayout($th,$rows);
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}