<?php
	class emails_page extends Page
	{
		var $table='';

		function on_init()
		{
			$this->allowed_actions=array('add','save','edit','import','export','import_save','export_save','unsubscribe','unsubscribe_check','update','delete','active');
			$this->assign('page_title','Adrese e-mail inregistrate');
		}

		function on_load()
		{
			$this->init_table();

			$this->assign('table',$this->table->get_array());
		}

		function action_add()
		{
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1');
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
				'Adaugare e-mail'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save'
						),
						'Tipul de e-mail:'=>array(
							'name'=>'type',
							'type'=>'select',
							'value'=>'',
							'default'=>'',
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types,
							'validate'=>array('required'=>'Alegeti tipul de adresa de email!')
						),
						'E-mail:'=>array(
							'name'=>'email',
							'type'=>'text',
							'value'=>'',
							'validate'=>array('required'=>'Completati e-mailul!',
												'email'=>'Completati email valid!')
						),
						'Sursa:'=>array(
							'name'=>'from',
							'type'=>'text',
							'value'=>''
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
			$this->table->id='table_newsletters_emais';
			if($data)
			{
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'name'=>'E-mail',
						'col'=>'email',
						'strong'=>1
					),
					array(
						'name'=>'Tip',
						'col'=>'type_id',
						'bind'=>array('table'=>$this->models->newsletters_emails_types->table,'get_field'=>'name')
					),
					array(
						'name'=>'Sursa',
						'col'=>'from'
					),
					array(
						'name'=>'Activ',
						'col'=>'active',
						'eval'=>'<a href="#" onclick="ajax_active(\'{$current}\',{$o.id.value},{if $o.active.value==0}1{else}0{/if},\'$(\\\'#j_{$table.id}\\\').gridview(\\\'refresh\\\');\');return false;" title="{if $o.active.value==1}{tr tags="buttons"}Dezactiveaza{/tr}{else}{tr tags="buttons"}Activeaza{/tr}{/if}"><b class="icon {if $o.active.value==1}icon-accept{else}icon-ban{/if}"></b></a>'
					),
				);

				$this->table->process_request();

				$this->table->process_content($this->models->newsletters_emails->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->newsletters_emails->total_rows;

				$this->table->add_action('Sterge','','','delete:{$o.id.value}',1,'icon-trash-o','Sunteti sigur ca doriti sa stergeti?');
				$this->table->add_action('Modifica','',$this->paths['root_component'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
			}
			$this->table->update_action='update';
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}

		function action_save($id='')
		{
			if($this->validate('texte_form'))
			{
				if($id)
					$email=$this->models->newsletters_emails->get($id);
				if((!$id && !$this->models->newsletters_emails->CheckEmail($_POST['email'],$_POST['type'])) || ($id && ($email['email']!=$_POST['email'] || $email['type_id']!=$_POST['type']) && !$this->models->newsletters_emails->CheckEmail($_POST['email'],$_POST['type'])))
				{
					$this->add_message('error','E-mail-ul exista deja in lista sau in tabela de tip setata!');
					if($id)
					{
						$this->redirect($this->paths['root_component'].'?a=edit:'.$id);
					}
					else
					{
						$this->redirect($this->paths['root_component'].'?a=add');
					}
				}
				else
				{
					$params=array();
					$params['type_id']=$_POST['type'];
					$params['email']=$_POST['email'];
					$params['from']=$_POST['from'];
					if(!$id)
						$params['add_date']=date('y-m-d H:i:s');
					$params['update_date']=date('y-m-d H:i:s');

					if($id)
					{
						$this->models->newsletters_emails->update($params,'id='.$id);
					}
					else
					{
						$id=$this->models->newsletters_emails->insert($params);
					}

					$this->add_message('success','S-a salvat e-mailul!');
					if(isset($_POST['return']) && $_POST['return'])
						$this->redirect($this->paths['current']);
					else
						$this->redirect($this->paths['root_component'].'?a=edit:'.$id);
				}
			}
			else
			{
				$this->add_message('error','S-au intalnit probleme!');
				$this->redirect($this->paths['root_component'].'?a=add');
			}
		}

		function action_edit($id)
		{
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1');
			$email=$this->models->newsletters_emails->get($id);
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
				'Modificare e-mail'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'save:'.$id
						),
						'Tipul de e-mail:'=>array(
							'name'=>'type',
							'type'=>'select',
							'value'=>$email['type_id'],
							'default'=>'',
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types
						),
						'E-mail:'=>array(
							'name'=>'email',
							'type'=>'text',
							'value'=>$email['email'],
							'validate'=>array('required'=>'Completati e-mailul!',
												'email'=>'Completati email valid!')
						),
						'Sursa:'=>array(
							'name'=>'from',
							'type'=>'text',
							'value'=>$email['from']
						)
					)
				)
			);
			$this->assign('form',$form);
		}

		function action_delete($id)
		{
			$this->models->newsletters_emails->delete($id);
			die;
		}

		function action_active($id,$value)
		{
			$this->models->newsletters_emails->set_active($id,$value,"active");
			die;
		}

		function action_import()
		{
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1');
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Importa';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Trimite newsletter'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'import_save'
						),
						'Tipul de e-mail:'=>array(
							'name'=>'type',
							'type'=>'select',
							'value'=>'',
							'default'=>'',
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types
						),
						'Sursa:'=>array(
							'name'=>'from',
							'type'=>'text',
							'value'=>''
						),
						'Fisier:'=>array(
							'name'=>'file',
							'type'=>'file',
							'value'=>'',
							'validate'=>array('required'=>'Adaugati un fisier!')
						)
					)
				)
			);
			$this->assign('form',$form);
		}

		function action_import_save()
		{
			$this->system->import('library',"ContentParser");
			set_time_limit(1000000);
			ini_set('memory_limit','1024M');
			$content=file_get_contents($_FILES['file']['tmp_name']);

			$parser=new ContentParser($content);
			$ems=$parser->get_emails();

			$found=count($ems);
			$valids=0;
			$params=array();
			$ems=$this->models->newsletters_emails->ValidateEmails($ems,$_POST['type']);
			foreach($ems as $k=>$v)
			{

					$valids++;
					$prms=array();
					$prms['type_id']=$_POST['type'];
					$prms['email']=$v;
					$prms['from']=$_POST['from'];
					$prms['add_date']=date('y-m-d H:i:s');
					$prms['update_date']=date('y-m-d H:i:s');
					$params[]=$prms;

				$this->models->emails_valid->update_field_cond('active',1,'email = \''.$v.'\'');
			}
			/*
			$this->models->newsletters_emails->insert_multiple(array('type_id','email','from','add_date','update_date'),$params);\
			*/
			$this->add_message('success','S-au gasit '.$found.' adrese din care '.$valids.' valide!');
			$this->redirect($this->paths['root_component'].'?a=import');
		}

		function action_export()
		{
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1');
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Exporta';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Trimite newsletter'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'export_save'
						),
						'Tipul de e-mail:'=>array(
							'name'=>'type',
							'type'=>'select',
							'value'=>'',
							'default'=>'',
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types,
							'validate'=>array('required'=>'Alegeti un tip de e-mail-uri pentru a le exporta!')
						)
					)
				)
			);
			$this->assign('form',$form);
		}

		function action_export_save()
		{
			set_time_limit(1000000);
			ini_set('memory_limit','128M');
			$emails=$this->models->newsletters_emails->get_all('','','','','type_id="'.$_POST['type'].'" and active=1');

			$content='';
			foreach($emails as $v)
				$content.="\n\r".$v['email'];

			// We'll be outputting a PDF
			header('Content-type: text/plain');

			// It will be called downloaded.pdf
			header('Content-Disposition: attachment; filename="emails.txt"');

			echo $content;

			die();
		}

		function action_unsubscribe()
		{
			$types=$this->models->newsletters_emails_types->get_all('','','','','active=1 and unsubscribe_active=1');
			$form=array();
			$form['name']='texte_form';
			$form['id']='texte_form';
			$form['btn_submit_text']='Verifica';
			$form['btn_reset_text']='Reset';
			$form['btn_cancel_text']='Cancel';
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				'Verifica mail'=>array(
					'fields'=>array(
						'Actiunea:'=>array(
							'name'=>'a',
							'type'=>'hidden',
							'value'=>'unsubscribe_check'
						),
						'Tipul de e-mail:'=>array(
							'name'=>'type',
							'type'=>'select',
							'value'=>'',
							'default'=>'',
							'option_field_value'=>'id',
							'option_field_text'=>'name',
							'options'=>$types
						)
					)
				)
			);
			$this->assign('form',$form);
		}

		function ValidMessage($subject,$from,$kwds)
		{
			$ok=false;
			foreach($kwds as $v)
				if(!(strpos($subject,$v)===false))
					$ok=true;

			return $ok;
		}

		function get_emails($subject,$body,$from,$to,$type_id)
		{
			$emails='';

			$parser=new ContentParser($body);
			$ems=$parser->get_emails();
			if($from != $to)
			{
				$ems[]=$from;
			}

				foreach($ems as $v)
				{
					if($v!=$to)
					{
						$em=$this->models->newsletters_emails->get_cond('email=\''.$v.'\' and active=1 and type_id='.$type_id);
						if(isset($em['id']))
						{
							$emails[]=$em;
						}
					}
				}

			return $emails;
		}

		function action_unsubscribe_check()
		{
			set_time_limit(1000000);
			$type=$this->models->newsletters_emails_types->get($_POST['type']);
			$keywords=explode(',',$type['unsubscribe_keywords']);

			$obj= new receiveMail($type['unsubscribe_username'],$type['unsubscribe_password'],$type['unsubscribe_email'],$type['unsubscribe_mailserver'],$type['unsubscribe_servertype'],$type['unsubscribe_serverport']);

			$obj->connect();

			$found=0;
			$tot=$obj->getTotalMails();

			for($i=1;$i<=$tot;$i++)
			{
				@$head=$obj->getHeaders($i);
				$subject=$head['subject'];
				$from=$head['from'];


				$to=$head['to'];

				if($this->ValidMessage($subject,$from,$keywords) )
				{
					@$body=$obj->getBody($i);
					$ems=$this->get_emails($subject,$body,$from,$to,$_POST['type']);
					if($ems)
						foreach($ems as $em)
						{
							$found++;
							$this->models->newsletters_emails->update_field($em['id'],'active',0);
							$obj->deleteMails($i); // Delete Mail from Mail box
						}
				}
			}
			$obj->close_mailbox();   //Close Mail Box

			$this->add_message('success','S-au gasit ',$tot,' mailuri la adresa specificata, din care ',$found,' mailuri de dezabonare.');
		}
	}
?>