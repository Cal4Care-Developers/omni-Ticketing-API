<?php
$plans = new plans();
$result_data["status"] = true;

if($action == "insert_plans"){
    $data= array("plan_name"=>$plan_name,"plan_cost"=>$plan_cost,"plan_description"=>$plan_description, "has_chat"=>$has_chat, "has_chatbot"=>$has_chatbot, "has_contact"=>$has_contact, "has_external_contact"=>$has_external_contact,
        "has_external_ticket"=>$has_external_ticket, "has_fax"=>$has_fax,"has_fb"=>$has_fb,"has_internal_chat"=>$has_internal_chat,"has_internal_ticket"=>$has_internal_ticket,
        "has_sms"=>$has_sms,"has_telegram"=>$has_telegram,"has_wechat"=>$has_wechat,"lead"=>$lead,"has_whatsapp"=>$has_whatsapp,"predective_dialer"=>$predective_dialer
    ,"reports"=>$reports,"wallboard_four"=>$wallboard_four,"wallboard_one"=>$wallboard_one,"wallboard_three"=>$wallboard_three,"wallboard_two"=>$wallboard_two,"status"=>$status,"voice_3cx"=>$voice_3cx);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $plans->insert_plans($data);
    
}
if($action == 'view_plans'){
    // $data = array("admin_id"=>"$admin_id");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $plans->view_plans();
}
if($action == 'edit_plans'){
     $data = array("id"=>"$id");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $plans->edit_plans($data);
}
if($action == 'update_plans'){
     $data= array("plan_name"=>$plan_name,"plan_cost"=>$plan_cost,"plan_description"=>$plan_description, "has_chat"=>$has_chat, "has_chatbot"=>$has_chatbot, "has_contact"=>$has_contact, "has_external_contact"=>$has_external_contact,"has_external_ticket"=>$has_external_ticket,"has_fax"=>$has_fax,"has_fb"=>$has_fb,"has_internal_chat"=>$has_internal_chat,"has_internal_ticket"=>$has_internal_ticket,"has_sms"=>$has_sms,"has_telegram"=>$has_telegram,"has_wechat"=>$has_wechat,"lead"=>$lead,"has_whatsapp"=>$has_whatsapp,"predective_dialer"=>$predective_dialer,"reports"=>$reports,"wallboard_four"=>$wallboard_four,"wallboard_one"=>$wallboard_one,"wallboard_three"=>$wallboard_three,"wallboard_two"=>$wallboard_two,"id"=>$id,"status"=>$status,"voice_3cx"=>$voice_3cx);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $plans->update_plans($data);
}
if($action == 'delete_plans'){
    $data = array("id"=>"$id");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $plans->delete_plans($data);
}