<?php
	header("Content-Type: type/plain");
	$name = null;
	$pw = null;

	if($_GET["name"] && $_GET["pw"]){
		$name = $_GET["name"];
		$pw = $_GET["pw"];
		print(varify($name, $pw));
	}else{
		print("Need to pass in account and pw");
	}


	function varify($name, $pw){
		$xml = new DOMDocument();
		$xml->load("loginInformation.xml");
		$accounts = $xml->getElementsByTagName("accountInfo");
		foreach($accounts as $account){
			if ($account->getElementsByTagName("name")->item(0)->nodeValue == $name) {
				if($account->getElementsByTagName("password")->item(0)->nodeValue == $pw){
					setcookie("name", $name);
					return "TRUE";
				}
				return "FALSE";
			}
		}

		return "FALSE";
	}
?>