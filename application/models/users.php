<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class users extends model{

	function addUser($username, $email, $password) {
		$data['username'] = $username;
		$data['email'] = strtolower($email);
		$data['password'] = $password;
		$this->db->reset();
		$this->db->insert('Users', $data);
	}

	function getUserByUsername($username){
		$this->db->reset();
		$this->db->where("username", $username);
		$data = $this->db->get("Users");
		if(!empty($data))
			return $data[0];
		return null;
	}

	function getUserByEmail($email) {
		$sql = "SELECT * FROM Users WHERE email = ? AND validEmail = 1";
		$bind[] = strtolower($email);
		$data = $this->db->query($sql, $bind);
		if(!empty($data))
			return $data[0];
		return null;
	} 

	function updateUser($username, $email, $password){
		$this->db->reset();
		$this->db->where("username", $username);
		$data['email'] = $email;
		$data['password'] = $password;
		$this->db->update('Users', $data);
	}

	function deleteUser($username) {
		$this->db->where("username", $username);
		$this->db->delete("Users");
	}

	function blockUser($username) {
		$this->db->where("username", $username);
		$data['blocked'] = 1;
		$this->db->update('Users', $data);
	}

	function unBlockUser($username) {
		$this->db->where("username", $username);
		$data['blocked'] = 0;
		$this->db->update('Users', $data);
	}

	function setValidEmail($username, $boolean){
		$this->db->where("username", $username);
		$data['validEmail'] = $boolean;
		$this->db->update('Users', $data);
	}
}