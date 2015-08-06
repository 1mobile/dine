<script>
$(document).ready(function(){
	<?php if($use_js == 'receiveJs'): ?>
		$('#save-btn').click(function(){
			$("#receive_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(data.error != null){
											rMsg(data.msg,'success');
										}else{
											window.location.reload();
										}
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
				set_item_details(v);
			}
		});
		function set_item_details(id){
			$.post(baseUrl+'receiving/get_item_details/'+id,function(data){
				$('#item-id').val(data.item_id);
				$('#item-uom').val(data.uom);
				$('#select-uom').find('option').remove();
				$.each(data.opts,function(key,val){
					$('#select-uom').append($("<option/>", {
				        value: val,
				        text: key
				    }));
				});
				$('#item-ppack').val(data.ppack);
				$('#item-pcase').val(data.pcase);
			},'json');
		}
		$('#add-item-btn').click(function(){
			var noError = $('#add_item_form').rOkay({
				btn_load	: 	$('#add-item-btn'),
				goSubmit	: 	false,
				bnt_load_remove	: 	true
			});
			if(noError){
				var formData = $("#add_item_form").serialize();
				$.post(baseUrl+'wagon/add_to_wagon/rec_cart',formData,function(data){
					var row = data.items;
					var id = data.id;
					var tr = $("<tr/>").attr('id','row-'+id);
					$.each(row,function(key,val){
						if(key == 'item-search' || key == 'qty' || key == 'select-uom' || key == 'cost'){
							if(key == 'select-uom'){
								var txt = splitUom(val);
								tr.append($("<td/>", {text: txt}));
							}
							else
								tr.append($("<td/>", {text: val}));
						}
					});
					var link = $('<a/>')
						.attr('id','del-'+id)
						.attr('class','del')
						.attr('href','#')
						.html('<i class="fa fa-trash-o fa-lg fa-fw"></i>');
					tr.append($("<td/>",{html:link}));
				    $('#details-tbl').append(tr);
				    $('#add_item_form').find("input[type=text], textarea").val("");
				    $('#select-uom').find('option').remove();
				    deleteRow(id);
				},'json');
			}
			return false;
		});
		function deleteRow(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'wagon/delete_to_wagon/rec_cart/'+id,function(data){
					$('#row-'+id).remove();
				},'json');
				return false;
			});
		}
		function splitUom(txt){
			var txt = txt.split('-');
			var line = "";
			if(1 in txt){
				if(txt[1] == 'pack')
					line = 'Pack(@'+txt[2]+' '+txt[0]+')';
				else
					line = 'Case(@'+txt[2]+' Packs)';
			}
			else
				line = txt[0];
			return line;
		}
	<?php endif; ?>
});
</script>