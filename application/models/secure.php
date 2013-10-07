<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class secure extends model{

	public $blocked = false;

	/* password hasher */
	function hashPassword($pass, $salt = null) {
		if($salt == null)
	  		$salt = substr(md5(time()),0,8);
	  	$encrypted = '';
	  	for($i = 0; $i<10000; $i++) {
	    	$encrypted = hash("sha512",$salt . $pass . $encrypted . $pass . $salt);
	  	}
	  	return $salt.$encrypted;
	}

	/* Check password */
	function checkPassword($password, $hash){
	  	if($hash == $this->hashPassword($password, substr($hash,0,8)))
	    	return true;
	  	else 
	    	return false;
	}

	function isLoggedin(){
		$this->login->updateUserSession();
		if($this->session->get("user_Username") != null && !$this->session->get("user_Blocked"))
			return true;
		$this->login->logout();
		return false;
	}

	function isAdmin(){
		if($this->isLoggedin()) {
			$this->login->updateUserSession();
			if($this->session->get("user_Level") == 1337)
				return true;
		}
		return false;
	}

	function noAdminRedirect(){
		include __APPLICATION_PATH.'errors/error404.php';
	}

	function isBlocked(){
		return $this->blocked;
	}

	function authToken(){
		$this->session->set("user_authToken", randomString(32));
		return $this->session->get("user_authToken");
	}
}        