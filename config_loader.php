<?php

foreach (glob(CONFDIR . 'config_*.php') as $config_file) {
	$mode = substr(basename($config_file), 7, -4);
	$all_config[$mode] = require $config_file;
}

$maximum_inheritance = 20;
$current_inheritance = 0;
$processed_config = array();

while (count($processed_config) < count($all_config) && $current_inheritance < $maximum_inheritance) {
	foreach ($all_config as $config_mode => $config_obj) {
		if (in_array($config_mode, $processed_config)) continue;
		
		//There are inherits that need to be processed
		if (!empty($config_obj['inherits'])) {
			$pending_inherits = array();
			$inherit_arr = explode(',', $config_obj['inherits']);
			foreach ($inherit_arr as $inherit) {
				//If the inherit still has inherits of its own, wait until later to merge it in
				if (!in_array($inherit, $processed_config)) $pending_inherits[] = $inherit;
				else {
					$all_config[$config_mode] = array_replace_recursive($all_config[$inherit], $all_config[$config_mode]);
				}
			}
			$config_obj['inherits'] = implode(',', $pending_inherits);
		}
		
		//All the inherits have been processed
		if (empty($config_obj['inherits'])) {
			$processed_config[] = $config_mode;
		}
	}
	$current_inheritance++;
}

if (count($processed_config) != count($all_config)) throw new \Exception('Maximum config inheritance level reached.');

if (empty($all_config)) return false;
return $all_config[$config_select];