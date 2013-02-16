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

namespace IgniteXT;

class Form_Validation extends Entity
{
	public $array = array();
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
		if ($rule_class == null) $this->rule_class = \Get::the('\IgniteXT\Validation');
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
			$value = \array_get($this->array, $key);
			
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
	
	/**
	 * Returns the form input value that was submitted for the specified key.
	 * 
	 * @param string $key
	 * @param string $default
	 * @return string $text
	 */
	public function form_value($key, $default = '')
	{
		$value = \array_get($this->array, $key);
		if ($value === null) return $default;
		return htmlentities($value, ENT_QUOTES);
	}
	
	/**
	 * Returns the text need to select a dropdown item if the specified key/value pair is selected.
	 * 
	 * @param string $key
	 * @param string $check_value
	 * @param boolean $default
	 * @return string $select_text
	 */
	public function form_select($key, $check_value, $default = false)
	{
		$value = \array_get($this->array, $key);
		if ($value === null && $default == true) return 'selected="selected"';
		if ($value == $check_value) return 'selected="selected"';
		else return '';
	}
	
	/**
	 * Returns the text need to select a checkbox or radio if the specified key/value pair is selected.
	 * 
	 * @param string $key
	 * @param boolean $default
	 * @return string $checkbox_text
	 */
	public function form_checkbox($key, $default = false, $which_array = 'request')
	{
		$value = \array_get($this->array, $key);
		if ($value === null && $default == true) return 'checked="checked"';
		if ($value !== null) return 'checked="checked"';
		else return '';
	}
	
}