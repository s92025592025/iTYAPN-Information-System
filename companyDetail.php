<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
		showErrorMessage();
		die();
	}

	HTMLHeader("詳細資料# ".$_GET["id"], "companyDetail.css", "");

	$conn = connectToDB("data/dbInformation.txt");
	$company_id = $conn->quote($_GET["id"]);
	$query = $conn->query("SELECT TOP(1) *
							FROM dbo.company_list
							WHERE [Id] = $company_id");
	$company_detail = array(
								"id" => $_GET["id"],
								"c_name" => "",
								"e_name" => "",
								"email" => "",
								"phone" => "",
								"address" => "",
								"customer" => "",
								"fax" => "",
								"abbre" => "",
								"genre" => ""
							);

	foreach($query as $row){
		$company_detail["c_name"] = $row["c_name"];
		$company_detail["e_name"] = $row["e_name"];
		$company_detail["address"] = $row["address"];
		$company_detail["genre"] = nullConvert($row["genre"]);
		$company_detail["email"] = nullConvert($row["email"]);
		$company_detail["phone"] = nullConvert($row["phone"]);
		$company_detail["fax"] = nullConvert($row["fax"]);
		$company_detail["customer"] = nullConvert($row["custom_service"]);
		$company_detail["abbre"] = nullConvert($row["abbre"]);
	}

	?>
		<div class="container">
			<h1>公司詳情 Company Detail</h1>
			<h2><?=$company_detail["c_name"]." ".$company_detail["e_name"]?> <a role="button" href="createNewCompany.php?id=<?=$_GET["id"]?>" class="btn btn-warning">修改資料</a></h2>
			<div class="panel panel-info">
				<div class="panel-heading">基本資料</div>
				<div class="panel-body">
					<div id="detail" class="panel-detail">
						<ul>
							<li>公司名稱: <?=$company_detail["c_name"]?></li>
							<li>英文名稱: <?=$company_detail["e_name"]?></li>
							<li>連絡電話: <?=$company_detail["phone"]?></li>
							<li>電子信箱: <?=$company_detail["email"]?></li>
							<li>客戶服務: <?=$company_detail["customer"]?></li>
							<li>傳真號碼: <?=$company_detail["fax"]?></li>
							<li>產業類別: <?=$company_detail["genre"]?></li>
							<li>其他稱呼: <?=str_replace(";", ", ", $company_detail["abbre"])?></li>
							<li>公司地址: <?=$company_detail["address"]?></li>
						</ul>
					</div>
					<div id="map" class="panel-detail">
						<img src=<?="http://maps.googleapis.com/maps/api/staticmap?center=".str_replace(" ", "+", $company_detail["address"])."&markers=color:red%7Clabel=@%7C".str_replace(" ", "+", $company_detail["address"])."&zoom=13&size=300x250&maptype=roadmap&key=".trim(file("data/googleAPI.txt")[0])?> >
					</div>
				</div>
			</div>

			<?php
				$statics = companyStatics();
			?>

			<div class="panel panel-success">
				<div class="panel-heading">統計 Statics (From 2015)</div>
				<div class="panel-body">
					<div class="panel-detail">
						<ul>
							<li>總連絡次數: <?=$statics["total_contact"]?></li>
							<li>取得實習職位數: <?=$statics["total_positions"]?></li>
							<li>洽談成功次數: <?=$statics["total_success"]?></li>
							<li>洽談失敗次數: <?=$statics["total_fail"]?></li>
						</ul>
					</div>
				</div>
			</div>

			<?php
				$query = $conn->query("SELECT dbo.ticket.[Id], c_name, [user], [year], [status]
									   FROM dbo.ticket
									   JOIN dbo.company_list ON dbo.company_list.[Id] = company_id
									   JOIN dbo.user_data ON dbo.user_data.[id] = user_id
									   WHERE $company_id = company_id");
			?>
			<div class="panel panel-info">
				<div class="panel-heading">聯絡紀錄 Past Tickets</div>
				<div class="panel-body">
					<table class="table table-hover table-condensed">
						<tr><th>#Id</th><th>公司名字</th><th>負責人</th><th>年分</th><th>狀態</th></tr>
						<?php
							foreach($query as $row){
								?>
									<tr><td><?=$row["Id"]?></td><td><a href="ticket.php?id=<?=$row["Id"]?>"><?=$row["c_name"]?></a></td><td><?=$row["user"]?></td><td><?=$row["year"]?></td><td><?=$row["status"]?></td></tr>
								<?php
							}
						?>
					</table>
				</div>
			</div>
		</div>
	<?php
	HTMLFooter();
?>

<?php
	# this is a function block
	
	# pre: when the company detail is requested from search page
	# post: get the statics from previous tickets
	function companyStatics(){
		$conn = connectToDB("data/dbInformation.txt");
		$statics = array(
							"total_contact" => 0,
							"total_positions" => 0,
							"total_success" => 0,
							"total_fail" => 0
													);

		$id = $conn->quote($_GET["id"]);

		$statics["total_contact"] = $conn->query("SELECT COUNT(*)
												  FROM dbo.ticket
												  WHERE company_id = $id")->fetchColumn();
		$statics["total_success"] = $conn->query("SELECT COUNT(*)
												  FROM dbo.ticket
												  WHERE company_id = $id AND [status] = 'Success'")->fetchColumn();
		$statics["total_fail"] = $conn->query("SELECT COUNT(*)
											   FROM dbo.ticket
											   WHERE company_id = $id AND [status] = 'Fail'")->fetchColumn();

		$query = $conn->query("SELECT [Id]
							   FROM dbo.ticket
							   WHERE company_id = $id");
		foreach($query as $row){
			$ticket = new DOMDocument();
			$ticket->load("data/tickets/".$row["Id"].".xml");

			$statics["total_positions"] += $ticket->getElementsByTagName("position")->length;
		}

		return $statics;
	}

	# pre: when a line of data might be empty or null
	# post: return "none" when data is empty, return original if not
	function nullConvert($data){
		if($data == null || $data == ""){
			return "none";
		}

		return $data;
	}
?>