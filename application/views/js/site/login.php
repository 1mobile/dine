<script>
$(document).ready(function(){
	<?php if($use_js == 'loginJs'): ?>
		if($('#shift_end').exists()){
			rMsg('Last Shift has ended.','success');
		}
		if($('.rot-login-by').exists()){
			var rot = $('.rot-login-by').first();
			$('.logins').hide();
			$('#pin-user').hide();
			$('#pin-id').val('');
			$(rot.attr('act')).show();
			if(rot.attr('act') == '#loginPin'){
				if(rot.attr('name') !== undefined){
					$('#pin-user').text(rot.attr('name'));
					$('#pin-user').show();
					$('#pin-id').val(rot.attr('user'));
				}
				else{
					$('#pin-user').text("");
					$('#pin-user').hide();
					$('#pin-id').val('');			
				}
			}
		}

		$('.login-by').click(function(){
			$('.logins').hide();
			$('#pin-user').hide();
			$('#pin-id').val('');
			$($(this).attr('act')).show();
			if($(this).attr('act') == '#loginPin'){
				if($(this).attr('name') !== undefined){
					$('#pin-user').text($(this).attr('name'));
					$('#pin-user').show();
					$('#pin-id').val($(this).attr('user'));
				}
				else{
					$('#pin-user').text("");
					$('#pin-user').hide();
					$('#pin-id').val('');			
				}
			}
			return false;
		});

		$('#training').click(function(){
			// alert(baseUrl);
			window.location = 'http://localhost/dineTrain';
			return false;
		});
		$('#uname-login').click(function(){
			$("#uname-login-form").rOkay({
				btn_load		: 	$('#uname-login'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(data.error_msg != null){
											rMsg(data.error_msg,'error');
										}
										else{
											window.location = baseUrl+'cashier';
										}
									}
			});
			return false;
		});
		$('#pin-login').click(function(){
			var pin = $('#pin').val();
			var pin_id = $('#pin-id').val();
			$.post(baseUrl+'site/go_login','pin='+pin+'&pin_id='+pin_id,function(data){
				if(data.error_msg != null){
					rMsg(data.error_msg,'error');
					$('#pin').focus();
				}
				else{
					window.location = data.redirect_address;
				}
			// },'json');
			},'json');
			return false;
		});
		// $('#login-btn').click(function(){
		// 	$("#login-form").rOkay({
		// 		btn_load		: 	$('#login-btn'),
		// 		bnt_load_remove	: 	true,
		// 		asJson			: 	true,
		// 		onComplete		:	function(data){
		// 								// alert(data);
		// 								if(data.error_msg != null){
		// 									rMsg(data.error_msg,'error');
		// 								}
		// 								else{
		// 									window.location = baseUrl;
		// 								}
		// 							}
		// 	});
		// 	return false;
		// });
	<?php elseif($use_js == 'autoZreadJs'): ?>
		$.post(baseUrl+'reads/auto_zread',function(data){
			// alert(data);
			$('.ztxt').html(data);
			setTimeout(function() {
			  window.location.href = baseUrl+"site/login";
			}, 2000);
		});
	<?php endif; ?>
});
</script>