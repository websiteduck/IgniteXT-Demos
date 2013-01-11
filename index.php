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

define('BASEDIR', dirname(__FILE__) . '/');
define('CONFDIR', 'application/config/');

function u(&$v, $default = null) { return isset($v) ? $v : $default; }
require 'get.php';

/**
 * Load the application configuration file. 
 */
$config_select = require 'config_selector.php';
$config = require 'config_loader.php';
if ($config === false) throw new \Exception('Failed to load configuration file.');

if (isset($config['hard_redirects'])) \Get::$hard_redirects = $config['hard_redirects'];
if (isset($config['soft_redirects'])) \Get::$soft_redirects = $config['soft_redirects'];

/**
 * Create constants using data from the config file.
 */
define('APPID', $config['APPID']);
define('APPDIR', $config['APPDIR']);
define('SHRDIR', $config['SHRDIR']);
define('IXTDIR', $config['IXTDIR']);
define('BASEURL', $config['BASEURL']);
define('ASSETS', $config['ASSETS']);

/**
 * Find and require the autoloader. 
 */
if (file_exists(APPDIR . 'autoload.php')) require APPDIR . 'autoload.php';
elseif (file_exists(SHRDIR . 'autoload.php')) require SHRDIR . 'autoload.php';
elseif (file_exists(IXTDIR . 'autoload.php')) require IXTDIR . 'autoload.php';
else throw new \Exception('Autoloader not found.');

$profiler = \Get::the('\Services\System\Profiler');
$profiler->start();

$session_class = \Get::the('\Services\System\Session');
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

	$router = \Get::the('\Services\System\Router');
	$router->route(isset($_GET['ixt_route']) ? $_GET['ixt_route'] : '');

	$profiler->event('normal', 'IgniteXT', 'Finish Application', 'Application has finished running.');
}