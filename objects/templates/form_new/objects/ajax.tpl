<div id='{$field.name}_zone'></div>	
<script type="text/javascript">
	ajax_load('{$field.url}','{$field.bind}='+$("[name='{$field.bind}']").val(),'#{$field.name}_zone');
	jQuery("[name='{$field.bind}']").{$field.event}(function(){literal}{{/literal}
		ajax_load('{$field.url}','{$field.bind}='+$("[name='{$field.bind}']").val(),'#{$field.name}_zone');
	{literal}}{/literal})
</script>


