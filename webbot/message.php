<?php

if(count($_POST) > 0){
	
include_once 'admin.controller.php';
$admin = new adminData();

	extract($_POST);
	
	if($action_data == "chat_generate"){

		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"generate_chat","customer_name"=>$customer_name,"customer_email"=>$customer_email,"customer_web_code"=>$customer_web_code,"chat_message"=>$chat_message,"queue_id"=>$queue_id,"admin_id"=>$admin_id, "client_city" => $client_city, "client_country"=> $client_country, "client_ip"=> $client_ip, "department"=>$department,"widget_name"=>$widget_name,"chatUrl"=>$chatUrl)));
	

		$chat_data  = json_decode($admin->curl_data($api_data));

		$result = 0;
		if($chat_data->status == 1){
			
			$chat_info = (array)$chat_data->result->data->chat_data;

			//file_put_contents('tester.txt', json_encode($chat_data->result->chat_data).PHP_EOL , FILE_APPEND | LOCK_EX);

			extract($chat_info);

				if(count($chat_info) >0){

					$result = '1^^^^<input type="hidden" id="chat_id" value="'.$chat_id.'"><input type="hidden" id="chat_msg_id" value="'.$chat_msg_id.'"><input type="hidden" id="queue_id" value="'.$queue_id.'"><input type="hidden" id="customer_id" value="'.$customer_id.'"><input type="hidden" id="customer_web_code" value="'.$customer_web_code.'"><input type="hidden" id="customer_name" value="'.$customer_name.'"><input type="hidden" id="customer_email" value="'.$customer_email.'"><input type="hidden" id="start_chat" value="'.$start_chat.'">';

//<div class="chat-body chat-field-panel" id="messages">
  //  <h6>Live Chat By '.$customer_name.'</h6>
    //<div class="customer-msg msg-row">
      //  <img src="images/user-theme.svg">
      //  <p>'.$chat_message.'</p><span>You</span>
   // </div>
//</div>';
					
				}
		}

		echo $result;

	}
	elseif($action_data == "send_chat_message"){

		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"update_chat_message","chat_id"=>$chat_id,"customer_id"=>$customer_id,"chat_message"=>$chat_message)));
//print_r($api_data); exit;
		$chat_data  = json_decode($admin->curl_data($api_data));

		echo $chat_data->result->data->chat_msg_id;


	}

	elseif($action_data == "queue_list"){


		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"get_chat_queue")));

		$queue_chat  = json_decode($admin->curl_data($api_data));

		$result = 0;
		$queue_list_option = "";
		if($queue_chat->status == 1){

			foreach ($queue_chat->result->data->queue_data as $queue_data) {

				$row = (array)$queue_data;
				extract($row);

				$queue_list_option.="<option value='".$queue_id."'>".$queue_name."</option>";

				
			}

			$result = "1^^^^".$queue_list_option;

		}

		echo $result;

		
	}

	elseif($action_data == "end_chat_message"){
	
		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"update_chat_status","chat_id"=>$chat_id,"widget_name"=>$widget_id)));
		
		echo $chat_data  = $admin->curl_data($api_data);
	}
	
	elseif($action_data == "on_off_status"){

		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"on_off_status","chat_id"=>$chat_id,"onoff_status"=>$onoff_status)));
		
		echo $chat_data  = $admin->curl_data($api_data);
	}

    elseif($action_data == "add_chatbot_message"){
		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"addchatbot_message","chat_id"=>$chat_id,"customer_id"=>$customer_id,"chat_message"=>$chat_message,"msg_user_type"=>$msg_user_type)));
        //print_r($api_data); exit;
		$chat_data  = json_decode($admin->curl_data($api_data));
		echo $chat_message;
	}

	elseif($action_data == "new_chat_generate"){
		$api_data = json_encode(array("operation"=>"web_chat","moduleType"=>"web_chat","api_type"=>"web_chat","element_data"=>array("action"=>"newchat_generate","chat_id"=>$chat_id,"customer_id"=>$customer_id,"chat_message"=>$chat_message,"admin_id"=>$admin_id,"widget_name"=>$widget_name,"msg_user_type"=>$msg_user_type,"department"=>$department,"chat_msg_id"=>$chat_msg_id,"queue_id"=>$queue_id,"customer_web_code"=>$customer_web_code,"customer_name"=>$customer_name,"customer_email"=>$customer_email)));
		$chat_data  = json_decode($admin->curl_data($api_data));
		$result = '1^^^^<input type="hidden" id="chat_id" value="'.$chat_id.'"><input type="hidden" id="chat_msg_id" value="'.$chat_msg_id.'"><input type="hidden" id="queue_id" value="'.$queue_id.'"><input type="hidden" id="customer_id" value="'.$customer_id.'"><input type="hidden" id="customer_web_code" value="'.$customer_web_code.'"><input type="hidden" id="customer_name" value="'.$customer_name.'"><input type="hidden" id="customer_email" value="'.$customer_email.'">
   <div class="chat-body chat-field-panel" id="messages">
   <h6>Live Chat By '.$customer_name.'</h6>
   <div class="customer-msg msg-row">
    <img src="images/user-theme.svg">
    <p>'.$chat_message.'</p><span>You</span>
   </div>
   </div>';
		echo $result;
	}


}






?>