<?php

if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
if($_REQUEST['action']){ $action = $_REQUEST['action'];}
$result_data["status"] = true; 
$chat = new chatinternal();
if($action == "send_internal_chat_message"){ 
    extract($_REQUEST);
    $chat_data = array("chat_sender_id"=>$chat_sender_id,"chat_receiver_id"=>$chat_receiver_id,"admin_id"=>$admin_id,"msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_msg,"image_file"=>$_FILES['image_file']['name'],"timezone_id"=>$timezone_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->insertInternalChatMessage($chat_data);
}
elseif($action == "internal_chat_detail_list" ){ 
    $chat_data = array("user_id"=>$user_id,"agent_id"=>$agent_id,"admin_id"=>$admin_id);   
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"]["chat_detail_list"] = $chat->internal_chat_detail_list($chat_data);  
}
elseif($action == "dept_agent_list"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->dept_agent_list($chat_data);
}
elseif($action == "dept_agent_list_app"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->dept_agent_list_app($chat_data);
}
elseif($action == "get_by_id"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id,"agent_id"=>$agent_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->get_by_id($chat_data);
}
elseif($action == "user_last_chat"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->user_last_chat($chat_data);
}
elseif($action == "userRecentChat"){  
    $chat_data = array("dial_time"=>$dial_time,"login"=>$login); 
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->userRecentChat($chat_data);
	$result_data["result"]["contacts"] = $chat->deptAgentLisst($chat_data);	
	$result_data["result"]["mData"] = $chat->getMyDatas($chat_data);
}
elseif($action == "getChatById"){  
    $chat_data = array("login"=>$login,"agent_id"=>$agent_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->getChatById($chat_data);
}
elseif($action == "getuserpermission"){  
	
    $data = array("dial_time"=>$dial_time,"login"=>$login); 
	
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat->getpermissions($data);
	
}
if($action == "sendChatmsgFromWidget"){    
    $chat_data = array("chat_sender_id"=>$chat_sender_id,"chat_receiver_id"=>$chat_receiver_id,"msg_from"=>"agent","msg_type"=>"text","chat_msg"=>$chat_msg,"login"=>$login);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat->sendChatmsgFromWidget($chat_data);
}
