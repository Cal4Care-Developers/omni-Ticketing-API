<?php
class chat_telegram extends restApi{
    function generate_incoming_telegram($chat_data){
        extract($chat_data);    
        $qry = "SELECT * FROM `admin_details` WHERE telegram_token='$token'";
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
        $checkqry = "select * from chat_telegram where sender_id ='$sender_id'";
        $results = $this->fetchData($checkqry, array());
        $result_count = $this->dataRowCount($checkqry , array());
		
        if($result_count > 0){			
          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_telegram (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','','$chat_msg','$admin_id','$created_at','$created_at','1')", array());     
          $mc_event_data = "Telegram Message From ".$name;
          $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','10','7','$created_at')", array());
			
		 $tele_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_telegram='1'", array());
		 $tele_users = explode(',',$tele_users['GROUP_CONCAT(user_id)']);	
	     $tele_users[] = $admin_id;
		 $this->telegram_notification_curl($tele_users,$chat_id,$mc_event_data);
			
		/*	$this->telegram_notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->telegram_notification_curl($token,$chat_id,$chat_msg);
			}*/
          return $chat_msg_id;      
        }
        else {      
			 
          $chat_id = $this->db_insert("INSERT INTO chat_telegram (`admin_id`,`recipient_id`,`sender_id`,`chat_status`,`updated_at`) VALUES ('$admin_id','$recipient_id','$sender_id','1','$created_at')", array());  		
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_telegram (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','','$chat_msg','$admin_id','$created_at','$created_at','1')", array());     
          $mc_event_data = "Telegram Message From ".$name;
          $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','10','7','$created_at')", array());
			
		 $tele_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_telegram='1'", array());
		 $tele_users = explode(',',$tele_users['GROUP_CONCAT(user_id)']);	
	     $tele_users[] = $admin_id;
		 $this->telegram_notification_curl($tele_users,$chat_id,$mc_event_data);
			/*$this->telegram_notification_curl($admintoken,$chat_id,$chat_msg);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->telegram_notification_curl($token,$chat_id,$chat_msg);
			}*/
          return $chat_msg_id;  
        }  
  }
  function telegram_message_panel($chat_data){
       extract($chat_data);//print_r($chat_data);exit;  
       $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
       $admin_data = $this->fetchData($admin_id_qry,array());
       $admin_id = $admin_data['admin_id'];
     if($admin_id==1){
          $aid = $user_id;
        }else{
          $aid = $admin_id;
        }  
       $chat_detail_qry = "SELECT chat_telegram.chat_id, chat_telegram.sender_id, chat_telegram.recipient_id, chat_telegram.admin_id, chat_telegram.chat_status, date_format(chat_telegram.updated_at, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_telegram.updated_at, '%H:%i') as chat_time, chat_data_telegram.displayName,chat_data_telegram.profile_picture FROM chat_telegram INNER JOIN chat_data_telegram ON chat_data_telegram.chat_id=chat_telegram.chat_id WHERE chat_telegram.admin_id = '$aid' GROUP BY chat_data_telegram.chat_id ORDER BY chat_data_telegram.chat_msg_id DESC";
	  //echo $chat_detail_qry;exit;
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;       
  }
	function chatDetailList($chat_id){    
    $chat_detail_qry = "select * from (select chat_data_telegram.chat_msg_id, chat_data_telegram.admin_id, chat_data_telegram.displayName, chat_data_telegram.profile_picture, chat_data_telegram.delivered_status as msg_status, chat_data_telegram.chat_id,chat_data_telegram.chat_message as chat_msg,date_format(chat_data_telegram.created_at, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_telegram.created_at, '%H:%i') as chat_time,chat_data_telegram.sender_id,chat_data_telegram.recipient_id from chat_data_telegram inner join chat_telegram on chat_telegram.chat_id = chat_data_telegram.chat_id where chat_telegram.chat_id = '$chat_id' order by chat_data_telegram.chat_msg_id desc) result_data order by chat_msg_id asc";
     $parms = array();
     $result = $this->dataFetchAll($chat_detail_qry,array());
     return $result;        
  }
  function reply_message($chat_data){
        extract($chat_data);//print_r($chat_data);exit;    
        $qry = "SELECT telegram_token FROM `admin_details` WHERE admin_id='$admin_id'";
        $token = $this->fetchOne($qry, array());
        $user_qry = "SELECT * FROM `user` WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchData($user_qry, array());
        $name = $user_qry_value['user_name'];
        $user_profile_image = $user_qry_value['profile_image'];
        $timezone = $user_qry_value['timezone_id'];
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone'";
        $user_timezone = $this->fetchOne($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
        $created_at = date("Y-m-d H:i:s");
        //reply code
        $reply_params = [
        'chat_id'=>$sender_id,
        'text' =>$chat_message
        ];
        $reply_url='https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($reply_params);
	    $reply=file_get_contents($reply_url);
        $te = json_encode($reply, TRUE);
	    //print_r($te);
	    //exit;
        //reply code
        $chat_msg_qry = $this->db_insert("INSERT INTO chat_data_telegram (`chat_id`,`recipient_id`,`sender_id`,`displayName`,`profile_picture`,`chat_message`,`admin_id`,`created_at`,`updated_at`,`delivered_status`) VALUES ('$chat_id','$recipient_id','$sender_id','$name','$user_profile_image','$chat_message','$admin_id','$created_at','$created_at','1')", array());
        $userqry_result = $this->db_query($chat_msg_qry, array());              
        $mc_event_data = "Telegram Message To ".$name;
        $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id','$admin_id','$mc_event_data','10','7','$created_at')", array());
	  $result = 1; 
        return $result;               
  }
  function mcEvents(){    
    $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type, event.event_desc,event.event_status,event.created_dt from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id order by event.mc_event_id DESC";      
    $parms = array();
        $result = $this->dataFetchAll($mc_event_qry,array());
        return $result;  
  }
}
    