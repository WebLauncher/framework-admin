
{if $p.actions[0]==""}	
	<div class="clearfix">
		<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=add')"><b class="icon icon-plus"></b>{tr tags="buttons"}Adauga email{/tr}</button>
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=export')"><b class="icon icon-drive_disk"></b>{tr tags="buttons"}Exporta{/tr}</button>
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=import')"><b class="icon icon-page_white_get"></b>{tr tags="buttons"}Importa{/tr}</button>
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=unsubscribe')"><b class="icon icon-disconnect"></b>{tr tags="buttons"}Verifica dezabonarile{/tr}</button>
	
	</div>
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{elseif $p.actions[0]=="edit" || $p.actions[0]=="import" || $p.actions[0]=="add" || $p.actions[0]=="export" || $p.actions[0]=="unsubscribe"}
	{include file=$p.objects.templates.form form=$form p=$p}
{/if}