<script>
$(document).ready(function(){
	<?php if($use_js == 'detailsJs'): ?>
		$(".timepicker").timepicker({
            showInputs: false
        });
		$('#save-btn').click(function(event){
			event.preventDefault();
			// $("#details_form").rOkay({
			// 	btn_load		: 	$('#save-btn'),
			// 	bnt_load_remove	: 	true,
			// 	asJson			: 	false,
			// 	onComplete		:	function(data){
			// 							alert(data);
			// 							rMsg(data.msg,'success');
			// 						}
			// });
			var formData = $('#details_form').serialize();
			var dtype = 'json';
			$.post(baseUrl+'setup/details_db',formData,function(data)
			{
				// alert(data);
				rMsg(data.msg,'success');
			},'json');
			// });
			// alert(formData);

		// 	$.ajax({
		//         url: baseUrl+'setup/details_db',
		//         type: 'POST',
		//         data:  formData,
		//         dataType:  dtype,
		//         mimeType:"multipart/form-data",
		//         contentType: false,
		//         cache: false,
		//         processData:false,
		//         success: function(data, textStatus, jqXHR){
		// 			// alert(data);
		// //          	settings.onComplete.call(this,data);
		// 				rMsg(data.msg,'success');
		//         },
		//         error: function(jqXHR, textStatus, errorThrown){
		// 			console.log(jqXHR);
		// 			console.log(textStatus);
		// 			console.log(errorThrown);
		//         }         
		//     });
			return false;
		});

		$('#save-pos-btn').click(function(event){
			$("#settings_form").rOkay({
				btn_load		: 	$('#save-pos-btn'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										rMsg(data.msg,'success');
									}
			});
			return false;
		});

		$('#target').click(function(e){
	    	$('#complogo').trigger('click');
	    }).css('cursor', 'pointer');

	    $('#upload-splsh-img').rPopFormFile({
	    // $('#upload-splsh-img').rPopForm({
	    	asJson	  : true,
	    	hide	  : true,
	    	onComplete: function(data){
	    		if(data.msg == "Image uploaded"){
		    		// rMsg(data.msg,'success');
		    		location.reload();
	    		}
		    	else{
		    		rMsg(data.msg,'error');
		    	}
	    	}
	    });
	    $('.del-spl-btn').click(function(){
	    	var img_id = $(this).attr('ref');
	    	var img = $(this);
	    	$.post(baseUrl+'setup/delete_splash_img/'+img_id,function(data){
	    		if(data.error == ""){
		    		img.parent().remove();
	    			rMsg('Image removed.','success');
	    		}
	    		else{
	    			rMsg(data.error,'error');
	    		}
	    	},'json');
	    	// alert(data);
	    	// });
	    	return false;
	    });
	<?php elseif($use_js == 'referencesJs'): ?>
		// alert('asd');
		$('.save_btn').click(function(){
			var type_id = $(this).attr('ref');
			var name = $(this).attr('label');
			var next_ref = $('#type-'+type_id).val();
			var formData = 'type_id='+type_id+'&next_ref='+next_ref+'&name='+name;

			// alert(formData);

			$.post(baseUrl+'settings/references_db', formData, function(data){
				rMsg(data.msg,'success');
			}, 'json');

			// $.post(baseUrl+'settings/references_db', formData, function(data){
				// alert(data);
				// // rMsg(data.msg,'success');
			// });

			return false;
		});
	<?php elseif($use_js == 'uploadSplashImagePopJs'): ?>
		function readURL(input) {
        	if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (e) {
	                $('#target').attr('src', e.target.result);
	            }
	            reader.readAsDataURL(input.files[0]);
	        }
	    }
    	$("#fileUpload").change(function(){
	        readURL(this);
	    });
	    $('#select-img').click(function(e){
	    	$('#fileUpload').trigger('click');

	    }).css('cursor', 'pointer');
	<?php endif; ?>
});
</script>