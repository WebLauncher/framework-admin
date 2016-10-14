<table cellspacing="0" cellspacing="0" border="0">
						<tr>
							<td>{$article.images|@count|default:0} {tr}imagini adaugate{/tr}</td>
							<td><div class="clearfix">
								<button class="floatright" type="button" onclick="add_image({$article.id})"><b class="icon icon-plus"></b>Adauga imagine</button>
							</div></td>
						</tr>
						{foreach item=image from=$article.images}<tr>
						<td>{image alt="No image" width="120" height="240" resize="true"}{$image.image_id}{/image}</td>
							<td>
								{if $article.image_id!=$image.id}
								<a href="#" onclick="set_as_main({$article.id},{$image.id})">{tr}Set as main image{/tr}</a><br/>
								{/if}
								<a href="#" onclick="remove_image({$article.id},{$image.id})">{tr}Remove{/tr}</a><br/>
								<a href="#" onclick="edit_image({$article.id},{$image.id})">{tr}Edit image{/tr}</a>
							</td>
						</tr>						
						{/foreach}
					</table>
					
					<script type="text/javascript" charset="utf-8">
						{literal}
						function add_image(article_id)
						{
							if(!jQuery('#images_load_div').length)
								jQuery('body').append('<div style="display:none" id="images_load_div"></div>');
							ajax_load(root+'admin/articles/?a=image_add:'+article_id,'','#images_load_div');
							jQuery('#images_load_div').dialog(
								{
									modal:true,
									title:'{/literal}Add image{literal}',
									width:600,
									height:450	
								}
							);
						}
						
						function edit_image(article_id,image_id)
						{
							if(!jQuery('#images_load_div').length)
								jQuery('body').append('<div style="display:none" id="images_load_div"></div>');
							ajax_load(root+'admin/articles/?a=image_edit:'+article_id+':'+image_id,'','#images_load_div');
							jQuery('#images_load_div').dialog(
								{
									modal:true,
									title:'{/literal}Edit image{literal}',
									width:600,
									height:500	
								}
							);
						}
						
						function set_as_main(article_id,image_id)
						{
							ajax_load(root+'admin/articles/?a=set_main_image:'+article_id+':'+image_id,'','#article_images');
						}
						
						
						function remove_image(article_id,image_id)
						{
							ajax_load(root+'admin/articles/?a=image_remove:'+article_id+':'+image_id,'','#article_images');
						}
						{/literal}
					</script>