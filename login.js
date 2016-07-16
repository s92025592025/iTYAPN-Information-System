(function(){
	"use strict";

	window.onload = function(){
		document.getElementById("login_btn").onclick = verify;
		document.getElementById("account_input").onkeypress = submit;
		document.getElementById("pw_input").onkeypress = submit;
	};

	function verify(){
		if(document.getElementById("account_input").value == ""){
			document.getElementById("account_label").innerHTML = 
				"Account: <span>*Please enter your account name</span>";
		}else{
			document.getElementById("account_label").innerHTML = "Account: ";
		}

		if(document.getElementById("pw_input").value == ""){
			document.getElementById("pw_label").innerHTML = 
			 "Password: <span>*Please enter your account password</span>";
		}else{
			document.getElementById("pw_label").innerHTML = "Password: ";
		}

		if (document.getElementById("account_input").value &&
			document.getElementById("pw_input").value) {
			var request = new XMLHttpRequest();
			request.onload = function(){
				var response = this.responseText;
				console.log(response);
				if(response == "TRUE"){
					window.location = "http://www.google.com";
				}else{
					alert("Wrong account or password");
				}
			};

			request.open("GET", "data/loginTest.php?name=" + 
				document.getElementById("account_input").value + "&pw=" + 
				document.getElementById("pw_input").value, false);
			request.send();
		}
	}

	function submit(e){
		if(e.keycode == 13 || e.which == 13){
			verify();
		}
	}

	function checkLoggedIn(){
		var request = new XMLHttpRequest();
		request.onload = function(){
			console.log(this.responseText);
			if(this.responseText == "TRUE"){
				window.location = "http://www.w3schools.com/js/js_window_location.asp";
			}
		};

		request.open("GET", "data/loginTest.php?check=ivs630s5573v56dj9a", false);
		request.send();
	}

})();