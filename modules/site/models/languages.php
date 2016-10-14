<?php



class languages extends Base
{
	var $admin=0;
	
	function __construct()
	{
		$this->table=$this->tbl_locales;
	}
	
	function getLanguages() {
     	$sql = "SELECT * FROM ".$this->table;
        $result = $this->db->getAll($sql);
        return $result;
     }
	 
	function getactive()
	{
	 	$query="select * from ".$this->table." where is_active=1 order by `order`";
		$result=$this->db->getAll($query);
		return $result;
	}
	 	
	function PercentTranslated($id,$admin=0)
	{
		global $dal;
		$texts=$dal->locales_texts->count_all("admin=".$admin);
		if($texts)
			return ($dal->translations->count_all('admin='.$admin.' and language_id='.$id)*100)/$texts;
		else
			return 0;
	}

	function delete($id,$callbacks=true)
	{
		global $page;
		$obj=$this->get($id);
		$filepath=$page->uploads->folder."flags/".end(explode("/",$obj['image_path']));
		if(is_file($filepath))
	 		unlink($filepath);
	 	parent::delete($id);
	}

	function process_row($row)
	{
		global $page;
		if($page->admin)
			$row['percent_translated']=$this->PercentTranslated($row['id'],$this->admin);
			
		return $row;
	}

	function ExportFile($id)
	{
		global $dal;
		
		$lang=$this->get($id);
		// set headers
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		header('Content-Type: text/xml');
		header("Content-Disposition: attachment; filename=\"".$lang['valoare'].".xml\"");
		header('Content-Transfer-Encoding: binary');
		
		$xml=new XMLWriter();
	    $xml->openMemory();
	    $xml->setIndent(true);
	    $xml->setIndentString(' ');
	    $xml->startDocument('1.0', 'UTF-8');
	    $xml->startElement('language');
	    $xml->writeAttribute("id",$lang['id']);
	    $xml->writeAttribute("code",$lang['code']);
	    $xml->writeAttribute("value",$lang['valoare']);
	    $xml->writeAttribute("export",date('Y-m-d'));
	    $texts=$dal->locales_texts->get_all('','','','','admin='.$this->admin);
		foreach($texts as $v)
		{
			$xml->startElement("text");
			
			$xml->writeAttribute("key",$v['key']);
			$trans=$dal->translations->get($v['id'],$id);
			$xml->writeCData($trans['value']);
			
			$xml->endElement();
		}
		$xml->endElement();
	    $xml->endDocument();
	    $exp=$xml->outputMemory();
	    header('Content-Length: ' . strlen($exp));
	    echo $exp;
	    die;
	}

	function ImportFile($id)
	{
		global $dal;
		$content=file_get_contents($_FILES['file']['tmp_name']);
		$lang=$this->get($id);
		$xml=xml_parser_create();
		
		xml_parse_into_struct($xml, $content, $vals, $index);
		xml_parser_free($xml);
		
		if($lang['code']==$vals[0]['attributes']['CODE'] || $lang['id']==$vals[0]['attributes']['ID'] || $lang['valoare']==$vals[0]['attributes']['VALUE'])
		{
			$c=0;$u=0;$i=0;
			foreach($index['TEXT'] as $k)
			{
				$key=$vals[$k]['attributes']['KEY'];
				$value=$vals[$k]['value'];
				
				$text=$dal->locales_texts->get_cond('`key`="'.$key.'" and admin='.$this->admin);
				if(isset($text['id']) && $text['id'])
				{
					$trans=$dal->translations->get($text['id'],$lang['id']);
					$pars=array();
					$pars['text_id']=$text['id'];
					$pars['value']=$value;
					$pars['admin']=$this->admin;
					$pars['language_id']=$lang['id'];
					if(!isset($trans['text_id']))
					{
						$dal->translations->insert($pars);
						$i++;
					}
					else
					{
						$dal->translations->update($pars,"text_id=".$text['id']." and language_id=".$lang['id']);
						$u++;
					}
					$c++;
				}
			}
			return $c."-".$i."-".$u;
		}
		else
			return 0;
	}
}
?>
