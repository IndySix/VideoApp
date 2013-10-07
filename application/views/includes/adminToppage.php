<!doctype html>
<head>
    <meta charset="utf-8">
    <title>Admin panel - <?PHP global $title; echo $title ?></title>
    <link rel="stylesheet" href="<?PHP echo baseUrl('css/adminstyle.css') ?>">
</head>
<body>
    <div id="container">
        <div id="header">
            <a href="<?PHP echo baseUrl('admin') ?>">Home</a>
            <a href="#" ><?PHP echo $this->session->get('user_Username') ?></a>
            <a href="#">links</a>
        </div>
        <div id="menu">
            <a href="<?PHP echo baseUrl('admin') ?>" class="<?PHP echo __CONTROLLER_NAME == 'admin' ? 'current' : '' ?>">Overzicht</a>
            <a href="#">Configuratie</a>
            <hr />
            <a href="<?PHP echo baseUrl('adminUsers') ?>" class="<?PHP echo __CONTROLLER_NAME == 'adminUsers' ? 'current' : '' ?>">Users</a>
            <a href="#">Configs</a>
            <a href="#">Saved-configs</a>
            <a href="#">Transactions</a>
            <hr />
            <a href="#">Weergave</a>
            <a href="#">plugins</a>
        </div>
        <div id="content">