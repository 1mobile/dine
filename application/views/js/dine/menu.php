<script>
$(document).ready(function(){
	<?php if($use_js == 'listFormJs'): ?>
		$('#menus-tbl').rTable({
			loadFrom	: 	 'menu/get_menus',
			noEdit		: 	 true,
			add			: 	 function(){
								goTo('menu/form');
							 }				 	
		});
	<?php elseif($use_js == 'menuFormJs'): ?>
		loader('#details_link');
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var menu_id = $('#menu_id').val();

			if(menu_id == ""){
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			$(tabPane).rLoad({url:baseUrl+loadUrl+menu_id});
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
		
	<?php elseif($use_js == 'menuImageJs'): ?>
				$('#save-image').click(function(){
					
					var noError = $('#images_form').rOkay({
		    			asJson			: 	false,
						btn_load		: 	null,
						goSubmit		: 	false,
						bnt_load_remove	: 	true
		    		});
		    		
		    		if(noError){
		    			$("#images_form").submit(function(e){
						    var formObj = $(this);
						    var formURL = formObj.attr("action");
						    var formData = new FormData(this);
						    $.ajax({
						        url: baseUrl+formURL,
						        type: 'POST',
						        data:  formData,
						//         dataType:  'json',
						        mimeType:"multipart/form-data",
						        contentType: false,
						        cache: false,
						        processData:false,
						        success: function(data, textStatus, jqXHR){
									if(data != ""){
										rMsg(data,'error');
									}
									else{
										rMsg('Image uploaded.','success');
									}
						        },
						        error: function(jqXHR, textStatus, errorThrown){
						        }         
						    });
						    e.preventDefault();
						    e.unbind();
						});
						$("#images_form").submit();
		    		}
		    		return false;
				});
				function readURL(input) {
		        	if (input.files && input.files[0]) {
			            var reader = new FileReader();
			            reader.onload = function (e) {
			            	// alert(e.target.result);
			                $('#target').attr('src', e.target.result);
			                // $('#target').html(e.target.result);
			            }
			            reader.readAsDataURL(input.files[0]);
			        }
			    }
		    	$("#fileUpload").change(function(){
			        readURL(this);
			    });
			    $('#target').click(function(e){
			    	$('#fileUpload').trigger('click');
			    }).css('cursor', 'pointer');
	<?php elseif($use_js == 'detailsLoadJs'): ?>
		$('#save-menu').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-menu'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(typeof data.msg != 'undefined' ){
											$('#menu_id').val(data.id);
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											disEnbleTabs('.load-tab',true);
											rMsg(data.msg,'success');
										}
									}
			});
			return false;
		});
		$('#save-new-menu').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-new-menu'),
				bnt_load_remove	: 	true,
				addData			: 	'new=1',
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(typeof data.msg != 'undefined' ){
											window.location = baseUrl+'menu/form/'+data.id;
											// $('#menu_id').val(data.id);
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											// disEnbleTabs('.load-tab',true);
											// rMsg(data.msg,'success');
										}
									}
			});
			return false;
		});
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
	<?php elseif($use_js == 'scheduleJs'): ?>

		$(".timepicker").timepicker({
	       showInputs: false,
	       minuteStep: 1
	    });
	    var schd_id = $('#menu_sched_id').val();
	    if(schd_id == ''){
	    	$('#add-schedule').attr('disabled','disabled');
	    }
	    $('#add-schedule').click(function(){
	    	$("#schedules_details_form").rOkay({
					btn_load		: 	$('#add-schedule'),
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											// alert(data.id);
											if(data.msg == 'error'){
												msg = 'Day already duplicated in this schedule.';
												rMsg(msg,'error');
											}
											else if(typeof data.msg != 'undefined' ){
												var sel = $('#promo-drop').val();
												$('#group-detail-con').rLoad({url:baseUrl+'menu/schedules_form/'+data.id});
												rMsg(data.msg,'success');
											}
										}
				});
	    	return false;
	    });
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
				$.post(baseUrl+'menu/remove_schedule_promo_details',formData,function(data){
					// alert('zxc');
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
			$('#save-list-form').click(function(){
				// alert('zxczxc');
				return false;
			});
			// $('#add-schedule').click(function(){
			// 	var sched_id = $('#menu_sched_id').val();
			// 	var timeon = $('#time-on').val();
			// 	var timeoff = $('#time-off').val();
			// 	var day = $('#day').val();
			// 	var formData = 'sched_id='+sched_id+'&time_on='+encodeURIComponent(timeon)+'&time_off='+encodeURIComponent(timeoff)+'&day='+day;
			// 	// alert(formData);
			// 	$.post(baseUrl+'menu/menu_sched_details_db',formData,function(data){

			// 		rMsg(data.msg,'success');
			// 	},'json');
			// 	// });
			// 	// return false;

			// 	return false;
			// });
	<?php elseif($use_js == 'recipeLoadJs'): ?>
			$('#item-search').typeaheadmap({
				"source": function(search, process) {
					var url = $('#item-search').attr('search-url');
					var formData = 'search='+search;
					$.post(baseUrl+url,formData,function(data){
						process(data);
					},'json');
				},
			    "key": "key",
			    "value": "value",
			    "listener": function(k, v) {
					$('#item-id-hid').val(v);
					get_item_details(v);
				}
			});

			$('#add-btn').click(function(event)
			{
				event.preventDefault();
				$('#recipe-details-form').rOkay({
					btn_load	         : $('#add-btn'),
					btn_load_remove 	 : true,
					asJson            	 : true,
					onComplete        	 : function(data) {
												if (data.act == 'add') {
													$('#details-tbl').append(data.row);
													rMsg(data.msg,'success');
												} else {
													var i = $('#row-'+data.id);
													$('#row-'+data.id).remove();
													$('#details-tbl').append(data.row);
													rMsg(data.msg,'success');
												}
												get_recipe_total();
												remove_row(data.id);
											}
				});
			});
			function get_item_details(v){
				$.post(baseUrl+'menu/recipe_item_details/'+v,function(data){
					$('#item-cost').val(data.cost);
					$('#item-uom-hid').val(data.uom);
					$('#uom-txt').text(data.uom);
					$('#qty').focus();
				},'json');
			}

			function get_recipe_total()
			{
				var mid = $( '#menu-id-hid' ).val();
				if ( mid != "" ) {
					$.post( baseUrl+'menu/get_recipe_total','menu_id='+mid,function(data){
						$('#total').val(data.total);
					},'json');
				}
			}
			function remove_row(id){
				$('#del-'+id).click(function(){
					$.post(baseUrl+'menu/remove_recipe_item','recipe_id='+id,function(data){
						$('#row-'+id).remove();
						rMsg(data.msg,'success');
					},'json');
					get_recipe_total();
					return false;
				});
			}
			$('#override-price').click(function(event){
				event.preventDefault();
				var mid = $( '#menu-id-hid' ).val();
				if ( mid != "" ) {
					var total = $('#total').val();
					$.post(baseUrl+'menu/override_price_total','menu_id='+mid+'&total='+total,function(data){
						rMsg('<b>Recipe price has been updated</b>','success');
					});
				}
			});

			$('.del-item').click(function(event)
			{
				event.preventDefault();
				var id = $(this).attr('ref');
				$.post(baseUrl+'menu/remove_recipe_item','recipe_id='+id,function(data){
					$('tr#row-'+id).remove();
					get_recipe_total();
					rMsg(data.msg,'success');
				},'json');
			});
	<?php elseif($use_js == 'menuModifierJs'): ?>
			$('#item-search').typeaheadmap({
				"source": function(search, process) {
					var url = $('#item-search').attr('search-url');
					var formData = 'search='+search;
					$.post(baseUrl+url,formData,function(data){
						process(data);
					},'json');
				},
			    "key": "key",
			    "value": "value",
			    "listener": function(k, v) {
					$('#mod-group-id-hid').val(v);

					$('#menu-modifier-form').rOkay({
						btn_load	         : $('#add-btn'),
						btn_load_remove 	 : true,
						asJson            	 : true,
						onComplete        	 : function(data) {
													if (data.result == 'success') {
														$('#details-tbl').append(data.row);
														rMsg(data.msg,'success');
														remove_row(data.id);
													} else {
														rMsg(data.msg,'error');
													}
												}
					});
				}
			});
			// $('#add-btn').click(function(event)
			// {
			// 	event.preventDefault();
			// 	$('#menu-modifier-form').rOkay({
			// 		btn_load	         : $('#add-btn'),
			// 		btn_load_remove 	 : true,
			// 		// asJson            	 : true,
			// 		onComplete        	 : function(data) {
			// 			alert(data);
			// 									// if (data.result == 'success') {
			// 									// 	$('#details-tbl').append(data.row);
			// 									// 	rMsg(data.msg,'success');
			// 									// 	remove_row(data.id);
			// 									// } else {
			// 									// 	rMsg(data.msg,'error');
			// 									// }
			// 									// remove_row(data.id);
			// 								}
			// 	});
			// });
			function remove_row(id){
				$('#del-'+id).click(function(){
					$.post(baseUrl+'menu/remove_menu_modifier','recipe_id='+id,function(data){
						$('#row-'+id).remove();
						rMsg(data.msg,'success');
					},'json');
					return false;
				});
			}
			$('.del-item').click(function(event)
			{
				event.preventDefault();
				var id = $(this).attr('ref');
				$.post(baseUrl+'menu/remove_menu_modifier','id='+id,function(data){
					$('tr#row-'+id).remove();
					rMsg(data.msg,'success');
				},'json');
			});
	<?php endif; ?>
});
</script>