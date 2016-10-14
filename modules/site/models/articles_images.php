<?php
class articles_images extends Base
{
	function __construct()
	{
		$this->table=$this->tbl_articles_images;
	}
	
	function save_request($file='image',$article_id,$image_id=0,$description='',$url='',$target='_self')
	{
		global $dal;
		if($image_id)
		{
			$image=$this->get($image_id);	
			if(isset_or($_FILES[$file]['name']))			
				$dal->images->save_request($file,'articles',$image['image_id']);
			$pars=array();
			$pars['description']=$description;
			$pars['url']=$url;
			$pars['target']=$target;
			$pars['update_datetime']=nowfull();
			$this->update($pars,'id='.$image_id);
		}
		else
		{
			if(isset_or($_FILES[$file]['name']))
			{				
				$pars=array();
				$pars['image_id']=$dal->images->save_request($file,"articles");
				$pars['description']=$description;
				$pars['url']=$url;
				$pars['target']=$target;
				$pars['add_datetime']=nowfull();
				$pars['update_datetime']=nowfull();
				$pars['article_id']=$article_id;
				$image_id=$this->insert($pars);
			}
			else {
				$image_id=0;
			}
		}
		return $image_id;
	}
	
	function delete($id,$callbacks=true)
	{
		$row=$this->get($id);
		global $dal;
		$dal->images->delete($row['image_id']);
		parent::delete($id);
	}
}
?>