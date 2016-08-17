<?php
	include("../common.php");

	/*
	** Modes: company lists, company by year list, detailed list, owner list, user list
	*/

	if(isset($_GET["mode"])){
		switch($_GET["mode"]){
			case "companyLists":
				companyLists($_GET["key"]);
				break;
			case "yearLists":
				#code...
				break;
			case "detailed":
				# code...
				break;
			case "ownerList":
				# code...
				break;
			case "userList":
				# code...
				break;
			default:
				header("HTTP/1.1 400 ILLEGAL REQUEST");
				die("Please request for a valid mode");
		}
	}else{
		header("HTTP/1.1 406 Mode request");
		die("Please request with a valid mode");
	}

?>

<?php
	# php block for functions


	# pre: when the user input a company's chinese or english name
	# post: returns a list of companys that may matches the user wants
	function companyLists($key){
		$xml = new DOMDocument();
		$companies = $xml->createElement("companies");

		if(strlen(trim($key)) > 0){
			$conn = connectToDB("dbInformation.txt");
			$key = $conn->quote("%$key%");
			$names = $conn->query("SELECT c_name, e_name, [id], [address], [email], 
										[phone]
									FROM dbo.company_list
									WHERE c_name LIKE N$key OR e_name LIKE N$key OR abbre LIKE N$key");

			foreach($names as $name){
				$company = $xml->createElement("company");
				$company->setAttribute("id", $name["id"]);
				$company->setAttribute("c_name", $name["c_name"]);
				$company->setAttribute("e_name", $name["e_name"]);
				$company->setAttribute("address", $name["address"]);
				$company->setAttribute("phone", $name["phone"]);
				$company->setAttribute("email", $name["email"]);

				$companies->appendChild($company);
			}
		}

		$xml->appendChild($companies);

		header("Content-type: text/xml");
		print $xml->saveXML();
	}

	/*
	#need to switch to SQL
	function yearLists($year){
		if($year == null){
			header("HTTP/1.1 400 Please specify a year");
			die("Please specify a year");
		}

		$xml = loadData();
		$output = new DOMDocument();
		$sameYear = getSameYear($year);
		$data = $output->createElement("data");
		$data->setAttribute("year", $year); # make the returned year
		foreach($sameYear as $eachNode){
			$company = $output->createElement("company");

			# add name to company
			$names = $output->createElement("names");
				$chineseName = $output->createElement("chineseName");
				$engName = $output->createElement("engName");
			$chineseName->appendChild($output->createTextNode($eachNode->parentNode->getElementsByTagName("chineseName")->item(0)->nodeValue));
			$engName->appendChild($output->createTextNode($eachNode->parentNode->getElementsByTagName("engName")->item(0)->nodeValue));
			$names->appendChild($chineseName);
			$names->appendChild($engName);
			$company->appendChild($names);

			# add position information of that year

			# put the company when the data is collect
			$data->appendChild($company);
		}
		#put everything back
		$output->appendChild($data);

		#output
		header("Content-type: text/xml");
		print($output->saveXML());
	}*/

?>