<?php
namespace Controllers\User_Manager;
use \System\Database as DB;
class users extends \System\Controller
{
	function index()
	{
		header('location:'.BASEURL.'user_manager/users/list'); die();
	}
	
	function m_list()
	{
		$data['user_count'] = DB::field("SELECT count(*) FROM users");
		$data['users'] = DB::rows("SELECT id, username FROM users LIMIT 500");
		\System\Display::template('user_manager/users.list', $data);
	}
	
	function create()
	{
		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			$id = DB::insert("INSERT INTO users (username) VALUES (?)", $_POST['username']);
			$_SESSION['success_messages'][] = 'User [' . $id . '] ' . $_POST['username'] . ' created.';
			header('location:'.BASEURL.'user_manager/users/list'); die();
		}
		\System\Display::template('user_manager/users.create', $data);
	}
	
	function delete()
	{
		$id = $_GET['id'];
		$username = DB::field("SELECT username FROM users WHERE id = ?", $id);
		DB::query("DELETE FROM users WHERE id = ?", $id);
		$_SESSION['success_messages'][] = 'User [' . $id . '] ' . $username . ' deleted.';
		header('location:'.BASEURL.'user_manager/users/list'); die();
	}
}