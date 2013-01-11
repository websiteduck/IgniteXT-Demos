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

namespace Services\System;

class Session extends \Services\System\Service
{
	public function __construct()
	{
		if (!isset($_SESSION[APPID])) $_SESSION[APPID] = array();
	}
	
	public function start()
	{
		session_start();
		if (isset($_SESSION[APPID]['next_flash'])) {
			$_SESSION[APPID]['flash'] = $_SESSION[APPID]['next_flash'];
			unset($_SESSION[APPID]['next_flash']);
		}
		register_shutdown_function(array($this, 'finish'));
	}
	
	public function finish()
	{
		if (isset($_SESSION[APPID]['flash'])) unset($_SESSION[APPID]['flash']);
	}
	
	public function set_flash($name, $value) {
		if (!isset($_SESSION[APPID]['flash'])) $_SESSION[APPID]['flash'] = array();
		if (!isset($_SESSION[APPID]['next_flash'])) $_SESSION[APPID]['next_flash'] = array();
		$ref_flash =& $this->get_value($name, $_SESSION[APPID]['flash'], true);
		$ref_next =& $this->get_value($name, $_SESSION[APPID]['next_flash'], true);
		$ref_flash = $value;
		$ref_next = $value;
	}
	
	public function get_flash($name) {
		return $this->get_value($name, $_SESSION[APPID]['flash']);
	}
	
	public function set($name, $value)
	{
		if (!isset($_SESSION[APPID])) $_SESSION[APPID] = array();
		$ref =& $this->get_value($name, $_SESSION[APPID], true);
		$ref = $value;
	}
	
	public function get($name)
	{
		return $this->get_value($name, $_SESSION[APPID]);
	}
	
	public function &reference($name = null)
	{
		if ($name === null) {
			if (!isset($_SESSION[APPID])) $_SESSION[APPID] = array();
			return $_SESSION[APPID];
		}
		else {
			if (!isset($_SESSION[APPID][$name])) $_SESSION[APPID][$name] = null;
			return $_SESSION[APPID][$name];
		}
	}
	
	public function clear() {
		$_SESSION[APPID] = null;
	}
	
	/**
	 * This function returns a reference to an array key using a string like so:
	 * 'person' -> $array['person']
	 * 'people[5]' -> $array['people'][5]
	 * 
	 * @param string $key
	 * @param array $array
	 * @return string $value
	 */
	private function &get_value($key_string, &$array, $create = false)
	{	
		if (empty($key_string)) throw new \Exception('Cannot get or set empty session key.');
		$keys = preg_split("/[\[\]]+/", $key_string);
		$current_array = &$array;
	
		foreach ($keys as $key) {
			if ($key == '') continue;
			if (!is_array($current_array)) { $temp = null; return $temp; }
			if (!isset($current_array[$key]) && $create === true) $current_array[$key] = array();
			if (!isset($current_array[$key])) { $temp = null; return $temp; }
			$current_array = &$current_array[$key];
		}
	
		return $current_array;
	}
	
}