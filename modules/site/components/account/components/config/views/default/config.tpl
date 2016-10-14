{if $p.subcomponent}
	{$page_component_2}
{else}
	{include file=$p.objects.admin.admin_menu_page menu=$admin_menu_page}
{/if}