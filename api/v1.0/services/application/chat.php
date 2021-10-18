	<?php

if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
if($_REQUEST['action']){ $action = $_REQUEST['action'];}
if($_REQUEST['chat_type']){ $chat_type = $_REQUEST['chat_type'];}



$result_data["status"] = true; 
$queue = new queue();
$chat = new chat();
$user = new userData();



if($chat_type == "webchat"){
	
    if($action == "chat_initialize"){
  
    $queue_user_access = $queue->userQueueAccess($user_id);
    $queue_features = $queue->queueFeaturesList();
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["user_access"] = $queue_user_access;
    $result_data["result"]["data"]["queue_features"] = $queue_features;
    $result_data["result"]["data"]["queue_chat_list"] = $chat->getcustomersChatWc($user_id,"");
    $result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents($user_id);

}

elseif($action == "mc_event_list" ){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents($user_id);
}

elseif($action == "get_queue_chat_list" ){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChatWc($user_id,$queue_id,$search_text);   
}

elseif($action == "chat_message_panel" ){
//echo '11';exit;
        $chat_detail_list == "";
    if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
        $chat_detail_list = $chat->chatDetailListWc($chat_id);
    }
    $result_data["result"]["status"] = true;
    //$result_data["result"]["data"]["chat_list"] = $chat->getcustomersChatWC($user_id,"","",$status);
	$result_data["result"]["data"]["chat_list"] = $chat->getcustomersChatWC($user_id,$ext_no,"",$search_text,$status);
    $result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list;
}

elseif($action == "chat_detail_list" ){
    
    $result_data["result"]["status"] = true;
    
    $result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailListWc($chat_id);
    
    
}

elseif($action == "send_chat_message" ){

	extract($_REQUEST);
	
    $chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$user_id,"extension"=>$ext_no,"customer_name"=>$customer_name, "msg_user_type"=>2,"msg_type"=>"text","chat_msg"=>$chat_message,"msg_status"=>1);
             
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessageWc($chat_data);
    
}
	
	
} 
else {
if($action == "chat_initialize"){
    $queue_user_access = $queue->userQueueAccess($user_id);
    $queue_features = $queue->queueFeaturesList();    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["user_access"] = $queue_user_access;
    $result_data["result"]["data"]["queue_features"] = $queue_features;	
    $result_data["result"]["data"]["queue_chat_list"] = $chat->getcustomersChat($user_id,"","","","");	
    $result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents($user_id);
    //$result_data["result"]["data"]["whatsapp_chat_list"] = $chat->mcwhatsapp($user_id);
	//$result_data["result"]["data"]["sms_list"] = $chat->mcsms($user_id);
	//print_r($result_data); exit;
}
elseif($action == "chat_message_panel"){
//echo '2';exit;
    $chat_detail_list == "";
    if($chat_id != "all" && $chat_id != "" && $chat_id != 0){		
        $chat_detail_list = $chat->chatDetailList($chat_id);
    }
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChat($user_id,"",$search_text,$limit,$offset);
	$result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list; 
}

elseif($action == "chat_detail_list"){

    $result_data["result"]["status"] = true;
    
    $result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailList($chat_id, $admin_id,$limit,$offset);
}
elseif($action == "send_chat_message"){
 
	
	
    $chat_data = array("timezone_id"=>$timezone_id,"chat_id"=>$chat_id,"agent_id"=>$user_id, "msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_message,"chat_status"=>1);
             
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertChatMessage($chat_data);
}

elseif($action == "compose_sms"){
      $chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"user_id"=>$user_id,"chat_msg"=>$chat_message,"chat_status"=>1,"sender_id"=>$sender_id,"country_code"=>$country_code,"admin_id"=>$admin_id,"from_external"=> "0");
	$result_data["result"]["status"] = true;
	
	if($admin_id == '870'){
		$result_data["result"]["data"] = $chat->compose_sms_imap($chat_data);
	} else {
	    $result_data["result"]["data"] = $chat->ComposeChatMessage($chat_data);
	}   
}
	
	
elseif($action == "compose_sms_imap"){
      $chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"user_id"=>$user_id,"chat_msg"=>$chat_message,"admin_id"=>$admin_id,"from_external"=>$from_external);
    $result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $chat->compose_sms_imap($chat_data);
    
}	
	
elseif($action == "compose_bulk_sms"){
 
	    $chat_data = array("timezone_id"=>$timezone_id,"group"=>$group,"user_id"=>$user_id,"chat_msg"=>$chat_message,"chat_status"=>1,"sender_id"=>$sender_id,"admin_id"=>$admin_id);
	
	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeGroupChatMessage($chat_data);
}
	
elseif($action == "generate_incoming_sms"){
    $phone_num = $_REQUEST['element_data']['phone_num'];
    $chat_message = $_REQUEST['element_data']['message'];
    $user_id = $_REQUEST['element_data']['created_by'];
    
    $chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"user_id"=>$user_id,"chat_msg"=>$chat_message,"chat_status"=>1,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->ComposeIncommingChatMessage($chat_data);
}
	
	
elseif($action == "chat_question"){    
    $data = array("admin_id"=>$admin_id, "widget_id"=>$widget_id, "chat_question"=>$chat_question, "chat_answer"=>$chat_answer);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertchat_question($data);
}
elseif($action == "chat_data"){    
    $data = array("admin_id"=>$admin_id, "chat_question"=>$chat_question);
    //$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->get_answer($data);
}
elseif($action == "get_question"){
    $data = array("admin_id"=>$admin_id, "widget_id"=>$widget_id);
    $result_data["result"]["data"] = $chat->get_question($data);
}
elseif($action == 'edit_chatquestion'){
    $data= array("admin_id"=>$admin_id, "id"=>$id);    
    $result_data["result"]["data"] = $chat->edit_chatquestion($data);   
}
elseif($action == "update_chatquestion"){    
    $data = array("id"=>$id, "admin_id"=>$admin_id, "widget_id"=>$widget_id, "chat_question"=>$chat_question, "chat_answer"=>$chat_answer);    
    $result_data["result"]["data"] = $chat->update_chatquestion($data);
}
elseif($action == "delete_chatquestion"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);       
    $result_data["result"]["data"] = $chat->delete_chatquestion($data);
}
elseif($action == "get_chat_settings"){     
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $chat->get_chat_settings($data);
}
elseif($action == "update_chat_settings"){     
    $data = array("user_id"=>$user_id,"chat_place"=>$chat_place,"chat_aviator"=>$chat_aviator,"office_in_time"=>$office_in_time,"office_out_time"=>$office_out_time,"offline_email"=>$offline_email,"chat_agent_name"=>$chat_agent_name);
    $result_data["result"]["data"] = $chat->chat_settings($data);
}
elseif($action == "chat_initiate"){     
    $data = array("user_id"=>$user_id,"chat_time"=>$chat_time);
    $result_data["result"]["data"] = $chat->chat_initiate($data);
}
elseif($action == "chat_offline_message"){
	 $widget_name = base64_decode($widget_id);;
    $data = array("user_id"=>$user_id,"widget_name"=>$widget_name,"name"=>$name,"email"=>$email,"department"=>$department,"chat_message"=>$chat_message);
    $result_data["result"]["data"] = $chat->chat_offline_message($data);
}
elseif($action == "chat_rating"){     
    $data = array("chat_id"=>$chat_id,"rating_value"=>$rating_value);     
    $result_data["result"]["data"] = $chat->chat_rating($data);
}
elseif($action == "chat_list"){
	//$user_id = base64_decode($user_id);
	$data = array("user_id"=>$user_id,"limit"=>$limit,"offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->chat_list($data);
}
elseif($action == "chat_details"){
	$widget_name = base64_decode($widget_name);
    $data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"widget_name"=>$widget_name);	
    $result_data["result"]["data"] = $chat->chat_details($data);
}
	
}















if($action == "mc_event_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents($user_id);
}
elseif($action == "get_queue_chat_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->getcustomersChat($user_id,$queue_id,$search_text); 
}
elseif($action == "get_pbx_settings"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getpbxsettings($user_id);   
}
elseif($action == "get_pbx_settingss"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getpbxsettingss($user_id);  
}
elseif($action == "get_pbx_details"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getpbxdetails($user_id);    
}
elseif($action == "get_single_pbx_settings"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->getsinglepbxsettings($pbx_id);  
}
elseif($action == "edit_single_pbx_settings"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->editsinglepbxsettings($edit_fom_url,$sip_port,$sip_url);  
}
elseif($action == "add_single_pbx_settings"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->addsinglepbxsettings($sip_port,$sip_url,$admin_id);   
}
elseif($action == "delete_pbx"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $chat->delete_pbx($data);
}
elseif($action == "test_sms"){
    $chat_data = array("phone_num"=>$phone_num,"chat_msg"=>$chat_msg);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->test($chat_data);
}

elseif($action == "sms_price_view"){
    $chat_data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->sms_price_view($chat_data);
}

elseif($action == "chat_det"){
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->chat_det($data);
}
elseif($action == "send_sms"){
    $data = array("sender_id"=>$sender_id,"phone_no"=>$phone_no,"country_code"=>$country_code,"message"=>$message,"company_name"=>$company_name,"username"=>$username,"password"=>$password);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->send_sms($data);
}
	
elseif($action == "generate_incoming_message"){
	
	
    $phone_num = $_REQUEST['element_data']['from'];
    $chat_message = $_REQUEST['element_data']['message'];
    $message_time = $_REQUEST['element_data']['message_time'];
    $user_id = $_REQUEST['element_data']['user_id'];
    $chat_data = array("phone_num"=>$phone_num,"chat_msg"=>$chat_message,"chat_status"=>1,"message_time"=>$message_time,"to"=>$to,"user_id"=>$user_id);
	//print_r($chat_data); echo 'dsfdsds'; exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->generate_incoming_message($chat_data);
}
elseif($action == "generate_incoming_message_from_outer"){
	
	
    $phone_num = $_REQUEST['element_data']['from'];
    $chat_message = $_REQUEST['element_data']['message'];
    $message_time = $_REQUEST['element_data']['message_time'];
    $to = $_REQUEST['element_data']['to'];
	$customer_pnr = $_REQUEST['element_data']['messageId'];
    $chat_data = array("phone_num"=>$phone_num,"chat_msg"=>$chat_message,"chat_status"=>1,"message_time"=>$message_time,"to"=>$to,"customer_pnr"=>$customer_pnr);
	//print_r($chat_data); echo 'fss'; exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->generate_incoming_message_from_outer($chat_data);
}

elseif($action == "generate_incoming_message_from_imap"){
    $phone_num = $_REQUEST['element_data']['from'];
    $chat_message = $_REQUEST['element_data']['message'];
    $message_time = $_REQUEST['element_data']['message_time'];
    $to = $_REQUEST['element_data']['to'];
    $chat_data = array("phone_num"=>$phone_num,"chat_msg"=>$chat_message,"chat_status"=>1,"message_time"=>$message_time,"to"=>$to);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->generate_incoming_message_from_outer($chat_data);
}

elseif($action == "userRecentChat"){
   // $chat_data = array("chat_id"=>$chat_id,"company"=>$cname,"username"=>$username,"password"=>$password); 
	$chat_data = array("chat_id"=>$chat_id,"login"=>$login);
    $chat_detail_list == "";
    if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
        $chat_detail_list = $chat->chatDetailList($chat_id);
    }
	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_list"] = $chat->userRecentChat($chat_data);
	$result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list; 
	$result_data["result"]["mData"] = $chat->getMyDatas($chat_data);
	$result_data["result"]["template"] = $chat->listTemplateExternal($login);
	$result_data["result"]["senderDetails"] = $chat->get_senderidE($login);
}
elseif($action == "chatDetailListFromExternal"){
   // $chat_data = array("chat_id"=>$chat_id,"company"=>$cname,"username"=>$username,"password"=>$password); 
	
	$chat_data = array("chat_id"=>$chat_id,"login"=>$login); 
	$result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailListFromExternal($chat_data); 
	$result_data["result"]["mData"] = $chat->getMyDatas($chat_data);
}

elseif($action == "compose_sms_external"){
      $chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"login"=>$login,"chat_msg"=>$chat_message,"chat_status"=>1,"sender_id"=>$sender_id,"country_code"=>$country_code);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->compose_sms_external($chat_data);
}
elseif($action == 'sms_list'){
	$data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->listsms($data);   
}
	
elseif($action == "get_chatBotQA"){
    $data = array("url"=>$url,"widget_name"=>$widget_name);
    $result_data["result"]["data"] = $chat->get_chatBotQA($data);
}		
elseif($action == "chatbot_det"){
    $data = array("url"=>$url,"email"=>$email,"ph_no"=>$ph_no,"country_code"=>$country_code);
    $result_data["result"]["data"] = $chat->chatbot_det($data);
}
elseif($action == "send_notification"){
	  $data = array("text"=>$text,"user_id"=>$user_id);
    $result_data["result"]["data"] = $chat->send_notification($data);
}
elseif($action == "chat_list_report"){  
	$data = array("user_id"=>$user_id,"from_date"=>$from_date,"to_date"=>$to_date);
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->chat_list_report($data);
}
elseif($action == "chat_closedby_user"){
	$widget_name = base64_decode($widget_name);    
	$data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"widget_name"=>$widget_name);
    $result_data["result"]["data"] = $chat->chat_closedby_user($data);
}
elseif($action == "mobile_admin_mail"){
		$widget_name = base64_decode($widget_name);    
	$data = array("user_id"=>$user_id,"chat_id"=>$chat_id,"widget_name"=>$widget_name);
    $result_data["result"]["data"] = $chat->mobile_admin_mail($data);
}
elseif($action == "add_ChatClose_keywords"){
	$data = array("user_id"=>$user_id, "keyword"=>$keyword,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->add_ChatClose_keywords($data);
}
elseif($action == "list_ChatClose_keywords"){
	$data = array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->list_ChatClose_keywords($data);
}
elseif($action == "edit_ChatClose_keywords"){	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->edit_ChatClose_keywords($id);
}
elseif($action == "update_ChatClose_keywords"){
	$data= array("id"=>$id,"keyword"=>$keyword);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->update_ChatClose_keywords($data);
}
elseif($action == "delete_ChatClose_keywords"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->delete_ChatClose_keywords($key_id);
}



elseif($action == "overallChatSettings"){   
    $result_data["result"]["data"] = $chat->overallChatSettings($admin_id);
}

elseif($action == "overallChatSettingsUpdate"){   
	$data = array("admin_id"=>$admin_id,"round_robin"=>$round_robin,"offline_chat"=>$offline_chat);
    $result_data["result"]["data"] = $chat->overallChatSettingsUpdate($data);
}


elseif($action == "add_overallChatSettings"){   
	$data = array("admin_id"=>$admin_id,"round_robin"=>$round_robin,"offline_chat"=>$offline_chat);
    $result_data["result"]["data"] = $chat->add_overallChatSettings($data);
}
elseif($action == "chatTransfer"){  
    $chat_data = array("user_id"=>$user_id,"from_user_id"=>$from_user_id,"chat_id"=>$chat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->chatTransfer($chat_data);
}elseif($action == "revokeTransfer"){  
    $chat_data = array("chat_id"=>$chat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->revokeTransfer($chat_data);
}elseif($action == "chatAgents"){
    $chat_data = array("widget_id"=>$widget_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->chatAgents($chat_data);
}
elseif($action == "onoff_status"){
    $chat_data = array("widget_id"=>$widget_id,"admin_id"=>$admin_id,"value"=>$value,"type"=>$type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->onoff_status($chat_data);
}
elseif($action == "copy_chat_question"){
    $chat_data = array("widget_id"=>$widget_id,"admin_id"=>$admin_id,"chat_question_id"=>$chat_question_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->copy_chat_question($chat_data);
}
elseif($action == "chatMessagePanel" ){
    $chat_detail_list == "";
    if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
        $chat_detail_list = $chat->chatMessagePanelDetail($chat_id);
		 $result_data["result"]["data"]["chat_detail_list"] = $chat_detail_list;
    } else {
		$result_data["result"]["data"]["chat_list"] = $chat->chatMessagePanel($login,"","",$limit,$offset);
	}
    $result_data["result"]["status"] = true;
}