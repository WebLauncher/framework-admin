<?xml version="1.0" encoding="utf-8" ?>
<table>
	<colls>
		{foreach item=h from=$table.header key=k name=header}
		{if !isset($h.hidden) || !$h.hidden}
		<coll label="{tr tags="table_colls"}{$h.name|default:""}{/tr}" name="{$h.col}" sort="{$h.sort|default:1}" sort_dir="{$h.sort_dir|default:""}"{if $h.width} width="{$h.width}"{/if}/>
		{/if}	
		{/foreach}
		{if count($table.actions)}
		<coll label="{tr}Actiuni{/tr}" name="actions"/>
		{/if}
	</colls>
	<data total="{$table.total}">
		{if isset($table.content)}
		{foreach item=o from=$table.content key=key}			
			<row>
				{foreach item=h from=$table.header key=k name=content}	
				<{$h.col}><![CDATA[{include file=$field_path_inc}]]></{$h.col}>
				{/foreach}
				{if count($table.actions)}
				<actions>
					<![CDATA[
				{foreach item=act from=$table.actions}				
					<a href="{if $act.link}{eval var=$act.link}{else}#{/if}" onclick="{if $act.onclick}ajax_action('{$current}','{eval var=$act.onclick}',{if $act.refresh}'$(\'#j_{$table.id}\').gridview(\'refresh\');'{else}''{/if},'{tr tags="confirm_msgs"}{$act.confirm}{/tr}');{else}{if $act.confirm}return confirm('{tr tags="confirm_msgs"}{$act.confirm}{/tr}');{/if}{/if}" title="{tr}{$act.title}{/tr}"><b class="icon {$act.icon}"></b>{eval var=$act.text}</a>
				{/foreach}
				]]>
				</actions>
				{/if}
			</row>
		{/foreach}
		{/if}
	</data>
</table>