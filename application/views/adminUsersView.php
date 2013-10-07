<?PHP include 'includes/adminToppage.php';?>

<h2><?PHP echo $user['username'] ?> <a href="<?PHP echo baseUrl("adminUsers/edit/".$user['username']) ?>"><img src="<?PHP echo baseUrl("pic/icons/pencil.png") ?>" alt="edit"></a></h2>
<hr>
<div class="userView">
	<p><span>Email:</span><?PHP echo $user['email'] ?>
		<img src="<?PHP echo baseUrl("pic/icons/".($user['validEmail'] == 0 ? 'delete.png': 'accept.png')) ?>"> 
	<p><span>Registration date:</span><?php echo $user['registrationDate'] ?>
	<p><span>Blocked:</span><?PHP echo $user['blocked'] == 0 ? 'No' : 'Yes' ?>
</div>
<?PHP include 'includes/adminBottompage.php';?>