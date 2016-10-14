{if isset($row.cols)}
{foreach item=col from=$row.cols}		
<div class="floatleft" style="width:{$col.width};">
	{foreach item=zone from=$col.zones key=ztitle name=zones}	
	{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form/zone.tpl{/capture}
	{include file=$path_inc zone=$zone form=$form}	
	{/foreach}
</div>
{/foreach}
{else}
{foreach item=zone from=$row.zones key=ztitle name=zones}
{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form/zone.tpl{/capture}
{include file=$path_inc zone=$zone form=$form ztitle=$ztitle}	
{/foreach}
{/if}