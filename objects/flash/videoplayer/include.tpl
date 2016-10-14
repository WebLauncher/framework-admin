<div id="video_player"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div>

<script type="text/javascript">
		{literal}
		var flashvars = {
		{/literal}
		  file: "{$video}",
		  image: "{$image}"
		  {if $videowidth}
		  ,width: {$videowidth}
		  {/if}
		  {if $videoheight}
		  ,height: {$videoheight}
		  {/if}
		  {if $smoothing}
		  ,smoothing:"{$smoothing}"
		  {/if}
		  {if $bufferlength}
		  ,bufferlength:{$bufferlength}
		  {/if}
		{literal}
		};
		var params = {
		{/literal}
		  menu: "false",
		  allowfullscreen:"{$fullscreen|default:'true'}",
		  allowscriptaccess:"always",
		  wmode:"transparent"
		{literal}
		};
		var attributes = {
		{/literal}
		  id: "myDynamicContent",
		  name: "myDynamicContent"
		{literal}
		};
		{/literal}
		
		swfobject.embedSWF("{$root}objects/flash/videoplayer/player.swf", "video_player", "{$width|default:320}", "{$height|default:240}", "9.0.0","{$root_scripts}swfobject/expressInstall.swf", flashvars, params, attributes);
</script>
