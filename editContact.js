(function (){
	"use strict";

	window.onload = function(){
		var input = document.querySelectorAll("input");
		for(var i = 0; i < input.length; i++){
			input[i].onkeyup = phoneVadilate;
		}
	};

	// pre: when any of the input is entered something
	function phoneVadilate(){
		var reg = /^\(0([2-9]|37|49|89|82|826|836)\)[0-9]{5,8}$/;
		var phoneNum = document.querySelector("#phone").value;
		if(phoneNum.trim() == "" || 
			(reg.test(phoneNum) && phoneNum.replace("(", "").replace(")", "").length == 10)){
			document.querySelector("button").disabled = false;
			document.querySelector(".help-block").innerHTML = "";
		}else{
			document.querySelector("button").disabled = true;
			document.querySelector(".help-block").innerHTML = "電話輸入格式有誤。 此欄預設為輸入台灣市內電話所用，請依照 \"(區碼)無空格無符號電話號碼\" 的格式輸入，</br>" + 
					"且區碼及電話號碼加起來應有10碼。 區碼有兩碼以上也請還是照著得到的資料輸入，因為是存在的。 </br>" + 
					"如有太過特殊的電話號碼，請洽管理員並將此欄空白，稍後還有機會再修改";
		}
	}
})();