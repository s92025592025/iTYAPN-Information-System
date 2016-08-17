<?php
	include("common.php");

	checkLoggedIn();

	session_start();

	HTMLHeader("New Ticket - Step 1", "new_ticket_search_company.css", "new_ticket_search_company.js");

	?>
		<div class="container">
			<h1>New Ticket - Step one</h1>
			<h2>請從先從資料庫中選擇已有的公司，如未搜尋到再新增進資料庫。 請盡量用公司<span>"全名"</span>搜尋以免找不到想要的結果</h2>

			<form role="form" class="form-inline">
				<div class="form-group">
					<input type="search" list="companies" class="form-control" id="search" placeholder="Search By Typing in Here" />
					<button type="search" id="search_btn" class="btn btn-success">GO!</button>
				</div>
			</form>

			<div id="search_result" class="info_panel" style="display: none">
				<div class="panel panel-default">
					<div class="panel-heading">搜尋結果 Search Results</div>
					<div class="panel-body"></div>
				</div>
			</div>

		</div>

	<?php

	HTMLFooter();

?>