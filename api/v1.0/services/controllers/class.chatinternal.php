<?php
require_once('class.phpmailer.php');
require __DIR__ . '/../../eio/vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;

class chatinternal extends restApi{
   
function insertInternalChatMessage($chat_data){        
      extract($chat_data);//print_r($chat_data);exit;     
	  if($admin_id==1){
          $aid = $user_id;
      }else{
          $aid = $admin_id; 
      }
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());  
      date_default_timezone_set($user_timezone);
      $created_dt = date("Y-m-d H:i:s");
      $updated_dt = date("Y-m-d H:i:s");  
      $results_check_qry = "select * from chat_internal where chat_sender_id='$chat_sender_id' and chat_receiver_id='$chat_receiver_id' and admin_id='$aid'";
      $results_check = $this->fetchData($results_check_qry, array());

      if($results_check > 0){
		  //echo '123';exit;
        $chat_id=$results_check['chat_id'];
        $chat_data = array("user_id"=>$chat_sender_id,"agent_id"=>$chat_receiver_id,"admin_id"=>$admin_id); 
		
		  //============= UPDATE THE TIME TO GET THE ORDER BY AGENT LIST =============
		            $update_order_qry = "UPDATE chat_internal
          SET order_by_time ='$updated_dt' where chat_sender_id='$chat_sender_id' and chat_receiver_id='$chat_receiver_id' and admin_id='$aid'";
		  
         $updated_order = $this->db_query($update_order_qry, array());
		  
		  // ============= FILE UPLOADING =================
		  	$type = $_FILES["image_file"]["type"];
			 $ext = explode('/',$type);
			$exten=$ext[1];
			//print_r($exten);exit;
		  if($type != '' && $type != 'undefined'){
			$newfilename= $admin_id.str_replace(" ", "", basename($_FILES["image_file"]["name"]));	     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;      
		      $img_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_internal/". $newfilename;  
		      $img_target_path = $destination_path."chat_internal/". $newfilename;
		      move_uploaded_file($_FILES['image_file']['tmp_name'], $img_target_path); 
		  }else{
			  $img_upload_path = '';
		  }
		  		//print_r($img_upload_path);exit;
		  echo "1";
		  echo "INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')";exit;
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')", array()); 
      
        $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		 //============== ERP NOTIFICATION ========================
			$this->appNotification($chat_receiver_id,$chat_sender_id,$chat_id);
		  
		  // ==================== OMNI CHANNEL NOTIFICATION ==========================
		  	$notifys = array("user_id"=>$chat_receiver_id,"chat_id"=>$chat_id);
		  	$this->send_notificationInternal($notifys);
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id','$chat_receiver_id','$aid','$mc_event_data_fm','8','7','$created_dt')", array());
		  
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];
		  //print_r($result);exit;
		 // $x=$result;
		$this->ch_notification_curl($token,$chat_id,$chat_msg);
		   $result = $this->internal_chat_detail($chat_data);
		 
		  $tresult['result']['status']="true";
		  $tresult['result']['data']=$result;
		  
		   $tarray = json_encode($tresult);
		  print_r($tarray);exit;
       // return $result;      
      }else { 
		 //echo '1233';exit;
        $results_check_qry1 = "select * from chat_internal where chat_sender_id='$chat_receiver_id' and chat_receiver_id='$chat_sender_id' and admin_id='$aid'";
      $results_check1 = $this->fetchData($results_check_qry1, array());
		  
      if($results_check1 > 0){
		 // echo'22';exit;
        $chat_id=$results_check1['chat_id'];
        $chat_data = array("user_id"=>$chat_receiver_id,"agent_id"=>$chat_sender_id,"admin_id"=>$admin_id);  
		  
		  	  //============= UPDATE THE TIME TO GET THE ORDER BY AGENT LIST =============
		            $update_order_qry2 = "UPDATE chat_internal
          SET order_by_time ='$updated_dt' where chat_sender_id='$chat_receiver_id' and chat_receiver_id='$chat_sender_id' and admin_id='$aid'";
         //	print_r($update_order_qry2);exit;
		  $updated_order = $this->db_query($update_order_qry2, array());
		  
  
		  // ============= FILE UPLOADING =================
		  	$type = $_FILES["image_file"]["type"];
			 $ext = explode('/',$type);
			$exten=$ext[1];
			//print_r($exten);exit;
		  if($type != '' && $type != 'undefined'){
			$newfilename= $admin_id.str_replace(" ", "", basename($_FILES["image_file"]["name"]));	     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;      
		      $img_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_internal/". $newfilename;  
		      $img_target_path = $destination_path."chat_internal/". $newfilename;
		      move_uploaded_file($_FILES['image_file']['tmp_name'], $img_target_path); 
		  }else{
			  $img_upload_path = '';
		  }
		  echo "2";
		  echo "INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')";exit;
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')", array()); 
        $result = $this->internal_chat_detail($chat_data);
       			  
		 //============== ERP NOTIFICATION ========================
			$this->appNotification($chat_receiver_id,$chat_sender_id,$chat_id);
		  
		 // ==================== OMNI CHANNEL NOTIFICATION ==========================
		  	$notifys = array("user_id"=>$chat_receiver_id,"chat_id"=>$chat_id);
		  	$this->send_notificationInternal($notifys);
				
		  $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id','$chat_receiver_id','$aid','$mc_event_data_fm','8','7','$created_dt')", array());
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];	
		$this->ch_notification_curl($token,$chat_id,$chat_msg);  
        return $result;      
      }else{
        $chat_data = array("user_id"=>$chat_sender_id,"agent_id"=>$chat_receiver_id,"admin_id"=>$admin_id);  
		  
		   
		  // ============= FILE UPLOADING =================
		  	$type = $_FILES["image_file"]["type"];
			 $ext = explode('/',$type);
			$exten=$ext[1];
			//print_r($exten);exit;
		  if($type != '' && $type != 'undefined'){
			$newfilename= $admin_id.str_replace(" ", "", basename($_FILES["image_file"]["name"]));	     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;      
		      $img_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_internal/". $newfilename;  
		      $img_target_path = $destination_path."chat_internal/". $newfilename;
		      move_uploaded_file($_FILES['image_file']['tmp_name'], $img_target_path); 
		  }else{
			  $img_upload_path = '';
		  } 
		  echo '3';
		  echo "INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')";exit;
        $chat_id = $this->db_insert("INSERT INTO `chat_internal` (`chat_id`, `chat_type`,`chat_cat`,`chat_status`,`admin_id`,`created_ip`,`created_dt`,`updated_dt`,`chat_sender_id`,`chat_receiver_id`,`order_by_time`) VALUES ('$chat_id','3', '1','1','$admin_id','','$created_dt','$updated_dt','$chat_sender_id', '$chat_receiver_id','$updated_dt')", array());     
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,img_url,img_type,msg_status,created_ip,created_dt,updated_dt,read_status) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','$img_upload_path','$exten','1','','$created_dt','$updated_dt','1')", array());
        $result = $this->internal_chat_detail($chat_data);		  
		  		  
		 //============== ERP NOTIFICATION ========================
			$this->appNotification($chat_receiver_id,$chat_sender_id,$chat_id);
		  
		 // ==================== OMNI CHANNEL NOTIFICATION ==========================
		  	$notifys = array("user_id"=>$chat_receiver_id,"chat_id"=>$chat_id);
		  	$this->send_notificationInternal($notifys);
		  
        $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id''$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id''$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];	
		$this->ch_notification_curl($token,$chat_id,$chat_msg);
		  
        return $result;          
      }           
    }
}
	function internal_chat_detail($chat_data){
      extract($chat_data);//print_r($chat_data);exit;		
       $chat_detail_qry = "select chat_internal.chat_id, chat_internal.chat_sender_id, chat_internal.chat_receiver_id, chat_internal.chat_status, chat_internal.chat_type, chat_internal.chat_status, chat_internal_msg.chat_msg_id, chat_internal_msg.msg_sender_id, chat_internal_msg.msg_receiver_id, chat_internal_msg.msg_user_type,chat_internal_msg.img_url,chat_internal_msg.img_type,chat_internal_msg.read_status,chat_internal_msg.msg_type, chat_internal_msg.chat_msg, chat_internal_msg.msg_status,  TIME_FORMAT(chat_internal_msg.created_dt, '%H:%i') as chat_time, date_format(chat_internal_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.profile_image from chat_internal inner join chat_internal_msg on chat_internal_msg.chat_id=chat_internal.chat_id left join user on chat_internal_msg.msg_sender_id = user.user_id where chat_internal.chat_sender_id = '$user_id' and chat_internal.chat_receiver_id = '$agent_id' and chat_internal.admin_id = '$admin_id' order by chat_internal_msg.chat_msg_id DESC LIMIT 1";
        $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());
        return $result;       
    }
    	
	function internal_chat_detail_list($chat_data){
      extract($chat_data);//print_r($chat_data);exit;		
       $chat_detail_qry = "select chat_internal.chat_id, chat_internal.chat_type, chat_internal.chat_status, chat_internal_msg.chat_msg_id, chat_internal_msg.msg_sender_id, chat_internal_msg.msg_receiver_id,chat_internal_msg.img_type,chat_internal_msg.img_url, chat_internal_msg.msg_user_type, chat_internal_msg.msg_type, chat_internal_msg.chat_msg,chat_internal_msg.read_status, chat_internal_msg.msg_status,  TIME_FORMAT(chat_internal_msg.created_dt, '%H:%i') as chat_time, date_format(chat_internal_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.profile_image from chat_internal inner join chat_internal_msg on chat_internal_msg.chat_id=chat_internal.chat_id left join user on chat_internal_msg.msg_sender_id = user.user_id where chat_internal.chat_sender_id = '$user_id' and chat_internal.chat_receiver_id = '$agent_id' and chat_internal.admin_id = '$admin_id' order by chat_internal_msg.chat_msg_id asc";
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;       
    }
	
public function dept_agent_list($chat_data){ 
     extract($chat_data);//print_r($chat_data);exit;
	 if($admin_id==$user_id){
          $aid = $user_id;
		  $get_dept_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$aid' AND delete_status=0"; 
          $result['department_list'] = $this->dataFetchAll($get_dept_qry,array());
		  $get_agent_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE admin_id='$aid' AND delete_status=0";           
		 $result['agent_list'] = $this->dataFetchAll($get_agent_qry,array());
      }else{
          $aid = $admin_id;
		  $get_dept_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$aid' AND delete_status=0"; 
          $result['department_list'] = $this->dataFetchAll($get_dept_qry,array());
		  $get_agent_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE admin_id='$aid' AND user_id != '$user_id' AND delete_status=0";    
          $result['agent_list'] = $this->dataFetchAll($get_agent_qry,array());
		  $get_admin_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE user_id='$aid' AND delete_status=0";           
		  $result['admin_list'] = $this->dataFetchAll($get_admin_qry,array());
      }
      return $result;    
   } 

	public function dept_agent_list_app($chat_data){ 
     extract($chat_data);//print_r($chat_data);exit;
		$aid = $admin_id;
	$get_present_list = "(SELECT chat_sender_id AS chat_list,order_by_time FROM chat_internal WHERE '$user_id' IN (chat_sender_id,chat_receiver_id) UNION DISTINCT SELECT chat_receiver_id,order_by_time FROM chat_internal WHERE '$user_id' IN (chat_sender_id,chat_receiver_id)) ORDER BY order_by_time DESC";	
		$chatted_list = $this->dataFetchAll($get_present_list,array());
		
		$filterBy = $user_id; // Remove user ID

		$new = array_filter($chatted_list, function ($var) use ($filterBy) {
    	return ($var['chat_list'] != $filterBy);
		});
		
		//print_r($new);exit;
		$isbnList = [];
		foreach ($new as $item) {
    	if (isset($item['chat_list'])) {
       	 $isbnList[] = $item['chat_list'];
    		}
		}
		$value_id = implode(",", $isbnList);
		//print_r($value_id);exit;
		$get_first_msg = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE admin_id='$aid' AND user_id NOT IN ($value_id) AND user_id !='$user_id' AND delete_status=0";
		$get_agent_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE user_id IN ($value_id) AND delete_status=0 ORDER BY FIELD(user_id,$value_id);";
		$result['receiver_list'] = $this->dataFetchAll($get_first_msg,array());
        $result['agent_list'] = $this->dataFetchAll($get_agent_qry,array());
		
		return $result; 
		
   
   }  
	
	public function get_by_id($chat_data){ 
     extract($chat_data);//print_r($chat_data);exit;
		
	// ======================= UPDATE THE READED STATUS ==========================
		$update_read = "UPDATE chat_internal SET read_status ='0' WHERE chat_sender_id='$agent_id' AND chat_receiver_id = '$user_id' AND admin_id = '$admin_id'";
	  $updated_status = $this->db_query($update_read, array());
		
		
     $get_qry_one = "SELECT chat_id FROM chat_internal WHERE chat_sender_id='$user_id' AND chat_receiver_id = '$agent_id'";
     $results_one = $this->fetchData($get_qry_one,array());
     if($results_one > 0){           
             $result["chat_detail_list"] = $this->internal_chat_detail_list($chat_data);
		     $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type FROM user WHERE user_id='$agent_id'";
             $results_agent = $this->fetchData($get_agent_qry,array());
             $result['agent_name'] = $results_agent['agent_name'];
             $result['agent_profile_image'] = $results_agent['profile_image'];
		     $result['login_status'] = $results_agent['login_status'];
             $result['user_type'] = $results_agent['user_type'];
             return $result;
     }else{ 
		   
             $get_qry_two = "SELECT chat_id FROM chat_internal WHERE chat_sender_id='$agent_id' AND chat_receiver_id = '$user_id'";
             $results_two = $this->fetchData($get_qry_two,array());
             if($results_two > 0){             
               $chat_data = array("user_id"=>$agent_id,"agent_id"=>$user_id,"admin_id"=>$admin_id);				 
               $result["chat_detail_list"] = $this->internal_chat_detail_list($chat_data);
			   $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type FROM user WHERE user_id='$agent_id'";
               $results_agent = $this->fetchData($get_agent_qry,array());
               $result['agent_name'] = $results_agent['agent_name'];
               $result['agent_profile_image'] = $results_agent['profile_image'];
			   $result['login_status'] = $results_agent['login_status'];
               $result['user_type'] = $results_agent['user_type'];
               return $result;
             }else{
			   $result["chat_detail_list"] = array();
			   $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type FROM user WHERE user_id='$agent_id'";
               $results_agent = $this->fetchData($get_agent_qry,array());
			   $result['agent_name'] = $results_agent['agent_name'];
               $result['agent_profile_image'] = $results_agent['profile_image']; 
			    $result['login_status'] = $results_agent['login_status'];
               $result['user_type'] = $results_agent['user_type'];
               return $result;
			 }
      }             
   }
	
   public function user_last_chat($chat_data){ 
     extract($chat_data);//print_r($chat_data);exit;	     
	  $get_qry_one ="SELECT IF(chat_receiver_id = '$user_id', chat_sender_id,chat_receiver_id ) as my_chat,user.user_id,user.agent_name,user.profile_image,chat_internal_msg.chat_msg,chat_internal_msg.chat_msg_id FROM `chat_internal` INNER JOIN user on user.user_id= IF(chat_receiver_id = '$user_id', chat_sender_id,chat_receiver_id ) INNER JOIN chat_internal_msg on chat_internal.chat_id=chat_internal_msg.chat_id where (chat_sender_id='$user_id' or chat_receiver_id='$user_id') and chat_internal_msg.chat_msg_id =(SELECT max(chat_msg_id) FROM `chat_internal_msg` where (msg_sender_id=user.user_id or msg_receiver_id=user.user_id))";
     $results_one = $this->dataFetchAll($get_qry_one,array());
     if($results_one > 0){           
             $result["chat_detail_list"] = $results_one;
             return $result;
     }else{           
         $result["chat_detail_list"] = array();
         return $result;      
      }             
   }
	
	function ch_notification_curl($token,$chat_id,$chat_msg){
		   $url = "https://fcm.googleapis.com/fcm/send";
    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New Chat From Omni channel";
    $body = "Hello I am from Your php server";    
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'int_chat', 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
	
	
	public function getpermissions($data){
	
		extract($data);
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
		
		$get_agent_qry = "select user_id from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
	    $user_id = $this->fetchOne($get_agent_qry,array());
		$qry = "select * from user where user_id='$user_id'";		
		$results = $this->fetchData($qry, array());
		return $results;
	}
	
   public function userRecentChat($chat_data){ 
     extract($chat_data);
	  
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
	   $get_agent_qry = "select user_id from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
	   $user_id = $this->fetchOne($get_agent_qry,array());
	  $get_qry_one ="SELECT IF(chat_receiver_id = '$user_id', chat_sender_id,chat_receiver_id ) as my_chat,user.user_id,user.agent_name,user.profile_image,user.login_status,chat_internal_msg.chat_msg,chat_internal_msg.chat_msg_id FROM `chat_internal` INNER JOIN user on user.user_id= IF(chat_receiver_id = '$user_id', chat_sender_id,chat_receiver_id ) INNER JOIN chat_internal_msg on chat_internal.chat_id=chat_internal_msg.chat_id where (chat_sender_id='$user_id' or chat_receiver_id='$user_id') and chat_internal_msg.chat_msg_id =(SELECT max(chat_msg_id) FROM `chat_internal_msg` where (msg_sender_id=user.user_id or msg_receiver_id=user.user_id))";
     $results_one = $this->dataFetchAll($get_qry_one,array());
     if($results_one > 0){           
             $result["chat_detail_list"] = $results_one;
             return $result;
     }else{           
         $result["chat_detail_list"] = array();
         return $result;      
      }             
   }	
	
	public function appNotification($receiver_id,$sender_id,$chat_ids){
		
		$app_name = "select sip_login from user where user_id='$receiver_id'"; 
		$receiver_app = $this->fetchOne($app_name, array()); 
		$send_app_name = "select sip_login from user where user_id='$sender_id'"; 
		$sender_app = $this->fetchOne($send_app_name, array()); 
		 $app_element_data = array("action"=>"internal_chat_notification","receiver"=>$receiver_app,"sender"=>$sender_app,"sent_id"=>$chat_ids);

    //"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
		$send_app_result = array("operation" => "agents","moduleType"=>"agents","api_type"=>"web","access_token"=>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc","element_data"=>$app_element_data);
		$this->erp_app($send_app_result);	
	}
	
	public function deptAgentLisst($chat_data){ 
     extract($chat_data);//print_r($chat_data);exit;
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
		
		
	 if($admin_id==$user_id){
          $aid = $user_id;
		  $get_dept_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$aid' AND delete_status=0"; 
          $result['department_list'] = $this->dataFetchAll($get_dept_qry,array());
		  $get_agent_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE admin_id='$aid' AND delete_status=0";           
		 $result['agent_list'] = $this->dataFetchAll($get_agent_qry,array());
      }else{
          $aid = $admin_id;
		  $get_dept_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$aid' AND delete_status=0"; 
          $result['department_list'] = $this->dataFetchAll($get_dept_qry,array());
		  $get_agent_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE admin_id='$aid' AND user_id != '$user_id' AND delete_status=0";    
          $result['agent_list'] = $this->dataFetchAll($get_agent_qry,array());
		  $get_admin_qry = "SELECT user_id,agent_name,profile_image,login_status FROM user WHERE user_id='$aid' AND delete_status=0";           
		  $result['admin_list'] = $this->dataFetchAll($get_admin_qry,array());
      }
      return $result;    
   } 
	
	public function getChatById($chat_data){ 
     extract($chat_data);
		
		
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
		
		 $chat_details = array("admin_id"=>$admin_id,"user_id"=>$user_id,"agent_id"=>$agent_id);  

     $get_qry_one = "SELECT chat_id FROM chat_internal WHERE chat_sender_id='$user_id' AND chat_receiver_id = '$agent_id'";
     $results_one = $this->fetchData($get_qry_one,array());
     if($results_one > 0){           
             $result["chat_detail_list"] = $this->internal_chat_detail_list($chat_details);
		     $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type,user_id FROM user WHERE user_id='$agent_id'";
             $results_agent = $this->fetchData($get_agent_qry,array());
             $result['agent_name'] = $results_agent['agent_name'];
             $result['agent_profile_image'] = $results_agent['profile_image'];
		     $result['login_status'] = $results_agent['login_status'];
             $result['user_type'] = $results_agent['user_type'];
		 	 $result['user_i'] = base64_encode($results_agent['user_id']);
             return $result;
     }else{ 
		   
             $get_qry_two = "SELECT chat_id FROM chat_internal WHERE chat_sender_id='$agent_id' AND chat_receiver_id = '$user_id'";
             $results_two = $this->fetchData($get_qry_two,array());
             if($results_two > 0){             
               $chat_data = array("user_id"=>$agent_id,"agent_id"=>$user_id,"admin_id"=>$admin_id);				 
               $result["chat_detail_list"] = $this->internal_chat_detail_list($chat_data);
			   $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type,user_id FROM user WHERE user_id='$agent_id'";
               $results_agent = $this->fetchData($get_agent_qry,array());
               $result['agent_name'] = $results_agent['agent_name'];
               $result['agent_profile_image'] = $results_agent['profile_image'];
			   $result['login_status'] = $results_agent['login_status'];
               $result['user_type'] = $results_agent['user_type'];
				  $result['user_i'] = base64_encode($results_agent['user_id']);
               return $result;
             }else{
			   $result["chat_detail_list"] = array();
			   $get_agent_qry = "SELECT agent_name,profile_image,login_status,user_type,user_id FROM user WHERE user_id='$agent_id'";
               $results_agent = $this->fetchData($get_agent_qry,array());
			   $result['agent_name'] = $results_agent['agent_name'];
               $result['agent_profile_image'] = $results_agent['profile_image']; 
			    $result['login_status'] = $results_agent['login_status'];
               $result['user_type'] = $results_agent['user_type'];
				$result['user_i'] = base64_encode($results_agent['user_id']);
               return $result;
			 }
      }             
   }	
	
	
	public function getMyDatas($chat_data){ 
     extract($chat_data);
		
		
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
		$agent_name = $user_details['agent_name'];
		$user_id = base64_encode($user_details['user_id']);
		$user_type = $user_details['user_type'] == '2' ? 'Admin' : 'Employee';
		$profile_image = $user_details['profile_image'];
		$user_meta = array("user_type"=>$user_type,"usermeta"=>$user_id,"profile"=>$profile_image,"agent_name"=>$agent_name);  
		return $user_meta;
	}	
	
	
	
	function sendChatmsgFromWidget($chat_data){        
      extract($chat_data);
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
		  if($admin_id==1){
			  $aid = $user_id;
		  }else{
			  $aid = $admin_id; 
		  }
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());  
      date_default_timezone_set($user_timezone);
      $created_dt = date("Y-m-d H:i:s");
      $updated_dt = date("Y-m-d H:i:s");  
      $results_check_qry = "select * from chat_internal where chat_sender_id='$chat_sender_id' and chat_receiver_id='$chat_receiver_id' and admin_id='$aid'";
      $results_check = $this->fetchData($results_check_qry, array());      
      if($results_check > 0){
		  //echo '123';exit;
        $chat_id=$results_check['chat_id'];
        $chat_data = array("user_id"=>$chat_sender_id,"agent_id"=>$chat_receiver_id,"admin_id"=>$admin_id);  
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,msg_status,created_ip,created_dt,updated_dt) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','1','','$created_dt','$updated_dt')", array()); 
       
       $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];
		  //print_r($result);exit;
		 // $x=$result;
		$this->ch_notification_curl($token,$chat_id,$chat_msg);
		   $result = $this->internal_chat_detail($chat_data);
		  //print_r($result);exit;
        return $result;      
      }else { 
		 //echo '1233';exit;
        $results_check_qry1 = "select * from chat_internal where chat_sender_id='$chat_receiver_id' and chat_receiver_id='$chat_sender_id' and admin_id='$aid'";
      $results_check1 = $this->fetchData($results_check_qry1, array());
      if($results_check1 > 0){
		 // echo'22';exit;
        $chat_id=$results_check1['chat_id'];
        $chat_data = array("user_id"=>$chat_receiver_id,"agent_id"=>$chat_sender_id,"admin_id"=>$admin_id);  
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,msg_status,created_ip,created_dt,updated_dt) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','1','','$created_dt','$updated_dt')", array()); 
        $result = $this->internal_chat_detail($chat_data);
        $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];	
		$this->ch_notification_curl($token,$chat_id,$chat_msg);  
        return $result;      
      }else{
        $chat_data = array("user_id"=>$chat_sender_id,"agent_id"=>$chat_receiver_id,"admin_id"=>$admin_id);  
        $chat_id = $this->db_insert("INSERT INTO `chat_internal` (`chat_id`, `chat_type`,`chat_cat`,`chat_status`,`admin_id`,`created_ip`,`created_dt`,`updated_dt`,`chat_sender_id`,`chat_receiver_id`) VALUES ('$chat_id','3', '1','1','$admin_id','','$created_dt','$updated_dt','$chat_sender_id', '$chat_receiver_id')", array());     
        $chat_msg_id = $this->db_insert("INSERT INTO chat_internal_msg(chat_id,msg_sender_id,msg_receiver_id,msg_user_type,msg_type,chat_msg,msg_status,created_ip,created_dt,updated_dt) VALUES ('$chat_id','$chat_sender_id','$chat_receiver_id','4','text','$chat_msg','1','','$created_dt','$updated_dt')", array());
        $result = $this->internal_chat_detail($chat_data);
      $agent_name_qry = "select agent_name from user where user_id='$chat_receiver_id'";   
        $agent_name = $this->fetchOne($agent_name_qry, array());  
        $mc_event_data = "INT Chat to ".$agent_name;
		  
		$agent_name_qry_fm = "select agent_name from user where user_id='$chat_sender_id'";   
        $agent_name_fm = $this->fetchOne($agent_name_qry_fm, array());  
        $mc_event_data_fm = "INT Chat from ".$agent_name_fm;
		  
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_sender_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_receiver_id','$chat_receiver_id','$aid','$mc_event_data','8','7','$created_dt')", array());
		  
		$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$chat_receiver_id'";
	    $agentresult = $this->fetchData($agentqry, array());
		$token = $agentresult['notification_code'];
		//$profile_image = $agentresult[$i]['profile_image'];	
		$this->ch_notification_curl($token,$chat_id,$chat_msg);
        return $result;          
      }           
    }
}
	

 function send_notificationInternal($data){

extract($data);

//$host_name='https://'.$_SERVER['HTTP_HOST'];
//$click_url = $host_name.'/#/sms'; 
$socket = "https://myscoket.mconnectapps.com:4031";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    $client->emit('broadcast', ['title' =>'Internal Chat', 'notification_for'=>'internal-chat','unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','user_id'=>$user_id]);
		
	}
	
}	

		

