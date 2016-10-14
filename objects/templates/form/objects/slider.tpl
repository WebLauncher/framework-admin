<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

</th>
				<td>
					<div class="slider" style="width:98%;" id="field_id_{$field.name}_div" {if $field.min} min="{$field.min}"{/if}{if $field.max} max="{$field.max}"{/if}{if $field.step} step="{$field.step}"{/if}{if isset($p.state[$field.name]) && $p.state[$field.name]} value="{$p.state[$field.name]}"{else}{if $field.value} value="{$field.value}"{/if}{/if}></div>
					<input type="text" value="{if isset($p.state[$field.name]) && $p.state[$field.name]}{$p.state[$field.name]}{else}{$field.value}{/if}" readonly="readonly" name="{$field.name}" id="input_field_id_{$field.name}_div" class="text" style="width:30px;"/>
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