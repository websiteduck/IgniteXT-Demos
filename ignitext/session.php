<?php
/**
 * Session
 * 
 * Manages the application's session.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Session extends Service
{
	public function __construct()
	{
		if (!isset($_SESSION[APP_ID])) $_SESSION[APP_ID] = array();
	}
	
	public function start()
	{
		session_start();
		if (isset($_SESSION[APP_ID]['next_flash'])) {
			$_SESSION[APP_ID]['flash'] = $_SESSION[APP_ID]['next_flash'];
			unset($_SESSION[APP_ID]['next_flash']);
		}
		register_shutdown_function(array($this, 'finish'));
	}
	
	public function finish()
	{
		if (isset($_SESSION[APP_ID]['flash'])) unset($_SESSION[APP_ID]['flash']);
	}
	
	public function set_flash($name, $value) 
	{
		if (!isset($_SESSION[APP_ID]['flash'])) $_SESSION[APP_ID]['flash'] = array();
		if (!isset($_SESSION[APP_ID]['next_flash'])) $_SESSION[APP_ID]['next_flash'] = array();
		\array_set($_SESSION[APP_ID]['flash'], $name, $value);
		\array_set($_SESSION[APP_ID]['next_flash'], $name, $value);
	}
	
	public function get_flash($name) 
	{
		return \array_get($_SESSION[APP_ID]['flash'], $name);
	}
	
	public function set($name, $value)
	{
		if (!isset($_SESSION[APP_ID])) $_SESSION[APP_ID] = array();
		\array_set($_SESSION[APP_ID], $name, $value);
	}
	
	public function get($name)
	{
		return \array_get($_SESSION[APP_ID], $name);
	}
	
	public function &reference($name = null)
	{
		if (!isset($_SESSION[APP_ID])) $_SESSION[APP_ID] = array();
		return \array_get($_SESSION[APP_ID], $name);
	}
	
	public function clear() 
	{
		$_SESSION[APP_ID] = null;
	}
	
}