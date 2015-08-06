<?php
function makeRestaurantPage($list=""){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol();
				$CI->make->sBox('primary');
                    $CI->make->sBoxBody();
                    	$CI->make->sDivRow();
							$CI->make->sDivCol(12,'right');
								 $CI->make->A(fa('fa-plus').' Add New Restaurant',base_url().'restaurants/setup',array('class'=>'btn btn-primary'));
								 // $CI->make->A(fa('fa-plus').' Add New Restaurant',base_url().'branches/branch',array('class'=>'btn btn-primary'));
							$CI->make->eDivCol();
                    	$CI->make->eDivRow();
                    	$CI->make->sDivRow();
							$CI->make->sDivCol();
								$th = array('Restaurant Name'=>'',
										'Restaurant Type'=>'',
										' '=>array('width'=>'12%','align'=>'right'));
								$rows = array();
								foreach($list as $v){
								$links = "";
									// $icon = "fa-users fa-lg fa-fw";
									// $url[] = '<a href="restaurant/manage_users/'.$v->res_id.'"><i class="fa '.$icon.'"></i></a>';
									// $icon = "fa-bars";
									// $url[] = '<a href="branches/branch/'.$v->res_id.'"><i class="fa '.$icon.'"></i></a>';
									// $icon = "fa-edit";
									// $url[] = '<a href="restaurant/manage_users/"'.$v->res_id.'><i class="fa '.$icon.'"></i></a>';
									// $links .= $CI->make->A(fa('fa-group fa-2x fa-fw'),base_url().'restaurants/manage_users'.$v->res_id,array("return"=>true));
									$links .= $CI->make->A(fa('fa-pencil fa-2x fa-fw'),base_url().'restaurants/setup/'.$v->res_id,array("return"=>true));
									$links .= $CI->make->A(fa('fa-home fa-2x fa-fw'),base_url().'branches/branch/'.$v->res_id,array("return"=>true));
									$links .= $CI->make->A(fa('fa-book fa-2x fa-fw'),base_url().'menu/manage_items/'.$v->res_id,array("return"=>true));
									// $links .= $CI->make->A(fa('fa-bars fa-lg fa-fw'),base_url().'branches/branch'.$v->res_id,'');
									$rows[] = array($v->res_name,
												  $v->type_name,
												  $links
										);
								}
								// $rows = array(
								// 	"1" => array("1","81230",'10021199','Report System Error via Coordination with BAM','smaca_sf@yahoo.com','48 hrs','2014-05-11 11:52:34','in progress','<a href="#"><i class="fa fa-search"></i></a>',''),
								// );
								$CI->make->listLayout($th,$rows);
							$CI->make->eDivCol();
                    	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function makeRestaurantForm($branches=array(),$res_id=null){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->hidden('res_id',$res_id);
			$CI->make->sDivCol();
				$CI->make->sTab();
					$tabs = array(
						fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'restaurants/setup_load/','id'=>'details_link'),
						fa('fa-credit-card')." Taxes"=>array('href'=>'#taxes','class'=>'tab_link load-tab','load'=>'restaurants/tax_load/','id'=>'taxes_link'),
						fa('fa-gift')." Discounts"=>array('href'=>'#discounts','class'=>'tab_link load-tab','load'=>'restaurants/discount_load/','id'=>'discounts_link')
					);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'taxes','class'=>'tab-pane'));
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'discounts','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function displayDetailsPerResto($resto=null,$res_id=null){
	$CI =& get_instance();
		$CI->make->sForm("restaurants/resto_details_db",array('id'=>'resto_details_form','enctype'=>'multipart/form-data'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(2);
					$CI->make->sDiv(array('class'=>'media'));
						$thumb = base_url().'img/noimage.png';
						if(iSetObj($resto,'image')  != ""){
							// $thumb = blob2Image($resto->res_logo);
							$thumb = base_url().'uploads/'.$res_id.'/'.iSetObj($resto,'image');
						}
						$CI->make->img($thumb,array('class'=>'media-object','id'=>'target','style'=>'height:160px;;width:100%;'));
						$CI->make->file('fileUpload',array('style'=>'display:none;'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->hidden('res_id',$res_id);
					$CI->make->input('Code','res_code',iSetObj($resto,'res_code'),'Restaurant Code',array());
					$CI->make->input('Name','res_name',iSetObj($resto,'res_name'),'Restaurant Name',array());
					$CI->make->restoTypeDrop('Type','type_id',iSetObj($resto,'type_id'),'Select Restaurant Type',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(5);
					$CI->make->textarea('Description','res_desc',iSetObj($resto,'res_desc'),'Description',array());
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(3,'left',4);
				$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function displayTaxPerResto($resto=null,$res_id=null){
	$CI =& get_instance();
		// $CI->make->sForm("branches/branch_details_db",array('id'=>'branch_form'));
		$CI->make->sForm("restaurants/resto_tax_db",array('id'=>'resto_tax_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('resID',$res_id);
					$CI->make->input('Name','name',null,'Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->input('Rate','rate',null,'Tax Rate',array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->button(fa('fa-plus').' Add Tax',array('id'=>'add-tax','style'=>'margin-top:23px;'),'primary');
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->sUl(array('class'=>'vertical-list','id'=>'staff-list'));
			    	if(count($resto) > 0){
						foreach ($resto as $res) {
								$CI->make->li(
									$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
									$CI->make->span($res->name,array('class'=>'text','return'=>true))." ".
									$CI->make->span($res->rate.'%',array('class'=>'label label-success li-info','return'=>true))." ".
									$CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-tax','id'=>'del-tax-'.$res->tax_id,'ref'=>$res->tax_id))
								);
						}
					}else{
						$CI->make->li(
										$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
										$CI->make->span("No Tax inclusive to this restaurant.",array('class'=>'text','return'=>true))." ".
										$CI->make->span("",array('class'=>'label label-success li-info','return'=>true)),
										array('class'=>'no-tax')
									);
					}
					$CI->make->eUl();
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function displayDiscountPerResto($resto=null,$res_id=null){
	$CI =& get_instance();
		// $CI->make->sForm("branches/branch_details_db",array('id'=>'branch_form'));
		$CI->make->sForm("restaurants/resto_disc_db",array('id'=>'resto_disc_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(2);
					$CI->make->hidden('ressID',$res_id);
					$CI->make->hidden('disc_id',$res_id);
					$CI->make->input('Discount Code','code',null,'Code',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->input('Discount Name','name',null,'Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(2);
					$CI->make->input('Rate','rate',null,'Discount Rate',array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->button(fa('fa-plus').' Add Discount',array('id'=>'add-disc','style'=>'margin-top:23px;'),'primary');
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->sUl(array('class'=>'vertical-list','id'=>'disc-list'));
			    	if(count($resto) > 0){
						foreach ($resto as $res) {
								$CI->make->li(
									$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
									$CI->make->span($res->disc_name,array('class'=>'text','return'=>true))." ".
									$CI->make->span($res->disc_rate.'%',array('class'=>'label label-success li-info','return'=>true))." ".
									$CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-disc','id'=>'del-disc-'.$res->disc_id,'ref'=>$res->disc_id))
								);
						}
					}else{
						$CI->make->li(
										$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
										$CI->make->span("No Discount yet for this restaurant.",array('class'=>'text','return'=>true))." ".
										$CI->make->span("",array('class'=>'label label-success li-info','return'=>true)),
										array('class'=>'no-tax')
									);
					}
					$CI->make->eUl();
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
?>