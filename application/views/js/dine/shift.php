<script>
$(document).ready(function(){
   <?php if($use_js == 'shiftJs'): ?>
        $('.loads-div').rLoad({url:'shift/time'});
        $('#time-btn').click(function(){
            $('.loads-div').rLoad({url:'shift/time'});
            return false;
        }); 
        $('#cashier-btn').click(function(){
            window.location = baseUrl+'cashier';
            return false;
        });
        $('#lock-btn').click(function(){
            window.location = baseUrl+'site/go_logout';
            return false;
        });	
        
   <?php elseif($use_js == 'timeJs'): ?>
        $('#start-shift-btn').click(function(){
            $('.loads-div').rLoad({url:'shift/start_amount'});
            return false;
        });
        $('#end-shift-btn').click(function(){
            $.callManager({
                success : function(){
                    window.location = baseUrl+'manager';
                }
            });
            return false;
        }); 
        function startTime(){
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                var day_or_night = (h > 11) ? "PM" : "AM";
                if (h > 12)
                    h -= 12;
                $('#timer').html(h + ":" + m + ":" + s + " " + day_or_night);
                setTimeout(function() {
                    startTime();
                }, 500);
        }
        function checkTime(i){
            if (i < 10)
            {
                i = "0" + i;
            }
            return i;
        }
        startTime();
   <?php elseif($use_js == 'startAmountJs'): ?>
        $('#enter-amount-btn').click(function(){
            var amt = $('#amount-input').val();
            if($.isNumeric(amt) && amt > 0){
                $.post(baseUrl+'shift/timeIn','amount='+amt,function(data){
                    if(data.error == ""){
                        $('.loads-div').rLoad({url:'shift/time'});
                        rMsg('Shift Started.','success');                        
                    }
                },'json');
            }
            else{
                rMsg('Input a valid amount','error');
            }
            return false;
        }); 
        $('#cancel-amount-btn').click(function(){
            $('.loads-div').rLoad({url:'shift/time'});
            return false;
        });
   <?php endif; ?>
});
</script>