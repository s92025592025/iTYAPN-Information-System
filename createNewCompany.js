(function (){
	window.onload = function (){
		var inputs = document.querySelectorAll("input");
		for(var i = 0; i < inputs.length; i++){
			inputs[i].onkeyup = checkMustFill;
		}

		/*var phones = document.querySelectorAll(".phone-num");
		for(var i = 0; i < phones.length; i++){
			phones[i].onkeyup = phoneVadilate;
		}

		var emails = document.querySelectorAll(".email");
		for(var i = 0; i < emails.length; i++){
			emails[i].onkeyup = emailVadilate;
		}*/
	};

	// pre: whenever the user entered something in input
	// post: check if the must-fill are filled up, and whether the data entered were in the right format
	function checkMustFill(){
		var must_fills = document.querySelectorAll(".must-fill input");
		var flag = false;
		for(var i = 0; i < must_fills.length; i++){
			if(must_fills[i].value.length <= 0){
				flag = true;
			}
		}

		if(!phoneVadilate() || !emailVadilate()){
			flag = true;
		}

		document.querySelector("button").disabled = flag;
	}

	// pre: when the user has put data in inputs involved with phone numbers
	// post: warn the user to follow the format, and ask them to contact admin if they need any other
	//		 exception
	function phoneVadilate(){
		var phones = document.querySelectorAll(".phone-num");
		var reg = /^\(0([2-8]|37|49|89|82|826|836)\)[0-9]{5,8}$/
		var flag = true;
		for(var i = 0; i < phones.length; i++){
			if(phones[i].value.trim().length > 0 && (!reg.test(phones[i].value.trim()) ||
				phones[i].value.replace("(", "").replace(")", "").trim().length != 10)){
				// show error message
				document.querySelectorAll(".phone-warning")[i].innerHTML = 
					"電話輸入格式有誤。 此欄預設為輸入台灣市內電話所用，請依照 \"(區碼)無空格無符號電話號碼\" 的格式輸入，</br>" + 
					"且區碼及電話號碼加起來應有10碼。 區碼有兩碼以上也請還是照著得到的資料輸入，因為是存在的。 </br>" + 
					"如有太過特殊的電話號碼，請洽管理員並將此欄空白，稍後還有機會再修改";
				phones[i].parentNode.parentNode.classList.add("has-error");

				flag = false;
			}else{
				//remove error message
				document.querySelectorAll(".phone-warning")[i].innerHTML = "";
				phones[i].parentNode.parentNode.classList.remove("has-error");
			}

		}


		return flag;

	}

	// pre: when the user has enter data involved with email address
	// post: if the user enter the right email format or "online form", no warnings will be displayed.
	//		 if not, show warnings and tell them if they are trying to enter "online form"
	function emailVadilate(){
		var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		var emails = document.querySelectorAll(".email");
		var flag = true;
		for(var i = 0; i < emails.length; i++){
			if(emails[i].values > 0 && !reg.test(emails[i].value.trim())){
				flag = false;

				document.querySelectorAll(".email-warning")[i].innerHTML = "請輸入正確的電子郵件格式。";
				emails[i].parentNode.parentNode.classList.add("has-error");

			}else{
				document.querySelectorAll(".email-warning")[i].innerHTML = "";
				emails[i].parentNode.parentNode.classList.remove("has-error");
			}
		}

		return flag;
	}
})();