<script>
$(document).ready(function(){
	<?php if($use_js == 'giftCardFormContainerJs'): ?>
		$('.tab_link').click(function(event)
		{
			event.preventDefault();
			var id = $(this).attr('id');
			loader('#'+id);
		});
		loader('#details_link');
		function loader(btn)
		{
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var selected = $('#gc_idx').val();
			if (selected == '') {
				selected = 'add';
				disableTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			} else {
				disableTabs('.load-tab',true);
			}
			var item_id = $('#gc_idx').val();
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+item_id});
		}
		function disableTabs(id,enable)
		{
			if (enable) {
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			} else {
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}
	<?php elseif($use_js == 'giftCardDetailsJs'): ?>
		$('#save-btn').click(function(){
			$("#gift_cards_details_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										rMsg(data.msg,'success');
									}
			});
			
			setTimeout(function(){
				window.location.reload();
			},1500);
			
			return false;
		});
		
		$('#card_no, #amount')
		.keyboard({
			alwaysOpen: false,
			usePreview: false,
			autoAccept : true
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
		
	<?php elseif($use_js == 'giftCardsJs'): ?>
		$('#new-gift-card-btn').click(function(){
			// alert('New Customer Button');
			window.location = baseUrl+'gift_cards/cashier_gift_cards';
			return false;
		});
		
		$('#look-up-btn').click(function(){
			// alert('Look up button');
			
			var this_url =  baseUrl+'gift_cards/gift_cards_list';
			$.post(this_url, {},function(data){
				$('.gc_content_div').html(data);
				// $('#cardno').attr({'value' : ''}).val('');
				
				$('.edit-line').click(function(){
					var line_id = $(this).attr('ref');
					var cardno = $(this).attr('cardnoref');
					var thisurl = baseUrl+'gift_cards/load_gift_cards_details';
					// alert('edit to : '+line_id);
					
					$.post(thisurl, {'cardno' : cardno}, function(data1){
						$('.gc_content_div').html(data1);
					});
					
					return false;
				});
				
			});
			return false;
		});
		
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		
		$('#pin')
		.keyboard({
			alwaysOpen: false,
			usePreview: false,
			autoAccept : true
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
		
		$('#cardno').focus(function(){
			$('#cardno').attr({'value' : ''}).val('');
			return false;
		});
		
		$('#cardno-login').click(function(){
			var cardno = $('#cardno').val();
			var this_url =  baseUrl+'gift_cards/validate_card_number';
			// alert('asdfg---'+cardno);
			
			$.post(this_url, {'cardno' : cardno}, function(data){
				// alert(data);
				var parts = data.split('||');
				// alert('eto:'+parts[1]);
				if(parts[0] == 'empty'){
					rMsg('Text field is empty!','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'none'){
					rMsg('Gift Card does not exist.','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'success'){
					rMsg('Loading Gift Card Details...','success');
					
					setTimeout(function(){
						var this_url2 =  baseUrl+'gift_cards/load_gift_cards_details';
						$.post(this_url2, {'cardno' : parts[1]},function(data){
							$('.gc_content_div').html(data);
							// $('#cardno').attr({'value' : ''}).val('');
							return false;
						});
					},1500);
						
				}
			});
			
			return false;
		});
		
		function loadGiftCardDetails(){
			var this_url =  baseUrl+'gift_cards/gift_cards_load';
			
			$.post(this_url, {},function(data){
				$('.gc_content_div').html(data);
				$('#cardno').focus();
				return false;
			});
		}
		
		loadGiftCardDetails();
		
	<?php endif; ?>
});
</script>