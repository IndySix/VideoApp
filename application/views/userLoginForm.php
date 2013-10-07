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

		<h1>Login form</h1>
		<?PHP echo $loginError ?>
		<form action="<?PHP echo baseUrl("user/login") ?>" method="post">
		<input type="hidden" name="authtoken" value="<?PHP echo $this->secure->authToken() ?>" />
		<p><input type="text" name="username" placeholder="Username" value="<?PHP echo $username ?>" /></p>
		<p><input type="password" name="password" placeholder="password" /></p>
		<p><input type="submit" name="login_submit" value="Login" /></p>
	</form>

	</div>
	<div id="footer">
		<p>&copy; 2013 <a href="#">Indy Six</a></p>
	</div>
</body>
</html>