<?php
$sms= new sms();
$result_data["status"] = true;

if($action == "sms_report"){
  $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sms->generate_sms($data);

}