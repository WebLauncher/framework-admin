
{if $p.actions[0]==""}	
	<div class="clearfix">
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=add')"><b class="icon icon-plus"></b>{tr tags="buttons"}Adauga tip{/tr}<button>
	</div>
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{elseif $p.actions[0]=="edit" || $p.actions[0]=="add"}
	{include file=$p.objects.templates.form form=$form p=$p}
{/if}