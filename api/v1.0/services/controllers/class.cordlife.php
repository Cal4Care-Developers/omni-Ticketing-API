<?php 
class cordlife extends restApi{
	function get_comp_det($data){
		//print_r($data);exit;
		  extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
    	 		$curl = curl_init();
					//echo $access;exit;
   						curl_setopt_array($curl, array(
        				CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/customer",
        				CURLOPT_RETURNTRANSFER => true,
        				CURLOPT_ENCODING => "",
        				CURLOPT_MAXREDIRS => 10,
        				CURLOPT_TIMEOUT => 0,
        				CURLOPT_FOLLOWLOCATION => true,
        				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        				CURLOPT_CUSTOMREQUEST => "POST",
        				CURLOPT_POSTFIELDS =>"$phone_no",
        				CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer $access",
            			"Content-Type: application/json"
        			),
    				));

    			$response_number = curl_exec($curl);
			    curl_close($curl);
   				echo $response_number;exit;
				 $campaign_options_array = array( $response_number=>'data');	
 				$status = array('status' => 'true');
	 			$merge_result = array_merge($status, $campaign_options_array);    
				$tarray = json_encode($merge_result,true);   
				print_r(stripslashes($tarray));exit;
	}
	
	function get_bank_det($data){
		//print_r($data);exit;
		extract($data);
		//$expl=explode('-',$cus_id);
      $access_num=$cus_id;
	$curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $res= curl_exec($curl);
          curl_close($curl);
          //echo $res;exit;
				  $array = json_decode($res, true);
   	   			  $access= $array['access_token'];	
		

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/CustomerContractBalance",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$cus_id\"",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: bearer $access"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
			
		
		
$curl = curl_init();
					//echo $access;exit;
   						curl_setopt_array($curl, array(
        				CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/customer",
        				CURLOPT_RETURNTRANSFER => true,
        				CURLOPT_ENCODING => "",
        				CURLOPT_MAXREDIRS => 10,
        				CURLOPT_TIMEOUT => 0,
        				CURLOPT_FOLLOWLOCATION => true,
        				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        				CURLOPT_CUSTOMREQUEST => "POST",
        				CURLOPT_POSTFIELDS =>"$phone_no",
        				CURLOPT_HTTPHEADER => array(
            			"Authorization: Bearer $access",
            			"Content-Type: application/json"
        			),
    				));

    			$response_number = curl_exec($curl);
			    curl_close($curl);
   				$arr = json_decode($response_number, true);
   	   			 $product= $arr['AccountResponseValue']['ContractProduct'];	
		 $test=array("main"=>$product);
				//$final['main']= json_encode($product);                
   				$fin= json_decode($response, true);
		 $tests=array("finance"=>$fin);
		$arrays=array_merge($test,$tests);
		 $xout=json_encode($arrays, JSON_PRETTY_PRINT);
		print_r($response);exit;
			
	}
function get_customer_list($data){
	//print_r($data);
	extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
	

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/CustomerList",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"PhoneNumber\" : \"$phone_no\",\r\n     \"NRIC\" : \"null\", \r\n     \"DateOfBirth\" : \"$dob\", \r\n     \"CustomerNumber\" : \"$cus_no\" \r\n }",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: bearer $access"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;exit;

}
function CustomerInfoByGuid($data){
	extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
	

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/CustomerInfoByGuid",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$accountGuid\"",
  CURLOPT_HTTPHEADER => array(
    "Content-Typ: application/json",
    "Authorization: Bearer $access",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;exit;

}
	function SalesDocumentByCustomer($data){
		  extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
		
		
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/SalesDocumentByCustomer",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$customer_no\"",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
   "Authorization: Bearer $access"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;exit;

	}
	
	function SalesDocumentByCustomerContract($data){
		  extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
		

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/SalesDocumentByCustomerContract",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\r\n\t\"CustomerNumber\": \"$customer_no\",\r\n\t\"ContractNumber\": \"$contract_no\"\r\n}\r\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: Bearer $access"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;exit;


	}
	
	function lab_tab($data){
		  extract($data);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $response = curl_exec($curl);
          curl_close($curl);
          //echo $response;exit;
				  $array = json_decode($response, true);
   	   			  $access= $array['access_token'];
		
		
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/UCBTestResult",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$contract_no\"",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $access",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
		 $ares = json_decode($response, true);
   	   			  $LabTabResponseStatus= $ares['LabTabResponseStatus'];
		if($LabTabResponseStatus=='200'){
			echo $response;exit;
		}elseif($LabTabResponseStatus=='201'){
			
			
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/UCLTestResult",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$contract_no\"",
  CURLOPT_HTTPHEADER => array(
   "Authorization: Bearer $access",
    "Content-Type: application/json"
  ),
));

$ucl = curl_exec($curl);

curl_close($curl);
			$ucl_de = json_decode($ucl, true);
   	   			  $ucl_stat= $ucl_de['LabTabResponseStatus'];
			if($ucl_stat=='200'){
echo $ucl;exit;
			}elseif($ucl_stat=='201'){
				
				
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/WJTestResult",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$contract_no\"",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $access",
    "Content-Type: application/json"
  ),
));

$wj = curl_exec($curl);

curl_close($curl);
echo $wj;exit;
				
			}
			
		}
		echo $response;exit;
//echo $LabTabResponseStatus;exit;


	}

function lab_track($data){
		//print_r($data);exit;
		extract($data);
		//$expl=explode('-',$cus_id);
      $access_num=$cus_id;
	$curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sguatapi.cordlife.com/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "username=N@mec%26lU@d%25APP0ftheU%24er&password=P@%24Ssw0rdfIVRt0en7er&grant_type=password",
            CURLOPT_HTTPHEADER => array(
              "Content-Type: application/x-www-form-urlencoded"
            ),
          ));

          $res= curl_exec($curl);
          curl_close($curl);
          //echo $res;exit;
				  $array = json_decode($res, true);
   	   			  $access= $array['access_token'];	
	
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://sguatapi.cordlife.com/ivr/ws/v1/LabTracking",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"\"$contract_no\"",
  CURLOPT_HTTPHEADER => array(
  "Authorization: Bearer $access",
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;exit;
}
}
