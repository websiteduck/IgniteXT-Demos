<?php
/**
 * Controller
 * 
 * The base class for a controller.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

abstract class Controller extends Service
{
	public function created() 
	{		
		if (!isset($this->sess) && isset($this->session)) $this->sess = &$this->session->reference();
		if (!isset($this->conf) && isset($this->config)) $this->conf = &$this->config->reference();
		if (!isset($this->data) && isset($this->display)) $this->data = &$this->display->data;
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