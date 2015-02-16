<?php
/**
 * IXT Validation Library
 * 
 * Functions to validate data.
 * 
 * alphanumeric - must be an alphanumeric string.
 * contains - must contain the specified string.
 * decimal - must be decimal or integer.
 * email - must be a valid e-mail address.
 * email_list - must contain a comma separated list of valid e-mail addresses.
 * greater_than - must be greater than the specified number.
 * integer - must be an integer.  String must contain only numbers and leading zeros are not permitted.
 * less_than - must be less than the specified number.
 * matches - must match the specified string.
 * max_length - must be at most $length characters.
 * min_length - must be at least $length characters.
 * natural - must be a positive integer. String must contain only numbers and leading zeros are not permitted.
 * numeric - must be numeric.  "+0123.45e6" and "0xFF" are considered numeric.
 * range - must be numeric and greater than or equal to $from and less than or equal to $to.
 * required - cannot be unset, null, or an empty string.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Validation
{
	/**
	 * Input cannot be unset, null, or an empty string.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function required($input)
	{
		if (isset($input) && $input !== '') return true;
		else return false;
	}
	
	/**
	 * Input must be a valid e-mail address.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function email($input)
	{
		//Regular Expression taken from Jonathan Gotti's EasyMail (http://jgotti.net)
		$valid = preg_match('/^(?:(?:(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|\x5c(?=[@,"\[\]' . 
			'\x5c\x00-\x20\x7f-\xff]))(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|(?<=\x5c)' .
			'[@,"\[\]\x5c\x00-\x20\x7f-\xff]|\x5c(?=[@,"\[\]\x5c\x00-\x20\x7f-\xff])' .
			'|\.(?=[^\.])){1,62}(?:[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]|(?<=\x5c)[@' .
			',"\[\]\x5c\x00-\x20\x7f-\xff])|[^@,"\[\]\x5c\x00-\x20\x7f-\xff\.]{1,2})|"' .
			'(?:[^"]|(?<=\x5c)"){1,62}")@(?:(?!.{64})(?:[a-zA-Z0-9][a-zA-Z0-9-]{1,61}' .
			'[a-zA-Z0-9]\.?|[a-zA-Z0-9]\.?)+\.(?:xn--[a-zA-Z0-9]+|[a-zA-Z]{2,6})|\[(?' .
			':[0-1]?\d?\d|2[0-4]\d|25[0-5])(?:\.(?:[0-1]?\d?\d|2[0-4]\d|25[0-5])){3}\])$/', $input);
		if ($valid) return true;
		else return false;
	}
	
	/**
	 * Input must contain a comma separated list of valid e-mail addresses.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function email_list($input)
	{
		$emails = explode(',', $input);
		
		$valid = true;
		foreach ($emails as $email)	
			if (static::email($email) !== true) { $valid = false; break; }
			
		if ($valid) return true;
		else return false;
	}
	
	/**
	 * Input must be an integer.  String must contain only numbers and leading zeros are not permitted.
	 * Integer input is also allowed.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function integer($input)
	{
		if ($input === (string)(int)$input || $input === (int)$input) return true;
		else return false;
	}
	
	public static function natural($input) 
	{
		if (($input === (string)(int)$input || $input === (int)$input) && (int)$input >= 0) return true;
		else return false;
	}
	
	/**
	 * Input must be numeric.  "+0123.45e6" and "0xFF" are considered numeric.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function numeric($input)
	{
		if (is_numeric($input)) return true;
		else return false;
	}
	
	/**
	 * Input must be decimal or integer. 
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function decimal($input)
	{
		if (preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $input)) return true;
		else return false;
	}
	
	/**
	 * Input must be an alphanumeric string.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function alphanumeric($input)
	{
		if (preg_match("/^[a-zA-Z0-9]*$/", $input)) return true;
		else return false;
	}
	
	/**
	 * Input must be a string containing only alphanumeric characters, dashes, or underscores.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function alphadash($input)
	{
		if (preg_match("/^[0-9a-zA-Z\_\-]*$/", $input)) return true;
		else return false;
	}
	
	/**
	 * Input must contain only letters.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function alpha($input)
	{
		if (preg_match("/^[a-zA-Z]*$/", $input)) return true;
		else return false;
	}
	
	/**
	 * Input must be greater than the specified number.
	 * 
	 * @param string $input
	 * @param integer $greater_than
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function greater_than($input, $greater_than)
	{
		$numeric = static::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input > $greater_than) return true;
		else return false;
	}
	
	/**
	 * Input must be less than the specified number.
	 * 
	 * @param string $input
	 * @param integer $less_than
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function less_than($input, $less_than)
	{
		$numeric = static::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input < $less_than) return true;
		else return false;
	}
	
	/**
	 * Input must be numeric and greater than or equal to $from and less than or equal to $to.
	 * 
	 * @param string $input
	 * @param integer $from
	 * @param integer $to
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function range($input, $from, $to)
	{
		$numeric = static::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input >= $from && $input <= $to) return true;
		else return false;
	}
	
	/**
	 * Input must be at least $length characters.
	 * 
	 * @param string $input
	 * @param integer $length
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function min_length($input, $length)
	{
		if (strlen($input) >= $length) return true;
		else return false;
	}
	
	/**
	 * Input must be at most $length characters.
	 * 
	 * @param string $input
	 * @param integer $length
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function max_length($input, $length)
	{
		if (strlen($input) <= $length) return true;
		else return false;
	}
	
	/*
	 * Input must contain the specified string.
	 * 
	 * @param string $input
	 * @param string $contains_this
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function contains($input, $contains_this)
	{
		if (strpos($input, $contains_this) !== false) return true;
		else return false;
	}
	
	/*
	 * Input must match the specified string.
	 * 
	 * @param string $input
	 * @param string $matches_this
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public static function matches($input, $matches_this)
	{
		if ($input === $matches_this) return true;
		else return false;
	}
	
}
