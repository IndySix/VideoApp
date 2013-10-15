<?PHP
class JSend{
	#Status of message (success, fail, error)
	public $status;
	public $data;
	public $code;
	public $message;

	function __construct(){
		$this->status = "success";
	}

	function getJson(){
		$jClass = new stdClass;
		$jClass->status = $this->status;

		if($this->status != "error"){
			$jClass->data = $this->data;
		} else {
			$jClass->code = $this->code;
			$jClass->message = $this->message;
		}
		return json_encode($jClass);
	}
}

function baseUrl($uri = "") {
	// $x = explode('/', $_SERVER['SCRIPT_NAME']);
	// unset($x[count($x)-1]);
	// $reqPath = implode('/', $x);
	// $pageURL = 'http';
 // 	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 // 	$pageURL .= "://";
 // 	if ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") {
 //  		$pageURL .= $_SERVER["SERVER_NAME"].$reqPath.'/'.$uri;
 // 	} else {
 //  		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$reqPath.'/'.$uri;
 // 	}
 //	return $pageURL;
	return "http://localhost/webApp/".$uri;
}

#Check if request has a valid apikey.
if( !(isset($_GET['apikey']) && $_GET['apikey'] == "indysix1337") ) {
	$jsend = new JSend();
	$jsend->status = "error";
	$jsend->code 	= 1;
	$jsend->message = "No valid api key is submitted!";
	echo $jsend->getJson();
	die();
}


#Returns movies
if(isset($_GET['movies'])) {
	$jsend = new JSend();
	$movies = array();
	foreach (scandir(__DIR__.'/movies/') as $file) {
		$x = explode('.', $file);
		if ($x[count($x)-1] == "ogg") {
			unset( $x[ count($x)-1 ] );
			$name = implode('.', $x);
			$movie['name'] 		= $name;
			$movie['oggUrl']	= baseUrl('movies/'.$name.'.ogg');
			$movie['mp4Url']	= baseUrl('movies/'.$name.'.mp4');
			$movie['screencap']	= baseUrl('movies/screencap/'.$name.'.png');
			$movies[] = $movie;
		}	
	}
	$jsend->data = $movies;
	echo $jsend->getJson();
}

//Upload movie to profile
if(isset($_POST['addmovie'])) {
	$jsend 		= new JSend(); 
	$movie 		= $_POST['addmovie']; 
	$username 	= $_POST['username']; 

	//check if movie exists
	$movieOgg = __DIR__."/movies/".$movie.".ogg";
	if(!is_file($movieOgg)){
		$jsend->status  = "fail";
		$jsend->data 	= "Movie does not exists! ".$movieOgg;
		echo $jsend->getJson();
		die();
	}

	//check if username is valid
	$result = file_get_contents('https://server.martens.me/indysix/user/exists/'.$username);
	if($result == "0"){
		$jsend->status  = "fail";
		$jsend->data 	= "Username does not exists!";
		echo $jsend->getJson();
		die();
	}

	//upload movie to website
	$target_url = 'https://server.martens.me/indysix/api/uploadMovie';

        /* curl will accept an array here too.
         * Many examples I found showed a url-encoded string instead.
         * Take note that the 'key' in the array will be the key that shows up in the
         * $_FILES array of the accept script. and the at sign '@' is required before the
         * file name.
         */
	$post = array('username' => $username,'title' => $movie,'movie'=>'@'.$movieOgg);
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$result = curl_exec ($ch);
	curl_close ($ch);

	if($result == FALSE) {
    	$jsend->status  = "fail";
		$jsend->data 	= "Error with uploading file!";
		echo $jsend->getJson();
		die();
	}
	
	//Delete movie and screencap
	unlink($movieOgg);
	$screencap = __DIR__."/movies/screencap/".$movie.".png";
	unlink($screencap);
	echo $jsend->getJson();
}
