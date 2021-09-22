<?php
$result_data["status"] = true;
$broadcast_report = new broadcast_report();
if($action == "bd_report"){
    $data = array("admin_id"=>$admin_id,"from"=>$from,"to"=>$to,"name"=>$name,"type"=>$type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $broadcast_report->bd_report($data);
}
if($action == "bd_names"){
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $broadcast_report->bd_names($data);
}