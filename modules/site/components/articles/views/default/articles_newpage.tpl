{if $p.actions[0]==""}
	<div class="clearfix box">
	{if $p.session.articles.parent_id}
	<button type="button" class="floatleft"  onclick="admin_go_to('{$root_module}articles/?type=newpage&article={bind table=$p.tables.tbl_articles get_field='parent_id'}{$p.session.articles.parent_id}{/bind}')"><b class="icon icon-arrow_left"></b><span>{tr tags="buttons"}Back{/tr}</span></button>
	{/if}
	{if $folder.others.allow_subdirectories || !$folder.id}
	<button type="button" class="floatleft" onclick="go_to('{$root_component}?a=new_folder:{$p.session.articles.parent_id|default:0}')"><b class="icon icon-folder"></b><span>New folder</span></button>
	{/if}
	{if $p.subquery[2]=="news" || $p.subquery[2]=="events" || $p.subquery[2]=="newpage"}
	<button type="button" class="floatleft positive" onclick="go_to('{$root_component}?a=add:{$p.session.articles.parent_id|default:0}')"><b class="icon icon-plus"></b><span>New article</span></button>
	{/if}
	</div>
	<div class="clearfix folderview">
		<div class="floatleft list">
		{foreach item=f from=$folders}
		<div id="folder_{$f.id}" class="floatleft folder" onmouseover="$('#selected_folder .content').html($('#folder_{$f.id} .description').html());">
			
				<div class="wrapper">
					<a href="#" onclick="admin_go_to('{$root_module}articles/?type=newpage&article={$f.id}')" class="clearfix">
						<div class="image"></div>
						<div class="title">
							{$f.title}					
						</div>
					</a>
					<div class="actions clearfix">
						<a href="{$root_module}articles/?type=newpage&a=delete:{$f.id}" class="floatleft" onclick="return confirm('Are you sure you want to delete this?');" title="Delete directory"><img src="{$skin_images}newicons/trash_16.png"></a>
						<a href="{$root_module}articles/?type=newpage&a=edit_folder:{$f.id}" class="floatleft" title="Edit directory"><img src="{$skin_images}newicons/pencil_16.png"></a>
					</div>
					<div class="description">
						<a href="{$root_module}articles/?type=newpage&article={$f.id}"><strong>{$f.title}</strong></a><br/>
						<small>{$f.no_directories} directories, {$f.no_articles} articles</small><br/>
						<a href="{$root_module}articles/?type=newpage&a=edit_folder:{$f.id}">edit</a>&nbsp;<a href="{$root_module}articles/?type=newpage&a=delete:{$f.id}" onclick="return confirm('Are you sure you want to delete this?');">delete</a>			
					</div>
				</div>
			
		</div>
		{/foreach}
		{foreach item=f from=$articles}
		<div id="file_{$f.id}" class="floatleft file" onmouseover="$('#selected_folder .content').html($('#file_{$f.id} .description').html());">
			<div class="wrapper">
				<a href="{$root_module}articles/?type=newpage&a=edit:{$f.id}" class="clearfix">
					<div class="image">
						<div class="subicon"><img src="{$skin_images}languages/{$l.code|lower}.png" alt="{$l.valoare}"/></div>
					</div>
					<div class="title">
						{$f.title}
					</div>
				</a>
				<div class="actions clearfix">
					<a href="{$root_module}articles/?type=newpage&a=delete:{$f.id}" onclick="return confirm('Are you sure you want to delete this?');" class="floatleft" title="Delete article"><img src="{$skin_images}newicons/trash_16.png"></a>
					<a href="{$root_module}articles/?type=newpage&a=edit:{$f.id}" class="floatleft" title="Edit article"><img src="{$skin_images}newicons/pencil_16.png"></a>
					{if !$f.is_translation}<a href="#" onclick="admin_go_to('{$root_module}articles/?type=newpage&article={$f.id}');" class="floatleft" title="View translations"><img src="{$skin_images}newicons/briefcase_16.png"></a>{/if}
				</div>
				<div class="description">
					<a href="{$root_module}articles/?type=newpage&a=edit:{$f.id}"><strong>{$f.title}</strong></a> (<a href="{$root_module}articles/?type=newpage&a=edit:{$f.id}">edit</a> | <a href="{$root_module}articles/?type=newpage&a=delete:{$f.id}" onclick="return confirm('Are you sure you want to delete this?');">delete</a>)<br/>
					<small>Language: <img src="{$skin_images}languages/{$l.code|lower}.png" alt="{$l.valoare}"/></small><br/>
					{if $f.image_id}
					<div>
						{image alt="No image" width="120" height="240" resize="true"}{$f.image_id}{/image}
					</div>
					{/if}	
					{if count($f.translations)}
					<small>Translations:
						{foreach item=tran from=$f.translations} 
							<a href="{$root_module}articles/?type=newpage&a=edit:{$tran.id}" title="{tr}Edit translation{/tr}">{bind table=$p.tables.tbl_locales get_field="code" assign="l_code"}{$tran.language_id}{/bind}<img src="{$skin_images}languages/{$l_code|lower}.png" alt="{$l_code}"/></a>
						{/foreach}
					</small><br/>
					{/if}					
				</div>
			</div>
		</div>
		{/foreach}
		{if !$folder.is_directory && !$folder.is_translation}
		{foreach item=f from=$folder.translatations_available}
		<div id="file_{$folder.id}_{$f}" class="floatleft file" onmouseover="$('#selected_folder .content').html($('#file_{$folder.id}_{$f} .description').html());">
			<a href="{$root_module}articles/?type=newpage&a=add_translation_save:{$folder.id}&language_id={$f}" class="clearfix">
				<div class="image">
					<div class="subicon">{image}{bind table=$p.tables.tbl_locales get_field="image_id"}{$f}{/bind}{/image}</div>
				</div>
				<div class="title">
					{$folder.title|truncate:25:"..."}
				</div>
			</a>
			<div class="actions clearfix">				
				<div><small>{tr}Click to add translation{/tr}</small></div>
			</div>
			<div class="description">
				{tr}Click to add translation.{/tr}
			</div>
		</div>
		{/foreach}		
		{/if}
		</div>
		<div class="floatright details">
			<div class="panel">			
				<div class="title">{if $folder.is_directory}{tr}Current directory{/tr}{else}{tr}Current article{/tr}{/if}</div>
				<div class="content">
					<strong>{$folder.title}</strong> {if $folder.id}(<a href="{$root_module}articles/?type=newpage&a=edit_folder:{$folder.id}">edit</a>){/if}<br/>					
					<small>{if $folder.is_directory}{$folder.no_directories} directories, {$folder.no_articles} articles{else}{$folder.translations|@count} {tr}traduceri{/tr}{/if}</small><br>
				</div>
			</div>
			<div class="panel" id="selected_folder">			
				<div class="title">Selected directory/article</div>
				<div class="content">
					None
				</div>
			</div>
		</div>
	</div>
{/if}