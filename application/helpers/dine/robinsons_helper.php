<?php
function robFiles($list = array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								// 'Code'=>'',
								'File'=>'',
								'Date Created'=>'',
								'Date Last Sent'=>'',
								'Sent'=>'',
								''=>array('width'=>'10%','align'=>'right')
							);
							$rows = array();
							foreach ($list as $val) {
								$link = "";
								$link .= $CI->make->A(fa('fa-envelope fa-lg fa-fw'),base_url().'reads/send_to_rob_man/'.$val->id,array('return'=>'true'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								// $link .= $CI->make->A(fa('fa-penci fa-lg fa-fw'),base_url().'items/setup/'.$val->cust_id,array('return'=>'true','title'=>'Edit "'.$val->name.'"'));
								$rows[] = array(
									// $val->code,
									$val->code,
									sql2Date($val->date_created),
									sql2Date($val->last_update),
									($val->inactive == 0 ? 'Sent' : 'Not Sent'),
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
?>