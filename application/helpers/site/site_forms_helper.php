<?php
// function site_list_form($load_url=null,$form=null,$title_header=null,$lists=array(),$desc=null,$ref=null){
function promo_list_form($load_url=null,$form=null,$title_header=null,$promo=array(),$desc=null,$ref=null){
        $CI =& get_instance();

        $CI->make->sDivRow();
            $CI->make->sDivCol(4,'right',6);
                $options = array();
                $options['Add New Promo'] = "";
                $ctr = 1;
                $selected = "";
                // echo var_dump($promo);
                foreach ($promo as $res) {
                    if($ctr == 1)
                        $selected = $res->promo_id;
                    $options[$res->promo_name] = $res->promo_id;
                    $ctr++;
                }
                $CI->make->select(null,'promo-drop',$options,$selected,array());
            $CI->make->eDivCol();
            $CI->make->sDivCol(2,'left');
                // $CI->make->button(fa('fa-plus').' Create New Branch',array('id'=>'add-new-branch'),'primary');
                $CI->make->button(fa('fa-plus').' Create New Promo',array('id'=>'add-new-promo'),'primary');
                // $CI->make->hidden('res_id',$res_id);
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        $CI->make->sDivRow();
            $CI->make->sDivCol();
                $CI->make->sTab();
                    $tabs = array(
                        fa('fa-info-circle')." General Details"=>array('href'=>'#details','class'=>'tab_link','load'=>'settings/promo_details_load/','id'=>'details_link'),
                        fa('fa-group')." Assign"=>array('href'=>'#assign','class'=>'tab_link load-tab','load'=>'settings/assign_load','id'=>'assign_link'),
                    );
                    $CI->make->tabHead($tabs,null,array());
                    $CI->make->sTabBody();
                        $CI->make->sTabPane(array('id'=>'details','class'=>'tab-pane active'));
                        $CI->make->eTabPane();

                        $CI->make->sTabPane(array('id'=>'assign','class'=>'tab-pane'));
                        $CI->make->eTabPane();

                    $CI->make->eTabBody();
                $CI->make->eTab();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        // $CI->make->sDivRow();
            // echo $map['js'];
            // echo $map['disp'];
            // $CI->make->append($map['js'].$map['html']);
            // $CI->make->sDivCol(12,"left",null,array("id"=>"map","style"=>"height:100px;"));
            // $CI->make->eDivCol();
        // $CI->make->eDivRow();
    return $CI->make->code();
}
function create_rtable($datatable=null,$column_id=null,$id=null,$th=array(),$searchUrl=null,$multiple=false,$listTypeDefault='list'){
    $CI =& get_instance();
    $CI->make->sBox('primary',array('style'=>'-webkit-border-radius: 0px;-moz-border-radius: 0px;border-radius: 0px;'));
        $CI->make->sBoxBody(array('class'=>'no-padding'));  
            $CI->make->append('<br>');
            $data_url = 'loads/get_tbl_data/'.$datatable.'/'.$column_id;
            $targs = array('class'=>'table','id'=>$id,'data-tbl-url'=>$data_url,'search-url'=>$searchUrl);
            $targs['listyle'] = $listTypeDefault;
            $targs['listyle-multi'] = 'no';                
            if($multiple){
                $targs['listyle-multi'] = 'yes';                
            }
            $CI->make->sTable($targs);
                $CI->make->sRow(array('id'=>'rtable-ths'));
                    foreach ($th as $txt) {
                        if($txt == 'add_ctr'){
                            $CI->make->th('#',array('style'=>'width:10px;'));
                        }
                        else{
                            $CI->make->th($txt);
                        }
                    }
                $CI->make->eRow();
            $CI->make->eTable();
        $CI->make->eBoxBody();
    $CI->make->eBox();
    return $CI->make->code();
}    
function makeAssignItemsLoad($items='',$promo_id=null){
    $CI =& get_instance();
        $CI->make->sDivRow();
            $CI->make->sDivCol(12,'left');
                // $CI->make->sForm("menu/combos_db",array('id'=>'promo-form','enctype'=>'multipart/form-data'));
                $CI->make->sForm("settings/assigned_item_db",array('id'=>'assignedItem_form'));
                    $CI->make->hidden('promo_id',$promo_id);
                    $CI->make->sDivRow();
                        $CI->make->sDivCol(6);
                            $CI->make->itemsDrop('Item','item',null,'Select Item',array('class'=>'rOkay'));
                        $CI->make->eDivCol();
                        $CI->make->sDivCol(4);
                            $CI->make->button(fa('fa-plus').' Add Item',array('id'=>'add-item','style'=>'margin-top:23px;'),'primary');
                        $CI->make->eDivCol();
                    $CI->make->eDivRow();
                $CI->make->eForm();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
        $CI->make->sDivRow();
            $CI->make->sDivCol(4);
                $CI->make->sUl(array('class'=>'vertical-list','id'=>'staff-list'));
                    if(count($items) > 0){
                        foreach ($items as $res) {
                                $CI->make->li(
                                    $CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                                    $CI->make->span($res->menu_name,array('class'=>'text','return'=>true))." ".
                                    // $CI->make->span($res->staff_name,array('class'=>'label label-success li-info','return'=>true))." ".
                                    $CI->make->A(fa('fa-lg fa-times'),'#',array('return'=>true,'class'=>'del-staff','id'=>'del-staff-'.$res->id,'ref'=>$res->id))
                                );
                        }
                    }
                    else{
                        $CI->make->li(
                                        $CI->make->span(fa('fa-ellipsis-v'),array('class'=>'handle','return'=>true))." ".
                                        $CI->make->span("No Item found.",array('class'=>'text','return'=>true))." ".
                                        $CI->make->span("",array('class'=>'label label-success li-info','return'=>true)),
                                        array('class'=>'no-staff')
                                    );
                    }
                $CI->make->eUl();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
    return $CI->make->code();
}
function makePromoDetailsLoad($promo='',$promo_id=null,$items=null){
    $CI =& get_instance();
        $CI->make->sDivRow();
            $CI->make->sDivCol(4,'left');
                $CI->make->sBox('primary');
                    $CI->make->sBoxBody();
                        $CI->make->sForm("settings/promo_details_db",array('id'=>'promo-form','enctype'=>'multipart/form-data'));
                            $CI->make->hidden('promo_id',iSetObj($promo,'promo_id'));
                            // $CI->make->hidden('res_id',$promo_id);
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->H(4,fa('fa-info-circle fa-fw').' General Details',array('class'=>'page-header'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->input('Promo Code','promo_code',iSetObj($promo,'promo_code'),'Type Promo Code',array('class'=>'rOkay'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->input('Promo Name','promo_name',iSetObj($promo,'promo_name'),'Type Promo Name',array('class'=>'rOkay'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->input('Value','value',iSetObj($promo,'value'),'Type Promo Value',array('class'=>'rOkay'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->inactiveDrop('Is Absolute','absolute',iSetObj($promo,'absolute'),'',array('style'=>'width: 85px;'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->inactiveDrop('Is Inactive','inactive',iSetObj($promo,'inactive'),'',array('style'=>'width: 85px;'));
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                            $CI->make->sDivRow();
                                $CI->make->sDivCol();
                                    $CI->make->button(fa('fa-save').' Save Promo Details',array('id'=>'save-btn','class'=>'btn-block'),'primary');
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                        $CI->make->eForm();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
            $CI->make->eDivCol();

            $CI->make->sDivCol(8,'left',0,array('id'=>'item-details-box'));
                $CI->make->sBox('primary');
                    $CI->make->sBoxBody();

                        $CI->make->sDivRow();
                            $CI->make->sDivCol();
                                $CI->make->H(4,fa('fa-archive').' Promo Duration',array('class'=>'page-header'));
                            $CI->make->eDivCol();
                        $CI->make->eDivRow();

                        $CI->make->sForm("settings/promo_discount_sched_db",array('id'=>'promo-details-form'));
                            $CI->make->hidden('promo_id',iSetObj($promo,'promo_id'));
                            $CI->make->sDivRow();
                                $CI->make->sDivCol(3);
                                    $CI->make->time('Time On','time-on',null,'Time On');
                                $CI->make->eDivCol();
                                $CI->make->sDivCol(3);
                                    $CI->make->time('Time Off','time-off',null,'Time Off');
                                $CI->make->eDivCol();
                                $CI->make->sDivCol(3);
                                    $CI->make->dayDrop('Day','day',null,'',array('style'=>'width: inherit;'));
                                $CI->make->eDivCol();
                                $CI->make->sDivCol(3);
                                    $CI->make->button(fa('fa-plus').' Add Schedule',array('id'=>'add-schedule','style'=>'margin-top:23px;'),'primary');
                                $CI->make->eDivCol();
                            $CI->make->eDivRow();
                        $CI->make->eForm();

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
                                        if(count($items) > 0){
                                            foreach ($items as $res) {
                                                $CI->make->sRow(array('id'=>'row-'.$res->id));
                                                    $CI->make->td(date('l',strtotime($res->day)));
                                                    $CI->make->td($res->time_on);
                                                    $CI->make->td($res->time_off);
                                                    $a = $CI->make->A(fa('fa-trash-o fa-fw fa-lg'),'#',array('id'=>'del-'.$res->id,'class'=>'dels','ref'=>$res->id,'return'=>true));
                                                    $CI->make->td($a);
                                                $CI->make->eRow();
                                            //     $total += $price * $res->qty;
                                            }
                                        }
                                    $CI->make->eTable();
                                $CI->make->eDiv();
                            $CI->make->eDivCol();
                        $CI->make->eDivRow();
                    $CI->make->eBoxBody();
                $CI->make->eBox();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
    return $CI->make->code();
}
function site_list_table($datatable=null,$column_id=null,$id=null,$th=array(),$cols=null){
    $CI =& get_instance();
    $CI->make->sBox('primary');
        $CI->make->sBoxBody(array('class'=>'no-padding'));  
            $CI->make->append('<br>');
            $data_url = 'loads/get_tbl_data/'.$datatable.'/'.$column_id;
            if($cols != null){
                $data_url .= '?columns='.$cols;
            }
            $CI->make->sTable(array('class'=>'table','id'=>$id,'data-tbl-url'=>$data_url));
                $CI->make->sRow();
                    foreach ($th as $txt) {
                        if($txt == 'add_ctr'){
                            $CI->make->th('#',array('style'=>'width:10px;'));
                        }
                        else{
                            $CI->make->th($txt);
                        }
                    }
                $CI->make->eRow();
            $CI->make->eTable();
        $CI->make->eBoxBody();
    $CI->make->eBox();
    return $CI->make->code();
}    
function site_list_form($load_url=null,$form=null,$title_header=null,$lists=array(),$desc=null,$ref=null){
    $CI =& get_instance();
        $CI->make->sDivRow();
            $CI->make->sDivCol(3);
                $CI->make->sBox('primary');
                    $CI->make->sBoxHead();
                        $CI->make->boxTitle($title_header);
                    $CI->make->eBoxHead();
                    $CI->make->sBoxBody();
                        $list = array();
                        // $icon = $CI->make->icon('fa-plus');
                        $list[fa('fa-plus').' Add New'] = array('id'=>'add-new','class'=>'grp-list');
                        foreach($lists as $val){
                            $name = "";
                            if(!is_array($desc))
                              $name = $val->$desc;
                            else{
                                foreach ($desc as $dsc) {
                                   $name .= $val->$dsc." ";
                                }
                            }
                            $list[$name] = array('class'=>'grp-btn grp-list','id'=>'grp-list-'.$val->$ref,'ref'=>$val->$ref);
                        }
                        $CI->make->listGroup($list,array('id'=>'add-grp-list-div'));
                    $CI->make->eBoxBody();
                $CI->make->eBox();
            $CI->make->eDivCol();

            $CI->make->sDivCol(9);
                $CI->make->sBox('primary');
                    $CI->make->sBoxBody(array('id'=>'group-detail-con','load-url'=>$load_url));
                    $CI->make->eBoxBody();
                    $CI->make->sBoxFoot(array("style"=>"text-align:right"));
                        $CI->make->button(fa('fa-save').' Save',array('id'=>'save-list-form','class'=>'btn-primary','save-form'=>$form,'load-url'=>$load_url),'primary');
                    $CI->make->eBoxFoot();
                $CI->make->eBox();
            $CI->make->eDivCol();
        $CI->make->eDivRow();
    return $CI->make->code();
}

function adminDashboard(){
    $CI =& get_instance();

        $CI->make->append('<div class="row">
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>
                                            150
                                        </h3>
                                        <p>
                                            Closed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-checkmark"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>
                                            53
                                        </h3>
                                        <p>
                                            Completed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-thumbsup"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>
                                            44
                                        </h3>
                                        <p>
                                            Submitted
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-forward"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>
                                            65
                                        </h3>
                                        <p>
                                            In-progress
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-help-buoy"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>
                                            11
                                        </h3>
                                        <p>
                                            Out of Scope
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-social-buffer"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-maroon">
                                    <div class="inner">
                                        <h3>
                                            42
                                        </h3>
                                        <p>
                                            Rejected
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-close-round"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>');
        $CI->make->append('<section class="col-lg-8 connectedSortable">
                    <!-- Map box -->
                    <div class="box box-primary">
                        <div class="box-header">
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <!-- <button class="btn btn-flat btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>-->
                            </div><!-- /. tools -->

                            <i class="fa fa-map-marker"></i>
                            <h3 class="box-title">
                                Requests
                            </h3>
                        </div>
                        <div class="box-body no-padding">
                            <div class="table-responsive">
                                <!-- .table - Uses sparkline charts-->
                                <table class="table table-striped">
                                    <tr>
                                        <th></th>
                                        <th>Ticket ID</th>
                                        <th>Service Description</th>
                                        <th>SLA</th>
                                        <th>Submission Time</th>
                                        <th>Attended By</th>
                                        <th>Status</th>
                                    </tr>
                                    <tr>
                                        <td><a href="#">1</a></td>
                                        <td>81230</td>
                                        <td>Collate WSM reports and submit to SIRA desk</td>
                                        <td>48 Hours</td>
                                        <td>'.date('l jS \of F Y h:i:s A').'</td>
                                        <td> </td>
                                        <td>Closed</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">2</a></td>
                                        <td>81231</td>
                                        <td>Collate WSM reports and submit to SIRA desk</td>
                                        <td>21 Hours</td>
                                        <td>'.date('l jS \of F Y h:i:s A').'</td>
                                        <td>Haydee Adan</td>
                                        <td>In-progress</td>
                                    </tr>
                                </table><!-- /.table -->
                            </div>
                        </div><!-- /.box-body-->
                        <div class="box-footer">
                            <button class="btn btn-flat btn-info"><i class="fa fa-download"></i> Generate PDF</button>
                        </div>
                    </div>
                    <!-- /.box -->
                </section><!-- right col -->');
        $CI->make->append('<!-- Left col -->
                        <section class="col-lg-4 connectedSortable">
                            <!-- Box (with bar chart) -->
                            <div class="box box-danger" id="loading-example">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-danger btn-sm refresh-btn" data-toggle="tooltip" title="Reload"><i class="fa fa-refresh"></i></button>
                                        <button class="btn btn-flat btn-danger btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-users"></i>

                                    <h3 class="box-title">Teams</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <div class="row">
                                        <!--<div class="col-sm-7">-->
                                            <!-- bar chart -->
                                            <!--<div class="chart" id="bar-chart" style="height: 250px;"></div>-->
                                        <!--</div>-->
                                        <!--<div class="col-sm-5">-->
                                        <div class="col-sm-12">
                                            <div class="pad">
                                                <!-- Progress bars -->
                                                <div class="clearfix">
                                                    <span class="pull-left">Alpha</span>
                                                    <small class="pull-right">21/211 </small>
                                                </div>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-green" style="width: 9.9%;"></div>
                                                </div>

                                                <div class="clearfix">
                                                    <span class="pull-left">Beta</span>
                                                    <small class="pull-right">396/501 </small>
                                                </div>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-red" style="width: 79%;"></div>
                                                </div>

                                                <div class="clearfix">
                                                    <span class="pull-left">Gamma</span>
                                                    <small class="pull-right">321/628 </small>
                                                </div>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-light-blue" style="width: 51%;"></div>
                                                </div>

                                                <div class="clearfix">
                                                    <span class="pull-left">Delta</span>
                                                    <small class="pull-right">10/200 </small>
                                                </div>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 5%;"></div>
                                                </div>
                                                <!-- Buttons -->
                                                <p>
                                                    <button class="btn btn-default btn-sm"><i class="fa fa-cloud-download"></i> Generate PDF</button>
                                                </p>
                                            </div><!-- /.pad -->
                                        </div><!-- /.col -->
                                    </div><!-- /.row - inside box -->
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                </div><!-- /.box-footer -->');
    return $CI->make->code();
}
function requestorDashboard(){
    $CI =& get_instance();

        $CI->make->append('<div class="row">
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>
                                            1
                                        </h3>
                                        <p>
                                            Closed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-checkmark"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            Completed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-thumbsup"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            Submitted
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-forward"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>
                                            1
                                        </h3>
                                        <p>
                                            In-progress
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-help-buoy"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            Out of Scope
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-social-buffer"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-maroon">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            Rejected
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-close-round"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>');

        $CI->make->append('<section class="col-lg-4 connectedSortable">
                            <!-- quick email widget -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <i class="fa fa-envelope"></i>
                                    <h3 class="box-title">Send Request</h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!--<button class="btn btn-flat btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>-->
                                    </div><!-- /. tools -->
                                </div>
                                <div class="box-body">
                                    <form action="#" method="post">
                                        <!--<div class="form-group">
                                            <input type="email" class="form-control" name="emailto" placeholder="To"/>
                                        </div>-->
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="subject" placeholder="Service"/>
                                        </div>
                                        <div>
                                            <textarea class="textarea" placeholder="Remarks" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="box-footer clearfix">
                                    <button class="pull-right btn btn-default" id="sendEmail">Send <i class="fa fa-arrow-circle-right"></i></button>
                                </div>
                            </div>
                        </section>');
        $CI->make->append('<section class="col-lg-8 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Requests
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th></th>
                                                <th>Ticket ID</th>
                                                <th>Service Description</th>
                                                <th>SLA</th>
                                                <th>Submission Time</th>
                                                <th>Attended By</th>
                                                <th>Status</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">1</a></td>
                                                <td>81230</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>48 Hours</td>
                                                <td>'.date('l jS \of F Y h:i:s A').'</td>
                                                <td> </td>
                                                <td>Closed</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">2</a></td>
                                                <td>81231</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>21 Hours</td>
                                                <td>'.date('l jS \of F Y h:i:s A').'</td>
                                                <td>Haydee Adan</td>
                                                <td>In-progress</td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                    <button class="btn btn-flat btn-info"><i class="fa fa-download"></i> Generate PDF</button>
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->');

    return $CI->make->code();
}
function teamLeadDashboard(){
    $CI =& get_instance();

        $CI->make->append('<div class="row">
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            HR requests
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-clipboard"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>
                                            42
                                        </h3>
                                        <p>
                                            Retailer Requests
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-share"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>
                                            0
                                        </h3>
                                        <p>
                                            Pending out of scope requests
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-hand"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            ');

        $CI->make->append('<section class="col-lg-8 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- <button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>-->
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Requests for our Team
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th></th>
                                                <th>Ticket ID</th>
                                                <th>Service Description</th>
                                                <th>SLA</th>
                                                <th>Submission Time</th>
                                                <th>Attended By</th>
                                                <th>Status</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">1</a></td>
                                                <td>81230</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>48 Hours</td>
                                                <td>'.date('l jS \of F Y h:i:s A').'</td>
                                                <td> </td>
                                                <td>Closed</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">2</a></td>
                                                <td>81231</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>21 Hours</td>
                                                <td>'.date('l jS \of F Y h:i:s A').'</td>
                                                <td>Haydee Adan</td>
                                                <td>In-progress</td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                    <button class="btn btn-info"><i class="fa fa-download"></i> Generate PDF</button>
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->');
    $CI->make->append('<section class="col-lg-4 connectedSortable">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        My Members
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Member</th>
                                                <th>Requests</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Rain Helo</a></td>
                                                <td><div id="sparkline-1"></div></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Haydee Adan</a></td>
                                                <td><div id="sparkline-2"></div></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Aicynne Liwanag</a></td>
                                                <td><div id="sparkline-3"></div></td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div>
                            </div>
                            <!-- Map box -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Services Assigned to Our Team
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Service Name</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Collate WSM reports and submit to SIRA desk</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Download SIRA SNAPS and PLIPs SIRA Generated</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Report System Error via Coordination with BAM</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">DO Sites Volume Analysis Monthly</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Permits Update - Monthly</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Update Contract Database</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">CCR</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Pricing Accruals</a></td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->');

    return $CI->make->code();
}
function staffDashboard(){
    $CI =& get_instance();

        $CI->make->append('<div class="row">
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>
                                            150
                                        </h3>
                                        <p>
                                            Closed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-android-checkmark"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>
                                            53
                                        </h3>
                                        <p>
                                            Completed
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-thumbsup"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3>
                                            44
                                        </h3>
                                        <p>
                                            Submitted
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-forward"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>
                                            65
                                        </h3>
                                        <p>
                                            In-progress
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-help-buoy"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3>
                                            11
                                        </h3>
                                        <p>
                                            Out of Scope
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-social-buffer"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 col-xs-6">
                                <div class="small-box bg-maroon">
                                    <div class="inner">
                                        <h3>
                                            42
                                        </h3>
                                        <p>
                                            Rejected
                                        </p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-close-round"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        More info <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>');
        $CI->make->append('<section class="col-lg-8 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Requests Assigned to Me
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th></th>
                                                <th>Ticket ID</th>
                                                <th>Service Description</th>
                                                <th>SLA</th>
                                                <th>Status</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">1</a></td>
                                                <td>81230</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>48 Hours</td>
                                                <td>Closed</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">2</a></td>
                                                <td>81231</td>
                                                <td>Collate WSM reports and submit to SIRA desk</td>
                                                <td>21 Hours</td>
                                                <td>In-progress</td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                    <button class="btn btn-flat btn-info"><i class="fa fa-download"></i> Generate PDF</button>
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->');
    $CI->make->append('<section class="col-lg-4 connectedSortable">
                            <!-- Map box -->
                            <div class="box box-primary">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <!--
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-flat btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                        <button class="btn btn-flat btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div>
                                    --><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Services Assigned to Our Team
                                    </h3>
                                </div>
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                        <!-- .table - Uses sparkline charts-->
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Service Name</th>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Collate WSM reports and submit to SIRA desk</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Download SIRA SNAPS and PLIPs SIRA Generated</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Report System Error via Coordination with BAM</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">DO Sites Volume Analysis Monthly</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Permits Update - Monthly</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Update Contract Database</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">CCR</a></td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">Pricing Accruals</a></td>
                                            </tr>
                                        </table><!-- /.table -->
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer">
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->');
    return $CI->make->code();
}
?>