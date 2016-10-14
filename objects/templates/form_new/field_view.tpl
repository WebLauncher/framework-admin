<div class="clearfix field_{$field.type}"{if $field.type=='hidden'} style='display:none;'{/if} id="field_view_{$field.name}">
	<div class="clearfix field">
		<div class="field_label">
			<span class="field_description_icon">
			<label for="field_id_{$field.name}">{tr tags="form_labels"}{$field.label}{/tr}</label>		
			<span class="help_icon">	
				{if isset_or($field.description)}
				<img src="{$skin_images}newicons/info_16.png" alt="?" title="{tr tags="form_descriptions"}{$field.description}{/tr}"/>
				{/if}
			</span>
			<span class="required{if isset($field.validate) && in_array('required',array_keys($field.validate))} required_active{/if}"></span>
			</span>			
		</div>
		<div class="field_input">
			{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/objects/{$field.type}.tpl{/capture}
			{include file=$path_inc field=$field form=$form}
			<div class="field_validation">
				{if isset($field.validate)}
				{foreach item=msg from=$field.validate key=val}
				{validator form=$form.id field=$field.name rule=$val message=$msg}
				{/foreach}			
				{/if}
			</div>
		</div>
	</div>	
	{if isset($field.script)}
	<script type="text/javascript" charset="utf-8">
		{$field.script}
	</script>
	{/if}
</div>