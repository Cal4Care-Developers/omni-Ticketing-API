<?php

$netwrix = new netwrix();
$result_data["status"] = true;

if($action == "chart"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"queue"=>$queue,"report_name"=>$report_name, "type"=>$type,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $netwrix->create_chart($data); 

}if($action == "get_queue"){
    $data= array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $netwrix->get_queue($data); 

}if($action == "chart_excel"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"queue"=>$queue,"report_name"=>$report_name, "type"=>$type,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $netwrix->chart_excel($data); 

}