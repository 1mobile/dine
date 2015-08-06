<script>
$(document).ready(function(){
	<?php if($use_js == 'clockJs'): ?>
		//Start of functions:
		//timeticke
		function startTime(){
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();

                // add a zero in front of numbers<10
                m = checkTime(m);
                s = checkTime(s);

                //Check for PM and AM
                var day_or_night = (h > 11) ? "PM" : "AM";

                //Convert to 12 hours system
                if (h > 12)
                    h -= 12;


                //Add time to the headline and update every 500 milliseconds
                $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
                //alert($('#time').text());
                //cur_time = $('#time').text();

                setTimeout(function() {
                    //startTime(brk_out,chk_out);
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
		//End of functions

		startTime();

		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});

        loadClock('status');

        function loadClock(btype){
            //$('#hidden_stat').val('in');
            $('.clock-center').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
            $.post(baseUrl+'clock/transaction/'+btype,function(data){
               
                $('.clock-center').html(data.code);
                $('#clockin').click(function(){
                    cur_time = $('#time').text();
                    //alert(cur_time);
                    var formData = 'cur_time='+cur_time;
                    $.post(baseUrl+'clock/saveClockin',formData,function(data){
                        // alert(data);
                        if(data.error != null){
                            rMsg(data.error,'error');
                            $.callManager({
                                title   : "Manager Shift Override",
                                success : function(){
                                    $('#drawer-btn').trigger('click');
                                }
                            });
                        }else{
                            //rMsg('Clock in successful.','success');
                            //startTime();
                            // if(data.count_in == 0){
                                //loadClock('status');
                                $('#drawer-btn').trigger('click');
                            // }else{
                                // loadClock('status');
                            // }
                        }

                    },'json');
                    // });
                });
                $('#clockout').click(function(){
                    //alert('out');
                    cur_time = $('#time').text();
                    $('#hidden_stat').val('out');
                    $('#drawer-btn').trigger('click');
                });

            },'json');
            // alert(data);
            // });
        }

        $('#status-btn').click(function(){
            ref = $(this).attr('ref');

            loadClock(ref);
        });

        $('#sched-btn').click(function(){
            //$('#hidden_stat').val('in');

            hdate = $('#hidden_date').val();
            act = 'none';
            sched(hdate,act);

        });

        function sched(hdate,act){

            $('.clock-center').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
            // hdate = $('#hidden_date').val();
            // act = 'none';
            var formData = 'hdate='+hdate+'&act='+act;
            $.post(baseUrl+'clock/schedule',formData,function(data){
                //alert(data);
                $('.clock-center').html(data.code);
                //alert('aw');
                $('#prev-sched').click(function(){
                    hdate = $('#hidden_date').val();
                    act = 'prev';
                    sched(hdate,act);
                });

                $('#next-sched').click(function(){
                    hdate = $('#hidden_date').val();
                    act = 'next';
                    sched(hdate,act);
                });

                $('#hidden_date').val(data.hdate);

            },'json');
        }

        $('#drawer-btn').click(function(){

            hstat = $('#hidden_stat').val();


            if(hstat == 'first_in'){
                //alert(hstat);
                var btn = $(this);
                //btn.prop('disabled', true);
                $('.clock-center').rLoad({url:baseUrl+'clock/cashdrawer/'+hstat});
                //btn.prop('disabled', false);
                //$('#hidden_stat').val('in');
            }else if(hstat == 'in'){
                window.location = baseUrl+'manager#cash-drawer';
            }else{
                $.post(baseUrl + 'clock/check_shift_xread',{}, function(data)
                {
                    
                    if (typeof data.error == "undefined") {
                        
                        $.post(baseUrl + 'clock/clockOut',{}, function(data1){

                           if(data1.error != null){
                                rMsg(data1.error,'error');
                            }else{
                                $('#logout-btn').trigger('click');
                            }

                        },'json');


                    }else{
                        rMsg(data.error,"error");
                    }

                },'json');
                //     alert(data);
                // });

                 // window.location = baseUrl+'manager#cash-drawer';
                // alert(hstat);
                // var btn = $(this);
                // //btn.prop('disabled', true);
            }
            return false;
        });

        $('#timecard-btn').click(function(){
            //$('#hidden_stat').val('in');
            var btn = $(this);
            btn.prop('disabled', true);
            $('.clock-center').rLoad({url:baseUrl+'clock/timecard'});
            btn.prop('disabled', false);
            return false;

        });

        $('#logout-btn').click(function(){
            window.location = baseUrl+'site/go_logout';
            return false;
        });

    <?php elseif($use_js == 'drawerJS'): ?>
        $('#drawer-enter-btn').click(function(){

            fstat = $('#fstat').val();
            cur_time = $('#time').text();
            cashin = $('#drawer-input').val();
            //alert(cashin);
            if(cashin == ""){
                rMsg('Please input an amount.','error');
            }else{
                var formData = 'cashin='+cashin+'&cur_time='+cur_time;
                //alert(fstat);
                if(fstat == 'first_in'){
                    $.post(baseUrl+'clock/saveCashin',formData,function(data){
                        //alert(data);
                        if(data.error != null){
                            rMsg(data.error,'error');
                        }else{
                            // rMsg('Clock IN Successfully with Cash In amount P'+cashin+' successful.','success');
                            //$('#drawer-input').val('');
                            // $('#hidden_stat').val('in');
                            // $('#drawer-btn').removeAttr('disabled');
                            // $('#status-btn').trigger('click');
                            window.location = baseUrl+'clock';
                        }
                    // });
                    },'json');
                }else{
                    // alert('aw');
                    // $.post(baseUrl+'clock/saveCashout',formData,function(data){
                    //     // alert(data);
                    //     if(data.error != null){
                    //         rMsg(data.error,'error');
                    //     }else{
                    //         rMsg('Clock Out successful.','success');
                    //         $('#drawer-input').val('');
                    //         $('#status-btn').trigger('click');
                    //     }
                    // // });
                    // },'json');
                }
            }
        })

    <?php elseif($use_js == 'timecardJS'): ?>
        //alert('aw');
        $('#date-to').click(function(){
            $(this).val('');
        });
        $('#date-from').click(function(){
            $(this).val('');
        });

        $('#search-dtr').click(function(){
            to = $('#date-to').val();
            from = $('#date-from').val();

            if(to == "" || from == ""){
                rMsg('Complete the form.','error');
            }else{
                formData = 'from='+from+'&to='+to;
                // alert()
                $.post(baseUrl+'clock/search_dtr',formData,function(data){
                            //alert(data.code);
                            $('.dtr-details').html(data.code);

                        // });
                },'json');
            }
        });

        $('#date-to,#date-from')
        .keyboard({
            alwaysOpen: false,
            usePreview: false
        })
        .addNavigation({
            position   : [0,0],
            toggleMode : false,
            focusClass : 'hasFocus'
        });

	<?php endif; ?>
});
</script>