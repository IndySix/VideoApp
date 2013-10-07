<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class adminUsers extends controller {
	function index(){
		$this->overview();
	}

	function overview(){
		//check if user is admin, when not show 404 page
		if(!$this->secure->isAdmin()) {
			$this->secure->noAdminRedirect();
			return;
		}
		//Begin function
		global $title; $title = "Users Overview";
		$data['search'] 	= '';
		$data['column'] 	= '';
		$data['paginate'] 	= '';
		$page 				= 1;
		$limitStart 		= 0;

		if(isset($_POST['search_submit']) && strlen($_POST['search']) > 0) {
			$data['search'] = $_POST['search'];
			$data['column'] = $_POST['column'];
		}

		if($this->uri->segment(5)) {
			$data['column'] = $this->uri->segment(3);
			$data['search'] = $this->uri->segment(4);
			$page  			= $this->uri->segment(5);
		}

		if($this->uri->segment(3) && !$this->uri->segment(4)) {
			$page  	= $this->uri->segment(3);
		}

		if(is_numeric($page ) && $page  > 0) {
			$limitStart = ($page -1) * 20;
		}

		if(!empty($data['search'])) {
			$bind[] = $data['search'].'%';
			$data['paginate'] 	= '/'.$data['column'].'/'.$data['search']; 
			if($data['column'] == 'Username') {
				$sql = "SELECT * FROM Users WHERE lower(username) like lower(?) LIMIT $limitStart, 20";
				$count = "SELECT count(*) FROM Users WHERE lower(username) like lower(?)";
			} else {
				$sql = "SELECT * FROM Users WHERE lower(email) like lower(?) LIMIT $limitStart, 20";
				$count = "SELECT count(*) FROM Users WHERE lower(email) like lower(?)";
			}
			$data['users'] = $this->db->query($sql, $bind);
			$itemsTotal = $this->db->query($count, $bind);
		} else {
			$data['users'] = $this->db->get('Users', 20, $limitStart);
			$itemsTotal = $this->db->numRows('Users');
		}

		//Create paginate
		$this->load->library('paginate');
		$this->paginate->setBaseUrl("adminUsers/overview".$data['paginate']);
		$this->paginate->setItemsPerPage(20);
		$this->paginate->setCurrentPage((int)$page);
		$this->paginate->setItemsTotal((int)$itemsTotal);

		$data['paginate'] = $this->paginate->getPaginate();
		$this->load->view('adminUsers', $data);
   }

   	function edit(){
	   	//check if user is admin, when not show 404 page
	   	if(!$this->secure->isAdmin()) {
			$this->secure->noAdminRedirect();
			return;
		}
		//Begin function
		$data['user'] 						= null;
		$data['error']['email']     		= '';
		$data['error']['password']  		= '';
		
		if($this->uri->segment(3)) {
			$this->load->model('users');
			$data['user'] = $this->users->getUserByUsername($this->uri->segment(3));

			if(isset($_POST['edit_submit']) && $data['user'] != null) {
				$this->load->model('validate');
				$data['user']['email'] = $_POST['email'];

				//validate email
				if(!$this->validate->email($_POST['email'], $data['user']['username']))
					$data['error']['email'] = $this->validate->getErrors();

				//validate password
				if(strlen($_POST['password']) > 0 && !$this->validate->password($_POST['password']))
					$data['error']['password'] = $this->validate->getErrors();

				//block user
				if(isset($_POST['block']))
					$data['user']['blocked'] = 1;
				else 
					$data['user']['blocked'] = 0;

				//update user
				if($data['error']['email'] == '' && $data['error']['password'] == ''){
					
					if(strlen($_POST['password']) > 0 )
						$data['user']['password'] = $this->secure->hashPassword($_POST['password']);

					$this->users->updateUser($data['user']['username'],$data['user']['email'],$data['user']['password']);
					
					if($data['user']['blocked'] == 1)
						$this->users->blockUser($data['user']['username']);
					else
						$this->users->unBlockUser($data['user']['username']);
				}
			}
		}

		if($data['user'] != null) {
			global $title; $title = "Edit user ".$data['user']['username'];
			$this->load->view('adminUsersEdit', $data);
		} else {
			$data['errorTitle'] = "User does not exist";
			$data['errorMessage'] = "The user \"".$this->uri->segment(3)."\" does noet exist!";
			$this->load->view('adminError', $data);
		}

   }

	function view(){
   		//check if user is admin, when not show 404 page
	   	if(!$this->secure->isAdmin()) {
			$this->secure->noAdminRedirect();
			return;
		}
		//Begin function
		$data['user'] = null;
		if($this->uri->segment(3)) {
			$this->load->model('users');
			$data['user'] = $this->users->getUserByUsername($this->uri->segment(3));
		}
		if($data['user'] != null) {
			global $title; $title = "User ".$data['user']['username'];
			$this->load->view('adminUsersView', $data);
		} else {
			$data['errorTitle'] = "User does not exist";
			$data['errorMessage'] = "The user \"".$this->uri->segment(3)."\" does noet exist!";
			$this->load->view('adminError', $data);
		}
   	}
}