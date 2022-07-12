<?php 
class userData extends restApi{            
		public function userLoginValidation($company_name, $user_name, $password){			
            $userAuth = new userAuth();
            $password_encode = $userAuth->userPwdEncode($password);        
            $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_login,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.has_internal_chat,user.external_contact_url,user.show_caller_id as user_caller_id,user.predective_dialer_behave,user.whatsapp_account,user.crm_type,user.facebook_account,admin_details.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.admin_status,admin_details.show_caller_id,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.has_webinar,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip,admin_details.admin_status, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.company_name='$company_name' AND user_name='$user_name' AND user_pwd='$password_encode' AND user_status=1 ";
			//print_r($qry); exit;
            $result = $this->fetchData($qry, array());
		
				//print_r($result); exit;
		
			
            $two_factor_qry = "SELECT two_factor FROM user WHERE user_name='$user_name' AND user_pwd='$password_encode'";
            $two_factor = $this->fetchOne($two_factor_qry,array());
            if($two_factor == ''){              
              $result = array("data" => 0);              
              return $result;
            }
            elseif($two_factor == 0){              
              if($result == null){
                return $result;
              }
              else{
				  $ext_no = $result['sip_login'];
                  $element_data = array('action' => 'get_profile_image', 'ext_no' => $ext_no);
				  $fields = array('operation'=>'get_profile_image','moduleType'=>'ticket','api_type'=>'web','action' => 'get_profile_image', 'ext_no' => $ext_no);
				  //file_put_contents('vaithee.txt', print_r($fields,true).PHP_EOL , FILE_APPEND | LOCK_EX);
				  $url = 'https://erp.cal4care.com/erp/apps/ind.php';
				  $ch = curl_init();
				  curl_setopt_array($ch, array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_POST => true,
						CURLOPT_POSTFIELDS => $fields
					));
				  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				  $output = curl_exec($ch);
				  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                  $curl_errno= curl_errno($ch);				  
				  $te = json_decode($output,true);
				  $profile_image = $te['profile_image'];				  
                  $user_id = $result["user_id"];
                $ip = $this->getClientIP();
                $this->db_query("INSERT INTO user_login(user_id, user_method, created_ip) VALUES ('$user_id','user','$ip')", array());
				$this->db_query("UPDATE user SET login_status=1,profile_image='$profile_image' WHERE user_id='$user_id'", array());
				  $ry = "UPDATE user SET login_status=1,profile_image='$profile_image' WHERE user_id='$user_id'";
				  
              }
			  $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_login,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.has_internal_chat,user.external_contact_url,user.show_caller_id as user_caller_id,user.predective_dialer_behave,user.whatsapp_account,user.crm_type,user.facebook_account,admin_details.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.admin_status,admin_details.show_caller_id,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.has_webinar,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip,admin_details.admin_status, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.company_name='$company_name' AND user_name='$user_name' AND user_pwd='$password_encode' AND user_status=1 ";
			//print_r($qry); exit;
            $result = $this->fetchData($qry, array());	
              return $result;
            }
            else{              
              if($result == null){
                return $result;
              }
              else{   
                $result = array("data" => 1);
                $result_array = json_encode($result);           
                print_r($result_array);exit;   
              }
            }                       
        }
	

        public function send_otp($company_name, $user_name, $password, $method){
            $userAuth = new userAuth();
            $password_encode = $userAuth->userPwdEncode($password);        
            $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,,user.has_internal_chat,user.show_caller_id,user.external_contact_url,user.predective_dialer_behave,user.whatsapp_account, user.crm_type,user.facebook_account,user.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.company_name='$company_name' AND user_name='$user_name' AND user_pwd='$password_encode'";
            $result = $this->fetchData($qry, array());
            if($method=='sms')
            {          
                $otp = rand(0, 9999);
                $user_id = $result['user_id'];
                $mobile_num = $result['phone_number'];                 
                $postData = array(
                                      'user' => 'cal4care',
                                      'password' => 'Godaddy123',
                                      'phonenumber' => "+91".$mobile_num,
                                      'text' => $otp,
                                      'gsm_sender' => 'BzzSMS',
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
                $output = curl_exec($ch);
                //print_r($output);exit;
                if(curl_errno($ch)){
                  throw new Exception(curl_error($ch));
                }
                curl_close($ch);
                if($output == '1: Message sent successfully @  '){
                  $updateqry1 = "UPDATE user SET `otp` = $otp WHERE user_id='$user_id'";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);
                  $result = array("data" => 1);
                  $result_array = json_encode($result);           
                  print_r($result_array);exit;
                }else{
                  $result = array("data" => 0);
                  $result_array = json_encode($result);           
                  print_r($result_array);exit;
                }
            }
            if($method=='email')
            {          
                $otp = rand(0, 9999);
                $user_id = $result['user_id'];
                $email_id = $result['email_id'];
                if($email_id == ''){
                  return $result;
                }
                else{
                  require_once('class.phpmailer.php');
                  $subject = "Your one time password";
                  $message = "Your one time password to login ".$otp;
                  $body = addslashes($message);                
                  $mail = new PHPMailer();
                  $mail->IsSMTP();
                  $mail->SMTPAuth = true; 
                  $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                  $mail->Host = 'smtpcorp.com'; // sets the SMTP server
                  $mail->Port = '2525';                    // set the SMTP port for the GMAIL server
                  $mail->Username = 'erpdev2'; // SMTP account username
                  $mail->Password = 'dnZ0ZjlyZ3RydzAw';        // SMTP account password 
                  $mail->SetFrom($to, $to);
                  $mail->AddReplyTo($from, $from);
                  $mail->Subject = $subject;
                  $mail->MsgHTML($body);
                  $mail->AddAddress($user_email);    
                  $mail->Send();
                  $updateqry1 = "UPDATE user SET `otp` = $otp WHERE user_id='$user_id'";
                  $parms = array();
                  $results1 = $this->db_query($updateqry1,$parms);
                  $results = $results1 == 1 ? 1 : 0;
                  return $results;                        
                }                       
            }            
        }	
	
	public function check_otp($otp){                       
            $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,,user.has_internal_chat,user.show_caller_id,user.external_contact_url,user.predective_dialer_behave,user.whatsapp_account, user.crm_type,user.facebook_account,user.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE otp=:otp";
            $result = $this->fetchData($qry, array("otp"=>$otp));            
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
    $user_timezone_id_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
    $timezone_id = $this->fetchOne($user_timezone_id_qry,array());    
    if($admin_permision==1)
    { 
      $admin_details_qry = "select * from user where user_id='$admin_id'";
      $admin_details = $this->fetchData($admin_details_qry, array());   
      $has_contact = $admin_details['has_contact'];
      $has_sms = $admin_details['has_sms'];
      $has_chat = $admin_details['has_chat']; 
      $has_whatsapp = $admin_details['has_whatsapp'];
      $has_chatbot = $admin_details['has_chatbot']; 
      $has_fb = $admin_details['has_fb'];
		$has_fax = $admin_details['has_fax'];
      $has_wechat = $admin_details['has_wechat'];
      $has_telegram = $admin_details['has_telegram'];
      $has_internal_ticket = $admin_details['has_internal_ticket'];
      $has_external_ticket = $admin_details['has_external_ticket'];
      $ext_int_status = $admin_details['ext_int_status'];
      $voice_3cx = $admin_details['voice_3cx'];
      $predective_dialer = $admin_details['predective_dialer'];
      $lead = $admin_details['lead'];
      $wallboard_one = $admin_details['wallboard_one'];
      $wallboard_two = $admin_details['wallboard_two'];
      $wallboard_three = $admin_details['wallboard_three'];
      $wallboard_four = $admin_details['wallboard_four'];
      $two_factor = $admin_details['two_factor'];
      $reports = $admin_details['reports'];
	  $has_internal_chat = $admin_details['has_internal_chat'];	
	  $predective_dialer_behave = $admin_details['predective_dialer_behave'];	
      $company_name = $admin_details['company_name'];
      $hardware_id = $admin_details['hardware_id'];
	  $domain_name = $admin_details['domain_name'];		
	  $whatsapp_type = $admin_details['whatsapp_type'];	 
	  $wp_instance_count = $admin_details['wp_instance_count'];
	  $has_webinar = $admin_details['has_webinar'];
	  $plan_id = $admin_details['plan_id'];
	  $call_rate = $admin_details['call_rate'];
	  $valid_from = $admin_details['valid_from'];
	  $valid_to = $admin_details['valid_to']; 
	  $call_prefix = $admin_details['call_prefix'];
	  $tax_name = $admin_details['tax_name'];
	  $tax_per = $admin_details['tax_per'];
	  $has_3cx_rep = $admin_details['has_3cx_rep'];
	  $ag_group = $admin_details['ag_group'];
	  $voice_manage = $admin_details['voice_manage'];
	  $baisc_wallboard = $admin_details['baisc_wallboard'];
	  $wrap_up = $admin_details['wrap_up'];
	  $queue = $admin_details['queue'];
	  $wallboard_eight = $admin_details['wallboard_eight'];
		$webrtc_server =  $admin_details['webrtc_server'];
    $dialer_auto_answer =  $admin_details['dialer_auto_answer'];
    $has_kb =  $admin_details['has_kb'];
			$check_user=$this->fetchOne("SELECT user_id FROM user where user_name='$user_name' and company_name='$company_name'",array());
if($check_user!=''){
	$result_data["result"]["status"] = false;
			print_r( json_encode($result_data));
			exit;
	
}
		
		//echo "INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password,two_factor,ext_int_status,dsk_access,dsk_user_name,dsk_user_pwd,reports,has_internal_chat,predective_dialer_behave) VALUES ('$agent_name','$phone_number','$email_id',$user_status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password',$two_factor,$ext_int_status,'$dsk_access','$dsk_user_name','$dsk_user_pwd','$reports','$has_internal_chat','$predective_dialer_behave')";exit;
	  $admin_details_qry = "select * from user where user_id='$admin_id'";
      $admin_details = $this->fetchData($admin_details_qry, array());
	  $webrtc_server =  $admin_details['webrtc_server'];
		
      $qry_result = $this->db_query("INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password,two_factor,ext_int_status,dsk_access,dsk_user_name,dsk_user_pwd,reports,has_internal_chat,predective_dialer_behave,company_name,domain_name,hardware_id,has_fax,whatsapp_type,wp_instance_count,admin_permision,has_webinar,call_rate,valid_from,valid_to,call_plan,call_prefix,tax_name,tax_per,has_3cx_rep,ag_group,dialer_auto_answer,voice_manage,baisc_wallboard,wrap_up,queue,wallboard_eight,webrtc_server,has_kb) VALUES ('$agent_name','$phone_number','$email_id',$user_status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password',$two_factor,$ext_int_status,'$dsk_access','$dsk_user_name','$dsk_user_pwd','$reports','$has_internal_chat','$predective_dialer_behave','$company_name','$domain_name','$hardware_id','$has_fax','$whatsapp_type','$wp_instance_count','$admin_permision','$has_webinar','$call_rate','$valid_from','$valid_to','$plan_id','$call_prefix','$tax_name','$tax_per','$has_3cx_rep','$ag_group','$dialer_auto_answer','$voice_manage','$baisc_wallboard','$wrap_up','$queue','$wallboard_eight','$webrtc_server','$has_kb')", array());    

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
    else{

        $admin_details_qry = "select * from user where user_id='$admin_id'";
        $admin_details = $this->fetchData($admin_details_qry, array());  
        $company_name = $admin_details['company_name'];
        $hardware_id = $admin_details['hardware_id'];
        $domain_name = $admin_details['domain_name'];	
		$ext_int_status = $admin_details['ext_int_status'];
		$webrtc_server =  $admin_details['webrtc_server'];
		

		//echo "INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password,dsk_access,dsk_user_name,dsk_user_pwd,reports,close_all_menu,predective_dialer_behave,company_name,domain_name,hardware_id,has_fax,whatsapp_type,wp_instance_count,ext_int_status,has_webinar,call_rate,valid_from,valid_to,call_plan,call_prefix,tax_name,tax_per,has_internal_chat,has_3cx_rep,ag_group,dialer_auto_answer,webrtc_server) VALUES ('$agent_name','$phone_number','$email_id',$user_status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password','$dsk_access','$dsk_user_name','$dsk_user_pwd','$reports','$close_all_menu','$predective_dialer_behave','$company_name','$domain_name','$hardware_id','$has_fax','$whatsapp_type','$wp_instance_count','$ext_int_status','$has_webinar','$call_rate','$valid_from','$valid_to','$plan_id','$call_prefix','$tax_name','$tax_per','$has_internal_chat','$has_3cx_rep','$ag_group','$dialer_auto_answer','$webrtc_server')";exit;

		
			$check_user=$this->fetchOne("SELECT user_id FROM user where user_name='$user_name' and company_name='$company_name'",array());
if($check_user!=''){
	$result_data["result"]["status"] = false;
			print_r( json_encode($result_data));
			exit;
}
	//	echo $webrtc_server;exit;
		
      $qry_result = $this->db_query("INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password,dsk_access,dsk_user_name,dsk_user_pwd,reports,close_all_menu,predective_dialer_behave,company_name,domain_name,hardware_id,has_fax,whatsapp_type,wp_instance_count,ext_int_status,has_webinar,call_rate,valid_from,valid_to,call_plan,call_prefix,tax_name,tax_per,has_internal_chat,has_3cx_rep,ag_group,dialer_auto_answer,webrtc_server,has_kb) VALUES ('$agent_name','$phone_number','$email_id',$user_status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password','$dsk_access','$dsk_user_name','$dsk_user_pwd','$reports','$close_all_menu','$predective_dialer_behave','$company_name','$domain_name','$hardware_id','$has_fax','$whatsapp_type','$wp_instance_count','$ext_int_status','$has_webinar','$call_rate','$valid_from','$valid_to','$plan_id','$call_prefix','$tax_name','$tax_per','$has_internal_chat','$has_3cx_rep','$ag_group','$dialer_auto_answer','$webrtc_server','$has_kb')", array()); 
      $result = $qry_result == 1 ? 1 : 0;
      if($qry_result != 0){
          $last_id_qry = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
          $last_id = $this->fetchOne($last_id_qry,array());
		  $admin_qry = "SELECT company_name,domain_name FROM admin_details WHERE admin_id='$admin_id'";
				  $admin_qry_res = $this->fetchData($admin_qry,array());
				  $company_name = $admin_qry_res['company_name'];
				  $domain_name = $admin_qry_res['domain_name'];
				  $qry = "UPDATE user SET company_name='$company_name',domain_name='$domain_name' WHERE user_id='$last_id'";
				  $qry_result = $this->db_query($qry, array());
          $result = $this->getUserData($last_id);                
      }
      else{                
          $result = 0;
      }
      return $result;
    }
  }
    
/*public  function update_agent($user_id,$agent_name,$email_id,$phone_number,$user_name,$sip_login,$sip_username,$sip_password){  
  
	$user_qry = "UPDATE user SET agent_name='$agent_name',email_id='$email_id',phone_number='$phone_number',user_name='$user_name',sip_login='$sip_login',sip_username='$sip_username',sip_password='$sip_password' where user_id='$user_id'";
  $userqry_result = $this->db_query($user_qry, array());  
  $result = $userqry_result == 1 ? 1 : 0;
  return $result;    
}*/
 
public  function update_agent($agent_data){ 
	//print_r($agent_data); exit;
  extract($agent_data); 
	$user_pwd = $password;
	$password = MD5(trim($user_pwd));
	
	 if($admin_permision==1)
    { 
      $admin_details_qry = "select * from user where user_id='$admin_id'";
      $admin_details = $this->fetchData($admin_details_qry, array());   
      $has_contact = $admin_details['has_contact'];
      $has_sms = $admin_details['has_sms'];
      $has_chat = $admin_details['has_chat']; 
      $has_whatsapp = $admin_details['has_whatsapp'];
      $has_chatbot = $admin_details['has_chatbot']; 
      $has_fb = $admin_details['has_fb'];
		$has_fax = $admin_details['has_fax'];
      $has_wechat = $admin_details['has_wechat'];
      $has_telegram = $admin_details['has_telegram'];
      $has_internal_ticket = $admin_details['has_internal_ticket'];
      $has_external_ticket = $admin_details['has_external_ticket'];
      $ext_int_status = $admin_details['ext_int_status'];
      $voice_3cx = $admin_details['voice_3cx'];
      $predective_dialer = $admin_details['predective_dialer'];
      $lead = $admin_details['lead'];
      $wallboard_one = $admin_details['wallboard_one'];
      $wallboard_two = $admin_details['wallboard_two'];
      $wallboard_three = $admin_details['wallboard_three'];
      $wallboard_four = $admin_details['wallboard_four'];
      $two_factor = $admin_details['two_factor'];
      $reports = $admin_details['reports'];
	  $has_internal_chat = $admin_details['has_internal_chat'];	
	  $predective_dialer_behave = $admin_details['predective_dialer_behave'];	
      $company_name = $admin_details['company_name'];
      $hardware_id = $admin_details['hardware_id'];
	  $domain_name = $admin_details['domain_name'];		
	  $whatsapp_type = $admin_details['whatsapp_type'];	 
	  $wp_instance_count = $admin_details['wp_instance_count'];
		 $has_webinar = $admin_details['has_webinar'];
	  $plan_id = $admin_details['plan_id'];
	  $call_rate = $admin_details['call_rate'];
	  $valid_from = $admin_details['valid_from'];
	  $valid_to = $admin_details['valid_to'];
	  $call_prefix = $admin_details['call_prefix'];
	  $tax_name = $admin_details['tax_name'];
	  $tax_per = $admin_details['tax_per'];
	 $has_3cx_rep = $admin_details['has_3cx_rep'];
	  $ag_group = $admin_details['ag_group'];
		 $dialer_auto_answer = $admin_details['dialer_auto_answer'];
		 $voice_manage = $admin_details['voice_manage'];
		 $baisc_wallboard = $admin_details['baisc_wallboard'];
		 $wrap_up = $admin_details['wrap_up'];
		 $queue = $admin_details['queue'];
		 $wallboard_eight = $admin_details['wallboard_eight'];
		 $webrtc_server = $admin_details['webrtc_server'];
		 $show_caller_id = $admin_details['show_caller_id'];
     $has_kb = $admin_details['has_kb'];
     // $qry_result = $this->db_query("INSERT INTO user (agent_name,phone_number,email_id,user_status,created_ip,created_by,incoming_call_status,user_type,user_name,user_pwd,sip_login,sip_username,sip_password,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,layout,theme,timezone_id,admin_id,password,two_factor,ext_int_status,dsk_access,dsk_user_name,dsk_user_pwd,reports,has_internal_chat,predective_dialer_behave,company_name,domain_name,hardware_id,has_fax,whatsapp_type,wp_instance_count) VALUES ('$agent_name','$phone_number','$email_id',$user_status,'',$admin_id,1,4,'$user_name','$user_pwd','$sip_login','$sip_username','$sip_password',$has_contact,$has_sms,$has_chat,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,'$layout','$theme',$timezone_id,$admin_id,'$password',$two_factor,$ext_int_status,'$dsk_access','$dsk_user_name','$dsk_user_pwd','$reports','$has_internal_chat','$predective_dialer_behave','$company_name','$domain_name','$hardware_id','$has_fax','$whatsapp_type','$wp_instance_count')", array());    
 
	  $user_qry = "UPDATE user SET agent_name='$agent_name',email_id='$email_id',phone_number='$phone_number',user_name='$user_name',sip_login='$sip_login',sip_username='$sip_username',sip_password='$sip_password',has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',has_whatsapp='$has_whatsapp',has_fax='$has_fax',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',dsk_access='$dsk_access',dsk_user_name='$dsk_user_name',dsk_user_pwd='$dsk_user_pwd',reports='$reports',user_status='$user_status',close_all_menu='$close_all_menu',predective_dialer_behave='$predective_dialer_behave',admin_permision='$admin_permision',user_pwd='$password',password='$user_pwd',whatsapp_type='$whatsapp_type',wp_instance_count='$wp_instance_count',has_webinar='$has_webinar',call_rate='$call_rate',valid_from='$valid_from',valid_to='$valid_to',call_plan='$plan_id',call_prefix='$call_prefix',tax_name='$tax_name',tax_per='$tax_per',has_internal_chat='$has_internal_chat',has_3cx_rep='$has_3cx_rep',ag_group='$ag_group',dialer_auto_answer='$dialer_auto_answer',voice_manage='$voice_manage',baisc_wallboard='$baisc_wallboard',wrap_up='$wrap_up',queue='$queue',wallboard_eight='$wallboard_eight',webrtc_server='$webrtc_server',has_kb='$has_kb',show_caller_id='$show_caller_id'  WHERE user_id='$user_id'";
		 } else {
		 $admin_details_qry = "select * from user where user_id='$admin_id'";
         $admin_details = $this->fetchData($admin_details_qry, array());   
		 $webrtc_server = $admin_details['webrtc_server'];
		 $show_caller_id = $admin_details['show_caller_id'];
	   $user_qry = "UPDATE user SET agent_name='$agent_name',email_id='$email_id',phone_number='$phone_number',user_name='$user_name',sip_login='$sip_login',sip_username='$sip_username',sip_password='$sip_password',has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',has_whatsapp='$has_whatsapp',has_fax='$has_fax',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',dsk_access='$dsk_access',dsk_user_name='$dsk_user_name',dsk_user_pwd='$dsk_user_pwd',reports='$reports',user_status='$user_status',close_all_menu='$close_all_menu',predective_dialer_behave='$predective_dialer_behave',admin_permision='$admin_permision',user_pwd='$password',password='$user_pwd',whatsapp_type='$whatsapp_type',wp_instance_count='$wp_instance_count',has_webinar='$has_webinar',call_rate='$call_rate',valid_from='$valid_from',valid_to='$valid_to',call_plan='$plan_id',call_prefix='$call_prefix',tax_name='$tax_name',tax_per='$tax_per',has_internal_chat='$has_internal_chat',has_3cx_rep='$has_3cx_rep',ag_group='$ag_group',dialer_auto_answer='$dialer_auto_answer',voice_manage='$voice_manage',baisc_wallboard='$baisc_wallboard',wrap_up='$wrap_up',queue='$queue',wallboard_eight='$wallboard_eight',webrtc_server='$webrtc_server',show_caller_id='$show_caller_id',has_kb='$has_kb'  WHERE user_id='$user_id'";
		
		 
	 }
$exp = explode(',',$user_dept);
$arr_count = count($exp);
for($q=0;$q<$arr_count;$q++){
 $dep_ids = $exp[$q];
 $dep_users = $this->fetchOne("SELECT department_users FROM `departments` WHERE dept_id='$dep_ids' ",array());
 $exp1 = explode(',',$dep_users);
 if (in_array($user_id, $exp1)){
 }
 else{
    $implode_user = $dep_users.",".$user_id;
    $update_dept_qry = "UPDATE `departments` SET department_users = '$implode_user' WHERE dept_id = '$dep_ids'";             
    $this->db_query($update_dept_qry, array());
 }
}	
	
	

  //echo $user_qry;exit;
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
      /*else if ($profile_image_width > 100 && $profile_image_height > 100) {
            $result = array(
                "status" => "false",
                "message" => "Image width or height exceeds"
            );
			$tarray = json_encode($result);  
            print_r($tarray);exit;
      }*/
	  else{
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
	

public function admin_global_settings($data, $where_arr){   
	//print_r($data);exit;
    $user_id = $data['img_user_id'];
    $timezone_id = $data['timezone_id'];
	$ext_int_status = $data['ext_int_status'];
	 $show_caller_id = $data['show_caller_id'];
	$has_video_call = $data['has_video_call'];
	$dialer_ring = $data['dialer_ring'];
	$webrtc_server = $data['webrtc_server'];
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
	
 
	
	$this->db_query("UPDATE user SET show_caller_id='$show_caller_id' WHERE admin_id='$user_id'", array());
	$this->db_query("UPDATE user SET show_caller_id='$show_caller_id' WHERE user_id='$user_id'", array());
	
	$this->db_query("UPDATE user SET has_video_call='$has_video_call' WHERE admin_id='$user_id'", array());
	$this->db_query("UPDATE user SET has_video_call='$has_video_call' WHERE user_id='$user_id'", array());
	
	$this->db_query("UPDATE user SET dialer_ring='$dialer_ring' WHERE user_id='$user_id'", array());
	$this->db_query("UPDATE user SET dialer_ring='$dialer_ring' WHERE admin_id='$user_id'", array());
	
	$this->db_query("UPDATE user SET webrtc_server='$webrtc_server'  WHERE user_id='$user_id'", array());
	$this->db_query("UPDATE user SET webrtc_server='$webrtc_server'  WHERE admin_id='$user_id'", array());

	//$this->db_query("UPDATE admin_details SET show_caller_id='$show_caller_id' WHERE admin_id='$user_id'", array());
	
	//print_r($agent_data);exit;
    $qry_result = $this->db_query("UPDATE user SET timezone_id='$timezone_id',ext_int_status='$ext_int_status',logo_image='$logo_image_upload_path',small_logo_image='$small_logo_image_upload_path' where user_id='$user_id'", array());
	
 $qry_result = $this->db_query("UPDATE user SET timezone_id='$timezone_id',logo_image='$logo_image_upload_path',small_logo_image='$small_logo_image_upload_path',ext_int_status='$ext_int_status' where admin_id='$user_id'", array());
	
    $resultqry = $qry_result == 1 ? 1 : 0; 
     if($resultqry==1){
		 $user_type_qry = "SELECT user_type FROM user WHERE user_id='$user_id'";
        $user_type = $this->fetchOne($user_type_qry,array());
        if($user_type==2){
			// echo $has_video_call; exit;
          $admin_qry_result = $this->db_query("UPDATE admin_details SET ext_int_status='$ext_int_status',has_video_call='$has_video_call', show_caller_id='$show_caller_id',dialer_ring='$dialer_ring',webrtc_server='$webrtc_server' where admin_id='$user_id'", array());
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
                $search_qry = " and (user.user_name like '%".$search_text."%' or user.agent_name like '%".$search_text."%' or user.email_id like '%".$search_text."%' or user.phone_number like '%".$search_text."%' or status.status_desc like '%".$search_text."%')";
            }
if($admin_id == '1'){
	  $qry = "select user.user_id,user.has_kb,user.sip_login,user.user_name,user.user_pwd,user.agent_name,user.user_type, user_type.user_type_name, user.email_id,user.phone_number,user.user_status,user.has_contact,user.has_sms,user.has_chat,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.layout,user.theme,user.password,user.two_factor,user.company_name,user.domain_name,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.sip_registered_status,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.has_internal_chat,user.login_status,user.external_contact_url,user.show_caller_id,user.has_webinar,user.admin_permision,user.short_code,status.status_name,status.status_desc from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_type != 1 ".$search_qry; 
	
	  $qry_s = "select user.sip_login from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_type != 1 ".$search_qry; 
	
} else {
	  $qry = "select user.user_id,user.has_kb,user.sip_login,user.user_name,user.user_pwd,user.agent_name,user.user_type, user_type.user_type_name, user.email_id,user.phone_number,user.user_status,user.has_contact,user.has_sms,user.has_chat,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.layout,user.theme,user.password,user.two_factor,user.company_name,user.domain_name,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.sip_registered_status,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.external_contact_url,user.has_internal_chat,user.external_contact_url,user.show_caller_id,user.has_webinar,user.admin_permision,user.short_code, status.status_name,status.status_desc from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_type != 1 and user.delete_status != 1 and user.admin_id='$admin_id'".$search_qry; 
	
	 $qry_s = "select sip_login from user where delete_status != 1 and admin_id='$admin_id'"; 
	
}
           
			
		
            $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;	
			
		//echo $detail_qry; exit;	
            $user_permission_qry = "select has_kb,user_status,has_contact,has_sms,has_chat,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,wallboard_one,wallboard_two,wallboard_three,wallboard_four,two_factor,company_name,domain_name,dsk_access,dsk_user_name,dsk_user_pwd,has_wallboard,hardware_id,has_fax,has_external_contact,reports,close_all_menu,login_status,external_contact_url,has_internal_chat,external_contact_url,show_caller_id,voice_3cx,predective_dialer,has_webinar from user where user_id='$admin_id'";			
            $agent_count= "select COUNT(user_id) from user where admin_id='$admin_id' and delete_status!='1'";
			 $agent_count=$this->fetchOne($agent_count, array());
			 $allowed_agents= $this->fetchOne("SELECT agent_counts FROM `admin_details` where admin_id='$admin_id'", array());
			$can_add=$allowed_agents-$agent_count;
			//echo $user_permission_qry; exit;	
      $users = $this->dataFetchAll($detail_qry,array());
			$omni_users = "SELECT * FROM ms_sso_authentication where admin_id='$admin_id'";
			$resultsso = $this->fetchData($omni_users,array());
			$omni_users = $resultsso['omni_users'];
			$teams_users = $resultsso['teams_users'];
			$omni_users = explode(',',$omni_users);
			$teams_users = explode(',',$teams_users);
			
			foreach($users as $user){
				$u_id = $user['user_id'];
				if (in_array($u_id, $omni_users)){ $user['omnisso'] = '1';}else{$user['omnisso'] = '0'; }
				if (in_array($u_id, $teams_users)){ $user['teamssso'] = '1';}else{$user['teamssso'] = '0'; }
				$user2[] = $user;
			}
            $parms = array();			
            $result["user_permission"] = $this->fetchData($user_permission_qry, array());
            $result["list_data"] = $user2;
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["available_users"]=$agent_count;
            $result["list_info"]["can_add"]=$can_add;
            $result["list_info"]["offset"] = $offset;
			$sips= $this->dataFetchAll($qry_s,array());

			
			$array = array_column($sips, 'sip_login');
			$result["sip_logins"] = $array;
			
			
			
			
            return $result;
        }
    
    
        public function getUserData($user_id){
            $qry = "select * from user where user_id='$user_id'";
           
            $result = $this->fetchData($qry, array());
           
           if($result['user_type'] == '2'){
               
                $admin_id = $user_id; 
            } else { 
                $admin_id = $result['admin_id'];
            }
            
             $qry = "select user.user_id,user.has_kb,user.user_name,user.user_pwd,user.agent_name,user.user_type,user.email_id,user.phone_number,user.has_contact,user.has_sms,user.has_chat,user.user_status,user.has_whatsapp,user.has_chatbot,user.has_fb,user.has_wechat,user.has_telegram,user.has_internal_ticket,user.has_external_ticket,user.voice_3cx,user.predective_dialer,user.lead,user.wallboard_one,user.wallboard_two,user.wallboard_three,user.wallboard_four,user.sip_login,user.sip_username,user.sip_password,user.timezone_id,user.profile_image,user.logo_image,user.small_logo_image,user.layout,user.theme,user.password,user.two_factor,user.company_name,user.domain_name,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.reports,user.has_external_contact,user.close_all_menu,user.login_status,user.external_contact_url,user.admin_permision,user.predective_dialer_behave, user_type.user_type_name,user.webrtc_server, status.status_name,user.has_internal_chat,user.has_webinar,user.predective_dialer_behave,user.dialer_auto_answer,user.valid_from,user.valid_to,user.call_rate,user.call_plan,user.call_prefix,user.tax_name,user.tax_per,user.has_3cx_rep,user.ag_group,user.voice_manage,user.baisc_wallboard,user.wrap_up,user.queue,user.wallboard_eight from user left join user_type on user_type.user_type_id =user.user_type left join status on status.status_id = user.user_status where user.user_id='$user_id'";
        
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
    
  public function getAdminData($user_id){            
          $qrys = "SELECT reseller FROM `user` where user_id='$user_id'";	 
	          $reseller=$this->fetchOne($qrys, array());
	  if($user_id=='1'){
	       $qry = "SELECT admin_details.id,admin_details.name,admin_details.admin_id,admin_details.pbx_count,admin_details.pbx_id,admin_details.agent_counts,admin_details.created_dt,admin_details.updated_dt,admin_details.has_contact,admin_details.has_sms,admin_details.has_chat,admin_details.ext_int_status,admin_details.whatsapp_num,admin_details.admin_status,admin_details.has_whatsapp,admin_details.has_chatbot,admin_details.has_fb,admin_details.has_wechat,admin_details.has_telegram,admin_details.has_internal_ticket,admin_details.has_external_ticket,admin_details.voice_3cx,admin_details.predective_dialer,admin_details.lead,admin_details.wallboard_one,admin_details.wallboard_two,admin_details.wallboard_three,admin_details.wallboard_four,admin_details.delete_status,admin_details.two_factor,admin_details.company_name,admin_details.domain_name,admin_details.has_fax,admin_details.has_external_contact,admin_details.reports,admin_details.external_contact_url,admin_details.has_internal_chat,admin_details.show_caller_id,admin_details.price_sms,admin_details.has_webinar,user.user_name,user.password FROM `admin_details` LEFT JOIN user on admin_details.admin_id = user.user_id WHERE admin_details.delete_status = 0";
	  }elseif($reseller!=''){
		    $qry = "SELECT admin_details.id,admin_details.name,admin_details.admin_id,admin_details.pbx_count,admin_details.pbx_id,admin_details.agent_counts,admin_details.created_dt,admin_details.updated_dt,admin_details.has_contact,admin_details.has_sms,admin_details.has_chat,admin_details.ext_int_status,admin_details.whatsapp_num,admin_details.admin_status,admin_details.has_whatsapp,admin_details.has_chatbot,admin_details.has_fb,admin_details.has_wechat,admin_details.has_telegram,admin_details.has_internal_ticket,admin_details.has_external_ticket,admin_details.voice_3cx,admin_details.predective_dialer,admin_details.lead,admin_details.wallboard_one,admin_details.wallboard_two,admin_details.wallboard_three,admin_details.wallboard_four,admin_details.delete_status,admin_details.two_factor,admin_details.company_name,admin_details.domain_name,admin_details.has_fax,admin_details.has_external_contact,admin_details.reports,admin_details.external_contact_url,admin_details.has_internal_chat,admin_details.show_caller_id,admin_details.price_sms,admin_details.has_webinar,user.user_name,user.password,user.webrtc_server  FROM `admin_details` LEFT JOIN user on admin_details.admin_id = user.user_id WHERE admin_details.delete_status = 0 and admin_details.admin_id IN ($reseller)";
		
	  }
	       return $this->dataFetchAll($qry, array());
        }
	
	
	  public function getsingleAdminData($id){
            
            //$qry = "SELECT * FROM `admin_details` WHERE id = '$id'";
		    //  return $this->dataFetchAll($qry, array());
            //  $qry = "SELECT admin_details.id,admin_details.name,admin_details.admin_id,admin_details.pbx_count,admin_details.pbx_id,admin_details.agent_counts,admin_details.created_dt,admin_details.updated_dt,admin_details.has_contact,admin_details.has_sms,admin_details.has_chat,admin_details.ext_int_status,admin_details.whatsapp_num,admin_details.admin_status,admin_details.has_whatsapp,admin_details.has_chatbot,admin_details.has_fb,admin_details.has_wechat,admin_details.has_telegram,admin_details.has_internal_ticket,admin_details.has_external_ticket,admin_details.voice_3cx,admin_details.predective_dialer,admin_details.lead,admin_details.wallboard_one,admin_details.wallboard_two,admin_details.wallboard_three,admin_details.wallboard_four,admin_details.delete_status,admin_details.two_factor,admin_details.company_name,admin_details.domain_name,admin_details.has_fax,admin_details.has_external_contact,admin_details.reports,admin_details.external_contact_url,admin_details.has_internal_chat,admin_details.show_caller_id,admin_details.price_sms,admin_details.fax_user_id, user.user_name,user.password,admin_details.survey_vid ,admin_details.tarrif_id,admin_details.whatsapp_type,admin_details.wp_instance_count FROM `admin_details` LEFT JOIN user on admin_details.admin_id = user.user_id WHERE admin_details.id = '$id' AND admin_details.delete_status = 0";
            // return $this->fetchData($qry, array());  
		  
		  
		   $qry = "SELECT admin_details.id,admin_details.has_kb,admin_details.name,admin_details.admin_id,admin_details.pbx_count,admin_details.pbx_id,admin_details.agent_counts,admin_details.created_dt,admin_details.updated_dt,admin_details.has_contact,admin_details.has_sms,admin_details.has_chat,admin_details.ext_int_status,admin_details.whatsapp_num,admin_details.admin_status,admin_details.has_whatsapp,admin_details.has_chatbot,admin_details.has_fb,admin_details.has_wechat,admin_details.has_telegram,admin_details.has_internal_ticket,admin_details.has_external_ticket,admin_details.voice_3cx,admin_details.predective_dialer,admin_details.lead,admin_details.wallboard_one,admin_details.wallboard_two,admin_details.wallboard_three,admin_details.wallboard_four,admin_details.wallboard_five,admin_details.wallboard_six,admin_details.delete_status,admin_details.two_factor,admin_details.company_name,admin_details.domain_name,admin_details.has_fax,admin_details.has_external_contact,admin_details.reports,admin_details.external_contact_url,admin_details.has_internal_chat,admin_details.show_caller_id,admin_details.price_sms,admin_details.fax_user_id, user.user_name,user.password,admin_details.survey_vid ,admin_details.reseller ,admin_details.tarrif_id,admin_details.whatsapp_type,admin_details.wp_instance_count,admin_details.mr_voip,admin_details.notes,admin_details.sms_type,admin_details.has_webinar,user.call_plan,user.call_rate,user.valid_from,user.valid_to,user.call_prefix,user.tax_name,user.tax_per,user.voice_manage,user.baisc_wallboard,user.wrap_up,user.queue,admin_details.wallboard_eight FROM `admin_details` LEFT JOIN user on admin_details.admin_id = user.user_id WHERE admin_details.id = '$id' AND admin_details.delete_status = 0";
		  
		 $admin_data = $this->fetchData($qry, array());
		  $id = $admin_data['admin_id'];
		  $qry = "SELECT * FROM whatsapp_instance_details WHERE admin_id='$id'";
  		  $inst_i = $this->dataFetchall($qry,array());
		  
		  $myArray = array("admin_data" => $admin_data,"instance_data" => $inst_i); 

             return    $myArray;
		  
		  
		  
        }
	
public  function EditSingleAdminsettings($admin_name,$pbx_count,$agent_count,$id,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$wallboard_five,$wallboard_six,$two_factor,$company_name,$domain_name,$has_fax,$has_internal_chat,$reports,$whatsapp_num,$price_sms,$survey_vid,$tarrif_id,$whatsapp_type,$wp_instance_count,$reseller,$sms_type,$mr_voip,$notes,$has_webinar,$plan_id,$call_rate,$valid_from,$valid_to,$call_prefix,$tax_name,$tax_per,$voice_manage,$baisc_wallboard,$wrap_up,$queue,$wallboard_eight,$has_kb){ 

  $admin_id_qry = "SELECT admin_id FROM admin_details WHERE id='$id'";
  $admin_id = $this->fetchOne($admin_id_qry,array());
 /* if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }  
  if($has_sms == 1 || $has_sms == true){ $has_smss = 1; }else{ $has_smss = 0; }//echo $has_smss;exit; 
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
  if($two_factor == 1 || $two_factor == true){ $two_factors = 1; }else{ $two_factors = 0; }
  if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chats = 1; }else{ $has_internal_chats = 0; } 
  if($has_fax == 1 || $has_fax == true){ $has_faxs = 1; }else{ $has_faxs = 0; }

  //if($has_wallboard == 1 || $has_wallboard == true){ $has_wallboards = 1; }else{ $has_wallboards = 0; } */
	//echo "UPDATE user SET has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',user_status='$admin_status',has_whatsapp='$has_whatsapp',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',lead='$lead',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',two_factor='$two_factor',company_name='$company_name',domain_name='$domain_name',has_fax='$has_fax',reports='$reports',has_internal_chat='$has_internal_chat',whatsapp_num='$whatsapp_num' WHERE user_id='$admin_id'";exit;
	
		if($wp_instance_count){
		$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='0',department_id=NULL,whatsapp_num=NULL,agent_name=NULL  where admin_id = $admin_id", array());
	$myArray = explode(',', $wp_instance_count);
		foreach($myArray as $inst_id){
			$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='$admin_id' where wp_inst_id = $inst_id", array());
		}
	} else {	
	$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='0',department_id=NULL,whatsapp_num=NULL,agent_name=NULL where admin_id = $admin_id", array());
	}
	
  $user_qry = "UPDATE user SET has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',user_status='$admin_status',has_whatsapp='$has_whatsapp',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',predective_dialer_behave='$predective_dialer',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',wallboard_five='$wallboard_five',wallboard_six='$wallboard_six',two_factor='$two_factor',company_name='$company_name',domain_name='$domain_name',has_fax='$has_fax',reports='$reports',has_internal_chat='$has_internal_chat',whatsapp_num='$whatsapp_num',price_sms='$price_sms',survey_vid='$survey_vid',tarrif_id='$tarrif_id',tarrif_id='$tarrif_id',whatsapp_type='$whatsapp_type',wp_instance_count='$wp_instance_count' ,reseller='$reseller',has_webinar='$has_webinar', call_plan='$plan_id',call_rate='$call_rate',valid_from='$valid_from',valid_to='$valid_to',call_prefix='$call_prefix',tax_name='$tax_name',tax_per='$tax_per',voice_manage='$voice_manage',baisc_wallboard='$baisc_wallboard',wrap_up='$wrap_up',queue='$queue',wallboard_eight='$wallboard_eight',has_kb='$has_kb' WHERE user_id='$admin_id'";
  $userqry_result = $this->db_query($user_qry, array());
	
	  $admin_qry = "UPDATE user SET has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',user_status='$admin_status',has_whatsapp='$has_whatsapp',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',predective_dialer_behave='$predective_dialer',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',wallboard_five='$wallboard_five',wallboard_six='$wallboard_six',two_factor='$two_factor',company_name='$company_name',domain_name='$domain_name',has_fax='$has_fax',has_internal_chat='$has_internal_chat',has_webinar='$has_webinar',voice_manage='$voice_manage',baisc_wallboard='$baisc_wallboard',wrap_up='$wrap_up',queue='$queue',wallboard_eight='$wallboard_eight',survey_vid='$survey_vid',has_kb='$has_kb' WHERE admin_id='$admin_id'";
  $userqry_result = $this->db_query($admin_qry, array());
	
	
  $qry = "UPDATE admin_details SET name='$admin_name',pbx_count='1',agent_counts='$agent_count',has_contact='$has_contact',has_sms='$has_sms',has_chat='$has_chat',admin_status='$admin_status',has_whatsapp='$has_whatsapp',has_chatbot='$has_chatbot',has_fb='$has_fb',has_wechat='$has_wechat',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer',predective_dialer_behave='$predective_dialer',wallboard_one='$wallboard_one',wallboard_two='$wallboard_two',wallboard_three='$wallboard_three',wallboard_four='$wallboard_four',wallboard_five='$wallboard_five',wallboard_six='$wallboard_six',two_factor='$two_factor',company_name='$company_name',domain_name='$domain_name',whatsapp_num='$whatsapp_num',has_fax='$has_fax',reports='$reports',has_internal_chat='$has_internal_chat',price_sms='$price_sms',survey_vid='$survey_vid',tarrif_id='$tarrif_id',whatsapp_type='$whatsapp_type',wp_instance_count='$wp_instance_count' ,reseller='$reseller',sms_type='$sms_type',mr_voip='$mr_voip',notes='$notes',has_webinar='$has_webinar',voice_manage='$voice_manage',baisc_wallboard='$baisc_wallboard',wrap_up='$wrap_up',queue='$queue',wallboard_eight='$wallboard_eight',has_kb='$has_kb'  where id='$id'";
//echo $qry;exit;
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;    
}
public function AddSingleAdmin($name,$pbx_count,$agent_counts,$user_name,$user_password,$has_contact,$has_sms,$has_chat,$admin_status,$has_whatsapp,$has_chatbot,$has_fb,$has_wechat,$has_telegram,$has_line,$has_internal_ticket,$has_external_ticket,$voice_3cx,$predective_dialer,$lead,$wallboard_one,$wallboard_two,$wallboard_three,$wallboard_four,$wallboard_five,$wallboard_six,$two_factor,$company_name,$domain_name,$has_fax,$has_internal_chat,$reports,$price_sms,$survey_vid,$tarrif_id,$whatsapp_type,$wp_instance_count,$reseller,$user_id,$sms_type,$mr_voip,$notes,$has_webinar,$plan_id,$call_rate,$valid_from,$valid_to,$call_prefix,$tax_name,$tax_per,$voice_manage,$baisc_wallboard,$wrap_up,$queue,$wallboard_eight,$has_kb){ //echo $price_sms;exit;   
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
   /* if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }
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
  if($two_factor == 1 || $two_factor == true){ $two_factors = 1; }else{ $two_factors = 0; }
  if($has_fax == 1 || $has_fax == true){ $has_faxs = 1; }else{ $has_faxs = 0; }
  if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chats = 1; }else{ $has_internal_chats = 0; }
  //if($has_wallboard == 1 || $has_wallboard == true){ $has_wallboards = 1; }else{ $has_wallboards = 0; }*/
	
     if($user_id=='0'){
//echo '123';exit;
     $qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,has_contact,has_sms,has_chat,user_status,admin_id,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,layout,theme,lead_token,password,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,wallboard_five,wallboard_six,two_factor,company_name,domain_name,has_fax,has_internal_chat,reports,price_sms,survey_vid,tarrif_id,whatsapp_type,wp_instance_count,reseller,has_webinar,call_plan,call_rate,valid_from,valid_to,call_prefix,tax_name,tax_per,voice_manage,baisc_wallboard,wrap_up,queue,wallboard_eight,has_kb) VALUES ('$user_name', '$user_password','2','$name','$has_contacts','$has_smss','$has_chats','1','1','$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','dark','black','$lead_token','$password','$voice_3cxs','$predective_dialers','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours','$wallboard_fives','$wallboard_sixs','$two_factors','$company_name','$domain_name','$has_faxs','$has_internal_chats','$reports','$price_sms','$survey_vid','$tarrif_id','$whatsapp_type','$wp_instance_count','$reseller','$has_webinar','$plan_id','$call_rate','$valid_from','$valid_to','$call_prefix','$tax_name','$tax_per','$voice_manage','$baisc_wallboard','$wrap_up','$queue','$wallboard_eight','$has_kb')";
	   //print_r($qry);exit;
    $qry_result = $this->db_query($qry, array());


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
		    if($wallboard_five == 1 || $wallboard_five == true){ $wallboard_fives = 1; }else{ $wallboard_fives = 0; }
		    if($wallboard_six == 1 || $wallboard_six == true){ $wallboard_sixs = 1; }else{ $wallboard_sixs = 0; }
    if($two_factor == 1 || $two_factor == true){ $two_factors = 1; }else{ $two_factors = 0; }
    if($has_fax == 1 || $has_fax == true){ $has_faxs = 1; }else{ $has_faxs = 0; }
    if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chats = 1; }else{ $has_internal_chats = 0; }
      if($has_webinar == 1 || $has_webinar == true){ $has_webinar = 1; }else{ $has_webinar = 0; }
      if($has_kb == 1 || $has_kb == true){ $has_kb = 1; }else{ $has_kb = 0; }
      //if($has_wallboard == 1 || $has_wallboard == true){ $has_wallboards = 1; }else{ $has_wallboards = 0; }
		  
		 if($wp_instance_count > 0){ 
		  $myArray = explode(',', $wp_instance_count);
		foreach($myArray as $inst_id){
			$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='$result',department_id=NULL,whatsapp_num=NULL,agent_name=NULL where wp_inst_id = $inst_id", array());
		}
		 }
        $qry = "INSERT INTO admin_details(name,admin_id,pbx_count,agent_counts,has_contact,has_sms,has_chat,admin_status,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,wallboard_five,wallboard_six,two_factor,company_name,domain_name,has_fax,has_internal_chat,reports,price_sms,survey_vid,tarrif_id,whatsapp_type,wp_instance_count,reseller,sms_type,mr_voip,notes,has_webinar,voice_manage,baisc_wallboard,wrap_up,queue,wallboard_eight,has_kb) VALUES ('$name', '$result','1','$agent_counts','$has_contacts','$has_smss','$has_chats',1,'$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','$voice_3cxs','$predective_dialers','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours','$wallboard_fives','$wallboard_sixs','$two_factors','$company_name','$domain_name','$has_faxs','$has_internal_chats','$reports','$price_sms','$survey_vid','$tarrif_id','$whatsapp_type','$wp_instance_count','$reseller','$sms_type','$mr_voip','$notes','$has_webinar','$voice_manage','$baisc_wallboard','$wrap_up','$queue','$wallboard_eight','$has_kb')";
   $qry_result = $this->db_query($qry, array()); 
          $result = $qry_result == 1 ? 1 : 0;
		 // if($qry_result!='' || $qry_result!='0'){
		//	  $result='1';
		  //}else{
			//$result='0';  
		 // }
             $updateqry2 = " Update user SET `lead_token` = '$lead_token' WHERE admin_id='$aid'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
		  
		  
	  
            return $result;
      }
   }
	
   }else{
		//echo '2334';exit;
     $qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,has_contact,has_sms,has_chat,user_status,admin_id,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,layout,theme,lead_token,password,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,wallboard_five,wallboard_six,two_factor,company_name,domain_name,has_fax,has_internal_chat,reports,price_sms,survey_vid,tarrif_id,whatsapp_type,wp_instance_count,reseller,has_webinar,call_plan,call_rate,valid_from,valid_to,call_prefix,tax_name,tax_per,voice_manage,baisc_wallboard,wrap_up,queue,wallboard_eight,has_kb) VALUES ('$user_name', '$user_password','2','$name','$has_contacts','$has_smss','$has_chats','1','1','$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','dark','black','$lead_token','$password','$voice_3cxs','$predective_dialers','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours','$wallboard_fives','$wallboard_sixs','$two_factors','$company_name','$domain_name','$has_faxs','$has_internal_chats','$reports','$price_sms','$survey_vid','$tarrif_id','$whatsapp_type','$wp_instance_count','$reseller','$has_webinar','$plan_id','$call_rate','$valid_from','$valid_to','$call_prefix','$tax_name','$tax_per','$voice_manage','$baisc_wallboard','$wrap_up','$queue','$wallboard_eight','$has_kb')";
	   //print_r($qry);exit;
   $qry_result = $this->db_insert($qry, array());
$insertd_id=$qry_result;

   if($qry_result > 1){
	     $qryss = "SELECT reseller FROM user where user_id = '$user_id'";
    $reseller_val = $this->fetchOne($qryss, array());
	   $new_reseller=$reseller_val.','.$insertd_id;
	   $qry_result = $this->db_query("UPDATE user SET reseller='$new_reseller' where user_id = $user_id", array());
	   
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
		     if($wallboard_five == 1 || $wallboard_five == true){ $wallboard_fives = 1; }else{ $wallboard_fives = 0; }
		    if($wallboard_six == 1 || $wallboard_six == true){ $wallboard_sixs = 1; }else{ $wallboard_sixs = 0; }
    if($two_factor == 1 || $two_factor == true){ $two_factors = 1; }else{ $two_factors = 0; }
    if($has_fax == 1 || $has_fax == true){ $has_faxs = 1; }else{ $has_faxs = 0; }
    if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chats = 1; }else{ $has_internal_chats = 0; }
		  if($has_webinar == 1 || $has_webinar == true){ $has_webinar = 1; }else{ $has_webinar = 0; }
		  if($has_kb == 1 || $has_kb == true){ $has_kb = 1; }else{ $has_kb = 0; }
      //if($has_wallboard == 1 || $has_wallboard == true){ $has_wallboards = 1; }else{ $has_wallboards = 0; }
		  
		 if($wp_instance_count > 0){ 
		  $myArray = explode(',', $wp_instance_count);
		foreach($myArray as $inst_id){
			$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='$result',department_id=NULL,whatsapp_num=NULL,agent_name=NULL where wp_inst_id = $inst_id", array());
		}
		 }
        $qry = "INSERT INTO admin_details(name,admin_id,pbx_count,agent_counts,has_contact,has_sms,has_chat,admin_status,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,wallboard_one,wallboard_two,wallboard_three,wallboard_four,wallboard_five,wallboard_six,two_factor,company_name,domain_name,has_fax,has_internal_chat,reports,price_sms,survey_vid,tarrif_id,whatsapp_type,wp_instance_count,reseller,sms_type,mr_voip,notes,has_webinar,voice_manage,baisc_wallboard,wrap_up,queue,wallboard_eight,has_kb) VALUES ('$name', '$result','1','$agent_counts','$has_contacts','$has_smss','$has_chats',1,'$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','$voice_3cxs','$predective_dialers','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours','$wallboard_fives','$wallboard_sixs','$two_factors','$company_name','$domain_name','$has_faxs','$has_internal_chats','$reports','$price_sms','$survey_vid','$tarrif_id','$whatsapp_type','$wp_instance_count','$reseller','$sms_type','$mr_voip','$notes','$has_webinar','$voice_manage','$baisc_wallboard','$wrap_up','$queue','$wallboard_eight','$has_kb')";
   $qry_result = $this->db_insert($qry, array()); 
          $insert_val = $qry_result ;
		  if($insert_val!='' || $insert_val!='0'){
			  $result='1';
		  }else{
			$result='0';  
		  }
		  
		   $qry_result = $this->db_query("UPDATE admin_details SET reseller='$new_reseller' where admin_id = $user_id", array());
		  
             $updateqry2 = " Update user SET `lead_token` = '$lead_token' WHERE admin_id='$aid'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
		  
		  
	  
            return $result;
      }
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
	
/*function toggle_status($data){    
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
	if($status_for=='two_factor'){
      if($results['two_factor']=='0'){
        $value=$two_factor='1';
      }else{$value=$two_factor='0';}    
    }
	if($status_for=='has_fax'){
      if($results['has_fax']=='0'){
        $value=$has_fax='1';
      }else{$value=$has_fax='0';}    
    }
	if($status_for=='has_external_contact'){
      if($results['has_external_contact']=='0'){
        $value=$has_external_contact='1';
      }else{$value=$has_external_contact='0';}    
    }
	if($status_for=='has_internal_chat'){
      if($results['has_internal_chat']=='0'){
        $value=$has_internal_chat='1';
      }else{$value=$has_internal_chat='0';}    
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
	if($status_for=='two_factor'){
      if($userdata['two_factor']=='0'){
        $keyword=$two_factor='1';
      }else{$keyword=$two_factor='0';}    
    }
	if($status_for=='has_fax'){
      if($userdata['has_fax']=='0'){
        $keyword=$has_fax='1';
      }else{$keyword=$has_fax='0';}    
    }
	if($status_for=='has_external_contact'){
      if($userdata['has_external_contact']=='0'){
        $keyword=$has_external_contact='1';
      }else{$keyword=$has_external_contact='0';}    
    }
	if($status_for=='has_internal_chat'){
      if($userdata['has_internal_chat']=='0'){
        $keyword=$has_internal_chat='1';
      }else{$keyword=$has_internal_chat='0';}    
    }
    $user_update_qry = " Update user SET $status_for='$keyword' where user_id='$aid'";
    $parms = array();
    $user_update_qry_results = $this->db_query($user_update_qry,$parms);
    $qry = " Update admin_details SET $status_for='$value' where id='$id'";
    $parms = array();
    $results = $this->db_query($qry,$parms);
    $output = $results == 1 ? 1 : 0;
    return  $output;
  }*/
	
public function take_down_toggle_status($data){  
  extract($data);
    $qry_select="SELECT * FROM admin_details where id='$id'";
    $parms = array();
    $results = $this->fetchdata($qry_select,$parms);    
	$admin_id = $results['admin_id'];
	$admin_status = $results['admin_status'];
	
 	if($results['admin_status']=='0'){
        $status='1';
      } else {
		$status='0';
	}   

    $qry = "Update admin_details SET admin_status='$status' where id='$id'";
    $parms = array();
    $results = $this->db_query($qry,$parms);
	
    $user_update_qry = "Update user SET user_status='$status' where user_id='$admin_id'";
    $parms = array();
    $user_update_qry_results = $this->db_query($user_update_qry,$parms);
	

	$user_update_qry = "Update user SET user_status='$status' where admin_id='$admin_id'";
    $parms = array();
    $user_update_qry_results = $this->db_query($user_update_qry,$parms);
	
	$output = $results == 1 ? 1 : 0;
    return  $output;


}


public function toggle_status($data){    
    extract($data);
    $qry_select="SELECT * FROM admin_details where id='$id'";
    $parms = array();
    $results = $this->fetchdata($qry_select,$parms);     
    
    if($status_for=='admin_status'){
      if($results['admin_status']=='0'){
        $value=$admin_status='1';
      }else{$value=$admin_status='0';}    
    }
    $adminidqry_select="SELECT admin_id FROM admin_details where id='$id'";
    $parms = array();
    $adminidqry = $this->fetchsingledata($adminidqry_select,$parms);
    $aid = $adminidqry['admin_id'];
    $user_qry_select="SELECT * FROM user where user_id='$aid'";
    $parms = array();
    $userdata = $this->fetchdata($user_qry_select,$parms);         
    if($status_for=='user_status'){
      if($userdata['user_status']=='0'){
        $keyword=$user_status='1';
      }else{$keyword=$user_status='0';}    
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
	if($keyword=='wallboard_five'){
		  if($results['wallboard_five']=='0'){
			$value=$wallboard_five='1';
		  }else{$value=$wallboard_five='0';}    
		}
if($keyword=='wallboard_six'){
		  if($results['wallboard_six']=='0'){
			$value=$wallboard_six='1';
		  }else{$value=$wallboard_six='0';}    
		}
		if($keyword=='two_factor'){
		  if($results['two_factor']=='0'){
			$value=$two_factor='1';
		  }else{$value=$two_factor='0';}    
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
      $qry = "DELETE from user WHERE user_id='$user_id' AND admin_id='$admin_id'";
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
		 $show_caller_id = $results['show_caller_id'];
		$has_video_call = $results['has_video_call'];	
		$dialer_ring = $results['dialer_ring'];
		$webrtc_server = $results['webrtc_server'];
//print_r($results);exit;
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
		$user_dialer_options = array('dialer_option' => $dialer, 'dialer_value' => $ext_int_status, 'show_caller_id'=> $show_caller_id,'has_video_call'=>$has_video_call,'dialer_ring'=>$dialer_ring,'webrtc_server'=>$webrtc_server);
		$user_timezone = array('user_timezone' => $timezone_id);
		$timezone_options_array = array('timezone_options' => $timezone_options_array);
		$logo_option_array = array('logo_image' => $logo_image, 'small_logo_image' => $small_logo_image);
		$finalresult = array_merge($status, $user_dialer_options, $user_timezone, $timezone_options_array, $logo_option_array);
		$tarray = json_encode($finalresult);
		print_r($tarray);exit;
  	}
	/*public function forgot_password($data){
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
    }*/
	public function choose_marketplace($data){
    extract($data);     
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
  public function check_license($data){
    extract($data);//print_r($data);exit;
    $element_data = array('action' => 'get_hardwareid','license_key'=>$license_key);
    $api_request_data = array('operation'=>'login','moduleType'=>'login','api_type'=>'web','element_data'=>$element_data);
     $crl = $this->hardware_curl($api_request_data);
	 if($crl != ''){	//echo "if";exit;	 
	  $qry_result = $this->db_query("UPDATE user SET hardware_id='$crl' WHERE user_id='$user_id'", array());
	  $agentqry_result = $this->db_query("UPDATE user SET hardware_id='$crl' WHERE admin_id='$user_id'", array());
      $result = array("value"=>'1',"hardware_id"=>$crl);
      return $result;
	 }else{//echo "el";exit;	
		$result = 0;
        return $result; 
	 }
  }
  public function check_hardware($data){
    extract($data);
    $hardware_id_qry = "SELECT hardware_id FROM user WHERE user_id='$user_id'";
	 // echo $hardware_id_qry;exit;
    $hardware_id = $this->fetchOne($hardware_id_qry,array());
    if($hardware_id==''){
      $result = 0;
      return $result;
    }
    else{
     //$element_data = array('action' => 'check_hardware_status','hardware_id'=>$hardware_id);
     //$api_request_data = array('operation'=>'login','moduleType'=>'login','api_type'=>'web','element_data'=>$element_data);
	 $api_request_data = array('action' => 'check_hardware_status','hardware_id'=>$hardware_id);	
     $crl = $this->hardware_status_curl($api_request_data);
	 if($crl != ''){	  
      $result = array("value"=>'1');
      return $result;
	 }else{
		$result = 0;
        return $result; 
	 }	
   }
  }
  /*public function check_license($data){
    extract($data);  
	$out = $this->erp_curl($license_key);
	 //echo $out;exit;
    if($out != ''){       
      $qry_result = $this->db_query("UPDATE user SET hardware_id='$out' WHERE user_id='$user_id'", array());
      $agentqry_result = $this->db_query("UPDATE user SET hardware_id='$out' WHERE admin_id='$user_id'", array());
      $result = array("value"=>'1',"hardware_id"=>$out);
      return $result;
    }else{
     $result = 0;
     return $result; 
    }
  }
  public function check_hardware($data){
    extract($data);
    $hardware_id_qry = "SELECT hardware_id FROM user WHERE user_id='$user_id'";
    $hardware_id = $this->fetchOne($hardware_id_qry,array());
    if($hardware_id==''){
      $result = 0;
      return $result;
    }
    else{    
     $data = array("api_user"=>"Cal4careCms", "api_pass"=>"16c21a5c08758dc10dadb11b7c8cc182", "access"=>"omni_site", "page_access"=>"view_page","action_info"=>"check_hardware_status", "hardware_id"=>$hardware_id);
     $post_data=json_encode($data);
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_URL,"https://dev.cal4care.com/erp/data-services/index.php");
     curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);                      
     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
     curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
     curl_setopt($curl, CURLOPT_TIMEOUT, 60);
     curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
     $out=curl_exec($curl);
     $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     curl_close($curl);
     if($out != ''){    
      $result = array("value"=>'1');
      return $result;
     }else{
      $result = 0;
      return $result; 
     }  
    }
  }	*/
  public function add_report($data){
      extract($data);      
      $get_qry = "SELECT * FROM reports WHERE report_name LIKE LIKE '%$report_name%'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = 0;
        return $result;
      }
      else{   
        //$qry_result = $this->db_query("INSERT INTO reports(report_name) VALUES ( '$report_name')", array());
		  $qry_result = $this->db_query("INSERT INTO reports(report_name,report_url) VALUES ('$report_name','$report_url')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
  }
	public function edit_report($data){
      extract($data);      
      $get_qry = "SELECT * FROM reports WHERE id='$id'";
      $result = $this->fetchData($get_qry,array());
	  $result_count = $this->dataRowCount($get_qry,array());	
      if($result_count > 0){        
        return $result;
      }
      else{          
        $result = 0;
        return $result;
      }
  }
  public function update_report($data){
      extract($data);      
      $get_qry = "SELECT * FROM reports WHERE id='$id'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){ 
        //$qry = $this->db_query("UPDATE reports SET report_name='$report_name' WHERE id='$id'", array());
		  $qry = $this->db_query("UPDATE reports SET report_name='$report_name',report_url='$report_url' WHERE id='$id'", array());
        $result = $qry == 1 ? 1 : 0;      
        return $result;
      }
      else{          
        $result = 0;
        return $result;
      }
  }
  public function list_report($data){
	  extract($data);
	  if($user_id){
	  if($user_id=='1'){
      $qry = "select * from reports";
		  }else{
		   $sql="SELECT reports from user where user_id='$user_id'";
		   $rep = $this->fetchOne($sql, array()); 
		 $qry="SELECT * from reports where id IN($rep)";
	  }
		  }else{
		  $qry = "select * from reports";
	  }
      return $this->dataFetchAll($qry, array());
  }
	
  public function add_fax_user($user_name,$password,$first_name,$last_name,$phone,$email,$address,$country,$timezone_id,$company_name,$daily_limit,$monthly_limit){
	  //echo $company_name;exit;
      $name = $first_name.$last_name;
      $password = $password;
      $user_password = MD5(trim($password));
      $random_number = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
      $lead_token = $name."_".$random_number;  
      $qry = "select * from user where user_name ='$user_name'";
      $qry_result = $this->dataFetchAll($qry, array()); 
      $result_count = $this->dataRowCount($qry, array());
      if($result_count > 0){
          $result = 3;
          return $result;
      }
	  else {
      $token = $this->fax_curl();
      $data = array("username"=>$user_name,
          "password"=>$password,
          "first_name"=>$first_name,
          "last_name"=>$last_name,
          "phone"=>$phone,
          "email"=>$email,
          "address"=>$address,
          "company"=>$company_name,
          "active"=>"1",
          "daily_limit"=>"100",
          "monthly_limit"=>"1000");
		  //print_r($data);exit;
      $userid = $this->add_fax_user_curl($data, $token);        
      $res = $this->add_fax_user_role($userid, $token);  
	  if($userid != 0){
      //$qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,user_status,admin_id,email_id,phone_number,layout,theme,company_name,reports,password,fax_user_id,fax_first_name,fax_last_name) VALUES ('$user_name','$user_password','2','$name','1','1','$email','$phone','dark','black','$company_name','1,2,3,4,5,6,7,8,9,10','$password','$userid','$first_name','$last_name')";
	  $qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,user_status,admin_id,email_id,phone_number,layout,theme,company_name,reports,password,fax_user_id,fax_first_name,fax_last_name,fax_user_address,timezone_id,fax_daily_limit,fax_monthly_limit) VALUES ('$user_name','$user_password','2','$name','1','1','$email','$phone','dark','black','$company_name','1,2,3,4,5,6,7,8,9,10','$password','$userid','$first_name','$last_name','$address','$timezone_id','$daily_limit','$monthly_limit')";	  
      $qry_result = $this->db_query($qry, array());
      //print_r($qry_result);
      if($qry_result == 1){
            $user_id_qry = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
            $user_id_value = $this->fetchOne($user_id_qry, array());                        
            if($user_id_value){              
              $qry = "INSERT INTO admin_details(name,admin_id,pbx_count,agent_counts,admin_status,company_name,support_email,reports,fax_user_id,fax_first_name,fax_last_name,fax_user_address,fax_daily_limit,fax_monthly_limit) VALUES ('$name','$user_id_value','2','2','1','$company_name','$email','1,2,3,4,5,6,7,8,9,10','$userid','$first_name','$last_name','$address','$daily_limit','$monthly_limit')";
              $qry_result = $this->db_query($qry, array()); 
              $result = $qry_result == 1 ? 1 : 0;
                 $updateqry2 = " Update user SET `lead_token` = '$lead_token' WHERE admin_id='$user_id_value'";
                $parms = array();
                $results2 = $this->db_query($updateqry2,$parms);
                return $result;
            }         
      }
	  }
		else{
			$result = 0;
			return $result;
		}
      }
    }

	public function list_fax_users($data){
		extract($data);
		//$user_id='711';
		if($user_id=='0'){		
      $qry = "SELECT * FROM user WHERE fax_user_id !=''";
      return $this->dataFetchAll($qry, array());
		}else{
		$reseller=$this->fetchOne("SELECT reseller FROM user where user_id='$user_id'", array());
		$new_reseller=$user_id.','.$reseller;
		//$new_reseller='475,477';
		$qry = "SELECT * FROM user WHERE fax_user_id !='' and IF(admin_id = 1, user_id, admin_id) IN ($new_reseller)";
		return $this->dataFetchAll($qry, array());

		}
    }
	 public function edit_fax_user($user_id){    
      $qry = "SELECT user_id,fax_user_id,user_name,fax_first_name,fax_last_name,email_id,phone_number,fax_user_address,fax_daily_limit,fax_monthly_limit,timezone_id,company_name FROM user WHERE user_id ='$user_id'";
      $result = $this->fetchData($qry,array());
      $result_count = $this->dataRowCount($qry,array());  
      if($result_count > 0){        
        return $result;
      }
      else{          
        $result = 0;
        return $result;
      }
  }
  public function update_fax_user($user_id,$fax_user_id,$user_name,$first_name,$last_name,$phone,$email,$address,$company_name,$daily_limit,$monthly_limit,$timezone_id){     
      $token = $this->fax_curl();
      $data = array("username"=>$user_name,
          //"password"=>$user_password,
          "first_name"=>$first_name,
          "last_name"=>$last_name,
          "phone"=>$phone,
          "email"=>$email,
          "address"=>$address,
          "company"=>$company_name,
          "active"=>"1",
          "daily_limit"=>$daily_limit,
          "monthly_limit"=>$monthly_limit);//print_r($data);exit;
      $res = $this->update_fax_user_curl($token, $fax_user_id, $data);//print_r($res);exit;
      $fax_user_id = $res['user_id'];
      $first_name = $res['first_name'];
      $last_name = $res['last_name'];
      $phone = $res['phone'];
      $email = $res['email'];
      $address = $res['address'];
      $company_name = $res['company'];
      $daily_limit = $res['daily_limit'];
      $monthly_limit = $res['monthly_limit'];
      $timezone_id = $res['timezone_id'];
	  //echo "UPDATE user SET fax_user_id='$fax_user_id',fax_first_name='$first_name',fax_last_name='$last_name',phone_number='$phone',email_id='$email',fax_user_address='$address',company_name='$company_name',fax_daily_limit='$daily_limit',fax_monthly_limit='$monthly_limit',timezone_id='$timezone_id' WHERE user_id='$user_id'";exit;
      $qry = "UPDATE user SET fax_user_id='$fax_user_id',fax_first_name='$first_name',fax_last_name='$last_name',phone_number='$phone',email_id='$email',fax_user_address='$address',company_name='$company_name',fax_daily_limit='$daily_limit',fax_monthly_limit='$monthly_limit',timezone_id='$timezone_id' WHERE user_id='$user_id'";
       $qry_result = $this->db_query($qry, array());
       $result = $qry_result == 1 ? 1 : 0;
	  //echo $result;exit;
       if($result==1){
          $adminqry = "UPDATE admin_details SET fax_user_id='$fax_user_id',fax_first_name='$first_name',fax_last_name='$last_name',support_email='$email',fax_user_address='$address',company_name='$company_name',fax_daily_limit='$daily_limit',fax_monthly_limit='$monthly_limit' WHERE admin_id='$user_id'";
          $adminqry_result = $this->db_query($adminqry, array());
          $result = $adminqry_result == 1 ? 1 : 0;
          return $result;
       }else{
          $result = 0;
          return $result;
       }             
  }
  public function delete_fax_user($data){
      extract($data); 
      $token = $this->fax_curl();
      $res = $this->delete_fax_user_curl($token, $fax_user_id);
	 // echo $res;exit;
      if($res=='true'){//echo "DELETE FROM admin_details WHERE admin_id='$user_id' AND fax_user_id='$fax_user_id'";exit;
        $delete_admintable_qry = "DELETE FROM admin_details WHERE admin_id='$user_id' AND fax_user_id='$fax_user_id'";
        $results = $this->db_query($delete_admintable_qry, array());
        $result = $results == 1 ? 1 : 0;//echo $result;exit;
        if($result==1){//echo "1";exit;
           $delete_usertable_qry = "DELETE FROM user WHERE user_id='$user_id'";
           $results_qry = $this->db_query($delete_usertable_qry, array());
		   $delete_agent_qry = "DELETE FROM user WHERE admin_id='$user_id'";
           $res_qry = $this->db_query($delete_agent_qry, array());	
           $output = $results_qry == 1 ? 1 : 0;
           return $output;
        }else{//echo "2";exit;
           $output = 0;
           return $output;
        }
      }else{
           $output = 0;
           return $output;
        }          
  }
	/*public function user_log_out($user_id){
        $qry_result = $this->db_query("UPDATE user SET login_status=0,sip_registered_status=0 WHERE user_id='$user_id'", array());            
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
    }*/
	public function user_log_out($user_id){
        $qry_result = $this->db_query("UPDATE user SET login_status=0,sip_registered_status=0 WHERE user_id='$user_id'", array());                
        $result = $qry_result == 1 ? 1 : 0;
	    $this->db_query("UPDATE user SET notification_code='', android_token='' WHERE user_id='$user_id'", array());
        return $result;
  }
	 public function update_external_contact($data){//print_r($data);
		 extract($data);		 
        $qry_result = $this->db_query("UPDATE user SET has_external_contact='$has_external_contact',crm_type='$crm_type',external_contact_url='$external_contact_url' WHERE user_id='$user_id'", array());       
        $result = $qry_result == 1 ? 1 : 0;        
        if($result==1){
          $qry_result = $this->db_query("UPDATE admin_details SET has_external_contact='$has_external_contact',crm_type='$crm_type',external_contact_url='$external_contact_url' WHERE admin_id='$user_id'", array());   
          $result = $qry_result == 1 ? 1 : 0;
          return $result;
        }else{
          $result = 0;
          return $result;
        }
    }
	
  public function sip_registered_status($user_id, $status){
        $qry_result = $this->db_query("UPDATE user SET sip_registered_status='$status' WHERE user_id='$user_id'", array());                
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
  }	
public  function whatsapp_account_settings($data){  
      extract($data);             
      $qry = "UPDATE user SET whatsapp_account='$whatsapp_account' WHERE user_id='$user_id'";
      $qry_result = $this->db_query($qry, array());
      $results = $qry_result == 1 ? 1 : 0;
      if($results==1){
         $adminqry = "UPDATE admin_details SET whatsapp_account='$whatsapp_account' WHERE admin_id='$user_id'";
         $adminqry_result = $this->db_query($adminqry, array());
         $result = $qry_result == 1 ? 1 : 0;
        if($result==1){
         return $result;
        }else{
          $result = 0;return $result;
        }
      }
      else{
          $result = 0;return $result;
        }          
    }
	public  function notification_code($data){  
      extract($data);//print_r($data);
		//echo "UPDATE user SET notification_code='$notification_code' WHERE user_id='$user_id'";
		//exit;             
      $qry = "UPDATE user SET notification_code='$notification_code' WHERE user_id='$user_id'";
      $qry_result = $this->db_query($qry, array());
      $results = $qry_result == 1 ? 1 : 0;      
      return $results;      
    }
	public  function android_token($data){  
      extract($data);//print_r($data);
	   if($notification_code == '' || $notification_code == 'null'){
	   return false;  
	   }
      $qry = "UPDATE user SET android_token='$notification_code' WHERE user_id='$user_id'";
      $qry_result = $this->db_query($qry, array());
      $results = $qry_result == 1 ? 1 : 0;      
      return $results;      
    }


	public  function curl_response($data){  
      extract($data);//print_r($data);exit;  
	  //echo $url;exit;
		$post = array("q"=>"63401005","hapikey"=>"ed15f3fa-87c4-4169-a555-6bb845c257e9");
		$dat = json_encode($post);
		$curl_handle=curl_init();
		  curl_setopt($curl_handle,CURLOPT_URL,$url);
		  curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		  curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		  $buffer = curl_exec($curl_handle);
		  curl_close($curl_handle);
		  if (empty($buffer)){
			  print "Nothing returned from url.<p>";
		  }
		  else{
			  print $buffer;
		  }exit;
		//print_r($result);exit;
      //return $result;      
    }
	
	public  function curl_response_zoho_desk($data){  
      	extract($data);
		//  $number = base64_decode($number);
		$url = 'https://desk.zoho.com/api/v1/search?module=contacts&searchStr='.$number;
    	$request_headers = array(
                    "Authorization:" . $authkey,
                    "orgId:" . $orgID
				);
				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    	$season_data = curl_exec($ch);
		if (curl_errno($ch)) {
			print "Error: " . curl_error($ch);
			exit();
		}
		curl_close($ch);
		$json = $season_data;
	
    	$json = json_decode($json);
		return $json->data[0]->webUrl;

    }
				  
				  
				  
	public function dialer_settings($login){
		
				  
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
		$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;
		$timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());			  
		$sip_login =base64_encode($user_details['sip_login']);
		$sip_username = base64_encode($user_details['sip_username']);
		$sip_password = base64_encode($user_details['sip_password']);
		$hardware_id = base64_encode($user_details['hardware_id']);
		$sip_url = $this->fetchOne("select sip_url from pbx_settings where admin_id='$admin_id'",array());	
		$sip_port = $this->fetchOne("select sip_port from pbx_settings where admin_id='$admin_id'",array());	
		$qry = "select status,reason from log_details where agent_id ='".$user_id."' order by id desc ";
        $parms = array();
        $results = $this->fetchdata($qry,$parms);
		
		
		
   		$auxcode = "select * from aux_code where delete_status != 1 and admin_id IN ($admin_id,1)";
		$auxcode =  $this->dataFetchAll($auxcode,$parms);

 		  $result = array("sipusername"=>$sip_username,"sippassword"=>$sip_password,"sipurl"=>base64_encode($sip_url),"sipport"=>base64_encode($sip_port),"sip_login"=>$sip_login,"hardware_id"=>$hardware_id);
          $status = array("status"=>"true");
		  $sip_details_array = array("data"=>$result,"queueData"=>$results);	
          $merge_array=array_merge($status, $sip_details_array); 	
		  $merge_array['wrapUpCode'] = $auxcode;
          $tarray = json_encode($merge_array);           
          print_r($tarray);exit;
    }
	
function welcome_email($chat_data){        
      extract($chat_data);    
		$host_name='https://'.$_SERVER['HTTP_HOST'];

      $chat_details_qry = "select * from user where user_id='$user_id' AND email_id='$email'";
      $chat_details = $this->fetchData($chat_details_qry, array());
	  $count = $this->dataRowCount($chat_details_qry, array());	
if($count > 0){
    $user_name = $chat_details['user_name'];
    $user_pwd = $chat_details['password'];
    $email = $chat_details['email_id'];
	  $company_name = $chat_details['company_name'];    
    $has_fb = $chat_details['has_fb'];
    $has_whatsapp = $chat_details['has_whatsapp'];
    $has_line = $chat_details['has_fax'];
    $has_sms = $chat_details['has_sms'];
	  $admin_id = $chat_details['admin_id'];
	  if($admin_id==1){
		  $aid = $user_id;
	  }else{
		  $aid = $admin_id;
    } 
	//NKH welcome email
    if($aid== 341 || $aid==835){
      $email_url='https://nkh.asia/';
    }
    elseif($aid ==612){
    $email_url='https://newubin1.mconnectapps.com/';
    }
    else {
		
      $email_url='https://'.$host_name.'/#';
    }
   // echo $email_url;exit;
	  $admin_qry = "select name from admin_details where admin_id='$aid'";
      $admin_name = $this->fetchOne($admin_qry, array());
      require_once('class.phpmailer.php'); 
      $customer_email = 'noreply@mconnectapps.com';   
      $from = 'Omni Channel';
      $subject = "Welcome Email From Omni Channel";
      $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
	<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<![endif]-->
	<title></title>
	<!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<!--<![endif]-->
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
		}

		table,
		td,
		tr {
			vertical-align: top;
			border-collapse: collapse;
		}

		* {
			line-height: inherit;
		}

		a[x-apple-data-detectors=true] {
			color: inherit !important;
			text-decoration: none !important;
		}
	</style>
	<style type="text/css" id="media-query">
		@media (max-width: 720px) {

			.block-grid,
			.col {
				min-width: 320px !important;
				max-width: 100% !important;
				display: block !important;
			}

			.block-grid {
				width: 100% !important;
			}

			.col {
				width: 100% !important;
			}

			.col>div {
				margin: 0 auto;
			}

			img.fullwidth,
			img.fullwidthOnMobile {
				max-width: 100% !important;
			}

			.no-stack .col {
				min-width: 0 !important;
				display: table-cell !important;
			}

			.no-stack.two-up .col {
				width: 50% !important;
			}

			.no-stack .col.num4 {
				width: 33% !important;
			}

			.no-stack .col.num8 {
				width: 66% !important;
			}

			.no-stack .col.num4 {
				width: 33% !important;
			}

			.no-stack .col.num3 {
				width: 25% !important;
			}

			.no-stack .col.num6 {
				width: 50% !important;
			}

			.no-stack .col.num9 {
				width: 75% !important;
			}

			.video-block {
				max-width: none !important;
			}

			.mobile_hide {
				min-height: 0px;
				max-height: 0px;
				max-width: 0px;
				display: none;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide {
				display: block !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f9f9f9;">
	<!--[if IE]><div class="ie-browser"><![endif]-->
	<table class="nl-container" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#f9f9f9" valign="top">
		<tbody>
			<tr style="vertical-align: top;" valign="top">
				<td style="word-break: break-word; vertical-align: top;" valign="top">
					<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f9f9f9"><![endif]-->
					
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:0px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/Round_corner_top.png" alt="Image" title="Alternate text" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" bgcolor="#74c6d7" width="700" style="background-color:#74c6d7;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:15px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; background-color: #74c6d7; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top: 15px; padding-bottom: 15px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/logo.png" alt="Logo" title="Logo" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 300px; max-width: 300px; display: block;" width="300">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center fixedwidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center fixedwidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/banner.gif" alt="Banner" title="Banner" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 34px; line-height: 1.5; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 51px; margin: 0;"><span style="font-size: 34px;"><strong>WELCOME TO OMNI CHANNELS !!</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">New Omni channel account created to <strong>"'.$company_name.'" 
													</strong> with full features</span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;"><strong>Admin credentials :</strong>&nbsp;</span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">Login URL &nbsp;&nbsp;:&nbsp; <strong>
														<a target="_blank" style="color: #0597d4;" href="' . $email_url. '">' . $email_url. '</a></strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">Company&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp;&nbsp; <strong>"'.$company_name.'"</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">User Name :&nbsp;&nbsp;&nbsp; <strong>"'.$user_name.'"</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">Password &nbsp;&nbsp; : &nbsp; <strong>"'.$user_pwd.'"</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" align="center" role="presentation" height="0" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="0" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="button-container" align="center" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:31.5pt; width:132.75pt; v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#0597d4"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
												<div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#0597d4;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #0597d4;border-right:1px solid #0597d4;border-bottom:1px solid #0597d4;border-left:1px solid #0597d4;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; margin: 0; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;"><a style="text-decoration: none; color: #ffffff; font-weight: 700; letter-spacing: 1px;" target="_blank" href="' . $email_url. '">START NOW</a></span></span></div>
												<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
											</div>
										
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/inner-banner-1.png" alt="BAnner Image" title="Banner Image" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 0px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:30px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #516dac;"><strong>&nbsp; 1&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong> Configure PBX account for Admin</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
										
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="' . $email_url. '" target="_blank"> ' . $email_url. ' </a> &nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Click top right profile image icon and click Profile&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Key-in your extension, SIP username and SIP password and click Update Agent button&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>


											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffc5c3;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffc5c3"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:30px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #f26d6b;"><strong>&nbsp; 2&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Configure PBX account for Agent</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="' . $email_url. '" target="_blank"> ' . $email_url. '</a>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2.  Click Add Agent button Key-in agent details along with agent extension, SIP username and SIP password and click Add Agent button &nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. If already added with PBX account, click Edit button from agent list and update.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											
										
												<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>

											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
										
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #6e51c9;"><strong>&nbsp; 3&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Install/configure Mr.VoIP in 3CX server</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="' . $email_url. '" target="_blank"> ' . $email_url. '</a>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> Download Documents&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Under Mr.VoIP you can Linux files and windows installer file to download&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Download Guide and follow the procedure (Linux/Windows)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 5. Note: Mr.VoIP need to configure/install in 3CX server&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
										
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>';
if($has_fb==1){

          $message .='<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fbf2ce;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#fbf2ce;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fbf2ce;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#fbf2ce"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
										
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #cdc5a6;"><strong>&nbsp; 4&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Facebook Messenger Integration</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into Omnichannel ' . $email_url. '</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> App Settings ->Facebook Messenger(live) and Click on “Download” button.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. You will find the guidelines for Facebook Messenger integration.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Use the guidelines for perfect integration.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>';  

	}

if($has_whatsapp==1){
	$message .='<div style="background-color:transparent;">
            <div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
              <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                <div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
                  <div style="width:100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                      <!--<![endif]-->


                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: Trebuchet MS, Tahoma, sans-serif"><![endif]-->
                      <div style="color:#ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                        <div style="line-height: 1.8; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #6e51c9;"><strong>&nbsp; 5&nbsp;&nbsp;</strong></span></p>
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>WhatsApp Integration</strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into Omnichannel ' . $email_url. '&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> App Settings ->WhatsApp -> (live)and Click on “Download” button.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. You will find the guidelines for WhatsApp Integration.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Use the guidelines for perfect integration.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                        <tbody>
                        <tr style="vertical-align: top;" valign="top">
                          <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                            <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
                              <tbody>
                              <tr style="vertical-align: top;" valign="top">
                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
                              </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                      <!--[if (!mso)&(!IE)]><!-->
                    </div>
                    <!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>';

}
if($has_line==1){
$message .= '<div style="background-color:transparent;">
            <div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
              <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                <div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px; background: lightgreen;">
                  <div style="width:100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                      <!--<![endif]-->


                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: Trebuchet MS, Tahoma, sans-serif"><![endif]-->
                      <div style="color:#ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                        <div style="line-height: 1.8; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: lightseagreen;"><strong>&nbsp; 6&nbsp;&nbsp;</strong></span></p>
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>LINE Integration</strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into Omnichannel ' . $email_url. '&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> App Settings ->LINE and Click on “Download” button.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. You will find the Detailed Guide for LINE Integration.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Use the guidelines for perfect integration.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->
                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 5. For any Clarification contact us.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                        <tbody>
                        <tr style="vertical-align: top;" valign="top">
                          <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                            <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
                              <tbody>
                              <tr style="vertical-align: top;" valign="top">
                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
                              </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                      <!--[if (!mso)&(!IE)]><!-->
                    </div>
                    <!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>';
}
if($has_sms==1){
 $message .='<div style="background-color:transparent;">
            <div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
              <div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
                <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
                <!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
                <div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
                  <div style="width:100% !important;">
                    <!--[if (!mso)&(!IE)]><!-->
                    <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                      <!--<![endif]-->


                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: Trebuchet MS, Tahoma, sans-serif"><![endif]-->
                      <div style="color:#ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
                        <div style="line-height: 1.8; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #6e51c9;"><strong>&nbsp; 7&nbsp;&nbsp;</strong></span></p>
                          <p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Omni Offline SMS</strong></span></p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into Omnichannel ' . $email_url. '&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Messaging -> SMS->Compose New  for Compose SMS.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Go to Settings-> App Settings->SMS Management-> click “Add sender ID” for add your sender Address.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
                      <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                          <p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. For any Clarification contact us.&nbsp;</p>
                        </div>
                      </div>
                      <!--[if mso]></td></tr></table><![endif]-->

                      <table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
                        <tbody>
                        <tr style="vertical-align: top;" valign="top">
                          <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
                            <table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
                              <tbody>
                              <tr style="vertical-align: top;" valign="top">
                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
                              </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                      <!--[if (!mso)&(!IE)]><!-->
                    </div>
                    <!--<![endif]-->
                  </div>
                </div>
                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
              </div>
            </div>
          </div>';
}          

					$message .='<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #3f4d6a;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#3f4d6a;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#3f4d6a"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#3f4d6a;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" align="center" role="presentation" height="5" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="5" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="img-container center fixedwidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center fixedwidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/license.png" alt="License Image" title="License Image" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;" width="150">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<!--<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 20px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 20px; background-color: #0597d4; padding: 10px 30px;"><strong>&nbsp; AC0FE679-7C40-4A19-8119-A213B94E5327&nbsp;&nbsp;</strong></span></p>
													
												</div>
											</div>-->
											<!--[if mso]></td></tr></table><![endif]-->
											
										
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<!--<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;"><span style="color: #f2f2f2;">&nbsp; Use above license key for Mr.VoIP and Omni&nbsp;</span></p>
												</div>
											</div>-->
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 0px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top: 0px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&nbsp;<strong>Note :</strong>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Other Settings you can go through Settings -> Global Settings (To update time zone, logo etc)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> App Settings (To update Themes, chatbot, FB Integration, Whatsapp etc.,)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Go to Settings -> Chat Settings (To configure Live Chat widget etc.,)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">I hope the above is useful to you.&nbsp; Please feel free to contact us if you need any further information.</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->





											<div class="button-container" align="center" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:31.5pt; width:120pt; v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#0597d4"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
												<div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#0597d4;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #0597d4;border-right:1px solid #0597d4;border-bottom:1px solid #0597d4;border-left:1px solid #0597d4;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; margin: 0; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">
													<a style="text-decoration: none; color: #ffffff;" href="https://mconnectapps.com/contact-us/" target="_blank">Contact Us</a></span></span></div>
												<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
											</div>
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" align="center" role="presentation" height="0" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="0" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/Last_box_round.png" alt="Banner" title="Banner" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f9f9f9;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#f9f9f9;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#f9f9f9"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#f9f9f9;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#000000;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.2; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #000000; mso-line-height-alt: 14px;">
													<p style="font-size: 12px; line-height: 1.2; text-align: center; word-break: break-word; font-family: inherit; mso-line-height-alt: 14px; margin: 0;"><a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Terms & Conditions</a> | <a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Help & Contact</a>&nbsp;| <a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Privacy Notice</a> | <a style="text-decoration: none; color: #0068a5;" href="http://mconnectapps.com/" target="_blank" rel="noopener">View Online</a></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="social_icons" cellpadding="0" cellspacing="0" width="100%" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="social_table" align="center" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" valign="top">
																<tbody>
																	<tr style="vertical-align: top; display: inline-block; text-align: center;" align="center" valign="top">
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.facebook.com/Cal4care/" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/facebook.png" alt="Facebook" title="Facebook" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://twitter.com/calncall" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/twitter.png" alt="Twitter" title="Twitter" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.instagram.com/cal4care/" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/instagram.png" alt="Instagram" title="Instagram" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.linkedin.com/company/cal4care-pte-ltd/?viewAsMember=true" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/linkedin.png" alt="LinkedIn" title="LinkedIn" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.youtube.com/results?search_query=cal4care" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/youtube.png" alt="Youtube" title="Youtube" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
				</td>
			</tr>
		</tbody>
	</table>
	<!--[if (IE)]></div><![endif]-->
</body>
</html>';
      $smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
      $smtp_qry_value = $this->fetchData($smtp_qry,array());
      $hostname = $smtp_qry_value['hostname'];
      $port = $smtp_qry_value['port'];
      $username = $smtp_qry_value['username'];
      $password = $smtp_qry_value['password'];
      $body = $message;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = $hostname;
      $mail->Port = $port;
      $mail->Username = $username;
      $mail->Password = $password;
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($email);
      $mail->Send();
	  $status = array("status"=>true);
      $tarray = json_encode($status); 
      print_r($tarray);exit;
    }else{
	  $status = array("status"=>false);
      $tarray = json_encode($status); 
      print_r($tarray);exit;	
	}
}	
	
public function forgot_password($data){
      extract($data); 
    if($email != ''){ 
      $get_question_qry = "SELECT * FROM user WHERE email_id='$email'";
      $result = $this->fetchData($get_question_qry,array());    
      if($result > 0){
      $admin_id = $result['admin_id'];
      /*if($admin_id==1){
      $from = 'sales@cal4care.com.sg';
      }else{
      $admin_email_qry = "SELECT email_id FROM user WHERE user_id='$admin_id'";
            $from = $this->fetchOne($admin_email_qry,array());
      }*/
	  $customer_email = 'noreply@mconnectapps.com';   
      $from = 'Omni Channel';
	  $password = $result['password'];
      $content='<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <!-- NAME: 1 COLUMN -->
  <!--[if gte mso 15]>
      <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
      </xml>
    <![endif]-->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Your Password</title>
  <!--[if !mso]>
    <!-- -->
    <link href="https://fonts.googleapis.com/css?family=Asap:400,400italic,700,700italic" rel="stylesheet" type="text/css">
    <!--<![endif]-->
    <style type="text/css">
      @media only screen and (min-width:768px){
        .templateContainer{
          width:600px !important;
        }
        
        }   @media only screen and (max-width: 480px){
          body,table,td,p,a,li,blockquote{
            -webkit-text-size-adjust:none !important;
          }
          
          }   @media only screen and (max-width: 480px){
            body{
              width:100% !important;
              min-width:100% !important;
            }
            
            }   @media only screen and (max-width: 480px){
              #bodyCell{
                padding-top:10px !important;
              }
              
              }   @media only screen and (max-width: 480px){
                .mcnImage{
                  width:100% !important;
                }
                
                }   @media only screen and (max-width: 480px){
                 
                 .mcnCaptionTopContent,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer{
                  max-width:100% !important;
                  width:100% !important;
                }
                
                }   @media only screen and (max-width: 480px){
                  .mcnBoxedTextContentContainer{
                    min-width:100% !important;
                  }
                  
                  }   @media only screen and (max-width: 480px){
                    .mcnImageGroupContent{
                      padding:9px !important;
                    }
                    
                    }   @media only screen and (max-width: 480px){
                      .mcnCaptionLeftContentOuter
                      .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
                        padding-top:9px !important;
                      }
                      
                      }   @media only screen and (max-width: 480px){
                        .mcnImageCardTopImageContent,.mcnCaptionBlockInner
                        .mcnCaptionTopContent:last-child .mcnTextContent{
                          padding-top:18px !important;
                        }
                        
                        }   @media only screen and (max-width: 480px){
                          .mcnImageCardBottomImageContent{
                            padding-bottom:9px !important;
                          }
                          
                          }   @media only screen and (max-width: 480px){
                            .mcnImageGroupBlockInner{
                              padding-top:0 !important;
                              padding-bottom:0 !important;
                            }
                            
                            }   @media only screen and (max-width: 480px){
                              .mcnImageGroupBlockOuter{
                                padding-top:9px !important;
                                padding-bottom:9px !important;
                              }
                              
                              }   @media only screen and (max-width: 480px){
                                .mcnTextContent,.mcnBoxedTextContentColumn{
                                  padding-right:18px !important;
                                  padding-left:18px !important;
                                }
                                
                                }   @media only screen and (max-width: 480px){
                                  .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
                                    padding-right:18px !important;
                                    padding-bottom:0 !important;
                                    padding-left:18px !important;
                                  }
                                  
                                  }   @media only screen and (max-width: 480px){
                                    .mcpreview-image-uploader{
                                      display:none !important;
                                      width:100% !important;
                                    }
                                    
                                    }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Heading 1
      @tip Make the first-level headings larger in size for better readability
   on small screens.
   */
   h1{
    /*@editable*/font-size:20px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Heading 2
      @tip Make the second-level headings larger in size for better
   readability on small screens.
   */
   h2{
    /*@editable*/font-size:20px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Heading 3
      @tip Make the third-level headings larger in size for better readability
   on small screens.
   */
   h3{
    /*@editable*/font-size:18px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Heading 4
      @tip Make the fourth-level headings larger in size for better
   readability on small screens.
   */
   h4{
    /*@editable*/font-size:16px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Boxed Text
      @tip Make the boxed text larger in size for better readability on small
   screens. We recommend a font size of at least 16px.
   */
   .mcnBoxedTextContentContainer
   .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
    /*@editable*/font-size:16px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Preheader Visibility
      @tip Set the visibility of the emails preheader on small screens. You
   can hide it to save space.
   */
   #templatePreheader{
    /*@editable*/display:block !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Preheader Text
      @tip Make the preheader text larger in size for better readability on
   small screens.
   */
   #templatePreheader .mcnTextContent,#templatePreheader
   .mcnTextContent p{
    /*@editable*/font-size:12px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Header Text
      @tip Make the header text larger in size for better readability on small
   screens.
   */
   #templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
    /*@editable*/font-size:16px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Body Text
      @tip Make the body text larger in size for better readability on small
   screens. We recommend a font size of at least 16px.
   */
   #templateBody .mcnTextContent,#templateBody .mcnTextContent p{
    /*@editable*/font-size:16px !important;
    /*@editable*/line-height:150% !important;
  }
  
  }   @media only screen and (max-width: 480px){
      /*
      @tab Mobile Styles
      @section Footer Text
      @tip Make the footer content text larger in size for better readability
   on small screens.
   */
   #templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
    /*@editable*/font-size:12px !important;
    /*@editable*/line-height:150% !important;
  }
  
}
</style>
</head>

<body style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
background-color: #e4ce75; height: 100%; margin: 0; padding: 0; width: 100%">
<center>
  <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" id="bodyTable" style="border-collapse: collapse; mso-table-lspace: 0;
  mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
  100%; background-color: #e4ce75; height: 100%; margin: 0; padding: 0; width:
  100%" width="100%">
  <tr>
    <td align="center" id="bodyCell" style="mso-line-height-rule: exactly;
    -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-top: 0;
    height: 100%; margin: 0; padding: 0; width: 100%" valign="top">
    <!-- BEGIN TEMPLATE // -->
          <!--[if gte mso 9]>
              <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
                <tr>
                  <td align="center" valign="top" width="600" style="width:600px;">
                  <![endif]-->
                  <table border="0" cellpadding="0" cellspacing="0" class="templateContainer" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
                  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; max-width:
                  600px; border: 0" width="100%">
                  <tr>
                    <td id="templatePreheader" style="mso-line-height-rule: exactly;
                    -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #ffffff;
                    border-top: 0; border-bottom: 0; padding-top: 16px; padding-bottom: 0px" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0;
                    mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
                    min-width:100%;" width="100%">
                    <tbody class="mcnTextBlockOuter">
                      <tr>
                        <td class="mcnTextBlockInner" style="mso-line-height-rule: exactly;
                        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%" valign="top">
                        <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnTextContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
                        mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
                        100%; min-width:100%;" width="100%">
                        <tbody>
                          <tr>
                            <td class="mcnTextContent" style="mso-line-height-rule: exactly;
                            -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; word-break: break-word;
                            color: #2a2a2a; font-family: Asap, Helvetica, sans-serif; font-size: 12px;
                            line-height: 150%; text-align: center; padding-top:9px; padding-right: 18px;
                            padding-bottom: 0px; padding-left: 18px;" valign="top">
                            <a href="http://mconnectapps.com/" style="mso-line-height-rule: exactly;
                            -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #2a2a2a;
                            font-weight: normal; text-decoration: none" target="_blank" title="mConnect">
                            <img align="none" alt="mConnect" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/logo.png" style="-ms-interpolation-mode: bicubic; border: 0; outline: none;
                            text-decoration: none; height: 52px; width: 230px; margin: 0px;" height="52" width="230" />
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td id="templateHeader" style="mso-line-height-rule: exactly;
        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #ffffff;
        border-top: 0; border-bottom: 0; padding-top: 0px; padding-bottom: 0" valign="top">
        <table border="0" cellpadding="0" cellspacing="0" class="mcnImageBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
        min-width:100%;" width="100%">
        <tbody class="mcnImageBlockOuter">
          <tr>
            <td class="mcnImageBlockInner" style="mso-line-height-rule: exactly;
            -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding:0px" valign="top">
            <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
            mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
            100%; min-width:100%;" width="100%">
            <tbody>
              <tr>
                <td class="mcnImageContent" style="mso-line-height-rule: exactly;
                -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-right: 0px;
                padding-left: 0px; padding-top: 0; padding-bottom: 0; text-align:center;" valign="top">
                <a class="" href="http://mconnectapps.com/" style="mso-line-height-rule:
                exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color:
                #f57153; font-weight: normal; text-decoration: none" target="_blank" title="">
                <a class="" href="#" style="mso-line-height-rule:
                exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color:
                #f57153; font-weight: normal; text-decoration: none" title="">
                <img align="center" alt="Forgot your password?" class="mcnImage" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/animated-image.gif" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none;
                text-decoration: none; vertical-align: bottom; max-width:1200px; padding-bottom:
                0; display: inline !important; vertical-align: bottom;" width="600"></img>
              </a>
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
  <td id="templateBody" style="mso-line-height-rule: exactly;
  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #ffffff;
  border-top: 0; border-bottom: 0; padding-top: 0; padding-bottom: 0" valign="top">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width:100%;" width="100%">
  <tbody class="mcnTextBlockOuter">
    <tr>
      <td class="mcnTextBlockInner" style="mso-line-height-rule: exactly;
      -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%" valign="top">
      <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnTextContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
      mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
      100%; min-width:100%;" width="100%">
      <tbody>
        <tr>
          <td class="mcnTextContent" style="mso-line-height-rule: exactly;
          -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; word-break: break-word;
          color: #2a2a2a; font-family: Asap, Helvetica, sans-serif; font-size: 16px;
          line-height: 150%; text-align: center; padding-top:9px; padding-right: 18px;
          padding-bottom: 9px; padding-left: 18px;" valign="top">

          <h1 class="null" style="color: #2a2a2a; font-family: Asap, Helvetica,
          sans-serif; font-size: 32px; font-style: normal; font-weight: bold; line-height:
          125%; letter-spacing: 2px; text-align: center; display: block; margin: 0;
          padding: 0"><span style="text-transform:uppercase">Forgot</span></h1>


          <h2 class="null" style="color: #2a2a2a; font-family: Asap, Helvetica,
          sans-serif; font-size: 24px; font-style: normal; font-weight: bold; line-height:
          125%; letter-spacing: 1px; text-align: center; display: block; margin: 0;
          padding: 0"><span style="text-transform:uppercase">your password?</span></h2>

        </td>
      </tr>
    </tbody>
  </table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace:
0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
min-width:100%;" width="100%">
<tbody class="mcnTextBlockOuter">
  <tr>
    <td class="mcnTextBlockInner" style="mso-line-height-rule: exactly;
    -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%" valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnTextContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
    mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
    100%; min-width:100%;" width="100%">
    <tbody>
      <tr>
        <td class="mcnTextContent" style="mso-line-height-rule: exactly;
        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; word-break: break-word;
        color: #2a2a2a; font-family: Asap, Helvetica, sans-serif; font-size: 16px;
        line-height: 150%; text-align: center; padding-top:9px; padding-right: 18px;
        padding-bottom: 9px; padding-left: 18px;" valign="top">Not to worry, we got you!.
        <br></br>
      </td>
    </tr>
  </tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="border-collapse: collapse; mso-table-lspace: 0;
mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
min-width:100%;" width="100%">
<tbody class="mcnButtonBlockOuter">
  <tr>
    <td align="center" class="mcnButtonBlockInner" style="mso-line-height-rule:
    exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
    padding-top: 0px; padding-right:18px; padding-bottom: 30px; padding-left:18px;" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
    -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width:100%;" width="100%">
    <tbody class="mcnButtonBlockOuter">
      <tr>
        <td align="center" class="mcnButtonBlockInner" style="mso-line-height-rule:
        exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
        padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top">
        <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
        mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
        border-collapse: separate !important;border-radius: 48px;background-color:
        #F57153;">
        <tbody>
          <tr>
            <td align="center" class="mcnButtonContent" style="mso-line-height-rule:
            exactly; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;
            font-family: Asap, Helvetica, sans-serif; font-size: 16px; padding-top:24px;
            padding-right:48px; padding-bottom:24px; padding-left:48px;" valign="middle">
            <a class="mcnButton " href="#" style="mso-line-height-rule: exactly;
            -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; display: block; color: #f57153;
            font-weight: normal; text-decoration: none; font-weight: normal;letter-spacing:
            1px;line-height: 100%;text-align: center;text-decoration: none;color:
            #FFFFFF; text-transform:uppercase;" target="_blank" title="Review Lingo kit
            invitation"><strong>"'.$password.'"</strong></a>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="mcnImageBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width:100%;" width="100%">
<tbody class="mcnImageBlockOuter">
  <tr>
    <td class="mcnImageBlockInner" style="mso-line-height-rule: exactly;
    -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding:0px" valign="top">
    <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="border-collapse: collapse; mso-table-lspace: 0;
    mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
    100%; min-width:100%;" width="100%">
    <tbody>
      <tr>
        <td class="mcnImageContent" style="mso-line-height-rule: exactly;
        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-right: 0px;
        padding-left: 0px; padding-top: 0; padding-bottom: 0; text-align:center;" valign="top"></td>
      </tr>
    </tbody>
  </table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
  <td id="templateFooter" style="mso-line-height-rule: exactly;
  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #e4ce75;
  border-top: 0; border-bottom: 0; padding-top: 8px; padding-bottom: 40px" valign="top">
  <table border="0" cellpadding="0" cellspacing="0" class="mcnTextBlock" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
  -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width:100%;" width="100%">
  <tbody class="mcnTextBlockOuter">
    <tr>
      <td class="mcnTextBlockInner" style="mso-line-height-rule: exactly;
      -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%" valign="top">
      <table align="center" bgcolor="#F7F7FF" border="0" cellpadding="32" cellspacing="0" class="card" style="border-collapse: collapse; mso-table-lspace: 0;
      mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust:
      100%; background:#ffffff; margin:auto; text-align:left; max-width:600px;
      font-family: Asap, Helvetica, sans-serif;" text-align="left" width="100%">
      <tr>
        <td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%">

        <h3 style="color: #2a2a2a; font-family: Asap, Helvetica, sans-serif;
        font-size: 20px; font-style: normal; font-weight: normal; line-height: 125%;
        letter-spacing: normal; text-align: center; display: block; margin: 0; padding:
        0; text-align: left; width: 100%; font-size: 16px; font-weight: bold; ">If you received this message by mistake?</h3>

        <p style="margin: 10px 0; padding: 0; mso-line-height-rule: exactly;
        -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; color: #2a2a2a;
        font-family: Asap, Helvetica, sans-serif; font-size: 12px; line-height: 150%;
        text-align: left; text-align: left; font-size: 14px; ">If you did not ask to change your password, do not worry! Your password is still safe and you can delete this email.
      </p>
      <div style="padding-bottom: 18px;">
        <a href="https://mconnectapps.com/" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration: none;
        font-size: 14px; color:#F57153; text-decoration:none;" target="_blank" title="Learn more about Lingo">Learn More </a>
      </div>
    </td>
  </tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0;
-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; min-width:100%;" width="100%">
<tbody>
  <tr>
    <td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%; padding-top: 24px; padding-right: 18px;
    padding-bottom: 24px; padding-left: 18px; color: #333; font-family: Asap,
    Helvetica, sans-serif; font-size: 14px;" valign="top">
    <div style="text-align: center;">27 New Industrial Rd, #03-06 Novelty Techpoint, Singapore 536212</div>
  </td>
</tr>
<tbody></tbody>
</tbody>
</table>
<table align="center" border="0" cellpadding="12" style="border-collapse:
collapse; mso-table-lspace: 0; mso-table-rspace: 0; -ms-text-size-adjust:
100%; -webkit-text-size-adjust: 100%; ">
<tbody>
  <tr>
    <td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%">
    <a href="https://www.facebook.com/Cal4care/" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration: none" target="_blank">
    <img alt="Facebook" height="32" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/facebook.png" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none; text-decoration:
    none" width="32" />
  </a>
</td>
<td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%">
<a href="https://twitter.com/calncall" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration:
none" target="_blank">
<img alt="Twitter" height="32" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/twitter.png" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none;
text-decoration: none" width="32" />
</a>
</td>
<td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%">
<a href="https://www.linkedin.com/company/cal4care-pte-ltd/?viewAsMember=true" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration: none" target="_blank">
<img alt="LinkedIn" height="32" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/linkedin.png" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none; text-decoration: none" width="32" />
</a>
</td>
<td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%">
<a href="https://www.instagram.com/cal4care/" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration: none" target="_blank">
<img alt="Instagram" height="32" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/instagram.png" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none;
text-decoration: none" width="32" />
</a>
</td>
<td style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%">
<a href="https://www.youtube.com/channel/UCBgkvrZPWYVQKlmyh1WmZoQ" style="mso-line-height-rule: exactly; -ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%; color: #f57153; font-weight: normal; text-decoration: none" target="_blank">
<img alt="youTube" height="32" src="https://erp.cal4care.com/img/email-template/mconnect-forgot-password/youtube.png" style="-ms-interpolation-mode: bicubic; border: 0; height: auto; outline: none;
text-decoration: none" width="32" />
</a>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</table>
          <!--[if gte mso 9]>
                  </td>
                </tr>
              </table>
            <![endif]-->
            <!-- // END TEMPLATE -->
          </td>
        </tr>
      </table>
    </center>
  </body>

  </html>';
		  //echo $content;exit;              
        //$content = "Your Password:".$password;
        $subject = "Forgot Password From Omni Channel";
        require_once('class.phpmailer.php');
        $body = $content;
        //echo $from;exit;
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
        $mail->Host = 'smtpcorp.com'; // sets the SMTP server
        $mail->Port = '2525';                    // set the SMTP port for the GMAIL server
        $mail->Username = 'erpdev2'; // SMTP account username
        $mail->Password = 'dnZ0ZjlyZ3RydzAw';        // SMTP account password 
        $mail->SetFrom($customer_email, $from);
        $mail->AddReplyTo($customer_email, $from);
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
  public  function facebook_account_settings($data){  
      extract($data);             
      $qry = "UPDATE user SET facebook_account='$facebook_account' WHERE user_id='$user_id'";
      $qry_result = $this->db_query($qry, array());
      $results = $qry_result == 1 ? 1 : 0;
      if($results==1){
         $adminqry = "UPDATE admin_details SET facebook_account='$facebook_account' WHERE admin_id='$user_id'";
         $adminqry_result = $this->db_query($adminqry, array());
         $result = $qry_result == 1 ? 1 : 0;
        if($result==1){
		  $qry = "select fb_page_id,fb_page_name,facebook_account from admin_details where admin_id='$user_id'";
		  $res = $this->fetchData($qry, array());
		  $fb_page_id = $res['fb_page_id'];
		  $fb_page_name = $res['fb_page_name'];
		  $facebook_account = $res['facebook_account'];
		  if($facebook_account==0){
		    $result = array("status"=>"true", "fb_page_id"=>"110235083992272", "fb_page_name"=>"Omni channel", "facebook_account"=>"0");
		  }else{
			$result = array("status"=>"true", "fb_page_id"=>$fb_page_id, "fb_page_name"=>$fb_page_name, "facebook_account"=>"1");  
		  }
         return $result;
        }else{
          $result = array("status"=>"false", "fb_page_id"=>"", "fb_page_name"=>"");
		  return $result;
        }
      }
      else{
          $result = array("status"=>"false", "fb_page_id"=>"", "fb_page_name"=>"");
		  return $result;
        }          
    }
	
  public function update_whatsapp_num($data){
      extract($data);
	    $user_host_qry = "select instance_url from whatsapp_instance_details where wp_inst_id='$wp_inst_id'";			
        $host = $this->fetchOne($user_host_qry, array());
		$url = $host."/w2apiisthebestlibrary/reloadServer";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	
   	  $qry = $this->db_query("UPDATE admin_details SET whatsapp_num='$whatsapp_num' WHERE admin_id='$admin_id'", array());
      $result = $qry == 1 ? 1 : 0; 
	  
	  $qry = $this->db_query("UPDATE user SET whatsapp_num='$whatsapp_num' WHERE admin_id='$admin_id'", array());
      $result = $qry == 1 ? 1 : 0; 
	  
  		$qry = $this->db_query("UPDATE user SET whatsapp_num='$whatsapp_num' WHERE user_id='$admin_id'", array());
      $result = $qry == 1 ? 1 : 0; 




      return $result;
  }

  public function getInstanceDetails(){
   	  $qry = $this->dataFetchAll("SELECT * FROM `whatsapp_instance_details` WHERE admin_id='0'", array());
      return $qry;
  }	
	 public function getallInstanceDetails($admin_id){
		 
   	  $qry = $this->dataFetchAll("SELECT * FROM `whatsapp_instance_details` WHERE admin_id IN ('0','$admin_id')", array());
      return $qry;
  }
		
 public function getalladmins(){
		 
   	  $qry = $this->dataFetchAll("SELECT admin_id as id,name FROM `admin_details` where delete_status='0' ", array());
      return $qry;
  }	

 function generate_incoming_message($chat_data){
        extract($chat_data);
	 
	 //print_r($chat_data); exit;
    //date_default_timezone_set("Indian/Maldives");
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='2'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);
      $created_at = date("Y-m-d H:i:s"); 
	 $pn = $phone_num;

    //$user_id = '612';
		if($user_id == '612'){
			$phone_num = str_replace("+65","",$phone_num);	
		} else if($user_id == '847'){
			if(substr( $pn, 0, 3 ) === "+65"){
				$phone_num = str_replace("+65","",$phone_num);	
			} elseif(substr( $pn, 0, 3 ) === "+60") {
				$phone_num = str_replace("+60","",$phone_num);
			}
		}
     $qry = "select * from chat_sms where app_customer_key='$phone_num'";
      $result = $this->fetchData($qry, array());
	// print_r($result); exit;

    if($result > 0){
          $chat_id=$result['chat_id'];
          $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type,msg_from,agent_id, chat_message, delivered_status, chat_status,created_dt,send_by,read_status) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','Using Omni SMS panel','1')", array());
            $chat_data = $this->getChatDetails($chat_msg_id);
            return $chat_data;
      
    } else {//echo "el";exit;
      $qry = "select * from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
            if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $user_id; }
	
           
		
		if($admin_id == '612'){
			  $qry = "INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`created_at`, `country_code`) VALUES ('$phone_num', '$phone_num', '$phone_num', '', 'sms', '0', '1', '1', '$admin_id','$created_at','65')";
		} else if($admin_id == '847'){
			if(substr( $pn, 0, 3 ) === "+65"){
			   $qry = "INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`created_at`, `country_code`) VALUES ('$phone_num', '$phone_num', '$phone_num', '', 'sms', '0', '1', '1', '$admin_id','$created_at','65')";
			} elseif(substr( $pn, 0, 3 ) === "+60") {
			 $qry = "INSERT INTO `chat_sms` (`app_chat_id`, `app_customer_key`, `customer_name`, `customer_pnr`, `api_type`, `assigned_agent`, `chat_status`, `chat_read_status`, `admin_id`,`created_at`, `country_code`) VALUES ('$phone_num', '$phone_num', '$phone_num', '', 'sms', '0', '1', '1', '$admin_id','$created_at','60')";
			}		 
		}		



 $chat_id = $this->db_insert($qry, array());
		
            $chat_msg_id = $this->db_insert("INSERT INTO chat_data_sms(chat_id,msg_type, msg_from,agent_id, chat_message, delivered_status, chat_status, created_dt,send_by,read_status) VALUES ('$chat_id','text', 'customer','$user_id', '$chat_msg','1', '1','$created_at','Using Omni SMS panel','1')", array());
          $chat_data = $this->getChatDetails($chat_msg_id);
		//$mc_event_data = "SMS To".$phone_num;
		//$this->db_query("INSERT INTO mc_event (mc_event_data,mc_event_type,event_status,created_dt) VALUES('$mc_event_data','6','7','$created_at')", array());
            return $chat_data;  
    }  
    }	

  public function getUserActiveStatus($id){
   	  $qry = $this->dataFetchAll("SELECT user_status FROM `user` WHERE user_id='$id'", array());
      return $qry;
  }
	

  public function getEncriptedLogin($user_id){
            $qry = "select * from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
				$company = $result['company_name'];
				$uname = $result['user_name'];
				$password = $result['password'];
	  $simple_string = '{"company":"'.$company.'","username":"'.$uname.'","password":"'.$password.'"}'; 
$ciphering = "AES-128-CTR"; 
$iv_length = openssl_cipher_iv_length($ciphering); 
$options = 0; 
$encryption_iv = '1234567891011121'; 
$encryption_key = "GeeksforGeeks"; 
$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 
           return $encryption;
         
        }
public function webinar_meeting($data){   
  //print_r($data);exit;
    $admin_id = $data['img_user_id'];
    $timezone_id = $data['timezone_id'];
    $title = $data['title'];
    $content = $data['content'];
    $meeting_date = $data['meeting_date'];
	$m_date = str_replace('T', ' ', $meeting_date);
	$minutes = $data['minutes'];
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
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/logo_image/". basename( $_FILES["logo_image"]["name"]);  
            $logo_target_path = $destination_path."logo_image/". basename( $_FILES["logo_image"]["name"]);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logo_target_path);        
          }
        }                 
        if(isset($_FILES["content_image"]))
        {
          $small_logo_image_info = getimagesize($_FILES["content_image"]["tmp_name"]);
          $small_logo_image_width = $small_logo_image_info[0];
          $small_logo_image_height = $small_logo_image_info[1];
          $allowed_small_logo_image_extension = array("png","jpg","jpeg");
          $small_logo_file_extension = pathinfo($_FILES["content_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($small_logo_file_extension, $allowed_small_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $small_logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/small_logo_image/". basename( $_FILES["content_image"]["name"]);  
            $small_logo_target_path = $destination_path."small_logo_image/". basename( $_FILES["content_image"]["name"]);
            move_uploaded_file($_FILES['content_image']['tmp_name'], $small_logo_target_path);        
          }
        }               
    // Logo Images code ends here
    $qry_result = $this->db_query("INSERT INTO webinar_meeting(admin_id, title, content, logo, content_image, timezone_id, meeting_date, minutes) VALUES ('$admin_id', '$title', '$content', '$logo_image_upload_path', '$small_logo_image_upload_path', '$timezone_id', '$m_date', '$minutes')", array()); 
    $resultqry = $qry_result == 1 ? 1 : 0; 
     if($resultqry==1){
		$meeting_id_qry = "SELECT MAX(id) FROM webinar_meeting WHERE admin_id='$admin_id'"; 
		$meeting_id = $this->fetchOne($meeting_id_qry,array()); 
        $result = array("status" => "true", "meeting_id" => $meeting_id);          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
     }else{
        $result = array("status" => "false");          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
     }             
}
	public function list_webinar_meeting($data){
     extract($data);
     $sql="SELECT user_id from user where user_id='$admin_id'";
     $rep = $this->fetchOne($sql, array());
     if($rep!=''){
      $qry = "select * from webinar_meeting where admin_id='$rep'";
      return $this->dataFetchAll($qry, array());
     }else{
       $result = 0;
       return $result;
     }
   }
   public function delete_webinar_meeting($data){
      extract($data);
      $qry = "DELETE FROM webinar_meeting WHERE admin_id='$admin_id' AND id='$id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function edit_webinar_meeting($data)
    {
      extract($data);
      $qry = "select * from webinar_meeting where id='$id' and admin_id='$admin_id'"; 
      $result = $this->dataFetchAll($qry, array());
	  foreach($result as $v) {		  
		  $v['meeting_date'] = date('Y-m-d\TH:i', strtotime($v['meeting_date']));
		}	
	  return $result;
	}
	public function update_webinar_meeting($data){   
      extract($data);
	  $m_date = str_replace('T', ' ', $meeting_date);
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
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/logo_image/". basename( $_FILES["logo_image"]["name"]);  
            $logo_target_path = $destination_path."logo_image/". basename( $_FILES["logo_image"]["name"]);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logo_target_path);        
          }
        }else{
           $sql="SELECT logo from webinar_meeting where id='$id'";
           $logo_image_upload_path = $this->fetchOne($sql, array());
        }                 
        if(isset($_FILES["content_image"]))
        {
          $small_logo_image_info = getimagesize($_FILES["content_image"]["tmp_name"]);
          $small_logo_image_width = $small_logo_image_info[0];
          $small_logo_image_height = $small_logo_image_info[1];
          $allowed_small_logo_image_extension = array("png","jpg","jpeg");
          $small_logo_file_extension = pathinfo($_FILES["content_image"]["name"], PATHINFO_EXTENSION);   
          if (! in_array($small_logo_file_extension, $allowed_small_logo_image_extension)) {
                $result = array( 
                    "status" => "false",          
                    "message" => "Please upload PNG and JPEG Format Image."
                );
                $tarray = json_encode($result);  
                print_r($tarray);exit;
          }
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $small_logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/small_logo_image/". basename( $_FILES["content_image"]["name"]);  
            $small_logo_target_path = $destination_path."small_logo_image/". basename( $_FILES["content_image"]["name"]);
            move_uploaded_file($_FILES['content_image']['tmp_name'], $small_logo_target_path);        
          }
        }else{
           $sql="SELECT content_image from webinar_meeting where id='$id'";
           $small_logo_image_upload_path = $this->fetchOne($sql, array());
        }               
       // Logo Images code ends here		
       $qry_result = $this->db_query("UPDATE webinar_meeting SET title='$title',content='$content',logo='$logo_image_upload_path',content_image='$small_logo_image_upload_path',timezone_id='$timezone_id',meeting_date='$m_date',minutes='$minutes' WHERE id='$id' AND admin_id='$admin_id'", array()); 
       $resultqry = $qry_result == 1 ? 1 : 0; 
       if($resultqry==1){
        $result = array("status" => "true", "meeting_id" => $id);          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
       }else{
        $result = array("status" => "false");          
        $tarray = json_encode($result);  
        print_r($tarray);exit;
       }             
   }
	public function agent_billing_det($data){   
		extract($data);
  $qry = "INSERT INTO agent_billing_det(user_id, admin_id, contact_person, add1, add2, city, state, zip_code, country, edit_ship, ship_contact, ship_to, ship_add1, ship_add2, ship_city, ship_state, ship_zip, ship_country,monthly_charges,discount) VALUES ('$user_id', '$admin_id','$contact_person','$add1','$add2','$city','$state','$zip_code','$country','$edit_ship','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$monthly_charges','$discount_per')";
    $qry_result = $this->db_query($qry, array());	
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
	}
	public function edit_agent_billing_det($data){   
		extract($data);
		$qry= "SELECT * FROM `agent_billing_det` where user_id='$id' and admin_id='$admin_id'";
	$res= $this->dataFetchAll($qry,array());
		return $res;
	}
	public function update_agent_billing_det($data){   
		extract($data); 	
 $id=$this->fetchOne("SELECT * FROM `agent_billing_det` where user_id='$user_id' and admin_id='$admin_id'",array());
		if($id!=''){
 $qry = "UPDATE agent_billing_det SET contact_person = '$contact_person',add1='$add1',add2='$add2', city='$city', state='$state', zip_code='$zip_code', country='$country', edit_ship='$edit_ship', ship_contact='$ship_contact', ship_to='$ship_to', ship_add1='$ship_add1', ship_add2='$ship_add2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', monthly_charges='$monthly_charges', discount='$discount_per' WHERE id='$id' and admin_id='$admin_id'";
			}else{
			$qry = "INSERT INTO agent_billing_det(user_id, admin_id, contact_person, add1, add2, city, state, zip_code, country, edit_ship, ship_contact, ship_to, ship_add1, ship_add2, ship_city, ship_state, ship_zip, ship_country,monthly_charges,discount) VALUES ('$user_id', '$admin_id','$contact_person','$add1','$add2','$city','$state','$zip_code','$country','$edit_ship','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$monthly_charges','$discount_per')";
			}
     //echo 	$qry;exit;
    $qry_result = $this->db_query($qry, array());	
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
	}
	public function clickToCallWidget($login,$number){
				  
	/*	$encryption = $login;
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
	//$this->senddata($login,$number);
		
		$data = array('number' => $number,'login' => $login);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://omni.mconnectapps.com/dialer-v2/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);
		curl_close($ch);
		print_r( $response);
		


	}
	
	public function add_admin_biller($data){   
		extract($data);
		
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
          else{
            $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $logo_image_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/logo_image/". basename( $_FILES["logo_image"]["name"]);  
            $logo_target_path = $destination_path."logo_image/". basename( $_FILES["logo_image"]["name"]);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logo_target_path);        
          }
        }
        else
        {
          $logo_image_qry = "SELECT logo_image FROM admin_biller WHERE admin_id='$admin_id'";
          $logo_image_upload_path = $this->fetchmydata($logo_image_qry,array());          
        }
		
		$already=$this->fetchOne("SELECT id from admin_biller where user_id='$user_id'",array());      
	if($already==''){	
  $qry = "INSERT INTO admin_biller(user_id, admin_id, add1, add2, city, state, zip_code, country,phone,toll_free,reg_no,logo_image,email,account_no,bank,branch ) VALUES ('$user_id', '$admin_id','$add1','$add2','$city','$state','$zip_code','$country','$phone','$toll_free','$reg_no','$logo_image_upload_path','$email','$account_no','$bank','$branch')";
		}else{
		 $qry = "UPDATE admin_biller SET add1='$add1',add2='$add2',city='$city',state='$state',zip_code='$zip_code',country='$country', phone='$phone', toll_free='$toll_free', reg_no='$reg_no', email='$email', account_no='$account_no', bank='$bank', branch='$branch' WHERE user_id='$user_id'";
}
    $qry_result = $this->db_query($qry, array());	
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
	}
public function agent_group($data){
	extract($data);
	  $qry_result = $this->db_query("INSERT INTO agent_group(group_name,agents,admin_id) VALUES ('$group_name','$agents','$admin_id')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
}
	public function view_agent_group($data){
		extract($data);
		  $get_ag_group = "SELECT * FROM agent_group WHERE admin_id='$admin_id'";
            $res= $this->dataFetchAll($get_ag_group,array());
		return $res;
}
	public function ed_agent_group($data){
		extract($data);
		  $get_ag_group = "SELECT * FROM agent_group WHERE admin_id='$admin_id' and id='$id'";
            $res= $this->dataFetchAll($get_ag_group,array());
		return $res;
}	
	public function del_agent_group($data){
		extract($data);
		  $get_ag_group = "DELETE FROM agent_group WHERE admin_id='$admin_id' and id='$id'";
            $res= $this->db_query($get_ag_group,array());
		return $res;
}
public function update_agent_group($data){
	extract($data);
	$qry="UPDATE agent_group SET group_name='$group_name',agents='$agents' WHERE id='$id'";
	$res=$this->db_query($qry, array());
	return $res;
}

//NB code 08-09-2021	
//erp mobile app code 
	  public function erp_mobile_user($data){
			extract($data);
		  if($extension != ''){
		      $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.user_pwd,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_login,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.has_internal_chat,user.external_contact_url,user.show_caller_id as user_caller_id,user.predective_dialer_behave,user.whatsapp_account,user.crm_type,user.facebook_account,admin_details.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.admin_status,admin_details.show_caller_id,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.has_webinar,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip,admin_details.admin_status, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.user_name='$extension' OR user.sip_login='$extension'";
			  //print_r($qry);exit;
			      $result = $this->fetchData($qry, array());
			  	$encoded = base64_encode(base64_encode(json_encode($result)));	
				 return $encoded;
		  }

	  }

//erp mobile app code ENDS
	
// login from erp code starts here
  public function login_from_erp($extension){
	  //echo $extension;exit;
	if($extension!=''){  
    $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.user_pwd,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_login,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.has_internal_chat,user.external_contact_url,user.show_caller_id as user_caller_id,user.predective_dialer_behave,user.whatsapp_account,user.crm_type,user.facebook_account,admin_details.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.admin_status,admin_details.show_caller_id,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.has_webinar,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip,admin_details.admin_status, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.user_name='$extension' OR user.sip_login='$extension'";
    //print_r($qry); exit;
    $result = $this->fetchData($qry, array());
    //print_r($result); exit;
	$user_name = $result['user_name'];  
	$user_pwd = $result['user_pwd']; 
	$ext_no = $result['sip_login'];	
    $two_factor_qry = "SELECT two_factor FROM user WHERE user_name='$user_name' AND user_pwd='$user_pwd'";
    $two_factor = $this->fetchOne($two_factor_qry,array());
    if($two_factor == ''){             
      $result = array("data" => 0);              
      return $result;
    }
    elseif($two_factor == 0){             
      if($result == null){
        return $result;
      }
      else{
		          $element_data = array('action' => 'get_profile_image', 'ext_no' => $ext_no);
				  $fields = array('operation'=>'get_profile_image','moduleType'=>'ticket','api_type'=>'web','action' => 'get_profile_image', 'ext_no' => $ext_no);
				  file_put_contents('vait.txt', print_r($fields,true).PHP_EOL , FILE_APPEND | LOCK_EX);
				  $url = 'https://erp.cal4care.com/erp/apps/ind.php';
				  $ch = curl_init();
				  curl_setopt_array($ch, array(
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_POST => true,
						CURLOPT_POSTFIELDS => $fields
					));
				  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				  $output = curl_exec($ch);
				  $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                  $curl_errno= curl_errno($ch);				  
				  $te = json_decode($output,true);
				  $profile_image = $te['profile_image'];  
        $user_id = $result["user_id"];
        $ip = $this->getClientIP();
        $this->db_query("INSERT INTO user_login(user_id, user_method, created_ip) VALUES ('$user_id','user','$ip')", array());
        $this->db_query("UPDATE user SET login_status=1,profile_image='$profile_image' WHERE user_id='$user_id'", array());
      }
	   $qry = "SELECT user.user_id,user.admin_id,user.user_name,user.user_pwd,user.delete_status,user.user_type,user.user_status,user.created_dt,user.email_id,user.phone_number,user.incoming_call_status,user.sip_login,user.sip_username,user.sip_password,user.sip_tocken,user.profile_image,user.layout,user.theme,user.timezone_id,user.lead_token,user.logo_image,user.small_logo_image,user.ext_int_status,user.two_factor,user.dsk_access,user.dsk_user_name,user.dsk_user_pwd,user.has_wallboard,user.hardware_id,user.has_fax,user.has_external_contact,user.reports,user.close_all_menu,user.login_status,user.has_internal_chat,user.external_contact_url,user.show_caller_id as user_caller_id,user.predective_dialer_behave,user.whatsapp_account,user.crm_type,user.facebook_account,admin_details.reseller, admin_details.wallboard_one_isactive,admin_details.wallboard_two_isactive,admin_details.admin_status,admin_details.show_caller_id,admin_details.wallboard_three_isactive,admin_details.wallboard_four_isactive,admin_details.company_name,admin_details.has_webinar,admin_details.domain_name,admin_details.has_wallboard,admin_details.price_sms,admin_details.mr_voip,admin_details.admin_status, user_type.user_type_name as userType FROM user LEFT JOIN user_type on user.user_type = user_type.user_type_id LEFT JOIN admin_details on admin_details.admin_id = user.user_id WHERE user.user_name='$extension' OR user.sip_login='$extension'";
    //print_r($qry); exit;
      $result = $this->fetchData($qry, array());	
      return $result;
    }
    else{            
      if($result == null){
        return $result;
      }
      else{   
        return $result;   
      }
    }
  }else{
	return $result;
	}  
  }
// login from erp code ends here	

//SSO Codes
	
public function ms_sso($email){
	$get_agent_qry = "select * from user where company_name='cal4care' and email_id='$email'";
	$user_details = $this->fetchData($get_agent_qry,array());
	$user_id = $user_details['user_id'];
	$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
	$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;

	if($email =='mr@cal4care.com'){
	}elseif($user_details['user_type'] != '2'){
		$omni_users = "SELECT omni_users FROM ms_sso_authentication where admin_id='$admin_id'";
		$omni_users =$this->fetchOne($omni_users,array());	
		$omni_users = explode(',',$omni_users);
		if (in_array($user_id, $omni_users)){
			$valid_user = $user_id;
		}else{
			$valid_user = "";
			echo "Not an Valid User"; exit;
		}
	} 


	$qry = "select * from user where user_id='$user_id'";
	$result = $this->fetchData($qry, array());
	$company = $result['company_name'];
	$uname = $result['user_name'];
	$password = $result['password'];
	$simple_string = '{"company":"'.$company.'","username":"'.$uname.'","password":"'.$password.'"}'; 
	$ciphering = "AES-128-CTR"; 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	$encryption_iv = '1234567891011121'; 
	$encryption_key = "GeeksforGeeks"; 
	$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 
	echo $encryption; exit;
	
}

public function mss_sso_teams($email){
	$get_agent_qry = "select * from user where company_name='cal4care' and email_id='$email'";
	$user_details = $this->fetchData($get_agent_qry,array());
	$user_id = $user_details['user_id'];
	$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
	$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;
	if($user_details['user_type'] != '2'){
		$omni_users = "SELECT teams_users FROM ms_sso_authentication where admin_id='$admin_id'";
		$omni_users =$this->fetchOne($omni_users,array());	
		$omni_users = explode(',',$omni_users);
		if (in_array($user_id, $omni_users)){
			$valid_user = $user_id;
		}else{
			$valid_user = "";
			echo "Not an Valid User"; exit;
		}
	} 


	$qry = "select * from user where user_id='$user_id'";
	$result = $this->fetchData($qry, array());
	$company = $result['company_name'];
	$uname = $result['user_name'];
	$password = $result['password'];
	$simple_string = '{"company":"'.$company.'","username":"'.$uname.'","password":"'.$password.'"}'; 
	$ciphering = "AES-128-CTR"; 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	$encryption_iv = '1234567891011121'; 
	$encryption_key = "GeeksforGeeks"; 
	$encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); 
	echo $encryption; exit;
	
}	
public function ms_sso_omni($login){
	$login = base64_decode($login);
	$encryption = $login;
	$ciphering = "AES-128-CTR"; 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	$decryption_iv = '1234567891011121'; 
	$decryption_key = "GeeksforGeeks"; 
	$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
	$decryption =  $array = json_decode($decryption, true);
	$tarray = json_encode($decryption);           
	print_r($tarray);exit;
}	
//Ms-SSO Codes End
}
