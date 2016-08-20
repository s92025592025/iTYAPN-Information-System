<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("New Ticket", "new_ticket_search_company.css", "new_ticket_search_company.js");

	?>
		<div class="container">
			<h1>New Ticket - Step one</h1>
			<h2>請從先從資料庫中選擇已有的公司，如未搜尋到再新增進資料庫。 請盡量用公司<span>"全名"</span>搜尋以免找不到想要的結果</h2>

			<div class="form-inline">
				<div class="form-group">
					<input type="search" list="companies" class="form-control" id="search" placeholder="Search By Typing in Here" />
					<button id="search_btn" class="btn btn-success">GO!</button>
				</div>
			</div>

			<div id="search_result" class="info_panel" style="display: none">
				<div class="panel panel-default">
					<div class="panel-heading">搜尋結果 Search Results</div>
					<div class="panel-body"></div>
				</div>
			</div>

			<div id="company_detail" style="display: none">
				<h3>請確認公司資料是否正確</h3>
				<h4></h4><!-- company chinese name -->
				<div class="panel panel-info">
					<div class="panel-heading">詳細資料 Details</div>
					<div class="panel-body">
						<div id="company_detail_info"></div>
						<div id="company_map">
							<img src="" />
						</div>
					</div>
				</div>

				<div class="well">
					<h4>其他聯絡方式? Other contacts?</h4>
					<p>除了上述的聯絡方式外，還有其他或指定的聯絡人及聯絡方法嗎?</p>
					<form id="data_input" class="from-horizontal">
						<div class="checkbox">
							<label><input id="more_contact" type="checkbox"> 聯絡方法與詳細資料中相同</label>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="contactee">聯絡人: </label>
							<div class="col-sm-10">
								<input id="contactee" class="form-control" placeholder="請輸入對方公司聯絡人">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="email">Email: </label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="email" placeholder="請輸入聯絡人Email">
							</div>
						</div>
						<p id="phone_warning"></p>
						<div class="form-group">
							<label class="control-label col-sm-2">連絡電話: </label>
							<div class="col-sm-10">
								<input type="phone" class="form-control" id="phone" placeholder="請依照 (02)12345678 的格式輸入">
							</div>
						</div>
					</form>
					<p>&nbsp</p>
				</div>
				<form class="from-horizontal">
					<div class="form-group">
						<button id="send" class="btn btn-warning col-sm-offset-4 col-sm-offset-8" disabled="disabled">我確定資料都是對的，下一步</button>
					</div>
				</form>
			</div>

		</div>

	<?php

	HTMLFooter();

?>