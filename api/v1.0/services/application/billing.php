<?php
$billing = new billing();
$result_data["status"] = true;

if($action == "insert_billing_det"){
    $data= array("biller_code"=>$biller_code, "enq_code"=>$enq_code, "biller_name"=>$biller_name, "address"=>$address, "city"=>$city, "state"=>$state, "country"=>$country,"zip_code"=>$zip_code,"phone"=>$phone,"email"=>$email,"fax"=>$fax,"tax_per"=>$tax_per,"tax_name"=>$tax_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->insert_billing_det($data);
    
}
if($action == 'view_billing_det'){
	// $data = array("admin_id"=>"$admin_id");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->view_billing_det();
}
if($action == 'edit_billing_det'){
     $data = array("id"=>"$id");
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->edit_billing_det($data);
}
if($action == 'update_billing_det'){
$data= array("id"=>$id,"biller_code"=>$biller_code, "enq_code"=>$enq_code, "biller_name"=>$biller_name, "address"=>$address, "city"=>$city, "state"=>$state, "country"=>$country,"zip_code"=>$zip_code,"phone"=>$phone,"email"=>$email,"fax"=>$fax,"tax_per"=>$tax_per,"tax_name"=>$tax_name);
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->update_billing_det($data);
}
if($action == 'check_transaction_banking_charge'){
	 $data = array("api_user"=>"Cal4careCms", "api_pass"=>"16c21a5c08758dc10dadb11b7c8cc182", "access"=>"omni_site", "page_access"=>"view_page","action_info"=>"check_transaction_banking_charge", "currency"=>$currency_name,"selected_rdo"=>$selected_rdo,'total_amount'=>$total_amount);
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->curlData($data);
}

if($action == 'add_to_cart'){
	 $data = array("admin_id"=>"$admin_id", "item_name"=>"$item_name", "price"=>"$price", "quantity"=>"$quantity","tot_amt"=>"$tot_amt");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->add_to_cart($data);
}

if($action == 'view_cart'){
	 $data = array("admin_id"=>"$admin_id");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->view_cart($data);
}

if($action == 'delete_from_cart'){
	 $data = array("id"=>"$id");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->delete_from_cart($data);
}

if($action == 'add_billing_address'){
	 $data = array("cmp_name"=>"$cmp_name", "contact_person"=>"$contact_person", "phone_no"=>"$phone_no", "email"=>"$email", "alt_phone"=>"$alt_phone" ,"alt_email"=>"$alt_email", "address1"=>"$address1", "address2"=>"$address2", "contact_person"=>"$contact_person","city"=>"$city","state"=>"$state","country"=>"$country","admin_id"=>"$admin_id");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->add_billing_address($data);
}

if($action == 'edit_cart'){
	 $data = array("id"=>"$id","admin_id"=>"$admin_id");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->edit_cart($data);
}

if($action == 'update_cart'){
	 $data = array("id"=>"$id","admin_id"=>"$admin_id","price"=>"$price");
	 $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $billing->update_cart($data);
}
