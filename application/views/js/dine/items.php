<script>
$(document).ready(function(){
	<?php if($use_js == 'itemFormContainerJs'): ?>
		$('.tab_link').click(function(event)
		{
			event.preventDefault();
			var id = $(this).attr('id');
			loader('#'+id);
		});
		loader('#details_link');
		function loader(btn)
		{
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var selected = $('#item_idx').val();
			if (selected == '') {
				selected = 'add';
				disableTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			} else {
				disableTabs('.load-tab',true);
			}
			var item_id = $('#item_idx').val();
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+item_id});
		}
		function disableTabs(id,enable)
		{
			if (enable) {
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			} else {
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}
	<?php elseif($use_js == 'itemDetailsJs'): ?>
		$('#save-btn').click(function(){
			$("#item_details_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										rMsg(data.msg,'success');
									}
			});
			return false;
		});

		$('#cat_id').on('change',function()
		{
			var cat_id = $(this).val();
			var passUrl = baseUrl + 'items/get_subcategories/' + cat_id;

			$('#subcat_id').empty();
			$.post(passUrl,'',function(data)
			{
				str = '';
				$.each(data,function(key,value)
				{
					str = str + "<option value='" + key + "'>" + value + "</option>";
				});
				$('#subcat_id').append(str);
			},'json');
		});
		$('#cat_id').change();

	<?php elseif($use_js == 'inventoryJS'): ?>
		$('#print-btn').click(function(){

			window.location = baseUrl+'items/print_inventory';

		});

	<?php endif; ?>
});
</script>