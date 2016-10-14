<?php
	
	class templates_page extends Page
	{
		var $table="";
		
		function on_init()
		{
			$this->allowed_actions=array("add","save","edit","update","dalete");
			$this->assign("page_title","Configurare template-uri email");
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
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare meta"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save"
								),
								"Titlu:"=>array(
									"name"=>"apge",
									"type"=>"text",
									"value"=>""
								),
								"De la e-mail (from):"=>array(
									"name"=>"from",
									"type"=>"text",
									"value"=>""
								),
								"Subiect:"=>array(
									"name"=>"subject",
									"type"=>"text",
									"value"=>""
								),
								"Reply-to:"=>array(
									"name"=>"reply_to",
									"type"=>"text",
									"value"=>""
								),
								"Reply nume:"=>array(
									"name"=>"reply_name",
									"type"=>"text",
									"value"=>""
								),
								"Path template:"=>array(
									"name"=>"template_path",
									"type"=>"text",
									"value"=>""
								),
								"Continut template:"=>array(
									"name"=>"template_content",
									"type"=>"htmleditor",
									"value"=>""
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_edit($id)
		{
			$obj=$this->models->email_templates->get($id);
					
					$form=array();
					$form['name']="language_form";
					$form['id']="language_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare meta"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save:".$obj['id']
								),
								"Titlu:"=>array(
									"name"=>"apge",
									"type"=>"text",
									"value"=>$obj['title']
								),
								"De la e-mail (from):"=>array(
									"name"=>"from",
									"type"=>"text",
									"value"=>$obj['from']
								),
								"Subiect:"=>array(
									"name"=>"subject",
									"type"=>"text",
									"value"=>$obj['subject']
								),
								"Reply-to:"=>array(
									"name"=>"reply_to",
									"type"=>"text",
									"value"=>$obj['reply_to']
								),
								"Reply nume:"=>array(
									"name"=>"reply_name",
									"type"=>"text",
									"value"=>$obj['reply_name']
								),
								"Path template:"=>array(
									"name"=>"template_path",
									"type"=>"text",
									"value"=>$obj['template_path']
								),
								"Continut template:"=>array(
									"name"=>"template_content",
									"type"=>"htmleditor",
									"value"=>file_get_contents($obj['template_path'])
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}

		function action_save($id=0)
		{
					$params=array();
					$params['titlu']=$_POST['titlu'];
					$params['from']=$_POST['from'];
					$params['subject']=$_POST['subject'];
					$params['reply_to']=$_POST['reply_to'];
					$params['reply_name']=$_POST['reply_name'];
					$params['template_path']=$_POST['template_path'];
					$params['template_content']=$_POST['template_content'];
					$ok="";
					if($id)
					{
						$ok=$this->models->email_templates->update($params,"id=".$id);
					}
					else
					{
						$ok=$this->models->email_templates->insert($params);
					}
					if($ok)
					{
						$this->add_message("success","S-a salvat template-ul!");
					}
					else
					{
						$this->add_message("error","S-au intalnit niste probleme verificati drepturile de scriere pe path-ul de template!");
					}
					if(isset($_REQUEST['return']))
						$this->redirect($this->paths['current']);
					else
					{
						if($id)
						{
							$this->redirect($this->paths['root_component']."templateuri?a=edit:$id");
						}
						else
						{
							$this->redirect($this->paths['root_component']."templateuri?a=add");
						}
					}
		}
					
		function action_update()
		{
			$this->init_table(1);
			
			$this->table->display_data();
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_general_templates';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'Nume',
						'col'=>'name'
					),
					array(
						'name'=>'Titlu',
						'col'=>'title',
						'strong'=>1
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->email_templates->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->email_templates->total_rows;
				
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
			$this->models->email_templates->delete($id);
			die;
		}
	
	}
	
	
?>