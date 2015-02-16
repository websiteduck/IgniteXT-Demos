<?php
namespace Controllers;

class Index extends \IgniteXT\Controller
{
	function get_index()
	{
		$this->display->template('index');
	}
}