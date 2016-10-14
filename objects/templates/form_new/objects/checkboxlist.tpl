
					{capture assign="fieldname"}{$field.name}[]{/capture}
					{foreach item=opt from=$field.options key=kopt}					
					<label><input type="checkbox" name="{$field.name}[]" id="field_id_{$field.name}_{$kopt}" value="{$opt.id}" {if isset_or($opt.checked)}checked="checked"{else}{if isset_or($smarty.capture.namekey) && isset_or($p.state[$smarty.capture.namekey])} checked="checked"{/if}{/if}/>{$opt.name}</label>
					{/foreach}					
					