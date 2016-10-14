<?php
	
	class states_page extends Page
	{
		function on_init()
		{
			$this->allowed_actions=array("add","save","edit","update","delete");
			$this->assign("page_title","Configurare lista judete");
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
						"Adauga judet"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save"
								),
								"Numele:"=>array(
									"name"=>"valoare",
									"type"=>"text",
									"value"=>""
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_edit($id)
		{
			$obj=$this->models->xjudete->get($id);
					
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
						"Modificare judet"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save:".$obj['id']
								),
								"Numele:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>$obj['valoare']
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}

		function action_save($id=0)
		{
					$params=array();
					$params['valoare']=$_POST['valoare'];
					if($id)
					{
						$this->models->xjudete->update($params,"id=".$id);
					}
					else
					{
						$id=$this->models->xjudete->insert($params);
					}
					$this->add_message("success","S-a salvat judetul!");
				if(isset($_REQUEST['return']))
					$this->redirect($this->paths['current']);
				else
					$this->redirect($this->paths['current']."?a=edit:$id");
		}
				
		function action_update()
		{
			$this->init_table(1);
			
			$this->table->display_data();
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_general_states';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'Nume',
						'col'=>'valoare'
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->xjudete->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->xjudete->total_rows;
				
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
			$this->models->xjudete->delete($id);
		}
	}
	
?>