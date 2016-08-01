<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("Home", "home.css", "");

?>

	<div class="container">
	<h1>Welcome, <?=$_SESSION["user"]?></h1>
		<fieldset>
			<legend>My Tickets</legend>
			<?=myTickets()?>
		</fieldset>
	</div>

<?php
	HTMLFooter();

	function myTickets(){
		$conn = connectToDB();

		$user = $conn->quote($_SESSION["user"]);
		$year = $conn->quote(date("Y"));

		$tickets = $conn->query("SELECT *
								FROM dbo.ticket
								JOIN dbo.user_data ON dbo.ticket.user_id = dbo.user_data.id
								JOIN dbo.company_list ON dbo.company_list.id = dbo.ticket.company_id
								WHERE dbo.user_data.[user] = $user AND dbo.ticket.year = $year
								ORDER BY dbo.ticket.company_id");
		?>
		<table>
			<tr><th>#Id</th><th>Company</th><th>Owner</th><th>Status</th></tr>
			<?php
				foreach($tickets as $ticket){
					?>
						<tr><td><?=$ticket["dbo.ticket.id"]?></td><td><?=$ticket["dbo.company_list.e_name"]?></td><td></td><?=$ticket["dbo.ticket.status"]?></td></tr>
					<?php
				}
			?>
		</table>
		<?php
	}
?>