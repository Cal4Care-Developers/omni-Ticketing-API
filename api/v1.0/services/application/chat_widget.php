<?php
//echo "123";exit;
$result_data["status"] = true; 
$chat_widget = new chat_widget();
if($_REQUEST['action'] == 'chat_image_upload') {  
    $action ='chat_image_upload'; 
    $img_user_id = $_REQUEST['user_id'];
    $widget_id = $_REQUEST['widget_id'];
    $image_id = $_REQUEST['image_id'];     
    $chat_image=$_FILES['chat_image']['name'];
}
if($action == "add_chat_widget"){  
    $chat_data = array("user_id"=>$user_id, "widget_name"=>$widget_name);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->add_chat_widget($chat_data);
}
elseif($action == "edit_chat_widget"){ 
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "widget_name"=>$widget_name, "color"=>$color);     
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->edit_chat_widget($chat_data);
}
elseif($action == "widget_list"){
    $chat_data = array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->widget_list($chat_data);
}
elseif($action == "get_by_id"){
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->get_by_id($chat_data);
}
elseif($action == "widget_color"){
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "color"=>$color);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->widget_color($chat_data);
}
elseif($action == "widget_onclick_behaviour"){
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "behaviour"=>$behaviour);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->widget_onclick_behaviour($chat_data);
}
elseif($action == "widget_behaviour"){    
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "keyword"=>$keyword);    
    $result_data["result"]["data"] = $chat_widget->widget_behaviour($chat_data);
}
elseif($action == "get_widget_data"){
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->get_widget_data($chat_data);
}
elseif($action == "widget_advanced_settings"){
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id,"widget_appearance"=>$widget_appearance,"widget_position"=>$widget_position,"attention_grabber"=>$attention_grabber,"mobile_widget"=>$mobile_widget);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->widget_advanced_settings($chat_data);
}
elseif($action == "chat_image_upload"){  
    $chat_data = array("chat_image"=>$chat_image,"img_user_id"=>$img_user_id,"widget_id"=>$widget_id,"image_id"=>$image_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->chat_image_upload($chat_data);
}
elseif($action == "chat_image_list"){
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->chat_image_list($chat_data);
}
elseif($action == "update_chat_image"){
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id,"image_id"=>$image_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->update_chat_image($chat_data);
}
elseif($action == "add_consent_form_option"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "display_option_value"=>$display_option_value);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->add_consent_form_option($chat_data);
}
elseif($action == "edit_consent_form_option"){  
    $chat_data = array("id"=>$id); 
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->edit_consent_form_option($chat_data);
}
elseif($action == "update_consent_form_option"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "id"=>$id,"display_option_value"=>$display_option_value);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->update_consent_form_option($chat_data);
}
elseif($action == "delete_consent_form_option"){     
    $data = array("id"=>$id);       
    $result_data["result"]["data"] = $chat_widget->delete_consent_form_option($data);
}
elseif($action == "consent_form_option_list"){
    $chat_data = array("user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->consent_form_option_list($chat_data);
}
elseif($action == "update_consent_form_data"){
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "id"=>$id,"consent_message"=>$consent_message,"privacy_policy_link"=>$privacy_policy_link,"privacy_policy_text"=>$privacy_policy_text,"opt_in_button"=>$opt_in_button,"opt_out_button"=>$opt_out_button);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->update_consent_form_data($chat_data);
}
elseif($action == "choose_consent_form_option"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "id"=>$id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->choose_consent_form_option($chat_data);
}
elseif($action == "country_restriction"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "has_restriction"=>$has_restriction, "option_value"=>$option_value, "countries"=>$countries);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->country_restriction($chat_data);
}
elseif($action == "chat_timezone"){      
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id,"offline_email"=>$offline_email,"chat_aviator"=>$chat_aviator,"chat_agent_name"=>$chat_agent_name,"main_timeZone"=>$main_timeZone,"has_department"=>$has_department);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->chat_timezone($chat_data);
}
elseif($action == "get_single_widget_data"){
    //$chat_data = array("user_id"=>$user_id,"widget_name"=>$widget_name,"chat_time"=>$chat_time);
	 $chat_data = array("user_id"=>$user_id,"widget_name"=>$widget_name,"chat_time"=>$chat_time,"client_country"=>$client_country);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->get_single_widget_data($chat_data);
}
elseif($action == "delete_widget_data"){     
    $data = array("widget_id"=>$widget_id);       
    $result_data["result"]["data"] = $chat_widget->delete_widget_data($data);
}
elseif($action == "list_country_code"){         
    $result_data["result"]["data"] = $chat_widget->list_country_code();
}
elseif($action == "get_chat_data"){
	$user_id = base64_decode($user_id);
    $chat_data = array("user_id"=>$user_id,"chat_id"=>$chat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->get_chat_data($chat_data);
}
elseif($action == "get_chat_sound"){    
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $chat_widget->get_chat_sound();
}
elseif($action == "dept_agent_list"){  
    $chat_data = array("admin_id"=>$admin_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->dept_agent_list($chat_data);
}
elseif($action == "update_chat_sound"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "sound_id"=>$sound_id);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->update_chat_sound($chat_data);
}
elseif($action == "user_restriction"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "departments"=>$departments, "agents"=>$agents);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->user_restriction($chat_data);
}
elseif($action == "update_activity_time"){  
    $chat_data = array("user_id"=>$user_id, "widget_id"=>$widget_id, "widget_activity_time"=>$widget_activity_time);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->update_activity_time($chat_data);
}
elseif($action == "add_chat_scheduler_main"){      
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id,"day1_opening_time"=>$day1_opening_time,"day1_close_time"=>$day1_close_time,"day2_opening_time"=>$day2_opening_time,"day2_close_time"=>$day2_close_time,"day3_opening_time"=>$day3_opening_time,"day3_close_time"=>$day3_close_time,"day4_opening_time"=>$day4_opening_time,"day4_close_time"=>$day4_close_time,"day5_opening_time"=>$day5_opening_time,"day5_close_time"=>$day5_close_time,"day6_opening_time"=>$day6_opening_time,"day6_close_time"=>$day6_close_time,"day7_opening_time"=>$day7_opening_time,"day7_close_time"=>$day7_close_time,);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->add_chat_scheduler_main($chat_data);
}
elseif($action == "add_chat_scheduler_sub"){      
    $chat_data = array("user_id"=>$user_id,"widget_id"=>$widget_id,"day1_opening_time_s"=>$day1_opening_time_s,"day1_close_time_s"=>$day1_close_time_s,"day2_opening_time_s"=>$day2_opening_time_s,"day2_close_time_s"=>$day2_close_time_s,"day3_opening_time_s"=>$day3_opening_time_s,"day3_close_time_s"=>$day3_close_time_s,"day4_opening_time_s"=>$day4_opening_time_s,"day4_close_time_s"=>$day4_close_time_s,"day5_opening_time_s"=>$day5_opening_time_s,"day5_close_time_s"=>$day5_close_time_s,"day6_opening_time_s"=>$day6_opening_time_s,"day6_close_time_s"=>$day6_close_time_s,"day7_opening_time_s"=>$day7_opening_time_S,"day7_close_time_s"=>$day7_close_time_s);  
    $result_data["result"]["status"] = true;    
    $result_data["result"]["data"] = $chat_widget->add_chat_scheduler_sub($chat_data);
}
