<? 
$tpl->title = 'Create User - User Manager';
$this->view('user_manager/menu', $data); 

$tpl->breadcrumbs = array(
	array('Demo Apps', ''),
	array('User Manager', 'user_manager'),
	array('Create User', '')
);
?>

<form action="" method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Username</label>
		<div class="controls">
			<input type="text" name="username" />
		</div>
	</div>
	<div class="form-actions">
		<button class="btn btn-primary" type="submit"><i class="icon-ok icon-white"></i> Create User</button>
	</div>
</form>