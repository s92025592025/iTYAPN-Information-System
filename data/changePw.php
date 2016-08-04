<?php
	include("../common.php");

	checkLoggedIn();

	session_start();

	header("Content-type: plain/text");

	if(!isset($_POST["old"]) || !isset($_POST["new"])){
		print "Missing Parameter";
	}else{

		// check if the old password match the current one
		if(!originPwCheck($_POST["old"])){
			print "Old password not correct";
			die();
		}

		// check if the new password macth the required strength
			print "Please make your password at least 6 digit long, consist of alphabets(case-sensitive),numbers, or special symbols like !@#$%^&*";
			die();
		}

	}

?>

<?php # this is a function block

	# pre: when the user send in their old password
	# post: check if the old password matchs the one in the db
	function originPwCheck($old){
		$conn = connectToDB("dbInformation.txt");

		$user = $conn->quote($_SESSION["user"]);
		$old = $conn->quote(trim($old));

		return $conn->query("SELECT COUNT(*)
										FROM dbo.user_data
										WHERE [user] = $user AND [password] = $old")->fetchColumn() == 1;
	}

	# pre: after the password check and the pattern check passed
	# post: change the password for the user to the new one, return "TRUE" when success,
	#		show error message when things when wrong
	function passwordChange($old, $new){
		$conn = connectToDB("dbInformation.txt");

		$user = $conn->quote($_SESSION["user"]);
		$old = $conn->quote(trim($old));
		$new = $conn->quote(trim($new));

		$query = $conn->query("UPDATE dbo.user_data
								SET [password]=$new
								WHERE [user]=$user AND [password]=$old");

		if($query->rowCount()){
			return "TRUE";
		}

		return "Something went wrong, please contact your administrator with the error message your saw";
	}

?>