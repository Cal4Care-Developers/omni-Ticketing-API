<?php

if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
//echo $action; exit;
if($_REQUEST['action'] == 'whatsapp_media_upload') {  
    $action ='whatsapp_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $chat_id = $_REQUEST['chat_id'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}

$result_data["status"] = true; 
$chat = new wpinstance();


if($_REQUEST['action'] == 'single_whatsapp_media_upload_uoff') {  
    $action ='single_whatsapp_media_upload_uoff'; 
    $user_id = $_REQUEST['user_id'];
    $admin_id = $_REQUEST['admin_id'];
	$instance_id = $_REQUEST['instance_id'];
    $timezone_id = $_REQUEST['timezone_id'];
    $country_code = $_REQUEST['country_code'];
    $phone_num = $_REQUEST['phone_num'];        
    $chat_msg = $_REQUEST['chat_msg'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}


if($action == "getInstanceDetailsForAdmin"){
	
	$chat_data = array("user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $chat->getInstanceDetailsForAdmin($chat_data);
} elseif($action == "updateNumWithDeptToInst"){
	$result_data["result"]["status"] = true;
	$data= array("whatsapp_num"=>$whatsapp_num,"department_id"=>$department_id, "instance_id"=>$instance_id);
	$result_data["result"]["data"] = $chat->updateNumWithDeptToInst($data);
} elseif($action == "readInstance"){
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $chat->readInstance($instance_id);
}  elseif($action == "refreshInstance"){
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $chat->refreshInstance($instance_id);
} elseif($action == "reloadInstance"){
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $chat->reloadInstance($instance_id);
} elseif($action == "generate_incoming_wp_unoff"){
	$from = $_REQUEST['element_data']['from'];
	if($from == 'status@broadcast'){
	return false;
	}
	$chat_message = $_REQUEST['element_data']['message'];
	$to = $_REQUEST['element_data']['to'];
	$sender = $_REQUEST['element_data']['receiver_prof_pic'];
	$message_id = $_REQUEST['element_data']['message_id'];
	$sender_name = $_REQUEST['element_data']['sender_name'];
	$time = $_REQUEST['element_data']['time'];
	
	//$chat_message = str_replace("/////", "'", $chat_message);
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"sender"=>$sender);
    $result_data["result"]["status"] = true;
	//print_r($chat_data); exit;
    $result_data["result"]["data"] = $chat->ComposeIncommingChatMessageUnoff($chat_data);
}  elseif($action == "generate_incoming_image_wp_unoff"){
	$from = $_REQUEST['element_data']['from'];
	if($from == 'status@broadcast'){
	return false;
	}
	$chat_message = $_REQUEST['element_data']['message'];
	
	$to = $_REQUEST['element_data']['to'];
	$sender = $_REQUEST['element_data']['receiver_prof_pic'];
	$message_id = $_REQUEST['element_data']['message_id'];
	$sender_name = $_REQUEST['element_data']['sender_name'];
	$time = $_REQUEST['element_data']['time'];
	$caption = $_REQUEST['element_data']['caption'];
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"sender"=>$sender,"caption"=>$caption);
    $result_data["result"]["status"] = true;
	//print_r($chat_data); exit;
    $result_data["result"]["data"] = $chat->generate_incoming_image_wp_unoff($chat_data);
} elseif($action == "generate_incoming_group_wp_unoff"){
	$from = $_REQUEST['element_data']['from'];
	if($from == 'status@broadcast'){
	return false;
	}
	$chat_message = $_REQUEST['element_data']['message'];
	$to = $_REQUEST['element_data']['to'];
	$group_icon = $_REQUEST['element_data']['group_icon'];
	$message_id = $_REQUEST['element_data']['message_id'];
	$sender_name = $_REQUEST['element_data']['sender_name'];
	$time = $_REQUEST['element_data']['time'];
	$group_name = $_REQUEST['element_data']['group_name'];
	
	
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"group_icon"=>$group_icon,"group_name"=>$group_name);
    $result_data["result"]["status"] = true;
	//print_r($chat_data); exit;
    $result_data["result"]["data"] = $chat->generate_incoming_group_wp_unoff($chat_data);
} elseif($action == "generate_incoming_group_image_wp_unoff"){
	$from = $_REQUEST['element_data']['from'];
	if($from == 'status@broadcast'){
	return false;
	}
	$chat_message = $_REQUEST['element_data']['message'];
	$to = $_REQUEST['element_data']['to'];
	$group_icon = $_REQUEST['element_data']['group_icon'];
	$message_id = $_REQUEST['element_data']['message_id'];
	$sender_name = $_REQUEST['element_data']['sender_name'];
	$time = $_REQUEST['element_data']['time'];
	$group_name = $_REQUEST['element_data']['group_name'];
	$caption = $_REQUEST['element_data']['caption'];
	
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"group_icon"=>$group_icon,"group_name"=>$group_name , "caption"=>$caption);
    $result_data["result"]["status"] = true;
	//print_r($chat_data); exit;
    $result_data["result"]["data"] = $chat->generate_incoming_group_image_wp_unoff($chat_data);
} elseif($action == "single_whatsapp_media_upload_uoff"){
$chat_data = array("whatsapp_media"=>$whatsapp_media,"user_id"=>$user_id,"chat_msg"=>$chat_msg,"timezone_id"=>$timezone_id,"country_code"=>$country_code,"phone_num"=>$phone_num,"instance_id"=>$instance_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->single_whatsapp_media_upload_uoff($chat_data);
} elseif($action == "chat_message_panel_unoff"){
	$chat_detail_list == "";
	if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
		$chat_detail_list = $chat->chatDetailListUOFF($chat_id);
	} 

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChatUOFF($user_id,"","",$user_type,$instance_id,$limit,$offset);
	 $result_data["result"]["data"]["user_list"] = $chat->getdeptUsers($instance_id);
	 
    $result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list;
} elseif($action == "send_chat_message_unoff"){
	//send_chat_message_unoffocial_wp
     $chat_data = array("chat_id"=>$chat_id,"agent_id"=>$user_id, "msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_message,"whatsapp_media_url"=>$whatsapp_media_url,"chat_status"=>1,"instance_id"=>$instance_id,"is_group"=>$is_group);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOff($chat_data);
}elseif($action == "sendWhatsappText"){
	$from = $_REQUEST['element_data']['from'];
	$to = $_REQUEST['element_data']['to'];
	$chat_message = $_REQUEST['element_data']['message'];
	$company = $_REQUEST['element_data']['company'];
	$username = $_REQUEST['element_data']['username'];
	$is_group = $_REQUEST['element_data']['is_group'];
	//echo  $_REQUEST['element_data']['is_group']; exit;
	//print_r($_REQUEST); exit;
    $chat_data = array("msg_to" =>$to ,"msg_from"=>$from,"msg_type"=>"text","chat_msg"=>$chat_message,"username"=>$username,"company"=>$company,"chat_status"=>1,"is_group"=>$is_group);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOff2($chat_data);
}elseif($action == "send_chat_message_media_unoff"){
		$from = $_REQUEST['element_data']['from'];
	$to = $_REQUEST['element_data']['to'];
	$chat_file = $_REQUEST['element_data']['file_url'];
	$company = $_REQUEST['element_data']['company'];
	$username = $_REQUEST['element_data']['username'];
	$caption = $_REQUEST['element_data']['caption'];
	$is_group = $_REQUEST['element_data']['is_group'];
    $chat_data = array("msg_to" =>$to ,"msg_from"=>$from,"msg_type"=>"text","chat_file"=>$chat_file,"username"=>$username,"company"=>$company,"instance_id"=>$instance_id,"chat_status"=>1,"is_group"=>$is_group,"caption"=>$caption);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOffFiles($chat_data);
}elseif($action == "whatsapp_media_upload"){  
    $chat_data = array("whatsapp_media"=>$whatsapp_media,"user_id"=>$user_id,"chat_id"=>$chat_id,"whatsapp_media_with_text"=>$whatsapp_media_with_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->whatsapp_media_upload($chat_data);
}elseif($action == "chatTransfer"){  
    $chat_data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"inst_id"=>$instance_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->chatTransfer($chat_data);
}elseif($action == "revokeTransfer"){  
    $chat_data = array("chat_id"=>$chat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->revokeTransfer($chat_data);
}elseif($action == "revokeInstance"){  
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->revokeInstance($instance_id);
} elseif($action == "change_wp_status_unoff"){
	$MessageId = $_REQUEST['element_data']['message_id'];
	$status = $_REQUEST['element_data']['ack'];
    $chat_data = array("MessageId"=>$MessageId,"chat_status"=>$status);	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->updateWpChatStatus($chat_data);
}  elseif($action == "setWebHook"){
	$web_hook = $_REQUEST['element_data']['web_hook'];
	$wp_num = $_REQUEST['element_data']['wp_num'];
    $chat_data = array("web_hook"=>$web_hook,"wp_num"=>$wp_num);	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->setWebHook($chat_data);
}  elseif($action == "generate_outgoing_wp_unoff"){
	$MessageId = $_REQUEST['element_data']['message_id'];
	$to = $_REQUEST['element_data']['to'];
	$receiver_prof_pic = $_REQUEST['element_data']['receiver_prof_pic'];
	$receiver_name = $_REQUEST['element_data']['receiver_name'];
    $chat_data = array("MessageId"=>$MessageId,"receiver_prof_pic"=>$receiver_prof_pic,"receiver_name"=>$receiver_name,"to"=>$to);	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->generate_outgoing_wp_unoff($chat_data);
}   elseif($action == "getSearchResForWhatsapp"){
    $chat_data = array("search_text"=>$search_text,"instance_id"=>$instance_id,"user_id"=>$user_id,"user_type"=>$user_type,"search_type"=>$search_type);	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getSearchResForWhatsapp($chat_data);
}




