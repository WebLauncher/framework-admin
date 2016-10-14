{php}
	$id=$this->get_template_vars('banner_id');
	global $dal;
	global $page;
	$dal->import('banners');
	$dal->import('banners_zones');
	if($id)
	{	
		$banner=$dal->banners->Get($id);
		$zone=$dal->banners_zones->get($banner['zone_id']);
				
		$this->assign('banner',$banner);
		$this->assign('zone',$zone);
	}
	else{
		$zone=$this->get_template_vars('zone');
		$this->assign("zone_name",$zone);
		if($zone!="")
		{		   
			if($dal)
			{
				$banner=$dal->banners->GetBanner($zone);
				$zone=$dal->banners_zones->get($banner['zone_id']);
				
				$this->assign('banner',$banner);
				$this->assign('zone',$zone);
			}
		}
	}
{/php}
<div style='overflow:hidden;width:{$zone.width}px;height:{$zone.height}px'{if $class} class='{$class}'{/if}>
	{if isset($banner.id)}
		{if $banner.type.name=="image"}
			<center>
			<a href="{$banner.link}" target="{$banner.target}">{image}{$banner.content}{/image}</a>
			</center>
		{elseif $banner.type.name=="flash"}
			<div id='banner_{$banner.id}'>
					{$p.paths.root}{$banner.content}
				</div>
							{literal}
							<script type="text/javascript">
									swfobject.embedSWF("{$p.paths.root}{$banner.content}", "banner_{$banner.id}", "{$zone.width}", "{$zone.height}", "9.0.0","{$root_scripts}swfobject/expressInstall.swf","",{wmode:'transparent'});
							</script>
							{/literal}
		{elseif $banner.type.name=="script"}
			<script type="text/javascript">
				{$banner.content}
			</script>
		{elseif $banner.type.name=="text"}
			{$banner.content}
		{elseif $banner.type.name=="html"}
	        {$banner.content}
		{/if}    
	{else}
		{if $p.live}<div class="banner">{tr}Zona publicitara{/tr}</div>{else}- banner zone ['{$zone_name}'] not found -{/if}
	{/if}
</div>