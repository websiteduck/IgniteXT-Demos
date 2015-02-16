<?php
/**
 * IXT Form Validation Library
 * 
 * Validates user input. Can also set form values when validation fails and
 * display error messages with custom delimiters.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
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

	private $session;
	
	public function valid() { return $this->valid; }
	public function checked() { return $this->checked; }
	public function checked_valid() { return ($this->checked && $this->valid); }
	public function checked_invalid() { return ($this->checked && !$this->valid); }
	public function reset() { $this->valid = false; $this->checked = false; $this->errors = array(); }

	public function clear_rules() { $this->rules = array(); }
	public function get_rules() { return $this->rules; }
	public function get_error_array() { return $this->errors; }

	public function set_error($key, $message) { $this->errors[$key] = $message; $this->valid = false; }
	public function clear_errors() { $this->errors = array(); }

	public function set_delim($pre_delim, $post_delim) { $this->pre_delim = $pre_delim; $this->post_delim = $post_delim; }

	public function __construct($rule_class = null)
	{
		$language = \Get::the('IgniteXT.Language');
		$language->load('validation');

		$this->session = \Get::the('IgniteXT.Session');

		if ($rule_class == null) $this->rule_class = \Get::the('IgniteXT.Validation');
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
	 * Uses the rules to run validation on a given array.
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
				$rule_params = $this->resolve_fields($parameters);
				array_unshift($rule_params, $value);
				$output = call_user_func_array(array($this->rule_class, $rule), $rule_params);
				if ($output !== true) $this->errors[$key] = $this->determine_failure_output($rule, $label, $parameters);
			}
		}

		if (count($this->errors) > 0) $this->valid = false;
		$this->checked = true;
		return $this->valid;
	}
	
	private function determine_failure_output($rule, $label, $parameters) 
	{
		foreach ($parameters as &$parameter) {
			$match = preg_match('%^{{(.+)}}$%', $parameter, $matches);
			if ($match === 1) $parameter = \__('validation.the_compare_field', $this->rules[$matches[1]]['label']);
		}
		unset($parameter);
		array_unshift($parameters, \__('validation.the_field', $label));
		return \__('validation.' . $rule, $parameters);
	}
	
	private function resolve_fields($parameters)
	{
		if (empty($parameters)) return $parameters;
		foreach ($parameters as &$parameter) {
			$match = preg_match('%^{{(.+)}}$%', $parameter, $matches);
			if ($match === 1) $parameter = \array_get($this->array, $matches[1]);
		}
		return $parameters;		
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
         * Checkbox elements should have a hidden input before them that sets their false value to 0 like so:
         * <input type="hidden" name="name" value="0" />
         * <input type="checkbox" name="name" < ?=$form->form_checkbox('name', true)? > />
         * Otherwise the default value will be set whenever the checkbox is unchecked.
	 * 
	 * @param string $key
	 * @param boolean $default
	 * @return string $checkbox_text
	 */
	public function form_checkbox($key, $default = false)
	{
		$value = \array_get($this->array, $key);
		if ($value === null && $default == true) return 'checked="checked"';
		if (!empty($value)) return 'checked="checked"';
		else return '';
	}
	
	public function form_multiselect($key, $check_value, $default = false) 
	{
		$arr = \array_get($this->array, $key);
		if ($arr === null && $default == true) return 'selected="selected"';
		if (is_array($arr) && in_array($check_value, $arr)) return 'selected="selected"';
		else return '';
	}

	public function save()
	{
		$this->session->set_flash('ixt_form_validation', $this->object_array_data());
	}
	
	public function load()
	{
		$properties = $this->session->get_flash('ixt_form_validation');

		if (!empty($properties)) {
			foreach ($properties as $key => $val) $this->$key = $val;
		}
	}
	
	public function object_array_data()
	{
		return array(
			'array' => $this->array,
			'rules' => $this->rules,
			'errors' => $this->errors,
			'checked' => $this->checked,
			'valid' => $this->valid,
			'pre_delim' => $this->pre_delim,
			'post_delim' => $this->post_delim,
		);
	}
	
}
