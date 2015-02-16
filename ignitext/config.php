<?php
/**
 * Config
 * 
 * Manages config data.
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace IgniteXT;

class Config
{
	protected $data = array();
	public $select = null;
	
	public function load($config_select) 
	{
		$all_config = array();
		$all_config[$config_select] = require(APP_DIR . 'config/' . $config_select . '/' . $config_select . '.php');

		$maximum_inheritance = 20;
		$current_inheritance = 0;
		$pending_config = true;
		$processed_config = array();

		while ($pending_config && $current_inheritance < $maximum_inheritance) {
			$pending_config = false;
			foreach ($all_config as $config_mode => $config_obj) {
				if (in_array($config_mode, $processed_config)) continue;

				//There are inherits that need to be processed
				if (!empty($config_obj['inherits'])) {
					$pending_inherits = array();
					$inherit_arr = explode(',', $config_obj['inherits']);
					foreach ($inherit_arr as $inherit) {
						if (!isset($all_config[$inherit])) $all_config[$inherit] = require(APP_DIR . 'config/' . $inherit . '/' . $inherit . '.php');

						//If the inherit still has inherits of its own, wait until later to merge it in
						if (!in_array($inherit, $processed_config)) $pending_inherits[] = $inherit;
						else $all_config[$config_mode] = array_replace_recursive($all_config[$inherit], $all_config[$config_mode]);
					}
					$config_obj['inherits'] = implode(',', $pending_inherits);
				}

				//All the inherits have been processed
				if (empty($config_obj['inherits']))	$processed_config[] = $config_mode;
				else $pending_config = true;
			}
			$current_inheritance++;
		}

		if ($pending_config) throw new \Exception('Maximum config inheritance level reached.');

		if (empty($all_config)) return false;
		$this->data = $all_config[$config_select];
		$this->select = $config_select;
	}
	
	public function set($name, $value, $separator = '.')
	{
		\array_set($this->data, $name, $value, $separator);
	}
	
	public function get($name = '', $separator = '.')
	{
		return \array_get($this->data, $name, $separator);
	}
	
	public function &reference()
	{
		return $this->data;
	}
}