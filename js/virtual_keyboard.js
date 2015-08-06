$(document).ready(function(){
	$('#username, #password')
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
});

