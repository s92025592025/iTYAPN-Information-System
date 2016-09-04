<?php
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://api.imgur.com/3/credits.xml?_fake_status=200");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$xml = curl_exec($curl);

	curl_close($curl);

	if($xml){
		header("Content-type: text/xml");
		print $xml;
	}else{
		print $xml;
	}
?>