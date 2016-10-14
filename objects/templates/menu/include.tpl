<ul{if $menu_id} id="{$menu_id}"{/if}>
	{foreach item=m from=$menu}	
	<li><a href="{$root_module}{eval var=$m.link}">{if isset($m.icon)}<b class="icon {$m.icon}"></b>{/if}<span>{tr}{eval var=$m.name}{/tr}</span></a>
		{if isset($m.submenu)}				
		{include file=$p.objects.templates.menu menu=$m.submenu.item menu_id=""}
		{/if}
	</li>
	{/foreach}
</ul>