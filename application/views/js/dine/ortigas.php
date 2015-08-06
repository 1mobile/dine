<script>
$(document).ready(function(){
	<?php if($use_js == 'ortigasPageJs'): ?>
		$('#ortigas-content').rLoad({url:'ortigas/dailyFileRead'});
		
		$('#daily-sales-btn').click(function(){
			$('#ortigas-content').rLoad({url:'ortigas/daily_sales'});
			return false;
		});
		$('#hourly-sales-btn').click(function(event){
			$('#ortigas-content').rLoad({url:'ortigas/hourly_sales'});
			return false;
		});
		$('#invoice-sales-btn').click(function(event){
			$('#ortigas-content').rLoad({url:'ortigas/invoice_sales'});
			return false;
		});
		$('#settings-btn').click(function(event){
			$('#ortigas-content').rLoad({url:'ortigas/settings'});
			return false;
		});
		$('#daily-read-sales-btn').click(function(event){
			$('#ortigas-content').rLoad({url:'ortigas/dailyFileRead'});
			return false;
		});
	<?php elseif($use_js == 'generateJs'): ?>
		$('#generate-btn').click(function(e){
 		   $("#generate_form").rOkay({
				btn_load		: 	$('#generate-btn'),
				bnt_load_remove	: 	true,
				goSubmit		:   true,
				onComplete		: 	function(data){
										rMsg(data,'success');
									}
			});
			// if(noError){
			// 	var formData = $("#generate_form").serialize();
			// 	var passTo = $("#generate_form").attr('action');
			// 	window.location = baseUrl+passTo+"?"+formData;
			// }
			return false;
		});	
	<?php elseif($use_js == 'dailyFileReadJS'): ?>
		$('.view_file').rPopView();
	<?php elseif($use_js == 'generateJs'): ?>
		$('#generate-btn').click(function(e){
 		   $("#generate_form").rOkay({
				btn_load		: 	$('#generate-btn'),
				bnt_load_remove	: 	true,
				goSubmit		:   true,
				onComplete		: 	function(data){
										rMsg(data,'success');
									}
			});
			// if(noError){
			// 	var formData = $("#generate_form").serialize();
			// 	var passTo = $("#generate_form").attr('action');
			// 	window.location = baseUrl+passTo+"?"+formData;
			// }
			return false;
		});	
	<?php endif; ?>
});
</script>