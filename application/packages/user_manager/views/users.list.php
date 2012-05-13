<? 
$tpl['title'] = 'List Users - User Manager';
\System\Display::view('user_manager/menu', $data); 

$tpl['breadcrumbs'] = array(
	array('Demo Apps', ''),
	array('User Manager', 'user_manager'),
	array('List Users', '')
);
?>

<p>There <?=$user_count==1?'is':'are'?> <?=$user_count?> user<?=$user_count==1?'':'s'?>.</p>

<table class="table table-bordered table-striped" style="width: 400px;">
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
				<a href="<?php echo BASEURL ?>user_manager/users/delete?id=<?=$user->id?>">
					<img src="<?php echo ASSETS ?>img/icons/cross-button.png" alt="Delete" />
				</a>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>