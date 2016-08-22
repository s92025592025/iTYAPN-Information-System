(function (){
	window.onload = function (){
		var inputs = document.querySelectorAll("input");
		for(var i = 0; i < inputs.length; i++){
			inputs[i].onkeyup = checkMustFill;
		}

		var phones = document.querySelectorAll(".phone-num");
		for(var i = 0; i < phones.length; i++){
			phones[i].onkeyup = phoneVadilate;
		}

		var emails = document.querySelectorAll(".email");
		for(var i = 0; i < emails.length; i++){
			emails[i].onkeyup = emailVadilate;
		}
	};

	// pre: whenever the user entered something inn input
	// post: check if the must-fill are filled up. enable the submit button if so,
	//		 and vise versa
	function checkMustFill(){
		var must_fills = document.querySelectorAll(".must-fill input");
		var flag = false;
		for(var i = 0; i < must_fills.length; i++){
			if(must_fills[i].value.length <= 0){
				flag = true;
			}
		}

		document.querySelector("button").disabled = flag;
	}

	// pre: when the user tried to put data in inputs involved with phone numbers
	// post: warn the user to follow the format, and ask them to contact admin if they need any other
	//		 exception
	function phoneVadilate(){
		checkMustFill();
	}

	// pre: when the user tried to enter data involved with email address
	// post: if the user enter the right email format or "online form", no warnings will be displayed.
	//		 if not, show warnings and tell them if they are trying to enter "online form"
	function emailVadilate(){
		checkMustFill();
	}
})();