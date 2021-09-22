<?php
 $inc_wp_response = file_get_contents('php://input',true);
$inc_wp_response = str_replace("'", "/////", $inc_wp_response);
 $log = file_put_contents('email_test2.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
// message From Me
//$inc_wp_response ='{"ts":1628659582350,"sessionId":"c4c-inst8","id":"65356642-6bf0-4739-a760-f406149a1783","event":"onMessage","data":{"id":"false_919159266344@c.us_3EB07C93ACEC0F6A3852","body":"wfwef","type":"chat","t":1628659581,"notifyName":"","from":"919159266344@c.us","to":"14692423571@c.us","self":"in","ack":0,"invis":false,"isNewMsg":true,"star":false,"recvFresh":true,"isFromTemplate":false,"broadcast":false,"quotedMsg":null,"mentionedJidList":[],"isVcardOverMmsDocument":false,"isForwarded":false,"labels":[],"ephemeralOutOfSync":false,"productHeaderImageRejected":false,"isDynamicReplyButtonsMsg":false,"isMdHistoryMsg":false,"chatId":"919159266344@c.us","fromMe":false,"sender":{"id":"919159266344@c.us","name":"+91 91592 66344","pushname":"Kumar🤠","type":"in","isBusiness":false,"isEnterprise":false,"statusMute":false,"labels":[],"formattedName":"+91 91592 66344","isMe":false,"isMyContact":true,"isPSA":false,"isUser":true,"isWAContact":true,"profilePicThumbObj":{"eurl":"https://pps.whatsapp.net/v/t61.24694-24/178771976_599724191419726_5442312785396106468_n.jpg?ccb=11-4&oh=ab5ec7057080c7a45b03f45e37f3fc06&oe=611838F2","id":"919159266344@c.us","img":"https://web.whatsapp.com/pp?e=https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F178771976_599724191419726_5442312785396106468_n.jpg%3Fccb%3D11-4%26oh%3Dab5ec7057080c7a45b03f45e37f3fc06%26oe%3D611838F2&t=s&u=919159266344%40c.us&i=1624254269&n=PdRISu5xK7b9I9NOWGJh7qNHPREe%2FSk1DY70CDV27xA%3D","imgFull":"https://web.whatsapp.com/pp?e=https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F178771976_599724191419726_5442312785396106468_n.jpg%3Fccb%3D11-4%26oh%3Dab5ec7057080c7a45b03f45e37f3fc06%26oe%3D611838F2&t=l&u=919159266344%40c.us&i=1624254269&n=PdRISu5xK7b9I9NOWGJh7qNHPREe%2FSk1DY70CDV27xA%3D","raw":null,"tag":"1624254269"},"msgs":null},"timestamp":1628659581,"content":"wfwef","isGroupMsg":false,"isMedia":false,"isNotification":false,"isPSA":false,"chat":{"id":"919159266344@c.us","pendingMsgs":false,"lastReceivedKey":{"fromMe":false,"remote":"919159266344@c.us","id":"3EB04A35C204B18EC670","_serialized":"false_919159266344@c.us_3EB04A35C204B18EC670"},"t":1628659408,"unreadCount":1,"archive":false,"isReadOnly":false,"modifyTag":17196,"muteExpiration":0,"name":"+91 91592 66344","notSpam":true,"pin":1600852652,"ephemeralDuration":0,"ephemeralSettingTimestamp":0,"msgs":null,"kind":"chat","canSend":true,"isGroup":false,"formattedTitle":"+91 91592 66344","contact":{"id":"919159266344@c.us","name":"+91 91592 66344","pushname":"Kumar🤠","type":"in","isBusiness":false,"isEnterprise":false,"statusMute":false,"labels":[],"formattedName":"+91 91592 66344","isMe":false,"isMyContact":true,"isPSA":false,"isUser":true,"isWAContact":true,"profilePicThumbObj":{"eurl":"https://pps.whatsapp.net/v/t61.24694-24/178771976_599724191419726_5442312785396106468_n.jpg?ccb=11-4&oh=ab5ec7057080c7a45b03f45e37f3fc06&oe=611838F2","id":"919159266344@c.us","img":"https://web.whatsapp.com/pp?e=https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F178771976_599724191419726_5442312785396106468_n.jpg%3Fccb%3D11-4%26oh%3Dab5ec7057080c7a45b03f45e37f3fc06%26oe%3D611838F2&t=s&u=919159266344%40c.us&i=1624254269&n=PdRISu5xK7b9I9NOWGJh7qNHPREe%2FSk1DY70CDV27xA%3D","imgFull":"https://web.whatsapp.com/pp?e=https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F178771976_599724191419726_5442312785396106468_n.jpg%3Fccb%3D11-4%26oh%3Dab5ec7057080c7a45b03f45e37f3fc06%26oe%3D611838F2&t=l&u=919159266344%40c.us&i=1624254269&n=PdRISu5xK7b9I9NOWGJh7qNHPREe%2FSk1DY70CDV27xA%3D","raw":null,"tag":"1624254269"},"msgs":null},"groupMetadata":null,"presence":{"id":"919159266344@c.us","chatstates":[]},"isOnline":false,"participantsCount":1},"isOnline":false,"quotedMsgObj":null,"mediaData":{}},"webhook_id":"1741fe1f-8f8a-4f69-a723-862adf9b6e66"}';


$inc_wp_response = json_decode($inc_wp_response);


//print_r($inc_wp_response); exit;
if($inc_wp_response->event == 'onAck'){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@c.us","",$inc_wp_response->data->from);	
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$timestamp = $inc_wp_response->data->ephemeralStartTimestamp;	
$element_data = array('action' => 'change_wp_status_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'ack'=>$ack,'time'=>$timestamp,'self'=>$self);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);	
} elseif($inc_wp_response->data->self == 'out' && $inc_wp_response->data->isGroupMsg == '1'){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@c.us","",$inc_wp_response->data->from);	
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
$receiver_name = $inc_wp_response->data->chat->contact->formattedName;
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$receiver_prof_pic = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
$timestamp = $inc_wp_response->data->timestamp;
	$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$element_data = array('action' => 'generate_outgoing_group_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'receiver_prof_pic'=>$receiver_prof_pic,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'receiver_name'=>$receiver_name,'chat_type'=>$chat_type);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);

} elseif($inc_wp_response->data->self == 'in' && $inc_wp_response->data->isGroupMsg == '1' && $inc_wp_response->event == 'onMessage' && $inc_wp_response->data->type == 'chat' ){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@g.us","",$inc_wp_response->data->from);	
$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
$group_icon = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$sender_name = $inc_wp_response->data->sender->pushname;
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$group_name = $inc_wp_response->data->chat->contact->name;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
$timestamp = $inc_wp_response->data->timestamp;
$element_data = array('action' => 'generate_incoming_group_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'group_icon'=>$group_icon,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'sender_name'=>$sender_name,'group_name'=>$group_name,'chat_type'=>$chat_type);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
}  elseif($inc_wp_response->data->self == 'in' && $inc_wp_response->data->isGroupMsg == '1' && $inc_wp_response->event == 'onMessage' && $inc_wp_response->data->type == 'image' ){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@g.us","",$inc_wp_response->data->from);	
$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
		$inc_caption = str_replace("%0","\n",$inc_wp_response->data->caption);	
$group_icon = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$sender_name = $inc_wp_response->data->sender->pushname;
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$group_name = $inc_wp_response->data->chat->contact->name;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
$timestamp = $inc_wp_response->data->timestamp;
$element_data = array('action' => 'generate_incoming_group_image_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'group_icon'=>$group_icon,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'sender_name'=>$sender_name,'group_name'=>$group_name,'chat_type'=>$chat_type,'caption'=>$inc_caption);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
} elseif($inc_wp_response->data->self == 'out'){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@c.us","",$inc_wp_response->data->from);	
$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
$receiver_name = $inc_wp_response->data->chat->contact->pushname;
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$receiver_prof_pic = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
$timestamp = $inc_wp_response->data->timestamp;
$element_data = array('action' => 'generate_outgoing_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'receiver_prof_pic'=>$receiver_prof_pic,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'receiver_name'=>$receiver_name,'chat_type'=>$chat_type);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);

} elseif($inc_wp_response->data->self == 'in' && $inc_wp_response->event == 'onMessage' && $inc_wp_response->data->type == 'chat'){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

$from_num = str_replace("@c.us","",$inc_wp_response->data->from);	
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);
	
	if($inc_wp_response->data->chat->contact->pushname == ''){
		$sender_name = $inc_wp_response->data->chat->contact->verifiedName;
	} else {
		$sender_name = $inc_wp_response->data->chat->contact->pushname;
	}
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$receiver_prof_pic = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
	$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$timestamp = $inc_wp_response->data->timestamp;
$element_data = array('action' => 'generate_incoming_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'receiver_prof_pic'=>$receiver_prof_pic,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'sender_name'=>$sender_name,'chat_type'=>$chat_type);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
}  elseif($inc_wp_response->data->self == 'in' && $inc_wp_response->event == 'onMessage' && $inc_wp_response->data->type == 'image'){
$message_id = $inc_wp_response->data->id;
$inc_message = str_replace("%0","\n",$inc_wp_response->data->body);	

	$inc_caption = str_replace("%0","\n",$inc_wp_response->data->caption);	
$from_num = str_replace("@c.us","",$inc_wp_response->data->from);	
$to_num = str_replace("@c.us","",$inc_wp_response->data->to);	
$sender_name = $inc_wp_response->data->chat->contact->pushname;
$sender_prof = $inc_wp_response->data->sender->profilePicThumbObj->eurl;
$receiver_prof_pic = $inc_wp_response->data->chat->contact->profilePicThumbObj->eurl;
$fromMe = $inc_wp_response->data->fromMe;
$self = $inc_wp_response->data->self;
$ack = $inc_wp_response->data->ack;
$isGroupMsg = $inc_wp_response->data->isGroupMsg;
	$chat_type = str_replace("@c.us","",$inc_wp_response->data->type);
$timestamp = $inc_wp_response->data->timestamp;
$element_data = array('action' => 'generate_incoming_image_wp_unoff','from'=>$from_num,'message'=>$inc_message,'to'=>$to_num,'message_id'=>$message_id,'sender_prof'=>$sender_prof,'receiver_prof_pic'=>$receiver_prof_pic,'ack'=>$ack,'isGroupMsg'=>$isGroupMsg,'time'=>$timestamp,'fromMe'=>$fromMe,'self'=>$self,'sender_name'=>$sender_name,'chat_type'=>$chat_type,"caption"=>$inc_caption);
$fields = array('operation' =>'wp_instance','moduleType' => 'wp_instance','api_type' => 'web', 'element_data' => $element_data);
}

//print_r(json_encode($fields)); exit;



//

$post = http_build_query($fields);

//print_r($post);exit;

$url = 'https://ticketing.mconnectapps.com/api/v1.0/index_new.php';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, 1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
$result = curl_exec($ch);
curl_close($ch);
print_r($result);


/*$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,"https://omni.mconnectapps.com/api/v1.0/omni-whatsapp/dummy.php");
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_api));
curl_setopt($curl, CURLOPT_TIMEOUT, 40);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result=curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl); 


print_r($result); */


exit;
