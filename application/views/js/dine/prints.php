<script>
$(document).ready(function(){
	<?php if($use_js == 'mainPageJS'): ?>
		$('#prnt-main').height($(document).height() - 63);
		$('#prnt-loads').height($('#prnt-main').height() - 68);
		$('#prnt-loads').rLoad({url:baseUrl+'prints/date_and_time'});
		$('#date-time-sales-btn').addClass('selected');

		// $('.datepicker').datepicker({format:'yyyy-mm-dd'});

		$('#date-time-sales-btn').click(function(){
			$('#prnt-loads').rLoad({url:baseUrl+'prints/date_and_time'});
			$('.load-types-btns').removeClass('selected');
			$(this).addClass('selected');
			return false;
		});
		$('#shift-sales-btn').click(function(){
			$('#prnt-loads').rLoad({url:baseUrl+'prints/shift_sales'});
			$('.load-types-btns').removeClass('selected');
			$(this).addClass('selected');
			return false;
		});
		$('#end-day-sales-btn').click(function(){
			$('#prnt-loads').rLoad({url:baseUrl+'prints/end_day_sales'});
			$('.load-types-btns').removeClass('selected');
			$(this).addClass('selected');
			return false;
		});
	<?php elseif($use_js == 'datetimeJS'): ?>
		$('.print-containers').css({'height':$('#prnt-loads').height() - 20});
		$('.rep-btns').click(function(){
			load_rep($(this).attr('ref'),$(this));
			return false;
		});
		$('#pdf-paper-btn').click(function(){
			$('#report-view-div').print();
			return false;
		});	
		$('#print-paper-btn').click(function(){
			var ref = $(this).attr('target');
			$("#sform").rOkay({
				btn_load		: 	$('#print-paper-btn'),
				passTo			: 	'prints/'+ref+'/1',
				bnt_load_remove	: 	true,
				asJson			: 	false,
				onComplete		:	function(data){
										// alert(data);
									}
			});
			return false;
		});
		function selector(cl,btn){
			$(cl).removeClass('selected');
			btn.addClass('selected');
		}
		function load_rep(ref,btn){

			$('#report-view-div').html('');
			$("#sform").rOkay({
				btn_load		: 	btn,
				passTo			: 	'prints/'+ref,
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$('#print-paper-btn').attr('target',ref);
										selector('.rep-btns',btn);
										$('#report-view-div').html(data.code);
										scroller();
									}
			});
		}
		function scroller(){
			scrolled = 0;
			// $('#report-view-div').perfectScrollbar({suppressScrollX: true});
			$("#down-paper-btn").on("click" ,function(){
			    var inHeight = $("#report-view-div")[0].scrollHeight;
			    var divHeight = $("#report-view-div").height();
			    var trueHeight = inHeight - divHeight;
		        if((scrolled + 150) > trueHeight){
		        	scrolled = trueHeight;
		        }
		        else{
		    	    scrolled=scrolled+150;				    	
		        }
			    // scrolled=scrolled+100;
				$("#report-view-div").animate({
				        scrollTop:  scrolled
				},1);
			});
			$("#up-paper-btn").on("click" ,function(){
				if(scrolled > 0){
					scrolled=scrolled-150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					},1);
				}
			});
		}
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});
 	<?php elseif($use_js == 'sfhitJS'): ?>
		$('.print-containers').css({'height':$('#prnt-loads').height() - 20});
		$('#shifts-load').css({'height':$('#prnt-loads').height() - 85});
		load_shifts();
		$('#calendar').change(function(){
			load_shifts();
		});
		function load_shifts(){
			var calendar = $('#calendar').val();
			$.post(baseUrl+'prints/get_shifts','calendar='+calendar,function(data){
				$('#shifts-load').html(data.code);
				$('#report-view-div').html('');
				$('.rep-btns').removeClass('selected');
				// alert(data.code);
				var shifts = data.shifts;
				$.each(shifts,function(key,opt){
					$('#shift-box-'+key).click(function(){
						var box = $(this);
						
						
						$('.shift-box').removeClass('selected');
						box.addClass('selected');
						var selected = $('.rep-btns.selected');
						if(typeof selected !== 'undefined' || selected !== null){
							load_rep(selected.attr('ref'),selected);
						}	
					});
				});
			},'json');
			// alert(data)
			// });
		}
		$('.rep-btns').click(function(){
			load_rep($(this).attr('ref'),$(this));
			return false;
		});
		$('#pdf-paper-btn').click(function(){
			$('#report-view-div').print();
			return false;
		});	
		$('#print-paper-btn').click(function(){
			var ref = $(this).attr('target');
			var title = $(this).attr('title');
			var shift_id = $('.shift-box.selected').attr('ref');

			if(typeof shift_id === 'undefined' || shift_id === null){
				rMsg('Select A shift','error');   
			}
			else{
				$("#sform").rOkay({
					btn_load		: 	$('#print-paper-btn'),
					passTo			: 	'prints/'+ref+'/1',
					addData			: 	'shift_id='+shift_id+'&title='+title,
					bnt_load_remove	: 	true,
					asJson			: 	false,
					onComplete		:	function(data){
											// alert(data);
										}
				});
			}	
				return false;
		});
		function selector(cl,btn){
			$(cl).removeClass('selected');
			btn.addClass('selected');
		}
		function load_rep(ref,btn){
			$('#report-view-div').html('');
			var shift_id = $('.shift-box.selected').attr('ref');
			var title = btn.attr('title');
			if(typeof shift_id === 'undefined' || shift_id === null){
				rMsg('Select A shift','error');   
			}
			else{
				$("#sform").rOkay({
					btn_load		: 	btn,
					passTo			: 	'prints/'+ref,
					addData			: 	'shift_id='+shift_id+'&title='+title,
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											// alert(data);
											$('#print-paper-btn').attr('target',ref);
											$('#print-paper-btn').attr('title',title);
											selector('.rep-btns',btn);
											$('#report-view-div').html(data.code);
											scroller();
										}
				});
			}
		}
		function scroller(){
			scrolled = 0;
			$("#report-view-div").scrollTop = 0;
			// $('#report-view-div').perfectScrollbar({suppressScrollX: true});
			$("#down-paper-btn").on("click" ,function(){
			    var inHeight = $("#report-view-div")[0].scrollHeight;
			    var divHeight = $("#report-view-div").height();
			    var trueHeight = inHeight - divHeight;
		        if((scrolled + 150) > trueHeight){
		        	scrolled = trueHeight;
		        }
		        else{
		    	    scrolled=scrolled+150;				    	
		        }
			    // scrolled=scrolled+100;
				$("#report-view-div").animate({
				        scrollTop:  scrolled
				},1);
			});
			$("#up-paper-btn").on("click" ,function(){
				if(scrolled > 0){
					scrolled=scrolled-150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					},1);
				}
			});
		}
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});	
	<?php elseif($use_js == 'dayReadsJS'): ?>
		$('.print-containers').css({'height':$('#prnt-loads').height() - 20});
		$('.rep-btns').click(function(){
			load_rep($(this).attr('ref'),$(this));
			return false;
		});
		$('#pdf-paper-btn').click(function(){
			$('#report-view-div').print();
			return false;
		});	
		$('#print-paper-btn').click(function(){
			var ref = $(this).attr('target');
			var title = $(this).attr('title');
			$("#sform").rOkay({
				btn_load		: 	$('#print-paper-btn'),
				passTo			: 	'prints/'+ref+'/1',
				addData			: 	'title='+title,
				bnt_load_remove	: 	true,
				asJson			: 	false,
				onComplete		:	function(data){
										// alert(data);
									}
			});
			return false;
		});
		function selector(cl,btn){
			$(cl).removeClass('selected');
			btn.addClass('selected');
		}
		function load_rep(ref,btn){
			var title = btn.attr('title');

			$('#report-view-div').html('');
			$("#sform").rOkay({
				btn_load		: 	btn,
				passTo			: 	'prints/'+ref,
				addData			: 	'title='+title,
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$('#print-paper-btn').attr('target',ref);
										$('#print-paper-btn').attr('title',title);
										selector('.rep-btns',btn);
										$('#report-view-div').html(data.code);
										scroller();
									}
			});
		}
		function scroller(){
			scrolled = 0;
			$("#report-view-div").scrollTop = 0;
			$("#down-paper-btn").on("click" ,function(){
			    var inHeight = $("#report-view-div")[0].scrollHeight;
			    var divHeight = $("#report-view-div").height();
			    var trueHeight = inHeight - divHeight;
		        if((scrolled + 150) > trueHeight){
		        	scrolled = trueHeight;
		        }
		        else{
		    	    scrolled=scrolled+150;				    	
		        }
			    // scrolled=scrolled+100;
				$("#report-view-div").animate({
				        scrollTop:  scrolled
				},1);
			});
			$("#up-paper-btn").on("click" ,function(){
				if(scrolled > 0){
					scrolled=scrolled-150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					},1);
				}
			});
		}
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});
	<?php endif; ?>
});
</script>