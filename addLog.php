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
	function addLog(){}

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
				<form action="addLog.php" method="POST">
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
				</form>
			</div>
		<?php

		HTMLFooter();
	}
?>