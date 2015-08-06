<?php
function menuListPage($list=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Add New Menu',base_url().'menu/form',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array('Menu Code'=>'',
									'Barcode'=>'',
									'Short Description'=>'',
									'Name'=>'',
									'Category'=>'',
									'Schedule'=>'',
									'Price'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $v){
								$links = "";
								$links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'menu/form/'.$v->menu_id,array("return"=>true));
								$rows[] = array(
											  $v->menu_code,
											  $v->menu_barcode,
											  $v->menu_name,
											  $v->menu_short_desc,
											  $v->category_name,
											  $v->menu_schedule_name,
											  $v->cost,
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
function menuSearchForm($post=array()){
	$CI =& get_instance();
	$CI->make->sForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->input('Menu Name','menu_name',null,null);
				$CI->make->menuCategoriesDrop('Categories','menu_cat_id',null,'- select category -');
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',null,null,array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function menuFormPage($menu_id=null){
	$CI =& get_instance();
	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
			$CI->make->sDivCol(12,'right');
				$CI->make->A(fa('fa-reply').' Go back to list',base_url().'menu',array('class'=>'btn btn-primary'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sTab();
					$tabs = array(
						fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'menu/details_load/','id'=>'details_link'),
						fa('fa-book')." Recipe"=>array('href'=>'#recipe','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'menu/recipe_load/','id'=>'recipe_link'),
						fa('fa-asterisk')." Modifiers"=>array('href'=>'#modifiers','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'menu/modifier_load/','id'=>'modifier_link'),
						fa('fa-picture-o')." Image Upload"=>array('href'=>'#image','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'menu/upload_image_load/','id'=>'image_link'),
					);
					$CI->make->hidden('menu_id',$menu_id);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody(array('style'=>'min-height:202px;'));
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'recipe','class'=>'tab-pane'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'modifiers','class'=>'tab-pane'));
						$CI->make->eTabPane();
						$CI->make->sTabPane(array('id'=>'image','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function menuImagesLoad($menu_id=null,$res=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/images_db",array('id'=>'images_form','enctype'=>'multipart/form-data'));
			$CI->make->hidden('form_menu_id',$menu_id);
			$CI->make->sDivRow();
				$img = base_url().'img/no_image.jpg';
				if(iSetObj($res,'img_path') != ""){					
					$img = base_url().$res->img_path;
				}
				$CI->make->sDivCol(12,'center');
					$CI->make->img($img,array('class'=>'media-object thumbnail','id'=>'target','style'=>'max-height:220px;margin:0 auto;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDiv(array('style'=>'margin-top:10px;'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(12,'center');
					$CI->make->button(fa('fa-save').' Save Image',array('id'=>'save-image'),'primary');
				$CI->make->eDivCol();
		    $CI->make->eDivRow();
	    $CI->make->eDiv();
	return $CI->make->code();
}
function menuDetailsLoad($menu=null,$menu_id=null){
	$CI =& get_instance();
		$CI->make->sForm("menu/details_db",array('id'=>'details_form'));
			$CI->make->hidden('form_menu_id',$menu_id);
			$CI->make->sDivRow();
				$CI->make->sDivCol(2);
					$CI->make->input('Code','menu_code',iSetObj($menu,'menu_code'),'Type Code',array('class'=>'rOkay','maxlength'=>'15'));
					$CI->make->menuCategoriesDrop('Category','menu_cat_id',iSetObj($menu,'menu_cat_id'),'Select Category',array('class'=>'rOkay'));
					$CI->make->menuSubCategoriesDrop('Sub Category','menu_sub_cat_id',iSetObj($menu,'menu_sub_cat_id'),'Select Sub Category',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->input('Barcode','menu_barcode',iSetObj($menu,'menu_barcode'),'Type Barcode',array('class'=>'rOkay'));
					$CI->make->menuSchedulesDrop('Schedule','menu_sched_id',iSetObj($menu,'menu_sched_id'),'Select Schedule',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->input('Short Description','menu_name',iSetObj($menu,'menu_name'),'Type Short Desc',array('class'=>'rOkay','maxlength'=>20));
					$CI->make->input('Price','cost',iSetObj($menu,'cost'),'Price',array('class'=>'rOkay','style'=>'width:85px;'));
						// $CI->make->sDivCol(4);
						// 	$CI->make->input('Price','cost',iSetObj($menu,'cost'),'Price',array('class'=>'rOkay','style'=>'width:85px;'));
						// $CI->make->eDivCol();
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->input('Description','menu_short_desc',iSetObj($menu,'menu_short_desc'),'Type Name',array('class'=>'rOkay','maxlength'=>'50'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(4);
							$CI->make->inactiveDrop('Is Tax Exempt','no_tax',(iSetObj($menu,'no_tax')),null,array('style'=>'width:85px;'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							// $CI->make->inactiveDrop('Inactive','inactive',iSetObj($menu,'inactive'),null,array('style'=>'width:85px;'));
							$CI->make->inactiveDrop('Free','free',(iSetObj($menu,'free')),null,array('style'=>'width:85px;'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->inactiveDrop('Inactive','inactive',iSetObj($menu,'inactive'),null,array('style'=>'width:85px;'));
						$CI->make->eDivCol();
			    	$CI->make->eDivRow();
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(2,'left',4);
				$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-menu'),'primary');
			$CI->make->eDivCol();
			$CI->make->sDivCol(2,'left');
				$CI->make->button(fa('fa-save').' Save As New',array('id'=>'save-new-menu'),'success');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function menuRecipeLoad($menu_id,$recipe=null,$det=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol(4);
			$CI->make->sForm('menu/recipe_details_db',array('id'=>'recipe-details-form'));
				$CI->make->hidden('menu-id-hid',$menu_id);
				$CI->make->sDivRow();
					$CI->make->sDivCol();
						$CI->make->input('Search Item','item-search',null,'Search for item',array('search-url'=>'menu/recipe_search_item'),'',fa('fa-search'));
						// $CI->make->input('Unit of Measurement','item-uom',null,'',array('readOnly'=>'readOnly'));
						$uomTxt = $CI->make->span('&nbsp;&nbsp;',array('return'=>true,'id'=>'uom-txt'));
						$CI->make->input('Cost','item-cost',null,null,array('readOnly'=>'readOnly'));
						$CI->make->input('Quantity','qty',null,null,array(),'',$uomTxt);
						$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select an item'));
						$CI->make->hidden('item-uom-hid',0);
						$CI->make->button(fa('fa-plus').' Add item to recipe',array('id'=>'add-btn'),'primary btn-block');
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eForm();
		$CI->make->eDivCol();
    	$CI->make->sDivCol(8);
    		$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    					$CI->make->sRow();
	    					$CI->make->th('Item');
	    					$CI->make->th('UOM');
	    					$CI->make->th('Unit Price');
	    					$CI->make->th('Quantity');
	    					$CI->make->th('Line Total');
	    					$CI->make->th();
	    				$CI->make->eRow();
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->recipe_id));
    							$CI->make->td($val->item_name);
    							$CI->make->td($val->uom);
    							$CI->make->td($val->item_cost);
    							$CI->make->td($val->qty);
    							$CI->make->td(num($val->item_cost * $val->qty));
    							$a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$val->recipe_id,'ref'=>$val->recipe_id,'class'=>'del-item','return'=>true));
            					$CI->make->td($a);
    						$CI->make->eRow();
    					}
    			$CI->make->eTable();
    		$CI->make->eDiv();
    	$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow(array("style"=>"margin-top:20px;"));
		$CI->make->sDivCol(4,'left','8');
			$pop = $CI->make->A(fa('fa-save fa-fw '),'#',array('id'=>'override-price','return'=>true));
			$sell_price = iSetObj((!empty($det[0]) ? $det[0] : null),'menu_cost');
			$CI->make->input('Selling Price','total',num($sell_price),null,array(),null,$pop);
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}
function menuModifierLoad($menu_id,$det = null)
{
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol(4);
			$CI->make->sForm('menu/menu_modifier_db',array('id'=>'menu-modifier-form'));
				$CI->make->hidden('menu-id-hid',$menu_id);
				$CI->make->sDivRow();
					$CI->make->sDivCol();
						$CI->make->input('Search Item','item-search',null,'Search for item',array('search-url'=>'menu/modifier_search_item'),'',fa('fa-plus'));
						$CI->make->hidden('mod-group-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select a modifier'));
						// $CI->make->button(fa('fa-plus').' Add Modifier Group',array('id'=>'add-btn'),'primary btn-block');
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eForm();
		$CI->make->eDivCol();
		$CI->make->sDivCol(4);
			$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:23px'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    					$CI->make->sRow();
	    					$CI->make->th('Modifier Group',array('style'=>'text-align:center'));
	    					$CI->make->th();
	    				$CI->make->eRow();
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow(array('id'=>'row-'.$val->id));
    							$CI->make->td(fa('fa-asterisk')." ".$val->mod_group_name);
    							$a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$val->id,'ref'=>$val->id,'class'=>'del-item','return'=>true));
            					$CI->make->td($a);
    						$CI->make->eRow();
    					}
    			$CI->make->eTable();
    		$CI->make->eDiv();
		$CI->make->eDivCol();
	$CI->make->eDivRow();

	return $CI->make->code();
}

function menuRecipeForm($menu_id=null,$recipe=null,$det=array())
{
	$CI =& get_instance();

	$CI->make->sDivRow(array('style'=>'margin-bottom:10px'));
		$CI->make->sDivCol(12,'right');
			$CI->make->A(" ".fa('fa-reply')." Go back",base_url().'menu/form/'.$menu_id,array('class'=>'btn btn-default'));
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxHead();
					$CI->make->boxTitle(4,fa('fa-archive').' Recipe Details');
				$CI->make->eBoxHead();
				$CI->make->sBoxBody();
					$CI->make->sForm('menu/recipe_details_db',array('id'=>'recipe-details-form'));
						$CI->make->hidden('recipe-id-hid',iSetObj($recipe,'recipe_id'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4);
								$CI->make->input(null,'item-search',null,'Search for item',array('search-url'=>'menu/search_items','add-data'=>'menu_id='+$menu_id));
								$CI->make->hidden('item-id-hid',null,array('class'=>'rOkay','ro-msg'=>'Please select an item'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->H(4,'',array('id'=>'item-uom'));
								$CI->make->hidden('item-uom-hid',0);
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->H(4,'0.00',array('id'=>'item-price'));
								$CI->make->hidden('item-price-hid',0);
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->input(null,'qty',null,'Add quantity',array('class'=>'rOkay','ro-msg'=>'Please add quantity'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-plus').' Add item to recipe',array('id'=>'add-btn'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
    $CI->make->eDivRow();
    $CI->make->sDivRow();
    	$CI->make->sDivCol();
    		$CI->make->sDiv(array('class'=>'table-responsive'));
    			$CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
    				$CI->make->sTablehead();
    					$CI->make->sRow();
	    					$CI->make->th('Item');
	    					$CI->make->th('UOM');
	    					$CI->make->th('Unit Price');
	    					$CI->make->th('Quantity');
	    					$CI->make->th('Line Total');
	    				$CI->make->eRow();
    				$CI->make->eTableHead();
    				$CI->make->sTableBody();
    					$total = 0;
    					foreach ($det as $val) {
    						$CI->make->sRow();
    							$CI->make->td();
    						$CI->make->eRow();
    					}
    				$CI->make->eTableBody();
    			$CI->make->eTable();
    		$CI->make->eDiv();
    	$CI->make->eDivCol();
    $CI->make->eDivRow();


	return $CI->make->code();
}

function makeMenuCategoriesForm($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/categories_form_db",array('id'=>'categories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_cat_id',iSetObj($cat,'menu_cat_id'));
				$CI->make->input('Name','menu_cat_name',iSetObj($cat,'menu_cat_name'),'Type Category Name',array('class'=>'rOkay'));
				$CI->make->menuSchedulesDrop('Default Schedule','menu_sched_id',iSetObj($cat,'menu_sched_id'),'Select Schedule',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
function makeMenuSubCategoriesForm($cat=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(5);
				$CI->make->hidden('menu_sub_cat_id',iSetObj($cat,'menu_sub_cat_id'));
				$CI->make->input('Name','menu_sub_cat_name',iSetObj($cat,'menu_sub_cat_name'),'Type Sub Category Name',array('class'=>'rOkay'));
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
	$CI->make->eForm();
	return $CI->make->code();
}
function makeMenuSchedulesForm($cat=array(),$dets=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/menu/menu_sched_db",array('id'=>'schedules_form'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(6);
				$CI->make->hidden('menu_sched_id',iSetObj($cat,'menu_sched_id'));
				$CI->make->input('Description','desc',iSetObj($cat,'desc'),'Type Description',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
    $CI->make->eForm();
	$CI->make->sForm("dine/menu/menu_sched_details_db",array('id'=>'schedules_details_form'));
    	$CI->make->sDivRow();
            $CI->make->sDivCol(3);
            	// $CI->make->hidden('menu_sched_id',iSetObj($cat,'menu_sched_id'));
            	$CI->make->hidden('sched_id',iSetObj($cat,'menu_sched_id'));
                $CI->make->time('Time On','time_on',null,'Time On');
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
                $CI->make->time('Time Off','time_off',null,'Time Off');
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
                $CI->make->dayDrop('Day','day',null,'',array('style'=>'width: inherit;'));
            $CI->make->eDivCol();
            $CI->make->sDivCol(3);
                $CI->make->button(fa('fa-plus').' Add Schedule',array('id'=>'add-schedule','style'=>'margin-top:23px;'),'primary');
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        $CI->make->sDivRow();
            $CI->make->sDivCol();
                $CI->make->sDiv(array('class'=>'table-responsive'));
                    $CI->make->sTable(array('class'=>'table table-striped','id'=>'details-tbl'));
                        $CI->make->sRow();
                            // $CI->make->th('DAY');
                            $CI->make->th('DAY',array('style'=>'width:60px;'));
                            $CI->make->th('TIME ON',array('style'=>'width:60px;'));
                            $CI->make->th('TIME OFF',array('style'=>'width:60px;'));
                            $CI->make->th('&nbsp;',array('style'=>'width:40px;'));
                        $CI->make->eRow();
                        $total = 0;
                        // echo var_dump($dets);
                        if(count($dets) > 0){
                            foreach ($dets as $res) {
                                $CI->make->sRow(array('id'=>'row-'.$res->id));
                                    $CI->make->td(date('l',strtotime($res->day)));
                                    $CI->make->td(date('h:i A',strtotime($res->time_on)));
                                    $CI->make->td(date('h:i A',strtotime($res->time_off)));
                                    $a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-sched-'.$res->id,'class'=>'del-sched','ref'=>$res->id,'return'=>true));
                                    $CI->make->td($a);
                                $CI->make->eRow();
                            //     $total += $price * $res->qty;
                            }
                        }
                    $CI->make->eTable();
                $CI->make->eDiv();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
	$CI->make->eForm();

	return $CI->make->code();
}
?>