<?php

class chat_widget extends restApi{
  	
  public function add_chat_widget($chat_data){
      extract($chat_data);      
      $get_qry = "SELECT * FROM chat_widget WHERE admin_id='$user_id' AND widget_name='$widget_name'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = 0;
		return $result;
      }
      else{           
		$qry_result = $this->db_query("INSERT INTO chat_widget(admin_id,widget_name,color,widget_appearance,widget_position,mobile_widget,image_id,behaviour,option_value,main_timeZone,office_in_time,office_out_time,schedule_timeZone,s_office_in_time,s_office_out_time,chat_sound,widget_activity_time) VALUES ( '$user_id','$widget_name','#000','online','bottom-right','online','1','popout','Show widget only to visitors of the following countries','1','08:00','17:00','2','08:00','17:00','1','10')", array());  
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
  }

  public function edit_chat_widget($chat_data){
      extract($chat_data);              
      $qry_result = $this->db_query("UPDATE chat_widget SET widget_name='$widget_name',color='$color' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }

  public function widget_list($chat_data){
    extract($chat_data);     
	//$get_qry = "SELECT chat_widget.*,chat_widget_image.chat_image as image_url FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id WHERE chat_widget.admin_id='$user_id'"; 
	  $get_qry = "SELECT chat_widget.*,chat_widget_image.chat_image as image_url,chat_scheduler_main.day1_opening_time,chat_scheduler_main.day1_close_time,chat_scheduler_main.day2_opening_time,chat_scheduler_main.day2_close_time,chat_scheduler_main.day3_opening_time,chat_scheduler_main.day3_close_time,chat_scheduler_main.day4_opening_time,chat_scheduler_main.day4_close_time,chat_scheduler_main.day5_opening_time,chat_scheduler_main.day5_close_time,chat_scheduler_main.day6_opening_time,chat_scheduler_main.day6_close_time,chat_scheduler_main.day7_opening_time,chat_scheduler_main.day7_close_time,chat_scheduler_sub.day1_opening_time_s,chat_scheduler_sub.day1_close_time_s,chat_scheduler_sub.day2_opening_time_s,chat_scheduler_sub.day2_close_time_s,chat_scheduler_sub.day3_opening_time_s,chat_scheduler_sub.day3_close_time_s,chat_scheduler_sub.day4_opening_time_s,chat_scheduler_sub.day4_close_time_s,chat_scheduler_sub.day5_opening_time_s,chat_scheduler_sub.day5_close_time_s,chat_scheduler_sub.day6_opening_time_s,chat_scheduler_sub.day6_close_time_s,chat_scheduler_sub.day7_opening_time_s,chat_scheduler_sub.day7_close_time_s FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id LEFT JOIN chat_scheduler_main ON chat_scheduler_main.widget_id=chat_widget.id LEFT JOIN chat_scheduler_sub ON chat_scheduler_sub.widget_id=chat_widget.id WHERE chat_widget.admin_id='$user_id'";
    $result = $this->dataFetchAll($get_qry,array());
    return $result;
   }
	
   public function get_by_id($chat_data){
    extract($chat_data);//print_r($chat_data);exit; 	   
	   //$get_qry = "SELECT chat_widget.*,chat_widget_image.id as chat_image_id,chat_widget_image.chat_image,chat_sound.id as chat_sound_id,chat_sound.sound_file_path FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id LEFT JOIN chat_sound ON chat_sound.id=chat_widget.chat_sound WHERE chat_widget.id='$widget_id' AND chat_widget.admin_id='$user_id'";
	   $get_qry = "SELECT chat_widget.*,chat_widget_image.id as chat_image_id,chat_widget_image.chat_image,chat_sound.id as chat_sound_id,chat_sound.sound_file_path,chat_scheduler_main.day1_opening_time,chat_scheduler_main.day1_close_time,chat_scheduler_main.day2_opening_time,chat_scheduler_main.day2_close_time,chat_scheduler_main.day3_opening_time,chat_scheduler_main.day3_close_time,chat_scheduler_main.day4_opening_time,chat_scheduler_main.day4_close_time,chat_scheduler_main.day5_opening_time,chat_scheduler_main.day5_close_time,chat_scheduler_main.day6_opening_time,chat_scheduler_main.day6_close_time,chat_scheduler_main.day7_opening_time,chat_scheduler_main.day7_close_time FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id LEFT JOIN chat_sound ON chat_sound.id=chat_widget.chat_sound LEFT JOIN chat_scheduler_main ON chat_scheduler_main.widget_id = chat_widget.id WHERE chat_widget.id='$widget_id' AND chat_widget.admin_id='$user_id'";
    $result = $this->fetchdata($get_qry,array());
    return $result;
   }
  
   public function widget_onclick_behaviour($chat_data){
      extract($chat_data);      
      $qry_result = $this->db_query("UPDATE chat_widget SET behaviour='$behaviour' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
   }
  
  public function widget_behaviour($data){
    extract($data);//print_r($data);exit;
    $qry_select="SELECT * FROM chat_widget WHERE id='$widget_id' AND admin_id='$user_id'";
    $parms = array();
    $results = $this->fetchdata($qry_select,$parms);	
    if($keyword=='hide_estimated_wait_time'){
      if($results['hide_estimated_wait_time']=='0'){
         $value=$hide_estimated_wait_time='1';
      }else{ $value=$hide_estimated_wait_time='0';}  
    }
    if($keyword=='disable_sound_notification'){
      if($results['disable_sound_notification']=='0'){
        $value=$disable_sound_notification='1';
      }else{$value=$disable_sound_notification='0';}  
    }
    if($keyword=='disable_message_preview'){
      if($results['disable_message_preview']=='0'){
        $value=$disable_message_preview='1';
      }else{$value=$disable_message_preview='0';}    
    }
    if($keyword=='disable_agent_typing_notification'){
      if($results['disable_agent_typing_notification']=='0'){
        $value=$disable_agent_typing_notification='1';
      }else{$value=$disable_agent_typing_notification='0';} 
    }
    if($keyword=='disable_visitor_typing_function'){
      if($results['disable_visitor_typing_function']=='0'){
        $value=$disable_visitor_typing_function='1';
      }else{$value=$disable_visitor_typing_function='0';}   
    }
    if($keyword=='disable_browser_tab_notification'){
      if($results['disable_browser_tab_notification']=='0'){
        $value=$disable_browser_tab_notification='1';
      }else{
        $value=$disable_browser_tab_notification='0';}    
    }
    if($keyword=='hide_widget_when_offline'){
      if($results['hide_widget_when_offline']=='0'){
        $value=$hide_widget_when_offline='1';
      }else{$value=$hide_widget_when_offline='0';}     
    }
    if($keyword=='hide_widget_on_load'){
      if($results['hide_widget_on_load']=='0'){
      $value=$hide_widget_on_load='1';
      }else{$value=$hide_widget_on_load='0';}     
    }
    if($keyword=='hide_widget_on_mobile'){
      if($results['hide_widget_on_mobile']=='0'){
      $value=$hide_widget_on_mobile='1';
      }else{$value=$hide_widget_on_mobile='0';}    
    }
    if($keyword=='disable_emoji_selection'){
      if($results['disable_emoji_selection']=='0'){
      $value=$disable_emoji_selection='1';
      }else{$value=$disable_emoji_selection='0';}     
    }
    if($keyword=='disable_file_upload'){
      if($results['disable_file_upload']=='0'){
      $value=$disable_file_upload='1';
      }else{$value=$disable_file_upload='0';}     
    }
      if($keyword=='disable_chat_rating'){
      if($results['disable_chat_rating']=='0'){
      $value=$disable_chat_rating='1';
      }else{$value=$disable_chat_rating='0';}    
    }        
    $qry = "UPDATE chat_widget SET $keyword='$value' WHERE id='$widget_id' AND admin_id='$user_id'";    
      $parms = array();
      $qryresults = $this->db_query($qry,$parms);
      $output = $qryresults == 1 ? 1 : 0;   
      return  $output;                   
    }
	public function get_widget_data($chat_data){
		extract($chat_data); 
		//$get_qry = "SELECT * FROM chat_widget WHERE admin_id='$user_id' AND id='$widget_id'";  		
		$get_qry = "SELECT chat_widget.*,chat_scheduler_main.day1_opening_time,chat_scheduler_main.day1_close_time,chat_scheduler_main.day2_opening_time,chat_scheduler_main.day2_close_time,chat_scheduler_main.day3_opening_time,chat_scheduler_main.day3_close_time,chat_scheduler_main.day4_opening_time,chat_scheduler_main.day4_close_time,chat_scheduler_main.day5_opening_time,chat_scheduler_main.day5_close_time,chat_scheduler_main.day6_opening_time,chat_scheduler_main.day6_close_time,chat_scheduler_main.day7_opening_time,chat_scheduler_main.day7_close_time,chat_scheduler_sub.day1_opening_time_s,chat_scheduler_sub.day1_close_time_s,chat_scheduler_sub.day2_opening_time_s,chat_scheduler_sub.day2_close_time_s,chat_scheduler_sub.day3_opening_time_s,chat_scheduler_sub.day3_close_time_s,chat_scheduler_sub.day4_opening_time_s,chat_scheduler_sub.day4_close_time_s,chat_scheduler_sub.day5_opening_time_s,chat_scheduler_sub.day5_close_time_s,chat_scheduler_sub.day6_opening_time_s,chat_scheduler_sub.day6_close_time_s,chat_scheduler_sub.day7_opening_time_s,chat_scheduler_sub.day7_close_time_s FROM chat_widget LEFT JOIN chat_scheduler_main ON chat_scheduler_main.widget_id=chat_widget.id LEFT JOIN chat_scheduler_sub ON chat_scheduler_sub.widget_id=chat_widget.id WHERE chat_widget.admin_id='$user_id' AND chat_widget.id='$widget_id'";
		$result = $this->fetchData($get_qry,array());
		return $result;
   }
   public function widget_advanced_settings($chat_data){
      extract($chat_data);//print_r($chat_data);exit; 
      if($attention_grabber == 1 || $attention_grabber == true){ $attention_grabbers = 1; }else{ $attention_grabbers = 0; }     
	   $qry = "UPDATE chat_widget SET widget_appearance='$widget_appearance',widget_position='$widget_position',attention_grabber='$attention_grabbers',mobile_widget='$mobile_widget' WHERE id='$widget_id' AND admin_id='$user_id'";
	   $parms = array();
	   $results = $this->db_query($qry,$parms);      
	   $output = $results == 1 ? 1 : 0;	   
       return $output;
   }
   public function chat_image_upload($chat_data){
	   //print_r($chat_data);exit;
      $user_id = $chat_data['img_user_id'];
      $widget_id = $chat_data['widget_id']; 
      $image_id = $chat_data['image_id'];                   
      if(isset($_FILES["chat_image"]))
        {
          $chat_image_info = getimagesize($_FILES["chat_image"]["tmp_name"]);
          $chat_image_width = $chat_image_info[0];
          $chat_image_height = $chat_image_info[1];
          $allowed_chat_image_extension = array("png","jpg","jpeg");
          $chat_file_extension = pathinfo($_FILES["chat_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($chat_file_extension, $allowed_chat_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                ); 
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
		  else{               
			  $destination_path = getcwd().DIRECTORY_SEPARATOR;            
			  $chat_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api-gdex/v1.0/chat_image/". basename( $_FILES["chat_image"]["name"]);  
			  $chat_image_target_path = $destination_path."chat_image/". basename( $_FILES["chat_image"]["name"]);
			  move_uploaded_file($_FILES['chat_image']['tmp_name'], $chat_image_target_path);			 			  
			  $qry = "INSERT INTO chat_widget_image(widget_id,admin_id,chat_image) VALUES ('$widget_id','$user_id', '$chat_image_upload_path')";
			  $results = $this->db_query($qry, array()); 			  
			  $result = array( "status" => "true" ); 
              $tarray = json_encode($result);  
              print_r($tarray);exit;
	      }
       }
	   else{
           $qry_result = "UPDATE chat_widget SET image_id='$image_id' WHERE id='$widget_id' AND admin_id='$user_id'";
           $results = $this->db_query($qry_result, array()); 
           //return $result;
		   $result = array( "status" => "true" ); 
           $tarray = json_encode($result);  
           print_r($tarray);exit;
       }
   }
   
   public function chat_image_list($chat_data){
    extract($chat_data); 
    $get_upload_qry = "SELECT * FROM chat_widget_image WHERE admin_id='$user_id'";    
    //$result = $this->dataFetchAll($get_qry,array());
    $get_default_qry = "SELECT * FROM chat_widget_image WHERE admin_id=0 AND widget_id=0";
    $result["upload_images"] = $this->dataFetchAll($get_upload_qry, array());
    $result["default_images"] = $this->dataFetchAll($get_default_qry, array());
    return $result;
   }	
   public function update_chat_image($chat_data){
      extract($chat_data);           
      $qry_result = $this->db_query("UPDATE chat_widget SET image_id='$image_id' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
   }
   public function add_consent_form_option($chat_data){
      extract($chat_data);      
      $get_qry = "SELECT * FROM consent_form_option WHERE admin_id='$user_id' AND widget_id='$widget_id' AND display_option_value='$display_option_value'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = 0;
    return $result;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO consent_form_option(admin_id,widget_id,display_option_value) VALUES ( '$user_id','$widget_id','$display_option_value')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
   }
   public function edit_consent_form_option($chat_data){
      extract($chat_data);              
      $qry = "select * from consent_form_option where id='$id'"; 
      $result = $this->fetchData($qry, array()); 
      return $result;      
   }
   public function update_consent_form_option($chat_data){
      extract($chat_data);              
      $qry_result = $this->db_query("UPDATE consent_form_option SET display_option_value='$display_option_value' WHERE id='$id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
   }
   public function delete_consent_form_option($chat_data){
      extract($chat_data);	  
      $qry = "DELETE FROM consent_form_option WHERE id='$id'";
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function consent_form_option_list($chat_data){
     extract($chat_data); 
     $get_qry = "SELECT * FROM consent_form_option WHERE admin_id='$user_id'";    
     $result = $this->dataFetchAll($get_qry,array());
     return $result;
   }
   public function update_consent_form_data($chat_data){
      extract($chat_data); 	              
      $qry_result = $this->db_query("UPDATE chat_widget SET consent_form='$id',consent_message='$consent_message',privacy_policy_link='$privacy_policy_link',privacy_policy_text='$privacy_policy_text',opt_in_button='$opt_in_button',opt_out_button='$opt_out_button' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
   }
   public function choose_consent_form_option($chat_data){
      extract($chat_data);              
      $qry_result = $this->db_query("UPDATE chat_widget SET consent_form='$id' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;
   }
   public function country_restriction($chat_data){
      extract($chat_data);
      if($has_restriction == 1 || $has_restriction == true){ $has_restriction = 1; }else{ $has_restriction = 0; }         
      $qry_result = $this->db_query("UPDATE chat_widget SET country_restriction='$has_restriction',option_value='$option_value',countries='$countries' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }
  public function chat_timezone($chat_data){
      extract($chat_data);
	  //print_r($chat_data);exit;	  
      if($chat_aviator == 1 || $chat_aviator == true ){ $chat_aviator = 1; }else{ $chat_aviator = 0; }
      if($chat_agent_name == 1 || $chat_agent_name == true){ $chat_agent_name = 1; }else{ $chat_agent_name = 0; } 
	  if($has_department == 1 || $has_department == true){ $has_department = 1; }else{ $has_department = 0; }
	  //echo "UPDATE chat_widget SET offline_email='$offline_email',chat_aviator='$chat_aviator',chat_agent_name='$chat_agent_name',main_timeZone='$main_timeZone',has_department='$has_department' WHERE id='$widget_id' AND admin_id='$user_id'";exit;
      $qry_result = $this->db_query("UPDATE chat_widget SET offline_email='$offline_email',chat_aviator='$chat_aviator',chat_agent_name='$chat_agent_name',main_timeZone='$main_timeZone',has_department='$has_department' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }
  
	/*public function get_single_widget_data($chat_data){
    $user_id = base64_decode($chat_data['user_id']);
    $widget_name = base64_decode($chat_data['widget_name']);
    $explode = explode(':', $chat_data['chat_time']);
    $first_value = $explode[0];
    $second_value = $explode[1];
    if($first_value < 10){
        $first_value = "0".$first_value;
        $chat_time_value = $first_value.":".$second_value;
    }else{
        $chat_time_value = $chat_data['chat_time'];
    }
    $get_qry = "SELECT * FROM chat_widget WHERE admin_id='$user_id' AND widget_name='$widget_name'"; 
    $user_details = $this->fetchData($get_qry,array());
    $country = $chat_data['client_country'];
	$dep = $user_details['departments'];
    $explode_dep = explode(',', $dep);
    $cnt = count($explode_dep);
    for($i=0;$i<$cnt;$i++){
      $dept_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$user_id' AND dept_id='$explode_dep[$i]' AND status=1 AND delete_status=0";
      $dept_qrys[] = $this->fetchData($dept_qry,array());
    }		
    
    if($country=='IN'){
     $office_in_time = $user_details['office_in_time']; 
     $office_out_time = $user_details['office_out_time'];     
    }else{
      $office_in_time = $user_details['s_office_in_time']; 
      $office_out_time = $user_details['s_office_out_time'];
    }
    if($chat_time_value < $office_in_time && $chat_time_value > $office_out_time){  
        $available_status = 0;         
    }
    elseif($chat_time_value > $office_out_time){
        $available_status = 0;       
    }
    elseif($chat_time_value < $office_in_time){
        $available_status = 0;       
    }
    else{
        $available_status = 1;        
    }
	$country_restriction_value = $user_details['option_value'];    
    if($country_restriction_value=='Show widget only to visitors of the following countries'){      
      $qry = "SELECT * FROM chat_widget WHERE (find_in_set('$client_country', countries)) AND `widget_name`='$widget_name'";
      $qry_result = $this->db_query($qry, array());
      if($qry_result > 0){
        $country_status = 1;
      }
      else{
        $country_status = 0;
      }
	}else{
	    $qry = "SELECT * FROM chat_widget WHERE (find_in_set('$client_country', countries)) AND `widget_name`='$widget_name'";
      $qry_result = $this->db_query($qry, array());
      if($qry_result > 0){
        $country_status = 1;
      }
      else{
        $country_status = 0;
      }    
	}			
	$get_qry = "SELECT chat_widget.*,chat_widget_image.id as chat_image_id,chat_widget_image.chat_image,chat_sound.id as chat_sound_id,chat_sound.sound_file_path FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id LEFT JOIN chat_sound ON chat_sound.id=chat_widget.chat_sound WHERE chat_widget.widget_name='$widget_name' AND chat_widget.admin_id='$user_id'";	
    $result['widget_data'] = $this->fetchdata($get_qry,array());
	//$dept_qry = "SELECT * FROM departments WHERE admin_id='$user_id' AND status=1 AND delete_status=0";
    //$result["department"] = $this->dataFetchAll($dept_qry,array());$dept_qrys
		$result["department"] = $dept_qrys;
    $result['available_status'] = $available_status;
	$result['country_status'] = $country_status;
    return $result;
   }*/
	public function get_single_widget_data($chat_data){
    $user_id = base64_decode($chat_data['user_id']);
    $widget_name = base64_decode($chat_data['widget_name']);
    $chat_time_value = $chat_data['chat_time'];    
    $country = $chat_data['client_country'];
	$timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country);
    $countryzone = $timezone[0];
    date_default_timezone_set($countryzone);
    //$client_time = date('H:i:s', time());
	$client_time = date('H.i A', time());	
	//echo $client_time;exit;	
	$client_str_time = strtotime($client_time);
    $get_qry = "SELECT chat_widget.*,chat_scheduler_main.day1_opening_time,chat_scheduler_main.day1_close_time,chat_scheduler_main.day2_opening_time,chat_scheduler_main.day2_close_time,chat_scheduler_main.day3_opening_time,chat_scheduler_main.day3_close_time,chat_scheduler_main.day4_opening_time,chat_scheduler_main.day4_close_time,chat_scheduler_main.day5_opening_time,chat_scheduler_main.day5_close_time,chat_scheduler_main.day6_opening_time,chat_scheduler_main.day6_close_time,chat_scheduler_main.day7_opening_time,chat_scheduler_main.day7_close_time FROM chat_widget LEFT JOIN chat_scheduler_main ON chat_scheduler_main.widget_id = chat_widget.id WHERE chat_widget.admin_id='$user_id' AND chat_widget.widget_name='$widget_name'"; 
    $user_details = $this->fetchData($get_qry,array());//print_r($user_details);	
    $timezone_id = $user_details['main_timeZone'];
    $timezone_qry = "SELECT name FROM timezone WHERE id='$timezone_id'";
    $timezone_qry_value = $this->fetchOne($timezone_qry,array());
    //date_default_timezone_set($user_timezone);	
	date_default_timezone_set($timezone_qry_value);		
    $datevalue = date('Y-m-d H:i:s');		
    $day = date('l', strtotime($datevalue)); 
		//echo $day;exit;
     if($day=='Monday'){
      $opening_time = $user_details['day1_opening_time'];
		 //echo $opening_time;exit;
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day1_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Tuesday'){
	  $opening_time = $user_details['day2_opening_time'];
		 //echo $opening_time;exit;
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day2_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Wednesday'){
      $opening_time = $user_details['day3_opening_time'];
		 //echo $opening_time;exit;
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day3_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Thursday'){
      $opening_time = $user_details['day4_opening_time'];		 
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day4_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Friday'){
      $opening_time = $user_details['day5_opening_time'];		 
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day5_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Saturday'){
      $opening_time = $user_details['day6_opening_time'];
		 //echo $opening_time;exit;
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day6_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }
     if($day=='Sunday'){
      $opening_time = $user_details['day7_opening_time'];
		 //echo $opening_time;exit;
      $opening_str_time = strtotime($opening_time);	 
      $close_time = $user_details['day7_close_time'];
      $close_str_time = strtotime($close_time);	  	 
	  date_default_timezone_set($timezone_qry_value);
	  $current_time = date('H:i', time());
	  $current_str_time = strtotime($current_time);
	  //echo $current_str_time;exit; 	 
	  //if($close_str_time > $current_str_time){
	  if($opening_str_time < $current_str_time && $close_str_time > $current_str_time){	  
        $available_status = 1;
      }else{
        $available_status = 0;
      }
     }   

  $country_restriction_value = $user_details['option_value'];    
    if($country_restriction_value=='Show widget only to visitors of the following countries'){      
      $qry = "SELECT * FROM chat_widget WHERE (find_in_set('$client_country', countries)) AND `widget_name`='$widget_name'";
      $qry_result = $this->db_query($qry, array());
      if($qry_result > 0){
        $country_status = 1;
      }
      else{
        $country_status = 0;
      }
  }else{
      $qry = "SELECT * FROM chat_widget WHERE (find_in_set('$client_country', countries)) AND `widget_name`='$widget_name'";
      $qry_result = $this->db_query($qry, array());      
      if($qry_result > 0){
        $country_status = 1;
      }
      else{
        $country_status = 0;
      }    
  }
   $get_qry = "SELECT chat_widget.*,chat_widget_image.id as chat_image_id,chat_widget_image.chat_image,chat_scheduler_main.day1_close_time,chat_scheduler_main.day2_close_time,chat_scheduler_main.day3_close_time,chat_scheduler_main.day4_close_time,chat_scheduler_main.day5_close_time,chat_scheduler_main.day6_close_time,chat_scheduler_main.day7_close_time,chat_scheduler_main.day1_opening_time,chat_scheduler_main.day2_opening_time,chat_scheduler_main.day3_opening_time,chat_scheduler_main.day4_opening_time,chat_scheduler_main.day5_opening_time,chat_scheduler_main.day6_opening_time,chat_scheduler_main.day7_opening_time FROM chat_widget LEFT JOIN chat_widget_image ON chat_widget_image.id=chat_widget.image_id LEFT JOIN chat_scheduler_main ON chat_scheduler_main.widget_id = chat_widget.id WHERE chat_widget.widget_name='$widget_name' AND chat_widget.admin_id='$user_id'";  
    $result['widget_data'] = $this->fetchdata($get_qry,array());
		$depart=$result['widget_data']['departments'];
		$depart="'" . str_replace(",", "','", $depart) . "'";
		$dept_user=$this->fetchOne("select GROUP_CONCAT(DISTINCT(department_users)) as dep_users from departments where dept_id IN ($depart)",array());
		$enable_dept = $this->dataFetchAll("select * from departments where dept_id IN ($depart)", array());
		$enable_depts=array();
		foreach ($enable_dept as $new_enable_value) {  			
				$users_value =$new_enable_value['department_users'];
				$get_ext_no = $this->fetchOne("select GROUP_CONCAT(sip_login) as ext_no from user where user_id in ($users_value)",array());
				 $new_enable_value["get_ext_no"] = $get_ext_no;
				array_push($enable_depts,$new_enable_value);
		}
  $dept_qry = "SELECT * FROM departments WHERE admin_id='$user_id' AND status=1 AND delete_status=0";
	$deptment=$this->dataFetchAll($dept_qry,array());
    $result["department"] = $deptment;
    $result['available_status'] = $available_status;
    $result['country_status'] = $country_status;
    $result['department_users'] = $dept_user;
    $result['enable_dept'] = $enable_depts;
	$get_sound_qry = "SELECT chat_sound FROM chat_widget WHERE widget_name='$widget_name' AND admin_id='$user_id'";  
    $sound = $this->fetchOne($get_sound_qry,array());
	if($sound == 1){
	  $snd = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_sound/sound_1.mp3";
	}
	if($sound == 2){
	  $snd = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_sound/sound_2.mp3";
	}
	if($sound == 3){
	  $snd = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/chat_sound/sound_3.mp3";
	}
	$result['sound_file_path'] = $snd;
		$chat_detail_qry = "select offline_chat from overall_chat_settings where admin_id ='$user_id'";
	$offline_chat = $this->fetchOne($chat_detail_qry,array());	
		$result['offline_chat'] = $offline_chat;
    return $result;
   }
	public function delete_widget_data($data){
      extract($data);
      $qry = "DELETE FROM chat_widget WHERE id='$widget_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function list_country_code(){    
     $get_qry = "SELECT * FROM apps_countries";    
     $result = $this->dataFetchAll($get_qry,array());
     return $result;
   }
  public function get_chat_data($chat_data){
    extract($chat_data); 
	   $qry = "SELECT widget_name FROM `chat` WHERE chat_id='$chat_id'";
    $widget_name = $this->fetchOne($qry, array());
	//$chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value, chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.msg_status, customer.customer_name,customer_email,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.chat_aviator,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' and chat.admin_id = '$user_id' order by chat_msg.chat_msg_id asc";
	$chat_detail_qry = "select chat.chat_id, chat.chat_user, chat.chat_type, chat.chat_status, chat.rating_value,chat_msg.extension,chat_msg.chat_msg_id, chat_msg.msg_user_id, chat_msg.msg_user_type, chat_msg.msg_type, chat_msg.chat_msg, chat_msg.chat_images, chat_msg.msg_status, customer.customer_name,customer_email,customer.city,customer.country,customer.created_ip, TIME_FORMAT(chat_msg.created_dt, '%H:%i') as chat_time, date_format(chat_msg.created_dt, '%d/%m/%Y') as chat_dt, user.user_name,user.agent_name,user.chat_aviator,user.profile_image from chat inner join chat_msg on chat_msg.chat_id=chat.chat_id left join customer on chat.chat_user = customer.customer_id left join user on chat_msg.msg_user_id = user.user_id where chat.chat_id = '$chat_id' and chat.widget_name = '$widget_name' and chat.admin_id = '$user_id' order by chat_msg.chat_msg_id asc";  
    $result = $this->dataFetchAll($chat_detail_qry,array());
	
	$result_count =  $this->dataRowCount($chat_detail_qry,array());
	if($result_count > 0){
		  $chat_msg_id = $result[0]['chat_msg_id'];
		  $customer_id = $result[0]['customer_id'];
		  $customer_code = $result[0]['customer_code'];
		  $customer_name = $result[0]['customer_name'];
		  $customer_email = $result[0]['customer_email'];
		  $results = '<input type="hidden" id="chat_id" value="'.$chat_id.'"><input type="hidden" id="chat_msg_id" value="'.$chat_msg_id.'"><input type="hidden" id="queue_id" value="0"><input type="hidden" id="customer_id" value="'.$customer_id.'"><input type="hidden" id="customer_web_code" value="'.$customer_code.'">
		<input type="hidden" id="customer_name" value="'.$customer_name.'">
		<input type="hidden" id="customer_email" value="'.$customer_email.'">
		<div class="chat-body chat-field-panel" id="messages">
		<h6>Live Chat By "'.$customer_name.'"</h6>';
		  foreach($result as $data){
			 $customer_names = $data['customer_name'];
			 $chat_msg = $data['chat_msg'];
			  $chat_extension = $data['extension'];
			 $profile_image = $data['profile_image'];
			 $user_name = $data['user_name'];
			 $agents_name = $data['agent_name']; 
			 $split_agent_name = explode(" ", $agents_name);
			 $msg_user_id = $data['msg_user_id'];   
			   $chat_images = $data['chat_images'];   
				$msg_user_type = $data['msg_user_type'];
//			 if($msg_user_id == $user_id){
			if($msg_user_type == '2' || $msg_user_type == '4'){
				 
        if($chat_images == ''){
        $results .= '<div class="agent-msg msg-row"><img  class="agent-avatar" src="images/user-theme.svg">
        <p>'.$chat_msg.'</p><span></span></div>';
        } else {
		
		//	$results. = '<div class="agent-msg msg-row"><img class="agent-avatar" src="' .$profile_image. '">';
			if ($chat_extension == 'png' || $chat_extension == 'jpeg' || $chat_extension == 'jpg' || $chat_extension == 'gif' || $chat_extension == 'svg' || $chat_extension == 'apng'||$chat_extension == 'bmp'){
				 
				// $results .= '<div class="agent-msg msg-row"><img class="agent-avatar" src="' .$profile_image. '"><p style="max-width:73px;" id="main">'.$chat_msg.'<a target="_blank" href="' .$chat_images.'"><img class="chat_image" style="width: 100%;height: 100%;" src='.$split_agent_name. '></a></p><span class="agent_name">' .$split_agent_name. '</span></div>';
				
				 $results .= '<div class="agent-msg msg-row"><img class="agent-avatar" src="'.$profile_image.'">
            <p style="max-width:75px;"><a target="_blank" href="'.$chat_images.'"><img class="chat_image" style="width: 100% !important;height: 100% !important;" src="'.$chat_images.'"></a>'.$chat_msg.'</p><span class="agent_name">'.$split_agent_name[0].'</span>
            </div>';
			}
          /*  if ($chat_extension != 'doc' && $chat_extension != 'docx' && $chat_extension != 'pdf' && $chat_extension != 'csv' && $chat_extension != 'txt') {
            $results .= '<div class="agent-msg msg-row"><img class="agent-avatar" src="'.$profile_image.'">
            <p>'.$chat_msg.'</p><span class="agent_name">'.$split_agent_name[0].'</span>
            </div>';
            } */
			else {

            $results .= '<div class="agent-msg msg-row"><img class="agent-avatar" src="'.$profile_image.'">
            <p style="max-width:75px;"><a target="_blank" href="'.$chat_images.'"><img class="chat_image" style="width: 100% !important;height: 100% !important;" src="images/quickView.png"></a>'.$chat_msg.'</p><span class="agent_name">'.$split_agent_name[0].'</span>
            </div>';
            }
        }
} else {
				//echo $chat_extension;exit;
        if($chat_images == ''){
        $results .= '<div class="customer-msg msg-row"><img src="images/user-theme.svg">
        <p>'.$chat_msg.'</p><span>You</span></div>';
        } else {
			if ($chat_extension == 'png' || $chat_extension == 'jpeg' || $chat_extension == 'jpg' || $chat_extension == 'gif' || $chat_extension == 'svg' || $chat_extension == 'apng'||$chat_extension == 'bmp'){

				 $results .= '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;">
				 <a target="_blank" href="' .$chat_images.'"><img class="paragraph-img" src="'.$chat_images.'"></a>
				 </p><span>You</span></div>';
				
			}
			/*
            if ($chat_extension != 'doc' && $chat_extension != 'docx' && $chat_extension != 'pdf' && $chat_extension != 'csv' && $chat_extension != 'txt') {
            $results .= '<div class="customer-msg msg-row 3"><img src="images/user-theme.svg">
            <p style="max-width:75px;"><img class="paragraph-img" src="'.$chat_images.'">'.$chat_msg.'</p><span>You</span>
            </div>';
            } 
			*/
			else{
				 $results .= '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;">
				 <a target="_blank" href="' .$chat_images.'"><img class="paragraph-img" src="images/quickView.png"></a>
				 </p><span>You</span></div>';
            //$results .= '<div class="customer-msg msg-row 3"><img src="images/user-theme.svg"><p style="max-width:75px;"><img class="paragraph-img" src="'.$chat_images.'">'.$chat_msg.'</p><span>You</span></div>';
            }
        }

    }	
}	
			 
}	 else{
        $results = 0;
        
      }
	   //print_r($results); exit;	
	   return $results;
   }
   public function get_chat_sound(){    
     $get_qry = "SELECT * FROM chat_sound";    
     $result = $this->dataFetchAll($get_qry,array());
     return $result;
   }
   public function dept_agent_list($chat_data){ 
	   extract($chat_data);
     $get_qry = "SELECT dept_id,department_name FROM departments WHERE admin_id='$admin_id' AND delete_status=0";    
     $result['department_list'] = $this->dataFetchAll($get_qry,array());
     $get_qry = "SELECT user_id,agent_name,profile_image FROM user WHERE admin_id='$admin_id' AND user_status=1 AND delete_status=0";    
     $result['agent_list'] = $this->dataFetchAll($get_qry,array());
     return $result;
   }
   public function update_chat_sound($chat_data){
      extract($chat_data);              
      $qry_result = $this->db_query("UPDATE chat_widget SET chat_sound='$sound_id' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }
	public function user_restriction($chat_data){
      extract($chat_data);
		//print_r($chat_data);exit;               
      $qry_result = $this->db_query("UPDATE chat_widget SET departments='$departments',agents='$agents' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }

  public function update_activity_time($chat_data){
      extract($chat_data);              
      $qry_result = $this->db_query("UPDATE chat_widget SET widget_activity_time='$widget_activity_time' WHERE id='$widget_id' AND admin_id='$user_id'", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;      
  }

  public function add_chat_scheduler_main($chat_data){
      extract($chat_data);
      //$get_qry = "SELECT * FROM chat_scheduler_main WHERE admin_id='$user_id' AND widget_id='$widget_id'";
	  $get_qry = "SELECT * FROM chat_scheduler_main WHERE widget_id='$widget_id'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $qry_result = $this->db_query("UPDATE chat_scheduler_main SET day1_opening_time='$day1_opening_time',day1_close_time='$day1_close_time',day2_opening_time='$day2_opening_time',day2_close_time='$day2_close_time',day3_opening_time='$day3_opening_time',day3_close_time='$day3_close_time',day4_opening_time='$day4_opening_time',day4_close_time='$day4_close_time',day5_opening_time='$day5_opening_time',day5_close_time='$day5_close_time',day6_opening_time='$day6_opening_time',day6_close_time='$day6_close_time',day7_opening_time='$day7_opening_time',day7_close_time='$day7_close_time' WHERE admin_id='$user_id' AND widget_id='$widget_id'", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO chat_scheduler_main(admin_id,widget_id,day1_opening_time,day1_close_time,day2_opening_time,day2_close_time,day3_opening_time,day3_close_time,day4_opening_time,day4_close_time,day5_opening_time,day5_close_time,day6_opening_time,day6_close_time,day7_opening_time,day7_close_time) VALUES ( '$user_id','$widget_id','$day1_opening_time','$day1_close_time','$day2_opening_time','$day2_close_time','$day3_opening_time','$day3_close_time','$day4_opening_time','$day4_close_time','$day5_opening_time','$day5_close_time','$day6_opening_time','$day6_close_time','$day7_opening_time','$day7_close_time')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
    }

    public function add_chat_scheduler_sub($chat_data){
      extract($chat_data);
      $get_qry = "SELECT * FROM chat_scheduler_sub WHERE admin_id='$user_id' AND widget_id='$widget_id'";   
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $qry_result = $this->db_query("UPDATE chat_scheduler_sub SET day1_opening_time_s='$day1_opening_time_s',day1_close_time_s='$day1_close_time_s',day2_opening_time_s='$day2_opening_time_s',day2_close_time_s='$day2_close_time_s',day3_opening_time_s='$day3_opening_time_s',day3_close_time_s='$day3_close_time_s',day4_opening_time_s='$day4_opening_time_s',day4_close_time_s='$day4_close_time_s',day5_opening_time_s='$day5_opening_time_s',day5_close_time_s='$day5_close_time_s',day6_opening_time_s='$day6_opening_time_s',day6_close_time_s='$day6_close_time_s',day7_opening_time_s='$day7_opening_time_s',day7_close_time_s='$day7_close_time_s' WHERE admin_id='$user_id' AND widget_id='$widget_id'", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO chat_scheduler_sub(admin_id,widget_id,day1_opening_time_s,day1_close_time_s,day2_opening_time_s,day2_close_time_s,day3_opening_time_s,day3_close_time_s,day4_opening_time_s,day4_close_time_s,day5_opening_time_s,day5_close_time_s,day6_opening_time_s,day6_close_time_s,day7_opening_time_s,day7_close_time_s) VALUES ( '$admin_id','$widget_id','$day1_opening_time_s','$day1_close_time_s','$day2_opening_time_s','$day2_close_time_s','$day3_opening_time_s','$day3_close_time_s','$day4_opening_time_s','$day4_close_time_s','$day5_opening_time_s','$day5_close_time_s','$day6_opening_time_s','$day6_close_time_s','$day7_opening_time_s','$day7_close_time_s')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
    }
}

