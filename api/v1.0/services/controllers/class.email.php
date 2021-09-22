<?php
class email extends restApi{
    
    
    function getcustomersMail($user_id,$queue_id){
        
        
        if($queue_id == null || $queue_id==""){
            
            $queue_condtion = "select queue.queue_id from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and  FIND_IN_SET('2', q_user.queue_feature)  and queue.queue_status = '1' and q_user.queue_user_status = '1'";
            
            
        }
        else{
            $queue_condtion = $queue_id;
        }
        
        $queue_chat_qry = "select chat.chat_id, chat.chat_code,chat.chat_user,chat.chat_type,chat.chat_queue,chat.assigned_user, chat.chat_status, customer.customer_name from chat left join customer on customer.customer_id = chat.chat_user where chat.chat_queue in ($queue_condtion) and chat.chat_type in (6) order by chat.chat_id desc";

        
        $parms = array();
        $result = $this->dataFetchAll($queue_chat_qry,array());

        return $result;
        

    }
    
    function mailDetailList($chat_id){
        
        
        $chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type as chat_msg_user_type, chat_msg.msg_type, TO_BASE64(chat_msg.chat_msg) as chat_msg, chat_msg.msg_status, customer.customer_name, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' order by chat_msg.chat_msg_id asc";
        
        
            
        $parms = array();
        $result = $this->dataFetchAll($chat_detail_qry,array());

        return $result;
        
        
    }
    
    
    function insertMailThread($chat_data){
        
        extract($chat_data);
        
        $chat_msg_id = $this->db_insert("INSERT INTO chat_msg(chat_id, msg_user_id, msg_user_type, msg_type, chat_msg, msg_status) VALUES ('$chat_id', '$msg_user_id', '$msg_user_type', '$msg_type', '$chat_msg', '1')", array());
        
   
        return $chat_msg_id;
           
    }
	
	
	
    function generateMail($customer_data,$chat_msg,$queue_id){
        
       $customer_id =  $this->db_insert('INSERT INTO customer(customer_web_code, customer_name, customer_email, phone_number, customer_type, customer_cat) VALUES (:customer_web_code,:customer_name,:customer_email,"","1","1")',$customer_data);
   
        $result = array();
        
        if($customer_id > 0){
           
            $chat_id = $this->db_insert("INSERT INTO chat(chat_user, chat_subject, chat_type, chat_cat, chat_queue, assigned_user, chat_status, created_ip) VALUES ('$customer_id','$chat_subject','6','3','$queue_id','0','5','')", array());
            
            if($chat_id > 0){
                
                if($chat_id != "" && $chat_id != 0){
                    $dt = date('Y-m-d H:i:s');
                    $mc_event_data = $chat_subject;
                    $this->db_query("Insert into mc_event (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$chat_id','$mc_event_data','1','7','$dt')", array());
                }
                
               $chat_data = array("chat_id"=>$chat_id,"msg_user_id"=>$customer_id, "msg_user_type"=>1,"msg_type"=>"text","chat_msg"=>$chat_msg,"msg_status"=>1);
               $chat_msg_id =  $this->insertMailThread($chat_data);
                
                $result = array("customer_id"=>$customer_id,"chat_id"=>$chat_id,"chat_msg_id"=>$chat_msg_id);
            }
            
        }
        
        return $result;
        
        
    }
	
	function getMailParticipants($chat_id){
		
		
		$result = $this->fetchData("select chat_id,(select (case when chat.msg_from = 1 then (select customer_email from customer where customer_id = chat.chat_user) when chat.msg_from = 2 then (select email_id from user where user_id = chat.chat_user) ELSE null end) from chat where chat_id=chat_participants.chat_id) as email_user, TRIM(BOTH ',' FROM (GROUP_CONCAT((case when msg_user_type = 1 then (select customer_email from customer where customer_id = chat_participants.msg_user_id) when msg_user_type = 2 then (select email_id from user where user_id = chat_participants.msg_user_id) end)))) as email_list from chat_participants  where chat_id =:chat_id", array("chat_id"=>$chat_id));
		
		return $result;
	
	}
	
    
    
    
}
    