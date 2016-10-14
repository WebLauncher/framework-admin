		{if !isset($h.hidden) || !$h.hidden}	
			{if isset($h.strong) && $h.strong}<strong>{/if}	
			{if isset($h.small) && $h.small}<small>{/if}		
			{if isset($h.bind) && $h.bind}
				{capture assign=bind_field}{if isset($h.bind.field)}{$h.bind.field}{/if}{/capture}
				{if isset($h.image) && $h.image}					
					{image}{bind table=$h.bind.table get_field=$h.bind.get_field field=$bind_field}{$o[$h.col].value}{/bind}{/image}					
				{else}
					{bind table=$h.bind.table get_field=$h.bind.get_field field=$bind_field}{$o[$h.col].value}{/bind}
				{/if}
			{elseif isset($h.image) && $h.image}
				{if $o[$h.col].value>0}
					{capture assign=img_width}{$h.width|default:''}{/capture}
					{capture assign=img_height}{$h.height|default:''}{/capture}
					{image width=$img_width height=$img_height}{$o[$h.col].value}{/image}
				{else}
					{tr tags="table_nocontent"}-imagine lipsa-{/tr}
				{/if}
			{elseif isset($h.date) && $h.date}
				{capture assign=date}{eval var=$o[$h.col].value}{/capture}
				{$date}
			{elseif isset($h.array) && $h.array}
				{foreach item=obj from=$o[$h.col].value key=key}
				{eval var=$h.eval}
				{foreachelse}
				{tr tags="table_content"}{$h.no_items|default:"nu sunt valori"}{/tr} 
				{/foreach}
			{elseif isset($h.eval) && $h.eval}
				{eval var=$h.eval}
			{elseif isset($h.active) && $h.active}
				{if isset($h.action) && $h.action}				
					<a href="#" onclick="ajax_load('{$current}?a={$h.action}:{$o.id.value}:{if $o[$h.col].value==0}1{else}0{/if}','','','$(\'#j_{$table.id}\').gridview(\'refresh\');');return false;" title="{if $o[$h.col].value==1}{tr tags="buttons"}Dezactiveaza{/tr}{else}{tr tags="buttons"}Activeaza{/tr}{/if}"><b class="icon {if $o[$h.col].value==1}icon-check{else}icon-ban{/if}"></b></a>				
				{else}
					<a href="#" onclick="ajax_active('{$current}',{$o.id.value},{if $o[$h.col].value==0}1{else}0{/if},'$(\'#j_{$table.id}\').gridview(\'refresh\');');return false;" title="{if $o[$h.col].value==1}{tr tags="buttons"}Dezactiveaza{/tr}{else}{tr tags="buttons"}Activeaza{/tr}{/if}"><b class="icon {if $o[$h.col].value==1}icon-check{else}icon-ban{/if}"></b></a>
				{/if}
			{elseif isset($h.order) && $h.order}
				{if $o[$h.col].value>0}
					<a href="#" onclick="ajax_order('{$current}',{$o.id.value},-1,'$(\'#j_{$table.id}\').gridview(\'refresh\');');return false;" title="-1"><b class="icon icon-arrow-up "></b></a>
				{/if}
				{if $o[$h.col].value+1 < $table.total}
					<a href="#" title="+1" onclick="ajax_order('{$current}',{$o.id.value},1,'$(\'#j_{$table.id}\').gridview(\'refresh\');');return false;"><b class="icon icon-arrow-down "></b></a>
				{/if}
			{else}
				{if isset($o[$h.col].value)}{$o[$h.col].value}{/if}
			{/if}
			{if isset($h.small) && $h.small}</small>{/if}
			{if isset($h.strong) && $h.strong}</strong>{/if}
			{assign var=passed value=1}	
			{if !$show_end}###{/if}	
		{/if}	
		
