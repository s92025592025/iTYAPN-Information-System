<?php
	include("common.php");

	checkLoggedin();

	session_start();

	HTMLHeader("搜尋公司 Search Company", "search.css", "");

	$key = "";
	if(isset($_GET["key"])){
		$key = $_GET["key"];
	}

	?>
		<div class="container">
			<h1>搜尋公司 Search Company</h1>
			<form class="form-inline" action="search.php" method="GET">
				<div class="form-group">
					<label class="sr-only" for="key">Search</label>
					<input type="search" name="key" id="key" class="form-control" placeholder="請輸入關鍵字" value="<?=$key?>">
					<button type="submit" class="btn btn-info">搜尋 Search</button>
				</div>
			</form>
			<?php
				if(isset($_GET["key"])){
					showResults($_GET["key"]);
				}
			?>
		</div>
	<?php

	HTMLFooter();
?>

<?php
	# this is a function block

	# pre: when the get parameter is set
	# post: show a list of companies that may match the key word
	function showResults($key){
		$conn = connectToDB("data/dbInformation.txt");

		$key = $conn->quote("%$key%");

		$search = $conn->query("SELECT *
								FROM dbo.company_list
								WHERE c_name LIKE N$key OR e_name LIKE N$key OR
									[address] LIKE N$key OR email LIKE N$key OR
									phone LIKE N$key OR abbre LIKE N$key
								ORDER BY c_name, e_name, [email], phone, [address]");
	}
?>