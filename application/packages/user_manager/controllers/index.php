<?php
namespace Controllers\User_Manager;
class index extends \System\Controller
{
	function index()
	{
		header('location:'.BASEURL.'user_manager/users');
	}
}