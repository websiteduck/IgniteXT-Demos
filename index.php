<?php
/**
 *==============================================================================*
 *  ,--,                                   ,--,                                 *
 *  |  |                                   |  |            ,--,  ,------------, *
 *  |  |                          ,--, ,---'  '---,        |  |  |  ,--,  ,---' *
 *  |  |                          '__' '---,  ,---'         \  \/  /   |  |     *
 *  |  |    ,-----,   ,------,    ,--,     |  |   ,------,   \    /    |  |     *
 *  |  |   /  _   |  |  ,--,  |   |  |     |  |  |   ==  |   /    \    |  |     *
 *  |  |  |  / \  |  |  |  |  |   |  |     |  |  |  ,----'  /  /\  \   |  |     *
 *  |  |  |  \_/  |  |  |  |  |   |  |     |  |  |  '---', |  |  |  |  |  |     *
 * '----'  \      | '----''----' '----'   '----'  '-----,' '--'  '--' '----'    *
 *          '--|  |                                                             *
 *     ,'------'  '     P H P   F R A M E W O R K            v1.0.0b5           *
 *     ',---------'                                                             *
 *==============================================================================*
 *
 * @copyright  Copyright 2011-2015, Website Duck LLC (http://www.websiteduck.com)
 * @link       http://www.ignitext.com IgniteXT PHP Framework
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

define('BASE_DIR', dirname(__FILE__) . '/');

//Define APP_DIR, SHR_DIR, IXT_DIR, and select which configuration to use.
$config_select = require 'config_selector.php';

function find_file($file)
{
	$dirs = array(APP_DIR, SHR_DIR, IXT_DIR);
	foreach ($dirs as $dir) if (file_exists($dir . $file)) return $dir . $file;
	return null;
}

require find_file('statics/helpers.php');
require find_file('statics/get.php');
require find_file('statics/autoloader.php');

//Load the application configuration file. 
$config = new \IgniteXT\Config;
$config->load($config_select);

\Get::initialize($config);

$ini_sets = $config->get('ini_set');
if ($ini_sets !== null) foreach ($ini_sets as $key => $value) ini_set($key, $value);

define('APP_ID', $config->get('APP_ID'));
define('BASE_URL', $config->get('BASE_URL'));
define('ASSETS', $config->get('ASSETS'));

if ($config->get('enable_stack_trace')) {
	require IXT_DIR . 'php_error.php';
	$php_error_options = $config->get('php_error_options');
	if (empty($php_error_options)) $php_error_options = array('enable_saving' => false);
	\php_error\reportErrors($php_error_options);
}

$profiler = \Get::the('IgniteXT.Profiler');
$profiler->start();

$session_class = \Get::the('IgniteXT.Session');
$session_class->start();

$startup_file = find_file('startup.php');
if (!empty($startup_file)) require $startup_file;

/*
 * Run the specified route which will call the appropriate controller.
 * 
 * The $bootstrap variable can be set to true in an external script if you want 
 * to use this file as just a bootstrap and not automatically run a route.
 */
if (\u($bootstrap) !== true)
{
	$profiler->event('normal', 'IgniteXT', 'Start Application', 'Application has started running.');

	$router = \Get::the('IgniteXT.Router');
	$prefix = substr($_SERVER['SCRIPT_NAME'], 0, -9);
	$url_parts = parse_url($_SERVER["REQUEST_URI"]);
	$uri = $url_parts['path'];
	if (substr($uri, 0, strlen($prefix)) === $prefix) $uri = substr($uri, strlen($prefix));
	$router->route($uri);

	$profiler->event('normal', 'IgniteXT', 'Finish Application', 'Application has finished running.');
}