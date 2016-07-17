<?php
	function HTMLheader($title, $css, $js){
		?>
			<!DOCTYPE html>
			<html>
				<head>
					<title><?=$title?></title>
					<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
								<li class="active"><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
							</ul>
						</div>
					</nav>
		<?php
	}

	function HTMLFooter(){
		?>
				</body>
			</html>
		<?php
	}
?>