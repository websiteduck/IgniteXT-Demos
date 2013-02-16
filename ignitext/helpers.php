<?php

function u(&$v, $default = null) 
{
	return isset($v) ? $v : $default; 
}

/**
 * This function returns a reference to an array key using a string like so
 * 
 *   $var =& array_get($arr, 'one.two.three');
 *   $var = 'foo'; //Modifies original array
 *   OR not by reference
 *   $var = array_get($arr, 'one.two.three');
 *   
 * @param array $array
 * @param array $array
 * @return string $value
 */
function &array_get(&$array, $key_string, $separator = '.')
{	
	if (empty($key_string)) return $array;
	$keys = explode($separator, $key_string);
	$current_array = &$array;

	foreach ($keys as $key) {
		if (!is_array($current_array)) { $temp = null; return $temp; }
		if (!isset($current_array[$key])) { $temp = null; return $temp; }
		$current_array = &$current_array[$key];
	}

	return $current_array;
}

/**
 * This function sets a value in an array.
 * 
 * @param string $key
 * @param array $array
 * @return string $value
 */
function array_set(&$array, $key_string, $value, $separator = '.')
{	
	$keys = explode($separator, $key_string);
	$current_array = &$array;

	foreach ($keys as $key) {
		if ($key == '') continue;
		if (!is_array($current_array)) { return false; }
		if (!isset($current_array[$key])) $current_array[$key] = array();
		$current_array = &$current_array[$key];
	}

	$current_array = $value;
	return true;
}

