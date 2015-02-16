<?php
/**
 * Class callbacks get ran whenever an instance of the specified class
 * gets created by the \Get class.
 */

$config = static::$config;

return array(
	'IgniteXT.Profiler' => function($profiler) use ($config) {
		$profiler->log_everything = false;
	},
			
	'IgniteXT.Database' => function ($database) use ($config) {
		$database->connect_dsn('sqlite:ixt_demo.sqlite');
	},

	'IgniteXT.Router' => function ($router) use ($config) {
		$router->_404 = (object)array(
			'controller' => $config->get('404_controller'),
			'action'     => $config->get('404_action'),
		);
		$router->routes = $config->get('routes');
	},
);