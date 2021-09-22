<?php
$userData = new userData();
$result_data["status"] = true;
if($action == "login_validation"){
	$user_data = $userData->userLoginValidation($company_name, $user_name, $password);
	if($user_data != null && isset($user_data['user_id'])){
		extract($user_data);
		$result_data["result"]["status"] = true;
		$result_data["result"]["data"]["user_id"] = $user_id;				
		if($admin_id==1){
			$result_data["result"]["data"]["admin_id"] = $user_id;			
		}else{
			$result_data["result"]["data"]["admin_id"] = $admin_id;			
		}
		$a_id = $result_data["result"]["data"]["admin_id"];
		$result_data["result"]["data"]["encAdmin"] = $userData->getEncriptedLogin($a_id);	
		$result_data["result"]["data"]["encUser"] = $userData->getEncriptedLogin($user_id);	
		$result_data["result"]["data"]["user_name"] = $user_name;
		$result_data["result"]["data"]["user_type"] = $user_type;
		$result_data["result"]["data"]["userType"] = $userType;
		$result_data["result"]["data"]["email_id"] = $email_id;
		$result_data["result"]["data"]["phone_number"] = $phone_number;
		$result_data["result"]["data"]["incoming_call_status"] = $incoming_call_status;
		$result_data["result"]["data"]["sip_username"] = $sip_username;
		$result_data["result"]["data"]["sip_password"] = $sip_password;
		$result_data["result"]["data"]["sip_tocken"] = $sip_tocken;
		$result_data["result"]["data"]["timezone_id"] = $timezone_id;
		$result_data["result"]["data"]["profile_image"] = $profile_image;
		$result_data["result"]["data"]["logo_image"] = $logo_image;
		$result_data["result"]["data"]["small_logo_image"] = $small_logo_image;
		$result_data["result"]["data"]["ext_int_status"] = $ext_int_status;
		$result_data["result"]["data"]["layout"] = $layout;
		$result_data["result"]["data"]["theme"] = $theme;
		$result_data["result"]["data"]["lead_token"] = $lead_token;
		$result_data["result"]["data"]["wallboard_one_isactive"] = $wallboard_one_isactive;
		$result_data["result"]["data"]["wallboard_two_isactive"] = $wallboard_two_isactive;
		$result_data["result"]["data"]["wallboard_three_isactive"] = $wallboard_three_isactive;
		$result_data["result"]["data"]["wallboard_four_isactive"] = $wallboard_four_isactive;		
		$result_data["result"]["data"]["two_factor"] = $two_factor;
		$result_data["result"]["data"]["company_name"] = $company_name;
		$result_data["result"]["data"]["domain_name"] = $domain_name;
		$result_data["result"]["data"]["dsk_access"] = $dsk_access;
		$result_data["result"]["data"]["dsk_user_name"] = $dsk_user_name;
		$result_data["result"]["data"]["dsk_user_pwd"] = $dsk_user_pwd;
		$result_data["result"]["data"]["has_wallboard"] = $has_wallboard;
		$result_data["result"]["data"]["hardware_id"] = $hardware_id;
		$result_data["result"]["data"]["has_fax"] = $has_fax;
		$result_data["result"]["data"]["has_external_contact"] = $has_external_contact;
		$result_data["result"]["data"]["reports"] = $reports;
		$result_data["result"]["data"]["close_all_menu"] = $close_all_menu;
		$result_data["result"]["data"]["login_status"] = $login_status;
		$result_data["result"]["data"]["external_contact_url"] = $external_contact_url;
		$result_data["result"]["data"]["has_internal_chat"] = $has_internal_chat;
		$result_data["result"]["data"]["show_caller_id"] = $show_caller_id;
		$result_data["result"]["data"]["show_caller_id"] = $user_caller_id;
		$result_data["result"]["data"]["predective_dialer_behave"] = $predective_dialer_behave;
		$result_data["result"]["data"]["whatsapp_account"] = $whatsapp_account;
		$result_data["result"]["data"]["facebook_account"] = $facebook_account;
		$result_data["result"]["data"]["crm_type"] = $crm_type;
		$result_data["result"]["data"]["price_sms"] = $price_sms;
		$result_data["result"]["data"]["reseller"] = $reseller;
		$result_data["result"]["data"]["mr_voip"] = $mr_voip;
		$result_data["result"]["data"]["session_login"] = time() + 7200;
		$result_data["access_token"] = $admin->tokenGenerate(array("token_accessId"=>$user_id,"token_accessName"=>$user_name,"token_accessType"=>$user_type), $_SERVER["HTTP_HOST"]);		
	}	
	else{		
		$result_data["result"]["status"] = false;
		$result_data["result"]["data"] = array();
	}
}
if($action == "send_otp"){
	$user_data = $userData->send_otp($company_name, $user_name, $password, $method);
	if($user_data != null && isset($user_data['user_id'])){
		extract($user_data);
		$result_data["result"]["status"] = true;
		$result_data["result"]["data"]["user_id"] = $user_id;				
		if($admin_id==1){
			$result_data["result"]["data"]["admin_id"] = $user_id;			
		}else{
			$result_data["result"]["data"]["admin_id"] = $admin_id;			
		}
		$result_data["result"]["data"]["user_name"] = $user_name;
		$result_data["result"]["data"]["user_type"] = $user_type;
		$result_data["result"]["data"]["userType"] = $userType;
		$result_data["result"]["data"]["email_id"] = $email_id;
		$result_data["result"]["data"]["phone_number"] = $phone_number;
		$result_data["result"]["data"]["incoming_call_status"] = $incoming_call_status;
		$result_data["result"]["data"]["sip_username"] = $sip_username;
		$result_data["result"]["data"]["sip_password"] = $sip_password;
		$result_data["result"]["data"]["sip_tocken"] = $sip_tocken;
		$result_data["result"]["data"]["timezone_id"] = $timezone_id;
		$result_data["result"]["data"]["profile_image"] = $profile_image;
		$result_data["result"]["data"]["logo_image"] = $logo_image;
		$result_data["result"]["data"]["small_logo_image"] = $small_logo_image;
		$result_data["result"]["data"]["ext_int_status"] = $ext_int_status;
		$result_data["result"]["data"]["layout"] = $layout;
		$result_data["result"]["data"]["theme"] = $theme;
		$result_data["result"]["data"]["lead_token"] = $lead_token;
		$result_data["result"]["data"]["wallboard_one_isactive"] = $wallboard_one_isactive;
		$result_data["result"]["data"]["wallboard_two_isactive"] = $wallboard_two_isactive;
		$result_data["result"]["data"]["wallboard_three_isactive"] = $wallboard_three_isactive;
		$result_data["result"]["data"]["wallboard_four_isactive"] = $wallboard_four_isactive;
		$result_data["result"]["data"]["two_factor"] = $two_factor;
		$result_data["result"]["data"]["company_name"] = $company_name;
		$result_data["result"]["data"]["domain_name"] = $domain_name;
		$result_data["result"]["data"]["dsk_access"] = $dsk_access;
		$result_data["result"]["data"]["dsk_user_name"] = $dsk_user_name;
		$result_data["result"]["data"]["dsk_user_pwd"] = $dsk_user_pwd;
		$result_data["result"]["data"]["has_fax"] = $has_fax;
		$result_data["result"]["data"]["has_external_contact"] = $has_external_contact;
		$result_data["result"]["data"]["reports"] = $reports;
		$result_data["result"]["data"]["close_all_menu"] = $close_all_menu;
		$result_data["result"]["data"]["login_status"] = $login_status;
		$result_data["result"]["data"]["external_contact_url"] = $external_contact_url;
		$result_data["result"]["data"]["has_internal_chat"] = $has_internal_chat;
		$result_data["result"]["data"]["show_caller_id"] = $show_caller_id;
		$result_data["result"]["data"]["predective_dialer_behave"] = $predective_dialer_behave;
		$result_data["result"]["data"]["whatsapp_account"] = $whatsapp_account;
		$result_data["result"]["data"]["facebook_account"] = $facebook_account;
		$result_data["result"]["data"]["crm_type"] = $crm_type;
		$result_data["result"]["data"]["price_sms"] = $price_sms;
		$result_data["result"]["data"]["reseller"] = $reseller;
		$result_data["result"]["data"]["mr_voip"] = $mr_voip;
		$result_data["result"]["data"]["session_login"] = time() + 7200;
		$result_data["access_token"] = $admin->tokenGenerate(array("token_accessId"=>$user_id,"token_accessName"=>$user_name,"token_accessType"=>$user_type), $_SERVER["HTTP_HOST"]);	
	}	
	else{		
		$result_data["result"]["status"] = false;
		$result_data["result"]["data"] = array();
	}
}
if($action == "check_otp"){
	$user_data = $userData->check_otp($otp);
	if($user_data != null && isset($user_data['user_id'])){
		extract($user_data);
		$result_data["result"]["status"] = true;
		$result_data["result"]["data"]["user_id"] = $user_id;				
		if($admin_id==1){
			$result_data["result"]["data"]["admin_id"] = $user_id;			
		}else{
			$result_data["result"]["data"]["admin_id"] = $admin_id;			
		}
		$result_data["result"]["data"]["user_name"] = $user_name;
		$result_data["result"]["data"]["user_type"] = $user_type;
		$result_data["result"]["data"]["userType"] = $userType;
		$result_data["result"]["data"]["email_id"] = $email_id;
		$result_data["result"]["data"]["phone_number"] = $phone_number;
		$result_data["result"]["data"]["incoming_call_status"] = $incoming_call_status;
		$result_data["result"]["data"]["sip_username"] = $sip_username;
		$result_data["result"]["data"]["sip_password"] = $sip_password;
		$result_data["result"]["data"]["sip_tocken"] = $sip_tocken;
		$result_data["result"]["data"]["timezone_id"] = $timezone_id;
		$result_data["result"]["data"]["profile_image"] = $profile_image;
		$result_data["result"]["data"]["logo_image"] = $logo_image;
		$result_data["result"]["data"]["small_logo_image"] = $small_logo_image;
		$result_data["result"]["data"]["ext_int_status"] = $ext_int_status;
		$result_data["result"]["data"]["layout"] = $layout;
		$result_data["result"]["data"]["theme"] = $theme;
		$result_data["result"]["data"]["lead_token"] = $lead_token;
		$result_data["result"]["data"]["wallboard_one_isactive"] = $wallboard_one_isactive;
		$result_data["result"]["data"]["wallboard_two_isactive"] = $wallboard_two_isactive;
		$result_data["result"]["data"]["wallboard_three_isactive"] = $wallboard_three_isactive;
		$result_data["result"]["data"]["wallboard_four_isactive"] = $wallboard_four_isactive;
		$result_data["result"]["data"]["two_factor"] = $two_factor;
		$result_data["result"]["data"]["company_name"] = $company_name;
		$result_data["result"]["data"]["domain_name"] = $domain_name;
		$result_data["result"]["data"]["dsk_access"] = $dsk_access;
		$result_data["result"]["data"]["dsk_user_name"] = $dsk_user_name;
		$result_data["result"]["data"]["dsk_user_pwd"] = $dsk_user_pwd;
		$result_data["result"]["data"]["has_fax"] = $has_fax;
		$result_data["result"]["data"]["has_external_contact"] = $has_external_contact;
		$result_data["result"]["data"]["reports"] = $reports;
		$result_data["result"]["data"]["close_all_menu"] = $close_all_menu;
		$result_data["result"]["data"]["login_status"] = $login_status;
		$result_data["result"]["data"]["external_contact_url"] = $external_contact_url;
		$result_data["result"]["data"]["has_internal_chat"] = $has_internal_chat;
		$result_data["result"]["data"]["show_caller_id"] = $show_caller_id;
		$result_data["result"]["data"]["predective_dialer_behave"] = $predective_dialer_behave;
		$result_data["result"]["data"]["whatsapp_account"] = $whatsapp_account;
		$result_data["result"]["data"]["facebook_account"] = $facebook_account;
		$result_data["result"]["data"]["crm_type"] = $crm_type;
		$result_data["result"]["data"]["price_sms"] = $price_sms;
		$result_data["result"]["data"]["reseller"] = $reseller;
		$result_data["result"]["data"]["mr_voip"] = $mr_voip;
		$result_data["result"]["data"]["session_login"] = time() + 7200;
		$result_data["access_token"] = $admin->tokenGenerate(array("token_accessId"=>$user_id,"token_accessName"=>$user_name,"token_accessType"=>$user_type), $_SERVER["HTTP_HOST"]);
	}	
	else{		
		$result_data["result"]["status"] = false;
		$result_data["result"]["data"] = array();
	}
}
// login from erp code starts here
if($action == "login_from_erp"){
    $extension = base64_decode($extension);   
	$user_data = $userData->login_from_erp($extension);
	if($user_data != null && isset($user_data['user_id'])){
		extract($user_data);
		$result_data["result"]["status"] = true;
		$result_data["result"]["data"]["user_id"] = $user_id;			
		if($admin_id==1){
			$result_data["result"]["data"]["admin_id"] = $user_id;		
		}else{
			$result_data["result"]["data"]["admin_id"] = $admin_id;		
		}
		$a_id = $result_data["result"]["data"]["admin_id"];
		$result_data["result"]["data"]["encAdmin"] = $userData->getEncriptedLogin($a_id);	
		$result_data["result"]["data"]["encUser"] = $userData->getEncriptedLogin($user_id);	
		$result_data["result"]["data"]["user_name"] = $user_name;
		$result_data["result"]["data"]["user_type"] = $user_type;
		$result_data["result"]["data"]["userType"] = $userType;
		$result_data["result"]["data"]["email_id"] = $email_id;
		$result_data["result"]["data"]["phone_number"] = $phone_number;
		$result_data["result"]["data"]["incoming_call_status"] = $incoming_call_status;
		$result_data["result"]["data"]["sip_username"] = $sip_username;
		$result_data["result"]["data"]["sip_password"] = $sip_password;
		$result_data["result"]["data"]["sip_tocken"] = $sip_tocken;
		$result_data["result"]["data"]["timezone_id"] = $timezone_id;
		$result_data["result"]["data"]["profile_image"] = $profile_image;
		$result_data["result"]["data"]["logo_image"] = $logo_image;
		$result_data["result"]["data"]["small_logo_image"] = $small_logo_image;
		$result_data["result"]["data"]["ext_int_status"] = $ext_int_status;
		$result_data["result"]["data"]["layout"] = $layout;
		$result_data["result"]["data"]["theme"] = $theme;
		$result_data["result"]["data"]["lead_token"] = $lead_token;
		$result_data["result"]["data"]["wallboard_one_isactive"] = $wallboard_one_isactive;
		$result_data["result"]["data"]["wallboard_two_isactive"] = $wallboard_two_isactive;
		$result_data["result"]["data"]["wallboard_three_isactive"] = $wallboard_three_isactive;
		$result_data["result"]["data"]["wallboard_four_isactive"] = $wallboard_four_isactive;		
		$result_data["result"]["data"]["two_factor"] = $two_factor;
		$result_data["result"]["data"]["company_name"] = $company_name;
		$result_data["result"]["data"]["domain_name"] = $domain_name;
		$result_data["result"]["data"]["dsk_access"] = $dsk_access;
		$result_data["result"]["data"]["dsk_user_name"] = $dsk_user_name;
		$result_data["result"]["data"]["dsk_user_pwd"] = $dsk_user_pwd;
		$result_data["result"]["data"]["has_wallboard"] = $has_wallboard;
		$result_data["result"]["data"]["hardware_id"] = $hardware_id;
		$result_data["result"]["data"]["has_fax"] = $has_fax;
		$result_data["result"]["data"]["has_external_contact"] = $has_external_contact;
		$result_data["result"]["data"]["reports"] = $reports;
		$result_data["result"]["data"]["close_all_menu"] = $close_all_menu;
		$result_data["result"]["data"]["login_status"] = $login_status;
		$result_data["result"]["data"]["external_contact_url"] = $external_contact_url;
		$result_data["result"]["data"]["has_internal_chat"] = $has_internal_chat;
		$result_data["result"]["data"]["show_caller_id"] = $show_caller_id;
		$result_data["result"]["data"]["show_caller_id"] = $user_caller_id;
		$result_data["result"]["data"]["predective_dialer_behave"] = $predective_dialer_behave;
		$result_data["result"]["data"]["whatsapp_account"] = $whatsapp_account;
		$result_data["result"]["data"]["facebook_account"] = $facebook_account;
		$result_data["result"]["data"]["crm_type"] = $crm_type;
		$result_data["result"]["data"]["price_sms"] = $price_sms;
		$result_data["result"]["data"]["reseller"] = $reseller;
		$result_data["result"]["data"]["mr_voip"] = $mr_voip;
		$result_data["result"]["data"]["session_login"] = time() + 7200;
		$result_data["access_token"] = $admin->tokenGenerate(array("token_accessId"=>$user_id,"token_accessName"=>$user_name,"token_accessType"=>$user_type), $_SERVER["HTTP_HOST"]);		
	}	
	else{		
		$result_data["result"]["status"] = false;
		$result_data["result"]["data"] = array();
	}    
}
// login from erp code ends here

?>