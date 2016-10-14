<input type="text" name="{$field.name}" value="{if $p.state[$field.name]}{$p.state[$field.name]}{else}{$field.value}{/if}" id="field_id_{$field.name}" class="text"/>
<script type="text/javascript">
		{literal}
	$(function() {
	$( "#id_{/literal}{$field.name}{literal}" ).autocomplete({
	source: {/literal}"{$field.url}"{literal},
	minLength: 2
	});
	});
	{/literal}
</script>
