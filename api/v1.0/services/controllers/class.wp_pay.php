<?php
class wp_pay extends restApi{

  function init_trans($data){	 
	   extract($data);
     $qry = "SELECT * FROM wp_pay WHERE  account_no='$account_no' and pin='$pin'";
     $values= $this->fetchData($qry, array());
	 $country_code= $values['country_code'];		
	 $phone_no= $values['phone_no'];
	  
	  if($values!=''){
	 $result= $this->send_otp($country_code, $phone_no);
	  }
	  else{
		  $result='2';
	  }
	
 $response="TO Initiate Transaction 
 {
    \"status\" : true,
    \"result\": {
        \"status\": true,
        \"data\": \"$result\";
	}
}";
	  $log= $this->wh_log($response);
	  return $result;
    }
 public function send_otp($country_code, $phone_no){
	  			$otp = rand(0, 9999);                         
                $postData = array(
                                      'user' => 'cal4care',
                                      'password' => 'Godaddy123',
                                      'phonenumber' => "+".$country_code.$phone_no,
                                      'text' => $otp,
                                      'gsm_sender' => 'BzzSMS',
                                      'cdma_sender' => '82986675',
                                      'action' => 'send'
                                   );    
                $url="http://bzzsms.com/sendsms.php";
                $ch = curl_init();
                curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                ));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
                $output = curl_exec($ch);
                //print_r($output);exit;
                if(curl_errno($ch)){
                  throw new Exception(curl_error($ch));
                }
                curl_close($ch);
                if($output == '1: Message sent successfully @  '){
                  $updateqry1 = "UPDATE wp_pay SET `otp` = $otp WHERE phone_no='$phone_no'";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);
                 // $result = array(1);
                 // $result_array = json_encode($result);           
                 return '1';
                }else{
                  $result = array(0);
                  $result_array = json_encode($result);           
                 return '0';
                }
	   }
function get_session($data){
	 extract($data);
      $qry = "SELECT * FROM `wp_pay` where account_no='$account_no' and otp='$otp'";
     $res= $this->fetchData($qry, array());
	if($res!='')
	{
$issued_date = time();
$hash = md5($account_no.$issued_date. substr(sha1(mt_rand()), 0, 22));

    for ($i = 0; $i < 1000; $i++) {
        $hash = md5($hash);
    }

    $serial= implode('-', str_split(substr($hash, 0, 25), 5));
		 $updateqry1 = "UPDATE wp_pay SET `session_id` = '$serial' where account_no='$account_no' and otp='$otp'";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);
		
	}else{
		$serial= '2';
	}
	 $response="Creating Session 
	{
    \"status\" : true,
    \"result\": {
        \"status\": true,
        \"data\": \"$serial\";
	}
}";
	  $log= $this->wh_log($response);
	return $serial;
	
}
	function b2w($data){
		extract($data);
		 $qry = "SELECT balance FROM `wp_pay` where session_id='$session_id'";
     $res= $this->fetchData($qry, array());
		if($res!=''){
				 $wallet_ref_no= $this->randomNumber('12');
			$balance=$res['balance']+$amt;
		 $updateqry1 = "UPDATE wp_pay SET `balance` = '$balance',wallet_ref_no='$wallet_ref_no' where session_id='$session_id' ";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);	
		}
		
		  $qry2 = "SELECT session_id,wallet_ref_no,mobile_money_provider,balance FROM `wp_pay` where session_id='$session_id'";
    	 $res2= $this->fetchData($qry2, array());
		$session_id=$res2['session_id'];
	$wallet_ref_no=$res2['wallet_ref_no'];
	$mobile_money_provider=$res2['mobile_money_provider'];
	$balance=$res2['balance'];
			$response="Bank to Wallet Transfer
			{
    \"status\": true,
    \"result\": {
        \"status\": true,
        \"data\": {
            \"session_id\": \"$session_id\",
            \"wallet_ref_no\": \"$wallet_ref_no\",
            \"mobile_money_provider\": \"$mobile_money_provider\",
            \"balance\": \"$balance\"
        }
    }
}";
	 $log= $this->wh_log($response);
		 return $res2;
	}
function w2b($data){
		extract($data);
		 $qry = "SELECT balance FROM `wp_pay` where session_id='$session_id'";
     $res= $this->fetchData($qry, array());
		if($res!=''){
			$balance=$res['balance']-$amt;
			if($balance<0){
				 $result['balance']=$balance=$res['balance'];
				
				$response="Wallet to Bank Transfer
				{
    \"status\": true,
    \"result\": {
        \"status\": true,
        \"data\": {
            \"balance\": \"$balance\"
        }
    }
}";
				 $log= $this->wh_log($response);
				return $result;
			}
			 $wallet_ref_no= $this->randomNumber('12');
		 $updateqry1 = "UPDATE wp_pay SET `balance` = '$balance',wallet_ref_no='$wallet_ref_no' where session_id='$session_id' ";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);	
		}
		
		  $qry2 = "SELECT session_id,wallet_ref_no,mobile_money_provider,balance FROM `wp_pay` where session_id='$session_id'";
    	 $res2= $this->fetchData($qry2, array());
	$session_id=$res2['session_id'];
	$wallet_ref_no=$res2['wallet_ref_no'];
	$mobile_money_provider=$res2['mobile_money_provider'];
	$balance=$res2['balance'];
	
	$response="Wallet to Bank Transfer
	{
    \"status\": true,
    \"result\": {
        \"status\": true,
        \"data\": {
            \"session_id\": \"$session_id\",
            \"wallet_ref_no\": \"$wallet_ref_no\",
            \"mobile_money_provider\": \"$mobile_money_provider\",
            \"balance\": \"$balance\"
        }
    }
}";
	 $log= $this->wh_log($response);
		 return $res2;
	}

function start_chat($data){
		extract($data);	 
	
	 if($type=='Insert'){
		$qry ="INSERT INTO `wp_temp` ( `msg`,phone_no) VALUES ( '$msg','$phone_no')";
		return $qry_result = $this->db_query($qry, array());
	 }else{ 
		  $qry2 = "SELECT id FROM `wp_temp` where phone_no='$phone_no' order by id desc limit 1";
    	  $id= $this->fetchOne($qry2, array());
		 
		 if($column_name=='country_code'){
			 $qry = "SELECT country_code FROM `wp_pay` where country_code='$msg' ";
    		 $country_code= $this->fetchOne($qry, array());
			 if($country_code==''){
				 return "False";
			 }
		 }
		   $updateqry1 = "UPDATE wp_temp SET $column_name = '$msg' where id='$id' ";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);	
		 $result = $results1 == 1 ? 1 : 0;
		 
		  $send_otp = "SELECT * FROM `wp_temp` where id='$id'";
    	    $res_send= $this->fetchData($send_otp, array());
		     $account_no= $res_send['account_no'];
			 $country_code=$res_send['country_code'];
			 $pin=$res_send['pin_no'];
		     $otp=$res_send['otp'];
		 
		 if($column_name=='pin_no'){			
			 $data= array("country_code"=>$country_code,"account_no"=>$account_no,"pin"=>$pin );
			$result= $this->init_trans($data);			
		 }
		 if($column_name=='otp'){			
			 $data= array("account_no"=>$account_no,"otp"=>$otp );
			 $result= $this->get_session($data);	
		 }
		 return $result;
		
		 
	 }
}
	
	
function insert_pay($data){
	
		extract($data);
	$qry ="INSERT INTO `wp_pay_details` ( `from_no`, `to_no`, `msg`, `msg_id`) VALUES ( '$from_no', '$to_no', '$msg', '$msg_id')";	
	return $qry_result = $this->db_query($qry, array());
}
	function add_bank_acc($data){
		//print_r($data);exit;
		extract($data);	 
				
		 $qry ="INSERT INTO `wp_pay` ( `name`, `country_code`, `phone_no`, `account_no`, `pin`, `mobile_money_provider`, `balance`) VALUES ( '$name', '$country_code', '$phone_no', '$account_no','$pin','$mobile_money_provider','$balance')";	
	return $qry_result = $this->db_query($qry, array());
	}
 function view_bank(){	 
	  // extract($data);
     $qry = "SELECT * FROM wp_pay";
     $values= $this->dataFetchAll($qry, array());
      return $values;
 }
 function edit_bank($data){	 
	   extract($data);
     $qry = "SELECT * FROM wp_pay where id='$id'";
     $values= $this->dataFetchAll($qry, array());
      return $values;
 }
function update_bank($data){	 
	   extract($data);
   $updateqry1 = "UPDATE wp_pay SET name = '$name',country_code='$country_code',phone_no='$phone_no',account_no='$account_no', pin='$pin',mobile_money_provider='$mobile_money_provider' ,balance='$balance'  WHERE id='$id'";
                  $parms = array();
                return  $results1 = $this->db_query($updateqry1,$parms);
 }
 function get_chats($data){	 
	   extract($data);
     $qry = "SELECT * FROM `wp_pay_details` where (from_no='$phone_no' or to_no='$phone_no') order by id desc";
     $values= $this->dataFetchAll($qry, array());
      return $values;
 }
function randomNumber($length) {
    $min = 1 . str_repeat(0, $length-1);
    $max = str_repeat(9, $length);
    return mt_rand($min, $max);   
} 
	  
function wh_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {        mkdir($log_filename, 0777, true);    }
    $log_file_data = $log_filename.'/log_' . date('d-m-Y') . '.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}	
}
