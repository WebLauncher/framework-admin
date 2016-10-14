		<div class="zone">
		<fieldset class="formview">
			<div class="clearfix title">
				<div class="floatleft">{tr tags="form_titles"}{$zone.name}{/tr}</div>				
			</div>
			{if isset($zone.description) && $zone.description}
			<div class="notice">
				{tr}{$zone.description}{/tr}
			</div>
			{/if}
			<div class="zone_content">
				{if isset($zone.content)}
				{foreach item=cont from=$zone.content}	
				{if $cont.type=='row'}
				{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/{$cont.type}_view.tpl{/capture}
				{include file=$path_inc row=$cont.value form=$form}			
				{elseif $cont.type=='field'}
				{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/{$cont.type}_view.tpl{/capture}
				{include file=$path_inc field=$cont.value form=$form}
				{/if}	
				{/foreach}
				{/if}
			</div>
		</fieldset>
		</div>