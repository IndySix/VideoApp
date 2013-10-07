<?PHP include 'includes/adminToppage.php';?>

<h2><?PHP echo $user['username'] ?></a></h2>
<hr>
<form action="<?PHP echo baseUrl("adminUsers/edit/".$user['username']) ?>" method="post" style="width:400px">
	<div id="emailError"><?PHP echo $error['email'] ?></div>
	<p><label>Email:</label><input type="text" name="email" placeholder="Email" value="<?PHP echo $user['email'] ?>"/>

	<div id="passwordError"><?PHP echo $error['password'] ?></div>
	<p><label>Password:</label><input type="password" name="password" placeholder="password"/>

	<p><label>Block:</label><input type="checkbox" name="block" value="1" <?PHP echo $user['blocked'] == 1 ? 'CHECKED' : ''?> />
	
	<p><input type="submit" name="edit_submit" value="Edit" />
</form>
<?PHP include 'includes/adminBottompage.php';?>