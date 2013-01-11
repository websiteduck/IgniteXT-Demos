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

namespace Services\System;

class Input extends \Services\System\Service
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
		$value = $this->get_value($key, $array);
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
	
	/**
	 * Returns the form input value that was submitted for the specified key.
	 * 
	 * @param string $key
	 * @return string $value
	 */
	public function form_value($key, $default = '', $which_array = 'request')
	{
		$array = &$this->input_array($which_array);
		$value = $this->get_value($key, $array);
		if ($value === null) return $default;
		return htmlentities($value, ENT_QUOTES);
	}
	public function form_value_post($key, $default = null) { return $this->form_value($key, $default, 'post'); }
	public function form_value_get($key, $default = null) { return $this->form_value($key, $default, 'get'); }

	/**
	 * Returns the text need to select a dropdown item if the specified key/value pair is selected.
	 * 
	 * @param string $key
	 * @param string $value
	 * @return string $select_text
	 */
	public function form_select($key, $check_value, $default = false, $which_array = 'request')
	{
		$array = &$this->input_array($which_array);
		$value = $this->get_value($key, $array);
		if ($value === null && $default == true) return 'selected="selected"';
		if ($value == $check_value) return 'selected="selected"';
		else return '';
	}
	public function form_select_post($key, $check_value, $default = null) { return $this->form_select($key, $check_value, $default, 'post'); }
	public function form_select_get($key, $check_value, $default = null) { return $this->form_select($key, $check_value, $default, 'get'); }
	
	/**
	 * Returns the text need to select a checkbox or radio if the specified key/value pair is selected.
	 * 
	 * @param string $key
	 * @param string $value
	 * @return string $check_text
	 */
	public function form_checkbox($key, $default = false, $which_array = 'request')
	{
		$array = &$this->input_array($which_array);
		$value = $this->get_value($key, $array);
		if ($value === null && $default == true) return 'checked="checked"';
		if ($value !== null) return 'checked="checked"';
		else return '';
	}
	public function form_checkbox_post($key, $default = null) { return $this->form_checkbox($key, $default, 'post'); }
	public function form_checkbox_get($key, $default = null) { return $this->form_checkbox($key, $default, 'get'); }
	
	/**
	 * This function returns the value of an array using a string like so:
	 * 'person' -> $array['person']
	 * 'people[5]' -> $array['people'][5]
	 * 
	 * @param string $key
	 * @param array $array
	 * @return string $value
	 */
	private function get_value($key_string, &$array)
	{
		if (empty($key_string)) throw new \Exception('Cannot get empty array key.');
		$keys = preg_split("/[\[\]]+/", $key_string);
		$current_array = &$array;
		
		foreach ($keys as $key) {
			if ($key == '') continue;
			if (!is_array($current_array)) return null;
			if (!isset($current_array[$key])) return null;
			$current_array = &$current_array[$key];
		}
		
		return $current_array;
	}
}