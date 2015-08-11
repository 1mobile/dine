<script>
$(document).ready(function(){
	<?php if($use_js == 'aranetaPageJs'): ?>
		$('#div-content').rLoad({url:'araneta/files'});

		$('#settings-btn').click(function(event){
			$('#div-content').rLoad({url:'araneta/settings'});
			return false;
		});
		$('#files-btn').click(function(event){
			$('#div-content').rLoad({url:'araneta/files'});
			return false;
		});
	<?php elseif($use_js == 'fileJs'): ?>
		$('#file_date').change(function(){
			load_daily_files();
		});
		function load_daily_files(){
			var date = $('#file_date').val();
			$("#summary-div").html("");
			$("#monthly-div").html("");
			$("#trans-list-div").html("");
			$.post(baseUrl+'araneta/daily_files','file_date='+date,function(data){
				$("#summary-div").html(data.sum);
				$("#trans-list-div").html(data.list);
				$("#monthly-div").html(data.month);
			},'json');
		}
	<?php elseif($use_js == 'settingsJs'): ?>
		$('#save-btn').click(function(e){
 		   $("#settings-form").rOkay({
				btn_load		: 	$('#save-btn'),
				bnt_load_remove	: 	true,
				goSubmit		:   true,
				asJson			:   true,
				onComplete		: 	function(data){
										rMsg(data.msg,'success');
									}
			});
			return false;
		});		
	<?php endif; ?>	
});
</script>