<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

				<td>
					{foreach item=opt from=$field.options key=kopt}
					<label><input type="radio" name="{$field.name}" id="field_id_{$field.name}_{$kopt}" value="{$opt[$field.field_value]}" {if isset($p.state[$field.name]) && $p.state[$field.name]==$opt[$field.field_value]} checked="checked"{else}{if $field.value>=0}{if $field.value==$opt[$field.field_value]} checked="checked"{/if}{else}{if $field.value==$field.default} checked="checked"{/if}{/if}{/if}/>{image title=$opt[$field.field_title] alt=$opt[$field.field_title]}{$opt[$field.field_image]}{/image}</label>
					{/foreach}
{if isset($field.validate)}
				{foreach item=msg from=$field.validate key=val}
				{validator form=$form.id field=$field.name rule=$val message=$msg}
				{/foreach}			
			{/if}
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
				</td>