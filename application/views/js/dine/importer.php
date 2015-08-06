<script>
$(document).ready(function(){
	<?php if($use_js == 'importJs'): ?>
		$.rProgressBar();
		$.post(baseUrl+'main/do_import',function(data){
			$.rProgressBarEnd({
				onComplete : function(){
					alert(data);
				 }
			});
			// alert(data);
		});
		// alert('here');
	
	<?php endif; ?>
});
</script>