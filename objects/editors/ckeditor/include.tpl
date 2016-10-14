
<textarea id="{$textarea_id}" name="{$textarea_name}" style="width:{$width};height:{$height};">{$textarea_value}</textarea>
{if !$autostart}		
<a href="#" onclick="start_editor('ckeditor','{$textarea_name}','');$(this).hide()" id="start_{$textarea_id}" title="{tr}Porneste editorul HTML{/tr}">{tr}Porneste editorul HTML{/tr}</a>
{/if}
{if $autostart}
<script type="text/javascript">
	start_editor('ckeditor','{$textarea_name}',{$full_page|default:'true'});
</script>
{/if}
