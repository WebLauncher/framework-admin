<?php
	class pages_page extends Page
	{
		var $table='';

		function on_init()
		{
			$this->allowed_actions=array(
				"edit",
				"save",
				"add",
				'update',
				'delete',
				'active',
				'select'
			);
		}

		function on_load()
		{
			if(!isset($this->system->actions[0]))
			{
				$this->session['seo_url']=isset_or($_REQUEST['url'],'https?://'.($this->system->application['name']?$this->system->server['host'].'/':''));
				$this->init_table();
				$this->assign('table',$this->table->get_array());
			}
		}

		function action_save($id=0)
		{
			$params=array();
			$params['title']=$_POST['_title'];
			$params['active']=$_POST['active'];
			$params['keywords']=$_POST['keywords'];
			$params['description']=$_POST['description'];
			if($id)
			{
				$this->models->seo_links->update($params,"id=".$id);
			}
			else
			{
				$params['page']=$_POST['page'];
				$params['views']=0;
				$id=$this->models->seo_links->insert($params);
			}

			$this->add_message("success","S-a salvat setarile paginii!");
			if(isset($_REQUEST['return']))
				$this->redirect($this->paths['current']);
			else
				$this->redirect($this->paths['current']."?a=edit:$id");
		}

		function action_add()
		{
			$this->view='form';
			$form=array();
					$form['name']="banners_zones_form";
					$form['id']="banners_zones_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare setari SEO pagina"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save"
								),
								"Pagina:"=>array(
									"name"=>"page",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati pagina!")
								),
								"Titlul general %title%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->settings['general_page_title']['value']
								),
								"Titlu:"=>array(
									"name"=>"_title",
									"type"=>"text",
									"value"=>$this->system->settings['general_page_title']['value'],
									"validate"=>array("required"=>"Completati titlul!")
								),
								"Activa:"=>array(
									"name"=>"active",
									"type"=>"radiolist",
									"value"=>1,
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								),
								"Keywords general %main%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->get_meta_tag("keywords")
								),
								"Keywords:"=>array(
									"name"=>"keywords",
									"type"=>"text",
									"value"=>$this->system->get_meta_tag("keywords"),
									"validate"=>array("required"=>"Completati keywords!")
								),
								"Description general %main%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->get_meta_tag("description")
								),
								"Description:"=>array(
									"name"=>"description",
									"type"=>"text",
									"value"=>$this->system->GetMetaTag("description"),
									"validate"=>array("required"=>"Completati description!")
								)
							)
						)
					);

					$this->assign("form",$form);
		}

		function action_edit($id=0)
		{
			$this->view='form';
					$campanie=$this->models->seo_links->get($id);

					$form=array();
					$form['name']="banners_zones_form";
					$form['id']="banners_zones_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare setari SEO pagina"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save:".$campanie['id']
								),
								"Pagina:"=>array(
									"name"=>"page",
									"type"=>"none",
									"value"=>$campanie['page']
								),
								"Titlul general %title%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->settings['general_page_title']['value']
								),
								"Titlu:"=>array(
									"name"=>"_title",
									"type"=>"text",
									"value"=>$campanie['title'],
									"validate"=>array("required"=>"Completati titlul!")
								),
								"Activa:"=>array(
									"name"=>"active",
									"type"=>"radiolist",
									"value"=>$campanie['active'],
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								),
								"Keywords general %main%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->get_meta_tag("keywords")
								),
								"Keywords:"=>array(
									"name"=>"keywords",
									"type"=>"text",
									"value"=>$campanie['keywords'],
									"validate"=>array("required"=>"Completati keywords!")
								),
								"Description general %main%:"=>array(
									"name"=>"gtitle",
									"type"=>"none",
									"value"=>$this->system->get_meta_tag("description")
								),
								"Description:"=>array(
									"name"=>"description",
									"type"=>"text",
									"value"=>$campanie['description'],
									"validate"=>array("required"=>"Completati description!")
								)
							)
						)
					);

					$this->assign("form",$form);
		}

		function action_update()
		{
			$this->init_table(1);
			$this->table->display_data();
			die;
		}

		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_seo_pages';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'Nume',
						'col'=>'page',
						'strong'=>1
					),
					array(
						'name'=>'Subpages',
						'col'=>'subpages',
						'eval'=>'{if $o.subpages.value}<a href="{$current}?url={$o.page.value|urlencode}">{$o.subpages.value}</a>{else}no subpages{/if}'
					),
					array(
						'name'=>'Vizualizari',
						'col'=>'views'
					),
					array(
						'name'=>'Activ',
						'col'=>'active',
						'active'=>1
					)
				);

				$this->table->process_request();

				$cond='page REGEXP "^'.isset_or($this->session['seo_url'],'https?://').'[-_a-zA-Z0-9\.]+/" or page REGEXP "^'.isset_or($this->session['seo_url'],'https?://').'[\?\=-_a-zA-Z0-9\.]+" ';


				$this->table->process_content($this->models->seo_links->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,$cond,true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->seo_links->total_rows;

				$this->table->add_action('Modifica','',$this->paths['root_subcomponent'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
				$this->table->add_action("Sterge","","",'delete:{$o.id.value}',1,"icon-trash-o","Sunteti sigur ca doriti sa stergeti?");
			}
			$this->table->update_action='update';
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}

		function action_delete($id)
		{
			$this->models->seo_links->delete($id);die;
		}

		function action_active($id,$value)
		{
			$this->models->seo_links->set_active($id,$value,"active");die;
		}

	}
?>
