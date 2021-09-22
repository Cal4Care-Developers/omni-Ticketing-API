<?php
class template extends restApi{
 public function addtemplate($data){
		extract($data);	
		$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
           $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		   $timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";			 
           $timezone = $this->fetchmydata($timezone_qry,array());	
           date_default_timezone_set($timezone);  
     	   $created_at = date("Y-m-d H:i:s");
     	   $updated_at = date("Y-m-d H:i:s");             
     	   $datas=array("template_message"=>$template_message,"queue_no"=>$queue_no,"admin_id"=>$admin_id,"created_at"=>$created_at,"updated_at"=>$updated_at);
	  $qry = $this->generateCreateQry($datas, "template");
           $insert_data = $this->db_insert($qry, $datas);           
           if($insert_data != 0){
               $result = 1;              
           }
           else{                
                $result = 0;
           }            
           return $result;
		  
}
public function updateTemplate($data){
		extract($data);		
		$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
		$timezone = $this->fetchmydata($timezone_qry,array());	
        date_default_timezone_set($timezone);       	
     	$updated_at = date("Y-m-d H:i:s");
		$qry = "UPDATE template SET template_message='$template_message',queue_no='$queue_no',admin_id='$admin_id',updated_at='$updated_at' where template_id='$template_id'";
		$update_data = $this->db_query($qry, $params);
	    if($update_data != 0){
	        $result = 1;
	    }
	    else{
	        $result = 0;
	    }
	    return $result;
	}
	public function gettemplate($admin_id){
	    $qry = "select * from template where delete_status != 1 and admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
	}
	public function gettemplate_data($data){
		extract($data);
	    $qry = "select * from template where template_id ='$template_id' and admin_id='$admin_id'";		
		return $this->fetchData($qry, $params);
	}
	public function send_message($data){
		extract($data);	
		$admin_id_qry = "SELECT user_id FROM user WHERE hardware_id='$hardware_id'";
        $admin_id = $this->fetchOne($admin_id_qry,array());
		$qry = "select * from template where queue_no = '$queue_no' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array());		
		if($result > 0){
			     $template_message = $result['template_message'];
			 	 $message = urlencode($template_message);  
				 $postData = array(
				                'user' => 'cal4care',
				                'password' => 'Godaddy123',
				                'phonenumber' => "+91".$mobile_num,
				                'text' => $message,
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
				  $result = curl_exec($ch);
				  if(curl_errno($ch)){
				      throw new Exception(curl_error($ch));
				  }
				  curl_close($ch);
				  if($result == '1: Message sent successfully @  '){
				      $result = array("data" => 1);
				      $result_array = json_encode($result);           
				      print_r($result_array);exit;
				    }else{
				      $result = array("data" => 0);
				      $result_array = json_encode($result);           
				      print_r($result_array);exit;
				    }
		} else {
		   $result = array("data" => 0);
		   $result_array = json_encode($result);           
		   print_r($result_array);exit;
		}
	}
	public function delete_template($data){
      extract($data);
      $qry = "UPDATE template SET delete_status='1' WHERE template_id='$template_id' AND admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
		
	
	
public function addSmsTemplate($data){
	extract($data);	
	//print_r($data); exit;
	$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
	$user_timezone = $this->fetchmydata($user_timezone_qry,array());
	$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";			 
	$timezone = $this->fetchmydata($timezone_qry,array());	
	date_default_timezone_set($timezone);  
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s"); 
	
	 $insert_data = $this->db_insert("INSERT INTO sms_templates(template_message, template_name, department, admin_id, created_at,updated_at) VALUES ('$template_content', '$template_name', '$department', '$admin_id', '$created_at', '$updated_at')", array()); 
	
	if($insert_data != 0){
		$result = 1;              
	}
	else{                
		$result = 0;
	}            
	return $result;

}
	
public function listTemplate($admin_id){
	$qry = "select *,(select department_name from departments where dept_id = department) as dept from sms_templates where admin_id ='$admin_id'";
	return $this->dataFetchAll($qry, array());
}

public function listTemplateExternal($login){
		$encryption = $login;
		$ciphering = "AES-128-CTR"; 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		$decryption_iv = '1234567891011121'; 
		$decryption_key = "GeeksforGeeks"; 
		$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
		$decryption =  $array = json_decode($decryption, true);
		extract($decryption);
       	$hash_val = md5($password);
		$get_agent_qry = "select * from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
		$user_details = $this->fetchData($get_agent_qry,array());
		$user_id = $user_details['user_id'];
		$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
		$timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());
	
	
	$qry = "select *,(select department_name from departments where dept_id = department) as dept from sms_templates where admin_id ='$admin_id'";
	return $this->dataFetchAll($qry, array());
}
	
public function getSingleTemplateData($data){
	extract($data);
	$qry = "select * from sms_templates where template_id ='$template_id' and admin_id='$admin_id'";	
	return $this->fetchData($qry, array());
}
		
public function updateSmsTemplate($data){
	extract($data);		
	$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
	$user_timezone = $this->fetchmydata($user_timezone_qry,array());
	$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
	$timezone = $this->fetchmydata($timezone_qry,array());	
	date_default_timezone_set($timezone);       	
	$updated_at = date("Y-m-d H:i:s");
	$qry = "UPDATE sms_templates SET template_message='$template_content',template_name='$template_name',department='$department',updated_at='$updated_at' where template_id='$template_id'";
	$update_data = $this->db_query($qry, array());
	if($update_data != 0){
		$result = 1;
	}
	else{
		$result = 0;
	}
	return $result;
	}
	public function listTemplateByUSer($data){
		extract($data);
		
	 $user_type_qry = "SELECT user_type FROM user WHERE user_id='$user_id'";
        $user_type = $this->fetchOne($user_type_qry,array());
        if($user_type==2){	
			$qry = "select dept_id from departments where admin_id='$user_id'";
			
		} else {
		$qry = "select dept_id from departments where department_users LIKE '%$user_id%'";
		}
		$result  = $this->dataFetchAll($qry, array());
		
		foreach($result as $v) {
		  $r[] = $v['dept_id'];
		}
		$departments = implode(',',$r);
		$qry = "select *,(select department_name from departments where dept_id = department) as dept from sms_templates where department IN ($departments) and admin_id='$admin_id'";
	return $this->dataFetchAll($qry, array());

}
	
	public function deleteTemplate($template_id){
      $qry = "DELETE From sms_templates where template_id='$template_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }	
	
	}