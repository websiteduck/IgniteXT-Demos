<?php
namespace Controllers;

class index extends \Services\System\Controller
{
	function index()
	{
		$this->display->template('index');
	}
}