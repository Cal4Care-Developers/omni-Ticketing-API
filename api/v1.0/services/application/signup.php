<?php
$signup = new signup();
$result_data["status"] = true;
if($_REQUEST['action'] == 'signup') {  
    $action ='signup'; 
	$name=$_REQUEST['name'];	
	$user_name=$_REQUEST['user_name'];	
	$user_password=$_REQUEST['user_password'];
	$admin_status=$_REQUEST['admin_status'];
	$company_name=$_REQUEST['company_name'];
	$plan_id=$_REQUEST['plan_id'];
	$address=$_REQUEST['address'];
	$email_id=$_REQUEST['email_id'];
}
if($action == "signup"){
	//echo '123';exit;
	$result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $signup->sign_up($name,$user_name,$user_password,$admin_status,$company_name,$plan_id,$address,$email_id); 
}
if($action == "signup_approval_list"){
	 //$data= array("type"=>$type,"admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"rep_format"=>$rep_format,"report_name"=>$report_name );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $signup->signup_approval_list();
}
if($action == "approve"){
	 $data= array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $signup->approve($data);
}