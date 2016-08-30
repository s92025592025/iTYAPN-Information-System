<?php

	include("common.php");

	checkLoggedIn();

	session_start();

	if(!isset($_GET["id"]) || !checkTicket($_GET["id"])){
		header("Location: home.php");
		die();
	}else{
		HTMLHeader("Ticket #".$_GET["id"], "ticket.css", "");

		$conn = connectToDB("data/dbInformation.txt");
		$ticketId = $conn->quote($_GET["id"]);

		$ticketInfo = $conn->query("SELECT TOP(1) [user], c_name, e_name, contactee,
										dbo.ticket.email, [address], [status],
										c_phone
									FROM dbo.ticket
									JOIN dbo.company_list ON company_id = dbo.company_list.id
									JOIN dbo.user_data ON user_id = dbo.user_data.id
									WHERE dbo.ticket.id LIKE $ticketId");

		$info = "";

		foreach($ticketInfo as $ticket){
			$info = $ticket;
		}

		?>

		<div id="main" class="container">
			<h1>#<?=$_GET["id"]?>-<?=$info["c_name"]?></h1>

			<div id="status_control" class="btn-group">
				<a role="button" class="btn btn-success" href=<?="addLog.php?id=".$_GET["id"]."&status=Comment"?>>Comment</a>
				<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Basics <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="changeOwner.php?id=<?=$_GET["id"]?>">People</a></li>
						<li><a href="editPositions.php?id=<?=$_GET["id"]?>">Positions</a></li>
						<li><a href="editContact.php?id=<?=$_GET["id"]?>">Contacts</a></li>
					</ul>
				</div>
				<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Status <span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" data-toggle="modal" data-target="#myModal">Delete</a></li>
						<li><a href=<?="addLog.php?id=".$_GET["id"]."&status=Open"?>>Open</a></li>
						<li><a href=<?="addLog.php?id=".$_GET["id"]."&status=Stalled"?>>Stalled</a></li>
						<li><a href=<?="addLog.php?id=".$_GET["id"]."&status=Comment"?>>Comment</a></li>
						<li><a href=<?="addLog.php?id=".$_GET["id"]."&status=Fail"?>>Fail</a></li>
						<li><a href=<?="addLog.php?id=".$_GET["id"]."&status=Success"?>>Success</a></li>
					</ul>
				</div>
			</div>

			<div id="myModal" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">確定要刪除?</h4>
			      </div>
			      <div class="modal-body">
			        <p>確定要刪除? 此舉將無法恢復。</p>
			      </div>
			      <div class="modal-footer">
			      	<form>
			      		<input type="hidden" name="id" value="<?=$_GET["id"]?>">
				      	<button type="submit" class="btn btn-danger">確定刪除</button>
			        	<button type="button" class="btn btn-warning" data-dismiss="modal">取消</button>

				     </form>
			      </div>
			    </div>

			  </div>
			</div>

			<div class="row">
				<div class="info panel panel-primary">
					<div class="panel-heading">詳細資料</div>
					<div class="panel-body">
						<ul>
							<li>公司名稱: <?=$info["c_name"]?></li>
							<li>英文名稱: <?=$info["e_name"]?></li>
							<li>電話號碼: <?=nullTester($info["c_phone"])?></li>
							<li>電子信箱: <?=nullTester($info["email"])?></li>
							<li>聯絡地址: <?=$info["address"]?></li>
							<li>聯絡人:  <?=nullTester($info["contactee"])?></li>
							<li>負責人: <?=$info["user"]?></li>
						</ul>
					</div>
				</div>
				<div class="info panel panel-primary">
					<div class="panel-heading">位置</div>
					<div class="panel-body">
						<img class="maps" src=<?="http://maps.googleapis.com/maps/api/staticmap?center=".str_replace(" ", "+", $info["address"])."&markers=color:red%7Clabel=@%7C".str_replace(" ", "+", $info["address"])."&zoom=13&size=300x250&maptype=roadmap&key=".trim(file("data/googleAPI.txt")[0])?> />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="full_panel panel panel-success">
					<div class="panel-heading">實習職位 Internship Position</div>
					<div class="panel-body">
						<?=positions($_GET["id"])?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="full_panel panel panel-danger">
					<div class="panel-heading">紀錄 Comments</div>
					<div class="panel-body"><?=comments($_GET["id"])?></div>
				</div>
			</div>

		</div>

		<?php
		HTMLFooter();
	}

?>

<?php

	# This is a function block
	
	# pre: when request to see a detail of a ticket
	# post: check if the ticket exists
	function checkTicket($id){
		$conn = connectToDB("data/dbInformation.txt");

		$id = $conn->quote($id);

		return $conn->query("SELECT COUNT(*)
							FROM dbo.ticket
							WHERE id LIKE $id")->fetchColumn() == 1;
	}

	# pre: when get a row of data in SQL that might be null
	# post: return "NONE" when the data from SQL is null
	function nullTester($data){
		if($data == null || $data == "NULL"){
			return "none";
		}

		return $data;
	}

	# pre: when need to show the positions provided by the company
	# post: if there is no positions provided yet, show the message, otherwise show
	#		detailed information about the position
	function positions($id){
		$data = loadData($id);

		$positions = $data->getElementsByTagName("position");
		if($positions->length <= 0){ # if no position is provided yet
			?>
				<h2>No Position is Provided Now.</h2>
			<?php
		}else{
			foreach($positions as $position){
				?>
					<div class="info panel panel-info">
						<div class="panel-heading"><?=$position->getAttribute("name")?></div>
						<div class="panel-body">
							<ul>
								<li>招募人數: <?=$position->getAttribute("amount")?> 名</li>
								<li>工作內容: <?=$position->getElementsByTagName("about")->item(0)->nodeValue?></li>
								<li>實習地點: <?=$position->getElementsByTagName("location")->item(0)->nodeValue?></li>
								<li>
									應徵要求:
										<ol>
											<?php
												$requirements = $position->getElementsByTagName("requirement");
												foreach($requirements as $requirement){
													?>
														<li><?=$requirement->nodeValue?></li>
													<?php
												}
											?>
										</ol>
								</li>
								<li>實習薪資: <?php
									$salary = $position->getElementsByTagName("paid")->item(0);
									if($salary->getAttribute("paid") == "true"){
										print $salary->nodeValue;
									}else if($salary->getAttribute("paid") == "false"){
										print "不給薪";
									}else{
										print $salary->getAttribute("paid");
									}
								?></li>
								<li>備　註　: <?=$position->getElementsByTagName("other")->item(0)->nodeValue?></li>
							</ul>
						</div>
					</div>
				<?php
			}
		}
	}

	# pre: when request data from xml data
	# post: returns a DOMDocument object
	function loadData($id){
		$xml = new DOMDocument();
		$xml->load("data/tickets/".trim($id).".xml");

		return $xml;
	}

	# pre: when need to show the comments made by the users
	# post: show all the comments by time
	function comments($id){
		$xml = loadData($id);

		$logs = $xml->getElementsByTagName("log");

		foreach($logs as $log){
			?>
				<div class="comment panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="log_status col-sm-1"><?=$log->getAttribute("status")?></div>
							<div class="log_status_info col-sm-11">
								<ul>
									<li>From: <?=$log->getAttribute("author")?></li>
									<li>Time: <?=date("d-M-Y D H:i:s a", $log->getAttribute("time"))?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<p><?=$log->getElementsByTagName("text")->item(0)->nodeValue?></p>
						<hr />
						<div>
							<p class="log_file_tags">image</p>
							<?php
								$imgs = $log->getElementsByTagName("images");
								foreach($imgs as $img){
									?>
										<img src=<?=$img->nodeValue?> />
									<?php
								}
							?>
							<p class="log_file_tags">files</p>
							<?php
								$files = $log->getElementsByTagName("file");
								foreach($files as $file){
									?>
										<a class="log_file_link" target="_blank" href=<?=$file->nodeValue?>><?=$file->getAttribute("name")?></a>
									<?php
								}
							?>
						</div>
					</div>
				</div>
			<?php
		}
	}

?>