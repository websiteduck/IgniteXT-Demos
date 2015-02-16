<?php
/**
 * Get
 * 
 * Responsible for creating instances of objects and maintaining singleton instances of objects.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

abstract class Get 
{
	public static $statics = array();
	public static $soft_redirects = array();
	public static $hard_redirects = array();
	
	public static $config;
	
	public static function initialize($config) 
	{
		static::$config = $config;
		static::$statics['IgniteXT.Config'] = $config;
		static::$config->set('class_callbacks', require(APP_DIR . 'class_callbacks.php'));
	}
	
	public static function a() 
	{ 
		$constructor_args = func_get_args();
		$class_alias = array_shift($constructor_args);
		if (count($constructor_args) == 1 && is_array($constructor_args[0])) $constructor_args = $constructor_args[0];
		
		$actual_class = $class_alias;
		
		$hard_redirect = static::$config->get('class_redirects->hard->' . $class_alias, '->');
		if ($hard_redirect !== null) {
			$actual_class = $hard_redirect;
			$stored_class = $actual_class;
		}
		
		$soft_redirect = static::$config->get('class_redirects->soft->' . $class_alias, '->');
		if ($soft_redirect !== null) {
			$actual_class = $soft_redirect;
			$stored_class = $class_alias;
		}
		
		return static::create_class($class_alias, $actual_class, $constructor_args);
	}
    
	public static function the() 
	{ 
		$constructor_args = func_get_args();
		$class_alias = array_shift($constructor_args);
		if (count($constructor_args) == 1 && is_array($constructor_args[0])) $constructor_args = $constructor_args[0];
                
		//Hard redirects will only have a single instance of the class stored in $statics
		// $hard_redirects['IgniteXT.Database'] => 'My_Database' ------>  $statics['My_Database'] => My_Database
		// $hard_redirects['My_Other_DB']       => 'My_Database' --/
		// 
		//Soft redirects will have a separate instance for each redirect
		// $soft_redirects['IgniteXT.Database'] => 'My_Database' ------>  $statics['IgniteXT.Database'] => My_Database
		// $soft_redirects['My_Other_DB']       => 'My_Database' ------>  $statics['My_Other_DB']       => My_Database
		
		$actual_class = $stored_class = $class_alias;
		
		$hard_redirect = static::$config->get('class_redirects->hard->' . $class_alias, '->');
		if ($hard_redirect !== null) {
			$actual_class = $hard_redirect;
			$stored_class = $actual_class;
		}
		
		$soft_redirect = static::$config->get('class_redirects->soft->' . $class_alias, '->');
		if ($soft_redirect !== null) {
			$actual_class = $soft_redirect;
			$stored_class = $class_alias;
		}
		
		if (!isset(static::$statics[$stored_class])) 
			static::$statics[$stored_class] = static::create_class($class_alias, $actual_class, $constructor_args);
		return static::$statics[$stored_class]; 	
	}
	
	protected static function create_class($class_alias, $actual_class, $constructor_args = array()) 
	{			
		$class_name = static::slash_to_dot($actual_class);
		$class_name_slash = static::dot_to_slash($actual_class);
		if (!empty($constructor_args)) {
			$c = $constructor_args;
			$obj = @ new $class_name_slash($c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6], $c[7], $c[8], $c[9], $c[10], $c[11], $c[12], $c[13], $c[14], $c[15], $c[16], $c[17], $c[18], $c[19], $c[20]);	
		}
		else {
			$obj = new $class_name_slash;
		}
		
		$classes = array_reverse(class_parents($class_name_slash));
		array_walk($classes, function (&$class) { $class = \Get::slash_to_dot($class); });
		
		//Run all of the callbacks for the parent classes of this class and inject dependencies
		foreach ($classes as $class) {
			$callback = static::$config->get('class_callbacks->' . $class, '->');
			if ($callback !== null) $callback($obj, true);

			$auto_injections = static::$config->get('class_injections->' . $class, '->');
			if ($auto_injections !== null) {
				foreach ($auto_injections as $alias => $inject_class) {
					$obj->$alias = \Get::the($inject_class);
				}
			}
		}
		
		//Inject dependencies for this class
		$auto_injections = static::$config->get('class_injections->' . $class_alias, '->');
		if ($auto_injections !== null) {
			foreach ($auto_injections as $alias => $inject_class) {
				$obj->$alias = \Get::the($inject_class);
			}
		}
		
		//Run the callback for this class
		$callback = static::$config->get('class_callbacks->' . $class_alias, '->');
		if ($callback !== null) $callback($obj, false);
			
		if (is_callable(array($obj, 'created'))) 
			call_user_func(array($obj, 'created'));
		
		return $obj;
	}
	
	public static function dot_to_slash($class_name)
	{
		if ($class_name[0] == '\\') return $class_name;
		return '\\' . str_replace('.', '\\', $class_name);
	}
	
	public static function slash_to_dot($class_name) 
	{
		return trim( str_replace('\\', '.', $class_name), '.');
	}
}
