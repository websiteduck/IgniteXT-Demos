<?php
/**
 * Autoloader
 * 
 * Automatically loads a PHP file that contains the class requested by your
 * application.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

function autoload_source($class)
{
	$valid_types = array('models', 'controllers', 'services', 'entities');

	$class = strtolower($class);
	$parts = explode('\\', $class);
	
	$type = array_shift($parts);
	if (!in_array($type, $valid_types)) return;

	$filename = array_pop($parts);
	
	$check_dirs = array(
		APP_DIR . 'source/', 
		SHR_DIR . 'source/', 
		IXT_DIR . 'source/'
	);
	
	foreach ($check_dirs as $dir)
	{		
		if (count($parts) == 0 || !is_dir($dir . $parts[0])) $dir .= 'base/';
		
		for ($i = 0; $i <= count($parts); $i++)
		{
			$location = $dir;
			if ($i > 0) $location .= implode(array_slice($parts, 0, $i), '/') . '/';
			if ($i > 0 && !is_dir($location)) continue 2; //If this isn't a directory, none of the others will be either
			$location .= $type . '/';
			if ($i < count($parts)) $location .= implode(array_slice($parts, -(count($parts) - $i)), '/') . '/';
			$location .= $filename . '.php';
			if (file_exists($location)) 
			{ 
				require $location; 
				return; 
			}
		}
	}
}

function autoload_ignitext($class)
{
	$class = strtolower($class);
	$parts = explode('\\', $class);
	
	$filename = array_pop($parts);
	
	$location = implode($parts, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename . '.php';
	if (file_exists($location)) require $location;
}

spl_autoload_register('\IgniteXT\autoload_source');
spl_autoload_register('\IgniteXT\autoload_ignitext');
