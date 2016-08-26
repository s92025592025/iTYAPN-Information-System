<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		editPosition();
	}else{
		displayPage();
	}
?>

<?php
	# this is a function bolck

	# pre: when the user tried to delete or add a new position
	# post: add or delete the position in xml
	function editPosition(){};

	# pre: when the user request to edit the positions
	# post: display a page for the user to edit the positions in the ticket
	function displayPage(){
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}
	};
?>