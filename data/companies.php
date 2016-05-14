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
				# code...
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
function companyLists(){
	$xml = new DOMDocument();
	$output = new DOMDocument();
	$xml -> load("companyData.xml");
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
?>