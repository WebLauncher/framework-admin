<th><label for="{$field.name}">{tr tags="form_labels"}{$name}{/tr}</label>
{if isset($field.description)}
<div class="field_description">{tr tags="form_descriptions"}{$field.description}{/tr}</div>
{/if}

</th>
				<td>
					{capture assign=value}{if isset($p.state[$field.name])}{$p.state[$field.name]|stripslashes}{else}{$field.value}{/if}{/capture}
					{capture assign=textarea_id}field_id_{$field.name}{/capture}
					{capture assign=autostart}{$field.autostart|default:0}{/capture}														
					{include file=$p.objects.editors.ckeditor textarea_id=$textarea_id textarea_name=$field.name width="98%" height="100px" textarea_value=$value autostart=$autostart}
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