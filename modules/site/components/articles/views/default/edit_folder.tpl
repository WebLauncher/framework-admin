<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save_folder:{$directory.id}" />
	<input type="hidden" name="type" value="{$directory.type}" />
	<div class="form_wrap" id="form_texte_form">
		<div id="acordeon_texte_form" class="clearfix">		
		<div class="floatleft" style="width:70%;">
		<fieldset class="formview">
			<div class="clearfix title">
				<div class="floatleft">
				Directory details
				</div>				
			</div>			
					<table cellspacing="0" cellspacing="0" border="0">
						<tr>
							<td colspan="2"><label for="_title">Title:*</label><br/><input type="text" name="_title" value="{$directory.title}" class="text" style="width:99%"/>
								{validator form="texte_form" field="_title" rule="required" message="Please fill in the title!"}
							</td>
						</tr>
						<tr>
							<td colspan="2"><label for="_title">Code:</label><br/><input type="text" name="code" value="{$directory.code}" class="text" style="width:99%"/>								
							</td>
						</tr>								
						<tr>
							<td colspan="2">
								<label for="summary">Description:</label>
								<textarea id="field_summary" name="summary" class="textarea">{$directory.summary}</textarea>
							</td>	
						</tr>
										
					</table>
		</fieldset>
		</div>	
		<div class="floatright" style="width:30%;">
		<fieldset class="formview">
			<div class="clearfix title">
				<div class="floatleft">
				Other details
				</div>				
			</div>
			<input type="hidden" name="has_kids" value="1"/>
			<table cellspacing="0" cellspacing="0" border="0">					
					<tr>
						<th><label>Parent directory:</label></th>
						<td>
							<input type="hidden" name="parent_id" value="{$directory.parent_id}"/>
							{if !$directory.parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$directory.parent_id}{/bind}{/if}
						</td>
					</tr>
					<tr>
						<th><label for="language_id">Language:</label></th>
						<td>
							{foreach item=l from=$languages}<label><input type="radio" name="language_id" value="{$l.id}"{if $l.id==$directory.language_id} checked="checked"{/if}/>{image title=$l.valoare alt=$l.valoare}{$l.image_id}{/image}</label>{/foreach}
						</td>							
					</tr>	
					<tr >
						<th><label for="image">Directory image:</label></th>
						<td>	
							{if $directory.image_id}
							<div>
								{image alt="No image" width="120" height="240" resize="true"}{$directory.image_id}{/image}
							</div>
							{/if}						
							<input type="file" name="image" class="text" value=""/>
						</td>	
					</tr>
					<tr class='alt'>
						<th><label for="has_kids">Allow sub-directories:</label></th>
						<td>
							<label><input type="radio" name="others[allow_subdirectories]"{if !$directory.others.allow_subdirectories} checked="checked"{/if} value="0"/>No</label>
							<label><input type="radio" name="others[allow_subdirectories]"{if $directory.others.allow_subdirectories} checked="checked"{/if} value="1"/>Yes</label>
						</td>	
					</tr>
					<tr class='alt'>
						<th><label for="related_id">{tr}Related to:{/tr}</label>
							<div class="field_description">
								{tr}Any article in this directory will be automatically related to the selected article.{/tr}
							</div>
						</th>
						<td>
							<select name="related_id">
								<option value="0">{tr}No article{/tr}</option>
								<optgroup label="{tr}Main Pages{/tr}">
									{foreach item=rel from=$related}
									{if $rel.type=="main"}
									<option value="{$rel.id}"{if $directory.related_id==$rel.id} selected="selected"{/if}>{$rel.title}</option>
									{/if}
									{/foreach}
								</optgroup>
								<optgroup label="{tr}Events{/tr}">
									{foreach item=rel from=$related}
									{if $rel.type=="events"}
									<option value="{$rel.id}"{if $directory.related_id==$rel.id} selected="selected"{/if}>{$rel.title}</option>
									{/if}
									{/foreach}
								</optgroup>
								<optgroup label="{tr}News{/tr}">
									{foreach item=rel from=$related}
									{if $rel.type=="news"}
									<option value="{$rel.id}"{if $directory.related_id==$rel.id} selected="selected"{/if}>{$rel.title}</option>
									{/if}
									{/foreach}
								</optgroup>
								<optgroup label="{tr}New Pages{/tr}">
									{foreach item=rel from=$related}
									{if $rel.type=="newpage"}
									<option value="{$rel.id}"{if $directory.related_id==$rel.id} selected="selected"{/if}>{$rel.title}</option>
									{/if}
									{/foreach}
								</optgroup>
							</select>
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
									<button class="floatright negative" type="button" onclick="go_to('{$current}?article={$directory.parent_id}')"><b class="icon icon-ban"></b>Cancel</button>
									<button class="floatright" type="reset"><b class="icon icon-backward"></b>Reset</button>
					</div>
</form>
