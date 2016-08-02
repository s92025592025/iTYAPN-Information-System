<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("Home", "home.css", "");

?>

	<div class="container">
		<div>
			<h1>Welcome, <?=$_SESSION["user"]?> <button type="button" id="new_ticket_btn" class="btn btn-primary">New Tickets</button></h1>
		</div>
		<fieldset>
			<legend>My Tickets</legend>
			<?=myTickets()?>
		</fieldset>
		<fieldset>
			<legend>Tickets in queue</legend>
			<?=activeTickets()?>
		</fieldset>
		<fieldset>
			<legend>Past tickets</legend>
			<?=pastTickets()?>
		</fieldset>
	</div>

<?php
	HTMLFooter();

	function makeTable($tickets){
		?>
		<table>
			<tr><th>#Id</th><th>Company</th><th>Owner</th><th>Status</th></tr>
			<?php
				foreach($tickets as $ticket){
					?>
						<tr><td><?=$ticket["id"]?></td><td><?=$ticket["c_name"]?></td><td><?=$ticket["user"]?></td><td><?=$ticket["status"]?></td></tr>
					<?php
				}
			?>
		</table>
		<?php
	}

	function myTickets(){
		$conn = connectToDB();

		$user = $conn->quote($_SESSION["user"]);
		$year = $conn->quote(date("Y"));

		$tickets = $conn->query("SELECT dbo.ticket.id, dbo.company_list.c_name, 
									dbo.company_list.e_name, dbo.user_data.[user], dbo.ticket.status
								FROM dbo.ticket
								JOIN dbo.user_data ON dbo.ticket.user_id = dbo.user_data.id
								JOIN dbo.company_list ON dbo.company_list.id = dbo.ticket.company_id
								WHERE dbo.user_data.[user] = $user AND dbo.ticket.year LIKE $year
									AND dbo.ticket.status != 'success' 
									AND dbo.ticket.status != 'fail'
								ORDER BY dbo.ticket.company_id");

		makeTable($tickets);
	}

	function activeTickets(){
		$conn = connectToDB();

		$user = $conn->quote($_SESSION["user"]);
		$year = $conn->quote(date("Y"));

		$tickets = $conn->query("SELECT dbo.ticket.id, dbo.company_list.c_name, 
									dbo.company_list.e_name, dbo.user_data.[user], dbo.ticket.status
								FROM dbo.ticket
								JOIN dbo.user_data ON dbo.ticket.user_id = dbo.user_data.id
								JOIN dbo.company_list ON dbo.company_list.id = dbo.ticket.company_id
								WHERE dbo.user_data.[user] != $user AND dbo.ticket.year LIKE $year
									AND dbo.ticket.status != 'success'
									AND dbo.ticket.status != 'fail'
								ORDER BY dbo.ticket.company_id");

		makeTable($tickets);
	}

	function pastTickets(){
		$conn = connectToDB();

		$year = $conn->quote(date("Y"));

		$tickets = $conn->query("SELECT dbo.ticket.id, dbo.company_list.c_name, 
									dbo.company_list.e_name, dbo.user_data.[user], dbo.ticket.status
								FROM dbo.ticket
								JOIN dbo.user_data ON dbo.ticket.user_id = dbo.user_data.id
								JOIN dbo.company_list ON dbo.company_list.id = dbo.ticket.company_id
								WHERE dbo.ticket.year < $year OR dbo.ticket.status = 'success'
									OR dbo.ticket.status = 'fail'
								ORDER BY dbo.ticket.company_id");

		makeTable($tickets);
	}
?>