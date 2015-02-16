<?php
/**
 * Restful Routes
 *   Syntax: 
 *     $routes[] = array('restful', url, controller, [enable params]);
 *   Examples:
 *     $routes[] = array('restful', 'photos', 'Photos'), // example.com/photos/list will resolve to Photos::getList()
 *     $routes[] = array('restful', '(?P<username>.+?)/photos', 'Users.Photos'), // $_GET['username'] will contain the username variable
 *     $routes[] = array('restful', '', 'Static_Pages'), // This should be defined last since it will match all URLs
 *     $routes[] = array('restful', 'users', 'Users', true), //Enable params maps /users/view/123 to \Controllers\Users::view('123')
 *
 * Direct Routes
 *   Syntax: 
 *     $routes[] = array('direct', url, controller, action, [enable params], [method]);
 *   Examples:
 *     $routes[] = array('direct', 'photos', 'Photos', 'list'), // example.com/photos will resolve to Photos::list()
 *     $routes[] = array('direct', '', 'Index', 'index', true, 'get'), 
 *     $routes[] = array('direct', 'business-hours', 'Business_Info', 'business_hours', false, 'get'),
 */
$routes = array();
$routes[] = array('restful', 'libraries/stopwatch', 'Libraries.Stopwatch');
$routes[] = array('restful', 'libraries/form-validation', 'Libraries.Form_Validation');
$routes[] = array('restful', 'user-manager/users', 'User_Manager.Users');
$routes[] = array('direct', 'user-manager', 'User_Manager.Users', 'get_list', false);
$routes[] = array('restful', '', 'Index');
return $routes;