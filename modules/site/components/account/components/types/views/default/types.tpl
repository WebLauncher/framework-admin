	{if $p.actions[0]=="edit"}
		{include file=$p.objects.templates.form form=$form p=$p}
	{elseif $p.actions[0]=="add"}
		{include file=$p.objects.templates.form form=$form p=$p}
	{else}
	{if $is_master == 1}
		<div class="clearfix">
			<button class="floatleft" onclick="go_to('{$current}?a=add')"><b class="icon icon-plus"></b>Add type</button>
		</div>
	{/if}
		{include file=$p.objects.templates.ajax_table form=$table p=$p}
	{/if}