<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class sendMail extends model{

	function register($email, $username, $validateToken){
		$this->load->library('mail');
		$message  = "Hello!\n\n";
		$message .= "Your Indy Six account has been created. Your username is $username.\n\n";
		$message .= "To verify your email address please click this link below.\n";
		$message .= baseUrl("user/validateEmail/".$validateToken)."\n\n";
		$message .= "If you lost your password! reset your password with this link (Only works if you validate your email).\n";
		$message .= baseUrl("user/passwordReset/".$email)."\n\n";
		$message .= "If you did not initiate this request, you may safely ignore this message.";
		
		$this->mail->addTo($email);
		$this->mail->setSubject('Account created');
		$this->mail->setMessage($message);
		$this->mail->send();
	}

	function emailValidate($email, $validateToken) {
		$this->load->library('mail');
		$message  = "Hello!\n\n";
		$message .= "This email was sent to validate your email address on your Indy Six account.\n\n";
		$message .= "To verify your email address please click this link below.\n";
		$message .= baseUrl("user/validateEmail/".$validateToken)."\n\n";
		$message .= "If you did not initiate this request, you may safely ignore this message.";

		$this->mail->addTo($email);
		$this->mail->setSubject('Email validation');
		$this->mail->setMessage($message);
		$this->mail->send();
	}
}