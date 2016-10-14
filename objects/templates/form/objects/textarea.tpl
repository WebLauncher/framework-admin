<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}
</th><td><textarea id="field_id_{$field.name}" name="{$field.name}" class="textarea"  {if $field.cols}cols="{$field.cols}"{/if} {if $field.rows}rows="{$field.rows}"{/if}>{$p.state[$field.name]|default:$field.value}</textarea>
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