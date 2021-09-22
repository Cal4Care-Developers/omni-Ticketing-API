<?php

$call = new call();
$user = new userData();
$result_data["status"] = true;

if($action == "recent_list"){
    $data= array("user_id"=>$user_id, "order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->callHistoryList($data);
    
}
if($action == "recent_call_list"){
    $data= array("user_id"=>$user_id, "order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"admin_id"=>$admin_id,"extension"=>$extension);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->callrecentList($data);
    
}
if($action == "recent_list_widget"){
    $data= array("login"=>$login, "order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->callHistoryListWidget($data);
    
}

elseif($action == "user_list"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->userList($user_id);

}
elseif($action == "user_list_login"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->userListLogin($login);

}
elseif($action == "outgoing_call_inprogess"){
    
   $data =  array("user_id"=>$user_id,"customer_id"=>$customer_id,"call_data"=>$call_data,'call_note'=>$call_note,'phone'=>$phone,'call_type'=>$call_type,"call_status"=>$call_status,"dialer_type"=>$dialer_type);
    
    
    $result_call_data = $call->callHitoryEntry($data);
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $result_call_data;

}

/*elseif($action == "call_incoming"){
    
    $data =  array("user_id"=>$user_id,"customer_id"=>$customer_id,"call_data"=>$call_data,'call_note'=>$call_note,'phone'=>$phone,'call_type'=>$call_type,"call_status"=>$call_status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] =$call->callHitoryEntry($data);
    
}*/
elseif($action == "call_incoming"){
    
    $data =  array("user_id"=>$user_id,"customer_id"=>$customer_id,"call_data"=>$call_data,'call_note'=>$call_note,'phone'=>$phone,'call_type'=>$call_type,"call_status"=>$call_status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] =$call->callEntry($data);
    
}

elseif($action == "incoming_call_inprogess"){
    
    $call->callHitoryUpdate($callid,$call_status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->callHitoryDetails($callid);
    
}

elseif($action == "call_history_detail"){
    if($callid == '' || $callid == null){
         $callid = $call->userLastCallHistory($user_id);
    }
    $call_history_data = $call->callHitoryDetails($callid);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] =$call_history_data;

}

elseif($action == "user_detail_view"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->userDetails($user_id);
    
}
elseif($action == 'auxcode_reports'){
    $data= array("user_id"=>$user_id,"fromDate"=>$fromDate,"toDate"=>$toDate,"auxcode_name"=>$auxcode_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->auxcodeReport($data);   
}

elseif($action == 'external_call'){
    $data= array("type"=>$type,"call_nature"=>$call_nature,"from_no"=>$from_no,"to_no"=>$to_no,"dt_time"=>$dt_time,"duration"=>$duration,"rec_path"=>$rec_path,"hardware_id"=>$hardware_id,"call_start"=>$call_start,"call_end"=>$call_end,"int_ext"=>$int_ext);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->external_call($data);
}
elseif($action == "to_external_api"){
    $data= array("user_id"=>$user_id,"to_no"=>$to_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->caller_no($data);

}
elseif($action == "queue_login_logout"){
    $data= array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->queue_login_logout($data);

}elseif($action == "in_login_logout"){
    $data= array("agent_id"=>$agent_id,"reason"=>$reason,"status"=>$status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->in_login_logout($data);

}elseif($action == "voip_login_logout"){
    $data= array("hardware_id"=>$hardware_id,"reason"=>$reason,"status"=>$status,"extension_no"=>$extension_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->voip_login_logout($data);

}elseif($action == "login_logout_report"){
    $data= array("agent_id"=>$agent_id,"agents"=>$agents,"status"=>$status,"reason"=>$reason,"from_date"=>$from_date,"to_date"=>$to_date);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->login_logout_report($data);

}
elseif($action == "login_list"){
    $data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->login_list($data);

}
//Only for BlueboxIt Entertainment
elseif($action == "get_aux_list_byCustID"){
	
    $data= array("cust_id"=>$cust_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->get_aux_list_byCustID($data);

}
elseif($action == "call_report"){
    $data= array("admin_id"=>$admin_id,"call_type"=>$call_type,"call_nature"=>$call_nature,"extension"=>$extension,"from_dt"=>$from_dt,"to_dt"=>$to_dt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->call_report($data);

}elseif($action == "call_list"){
    $data= array("user_id"=>$user_id,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->call_list($data);

}elseif($action == "call_details_dropdown"){
    //$data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->call_details_dropdown();

}elseif($action == "create_servers"){
    $data= array("server_name"=>$server_name,"server_ip"=>$server_ip, "server_location"=>$server_location,"server_fqdn"=>$server_fqdn);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->create_servers($data);
}elseif($action == "update_webrtc_servers"){
    $data= array("server_id"=>$server_id,"server_name"=>$server_name,"server_ip"=>$server_ip, "server_location"=>$server_location,"server_fqdn"=>$server_fqdn);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->update_webrtc_servers($data);
}elseif($action == "list_webrtc_servers"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->list_webrtc_servers();
}elseif($action == "add_warp_external"){
   // $data= array("wrap_up"=>$wrap_up,"call_id"=>$call_id,"url"=>$url);
    $data= array("caller_no"=>$caller_no,"call_start"=>$call_start,"call_end"=>$call_end,"wrap_up"=>$wrap_up,"call_id"=>$call_id,"url"=>$url,"call_type"=>$call_type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call->add_warp_external($data);

}