<?php
 /* $filename = "/img/logo.jpg";
  $post = array("title" => $filename,
                "Content-type" => "image/jpeg",
                "Content-Length" => filesize($filename));
  $header = array("Authorization" => "Bearer APmJenSy9iMlJYfX-OQb0ZJL");
  $host = "https://www.googleapis.com/upload/drive/v2/files?uploadType=media";

  $curl = curl_init();
  curl_setopt($curl,  CURLOPT_URL, $host);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

  $output = curl_exec($curl);
  curl_close($output);

  print $output;
  */
?>

<?php 
  $post = array("grant_type" => "refresh_token",
                "client_id" => "",
                "client_secret" => "",
                "refresh_token" => "",
                "Content-length" => "163");

  $header = array("Content-type" => "application/x-www-form-urlencoded",
                  "Content-length" => "163");

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "www.googleapis.com/oauth2/v4/token");
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_POST, $post);

  $output = curl_exec($curl);
  curl_close($curl);

  print $output;

	if($xml){
		header("Content-type: text/xml");
		print $xml;
	}else{
		print $xml;
	}
?>