<script>
$(document).ready(function(){
	<?php if($use_js == 'modFormJs'): ?>
		loader('#details_link');
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var mod_id = $('#mod_id').val();

			if(mod_id == ""){
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			$(tabPane).rLoad({url:baseUrl+loadUrl+mod_id});
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
	<?php elseif($use_js == 'detailsLoadJs'): ?>
	//alert('zxczxc');
		$('#save-mod').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-mod'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(typeof data.msg != 'undefined' ){
											$('#mod_id').val(data.id);
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											disEnbleTabs('.load-tab',true);
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
	<?php elseif($use_js == 'recipeLoadJs'): ?>
		$('#add-item-btn').click(function(){
			$("#recipe_form").rOkay({
				btn_load		: 	$('#add-item-btn'),
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
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
										get_recipe_total();
										remove_row(data.id);
									}
			});
			return false;
		});
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
		$('.dels').each(function(){
			var id = $(this).attr('ref');
			remove_row(id);
		});
		$('#override-price').click(function(){
			var cid = $('#mod_id').val();
			if(cid != ""){
				var total = $('#total').val();
				$.post(baseUrl+'mods/update_modifier_price','mod_id='+cid+'&total='+total,function(data){
					rMsg('Selling Price Updated.','success');
				});
			}
			return false;
		});
		function get_item_details(v){
			$.post(baseUrl+'mods/get_item_details/'+v,function(data){
				$('#item-cost').val(data.cost);
				$('#item-uom-hid').val(data.uom);
				$('#uom-txt').text(data.uom);
				$('#qty').focus();
			},'json');
		}
		function remove_row(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'mods/remove_recipe_item','mod_recipe_id='+id,function(data){
					$('#row-'+id).remove();
					rMsg(data.msg,'warning');
					get_recipe_total();
				},'json');
				return false;
			});
		}
		function get_recipe_total(){
			var cid = $('#mod_id').val();
			if(cid != ""){
				$.post(baseUrl+'mods/get_recipe_total','mod_id='+cid,function(data){
					// alert(data);
					$('#total').val(data.total);
				// });
				},'json');
			}
		}
	<?php elseif($use_js == 'modGroupFormJs'): ?>
		loader('#details_link');
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var mod_group_id = $('#mod_group_id').val();

			if(mod_group_id == ""){
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			$(tabPane).rLoad({url:baseUrl+loadUrl+mod_group_id});
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
	<?php elseif($use_js == 'groupDetailsLoadJs'): ?>
		$('#save-grp').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-grp'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(typeof data.msg != 'undefined' ){
											$('#mod_group_id').val(data.id);
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											disEnbleTabs('.load-tab',true);
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
	<?php elseif($use_js == 'groupRecipeLoadJs'): ?>
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
				$('#item-search').val('');
				add_to_group(v,k);
			}
		});
		$('.del').each(function(){
			var id = $(this).attr('ref');
			remove_row(id);
		});
		$('#item-search').keyup(function(e){
			if(e.keyCode == '13'){
				$(this).val("");
			}
		});
		function add_to_group(id,text){
			var mod_id = id;
			var mod_group_id = $('#mod_group_id').val();
			var mod_text = text;
			$.post(baseUrl+'mods/group_modifiers_details_db','mod_group_id='+mod_group_id+'&mod_id='+mod_id+'&mod_text='+text,function(data){
				if(data.act == 'add'){
					$('#modifier-list').append(data.li);
					rMsg(data.msg,'success');
				}
				else{
					var i = $('#li-'+data.id);
					$('#li-'+data.id).remove();
					$('#modifier-list').append(data.li);
					rMsg(data.msg,'success');
				}
				remove_row(data.id);
			},'json');
		}
		function remove_row(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'mods/remove_group_modifier','group_mod_id='+id,function(data){
					$('#li-'+id).remove();
					rMsg(data.msg,'warning');
				},'json');
				return false;
			});
		}
	<?php endif; ?>
});
</script>