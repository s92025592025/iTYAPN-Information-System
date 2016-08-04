<?php
	# pre: Load in when the page is loaded
	# post: load in when the header of the page
	function HTMLheader($title, $css, $js){
		?>
			<!DOCTYPE html>
			<html>
				<head>
					<title><?=$title?></title>
					<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
					<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
					<link rel="stylesheet" type="text/css" href="login.css">
					<link rel="stylesheet" type="text/css" href=<?=$css?>>
					<link rel="icon" type="img/jpg" href="img/logo.jpg">
					<script type="text/javascript" src=<?=$js?>></script>
				</head>

				<body>
					<nav class="nav navbar-default">
						<div class="container-fluid">
							<div class="navbar-header">
								<a class="navbar-brand" href="#">TYAPN</a>
							</div>
							<ul class="nav navbar-nav">
								<li><a href="home.php">Home</a></li>
								<li><a herf="search.php">Search</a></li>
								<li><a href="account.php">Account</a></li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li class="active"><a href="data/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							</ul>
						</div>
					</nav>
		<?php
	}

	# pre: load in when the page is loaded
	# post: load in the footer of the page
	function HTMLFooter(){
		?>
				</body>
			</html>
		<?php
	}

	# pre: whenever need to connect to the database
	# post: return a PDO object that connect to the db
	function connectToDB($path){
		try{
			$conn = new PDO ("sqlsrv:server = tcp:ityapn-database-server.database.windows.net,1433; Database = iTYAPNSystemDB", trim(file($path)[0]), trim(file($path)[1]));
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}catch(PDOException $e){
			header("Content-type: plain/text");
			print($e->getMessage());
		}

		return $conn;
	}

	# pre: whenever the user reaches the page that should display only when they have logged in
	# post: if the session isn't set, redirect the user to login
	function checkLoggedIn(){
		session_start();
		if(!isset($_SESSION["user"])){
			unset($_SESSION["user"]);
			header("Location: login.html");
			die();
		}
	}
?>