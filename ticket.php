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

			<div id="status_control" class="btn-group">
				<button type="button" class="btn btn-success">Comment</button>
				<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Basics <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#">People</a></li>
						<li><a href="#">Positions</a></li>
						<li><a href="#">Contacts</a></li>
					</ul>
				</div>
				<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Status <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li>Delete</li>
						<li>Stalled</li>
						<li>Comment</li>
						<li>Fail</li>
						<li>Success</li>
					</ul>
				</div>
			</div>

			<div class="row">
				<div class="info panel panel-primary">
					<div class="panel-heading">詳細資料</div>
					<div class="panel-body">
						What is Lorem Ipsum?
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
					</div>
				</div>
				<div class="info panel panel-primary">
					<div class="panel-heading">位置</div>
					<div class="panel-body">
						What is Lorem Ipsum?
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
					</div>
				</div>
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