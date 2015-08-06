<script>
$(document).ready(function(){
	<?php if($use_js == 'endofdayJs'): ?>
		$('#endofday-content').rLoad({url:'endofday/summary'});
		$('#day-report-btn').click(function(){
			$('#endofday-content').rLoad({url:'endofday/summary'});
			return false;
		});
		$('#day-xread-btn').click(function(event){
			$('#endofday-content').rLoad({url:'endofday/end_shift'});
			// event.preventDefault();
			// $('#endofday-content').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-4x fa-fw fa-spin aw"></i></div></center>');
			// var this_url = baseUrl+'manager/manager_xread';

			// $.post(this_url, {}, function(data){
			// 	$('#endofday-content').html(data);
			// });
			return false;
		});
		$('#day-zread-btn').click(function(event){
			$('#endofday-content').rLoad({url:'endofday/end_day'});
			return false;
			
			// $('#endofday-content').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-4x fa-fw fa-spin aw"></i></div></center>');
			// var this_url = baseUrl+'manager/manager_zread';

			// $.post(this_url, {}, function(data){
			// 	$('#endofday-content').html(data);
			// });
		});
	<?php elseif($use_js == 'summaryJs'): ?>	
		load_trans_chart();
		function load_trans_chart(){
			$('#bar-chart').goLoad();
			$('#bars-div').goLoad();
			$.post(baseUrl+'endofday/summary_orders',function(data){
				// alert(data.orders);
				var orders = new Array();
				$.each(data.orders,function(key,ord){
					orders.push(ord);
				});
				var bar = new Morris.Bar({
		            element: 'bar-chart',
		            resize: true,
		            data: orders,
		            barColors: ["#428BCA", "#00A65A","#F39C12", "#F56954"],
		            xkey: 'label',
		            ykeys: ['open','settled','cancel','void'],
		            labels: ['open','settled','cancel','void'],
		            hideHover: 'auto'
		  		});
				$('#bar-chart').goLoad({load:false});
				$('#bars-div').html(data.code);
			// });
			},'json');
		}			
	<?php elseif($use_js == 'endShiftJs'): ?>
		show_x_read();
		function show_x_read(){
			if($('#eod_form').exists()){
				var formData = $('#eod_form').serialize();
				var ur = $('#eod_form').attr('action')+'/0/1';
				$.post(ur,formData,function(data){
					// alert(data);
					$('#report-view-div').html(data.code);
				// });
				},'json');
			}
		}
		$('#end-shift-btn').click(function(){
			$(this).goLoad();
			$.rProgressBar();
			$.post(baseUrl+'endofday/read_shift_sales',function(data){
				$.rProgressBarEnd({
					onComplete : function(){
						if(data.error == ""){
							window.location = baseUrl+'site/end_shift';
						}
						else{
							rMsg(data.error,'error');
						}
					 }
				});

			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#end-shift-print-btn').click(function(){
			$(this).goLoad();
			$.rProgressBar();
			$.post(baseUrl+'endofday/read_shift_sales',function(data){
				$.rProgressBarEnd({
					onComplete : function(){
						if(data.error == ""){
								var formData = $('#eod_form').serialize();
								var ur = $('#eod_form').attr('action')+'/1/1/1';
								$.post(ur,formData,function(data){
									window.location = baseUrl+'site/end_shift';		
									// alert(data)				
								});
							}
							else{
								rMsg(data.error,'error');
						}
					 }
				});
			},'json');
			return false;
		});

		scroller();
		function scroller(){
			var scrolled = 0;
			$("#order-scroll-down-btn").on("click" ,function(){
			    // var scrollH = parseFloat($("#report-view-div")[0].scrollHeight) - 150;
			    // if(scrolled < scrollH){

				    scrolled=scrolled+150;
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
	<?php elseif($use_js == 'endDayJs'): ?>	
		show_z_read();
		function show_z_read(){
			if($('#eod_form').exists()){
				var formData = $('#eod_form').serialize();
				var ur = $('#eod_form').attr('action')+'/0/1';
				$.post(ur,formData,function(data){
					// alert(data);
					$('#report-view-div').html(data.code);
				// });
				},'json');
			}
		}
		$('#end-day-btn').click(function(){
			$(this).goLoad();
			$.rProgressBar();
			$.post(baseUrl+'endofday/read_end_day_sales',function(data){
				$.rProgressBarEnd({
					onComplete : function(){
						window.location.reload();
					 }
				});
			});
			return false;
		});
		$('#end-day-print-btn').click(function(){
			$(this).goLoad();
			$.rProgressBar();
			$.post(baseUrl+'endofday/read_end_day_sales',function(data){
				$.rProgressBarEnd({
					onComplete : function(){
						// var formData = 'daterange='+$("#read_date").val();
						var formData = $('#eod_form').serialize();
						var ur = $('#eod_form').attr('action')+'/1';
						$.post(ur,formData,function(data){
							// alert(data);
							window.location.reload();			
						});
					 }
				});
			});
			return false;
		});

		scroller();
		function scroller(){
			var scrolled = 0;
			$("#order-scroll-down-btn").on("click" ,function(){
			    // var scrollH = parseFloat($("#report-view-div")[0].scrollHeight) - 150;
			    // if(scrolled < scrollH){

				    scrolled=scrolled+150;
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
	<?php endif; ?>
});
</script>