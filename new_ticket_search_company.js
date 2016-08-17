(function (){

	window.onload = function (){
		document.getElementById("search_btn").onclick = search;
	};

	// pre: when the user clicked the search button
	// post: it will request to companies.php to figure out the company the user is searching for
	function search(){
		document.getElementById("search_result").style.display = "block";
		document.querySelector(".panel-body").innerHTML = "";

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

		/*
		**	STILL NEED TO TAKE ACCOUNT FOR IF NO RESULTS WERE THERE
		*/

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
	}

})();