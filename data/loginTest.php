<?php
	header("Content-Type: type/plain");
	$name = null;
	$pw = null;

	if($_GET["name"] && $_GET["pw"]){
		$name = $_GET["name"];
		$pw = $_GET["pw"];
		print(varify($name, $pw))
	}else{
		print("Need to pass in account and pw");
	}


	function varify($name, $pw){
		$xml = new DOMDocument();
		$xml->load("loginInformation.xml");
		$accounts = $xml->getElementsByTagName("accountInfo");
		foreach($accounts as $account){
			if($account.childNodes(0).value == $name){
				if($account.childNodes(1).value == $pw){
					return "TRUE";
				}

				return "FALSE";
			}
		}

		return "FALSE";
	}
?>