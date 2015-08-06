<script>
$(document).ready(function(){
	<?php if($use_js == 'seatingJs'): ?>
		displayImage();
		$('#change-img').rPopFormFile({
			asJson	  : true,
			onComplete: function(data){
				rMsg(data.msg,'success');
				$('#imgSrc').val(baseUrl+data.src);				
				$('#imgCon').html("");
				// alert(data.src);
				$('#change-img').html('<i class="fa fa-picture-o"></i> Change Image');
				bootbox.hideAll();
				displayImage();
			}
		});
		function displayImage(){
			var img = $('#imgSrc').val();
			if(img != ""){
				var img_real_width=0,
				    img_real_height=0;
				$("<img/>")
				    .attr("src", img)
				    .attr("id", "imgLayout")
				    .load(function(){
			           img_real_width = this.width;
			           img_real_height = this.height;
			           $(this).appendTo('#imgCon');
			           $("<div/>")
					    .attr("class", "rtag")
					    .attr("id", "rtag-div")
					    .css("height", img_real_height)
					    .css("width", img_real_width)
					    .css("left", '15px')
					    .appendTo('#imgCon')
					    .click(function(e){
			    			var offset_t = $(this).offset().top - $(window).scrollTop();
							var offset_l = $(this).offset().left - $(window).scrollLeft();
							var left = Math.round( (e.clientX - offset_l) );
							var top = Math.round( (e.clientY - offset_t) );
			    			showDialog('',top,left);
				    		return false;
			   			});
			   			loadMarks(); 
				    });  
			}
		}
		function loadMarks(){
			$.post(baseUrl+'settings/get_tables',function(data){
				$.each(data,function(key,val){
					$('<a/>')
		    			.attr('href','#')
		    			.attr('class','marker')
		    			.attr('id','mark-'+key)
		    			.css('top',val.top+'px')
		    			.css('left',val.left+'px')
		    			.appendTo('#rtag-div')
		    			.click(function(e){
		    				showDeleteDialog(key,val.top,val.left);
		    				return false;
	    			});
				});
			},'json');
		}
		function showDialog(tbl_id,top,left){
			bootbox.dialog({
			  message: baseUrl+'settings/tables_form/'+tbl_id,
			  title: 'Table Details',
			  buttons: {
			    cancel: {
			      label: "Cancel",
			      className: "btn-default",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    },
			    submit: {
			      label: "<i class='fa fa-plus'></i> Add",
			      className: "btn-primary rFormSubmitBtn",
			      callback: function() {
						$('#table_form').rOkay({
								passTo		: 	$('#table_form').attr('action'),
								addData		: 	'top='+top+'&left='+left,
								asJson		: 	true,
								btn_load	: 	$('.rFormSubmitBtn'),
								onComplete	: 	function(data){
													var tbl_id = data.id;
													$('<a/>')
										    			.attr('href','#')
										    			.attr('class','marker')
										    			.attr('id','mark-'+tbl_id)
										    			.css('top',top+'px')
										    			.css('left',left+'px')
										    			.appendTo('#rtag-div')
										    			.click(function(e){
										    				showDeleteDialog(tbl_id,top,left)
										    				return false;
									    			});
										    		bootbox.hideAll();	
												}
						});
						// alert('something');
						return false;	
			      }
			    }
			  }
			});
		}
		function showDeleteDialog(tbl_id,top,left){
			
			bootbox.dialog({
			  message: baseUrl+'settings/tables_form/'+tbl_id,
			  title: 'Table Details',
			  buttons: {
			    cancel: {
			      label: "Cancel",
			      className: "btn-default",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    },
			    submit: {
			      label: "<i class='fa fa-save'></i> Update",
			      className: "btn-primary rFormSubmitBtn",
			      callback: function() {
						$('#table_form').rOkay({
								passTo		: 	$('#table_form').attr('action'),
								addData		: 	'top='+top+'&left='+left,
								asJson		: 	true,
								btn_load	: 	$('.rFormSubmitBtn'),
								onComplete	: 	function(data){
													rMsg(data.msg,'success');
										    		bootbox.hideAll();	
												}
						});
						// alert('something');
						return false;	
			      }
			    },
			    "Delete": {
			      label: "<i class='fa fa-trash-o'></i> Delete",
			      className: "btn-danger rFormSubmitBtn",
			      callback: function() {
						$('#table_form').rOkay({
								passTo		: 	$('#table_form').attr('action'),
								addData		: 	'delete='+tbl_id,
								asJson		: 	true,
								btn_load	: 	$('.rFormSubmitBtn'),
								onComplete	: 	function(data){
													rMsg(data.msg,'success');
													$('#mark-'+data.id).remove();
										    		bootbox.hideAll();	
												}
						});
						// alert('something');
						return false;	
			      }
			    }
			  }
			});
		}    	
	<?php elseif($use_js == 'uploadImageSeatJs'): ?>
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