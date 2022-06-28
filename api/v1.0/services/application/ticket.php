<?php
//echo 'test';exit;
$result_data["status"] = true;
$ticket = new ticket();

if($action == "get_dept_settings"){
    $data= array("user_id"=>$user_id,"type"=>$type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getDeptList($data);
}
elseif($action == "add_department"){
	
	//$data = array("department_name"=>$department_name,"department_users"=>$department_users,"department_wrapups"=>$department_wrapups, "status"=>$status,"admin_id"=>$admin_id);
	$data = array("department_name"=>$department_name,"department_users"=>$department_users,"alias_name"=>$alias_name,"status"=>$status,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addDepartment($data);
}
elseif($action == "retrive_department"){
	
	//$data = array("department_name"=>$department_name,"department_users"=>$department_users,"department_wrapups"=>$department_wrapups, "status"=>$status,"admin_id"=>$admin_id);
	$data = array("department_name"=>$department_name,"department_users"=>$department_users,"hardware_id"=>$hardware_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->retriveDepartment($data);
}
elseif($action == "import_department"){
	
	$data = array("department_name"=>$department_name,"hardware_id"=>$hardware_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->import_department($data);
}
elseif($action == "queue_user_assign_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"]["queue_assign_users"] = $queue->getQueueUserList($queue_id);
    $result_data["result"]["data"]["queue_features"] = $queue->queueFeaturesList();
    
}
elseif($action == "get_department"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getDept($dept_id,$admin_id);
}
elseif($action == "update_department"){
	$data = array("department_name"=>$department_name,"alias_name"=>$alias_name,"department_users"=>$department_users,"department_wrapups"=>$department_wrapups, "status"=>$status,"admin_id"=>$admin_id,"dept_id"=>$dept_id);

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateDepartment($data);
}
elseif($action == "get_agents_by_department"){

    $data= array("admin_id"=>$admin_id,"dept_id"=>$dept_id);

    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getAgentsDepartment($data);
}
elseif($action == "generate_ticket"){
	$data = array("department_id"=>$department_id,"res_departments"=>$res_departments,"activity"=>$activity, "note_id"=>$note_id,"user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->generateTicket($data);
}
elseif($action == "my_tickets"){
	$data = array( "user_type"=>$user_type,"user_id"=>$user_id);
	$result_data["result"]["status"] = true;
	/*$result_data["result"]["data"]["status"] = $status;
    if($status==1){
        $result_data["result"]["ticket_status"] = 'Open';
    }else{
        $result_data["result"]["ticket_status"] = 'Closed';
    }*/
    $result_data["result"]["data"] = $ticket->getMyTicket($data);
}
elseif($action == "view_tickets"){
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->viewMyTicket($ticket_id);
}
elseif($action == "re_assign_ticket"){
	$data = array( "department_id"=>$department_id,"user_id"=>$user_id,"ticket_id"=>$ticket_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->reAssignTicket($data);
}
elseif($action == "generate_sms_ticket"){
	$data = array("department_id"=>$department_id, "phone_num"=>$phone_num,"user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->generateSmsTicket($data);
}
elseif($action == "generate_wp_ticket"){
    $data = array("user_id"=>$user_id, "department_id"=>$department_id, "phone_num"=>$phone_num,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->generateWpTicket($data);
}
elseif($action == "replay_ticket"){
	$data = array( "department_id"=>$department_id,"user_id"=>$user_id,"ticket_id"=>$ticket_id,"message"=>$message);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->replayTicket($data);
}
elseif($action == "close_my_ticket"){
	$data = array( "ticket_id"=>$ticket_id,"user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->closeMyTicket($data);
}
elseif($action == 'ticket_reports'){
	$data= array("user_id"=>$user_id,"fromDate"=>$fromDate,"toDate"=>$toDate,"tic_status"=>$tic_status);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->ticketReport($data);   
}
elseif($action == 'advance_ticket_reports'){

    $data= array("user_id"=>$user_id,"customer_name"=>$customer_name,"fromDate"=>$fromDate,"toDate"=>$toDate,"search_text"=>$search_text,"limit"=>$limit,"offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $ticket->advanceTicketReport($data);
       
}
elseif($action == "export_ticket_reports"){ 
    $data= array("user_id"=>$user_id,"customer_name"=>$customer_name,"fromDate"=>$fromDate,"toDate"=>$toDate,"search_text"=>$search_text,"limit"=>$limit,"offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->export_ticket_reports($data);
}
elseif($action == "add_notAssigned_tickets"){ 
    $data = array("from"=>$from, "to"=>$to,"subject"=>$subject,"message"=>$message,"attachments"=>$attachments,"ticket_reply_id"=>$ticket_reply_id,"to_mail"=>$to_mail,"cc_mail"=>$cc_mail,"agent_short_code"=>$agent_short_code,"forward_from"=>$forward_from,"forward_to"=>$forward_to,"forward_cc"=>$forward_cc,"fattachments"=>$fattachments,"mail_type"=>$mail_type,"comments"=>$comments,"enquiry_comments"=>$enquiry_comments,"enquiry_company"=>$enquiry_company,"enquiry_country"=>$enquiry_country);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_notAssigned_tickets($data);
}
elseif($action == "list_notAssigned_tickets"){   
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_notAssigned_tickets($data);
}
elseif($action == "generate_external_ticket"){
    $data = array("id"=>$id,"department_id"=>$department_id,"assigned_to"=>$assigned_to,"ticket_status"=>$ticket_status,"priority"=>$priority,"user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->generateExternalTicket($data);
}
elseif($action == "my_externaltickets"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"ticket_status"=>$ticket_status,"ticket_department"=>$ticket_department,"is_spam"=>$is_spam,"ticket_user"=>$ticket_user);    
    $result_data["result"]["data"] = $ticket->getmyExternalTicket($data);
}
elseif($action == "getAlldetailsOfAgents"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $ticket->getAlldetailsOfAgents($data);
}
elseif($action == "onchange_priority"){
    $data = array("priority_id"=>$priority_id,"ticket_id"=>$ticket_id);	
    $result_data["result"]["data"] = $ticket->onchangePriority($data);
}
elseif($action == "onchange_status"){
    $data = array("status_id"=>$status_id,"ticket_id"=>$ticket_id,"user_id"=>$user_id,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $ticket->onchangeStatus($data);
}
elseif($action == "oncloseTocket"){
    $data = array("status_id"=>$status_id,"ticket_id"=>$ticket_id,"user_id"=>$user_id,"agent_name"=>$agent_name,"admin_id"=>$admin_id,"ticket_to"=>$ticket_to,"ticket_cc"=>$ticket_cc,"alert_status"=>$alert_status,"enquiry_dropdown_id"=>$enquiry_dropdown_id,"revisit"=>$revisit);    
    $result_data["result"]["data"] = $ticket->oncloseTocket($data);
}


elseif($action == "onchange_department"){
    $data = array("department_id"=>$department_id,"ticket_id"=>$ticket_id,"admin_id"=>$admin_id);  
    $result_data["result"]["data"] = $ticket->onchangeDepartment($data);
}
elseif($action == "viewExternalTicket"){
	$data= array("ticket_id"=>$ticket_id,"admin_id"=>$admin_id, "limit"=>$limit, "offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->viewExternalTicket($data);
}
elseif($action == "delete_department"){     
    $data = array("department_id"=>$department_id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $ticket->delete_department($data);
}
elseif($action == "external_ticket_dropdown"){
    $data= array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->external_ticket_dropdown($data);
}
elseif($_REQUEST['action'] == 'replyTicketMessage') {
	//echo 'ferfref';exit;
	$message = $_REQUEST['message'];
	$ticket_id = $_REQUEST['ticket_id'];
	$to = $_REQUEST['to'];
	$user_id = $_REQUEST['user_id'];
	$signature_id = $_REQUEST['signature_id'];
	$mail_cc = $_REQUEST['mail_cc'];
	$customer_id = $_REQUEST['customer_id'];
    $data= array("message"=>$message,"ticket_id"=>$ticket_id,"to"=>$to,"subject"=>$subject,"from"=>$from,"user_id"=>$user_id,"signature_id"=>$signature_id,"mail_cc"=>$mail_cc,"customer_id"=>$customer_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->replyMessage($data);
}
elseif($action == "external_ticket_bulk_assign"){
    $data= array("user_id"=>$user_id,"department"=>$department,"agent_id"=>$agent_id,"ticket_id"=>$ticket_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->external_ticket_bulk_assign($data);
}
elseif($_REQUEST['action'] == "createExternalTicket"){
	extract($_REQUEST);
	
    $data= array("user_id"=>$user_id,"department"=>$department,"agent_id"=>$agent_id,"admin_id"=>$admin_id,"status"=>$status,"priority"=>$priority_id,"subject"=>$subject,"description"=>$description,"to"=>$to,"from_address"=>$from_address,"mail_cc"=>$mail_cc );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->createExternalTicket($data);
}
elseif($action == "addNotesForTicketReply"){
	$data= array("ticket_message_id"=>$ticket_message_id,"admin_id"=>$admin_id,"ticket_notes"=>$ticket_notes,"user_id"=>$user_id,"user_name"=>$user_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addNotesForTicketReply($data);
}elseif($action == "updateTicketStatus"){
	$data= array("ticket_id"=>$ticket_id,"status"=>$status,"ticket_notes"=>$ticket_notes,"user_id"=>$user_id,"admin_id"=>$admin_id,"user_name"=>$user_name,"department"=>$department,"agent_id"=>$agent_id,"enquiry_dropdown_id"=>$enquiry_dropdown_id,"revisit"=>$revisit);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateTicketStatus($data);
}
elseif($_REQUEST['action'] == "createTicketSignature"){
	extract($_REQUEST);
    $data= array("admin_id"=>$admin_id,"is_default"=>$is_default,"sig_title"=>$sig_title,"sig_content"=>$sig_content,"user_id"=>$user_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->createTicketSignature($data);
}
elseif($action == "viewTicketSignature"){
	//extract($_REQUEST);
    	$data= array("admin_id"=>$admin_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->viewTicketSignature($data);
}
elseif($action == "editTicketSignature"){
	//extract($_REQUEST);
    	$data= array("admin_id"=>$admin_id,"sig_id"=>$sig_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->editTicketSignature($data);
}
elseif($_REQUEST['action'] == "updateTicketSignature"){
	extract($_REQUEST);
    $data= array("admin_id"=>$admin_id,"is_default"=>$is_default,"sig_title"=>$sig_title,"sig_content"=>$sig_content, "sig_id"=>$sig_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateTicketSignature($data);
}
elseif($action == "makeSignatureDefault"){
    $data= array("user_id"=>$user_id,"admin_id"=>$admin_id,"is_default"=>$is_default,"signature_id"=>$signature_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->makeSignatureDefault($data);
}elseif($action == "deleteSignature"){
    $data= array("admin_id"=>$admin_id,"signature_id"=>$sig_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->deleteSignature($data);
}elseif($action == "getmyDepartmentTicket"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"ticket_status"=>$ticket_status,"ticket_department"=>$ticket_department);    
    $result_data["result"]["data"] = $ticket->getmyDepartmentTicket($data);
}
elseif($action == "getAllEmailDept"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getAllEmailDept($admin_id);
}
elseif($action == "getEmailDept"){
    //$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getEmailDept($id);
}
elseif($action == "addUpdateDeptToEmail"){
	 $data= array("admin_id"=>$admin_id,"departments"=>$departments,"email"=>$email,"aliseEmail"=>$aliseEmail,"senderID"=>$senderID);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addUpdateDeptToEmail($data);
}
elseif($action == "delEmailDept"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delEmailDept($id);
}
elseif($action == "addUpdateDeptToEmailResponse"){
	 $data= array("admin_id"=>$admin_id,"response_for"=>$response_for,"content"=>$content,"status"=>$status,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addUpdateDeptToEmailResponse($data);
}
elseif($action == "getEmaiautoResponses"){
    $data= array("admin_id"=>$admin_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getEmaiautoResponses($data);
}
elseif($action == "EmailNotiTEST"){

    $data=  array("user_id"=>"1203","ticket_for"=>"New Ticket","ticket_from"=>"SK_MrVOIP","ticket_subject"=>"HEll0", "ticket_id"=>"##301");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->send_notificationTEST($data);
}
elseif($action == "claimMyTicket"){
	$data= array("ticket_id"=>$ticket_id,"user_id"=>$user_id,"user_name"=>$user_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->claimMyTicket($data);
}
elseif($action == "getIncomingEmailIds"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getIncomingEmailIds($admin_id);
	$result_data["result"]["spamLists"] = $ticket->spamLists($admin_id);
}
elseif($action == "blockEmailIds"){
	$data= array("email_id"=>$email_id,"admin_id"=>$admin_id,"spam_status"=>$spam_status,"blacklist_status"=>$blacklist_status,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->blockEmailIds($data);
}
elseif($action == "delSpamEmail"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delSpamEmail($email);
}
elseif($action == "searchTicketID"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"is_spam"=>$is_spam,"ticket_search"=>$ticket_search,"limit"=>$limit, "offset"=>$offset);  
    $result_data["result"]["data"] = $ticket->searchTicketID($data);
}
elseif($action == "agentAlertSettingsList"){
	$data= array("dept_id"=>$dept_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->agentAlertSettingsList($data);
}
elseif($action == "addAgentMailSetting"){
	$data= array("agent_id"=>$agent_id,"admin_id"=>$admin_id,"new_email_alert"=>$new_email_alert,"reply_email_alert"=>$reply_email_alert,"close_email_alert"=>$close_email_alert,"send_fullthread"=>$send_fullthread);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addAgentMailSetting($data);
}
elseif($action == "delEmailSettings"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delEmailSettings($id);
}
elseif($action == "update_type_settings"){
    $data= array("dept_id"=>$dept_id,"type"=>$type,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_type_settings($data);
}
elseif($action == "add_email_group"){
	$data = array("user_id"=>$user_id, "group_name"=>$group_name, "email"=>$email);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_email_group($data);
}
elseif($action == "list_email_group"){
	$data = array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_email_group($data);
}
elseif($action == "edit_email_group"){	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_email_group($group_id);
}
elseif($action == "update_email_group"){
	$data= array("group_id"=>$group_id,"group_name"=>$group_name, "email"=>$email,"new_email_alert"=>$new_email_alert,"reply_email_alert"=>$reply_email_alert,"close_email_alert"=>$close_email_alert,"send_fullthread"=>$send_fullthread);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_email_group($data);
}
elseif($action == "delete_email_group"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_email_group($group_id);
}
elseif($action == "addGroupMailSetting"){
	$data= array("group_id"=>$group_id,"new_email_alert"=>$new_email_alert,"reply_email_alert"=>$reply_email_alert,"close_email_alert"=>$close_email_alert,"send_fullthread"=>$send_fullthread);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addGroupMailSetting($data);
}
elseif($action == "addSmtpSetting"){
	$data= array("user_id"=>$user_id,"hostname"=>$hostname,"port"=>$port,"username"=>$username,"password"=>$password,"departments"=>$departments);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addSmtpSetting($data);
}
elseif($action == "list_smtp"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_smtp($user_id);
}
elseif($action == "get_smtp_byid"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->get_smtp_byid($id);
}
elseif($action == "updateSmtpSetting"){
	$data= array("id"=>$id,"hostname"=>$hostname,"port"=>$port,"username"=>$username,"password"=>$password,"departments"=>$departments);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateSmtpSetting($data);
}
elseif($action == "delete_smtp"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_smtp($id);
}
elseif($action == "set_default_smtp"){
    $data= array("id"=>$id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->set_default_smtp($data);
}
elseif($action == "get_agents"){
    $data = array("user_id"=>$user_id, "limit"=>$limit, "offset"=>$offset, "search_text"=>$search_text);    
    $result_data["result"]["data"] = $ticket->get_agents($data);
}
elseif($action == "update_agent_alert"){
    $data= array("user_id"=>$user_id,"new_email_alert"=>$new_email_alert,"reply_email_alert"=>$reply_email_alert,"close_email_alert"=>$close_email_alert,"send_fullthread"=>$send_fullthread);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_agent_alert($data);
}
elseif($action == "check_email"){
    $data = array("user_id"=>$user_id, "email"=>$email);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->check_email($data);
}
elseif($action == "check_to_email"){
    $data = array("to_email"=>$to_email);
	//$data = array("ticket_to"=>"vaithees20@gmail.com","ticket_cc"=>"","ticket_bcc"=>"","from"=>"omni@pipe.mconnectapps.com","message"=>"test","subject"=>"test","ticket_id"=>"12","message_id"=>"0");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->check_to_email($data);
}
elseif($action == "checking_email"){
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"user_type"=>$user_type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->checking_email($data);
}
elseif($action == "update_override"){
    $data= array("admin_id"=>$admin_id,"value"=>$value,"ticket_limit"=>$ticket_limit);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_override($data);
}
elseif($action == "delete_multiple_ticket"){
    $data= array("admin_id"=>$admin_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_multiple_ticket($data);
}
elseif($action == "delete_ticket"){
    $data= array("admin_id"=>$admin_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_ticket($data);
}
elseif($action == "restore_ticket"){
    $data= array("admin_id"=>$admin_id,"ticket_id"=>$ticket_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->restore_ticket($data);
}
elseif($action == "add_email_filter"){
	$data = array("user_id"=>$user_id, "keyword"=>$keyword,"admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_email_filter($data);
}
elseif($action == "list_email_filter"){
	$data = array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_email_filter($data);
}
elseif($action == "edit_email_filter"){	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_email_filter($key_id);
}
elseif($action == "update_email_filter"){
	$data= array("key_id"=>$key_id,"filter_word"=>$filter_word);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_email_filter($data);
}
elseif($action == "delete_email_filter"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_email_filter($key_id);
}
elseif($action == "update_share_ticket"){
	$data= array("user_id"=>$user_id,"share_ticket"=>$share_ticket);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_share_ticket($data);
}
elseif($action == "agent_picture_permission"){
	$data= array("user_id"=>$user_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->agent_picture_permission($data);
}
elseif($action == "updateEmailResponse_status"){
	 $data= array("admin_id"=>$admin_id,"response_for"=>$response_for,"status"=>$status,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateEmailResponse_status($data);
}
elseif($action == "get_unassign_tickets"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"ticket_department"=>$ticket_department,"is_spam"=>$is_spam);    
    $result_data["result"]["data"] = $ticket->get_unassign_tickets($data);
}
elseif($action == "get_deleted_tickets"){
    $data = array("user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit,"offset"=>$offset,"search_text"=>$search_text);    
    $result_data["result"]["data"] = $ticket->get_deleted_tickets($data);
}
elseif($action == "forward_ticket"){
    $data = array("ticket_message_id"=>$ticket_message_id,"user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id,"ticket_id"=>$ticket_id,"forward_to"=>$forward_to);    
    $result_data["result"]["data"] = $ticket->forward_ticket($data);
}
elseif($action == "filter_getmyExternalTicket"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"ticket_status"=>$ticket_status,"ticket_department"=>$ticket_department,"is_spam"=>$is_spam);    
    $result_data["result"]["data"] = $ticket->filter_getmyExternalTicket($data);
}
elseif($action == "spam_getmyExternalTicket"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"ticket_status"=>$ticket_status,"ticket_department"=>$ticket_department,"is_spam"=>$is_spam,"search_text"=>$search_text);    
    $result_data["result"]["data"] = $ticket->spam_getmyExternalTicket($data);
}
elseif($action == "ticket_dashboard"){
    $data = array("user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $ticket->ticket_dashboard($data);
}
elseif($action == "ticket_dashboard_dateFilter"){
    $data = array("user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id,"from_date"=>$from_date,"to_date"=>$to_date);
    $result_data["result"]["data"] = $ticket->ticket_dashboard_dateFilter($data);
}
elseif($action == "ticket_dashboard_customFilter"){
    $data = array("user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id,"custom_value"=>$custom_value);    
    $result_data["result"]["data"] = $ticket->ticket_dashboard_customFilter($data);
}
elseif($action == "ticket_shared_agent"){
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $ticket->ticket_shared_agent($data);
}
elseif($action == "check_rounrobin"){
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"ticket_id"=>$ticket_id);    
    $result_data["result"]["data"] = $ticket->check_rounrobin($data);
}
elseif($action == "wallboard_ticket_count"){
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $ticket->wallboard_ticket_count($data);
}
elseif($action == "add_priority_filter"){
	$data = array("user_id"=>$user_id, "priority"=>$priority, "keyword"=>$keyword, "admin_id"=>$admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_priority_filter($data);
}
elseif($action == "list_priority_filter"){
	$data = array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_priority_filter($data);
}
elseif($action == "edit_priority_filter"){	
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_priority_filter($key_id);
}
elseif($action == "update_priority_filter"){
	$data= array("key_id"=>$key_id,"key_word"=>$key_word);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_priority_filter($data);
}
elseif($action == "delete_priority_filter"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_priority_filter($key_id);
}
elseif($action == "getMyAliasEmails"){   
    $result_data["result"]["data"] = $ticket->getMyAliasEmails($admin_id);
}
elseif($action == "getPriorities"){   
    $result_data["result"]["data"] = $ticket->getPriorities();
}
elseif($action == "searchFunction"){
    $data = array("user_id"=>$user_id, "user_type"=>$user_type,"admin_id"=>$admin_id,"type"=>$type,"ticket_search"=>$ticket_search,"limit"=>$limit, "offset"=>$offset);  
    $result_data["result"]["data"] = $ticket->searchFunction($data);
}
elseif($action == "check_rounrobin_queue"){
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"dept_id"=>$dept_id);    
    $result_data["result"]["data"] = $ticket->check_rounrobin_queue($data);
}
elseif($action == "reassign_ticket_roundrobin"){
    $data = array("admin_id"=>$admin_id,"changing_user"=>$changing_user,"changing_user_dept"=>$changing_user_dept,"ticket_id"=>$ticket_id);    
    $result_data["result"]["data"] = $ticket->reassign_ticket_roundrobin($data);
}
elseif($action == "my_internalMail"){
    $data = array("user_id"=>$user_id, "admin_id"=>$admin_id, "agent_email" =>$agent_email, "limit"=>$limit, "offset"=>$offset, "is_spam"=>$is_spam);    
    $result_data["result"]["data"] = $ticket->getmyInternalMail($data);
}
elseif($action == "viewInternalMail"){
    $data= array("ticket_id"=>$ticket_id, "user_id"=>$user_id, "admin_id"=>$admin_id, "limit"=>$limit, "offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->viewInternalMail($data);
}
elseif($action == "add_InternalMail"){ 
    $data = array("from"=>$from, "to"=>$to,"subject"=>$subject,"message"=>$message,"attachments"=>$attachments,"ticket_reply_id"=>$ticket_reply_id,"to_mail"=>$to_mail,"cc_mail"=>$cc_mail,"forward_from"=>$forward_from,"forward_to"=>$forward_to,"forward_cc"=>$forward_cc);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_InternalMail($data);
}
elseif($_REQUEST['action'] == "composeInternalMail"){
    extract($_REQUEST);    
    $data= array("user_id"=>$user_id,"admin_id"=>$admin_id,"subject"=>$subject,"description"=>$description,"to"=>$to,"from_address"=>$from_address,"mail_cc"=>$mail_cc );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->composeInternalMail($data);
}
elseif($_REQUEST['action'] == 'replyInternalMail') {
    //echo 'ferfref';exit;
    $message = $_REQUEST['message'];
    $ticket_id = $_REQUEST['ticket_id'];
    $to = $_REQUEST['to'];
    $user_id = $_REQUEST['user_id'];
    $admin_id = $_REQUEST['admin_id'];
    $signature_id = $_REQUEST['signature_id'];
    $mail_cc = $_REQUEST['mail_cc'];
    $data= array("message"=>$message,"ticket_id"=>$ticket_id,"to"=>$to,"user_id"=>$user_id,"signature_id"=>$signature_id,"mail_cc"=>$mail_cc,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->replyInternalMail($data);
}
elseif($action == "searchInternalMail"){
    $data = array("agent_email"=>$agent_email,"user_id"=>$user_id,"user_type"=>$user_type,"admin_id"=>$admin_id,"is_spam"=>$is_spam,"ticket_search"=>$ticket_search,"limit"=>$limit, "offset"=>$offset);  
    $result_data["result"]["data"] = $ticket->searchInternalMail($data);
}
elseif($action == "addPhoneBridge"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id,"ip_address"=>$ip_address,"sip_url"=>$sip_url,"sip_port"=>$sip_port);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->addPhoneBridge($data);
}
elseif($action == "editPhoneBridge"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id,"key_id"=>$key_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->editPhoneBridge($data);
}
elseif($action == "updatePhoneBridge"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id,"ip_address"=>$ip_address,"sip_url"=>$sip_url,"sip_port"=>$sip_port,"key_id"=>$key_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updatePhoneBridge($data);
}
elseif($action == "listPhoneBridge"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id,"limit"=>$limit,"offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->listPhoneBridge($data);
}
elseif($action == "deletePhoneBridge"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->deletePhoneBridge($key_id,$admin_id);
}
elseif($action == "ticket_contract_details"){    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->ticket_contract_details($cust_id);
}
elseif($action == "getCustomerDetasils"){    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->getCustomerDetasils($admin_id);
}
elseif($action == "phone_bridge_users"){
    //$agents = array("agent_name"=>$agent_name,"agent_number"=>$agent_number);
    $data = array("hardware_id"=>$hardware_id,"bridge_host"=>$bridge_host,"agents"=>$agents);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->phone_bridge_users($data);
}
elseif($action == "view_phone_bridge_users"){
    $data= array("admin_id"=>$admin_id,"ip_address"=>$ip_address);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->view_phone_bridge_users($data);
}
elseif($action == "updateCustomer"){
    $data= array("ticket_id"=>$ticket_id,"admin_id"=>$admin_id,"customer_id"=>$customer_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->updateCustomer($data);
}
elseif($action == "get_department_signature"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->get_department_signature($admin_id);
}
elseif($action == "update_signature_strategy"){
    $data= array("user_id"=>$user_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_signature_strategy($data);
}
elseif($action == "update_switch_signature"){
    $data= array("user_id"=>$user_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_switch_signature($data);
}
elseif($action == "merge_ticket"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id,"main_ticket_id"=>$main_ticket_id,"sub_ticket_id"=>$sub_ticket_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->merge_ticket($data);
}
elseif($action == "view_bridge_users"){    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->view_bridge_users($admin_id);
}
elseif($action == "geterpCustomerDetasils"){    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->geterpCustomerDetasils($admin_id);
}
elseif($action == "editCustomer"){
    $data= array("admin_id"=>$admin_id,"customer_id"=>$customer_id);    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->editCustomer($data);
}
elseif($action == "changeCustomer"){    
    $data= array("from_mail"=>$from_mail,"admin_id"=>$admin_id,"customer_id"=>$customer_id,"customer_email"=>$customer_email,"customer_name"=>$customer_name,"customer_code"=>$customer_code,"customer_country"=>$customer_country,"customer_phone"=>$customer_phone);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->changeCustomer($data);
}
elseif($action == "update_thread_strategy"){
    $data= array("user_id"=>$user_id,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_thread_strategy($data);
}
elseif($action == "change_thread_order"){    
    $data= array("ticket_id"=>$ticket_id, "admin_id"=>$admin_id, "uid"=>$uid, "limit"=>$limit, "offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->change_thread_order($data);
}
elseif($action == "add_department_keyword"){
    $data = array("type"=>$type, "type_id"=>$type_id, "user_id"=>$user_id, "keyword"=>$keyword);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_department_keyword($data);
}
elseif($action == "list_department_keyword"){
    $data = array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_department_keyword($data);
}
elseif($action == "edit_department_keyword"){ 
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_department_keyword($key_id);
}
elseif($action == "update_department_keyword"){
    $data= array("key_id"=>$key_id, "type"=>$type, "type_id"=>$type_id, "filter_word"=>$filter_word);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_department_keyword($data);
}
elseif($action == "delete_department_keyword"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_department_keyword($key_id);
}
elseif($action == "internalmail_spamLists"){
    $data= array("admin_id"=>$admin_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;    
    $result_data["result"]["spamLists"] = $ticket->internalmail_spamLists($data);
}
elseif($action == "internalmail_blockEmailIds"){
    $data= array("email_id"=>$email_id,"admin_id"=>$admin_id,"spam_status"=>$spam_status,"blacklist_status"=>$blacklist_status,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->internalmail_blockEmailIds($data);
}
elseif($action == "internalmail_delSpamEmail"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->internalmail_delSpamEmail($email);
}
elseif($action == "share_external_ticket"){
    $data= array("ticket_id"=>$ticket_id, "admin_id"=>$admin_id, "agent_id"=>$agent_id, "user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->share_external_ticket($data);
}
elseif($action == "shared_agent_list"){
    $data= array("ticket_id"=>$ticket_id, "admin_id"=>$admin_id, "user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->shared_agent_list($data);
}
elseif($action == "update_spamstatus_settings"){
    $data = array("dept_id"=>$dept_id,"aliseEmail"=>$aliseEmail,"value"=>$value);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_spamstatus_settings($data);
}
elseif($action == "add_domain_whitelist"){
    $data = array("admin_id"=>$admin_id,"domain"=>$domain);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_domain_whitelist($data);
}
elseif($action == "list_domain_whitelist"){
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_domain_whitelist($data);
}
elseif($action == "edit_domain_whitelist"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_domain_whitelist($data);
}
elseif($action == "update_domain_whitelist"){
    $data = array("id"=>$id,"admin_id"=>$admin_id,"domain"=>$domain);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_domain_whitelist($data);
}
elseif($action == "delete_domain_whitelist"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_domain_whitelist($data);
}
elseif($action == "add_private_forwardmail"){
    $data = array("admin_id"=>$admin_id,"email"=>$email);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_private_forwardmail($data);
}
elseif($action == "list_private_forwardmail"){
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_private_forwardmail($data);
}
elseif($action == "edit_private_forwardmail"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_private_forwardmail($data);
}
elseif($action == "update_private_forwardmail"){
    $data = array("id"=>$id,"admin_id"=>$admin_id,"email"=>$email);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_private_forwardmail($data);
}
elseif($action == "delete_private_forwardmail"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_private_forwardmail($data);
}
elseif($action == "add_subject_filter"){
    $data = array("admin_id"=>$admin_id,"subject"=>$subject);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_subject_filter($data);
}
elseif($action == "list_subject_filter"){
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_subject_filter($data);
}
elseif($action == "edit_subject_filter"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_subject_filter($data);
}
elseif($action == "update_subject_filter"){
    $data = array("id"=>$id,"admin_id"=>$admin_id,"subject"=>$subject);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_subject_filter($data);
}
elseif($action == "delete_subject_filter"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_subject_filter($data);
}
elseif($action == "list_customer_whitelist"){
    $data = array("admin_id"=>$admin_id,"limit"=>$limit,"offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_customer_whitelist($data);
}
elseif($action == "add_customer_whitelist"){
    $data = array("admin_id"=>$admin_id,"email"=>$email);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_customer_whitelist($data);
}
elseif($action == "edit_customer_whitelist"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->edit_customer_whitelist($data);
}
elseif($action == "update_customer_whitelist"){
    $data = array("id"=>$id,"admin_id"=>$admin_id,"email"=>$email);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_customer_whitelist($data);
}
elseif($action == "delete_customer_whitelist"){
    $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->delete_customer_whitelist($data);
}
elseif($action == "get_hasemail_department"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->get_hasemail_department($admin_id);
}

elseif($action == "email_queue_report"){
    $data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"agent_id"=>$agent_id,"dept_id"=>$dept_id,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->email_queue_report($data);    
}
elseif($action == "email_queue_report_export"){
    $data= array("admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"agent_id"=>$agent_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->email_queue_report_export($data);
}
elseif($action == "add_custom_customer"){
    $data = array("admin_id"=>$admin_id,"customer_name"=>$customer_name,"customer_email"=>$customer_email,"phone_number"=>$phone_number,"country"=>$country);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->add_custom_customer($data);
}
elseif($action == "customer_search_function"){
    $data = array("admin_id"=>$admin_id,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->customer_search_function($data);
}
elseif($action == "change_from_function"){
    $data = array("admin_id"=>$admin_id,"cusmail"=>$cusmail,"ticket_no"=>$ticket_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->change_from_function($data);
}
elseif($action == "list_enquiry_tickets"){
    $data = array("admin_id"=>$admin_id,"search_text"=>$search_text,"limit"=>$limit,"offset"=>$offset);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_enquiry_tickets($data);
}
elseif($action == "list_enquiry_dropdown"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->list_enquiry_dropdown();
}
elseif($action == "enquiry_ticket_filter"){
    $data= array("admin_id"=>$admin_id,"limit"=>$limit, "offset"=>$offset,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"dept_id"=>$dept_id,"enquiry_dropdown_id"=>$enquiry_dropdown_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->enquiry_ticket_filter($data);    
}
elseif($action == "update_ticket_type"){
    $data = array("admin_id"=>$admin_id,"type"=>$type,"ticket_no"=>$ticket_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_ticket_type($data);
}
elseif($action == "update_enquiry_details"){
    $data = array("admin_id"=>$admin_id,"enquiry_company"=>$enquiry_company,"enquiry_country"=>$enquiry_country,"enquiry_comments"=>$enquiry_comments,"ticket_no"=>$ticket_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_enquiry_details($data);
}
elseif($action == "update_enquiry_comments"){
    $data = array("admin_id"=>$admin_id,"enquiry_outcome_comments"=>$enquiry_outcome_comments,"ticket_no"=>$ticket_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $ticket->update_enquiry_comments($data);
}
?>