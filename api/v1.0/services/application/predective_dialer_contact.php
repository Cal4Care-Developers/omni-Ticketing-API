<?php

$result_data["status"] = true;
$predective_dialer_contact = new predective_dialer_contact();
if($_REQUEST['action'] == 'mrvoip_upload') {  
    $action ='mrvoip_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$mrvoip_version = $_REQUEST['mrvoip_version'];
	$sec_title = $_REQUEST['sec_title'];
	$sec2_title = $_REQUEST['sec2_title'];
    $mrvoip_main=$_FILES['mrvoip_main']['name'];
	$mrvoip_linux_1=$_FILES['linux_document_1']['name']; 
    $mrvoip_windows_1=$_FILES['windows_document_1']['name'];
    $mrvoip_linux=$_FILES['mrvoip_linux']['name']; 
    $mrvoip_windows=$_FILES['mrvoip_windows']['name'];
}
if($_REQUEST['action'] == 'agent_rating_upload') {     
    $action ='agent_rating_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$agent_rating_version = $_REQUEST['agent_rating_version'];
    $agent_rating_main=$_FILES['agent_rating_main']['name']; 
    $agent_rating_1=$_FILES['agent_rating_1']['name']; 
    $agent_rating_2=$_FILES['agent_rating_2']['name']; 
}
if($_REQUEST['action'] == 'predective_dialer_upload') {     
    $action ='predective_dialer_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$pd_version = $_REQUEST['pd_version'];
    $pd_main=$_FILES['pd_main']['name']; 
    $camp_1=$_FILES['camp_1']['name']; 
    $camp_2=$_FILES['camp_2']['name'];
    $camp_3=$_FILES['camp_3']['name']; 
    $camp_4=$_FILES['camp_4']['name']; 
}

if($_REQUEST['action'] == 'proactive_dialer_upload') {     
    $action ='proactive_dialer_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$pro_version = $_REQUEST['pro_version'];
    $pro_main=$_FILES['pro_main']['name']; 
    $camp_1=$_FILES['camp_1']['name']; 
}

if($_REQUEST['action'] == 'broadcast_dialler_upload') {     
    $action ='broadcast_dialler_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$pro_version = $_REQUEST['bd_version'];
    $pro_main=$_FILES['bd_main']['name']; 
    $camp_1=$_FILES['camp_1']['name']; 
    $camp_2=$_FILES['camp_2']['name']; 
}


if($_REQUEST['action'] == 'broadcast_survey_dialler_upload') {     
    $action ='broadcast_survey_dialler_upload'; 
    $img_user_id = $_REQUEST['user_id'];
	$bs_version = $_REQUEST['bs_version'];
    $bs_main=$_FILES['bs_main']['name']; 
    $camp_1=$_FILES['camp_1']['name']; 
    $camp_2=$_FILES['camp_2']['name']; 
}

if($action == 'add_contact'){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->createContact($user_id,$admin_id,$campaign_id,$customer_name,$address,$city,$state,$zipcode,$country,$phone_number,$source_data,$notes);   
}
if($action == "get_campaign"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->get_campaign($admin_id);
}
if($action == 'edit_contact'){
    $data= array("contact_id"=>$contact_id, "user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->editContacts($data);   
}
if($action == 'update_contact'){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->updateContact($contact_id,$user_id,$admin_id,$campaign_id,$customer_name,$address,$city,$state,$zipcode,$country,$phone_number,$source_data,$notes); 
}
if($action == 'contact_list'){
    $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->listContacts($data);   
}
if($action == "delete_contact"){     
    $data = array("contact_id"=>$contact_id,"user_id"=>$user_id);     
    $result_data["result"]["data"] = $predective_dialer_contact->delete_dialer_contact($data);
}
if($action == "mrvoip_upload"){  
	$agent_data = array("mrvoip_main"=>$mrvoip_main,"mrvoip_linux_1"=>$mrvoip_linux_1,"mrvoip_windows_1"=>$mrvoip_windows_1,"mrvoip_linux"=>$mrvoip_linux,"mrvoip_windows"=>$mrvoip_windows,"img_user_id"=>$img_user_id,"mrvoip_version"=>$mrvoip_version,"sec_title"=>$sec_title,"sec2_title"=>$sec2_title);
	
	// print_r($agent_data); exit;
	
	
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->mrvoip_upload($agent_data);
	
}
if($action == "agent_rating_upload"){           
    $agent_data = array("agent_rating_main"=>$agent_rating_main,"agent_rating_1"=>$agent_rating_1,"agent_rating_2"=>$agent_rating_2,"img_user_id"=>$img_user_id,"agent_rating_version"=>$agent_rating_version);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->agent_rating_upload($agent_data);
}
if($action == "predective_dialer_upload"){           
    $agent_data = array("pd_main"=>$pd_main,"camp_1"=>$camp_1,"camp_2"=>$camp_2,"camp_3"=>$camp_3,"camp_4"=>$camp_4,"img_user_id"=>$img_user_id,"pd_version"=>$pd_version);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->predective_dialer_upload($agent_data);
}
if($action == "proactive_dialer_upload"){           
    $agent_data = array("pro_main"=>$pro_main,"camp_1"=>$camp_1,"img_user_id"=>$img_user_id,"pro_version"=>$pro_version);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->proactive_dialer_upload($agent_data);
}
if($action == "broadcast_dialler_upload"){           
    $agent_data = array("bd_main"=>$bd_main,"camp_1"=>$camp_1,"camp_2"=>$camp_2, "img_user_id"=>$img_user_id,"bd_version"=>$pro_version);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->broadcast_dialler_upload($agent_data);
}
if($action == "broadcast_survey_dialler_upload"){           
    $agent_data = array("bs_main"=>$bd_main,"camp_1"=>$camp_1,"camp_2"=>$camp_2, "img_user_id"=>$img_user_id,"bs_version"=>$pro_version);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->broadcast_survey_dialler_upload($agent_data);
}
if($action == 'upload_list'){
    //$data= array("user_id"=>$user_id);    
    $result_data["result"]["data"] = $predective_dialer_contact->upload_list();   
}
if($action == "delete_mrvoip_upload"){     
    $data = array("column_name"=>$column_name);     
    $result_data["result"]["data"] = $predective_dialer_contact->delete_mrvoip_upload($data);
}
if($action == "zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->zipfile_update($data);
}
if($action == "pd_zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name, "cmp_id"=>$cmp_id, "cmp_pre"=>$cmp_pre);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->pd_zipfile_update($data);
}
if($action == 'invalid_list'){
    $data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->listinvalid($data);   
}
if($action == 'invalid_update'){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->invalid_update($data);   
}			

if($action == 'dnd_list'){
    $data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"admin_id"=>$admin_id,"stat"=>$stat);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->listdnd($data);   
}
if($action == 'dnd_update'){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->dnd_update($data);   
}
if($action == 'camp_call'){
	//echo'123';exit;
    $data= array("phone_no"=>$phone_no, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->camp_call($data);   
}
if($action == 'update_camp_call'){
	//echo'123';exit;
    $data= array("phone_no"=>$phone_no, "admin_id"=>$admin_id, "stat"=>$stat, "camp_id"=>$camp_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->update_camp_call($data);   
}
if($action == "pro_zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name, "cmp_id"=>$cmp_id, "cmp_pre"=>$cmp_pre);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->pro_zipfile_update($data);
}

if($action == "bd_zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name, "cmp_id"=>$cmp_id, "cmp_pre"=>$cmp_pre,"broadcast_audio"=>$broadcast_audio);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->bd_zipfile_update($data);
}

if($action == "bs_zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name, "cmp_id"=>$cmp_id, "cmp_pre"=>$cmp_pre,"broadcast_audio"=>$broadcast_audio);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->bs_zipfile_update($data);
}

if($action == "rak_zipfile_update"){ 
    $data = array("user_id"=>$user_id, "column_name"=>$column_name, "table_name"=>$table_name, "cmp_id"=>$cmp_id, "cmp_pre"=>$cmp_pre,"broadcast_audio"=>$broadcast_audio,"parallel"=>"$parallel","frequency"=>"$frequency",);   
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->rak_zipfile_update($data);
}
if($action == 'edit_popup_contact'){
    $data= array("phone_no"=>$phone_no, "user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $predective_dialer_contact->edit_popup_contact($data);   
}