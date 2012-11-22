<?php
namespace Controllers\User_Manager;

class users extends \Services\System\Controller
{
	public function __construct() {
		parent::__construct();
		$this->db = \Get::the('\Services\System\Database');
	}
	
	function index()
	{
		header('location:' . BASEURL . 'user_manager/users/list'); die();
	}
	
	function m_list()
	{
		$data['user_count'] = $this->db->field("SELECT count(*) FROM users");
		$data['users'] = $this->db->rows("SELECT id, username FROM users LIMIT 500");
		$this->display->template('user_manager/users.list', $data);
	}
	
	function create()
	{
		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			$id = $this->db->insert("INSERT INTO users (username) VALUES (?)", $_POST['username']);
			$_SESSION['success_messages'][] = 'User [' . $id . '] ' . $_POST['username'] . ' created.';
			header('location:' . BASEURL . 'user_manager/users/list'); die();
		}
		$this->display->template('user_manager/users.create', $data);
	}
	
	function delete()
	{
		$id = $_GET['id'];
		$username = $this->db->field("SELECT username FROM users WHERE id = ?", $id);
		$this->db->query("DELETE FROM users WHERE id = ?", $id);
		$_SESSION['success_messages'][] = 'User [' . $id . '] ' . $username . ' deleted.';
		header('location:' . BASEURL . 'user_manager/users/list'); die();
	}
}