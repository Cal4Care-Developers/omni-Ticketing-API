<?php
$wp_pay = new wp_pay();
$result_data["status"] = true;
if($action == "init_trans"){
    $data= array("country_code"=>$country_code,"account_no"=>$account_no,"pin"=>$pin );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->init_trans($data);
	
}
if($action == "get_session"){
    $data= array("country_code"=>$country_code,"account_no"=>$account_no,"otp"=>$otp );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->get_session($data);
}
if($action == "b2w"){
    $data= array("session_id"=>$session_id,"amt"=>$amt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->b2w($data);
}
if($action == "w2b"){
    $data= array("session_id"=>$session_id,"amt"=>$amt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->w2b($data);
}
if($action == "start_chat"){
    $data= array("msg"=>$msg,"phone_no"=>$phone_no,"column_name"=>$column_name,"type"=>$type);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->start_chat($data);
}
if($action == "insert_pay"){
    $data= array("from_no"=>$from_no,"to_no"=>$to_no,"msg"=>$msg,"msg_id"=>$msg_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->insert_pay($data);
}
if($action == "add_bank_acc"){
    $data= array("name"=>$name,"country_code"=>$country_code,"phone_no"=>$phone_no,"account_no"=>$account_no, "pin"=>$pin, "mobile_money_provider"=>$mobile_money_provider, "balance"=>$balance);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->add_bank_acc($data);
}
if($action == "view_bank"){
   // $data= array("name"=>$name,"country_code"=>$country_code,"phone_no"=>$phone_no,"account_no"=>$account_no, "pin"=>$pin, "mobile_money_provider"=>$mobile_money_provider);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->view_bank();
}
if($action == "edit_bank"){
    $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->edit_bank($data);
}
if($action == "update_bank"){
    $data= array("name"=>$name,"country_code"=>$country_code,"phone_no"=>$phone_no,"account_no"=>$account_no, "pin"=>$pin, "mobile_money_provider"=>$mobile_money_provider, "id"=>$id, "balance"=>$balance);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->update_bank($data);
}
if($action == "get_chats"){
    $data= array("phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $wp_pay->get_chats($data);
}