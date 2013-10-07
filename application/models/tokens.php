<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class tokens extends model{

	function __construct() {
		$this->deleteExpired();
	}

	private function isValid($timestamp) {
		$time = time() - (60*15);
		if($timestamp > $time)
			return true;
		else 
			return false;
	}

	function createMail($username){
		$data['token'] 		= randomString(32);
		$data['username'] 	= $username;
		$data['type'] 		= "mail";
		$this->db->insert('Tokens', $data);
		return $data['token'];
	}

	function validMail($token) {
		$sql = "SELECT * FROM Tokens WHERE token = ? AND type = 'mail'";
		$bind[] = $token;
		$data = $this->db->query($sql, $bind);
		if(!empty($data) && $this->isValid(datetimeToTimestamp($data[0]['createDate'])))
			return true;
		return false;
	}

	function countMail($username){
		$sql = "SELECT count(*) FROM Tokens WHERE username = '$username' AND type = 'mail'";
		$data = $this->db->query($sql);
		return $data[0][0];
	}

	function createPassword($username){
		$data['token'] 	= randomString(32);
		$data['username'] 	= $username;
		$data['type'] 	= "password";
		$this->db->insert('Tokens', $data);
		return $data['token'];
	}

	function validPassword($token) {
		$sql = "SELECT * FROM Tokens WHERE token = ? AND type = 'password'";
		$bind[] = $token;
		$data = $this->db->query($sql, $bind);
		if(!empty($data) && $this->isValid(datetimeToTimestamp($data[0]['createDate'])))
			return true;
		return false;
	}

	function delete($token){
		$this->db->where('token', $token);
		$this->db->delete('Tokens');
	}

	private function deleteExpired(){
		$sql = "DELETE FROM Tokens WHERE createDate < now()- interval 15 MINUTE";
		$this->db->query($sql);
	}

	function deleteEmail($username) {
		$sql = "DELETE FROM Tokens WHERE username = '$username' AND type = 'mail'";
		$this->db->query($sql);
	}

	function getToken($token) {
		$this->db->reset();
		$this->db->where('token', $token);
		$data = $this->db->get("Tokens");
		if(!empty($data))
			return $data[0];
		return null;
	}
}