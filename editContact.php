<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		editContact();
	}else{
		displayPage();
	}
?>


<?php
	# this is a function block

	# pre: when a post request is made
	# post: update the contact info in db
	function editContact(){
		if(!isset($_POST["ticket_id"]) || !is_numeric($_POST["ticket_id"])){
			showErrorMessage();
			die();
		}

		$conn = connectToDB("data/dbInformation.txt");

		$id = $conn->quote($_POST["ticket_id"]);
		$contactee = nullMaker($conn, $_POST["contactee"]);
		$email = nullMaker($conn, $_POST["email"]);
		$phone = nullMaker($conn, $_POST["phone"]);

		if($conn->query("UPDATE dbo.ticket
						SET contactee = $contactee, [email] = $email, c_phone = $phone
						WHERE [id] = $id")){
			$ticket = new DOMDocument();
			$ticket->load("data/tickets/".$_POST["ticket_id"].".xml");
			$log = $ticket->createElement("log");
			$log->setAttribute("time", microtime(true));
			$log->setAttribute("author", "system");
			$log->setAttribute("status", "Comment");
			$log->appendChild($ticket->createElement("text", $_SESSION["user"]." has changed contact information"));
			$log->appendChild($ticket->createElement("files"));
			$ticket->getElementsByTagName("logs")->item(0)->appendChild($log);
			if($ticket->save("data/tickets/".$_POST["ticket_id"].".xml")){
				header("Location: ticket.php?id=".$_POST["ticket_id"]);
				die();
			}else{
				showErrorMessage();
				die();
			}
		}else{
			showErrorMessage();
			die();
		}
	}

	# pre: when the data passed in might be null
	# post: make the data null fit in sql if null, quote it when not null
	function nullMaker($conn, $data){
		if($data == null){
			return 'NULL';
		}

		return $conn->quote($data);
	}

	# pre: when the user wants to edit contact info
	# post: show a pagw with previous contact info
	function displayPage(){
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}

		$conn = connectToDB("data/dbInformation.txt");
		$id = $conn->quote($_GET["id"]);

		$quote = $conn->query("SELECT *
								FROM dbo.ticket
								WHERE [id] = $id");

		$contactee = "";
		$email = "";
		$phone = "";
		foreach($quote as $row){
			if($row["contactee"]){
				$contactee = $row["contactee"];
			}

			if($row["email"]){
				$email = $row["email"];
			}

			if($row["c_phone"]){
				$phone = $row["c_phone"];
			}
		}

		HTMLHeader("編輯聯絡人 Edit Contacts", "", "editContact.js");
		?>
			<div class="container">
				<h1>編輯聯絡人 Edit Contacts</h1>
				<form class="form-horizontal" action="editContact.php" method="POST">
					<div class="form-group">
						<label class="control-label col-sm-2">聯絡人: </label>
						<div class="col-sm-6">
							<input class="form-control" name="contactee" placeholder="請輸入聯絡人姓名" value="<?=$contactee?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">電子信箱: </label>
						<div class="col-sm-6">
							<input class="form-control" type="email" name="email" placeholder="請輸入聯絡人電子信箱地址" value="<?=$email?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">連絡電話: </label>
						<div class="col-sm-6">
							<input id="phone" class="form-control" type="tel" name="phone" placeholder="請輸入聯絡人電話" value="<?=$phone?>">
							<span class="help-block"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						<input type="hidden" name="ticket_id" value="<?=$_GET["id"]?>">
							<button type="submit" class="btn btn-primary">修改聯絡人資料 Edit Contact</button>
						</div>
					</div>
				</form>
			</div>
		<?php
		HTMLFooter();
	};
	
?>