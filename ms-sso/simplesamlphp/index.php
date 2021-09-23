<?php
	//header('Location: https://omnitickets.mconnectapps.com/'); exit;
require_once 'lib/_autoload.php';
$as = new SimpleSAML_Auth_Simple('default-sp');
$element_data = array('logout' => $url,'email'=>$emial);
$as->requireAuth();
$attributes = $as->getAttributes();
$url = $as->getLogoutURL();
$emial = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'];
$emial = implode(',', $emial);
//header('Location: https://omnitickets.mconnectapps.com/'); exit;
//echo '<a href="' . htmlspecialchars($url) . '">Logout</a>'; 
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
	$element = '{"operation":"agents", "moduleType": "agents", "api_type": "web","element_data":{"action":"mss_sso_teams","logout":"logout","email":"'.$emial.'"}}';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $urls);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $element);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == 'Not an Valid User'){
		$u = "https://omnitickets.mconnectapps.com/ms-sso/index.php?login=$result";
		ob_start();
		header('Location: '.$u);
		ob_end_flush();
		die();
	} else {
		$login = $result;
		$_SESSION["login"] = $login;
		//$u = "https://omnitickets.mconnectapps.com/mconnectDialer/?email=$emial&url=$url&login=$login";
		// $u = "https://omnitickets.mconnectapps.com/mconnectDialer/?login=$login";
		$u = "https://omnitickets.mconnectapps.com/ms-sso/call-details.php?email=$emial&url=$url&login=$login";
		ob_start();
		header('Location: '.$u);
		ob_end_flush();
		die();
	}
	
	
	

	exit();
}
