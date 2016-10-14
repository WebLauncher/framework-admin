<th><label>{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

</th><td><a href="{$field.href}" id="field_id_{$field.name}" target="{$field.target|default:"_self"}">{$field.value}</a>
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
</td>