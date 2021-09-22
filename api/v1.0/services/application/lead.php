<?php

$result_data["status"] = true;
$lead = new lead();
if($action == "add_lead"){   
	$data = array("lead_token"=>$lead_token, "name"=>$name,"email"=>$email,"phone"=>$phone,"city"=>$city,"country"=>$country,"message"=>$message);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $lead->addLead($data);
}
if($action == "lead_list"){
        $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $lead->getAlllead($data);    
}
if($action == "get_singlelead"){
    $data = array("user_id"=>$user_id,"lead_id"=>$lead_id);
    $result_data["result"]["data"] = $lead->get_Singlelead($data);
}
if($action == "update_lead"){    
    $data = array("lead_id"=>$lead_id,"user_id"=>$user_id,"name"=>$name,"email"=>$email,"phone"=>$phone,"city"=>$city,"country"=>$country,"message"=>$message);    
    $result_data["result"]["data"] = $lead->updateLead($data);
}
if($action == "delete_lead"){     
    $data = array("lead_id"=>$lead_id,"user_id"=>$user_id);       
    $result_data["result"]["data"] = $lead->deleteLead($data);
}
if($action == 'lead_onchange'){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $lead->convert_contact($lead_id,$contact_option);   
}

?>