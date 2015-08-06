<script>
$(document).ready(function(){
	<?php if($use_js == 'reportsJs'): ?>
		$('.btn-manager-report').on('click',function(event){
			var id = $(this).attr('id');
			var url = $(this).attr('url');
			$('#report-form-div').html('<center style="margin-top:30px;"><i class="fa fa-4x fa-spinner fa-spin"></i></center>');
			$.ajax({
					url:baseUrl+'reports/form/'+id,
					dataType:'json'
			})
			.done(function(data){
				$('#report-form-div').html(data.code);
				$('#report-view-div').perfectScrollbar({suppressScrollX: true});
				$('.datepicker').datepicker({format:'yyyy-mm-dd'});
				$('.daterangepicker').each(function(index){
		 			if ($(this).hasClass('datetimepicker')) {
		 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
		 			} else {
		 				$(this).daterangepicker({separator: ' to '});
		 			}
		 		});
		 		$('.pick-date').datetimepicker({
			        pickTime: false
			    });
		 		reportsBtn(url);
			});
			event.preventDefault();
		});
		function reportsBtn(goPassTo){
			$('#run-rep-btn').click(function(e){
				$("#report_form").rOkay({
					btn_load		: 	$('#run-rep-btn'),
					passTo			: 	goPassTo,
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											// alert(data);
											$('#report-view-div').html(data.code);
											scroller();
										}
				});
	 			return false;
			});
			$('#gen-rep-btn').click(function(e){
	 			$("#report_form").rOkay({
					btn_load		: 	$('#gen-rep-btn'),
					passTo			: 	goPassTo+'/true',
					bnt_load_remove	: 	true,
					asJson			: 	false,
					onComplete		:	function(data){
											// $('.report-submit-btn').removeAttr('disabled');
										}
				});
				return false;
			});
			$('#pdf-rep-btn').click(function(e){
	 			$("#report_form").rOkay({
					btn_load		: 	$('#pdf-rep-btn'),
					passTo			: 	goPassTo,
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											$('#report-view-div').html(data.code);
											scroller();
											$('#report-view-div').print();
										}
				});
				return false;
			});
			$('#gen-excel-btn').click(function(e){



	 			var noError = $("#report_form").rOkay({
					btn_load		: 	$('#gen-excel-btn'),
					bnt_load_remove	: 	true,
					goSubmit		:   false
				});
				if(noError){
					var formData = $("#report_form").serialize();
					// str = $('#daterange').val();
					// daterange = str.replace(/\//g,'-');
					window.location = baseUrl+goPassTo+"_excel?"+formData;

				}
				// alert(baseUrl+goPassTo+"_excel");
				return false;
			});
		}
		function scroller(){
			var scrolled = 0;
			$("#order-scroll-down-btn").on("click" ,function(){
			    // var scrollH = parseFloat($("#report-view-div")[0].scrollHeight) - 150;
			    // if(scrolled < scrollH){
			    	var inHeight = $("#report-view-div")[0].scrollHeight;
			    	var divHeight = $("#report-view-div").height();
			    	var trueHeight = inHeight - divHeight;
				    if((scrolled + 150) > trueHeight){
				    	scrolled = trueHeight;
				    }
				    else{
					    scrolled=scrolled+150;				    	
				    }
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					});
			    // }
			});
			$("#order-scroll-up-btn").on("click" ,function(){
				if(scrolled > 0){
					scrolled=scrolled-150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					});
				}
			});
			$("#report-view-div").bind("mousewheel",function(ev, delta) {
			    var scrollTop = $(this).scrollTop();
			    $(this).scrollTop(scrollTop-Math.round(delta));
			    scrolled=scrollTop-Math.round(delta);
			});
		} 		
	<?php elseif($use_js == 'actLogsJs'): ?>
		$('#daterange').daterangepicker({separator: ' to '});
		$('.excel-btn').click(function(){
			var formData = $('#search-form').serialize();
			var href = $(this).attr('href');
			window.location = href+'?'+formData;
			return false;
		});
	<?php elseif($use_js == 'salesRepJs'): ?>
		$('#daterange').daterangepicker({separator: ' to '});

		$('#search-btn').click(function(){
			$('#print-div').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-2x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'reports/sales_rep';


			dr = $('#daterange').val();
			formData = 'daterange='+dr;
			$.post(this_url, formData, function(data){
				// alert(data);
				$('#print-div').html(data.code);
			// });
			},'json');
		});

		$('#print-btn').click(function(){
			//$('#print-div').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-2x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'reports/sales_rep/'+true;


			dr = $('#daterange').val();
			formData = 'daterange='+dr;
			$.post(this_url, formData, function(data){
				// alert(data);
				$('#print-div').html(data.code);
			// });
			},'json');
		});

	<?php elseif($use_js == 'drawerJs'): ?>
		//$('#daterange').daterangepicker({separator: ' to '});

		$('#search-btn').click(function(){
			$('#print-div').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-2x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'reports/check_scheds';

			date = $('#date').val();
			user = $('#user').val();
			json = 'false';
			// alert(date+''+user);

			//dr = $('#daterange').val();
			formData = 'date='+date+'&user='+user+'&json='+json;
			$.post(this_url, formData, function(data){
				// alert(data);
				$('#print-div').html(data.code);
			// });
			},'json');
		});

		$('#print-btn').click(function(){
			//$('#print-div').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-2x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'reports/check_scheds';

			date = $('#date').val();
			user = $('#user').val();
			json = 'true';

			// alert(date+''+user);

			//dr = $('#daterange').val();
			formData = 'date='+date+'&user='+user+'&json='+json;
			$.post(this_url, formData, function(data){
				// alert(data);
				//$('#print-div').html(data.code);
			// });
			},'json');
		});
		
	<?php endif; ?>
});
</script>