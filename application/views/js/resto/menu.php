<script>
$(document).ready(function(){
	<?php if($use_js == 'menuJs'): ?>
		loadItems();
		$('#sub-category-drop').parent().parent().hide();
		$('#add-new-category').rPopFormFile({
			asJson	  : true,
			onComplete: function(data){
				if(data.act == 'add'){
					$('#category-drop').append($('<option>', {
										    value: data.id,
										    text: data.desc
										}).prop('selected', true));
					$('#add-new-category').attr('rata-title',data.desc);
					var url = 'menu/category_form/';
					var res_id = $('#res_id').val();
					$('#add-new-category').attr('href',url+res_id+'/'+data.id);
				}
				else{
					$('#category-drop option[value="'+data.id+'"]').text(data.desc);
					$('#add-new-category').attr('rata-title',data.desc);
				}
				loadItems();
				rMsg(data.msg,'success');
				hideOptions();
				$('#sub-category-drop').parent().parent().show();
				var suburl = 'menu/sub_category_form/';
				var res_id = $('#res_id').val();
				$('#add-new-sub-category').attr('href',suburl+res_id+'/'+data.id);
				bootbox.hideAll();
			}
		});
		$('#add-new-sub-category').rPopFormFile({
			asJson	  : true,
			onComplete: function(data){
				if(data.act == 'add'){
					$('#sub-category-drop').append($('<option>', {
										    value: data.id,
										    'category': data.cat_id,
										    text: data.desc
										}).prop('selected', true));
					var url = 'menu/sub_category_form/';
					var res_id = $('#res_id').val();
					$('#add-new-sub-category').attr('rata-title',data.desc);
					$('#add-new-sub-category').attr('href',url+res_id+'/'+data.cat_id+'/'+data.id);
				}
				else{
					$('#sub-category-drop option[value="'+data.id+'"]').text(data.desc);
					$('#add-new-sub-category').attr('rata-title',data.desc);
				}
				loadItems();
				rMsg(data.msg,'success');
				bootbox.hideAll();
			}
		});
		$('#category-drop').change(function(){
			var res_id = $('#res_id').val();
			var cat_id = $(this).val();
			var url = 'menu/category_form/';
			var suburl = 'menu/sub_category_form/';
			if(cat_id != ""){
				var title = $(this).find(':selected').text();
				hideOptions();
				$('#sub-category-drop').parent().parent().show();
			}
			else{
				var title = "Add New Category";
				showAllOptions();
				$('#sub-category-drop').parent().parent().hide();
			}
			$('#add-new-category').attr('rata-title',title);
			$('#add-new-category').attr('href',url+res_id+'/'+cat_id);
			$('#add-new-sub-category').attr('href',suburl+res_id+'/'+cat_id);
			loadItems();
		});
		$('#sub-category-drop').change(function(){
			var res_id = $('#res_id').val();
			var cat_id = $('#category-drop').val();
			var sub_cat_id = $(this).val();
			var url = 'menu/sub_category_form/';
			if(sub_cat_id != ""){
				var title = $(this).find(':selected').text();
			}
			else{
				var title = "Add New Category";
			}
			$('#add-new-sub-category').attr('rata-title',title);
			$('#add-new-sub-category').attr('href',url+res_id+'/'+cat_id+'/'+sub_cat_id);
			loadItems();
		});
		$('#combos_link').click(function(){
			loadCombos();
		});
		function hideOptions(){
			var cat_id = $('#category-drop').val();
			var opts = $('#sub-category-drop').find('option');
			opts.each(function(){
				if(typeof $(this).attr('category') !== typeof undefined && $(this).attr('category') !== false){
					if(cat_id != $(this).attr('category')){
						$(this).hide();
					}
					else{
						$(this).show();
					}
				}
			});
			$('#sub-category-drop  option:first-child').attr("selected", "selected");;
		}
		function showAllOptions(){
			var opts = $('#sub-category-drop').find('option');
			opts.each(function(){
				$(this).show();
			});
			$('#sub-category-drop  option:first-child').attr("selected", "selected");;
		}
		function loadItems(){
			var cat_id = $('#category-drop').val();
			var sub_cat_id = $('#sub-category-drop').val();
			var res_id = $('#res_id').val();
			$('#item-list-div').rLoad({url:baseUrl+'menu/item_list/'+res_id+'/'+cat_id+'/'+sub_cat_id});
		}
		function loadCombos(){
			var res_id = $('#res_id').val();
			$('#combos-list-div').rLoad({url:baseUrl+'menu/combo_list/'+res_id});
		}
	<?php elseif($use_js == 'menuCategoryJs'): ?>
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
	    $('#target').click(function(e){
	    	$('#fileUpload').trigger('click');

	    }).css('cursor', 'pointer');
	<?php elseif($use_js == 'itemFormJs'): ?>
		$('#sub-category-drop').parent().parent().hide();

		var res_id = $('#res_id').val();
		var cat_id = $('#category-drop').val();
		var url = 'menu/category_form/';
		var suburl = 'menu/sub_category_form/';
		if(cat_id != ""){
			var title = $('#category-drop').find(':selected').text();
			hideOptions();
			$('#sub-category-drop').parent().parent().show();
		}
		else{
			var title = "Add New Category";
			showAllOptions();
			$('#sub-category-drop').parent().parent().hide();
		}
		$('#add-new-category').attr('rata-title',title);
		$('#add-new-category').attr('href',url+res_id+'/'+cat_id);
		$('#add-new-sub-category').attr('href',suburl+res_id+'/'+cat_id);



		$('#add-new-category').rPopForm({
			asJson	  : true,
			onComplete: function(data){
				if(data.act == 'add'){
					$('#category-drop').append($('<option>', {
										    value: data.id,
										    text: data.desc
										}).prop('selected', true));
				}
				else{
					$('#category-drop option[value="'+data.id+'"]').text(data.desc);
					$('#add-new-category').attr('rata-title',data.desc);
				}
				rMsg(data.msg,'success');
				hideOptions();
				$('#sub-category-drop').parent().parent().show();
				var suburl = 'menu/sub_category_form/';
				var res_id = $('#res_id').val();
				$('#add-new-sub-category').attr('href',suburl+res_id+'/'+data.id);
				bootbox.hideAll();
			}
		});
		$('#add-new-sub-category').rPopFormFile({
			asJson	  : true,
			onComplete: function(data){
				if(data.act == 'add'){
					$('#sub-category-drop').append($('<option>', {
										    value: data.id,
										    'category': data.cat_id,
										    text: data.desc
										}).prop('selected', true));
				}
				else{
					$('#sub-category-drop option[value="'+data.id+'"]').text(data.desc);
					$('#add-new-sub-category').attr('rata-title',data.desc);
				}
				loadItems();
				rMsg(data.msg,'success');
				bootbox.hideAll();
			}
		});
		$('#category-drop').change(function(){
			var res_id = $('#res_id').val();
			var cat_id = $(this).val();
			var url = 'menu/category_form/';
			var suburl = 'menu/sub_category_form/';
			if(cat_id != ""){
				var title = $(this).find(':selected').text();
				hideOptions();
				$('#sub-category-drop').parent().parent().show();
			}
			else{
				var title = "Add New Category";
				showAllOptions();
				$('#sub-category-drop').parent().parent().hide();
			}
			$('#add-new-category').attr('rata-title',title);
			$('#add-new-category').attr('href',url+res_id+'/'+cat_id);
			$('#add-new-sub-category').attr('href',suburl+res_id+'/'+cat_id);
		});
		$('#sub-category-drop').change(function(){
			var res_id = $('#res_id').val();
			var cat_id = $('#category-drop').val();
			var sub_cat_id = $(this).val();
			var url = 'menu/sub_category_form/';
			if(sub_cat_id != ""){
				var title = $(this).find(':selected').text();
			}
			else{
				var title = "Add New Category";
			}
			$('#add-new-sub-category').attr('rata-title',title);
			$('#add-new-sub-category').attr('href',url+res_id+'/'+cat_id+'/'+sub_cat_id);
			loadItems();
		});
		$('#save-btn').click(function(){
			var noError = $('#items-form').rOkay({
    			asJson			: 	false,
				btn_load		: 	null,
				goSubmit		: 	false,
				bnt_load_remove	: 	true
    		});
    		if(noError){
    			$("#items-form").submit(function(e){
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
				         	if(typeof data.msg != 'undefined' ){
								rMsg(data.msg,'success');
							}
				        },
				        error: function(jqXHR, textStatus, errorThrown){
				        }         
				    });
				    e.preventDefault();
				    e.unbind();
				});
				$("#items-form").submit();
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
	    function hideOptions(){
			var cat_id = $('#category-drop').val();
			var opts = $('#sub-category-drop').find('option');
			opts.each(function(){
				if(typeof $(this).attr('category') !== typeof undefined && $(this).attr('category') !== false){
					if(cat_id != $(this).attr('category')){
						$(this).hide();
					}
					else{
						$(this).show();
					}
				}
			});
			$('#sub-category-drop  option:first-child').attr("selected", "selected");
			var hid_sub_cat_id = $('#hid_sub_cat_id').val();
			$('#sub-category-drop').val(hid_sub_cat_id);
		}
		function showAllOptions(){
			var opts = $('#sub-category-drop').find('option');
			opts.each(function(){
				$(this).show();
			});
			$('#sub-category-drop  option:first-child').attr("selected", "selected");;
		}
	<?php elseif($use_js == 'comboFormJs'): ?>
		$('#save-btn').click(function(){
			var noError = $('#combo-form').rOkay({
    			asJson			: 	false,
				btn_load		: 	null,
				goSubmit		: 	false,
				bnt_load_remove	: 	true
    		});

    		if(noError){
    			$("#combo-form").submit(function(e){
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
				         	if(typeof data.msg != 'undefined' ){
								rMsg(data.msg,'success');
								$('#combo-id-hid').val(data.id);
							}
				        },
				        error: function(jqXHR, textStatus, errorThrown){
				        }         
				    });
				    e.preventDefault();
				    
				});
				$("#combo-form").submit();
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
		$('#item-search').typeaheadmap({
			"source": function(search, process) {
				var url = $('#item-search').attr('search-url');
				var formData = 'search='+search;
				var addData = $('#item-search').attr('add-data');
				if (typeof addData !== typeof undefined && addData !== false) {
					formData += "&"+addData;
				}
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
		$('#pwh-drop').change(function(){
			price_change();
		});
		$('#add-btn').click(function(){
			var cid = $('#combo-id-hid').val();
			if(cid == ""){
				rMsg('You Need To save General Details first Before Adding in Item Details','error.');
			}
			else{

				$("#combo-details-form").rOkay({
					btn_load		: 	$('#add-btn'),
					asJson			: 	true,
					onComplete		:	function(data){
											if(data.act == 'add'){
												$('#details-tbl').append(data.row);
												rMsg(data.msg,'success');
											}
											else{
												var i = $('#row-'+data.id);
												$('#row-'+data.id).remove();
												$('#details-tbl').append(data.row);
												rMsg(data.msg,'success');
											}
											get_combo_total();
											remove_row(data.id);
										}
				});

			}
			return false;
		});
		$('.dels').each(function(){
			var id = $(this).attr('ref');
			remove_row(id);
		});
		$('#override-price').click(function(){
			var cid = $('#combo-id-hid').val();
			if(cid != ""){
				var total = $('#total').val();
				$.post(baseUrl+'menu/override_combo_total','combo_id='+cid+'&total='+total,function(data){
					rMsg('Selling Price Updated.','success');
				});
			}
			return false;
		});
		function get_combo_total(){
			var cid = $('#combo-id-hid').val();
			if(cid != ""){
				$.post(baseUrl+'menu/get_combo_total','combo_id='+cid,function(data){
					$('#total').val(data.total);
				},'json');
			}
		}
		function remove_row(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'menu/remove_detail_row','combo_det_id='+id,function(data){
					$('#row-'+id).remove();
					get_combo_total();
				},'json');
				return false;
			});
		}
		function get_item_details(v){
			$.post(baseUrl+'menu/get_item_details/'+v+'/'+$('#res_id').val(),function(data){
				$('#pwh-drop').attr('price',data.price);
				if(data.portion_price > 0){
					$('#pwh-drop').attr('portion_price',data.portion_price);
					$("#pwh-drop").prop("disabled", false);
				}
				else{
					$("#pwh-drop").val('whole');
					$("#pwh-drop").prop("disabled", true);
				}

				price_change();
			},'json');
		}
		function price_change(){
			var pwh = $('#pwh-drop').val();
			$('#type').val(pwh);
			if(pwh == "whole"){
				$('#item-price').number($('#pwh-drop').attr('price'),2);
				$('#item-price-hid').val($('#pwh-drop').attr('price'));
			}
			else{
				$('#item-price').number($('#pwh-drop').attr('portion_price'),2);
				$('#item-price-hid').val($('#pwh-drop').attr('portion_price'));
			}
		}
	<?php endif; ?>
});
</script>