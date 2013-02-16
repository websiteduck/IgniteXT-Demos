<?php
namespace Controllers;

class index extends \IgniteXT\Controller
{
	function index()
	{
		$this->display->template('index');
	}
}