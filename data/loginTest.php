<?php
	header("Content-Type: type/plain");
	session_start();
	
	$name = null;
	$pw = null;

	if(isset($_GET["check"])){
		print(checkLoggedIn());
	}else if($_GET["name"] && $_GET["pw"]){
		$name = $_GET["name"];
		$pw = $_GET["pw"];
		print(checkByDB($name, $pw));
	}else{
		print("Need to pass in account and pw");
	}

	function checkLoggedIn(){
		if(isset($_SESSION["user"])){
			return "TRUE";
		}

		return "FALSE";
	}

	function checkByDB($name, $pw){
		try{
			$conn = new PDO ( "sqlsrv:server = tcp:ityapn-database-server.database.windows.net,1433; Database = iTYAPNSystemDB", trim(file("dbInformation.txt")[0]), trim(file("dbInformation.txt")[1]));
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			header("Content-type: plain/text");
			print($e->getMessage());
		}

		$name = $conn->quote($name);
		$pw = $conn->quote($pw);

		$account = $conn->query("SELECT user
								 FROM dbo.user_data
								 WHERE user LIKE $name AND password LIKE $pw");

		return $account[0];
		if(count($account)){
			#if account found
			$_SESSION["user"] = $_GET["name"];
			return "TRUE";
		}else{
			#if account not found
			unset($_SESSION["user"]);
			return "FALSE";
		}
	}
?>