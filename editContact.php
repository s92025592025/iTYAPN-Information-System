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
				<form class="form-horizontal" action="editContact.php" method="POST">
					<div class="form-group">
						<label class="control-label col-sm-2">聯絡人: </label>
						<div class="col-sm-6">
							<input class="form-control" name="contactee" placeholder="請輸入聯絡人姓名">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">電子信箱: </label>
						<div class="col-sm-6">
							<input class="form-control" type="email" name="email" placeholder="請輸入聯絡人電子信箱地址">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">連絡電話: </label>
						<div class="col-sm-6">
							<input class="form-control" type="tel" name="phone" placeholder="請輸入聯絡人電話">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">修改聯絡人資料 Edit Contact</button>
						</div>
					</div>
				</form>
			</div>
		<?php
		HTMLFooter();
	};
	
?>