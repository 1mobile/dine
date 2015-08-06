<script>
$(document).ready(function(){
	<?php if($use_js == 'rolesJs'): ?>
		$(".check").click(function(){
			var id = $(this).attr('id');
			var ch = false
			if($(this).is(':checked'))
				var ch = true;
			$('.'+id).prop('checked',ch);

			var parent = $(this).attr('parent');
			if (typeof parent !== 'undefined' && parent !== false) {
			   parentCheck(ch,parent); 
			}

			// var classList = $(this).attr('class').split(/\s+/);
			// var chk = $(this);
			
			// $.each( classList, function(key, parent){
			// });
		});

		function parentCheck(ch,parent){
			if(parent != "check"){
				var par = $('#'+parent);
				if(!ch){
					var ctr = 0;
					$('.'+parent).each(function(){
						if($(this).is(':checked'))
							ctr ++;
					});
					if(ctr == 0)
						par.prop('checked',ch)
				}
				else
					par.prop('checked',ch);
				
				var parentParent = par.attr('parent');
				if (typeof parentParent !== 'undefined' && parentParent !== false) {
					parentCheck(ch,parentParent);	
				}

			}
		}
	<?php elseif($use_js == 'usersListJs'): ?>
		$('#users-tbl').rTable({
			loadFrom	: 	 'user/get_users',
			add			: 	 function(){goTo('user/users_form')},
			edit		: 	 function(id){goTo('user/users_form/'+id);}				 	
		});
	<?php elseif($use_js == 'userFormJs'): ?>	
		// $('#save-btn').click(function(){
		// 	$('#users_form').rOkay({
		// 		asJson				: 	false,
		// 		bnt_load_remove		: 	false,
		// 		btn_load			: 	$(this),
		// 		onComplete			: 	function(data){
		// 									goTo('user');
		// 								}
		// 	});
		// 	return false;
		// });	
		$('#save-btn').click(function(){
			var btn = $(this);
			var noError = $('#users_form').rOkay({
    			btn_load		: 	btn,
				bnt_load_remove	: 	true,
				goSubmit		: 	false,
    		});
    		
    		if(noError){
    			btn.goLoad();
    			$("#users_form").submit(function(e){
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
			    			btn.goLoad({load:false});
							goTo('user');
				        },
				        error: function(jqXHR, textStatus, errorThrown){
				        }         
				    });
				    e.preventDefault();
				    e.unbind();
				});
				$("#users_form").submit();
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
	<?php elseif($use_js == 'restartJs'): ?>
		  $('#restart-pos').click(function(){
			  $.callManager({
			  	success : function(){
			  		$('#restart-pos').goLoad2();
			  		$.post(baseUrl+'admin/go_restart',function(data){
				  		$('#restart-pos').goLoad2({load:false});
			  			window.location = baseUrl;
			  		});
			  	}
			  });
			  return false;
		  });
	<?php endif; ?>
});
</script>