<?php
namespace Controllers;
use \System\Database as DB;
class index extends \System\Controller
{
	function index()
	{
		\System\Display::template('index');
	}
}