<?php
$result_data["status"] = true;
$list = new zohocrm();

if($_REQUEST['action'] == 'activateSSO') {  	
	$action ='activateSSO'; 
}



if($action == "client_list"){   
	//$data = array("admin_id"=>$lead_token, "name"=>$name,"email"=>$email,"phone"=>$phone,"city"=>$city,"country"=>$country,"message"=>$message);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $list->clientList($admin_id);
} elseif($action == "client_add"){
	$data = array("admin_id"=>$admin_id, "client_id"=>$client_id,"client_secret_id"=>$client_secret_id,"redirect_url"=>$redirect_url,"client_fqdn"=>$external_client_fqdn);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->clientAdd($data);
} elseif($action == "client_delete"){
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->clientDlt($id);

} elseif($action == "client_update"){
	// $data = array("id"=>$id);
	$data = array("id"=>$id, "client_id"=>$client_id,"client_secret_id"=>$client_secret_id,"redirect_url"=>$redirect_url,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->clientUpdate($data);

} elseif($action == "client_access_code"){
	$data = array("admin_id"=>$admin_id,"access_code"=>$access_code,"refresh_code"=>$refresh_code);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->client_access_data($data);

}elseif($action == "client_refresh_code"){
	$data = array("id"=>$crm_id,"refresh_code"=>$refresh_code);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->client_refresh_data($data);
}
elseif($action == "zohoRinging"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zohoRinging($data);
}
elseif($action == "zohoAnswering"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zohoAnswering($data);
}
elseif($action == "zohoEnded"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id,"starting_call"=>$starting_call,"call_duration"=>$call_duration);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zohoEnded($data);
}
elseif($action == "zohoMissed"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id,"starting_call"=>$starting_call);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zohoMissed($data);
}
elseif($action == "zoho_outgoing_ring"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zoho_out_ring($data);
}
elseif($action == "zoho_outgoing_answered"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zoho_out_answered($data);
}
elseif($action == "zoho_outgoing_ended"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id,"starting_call"=>$starting_call,"call_duration"=>$call_duration);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zoho_out_ended($data);
}
elseif($action == "zoho_outgoing_unattended"){
	$data = array("crm_id"=>$crm_id,"type"=>$type,"state"=>$state,"from"=>$from,"to"=>$to,"call_id"=>$call_id,"starting_call"=>$starting_call);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->zoho_out_unattended($data);
}
elseif($action == "activateSSO"){
	$data = json_decode($_REQUEST['element_data'],true);
	extract($data);
	$data = array("sso_entity_id"=>$sso_entity_id,"sso_reply_url"=>$sso_reply_url,"azure_ad_id"=>$azure_ad_id,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->activateSSO($data);
}

elseif($action == "listSSO"){
   $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $list->listSSO($admin_id);
}
elseif($action == "listZohoUsers"){
   $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $list->listZohoUsers($admin_id);
}
elseif($action == "addZohoUsers"){
	$data = array("users"=>$data,"admin_id"=>$admin_id);
   $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $list->addZohoUsers($data);
}
elseif($action == "getExtensionUser"){
   $result_data["result"]["status"] = true;
   $result_data["result"]["data"] = $list->getExtensionUser($ext);
}
elseif($action == "addMSOmniUsers"){
	$data = array("users"=>$users,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->addMSOmniUsers($data);
}
elseif($action == "addMSTeamsUsers"){
	$data = array("users"=>$users,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $list->addMSTeamsUsers($data);
}
elseif($action == "ssoUsers"){
   $result_data["result"]["status"] = true;
   $result_data["result"]["data"] = $list->ssoUsers($admin_id);
}