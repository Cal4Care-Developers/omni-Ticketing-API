<?php
$daily_foods = new daily_foods();
$result_data["status"] = true;

if($action == "insert_3cx_calls"){
    $data= array("callid"=>$call_id, "call_con_id"=>$call_con_id, "call_frm_num"=>$call_frm_num, "q_num"=>$q_num,
        "agnt_num"=>$agnt_num, "status"=>$status, "time_of_status"=>$time_of_status, "call_start_time"=>$call_start_time, "call_ans_time"=>$call_ans_time, "call_end_time"=>$call_end_time, "call_waiting_time"=>$call_waiting_time, "call_talk_time"=>$call_talk_time,"hardware_id"=>$hardware_id, "call_history_id"=>"$call_history_id");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->insert_3cx_calls($data);
    
}elseif($action == "update_3cx_calls"){
    $data= array("call_id"=>$call_id, "call_con_id"=>$call_con_id, "call_start_time"=>$call_start_time, "call_ans_time"=>$call_ans_time, "call_end_time"=>$call_end_time, "call_waiting_time"=>$call_waiting_time, "call_talk_time"=>$call_talk_time,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->update_3cx_calls($data);
    
}elseif($action == "insert_user_status"){
    $data= array("dn_num"=>$dn_num, "ag_num"=>$ag_num, "status"=>$status, "time_of_update"=>$time_of_update,
        "q_num"=>$q_num, "time_of_endstatus"=>$time_of_endstatus,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->insert_user_status($data);
    
}elseif($action == "insert_ag_q_details"){
    $data= array("dn_num"=>$dn_num,"q_num"=>$q_num,"q_name"=>$q_name, "ag_num"=>$ag_num, "ag_firstname"=>$ag_firstname, "ag_lastname"=>$ag_lastname,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->insert_ag_q_details($data);
    
}elseif($action == "update_user_status"){
    $data= array( "time_of_endstatus"=>$time_of_endstatus,"dn_num"=>$dn_num,"q_num"=>$q_num, "ag_num"=>$ag_num, "status"=>$status,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->update_user_status($data);
    
}elseif($action == "update_user_status_nt_in"){
    $data= array( "time_of_endstatus"=>$time_of_endstatus,"dn_num"=>$dn_num,"q_num"=>$q_num, "ag_num"=>$ag_num, "status"=>$status,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $daily_foods->update_user_status_nt_in($data);
    
}

