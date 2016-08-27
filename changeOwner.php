<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		changeOwner();
	}else{
		displayPage();
	}
?>

<?php
	# this is a function block

	# pre: when the user submit the data
	# post: change the owner to a new one in sql, and then record it in log.
	#		Then redirect to ticket page
	function changeOwner(){
		if(!isset($_POST["ticket"]) || !isset($_POST["new_owner"]) ||
			!is_numeric($_POST["ticket"]) || !is_numeric($_POST["new_owner"])){
			showErrorMessage();
			die();
		}

		$conn = connectToDB("data/dbInformation.txt");

		$ticket_id = $conn->quote($_POST["ticket"]);
		$new_owner = $conn->quote($_POST["new_owner"]);

		$query = $conn->query("SELECT [user]
								FROM dbo.ticket
								JOIN dbo.user_data ON dbo.ticket.user_id = dbo.user_data.[id]
								WHERE dbo.ticket.[id] = $ticket_id");


		$old_owner_name = "";
		foreach($query as $row){
			$old_owner_name = $row["user"];
		}

		$query = $conn->query("SELECT [user]
								FROM dbo.user_data
								WHERE [id] = $new_owner");

		$new_owner_name = "";
		foreach($query as $row){
			$new_owner_name = $row["user"];
		}

		if($conn->query("UPDATE dbo.ticket
						 SET [user_id] = $new_owner
						 WHERE [id] = $ticket_id")){
			addCommentToTicket($new_owner_name, $old_owner_name, $_POST["ticket"]);
			header("Location: ticket.php?id=".$_POST["ticket"]);
			die();
		}else{
			showErrorMessage();
			die();
		}
	}

	# pre: when the owner change in sql is successful
	# post: add a comment to ticket by system to explain what's going on
	function addCommentToTicket($new, $old, $id){
		$ticket = new DOMDocument();
		$ticket->load("data/tickets/$id.xml");

		$log = $ticket->createElement("log");
		$log->setAttribute("time", microtime(true));
		$log->setAttribute("author", "system");
		$log->setAttribute("status", "Comment");

		$text = $ticket->createElement("text", $_SESSION["user"]." has assigned this ticket from $old to $new");

		$files = $ticket->createElement("files");

		$log->appendChild($text);
		$log->appendChild($files);

		$ticket->getElementsByTagName("logs")->item(0)->appendChild($log);

		if(!$ticket->save("data/tickets/$id.xml")){
			showErrorMessage();
			die();
		}
	}

	# pre: when the user clicked "people" in the tab
	# post: display a page for user to change owner of the ticket
	function displayPage(){

		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}

		HTMLHeader("People", "", "");

		?>
			<div class="container">
				<h1>People</h1>

				<form class="form-horizontal" action="changeOwner.php" method="POST">
					<div class="form-group">
						<label class="control-label col-sm-4" for="users">負責人 Owner:</label>
						<div class="col-sm-4">
							<select class="form-control" name="new_owner" id="users" required>
								<?=getUsers()?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<input type="hidden" name="ticket" value=<?=$_GET["id"]?> />
						<button type="submit" class="btn btn-primary col-sm-offset-4">更改負責人</button>
					</div>
				</form>
			</div>
		<?

		HTMLFooter();
	}

	// pre: when the option tab need to display all the users
	// post: show the current owner and display all the rest of user
	function getUsers(){
		$conn = connectToDB("data/dbInformation.txt");

		$ticket_id = $conn->quote($_GET["id"]);

		$query = $conn->query("SELECT [user_id]
								FROM dbo.ticket
								WHERE [id] = $ticket_id");

		$owner_id = "";
		foreach($query as $row){
			$owner_id = $row["user_id"];
		}

		$query = $conn->query("SELECT [id], [user]
								FROM dbo.user_data
								ORDER BY [user]");

		foreach($query as $row){
			if($row["id"] == $owner_id){
				?>
					<option selected value=<?=$owner_id?>><?=$row["user"]?>(owner)</option>
				<?php
			}else{
				?>
					<option value=<?=$row["id"]?>><?=$row["user"]?></option>
				<?php
			}
		}
	}
?>