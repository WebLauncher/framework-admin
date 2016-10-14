<?php



class xpaginations extends Base{
	function __construct(){
		$this->table=$this->tbl_x_conf_pagination;
	}

	function _($page)
	{
		return $this->get_field_cond("offset","page='".$page."'");
	}
}

?>