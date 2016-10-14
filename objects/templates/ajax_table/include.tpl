{if !isset_or($table.data)}
{capture assign=path_inc}{$p.paths.root_objects_inc}templates/ajax_table/objects/init.tpl{/capture}
{include file=$path_inc table=$table}
{else}
{capture assign=field_path_inc}{$p.paths.root_objects_inc}templates/ajax_table/objects/field_value.tpl{/capture}
{capture assign=path_inc}{$p.paths.root_objects_inc}templates/ajax_table/objects/content_{$table.data_type}.tpl{/capture}
{include file=$path_inc table=$table}
{/if}	







