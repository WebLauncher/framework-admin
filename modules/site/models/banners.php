<?php
    class banners extends Base
	{
		function __construct()
		{
			$this->table=$this->tbl_banners;
		}
						
		function delete($id,$callbacks=true)
		{
			global $dal;
			$obj=$this->get($id);

			switch($obj['type']['name'])
			{
				case "image":
					$dal->images->delete($obj['content']);
				break;
				case "flash":
					if(file_exists($obj['content']))
						unlink(str_replace("/","\\",$obj['content']));
				break;
			}
			
			parent::delete($id);
		}
		
		function delete_zone($id)
		{
			if($id)
			{
				$zone_banners=$this->get_all("","","","","zone_id=".$id);
				
				foreach($zone_banners as $v)
					$this->delete($v['id']);
			}
		}
		
		function count_zone($id)
		{
			$query="select count(id) from `".$this->table." where zone_id=$id";
			return $this->db->getOne($query);
		}
		
		function save($id,$name,$zone_id,$type_id,$content,$percent,$is_active,$link,$language_id,$target)
		{
			global $dal;
			if($id)
			{
				$obj=$this->get($id);
				
				$type=$dal->banners_types->get_field($obj['type_id'],"name");
				if($content!=$obj['content'])
				{
					if($type=="image")
						$dal->images->delete($obj['content']);
					if($type=="flash")
						if(file_exists($obj['content']))unlink($obj['content']);
				}
				$params=array();
				$params['name']=$name;
				$params['zone_id']=$zone_id;
				$params['type_id']=$type_id;
				$params['content']=$content;
				$params['percent']=$percent;
				$params['link']=$link;
				$params['is_active']=$is_active;
				$params['language_id']=$language_id;
				$params['target']=$target;
				
				$this->update($params,"id=$id");
			}
			else
			{
				$params=array();
				$params['name']=$name;
				$params['zone_id']=$zone_id;
				$params['type_id']=$type_id;
				$params['content']=$content;
				$params['percent']=$percent;
				$params['link']=$link;
				$params['is_active']=$is_active;
				$params['language_id']=$language_id;
				$params['target']=$target;
				
				$id=$this->insert($params);
			}
			
			
			return $id;
		}
		
		function process_row($row)
		{
			global $dal;
			$row['type']=$dal->banners_types->get($row['type_id']);
			
			return $row;
		}
		
		function GetBanner($zone_name)
		{
			global $dal;
			global $page;
			$zone=$dal->banners_zones->get_cond("name='".trim($zone_name)."'");
			if(isset($zone['id']))
			{
				$banners=$this->get_all("","","","","is_active=1 and zone_id=".$zone['id']." and language_id=".isset_or($page->session['language_id'],$page->settings['default_language_id']['id']));
				
				$total=0;
				$ids_arr=array();
				foreach($banners as $v)
				{
					for($i=1;$i<=$v['percent'];$i++)
						$ids_arr[]=$v['id'];
					$total+=$v['percent'];
				}
				$key=rand(0,$total-1);	
				
				return $this->get($ids_arr[$key]);
			}
			return "";
		}
		
		function GetBannerList($zone_name,$count=0)
		{
			global $dal;
			global $page;
			$zone=$dal->banners_zones->get_cond("name='".$zone_name."'");
			
			if($zone['id'])
			{
				$banners=$this->get_all("","","","","is_active=1 and zone_id=".$zone['id']." and language_id=".$page->session['language_id']);
				
				if(!$count || $count>count($banners))$count=count($banners);
				
				$total=0;
				$ids_arr=array();
				foreach($banners as $v)
				{
					for($i=1;$i<=$v['percent'];$i++)
						$ids_arr[]=$v['id'];
					$total+=$v['percent'];
				}
				
				$result_arr=array();
				
				while(count($result_arr)<$count)
				{
					$key=rand(0,$total-1);
					if(!in_array($ids_arr[$key],$result_arr))
						$result_arr[]=$ids_arr[$key];
				}
				
				$result=array();
				foreach($banners as $v)
					if(in_array($v['id'],$result_arr))
						$result[]=$v;
				
				return $result;
			}
			return "";
		}
	
		function UpdateBanners($zone_id)
		{
			global $dal;
			$zone=$dal->banners_zones->get($zone_id);
			$banners=$this->GetBannerList($zone['name']);
			
			foreach($banners as $k=>$v)
			{
				if(!in_array($v['type_id'],$zone['allowed_types']))
				{
					$this->delete($v['id']);
				}
			}
			
		}
	}
?>