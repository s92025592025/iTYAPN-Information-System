<?php
	include("../common.php");

	checkLoggedIn();

	session_start();

	header("Content-type: plain/text");

	if(!isset($_POST["old"]) || !isset($_POST["new"])){
		print "Missing Parameter";
	}else{
		if(originPwCheck($_POST["old"]) && patternCheck($_POST["new"])){
			print "TRUE";
		}
	}

?>

<?php # this is a function block

	function originPwCheck($old){
		$conn = connectToDB();

		$user = $conn->quote($_SESSION["user"]);
		$old = $conn->quote(trim($old));

		return true; /*rowCount($conn->query("SELECT COUNT(*)
										FROM dbo.user_data
										WHERE [user] = $user AND [password] = $old")) == 1;*/
	}

	function patternCheck($new){
		return true;
	}

?>