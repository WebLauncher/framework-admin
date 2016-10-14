<!-- Page Content Zone [{$module}]-->
<div class="admin_content clearfix" id="page">
	{if $p.logged}
	<div id="logo">
		<div>
			<a href="{$root_module}"><img src="{$skin_images}logo.png" align="center" width="100%"/></a>
		</div>
	</div>
	{/if}
	<div class="header clearfix" id="header">
		<div class="clearfix">				
		{if $p.logged}
		<div class="floatleft">
			{include file=$p.objects.templates.menu menu=$admin_menu menu_id="topmenu"}			
		</div>
		<div class="floatright user_logged" id="user_logged"> 
			Welcome, <strong>{$p.user.firstname} {$p.user.lastname}</strong>! <a href="{$current}?a=logout"><i class="icon icon-sign-out"></i>Logout</a> 
		</div>		
		{/if}
		</div>		
	</div>
	<div class="page clearfix" id="main_content">
	{if $p.logged && $p.session.user_type=="admin" && $p.user.is_active}
		<div class="admin_page">
			<div id="content_header">
			<div class="clearfix title">
				<div class="floatleft">
				<h1>{tr tags="menu"}{$page_title}{/tr}{if $page_subtitle} - {$page_subtitle}{/if}</h1>	
				</div>
				{if isset($p.session.messages) && count($p.session.messages)}
				<div id="message_zone">	
						{messages class_error='error' class_success='success'}
						<div class="clearfix **class_type**">									
							**message**					
						</div>	
						{/messages}
				</div>
				{/if}
			</div>			
			{include file=$p.objects.admin.sitemap map=$page_map}
			</div>
			<div class="content" id="content">
			{$subpage}
			</div>
		</div>
		
	{else}
		
		{$page_content}
		
		{/if}
	</div>
</div>
{if $p.logged}
	{literal}
		<script type="text/javascript">			
			jQuery(function(){
				jQuery(window).bind('resize', function(){
					jQuery("#content").height(jQuery(window).height()-jQuery("#content_header").height()-jQuery("#header").height()-30);
					jQuery('.form_fixed_bar').width(jQuery("#content").width()-32);							
				});
				jQuery(window).trigger('resize');
			});
		</script>
	{/literal}
{else}
{literal}
	<script type="text/javascript">
	jQuery(function(){
			jQuery(window).bind('resize', function(){
				jQuery("#content").height(jQuery(window).height());	
				jQuery('#admin_login').position({
					of: jQuery( "#content" ),
					my: "center center",
					at: "center center",
				});								
			});
			jQuery(window).trigger('resize');	
			});
		</script>
	{/literal}
{/if}
<!-- Page Content Zone [{$module}] End-->