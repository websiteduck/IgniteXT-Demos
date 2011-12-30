<?php

/**
 * Global Event Logging
 * If this is false, the Event class will ignore all events.
 */
\System\Event::$log_events = false;

/**
 * Show the event log based on some condition.  For example:
 * 
 *   Show based on remote IP address:  
 *     if ($_SERVER['REMOTE_ADDR'] == '123.123.123.123') {
 * 
 *   Show based on variable set in config files: 
 *     if ($debug === true) {
 */
if (false) \System\Event::$display_log = true;

/**
 * Database Configuration
 * \System\Database::connect(identifier, driver, server, username, password, database);
 */
\System\Database::$log_events = false;
\System\Database::connect_dsn('main', 'sqlite:ixt_demo.sqlite');