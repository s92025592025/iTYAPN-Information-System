<?php
	include("../common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_POST["id"]) || !is_numeric($_POST["id"])){
		showErrorMessage();
		die();
	}

	$conn = connectToDB("dbInformation.txt");

	$id = $conn->quote($_POST["id"]);
	
	if($conn->query("DELETE FROM dbo.ticket WHERE [id] = $id")){
		if(unlink("tickets/".$_POST["id"].".xml")){
			header("Location: ../home.php");
			die();
		}else{
			showErrorMessage();
			die();
		}
	}else{
		showErrorMessage();
		die();
	}
?>