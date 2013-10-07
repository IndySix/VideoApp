<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class admin extends controller {
	function index(){
		if(!$this->secure->isAdmin()) {
			$this->secure->noAdminRedirect();
			return;
		}
		global $title; $title = "Overview";
		
		$this->load->view('admin');
   }
}