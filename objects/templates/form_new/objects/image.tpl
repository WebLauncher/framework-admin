{if $field.url || $field.value}
	<img src="{$field.url}" id="field_id_{$field.name}" alt="{$field.value}" title="{$field.value}"/>
{/if}