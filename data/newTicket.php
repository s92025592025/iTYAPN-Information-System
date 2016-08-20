<?php
	include("../common.php");

	checkeLoggedIn();

	session_start();

	if(!isset($_POST["id"]) || $_POST["id"] == ""){
		showWarning();
		die();
	}
?>


<?php
		# this is a function block
	
	function showWarning(){
		HTMLHeader();
		?>
			<h1 style="text-align: center; color: red;">OOPS?! Something is wrong. Try to return to last page or restart a new ticket.</h1>
		<?php
		HTMLFooter();
	}

?>