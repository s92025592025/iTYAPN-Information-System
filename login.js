(function(){
	window.onload = function(){
		document.getElementById("login_btn").onclick = verify;
	};

	function verify(){
		if(document.getElementById("account_input").value == ""){
			document.getElementById("account_label").innerHTML += 
				"<span>*Please enter your account name</span>";
		}else{
			document.getElementById("account_label").innerHTML = "Account: ";
		}

		if(document.getElementById("pw_input").value == ""){
			document.getElementById("pw_label").innerHTML += 
			 "<span>*Please enter your account password</span>";
		}else{
			document.getElementById("pw_label").innerHTML = "Password: ";
		}

		if (document.getElementById("account_input").value &&
			document.getElementById("pw_input").value) {
			var request = new XMLHttpRequest();
			request.onload = function(){
				var response = this.responseText;
				if(response == "TRUE"){
					window.location = "www.google.com";
				}else{
					alert("Wrong account or password");
				}
			};

			request.open("GET", "data/loginTest.php", false);
			request.send();
		}
	}
})();