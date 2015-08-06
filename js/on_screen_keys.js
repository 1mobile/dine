$(document).ready(function(){
	var par = $('.scr-pad-key').closest('table');
	if(par.hasAttr('target')){
		var input = $(par.attr('target'));
	}
	else{
		var input = par.find('input');
	}
	input.focus();
	input.keypress(function(event){
	  if(event.keyCode == 13){
	    $('.btn-enter').trigger('click');
	  }
	});

	$('.scr-pad-key').disableSelection();
	$('.scr-pad-key').click(function(){
		var txt = $(this).text();
		var par = $(this).closest('table');
		if(par.hasAttr('target')){
			var input = $(par.attr('target'));
		}
		else
			var input = par.find('input');
		var inputTxt = input.val();


		if($(this).hasClass('btn-del')){
			if(inputTxt != ""){
				inputTxt = inputTxt.slice(0,-1);
				input.val(inputTxt);
			}
		}
		else if($(this).hasClass('btn-clear')){
			if(inputTxt != ""){
				input.val('');
			}
		}
		else if(!$(this).hasClass('btn-enter')) {
			if ( input.hasAttr('maxlength') && !isNaN(input.attr('maxlength')) ) {
				var max = parseInt(input.attr('maxlength'));

				if (max < inputTxt.length + txt.length ) {
					input.focus();
					return false;
				}
			}


			input.val(inputTxt+txt);
			input.focus();
		}

		return false;
	});


});

