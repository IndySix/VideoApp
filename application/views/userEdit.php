<?PHP include 'includes/toppage.php';?>

<h1>Edit account</h1>
<?PHP if(!empty($message)): echo $message ?><br>


<?PHP endif; ?>
<form action="<?PHP echo baseUrl("user/edit/") ?>" method="post">
	<input type="hidden" name="authtoken" value="<?PHP echo $this->secure->authToken() ?>" />
	<h3>Email</h3>
	<?PHP echo $error['email'] ?><br>
	<input name="email" value="<?PHP echo $user['email'] ?>" />
	
	<h3>Password</h3>
	<?PHP echo $error['oldPassword'] ?><br>
	<label>Old password</label>
		<input name="oldPassword"/><br>

	<?PHP echo $error['password'] ?><br>	
	<label>New password</label>
		<input name="password"/><br>
	<label>New password retype</label>
		<input name="passwordCheck"/><br>

	<input type="submit" name="edit_submit" value="Save" />
</form>

<?PHP include 'includes/bottompage.php';?>