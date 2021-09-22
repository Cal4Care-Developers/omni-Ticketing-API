<?php
$company_name = $_GET['company_name'];
$username = $_GET['username'];
$password = $_GET['password'];
$sender_id = $_GET['sender_id'];
$phone_no= $_GET['phone_no'];
$country_code = $_GET['country_code'];
 $message = $_GET['message'];
 //$admin_id = $_GET['admin_id'];
//$admin_id=  base64_decode($admin_id);

//$post_data = json_encode(array("comp_name"=>$comp_name, "username"=>$username,"password"=>$password, "sender_id"=>$sender_id,"from_no"=>$from_no, "sender_id"=>$to_no,"country_code"=>$country_code));



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://assaabloycc.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\r\n   \"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJhdWQiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJpYXQiOjE1ODcwNDcxNDMsIm5iZiI6MTU4NzA0NzE0MywiZXhwIjoxNTg3MDY1MTQzLCJhY2Nlc3NfZGF0YSI6eyJ0b2tlbl9hY2Nlc3NJZCI6IjQ1IiwidG9rZW5fYWNjZXNzTmFtZSI6ImRlbW9fYWNjIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.mHVhDZdmi4DGCooFLh2jo6vUrohtc9HaAsxjgnYNK4o\",  \r\n    \"operation\": \"chat\",\r\n    \"moduleType\": \"chat\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"send_sms\",\r\n        \"sender_id\":\"$sender_id\",\r\n        \"phone_no\":\"$phone_no\",\r\n        \"country_code\":\"$country_code\",\r\n        \"message\":\"$message\",\r\n        \"company_name\":\"$company_name\",\r\n        \"username\":\"$username\",\r\n        \"password\":\"$password\"\r\n        \r\n    }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>