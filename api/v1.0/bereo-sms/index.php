<?php
 $inc_wp_response = file_get_contents('php://input',true);
 $log = file_put_contents('email_test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);


$result = 'date=20/11/26,19:54:10+32^num=+6581413545^from=none^port=2^msg=Test^bhara^7.54pm';




$result = $inc_wp_response;
		$msg= strstr($result, 'msg=');
		$msg = str_replace('msg=','', $msg);
		$msg = str_replace('^',' ', $msg);
		$result = explode('^', $result);
		$date = str_replace('date=','', $result[0]);
		$num = str_replace('num=','', $result[1]);
		$from = str_replace('from=','', $result[2]);
		$port = str_replace('port=','', $result[3]);
		$message_time =  $date;
		$date = str_replace('/', '-', $message_time);
		$message_time = date('Y-m-d H:i:s', strtotime($date));



//$ch = curl_init();

//curl_setopt($ch, CURLOPT_URL, 'https://lookups.twilio.com/v1/PhoneNumbers/+918489514086?Type=carrier');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//curl_setopt($ch, CURLOPT_USERPWD, 'ACcf360292ecffb40031e510d2e7492490' . ':' . 'b8eeb0ca8db33cb1fefc3ff7017e2cdf');

//$result = curl_exec($ch);
//if (curl_errno($ch)) {
   // echo 'Error:' . curl_error($ch);
//}
//curl_close($ch);


//print_r($result); exit;

$element_data = array('action' => 'generate_incoming_message','from'=>$num,'message'=>$msg,'message_time'=>$message_time,'user_id'=>'612');
$fields = array('operation' =>'chat','moduleType' => 'chat','api_type' => 'web', 'element_data' => $element_data);
//echo json_encode($element_data); exit;
$post = http_build_query($fields);

//echo $post; exit;

$url = 'https://ubin.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
print_r($result);
