<?php
$result_data["status"] = true;
$email_blasting = new email_blasting();

if($action == "list_emailgroup"){
    $data= array("admin_id"=>$admin_id);    
    $result_data["result"]["data"] = $email_blasting->list_emailgroup($data);
} elseif($action == "get_user_list"){     
		$result_data["result"]["data"] = $email_blasting->get_user_list($admin_id);
} elseif($action == "add_email_group"){    
    $data = array("group_name"=>$group_name,"group_users"=>$group_users,"status"=>$status,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $email_blasting->add_email_group($data);
} elseif($action == "edit_emailgroup"){
		$result_data["result"]["status"] = true;
		$result_data["result"]["data"] = $email_blasting->edit_emailgroup($id);
} elseif($action == "update_emailgroup"){
		$data = array("group_name"=>$group_name,"group_users"=>$group_users, "status"=>$status,"admin_id"=>$admin_id,"id"=>$id);    
		$result_data["result"]["data"] = $email_blasting->update_emailgroup($data);
} elseif($action == "delete_emailgroup"){     
		$data = array("id"=>$id,"admin_id"=>$admin_id);     
		$result_data["result"]["data"] = $email_blasting->delete_emailgroup($data);
} elseif($action == "ComposeBulkEmail"){     
		$data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"sender_id"=>$sender_id,"chat_message"=>$chat_message,"subject"=>$chat_sub);     
		$result_data["result"]["data"] = $email_blasting->ComposeBulkEmail($data);
} elseif($action == "get_emails_list"){     
		$result_data["result"]["data"] = $email_blasting->get_emails_list($admin_id);
}