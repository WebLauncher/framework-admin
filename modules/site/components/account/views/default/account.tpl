{if $p.subquery[2]}
{$subpage}
{else}
	{include file=$p.objects.admin.admin_menu_page menu=$admin_menu_page}
{/if}