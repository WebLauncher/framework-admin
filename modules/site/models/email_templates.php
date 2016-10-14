<?php



class email_templates extends Base
{

	/**
	 * Sends templated email with custom params array
	 * @return [true/false] if mail sent
	 * @param object $template_name
	 * @param object $params ex. array("email"=>"emailadd@asdas.com",...)
	 */
	function send($template_name,$params,$attachments=array(),$base_path='')
	{
		global $smarty;
		global $page;

		$content=$this->get_template($template_name,$params,$base_path);
		$template=$this->get_cond(" name='".$template_name."' ");
		foreach($template as $k=>$v)
		{
			if(isset($params[$k]))
				$template[$k]=$params[$k];
		}

		$page->load_library("mail");
		$obj_mail = new Mail();
        if ($obj_mail->send_mail(
        					$params['email'],
        					$template['subject'],
        					$content,
        					$template['from'],
        					$template['from_name'],
        					$template['reply_to'],
        					$template['reply_name'],
        					$attachments
        				)){
      		$out = true;
    	} else {
    	    $out = false;
    	}
		$smarty->clear_assign('params');
		unset($content);
		unset($params);
        return $out;
	}

	function send_template($template_path,$params,$attachments=array(),$base_path='')
	{
		global $smarty;
		global $page;

		$content=$this->get_template_path($template_path,$params,$base_path);

		$page->load_library("mail");
		$obj_mail = new Mail();
        if ($obj_mail->send_mail(
        					$params['email'],
        					$params['subject'],
        					$content,
        					$params['from'],
        					$params['from_name'],
        					$params['reply_to'],
        					$params['reply_name'],
        					$attachments
        				)){
      		$out = true;
    	} else {
    	    $out = false;
    	}
		$smarty->clear_assign('params');
		unset($content);
		unset($params);
        return $out;
	}

	function get_template_path($template_path,$params,$base_path='')
	{
		global $page;

		$s_t_dir=$page->template->template_dir;
		$s_c_dir=$page->template->compile_dir;

		$base_path=$base_path?$base_path:$page->application['basedir'];
		$page->change_template_dir($base_path);
		$page->change_cache_dir($base_path."cache/email/".$page->module);

		$page->template->assign("params",$params);
		$page->template->assign("p",$page->get_page());
		
		if (isset($base_path)){
			$template_path = $base_path.$template_path;
		}
		$content=$page->template->fetch($template_path);

		//reset smarty dirs
		$page->change_template_dir($s_t_dir);
		$page->change_cache_dir($s_c_dir);
		return $content;
	}

	function get_template($template_name,$params,$base_path='')
	{
		global $page;

		$template=$this->get_cond(" name='".$template_name."' ");
		$s_t_dir=$page->template->template_dir;
		$s_c_dir=$page->template->compile_dir;

		$base_path=$base_path!=''?$base_path:$page->paths['root_dir'];
		$page->change_template_dir($base_path);
		$page->change_cache_dir($base_path."cache/email/".$page->module);

		$params['template']=$template;
		$page->template->assign("params",$params);
		$page->template->assign("p",$page->get_page());
		$content=$page->template->fetch($template['template_path']);
		//reset smarty dirs
		$page->change_template_dir($s_t_dir);
		$page->change_cache_dir($s_c_dir);
		return $content;
	}

	function sendMail($params,$attachments=array())
	{
		global $smarty;
		global $page;

		$s_t_dir=$smarty->template_dir;
		$s_c_dir=$smarty->compile_dir;

		$smarty->template_dir=$page->application['basedir'];
		$smarty->compile_dir=$page->application['basedir']."cache/email/";
		if(!is_dir($smarty->compile_dir))mkdir($smarty->compile_dir);
		$smarty->compile_dir.=$page->module;
		if(!is_dir($smarty->compile_dir))mkdir($smarty->compile_dir);


		$smarty->assign("params",$params);
		$smarty->assign("p",$page->GetPage());
		$content=$smarty->fetch($params['template_path']);

		//reset smarty dirs
		$smarty->template_dir=$s_t_dir;
		$smarty->compile_dir=$s_c_dir;

		$obj_mail = new Mail();
        if ($obj_mail->send_mail($params['email'], $params['subject'], $content,$params['from'],$params['from'],$params['reply_to'],$params['reply_name'],$attachments)){
      		$out = true;
    	} else {
    	    $out = false;
    	}
		$smarty->clear_assign('params');
		unset($content);
		unset($params);
        return $out;
	}


	function SaveContent($path,$content)
	{
		if($file=fopen($path,"a"))
		{
			fwrite($file, $content);
		}
		else
		{
			return false;
		}
		return true;
	}

	function update($params,$cond='',$callbacks=true)
	{
		if($this->SaveContent($params['template_path'],$params['template_content']))
			unset($params['template_content']);
		else
			return false;

		parent::update($params,$cond);
		return true;
	}

	function insert($params,$callbacks=true)
	{
		if($this->SaveContent($params['template_path'],$params['template_content']))
			unset($params['template_content']);
		else
			return 0;

		return parent::insert($params);
	}
	function send_multiple($template_name,$params,$attachments=array(),$base_path='')
	{
		global $smarty;
		global $page;

		$content=$this->get_template($template_name,$params,$base_path);
		$template=$this->get_cond(" name='".$template_name."' ");
		foreach($template as $k=>$v)
		{
			if(isset($params[$k]))
				$template[$k]=$params[$k];
		}

		$page->load_library("mail");
		$obj_mail = new Mail();
        if ($obj_mail->send_mail_multiple(
        					$params['emails'],
        					$template['subject'],
        					$content,
        					$template['from'],
        					$template['from_name'],
        					$template['reply_to'],
        					$template['reply_name'],
        					$attachments
        				)){
      		$out = true;
    	} else {
    	    $out = false;
    	}
		$smarty->clear_assign('params');
		unset($content);
		unset($params);
        return $out;
	}
}

?>