<?php
$inbound_report= new inbound_report();
$result_data["status"] = true;

if($action == "inbound"){
    $data= array("type"=>$type,"admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"rep_format"=>$rep_format,"report_name"=>$report_name, "call_type"=>$call_type );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $inbound_report->inbound($data);

}elseif("agnt_aux_list"){
    $data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $inbound_report->agnt_aux_list($data);
}