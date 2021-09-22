<?php
$campaign = new campaign();
$result_data["status"] = true;
if($_REQUEST['action'] == 'insert_camp') {  
    $action ='insert_camp'; 
	$agent_id=$_REQUEST['agent_id'];	
	$camp_id=$_REQUEST['camp_id'];	
	$camp_name=$_REQUEST['camp_name'];
	$camp_status=$_REQUEST['camp_status'];
	$call_repeat=$_REQUEST['call_repeat'];
	$camp_pre=$_REQUEST['camp_pre'];
	$camp_type=$_REQUEST['camp_type'];
    $audio_file=$_FILES['audio_file']['name'];
	$camp_vid=$_REQUEST['camp_vid'];
	$parallel=$_REQUEST['parallel'];
	$frequency=$_REQUEST['frequency'];
	$redial=$_REQUEST['redial'];
}
if($_REQUEST['action'] == 'update_camp') {  
    $action ='update_camp';
	$id=$_REQUEST['id']; 
	$camp_id=$_REQUEST['camp_id'];	
	$admin_id=$_REQUEST['admin_id'];	
	$camp_name=$_REQUEST['camp_name'];
	$call_repeat=$_REQUEST['call_repeat'];
	$camp_pre=$_REQUEST['camp_pre'];
	$camp_type=$_REQUEST['camp_type'];
    $audio_file=$_FILES['audio_file']['name'];
	$camp_vid=$_REQUEST['camp_vid'];
	$parallel=$_REQUEST['parallel'];
	$frequency=$_REQUEST['frequency'];
	$redial=$_REQUEST['redial'];
}
if($_REQUEST['action'] == 'insert_rak_camp') {  
    $action ='insert_rak_camp'; 
	$agent_id=$_REQUEST['agent_id'];	
	$camp_id=$_REQUEST['camp_id'];	
	$camp_name=$_REQUEST['camp_name'];
	$camp_status=$_REQUEST['camp_status'];
	$call_repeat=$_REQUEST['call_repeat'];
	$camp_pre=$_REQUEST['camp_pre'];
	$camp_type=$_REQUEST['camp_type'];
    $audio_file=$_FILES['audio_file']['name'];
	$camp_vid=$_REQUEST['camp_vid'];
	$parallel=$_REQUEST['parallel'];
	$frequency=$_REQUEST['frequency'];
	$redial=$_REQUEST['redial'];
}
if($_REQUEST['action'] == 'update_rak_camp') {  
    $action ='update_rak_camp';
	$id=$_REQUEST['id']; 
	$camp_id=$_REQUEST['camp_id'];	
	$admin_id=$_REQUEST['admin_id'];	
	$camp_name=$_REQUEST['camp_name'];
	$call_repeat=$_REQUEST['call_repeat'];
	$camp_pre=$_REQUEST['camp_pre'];
	$camp_type=$_REQUEST['camp_type'];
    $audio_file=$_FILES['audio_file']['name'];
	$camp_vid=$_REQUEST['camp_vid'];
	$parallel=$_REQUEST['parallel'];
	$frequency=$_REQUEST['frequency'];
	$redial=$_REQUEST['redial'];
}
if($action == "insert_camp"){
	
    $data= array("agent_id"=>$agent_id, "camp_id"=>$camp_id, "camp_name"=>$camp_name, "camp_status"=>$camp_status, "call_repeat"=>$call_repeat, "camp_pre"=>$camp_pre, "camp_type"=>$camp_type, "audio_file"=>$audio_file, "camp_vid"=>$camp_vid, "parallel"=>$parallel, "frequency"=>$frequency, "redial"=>$redial);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->insert_camp($data);
    
}elseif($action == "edit_camp"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->edit_camp($data);

}elseif($action == "camp_list"){
    $data= array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->camp_list($data);

}elseif($action == "update_camp"){
    $data= array("id"=>$id, "camp_id"=>$camp_id, "camp_name"=>$camp_name,"call_repeat"=>$call_repeat,"camp_pre"=>$camp_pre,"camp_type"=>$camp_type,"audio_file"=>$audio_file,"admin_id"=>$admin_id, "camp_vid"=>$camp_vid, "parallel"=>$parallel, "frequency"=>$frequency, "redial"=>$redial);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->update_camp($data);
    
}elseif($action == "delete_camp"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->delete_camp($data);

}elseif($action == "toggle_status"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->toggle_status($data);

}elseif($action == "insert_rak_camp"){
	
    $data= array("agent_id"=>$agent_id, "camp_id"=>$camp_id, "camp_name"=>$camp_name, "camp_status"=>$camp_status, "call_repeat"=>$call_repeat, "camp_pre"=>$camp_pre, "camp_type"=>$camp_type, "audio_file"=>$audio_file, "camp_vid"=>$camp_vid, "parallel"=>$parallel, "frequency"=>$frequency, "redial"=>$redial);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->insert_rak_camp($data);
    
}elseif($action == "update_rak_camp"){
    $data= array("id"=>$id, "camp_id"=>$camp_id, "camp_name"=>$camp_name,"call_repeat"=>$call_repeat,"camp_pre"=>$camp_pre,"camp_type"=>$camp_type,"audio_file"=>$audio_file,"admin_id"=>$admin_id, "camp_vid"=>$camp_vid, "parallel"=>$parallel, "frequency"=>$frequency, "redial"=>$redial);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $campaign->update_rak_camp($data);
    
}