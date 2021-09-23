
<?php
require_once 'rest.controller.php';
$access_page = "error.php";
//echo 'asasas';exit;
if($api_request_data["element_data"] > 0){
extract($api_request_data["element_data"]);
}



//print_r($_REQUEST['element_data']['action']); exit;
//print_r($api_request_data["element_data"]);exit;
//echo $_REQUEST['action'];exit;

if($api_type == "web"){
	
     switch($moduleType) {
          
		case 'login':
			include_once CONTROLLER_PATH.'class.user.php';
			include_once CONTROLLER_PATH.'class.authentication.php';
            $access_page = APPLICATION_PATH."login.php";
		break;
			 
        case 'customers':   
            include_once CONTROLLER_PATH.'class.customer.php';
            $access_page = APPLICATION_PATH."customer.php";
             
		break;
		
		case 'contact':   
	 		 
            include_once CONTROLLER_PATH.'class.contact.php';
            $access_page = APPLICATION_PATH."contact.php";
             
		break;
		case 'zohocrm':

			include_once CONTROLLER_PATH.'class.zohocrm.php';
			$access_page = APPLICATION_PATH."zohocrm.php"; 
			
	   break;
		case 'agents':  
			
			 
            include_once CONTROLLER_PATH.'class.user.php';
			
            $access_page = APPLICATION_PATH."user.php";
             
		break;
        case 'chat':
        	include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
             
		break;
		case 'email':
			include_once CONTROLLER_PATH.'class.customer.php';
			 include_once CONTROLLER_PATH.'class.email.php';
        	include_once CONTROLLER_PATH.'class.user.php';
			 include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."email.php";
             
		break;
		case 'call':
			include_once CONTROLLER_PATH.'class.call.php';
			include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."call.php";
             
		break;
		case 'queue':
			
			include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."queue.php";
		break;
		case 'wpchat':
			include_once CONTROLLER_PATH.'class.chatwp.php';
			 //print_r($api_request_data["element_data"]);exit;
            $access_page = APPLICATION_PATH."chat_wp.php";
		break;	 
		case 'ticket':
			
			include_once CONTROLLER_PATH.'class.ticket.php';
            $access_page = APPLICATION_PATH."ticket.php";
		break;
			 
		case 'questionaire': 
			include_once CONTROLLER_PATH.'class.questionaire.php';
            $access_page = APPLICATION_PATH."questionaire.php";
		break;
			 
		case 'auxcode':
			include_once CONTROLLER_PATH.'class.auxcode.php';
            $access_page = APPLICATION_PATH."auxcode.php";
		break;
		case 'campaign':
             include_once CONTROLLER_PATH.'class.campaign.php';
             $access_page = APPLICATION_PATH."campaign.php";
             break;
			 
		case 'predective_dialer_contact':
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
		break;
		case 'lead':			
        	include_once CONTROLLER_PATH.'class.lead.php'; 
            $access_page = APPLICATION_PATH."lead.php";
		break;
		case 'report':
            include_once CONTROLLER_PATH.'class.report.php';
            $access_page = APPLICATION_PATH."report.php";
        break;
		case 'daily_foods':
             include_once CONTROLLER_PATH.'class.daily_foods.php';
             $access_page = APPLICATION_PATH."daily_foods.php";
        break;
		case 'template':
			include_once CONTROLLER_PATH.'class.template.php';
            $access_page = APPLICATION_PATH."template.php";
		break;
		case 'agnt_aux':
            include_once CONTROLLER_PATH.'class.agnt_aux.php';
            $access_page = APPLICATION_PATH."agnt_aux.php";
        break;
		case 'survey':
            include_once CONTROLLER_PATH.'class.survey.php';
            $access_page = APPLICATION_PATH."survey.php";
        break;
		case 'fax':			 
            include_once CONTROLLER_PATH.'class.fax.php';			 
            $access_page = APPLICATION_PATH."fax.php";
			 //print_r($api_request_data["element_data"]);exit;
        break;
		case 'chatinternal':
			include_once CONTROLLER_PATH.'class.chatinternal.php';
            $access_page = APPLICATION_PATH."chat_internal.php";
		break;		 
		case 'chat_widget':			 
            include_once CONTROLLER_PATH.'class.chat_widget.php';			 
            $access_page = APPLICATION_PATH."chat_widget.php";
        break;	 
			 		 
		case 'cordlife':
             include_once CONTROLLER_PATH.'class.cordlife.php';
             $access_page = APPLICATION_PATH."cordlife.php";
             break;
		case 'chat_line':
             include_once CONTROLLER_PATH.'class.chat_line.php';
             $access_page = APPLICATION_PATH."chat_line.php";
             break;
		case 'chat_telegram':
             include_once CONTROLLER_PATH.'class.chat_telegram.php';
			 //print_r($api_request_data["element_data"]);exit;
             $access_page = APPLICATION_PATH."chat_telegram.php";
             break;	 
		case 'pre_camp':
             include_once CONTROLLER_PATH.'class.pre_camp.php';
             $access_page = APPLICATION_PATH."pre_camp.php";
             break;
		case 'social_media':
             include_once CONTROLLER_PATH.'class.social_media.php';
             $access_page = APPLICATION_PATH."social_media.php";
             break;	 
		case 'plans':
             include_once CONTROLLER_PATH.'class.plans.php';
             $access_page = APPLICATION_PATH."plans.php";
             break;	 
		case 'wp_pay':
             include_once CONTROLLER_PATH.'class.wp_pay.php';
             $access_page = APPLICATION_PATH."wp_pay.php";
             break;
		case 'wp_instance':
			include_once CONTROLLER_PATH.'class.wp_instance.php';
            $access_page = APPLICATION_PATH."wp_instance.php";
			break;
		case 'bd_report':
			include_once CONTROLLER_PATH.'class.broadcast_report.php';
            $access_page = APPLICATION_PATH."broadcast_report.php";
			break;
		case 'sms':
			include_once CONTROLLER_PATH.'class.sms.php';
            $access_page = APPLICATION_PATH."sms.php";
			break;
		case 'billing':
			include_once CONTROLLER_PATH.'class.billing.php';
            $access_page = APPLICATION_PATH."billing.php";
			break;
		case 'signup':
			include_once CONTROLLER_PATH.'class.signup.php';
            $access_page = APPLICATION_PATH."signup.php";
			break;
		case 'inbound_report':
			include_once CONTROLLER_PATH.'class.inbound_report.php';
            $access_page = APPLICATION_PATH."inbound_report.php";
			break;
		case 'webinar_meeting':			
        	include_once CONTROLLER_PATH.'class.webinar_meeting.php'; 
            $access_page = APPLICATION_PATH."webinar_meeting.php";
		break;
		case 'webinar_meeting_new':			
        	include_once CONTROLLER_PATH.'class.webinar_meeting_new.php'; 
            $access_page = APPLICATION_PATH."webinar_meeting_new.php";
		break;
		case 'webinar_configuration':				 
        	include_once CONTROLLER_PATH.'class.webinar_configuration.php'; 
            $access_page = APPLICATION_PATH."webinar_configuration.php";
		break;	
		case 'sg':
            include_once CONTROLLER_PATH.'class.sg.php';
            $access_page = APPLICATION_PATH."sg.php";
        break; 
		case 'nkh':
            include_once CONTROLLER_PATH.'class.nkh.php';
            $access_page = APPLICATION_PATH."nkh.php";
        break; 
		case 'call_tarrif':
            include_once CONTROLLER_PATH.'class.call_tarrif.php';
            $access_page = APPLICATION_PATH."call_tarrif.php";
        break;
		case 'email_blasting':
            include_once CONTROLLER_PATH.'class.email_blasting.php';
            $access_page = APPLICATION_PATH."email_blasting.php";
        break;
		 case 'netwrix':   
            include_once CONTROLLER_PATH.'class.netwrix.php';
            $access_page = APPLICATION_PATH."netwrix.php";
             
		break;
			 
		default:
			$access_page = "error.php";
		break;

	}

}
elseif($api_type == "mgt_console" || $api_type == "web_chat"){

	switch($moduleType) {
        case 'mgt_console':
        	include_once CONTROLLER_PATH.'class.3cx.php'; 
            $access_page = APPLICATION_PATH."service.3cx.php";
		break;
        case 'web_chat':			
        	include_once CONTROLLER_PATH.'class.web.chat.php';
			//print_r($api_request_data["element_data"]);exit;
            $access_page = APPLICATION_PATH."web_chat.php";
		break;
			
		default:
			$access_page = "error.php";
		
		break;

	}

} elseif($_REQUEST['action'] == 'csv_upload') {

			include_once CONTROLLER_PATH.'class.contact.php';
            $access_page = APPLICATION_PATH."contact.php";
}
elseif($_REQUEST['action'] == 'group_csv_upload') {
			include_once CONTROLLER_PATH.'class.contact.php';
            $access_page = APPLICATION_PATH."contact.php";
} elseif($_REQUEST['action'] == 'pre_csv_upload') {

			include_once CONTROLLER_PATH.'class.contact.php';
            $access_page = APPLICATION_PATH."contact.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_sms') {
        	include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_message_from_outer') {
        	include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_message_from_imap') {
        	include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
}
elseif($_REQUEST['action'] == 'insert_camp') {
  			include_once CONTROLLER_PATH.'class.campaign.php';
            $access_page = APPLICATION_PATH."campaign.php";
}

elseif($_REQUEST['action'] == 'update_camp') {
  			include_once CONTROLLER_PATH.'class.campaign.php';
            $access_page = APPLICATION_PATH."campaign.php";
}
 
elseif($_REQUEST['action'] == 'image_upload') {
  			include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."user.php";
}
elseif($_REQUEST['action'] == 'chat_image_upload') {
  			include_once CONTROLLER_PATH.'class.chat_widget.php';
            $access_page = APPLICATION_PATH."chat_widget.php";
}
elseif($_REQUEST['action'] == 'mrvoip_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}
elseif($_REQUEST['action'] == 'agent_rating_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}
elseif($_REQUEST['action'] == 'predective_dialer_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}

elseif($_REQUEST['action'] == 'proactive_dialer_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}

elseif($_REQUEST['action'] == 'broadcast_dialler_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}

elseif($_REQUEST['action'] == 'broadcast_survey_dialler_upload') {
			include_once CONTROLLER_PATH.'class.predective_dialer_contact.php';
            $access_page = APPLICATION_PATH."predective_dialer_contact.php";
}
elseif($_REQUEST['action'] == 'fax_upload') {
			include_once CONTROLLER_PATH.'class.fax.php';
            $access_page = APPLICATION_PATH."fax.php";
}
elseif($_REQUEST['action'] == 'whatsapp_media_upload') {
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
elseif($_REQUEST['action'] == 'single_whatsapp_media_upload_uoff') {

		include_once CONTROLLER_PATH.'class.wp_instance.php';
            $access_page = APPLICATION_PATH."wp_instance.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_outgoing_wp_unoff') {
		include_once CONTROLLER_PATH.'class.wp_instance.php';
            $access_page = APPLICATION_PATH."wp_instance.php";
}
elseif($_REQUEST['action'] == 'send_chat_message_unoff') {
			include_once CONTROLLER_PATH.'class.wp_instance.php';
            $access_page = APPLICATION_PATH."wp_instance.php";
	
}
elseif($_REQUEST['action'] == 'edit_fax_upload') {
			include_once CONTROLLER_PATH.'class.fax.php';
            $access_page = APPLICATION_PATH."fax.php";
}
elseif($_REQUEST['action'] == 'fb_reply_media_upload') {	
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
elseif($_REQUEST['action'] == 'bulk_whatsapp_media_upload') {
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
elseif($_REQUEST['action'] == 'single_whatsapp_media_upload') {
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
elseif($_REQUEST['action'] == 'admin_global_settings') {
  			include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."user.php";
}
elseif($_REQUEST['action'] == 'add_admin_biller') {
  			include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."user.php";
}
elseif($_REQUEST['action'] == 'call_rec') {
			include_once CONTROLLER_PATH.'class.contact.php';
            $access_page = APPLICATION_PATH."contact.php";
}
elseif($_REQUEST['action'] == 'webinar_meeting') {
	//print_r($_REQUEST);exit;
  			include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."user.php";
}
elseif($_REQUEST['action'] == 'update_webinar_meeting') {
	//print_r($_REQUEST);exit;
  			include_once CONTROLLER_PATH.'class.user.php';
            $access_page = APPLICATION_PATH."user.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_wp' || $_REQUEST['element_data']['action'] == 'generate_incoming_fb' || $moduleType == 'wpchat' || $_REQUEST['element_data']['action'] == 'change_wp_status') {
	
        	include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}

elseif($_REQUEST['element_data']['action'] == 'generate_incoming_line') {
	
        	include_once CONTROLLER_PATH.'class.chat_line.php';
            $access_page = APPLICATION_PATH."chat_line.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_message') {
	//print_r($_REQUEST);exit;
        	include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
}
elseif($_REQUEST['element_data']['action'] == 'generate_incoming_telegram') {
	
        	include_once CONTROLLER_PATH.'class.chat_telegram.php';
            $access_page = APPLICATION_PATH."chat_telegram.php";
}
elseif($_REQUEST['action'] == 'single_whatsapp_media_upload') {
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
//elseif( $_REQUEST['element_data']['action'] == 'generate_incoming_wp_unoff'|| $_REQUEST['element_data']['action'] == 'change_wp_status_unoff' || $_REQUEST['element_data']['action'] == 'send_chat_message_unoffs' || $_REQUEST['element_data']['action'] == 'send_chat_message_media_unoff') {
	
        	//include_once CONTROLLER_PATH.'class.chatwp.php';
            //$access_page = APPLICATION_PATH."chat_wp.php";
//}

elseif($_REQUEST['element_data']['action'] == 'generate_incoming_wp_unoff' || $_REQUEST['element_data']['action'] == 'change_wp_status' || $_REQUEST['element_data']['action'] == 'change_wp_status_unoff' || $_REQUEST['element_data']['action'] == 'sendWhatsappText' || $_REQUEST['element_data']['action'] == 'send_chat_message_media_unoff' || $_REQUEST['element_data']['action'] == 'generate_incoming_group_wp_unoff' ||$_REQUEST['element_data']['action'] == 'generate_incoming_group_image_wp_unoff' ||$_REQUEST['element_data']['action'] == 'generate_incoming_image_wp_unoff' ||$_REQUEST['element_data']['action'] == 'setWebHook' ) {
	//echo 'dfsddfsds'; exit;
        	include_once CONTROLLER_PATH.'class.wp_instance.php';
            $access_page = APPLICATION_PATH."wp_instance.php";
}
elseif($_REQUEST['action'] == 'send_chat_message_unoff') {
			include_once CONTROLLER_PATH.'class.chatwp.php';
            $access_page = APPLICATION_PATH."chat_wp.php";
}
elseif($_REQUEST['action'] == 'replyTicketMessage') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'createExternalTicket') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'createTicketSignature') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'updateTicketSignature') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'composeInternalMail') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'replyInternalMail') {
        include_once CONTROLLER_PATH.'class.ticket.php';
        $access_page = APPLICATION_PATH."ticket.php";
}
elseif($_REQUEST['action'] == 'send_chat_message') {

        include_once CONTROLLER_PATH.'class.customer.php';
        	include_once CONTROLLER_PATH.'class.chat.php';
        	include_once CONTROLLER_PATH.'class.user.php';
        	include_once CONTROLLER_PATH.'class.queue.php';
            $access_page = APPLICATION_PATH."chat.php";
}

elseif($_REQUEST['action'] == 'update_chat_message') {
       include_once CONTROLLER_PATH.'class.web.chat.php';
            $access_page = APPLICATION_PATH."web_chat.php";
}

elseif($_REQUEST['element_data']['rootfrom'] == 'mobile_apps') {
        include_once CONTROLLER_PATH.'class.call.php';
        $access_page = APPLICATION_PATH."mobile_app.php";
}

elseif($_REQUEST['operation'] == 'zohocrm') {			 
	include_once CONTROLLER_PATH.'class.zohocrm.php';
	$access_page = APPLICATION_PATH."zohocrm.php"; 
}

require_once($access_page);	

?>