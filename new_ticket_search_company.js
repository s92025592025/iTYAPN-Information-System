(function (){

	window.onload = function (){
		document.getElementById("search_btn").onclick = search;
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

})();