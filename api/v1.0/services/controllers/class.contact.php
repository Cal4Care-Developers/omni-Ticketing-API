<?php
//echo "test";exit;
	class contacts extends restApi{
 
		public function createContact($contact_owner,$first_name,$last_name,$account_name,$lead_source,$title,$email,$department,$activity,$res_dept,$phone,$home_phone,$office_phone,$fax,$mobile,$dob,$assistant,$assitant_phone,$reports_to,$email_opt_out,$skype,$secondary_email,$twitter,$reporting_to,$mailing_street,$other_street,$mailing_city,$other_city,$mailing_province,$other_province,$mailing_postal_code,$other_postal_code,$mailing_country,$other_country,$created_by,$notes,$auxcode_name,$user_id,$callid,$facebook_url,$whatsapp_number,$line,$wechat,$viber,$telegram,$instagram_url,$linkedin,$country_code){
			
	  //$user_qry = "SELECT timezone_id FROM user WHERE user_id='$created_by'";
	  $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";		
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
			//echo $user_timezone;
      date_default_timezone_set($user_timezone);  
     	 $created_at = date("Y-m-d H:i:s");
		 $updated_at = date("Y-m-d H:i:s");  
			//exit;
     	$users_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
        $aid = $this->fetchOne($users_qry,array());
		if($aid==1){
			$adminId = $created_by;
		}else{
			$adminId = $aid;
		}
			//$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$phone' and admin_id='$adminId'";				
            //$results = $this->fetchData($qry, array());
			$chk_num_qry = "SELECT phone FROM contacts WHERE phone='$phone' AND admin_id='$adminId'";
            $chk_num = $this->fetchOne($chk_num_qry,array());
		if($chk_num == ''){

			 $qry = "select * from user where user_id='$created_by'";
                
             $result = $this->fetchData($qry, array());
			
			if($result['user_type'] == '2'){ $admin_id = $created_by; } else { $admin_id = $result['admin_id']; }
			if($email_opt_out == 1 || $email_opt_out == "true"){ $email_opt_out = '1'; }else{ $email_opt_out = 0; }
			
			
			
			$qry = "INSERT INTO contacts (`contact_owner`, `first_name`, `last_name`, `account_name`, `lead_source`, `title`, `email`, `department`, `activity`, `res_dept`, `phone`, `home_phone`, `office_phone`, `fax`, `mobile`, `dob`, `assistant`, `assitant_phone`, `reports_to`, `email_opt_out`, `skype`, `secondary_email`, `twitter`, `reporting_to`, `mailing_street`, `other_street`, `mailing_city`, `other_city`, `mailing_province`, `other_province`, `mailing_postal_code`, `other_postal_code`, `mailing_country`, `other_country`, `created_by`, `admin_id`, `created_at`, `updated_at`,`facebook_url`,`whatsapp_number`,`line`,`wechat`,`viber`,`telegram`,`instagram_url`,`linkedin`,country_code) VALUES ('$contact_owner','$first_name','$last_name','$account_name','$lead_source','$title','$email','$department','$activity','$res_dept','$phone','$home_phone','$office_phone','$fax','$mobile','$dob','$assistant','$assitant_phone','$reports_to','$email_opt_out','$skype','$secondary_email','$twitter','$reporting_to','$mailing_street','$other_street','$mailing_city','$other_city','$mailing_province','$other_province','$mailing_postal_code','$other_postal_code','$mailing_country','$other_country','$created_by','$admin_id','$created_at','$updated_at','$facebook_url','$whatsapp_number','$line','$wechat','$viber','$telegram','$instagram_url','$linkedin','$country_code')";
	
			$qry_result = $this->db_query($qry, array());

         $result = $qry_result == 1 ? 1 : 0;
		//print_r($result);exit;


				if($notes == ''){
					if($callid != '' || $callid != 'null' || $callid != null){	
						$dt = date('Y-m-d H:i:s');
		      $insert_qry = "INSERT INTO call_history(user_id, customer_id, call_data, call_note, phone, call_type, duration, call_status, call_view_status, auxcode_name, call_start_dt, call_end_dt, created_dt, updated_dt) VALUES ('$created_by', '$created_by', 'Call to 601', '', '$phone', 'incoming','0', 'open','1','$auxcode_name','$dt', '$dt', '$dt', '$dt')";
						
               // $this->errorLog('test_data122',$qry);
                $callid_insertqry = $this->db_insert($insert_qry, array());
		 	          
		            }else{
					$callupdate_qry = "UPDATE `call_history` SET `auxcode_name` = '$auxcode_name' WHERE `callid` = '$callid'";
						
		 	          $callupdate_qry_result = $this->db_query($callupdate_qry, array());
					}
					
					$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater,(select sip_login from user where user_id = contacts.created_by) as extnsioin, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$phone'";
							$results = $this->fetchData($qry, array());
							
							$con_id = $results['contact_id'];
							$created_by = $results['created_by'];
							$admin_id = $results['admin_id'];
							$notes = $results['created_at'].': Incoming call from  '.$results['phone'].' to '.$results['extnsioin']; 
								
								$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`, `auxcode_name`, `created_at`, `updated_at`) VALUES ('$con_id', '$created_by', '$admin_id', '$notes', '$auxcode_name', '$created_at', '$updated_at')"; 
					$qry_result = $this->db_query($qry, array());	
					$res = $qry_result == 1 ? 1 : 0;	
			 	 
					/*if($res=='1' && $auxcode_name=='DND'){
						$sel_qry="UPDATE predective_dialer_contact set dnd='1' where phone_number='$phone' and   admin_id='$admin_id' ";						
						$qry_res = $this->db_query($sel_qry, array());
					}*/
				//print_r($qry_result);exit;
														
						}else{
					if($callid != '' || $callid != 'null' || $callid != null){
						$dt = date('Y-m-d H:i:s');
		      $insert_qry = "INSERT INTO call_history(user_id, customer_id, call_data, call_note, phone, call_type, duration, call_status, call_view_status, auxcode_name, call_start_dt, call_end_dt, created_dt, updated_dt) VALUES ('$created_by', '$created_by', 'Call to 601', '', '$phone', 'incoming','0', 'open','1','$auxcode_name','$dt', '$dt', '$dt', '$dt')";
						
               // $this->errorLog('test_data122',$qry);
                $callid_insertqry = $this->db_insert($insert_qry, array());
		 	          
		            }else{
					$callupdate_qry = "UPDATE `call_history` SET `auxcode_name` = '$auxcode_name' WHERE `callid` = '$callid'";
						
		 	          $callupdate_qry_result = $this->db_query($callupdate_qry, array());
					}
  		$qry = "SELECT contact_id FROM contacts ORDER BY contact_id DESC LIMIT 1";
		$result_qry = $this->dataFetchAll($qry, array());
		 $result_val = $result_qry[0];
		 $result_contactid = $result_val['contact_id'];
	     
         $notes = $results['created_at'].$notes.$results['phone'].' to '.$results['extnsioin']; 
			$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`, `auxcode_name`, `created_at`, `updated_at`) VALUES ( '$result_contactid', '$created_by', '$admin_id', '$notes', '$auxcode_name', '$created_at', '$updated_at')";
					$qry_result = $this->db_query($qry, array()); 
					
					$res = $qry_result == 1 ? 1 : 0;	
			 	 
					/*if($res=='1' && $auxcode_name=='DND'){
						$sel_qry="UPDATE predective_dialer_contact set dnd='1' where phone_number='$phone' and   admin_id='$admin_id' ";						
						$qry_res = $this->db_query($sel_qry, array());
					}*/
		}
		return $result;
	} else {
			$results["in_table"] = true;
 			return $results;

	}

    }
		
// public function updateContact($contact_owner,$first_name,$last_name,$account_name,$lead_source,$title,$email,$department,$activity,$res_dept,$phone,$home_phone,$office_phone,$fax,$mobile,$dob,$assistant,$assitant_phone,$reports_to,$email_opt_out,$skype,$secondary_email,$twitter,$reporting_to,$mailing_street,$other_street,$mailing_city,$other_city,$mailing_province,$other_province,$mailing_postal_code,$other_postal_code,$mailing_country,$other_country,$modified_by,$contact_id,$notes){
			
// 			if($email_opt_out == 1 || $email_opt_out == "true"){ $email_opt_out = '1'; }else{ $email_opt_out = 0; }
		
// 			$qry = "select * from contacts where phone='$phone'";
	
			
//              $results = $this->fetchData($qry, array());	
// 			if($results == ''){
// 				$user_id = $modified_by;

// 					 $qry = "select * from user where user_id='$user_id'";

// 					 $result = $this->fetchData($qry, array());

// 					if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }

// 					$qry ="INSERT INTO `contacts` ( `phone`,`created_by`,`admin_id`) VALUES ( '$phone','$user_id','$admin_id') ";

// 							$qry_result = $this->db_query($qry, array());

// 				 	$result = $qry_result == 1 ? 1 : 0;	
// 						if($result == 1){
// 						$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater,(select sip_login from user where user_id = contacts.created_by) as extnsioin, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$phone'";
// 							$results = $this->fetchData($qry, array());
							
// 							$con_id = $results['contact_id'];
// 							$created_by = $results['created_by'];
// 							$admin_id = $results['admin_id'];
// 							$notes = $results['created_at'].': Incoming call from  '.$results['phone'].' to '.$results['extnsioin']; 
								
// 								$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`) VALUES ('$con_id', '$created_by', '$admin_id', '$notes')";
// 					$qry_result = $this->db_query($qry, array());
				
							
						
// 							return  $results;
							
// 						}

				
				
// 			} else {
				




// 			$qry = "UPDATE `contacts` SET `contact_owner` = '$contact_owner', `first_name` = '$first_name', `account_name` = '$account_name', `lead_source` = '$lead_source', `title` = '$title', `email` = '$email', `department` = '$department', `activity` = '$activity',`res_dept` = '$res_dept', `phone` = '$phone', `home_phone` = '$home_phone', `office_phone` = '$office_phone', `fax` = '$fax', `mobile` = '$mobile', `dob` = '$dob', `assistant` = '$assistant', `assitant_phone` = '$assitant_phone', `reports_to` = '$reports_to',`email_opt_out`='$email_opt_out',`skype` = '$skype', `secondary_email` = '$secondary_email', `twitter` = '$twitter', `reporting_to` = '$reporting_to', `mailing_street` = '$mailing_street', `other_street` = '$other_street', `mailing_city` = '$mailing_city', `other_city` = '$other_city', `mailing_province` = '$mailing_province', `other_province` = '$other_province', `mailing_postal_code` = '$mailing_postal_code', `other_postal_code` = '$other_postal_code', `mailing_country` = '$mailing_country', `other_country` = '$other_country', `modified_by` = '$modified_by' WHERE `contacts`.`contact_id` = '$contact_id'";
	
	 
// 			$qry_result = $this->db_query($qry, array());

//         $qry_result == 1 ? 1 : 0;
	
// 		 if($qry_result == 1 && $notes !=''){

// 			$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`) VALUES ( '$contact_id', '$modified_by', '$admin_id', '$notes')";
// 					$qry_result = $this->db_query($qry, array());

//          $result = $qry_result == 1 ? 1 : 0;	

//             return $result;
			
		
// 		} else {

//             return $result;
// 		 }
			
// 		}
// 		}*/
		

public function updateContact($contact_owner,$first_name,$last_name,$account_name,$lead_source,$title,$email,$department,$activity,$res_dept,$phone,$home_phone,$office_phone,$fax,$mobile,$dob,$assistant,$assitant_phone,$reports_to,$email_opt_out,$skype,$secondary_email,$twitter,$reporting_to,$mailing_street,$other_street,$mailing_city,$other_city,$mailing_province,$other_province,$mailing_postal_code,$other_postal_code,$mailing_country,$other_country,$modified_by,$contact_id,$notes,$auxcode_name,$callid,$facebook_url,$whatsapp_number,$line,$wechat,$viber,$telegram,$instagram_url,$linkedin,$country_code){
      $user_qry = "SELECT timezone_id FROM user WHERE user_id='$modified_by'";
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);  
      $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");			
			if($email_opt_out == 1 || $email_opt_out == "true"){ $email_opt_out = '1'; }else{ $email_opt_out = 0; }
			
			$qry = "UPDATE `contacts` SET `contact_owner` = '$contact_owner', `first_name` = '$first_name', `account_name` = '$account_name', `lead_source` = '$lead_source', `title` = '$title', `email` = '$email', `department` = '$department', `activity` = '$activity',`res_dept` = '$res_dept', `phone` = '$phone', `home_phone` = '$home_phone', `office_phone` = '$office_phone', `fax` = '$fax', `mobile` = '$mobile', `dob` = '$dob', `assistant` = '$assistant', `assitant_phone` = '$assitant_phone', `reports_to` = '$reports_to',`email_opt_out`='$email_opt_out',`skype` = '$skype', `secondary_email` = '$secondary_email', `twitter` = '$twitter', `reporting_to` = '$reporting_to', `mailing_street` = '$mailing_street', `other_street` = '$other_street', `mailing_city` = '$mailing_city', `other_city` = '$other_city', `mailing_province` = '$mailing_province', `other_province` = '$other_province', `mailing_postal_code` = '$mailing_postal_code', `other_postal_code` = '$other_postal_code', `mailing_country` = '$mailing_country', `other_country` = '$other_country', `modified_by` = '$modified_by', `updated_at` = '$updated_at', `facebook_url` = '$facebook_url', `whatsapp_number` = '$whatsapp_number', `line` = '$line', `wechat` = '$wechat', `viber` = '$viber', `telegram` = '$telegram', `instagram_url` = '$instagram_url', `linkedin` = '$linkedin', `country_code` = '$country_code'  WHERE `contacts`.`contact_id` = '$contact_id'";
	
	 
			$qry_result = $this->db_query($qry, array());

         $result = $qry_result == 1 ? 1 : 0;
	
		 if($qry_result == 1 && $notes !=''){
if($callid != '' || $callid != 'null'){
	/*echo "yes";exit;
		 	  $callupdate_qry = "UPDATE `call_history` SET `auxcode_name` = '$auxcode_name' WHERE `callid` = '$callid'";
		 	  $callupdate_qry_result = $this->db_query($callupdate_qry, array());
		    }else{*/
	//echo "no";exit;
		    	$dt = date('Y-m-d H:i:s');
		      $insert_qry = "INSERT INTO call_history(user_id, customer_id, call_data, call_note, phone, call_type, duration, call_status, call_view_status, auxcode_name, call_start_dt, call_end_dt, created_dt, updated_dt) VALUES ('$modified_by', '$modified_by', 'Call to $phone', '', '$phone', 'outgoing','0', 'open','1','$auxcode_name','$dt', '$dt', '$dt', '$dt')";
               // $this->errorLog('test_data122',$qry);
                $callid_insertqry = $this->db_insert($insert_qry, array());
		    }
			   $get_rec_url="SELECT cd.rec_path,IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM call_details cd,user where (cd.to_no=user.sip_login or cd.from_no=user.sip_login) and user.user_id='$modified_by' ORDER by id desc LIMIT 1";
             $parms = array();
             $path= $this->fetchData($get_rec_url,$parms);
			 $path_url=$path['rec_path'];
			 $admin_id=$path['admin_id'];
			// echo $admin_id;exit;
			//print_r($path);exit;
			$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`, `auxcode_name`, `call_rec`, `created_at`, `updated_at`) VALUES ( '$contact_id', '$modified_by', '$admin_id', '$notes', '$auxcode_name', '$path_url', '$created_at', '$updated_at')";
			 //echo $qry;exit;
					$qry_result = $this->db_query($qry, array());

         $result = $qry_result == 1 ? 1 : 0;	
			 	 
					/*if($result=='1' && $auxcode_name=='DND'){
						$sel_qry="UPDATE predective_dialer_contact set dnd='1' where phone_number='$phone' and   admin_id='$admin_id' ";
						$qry_res = $this->db_query($sel_qry, array());
					} */

            return $result;
			
		
		} else {

            return $result;
		 }
			
		
		}		
		private function deleteCustomer($id){

			$qry = "Delete from customer where customer_id=:customer_id";
			$qry_result = $this->db_query($qry, array("customer_id"=>$id));
				
			$result = $qry_result == 1 ? 1 : 0;

			return $result;

		}
		
        public function listContacts($data){

            extract($data);
			$qry = "select * from user where user_id='$user_id'";
                
             $results = $this->fetchData($qry, array());
			
			if($results['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $results['admin_id']; }
	
			
            $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
            extract($qry_limit_data);

            $search_qry = "";
            if($search_text != ""){
                $search_qry = " and (contact.first_name like '%".$search_text."%' or contact.skype like '%".$search_text."%' or contact.email like '%".$search_text."%' or contact.phone like '%".$search_text."%' or contact.last_name like '%".$search_text."%')";
            }

            $qry = "SELECT `contact_id`, `first_name`,`email`,`phone`,`contact_owner` from contacts as contact where admin_id='$admin_id' ".$search_qry; 
            $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;
           // echo $qry;exit;
			
			$parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;
        }
		
		public function editContacts($data){
            extract($data);
			$admin_qry = "select * from user where user_id='$user_id'";
			$adminid_result = $this->fetchData($admin_qry, array());
		    if($adminid_result['user_type'] == '2'){ $adminid = $user_id; } else { $adminid = $adminid_result['admin_id']; }  
	  $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
     	
 				$qry = "select * from user where user_id='$user_id' and created_by='47'"; // check condition for dhiragu

				$result = $this->fetchData($qry, array());

				if($result == '')
				{
					$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$contact_phone' and admin_id='$adminid' ";					
			        $results = $this->fetchData($qry, array());
					$ph= $results['phone'];
					 $get_path = $this->fetchone("SELECT rec_path FROM `call_details` where from_no='$ph' order by id DESC LIMIT 1", array());
					//$results['rec_path']='';
					return  $results;
				}	
			else {
					$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$contact_phone' and admin_id='$adminid'";			
             $results = $this->fetchData($qry, array());
			
			
			if($results == ''){
					 $qry = "select * from user where user_id='$user_id'";

					 $result = $this->fetchData($qry, array());

					if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }

					$qry ="INSERT INTO `contacts` ( `phone`,`created_by`,`admin_id`,`created_at`,`updated_at`) VALUES ( '$contact_phone','$user_id','$admin_id','$created_at','$updated_at') ";
							$qry_result = $this->db_query($qry, array());

				 	$result = $qry_result == 1 ? 1 : 0;	
						if($result == 1){
						$qry = "select *, (select user_name from user where user_id = contacts.created_by) as creater,(select sip_login from user where user_id = contacts.created_by) as extnsioin, (select user_name from user where user_id = contacts.modified_by) as modifier from contacts where phone='$contact_phone' and admin_id='$adminid'";
							$results = $this->fetchData($qry, array());
							
							$con_id = $results['contact_id'];
							$created_by = $results['created_by'];
							$admin_id = $results['admin_id'];
							$auxcode_name = $results['auxcode_name'];
							$notes = $results['created_at'].': Incoming call from  '.$results['phone'].' to '.$results['extnsioin']; 
								
								$qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`,`auxcode_name`,`created_at`,`updated_at`) VALUES ('$con_id', '$created_by', '$admin_id', '$notes', '$auxcode_name', '$created_at', '$updated_at')";
					$qry_result = $this->db_query($qry, array());
				
							$res = $qry_result == 1 ? 1 : 0;	
			 	 
					/*if($res=='1' && $auxcode_name=='DND'){
						$sel_qry="UPDATE predective_dialer_contact set dnd='1' where phone_number='$phone' and   admin_id='$admin_id' ";						
						$qry_res = $this->db_query($sel_qry, array());
					}*/
						
						$ph= $results['phone'];
					 $get_path = $this->fetchone("SELECT rec_path FROM `call_details` where from_no='$ph' order by id DESC LIMIT 1", array());
					//$results['rec_path']='';
							return  $results;
							
						}

				
				
			} else {
				$ph= $results['phone'];
					 $get_path = $this->fetchone("SELECT rec_path FROM `call_details` where from_no='$ph' order by id DESC LIMIT 1", array());
					//$results['rec_path']='';
				return  $results;
			}
				}

			
			

				
			}
		
			public function getContactsnotes($data){

            extract($data);
			$qry = "select *, (select user_name from user where user_id = contact_notes.agent_id) as creater from contact_notes where contact_id='$contact_id' ORDER BY note DESC";

             $results = $this->dataFetchAll($qry, array());
				return  $results;
			}
		
			public function hasContactAcc($data){

            extract($data);
	//echo "select * from user where user_id='$user_id'";exit;
			$qry = "select * from user where user_id='$user_id'";
				
			$results = $this->fetchData($qry, array());
			if($results['user_type'] == '2'){ 
	
				//$qry = "select * from admin_details where admin_id='$user_id'";
				//$qry="select admin_details.*,user.sip_login from admin_details INNER JOIN user on user.user_id=admin_details.admin_id where admin_details.admin_id='$user_id'";
					$qry="select admin_details.*,user.sip_login,user.agent_name,user.signature_strategy,timezone.name as timezone_name from admin_details INNER JOIN user on user.user_id=admin_details.admin_id LEFT JOIN timezone on timezone.id=user.timezone_id  where admin_details.admin_id='$user_id'";
			$results = $this->fetchData($qry, array());
			//print_r($qry);exit;

			} else {
				
				//$qry = "select * from user where user_id='$user_id'";
					//$qry = "select user.*,timezone.name as timezone_name from user LEFT JOIN timezone on timezone.id=user.timezone_id  where user.user_id=$user_id";
				   $qry = "select user.*,timezone.name as timezone_name,admin_details.override,admin_details.ticket_limit from user LEFT JOIN timezone on timezone.id=user.timezone_id LEFT JOIN admin_details on admin_details.admin_id = user.admin_id  where user.user_id=$user_id";
			$results = $this->fetchData($qry, array());
			//print_r($qry);exit;
			}

				return  $results;
			}
		
		
		public function getConatctByID($data){
		    extract($data);
			$qry = "select * from contacts where contact_id='$contact_id'";

             $results = $this->dataFetchAll($qry, array());
				return  $results;
		}
		
		
		
	public function getAllMUsers($data){
		    extract($data);
		    $qry = "select * from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
			if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
			//$qry = "select * from user where admin_id='$user_id'";
		if($result['user_type']!='2'){
			if($result['user_type']=='1'){
			 $get_agents="SELECT agent_group.agents FROM `user` LEFT JOIN agent_group on agent_group.admin_id=IF(user.admin_id = 1, user.user_id , user.admin_id ) and agent_group.id=user.ag_group where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and user.user_id='$user_id'";		  $agns = $this->fetchOne($get_agents,array());
		 $agns="'" . str_replace(",", "','", $agns) . "'";
			$qry = "select * from user where IF(admin_id = 1, user_id, admin_id)='$admin_id' and user_id IN($agns) and delete_status='0'";
			}else{
			 $qry = "select * from user where IF(admin_id = 1, user_id, admin_id)='$admin_id' and delete_status='0'";
			}
		}else{
			 $qry = "select * from user where admin_id='$admin_id' and delete_status='0'";
		}
             $results = $this->dataFetchAll($qry, array());
			return  $results;
		}
		public function getAllContactsNumber($data){
		    extract($data);
		 $qry = "select * from user where user_id='$user_id'";
                
             $result = $this->fetchData($qry, array());
			
			if($result['user_type'] == '2'){ $admin_id = $user_id; } else { $admin_id = $result['admin_id']; }
			
			$qry = "select * from contacts where phone LIKE '%$phone_num%' and admin_id = '$admin_id'";
			
             $results = $this->dataFetchAll($qry, array());
			return  $results;
		}
public function csvBulkImport($data){
		$user_id = $data['user_id'];
	 	$file = $_FILES['file']['tmp_name'];
		
		$qry = "select * from user where user_id='$user_id'";
				
			$results = $this->fetchData($qry, array());
			
			if($results['user_type'] == '2'){ 
				$admin_id = $user_id;
			} else {
			$admin_id = $results['admin_id'];
			}
	
		if($file ==''){
		$result_data["result"]["status"] = false;
			print_r( json_encode($result_data));
			exit;
		}
		
     $handle = fopen($file, "r");
fgetcsv($handle);
	
     $c = 0;
     while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
     {
        $qry = "INSERT INTO `contacts` (`contact_owner`, `first_name`, `last_name`, `account_name`, `lead_source`, `title`, `email`, `department`, `activity`, `res_dept`, `phone`, `home_phone`, `office_phone`, `fax`, `mobile`, `dob`, `assistant`, `assitant_phone`, `reports_to`, `email_opt_out`, `skype`, `secondary_email`, `twitter`, `reporting_to`, `mailing_street`, `other_street`, `mailing_city`, `other_city`, `mailing_province`, `other_province`, `mailing_postal_code`, `other_postal_code`, `mailing_country`, `other_country`, `facebook_url`, `whatsapp_number`, `line`, `wechat`, `viber`, `telegram`, `instagram_url`, `linkedin`, `country_code`, `created_by`, `admin_id`) VALUES ('$filesop[0]','$filesop[1]', '$filesop[2]', '$filesop[3]', '$filesop[4]', '$filesop[5]', '$filesop[6]', '$filesop[7]', '$filesop[8]', '$filesop[9]', '$filesop[10]', '$filesop[11]', '$filesop[12]', '$filesop[13]', '$filesop[14]', '$filesop[15]', '$filesop[16]', '$filesop[17]', '$filesop[18]', '$filesop[19]', '$filesop[20]', '$filesop[21]', '$filesop[22]', '$filesop[23]', '$filesop[24]', '$filesop[25]', '$filesop[26]', '$filesop[27]', '$filesop[28]', '$filesop[29]', '$filesop[30]', '$filesop[31]', '$filesop[31]', '$filesop[32]', '$filesop[33]', '$filesop[34]', '$filesop[35]', '$filesop[36]', '$filesop[37]', '$filesop[38]', '$filesop[39]', '$filesop[40]', '$filesop[41]','$user_id','$admin_id')";
		  $qry_result = $this->db_query($qry, array());
          $c = $c + 1;
     }
		$result_data["result"]["status"] = true;
			print_r( json_encode($result_data));
		
		
	}

public function deleteContact($data){
		    extract($data);



			$qry = "DELETE FROM `contacts` WHERE `contacts`.`contact_id` = '$contact_id'";
			
            $results = $this->db_query($qry, array());



if($results == '1'){ 

		$qry = "DELETE FROM `contact_notes` WHERE `contact_notes`.`contact_id` = '$contact_id'";
			
        $results = $this->db_query($qry, array());
}

			return  $results;
		}

		public function contactReport($data){
			
			extract($data);
			if($phone == ''){
				if($fromDate !=='' and $toDate !== '' and $fromDate !== $toDate){
					$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and date(created_at)>='$fromDate' and date(created_at)<='$toDate'";
				} 
					elseif($fromDate == $toDate){
					$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and created_at like '%$fromDate%'";
				} 
					else {
					$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and contact_id = (select contact_id from contacts where phone = '$phone')";
				}
		} else {
			
				if($fromDate !=='' and $toDate !== '' and $fromDate !== $toDate){
					$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and date(created_at)>='$fromDate' and date(created_at)<='$toDate' and contact_id = (select contact_id from contacts where phone = '$phone')";
				} 
					elseif($fromDate == $toDate){
						$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and created_at like '%$fromDate%' and contact_id = (select contact_id from contacts where phone = '$phone')";
				} 
					else {
						$qry = "select (select contact_owner from contacts where contact_id = contact_notes.contact_id) as name,(select phone from contacts where contact_id = contact_notes.contact_id) as phone,(select user_name from user where user_id=contact_notes.agent_id) as agent, notes, (select department_name from departments where dept_id =  (select department from contacts where contact_id = contact_notes.contact_id)) as department, (select activity from contacts where contact_id = contact_notes.contact_id) as activity, date_format(created_at, '%d/%m/%Y') as created_date,date_format(created_at, '%H:%i:%s') as created_time, (select department_name from departments where dept_id =  (select res_dept from contacts where contact_id = contact_notes.contact_id)) as res_dept from contact_notes where agent_id IN ($agents) and notes NOT LIKE '%Incoming call%' and  contact_id = (select contact_id from contacts where phone = '$phone')";
				}
			
		}
			//echo $qry;
			
			//exit;

			$results = $this->dataFetchAll($qry, array());
		
			return  $results;
		}
public function addAuxcode($data){
		extract($data);	
		$qry = "select * from aux_code where auxcode_name LIKE '%$auxcode_name%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("id"=>$id));		
		 if($result > 0){
			 	$result = 2;
				return $result;
		 } else {
		   $user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
           $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		   $timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";			 
           $timezone = $this->fetchmydata($timezone_qry,array());	
           date_default_timezone_set($timezone);  
     	   $created_at = date("Y-m-d H:i:s");
     	   $updated_at = date("Y-m-d H:i:s");             $datas=array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id,"created_at"=>$created_at,"updated_at"=>$updated_at);
		   $qry = $this->generateCreateQry($datas, "aux_code");
            $insert_data = $this->db_insert($qry, $datas);           
            if($insert_data != 0){
               $result = 1; 
			
            }
            else{                
                $result = 0;
            }            
            return $result;
		 }
	}
	public function updateAuxcode($data){
		extract($data);		
		$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
		$timezone = $this->fetchmydata($timezone_qry,array());	
        date_default_timezone_set($timezone);       	
     	$updated_at = date("Y-m-d H:i:s");
		$qry = "UPDATE aux_code SET auxcode_name='$auxcode_name',admin_id='$admin_id',updated_at='$updated_at' where id='$auxcode_id'";
		$update_data = $this->db_query($qry, $params);
	    if($update_data != 0){
	        $result = 1;
	    }
	    else{
	        $result = 0;
	    }
	    return $result;
	}
	public function getAuxcode($id){
	    $qry = "select * from aux_code where delete_status != 1 and admin_id IN ($id,1)";
         // print_r($qry); exit;  
		return $this->dataFetchAll($qry, array("admin_id"=>$id));
	}
	public function getAuxcode_data($data){
		extract($data);
	    $qry = "select * from aux_code where id ='$auxcode_id' and admin_id='$admin_id'";		
		return $this->fetchData($qry, $params);
	}
	public function delete_auxcode($data){
      extract($data);
      $qry = "Delete from  aux_code where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function add_senderid($data){
		extract($data);	
		$qry = "select * from sender_id where senderID LIKE '%$senderid%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("id"=>$id));		
		 if($result > 0){
			 	$result = 2;
				return $result;
		 } else {
		   $user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
           $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		   $timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";			 
           $timezone = $this->fetchmydata($timezone_qry,array());	
           date_default_timezone_set($timezone);  
     	   $created_at = date("Y-m-d H:i:s");
     	   $updated_at = date("Y-m-d H:i:s");             
     	   $datas=array("senderID"=>$senderid,"admin_id"=>$admin_id,"created_at"=>$created_at,"updated_at"=>$updated_at);
		   $qry = $this->generateCreateQry($datas, "sender_id");
            $insert_data = $this->db_insert($qry, $datas);           
            if($insert_data != 0){
               $result = 1;              
            }
            else{                
                $result = 0;
            }            
            return $result;
		 }
	}
	public function delete_senderid($data){
      extract($data);
      $qry = "Update sender_id SET delete_status='1' where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function get_senderid($admin_id){
	    $qry = "select * from sender_id where delete_status != 1 and admin_id ='$admin_id'";
	    $qry_price = "SELECT price_sms FROM `admin_details` where admin_id ='$admin_id'";
		//return $this->dataFetchAll($qry, array("admin_id"=>$id));
		$parms = array();
            $result["sender_id"] = $this->dataFetchAll($qry, array("admin_id"=>$id));
			$result["sms_price"] = $this->fetchOne($qry_price, $parms);
		return $result;
	}
	public function add_sms_group($data){
		extract($data);
		$qry = "select * from sms_group where group_name LIKE '%$group_name%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array());		
		if($result > 0){
			$result = 2;
			return $result;
		 } else {				   
            $qry_result = $this->db_query("INSERT INTO sms_group(group_name,group_users,admin_id,status) values ('$group_name','$group_users','$admin_id','1')", array());        
            $insert_data = $qry_result == 1 ? 1 : 0;            
            if($insert_data != 0){
               $result = 1;              
            }
            else{                
                $result = 0;
            }            
            return $result;
		 }
	}
	public function list_smsgroup($data){
        extract($data); 
		//$limit = 2;  
        //echo "select * from sms_group where admin_id ='$admin_id' LIMIT $limit";exit;
        $qry = "select * from sms_group where delete_status != 1 and admin_id ='$admin_id'";        
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	/*public function edit_smsgroup($data){
		extract($data);
        $qry = "select * from sms_group where group_id='$group_id' and admin_id='$admin_id'";
        $result = $this->dataFetchAll($qry,array());
		$ids = $result[0]['group_users'];
		//echo "select * from user where user_id IN ($ids)";exit;
		$qry = "select * from user where user_id IN ($ids)";
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }*/
	public function edit_smsgroup($group_id){
		$qry = "select * from sms_group where group_id ='$group_id'";
		return $this->dataFetchAll($qry, array("group_id"=>$group_id));
	}
	public function update_smsgroup($data){
	    extract($data);//print_r($data);exit;		
		$qry = "UPDATE sms_group SET group_name='$group_name',group_users='$group_users',status='$status',admin_id='$admin_id' where group_id='$group_id'";
		$update_data = $this->db_query($qry, $params);
        if($update_data != 0){
            $result = 1;
        }
        else{
            $result = 0;
        }
        return $result;
	}
	public function delete_smsgroup($data){
      extract($data);
      $qry = "Update sms_group SET delete_status='1' where group_id='$group_id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function csvGroupBulkImport($data){
		$user_id = $data['user_id'];
		$user_qry = "select * from user where user_id ='$user_id'";
	    $user_result =  $this->fetchData($user_qry, array());
	    $admin_id = $user_result['admin_id'];
		$user_timezone = $user_result['timezone_id'];
	    if($admin_id==1){
	      $aid = $user_id;
	    }else{
	      $aid = $admin_id;
	    }
		$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
        $timezone = $this->fetchmydata($timezone_qry,array());	
        date_default_timezone_set($timezone);  
     	$created_at = date("Y-m-d H:i:s");
     	$updated_at = date("Y-m-d H:i:s");
		//$group_name = $data['group_name'];
		$group_name = 'dummny';
	 	$file = $_FILES['file']['tmp_name'];	 		
		if($file ==''){
		$result_data["result"]["status"] = false;
			print_r( json_encode($result_data));
			exit;
		}		
        $handle = fopen($file, "r");
        fgetcsv($handle);	
        $c = 0;
        while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
        {
          $qry = "INSERT INTO `contacts` (`contact_owner`, `first_name`, `last_name`, `account_name`, `lead_source`, `title`, `email`, `department`, `activity`, `res_dept`, `phone`, `home_phone`, `office_phone`, `fax`, `mobile`, `dob`, `assistant`, `assitant_phone`, `reports_to`, `email_opt_out`, `skype`, `secondary_email`, `twitter`, `reporting_to`, `mailing_street`, `other_street`, `mailing_city`, `other_city`, `mailing_province`, `other_province`, `mailing_postal_code`, `other_postal_code`, `mailing_country`, `other_country`,`created_by`,`admin_id`) VALUES ('$filesop[0]','$filesop[1]', '$filesop[2]', '$filesop[3]', '$filesop[4]', '$filesop[5]', '$filesop[6]', '$filesop[7]', '$filesop[8]', '$filesop[9]', '$filesop[10]', '$filesop[11]', '$filesop[12]', '$filesop[13]', '$filesop[14]', '$filesop[15]', '$filesop[16]', '$filesop[17]', '$filesop[18]', '$filesop[19]', '$filesop[20]', '$filesop[21]', '$filesop[22]', '$filesop[23]', '$filesop[24]', '$filesop[25]', '$filesop[26]', '$filesop[27]', '$filesop[28]', '$filesop[29]', '$filesop[30]', '$filesop[31]', '$filesop[32]', '$filesop[33]','$user_id','$aid')";
		
		  $qry_result = $this->db_query($qry, array());
          $c = $c + 1;
        }
        
		//echo "select phone from contacts ORDER BY contact_id DESC LIMIT $c";exit;
        $cidqry = "select phone from contacts ORDER BY contact_id DESC LIMIT $c";        
		$cidqry_value = $this->dataFetchAll($cidqry, array());
		foreach($cidqry_value as $rec){
		  $ids[] = $rec["phone"];
		}
		$implode = implode(',', $ids);
		$update_qry = "Update sms_group SET group_users='$implode',created_at='$created_at',updated_at='$updated_at' where group_name='$group_name' and admin_id='$aid'";
        $parms = array();
        $results = $this->db_query($update_qry,$parms);
		//print_r($cidqry_value);
		//print_r($implode);exit;
		//$qry_result = $this->db_query("INSERT INTO sms_group(group_name,group_users,admin_id,status) values ('$group_name','$implode',$aid,'1')", array());
		$result_data["result"]["status"] = true;
		print_r( json_encode($result_data));	
	}
			
public function precsvBulkImport($data){
	//print_r($data);exit;
		$user_id = $data['user_id'];
	 	$file = $_FILES['file']['tmp_name'];
		$admin_id = $data['admin_id'];
		
		if($file ==''){
		$result_data["result"]["status"] = false;
			print_r( json_encode($result_data));
			exit;
		}
		
     $handle = fopen($file, "r");
fgetcsv($handle);
	
     $c = 0;
     while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
     {
		 $phone=$filesop[7];
		 if($admin_id=='564'){
		  $numlength = strlen((string)$phone);
 		 $rest = substr($phone, 0, 3);
			   if($rest=='971'){
      $phone = substr_replace($phone, 0, 0, 3);
    }else{
     $phone='00'.$phone;
    }
}
		
        $qry = "INSERT INTO `predective_dialer_contact` (`user_id`, `admin_id`, `campaign_id`, `customer_name`, `address`, `city`, `state`, `zipcode`, `country`, `phone_number`, `source_data`, `notes`, `queue_status`) VALUES ('$user_id','$admin_id', '$filesop[0]', '$filesop[1]', '$filesop[2]', '$filesop[3]', '$filesop[4]', '$filesop[5]', '$filesop[6]', '$phone', '$filesop[8]', '$filesop[9]', '0')";
	//echo $qry;exit;
		  $qry_result = $this->db_query($qry, array());
          $c = $c + 1;
     }
		$result_data["result"]["status"] = true;
			print_r( json_encode($result_data));
		
		
	}
		 public function getWebrtcServer($user_id){
            $qry = "select webrtc_server from user where user_id='$user_id'";
            $result = $this->fetchData($qry, array());
			$webrtc_server = $result['webrtc_server'];
	  		$qry = "SELECT server_fqdn,server_id FROM webrtc_servers where server_id=$webrtc_server";
			$webrtc_servers = $this->fetchData($qry, array());
           return $webrtc_servers;
         
        }
	
	 public function add_auxcode_wall($data){
            extract($data);
		 if($type=='incoming'){
		 $call_det='Call From '.$to_no;
		 }else{
			  $call_det='Call to '.$to_no;
		 }
$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";			 
$user_timezone = $this->fetchmydata($user_timezone_qry,array());
$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
$timezone = $this->fetchmydata($timezone_qry,array());	
date_default_timezone_set($timezone);  
$call_start_date = date("Y-m-d H:i:s");		 
           $qry = "INSERT INTO `call_history` (`call_data`, `phone`, `call_type`, `call_status`, `call_view_status`, `auxcode_name`, `call_start_dt`, `call_end_dt`, `user_id`, `customer_id`,cat_id,call_note) VALUES ('$call_det', '$from_no', '$type', 'open', '1', '$aux_code', '$call_start_date', '$call_start_date', '$user_id', '$user_id','$cat_id','$call_note')";
	//echo $qry;exit;
		  $qry_result = $this->db_query($qry, array());
		  $result = $qry_result == 1 ? 1 : 0;	
      		return  $result;
        }

public function add_aux_code_category($data){
		extract($data);	
		$qry = "select * from aux_code_category where category_name LIKE '%$category_name%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("id"=>$id));		
		 if($result > 0){
			 	$result = 2;
				return $result;
		 } else {
		   $user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
           $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		   $timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";			 
           $timezone = $this->fetchmydata($timezone_qry,array());	
           date_default_timezone_set($timezone);  
     	   $created_at = date("Y-m-d H:i:s");
     	   $updated_at = date("Y-m-d H:i:s");             $datas=array("category_name"=>$category_name,"admin_id"=>$admin_id,"created_at"=>$created_at,"updated_at"=>$updated_at,"dept_id"=>$dept_id);
		   $qry = $this->generateCreateQry($datas, "aux_code_category");
            $insert_data = $this->db_insert($qry, $datas);           
            if($insert_data != 0){
               $result = 1; 
				$ex_dep=explode(',',$dept_id);
				$count_dep= count($ex_dep);
				for($i=0; $i<$count_dep; $i++){
	$dep_wrap = $this->fetchmydata("SELECT department_wrapups FROM departments WHERE  dept_id ='$ex_dep[$i]'",array());	
					if($dep_wrap!=''){
						$dep_wrap=$dep_wrap.','.$insert_data;
					}else{
						$dep_wrap=$insert_data;
					}
					//echo $dep_wrap;exit;
			 $qry = "UPDATE departments SET department_wrapups='$dep_wrap' where dept_id ='$ex_dep[$i]'";
					$update_data = $this->db_query($qry, $params);
				}
			
            }
            else{                
                $result = 0;
            }            
            return $result;
		 }
	}
	public function getAuxcode_category($data){
		extract($data);
		if($admin_id == $user_id){
			//$qry = "select * from aux_code_category where delete_status != 1 and admin_id IN ($admin_id,1)";
			//$qry="select aux_code_category.*,departments.department_name from aux_code_category LEFT JOIN departments on departments.dept_id=aux_code_category.dept_id where aux_code_category.delete_status != 1 and aux_code_category.admin_id IN ($admin_id,1)";
			
			$qry="select aux_code_category.*,GROUP_CONCAT(departments.department_name) as department_name from aux_code_category LEFT JOIN departments on FIND_IN_SET(departments.dept_id,aux_code_category.dept_id)  where aux_code_category.delete_status != 1 and departments.admin_id=aux_code_category.admin_id and aux_code_category.admin_id IN ($admin_id) group by aux_code_category.id";
			
		} else {
			$qry1= "SELECT GROUP_CONCAT(DISTINCT department_wrapups) as dep FROM departments WHERE FIND_IN_SET($user_id, department_users) and delete_status != 1 and department_wrapups IS NOT NULL";
		//echo $qry1;exit;
			$qry1 =  $this->dataFetchAll($qry1, $params);
			$deptData=$qry1[0][dep];
			//echo $deptData;exit;
			//$deptData = $qry1[0]['GROUP_CONCAT(DISTINCT department_wrapups)'];	
			
			//$qry = "select aux_code_category.*,departments.department_name from aux_code_category  LEFT JOIN departments on departments.dept_id=aux_code_category.dept_id where aux_code_category.id IN ($deptData)";
			$qry="select aux_code_category.*, GROUP_CONCAT(departments.department_name) as department_name from aux_code_category  LEFT JOIN departments on FIND_IN_SET(departments.dept_id,aux_code_category.dept_id) and departments.admin_id=aux_code_category.admin_id where aux_code_category.id IN ($deptData) group by aux_code_category.id";
		//echo $qry;exit;
			
		}
		
		return $this->dataFetchAll($qry, array("admin_id"=>$id));
	}	
		
		public function aux_code_category_list($data){
		extract($data);
		if($admin_id == $user_id){
		
			//$qry = "select * from aux_code_category where delete_status != 1 and admin_id IN ($admin_id,1)";
			//$qry="select aux_code_category.*,departments.department_name from aux_code_category LEFT JOIN departments on departments.dept_id=aux_code_category.dept_id where aux_code_category.delete_status != 1 and aux_code_category.admin_id IN ($admin_id,1)";
			
			$qry="select aux_code_category.*,departments.dept_id as department_id from aux_code_category LEFT JOIN departments on FIND_IN_SET(departments.dept_id,aux_code_category.dept_id)  where aux_code_category.delete_status != 1 and departments.admin_id=aux_code_category.admin_id  and aux_code_category.admin_id IN ($admin_id)";
			
		} else {
			
			$qry1= "SELECT GROUP_CONCAT(DISTINCT department_wrapups) as dep FROM departments WHERE FIND_IN_SET($user_id, department_users) and delete_status != 1 and department_wrapups IS NOT NULL";
		
			$qry1 =  $this->dataFetchAll($qry1, $params);
			$deptData=$qry1[0][dep];
			//$deptData = $qry1[0]['GROUP_CONCAT(DISTINCT department_wrapups)'];	
			
			//$qry = "select aux_code_category.*,departments.department_name from aux_code_category  LEFT JOIN departments on departments.dept_id=aux_code_category.dept_id where aux_code_category.id IN ($deptData)";
			$qry="select aux_code_category.*, departments.dept_id as department_id from aux_code_category  LEFT JOIN departments on FIND_IN_SET(departments.dept_id,aux_code_category.dept_id) and departments.admin_id=aux_code_category.admin_id where aux_code_category.id IN ($deptData) ";
			
			
		}
			
		//echo $qry;exit;
		return $this->dataFetchAll($qry, array("admin_id"=>$id));
	}
		
	public function editAuxcode_category($data){
		extract($data);
	     //$qry = "select * from aux_code_category where id ='$cat_id' and admin_id='$admin_id'";	
		$qry="select cat.*,GROUP_CONCAT(dep.department_name) as department_name from aux_code_category cat INNER JOIN departments dep ON FIND_IN_SET(dep.dept_id, cat.dept_id) where cat.id ='$cat_id' and cat.admin_id='$admin_id'";
		return $this->fetchData($qry, $params);
	}
	public function updateAuxcode_category($data){
		extract($data);		
		$user_timezone_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";			 
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		$timezone_qry = "SELECT name FROM timezone WHERE id='$user_timezone'";
		$timezone = $this->fetchmydata($timezone_qry,array());	
        date_default_timezone_set($timezone);       	
     	$updated_at = date("Y-m-d H:i:s");
		$qry = "UPDATE aux_code_category SET category_name='$category_name',admin_id='$admin_id',updated_at='$updated_at', dept_id ='$dept_id' where id='$cat_id'";
		$update_data = $this->db_query($qry, $params);
		//echo $update_data;exit;
	    if($update_data != 0){
	        $result = 1;
			$ex_dep=explode(',',$dept_id);
			$count_dep=count($ex_dep);
			for($i=0; $i<$count_dep; $i++){
						
			$is_cat = "SELECT department_wrapups FROM departments WHERE dept_id='$ex_dep[$i]'";
			$is_cat_id = $this->fetchmydata($is_cat,array());
			if($is_cat_id!=''){
				if (!in_array($cat_id, $is_cat_id))
				{
					$cat_id1=$is_cat_id.','.$cat_id;
				}
			}else{
				$cat_id1=$cat_id;
			}
				
				
			/*if($is_cat_id !=''){
				$var=implode(',',$is_cat_id);
				$cat_id=$var;
			}*/
		$qry = "UPDATE departments SET department_wrapups='$cat_id1' where dept_id ='$ex_dep[$i]'";
		$update_data = $this->db_query($qry, $params);
			}
	    }
	    else{
	        $result = 0;
	    }
	    return $result;
	}

	public function delete_auxcode_category($data){
      extract($data);
		//echo"adk";exit;
		$del=$this->db_query("DELETE from `aux_code` where cat_id='$id'",array());
         // $qry = "Update aux_code_category SET delete_status='1' where id='$id' and admin_id='$admin_id'";
         $qry = "DELETE from aux_code_category where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function getuax_by_cat($data){
		extract($data);
	    $qry = "select * from aux_code where cat_id ='$cat_id' and admin_id='$admin_id'";		
		return $this->dataFetchAll($qry, $params);
	}
	public function checkAuxcode_data($data){
		extract($data);
	    $check_qry = "SELECT id FROM `aux_code` WHERE `auxcode_name` LIKE '%$aux_code%' AND cat_id ='$cat_id' AND admin_id='$admin_id'";//echo $check_qry;exit;
	    $id = $this->fetchmydata($check_qry,array());
	    if($id==''){
	      $result = 1;	      
	    }else{
	      $result = 0;
	    }
	    return $result;
	}	
	}