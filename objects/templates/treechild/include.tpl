<ul>
	{foreach item=r from=$child name=child}
	<li {if !$r.collapsed}class="closed"{/if}>
		<span class="{$r.text_class}">
			{if $r.link}
			<a href="{$r.link}" title="{$r.title}">{$r.text}</a>
			{else}
			{$r.text}
			{/if}
		</span>
		{if count($r.children)>0}
		{include file=$p.objects.templates.treechild child=$r.children}
		{/if}
	</li>
	{/foreach}
</ul>