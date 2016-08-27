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
	function editPosition(){};

	# pre: when the user request to edit the positions
	# post: display a page for the user to edit the positions in the ticket
	function displayPage(){
		if(!isset($_GET["id"]) || !is_numeric($_GET["id"])){
			showErrorMessage();
			die();
		}

		HTMLHeader("修改職位 Position Edit", "", "");
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
									<button type="submit" class="btn btn-primary">新增職位 Add Position</button>
								</div>
							</div>
					</form>
				</div>
				<div id="provided-position">
					<fieldset>
						<legend><h2>現有職位 Provided Position</h2></legend>
					</fieldset>
					<form class="form-horizontal" action="editPositions.php" method="POST">
							
					</form>
				</div>
			</div>
		<?php
		HTMLFooter();
	};
?>