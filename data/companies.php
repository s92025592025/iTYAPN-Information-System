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
				detailed($_GET["id"]);
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

	function detailed($id){
		$conn = connectToDB("dbInformation.txt");
		$id = $conn->quote($id);
		$details = $conn->query("SELECT TOP(1) [id], c_name, e_name, [address], [email], [phone]
								FROM dbo.company_list
								WHERE [id] LIKE $id");

		$xml = new DOMDocument();
		$root = $xml->createElement("root");
		$info = $xml->createElement("info");
		foreach($details as $detail){
			$company_id = $xml->createElement("id");
			$c_name = $xml->createElement("c_name");
			$e_name = $xml->createElement("e_name");
			$address = $xml->createElement("address");
			$email = $xml->createElement("email");
			$phone = $xml->createElement("phone");

			$company_id->appendChild($xml->createTextNode($detail["id"]));
			$c_name->appendChild($xml->createTextNode($detail["c_name"]));
			$e_name->appendChild($xml->createTextNode($detail["e_name"]));
			$address->appendChild($xml->createTextNode($detail["address"]));
			$email->appendChild($xml->createTextNode($detail["email"]));
			$phone->appendChild($xml->createTextNode($detail["phone"]));

			$info->appendChild($company_id);
			$info->appendChild($c_name);
			$info->appendChild($e_name);
			$info->appendChild($address);
			$info->appendChild($email);
			$info->appendChild($phone);
		}

		$root->appendChild($info);
		$xml->appendChild($root);

		header("Content-type: text/xml");
		print $xml->saveXML();
	}

?>