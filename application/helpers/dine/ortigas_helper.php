<?php
function ortigasPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		$buttons = array(
						 "daily-read-sales"	=> fa('fa-file-text fa-lg fa-fw')."<br> DAILY FILE READS",
						 // "hourly-sales"	=> fa('fa-clock-o fa-lg fa-fw')."<br> HOURLY SALES",
						 // "invoice-sales"=> fa('fa-file-text fa-lg fa-fw')."<br> INVOICE SALES",
						 "settings"		=> fa('fa-cogs fa-lg fa-fw')."<br> SETTINGS",
						 );
		$CI->make->sDivRow(array('style'=>'margin:0;'));
		// $CI->make->sDivCol(4);
		// 	$CI->make->button(fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",array('id'=>'day-report-btn','class'=>'btn-block manager-btn-green double'));
		// $CI->make->eDivCol();
			foreach ($buttons as $id => $text) {
					$CI->make->sDivCol(6);
						$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
					$CI->make->eDivCol();
			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	$CI->make->sBox('default',array('class'=>'box-solid'));
		$CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:535px;padding:0 0 10px 0;'));
			$CI->make->sDiv(array('id'=>'ortigas-content'));
			$CI->make->eDiv();
		$CI->make->eBoxBody();
	$CI->make->eBox();
	return $CI->make->code();
}
function dailyFileReadPage($list=array()){
	$CI =& get_instance();
		$CI->make->sDiv(array('style'=>'padding:10px;'));
			$CI->make->sDivRow();
				$CI->make->sDivCol();
					$CI->make->sDiv(array('style'=>'background-color:#fff;padding:10px;'));
						$th = array(
							// 'Code'=>'',
							'Read Date'=>'',
							'From'=>'',
							'to'=>'',
							'Hourly'=>array('width'=>'30px','align'=>'center'),
							'Daily'=>array('width'=>'30px','align'=>'center'),
							'Invoice'=>array('width'=>'30px','align'=>'cneter'),
							// ''=>array('width'=>'10%','align'=>'right')
						);
						$rows = array();
						foreach ($list as $val) {
							$link = "";
							$hourly = $CI->make->A(fa('fa-eye fa-2x fa-fw'),'ortigas/file_view/hourly?date='.$val->read_date,array('return'=>'true','class'=>'view_file','rata-title'=>'HOURLY FILE '.sql2Date($val->read_date)));
							$daily = $CI->make->A(fa('fa-eye fa-2x fa-fw'),'ortigas/file_view/daily?date='.$val->read_date,array('return'=>'true','class'=>'view_file','rata-title'=>'DAILY FILE '.sql2Date($val->read_date)));
							$invoice = $CI->make->A(fa('fa-eye fa-2x fa-fw'),'ortigas/file_view/invoice?date='.$val->read_date,array('return'=>'true','class'=>'view_file','rata-title'=>'INVOICE FILE '.sql2Date($val->read_date)));
							$rows[] = array(
								// $val->code,
								sql2Date($val->read_date),
								sql2DateTime($val->scope_from),
								sql2DateTime($val->scope_to),
								// ($val->inactive == 0 ? 'Sent' : 'Not Sent'),
								$hourly,
								$daily,
								$invoice
							);
						}
						$CI->make->listLayout($th,$rows);
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function ortigasPageOLD(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'div-no-spaces'));
		$buttons = array(
						 "daily-sales"	=> fa('fa-money fa-lg fa-fw')."<br> DAILY SALES",
						 "hourly-sales"	=> fa('fa-clock-o fa-lg fa-fw')."<br> HOURLY SALES",
						 "invoice-sales"=> fa('fa-file-text fa-lg fa-fw')."<br> INVOICE SALES",
						 "settings"		=> fa('fa-cogs fa-lg fa-fw')."<br> SETTINGS",
						 );
		$CI->make->sDivRow(array('style'=>'margin:0;'));
		// $CI->make->sDivCol(4);
		// 	$CI->make->button(fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",array('id'=>'day-report-btn','class'=>'btn-block manager-btn-green double'));
		// $CI->make->eDivCol();
			foreach ($buttons as $id => $text) {
					$CI->make->sDivCol(3);
						$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
					$CI->make->eDivCol();
			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	$CI->make->sBox('default',array('class'=>'box-solid'));
		$CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:535px;padding:0 0 10px 0;'));
			$CI->make->sDiv(array('id'=>'ortigas-content'));
			$CI->make->eDiv();
		$CI->make->eBoxBody();
	$CI->make->eBox();
	return $CI->make->code();
}
function ortigasSettingsPage($obj=array()){
	$CI =& get_instance();
		$CI->make->sForm('ortigas/settings_db',array('id'=>'settings-form'));
			$CI->make->hidden('ortigas_id',iSetObj($obj,'id'));
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->H(3,'TENANT CODE',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->input(null,'tenant_code',iSetObj($obj,'tenant_code'),'',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
						$opts = array('- SALES TYPE -'=>'','Revenue Items'=>'01','Cosmetics'=>'02','Others'=>'03','Non-Revenue Items'=>'04','Multiple'=>'0');
						$CI->make->H(3,'SALES TYPE',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->select(null,'sales_type',$opts,iSetObj($obj,'sales_type'),array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'Select Sales Type'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->button(fa('fa-save fa-lg fa-fw')." SAVE DETAILS",array('id'=>'save-btn','class'=>'btn-block manager-btn-green'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();
}
function dailySalesPage(){
	$CI =& get_instance();
		$CI->make->sForm('ortigas/generate_daily_sales',array('id'=>'generate_form'));
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->H(3,'SELECT DATE',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$CI->make->date(null,'date','','',array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'Select Date'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->button(fa('fa-download fa-lg fa-fw')." GENERATE FILE",array('id'=>'generate-btn','class'=>'btn-block manager-btn-green'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();
}
function hourlySalesPage($zreads=array()){
	$CI =& get_instance();
		$CI->make->sForm('ortigas/generate_hourly_sales',array('id'=>'generate_form'));
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->H(3,'DATE',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$opts = array();
						$opts['- select z read date -'] = '';
						foreach ($zreads as $res) {
							$opts[sql2Date($res->read_date)] = $res->read_date;
						}
						$CI->make->select(null,'date',$opts,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'Select Date'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->button(fa('fa-download fa-lg fa-fw')." GENERATE FILE",array('id'=>'generate-btn','class'=>'btn-block manager-btn-green'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();
}
function invoiceSalesPage($zreads=array()){
	$CI =& get_instance();
		$CI->make->sForm('ortigas/generate_invoice_sales',array('id'=>'generate_form'));
			$CI->make->sDiv(array('style'=>'padding:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->H(3,'DATE',array('class'=>'headline text-center','style'=>'margin-bottom:10px;font-size:20px;'));
						$opts = array();
						$opts['- select z read date -'] = '';
						foreach ($zreads as $res) {
							$opts[sql2Date($res->read_date)] = $res->read_date;
						}
						$CI->make->select(null,'date',$opts,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'Select Date'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->sDivRow();
					$CI->make->sDivCol(4,'left',4);
						$CI->make->button(fa('fa-download fa-lg fa-fw')." GENERATE FILE",array('id'=>'generate-btn','class'=>'btn-block manager-btn-green'));
					$CI->make->eDivCol();
				$CI->make->eDivRow();
			$CI->make->eDiv();
		$CI->make->eForm();
	return $CI->make->code();
}
?>