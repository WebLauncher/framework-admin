<form action="{$current}" method="post" enctype="multipart/form-data" id="article_images_form">
	<input type="hidden" name="a" value="image_save:{$article.id}:{$image.id}"/>
	<div class="formview panel">
	<table cellspacing="0" cellspacing="0" border="0">
		<tr>
			<th><label for="image">{tr}Imaginea curenta:{/tr}*</label></th>
			<td>
			<center>{image width=120}{$image.image_id}{/image}</center>
			</td>
		</tr>
		<tr>
			<th><label for="image">{tr}Schimba image:{/tr}</label></th>
			<td>
			<input type="file" name="image" class="text" value=""/>			
			</td>
		</tr>
		<tr>
			<th><label for="description">{tr}Description:{/tr}</label></th>
			<td>			<textarea rows="3" name="description">{$image.description}</textarea></td>
		</tr>
		<tr>
			<th><label for="url">{tr}Url for link:{/tr}</label>
				<div class="field_description">
				{tr}Url for when the image is clicked as a banner.{/tr}
			</div>
			</th>
			<td>
			<input type="text" name="url" class="text" value="{$image.url}"/>
			</td>
		</tr>
		<tr>
			<th><label for="target">{tr}Deschide link-ul in:{/tr}</label>
			<div class="field_description">
				{tr}Targetul linkului cand se apara pe banner. Ultimele 2 optiuni sunt doar in cazul utilziarii frameurilor pe site.{/tr}
			</div></th><td><label>
				<input type="radio"{if $image.target=="_self"}checked="checked"{/if} value="_self" name="target">
				{tr}Aceeasi pagina{/tr}</label><label>
				<input type="radio"{if $image.target=="_blank"}checked="checked"{/if} value="_blank" name="target">
				{tr}Pagina noua{/tr}</label><br/><label>
				<input type="radio"{if $image.target=="_parent"}checked="checked"{/if} value="_parent" name="target">
				{tr}Frame-ul parinte{/tr}</label><label>
				<input type="radio"{if $image.target=="_top"}checked="checked"{/if} value="_top" name="target">
				{tr}Frame-ul de top{/tr}</label></td>
		</tr>
	</table>
	</div>
	<div class="clearfix">
	<button type="submit" class="floatright"><b class="icon icon-disk"></b>{tr}Salveaza{/tr}</button>
</div>
</form>

<script type="text/javascript" charset="utf-8">
{literal}
	 jQuery('#article_images_form').ajaxForm({
	 	beforeSubmit:function() { 
        	return jQuery('#article_images_form').valid();
     },
     success:function(){
     	ajax_load(root+'admin/articles/?a=images:{/literal}{$article.id}{literal}','','#article_images');
     	jQuery('#images_load_div').dialog('destroy');
     }
     });
{/literal} 
</script>