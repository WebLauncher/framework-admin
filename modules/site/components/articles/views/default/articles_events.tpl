	<div class="panel">
				<div class="content" style="overflow:hidden;">
					<div class="clearfix panel" style="background:#fff;">
						<div class="floatleft">
						{if $p.session.articles.parent_id}
						<a href="#" onclick="admin_go_to('{$current}?article={bind table=$p.tables.tbl_articles get_field='parent_id'}{$p.session.articles.parent_id}{/bind}')" style="border-right:1px solid #ddd; margin-right:10px;"><b class="icon icon-arrow_left"></b></a>
						{/if}
						{foreach item=pa from=$cpath name="folders"}
							{if !$smarty.foreach.folders.last}
							<a href="#" onclick="admin_go_to('{$current}?article={$pa}')">{if $pa}{bind table=$p.tables.tbl_articles get_field='title'}{$pa}{/bind}{else}Root directory{/if}</a> {if !$smarty.foreach.folders.last}>{/if}
							{/if} 
						{/foreach}
						<strong>{$folder.title}</strong> {if $folder.id}(<a href="{$current}?a=edit_folder:{$folder.id}">edit</a>){/if} (<small>{$folder.no_directories} directories, {$folder.no_articles} events</small>)
						</div>						
					</div>
					<div class="clearfix">
						<div class="floatleft">
						{if $folder.others.allow_subdirectories || !$folder.id}
						<button type="button"  onclick="go_to('{$root_component}?a=new_folder:{$p.session.articles.parent_id|default:0}')"><b class="icon icon-folder"></b><span>Add directory</span></button>
						{/if}
						<button type="button" class="positive" onclick="go_to('{$root_component}?a=add:{$p.session.articles.parent_id|default:0}')"><b class="icon icon-plus"></b><span>Add event</span></button>
						</div>
						<div class="floatright">
							<button type="button" onclick="admin_go_to('{$current}?a=show_past&article={$p.session.articles.parent_id|default:0}');">{if $p.session.articles.show_past}Hide{else}Show{/if} past events</button>		
							<button type="button"{if !$display_type} class="selected"{/if} onclick="admin_go_to('{$current}?a=display_mode:&article={$p.session.articles.parent_id|default:0}');">Files view</button>
							<button type="button"{if $display_type=="months"} class="selected"{/if} onclick="admin_go_to('{$current}?a=display_mode:months&article={$p.session.articles.parent_id|default:0}');">Months view</button>		
						</div>
					</div>
				</div>
	</div>
	
	<div class="clearfix folderview">
		<div class="floatleft list">
		{if $display_type==""}
		{foreach item=f from=$folders}
		<div id="folder_{$f.id}" class="floatleft folder" onmouseover="$('#selected_folder .content').html($('#folder_{$f.id} .description').html());">
			<div class="wrapper">
				<a href="#" onclick="admin_go_to('{$current}?article={$f.id}')" class="clearfix">
					<div class="image"></div>
					<div class="title">
						{$f.title}					
					</div>
				</a>
				<div class="actions clearfix">
					<a href="{$current}?a=edit_folder:{$f.id}" class="floatleft" title="Edit directory"><img src="{$skin_images}newicons/pencil_16.png"></a>
				</div>
				<div class="description">
					<a href="{$current}?article={$f.id}"><strong>{$f.title}</strong></a><br/>
					<small>{$f.no_directories} directories, {$f.no_articles} events</small><br/>
					<a href="{$current}?a=edit_folder:{$f.id}">edit</a>&nbsp;
				</div>
			</div>
		</div>
		{/foreach}
		{foreach item=f from=$articles}
		<div id="file_{$f.id}" class="floatleft file" onmouseover="$('#selected_folder .content').html($('#file_{$f.id} .description').html());">
			<div class="wrapper">
				<a href="{$current}?a=edit:{$f.id}" class="clearfix">
					<div class="image event">
						<div class="subicon">
							{if !$f.expired}<b class="icon icon-data"></b>{else}<b class="icon icon-stop"></b>{/if}					
							{if $f.others.product_id}<b class="icon icon-coins"></b>{/if}
						</div>
					</div>
					<div class="title">
						{$f.title}
					</div>
				</a>
				<div class="actions clearfix">
					<a href="{$current}?a=edit:{$f.id}" class="floatleft" title="Edit event"><img src="{$skin_images}newicons/pencil_16.png"></a>
					{if $folder.others.has_payment}
				    <a href="{$root}admin/participants/?event={$f.id}" class="floatleft" title="Event participants"><img src="{$skin_images}newicons/user_16.png"></a>
				    {/if}
				</div>
				<div class="description">
					<a href="{$current}?a=edit:{$f.id}"><strong>{$f.title}</strong></a> (<a href="{$current}?a=edit:{$f.id}">edit</a>)<br/>
					<small>Start: <strong>{$f.others.start_date|date_format:"%B %e, %Y %I:%M %p"}</strong></small><br/>
					<small>End: <strong>{$f.others.end_date|date_format:"%B %e, %Y %I:%M %p"}</strong></small><br/>
					<small>Location: <strong>{$f.others.address}, {$f.others.city}, {$f.others.state}, {$f.others.country}, {$f.others.zip}</strong></small><br/>
					<small>Language: {image title=$l.valoare alt=$l.valoare}{bind table=$p.tables.tbl_locales get_field="image_id"}{$f.language_id}{/bind}{/image}</small><br/>
					{if $f.image_id}
					<div>
						{image alt="No image" width="120" height="240" resize="true"}{$f.image_id}{/image}
					</div>
					{/if}						
				</div>
			</div>
		</div>
		{/foreach}		
		{elseif $display_type=="months"}
		{if count($folders)}
		<div class="clearfix subtitle">
			Directories
		</div>
		{/if}
		<div class="clearfix">			
		{foreach item=f from=$folders}
		<div id="folder_{$f.id}" class="floatleft folder" onmouseover="$('#selected_folder .content').html($('#folder_{$f.id} .description').html());">
			<div class="wrapper">
				<a href="#" onclick="admin_go_to('{$current}?article={$f.id}')" class="clearfix">
					<div class="image"></div>
					<div class="title">
						{$f.title}					
					</div>
				</a>
				<div class="actions clearfix">
					<a href="{$current}?a=edit_folder:{$f.id}" class="floatleft" title="Edit directory"><img src="{$skin_images}newicons/pencil_16.png"></a>
				</div>
				<div class="description">
					<a href="{$current}?article={$f.id}"><strong>{$f.title}</strong></a><br/>
					<small>{$f.no_directories} directories, {$f.no_articles} events</small><br/>
					<a href="{$current}?a=edit_folder:{$f.id}">edit</a>&nbsp;			
				</div>
			</div>
		</div>
		{/foreach}
		</div>
		{if count($articles)}
		<div class="clearfix">		
		{foreach item=f from=$articles}
		{capture assign="ev_month"}{$f.others.start_date|date_format:"%B %Y"}{/capture}
		{if $current_month!=$ev_month}
		</div>
		<div class="clearfix subtitle">
			{$ev_month}
		</div>
		<div class="clearfix">
		{/if}
		<div id="file_{$f.id}" class="floatleft file" onmouseover="$('#selected_folder .content').html($('#file_{$f.id} .description').html());">
			<a href="{$current}?a=edit:{$f.id}" class="clearfix">
				<div class="image event">
					<div class="subicon">{if !$f.expired}<b class="icon icon-data"></b>{else}<b class="icon icon-stop"></b>{/if}</div>
				</div>
				<div class="title">
					{$f.title}
				</div>
			</a>
			<div class="actions clearfix">
				<a href="{$current}?a=edit:{$f.id}" class="floatleft" title="Edit event"><img src="{$skin_images}newicons/pencil_16.png"></a>
				{if $folder.others.has_payment}
			    <a href="{$root}admin/participants/?event={$f.id}" class="floatleft" title="Event participants"><img src="{$skin_images}newicons/user_16.png"></a>
			   	{/if}
			</div>
			<div class="description">
				<a href="{$current}?a=edit:{$f.id}"><strong>{$f.title}</strong></a> (<a href="{$current}?a=edit:{$f.id}">edit</a>)<br/>
				<small>Start: <strong>{$f.others.start_date|date_format:"%B %e, %Y %I:%M %p"}</strong></small><br/>
				<small>End: <strong>{$f.others.end_date|date_format:"%B %e, %Y %I:%M %p"}</strong></small><br/>
				<small>Location: <strong>{$f.others.address}, {$f.others.city}, {$f.others.state}, {$f.others.country}, {$f.others.zip}</strong></small><br/>
				<small>Language: {image title=$l.valoare alt=$l.valoare}{bind table=$p.tables.tbl_locales get_field="image_id"}{$f.language_id}{/bind}{/image}</small><br/>
				{if $f.image_id}
				<div>
					{image alt="No image" width="120" height="240" resize="true"}{$f.image_id}{/image}
				</div>
				{/if}						
			</div>
		</div>		
		{capture assign="current_month"}{$ev_month}{/capture}		
		{/foreach}
		</div>
		{/if}	
		{/if}
		</div>
		
		<div class="floatright details">			
			<div class="panel" id="selected_folder">			
				<div class="title">Selected directory/event</div>
				<div class="content">
					None
				</div>
			</div>
			<div class="panel">			
				<div class="title">Legend</div>
				<div class="content">
					<table>
						<tr>
							<td><b class="icon icon-data"></b></td>
							<td>Upcoming event</td>
						</tr>
						<tr>
							<td><b class="icon icon-stop"></b></td>
							<td>Past event</td>
						</tr>
						<tr>
							<td><b class="icon icon-coins"></b></td>
							<td>Event linked to payment/registration</td>
						</tr>						
					</table>
				</div>
			</div>
		</div>
	</div>