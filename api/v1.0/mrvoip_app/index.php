<?php
header('Access-Control-Allow-Origin: *');
$inc_wp_response = file_get_contents('php://input',true);
//$inc_wp_response = '{"access_token":"HTTPS://CAL4CAREDEMO.3CX.SG/P/1FSV3BA7X3/9RNT3IR3RX107"}';
$inc_wp_response = json_decode($inc_wp_response);
$log = file_put_contents('email_test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
//$inc_wp_response =  '{"access_token":"HTTPS://CAL4CAREDEMO.3CX.SG/P/1FSV3BA7X3/9RNT3IR3RX107"}';
$inc_wp_response = json_decode($inc_wp_response);
$inc_wp_response = $inc_wp_response->access_token;
//echo $inc_wp_response; exit;
// using to read File function to get content
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $inc_wp_response);    // get the url contents
$data = curl_exec($ch); // execute curl request
curl_close($ch);
$xml = simplexml_load_string($data);
$extension = (string) $xml->Extension[0];
$name = (string) $xml->AccountName[0];
$authKey = (string) $xml->AuthID[0];
$authPass = (string) $xml->AuthPass[0];
$pbxAddr = (string) $xml->PBXPublicAddr[0];
$port = (string) $xml->PBXSipPort[0];
// Check On Database For user data
$element_data = array('action' => 'checkForValidQR','rootfrom'=>'mobile_apps','extension'=>$extension,'authName'=>$authKey,'authPass'=>$authPass);
$fields = array('operation' =>'chat','moduleType' => 'chat','api_type' => 'web', 'element_data' => $element_data);
$post = http_build_query($fields);
$url = 'https://omni.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result);
if($result->user_name !=='' && $result->user_name !==null){
	$post = array(
		'status' => '200',
		'extension' => $extension,
		'name' => $name,
		'authKey' => $authKey,
		'authPass' => $authPass,
		'pbxAddr' => $pbxAddr,
		'port' => $port,
		'enc' => $result->enc,
		'u_id' => $result->u_id
		);	
	$posts = json_encode($post);
	print_r($posts);
}else {
	$post = array(
		'status' => '404'
	);	
	$posts = json_encode($post);
	print_r($posts);
}
