
					<div class="clearfix box" id="field_id_{$field.name}">						
					</div>					
									
					<script type="text/javascript">
						var input='{$field.name}';
						{literal}
						/**
						 * @author Mihai Varga
						 */
						(function($) {
						$.widget("ui.listpicker", {
						
							_init: function() {
								this.listpicker={
									listpicker:this,
									actions:'',
									add_btn:"",
									dialog_div:"",
									selected_items:[],									
									items:[],
									loader:"",
									search:""
								};	
								var self=this,index;
								
								this.element.wrap("<div id='wrap_"+this.element.attr('id')+"' class='ui-listpicker'/>");
								// init selected values											
								this.listpicker.selected_items=this.options.selected_items;	
								this._init_selected();								
								// init dialog div
								this.element.after("<div id='dialog_"+this.element.attr('id')+"' class='ui-listpicker-dialog'></div>");
								this.listpicker.dialog_div=this.element.next();
								this.listpicker.dialog_div.hide();
								this._init_dialog_div();
								// init add more link
								this.element.before('<div class="ui-listpicker-actions clearfix"></div>');								
								this.listpicker.actions=this.element.prev('div');
								this.listpicker.actions.append("<a href='#' class='ui-listpicker-add-link'><b class='icon icon-add' title='"+this.options.text_add_more_title+"'></b>"+this.options.text_add_more+"</a>");
								this.listpicker.add_btn=this.listpicker.actions.children('a');
								this.listpicker.add_btn.click(function(){self._add_more();});
							},
							
							_init_selected:function()
							{
								var self=this,index;
								this.element.empty();	
								if(this.listpicker.selected_items.length)
								{															
									for(var i=0;i<this.listpicker.selected_items.length;i++)
										if(this._get_item(this.listpicker.selected_items[i].id))
										{	
											var obj=this._get_item(this.listpicker.selected_items[i].id);																		
											this.element.append('<div class="ui-listpicker-selected"><a href="#" title="'+this.options.text_remove_item+'"><b class="icon icon-delete"></b></a><input type="hidden" name="'+this.options.field+'['+obj.id+']" value="'+obj.id+'"/>'+obj.value+'</div>');
										}
									this.element.find('.ui-listpicker-selected').css('float','left');
									this.element.find('.ui-listpicker-selected a').click(function(){
										self._remove_item($(this).next().val());
									});
								}
								else
								{
									this.element.html(this.options.text_no_selected);
								}
							},
							
							_remove_item:function(id){
								var rem_index=0;
								for(var i=0;i<this.listpicker.selected_items.length;i++)
									if(this.listpicker.selected_items[i].id==id)
										rem_index=i;
								this.listpicker.selected_items.splice(rem_index,1);
								this._refresh_list();
							},
							
							_init_dialog_div:function()
							{
								var self=this,index;
								this.listpicker.dialog_div.empty();
								this.listpicker.dialog_div.append('<div class="ui-listpicker-search"><input type="text" name="'+this.options.field+'_search"/></div>');
								this.listpicker.dialog_div.find('.ui-listpicker-search input').keyup(function(){
									self._search($(this).val());
								});															
								this.listpicker.dialog_div.append('<div class="ui-listpicker-dialog-content"><table width="100%" class="ui-listpicker-dialog-table"></table></div>');
								for(var i=0;i<this.options.items.length;i++)
								{
									if(this._not_selected(this.options.items[i].id))
										$(this.listpicker.dialog_div.find('table')).append('<tr><td><label><input type="checkbox" value="'+this.options.items[i].id+'" text="'+this.options.items[i].value+'"/>'+this.options.items[i].value+'</label></td></tr>');
								}																
							},
							
							_refresh_list:function()
							{
								this._init_selected();
								this._init_dialog_div();								
							},
							
							_add_more:function()
							{
								var self=this,index;
								this.listpicker.dialog_div.dialog(
								{
									title:this.options.text_window_title,
										width:400,
										height:400,
										modal:true,
										close:function(){self.listpicker.dialog_div.dialog('destroy');},
										buttons:{
											'Cancel':function(){self.listpicker.dialog_div.dialog('destroy');},
											'Select checked':function(){
												self._add_items();
												self.listpicker.dialog_div.dialog('destroy');
											}										
										}
									}
								).show();
							},
							
							_get_item:function(id)
							{
								for(var i=0;i<this.options.items.length;i++)
								{
									if(this.options.items[i].id==id)
									{
										return this.options.items[i];
									}
								}
								return false;
							},
							
							_add_items:function(){
								var self=this,index;
								this.listpicker.dialog_div.find('input:checked').each(function(i){
									if(self._not_selected($(this).val()))
										self.listpicker.selected_items.push({id:$(this).val(),value:$(this).attr('text')});
								});																
								this._refresh_list();
							},
							
							_not_selected:function(id){
								for(var i=0;i<this.listpicker.selected_items.length;i++)
									if(this.listpicker.selected_items[i].id==id)
									{
										return false;
									}								
								return true;
							},
							
							_search:function(kwd)
							{
								jQuery.expr[':'].contains = function(a,i,m){
								    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
								};
								if (kwd != "") {
									
									this.listpicker.dialog_div.find('table').contents().filter(function(){
										return this.nodeType == 1
									}).each(function(i, el){
										$(el).hide();
									});
									this.listpicker.dialog_div.find('table').find(" :contains('" + kwd + "')").filter(function(){
										return this.nodeType == 1
									}).each(function(i, el){
										$(el).show();
										$(el).children("tr").each(function(i, el){
											$(el).hide();
										});
									});
								}
								else {												
									this.listpicker.dialog_div.find('table').find(" :hidden").show();									
								}
							},	
							
							destroy: function() {		
								$.widget.prototype.destroy.apply(this, arguments);
							}
						});
						
						$.extend($.ui.listpicker, {
							version: "1.7",
							defaults: {		
								dialog_div:'',
								items:[],
								field:'not_defined',
								selected_items:[],
								text_add_more:"",
								text_add_more_title:"Add more items",
								text_window_title:"Choose items",
								text_btn_select:'Select',
								text_btn_cancel:'Cancel',
								text_remove_item:'Remove item',
								text_no_selected:'No items selected.'
							}
						});
						})(jQuery);
						{/literal}
						$('#field_id_{$field.name}').listpicker(
							{literal}{{/literal}
							field:input,
							items:[
								{foreach item=row from=$field.options name=listpicker}
								{literal}{{/literal}id:'{$row[$field.options_id]}',value:'{$row[$field.options_value]|escape}'{literal}}{/literal}
								{if !$smarty.foreach.listpicker.last},{/if}
								{/foreach}
							],
							selected_items:[
								{foreach item=row from=$field.value name=listpicker}								
								{literal}{{/literal}id:'{if $field.value_id}{$row[$field.value_id]}{else}{$row}{/if}'{literal}}{/literal}
								{if !$smarty.foreach.listpicker.last}
								,
								{/if}
								{/foreach}
							]
							{literal}}{/literal}							
						);									
					</script>
