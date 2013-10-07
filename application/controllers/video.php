<?php if (!defined('__SITE_PATH')) exit('No direct script access allowed');

class video extends controller {
   
   function index(){
   		$this->db->where("username", $this->session->get("user_Username"));
         $this->db->orderBy('id');
   		$data['videos'] = $this->db->get("Videos");

    	$this->load->view("video", $data);
   }

   function watch(){
   		$this->db->where('id', $this->uri->segment(3) );
   		$data['video'] = $this->db->get('Videos');

   		if(!empty($data['video'])) {
   			$data['video'] = $data['video'][0];
   			global $title_page;
   			$title_page = $data['video']['title'];
   			$this->load->view("videoWatch", $data);
   		} else {
   			$data['titleMessage'] 	= "Video does not exists";
   			$data['error'] 			= "Sorry, this video does not exist";
   			$this->load->view("message", $data);
   		}
   }

   function upload(){
   		$data['title'] 			= "";
   		$data['username']		= "";
   		$data['title_error'] 	= "";
   		$data['file_error']		= "";
   		$data['message']		= "";
   		if(isset($_POST['submit'])){
   			$data['title'] 		= $_POST['title'];
   			$data['username']	= $_POST['username'];

   			$this->db->where("username", $data['username']);
   			$result = $this->db->get("Users");

   			if(empty($data['title']))
   				$data['title_error'] = "Cannot be empty!";

   			if(!empty($result) && !empty($data['title']) ){
   				$this->load->library("upload");
   				$this->upload->setValidExtensions("mp4");
   				$this->upload->loadFile($_FILES["file"]);

   				if($this->upload->uploadFile() ) {
   					//Create screen cap
   					$videofilePath = $this->upload->getFilePath();
   					$screencapPath = realpath(__SITE_PATH . '/data/uploads/screencap/').'/'.$this->upload->getFileName().'.png';
   					$cmd = "ffmpeg -i $videofilePath -ss 0 -vframes 5 $screencapPath";
   					exec($cmd);
   					
   					//$cmd = "ffmpeg2theora -o ".$videofilePath.".ogg ".$videofilePath." --videoquality 9 --audioquality 6";

   					$insert['username'] = $data['username'];
   					$insert['fileName'] = $this->upload->getFileName();
   					$insert['title']	= $data['title'];
   					$insert['saved']	= 1;
   					$this->db->insert("Videos", $insert);
   					
   					$data['title'] 		= "";
   					$data['message'] 	= "video is uploaded!";
   				} else {
   					foreach ($this->upload->getErrors() as $error)
   						$data['file_error'] .= $error."</br>";	
   				}
   			}
   		} else {
   			$data['username'] = $this->session->get("user_Username");
   		}

   		$this->load->view("videoUpload", $data);
   }
}