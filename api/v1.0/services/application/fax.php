<?php
$result_data["status"] = true;
$fax = new fax();
if($_REQUEST['action'] == 'fax_upload') {  
    $action ='fax_upload'; 
    $user_id = $_REQUEST['user_id'];
	$fax_user_id = $_REQUEST['fax_user_id'];
    $name = $_REQUEST['name'];
    $file = $_FILES['document_file']['name'];
    $description = $_REQUEST['description'];
	$type = $_REQUEST['type'];
}
if($action == "fax_upload"){  
    $fax_data = array("name"=>$name,"file"=>$file,"description"=>$description,"type"=>$type,"user_id"=>$user_id,"fax_user_id"=>$fax_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->fax_upload($fax_data);
}
if($_REQUEST['action'] == 'edit_fax_upload') {  
    $action ='edit_fax_upload'; 
    $user_id = $_REQUEST['user_id'];
	$fax_user_id = $_REQUEST['fax_user_id'];
    $name = $_REQUEST['name'];
    $doc_id = $_REQUEST['doc_id'];
    $file = $_FILES['document_file']['name'];
    $description = $_REQUEST['description'];
    $type = $_REQUEST['type'];                
}
if($action == "edit_fax_upload"){  
    $fax_data = array("name"=>$name,"doc_id"=>$doc_id,"file"=>$file,"description"=>$description,"type"=>$type,"user_id"=>$user_id,"fax_user_id"=>$fax_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->edit_fax_upload($fax_data);
}
if($action == "get_docid"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->get_docid($user_id);   
}
if($action == "get_contacts"){
    $data = array("search_val"=>$search_val,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->get_contacts($data);   
}
if($action == "send_fax"){  
    $fax_data = array("title"=>$title,"doc_id"=>$doc_id,"number"=>$number,"try_allowed"=>$try_allowed,"user_id"=>$user_id,"fax_user_id"=>$fax_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->send_fax($fax_data);
}
if($action == "sent_fax_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->sent_fax_list($user_id);   
}
if($action == "delete_fax_document"){
    $fax_data = array("doc_id"=>$doc_id,"user_id"=>$user_id,"fax_user_id"=>$fax_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->delete_fax_document($fax_data);   
}
if($action == "get_by_id"){
    $fax_data = array("doc_id"=>$doc_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->get_by_id($fax_data);   
}
if($action == "get_fax_stat"){
  //$fax_data = array("doc_id"=>$doc_id,"user_id"=>$user_id);
  $result_data["result"]["status"] = true;
  $result_data["result"]["data"] = $fax->get_fax_stat();
}
if($action == "add_didnumber"){    
    $data = array("didnumber"=>$didnumber,"title"=>$title);    
    $result_data["result"]["data"] = $fax->add_didnumber($data);
}
if($action == "edit_didnumber"){    
    $data = array("didid"=>$didid);    
    $result_data["result"]["data"] = $fax->edit_didnumber($data);
}
if($action == "didnumber_list"){
    //$data = array("admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $fax->didnumber_list();
}
if($action == "update_didnumber"){    
    $data = array("id"=>$id,"didnumber"=>$didnumber,"title"=>$title,"didid"=>$didid);    
    $result_data["result"]["data"] = $fax->update_didnumber($data);
}
if($action == "delete_didnumber"){    
    $data = array("id"=>$id,"didid"=>$didid);    
    $result_data["result"]["data"] = $fax->delete_didnumber($data);
}
if($action == "assign_didnumber"){
    $fax_data = array("didid"=>$didid,"selected_user_id"=>$selected_user_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->assign_didnumber($fax_data);   
}
if($action == "get_admin_didnumbers"){    
    $fax_data = array("user_id"=>$user_id,"fax_user_id"=>$fax_user_id);    
    $result_data["result"]["data"] = $fax->get_admin_didnumbers($fax_data);
}
if($action == "assign_email_didnumber"){
    $fax_data = array("didid"=>$didid,"username"=>$username,"email"=>$email,"first_name"=>$first_name,"phone"=>$phone,"user_id"=>$user_id,"fax_user_id"=>$fax_user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $fax->assign_email_didnumber($fax_data);   
}
if($action == "receive_fax_list"){    
    $fax_data = array("user_id"=>$user_id,"fax_user_id"=>$fax_user_id);    
    $result_data["result"]["data"] = $fax->receive_fax_list($fax_data);
}
if($action == "download_fax_document"){    
    $fax_data = array("user_id"=>$user_id,"fax_user_id"=>$fax_user_id,"doc_id"=>$doc_id);    
    $result_data["result"]["data"] = $fax->download_fax_document($fax_data);
}