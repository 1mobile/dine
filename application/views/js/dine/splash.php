<script>
$(document).ready(function(){
   <?php if($use_js == 'splashJs'): ?>
        $('#splashLoad').rLoad({url:baseUrl+'splash/commercial'});
        setInterval(function() {
            $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                if(data.ctr > 0){
                    window.location = baseUrl+'splash/transactions';
                }
            },'json');
        }, 300);
   <?php elseif($use_js == 'splashTransJs'): ?> 
        setInterval(function() {
            $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                if(data.ctr == 0){
                    window.location = baseUrl+'splash';
                }
            },'json');
            transTotal();
            get_trans();
        }, 300);
        function get_trans(){
            $.post(baseUrl+'splash/get_counter', function (data) {
                var head = data.counter;
                $('#trans-header').html(head.type);
                $('#trans-datetime').html(head.datetime);
                $('#transBody').html(data.code);
                $('#transBody').perfectScrollbar({suppressScrollX: true});
            },'json');
        }
        function transTotal(){
            $.post(baseUrl+'cashier/total_trans',function(data){
                var total = data.total;
                var discount = data.discount;
                $("#total-txt").number(total,2);
                $("#discount-txt").number(discount,2);
            },'json');
        }
   <?php endif; ?>
});
</script>