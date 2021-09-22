<?php
$cordlife = new cordlife();
$result_data["status"] = true;

if($action == "get_comp_det"){
//echo '123';exit;
    $data= array("phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->get_comp_det($data);
    
}

if($action == "get_bank_det"){
//echo '123';exit;
    $data= array("cus_id"=>$cus_id,"phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->get_bank_det($data);
    
}
if($action == "get_customer_list"){
//echo '123';exit;
    $data= array("cus_no"=>$cus_no,"dob"=>$dob,"nric"=>$nric,"phone_no"=>$phone_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->get_customer_list($data);
    
}

if($action == "CustomerInfoByGuid"){
//echo '123';exit;
    $data= array("accountGuid"=>$accountGuid);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->CustomerInfoByGuid($data);
    
}
if($action == "SalesDocumentByCustomer"){
//echo '123';exit;
    $data= array("customer_no"=>$customer_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->SalesDocumentByCustomer($data);
    
}
if($action == "SalesDocumentByCustomerContract"){
//echo '123';exit;
    $data= array("customer_no"=>$customer_no,"contract_no"=>$contract_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->SalesDocumentByCustomerContract($data);
    
}
if($action == "lab_tab"){
//echo '123';exit;
    $data= array("contract_no"=>$contract_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->lab_tab($data);
    
}
if($action == "lab_track"){
//echo '123';exit;
    $data= array("contract_no"=>$contract_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $cordlife->lab_track($data);
    
}

