<?php

/**
 * Global Event Logging
 * If set to true, the Profiler will log everything regardless of what 
 * classes having logging enabled.
 */
\System\Profiler::log_everything(true);

/**
 * Class-based Event Logging
 * If global event logging is set to false, use this to enable logging
 * on a per-class basis. 
 */
// \System\Profiler::enable_logging('\System\Database');

/**
 * Show the event log based on some condition.  For example:
 * 
 *   Show based on remote IP address:  
 *     if ($_SERVER['REMOTE_ADDR'] == '123.123.123.123') {
 * 
 *   Show based on variable set in config files: 
 *     if ($debug === true) {
 */
if (true) 
{
	\System\Profiler::output_json(true);
	\System\Profiler::output_html(true);
}

/**
 * Database Configuration
 * \System\Database::connect(identifier, driver, server, username, password, database);
 */
\System\Database::connect_dsn('main', 'sqlite:ixt_demo.sqlite');