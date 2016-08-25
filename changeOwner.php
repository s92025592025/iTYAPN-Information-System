<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){

	}else{
		displayPage();
	}
?>

<?php
	# this is a function block

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

				<from class="form-horizontal" action="changeOwner.php" method="POST">
					<div class="form-group">
						<label class="control-label col-sm-4">負責人 Owner:</label>
						<div class="col-sm-4">
							<select class="form-control" name="users" for="users">
								<?=getUsers()?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary col-sm-offset-4">更改負責人</button>
					</div>
				</from>
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
			$owner_id = $row["id"];
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