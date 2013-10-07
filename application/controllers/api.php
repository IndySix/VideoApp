<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class api extends controller {
	function index(){
   	
   	}

   	function uploadMovie(){
   		if(isset($_POST['username'])){
   			$username 	= $_POST['username'];
   			$title		= $_POST['title'];

   			$this->db->where("username", $username);
   			$result = $this->db->get("Users");

   			if(!empty($result) && !empty($title) ){
   				$this->load->library("upload");
   				//$this->upload->setValidExtensions("ogg");
   				$this->upload->loadFile($_FILES["movie"]);
   				
   				if($this->upload->uploadFile() ) {
   					$videofilePath = $this->upload->getFilePath();
   					$screencapPath = realpath(__SITE_PATH . '/data/uploads/screencap/').'/'.$this->upload->getFileName().'.png';
   					$cmd = "ffmpeg -i $videofilePath -ss 0 -vframes 5 $screencapPath";
   					exec($cmd);

   					$insert['username'] = $username;
   					$insert['fileName'] = $this->upload->getFileName();
   					$insert['title']	= $title;
   					$insert['saved']	= 1;
   					$this->db->insert("Videos", $insert);
   					echo "1";
   					return;
   				}
   			}
   		}
   		echo "0";
   	}
}