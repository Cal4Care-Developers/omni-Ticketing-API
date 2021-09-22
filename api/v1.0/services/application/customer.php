<?php
$result_data["status"] = true;
$customer = new customers();

if($action == "customer_list"){
    $data= array("order_by_name"=>$order_by_name, "order_by_type"=>$order_by_type,"limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $customer->getPDCustomerList($data);
}
elseif($action == "add_customer"){
	
	$customer_data = array("customer_name"=>$customer_email,"phone_number"=>$phone_number,"customer_type"=>$customer_type,"customer_cat"=>$customer_cat,"alt_mobile_number"=>$alt_mobile_number, "queue_id"=>$queue_id,"address"=>$address,"city"=>$city,"state"=>$state,"country"=>$country,"zipcode"=>$zipcode, "created_ip"=>'',"created_by"=>$user_id,"updated_by"=>$user_id);
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $customer->createCustomer($customer_data);
}


?>