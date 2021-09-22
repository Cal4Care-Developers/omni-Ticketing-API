<?php
$result_data["status"] = true;
$queue = new queue();

if($action == "queue_list"){
    $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->getQueueList($data);
}
/*elseif($action == "add_queue"){
	
	$queue_data = array("queue_name"=>$queue_name,"queue_status"=>4, "created_ip"=>'',"created_by"=>$user_id,"updated_by"=>$user_id,"queue_number"=>$queue_number,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->addQueue($queue_data);
}*/
elseif($action == "add_up_queue"){
	
	$data = array("queue_name"=>$queue_name,"queue_status"=>$queue_status, "created_by"=>$created_by,"queue_number"=>$queue_number,"hardware_id"=>$hardware_id,"queue_users"=>$queue_users);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->addQueue($data);
}
elseif($action == "user_queue_widget"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->user_queue_widget($login);

}
elseif($action == "queue_add"){ 
	//echo $action;exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->queue_add($queues,$hardware_id);
}
elseif($action == "queue_user_assign_list"){

    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["queue_assign_users"] = $queue->getQueueUserList($queue_id);
    $result_data["result"]["data"]["queue_features"] = $queue->queueFeaturesList();
    
}
elseif($action == "list_queue"){
    $data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->list_queue($data);

}
elseif($action == "edit_queue"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->edit_queue($data);

}elseif($action == "update_queue"){
    $data= array("id"=>$id, "queue_name"=>$queue_name, "queue_number"=>$queue_number,"queue_status"=>$queue_status,"queue_users"=>$queue_users,"wrapup_time"=>$wrapup_time,"max_callers"=>$max_callers,"priority"=>$priority,"sla_sec"=>$sla_sec);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->update_queue($data);
    
}elseif($action == "delete_queue"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->delete_queue($data);    
}
elseif($action == "queue_delete"){
    $data= array("hardware_id"=>$hardware_id,"queue_number"=>$queue_number);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->queue_delete($data);    
}
elseif($action == "user_queue"){
    $data= array("admin_id"=>$admin_id,"agent_id"=>$agent_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->user_queue($data);

}elseif($action == "user_add"){ 
	//echo $action;exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->user_add($users,$hardware_id,$all_data);
}elseif($action == "update_queue_usr"){
    $data= array(  "queue_number"=>$queue_number,"queue_users"=>$queue_users,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->update_queue_usr($data);
    
}elseif($action == "in_login_logout"){
	
    $data= array("login"=>$login,"reason"=>$reason,"status"=>$status);
	//print_r($data);exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $queue->in_login_logout($data);
    
}
?>