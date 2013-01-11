<?php
return array(
	// The application identifier will be used by system classes to prevent multiple 
	// applications from interfering with each other when using shared resources such
	// as PHP sessions.
	'APPID' => 'ignitext_demos',
	
	// Relative URL, if your index.php file is in http://example.com/ixt/ then
	// BASEURL will be "/ixt/".  If it's in your root folder, leave this as "/"
	'BASEURL' => '/',
	
	// The ASSETS URL contains your publicly accessible files such as javascript,
	// css, and image files.
	'ASSETS' => '/assets/',
		
	// Locations of directories used by IgniteXT
	'APPDIR' => 'application/',
	'SHRDIR' => 'shared/',
	'IXTDIR' => 'ignitext/',
	
	'class_callbacks' => array(
		'\\Services\\System\\Profiler' => function($profiler) {
			$profiler->log_everything = false;
		},
		'\\Services\\System\\Database' => function($database) {
			$database->connect_dsn('sqlite:ixt_demo.sqlite');
		}
	)

);
