<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class validate extends model{
	private $errors = "";

	function getErrors(){
		return $this->errors;
	} 

	function username($username) {
		if(!isset(controller::get_instance()->users))
			$this->load->model('users');
		$this->errors = "";

		if(strlen($username) == 0)
			$this->errors .= "<p>Username can't be blank.</p>";
		//check if username is alfabethic or/and numuric
		elseif (!preg_match("/^[A-Za-z0-9]+(?:[-][A-Za-z0-9]+)*$/", $username))
			$this->errors .= "<p>Username must contain alphanumeric characters or dashes and cannot begin with a dash.</p>";
		//check if username is already taken
		elseif($this->users->getUserByUsername($username) != null)
			$this->errors .= "<p>Username is already taken.</p>";

		return empty($this->errors); 
	}

	function email($email, $usernameEdit = null){
		if(!isset(controller::get_instance()->users))
			$this->load->model('users');
		$this->errors = "";
		
		//When email is filled in check email
		if(strlen($email) > 0) {
			$user = $this->users->getUserByEmail($email);
			//check if email is valid
			$regexEmail = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
			if (!preg_match($regexEmail, $email))
				$this->errors .= "<p>Email is invalid.</p>";
			//check if email is already taken
			elseif($user != null && ($usernameEdit == null || $user['username'] != $usernameEdit))
				$this->errors .= "<p>Email is already used.</p>";
		}
		return empty($this->errors);
	}

	function password($password){
		$this->errors = "";
		
		if(strlen($password) == 0)
            $this->errors .= "<p>Password can't be blank.</p>";
            //check if password is longer then 6 characters
         elseif(strlen($password) < 6)
            $this->errors .= "<p>Password is too short (minimum is 6 characters).</p>";

        return empty($this->errors);
	}
}