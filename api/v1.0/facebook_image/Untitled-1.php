<?php
 error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, PATCH, DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
header('Content-Type: text/plain; charset=utf-8');
$api_req = file_get_contents('php://input');
$api_request_data = json_decode($api_req,true);
extract($api_request_data);

if($api_request_data['apiFor'] === 'getContact'){
	
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/contacts?$select=firstname,lastname,_parentcustomerid_value,telephone1,telephone2,telephone3,fax,address2_telephone1,address2_telephone2,address2_telephone3,address2_fax,pager,emailaddress1&$filter=contains(telephone1,\''.$number.'\')%20',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$accessToken,
     'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);
	
$id = $response->value[0]->contactid;
$result_data["status"] = true;	
$result_data["id"] = $id;	
print_r(json_encode($result_data));
}
if($api_request_data['apiFor'] === 'getContact_omni'){
	$url="https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/contacts?\$filter=(telephone1 eq \''.$number.'\')";
	$url2='https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/contacts?$filter=contains(telephone1,\''.$number.'\')%20';
	//echo $url;exit;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url2,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$accessToken,
     'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6'
  ),
));

$response = curl_exec($curl);
curl_close($curl);	
//	echo $response;exit;
$response = json_decode($response);
$values = $response->value;
$result_data["status"] = true;	
$result_data["data"] = $values;	
print_r(json_encode($result_data));
}
if($api_request_data['apiFor']==='getAcesstoken'){
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://login.microsoftonline.com/3eda8495-cd20-4cf1-8d32-dc5ba0b065bd/oauth2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=9347710f-9c5b-4fc5-b05a-760c024bb7d9&client_secret=T094_697ZkfYfPktDk8VX~3j.-vj5y._UJ&resource=https%3A%2F%2Forg017de4ab.api.crm5.dynamics.com',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: esctx=AQABAAAAAAD--DLA3VO7QrddgJg7Wevr2toE0ORD0aJMf6EfGZLVvd_NkCR6GODFttqiI66B7W0K9Cm1Ec6SJPIUZqMnr7Vp6FRdCZKGwRIU_GvGbKhHyroRj3dH0S4wr8eibwX6zBxXTU5eY8p_0fSI12UfVT2PDO9VbhEOJGu3Hb2BvG-UlpRptfaEfmpJ5S6t4CwgdLEgAA; x-ms-gateway-slice=estsfd; stsservicecookie=estsfd; fpc=AuMSaABN49REgEPYPEfVjjmM2ZcJBQAAAGz9ANgOAAAA'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
	//print_r($response);exit;
$response = json_decode($response);
$access_tocken = $response->access_token;
$result_data["status"] = true;	
$result_data["access_token"] = $access_tocken;	
print_r(json_encode($result_data));
}
if($api_request_data['apiFor'] === 'getsystemusers'){

$curl = curl_init();

curl_setopt_array($curl, array(
 CURLOPT_URL => 'https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/systemusers?$select=systemuserid,fullname&$filter=isdisabled%20eq%20false%20and%20(new_extension%20eq%20\''.$number.'\')',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$accessToken,
    'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6' 
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);
$id = $response->value[0]->systemuserid;
$result_data["status"] = true;	
$result_data["systemuserid"] = $id;	
print_r(json_encode($result_data));
}
if($api_request_data['apiFor'] === 'addActivities'){

$dfs = '{
			"subject": "'.$subject.'",
			"phonenumber": "'.$callNum.'",
			"description": "'.$description.'",
			"directioncode": true,
			"ownerid@odata.bind": "/systemusers('.$systemUser.')",
			"regardingobjectid_contact@odata.bind": "'.$contct.'"
			}';

	
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/phonecalls',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$dfs,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$accessToken,
    'Content-Type: application/json',
     'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

}
if($api_request_data['apiFor'] === 'AddNewContact'){

  $dfs = '{
    "firstname": "'.$F_Name.'",
    "lastname": "'.$L_Name.'",
    "telephone1": "'.$Num.'",     
        }';
  
    
    
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/contacts',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$dfs,
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.$accessToken,
      'Prefer: return=representation',
      'Content-Type: application/json',
      'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6',
     
    )
  ));
 
$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);
$id = $response->contactid;
$result_data["status"] = true;	
$result_data["id"] = $id;	
print_r(json_encode($result_data));
  }
if($api_request_data['apiFor'] === 'getPhoneCallActivity'){
	
 // $url="https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/phonecalls?\$filter=contains(phonenumber,'$number')";
  //$url2="https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/phonecalls?\$filter=(phonenumber eq \''.$number.'\')&\$orderby=modifiedon desc";
//	https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/phonecalls?$filter=(phonenumber eq '63401005')&$orderby=modifiedon desc
	$url = 'https://org017de4ab.api.crm5.dynamics.com/api/data/v8.2/phonecalls?$filter=(phonenumber%20eq%20\''.$number.'\')&$orderby=modifiedon%20desc';
	//echo $url;exit;
 $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING =>'',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$accessToken,
     'Cookie: ARRAffinity=d75cfbc840006c9c55cc70b0800790f7873647effecccd1743c94791ce4f59a9; ReqClientId=c257413c-047a-4f92-90c7-4c1c295dd991; orgId=5cff0b57-159d-45ee-aea4-27fbd7b84ee6'
  ),
));

 
$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);
	//print_r($response);exit;
$values = $response->value;
$result_data["status"] = true;	
$result_data["data"] = $values;	
print_r(json_encode($result_data));
  }

?>
	