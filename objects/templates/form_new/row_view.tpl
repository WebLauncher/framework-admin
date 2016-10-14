{foreach item=col from=$row.cols}		
<div class="floatleft" style="width:{$col.width};">
	{foreach item=zone from=$col.content key=ztitle name=zones}	
	{if $zone.type=='row'}
	{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/{$zone.type}_view.tpl{/capture}
	{include file=$path_inc row=$zone.value form=$form}
	{elseif $zone.type=='zone'}
	{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/{$zone.type}_view.tpl{/capture}
	{include file=$path_inc zone=$zone.value form=$form}
	{elseif $zone.type=='field'}
	{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/{$zone.type}_view.tpl{/capture}
	{include file=$path_inc field=$zone.value form=$form}
	{/if}	
	{/foreach}	
</div>
{/foreach}
