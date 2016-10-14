<?php	
	
	class paginations_page extends Page
	{
		function on_init()
		{
			$this->allowed_actions=array("add","save","edit");
			$this->assign("page_title","Configurare paginari pe pagini");
		}
		
		function on_load()
		{
			if(!isset($this->system->actions[0]))
			{
					$table=array();
					$sort_dir="";
					$sort_by="";
					if(isset($_GET['sortBy']))
					{	
						if(isset($_GET['sortAscending'])){
							if($_GET['sortAscending']=='false')$sort_dir="desc";	
						}
						
						if(isset($_GET['startIndex']) && $_GET['startIndex']){
							$this->system->page_skip=$_GET['startIndex'];
						}
						switch($_GET['sortBy'])
						{
							default:
								$sort_by=$_GET['sortBy'];
							break;
						}
					} 
					
					$table['content']=$this->models->xpaginations->get_all($this->system->page_skip,$this->system->page_offset,$sort_by,$sort_dir);
					$this->system->no_total_rows=$this->models->xpaginations->count_all();
					$header=array(
						"id"=>"id",
						"Pagina:"=>"page",
						"Numar de randuri"=>"offset",
						"Actiuni"=>"actions"
					);
					$table["header"]=$header;
					$table["update_url"]="ajax/tabledata_paginari";
					$table["id"]='mytable';
					$table["edit_link"]="none";
					$table['sort_col_no']=1;
					$this->assign("table",$table);
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
						"Modificare meta"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save"
								),
								"Pagina:"=>array(
									"name"=>"page",
									"type"=>"text",
									"value"=>""
								),
								"Numar de randuri:"=>array(
									"name"=>"offset",
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
			$obj=$this->models->xpaginations->get($id);
					
					$form=array();
					$form['name']="language_form";
					$form['id']="language_form";
					$form['btn_submit_text']="Salveaza";
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
								"Pagina:"=>array(
									"name"=>"apge",
									"type"=>"text",
									"value"=>$obj['page']
								),
								"Numar de randuri:"=>array(
									"name"=>"offset",
									"type"=>"text",
									"value"=>$obj['offset']
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}

		function action_save($id=0)
		{
					$params=array();
					$params['page']=$_POST['page'];					
					$params['offset']=$_POST['offset'];
					if($id)
					{
						$this->models->xpaginations->update($params,"id=".$id);						
					}
					else
					{
						$id=$this->models->xpaginations->insert($params);
					}
					$this->add_message("success","S-a salvat valuta!");
					$this->redirect($this->paths['root_component']."paginari?a=edit:$id");
		}
	}
	
?>