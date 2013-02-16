<?php
/**
 * Router
 * 
 * Determines which controller to load and method to call based on the URL 
 * entered.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Router extends Service
{

	public $requested_url;
	public $action;
	public $custom_routes = array();
	public $_404 = '\Controllers\_404::index';
	
	/**
	 * Parses the URL to determine which controller and method should be used
	 * to display the page, then calls that method.
	 */
	public function route($route = '')
	{
		$route_clean = !empty($route) ? trim($route, '/') : '';
		$route_clean = str_replace('-', '_', $route_clean);
		if (substr($route_clean, 0, 2) == '_/') $route_clean = '/' . $route_clean;
		
		$request_arr = explode('/_/', $route_clean);
		$requested_url = $request_arr[0];
		if (isset($request_arr[1])) {
			$params = $request_arr[1];
			$params = explode('/', $params);	
		}
		else $params = array();
		
		$action = false;
		if ($action === false) $action = $this->custom_routes($requested_url);
		if ($action === false) $action = $this->automatic_routes($requested_url);
		if ($action === false) $action = $this->custom_routes($requested_url, true);		
		
		if (is_callable($action) === false) 
		{
			$action = $this->_404;
		}

		//The 404 controller doesn't exist, fail gracefully.
		if (is_callable($action) === false) 
		{
			return $this->die_404();
		}

		$this->action = $action;
		$this->requested_url = $requested_url;
		
		list($class, $method) = explode('::', $action, 2);

		$controller = \Get::the($class);
		if (is_callable(array($controller, 'pre_route'))) call_user_func(array($controller, 'pre_route'));
		call_user_func_array(array($controller, $method), $params);
		if (is_callable(array($controller, 'post_route'))) call_user_func(array($controller, 'post_route'));
	}
	
	protected function die_404()
	{
		header("HTTP/1.0 404 Not Found");
		echo "404 Not Found";
		die();
	}
	
	protected function automatic_routes($requested_url)
	{		
		if ($requested_url == '') $url_parts = array();
		else $url_parts = explode('/', $requested_url);
		
		$dirs = array(APP_DIR, SHR_DIR);
		
		foreach ($dirs as $dir)
		{		
			$url_parts_copy = $url_parts;
			$namespace = '\\Controllers\\';	
			
			if (count($url_parts_copy) == 0 && is_callable($namespace . 'index::index')) return $namespace . 'index::index';
			
			//\Controllers\MyDir\MyController\Index::index()
			$try_action = 'index';
			$try_controller = $namespace . implode('\\', $url_parts_copy) . '\index';
			if (is_callable($try_controller . '::' . $try_action)) return $try_controller . '::' . $try_action;
			
			//\Controllers\MyDir\MyController::index()
			$try_action = 'index';
			$try_controller = $namespace . implode('\\', $url_parts_copy);
			if (is_callable($try_controller . '::' . $try_action)) return $try_controller . '::' . $try_action;
			
			//\Controllers\MyDir::MyController()
			$try_action = array_pop($url_parts_copy);
			$try_controller = $namespace . implode('\\', $url_parts_copy);
			if (is_callable($try_controller . '::' . $try_action)) return $try_controller . '::' . $try_action;
			
			//If the method doesn't exist, try prefixing it with "m_".  This is useful
			//if you want to have an action named "list" but PHP won't allow you to have
			//a method named "list".
			//\Controllers\MyDir::m_MyController()
			$try_action = 'm_' . $try_action;
			if (is_callable($try_controller . '::' . $try_action)) return $try_controller . '::' . $try_action;
		}
		
		return false;
	}
	
	protected function custom_routes($requested_url, $after_auto = false)
	{
		foreach ($this->custom_routes as $route)
		{
			if ($route['after_auto'] == $after_auto)
			{
				$found_match = preg_match($route['regex'], $requested_url, $matches);
				if ($found_match === 1)
				{
					//Get rid of the matched string
					array_shift($matches);
					$i = 0;
					foreach ($matches as $key => $value)
					{
						if ($i % 2 == 0 && !isset($_GET[$key])) $_GET[$key] = $value;
						$i++;
					}
					return $route['action'];
				}
				else if ($found_match === false) throw new Exception('Manual route preg_match failed: ' . $route['regex']);
			}
		}
		return false;
	}
	
	public function simple_route($route, $action, $after_auto = false) 
	{
		//TODO: Simple Routes
		$this->custom_routes[] = array('regex' => $route, 'action' => $action, 'after_auto' => $after_auto);
	}
	
	public function regex_route($route, $action, $after_auto = false) 
	{
		$this->custom_routes[] = array('regex' => $route, 'action' => $action, 'after_auto' => $after_auto);
	}
	
	public function redirect($location)
	{
		header('Location:' . BASE_URL . $location);
		die();
	}
	
}