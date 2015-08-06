<?php
function rolesForm($roles=null,$access=array(),$navs=array()){
	$CI =& get_instance();
	$CI->make->sForm("admin/roles_db",array('id'=>'roles_form'));
		$CI->make->hidden('role_id',iSetObj($roles,'id'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol(4);
				$CI->make->input('Name','role',iSetObj($roles,'role'),'Role Name',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(8);
				$CI->make->input('Description','description',iSetObj($roles,'description'),'Role Description',array());
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
    	$CI->make->sDivRow(array('style'=>'margin-lef:10px;margin-right:10px;'));
			$CI->make->sDivCol();
			foreach ($navs as $id => $nav) {
				if($nav['exclude'] == 0){	
					$CI->make->sBox('info',array('class'=>'box-solid'));
	                    $CI->make->sBoxHead();       
	                    	$check = false;
	                    	if(in_array($id, $access))
		                    	$check = true;
		                     
		                    $checkbox = $CI->make->checkbox($nav['title'],'roles[]',$id,array('return'=>true,'id'=>$id,'class'=>'check'),$check);
		                    $CI->make->boxTitle($checkbox);
	                    $CI->make->eBoxHead();
	                    if(is_array($nav['path'])){	
		                    $CI->make->sBoxBody();
								$CI->make->append(underRoles($nav['path'],$access,$id));		                    	
		                    $CI->make->eBoxBody();
	                	}
	                $CI->make->eBox();
				}
			}				
			$CI->make->eDivCol();
		$CI->make->eDivRow();



	$CI->make->eForm();
	return $CI->make->code();
}
function underRoles($nav=array(),$access=array(),$main=null){
	$CI =& get_instance();
	
	foreach ($nav as $id => $nv) {
		$CI->make->sDivRow(array('style'=>'margin-left:10px;'));
			$CI->make->sDivCol();
				$check = false;
            	if(in_array($id, $access))
                	$check = true;
				$CI->make->checkbox($nv['title'],'roles[]',$id,array('class'=>$main." check",'parent'=>$main,'id'=>$id),$check);
				if(is_array($nv['path'])){
					$CI->make->append(underRoles($nv['path'],$access,$main." ".$id." "));
				}
			$CI->make->eDivCol();
		$CI->make->eDivRow();	
	}
	
	return $CI->make->code();
}
function restartPage(){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol(6,'left',3);
			$CI->make->sBox('solid');
				$CI->make->sBoxBody();
					$CI->make->sDiv(array('style'=>'margin:10px;'));
						$CI->make->H(4,'Warning! this will remove all transactions and reset the POS!',array('class'=>'text-center','style'=>'color:red;'));
						$CI->make->button(fa('fa-refresh').' Restart POS',array('id'=>'restart-pos','class'=>'btn-block'),'danger');
					$CI->make->eDiv();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();	
	return $CI->make->code();
}

?>