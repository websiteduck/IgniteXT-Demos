<?php
/**
 * Autoloader
 * 
 * Automatically loads a PHP file that contains the class requested by your
 * application.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Autoloader {
	
	public static function source($class)
	{
		$valid_types = array('models', 'controllers', 'services', 'entities');

		$class = strtolower($class);
		$parts = explode('\\', $class);
	
		$type = array_shift($parts);
		if (!in_array($type, $valid_types)) return;

		$filename = array_pop($parts);
		
		$check_dirs = array(
			APP_DIR . 'source/', 
			SHR_DIR . 'source/'
		);
	
		foreach ($check_dirs as $dir)
		{		
			if (count($parts) == 0 || !is_dir($dir . $parts[0])) $base = 'base';
			else $base = array_shift($parts);

			$location = $dir . $base . '/' . $type . '/' . implode($parts, '/') . '/' . $filename . '.' . $type[0] . '.php';
			if (file_exists($location)) {
				require $location;
				return;
			}
		}
	}
	
	public static function ignitext($class)
	{
		$class = strtolower($class);
		$parts = explode('\\', $class);
	
		$filename = array_pop($parts);
	
		$location = BASE_DIR . implode($parts, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename . '.php';
		if (file_exists($location)) require $location;
	}
}

spl_autoload_register('\IgniteXT\Autoloader::source');
spl_autoload_register('\IgniteXT\Autoloader::ignitext');


