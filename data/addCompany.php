<?php

	/*
	** Thinking about using this file to add the new company to sql, and then send a post request
	** to newTicket.php to let it handle the rest. Check the reference below for sending post request
	** from php.
	** http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
	*/

	include("../common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_POST["c_name"]) || !isset($_POST["e_name"]) || !isset($_POST["address"]) ||
		$_POST["c_name"] == "" || $_POST["e_name"] == "" || $_POST["address"] == "" || 
		showErrorPage();
		die();
	}

	$conn = connectToDB("dbInformation.txt");

	$c_name = $conn->quote($_POST["c_name"]);
	$e_name = $conn->quote($_POST["e_name"]);
	$address = $conn->quote($_POST["address"]);

?>

<?php
	# this is a function block

	// pre: when must-fill data is not filled or data not vadilated
	// post: show an error page to tell the user to return to last page
	function showErrorPage(){
		HTMLHeader("Something's wrong", "../login.css", "");

		?>
			<h1 style="text-align: center; color: red;">OOPS?! Something is wrong. Try to return to last page and re-enter the data.</h1>
		<?php

		HTMLFooter();
	}

	// pre: when the user send a company data in
	// post: return true when phone number is null or matches the pattern
	function vadilatePhone($phone){
		return str_len(trim($phone)) == 0 || (preg_match("/^\(0([2-8]|37|49|89|82|826|836)\)[0-9]{5,8}$/", trim($phone)) && str_len(trim($phone)) == 10);
	}

	// pre: when the user send a company data in
	// post: return true when email address is null or matches the pattern
	function vadilateEmail($email){
		return str_len(trim($email)) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL);
	}

?>