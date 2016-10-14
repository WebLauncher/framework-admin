
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