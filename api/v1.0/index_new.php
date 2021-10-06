<?php 
//echo '13';exit;/print_r($_REQUEST); exit;
//if($_FILES != '' || $_REQUEST['element_data']['action'] == 'generate_incoming_sms' || $_REQUEST['element_data']['action'] == 'generate_incoming_wp' || $_REQUEST['element_data']['action'] == 'generate_incoming_fb' || $_REQUEST['element_data']['action'] == 'change_wp_status' || $_REQUEST['element_data']['action'] == 'change_wp_status_unoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_media_unoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_unoffs' || $_REQUEST['element_data']['action'] == 'generate_incoming_wp_unoff'){  include_once 'app_redirect.php'; exit; } else { echo 'error'; exit; }
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
header('Content-Type: text/plain; charset=utf-8');


if($_FILES != '' || $_REQUEST['element_data']['action'] == 'createExternalTicket' || $_REQUEST['element_data']['action'] == 'createTicketSignature' || $_REQUEST['element_data']['action'] == 'generate_incoming_sms' || $_REQUEST['element_data']['action'] == 'generate_incoming_wp' || $_REQUEST['element_data']['action'] == 'generate_incoming_wp_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_fb' || $_REQUEST['element_data']['action'] == 'change_wp_status' || $_REQUEST['element_data']['action'] == 'change_wp_status_unoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_media_unoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_unoffs' || $_REQUEST['element_data']['action'] == 'single_whatsapp_media_upload_uoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_unoff' || $_REQUEST['element_data']['action'] == 'generate_outgoing_wp_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_group_wp_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_image_wp_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_group_image_wp_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_message' || $_REQUEST['element_data']['action'] == 'generate_incoming_message_from_outer' || $_REQUEST['element_data']['action'] == 'generate_incoming_message_from_imap' || $_REQUEST['element_data']['action'] == 'sendWhatsappText' || $_REQUEST['element_data']['action'] == 'setWebHook' || $_REQUEST['element_data']['action'] == 'fb_reply_media_upload' || $_REQUEST['element_data']['rootfrom'] == 'mobile_apps' || $_REQUEST['element_data']['action'] == 'composeInternalMail'){ echo 'exit'; include_once 'app_redirect.php';  } else { echo 'error'; exit; }



require_once 'main.controller.php';
$admin = new adminData();
$server_data = $_SERVER;
$api_req = file_get_contents('php://input');
//print_r($api_req); exit;
$api_request_data = json_decode($api_req,true);

$result_data["status"] = false;
if(isset($api_request_data["moduleType"]) && $api_request_data["moduleType"] != ""){
	$moduleType = $api_request_data["moduleType"];
	$operation = $api_request_data["operation"];
	$api_type = $api_request_data["api_type"];
	$token_key = $api_request_data["access_token"];
	$exception_list = $admin->tokenExceptionList($moduleType, $operation);
	
	$access_token = "";
	if($token_key != ""){
	
		$token_validation = $admin->tokenValidation($token_key);
		$access_token = $token_validation["new_token"]; 
		$token_access_data = $token_validation["access_data"]; 
		extract($token_access_data);
		
	}
	
		if($exception_list == 1){		
		include_once 'app_redirect.php';
	}
	else{

		if($access_token != ""){
			include_once 'app_redirect.php';
		}
		else{
			$result_data["error"]["code"] = "ACCESS_DENIED";
			$result_data["error"]["message"] = "Invalid token"; 

		}

	}

}

else{
	$result_data["error"]["code"] = "ACCESS_DENIED";
	$result_data["error"]["message"] = "Invalid credentials1."; 

}
//$admin->errorLog("data1", $api_req);
$result_data["api_req"] = $api_request_data;
echo json_encode($result_data);


?>