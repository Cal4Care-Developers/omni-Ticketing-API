<?php
$report = new report();
$result_data["status"] = true;

if($action == "gen_log_report"){
    $data= array("type"=>$type, "rep_format"=>$rep_format, "from_dt"=>$from_dt,"to_dt"=>$to_dt,"report_name"=>$report_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $report->gen_log_report($data);
    
}elseif("report_list"){
	$data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"user_id"=>$user_id);
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $report->report_list($data);
}