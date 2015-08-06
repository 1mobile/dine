/////////////////////////
//// Created by Reyp
(function($) {
	$.extend({
		callManager: function(options){
			var settings = $.extend({
				title 	: '',
				success	:	function(response){}
			}, options);
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/manager_call',
			  title: settings.title,
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "MANAGER PIN",
			      className: "btn btn-manager-submit pop-manage pop-manage-green",
			      callback: function() {
			      	var pin = $('#manager-call-pin-login').val();
			      	var formData = 'pin='+pin;
					$.post(baseUrl+'cashier/manager_go_login',formData,function(data){
						if (typeof data.error_msg === 'undefined'){
					      	settings.success.call(this,data.manager);
						}
						else
							rMsg(data.error_msg,'error');
					},'json');
			        return true;
			      }
			    },
			    cancel: {
			      label: "CANCEL",
			      className: "btn pop-manage pop-manage-red",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    }
			  }
			});
			box.bind('shown.bs.modal', function(){
			    input = box.find("#manager-call-pin-login");
			    input.focus();
			    input.keypress(function(event){
				  if(event.keyCode == 13){
				    box.find('.btn-manager-submit').trigger('click');
				  }
				});
			});
		},
		callFS: function(options){
			var settings = $.extend({
				title 	: '',
				success	:	function(response){}
			}, options);
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/food_server_call',
			  title: settings.title,
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "FOOD SERVER PIN",
			      className: "btn btn-manager-submit pop-manage pop-manage-green",
			      callback: function() {
			      	var pin = $('#fs-call-pin-login').val();
			      	var formData = 'pin='+pin;
					$.post(baseUrl+'cashier/food_server_login',formData,function(data){
						if (typeof data.error_msg === 'undefined'){
					      	settings.success.call(this,data.emp);
						}
						else
							rMsg(data.error_msg,'error');
					},'json');
			        return true;
			      }
			    },
			    cancel: {
			      label: "CANCEL",
			      className: "btn pop-manage pop-manage-red",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    }
			  }
			});
			box.bind('shown.bs.modal', function(){
			    input = box.find("#fs-call-pin-login");
			    input.focus();
			    input.keypress(function(event){
				  if(event.keyCode == 13){
				    box.find('.btn-manager-submit').trigger('click');
				  }
				});
			});
		},
		callReasons: function(options){
			var settings = $.extend({
				submit	:	function(response){}
			}, options);

			bootbox.dialog({
			  message: baseUrl+'cashier/manager_reasons',
			  // title: 'Somthing',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "SUBMIT",
			      className: "btn  pop-manage pop-manage-green",
			      callback: function() {
			        var reason = $('#pop-reason').val();
			        
			        settings.submit.call(this,reason);
			        return true;
			      }
			    },
			    cancel: {
			      label: "CANCEL",
			      className: "btn pop-manage pop-manage-red",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    }
			  }
			});
		},
		beep: function(options){
			var settings = $.extend({
				status	:	"success"
			}, options);
			if(settings.status == "success")
				$.playSound(baseUrl+'img/beep');
			else if(settings.status == "error")
				$.playSound(baseUrl+'img/beeperror');
		},
		rProgressBar: function (options) {
			var opt = 	$.extend({
				element  	 : 		null,
			}, options);
			var docHeight = $(document).height();
			$("body").append("<div id='overlayProgress'></div>");
			$("#overlayProgress")
				.height(docHeight)
				.css({
				// 'opacity' : 0.4,
				'position': 'absolute',
				'top': 0,
				'left': 0,
				"background-color": "rgba(0,0,0,.5)",
				'width': '100%',
				'z-index': 5000
			});
			var txt = $('<span/>')
					  .css({"position":'absolute','display':'block','width':'100%','color':'#000','font-size':'24px','margin':'10px auto'})
					  .text('0%');
			var bar = $('<div/>')
						.attr({'class':'progress-bar progress-bar-primary','role':'progressbar','aria-valuenow':'0','aria-valuemin':'0','aria-valuemax':'100'})
						.css({'width':"0%"})
						.append(txt);
			$('<div/>').attr({'class':'progress','id':'rProgressBar'})
					   .css({'width':'80%','height':'40px','margin':'20% auto','position':'relative'})
					   .append(bar).appendTo('#overlayProgress');
        	loadStatus();
			function loadStatus(){
				var statusElement = $('#rProgressBar .progress-bar span');
			    var progBar = $('#rProgressBar .progress-bar');
			    var i = setInterval(function (){
			        $.ajax(
			        {
			        	url: baseUrl+"site/get_load",
			        	dataType:  'json',
			            success: function (json){
			                statusElement.text(json.load + "%");
			                progBar.css({'width':json.load+'%'}).attr({'aria-valuenow':json.load});
			                if (json.load == 100){
			                	setTimeout(function(){
					                $('#overlayProgress').remove();
								},1000);
			                	clearInterval(i);
			            	}
			            },
			            error: function (){
			            	setTimeout(function(){
					                $('#overlayProgress').remove();
							},1000);
			                clearInterval(i);
			            }
			        });
			    }, 1000);
			}
        },
        rProgressBarEnd: function (options) {
        	var opt = 	$.extend({
				onComplete 	: 	function(response){},
			}, options);
	        var i = setInterval(function (){
		        var progBar = $('#rProgressBar .progress-bar');
		        if(progBar.exists()){
		        	var load = progBar.attr('aria-valuenow');
		        	if(load == 100){
			        	opt.onComplete.call();	
			        	clearInterval(i);	        		
		        	}
		        }
		        else{
		        	opt.onComplete.call();
		        	clearInterval(i);
		        }
		    }, 1000);
		}
	});
	$.fn.center = function() {
	    this.css("position", "absolute");
	    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
	            $(window).scrollTop()) - 30 + "px");
	    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
	            $(window).scrollLeft()) + "px");
	    return this;
	}
	$.fn.rLoadBar = function(options) {
		var opt = 	$.extend({
				bar  	 : 	 $(this).find('.progress-bar'),
				statTxt  : 	 null,
			}, options);
			var progBar = opt.bar;
			// alert(progBar.exists());
        	loadStatus(progBar,opt.statTxt);
			function loadStatus(progBar,statTxt){
				// var statusElement = $('#rProgressBar .progress-bar span');
			    // var progBar = $('#rProgressBar .progress-bar');
			    var i = setInterval(function (){
			        $.ajax(
			        {
			        	url: baseUrl+"site/get_load",
			        	dataType:  'json',
			            success: function (json){
			            	if(statTxt != null){
			                	if(statTxt.exists())
				                	statTxt.text(json.text);
			            	}
			                $('.loadTxt').text(json.load);
			                progBar.css({'width':json.load+'%'}).attr({'aria-valuenow':json.load});
			                if (json.load == 100){
			                	setTimeout(function(){
					                $('#overlayProgress').remove();
								},1000);
			                	clearInterval(i);
			            	}
			            },
			            error: function (){
			            	setTimeout(function(){
					                $('#overlayProgress').remove();
							},1000);
			                clearInterval(i);
			            }
			        });
			    }, 1000);
		}
	}
	$.fn.rLoadBarEnd = function(options) {
		var opt = 	$.extend({
			onComplete 	: 	function(response){},
			bar  	 : 	 $(this).find('.progress-bar')
		}, options);
        var i = setInterval(function (){
	        var progBar = opt.bar;
	        if(progBar.exists()){
	        	var load = progBar.attr('aria-valuenow');
	        	if(load == 100){
		        	opt.onComplete.call();	
		        	clearInterval(i);	        		
	        	}
	        }
	        else{
	        	opt.onComplete.call();
	        	clearInterval(i);
	        }
	    }, 1000);
	}	
	// Create a jquery plugin that prints the given element.
	$.fn.print = function(){
	    // NOTE: We are trimming the jQuery collection down to the
	    // first element in the collection.
	    if (this.size() > 1){
	        this.eq( 0 ).print();
	        return;
	    } else if (!this.size()){
	        return;
	    }
	 
	    // ASSERT: At this point, we know that the current jQuery
	    // collection (as defined by THIS), contains only one
	    // printable element.
	 
	    // Create a random name for the print frame.
	    var strFrameName = ("printer-" + (new Date()).getTime());
	 
	    // Create an iFrame with the new name.
	    var jFrame = $( "<iframe name='" + strFrameName + "'>" );
	 
	    // Hide the frame (sort of) and attach to the body.
	    jFrame
	        .css( "width", "1px" )
	        .css( "height", "1px" )
	        .css( "position", "absolute" )
	        .css( "left", "-9999px" )
	        .appendTo( $( "body:first" ) )
	    ;
	 
	    // Get a FRAMES reference to the new frame.
	    var objFrame = window.frames[ strFrameName ];
	 
	    // Get a reference to the DOM in the new frame.
	    var objDoc = objFrame.document;
	 
	    // Grab all the style tags and copy to the new
	    // document so that we capture look and feel of
	    // the current document.
	 
	    // Create a temp document DIV to hold the style tags.
	    // This is the only way I could find to get the style
	    // tags into IE.
	    var jStyleDiv = $( "<div>" ).append(
	        $( "style" ).clone()
	        );
	 
	    // Write the HTML for the document. In this, we will
	    // write out the HTML of the current element.
	    objDoc.open();
	    objDoc.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
	    objDoc.write( "<html>" );
	    objDoc.write( "<body>" );
	    objDoc.write( "<head>" );
	    objDoc.write( "<title>" );
	    objDoc.write( document.title );
	    objDoc.write( "</title>" );
	    objDoc.write( jStyleDiv.html() );
	    objDoc.write( "</head>" );
	    objDoc.write( this.html() );
	    objDoc.write( "</body>" );
	    objDoc.write( "</html>" );
	    objDoc.close();
	 
	    // Print the document.
	    objFrame.focus();
	    objFrame.print();
	 
	    // Have the frame remove itself in about a minute so that
	    // we don't build up too many of these frames.
	    setTimeout(
	        function(){
	            jFrame.remove();
	        },
	        (60 * 1000)
	        );
	}
	$.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
	$.fn.exists = function(){return this.length>0;}
	$.fn.hasAttr = function(name) {  
	   return this.attr(name) !== undefined;
	};
	$.fn.rOkay = function(options)	{
		var settings = $.extend({
			passTo		:	this.attr('action'),
			addData		:	"",
			checkCart	:	null,
			validate	: 	true,
			asJson		: 	false,
			btn_load	: 	null,
			goSubmit	: 	true,
			bnt_load_remove	: 	true,
			onComplete	:	function(response){}
		},options);

		function generate(text){
			var n = noty({
			       text        : text,
			       type        : 'error',
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

		var errors = 0;
		var check_form = $(this);
		if(settings.validate){
			check_form.find('.rOkay').each(function(){
				if($(this).val() == ""){
					var txt = $(this).prev('label').text();
					var msg = $(this).attr('ro-msg');

					var display_msg = 'Error! '+txt+' must not be empty.';
					if(typeof msg !== 'undefined' && msg !== false)
						display_msg = msg;
						
					generate(display_msg);
					$(this).focus();
					errors = errors+1;
					return false;
				}
				
			});
		}
		if(settings.goSubmit){
			if(errors == 0){
				var form = check_form;
				var formData = check_form.serialize();
				if(settings.addData != "")
					formData = formData+'&'+settings.addData;

				// alert(formData);
				if(settings.btn_load != null){
					// var pretext = check_form.attr('id');
					var pretext = check_form.attr('id');
					var lastTxt = settings.btn_load.html();
					// settings.btn_load.attr("disabled", "disabled").after(' <img src="'+baseUrl+'/images/preloader.gif" height="20" id="'+pretext+'-preloader">');
					settings.btn_load.attr("disabled", "disabled").html(lastTxt+' <i class="fa fa-spinner fa-spin fa-fw"></i>');
				}


				if(settings.asJson){
					if(settings.checkCart != null){
						$.post(baseUrl+'wagon/check_wagon/'+settings.checkCart,function(check){
							if(check.error > 0){
								settings.btn_load.html(lastTxt);
								settings.btn_load.removeAttr("disabled");
								generate('Error! '+ check.msg);
							}
							else{
								
								if(settings.btn_load != null && settings.bnt_load_remove){
									settings.btn_load.html(lastTxt);
									settings.btn_load.removeAttr("disabled");
								}

								$.post(baseUrl+settings.passTo,formData,function(data){
									// alert(data);
									settings.onComplete.call(this,data);
								// });		
								},"json");		
							}
						},"json");
					}
					else{
						$.post(baseUrl+settings.passTo,formData,function(data){
							if(settings.btn_load != null && settings.bnt_load_remove){
								settings.btn_load.html(lastTxt);
								settings.btn_load.removeAttr("disabled");
							}
							settings.onComplete.call(this,data);
						// });
						},"json");
					}
				}
				else{
					if(settings.checkCart != null){
						$.post(baseUrl+'wagon/check_wagon/'+settings.checkCart,function(check){
							if(check.error > 0){
								settings.btn_load.html(lastTxt);
								settings.btn_load.removeAttr("disabled");
								generate('Error! '+ check.msg);
							}
							else{
								// alert(formData);	
								if(settings.btn_load != null && settings.bnt_load_remove){
									settings.btn_load.html(lastTxt);
									settings.btn_load.removeAttr("disabled");
								}
								$.post(baseUrl+settings.passTo,formData,function(data){
									settings.onComplete.call(this,data);
								});		
							}
						},"json");
					}
					else{
						$.post(baseUrl+settings.passTo,formData,function(data){
							if(settings.btn_load != null && settings.bnt_load_remove){
								settings.btn_load.html(lastTxt);
								settings.btn_load.removeAttr("disabled");
							}
							settings.onComplete.call(this,data);
						});	
					}
				}
			}		
		}
		else{

			if(errors > 0)
				return false
			else
				return true;
		}
	}
	$.fn.rLoad = function(options) {
		var settings = $.extend({
			url		:	''
		}, options);
		// this.html('<center><img src="'+baseUrl+'/images/preloader.gif"></center>').load(settings.url);
		this.html('<center><span><i class="fa fa-spinner fa-lg fa-fw fa-spin"></i></span></center>').load(settings.url);
	}
	$.fn.goLoad = function(options) {
		var settings = $.extend({
			load 	:  true
		}, options);
		var txt = this.html();
		if(settings.load){
			this.attr("disabled", "disabled").html(txt+' <i class="fa fa-spinner fa-spin fa-fw go-load-spinner"></i>');
		}
		else{
			this.removeAttr("disabled");
			this.find('.go-load-spinner').remove();
		}
	}
	$.fn.goLoad2 = function(options) {
		var settings = $.extend({
			load 	:  true
		}, options);
		var txt = this.html();
		if(settings.load){
			this.attr("disabled", "disabled");
			var docHeight = $(document).height();
			$("body").append("<div id='overlayProgress'></div>");
			$("#overlayProgress")
				.height(docHeight)
				.css({
				// 'opacity' : 0.4,
				'position': 'absolute',
				'top': 0,
				'left': 0,
				"background-color": "rgba(0,0,0,.5)",
				'width': '100%',
				'z-index': 5000
			});
			var txt = $('<span/>')
				  .css({"position":'absolute','display':'block','width':'100%','color':'#fff','font-size':'24px','margin':'10px auto'})
				  .text('Loading...');
			var bar = $('<div/>').css({'width':'20%','height':'40px','margin':'20% auto','position':'relative'}).append(txt);
			$('<div/>')
				   .append(bar).appendTo('#overlayProgress');
		}
		else{
			this.removeAttr("disabled");
			$('#overlayProgress').remove();
		}
	}
	$.fn.rTable = function(options) {
		var opt = $.extend({
			tbl  	  		: 	$(this),
			loadFrom		:	$(this).attr('data-tbl-url'), 
			loadData		:	"", 
			noEdit 			:   false,
			beforeLoad 		:	function(response){},
			afterLoad  		:	function(response){},
			add 			: 	function(response){},
			edit 			: 	function(response){}
		}, options);
		var table = opt.tbl;
		
		table.addClass('rTable');
		if(opt.loadFrom != null){
			var loadUrl = opt.loadFrom;
			var formData = "";
			if(opt.loadData != "")
				formData = opt.loadData;
			loadTable(table);
			$.post(baseUrl+loadUrl,formData,function(data){
				unloadTable(table);
				appendBtns(table,data);
				populateTable(table,data);
			},'json');
			// alert(data);
			// });
		}
		else{
			var rows = table.find('tr:gt(0)');
			rows.each(function(){
				$(this).click(function(){
					selector(table,$(this));
					return false;
				});
			});
		}
		function populateTable(table,data){
			console.log(data);
			var cells = 0;
			// console.log(data.post);
			var style = table.attr('listyle');

			if(style == 'list'){
				
				$.each(data.rows,function(id,td){
					
					var row = $('<tr/>')
								.attr({'ref':id});
								
					if(!opt.noEdit){
						row.addClass('needHover')
						   .click(function(){
							selector(table,$(this));
							disableEnableBtns(table);
							return false;
						});
					}


					$.each(td,function(key,txt){
						$('<td/>')
							.html(txt)
							.appendTo(row);
						cells++;
					});
					row.appendTo(table);
				});
			}
			else{
				$('#rtable-ths').hide();
				var mainRow = $('<tr/>');
				var mainCell = $('<td/>')
							    .attr({'colspan':'100%'})
							    .css({'background-color':'#ddd'})
							    .appendTo(mainRow);
				var divRow = $('<div/>').attr({'class':'row'});

				$.each(data.rows,function(id,td){
					var col = $('<div/>').attr({'class':'col-md-3 text-left'});
					// $.each(td,function(key,txt){
						var img = '<img src="'+baseUrl+'img/user_default.png" style="height:100%;width:100%">';
						if(td.hasOwnProperty('image')){
							var img = '<img src="'+baseUrl+td['image']+'" style="height:100%;width:100%">';
						}
						col.html('<div class="info-box" style="cursor:pointer">'+
								     '<span class="info-box-icon" style="line-height:0px">'+
								     img+
								     '</span>'+
								     '<div class="info-box-content">'+
								     '<h5>'+td['title']+'</h5>'+
								     '<h5>'+td['subtitle']+'</h5>'+
								     '<h6 style="margin:0px;">'+td['caption']+'</h6>'+
								     '</div>'+
							     '</div>')
								.click(function(){
									opt.edit.call(this,id);
									return false;
								});
					// });
					col.appendTo(divRow);
				});	

				mainCell.append(divRow);		
				mainRow.appendTo(table);		
			}
			if(data.page != ""){
				var pagiRow = $('<tr/>');
				var pagiCell = $('<td/>')
								.attr('colspan',cells)
								.appendTo(pagiRow);
				pagiCell.append(data.page);
				table.append(pagiRow);
				
				var search = data.post;
				$('.pagination li a.ragi').click(function(data){
					var li = $(this).parent();
					if(!li.hasClass('disabled')){
						var pagi = $(this).attr('pagi');
						loadTable(table);
						var loadUrl = opt.loadFrom;
						var formData = opt.loadData;
						if(opt.loadData != ""){
							formData = opt.loadData;
							formData += '&pagi='+pagi;
						}
						else
							formData += 'pagi='+pagi;

						// var search = data.post;
						console.log(search);
						if(typeof search !== 'undefined' && search !== false){
								$.each(search,function(name,val){
									if(val != ""){
										if(name != "pagi"){
											formData += '&'+name+'='+val;
										}
									}
								});							
							console.log(formData);
						}
						$.post(baseUrl+loadUrl,formData,function(data){
							unloadTable(table);
							populateTable(table,data);
						},'json');
					}
					return false;
				});
			}
		}		
		function selector(table,row){
			if(row.hasClass('selected')){
				table.find('tr').removeClass('selected');
			}
			else{
				table.find('tr').removeClass('selected');
				row.addClass('selected');
			}
		}
		function loadTable(table){
			var tableloader = $('<tr><td colspan="100%"><center><i class="fa fa-spin fa-spinner fa-fw fa-2x"></i></center></td></tr>');
			$('#rtable-ths').hide();
			table.find('tr:gt(0)').remove();
			table.append(tableloader);
		}
		function unloadTable(table){
			table.find('tr:gt(0)').remove();
			$('#rtable-ths').show();
		}
		function appendBtns(table,loadData){
			var search = table.attr('search-url');
			var multi = table.attr('listyle-multi');
			var style = table.attr('listyle');
			var filter = loadData.post;
			$('#appends-btns').remove();
			table.before('<div class="row" id="appends-btns"><div class="col-md-12 text-right"><div class="btn-group" id="rTable-btns"></div></div></div>');
				$('<button/>')
					.attr({'id':'rtable-add-btn','class':'btn-success btn'})
					.html('<i class="fa fa-plus fa-fw"></i> ADD')
					.appendTo('#rTable-btns')
					.click(function(e){
						opt.add.call(e);
						return false;
					});
			if(!opt.noEdit){
				$('<button/>')
					.attr({'id':'rtable-edit-btn','class':'btn-primary btn'})
					.html('<i class="fa fa-edit fa-fw"></i> EDIT')
					.prop('disabled', true)
					.appendTo('#rTable-btns')
					.click(function(e){
						var sel = table.find('tr.selected');
						var id = sel.attr('ref');
						opt.edit.call(this,id);
						return false;
					});
			}
			if(search != ""){
				$('<button/>')
					.attr({'id':'rtable-search-btn','class':'btn-info btn'})
					.html('<i class="fa fa-filter fa-fw"></i> Filter')
					.appendTo('#rTable-btns')
					.click(function(e){
						var btn1 = "<i class='fa fa-check'></i> Submit";
						var classN = 'rTable-search-form';
						bootbox.dialog({
						  message: baseUrl+search,
						  title: '<i class="fa fa-filter fa-fw"></i> Filter',
						  className : classN,
						  buttons: {
						    cancel: {
						      label: "Cancel",
						      className: "btn-default",
						      callback: function() {
						        return true;	
						      }
						    },
						    submit: {
						      label: btn1,
						      className: "btn-primary rFormSubmitBtn",
						      callback: function() {
						      		var form = $('.rTable-search-form .bootbox-body').children('form');
						      		var datas = form.serialize();
						      		
						      		var loadUrl = opt.loadFrom;
						      		var formData = opt.loadData;
						      		if(formData != "")
						      			formData += '&'+datas;
						      		else
						      			formData += datas;

						      		loadTable(table);
						      		$.post(baseUrl+loadUrl,formData,function(data){
						      			unloadTable(table);
						      			console.log(data);
						      			populateTable(table,data);
						      			appendBtns(table,data);
						      		},'json');
						      		// alert(data);
						      		// });
									return true;	
						      }
						    }
						  }
						});	
						return false;
					});	
			}
			if(multi == 'yes'){
				var styleDiv = $('<div/>').addClass('btn-group').css({'margin-right':'10px'});
				var list = $('<button/>')
					.attr({'id':'rtable-list-btn','class':'btn listyle-btns'})
					.html('<i class="fa fa-align-justify fa-fw"></i>')
					.appendTo(styleDiv)
					.click(function(e){
						var loadUrl = opt.loadFrom;
						var formData = "";
						var styleBTN = $(this);
						if(opt.loadData != "")
							formData = opt.loadData;
						console.log(filter);
						if(typeof filter !== 'undefined' && filter !== false){
								$.each(filter,function(name,val){
									if(val != ""){
										if(name != "pagi"){
											formData += '&'+name+'='+val;
										}
									}
								});							
							console.log(formData);
						}
						table.attr('listyle','list');
						loadTable(table);
						$.post(baseUrl+loadUrl,formData,function(data){
							unloadTable(table);
							populateTable(table,data);
							// appendBtns(table,data);
							$('.listyle-btns').removeClass('active');
							styleBTN.addClass('active');
						},'json');
						return false;
					});
				var grid = $('<button/>')
					.attr({'id':'rtable-grid-btn','class':'btn listyle-btns'})
					.html('<i class="fa fa-th fa-fw"></i>')
					.appendTo(styleDiv)
					.click(function(e){
						var loadUrl = opt.loadFrom;
						var formData = "";
						var styleBTN = $(this);
						if(opt.loadData != "")
							formData = opt.loadData;
						if(typeof filter !== 'undefined' && filter !== false){
								$.each(filter,function(name,val){
									if(val != ""){
										if(name != "pagi"){
											formData += '&'+name+'='+val;
										}
									}
								});							
							console.log(formData);
						}
						table.attr('listyle','grid');
						loadTable(table);
						$.post(baseUrl+loadUrl,formData,function(data){
							unloadTable(table);
							populateTable(table,data);
							// appendBtns(table,data);
							$('.listyle-btns').removeClass('active');
							styleBTN.addClass('active');
						},'json');
						return false;
					});	
				if(style == 'list'){
					$('.listyle-btns').removeClass('active');
					list.addClass('active');
				}	
				else if(style == 'grid'){
					$('.listyle-btns').removeClass('active');
					grid.addClass('active');
				}
				$('#rTable-btns').after(styleDiv);


			}	
		}
		function disableEnableBtns(table){
			var sel = table.find('tr.selected');
			if(sel.exists()){
				$('#rtable-edit-btn').prop('disabled', false);
			}
			else{
				$('#rtable-edit-btn').prop('disabled', true);
			}
		}	
	}

	$.fn.rPopForm = function(options) {
		$(this).click(function(){
			var settings = $.extend({
				loadUrl		:	$(this).attr('href'),
				passTo		:	$(this).attr('rata-pass'),
				title		:	$(this).attr('rata-title'),
				rform		:	$(this).attr('rata-form'),
				wide 		:   false,
				addData		:	"",
				hide		:	false,
				asJson		:	false,
				onComplete	:	function(response){}
			}, options);

			// alert(settings.loadUrl);
			// alert($(this).attr('href'));
			if(settings.wide)
				var classN = "bootbox-wide";
			else
				var classN = null;

			bootbox.dialog({
			  message: baseUrl+settings.loadUrl,
			  title: settings.title,
			  className : classN,
			  buttons: {
			    cancel: {
			      label: "Cancel",
			      className: "btn-default",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    },
			    submit: {
			      label: "<i class='fa fa-save'></i> Submit",
			      className: "btn-primary rFormSubmitBtn",
			      callback: function() {
						// var formData = $('#'+settings.rform).serialize();
						// if(settings.addData != "")
						// 	formData = formData+'&'+settings.addData;
						// $.post(baseUrl+settings.passTo,formData,function(data){
						// 	settings.onComplete.call(this,data);
						// });

						$('#'+settings.rform).rOkay({
								onComplete	: 	settings.onComplete,
								passTo		: 	settings.passTo,
								addData		: 	settings.addData,
								asJson		: 	settings.asJson,
								btn_load	: 	$('.rFormSubmitBtn'),
						});
						return settings.hide;	
			      }
			    }
			  }
			});	
			return false;			
		})
	}
	$.fn.rPopFormFile = function(options) {
		$(this).click(function(){
			var settings = $.extend({
				loadUrl		:	$(this).attr('href'),
				passTo		:	$(this).attr('rata-pass'),
				title		:	$(this).attr('rata-title'),
				rform		:	$(this).attr('rata-form'),
				wide 		:   false,
				addData		:	"",
				hide		:	false,
				asJson		:	false,
				onComplete	:	function(response){}
			}, options);

			// alert(settings.loadUrl);
			// alert($(this).attr('href'));
			if(settings.wide)
				var classN = "bootbox-wide";
			else
				var classN = null;

			bootbox.dialog({
			  message: baseUrl+settings.loadUrl,
			  title: settings.title,
			  className : classN,
			  buttons: {
			    cancel: {
			      label: "Cancel",
			      className: "btn-default",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    },
			    submit: {
			      label: "<i class='fa fa-save'></i> Submit",
			      className: "btn-primary rFormSubmitBtn",
			      callback: function() {
						var noError = $('#'+settings.rform).rOkay({
			    			asJson			: 	settings.asJson,
							btn_load		: 	$('.rFormSubmitBtn'),
							goSubmit		: 	false,
							bnt_load_remove	: 	true
			    		});
			    		if(noError){
			    			var dtype = 'script';
			    			if(settings.asJson)
			    				dtype = 'json';
			    			$('#'+settings.rform).submit(function(e){
							    var formObj = $(this);
							    var formURL = settings.passTo;
							    var formData = new FormData(this);
							    $.ajax({
							        url: baseUrl+formURL,
							        type: 'POST',
							        data:  formData,
							        dataType:  dtype,
							        mimeType:"multipart/form-data",
							        contentType: false,
							        cache: false,
							        processData:false,
							        success: function(data, textStatus, jqXHR){
							         	settings.onComplete.call(this,data);
							        },
							        error: function(jqXHR, textStatus, errorThrown){
							        }          
							    });
							    e.preventDefault();
							//     e.unbind();
							}); 
							$('#'+settings.rform).submit();
						}
						return settings.hide;	
			       }
			    }
			  }


			});	
			return false;			
		})
	}
	$.fn.rPopView = function(options) {
		$(this).click(function(){
			var settings = $.extend({
				loadUrl		:	$(this).attr('href'),
				title		:	$(this).attr('rata-title'),
				wide		: 	false,
				addData		:	""
			}, options);

			if(settings.wide){
				var bootClass = "bootbox-wide";
			}
			else
				var bootClass = "";

			bootbox.dialog({
			  message: baseUrl+settings.loadUrl,
			  title: settings.title,
			  className: bootClass,
			  buttons: {
			    cancel: {
			      label: "Close",
			      className: "btn-default",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			      }
			    }
			  }


			});	
			// bootbox.classes.add('bootbox-wide');
			return false;			
		})
	}
	$.fn.rPrint = function(options) {
		var settings = $.extend({
			loadUrl		:	$(this).attr('href'),
			title		:	$(this).attr('print-title')
			// wide		: 	false,
			// addData		:	""
		}, options);
		$(this).click(function(){
			var title = $(this).attr('print-title');
			var path = $(this).attr('href');

			// alert(settings.loadUrl);
			// window.open(url+path,title);
			window.open(path,title,"height=600,width=800");
			return false;			
		})
	}
	$.fn.rChangeSetVal = function(options) {
		var settings = $.extend({
			tbl		:	'',
			where	:	'',
			col		:	'',
			changee : 	''
		}, options);
		
		this.change(function(){
			
			var val = $(this).val();
			var formData = 'tbl='+settings.tbl+'&col='+settings.col+'&where='+settings.where+'&val='+val;
			
			goCall(formData, function(data){
				var obj = $('#'+settings.changee);
				if( obj.is('input') || obj.is('select') || obj.is('textarea') ) {
					 obj.val(data);
				}
				else{
					obj.text(data);
				}
			});	

			function goCall(formData, callback){
			    $.post(baseUrl+'/site/custom_get_val', formData, function(data){
			        callback(data);
			    });
			}
		});
	}
	$.fn.rWagon = function(options) {
		var opt = $.extend({
			cart      		: 	'',
			tbl  	  		: 	$(this),
			add_wagon_cell	: 	null,
			input_row 		: 	null,
			inputs	 		: 	null,
			reset_row		:   true,
			datas     		: 	null,
			removeAdd  		: 	false,
			// add_datas 		: null,
			// function(response){}
			beforeAddShow	:	null, 
			onAdd	  		:	function(response){},
			onUpdate  		:	function(response){},
			onCancelUpdate  :	function(response){},
			onEdit  		:	function(response){},
			onDelete  		:	function(response){}
		}, options);
		set_edit_rows();
		make_add_item_row();
		append_add_wagon_btns();
		links_act();
		function links_act(){
			$('#show-input'+opt.cart).click(function(){
				var before = true;
				var ret = null;
				if($.isFunction(opt.beforeAddShow)){
					var ret = opt.beforeAddShow.call(this);
				}
				if(ret != null){
					before = ret;
				}
				
				if(before){
					var row = $(opt.input_row);
					row.show();
					$('#show-input-row'+opt.cart).hide();
					var pos = $('#show-input-row'+opt.cart).index();
					opt.tbl.find('tbody > tr').eq(pos).after(row);
				}
				return false;
			});
			$('#wAddCancel'+opt.cart).click(function(){
				var row = $(opt.input_row);
				row.hide();
				$('#show-input-row'+opt.cart).show();
				
				return false;
			});
			$('#wAdd'+opt.cart).click(function(){
				add_row();
				return false;
			});
			$('#wUpdate'+opt.cart).click(function(){
				// e.preventDefault;
				var id = $(this).attr('ref');
				// alert(ref);
				update_row(id);
				return false;
			});
			$('#wUpdateCancel'+opt.cart).click(function(){
				// e.preventDefault;
				var row = $(opt.input_row);
				var id = $(this).attr('ref');
				$('.line-row').show();
				$('#line-'+opt.cart+'-'+id).show();
				$('.wagon-add-btns').show();
				$('.wagon-update-btns').hide();
				$('.wagon-update-btns').removeAttr('ref');
				row.hide();
				$('#show-input-row').show();
				opt.onCancelUpdate.call(this);
				return false;
			});
		}
		function add_row(){
			var pos = $('#show-input-row').index();
			var formData = "";
			var row = $(opt.input_row);
			var ctr = 1;
			var string_row = "";
			$.each(opt.inputs,function(key,val){
				var elem = row.find(val['from']);
				if(elem.exists()){
					var s = "";
					if(ctr > 1){
						s="&";
					}
					// formData = formData+s+key+'='+elem.val(); 
					if( elem.is('input') ||  elem.is('textarea') ) {
						var echo = elem.val();
						formData = formData+s+key+'='+elem.val(); 
					}
					else if(elem.is('select')){
						var echo = elem.find(":selected").text();
						formData = formData+s+key+'='+elem.val(); 
					}
					else{
						var echo = elem.text();
						formData = formData+s+key+'='+elem.text(); 
					}

					if(val['show'] != null){
						if(isNumber(echo) && typeof(val['string-type'])=='undefined'){
							var number = formatNumber(parseFloat(echo),val['dec']);
							string_row = string_row+"<td>"+number+"</td>";
						}
						else
							string_row = string_row+"<td>"+echo+"</td>";
					}
				}
				ctr++;
			});			
			
			$.post(baseUrl+'wagon/add_to_wagon/'+opt.cart,formData,function(data){
				var string = "<tr id='line-"+opt.cart+"-"+data.id+"' class='line-row line-row-"+opt.cart+"'>";
				var string = string+string_row;
				string = string+"<td><a href='#' id='edit-"+opt.cart+"-"+data.id+"' class = 'edit-"+opt.cart+"' ref='"+data.id+"'><i class='fa fa-edit fa-lg'></i></a>";
				string = string+" <a href='#' id='delete-"+opt.cart+"-"+data.id+"' class = 'delete-"+opt.cart+"' ref='"+data.id+"'><i class='fa fa-trash-o fa-lg'></i></a></td>";
				string = string+"</tr>";
				opt.tbl.find('tbody > tr').eq(pos).before(string);
				
				edit_row(opt.cart,data.id,data.items);
				opt.onAdd.call(this,data);

				if(opt.reset_row)
					reset_row();

			},"json");
			// });
		}	
		function reset_row(){
			$.each(opt.inputs,function(key,val){
				var row = $(opt.input_row);
				var elem = row.find(val['from']);
				if(elem.exists()){
					if( elem.is('input') ||  elem.is('textarea') ) {
						elem.val("");
					}
					else if(elem.is('select')){
						if(elem.hasClass('dropitize')){
							elem.find('option:first-child').prop('selected', true)
							    .end().trigger('chosen:updated');
						}
						else{
							elem.val(elem.find("option:first").val());
						}
						// elem.find("option").removeAttr('selected').find(':first').attr('selected','selected');
					}
					else{
						elem.text("");
					}
				}
				
			});	
		}
		function update_row(id){
			var pos = $('#line-'+opt.cart+'-'+id).index();
			var formData = "";
			var string_row = "";
			var ctr = 1;
			var row = $(opt.input_row);
			$.each(opt.inputs,function(key,val){
				var elem = row.find(val['from']);
				if(elem.exists()){
					var s = "";
					if(ctr > 1){
						s="&";
					}
					// formData = formData+s+key+'='+elem.val(); 
					if( elem.is('input') ||  elem.is('textarea') ) {
						var echo = elem.val();
						formData = formData+s+key+'='+elem.val(); 
					}
					else if(elem.is('select')){
						var echo = elem.find(":selected").text();
						formData = formData+s+key+'='+elem.val(); 
					}
					else{

						var echo = elem.text();
						formData = formData+s+key+'='+elem.text(); 
						
					}

					if(val['show'] != null){
						
						if(isNumber(echo) && typeof(val['string-type'])=='undefined'){
							var number = formatNumber(parseFloat(echo),val['dec']);
							string_row = string_row+"<td>"+number+"</td>";
						}
						else
							string_row = string_row+"<td>"+echo+"</td>";
					}
				}
				ctr++;
			});	
			
			formData = formData+'&update='+id;
			
			$.post(baseUrl+'wagon/update_to_wagon/'+opt.cart,formData,function(data){
				$('#line-'+opt.cart+'-'+id).remove();
				var string = "<tr id='line-"+opt.cart+"-"+data.id+"' class='line-row line-row-"+opt.cart+"'>";
				var string = string+string_row;
				string = string+"<td><a href='#' id='edit-"+opt.cart+"-"+data.id+"' class = 'edit-"+opt.cart+"' ref='"+data.id+"'><i class='fa fa-edit fa-lg'></i></a>";
				string = string+" <a href='#' id='delete-"+opt.cart+"-"+data.id+"' class = 'delete-"+opt.cart+"' ref='"+data.id+"'><i class='fa fa-trash-o fa-lg'></i></a></td>";
				string = string+"</tr>";
				opt.tbl.find('tbody > tr').eq(pos).before(string);
				// alert(data.items);
				edit_row(opt.cart,data.id,data.items);
				opt.onUpdate.call(this,data);
			},"json");	
			$('.wagon-add-btns').show();
			$('.wagon-update-btns').hide();
			$('.wagon-update-btns').removeAttr('ref');
			row.hide();
			$('#show-input-row').show();
		}
		function edit_row(cart_name,id,items){
			$("#edit-"+cart_name+"-"+id).click(function(e){
				e.preventDefault();
				var row = $(opt.input_row);
				row.show();
				var pos = $('#line-'+cart_name+'-'+id).index(); 

				$.each(opt.inputs,function(key,val){
					var from = row.find(val['from']);
					var elem = row.find(val['show']);

					if(elem.exists()){

						if( elem.is('input') ||  elem.is('textarea') ) {
							 elem.val(items[key]);

						}
						else if( elem.is('select') ){
							elem.val(items[key]).trigger("chosen:updated");
						}
						else{
							
							// if(!isNaN(parseFloat(items[key])) && typeof(val['string-type'])=='undefined'){
							// 	elem.number(items[key],val['dec']);
							// }
							// else	
							
							elem.text(items[key]);
						}
					}
					if(from.exists()){

						if( from.is('input') ||  from.is('textarea') ) {
							 from.val(items[key]);

						}
						else if( from.is('select') ){
							from.val(items[key]).trigger("chosen:updated");
						}
						else{
							
							if(isNumber(items[key]) && typeof(val['string-type'])=='undefined'){
								from.number(items[key],val['dec']);
							}
							else	
								from.text(items[key]);
						}
					}
				});

				$('.line-row').show();
				$('#line-'+cart_name+'-'+id).hide();
				opt.tbl.find('tbody > tr').eq(pos).after(row);
				
				$('.wagon-add-btns').hide();
				$('.wagon-update-btns').show();
				$('.wagon-update-btns').attr('ref',id);

				$('#show-input-row').hide();
				opt.onEdit.call(this);
			});	

			$("#delete-"+cart_name+"-"+id).click(function(e){
				var formData = 'delete='+id;
				$.post(baseUrl+'wagon/delete_to_wagon/'+cart_name,formData,function(data){
					$('#line-'+cart_name+'-'+id).remove();
					opt.onDelete.call(this,data);
				},"json");	
				e.preventDefault();
			});	
		}
		function set_edit_rows(){
			var edit_rows = $('.wagon-edit-rows');
			// alert(edit_rows.length);
			if(edit_rows.length > 0){
				edit_rows.each(function(){
					var row_id = $(this).attr('ref');
					var cart = $(this).attr('cart');
					if(cart == opt.cart){
						var string = "";
						$(this).addClass("line-row line-row-"+opt.cart);
						$(this).attr("id","line-"+opt.cart+"-"+row_id);
						var rowa = $(this);
						$.post(baseUrl+'wagon/get_wagon/'+opt.cart+'/'+row_id,function(data){
							string = string+"<a href='#' id='edit-"+opt.cart+"-"+row_id+"' class = 'edit-"+opt.cart+"' ref='"+row_id+"'><i class='fa fa-edit fa-lg'></i></a>";
							string = string+" <a href='#' id='delete-"+opt.cart+"-"+row_id+"' class = 'delete-"+opt.cart+"' ref='"+row_id+"'><i class='fa fa-trash-o fa-lg'></i></a>";
							// alert(data);
							var cell = rowa.find('td:last-child');
							// alert(data.items);
							// alert(cell);
							cell.html(string);
							edit_row(opt.cart,row_id,data.items);						
						// });
						},"json");
					}
				});
			}
		}
		function append_add_wagon_btns(){
			var row = $(opt.input_row);
			var cell = row.find('td:last-child');
			if(opt.add_wagon_cell != null){
				cell = row.find(opt.add_wagon_cell);
			}
			var string = "";
			string += "<a href='#' id='wAdd"+opt.cart+"' class='wagon-add-btns'><i class='fa fa-check fa-lg'></i></a> <a href='#' id='wAddCancel"+opt.cart+"' class='wagon-add-btns'><i class='fa fa-ban fa-lg'></i></a>";
			string += "<a href='#' id='wUpdate"+opt.cart+"' class='wagon-update-btns' style='display:none;'><i class='fa fa-check fa-lg'></i></a> <a href='#' id='wUpdateCancel"+opt.cart+"' class='wagon-update-btns' style='display:none;'><i class='fa fa-ban fa-lg'></i></a>";
			cell.html(string);
		}
		function make_add_item_row(){
			// $('#'+opt.tbl);
			var pos = 0;
			var string = "<tr id='show-input-row"+opt.cart+"'>";
				string += "<td colspan='100%'><a href='#' id='show-input"+opt.cart+"' >Add an Item</a></td>";
			string += "</tr>";

			var edit_rows = opt.tbl.find('.wagon-edit-rows');
			// alert(edit_rows.length);
			if(edit_rows.length > 0){
				ctr = 1;
				edit_rows.each(function(){
					pos = ctr;
					ctr++;
				});
			}
				
			if(!opt.removeAdd){
				opt.tbl.find('tbody > tr').eq(pos).after(string);
			}
		}
		function isNumber(n) {
 			 return !isNaN(parseFloat(n)) && isFinite(n);
		}
	}
	$.fn.rWagonClear = function(options) {
		var opt = $.extend({
			cart      		: 	'',
			tbl  	  		: 	$(this),
			beforeClear		:	null, 
			onClear  		:	function(response){}
		}, options);

		var before = true;
		var ret = null;
		if($.isFunction(opt.beforeClear)){
			var ret = opt.beforeClear.call(this);
		}
		if(ret != null){
			before = ret;
		}

		if(before){
			opt.tbl.find(".line-row-"+opt.cart).hide();
			$.post(baseUrl+'wagon/clear_wagon/'+opt.cart,function(data){
				opt.tbl.find(".line-row-"+opt.cart).remove();
			});	
		}
	}	
}(jQuery));