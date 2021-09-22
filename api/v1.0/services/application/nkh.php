<?php
$nkh = new nkh();
$result_data["status"] = true;

if($action == "q_group"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id ,"abn_sec"=>$abn_sec);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $nkh->q_group($data);
    
}
if($action == "ag_perform"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $nkh->ag_perform($data);
    
}
if($action == "agn_report"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $nkh->agn_report($data);
    
}
if($action == "call_period"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id,"sla"=>$sla);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $nkh->call_period($data);
    
}