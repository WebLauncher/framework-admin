/**
 * @author Mihai Varga
 */
(function($) {
	$.widget("ui.gridview", {

		_init : function() {
			if(this.options.use_cookie)
				this._load_settings();
			this.element.css({
				"clear" : "both"
			}).wrap("<div></div>").parent().addClass("ui-widget ui-gridview");

			this.element.append("<thead></thead>");
			this.element.append("<tbody></tbody>");

			this.gridtable = {
				obj : this,
				header : "",
				pages : "",
				rows : "",
				loader : "",
				search : ""
			};

			var self = this, index;
			this.element.after("<div class='ui-widget-content ui-gridview-pages'></div>");
			this.gridtable.pages = this.element.next();

			this._show_pages();

			this.element.before("<div class='ui-gridview-loader'><div class='ui-gridview-loader-text'>Loading...</div></div>");
			this.gridtable.loader = this.element.prev()[this.element.prev().size() - 1];
			$(this.gridtable.loader).hide();

			this._show_search()._show_settings()._refresh();
		},
		_load_settings:function(){
			if(JSON.parse(this._get_cookie()))
				this.options=JSON.parse(this._get_cookie());
		},
		_displayLoader : function() {
			var self = this, index;
			fl_width = this.element.parent(".ui-gridview").children('table').first().innerWidth();
			fl_height = this.element.parent(".ui-gridview").children('table').first().innerHeight();
			fl_top = $(self.gridtable.header).outerHeight() + (this.element.parent(".ui-gridview").children('table').first().outerHeight() - fl_height);
			fl_left = (this.element.parent(".ui-gridview").width() - fl_width) / 2;
			$(this.gridtable.loader).width(fl_width).height(fl_height).css("line-height", fl_height + "px").css('margin-top', fl_top + 'px').css('margin-left', fl_left + 'px').css('position', 'absolute');
			$(this.gridtable.loader).show();
			return this;
		},
		_calcPages : function() {
			this.options.no_pages = Math.floor(this.options.no_results / this.options.no_rows);
			if (this.options.no_results % this.options.no_rows != 0)
				this.options.no_pages++;
			return this;
		},
		_hideLoader : function() {
			this.element.parent(".ui-gridview").children().show();
			$(this.gridtable.loader).hide();

			return this;
		},
		_refresh : function() {
			this._displayLoader();
			var self = this, index;
			kwd_cond = '';
			kwd = $('#gridview_kwd').val();
			if (kwd != '')
				kwd_cond = "kwd=\"" + kwd + "\"&";
			$.ajax({
				url : this.options.url,
				data : kwd_cond + "sortBy=" + (this.options.sortby ? this.options.sortby : "") + "&sortAscending=" + (this.options.sortdir > 0 ? "true" : "false") + "&numberOfRows=" + this.options.no_rows + "&startIndex=" + (this.options.current_page * this.options.no_rows),
				cache : false,
				dataType : this.options.data_type,
				success : function(response) {
					self._display(response)._show_pages()._hideLoader();
				}
			});

			return this;
		},
		_display_json : function(content) {
			var self = this, index;
			this.element.children("tbody").html("");

			this._display_json_header(content.header);

			for ( i = 0; i < this.options.no_rows; i++) {
				tclass = "ui-gridview-row";
				if ((i % 2))
					tclass += " ui-gridview-row-alt";
				this.element.children("tbody").append("<tr class='" + tclass + "'></tr>");
				for ( j = 0; j < this.options.no_cols; j++) {
					this.element.children("tbody").children().eq(i).append("<td></td>");
				}
			}

			this.element.children("tbody")
			this.gridtable.rows = this.element.children("tbody").children("tr");
			if (self.options.rowclick != '')
				this.gridtable.rows.each(function(i, el) {
					$(el).click(function() {
						if ( typeof self.options.rowclick == 'function')
							self.options.rowclick(this);
					});
				});
			this.options.no_results = content.results;

			if (content.data) {
				for ( i = 0; i < content.data.length; i++) {
					if (this.gridtable.rows.size() > i) {
						if (content.data[i]) {
							for ( j = 0; j < content.header.length; j++) {
								if ($($(this.gridtable.rows[i]).children("td")[j]))
									$($(this.gridtable.rows[i]).children("td")[j]).html(content.data[i][content.header[j].name]);
							}
						} else {
							for ( j = 0; j < this.gridtable.header.size(); j++) {
								if ($($(this.gridtable.rows[i]).children("td")[j]))
									$(this.gridtable.rows[i]).hide();
							}
						}
					}
				}
				if (content.data.length < this.gridtable.rows.size()) {
					for ( i = content.data.length; i < this.gridtable.rows.size(); i++)
						for ( j = 0; j < this.gridtable.header.size(); j++)
							if ($($(this.gridtable.rows[i]).children("td")[j]))
								$(this.gridtable.rows[i]).hide();
				}
			}
			delete content;
			return this;
		},
		_display : function(content) {
			switch(this.options.data_type) {
				case 'text':
					this._display_text(content);
					break;
				case 'json':
					this._display_json(content);
					break;
				case 'xml':
					this._display_xml(content);
					break;
			}
			return this;
		},
		_display_json_header : function(content) {
			cols = content;
			$(this.element.children("thead")[0]).html('');
			$(this.element.children("thead")[0]).append('<tr></tr>')
			this.options.no_cols = cols.length;
			for (var i = 0; i < cols.length; i++) {
				coll = cols[i];
				if (coll.sorted) {
					html = "<td class='ui-gridview-header-col " + ((coll.sorted == 0) ? "ui-gridview-header-col-desc" : "ui-gridview-header-col-asc") + "' sortby='" + coll.name + "' sortdir='" + ((coll.sorted == 0) ? "-1" : "1") + "'><span class='title'>" + coll.label + "</span><span class='ui-icon " + ((coll.sorted == 0) ? "ui-icon-arrowthick-1-n" : "ui-icon-arrowthick-1-s") + "'></span></td>";
				} else
					html = "<td class='ui-gridview-header-col' " + (coll.sort == 1 ? " sortby='" + coll.name + "' sortdir='0'" : "") + "><span class='title'>" + coll.label + "</span>" + (coll.sort == 1 ? "<span class='ui-icon ui-icon-arrowthick-2-n-s'></span>" : "") + "</td>";
				$(this.element.children("thead").children("tr")[0]).append(html);
			}
			this.element.find('thead td .ui-icon-arrowthick-2-n-s').parent('td').hover(function() {
				$(this).find('.ui-icon').show();
			}, function() {
				$(this).find('.ui-icon').hide();
			});
			var self = this, index;
			this.gridtable.cols=cols;
			this.gridtable.header = $(this.element.children("thead").children("tr")[0]).children();
			this.options.no_cols = this.gridtable.header.length;
			this.gridtable.header.css({
				"cursor" : 'pointer',
				'width' : Math.round(this.element.width() / this.options.no_cols) + 'px'
			}).addClass("ui-gridview-header-col").click(function() {
				if ($(this).attr("sortby")) {
					self.options.sortby = $(this).attr("sortby");
					if ($(this).attr("sortdir") <= 0) {
						self.options.sortdir = 1;
					} else {
						self.options.sortdir = -1;
					}
					self._refresh();
				}
			});
		},
		_parse_text_to_json : function(content) {
			ret = {};
			ret.header = [];
			list = content.split("|||");
			colls = list[0].split('###');
			for (var i = 0; i < colls.length; i++) {
				coll = colls[i].split("---");
				ret.header[i] = {
					label : jQuery.trim(coll[0]),
					name : jQuery.trim(coll[1]),
					sort : jQuery.trim(coll[2]),
					sorted : jQuery.trim(coll[3])
				};
			}

			last = list[list.length - 1];
			if (last != "") {
				last_a = last.split("###");
				if (last_a.length == 1)
					ret.results = $.trim(last);
			}
			list.splice(0, 1);
			ret.data = [];
			for ( i = 0; i < list.length; i++) {
				if (list[i]) {
					row = list[i].split("###");
					if (row.length == ret.header.length) {
						new_obj = {};
						for ( j = 0; j < ret.header.length; j++)
							new_obj[ret.header[j].name] = jQuery.trim(row[j]);
						ret.data[i] = new_obj;
					}
				}
			}
			delete list;
			delete content;
			return ret;
		},
		_parse_xml_to_json : function(content) {
			ret = {};
			ret.header = [];
			colls = $(content).find('table colls coll');
			for ( i = 0; i < colls.length; i++) {
				ret.header[i] = {
					label : $(colls[i]).attr('label'),
					name : $(colls[i]).attr('name'),
					sort : $(colls[i]).attr('sord'),
					sorted : $(colls[i]).attr('sord_dir')
				};
			}
			if ($(content).find('table data').length) {
				ret.data = [];
				ret.results = $(content).find('table data').attr('total');
				rows = $(content).find('table data row');
				for ( i = 0; i < rows.length; i++) {
					ret.data[i] = {};
					for ( j = 0; j < ret.header.length; j++)
						ret.data[i][ret.header[j].name] = $(rows[i]).find(ret.header[j].name).text();
				}
			}
			return ret;
		},
		_display_text : function(content) {
			return this._display_json(this._parse_text_to_json(content));
		},
		_display_xml : function(content) {
			return this._display_json(this._parse_xml_to_json(content));
		},
		_show_pages : function() {

			this._calcPages();

			$(this.gridtable.pages).html("").css({
				"clear" : "both"
			});
			if (this.options.no_pages > 1) {
				if (this.options.current_page > this.options.max_pages / 2 - 1) {
					$(this.gridtable.pages).append("<a class='ui-state-default ui-corner-all ui-gridview-page' page='" + 0 + "'>|<</a>").append("<a class='ui-state-default ui-corner-all ui-gridview-page' page='" + (this.options.current_page - 1) + "'><</a>");
				}

				var start = Math.floor(this.options.current_page - this.options.max_pages / 2 + 1) >= 0 ? Math.floor(this.options.current_page - this.options.max_pages / 2 + 1) : 0;
				var end = Math.floor(this.options.current_page + this.options.max_pages / 2) <= this.options.no_pages ? Math.floor(this.options.current_page + this.options.max_pages / 2) : this.options.no_pages;
				end = end + (this.options.max_pages - (end - start));
				if (end > this.options.no_pages) {
					end = this.options.no_pages;
					start = start - (this.options.max_pages - (end - start));
				}
				if (start < 0)
					start = 0;

				for ( i = start; i < end; i++) {
					cls = "class='ui-state-default ui-corner-all ui-gridview-page'";
					if (i == this.options.current_page)
						cls = "class='ui-state-active ui-corner-all ui-gridview-page-selected'";
					htmlitem = "<a " + cls + " page='" + i + "'>" + (i + 1) + "</a>";
					$(this.gridtable.pages).append(htmlitem);
				}

				if (end < this.options.no_pages) {
					$(this.gridtable.pages).append("<a class='ui-state-default ui-corner-all ui-gridview-page' page='" + (this.options.current_page + 1) + "'>></a>").append("<a class='ui-state-default ui-corner-all ui-gridview-page' page='" + (this.options.no_pages - 1) + "'>>|</a>");
				}
				var self = this, index;
				$(this.gridtable.pages).children().click(function() {
					self.options.current_page = parseInt($(this).attr("page"));
					self._refresh();
				}).mouseover(function() {
					$(this).toggleClass("ui-state-hover");
				}).mouseout(function() {
					$(this).toggleClass("ui-state-hover");
				});
			}
			to = ((this.options.current_page * this.options.no_rows + this.options.no_rows < this.options.no_results) ? this.options.current_page * parseInt(this.options.no_rows) + parseInt(this.options.no_rows) : this.options.no_results);
			if (to > this.options.no_results)
				to = this.options.no_results;
			$(this.gridtable.pages).append("<div class='ui-gridview-result'>" + this.options.text_pages_result + (this.options.current_page * this.options.no_rows + 1) + " " + this.options.text_pages_to + " " + to + " " + this.options.text_pages_of + " " + this.options.no_results + "</div>").css({
				"clear" : "both"
			}).addClass("clearfix");

			return this;
		},
		_show_search : function() {
			this.element.before("<div class='ui-widget-content ui-gridview-top'></div>").prev().css({
				"display" : "block"
			}).append("<div class='ui-gridview-search'>" + this.options.text_search + "</div>").children().append("<span class='ui-icon ui-icon-search'></span><input type='text' class='ui-widget-content ui-gridview-search-input' name='gridview_kwd' id='gridview_kwd'/><label>").css({
				"float" : "left"
			});

			var self = this, index;
			$("#gridview_kwd_word").parent().click(function() {
				if ($("#gridview_kwd").val() != "")
					self._refresh();
			});
			$("#gridview_kwd").css("margin", 2).keyup(function(e) {
				if ($("#gridview_kwd").val().length > 3 || $("#gridview_kwd").val().length == 0) {
					self.options.current_page = 0;
					self._refresh();
					$("#gridview_kwd").focus();
				}
				if (e.which == 13) {
					self.options.current_page = 0;
					self._refresh();
				}
			});
			return this;
		},
		_show_settings_dialog : function() {
			if (!$('#gridview_dialog_settings').length)
				$('body').append('<div id="gridview_dialog_settings"></div>');
			$('#gridview_dialog_settings').empty();
			var self = this, index;
			var dialog_buttons = {};
			dialog_buttons =
				[
				
				{
					text:this.options.text_settings_dialog_cancel,
					click:function() {
						$('#gridview_dialog_settings').dialog('destroy');
					},
					class:'ui-gridview-negative'
				},
				{
					text:this.options.text_settings_dialog_save,
					click:function() {
						self._save_settings();
						$('#gridview_dialog_settings').dialog('destroy');
					},
					class:'ui-gridview-positive'
				}
				]
			; 
			$('#gridview_dialog_settings').dialog({
				title : this.options.text_settings_dialog_title,
				modal : true,
				resizable:false,
				buttons : dialog_buttons
			});
			var select_rows_text = '<select name="gridview_no_rows">';
			for (var i = 0; i < this.options.no_rows_values.length; i++) {
				select_rows_text += '<option value="' + this.options.no_rows_values[i] + '"' + (this.options.no_rows_values[i] == this.options.no_rows ? ' selected="selected"' : '') + '>' + this.options.no_rows_values[i] + '</option>';
			}
			select_rows_text += '</select>';
			$('#gridview_dialog_settings').append('<label>' + this.options.text_settings_dialog_no_rows + ':</label>' + select_rows_text+'<br/>');
			
			var select_sortcol_text = '<select name="gridview_sort_coll">';
			for (var i = 0; i < this.gridtable.cols.length; i++) {
				if(this.gridtable.cols[i].sort)
					select_sortcol_text += '<option value="' + this.gridtable.cols[i].name + '"' + (this.gridtable.cols[i].name == this.options.sortby ? ' selected="selected"' : '') + '>' + this.gridtable.cols[i].label + '</option>';
			}
			select_sortcol_text += '</select>';
			$('#gridview_dialog_settings').append('<label>' + this.options.text_settings_dialog_sort_col + ':</label>' + select_sortcol_text+'<br/>');
			
			var select_sortdir_text = '<select name="gridview_sort_dir"><option value="0"'+(this.options.sortdir>0?' selected="selected"':'')+'>Ascending</option><option value="0"'+(this.options.sortdir<=0?' selected="selected"':'')+'>Descending</option></select><br/>';			
			$('#gridview_dialog_settings').append('<label>' + this.options.text_settings_dialog_sort_dir + ':</label>' + select_sortdir_text);
		},
		_save_settings : function() {
			this.options.no_rows = $('select[name="gridview_no_rows"]').val();
			this.options.sortby = $('select[name="gridview_sort_coll"]').val();
			this.options.sortdir = $('select[name="gridview_sort_dir"]').val();						
			
			// save cookie
			if(this.options.use_cookie){
				var value=JSON.stringify(this.options);
				var exdate=new Date();
				exdate.setDate(exdate.getDate() + 365);
				var c_name=this.element.attr('id')+'_table_settings';
				var c_value=escape(value) + "; expires="+exdate.toUTCString();
				document.cookie=c_name + "=" + c_value;
			}
			this._refresh();
		},
		_get_cookie:function(){
			var c_name=this.element.attr('id')+'_table_settings';
			var c_value = document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1)
			  {
			  c_start = c_value.indexOf(c_name + "=");
			  }
			if (c_start == -1)
			  {
			  c_value = null;
			  }
			else
			  {
			  c_start = c_value.indexOf("=", c_start) + 1;
			  var c_end = c_value.indexOf(";", c_start);
			  if (c_end == -1)
			  {
			c_end = c_value.length;
			}
			c_value = unescape(c_value.substring(c_start,c_end));
			}
			return c_value;			
		},
		_show_settings : function() {
			var self = this, index;
			this.element.prev().append("<div class='ui-gridview-settings'></div>").children(".ui-gridview-settings").append('<button type="buttton"><span class="ui-icon ui-icon-wrench"></span></button>').css({
				"float" : "right"
			}).click(function() {
				self._show_settings_dialog();
			});
			this.element.prev().append("<div class='ui-gridview-refresh'></div>").children(".ui-gridview-refresh").append('<button type="buttton"><span class="ui-icon ui-icon-arrowrefresh-1-s"></span></button>').css({
				"float" : "right"
			}).click(function() {
				self._refresh();
			});
			$("#gridview_norows").css("margin", 2).keyup(function(e) {
				if (e.which == 13) {
					self.options.current_page = 0;
					self.options.no_rows = $("#gridview_norows").val();
					self._refresh();
				}
			});
			return this;
		},
		refresh : function() {
			this._refresh();
		},
		destroy : function() {

			this.valueDiv.remove();

			$.widget.prototype.destroy.apply(this, arguments);

		},
		options : {
			use_cookie:true,
			url : '',
			no_rows : 10,
			no_cols : 10,
			no_results : 1,
			current_page : 1,
			max_pages : 10,
			data_type : 'text',
			rowclick : '',
			no_rows_values : [10, 20, 50, 100, 200],
			text_search : 'Cauta:',
			text_search_portion : ' ca si portiune de text?',
			text_no_rows : 'Nr randuri/pagina:',
			text_pages_result : 'Rezultate: ',
			text_pages_to : 'pana la',
			text_pages_of : 'din',
			text_settings_dialog_title : 'Table Settings',
			text_settings_dialog_save : 'Save',
			text_settings_dialog_cancel : 'Cancel',
			text_settings_dialog_no_rows : 'No rows',
			text_settings_dialog_sort_col : 'Sort by',
			text_settings_dialog_sort_dir : 'Sort direction'
		}
	});

	$.extend($.ui.gridview, {
		version : "1.7"
	});

})(jQuery);
