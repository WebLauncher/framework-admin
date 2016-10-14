{if $p.actions[0]=="edit"}
	{include file=$p.objects.templates.form form=$form p=$p}
{else}
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{/if}
