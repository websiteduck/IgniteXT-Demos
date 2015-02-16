
<div class="well">
	<a href="<?=BASE_URL?>user-manager/users/list" class="btn btn-primary">
		<img src="<?=ASSETS?>img/icons/users.png" alt="" class="icon"> List Users</a>
	<a href="<?=BASE_URL?>user-manager/users/create" class="btn btn-primary">
		<img src="<?=ASSETS?>img/icons/user--plus.png" alt="" class="icon"> Create User</a>
</div>

<?php if (isset($this->sess['success_messages'])): ?>
	<div class="alert alert-success" style="width: 400px;">
		<?php foreach ($this->sess['success_messages'] as $message): ?>
			<img src="<?php echo ASSETS ?>img/icons/tick.png" alt="" class="icon"> <?=$message?><br>
		<?php endforeach; ?>
	</div>
<?php unset($this->sess['success_messages']); endif; ?>
