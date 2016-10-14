<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}
</th><td>
	<div id='{$field.name}_zone'>
		
	</div>
{if isset($field.validate)}
				{foreach item=msg from=$field.validate key=val}
				{validator form=$form.id field=$field.name rule=$val message=$msg}
				{/foreach}			
			{/if}
<script type="text/javascript">
	ajax_load('{$field.url}','{$field.bind}='+$("[name='{$field.bind}']").val(),'#{$field.name}_zone');
	jQuery("[name='{$field.bind}']").{$field.event}(function(){literal}{{/literal}
		ajax_load('{$field.url}','{$field.bind}='+$("[name='{$field.bind}']").val(),'#{$field.name}_zone');
	{literal}}{/literal})
</script>
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
</td>

