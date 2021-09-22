<?php
$inc_wp_response = file_get_contents('php://input',true);
 $log = file_put_contents('email_test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
    
//$inc_wp_response = '{"from":"TG400 < cal4caredev2@gmail.com >","subject":"SMS from +601128166338 on TG400$s port 3","message":{"body":"Lorem ipsum dolor sit amet, consectetur adipiscing elit,@#$%^&*()_ 09876543210\n\n","type":"text","stripped_images":[],"embedded_images":[],"options":{"strip-embedded":true}},"to":"[\"sms@pipe.mconnectapps.com\"]"}';


$inc_wp_response = json_decode($inc_wp_response);

echo $inc_wp_response; exit;

$from = $inc_wp_response->from;
$subject = $inc_wp_response->subject;
$message = $inc_wp_response->message->body;

$from = str_replace('SMS from ','',$subject);
$from = str_replace(' on TG400$s port 3','',$from);

$to = $inc_wp_response->to;

$element_data = array('action' => 'generate_incoming_message','from'=>$from,'message'=>$message,'message_time'=>$message_time,'user_id'=>'847');
$fields = array('operation' =>'chat','moduleType' => 'chat','api_type' => 'web', 'element_data' => $element_data);


// print_r($fields); exit;

$post = http_build_query($fields);

//echo $post; exit;

$url = 'https://omni.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
print_r($result);
