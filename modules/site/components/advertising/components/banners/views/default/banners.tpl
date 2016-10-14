<div class="clearfix">
	{if !$p.actions[0]}
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=add_zone')"><b class="icon icon-plus"></b>{tr tags="buttons"}Adauga zona{/tr}</button>
	{elseif $p.actions[0]=="banners"}
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=add_banner:{$p.actions[1]}')"><b class="icon icon-plus"></b>{tr tags="buttons"}Adauga banner{/tr}</button>
	{/if}
	{if $p.actions[0]=="banners"}
	<label class="floatright">Alege zona:<select name="zone" onchange="go_to('{$current}?a=banners:'+this.value)">			
			{foreach item=z from=$zones}
			<option{if $z.id==$p.actions[1]} selected="selected"{/if} value="{$z.id}">{$z.name} ({$z.width}px / {$z.height}px)</option>
			{/foreach}
	</select></label>
	{/if}
</div>
{if !$p.actions[0] || $p.actions[0]=="banners"}	
	
	{include file=$p.objects.templates.ajax_table table=$table p=$p}
{else}
	{if $p.actions[0]=="add"}
	<fieldset class="formview">
		<div class="clearfix title">{tr tags="forms_legends"}Alegeti zona de banner{/tr}</div>
		<label>{tr tags="forms_labels"}Zona:{/tr}<select name="zone" onchange="go_to('{$current}?a=add_banner:'+this.value)">
			<option value="0">---------------</option>
			{foreach item=z from=$zones}
			<option value="{$z.id}">{$z.name} ({$z.width}px / {$z.height}px)</option>
			{/foreach}
		</select></label>
	</fieldset>
	{else}
	{if $p.actions[0]=="edit"}
	<fieldset class="formview">
		<div class="clearfix title">{tr tags="forms_legends"}Vizualizare{/tr}</div>
		<div style="text-align: center;">
		<table style="width:auto;margin:auto;">
			<tr><td>{include file=$p.objects.templates.banner banner_id=$p.actions[2]}</td></tr>
		</table>
		</div>
	</fieldset>
	{/if}
	{include file=$p.objects.templates.form form=$form p=$p}	
	{/if}	
{/if}
