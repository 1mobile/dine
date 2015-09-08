<script>
$(document).ready(function(){
	<?php if($use_js == 'megamallPageJs'): ?>
		$('#div-content').rLoad({url:'megamall/files'});

		$('#settings-btn').click(function(event){
			$('#div-content').rLoad({url:'megamall/settings'});
			return false;
		});
		$('#files-btn').click(function(event){
			$('#div-content').rLoad({url:'megamall/files'});
			return false;
		});
	<?php elseif($use_js == 'fileJs'): ?>
		$('#file_date').change(function(){
			load_daily_files();
		});
		function load_daily_files(){
			var date = $('#file_date').val();
			$("#daily-div").html("");
			$.post(baseUrl+'megamall/daily_files','file_date='+date,function(data){
				$("#daily-div").html(data.daily);
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