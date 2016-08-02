(function (){
	"use strict";

	window.onload = function(){
		document.getElementById("submit").onclick = changePw;
	};

	function changePw(){
		if(document.getElementById("new_pw").textValue != 
			document.getElementById("re_enter_pw").textValue){
			// if new password can't be entered the same twice
			alert("Re-enter not the same");
		}else if(document.getElementById("old_pw").textValue.length == 0 ||
					document.getElementById("new_pw").textValue.length = 0 ||
					document.getElementById("re_enter_pw").textValue == 0){}else{
			var request = new XMLHttpRequest();
		}
	}

})();