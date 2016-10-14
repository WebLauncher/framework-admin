<?php



class newsletters_emails_types extends Base
{
	/**
	 * Constructor
	 * @return
	 * @param object $db
	 */
	function __construct()
	{
		$this->table=$this->tbl_newsletters_emails_types;
	}
	
	function process_row($row)
	{
		global $dal;
		if(isset($row['id']))
		{
			$row['emails']=$dal->newsletters_emails->count_all("type_id=".$row['id']);
			$row['emails_active']=$dal->newsletters_emails->count_all("type_id=".$row['id']." and active=1");
		}
		return $row;
	}
}

?>