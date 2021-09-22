<?php
if($_REQUEST['action']){ $action = $_REQUEST['action'];}
if($_REQUEST['chat_type']){ $chat_type = $_REQUEST['chat_type'];}

$result_data["status"] = true;
$webChat = new webChat();

if($action == "generate_chat"){
//print_r($_REQUEST); exit;
    //$customer_data =  array("customer_name"=>$customer_name,"customer_email"=>$customer_email,"customer_web_code"=>$customer_web_code);
	$customer_data =  array("customer_name"=>$customer_name,"customer_email"=>$customer_email,"customer_web_code"=>$customer_web_code,"client_city"=>$client_city,"client_country"=>$client_country,"client_ip"=>$client_ip);


     $admin_id = base64_decode($admin_id);
	 $widget_name = base64_decode($widget_name);
    $chat_data = $webChat->generateChat($customer_data,$chat_message,$queue_id,$admin_id,$department,$widget_name,$chatUrl,$msg_user_type,$flag);
      
       // $admin->errorLog('testing',json_encode($chat_data));
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_data"] =$chat_data;

}
elseif($action == "update_chat_message"){
	extract($_REQUEST);
    
    $chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$customer_id, "msg_user_type"=>1,"msg_type"=>"text","chat_msg"=>$chat_message,"msg_status"=>1);
             
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_msg_id"] = $webChat->update_chat_message($chat_data);
    
}
elseif($action == "get_chat_queue"){
    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["queue_data"] = $webChat->getChatQueue();
    
}
/*elseif($action == "update_chat_status"){    
    $chat_data = array("chat_id"=>$chat_id,"chat_status"=>2);     
    $result_data["result"]["data"] = $webChat->updateChatStatus($chat_data);   
}*/

elseif($action == "on_off_status"){
	
	    $chat_data = array("chat_id"=>$chat_id,"onoff_status"=>$onoff_status);
	    $result_data["result"]["status"] = true;
    	$result_data["result"]["data"] = $webChat->updateOnOffStatus($chat_data);
}	

elseif($action == "addchatbot_message"){
   //print_r($_REQUEST); exit;   
   $chat_data = $webChat->insertChatBotMessage($chat_id,$customer_id,$chat_message,$msg_user_type);   
   $result_data["result"]["status"] = true;
   $result_data["result"]["data"]["chat_data"] =$chat_data;
}
elseif($action == "newchat_generate"){
    //print_r($_REQUEST); exit;
    $admin_id = base64_decode($admin_id);
	$widget_name = base64_decode($widget_name);	
	$chat_data = $webChat->generateNewChat($chat_id,$customer_id,$msg_user_type,$chat_message,$admin_id,$widget_name,$department);        
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["chat_data"] =$chat_data;
}    
   