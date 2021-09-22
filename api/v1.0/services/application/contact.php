<?php
$result_data["status"] = true;
$contact = new contacts();
if($_REQUEST['action'] == 'csv_upload') { $action ='csv_upload'; $csv_user_id = $_REQUEST['user_id'];}
if($_REQUEST['action'] == 'group_csv_upload') { 
	$action ='group_csv_upload'; 
	$csv_user_id = $_REQUEST['user_id'];
	$group_name = $_REQUEST['group_name'];
}
if($action == 'add_contact'){
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->createContact($contact_owner,$first_name,$last_name,$account_name,$lead_source,$title,$email,$department,$activity,$res_dept,$phone,$home_phone,$office_phone,$fax,$mobile,$dob,$assistant,$assitant_phone,$reports_to,$email_opt_out,$skype,$secondary_email,$twitter,$reporting_to,$mailing_street,$other_street,$mailing_city,$other_city,$mailing_province,$other_province,$mailing_postal_code,$other_postal_code,$mailing_country,$other_country,$created_by,$notes,$auxcode_name,$user_id,$callid,$facebook_url,$whatsapp_number,$line,$wechat,$viber,$telegram,$instagram_url,$linkedin,$country_code);   
}
if($action == 'edit_contact'){
	        $data= array("contact_phone"=>$contact_phone, "user_id"=>$user_id);

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->editContacts($data);   
}

if($action == 'contact_list'){
	
	        $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"user_id"=>$user_id);

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->listContacts($data);   
}
if($action == 'update_contact'){
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->updateContact($contact_owner,$first_name,$last_name,$account_name,$lead_source,$title,$email,$department,$activity,$res_dept,$phone,$home_phone,$office_phone,$fax,$mobile,$dob,$assistant,$assitant_phone,$reports_to,$email_opt_out,$skype,$secondary_email,$twitter,$reporting_to,$mailing_street,$other_street,$mailing_city,$other_city,$mailing_province,$other_province,$mailing_postal_code,$other_postal_code,$mailing_country,$other_country,$modified_by,$contact_id,$notes,$auxcode_name,$callid,$facebook_url,$whatsapp_number,$line,$wechat,$viber,$telegram,$instagram_url,$linkedin,$country_code); 
} 
if($action == 'get_contact_notes'){
	$data= array("contact_id"=>$contact_id, "user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getContactsnotes($data);   
}
if($action == 'has_contact_access'){
	$data= array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
	$result_data["result"]["webrtcServer"] = $contact->getWebrtcServer($user_id);
    $result_data["result"]["data"] = $contact->hasContactAcc($data);   
}
if($action == 'get_contact_by_id'){
	$data= array("contact_id"=>$contact_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getConatctByID($data);   
}
if($action == 'bulk_upload_contact'){
	$data= array("contact_id"=>$contact_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getConatctByID($data);   
}
if($action == 'contact_reports'){
	$data= array("user_id"=>$user_id,"agents"=>$agents,"fromDate"=>$fromDate,"toDate"=>$toDate,"phone"=>$phone);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->contactReport($data);   
}
if($action == 'get_all_my_users'){
	$data= array("user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getAllMUsers($data);   
}
if($action == 'contacts_number_list'){
    $data = array("user_id"=>$user_id,"phone_num"=>$phone_num);

	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getAllContactsNumber($data);   
}
if($action == 'csv_upload'){
	$data = array("user_id"=>$csv_user_id,"files"=>$_FILES['file']);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->csvBulkImport($data);   
}

if($action == 'delete_contact'){
	$data= array("contact_id"=>$contact_id, "user_id"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->deleteContact($data);   
}
if($action == "add_aux_code"){    
    $data = array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->addAuxcode($data);
}
if($action == "update_aux_code"){
    $data = array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id,"auxcode_id"=>$auxcode_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->updateAuxcode($data);
}
if($action == "get_aux_code"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getAuxcode($admin_id);
}
if($action == "edit_aux_code"){
	$data = array("admin_id"=>$admin_id,"auxcode_id"=>$auxcode_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getAuxcode_data($data);
}
if($action == "check_aux_code"){
	$data = array("admin_id"=>$admin_id,"cat_id"=>$cat_id,"aux_code"=>$aux_code);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->checkAuxcode_data($data);
}
if($action == "delete_auxcode"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $contact->delete_auxcode($data);
}
if($action == "add_senderid"){    
    $data = array("senderid"=>$senderid,"admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $contact->add_senderid($data);
}
if($action == "delete_senderid"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $contact->delete_senderid($data);
}
if($action == "get_senderid"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->get_senderid($admin_id);
}
if($action == "add_sms_group"){    
    $data = array("group_name"=>$group_name,"group_users"=>$group_users,"status"=>$status,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->add_sms_group($data);
}
if($action == "list_smsgroup"){
    $data= array("admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $contact->list_smsgroup($data);
}
/*if($action == "edit_smsgroup"){
    $data= array("admin_id"=>$admin_id,"group_id"=>$group_id);    
    $result_data["result"]["data"] = $contact->edit_smsgroup($data);
}*/
if($action == "edit_smsgroup"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->edit_smsgroup($group_id);
}
if($action == "update_smsgroup"){
    $data = array("group_name"=>$group_name,"group_users"=>$group_users, "status"=>$status,"admin_id"=>$admin_id,"group_id"=>$group_id);    
    $result_data["result"]["data"] = $contact->update_smsgroup($data);
}
if($action == "delete_smsgroup"){     
    $data = array("group_id"=>$group_id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $contact->delete_smsgroup($data);
}
if($action == 'group_csv_upload'){
	$data = array("user_id"=>$csv_user_id,"files"=>$_FILES['file'],"group_name"=>$group_name,);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->csvGroupBulkImport($data);   
}
if($_REQUEST['action'] == 'pre_csv_upload') {$action ='pre_csv_upload'; $pre_csv_user_id = $_REQUEST['user_id']; $pre_csv_admin_id = $_REQUEST['admin_id'];}
if($action == 'pre_csv_upload'){
	$data = array("user_id"=>$pre_csv_user_id,"files"=>$_FILES['file'],"admin_id"=>$pre_csv_admin_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->precsvBulkImport($data);   
}

if($action == 'add_auxcode_wall'){
	
	$data= array("from_no"=>$from_no, "to_no"=>$to_no,"type"=>$type, "aux_code"=>$aux_code, "user_id"=>$user_id,"cat_id"=>$cat_id,"call_note"=>$call_note);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->add_auxcode_wall($data);   
}
if($action == "add_aux_code_category"){    
    $data = array("category_name"=>$category_name,"admin_id"=>$admin_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->add_aux_code_category($data);
}
if($action == "get_aux_code_category"){
	$data = array("user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getAuxcode_category($data);
}
if($action == "aux_code_category_list"){
	$data = array("user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->aux_code_category_list($data);
}
if($action == "edit_aux_code_category"){
	$data = array("admin_id"=>$admin_id,"cat_id"=>$cat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->editAuxcode_category($data);
}
if($action == "update_aux_code_category"){
    $data = array("category_name"=>$category_name,"admin_id"=>$admin_id,"cat_id"=>$cat_id,"dept_id"=>$dept_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->updateAuxcode_category($data);
}
if($action == "delete_auxcode_category"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $contact->delete_auxcode_category($data);
}
if($action == "getuax_by_cat"){
	$data = array("admin_id"=>$admin_id,"cat_id"=>$cat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $contact->getuax_by_cat($data);
}