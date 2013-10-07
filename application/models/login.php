<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class login extends model{

	function __construct() {
		$value = $this->session->get('user_Username');
		if(empty($value))
			$this->cookieLogin();
	}

	function cookieLogin(){ 
		if(isset($_COOKIE['user_ses'])) {
			$data = explode(':', $_COOKIE['user_ses']);
			if(count($data) == 2) {
				//get user salt
				$this->db->where('username', $data[0]);
				$user = $this->db->get('Users');

				if(empty($user))
					return;
				
				$data[1] = $this->secure->hashPassword($data[1], substr($user[0]['password'],0,8));
				
				//Check if usersession exist
				$sql = "SELECT * FROM LoginSession WHERE username = ? AND token = ?";
				$check = $this->db->query($sql, $data);
				if(isset($check[0]['username'])) {
					//delete old session
					$this->db->where('token', $data[1]);
					$this->db->delete('LoginSession');
					//login user 
					$this->saveUserToSession($user[0]); 
					//create new session
					$this->saveLoginSession($user[0]['username'], randomString(32), substr($user[0]['password'],0,8));
				}
			}
		}
	 }

	 function saveLoginSession($username, $token, $salt){
	 	$expire = time() + (60*60*24*365);

        /* Get domain */
        $domain = $_SERVER['SERVER_NAME'];
   
        /* Create cookie */
        setcookie('user_ses', $username.':'.$token, $expire, "/", $domain, true, true);

        $data['username'] = $username;
        $data['token'] = $this->secure->hashPassword($token, $salt);
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $this->db->insert('LoginSession', $data);
	 }

	function user($username, $password){
		$this->db->where("username", $username);
		$data = $this->db->get("Users");
		if(!empty($data) && $this->secure->checkPassword($password, $data[0]['password'])){
			if($data[0]['blocked']) {
				$this->secure->blocked = true;
				return false;
			}
			$this->saveUserToSession($data[0]);
			$this->saveLoginSession($username, randomString(32), substr($data[0]['password'],0,8));
			return true;
		}
		return false;
	}

	function logout(){
		if(isset($_COOKIE['user_ses']) && $this->session->get('user_Salt') != null) {
			$data = explode(':', $_COOKIE['user_ses']);
			if(count($data) == 2) {
				$hash = $this->secure->hashPassword($data[1], $this->session->get('user_Salt'));
				$this->db->where('token', $hash);
				$this->db->delete('LoginSession');
			}
		}
		$_COOKIE['user_ses'] = null;
		setcookie('user_ses', '', time()-86400, "/", $_SERVER['SERVER_NAME'], true, true);
		$this->saveUserToSession(null, true);
	}

	function updateUserSession(){
		if($this->session->get("user_Username") != null && time() - $this->session->get("user_SessionSetTime") > 60*5){
			$this->db->where("username", $this->session->get("user_Username"));
			$data = $this->db->get("Users");
			$this->saveUserToSession($data[0]);
			return true;
		}
		return false;
	}

	function saveUserToSession($user, $delete = false) { 
		$this->session->set("user_Username", $delete ? null : $user['username']);
		$this->session->set("user_Salt", $delete ? null : substr($user['password'],0,8));
		$this->session->set("user_Level", $delete ? null : $user['level']);
		$this->session->set("user_Email", $delete ? null : $user['email']);
		$this->session->set("user_ValidEmail", $delete ? null : $user['validEmail']);
		$this->session->set("user_Blocked", $delete ? null : $user['blocked']);
		$this->session->set("user_RegistrationDate", $delete ? null : datetimeToTimestamp($user['registrationDate']));
		$this->session->set("user_SessionSetTime", $delete ? null : time());
	}

}