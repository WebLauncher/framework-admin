{if $field.url || $field.value}
				<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

				</th>
				<td>
					<img src="{$field.url}" id="field_id_{$field.name}" alt="{$field.value}" title="{$field.value}"/>
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
				</td>			
				{/if}