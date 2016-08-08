<?php

	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"])){
		header("Location: home.php");
	}else{
		HTMLHeader("Ticket #".$_GET["id"], "ticket.css", "");

		HTMLFooter();
	}

?>