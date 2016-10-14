<div class="clearfix">
	<button type="button" class="floatleft j_button j_button_positive" preicon="ui-icon-plus" onclick="go_to('{$current}?a=add')">{tr tags="buttons"}Add currency{/tr}</button>
</div>
{include file=$p.objects.templates.ajax_table table=$table p=$p}