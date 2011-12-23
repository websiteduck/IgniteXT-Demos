<style type="text/css">
	#content button { padding: 5px; }
</style>

<hr />

<button onclick="window.location = '<?=BASEURL?>user_manager/users/list'">
	<img src="<?=BASEURL?>assets/images/icons/users.png" class="icon" alt="" />
	List Users
</button>
<button onclick="window.location = '<?=BASEURL?>user_manager/users/create'">
	<img src="<?=BASEURL?>assets/images/icons/user--plus.png" class="icon" alt="" />
	Create User
</button>

<hr />

<? if (isset($_SESSION['success_messages'])): ?>
	<div class="success">
		<? foreach ($_SESSION['success_messages'] as $message): ?>
			<img src="<?=BASEURL?>assets/images/icons/tick.png" alt=""> <?=$message?><br />
		<? endforeach; ?>
	</div>
<? unset($_SESSION['success_messages']); endif; ?>