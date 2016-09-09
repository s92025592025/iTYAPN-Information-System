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
  set_include_path(get_include_path() . PATH_SEPARATOR . '/google-api-php-client-master/src');
  include '/google-api-php-client-master/src/Google/autoload.php';

  print "1";
  $client = new Google_Client();
  print "2";
  $client->setAuthConfig('client_secrets.json');
  print "3";
  $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
  print "4";
  $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
  print "5";
  $client->setAccessType("offline");
  print "6";
  $client->authenticate("1/T0Sm4vrzonTFakUQ8I2Xw0b6z9oLS3cotxAbD9IS7-0");
  print "7";
  $access_token = $client->getAccessToken();
  print $access_token;
?>