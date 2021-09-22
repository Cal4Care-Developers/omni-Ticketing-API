<?php
$token="1214270827:AAF4_IMkMd11LjNKW-YpLhYgLYbLXzoR-PY";
$teleUrl='https://api.telegram.org/bot'.$token;

$update = file_get_contents('php://input');
file_put_contents("TELE_text.txt",$update);


//$update='{"update_id":521159688,"message":{"message_id":430,"from":"id":1700374071,"is_bot":false,"first_name":"kiruba","last_name":"karan","language_code":"en"},"chat":{"id":1700374071,"first_name":"kiruba","last_name":"karan","type":"private"},"date":1631088207,"text":"Hi"}}';
$update = json_decode($update, TRUE);
$sender_id=$update['message']['from']['id'];
$first_name=$update['message']['from']['first_name'];
$last_name=$update['message']['from']['last_name'];
$name = $first_name.$last_name;
$chat_message=$update['message']['text'];


// ..............GET Telegram Admin account DATA............
$request_getdata='https://api.telegram.org/bot'.$token.'/getMe';
$acc_content=file_get_contents($request_getdata);
$acc_content = json_decode($acc_content,TRUE);
$recipient_id=$acc_content['result']['id'];


$reply_params = [
'chat_id'=>$sender_id,
'text' =>$chat_message
];
$reply_url='https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($reply_params);



$element_data = array('action' => 'generate_incoming_telegram','token'=>$token,'recipient_id'=>$recipient_id,'sender_id'=>$sender_id,'chat_message'=>$chat_message,'name'=>$name);
$fields = array('operation'=>'chat_telegram','moduleType'=>'chat_telegram','api_type'=>'web','element_data'=>$element_data);
     $post = http_build_query($fields);
     //print_r($post);
     $post_url = 'https://ticketing.mconnectapps.com/api/v1.0/index_new.php';
     $ch = curl_init();
//     // //set the url, number of POST vars, POST data
     curl_setopt($ch,CURLOPT_URL, $post_url);
     curl_setopt($ch,CURLOPT_POST, 1);
     curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
     $results = curl_exec($ch);
     curl_close($ch);
//     echo "rwnkdj";
//     echo "<pre>";
  print_r($results); exit;



