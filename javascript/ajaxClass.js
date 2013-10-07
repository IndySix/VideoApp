function ajax(urlp) {
	var xmlhttp = null;
	var params = '';
	var url = urlp;
	
	this.onReady = function(){}
	this.isPost = false;

	this.getText = function(){
		if (xmlhttp != null) 
			return xmlhttp.responseText;
	}

	this.getXML = function() {
		if (xmlhttp != null) 
			return xmlhttp.responseXML;
	}

	this.addParam = function(name, value) {
		if(name == "" || value == "")
			return false;

		if (params == "") {
			params = name+"="+encodeURIComponent(value);
		} else {
			params = params+"&"+name+"="+encodeURIComponent(value);
		}
		return true;
    }

    this.send = function(){
		if (window.XMLHttpRequest){
  			xmlhttp = new XMLHttpRequest();
  		} else {
  			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  		}

  		xmlhttp.onreadystatechange = function(){
  			if (xmlhttp.readyState==4 && xmlhttp.status==200)
  				ajax.onReady();
  		}
 		
  		if (this.isPost) {
  			post();
  		} else {
  			get();
  		}
    }

    var post = function(){
    	xmlhttp.open("POST", url,true);
    	if (params == "") {
			xmlhttp.send();
    	} else {
    		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    		xmlhttp.send(params);
    	}
    }

    var get = function(){
    	if (params == "") {
    		xmlhttp.open("GET",url,true);
    	} else {
    		xmlhttp.open("GET",url+"?"+params,true);
    	}
    	xmlhttp.send();
    }
}