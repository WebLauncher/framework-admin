<?php
    class banners_zones extends Base
	{
		function __construct()
		{
			$this->table=$this->tbl_banners_zones;
		}
		
		function save($id,$name,$width,$height,$allowed_types)
		{
			global $dal;
			$allowed_types=serialize($allowed_types);
			
			if($id)
			{
				$obj_zone=$this->get($id);
				$params=array();
				if($image_zone=$dal->image_types->get_by_name("banners_".$obj_zone['name']))
					if($image_zone['id'])
					{
						$dal->image_types->save($image_zone['id'],"banners_".$obj_zone['name'],$width,$height,"banners_".$obj_zone['name']);
					}
				
				$query="update `".$this->table."` set
						`width`='$width',
						`height`='$height',
						`allowed_types`='$allowed_types'
						
						where `id`=$id
						";
			}
			else
			{
				$query="insert into `".$this->table."`
						(`name`,`width`,`height`,`allowed_types`)
						values
						('$name','$width','$height','$allowed_types')
						";
			}
			$this->db->query($query);
			if($id)
				$dal->banners->UpdateBanners($id);
			return ($id?$id:$this->db->last_id());
		}
				
		function delete($id)
		{
			global $dal;
			$dal->banners->delete_zone($id);
			
			parent::delete($id);
		}
		
		function process_row($row)
		{
			global $dal;
			
			if(isset($row['id']))
			{
				$row['banners']=$dal->banners->count_all("zone_id=".$row['id']);
				$row['allowed_types']=unser($row['allowed_types']);
				$row['dimension']=$row['width']."px / ".$row['height']."px";
			}
			
			return $row;
		}
		
	}
?>