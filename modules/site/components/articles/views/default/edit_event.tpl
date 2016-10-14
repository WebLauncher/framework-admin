<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save:{$article.id}" />
	<input type="hidden" name="type" value="{$p.session.articles_type}" />
	<div class="form_wrap" id="form_texte_form">
		<fieldset class="formview panel clearfix">
			<div class="floatleft">
				{if $parent_folder.others.has_payment}
				<a href="{$root}admin/participants/?event={$article.id}" class="button"><b class="icon icon-user_silhouette"></b>Participants</a>
				<a href="{$root}admin/participants/?a=add:{$article.id}&event={$article.id}" class="button"><b class="icon icon-plus"></b>Add participant</a>
				<button type="button"  onclick="go_to('{$root}admin/participants/?a=import:{$event.id}')"><b class="icon icon-page_white_get"></b>{tr tags="buttons"}Import participants{/tr}</button>
				{/if}
			</div>
			<div class="floatright">
				{if $parent_folder.others.has_payment}				
				<button type="button" onclick="go_to('{$root}admin/participants/?a=send_email:{$article.id}')"><b class="icon icon-email"></b>{tr tags="buttons"}E-mail participants{/tr}</button>
				<button type="button" onclick="go_to('{$root}admin/participants/?a=export:{$article.id}')"><b class="icon icon-drive_disk"></b>{tr tags="buttons"}Export participants{/tr}</button>
				{/if}
			</div>
		</fieldset>
		<div id="acordeon_texte_form" class="clearfix">
		<div class="floatleft" style="width:70%;">
			<fieldset class="formview panel">
				<div class="clearfix title">				
					<small>Event details</small>
				</div>			
					<table cellspacing="0" cellspacing="0" border="0">
						
						<tr>
							<td colspan="2"><label for="_title">Name:*</label><br/><input type="text" name="_title" value="{$article.title}" class="text" style="width:99%"/>
								{validator form="texte_form" field="_title" rule="required" message="Please fill in a name!"}
							</td>
						</tr>						
						<tr>
							<td colspan="2">
								<label>Description:</label><br/>														
								<script type="text/javascript" src="{$root}objects/editors/ckeditor/ckeditor.js"></script>
								<textarea id="_content" name="_content" style="width:98%;height:100px;">{$article.content}</textarea>
								<script type="text/javascript">
									start_editor('ckeditor','_content','');
								</script>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<label for="summary">Summary:</label>
								<textarea id="field_summary" name="summary" class="textarea">{$article.summary}</textarea>
							</td>	
						</tr>						
					</table>
				</fieldset>
			{if $parent_folder.others.has_payment}
			<fieldset class="formview panel">
				<div class="clearfix title">
					<small>Registration Details</small>
				</div>
				<table cellspacing="0" cellspacing="0" border="0">
				<tr class='alt'>
					<th><label for="has_kids">Registration is active?</label></th>
					<td>
						<label><input type="radio" name="payment_is_active" value="0"/>No</label>
						<label><input type="radio" name="payment_is_active" value="1" checked="checked"/>Yes</label>
					</td>	
				</tr>
				<tr>
					<th><label>Main Price:</label></th>
					<td><input type="text" name="payment_main_price" class="text" value="{$parent_folder.others.main_price}"/></td>
				</tr>	
				{foreach item=d from=$category.details}
				<tr{cycle values=",class='alt'"}>
						<th><label for="{$d.name}">{$d.label}:{if $d.required}*{/if}</label></th>
						<td>
							{capture assign="detail_value"}{if $product.details[$d.name]}{$product.details[$d.name].value}{else}{$d.default_value}{/if}{/capture}
							{capture assign="detail_name"}payment_{$d.name}{/capture}
							{if $d.type=="text"}
							<input type="text" name="{$detail_name}" class="text" value="{$detail_value}"/>
							{elseif	$d.type=="date"}
							<input type="text" name="{$detail_name}" class="text calendar" value="{$detail_value}"/>
							{elseif	$d.type=="datetime"}							
							<input type="text" name="{$detail_name}_date" readonly="readonly" value="{$detail_value|date_format:'%Y-%m-%d'}" class="text calendar" style="width:60%"/> at
							<input type="text" name="{$detail_name}_time" id="{$d.name}_time" readonly="readonly" value="{$detail_value|date_format:'%H:%M'}" class="text" style="width:25%"/>		
							<script type="text/javascript">
								$('#{$d.name}_time').timepicker();
							</script>
							{elseif	$d.type=="description"}
							{include file=$p.objects.editors.ckeditor textarea_id=$detail_name textarea_name=$d.name width="98%" height="50px" textarea_value=$detail_value autostart=true}
							{elseif	$d.type=="list"}
							{/if}
							{if $d.required}
							{validator form="texte_form" field=$detail_name rule="required" message="Please fill in this field!"}
							{/if}
						</td>
				</tr>				
				{/foreach}
				</table>
			</fieldset>
			{/if}
		</div>
		<div class="floatright" style="width:30%;">
			<fieldset class="formview panel">
				<div class="clearfix title">
					<small>Parent directory</small>
				</div>
				<div class="content">
					<input type="hidden" name="parent_id" value="{$article.parent_id}"/>
									<input type="hidden" name="has_kids" value="0"/>
									<a href="{$current}?article={$parent_id}">{if !$article.parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$article.parent_id}{/bind}{/if}</a>
				</div>
			</fieldset>
			<fieldset class="formview panel">
				<div class="clearfix title">
					<small>Location</small>
				</div>
			<table cellspacing="0" cellspacing="0" border="0">				
					
					<tr>
						<th><label>Country:</label></th>
						<td>
							<select class="text" name="country" onchange="ajax_load('{$root_module}articles/?a=states:'+$('[name=\'country\']').val(),'','#stateField','');" style="width:99%;">
								{foreach item=c from=$countries}
								<option value="{$c.code}"{if $c.code==$article.others.country} selected="selected"{/if}>{$c.name}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
	        			<th><label>State:</label></th>
	        			<td id="stateField"></td>												
						<script type="text/javascript">
							ajax_load('{$root_module}articles/?a=states:'+$('[name=\'country\']').val()+':{$article.others.state}','','#stateField','');
						</script>
	        		</tr>
	        		<tr>
	        			<th><label>City:</label></th>
	        			<td><input type="text" name="city" value="{$article.others.city}" class="text" style="width:99%;"/>
						</td>
	        		</tr>
					<tr>
						<th><label for="language_id">Address:</label></th>
						<td>
							<textarea name="address">{$article.others.address}</textarea>
						</td>							
					</tr>
					<tr>
	        			<th><label>Zip:</label></th>
	        			<td><input type="text" name="zip" value="{$article.others.zip}" class="text" style="width:99%;"/>
						</td>
	        		</tr>
			</table>
			</fieldset>
			<fieldset class="formview panel">
				<div class="clearfix title">
					<small>Date and time</small>
				</div>
			<table cellspacing="0" cellspacing="0" border="0">
					<tr>
	        			<th><label>Start on:</label></th>
	        			<td><input type="text" name="start_date" readonly="readonly" value="{$article.others.start_date|date_format:'%Y-%m-%d'}" class="text calendar" style="width:60%"/> at
						<input type="text" name="start_time" id="start_time" readonly="readonly" value="{$article.others.start_date|date_format:'%H:%M'}" class="text" style="width:25%"/>		
						<script type="text/javascript">
							$('#start_time').timepicker();
						</script>						
						</td>
	        		</tr>				
					
					<tr>
	        			<th><label>End on:</label></th>
	        			<td><input type="text" name="end_date" readonly="readonly" value="{$article.others.end_date|date_format:'%Y-%m-%d'}" class="text calendar" style="width:60%"/> at
						<input type="text" name="end_time" id="end_time" readonly="readonly" value="{$article.others.end_date|date_format:'%H:%M'}" class="text" style="width:25%"/>		
						<script type="text/javascript">
							$('#end_time').timepicker();
						</script>						
						</td>
	        		</tr>
					<tr>
						<th><label>Timezone:</label></th>
						<td>
							<select name="timezone" style="width:99%;">
							      <option value="-12.0"{if $article.others.timezone=="-12.0"} selected="selected"{/if}>(GMT -12:00) Eniwetok, Kwajalein</option>
							      <option value="-11.0"{if $article.others.timezone=="-11.0"} selected="selected"{/if}>(GMT -11:00) Midway Island, Samoa</option>
							      <option value="-10.0"{if $article.others.timezone=="-10.0"} selected="selected"{/if}>(GMT -10:00) Hawaii</option>
							      <option value="-9.0"{if $article.others.timezone=="-9.0"} selected="selected"{/if}>(GMT -9:00) Alaska</option>
							      <option value="-8.0"{if $article.others.timezone=="-8.0"} selected="selected"{/if}>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
							      <option value="-7.0"{if $article.others.timezone=="-7.0"} selected="selected"{/if}>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
							      <option value="-6.0"{if $article.others.timezone=="-6.0"} selected="selected"{/if}>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
							      <option value="-5.0"{if $article.others.timezone=="-5.0"} selected="selected"{/if}>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
							      <option value="-4.0"{if $article.others.timezone=="-4.0"} selected="selected"{/if}>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
							      <option value="-3.5"{if $article.others.timezone=="-3.5"} selected="selected"{/if}>(GMT -3:30) Newfoundland</option>
							      <option value="-3.0"{if $article.others.timezone=="-3.0"} selected="selected"{/if}>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
							      <option value="-2.0"{if $article.others.timezone=="-2.0"} selected="selected"{/if}>(GMT -2:00) Mid-Atlantic</option>
							      <option value="-1.0"{if $article.others.timezone=="-1.0"} selected="selected"{/if}>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
							      <option value="0.0"{if $article.others.timezone=="0.0"} selected="selected"{/if}>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
							      <option value="1.0"{if $article.others.timezone=="1.0"} selected="selected"{/if}>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
							      <option value="2.0"{if $article.others.timezone=="2.0"} selected="selected"{/if}>(GMT +2:00) Kaliningrad, South Africa</option>
							      <option value="3.0"{if $article.others.timezone=="3.0"} selected="selected"{/if}>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
							      <option value="3.5"{if $article.others.timezone=="3.5"} selected="selected"{/if}>(GMT +3:30) Tehran</option>
							      <option value="4.0"{if $article.others.timezone=="4.0"} selected="selected"{/if}>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
							      <option value="4.5"{if $article.others.timezone=="4.5"} selected="selected"{/if}>(GMT +4:30) Kabul</option>
							      <option value="5.0"{if $article.others.timezone=="5.0"} selected="selected"{/if}>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
							      <option value="5.5"{if $article.others.timezone=="5.5"} selected="selected"{/if}>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
							      <option value="5.75"{if $article.others.timezone=="5.75"} selected="selected"{/if}>(GMT +5:45) Kathmandu</option>
							      <option value="6.0"{if $article.others.timezone=="6.0"} selected="selected"{/if}>(GMT +6:00) Almaty, Dhaka, Colombo</option>
							      <option value="7.0"{if $article.others.timezone=="7.0"} selected="selected"{/if}>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
							      <option value="8.0"{if $article.others.timezone=="8.0"} selected="selected"{/if}>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
							      <option value="9.0"{if $article.others.timezone=="9.0"} selected="selected"{/if}>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
							      <option value="9.5"{if $article.others.timezone=="9.5"} selected="selected"{/if}>(GMT +9:30) Adelaide, Darwin</option>
							      <option value="10.0"{if $article.others.timezone=="10.0"} selected="selected"{/if}>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
							      <option value="11.0"{if $article.others.timezone=="11.0"} selected="selected"{/if}>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
							      <option value="12.0"{if $article.others.timezone=="12.0"} selected="selected"{/if}>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
							</select>
						</td>
					</tr>
			</table>
			</fieldset>
			{if $article.others.product_id}
			<fieldset class="formview panel">
			<div class="clearfix title">
				<div class="floatleft">
				<small>Additional Prices</small>
				</div>				
			</div>
			<div id="product_prices">
				
			</div>				
		</fieldset>
			<div id="load_prices">
				
			</div>
			<script type="text/javascript">
				var current='{$root_module}sales/categories/';
				var product_id='{$product.id}';
				{literal}
				function load_prices(product_id){
					ajax_load(current+'?a=get_product_prices:'+product_id,'','#product_prices');
				}
				
				function delete_price(product_id,price_id){
					if(confirm('Are you sure you want to delete this?'))
						ajax_load(current+'?a=delete_price:'+product_id+':'+price_id,'','#product_prices');
				}
				
				function edit_price(product_id,price_id)
				{
					$('#load_prices').dialog(
						{
							title:'Edit price',
							modal:true,
							buttons:{
								'Cancel':function(){$('#load_prices').dialog('destroy')},
								'Save':function(){
									$('#add_details_form').submit();
								}
							}					
						}
					);
					ajax_load(current+'?a=edit_price:'+product_id+':'+price_id,'','#load_prices');
				}
				
				function add_price(product_id){
					$('#load_prices').dialog(
						{
							title:'Add price',
							modal:true,
							buttons:{
								'Cancel':function(){$('#load_prices').dialog('destroy')},
								'Add':function(){
									$('#add_details_form').submit();
								}
							}					
						}
					);
					ajax_load(current+'?a=add_price:'+product_id,'','#load_prices');
				}
				
				function close_form()
				{
					$('#load_prices').dialog('destroy');
					load_prices(product_id);
				}				
				{/literal}
				load_prices({$article.others.product_id});			
		</script>
		{/if}
			<fieldset class="formview panel">
				<div class="clearfix title">
					<small>Other details</small>
				</div>
				<table cellspacing="0" cellspacing="0" border="0">
					<tr>
						<th><label for="language_id">Language:</label></th>
						<td>
							{foreach item=l from=$languages}<label><input type="radio" name="language_id" value="{$l.id}" {if $article.language_id==$l.id} checked="checked"{/if}/>{image title=$l.valoare alt=$l.valoare}{$l.image_id}{/image}</label>{/foreach}
						</td>							
					</tr>										
					<tr >
						<th><label for="image">Event image:</label></th>
						<td>
							{if $article.image_id}
							<div>
								{image alt="No image" width="120" height="240" resize="true"}{$article.image_id}{/image}
							</div>
							{/if}
							<input type="file" name="image" class="text" value=""/>
						</td>	
					</tr>
			</table>
			</fieldset>	
		</div>		
	</div>
</div>
<div class="clearfix form_fixed_bar">
	{literal}
						<button class="floatleft positive" type="submit" onclick="show_submit_loader('Please wait while validating...');if($('#texte_form').valid())show_submit_loader('Please wait while processing...');else hide_submit_loader();"><b class="icon icon-disk"></b>Save</button>
									<input type="hidden" name="return" value="" id="input_return"/>

			<button class="floatleft" type="submit" onclick="show_submit_loader('Please wait while validating...');if($('#texte_form').valid()){$('#input_return').val(1);show_submit_loader('Please wait while processing...');}else hide_submit_loader();"><b class="icon icon-disk"></b>Save & show list</button>
			{/literal}
									<button class="floatright negative" type="button" onclick="go_to('{$current}?article={$parent_id}')"><b class="icon icon-ban"></b>Cancel</button>
									<button class="floatright" type="reset"><b class="icon icon-backward"></b>Reset</button>
					</div>
</form>
