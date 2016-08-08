<?php

	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"])){
		header("Location: home.php");
	}else{
		HTMLHeader();

		HTMLFooter();
	}

?>