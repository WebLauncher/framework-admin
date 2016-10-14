<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save" />
	<input type="hidden" name="type" value="{$p.session.articles_type}" />
	<input type="hidden" name="has_kids" value="1"/>
	<input type="hidden" name="parent_id" value="{$parent_id}"/>
	<div class="form_wrap" id="form_texte_form">
		<div id="acordeon_texte_form" class="clearfix">		
		<div class="floatleft" style="width:80%;height:100%;">		
				<div class="formview panel">
					<div class="clearfix title">				
						<small>Article</small>
					</div>		
					<br/>
					Add article to: <strong>{if !$parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$parent_id}{/bind}{/if}</strong>	
					<table cellspacing="0" cellspacing="0" border="0">
						<tr>
							<td colspan="2"><input placeholder="{tr}Title{/tr}" type="text" name="_title" value="" class="text" style="width:99%"/>
								{validator form="texte_form" field="_title" rule="required" message="Please fill in a title!"}
							</td>
						</tr>						
						<tr>
							<td colspan="2">														
								<script type="text/javascript" src="{$root}objects/editors/ckeditor/ckeditor.js"></script>
								<textarea id="_content" name="_content" style="width:100%;height:400px;"></textarea>
								<script type="text/javascript">
									CKEDITOR.config.height = $(window).height()-300;
									start_editor('ckeditor','_content','');
								</script>
							</td>
						</tr>						
					</table>
				</div>
		</div>
		<div class="floatright" style="width:20%;">
				<div class="formview panel">
					<div class="clearfix title">				
						<small>Details</small>
					</div>			
			<table cellspacing="0" cellspacing="0" border="0">	
				
						<tr>
							<td colspan="2">
								<label for="summary">Summary:</label>
								<textarea id="field_summary" name="summary" class="textarea"></textarea>
							</td>	
						</tr>				
					
					<tr>
						<th><label for="language_id">Language:</label></th>
						<td>
							{foreach item=l from=$languages}<label><input type="radio" name="language_id" value="{$l.id}" {if 8==$l.id} checked="checked"{/if}/>{image title=$l.valoare alt=$l.valoare}{$l.image_id}{/image}</label>{/foreach}
						</td>							
					</tr>				
					<tr >
						<th><label for="image">Image:</label></th>
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
