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
		!vadilatePhone($_POST["phone"]) || !vadilatePhone($_POST["fax"]) || !vadilateEmail($_POST["email"])){
		showErrorPage();
		die();
	}

	$conn = connectToDB("dbInformation.txt");

	// remember to add code to warn the user if the company is already exsited

	$c_name = $conn->quote($_POST["c_name"]);
	$e_name = $conn->quote($_POST["e_name"]);
	$address = $conn->quote($_POST["address"]);
	$email = nullMaker($conn, $_POST["email"]);
	$phone = nullMaker($conn, $_POST["phone"]);
	$customer = nullMaker($conn, $_POST["customer_service"]);
	$fax = nullMaker($conn, $_POST["fax"]);
	$abbre = nullMaker($conn, $_POST["abbre"]);

	$conn->query("INSERT INTO dbo.company_list (c_name, e_name, [address], email, phone,
					custom_service, fax, abbre)
		   		  OUTPUT INSERTED.id
				  VALUES (N$c_name, $e_name, N$address, $email, $phone, $customer, $fax,
					$abbre)");


	showSuccessMessage();
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
		return strlen(trim($phone)) == 0 || (preg_match("/^\(0([2-8]|37|49|89|82|826|836)\)[0-9]{5,8}$/", trim($phone)) && strlen(str_replace(")", "", str_replace("(", "", trim($phone)))) == 10);
	}

	// pre: when the user send a company data in
	// post: return true when email address is null or matches the pattern
	function vadilateEmail($email){
		return strlen(trim($email)) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// pre: when the data has any chance to be null
	// post: return null in '' to fit in sql query if null, quote it if there is data
	function nullMaker($conn, $data){
		if($data == null || $data == ""){
			return 'NULL';
		}

		return "N".$conn->quote(trim($data));
	}


	// pre: when the company data is successfully put in the data base
	// post: show successful message and prompt the user to create a new ticket again or 
	//		 return to home
	function showSuccessMessage(){
		HTMLHeader("Success!", "/login.css", "");

		?>
			<div class="container">
				<h1 style="text-align: center;">公司新增成功</h1>
				<p style="text-align: center;">公司新增成功，請可回到首頁或繼續以新增Ticket</p>
				<div class="row">
					<div class="col-sm-4"></div>
					<div class="col-sm-2">
						<a href="../home.php" style="margin-right: 3px;"><button class="btn btn-primary">Home</button></a>
					</div>
					<div class="col-sm-2">
						<a href="../new_ticket_search_company.php" style="margin-left: 3px;"><button class="btn btn-primary">Create Ticket</button></a>
					</div>
					<div class="col-sm-4"></div>
				</div>
			</div>
					
				</div>
		<?php

		HTMLFooter();
	}

?>