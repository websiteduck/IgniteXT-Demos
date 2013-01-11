<?php

foreach (glob(CONFDIR . 'config_*.php') as $config_file) {
	$mode = substr(basename($config_file), 7, -4);
	$all_config[$mode] = require $config_file;
}

foreach ($all_config as $config_mode => $config_obj) {
	if (!empty($config_obj['inherits'])) {
		$inherit_arr = explode(',', $config_obj['inherits']);
		foreach ($inherit_arr as $inherit) {
			$all_config[$config_mode] = array_replace_recursive($all_config[$inherit], $all_config[$config_mode]);
		}
	}
}

if (empty($all_config)) return false;
return $all_config[$config_select];