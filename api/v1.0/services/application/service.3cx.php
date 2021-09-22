<?php
$result_data["status"] = true;
$service3cx = new service3cx();
if($action == "queue_update"){
	
	$queue_result = $service3cx->agentUpdate(array("queue_number"=>$queue_number,"queue_id"=>$queue_id,"queue_name"=>$queue_name));

	if($queue_result == 1){
		$result_data["result"]["status"] = true;
	 	$result_data["result"]["message"] = "Queue details updated successfully";
	}
	else{
		$result_data["result"]["status"] = false;
		$result_data["result"]["message"] = "Queue details not updated";
	}
}
elseif($action == "agent_update"){
	
		$agent_result = $service3cx->agentUpdate(array("user_id"=>$user_id,"user_pwd"=>$user_pwd,"sip_username"=>$sip_username,"sip_password"=>$sip_password));

	if($agent_result == 1){
		$result_data["result"]["status"] = true;
	 	$result_data["result"]["message"] = "Agent details updated successfully";
	}
	else{
		$result_data["result"]["status"] = false;
		$result_data["result"]["message"] = "Agent details not updated";
	}

}