<div class="sitemap">
<ul>
	{foreach item=pag from=$map key=k name=map}		
		<li><a href="{$pag.link}">{tr tags="menu"}{$pag.name}{/tr}</a></li>
		{if $k<=count($pag) && !$smarty.foreach.map.last}
		<li>/</li>
		{/if}		
	{/foreach}
</ul>
</div>