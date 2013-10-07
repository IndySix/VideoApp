<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class home extends controller {
   function index(){
        if($this->secure->isLoggedin())
        	$this->loggedin();
        else
        	$this->notLoggedin();
   }

   private function loggedin(){
   		$this->load->view('homeLoggedin');
   }

   private function notLoggedin(){
   		$this->load->view('homeNotLoggedin');
   }
}        