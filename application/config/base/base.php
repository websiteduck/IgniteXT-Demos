<?php
return array(
	
	// The application identifier will be used by system classes to prevent multiple 
	// applications from interfering with each other when using shared resources such
	// as PHP sessions.
	'APP_ID' => 'ixt_demos',
	
	// Relative URL, if your index.php file is in http://example.com/ixt/ then
	// BASE_URL will be "/ixt/".  If it's in your root folder, leave this as "/"
	'BASE_URL' => '/',
	
	// The ASSETS URL contains your publicly accessible files such as javascript,
	// css, and image files.
	'ASSETS' => '/assets/',

	/*
	'db_driver'   => 'mysql',
	'db_host'     => 'localhost',
	'db_port'     => '3306',
	'db_username' => 'root',
	'db_password' => '',
	'db_database' => '',
	'db_charset'  => 'utf8',
	*/

	'404_controller' => '_404',
	'404_action' => 'index',

	'class_redirects' => require(APP_DIR . 'class_redirects.php'),
	'class_injections' => require(APP_DIR . 'class_injections.php'),
	'routes' => require(APP_DIR . 'routes.php'),
);
