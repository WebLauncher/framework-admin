		{if count($form.zones)>1}
		<div id="zone_{$smarty.foreach.zones.index}"></div>
		<div class="zoneslist">
		<strong>{tr}Jump to:{/tr}</strong> 
		{foreach item=zone1 from=$form.zones name=zones1 key=zname}
		<a href="#" onclick="$('#content').scrollTo('#zone_{$smarty.foreach.zones1.index}', 800);">{$zname}</a>
		{/foreach}
		</div>
		{/if}	
		<div>
		<fieldset class="formview">
			<div class="clearfix title">
				<div class="floatleft">{tr tags="form_titles"}{$ztitle}{/tr}</div>
				{if count($form.zones)>1}
				<div class="floatright">
					<a href="#" onclick="$('#content').scrollTo('#form_{$form.id}', 800);" title="{tr tags="links"}jump to top{/tr}"><img src="{$skin_images}newicons/up_16.png"/></a>
				</div>
				{/if}
			</div>
			{if isset($zone.description)}
			<div class="notice">
				{tr}{$zone.description}{/tr}
			</div>
			{/if}
			{if isset($zone.cols)}
			{foreach item=col from=$zone.cols}
			<div class="floatleft" style="width:{$col.width};">
				<div class="column">
				{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form/fields_view.tpl{/capture}				
				{include file=$path_inc zone=$col form=$form}
				</div>
			</div>
			{/foreach}
			{else}
			{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form/fields_view.tpl{/capture}
			{include file=$path_inc zone=$zone form=$form}	
			{/if}
		</fieldset>
		</div>