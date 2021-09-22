<?php
$pre_camp = new pre_camp();
$result_data["status"] = true;

if($action == "get_camp_on_off"){
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->get_camp_on_off($data);
    
}

if($action == "get_phone_no"){
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id,"queue_status"=>$queue_status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->get_phone_no($data);
    
}

if($action == "update_queue_stat"){
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id,"queue_status"=>$queue_status,"phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->update_queue_stat($data);
    
}

if($action == "get_call_repeat"){
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->get_call_repeat($data);
    
}


if($action == "insert_ag_survey"){
	//echo 'asas';exit;
        $data= array("posted_key"=>$posted_key, "ani"=>$ani, "dins"=>$dins, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->insert_ag_survey($data);
    
}

if($action == "insert_survey"){
        $data= array("posted_key"=>$posted_key, "ani"=>$ani, "dins"=>$dins, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->insert_survey($data);
    
}
if($action == "update_dialer_dnd"){
        $data= array("camp_id"=>$camp_id, "dnd"=>$dnd,"key_in"=>$key_in,"admin_id"=>$admin_id,"phone_no"=>$phone_no,"queue_status"=>$queue_status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->update_dialer_dnd($data);
    
}

if($action == "update_call_stat"){
	//echo '124';exit;
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id,"call_status"=>$call_status, "phone_no"=>$phone_no, "call_start"=>$call_start); 
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->update_call_stat($data);
    
}

if($action == "call_end"){
        $data= array("camp_id"=>$camp_id, "admin_id"=>$admin_id,"call_end"=>$call_end,"call_status"=>$call_status, "phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $pre_camp->call_end($data);
    
}