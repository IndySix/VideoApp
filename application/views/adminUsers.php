<?PHP include 'includes/adminToppage.php';?>

<h1>Users overview</h1>
<hr>
<p><a href="#">Add user</a>
<form id="search" action="<?PHP echo baseUrl("adminUsers") ?>" method="post">
	<select name="column">
		<option>Username</option>
		<option <?PHP echo $column == 'Email' ? 'SELECTED' : '' ?>>Email</option>
	</select>
    <input type="text" size="40" name="search" value="<?PHP echo $search ?>">
    <button type="submit" name="search_submit" value="Submit">Search</button>
</form>
<table width="100%">
	<tr>
		<th width="150px">Username</th>
		<th>Email</th>
		<th>Registration date</th>
		<th>Blocked</th>
	</tr>
	<?PHP foreach ($users as $user): ?>
		<tr>
			<td>
				<a href="<?PHP echo baseUrl("adminUsers/view/".$user['username']) ?>"><?PHP echo $user['username'] ?></a> 
				<span class="icon-items">
					<a href="<?PHP echo baseUrl("adminUsers/edit/".$user['username']) ?>"><img src="<?PHP echo baseUrl("pic/icons/pencil.png") ?>" alt="edit"></a>
				</span>
			</td>
			<td><?php echo $user['email']  ?></td>
			<td><?php echo $user['registrationDate'] ?></td>
			<td><?PHP echo $user['blocked'] == 0 ? '-' : 'BLOCKED' ?></td>
		</tr>
	<?PHP endforeach; ?>
</table>

<?PHP echo $paginate ?>

<?PHP include 'includes/adminBottompage.php';?>