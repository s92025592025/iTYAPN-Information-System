<?php
	header("Content-type: text/plain");
	print(check());
?>

<?php
	function check(){
		if(isset($_COOKIE["name"])){
			$loginInfo = new DOMDocument();
			$loginInfo->load("loginInformation.xml");
			header("Content-type: text/plain");
			foreach($loginInfo->getElementsByTagName("name") as $name){
				if($name->nodeValue == $_COOKIE["name"]){
					return "TRUE";
				}
			}
			return "FALSE";
		}
	}
?>