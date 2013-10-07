<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Indy Six - <?PHP global $title_page; echo $title_page; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="<?php echo baseUrl('css/style.css') ?>" type="text/css">
</head>

<body>
    <div id="header">
        <img src="<?PHP echo baseUrl("img/logo.png") ?>" title="logo" />

        <div id="cssmenu">
            <ul>
                <?PHP if($this->secure->isLoggedin()): ?>
                    <li class="/active"><a href="<?PHP echo baseUrl("home") ?>" title="Home">Home</a></li>
                    <li><a href="<?PHP echo baseUrl("video/upload") ?>" title="Home">Upload</a></li>
                <? else: ?>
                    <?PHP if($this->uri->segment(2,'') != 'login'): ?>
                        <li><a href="<?PHP echo baseUrl("user/login") ?>">Login</a></li>
                    <? endif; ?>

                    <?PHP if(__CONTROLLER_NAME != 'home' && $this->uri->segment(2,'') != 'signup'): ?>
                        <li><a href="<?PHP echo baseUrl("user/signup") ?>">Sign Up</a></li>
                    <? endif; ?>
                <? endif; ?>
            </ul>
        </div>
    </div>
        <div id="sidebar">
            <div>
                <a href="<?PHP echo baseUrl('user') ?>">
                    <img id="avatar" src="<?PHP echo gravatarUrl( $this->session->get("user_Email"), $this->session->get("user_ValidEmail")) ?>" />
                </a>
                <h2><a href="<?PHP echo baseUrl('user') ?>"><?PHP echo $this->session->get("user_Username") ?></a></h2>
                <p><?php echo substr(timestampToDatetime($this->session->get("user_RegistrationDate")),0,9) ?></p>
                <a id="logout" href="<?PHP echo baseUrl('user/logout') ?>">Logout</a>
            </div>
            <div>
                <ul>
                    <li><a href="<?PHP echo baseUrl("video") ?>">My Videos</a></li>
                </ul>
            </div>
        </div>

    <div id="content">