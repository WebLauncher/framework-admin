/**
 * @author Mihai Varga
 */
(function($) {
$.widget("ui.listfilter", {

	_init: function() {
		this.element.before("<div class='ui-listfilter-search'></div>");
		this.element.prev().append("<input type='text' class='ui-widget-content ui-listfilter-search-input' name='listfilter_kwd' id='listfilter_kwd'/>");
		var self=this,index;
		$("#listfilter_kwd").width($("#listfilter_kwd").parent().width()-5);
		$("#listfilter_kwd").focus();
		$("#listfilter_kwd")
			.css("margin",2)
			.keyup(			
			function(e){
				self._search();				
			}			
		);
		this.element.before("<div class='ui-listfilter-links'><a class='ui-listfilter-link_expandall'>"+this.options.text_expandall+"</a><a class='ui-listfilter-link_colapseall'>"+this.options.text_colapseall+"</a></div>");
		this.element.prev().children("a.ui-listfilter-link_expandall").click(function(){
			self._expandAll()
		});
		this.element.prev().children("a.ui-listfilter-link_colapseall").click(function(){
			self._colapseAll()
		});
		this.element.children("li").children("ul").before("<a class='ui-listfilter-btn_expand'>+</a>")		
		this._hideAll();
		this._show_selected();
	},
	
	_show_selected:function(){
		this.element.children("li").children("a.selected").next('a').trigger("click");		
	},
	
	_hideAll: function(){
		this.element.children("li").children("ul").hide();
		this._showExpanders();		
	},
	
	_show_top_btns:function()
	{
		this.element.prev('.ui-listfilter-links').show();
	},
	
	_hide_top_btns:function()
	{	
		this.element.prev('.ui-listfilter-links').hide();
	},
	
	_showExpanders: function(){
		this.element.children("li").children("a.ui-listfilter-btn_expand").show().attr("show",'').toggle(function(){
			$(this)
				.attr("show",1)				
				.html("-")
				.next("ul").show();
		}, function(){
			$(this).attr("show",'').html("+").next("ul").hide();
		});		
	},
	
	_hideExpanders: function(){
		this.element.children("li").children("a.ui-listfilter-btn_expand").hide();
	},
	
	_showAll:function()
	{
		this.element.children("li").children("ul").show();		
	},
	
	_expandAll:function()
	{			
		this.element.children("li").children("a.ui-listfilter-btn_expand[show!=1]").trigger("click");
	},
	
	_colapseAll:function()
	{			
		this.element.children("li").children("a.ui-listfilter-btn_expand[show=1]").trigger("click");
	},
	
	_search:function()
	{
		jQuery.expr[':'].contains = function(a,i,m){
		    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
		};
		kwd=$("#listfilter_kwd").val();
		if (kwd != "") {
			this._hide_top_btns();
			this._hideExpanders();
			this.element.contents().filter(function(){
				return this.nodeType == 1
			}).each(function(i, el){
				$(el).hide();
			});
			$("#" + this.element.attr("id") + " :contains('" + kwd + "')").filter(function(){
				return this.nodeType == 1
			}).each(function(i, el){
				$(el).show();
				$(el).children("li").each(function(i, el){
					$(el).hide();
				});
			});
		}
		else {
			this._show_top_btns();
			this._showExpanders();			
			$("#" + this.element.attr("id") + " :hidden").show();
			this._expandAll();
			this._colapseAll();
			this._show_selected();
		}
	},	
	
	destroy: function() {		
		$.widget.prototype.destroy.apply(this, arguments);
	}
});

$.extend($.ui.listfilter, {
	version: "1.7",
	defaults: {		
		text_expandall:"Expand all",
		text_colapseall:"Colapse all"
	}
});
})(jQuery);
