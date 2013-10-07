<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Indy Six - <?PHP global $title; echo $title; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="<?php echo baseUrl('css/style.css') ?>" type="text/css">
</head>

<body>
    <div id="header">
        <img src="<?PHP echo baseUrl("img/logo.png") ?>" title="logo" />

        <div id="cssmenu">
            <ul>
                <?PHP if($this->uri->segment(2,'') != 'login'): ?>
                    <li><a href="<?PHP echo baseUrl("user/login") ?>">Login</a></li>
                <? endif; ?>

                <?PHP if(__CONTROLLER_NAME != 'home' && $this->uri->segment(2,'') != 'signup'): ?>
                    <li><a href="<?PHP echo baseUrl("user/signup") ?>">Sign Up</a></li>
                <? endif; ?>
            </ul>
        </div>
    </div>
    <div id="content" style="width:96%;" >

<script type="text/javascript" src="<?PHP echo baseUrl("javascript/ajaxClass.js") ?>"></script>
<script type="text/javascript">
	<?PHP include(__SITE_PATH.'/javascript/signup.js'); ?>
</script>

<h1>Sign Up</h1>
<?PHP echo $tokenError ?>
<form action="<?PHP echo baseUrl("user/signup") ?>" method="post">
	<input type="hidden" name="authtoken" value="<?PHP echo $this->secure->authToken() ?>" />
	<div id="usernameError"><?PHP echo $usernameError ?></div>
	<p><input type="text" name="signup_username" placeholder="Pick a username" value="<?PHP echo $username ?>" onblur="checkUsername(this.value)" />
	<div id="emailError"><?PHP echo $emailError ?></div>
	<p><input type="text" name="signup_email" placeholder="Email" value="<?PHP echo $email ?>" onblur="checkEmail(this.value)"/>
	<div id="passwordError"><?PHP echo $passwordError ?></div>
	<p><input type="password" name="signup_password" placeholder="Create a password" onblur="checkPassword(this.value)"/>
	<p><input type="submit" name="signup_submit" value="Sign Up" />
</form>

	</div>
	<div id="footer">
		<p>&copy; 2013 <a href="#">Indy Six</a></p>
	</div>
</body>
</html>