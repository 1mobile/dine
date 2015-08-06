<script>
$(document).ready(function(){
	<?php if($use_js == 'couponsFormJs'): ?>
		$('#save-btn').click(function(){
			$("#coupons_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	false,
				onComplete		:	function(data){
										window.location = baseUrl+'coupons';
									}
			});
			setTimeout(function(){
				window.location.reload();
			},1500);
			return false;
		});		
	<?php endif; ?>
});
</script>