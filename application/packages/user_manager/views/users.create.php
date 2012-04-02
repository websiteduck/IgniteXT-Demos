<? 
$data['tpl']['title'] = 'Create User - User Manager';
$data['tpl']['breadcrumbs'] = array( 
	array('user_manager', 'User Manager'),
	array('user_manager/users/list', 'Create User')
);
\System\Display::view('user_manager/menu', $data); 
?>
<br /><br />

<form action="" method="post">
	<div class="col_6">
		<table class="striped">
			<tbody>
				<tr>
					<th>Username: </th>
					<td><input type="text" name="username" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<button type="submit" class="green"><span class="icon white small" data-icon="C"></span> Create</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>