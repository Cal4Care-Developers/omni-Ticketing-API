<?php

if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
if($_REQUEST['action'] == 'whatsapp_media_upload') {  
    $action ='whatsapp_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $chat_id = $_REQUEST['chat_id'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}
if($_REQUEST['action'] == 'fb_reply_media_upload') {  
    $action ='fb_reply_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $chat_id = $_REQUEST['chat_id'];
    $sender_id = $_REQUEST['sender_id'];        
    $facebook_media=$_FILES['facebook_media']['name'];
}

$result_data["status"] = true; 
$chat = new chatwp();
if($_REQUEST['action'] == 'bulk_whatsapp_media_upload') {  
    $action ='bulk_whatsapp_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $admin_id = $_REQUEST['admin_id'];
    $timezone_id = $_REQUEST['timezone_id'];
    $group = $_REQUEST['group'];
    //$chat_id = $_REQUEST['chat_id'];
    $chat_msg = $_REQUEST['chat_msg'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}
if($_REQUEST['action'] == 'single_whatsapp_media_upload') {  
    $action ='single_whatsapp_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $admin_id = $_REQUEST['admin_id'];
    $timezone_id = $_REQUEST['timezone_id'];
    $country_code = $_REQUEST['country_code'];
    $phone_num = $_REQUEST['phone_num'];        
    $chat_msg = $_REQUEST['chat_msg'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}


if($action == "mc_event_list"){
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents();
}
elseif($action == "get_queue_chat_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChat($user_id,$queue_id,$search_text);	
}

elseif($action == "chat_message_panel"){
	$chat_detail_list == "";
	if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
		$chat_detail_list = $chat->chatDetailList($chat_id);
	}
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChat($user_id,"","");
    $result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list;
}


elseif($action == "chat_message_panel_unoff"){
	$chat_detail_list == "";
	if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
		$chat_detail_list = $chat->chatDetailListUOFF($chat_id);
	}
	

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChatUOFF($user_id,"","");
    $result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list;
}
elseif($action == "chat_detail_listOFF"){
  $result_data["result"]["status"] = true;
  
  $result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailListUOFF($chat_id,$limit,$offset);
}

elseif($action == "chat_detail_list"){
    
    $result_data["result"]["status"] = true;
    
    $result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailList($chat_id);
}
elseif($action == "send_chat_message"){
     $chat_data = array("chat_id"=>$chat_id,"agent_id"=>$user_id, "msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_message,"whatsapp_media_url"=>$whatsapp_media_url,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessage($chat_data);
}elseif($action == "send_chat_message_unoff"){
	//send_chat_message_unoffocial_wp
     $chat_data = array("chat_id"=>$chat_id,"agent_id"=>$user_id, "msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_message,"whatsapp_media_url"=>$whatsapp_media_url,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOff($chat_data);
}elseif($action == "send_chat_message_unoffs"){
	$to = $_REQUEST['element_data']['phone'];
	$chat_message = $_REQUEST['element_data']['message'];
	$company = $_REQUEST['element_data']['company'];
	$username = $_REQUEST['element_data']['username'];
	//echo 'fvfx'; exit;
    $chat_data = array("msg_to" =>$to ,"msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_message,"username"=>$username,"company"=>$company,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOff2($chat_data);
}elseif($action == "send_chat_message_media_unoff"){
	
	$to = $_REQUEST['element_data']['phone'];
	$chat_file = $_REQUEST['element_data']['message'];
	$company = $_REQUEST['element_data']['company'];
	$username = $_REQUEST['element_data']['username'];
	$caption = $_REQUEST['element_data']['caption'];

    $chat_data = array("msg_to" =>$to ,"msg_from"=>"agent","msg_type"=>"text","caption"=>$caption,"chat_msg"=>$chat_file,"username"=>$username,"company"=>$company,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageOffFiles($chat_data);
}elseif($action == "compose_sms"){
    $chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"user_id"=>$user_id,"chat_msg"=>$chat_message,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeChatMessage($chat_data);
}
elseif($action == "generate_incoming_wp"){
	$from = $_REQUEST['element_data']['From'];
	$chat_message = $_REQUEST['element_data']['message'];
	$to = $_REQUEST['element_data']['To'];
	
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeIncommingChatMessage($chat_data);
}
//unofficial whatsapp

elseif($action == "generate_incoming_wp_unoff"){
	$from = $_REQUEST['element_data']['From'];
	$chat_message = $_REQUEST['element_data']['message'];
	$to = $_REQUEST['element_data']['To'];
	$instance_num = $_REQUEST['element_data']['instance_num'];
    $chat_data = array("from"=>$from,"to"=>$to,"chat_msg"=>$chat_message,"chat_status"=>1,"instance_num"=>$instance_num);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeIncommingChatMessageUnoff($chat_data);
}

elseif($action == "change_wp_status"){
	$MessageId = $_REQUEST['element_data']['MessageId'];
	$status = $_REQUEST['element_data']['status'];
    $chat_data = array("MessageId"=>$MessageId,"chat_status"=>$status);	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->updateWpChatStatus($chat_data);
}
elseif($action == "generate_incoming_fb"){
    $recipient_id = $_REQUEST['element_data']['recipient_id'];
    $sender_id = $_REQUEST['element_data']['sender_id'];
    $chat_message = $_REQUEST['element_data']['message'];
	$message_attachment = $_REQUEST['element_data']['attachment'];
	$first_name = $_REQUEST['element_data']['first_name'];
	$last_name = $_REQUEST['element_data']['last_name'];
	$profile_pic = $_REQUEST['element_data']['profile_pic'];
	$page_name = $_REQUEST['element_data']['page_name'];
	$page_picture = $_REQUEST['element_data']['page_picture'];
	$access_token = $_REQUEST['element_data']['access_token'];
	//$fileType = $_REQUEST['element_data']['attachment']['type'];
	//echo $fileType;exit;
    //$chat_data = array("recipient_id"=>$recipient_id,"sender_id"=>$sender_id,"chat_msg"=>$chat_message,"first_name"=>$first_name,"last_name"=>$last_name,"profile_pic"=>$profile_pic,"page_name"=>$page_name,"page_picture"=>$page_picture,"access_token"=>$access_token);
	$chat_data = array("recipient_id"=>$recipient_id,"sender_id"=>$sender_id,"chat_msg"=>$chat_message,"message_attachment"=>$message_attachment,"first_name"=>$first_name,"last_name"=>$last_name,"profile_pic"=>$profile_pic,"page_name"=>$page_name,"page_picture"=>$page_picture,"access_token"=>$access_token);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->generate_incoming_fb($chat_data);
}
elseif($action == "fb_message_panel"){
    $chat_data = array("user_id"=>$user_id);
    //$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->fb_message_panel($chat_data);
}
elseif($action == "fb_single_chat"){
    $chat_data = array("user_id"=>$user_id,"sender_id"=>$sender_id);
    //$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->fb_single_chat($chat_data);
}
/*elseif($action == "fb_reply_message"){
    $chat_data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"sender_id"=>$sender_id,"chat_message"=>$chat_message);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->fb_reply_message($chat_data);
}*/
elseif($action == "fb_reply_message"){
    $chat_data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"sender_id"=>$sender_id,"chat_message"=>$chat_message);
    $media='';
    $result_data["result"]["status"] = true;
	 
    $result_data["result"]["data"] = $chat->fb_reply_message($chat_data,$media);
	//echo "12e5r12rt";exit;
}
elseif($action == "fb_reply_media_upload"){
	//echo"43434";exit;
    $chat_data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"sender_id"=>$sender_id,"facebook_media"=>$facebook_media);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->fb_reply_media_upload($chat_data);
}
elseif($action == "whatsapp_media_upload"){  
	//echo "12";exit;
    $chat_data = array("whatsapp_media"=>$whatsapp_media,"user_id"=>$user_id,"chat_id"=>$chat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->whatsapp_media_upload($chat_data);
}
elseif($action == "compose_whatsapp"){
      $chat_data = array("user_id"=>$user_id,"phone_num"=>$phone_num,"chat_msg"=>$chat_message,"chat_status"=>1,"country_code"=>$country_code,"timezone_id"=>$timezone_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeMessage($chat_data);
}
elseif($action == "compose_bulk_whatsapp"){ 
        $chat_data = array("timezone_id"=>$timezone_id,"group"=>$group,"user_id"=>$user_id,"chat_msg"=>$chat_message,"chat_status"=>1,"admin_id"=>$admin_id);    
        $result_data["result"]["status"] = true;
        $result_data["result"]["data"] = $chat->ComposeGroupWhatsappMessage($chat_data);
}
elseif($action == "bulk_whatsapp_media_upload"){  
    $chat_data = array("whatsapp_media"=>$whatsapp_media,"user_id"=>$user_id,"chat_msg"=>$chat_msg,"timezone_id"=>$timezone_id,"group"=>$group,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->bulk_whatsapp_upload($chat_data);
}
elseif($action == "single_whatsapp_media_upload"){  
    $chat_data = array("whatsapp_media"=>$whatsapp_media,"user_id"=>$user_id,"chat_msg"=>$chat_msg,"timezone_id"=>$timezone_id,"country_code"=>$country_code,"phone_num"=>$phone_num,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->single_whatsapp_media_upload($chat_data);
}
elseif($action == "insert_sms_tarrif"){  
    $chat_data = array("id"=>$id,"price"=>$price,"plan_id"=>$plan_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insert_sms_tarrif($chat_data);
}
elseif($action == "getsms_tarrif"){  
    //$chat_data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getsms_tarrif();
}
elseif($action == "add_new_tarrif"){  
    $chat_data = array("tarrif_name"=>$tarrif_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->add_new_tarrif($chat_data);
}
elseif($action == "del_tarrif"){  
    $chat_data = array("tarrif_name"=>$tarrif_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->del_tarrif($chat_data);
}
elseif($action == "view_tarrif"){  
   // $chat_data = array("tarrif_name"=>$tarrif_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->view_tarrif();
}
elseif($action == "get_sel_tarrif"){  
    $chat_data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->get_sel_tarrif($chat_data);
}