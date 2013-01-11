<?php
/**
 * IXT Form Validation Library
 * 
 * Validates user input. Can also set form values when validation fails and
 * display error messages with custom delimiters.
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Entities\IXT;

class Form_Validation extends \Entities\System\Entity
{
	private $array = array();
	private $rules = array();
	private $errors = array();
	private $checked = false;
	private $valid = false;
	private $rule_class;
	
	private $pre_delim;
	private $post_delim;
	
	public function valid() { return $this->valid; }
	public function checked() { return $this->checked; }
	public function checked_valid() { return ($this->checked && $this->valid); }
	public function checked_invalid() { return ($this->checked && !$this->valid); }
	public function reset() { $this->valid = false; $this->checked = false; $this->errors = array(); }

	public function clear_rules() { $this->rules = array(); }
	public function get_rules() { return $this->rules; }

	public function set_error($key, $message) { $this->errors[$key] = $message; $this->valid = false; }
	public function clear_errors() { $this->errors = array(); }

	public function set_delim($pre_delim, $post_delim) { $this->pre_delim = $pre_delim; $this->post_delim = $post_delim; }

	public function __construct($rule_class = null)
	{
		if ($rule_class == null) $this->rule_class = \Get::the('\Services\IXT\Validation');
		else $this->rule_class = $rule_class;
	}
	
	/**
	 * Takes the rules array and puts it into the format that I want.
	 * 
	 * In: array('name', 'label', 'rule,list')
	 * Out: 'name' => array( 'label' => 'label', 'rules' => 'rule,list' )
	 * 
	 * @param array $rules
	 */
	public function set_rules($rules)
	{
		$this->rules = array();
		$this->add_rules($rules);
	}
	
	/**
	 * Same as set_rules but doesn't clear the rule array
	 * 
	 * @param type $rules 
	 */
	public function add_rules($rules)
	{
		if (is_array($rules) === false) throw new \Exception('Invalid rule array.  Please make sure array is in this format: array( array("fieldname", "Field Label", "comma,separated,rule,list"),... )');
		foreach ($rules as $rule)	
		{
			if (is_array($rule) === false || count($rule) != 3) throw new \Exception('Invalid rule array.  Please make sure array is in this format: array( array("fieldname", "Field Label", "comma,separated,rule,list"),... )');
			$this->rules[ $rule[0] ] = array('label' => $rule[1], 'rules' => $rule[2]);
		}
	}
	
	/**
	 * Uses the rules that were previously set to run validation on a given array.
	 * 
	 * @param array $array The array to validate. This will usually be $_GET or $_POST
	 * @return boolean $valid
	 */
	public function validate($array)
	{
		if (is_array($this->rules) === false) $this->rules = array();
		$this->array = $array;
		$this->valid = true;

		foreach ($this->rules as $key => $info)
		{
			$label = $info['label'];
			$rule_list = $info['rules'];
			$value = $this->get_value($key);
			
			if ($rule_list=='') continue;
			$rule_array = explode('|', $rule_list);
			
			foreach ($rule_array as $rule) 
			{
				if ($rule != 'required' && $value == '') continue;
				list($rule, $parameters) = $this->rule_parts($rule);
				array_unshift($parameters, $value);
				array_push($parameters, true);
				$output = call_user_func_array(array($this->rule_class, $rule), $parameters);
				if ($output !== true) $this->errors[$key] = 'The ' . $label . ' field ' . $output;
			}
		}
		if (count($this->errors) > 0) $this->valid = false;
		$this->checked = true;
		return $this->valid;
	}
	
	private function rule_parts($rule)
	{
		if (strpos($rule,'['))
		{
			if (substr($rule, -1) != ']') throw new \Exception('Invalid rule parameter format.  Please make sure rule is in this format: rule[1,2]');
			$rule_parts = explode('[', $rule);
			list($rule, $parameters) = $rule_parts;
			$parameters = substr($parameters, 0, -1);
			$parameters = explode(',',$parameters);
		}
		else
		{
			$parameters = array();
		}
		return array($rule, $parameters);
	}

	/**
	 * This function returns the value of an array using a string like so:
	 * 'person' -> $array['person']
	 * 'people[5]' -> $array['people'][5]
	 * 
	 * @param array $array
	 * @param string $key
	 * @return string $value
	 */
	private function get_value($key_string)
	{
		if (empty($key_string)) throw new \Exception('Cannot get empty array key.');
		$keys = preg_split("/[\[\]]+/", $key_string);
		$current_array = &$this->array;
		
		foreach ($keys as $key) {
			if ($key == '') continue;
			if (!is_array($current_array)) return null;
			if (!isset($current_array[$key])) return null;
			$current_array = &$current_array[$key];
		}
		return $current_array;
	}
	
	/**
	 * Gets the error message for a single field.  The message is wrapped in delimiters.
	 * 
	 * @param string $key
	 * @return string $error_message
	 */
	public function get_error($key)
	{
		if (!isset($this->errors[$key])) return '';
		else return $this->pre_delim . $this->errors[$key] . $this->post_delim;
	}

	/**
	 * Gets all of the error messages in a string. Each individual error message is wrapped in delimeters.
	 * You can optionally specify a separator that will be inserted between error messages (e.g. <br />)
	 * 
	 * @param string $separator
	 * @return string $error_message
	 */
	public function get_errors($separator = '')
	{		
		$error_string = implode($this->post_delim . $separator . $this->pre_delim, $this->errors);
		return $this->pre_delim . $error_string . $this->post_delim;
	}
	
	/**
	 * Returns whether or not the specified field is valid.
	 * 
	 * @param string $key
	 * @return boolean $valid
	 */
	public function field_valid($key)
	{
		if (isset($this->errors[$key])) return false;
		else return true;
	}	
	
	/**
	 * Returns whether or not the specified field had an error.
	 * 
	 * @param string $key
	 * @return boolean $error
	 */
	public function field_error($key)
	{
		return !$this->field_valid($key);
	}	
	
}