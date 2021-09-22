<?php

if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
$result_data["status"] = true; 
$chat = new chat_telegram();

/*if($_REQUEST['action'] == 'whatsapp_media_upload') {  
    $action ='whatsapp_media_upload'; 
    $user_id = $_REQUEST['user_id'];
    $chat_id = $_REQUEST['chat_id'];        
    $whatsapp_media=$_FILES['whatsapp_media']['name'];
}

if($action == "mc_event_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents();
}*/

if($action == "generate_incoming_telegram"){
    $recipient_id = $_REQUEST['element_data']['recipient_id'];
    $sender_id = $_REQUEST['element_data']['sender_id'];
    $chat_message = $_REQUEST['element_data']['chat_message'];
	$name = $_REQUEST['element_data']['name'];   
	$token = $_REQUEST['element_data']['token']; 
    //$profile_pic = $_REQUEST['element_data']['profile_pic'];    
    $chat_data = array("recipient_id"=>$recipient_id,"sender_id"=>$sender_id,"chat_msg"=>$chat_message,"name"=>$name,"token"=>$token);

    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->generate_incoming_telegram($chat_data);
}
if($action == "telegram_message_panel"){
    $chat_data = array("user_id"=>$user_id);    
    $result_data["result"]["data"] = $chat->telegram_message_panel($chat_data);
}
if($action == "chat_detail_list"){    
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"]["chat_detail_list"] = $chat->chatDetailList($chat_id);
}
if($action == "reply_message"){    
    $result_data["result"]["status"] = true;
    $chat_data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"chat_id"=>$chat_id,"sender_id"=>$sender_id,"recipient_id"=>$recipient_id,"chat_message"=>$chat_message); 

    $result_data["result"]["data"] = $chat->reply_message($chat_data);
}
