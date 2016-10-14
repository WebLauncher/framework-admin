
				{if count($states)}
				<select name="state" style="width:99%;">
					<option value=''>{tr}Choose one{/tr}</option>
					{foreach item=s from=$states}
					<option value='{$s.code}'{if $p.state.state==$s.code || (!isset($p.state.state) && $s.code==$default)} selected="selected"{/if}>{$s.valoare}</option>
					{/foreach}
				</select>
				{else}
				<input type="text" name="state" value="{$p.state.state|default:$default}"/>
				{/if}
			