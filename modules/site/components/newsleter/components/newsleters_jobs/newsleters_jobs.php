<?php
	class newsleters_jobs_page extends Page
	{
		var $table="";
		
		function on_init()
		{
			$this->allowed_actions=array("add","edit","save","send","send_mail","update","delete","active");
			$this->assign("page_title","Trimiteri de scrisori");
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
				$this->models->newsletters_jobs->update($pars,"id=".$id);
			}
			
			$this->add_message("success","S-a salvat scrisoarea!");
			$this->redirect($this->paths['current']."?a=edit:".$id);
		}
		
		function action_edit($id=0)
		{			
			$newsletter=$this->models->newsletters_jobs->get($id);
			
			$form=array();
			$form['name']="texte_form";
			$form['id']="texte_form";
			$form['btn_submit_text']="Salveaza";
			$form['btn_reset_text']="Reset";
			$form['btn_cancel_text']="Cancel";
			$form['btn_cancel_link']=$this->paths['current'];
			$form['zones']=
			array(
				"Modificare scrisoare"=>array(
					"fields"=>array(
						"Actiunea:"=>array(
							"name"=>"a",
							"type"=>"hidden",
							"value"=>"save:".$newsletter['id']
						),
						"Subiect:"=>array(
							"name"=>"subject",
							"type"=>"text",
							"value"=>$newsletter['subject'],
							"validate"=>array("required"=>"Completati subiectul!")
						),						
						"Mesaj:"=>array(
							"name"=>"message",
							"type"=>"htmleditor",
							"value"=>$newsletter['message'],
							"validate"=>array("required"=>"Completati continutul scrisorii!")
						),
						"De la adresa:"=>array(
							"name"=>"from_address",
							"type"=>"text",
							"value"=>$newsletter['from_address'],
							"validate"=>array("required"=>"Completati adresa de la care se trimite mailul!",
												"email"=>"Completati un email valid!"
										)
						),
						"De la nume:"=>array(
							"name"=>"from_name",
							"type"=>"text",
							"value"=>$newsletter['from_name'],
							"validate"=>array("required"=>"Completati numele sub care se trimite mailul!")
						),
						"Raspuns la adresa:"=>array(
							"name"=>"reply_address",
							"type"=>"text",
							"value"=>$newsletter['reply_address'],
							"validate"=>array("required"=>"Completati adresa de la care se va putea face raspuns!",
												"email"=>"Completati un email valid!"
										)
						),
						"Raspuns la nume:"=>array(
							"name"=>"reply_name",
							"type"=>"text",
							"value"=>$newsletter['reply_name'],
							"validate"=>array("required"=>"Completati numele sub care apare adresa la care se face raspuns!")
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
		}
		
		function init_table($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_newsletters_jobs';			
			if($data)
			{			
				$this->table->header=array(
					array(
						'col'=>'id',
						'hidden'=>1
					),
					array(
						'col'=>'total',
						'hidden'=>1
					),
					array(
						'col'=>'sent',
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
						'name'=>'Data trimitere',
						'col'=>'start_date',
						'date'=>1					
					),
					array(
						'name'=>'Status',
						'col'=>'status',
						'eval'=>'{if $o.active.value}
				{tr tags="tables"}Activa:{/tr} {$o.sent.value} {tr tags="tables"}trimise{/tr} / {$o.total.value} total {if $o.total.value}({math equation="x*100/y" x=$o.sent.value y=$o.total.value} %){/if}
				{else}
				{tr tags="tables"}Dezactivata{/tr}
				{/if}',
						'sort'=>0
					),
					array(
						'name'=>'Activ',
						'col'=>'active',
						'eval'=>'<a href="#" onclick="ajax_active(\'{$current}\',{$o.id.value},{if $o.active.value==0}1{else}0{/if},\'$(\\\'#j_{$table.id}\\\').gridview(\\\'refresh\\\');\');return false;" title="{if $o.active.value==1}{tr tags="buttons"}Dezactiveaza{/tr}{else}{tr tags="buttons"}Activeaza{/tr}{/if}"><b class="icon {if $o.active.value==1}icon-accept{else}icon-ban{/if}"></b></a>'
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->newsletters_jobs->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,'',true,$this->table->get_search_fields(),$this->table->search_keyword));				
				$this->system->no_total_rows=$this->models->newsletters_jobs->total_rows;
			
				$this->table->add_action('Sterge','','','delete:{$o.id.value}',1,'icon-trash-o','Sunteti sigur ca doriti sa stergeti?');
				$this->table->add_action('Modifica','',$this->paths['root_component'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
			}
			$this->table->update_action='update';			
			$this->table->edit_link='none';
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows; 
		}
				
		function action_delete($id)
		{
			$this->models->newsletters_jobs->delete($id);
			die();
		}
	
		function action_active($id,$value)
		{
			$this->models->newsletters_jobs->set_active($id,$value,"active");
			die();
		}
		
	}
?>