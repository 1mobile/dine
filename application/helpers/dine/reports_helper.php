<?php
function reportsPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
	$CI->make->sDivRow();
		$CI->make->sDivCol(2);
			$buttons = array(
				"store-sales" => array('text'=>fa('fa-building-o fa-fw')." Store Sales Report",'url'=>'reports/store_sales_rep'),
				"x-read" => array('text'=>fa('fa-group fa-fw')." X Readings",'url'=>'reports/x_read_rep'),
				"z-read" => array('text'=>fa('fa-group fa-fw')." Z Readings",'url'=>'reports/z_read_rep'),
				"system-sales" => array('text'=>fa('fa-group fa-fw')." System Sales Report",'url'=>'reports/system_sales_rep'),
				"employee-sales" => array('text'=>fa('fa-newspaper-o')." Employee Sales Report",'url'=>'reports/employee_sales_rep'),
				"menu-sales" => array('text'=>fa('fa-suitcase')." Menu Item Report",'url'=>'reports/menu_sales_rep'),
				"daily-sales" => array('text'=>fa('fa-suitcase')." Daily Sales Report",'url'=>'reports/daily_sales_rep'),
				"monthly-sales" => array('text'=>fa('fa-suitcase')." Monthly Sales Report",'url'=>'reports/monthly_sales_rep'),
				// "fs-sales" => array('text'=>fa('fa-bullhorn fa-2x')."<br>Food Server Sales",'url'=>'reports/food_server_sales_rep'),
				"hourly-sales" => array('text'=>fa('fa-clock-o'). "Hourly Sales Report",'url'=>'reports/hourly_sales_rep'),
				"void-sales" => array('text'=>fa('fa-trash')." Void Sales Report",'url'=>'reports/void_sales_rep')
			);
			foreach ($buttons as $id => $opt) {
				$CI->make->button($opt['text'],array('id'=>$id,'url'=>$opt['url'],'class'=>'btn-manager-report btn-block manager-btn-teal'));
			}
		$CI->make->eDivCol();
		$CI->make->sDivCol(10);
			$CI->make->sDiv(array('style'=>'background-color:#015D56;width:100%;height:600px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						$CI->make->sDiv(array('id'=>'report-form-div'));
						$CI->make->eDiv();
					$CI->make->eDivCol();
					$CI->make->sDivCol(8);
						$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
						$CI->make->sDiv(array('style'=>'background-color:#ddd;height:500px;padding:10px;'));
							$CI->make->sDiv(array('id'=>'report-view-div','style'=>'margin:0 auto;position:relative;height:480px;overflow:hidden;width:300px;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis manager-btn-red-gray'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
function build_report(){
	$CI =& get_instance();
	$arg_list = func_get_args();
	// echo var_dump($arg_list);
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sDiv(array('style'=>'margin:10px;padding:10px;background-color:#fff;'));
				$CI->make->sForm(null,array('id'=>'report_form'));
					$CI->make->sDivRow();
						$CI->make->sDivCol();
						$sel = str_replace("_","-",$arg_list[0]);
						foreach ($arg_list[1] as $func => $func_args) {
							if (is_array($func_args)) {
								if (strpos($func, "-")) {
									$func_a = explode("-", $func);
									$func = $func_a[0];
								}
								$str = call_user_func_array(array(&$CI->make,$func), $func_args);
							}
							else
								$str = $func_args;
							$CI->make->sDiv(array('style'=>'margin-bottom:10px;'));
								$CI->make->append($str);
							$CI->make->eDiv();
						}
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol(6);
							$CI->make->button(
								fa('fa-share fa-lg fa-fw')." Submit",
								array(
									// 'class'=>'btn-block manager-btn-green no-raduis run_rep_btn',
									// 'id'=>'run-rep-'.$sel,
									'class'=>'btn-block manager-btn-green no-raduis report-submit-btn',
									'id' => 'run-rep-btn',
									'data-sel'=>$sel
								),
								$type='default');
						$CI->make->eDivCol();
						$CI->make->sDivCol(6);
							$CI->make->button(
							fa('fa-print fa-lg')." PDF Report",
							array(
								'class'=>'btn-block manager-btn-orange no-raduis report-submit-btn',
								'id'=>'pdf-rep-btn',
								'data-sel'=>$sel
							),
							$type='default');	
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					if($sel == 'display-mothly-sales' || $sel == 'display-daily-sales'){
						$CI->make->sDivRow();
						if(isset($arg_list[2]) && $arg_list[2] == 'excel')
							$CI->make->sDivCol(6);
						else
							$CI->make->sDivCol(12);
							$CI->make->button(
							fa('fa-print fa-lg')." Print Report",
							array(
								'class'=>'btn-block manager-btn-black no-raduis report-submit-btn',
								'id'=>'gen-rep-btn',
								'data-sel'=>$sel
							),
							$type='default');
						$CI->make->eDivCol();

						if(isset($arg_list[2]) && $arg_list[2] == 'excel'){
							$CI->make->sDivCol(6);
								$CI->make->button(
								fa('fa-print fa-lg')." Excel Report",
								array(
									'class'=>'btn-block manager-btn-teal no-raduis report-submit-btn',
									'id'=>'gen-excel-btn',
									'data-sel'=>$sel
								),
								$type='default');
							$CI->make->eDivCol();						
						}
					$CI->make->eDivRow();
					}else{
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->button(
								fa('fa-print fa-lg')." Print Report",
								array(
									'class'=>'btn-block manager-btn-black no-raduis report-submit-btn',
									'id'=>'gen-rep-btn',
									'data-sel'=>$sel
								),
								$type='default');
							$CI->make->eDivCol();
							// $CI->make->sDivCol(6);
							// 	$CI->make->button(
							// 	fa('fa-print fa-lg')." Excel Report",
							// 	array(
							// 		'class'=>'btn-block manager-btn-teal no-raduis report-submit-btn',
							// 		'id'=>'gen-excel-btn',
							// 		'data-sel'=>$sel
							// 	),
							// 	$type='default');
							// $CI->make->eDivCol();
						$CI->make->eDivRow();
					}
				$CI->make->eForm();
			$CI->make->eDiv();
		$CI->make->eDivCol();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function activity_logs_pg($list = array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('success');
				$CI->make->sBoxBody(array('class'=>'no-padding'));
					$CI->make->sDivRow();
						$CI->make->sDiv(array('style'=>'margin:10px;'));
							$CI->make->sForm("",array('id'=>'search-form'));
							$range = rangeWeek(phpNow());
							$CI->make->sDivCol(4,'left');
								$CI->make->input('Date range','daterange',sql2Date($range['start'])." to ". sql2Date($range['end']),'',array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(4,'left');
								$CI->make->userDrop('User','user');
							$CI->make->eDivCol();
							$CI->make->sDivCol(4,'right');
								$CI->make->sDiv(array('style'=>'margin-top:23px;'));
									$CI->make->A(fa('fa-download').' Download Excel',base_url().'reports/activity_logs_rep_excel',array('class'=>'excel-btn btn btn-success'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
							$CI->make->eForm();
						$CI->make->eDiv();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol();
							$CI->make->sDiv(array('class'=>'table-responsive','style'=>'margin-top:10px;'));
								$CI->make->sTable(array('class'=>'table table-hover table-bordered table-condensed','id'=>'details-tbl'));
									$th = array(
										'Datetime'=>'',
										'Type'=>'',
										'User'=>'',
										'Action'=>'',
										'Reference'=>'',
									);
									$CI->make->sRow();
										foreach ($th as $txt=>$params) {
											$CI->make->th($txt);
										}
									$CI->make->eRow();
									
									$rows = array();
									foreach ($list as $val) {
										$rows[] = array(
											$val->datetime,
											$val->type,
											$val->fname." ".$val->mname." ".$val->lname." ".$val->suffix,
											$val->action,
											$val->reference
										);
									}

										foreach ($rows as $rw) {
											$CI->make->sRow();
												foreach ($rw as $desc) {
														$CI->make->td($desc);
												}
											$CI->make->eRow();
										}
									
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
function salesRepUI(){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					//$CI->make->sForm("reprint/results",array('id'=>'search-form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4);
								$CI->make->input('Date Range','daterange',null,null,array('class'=>'rOkay daterangepicker','style'=>'position:initial;'),null,fa('fa-calendar'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-search').' Submit',array('style'=>'margin-top:24px;','id'=>'search-btn'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol(12);
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->button(fa('fa-print').' Print',array('id'=>'print-btn','class'=>'pull-right','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'),'success');
				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					$CI->make->sDiv(array('id'=>'print-div','style'=>'margin:0 auto;position:relative;width:310px;'));
						
					//$CI->make->append('asdfafasdfasdfsadf');
					$CI->make->eDiv();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function drawerCountUI(){
	$CI =& get_instance();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					//$CI->make->sForm("reprint/results",array('id'=>'search-form'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(3);
								$CI->make->date('Date','date',null,null,array('class'=>'rOkay','style'=>'position:initial;'),null,fa('fa-calendar'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(3);
								$CI->make->userDrop('User','user',null,null);
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-search').' Submit',array('style'=>'margin-top:24px;','id'=>'search-btn'),'primary');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eForm();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol(12);
			$CI->make->sBox('default',array('class'=>'box-solid'));
				$CI->make->sBoxHead();
					$CI->make->button(fa('fa-print').' Print',array('id'=>'print-btn','class'=>'pull-right','style'=>'margin-top:10px;margin-right:10px;margin-bottom:10px;'),'success');
				$CI->make->eBoxHead();
				$CI->make->sBoxBody(array('class'=>'bg-gray','style'=>'min-height:50px;'));
					$CI->make->sDiv(array('id'=>'print-div','style'=>'margin:0 auto;position:relative;width:310px;'));
						
					//$CI->make->append('asdfafasdfasdfsadf');
					$CI->make->eDiv();
				$CI->make->eBoxBody();
			$CI->make->eBox();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}