<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		addLog();
	}else{
		showLogEdit();
	}

?>

<?php
	# this is a function block

	# pre: when the log is finished and is ready to write into xml
	# post: add log and status into xml
	function addLog(){}

	# pre: when log info is not yet done
	# post: show a page to prompt the user to leave a log
	function showLogEdit(){
		/*
		** NOTE: Maybe try using imgur to save photos?
		** imgur api: https://api.imgur.com/endpoints/image
		*/

		if(!isset($_GET["id"]) || !isset($_GET["status"]) || $_GET["id"] == "" ||
			$_GET["status"] == ""){
			showErrorMessage();
			die();
		}

		HTMLHeader("Add Log", "", "");


		HTMLFooter();
	}
?>