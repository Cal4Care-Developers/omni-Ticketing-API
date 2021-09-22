<?php
$inc_wp_response = file_get_contents('php://input',true);
ob_start('ob_gzhandler');
//$inc_wp_response = json_encode($inc_wp_response, JSON_UNESCAPED_SLASHES);
$log = file_put_contents('email_test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
//$inc_wp_response = "{\n  \"data\": {\n    \"event_type\": \"message.received\",\n    \"id\": \"1aeb5458-3f55-4416-bff0-a670801bba53\",\n    \"occurred_at\": \"2021-06-29T17:38:13.409+00:00\",\n    \"payload\": {\n      \"cc\": [],\n      \"completed_at\": null,\n      \"cost\": null,\n      \"direction\": \"inbound\",\n      \"encoding\": \"GSM-7\",\n      \"errors\": [],\n      \"from\": {\n        \"carrier\": \"\",\n        \"line_type\": \"\",\n        \"phone_number\": \"rashdt\"\n      },\n      \"id\": \"ad74a15b-04f1-4f00-bfc7-45b0a7e23037\",\n      \"media\": [],\n      \"messaging_profile_id\": \"40017591-2255-418d-a1e8-e36ac10c7bf5\",\n      \"organization_id\": \"3fd39c5e-05c7-4a22-b4ad-4f0c49f8ba05\",\n      \"parts\": 1,\n      \"received_at\": \"2021-06-29T17:38:13.289+00:00\",\n      \"record_type\": \"message\",\n      \"sent_at\": null,\n      \"tags\": [],\n      \"text\": \"TestSMSd\",\n      \"to\": [\n        {\n          \"carrier\": \"Telnyx\",\n          \"line_type\": \"Wireless\",\n          \"phone_number\": \"+61480090209\",\n          \"status\": \"webhook_delivered\"\n        }\n      ],\n      \"type\": \"SMS\",\n      \"valid_until\": null,\n      \"webhook_failover_url\": null,\n      \"webhook_url\": \"https://assaabloycc.mconnectapps.com/api/v1.0/ManagedserviceSMS/index.php\"\n    },\n    \"record_type\": \"event\"\n  },\n  \"meta\": {\n    \"attempt\": 1,\n    \"delivered_to\": \"https://assaabloycc.mconnectapps.com/api/v1.0/ManagedserviceSMS/index.php\"\n  }\n}";

$inc_wp_response = json_decode($inc_wp_response,true);
$from = $inc_wp_response['data']['payload']['from']['phone_number'];
$message = $inc_wp_response['data']['payload']['text'];
$to = $inc_wp_response['data']['payload']['to'][0]['phone_number'];
$message_time = $inc_wp_response['data']['payload']['received_at'];
$messageId = $inc_wp_response['data']['id'];
$element_data = array('action' => 'generate_incoming_message_from_outer','from'=>$from,'message'=>$message,'message_time'=>$message_time,'to'=>$to,'messageId'=>$messageId);
$fields = array('operation' =>'chat','moduleType' => 'chat','api_type' => 'web', 'element_data' => $element_data);
$post = http_build_query($fields);
$url = 'https://assaabloycc.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
print_r($result); exit;; 



