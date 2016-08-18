(function (){

	window.onload = function (){
		document.getElementById("search_btn").onclick = search;
	};

	// pre: when the user clicked the search button
	// post: it will request to companies.php to figure out the company the user is searching for
	function search(){
		document.getElementById("search_result").style.display = "block";
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

})();