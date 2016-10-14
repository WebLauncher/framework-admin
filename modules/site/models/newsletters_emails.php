<?php



class newsletters_emails extends Base
{
	/**
	 * Constructor
	 * @return
	 * @param object $db
	 */
	function __construct()
	{
		$this->table=$this->tbl_newsletters_emails;
	}
	
	function CheckEmail($email,$type_id)
	{
		global $dal;
		$type=$dal->newsletters_emails_types->get($type_id);
		
		$ok=true;
		$ok=!$this->exists_cond("lower(email)=lower('".$email."') and type_id=".$type_id);
		
		if($type['check_active'])
		{
			$query="select * from `".$type['check_in_table']."` where `".$type['check_in_field']."`='$email'";
			if($this->db->getRow($query))
				$ok=false;
		}
		
		return $ok;
	}
	
	function ValidateEmails($emails,$type_id)
	{
		global $dal;
		$type=$dal->newsletters_emails_types->get($type_id);
		$valid=array();
		
		foreach($emails as $v)
		{		
			$ok=true;
			if(isset($valid[strtolower($v)]))
				$ok=false;
			if($ok)
				$ok=(ValidateMethods::email($v));
			if($ok)
				 $ok=!$this->exists_cond("lower(email)=lower('".$v."') and type_id=".$type_id);
			
			if($ok && $type['check_active'])
			{
				$query="select * from `".$type['check_in_table']."` where `".$type['check_in_field']."`='$v'";
				if($this->db->getRow($query))
					$ok=false;
			}
			if($ok)
				$valid[strtolower($v)]=strtolower($v);
		}
		return $valid;
	} 
}

?>