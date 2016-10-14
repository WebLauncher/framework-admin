<?php

class locales_texts extends Base{
	
	function __construct()
	{
		$this->table=$this->tbl_locales_texts;
	}

	function GetForTranslation($to_language,$from_language=0,$admin=0)
	{
		if($from_language)
		{
			
		}
		else
		{
			$query="select `".$this->table."`.* from `".$this->table."` where admin=$admin and id not in (select text_id from `".$this->locales_translations."` where language_id=$to_language)";
		}
		return $this->db->getRow($query);
	}

	function process_row($row)
	{
		global $dal;
		$row['languages']=$dal->translations->getForText($row['id']);
		
		return $row;
	}
}

?>