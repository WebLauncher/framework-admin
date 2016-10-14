<?php
// site crypting key
$this->crypt_key = 'newsite_admin';

// user types tables associations
$this->user_types_tables = array (
		'admin' => array (
				'table' => 'administrators',
				'username' => array (
						'user',
						'email' 
				),
				'password' => 'password',
				'crypting' => 'sha1',
				'lastlogin' => 'last_login',
				'active' => 'is_active',
				'valid' => '',
				'deleted' => '' 
		) 
);