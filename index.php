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
 *     ,'------'  '     P H P   F R A M E W O R K                               *
 *     ',---------'                                                             *
 *==============================================================================*
 *
 * @copyright  Copyright 2011-2012, Website Duck LLC (http://www.websiteduck.com)
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
}

//Find and require important files.
require find_file('helpers.php');
require find_file('get.php');
require find_file('autoload.php');

//Load the application configuration file. 
$config = require find_file('config_loader.php');
if ($config === false) throw new \Exception('Failed to load configuration file.');

\Get::initialize($config);

//Create constants using data from the config file.
define('APP_ID', $config['APP_ID']);
define('BASE_URL', $config['BASE_URL']);
define('ASSETS', $config['ASSETS']);

$profiler = \Get::the('\IgniteXT\Profiler');
$profiler->start();

$session_class = \Get::the('\IgniteXT\Session');
$session_class->start();

/**
 * Run the specified route which will call the appropriate controller.
 * 
 * The $bootstrap variable can be set to true in an external script if you want 
 * to use this file as just a bootstrap and not automatically run a route.
 */
if (!isset($bootstrap) || $bootstrap === false)
{
	$profiler->event('normal', 'IgniteXT', 'Start Application', 'Application has started running.');

	$router = \Get::the('\IgniteXT\Router');
	$router->route(isset($_GET['ixt_route']) ? $_GET['ixt_route'] : '');

	$profiler->event('normal', 'IgniteXT', 'Finish Application', 'Application has finished running.');
}