 <?php
require __DIR__ . '/../../Unifonic/Autoload.php';
//use \Unifonic\API\Client;
require __DIR__ . '/../../eio/vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;


class chat extends restApi{
    function getcustomersChat($user_id,$queue_id,$search_text,$limit,$offset){
		
		// echo $user_id; exit;
    	
       $qry = "select * from user where user_id='$user_id'";

           $result = $this->fetchData($qry, array());

          if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }

    
        $search_qry ="";
    if($search_text != ""){
      $search_qry .= "and chat_sms.customer_name like '%".$search_text."%'";
    }
     
        
        if($queue_id == null || $queue_id==""){
            
            $queue_condtion = "select queue.queue_id from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and  FIND_IN_SET('1', q_user.queue_feature) and q_user.queue_user_status = '1' and queue.queue_status = '1'";
            
        }
        else{
            $queue_condtion = $queue_id;
        }
        
      // $queue_chat_qry = "select chat_data_sms.chat_msg_id, chat_data_sms.read_status,chat_data_sms.chat_message, chat_data_sms.chat_id, date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.customer_name as customer_name, (select first_name from contacts where phone=chat_sms.customer_name) as cusd_name, chat_sms.app_chat_id, chat_sms.api_type from chat_sms inner join chat_data_sms on chat_data_sms.chat_id = chat_sms.chat_id where chat_data_sms.chat_msg_id in (select max(chat_msg_id) from chat_data_sms group by chat_id order by chat_msg_id desc) and chat_sms.admin_id='$admin_id' order by chat_data_sms.chat_msg_id desc";
       
		
	 $queue_chat_qry = "select chat_data_sms.chat_msg_id, chat_data_sms.read_status,chat_data_sms.chat_message, chat_data_sms.chat_id, date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.customer_name as customer_name, (select first_name from contacts where phone = chat_sms.customer_name and admin_id='$admin_id'  GROUP BY phone ) as cus_name, chat_sms.app_chat_id, chat_sms.api_type from chat_sms inner join chat_data_sms on chat_data_sms.chat_id = chat_sms.chat_id where chat_data_sms.chat_msg_id in (select max(chat_msg_id) from chat_data_sms group by chat_id order by chat_msg_id desc) and chat_sms.admin_id='$admin_id' $search_qry order by chat_data_sms.chat_msg_id desc";	
		
		
  //echo $queue_chat_qry; exit;
        

        $result = $this->dataFetchAll($queue_chat_qry,array());
		//print_r($result); exit;
		foreach($result as &$v) {
		  $v['chat_message'] = utf8_encode($v['chat_message']);
		}


        return $result;
        

    }
	
	
	
       function getAdminDatasN(){
            $qry = "select * from user where user_type ='2'";

            return $this->dataFetchAll($qry, array());
        }
    function chatDetailList($chat_id, $admin_id,$limit,$offset){
	
      $qry_result = $this->db_query("UPDATE chat_data_sms SET read_status='0' where chat_id='$chat_id'", array());   
    
   $chat_detail_qry = "select * from (select chat_data_sms.chat_msg_id, chat_data_sms.delivered_status as msg_status, chat_data_sms.msg_from as msg_user_type,chat_data_sms.chat_id,chat_data_sms.chat_message as chat_msg,date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.app_customer_key as customer_name,chat_data_sms.chat_pnr as chat_pnr, users.agent_name,users.profile_image, chat_data_sms.agent_id as msg_user_id, chat_sms.api_type as msg_type,  (select first_name from contacts where phone = chat_sms.customer_name and admin_id='$admin_id'  GROUP BY phone ) as cus_name  from chat_data_sms inner join chat_sms on chat_sms.chat_id = chat_data_sms.chat_id left join user as users on users.user_id = chat_data_sms.agent_id where chat_sms.chat_id LIKE '".$chat_id."' order by chat_data_sms.chat_msg_id desc LIMIT $limit OFFSET $offset) result_data order by chat_msg_id asc";
       // echo $chat_detail_qry;exit;
        
           // $this->errorLog("demo",$chat_detail_qry);
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());

        return $result;
    }
  
  function getChatDetails($chat_msg_id){
    
     $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
    
    
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());

        return $result;
  
  
  }
    
    function getpbxsettings($user_id){
		
    if($user_id == 1){
   $mc_event_qry = "SELECT id,sip_login,sip_authentication,sip_password,sip_port,sip_url,(select user_name from user where user_id = pbx_settings.admin_id) as admin_name,status,updated_at FROM `pbx_settings`";
  } else { 
     $mc_event_qry = "select * from pbx_settings where delete_status != 1 and admin_id='$user_id'";
    }
     $parms = array();
     $result = $this->dataFetchAll($mc_event_qry,array());
       return $result;
   }

     function getpbxsettingss($user_id){
  
  //if($user_id == 1){
  // $mc_event_qry = "SELECT id,sip_login,sip_authentication,sip_password,sip_port,sip_url,(select user_name from user where user_id = pbx_settings.admin_id) as admin_name,status,updated_at FROM `pbx_settings` WHERE 1";
  //} else { }
     //$mc_event_qry = "select * from pbx_settings where admin_id='$user_id' and status='1'"; 
    $mc_event_qry="SELECT user.user_id,user.ext_int_status, user.sip_username as sip_authentication,user.sip_login,user.sip_password,pbx_settings.sip_port,pbx_settings.sip_url,user.survey_vid  FROM user,pbx_settings where IF(user.admin_id = 1, user.user_id , user.admin_id )=pbx_settings.admin_id and user.user_id='$user_id' and pbx_settings.status='1' and pbx_settings.delete_status='0'";
		//print_r($pbx);exit;
		
//	echo $pbx['sip_url'];exit;
	
     $parms = array();
    // $result = $this->fetchData($mc_event_qry,array());
        $pbx = $this->fetchData($mc_event_qry,array());
		 
		 $x = gethostbyname($pbx['sip_url']);
		 
		  $result['user_id']=base64_encode ($pbx['user_id']);
		  $result['ext_int_status']=base64_encode ($pbx['ext_int_status']);
		  $result['sip_authentication']=base64_encode ($pbx['sip_authentication']);
		  $result['sip_login']=base64_encode ($pbx['sip_login']);
		  $result['sip_password']=base64_encode ($pbx['sip_password']);
		  $result['sip_port']=base64_encode ($pbx['sip_port']);
		  $result['sip_url']=base64_encode ($pbx['sip_url']);
		  $result['sip_survey_vid']=base64_encode ($pbx['survey_vid']);
		 $result['server_ip'] = $x;
       return $result;
   }
    function getsinglepbxsettings($pbx_id){
    
     $mc_event_qry = "select * from pbx_settings where id = '$pbx_id'";
     $parms = array();
     $result = $this->dataFetchAll($mc_event_qry,array());
       return $result;
   }
  
      function getpbxdetails($user_id){
    
     $mc_event_qry = "select * from admin_details where admin_id = '$user_id'";
     $parms = array();
     $result = $this->dataFetchAll($mc_event_qry,array());
       return $result;
   }
  
  
   function editsinglepbxsettings($edit_fom_url,$sip_port,$sip_url){
	      $qry_result = $this->db_query("UPDATE pbx_settings SET sip_port='$sip_port',sip_url='$sip_url' where id='$edit_fom_url'", array());            
            $result = $qry_result == 1 ? 1 : 0;
            return $result;

  }

  /*function addsinglepbxsettings($sip_port,$sip_url,$admin_id){
    
    
	   $qry_result = $this->db_query("INSERT INTO pbx_settings(sip_port,sip_url,admin_id,status) values ('$sip_port','$sip_url','$admin_id','1')", array());
            

            $result = $qry_result == 1 ? 1 : 0;

            return $result;

  }*/
function addsinglepbxsettings($sip_port,$sip_url,$admin_id){    
     $get_qry = "SELECT * FROM pbx_settings WHERE admin_id='$admin_id' AND sip_port='$sip_port' AND sip_url='$sip_url'"; 
     $result = $this->fetchData($get_qry,array());
     if($result > 0){
       $result = 0;
       return $result;
     }else{
       $qry_result = $this->db_query("INSERT INTO pbx_settings(sip_port,sip_url,admin_id,status) values ('$sip_port','$sip_url','$admin_id','1')", array());
       $result = $qry_result == 1 ? 1 : 0;
       return $result;
     }
  }
  
  
  /*function mcEvents($user_id){    
    $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on event.mc_event_type = queue_features.feature_id WHERE event.admin_id='$user_id' order by event.mc_event_id DESC";
	//$mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id order by event.mc_event_id DESC";       
    $parms = array();
        $result = $this->dataFetchAll($mc_event_qry,array());
        return $result;  
  }*/
	function mcEvents($user_id){
    $admin_id_qry = "select * from user where user_id='$user_id'";   
    $admin_res = $this->fetchData($admin_id_qry, array());
		
	$admin_id = $admin_res['admin_id'];
		$reseller = $admin_res['reseller'];
	$has_sms = $admin_res['has_sms'];
		if($has_sms=='1'){$has_sms1='6';}else{$has_sms1='0';}
	$has_chat = $admin_res['has_chat'];
		if($has_chat=='1'){$has_chat1='1';}else{$has_chat1='0';}
	$has_whatsapp = $admin_res['has_whatsapp'];
		if($has_whatsapp=='1'){$has_whatsapp1='5';}else{$has_whatsapp1='0';}	
	$has_fb = $admin_res['has_fb'];
		if($has_fb=='1'){$has_fb1='7';}else{$has_fb1='0';}		
	$has_telegram = $admin_res['has_telegram'];
		if($has_telegram=='1'){$has_telegram1='10';}else{$has_telegram1='0';}
	$has_external_ticket = $admin_res['has_external_ticket'];
		if($has_external_ticket=='1'){$has_telegram1='11';}else{$has_external_ticket='0';}	
		//$internal_chat1='8';
		//$vals=$has_sms1.','.$has_chat1.','.$has_whatsapp1.','.$has_telegram1.','.$has_fb1.','.$internal_chat1;
		$vals=$has_chat1.','.$has_whatsapp1.','.$has_telegram1.','.$has_fb1.','.$has_external_ticket;
		 $vals="'" . str_replace(",", "','", $vals) . "'";
		//echo $vals;exit;
		
    if($admin_id==1){
     if($reseller==''){
        $mc_event_qry="(select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type,event.wp_inst_id, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id WHERE event.admin_id='$user_id' and event.mc_event_type!='11' and event.mc_event_type!='1' and event.mc_event_type!='6') UNION
(select ev.mc_event_id,ev.mc_event_key,ev.mc_event_data,ev.mc_event_type,ev.wp_inst_id, qf.feature_name,ev.event_desc,ev.event_status,ev.created_dt,ev.page_name,ev.page_picture,ev.is_contact from mc_event ev inner join queue_features qf on  ev.mc_event_type = qf.feature_id WHERE ev.admin_id='$user_id' and (ev.mc_event_type='11' OR ev.mc_event_type='1'  OR ev.mc_event_type='6') group by ev.mc_event_key,date_format(ev.created_dt, '%Y-%m-%d %H:%i' ))
 order by mc_event_id DESC LIMIT 50";
	 }else{
			  $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type,event.wp_inst_id, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id WHERE  event.admin_id IN($user_id,$reseller) order by event.mc_event_id DESC LIMIT 50";
		}   
		//echo $mc_event_qry;exit;
      $parms = array();
      $result = $this->dataFetchAll($mc_event_qry,array());
		
      return $result;
    }else{
		
		$vals=$has_fb1;
		 $vals="'" . str_replace(",", "','", $vals) . "'";
		// replace on 31-07-2020
		// $mc_event_qry = "select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type,event.wp_inst_id, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id WHERE event.admin_id='$admin_id' AND  mc_event_type IN ($vals) order by event.mc_event_id DESC LIMIT 50";
		// 05-06-2021
		$mc_event_qry="select event.mc_event_id,event.mc_event_key,event.mc_event_data,event.mc_event_type,event.wp_inst_id, queue_features.feature_name,event.event_desc,event.event_status,event.created_dt,event.page_name,event.page_picture,event.is_contact from mc_event event inner join queue_features on  event.mc_event_type = queue_features.feature_id WHERE event.user_id='$user_id' or event.mc_event_type IN ($vals)  order by event.mc_event_id DESC LIMIT 50";
      //echo $mc_event_qry;exit;
		$parms = array();
      $result = $this->dataFetchAll($mc_event_qry,array());
      return $result;
    }  
  }
  function mcwhatsapp($user_id){ 	  
    $user_qry = "select * from user where user_id ='$user_id'";
    $user_result =  $this->fetchData($user_qry, array());
    $admin_id = $user_result['admin_id'];
    if($admin_id==1){
      $userid = $user_id;
    }else{
      $userid = $admin_id;
    }	  
    $wpchat_qry = "SELECT chat_wp.chat_id,chat_wp.customer_name, chat_data_wp.chat_message,chat_data_wp.created_dt FROM chat_wp LEFT JOIN chat_data_wp on chat_wp.chat_id = chat_data_wp.chat_id WHERE chat_wp.admin_id = '$userid' ORDER BY chat_data_wp.created_dt DESC";
    $result =  $this->dataFetchAll($wpchat_qry, array());
    return $result;
  }  
  function mcsms($user_id){	  
    $sms_qry = "SELECT chat_sms.chat_id,chat_sms.customer_name, chat_data_sms.chat_message,chat_data_sms.created_dt FROM chat_sms LEFT JOIN chat_data_sms on chat_sms.chat_id = chat_data_sms.chat_id WHERE chat_sms.admin_id = '$user_id' ORDER BY chat_data_sms.created_dt DESC";
    $result =  $this->dataFetchAll($sms_qry, array());
    return $result;
  }  
    
   /*function insertChatMessage($chat_data){
        
        extract($chat_data);
     

          $qry = "select app_customer_key from chat_sms where chat_id='$chat_id'";

        $result = $this->fetchData($qry, array());
	   
     $mobile_num = $result['app_customer_key'];
  $xml = '<?xml version="1.0" encoding="UTF-8" ?><TELEMESSAGE><TELEMESSAGE_CONTENT><MESSAGE><MESSAGE_INFORMATION><SUBJECT></SUBJECT></MESSAGE_INFORMATION><USER_FROM><CIML><NAML><LOGIN_DETAILS><USER_NAME>1676</USER_NAME><PASSWORD>32902540</PASSWORD></LOGIN_DETAILS></NAML></CIML></USER_FROM><MESSAGE_CONTENT><TEXT_MESSAGE><MESSAGE_INDEX>0</MESSAGE_INDEX><TEXT>'.$chat_msg.'</TEXT></TEXT_MESSAGE></MESSAGE_CONTENT><USER_TO><CIML><DEVICE_INFORMATION><DEVICE_TYPE  DEVICE_TYPE="SMS"/><DEVICE_VALUE>'.$mobile_num.'</DEVICE_VALUE></DEVICE_INFORMATION></CIML></USER_TO></MESSAGE></TELEMESSAGE_CONTENT><VERSION>1.6</VERSION></TELEMESSAGE>';
  
	   
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
     //date_default_timezone_set("Indian/Maldives");
     $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
    // echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
  
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id, agent_id, msg_from, msg_type, chat_message, delivered_status, chat_status, created_dt) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', '$chat_msg','$del_stat', '1','$created_at')", array());
        
      $chat_data = $this->getChatDetails($chat_msg_id);
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
    }*/
	
function insertChatMessage($chat_data){        
        extract($chat_data);
	//print_r($chat_data);exit;
	
	  $qry = "select * from user where user_id='$agent_id'";
  	  $result = $this->fetchData($qry, array());	
  	  if($result['user_type'] == '2'){ $aid = $agent_id; } else { $aid = $result['admin_id']; }
        $qry = "select * from chat_sms where chat_id='$chat_id'";
        $result = $this->fetchData($qry, array());
        $mobile_num = $result['app_customer_key'];
		$sender_id = $result['sender_id'];
	//echo $aid;exit;
	
	
	
        if($agent_id == 47 || $aid == 47){   
     
  $xml = '<?xml version="1.0" encoding="UTF-8" ?><TELEMESSAGE><TELEMESSAGE_CONTENT><MESSAGE><MESSAGE_INFORMATION><SUBJECT></SUBJECT></MESSAGE_INFORMATION><USER_FROM><CIML><NAML><LOGIN_DETAILS><USER_NAME>1676</USER_NAME><PASSWORD>32902540</PASSWORD></LOGIN_DETAILS></NAML></CIML></USER_FROM><MESSAGE_CONTENT><TEXT_MESSAGE><MESSAGE_INDEX>0</MESSAGE_INDEX><TEXT>'.$chat_msg.'</TEXT></TEXT_MESSAGE></MESSAGE_CONTENT><USER_TO><CIML><DEVICE_INFORMATION><DEVICE_TYPE  DEVICE_TYPE="SMS"/><DEVICE_VALUE>'.$mobile_num.'</DEVICE_VALUE></DEVICE_INFORMATION></CIML></USER_TO></MESSAGE></TELEMESSAGE_CONTENT><VERSION>1.6</VERSION></TELEMESSAGE>';
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
} elseif($agent_id == 128 || $adminid == 128){  

		$client = new Client();
	$response = $client->Messages->Send($mobile_num,$chat_msg,$sender_id); 
	

	  if($response->Status == 'Queued'){
		  $del_stat = '1';
		}else{
		  $del_stat = '0';
		}
	} elseif($aid == 310){
	 //echo 'ds'; exit;
		  	  $country_code_qry = "SELECT country_code FROM chat_sms WHERE chat_id='$chat_id'";
	 $country_code = $this->fetchOne($country_code_qry,array());
	$admin_qry = "SELECT * FROM external_sms_details WHERE admin_id='$aid'";
	$res = $this->fetchData($admin_qry,array());
	$phone = $country_code.$mobile_num;
	$from = $res['admin_num'];
	$api_key = $res['api_key'];
	//echo $phone_num; exit;
	$res = '{ "from": "'.$from.'","to":"'.$phone.'","text":"'.$chat_msg.'" }';
	//echo $res ; exit;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.telnyx.com/v2/messages');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$api_key;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);	
	//print_r($result);
	 if($result){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }


}
	else{
		//echo $chat_id;exit;
   $country_code_qry = "SELECT country_code FROM chat_sms WHERE chat_id='$chat_id'";
  $country_code = $this->fetchOne($country_code_qry,array());		
	

if (strpos($country_code, '+') !== false) {
  $country_code = ltrim($country_code, '+');
}
//echo $country_code;exit;
$sel="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id from user where user_id='$agent_id'";
	$admin_id=$this->fetchOne($sel, array());
		$tarrif_val = $this->get_sms_column($admin_id);	
	  $sel_country_cde="SELECT $tarrif_val from country where phonecode='$country_code'";
	$sms_tarrif=$this->fetchOne($sel_country_cde, array());
	$result_counr= $this->dataRowCount($sel_country_cde,$parms);
	if($result_counr=='0'){
		return '2';
	}			
		 $len=strlen($chat_msg);
		 $sms_count=$len/160;
		 $cc= ceil($sms_count);
		 $spri=$cc*$sms_tarrif;
		if($sms_tarrif=='' || $sms_tarrif=='0'){
		return '4';
	}
	 // $sel_pr="SELECT price_sms from admin_details where admin_id='$admin_id'";
	//$sel_price=$this->fetchOne($sel_pr, array());
	// $rate= $sel_price-$spri;
	//if($rate<='0'){
	//	return '3';
	//}		
	  $sel_pr="SELECT price_sms,sms_type from admin_details where admin_id='$admin_id'";
	$res=$this->fetchData($sel_pr, array());
			$sel_price=$res['price_sms'];
			$sms_type=$res['sms_type'];
			if($sms_type=='1'){
				$rate= $sel_price;
			}else{
	 $rate= $sel_price-$spri;
	if($rate<='0'){
		return '3';
	}		
			}
		if($admin_id=='572'){
			$sender='EDUQUEST';
		}else{$sender='480090209';}
  $message = urlencode($chat_msg);
		if (strpos($mobile_num, '+') !== false) {
			 $mobile_num = ltrim($mobile_num, '+');
  $phonenumber=$mobile_num;
}else{		
		$phonenumber=$country_code.$mobile_num;
		}
 /* $postData = array(
                'user' => 'cal4care',
                'password' => 'Godaddy123',
                //'phonenumber' => "+61".$mobile_num,
	            'phonenumber' => $country_code.$mobile_num,
                'text' => $message,
                'gsm_sender' => "$sender",
                'cdma_sender' => '82986675',
                'action' => 'send'
             );    
			//print_r($postData);exit;
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
  //echo $result;exit;
  if(curl_errno($ch)){
      throw new Exception(curl_error($ch));
  }
  curl_close($ch);*/
		

$curl = curl_init();
//echo "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message";exit;
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);
//echo $response;exit;
curl_close($curl);
$res_out=substr($response,0,3);
	 if($res_out == '+OK'){	  
      $del_stat = '1';
    }else{	  
      $del_stat = '0';
    }
}   
	if($del_stat=='1'){	
	 $update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
		$qry_result = $this->db_query($update, array());
		
	}
     //date_default_timezone_set("Indian/Maldives");
     $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
     //echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
      $updated_at = date("Y-m-d H:i:s");
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id, agent_id, msg_from, msg_type, chat_message, delivered_status, chat_status, created_dt, updated_dt,sms_tarrif,send_by) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', '$chat_msg', '$del_stat', '1', '$created_at', '$updated_at','$spri','Using Omni SMS panel')", array());        
      $chat_data = $this->getChatDetails($chat_msg_id);
	$mc_event_data = "SMS To".$mobile_num;
        $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$agent_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
	
        return '1';
           
    }
	
function ComposeChatMessage($chat_data){
extract($chat_data); 
	//print_r($chat_data); exit;
$admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
$adminid = $this->fetchmydata($admin_id_qry,array());
if($adminid == 1){
$aid = $user_id;
}else{
$aid = $adminid;	
}
if($user_id == 47 || $aid == 47){
	
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
	
}  elseif($user_id == 128 || $adminid == 128){
	$client = new Client();
	$response = $client->Messages->Send($phone_num,$chat_msg,$sender_id); 

	  if($response->Status == 'Queued'){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }

}  elseif($aid == 310){
	
	$admin_qry = "SELECT * FROM external_sms_details WHERE admin_id='$aid'";
	$res = $this->fetchData($admin_qry,array());
	$phone = $country_code.$phone_num;
	$from = $res['admin_num'];
	$api_key = $res['api_key'];
	//echo $phone_num; exit;
$res = '{ "from": "'.$from.'","to":"'.$phone.'","text":"'.$chat_msg.'" }';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.telnyx.com/v2/messages');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$api_key;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);	
	//print_r($result);
	 if($result){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }

}
else{
	$sel="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id from user where user_id='$user_id'";
	$admin_id=$this->fetchOne($sel, array());
	
	 $tarrif_val = $this->get_sms_column($admin_id);
	$sel_country_cde="SELECT $tarrif_val from country where phonecode='$country_code'";
	
	$sms_tarrif=$this->fetchOne($sel_country_cde, array());
	$result_counr= $this->dataRowCount($sel_country_cde,$parms);
	if($result_counr=='0'){
		return '2';
	}
		 $len=strlen($chat_msg);
		 $sms_count=$len/160;
		 $cc= ceil($sms_count);
		 $spri=$cc*$sms_tarrif;
	if($sms_tarrif=='' || $sms_tarrif=='0'){
		return '4';
	}
		
	  $sel_pr="SELECT price_sms,sms_type from admin_details where admin_id='$admin_id'";
	$res=$this->fetchData($sel_pr, array());
			$sel_price=$res['price_sms'];
			 $sms_type=$res['sms_type'];
			if($sms_type=='1'){
				$rate= $sel_price;
			}else{
	 $rate= $sel_price-$spri;
	if($rate<='0'){
		return '3';
	}		
			}
	
	if($admin_id=='572'){
			$sender='EDUQUEST';
		}else{$sender='480090209';}
  $message = urlencode($chat_msg);  
	$phonenumber=$country_code.$phone_num;
  /*$postData = array(
                'user' => 'cal4care',
                'password' => 'Godaddy123',
                //'phonenumber' => "+91".$phone_num,
	            'phonenumber' => $country_code.$phone_num,
                'text' => $message,
                'gsm_sender' => "$sender",
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
  if($result == '1: Message sent successfully @  '){	*/
			  		

$curl = curl_init();
//echo "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message";exit;
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);
//echo $response;exit;
curl_close($curl);
$res_out=substr($response,0,3);
	 if($res_out == '+OK'){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }
}     
$qry = "select * from chat_sms where app_customer_key='$phone_num' and admin_id='$aid'";
$result = $this->fetchData($qry, array());
	//echo count($result);exit;
	if($del_stat=='1'){	
	$update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
		$qry_result = $this->db_query($update, array());
		
	}
if($result > 0){	
	
  $chat_id=$result['chat_id'];           
  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
  $user_timezone = $this->fetchmydata($user_timezone_qry,array());	
  date_default_timezone_set($user_timezone);
  $created_at = date("Y-m-d H:i:s");
  $updated_at = date("Y-m-d H:i:s");  
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt, updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());	
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';      
}else {
  $qry = "select * from user where user_id='$user_id'";
  $result = $this->fetchData($qry, array());	
  if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
  $tzone = $result['timezone_id'];
  if($tzone == 0){
	  //echo "if";exit;
    date_default_timezone_set('Asia/Singapore');   
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s");  
  }else{
	  //echo "else";exit;
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$tzone'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
	  //echo $user_timezone;exit;
	date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");	  
  }		
  $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `country_code`, `customer_name`, `customer_pnr`,`sender_id`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`, `updated_dt`) VALUES ('$phone_num', '$phone_num', '$country_code', '$phone_num', '','$sender_id', 'sms', '0', '1', '1', '$admin_id','$created_at','$updated_at')", array());
	
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';          
}
}	
    









function ComposeGroupChatMessage($chat_data){
        extract($chat_data); 
        $qry = "select group_users from sms_group where group_id ='$group' and admin_id = '$admin_id'";
        $g_contact = $this->fetchData($qry, array());
        $contacts = $g_contact['group_users'];
        $qry = "SELECT phone FROM `contacts` WHERE `contact_id` IN ($contacts) ORDER BY `contact_id` DESC";
        $result = $this->dataFetchAll($qry, array());
        foreach ($result as $key => $value) {
            $chat_data = $this->bulkSMS($value['phone'],$chat_msg,$sender_id,$admin_id,$user_id);
        }   
        return $chat_data;
}







function bulkSMS($phone_num,$chat_msg,$sender_id,$admin_id,$user_id){
    if($user_id == 47 || $aid == 47){
	
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
          
      }  elseif($user_id == 128 || $adminid == 128){
          $client = new Client();
          $response = $client->Messages->Send($phone_num,$chat_msg,$sender_id); 
      
            if($response->Status == 'Queued'){
            $del_stat = '1';
          }else{
            $del_stat = '0';
          }
      
      }
      else{
$sel="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id from user where user_id='$user_id'";
	$admin_id=$this->fetchOne($sel, array());
    $tarrif_val = $this->get_sms_column($admin_id);	  
	$sel_country_cde="SELECT $tarrif_val from country where phonecode='$country_code'";
	$sms_tarrif=$this->fetchOne($sel_country_cde, array());
	$result_counr= $this->dataRowCount($sel_country_cde,$parms);
	if($result_counr=='0'){
		return '2';
	}
		 $len=strlen($chat_msg);
		 $sms_count=$len/160;
		 $cc= ceil($sms_count);
		 $spri=$cc*$sms_tarrif;
if($sms_tarrif=='' || $sms_tarrif=='0'){
		return '4';
	}
		//echo $spri;exit;
	  $sel_pr="SELECT price_sms,sms_type from admin_details where admin_id='$admin_id'";
	$res=$this->fetchData($sel_pr, array());
			$sel_price=$res['price_sms'];
			 $sms_type=$res['sms_type'];
			if($sms_type=='1'){
				$rate= $sel_price;
			}else{
	 $rate= $sel_price-$spri;
	if($rate<='0'){
		return '3';
	}		
			}
		  if($admin_id=='572'){
			$sender='EDUQUEST';
		}else{$sender='480090209';}
        $message = urlencode($chat_msg);  
		  	$phonenumber=$country_code.$phone_num;
        /*$postData = array(
                      'user' => 'cal4care',
                      'password' => 'Godaddy123',
                      //'phonenumber' => "+91".$phone_num,
					  'phonenumber' => $country_code.$phone_num,
                      'text' => $message,
                      'gsm_sender' => "$sender",
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
        if($result == '1: Message sent successfully @  '){*/
		  		

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);

curl_close($curl);
$res_out=substr($response,0,3);
	 if($res_out == '+OK'){
            $del_stat = '1';
          }else{
            $del_stat = '0';
          }
      }     
    $qry = "select * from chat_sms where app_customer_key='$phone_num' and admin_id='$admin_id'";
    $result = $this->fetchData($qry, array());
        //echo count($result);exit;
	if($del_stat=='1'){	
	//echo $rate;exit;
	$update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
		$qry_result = $this->db_query($update, array());		
	}
    if($result > 0){
      $chat_id=$result['chat_id'];           
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());	
      date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s");
      $updated_at = date("Y-m-d H:i:s");  
      $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt, updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());
        
      $chat_data = $this->getChatDetails($chat_msg_id);
      return $chat_data;      
    }else {
      $qry = "select * from user where user_id='$admin_id'";
      $result = $this->fetchData($qry, array());	
      $tzone = $result['timezone_id'];
      if($tzone == 0){
          //echo "if";exit;
        date_default_timezone_set('Asia/Singapore');   
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");  
      }else{
          //echo "else";exit;
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$tzone'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
          //echo $user_timezone;exit;
        date_default_timezone_set($user_timezone);  
        $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");	  
      }		
      $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`,`sender_id`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`, `updated_dt`) VALUES ('$phone_num', '$phone_num', '$phone_num', '','$sender_id', 'sms', '0', '1', '1', '$admin_id','$created_at','$updated_at')", array());
        
      $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());
      $chat_data = $this->getChatDetails($chat_msg_id);
      return '1';          
    }
}













	
  
  function ComposeIncommingChatMessage($chat_data){
        extract($chat_data);
    //date_default_timezone_set("Indian/Maldives");
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
    
    
     $qry = "select * from chat_sms where app_customer_key='$phone_num'";
       $result = $this->fetchData($qry, array());
    if($result > 0){
          $chat_id=$result['chat_id'];
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,send_by) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','Using Omni SMS panel')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);
		//$mc_event_data = "SMS To".$phone_num;
		//$this->db_query("INSERT INTO mc_event (mc_event_data,mc_event_type,event_status,created_dt) VALUES('$mc_event_data','6','7','$created_at')", array());
                return $chat_data;
      
    } else {//echo "el";exit;
      $qry = "select * from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
            if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
            $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`created_at`) VALUES ('$phone_num', '$phone_num', '$phone_num', '', 'sms', '0', '1', '1', '$admin_id','$created_at')");
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt,send_by) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','Using Omni SMS panel')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);
		//$mc_event_data = "SMS To".$phone_num;
		//$this->db_query("INSERT INTO mc_event (mc_event_data,mc_event_type,event_status,created_dt) VALUES('$mc_event_data','6','7','$created_at')", array());
            return $chat_data;  
    }  
    }


	
	
	  function generate_incoming_message($chat_data){
        extract($chat_data);
		  print_r($chat_data);
 
    }
	
	
	

// Web Chat


        function getcustomersChatWC($user_id,$extension,$queue_id,$search_text,$status){
			
//print_r($search_text); exit;

        $search_qry ="";
    if($search_text != ""){
      $search_qry .= "and customer.customer_name like '%".$search_text."%'";
    }
			
	if($extension != "" && $extension != "null" && $extension != "undefined"){  //MOBILE APPS
	$qry = "select user_id from user where sip_login='$extension'";
      $user_id = $this->fetchOne($qry, array());
	}
        
        if($queue_id == null || $queue_id==""){
            
            $queue_condtion = "select queue.queue_id from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and  FIND_IN_SET('1', q_user.queue_feature) and q_user.queue_user_status = '1' and queue.queue_status = '1'";
            
            
        }
        else{
            $queue_condtion = $queue_id;
        }
        
        // $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user, chat.chat_status, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.chat_queue in ($queue_condtion) and chat.chat_type in (1,2) ".$search_qry." order by chat.chat_id desc";

      $qry = "select * from user where user_id='$user_id'";
      $result = $this->fetchData($qry, array());
			
      
      if($result['user_type'] == '2')
	  {
		 $admin_id = $user_id;
		  if($status){
		  	$status = "and chat.chat_status='$status'";
		  } else {
		  $status = '';
		  }		  
	     $queue_chat_qry = "select chat.chat_id,chat.claim_status,chat.chat_code,chat.chat_user,chat.chat_type,chat.chatUrl,chat.chat_queue,chat.assigned_user, chat.chat_status, chat.read_status,customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where  chat.admin_id='$admin_id' ".$status." and chat.chat_type in (1,2) ".$search_qry." order by chat.chat_id desc";
		  //echo $queue_chat_qry;exit;
		 
		  $result = $this->dataFetchAll($queue_chat_qry,array());
	  }
	  else
	  {		
		$admin_id = $result['admin_id'];
		$test = array(); 
		//$department_qry = "SELECT dept_id FROM `departments` WHERE department_users LIKE '%$user_id%'";
		$department_qry="SELECT GROUP_CONCAT(dept_id) as dept_id FROM `departments` WHERE department_users LIKE '%$user_id%'";
        $department_user = $this->dataFetchAll($department_qry, array());
	    $department_user_count = $this->dataRowCount($department_qry, array());
		   
	    if($department_user_count>0){
	    foreach($department_user as $dat){
			$dep = $dat['dept_id'];			
			 //$dep="'" . str_replace(",", "','", $dep) . "'";
			
		//	$queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.rating_value, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.department IN ($dep) and chat.admin_id='$admin_id' and (find_in_set('$user_id', chat.agents)) and chat.chat_type in (1,2)".$search_qry." order by chat.chat_id desc";
		 if($status){
		  	$status = "and chat.chat_status='$status'";
		  } else {
		  $status = '';
		  }			
			$queue_chat_qry = "SELECT chat.chat_id,chat.claim_status, chat.rating_value,departments.department_name,chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.read_status,chat.chatUrl,chat.rating_value,dept_id,customer.customer_name,DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt FROM `departments` INNER JOIN chat on FIND_IN_SET(departments.dept_id,chat.department) and departments.admin_id=chat.admin_id left join customer on customer.customer_id = chat.chat_user WHERE department_users LIKE '%$user_id%' and chat.admin_id='$admin_id' ".$status." and chat.chat_type in (1,2)".$search_qry." group by chat.chat_id order by chat.chat_id desc";	
			//echo $queue_chat_qry;exit; //NB
		}
	  }
		  //	$queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.rating_value, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where FIND_IN_SET('$dept',chat.department) and chat.admin_id='$admin_id'  and chat.chat_type in (1,2)".$search_qry." order by chat.chat_id desc";	
		/*  else{
			$queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.rating_value, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.admin_id='$admin_id' and (find_in_set('$user_id', chat.agents)) and chat.chat_type in (1,2)".$search_qry." order by chat.chat_id desc";
		}*/
		  //echo $queue_chat_qry;exit;
		  $result = $this->dataFetchAll($queue_chat_qry,array());
	  }
      return $result;        
        }

       
        function chatDetailListWC($chat_id){        
        $update="UPDATE chat SET read_status='0' where chat_id='$chat_id'";
		//print_r($update); exit;
		$qry_result = $this->db_query($update, array());
			
        $chat_detail_qry = "select chat.chat_id,chat.claim_status,chat.online_status,chat.assigned_user, chat.chat_user,departments.department_name, chat.chat_type, chat.chat_status,chat.chatUrl, chat.rating_value, chat.widget_name, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg,chat_msg.chat_images, chat_msg.msg_status,chat_msg.extension, customer.customer_name,customer_email,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt,user.agent_name as names, user.user_name,substring(user.agent_name,1,instr(user.agent_name,' ')-1) as agent_name,user.chat_aviator,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id left join departments on chat.department = departments.dept_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id asc";
     // echo $chat_detail_qry;exit;
       
            $this->errorLog("demo",$chat_detail_qry);

       $parms = array();
       $result = $this->dataFetchAll($chat_detail_qry,array());
//print_r($result);exit;
        return $result;
        
        
    }
  
  function getChatDetailsWC($chat_msg_id){
    
     $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.rating_value, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
    
    //print_r($chat_detail_qry);exit;
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());

        return $result;
  
  
  }


function insertChatMessageWc($chat_data){
        	
        extract($chat_data);

		$countfiles = count($_FILES['up_files']['name']); 
		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
		$upload_location = $destination_path."ext-ticket-image/";
		$files_arr = array();  
	
	

		if($countfiles == 1){
			$filename = $_FILES['up_files']['name'];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['up_files']['name'], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['up_files']['tmp_name'],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
					$ext_arr[] = $ext;
				}
		
		} else {
			for($index = 0; $index < $countfiles; $index++){
			$filename = $_FILES['up_files']['name'][$index];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
					$ext_arr[] = $ext;
				}

			}
		}
		
	if($extension != "" && $extension != "null" && $extension != "undefined"){  //MOBILE APPS
	$qry = "select user_id from user where sip_login='$extension'";
      $msg_user_id = $this->fetchOne($qry, array());
	}
	
	//MOBILE APP NOTIFICATION 
	
/*	$chat_user_type = "SELECT * FROM chat_msg WHERE chat_id='$chat_id' and msg_user_type='$msg_user_type'";
	$chat_counts = $this->dataRowCount($chat_user_type, array());

	if($chat_counts == 0){
		
		$qrys = "UPDATE chat SET claim_status='1' WHERE chat_id='$chat_id'";
		$qry_results = $this->db_query($qrys, array());
		$usrname  = $this->fetchOne("SELECT agent_name FROM user WHERE user_id='$msg_user_id' ",array());
		
		$chat_msg_user_type = '7';
		$msg_type='text';
		$chat_msg_one='Chat claimed by '.$usrname;
	 $chat_msg_ides = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status,chat_images,extension) VALUES ('$chat_id', '$msg_user_id', '$chat_msg_user_type', '$msg_type', '$chat_msg_one', '1','','')", array());
				
				
		$get_omni_name ="SELECT agent_name FROM user WHERE user_id = $msg_user_id";
		 $omni_name = $this->fetchOne($get_omni_name, array());
		$dept_id_req = "SELECT department FROM chat WHERE chat_id='$chat_id'";
		 $dept_value = $this->fetchOne($dept_id_req, array());
		$send_dept_id =$dept_value;
		$dept_user_ids = "SELECT department_users FROM departments WHERE dept_id='$send_dept_id'";
		 $usrs_ids = $this->fetchOne($dept_user_ids, array());
		$get_ext_no = "SELECT sip_login FROM user WHERE user_id IN ($usrs_ids)";
		 $user_ext_no = $this->dataFetchAll($get_ext_no, array());
		foreach ($user_ext_no as $key => $value) {
			$chat_values[] = $value['sip_login'];
        } 
		
		$result_values = implode(",", $chat_values);

		$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://erp.cal4care.com/cms/apps/index.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "operation": "agents",
    "moduleType": "agents",
    "api_type": "web",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
    "element_data": {
        "action": "claimed_notification",
        "extension_no":"'.$result_values.'",
		"omni_agent_name":"'.$omni_name.'",
		"cust_name":"'.$customer_name.'"

    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

	//echo $response;exit;

	}	*/

		$files_array = $files_arr;
	    $extension_arr = $ext_arr;
		$ticketMedia = implode(",",$files_arr);	
		$files_arr = implode(",",$files_arr);
	    $ext_arr = implode(",",$ext_arr);

        
        $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status,chat_images,extension) VALUES ('$chat_id', '$msg_user_id', '$msg_user_type', '$msg_type', '$chat_msg', '1','$files_arr','$ext_arr')", array());
        
	
				
		$chat_detail_qry = "select chat.chat_id,chat.claim_status, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg,chat_msg.chat_images, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt,chat_msg.extension, user.user_name,user.agent_name,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
    
    
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());	
	


  $status = array('status' => 'true');
		$response_array = array('data' => $result);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
    }
  function insertchat_question($data){
      extract($data);    
      $qry_result = $this->db_query("INSERT INTO chat_question(admin_id,widget_id,question,answer) VALUES ( '$admin_id','$widget_id','$chat_question','$chat_answer')", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
    }
  function get_answer($data){
      extract($data);
      $chat_answer_qry = "SELECT answer FROM chat_question WHERE  admin_id='$admin_id' AND question='$chat_question'"; 
      $result = $this->fetchmydata($chat_answer_qry,array());
      return $result;
    }
  public function get_question($data){   
	   extract($data);
      $get_queue_qry = "SELECT * FROM chat_question WHERE admin_id='$admin_id' AND widget_id='$widget_id'"; 	 
      $result = $this->dataFetchAll($get_queue_qry,array());
      return $result;
   }
  public function edit_chatquestion($data)
   {
      extract($data);//print_r($data);exit;
      $qry = "select * from chat_question where id='$id' and admin_id='$admin_id'"; 
      $result = $this->fetchData($qry, array());  
      return $result;    
    }
  public function update_chatquestion($data){
      extract($data);//print_r($data);exit;
      $qry_result = "UPDATE chat_question SET widget_id='$widget_id',question='$chat_question',answer='$chat_answer' WHERE admin_id='$admin_id' AND id='$id'";
      $qry_result = $this->db_query($qry_result, array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;           
    }
	public function delete_chatquestion($data){
      extract($data);
      $qry = "Delete from chat_question where id='$id' and admin_id='$admin_id'";
      $qry_result = $this->db_query($qry, array());        
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
    }
	public function delete_pbx($data){
      extract($data);
      $qry = "Update pbx_settings SET delete_status='1' where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function get_chat_settings($data){
      extract($data);    
      $qry = "select chat_place,chat_aviator,office_in_time,office_out_time,offline_email,chat_agent_name from user where user_id='$user_id'"; 
      $result = $this->fetchData($qry, array());  
      return $result;
    } 
    public function chat_settings($data){
      extract($data);//print_r($data);			
	if($chat_aviator == 1 || $chat_aviator == "true" || $chat_aviator == "1")
	{$chat_aviator = 1;}
    else
	{$chat_aviator = 0;}
    if($chat_agent_name == 1 || $chat_agent_name == "true" || $chat_agent_name == "1")
	{ $chat_agent_name = 1; }
    else
	{ $chat_agent_name = 0; }	
		//echo "UPDATE user SET chat_place='$chat_place',chat_aviator='$chat_aviator',office_in_time='$office_in_time',office_out_time='$office_out_time',chat_agent_name='$chat_agent_name',offline_email='$offline_email' WHERE user_id='$user_id'";exit;
      $qry = "UPDATE user SET chat_place='$chat_place',chat_aviator='$chat_aviator',office_in_time='$office_in_time',office_out_time='$office_out_time',chat_agent_name='$chat_agent_name',offline_email='$offline_email' WHERE user_id='$user_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function chat_initiate($data){
      extract($data);
	  $explode = explode(':', $chat_time);
	  $first_value = $explode[0];
      $second_value = $explode[1];
      if($first_value < 10){
        $first_value = "0".$first_value;
        $chat_time_value = $first_value.":".$second_value;
      }else{
        $chat_time_value = $chat_time;
      }
      //echo $chat_time_value;exit;
      $dept_qry = "SELECT * FROM departments WHERE admin_id='$user_id' AND status=1 AND delete_status=0";    
      $userqry = "SELECT * FROM user WHERE user_id='$user_id'"; 
      $user_details = $this->fetchData($userqry, array()); 
      $office_in_time = $user_details['office_in_time']; 
      $office_out_time = $user_details['office_out_time'];
      if($chat_time_value < $office_in_time && $chat_time_value > $office_out_time){  
        $result["available_status"] = 0;     
        $result["chat_place"] = $user_details['chat_place'];
        $result["chat_aviator"] = $user_details['chat_aviator'];
        $result["department"] = $this->dataFetchAll($dept_qry,array());
        return $result;
      }
	  elseif($chat_time_value > $office_out_time){
		$result["available_status"] = 0;     
        $result["chat_place"] = $user_details['chat_place'];
        $result["chat_aviator"] = $user_details['chat_aviator'];
        $result["department"] = $this->dataFetchAll($dept_qry,array());
        return $result;
	  }
	  elseif($chat_time_value < $office_in_time){
		$result["available_status"] = 0;     
        $result["chat_place"] = $user_details['chat_place'];
        $result["chat_aviator"] = $user_details['chat_aviator'];
        $result["department"] = $this->dataFetchAll($dept_qry,array());
        return $result;
	  }
	  else{
        $result["available_status"] = 1;
        $result["chat_place"] = $user_details['chat_place'];
        $result["chat_aviator"] = $user_details['chat_aviator'];
        $result["department"] = $this->dataFetchAll($dept_qry,array());
        return $result;
      }
    }
	public function chat_offline_message($data){		
      extract($data);	  
      $admin_email_qry = "select * from user where user_id='$user_id'";   
      $admin_data = $this->fetchData($admin_email_qry, array());
	  $admin_id = $admin_data['admin_id'];
	  if($admin_id==1){
          $aid = $user_id;
      }else{
          $aid = $admin_id; 
      }
	  $widget_name_qry = "select widget_name from chat_widget where widget_name='$widget_name'";   
      $widget_name = $this->fetchOne($widget_name_qry, array());	
      $site = $admin_data['domain_name'];		
	  $offline_email_qry = "select offline_email from chat_widget where widget_name='$widget_name' and admin_id='$aid'"; 
      $offline_email = $this->fetchOne($offline_email_qry, array());
      $department_qry = "select department_name from departments where dept_id='$department'";   
      $department_name = $this->fetchOne($department_qry, array());
	  $chatdate = date('Y-m-d H:i:s');
      $dateOnly = date('Y-m-d', strtotime($chatdate));
      $dates = new DateTime($dateOnly);
      $chat_date = $dates->format("D, M d, Y");
	  $customer_email = 'noreply@mconnectapps.com';
      $from = 'Omni Channel';	
      require_once('class.phpmailer.php');
                  $subject = "Offline message sent by ".$name;
                  //$message = "Offline message sent on ".$chat_date."<br>Offline message sent by :".$name."<br>Site : ".$site."<br>Submitted From : ".$site."<br>Name : ".$name."<br>Email : ".$email."<br>Department : ".$department_name."<br>Message : ".$chat_message;
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" style="opacity: 1;" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
<head> 
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" /> 
<meta content="width=device-width, initial-scale=1.0" name="viewport" /> 
<!--[if !mso]><!--> 
<meta content="IE=edge" http-equiv="X-UA-Compatible" /> 
<!--<![endif]--> 
<title></title> 
<style type="text/css">/* RESET STYLES */
img, a img{border:0; height:auto; outline:none; text-decoration:none;}
body{height:100% !important; margin:0 auto !important; padding:0; width:100% !important;}
/* CLIENT-SPECIFIC STYLES */
img{-ms-interpolation-mode: bicubic;} /* Force IE to smoothly render resized images.
 */
#outlook a{padding:0;} /* Force Outlook 2007 and up to provide a "view in browser" message.
 */
table{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up.
 */
.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Outlook.com to display emails at full width.
 */
p, a, td{mso-line-height-rule:exactly;} /* Force Outlook to render line heights as they are originally set.
 */
p, a, td, body, table{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;} /* Prevent Windows- and Webkit-based mobile platforms from changing declared text sizes.
 */
.ExternalClass, .ExternalClass p, .ExternalClass td, .ExternalClass div, .ExternalClass span, .ExternalClass font{line-height:100%;} /* Force Outlook.com to display line heights normally.
 */
a[x-apple-data-detectors] {color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important;} /* attempt to control apple deata detection */
/* TEMPLATE STYLES */
    .mw300_desk{max-width:300px!important;width:300px!important;}
    .mbl_show{display:none!important;}   
@media screen and (max-width:500px){
     u + .body .gwfw {width:100% !important;width:100vw !important;}
    .mbl_show{display:inline-block !important;}
    .mw100{width:100% !important; max-width:100% !important;}
    .mw90{width: 100% !important; max-width:90% !important;}
    .miw90{width: 90% !important; max-width:90% !important; padding: 10px !important;}
    .w90{width:90% !important;}
    .w70{width:70% !important;}
    .ganga{min-width:100% !important;}
    .mbl_rm{display:none !important;}
    .mbl_img{content:url("https://omni.mconnectapps.com/api/v1.0/images/logo.png") !important; max-width:414px !important;}
    .mbl_dot{content:url("https://omni.mconnectapps.com/api/v1.0/images/mob2.png") !important; max-width:414px !important;}
    .mb_top_pd{padding-top: 0 !important;}
    .mb-text-center {text-align: center !important;}
    .mb-float-center {margin-left: auto !important; margin-right: auto !important; float: none !important; text-align: center !important;}
    .mb-bg-color {background-color: #ffffff !important }    
    .mb-logo-pd {padding: 20px 40px 10px !important }
    .mb_ctr{text-align: center !important; padding:0 10px 15px 10px !important}
}
   </style> 
<!--[if gte mso 9]><xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml><![endif]--> 
</head> 
<body id="body" bgcolor="#ffffff" class="mktoText body" style="margin: 0px; padding: 0px;"><style type="text/css">div#emailPreHeader{ display: none !important; }</style><div id="emailPreHeader" style="mso-hide:all; visibility:hidden; opacity:0; color:transparent; mso-line-height-rule:exactly; line-height:0; font-size:0px; overflow:hidden; border-width:0; display:none !important;">Two stages. Two days. Too awesome.</div>
<div class="mb_top_pd" bgcolor="#ffffff" style="background-color:#ffffff; width:100%;"> 
<!--[if mso | IE]> 
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="width:100%;" bgcolor="ffffff">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">        
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="680" align="center" style="width:680px;">              
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
    <![endif]--> 
<table align="center" border="0" cellpadding="0" cellspacing="0" class="gwfw" style="width:100%;max-width:680px;background-color:#ffffff; Margin:0 auto;"> 
<tbody> 
<tr> 
<td style="font-size:0;"></td> 
<td align="center" style="width:680px;background-color:#ffffff;" valign="top"> 
<div> 
<!--ALL CONTENT STARTS HERE--> 
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 680px;"> 
<tbody> 
<tr> 
<td> 
<!--start module 1--> 
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;padding-top: 0px;"> 
<tbody> 
<tr> 
<td background="https://omni.mconnectapps.com/api/v1.0/images/syn19_herobg.jpg" bgcolor="#f2efe1" height="600" style="-webkit-background-size: contain; -moz-background-size: contain; -o-background-size: contain; background-size: contain;background-repeat: no-repeat; background-position:left top;" valign="top" width="680"> 
<!--[if mso]>
                              <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:560pt;">
                              <v:fill type="frame" src="https://omni.mconnectapps.com/api/v1.0/images/syn19_herobg.jpg" color="#f2efe1"/>
                              <v:textbox style="mso-fit-shape-to-text:true" inset="0,0,0,0" style="v-text-anchor:bottom;">
                              <![endif]--> 
<div> 
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:94%;"> 
<tbody> 
<tr> 
<td style="mso-line-height-rule:at-least;font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif;font-size:20px;color:#ffffff;line-height:25px;text-align:center; padding: 30px 0 20px;"><a href=
"http://mconnectapps.com/" style="font-family:Helvetica,Arial,sans-serif; font-size:16px;color:#2F3033;text-decoration:none; "
><img alt="" border="0" src="https://omni.mconnectapps.com/api/v1.0/images/logo.png" border: 0; outline: 0; padding: 0; width: 100%; max-width: 300px; height: auto; margin: 0 auto; font-size: 26px; color: #111149;" width="300" />
</a></td> 
</tr> 
<tr align="center"> 
<td align="center" style="padding: 30px 25px 30px 25px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 24px; line-height: 30px; text-align: center; font-weight: 700;">- Offline Message Sent on '.$chat_date.' -<br />Chat on '.$widget_name.'</td> 
</tr> 
<tr> 
<td> 
<!--START Body Copy Section 4--> 
<table border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:680px;background-color:#f2efe1;"> 
<tbody> 
<tr> 
<td background="https://omni.mconnectapps.com/api/v1.0/images/syn19_bg2_2.jpg" valign="top" width="680" height="100%" bgcolor="#f2efe1" align="center" style="padding:20px 0 20px 0;background-color:#f2efe1;-webkit-background-size: contain; -moz-background-size: contain; -o-background-size: contain; background-size: contain;background-repeat: no-repeat; background-position:left top;"> 
<div> 
<table class="mw94" border="0" cellpadding="0" cellspacing="0" style="width:100%;background-color:#f2efe1;"> 
<tbody> 
<tr align="center"> 
<td align="center" style="padding:40px 25px 10px 25px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 24px; line-height: 26px; text-align: center; font-weight: 700;">Customer Details</td> 
</tr> 

<tr> 
<td style="padding: 10px 30px 10px 30px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 17px; line-height: 26px; text-align: left; font-weight: normal;"><strong style="color:#111149; min-width: 150px; width: 150px; float: left; display: inline-block;  text-decoration:none;">Name : </strong> '.$name.'</td> 
</tr>
<tr> 
<td style="padding: 0px 30px 10px 30px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 17px; line-height: 26px; text-align: left; font-weight: normal;"><strong style="color:#111149; min-width: 150px; width: 150px; float: left; display: inline-block;  text-decoration:none;">Email : </strong> '.$email.'</td> 
</tr>
<tr> 
<td style="padding: 0px 30px 10px 30px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 17px; line-height: 26px; text-align: left; font-weight: normal;"><strong style="color:#111149; min-width: 150px; width: 150px; float: left; display: inline-block;  text-decoration:none;">Department : </strong> '.$department_name.'</td> 
</tr>

<tr> 
<td style="padding: 0px 30px 40px 30px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 17px; line-height: 26px; text-align: left; font-weight: normal;"><strong style="color:#111149; min-width: 150px; width: 150px; float: left; display: inline-block;  text-decoration:none;">Question : </strong> '.$chat_message.'</td> 
</tr>

<tr> 
<td style="padding: 0px 30px 40px 30px;background-color:#ffffff;color: #111149; font-family: Post Grotesk, Avenir, Century Gothic, Helvetica, sans-serif; min-width: auto !important; font-size: 17px; line-height: 26px; text-align: left; font-weight: normal;"><strong style="color:#111149; min-width: 150px; width: 150px; float: left; display: inline-block;  text-decoration:none;">Reason : </strong> Offline Message</td> 
</tr>
</tbody> 
</table> 
</div> </td> 
</tr> 
</tbody> 
</table> 
<!--END Body Copy Section 4--></td> 
</tr> 


<tr> 
<td align="center" style="background-color: transparent; height:40px;"> 
 </td> 
</tr> 

</tbody> 
</table> 
</div> 
<!--[if mso]>
                                  <p style="margin:0;mso-hide:all"><o:p xmlns:o="urn:schemas-microsoft-com:office:office">&nbsp;</o:p></p>
                                  </v:textbox>
                                  </v:rect>
                                  <![endif]--> </td> 
</tr> 
</tbody> 
</table> 
<!--/end module 1--></td> 
</tr> 

<tr> 
<td align="center"> 
<!--START Footer--> 
<table align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:680px;background-color: rgba(107, 46, 119, 0.7);"> 
<tbody> 

</tbody> 
</table> 
<!--END Footer--></td> 
</tr> 
</tbody> 
</table> 
<!--/ALL CONTENT ENDS HERE--> 
</div> </td> 
<td style="font-size:0;"></td> 
</tr> 
</tbody> 
</table> 
<!--[if mso | IE]>
            </td>
</tr>
</table>
        </td>
</tr>
</table>
   <![endif]--> 
</div>

  
</body>
</html>';
	
		          $email ='noreply@mconnectapps.com'; 
                  $body = $message;                
                  $mail = new PHPMailer();
                  $mail->IsSMTP();
                  $mail->SMTPAuth = true; 
                  $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                  $mail->Host = 'smtpcorp.com'; // sets the SMTP server
                  $mail->Port = '2525';                    // set the SMTP port for the GMAIL server
                  $mail->Username = 'omni_erp'; // SMTP account username
                  $mail->Password = 'MGMyd3p2end0YnNi';        // SMTP account password 
                  $mail->SetFrom($customer_email, $name);
                  $mail->AddReplyTo($customer_email, $name);
		          $mail->isHTML(true);
                  $mail->Subject = $subject;
                  $mail->MsgHTML($body);
                  $mail->AddAddress($offline_email);    
                  $sendmail = $mail->Send();
                  if($sendmail){
                    $result = 1;
                  }else{
                    $result = 0;
                  }
                  return $result;
    }
	public function chat_rating($data){
      extract($data);
      $qry_result = "UPDATE chat SET rating_value='$rating_value' WHERE chat_id='$chat_id'";
      $qry_result = $this->db_query($qry_result, array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;           
    }
	/*public function chat_list($user_id){ 		
        //$chat_detail_qry = "SELECT chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, customer.customer_name,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name, departments.department_name FROM chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id LEFT JOIN customer on chat.chat_user = customer.customer_id LEFT JOIN user on chat_msg.msg_user_id = user.user_id LEFT JOIN departments on departments.admin_id = chat.admin_id WHERE chat.admin_id = '$user_id' ORDER BY chat_msg.chat_msg_id DESC";
		//echo "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, customer.customer_name,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat.created_dt, '%H:%i') as chat_time, date_format(chat.created_dt, '%d/%m/%Y') as chat_dt, departments.department_name from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id where chat.admin_id = '$user_id' order by chat.chat_id DESC";
		$chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, customer.customer_name,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat.created_dt, '%H:%i') as chat_time, date_format(chat.created_dt, '%d/%m/%Y') as chat_dt, departments.department_name from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id where chat.admin_id = '$user_id' order by chat.chat_id DESC";
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;          
    }*/
	
	
	public function chat_list($data){//print_r($data);exit;
    extract($data);
    $search_qry = "";
    if($search_text!= ""){
        $search_qry= " and (customer.customer_name like '%".$search_text."%' or departments.department_name like '%".$search_text."%' or customer.country like '%".$search_text."%')";
    }
		$admin_id=$this->fetchOne("select admin_id from user where user_id='$user_id'",array());
		//echo $admin_id;exit;
    if($admin_id=='1'){	
        $qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, (SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,customer.customer_name,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat.created_dt, '%H:%i') as chat_time, date_format(chat.created_dt, '%d/%m/%Y') as chat_dt, departments.department_name from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$user_id'".$search_qry;
        $chat_detail_qry = $qry." order by chat.chat_id DESC LIMIT ".$limit." Offset ".$offset;		
    }else{
    	$dept_id=$this->fetchOne("SELECT GROUP_CONCAT(dept_id) FROM `departments` where FIND_IN_SET('$user_id',department_users)",array());
    	$dept_id = str_replace("'", "'", $dept_id);	
      $qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, (SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent, customer.customer_name,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat.created_dt, '%H:%i') as chat_time, date_format(chat.created_dt, '%d/%m/%Y') as chat_dt, departments.department_name from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$admin_id' and chat.department IN ($dept_id)".$search_qry;
      $chat_detail_qry = $qry." order by chat.chat_id DESC LIMIT ".$limit." Offset ".$offset;	
    }
		//echo $chat_detail_qry;exit;
        $parms = array();        
        $result["list_data"] = $this->dataFetchAll($chat_detail_qry,$parms);
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;		
        return $result;          
  }
	
function sms_price_view($chat_data){
     extract($chat_data);
	$get_price="SELECT chat_sms.customer_name,user.user_name,chat_data_sms.chat_message,chat_sms.country_code,chat_sms.sender_id,chat_data_sms.sms_tarrif FROM `chat_data_sms` INNER JOIN user on user.user_id=chat_data_sms.agent_id INNER JOIN chat_sms on chat_sms.chat_id=chat_data_sms.chat_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' ORDER BY chat_data_sms.chat_msg_id desc";
	
	$get_bal="SELECT price_sms FROM `admin_details` where admin_id='$admin_id'";
	 $result["price_view"] = $this->dataFetchAll($get_price,array());
	 $result["sms_bal"] = $this->fetchOne($get_bal,array());
	 return $result;   
}
	function chat_det($data){
		     extract($data);
			$get_list="select  customer.customer_name,departments.department_name,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt ,chat.rating_value, customer.country from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id where chat.admin_id = '$admin_id' order by chat.chat_id DESC";
	 $result = $this->dataFetchAll($get_list,array());
	 return $result;   
		}
	function send_sms($data){
		print_r($data);exit;
		extract($data);
		$auth="select *,IF(admin_id = 1, user_id , admin_id ) as ad from user WHERE company_name='$company_name' and user_name='$username' and password='$password'";
		  $result_auth = $this->fetchData($auth, array());	
		$result_auth_num= $this->dataRowCount($auth,$parms);
		if($result_auth_num=='0'){
		return '5';
		}else{
			 $user_id = $result_auth['user_id'];
			 $admin_id=$result_auth['ad'];
			//if($res_admin!=$admin_id){
				//return '6';
			//}
		}
		// $tzone = $result_auth['timezone_id'];
		$tarrif_val = $this->get_sms_column($admin_id);
		$sel_country_cde="SELECT $tarrif_val from country where phonecode='$country_code'";
	 $sms_tarrif=$this->fetchOne($sel_country_cde, array());
	$result_counr= $this->dataRowCount($sel_country_cde,$parms);
	if($result_counr=='0'){
		return '2';
	}
		if($sms_tarrif=='' || $sms_tarrif=='0'){
		return '4';
	}
		 $len=strlen($message);
		 $sms_count=$len/160;
		 $cc= ceil($sms_count);
		 $spri=$cc*$sms_tarrif;
		
	  $sel_pr="SELECT price_sms,sms_type from admin_details where admin_id='$admin_id'";
	$res=$this->fetchData($sel_pr, array());
			$sel_price=$res['price_sms'];
			 $sms_type=$res['sms_type'];
			if($sms_type=='1'){
				$rate= $sel_price;
			}else{
	 $rate= $sel_price-$spri;
	if($rate<='0'){
		return '3';
	}		
			}
		if($admin_id=='572'){
			$sender='EDUQUEST';
		}else{$sender='480090209';}
		$ch_message=$message;
		$message = urlencode($message); 
		echo $phonenumber=$country_code.$phone_num;exit;
 /* $postData = array(
                'user' => 'cal4care',
                'password' => 'Godaddy123',
                //'phonenumber' => "+91".$phone_num,
	            'phonenumber' => $country_code.$phone_no,
                'text' => $message,
                'gsm_sender' => "$sender",
                'cdma_sender' => '82986675',
                'action' => 'send'
             );    
		//print_r($postData);exit;
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
  if($result == '1: Message sent successfully @  '){*/
				  		

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

echo $response = curl_exec($curl);exit;

curl_close($curl);
$res_out=substr($response,0,3);
	 if($res_out == '+OK'){
	  $del_stat = '1';
    }else{
      $del_stat = '0';
    }
if($del_stat=='1'){	
	$update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
		$qry_result = $this->db_query($update, array());		
	}
  $qry = "select * from user where user_id='$admin_id'";
  $result = $this->fetchData($qry, array());	
		 $tzone = $result['timezone_id'];
		if($tzone == 0){
	  //echo "if";exit;
    date_default_timezone_set('Asia/Singapore');   
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s");  
  }else{
	  //echo "else";exit;
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$tzone'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
	//  echo $user_timezone;exit;
	date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");	  
  }
		 $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `country_code`, `customer_name`, `customer_pnr`,`sender_id`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`, `updated_dt`) VALUES ('$phone_no', '$phone_no', '$country_code', '$phone_no', '','$sender_id', 'sms', '0', '1', '1', '$admin_id','$created_at','$updated_at')", array());
		
		 $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$ch_message','$del_stat', '1','$created_at','$updated_at','$spri','API')", array());
  $chat_data = $this->getChatDetails($chat_msg_id);
   $mc_event_data = "SMS To ".$phone_no;
		
		  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$admin_id','$phone_no','$mc_event_data','6','7','$created_at')", array());
  return '1';   
		
	}
	function get_sms_column($admin_id){
		extract($admin_id);
		 $get_tarrif="SELECT tarrif_id FROM `admin_details` where admin_id='$admin_id'";
		$id=$this->fetchOne($get_tarrif, array());

		$sel_country_cde="SELECT tarrif_name FROM `sms_plans` where id='$id'";
	    $sms_tarrif=$this->fetchOne($sel_country_cde, array());
		return $sms_tarrif;
	}
		
    function userRecentChat($chat_data){
		extract($chat_data);
		$encryption = $login;
		//$simple_string = "{'comp':'developers','uname':'developers','password':'developers'}"; 
		$ciphering = "AES-128-CTR"; 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		//$encryption_iv = '1234567891011121'; 
		//$encryption_key = "GeeksforGeeks"; 
		//$encryption = openssl_encrypt($simple_string, $ciphering,$encryption_key, $options, $encryption_iv); 
		//echo "Encrypted String: " . $encryption . "\n"; 
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
        
        $queue_chat_qry = "select chat_data_sms.chat_msg_id, chat_data_sms.read_status,chat_data_sms.chat_message, chat_data_sms.chat_id, date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.customer_name as customer_name, chat_sms.app_chat_id, chat_sms.api_type from chat_sms inner join chat_data_sms on chat_data_sms.chat_id = chat_sms.chat_id where chat_data_sms.chat_msg_id in (select max(chat_msg_id) from chat_data_sms group by chat_id order by chat_msg_id desc) and chat_sms.admin_id='$admin_id' order by chat_data_sms.chat_msg_id desc";
        
//echo $queue_chat_qry; exit;
        
        $parms = array();
        $result = $this->dataFetchAll($queue_chat_qry,array());
		foreach($result as &$v) {
		  $v['chat_message'] = utf8_encode($v['chat_message']);
		}
        return $result;
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
	
  function chatDetailListFromExternal($chat_data){
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
  
       // $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id asc";
    
    
  $chat_detail_qry = "select * from (select chat_data_sms.chat_msg_id, chat_data_sms.delivered_status as msg_status, chat_data_sms.msg_from as msg_user_type,chat_data_sms.chat_id,chat_data_sms.chat_message as chat_msg,date_format(chat_data_sms.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_sms.created_dt, '%H:%i') as chat_time, chat_sms.app_customer_key as customer_name,chat_data_sms.chat_pnr as chat_pnr, users.user_name,users.user_type,users.profile_image, chat_data_sms.agent_id as msg_user_id, chat_sms.api_type as msg_type from chat_data_sms inner join chat_sms on chat_sms.chat_id = chat_data_sms.chat_id left join user as users on users.user_id = chat_data_sms.agent_id where chat_sms.chat_id LIKE '".$chat_id."' order by chat_data_sms.chat_msg_id desc) result_data order by chat_msg_id asc";
        
  //echo $chat_detail_qry; exit;
        $parms = array();
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  
	 // print_r($result); exit;
		foreach($result as &$v) {
		  $v['chat_msg'] = utf8_encode($v['chat_msg']);
		}

        return $result;
        
        
    }
public function get_senderidE($login){
	
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


	    $qry = "select * from sender_id where delete_status != 1 and admin_id ='$admin_id'";
	    $qry_price = "SELECT price_sms FROM `admin_details` where admin_id ='$admin_id'";
		//return $this->dataFetchAll($qry, array("admin_id"=>$id));
		$parms = array();
            $result["sender_id"] = $this->dataFetchAll($qry, array("admin_id"=>$id));
			$result["sms_price"] = $this->fetchOne($qry_price, $parms);
		return $result;
	}

	
public function chat_details($data){
      extract($data); //print_r($data);exit;	  
	  $qry = "UPDATE chat SET chat_status='2' WHERE chat_id='$chat_id'";
      $qry_result = $this->db_query($qry, array()); 	
	  $admin_id_qry = "SELECT admin_id FROM chat WHERE chat_id='$chat_id'";
      $admin_id = $this->fetchOne($admin_id_qry,array());
      $domain_name_qry = "SELECT domain_name FROM user WHERE user_id='$admin_id'";
      $domain_name = $this->fetchOne($domain_name_qry,array());
      $chat_details_qry = "select * from chat where chat_id='$chat_id'";
      $chat_details = $this->fetchData($chat_details_qry, array()); 
      $chat_dept_id = $chat_details['department'];
      $customer_id = $chat_details['chat_user'];
      $chat_date = $chat_details['created_dt'];
	  $dateOnly = date('Y-m-d', strtotime($chat_date));
      $dates = new DateTime($dateOnly);
      $chat_start_day = $dates->format("D, M d, Y");
      $date = strtotime($chat_date);
      $chat_time = date('H:i', $date);
	  $chat_rating = $chat_details['rating_value'];
	  if($chat_rating != ''){
		  $reason = 'On close';		  
	  }else{
		  $reason = 'Abruptly closed';
		  $update="UPDATE chat SET chat_status='6' WHERE admin_id='$admin_id' AND chat_id='$chat_id'";
		  $qry_result = $this->db_query($update, array());
	  }
      $chat_msg_qry = "select * from chat_msg where chat_id='$chat_id' ORDER BY chat_msg_id ASC LIMIT 1";
      $chat_msg = $this->fetchData($chat_msg_qry, array());
      $first_chat_msg = $chat_msg['chat_msg'];
      $chat_dept_name_qry = "SELECT department_name FROM departments WHERE dept_id='$chat_dept_id'";
      $chat_dept_name = $this->fetchOne($chat_dept_name_qry,array());
      $customer_details_qry = "select * from customer where customer_id='$customer_id'";
      $customer_details = $this->fetchData($customer_details_qry, array());
      $customer_name = $customer_details['customer_name'];
      $customer_email = $customer_details['customer_email'];
      $customer_phone = $customer_details['phone_number']; 
      $customer_country = $customer_details['country'];
      $customer_ip = $customer_details['created_ip'];
$email_message = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<title>Omni Chat</title>
<!--[if !mso]><!==-->
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<!--<![endif]-->
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<style type="text/css">
hr.tacos {
font-family: Arial, sans-serif;
text-align: center;
line-height: 1px;
height: 1px;
font-size: 1em;
border: 0 none;
overflow: visible;
-webkit-box-sizing: content-box;
-moz-box-sizing: content-box;
-ms-box-sizing: content-box;
-o-box-sizing: content-box;
box-sizing: content-box;
}
hr.tacos:after {
content: "\01F32E\01F32E\01F32E\01F32E\01F32E";
display: inline;
border: 0px solid none;
}
x:-o-prefocus,
hr.tacos:after {
content: "";
}

.emvFields {
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
text-align: left;
}

.emvField {
line-height: normal;
font-size: 12px;
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
}

#outlook a {
padding: 0;
}

.ReadMsgBody {
width: 100%;
}

.ExternalClass {
width: 100%;
}

.ExternalClass * {
line-height: 100%;
}

body {
margin: 0;
padding: 0;
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}

table,
td {
border-collapse: collapse;
mso-table-lspace: 0pt;
mso-table-rspace: 0pt;
}

img {
border: 0;
height: auto;
line-height: 100%;
outline: none;
text-decoration: none;
-ms-interpolation-mode: bicubic;
}

p {
display: block;
margin: 13px 0;
}

.mobile-hide {
vertical-align: top;
}
</style>
<!--[if !mso]><!-->
<style type="text/css">
@media only screen and (max-width:480px) {
@-ms-viewport {
width: 320px;
}

@viewport {
width: 320px;
}
}
</style>
<!--<![endif]-->
<!--[if lte mso 11]>
<style type="text/css">
.outlook-group-fix {
width:100% !important;
}
</style>
<![endif]-->
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:700" rel="stylesheet" type="text/css">
<style type="text/css">
@import url("https://fonts.googleapis.com/css?family=PT+Sans:400,700");
@import url("https://fonts.googleapis.com/css?family=Montserrat:700");

.text-cta {
text-decoration: none !important;
border-bottom: 2px solid #FFFFFF !important;
}
</style>
<!--<![endif]-->
<style type="text/css">
@media screen yahoo {
 {
overflow: visible !important
}

.y-overflow-hidden {
overflow: hidden !important
}

.mobile-show {
display: none !important;
}
}

h1 {
font-size: 46px;
line-height: 48px;
margin: 0;
padding: 0;
}

h2 {
font-size: 32px;
line-height: 35px;
margin: 0;
padding: 0;
}

p {
font-size: 18px;
line-height: 24px;
margin: 0;
padding: 0;
}

.button td table tr td:hover,
.button td table tr td:hover a {
opacity: 0.8;
transition: opacity .25s ease-in-out;
-moz-transition: opacity .25s ease-in-out;
-webkit-transition: opacity .25s ease-in-out;
}

.store-location a {
color: #FFFFFF !important;
text-decoration: none !important;
}

.payment-cards a {
color: #000001 !important;
text-decoration: none !important;
}

.address a {
color: #000001 !important;
text-decoration: none !important;
}

u+.body .mj-inline-links {
width: 100%;
padding: 0;
margin: 0;
}

u+.body .mj-inline-links a {
font-size: 12px !important;
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
padding: 0;
margin: 0;
}

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 150px 50px 0;
}

.android-fix {
display: none !important;
}

@media all and (max-width: 480px) {

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 125px 50px 0;
}

.android-fix {
display: none !important;
}

.mobile-hide {
display: none !important;

}

.social-icons_container {
display: block !important;
width: 388px !important;
margin: 0 auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 100px !important;
overflow: visible !important;
visibility: visible !important;
}

.device-width {
width: 100% !important;
max-width: 100% !important;
float: none !important;
}

.mobile-txt {
padding: 0 15px !important;
text-align: center !important;
}

.mobile-bg-receipt {
background: #702082 top center no-repeat !important;
background-size: 100% auto !important;
}

.mobile-bg {
height: auto !important;
}

.navigation_header .mj-inline-links a {
font-size: 13px !important;
padding: 15px 10px !important;
}

.navigation_footer .mj-inline-links a img {
height: 68px !important;
width: auto !important;
}

.center-img img {
height: auto !important;
}

.hero-copy {
padding: 35px 35px 0 !important;
}

.product-row {
width: 340px !important;
margin: 0 auto !important;
}

.product-img {
height: 55px !important;
width: 55px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 55px !important;
}

.product-quantity-mobile-wrapper {
width: 38px;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}

.product-total {
width: 92% !important;
margin: 0 10px !important
}
}

/* Social Icons */
@media only screen and (max-device-width: 388px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}


.social-icons_container {
display: block !important;
width: 320px !important;
margin: 0 auto !important;
}

.social-icon {
width: 64px !important;
height: auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 64px !important;
overflow: visible !important;
width: 64px !important;
height: auto !important;
}

}

@media all and (max-width: 360px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}
}

@media all and (max-width: 320px) {
.mobile-hide {
display: none !important;

}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}

.product-row {
width: 300px !important;
margin: 0 auto !important;
}

.product-img {
height: 50px !important;
width: 50px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 50px !important;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}
}
</style>
<!--[if gte mso 9]>
<style type="text/css">
a {text-decoration: none;}
</style>
<![endif]-->
<style type="text/css">
@media only screen and (min-width:480px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 50px 50px 0;
}


.mj-column-per-100 {
width: 100% !important;
}
}
</style>
<!--[if gte mso 9]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
</head>

<body class="body">
<div class="mj-container">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<p style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"></p>

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->
<div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr class="center-img">
<td align="center" style="word-wrap:break-word;font-size:0px;padding:10px 25px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
<tr>
<td style="width:320px;">
<a href="http://links.tacobell.mkt8756.com/ctt?kn=5&ms=NDA0MzIwNzAS1&r=LTM5NDE1NDU4NDUS1&b=0&j=MTYwMTE0NzQyMAS2&mt=1&rt=0"
target="_blank" name="www_tacobell_com_utm_medium_email_2" ><img alt="TACO BELL"
  height="52"  src="https://erp.cal4care.com/images/edm/omni-chat/logo.png"
  style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 52px; width: inherit;"
  title="" width="320"></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>

<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082">
<table border="0" cellpadding="0" cellspacing="0" class="android-fix">
<tr>
<td class="android-fix" height="1" style="min-width:640px; font-size:0px;line-height:0px;"><img height="1"
src="https://erp.cal4care.com/images/edm/omni-chat/header-spacer.gif"
style="min-width: 640px; text-decoration: none; border: none; -ms-interpolation-mode: bicubic;"></td>
</tr>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;"
name="Cont_2" 
class="mobile-bg-receipt">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
<!--[if gte mso 9]>
<v:image xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block; width: 640px; height: 426px;" src="https://erp.cal4care.com/images/edm/omni-chat/bg_receipt_short.png" />
<v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block;position: absolute; width: 640px; height: 426px;">
<v:fill type="frame" opacity="0%" color="#702082" />
<v:textbox inset="0,0,0,0">
<![endif]-->
<div>
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr>
<td align="center" class="td-header">
<div style="cursor:auto;color:#FFFFFF;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:24px;line-height:32px;text-align:center;">
<strong>Chat on '.$widget_name.'</strong><br>
</div>
</td>
</tr>
<tr>
<td align="left" style="word-wrap:break-word;font-size:0px;padding:15px 50px 15px 0;">
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Name  </td>
<td style="color:#FFFFFF; font-family:PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Email  </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_email.'</td>
</tr>
</table>
<table>
<tr style="padding-bottom: 15px;">
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Department </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$chat_dept_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">IP </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_ip.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Country </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_country.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Reason </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$reason.'</td>
</tr>
</table>       

</td>
</tr>
</table>
</div>
<!--[if gte mso 9]>
</v:textbox>
</v:fill>
</v:rect>
</v:image>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td>
</tr>
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;background:#702082;">
<tr>
<td class="mobile-hide" valign="top" style="width: 10px;">&nbsp;</td>
<td valign="top">
<table bgcolor="#F2F2F2" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
width="100%" class="device-width" style="width:520px;">
<tr>
<td valign="top">
<img alt="" class="device-width"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-top.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%" height="44"></td>
</tr>
<tr>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="mobile-hide" width="25" valign="top" style="width:25px; padding: 10px;"></td>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="520" style="width:520px;"
class="device-width">
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 230px;">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:left;"
        valign="top">
        <span style="font-size: 12px; line-height: 24px; margin: 0; padding: 0;">Date: <strong>'.$dateOnly.'</strong></span>
      </td>
      </tr>      
    </table>
    <!--[if (gte mso 9)|(IE)]>
                  </td>
                  <td>
                <table width="245 align="right" cellpadding="0" cellspacing="0" border="0" style="width:245px;">
                  <tr>
                  <td>
                <![endif]-->


    <table align="right" cellpadding="0" cellspacing="0" class="device-width" role="presentation">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:right;"
        valign="top">
        <span style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"><strong>'.$chat_rating.'</strong> Rating
        (s)</span>
      </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
                  </table>
                <![endif]-->
    </td>
  </tr>
  </table>
</td>
</tr>

<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #54ca68;">Coversation start with Chatbot</span>
    </td>
  </tr>
  </table>
</td>
</tr>


<tr>
<td height="2" valign="top"></td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type==4){
$email_message.='<!-- Chat Agent Panel -->
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/chatbot.png"
          style="outline:none;text-decoration:none;border: none; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>Chatbot</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}
else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}
}
$email_message.='<tr>
<td height="10" valign="top"></td>
</tr>
<!-- Agent Conversation Start --->
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #318CE7;">&nbsp;Coversation assigned to Agent</span>
    </td>
  </tr>
  </table>
</td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='text' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type == '2'){
$email_message.='
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="'.$user_image.'"
		  style="outline: none;text-decoration: none;border: none;display: block;width: 45px;object-fit: cover;border-radius: 50%;height:45px;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>'.$user_name.'</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}}	  	  
$email_message.='<!------ Chat End ------>
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #ff0000;">&nbsp;Chat end by Customer&nbsp;</span>
    </td>
  </tr>
  </table>
</td>
</tr>
<!------ Chat End ------>

</table>
</td>
<td class="mobile-hide" valign="top" style="width:25px;padding: 10px;"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="10" style="font-size:35px;line-height:35px;height:35px;" valign="top"></td>
</tr>
<tr>
<td valign="top"><img alt="" class="device-width" height="44"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-btm.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%"></td>
</tr>
</table>
</td>
<td class="mobile-hide" valign="top" style="width:10px;">&nbsp;</td>
</tr>
<tr>
<td height="70" style="font-size:70px;line-height:70px;height:70px;" valign="top"></td>
</tr>
</table>
<div>
</div>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;" class="navigation_footer-outlook">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:35px 0px 70px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</div>


</body>

</html>


';
      
	  $admin_email_qry = "select offline_email from chat_widget where widget_name='$widget_name' and admin_id='$admin_id'";   
      $admin_email = $this->fetchOne($admin_email_qry, array());	  
	  require_once('class.phpmailer.php');
      $customer_email = 'noreply@mconnectapps.com';
      $from = 'Omni Channel';     
      $subject = "Chat transcript on ".$widget_name." started on ".$chat_start_day." , at ".$chat_time;		
	  $message = $email_message;	
      $body = $message;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtpcorp.com';
      $mail->Port = '2525';
      $mail->Username = 'erpdev2';
      $mail->Password = 'dnZ0ZjlyZ3RydzAw';
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
	  $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($admin_email);    
      $sendmail = $mail->Send();
      if($sendmail){		 
        $result = 1;
      }else{
        $result = 0;
      }
      return $result;
	}	







function compose_sms_external($chat_data){
	
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
	

$admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
$adminid = $this->fetchmydata($admin_id_qry,array());
if($adminid == 1){
$aid = $user_id;
}else{
$aid = $adminid;	
}
//echo $adminid;exit;	
if($aid == '870'){
	$chat_data = array("timezone_id"=>$timezone_id,"phone_num"=>$phone_num,"user_id"=>$user_id,"chat_msg"=>$chat_msg,"admin_id"=>$admin_id,"from_external"=>"1");
    //$result_data["result"]["status"] = true;
		$snd_message = $this->compose_sms_imap($chat_data);
		return $snd_message; exit;
}
if($user_id == 47 || $aid == 47){
	
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
	
}  elseif($user_id == 128 || $adminid == 128){
	$client = new Client();
	$response = $client->Messages->Send($phone_num,$chat_msg,$sender_id); 

	  if($response->Status == 'Queued'){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }

}
else{
	$sel="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id from user where user_id='$user_id'";
	$admin_id=$this->fetchOne($sel, array());
	
	 $tarrif_val = $this->get_sms_column($admin_id);
	$sel_country_cde="SELECT $tarrif_val from country where phonecode='$country_code'";
	$sms_tarrif=$this->fetchOne($sel_country_cde, array());
	$result_counr= $this->dataRowCount($sel_country_cde,$parms);
	if($result_counr=='0'){
		return '2';
	}
		 $len=strlen($chat_msg);
		 $sms_count=$len/160;
		 $cc= ceil($sms_count);
		 $spri=$cc*$sms_tarrif;
	if($sms_tarrif=='' || $sms_tarrif=='0'){
		return '4';
	}
		
	  $sel_pr="SELECT price_sms,sms_type from admin_details where admin_id='$admin_id'";
	$res=$this->fetchData($sel_pr, array());
			$sel_price=$res['price_sms'];
			 $sms_type=$res['sms_type'];
			if($sms_type=='1'){
				$rate= $sel_price;
			}else{
	 $rate= $sel_price-$spri;
	if($rate<='0'){
		return '3';
	}		
			}
	
	if($admin_id=='572'){
			$sender='EDUQUEST';
		}else{$sender='480090209';}
  $message = urlencode($chat_msg);  
  $phonenumber=$country_code.$phone_num;
 /* $postData = array(
                'user' => 'cal4care',
                'password' => 'Godaddy123',
                //'phonenumber' => "+91".$phone_num,
	            'phonenumber' => $country_code.$phone_num,
                'text' => $message,
                'gsm_sender' => "$sender",
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
  if($result == '1: Message sent successfully @  '){	*/
			  		

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://smsgw.mmea.mes.syniverse.com/sms.php?id=21336&pw=LLkZ5ok8&dnr=%2b$phonenumber&snr=%2b61480090209&msg=$message",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response = curl_exec($curl);

curl_close($curl);
$res_out=substr($response,0,3);
	 if($res_out == '+OK'){
      $del_stat = '1';
    }else{
      $del_stat = '0';
    }
}     
$qry = "select * from chat_sms where app_customer_key='$phone_num' and admin_id='$aid'";
$result = $this->fetchData($qry, array());
	//echo count($result);exit;
	if($del_stat=='1'){	
	$update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
		$qry_result = $this->db_query($update, array());
		
	}
if($result > 0){	
  $chat_id=$result['chat_id'];           
  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
  $user_timezone = $this->fetchmydata($user_timezone_qry,array());	
  date_default_timezone_set($user_timezone);
  $created_at = date("Y-m-d H:i:s");
  $updated_at = date("Y-m-d H:i:s");  
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt, updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());	
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';      
}else {
  $qry = "select * from user where user_id='$user_id'";
  $result = $this->fetchData($qry, array());	
  if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
  $tzone = $result['timezone_id'];
  if($tzone == 0){
	  //echo "if";exit;
    date_default_timezone_set('Asia/Singapore');   
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s");  
  }else{
	  //echo "else";exit;
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$tzone'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
	  //echo $user_timezone;exit;
	date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");	  
  }		
  $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `country_code`, `customer_name`, `customer_pnr`,`sender_id`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`, `updated_dt`) VALUES ('$phone_num', '$phone_num', '$country_code', '$phone_num', '','$sender_id', 'sms', '0', '1', '1', '$admin_id','$created_at','$updated_at')", array());
	
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';          
}
}	



function generate_incoming_message_from_outer($data){
	  extract($data);
	

	
      $user_timezone_qry = "SELECT admin_id FROM external_sms_details WHERE admin_num='$to'";
      $admin_id = $this->fetchmydata($user_timezone_qry,array());
	  $country_code = $this->fetchmydata("SELECT country_code FROM external_sms_details WHERE admin_num='$to'",array());
	  $user_id = $admin_id;
	  $timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());
      $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
	  $pn = $phone_num;

    
    //$user_id = '612';
		if(substr( $phone_num, 0, 3 ) === "+60"){	
			$country_code = '+60';
			$phone_num = str_replace("+60","",$phone_num);
		} if(substr( $phone_num, 0, 3 ) === "+91"){	
			$country_code = '+91';
			$phone_num = str_replace("+91","",$phone_num);
		} else {
			$phone_num = str_replace($country_code,"",$phone_num);
		}
	
	



     $qry = "select * from chat_sms where app_customer_key='$phone_num'";
     $result = $this->fetchData($qry, array());
	

	 

    if($result > 0){
          $chat_id=$result['chat_id'];
	 $qry = "select * from chat_data_sms where chat_pnr LIKE '%$customer_pnr%'";
     $resultss = $this->fetchData($qry, array());
		

		if($resultss > 0){ 
			 exit;
		} else {
	    $mc_event_data ="Received SMS from $phone_num";
		$sms_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_sms='1'", array());
		$sms_users = explode(',',$sms_users['GROUP_CONCAT(user_id)']);
			$sms_users[] = $admin_id;
			$udm = array("text"=>"$mc_event_data","user_id"=>$sms_users,"chat_id"=>$chat_id);
		    $u = $this->send_notificationSMS($udm);
						
	
		
		
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type,msg_from,agent_id, chat_message,read_status, delivered_status, chat_status,created_dt,send_by,chat_pnr) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1','1', '1','$created_at','Using Omni SMS panel','$customer_pnr')", array());
          
			foreach($sms_users as $user){
		$this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt,admin_id) VALUES('$user','$chat_id','$mc_event_data','6','7','$created_at','$admin_id')", array());
					//$udm = array("text"=>"$mc_event_data","user_id"=>$user,"chat_id"=>$chat_id);
			       // $u = $this->send_notificationSMS($udm);
				}
            print_r($chat_msg_id); exit;
			}
      
    } else {
      $qry = "select * from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
            if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $user_id; }
		
			$qry = "INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`created_at`, `country_code`) VALUES ('$phone_num', '$phone_num', '$phone_num', '$customer_pnr', 'sms', '0', '1', '1', '$admin_id','$created_at','$country_code')";	
		//print_r($qry); exit;
 			$chat_id = $this->db_insert($qry, array());
		
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message,read_status, delivered_status, chat_status, created_dt,send_by,chat_pnr) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1','1', '1','$created_at','Using Own SMS panel','$customer_pnr')", array());
		$chat_id=$this->fetchOne("SELECT chat_id FROM `chat_data_sms` where chat_msg_id='$chat_msg_id'",array());	
          $chat_data = $this->getChatDetails($chat_msg_id);
		$mc_event_data = "New SMS From ".$phone_num;
		
		   
		
$sms_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_sms='1'", array());
		$sms_users = explode(',',$sms_users['GROUP_CONCAT(user_id)']);
		$sms_users[] = $admin_id;
		           $udm = array("text"=>"$mc_event_data","user_id"=>$sms_users,"chat_id"=>$chat_id);
			        $u = $this->send_notificationSMS($udm);
				$sms_users[] = $admin_id;

		//$this->db_query("INSERT INTO mc_event (user_id,mc_event_data,mc_event_type,event_status,created_dt,admin_id) VALUES('$user_id','$mc_event_data','6','7','$created_at','$admin_id')", array());
	foreach($sms_users as $user){				
		$this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt,admin_id) VALUES('$user','$chat_id','$mc_event_data','6','7','$created_at','$admin_id')", array());
					//$udm = array("text"=>"$mc_event_data","user_id"=>$user,"chat_id"=>$chat_id);
			       // $u = $this->send_notificationSMS($udm);
				}
		    
          print_r($chat_msg_id); exit;
    } 
			
}


function compose_sms_imap($chat_data){
extract($chat_data); 
	//print_r($chat_data); echo "compose_sms_imap"; exit;

	$timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());
	$admin_qry = "SELECT * FROM external_sms_details WHERE admin_id='$admin_id'";
	$res = $this->fetchData($admin_qry,array());
	//print_r($res);
	$admin_num =  $res['admin_num'];
	if($admin_id = '870'){
		$p_num = $phone_num."@sms.a-com.tech";
		$a_num = $admin_num."@agedcaredecisions.com.au";
	}
	//echo $admin_num;
	  $subject = "New SMS from $admin_num in Inbox for ".$phone_num;
	//echo $subject; exit;
	  require_once('class.phpmailer.php');
      $body = $chat_msg;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtp.office365.com';
      $mail->Port = '587';
      $mail->Username = 'pbx@agedcaredecisions.com.au';
      $mail->Password = 't45xNI@3KF$jOMT8';
      $mail->setFrom($a_num, '');
      $mail->addAddress($p_num, $phone_num);
	  $mail->isHTML(false);
      $mail->Subject = $subject;
      $mail->Body = $body;
      $sendmail = $mail->Send();
      if($sendmail){		 
        $del_stat = 1;
      }else{
        $del_stat = 0;
      }
     // return $result;

$user_id = $chat_data['user_id'];
$admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
$adminid = $this->fetchmydata($admin_id_qry,array());
if($adminid == 1){
$aid = $user_id;
}else{
$aid = $adminid;	
}
    
$qry = "select * from chat_sms where app_customer_key='$phone_num' and admin_id='$aid'";
	//echo $qry; exit;
$result = $this->fetchData($qry, array());
	if($del_stat=='1'){	
	$update="UPDATE admin_details SET price_sms='$rate' where admin_id='$admin_id'";
	$qry_result = $this->db_query($update, array());	
}
if($result > 0){	
  $chat_id=$result['chat_id'];           
  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
  $user_timezone = $this->fetchmydata($user_timezone_qry,array());	
	// echo $user_timezone; exit;
  date_default_timezone_set($user_timezone);
  $created_at = date("Y-m-d H:i:s");
  $updated_at = date("Y-m-d H:i:s"); 
	
	$chat_msg = str_replace("'", "\'", $chat_msg);
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt, updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());	
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';      
}else {
	$user_id = $chat_data['user_id'];
	// print_r($chat_data); echo "compose_sms_imap"; exit
  $qry = "select * from user where user_id='$user_id'";
  $result = $this->fetchData($qry, array());	
  	if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
  $tzone = $result['timezone_id'];
	//echo $tzone; exit;
  if($tzone == 0){
	  //echo "if";exit;
    date_default_timezone_set('Asia/Singapore');   
	$created_at = date("Y-m-d H:i:s");
	$updated_at = date("Y-m-d H:i:s");  
  }else{
	  //echo "else";exit;
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$tzone'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
	  //echo $user_timezone;exit;
	date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");	  
  }		



  $chat_id = $this->db_insert("INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `country_code`, `customer_name`, `customer_pnr`,`sender_id`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`, `created_at`, `updated_dt`) VALUES ('$phone_num', '$phone_num', '$country_code', '$phone_num', '','$sender_id', 'sms', '0', '1', '1', '$admin_id','$created_at','$updated_at')", array());
	
  $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,updated_dt,sms_tarrif,send_by) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','$del_stat', '1','$created_at','$updated_at','$spri','Using Omni SMS panel')", array());
  $chat_data = $this->getChatDetails($chat_msg_id);
  $mc_event_data = "SMS To ".$phone_num;
  $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$user_id','$aid','$chat_id','$mc_event_data','6','7','$created_at')", array());
  return '1';          
}
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
public function listsms($data){	

            extract($data);		

            $search_qry = "";
            if($search_text != ""){
                $search_qry = " and (chat_sms.customer_name like '%".$search_text."%' )";
            }

            $qry = "SELECT chat_sms.customer_name,user.user_name,chat_data_sms.chat_message,chat_sms.country_code,chat_sms.sender_id,chat_data_sms.sms_tarrif FROM `chat_data_sms` INNER JOIN user on user.user_id=chat_data_sms.agent_id INNER JOIN chat_sms on chat_sms.chat_id=chat_data_sms.chat_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' ".$search_qry; 
            $detail_qry = $qry."ORDER BY chat_data_sms.chat_msg_id desc limit ".$limit." Offset ".$offset;
           // echo $qry;exit;
		$get_bal="SELECT price_sms FROM `admin_details` where admin_id='$admin_id'";	
			$parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
			$result["sms_bal"] = $this->fetchOne($get_bal,array());
            return $result;
        }	

public function get_chatBotQA($data){    
	//echo $url;exit;
/*$encryption = $url;
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
		$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id; */
//echo $url;exit;
	extract($data);
	$admin_id = $url;
	$widget_id= $this->fetchOne("SELECT id FROM chat_widget WHERE widget_name='$widget_name' AND admin_id='$admin_id'", array());
 // $get_queue_qry = "SELECT question FROM chat_question where admin_id='$admin_id'";     
  $questio = $this->dataFetchAll("SELECT question FROM chat_question WHERE admin_id='$admin_id' AND widget_id='$widget_id'",array());
	foreach($questio as $question){
		$questions[][] = $question['question'];
	}
  $answersAll = $this->dataFetchAll("SELECT answer FROM chat_question WHERE admin_id='$admin_id' AND widget_id='$widget_id'",array());
	foreach($answersAll as $answer){
		$answers[][] = $answer['answer'];
	}	
	
  $alternatives = $this->dataFetchAll("SELECT question FROM chat_question where admin_id='$admin_id' AND status = 2" ,array());	
  $keyWords = $this->dataFetchAll("SELECT keyword FROM chatcloseKeyWords where admin_id='$admin_id'" ,array());
  $keyWords = $keyWords[0];	
  $alterAll[] = $alternatives[0]['question'];
  $result["questions"] = $questions;
  $result["answer"] = $answers;
  $result["alternative"] = $alterAll;
  $result["keyWord"] = $keyWords['keyword'];
  return $result;
}
public function chatbot_det($data){   

	extract($data);
	
		$encryption = $url;
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
		$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;
	//echo "INSERT INTO chatbot_user(email,phone_no,admin_id) values ('$email','$ph_no','$admin_id')";exit;
	 $qry_result = $this->db_query("INSERT INTO chatbot_user(email,phone_no,admin_id,country_code) values ('$email','$ph_no','$admin_id','$country_code')", array());
			$result = $qry_result == 1 ? 1 : 0;

            return $result;
}
	function send_notification($data){
		extract($data);
			$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$user_id' AND `notification_code`!=''";
			$agentresult = $this->fetchData($agentqry, array());	
			$token = $agentresult['notification_code'];
	$host_name='https://'.$_SERVER['HTTP_HOST'];
//echo $token;exit;		
	$url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = $text;
    //$body = "Hello I am from Your php server";
	$click_url = $host_name.'/#/';
    $notification = array('title' =>$title, 'text' => $text, 'notification_for'=>'Call', 'click_action'=>'', 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
		//echo $response;exit;
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
		return '0';
    }else{
		return '1';
	}
		
    curl_close($ch);
		 

		
	}
	function send_notificationSMS($data){
	
	      	extract($data);
		
			/*$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$user_id' AND `notification_code`!=''";
			$agentresult = $this->fetchData($agentqry, array());	
			$token = $agentresult['notification_code'];

	$host_name='https://'.$_SERVER['HTTP_HOST'];

	$url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = $text;
	$click_url = $host_name.'/#/sms';  	 		 
    $notification = array('title' =>$title, 'text' => $text, 'notification_for'=>'SMS', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => '');
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
    $response = curl_exec($ch);
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
		return '0';
    }else{
		return '1';
	}		
    curl_close($ch);*/
		
$host_name='https://'.$_SERVER['HTTP_HOST'];
$click_url = $host_name.'/#/sms'; 
$socket = "https://myscoket.mconnectapps.com:4027";
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
    $client->emit('broadcast', ['title' =>$text, 'notification_for'=>'SMS', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name,'user_id'=>$user_id]);
		
	}
	public function chat_list_report($data){ 	
         extract($data);//print_r($data);exit;
		 $admin_id=$this->fetchOne("select admin_id from user where user_id='$user_id'",array());
		//echo $admin_id;exit;
if($admin_id=='1'){	
		//$chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$user_id' order by chat.chat_id DESC";
	if($from_date !=='' and $to_date !== '' and $from_date !== $to_date){   
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$user_id' and DATE(chat.created_dt) >= '$from_date' and DATE(chat.created_dt) <= '$to_date' order by chat.chat_id DESC";
    } 
    elseif($from_date == $to_date){
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$user_id' and DATE(chat.created_dt) like '%$from_date%' order by chat.chat_id DESC";
    } 
    else {
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$user_id' order by chat.chat_id DESC";
    }
}else{	
	$dept_id=$this->fetchOne("SELECT GROUP_CONCAT(dept_id) FROM `departments` where FIND_IN_SET('$user_id',department_users)",array());
	$dept_id = str_replace("'", "'", $dept_id);
	/*$chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$admin_id' and chat.department IN ($dept_id) order by chat.chat_id DESC";*/
	if($from_date !=='' and $to_date !== '' and $from_date !== $to_date){  		
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$admin_id' and chat.department IN ($dept_id)  and DATE(created_dt) >= '$from_date' and DATE(created_dt) <= '$to_date' order by chat.chat_id DESC";
    } 
    elseif($from_date == $to_date){        
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$admin_id' and chat.department IN ($dept_id) and DATE(created_dt) like '%$from_date%' order by chat.chat_id DESC";
    } 
    else {		
        $chat_detail_qry = "select customer.customer_name,(SELECT agent_name from user where user_id = (select MAX(msg_user_id) from chat_msg WHERE chat_id = chat.chat_id and msg_user_type=2)) as chat_agent,departments.department_name,customer.city,customer.country,chat.rating_value,chat.chatUrl,date_format(chat.created_dt, '%d/%m/%Y') as chat_dt from chat left join customer on chat.chat_user = customer.customer_id left join departments on departments.admin_id = chat.admin_id  and departments.dept_id=chat.department where chat.admin_id = '$admin_id' and chat.department IN ($dept_id) order by chat.chat_id DESC";
    }
}
		//echo $chat_detail_qry;exit;
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());
        return $result;          
    }

public function chat_closedby_user($data){
      extract($data); //print_r($data);exit;	  
	    $qry = "UPDATE chat SET online_status='0', chat_status='2' WHERE chat_id='$chat_id'";
      $qry_result = $this->db_query($qry, array()); 	
	    $admin_id_qry = "SELECT admin_id FROM chat WHERE chat_id='$chat_id'";
      $admin_id = $this->fetchOne($admin_id_qry,array());
      $domain_name_qry = "SELECT domain_name FROM user WHERE user_id='$admin_id'";
      $domain_name = $this->fetchOne($domain_name_qry,array());
      $chat_details_qry = "select * from chat where chat_id='$chat_id'";
      $chat_details = $this->fetchData($chat_details_qry, array()); 
      $chat_dept_id = $chat_details['department'];
      $customer_id = $chat_details['chat_user'];
      $chat_date = $chat_details['created_dt'];
	    $dateOnly = date('Y-m-d', strtotime($chat_date));
      $dates = new DateTime($dateOnly);
      $chat_start_day = $dates->format("D, M d, Y");
      $date = strtotime($chat_date);
      $chat_time = date('H:i', $date);
	    $chat_rating = $chat_details['rating_value'];
	    $reason = 'On close';    
      $chat_msg_qry = "select * from chat_msg where chat_id='$chat_id' ORDER BY chat_msg_id ASC LIMIT 1";
      $chat_msg = $this->fetchData($chat_msg_qry, array());
      $first_chat_msg = $chat_msg['chat_msg'];
      $chat_dept_name_qry = "SELECT department_name FROM departments WHERE dept_id='$chat_dept_id'";
      $chat_dept_name = $this->fetchOne($chat_dept_name_qry,array());
      $customer_details_qry = "select * from customer where customer_id='$customer_id'";
      $customer_details = $this->fetchData($customer_details_qry, array());
      $customer_name = $customer_details['customer_name'];
      $customer_email = $customer_details['customer_email'];
      $customer_phone = $customer_details['phone_number']; 
      $customer_country = $customer_details['country'];
      $customer_ip = $customer_details['created_ip'];
$email_message = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<title>Omni Chat</title>
<!--[if !mso]><!==-->
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<!--<![endif]-->
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<style type="text/css">
hr.tacos {
font-family: Arial, sans-serif;
text-align: center;
line-height: 1px;
height: 1px;
font-size: 1em;
border: 0 none;
overflow: visible;
-webkit-box-sizing: content-box;
-moz-box-sizing: content-box;
-ms-box-sizing: content-box;
-o-box-sizing: content-box;
box-sizing: content-box;
}
hr.tacos:after {
content: "\01F32E\01F32E\01F32E\01F32E\01F32E";
display: inline;
border: 0px solid none;
}
x:-o-prefocus,
hr.tacos:after {
content: "";
}

.emvFields {
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
text-align: left;
}

.emvField {
line-height: normal;
font-size: 12px;
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
}

#outlook a {
padding: 0;
}

.ReadMsgBody {
width: 100%;
}

.ExternalClass {
width: 100%;
}

.ExternalClass * {
line-height: 100%;
}

body {
margin: 0;
padding: 0;
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}

table,
td {
border-collapse: collapse;
mso-table-lspace: 0pt;
mso-table-rspace: 0pt;
}

img {
border: 0;
height: auto;
line-height: 100%;
outline: none;
text-decoration: none;
-ms-interpolation-mode: bicubic;
}

p {
display: block;
margin: 13px 0;
}

.mobile-hide {
vertical-align: top;
}
</style>
<!--[if !mso]><!-->
<style type="text/css">
@media only screen and (max-width:480px) {
@-ms-viewport {
width: 320px;
}

@viewport {
width: 320px;
}
}
</style>
<!--<![endif]-->
<!--[if lte mso 11]>
<style type="text/css">
.outlook-group-fix {
width:100% !important;
}
</style>
<![endif]-->
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:700" rel="stylesheet" type="text/css">
<style type="text/css">
@import url("https://fonts.googleapis.com/css?family=PT+Sans:400,700");
@import url("https://fonts.googleapis.com/css?family=Montserrat:700");

.text-cta {
text-decoration: none !important;
border-bottom: 2px solid #FFFFFF !important;
}
</style>
<!--<![endif]-->
<style type="text/css">
@media screen yahoo {
 {
overflow: visible !important
}

.y-overflow-hidden {
overflow: hidden !important
}

.mobile-show {
display: none !important;
}
}

h1 {
font-size: 46px;
line-height: 48px;
margin: 0;
padding: 0;
}

h2 {
font-size: 32px;
line-height: 35px;
margin: 0;
padding: 0;
}

p {
font-size: 18px;
line-height: 24px;
margin: 0;
padding: 0;
}

.button td table tr td:hover,
.button td table tr td:hover a {
opacity: 0.8;
transition: opacity .25s ease-in-out;
-moz-transition: opacity .25s ease-in-out;
-webkit-transition: opacity .25s ease-in-out;
}

.store-location a {
color: #FFFFFF !important;
text-decoration: none !important;
}

.payment-cards a {
color: #000001 !important;
text-decoration: none !important;
}

.address a {
color: #000001 !important;
text-decoration: none !important;
}

u+.body .mj-inline-links {
width: 100%;
padding: 0;
margin: 0;
}

u+.body .mj-inline-links a {
font-size: 12px !important;
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
padding: 0;
margin: 0;
}

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 150px 50px 0;
}

.android-fix {
display: none !important;
}

@media all and (max-width: 480px) {

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 125px 50px 0;
}

.android-fix {
display: none !important;
}

.mobile-hide {
display: none !important;

}

.social-icons_container {
display: block !important;
width: 388px !important;
margin: 0 auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 100px !important;
overflow: visible !important;
visibility: visible !important;
}

.device-width {
width: 100% !important;
max-width: 100% !important;
float: none !important;
}

.mobile-txt {
padding: 0 15px !important;
text-align: center !important;
}

.mobile-bg-receipt {
background: #702082 top center no-repeat !important;
background-size: 100% auto !important;
}

.mobile-bg {
height: auto !important;
}

.navigation_header .mj-inline-links a {
font-size: 13px !important;
padding: 15px 10px !important;
}

.navigation_footer .mj-inline-links a img {
height: 68px !important;
width: auto !important;
}

.center-img img {
height: auto !important;
}

.hero-copy {
padding: 35px 35px 0 !important;
}

.product-row {
width: 340px !important;
margin: 0 auto !important;
}

.product-img {
height: 55px !important;
width: 55px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 55px !important;
}

.product-quantity-mobile-wrapper {
width: 38px;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}

.product-total {
width: 92% !important;
margin: 0 10px !important
}
}

/* Social Icons */
@media only screen and (max-device-width: 388px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}


.social-icons_container {
display: block !important;
width: 320px !important;
margin: 0 auto !important;
}

.social-icon {
width: 64px !important;
height: auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 64px !important;
overflow: visible !important;
width: 64px !important;
height: auto !important;
}

}

@media all and (max-width: 360px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}
}

@media all and (max-width: 320px) {
.mobile-hide {
display: none !important;

}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}

.product-row {
width: 300px !important;
margin: 0 auto !important;
}

.product-img {
height: 50px !important;
width: 50px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 50px !important;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}
}
</style>
<!--[if gte mso 9]>
<style type="text/css">
a {text-decoration: none;}
</style>
<![endif]-->
<style type="text/css">
@media only screen and (min-width:480px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 50px 50px 0;
}


.mj-column-per-100 {
width: 100% !important;
}
}
</style>
<!--[if gte mso 9]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
</head>

<body class="body">
<div class="mj-container">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<p style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"></p>

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->
<div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr class="center-img">
<td align="center" style="word-wrap:break-word;font-size:0px;padding:10px 25px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
<tr>
<td style="width:320px;">
<a href="http://links.tacobell.mkt8756.com/ctt?kn=5&ms=NDA0MzIwNzAS1&r=LTM5NDE1NDU4NDUS1&b=0&j=MTYwMTE0NzQyMAS2&mt=1&rt=0"
target="_blank" name="www_tacobell_com_utm_medium_email_2" ><img alt="TACO BELL"
  height="52"  src="https://erp.cal4care.com/images/edm/omni-chat/logo.png"
  style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 52px; width: inherit;"
  title="" width="320"></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>

<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082">
<table border="0" cellpadding="0" cellspacing="0" class="android-fix">
<tr>
<td class="android-fix" height="1" style="min-width:640px; font-size:0px;line-height:0px;"><img height="1"
src="https://erp.cal4care.com/images/edm/omni-chat/header-spacer.gif"
style="min-width: 640px; text-decoration: none; border: none; -ms-interpolation-mode: bicubic;"></td>
</tr>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;"
name="Cont_2" 
class="mobile-bg-receipt">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
<!--[if gte mso 9]>
<v:image xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block; width: 640px; height: 426px;" src="https://erp.cal4care.com/images/edm/omni-chat/bg_receipt_short.png" />
<v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block;position: absolute; width: 640px; height: 426px;">
<v:fill type="frame" opacity="0%" color="#702082" />
<v:textbox inset="0,0,0,0">
<![endif]-->
<div>
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr>
<td align="center" class="td-header">
<div style="cursor:auto;color:#FFFFFF;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:24px;line-height:32px;text-align:center;">
<strong>Chat on '.$widget_name.'</strong><br>
</div>
</td>
</tr>
<tr>
<td align="left" style="word-wrap:break-word;font-size:0px;padding:15px 50px 15px 0;">
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Name  </td>
<td style="color:#FFFFFF; font-family:PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Email  </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_email.'</td>
</tr>
</table>
<table>
<tr style="padding-bottom: 15px;">
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Department </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$chat_dept_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">IP </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_ip.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Country </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_country.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Reason </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$reason.'</td>
</tr>
</table>       

</td>
</tr>
</table>
</div>
<!--[if gte mso 9]>
</v:textbox>
</v:fill>
</v:rect>
</v:image>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td>
</tr>
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;background:#702082;">
<tr>
<td class="mobile-hide" valign="top" style="width: 10px;">&nbsp;</td>
<td valign="top">
<table bgcolor="#F2F2F2" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
width="100%" class="device-width" style="width:520px;">
<tr>
<td valign="top">
<img alt="" class="device-width"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-top.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%" height="44"></td>
</tr>
<tr>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="mobile-hide" width="25" valign="top" style="width:25px; padding: 10px;"></td>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="520" style="width:520px;"
class="device-width">
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 230px;">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:left;"
        valign="top">
        <span style="font-size: 12px; line-height: 24px; margin: 0; padding: 0;">Date: <strong>'.$dateOnly.'</strong></span>
      </td>
      </tr>      
    </table>
    <!--[if (gte mso 9)|(IE)]>
                  </td>
                  <td>
                <table width="245 align="right" cellpadding="0" cellspacing="0" border="0" style="width:245px;">
                  <tr>
                  <td>
                <![endif]-->


    <table align="right" cellpadding="0" cellspacing="0" class="device-width" role="presentation">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:right;"
        valign="top">
        <span style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"><strong>'.$chat_rating.'</strong> Rating
        (s)</span>
      </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
                  </table>
                <![endif]-->
    </td>
  </tr>
  </table>
</td>
</tr>

<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #54ca68;">Coversation start with Chatbot</span>
    </td>
  </tr>
  </table>
</td>
</tr>


<tr>
<td height="2" valign="top"></td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type==4){
$email_message.='<!-- Chat Agent Panel -->
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/chatbot.png"
          style="outline:none;text-decoration:none;border: none; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>Chatbot</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}
else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}
}
$email_message.='<tr>
<td height="10" valign="top"></td>
</tr>
<!-- Agent Conversation Start --->
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #318CE7;">&nbsp;Coversation assigned to Agent</span>
    </td>
  </tr>
  </table>
</td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='text' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type == '2'){
$email_message.='
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="'.$user_image.'"
          style="outline: none;text-decoration: none;border: none;display: block;width: 45px;object-fit: cover;border-radius: 50%;height:45px;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>'.$user_name.'</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}}	  	  
$email_message.='<!------ Chat End ------>
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #ff0000;">&nbsp;Chat end by Customer&nbsp;</span>
    </td>
  </tr>
  </table>
</td>
</tr>
<!------ Chat End ------>

</table>
</td>
<td class="mobile-hide" valign="top" style="width:25px;padding: 10px;"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="10" style="font-size:35px;line-height:35px;height:35px;" valign="top"></td>
</tr>
<tr>
<td valign="top"><img alt="" class="device-width" height="44"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-btm.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%"></td>
</tr>
</table>
</td>
<td class="mobile-hide" valign="top" style="width:10px;">&nbsp;</td>
</tr>
<tr>
<td height="70" style="font-size:70px;line-height:70px;height:70px;" valign="top"></td>
</tr>
</table>
<div>
</div>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;" class="navigation_footer-outlook">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:35px 0px 70px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</div>


</body>

</html>


';
      
	  $admin_email_qry = "select offline_email from chat_widget where widget_name='$widget_name' and admin_id='$admin_id'";   
      $admin_email = $this->fetchOne($admin_email_qry, array());	  
	  require_once('class.phpmailer.php');
      $customer_email = 'noreply@mconnectapps.com';
      $from = 'Omni Channel';     
      $subject = "Chat transcript on ".$widget_name." started on ".$chat_start_day." , at ".$chat_time;		
	  $message = $email_message;	
      $body = $message;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtpcorp.com';
      $mail->Port = '2525';
      $mail->Username = 'erpdev2';
      $mail->Password = 'dnZ0ZjlyZ3RydzAw';
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
	    $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($admin_email);    
      $sendmail = $mail->Send();
      if($sendmail){		 
        $result = 1;
      }else{
        $result = 0;
      }
      return $result;
}	
	


function add_ChatClose_keywords($data){
	  extract($data);	
		//print_r($data); exit;
	  $qry = "select * from user where user_id='$user_id'";//echo $qry;exit;
	  $result = $this->fetchData($qry, array());
	 // print_r($result);exit;	
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }
	  $qry = "select * from chatcloseKeyWords where keyword LIKE '%$keyword%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO chatcloseKeyWords(keyword,user_id,admin_id) VALUES ('$keyword','$user_id','$admin_id')";
		  //echo $qry;exit;
        $qry_result = $this->db_query($qry, array());           
		if($qry_result == 1){
		  $result = 1;              
		}
		else{                
		  $result = 0;
		}            
		return $result;
	  }
    }
	
		public function list_ChatClose_keywords($data){
	  extract($data);
	  $qry = "select user_type,admin_id from user where user_id='$user_id'";
	  $res = $this->fetchsingledata($qry, array());	
	  if($res['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $res['admin_id']; 
	  }
	    $qry = "select * from chatcloseKeyWords where admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
	}
  public function edit_ChatClose_keywords($id){	
	 
	  $qry = "select * from chatcloseKeyWords where id ='$id'";
	   //echo $qry; exit;
	  return $this->dataFetchAll($qry, array());
	}
	
		  
   function update_ChatClose_keywords($data){
	  extract($data);	
      $qry = "UPDATE chatcloseKeyWords SET keyword='$keyword' where id='$id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }			  
public function delete_ChatClose_keywords($key_id){     
     $qry = "Delete FROM chatcloseKeyWords WHERE id='$key_id'";
     $qry_result = $this->db_query($qry, array());
     if($qry_result == 1){
      $result = 1;
     }else{
      $result = 0;
    }
    return $result;
  }
	
	function overallChatSettings($id){
		$chat_detail_qry = "select * from overall_chat_settings where admin_id ='$id'";
		$result = $this->dataFetchAll($chat_detail_qry,array());
		return $result;
	}
	
	
function add_overallChatSettings($data){
	  extract($data);	
	
	  $qry = "select * from overall_chat_settings where admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO overall_chat_settings(round_robin,offline_chat,admin_id) VALUES ('$round_robin','$offline_chat','$admin_id')";
		  //echo $qry;exit;
        $qry_result = $this->db_query($qry, array());           
		if($qry_result == 1){
		  $result = 1;              
		}
		else{                
		  $result = 0;
		}            
		return $result;
	  }
    }
	



	function overallChatSettingsUpdate($data){
		extract($data);
		$chat_detail_qry = "UPDATE overall_chat_settings SET round_robin='$round_robin',offline_chat='$offline_chat' where admin_id ='$admin_id'";
		$result = $this->db_query($chat_detail_qry,array());
		$result == 1 ? 1 : 0;
		if($result == 1){
				$chat_detail_qry = "select * from overall_chat_settings where admin_id ='$admin_id'";
				$result = $this->dataFetchAll($chat_detail_qry,array());
		} else {
			$result = false;
		}
		return $result;
	}
	

public function mobile_admin_mail($data){
      extract($data); //print_r($data);exit;	  
	//     $qry = "UPDATE chat SET chat_status='2' WHERE chat_id='$chat_id'";
    //   $qry_result = $this->db_query($qry, array()); 	
	    $admin_id_qry = "SELECT admin_id FROM chat WHERE chat_id='$chat_id'";
      $admin_id = $this->fetchOne($admin_id_qry,array());
      $domain_name_qry = "SELECT domain_name FROM user WHERE user_id='$admin_id'";
      $domain_name = $this->fetchOne($domain_name_qry,array());
      $chat_details_qry = "select * from chat where chat_id='$chat_id'";
      $chat_details = $this->fetchData($chat_details_qry, array()); 
      $chat_dept_id = $chat_details['department'];
      $customer_id = $chat_details['chat_user'];
      $chat_date = $chat_details['created_dt'];
	    $dateOnly = date('Y-m-d', strtotime($chat_date));
      $dates = new DateTime($dateOnly);
      $chat_start_day = $dates->format("D, M d, Y");
      $date = strtotime($chat_date);
      $chat_time = date('H:i', $date);
	    $chat_rating = $chat_details['rating_value'];
	    // $reason = 'On close';    
	    $reason = 'Total Chat';    
      $chat_msg_qry = "select * from chat_msg where chat_id='$chat_id' ORDER BY chat_msg_id ASC LIMIT 1";
      $chat_msg = $this->fetchData($chat_msg_qry, array());
      $first_chat_msg = $chat_msg['chat_msg'];
      $chat_dept_name_qry = "SELECT department_name FROM departments WHERE dept_id='$chat_dept_id'";
      $chat_dept_name = $this->fetchOne($chat_dept_name_qry,array());
      $customer_details_qry = "select * from customer where customer_id='$customer_id'";
      $customer_details = $this->fetchData($customer_details_qry, array());
      $customer_name = $customer_details['customer_name'];
      $customer_email = $customer_details['customer_email'];
      $customer_phone = $customer_details['phone_number']; 
      $customer_country = $customer_details['country'];
      $customer_ip = $customer_details['created_ip'];
$email_message = '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<title>Omni Chat</title>
<!--[if !mso]><!==-->
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<!--<![endif]-->
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<style type="text/css">
hr.tacos {
font-family: Arial, sans-serif;
text-align: center;
line-height: 1px;
height: 1px;
font-size: 1em;
border: 0 none;
overflow: visible;
-webkit-box-sizing: content-box;
-moz-box-sizing: content-box;
-ms-box-sizing: content-box;
-o-box-sizing: content-box;
box-sizing: content-box;
}
hr.tacos:after {
content: "\01F32E\01F32E\01F32E\01F32E\01F32E";
display: inline;
border: 0px solid none;
}
x:-o-prefocus,
hr.tacos:after {
content: "";
}

.emvFields {
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
text-align: left;
}

.emvField {
line-height: normal;
font-size: 12px;
font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;
color: #000000;
}

#outlook a {
padding: 0;
}

.ReadMsgBody {
width: 100%;
}

.ExternalClass {
width: 100%;
}

.ExternalClass * {
line-height: 100%;
}

body {
margin: 0;
padding: 0;
-webkit-text-size-adjust: 100%;
-ms-text-size-adjust: 100%;
}

table,
td {
border-collapse: collapse;
mso-table-lspace: 0pt;
mso-table-rspace: 0pt;
}

img {
border: 0;
height: auto;
line-height: 100%;
outline: none;
text-decoration: none;
-ms-interpolation-mode: bicubic;
}

p {
display: block;
margin: 13px 0;
}

.mobile-hide {
vertical-align: top;
}
</style>
<!--[if !mso]><!-->
<style type="text/css">
@media only screen and (max-width:480px) {
@-ms-viewport {
width: 320px;
}

@viewport {
width: 320px;
}
}
</style>
<!--<![endif]-->
<!--[if lte mso 11]>
<style type="text/css">
.outlook-group-fix {
width:100% !important;
}
</style>
<![endif]-->
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:700" rel="stylesheet" type="text/css">
<style type="text/css">
@import url("https://fonts.googleapis.com/css?family=PT+Sans:400,700");
@import url("https://fonts.googleapis.com/css?family=Montserrat:700");

.text-cta {
text-decoration: none !important;
border-bottom: 2px solid #FFFFFF !important;
}
</style>
<!--<![endif]-->
<style type="text/css">
@media screen yahoo {
 {
overflow: visible !important
}

.y-overflow-hidden {
overflow: hidden !important
}

.mobile-show {
display: none !important;
}
}

h1 {
font-size: 46px;
line-height: 48px;
margin: 0;
padding: 0;
}

h2 {
font-size: 32px;
line-height: 35px;
margin: 0;
padding: 0;
}

p {
font-size: 18px;
line-height: 24px;
margin: 0;
padding: 0;
}

.button td table tr td:hover,
.button td table tr td:hover a {
opacity: 0.8;
transition: opacity .25s ease-in-out;
-moz-transition: opacity .25s ease-in-out;
-webkit-transition: opacity .25s ease-in-out;
}

.store-location a {
color: #FFFFFF !important;
text-decoration: none !important;
}

.payment-cards a {
color: #000001 !important;
text-decoration: none !important;
}

.address a {
color: #000001 !important;
text-decoration: none !important;
}

u+.body .mj-inline-links {
width: 100%;
padding: 0;
margin: 0;
}

u+.body .mj-inline-links a {
font-size: 12px !important;
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
padding: 0;
margin: 0;
}

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 150px 50px 0;
}

.android-fix {
display: none !important;
}

@media all and (max-width: 480px) {

.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 125px 50px 0;
}

.android-fix {
display: none !important;
}

.mobile-hide {
display: none !important;

}

.social-icons_container {
display: block !important;
width: 388px !important;
margin: 0 auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 100px !important;
overflow: visible !important;
visibility: visible !important;
}

.device-width {
width: 100% !important;
max-width: 100% !important;
float: none !important;
}

.mobile-txt {
padding: 0 15px !important;
text-align: center !important;
}

.mobile-bg-receipt {
background: #702082 top center no-repeat !important;
background-size: 100% auto !important;
}

.mobile-bg {
height: auto !important;
}

.navigation_header .mj-inline-links a {
font-size: 13px !important;
padding: 15px 10px !important;
}

.navigation_footer .mj-inline-links a img {
height: 68px !important;
width: auto !important;
}

.center-img img {
height: auto !important;
}

.hero-copy {
padding: 35px 35px 0 !important;
}

.product-row {
width: 340px !important;
margin: 0 auto !important;
}

.product-img {
height: 55px !important;
width: 55px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 55px !important;
}

.product-quantity-mobile-wrapper {
width: 38px;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}

.product-total {
width: 92% !important;
margin: 0 10px !important
}
}

/* Social Icons */
@media only screen and (max-device-width: 388px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}


.social-icons_container {
display: block !important;
width: 320px !important;
margin: 0 auto !important;
}

.social-icon {
width: 64px !important;
height: auto !important;
}

.mobile-show {
display: block !important;
max-height: 100px !important;
max-width: 64px !important;
overflow: visible !important;
width: 64px !important;
height: auto !important;
}

}

@media all and (max-width: 360px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 100px 50px 0;
}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}
}

@media all and (max-width: 320px) {
.mobile-hide {
display: none !important;

}

.navigation_header .mj-inline-links a {
font-size: 12px !important;
padding: 15px 10px !important;
}

.product-row {
width: 300px !important;
margin: 0 auto !important;
}

.product-img {
height: 50px !important;
width: 50px !important;
}

.product-name {
font-size: 16px !important;
line-height: 20px !important;
}

.product-price {
font-size: 16px !important;
line-height: 20px !important;
}

.product-quantity {
width: 50px !important;
}

.product-descriptions {
width: 94% !important;
margin: 0 10px !important
}
}
</style>
<!--[if gte mso 9]>
<style type="text/css">
a {text-decoration: none;}
</style>
<![endif]-->
<style type="text/css">
@media only screen and (min-width:480px) {
.td-header {
word-wrap: break-word;
font-size: 0px;
padding: 50px 50px 0;
}


.mj-column-per-100 {
width: 100% !important;
}
}
</style>
<!--[if gte mso 9]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
</head>

<body class="body">
<div class="mj-container">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<p style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"></p>

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->
<div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr class="center-img">
<td align="center" style="word-wrap:break-word;font-size:0px;padding:10px 25px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
<tr>
<td style="width:320px;">
<a href="http://links.tacobell.mkt8756.com/ctt?kn=5&ms=NDA0MzIwNzAS1&r=LTM5NDE1NDU4NDUS1&b=0&j=MTYwMTE0NzQyMAS2&mt=1&rt=0"
target="_blank" name="www_tacobell_com_utm_medium_email_2" ><img alt="TACO BELL"
  height="52"  src="https://erp.cal4care.com/images/edm/omni-chat/logo.png"
  style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 52px; width: inherit;"
  title="" width="320"></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>

<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082">
<table border="0" cellpadding="0" cellspacing="0" class="android-fix">
<tr>
<td class="android-fix" height="1" style="min-width:640px; font-size:0px;line-height:0px;"><img height="1"
src="https://erp.cal4care.com/images/edm/omni-chat/header-spacer.gif"
style="min-width: 640px; text-decoration: none; border: none; -ms-interpolation-mode: bicubic;"></td>
</tr>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;"
name="Cont_2" 
class="mobile-bg-receipt">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0px;">
<!--[if gte mso 9]>
<v:image xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block; width: 640px; height: 426px;" src="https://erp.cal4care.com/images/edm/omni-chat/bg_receipt_short.png" />
<v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style=" border: 0;display: inline-block;position: absolute; width: 640px; height: 426px;">
<v:fill type="frame" opacity="0%" color="#702082" />
<v:textbox inset="0,0,0,0">
<![endif]-->
<div>
<table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
<tr>
<td align="center" class="td-header">
<div style="cursor:auto;color:#FFFFFF;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:24px;line-height:32px;text-align:center;">
<strong>Chat on '.$widget_name.'</strong><br>
</div>
</td>
</tr>
<tr>
<td align="left" style="word-wrap:break-word;font-size:0px;padding:15px 50px 15px 0;">
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Name  </td>
<td style="color:#FFFFFF; font-family:PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Email  </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_email.'</td>
</tr>
</table>
<table>
<tr style="padding-bottom: 15px;">
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Department </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$chat_dept_name.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">IP </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_ip.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Country </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$customer_country.'</td>
</tr>
</table>
<table>
<tr>
<td width="50"></td>
<td width="120" style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">Reason </td>
<td style="color:#FFFFFF; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24px; margin: 0; padding: 0 0 15px 0;">'.$reason.'</td>
</tr>
</table>       

</td>
</tr>
</table>
</div>
<!--[if gte mso 9]>
</v:textbox>
</v:fill>
</v:rect>
</v:image>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td>
</tr>
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;background:#702082;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;background:#702082;">
<tr>
<td class="mobile-hide" valign="top" style="width: 10px;">&nbsp;</td>
<td valign="top">
<table bgcolor="#F2F2F2" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
width="100%" class="device-width" style="width:520px;">
<tr>
<td valign="top">
<img alt="" class="device-width"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-top.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%" height="44"></td>
</tr>
<tr>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="mobile-hide" width="25" valign="top" style="width:25px; padding: 10px;"></td>
<td valign="top">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="520" style="width:520px;"
class="device-width">
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 230px;">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:left;"
        valign="top">
        <span style="font-size: 12px; line-height: 24px; margin: 0; padding: 0;">Date: <strong>'.$dateOnly.'</strong></span>
      </td>
      </tr>      
    </table>
    <!--[if (gte mso 9)|(IE)]>
                  </td>
                  <td>
                <table width="245 align="right" cellpadding="0" cellspacing="0" border="0" style="width:245px;">
                  <tr>
                  <td>
                <![endif]-->


    <table align="right" cellpadding="0" cellspacing="0" class="device-width" role="presentation">
      <tr>
      <td class="mobile-txt" style="cursor:auto;color:#000000;font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:18px;line-height:25px;text-align:right;"
        valign="top">
        <span style="font-size: 18px; line-height: 24px; margin: 0; padding: 0;"><strong>'.$chat_rating.'</strong> Rating
        (s)</span>
      </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
                    </td>
                  </tr>
                  </table>
                <![endif]-->
    </td>
  </tr>
  </table>
</td>
</tr>

<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #54ca68;">Coversation start with Chatbot</span>
    </td>
  </tr>
  </table>
</td>
</tr>


<tr>
<td height="2" valign="top"></td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type==4){
$email_message.='<!-- Chat Agent Panel -->
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/chatbot.png"
          style="outline:none;text-decoration:none;border: none; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>Chatbot</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}
else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}
}
$email_message.='<tr>
<td height="10" valign="top"></td>
</tr>
<!-- Agent Conversation Start --->
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #318CE7;">&nbsp;Coversation assigned to Agent</span>
    </td>
  </tr>
  </table>
</td>
</tr>';
$chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name,user.profile_image from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '".$chat_id."' AND chat_msg.msg_type='text' order by chat_msg.chat_msg_id ASC";
	  $result = $this->dataFetchAll($chat_detail_qry,array());
	  $items = '';
	  foreach($result as $record){
        $chat_time = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        $message = $record['chat_msg'];
		$user_name = $record['user_name'];
		$user_profile_image = $record['profile_image'];
	  if($user_profile_image==''){
		  $user_image = 'https://omni.mconnectapps.com/api/v1.0/images/user.png';
	  }else{
		  $user_image = $user_profile_image;
	  }
if($chat_user_type == '2'){
$email_message.='
<tr>
<td valign="top">
  <table align="center" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>
    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Breakfast Crunchwrap®"
          class="product-img"  src="'.$user_image.'"
		  style="outline: none;text-decoration: none;border: none;display: block;width: 45px;object-fit: cover;border-radius: 50%;height:45px;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

    <td valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="product-descriptions"
      width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:left;" valign="top">
        <span class="product-name" style="font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color: #919191;"><i>'.$user_name.'</i></strong> <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#7e7e7e;
        ">
        [ '.$chat_time.' ]
        </span><br />
          '.$message.'
        </span>
      </td>
      </tr>
      
    </table>
    </td>
  </tr>
  </table>
</td>
</tr>
<!-- Chat Agent Panel End -->
<tr>
<td height="20"></td>
</tr>';
}else{
$email_message.='<!-- Chat Customer Panel -->
<tr>
<td valign="top">
  <table align="left" border="0" cellpadding="0" cellspacing="0" class="product-row"
  width="100%">
  <tr>                         
    <td valign="top">
    <table border="0" align="right" cellpadding="0" cellspacing="0" class="product-descriptions" width="300">
      <tr>
      <td style="cursor:auto;color:#000000;text-align:right;" valign="top">
        <span style="font-weight: 500; font-family: Montserrat, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 13px;line-height:22px;">
          <strong style="color:#919191"><i>'.$customer_name.'</i></strong>
        <span class="Order_Item.Product_Modifications-quantity" style="font-weight: bold; padding-left: 10px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;
        color:#7e7e7e;"> [ '.$chat_time.' ] <br />
        </span>
          '.$message.'
        </span>
      </td>
      </tr>
    </table>
    </td>

    <td valign="top" class="product-quantity-mobile-wrapper">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="device-width"
      style="width: 100%; max-width: 100px;">
      <tr>
      <td valign="top">
      <img alt="Customer"
          class="product-img"  src="https://erp.cal4care.com/images/edm/omni-chat/user.png"
          style="outline:none;text-decoration:none;border: none; margin-left: 20px; display: block;"
          width="40" height="40"></td>
      </tr>
    </table>
    </td>

  </tr>
  </table>
</td>
</tr>
<!-- Chat Customer Panel End -->

<tr>
<td height="10" valign="top"></td>
</tr>';
}}	  	  
$email_message.='<!------ Chat End ------>
<tr>
<td valign="top" style="padding: 15px">
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="payment-cards" style="font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:24px;color:#fff;text-align:center;"
    valign="top">
    <span style=" padding: 6px 16px; border-radius: 15px; font-family: PT Sans, Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 14px;line-height:24px;color: #fff; text-align:center;background-color: #ff0000;">&nbsp;Chat end by Customer&nbsp;</span>
    </td>
  </tr>
  </table>
</td>
</tr>
<!------ Chat End ------>

</table>
</td>
<td class="mobile-hide" valign="top" style="width:25px;padding: 10px;"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="10" style="font-size:35px;line-height:35px;height:35px;" valign="top"></td>
</tr>
<tr>
<td valign="top"><img alt="" class="device-width" height="44"  src="https://erp.cal4care.com/images/edm/omni-chat/img_receipt-btm.jpg"
style="max-width: 100%; border: none; border-radius: 0px; display: block; font-size: 13px; outline: none; text-decoration: none; height: 44px; width: 100%;"
title="" width="100%"></td>
</tr>
</table>
</td>
<td class="mobile-hide" valign="top" style="width:10px;">&nbsp;</td>
</tr>
<tr>
<td height="70" style="font-size:70px;line-height:70px;height:70px;" valign="top"></td>
</tr>
</table>
<div>
</div>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;" class="navigation_footer-outlook">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="640" align="center" style="width:640px;">
<tr>
<td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
<![endif]-->
<div style="margin:0px auto;max-width:640px;">
<table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="font-size:0px;width:100%;">
<tr>
<td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:35px 0px 70px;">
<!--[if mso | IE]>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="vertical-align:top;width:640px;">
<![endif]-->

<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</div>
<!--[if mso | IE]>
</td></tr></table>
<![endif]-->
</div>


</body>

</html>


';
	
      if($user_id != '' && $user_id != 'undefined'){

	  $admin_email_qry="select email_id from user where user_id='$user_id'";
	  }else{
	   $admin_email_qry = "select offline_email from chat_widget where widget_name='$widget_name' and admin_id='$admin_id'";   
	  }
	  $admin_email = $this->fetchOne($admin_email_qry, array());	  
	  require_once('class.phpmailer.php');
      $customer_email = 'noreply@mconnectapps.com';
      $from = 'Omni Channel';     
      $subject = "Chat transcript on ".$widget_name." started on ".$chat_start_day." , at ".$chat_time;		
	  $message = $email_message;	
      $body = $message;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtpcorp.com';
      $mail->Port = '2525';
      $mail->Username = 'erpdev2';
      $mail->Password = 'dnZ0ZjlyZ3RydzAw';
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
	    $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($admin_email);    
      $sendmail = $mail->Send();
      if($sendmail){		 
        $result = 1;
      }else{
        $result = 0;
      }
      return $result;
}
	
public function chatTransfer($data){
		extract($data);
		$qry = "UPDATE chat SET assigned_user='$user_id' WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$usrname  = $this->fetchOne("SELECT agent_name FROM user WHERE user_id='$user_id' ",array());
		
		$msg_user_type = '6';
		$msg_type='text';
		$chat_msg='Chat Transfered to '.$usrname;
	 $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status,chat_images,extension) VALUES ('$chat_id', '$from_user_id', '$msg_user_type', '$msg_type', '$chat_msg', '1','','')", array());
	
			$chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg,chat_msg.chat_images, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt,chat_msg.extension, user.user_name,user.agent_name,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
		$result = $this->fetchData($chat_detail_qry,array());
		
		//$result = $chat_msg_id == 1 ? 1 : 0;
		return $result;
	}
public function revokeTransfer($data){
		extract($data);
		$qry = "UPDATE chat SET assigned_user=0 WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
public function updateOnOffStatus($data){
		extract($data);
		$qry = "UPDATE chat SET online_status='$onoff_status' WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;

}	
public function chatAgents($data){
		extract($data);
		$dept_ids  = $this->fetchOne("SELECT departments FROM chat_widget WHERE widget_name='$widget_id' AND admin_id='$admin_id'",array());

		$agent_ids = $this->fetchData("SELECT GROUP_CONCAT(department_users) as users_id FROM departments WHERE dept_id IN ($dept_ids)",array());
		
		//print_r("SELECT GROUP_CONCAT(department_users) as users_id FROM departments WHERE dept_id IN ($dept_ids)");exit;
		$unjoin_id = explode(',', $agent_ids['users_id']);
        $rm_duplicate = array_unique($unjoin_id);
        $join_id = implode(",", $rm_duplicate);
		
        // $enable_depts = array();
        // foreach ($enable_dept as $new_enable_value) {
        //     $users_value = $new_enable_value['department_users'];
        //     $get_ext_no = $this->fetchOne("select GROUP_CONCAT(sip_login) as ext_no from user where user_id in ($users_value)", array());
        //     $new_enable_value["get_ext_no"] = $get_ext_no;
        //     array_push($enable_depts, $new_enable_value);
        // }
	
		$user_details = $this->dataFetchAll("SELECT user_id,agent_name,sip_login,profile_image,login_status FROM user WHERE user_id IN ($join_id)",array());
	
	$counts = count($user_details);
	
 		$enable_depts = array();	
	foreach ($user_details as $new_enable_value) {
//		for ($i=0;$i<$counts;$i++){
		  $users_value = $user_details['user_id'];	
				
			//$get_dept_name = $this->fetchOne("SELECT GROUP_CONCAT(department_name) FROM departments WHERE department_users = ($users_value) AND dept_id in ($dept_ids)",array());
			
			$get_dept_name =$this->dataFetchAll("SELECT department_name FROM departments WHERE dept_id IN ($dept_ids) AND department_users LIKE ('%$users_value%')",array());

			$new_enable_value["dept_name"] = $get_dept_name;
			array_push($enable_depts, $new_enable_value);
			
		}


		return $enable_depts;
	

}
public function onoff_status($data){
		extract($data);	    
		$qry = "UPDATE chat_widget SET $type='$value' WHERE id='$widget_id' AND admin_id='$admin_id'";
	    //echo $qry;exit;
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
public function copy_chat_question($data){
		extract($data);	    
	    $parms = array();
		$selectqry = 	"SELECT * FROM `chat_question` WHERE id IN ($chat_question_id) AND admin_id='$admin_id'";
	    $result = $this->dataFetchAll($selectqry,array());
	    //print_r($result);exit;
	    foreach($result as $data){
		  $question = $data['question'];
		  $answer = $data['answer'];	
		  $insertqry = 	"INSERT INTO chat_question (admin_id,question,answer,widget_id) VALUES ('$admin_id','$question','$answer','$widget_id')";
		  $insert_data = $this->db_query($insertqry, $params);	
		}
	    return $insert_data;    
}	

function chatMessagePanel($login,$queue_id,$search_text,$limit,$offset){
		
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

// $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user, chat.chat_status, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.chat_queue in ($queue_condtion) and chat.chat_type in (1,2) ".$search_qry." order by chat.chat_id desc";

$qry = "select * from user where user_id='$user_id'";
$result = $this->fetchData($qry, array());

if($result['user_type'] == '2')
{
    
  $admin_id = $user_id;		 
  $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user, chat.chat_status, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where  chat.admin_id='$admin_id' and chat.chat_type in (1,2) ".$search_qry." order by chat.chat_id desc limit $limit Offset $offset";
  $result = $this->dataFetchAll($queue_chat_qry,array());
}
else
{		
  $admin_id = $result['admin_id'];
  $test = array(); 
  $department_qry = "SELECT dept_id FROM `departments` WHERE department_users LIKE '%$user_id%'";
  $department_user = $this->fetchData($department_qry, array());
  $department_user_count = $this->dataRowCount($department_qry, array());
  if($department_user_count>0){
    $dep = $department_user['dept_id'];
    $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.rating_value, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.department = '$dep' and chat.admin_id='$admin_id'  and chat.chat_type in (1,2)".$search_qry." order by chat.chat_id desc limit $limit Offset $offset";	
  
  }else{
    $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user,chat.chat_status,chat.rating_value, customer.customer_name, DATE_FORMAT(chat.created_dt, '%d-%m-%Y %H:%i') as chat_dt from chat left join customer on customer.customer_id = chat.chat_user where chat.admin_id='$admin_id' and (find_in_set('$user_id', chat.agents)) and chat.chat_type in (1,2)".$search_qry." order by chat.chat_id desc limit $limit Offset $offset";
  }

  
  $result = $this->dataFetchAll($queue_chat_qry,array());
}
$results['chatHEads'] = $result;
$results['userData'] = $user_id;
return $results;        
}

 function chatMessagePanelDetail($chat_id){        
    
    $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, chat.widget_name, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name,customer_email,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.chat_aviator,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id asc";

   $parms = array();
   $result = $this->dataFetchAll($chat_detail_qry,array());
    return $result;
    
    
}

public function ClaimedBy($data){
	extract($data);	 
	if($extension != "" && $extension != "null" && $extension != "undefined"){  //MOBILE APPS
	$qry = "select user_id from user where sip_login='$extension'";
      $msg_user_id = $this->fetchOne($qry, array());
	}	

		//MOBILE APP NOTIFICATION 

	$chat_user_type = "SELECT * FROM chat_msg WHERE chat_id='$chat_id' and msg_user_type='$msg_user_type'";
	$chat_counts = $this->dataRowCount($chat_user_type, array());
	//print_r($chat_counts);exit;
	//if($chat_counts == 0){
		
		$qrys = "UPDATE chat SET claim_status='$msg_user_id' WHERE chat_id='$chat_id'";
		$qry_results = $this->db_query($qrys, array());
		$usrname  = $this->fetchOne("SELECT agent_name FROM user WHERE user_id='$msg_user_id' ",array());

		$chat_msg_user_type = '7';
		$msg_type='text';
		$chat_msg_one='Chat claimed by '.$usrname;
	 $chat_msg_ides = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status,chat_images,extension) VALUES ('$chat_id', '$msg_user_id', '$chat_msg_user_type', '$msg_type', '$chat_msg_one', '1','','')", array());


		
		$chat_detail_qry = "select chat.chat_id, chat.chat_user,chat.claim_status,chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg,chat_msg.chat_images, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt,chat_msg.extension, user.user_name,user.agent_name,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_ides' order by chat_msg.chat_msg_id asc limit 1";
    
    
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());			
	
	//print_r($chat_detail_qry);exit;

				
		$get_omni_name ="SELECT agent_name FROM user WHERE user_id = $msg_user_id";
		 $omni_name = $this->fetchOne($get_omni_name, array());
		$dept_id_req = "SELECT department FROM chat WHERE chat_id='$chat_id'";
		 $dept_value = $this->fetchOne($dept_id_req, array());
		$send_dept_id =$dept_value;
		$dept_user_ids = "SELECT department_users FROM departments WHERE dept_id='$send_dept_id'";
		 $usrs_ids = $this->fetchOne($dept_user_ids, array());
		$get_ext_no = "SELECT sip_login FROM user WHERE user_id IN ($usrs_ids)";
		 $user_ext_no = $this->dataFetchAll($get_ext_no, array());
		foreach ($user_ext_no as $key => $value) {
			$chat_values[] = $value['sip_login'];
        } 
		
		$result_values = implode(",", $chat_values);
		
$element_values = array("action"=>"claimed_notification","extension_no"=>$result_values,"omni_agent_name"=>$omni_name,"cust_name"=>$customer_name);		

	$adm = array("operation"=>"agents","moduleType"=>"agents","api_type"=>"web","access_token"=>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc","element_data"=>$element_values);
		
	$notify = $this->erp_app($adm);


/*		$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://erp.cal4care.com/cms/apps/index.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "operation": "agents",
    "moduleType": "agents",
    "api_type": "web",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
    "element_data": {
        "action": "claimed_notification",
        "extension_no":"'.$result_values.'",
		"omni_agent_name":"'.$omni_name.'",
		"cust_name":"'.$customer_name.'"

    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl); */

	


  	$status = array('status' => 'true');
		$response_array = array('data' => $result);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;


	//echo $response;exit;

//	}

}



}
