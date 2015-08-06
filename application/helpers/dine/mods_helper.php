<?php
function modListPage($list=array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Add New Modifier',base_url().'mods/form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
									'Name'=>'',
									'Cost'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $v){
								$links = "";
								$links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'mods/form/'.$v->mod_id,array("return"=>true));
								$rows[] = array(
											  $v->name,
											  num($v->cost),
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
function modFormPage($mod_id=null){
	$CI =& get_instance();
	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
			$CI->make->sDivCol(12,'right');
				$CI->make->A(fa('fa-reply').' Go Back To list',base_url().'mods',array('class'=>'btn btn-primary'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sTab();
					$tabs = array(
						fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'mods/details_load/','id'=>'details_link'),
						fa('fa-book')." Recipe"=>array('href'=>'#recipe','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'mods/recipe_load/','id'=>'recipe_link'),
					);
					$CI->make->hidden('mod_id',$mod_id);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'recipe','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function modDetailsLoad($mod=null,$mod_id=null){
	$CI =& get_instance();
		$CI->make->sForm("mods/details_db",array('id'=>'details_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->hidden('form_mod_id',$mod_id);
					$CI->make->input('Name','name',iSetObj($mod,'name'),'Type Code',array('class'=>'rOkay'));
					$CI->make->input('Price','cost',iSetObj($mod,'cost'),'Price',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->inactiveDrop('Has Recipe','has_recipe',iSetObj($mod,'has_recipe'));
					$CI->make->inactiveDrop('Inactive','inactive',iSetObj($mod,'inactive'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4,'left',4);
				$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-mod'),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function modRecipeLoad($mod_id=null,$det=null,$mod=null){
	$CI =& get_instance();
		$CI->make->sForm("mods/recipe_db",array('id'=>'recipe_form'));
			$CI->make->hidden('mod_id',$mod_id);
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->input(null,'item-search',null,'Search Item',array('search-url'=>'mods/search_items'),'',fa('fa-search'));
					$CI->make->input('Item Price','item-cost',null,null,array('readonly'=>'readonly'));
					$uomTxt = $CI->make->span('&nbsp;&nbsp;',array('return'=>true,'id'=>'uom-txt'));
					$CI->make->input('Quantity','qty',null,null,array(),'',$uomTxt);
					$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please Select an Item'));
					$CI->make->hidden('item-uom-hid',null);
					$CI->make->button(fa('fa-plus').' Add Item',array('class'=>'btn-block','id'=>'add-item-btn'),'primary');
				$CI->make->eDivCol();
				$CI->make->sDivCol(9);
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$CI->make->sDiv(array('class'=>'table-responsive'));
								$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
									$CI->make->sRow();
										$CI->make->th('ITEM');
										$CI->make->th('QTY',array('style'=>'width:60px;'));
										$CI->make->th('PRICE',array('style'=>'width:60px;'));
										$CI->make->th('SUBTOTAL',array('style'=>'width:60px;'));
										$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
									$CI->make->eRow();
									$total = 0;
									if(count($det) > 0){
										foreach ($det as $res) {
											$CI->make->sRow(array('id'=>'row-'.$res->mod_recipe_id));
									            $CI->make->td($res->code." ".$res->name);
									            $CI->make->td(num($res->qty));
										        $CI->make->td(num($res->cost));
									            $CI->make->td(num($res->cost * $res->qty));
									            $a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$res->mod_recipe_id,'class'=>'dels','ref'=>$res->mod_recipe_id,'return'=>true));
									            $CI->make->td($a);
									        $CI->make->eRow();
											$total += $res->cost * $res->qty;
										}
									}
								$CI->make->eTable();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol(4,'left','8');
								$pop = $CI->make->A(fa('fa-save fa-fw '),'#',array('id'=>'override-price','return'=>true));
								$sell_price = iSetObj($mod,'cost');
								$CI->make->input('Selling Price','total',num($sell_price),null,array(),null,$pop);
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDivCol();				
			$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function modGroupListPage($list=array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Add New Modifier Group',base_url().'mods/group_form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
									'Name'=>'',
									'Cardinality'=>'',
									'Selection'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $v){
								$links = "";
								$links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'mods/group_form/'.$v->mod_group_id,array("return"=>true));
								$type = "Mandatory";
								if($v->mandatory == 0)
									$type = "Optional";
								$sel = "Single";
								if($v->multiple == 0)
									$sel = "Multiple";
								$rows[] = array(
											  $v->name,
											  $type,
											  $sel,
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
function modGroupFormPage($mod_group_id=null){
	$CI =& get_instance();
	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
			$CI->make->sDivCol(12,'right');
				$CI->make->A(fa('fa-reply').' Go Back To list',base_url().'mods/groups',array('class'=>'btn btn-primary'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sTab();
					$tabs = array(
						fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'mods/group_details_load/','id'=>'details_link'),
						fa('fa-book')." Modifiers"=>array('href'=>'#modifiers','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'mods/group_modifiers_load/','id'=>'recipe_link'),
					);
					$CI->make->hidden('mod_group_id',$mod_group_id);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'modifiers','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function modGroupDetailsLoad($grp=null,$mod_group_id=null){
	$CI =& get_instance();
		$CI->make->sForm("mods/group_details_db",array('id'=>'details_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->hidden('form_mod_group_id',$mod_group_id);
					$CI->make->input('Name','name',iSetObj($grp,'name'),'Type Code',array('class'=>'rOkay'));
					$CI->make->inactiveDrop('Mandatory','mandatory',iSetObj($grp,'mandatory'));
					$CI->make->number('Select Limit','multiple',iSetObj($grp,'multiple'));
					$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($grp,'inactive'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4,'left',4);
				$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-grp'),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function groupModifiersLoad($mod_group_id=null,$det=null){
	$CI =& get_instance();
		$CI->make->sForm("mods/recipe_db",array('id'=>'recipe_form'));
			$CI->make->hidden('mod_group_id',$mod_group_id);
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->input(null,'item-search',null,'Search Modifiers',array('search-url'=>'mods/search_modifiers'),'',fa('fa-search'));
				$CI->make->eDivCol();				
			$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->sUl(array('class'=>'vertical-list','id'=>'modifier-list'));
						foreach ($det as $res) {
							$li = $CI->make->li(
				                $CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
				                $CI->make->span($res->mod_name,array('class'=>'text','return'=>true))." ".
				                $CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del','id'=>'del-'.$res->id,'ref'=>$res->id)),
				                array('id'=>'li-'.$res->id)
				             );
						}
					$CI->make->eUl();
				$CI->make->eDivCol();				
			$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
?>