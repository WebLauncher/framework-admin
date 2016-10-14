jQuery.ajaxSetup({ cache: false });

function InitJs() {
	jQuery("[title]").each(function(i, el) {
		if (!jQuery(el).data("tipTip")) {
			if(!(jQuery(el).attr('title').indexOf(" ") >= 0) && !(jQuery(el).attr('title').indexOf("&") >= 0) && jQuery(jQuery(el).attr('title')).length)
			{
				jQuery(el).tipTip({maxWidth: "auto", edgeOffset: 10,content:jQuery(jQuery(el).attr('title')).html()});
			}
			else
				jQuery(el).tipTip({maxWidth: "auto", edgeOffset: 10});
		}
	});

	jQuery(".treeview").each(function(i, el) {
		if (!jQuery(el).data("treeview")) {
			jQuery(el).treeview( {
				persist : "location",
				collapsed : true,
				unique : true
			});
		}
	});

	jQuery(".temporary").each(function(i, el) {
		jQuery(el).oneTime(10000, function() {
			jQuery(this).hide();
		});
	});

	jQuery(".message").each(function(i, el) {
		if (!jQuery(el).data("dialog")) {
			jQuery(el).dialog( {
				width : 900,
				height : 200,
				title : jQuery(el).attr('window'),
				modal : true,
				draggable : true,
				resizable : true
			});
		}
		jQuery(el).dialog('open');
	});

	jQuery(".accordion").each(function(i, el) {
		if (!jQuery(el).data("accordion")) {
			jQuery(el).accordion( {
				autoHeight : false,
				clearStyle : true,
				animated : false
			});
		}
	});

	jQuery(".progressbar").each(function(i, el) {
		if (!jQuery(el).data("progressbar")) {
			jQuery(el).progressbar( {
				value : jQuery(el).attr("value") ? jQuery(el).attr("value") : 0
			});
		}
	});

	jQuery(".slider").each(
			function(i, el) {
				if (!jQuery(el).data("slider")) {
					jQuery(el).slider(
							{
								value : jQuery(el).attr("value") ? jQuery(el)
										.attr("value") : 0,

								slide : function(event, ui) {
									jQuery("#input_" + el.id)
											.val(ui.value);
								}

							});
				}
			});

	jQuery("*.popup")
			.each(
					function(i, el) {
						jQuery(el)
								.click(
										function() {
											p_id = "#"
													+ jQuery(this).attr(
															"window");
											if (jQuery(p_id).length <= 0)
												jQuery(this)
														.after(
																"<div id='"
																		+ jQuery(
																				this)
																				.attr(
																						"window")
																		+ "' style='display:none'></div>");
											p_width = parseInt((jQuery(this)
													.attr("window_width") ? jQuery(
													this).attr("window_width")
													: 100));
											p_height = parseInt(jQuery(this)
													.attr("window_height") ? jQuery(
													this).attr("window_height")
													: 100);
											p_resizeable = (jQuery(this).attr(
													"window_resizeable") ? jQuery(
													this).attr(
													"window_resizeable") == "true"
													: true);
											p_draggable = (jQuery(this).attr(
													"window_draggable") ? jQuery(
													this).attr(
													"window_draggable") == "true"
													: true);
											p_modal = (jQuery(this).attr(
													"window_modal") ? jQuery(
													this).attr("window_modal") == "true"
													: true);
											p_title = (jQuery(this).attr(
													"window_title") ? jQuery(
													this).attr("window_title")
													: "");
											p_link = (jQuery(this).attr(
													"window_link") ? jQuery(
													this).attr("window_link")
													: '');

											this.dialog = jQuery(p_id)
													.dialog(
															{
																width : p_width,
																open : function(
																		event,
																		ui) {
																	if (p_link) {
																		jQuery(
																				p_id)
																				.load(
																						p_link)
																	}
																},
																close : function(
																		event,
																		ui) {
																	jQuery(p_id)
																			.dialog(
																					'destroy');
																},
																height : p_height,
																title : p_title,
																modal : p_modal,
																draggable : p_draggable,
																resizable : p_resizeable
															});
											this.dialog.dialog('open');
										});
					});

	jQuery("input.calendar").each(function(i, el) {
		if (!jQuery(el).data("datepicker")) {
			jQuery(el).datepicker( {
				changeMonth : true,
				changeYear : true,
				dateFormat : jQuery(el).attr('format')?jQuery(el).attr('format'):'yy-mm-dd'
			});
		}
	});

	jQuery("input.spinner").each(function(i, el) {
		if (!jQuery(el).data("spinner")) {
			jQuery(el).spinner();
		}
	});
	jQuery("div.ddsmoothmenu").each(function(i, el) {
		ddsmoothmenu.init( {
			mainmenuid : el.id,
			contentsource : "markup"
		})
	});

	jQuery("*.tabs_view").each(function(i, el) {
		if (!jQuery(el).data("tabs")) {
			options = {
				select : function(event, ui) {				
					jQuery(ui.panel).addClass('div-ajax-loader');
				},
				load : function(event, ui) {
					jQuery(ui.panel).removeClass('div-ajax-loader');
				},
				create : function(event, ui) {
					jQuery(el).show();
				},
				ajaxOptions : {
					async : true
				}
			};
			if (jQuery(el).attr("select"))
				options.selected = parseInt(jQuery(el).attr("select"));
			jQuery(el).tabs(options);
		}
	});

	jQuery("*.slideshow").each(function(i, el) {
		jQuery(el).carousel( {
			hide : 'fadeOut', // jquery show / hide effect method name
			show : 'fadeIn', // jquery show / hide effect method name
			duration : 500, // duration of wait in milliseconds
			speed : 2000, // speed in milliseconds, 'slow' or 'fast',
			seed : 5
		// length of random classname applied to item to keep things all nice
				// and seperate
				// warning: setting this to something ridiculous will make it
				// take ages to load.
				});
		jQuery(el).show();
	});

	jQuery("*.expander")
			.each(
					function(i, el) {
						jQuery(el)
								.click(
										function() {
											jQuery(this).toggleClass(
													'minimized');
											jQuery(this).toggleClass(
													'maximized');
											if (jQuery(this).attr("expand")
													&& jQuery(
															"#"
																	+ jQuery(
																			this)
																			.attr(
																					"expand"))
															.get() != "") {
												if (jQuery(
														"#"
																+ jQuery(this)
																		.attr(
																				"expand"))
														.is(':visible'))
													jQuery(
															"#"
																	+ jQuery(
																			this)
																			.attr(
																					"expand"))
															.hide();
												else
													jQuery(
															"#"
																	+ jQuery(
																			this)
																			.attr(
																					"expand"))
															.show();
											} else {
												if (jQuery(this).next().is(
														':visible'))
													jQuery(jQuery(this)).next()
															.hide();
												else
													jQuery(jQuery(this)).next()
															.show();
											}
										});
					});

	jQuery("[hint]").each(function(i, el) {		
			if (!jQuery(el).data("tipTip")) {
				jQuery(el).tipTip({activation:'focus',defaultPosition:'right',attribute:'hint'});
			}		
	});
	
	if(jQuery().uniform){		
		jQuery("select, input, textarea").each(function(i,el){
			if (!jQuery(el).data("uniform") && jQuery(el).css('opacity') != '0')
				jQuery(el).uniform();
			
		});
		jQuery('select').each(
			function(i,el){
				jQuery(el).parent().width(jQuery(el).width()-10);
			}
		);
	}
	
	if(typeof admin_InitJs=='function')
		admin_InitJs();
}

// tooltip init
jQuery(document).ready(function() {
	InitJs();
	jQuery(document).ajaxSuccess(function() {
		InitJs();
	})
});

function show_loader(where) {
	if (jQuery(where).length) {
		w = jQuery(where).width();
		h = jQuery(where).height();
		jQuery(where).prepend('<div class="div-ajax-loader"></div>')
		jQuery(where).children('.div-ajax-loader').css( {
			position : 'absolute',
			'z-index' : '20000000',
			'text-align' : 'center',
			opacity : 0.8
		}).width(w).height(h);
	}
}

function ajax_load(url, pars, where, evalonsuccess,cache,method) {
	show_loader(where);
	if(cache === undefined)
		cache=false;
	if(method === undefined)
		method='GET';
	jQuery.ajax( {
		url : url,
		data : pars,
		cache : cache,
		type : method,
		success : function(response) {
			if (where != "") {
				jQuery(where).html(response);
			}
			eval(evalonsuccess);
		}
	});
}

function go_to(url) {
	location.href = url;
	return false;
}

function edit(url, id) {
	go_to(url + "?a=edit:" + id);
}

function del(url) {
	if (confirm("Sunteti sigur ca doriti sa stergeti?")) {
		go_to(url);
	}
}

function cnf(message) {
	return confirm(message);
}

function form_check_all(id, elem) {
	jQuery("*:checkbox").each(function(i, el) {
		el.checked = elem.checked;
	});
}

function check_all(form_id) {
	jQuery("#" + form_id + " input:checkbox").each(function(i, el) {
		el.checked = true;
	});
}

function uncheck_all(form_id) {
	jQuery("#" + form_id + " input:checkbox").each(function(i, el) {
		el.checked = false;
	});
}

function getProgress(id) {
	jQuery.getJSON(root + "?progress_key=" + jQuery("#" + id).attr('key'), "",
			function(data) {
				procent = parseInt((data.current * 100 / data.total));
				jQuery("#" + id + "> .bar").width(procent + "%");
				jQuery("#" + id + "> .bar").html(procent + "%");
				jQuery("#" + id + "> .downbar> .speed").html(
						"Av. Speed: " + parseInt(data.speed / 1024) + "Kb/s");
				jQuery("#" + id + "> .downbar> .status").html(
						"Uploaded/Total:" + parseInt(data.current / 1024)
								+ "Kb/" + parseInt(data.total / 1024) + "Kb");
				if (procent < 100)
					setTimeout("getProgress('" + id + "')", 1000);
			});
}

function startProgress(id) {
	jQuery("#" + id).addClass('up_progress').show();
	jQuery("#" + id).append("<div class='bar'></div>");
	jQuery("#" + id)
			.append(
					"<div class='downbar'><div class='speed'></div><div class='status'></div></div>");
	jQuery("#" + id).append("<div class='clear'></div>");
	setTimeout("getProgress('" + id + "')", 1000);
}

function launchwin(winurl, winname, winfeatures) {
	var newwin = window.open(winurl, winname, winfeatures);
	newwin.focus();
	return newwin;
}

function addslashes(str) 
{
    str = str.replace(/\'/g,'\\\'');
    str = str.replace(/\"/g,'\\"');
    str = str.replace(/\\/g,'\\\\');
    str = str.replace(/\0/g,'\\0');
    return str;
};

function start_editor(type,id,baseurl)
{
	switch(type)
	{
		case "fckeditor":
			oFCKeditor = new FCKeditor( id ) ;
			oFCKeditor.BasePath = baseurl ;
			oFCKeditor.ReplaceTextarea() ;
		break;
		case "ckeditor":
			CKEDITOR.replace( id,
			{
				fullPage:true,
			 filebrowserBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html',
			 filebrowserImageBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html?Type=Images',
			 filebrowserFlashBrowseUrl : root+'objects/filemanagers/ckfinder/ckfinder.html?Type=Flash',
			 filebrowserUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			 filebrowserImageUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			 filebrowserFlashUploadUrl : root+'objects/filemanagers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			});

		break;
		case "openwysiwyg":
		break;
		default:
	}
	
}

