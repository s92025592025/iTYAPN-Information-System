<?php
	/*
	** Modes: company lists, company by year list, detailed list, owner list, user list
	*/

	if(isset($_GET["mode"])){
		switch($_GET["mode"]){
			case "companyLists":
				companyLists();
				break;
			case "yearLists":
				yearLists($_GET["year"]);
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

function companyLists(){
	$xml = loadData();
	$output = new DOMDocument();
	$names = $output->createElement("names");
	$companies = $xml->getElementsByTagName("company");
	foreach ($companies as $company) {
		$name = $output->createElement("name");
		$chineseName = $output->createElement("chineseName");
		$engName = $output->createElement("engName");
		$chineseName->appendChild($output->createTextNode($company->getElementsByTagName("chineseName")->item(0)->nodeValue));
		$engName->appendChild($output->createTextNode($company->getElementsByTagName("engName")->item(0)->nodeValue));
		$name->appendChild($chineseName);
		$name->appendChild($engName);
		$names->appendChild($name);
	}
	$output->appendChild($names);

	header("Content-type: text/xml");
	print($output->saveXML());
}

function yearLists($year){
	if($year == null){
		header("HTTP/1.1 400 Please specify a year");
		die("Please specify a year");
	}

	$xml = loadData();
	$output = new DOMDocument();
	$data = $output->createElement("data");
	$data->setAttribute("year", $year); # make the returned year
	getSameYear($year);
	$output->appendChild($data);
}


# pre: should have companyData.xml file
# post: will return the entire companyData.xml file by a xml structure
function loadData(){
	$xml = new DOMDocument();
	$xml->load("companyData.xml");

	return $xml;
}

# pre: should enter a valid year in western style to year
# post: returns a list on year element what occurs in the year passed in
function getSameYear($year){
	$xml = loadData();
	$years = array();
	foreach($xml->getElementsByTagName("year") as $oneyear){
		if($oneyear->getAttribute("year") == $year){
			$years[] = $oneyear;
		}
	}

	return $years;
}

?>