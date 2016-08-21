<?php
	include("../common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_POST["id"]) || $_POST["id"] == ""){
		showWarning();
		die();
	}

	$ticket = new DOMDocument();
	$year = $ticket->createElement("year"); # root element
	$positions = $ticket->createElement("positions");
	$logs = $ticket->createElement("logs");

	// set up attribute for year
	$year->setAttribute("year", Date("Y"));
	$year->setAttribute("owner", $_SESSION["user"]);

	// create new message in log
	$log = $ticket->createElement("log");
	$log->setAttribute("time", microtime(true));
	$log->setAttribute("author", $_SESSION["user"]);
	$log->setAttribute("status", "Open");
	$text = $ticket->createElement("text", "New ticket created");
	$files = $ticket->createElement("files");

	// add all element to the dom
	// add positions
	$year->appendChild($positions);
	//add logs
	$log->appendChild($text);
	$log->appendChild($files);
	$logs->appendChild($log);
	$year->appendChild($logs);

	// add everything back to dom
	$ticket->appendChild($year);

	// save xml to file
	$filename = newTicketFileName();
	if($ticket->save("tickets/$filename.xml")){
		// if ticket created successfully
		header("Location: ../ticket.php?id=$filename");
		die();
	}else{
		showWarning();
		die();
	}

?>


<?php
	# this is a function block
	
	# pre: when the user deleted id on purpurse
	# post: tell them to restart the new ticket
	function showWarning(){
		HTMLHeader("OOPs?!", "../login.css", "");
		?>
			<h1 style="text-align: center; color: red;">OOPS?! Something is wrong. Try to return to last page or restart a new ticket.</h1>
		<?php
		HTMLFooter();
	}

	# pre: when the data check passed, ready to make a new ticket
	# post: make a new ticket in sql and return the file name to save in server
	function newTicketFileName(){
		$conn = connectToDB("dbInformation.txt");

		$user = $conn->quote($_SESSION["user"]);
		$query = $conn->query("SELECT TOP(1) [id]
							  FROM dbo.user_data
							  WHERE [user] LIKE $user");

		foreach($query as $test){
			$user = $test["id"];
		}

		$user = $conn->quote($user);
		$company = $conn->quote($_POST["id"]);
		$year = $conn->quote(Date("Y"));
		$contactee = queryNullMaker($conn, $_POST["contactee"]);
		$email = queryNullMaker($conn, $_POST["email"]);
		$phone = queryNullMaker($conn, $_POST["phone"]);

		print "test1";

		$query = $conn->query("INSERT INTO dbo.ticket ([user_id], company_id, [year], [status], 
									contactee, [email], c_phone)
								OUTPUT INSERTED.id
								VALUES ($user, $company, $year, 'new', $contactee, $email,
									$phone)");

		print "test2";

		foreach($query as $temp){
			return trim($temp["id"]);
		}
	}

	# pre: when the data might be null and it needs to be put in a sql query
	# post: quote the data if the data is not null, and return null when data is null
	function queryNullMaker($conn, $data){
		if($data == null || $data == ""){
			return 'NULL';
		}

		return $conn->quote($data);
	}

?>