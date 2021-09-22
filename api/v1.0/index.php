<?php
//echo '1234';exit;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
header('Content-Type: text/plain; charset=utf-8');
require_once 'main.controller.php';
$admin = new adminData();
$server_data = $_SERVER;
$api_req = file_get_contents('php://input');
//print_r($api_req);exit;
//$api_req = str_replace("\r\n", '\r\n', $api_req); 
$api_request_data = json_decode($api_req,true);
//print_r($api_request_data);exit;
$result_data["status"] = false;
if(isset($api_request_data["moduleType"]) && $api_request_data["moduleType"] != ""){

	$moduleType = $api_request_data["moduleType"];
	$operation = $api_request_data["operation"];
	$api_type = $api_request_data["api_type"];
	$token_key = $api_request_data["access_token"];
	$exception_list = $admin->tokenExceptionList($moduleType, $operation);
	//print_r($exception_list);exit;
	$access_token = "";
	if($token_key != ""){
	
		$token_validation = $admin->tokenValidation($token_key);
		$access_token = $token_validation["new_token"]; 
		$token_access_data = $token_validation["access_data"]; 
		if($token_access_data > 0){
			extract($token_access_data);
		}
		
	}
	 
	if($exception_list == 1){	
		
		include_once 'app_redirect.php';
	}
	else{

		if($access_token != ""){
		
			include_once 'app_redirect.php';
		}
		else{
			$result_data["error"]["code"] = "ACCESS_DENIED";
			$result_data["error"]["message"] = "Invalid token"; 

		}
 
	}


}

else{
	$result_data["error"]["code"] = "ACCESS_DENIED";
	$result_data["error"]["message"] = "Invalid credentials1."; 

}
$admin->errorLog("data1", $api_req);
echo json_encode($result_data);


?>