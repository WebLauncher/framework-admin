					{capture assign=value}{if isset($p.state[$field.name])}{$p.state[$field.name]|stripslashes}{else}{$field.value}{/if}{/capture}
					{capture assign=textarea_id}field_id_{$field.name}{/capture}
					{capture assign=autostart}{$field.autostart|default:0}{/capture}														
					{include file=$p.objects.editors.ckeditor textarea_id=$textarea_id textarea_name=$field.name width="98%" height="100px" textarea_value=$value autostart=$autostart}