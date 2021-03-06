<?php
$tpl->title = 'Form Validation';
$tpl->breadcrumbs = array(
	array('Libraries', ''),
	array('Form Validation', '')
);

//array(key, title, default value, instructions)
$form_validation_fields = array(
	array('first_name', 'First Name', 'John', ''),
	array('last_name', 'Last Name', 'Doe', ''),
	array('email', 'E-mail', 'user@email.com', ''),
	array('number', 'Number', '0xFF', ''),
	array('integer', 'Integer', '7', '(Not Required)'),
	array('decimal', 'Decimal', '11.5', '(10-20)'),
	array('underscore', 'Underscore', 'a_b', '')
);
?>

<?php $form_validation->set_delim('<li class="error">','</li>'); ?>

<?php if ($form_validation->checked_invalid()): ?>
	<div class="alert alert-error">
		<ul style="margin-bottom: 0;">
			<?=$form_validation->get_errors()?>
		</ul>
	</div>
<?php endif; ?>

<?php if ($form_validation->checked_valid()): ?>
	<br />
	<div class="alert alert-success">
		<h2>SUCCESS</h2>
	</div>
<?php endif; ?>

<?php $form_validation->set_delim('<span class="error">','</span>'); ?>

<form method="post" action="" class="form-horizontal">
	<?php foreach ($form_validation_fields as $form_validation_input): ?>
	<?php list($key, $title, $default, $instructions) = $form_validation_input; ?>
		<div class="control-group <?php if ($form_validation->field_error($key)) echo 'error'; ?>">
			<label class="control-label"><?php echo $title ?></label>
			<div class="controls">
				<input type="text" name="<?php echo $key ?>" value="<?php echo $form_validation->form_value($key, $default) ?>" />
				<?php echo $instructions ?>
				<span class="help-inline"><?php echo $form_validation->get_error($key) ?></span>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="form-actions">
		<button class="btn btn-primary" type="submit"><i class="icon-ok icon-white"></i> Submit</button>
	</div>
</form>