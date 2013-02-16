<?php
return array(
	// The application identifier will be used by system classes to prevent multiple 
	// applications from interfering with each other when using shared resources such
	// as PHP sessions.
	'APP_ID' => 'ignitext_demos',
	
	// Relative URL, if your index.php file is in http://example.com/ixt/ then
	// BASE_URL will be "/ixt/".  If it's in your root folder, leave this as "/"
	'BASE_URL' => '/',
	
	// The ASSETS URL contains your publicly accessible files such as javascript,
	// css, and image files.
	'ASSETS' => '/assets/',
	
	'class_callbacks' => array(
		'\\IgniteXT\\Profiler' => function($profiler) {
			$profiler->log_everything = false;
		},
		'\\IgniteXT\\Database' => function($database) {
			$database->connect_dsn('sqlite:ixt_demo.sqlite');
		}
	)

);
