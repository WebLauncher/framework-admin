<div class="clearfix">
	<button type="button" class="floatleft" onclick="go_to('{$current}?a=add')"><img src="{$skin_images}icons/add.gif" />{tr tags="buttons"}Adauga configuratii particulare pentru o pagina{/tr}</button>
	<label class="floatright">
	Select website:
	<select name="site" class="text">
		<option value="">- all sites -</option>
		{foreach item=s from=$sites}
		{/foreach}
	</select>	
	</label>
</div>
{include file=$p.objects.templates.ajax_table table=$table p=$p}
