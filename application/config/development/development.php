<?php
return array(
	'inherits' => 'base',
	
	'ini_set' => array(
		'error_reporting' => E_ALL & ~E_NOTICE,
		'display_errors' => '1'
	),

	'enable_stack_trace' => true,
);