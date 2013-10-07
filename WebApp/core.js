var videos = {};
function addVideo(movieName, movieScreenCap){
	var parent 		= document.getElementById("videos");
	var firstChild 	= parent.firstChild;

	//Create new videoDiv
	var videoDiv = document.createElement("div");
	videoDiv.id 		= movieName;
	videoDiv.className 	= "video";
	
	//Create videoImgContainer
	var videoImgContainer = document.createElement("div");
	videoImgContainer.className = "videoImgContainer"
	videoDiv.appendChild(videoImgContainer);

	//create videoPlaylink
	var videoPlaylink = document.createElement("a");
	videoPlaylink.href 		= "javascript:void(0)";
	videoPlaylink.onclick 	= function(){ playVideo(movieName) };
	videoImgContainer.appendChild(videoPlaylink);

	//create videoImg
	var videoImg 	= document.createElement("img");
	videoImg.className 	= "videoImg";
	videoImg.src 		= movieScreenCap;
	videoPlaylink.appendChild(videoImg); 	

	//create videoPlayImg
	var videoPlayButton	= document.createElement("img");
	videoPlayButton.className 	= "playButton";
	videoPlayButton.src 		= "img/play_button.png";
	videoPlaylink.appendChild(videoPlayButton); 

	//create addVideoButton
	var addVideoButton = document.createElement("a");
	addVideoButton.href 		= "javascript:void(0)";
	addVideoButton.onclick 		= function(){ openAddVideo(movieName)};
	addVideoButton.className	= "addButton";
	addVideoButton.innerHTML	= "Add Video";
	videoDiv.appendChild(addVideoButton);
	
	parent.insertBefore(videoDiv, firstChild);
	
	videoDiv.style.opacity = 0;
	window.getComputedStyle(videoDiv).opacity;
	videoDiv.style.opacity = 1;
}

function addButtonVideo(div){
	setTimeout(function() {deleteVideoAction(div)},1000);
}

/* Timer function for getting the resent video's */
var gettingVideos = true;
function getvideos(){
	if(!gettingVideos)
		return;
	gettingVideos = false;

	ajaxObject = new ajax('http://localhost/projecten/indysix_gui/api.php');
	ajaxObject.isPost = false;
	ajaxObject.addParam("movies","");
	ajaxObject.addParam("apikey","indysix1337");	
	ajax.onReady = function() {
		try	{
	   		var jsend = JSON.parse( ajaxObject.getText() );
		} catch(e) {
	   		console.log('getvideos: invalid json');
	   		return;
		}
		
  		if(jsend.status == "error") {
  			alert(jsend.message);
  			return;
  		}
  		if(jsend.status == "fail"){
  			alert("Getting videos failed!");
  			return;
  		}

  		//add new videos
  		var movieNames = {};
  		for (var i = 0; i < jsend.data.length; i++) {
  			var movie = jsend.data[i]; 

  			if(!videos.hasOwnProperty(movie.name)){
  				addVideo(movie.name, movie.screencap);
  				videos[movie.name] = movie;
  				console.log("Add video:");
  				console.log(movie);
  			}
  			movieNames[movie.name] = null;
  		}

  		//remove videos
  		for (var key in videos) {
  			var movie = videos[key];

  			if(!movieNames.hasOwnProperty( movie.name )){
  				deleteVideoAction(movie.name);
  				delete videos[key];
  				console.log("Delete video:");
  				console.log(movie);
  			}
  		}
  		gettingVideos = true;		
	}
	ajaxObject.send();
}
//Runs getVideos when script is laoded
getvideos();
//Timer for getting new videos every 5 seconds
setInterval( function(){ getvideos() },5000);

function whichTransitionEvent(){
	var t;
	var el = document.createElement('fakeelement');
	var transitions = {
			'transition':'transitionend',
			'OTransition':'oTransitionEnd',
			'MozTransition':'transitionend',
			'WebkitTransition':'webkitTransitionEnd'
	}

	for(t in transitions){
		if( el.style[t] !== undefined ){
    		return transitions[t];
		}
	}
}

function deleteVideoAction(div){
	var node = document.getElementById(div);
	node.style.opacity = 0;
	var transitionEnd = whichTransitionEvent();
	node.addEventListener(transitionEnd, function() {deleteVideo(node)}, false);
}

function deleteVideo(node){
	node.parentNode.removeChild(node);
}

function playVideo(movie) {
	var videoMp4 = document.getElementById("videoMp4");
	videoMp4.src = videos[movie].mp4Url;
	var videoOgg = document.getElementById("videoOgg");
	videoOgg.src = videos[movie].oggUrl;
	var node = document.getElementById("videoPlayBack");
	node.style.display = "block";
	var video = document.getElementById("videoPlayer");
	video.load();
	video.play();
	return false;
}

function closeVideo(){
	var node = document.getElementById("videoPlayBack");
	node.style.display = "none";
	return false;
}
/* keyboard*/
var openVideoName 	= "";
function openAddVideo(videoName){
	var input = document.getElementById("addVideoInput");
	input.value = "";
	openVideoName	= videoName;
	var node = document.getElementById("addVideoBack");
	node.style.display = "block";
	return false;
}

function closeAddVideo(){
	var node = document.getElementById("addVideoBack");
	node.style.display = "none";
	return false;
}

function pressKey(key) {
	var input = document.getElementById("addVideoInput");
	input.value += key;
	return false;
}

function pressBackspace(){
	var input = document.getElementById("addVideoInput");
	input.value = input.value.substring(0, input.value.length - 1);
	return false;
}

function pressClear(){
	var input = document.getElementById("addVideoInput");
	input.value = "";
	return false;
}

function pressEnter(){
	var input = document.getElementById("addVideoInput");

	ajaxObject = new ajax('http://localhost/projecten/indysix_gui/api.php?apikey=indysix1337');
	ajaxObject.isPost = true;
	ajaxObject.addParam("addmovie",openVideoName);
	ajaxObject.addParam("username",input.value);
	ajax.onReady = function() {
		try	{
	   		var jsend = JSON.parse( ajaxObject.getText() );
		} catch(e) {
	   		console.log('pressEnter: invalid json');
	   		return;
		}

		if(jsend.status == "error") {
  			alert(jsend.message);
  			return;
  		}
  		if(jsend.status == "fail"){
  			alert(jsend.data);
  			return;
  		}
  		closeAddVideo();
  		deleteVideoAction(openVideoName);
	}
	ajaxObject.send();
}