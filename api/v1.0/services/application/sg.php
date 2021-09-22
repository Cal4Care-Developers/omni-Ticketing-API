<?php
$sg = new sg();
$result_data["status"] = true;

if($action == "ag_sum"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sg->ag_sum($data);
    
}
if($action == "work_rep"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id, "q_num"=>$q_num);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sg->work_rep($data);
    
}