<th><label>{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}
</th><td>
<div class="clearfix">
	<div  id="field_id_{$field.name}" class="floatleft" style="width:200px;">
		
	</div>
	<div class="floatleft" style="margin-left: 5px;">{$field.value} %</div>
</div>
<script type="text/javascript">
{literal}
	jQuery(function() {
		jQuery( "#field_id_{/literal}{$field.name}{literal}" ).progressbar({
			value: {/literal}{$field.value}{literal}
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