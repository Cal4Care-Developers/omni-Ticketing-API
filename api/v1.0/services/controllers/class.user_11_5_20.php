<?php
class userData extends restApi{
    
        public function userLoginValidation($user_name, $password){
            
            $userAuth = new userAuth();
            $password_encode = $userAuth->userPwdEncode($password);
            
           $qry = "select user.user_id,user.admin_id,user.user_name,user.delete_status, user_type.user_type_name as userType, user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status, user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.logo_image,user.small_logo_image,user.ext_int_status,user.layout,user.theme,user.timezone_id,user.lead_token, admin_details.wallboard_one_isactive, admin_details.wallboard_two_isactive,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive from user left join user_type on user.user_type = user_type.user_type_id left join admin_details on admin_details.admin_id = user.user_id where user_name=:user_name and user_pwd=:user_pwd";

            $result = $this->fetchData($qry, array("user_name"=>$user_name, "user_pwd"=>$password_encode));
            
            if($result == null){
                return $result;
            }
            else{
                $user_id = $result["user_id"];
                $ip = $this->getClientIP();
                $this->db_query("INSERT INTO user_login(user_id, user_method, created_ip) VALUES ('$user_id','user','$ip')", array());
            }

            return $result;
                 
        }
    
        
	
	
	
    


	
	
	public function createUser($agent_data){ 
		  extract($agent_data);		 
		  //print_r($agent_data);exit;
          $user_timezone_id_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
          $timezone_id = $this->fetchOne($user_timezone_id_qry,array());		
          $qry_result = $this->db_query("INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password) VALUES ('$agent_name','$phone_number','$email_id',$status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password')", array());		
           $result = $qry_result == 1 ? 1 : 0;
		   if($qry_result != 0){
			    $last_id_qry = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
                $last_id = $this->fetchOne($last_id_qry,array());
                $result = $this->getUserData($last_id);                
            }
            else{                
                $result = 0;
            }
           return $result;            
        }
    
public  function update_agent($user_id,$agent_name,$email_id,$phone_number,$user_name,$sip_login,$sip_username,$sip_password){  
  
	$user_qry = "UPDATE user SET agent_name='$agent_name',email_id='$email_id',phone_number='$phone_number',user_name='$user_name',sip_login='$sip_login',sip_username='$sip_username',sip_password='$sip_password' where user_id='$user_id'";
  $userqry_result = $this->db_query($user_qry, array());  
  $result = $userqry_result == 1 ? 1 : 0;
  return $result;    
}

public function updateUser($data, $where_arr){ 	
  $user_id = $data['img_user_id'];
  $user_type_qry = "SELECT user_type FROM user WHERE user_id='$user_id'";
  $user_type = $this->fetchmydata($user_type_qry,array());
  // profile image code starts here
  if(isset($_FILES["profile_image"]))
    {
	  //echo "isset";exit;
      $profile_image_info = getimagesize($_FILES["profile_image"]["tmp_name"]);
      $profile_image_width = $profile_image_info[0];
      $profile_image_height = $profile_image_info[1];
      $allowed_image_extension = array("png","jpg","jpeg");
      $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);   
      if (! in_array($file_extension, $allowed_image_extension)) {echo "if";exit;
            $result = array( 
                "status" => "false",          
                "message" => "Please upload PNG and JPEG Format Image."
            );
		    $tarray = json_encode($result);  
            print_r($tarray);exit;
      }
      else if ($profile_image_width > 100 && $profile_image_height > 100) {
            $result = array(
                "status" => "false",
                "message" => "Image width or height exceeds"
            );
			$tarray = json_encode($result);  
            print_r($tarray);exit;
      }else{
        $destination_path = getcwd().DIRECTORY_SEPARATOR;            
        $profile_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/profile_image/". basename( $_FILES["profile_image"]["name"]);  
        $profile_target_path = $destination_path."profile_image/". basename( $_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_target_path);        
      }
    }else{      
      $profile_image_qry = "SELECT profile_image FROM user WHERE user_id='$user_id'";
      $profile_image_upload_path = $this->fetchmydata($profile_image_qry,array());
    }	
    // profile image code ends here
    $agent_data = array("user_name"=>$data['user_name'],"agent_name"=>$data['agent_name'], "sip_login"=>$data['sip_login'],"sip_username"=>$data['sip_username'],"sip_password"=>$data['sip_password'],"email_id"=>$data['email_id'],"phone_number"=>$data['phone_number'],"profile_image"=>$profile_image_upload_path);        
    $qry = $this->generateUpdateQry($agent_data, "user");
    $qry .= "user_id=:user_id";
    $params = array_merge($agent_data,$where_arr);
    $update_data = $this->db_query($qry, $params);    
    $result = array("status" => "true");          
    $tarray = json_encode($result);  
    print_r($tarray);exit;         
}
	
/*public function admin_global_settings($data, $where_arr){   
    $user_id = $data['img_user_id'];
    // Logo Images code starts here    
        if(isset($_FILES["logo_image"]))
        {
          $logo_image_info = getimagesize($_FILES["logo_image"]["tmp_name"]);
          $logo_image_width = $logo_image_info[0];
          $logo_image_height = $logo_image_info[1];
          $allowed_logo_image_extension = array("png","jpg","jpeg");
          $logo_file_extension = pathinfo($_FILES["logo_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($logo_file_extension, $allowed_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                ); 
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          elseif ($logo_image_width > 250 && $logo_image_height > 68) {
                $result = array(
                    "status" => "false",
                    "message" => "Logo Image width or height exceeds"
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/logo_image/". basename( $_FILES["logo_image"]["name"]);  
            $logo_target_path = $destination_path."logo_image/". basename( $_FILES["logo_image"]["name"]);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logo_target_path);        
          }
        }
        else
        {
          $logo_image_qry = "SELECT logo_image FROM user WHERE user_id='$user_id'";
          $logo_image_upload_path = $this->fetchmydata($logo_image_qry,array());          
        }         
        if(isset($_FILES["small_logo_image"]))
        {
          $small_logo_image_info = getimagesize($_FILES["small_logo_image"]["tmp_name"]);
          $small_logo_image_width = $small_logo_image_info[0];
          $small_logo_image_height = $small_logo_image_info[1];
          $allowed_small_logo_image_extension = array("png","jpg","jpeg");
          $small_logo_file_extension = pathinfo($_FILES["small_logo_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($small_logo_file_extension, $allowed_small_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          else if ($small_logo_image_width > 65 && $small_logo_image_height > 65) {
                $result = array(
                    "status" => "false",
                    "message" => "Small Logo Image width or height exceeds"
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $small_logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/small_logo_image/". basename( $_FILES["small_logo_image"]["name"]);  
            $small_logo_target_path = $destination_path."small_logo_image/". basename( $_FILES["small_logo_image"]["name"]);
            move_uploaded_file($_FILES['small_logo_image']['tmp_name'], $small_logo_target_path);        
          }
        }
        else
        {          
          $small_logo_image_qry = "SELECT small_logo_image FROM user WHERE user_id='$user_id'";
          $small_logo_image_upload_path = $this->fetchmydata($small_logo_image_qry,array());
        }        
    // Logo Images code ends here
    $agent_data = array("timezone_id"=>$data['timezone_id'],"ext_int_status"=>$data['ext_int_status'],"logo_image"=>$logo_image_upload_path,"small_logo_image"=>$small_logo_image_upload_path);   
    $qry = $this->generateUpdateQry($agent_data, "user");
    $qry .= "user_id=:user_id";
    $params = array_merge($agent_data,$where_arr);
    $update_data = $this->db_query($qry, $params);    
    $result = array("status" => "true");          
    $tarray = json_encode($result);  
    print_r($tarray);exit;         
}*/
	
public function admin_global_settings($data, $where_arr){   
	//print_r($data);exit;
    $user_id = $data['img_user_id'];
    $timezone_id = $data['timezone_id'];
	$ext_int_status = $data['ext_int_status'];
    // Logo Images code starts here    
        if(isset($_FILES["logo_image"]))
        {
          $logo_image_info = getimagesize($_FILES["logo_image"]["tmp_name"]);
          $logo_image_width = $logo_image_info[0];
          $logo_image_height = $logo_image_info[1];
          $allowed_logo_image_extension = array("png","jpg","jpeg");
          $logo_file_extension = pathinfo($_FILES["logo_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($logo_file_extension, $allowed_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                ); 
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          /*elseif ($logo_image_width > 250 && $logo_image_height > 68) {
                $result = array(
                    "status" => "false",
                    "message" => "Logo Image width or height exceeds"
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }*/
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/logo_image/". basename( $_FILES["logo_image"]["name"]);  
            $logo_target_path = $destination_path."logo_image/". basename( $_FILES["logo_image"]["name"]);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logo_target_path);        
          }
        }
        else
        {
          $logo_image_qry = "SELECT logo_image FROM user WHERE user_id='$user_id'";
          $logo_image_upload_path = $this->fetchmydata($logo_image_qry,array());          
        }         
        if(isset($_FILES["small_logo_image"]))
        {
          $small_logo_image_info = getimagesize($_FILES["small_logo_image"]["tmp_name"]);
          $small_logo_image_width = $small_logo_image_info[0];
          $small_logo_image_height = $small_logo_image_info[1];
          $allowed_small_logo_image_extension = array("png","jpg","jpeg");
          $small_logo_file_extension = pathinfo($_FILES["small_logo_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($small_logo_file_extension, $allowed_small_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          /*else if ($small_logo_image_width > 65 && $small_logo_image_height > 65) {
                $result = array(
                    "status" => "false",
                    "message" => "Small Logo Image width or height exceeds"
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }*/
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $small_logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/small_logo_image/". basename( $_FILES["small_logo_image"]["name"]);  
            $small_logo_target_path = $destination_path."small_logo_image/". basename( $_FILES["small_logo_image"]["name"]);
            move_uploaded_file($_FILES['small_logo_image']['tmp_name'], $small_logo_target_path);        
          }
        }
        else
        {          
          $small_logo_image_qry = "SELECT small_logo_image FROM user WHERE user_id='$user_id'";
          $small_logo_image_upload_path = $this->fetchmydata($small_logo_image_qry,array());
        }        
    // Logo Images code ends here
    $agent_data = array("timezone_id"=>$data['timezone_id'],"ext_int_status"=>$data['ext_int_status'],"logo_image"=>$logo_image_upload_path,"small_logo_image"=>$small_logo_image_upload_path);  
	//print_r($agent_data);exit;
    $qry_result = $this->db_query("UPDATE user SET timezone_id='$timezone_id',ext_int_status='$ext_int_status',logo_image='$logo_image_upload_path',small_logo_image='$small_logo_image_upload_path' where user_id='$user_id'", array());
    $resultqry = $qry_result == 1 ? 1 : 0; 
     if($resultqry==1){
		 $user_type_qry = "SELECT user_type FROM user WHERE user_id='$user_id'";
        $user_type = $this->fetchOne($user_type_qry,array());
        if($user_type==2){
          $admin_qry_result = $this->db_query("UPDATE admin_details SET ext_int_status='$ext_int_status' where admin_id='$user_id'", array());
        }
        $result = array("status" => "true");          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
     }else{
        $result = array("status" => "false");          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
     }             
}
    
    
        public function getAlluser($data){

            extract($data);//print_r($data);exit;
            $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
            extract($qry_limit_data);

            $search_qry = "";
            if($search_text != ""){
                $search_qry = " and (user.user_name like '%".$search_text."%' or user_type.user_type_name like '%".$search_text."%' or user.email_id like '%".$search_text."%' or user.phone_number like '%".$search_text."%' or status.status_desc like '%".$search_text."%')";
            }
if($admin_id == '1'){
	  $qry = "select user.user_id,user.sip_login,user.user_name,user.agent_name,user.user_type, user_type.user_type_name, user.email_id,user.phone_number,user.user_status,user.has_contact,user.has_sms,user.has_chat,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.layout,user.theme,user.password, status.status_name, status.status_desc from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_type != 1 ".$search_qry; 
} else {
	  $qry = "select user.user_id,user.sip_login,user.user_name,user.agent_name,user.user_type, user_type.user_type_name, user.email_id,user.phone_number,user.user_status,user.has_contact,user.has_sms,user.has_chat,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.layout,user.theme,user.password, status.status_name, status.status_desc from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_type != 1 and user.delete_status != 1 and user.admin_id='$admin_id'".$search_qry; 
}
           
			
		
            $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;	
            $user_permission_qry = "select user_status,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,wallboard_one,wallboard_two,wallboard_three,wallboard_four from user where user_id='$admin_id'";			
            $parms = array();			
            $result["user_permission"] = $this->fetchData($user_permission_qry, array());
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;
        }
    
    
        public function getUserData($id){
            $qry = "select * from user where user_id='$user_id'";
                
            $result = $this->fetchData($qry, array());
           
           if($result['user_type'] == '2'){
               
                $admin_id = $user_id; 
            } else { 
                $admin_id = $result['admin_id'];
            }
            
            $qry = "select user.user_id, user.user_name,user.agent_name,user.user_type,user.email_id,user.phone_number,user.has_contact,user.has_sms,user.has_chat,user.user_status,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.sip_login,user.sip_username,user.sip_password,user.timezone_id,user.profile_image,user.logo_image,user.small_logo_image,user.layout,user.theme,user.password, user_type.user_type_name, status.status_name from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_id='$id'";
              echo $admin_id;
           

                return $this->fetchData($qry, array());
        }
            
        public function getLoginUserData(){
            
           

        }
    
        function updateIncomingCallStatus($incoming_call_status, $user_id){
            
            $qry_result = $this->db_query("UPDATE user SET incoming_call_status='$incoming_call_status' where user_id='$user_id'", array());
            
            $result = $qry_result == 1 ? 1 : 0;

            return $result;
            
        }
    
        public function archiveCustomer($id){

            $qry = "UPDATE user SET user_status ='2' where user_id=:user_id";
            $qry_result = $this->db_query($qry, array("user_id"=>$id));
            
            $result = $qry_result == 1 ? 1 : 0;

            return $result;

        }
    
        private function deleteUser($id){

            $qry = "Delete from user where user_id=:user_id";
            $qry_result = $this->db_query($qry, array("user_id"=>$id));
                
            $result = $qry_result == 1 ? 1 : 0;

            return $result;

        }
    
  public function getAdminData(){            
           //$qry = "SELECT * FROM `admin_details`";
	       $qry = "SELECT admin_details.id,admin_details.name,admin_details.admin_id,admin_details.pbx_count,admin_details.pbx_id,admin_details.agent_counts,admin_details.created_dt,admin_details.updated_dt,admin_details.has_contact,admin_details.has_sms,admin_details.has_chat,admin_details.ext_int_status,admin_details.whatsapp_num,admin_details.admin_status,admin_details.has_whatsapp,admin_details.has_chatbot,admin_details.has_fb,admin_details.has_wechat,admin_details.has_telegram,admin_details.has_internal_ticket,admin_details.has_external_ticket,admin_details.voice_3cx,admin_details.predective_dialer,admin_details.lead,admin_details.wallboard_one,admin_details.wallboard_two,admin_details.wallboard_three,admin_details.wallboard_four,admin_details.delete_status, user.password FROM `admin_details` LEFT JOIN user on admin_details.admin_id = user.user_id WHERE admin_details.delete_status = 0";
	       return $this->dataFetchAll($qry, array());
        }
	
	
	  public function getsingleAdminData($id){
            
            $qry = "SELECT * FROM `admin_details` WHERE id = '$id'";
              
                return $this->dataFetchAll($qry, array());
        }
	
public  function EditSingleAdminsettings($admin_name,$pbx_count,$agent_count,$id,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four){
  //echo $id;exit;
  $admin_id_qry = "SELECT admin_id FROM admin_details WHERE id='$id'";
  $admin_id = $this->fetchOne($admin_id_qry,array());
  if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }
  if($has_sms == 1 || $has_sms == true){ $has_smss = 1; }else{ $has_smss = 0; }
  if($has_chat == 1 || $has_chat == true){ $has_chats = 1; }else{ $has_chats = 0; }
  if($admin_status == 1 || $admin_status == true){ $admin_statuss = 1; }else{ $admin_statuss = 0; }
  if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapps = 1; }else{ $has_whatsapps = 0; } 
  if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbots = 1; }else{ $has_chatbots = 0; }
  if($has_fb == 1 || $has_fb == true){ $has_fbs = 1; }else{ $has_fbs = 0; }
  if($has_wechat == 1 || $has_wechat == true){ $has_wechats = 1; }else{ $has_wechats = 0; }
  if($has_telegram == 1 || $has_telegram == true){ $has_telegrams = 1; }else{ $has_telegrams = 0; }
  if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_tickets = 1; }else{ $has_internal_tickets = 0; }
  if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_tickets = 1; }else{ $has_external_tickets = 0; }
  if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cxs = 1; }else{ $voice_3cxs = 0; }     
  if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialers = 1; }else{ $predective_dialers = 0; }
  if($lead == 1 || $lead == true){ $leads = 1; }else{ $leads = 0; }
  if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_ones = 1; }else{ $wallboard_ones = 0; }
    if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_twos = 1; }else{ $wallboard_twos = 0; }     
    if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_threes = 1; }else{ $wallboard_threes = 0; }
    if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_fours = 1; }else{ $wallboard_fours = 0; }
  $user_qry = "UPDATE user SET has_contact='$has_contacts',has_sms='$has_smss',has_chat='$has_chats',user_status='$admin_statuss',has_whatsapp='$has_whatsapps',has_chatbot='$has_chatbots',has_fb='$has_fbs',has_wechat='$has_wechats',has_telegram='$has_telegrams',has_internal_ticket='$has_internal_tickets',has_external_ticket='$has_external_tickets',voice_3cx='$voice_3cxs',predective_dialer='$predective_dialers',lead='$leads',wallboard_one='$wallboard_ones',wallboard_two='$wallboard_twos',wallboard_three='$wallboard_threes',wallboard_four='$wallboard_fours' where user_id='$admin_id'";
  $userqry_result = $this->db_query($user_qry, array());
  $qry = "UPDATE admin_details SET name='$admin_name',pbx_count='$pbx_count',agent_counts='$agent_count',has_contact='$has_contacts',has_sms='$has_smss',has_chat='$has_chats',admin_status='$admin_statuss',has_whatsapp='$has_whatsapps',has_chatbot='$has_chatbots',has_fb='$has_fbs',has_wechat='$has_wechats',has_telegram='$has_telegrams',has_internal_ticket='$has_internal_tickets',has_external_ticket='$has_external_tickets',voice_3cx='$voice_3cxs',predective_dialer='$predective_dialers',lead='$leads',wallboard_one='$wallboard_ones',wallboard_two='$wallboard_twos',wallboard_three='$wallboard_threes',wallboard_four='$wallboard_fours' where id='$id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;    
}
public function AddSingleAdmin($name,$pbx_count,$agent_counts,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four){ //echo'123';exit;   
    $password = $user_password;
	$user_password = MD5(trim($user_password));
   $random_number = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
   $lead_token = $name."_".$random_number;	
       $qry = "select * from user where user_name ='$user_name'";
       $qry_result = $this->dataFetchAll($qry, array()); 
	   $result_count = $this->dataRowCount($qry, array());
         if($result_count > 0){
      $result = 3;
      return $result;
     } else {
     }
     if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }
     if($has_sms == 1 || $has_sms == true){ $has_smss = 1; }else{ $has_smss = 0; }
     if($has_chat == 1 || $has_chat == true){ $has_chats = 1; }else{ $has_chats = 0; }       
     if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapps = 1; }else{ $has_whatsapps = 0; }
    if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbots = 1; }else{ $has_chatbots = 0; }
    if($has_fb == 1 || $has_fb == true){ $has_fbs = 1; }else{ $has_fbs = 0; }
    if($has_wechat == 1 || $has_wechat == true){ $has_wechats = 1; }else{ $has_wechats = 0; }
    if($has_telegram == 1 || $has_telegram == true){ $has_telegrams = 1; }else{ $has_telegrams = 0; }
    if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_tickets = 1; }else{ $has_internal_tickets = 0; }
    if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_tickets = 1; }else{ $has_external_tickets = 0; }
	if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cxs = 1; }else{ $voice_3cxs = 0; }     
    if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialers = 1; }else{ $predective_dialers = 0; }
    if($lead == 1 || $lead == true){ $leads = 1; }else{ $leads = 0; }
	if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_ones = 1; }else{ $wallboard_ones = 0; }
    if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_twos = 1; }else{ $wallboard_twos = 0; }     
    if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_threes = 1; }else{ $wallboard_threes = 0; }
    if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_fours = 1; }else{ $wallboard_fours = 0; }
     $qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,has_contact,has_sms,has_chat,user_status,admin_id,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,layout,theme,lead_token,password,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four) VALUES ('$user_name', '$user_password','2','$name','$has_contacts','$has_smss','$has_chats','1','1','$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','dark','black','$lead_token','$password','$voice_3cxs','$predective_dialers','$leads','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours')";
   $qry_result = $this->db_query($qry, array());
    //print_r($qry_result);

   if($qry_result == 1){
      $qry = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
    $result = $this->dataFetchAll($qry, array());
    $result = $result[0];
     $result =  $result['user_id'];$aid =  $result['user_id'];
      if($result){
        if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }
        if($has_sms == 1 || $has_sms == true){ $has_smss = 1; }else{ $has_smss = 0; }
        if($has_chat == 1 || $has_chat == true){ $has_chats = 1; }else{ $has_chats = 0; }        
        if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapps = 1; }else{ $has_whatsapps = 0; }
        if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbots = 1; }else{ $has_chatbots = 0; }
        if($has_fb == 1 || $has_fb == true){ $has_fbs = 1; }else{ $has_fbs = 0; }
        if($has_wechat == 1 || $has_wechat == true){ $has_wechats = 1; }else{ $has_wechats = 0; }
        if($has_telegram == 1 || $has_telegram == true){ $has_telegrams = 1; }else{ $has_telegrams = 0; }
        if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_tickets = 1; }else{ $has_internal_tickets = 0; }
        if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_tickets = 1; }else{ $has_external_tickets = 0; }
        if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cxs = 1; }else{ $voice_3cxs = 0; }     
    if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialers = 1; }else{ $predective_dialers = 0; }
    if($lead == 1 || $lead == true){ $leads = 1; }else{ $leads = 0; }
    if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_ones = 1; }else{ $wallboard_ones = 0; }
        if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_twos = 1; }else{ $wallboard_twos = 0; }     
        if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_threes = 1; }else{ $wallboard_threes = 0; }
        if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_fours = 1; }else{ $wallboard_fours = 0; }
        $qry = "INSERT INTO admin_details(name,admin_id,pbx_count,agent_counts,has_contact,has_sms,has_chat,admin_status,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four) VALUES ('$name', '$result','$pbx_count','$agent_counts','$has_contacts','$has_smss','$has_chats',1,'$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','$voice_3cxs','$predective_dialers','$leads','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours')";
   $qry_result = $this->db_query($qry, array()); 
          $result = $qry_result == 1 ? 1 : 0;
             $updateqry2 = " Update user SET `lead_token` = '$lead_token' WHERE admin_id='$aid'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
            return $result;
      }
     
   }    
} 
/*public function getTimezone(){
            $qry = "select * from timezone";
            return $this->dataFetchAll($qry, array());
        }*/
public function getTimezone($user_id){
        $user_timezone_id_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_timezone_id = $this->fetchOne($user_timezone_id_qry,array());
        $qry = "SELECT * FROM timezone";
        $qry_result = $this->dataFetchAll($qry, array());
        for($i = 0; count($qry_result) > $i; $i++){
            $id = $qry_result[$i]['id'];
            $name = $qry_result[$i]['name'];
            $timezone_options = array('id' => $id, 'name' => $name);         
            $timezone_options_array[] = $timezone_options;
        }
        $status = array('status' => 'true');
        $user_timezone = array('user_timezone' => $user_timezone_id);
        $timezone_options_array = array('timezone_options' => $timezone_options_array);
        $finalresult = array_merge($status, $user_timezone, $timezone_options_array);
        $tarray = json_encode($finalresult);
        print_r($tarray);exit;
}
public  function updateSettings($data){  
  extract($data);             
  $qry = "UPDATE user SET layout='$layout',theme='$theme' where user_id='$user_id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;    
}
public function editdialertimezone($data){
	     extract($data);
        $user_timezone_id_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_timezone_id = $this->fetchOne($user_timezone_id_qry,array());
        $user_dialer_qry = "SELECT ext_int_status FROM user WHERE user_id='$user_id'";
        $user_dialer = $this->fetchOne($user_dialer_qry,array());
        
        $qry = "SELECT * FROM timezone";
        $qry_result = $this->dataFetchAll($qry, array());
        for($i = 0; count($qry_result) > $i; $i++){
            $id = $qry_result[$i]['id'];
            $name = $qry_result[$i]['name'];
            $timezone_options = array('id' => $id, 'name' => $name);         
            $timezone_options_array[] = $timezone_options;
        }
        $status = array('status' => 'true');
        $user_timezone = array('user_timezone' => $user_timezone_id);
        $user_dialer_array = array('user_dialer' => $user_dialer);
        $timezone_options_array = array('timezone_options' => $timezone_options_array);
        $finalresult = array_merge($status, $user_timezone, $timezone_options_array, $user_dialer_array);
        $tarray = json_encode($finalresult);
        print_r($tarray);exit;
}
public  function update_dialer_timezone($data){  
  extract($data); 
  $qrys = "UPDATE admin_details SET ext_int_status='$ext_int_status' where admin_id='$user_id'";
  $qry_results = $this->db_query($qrys, array());	
  $qry = "UPDATE user SET timezone_id='$timezone_id',ext_int_status='$ext_int_status' where user_id='$user_id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;    
}
public function wallboard_counts($data){
  extract($data);
	$user_wp_qry = "SELECT count(chat_id) as chat_count FROM chat_wp WHERE admin_id='$user_id'";
        $user_wp_count = $this->fetchmydata($user_wp_qry,array());
	$user_chat_qry = "SELECT count(chat_id) as chat_count FROM chat WHERE admin_id='$user_id'";
        $user_chat_count = $this->fetchmydata($user_chat_qry,array());
	$user_sms_qry = "SELECT count(chat_id) as chat_count FROM chat_sms WHERE admin_id='$user_id'";
        $user_sms_count = $this->fetchmydata($user_sms_qry,array());
	$status = array('status' => 'true');
	$wp_count = array('wp_count' => $user_wp_count);
	$chat_count = array('chat_count' => $user_chat_count);
	$sms_count = array('sms_count' => $user_sms_count);
	$email_count = array('email_ticket_count' => '');
	$final_result = array_merge($status, $wp_count, $chat_count, $sms_count, $email_count);
	//print_r($finalresult);exit;
	$final_array = json_encode($final_result, true);
    print_r($final_array);
	exit;
}
	
function toggle_status($data){    
    extract($data);
    $qry_select="SELECT * FROM admin_details where id='$id'";
    $parms = array();
    $results = $this->fetchdata($qry_select,$parms);     
    if($status_for=='has_contact'){
      if($results['has_contact']=='0'){
        $value=$has_contacts='1';
      }else{$value=$has_contacts='0';}  
    }
    if($status_for=='has_sms'){
      if($results['has_sms']=='0'){
        $value=$has_smss='1';
      }else{$value=$has_smss='0';}     
    }
    if($status_for=='has_chat'){
      if($results['has_chat']=='0'){
        $value=$has_chats='1';
      }else{$value=$has_chats='0';}     
    }
    if($status_for=='has_whatsapp'){
      if($results['has_whatsapp']=='0'){
        $value=$has_whatsapps='1';
      }else{$value=$has_whatsapps='0';}     
    }    
    if($status_for=='has_chatbot'){
      if($results['has_chatbot']=='0'){
        $value=$has_chatbots='1';
      }else{
        $value=$has_chatbots='0';}      
    }
    if($status_for=='has_fb'){
      if($results['has_fb']=='0'){
        $value=$has_fb='1';
      }else{$value=$has_fb='0';}    
    
    }
    if($status_for=='has_wechat'){
      if($results['has_wechat']=='0'){
        $value=$has_wechat='1';
      }else{$value=$has_wechat='0';}    
    
    }
    if($status_for=='has_telegram'){
      if($results['has_telegram']=='0'){
        $value=$has_telegram='1';
      }else{$value=$has_telegram='0';}    
    
    }
    if($status_for=='has_internal_ticket'){
      if($results['has_internal_ticket']=='0'){
        $value=$has_internal_ticket='1';
      }else{$value=$has_internal_ticket='0';}   
    
    }
    if($status_for=='has_external_ticket'){
      if($results['has_external_ticket']=='0'){
        $value=$has_external_ticket='1';
      }else{$value=$has_external_ticket='0';}    
    }
	if($status_for=='voice_3cx'){
      if($results['voice_3cx']=='0'){
        $value=$voice_3cx='1';
      }else{$value=$voice_3cx='0';}    
    }
	if($status_for=='predective_dialer'){
      if($results['predective_dialer']=='0'){
        $value=$predective_dialer='1';
      }else{$value=$predective_dialer='0';}    
    }
	if($status_for=='lead'){
      if($results['lead']=='0'){
        $value=$lead='1';
      }else{$value=$lead='0';}    
    }
    if($status_for=='wallboard_one'){
      if($results['wallboard_one']=='0'){
        $value=$wallboard_one='1';
      }else{$value=$wallboard_one='0';}    
    }
    if($status_for=='wallboard_two'){
      if($results['wallboard_two']=='0'){
        $value=$wallboard_two='1';
      }else{$value=$wallboard_two='0';}    
    }
    if($status_for=='wallboard_three'){
      if($results['wallboard_three']=='0'){
        $value=$wallboard_three='1';
      }else{$value=$wallboard_three='0';}    
    }
    if($status_for=='wallboard_four'){
      if($results['wallboard_four']=='0'){
        $value=$wallboard_four='1';
      }else{$value=$wallboard_four='0';}    
    }
    $adminidqry_select="SELECT admin_id FROM admin_details where id='$id'";
    $parms = array();
    $adminidqry = $this->fetchsingledata($adminidqry_select,$parms);
	$aid = $adminidqry['admin_id'];
    $user_qry_select="SELECT * FROM user where user_id='$aid'";
    $parms = array();
    $userdata = $this->fetchdata($user_qry_select,$parms);     
    if($status_for=='has_contact'){
      if($userdata['has_contact']=='0'){
        $keyword=$has_contacts='1';
      }else{$keyword=$has_contacts='0';}  
    }
    if($status_for=='has_sms'){
      if($userdata['has_sms']=='0'){
        $keyword=$has_smss='1';
      }else{$keyword=$has_smss='0';}     
    }
    if($status_for=='has_chat'){
      if($userdata['has_chat']=='0'){
        $keyword=$has_chats='1';
      }else{$keyword=$has_chats='0';}     
    }
    if($status_for=='has_whatsapp'){
      if($userdata['has_whatsapp']=='0'){
        $keyword=$has_whatsapps='1';
      }else{$keyword=$has_whatsapps='0';}     
    }    
    if($status_for=='has_chatbot'){
      if($userdata['has_chatbot']=='0'){
        $keyword=$has_chatbots='1';
      }else{
        $keyword=$has_chatbots='0';}      
    }
    if($status_for=='has_fb'){
      if($userdata['has_fb']=='0'){
        $keyword=$has_fb='1';
      }else{$keyword=$has_fb='0';}     
    }
    if($status_for=='has_wechat'){
      if($userdata['has_wechat']=='0'){
        $keyword=$has_wechat='1';
      }else{$keyword=$has_wechat='0';}    
    }
    if($status_for=='has_telegram'){
      if($userdata['has_telegram']=='0'){
        $keyword=$has_telegram='1';
      }else{$keyword=$has_telegram='0';}    
    }
    if($status_for=='has_internal_ticket'){
      if($userdata['has_internal_ticket']=='0'){
        $keyword=$has_internal_ticket='1';
      }else{$keyword=$has_internal_ticket='0';}    
    }
    if($status_for=='has_external_ticket'){
      if($userdata['has_external_ticket']=='0'){
        $keyword=$has_external_ticket='1';
      }else{$keyword=$has_external_ticket='0';}     
    }
	if($status_for=='voice_3cx'){
      if($userdata['voice_3cx']=='0'){
        $keyword=$voice_3cx='1';
      }else{$keyword=$voice_3cx='0';}    
    }
	if($status_for=='predective_dialer'){
      if($userdata['predective_dialer']=='0'){
        $keyword=$predective_dialer='1';
      }else{$keyword=$predective_dialer='0';}    
    }
	if($status_for=='lead'){
      if($userdata['lead']=='0'){
        $keyword=$lead='1';
      }else{$keyword=$lead='0';}    
    }
	if($status_for=='wallboard_one'){
      if($userdata['wallboard_one']=='0'){
        $keyword=$wallboard_one='1';
      }else{$keyword=$wallboard_one='0';}    
    }
    if($status_for=='wallboard_two'){
      if($userdata['wallboard_two']=='0'){
        $keyword=$wallboard_two='1';
      }else{$keyword=$wallboard_two='0';}    
    }
    if($status_for=='wallboard_three'){
      if($userdata['wallboard_three']=='0'){
        $keyword=$wallboard_three='1';
      }else{$keyword=$wallboard_three='0';}    
    }
    if($status_for=='wallboard_four'){
      if($userdata['wallboard_four']=='0'){
        $keyword=$wallboard_four='1';
      }else{$keyword=$wallboard_four='0';}    
    }
    $user_update_qry = " Update user SET $status_for='$keyword' where user_id='$aid'";
    $parms = array();
    $user_update_qry_results = $this->db_query($user_update_qry,$parms);
    $qry = " Update admin_details SET $status_for='$value' where id='$id'";
    $parms = array();
    $results = $this->db_query($qry,$parms);
    $output = $results == 1 ? 1 : 0;
    return  $output;
  }

public function change_agent_permission($data){
      extract($data);//print_r($data);exit;
      $qry_select="SELECT * FROM user WHERE user_id='$user_id'";
	  $parms = array();
	  $results = $this->fetchdata($qry_select,$parms);		 
	  if($keyword=='user_status'){
			if($results['user_status']=='0'){
				$value=$user_statuss='1';
			}else{$value=$user_statuss='0';}	
	  }
	  if($keyword=='has_contact'){
			if($results['has_contact']=='0'){
				$value=$has_contacts='1';
			}else{$value=$has_contacts='0';}	
	  }
	  if($keyword=='has_sms'){
			if($results['has_sms']=='0'){
				$value=$has_smss='1';
			}else{$value=$has_smss='0';}		
	  }
	  if($keyword=='has_chat'){
			if($results['has_chat']=='0'){
				$value=$has_chats='1';
			}else{$value=$has_chats='0';}	
	  }
	  if($keyword=='has_whatsapp'){
			if($results['has_whatsapp']=='0'){
				$value=$has_whatsapps='1';
			}else{$value=$has_whatsapps='0';}		
	  }
	  if($keyword=='has_chatbot'){
			if($results['has_chatbot']=='0'){
				$value=$has_chatbots='1';
			}else{
				$value=$has_chatbots='0';}		
	  }
	  if($keyword=='has_fb'){
      if($results['has_fb']=='0'){
        $value=$has_fb='1';
      }else{$value=$has_fb='0';}     
	  }
		if($keyword=='has_wechat'){
		  if($results['has_wechat']=='0'){
			$value=$has_wechat='1';
		  }else{$value=$has_wechat='0';}     
		}
		if($keyword=='has_telegram'){
		  if($results['has_telegram']=='0'){
			$value=$has_telegram='1';
		  }else{$value=$has_telegram='0';}    
		}
		if($keyword=='has_internal_ticket'){
		  if($results['has_internal_ticket']=='0'){
			$value=$has_internal_ticket='1';
		  }else{$value=$has_internal_ticket='0';}     
		}
		if($keyword=='has_external_ticket'){
		  if($results['has_external_ticket']=='0'){
			$value=$has_external_ticket='1';
		  }else{$value=$has_external_ticket='0';}     
		}
	    if($keyword=='voice_3cx'){
		  if($results['voice_3cx']=='0'){
			$value=$voice_3cx='1';
		  }else{$value=$voice_3cx='0';}    
		}
		if($keyword=='predective_dialer'){
		  if($results['predective_dialer']=='0'){
			$value=$predective_dialer='1';
		  }else{$value=$predective_dialer='0';}     
		}
		if($keyword=='lead'){
		  if($results['lead']=='0'){
			$value=$lead='1';
		  }else{$value=$lead='0';}     
		}
	    if($keyword=='wallboard_one'){
          if($results['wallboard_one']=='0'){
            $value=$wallboard_one='1';
          }else{$value=$wallboard_one='0';}    
        }
		if($keyword=='wallboard_two'){
		  if($results['wallboard_two']=='0'){
			$value=$wallboard_two='1';
		  }else{$keyword=$wallboard_two='0';}    
		}
		if($keyword=='wallboard_three'){
		  if($results['wallboard_three']=='0'){
			$value=$wallboard_three='1';
		  }else{$value=$wallboard_three='0';}    
		}
		if($keyword=='wallboard_four'){
		  if($results['wallboard_four']=='0'){
			$value=$wallboard_four='1';
		  }else{$value=$wallboard_four='0';}    
		}
	//echo "UPDATE user SET $keyword='$value' WHERE user_id=$user_id";exit;
	  $qry = "UPDATE user SET $keyword='$value' WHERE user_id='$user_id'";		
      $parms = array();
      $qryresults = $this->db_query($qry,$parms);
      $output = $qryresults == 1 ? 1 : 0;		
      return  $output;                   
    }
	
	public function delete_admin($data){
      extract($data);	
      // update admin_details_table		
      $admindetails_qry = "UPDATE admin_details SET delete_status='1' WHERE id='$user_id'";
      $parms = array();
      $admindetails_qry_results = $this->db_query($admindetails_qry,$parms);
	  $output = $admindetails_qry_results == 1 ? 1 : 0;
	  // update user atble
	  $aid_qry = "SELECT admin_id FROM admin_details WHERE id='$user_id'";
      $aid = $this->fetchOne($aid_qry,array());	
	  $qry = "UPDATE user SET delete_status='1' WHERE user_id='$aid'";
      $parms = array();
      $results = $this->db_query($qry,$parms);
      // update agents data
      $agentqry = "UPDATE user SET delete_status='1' WHERE admin_id='$aid'";
      $parms = array();
      $agentqry_results = $this->db_query($agentqry,$parms);          
      return  $output;
    }
	public function delete_agent($data){
      extract($data);
      $qry = "UPDATE user SET delete_status='1' WHERE user_id='$user_id' AND admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function get_admin_global_settings($data){
		extract($data);
		$qry_select = "SELECT * FROM `user` WHERE user_id = '$user_id'";
		$parms = array();
		$results = $this->fetchdata($qry_select,$parms);		
		$logo_image = $results['logo_image'];
		$small_logo_image = $results['small_logo_image'];
		$ext_int_status = $results['ext_int_status']; 
		if($ext_int_status==1){
		  $dialer = 'External';
		  $ext_int_status = 1;
		}else{
		  $dialer = 'Internal';
		  $ext_int_status = 2;
		}
		$timezone_id = $results['timezone_id'];
		$qry = "SELECT * FROM timezone";
		$qry_result = $this->dataFetchAll($qry, array());
		for($i = 0; count($qry_result) > $i; $i++){
		  $id = $qry_result[$i]['id'];
		  $name = $qry_result[$i]['name'];
		  $timezone_options = array('id' => $id, 'name' => $name);         
		  $timezone_options_array[] = $timezone_options;
		}    
		$status = array('status' => 'true');
		$user_dialer_options = array('dialer_option' => $dialer, 'dialer_value' => $ext_int_status);
		$user_timezone = array('user_timezone' => $timezone_id);
		$timezone_options_array = array('timezone_options' => $timezone_options_array);
		$logo_option_array = array('logo_image' => $logo_image, 'small_logo_image' => $small_logo_image);
		$finalresult = array_merge($status, $user_dialer_options, $user_timezone, $timezone_options_array, $logo_option_array);
		$tarray = json_encode($finalresult);
		print_r($tarray);exit;
  	}
	public function forgot_password($data){
      extract($data); 
	  if($email != ''){	
      $get_question_qry = "SELECT * FROM user WHERE email_id='$email'";
      $result = $this->fetchData($get_question_qry,array()); 		
      if($result > 0){
		  $admin_id = $result['admin_id'];
		if($admin_id==1){
			$from = 'sales@cal4care.com.sg';
		}else{
			$admin_email_qry = "SELECT email_id FROM user WHERE user_id='$admin_id'";
            $from = $this->fetchOne($admin_email_qry,array());
		}
        $password = $result['password'];		  
        $content = "Your Password:".$password;
        $subject = "Forgot Password From Omni Channel";
        require_once('class.phpmailer.php');
        $body = addslashes($content);         
        //echo $from;exit;
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
        $mail->Host = 'smtpcorp.com'; // sets the SMTP server
        $mail->Port = '2525';                    // set the SMTP port for the GMAIL server
        $mail->Username = 'erpdev2'; // SMTP account username
        $mail->Password = 'dnZ0ZjlyZ3RydzAw';        // SMTP account password 
        $mail->SetFrom($from, $from);
        $mail->AddReplyTo($from, $from);
        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($email);    
        $mail->Send();
        $result = array("status" => true);
        $tarray = json_encode($result);           
        print_r($tarray);exit;       
      }
      else{ 
        $result = array("status" => false);
        $tarray = json_encode($result);           
        print_r($tarray);exit;       
      }
	}else{ 
        $result = array("status" => false);
        $tarray = json_encode($result);           
        print_r($tarray);exit;       
     }
    }
	public function choose_marketplace($data){
    extract($data);//print_r($data);exit;      
    if($wallboard_id==1){
      $column = 'wallboard_one_isactive';
      $value = 1;  
    }
    if($wallboard_id==2){
      $column = 'wallboard_two_isactive';
      $value = 1;  
    }
    if($wallboard_id==3){
      $column = 'wallboard_three_isactive';
      $value = 1;  
    }
    if($wallboard_id==4){
      $column = 'wallboard_four_isactive';
      $value = 1;  
    }
	//echo "UPDATE admin_details SET $column='$value' WHERE id='$admin_id'";exit;
    $qry = "UPDATE admin_details SET $column='$value' WHERE admin_id='$admin_id'";    
    $parms = array();
    $results = $this->db_query($qry,$parms);
    $output = $results == 1 ? 1 : 0;    
    return  $output;                   
  }
	
 

}
