{if $p.subquery[3]}
	{$subpage}
{else}
	{include file=$p.objects.admin.admin_menu_page menu=$admin_menu_page}
{/if}
