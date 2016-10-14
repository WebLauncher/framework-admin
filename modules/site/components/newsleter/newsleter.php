<?php
	class newsleter_page extends Page
	{
		function on_init()
		{
			$this->version="1.1.0";
			$this->system->import('dal','newsletters');
			$this->system->import('dal','newsletters_emails');
			$this->system->import('dal','newsletters_jobs');
			$this->system->import('dal','newsletters_emails_types');
		}
		
		function on_load()
		{
			
		}
	}
?>
