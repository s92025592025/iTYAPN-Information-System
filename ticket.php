<?php

	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"])){
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

		$info = $ticketInfo->fetchColumn();
		?>

		<div id="main" class="container">
			<h1><?=$info["phone"]?></h1>
		</div>

		<?php
		HTMLFooter();
	}

?>