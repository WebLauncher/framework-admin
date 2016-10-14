{if $p.component}
	{$subpage}
{else}
{$form}
{include file=$p.objects.admin.admin_menu_page menu=$admin_menu_page}
{/if}
