<script>
$(document).ready(function(){
	<?php if($use_js == 'branchesJs'): ?>
		$('div#map-canvas').hide();
		loader('#details_link');
		$('#add-new-branch').click(function(){
			$('#branch-drop option:first-child').attr("selected", "selected");
			loader('#details_link');
			return false;
		});
		$('#branch-drop').change(function(){
			var active = $('.nav-tabs').find('li.active');
			var id = active.find('a').attr('id');
			loader('#'+id);
		});
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var sel = $('#branch-drop').val();
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
			$(tabPane).rLoad({url:baseUrl+loadUrl+sel+'/'+res_id});
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
	<?php elseif($use_js == 'branchesDetailsJs'): ?>
		function reset_marker(){
			$('#gmap').gmap3({action:'clear', tag:'pntr'});
		}
		function relocate(){
			// alert($('#base_loc').val());
			$('#gmap').gmap3({
			 map: {
			    options: {
			      // maxZoom: 16,
			      zoom: 14,
			    }
			 },
			 tag:"pntr",
			 marker:{
			    // address: "SM Megamall",
			    // name:'searchMarker',
			    tag:'test',
			    address: $('#base_loc').val(),
			    options: {
			     // content: 'SM Megamall',
			     icon: new google.maps.MarkerImage(
			       // "http://gmap3.net/skin/gmap/magicshow.png",
			       "http://maps.gstatic.com/mapfiles/marker_green.png",
			       // baseUrl+"img/subway.png",
			       new google.maps.Size(32, 37, "px", "px")
			     )
			    }
			 }
			},
			"autofit" );
		}

		// relocate();
		$('#base_loc').focusout(function(){
			reset_marker();
			// relocate();
		});
		$('#save-branch').click(function(){
			$("#branch_form").rOkay({
				btn_load		: 	$('#save-branch'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(typeof data.msg != 'undefined' ){
											var sel = $('#branch-drop').val();
											var res_id = $('#res_id').val();
											$('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											// window.location = baseUrl+"restaurants";
											// $('#branch-drop').append($('<option>', {
											//     value: data.id,
											//     text: data.desc
											// }).prop('selected', true));
											// disEnbleTabs('.load-tab',true);
											rMsg(data.msg,'success');
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
	<?php elseif($use_js == 'branchesStaffJs'): ?>
		$('#add-staff').click(function(){
			$("#staff_form").rOkay({
				btn_load		: 	$('#add-staff'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(data.found == 0){
											$('.no-staff').remove();
											$('#staff-list').append(data.li);
											deleleteStaff(data.id);
											rMsg(data.msg,'success');
										}
										else{
											rMsg(data.msg,'error');
										}
									}
			});
			return false;
		});
		$('#type').change(function(){
			var access = $(this).find(':selected').attr('access');

			$('#staff-access').val(access);
		});
		$('.del-staff').each(function(){
			var id = $(this).attr('ref');
			deleleteStaff(id);
		});
		function deleleteStaff(id){
			$('#del-staff-'+id).click(function(){
				var formData = 'br_staff_id='+id;
				var li = $(this).parent();
				$.post(baseUrl+'branches/branch_staffs_db',formData,function(data){
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('ul#staff-list li').length;
					if(noLi == 0){
						$('ul#staff-list').append('<li class="no-staff"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Staffs found.</span></li>');
					}
				},'json');
				return false;
			});
		}
	<?php elseif($use_js == 'branchesMenuJs'): ?>
		$('.add-item').rPopForm({
			asJson	  : true,
			onComplete: function(data){
				if(data.found == 0){
					// deleleteStaff(data.id);
					if(data.act == 'add'){
						$('#branch-menu-item-list').append(data.div);
						edit_item(data.id);
						remove_item(data.id);
					}
					else{
						$('#branch-item-price-'+data.id).number(data.price,2);
					}
					rMsg(data.msg,'success');
				}
				else{
					rMsg(data.msg,'error');
				}
				bootbox.hideAll();
			}
		});
		$('.del-menu-item').each(function(){
			var id = $(this).attr('ref');
			remove_item(id);
		});
		function remove_item(id){
			$('#del-menu-item-'+id).click(function(){
				var link = $(this);
				$.post(baseUrl+'branches/branch_menu_item_db','remove_item='+id,function(data){
					link.parent().parent().parent().parent().remove();
					rMsg(data.msg,'success');
				},'json');
				return false;
			});
		}
		function edit_item(id){
			$('#add-item-'+id).rPopForm({
				asJson	  : true,
				onComplete: function(data){
					if(data.found == 0){
						$('#branch-item-price-'+data.id).number(data.price,2);
						rMsg(data.msg,'success');
					}
					bootbox.hideAll();
				}
			});
		}

		$('.add-combo').rPopForm({
			asJson	  : true,
			onComplete: function(data){
				if(data.found == 0){
					// deleleteStaff(data.id);
					if(data.act == 'add'){
						$('#branch-menu-combo-list').append(data.div);
						edit_combo(data.id);
						remove_combo(data.id);
					}
					else{
						$('#branch-combo-price-'+data.id).number(data.price,2);
					}
					rMsg(data.msg,'success');
				}
				else{
					rMsg(data.msg,'error');
				}
				bootbox.hideAll();
			}
		});
		$('.del-menu-combo').each(function(){
			var id = $(this).attr('ref');
			remove_combo(id);
		});
		function remove_combo(id){
			$('#del-menu-combo-'+id).click(function(){
				var link = $(this);
				$.post(baseUrl+'branches/branch_menu_combo_db','remove_combo='+id,function(data){
					link.parent().parent().parent().parent().remove();
					rMsg(data.msg,'success');
				},'json');
				return false;
			});
		}
		function edit_combo(id){
			$('#add-combo-'+id).rPopForm({
				asJson	  : true,
				onComplete: function(data){
					if(data.found == 0){
						$('#branch-combo-price-'+data.id).number(data.price,2);
						rMsg(data.msg,'success');
					}
					bootbox.hideAll();
				}
			});
		}
	<?php elseif($use_js == 'branchesTableJs'): ?>
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
			var branch_id = $('#branch_id').val();
			$.post(baseUrl+'branches/get_tables/'+branch_id,function(data){
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
			var branch_id = $('#branch_id').val();
			bootbox.dialog({
			  message: baseUrl+'branches/tables_form/'+branch_id+'/'+tbl_id,
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
			var branch_id = $('#branch_id').val();
			bootbox.dialog({
			  message: baseUrl+'branches/tables_form/'+branch_id+'/'+tbl_id,
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
	<?php elseif($use_js == 'branchesUploadImageTableJs'): ?>
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