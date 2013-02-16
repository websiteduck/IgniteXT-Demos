<?php
/**
 * Get
 * 
 * Responsible for creating instances of objects and maintaining singleton instances of objects.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Get 
{
    public static $statics = array();
	public static $soft_redirects = array();
	public static $hard_redirects = array();
	
	private static $config;
	
	public static function initialize(&$config) {
		static::$config = &$config;
		if (isset($config['hard_redirects'])) static::$hard_redirects = $config['hard_redirects'];
		if (isset($config['soft_redirects'])) static::$soft_redirects = $config['soft_redirects'];
	}
	
	public static function a($class, $constructor_params = array()) 
	{ 
		$class = strtolower($class);
		//Soft redirects and hard redirects behave the same when you're just returning a new instance
		if (isset(static::$hard_redirects[$class])) $class = static::$hard_redirects[$class];
		if (isset(static::$soft_redirects[$class])) $class = static::$soft_redirects[$class];
		return static::create_class($class, $constructor_params);
	}
    
	public static function the($class, $constructor_params = array()) 
	{ 
		//Hard redirects will only have a single instance for the original class stored in $statics
		//Soft redirects will have a separate instance
		if (isset(static::$hard_redirects[$class])) $class = static::$hard_redirects[$class];
		$new_class = $class;
		if (isset(static::$soft_redirects[$class])) $new_class = static::$soft_redirects[$class];
		if (!isset(static::$statics[$new_class])) 
			static::$statics[$class] = static::create_class($new_class, $constructor_params);
		return static::$statics[$class]; 	
	}
	
	protected static function create_class($class_name, $constructor_params = array()) 
	{			
		if (!empty($constructor_params)) {
			$c = $constructor_params;
			$obj = @ new $class_name($c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6], $c[7], $c[8], $c[9], $c[10], $c[11], $c[12], $c[13], $c[14], $c[15], $c[16], $c[17], $c[18], $c[19], $c[20]);	
		}
		else {
			$obj = new $class_name();
		}
		
		$classes = array_reverse(class_parents($class_name));
		array_walk($classes, function (&$class) { $class = '\\' . $class; });
		
		$parent_callbacks = array();
		foreach ($classes as $class) {
			if (isset(static::$config['class_callbacks'][$class])) {
				$parent_callbacks[] = static::$config['class_callbacks'][$class];
			}
		}
		foreach ($parent_callbacks as $parent_callback) $parent_callback($obj, true);
		
		if (isset(static::$config['class_callbacks'][$class_name])) {
			$callback = static::$config['class_callbacks'][$class_name];
			$callback($obj, false);
		}
			
		if (is_callable(array($obj, 'created'))) 
			call_user_func(array($obj, 'created'));
		
		return $obj;
	}
}