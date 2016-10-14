<?php


class images extends Base
{
	var $manager;

	function __construct()
	{
		$this->table=$this->tbl_images;
		global $page;
		$page->import("class","managers.ImageManager");
		$this->manager=new ImageManager();
	}
	
	function save_url($url,$type,$id=""){
		global $dal;
		global $page;

		$files_manager=$page->files_manager;

		$original_http_path="";
		$original_local_path="";
		$current_http_path="";
		$current_local_path="";

		$obj=$this->get($id);
		$obj_type=$dal->image_types->get_by_name($type);
		if($url)
		{
			if($url)
			{
				$filename="img_".$type."_".md5(microtime()).".".$files_manager->get_extension($url);
				$filepath=$files_manager->folder;

				// save original
				file_put_contents($filepath.$filename, file_get_contents($url));
				$original_http_path=$page->paths['root_dir'].'files/'.$filename;
				$original_local_path=$filepath.$filename;
				$current_http_path=$page->paths['root_dir'].'files/'.$filename;
				$current_local_path=$filepath.$filename;
			}

			if($id && $this->exists("id",$id))
			{
				$params=array();
				$params['type_id']=$obj_type['id'];
				$params['current_http_path']=$current_http_path;
				$params['current_local_path']=$current_local_path;
				$params['original_http_path']=$original_http_path;
				$params['original_local_path']=$original_local_path;
				$params['image_type']='image/png';

				$this->update($params,"id=$id");

				if(file_exists($obj['original_local_path']))
					unlink($obj['original_local_path']);

				if(file_exists($obj['current_local_path']))
					unlink($obj['current_local_path']);
			}
			else
			{
				$params=array();
				$params['type_id']=$obj_type['id'];
				$params['current_http_path']=$current_http_path;
				$params['current_local_path']=$current_local_path;
				$params['original_http_path']=$original_http_path;
				$params['original_local_path']=$original_local_path;
				$params['image_type']='image/png';

				$id=$this->insert($params);
			}

			return $id;
		}
		return 0;
	}

	function save_request($name,$type,$id="")
	{
		global $dal;
		global $page;

		$files_manager=$page->files_manager;

		$original_http_path="";
		$original_local_path="";
		$current_http_path="";
		$current_local_path="";

		$obj=$this->get($id);
		$page->import('dal','image_types');
		$obj_type=$dal->image_types->get_by_name($type);
		if($_FILES[$name]['name']!="")
		{
			$page->load_library("images");
			$file_type=$_FILES[$name]['type'];

			if($_FILES[$name]['tmp_name'])
			{
				$filename="img_".$type."_".md5(microtime()).".".$files_manager->get_extension($_FILES[$name]['name']);
				$filepath=$files_manager->folder;

				// save original
				$files_manager->save_upload($name,"",$filename);
				$original_http_path=$files_manager->last_file;
				$original_local_path=$files_manager->last_local_file;

				// modify image
				if(!is_dir($filepath.$obj_type['folder']."/"))mkdir($filepath.$obj_type['folder']."/");

				$current_http_path=$page->paths['root'].$filepath.$obj_type['folder']."/".$filename;
				$current_local_path=$files_manager->local_root.$filepath.$obj_type['folder']."/".$filename;
				/**
				 * $this->manager->output_resized_image_proportional($original_local_path,$file_type,$current_local_path,$obj_type['width'],$obj_type['height']);
			     */
			}

			if($id && $this->exists("id",$id))
			{
				$params=array();
				$params['type_id']=$obj_type['id'];
				$params['current_http_path']=$current_http_path;
				$params['current_local_path']=$current_local_path;
				$params['original_http_path']=$original_http_path;
				$params['original_local_path']=$original_local_path;
				$params['image_type']=$file_type;

				$this->update($params,"id=$id");

				if(file_exists($obj['original_local_path']))
					unlink($obj['original_local_path']);

				if(file_exists($obj['current_local_path']))
					unlink($obj['current_local_path']);
			}
			else
			{
				$params=array();
				$params['type_id']=$obj_type['id'];
				$params['current_http_path']=$current_http_path;
				$params['current_local_path']=$current_local_path;
				$params['original_http_path']=$original_http_path;
				$params['original_local_path']=$original_local_path;
				$params['image_type']=$file_type;

				$id=$this->insert($params);
			}

			return $id;
		}
		return 0;
	}

	function delete($id,$callbacks=true)
	{
		$obj=$this->get($id);

		if(file_exists($obj['original_local_path']))
			unlink($obj['original_local_path']);

		if(file_exists($obj['current_local_path']))
			unlink($obj['current_local_path']);

		parent::delete($id);
	}

	function ReProcess($id,$width,$height)
	{
		global $dal;
		$img=$this->get($id);
		if(file_exists($img['original_local_path']))
			$this->manager->output_resized_image_proportional($img['original_local_path'],$img['image_type'],$img['current_local_path'],$width,$height);
	}

	function import_url($url,$type,$id=0)
	{
		global $dal;
		global $page;
		$page->load_library("images");
		$files_manager=$page->files_manager;

		$original_http_path="";
		$original_local_path="";
		$current_http_path="";
		$current_local_path="";

		$obj=$this->get($id);
		$page->import('dal','image_types');
		$obj_type=$dal->image_types->get_by_name($type);


		if($files_manager->GetExtension($url))
			$filename="img_".$type."_".md5(microtime()).".".$files_manager->GetExtension($url);
		else
			$filename="img_".$type."_".md5(microtime()).".jpg";
		$filepath=$files_manager->local_root.$files_manager->folder;

		$this->manager->import_url($url,$filepath.$filename);
		$original_http_path=$files_manager->http_root.$files_manager->folder.$filename;
		$original_local_path=$filepath.$filename;

		// modify image
		if(!is_dir($filepath.$obj_type['folder']."/"))mkdir($filepath.$obj_type['folder']."/");

		$current_http_path=$files_manager->http_root.$files_manager->folder.$obj_type['folder']."/".$filename;
		$current_local_path=$files_manager->local_root.$files_manager->folder.$obj_type['folder']."/".$filename;
		$this->manager->output_resized_image_proportional($original_local_path,'',$current_local_path,$obj_type['width'],$obj_type['height']);

		if($id && $this->exists("id",$id))
		{
			$obj=$this->get($id);
			$params=array();
			$params['type_id']=$obj_type['id'];
			$params['current_http_path']=$current_http_path;
			$params['current_local_path']=$current_local_path;
			$params['original_http_path']=$original_http_path;
			$params['original_local_path']=$original_local_path;
			$params['image_type']=filetype($original_local_path);

			$this->update($params,"id=$id");

			if(file_exists($obj['original_local_path']))
				unlink($obj['original_local_path']);

			if(file_exists($obj['current_local_path']))
				unlink($obj['current_local_path']);
		}
		else
		{
			$params=array();
			$params['type_id']=$obj_type['id'];
			$params['current_http_path']=$current_http_path;
			$params['current_local_path']=$current_local_path;
			$params['original_http_path']=$original_http_path;
			$params['original_local_path']=$original_local_path;
			$params['image_type']=filetype($original_local_path);

			$id=$this->insert($params);
		}

		return $id;
	}
}

?>