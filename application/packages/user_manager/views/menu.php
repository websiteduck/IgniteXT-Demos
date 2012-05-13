
<div class="well">
	<a href="<?php echo BASEURL ?>user_manager/users/list" class="btn btn-primary">
		<img src="<?php echo ASSETS ?>img/icons/users.png" alt="" class="icon" /> List Users</a>
	<a href="<?php echo BASEURL ?>user_manager/users/create" class="btn btn-primary">
		<img src="<?php echo ASSETS ?>img/icons/user--plus.png" alt="" class="icon" /> Create User</a>
</div>

<? if (isset($_SESSION['success_messages'])): ?>
	<div class="alert alert-success" style="width: 400px;">
		<? foreach ($_SESSION['success_messages'] as $message): ?>
			<img src="<?php echo ASSETS ?>img/icons/tick.png" alt="" class="icon"> <?=$message?><br />
		<? endforeach; ?>
	</div>
<? unset($_SESSION['success_messages']); endif; ?>