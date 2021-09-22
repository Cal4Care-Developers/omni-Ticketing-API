<?php
class chat_line extends restApi{
    function generate_incoming_line($chat_data){
        extract($chat_data);    
        $qry = "SELECT * FROM `admin_details` WHERE line_destination_id='$recipient_id' ";
        $result = $this->fetchData($qry, array());
        $admin_id = $result['admin_id'];    
        $qry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
        $res = $this->fetchData($qry, array());
        $timezone_id = $res['timezone_id'];
		$admintoken = $res['notification_code'];
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
        $created_at = date("Y-m-d H:i:s"); 
        $checkqry = "select * from chat_line where sender_id ='$sender_id'";
        $results = $this->fetchData($checkqry, array());
        $result_count = $this->dataRowCount($checkqry , array());
        if($result_count > 0){
          $chat_id=$results['chat_id'];			
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_line (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`reply_token`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','$profile_pic','$chat_msg','$admin_id','$created_at','$created_at','$reply_token','1')", array());     
          $mc_event_data = "Line Message From ".$name;
          $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','9','7','$created_at')", array());
		    $this->line_notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->line_notification_curl($token,$chat_id,$chat_msg);
			}	
          return $chat_msg_id;      
        }
        else {			
          $chat_id = $this->db_insert("INSERT INTO chat_line (`admin_id`,`recipient_id`,`sender_id`,`chat_status`,`created_at`,`updated_at`) VALUES ('$admin_id','$recipient_id','$sender_id','1','$created_at','$created_at')", array());   
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_line (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`reply_token`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','$profile_pic','$chat_msg','$admin_id','$created_at','$created_at','$reply_token','1')", array());     
          $mc_event_data = "Line Message From ".$name;
          $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','9','7','$created_at')", array());
			$this->line_notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->line_notification_curl($token,$chat_id,$chat_msg);
			}
          return $chat_msg_id;  
        }  
  }
  function line_message_panel($chat_data){
       extract($chat_data);//print_r($chat_data);exit;  
       $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
       $admin_data = $this->fetchData($admin_id_qry,array());
       $admin_id = $admin_data['admin_id'];
	   if($admin_id==1){
          $aid = $user_id;
        }else{
          $aid = $admin_id;
        }	 
       $chat_detail_qry = "SELECT chat_line.chat_id, chat_line.sender_id, chat_line.recipient_id, chat_line.admin_id, chat_line.chat_status, date_format(chat_line.created_at, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_line.created_at, '%H:%i') as chat_time, chat_data_line.displayName,chat_data_line.profile_picture FROM chat_line INNER JOIN chat_data_line ON chat_data_line.chat_id=chat_line.chat_id WHERE chat_line.admin_id = '$aid' GROUP BY chat_data_line.chat_id ORDER BY chat_data_line.chat_msg_id DESC";
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;       
  }
  function chatDetailList($chat_id){ 	  
	  $chat_detail_qry = "select * from (select chat_data_line.chat_msg_id, chat_data_line.admin_id, chat_data_line.displayName, chat_data_line.profile_picture, chat_data_line.delivered_status as msg_status, chat_data_line.chat_id,chat_data_line.chat_message as chat_msg,date_format(chat_data_line.created_at, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_line.created_at, '%H:%i') as chat_time,chat_data_line.sender_id,chat_data_line.recipient_id from chat_data_line inner join chat_line on chat_line.chat_id = chat_data_line.chat_id where chat_line.chat_id = '$chat_id' order by chat_data_line.chat_msg_id desc) result_data order by chat_msg_id asc";
     $parms = array();
     $result = $this->dataFetchAll($chat_detail_qry,array());
     return $result;        
  }
  function reply_message($chat_data){
        extract($chat_data);//print_r($chat_data);exit;    
        $qry = "SELECT line_access_token FROM `admin_details` WHERE admin_id='$admin_id'";
         $access_token = $this->fetchOne($qry, array());
	  //echo "SELECT reply_token FROM `chat_data_line` WHERE `sender_id` ='$recipient_id' ORDER BY chat_msg_id DESC LIMIT 1";exit;
        $reply_token_qry = "SELECT reply_token FROM `chat_data_line` WHERE `sender_id` ='$recipient_id' ORDER BY chat_msg_id DESC LIMIT 1";
        $reply_token = $this->fetchOne($reply_token_qry, array());
        $user_qry = "SELECT * FROM `user` WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchData($user_qry, array());//print_r($user_qry_value);exit;
        $name = $user_qry_value['user_name'];
        $user_profile_image = $user_qry_value['profile_image'];
        $timezone = $user_qry_value['timezone_id'];
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone'";
        $user_timezone = $this->fetchOne($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
        $created_at = date("Y-m-d H:i:s");
        $messages = [
           'type' => 'text',
           'text' => $chat_message
        ];
        $data = [
          'to' => $reply_token,
          'messages' => [$messages],
        ];
        $response = $this->line_reply_curl($access_token,$data);
	  //echo $response;exit;
        if($response=='{"message":"Invalid reply token"}'){
          $result = 0;
          return $result;
        }else{
		  //$get_qry = "SELECT displayName FROM chat_data_line WHERE widget_name='$widget_name'"; 
          //$user_details = $this->fetchOne($get_qry,array());	
          $chat_msg_qry = $this->db_insert("INSERT INTO chat_data_line (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','$user_profile_image','$chat_message','$admin_id','$created_at','$created_at','1')", array());
          $userqry_result = $this->db_query($chat_msg_qry, array());  
          $result = 1;     
          $mc_event_data = "Line Message To ".$name;			
          $this->db_insert("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','9','7','$created_at')", array());
          return $result;
        }        
  }
	function mcEvents(){    
    $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type, event.event_desc,event.event_status,event.created_dt from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id order by event.mc_event_id DESC";      
    $parms = array();
        $result = $this->dataFetchAll($mc_event_qry,array());
        return $result;  
  }
}
    