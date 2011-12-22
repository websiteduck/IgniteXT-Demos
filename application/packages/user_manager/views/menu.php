<h1>User Manager - <?=$content_title?></h1>

<a href="<?=BASEURL?>user_manager/users/list">List Users</a> : 
<a href="<?=BASEURL?>user_manager/users/create">Create User</a>

<? if (isset($_SESSION['success_messages'])): ?>
	<ul>
		<? foreach ($_SESSION['success_messages'] as $message): ?>
			<li><img src="<?=BASEURL?>assets/images/icons/tick.png" alt=""> <?=$message?></li>
		<? endforeach; ?>
	</ul>
<? unset($_SESSION['success_messages']); endif; ?>