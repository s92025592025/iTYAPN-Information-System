(function (){

	window.onload = function (){
		document.getElementById("search_btn").onclick = search;
		document.getElementById("more_contact").onchange = filter;
	};

	// pre: when the user clicked the search button
	// post: it will request to companies.php to figure out the company the user is searching for,
	// 		 and hide the details previously displayed
	function search(){
		document.getElementById("search_result").style.display = "block";
		document.getElementById("company_detail").style.display = "none";
		document.querySelector("#search_result .panel-body").innerHTML = "";

		var request = new XMLHttpRequest();
		request.open("GET", "data/companies.php?mode=companyLists&key="
				+ document.getElementById("search").value, true);

		request.onload = createTable;

		request.send();
	}

	// pre: when the search reqult came back
	// post: generate a table of the results we got
	function createTable(){
		var response = this.responseXML;

		if(response.querySelectorAll("company").length > 0){
			var table = document.createElement("table");

			// create table header
			var header_tr = document.createElement("tr");
			var id_th = document.createElement("th");
			var c_name_th = document.createElement("th");
			var e_name_th = document.createElement("th");
			var address_th = document.createElement("th");

			id_th.innerHTML = "#Id";
			c_name_th.innerHTML = "公司";
			e_name_th.innerHTML = "Company";
			address_th.innerHTML = "Address";

			header_tr.appendChild(id_th);
			header_tr.appendChild(c_name_th);
			header_tr.appendChild(e_name_th);
			header_tr.appendChild(address_th);

			table.appendChild(header_tr);

			// start addind the data we got
			var companies = response.querySelectorAll("company");
			for(var i = 0; i < companies.length; i++){
				var tr = document.createElement("tr");
				var id = document.createElement("td");
				var c_name = document.createElement("td");
				var e_name = document.createElement("td");
				var address = document.createElement("td");

				id.innerHTML = companies[i].getAttribute("id");
				c_name.innerHTML = companies[i].getAttribute("c_name");
				e_name.innerHTML = companies[i].getAttribute("e_name");
				address.innerHTML = companies[i].getAttribute("address");

				tr.appendChild(id);
				tr.appendChild(c_name);
				tr.appendChild(e_name);
				tr.appendChild(address);

				tr.id = companies[i].getAttribute("id");
				tr.onclick = showDetails;

				table.appendChild(tr);
			}

			document.querySelector("#search_result .panel-body").appendChild(table);
		}else{
			var h3 = document.createElement("h3");
			h3.style.color = "red";
			h3.innerHTML = "查無此公司 No Results";

			document.querySelector("#search_result .panel-body").appendChild(h3);
		}
	}

	// pre: when the user clicked the company they are looking for
	// post: show thw user the details of the company, and prompt to ask if there is 
	//		 other new information we need to know
	function showDetails() {
		document.getElementById("search_result").style.display = "none";
		document.getElementById("company_detail").style.display = "block";

		// make previous data blank
		document.querySelector("#company_detail > h4").innerHTML = "";
		document.getElementById("company_detail_info").innerHTML = "";
		document.querySelector("#company_map img").src = "";
		document.getElementById("contactee").value = "";
		document.getElementById("email").value = "";
		document.getElementById("phone").value = "";

		var request = new XMLHttpRequest();
		request.open("GET", "data/companies.php?mode=detailed&id=" + this.id, true);
		request.onload = details;
		request.send();
	}

	function details(){
		var response = this.responseXML;

		document.querySelector("#company_detail > h4").innerHTML = 
			response.querySelector("c_name").childNodes[0].nodeValue;
		document.querySelector("#company_detail > h4").id = 
			response.querySelector("id").childNodes[0].nodeValue;

		// elements to put in company_detail_info
		var ul = document.createElement("ul");
		var c_name = document.createElement("li");
		var e_name = document.createElement("li");
		var phone = document.createElement("li");
		var email = document.createElement("li");
		var address = document.createElement("li");

		c_name.innerHTML = "公司名稱: " + response.querySelector("c_name").childNodes[0].nodeValue;
		e_name.innerHTML = "Company: " + response.querySelector("e_name").childNodes[0].nodeValue;
		phone.innerHTML = "連絡電話: " + nodeChecker(response.querySelector("phone"));
		email.innerHTML = "電子信箱: " + nodeChecker(response.querySelector("email"));
		address.innerHTML = "公司地址: " + response.querySelector("address").childNodes[0].nodeValue;

		ul.appendChild(c_name);
		ul.appendChild(e_name);
		ul.appendChild(phone);
		ul.appendChild(email);
		ul.appendChild(address);

		document.getElementById("company_detail_info").appendChild(ul);

		document.querySelector("#company_map img").src = "http://maps.googleapis.com/maps/api/staticmap?center=" +
				response.querySelector("address").childNodes[0].nodeValue.replace(" ", "+") + 
				"&markers=color:red%7Clabel=@%7C" + 
				response.querySelector("address").childNodes[0].nodeValue.replace(" ", "+") + 
				"&zoom=13&size=300x250&maptype=roadmap&key=AIzaSyCTBKRUc0a4Sltk0XefQWIpXHnOhbLnXW0";


	}

	// pre: pass in a data to check if it is null
	// post: return "none" if it is null, return original data if not
	function nodeChecker(data){
		if(data.childNodes.length <= 0){
			return "none";
		}

		return data.childNodes[0].nodeValue;
	}

	// pre: if the company details are displayed
	// post: check if the user is mising any information before they send our the data
	function filter(){
		var input = document.querySelectorAll(".well .form-control");
		document.getElementById("phone").parentNode.parentNode.classList.remove("has-error");
		document.getElementById("send").parentNode.parentNode.action = "";
		document.getElementById("send").parentNode.parentNode.method = "";

		var post_input = document.querySelectorAll(".form-horizontal > form-group > input");
		for(var i = 0; i < post_input.length; i++ ){
			post_input[i].value = "";
		}

		if(this.checked){
			for(var i = 0; i < input.length; i++){
				input[i].value = "";
				input[i].disabled = true;
			}

			noExtraData();
		}else{
			/*
			**	if the user has not check the checkbox, that means there should be at least one column
			** 	entered, need to check that and mak sure the phone number is entered by a specific format,
			**	then let the user able the button to send out the data
			*/

			for(var i = 0; i < input.length; i++){
				input[i].value = "";
				input[i].disabled = false;
				input[i].onkeyup = varifyData;
			}
			document.getElementById("send").disabled = true;
		}
	}

	function varifyData(){
		var input = document.querySelectorAll(".well .form-control");
		document.getElementById("send").parentNode.parentNode.action = "";
		document.getElementById("send").parentNode.parentNode.method = "";
		var flag = false;
		for(var i = 0; i < input.length; i++){
			if(input[i].value.trim().length <= 0){
				document.getElementById("send").disabled = true;
			}else{
				flag = true;
			}
		}


		if(flag && document.getElementById("phone").value.trim() != ""){
			var reg = /^\(0([2-8]|37|49|89|82|826|836)\)[0-9]{5,8}$/
			flag = reg.test(document.getElementById("phone").value.trim()) &&
					document.getElementById("phone").value.trim().replace("(", "").replace(")", "").length == 10;

			if(!flag){
				document.getElementById("phone").parentNode.parentNode.classList.add("has-error");
				document.getElementById("phone_warning").innerHTML = 
					"電話輸入格式有誤。 此欄預設為輸入台灣市內電話所用，請依照 \"(區碼)無空格無符號電話號碼\" 的格式輸入，</br>" + 
					"且區碼及電話號碼加起來應有10碼。 區碼有兩碼以上也請還是照著得到的資料輸入，因為是存在的。 </br>" + 
					"如有太過特殊的電話號碼，請洽管理員並將此欄空白，稍後還有機會再修改";
			}else{
				document.getElementById("phone").parentNode.parentNode.classList.remove("has-error");
				document.getElementById("phone_warning").innerHTML = "";
			}
		}else{
			document.getElementById("phone").parentNode.parentNode.classList.remove("has-error");
				document.getElementById("phone_warning").innerHTML = "";
		}

		if(flag){
			extraData();
		}
	}

	// pre: when the user checked "having to ertra data"
	// post: put the necessary data in hidden inputs and set action direction
	function noExtraData(){
		document.getElementById("post_id").value = document.querySelector("#company_detail > h4").id;
		document.getElementById("post_contactee").value = "";
		document.getElementById("post_phone").value = "";
		document.getElementById("post_email").value = "";
		enableSend();
	}

	// pre: when the user unchecked the "have no extra data" and did put data in at least one input tab
	// post: put the necessary data in hidden inputs for further post action
	function extraData(){
		document.getElementById("post_id").value = document.querySelector("#company_detail > h4").id;
		document.getElementById("post_contactee").value = document.getElementById("contactee").value;
		document.getElementById("post_email").value = document.getElementById("email").value;
		document.getElementById("post_phone").value = document.getElementById("phone").value;
		enableSend();
	}

	// pre: when the needed information are in the hidden input tabs
	// post: set up the action and post method, then enable the user to send out data to server
	function enableSend(){
		document.getElementById("send").parentNode.parentNode.method = "post";
		document.getElementById("send").parentNode.parentNode.action = "data/newTicket.php";
		document.getElementById("send").disabled = false; // should be last step
	}

})();