<?php
	include("../common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_POST["id"]) || !isset($_POST["c_name"]) || !isset($_POST["e_name"]) ||
		!isset($_POST["address"]) || !isset($_POST["genre"]) || !is_numeric($_POST["id"]) ||
		trim($_POST["c_name"]) == "" || trim($_POST["e_name"]) == "" || 
		trim($_POST["address"]) == "" || trim($_POST["genre"]) == ""){
		showErrorMessage();
		die();
	}

	$conn = connectToDB("dbInformation.txt");

	$id = $conn->quote($_POST["id"]);
	$c_name = $conn->quote($_POST["c_name"]);
	$e_name = $conn->quote($_POST["e_name"]);
	$address = $conn->quote($_POST["address"]);
	$genre = $conn->quote($_POST["genre"]);
	$phone = $conn->quote($_POST["phone"]);
	$email = $conn->quote($_POST["email"]);
	$custom = $conn->quote($_POST["customer_service"]);
	$fax = $conn->quote($_POST["fax"]);
	$abbre = $conn->quote($_POST["abbre"]);

	if($conn->query("UPDATE dbo.company_list
					 SET c_name = N$c_name, e_name = $e_name, [address] = N$address, genre = N$genre,
					 	phone = $phone, email = $email, custom_service = $custom, fax = $fax,
					 	abbre = N$abbre
					 WHERE [Id] = $id")){
		header("Location: ../companyDetail.php?id=".$_POST["id"]);
		die();
	}else{
		showErrorMessage();
		die();
	}
?>