<?php

$all_config = array();
$all_config[$config_select] = require(APP_DIR . 'config/' . $config_select . '.php');

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
				if (!isset($all_config[$inherit])) $all_config[$inherit] = require(APP_DIR . 'config/' . $inherit . '.php');
				
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
return $all_config[$config_select];