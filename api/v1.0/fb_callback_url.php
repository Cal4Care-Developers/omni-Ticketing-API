<?php
 $inc_fb_response = file_get_contents('php://input',true);
 $log = file_put_contents('fb.txt', $inc_fb_response.PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents('fb.txt', json_encode($_REQUEST).PHP_EOL , FILE_APPEND | LOCK_EX);
/*$inc_fb_response = explode('&',$inc_fb_response);
if( $inc_fb_response[0] == 'EventType=READ' || $inc_fb_response[0] == 'EventType=DELIVERED' || $inc_fb_response[1] == 'SmsStatus=sent'){
	
	if($inc_fb_response[1] == 'SmsStatus=sent'){
		$message_id = str_replace("MessageSid=","",$inc_fb_response[6]);
		$message_status = 'SENT';
	} else {
		$message_id = str_replace("MessageSid=","",$inc_fb_response[7]);
		$message_status = str_replace("EventType=","",$inc_fb_response[0]);
	}
	
	
	$element_data = array('action' => 'change_wp_status','MessageId'=>$message_id,'status'=>$message_status);
	$fields = array('operation' =>'wpchat','moduleType' => 'wpchat','api_type' => 'web', 'element_data' => $element_data);
	
	
} else {
	
	
$inc_message = str_replace("Body=","",$inc_fb_response[4]);
$inc_message = str_replace("+"," ",$inc_message);
$inc_message = urldecode($inc_message);
$inc_message = str_replace("%0","\n",$inc_message);
$from_num = str_replace("From=whatsapp%3A%2B","+",$inc_fb_response[9]);
$to_num = str_replace("To=whatsapp%3A%2B","+",$inc_fb_response[5]);
$message_id = str_replace("MessageSid=","",$inc_fb_response[7]);

$element_data = array('action' => 'generate_incoming_wp','From'=>$from_num,'message'=>$inc_message,'To'=>$to_num,'MessageId'=>$message_id);
$fields = array('operation' =>'wpchat','moduleType' => 'wpchat','api_type' => 'web', 'element_data' => $element_data);
}





$post = http_build_query($fields);
$url = 'http://omni.mconnectapps.com/api/v1.0/index_new.php';

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);

print_r($result);*/
