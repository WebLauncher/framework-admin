<?php
	class banners_page extends Page
	{
		var $table="";
		
		function on_init()
		{
			$this->allowed_actions=array(
				"save_zone",
				"save_banner",
				"add_zone",
				"edit_zone",
				"add_banner",
				"edit",
				"banners",
				"add",
				"update_zones",
				"update_banners",
				"delete",
				"delete_banner",
				"active"
			);
		}
		
		function on_load()
		{
			if(!isset($this->system->actions[0]))
			{
				$this->assign("page_title","Publicitate - Zone Banere");
				$this->init_table_zones();
				
				$this->assign("table",$this->table->get_array());
			}
		}
		
		function action_save_zone($id=0)
		{
			$width=$_REQUEST['width'];
			$height=$_REQUEST['height'];
			$allowed_types=$_REQUEST['allowed_types'];
			
			
			$id=$this->models->banners_zones->save($id,isset($_REQUEST['name'])?$_REQUEST['name']:"",$width,$height,$allowed_types);
			
			$this->add_message("success","S-a salvat zona!");
			if(isset($_POST['return']) && $_POST['return'])
				$this->redirect($this->paths['current']);
			else
				$this->redirect($this->paths['current']."?a=edit_zone:$id");
		}
		
		function action_save_banner($id=0)
		{
			$zone_id=($_REQUEST['zone_id']?$_REQUEST['zone_id']:$id);
			$obj_zone=$this->models->banners_zones->get($zone_id);
			
			$type=$_REQUEST['type'];
			$obj_type=$this->models->banners_types->get($type);
			
			$obj=$this->models->banners->get($id);
			
			
			$content=$_REQUEST['_content'];
			$name=$_REQUEST['name'];
			$percent=$_REQUEST['percent'];
			$is_active=$_REQUEST['is_active'];
			$link=$_REQUEST['link'];
			
			if($_FILES['file']['name'])
			{
				switch($obj_type['name'])
				{
					case "image":
						if(!$this->models->image_types->get_by_name("banners_".$obj_zone['name']))
							$this->models->image_types->save("","banners_".$obj_zone['name'],$obj_zone['width'],$obj_zone['height'],"banners_".$obj_zone['name']);
						$content=$this->models->images->save_request('file',"banners_".$obj_zone['name']);
					break;
					case "flash":
						$filename=md5(microtime()).".".$this->system->files_manager->get_extension($_FILES['file']['name']);
						$this->system->files_manager->save_upload("file","banners_flash/",$filename);
						$content=$this->system->files_manager->folder."banners_flash/".$filename;
					break;
					case "script":
					case "text":
						$content=$this->system->files_manager->get_upload_content("file");
					break;
				}
			}
			
			$id=$this->models->banners->save($id,$name,$zone_id,$type,$content,$percent,$is_active,$link,$_POST['language_id'],$_POST['target']);

			$this->add_message("success","S-a salvat banerul!");
			if(isset($_POST['return']) && $_POST['return'])
				$this->redirect($this->paths['current']."?a=banners:$zone_id");
			else
				$this->redirect($this->paths['root_component']."?a=edit:$zone_id:$id");
			
		}
		
		function action_add_zone()
		{
			$types=$this->models->banners_types->get_all();

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
						"Adaugare zona bannere"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save_zone"
								),
								"Nume:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati numele!")
								),
								"Latime:"=>array(
									"name"=>"width",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati latimea (numar)!",
										"number"=>"Completati un numar!")
								),
								"Inaltimea:"=>array(
									"name"=>"height",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati inaltimea (numar)!",
										"number"=>"Completati un numar!")
								),
								"Tipuri active:"=>array(
									"name"=>"allowed_types",
									"type"=>"checkboxlist",
									"options"=>$types
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_edit_zone($id=0)
		{
			$this->models->import('banners_types');
			$this->models->import('banners_zones');
					$types=$this->models->banners_types->get_all();
					$zone=$this->models->banners_zones->get($id);
										
					foreach($types as $k=>$v)
					{
						if(in_array($v['id'],$zone['allowed_types']))
							$types[$k]['checked']=1;
					}
					
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
						"Modificare zona bannere"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save_zone:".$zone['id']
								),
								"Nume:"=>array(
									"name"=>"name",
									"type"=>"none",
									"value"=>$zone['name']
								),
								"Latime:"=>array(
									"name"=>"width",
									"type"=>"text",
									"value"=>$zone['width'],
									"validate"=>array("required"=>"Completati latimea (numar)!",
										"number"=>"Completati un numar!")
								),
								"Inaltimea:"=>array(
									"name"=>"height",
									"type"=>"text",
									"value"=>$zone['height'],
									"validate"=>array("required"=>"Completati inaltimea (numar)!",
										"number"=>"Completati un numar!")
								),
								"Tipuri active:"=>array(
									"name"=>"allowed_types",
									"type"=>"checkboxlist",
									"options"=>$types
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
	
		function action_add_banner($zone_id=0)
		{
				$zone=$this->models->banners_zones->get($zone_id);
				$this->models->import('languages');
					$types=array();
					foreach($zone['allowed_types'] as $k=>$v)
					{
						$tip=$this->models->banners_types->get($v);
						$types[$tip['name']]=$v;
					}
					
					$targets=array("_self"=>"_self","_blank"=>"_blank","_parent"=>"_parent","_top"=>"_top");
					$languages=$this->models->languages->GetActive();
					
					$percs=array();
					for($i=1;$i<=100;$i++)
						$percs[$i]["value"]=$i;
					
					$form=array();
					$form['name']="banners_zones_form";
					$form['id']="banners_zones_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current']."?a=banners:".$zone['id'];
					$form['zones']=
					array(
						"Adaugare baner"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save_banner"
								),
								"Zona_id:"=>array(
									"name"=>"zone_id",
									"type"=>"hidden",
									"value"=>$zone['id']
								),
								"Zona:"=>array(
									"name"=>"zona_name",
									"type"=>"none",
									"value"=>$zone['name']
								),
								"Dimensiune:"=>array(
									"name"=>"zona_desc",
									"type"=>"none",
									"value"=>$zone['width']."px / ".$zone['height']."px"
								),
								"Nume banner:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>"",
									"validate"=>array("required"=>"Completati numele!")
								),
								"Limba:"=>array(
									"name"=>"language_id",
									"type"=>"radioimages",
									"value"=>"",
									"field_value"=>"id",
									"field_image"=>"image_id",
									"field_title"=>"valoare",
									"options"=>$languages,
									"validate"=>array("required"=>"Alegeti limba!")
								),
								"Link:"=>array(
									"name"=>"link",
									"type"=>"text",
									"value"=>""
								),
								"Tipul:"=>array(
									"name"=>"type",
									"type"=>"radiolist",
									"value"=>2,
									"options"=>$types
								),
								"Continut fisier:"=>array(
									"name"=>"file",
									"type"=>"file"
								),
								"Continut text:"=>array(
									"name"=>"_content",
									"type"=>"htmleditor",
									"value"=>""
								),
								"Target:"=>array(
									"name"=>"target",
									"type"=>"radiolist",
									"value"=>"_self",
									"options"=>$targets
								),
								"Procent:"=>array(
									"name"=>"percent",
									"type"=>"select",
									"value"=>"100",
									"default"=>"",
									"default_show"=>"true",
									"default_text"=>"- alegeti procentul -",
									"option_field_value"=>"value",
									"option_field_text"=>"value",
									"options"=>$percs,
									"validate"=>array("required"=>"Selectati un procent!")
								),
								"Procent:"=>array(
									"name"=>"percent",
									"type"=>"slider",
									"value"=>100,
									"min"=>1,
									"max"=>100,
									"step"=>1
								),
								"Activ:"=>array(
									"name"=>"is_active",
									"type"=>"radiolist",
									"value"=>1,
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_add()
		{
				$zones=$this->models->banners_zones->get_all();
										
				$this->assign("zones",$zones);
		}
		
		function action_edit($zone_id,$id)
		{
			$this->models->import('languages');
			$zone=$this->models->banners_zones->get($zone_id);
					$banner=$this->models->banners->get($id);
					
					$types=array();
					foreach($zone['allowed_types'] as $k=>$v)
					{
						$tip=$this->models->banners_types->get($v);
						$types[$tip['name']]=$v;
					}
					
					$targets=array(tr("Aceeasi pagina")=>"_self",tr("Pagina noua")=>"_blank",tr("Frame-ul parinte")=>"_parent",tr("Frame-ul de top")=>"_top");
					$languages=$this->models->languages->GetActive();
					
					$percs=array();
					for($i=1;$i<=100;$i++)
						$percs[$i]["value"]=$i;
					
					$form=array();
					$form['name']="banners_zones_form";
					$form['id']="banners_zones_form";
					$form['btn_submit_text']="Salveaza";
					$form['btn_submit_return_text']="Salveaza si afiseaza lista";
					$form['btn_reset_text']="Reset";
					$form['btn_cancel_text']="Cancel";
					$form['btn_cancel_link']=$this->paths['current']."?a=banners:".$zone['id'];
					$form['zones']=
					array(
						"Adaugare baner"=>array(
							"fields"=>array(
								"Actiunea:"=>array(
									"name"=>"a",
									"type"=>"hidden",
									"value"=>"save_banner:".$banner['id']
								),
								"Zona_id:"=>array(
									"name"=>"zone_id",
									"type"=>"hidden",
									"value"=>$zone['id']
								),
								"Zona:"=>array(
									"name"=>"zona_name",
									"type"=>"none",
									"value"=>$zone['name']
								),
								"Dimensiune:"=>array(
									"name"=>"zona_desc",
									"type"=>"none",
									"value"=>$zone['width']."px / ".$zone['height']."px"
								),
								"Nume banner:"=>array(
									"name"=>"name",
									"type"=>"text",
									"value"=>$banner['name'],
									"validate"=>array("required"=>"Completati numele!")
								),
								"Limba:"=>array(
									"name"=>"language_id",
									"type"=>"radioimages",
									"value"=>$banner['language_id'],
									"field_value"=>"id",
									"field_image"=>"image_id",
									"field_title"=>"valoare",
									"options"=>$languages,
									"validate"=>array("required"=>"Alegeti limba!")
								),
								"Link:"=>array(
									"name"=>"link",
									"type"=>"text",
									"value"=>$banner['link']
								),
								"Tipul:"=>array(
									"name"=>"type",
									"type"=>"radiolist",
									"default"=>2,
									"value"=>$banner['type_id'],
									"options"=>$types
								),
								"Continut fisier:"=>array(
									"name"=>"file",
									"type"=>"file"
								),
								"Continut text:"=>array(
									"name"=>"_content",
									"type"=>"htmleditor",
									"value"=>$banner['content']
								),
								"Deschide link-ul in:"=>array(
									'description'=>'Targetul linkului cand se apara pe banner. Ultimele 2 optiuni sunt doar in cazul utilziarii frameurilor pe site.',
									"name"=>"target",
									"type"=>"radiolist",
									"value"=>$banner['target'],
									"options"=>$targets
								),
								"Procent:"=>array(
									'description'=>'Acesta este procentul de aparitie pe site. Daca procentul este mai mare banerul va aparea mai des.',
									"name"=>"percent",
									"type"=>"slider",
									"value"=>$banner['percent'],
									"min"=>1,
									"max"=>100,
									"step"=>1
								),
								"Activ:"=>array(
									'description'=>'De aici se poate dezactiva aparitia banerului pe site.',
									"name"=>"is_active",
									"type"=>"radiolist",
									"value"=>$banner['is_active'],
									"default"=>0,
									"options"=>array(
										"Nu"=>0,
										"Da"=>1
									)
								)
							)
						)
					);
					
					$this->assign("form",$form);
		}
		
		function action_banners($zone_id)
		{
			$this->init_table_banners(0,$zone_id);
			$this->assign("zones",$this->models->banners_zones->get_all());
			$this->assign("table",$this->table->get_array());
		}
	
		function action_update_zones()
		{
			$this->init_table_zones(1);
			
			$this->table->display_data();
		}
		
		function action_update_banners($id)
		{
			$this->init_table_banners(1,$id);
			
			$this->table->display_data();
		}
	
		function init_table_zones($data=0)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_banners_zones';
			if($data)
			{
				$this->table->header=array(
					array(
						"col"=>"id",
						"hidden"=>1
					),
					array(
						"col"=>"height",
						"hidden"=>1
					),
					array(
						"col"=>"width",
						"hidden"=>1
					),
					array(
						"name"=>"Nume",
						"col"=>"name",
						"strong"=>1
					),
					array(
						"name"=>"Dimensiune",
						"col"=>"dimension",
						"eval"=>'{$o.width.value}px / {$o.height.value}px'
					),
					array(
						"name"=>"Bannere",
						"col"=>"banners",
						"eval"=>'<a href=\'{$root_component}?a=banners:{$o.id.value}\'>{$o.banners.value|default:0}</a>&nbsp;<a href=\'{$root_component}?a=add_banner:{$o.id.value}\'>{tr}Adauga{/tr}</a>'
					)
				);
			
				$this->table->process_request();
			
				$this->table->process_content($this->models->banners_zones->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,"",true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->banners_zones->total_rows;
			
				$this->table->add_action("Sterge","","",'delete:{$o.id.value}',1,"icon-trash-o","Sunteti sigur ca doriti sa stergeti?");
				$this->table->add_action("Modifica","",$this->paths['root_component'].'?a=edit_zone:{$o.id.value}',"",0,"icon-pencil");
				$this->table->add_action("Adauga banner","",$this->paths['root_component'].'?a=add_banner:{$o.id.value}',"",0,"icon-plus");
			}
			$this->table->update_action="update_zones";
			$this->table->edit_link="none";
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}
		
		function init_table_banners($data=0,$id)
		{
			$this->table=new AjaxTable();
			$this->table->id='table_banners_zone_'.$id;
			$this->models->import('languages');
			if($data)
			{
				$this->table->header=array(
					array(
						"col"=>"id",
						"hidden"=>1
					),
					array(
						"col"=>"zone_id",
						"hidden"=>1
					),
					array(
						"name"=>"Nume",
						"col"=>"name",
						"strong"=>1
					),
					array(
						"name"=>"Tip",
						"col"=>"type_id",
						"bind"=>array(
							"table"=>$this->models->banners_types->table,
							"get_field"=>"name"
						)
					),
					array(
						"name"=>"Limba",
						"col"=>"language_id",
						"image"=>1,
						"bind"=>array(
							"table"=>$this->models->languages->table,
							"get_field"=>"image_id"
						)
					),
					array(
						"name"=>"Target",
						"col"=>"target"
					),
					array(
						"name"=>"Activ",
						"col"=>"is_active",
						"active"=>1
					),
					array(
						"name"=>"Vezi",
						"col"=>"content",
						"eval"=>'<a href="#" id="view_banner_{$o.id.value}" title="#banner_{$o.id.value}"><b class="icon icon-eye"></b></a>
				<div id="banner_{$o.id.value}" style="display:none;">
				{include file=$p.objects.templates.banner banner_id=$o.id.value}
				</div>'
					)
				);
			
				$this->table->process_request();
				
				$this->table->process_content($this->models->banners->get_all($this->system->page_skip,$this->system->page_offset,$this->table->sort_by,$this->table->sort_dir,"zone_id=".$id,true,$this->table->get_search_fields(),$this->table->search_keyword));
				$this->system->no_total_rows=$this->models->banners->total_rows;
			
				$this->table->add_action("Sterge","","",'delete_banner:{$o.id.value}',1,"icon-trash-o","Sunteti sigur ca doriti sa stergeti?");
				$this->table->add_action("Modifica","",$this->paths['root_component'].'?a=edit:{$o.zone_id.value}:{$o.id.value}',"",0,"icon-pencil");
			}
			$this->table->update_action="update_banners:".$id;
			$this->table->edit_link="none";
			$this->table->sort_col_no=1;
			$this->table->total=$this->system->no_total_rows;
		}
	
		function action_delete($id)
		{
			$this->models->banners_zones->delete($id);
			die();
		}
		
		function action_delete_banner($id)
		{
			$this->models->import('banners');
			$this->models->banners->delete($id);
			die;
		}
		
		function action_active($id,$value)
		{
			$this->models->banners->set_active($id,$value);
			die;
		}
	}
?>
