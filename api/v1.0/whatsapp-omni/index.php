<?php
$inc_wp_response = file_get_contents('php://input',true);
$log = file_put_contents('email_test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);




$inc_wp_response = json_decode($inc_wp_response);


$action = $inc_wp_response->element_data->action;

if($action == 'sendWhatsappText'){
	$from = $inc_wp_response->element_data->from;
	$phone = $inc_wp_response->element_data->to;
	$message_body = $inc_wp_response->element_data->message;
	$company= $inc_wp_response->element_data->company;
	$username = $inc_wp_response->element_data->username;
	$is_group = $inc_wp_response->element_data->is_group;
$element_data = array('action' => 'sendWhatsappText','from'=>$from,'to'=>$phone,'message'=>$message_body,'company'=>$company,'username'=>$username,'is_group'=>$is_group );
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
$post = http_build_query($fields);
$url = 'https://assaabloycc.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
//print_r($result);
	return $result;	
} if($action == 'setWebHook'){
$web_hook = $inc_wp_response->element_data->web_hook;
$wp_num = $inc_wp_response->element_data->wp_num;	
$element_data = array('action' => 'setWebHook','wp_num'=>$wp_num,'web_hook'=>$web_hook);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
	// print_r($fields); exit;
$post = http_build_query($fields);
$url = 'https://assaabloycc.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
	//print_r($result);
	return $result;	
} else {
	

	
$from = $inc_wp_response->element_data->from;
$phone = $inc_wp_response->element_data->to;
$message_body = $inc_wp_response->element_data->file_url;
$filename = $inc_wp_response->element_data->filename;	
$company= $inc_wp_response->element_data->company;
	$caption= $inc_wp_response->element_data->caption;
$username = $inc_wp_response->element_data->username;
$is_group = $inc_wp_response->element_data->is_group;	
	
	
$element_data = array('action' => 'send_chat_message_media_unoff','from'=>$from,'to'=>$phone,'file_url'=>$message_body,'company'=>$company,'username'=>$username,'is_group'=>$is_group,'caption'=>$caption );
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);


$post = http_build_query($fields);
$url = 'https://assaabloycc.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
	//print_r($result);
	return $result;
}
