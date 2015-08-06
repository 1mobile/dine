<?php
function adjustments_display($list = null)
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Create new adjustment',base_url().'adjustment/form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
									'Date'=>'',
									'Reference'=>'',
									'Created by' => '',
									'Memo'=>'',
									'Last updated'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $val){
								$links = "";
								// $links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'menu/form/'.$v->menu_id,array("return"=>true));
								$rows[] = array(
											sql2Date($val->reg_date)
											, $val->trans_ref
											, $val->username
											, $val->memo
											, $val->update_date
											, ''
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
function adjustment_form($list = null)
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol(4);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sForm("adjustment/add_item",array('id'=>'add_item_form'));
						$CI->make->input('Item','item-search',null,'Search Item',array('search-url'=>'mods/search_items'),'',fa('fa-search'));
						$CI->make->hidden('item-id',null);
						$CI->make->hidden('item-uom',null);
						$CI->make->hidden('item-ppack',null);
						$CI->make->hidden('item-pcase',null);
						$CI->make->sDivRow();
							$CI->make->sDivCol(6);
								$CI->make->input('Quantity','qty',null,null,array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(6);
								$CI->make->select('&nbsp;','select-uom',array(),null,array());
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->locationsDrop('Transfer from','from_loc',iSetObj($list,'from_loc'),null,array('shownames'=>true));
						$CI->make->locationsDrop('Transfer to','to_loc',iSetObj($list,'to_loc'),null,array('shownames'=>true));
						$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
		#FORM
		$CI->make->sDivCol(8);
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sForm("adjustment/adjustment_db",array('id'=>'adjustment_form'));
							$CI->make->sDivCol(5);
								$CI->make->input('Remarks','memo',null,'Notes',array());
							$CI->make->eDivCol();
							$CI->make->sDivCol(3,'right',4);
								$CI->make->button(
									fa('fa-save').' Save Adjustments'
									, array('id'=>'save-trans','disabled'=>'disabled','style'=>'margin:23px 0 10px')
									, 'primary');
							$CI->make->eDivCol();
						$CI->make->eForm();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$CI->make->sDiv(array('class'=>'table-responsive'));
							$CI->make->sTable(array('class'=>'table table-hover','id'=>'details-tbl'));
								$CI->make->sRow();
									$CI->make->th('Item');
									$CI->make->th('Quantity');
									// $CI->make->th('QTY',array('style'=>'width:60px;'));
									$CI->make->th('UOM',array('style'=>'width:60px;'));
									// $CI->make->th('Cost',array('style'=>'width:60px;'));
									$CI->make->th('Transfer from');
									$CI->make->th('Transfer to');
									$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
								$CI->make->eRow();
							$CI->make->eTable();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();

	return $CI->make->code();
}