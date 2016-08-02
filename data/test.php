<?php
	$name = $_GET["name"];
	$pw = $_GET["pw"];

	try{
			$conn = new PDO ( "sqlsrv:server = tcp:ityapn-database-server.database.windows.net,1433; Database = iTYAPNSystemDB", trim(file("dbInformation.txt")[0]), trim(file("dbInformation.txt")[1]));
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			header("Content-type: plain/text");
			print($e->getMessage());
		}

		$name = $conn->quote($name);
		$pw = $conn->quote($pw);

		$account = $conn->query("SELECT [user]
								 FROM dbo.user_data
								 WHERE [user] LIKE $name AND [password] LIKE $pw");

		header("Content-type: plain/text");
		print_r($account);

?>