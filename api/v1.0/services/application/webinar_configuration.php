<?php
$result_data["status"] = true; 
$webinar_configuration = new webinar_configuration();

if($action == "insert_configuration"){    
    $data = array("admin_id"=>$admin_id, "fqdn"=>$fqdn,"api_token"=>$api_token,"extension_number"=>$extension_number,"country"=>$country,"subscribers_limit"=>$subscribers_limit);    
    $result_data["result"]["data"] = $webinar_configuration->insertConfiguration($data);
}
elseif($action == "get_configuration"){

	$data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $webinar_configuration->getConfiguration($data);
	//$result_data["result"]["data"]["countries"] = $webinar_configuration->getCountries();

}
elseif($action == "get_countries"){
	$result_data["result"]["data"] = $webinar_configuration->getCountries();
}
elseif($action == "get_meeting_list"){
	$data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $webinar_configuration->get_meeting_list($data);
}
elseif($action == "list_meeting_participants"){
	$data = array("meetingid"=>$meetingid);
    $result_data["result"]["data"] = $webinar_configuration->list_meeting_participants($data);
}
elseif($action == "list_meeting_participants_report"){
	$data = array("meetingid"=>$meetingid);
    $result_data["result"]["data"] = $webinar_configuration->list_meeting_participants_report($data);
}
elseif($action == "delete_meeting"){     
    $data = array("meetingid"=>$meetingid);       
    $result_data["result"]["data"] = $webinar_configuration->delete_meeting($data);
}
/*elseif($action == "edit_question"){    
    $data = array("question_id"=>$question_id);    
    $result_data["result"]["data"] = $questionaire->editQuestion($data);
}
elseif($action == "get_queue"){
	$data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $questionaire->getQueue($data);
}
elseif($action == "question_list"){
    $data = array("admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $questionaire->questionList($data);
}
elseif($action == "update_question"){    
    $data = array("question_id"=>$question_id,"admin_id"=>$admin_id, "department_id"=>$department_id,"question"=>$question);    
    $result_data["result"]["data"] = $questionaire->updateQuestion($data);
}
elseif($action == "get_user_queue"){
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $questionaire->getuserQueue($data);
}
elseif($action == "delete_question"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);       
    $result_data["result"]["data"] = $questionaire->delete_question($data);
}*/

