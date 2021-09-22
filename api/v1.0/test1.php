<?php 
include_once 'admin.controller.php';
$admin = new adminData();

// $access_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJhdWQiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJpYXQiOjE1NzExMjc4NzUsIm5iZiI6MTU3MTEyNzg3NSwiZXhwIjoxNTcxMTQ1ODc1LCJhY2Nlc3NfZGF0YSI6eyJ0b2tlbl9hY2Nlc3NJZCI6IjEiLCJ0b2tlbl9hY2Nlc3NOYW1lIjoib21uaV9hcHAiLCJ0b2tlbl9hY2Nlc3NUeXBlIjoiMSJ9fQ.lXym_m06o1d8EHf7eFCgJl4Zr1_LXTFyR8OfLuf_ZCI";


// $user_id = 1;

// $api_data = json_encode(array("access_token"=>$access_token,"operation"=>"email","moduleType"=>"email","api_type"=>"web","element_data"=>array("action"=>"mail_detail_list","user_id"=>$user_id,"chat_id"=>46)));
//echo "<pre>";
  // echo $result_data = $admin->curl_data($api_data);
//echo "<pre>";
//echo htmlentities (print_r (json_decode ($result_data), true));


$element_data = array("action"=>"queue_update","queue_id"=>"12","queue_number"=>"121","queue_name"=>"21");
 $request_data = json_encode(array("operation"=>"3cx_service","moduleType"=>"3cx","api_type"=>"3cx","element_data"=>$element_data));

print_r($admin->curl_data(json_encode($request_data)));



?>