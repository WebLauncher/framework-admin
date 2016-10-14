<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}
</th><td><input type="text" name="{$field.name}" value="{if $p.state[$field.name]}{$p.state[$field.name]}{else}{$field.value}{/if}" id="field_id_{$field.name}" class="text"/>
{if isset($field.validate)}
				{foreach item=msg from=$field.validate key=val}
				{validator form=$form.id field=$field.name rule=$val message=$msg}
				{/foreach}			
			{/if}
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
{if isset($field.script)}
<script type="text/javascript" charset="utf-8">
	{$field.script}
</script>
{/if}
</td>