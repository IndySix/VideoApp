function checkUsername(username){
	var node = document.getElementById("usernameError");
	node.innerHTML="";
	if (username.length == 0) {
			node.innerHTML="<p>Username can't be blank.</p>";
			return false;
		}
	ajaxObject = new ajax('<?PHP echo baseUrl("user/exists") ?>/'+username);
	ajax.onReady = function() {
		if(ajaxObject.getText() == 1) {
			node.innerHTML="<p>Username is already taken.</p>";
		}
	}
	ajaxObject.send();
	return true;
}

function checkEmail(email){
	var node = document.getElementById("emailError");
	node.innerHTML="";
	
	if("" != email){
		if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
			node.innerHTML="<p>Email is invalid.</p>";
			return false;
		} else {
			ajaxObject = new ajax('<?PHP echo baseUrl("user/emailused") ?>/');
			ajaxObject.isPost = true;
			ajaxObject.addParam("email",email);
			ajax.onReady = function() {
				if(ajaxObject.getText() == 1) {
					node.innerHTML="<p>Email is already used.</p>";
				}
			}
			ajaxObject.send();
		}
	}
	return true;
}

function checkPassword(password){
	var node = document.getElementById("passwordError");
	node.innerHTML="";

	if (password.length == 0) {
			node.innerHTML="<p>Password can't be blank.</p>";
			return false;
		} else if(password.length < 6) {
		node.innerHTML="<p>Password is too short (minimum is 6 characters).</p>";
			return false;
		}
		return true;
}