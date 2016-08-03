(function (){
	"use strict";

	window.onload = function(){
		document.getElementById("submit").onclick = changePw;
	};

	// pre: when the user pressed the submit button
	// post: start changing password process
	function changePw(){
		if(document.getElementById("new_pw").value != 
			document.getElementById("re_enter_pw").value){
			// if new password canW't be entered the same twice

			// make the boxes red
			document.getElementById("new_pw").classList.add("error");
			document.getElementById("re_enter_pw").classList.add("error");
			// show error message
			document.getElementById("change_pw_warning").innerHTML = "Re-enter not the same";
			document.getElementById("old_pw_warning").innerHTML = "";
		}else if(document.getElementById("old_pw").value.length == 0 ||
					document.getElementById("new_pw").value.length == 0 ||
					document.getElementById("re_enter_pw").value.length == 0){
			// if any of the input is blank
			checkBlankInput("#change_pw");

			// show error message
			document.getElementById("change_pw_warning").innerHTML = "";
			document.getElementById("old_pw_warning").innerHTML = "The red box below shouldn't be blank";
		}else{
			document.getElementById("change_pw_warning").innerHTML = "";
			document.getElementById("old_pw_warning").innerHTML = "";

			AJAXCall(document.getElementById("old_pw").value, document.getElementById("new_pw").value);
		}
	}

	// pre: when ever the user is trying to submit something
	// post: check if the user has left anything blank when they submit
	function checkBlankInput(block){
		var inputs = document.querySelectorAll(block + " input");
		for(var i = 0; i < inputs.length; i++){
			if(inputs[i].value.length == 0){
				inputs[i].classList.add("error");
			}else{
				inputs[i].classList.remove("error");
			}
		}
	}

	// 
	function AJAXCall(oldPw, newPw){
		var request = new XMLHttpRequest();

		request.onload = function(){
			var response = request.responseText;

			if(response == "TRUE"){
				alert("Password Change Successful");
				window.location = account.php;
			}else{
				document.getElementById("old_pw_warning").innerHTML = response;
			}
		};

		request.open("POST", "data/changePw.php", false);
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		request.send("old=" + oldPw + "&new=" + newPw);
	}

})();