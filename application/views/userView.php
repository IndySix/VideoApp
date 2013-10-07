<?PHP include 'includes/toppage.php';?>

<script type="text/javascript" src="<?PHP echo baseUrl("javascript/utils.js") ?>"></script>
<script type="text/javascript">
	function moreInfoToggle(link, div) {
		toggleCollapse(div);
		var node = document.getElementById(div);
		if(node.offsetHeight == 0){
			link.innerHTML = "Hide";
		} else {
			link.innerHTML = "More";
		}
	}
</script>

<h1><img src="<?PHP echo gravatarUrl($user['email'], $user['validEmail']) ?>" />
	<?PHP echo $user['username'] ?></h1>
<?PHP if($ownProfile): ?>
	<p><a href="<?PHP echo baseUrl("user/edit") ?>">Edit account</a>
	<br><br>
<?PHP endif; ?>

<?PHP if($ownProfile): ?>
	<p><span>Email: </span><?PHP echo $user['email'] ?>
		<img src="<?PHP echo baseUrl("pic/icons/".($user['validEmail'] == 0 ? 'delete.png': 'accept.png')) ?>">
		<?PHP if(!empty($user['email']) && $user['validEmail'] == 0): ?>
			<a href="<?PHP echo baseUrl('user/emailValidation/'.$this->secure->authToken()) ?>">validate email</a>
		<?PHP endif; ?>
<?PHP endif; ?>

<p><span>Registration date: </span><?php echo $user['registrationDate'] ?>

<?PHP if($ownProfile): ?>
<p><a href="#" onclick="moreInfoToggle(this,'moreInfo')">More</a>
<div id="moreInfo" class="collapse">
	<h1>Login Sessions</h1>
	<table cellpadding="5px">
		<tr>
			<th>IP</th>
			<th>Login date</th>
		</tr>
		<?PHP foreach ($loginSessions as $session): ?>
			<tr>
				<td><?PHP echo $session['ip'] ?></td>
				<td><?PHP echo $session['loginDate'] ?></td>
				<td><?PHP echo $session['isSession'] ? 'this session' : 'delete'?></td>
			</tr>
		<?PHP endforeach; ?>
	</table>
</div>
<?PHP endif; ?>
<?PHP include 'includes/bottompage.php';?>