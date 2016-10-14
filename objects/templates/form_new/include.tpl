<script type="text/javascript" src="{$root}objects/editors/ckeditor/ckeditor.js"></script>
<form action="{if $form.action}{$form.action}{else}{$current}{/if}" name="{$form.name}" id="{$form.id}" method="{$form.method}" enctype="multipart/form-data">
<div class="wbl_form" id="form_{$form.id}">
{if count($p.errors)}
<div class="error">	
	{tr}Errors that have been found:{/tr}	
	<ul>
		{foreach item=err from=$p.errors key=fname}
		<li><a href="#" onclick="$('#content').scrollTo('[name={$fname}]', 800);$('[name={$fname}]').focus().select()"><b class="icon icon-cross_octagon"></b>{$err}</a></li>
		{/foreach}
	</ul>
</div>
{/if}
<div {if isset($form.width)}style="width:{$form.width}"{/if} id="acordeon_{$form.id}" class="clearfix">	
	{foreach item=row from=$form.layout.rows}
	<div>
		{capture assign=path_inc}{$p.paths.root_objects_inc}templates/form_new/row_view.tpl{/capture}
		{include file=$path_inc row=$row form=$form}
	</div>
	{/foreach}	
</div>
</div>
<div class="clearfix form_fixed_bar">
			{if isset_or($form.btn_submit_text)}
			<button class="floatleft positive" type="submit" onclick="show_submit_loader('Please wait while validating...');if($('#{$form.id}').valid())show_submit_loader('Please wait while processing...');else hide_submit_loader();"><b class="icon icon-disk"></b>{tr}{$form.btn_submit_text}{/tr}</button>
			{/if}
			{if isset_or($form.btn_submit_return_text)}
			<input type="hidden" name="return" value="" id="input_return"/>
			<button class="floatleft" type="submit" onclick="show_submit_loader('Please wait while validating...');if($('#{$form.id}').valid()){literal}{{/literal}$('#input_return').val(1);show_submit_loader('Please wait while processing...');{literal}}{/literal}else hide_submit_loader();"><b class="icon icon-disk"></b>{tr}{$form.btn_submit_return_text}{/tr}</button>
			{/if}
			{if isset_or($form.btn_cancel_text)}
			<button class="floatright negative" type="button" onclick="go_to('{if $form.btn_cancel_link}{$form.btn_cancel_link}{else}{$p.history[0]}{/if}')"><b class="icon icon-ban"></b>{tr}{$form.btn_cancel_text}{/tr}</button>
			{/if}
			{if isset_or($form.btn_reset_text)}
			<button class="floatright" type="reset"><b class="icon icon-backward"></b>{tr}{$form.btn_reset_text}{/tr}</button>
			{/if}
		</div>
</form>
