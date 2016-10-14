<?php
	
	class languages_page extends Page
	{
		function on_init()
		{
			$this->allowed_actions=array("add","save","edit","update","delete","order","active");
			$this->assign("page_title","Configurare limbi straine");
		}
		
		function on_load()
		{
			if(!isset($this->system->actions[0]))
			{
				$this->init_table();
				$this->assign('table',$this->table->get_array());
			}
		}
		
		function action_add()
		{
			$form=array();
					$form['name']="language_form";
					$form['id']="language_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Adaugare limba"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save"
								),
								"Nume:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati numele!")
								),
								"Cod:"=>array(
									"name"=>"code",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati codul!")
								),
								"Activa:"=>array(
									"name"=>"active",
									"type"=>"radiolist",
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								),
								"Imagine:"=>array(
									"name"=>"image",
									"type"=>"file"
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_edit($id)
		{
			$obj=$this->models->languages->get($id);
					$obj_img=$this->models->images->get($obj['image_id']);
					
					$form=array();
					$form['name']="language_form";
					$form['id']="language_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare limba"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save:".$obj['id']
								),
								"Nume:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>$obj['valoare'],
									"validate"=>array("required"=>"Completati numele!")
								),
								"Cod:"=>array(
									"name"=>"code",
									"type"=>"text",
									"value"=>$obj['code'],
									"validate"=>array("required"=>"Completati codul!")
								),
								"Activa:"=>array(
									"name"=>"active",
									"type"=>"radiolist",
									"value"=>$obj['is_active'],
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								),
								"Imaginea curenta:"=>array(
									"name"=>"",
									"type"=>"image",
									"value"=>$obj['valoare'],
									"url"=>isset_or($obj_img['current_http_path'])
								),
								"Imagine noua:"=>array(
									"name"=>"image",
									"type"=>"file"
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}

		function action_save($id=0)
		{
			$params=array();
			$params['valoare']=$_REQUEST['name'];
			$params['code']=$_REQUEST['code'];
			$params['is_active']=$_REQUEST['active'];
			if($id)
			{
				$obj=$this->models->languages->get($id);
				$params['image_id']=$this->models->images->save_request("image","languages",$obj['image_id']);
				$this->models->languages->update($params,"id=".$id);
			}
			else
			{
				$params['order']=$this->models->languages->count_all();
				$params['image_id']=$this->models->images->save_request("image","languages");
				$id=$this->models->languages->insert($params);
			}
					
			$this->add_message("success","S-a salvat limba!");
			$this->redirect($this->paths['current']."?a=edit:".$id);
		}
			
		function action_update()
		{
			$this->init_table(1);
			
			$this->table->display_data();
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_general_languages';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'col'=>'valoare',
						'name'=>'Limba',
						'strong'=>1
					),
					array(
						'col'=>'code',
						'name'=>'Cod'
					),
					array(
						'col'=>'is_active',
						'name'=>'Activa',
						'active'=>'1'
					),
					array(
						'name'=>'Imagine',
						'col'=>'image_id',
						'eval'=>'{image alt=$o.code.value}{$p.paths.skin_images}languages/{$o.code.value}.png{/image}'
					),
					array(
						'name'=>'Ordine',
						'col'=>'order',
						'order'=>1
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->languages->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->languages->total_rows;
				
				$this->table->add_action('Modifica','',$this->paths['root_subcomponent'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
				$this->table->add_action("Sterge","","",'delete:{$o.id.value}',1,"icon-trash-o","Sunteti sigur ca doriti sa stergeti?");
			}
			$this->table->update_action='update';
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}
		
		function action_order($id,$value)
		{
			$this->models->languages->set_order($id,$value);
			die;
		}
		
		function action_active($id,$value)
		{
			$this->models->languages->set_active($id,$value);
			die;
		}
		
		function action_delete($id)
		{
			$this->models->languages->delete($id);
			die;
		}
	}
	
?>