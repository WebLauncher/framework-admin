
	{if $p.actions[0]=="edit" ||  $p.actions[0]=="add"}
		{include file=$p.objects.templates.form form=$form p=$p}
	{else}
		<div class="clearfix">
			<button class="floatleft" onclick="go_to('{$current}?a=add')"><b class="icon icon-plus"></b><span>{tr tags="buttons"}Adauga paginare{/tr}</span></button>
		</div>
		{include file=$p.objects.templates.ajax_table table=$table p=$p}
	{/if}
