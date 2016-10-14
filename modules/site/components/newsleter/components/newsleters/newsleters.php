<?php
	class newsleters_page extends Page
	{
		var $allowed_actions=array('add','edit','save','send','send_mail','update','delete');
		
		function on_init()
		{			
			$this->title='Scrisori salvate';
		}
		
		function on_load()
		{
			$this->init_table();
				
			$this->assign('table',$this->table->get_array());
		}
		
		function action_save($id=0)
		{
			$pars=array();
			$pars['subject']=$_POST['subject'];
			$pars['message']=$_POST['message'];
			$pars['from_address']=$_POST['from_address'];
			$pars['from_name']=$_POST['from_name'];
			$pars['reply_address']=$_POST['reply_address'];
			$pars['reply_name']=$_POST['reply_name'];
			if($id)
			{
				$pars['update_date']=date('y-m-d H:i:s');
				$this->models->newsletters->update($pars,'id='.$id);
			}
			else
			{	
				$pars['add_date']=date('y-m-d H:i:s');
				$pars['update_date']=date('y-m-d H:i:s');
				$id=$this->models->newsletters->insert($pars);
			}
			$this->add_message('success','S-a salvat scrisoarea!');
			$this->redirect($this->paths['current'].'?a=edit:'.$id);
		}
		
		function action_add()
		{			
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Salveaza';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Adaugare scrisoare'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save'
						),
						'Subiect:'=>array(
							'name'=>'subject',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati subiectul!')
						),						
						'Mesaj:'=>array(
							'name'=>'message',
							'type'=>'htmleditor',
							'value'=>'',
							'validate'=>array('required'=>'Completati continutul scrisorii!')
						),
						'De la adresa:'=>array(
							'name'=>'from_address',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati adresa de la care se trimite mailul!',
												'email'=>'Completati un email valid!'
										)
						),
						'De la nume:'=>array(
							'name'=>'from_name',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati numele sub care se trimite mailul!')
						),
						'Reply la adresa:'=>array(
							'name'=>'reply_address',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati adresa de la care se va putea face raspuns!',
												'email'=>'Completati un email valid!'
										)
						),
						'Reply la nume:'=>array(
							'name'=>'reply_name',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati numele sub care apare adresa la care se face raspuns!')
						)
					)
				)
			);
			
			$this->assign('form',$form);
		}
		
		function action_edit($id=0)
		{			
			$newsletter=$this->models->newsletters->get($id);
			
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Salveaza';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Modificare scrisoare'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save:'.$newsletter['id']
						),
						'Subiect:'=>array(
							'name'=>'subject',
							'type'=>'text',
							'value'=>$newsletter['subject'],
							'validate'=>array('required'=>'Completati subiectul!')
						),						
						'Mesaj:'=>array(
							'name'=>'message',
							'type'=>'htmleditor',
							'value'=>$newsletter['message'],
							'validate'=>array('required'=>'Completati continutul scrisorii!')
						),
						'De la adresa:'=>array(
							'name'=>'from_address',
							'type'=>'text',
							'value'=>$newsletter['from_address'],
							'validate'=>array('required'=>'Completati adresa de la care se trimite mailul!',
												'email'=>'Completati un email valid!'
										)
						),
						'De la nume:'=>array(
							'name'=>'from_name',
							'type'=>'text',
							'value'=>$newsletter['from_name'],
							'validate'=>array('required'=>'Completati numele sub care se trimite mailul!')
						),
						'Reply la adresa:'=>array(
							'name'=>'reply_address',
							'type'=>'text',
							'value'=>$newsletter['reply_address'],
							'validate'=>array('required'=>'Completati adresa de la care se va putea face raspuns!',
												'email'=>'Completati un email valid!'
										)
						),
						'Reply la nume:'=>array(
							'name'=>'reply_name',
							'type'=>'text',
							'value'=>$newsletter['reply_name'],
							'validate'=>array('required'=>'Completati numele sub care apare adresa la care se face raspuns!')
						)
					)
				)
			);
			
			$this->assign('form',$form);
		}
		
		function action_send($id=0)
		{
			$newsletter=$this->models->newsletters->get($id);
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1');
			$types[]=array('name'=>'- trimite la toti -','id'=>'0');
			
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Trimite';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Trimite scrisoare'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'send_mail'
						),
						'Trimite la adresele de tipul:'=>array(
							'name'=>'type_id',
							'type'=>'select',
							'value'=>'',
							'default'=>'',							
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types
						),
						'Subiect:'=>array(
							'name'=>'subject',
							'type'=>'text',
							'value'=>$newsletter['subject'],
							'validate'=>array('required'=>'Completati subiectul!')
						),						
						'Mesaj:'=>array(
							'name'=>'message',
							'type'=>'htmleditor',
							'value'=>$newsletter['message'],
							'validate'=>array('required'=>'Completati continutul scrisorii!')
						),
						'De la adresa:'=>array(
							'name'=>'from_address',
							'type'=>'text',
							'value'=>$newsletter['from_address'],
							'validate'=>array('required'=>'Completati adresa de la care se trimite mailul!',
												'email'=>'Completati un email valid!'
										)
						),
						'De la nume:'=>array(
							'name'=>'from_name',
							'type'=>'text',
							'value'=>$newsletter['from_name'],
							'validate'=>array('required'=>'Completati numele sub care se trimite mailul!')
						),
						'Raspuns la adresa:'=>array(
							'name'=>'reply_address',
							'type'=>'text',
							'value'=>$newsletter['reply_address'],
							'validate'=>array('required'=>'Completati adresa de la care se va putea face raspuns!',
												'email'=>'Completati un email valid!'
										)
						),
						'Raspuns la nume:'=>array(
							'name'=>'reply_name',
							'type'=>'text',
							'value'=>$newsletter['reply_name'],
							'validate'=>array('required'=>'Completati numele sub care apare adresa la care se face raspuns!')
						)
					)
				)
			);
			$this->assign('form',$form);
		}
		
		function action_send_mail()
		{
			$pars=array();
			$pars['type_id']=$_POST['type_id'];
			$pars['subject']=$_POST['subject'];
			$pars['message']=$_POST['message'];
			$pars['from_address']=$_POST['from_address'];
			$pars['from_name']=$_POST['from_name'];
			$pars['reply_address']=$_POST['reply_address'];
			$pars['reply_name']=$_POST['reply_name'];
			$pars['start_date']=date('y-m-d H:i:s');
			if($pars['type_id'])$pars['total']=$this->models->newsletters_emails->count_all('type_id='.$pars['type_id'].' and active=1');			
			else $pars['total']=$this->models->newsletters_emails->count_all('active=1');
			$pars['sent']=0;
			$pars['active']=1;	
			
			$this->add_message('success','Trimiterea scrisorilor a inceput cu success! ');
			$this->models->newsletters_jobs->insert($pars);	
			$this->redirect($this->paths['current']);
		}
			
		function action_update()
		{
			$this->init_table(1);
			
			$this->table->display_data();
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_newsletters';			
			if($data)
			{					
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'Subiect',
						'col'=>'subject',
						'strong'=>1
					),
					array(
						'name'=>'De la adresa',
						'col'=>'from_address'			
					),
					array(
						'name'=>'Data adaugare',
						'col'=>'add_date',
						'date'=>1					
					),
					array(
						'name'=>'Data modificare',
						'col'=>'update_date',
						'date'=>1					
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->newsletters->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));				
				$this->system->no_total_rows=$this->models->newsletters->total_rows;
			
				$this->table->add_action('Sterge','','','delete:{$o.id.value}',1,'icon-trash-o','Sunteti sigur ca doriti sa stergeti?');
				$this->table->add_action('Modifica','',$this->paths['root_component'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
				$this->table->add_action('Trimite','',$this->paths['root_component'].'?a=send:{$o.id.value}','',0,'icon-email');
			}
			$this->table->update_action='update';			
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows; 
		}
				
		function action_delete($id)
		{
			$this->models->newsletters->delete($id);
		}	
	}
?>