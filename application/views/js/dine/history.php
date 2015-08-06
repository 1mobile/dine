<script>
$(document).ready(function(){
	<?php if($use_js == 'historyJs'): ?>
			$('.dt-tbl').dataTable({"aaSorting": [],
				"aLengthMenu": [[15, 25, 50, 100], [15, 25, 50, 100]],
				"iDisplayLength": 50
});
	<?php endif; ?>
});
</script>