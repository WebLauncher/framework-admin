<?php
	class PageIndex extends AdminPage
	{
		public $version='1.0.0';
		
		function on_init()
		{
			$this->assign("admin_version","1.8.0.0");
			$this->system->check_login=true;
			$this->system->title = "WebLauncher - Administrare site";
			$this->allowed_actions=array('forgot','login_reset');
			if($this->system->logged)
				$this->session['language_id']=isset_or($this->user['language_id'],20);
		}

		function on_load()
		{

		}

		function action_forgot()
		{
		}

		function action_login_reset()
		{
			if($this->models->administrators->ExistsCond('user="'.$_REQUEST['_username'].'"'))
			{
				$user=$this->models->administrators->GetCond('user="'.$_REQUEST['_username'].'"');
				$this->system->import('library','mail');
				$passw=substr(base64_encode(microtime()),0,8);
				$this->models->administrators->UpdateFieldCond('password',sha1($passw),'user="'.$_REQUEST['_username'].'"');

				$content='Your new administrator password is: '.$passw;
				$obj_mail = new Mail();
		        if ($obj_mail->send_mail($user['email'], 'New administrator password for:'.$this->paths['root'], $content,$this->paths['root'],$this->paths['root'],'no-reply','no-reply')){
		      		$out = true;
		    	} else {
		    	    $out = false;
		    	}
		    	$this->add_message('success','S-a trimis noua parola pe mail!');
				$this->redirect($this->paths['current']);
			}
			else
			{
				$this->add_message('error','Nu exista utilizatorul completat!');
				$this->redirect($this->paths['current']."?a=forgot");
			}
		}
	}

?>