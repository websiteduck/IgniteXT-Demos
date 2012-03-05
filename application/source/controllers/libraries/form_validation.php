<?php
namespace Controllers\Libraries;
class form_validation extends \System\Controller
{
	function index()
	{
		$data['form'] = $form = new \Libraries\IXT\Form_Validation();
		
		if ($_SERVER['REQUEST_METHOD']=='POST')
		{
			$rules_array = array(
				array('first_name', 'First Name', 'required|min_length[2]'),
				array('last_name', 'Last Name', 'required|min_length[2]'),
				array('email', 'Email', 'required|email'),
				array('number', 'Number', 'required|numeric'),
				array('integer', 'Integer', 'integer'),
				array('decimal', 'Decimal', 'required|decimal|range[10,20]')
			);
			$form->set_rules($rules_array);
			$form->validate($_POST);
			
			if (strpos($_POST['underscore'],'_')===false)
				$form->set_error('underscore', 'The Underscore field must contain an underscore.');
		}
		\System\Display::template('libraries/form_validation', $data);
	}
}