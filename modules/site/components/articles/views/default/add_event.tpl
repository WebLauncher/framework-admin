<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save" />
	<input type="hidden" name="type" value="{$p.session.articles_type}" />
	<div class="form_wrap" id="form_texte_form">
		<div id="acordeon_texte_form" class="clearfix">		
		<div class="floatleft" style="width:70%;">
			<div class="formview panel">
				<div class="clearfix title">				
					<small>Event details</small>
				</div>			
						<table cellspacing="0" cellspacing="0" border="0">
							
							<tr>
								<td colspan="2"><label for="_title">Name:*</label><br/><input type="text" name="_title" value="{if !$parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$parent_id}{/bind}{/if}" class="text" style="width:99%"/>
									{validator form="texte_form" field="_title" rule="required" message="Please fill in a name!"}
								</td>
							</tr>						
							<tr>
								<td colspan="2">
									<label>Description:</label><br/>														
									<script type="text/javascript" src="{$root}objects/editors/ckeditor/ckeditor.js"></script>
									<textarea id="_content" name="_content" style="width:98%;height:100px;"></textarea>
									<script type="text/javascript">
										start_editor('ckeditor','_content','');
									</script>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="summary">Summary:</label>
									<textarea id="field_summary" name="summary" class="textarea"></textarea>
								</td>	
							</tr>						
						</table>
			</div>
			{if $parent_folder.others.has_payment}
			<div class="formview panel">
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
							{capture assign="detail_value"}{$d.default_value}{/capture}
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
			</div>
			{/if}
		</div>
		<div class="floatright" style="width:30%;">
			<div class="formview panel">
				<div class="clearfix title">
					<small>Parent directory</small>
				</div>
				<div class="content">
					<input type="hidden" name="parent_id" value="{$parent_id}"/>
									<input type="hidden" name="has_kids" value="0"/>
									<a href="{$current}?article={$parent_id}">{if !$parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$parent_id}{/bind}{/if}</a>
				</div>
			</div>
			<div class="formview panel">
				<div class="clearfix title">
					<small>Location</small>
				</div>
			<table cellspacing="0" cellspacing="0" border="0">				
					
					<tr>
						<th><label>Country:</label></th>
						<td>
							<select class="text" name="country" onchange="ajax_load('{$root_module}articles/?a=states:'+$('[name=\'country\']').val(),'','#stateField','');" style="width:99%;">
								{foreach item=c from=$countries}
								<option value="{$c.code}"{if $c.code=='US'} selected="selected"{/if}>{$c.name}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
	        			<th><label>State:</label></th>
	        			<td id="stateField"></td>												
						<script type="text/javascript">
							ajax_load('{$root_module}articles/?a=states:'+$('[name=\'country\']').val(),'','#stateField','');
						</script>
	        		</tr>
	        		<tr>
	        			<th><label>City:</label></th>
	        			<td><input type="text" name="city" value="" class="text" style="width:99%;"/>
						</td>
	        		</tr>
					<tr>
						<th><label for="language_id">Address:</label></th>
						<td>
							<textarea name="address"></textarea>
						</td>							
					</tr>
					<tr>
	        			<th><label>Zip:</label></th>
	        			<td><input type="text" name="zip" value="" class="text" style="width:99%;"/>
						</td>
	        		</tr>
			</table>
			</div>
			<div class="formview panel">
				<div class="clearfix title">
					<small>Date and time</small>
				</div>
			<table cellspacing="0" cellspacing="0" border="0">
					<tr>
	        			<th><label>Start on:</label></th>
	        			<td><input type="text" name="start_date" readonly="readonly" value="0000-00-00" class="text calendar" style="width:60%"/> at
						<input type="text" name="start_time" id="start_time" readonly="readonly" value="00:00" class="text" style="width:25%"/>		
						<script type="text/javascript">
							$('#start_time').timepicker();
						</script>						
						</td>
	        		</tr>				
					
					<tr>
	        			<th><label>End on:</label></th>
	        			<td><input type="text" name="end_date" readonly="readonly" value="0000-00-00" class="text calendar" style="width:60%"/> at
						<input type="text" name="end_time" id="end_time" readonly="readonly" value="00:00" class="text" style="width:25%"/>		
						<script type="text/javascript">
							$('#end_time').timepicker();
						</script>						
						</td>
	        		</tr>
					<tr>
						<th><label>Timezone:</label></th>
						<td>
							<select name="timezone" style="width:99%;">
							      <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
							      <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
							      <option value="-10.0">(GMT -10:00) Hawaii</option>
							      <option value="-9.0">(GMT -9:00) Alaska</option>
							      <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
							      <option value="-7.0" selected="selected">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
							      <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
							      <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
							      <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
							      <option value="-3.5">(GMT -3:30) Newfoundland</option>
							      <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
							      <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
							      <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
							      <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
							      <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
							      <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
							      <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
							      <option value="3.5">(GMT +3:30) Tehran</option>
							      <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
							      <option value="4.5">(GMT +4:30) Kabul</option>
							      <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
							      <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
							      <option value="5.75">(GMT +5:45) Kathmandu</option>
							      <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
							      <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
							      <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
							      <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
							      <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
							      <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
							      <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
							      <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
							</select>
						</td>
					</tr>
			</table>
			</div>
			<div class="formview panel">
				<div class="clearfix title">
					<small>Other details</small>
				</div>
				<table cellspacing="0" cellspacing="0" border="0">
					<tr>
						<th><label for="language_id">Language:</label></th>
						<td>
							{foreach item=l from=$languages}<label><input type="radio" name="language_id" value="{$l.id}" {if 8==$l.id} checked="checked"{/if}/>{image title=$l.valoare alt=$l.valoare}{$l.image_id}{/image}</label>{/foreach}
						</td>							
					</tr>										
					<tr >
						<th><label for="image">Event image:</label></th>
						<td>
							<input type="file" name="image" class="text" value=""/>
						</td>	
					</tr>
			</table>
			</div>	
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
