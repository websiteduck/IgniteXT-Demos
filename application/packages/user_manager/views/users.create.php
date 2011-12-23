<? \System\Display::view('user_manager/menu', $data); ?>
<br /><br />

<form action="" method="post">
	<table class="ixt_table">
		<tr>
			<th>Username: </th>
			<td><input type="text" name="username" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<button type="submit">
					<img src="<?=BASEURL?>assets/images/icons/tick.png" class="icon" alt="" />
					Create
				</button>
			</td>
		</tr>
	</table>
</form>