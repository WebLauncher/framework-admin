
{if $p.actions[0]==""}
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{elseif $p.actions[0]=="edit" || $p.actions[0]=="add" || $p.actions[0]=="send"}
	{include file=$p.objects.templates.form form=$form p=$p}
{/if}