<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		editPosition();
	}else{
		displayPage();
	}
?>

<?php
	# this is a function bolck

	# pre: when the user tried to delete or add a new position
	# post: add or delete the position in xml
	function editPosition(){
		if(!isset($_POST["mode"])){
			showErrorMessage();
			die();
		}

		switch($_POST["mode"]){
			case "add":
				addPosition();
				break;
			case "delete":
				deletePosition();
				break;
			case "edit":
				showEditPage();
				break;
			case "editComplete":
				editPosition();
				break;
			default:
				showErrorMessage();
				die();
				break;
		}
	};

	# pre: when the user just finished editing
	# post: edit the position in XML
	function editPosition(){
		/*
		**	REMEMBER TO KEEP A LOG WHEN POSITION IS EDITED
		*/

		if(!isset($_POST["ticket_id"]) || !isset($_POST["position_id"]) || 
			!isset($_POST["position_name"]) || !is_numeric($_POST["ticket_id"]) ||
			!is_numeric($_POST["position_id"]) || $_POST["position_name"] == ""){
			showErrorMessage();
			die();
		}
	}

	# pre: when a position is clicked to edit
	# post: show a page with editable inputs to edit
	function showEditPage(){
		if(!isset($_POST["ticket_id"]) || !isset($_POST["position_id"]) ||
			!is_numeric($_POST["ticket_id"]) || !is_numeric($_POST["position_id"])){
			showErrorMessage();
			die();
		}

		$ticket = new DOMDocument();
		$ticket->load("data/tickets/".$_POST["ticket_id"].".xml");
		$position = "";
		foreach($ticket->getElementsByTagName("position") as $temp){
			if($temp->getAttribute("id") == $_POST["position_id"]){
				$position = $temp;
			}
		}

		if(!$position){
			showErrorMessage();
			die();
		}

		HTMLHeader("編輯 Edit", "", "");
		?>
			<div class="container">
				<fieldset>
					<ledgend><h2>修改職位 Edit Position</h2></ledgend>
					<span class="help-block">有紅色星星(<span>*</span>)標示者為必填</span>
				</fieldset>
				<form class="form-horizontal" action="editPositions.php" method="POST">
						<div class="form-group must-fill">
							<label class="control-label col-sm-2">職位名稱<span>*</span>: </label>
							<div class="col-sm-6">
								<input class="form-control" name="position_name" placeholder="請輸入職位名稱(必填)" required value=<?=$position->getAttribute("name")?> / >
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">招募人數: </label>
							<div class="col-sm-6">
								<input class="form-control" type="number" name="amount" placeholder="請輸入公司欲招募人數" value=<?=$position->getAttribute("amount")?> />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">實習地點: </label>
							<div class="col-sm-6">
								<input class="form-control" name="location" placeholder="請輸入實習工作地點" value=<?=$position->getElementsByTagName("location")->item(0)->nodeValue?> >
								<span class="help-block">如不清楚詳細地址，則輸入所在縣市或地區</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">提供薪資: </label>
							<div class="col-sm-6">
								<input class="form-control" name="salary" placeholder="請輸入公司願提共的支薪" value=<?php
									if($position->getElementsByTagName("paid")->item(0)->getAttribute("paid") == "true"){
										print $position->getElementsByTagName("paid")->item(0)->nodeValue;
									}else if($position->getElementsByTagName("paid")->item(0)->getAttribute("paid") == "false"){
										print "no";
									}else{
										print $position->getElementsByTagName("paid")->item(0)->getAttribute("paid");
									}
								?> >
								<span class="help-block">請以新台幣為主並加上單位。如不提共支薪，請輸入"no"(不含引號)。如不清楚或公司未說明，請輸入"unknown"(不含引號)。如有薪資外福利，請在備註中描述。請勿填入此欄。</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">工作內容: </label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="5" name="about" placeholder="請輸入此實習的工作內容"><?=$position->getElementsByTagName("about")->item(0)->nodeValue?></textarea>
								<span class="help-block">系統將原汁原味，完整呈現此蘭內容，如要列點，請自行排好版</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">徵求條件: </label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="5" name="requirements" placeholder="請輸入徵人條件"><?php
										$requirements = "";
										$flag = true;
										foreach($position->getElementsByTagName("requirement") as $require){
											if($flag){
												$requirements .= $require->nodeValue; 
											}else{
												$requirements .= ";" . $require->nodeValue;
											}

											$flag = false;
										}

										print $requirements;
									?></textarea>
								<span class="help-block">如果有列點，請將各點以分號分開。不須將各點編上編號，系統將自動編排好。</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">備 註: </label>
							<div class="col-sm-6">
								<textarea class="form-control" name="other" rows="5"><?=$position->getElementsByTagName("about")->item(0)->nodeValue?></textarea>
								<span class="help-block">如果除了薪資之外，公司還有提供其他福利，請列舉於此。如有其他備註事項，也請一並記錄於此。</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-9">
							<input type="hidden" name="mode" value="editComplete">
							<input type="hidden" name="ticket_id" value=<?=$_POST["ticket_id"]?>>
							<input type="hidden" name="position_id" value=<?=$_POST["position_id"]?>>
								<button type="submit" class="btn btn-warning">修改職位 Edit Position</button>
							</div>
						</div>
				</form>
			</div>
		<?php	
		HTMLFooter();
	}

	# pre: when sent in a "delete" mode
	# post: delete the clicked position
	function deletePosition(){
		if(!isset($_POST["ticket_id"]) || !isset($_POST["position_id"]) ||
			!is_numeric($_POST["ticket_id"]) || !is_numeric($_POST["position_id"])){
			showErrorMessage();
			die();
		}

		$ticket = new DOMDocument();
		$ticket->load("data/tickets/".$_POST["ticket_id"].".xml");

		$delete = -1;
		for($i = 0; $i < $ticket->getElementsByTagName("position")->length; $i++){
			if($ticket->getElementsByTagName("position")->item($i)->getAttribute("id") == $_POST["position_id"]){
				$delete = $i;
			}
		}

		if($delete >= 0){
			$ticket->getElementsByTagName("positions")->item(0)->removeChild($ticket->getElementsByTagName("position")->item($delete));
		}

		if($ticket->save("data/tickets/" . $_POST["ticket_id"] . ".xml")){
			header("Location: ticket.php?id=".$_POST["ticket_id"]);
			die();
		}else{
			showErrorMessage();
			die();
		}
	}

	# pre: when the mode was set to "add"
	# post: add the position into xml file
	function addPosition(){
		if(!isset($_POST["ticket_id"]) || !isset($_POST["position_name"]) || !is_numeric($_POST["ticket_id"])){
			showErrorMessage();
			die();
		}

		$ticket = new DOMDocument();
		$ticket->load("data/tickets/".$_POST["ticket_id"].".xml");
		
		# set the attribute of position tag
		$position = $ticket->createElement("position");
		$position->setAttribute("name", $_POST["position_name"]);
		$position->setAttribute("amount", $_POST["amount"]);

		$id = -1;
		foreach($ticket->getElementsByTagName("position") as $temp){
			if($id < $temp->getAttribute("id") + 0){
				$id = $temp->getAttribute("id") + 0;
			}
		}

		$position->setAttribute("id", $id + 1);

		# set up requirements tag
		$requirements = $ticket->createElement("requirements");
		if(trim($_POST["requirements"])){
			foreach(explode(";", trim($_POST["requirements"])) as $temp){
				$requirements->appendChild($ticket->createElement("requirement", trim($temp)));
			}
		}

		# set about tag
		$about = $ticket->createElement("about", $_POST["about"]);
		# set location tag
		$location = $ticket->createElement("location", $_POST["location"]);
		# set paid tag
		$paid = $ticket->createElement("paid");
		if(strtolower($_POST["salary"]) == "no"){
			$paid->setAttribute("paid", "false");
		}else if(strtolower($_POST["salary"]) == "unknown"){
			$paid->setAttribute("paid", "unknown");
		}else{
			$paid->setAttribute("paid", "true");
			$paid->appendChild($ticket->createTextNode($_POST["salary"]));
		}
		# set other tag
		$other = $ticket->createElement("other", $_POST["other"]);
		# put all of them together
		$position->appendChild($requirements);
		$position->appendChild($about);
		$position->appendChild($location);
		$position->appendChild($paid);
		$position->appendChild($other);
		$ticket->getElementsByTagName("positions")->item(0)->appendChild($position);

		if(!$ticket->save("data/tickets/".$_POST["ticket_id"].".xml")){
			showErrorMessage();
			die();
		}else{
			header("Location: ticket.php?id=".$_POST["ticket_id"]);
			die();
		}
	}

	# pre: when the user request to edit the positions
	# post: display a page for the user to edit the positions in the ticket
	function displayPage(){
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}

		HTMLHeader("修改職位 Position Edit", "editPositions.css", "");
		?>
			<div class="container">
				<h1>修改職位 Edit Position</h1>
				<div id="add-position">
					<fieldset>
						<ledgend><h2>新增職位 Add Position</h2></ledgend>
						<span class="help-block">有紅色星星(<span>*</span>)標示者為必填</span>
					</fieldset>
					<form class="form-horizontal" action="editPositions.php" method="POST">
							<div class="form-group must-fill">
								<label class="control-label col-sm-2">職位名稱<span>*</span>: </label>
								<div class="col-sm-6">
									<input class="form-control" name="position_name" placeholder="請輸入職位名稱(必填)" required / >
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">招募人數: </label>
								<div class="col-sm-6">
									<input class="form-control" type="number" name="amount" placeholder="請輸入公司欲招募人數" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">實習地點: </label>
								<div class="col-sm-6">
									<input class="form-control" name="location" placeholder="請輸入實習工作地點" />
									<span class="help-block">如不清楚詳細地址，則輸入所在縣市或地區</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">提供薪資: </label>
								<div class="col-sm-6">
									<input class="form-control" name="salary" placeholder="請輸入公司願提共的支薪"/>
									<span class="help-block">請以新台幣為主並加上單位。如不提共支薪，請輸入"no"(不含引號)。如不清楚或公司未說明，請輸入"unknown"(不含引號)。如有薪資外福利，請在備註中描述。請勿填入此欄。</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">工作內容: </label>
								<div class="col-sm-6">
									<textarea class="form-control" rows="5" name="about" placeholder="請輸入此實習的工作內容"></textarea>
									<span class="help-block">系統將原汁原味，完整呈現此蘭內容，如要列點，請自行排好版</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">徵求條件: </label>
								<div class="col-sm-6">
									<textarea class="form-control" rows="5" name="requirements" placeholder="請輸入徵人條件"></textarea>
									<span class="help-block">如果有列點，請將各點以分號分開。不須將各點編上編號，系統將自動編排好。</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">備 註: </label>
								<div class="col-sm-6">
									<textarea class="form-control" name="other" rows="5"></textarea>
									<span class="help-block">如果除了薪資之外，公司還有提供其他福利，請列舉於此。如有其他備註事項，也請一並記錄於此。</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-9">
								<input type="hidden" name="mode" value="add">
								<input type="hidden" name="ticket_id" value=<?=$_GET["id"]?>>
									<button type="submit" class="btn btn-primary">新增職位 Add Position</button>
								</div>
							</div>
					</form>
				</div>
				<div id="provided-position">
					<fieldset>
						<legend><h2>現有職位 Provided Position</h2></legend>
					</fieldset>
						<?php
							$ticket = new DOMDocument();
							$ticket->load("data/tickets/".$_GET["id"].".xml");
							if(!$ticket->getElementsByTagName("position")->length){
								?>
									<h3>No Position Provided Now.</h3>
								<?php
							}else{
								showCurrentPosition();
							}
						?>
				</div>
			</div>
		<?php
		HTMLFooter();
	};

	# pre: if there is position provided by the company
	# post: show all the positions the company provided
	function showCurrentPosition(){
		$ticket = new DOMDocument();
		$ticket->load("data/tickets/".$_GET["id"].".xml");
		$positions = $ticket->getElementsByTagName("position");

		?>
			<div class="panel-group" id="accord">
		<?php

		foreach($positions as $position){
			?>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accord" href="#position<?=$position->getAttribute("id")?>"><?=$position->getAttribute("name")?></a>
							<form class="form-horizontal" action="editPositions.php" method="POST">	
								<button>
									<input type="hidden" name="mode" value="delete">
									<input type="hidden" name="ticket_id" value=<?=$_GET["id"]?>>
									<input type="hidden" name="position_id" value=<?=$position->getAttribute("id")?>>
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</form>
							<form class="form-horizontal" action="editPositions.php" method="POST">	
								<button>
									<input type="hidden" name="mode" value="edit">
									<input type="hidden" name="ticket_id" value=<?=$_GET["id"]?>>
									<input type="hidden" name="position_id" value=<?=$position->getAttribute("id")?>>
									<span class="glyphicon glyphicon-edit"></span>
								</button>
							</form>
						</h4>
					</div>
					<div class="panel-collapse collapse" id="position<?=$position->getAttribute("id")?>">
						<div class="panel-body">
							<ul>
								<li>招募人數: <?=$position->getAttribute("amount")?> 名</li>
								<li>工作內容: <?=$position->getElementsByTagName("about")->item(0)->nodeValue?></li>
								<li>實習地點: <?=$position->getElementsByTagName("location")->item(0)->nodeValue?></li>
								<li>
									應徵要求:
										<ol>
											<?=showPositionRequirement($position)?>
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
				</div>
			<?php
		}

		?>
			</div>
		<?php
	}

	# pre: when there is requirement for this job
	# post: show the requirements in lists
	function showPositionRequirement($position){
		foreach($position->getElementsByTagName("requirement") as $require){
			?>
				<li><?=$require->nodeValue?></li>
			<?php
		}
	}
?>