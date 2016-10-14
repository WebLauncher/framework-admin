<?php



class image_types extends Base
{
	function __construct()
	{
		$this->table=$this->tbl_image_types;
	}
	
	function get_by_name($name)
	{
		$query="select * from `".$this->table."` where name='".$name."'";
		return $this->db->getRow($query);
	}
    
    /**
     * get the id of a type using the name of the id
     * @return $id - the id that i need to find witch images to show
     * @param object $name
     */
    function getTypeId($name) {
    	$sql = "SELECT id FROM ".$this->tbl_images_type." WHERE name=".$name;
        $id  = $this->db->getOne($sql);
        return $id;
    }//end function getTypeId($name)
	
	function save($id,$name,$width,$height,$folder)
	{
		if($id)
		{
			$params=array();
			$params['name']=$name;
			$params['width']=$width;
			$params['height']=$height;
			$params['folder']=$folder;
			 
			$this->update($params,"id=$id");
						
			$this->ReProcess($id,$width,$height);
		}
		else
		{
			$query="insert into `".$this->table."`
					(name,width,height,folder)
					values
					('$name','$width','$height','$folder')
					";
			$this->db->query($query);
		}
		
		return ($id?$id:$this->db->last_id());
	}
    
   	function ReProcess($id,$width,$height)
	{
		global $dal;
		global $page;
		$page->load_library("images");
		$images=$dal->images->get_all("","","","","type_id=".$id);
		
		foreach($images as $k=>$v)
		{
			$dal->images->ReProcess($v['id'],$width,$height);
		}
		
	}
}

?>