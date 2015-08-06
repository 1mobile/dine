<?php
function makeManageItems($res_id=null,$category=null,$subcategory=null){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->hidden('res_id',$res_id);
			$CI->make->sTab();
					$tabs = array(
						fa('fa-flask')." Items"=>array('href'=>'#items','class'=>'tab_link','load'=>'menu/items_load/','id'=>'items_link'),
						fa('fa-archive')." Combos"=>array('href'=>'#combos','class'=>'tab_link load-tab','load'=>'menu/combos_load/','id'=>'combos_link'),
					);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						#ITEMS TAB
						$CI->make->sTabPane(array('id'=>'items','class'=>'tab-pane active'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(3,'Left');
									$options = array();
									$options['Select a Category'] = "";
									$selected = "";
									foreach ($category as $res) {
										$options[$res->name] = $res->cat_id;
									}
									$pop = $CI->make->A(fa('fa-external-link fa-fw '),'menu/category_form/'.$res_id,array(
																								'id'=>'add-new-category',
																								'rata-title'=>'Add New Category',
																								'rata-pass'=>'menu/category_db',
																								'rata-form'=>'category_form',
																								'return'=>true
																								));
									$CI->make->select('Category','category-drop',$options,$selected,array(),null,$pop);
								$CI->make->eDivCol();
								$CI->make->sDivCol(3,'Left');
									$options = array();
									$options['Select a Sub Category'] = "";
									$selected = "";
									foreach ($subcategory as $sres) {
										$options[$sres->name] = array("value"=>$sres->sub_cat_id,'category'=>$sres->cat_id);
									}

									$pop = $CI->make->A(fa('fa-external-link fa-fw '),'menu/sub_category_form/'.$res_id,array(
																									'id'=>'add-new-sub-category',
																									'rata-title'=>'Add New Sub Category',
																									'rata-pass'=>'menu/sub_category_db',
																									'rata-form'=>'sub_category_form',
																									'return'=>true
																								));
									$CI->make->select('Sub Category','sub-category-drop',$options,$selected,array(),null,$pop);
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'right',2,array('style'=>'margin-top:22px'));
									$CI->make->A(fa('fa-plus fa-fw').' Add New Item ',base_url().'menu/item_form/'.$res_id,array('class'=>'btn btn-primary'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'left',0,array('id'=>'item-list-div'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eTabPane();
						#COMBOS TAB
						$CI->make->sTabPane(array('id'=>'combos','class'=>'tab-pane'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
									$CI->make->A(fa('fa-plus fa-fw').' Add New Combo ',base_url().'menu/combo_form/'.$res_id,array('class'=>'btn btn-primary'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'left',0,array('id'=>'combos-list-div'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eTabPane();
					$CI->make->eTabBody();
			$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function makeCategoryLoad($res_id=null,$cat=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/category_db",array('id'=>'category_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('res_id',$res_id);
					$CI->make->hidden('cat_id',iSetObj($cat,'cat_id'));
					$CI->make->input('Code','code',iSetObj($cat,'code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(8);
					$CI->make->input('Name','name',iSetObj($cat,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol();
					$thumb = base_url().'img/noimage.png';
					if(iSetObj($cat,'image')  != ""){
					// 	$thumb = blob2Image($cat->image);
						$thumb = base_url().'uploads/categories/'.iSetObj($cat,'image');
					}
					$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'max-height:380px;min-height:200px;width:inherit;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function makeSubCategoryLoad($res_id=null,$cat_id=null,$cat=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/sub_category_db",array('id'=>'sub_category_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('res_id',$res_id);
					$CI->make->hidden('cat_id',$cat_id);
					$CI->make->hidden('sub_cat_id',iSetObj($cat,'sub_cat_id'));
					$CI->make->input('Code','code',iSetObj($cat,'code'),'Type Code',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(8);
					$CI->make->input('Name','name',iSetObj($cat,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol();
					$thumb = base_url().'img/noimage.png';
					if(iSetObj($cat,'image')  != ""){
						// $thumb = blob2Image($cat->img);
						$thumb = base_url().'uploads/sub_categories/'.iSetObj($cat,'image');
						// echo $thumb;
					}
					$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'max-height:380px;min-height:200px;width:inherit;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function makeItemListLoad($res_id=null,$items=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			foreach ($items as $res) {
				$thumb = base_url().'img/noimage.png';
				if($res->image  != ""){
					// $thumb = blob2Image($res->img);
					$thumb = base_url().'uploads/'.$res_id.'/menu/'.iSetObj($res,'image');
					// echo $thumb;
				}
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'media'));
						$img = $CI->make->img($thumb,array('class'=>'media-object thumbnail','return'=>true,'height'=>"90"));
						$CI->make->A($img,base_url().'menu/item_form/'.$res_id."/".$res->item_id,array('class'=>'pull-left'));
						$CI->make->sDiv(array('class'=>'media-body'));
							// $sub = $CI->make->small('Something',array('return'=>true));
							$CI->make->H(4,strong($res->name),array('class'=>'media-object'));
							$CI->make->H(5,tagWord($res->cat_name)." ".tagWord($res->sub_cat_name,'info'),array('class'=>'media-object'));
							$CI->make->H(5,num($res->price),array('class'=>'media-object'));
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			}
    	$CI->make->eDivRow();
	return $CI->make->code();
}
function makeItemForm($res_id=null,$category=array(),$item=null,$subcategory=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(12,'right');
            	$CI->make->button(fa('fa-save').' Save Item Details',array('id'=>'save-btn'),'primary');
            	$CI->make->append(" ");
            	$CI->make->A(fa('fa-reply').' Go back',base_url().'menu/manage_items/'.$res_id,array('class'=>'btn btn-default'));
            $CI->make->eDivCol();
    	$CI->make->eDivRow();
    	$CI->make->sDivRow();
			$CI->make->sDivCol();
				$CI->make->sPaper();
					$CI->make->sForm("menu/items_db",array('id'=>'items-form','enctype'=>'multipart/form-data'));
					$CI->make->hidden('item_id',iSetObj($item,'item_id'));
					$CI->make->hidden('hid_sub_cat_id',iSetObj($item,'sub_cat_id'));
					/* GENERAL DETAILS */
						$CI->make->sDivRow(array('style'=>'margin:10px;'));
							$CI->make->sDivCol(3);
								$CI->make->sDiv(array('class'=>'media'));
									$thumb = base_url().'img/noimage.png';
									if(iSetObj($item,'image')  != ""){
										// $thumb = blob2Image($item->img);
										// $thumb = $item->image;
										$thumb = base_url().'uploads/'.$res_id.'/menu/'.iSetObj($item,'image');
									}
									$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'height:220px;width:100%;'));
									$CI->make->file('fileUpload',array('style'=>'display:none;'));
								$CI->make->eDiv();
							$CI->make->eDivCol();

							$CI->make->sDivCol(9);
								$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(4);
										$CI->make->input('Item Code','code',iSetObj($item,'code'),'Type Code',array('class'=>'rOkay'));
										$CI->make->input('Barcode','barcode',iSetObj($item,'barcode'),'Type Code',array('class'=>'rOkay'));
										$options = array();
											$options['Select a Category'] = "";
											$selected = iSetObj($item,'cat_id');
											foreach ($category as $res) {
												$options[$res->name] = $res->cat_id;
											}
											$pop = $CI->make->A(fa('fa-external-link fa-fw '),'menu/category_form/'.$res_id,array(
																										'id'=>'add-new-category',
																										'rata-title'=>'Add New Category',
																										'rata-pass'=>'menu/category_db',
																										'rata-form'=>'category_form',
																										'return'=>true
																										));
										$CI->make->select('Category','category-drop',$options,$selected,array('class'=>'rOkay','ro-msg'=>'Error! Category must not be empty'),null,$pop);
										$options = array();
											$options['Select a Sub Category'] = "";
											$selected = iSetObj($item,'sub_cat_id');
											foreach ($subcategory as $sres) {
												$options[$sres->name] = array("value"=>$sres->sub_cat_id,'category'=>$sres->cat_id);
											}

											$pop = $CI->make->A(fa('fa-external-link fa-fw '),'menu/sub_category_form/'.$res_id,array(
																											'id'=>'add-new-sub-category',
																											'rata-title'=>'Add New Sub Category',
																											'rata-pass'=>'menu/sub_category_db',
																											'rata-form'=>'sub_category_form',
																											'return'=>true
																										));
										$CI->make->select('Sub Category','sub-category-drop',$options,$selected,array(),null,$pop);
									$CI->make->eDivCol();
									$CI->make->sDivCol(6,'left');
										$CI->make->input('Name','name',iSetObj($item,'name'),'Type Name',array('class'=>'rOkay'));
										$CI->make->textarea('Description','description',iSetObj($item,'description'),null,array('class'=>'rOkay'));
										
										$CI->make->hidden('res_id',$res_id);
									$CI->make->eDivCol();
			                	$CI->make->eDivRow();
			                	$CI->make->sDivRow(array('style'=>'margin:10px;'));
									$CI->make->sDivCol(2,'left');
										$CI->make->input('Price','price',iSetObj($item,'price'),'Type Price',array('class'=>'rOkay'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(2);
										$CI->make->input('Portions','portion',iSetObj($item,'portion'),'Type Portions',array());
									$CI->make->eDivCol();
									$CI->make->sDivCol(2);
										$CI->make->input('Portion Price','portion_price',iSetObj($item,'portion_price'),'Type Portion Price',array());
									$CI->make->eDivCol();
			                	$CI->make->eDivRow();
							$CI->make->eDivCol();

	                	$CI->make->eDivRow();



					$CI->make->eForm();
				$CI->make->ePaper();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function makeComboListLoad($res_id=null,$combos=null){
	$CI =& get_instance();
		$CI->make->sDivRow(array('style'=>'margin-top:15px;'));
			$thumb = base_url().'img/noimage.png';
			foreach ($combos as $res) {
				$thumb = base_url().'img/noimage.png';
				if($res->image  != ""){
					// $thumb = blob2Image($res->img);
					$thumb = base_url().'uploads/'.$res_id.'/combo/'.iSetObj($res,'image');
				}
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'media'));
						$img = $CI->make->img($thumb,array('class'=>'media-object','return'=>true,'height'=>"90",'width'=>"auto"));
						$CI->make->A($img,base_url().'menu/combo_form/'.$res_id."/".$res->combo_id,array('class'=>'pull-left'));
						$CI->make->sDiv(array('class'=>'media-body'));
							// $sub = $CI->make->small('Something',array('return'=>true));
							$CI->make->H(4,strong($res->combo_name),array('class'=>'media-object'));
							$CI->make->H(5,num($res->selling_price),array('class'=>'media-object'));
							$CI->make->H(5,$res->combo_desc,array('class'=>'media-object'));
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			}
    	$CI->make->eDivRow();
	return $CI->make->code();
}
function makeComboForm($res_id=null,$combo=null,$det=array()){
	$CI =& get_instance();
		$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
			$CI->make->sDivCol(12,'right');
            	$CI->make->append(" ");
            	$CI->make->A(fa('fa-reply').' Go back',base_url().'menu/manage_items/'.$res_id,array('class'=>'btn btn-default'));
            $CI->make->eDivCol();
    	$CI->make->eDivRow();
    	$CI->make->sDivRow();
			$CI->make->sDivCol(4,'left');
				$CI->make->sBox('primary');
                    $CI->make->sBoxBody();
                    	$CI->make->sForm("menu/combos_db",array('id'=>'combo-form','enctype'=>'multipart/form-data'));
							$CI->make->hidden('combo_id',iSetObj($combo,'combo_id'));
							$CI->make->hidden('res_id',$res_id);
							$CI->make->sDivRow();
								$CI->make->sDivCol();
									$CI->make->H(4,fa('fa-info-circle fa-fw').' General Details',array('class'=>'page-header'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								$CI->make->sDivCol();
									$CI->make->input('Code','combo_code',iSetObj($combo,'combo_code'),'Type Code',array('class'=>'rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol();
									$CI->make->input('Barcode','combo_barcode',iSetObj($combo,'combo_barcode'),'Type Barcode',array('class'=>'rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol();
									$CI->make->input('Name','combo_name',iSetObj($combo,'combo_name'),'Type Name',array('class'=>'rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol();
									$CI->make->textarea('Description','combo_desc',iSetObj($combo,'combo_desc'),'Type Name',array('class'=>'rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol();
									$CI->make->sDiv(array('class'=>'media'));
										$thumb = base_url().'img/noimage.png';
										if(iSetObj($combo,'image')  != ""){
											// $thumb = blob2Image($combo->img);
											$thumb = base_url().'uploads/'.$res_id.'/combo/'.iSetObj($combo,'image');
										}
										$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'height:220px;'));
										$CI->make->file('fileUpload',array('style'=>'display:none;'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
                	       $CI->make->eForm();
							$CI->make->sDivCol();
				            	$CI->make->button(fa('fa-save').' Save Combo Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
							$CI->make->eDivCol();
		                	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();
			$CI->make->sDivCol(8,'left',0,array('id'=>'item-details-box'));

				$CI->make->sBox('primary');
                    $CI->make->sBoxBody();

						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->H(4,fa('fa-archive').' Item Details',array('class'=>'page-header'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();

						$CI->make->sForm("menu/combos_details_db",array('id'=>'combo-details-form'));
							$CI->make->hidden('combo-id-hid',iSetObj($combo,'combo_id'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
									$CI->make->input(null,'item-search',null,'Search Item',array('search-url'=>'menu/search_items','add-data'=>'res_id='+$res_id));
									$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please Select an Item'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(2);
									$CI->make->portionWholeDrop(null,'pwh-drop',null,null,array());
									$CI->make->hidden('type',null,array('class'=>'rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(1);
									$CI->make->H(4,'0.00',array('id'=>'item-price'));
									$CI->make->hidden('item-price-hid',0);
								$CI->make->eDivCol();
								$CI->make->sDivCol(2);
									$CI->make->input(null,'qty',null,'Add QTY',array('class'=>'rOkay','ro-msg'=>'Error! Add Quantity'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(1);
									$CI->make->button(fa('fa-plus').' Add Item',array('id'=>'add-btn'),'primary');
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eForm();

						$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDiv(array('class'=>'table-responsive'));
									$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
										$CI->make->sRow();
											$CI->make->th('ITEM');
											$CI->make->th('TYPE',array('style'=>'width:60px;'));
											$CI->make->th('QTY',array('style'=>'width:60px;'));
											$CI->make->th('PRICE',array('style'=>'width:60px;'));
											$CI->make->th('SUBTOTAL',array('style'=>'width:60px;'));
											$CI->make->th('&nbsp;',array('style'=>'width:40px;'));
										$CI->make->eRow();
										$total = 0;
										if(count($det) > 0){
											foreach ($det as $res) {
												$CI->make->sRow(array('id'=>'row-'.$res->combo_det_id));
										            $CI->make->td($res->code." ".$res->name);
										            $CI->make->td($res->type);
										            $CI->make->td(num($res->qty));
										            if($res->type == 'whole')
										            	$price = $res->price;
											        else
										            	$price = $res->portion_price;
											        $CI->make->td(num($price));
										            $CI->make->td(num($price * $res->qty));
										            $a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$res->combo_det_id,'class'=>'dels','ref'=>$res->combo_det_id,'return'=>true));
										            $CI->make->td($a);
										        $CI->make->eRow();
												$total += $price * $res->qty;
											}
										}
									$CI->make->eTable();
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow(array("style"=>"margin-top:20px;"));
							$CI->make->sDivCol(4,'left','8');
									$pop = $CI->make->A(fa('fa-save fa-fw '),'#',array('id'=>'override-price','return'=>true));
									$sell_price = iSetObj($combo,'selling_price');
									$CI->make->input('Selling Price','total',num($sell_price),null,array(),null,$pop);
							$CI->make->eDivCol();
						$CI->make->eDivRow();

                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();
		$CI->make->eDivRow();


	return $CI->make->code();
}
?>