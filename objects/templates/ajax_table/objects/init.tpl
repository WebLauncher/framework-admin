<div class="clearfix">
<table cellpadding="0" cellspacing="0" id="j_{$table.id}">
</table>
</div>
{if !isset_or($tbl_empty)}
<script>


	$("#j_{$table.id}").gridview(
				{literal}
				{
				{/literal}
					text_search:'',
					sortby:'{$table.sort_by}',
					sortdir:'{$table.sort_dir}',
					text_search_portion:'{tr tags="table_content"} ca si portiune de text?{/tr}',
					text_no_rows:'{tr tags="table_content"}Nr randuri/pagina:{/tr}',
					text_pages_result:'{tr tags="table_content"}Rezultate: {/tr}',
					text_pages_to:'{tr tags="table_content"}pana la{/tr}',
					text_pages_of:'{tr tags="table_content"}din{/tr}',
					text_settings_dialog_title:'{tr tags="table_content"}Table Settings{/tr}',
					data_type:'{$table.data_type}',
					url:'{$current}?a={$table.update_action}',
					no_rows:{$p.page_offset},
					no_results:{$p.no_total_rows},
					current_page:{$p.session.pages[$table.id]|default:0},
					max_pages:20					
					{if isset_or($table.edit_link)!="none"}
					{literal}
					,rowclick:function(el){
					{/literal}					
						edit('{if !$table.edit_link}{$current}{else}{$table.edit_link}{/if}',$($(el).children()[0]).html());
					{literal}
					}
					{/literal}
					{/if}
		{literal}});

{/literal}

</script>
{/if}