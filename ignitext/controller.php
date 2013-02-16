<?php
/**
 * Controller
 * 
 * The base class for a controller.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Controller extends Service
{
	public function __construct() 
	{
		$this->db = \Get::the('\IgniteXT\Database');
		$this->display = \Get::the('\IgniteXT\Display');
		$this->router = \Get::the('\IgniteXT\Router');
		$this->session_class = \Get::the('\IgniteXT\Session');
		$this->session = &$this->session_class->reference();
		$this->input = \Get::the('\IgniteXT\Input');
	}
	
	public function post_handler($action) { return $this->handler("POST", $action); }
	public function get_handler($action) { return $this->handler("GET", $action); }
	public function put_handler($action) { return $this->handler("PUT", $action); }
	public function delete_handler($action) { return $this->handler("DELETE", $action); }
	
	/**
	 * Run an action for a certain request method.
	 * 
	 * @param string $request_method
	 * @param string $action
	 * @return boolean $action_was_ran
	 */
	private function handler($request_method, $action) 
	{
		$request_method = strtolower($request_method);
		if ($request_method == strtolower($_SERVER['REQUEST_METHOD'])) {
			$this->$action();
			return true;
		}
		return false;
	}
}