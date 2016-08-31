<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("New Company", "createNewCompany.css", "createNewCompany.js");

	?>

	<div class="container">
		<h1>新增公司 Add New Company</h1>
		<p>有紅色星星(<span>*</span>)標記欄位為必填</p>

		<form class="form-horizontal" action="data/addCompany.php" method="POST">
			<div class="form-group must-fill">
				<label class="control-label col-sm-2">中文名字<span>*</span>: </label>
				<div class="col-sm-10">
					<input class="form-control" name="c_name" placeholder="請輸入公司&quot;完整&quot;中文名字(必填)" required />
				</div>
			</div>
			<div class="form-group must-fill">
				<label class="control-label col-sm-2">英文名字<span>*</span>: </label>
				<div class="col-sm-10">
					<input class="form-control" name="e_name" placeholder="請輸入公司&quot;完整&quot;英文名字(必填)" required />
				</div>
			</div>
			<div class="form-group must-fill">
				<label class="control-label col-sm-2">公司地址<span>*</span>: </label>
				<div class="col-sm-10">
					<input class="form-control" name="address" placeholder="請輸入公司&quot;完整&quot;地址(須包括縣市，必填)" required />
				</div>
			</div>
			<div class="form-group must-fill">
				<label class="control-label col-sm-2">產業類別<span>*</span>: </label>
				<div class="col-sm-10">
					<select class="form-control" name="genre" required>
						<?php
							$genre = array("電子科技╱資訊╱軟體╱半導體", "礦業／土石能源採取", "金融投顧／保險相關", "運輸物流╱倉儲╱貿易", "醫療照護／環境衛生", "一般服務業", "餐飲／住宿服務", "教育╱出版╱藝文相關", "一般傳統製造", "政治／宗教／社福", "法律／會計／顧問／研發／設計", "建築營造／不動產相關", "農林漁牧／水電資源", "旅遊╱休閒╱運動", "大眾傳播相關", "批發／零售／傳直銷");

							foreach($genre as $each){
								?>
									<option><?=$each?></option>
								<?php
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">公司電話: </label>
				<div class="col-sm-10">
					<input class="form-control phone-num" name="phone" placeholder="請依照(02)12345678的格式輸入">
					<span class="help-block phone-warning"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">電子信箱: </label>
				<div class="col-sm-10">
					<input class="form-control email" name="email" placeholder="請輸入公司電子信箱">
					<span class="help-block email-warning"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">客服專線: </label>
				<div class="col-sm-10">
					<input class="form-control" name="customer_service" placeholder="請輸入客服聯絡方式">
					<span class="help-block">客服聯絡方式可以是電話，電子信箱，如是線上填表，請輸入online form。如有其他，請載名</span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">公司傳真: </label>
				<div class="col-sm-10">
					<input class="form-control phone-num" name="fax" placeholder="請依照(02)12345678的格式輸入">
					<span class="help-block phone-warning"></span>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">其他稱呼: </label>
				<div class="col-sm-10">
					<input class="form-control" name="abbre" placeholder="請輸入此公司常見俗稱、暱稱、簡稱。如有多項，請用分號(;)區隔">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<button type="submit" class="btn btn-info" disabled="disabled">Submit and Create new Ticket!</button>
				</div>
			</div>


		</form>
	</div>

	<?php

	HTMLFooter();
?>