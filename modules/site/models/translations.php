<?php



class translations extends Base
{
	function __construct()
	{
		$this->table=$this->tbl_locales_translations;
	}
	
	function getForText($text_id)
	{
		$query="select ".$this->tbl_locales.".*
		,".$this->table.".* from ".$this->table." left join ".$this->tbl_locales." on
		".$this->tbl_locales.".id=".$this->table.".language_id
		 where ".$this->table.".text_id=$text_id and ".$this->tbl_locales.".is_active=1";
		return $this->db->getAll($query);
	}
	
	function get($text_id,$language_id='')
	{
		$query="select ".$this->table.".*,".$this->tbl_locales.".* from ".$this->table."
				inner join ".$this->tbl_locales." on	".$this->tbl_locales.".id=".$this->table.".language_id
				where text_id=$text_id and language_id=$language_id";
		
		return $this->db->getRow($query);
	}

	function set_default($language_id,$admin=0)
	{
		$query='insert into 
				locales_translations
				(text_id,language_id,`value`,admin)
				(select 
				locales_texts.id,'.$language_id.',locales_texts.`value`,locales_texts.admin 
				from locales_texts 
			where admin='.$admin.' and locales_texts.id not in (select locales_translations.text_id from locales_translations where locales_translations.language_id='.$language_id.' and locales_translations.admin='.$admin.'))';
		$this->db->query($query);
	}
}

?>