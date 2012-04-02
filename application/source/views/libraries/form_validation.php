<?php
$data['tpl']['title'] = 'Form Validation';
$data['tpl']['breadcrumbs'] = array( 
	array('', 'Libraries'),
	array('libraries/form_validation', 'Form Validation')
);
?>
<style type="text/css">
	#tbl_form th { text-align: right; }
	.error { color: #F00; }
	.success { color: #0A0; font-size: 30px; }
</style>

<? $form->set_delim('<li class="error">','</li>'); ?>

<? if ($form->checked_invalid()): ?>
<ul style="border: 1px solid red; margin: 20px;">
	<?=$form->get_errors()?>
</ul>
<? endif; ?>

<? $form->set_delim('<span class="error">','</span>'); ?>

<form method="post" action="">
	<table id="tbl_form">
		<tr>
			<th>First Name: </th>
			<td><input type="text" name="first_name" value="<?=$form->form_value('first_name','First')?>" />  <?=$form->get_error('first_name')?></td>
		</tr>
		<tr>
			<th>Last Name: </th>
			<td><input type="text" name="last_name" value="<?=$form->form_value('last_name','Last')?>" />  <?=$form->get_error('last_name')?></td>
		</tr>
		<tr>
			<th>E-mail: </th>
			<td><input type="text" name="email" value="<?=$form->form_value('email','user@email.com')?>" />  <?=$form->get_error('email')?></td>
		</tr>
		<tr>
			<th>Number: </th>
			<td><input type="text" name="number" value="<?=$form->form_value('number','0xFF')?>" />  <?=$form->get_error('number')?></td>
		</tr>
		<tr>
			<th>Integer: </th>
			<td><input type="text" name="integer" value="<?=$form->form_value('integer','7')?>" />(Not Required)  <?=$form->get_error('integer')?></td>
		</tr>
		<tr>
			<th>Decimal: </th>
			<td><input type="text" name="decimal" value="<?=$form->form_value('decimal','11.5')?>" />(10-20)  <?=$form->get_error('decimal')?></td>
		</tr>
		<tr>
			<th>Underscore: </th>
			<td><input type="text" name="underscore" value="<?=$form->form_value('underscore','a_b')?>" />  <?=$form->get_error('underscore')?></td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td><input type="submit" value="Submit" /></td>
		</tr>
	</table>
</form>

<? if ($form->checked_valid()): ?>
<br />
<span class="success">SUCCESS</span>
<? endif; ?>