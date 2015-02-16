<?php
namespace Controllers\Libraries;

class Form_Validation extends \IgniteXT\Controller
{
	function get_index()
	{
		$form_validation = \Get::a('IgniteXT.Form_Validation');
		$form_validation->load();
		$this->data['form_validation'] = $form_validation;
		$this->display->template('libraries/form_validation');
	}

	function post_index()
	{		
		$form_validation = \Get::a('IgniteXT.Form_Validation');
		$rules_array = array(
			array('first_name', 'First Name', 'required|min_length[2]'),
			array('last_name', 'Last Name', 'required|min_length[2]'),
			array('email', 'Email', 'required|email'),
			array('number', 'Number', 'required|numeric'),
			array('integer', 'Integer', 'integer'),
			array('decimal', 'Decimal', 'required|decimal|range[10,20]')
		);
		$form_validation->set_rules($rules_array);
		$form_validation->validate($_POST);
			
		if (strpos($_POST['underscore'],'_') === false) {
			$form_validation->set_error('underscore', 'The Underscore field must contain an underscore.');
		}

		$form_validation->save();
		$this->router->redirect('libraries/form-validation');
	}
}