<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save_folder" />
	<input type="hidden" name="type" value="{$p.session.articles_type}" />
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
							<td colspan="2"><label for="_title">Title:*</label><br/><input type="text" name="_title" value="" class="text" style="width:99%"/>
								{validator form="texte_form" field="_title" rule="required" message="Please fill in the title!"}
							</td>
						</tr>
						<tr>
							<td colspan="2"><label for="_title">Code:</label><br/><input type="text" name="code" value="" class="text" style="width:99%"/>								
							</td>
						</tr>								
						<tr>
							<td colspan="2">
								<label for="summary">Description:</label>
								<textarea id="field_summary" name="summary" class="textarea"></textarea>
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
							<input type="hidden" name="parent_id" value="{$parent_id}"/>
							{if !$parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$parent_id}{/bind}{/if}
						</td>
					</tr>
					<tr>
						<th><label for="language_id">Language:</label></th>
						<td>
							{foreach item=l from=$languages}<label><input type="radio" name="language_id" value="{$l.id}"{if $l.id==8} checked="checked"{/if}/>{image title=$l.valoare alt=$l.valoare}{$l.image_id}{/image}</label>{/foreach}
						</td>							
					</tr>			
					<tr >
						<th><label for="image">Directory image:</label></th>
						<td>							
							<input type="file" name="image" class="text" value=""/>
						</td>	
					</tr>
					<tr class='alt'>
						<th><label for="has_kids">Allow sub-directories:</label></th>
						<td>
							<label><input type="radio" name="others[allow_subdirectories]" value="0"/>No</label>
							<label><input type="radio" name="others[allow_subdirectories]" value="1" checked="checked"/>Yes</label>
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
