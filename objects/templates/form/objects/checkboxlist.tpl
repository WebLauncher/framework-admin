<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

				<td>
					{capture assign="fieldname"}{$field.name}[]{/capture}
					{foreach item=opt from=$field.options key=kopt}					
					<label><input type="checkbox" name="{$field.name}[]" id="field_id_{$field.name}_{$kopt}" value="{$opt.id}" {if isset_or($opt.checked)}checked="checked"{else}{if isset_or($smarty.capture.namekey) && isset_or($p.state[$smarty.capture.namekey])} checked="checked"{/if}{/if}/>{$opt.name}</label>
					{/foreach}					
					{if isset($field.validate)}
						{foreach item=msg from=$field.validate key=val}
						{validator form=$form.id field=$fieldname rule=$val message=$msg location=true}
						{/foreach}			
					{/if}
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
				</td>