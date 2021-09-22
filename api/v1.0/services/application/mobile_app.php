<?php
if($_REQUEST['element_data']['action']){ $action = $_REQUEST['element_data']['action'];}
$call = new call();
if($action == "checkForValidQR"){
	$ext = $_REQUEST['element_data']['extension'];
	$authName = $_REQUEST['element_data']['authName'];	
	$authPass = $_REQUEST['element_data']['authPass'];	
	$data= array("extension"=>$ext, "authPass"=>$authPass, "authName"=>$authName);
	//$data= array("extension"=>"605", "authPass"=>"asXYuz8lmv", "authName"=>"N0i8Oi0W8T");
	$result_data["result"]["status"] = true;
	$result_data["result"]["data"] = $call->checkForValidQR($data);
}