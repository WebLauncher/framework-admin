<form action="{$current}" name="texte_form" id="texte_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="save:{$article.id}" />
	<input type="hidden" name="type" value="{$article.type}" />
	{if !$article.is_translation}
	<input type="hidden" name="has_kids" value="1"/>
	{/if}	
	<input type="hidden" name="parent_id" value="{$article.parent_id}"/>
	<div class="form_wrap" id="form_texte_form">
		<div id="acordeon_texte_form" class="clearfix">
			<div class="floatleft" style="width:80%;">		
				<div class="formview panel">
					<div class="clearfix title">				
						<small>{tr}{if $article.is_translation}Translation{else}Article{/if}{/tr}</small>
					</div>				
					<br/>
					Parent Article/Folder: <strong>{if !$article.parent_id}Root directory{else}{bind table=$p.tables.tbl_articles get_field="title"}{$article.parent_id}{/bind}{/if}</strong>
								
							<table cellspacing="0" cellspacing="0" border="0">
								<tr>
									<td colspan="2"><input type="text" name="_title" value="{$article.title}" class="text" style="width:99%"/></td>
								</tr>						
								<tr>
									<td colspan="2">														
										<script type="text/javascript" src="{$root}objects/editors/ckeditor/ckeditor.js"></script>
										<textarea id="_content" name="_content" style="width:98%;height:100px;">{$article.content}</textarea>
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
										<textarea id="field_summary" name="summary" class="textarea">{$article.summary}</textarea>
									</td>	
								</tr>	
							<tr>
								<th><label>Slug:</label></th><td>{$article.code|wordwrap:30:"<br />\n":true}
									<input type="hidden" name="code" value="{$article.code}"/>
								</td>
							</tr>
							
							{if $article.related_id}
							<tr>
								<th><label>{tr}Sub-Article of:{/tr}</label></th>
								<td>
									<a href="{$root_module}articles/{bind table=$p.tables.tbl_articles get_field='type'}{$article.related_id}{/bind}/?a=edit:{$article.related_id}">{bind table=$p.tables.tbl_articles get_field='title'}{$article.related_id}{/bind}</a>
								</td>
							</tr>
							{/if}
							<tr>
								<th><label for="language_id">Language:</label></th>
								<td>	
									<input type="hidden" name="language_id" value="{$article.language_id}"/> 		
									{$article.language.valoare}
								</td>							
							</tr>
							
							{if $article.image_id}						
							<tr >
								<th><label for="image">{tr}Main image:{/tr}</label></th>
								<td>
									
									<div>
										{image alt="No image" width="120" height="240" resize="true"}{bind table=$p.tables.tbl_articles_images get_field="image_id"}{$article.image_id}{/bind}{/image}
									</div>
								</td>	
							</tr>					
							{/if}		
							<tr >
								<th><label for="image">{tr}Upload main image:{/tr}</label></th>
								<td>									
									<input type="file" name="image" class="text" value=""/>
								</td>	
							</tr>
					</table>	
				</div>	
				{if !$article.is_translation}
				<div class="formview panel">
					<div class="clearfix title">				
						<small>Translations</small>
					</div>	
					<div class="field_description">
						{tr}Daca vreti sa adaugati traduceri mai jos trebuie sa activati mai multe limbi din configuratii{/tr}
					</div>
					<table cellspacing="0" cellspacing="0" border="0">
						{foreach item=tran from=$article.translations}
							<tr>
								<th><label>{$tran.language.valoare} <img src="{$skin_images}languages/{$tran.language.code|lower}.png"/></label></th><td><a href="{$current}?a=edit:{$tran.id}">edit</a></td>
							</tr>
						{/foreach}
						{if count($article.translatations_available)}
							{foreach from=$article.translatations_available item=tran_a}
							<tr>
								<th><label>{bind table=$p.tables.tbl_locales get_field='valoare'}{$tran_a}{/bind} {bind table=$p.tables.tbl_locales get_field='code' assign="l_code"}{$tran_a}{/bind} {image}{$skin_images}languages/{$l_code|lower}.png{/image}</label></th>
								<td><a href="{$current}?a=add_translation_save:{$article.id}&language_id={$tran_a}">add</a></td>
							</tr>
							{/foreach}
						{/if}
					</table>	
				</div>				
				{/if}
				{if $article.has_kids && count($article.kids)}
				<div class="formview panel">
					<div class="clearfix title">				
						<small>{tr}Related Sub-articles{/tr}</small>
					</div>
					<table cellspacing="0" cellspacing="0" border="0">
						{foreach item=sub from=$article.kids}
							<tr>
								<td><a href="{$root_module}articles/newpage/?a=edit:{$sub.id}">{$sub.title}</a></td>
							</tr>
						{/foreach}						
					</table>	
				</div>				
				{/if}
				<div class="formview panel">
					<div class="clearfix title">				
						<small>{tr}Images{/tr}</small>
					</div>		
					<div id="article_images"></div>				
					<script type="text/javascript">
					{literal}
						jQuery(function(){
							ajax_load(root+'admin/articles/?a=images:{/literal}{$article.id}{literal}','','#article_images');
						});
					{/literal}
					</script>	
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
									<button class="floatright negative" type="button" onclick="go_to('{$current}?article={$article.parent_id}')"><b class="icon icon-ban"></b>Cancel</button>
									<button class="floatright" type="reset"><b class="icon icon-backward"></b>Reset</button>
					</div>
</form>
