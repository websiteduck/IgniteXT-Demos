<?php
/**
 * IXT Validation Library
 * 
 * Functions to validate data.
 * 
 * alphanumeric - must be an alphanumeric string.
 * decimal - must be decimal or integer.
 * email - must be a valid e-mail address.
 * email_list - must contain a comma separated list of valid e-mail addresses.
 * greater_than - must be greater than the specified number.
 * integer - must be an integer.  String must contain only numbers and leading zeros are not permitted.
 * less_than - must be less than the specified number.
 * max_length - must be at most $length characters.
 * min_length - must be at least $length characters.
 * numeric - must be numeric.  "+0123.45e6" and "0xFF" are considered numeric.
 * range - must be numeric and greater than or equal to $from and less than or equal to $to.
 * required - cannot be unset, null, or an empty string.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Services\IXT;

class Validation extends \Services\System\Service
{
	/**
	 * Input cannot be unset, null, or an empty string.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function required($input, $return_error_message = false)
	{
		if (isset($input) && $input !== '') return true;
		else if ($return_error_message == true) return 'is required.';
		else return false;
	}
	
	/**
	 * Input must be a valid e-mail address.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function email($input, $return_error_message = false)
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
		else if ($return_error_message == true) return 'must be a valid e-mail address.';
		else return false;
	}
	
	/**
	 * Input must contain a comma separated list of valid e-mail addresses.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function email_list($input, $return_error_message = false)
	{
		$emails = explode(',', $input);
		
		$valid = true;
		foreach ($emails as $email)	
			if (self::email($email) !== true) { $valid = false; break; }
			
		if ($valid) return true;
		else if ($return_error_message == true) return 'must contain a comma separated list of valid e-mail addresses.';
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
	public function integer($input, $return_error_message = false)
	{
		if ($input === (string)(int)$input || $input === (int)$input) return true;
		else if ($return_error_message == true) return 'must be an integer.';
		else return false;
	}
	
	/**
	 * Input must be numeric.  "+0123.45e6" and "0xFF" are considered numeric.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function numeric($input, $return_error_message = false)
	{
		if (is_numeric($input)) return true;
		else if ($return_error_message == true) return 'must be a number.';
		else return false;
	}
	
	/**
	 * Input must be decimal or integer. 
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function decimal($input, $return_error_message = false)
	{
		if (preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $input)) return true;
		else if ($return_error_message == true) return 'must be a decimal number.';
		else return false;
	}
	
	/**
	 * Input must be an alphanumeric string.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function alphanumeric($input, $return_error_message = false)
	{
		if (preg_match("/^[a-zA-Z0-9]*$/", $input)) return true;
		else if ($return_error_message == true) return 'must be an alphanumeric string.';
		else return false;
	}
	
	/**
	 * Input must be a string containing only alphanumeric characters, dashes, or underscores.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function alphadash($input, $return_error_message = false)
	{
		if (preg_match("/^[0-9a-zA-Z\_\-]*$/", $input)) return true;
		else if ($return_error_message == true) return 'must contain only alphanumeric characters, underscores, or dashes.';
		else return false;
	}
	
	/**
	 * Input must contain only letters.
	 * 
	 * @param string $input
	 * @param boolean $return_error_message
	 * @return mixed $valid
	 */
	public function alpha($input, $return_error_message = false)
	{
		if (preg_match("/^[a-zA-Z]*$/", $input)) return true;
		else if ($return_error_message == true) return 'must contain only letters.';
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
	public function greater_than($input, $greater_than, $return_error_message = false)
	{
		$numeric = self::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input > $greater_than) return true;
		else if ($return_error_message == true)	return 'must be greater than ' . $greater_than . '.';
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
	public function less_than($input, $less_than, $return_error_message = false)
	{
		$numeric = self::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input < $less_than) return true;
		else if ($return_error_message == true)	return 'must be less than ' . $less_than . '.';
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
	public function range($input, $from, $to, $return_error_message = false)
	{
		$numeric = self::numeric($input);
		if ($numeric !== true) return $numeric;
		if ($input >= $from && $input <= $to) return true;
		else if ($return_error_message == true) return ' must be a number from ' . $from . ' to ' . $to . ' inclusive.';
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
	public function min_length($input, $length, $return_error_message = false)
	{
		if (strlen($input) >= $length) return true;
		else if ($return_error_message == true) return ' must be at least ' . $length . ' characters.';
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
	public function max_length($input, $length, $return_error_message = false)
	{
		if (strlen($input) <= $length) return true;
		else if ($return_error_message == true) return ' must be ' . $length . ' characters or less.';
		else return false;
	}
	
}
