<?php
function receivingListPage($list=array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-download').' Receive',base_url().'receiving/form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
									'Date'=>'',
									'Reference'=>'',
									'Supplier'=>'',
									'Supplier Reference'=>'',
									'Received By'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $res){
								$links = "";
								// $links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'menu/form/'.$v->menu_id,array("return"=>true));
								$rows[] = array(
											  sql2Date($res->reg_date),
											  $res->trans_ref,
											  $res->supplier_name,
											  $res->reference,
											  $res->username,
											  $links
									);
							}
							$CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function receivingFormPage($list=array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol(3);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/add_item",array('id'=>'add_item_form'));
						$CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items','class'=>'rOkay'),'',fa('fa-search'));
						$CI->make->hidden('item-id',null);
						$CI->make->hidden('item-uom',null);
						$CI->make->hidden('item-ppack',null);
						$CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(6);
								$CI->make->input('Quantity','qty',null,null,array('class'=>'rOkay'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->select('&nbsp;','select-uom',array(),null,array('class'=>'rOkay'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->input('Total Cost','cost',null,null,array());
						$CI->make->locationsDrop('Receiving Location','loc_id',null,null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(9);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("receiving/save",array('id'=>'receive_form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(2);
								$CI->make->input('Reference','reference',null,'Supplier reference',array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->suppliersDrop('Supplier','suppliers',null,null,array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(5);
								$CI->make->input('Remarks','memo',null,'Notes',array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(2,'right');
								$CI->make->button(fa('fa-download').' Receive',array('id'=>'save-btn','style'=>'margin-top:25px'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDiv(array('class'=>'table-responsive'));
								$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl'));
									$CI->make->sRow();
										$CI->make->th('ITEM');
										$CI->make->th('QTY',array('style'=>'width:60px;'));
										$CI->make->th('UOM',array('style'=>'width:180px;'));
										$CI->make->th('Cost',array('style'=>'width:60px;'));
										$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
									$CI->make->eRow();
								$CI->make->eTable();
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
							// $CI->make->button(
							// fa('fa-save').' Save Adjustments'
							// , array('class'=>'btn-block','id'=>'save-trans','style'=>'margin-top:10px','disabled'=>'disabled')
							// , 'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();


	$CI->make->eDivRow();

	return $CI->make->code();
}
?>