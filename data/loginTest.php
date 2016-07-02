<?php
	checkByDB("Daniel", "a");

	session_start();
	header("Content-Type: type/plain");
	$name = null;
	$pw = null;

	if(isset($_GET["check"])){
		print(checkLoggedIn());
	}else if($_GET["name"] && $_GET["pw"]){
		$name = $_GET["name"];
		$pw = $_GET["pw"];
		print(varify($name, $pw));
	}else{
		print("Need to pass in account and pw");
	}


	/*function varify($name, $pw){
		$xml = new DOMDocument();
		$xml->load("loginInformation.xml");
		$accounts = $xml->getElementsByTagName("accountInfo");
		foreach($accounts as $account){
			if ($account->getElementsByTagName("name")->item(0)->nodeValue == $name) {
				if($account->getElementsByTagName("password")->item(0)->nodeValue == $pw){
					$_SESSION["user"] = $name;
					return "TRUE";
				}
				unset($_SESSION["user"]);
				return "FALSE";
			}
		}

		unset($_SESSION["user"]);
		return "FALSE";
	}*/

	function checkLoggedIn(){
		if(isset($_SESSION["user"])){
			return "TRUE";
		}

		return "FALSE";
	}

	function checkByDB($name, $pw){
		try{
			$conn = new PDO ( "sqlsrv:server = tcp:ityapn-database-server.database.windows.net,1433; Database = iTYAPNSystemDB", file("dbInformation.txt")[0], file("dbInformation.txt")[1]);
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			header("Content-type: plain/text");
			print($e->getMessage());
		}
	}
?>