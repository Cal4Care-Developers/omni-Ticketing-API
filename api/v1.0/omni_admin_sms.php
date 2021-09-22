<?php
file_put_contents('adminSMS.txt', file_get_contents('php://input'), FILE_APPEND | LOCK_EX);
$xmlstring = file_get_contents('php://input');


$xml = simplexml_load_string($xmlstring,"SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$created_at = $array['TELEMESSAGE_CONTENT']['MESSAGE']['MESSAGE_INFORMATION']['TIME_STAMP'];
$phone_num = $array['TELEMESSAGE_CONTENT']['MESSAGE']['USER_FROM']['CIML']['DEVICE_INFORMATION']['DEVICE_VALUE'];
$phone_num = str_replace('960-','', $phone_num);
$phone_num = str_replace('-','', $phone_num);
$message = $array['TELEMESSAGE_CONTENT']['MESSAGE']['MESSAGE_CONTENT']['TEXT_MESSAGE']['TEXT'];
$element_data = array('action' => 'generate_incoming_sms','created_by'=>'47','phone_num'=>$phone_num,'message'=>$message,'created_at'=>$created_at);
$fields = array('operation' => 'chat','moduleType' => 'chat','api_type' => 'web', 'element_data' => $element_data);

$post = http_build_query($fields);
$url = 'http://omni.mconnectapps.com/api/v1.0/index_new.php';

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
echo 'OK';
?>