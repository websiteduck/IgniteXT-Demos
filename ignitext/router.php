<?php
/**
 * Router
 * 
 * Determines which controller to load and method to call based on the URL 
 * entered.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Router extends Service
{
	public $routes;
	public $_404;
	
	public $route;
	public $requested_url;
	
	/**
	 * Parses the URL to determine which controller and method should be used
	 * to display the page, then calls that method.
	 */
	public function route($requested_url = '')
	{
		if (empty($requested_url)) $requested_url = '';
		$requested_url = trim($requested_url, '/');
		$this->requested_url = $requested_url;

		$route = false;
		foreach ($this->routes as $regex_route) {
			$matches = array();
			if ($regex_route[0] === 'restful') {
				if (!isset($regex_route[3])) $regex_route[3] = true; //Enable Params
				list($type, $url, $controller, $enable_params) = $regex_route;
				$method = 'any';
			}
			elseif ($regex_route[0] === 'direct') {
				if (!isset($regex_route[4])) $regex_route[4] = true; //Enable Params
				if (!isset($regex_route[5])) $regex_route[5] = 'any'; //Request Method
				list($type, $url, $controller, $action, $enable_params, $method) = $regex_route;
			}
			else throw new \Exception('Invalid Route Type: ' . $regex_route[0]);

			$url = str_replace('@', '\\@', $url);
			$url = rtrim($url, '/');

			if ($type === 'restful') {
				$regex = '@^' . $url . '(?:/(?P<ixt_action>[^/]+))?';
			}
			elseif ($type === 'direct') {
				$regex = '@^' . $url;
			}

			if ($enable_params) $regex .= '(?P<ixt_params>.+)?';
			$regex .= '$@';
			
			$matched = preg_match($regex, $this->requested_url, $matches);
			
			if ($method !== 'any') if (strtolower($_SERVER['REQUEST_METHOD']) !== $method) $matched = 0;

			if ($matched === 1) {
				if ($type === 'restful') {
					if (isset($matches['ixt_action'])) $action = $matches['ixt_action'];
					else $action = 'index';
					
					$action = str_replace(array('-', ' '), '_', $action);
					$action = strtolower($_SERVER['REQUEST_METHOD']) . '_' . $action;
				}
				
				$params = array();
				if (isset($matches['ixt_params'])) $params = explode('/', trim($matches['ixt_params'], '/'));
				foreach ($matches as $key => $value) if (!is_int($key)) $_GET[$key] = $value;

				$route = new \stdClass;
				$route->controller = $controller;
				$route->action = $action;
				$route->params = $params;
				break;
			}
			
		}
		
		//If the route was not found or the controller and action don't exist, use the 404 controller
		if ($route === false || is_callable(\Get::dot_to_slash('Controllers.' . $route->controller . '::' . $route->action)) === false) 
		{
			$route = $this->_404;
		}

		//If the 404 controller doesn't exist, fail gracefully.
		if (is_callable(\Get::dot_to_slash('Controllers.' . $route->controller . '::' . $route->action)) === false) 
		{
			return $this->die_404();
		}

		if (!isset($route->params)) $route->params = array();
		$this->route = $route;

		$controller = \Get::the('Controllers.' . $route->controller);
		if (is_callable(array($controller, 'pre_route'))) call_user_func(array($controller, 'pre_route'));
		call_user_func_array(array($controller, $route->action), $route->params);
		if (is_callable(array($controller, 'post_route'))) call_user_func(array($controller, 'post_route'));
	}
	
	protected function die_404()
	{
		header("HTTP/1.0 404 Not Found");
		echo "404 Not Found";
		die();
	}
	
	public function redirect($location, $include_base_url = true)
	{
		session_write_close();
		if ($include_base_url) header('Location:' . BASE_URL . $location);
		else header('Location:' . $location);
		die();
	}
	
	public function redirect_301($location, $include_base_url = true)
	{
		session_write_close();
		header('HTTP/1.1 301 Moved Permanently'); 
		$this->redirect($location, $include_base_url);
	}
}
