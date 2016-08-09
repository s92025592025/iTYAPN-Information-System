<?php

	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"]) || !checkTicket($_GET["id"])){
		header("Location: home.php");
		die();
	}else{
		HTMLHeader("Ticket #".$_GET["id"], "ticket.css", "");

		$conn = connectToDB("data/dbInformation.txt");
		$ticketId = $conn->quote($_GET["id"]);
		$ticketInfo = $conn->query("SELECT TOP(1) [user], c_name, e_name, contactee,
										dbo.ticket.email, [address], [status],
										phone
									FROM dbo.ticket
									JOIN dbo.company_list ON company_id = dbo.company_list.id
									JOIN dbo.user_data ON user_id = dbo.user_data.id
									WHERE dbo.ticket.id LIKE $ticketId");

		$info = "";

		foreach($ticketInfo as $ticket){
			$info = $ticket;
		}

		?>

		<div id="main" class="container">
			<h1><?=$info["c_name"]?></h1>

			<div class="btn-group">
				<button type="button" class="btn btn-success">Comment</button>
				<button type="button" class="btn btn-success">Basics</button>
				<button type="button" class="btn btn-success">Status</button>
			</div>
		</div>

		<?php
		HTMLFooter();
	}

?>

<?php

	# This is a function block
	
	# pre: when request to see a detail of a ticket
	# post: check if the ticket exists
	function checkTicket($id){
		$conn = connectToDB("data/dbInformation.txt");

		$id = $conn->quote($id);

		return $conn->query("SELECT COUNT(*)
							FROM dbo.ticket
							WHERE id LIKE $id")->fetchColumn() == 1;
	}

?>