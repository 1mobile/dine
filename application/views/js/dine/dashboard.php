<script>
$(document).ready(function(){
	<?php if($use_js == 'dashBoardJs'): ?>
	startTime();
	load_trans_chart();
	function load_trans_chart(){
		// $('#bar-chart').goLoad();
		$('#bars-div').goLoad();
		$.post(baseUrl+'dashboard/summary_orders',function(data){
			// alert(data.orders);
			// var orders = new Array();
			var shift_sales = new Array();
			$.each(data.shift_sales,function(key,val){
				shift_sales.push(val);
			});
			// var bar = new Morris.Bar({
	  //           element: 'bar-chart',
	  //           resize: true,
	  //           data: orders,
	  //           barColors: ["#428BCA", "#00A65A","#F39C12", "#F56954"],
	  //           xkey: 'label',
	  //           ykeys: ['open','settled','cancel','void'],
	  //           labels: ['open','settled','cancel','void'],
	  //           hideHover: 'auto'
	  // 		});
			// $('#bar-chart').goLoad({load:false});
			//DONUT CHART
		    var donut = new Morris.Donut({
		        element: 'sales-chart',
		        resize: true,
		        data:shift_sales,
		        hideHover: 'auto'
		    });
		    console.log(shift_sales);
			$('#bars-div').html(data.code);
		// });
		},'json');
	}

	function startTime(){
	    var today = new Date();
	    var h = today.getHours();
	    var m = today.getMinutes();
	    var s = today.getSeconds();
	    var weekday = new Array(7);
	        weekday[0]=  "Sunday";
	        weekday[1] = "Monday";
	        weekday[2] = "Tuesday";
	        weekday[3] = "Wednesday";
	        weekday[4] = "Thursday";
	        weekday[5] = "Friday";
	        weekday[6] = "Saturday";
	    var d = weekday[today.getDay()];

	    var today = moment();
	    var to = today.format('MMMM  D, YYYY');
	    // add a zero in front of numbers<10
	    m = checkTime(m);
	    s = checkTime(s);

	    //Check for PM and AM
	    var day_or_night = (h > 11) ? "PM" : "AM";

	    //Convert to 12 hours system
	    if (h > 12)
	        h -= 12;

	    //Add time to the headline and update every 500 milliseconds
	    $('#box-time').html(h + ":" + m + ":" + s + " " + day_or_night);
	    $('#box-day').html(d);
	    $('#box-date').html(to);
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

	<?php endif; ?>
});
</script>