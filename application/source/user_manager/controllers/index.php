<?php
namespace Controllers\User_Manager;

class index extends \IgniteXT\Controller
{
	function index()
	{
		header('location:'.BASE_URL.'user_manager/users');
	}
}