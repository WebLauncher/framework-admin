{if $p.actions[0]==""}
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{elseif $p.actions[0]=="edit" || $p.actions[0]=="trad" || $p.actions[0]=='setdefault' || $p.actions[0]=='copy_language'}
	{include file=$p.objects.templates.form form=$form p=$p}
{/if}