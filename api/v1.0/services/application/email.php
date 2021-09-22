<?php
$result_data["status"] = true;
$queue = new queue();
$email = new email();
$user = new userData();

// if($action == "chat_initialize"){
    
//     $queue_user_access = $queue->userQueueAccess($user_id);
//     $queue_features = $queue->queueFeaturesList();
	
//     $result_data["result"]["status"] = true;
//     $result_data["result"]["data"]["user_access"] = $queue_user_access;
//     $result_data["result"]["data"]["queue_features"] = $queue_features;
//     $result_data["result"]["data"]["queue_chat_list"] = $chat->getcustomersChat($user_id,"");
// 	$result_data["result"]["data"]["mc_event_list"] = $chat->mcEvents();

// }

if($action == "get_queue_mail_list"){
    
    $result_data["result"]["status"] = true;
    
    $result_data["result"]["data"]["queue_mail_list"] = $email->getcustomersMail($user_id,$queue_id);
    
    
}

elseif($action == "email_panel"){
	$mail_detail_list == "";
	if($chat_id != "all" && $chat_id != "" && $chat_id != 0){
		$mail_detail_list = $email->mailDetailList($chat_id);
	}
	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"]["mail_list"] = $email->getcustomersMail($user_id,"");
    $result_data["result"]["data"]["mail_detail_list"] = $mail_detail_list;
}

elseif($action == "mail_detail_list"){
  $mail_detail_list =   $email->mailDetailList($chat_id);

	
    $result_data["result"]["status"] = true;
    
    $result_data["result"]["data"]["mail_detail_list"] = $mail_detail_list;
    
    
}

elseif($action == "send_reply_email"){
    
    $chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$user_id, "msg_user_type"=>2,"msg_type"=>"text","chat_msg"=>$chat_message,"msg_status"=>1);
             
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $email->insertMailThread($chat_data);
    
}