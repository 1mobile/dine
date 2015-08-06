<?php
function readHistoryList($list = array()){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array(
								'Read Date'=>'',
								'Type'=>'',
								'User'=>'',
								'OLD GT'=>'',
								'NEW GT'=>'',
								'From'=>'',
								'To'=>''
							);
							$rows = array();
							foreach ($list as $val) {
								$type = 'xread';
								if($val->read_type == 2)
									$type = 'zread';
								$user = null;
								if($val->read_type == 1)
									$user = ucwords(strtolower($val->fname.', '.$val->mname.' '.$val->lname.' '.$val->suffix));
								$rows[] = array(
									sql2Date($val->read_date),
									$type,
									$user,
									$val->old_total,
									$val->grand_total,
									date('M d, Y D  @ h:i:s A',strtotime($val->scope_from)),
									date('M d, Y D  @ h:i:s A',strtotime($val->scope_to)),
								);
							}
							$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:10px;'));
								$CI->make->sTable(array('class'=>'table table-bordered table-striped dt-tbl'));
									$CI->make->append('<thead>');
									$CI->make->sRow();
										foreach ($th as $text => $opts) {
												$thParams = array();
												if(is_array($opts))
													$thParams = $opts;
												$CI->make->th($text,$thParams);
										}
									$CI->make->eRow();
									$CI->make->append('</thead>');
									$CI->make->append('<tbody>');
									foreach($rows as $cells){
										$CI->make->sRow();
											foreach ($cells as $val) {
												$CI->make->td($val);
											}
										$CI->make->eRow();
									}
									$CI->make->append('</tbody>');
								$CI->make->eTable();
							$CI->make->eDiv();
							// $CI->make->listLayout($th,$rows);
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}