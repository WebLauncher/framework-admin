<?php



class seo_links extends Base{
	function process_row($row)
	{
		if(isset($row['id']))
		{
			$row['subpages']=$this->count_all('page REGEXP "^'.$row['page'].'[-_a-zA-Z0-9\.]+/" or page REGEXP "^'.$row['page'].'[\?\=-_a-zA-Z0-9\.]+"');
		}
		return $row;
	}
}

?>