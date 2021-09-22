<?php
$result_data["status"] = true;
$user = new userData(); 
if($_REQUEST['action'] == 'image_upload') { 	
	$action ='image_upload'; 
	$img_user_id = $_REQUEST['user_id'];		
	$json_element_data = json_decode($_REQUEST['element_data'],true);	
}
if($_REQUEST['action'] == 'admin_global_settings') {     
    $action ='admin_global_settings'; 
    $img_user_id = $_REQUEST['user_id'];
	$timezone_id = $_REQUEST['timezone_id'];
	$ext_int_status = $_REQUEST['ext_int_status'];
	$show_caller_id = $_REQUEST['show_caller_id'];
	$has_video_call =  $_REQUEST['has_video_call'];
	$dialer_ring = $_REQUEST['dialer_ring'];
	//$logo_image = $_REQUEST['logo_image'];
	//$small_logo_image = $_REQUEST['small_logo_image'];	
    //$json_element_data = json_decode($_REQUEST['element_data'],true);	
}
if($_REQUEST['action'] == 'add_admin_biller') {     
    $action ='add_admin_biller'; 
    $admin_id = $_REQUEST['admin_id'];
	$user_id = $_REQUEST['user_id'];
	$add1 = $_REQUEST['add1'];
	$add2 = $_REQUEST['add2'];
	$city =  $_REQUEST['city'];
	$state =  $_REQUEST['state'];
	$zip_code =  $_REQUEST['zip_code'];
	$country =  $_REQUEST['country'];
	$phone =  $_REQUEST['phone'];
	$toll_free =  $_REQUEST['toll_free'];
	$reg_no =  $_REQUEST['reg_no'];
	$email =  $_REQUEST['email'];
	$account_no =  $_REQUEST['account_no'];
	$bank =  $_REQUEST['bank'];
	$branch =  $_REQUEST['branch'];
	$logo_image = $_REQUEST['logo_image'];
	//$small_logo_image = $_REQUEST['small_logo_image'];	
    //$json_element_data = json_decode($_REQUEST['element_data'],true);	
}
if($_REQUEST['action'] == 'webinar_meeting') {     
    $action ='webinar_meeting'; 
    $img_user_id = $_REQUEST['user_id'];
    $timezone_id = $_REQUEST['timezone_id'];
    $title = $_REQUEST['title'];
    $content = $_REQUEST['content'];
    $meeting_date = $_REQUEST['meeting_date'];
	$minutes = $_REQUEST['minutes'];
    //$small_logo_image = $_REQUEST['small_logo_image'];    
    //$json_element_data = json_decode($_REQUEST['element_data'],true); 
}
if($_REQUEST['action'] == 'update_webinar_meeting') {     
    $action ='update_webinar_meeting'; 
    $id = $_REQUEST['id'];
	$admin_id = $_REQUEST['admin_id'];
    $timezone_id = $_REQUEST['timezone_id'];
    $title = $_REQUEST['title'];
    $content = $_REQUEST['content'];
    $meeting_date = $_REQUEST['meeting_date'];
	$minutes = $_REQUEST['minutes'];
    //$small_logo_image = $_REQUEST['small_logo_image'];    
    //$json_element_data = json_decode($_REQUEST['element_data'],true); 
}
if($action == "user_list"){
        $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"admin_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->getAlluser($data);
    
}
if($action == "get_admin_settings"){
	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->getAdminData($user_id);
}
if($action == "get_single_admin_settings"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->getsingleAdminData($pbx_id);   
}
if($action == "get_agent_data"){

    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->getUserData($user_id);   
	$result_data["result"]["encLogin"] = $user->getEncriptedLogin($user_id);  
}

if($action == "getUserActiveStatus"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->getUserActiveStatus($user_id);   
}


/*if($action == "edit_single_adminsettings"){
    $result_data["result"]["status"] = true;
$result_data["result"]["data"] = $user->EditSingleAdminsettings($admin_name,$pbx_count,$agent_count,$id,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$two_factor,$company_name,$domain_name,$has_fax,$has_external_contact,$reports);   
}
if($action == "add_single_admin"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->AddSingleAdmin($name,$pbx_count,$agent_counts,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$two_factor,$company_name,$domain_name,$has_fax,$has_external_contact,$reports);   
}*/
if($action == "edit_single_adminsettings"){
	
    $result_data["result"]["status"] = true;
$result_data["result"]["data"] = $user->EditSingleAdminsettings($admin_name,$pbx_count,$agent_count,$id,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$wallboard_five,$wallboard_six,$two_factor,$company_name,$domain_name,$has_fax,$has_internal_chat,$reports,$whatsapp_num,$price_sms,$survey_vid,$tarrif_id,$whatsapp_type,$wp_instance_count,$reseller,$sms_type,$mr_voip,$notes,$has_webinar,$plan_id,$call_rate,$valid_from,$valid_to,$call_prefix,$tax_name,$tax_per,$voice_manage,$baisc_wallboard,$wrap_up,$queue,$wallboard_eight);   
}
if($action == "add_single_admin"){
	//echo $price_sms;exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->AddSingleAdmin($name,$pbx_count,$agent_counts,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_line,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$wallboard_five,$wallboard_six,$two_factor,$company_name,$domain_name,$has_fax,$has_internal_chat,$reports,$price_sms,$survey_vid,$tarrif_id,$whatsapp_type,$wp_instance_count,$reseller,$user_id,$sms_type,$mr_voip,$notes,$has_webinar,$plan_id,$call_rate,$valid_from,$valid_to,$call_prefix,$tax_name,$tax_per,$voice_manage,$baisc_wallboard,$wrap_up,$queue,$wallboard_eight);   
}
if($action == "add_agent"){
	if($has_contact == 1 || $has_contact == true){ $has_contact = 1; }else{  $has_contact = 0; }
	if($has_sms == 1 || $has_sms == true){ $has_sms = 1; }else{ $has_sms = 0; }
	if($has_chat == 1 || $has_chat == true){ $has_chat = 1; }else{ $has_chat = 0; }
	if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapp = 1; }else{ $has_whatsapp = 0; }
    if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbot = 1; }else{ $has_chatbot = 0; }
    if($has_fb == 1 || $has_fb == true){ $has_fb = 1; }else{ $has_fb = 0; }
	if($has_fax == 1 || $has_fax == true){ $has_fax = 1; }else{ $has_fax = 0; }
    if($has_wechat == 1 || $has_wechat == true){ $has_wechat = 1; }else{ $has_wechat = 0; }
    if($has_telegram == 1 || $has_telegram == true){ $has_telegram = 1; }else{ $has_telegram = 0; }
    if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_ticket = 1; }else{ $has_internal_ticket = 0; }
    if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_ticket = 1; }else{ $has_external_ticket = 0; }
	if($user_status == 1 || $user_status == true){ $user_status = 1; }else{  $user_status = 0; }
	if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cx = 1; }else{ $voice_3cx = 0; }     
    if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialer = 1; }else{ $predective_dialer = 0; }
    if($lead == 1 || $lead == true){ $lead = 1; }else{ $lead = 0; }
	if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_one = 1; }else{ $wallboard_one = 0; }
    if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_two = 1; }else{ $wallboard_two = 0; }     
    if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_three = 1; }else{ $wallboard_three = 0; }
    if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_four = 1; }else{ $wallboard_four = 0; }
	if($two_factor == 1 || $two_factor == true){ $two_factor = 1; }else{ $two_factor = 0; }
	if($close_all_menu == 1 || $close_all_menu == true){ $close_all_menu = 1; }else{ $close_all_menu = 0; }
	if($admin_permision == 1 || $admin_permision == true){ $admin_permision = 1; }else{ $admin_permision = 0; }
    if($predective_dialer_behave == 1 || $predective_dialer_behave == true){ $predective_dialer_behave = 1; }else{ $predective_dialer_behave = 0; }
	if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chat = 1; }else{ $has_internal_chat = 0; }
	if($dialer_auto_answer == 1 || $dialer_auto_answer == true){ $dialer_auto_answer = 1; }else{ $dialer_auto_answer = 0; }
	if($admin_id == 128){
        if($dsk_access == 1){
            $dsk_user_name = $dsk_username;
            $dsk_user_pwd = $dsk_password;
        }else{
            $dsk_user_name = '';
            $dsk_user_pwd = '';
        }
    }else{
        $dsk_access == 0;
        $dsk_user_name = '';
        $dsk_user_pwd = '';
    }
	$password = $user_pwd;
	$user_pwd = MD5(trim($user_pwd));
	$layout = 'light';$theme = 'black';
	$agent_data = array("agent_name"=>$agent_name,"phone_number"=>$phone_number,"email_id"=>$email_id,"user_status"=>$user_status, "created_ip"=>'',"created_by"=>$admin_id,"incoming_call_status"=>1,"user_type"=>4,"user_name"=>$user_name,"user_pwd"=>$user_pwd,"sip_login"=>$sip_login,"sip_username"=>$sip_username,"sip_password"=>$sip_password,"has_contact"=>$has_contact,"has_sms"=>$has_sms,"has_chat"=>$has_chat,"has_whatsapp"=>$has_whatsapp,"has_chatbot"=>$has_chatbot,"has_fb"=>$has_fb,"has_wechat"=>$has_wechat,"has_telegram"=>$has_telegram,"has_internal_ticket"=>$has_internal_ticket,"has_external_ticket"=>$has_external_ticket,"voice_3cx"=>$voice_3cx,"predective_dialer"=>$predective_dialer,"lead"=>$lead,"wallboard_one"=>$wallboard_one,"wallboard_two"=>$wallboard_two,"wallboard_three"=>$wallboard_three,"wallboard_four"=>$wallboard_four,"layout"=>$layout,"theme"=>$theme,"admin_id"=>$admin_id,"password"=>$password,"two_factor"=>$two_factor,"admin_permision"=>$admin_permision,"dsk_access"=>$dsk_access,"dsk_user_name"=>$dsk_user_name,"dsk_user_pwd"=>$dsk_user_pwd,"reports"=>$reports,"close_all_menu"=>$close_all_menu,"predective_dialer_behave"=>$predective_dialer_behave,"price_sms"=>$price_sms,"survey_vid"=>$survey_vid,"tarrif_id"=>$tarrif_id,"has_fax"=>$has_fax,"whatsapp_type"=>$whatsapp_type,"wp_instance_count"=>$wp_instance_count,"has_webinar"=>$has_webinar,"plan_id"=>$plan_id,"call_rate"=>$call_rate,"valid_from"=>$valid_from,"valid_to"=>$valid_to,"call_prefix"=>$call_prefix,"tax_name"=>$tax_name,"tax_per"=>$tax_per,"has_internal_chat"=>$has_internal_chat,"has_3cx_rep"=>$has_3cx_rep,"ag_group"=>$ag_group,"dialer_auto_answer"=>$dialer_auto_answer,"voice_manage"=>$voice_manage,"baisc_wallboard"=>$baisc_wallboard,"wrap_up"=>$wrap_up,"queue"=>$queue,"wallboard_eight"=>$wallboard_eight,"webrtc_server"=>$webrtc_server);
	//print_r($agent_data); exit;
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->createUser($agent_data);
}
/*if($action == "update_agent"){	
	$result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $user->update_agent($user_id,$agent_name,$email_id,$phone_number,$user_name,$sip_login,$sip_username,$sip_password);
}*/

if($action == "update_agent"){
    if($has_contact == 1 || $has_contact == true){ $has_contact = 1; }else{  $has_contact = 0; }
    if($has_sms == 1 || $has_sms == true){ $has_sms = 1; }else{ $has_sms = 0; }
    if($has_chat == 1 || $has_chat == true){ $has_chat = 1; }else{ $has_chat = 0; }
    if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapp = 1; }else{ $has_whatsapp = 0; }
    if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbot = 1; }else{ $has_chatbot = 0; }
    if($has_fb == 1 || $has_fb == true){ $has_fb = 1; }else{ $has_fb = 0; }
	if($has_fax == 1 || $has_fax == true){ $has_fax = 1; }else{ $has_fax = 0; }
    if($has_wechat == 1 || $has_wechat == true){ $has_wechat = 1; }else{ $has_wechat = 0; }
    if($has_telegram == 1 || $has_telegram == true){ $has_telegram = 1; }else{ $has_telegram = 0; }
    if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_ticket = 1; }else{ $has_internal_ticket = 0; }
    if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_ticket = 1; }else{ $has_external_ticket = 0; }
    if($user_status == 1 || $user_status == true){ $user_status = 1; }else{  $user_status = 0; }
    if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cx = 1; }else{ $voice_3cx = 0; }     
    if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialer = 1; }else{ $predective_dialer = 0; }
    if($lead == 1 || $lead == true){ $lead = 1; }else{ $lead = 0; }
    if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_one = 1; }else{ $wallboard_one = 0; }
    if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_two = 1; }else{ $wallboard_two = 0; }     
    if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_three = 1; }else{ $wallboard_three = 0; }
    if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_four = 1; }else{ $wallboard_four = 0; }
	if($close_all_menu == 1 || $close_all_menu == true){ $close_all_menu = 1; }else{ $close_all_menu = 0; }
	if($admin_permision == 1 || $admin_permision == true){ $admin_permision = 1; }else{ $admin_permision = 0; }
	if($has_admin == 1 || $has_admin == true){ $has_admin = 1; }else{ $has_admin = 0; }
	if($has_webinar == 1 || $has_webinar == true){ $has_webinar = 1; }else{ $has_webinar = 0; }
	if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chat = 1; }else{ $has_internal_chat = 0; }
	if($dialer_auto_answer == 1 || $dialer_auto_answer == true){ $dialer_auto_answer = 1; }else{ $dialer_auto_answer = 0; }
	//echo $admin_permision;exit;
    if($predective_dialer_behave == 1 || $predective_dialer_behave == true){ $predective_dialer_behave = 1; }else{ $predective_dialer_behave = 0; }
	if($admin_id == 128){
        if($dsk_access == 1){
            $dsk_user_name = $dsk_username;
            $dsk_user_pwd = $dsk_password;
        }else{
            $dsk_user_name = '';
            $dsk_user_pwd = '';
        }
    }else{
        $dsk_access == 0;
        $dsk_user_name = '';
        $dsk_user_pwd = '';
    }
	
		

     $agent_data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"agent_name"=>$agent_name,"phone_number"=>$phone_number,"email_id"=>$email_id,"user_status"=>$user_status,"user_name"=>$user_name,"password"=>$password,"sip_login"=>$sip_login,"sip_username"=>$sip_username,"sip_password"=>$sip_password,"has_contact"=>$has_contact,"has_sms"=>$has_sms,"has_chat"=>$has_chat,"has_whatsapp"=>$has_whatsapp,"has_chatbot"=>$has_chatbot,"has_fb"=>$has_fb,"has_wechat"=>$has_wechat,"has_telegram"=>$has_telegram,"has_internal_ticket"=>$has_internal_ticket,"has_external_ticket"=>$has_external_ticket,"voice_3cx"=>$voice_3cx,"predective_dialer"=>$predective_dialer,"lead"=>$lead,"wallboard_one"=>$wallboard_one,"wallboard_two"=>$wallboard_two,"wallboard_three"=>$wallboard_three,"wallboard_four"=>$wallboard_four,"dsk_access"=>$dsk_access,"dsk_user_name"=>$dsk_user_name,"dsk_user_pwd"=>$dsk_user_pwd,"reports"=>$reports,"close_all_menu"=>$close_all_menu,"admin_permision"=>$admin_permision,"predective_dialer_behave"=>$predective_dialer_behave,"price_sms"=>$price_sms,"survey_vid"=>$survey_vid,"tarrif_id"=>$tarrif_id,"has_fax"=>$has_fax,"whatsapp_type"=>$whatsapp_type,"wp_instance_count"=>$wp_instance_count,"has_admin"=>$has_admin,"has_webinar"=>$has_webinar,"call_rate"=>$call_rate,"valid_from"=>$valid_from,"valid_to"=>$valid_to,"plan_id"=>$plan_id,"call_prefix"=>$call_prefix,"tax_name"=>$tax_name,"tax_per"=>$tax_per,"has_internal_chat"=>$has_internal_chat,"has_3cx_rep"=>$has_3cx_rep,"ag_group"=>$ag_group,"dialer_auto_answer"=>$dialer_auto_answer,"voice_manage"=>$voice_manage,"baisc_wallboard"=>$baisc_wallboard,"wrap_up"=>$wrap_up,"queue"=>$queue,"wallboard_eight"=>$wallboard_eight,"webrtc_server"=>$webrtc_server); 
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $user->update_agent($agent_data);
}

if($action == "incoming_call_request"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->updateIncomingCallStatus($incoming_call_status, $user_id);
}

if($action == "get_timezone"){    
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->getTimezone($user_id);
}

/*if($action == "image_upload"){
	$email_id = $json_element_data['email_id'];
	$agent_name = $json_element_data['agent_name'];
	$phone_number = $json_element_data['phone_number'];
	$has_contact = $json_element_data['has_contact'];
	$has_sms = $json_element_data['has_sms'];
	$has_chat = $json_element_data['has_chat'];
	$sip_login = $json_element_data['sip_login'];
	$sip_username = $json_element_data['sip_username'];
	$sip_password = $json_element_data['sip_password'];	   
	$profile_image=$_FILES['profile_image']['name'];	
	if($has_contact == 1 || $has_contact == "true"){ $has_contact = '1'; }else{ $has_contact = 0; }
	if($has_sms == 1 || $has_sms == 'true'){ $has_sms = 1; }else{ $has_sms = 0; }
    if($has_chat == 1 || $has_chat == 'true'){ $has_chat = 1; }else{ $has_chat = 0; }
	$agent_data = array("email_id"=>$email_id,"agent_name"=>$agent_name, "phone_number"=>$phone_number,"has_contact"=>$has_contact,"has_sms"=>$has_sms,"has_chat"=>$has_chat,"sip_login"=>$sip_login,"sip_username"=>$sip_username,"sip_password"=>$sip_password,"profile_image"=>$profile_image,"img_user_id"=>$img_user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->updateUser($agent_data, array("user_id"=>$img_user_id));
}*/
if($action == "image_upload"){
    $user_name = $json_element_data['user_name'];
    $agent_name = $json_element_data['agent_name'];
    $sip_login = $json_element_data['sip_login'];
    $sip_username = $json_element_data['sip_username'];
    $sip_password = $json_element_data['sip_password'];
    $email_id = $json_element_data['email_id'];    
    $phone_number = $json_element_data['phone_number'];             
    $profile_image=$_FILES['profile_image']['name'];        
    $agent_data = array("user_name"=>$user_name,"agent_name"=>$agent_name, "sip_login"=>$sip_login,"sip_username"=>$sip_username,"sip_password"=>$sip_password,"email_id"=>$email_id,"phone_number"=>$phone_number,"profile_image"=>$profile_image,"img_user_id"=>$img_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->updateUser($agent_data, array("user_id"=>$img_user_id));
}
if($action == "admin_global_settings"){    
   // $timezone_id = $json_element_data['timezone_id']; 
    //$ext_int_status = $json_element_data['ext_int_status'];       
    $logo_image=$_FILES['logo_image']['name'];
    $small_logo_image=$_FILES['small_logo_image']['name'];   
	$has_video_call = $_REQUEST['has_video_call'];
	$dialer_ring = $_REQUEST['dialer_ring'];
	$webrtc_server = $_REQUEST['webrtc_server'];
	//echo $dialer_ring;exit;
    $agent_data = array("timezone_id"=>$timezone_id,"ext_int_status"=>$ext_int_status,"show_caller_id"=>$show_caller_id,"logo_image"=>$logo_image,"small_logo_image"=>$small_logo_image,"img_user_id"=>$img_user_id,"has_video_call"=>$has_video_call,"dialer_ring"=>$dialer_ring,"webrtc_server"=>$webrtc_server);

    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->admin_global_settings($agent_data, array("user_id"=>$img_user_id));
}
if($action == "webinar_meeting"){           
    $logo_image=$_FILES['logo_image']['name'];
    $content_image=$_FILES['content_image']['name'];    
    $agent_data = array("timezone_id"=>$timezone_id,"title"=>$title,"content"=>$content,"logo_image"=>$logo_image,"content_image"=>$content_image,"img_user_id"=>$img_user_id,"meeting_date" => $meeting_date,"minutes"=>$minutes);
    //print_r($agent_data);exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->webinar_meeting($agent_data);
}
if($action == "list_webinar_meeting"){        
    $data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $user->list_webinar_meeting($data);
}
if($action == "edit_webinar_meeting"){
    $data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["data"] = $user->edit_webinar_meeting($data);
}
if($action == "update_webinar_meeting"){           
    $logo_image=$_FILES['logo_image']['name'];
    $content_image=$_FILES['content_image']['name'];    
    $agent_data = array("id"=>$id,"admin_id"=>$admin_id,"timezone_id"=>$timezone_id,"title"=>$title,"content"=>$content,"logo_image"=>$logo_image,"content_image"=>$content_image,"meeting_date" => $meeting_date,"minutes"=>$minutes);
    //print_r($agent_data);exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->update_webinar_meeting($agent_data);
}
if($action == "delete_webinar_meeting"){     
    $data = array("admin_id"=>$admin_id, "id"=>$id);       
    $result_data["result"]["data"] = $user->delete_webinar_meeting($data);
}
if($action == "update_settings"){    
    $data = array("user_id"=>$user_id,"layout"=>$layout,"theme"=>$theme);
    $result_data["result"]["data"] = $user->updateSettings($data);
}
if($action == "edit_dialer_timezone"){    
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->editdialertimezone($data);
}
if($action == "update_dialer_timezone"){    
    $data = array("user_id"=>$user_id,"timezone_id"=>$timezone_id,"ext_int_status"=>$ext_int_status);
    $result_data["result"]["data"] = $user->update_dialer_timezone($data);
}
if($action == "wallboard_counts"){    
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->wallboard_counts($data);
}
if($action == "toggle_status"){	
    $data = array("status_for"=>$status_for,"id"=>$id);
    $result_data["result"]["data"] = $user->toggle_status($data);
}
if($action == "take_down_toggle_status"){	
    $data = array("status_for"=>$status_for,"id"=>$id);
    $result_data["result"]["data"] = $user->take_down_toggle_status($data);
}



if($action == "change_agent_permission"){    
    $data = array("user_id"=>$user_id,"keyword"=>$keyword);    
    $result_data["result"]["data"] = $user->change_agent_permission($data);
}
if($action == "delete_admin"){     
    $data = array("user_id"=>$user_id);       
    $result_data["result"]["data"] = $user->delete_admin($data);
}
if($action == "delete_agent"){     
    $data = array("user_id"=>$user_id,"admin_id"=>$admin_id);       
    $result_data["result"]["data"] = $user->delete_agent($data);
}
if($action == "get_admin_global_settings"){    
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->get_admin_global_settings($data);
}
if($action == "forgot_password"){     
    $data = array("email"=>$email);       
    $result_data["result"]["data"] = $user->forgot_password($data);
}
if($action == "choose_marketplace"){    
    $data = array("wallboard_id"=>$wallboard_id,"admin_id"=>$admin_id);
    $result_data["result"]["data"] = $user->choose_marketplace($data);
}
if($action == "check_license"){    
    $data = array("user_id"=>$user_id,"license_key"=>$license_key);
    $result_data["result"]["data"] = $user->check_license($data);
}
/*if($action == 'check_license'){
     $data = array("api_user"=>"Cal4careCms", "api_pass"=>"16c21a5c08758dc10dadb11b7c8cc182", "access"=>"omni_site", "page_access"=>"view_page","action_info"=>"check_license", "user_id"=>$user_id,"license_key"=>$license_key);
     $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->check_license($data);
}*/
if($action == "check_hardware"){    
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->check_hardware($data);
}
if($action == "add_report"){    
    $data = array("report_name"=>$report_name,"report_url"=>$report_url);
    $result_data["result"]["data"] = $user->add_report($data);
}
if($action == "edit_report"){    
    $data = array("id"=>$id);
    $result_data["result"]["data"] = $user->edit_report($data);
}
if($action == "update_report"){    
    $data = array("id"=>$id,"report_name"=>$report_name,"report_url"=>$report_url);
    $result_data["result"]["data"] = $user->update_report($data);
}
if($action == "list_report"){        
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $user->list_report($data);
}
if($action == "add_fax_user"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->add_fax_user($user_name,$password,$first_name,$last_name,$phone,$email,$address,$country,$timezone_id,$company_name,$daily_limit,$monthly_limit);   
}
if($action == "list_fax_users"){
	$data = array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;        
    $result_data["result"]["data"] = $user->list_fax_users($data);
}
if($action == "edit_fax_user"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->edit_fax_user($user_id);   
}
if($action == "update_fax_user"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->update_fax_user($user_id,$fax_user_id,$user_name,$first_name,$last_name,$phone,$email,$address,$company_name,$daily_limit,$monthly_limit,$timezone_id);   
}
if($action == "delete_fax_user"){     
    $data = array("user_id"=>$user_id,"fax_user_id"=>$fax_user_id);       
    $result_data["result"]["data"] = $user->delete_fax_user($data);
}
if($action == "user_log_out"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->user_log_out($user_id);   
}
if($action == "update_external_contact"){    
    $data = array("user_id"=>$user_id,"has_external_contact"=>$has_external_contact,"external_contact_url"=>$external_contact_url,"crm_type"=>$crm_type);
    $result_data["result"]["data"] = $user->update_external_contact($data);
}
if($action == "sip_registered_status"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $user->sip_registered_status($user_id, $status);   
}
if($action == "whatsapp_account_settings"){    
    $data = array("user_id"=>$user_id,"whatsapp_account"=>$whatsapp_account);
    $result_data["result"]["data"] = $user->whatsapp_account_settings($data);
}
if($action == "notification_code"){    
    $data = array("user_id"=>$user_id,"notification_code"=>$notification_code);
    $result_data["result"]["data"] = $user->notification_code($data);
}
if($action == "android_token"){    
    $data = array("user_id"=>$user_id,"notification_code"=>$android_token);
    $result_data["result"]["data"] = $user->android_token($data);
}
if($action == "curl_response"){    
    $data = array("url"=>$url);
    $result_data["result"]["data"] = $user->curl_response($data);
}
if($action == "curl_response_zoho_desk"){    
    $data = array("url"=>$url,"authkey"=>$authkey,"orgID"=>$orgID,"number"=>$number);
    $result_data["result"]["data"] = $user->curl_response_zoho_desk($data);
}
if($action == "dialer_settings"){

    $result_data["result"]["data"] = $user->dialer_settings($login);
}
if($action == "welcome_email"){    
    $data = array("user_id"=>$user_id,"email"=>$email);
    $result_data["result"]["data"] = $user->welcome_email($data);
}
if($action == "facebook_account_settings"){    
    $data = array("user_id"=>$user_id,"facebook_account"=>$facebook_account);
    $result_data["result"]["data"] = $user->facebook_account_settings($data);
}
if($action == "update_whatsapp_num"){    
    $data = array("admin_id"=>$admin_id,"whatsapp_num"=>$whatsapp_num,"wp_inst_id"=>$wp_inst_id);
    $result_data["result"]["data"] = $user->update_whatsapp_num($data);
}
if($action == "getInstanceDetails"){    
    $result_data["result"]["data"] = $user->getInstanceDetails();
}
if($action == "getallInstanceDetails"){    
    $result_data["result"]["data"] = $user->getallInstanceDetails($admin_id);
}

if($action == "getalladmins"){    
    $result_data["result"]["data"] = $user->getalladmins();
}
if($action == "agent_billing_det"){    
	$data = array("admin_id"=>$admin_id,"user_id"=>$user_id,"contact_person"=>$contact_person,"add1"=>$add1,"add2"=>$add2,"city"=>$city,"state"=>$state, "zip_code"=>$zip_code,"country"=>$country,"edit_ship"=>$edit_ship,"ship_contact"=>$ship_contact,"ship_contact"=>$ship_contact,"ship_to"=>$ship_to ,"ship_add1"=>$ship_add1,"ship_add2"=>$ship_add2,"ship_city"=>$ship_city,"ship_state"=>$ship_state,"ship_zip"=>$ship_zip, "ship_country"=>$ship_country, "monthly_charges"=>$monthly_charges,"discount_per"=>$discount_per);
    $result_data["result"]["data"] = $user->agent_billing_det($data);
}
if($action == "edit_agent_billing_det"){    
	$data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["data"] = $user->edit_agent_billing_det($data);
}
if($action == "update_agent_billing_det"){    
	$data = array("admin_id"=>$admin_id,"user_id"=>$user_id,"contact_person"=>$contact_person,"add1"=>$add1,"add2"=>$add2,"city"=>$city,"state"=>$state, "zip_code"=>$zip_code,"country"=>$country,"edit_ship"=>$edit_ship,"ship_contact"=>$ship_contact,"ship_contact"=>$ship_contact,"ship_to"=>$ship_to ,"ship_add1"=>$ship_add1,"ship_add2"=>$ship_add2,"ship_city"=>$ship_city,"ship_state"=>$ship_state,"ship_zip"=>$ship_zip, "ship_country"=>$ship_country, "monthly_charges"=>$monthly_charges,"discount_per"=>$discount_per);
    $result_data["result"]["data"] = $user->update_agent_billing_det($data);
}
if($action == "clickToCallWidget"){
    $result_data["result"]["data"] = $user->clickToCallWidget($login,$number);
}
if($action == "add_admin_biller"){    
	$data = array("admin_id"=>$admin_id,"user_id"=>$user_id,"add1"=>$add1,"add2"=>$add2,"city"=>$city,"state"=>$state, "zip_code"=>$zip_code,"country"=>$country,"phone"=>$phone,"email"=>$email,"toll_free"=>$toll_free,"reg_no"=>$reg_no,"logo_image"=>$logo_image,"account_no"=>$account_no,"bank"=>$bank,"branch"=>$branch);
    $result_data["result"]["data"] = $user->add_admin_biller($data);
}
if($action == "agent_group"){    
	$data = array("admin_id"=>$admin_id,"group_name"=>$group_name,"agents"=>$agents);
    $result_data["result"]["data"] = $user->agent_group($data);
}
if($action == "view_agent_group"){    
	$data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $user->view_agent_group($data);
}
if($action == "ed_agent_group"){    
	$data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["data"] = $user->ed_agent_group($data);
}
if($action == "del_agent_group"){    
	$data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["data"] = $user->del_agent_group($data);
}
if($action == "update_agent_group"){    
		$data = array("admin_id"=>$admin_id,"group_name"=>$group_name,"agents"=>$agents,"id"=>$id);
    $result_data["result"]["data"] = $user->update_agent_group($data);
}
if($action == "erp_mobile_user"){    
		$data = array("extension"=>$ext_no);
    $result_data["result"]["data"] = $user->erp_mobile_user($data);
}

?>