
					<select name="{$field.name}" id="field_id_{$field.name}">
						{if isset($field.default_show) && $field.default_show}
						<option value="{$field.default}">{$field.default_text}</option>
						{/if}
						{if isset($field.groups)}
						{foreach item=gr from=$field.groups}
						<optgroup label="{$gr.label}">
							{foreach item=opt from=$gr.options}
							<option value="{$opt.$field.option_field_value}" {if $p.state[$field.name]}{if $p.state[$field.name]==$opt[$field.option_field_value]} selected="selected" {/if}{else}{if isset($field.selected) && $field.selected==$opt[$field.option_field_value] || $field.value==$opt[$field.option_field_value]} selected="selected" {/if}{/if}>{$opt.$field.option_field_text}</option>
							{/foreach}
						</optgroup>
						{/foreach}
						{else}
						{foreach item=opt from=$field.options}
						<option value="{$opt[$field.option_field_value]}" {if isset($p.state[$field.name])}{if $p.state[$field.name]==$opt[$field.option_field_value]} selected="selected" {/if}{else}{if isset($field.selected) && $field.selected==$opt[$field.option_field_value] || $field.value==$opt[$field.option_field_value]} selected="selected" {/if}{/if}>{$opt[$field.option_field_text]}</option>
						{/foreach}
						{/if}
					</select>				