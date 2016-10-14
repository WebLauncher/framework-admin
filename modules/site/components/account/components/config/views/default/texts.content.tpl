{if $p.actions[0]==""}
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{elseif $p.actions[0]=="edit"}
	{include file=$p.objects.templates.form form=$form p=$p}
{elseif $p.actions[0]=="trad"}
	{include file=$p.objects.templates.form form=$form p=$p}
{/if}