<?php
$tpl->title = 'List Users - User Manager';
$this->view('user_manager/menu'); 

$tpl->breadcrumbs = array(
	array('Demo Apps', ''),
	array('User Manager', 'user_manager'),
	array('List Users', '')
);
?>

<style>
#tbl_users form { padding: 0; margin: 0; }
#tbl_users form img { cursor: pointer; }
</style>

<p>There <?=$user_count==1?'is':'are'?> <?=$user_count?> user<?=$user_count==1?'':'s'?>.</p>

<table id="tbl_users" class="table table-bordered table-striped" style="width: 400px;">
	<thead>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($users as $user): ?>
		<tr>
			<th style="font-weight: bold;"><?=$user->id?></th>
			<td><?=$user->username?></td>
			<td>
				<form action="delete" method="POST">
					<input type="hidden" name="id" value="<?=$user->id?>">
					<input type="image" src="<?=ASSETS?>img/icons/cross-button.png" alt="Delete" />
				</form>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>