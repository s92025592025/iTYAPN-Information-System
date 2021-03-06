<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("Account", "account.css", "account.js");

	?>

	<div class="container">
		<h1>Account Info</h1>
		<div id="change_pw">
			<legend>Change passowrd</legend>
			<p id="old_pw_warning" class="warning"></p>
			<label>old password: <input type="password" id="old_pw"></label>
			<p id="change_pw_warning" class="warning"></p>
			<label>new password: <input type="password" id="new_pw"></label>
			<label>re-enter new pw: <input type="password" id="re_enter_pw"></label>
			<button id="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>

	<?php

	HTMLFooter();
?>