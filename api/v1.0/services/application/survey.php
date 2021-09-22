<?php
$survey = new survey();
$result_data["status"] = true;

if($action == "survey_list"){
    $data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"agent_name"=>$agent_name,"call_id"=>$call_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->survey_list($data);

}elseif($action == "survey_rep"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"agent_name"=>$agent_name,"call_id"=>$call_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->survey_rep($data);

}elseif($action == "get_survey_agents"){
    $data= array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->get_survey_agents($data);

}elseif($action == "get_survey_callers"){
    $data= array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->get_survey_callers($data);

}elseif($action == "survey_summary"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->survey_summary($data);

}
elseif($action == "survey_summary_rep"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $survey->survey_summary_rep($data);

}
