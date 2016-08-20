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

	# pre: when the 
	function newTicketFileName(){
		$conn = connectToDB("dbInformation.txt");

		$user = $conn->quote($_SESSION["user"]);
		$query = $conn->query("SELECT TOP(1) [id]
							  FROM dbo.user_data
							  WHERE [user] LIKE $user");

		foreach($query as $test){
			$user = $test["id"];
		}

		$company = $conn->quote($_POST["id"]);
		$query = $conn->query("");

	}

?>