<?php

//echo 'daa'; exit;
require_once 'lib/_autoload.php';
$as = new SimpleSAML_Auth_Simple('default-sp');
$as->requireAuth();

$attributes = $as->getAttributes();


$url = $as->getLogoutURL();

$emial = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'];
$emial = implode(',', $emial);
$element_data = array('logout' => $url,'email'=>$emial);

echo '<a href="' . htmlspecialchars($url) . '">Logout</a>'; 
if($_GET['logout']){
	//echo $_GET['logout'];
	$u = $_GET['logout'];	
	ob_start();
   	header('Location: '.$u);
    ob_end_flush();
    die();
	exit();
}



if($element_data){
	
	$urls = 'https://omnitickets.mconnectapps.com/api/v1.0/index.php';
	$element = '{"operation":"agents", "moduleType": "agents", "api_type": "web","element_data":{"action":"mss_sso","logout":"logout","email":"'.$emial.'"}}';
	//print_r($element); exit;
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $urls);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $element);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == 'Not an Valid User'){
		$u = "https://omnitickets.mconnectapps.com/#/login?login=$result";
		ob_start();
		header('Location: '.$u);
		ob_end_flush();
		die();
	} else {
		$login = $result;
		$login = base64_encode($login);
		$u = "https://omnitickets.mconnectapps.com/#/login?login=$login";
		ob_start();
		header('Location: '.$u);
		ob_end_flush();
		die();
	}

	exit();
}
