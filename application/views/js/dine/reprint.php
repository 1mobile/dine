<script>
$(document).ready(function(){
	<?php if($use_js == 'printReceiptJs'): ?>
		$('#search-btn').click(function(){
			$("#search-form").rOkay({
				btn_load		: 	$('#search-btn'),
				btn_load_remove	: 	true,
				addData			: 	'change_db=main',
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$("#results-div").html('');
										$("#results-div").html(data.code);
										$.each(data.ids,function(key,id){
											view_div(id);
										});
									}
			});
			return false;
		});
		$('#print-btn').click(function(){
			var id = $('#print-div').attr('ref-id');
			var btn = $(this);
			btn.goLoad();
			if(id != ""){
				$.post(baseUrl+'reprint/view/'+id+'/0',function(data){
					// $('#print-div').html(data);
					btn.goLoad({load:false});
				});
			}
			return false;
		});
		function view_div(id){
			$('#rec-'+id).click(function(){
				var btn = $(this);
				btn.goLoad();
				$('#print-div').html('');
				$('#print-div').attr('ref-id',id);
				$.post(baseUrl+'reprint/view/'+id,function(data){
					$('#print-div').html(data);
					btn.goLoad({load:false});
				});
				return false;
			});
		}
	<?php endif; ?>
});
</script>