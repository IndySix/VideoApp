<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class user extends controller {
   function index(){
      $this->view();
   }

    /**
     * view
     *
     * Shows an user profile page, when no username was given and user is logged in
     * it wil show his user page.
     */
   function view(){
      $data['ownProfile']  = false;
      $data['user']        = null;
      $username            = '';
      
      if($this->uri->segment(3))
         $username = $this->uri->segment(3);
      elseif($this->secure->isLoggedin())
         $username = $this->session->get('user_Username');

      if(!empty($username) && strtolower($username) == strtolower($this->session->get('user_Username'))) {
         $data['ownProfile'] = true;

         //get all user sessions
         $this->db->reset();
         $this->db->where('username', $username);
         $this->db->orderBy('loginDate');
         $data['loginSessions'] = $this->db->get('LoginSession');

         //Get currunt session
         $hash = '';
         if(isset($_COOKIE['user_ses'])) {
            $session = explode(':', $_COOKIE['user_ses']);
            if(count($session) == 2) {
               $hash = $this->secure->hashPassword($session[1], $this->session->get('user_Salt'));
            }
         }

         //get all sessions
         foreach ($data['loginSessions'] as $key => $session) {
            if($session['token'] == $hash)
               $data['loginSessions'][$key]['isSession'] = true;
             else 
               $data['loginSessions'][$key]['isSession'] = false;
         }
      }
    
      if(!empty($username)) {
         $this->load->model('users');
         $data['user'] = $this->users->getUserByUsername($username);
      }

      if($data['user'] == null) {
         $data['titleMessage'] = "User does not exist";
         $data['error'] = "The specified user you are trying to view does not exist!";
         $this->load->view('message', $data);
      } else {
         $this->load->view('userView', $data);
      }
   }

   /**
     * Login
     *
     * Shows loginpage or on valid loggin redirect to home page.
     */
   function login() {
      $data['username'] = '';
      $data['loginError'] = '';
		if(isset($_POST['login_submit'])) {
         $data['username'] = $_POST['username'];

			if($_POST['authtoken'] != $this->session->get("user_authToken"))
				$data['loginError'] .= "<p>Login session has expired.</p>";
			//check if login is correct
         if(!$this->login->user($_POST['username'], $_POST['password'])) {
            //check if account is blocked
            if($this->secure->isBlocked())
               $data['loginError'] .= "<p>Account is blocked.</p>";
            else 
              $data['loginError'] .= "<p>Incorrect username or password.</p>"; 
         } 
		}
      
      $this->secure->authToken();

      if($this->secure->isLoggedin()) {
         redirect('home');
      } else {
         $this->load->view('userLoginForm', $data);
      }
   }

      /**
     * Logout
     *
     * Logout the user.
     */
   function logout() {
   	if($this->secure->isLoggedin())
   		$this->login->logout();
      redirect('home');
   }

   /**
     * Signup
     *
     * Register an account when valid username and password are given.
     * email is optional, when email is valid it wil send email to user.
     */
   function signup() {
      $this->load->model('users');
      $data['username']       = '';
      $data['email']          = '';
      $data['usernameError']  = '';
      $data['emailError']     = '';
      $data['passwordError']  = '';
      $data['tokenError']     = '';

      if(isset($_POST['signup_submit'])){
         $this->load->model('validate');
         $data['username'] = $_POST['signup_username'];
         $data['email']    = $_POST['signup_email'];

         if($_POST['authtoken'] != $this->session->get("user_authToken"))
            $data['tokenError'] .= "<p>Sign up session has expired.</p>";

         if(!$this->validate->username($_POST['signup_username']))
            $data['usernameError'] = $this->validate->getErrors();

         if(!$this->validate->email($_POST['signup_email']))
            $data['emailError'] = $this->validate->getErrors();

         if(!$this->validate->password($_POST['signup_password']))
            $data['passwordError'] = $this->validate->getErrors();

         if($data['usernameError'] == '' && $data['emailError'] == '' && $data['passwordError'] == '' && $data['tokenError'] == ''){
            $hash = $this->secure->hashPassword($_POST['signup_password']);
            $this->users->addUser($_POST['signup_username'], strtolower($_POST['signup_email']), $hash);
            $this->login->user($_POST['signup_username'], $_POST['signup_password']);

            //mail user when email set
            if(!empty($_POST['signup_email'])) {
               $this->load->model('sendMail');
               $this->load->model('tokens');
               $token =  $this->tokens->createMail($_POST['signup_username']);
               $this->sendMail->register(strtolower($_POST['signup_email']), $_POST['signup_username'], $token);
            }

            //redirect to home
            redirect('home');
         }
      }
   	$this->load->view('userSignupForm', $data);
   }

   /**
     * exists (?Maybe make ajax api class/page?)
     *
     * Checks if given user exists, used for ajax.
     */
   function exists(){
      $this->load->model('users');
      if($this->users->getUserByUsername($this->uri->segment(3, '')) == null)
         echo 0;
      else
         echo 1;
   }

   /**
     * emailused (?Maybe make ajax api class/page?)
     *
     * Checks if given email is used, used for ajax.
     */
   function emailused(){
      if(isset($_POST['email'])) {
         $this->load->model('users');
         $email = $_POST['email'];
         if(strlen($email) > 0 && $this->users->getUserByEmail($email) != null)
            echo 1;
      } else {
         echo 0;
      }
   }

   /**
     * Edit
     *
     * Edit user settings for email and password.
     */
   function edit(){
      if(!$this->secure->isLoggedin()) {
         $data['titleMessage'] = "Not logged in";
         $data['message'] = "You must be logged in if you want to modify your account information!";
         $this->load->view('message', $data);
         return;
      }

      $this->load->model('users');
      $this->load->model('validate');
      $data['user'] = $this->users->getUserByUsername($this->session->get('user_Username'));
      $data['message']                 = '';
      $data['error']['email']          = '';
      $data['error']['password']       = '';
      $data['error']['oldPassword']    = '';

      if(isset($_POST['edit_submit'])) {
         $email         = $_POST['email'];
         $oldPassword   = $_POST['oldPassword'];
         $password      = $_POST['password'];
         $passwordCheck = $_POST['passwordCheck'];

         if($_POST['authtoken'] != $this->session->get("user_authToken")){
            $data['message'] = 'Edit profile session has expired.';
            $this->load->view('userEdit', $data);
            return;
         }

         //check email
         if(!$this->validate->email($email, $data['user']['username']))
               $data['error']['email'] = $this->validate->getErrors();

         //check password
         if(strlen($password.$passwordCheck.$oldPassword) > 0 ){
            if($this->secure->checkPassword($oldPassword, $data['user']['password'])){
               if(!$this->validate->password($password))
                  $data['error']['password'] = $this->validate->getErrors();
               elseif($password != $passwordCheck)
                  $data['error']['password'] = "Passwords doesn't match!";
            } else {
               $data['error']['oldPassword'] = "Old password is not correct!";
            }
         }

         //update user
         if(empty($data['error']['email']) && empty($data['error']['password']) && empty($data['error']['oldPassword'])) {
            if(strlen($password) > 0){
               $this->login->logout();
               $data['user']['password'] = $this->secure->hashPassword($password);
               $data['message'] = "<p>Password changed.</p>";
            }

            $this->users->updateUser($data['user']['username'], $email, $data['user']['password']);
            
            if($email != $data['user']['email']) {
               $this->users->setValidEmail($data['user']['username'], false);
               $data['user']['email'] = $email;
               
               if(empty($email)) {
                  $data['message'] .= "<p>Warning! you have deleted your email, you can't recover your account when email is not set!</p>";
               } else {
                  $data['message'] .= "<p>There is an email send with a link to validate the new email address <i>$email</i>.</p>";
                  $this->load->model('tokens');
                  $this->load->model('sendMail');
                  $this->tokens->deleteEmail($this->session->get("user_Username"));
                  $token =  $this->tokens->createMail($this->session->get("user_Username"));
                  $this->sendMail->emailValidate($email, $token);
               }
            }
            //save changes to session
            $this->login->saveUserToSession($data['user']);
         }
         $data['user']['email'] = $email;
      }
      $this->secure->authToken();
      $this->load->view('userEdit', $data);
   }

   /**
     * emailValidation
     *
     * Sends an email to validate email address.
     * Only when email is set, not already valid and this function is not used for more then
     * three times in fifteen minutes by the same user.
     */
   function emailValidation(){
      if(!$this->secure->isLoggedin()){
         $data['titleMessage'] = "Not logged in";
         $data['error'] = "You must be logged in if you want to validate your email address!";
         $this->load->view('message', $data);
         return;
      }
      
      $this->load->model('tokens');
      $data['titleMessage'] = "Email validation";

      if($this->session->get("user_Email") == '') {
         //Check if email is set
         $data['error'] = "There is no email set, <a href='".baseUrl("user/edit")."'>edit</a> your email setting!";
         $this->load->view('message', $data);
         return;
      } elseif($this->session->get("user_ValidEmail") == 1) {
         //Check if email is already valid
         $data['message'] = "Your email address is already validated.";
         $this->load->view('message', $data);
         return;
      }

      //check if email not already validaded on other account
      $this->load->model('users');
      if($this->users->getUserByEmail($this->session->get("user_Username")) != null){
         $data['message'] = "This email address is already validated on another account.";
         $this->load->view('message', $data);
         return;
      }

      //max 3 validations per 15 minutes
      if($this->tokens->countMail($this->session->get("user_Username")) > 3) {
         $data['warning'] = "Mail not sent, because You have sent already three validation mails within fifteen minutes.";
         $this->load->view('message', $data);
         return;
      }

      if($this->uri->segment(3, '#') == $this->session->get("user_authToken")){
         //Create email token en send email
         $this->load->model('sendMail');
         $token =  $this->tokens->createMail($this->session->get("user_Username"));
         $this->sendMail->emailValidate($this->session->get("user_Email"), $token);

         $data['message'] = "A mail is sent to ".$this->session->get("user_Email")." to validate your email address.";
         $this->load->view('message', $data);
      } else {
         //error token expirede
         $data['error'] = "Validation session has expired!";
         $this->load->view('message', $data);
      }
      $this->secure->authToken();
   }

   /**
     * validateEmail
     *
     * When token is correct change email to valid for user.
     */
   function validateEmail(){
      $this->load->model('tokens');
      $data['titleMessage'] = "Email validation";

      if($this->tokens->validMail($this->uri->segment(3, '#'))){
         $this->load->model('users');
         
         $token = $this->tokens->getToken($this->uri->segment(3));
         $user = $this->users->getUserByUsername($token['username']);
         
         if($this->users->getUserByEmail($user['email']) == null) {
            $this->tokens->deleteEmail($token['username']);
            $this->users->setValidEmail($token['username'], true);
            $data['message'] = "Your email is validated!";
         } else {
            $data['message'] = "This email address is already validated on another account.";
         }
      } else {
         $data['error'] = "Token does not exist or has expired!";
      }
      $this->load->view('message', $data);
   }

   /**
     * passwordReset
     *
     * Send an reset password email to given user.
     * Can only be used 3 times per hour per IP.
     */
   function passwordReset(){

   }

   /**
     * resetPassword
     *
     * When token is correct user can change password.
     */
   function resetPassword(){

   }
}        