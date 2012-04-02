<? 
$data['tpl']['title'] = 'List Users - User Manager';
$data['tpl']['breadcrumbs'] = array( 
	array('user_manager', 'User Manager'),
	array('user_manager/users/list', 'List Users')
);
\System\Display::view('user_manager/menu', $data); 
?>

<p>There <?=$user_count==1?'is':'are'?> <?=$user_count?> user<?=$user_count==1?'':'s'?>.</p>

<div class="col_6">
<table class="striped tight">
	<thead>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<? foreach ($users as $user): ?>
		<tr>
			<th style="font-weight: bold;"><?=$user->id?></th>
			<td><?=$user->username?></td>
			<td>
				<a href="<?=BASEURL?>user_manager/users/delete?id=<?=$user->id?>">
					<img src="<?=ASSETS?>img/icons/cross-button.png" alt="Delete" />
				</a>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>
</div>