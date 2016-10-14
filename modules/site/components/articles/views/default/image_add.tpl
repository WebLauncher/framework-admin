<form action="{$current}" method="post" enctype="multipart/form-data" id="article_images_form">
	<input type="hidden" name="a" value="image_save:{$article.id}"/>
	<div class="formview panel">
	<table cellspacing="0" cellspacing="0" border="0">
		<tr>
			<th><label for="image">{tr}Select image:{/tr}*</label></th>
			<td>
			<input type="file" name="image" class="text" value=""/>
			{validator form="article_images_form" field="image" rule="required" message="Please select an image!"}
			</td>
		</tr>
		<tr>
			<th><label for="description">{tr}Description:{/tr}</label></th>
			<td>			<textarea rows="3" name="description">{$article.summary}</textarea></td>
		</tr>
		<tr>
			<th><label for="url">{tr}Url for link:{/tr}</label>
				<div class="field_description">
				{tr}Url for when the image is clicked as a banner.{/tr}
			</div>
			</th>
			<td>
			<input type="text" name="url" class="text" value=""/>
			</td>
		</tr>
		<tr>
			<th><label for="target">{tr}Deschide link-ul in:{/tr}</label>
			<div class="field_description">
				{tr}Targetul linkului cand se apara pe banner. Ultimele 2 optiuni sunt doar in cazul utilziarii frameurilor pe site.{/tr}
			</div></th><td><label>
				<input type="radio" checked="checked" value="_self" name="target">
				{tr}Aceeasi pagina{/tr}</label><label>
				<input type="radio" value="_blank" name="target">
				{tr}Pagina noua{/tr}</label><br/><label>
				<input type="radio" value="_parent" name="target">
				{tr}Frame-ul parinte{/tr}</label><label>
				<input type="radio" value="_top" name="target">
				{tr}Frame-ul de top{/tr}</label></td>
		</tr>
	</table>
	</div>
	<div class="clearfix">
	<button type="submit" class="floatright"><b class="icon icon-disk"></b>{tr}Adauga{/tr}</button>
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