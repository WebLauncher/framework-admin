{foreach item=opt from=$field.options key=kopt}
<label><input type="radio" name="{$field.name}" id="field_id_{$field.name}_{$kopt}" value="{$opt}" {if isset($p.state[$field.name]) && $p.state[$field.name]==$opt} checked="checked"{else}{if isset($field.value) && $field.value==$opt} checked="checked"{elseif isset($field.default) && $opt==$field.default} checked="checked"{/if}{/if}/>{$kopt}</label>
{/foreach}
					