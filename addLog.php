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
	function addLog(){
		if(!isset($_POST["id"]) || !isset($_POST["status"]) || !is_numeric($_POST["id"]) || 
			!statusMatch($_POST["status"])){
			showErrorMessage();
			die();
		}
		print "test1";
		$ticket = new DOMDocument();
		$ticket->load("data/tickets/".$_POST["id"].".xml");
		print "test2";


		$log = $ticket->createElement("log");
		$log->setAttribute("time", microtime(true));
		$log->setAttribute("author", $_SESSION["user"]);
		$log->setAttribute("status", $_POST["status"]);
		$text = $ticket->createElement("text", $_POST["comment"]);
		print "test3";

		$files = $ticket->createElement("files");

		print "test4";
		$log->appendChild($text);
		print "test5";
		$log->appendChild($files);
		print "test6";
		$ticket->getElementsByTagName("logs")->item(0)->appendChild($log);
		print "test7";


		if($ticket->save("data/tickets/".$_POST["id"].".xml")){
			if($_POST["status"] != "Comment"){
				updateStatusInDB();
			}
			header("Location: ticket.php?id=".$_POST["id"]);
		}else{
			showErrorMessage();
		}
	}

	# pre: when a status is passed in as a POST method
	# post: return true if the status passed in is the one we want
	function statusMatch($data){
		$statuses = array("Open", "Stalled", "Comment", "Fail", "Success");
		foreach($statuses as $status){
			if($data == $status){
				return true;
			}
		}
		return false;
	}

	# pre: when the user is leaving a comment with a statua change
	# post: update the status change in db
	function updateStatusInDB(){
		$conn = connectToDB("data/dbInformation.txt");

		$id = $conn->quote($_POST["id"]);
		$status = $conn->quote($_POST["status"]);

		$conn->query("UPDATE dbo.ticket
					  SET [status] = $status
					  WHERE [id] = $id");
	}

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

		?>
			<div class="container">
				<h1>新增紀錄 Add Log</h1>
				<form class="form-horizontal" action="addLog.php" method="POST">
					<div class="form-group">
						<label for="status">狀態 Status:</label>
						<select name="status" id="status" class="form-control">
							<?php
								$status = array("Open", "Stalled", "Comment", "Fail", "Success");
								foreach($status as $temp){
									if(strtoupper($temp) == strtoupper($_GET["status"])){
										?>
											<option selected value=<?=$temp?>><?=$temp?></option>
										<?php
									}else{
										?>
											<option value=<?=$temp?>><?=$temp?></option>
										<?php
									}
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="comment">紀錄 Comment: </label>
						<textarea name="comment" id="comment" class="form-control" row="10"></textarea>
					</div>
					<div class="form-group">
						<input type="hidden" name=id value=<?=$_GET["id"]?> />
						<div class="col-sm-offest-5 col-sm-7">
							<button type="submit" class="btn btn-primary">新增紀錄</button>
						</div>
					</div>
				</form>
			</div>
		<?php

		HTMLFooter();
	}
?>