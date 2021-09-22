<?php
//echo "123";exit;
//require __DIR__ . '/../../eio/vendor/autoload.php';
//use ElephantIO\Client;
//use ElephantIO\Engine\SocketIO\Version1X as Version1X;
class webChat extends restApi{
    
    
    function generateChat($customer_data,$chat_msg,$queue_id,$admin_id,$department,$widget_name,$chatUrl,$msg_user_type,$flag){
		$fl = 'bot';
		//print_r($customer_data);
		  //echo $chat_msg;exit;
		//file_put_contents('chat.txt', $chat_msg.PHP_EOL , FILE_APPEND | LOCK_EX);
        $get_qry = "SELECT agents FROM chat_widget WHERE widget_name='$widget_name'"; 
        $user_details = $this->fetchOne($get_qry,array());
		$qry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
		$start_bot_qry = "select question from chat_question where admin_id='$admin_id' and status = 1"; 
        $res = $this->fetchData($qry, array());        
		$start_bots = $this->fetchData($start_bot_qry, array());
		$admintoken = $res['notification_code'];
		$customer_id =  $this->db_insert('INSERT INTO customer(customer_web_code, customer_name, customer_email, phone_number, customer_type, customer_cat, city, country, created_ip) VALUES (:customer_web_code,:customer_name,:customer_email,"","1","1",:client_city,:client_country,:client_ip)',$customer_data);
        $result = array();  

        if($customer_id > 0){       

            $chat_id = $this->db_insert("INSERT INTO chat(chat_user, chat_type, chat_cat, chat_queue, assigned_user, chat_status, admin_id, created_ip, department, widget_name, agents,read_status,chatUrl) VALUES ('$customer_id','1','1','$queue_id','0','5','$admin_id','','$department','$widget_name','$user_details','1','$chatUrl')", array());    

            if($chat_id > 0){
			 if($fl != 'bot'){	
				if($chat_id != "" && $chat_id != 0){ 
					$dt = date('Y-m-d H:i:s');
					$mc_event_data = "Chat message from ".$customer_data["customer_name"]."[".$widget_name."]";			
					//$department="'" . str_replace(",", "','", $department) . "'";							
					$dep_users=$this->fetchOne("SELECT department_users as dep_user FROM `departments` where dept_id IN($department)",array());	
					$ex=array_unique(explode(',',$dep_users));
					$count_ex=count($ex);
					for($i=0; $i<$count_ex; $i++){
					   $user_id= $ex[$i];
						$this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt,agents,widget_name,user_id) values('$admin_id','$chat_id','$mc_event_data','1','7','$dt','$user_details','$widget_name','$user_id')", array());
					}					
				} 
			   }	
               $chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$customer_id, "msg_user_type"=>$msg_user_type,"msg_type"=>"text","chat_msg"=>$chat_msg,"msg_status"=>1,"user_details"=>$user_details,"admintoken"=>$admintoken,"flag"=>$flag);
				
				//print_r($chat_data);exit;
				 $chatbot_question= $start_bots['question'];
               $chat_msg_id =  $this->insertChatMessage($chat_data);                
                $result = array("customer_id"=>$customer_id,"chat_id"=>$chat_id,"chat_msg_id"=>$chat_msg_id,"start_chat"=>$chatbot_question);
            }
		}        
        return $result;        
    }
    
    function insertChatMessage($chat_data){        
        extract($chat_data);		
		//$t = "INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status) VALUES ('$chat_id', '$msg_user_id', '$msg_user_type', '$msg_type', '$chat_msg', '1')";
		//file_put_contents('chat.txt', $t.PHP_EOL , FILE_APPEND | LOCK_EX);
           $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status) VALUES ('$chat_id', '$msg_user_id', '1', '$msg_type', '$chat_msg', '1')", array());
		 /*
           $this->chat_notification_curl($admintoken,$chat_id,$chat_msg);
		   $explode = explode(',', $user_details);
           $cnt = count($explode);
           for($i=0;$i<$cnt;$i++){
            $uid = $explode[$i];
            $agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$uid'";
            $agentresult = $this->fetchData($agentqry, array());
            $token = $agentresult['notification_code'];
            //$profile_image = $agentresult[$i]['profile_image']; 
            $this->chat_notification_curl($token,$chat_id,$chat_msg);
           }*/		
        return $chat_msg_id;           
    }
	
	function update_chat_message($chat_data){   
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
									  
		$files_array = $files_arr;
		$ticketMedia = implode(",",$files_arr);	
		$files_arr = implode(",",$files_arr);
		$extension_arr = $ext_arr;
        $ext_arr = implode(",",$ext_arr);
		
		
           $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status,chat_images,extension) VALUES ('$chat_id', '$msg_user_id', '$msg_user_type', '$msg_type', '$chat_msg', '1','$files_arr','$ext_arr')", array());
		
		 $update="UPDATE chat SET read_status='1' where chat_id='$chat_id'";		
		 $qry_result = $this->db_query($update, array()); 
		
           $widget_name_qry = "SELECT widget_name FROM chat WHERE chat_id='$chat_id'"; 
           $widget_name = $this->fetchOne($widget_name_qry,array());
		   $admin_idqry = "SELECT admin_id FROM `chat` WHERE `chat_id` ='$chat_id'";
           $adminid = $this->fetchOne($admin_idqry, array());
		   $admin_tokenqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$adminid'";
           $admintoken = $this->fetchOne($admin_tokenqry, array());
		   $this->chat_notification_curl($admintoken,$chat_id,$chat_msg);
		   $get_qry = "SELECT agents FROM chat_widget WHERE widget_name='$widget_name'"; 
           $user_details = $this->fetchOne($get_qry,array());
		   $explode = explode(',', $user_details);
           $cnt = count($explode);
           for($i=0;$i<$cnt;$i++){
            $uid = $explode[$i];
            $agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$uid'";
            $agentresult = $this->fetchData($agentqry, array());
            $token = $agentresult['notification_code'];
            //$profile_image = $agentresult[$i]['profile_image']; 
            $this->chat_notification_curl($token,$chat_id,$chat_msg);
           }		
		
		
		$chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg,chat_msg.chat_images, chat_msg.msg_status,chat_msg.extension, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat_msg.chat_msg_id = '$chat_msg_id' order by chat_msg.chat_msg_id asc limit 1";
    
    
     $parms = array();
        $result = $this->fetchData($chat_detail_qry,array());	
	


  $status = array('status' => 'true');
		$response_array = array('data' => $result);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
	         
    }
    
    function getChatQueue(){
        
        $queue_data = $this->dataFetchAll("select queue_id, queue_name, queue_status from queue where queue_status='1'",array());
        
        return $queue_data;       
    }
	
	 function updateOnOffStatus($chat_data){
		
		extract($chat_data);
		$qry = "UPDATE chat SET online_status='$onoff_status' WHERE chat_id='$chat_id'";
		$qry_result = $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;

	}
	
	/*function updateChatStatus($chat_data){        
        extract($chat_data);		
        //print_r($chat_data);exit;
		$admin_id_qry = "SELECT admin_id FROM chat WHERE chat_id='$chat_id'";
        $admin_id = $this->fetchOne($admin_id_qry,array());
		$domain_name_qry = "SELECT domain_name FROM user WHERE user_id='$admin_id'";
        $domain_name = $this->fetchOne($domain_name_qry,array());
        $admin_email_qry = "SELECT email_id FROM user WHERE user_id='$admin_id'";
        $admin_email = $this->fetchOne($admin_email_qry,array());
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
      $email_message =  "Chat on ".$domain_name."<br>Conversation started on : ".$chat_start_day."<br>";
      $email_message .= "[".$chat_time."] Name : ".$customer_name."<br>Email : ".$customer_email."<br>Phone : ".$customer_phone."<br>Department :".$chat_dept_name."<br>Question : ".$first_chat_msg;

      $chat_detail_qry = "select chat.chat_id, customer.customer_name, chat_msg.chat_msg_id, chat_msg.chat_msg, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time,chat_msg.msg_user_type, user.user_name from chat INNER JOIN chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on user.user_id = chat_msg.msg_user_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id ASC";
      $parms = array();
      $result = $this->dataFetchAll($chat_detail_qry,array());
      $items = '';      
      foreach($result as $record){
        $chat_times = $record['chat_time'];
        $chat_user_type = $record['msg_user_type'];
        if($chat_user_type==2){
          $user_name = $record['user_name'];
        }else{
          $user_name = $record['customer_name'];
        }
        $chat_message = $record['chat_msg'];
        $items .= "[".$chat_time."] ".$user_name." : ".$chat_message."\n";		
      }
		require_once('class.phpmailer.php');
		//$email = 've@cal4care.com';
        $from = 'Omni Channel';
        //$admin_email = 've@cal4care.com';
        $subject = "Chat transcript on ".$domain_name." started on ".$chat_start_day." , at ".$chat_time;
		$message = $email_message.$items;
      $body = addslashes($message);                
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
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($admin_email);
		$mail->Send();
        $qry = "UPDATE chat SET chat_status='2' WHERE chat_id='$chat_id'";
        $qry_result = $this->db_query($qry, array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;           
    }*/
    
function insertChatBotMessage($chat_id,$customer_id,$chat_message,$msg_user_type){
   $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg) VALUES ('$chat_id', '$customer_id', '$msg_user_type', 'bot', '$chat_message')", array());
   return $chat_message;           
} 

function generateNewChat($chat_id,$customer_id,$msg_user_type,$chat_message,$admin_id,$widget_name,$department){
	//print_r($customer_data);
	    $get_qry = "SELECT agents FROM chat_widget WHERE widget_name='$widget_name'"; 
        $user_details = $this->fetchOne($get_qry,array());
		$qry = "SELECT * FROM `user` WHERE `user_id` ='$admin_id'";
        $res = $this->fetchData($qry, array());        
		$admintoken = $res['notification_code'];
		$get_cname_qry = "SELECT customer_name FROM customer WHERE customer_id='$customer_id'"; 
        $customer_name = $this->fetchOne($get_cname_qry,array());  
		$dt = date('Y-m-d H:i:s');
		$mc_event_data = "Chat message from ".$customer_name."[".$widget_name."]";			
		$dep_users=$this->fetchOne("SELECT department_users as dep_user FROM `departments` where dept_id IN($department)",array());	
		$ex=array_unique(explode(',',$dep_users));
		$count_ex=count($ex);
		for($i=0; $i<$count_ex; $i++){
		   $user_id= $ex[$i];
		   $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt,agents,widget_name,user_id) values('$admin_id','$chat_id','$mc_event_data','1','7','$dt','$user_details','$widget_name','$user_id')", array());
		}					
		$chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$customer_id, "msg_user_type"=>$msg_user_type,"msg_type"=>"text","chat_msg"=>$chat_message,"msg_status"=>1,"user_details"=>$user_details,"admintoken"=>$admintoken);
		$chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status) VALUES ('$chat_id', '$customer_id', '$msg_user_type', 'text', '$chat_message', '1')", array());
		$this->chat_notification_curl($admintoken,$chat_id,$chat_message);
		$explode = explode(',', $user_details);
        $cnt = count($explode);
        for($i=0;$i<$cnt;$i++){
            $uid = $explode[$i];
            $agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$uid'";
            $agentresult = $this->fetchData($agentqry, array());
            $token = $agentresult['notification_code'];
            //$profile_image = $agentresult[$i]['profile_image']; 
            $this->chat_notification_curl($token,$chat_id,$chat_message);
        }                       
        $result = array("customer_id"=>$customer_id,"chat_id"=>$chat_id,"chat_msg_id"=>$chat_msg_id);	       
        return $result;        
}	
    
}