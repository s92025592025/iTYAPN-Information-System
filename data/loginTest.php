<?php
	header("Content-Type: type/plain");
	session_start();
	
	$name = null;
	$pw = null;

	if(isset($_GET["check"])){ # if request to ckeck login status
		print(checkLoggedIn());
	}else if($_GET["name"] && $_GET["pw"]){# request for login
		$name = $_GET["name"];
		$pw = $_GET["pw"];
		print(checkByDB($name, $pw));
	}else{ #if nothing was requested
		print("Need to pass in account and pw");
	}

	# pre: when the user lod in the page
	# post: checks if the user has already logged in the page before
	function checkLoggedIn(){
		if(isset($_SESSION["user"])){
			return "TRUE";
		}

		return "FALSE";
	}


	# pre: When the user loaded in the login page or pushed the login button
	# post: request to DB to see when the login information is valid
	function checkByDB($name, $pw){
		try{
			$conn = new PDO ("sqlsrv:server = tcp:ityapn-database-server.database.windows.net,1433; Database = iTYAPNSystemDB", trim(file("dbInformation.txt")[0]), trim(file("dbInformation.txt")[1]));
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			header("Content-type: plain/text");
			print($e->getMessage());
		}

		$Qname = $conn->quote($name);
		$Qpw = $conn->quote($pw);

		$account = $conn->query("SELECT [user], [password]
								 FROM dbo.user_data
								 WHERE [user] = $Qname AND [password] = $Qpw");

		/* NOTE: It seems like Azure SQL server won't send back the row count information when 
		** querying for "SELECT". If we want to get how much rows we get when select, we need to
		** do two queries and the key word "COUNT". Reference: http://php.net/manual/en/pdostatement.rowcount.php
		** in Example #2
		*/

		foreach($account as $row){
			if($row["user"] == $name && $row["password"] == $pw){
				$_SESSION["user"] = $row["user"];
				return "TRUE";
			}
		}

		unset($_SESSION["user"]);
		return "FALSE";
	}
?>