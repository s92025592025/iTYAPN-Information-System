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
						<ul>
							<li>公司名稱: <?=$info["c_name"]?></li>
							<li>英文名稱: <?=$info["e_name"]?></li>
							<li>電話號碼: <?=nullTester($info["phone"])?></li>
							<li>電子信箱: <?=nullTester($info["email"])?></li>
							<li>聯絡地址: <?=nullTestrer($info["address"])?></li>
							<li>聯絡人:  <?=nullTester($info["contactee"])?></li>
							<li>負責人: <?=$info["user"]?></li>
						</ul>
					</div>
				</div>
				<div class="info panel panel-primary">
					<div class="panel-heading">位置</div>
					<div class="panel-body">
						<img class="maps" src="" />
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

	function nullTester($data){
		if($data == null || $data == "NULL"){
			return "none";
		}

		return $data;
	}

?>