{foreach item=h from=$table.header key=k name=header}	
	{if !isset($h.hidden) || !$h.hidden}		
	{if isset_or($passed)}###{/if}{tr tags="table_colls"}{$h.name|default:""}{/tr}---{$h.col}---{$h.sort|default:1}---{$h.sort_dir|default:""}{if $h.width}---{$h.width}{/if}
	{assign var=passed value=1}	
	{/if}	
{/foreach}
{if count($table.actions)}
###{tr}Actiuni{/tr}---actions
{/if}
|||
{if isset($table.content)}
{foreach item=o from=$table.content key=key}
	{assign var=passed value=0}
	{foreach item=h from=$table.header key=k name=table_content}{include file=$field_path_inc h=$h show_end=$smarty.foreach.table_content.last}{/foreach}		
	{if count($table.actions)}###{foreach item=act from=$table.actions}	
				<a href="{if $act.link}{eval var=$act.link}{else}#{/if}" onclick="{if $act.onclick}ajax_action('{$current}','{eval var=$act.onclick}',{if $act.refresh}'$(\'#j_{$table.id}\').gridview(\'refresh\');'{else}''{/if},'{tr tags="confirm_msgs"}{$act.confirm}{/tr}');{else}{if $act.confirm}return confirm('{tr tags="confirm_msgs"}{$act.confirm}{/tr}');{/if}{/if}" title="{tr}{$act.title}{/tr}"><b class="icon {$act.icon}"></b>{eval var=$act.text}</a>
	{/foreach}{/if}|||
{/foreach}
{/if}
{$table.total}