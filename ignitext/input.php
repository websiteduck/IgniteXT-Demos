<?php
/**
 * Input
 * 
 * Contains methods for retrieving user input.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Input extends Service
{
	/**
	 * Returns the input value for the specified key.
	 * 
	 * @param string $key
	 * @param mixed $default_value
	 */
	public function request($key, $default = null, $which_array = 'request')
	{
		$array = &$this->input_array($which_array);
		$value = \array_get($array, $key);
		if ($value === null) return $default;
		return $value;
	}
	public function post($key, $default = null) { return $this->request($key, $default, 'post'); }
	public function get($key, $default = null) { return $this->request($key, $default, 'get'); }
	public function cookie($key, $default = null) { return $this->request($key, $default, 'cookie'); }
	
	/**
	 * Returns a pointer to the specified input array.
	 * @param string $which
	 * @return array $input
	 */
	private function &input_array($which)
	{
		switch ($which) {
			case 'request': return $_REQUEST; break;
			case 'post': return $_POST; break;
			case 'get': return $_GET; break;
			case 'cookie': return $_COOKIE; break;
		}
	}

}