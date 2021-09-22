<html>
<head>
</head>
<body>
<?php
 session_start();
	
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
 $inc_wp_response = file_get_contents('php://input',true);
 $log = file_put_contents('wb_bot.json', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
  
$inc_wp_response = explode('&',$inc_wp_response);
        $sid    ="AC298d93d2c28ea6c84b0f879d19ec84b2";
       $token  = "9dfc98c29345917dad785b58a26a3095";
       $twilio = new Client($sid, $token);

	
function insert($from,$to,$msg,$msg_id){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"insert_pay\",\r\n        \"from_no\":\"$from\",\r\n        \"to_no\":\"$to\",\r\n        \"msg\":\"$msg\",\r\n        \"msg_id\":\"$msg_id\"\r\n        \r\n    }\r\n}\r\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
	//$log=wh_log($response);
}

	function insert_reply($from,$to,$msg,$msg_id){
	//return $msg_id;exit;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"insert_pay\",\r\n        \"from_no\":\"$from\",\r\n        \"to_no\":\"$to\",\r\n        \"msg\":\"$msg\",\r\n        \"msg_id\":\"$msg_id\"\r\n        \r\n    }\r\n}\r\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
//$log=wh_log($response);
	$result = json_decode($response, true);
	//$result=$result['result']['data'];
return $result;

}
function insert_temp($temp_msg,$phone,$col_name,$type){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"start_chat\",\r\n        \"msg\":\"$temp_msg\",\r\n        \"phone_no\":\"$phone\",\r\n        \"column_name\":\"$col_name\",\r\n        \"type\":\"$type\"\r\n        \r\n    }\r\n}\r\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
	$response = curl_exec($curl);
	curl_close($curl);
	//$log=wh_log($response);
	$result = json_decode($response, true);
	$result=$result['result']['data'];
	return $result;
	
}	
$inc_message = str_replace("Body=","",$inc_wp_response[4]);
$inc_message = str_replace("+"," ",$inc_message);
$inc_message = urldecode($inc_message);
$inc_message = str_replace("%0","\n",$inc_message);
$from_num = str_replace("From=whatsapp%3A%2B","+",$inc_wp_response[9]);
$to_num = str_replace("To=whatsapp%3A%2B","+",$inc_wp_response[5]);
$message_id = str_replace("MessageSid=","",$inc_wp_response[7]);

/*if(!isset(session_start()){
  $message = $twilio->messages->create("whatsapp:$from_num",[
                               "from" => "whatsapp:$to_num",
                               "body" => "Please type 'hi' to initiate "]);
}*/

/*	if(isset($_SESSION['temp']){
		$_SESSION['start']=time();
	  $_SESSION['expire']=$_SESSION['start']+ (1*60);
		if($_SESSION['start'] > $_SESSION['expire']){
			//$_SESSION['temp']==true;
		
		session_destroy();
			$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "Your Session is expired"]);
		}
	}*/
	
      if($inc_message =='transfer'||$inc_message =='banking'||$inc_message =='Banking'||$inc_message =='BANKING'||$inc_message =='Transfer'||$inc_message =='TRANSFER')
	  {
		 	
		  $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		  $body="Welcome to OmniChannles banking,Please Enter your Country Code to Initialise your Transaction";
		  $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
          $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"
                           ]);
		  $temp_data=insert_temp($inc_message,$from_num,'','Insert');
		  $_SESSION["temp"] = 'countrycode';
	  }
	
	elseif($inc_message=='cancel'||$inc_message=='Cancel'||$inc_message=='CANCEL'||$inc_message=='CanceL'){
		session_destroy();
	 $body="Your transaction is terminated.Type 'banking' to initiate your bank transaction. If you need to terminate the transaction inbetween type 'cancel'.Please follow the flow me with my question";
		  $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
          $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
		 $_SESSION["temp"] = 'cancel';
  session_destroy();

	}
	
	elseif($_SESSION["temp"]=='countrycode'){
		  
		    $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		    
			$temp_data = insert_temp($inc_message,$from_num,'country_code','Update');
		 if(is_numeric($inc_message) && $temp_data == 1){
			 $body="Please enter your Account Number";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
	                           $message = $twilio->messages->create("whatsapp:$from_num",[
                               "from" => "whatsapp:$to_num",
                               "body" => "$body"]);
				 $_SESSION["temp"] = 'accountnumber';
				
			  }
			 else{
				 $body="Enter Valid Coutry Code";
		         $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
				 
			  $message = $twilio->messages->create("whatsapp:$from_num", // to
                           [ "from" => "whatsapp:$to_num",
                             "body" => "Enter Valid Coutry Code"
                           ]);
				 $_SESSION["temp"]='countrycode';
			//session_destroy();
			//$_SESSION["temp"]='destroy';
		}
			
	    }
	elseif($_SESSION["temp"]=='accountnumber'){
		
		 $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		 $temp_data = insert_temp($inc_message,$from_num,'account_no','Update');
		if(is_numeric($inc_message)&&strlen($inc_message)==10)
		{
			 $body="Ok Good,Your Account Number is $inc_message. Next Enter your 4 digits Account PIN Number";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
			
			
	                       $message = $twilio->messages->create("whatsapp:$from_num", // to
                           [ "from" => "whatsapp:$to_num",
                             "body" => "$body"
                           ]);
		$_SESSION["temp"]='pin';
	}
		else{
			 $body="Enter Valid Account number.It should be 10 digits";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
			  $message = $twilio->messages->create("whatsapp:$from_num", // to
                           [ "from" => "whatsapp:$to_num",
                             "body" => "Enter Valid Account number. It should be 10 digits."
                           ]);
			
			$_SESSION["temp"]='accountnumber';
		}
		
	}
		
elseif($_SESSION["temp"]=='pin'){
	 $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
	 $temp_data = insert_temp($inc_message,$from_num,'pin_no','Update');
	
	if(is_numeric($inc_message)&&$temp_data==1){
		     $body="At last, enter the OTP sent to your regesitered mobile number";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
	                       $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"
                           ]); 
		$_SESSION["temp"]='getotp';
	}else{
		     $body="Sorry,You Entered Wrong credentials.Please type Valid PIN";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
		$_SESSION["temp"]='pin';
	   }
	
}
	elseif($_SESSION["temp"]=='getotp'){
		 $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		 $temp_data = insert_temp($inc_message,$from_num,'otp','Update');
		if($temp_data!=2){
			  $body="Your account has been successfully registered. Happy banking! Type “1” for Bank to Wallet Transfer. Type “2” for Wallet to Bank Transfer. ";
		      $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		      $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
		$_SESSION["session_trans"]=$temp_data;
	    $_SESSION["temp"]='transtype';
	}
		else{
			 $body="Sorry,You Entered Wrong credential.Please try Again.Type a valid OTP.";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
		$_SESSION["temp"]='getotp';
		}
	}
	
	elseif($_SESSION["temp"]=='transtype'){
		 $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		$session_id=$_SESSION["session_trans"];
		if($inc_message==1){
			 $body="Please enter the exact sum required to be added in your wallet.(Your Amount should be consider as Dollers)";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
			$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
			$_SESSION["temp"]='b2w';
		}
		elseif($inc_message==2){
			 $body="Please enter the exact sum required to be added in your Bank(Your Amount should be consider as Dollers)";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
			$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
			$_SESSION["temp"]='w2b';
		}
		else{
			 $body="Please Enter your type of transaction.Type '1' for Bank to Wallet or Type '2' for Wallet to bank Transaction";
		     $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
			$message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body"]);
			
	        $_SESSION["temp"]='transtype';
		}
		
	}
	
	elseif($_SESSION["temp"]=='b2w'){
		 $ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		$session_id=$_SESSION["session_trans"];
		if(is_numeric($inc_message)){
				$res=b2w($session_id,$inc_message);
			if($res!=''){
				 $body="ok,$$inc_message is successfully added in your Wallet!. You have $res in your Omnichannel Wallet ";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
				$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
				$_SESSION['temp']='completed';
				$body2="Thankyou for using OmniChannels bot. Please connect with us. If you need start banking again, type 'transfer'.";
		  $ins_reply_data2 = insert_reply2($to_num,$from_num,$body2,$message_id);
          $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body2"]);
				session_destroy();
			}
			else{
				$body="Oops!,Your Transaction has been failure.Please try again";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
				$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
				$_SESSION['temp']='true';
				session_destroy();
			}
		}
		else{
			    $body="Oops!,Please Enter valid Currency";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
			$_SESSION["temp"]='b2w';
		}
	}
	
	elseif($_SESSION["temp"]=='w2b'){
		$ins_data = insert($from_num,$to_num,$inc_message,$message_id);
		$session_id=$_SESSION["session_trans"];
		if(is_numeric($inc_message)){
			  
				$res=w2b($session_id,$inc_message);
			    $result = json_decode($res, true);
			    $fail=$result['result']['data'];
			    $data=$result['result']['data']['session_id'];
	            $balance=$result['result']['data']['balance'];
			   //if($balance!=''&&$result!="2"){
				if($data!=''){
				 $body="$$inc_message is successfully transfered to your Bank.Your Wallet Balance is $balance.";
		        $ins_reply_data = insert_reply2($to_num,$from_num,$body,$message_id);
				$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
				$_SESSION['temp']='completed';
				//$_SESSION["temp"]='w2b';
				$body2="You are a valuable customer! Thanks for using Omni Channel Wallet. Be connected! To start another transfer type ‘transfer’";
		  $ins_reply_data2 = insert_reply($to_num,$from_num,$body2,$message_id);
          $message = $twilio->messages->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                            "body" => "$body2"]);
					session_destroy();
			}
			elseif($data=='' && $fail!=''){
				 $body="You did not have enough amount in your wallet.Your Wallet Balance is $balance.Please Enter it Again";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
				$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
				//$_SESSION['temp']='completed';
				$_SESSION["temp"]='w2b'; 

			}
			else{
				 $body="oops!,Your Transaction has been failure.Please try again";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
				$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
				$_SESSION['temp']='cancel';
				session_destroy();
			}
		}
		else{
			 $body="Please Enter valid Currency";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		$message = $twilio->messages->create("whatsapp:$from_num", // to
								   ["from" => "whatsapp:$to_num",
									"body" => "$body"]);
			$_SESSION["temp"]='w2b';
		}
	}
	
	elseif((!isset($_SESSION['temp']))||$inc_message=='Hi'||$inc_message =='hi'||$inc_message =='Hello'||$inc_message =='hello'||$inc_message =='Hi Cal4care'||$inc_message =='hi cal4care'||$inc_message =='Hi omni'||$inc_message =='Hi Omnichannel'){
		
		$body="Hi,This is the WhatsApp Pay Bot from OmniChannels.Type 'banking' to initiate your bank transaction. If you need to terminate the transaction inbetween type 'cancel'.";
	    $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
		$message = $twilio->messages->create("whatsapp:$from_num",[
                               "from" => "whatsapp:$to_num",
                               "body" => "$body"]);
return false;	
}
	
	else{
			 $body="Sorry,Please follow the flow.I can't Understand";
		        $ins_reply_data = insert_reply($to_num,$from_num,$body,$message_id);
          $message = $twilio->messages
                  ->create("whatsapp:$from_num", // to
                           ["from" => "whatsapp:$to_num",
                               "body" => "$body"]);   
			//$_SESSION['temp']='cancel';
			//$_SESSION['cancel']='true';
			session_destroy();
       }
	   $wp_msg_id = $message->sid;
	


	
	
//}

function b2w($session_id,$amount){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"b2w\",\r\n        \"session_id\":\"$session_id\",\r\n        \"amt\":\"$amount\"\r\n        \r\n    }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
	//$log=wh_log($response);

    $result = json_decode($response, true);
	$result=$result['result']['data']['balance'];
	return $result;
}
	
function w2b($session,$amount){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"w2b\",\r\n        \"session_id\":\"$session\",\r\n        \"amt\":\"$amount\"\r\n        \r\n    }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
	//$log= wh_log($response);
  // $result = json_decode($response, true);
	//$result=$result['result']['data']['balance'];
	//return $result;
return $response;
	
}
	
	
	
	function insert_reply2($from,$to,$msg,$msg_id){
	//return $msg_id;exit;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://devomni.mconnectapps.com/api/v1.0/index.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{ \r\n    \"operation\": \"wp_pay\",\r\n    \"moduleType\": \"wp_pay\",\r\n    \"api_type\": \"web\",\r\n    \"element_data\": {\r\n        \"action\": \"insert_pay\",\r\n        \"from_no\":\"$from\",\r\n        \"to_no\":\"$to\",\r\n        \"msg\":\"$msg\",\r\n        \"msg_id\":\"$msg_id\"\r\n        \r\n    }\r\n}\r\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
//$log= wh_log($response);
curl_close($curl);
	//$result = json_decode($response, true);
	//$result=$result['result']['data'];
return $response;

}

	
function wh_log($log_msg)
{
	//return $log_msg;exit;
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {        mkdir($log_filename, 0777, true);    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}	

	
?>


