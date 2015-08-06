<script>
$(document).ready(function(){
	<?php if($use_js == 'scheduleJs'): ?>

		$(".timepicker").timepicker({
	       showInputs: false,
	       minuteStep: 1
	    });
	    var schd_id = $('#dtr_sched_id').val();
	    if(schd_id == ''){
	    	$('#add-schedule').attr('disabled','disabled');
	    }
	    $('#add-schedule').click(function(){
	    	//alert('aw');
	    	$("#dtr_schedules_details_form").rOkay({
					btn_load		: 	$('#add-schedule'),
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											//alert(data);
											if(data.msg == 'error'){
												msg = 'Day already duplicated in this schedule.';
												rMsg(msg,'error');
											}
											else if(typeof data.msg != 'undefined' ){
												var sel = $('#promo-drop').val();
												$('#group-detail-con').rLoad({url:baseUrl+'dtr/dtr_schedules_form/'+data.id});
												rMsg(data.msg,'success');
											}
										}
				});
	    	return false;
	    });
	    $('.del-sched').each(function(){
			var id = $(this).attr('ref');
			deleteSched(id);
		});
		function deleteSched(id){
			$('#del-sched-'+id).click(function(){
				//alert(id);
				var formData = 'pr_sched_id='+id;
				var li = $(this).parent().parent();
				// alert(baseUrl+'settings/remove_promo_details');
				$.post(baseUrl+'dtr/remove_schedule_promo_details',formData,function(data){
					// alert('zxc');
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#staff-list li').length;
					if(noLi == 0){
						$('ul#staff-list').append('<li class="no-staff"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Staffs found.</span></li>');
					}
					// });
				},'json');
				return false;
			});
		}
		$(".timepicker").timepicker({
			    showInputs: false,
			    minuteStep: 1
			});
			$('#save-list-form').click(function(){
				//alert('zxczxc');
				return false;
			});
			// $('#add-schedule').click(function(){
			// 	var sched_id = $('#dtr_sched_id').val();
			// 	var timeon = $('#time-on').val();
			// 	var timeoff = $('#time-off').val();
			// 	var day = $('#day').val();
			// 	var formData = 'sched_id='+sched_id+'&time_on='+encodeURIComponent(timeon)+'&time_off='+encodeURIComponent(timeoff)+'&day='+day;
			// 	alert(formData);
			// 	// $.post(baseUrl+'dtr/dtr_sched_details_db',formData,function(data){
			// 	// 	//alert(data);
			// 	// 	rMsg(data.msg,'success');
			// 	// 	$('#group-detail-con').rLoad({url:baseUrl+'dtr/dtr_schedules_form/'+data.id});
			// 	// },'json');
			// 	// });
			// 	// return false;

			// 	return false;
			// });

	<?php elseif($use_js == 'shiftFormJs'): ?>
		//alert('aw');
		loader('#details_link');
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var menu_id = $('#menu_id').val();

			if(menu_id == ""){
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			$(tabPane).rLoad({url:baseUrl+loadUrl+menu_id});
		}
		function disEnbleTabs(id,enable){
			if(enable){
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			}
			else{
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}
		// $('#save-shift').click(function(){
		// 	alert('aw');
		// 	$("#details_form").rOkay({
		// 		btn_load		: 	$('#save-shift'),
		// 		bnt_load_remove	: 	true,
		// 		asJson			: 	true,
		// 		onComplete		:	function(data){
		// 								if(typeof data.msg != 'undefined' ){
		// 									$('#menu_id').val(data.id);
		// 									// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
		// 									disEnbleTabs('.load-tab',true);
		// 									rMsg(data.msg,'success');
		// 								}
		// 							}
		// 	});
		// 	return false;
		// });
		function disEnbleTabs(id,enable){
			if(enable){
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			}
			else{
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}

	<?php elseif($use_js == 'shiftFormJs2'): ?>
	$(".timepicker").timepicker({
	       showInputs: false,
	       minuteStep: 1
	    });

	$('#save-shift').click(function(){
			//alert('aw');
			$("#details_form").rOkay({
				btn_load		: 	$('#save-shift'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										//alert(data);
										$('#save-shift').attr('disabled','disabled');
										rMsg(data.msg,'success');
										setTimeout(function() {
										      // Do something after 2 seconds
										window.location = baseUrl+'dtr/form_shift/'+data.id;
										}, 1000);

										//$('#form_shift_id').val(data.id);
										// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
										//disEnbleTabs('.load-tab',true);
										
									}
			});
			return false;
		});
	///////////////////////////scheduler/////////////////////////
	/////////////////////////////////////////////////////////////
	<?php elseif($use_js == 'attendanceJs'): ?>
		$("#tbl-div").rLoad({url:baseUrl+'dtr/table'});

		$('#go').click(function(){
			var okay = $('#search_form').rOkay({goSubmit:false});
			if(okay){
				var datas = $('#search_form').serialize();
				$("#tbl-div").rLoad({url:baseUrl+'dtr/table'+'?'+datas});				
			}
			return false;
		});

	<?php elseif($use_js == 'schedTableJs'): ?>
		$('.sched').change(function(){
			user_id = $(this).attr('user_id');
			date = $(this).attr('date');
			val = $(this).val();
			
			var formData = 'user_id='+user_id+'&date='+date+'&val='+val;
			// 	alert(formData);
			$.post(baseUrl+'dtr/save_sched',formData,function(data){
				//alert(data);
				//rMsg(data.msg,'success');
				//$('#group-detail-con').rLoad({url:baseUrl+'dtr/dtr_schedules_form/'+data.id});
			// },'json');
			});

		});



	<?php endif; ?>
});
</script>