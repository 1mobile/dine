<script>
$(document).ready(function(){
	<?php if($use_js == 'customerFormContainerJs'): ?>
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
			var selected = $('#cust_idx').val();
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
			var item_id = $('#cust_idx').val();
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
	<?php elseif($use_js == 'customerDetailsJs'): ?>
		$('#save-btn').click(function(){
			$("#customer_details_form").rOkay({
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
		
		$('#fname, #mname, #lname, #suffix, #phone, #email, #street_no, #street_address, #city, #region, #zip')
		.keyboard({
			alwaysOpen: false,
			usePreview: false
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
	<?php elseif($use_js == 'customersJs'): ?>
		$('#new-customer-btn').click(function(){
			// alert('New Customer Button');
			window.location = baseUrl+'customers/cashier_customers';
			return false;
		});
		
		$('#look-up-btn').click(function(){
			// alert('Look up');
			var this_url =  baseUrl+'customers/customers_list';
			$.post(this_url, {},function(data){
				$('.customer_content_div').html(data);
				// $('#telno').attr({'value' : ''}).val('');
				
				$('.edit-line').click(function(){
					var line_id = $(this).attr('ref');
					var phone = $(this).attr('phoneref');
					var thisurl = baseUrl+'customers/load_customer_details';
					// alert('edit to : '+line_id);
					
					$.post(thisurl, {'telno' : phone}, function(data1){
						$('.customer_content_div').html(data1);
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
			usePreview: false
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
		
		$('#telno').focus(function(){
			$('#telno').attr({'value' : ''}).val('');
			return false;
		});
		
		$('#telno-login').click(function(){
			var telno = $('#telno').val();
			var this_url =  baseUrl+'customers/validate_phone_number';
			// alert('asdfg---'+telno);
			
			$.post(this_url, {'telno' : telno}, function(data){
				// alert(data);
				var parts = data.split('||');
				// alert('eto:'+parts[1]);
				if(parts[0] == 'empty'){
					rMsg('Text field is empty!','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'none'){
					rMsg('Customer does not exist.','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'success'){
					rMsg('Loading customer details...','success');
					
					setTimeout(function(){
						var this_url2 =  baseUrl+'customers/load_customer_details';
						$.post(this_url2, {'telno' : parts[1]},function(data){
							$('.customer_content_div').html(data);
							// $('#telno').attr({'value' : ''}).val('');
							return false;
						});
					},1500);
						
				}
			});
			
			return false;
		});
		
		function loadCustomerDetails(){
			var this_url =  baseUrl+'customers/customer_load';
			
			$.post(this_url, {},function(data){
				$('.customer_content_div').html(data);
				$('#telno').focus();
				return false;
			});
		}
		
		loadCustomerDetails();
		
		
	<?php endif; ?>
});
</script>