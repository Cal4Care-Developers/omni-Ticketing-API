<?php
//print_r($_REQUEST);exit;
require __DIR__ . '/../../twilio-php-master/src/Twilio/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;
//require __DIR__ . '/../../eio/vendor/autoload.php';
//use ElephantIO\Client;
//use ElephantIO\Engine\SocketIO\Version1X as Version1X;
class chatwp extends restApi{
    
    
    function getcustomersChat($user_id,$queue_id,$search_text){
    
       $qry = "select * from user where user_id='$user_id'";

           $result = $this->fetchData($qry, array());

          if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }

    
        $search_qry ="";
		if($search_text != ""){
		  $search_qry .= "and customer.customer_name like '%".$search_text."%'";
		}
        
        
        if($queue_id == null || $queue_id==""){
            
            $queue_condtion = "select queue.queue_id from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and  FIND_IN_SET('1', q_user.queue_feature) and q_user.queue_user_status = '1' and queue.queue_status = '1'";
            
            
        }
        else{
            $queue_condtion = $queue_id;
        }
        
        //$queue_chat_qry = "select chat_data_wp.chat_msg_id, chat_data_wp.read_status, chat_data_wp.chat_id, chat_data_wp.chat_message, date_format(chat_data_wp.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp.created_dt, '%H:%i') as chat_time, chat_wp.customer_name as customer_name, chat_wp.app_chat_id, chat_wp.api_type from chat_wp inner join chat_data_wp on chat_data_wp.chat_id = chat_wp.chat_id where chat_data_wp.chat_msg_id in (select max(chat_msg_id) from chat_data_wp group by chat_id order by chat_msg_id desc) and chat_wp.admin_id='$admin_id' order by chat_data_wp.chat_msg_id desc";
		
		
		
           $qry = "select * from user where user_id='$admin_id'";

           $result = $this->fetchData($qry, array());
		
		$wathsapp_num = $result['whatsapp_num'];
		
		if($result['whatsapp_account'] == '1'){
				$queue_chat_qry = "select chat_data_wp.chat_msg_id, chat_data_wp.read_status, chat_data_wp.chat_id, chat_data_wp.chat_message, date_format(chat_data_wp.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp.created_dt, '%H:%i') as chat_time, chat_wp.customer_name as customer_name, chat_wp.app_chat_id, chat_wp.api_type from chat_wp inner join chat_data_wp on chat_data_wp.chat_id = chat_wp.chat_id where chat_data_wp.chat_msg_id in (select max(chat_msg_id) from chat_data_wp group by chat_id order by chat_msg_id desc) and chat_wp.admin_id != '' and chat_wp.app_chat_id = '$wathsapp_num' order by chat_data_wp.chat_msg_id desc";
		} else {
				$queue_chat_qry = "select chat_data_wp.chat_msg_id, chat_data_wp.read_status, chat_data_wp.chat_id, chat_data_wp.chat_message, date_format(chat_data_wp.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp.created_dt, '%H:%i') as chat_time, chat_wp.customer_name as customer_name, chat_wp.app_chat_id, chat_wp.api_type from chat_wp inner join chat_data_wp on chat_data_wp.chat_id = chat_wp.chat_id where chat_data_wp.chat_msg_id in (select max(chat_msg_id) from chat_data_wp group by chat_id order by chat_msg_id desc) and chat_wp.admin_id != '' order by chat_data_wp.chat_msg_id desc";
		}
		

        
      
        
        $parms = array();
        $result = $this->dataFetchAll($queue_chat_qry,array());

        return $result;
        

    }
       function getAdminDatasN(){
            $qry = "select * from user where user_type ='2'";

            return $this->dataFetchAll($qry, array());
        }
    function chatDetailList($chat_id){
        
  
       // $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id asc";
    
    
  $chat_detail_qry = "select * from (select chat_data_wp.chat_msg_id, chat_data_wp.whatsapp_media_url, chat_data_wp.delivered_status as msg_status, chat_data_wp.msg_from as msg_user_type,chat_data_wp.chat_id,chat_data_wp.chat_message as chat_msg,date_format(chat_data_wp.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp.created_dt, '%H:%i') as chat_time, chat_wp.app_customer_key as customer_name,chat_data_wp.chat_pnr as chat_pnr, users.user_name,chat_data_wp.agent_id as msg_user_id, chat_wp.api_type as msg_type from chat_data_wp inner join chat_wp on chat_wp.chat_id = chat_data_wp.chat_id left join user as users on users.user_id = chat_data_wp.agent_id where chat_wp.chat_id LIKE '".$chat_id."' order by chat_data_wp.chat_msg_id desc) result_data order by chat_msg_id asc";
        
    
        
           // $this->errorLog("demo",$chat_detail_qry);
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());

        return $result;
        
        
    }
  
  function getChatDetails($chat_msg_id){
    
     $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
    
    
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());

        return $result;
  
  
  }
    

  
  
  function mcEvents(){
    
    $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id order by event.mc_event_id DESC";
      
    $parms = array();
        $result = $this->dataFetchAll($mc_event_qry,array());

        return $result;
  
  }
    
    
    
   /*function insertChatMessage($chat_data){
        
        extract($chat_data);

          $qry = "select * from chat_wp where chat_id='$chat_id'";

        $result = $this->fetchData($qry, array());


          
    
     $mobile_num = $result['app_customer_key'];
     $admin_num = $result['app_chat_id'];



// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure
$sid    = "ACd2b8754af33c55da6e4ec1e00e429266";
$token  = "6d0000186df92bb6444f03cc7f22b655";
$twilio = new Client($sid, $token);

$message = $twilio->messages
                  ->create("whatsapp:$mobile_num", // to
                           [
                               "mediaUrl" => ["https://omni.mconnectapps.com/api/v1.0/profile_image/c4c-logo.png"],
                               "from" => "whatsapp:$admin_num",
                               
                           ]
                  );

  $wp_msg_id = $message->sid;

  $qry = "SELECT * FROM `user` WHERE `user_id` ='$agent_id'";
      $result = $this->fetchData($qry, array());

      $timezone_id = $result['timezone_id'];
            $admin_id = $result['admin_id']; 


     $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
    // echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
  
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());        
      $chat_data = $this->getChatDetails($chat_msg_id);
     $mc_event_data = "whatsapp Message By Admin";
        $this->db_query("INSERT INTO mc_event (admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$admin_id','$mc_event_data','5','7','$created_at')", array());
        return $chat_data;
           
    }*/
    

function insertChatMessage($chat_data){        
       extract($chat_data);
	   $qry = "select * from chat_wp where chat_id='$chat_id'";
	   $result = $this->fetchData($qry, array());	
	   $mobile_num = $result['app_customer_key'];
	   $admin_num = $result['app_chat_id'];
// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure
       $sid    = "ACcf360292ecffb40031e510d2e7492490";
       $token  = "b8eeb0ca8db33cb1fefc3ff7017e2cdf";
       $twilio = new Client($sid, $token);
       if($whatsapp_media_url==''){
          $message = $twilio->messages
                  ->create("whatsapp:$mobile_num", // to
                           [
                               "from" => "whatsapp:$admin_num",
                               "body" => "$chat_msg"
                           ]
                  );
        }else{  
          $message = $twilio->messages
                  ->create("whatsapp:$mobile_num", // to
                           [
                               "mediaUrl" => "$whatsapp_media_url",
                               "from" => "whatsapp:$admin_num"                               
                           ]
                  );                
       }
	   $wp_msg_id = $message->sid;
	//echo $wp_msg_id; exit;
	   $qry = "SELECT * FROM `user` WHERE `user_id` ='$agent_id'";
	   $result = $this->fetchData($qry, array());
	   $timezone_id = $result['timezone_id'];
       $admin_id = $result['admin_id'];
	   $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
	  // echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
       $user_timezone = $this->fetchmydata($user_timezone_qry,array());
       date_default_timezone_set($user_timezone);
       $created_at = date("Y-m-d H:i:s"); 	
       $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,	delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', '$chat_msg', 'SENT', '1', '$wp_msg_id', '$created_at', '$whatsapp_media_url')", array());        
   	   $chat_data = $this->getChatDetails($chat_msg_id);
	   $mc_event_data = "whatsapp Message By Admin";
       $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
        return $chat_data;         
    }










    function ComposeChatMessage($chat_data){
        
        extract($chat_data);    

$xml = '<?xml version="1.0" encoding="UTF-8" ?><TELEMESSAGE><TELEMESSAGE_CONTENT><MESSAGE><MESSAGE_INFORMATION><SUBJECT></SUBJECT></MESSAGE_INFORMATION><USER_FROM><CIML><NAML><LOGIN_DETAILS><USER_NAME>1676</USER_NAME><PASSWORD>32902540</PASSWORD></LOGIN_DETAILS></NAML></CIML></USER_FROM><MESSAGE_CONTENT><TEXT_MESSAGE><MESSAGE_INDEX>0</MESSAGE_INDEX><TEXT>'.$chat_msg.'</TEXT></TEXT_MESSAGE></MESSAGE_CONTENT><USER_TO><CIML><DEVICE_INFORMATION><DEVICE_TYPE  DEVICE_TYPE="SMS"/><DEVICE_VALUE>'.$phone_num.'</DEVICE_VALUE></DEVICE_INFORMATION></CIML></USER_TO></MESSAGE></TELEMESSAGE_CONTENT><VERSION>1.6</VERSION></TELEMESSAGE>';
$url = 'https://bulkmessage.dhiraagu.com.mv/partners/xmlMessage.jsp';
$curl = curl_init($url);
curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($curl);
if(curl_errno($curl)){
    throw new Exception(curl_error($curl));
}
curl_close($curl);
     
$xml = simplexml_load_string($result);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
     
     if($array['TELEMESSAGE_CONTENT']['RESPONSE']['RESPONSE_STATUS'] == '100'){ $del_stat = '1'; } else { $del_stat = '0'; }
      
     $qry = "select * from chat_sms where app_customer_key='$phone_num'";

       $result = $this->fetchData($qry, array());
    if($result > 0){
      $chat_id=$result['chat_id'];
           
//date_default_timezone_set("Indian/Maldives");
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
  
    
      
       $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at')", array());
        
        $chat_data = $this->getChatDetails($chat_msg_id);
        return $chat_data;
      
    } else {
       $qry = "select * from user where user_id='$user_id'";

       $result = $this->fetchData($qry, array());

        if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }

//date_default_timezone_set("Indian/Maldives");
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
      
        $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`) VALUES ('$phone_num', '$phone_num', '$phone_num', '', 'sms', '0', '1', '1', '$admin_id','$created_at')");
        
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at')", array());
        
      $chat_data = $this->getChatDetails($chat_msg_id);
        return $chat_data;
           
    }
      
    }
  
  function ComposeIncommingChatMessage($chat_data){
        extract($chat_data);	  
    $qry = "SELECT * FROM `admin_details` WHERE `whatsapp_num` LIKE '$to'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
    $userqry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
    $userresult = $this->fetchData($userqry, array());    
    $timezone_id = $userresult['timezone_id'];
	//$notification_code = $userresult['notification_code'];
	$admintoken = $userresult['notification_code'];
	$profile_image = $userresult['profile_image'];
    //date_default_timezone_set("Indian/Maldives");
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
	  $chat_name="SELECT contact_owner FROM `contacts` where whatsapp_number='$from'";
		$name_wp = $this->fetchOne($chat_name, array());   
		if($name_wp==''){
			$from_name=$from;
		}else{
			$from_name=$name_wp;
		}
     $qry = "select * from chat_wp where app_customer_key ='$from'";  
       $results = $this->fetchData($qry, array());  
    if($results > 0){     
          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;
                $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;      
    } else {
            $chat_id = $this->db_insert("INSERT INTO chat_wp (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`) VALUES ('$to','$from', '$from','sms','0','1','1','$admin_id','$created_at')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;     
                $this->db_query("INSERT INTO mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) VALUES('$chat_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;  
    }  
    }
  
  
  
    function updateWpChatStatus($chat_data){
      extract($chat_data);

    $qry = "UPDATE chat_data_wp SET delivered_status = '$chat_status' WHERE wp_msg_id='$MessageId'";
      
      
            $qry_result = $this->db_query($qry, $params);   
      
      
      
            return $qry_result;

  }

  
/*function generate_incoming_fb($chat_data){
    extract($chat_data);
	
      $qry = "SELECT * FROM `admin_details` WHERE `fb_page_id` like '%$recipient_id%'";
      $result = $this->fetchData($qry, array());
      $admin_id = $result['admin_id'];
      $widget_details= $this->fetchData("SELECT id,has_fb FROM chat_widget WHERE admin_id='$admin_id' AND has_fb = 1", array());
      $widget_id = $widget_details['id'];
      $fb_permission = $widget_details['has_fb'];	  
	  //$payment_log = file_put_contents('fb.txt', $widget_id.$fb_permission.PHP_EOL , FILE_APPEND | LOCK_EX);
      $qry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
      $result = $this->fetchData($qry, array());
      $timezone_id = $result['timezone_id'];
	  $admintoken = $result['notification_code'];
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s");
    
    if($fb_permission==0){
		if(isset($message_attachment)){
		$base = basename($message_attachment);
		$explode_question = explode('?',$base);
		//print_r($explode);
		$attachment_name = $explode_question[0];
		$explode_dot = explode('.', $attachment_name);
		$extension_value = end($explode_dot);
		$image_name = $attachment_name; 
		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
		$fb_media_target_path = $destination_path."facebook_image/".$image_name;
		file_put_contents($fb_media_target_path, file_get_contents($message_attachment)); 
		$fb_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/facebook_image/".$image_name; 		
		copy($message_attachment, $fb_media_target_path);
		}
		// fb incoming document upload code
		$checkqry = "select * from chat_fb where recipient_id ='$recipient_id' AND admin_id='$admin_id'";
		$results = $this->fetchData($checkqry, array());
		$result_count = $this->dataRowCount($checkqry , array());
		if($result_count > 0){
		  $chat_id=$results['chat_id'];
		  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1')", array());     
		  $mc_event_data = "Facebook Message From ".$first_name.$last_name;
			$this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$admin_id','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array());
		   // $this->fb_notification_curl($admin_id,$sender_id,$chat_msg);
			$fb_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_fb='1'", array());

				$fb_users = explode(',',$fb_users['GROUP_CONCAT(user_id)']);	
				$fb_users[]=$admin_id;
				$this->fb_notification_curl($fb_users,$sender_id,$chat_msg);
				
			return $chat_msg_id;      
		}
		else {
		  $chat_id = $this->db_insert("INSERT INTO chat_fb (`recipient_id`,`sender_id`,`chat_status`,`admin_id`,`created_at`) VALUES ('$recipient_id','$sender_id','1','$admin_id','$created_at')", array());   
		  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`read_status`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,'$extension_value') VALUES ('$chat_id','$recipient_id','$sender_id','1','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture')", array());       
			$mc_event_data = "New Facebook Message From ".$first_name.$last_name;
			$this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$admin_id','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array());
		  //  $this->fb_notification_curl($admin_id,$sender_id,$chat_msg);
				//$agentqry = "SELECT user_id FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
				$fb_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_fb='1'", array());
				$fb_users = explode(',',$fb_users['GROUP_CONCAT(user_id)']);
				$fb_users[]=$admin_id;
				$this->fb_notification_curl($fb_users,$sender_id,$chat_msg);
				
			return $chat_msg_id;  
		}
    }
    else{  // chatbot permission on state		
	    $checkqry = "select * from chat_fb where recipient_id ='$recipient_id' AND admin_id='$admin_id'";
        $results = $this->fetchData($checkqry, array());
        $result_count = $this->dataRowCount($checkqry , array());
		//file_put_contents('fb.txt', $result_count.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
        if($result_count > 0){
            $chat_id=$results['chat_id'];
            $insertUserChat = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`,`bot_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1','1')", array());
            $chat_bot_reply = $this->fetchOne("SELECT answer FROM chat_question WHERE question LIKE '%$chat_msg%' AND admin_id='$admin_id' AND widget_id = '$widget_id'", array());
            if($chat_bot_reply != ''){
              $chat_message = $chat_bot_reply;
            }else{
              $chat_message = $this->fetchOne("SELECT question FROM chat_question WHERE admin_id='$admin_id' AND status = 2", array());
            }
            $reply_message="{
                       'recipient': {
                          'id': '$sender_id'
                        },
                       'message':{    
                          'text': '$chat_message'
                        }
                   }";
            $sr = $this->send_reply($access_token,$reply_message);
            //file_put_contents('fb.txt', $sr.PHP_EOL , FILE_APPEND | LOCK_EX);
			$bot_profile_pic = 'https://omni-ticketing-xcupb.ondigitalocean.app/assets/images/bot.png';
            $insertBotReply = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`profile_pic`,`chat_message`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`read_status`,`bot_status`) VALUES ('$chat_id','$bot_profile_pic','$chat_message','1','$admin_id','1','$created_at','1','1')", array());
       }
    }  
  }*/

  function generate_incoming_fb($chat_data){
    extract($chat_data);    
    //$payment_log = file_put_contents('demo.txt', $profile_pic.PHP_EOL , FILE_APPEND | LOCK_EX);
    $qry = "SELECT * FROM `admin_details` WHERE `fb_page_id` like '%$recipient_id%'";
    $result = $this->fetchData($qry, array());
    $admin_id = $result['admin_id'];
    $widget_details= $this->fetchData("SELECT widget_id,has_fb FROM chat_widget WHERE admin_id='$admin_id' AND has_fb = 1", array());
    $widget_id = $widget_details['widget_id'];
    $fb_permission = $widget_details['has_fb'];
    $qry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
    $result = $this->fetchData($qry, array());
    $timezone_id = $result['timezone_id'];
    $admintoken = $result['notification_code'];
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
    date_default_timezone_set($user_timezone);
    $created_at = date("Y-m-d H:i:s");
    if($fb_permission==0){
        if(isset($message_attachment)){
        $base = basename($message_attachment);
        $explode_question = explode('?',$base);
        //print_r($explode);
        $attachment_name = $explode_question[0];
        $explode_dot = explode('.', $attachment_name);
        $extension_value = end($explode_dot);
        $image_name = $attachment_name; 
        $destination_path = getcwd().DIRECTORY_SEPARATOR;            
        $fb_media_target_path = $destination_path."facebook_image/".$image_name;
        file_put_contents($fb_media_target_path, file_get_contents($message_attachment)); 
        $fb_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/facebook_image/".$image_name;         
        copy($message_attachment, $fb_media_target_path);
        }
        // fb incoming document upload code
        $checkqry = "select * from chat_fb where recipient_id ='$recipient_id'";
        $results = $this->fetchData($checkqry, array());
        $result_count = $this->dataRowCount($checkqry , array());
        if($result_count > 0){
          $chat_id=$results['chat_id'];
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1')", array());     
          $mc_event_data = "Facebook Message From ".$first_name.$last_name;
          $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$admin_id','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array());
           // $this->fb_notification_curl($admin_id,$sender_id,$chat_msg);
          $fb_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_fb='1'", array());
          $fb_users = explode(',',$fb_users['GROUP_CONCAT(user_id)']);  
          $fb_users[]=$admin_id;
          $this->fb_notification_curl($fb_users,$sender_id,$chat_msg);          
          return $chat_msg_id;      
        }
        else {
          $chat_id = $this->db_insert("INSERT INTO chat_fb (`recipient_id`,`sender_id`,`chat_status`,`admin_id`,`created_at`) VALUES ('$recipient_id','$sender_id','1','$admin_id','$created_at')", array());   
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`read_status`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,'$extension_value') VALUES ('$chat_id','$recipient_id','$sender_id','1','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture')", array());       
          $mc_event_data = "New Facebook Message From ".$first_name.$last_name;
          $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$admin_id','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array());
          //  $this->fb_notification_curl($admin_id,$sender_id,$chat_msg);
                //$agentqry = "SELECT user_id FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
          $fb_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_fb='1'", array());
          $fb_users = explode(',',$fb_users['GROUP_CONCAT(user_id)']);
          $fb_users[]=$admin_id;
          $this->fb_notification_curl($fb_users,$sender_id,$chat_msg);
          return $chat_msg_id;
        }
    }
    else{  // chatbot permission on state
        $checkqry = "SELECT * FROM chat_fb WHERE recipient_id ='$recipient_id'";
        $results = $this->fetchData($checkqry, array());
        $result_count = $this->dataRowCount($checkqry , array());
        if($result_count > 0){
            $chat_id=$results['chat_id'];
            if($chat_msg!='yes'){
                $insertUserChat = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`,`bot_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1','0')", array());
                $chat_bot_reply = $this->fetchOne("SELECT answer FROM chat_question WHERE question LIKE '%$chat_msg%' AND admin_id='$admin_id' AND widget_id = '$widget_id'", array());
                if($chat_bot_reply != ''){
                  $chat_message = $chat_bot_reply;
                }else{
                  $chat_message = $this->fetchOne("SELECT question FROM chat_question WHERE admin_id='$admin_id' AND status = 2", array());
                }
                $reply_message="{
                           'recipient': {
                              'id': '$sender_id'
                            },
                           'message':{    
                              'text': '$chat_message'
                            }
                       }";
                $sr = $this->send_reply($access_token,$reply_message);
                $payment_log = file_put_contents('fb.txt', $sr.PHP_EOL , FILE_APPEND | LOCK_EX);
                $bot_profile_pic = 'https://omni-ticketing-xcupb.ondigitalocean.app/assets/images/bot.png';
                $insertBotReply = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`profile_pic`,`chat_message`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`read_status`,`bot_status`) VALUES ('$chat_id','$bot_profile_pic','$chat_msg','1','$admin_id','1','$created_at','1','1')", array());
            }else{
                if(isset($message_attachment)){
                    $base = basename($message_attachment);
                    $explode_question = explode('?',$base);
                    //print_r($explode);
                    $attachment_name = $explode_question[0];
                    $explode_dot = explode('.', $attachment_name);
                    $extension_value = end($explode_dot);
                    $image_name = $attachment_name; 
                    $destination_path = getcwd().DIRECTORY_SEPARATOR;            
                    $fb_media_target_path = $destination_path."facebook_image/".$image_name;
                    file_put_contents($fb_media_target_path, file_get_contents($message_attachment)); 
                    $fb_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/facebook_image/".$image_name;         
                    copy($message_attachment, $fb_media_target_path);
                }
                $chat_msg_id = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1')", array());     
               $mc_event_data = "Facebook Message From ".$first_name.$last_name;
               $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$admin_id','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array());
               // $this->fb_notification_curl($admin_id,$sender_id,$chat_msg);
               $fb_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_fb='1'", array());
               $fb_users = explode(',',$fb_users['GROUP_CONCAT(user_id)']);  
               $fb_users[]=$admin_id;
               $this->fb_notification_curl($fb_users,$sender_id,$chat_msg);
               $this->db_query("UPDATE external_tickets_data SET bot_status='0' where chat_id='$chat_id'", array());          
               return $chat_msg_id;
            }
       }else{
            $chat_id = $this->db_insert("INSERT INTO chat_fb (`recipient_id`,`sender_id`,`chat_status`,`admin_id`,`created_at`) VALUES ('$recipient_id','$sender_id','1','$admin_id','$created_at')", array());   
            if($chat_msg!='yes'){
                $insertUserChat = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`recipient_id`,`sender_id`,`access_token`,`first_name`,`last_name`,`profile_pic`,`chat_message`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`page_name`,`page_picture`,`extension`,`read_status`,`bot_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$access_token','$first_name','$last_name','$profile_pic','$chat_msg','$fb_media_target_path','1','$admin_id','1','$created_at','$page_name','$page_picture','$extension_value','1','0')", array());
                $chat_bot_reply = $this->fetchOne("SELECT answer FROM chat_question WHERE question LIKE '%$chat_msg%' AND admin_id='$admin_id' AND widget_id = '$widget_id'", array());
                if($chat_bot_reply != ''){
                  $chat_message = $chat_bot_reply;
                }else{
                  $chat_message = $this->fetchOne("SELECT question FROM chat_question WHERE admin_id='$admin_id' AND status = 2", array());
                }
                $reply_message="{
                           'recipient': {
                              'id': '$sender_id'
                            },
                           'message':{    
                              'text': '$chat_message'
                            }
                       }";
                $sr = $this->send_reply($access_token,$reply_message);
                $payment_log = file_put_contents('fb.txt', $sr.PHP_EOL , FILE_APPEND | LOCK_EX);
                $bot_profile_pic = 'https://omni-ticketing-xcupb.ondigitalocean.app/assets/images/bot.png';
                $insertBotReply = $this->db_insert("INSERT INTO chat_data_fb (`chat_id`,`profile_pic`,`chat_message`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`read_status`,`bot_status`) VALUES ('$chat_id','$bot_profile_pic','$chat_msg','1','$admin_id','1','$created_at','1','1')", array());
            }          
       }      
    }  
  }	
	
 function fb_message_panel($chat_data){
    extract($chat_data);
    $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
    $admin_id = $this->fetchOne($admin_id_qry,array());
    if($admin_id==1){
      $aid = $user_id;
    }else{
      $aid = $admin_id;
    }
	$chat_detail_qry = "SELECT sender_id FROM chat_data_fb WHERE admin_id='$aid' AND first_name != '' GROUP BY `sender_id` ORDER BY MAX(created_at) DESC";
    $parms = array();
    $result = $this->dataFetchAll($chat_detail_qry,array());
	//print_r($result);exit;  
    for($i = 0; count($result) > $i; $i++){
      $sid = $result[$i]['sender_id'];
      $getdata = "SELECT first_name,chat_id,recipient_id,last_name,profile_pic,page_name,page_picture,date_format(created_at, '%d/%m/%Y') as chat_dt,read_status FROM chat_data_fb WHERE sender_id='$sid' ORDER BY chat_msg_id DESC LIMIT 1";
	  //echo $getdata;	
      $data_value = $this->fetchsingledata($getdata, array(":sender_id"=>$sid));
      $first_name = $data_value['first_name'];
      $chat_id = $data_value['chat_id'];
      $recipient_id = $data_value['recipient_id'];
      $last_name = $data_value['last_name'];
      $profile_pic = $data_value['profile_pic'];
      $page_name = $data_value['page_name'];
      $page_picture = $data_value['page_picture'];
      $chat_dt = $data_value['chat_dt'];
	  $read_status = $data_value['read_status'];	
      $options = array('first_name' => $first_name, 'chat_id' => $chat_id, 'recipient_id' => $recipient_id, 'last_name' => $last_name, 'profile_pic' => $profile_pic, 'page_name' => $page_name, 'page_picture' => $page_picture, 'chat_dt' => $chat_dt, 'sender_id' => $sid, 'read_status' => $read_status);                   
      $options_array[] = $options;
    }
    $status_array = array('status' => true);
    $options_array = array('data' => $options_array);
    $result_arr = array('result' => $options_array);
    $merge = array_merge($status_array, $result_arr);
    $tarray = json_encode($merge);
    print_r($tarray);exit;   
    //echo "SELECT first_name,chat_id,sender_id,recipient_id,last_name,profile_pic,page_name,page_picture,date_format(created_at, '%d/%m/%Y') as chat_dt FROM chat_data_fb WHERE admin_id='$aid' AND first_name != '' GROUP BY sender_id ORDER BY chat_msg_id DESC";exit;	  
	  //SELECT chat_msg_id,first_name,chat_id,read_status,sender_id,recipient_id,last_name,profile_pic,page_name,page_picture,date_format(created_at, '%d/%m/%Y') as chat_dt FROM chat_data_fb WHERE chat_msg_id IN ( SELECT Max(chat_msg_id) FROM chat_data_fb WHERE admin_id='$aid' AND first_name != '' GROUP BY sender_id ) ORDER BY chat_msg_id DESC
	 	// $queue_chat_qry = "select chat_data_sms.chat_msg_id, chat_data_sms.read_status,chat_data_sms.chat_message, chat_data_sms.chat_id, date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.customer_name as customer_name, (select first_name from contacts where phone = chat_sms.customer_name and admin_id='$admin_id'  GROUP BY phone ) as cus_name, chat_sms.app_chat_id, chat_sms.api_type from chat_sms inner join chat_data_sms on chat_data_sms.chat_id = chat_sms.chat_id where chat_data_sms.chat_msg_id in (select max(chat_msg_id) from chat_data_sms group by chat_id order by chat_msg_id desc) and chat_sms.admin_id='$admin_id' $search_qry order by chat_data_sms.chat_msg_id desc";	 

	 // print_r($chat_detail_qry); exit;


	    /*$chat_detail_qry = "SELECT chat_msg_id,from_id,first_name,last_name,profile_pic,page_name,page_picture,admin_id,chat_message,message_attachment,delivered_status,date_format(created_at, '%d/%m/%Y') as chat_dt,date_format(created_at, '%H:%i') as Time,extension,agent_name,profile_picture FROM chat_data_fb WHERE admin_id='".$aid."' AND sender_id='".$sender_id."'";
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;*/ 
    }
  
  function fb_single_chat($chat_data){
    extract($chat_data);//print_r($chat_data);exit;
	  $update="UPDATE chat_data_fb SET read_status='0' where sender_id='$sender_id'";
	  $qry_result = $this->db_query($update, array());
    $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
    $admin_id = $this->fetchOne($admin_id_qry,array());
    if($admin_id==1){
      $aid = $user_id;
    }else{
      $aid = $admin_id;
    }   
	$chat_detail_qry = "SELECT chat_msg_id,from_id,first_name,last_name,profile_pic,page_name,page_picture,admin_id,chat_message,message_attachment,delivered_status,date_format(created_at, '%d/%m/%Y') as chat_dt,date_format(created_at, '%H:%i') as Time,extension,agent_name,profile_image,read_status,bot_status FROM chat_data_fb WHERE admin_id='".$aid."' AND sender_id='".$sender_id."'";
	//echo  $chat_detail_qry;exit; 
    $parms = array();
    $result = $this->dataFetchAll($chat_detail_qry,array());
    return $result; 
    }
  
  

/*function fb_single_chat($chat_data){
    extract($chat_data);//print_r($chat_data);exit;
	  $update="UPDATE chat_data_fb SET read_status='0' where sender_id='$sender_id'";
	  $qry_result = $this->db_query($update, array());
    $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
    $admin_id = $this->fetchOne($admin_id_qry,array());
    if($admin_id==1){
      $aid = $user_id;
    }else{
      $aid = $admin_id;
    }   
    //$chat_detail_qry = "SELECT chat_msg_id,from_id,first_name,last_name,profile_pic,page_name,page_picture,admin_id,chat_message,message_attachment,delivered_status,date_format(created_at, '%d/%m/%Y') as chat_dt,date_format(created_at, '%H:%i') as Time,extension FROM chat_data_fb WHERE admin_id='".$aid."' AND sender_id='".$sender_id."'";
	  $chat_detail_qry = "SELECT chat_msg_id,from_id,first_name,last_name,profile_pic,page_name,page_picture,admin_id,chat_message,message_attachment,delivered_status,date_format(created_at, '%d/%m/%Y') as chat_dt,date_format(created_at, '%H:%i') as Time,extension,user_id FROM chat_data_fb WHERE admin_id='".$aid."' AND sender_id='".$sender_id."'";
    $parms = array();
    $result = $this->dataFetchAll($chat_detail_qry,array());
    for($i = 0; count($result) > $i; $i++){
      $chat_msg_id = $result[$i]['chat_msg_id'];
      $userId = $result[$i]['user_id'];
      $from_id = $result[$i]['from_id'];
      $first_name = $result[$i]['first_name'];
      $last_name = $result[$i]['last_name'];
      $profile_pic = $result[$i]['profile_pic'];
      $page_name = $result[$i]['page_name'];
      $page_picture = $result[$i]['page_picture'];
      $admin_id = $result[$i]['admin_id'];
      $chat_message = $result[$i]['chat_message'];
      $message_attachment = $result[$i]['message_attachment'];
      $delivered_status = $result[$i]['delivered_status'];
      $chat_dt = $result[$i]['chat_dt'];
      $Time = $result[$i]['Time'];
      $extension = $result[$i]['extension'];
      $profile_qry = "SELECT agent_name,profile_image FROM user WHERE user_id='$userId'";
      $user_data_value = $this->fetchsingledata($profile_qry, array(":user_id"=>$userId));
      $agent_name = $user_data_value['agent_name'];
      $profile_image = $user_data_value['profile_image'];
      $website_options = array('chat_msg_id' => $chat_msg_id, 'from_id' => $from_id, 'first_name' => $first_name, 'last_name' => $last_name, 'profile_pic' => $profile_pic, 'page_name' => $page_name, 'page_picture' => $page_picture, 'admin_id' => $admin_id, 'chat_message' => $chat_message,'message_attachment' => $message_attachment, 'delivered_status' => $delivered_status, 'chat_dt' => $chat_dt, 'Time' => $Time, 'extension' => $extension, 'agent_name' => $agent_name,'profile_image' => $profile_image);
      $website_options_array[] = $website_options;
    }
	$sts = 'true';
	$status = array('status' => $sts);
    $website_options_array = array('data' => $website_options_array);
    $options_array = array('result' => $website_options_array);
    $merge_result = array_merge($status, $options_array);
    $tarray = json_encode($merge_result);           
    print_r($tarray);exit; 
}*/

	
	
  function fb_reply_message($chat_data,$media){
	//echo $media;exit;
	  //print_r($chat_data);exit;
      extract($chat_data);//print_r($chat_data);exit;
      $admin_id_qry = "SELECT admin_id,timezone_id FROM user WHERE user_id='$user_id'";
      $admin_data = $this->fetchData($admin_id_qry,array());
      $admin_id = $admin_data['admin_id'];
      $timezone_id = $admin_data['timezone_id'];
      if($admin_id==1){
        $aid = $user_id;
      }else{
        $aid = $admin_id;
      }
	  $details_qry = "SELECT first_name,last_name,page_name FROM chat_data_fb WHERE sender_id='$sender_id' ORDER BY chat_msg_id ASC LIMIT 1";
	  $rec = $this->fetchData($details_qry,array());
      $first_name = $rec['first_name'];
	  $last_name = $rec['last_name'];
      $page_name = $rec['page_name'];
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchOne($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
	  //echo "SELECT first_name,last_name,profile_pic,admin_id,page_name,page_picture,access_token FROM chat_data_fb WHERE sender_id='$sender_id' ORDER BY chat_msg_id ASC LIMIT 1";exit;
      $sender_details_qry = "SELECT first_name,last_name,profile_pic,admin_id,page_name,page_picture,access_token FROM chat_data_fb WHERE sender_id='$sender_id' ORDER BY chat_msg_id DESC LIMIT 1";
      $sender_details = $this->fetchData($sender_details_qry,array());
	  //print_r($sender_details);
     // $first_name = $sender_details['first_name'];
     // $last_name = $sender_details['last_name'];
      $profile_pic = $sender_details['profile_pic'];
      $sender_adminid = $sender_details['admin_id']; 
      $access_token= $sender_details['access_token']; 
    //$page_name= $sender_details['page_name'];
    $page_picture= $sender_details['page_picture'];
	  
	  $chat_message = str_replace("\n",'%2%',$chat_message); 
		 
		 $chat_message = str_replace("%2%",'\n',$chat_message); 
	  //echo $chat_message;exit;
		
			 
    if(isset($_FILES["facebook_media"])){
		//echo"njcjc";exit;
		
		$media_extension = pathinfo($media, PATHINFO_EXTENSION);
		if($media_extension=='jpg'||$media_extension=='jpeg'||$media_extension=='jpe'|| $media_extension=='jif'||$media_extension=='jfif'||$media_extension=='png')
				 $type='image';
		else $type='file';
		
      $reply_message="{
                   'recipient': {
                      'id': '$sender_id'
                    },
                   'message':{    
                      'attachment': {
                              'type': '$type',
                            'payload': {
                            'url'  :'$media'
                                        }
                                  }
                             }
               }"; 

     }
     else
     {
      $reply_message="{
                   'recipient': {
                      'id': '$sender_id'
                    },
                   'message':{    
                      'text': '$chat_message'
                    }
               }"; 

        } 
	 //echo $reply_message;
	 // echo $whatsapp_media_extension;exit;
      $sr = $this->send_reply($access_token,$reply_message);   
	//print_r($sr); exit;
	 if($sr[recipient_id]){
	//echo "sndjs";exit;
	  $user_qry = "SELECT agent_name,profile_image FROM user WHERE user_id='$user_id'";
      $user_value = $this->fetchsingledata($user_qry, array(":user_id"=>$user_id));
      $agent_name = $user_value['agent_name'];
      $profile_image = $user_value['profile_image']; 	 
      $chat_msg_qry = "INSERT INTO chat_data_fb (`chat_id`,`sender_id`,`from_id`,`chat_message`,`read_status`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`access_token`,`profile_pic`,`page_picture`,`first_name`,`last_name`,page_name,`user_id`,`agent_name`,`profile_image`) VALUES ('$chat_id','$sender_id','admin','$chat_message','1','$media','1','$aid','1','$created_at','$access_token','$profile_pic','$page_picture','$first_name','$last_name','$page_name','$user_id','$agent_name','$profile_image')";
      	// echo $chat_msg_qry;exit;

		 $userqry_result = $this->db_query($chat_msg_qry, array());  
	
      $output = $userqry_result == 1 ? 1 : 0;
	//echo $output;exit;
    $mc_event_data = "Facebook Message To ".$first_name.$last_name;      
    $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,page_name,page_picture) VALUES('$sender_id','$aid','$mc_event_data','7','7','$created_at','$page_name','$page_picture')", array()); 
	    if(isset($_FILES["facebook_media"])){
		   $result = array( "status" => "true", "output" => $output ); 
             $tarray = json_encode($result); print_r($tarray);exit;
			//return $output;
	  }else{
		  return $output;
	  }
	 }
	  else{
		  $output=0;
		 if(isset($_FILES["facebook_media"])){
		   $result = array( "status" => "false", "output" => $output ); 
              $tarray = json_encode($result); print_r($tarray);exit;
	  }else{
		  return $output;
	  }
	  }
    }

 /*public function fb_reply_media_upload($chat_data){
       extract($chat_data);
	   $user_timezone_id = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
	   $user_timezoneid = $this->fetchmydata($user_timezone_id,array());
	   $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezoneid'";
	   $user_timezone = $this->fetchmydata($user_timezone_qry,array());
	   date_default_timezone_set($user_timezone);
	   $created_at = date("Y-m-d H:i:s");
      if(isset($_FILES["facebook_media"]))
        {
		 // echo "1234";exit;
          $whatsapp_media_info = getimagesize($_FILES["facebook_media"]["tmp_name"]);                    
          $whatsapp_media_extension = pathinfo($_FILES["facebook_media"]["name"], PATHINFO_EXTENSION);                 
        $destination_path = getcwd().DIRECTORY_SEPARATOR;            
        $facebook_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/facebook_image/". basename( $_FILES["facebook_media"]["name"]);  
        $facebook_media_target_path = $destination_path."facebook_image/". basename( $_FILES["facebook_media"]["name"]);
        move_uploaded_file($_FILES['facebook_media']['tmp_name'], $facebook_media_target_path); 
      
       $details_qry = "SELECT first_name,last_name,page_name FROM chat_data_fb WHERE sender_id='$sender_id' ORDER BY chat_msg_id ASC LIMIT 1";
	  $rec = $this->fetchData($details_qry,array());
      $first_name = $rec['first_name'];
	  $last_name = $rec['last_name'];
      $page_name = $rec['page_name'];
           $sender_details_qry = "SELECT profile_pic,admin_id,page_picture,access_token FROM chat_data_fb WHERE sender_id='$sender_id' ORDER BY chat_msg_id DESC LIMIT 1";
      $sender_details = $this->fetchData($sender_details_qry,array());     
      $profile_pic = $sender_details['profile_pic'];
      //$sender_adminid = $sender_details['admin_id']; 
      $access_token= $sender_details['access_token']; 
      $page_picture= $sender_details['page_picture'];
      $qry = "INSERT INTO chat_data_fb (`chat_id`,`sender_id`,`from_id`,`read_status`,`message_attachment`,`delivered_status`,`admin_id`,`chat_status`,`created_at`,`access_token`,`profile_pic`,`page_picture`,`first_name`,`last_name`,page_name) VALUES ('$chat_id','$sender_id','admin','1','$facebook_media_upload_path','1','$sender_adminid','1','$created_at','$access_token','$profile_pic','$page_picture','$first_name','$last_name','$page_name')";
        $results = $this->db_query($qry, array());    
		 // echo $results;exit;
        $result = array( "status" => "true", "url" => $facebook_media_upload_path ); 
              $tarray = json_encode($result);  
              print_r($tarray);exit;
             // $this->fb_reply_message($chat_data,$facebook_media_upload_path);
       }
     else{   
		
          $result = array( "status" => "false" ); 
           $tarray = json_encode($result);  
           print_r($tarray);exit;
       }
    }*/

public function fb_reply_media_upload($chat_data){
       extract($chat_data);
 //print_r($tarray);exit;
      if(isset($_FILES["facebook_media"]))
        {
          $whatsapp_media_info = getimagesize($_FILES["facebook_media"]["tmp_name"]);                    
          $whatsapp_media_extension = pathinfo($_FILES["facebook_media"]["name"], PATHINFO_EXTENSION);                 
          $destination_path = getcwd().DIRECTORY_SEPARATOR;            
          $facebook_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/facebook_image/". basename( $_FILES["facebook_media"]["name"]);  
          $facebook_media_target_path = $destination_path."facebook_image/". basename( $_FILES["facebook_media"]["name"]);
          move_uploaded_file($_FILES['facebook_media']['tmp_name'], $facebook_media_target_path); 
          $result = array( "status" => "true", "url" => $facebook_media_upload_path ); 
          $tarray = json_encode($result);  
          //print_r($tarray);exit;
          $this->fb_reply_message($chat_data,$facebook_media_upload_path);
       }
     else
       {   
		      $result = array( "status" => "false" ); 
          $tarray = json_encode($result);  
          print_r($tarray);exit;
       }
    }	
	
    public function whatsapp_media_upload($chat_data){
	
    // print_r($chat_data);exit;
      $user_id = $chat_data['user_id'];
      $chat_id = $chat_data['chat_id'];                         
      if(isset($_FILES["whatsapp_media"]))
        {
          $whatsapp_media_info = getimagesize($_FILES["whatsapp_media"]["tmp_name"]);                    
          $whatsapp_media_extension = pathinfo($_FILES["whatsapp_media"]["name"], PATHINFO_EXTENSION);                 
        $destination_path = getcwd().DIRECTORY_SEPARATOR;            
        $whatsapp_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);  
        $whatsapp_media_target_path = $destination_path."whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);
        move_uploaded_file($_FILES['whatsapp_media']['tmp_name'], $whatsapp_media_target_path); 
		  
        $qry = "INSERT INTO whatsapp_image(chat_id,admin_id,whatsapp_image) VALUES ('$chat_id','$user_id', '$whatsapp_media_upload_path')";
        $results = $this->db_query($qry, array());         
        $result = array( "status" => "true", "url" => $whatsapp_media_upload_path ); 
              $tarray = json_encode($result);  
              print_r($tarray);exit;
       }
     else{           
          $result = array( "status" => "false" ); 
           $tarray = json_encode($result);  
           print_r($tarray);exit;
       }
    }
	
	
	 function ComposeMessage($chat_data){        
      extract($chat_data);//print_r($chat_data);exit;
      $phone = $country_code.$phone_num;
      $sid    = "ACcf360292ecffb40031e510d2e7492490";
      $token  = "b8eeb0ca8db33cb1fefc3ff7017e2cdf";
      $twilio = new Client($sid, $token);
      $message = $twilio->messages
                  ->create("whatsapp:$phone", // to
                           [
                               "from" => "whatsapp:+19854650011",
                               "body" => "$chat_msg"
                           ]
                  ); 
       $wp_msg_id = $message->sid;               
       $qry = "select * from user where user_id='$user_id'";
       $result = $this->fetchData($qry, array());		 
       if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }		 
       $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
       $user_timezone = $this->fetchmydata($user_timezone_qry,array());
       date_default_timezone_set($user_timezone);
       $created_at = date("Y-m-d H:i:s");
		 $qry = "select * from chat_wp where app_customer_key ='$phone'";  
       $results = $this->fetchData($qry, array());
	   if($results > 0){     
            $chat_id=$results['chat_id'];		   
            $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());                
              $mc_event_data = "whatsapp Message By Admin";
                  $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
              $result = array( "status" => "true", "chat_id" => $chat_id ); 
                $tarray = json_encode($result);  
                print_r($tarray);exit;     
      } else{	 
       $chat_id = $this->db_insert("INSERT INTO `chat_wp` (`app_chat_id`, `app_customer_key`, `customer_name`,  `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`) VALUES ('$phone', '$phone', '$phone', 'sms', '0', '1', '1', '$admin_id', '$created_at')", array());   
		 //print_r(); exit;
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status, wp_msg_id, created_dt) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());
		 //echo $chat_msg_id;exit;
        //$chat_data = $this->getwhatsappDetails($chat_id);
        $mc_event_data = "whatsapp Message By Admin";
        $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
        $result = array( "status" => "true", "chat_id" => $chat_id ); 
              $tarray = json_encode($result);  
              print_r($tarray);exit;   
	   }
    }

    function getwhatsappDetails($chat_msg_id){         
        $chat_detail_qry = "select * from (select chat_data_wp.chat_msg_id, chat_data_wp.delivered_status as msg_status, chat_data_wp.msg_from as msg_user_type,chat_data_wp.chat_id,chat_data_wp.chat_message as chat_msg,date_format(chat_data_wp.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp.created_dt, '%H:%i') as chat_time, chat_wp.app_customer_key as customer_name, users.user_name,chat_data_wp.agent_id as msg_user_id, chat_wp.api_type as msg_type from chat_data_wp inner join chat_wp on chat_wp.chat_id = chat_data_wp.chat_id left join user as users on users.user_id = chat_data_wp.agent_id where chat_wp.chat_id LIKE '".$chat_msg_id."' order by chat_data_wp.chat_msg_id desc) result_data order by chat_msg_id asc";
              // $this->errorLog("demo",$chat_detail_qry);
           //print_r( $chat_detail_qry); exit;
           $parms = array();
           $result = $this->dataFetchAll($chat_detail_qry,array());
           return $result;   
       }
       
       function ComposeGroupWhatsappMessage($chat_data){
           extract($chat_data); 
           $qry = "select group_users from sms_group where group_id ='$group' and admin_id = '$admin_id'";
           $g_contact = $this->fetchData($qry, array());
           $contacts = $g_contact['group_users'];
           $qry = "SELECT phone FROM `contacts` WHERE `contact_id` IN ($contacts) ORDER BY `contact_id` DESC";
           $result = $this->dataFetchAll($qry, array());
           foreach ($result as $key => $value) {
               $chat_data = $this->bulkWhatsapp($value['phone'],$chat_msg,$admin_id,$user_id);
           }   
           return $chat_data;
       }
       function bulkWhatsapp($phone_num,$chat_msg,$admin_id,$user_id){      
         //$phone = $country_code.$phone_num;
           //echo $phone_num;exit;
         $sid    = "ACcf360292ecffb40031e510d2e7492490";
         $token  = "b8eeb0ca8db33cb1fefc3ff7017e2cdf";
         $twilio = new Client($sid, $token);
         $message = $twilio->messages
                     ->create("whatsapp:$phone_num", // to
                              [
                                  "from" => "whatsapp:+19854650011",
                                  "body" => "$chat_msg"
                              ]
                     ); 
          $wp_msg_id = $message->sid;
           //echo $wp_msg_id;exit;       
          $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
          $user_timezone = $this->fetchmydata($user_timezone_qry,array());
          date_default_timezone_set($user_timezone);
          $created_at = date("Y-m-d H:i:s");
          $qry = "select * from chat_wp where app_customer_key ='$phone_num'";  
          $results = $this->fetchData($qry, array());  
           if($results > 0){     
               $chat_id=$results['chat_id'];		   
               $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());                
                 $mc_event_data = "whatsapp Message By Admin";
                     $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
                 $result = array( "status" => "true", "chat_id" => $chat_id ); 
                   $tarray = json_encode($result);return $tarray;  
                   //print_r($tarray);exit;     
         } else{	 
          $chat_id = $this->db_insert("INSERT INTO `chat_wp` (`app_chat_id`, `app_customer_key`, `customer_name`,  `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`) VALUES ('$phone', '$phone', '$phone', 'sms', '0', '1', '1', '$admin_id', '$created_at')", array());   
            //print_r(); exit;
           $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status, wp_msg_id, created_dt) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());
            //echo $chat_msg_id;exit;
           //$chat_data = $this->getwhatsappDetails($chat_id);
           $mc_event_data = "whatsapp Message By Admin";
           $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
           $result = array( "status" => "true", "chat_id" => $chat_id ); 
                 $tarray = json_encode($result); return $tarray; 
                 //print_r($tarray);exit;   
          }      
       }
       
       public function bulk_whatsapp_upload($chat_data){
       // print_r( $chat_data);exit;
         $user_id = $chat_data['user_id'];
         $admin_id = $chat_data['admin_id'];
         $timezone_id = $chat_data['timezone_id'];
         //$chat_id = $chat_data['chat_id'];
         $group = $chat_data['group'];
         $chat_msg = $chat_data['chat_msg'];                         
         if(isset($_FILES["whatsapp_media"]))
           {
             $whatsapp_media_info = getimagesize($_FILES["whatsapp_media"]["tmp_name"]);                    
             $whatsapp_media_extension = pathinfo($_FILES["whatsapp_media"]["name"], PATHINFO_EXTENSION);                 
             $destination_path = getcwd().DIRECTORY_SEPARATOR;            
             $whatsapp_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);  
             $whatsapp_media_target_path = $destination_path."whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);
             move_uploaded_file($_FILES['whatsapp_media']['tmp_name'], $whatsapp_media_target_path);       
             $qry = "INSERT INTO whatsapp_image(chat_id,admin_id,whatsapp_image) VALUES ('0','$user_id', '$whatsapp_media_upload_path')";
             $results = $this->db_query($qry, array());        
          }
          $qry = "select group_users from sms_group where group_id ='$group' and admin_id = '$admin_id'";
           $g_contact = $this->fetchData($qry, array());
           $contacts = $g_contact['group_users'];
           $qry = "SELECT phone FROM `contacts` WHERE `contact_id` IN ($contacts) ORDER BY `contact_id` DESC";
           $result = $this->dataFetchAll($qry, array());
           foreach ($result as $key => $value) {
               $chat_data = $this->bulkWhatsappMedia($value['phone'],$chat_msg,$admin_id,$user_id,$whatsapp_media_upload_path,$timezone_id);
           }   
           //print_r( $result);exit;
           return $chat_data;     
       }
   
       function bulkWhatsappMedia($phone_num,$chat_msg,$admin_id,$user_id,$whatsapp_media_upload_path,$timezone_id){     
           //echo $whatsapp_media_upload_path; exit;
         $sid    = "ACcf360292ecffb40031e510d2e7492490";
         $token  = "b8eeb0ca8db33cb1fefc3ff7017e2cdf";
         $twilio = new Client($sid, $token);      
		   
		    $qry = "SELECT * FROM `admin_details` WHERE `admin_id` LIKE '$admin_id'";
           $result = $this->fetchData($qry, array());   
           $whatsapp_account = $result['whatsapp_account'];
           if($whatsapp_account == '0'){
               $admin_num = '+14155238886';
           } else {
               $admin_num = $result['whatsapp_num'];
           }
		   
		   
		   
         if($whatsapp_media_upload_path != '' && $chat_msg !=''){
         $message = $twilio->messages
                           ->create("whatsapp:$phone_num", // to
                                    [
                                        "mediaUrl" => ["$whatsapp_media_upload_path"],
                                        "from" => "whatsapp:$admin_num",
                                        "body" => "$chat_msg"
                                    ]
                         );
         }elseif($whatsapp_media_upload_path == '' && $chat_msg !=''){
         $message = $twilio->messages
                           ->create("whatsapp:$phone_num", // to
                                    [                               
                                        "from" => "whatsapp:$admin_num",
                                        "body" => "$chat_msg"
                                    ]
                         );  
         }else{
         $message = $twilio->messages
                           ->create("whatsapp:$phone_num", // to
                                    [                               
                                        "mediaUrl" => ["$whatsapp_media_upload_path"],
                                        "from" => "whatsapp:$admin_num",
                                    ]
                         );  
         }             
         $wp_msg_id = $message->sid;
       //echo $wp_msg_id;exit;       
          $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
          $user_timezone = $this->fetchmydata($user_timezone_qry,array());
          date_default_timezone_set($user_timezone);
          $created_at = date("Y-m-d H:i:s");
          $qry = "select * from chat_wp where app_customer_key ='$phone_num'";  
          $results = $this->fetchData($qry, array());  
           if($results > 0){     
               $chat_id=$results['chat_id'];      
                $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT','1','$wp_msg_id','$created_at','$whatsapp_media_upload_path')", array());  
               
               
                 $mc_event_data = "whatsapp Message By Admin";
                     $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
               
             $result = array( "status" => "true", "chat_id" => $chat_id ); 
                   $tarray = json_encode($result); 
                  print_r($tarray);exit;     
         } else{  
          $chat_id = $this->db_insert("INSERT INTO `chat_wp` (`app_chat_id`, `app_customer_key`, `customer_name`,  `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`) VALUES ('$phone', '$phone', '$phone', 'sms', '0', '1', '1', '$admin_id', '$created_at')", array());   
      
           $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status, wp_msg_id, created_dt) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at')", array());
       
           $mc_event_data = "whatsapp Message By Admin";
           $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id',$mc_event_data','5','7','$created_at')", array());
               
               //print_r(  $result ); exit;
           $result = array( "status" => "true", "chat_id" => $chat_id ); 
                 $tarray = json_encode($result);
               //return $tarray; 
               print_r($tarray);exit;   
        } 
       }
       
       public function single_whatsapp_media_upload($chat_data){
       // print_r($chat_data);exit;
   
   
   
         $user_id = $chat_data['user_id'];
         $admin_id = $chat_data['admin_id'];
         $timezone_id = $chat_data['timezone_id'];      
         $country_code = $chat_data['country_code'];
         $phone_num = $chat_data['phone_num'];
         $chat_msg = $chat_data['chat_msg'];    
         $whatsapp_media_upload_path = '';
       
           $qry = "SELECT * FROM `admin_details` WHERE `admin_id` LIKE '$admin_id'";
           $result = $this->fetchData($qry, array());   
           $whatsapp_account = $result['whatsapp_account'];
           if($whatsapp_account == '0'){
               $admin_num = '+14155238886';
           } else {
               $admin_num = $result['whatsapp_num'];
           }
         if(isset($_FILES["whatsapp_media"]))
           {
             $whatsapp_media_info = getimagesize($_FILES["whatsapp_media"]["tmp_name"]);                    
             $whatsapp_media_extension = pathinfo($_FILES["whatsapp_media"]["name"], PATHINFO_EXTENSION);                 
           $destination_path = getcwd().DIRECTORY_SEPARATOR;            
           $whatsapp_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);  
           $whatsapp_media_target_path = $destination_path."whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);
           move_uploaded_file($_FILES['whatsapp_media']['tmp_name'], $whatsapp_media_target_path);       
           $qry = "INSERT INTO whatsapp_image(chat_id,admin_id,whatsapp_image) VALUES ('0','$user_id', '$whatsapp_media_upload_path')";
           $results = $this->db_query($qry, array());       
          }
          $phone = $country_code.$phone_num;      
          $sid    = "ACcf360292ecffb40031e510d2e7492490";
          $token  = "b8eeb0ca8db33cb1fefc3ff7017e2cdf";
          $twilio = new Client($sid, $token);
          if($whatsapp_media_upload_path != '' && $chat_msg != 'null'){
          $message = $twilio->messages
                           ->create("whatsapp:$phone", // to
                                    [
                                        "mediaUrl" => ["$whatsapp_media_upload_path"],
                                        "from" => "whatsapp:$admin_num",
                                        "body" => "$chat_msg"
                                    ]
                         );
          }elseif($whatsapp_media_upload_path == '' && $chat_msg !=''){
          $message = $twilio->messages
                           ->create("whatsapp:$phone", // to
                                    [                               
                                        "from" => "whatsapp:$admin_num",
                                        "body" => "$chat_msg"
                                    ]
                         );  
          }else{//echo $whatsapp_media_upload_path;exit;
          $message = $twilio->messages
                     ->create("whatsapp:$phone", // to
                              [
                                 
                                  "from" => "whatsapp:$admin_num",
                                  "body" => "$chat_msg"
                              ]
                     );  
          } 
          $wp_msg_id = $message->sid;     
           // echo $wp_msg_id; exit;
          $qry = "select * from user where user_id='$user_id'";
          $result = $this->fetchData($qry, array());
          if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
          $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
          $user_timezone = $this->fetchmydata($user_timezone_qry,array());
          date_default_timezone_set($user_timezone);
          $created_at = date("Y-m-d H:i:s");
          $qry = "select * from chat_wp where app_customer_key ='$phone'";  
          $results = $this->fetchData($qry, array());  
       
          if($results > 0){     
               $chat_id=$results['chat_id'];     
           
               $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT','1','$wp_msg_id','$created_at','$whatsapp_media_upload_path')", array());                
                 $mc_event_data = "whatsapp Message From".$from;
                     $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
                 $result = array( "status" => "true", "chat_id" => $chat_id ); 
                   $tarray = json_encode($result);  
                 print_r($tarray);exit;     
         } else{  
       
          $chat_id = $this->db_insert("INSERT INTO `chat_wp` (`app_chat_id`, `app_customer_key`, `customer_name`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`) VALUES ('$admin_num', '$phone', '$phone', 'sms', '0', '1', '1', '$admin_id','$created_at')", array());
                
           $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$user_id', 'agent', 'text', '$chat_msg','SENT', '1','$wp_msg_id','$created_at','$whatsapp_media_upload_path')", array());        
           $chat_data = $this->getChatDetails($chat_msg_id);
              
              
           $mc_event_data = "whatsapp Message By Admin";
           $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
               $result = array( "status" => "true"); 
                   $tarray = json_encode($result);  
                 print_r($tarray);exit;
           }     
       }
	
	function insert_sms_tarrif($data){
			extract($data);
			$sel_col="SELECT tarrif_name FROM `sms_plans` where id='$plan_id'";
		$tarrif_name = $this->fetchOne($sel_col, array());
		
		   $qry = "Update country SET $tarrif_name='$price' where  id='$id'";
           $qry_result = $this->db_query($qry, array());
		   $result = $qry_result == 1 ? 1 : 0;
			return $result;
	}
	function getsms_tarrif(){
		//echo '123';exit;
		  $sel = "SELECT * FROM `country`";
		 $result = $this->dataFetchAll($sel, array());
		return $result;
	}
	function add_new_tarrif($chat_data){
			extract($chat_data);
		$plan_name=$tarrif_name;
		$tarrif_name = str_replace(' ', '_', $tarrif_name);
		     $qry = "ALTER TABLE country ADD $tarrif_name  varchar(255) NOT NULL DEFAULT 0";
		$qry_result = $this->db_query($qry, array());
		
		$chat_msg_id = $this->db_insert("INSERT INTO sms_plans(plan_name,tarrif_name, created_dt) VALUES ('$plan_name','$tarrif_name',CURRENT_TIMESTAMP)", array());
		  // $result = $qry_result == 1 ? 1 : 0;
			return $chat_msg_id;
	}
function del_tarrif($chat_data){
			extract($chat_data);
		$plan_name=$tarrif_name;
		$tarrif_name = str_replace(' ', '_', $tarrif_name);
	   $sel = "SELECT tarrif_name FROM `sms_plans` where id='$plan_name'";
		$del_name = $this->fetchOne($sel, array());
	 $qry = "ALTER TABLE country DROP COLUMN $del_name";
		$qry_result = $this->db_query($qry, array());
	$chat_msg_id = $this->db_query("DELETE FROM sms_plans where tarrif_name='$del_name'", array());
	$result = $qry_result == 1 ? 1 : 0;
			return $result;
		
	}
	function view_tarrif(){
		//echo '123';exit;
		  $sel = "SELECT id,plan_name  FROM `sms_plans`";
		$get_default_value="SELECT id,name,phonecode,sms_tarrif as tarrif  FROM country";
		 $result['plans'] = $this->dataFetchAll($sel, array());
		 $result['def_plan'] = $this->dataFetchAll($get_default_value, array());
		return $result;
	}
	function get_sel_tarrif($chat_data){
		extract($chat_data);
		 $sel = "SELECT tarrif_name as plan FROM `sms_plans` where id='$id'";
		$tarrif_name = $this->fetchOne($sel, array());
		$get_plan="SELECT id,name,phonecode,$tarrif_name as tarrif FROM country";
		 $result = $this->dataFetchAll($get_plan, array());
		return $result;
    }
    




//unofficial whatsapp



function ComposeIncommingChatMessageUnoff($chat_data){
        extract($chat_data);	  


    $qry = "SELECT * FROM `admin_details` WHERE `whatsapp_num` LIKE '$to'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];

    $userqry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
    $userresult = $this->fetchData($userqry, array());    
    $timezone_id = $userresult['timezone_id'];
	$admintoken = $userresult['notification_code'];
	$profile_image = $userresult['profile_image'];
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
	  $chat_name="SELECT contact_owner FROM `contacts` where whatsapp_number='$from'";
		$name_wp = $this->fetchOne($chat_name, array());   
		if($name_wp==''){
			$from_name=$from;
		}else{
			$from_name=$name_wp;
		}
     $qry = "select * from chat_wp_uf where app_customer_key ='$from' and admin_id= '$admin_id'";  

       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','$instance_num')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;
                $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;      
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`) VALUES ('$to','$from', '$from','sms','$instance_num','0','1','1','$admin_id','$created_at')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$instance_num','$created_at')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;     
                $this->db_query("INSERT INTO mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) VALUES('$chat_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;  
    }  
    }
  

 function chatDetailListUOFF($chat_id,$limit,$offset){
        
 
		$update="UPDATE chat_data_wp_uf SET read_status='1' where chat_id='$chat_id'";
		//print_r($update); exit;
		$qry_result = $this->db_query($update, array());
     
     
          $chat_detail_qry = "select * from (select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.whatsapp_media_url, chat_data_wp_uf.delivered_status as msg_status, chat_data_wp_uf.msg_from as msg_user_type,chat_data_wp_uf.chat_id,chat_data_wp_uf.chat_message as chat_msg,date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, chat_wp_uf.app_customer_key as customer_number,(SELECT name FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_name,(SELECT image_path FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_image,chat_wp_uf.group_name as group_name,chat_wp_uf.group_icon as group_icon,chat_wp_uf.user_id, chat_wp_uf.f_user_id, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.f_user_id) as f_user_nm,(SELECT agent_name FROM user WHERE user_id= chat_data_wp_uf.agent_id) as replied_by,(SELECT profile_image FROM user WHERE user_id= chat_data_wp_uf.agent_id) as replied_agent_img, chat_data_wp_uf.chat_pnr as chat_pnr,chat_data_wp_uf.customer_name as group_sender_name,(SELECT agent_name FROM user WHERE user_id= chat_wp_uf.user_id) as user_name,chat_data_wp_uf.agent_id as msg_user_id, chat_wp_uf.api_type as msg_type from chat_data_wp_uf inner join chat_wp_uf on chat_wp_uf.chat_id = chat_data_wp_uf.chat_id left join user as users on users.user_id = chat_data_wp_uf.agent_id where chat_wp_uf.chat_id LIKE '".$chat_id."' order by chat_data_wp_uf.chat_msg_id desc LIMIT $limit OFFSET $offset) result_data order by chat_msg_id asc";


         //print_r($chat_detail_qry); exit;
            // $this->errorLog("demo",$chat_detail_qry);
         $parms = array();
         $result = $this->dataFetchAll($chat_detail_qry,array());
	
		 foreach($result as &$v) {
				  $v['chat_msg'] = str_replace("/////", "'", $v['chat_msg']);
				}
	
         return $result;
     
     }






     function getcustomersChatUOFF($user_id,$queue_id,$search_text){
       
    
        $qry = "select * from user where user_id='$user_id'";
    
            $result = $this->fetchData($qry, array());
    
           if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
    
     


         $search_qry ="";
        if($search_text != ""){
        $search_qry .= "and customer.customer_name like '%".$search_text."%'";
        }
         
         
         if($queue_id == null || $queue_id==""){
             
             $queue_condtion = "select queue.queue_id from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and  FIND_IN_SET('1', q_user.queue_feature) and q_user.queue_user_status = '1' and queue.queue_status = '1'";
             
             
         }
         else{
             $queue_condtion = $queue_id;
         }
         
         //$queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id, chat_data_wp_uf.chat_message, date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, chat_wp_uf.customer_name as customer_name, chat_wp_uf.app_chat_id, chat_wp_uf.api_type from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_data_wp_uf.chat_msg_id in (select max(chat_msg_id) from chat_data_wp_uf group by chat_id order by chat_msg_id desc) and chat_wp_uf.admin_id='$admin_id' order by chat_data_wp_uf.chat_msg_id desc";
     
     
     
            $qry = "select * from user where user_id='$admin_id'";
		// print_r($qry); exit;
    
            $result = $this->fetchData($qry, array());
     
     $wathsapp_num = $result['whatsapp_num'];

     
     $queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id, chat_data_wp_uf.chat_message, date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, chat_wp_uf.customer_name as customer_name, chat_wp_uf.app_chat_id, chat_wp_uf.api_type from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_data_wp_uf.chat_msg_id in (select max(chat_msg_id) from chat_data_wp_uf group by chat_id order by chat_msg_id desc) and chat_wp_uf.admin_id != '' and chat_wp_uf.app_chat_id = '$wathsapp_num' order by chat_data_wp_uf.chat_msg_id desc";


     //  print_r($queue_chat_qry); exit;
         
         $parms = array();
         $result = $this->dataFetchAll($queue_chat_qry,array());
    
         return $result;
         
    
     }


     function insertChatMessageOff($chat_data){        
        extract($chat_data);
        $qry = "select * from chat_wp_uf where chat_id='$chat_id'";
        $result = $this->fetchData($qry, array());	
        $mobile_num = $result['app_customer_key'];
        $admin_num = $result['app_chat_id'];
        $instance_num = $result['chat_instance'];

     
        if($whatsapp_media_url==''){
              $postfields = '{"phone":"'.$mobile_num.'","body": "'.$chat_msg.'"}';
                   $curl = curl_init();
                   curl_setopt($curl,CURLOPT_URL,"https://whatsapp.mconnectapps.com/$instance_num/sendMessage?token=j19ksi1mim1lksm12213");
                   curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
                   curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                   curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                   curl_setopt($curl, CURLOPT_TIMEOUT, 40);
                   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                   $message=curl_exec($curl);
                   $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                   curl_close($curl); 
			$message = json_decode($message);
			$msg_status = $message->status;
			if($msg_status == '1'){
			
			} else {
				return '{"status":"false"}';
			}
		
				 
                 
         }else{  
    
          $postfields = '{"phone":"'.$mobile_num.'","body": "'.$whatsapp_media_url.'", "filename": "omni_whatsapp.jpg"}';
    
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,"https://whatsapp.mconnectapps.com/$instance_num/sendMessage?token=j19ksi1mim1lksm12213");
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($curl, CURLOPT_TIMEOUT, 40);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $message=curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);   
			$message = json_decode($message);
			$msg_status = $message->status;
			if($msg_status == '1'){
			
			} else {
				return '{"status":"false"}';
			}
		
        }
        $qry = "SELECT * FROM `user` WHERE `user_id` ='$agent_id'";
        $result = $this->fetchData($qry, array());
        $timezone_id = $result['timezone_id'];
        $admin_id = $result['admin_id'];
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
       // echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
        $created_at = date("Y-m-d H:i:s"); 	
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id, agent_id, msg_from, msg_type, chat_message,	delivered_status, chat_status, created_dt,whatsapp_media_url,chat_instance) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', '$chat_msg', 'SENT', '1', '$created_at', '$whatsapp_media_url','$instance_num')", array());        
           $chat_data = $this->getChatDetails($chat_msg_id);
        $mc_event_data = "whatsapp Message By Admin";
        $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
         return $chat_data;         
     }

     function insertChatMessageOff2($chat_data){   
		
        extract($chat_data);
        

 		$qry = "select * from user where user_name='$username' and company_name='$company'";
        $results = $this->fetchData($qry, array());
		 
		$admin_id = $results['user_id'];

		$mobile_num = $msg_to;
		 $adm_num= $results['whatsapp_num'];
		 

 

            $postfields = '{"phone":"'.$mobile_num.'","body": "'.$chat_msg.'"}';
		
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,"https://whatsapp.mconnectapps.com/83430/sendMessage?token=j19ksi1mim1lksm12213");
		 curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
		 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		 curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		 curl_setopt($curl, CURLOPT_TIMEOUT, 40);
		 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		 $message=curl_exec($curl);
		 $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		 curl_close($curl); 
			$message = json_decode($message);
			$msg_status = $message->status;
			if($msg_status == '1'){
			
			} else {
				return '{"status":"false"}';
			}
		
    
        $qry = "select * from chat_wp_uf where app_customer_key ='$msg_to' and admin_id= '$admin_id'";  
 
       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance) VALUES ('$chat_id','text', 'customer','$admin_id', '$chat_msg','1', '1','$created_at','83430')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;
                $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;      
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`) VALUES ('$adm_num','$msg_to', '$msg_to','sms','83430','0','1','1','$admin_id','$created_at')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt) VALUES ('$chat_id','text', 'customer','$admin_id', '$chat_msg','1', '1','83430','$created_at')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;     
                $this->db_query("INSERT INTO mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) VALUES('$chat_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;  
           
     }
    
   }
	
				
	
				     function insertChatMessageOffFiles($chat_data){   
		
        extract($chat_data);

						 
				//print_r($chat_data); exit;
 		$qry = "select * from user where user_name='$username' and company_name='$company'";
        $results = $this->fetchData($qry, array());
		 
		$admin_id = $results['user_id'];

		$mobile_num = $msg_to;
		$adm_num= $results['whatsapp_num'];
		$whatsapp_media_url = $chat_msg;
						 
				


		 
		   $postfields = '{"phone":"'.$mobile_num.'","body": "'.$whatsapp_media_url.'", "filename": "omni_whatsapp.jpg"}';

		
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,"https://whatsapp.mconnectapps.com/83430/sendMessage?token=j19ksi1mim1lksm12213");
		 curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
		 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		 curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		 curl_setopt($curl, CURLOPT_TIMEOUT, 40);
		 curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		 $message=curl_exec($curl);
		 $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		 curl_close($curl); 
			$message = json_decode($message);
			$msg_status = $message->status;
			if($msg_status == '1'){
			
			} else {
				//return '{"status":"false"}';
			}
		
    
        $qry = "select * from chat_wp_uf where app_customer_key ='$msg_to' and admin_id= '$admin_id'";  
 
       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$admin_id', '$caption','1', '1','$created_at','83430','$whatsapp_media_url')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;
                $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;      
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`) VALUES ('$adm_num','$msg_to', '$msg_to','sms','83430','0','1','1','$admin_id','$created_at')", array());   
		
				
				
				
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$admin_id', '$caption','1', '1','83430','$created_at','$whatsapp_media_url')", array());
		
				
				
          $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$from_name;     
                $this->db_query("INSERT INTO mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) VALUES('$chat_id','$mc_event_data','5','7','$created_at')", array());
		    $this->notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->notification_curl($token,$chat_id,$chat_msg);
			}
            return $chat_data;  
           
     }
    
   }
				
				
				
				
	}