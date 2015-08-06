<?php

function makeDtrSchedulesForm($cat=array(),$dets=array()){
	$CI =& get_instance();
	$CI->make->sForm("dine/dtr/dtr_sched_db",array('id'=>'dtr_schedules_form'));
		$CI->make->sDivRow(array('style'=>''));
			$CI->make->sDivCol(6);
				$CI->make->hidden('dtr_sched_id',iSetObj($cat,'dtr_sched_id'));
				$CI->make->input('Description','desc',iSetObj($cat,'desc'),'Type Description',array('class'=>'rOkay'));
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($cat,'inactive'),'',array('style'=>'width: 85px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
    $CI->make->eForm();
	$CI->make->sForm("dine/dtr/dtr_sched_details_db",array('id'=>'dtr_schedules_details_form'));
    	$CI->make->sDivRow();
            $CI->make->sDivCol(3);
            	// $CI->make->hidden('menu_sched_id',iSetObj($cat,'menu_sched_id'));
            	$CI->make->hidden('sched_id',iSetObj($cat,'dtr_sched_id'));
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

//////////////////////////////////////Shifts///////////////////////////////
///////////////////////////////////////////////////////////////////////////
function shiftsListPage($list=array()){
	$CI =& get_instance();

	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sBox('primary');
				$CI->make->sBoxBody();
					$CI->make->sDivRow();
						$CI->make->sDivCol(12,'right');
							 $CI->make->A(fa('fa-plus').' Add New Shift',base_url().'dtr/form_shift',array('class'=>'btn btn-primary'));
						$CI->make->eDivCol();
                	$CI->make->eDivRow();
                	$CI->make->sDivRow();
						$CI->make->sDivCol();
							$th = array('Shift Code'=>'',
									'Description'=>'',
									'Time In'=>'',
									'Time Out'=>'',
									'Grace Period'=>'',
									' '=>array('width'=>'12%','align'=>'right'));
							$rows = array();
							foreach($list as $v){
								$links = "";
								$links .= $CI->make->A(fa('fa-edit fa-2x fa-fw'),base_url().'dtr/form_shift/'.$v->id,array("return"=>true));
								$rows[] = array(
											  $v->code,
											  $v->description,
											  $v->time_in,
											  $v->time_out,
											  $v->grace_period,
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
function shiftFormPage($shift_id=null){
	$CI =& get_instance();
	$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
			$CI->make->sDivCol(12,'right');
				$CI->make->A(fa('fa-reply').' Go back to shifts',base_url().'dtr/dtr_shifts',array('class'=>'btn btn-primary'));
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	$CI->make->sDivRow();
		$CI->make->sDivCol();
			$CI->make->sTab();
					$tabs = array(
						fa('fa-clock-o')." Schedule Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'dtr/details_load/','id'=>'details_link'),
						//fa('fa-book')." Recipe"=>array('href'=>'#recipe','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'menu/recipe_load/','id'=>'recipe_link'),
						//fa('fa-asterisk')." Modifiers"=>array('href'=>'#modifiers','disabled'=>'disabled','class'=>'tab_link load-tab','load'=>'menu/modifier_load/','id'=>'modifier_link'),
					);
					$CI->make->hidden('menu_id',$shift_id);
					$CI->make->tabHead($tabs,null,array());
					$CI->make->sTabBody(array('style'=>'min-height:202px;'));
						$CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
						$CI->make->eTabPane();
						// $CI->make->sTabPane(array('id'=>'recipe','class'=>'tab-pane'));
						// $CI->make->eTabPane();
						// $CI->make->sTabPane(array('id'=>'modifiers','class'=>'tab-pane'));
						// $CI->make->eTabPane();
					$CI->make->eTabBody();
				$CI->make->eTab();
		$CI->make->eDivCol();
	$CI->make->eDivRow();
	return $CI->make->code();
}
function shiftsDetailsLoad($shift=null,$shift_id=null){
	$CI =& get_instance();
		$CI->make->sForm("dtr/shift_details_db",array('id'=>'details_form'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(3);
					$CI->make->hidden('form_shift_id',$shift_id,array('id'=>""));
					$CI->make->input('Shift Code','shift_code',iSetObj($shift,'code'),'Code',array('class'=>'rOkay'));
				$CI->make->eDivCol();
				$CI->make->sDivCol(5);
					$CI->make->input('Shift Description','shift_desc',iSetObj($shift,'description'),'Description',array('class'=>''));
				$CI->make->eDivCol();
	    	$CI->make->eDivRow();
		$CI->make->sDivRow();
			$CI->make->sDivCol(2);
				//$CI->make->time('Official Time In','time_in','01:00');
				$CI->make->input('Official Time In','time_in',iSetObj($shift,'time_in'),'',array('class'=>''));
				$CI->make->input('Break Hours','break_hours',iSetObj($shift,'break_hours'),'',array('class'=>''));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Break Out','break_out',iSetObj($shift,'break_out'),'',array('class'=>''));
				$CI->make->input('Grace Period','grace_period',iSetObj($shift,'grace_period'),'00:00:00',array('class'=>''));
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Break In','break_in',iSetObj($shift,'break_in'),'',array('class'=>''));
				$CI->make->input('Time In Grace Period','timein_grace_period',iSetObj($shift,'timein_grace_period'),'00:00:00',array('class'=>''));
				
			$CI->make->eDivCol();
			$CI->make->sDivCol(3);
				$CI->make->input('Official Time Out','time_out',iSetObj($shift,'time_out'),'',array('class'=>''));
				if($shift_id != null){
				$CI->make->input('Duration','duration',iSetObj($shift,'work_hours'),'',array('readonly'=>'true'));
				}
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
		$CI->make->sDivRow();
			$CI->make->sDivCol(4,'left',4);
				$CI->make->button(fa('fa-save').' Save Shift',array('id'=>'save-shift'),'primary');
			$CI->make->eDivCol();
	    $CI->make->eDivRow();
		$CI->make->eForm();
	return $CI->make->code();
}

////////////////////////scheduler///////////////////////
///////////////////////////////////////////////////////

function schedulerPage(){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol();
				$CI->make->sBox('primary');
                    $CI->make->sBoxBody();
                    	$CI->make->sForm(null,array('id'=>'search_form'));
                    		$range = rangeWeek(phpNow());
	                    	$CI->make->sDivRow();
	                    		$CI->make->sDivCol('2','left');
		                    		$CI->make->date('From','date-from',sql2Date($range['start']),null,array("class"=>'rOkay',"ro-msg"=>'Error! Select Date From!'));
		                    	$CI->make->eDivCol();
		                    	$CI->make->sDivCol('2','left');
		                    		$CI->make->date('To','date-to',sql2Date($range['end']),null,array("class"=>'rOkay',"ro-msg"=>'Error! Select Date TO!'));
		                    	$CI->make->eDivCol();
		                    	$CI->make->sDivCol('2','left');
		                    		$CI->make->userDrop2('Users','user-drop',null,null,array());
		                    	$CI->make->eDivCol();
		                    	// $CI->make->sDivCol('2','left');
		                    	// 	$CI->make->gradeDrop('Grade','grade-drop',null,'Select Grade',array("class"=>'rOkay',"ro-msg"=>'Error! Select Grade!'));
		                    	// $CI->make->eDivCol();
		                    	// $CI->make->sDivCol('2','left');
		                    	// 	$CI->make->sectionDrop('Section','section-drop',null,'Select Section',array("class"=>'rOkay',"ro-msg"=>'Error! Select Section!'));
		                    	// $CI->make->eDivCol();
		                    	$CI->make->sDivCol('1','left');
		                    		$CI->make->button('Search',array('id'=>'go','style'=>'margin-top:25px;'),'success');
		                    	$CI->make->eDivCol();
	                    	$CI->make->eDivRow();
                    	$CI->make->eForm();

                    	$CI->make->sDivRow();
                    		$CI->make->sDivCol('12','left',0,array('id'=>'tbl-div','style'=>'margin-top:10px;'));
	                    	$CI->make->eDivCol();
                    	$CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}

function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
    $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
    return $res;
}

function dateRange($first, $last, $step = '+1 day', $format = 'm/d/Y' ) { 

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) { 

        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

function scheduleTable($users = array(),$from=null,$to=null,$sch=array()){
	$CI =& get_instance();
	$CI->load->model('dine/dtr_model');
		$CI->make->sDiv(array('class'=>'outer'));
			$CI->make->sDiv(array('class'=>'inner'));
				$CI->make->sTable(array('class'=>'table  table-bordered table-hover table-condensed'));
					$CI->make->sTableHead();
						$CI->make->sRow();
							$CI->make->th('Name<br>&nbsp;',array('style'=>'border:none;text-align:center'));
							$dates = array();
							if($from == null && $to == null){
								$range = rangeWeek(phpNow());
								$dates = dateRange($range['start'],$range['end']);
							}else{
								$dates = dateRange($from,$to);
							}
							//echo $from."----".$to;
							foreach ($dates as $date) {
								$day = date("l",strtotime($date));
								$CI->make->td($date."<br>".$day,array('style'=>'text-align:center'));
							}
						$CI->make->eRow();
					$CI->make->eTableHead();
					$CI->make->sTableBody();
						foreach ($users as $res) {
							$CI->make->sRow();
								$CI->make->th($res->fname." ".$res->mname." ".$res->lname." ".$res->suffix,array('width'=>'150px'));
								foreach ($dates as $date) {
									// $day = date("l",strtotime($date));
									$param = array();
									$param['return'] = true; 
									$param['class'] = 'sched';
									$param['date'] = $date;
									$param['user_id'] = $res->id; 
									// $val=null;
									// if(isset($sch[$res->id." ".$date]))
									// 	$val=$att[$res->id." ".$date];
									$ref = $res->id."-".$date;

									$dtr_id = 0;
									$gsched = $CI->dtr_model->getSchedule($date,$res->id);
									if(count($gsched) > 0){
										$dtr_id = $gsched[0]->dtr_id;
									}
									//var_dump($gsched);
									// $CI->make->td($res->id."-".$date,array('style'=>'text-align:center'));
									$select = $CI->make->scheduleDrop(null,'sched['.$ref.']',$dtr_id,null,$param);
									$CI->make->td($select,array('style'=>'text-align:center'));
								}
							$CI->make->eRow();							
						}
					$CI->make->eTableBody();
				$CI->make->eTable();
			$CI->make->eDiv();
		$CI->make->eDiv();
	
	return $CI->make->code();
}

?>