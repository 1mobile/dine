function baseUrl() {
	var href = window.location.href.split('/');
	return href[0]+'//'+href[2]+'/'+href[3]+'/';
}
var baseUrl = baseUrl();
function isArray(object){
    return object.constructor === Array;
}
function formatNumber(number,dec){

	if(typeof(dec) == 'undefined')
		dec = 2;
    number = number.toFixed(dec) + '';
    x = number.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function rMsg(text,type){
	var n = noty({
	       text        : text,
	       type        : type,
	       dismissQueue: true,
	       layout      : 'topRight',
	       theme       : 'defaultTheme',
	       animation	: {
						open: {height: 'toggle'},
						close: {height: 'toggle'},
						easing: 'swing',
						speed: 500 // opening & closing animation speed
					}
	   }).setTimeout(3000);
}
function site_alerts(){
    $.post(baseUrl+'site/site_alerts',function(data){
        var alerts = data.alerts;
        $.each(alerts, function(index,row){
            rMsg(row['text'],row['type']);
        });
    },"json").promise().done(function() {
        $.post(baseUrl+'site/clear_site_alerts');
    });
}
$(document).ready(function(){
    site_alerts();
	$('.data-table').dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
    });
    $('.no-decimal').number(true,0);
    $('.numbers-only').number(true,2);
    $("[data-mask]").inputmask();
    // $('.cashier-wrapper').disableSelection();
    $('.pick-date').datetimepicker({
        pickTime: false
    });

    var problem = $('body').attr('problem');
    if (typeof problem !== typeof undefined && problem !== false) {
        $('#nav-problem-txt').css({
            'float':'left'
        }).html('<h4 style="color:#555;padding:8px;padding-top:5px;background-color:#FCF8E3;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;"> <i class="fa fa-warning"></i> Warning! There are unclosed shifts. Close it first before you can start a trasaction.</h3>');
        if($('.new-order-btns').exists()){
            $('.new-order-btns').attr('disabled','disabled');
        }
    }


});
$(function() {
    function clearTableActivity() {
        $.post(baseUrl+'cashier/update_tbl_activity/0/1',function(data){});
    }
    window.onbeforeunload = clearTableActivity;
});
    //Sparkline charts
    var myvalues = [511, 323, 555, 731, 100, 220, 101, 276, 195, 399, 219];
    $('#sparkline-1').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });
    myvalues = [15, 19, 20, 22, 55, 30, 58, 27, 19, 30, 21];
    $('#sparkline-2').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });
    myvalues = [35, 29, 30, 22, 33, 27, 31, 27, 29, 30, 36];
    $('#sparkline-3').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });
    myvalues = [15, 19, 20, 22, 33, -27, -31, 27, 19, 30, 21];
    $('#sparkline-4').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });
    myvalues = [15, 19, 20, 22, 33, 27, 31, -27, -19, 30, 21];
    $('#sparkline-5').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });
    myvalues = [15, 19, -20, 22, -13, 27, 31, 27, 19, 30, 21];
    $('#sparkline-6').sparkline(myvalues, {
        type: 'bar',
        barColor: '#00a65a',
        negBarColor: "#f56954",
        height: '20px'
    });

    function goTo(url){
        window.location = baseUrl+url;
    }