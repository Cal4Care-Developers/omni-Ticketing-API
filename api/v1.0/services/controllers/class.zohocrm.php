 
<?php
error_reporting(0);
class zohocrm extends restApi{

  public function clientList($admin_id){ 
	//extract($data);	  
	  
    $user_leadtoken_qry = "SELECT * FROM zoho_crmdata where admin_id='$admin_id'";
    $result = $this->dataFetchAll($user_leadtoken_qry,array());	  
    return $result;
  }
  public function clientAdd($data){
  	//print_r($data);exit;
  	extract($data);
	  
	 $user_leadtoken_qry = "SELECT * FROM zoho_crmdata where admin_id='$admin_id'";
     $result = $this->dataFetchAll($user_leadtoken_qry, array()); 						

	  if($result){
		 $id = $result[0]['id'];
			$qry = "UPDATE zoho_crmdata SET client_id='$client_id', client_secret_id='$client_secret_id',redirect_url='$redirect_url',client_fqdn='$client_fqdn' where id = $id";
			$update_data = $this->db_query($qry, $params);
	  } else {
	  
     $user_qry = "INSERT INTO zoho_crmdata(client_id,client_secret_id,redirect_url,client_fqdn,admin_id) values ('$client_id','$client_secret_id','$redirect_url','$client_fqdn','$admin_id')";
     //echo  $user_qry; exit;
     $result = $this->db_query($user_qry,array());
	  }
     $result = "SELECT * FROM zoho_crmdata ";
     $list_result = $this->dataFetchAll($result,array());
     //print_r($list_result);exit;
     return $list_result;
  }
  public function clientDlt($id){
  	//extract($data);
  	$client_qry = "DELETE FROM zoho_crmdata WHERE id = $id";
  	$result = $this->db_query($client_qry,array());
  	return $result;
  }
  public function clientUpdate($data){
  	extract($data);
  	$cli_qry = "UPDATE zoho_crmdata SET  client_id = '$client_id', client_secret_id = '$client_secret_id', redirect_url = '$redirect_url', admin_id = '$admin_id' WHERE id = $id";
    	$cli_var= $this->db_query($cli_qry,array());
    	$output = $cli_var == 1 ? 1 : 0; 
      $output = "SELECT * FROM zoho_crmdata ";
      $list_result = $this->dataFetchAll($output,array());
      //print_r($list_result);exit;
      return $list_result;


  }
    public function client_access_data($data){
      extract($data);


       $user_qry = "SELECT * FROM zoho_crmdata where admin_id='$admin_id'";
       $result = $this->dataFetchAll($user_qry,array());  
       $result = $result[0];
		$id = $result['id'];


 $cli_qry = "UPDATE zoho_crmdata SET  access_token = '$access_code', refresh_token = '$refresh_code' WHERE id = $id";
     $cli_var= $this->db_query($cli_qry,array());
     $output = $cli_var == 1 ? 1 : 0; 
     //print_r($cli_qry);exit;
     $output = "SELECT * FROM zoho_crmdata where id = $id";
     $list_result = $this->dataFetchAll($output,array());
      return $list_result;
  }
    
    public function client_refresh_data($data){
  extract($data);

    $user_qry = "SELECT * FROM zoho_crmdata where id='$id'";
       $result = $this->dataFetchAll($user_qry,array());  
       $result = $result[0];
       extract($result);

$url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret_id&grant_type=refresh_token";

  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $data,
  CURLOPT_HTTPHEADER => array(
    'Cookie: JSESSIONID=8632493A4DC684A0829683DEBF197B68; _zcsr_tmp=31dec81f-f129-43c4-bb12-77f75c0d95f6; b266a5bf57=a711b6da0e6cbadb5e254290f114a026; e188bc05fe=412d04ceb86ecaf57aa7a1d4903c681d; iamcsr=31dec81f-f129-43c4-bb12-77f75c0d95f6'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);
if($response->error){
     $error = 'Sorry Some Error Occured '. $response->error;
     return $error;
}
$access_token = $response->access_token;

    $cli_qry = "UPDATE zoho_crmdata SET access_token = '$access_token' WHERE id = $id";
     $cli_var= $this->db_query($cli_qry,array());
     $output = $cli_var == 1 ? 1 : 0; 
     $output = "SELECT * FROM zoho_crmdata ";
     $list_result = $this->dataFetchAll($output,array());
     //print_r($list_result);exit;
      return $list_result;
    }


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

 public function zohoRinging($data){
     extract($data);
     $selectqry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token= $this->fetchOne($selectqry,array());
     $rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$rand&from=$from&to=$to";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ('$rand','$type','$state','$from','$to','$crm_id')";
          $results = $this->db_query($user_qry,array());
          return $rand;
     }
     //return $result;
 } 
 
 public function zohoAnswering($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token = $this->fetchOne($qry,array());
	 //print_r($access_token);exit;
     //$rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to";
	 //print_r($queryParms);exit;
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ($type','$state','$from','$to','$crm_id')";
          $results = $this->db_query($user_qry,array());
     }
     return $result;
     

 }
 public function zohoEnded($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token= $this->fetchOne($qry,array());
     //$rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to&start_time=$starting_call&duration=$call_duration";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ($type','$state','$from','$to','$starting_call','$call_duration',$crm_id')";
          $results = $this->db_query($user_qry,array());
     }
     return $result;
     
 }

 public function zohoMissed($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token = $this->fetchOne($qry,array());
     //$rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to&start_time=$starting_call";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ('$type','$state','$from','$to','$starting_call','$crm_id')";
          $results = $this->db_query($user_qry,array());
     }
     return $result;    
}

 public function zoho_out_ring($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token = $this->fetchOne($qry,array());
     $rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$rand&from=$from&to=$to";
     //print_r($queryParms);exit;
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms );
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token ;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
	 //print_r($result);exit;
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
	 //print_r($result);exit;
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ('$rand','$type','$state','$from','$to','$crm_id')";
		 
          $results = $this->db_query($user_qry,array());
          return $rand;
     }
    

}
     public function zoho_out_answered($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token= $this->fetchOne($qry,array());
     //$rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
     if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ($type','$state','$from','$to','$crm_id')";
          $results = $this->db_query($user_qry,array());
          return $result;
     }


     }

     public function zoho_out_ended($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token= $this->fetchOne($qry,array());
     // $rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to&start_time=$starting_call&duration=$call_duration";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token ;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
     $result = json_decode($result);
     if($result->code == 'SUCCESS'){
     $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ('$type','$state','$from','$to','$starting_call','$call_duration','$crm_id')";
     $results = $this->db_query($user_qry,array());
     return $result;
     }
       }


     public function zoho_out_unattended($data){
     extract($data);
     $qry = "select access_token from zoho_crmdata where id='$crm_id'";
     $access_token = $this->fetchOne($qry,array());
     $rand = $this->generateRandomString();
     $queryParms = "type=$type&state=$state&id=$call_id&from=$from&to=$to&start_time=$starting_call";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/callnotify');
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $queryParms);
     $headers = array();
     $headers[] = 'Authorization: Zoho-oauthtoken '. $access_token;
     $headers[] = 'Content-Type: application/x-www-form-urlencoded';
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     $result = curl_exec($ch);
     if (curl_errno($ch)) {
         echo 'Error:' . curl_error($ch);
     }
     curl_close($ch);
      if($result->code == 'SUCCESS'){
          $user_qry = "INSERT INTO zohocall_info(call_id,call_type,call_state,call_from,call_to,crm_id) values ('$type','$state','$from','$to','$starting_call','$crm_id')";
          $results = $this->db_query($user_qry,array());
     }
     return $rand;

        }
        public function activateSSO($data){
          //print_r($_FILES);
      
          if (isset($_FILES['federation_metadata_xml']) && ($_FILES['federation_metadata_xml']['error'] == UPLOAD_ERR_OK)) {
            $xml = simplexml_load_file($_FILES['federation_metadata_xml']['tmp_name']);
            $json = json_encode($xml);
            $configData = json_decode($json, true);
            $entityID = $configData['@attributes']['entityID'];
            $SingleLogoutServiceBinding = $configData['IDPSSODescriptor']['SingleLogoutService']['@attributes']['Binding'];
            $SingleLogoutServiceLocation = $configData['IDPSSODescriptor']['SingleLogoutService']['@attributes']['Location'];
            $X509Certificate = $configData['IDPSSODescriptor']['KeyDescriptor']['KeyInfo']['X509Data']['X509Certificate'];
            $t =$_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/metadata/saml20-idp-remote.php';
            $t2 =$_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/metadata/saml202-idp-remote.php';
            $str = file_get_contents($t2);
            $rrr = $str;
            $str = str_replace('MetaentityID', $entityID ,$str);
            $str = str_replace('SingleLogoutServiceBinding', $SingleLogoutServiceBinding ,$str);
            $str = str_replace('SingleLogoutServiceLocation', $SingleLogoutServiceLocation ,$str);
            $str = str_replace('MSSSOX509Certificate', $X509Certificate ,$str);
            file_put_contents($t, $str);
          }
          
          
          extract($data);
          $authsources = $_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/config/authsourcesSSO.php';
          $strs = file_get_contents($authsources);
      
          $strs = str_replace('AssureEntityID', $sso_entity_id ,$strs);
          $strs = str_replace('AssureIDPVALUE', $azure_ad_id ,$strs);
          $authsourcess = $_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/config/authsources.php';
          file_put_contents($authsourcess, $strs);
          
      
          $user_leadtoken_qry = "SELECT * FROM ms_sso_authentication where admin_id='$admin_id'";
          $result = $this->dataFetchAll($user_leadtoken_qry, array()); 						
          //print_r($user_leadtoken_qry); exit;
          if($result){
            $id = $result[0]['id'];
            $qry = "UPDATE ms_sso_authentication SET sso_entity_id='$sso_entity_id', sso_reply_url='$sso_reply_url',azure_ad_id='$azure_ad_id',admin_id='$admin_id' where id = $id";
            $results = $this->db_query($qry, $params);
      
          } else {
            $user_qry = "INSERT INTO ms_sso_authentication(sso_entity_id,sso_reply_url,azure_ad_id,admin_id) values ('$sso_entity_id','$sso_reply_url','$azure_ad_id','$admin_id')";
            $results = $this->db_query($user_qry,array());
          }
          $user_leadtoken_qry = "SELECT * FROM ms_sso_authentication where admin_id='$admin_id'";
          $result = $this->dataFetchAll($user_leadtoken_qry, array()); 
      
          
          
      $f13c = file_get_contents($authsourcess); 
          $f13 = $authsourcess;
      
      $t =$_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/metadata/saml20-idp-remote.php';
          $fl2c = file_get_contents($t);
          $fl2 = $t;
        $sadfs = $_SERVER['DOCUMENT_ROOT'].'/ms-sso/simplesamlphp/config/authsources.php';
          $fl2cur = file_get_contents($sadfs);
          
          $result_data["result"]["status"] = true;
          $result_data["result"]["data"] = $result;
          $result_data["result"]["authsources"] = $f13;
          $result_data["result"]["authsourcescurr"] = $fl2cur;
          $result_data["result"]["authsourcesContent"] = $f13c;
          $result_data["result"]["saml20-idp-remote"] = $fl2;
          $result_data["result"]["saml20-idp-remoteold"] = $rrr;
          $result_data["result"]["saml20-idp-remoteContent"] = $fl2c;
          
          $result = json_encode($result_data);
          print_r($result); exit;
          return $result;
        }
        
 public function listSSO($admin_id){ 
	//extract($data);	  
    $user_leadtoken_qry = "SELECT * FROM ms_sso_authentication where admin_id='$admin_id'";
    $result = $this->dataFetchAll($user_leadtoken_qry,array());	  
    return $result;
  }
	
 public function listZohoUsers($admin_id){ 
	//extract($data);	  
    $user_leadtoken_qry = "SELECT access_token FROM zoho_crmdata where admin_id='$admin_id'";
    $access_token = $this->fetchOne($user_leadtoken_qry,array());
	 //echo $access_token;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.zohoapis.com/phonebridge/v3/users');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Authorization: Zoho-oauthtoken '.$access_token;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resu = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	//print_r($result); exit
	 $user_leadtoken_qry = "SELECT * FROM zoho_users_list where admin_id='$admin_id'";
    $results = $this->dataFetchAll($user_leadtoken_qry,array());	  

$result['inUsers'] = $results;
$result['zohoUsers'] = $resu;	 

    return $result;
  }
public function addZohoUsers($data){ 
	extract($data);	 
	$client_fqdn = "SELECT client_fqdn FROM zoho_crmdata where admin_id='$admin_id'";
    $clicktodial_url =$this->fetchOne($client_fqdn,array());
	$clicktodial_url = 'http://'.$clicktodial_url.'/api/value/';
	foreach($users as $user){
		$user_name = $user['username'];
		$extension = $user['extension'];
		$zoho_user_id = $user['zohouser'];
		$user_leadtoken_qry = "SELECT zoho_user_id FROM zoho_users_list where zoho_user_id='$zoho_user_id'";
    	$zoho_user_idOld = $this->fetchOne($user_leadtoken_qry,array());
		if($zoho_user_idOld){
			$qry = "UPDATE zoho_users_list SET extension='$extension' where zoho_user_id = $zoho_user_idOld";
			//echo $qry; exit;
			$update_data = $this->db_query($qry, $params);
		} else {
			$user_qry = "INSERT INTO zoho_users_list(user_name,extension,zoho_user_id,clicktodial_url,admin_id) values ('$user_name','$extension','$zoho_user_id','$clicktodial_url','$admin_id')";
        $results = $this->db_query($user_qry,array());
		}
	}
	return true;
  }	
	public function getExtensionUser($extension){ 
	  $user_leadtoken_qry = "SELECT * FROM zoho_users_list where extension='$extension'";
      $result = $this->dataFetchAll($user_leadtoken_qry,array());	  
      return $result;
	}
	public function addMSOmniUsers($data){ 
		extract($data);	 
		$omni_users = "SELECT id FROM ms_sso_authentication where admin_id='$admin_id'";
		$omni_Id =$this->fetchOne($omni_users,array());
		$qry = "UPDATE ms_sso_authentication SET omni_users='$users' where id = $omni_Id";
		$update_data = $this->db_query($qry, $params);
		return true;
	}	
	public function addMSTeamsUsers($data){ 
		extract($data);	 
		$omni_users = "SELECT id FROM ms_sso_authentication where admin_id='$admin_id'";
		$omni_Id =$this->fetchOne($omni_users,array());
		$qry = "UPDATE ms_sso_authentication SET teams_users='$users' where id = $omni_Id";
		$update_data = $this->db_query($qry, $params);
		return true;
	}
	public function ssoUsers($data){ 
		extract($data);	 
		$omni_users = "SELECT * FROM ms_sso_authentication where admin_id='$data'";
		$result = $this->fetchData($omni_users,array());
		$omni_users = $result['omni_users'];
		$omni_mainusers = $result['omni_users'];
		$omni_users = "SELECT user_id,agent_name,email_id,sip_login FROM user where user_id IN ($omni_users)";
		$omni_users = $this->dataFetchAll($omni_users,array());
		$teams_users = $result['teams_users'];
		$teams_main_users = $result['teams_users'];
		$teams_users = "SELECT user_id,agent_name,email_id,sip_login FROM user where user_id IN ($teams_users)";
		$teams_users = $this->dataFetchAll($teams_users,array());
		$datas['omni_users'] = $omni_users;
		$datas['omni_main_users'] = $omni_mainusers;
		$datas['teams_main_users'] = $teams_main_users;
		$datas['teams_users'] = $teams_users;
	
		return $datas;
	}
}