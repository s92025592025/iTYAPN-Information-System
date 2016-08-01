<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("Account", "login.css");

	HTMLFooter();
?>