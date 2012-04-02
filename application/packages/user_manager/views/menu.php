<hr />

<a href="<?=BASEURL?>user_manager/users/list" class="button"><img src="<?=ASSETS?>img/icons/users.png" alt="" /> List Users</a>
<a href="<?=BASEURL?>user_manager/users/create" class="button"><img src="<?=ASSETS?>img/icons/user--plus.png" alt="" /> Create User</a>

<hr />

<? if (isset($_SESSION['success_messages'])): ?>
	<div class="success">
		<? foreach ($_SESSION['success_messages'] as $message): ?>
			<img src="<?=ASSETS?>img/icons/tick.png" alt=""> <?=$message?><br />
		<? endforeach; ?>
	</div>
<? unset($_SESSION['success_messages']); endif; ?>