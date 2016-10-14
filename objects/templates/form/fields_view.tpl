<table cellspacing="0" cellspacing="0" border="0">
	{foreach item=field from=$zone.fields key=name}
	<tr class="field_{$field.type}{cycle values=", alt"}"{if $field.type=='hidden'} style='display:none;'{/if}>
		{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form/objects/{$field.type}.tpl{/capture}
		{include file=$path_inc name=$name field=$field form=$form}
	</tr>
	{/foreach}
</table>