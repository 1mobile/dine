<script>
$(document).ready(function(){
	<?php if($use_js == 'loadJs'): ?>
        $(".center").center();
        $(document).resize(function() {
            $(".center").center();
        });	    
        $('#loadBar').rLoadBar({'statTxt':$('#loadTxt')});
        $.post(baseUrl+'site/go_load',function(data){
	        $('#loadBar').rLoadBarEnd({
	        	onComplete	: function(){
	        					if(data.error != ""){
		        					window.location = baseUrl+'site/login/'+data.error;	        						
	        					}
	        					else{
		        					window.location = baseUrl;	        						
	        					}
	        				  }
	        });
		},'json');
	        // alert(data);
		// });

	<?php endif; ?>    
});
</script>