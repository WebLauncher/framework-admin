<div class="clearfix panel">
		<div class="clearfix">
			<div class="notice clearfix">
				{tr tags="descriptions"}{$menu.description}{/tr}
			</div>
			<div class="clearfix">
				<div class="floatleft" style="width:30%;">
					<ul class="pagemenuv" descriptor="desc">
						{foreach item=m from=$menu.submenu.item}
						<li><a href="{$root_module}{$m.link}"><b class="icon {$m.icon}"></b><span>{tr tags="menu"}{$m.name}{/tr}</span></a>
							<div style="display:none">
								<strong>{tr tags="menu"}{$m.name}{/tr}</strong><br/>
								<div class="clearfix">{tr tags="descriptions"}{$m.description}{/tr}</div>
								{if $menu.levels>1 && count($m.submenu.item)}
								<div class="clearfix">
									<ul class="pagemenu">
										{foreach item=m1 from=$m.submenu.item}
										<li><a href="{$root_module}{$m1.link}"><b class="icon {$m1.icon}"></b><span>{tr tags="menu"}{$m1.name}{/tr}</span></a>
											<div style="display:none">{tr tags="descriptions"}{$m1.description}{/tr}</div>				
										</li>	
										{/foreach}			
									</ul>
								</div>
								{/if}
							</div>				
						</li>			
						{/foreach}						
					</ul>
				</div>
				<div class="floatright" style="width: 70%;">
					<div class="pagemenudesc" id="desc" style="display:none;">						
					</div>
				</div>
			</div>
		</div>
</div>