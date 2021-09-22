<?php
class wpinstance extends restApi{
	public function getInstanceDetailsForAdmin($data){
		extract($data);
	
		if($user_type=="Employee"){
			$query = "SELECT GROUP_CONCAT(dept_id SEPARATOR ',') as departments FROM `departments` WHERE `department_users` LIKE '%$user_id%'";
			$result = $this->fetchOne($query, array());
			
			//print_r($result); exit;
			$qry = "SELECT *, (SELECT department_name FROM departments WHERE dept_id=whatsapp_instance_details.department_id) as dept FROM `whatsapp_instance_details` WHERE `department_id` IN ($result) and admin_id='$admin_id'";
			//echo $qry; exit;
			$result = $this->dataFetchAll($qry, array());
		} else {
			$qry = "select *, (SELECT department_name FROM departments WHERE dept_id=whatsapp_instance_details.department_id) as dept  from whatsapp_instance_details where admin_id='$user_id'";
			$result = $this->dataFetchAll($qry, array());
		}
		return $result;
	}
	public function updateNumWithDeptToInst($data){
		extract($data);
		$user_host_qry = "select instance_url from whatsapp_instance_details where wp_inst_id='$instance_id'";			
        $host = $this->fetchOne($user_host_qry, array());
		$url = $host."getMe";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		$result = json_decode($result);
		$agent_name = $result->response->pushname;
		$wid = $agent_name = $result->response->wid;
		$wid = str_replace("@c.us","",$wid);	
	
		if($wid == ''){
		   $result = 'scan_again';
		   return $result;
		}
		if($whatsapp_num == $wid){
			
			$qry = "UPDATE chat_wp_uf SET chat_status='1' WHERE app_chat_id='$whatsapp_num'";
            $qry_result = $this->db_query($qry, array());
			
			$qry = "UPDATE whatsapp_instance_details SET whatsapp_num='$whatsapp_num',department_id='$department_id',agent_name ='$agent_name' WHERE wp_inst_id='$instance_id'";
		    $qry_result = $this->db_query($qry, array());
		    $result = $qry_result == 1 ? 'Scanned' : 'Error';
			return $result;
		} else {
			$result = 'mismatch';
		   return $result;
		}	
		
	}
	public function readInstance($instance_id){
		$user_host_qry = "select instance_url from whatsapp_instance_details where wp_inst_id='$instance_id'";			
        $host = $this->fetchOne($user_host_qry, array());
		$url = $host."getConnectionState";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		if (strpos($result, '<!DOCTYPE html>') !== false) {
		$result = 'not yet scanned';
		} else {
			$result = json_decode($result);
			$result = $result->response;
		}
		return $result;
	}
	public function revokeInstance($instance_id){
		$whatsapp_num = "select whatsapp_num from whatsapp_instance_details where wp_inst_id='$instance_id'";	
		//print_r($whatsapp_num);
        $whatsapp_num = $this->fetchOne($whatsapp_num, array());
		
		//echo $whatsapp_num; exit;
		/*$url = "http://206.189.86.180/restart";
		$postfields = '{"sessionId":"'.$inst_name.'"}';
		//echo $postfields; exit;
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($curl, CURLOPT_TIMEOUT, 40);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$message=curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl); 
		$data = $message; */

			$qry = "UPDATE whatsapp_instance_details SET whatsapp_num=NULL,department_id=NULL,agent_name=NULL WHERE wp_inst_id='$instance_id'";
            $qry_result = $this->db_query($qry, array());
		
		    $qry = "UPDATE chat_wp_uf SET chat_status='0' WHERE app_chat_id='$whatsapp_num'";
            $qry_result = $this->db_query($qry, array());
		
            if($qry_result == '1'){
                $data = 'true'; 
            } else {
                $data = 'false';
            }
		return $data;
	}
	public function reloadInstance($instance_id){
		$user_host_qry = "select inst_main_name from whatsapp_instance_details where wp_inst_id='$instance_id'";			
        $inst_name = $this->fetchOne($user_host_qry, array());
		$url = "http://206.189.86.180/restart";
		$postfields = '{"sessionId":"'.$inst_name.'"}';
		//echo $postfields; exit;
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($curl, CURLOPT_TIMEOUT, 40);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$message=curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl); 
		$data = $message;
		return $data;
	}
	
	public function refreshInstance($instance_id){
		$user_host_qry = "select instance_url from whatsapp_instance_details where wp_inst_id='$instance_id'";			
        $host = $this->fetchOne($user_host_qry, array());
		$url = $host."refresh";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch); 
		return $result;
	}
	
	public function setWebHook($data){
		extract($data);
		$qry = "UPDATE whatsapp_instance_details SET web_hook='$web_hook' WHERE whatsapp_num='$wp_num'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? '{"status":"true","web_hook":"'.$web_hook.'"}' : '{"status":"false"}' ;
		echo  $result;
	}
	public function chatTransfer($data){
		extract($data);
		$qry = "UPDATE chat_wp_uf SET f_user_id='$user_id' WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		$user_nme_qry = "select agent_name from user where user_id='$user_id'";			
        $user = $this->fetchOne($user_nme_qry, array());
		$userid=[];
		$userid[]=$user_id;
		$admin_id_qry = "select admin_id from user where user_id='$user_id'";			
        $admin_id = $this->fetchOne($admin_id_qry, array());
		if($admin_id=='1'  ||$admin_id==1 )
			$admin_id=$user_id;
		$text="WhatsApp Chat Assigned for $user";
		//echo $text;exit;
		 $this->wpn_curl($userid,$chat_id,$text,$inst_id);
		 $created_at = date("Y-m-d H:i:s"); 
		
	    //$this->db_query("INSERT INTO mc_event (mc_event_key,user_id,admin_id,mc_event_data,mc_event_type,event_status,created_dt) VALUES('$chat_id',$user_id,'128','$text','5','7','$created_at')", array());
		 $this->db_query("INSERT INTO mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$admin_id','$chat_id','$text','5','7','$created_at','$inst_id')", array());
		return $result;
	}
	public function revokeTransfer($data){
		extract($data);
		$qry = "UPDATE chat_wp_uf SET f_user_id=NULL WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
	}
	public function getdeptUsers($instance_id){
		extract($data);
		$user_dep_qry = "select department_id from whatsapp_instance_details where wp_inst_id='$instance_id'";			
        $dept = $this->fetchOne($user_dep_qry, array());
		$user_dep_qry = "SELECT `department_users` FROM `departments` WHERE dept_id='$dept'";			
        $dept = $this->fetchOne($user_dep_qry, array());
		$qry = "SELECT * FROM `user` WHERE `user_id` IN ($dept) ORDER BY `user_id` DESC";
        $result = $this->dataFetchAll($qry, array());
		return $result;
	}
	
	
	

		//function sendMessage($deviceToken,$sender){
		//echo $deviceToken;exit;
		
      
   // }
		

	
//unofficial whatsapp


	


function ComposeIncommingChatMessageUnoff($chat_data){
	
        extract($chat_data);	
	//echo 'dsfsd'; exit;
	
//print_r($chat_data); exit;
	
	
	
	
      // print_r($chat_data); exit;
    $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num` LIKE '$to'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$instance_id = $result['wp_inst_id'];
	$dept_id = $result['department_id'];
	$web_hook = $result['web_hook'];
	
	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL, $web_hook);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($chat_data));
	curl_setopt($curl, CURLOPT_TIMEOUT, 40);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$result=curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl); 	
	
	
	
	$user_dep_qry = "SELECT `department_users` FROM `departments` WHERE dept_id='$dept_id'";			
	$dept = $this->fetchOne($user_dep_qry, array());
	$qry = "SELECT android_token FROM `user` WHERE `user_id` IN ($dept) ORDER BY `user_id` DESC";
	$user_arr = $this->dataFetchAll($qry, array());
	$cont = $sender_name.':'.$chat_msg;
	foreach($user_arr as $at){
		$device_t =  $at['android_token'];
	    $response = $this->sendAppNotification($device_t,'New Whatsapp Message from OmniChannel', $cont,'chat','');
	}
	$qry = "SELECT android_token FROM `user` WHERE `user_id` ='$admin_id'";
	$admin_token = $this->fetchOne($qry, array());
    $response = $this->sendAppNotification($admin_token,'New Whatsapp Message from OmniChannel', $cont,'chat','');


	//echo $admin_id; echo $instance_id; echo $dept_id; exit;

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
	
	
	$img_Src = $sender;
		$ext = pathinfo(parse_url($img_Src, PHP_URL_PATH), PATHINFO_EXTENSION); 
		$to_num = str_replace("@c.us","",$from);
		

	$image_name = $to_num.'.'.$ext; 
	$destination_path = getcwd().DIRECTORY_SEPARATOR;            
	$whatsapp_media_target_path = $destination_path."whatsapp_image/".$image_name;
	//file_put_contents($whatsapp_media_target_path, $img_Src);
	file_put_contents($whatsapp_media_target_path, file_get_contents($img_Src)); 
	$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/".$image_name; 
		
		copy($img_Src, $whatsapp_media_target_path);
	
		
$qry = "select * from wp_cust_img where number ='$to_num'";  
$results = $this->fetchData($qry, array());  
if($results > 0){
	//$qry = "UPDATE wp_cust_img SET number='$to_num',image_path='$whatsapp_media_target_path',name='$sender_name' WHERE number='$to_num'";
//$qry_result = $this->db_query($qry, $params); 
} else {
     $chat_msg_id = $this->db_insert("INSERT INTO wp_cust_img(number,image_path,name) VALUES ('$to_num','$whatsapp_media_target_path', '$sender_name')", array());
}
	
	
     $qry = "select * from chat_wp_uf where app_customer_key ='$from' and app_chat_id ='$to' and admin_id= '$admin_id'";  

       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
		
		  $chat_id2=$chat_id;

		
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,customer_image,customer_name) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','$instance_num','$sender','$sender_name')", array());
	
            //$chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$sender_name;
		
             $chat_id_mc =   $this->db_query("INSERT INTO mc_event (mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES ('$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		//echo  $chat_id; exit;
		
		//$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		//$admintoken = $this->fetchOne($qry, array());
		    //$this->wpn_curl($admin_id,$chat_id,$chat_msg,$instance_id);
			//$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
		     
		     $wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
            
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);	
		    //$text="New WhatsApp Message";
		    $wp_users[] = $admin_id;
		    $this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
		
		/*	$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['user_id'];
		      //$token = $agentresult[$i]['notification_code'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
			}*/
            return $chat_data;      
    } else {
	      
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`instance_id`,`department_id`,`customer_image`) VALUES ('$to','$from', '$sender_name','sms','$instance_num','0','1','1','$admin_id','$created_at','$instance_id','$dept_id','$sender')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,customer_image,customer_name) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$instance_num','$created_at','$sender','$sender_name')", array());
         // $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$sender_name;     
               $chat_id_mc =     $this->db_query("INSERT INTO mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$chat_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		
		$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);	
	     	$wp_users[] = $admin_id;
		    $this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
		
		/*$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		$admintoken = $this->fetchOne($qry, array());		
		    $this->wpn_curl($admin_id,$chat_id,$mc_event_data,$instance_id);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['user_id'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->wpn_curl($token,$chat_id,$mc_event_data,$instance_id);
			}*/
            return $chat_data;  
    }  
    }
  

    function chatDetailListUOFF($chat_id){
       
         $chat_detail_qry = "select * from (select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.whatsapp_media_url, chat_data_wp_uf.delivered_status as msg_status, chat_data_wp_uf.msg_from as msg_user_type,chat_data_wp_uf.chat_id,chat_data_wp_uf.chat_message as chat_msg,date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, chat_wp_uf.app_customer_key as customer_name,chat_data_wp_uf.chat_pnr as chat_pnr, users.user_name,chat_data_wp_uf.agent_id as msg_user_id, chat_wp_uf.api_type as msg_type from chat_data_wp_uf inner join chat_wp_uf on chat_wp_uf.chat_id = chat_data_wp_uf.chat_id left join user as users on users.user_id = chat_data_wp_uf.agent_id where chat_wp_uf.chat_id LIKE '".$chat_id."' order by chat_data_wp_uf.chat_msg_id desc) result_data order by chat_msg_id asc LIMIT 50";


        // print_r($chat_detail_qry); exit;
            // $this->errorLog("demo",$chat_detail_qry);
         $parms = array();
         $result = $this->dataFetchAll($chat_detail_qry,array());
 
         return $result;
     
     }






          function getcustomersChatUOFF($user_id,$queue_id,$search_text,$user_type,$instance_id,$limit,$offset){
       
    // echo $user_type; echo $instance_id; exit;
		 
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
     
 $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `wp_inst_id` = '$instance_id'";
    $result = $this->fetchData($qry, array());   
	$wathsapp_num = $result['whatsapp_num'];

     if($user_type == 'Employee'){
     $queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id, chat_data_wp_uf.chat_message, date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, (SELECT name FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_name, (SELECT image_path FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as prof_image,chat_wp_uf.group_msg as group_msg,chat_wp_uf.group_name as group_name,chat_wp_uf.group_icon as group_icon, chat_wp_uf.app_chat_id, chat_wp_uf.api_type, chat_wp_uf.user_id, chat_wp_uf.f_user_id, (SELECT user_name FROM user WHERE user_id= chat_wp_uf.user_id) as user_nm, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.f_user_id) as f_user_nm from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_data_wp_uf.chat_msg_id in (select max(chat_msg_id) from chat_data_wp_uf group by chat_id order by chat_msg_id desc) and chat_wp_uf.admin_id = '$admin_id' and chat_wp_uf.chat_status = '1'  and  (chat_wp_uf.user_id = '$user_id' or chat_wp_uf.f_user_id = '$user_id' or chat_wp_uf.user_id IS NULL) order by chat_data_wp_uf.chat_msg_id desc";
	 } else {
	 $queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id,  chat_data_wp_uf.chat_message,date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time,chat_wp_uf.group_msg as group_msg,chat_wp_uf.group_name as group_name,chat_wp_uf.group_icon as group_icon, chat_wp_uf.app_chat_id, chat_wp_uf.api_type, chat_wp_uf.user_id, chat_wp_uf.f_user_id, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.user_id) as user_nm, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.f_user_id) as f_user_nm,(SELECT distinct(name) FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_name,(SELECT distinct(image_path) FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as prof_image from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_data_wp_uf.chat_msg_id in (select max(chat_msg_id) from chat_data_wp_uf group by chat_id order by chat_msg_id desc) and chat_wp_uf.admin_id != '' and chat_wp_uf.admin_id = '$admin_id' and chat_wp_uf.instance_id = '$instance_id' and chat_wp_uf.chat_status = '1' and chat_wp_uf.app_chat_id = '$wathsapp_num' order by chat_data_wp_uf.chat_msg_id desc";	
		 
		 		
	 }
$queue_chat_qry.=" LIMIT $limit offset $offset";
         
         $parms = array();
         $result = $this->dataFetchAll($queue_chat_qry,array());
    
         return $result;
         
    
     }


     function insertChatMessageOff($chat_data){      
		//print_r($chat_data); exit;
        extract($chat_data);
		// echo $agent_id; exit;
        $qry = "select * from chat_wp_uf where chat_id='$chat_id'";
        $result = $this->fetchData($qry, array());	
        $mobile_num = $result['app_customer_key'];
        $admin_num = $result['app_chat_id'];
        $instance_num = $result['chat_instance'];
		 $u_id = $result['user_id'];
		  $fu_id = $result['f_user_id'];
		 if($u_id == '' && $fu_id == ''){
		 $qry = "UPDATE chat_wp_uf SET user_id='$agent_id' WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		
		 } else {
		
		 }
		

     $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `wp_inst_id`='$instance_id'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];	
	$admin_num = $result['whatsapp_num'];
	$instance_url =  $result['instance_url'];
	$url = $instance_url;  	
		// echo $url; exit;
		
	$chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
		 $chat_msg = str_replace("%2%",'\n',$chat_msg); 
		 
		 
		// echo $chat_msg; exit;	 
		 
        if($whatsapp_media_url==''){
      
			
					if($is_group == '1'){
					$phone_us = $mobile_num."@g.us";
					} else {
					$phone_us = $mobile_num."@c.us";
					}
                
                    
                    $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
		 $chat_msg = str_replace("%2%",'\n',$chat_msg); 
			    $postfields = '{ "args": { "to": "'.$phone_us.'", "content": "'.$chat_msg.'" } }';
			//echo $postfields; exit;
				$url = $url."/sendText";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
				$headers = array();
				$headers[] = 'Accept: application/json';
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				$result = json_decode($result);
				//$wp_msg_id = $result->response; 
			//print_r($result); exit;
			 if($result->response == ''){
			  echo '{"status":"false","result": {"data":"not-sent"}}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			}
			
                 
         } else {  
    

        $file = file_get_contents($whatsapp_media_url); // to get file
        $name = basename($whatsapp_media_url); // to get file name


if($is_group == '1'){
					$phone_us = $mobile_num."@g.us";
					} else{
					$phone_us = $mobile_num."@c.us";
                    }
                    

                    $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
                    $chat_msg = str_replace("%2%",'\n',$chat_msg); 

			
			$postfields = '{ "args": { "to": "'.$phone_us.'", "url": "'.$whatsapp_media_url.'", "filename": "'.$name.'","caption": "'.$chat_msg.'" } }';
			//print_r($postfields); exit;
		   $url = $url."sendFileFromUrl";
            $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $url);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		   $headers = array();
		   $headers[] = 'Accept: application/json';
		   $headers[] = 'Content-Type: application/json';
		   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		   $result = curl_exec($ch);
		   if (curl_errno($ch)) {
			   echo 'Error:' . curl_error($ch);
		   }
		   curl_close($ch);
		
			$result = json_decode($result);
			$wp_msg_id = $result->response; 
		  // echo $wp_msg_id; exit;
			
			 if($result->response == ''){
			  echo '{"status":"false","result": {"data":"not-sent"}}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			}
		
        }
        $qry = "SELECT * FROM `user` WHERE `user_id` ='$agent_id'";
        $result = $this->fetchData($qry, array());
        $timezone_id = $result['timezone_id'];
        //$admin_id = $result['admin_id'];
		//echo $admin_id; exit;
		 
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
       // echo "SELECT name FROM timezone WHERE id='$timezone_id'";exit;
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
        $created_at = date("Y-m-d H:i:s"); 	
		// echo $whatsapp_media_url; exit;
		 $chat_msg = '"'.$chat_msg.'"';
		// $RD = "INSERT INTO chat_data_wp_uf(chat_id, agent_id, msg_from, msg_type, chat_message,	delivered_status, chat_status, created_dt,whatsapp_media_url,chat_instance,wp_msg_id) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', $chat_msg, 'SENT', '1', '$created_at', '$whatsapp_media_url','$instance_num','$wp_msg_id')";
		 
		// echo $RD; exit;
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id, agent_id, msg_from, msg_type, chat_message,	delivered_status, chat_status, created_dt,whatsapp_media_url,chat_instance,wp_msg_id) VALUES ('$chat_id', '$agent_id', '$msg_from', '$msg_type', $chat_msg, 'SENT', '1', '$created_at', '$whatsapp_media_url','$instance_num','$wp_msg_id')", array());        
       $chat_data = $chat_msg_id;
		// echo $chat_data; exit;
		 if($admin_id == $agent_id){
		 $mc_event_data = "whatsapp Message By Admin";
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$agent_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		 } else {
		 $mc_event_data = "whatsapp Message By Agent";
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$agent_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		 }
        
         return $chat_data;         
     }

     function insertChatMessageOff2($chat_data){   
		
        extract($chat_data);
       // echo 'dss'; exit;
//print_r($chat_data); exit;
 		$qry = "select * from user where user_name='$username' and company_name='$company'";
        $results = $this->fetchData($qry, array());
		$user_id = $results['user_id'];
		 $user_name = $results['user_name'];
		$admin_id = $results['admin_id'];
		 if($results['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $results['user_id']; }
		 
		$mobile_num = $msg_to;
		$adm_num= $results['whatsapp_num'];
		// echo $admin_id; exit;
		 
	$qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num`='$msg_from'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];
		 
		 $inst_id = $result['instance_id'];	
	$instance_url =  $result['instance_url'];
	$url = $instance_url;  	


                 if($is_group == '1'){
					$phone_us = $mobile_num."@g.us";
					} else{
					$phone_us = $mobile_num."@c.us";
					}
                
                    
                    $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
		 $chat_msg = str_replace("%2%",'\n',$chat_msg); 
			    $postfields = '{ "args": { "to": "'.$phone_us.'", "content": "'.$chat_msg.'" } }';
			//echo $postfields; exit;
				$url = $url."/sendText";
									//$url= 'https://whats.mconnectapps.com/api/c4c-inst2/sendText';
		 							//echo $url;exit;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
				$headers = array();
				$headers[] = 'Accept: application/json';
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				$result = json_decode($result);
		// echo $result;exit;
			 if($result->response == ''){
			  echo '{"status":"false","result": {"data":"not-sent"}}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			} 
		 
		// $wp_msg_id = 'asd'; 
		 
		 
		

    
        $qry = "select * from chat_wp_uf where app_customer_key ='$msg_to' and admin_id= '$admin_id'";  
 
       $results = $this->fetchData($qry, array());  
	

	

    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,wp_msg_id) VALUES ('$chat_id','text', 'customer','$admin_id', '$chat_msg','1', '1','$created_at','$inst_id','$wp_msg_id')", array());
            $mc_event_data = "whatsapp Message From ".$from_name;
                $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$admin_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$inst_id')", array());
		
              echo '{"status":"true","result": {"data":"'.$wp_msg_id.'"}}'; exit;
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`instance_id`,`department_id`,`user_id`) VALUES ('$msg_from','$msg_to', '$sender_name','sms','$instance_num','0','1','1','$admin_id','$created_at','$inst_id','$dept_id','$user_id')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,customer_image,customer_name,wp_msg_id) VALUES ('$chat_id','text', 'agent','$user_id', '$chat_msg','1', '1','$inst_id','$created_at','$sender','$user_name','$wp_msg_id')", array());
            $mc_event_data = "whatsapp Message From ".$user_name;     
                $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$mc_event_data','5','7','$created_at','$inst_id')", array());
		   
           echo '{"status":"true","result": {"data":"'.$wp_msg_id.'"}}'; exit; 
           
     }
    
   }
	
				
	
	 function insertChatMessageOffFiles($chat_data){   
		
        extract($chat_data);
 	//print_r($chat_data); exit;
 		$qry = "select * from user where user_name='$username' and company_name='$company'";
        $results = $this->fetchData($qry, array());
		$user_id = $results['user_id'];
		 $user_name = $results['user_name'];
		$admin_id = $results['admin_id'];
		 if($results['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $results['user_id']; }
		 
		$mobile_num = $msg_to;
		$adm_num= $results['whatsapp_num'];
		// echo $admin_id; exit;
		 
	$qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num`='$msg_from'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];
		 
		 $inst_id = $result['instance_id'];	
	$instance_url =  $result['instance_url'];
	$url = $instance_url;  
		// echo $url; exit;
		$whatsapp_media_url = $chat_file;
        $file = file_get_contents($whatsapp_media_url); // to get file
        $name = basename($whatsapp_media_url); // to get file name

			if($is_group == '1'){
				$phone_us = $mobile_num."@g.us";
			} else{
				$phone_us = $mobile_num."@c.us";
            }
            

            $caption = str_replace("\n",'%2%',$caption); 
		 
            $caption = str_replace("%2%",'\n',$caption); 

			$postfields = '{ "args": { "to": "'.$phone_us.'", "url": "'.$whatsapp_media_url.'", "filename": "'.$name.'","caption": "'.$caption.'" } }';
		   $url = $url."sendFileFromUrl";
            $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $url);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		   $headers = array();
		   $headers[] = 'Accept: application/json';
		   $headers[] = 'Content-Type: application/json';
		   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		   $result = curl_exec($ch);
		   if (curl_errno($ch)) {
			   echo 'Error:' . curl_error($ch);
		   }
		   curl_close($ch);
		
			$result = json_decode($result);
		// print_r($result); exit;
			$wp_msg_id = $result->response; 
		  // echo $wp_msg_id; exit;
			
			 if($result->response == ''){
			  echo '{"status":"false","result": {"data":"not-sent"}}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->success; 
			} else {
				return false;
			}

    
        $qry = "select * from chat_wp_uf where app_customer_key ='$msg_to' and admin_id= '$admin_id'";  
 
       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,whatsapp_media_url) VALUES ('$chat_id','text', 'agent','$user_id', '$caption','1', '1','$created_at','$inst_id','$whatsapp_media_url')", array());
            $mc_event_data = "whatsapp Message From ".$user_name;
                $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$inst_id')", array());
		
		   if($wp_msg_id == 1){
		   echo '{"status":"true"}'; exit; 
		   } else {
		   echo '{"status":"false"}'; exit; 
		   }
               
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`department_id`) VALUES ('$adm_num','$msg_to', '$msg_to','sms','$inst_id','0','1','1','$admin_id','$created_at','$dept_id')", array());   
		
				
				
				
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,whatsapp_media_url) VALUES ('$chat_id','text', 'agent','$user_id', '$caption','1', '1','$created_at','$inst_id','$whatsapp_media_url')", array());
		
				
				
            $mc_event_data = "whatsapp Message From ".$user_name;     
                $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$mc_event_data','5','7','$created_at')", array());
		  
          if($wp_msg_id == 1){
		   echo '{"status":"true"}'; exit; 
		   } else {
		   echo '{"status":"false"}'; exit; 
		   }
           
     }
    
   }
	

				
	public function single_whatsapp_media_upload_uoff($chat_data){
     //print_r($chat_data);exit;



      $user_id = $chat_data['user_id'];
	  $instance_id = $chat_data['instance_id'];
      $timezone_id = $chat_data['timezone_id'];      
      $country_code = $chat_data['country_code'];
      $phone_num = $chat_data['phone_num'];
      $chat_msg = $chat_data['chat_msg'];    
      $whatsapp_media_upload_path = '';
    
        $qry = "SELECT * FROM `admin_details` WHERE `admin_id` LIKE '$admin_id'";
        $result = $this->fetchData($qry, array());   
        $whatsapp_account = $result['whatsapp_account'];
		
	
    $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `wp_inst_id`='$instance_id'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];	
	$admin_num = $result['whatsapp_num'];
	$instance_url =  $result['instance_url'];
	$url = $instance_url;  
	
		if($admin_num == ''){
		
			$result = array( "status" => "false","data"=>"Sorry! Number not implemented with this instance"); 
                $tarray = json_encode($result);  
              print_r($tarray);exit;
		}
     
      if(isset($_FILES["whatsapp_media"]))
        {
          $whatsapp_media_info = getimagesize($_FILES["whatsapp_media"]["tmp_name"]);                    
          $whatsapp_media_extension = pathinfo($_FILES["whatsapp_media"]["name"], PATHINFO_EXTENSION);                 
        $destination_path = getcwd().DIRECTORY_SEPARATOR;   
		  $wp_fname = basename( $_FILES["whatsapp_media"]["name"]);
        $whatsapp_media_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);  
        $whatsapp_media_target_path = $destination_path."whatsapp_image/". basename( $_FILES["whatsapp_media"]["name"]);
        move_uploaded_file($_FILES['whatsapp_media']['tmp_name'], $whatsapp_media_target_path);       
        $qry = "INSERT INTO whatsapp_image(chat_id,admin_id,whatsapp_image) VALUES ('0','$user_id', '$whatsapp_media_upload_path')";
        $results = $this->db_query($qry, array());       
       }
       $phone = $country_code.$phone_num;   
		
		
		
		if($is_group == '1'){
					$phone_id = $phone."@g.us";
					} else{
					$phone_id = $phone."@c.us";
					}
		
		
       if($whatsapp_media_upload_path != '' && $chat_msg != 'null'){
        $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
        $chat_msg = str_replace("%2%",'\n',$chat_msg); 

		$url = $url."sendFileFromUrl";
		  // $phone_id = "919600953854@c.us";

		 $postfields = '{"args":{ "to":"'.$phone_id.'","url":"'.$whatsapp_media_upload_path.'","filename": "'.$wp_fname.'","caption":"'.$chat_msg.'"}}';
		   
//print_r($postfields); exit;
            $ch = curl_init();
		   curl_setopt($ch, CURLOPT_URL, $url);
		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		   curl_setopt($ch, CURLOPT_POST, 1);
		   curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		   $headers = array();
		   $headers[] = 'Accept: application/json';
		   $headers[] = 'Content-Type: application/json';
		   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		   $result = curl_exec($ch);
		   if (curl_errno($ch)) {
			   echo 'Error:' . curl_error($ch);
		   }
		   curl_close($ch);
		  // print_r($result); exit;
			
		  // echo $wp_msg_id; exit;
		   $result = json_decode($result);
		   if($result->response == ''){
			echo '{"status":"false","data":"Not in contact"}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			}

		   
       }elseif($whatsapp_media_upload_path == '' && $chat_msg !=''){	



        $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
		 $chat_msg = str_replace("%2%",'\n',$chat_msg); 
				$postfields = '{ "args": { "to": "'.$phone_id.'", "content": "'.$chat_msg.'" } }';
		 //  echo $postfields; exit;
				$url = $url."/sendText";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
				$headers = array();
				$headers[] = 'Accept: application/json';
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				$result = json_decode($result);
		   
				//$wp_msg_id = $result->response;
		   
		  // print_r($result); exit;
		   if($result->response == ''){
			echo '{"status":"false","data":"Not in contact"}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			}
		   
		
       }else{


        $chat_msg = str_replace("\n",'%2%',$chat_msg); 
		 
		 $chat_msg = str_replace("%2%",'\n',$chat_msg); 
				$postfields = '{ "args": { "to": "'.$phone_id.'", "content": "'.$chat_msg.'" } }';
				$url = $url."/sendText";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
				$headers = array();
				$headers[] = 'Accept: application/json';
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				$result = json_decode($result);
				//$wp_msg_id = $result->response;
		   
		  if($result->response == ''){
			echo '{"status":"false","data":"Not in contact"}'; exit;
			} elseif($result->success == '1'){
			  $wp_msg_id = $result->response; 
			} else {
				return false;
			}


       } 
    
        // echo $wp_msg_id; exit;
       $qry = "select * from user where user_id='$user_id'";
       $result = $this->fetchData($qry, array());

       if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
       $user_timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
       $user_timezone = $this->fetchmydata($user_timezone_qry,array());
       date_default_timezone_set($user_timezone);
       $created_at = date("Y-m-d H:i:s");
		
		
		// echo $admin_num; exit;
		
		
       $qry = "select * from chat_wp_uf where app_customer_key ='$phone' and app_chat_id='$admin_num' and admin_id='$admin_id'";  
       $results = $this->fetchData($qry, array());  
		
     $chat_msg = '"'.$chat_msg.'"';
       if($results > 0){     
            $chat_id=$results['chat_id'];    
		   
		  
        
            $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$user_id', 'agent', 'text', $chat_msg,'SENT','1','$wp_msg_id','$created_at','$whatsapp_media_upload_path')", array());                
              $mc_event_data = "whatsapp Message From".$from;
                  $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
              $result = array( "status" => "true", "chat_id" => $chat_id ); 
                $tarray = json_encode($result);  
              print_r($tarray);exit;     
      } else { 
	
       $chat_id = $this->db_insert("INSERT INTO `chat_wp_uf` (`app_chat_id`, `app_customer_key`, `customer_name`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`user_id`, `created_at`,`instance_id`,`department_id`,`wp_msg_id`) VALUES ('$admin_num', '$phone', '$phone', 'sms', '0', '1', '1', '$admin_id','$user_id','$created_at','$instance_id','$dept_id','$wp_msg_id')", array());
             
        $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id, agent_id, msg_from, msg_type, chat_message,  delivered_status, chat_status,wp_msg_id, created_dt,whatsapp_media_url) VALUES ('$chat_id', '$user_id', 'agent', 'text', $chat_msg,'SENT', '1','$wp_msg_id','$created_at','$whatsapp_media_upload_path')", array());        
           
      
        $mc_event_data = "whatsapp Message By Admin";
        $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
            $result = array( "status" => "true"); 
                $tarray = json_encode($result);  
              print_r($tarray);exit;
        }     
    }
 			
public function whatsapp_media_upload($chat_data){
	
 
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
	
function updateWpChatStatus($chat_data){
      extract($chat_data);
	if($chat_status == '1'){  
	   $chat_status_m = 'SENT';
	} elseif($chat_status == '2') {
	   $chat_status_m = 'DELIVERED';
	}elseif($chat_status == '3') {
	   $chat_status_m = 'READ';
	}
    $qry = "UPDATE chat_data_wp_uf SET delivered_status = '$chat_status_m' WHERE wp_msg_id='$MessageId'";
            $qry_result = $this->db_query($qry, $params);   
      
            return $qry_result;
  }
	
	function generate_outgoing_wp_unoff($chat_data){
        extract($chat_data);
		$img_Src = $receiver_prof_pic;
		$ext = pathinfo(parse_url($img_Src, PHP_URL_PATH), PATHINFO_EXTENSION); 
		$to_num = str_replace("@c.us","",$to);
		

	$image_name = $to_num.'.'.$ext; 
	$destination_path = getcwd().DIRECTORY_SEPARATOR;            
	$whatsapp_media_target_path = $destination_path."whatsapp_image/".$image_name;
	//file_put_contents($whatsapp_media_target_path, $img_Src);
	file_put_contents($whatsapp_media_target_path, file_get_contents($img_Src)); 
	$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/".$image_name; 
		
		copy($img_Src, $whatsapp_media_target_path);
	
		
$qry = "select * from wp_cust_img where number ='$to_num'";  
$results = $this->fetchData($qry, array());  
if($results > 0){
	//$qry = "UPDATE wp_cust_img SET number='$to_num',image_path='$whatsapp_media_target_path',name='$receiver_name' WHERE number='$to_num'";
	//$qry_result = $this->db_query($qry, $params); 
} else {
     $chat_msg_id = $this->db_insert("INSERT INTO wp_cust_img(number,image_path,name) VALUES ('$to_num','$whatsapp_media_target_path', '$receiver_name')", array());
}
		
	 	
		
    //$qry = "UPDATE chat_wp_uf SET customer_image='$receiver_prof_pic',customer_name='$receiver_name' WHERE wp_msg_id='$MessageId'";
	//$qry_result = $this->db_query($qry, $params);  
		
	//$chat_name="SELECT chat_id FROM `chat_data_wp_uf` WHERE wp_msg_id='$MessageId'";
	//$chat_id = $this->fetchOne($chat_name, array());
	//$qry = "UPDATE chat_wp_uf SET customer_image='$receiver_prof_pic',customer_name='$receiver_name' WHERE chat_id='$chat_id'";
	//$qry_result = $this->db_query($qry, $params);  
	//$qry = "UPDATE chat_data_wp_uf SET customer_image = '$receiver_prof_pic',customer_name='$receiver_name' WHERE chat_id='$chat_id'";	
    //$qry_result = $this->db_query($qry, $params);  
            return $qry_result;
  }
  
  
function generate_incoming_group_wp_unoff($chat_data){
	
  extract($chat_data);	  
//
	
	
$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,"https://omni.mconnectapps.com/api/v1.0/whatsapp-omni/");
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($chat_data));
curl_setopt($curl, CURLOPT_TIMEOUT, 40);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result=curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl); 	
//	print_r($chat_data); exit;

$qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num` LIKE '$to'";
$result = $this->fetchData($qry, array());   
$admin_id = $result['admin_id'];
$instance_id = $result['wp_inst_id'];
$dept_id = $result['department_id'];
	$web_hook = $result['web_hook'];
	



$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,$web_hook);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($chat_data));
curl_setopt($curl, CURLOPT_TIMEOUT, 40);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result=curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

	$user_dep_qry = "SELECT `department_users` FROM `departments` WHERE dept_id='$dept_id'";			
	$dept = $this->fetchOne($user_dep_qry, array());
	$qry = "SELECT android_token FROM `user` WHERE `user_id` IN ($dept) ORDER BY `user_id` DESC";
	$user_arr = $this->dataFetchAll($qry, array());
	$cont = $sender_name.':'.$chat_msg;
	foreach($user_arr as $at){
		$device_t =  $at['android_token'];
	    $response = $this->sendAppNotification($device_t,'New Whatsapp Group Message from OmniChannel', $cont,'chat','');
	}
	$qry = "SELECT android_token FROM `user` WHERE `user_id` ='$admin_id'";
	$admin_token = $this->fetchOne($qry, array());
    $response = $this->sendAppNotification($admin_token,'New Whatsapp Group Message from OmniChannel', $cont,'chat','');
//echo $admin_id; echo $instance_id; echo $dept_id; exit;

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
$qry = "select * from chat_wp_uf where app_customer_key ='$from' and app_chat_id ='$to' and admin_id= '$admin_id'";  

 $results = $this->fetchData($qry, array());  




if($results > 0){    

    $chat_id=$results['chat_id'];     
    $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,customer_image,customer_name) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','$instance_num','$sender','$sender_name')", array());
     // $chat_data = $this->getChatDetails($chat_msg_id);     
      $mc_event_data = "whatsapp Message From ".$from_name;
        $c =  $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
  //$this->notification_curl($admintoken,$chat_id,$chat_msg);
	
//$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
	//	$admintoken = $this->fetchOne($qry, array());
		 //   $this->wpn_curl($admintoken,$chat_id,$chat_msg,$instance_id);
	
$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;
	        $this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
	
/*$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
$agentresult = $this->dataFetchAll($agentqry, array());
$agentcount = $this->dataRowCount($agentqry, array()); 
for($i=0;$i<$agentcount;$i++){		  
  $token = $agentresult[$i]['user_id'];
  $profile_image = $agentresult[$i]['profile_image'];	
  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
}*/
      return $chat_data;      
} else {

      $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`instance_id`,`department_id`,`customer_image`,`group_name`,`group_icon`,`group_msg`) VALUES ('$to','$from', '$sender_name','sms','$instance_num','0','1','1','$admin_id','$created_at','$instance_id','$dept_id','$sender','$group_name','$group_icon','1')", array());   
      $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,customer_image,customer_name) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$instance_num','$created_at','$sender','$sender_name')", array());
   // $chat_data = $this->getChatDetails($chat_msg_id);     
      $mc_event_data = "whatsapp Message From ".$from_name;     
         $c =  $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
	$wp_users[] = $admin_id;
	$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;   
	        $this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
	
/*  $this->wpn_curl($admintoken,$chat_id,$chat_msg,$instance_id);	
$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
$agentresult = $this->dataFetchAll($agentqry, array());
$agentcount = $this->dataRowCount($agentqry, array()); 
for($i=0;$i<$agentcount;$i++){		  
  $token = $agentresult[$i]['notification_code'];
  $profile_image = $agentresult[$i]['profile_image'];	
  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
}*/
      return $chat_data;  
}  
}

function generate_incoming_group_image_wp_unoff($chat_data){
      extract($chat_data);
	//print_r($chat_data); exit;
	 $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num`='$to'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];	
	$admin_num = $result['whatsapp_num'];
	$instance_id = $result['wp_inst_id'];
	$instance_url =  $result['instance_url'];
	$web_hook = $result['web_hook'];
	$url = $instance_url;  
	$postfields = '{ "args": { "message": "'.$message_id.'"} }';
	$url = $url."/decryptMedia";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$headers = array();
	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	$result = json_decode($result);
	if($result->success == 'true'){
	$img_Src =  $result->response;
	} else {
		return false;
	}
	$f = explode(';', $img_Src);	
	$img_type= str_replace("data:image/","",$f[0]);	
	$image_name = $time.'.'.$img_type;
	list($type, $img_Src) = explode(';', $img_Src);
	list(, $img_Src)      = explode(',', $img_Src);
	$img_Src = base64_decode($img_Src);
	$destination_path = getcwd().DIRECTORY_SEPARATOR;            
	$whatsapp_media_target_path = $destination_path."whatsapp_image/".$image_name;
	file_put_contents($whatsapp_media_target_path, $img_Src);
	$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/".$image_name;  
	
$chat_data_im = array("from"=>$from,"to"=>$to,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"sender"=>$sender,"image"=>$whatsapp_media_target_path,"group_icon"=>$group_icon,"group_name"=>$group_name);

	$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,$web_hook);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($chat_data_im));
curl_setopt($curl, CURLOPT_TIMEOUT, 40);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result=curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl); 
	

//print_r($chat_data); exit;

$user_dep_qry = "SELECT `department_users` FROM `departments` WHERE dept_id='$dept_id'";			
	$dept = $this->fetchOne($user_dep_qry, array());
	$qry = "SELECT android_token FROM `user` WHERE `user_id` IN ($dept) ORDER BY `user_id` DESC";
	$user_arr = $this->dataFetchAll($qry, array());
	$cont = $sender_name.': ';
	foreach($user_arr as $at){
		$device_t =  $at['android_token'];
	    $response = $this->sendAppNotification($device_t,'New Whatsapp Group Message from OmniChannel', $cont,'image',$whatsapp_media_target_path);
	}
	$qry = "SELECT android_token FROM `user` WHERE `user_id` ='$admin_id'";
	$admin_token = $this->fetchOne($qry, array());
    $response = $this->sendAppNotification($admin_token,'New Whatsapp Group Message from OmniChannel', $cont,'image',$whatsapp_media_target_path);



//echo $admin_id; echo $instance_id; echo $dept_id; exit;

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
$qry = "select * from chat_wp_uf where app_customer_key ='$from' and app_chat_id ='$to' and admin_id= '$admin_id'";  

 $results = $this->fetchData($qry, array());  




if($results > 0){    

    $chat_id=$results['chat_id'];     
    $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,customer_image,customer_name,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$user_id', '$caption','1', '1','$created_at','$instance_num','$sender','$sender_name','$whatsapp_media_target_path')", array());
    // $chat_data = $this->getChatDetails($chat_msg_id);     
      $mc_event_data = "whatsapp Message From ".$sender_name;
      $c =      $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
  // $this->notification_curl($admintoken,$chat_id,$chat_msg);
	$wp_users[] = $admin_id;
		 $wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;
	$this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);

/*
$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		//$admintoken = $this->fetchOne($qry, array());
		    $this->wpn_curl($admin_id,$chat_id,$chat_msg,$instance_id);	
$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
$agentresult = $this->dataFetchAll($agentqry, array());
$agentcount = $this->dataRowCount($agentqry, array()); 
for($i=0;$i<$agentcount;$i++){		  
  $token = $agentresult[$i]['user_id'];
  $profile_image = $agentresult[$i]['profile_image'];	
  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
}
*/
      return $chat_data;      
} else {

      $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`instance_id`,`department_id`,`customer_image`,`group_name`,`group_icon`,`group_msg`) VALUES ('$to','$from', '$sender_name','sms','$instance_num','0','1','1','$admin_id','$created_at','$instance_id','$dept_id','$sender','$group_name','$group_icon','1')", array());   
      $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,customer_image,customer_name,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$user_id', '$caption','1', '1','$instance_num','$created_at','$sender','$sender_name','$whatsapp_media_target_path')", array());
    //$chat_data = $this->getChatDetails($chat_msg_id);     
      $mc_event_data = "whatsapp Message From ".$sender_name;     
        $c = $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
 // $this->notification_curl($admintoken,$chat_id,$chat_msg);
$wp_users[] = $admin_id;
		$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;
	$this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
	
/*	
	$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		//$admintoken = $this->fetchOne($qry, array());
		    $this->wpn_curl($admin_id,$chat_id,$chat_msg,$instance_id);
	
$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
$agentresult = $this->dataFetchAll($agentqry, array());
$agentcount = $this->dataRowCount($agentqry, array()); 
for($i=0;$i<$agentcount;$i++){		  
  $token = $agentresult[$i]['user_id'];
  $profile_image = $agentresult[$i]['profile_image'];	
  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
}
	*/
      return $chat_data;  
}  }

	function generate_incoming_image_wp_unoff($chat_data){
	     extract($chat_data);
	//print_r($chat_data); exit;
	 $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `whatsapp_num`='$to'";
    $result = $this->fetchData($qry, array());   
    $admin_id = $result['admin_id'];
	$dept_id = $result['department_id'];	
	$admin_num = $result['whatsapp_num'];
	$instance_id = $result['wp_inst_id'];
	$instance_url =  $result['instance_url'];
		$web_hook = $result['web_hook'];
	$url = $instance_url;  
	$postfields = '{ "args": { "message": "'.$message_id.'"} }';
	$url = $url."/decryptMedia";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$headers = array();
	$headers[] = 'Accept: application/json';
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	$result = json_decode($result);
	if($result->success == 'true'){
	$img_Src =  $result->response;
	} else {
		return false;
	}
	$f = explode(';', $img_Src);	
	$img_type= str_replace("data:image/","",$f[0]);	
	$image_name = $time.'.'.$img_type;
	list($type, $img_Src) = explode(';', $img_Src);
	list(, $img_Src)      = explode(',', $img_Src);
	$img_Src = base64_decode($img_Src);
	$destination_path = getcwd().DIRECTORY_SEPARATOR;            
	$whatsapp_media_target_path = $destination_path."whatsapp_image/".$image_name;
	file_put_contents($whatsapp_media_target_path, $img_Src);  
	$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/whatsapp_image/".$image_name;  
		//echo $whatsapp_media_target_path;
		


$chat_data_im = array("from"=>$from,"to"=>$to,"chat_status"=>1,"time"=>$time,"sender_name"=>$sender_name,"message_id"=>$message_id,"sender"=>$sender,"image"=>$whatsapp_media_target_path);
  
		

$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,$web_hook);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($chat_data_im));
curl_setopt($curl, CURLOPT_TIMEOUT, 40);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$result=curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);



$user_dep_qry = "SELECT `department_users` FROM `departments` WHERE dept_id='$dept_id'";			
	$dept = $this->fetchOne($user_dep_qry, array());
	$qry = "SELECT android_token FROM `user` WHERE `user_id` IN ($dept) ORDER BY `user_id` DESC";
	$user_arr = $this->dataFetchAll($qry, array());
	$cont = $sender_name.': ';
	foreach($user_arr as $at){
		$device_t =  $at['android_token'];
	    $response = $this->sendAppNotification($device_t,'New Whatsapp Group Message from OmniChannel', $cont,'image',$whatsapp_media_target_path);
	}
	$qry = "SELECT android_token FROM `user` WHERE `user_id` ='$admin_id'";
	$admin_token = $this->fetchOne($qry, array());
    $response = $this->sendAppNotification($admin_token,'New Whatsapp Group Message from OmniChannel', $cont,'image',$whatsapp_media_target_path);

	// print_r($response); exit;
	//echo $admin_id; echo $instance_id; echo $dept_id; exit;

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
     $qry = "select * from chat_wp_uf where app_customer_key ='$from' and app_chat_id ='$to' and admin_id= '$admin_id'";  

       $results = $this->fetchData($qry, array());  
	



    if($results > 0){    

          $chat_id=$results['chat_id'];     
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,chat_instance,customer_image,customer_name,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$user_id', '$caption','1', '1','$created_at','$instance_num','$sender','$sender_name','$whatsapp_media_target_path')", array());
          //  $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$sender_name;
              $c =  $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,admin_id,mc_event_data,mc_event_type,event_status,created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$admin_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		    //$this->notification_curl($admintoken,$chat_id,$chat_msg);
		$wp_users[] = $admin_id;
	$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;
		$this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);		

/*
		$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		$admintoken = $this->fetchOne($qry, array());
		    $this->wpn_curl($admin_id,$chat_id,$chat_msg,$instance_id);
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['user_id'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
			}
		*/
            return $chat_data;      
    } else {
	
            $chat_id = $this->db_insert("INSERT INTO chat_wp_uf (`app_chat_id`,`app_customer_key`,`customer_name`,`api_type`,`chat_instance`,`assigned_agent`,`chat_status`,`chat_read_status`, `admin_id`,`created_at`,`instance_id`,`department_id`,`customer_image`) VALUES ('$to','$from', '$sender_name','sms','$instance_num','0','1','1','$admin_id','$created_at','$instance_id','$dept_id','$sender')", array());   
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_wp_uf(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status,chat_instance, created_dt,customer_image,customer_name,whatsapp_media_url) VALUES ('$chat_id','text', 'customer','$user_id', '$caption','1', '1','$instance_num','$created_at','$sender','$sender_name','$whatsapp_media_target_path')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);     
            $mc_event_data = "whatsapp Message From ".$sender_name;     
             $c= $this->db_query("INSERT INTO mc_event (user_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt,wp_inst_id) VALUES('$user_id','$chat_id','$mc_event_data','5','7','$created_at','$instance_id')", array());
		  //  $this->notification_curl($admintoken,$chat_id,$chat_msg);
		$wp_users[] = $admin_id;
			$wp_users = $this->fetchData("SELECT GROUP_CONCAT(user_id) FROM `user` where admin_id='$admin_id' and has_whatsapp='1'", array());
		    $wp_users = explode(',',$wp_users['GROUP_CONCAT(user_id)']);		
		    $wp_users[] = $admin_id;
		$this->wpn_curl($wp_users,$chat_id,$mc_event_data,$instance_id);
/*				
		$qry = "SELECT notification_code FROM `user` WHERE `user_id` ='$admin_id'";
		$admintoken = $this->fetchOne($qry, array());
		    $this->wpn_curl($admin_id,$chat_id,$chat_msg,$instance_id);
				
			$agentqry = "SELECT notification_code FROM `user` WHERE `admin_id` ='$admin_id' AND `notification_code`!=''";
			$agentresult = $this->dataFetchAll($agentqry, array());
			$agentcount = $this->dataRowCount($agentqry, array()); 
			for($i=0;$i<$agentcount;$i++){		  
			  $token = $agentresult[$i]['user_id'];
			  $profile_image = $agentresult[$i]['profile_image'];	
			  $this->wpn_curl($token,$chat_id,$chat_msg,$instance_id);
			}
*/
            return $chat_data;  
    }  
    }
  
	
	  function getSearchResForWhatsapp($chat_data){
		  extract($chat_data);
     		 
        $qry = "select * from user where user_id='$user_id'";
    
            $result = $this->fetchData($qry, array());
    
           if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
		  
        $qry = "select * from user where user_id='$admin_id'";
    
        $result = $this->fetchData($qry, array());
     
 $qry = "SELECT * FROM `whatsapp_instance_details` WHERE `wp_inst_id` = '$instance_id'";
    $result = $this->fetchData($qry, array());   
	$wathsapp_num = $result['whatsapp_num'];
			
		  
				// $search_q = '(chat_wp_uf.app_customer_key IN (SELECT GROUP_CONCAT(number) FROM wp_cust_img where name LIKE '%$search_text%') or chat_wp_uf.app_customer_key like '%".$search_text."%' or chat_data_wp_uf.chat_message LIKE '%$search_text%')';
				
				
	 if($user_type == 'Employee'){
     $queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id, chat_data_wp_uf.chat_message, date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, (SELECT name FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_name, (SELECT image_path FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as prof_image,chat_wp_uf.group_msg as group_msg,chat_wp_uf.group_name as group_name,chat_wp_uf.group_icon as group_icon, chat_wp_uf.app_chat_id, chat_wp_uf.api_type, chat_wp_uf.user_id, chat_wp_uf.f_user_id, (SELECT user_name FROM user WHERE user_id= chat_wp_uf.user_id) as user_nm, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.f_user_id) as f_user_nm from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_wp_uf.admin_id = '$admin_id' and chat_wp_uf.chat_status = '1'  and  (chat_wp_uf.user_id = '$user_id' or chat_wp_uf.f_user_id = '$user_id' or chat_wp_uf.user_id IS NULL) and (chat_wp_uf.app_customer_key IN (SELECT GROUP_CONCAT(number) FROM wp_cust_img where name LIKE '%$search_text%') or chat_wp_uf.app_customer_key like '%".$search_text."%' or chat_data_wp_uf.chat_message LIKE '%$search_text%') order by chat_data_wp_uf.chat_msg_id desc";
	 } else {
	 $queue_chat_qry = "select chat_data_wp_uf.chat_msg_id, chat_data_wp_uf.read_status, chat_data_wp_uf.chat_id, chat_data_wp_uf.chat_message, date_format(chat_data_wp_uf.created_dt, '%d/%m/%Y') as chat_dt, TIME_FORMAT(chat_data_wp_uf.created_dt, '%H:%i') as chat_time, (SELECT name FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as customer_name, (SELECT image_path FROM wp_cust_img WHERE number= chat_wp_uf.app_customer_key) as prof_image ,chat_wp_uf.group_msg as group_msg,chat_wp_uf.group_name as group_name,chat_wp_uf.group_icon as group_icon, chat_wp_uf.app_chat_id, chat_wp_uf.api_type, chat_wp_uf.user_id, chat_wp_uf.f_user_id, (SELECT user_name FROM user WHERE user_id= chat_wp_uf.user_id) as user_nm, (SELECT agent_name FROM user WHERE user_id= chat_wp_uf.f_user_id) as f_user_nm from chat_wp_uf inner join chat_data_wp_uf on chat_data_wp_uf.chat_id = chat_wp_uf.chat_id where chat_wp_uf.admin_id != '' and chat_wp_uf.admin_id = '$admin_id' and chat_wp_uf.instance_id = '$instance_id' and chat_wp_uf.chat_status = '1' and chat_wp_uf.app_chat_id = '$wathsapp_num' and (chat_wp_uf.app_customer_key IN (SELECT GROUP_CONCAT(number) FROM wp_cust_img where name LIKE '%$search_text%') or chat_wp_uf.app_customer_key like '%".$search_text."%' or chat_data_wp_uf.chat_message LIKE '%$search_text%')  order by chat_data_wp_uf.chat_msg_id desc";
	 }			
				
				

// print_r($queue_chat_qry); exit;
         
         $parms = array();
         $result = $this->dataFetchAll($queue_chat_qry,array());
    
         return $result;
         
    
     }

	
	}
