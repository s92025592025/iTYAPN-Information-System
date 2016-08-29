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
	function editContact(){}

	# pre: when the user wants to edit contact info
	# post: show a pagw with previous contact info
	function displayPage(){
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}

		HTMLHeader("編輯聯絡人 Edit Contacts", "", "");
		?>
			<div class="container">
				<h1>編輯聯絡人 Edit Contacts</h1>
			</div>
		<?php
		HTMLFooter();
	};
	
?>