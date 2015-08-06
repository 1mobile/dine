<script>
$(document).ready(function(){
	<?php if($use_js == 'restaurantJS'): ?>
		// alert('zxczxc');
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
	    $('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});

		loader('#details_link');
	    function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var sel = $('#res_id').val();
			if(sel == ""){
				sel = 'add';
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			var res_id = $('#res_id').val();
			// alert(res_id);
			// alert(baseUrl+loadUrl+res_id);
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+res_id});
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
    <?php elseif($use_js == 'restaurantDetailsJS'): ?>
    	$('#save-btn').click(function(){
    		var noError = $('#resto_details_form').rOkay({
    			asJson			: 	false,
				btn_load		: 	null,
				goSubmit		: 	false,
				bnt_load_remove	: 	true
    		});
    		if(noError){
    			$("#resto_details_form").submit(function(e){
				    var formObj = $(this);
				    var formURL = formObj.attr("action");
				    var formData = new FormData(this);
				    $.ajax({
				        url: baseUrl+formURL,
				        type: 'POST',
				        data:  formData,
				        dataType:  'json',
				        mimeType:"multipart/form-data",
				        contentType: false,
				        cache: false,
				        processData:false,
				        success: function(data, textStatus, jqXHR){
				         	rMsg(data.msg,'success');
							disEnbleTabs('.load-tab',true);
				        },
				        error: function(jqXHR, textStatus, errorThrown){
				        }          
				    });
				    e.preventDefault();
				    e.unbind();
				}); 
				$("#resto_details_form").submit();
    		}
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
    <?php elseif($use_js == 'restaurantTaxesJS'): ?>
    	$('#add-tax').click(function(){
			$("#resto_tax_form").rOkay({
				btn_load		: 	$('#add-tax'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										tax_remove(data.id);
										rMsg(data.msg,'success');
										// if(typeof data.msg != 'undefined' ){
										// 	// $('#branch-drop').append($('<option>', {
										// 	//     value: data.id,
										// 	//     text: data.desc
										// 	// }).prop('selected', true));
										// 	disEnbleTabs('#taxes_link',true);
										// 	rMsg(data.msg,'success');
										// }
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
		$('.del-tax').each(function(){
    		var id = $(this).attr('ref');
    		tax_remove(id);
    	});
		function tax_remove(id){
    		$('#del-tax-'+id).click(function(){alert('asd');
    			var link = $(this);
    			$.post(baseUrl+'restaurants/resto_disc_db','remove_tax='+id,function(data){
    				rMsg(data.msg,'success');
    				link.parent().remove();
    			},'json');
    			return false;
    		});
    	}
    <?php elseif($use_js == 'restaurantDiscountJS'): ?>
   		// alert('DISC AKO');
    	$('#add-disc').click(function(){
			$("#resto_disc_form").rOkay({
				btn_load		: 	$('#add-disc'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										$('#disc-list').append(data.li);
										rMsg(data.msg,'success');
										disc_remove(data.id);
										// alert(data);
										// if(typeof data.msg != 'undefined' ){
										// 	// $('#branch-drop').append($('<option>', {
										// 	//     value: data.id,
										// 	//     text: data.desc
										// 	// }).prop('selected', true));
										// 	disEnbleTabs('#discounts_link',true);
										// }
									}
			});
			return false;
		});
    	$('.del-disc').each(function(){
    		var id = $(this).attr('ref');
    		disc_remove(id);
    	});
    	function disc_remove(id){
    		$('#del-disc-'+id).click(function(){
    			var link = $(this);
    			$.post(baseUrl+'restaurants/resto_disc_db','remove_disc='+id,function(data){
    				rMsg(data.msg,'success');
    				link.parent().remove();
    			},'json');
    			return false;
    		});
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
	<?php endif; ?>
});
</script>