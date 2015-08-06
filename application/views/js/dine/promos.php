<script>
$(document).ready(function(){
	<?php if($use_js == 'promosJs'): ?>
		loader('#details_link');

		$(".timepicker").timepicker({
		    showInputs: false
		});
		$('#add-new-promo').click(function(){
			// var vald = $('#promo_id').val();

			// if(vald == '')	$('#save-btn').trigger('click');
			$('#promo-drop option:first-child').attr("selected", "selected");
			loader('#details_link');
		});

		$('#promo-drop').change(function(){
			var sel = $(this).val();
			if(sel == ''){
				$('#add-new-promo').trigger('click');
				$('#add-new-promo').removeClass('disabled');
				$('#add-schedule').addClass('disabled');
				$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			}else{
				$('#add-new-promo').addClass('disabled');
				$('#add-schedule').addClass('disabled');
				$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			}

		});

		$('.tab_link').click(function(){
				var id = $(this).attr('id');
				loader('#'+id);
			});

		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var sel = $('#promo-drop').val();

			if(sel == ""){
				sel = 'add';
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
				// $('#assign').removeClass('disabled');
				// $('#add-new-promo').removeClass('disabled');
				// alert('zxc');
			}
			else{
				// $('#assign').addClass('disabled');
				// $('#add-new-promo').addClass('disabled');
				disEnbleTabs('.load-tab',true);
			}
			var res_id = $('#res_id').val();
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+sel});
		}
		function disEnbleTabs(id,enable){
			if(enable){
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			}
			else{
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}

	<?php elseif($use_js == 'promoDetailsJs'): ?>
		var promo = $('#promo-drop').val();
		if(promo == '')
			$('#add-schedule').addClass('disabled');

		$('.del-sched').each(function(){
			var id = $(this).attr('ref');
			deleteSched(id);
		});
		function deleteSched(id){
			$('#del-sched-'+id).click(function(){
				// alert(id);
				var formData = 'pr_sched_id='+id;
				var li = $(this).parent().parent();
				// alert(baseUrl+'settings/remove_promo_details');
				$.post(baseUrl+'settings/remove_promo_details',formData,function(data){
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#staff-list li').length;
					if(noLi == 0){
						$('ul#staff-list').append('<li class="no-staff"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Staffs found.</span></li>');
					}
					// });
				},'json');
				return false;
			});
		}

		$(".timepicker").timepicker({
                    showInputs: false,
                    minuteStep: 1
                });
		$('#save-btn').click(function(){
			$("#promo-form").rOkay({
				btn_load		: 	$('#save-btn'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(typeof data.msg != 'undefined' ){
											// var sel = $('#promo-drop').val();
											// $('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
											// rMsg(data.msg,'success');
											$('#save-btn').attr('disabled','disabled');
											rMsg(data.msg,'success');
											setTimeout(function() {
											      // Do something after 2 seconds
												window.location = baseUrl+'settings/promos';
											}, 1000);
										}
									}
			});
			return false;
		});
		$('#add-schedule').click(function(){
			$("#promo-details-form").rOkay({
				btn_load		: 	$('#add-schedule'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										// alert(data.msg);
										if(data.msg == 'error'){
											msg = 'Day already duplicated in this promo.';
											rMsg(msg,'error');
										}
										else if(typeof data.msg != 'undefined' ){
											var sel = $('#promo-drop').val();
											$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
											msg = 'Schedule has been added.';
											rMsg(msg,'success');

										}
									}
			});
			return false;
		});

		$('.dels').click(function(){
			ref = $(this).attr('ref');

			formData = 'ref='+ref;
			$.post(baseUrl+'settings/remove_schedule',formData,function(data){
				
				rMsg('Schedule has been deleted.','success');
				var sel = $('#promo-drop').val();
				$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			});
		});

	<?php elseif($use_js == 'assignedItemPromoJs'): ?>
		$('.del-item').each(function(){
			var id = $(this).attr('ref');
			deleteSched(id);
		});

		function deleteSched(id){
			$('#del-item-'+id).click(function(){
				//alert(id);
				var formData = 'pr_item_id='+id;
				var li = $(this).parent();
				// alert(baseUrl+'settings/remove_promo_details');
				$.post(baseUrl+'settings/remove_promo_items',formData,function(data){
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#item-list li').length;
					if(noLi == 0){
						$('ul#item-list').append('<li class="no-item"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Item found.</span></li>');
					}
					// });
				},'json');
				return false;
			});
		}
		$('#add-item').click(function(){
			$("#assignedItem_form").rOkay({
				btn_load		: 	$('#add-item'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										//alert(data);
										if(data.msg == 'error'){
											msg = 'Item already duplicated in this promo.';
											rMsg(msg,'error');
										}
										else if(typeof data.msg != 'undefined' ){
											var sel = $('#promo-drop').val();
											$('#assign').rLoad({url:baseUrl+'settings/assign_load/'+sel});
											msg = 'Item successfully added.';
											rMsg(msg,'success');
										}
									}
			});
			return false;
		});
		$('.del-staff').click(function(){
			ref = $(this).attr('ref');
			formData = 'ref='+ref;
			$.post(baseUrl+'settings/remove_item_assign',formData,function(data){
				
				rMsg('Item has been deleted.','success');
				var sel = $('#promo-drop').val();
				$('#assign').rLoad({url:baseUrl+'settings/assign_load/'+sel});
			});
			// },'json');

		});
	<?php endif; ?>
});
</script>