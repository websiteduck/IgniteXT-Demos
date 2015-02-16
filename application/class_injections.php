<?php

// Automatically inject dependencies into these classes.

return array(
	'IgniteXT.Service_Entity' => array(
		'config' => 'IgniteXT.Config',
	),

	'IgniteXT.Controller' => array(
		'db'      => 'IgniteXT.Database',
		'display' => 'IgniteXT.Display',
		'input'   => 'IgniteXT.Input',
		'router'  => 'IgniteXT.Router',
		'session' => 'IgniteXT.Session',
	),
	
	'IgniteXT.Model' => array(
		'db'      => 'IgniteXT.Database',
		'display' => 'IgniteXT.Display',
		'input'   => 'IgniteXT.Input',
		'router'  => 'IgniteXT.Router',
		'session' => 'IgniteXT.Session',
	),
	
	'IgniteXT.Display' => array(
		'input'   => 'IgniteXT.Input',
		'session' => 'IgniteXT.Session',
	),
);