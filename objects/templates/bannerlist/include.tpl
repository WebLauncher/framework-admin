{php}
	global $dal;
	global $page;
	$dal->import('banners');
	$dal->import('banners_zones');
	$zone=$this->get_template_vars('zone');
	$this->assign('zone_name',$zone);
	$count=$this->get_template_vars('count');
	if($zone!="")
	{
	   
		if($dal)
		{
			$banners=$dal->banners->GetBannerList($zone,$count);
			$zone=$dal->banners_zones->GetCond("name='".$zone."'");

			$this->assign('banners',$banners);
			$this->assign('zone',$zone);
		}
	}
	
{/php}

<div class="clearfix {$list_class}" style="{$list_style}">
<center>
<ul style="list-style:none">
	{foreach item=banner from=$banners}
	<li style='overflow:hidden;{if $type==""} display:inline; {/if}{$banner_style}' class='{$banner_class} '>
		{if $banner}
		{if $banner.type.name=="image"}
			<a href="{$banner.link}" target="{$banner.target}">{image}{$banner.content}{/image}</a>
		{elseif $banner.type.name=="flash"}
				
			<div id='banner_{$banner.id}'>
				{$p.paths.root}{$banner.content}
			</div>
						<script type="text/javascript">
								swfobject.embedSWF("{$p.paths.root}{$banner.content}", "banner_{$banner.id}", "{$zone.width}", "{$zone.height}", "9.0.0","{$root_scripts}swfobject/expressInstall.swf","",{literal}{wmode:'transparent'}{/literal});
						</script>
		{elseif $banner.type.name=="script"}
			<script type="text/javascript">
				{$banner.content}
			</script>
		{elseif $banner.type.name=="text"}
			{$banner.content}
		{/if}
		{else}
			- banner zone ['{$zone_name}'] not found -
		{/if}
	</li>
	{/foreach}
</ul>

</center>
</div>