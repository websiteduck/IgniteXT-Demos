<?php
namespace Controllers\User_Manager;

class Users extends \IgniteXT\Controller
{
	function get_list()
	{
		$this->data['user_count'] = $this->db->field("SELECT count(*) FROM users");
		$this->data['users'] = $this->db->rows("SELECT id, username FROM users LIMIT 500");
		$this->display->template('user_manager/users.list');
	}
	
	function get_create()
	{
		$this->display->template('user_manager/users.create');
	}

	function post_create()
	{
		$username = $this->input->post('username');
		$id = $this->db->insert_id("INSERT INTO users (username) VALUES (?)", $username);
		$this->sess['success_messages'][] = 'User [' . $id . '] ' . $username . ' created.';
		$this->router->redirect('user-manager/users/list');
	}
	
	function post_delete()
	{
		$id = $this->input->post('id');
		$username = $this->db->field("SELECT username FROM users WHERE id = ?", $id);
		$this->db->query("DELETE FROM users WHERE id = ?", $id);
		$this->sess['success_messages'][] = 'User [' . $id . '] ' . $username . ' deleted.';
		$this->router->redirect('user-manager/users/list');
	}
}