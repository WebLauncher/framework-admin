<?php
	class configurations_page extends Page
	{
		
		function on_init()
		{
			$this->system->import('dal','settings');
			$this->system->import('dal','languages');
			$this->system->import('dal','xjudete');
			$this->system->import('dal','xorase');
			$this->system->import('dal','email_templates');
			$this->system->import('dal','seo_metas');
			$this->system->import('dal','seo_links');
			$this->system->import('dal','images');
		}
		
		function on_load()
		{
			
		}
	}
?>
