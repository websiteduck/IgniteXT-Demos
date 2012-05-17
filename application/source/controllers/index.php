<?php
namespace Controllers;

use \Services\System\Display;
use \Services\System\Database as DB;

class index extends \Services\System\Controller
{
	function index()
	{
		Display::template('index');
	}
}