<?php
	class emails_types_page extends Page
	{
		var $table='';
		
		function on_init()
		{
			$this->allowed_actions=array('add','save','edit','update','delete','active');
			$this->assign('page_title','Tipuri adrese e-mail');
		}
		
		function on_load()
		{
			$this->init_table();
				
			$this->assign('table',$this->table->get_array());
		}

		function action_add()
		{
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Salveaza';
			$form['btn_submit_return_text']='Salveaza si afiseaza lista';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Detalii tip'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save'
						),
						'Nume:'=>array(
							'name'=>'name',
							'type'=>'text',
							'value'=>''
						),
						'Activ:'=>array(
									'name'=>'active',
									'type'=>'radiolist',
									'default'=>0,
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								)						
					)
				),
				'Verificare existenta email'=>array(
					'fields'=>array(
						'Activa:'=>array(
									'name'=>'check_active',
									'type'=>'radiolist',
									'default'=>0,
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								),
						'Tabela de verificare:'=>array(
							'name'=>'check_table',
							'type'=>'text',
							'value'=>''
						),	
						'Campul de verificare:'=>array(
							'name'=>'check_field',
							'type'=>'text',
							'value'=>''
						),					
					)
				),
				'Verificare dezabonare'=>array(
					'fields'=>array(
						'Activa:'=>array(
									'name'=>'unsubscribe_active',
									'type'=>'radiolist',
									'default'=>0,
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								),
						'E-mail:'=>array(
							'name'=>'unsubscribe_email',
							'type'=>'text',
							'value'=>''
						),	
						'Server mail:'=>array(
							'name'=>'unsubscribe_mailserver',
							'type'=>'text',
							'value'=>''
						),	
						'Server port:'=>array(
							'name'=>'unsubscribe_serverport',
							'type'=>'text',
							'value'=>''
						),
						'Server tip:'=>array(
							'name'=>'unsubscribe_servertype',
							'type'=>'select',
							'value'=>'pop3',
							'default'=>'',							
							'option_field_value'=>'type',
							'option_field_text'=>'type',
							'options'=>array(array('type'=>'pop3'),array('type'=>'imap'))
						),	
						'Server username:'=>array(
							'name'=>'unsubscribe_username',
							'type'=>'text',
							'value'=>''
						),	
						'Server password:'=>array(
							'name'=>'unsubscribe_password',
							'type'=>'text',
							'value'=>''
						),	
						'Cuvinte de verificare ( ex. dezabonare, failure ):'=>array(
							'name'=>'unsubscribe_keywords',
							'type'=>'textarea',
							'value'=>''
						)				
					)
				)
			);
			$this->assign('form',$form);
		}
		
		function action_save($id='')
		{
			if($this->validate('texte_form'))
			{
				$params=array();
					$params['name']=$_POST['name'];
					$params['active']=$_POST['active'];
					
					$params['check_active']=$_POST['check_active'];
					$params['check_in_table']=$_POST['check_table'];
					$params['check_in_field']=$_POST['check_field'];
					
					$params['unsubscribe_active']=$_POST['unsubscribe_active'];
					$params['unsubscribe_email']=$_POST['unsubscribe_email'];
					$params['unsubscribe_mailserver']=$_POST['unsubscribe_mailserver'];
					$params['unsubscribe_servertype']=$_POST['unsubscribe_servertype'];
					$params['unsubscribe_serverport']=$_POST['unsubscribe_serverport'];
					$params['unsubscribe_username']=$_POST['unsubscribe_username'];
					$params['unsubscribe_password']=$_POST['unsubscribe_password'];
					$params['unsubscribe_keywords']=$_POST['unsubscribe_keywords'];
				if($id)
				{
					$this->models->newsletters_emails_types->update($params,'id='.$id);
				}
				else
				{
					
						
					$id=$this->models->newsletters_emails_types->insert($params);
				}
				
				$this->add_message('success','S-a salvat tipul!');
				if(isset($_POST['return']) && $_POST['return'])
					$this->redirect($this->paths['current']);
				else
					$this->redirect($this->paths['root_component'].'?a=edit:'.$id);
				
			}
			else
			{
				
				$this->add_message('error','S-au intalnit probleme!');
				$this->redirect($this->paths['root_component'].'?a=add');
			}
		}
		
		function action_edit($id)
		{
			$type=$this->models->newsletters_emails_types->get($id);
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Salveaza';
			$form['btn_submit_return_text']='Salveaza si afiseaza lista';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Adauga tip'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save:'.$id
						),
						'Nume:'=>array(
							'name'=>'name',
							'type'=>'text',
							'value'=>$type['name']
						),
						'Activ:'=>array(
									'name'=>'active',
									'type'=>'radiolist',
									'default'=>0,
									'value'=>$type['active'],
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								)						
					)
				),
				'Verificare existenta email'=>array(
					'fields'=>array(
						'Activa:'=>array(
									'name'=>'check_active',
									'type'=>'radiolist',
									'default'=>0,
									'value'=>$type['check_active'],
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								),
						'Tabela de verificare:'=>array(
							'name'=>'check_table',
							'type'=>'text',
							'value'=>$type['check_in_table']
						),	
						'Campul de verificare:'=>array(
							'name'=>'check_field',
							'type'=>'text',
							'value'=>$type['check_in_field']
						),					
					)
				),
				'Verificare dezabonare'=>array(
					'fields'=>array(
						'Activa:'=>array(
									'name'=>'unsubscribe_active',
									'type'=>'radiolist',
									'default'=>0,
									'value'=>$type['unsubscribe_active'],
									'options'=>array(
										'Nu'=>0,
										'Da'=>1
									)
								),
						'E-mail:'=>array(
							'name'=>'unsubscribe_email',
							'type'=>'text',
							'value'=>$type['unsubscribe_email']
						),	
						'Server mail:'=>array(
							'name'=>'unsubscribe_mailserver',
							'type'=>'text',
							'value'=>$type['unsubscribe_mailserver']
						),	
						'Server port:'=>array(
							'name'=>'unsubscribe_serverport',
							'type'=>'text',
							'value'=>$type['unsubscribe_serverport']
						),
						'Server tip:'=>array(
							'name'=>'unsubscribe_servertype',
							'type'=>'select',
							'value'=>$type['unsubscribe_servertype'],
							'select'=>$type['unsubscribe_servertype'],
							'default'=>'',							
							'option_field_value'=>'type',
							'option_field_text'=>'type',
							'options'=>array(array('type'=>'pop3'),array('type'=>'imap'))
						),	
						'Server username:'=>array(
							'name'=>'unsubscribe_username',
							'type'=>'text',
							'value'=>$type['unsubscribe_username']
						),	
						'Server password:'=>array(
							'name'=>'unsubscribe_password',
							'type'=>'text',
							'value'=>$type['unsubscribe_password']
						),	
						'Cuvinte de verificare ( ex. dezabonare, failure ):'=>array(
							'name'=>'unsubscribe_keywords',
							'type'=>'textarea',
							'value'=>$type['unsubscribe_keywords']
						)				
					)
				)
			);
			$this->assign('form',$form);
		}
	
		function action_update()
		{
			$this->init_table(1);
			
			$this->table->display_data();
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_newsletters_emais_types';			
			if($data)
			{	
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'Nume',
						'col'=>'name',
						'strong'=>1
					),
					array(
						'name'=>'Activ',
						'col'=>'active',
						'eval'=>'<a href="#" onclick="ajax_active(\'{$current}\',{$o.id.value},{if $o.active.value==0}1{else}0{/if},\'$(\\\'#j_{$table.id}\\\').gridview(\\\'refresh\\\');\');return false;" title="{if $o.active.value==1}{tr tags="buttons"}Dezactiveaza{/tr}{else}{tr tags="buttons"}Activeaza{/tr}{/if}"><b class="icon {if $o.active.value==1}icon-accept{else}icon-ban{/if}"></b></a>'
					),
					array(
						'name'=>'Verificare',
						'col'=>'check_active',
						'eval'=>'<b class="icon {if $o.check_active.value==1}icon-accept{else}icon-ban{/if}""></b></a>'
					),					
					array(
						'name'=>'Dezabonare',
						'col'=>'unsubscribe_active',
						'eval'=>'<b class="icon {if $o.unsubscribe_active.value==1}icon-accept{else}icon-ban{/if}"></b></a>'
					),
					array(
						'name'=>'Total',
						'col'=>'emails'
					),
					array(
						'name'=>'Active',
						'col'=>'emails_active'
					),
					array(
						'name'=>'Export',
						'col'=>'export',
						'sort'=>0,
						'eval'=>'<a href="{$current}?a=export:{$o.id.value}">export</a>'
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->newsletters_emails_types->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));				
				$this->system->no_total_rows=$this->models->newsletters_emails_types->total_rows;
			
				$this->table->add_action('Sterge','','','delete:{$o.id.value}',1,'icon-trash-o','Sunteti sigur ca doriti sa stergeti?');
				$this->table->add_action('Modifica','',$this->paths['root_component'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
			}
			$this->table->update_action='update';			
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows; 
		}
				
		function action_active($id,$value)
		{
			$this->models->newsletters_emails_types->set_active($id,$value,"active");
			die;
		}
		
		function action_delete($id)
		{
			$this->models->newsletters_emails_types->delete($id);
		}
		
		function action_export($id){
			$type=$this->models->newsletters_emails_types->get($id);
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=\"".$type['name']."-".now().".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$emails=$this->models->newsletters_emails->get_all('','','','','type_id='.sat($id));
			foreach($emails as $v){
				echo $v['email']."\n";
			}
			die;
		}	
	}
?>