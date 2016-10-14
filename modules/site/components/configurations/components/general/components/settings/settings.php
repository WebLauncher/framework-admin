<?php

	class settings_page extends Page
	{
		var $table="";
		
		function on_init()
		{
			$this->allowed_actions=array("save","edit","update");
		}
		
		function on_load()
		{
			if(!isset($this->system->actions[0]))
			{
				$this->init_table();
				$this->assign('table',$this->table->get_array());
			}
		}
			
		function action_edit($id)
		{
			$obj=$this->models->settings->get($id);
					
					$form=array();
					$form['name']="language_form";
					$form['id']="language_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current'];
					$form['zones']=
					array(
						"Modificare setare"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save:".$obj['id']
								),
								"Setare:"=>array(
									"name"=>"description",
									"type"=>"none",
									"value"=>$obj['description']
								)
							)
						)
					);
					switch($obj['type'])
					{
						case "active":
							$form['zones']['Modificare setare']['fields']["Valoare"]=array(
								"name"=>"value",
								"type"=>"radiolist",
								"value"=>$obj['value'],
								"default"=>0,
								"options"=>array(
									"Inactive"=>0,
									"Active"=>1
								)
							);
						break;
						case "html":
							$form['zones']['Modificare setare']['fields']["Valoare"]=array(
								"name"=>"value",
								"type"=>"htmleditor",
								"value"=>$obj['value']
							);
						break;
						case "text":
							$form['zones']['Modificare setare']['fields']["Valoare"]=array(
								"name"=>"value",
								"type"=>"textarea",
								"value"=>$obj['value']
							);
						break;
						case "id":
							$query="select * from `".$obj['from_table']."`";
							$objs=$this->models->db->getAll($query);
							
							$form['zones']['Modificare setare']['fields']["Valoare"]=array(
								"name"=>"value",
								"type"=>"select",
								"selected"=>$obj['value'],
								"default"=>"",
								"default_show"=>"true",
								"default_text"=>"- alege -",
								"option_field_value"=>"id",
								"option_field_text"=>$obj['from_field'],
								"options"=>$objs
							);
						break;
						case "array":
							$query="select * from `".$obj['from_table']."`";
							$objs=$this->models->db->getAll($query);
							
							foreach($objs as $k=>$v)
							{
								$objs[$k]['name']=$v[$obj['from_field']];
								if(in_array($v['id'],$obj['value']))
									$objs[$k]['checked']=1;
							}
							
							$form['zones']['Modificare setare']['fields']["Valoare"]=array(
								"name"=>"value",
								"type"=>"checkboxlist",
								"options"=>$objs
							);
						break;
					}
					
					$this->assign("form",$form);
		}

		function action_save($id=0)
		{
					$params=array();
					if(is_array($_POST['value']))
						$params['value']=ser($_POST['value']);
					else
						$params['value']=$_POST['value'];
					
					$this->models->settings->update($params,"id=".$id);
					
					$this->add_message("success","S-a salvat setarea!");
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
			$this->table->id='table_general_settings';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'col'=>'type',
						'hidden'=>1
					),
					array(
						'col'=>'from_table',
						'hidden'=>1
					),
					array(
						'col'=>'from_field',
						'hidden'=>1
					),
					array(
						'name'=>'Setare',
						'col'=>'description',
						'strong'=>1
					),
					array(
						'name'=>'Valoare',
						'col'=>'value',
						'eval'=>'{if $o.type.value=="id"}{bind table=$o.from_table.value get_field=$o.from_field.value}{$o.value.value}{/bind}{elseif $o.type.value=="array"}{foreach item=val from=$o.value.value}{bind table=$o.from_table.value get_field=$o.from_field.value}{$val}{/bind},{/foreach}{else}{$o.value.value}{/if}'
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->settings->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'hidden=0',true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->settings->total_rows;
				
				$this->table->add_action('Modifica','',$this->paths['root_subcomponent'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
			}
			$this->table->update_action='update';
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}
	
	}
		
?>