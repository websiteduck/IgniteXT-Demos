<? \System\Display::view('user_manager/menu', $data); ?>

<p>There <?=$user_count==1?'is':'are'?> <?=$user_count?> user<?=$user_count==1?'':'s'?>.</p>

<table>
	<? foreach ($users as $user): ?>
		<tr>
			<td>[<?=$user->id?>]</td>
			<td><?=$user->username?></td>
			<td>
				<a href="<?=BASEURL?>user_manager/users/delete?id=<?=$user->id?>">
					<img src="<?=BASEURL?>assets/images/icons/cross-button.png" alt="Delete">
				</a>
			</td>
		</tr>
	<? endforeach; ?>
</table>