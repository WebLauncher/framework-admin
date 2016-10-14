<?php



class newsletters extends Base
{
	/**
	 * Constructor
	 * @return
	 * @param object $db
	 */
	function __construct()
	{
		$this->table=$this->tbl_newsletters;
	}
}

?>