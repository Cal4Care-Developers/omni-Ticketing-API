<?php

$result_data["status"] = true;
$template = new template();


if($action == "add_template"){    
    $data = array("template_message"=>$template_message,"queue_no"=>$queue_no,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->addtemplate($data);
}
if($action == "update_template"){
    $data = array("template_message"=>$template_message,"queue_no"=>$queue_no,"admin_id"=>$admin_id,"template_id"=>$template_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->updateTemplate($data);
}
if($action == "edit_template"){
	$data = array("admin_id"=>$admin_id,"template_id"=>$template_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->gettemplate_data($data);
}
if($action == "get_template"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->gettemplate($admin_id);
}
if($action == "send_message"){     
    $data = array("hardware_id"=>$hardware_id,"queue_no"=>$queue_no,"mobile_num"=>$mobile_num);     
    $result_data["result"]["data"] = $template->send_message($data);
}
if($action == "delete_template"){     
    $data = array("template_id"=>$template_id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $template->delete_template($data);
}



if($action == "addSmsTemplate"){    
    $data = array("template_content"=>$template_content,"template_name"=>$template_name,"department"=>$department,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->addSmsTemplate($data);
}
if($action == "updateSmsTemplate"){
    $data = array("template_content"=>$template_content,"template_name"=>$template_name,"department"=>$department,"template_id"=>$template_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->updateSmsTemplate($data);
}

if($action == "listTemplate"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->listTemplate($admin_id);
}
if($action == "listTemplateExternal"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->listTemplateExternal($login);
}

if($action == "listTemplateByUSer"){
	$data = array("admin_id"=>$admin_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->listTemplateByUSer($data);
}
if($action == "getSingleTemplateData"){
	$data = array("admin_id"=>$admin_id,"template_id"=>$template_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $template->getSingleTemplateData($data);
}
if($action == "deleteTemplate"){     
    $result_data["result"]["status"] = true;   
    $result_data["result"]["data"] = $template->deleteTemplate($template_id);
}