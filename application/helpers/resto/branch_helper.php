<?php
function makeBranchesPage($branches=array(),$res_id=null,$map=null){
	$CI =& get_instance();

		$CI->make->sDivRow();
			$CI->make->sDivCol(4,'right',6);
				$options = array();
				$options['Add New Branch'] = "";
				$ctr = 1;
				$selected = "";
				foreach ($branches as $res) {
					if($ctr == 1)
						$selected = $res->branch_id;
					$options[$res->branch_name] = $res->branch_id;
					$ctr++;
				}
				$CI->make->select(null,'branch-drop',$options,$selected,array());
			$CI->make->eDivCol();
			$CI->make->sDivCol(2,'left');
				$CI->make->button(fa('fa-plus').' Create New Branch',array('id'=>'add-new-branch'),'primary');
				$CI->make->hidden('res_id',$res_id);
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow();
			$CI->make->sDivCol();
				$CI->make->sTab();
					$tabs = array(
						fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'branches/details_load/','id'=>'details_link'),
						fa('fa-group')." Staffs"=>array('href'=>'#staff','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'branches/staffs_load/','id'=>'staff_link'),
						fa('fa-tablet')." Tables"=>array('href'=>'#tables','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'branches/tables_load/','id'=>'tables_link'),
						fa('fa-book')." Menu"=>array('href'=>'#menu','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'branches/menu_load/','id'=>'menu_link')
					);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody();
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'staff','class'=>'tab-pane'));
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'tables','class'=>'tab-pane'));
						$CI->make->eTabPane();

						$CI->make->sTabPane(array('id'=>'menu','class'=>'tab-pane'));
						$CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
		$CI->make->sDivRow();
			// echo $map['js'];
			// echo $map['disp'];
			// $CI->make->append($map['js'].$map['html']);
			// $CI->make->sDivCol(12,"left",null,array("id"=>"map","style"=>"height:100px;"));
			// $CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function makeDetailsLoad($branch=null,$res_id=null,$map=null){
	$CI =& get_instance();
		$CI->make->sForm("branches/branch_details_db",array('id'=>'branch_form'));
			// $CI->make->sDivRow();
			// 	$CI->make->sDivCol(4);
			// 		$CI->make->append($map['js'].$map['disp']);
			// 	$CI->make->eDivCol();
	    	// $CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('branch_id',iSetObj($branch,'branch_id'));
					$CI->make->hidden('res_id',$res_id);
					$CI->make->input('Branch Name','branch_name',iSetObj($branch,'branch_name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(6);
					// $CI->make->input('Branch Description','branch_desc',iSetObj($branch,'branch_desc'),'Type Description',array());
					$CI->make->currenciesDrop('Currency','currency',iSetObj($branch,'currency'),'Select Currency',array('class'=>'rOkay'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol(10);
					$CI->make->input('Branch Description','branch_desc',iSetObj($branch,'branch_desc'),'Type Description',array());
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->input('Contact No.','contact_no',iSetObj($branch,'contact_no'),null,array('class'=>'rOkay'),fa('fa-phone fa-fw'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(6);
					// $CI->make->input('Address','address',iSetObj($branch,'address'),'Address',array());
					$CI->make->input('Delivery No.','delivery_no',iSetObj($branch,'delivery_no'),null,array('class'=>'rOkay'),fa('fa-truck fa-fw'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
	    	$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					// $CI->make->input('Delivery No.','delivery_no',iSetObj($branch,'delivery_no'),null,array('class'=>'rOkay'),fa('fa-truck fa-fw'));
					// $CI->make->currenciesDrop('Currency','currency',iSetObj($branch,'currency'),'Select Currency',array('class'=>'rOkay'));
					$CI->make->input('Address','address',iSetObj($branch,'address'),'Address',array());
				$CI->make->eDivCol();
				$CI->make->sDivCol(6);
					$CI->make->input('Base Location','base_loc',iSetObj($branch,'base_location'),'Type Base Location',array());
					// $CI->make->sDivCol(12,"left",null,array("id"=>"gmap","style"=>"height:200px;"));
					// $CI->make->eDivCol();
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
			$CI->make->sDivRow();
				$CI->make->sDivCol();
				$CI->make->sDivCol(10,"left",null,array("id"=>"gmap","style"=>"height:200px;margin-left:5px;width:82%;margin-bottom:10px;"));
					$CI->make->eDivCol();
	    	$CI->make->eDivRow();
			// $CI->make->sDivRow();
			// 	$CI->make->sDivCol(4);
			// 		// echo var_dump($map);
			// 		// $CI->make->append($map['js'].$map['html']);

			// 		// $CI->make->sDivCol(12,"left",null,array("id"=>"mapCanvas","style"=>"height:100px;"));
			// 		$CI->make->sDivCol(12,"left",null,array("id"=>"gmap","style"=>"height:100px;"));
			// 		// $CI->make->sDivCol(12,"left",null,array("id"=>"gmap-dropdown","style"=>"height:100px;"));
			// 		// $CI->make->sDivCol(12,"left",null,array("id"=>"gmap-list","style"=>"height:100px;"));
			// 		// $CI->make->eDivCol();
			// 	$CI->make->eDivCol();
			// 	// 	$CI->make->sDivCol(12,"left",null,array("id"=>"gmap-list","style"=>"height:100px;"));
			// 	// $CI->make->eDivCol();
			// $CI->make->eDivRow();
		$CI->make->eForm();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4,'left',4);
				$CI->make->button(fa('fa-save').' Save Details',array('id'=>'save-branch'),'primary');
				// $CI->make->button(fa('fa-save').' Save As New Branch',array('id'=>'save-as-new-branch','style'=>'margin-left:10px;'),'info');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function makeStaffLoad($branch_id=null,$staffs=array()){
	$CI =& get_instance();
		$CI->make->sForm("branches/branch_staffs_db",array('id'=>'staff_form'));
			$CI->make->hidden('branch_id',$branch_id);
			$CI->make->hidden('staff-access',null);
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->userDrop('User','user',null,'Select Staff User',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(3);
					$CI->make->restoStaffDrop('Type','type',null,'Select Staff Type',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(4);
					$CI->make->button(fa('fa-plus').' Add Staff',array('id'=>'add-staff','style'=>'margin-top:23px;'),'primary');
				$CI->make->eDivCol();
		    $CI->make->eDivRow();
	    $CI->make->eForm();
	    $CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->sUl(array('class'=>'vertical-list','id'=>'staff-list'));
					if(count($staffs) > 0){
						foreach ($staffs as $res) {
								$CI->make->li(
									$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
									$CI->make->span($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,array('class'=>'text','return'=>true))." ".
									$CI->make->span($res->staff_name,array('class'=>'label label-success li-info','return'=>true))." ".
									$CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-staff','id'=>'del-staff-'.$res->id,'ref'=>$res->id))
								);
						}
					}
					else{
						$CI->make->li(
										$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
										$CI->make->span("No Staffs found.",array('class'=>'text','return'=>true))." ".
										$CI->make->span("",array('class'=>'label label-success li-info','return'=>true)),
										array('class'=>'no-staff')
									);
					}
				$CI->make->eUl();
			$CI->make->eDivCol();
	    $CI->make->eDivRow();

	return $CI->make->code();
}
function makeTablesLoad($branch_id=null,$branch=null,$res_id=null){
	$CI =& get_instance();
	    $CI->make->sDivRow();
			$CI->make->sDivCol();
			$btnMsg = "Add an Image";
	    	if($branch->image != null)
				$btnMsg = "Change Image";
				$CI->make->A(fa('fa-picture-o').' '.$btnMsg,'branches/upload_image_form/'.$branch_id,array(
															'id'=>'change-img',
															'rata-title'=>'Restaurant Seating Image Upload',
															'rata-pass'=>'branches/upload_table_image',
															'rata-form'=>'upload_image_form',
															'class'=>'btn btn-primary'
														));
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	    $CI->make->sDivRow();
		    	// $CI->make->hidden('imgSrc',base_url().'img/notablelayout.jpg');
	    	if($branch->image != null)
		    	$CI->make->hidden('imgSrc',base_url().'uploads/'.$res_id.'/layout/'.$branch->image);
	    	else
		    	$CI->make->hidden('imgSrc',null);

	    	$CI->make->hidden('branch_id',$branch_id);
			$CI->make->sDivCol(12,'left',0,array('id'=>'imgCon'));
				
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
	return $CI->make->code();
}
function makeTableForm($det=null,$branch_id=null){
	$CI =& get_instance();
		$CI->make->sForm("branches/tables_db",array('id'=>'table_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(2);
					$CI->make->hidden('tbl_id',iSetObj($det,'tbl_id'));
					$CI->make->hidden('branch_id',$branch_id);
					$CI->make->input('Capacity','capacity',iSetObj($det,'capacity'),'Type Capacity',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(8);
					$CI->make->input('Name','name',iSetObj($det,'name'),'Type Name',array('class'=>'rOkay'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();	
	return $CI->make->code();
}
function makeTableUploadForm($det=null,$branch_id=null){
	$CI =& get_instance();
		$CI->make->sForm("branches/upload_table_image",array('id'=>'upload_image_form','enctype'=>'multipart/form-data'));
			$CI->make->hidden('branch_id',$branch_id);
			$CI->make->hidden('res_id',$det->res_id);
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
						$thumb = base_url().'uploads/'.$det->res_id.'/layout/'.iSetObj($det,'image');
					}
					$CI->make->img($thumb,array('class'=>'media-object thumbnail','id'=>'target','style'=>'height:220px;'));
					$CI->make->file('fileUpload',array('style'=>'display:none;'));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->eForm();	
	return $CI->make->code();
}
function makeMenuLoad($branch_id=null,$items=array(),$combos=array(),$item_list=array(),$combo_list=array()){
	$CI =& get_instance();
	    $CI->make->sDivRow();
			$CI->make->sDivCol(4);
				$CI->make->sBox('warning',array('class'=>'box-solid'));
                    $CI->make->sBoxHead();
                    	$CI->make->boxTitle('Restaurant Items');
                    $CI->make->eBoxHead();
                    $CI->make->sBoxBody();
                    	$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sUl(array('class'=>'vertical-list','id'=>'item-list'));
									if(count($items) > 0){
										foreach ($items as $res) {
												$CI->make->li(
													$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
													$CI->make->A($res->code." ".$res->name,'#',array('return'=>true,'class'=>'view-item text','id'=>'view-item-'.$res->item_id,'ref'=>$res->item_id))." ".
													$CI->make->A(fa('fa-lg fa-plus'),'branches/branch_menu_item_form/'.$branch_id."/".$res->item_id,array('return'=>true,
																	'class'=>'add-item',
																	'rata-title'=>'Add Item '.$res->code." ".$res->name,
																	'rata-pass'=>'branches/branch_menu_item_db',
																	'rata-form'=>'menu_item_form',
																	'ref'=>$res->item_id
																))
												);
										}
									}
									else{
										$CI->make->li(
														$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
														$CI->make->span("No Items found.",array('class'=>'text','return'=>true)),
														array('class'=>'no-items')
													);
									}
								$CI->make->eUl();
							$CI->make->eDivCol();
                    	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
                $CI->make->sBox('success',array('class'=>'box-solid'));
                    $CI->make->sBoxHead();
                    	$CI->make->boxTitle('Restaurant Combos');
                    $CI->make->eBoxHead();
                    $CI->make->sBoxBody();
                    	$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sUl(array('class'=>'vertical-list','id'=>'combo-list'));
									if(count($combos) > 0){
										foreach ($combos as $res) {
												$CI->make->li(
													$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
													$CI->make->A($res->combo_code." ".$res->combo_name,'#',array('return'=>true,'class'=>'view-combo text','id'=>'view-combo-'.$res->combo_id,'ref'=>$res->combo_id))." ".
													$CI->make->A(fa('fa-lg fa-plus'),'branches/branch_menu_combo_form/'.$branch_id."/".$res->combo_id,array('return'=>true,
																	'class'=>'add-combo',
																	'rata-title'=>'Update Combo '.$res->combo_code." ".$res->combo_name,
						                                            'rata-pass'=>'branches/branch_menu_combo_db',
						                                            'rata-form'=>'menu_combo_form',
																	'ref'=>$res->combo_id
																))
												);
										}
									}
									else{
										$CI->make->li(
														$CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
														$CI->make->span("No Combos found.",array('class'=>'text','return'=>true)),
														array('class'=>'no-items')
													);
									}
								$CI->make->eUl();
							$CI->make->eDivCol();
                    	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();
			#MENU LIST
			$CI->make->sDivCol(8,'left');
				$CI->make->sBox('primary',array('class'=>'box-solid'));
                    $CI->make->sBoxHead();
                    	$CI->make->boxTitle('Branch Menu List');
                    $CI->make->eBoxHead();
                    $CI->make->sBoxBody();
                    	$CI->make->sDivRow();
							$CI->make->sDivCol();
								$CI->make->sDivRow();
									$CI->make->sDivCol();
										$CI->make->H(4,"Items");
									$CI->make->eDivCol();
						    	$CI->make->eDivRow();
								$CI->make->sDivRow(array("id"=>'branch-menu-item-list'));
									foreach ($item_list as $res) {
										$thumb = base_url().'img/noimage.png';
										if($res->img  != ""){
											$thumb = blob2Image($res->img);
										}
										$CI->make->sDivCol(4);
											$CI->make->sDiv(array('class'=>'media'));
												$img = $CI->make->img($thumb,array('class'=>'media-object','return'=>true,'height'=>"60"));
												$CI->make->A($img,'branches/branch_menu_item_form/'.$branch_id."/".$res->item_id."/".$res->menu_item_id,array(
																'class'=>'add-item pull-left',
																'rata-title'=>'Update Item '.$res->item_code." ".$res->item_name,
																'rata-pass'=>'branches/branch_menu_item_db',
																'rata-form'=>'menu_item_form',
																'ref'=>$res->item_id
															));
												$CI->make->sDiv(array('class'=>'media-body'));
													$CI->make->H(5,strong($res->item_name),array('class'=>'media-object'));
													$CI->make->H(6,num($res->price),array('class'=>'media-object','id'=>'branch-item-price-'.$res->menu_item_id));
													$a = $CI->make->A('[Remove]','#',array('return'=>true,'class'=>'del-menu-item','id'=>'del-menu-item-'.$res->menu_item_id,'ref'=>$res->menu_item_id));
													$CI->make->H(6,$a,array('class'=>'media-object'));
												$CI->make->eDiv();
											$CI->make->eDiv();
										$CI->make->eDivCol();
									}
						    	$CI->make->eDivRow();
							$CI->make->eDivCol();
							$CI->make->sDivCol(12);
								$CI->make->sDivRow();
									$CI->make->sDivCol();
										$CI->make->H(4,"Combos");
									$CI->make->eDivCol();
						    	$CI->make->eDivRow();
						    	$CI->make->sDivRow(array("id"=>'branch-menu-combo-list'));
									$thumb = base_url().'img/noimage.png';
									foreach ($combo_list as $res) {
										$thumb = base_url().'img/noimage.png';
										if($res->img  != ""){
											$thumb = blob2Image($res->img);
										}
										$CI->make->sDivCol(4);
					                        $CI->make->sDiv(array('class'=>'media'));
					                            $img = $CI->make->img($thumb,array('class'=>'media-object','return'=>true,'height'=>"60"));
					                            $CI->make->A($img,'branches/branch_menu_combo_form/'.$res->branch_id."/".$res->combo_id."/".$res->menu_combo_id,array(
					                                            'class'=>'add-combo pull-left',
					                                            'rata-title'=>'Update Combo '.$res->combo_code." ".$res->combo_name,
					                                            'rata-pass'=>'branches/branch_menu_combo_db',
					                                            'rata-form'=>'menu_combo_form',
					                                            'ref'=>$res->combo_id,
					                                            'id'=>'add-combo-'.$res->menu_combo_id
					                                        ));
					                            $CI->make->sDiv(array('class'=>'media-body'));
					                                // $sub = $CI->make->small('Something',array('return'=>true));
					                                $CI->make->H(5,strong($res->combo_name),array('class'=>'media-object'));
					                                $CI->make->H(6,num($res->selling_price),array('class'=>'media-object','id'=>'branch-combo-price-'.$res->menu_combo_id));
					                                $a = $CI->make->A('[Remove]','#',array('return'=>true,'class'=>'del-menu-combo','id'=>'del-menu-combo-'.$res->menu_combo_id,'ref'=>$res->menu_combo_id));
					                                $CI->make->H(6,$a,array('class'=>'media-object'));
					                            $CI->make->eDiv();
					                        $CI->make->eDiv();
					                    $CI->make->eDivCol();
									}
						    	$CI->make->eDivRow();

							$CI->make->eDivCol();

                    	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();

	    $CI->make->eDivRow();
	return $CI->make->code();
}
function makeMenuItemForm($branch_id=null,$item=null){
	$CI =& get_instance();
		$CI->make->sForm("branches/branch_menu_item_db",array('id'=>'menu_item_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('branch_id',$branch_id);
					$CI->make->hidden('menu_item_id',iSetObj($item,'menu_item_id'));
					$CI->make->hidden('item_id',iSetObj($item,'item_id'));
					$CI->make->input('Price','price',iSetObj($item,'price'),null,array('class'=>'rOkay'));
				$CI->make->eDivCol();
				if(iSetObj($item,'portion_price') != "" && $item->portion_price > 0){
					$CI->make->sDivCol(6);
						$CI->make->input('Portion Price','portion_price',iSetObj($item,'portion_price'),null,array('class'=>'rOkay'));
					$CI->make->eDivCol();
				}
	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
function makeMenuComboForm($branch_id=null,$combo=null){
	$CI =& get_instance();
		$CI->make->sForm("branches/branch_menu_combo_db",array('id'=>'menu_combo_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(4);
					$CI->make->hidden('branch_id',$branch_id);
					$CI->make->hidden('menu_combo_id',iSetObj($combo,'menu_combo_id'));
					$CI->make->hidden('combo_id',iSetObj($combo,'combo_id'));
					$CI->make->input('Selling Price','selling_price',iSetObj($combo,'selling_price'),null,array('class'=>'rOkay'));
				$CI->make->eDivCol();

	    	$CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}
?>