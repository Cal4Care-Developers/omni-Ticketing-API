<?php

	class predective_dialer_contact extends restApi{
	public function createContact($user_id,$admin_id,$campaign_id,$customer_name,$address,$city,$state,$zipcode,$country,$phone_number,$source_data,$notes){			
	  $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		
      date_default_timezone_set($user_timezone);  
      $created_at = date("Y-m-d H:i:s");
	  $updated_at = date("Y-m-d H:i:s");  
	  $qry = $this->db_query("INSERT INTO predective_dialer_contact(`user_id`, `admin_id`, `campaign_id`, `customer_name`, `address`, `city`, `state`, `zipcode`, `country`, `phone_number`, `source_data`, `notes`, `created_at`, `updated_at`) VALUES ('$user_id','$admin_id','$campaign_id','$customer_name','$address','$city','$state','$zipcode','$country','$phone_number','$source_data','$notes','$created_at','$updated_at')", array());		
	  $result = $qry == 1 ? 1 : 0;		
      return $result;		
    }
	public function get_campaign($admin_id){
		$qry = "select camp_id,camp_name from campaign where admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array());
	}
	public function editContacts($data){
      extract($data); 
	  $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);  
      $created_at = date("Y-m-d H:i:s");
      $updated_at = date("Y-m-d H:i:s");
     // $qry = "select *, (select camp_name from campaign where camp_id = predective_dialer_contact.campaign_id) as campaign_name from predective_dialer_contact where id='$contact_id'";		
		$qry="SELECT * from campaign INNER join predective_dialer_contact on predective_dialer_contact.campaign_id=campaign.camp_id and predective_dialer_contact.admin_id=campaign.admin_id where predective_dialer_contact.id='$contact_id' and campaign.agent_id='$user_id'";
	  $result = $this->fetchData($qry, array());		
	  $contact_options = array('id' => $result['id'], 'user_id' => $result['user_id'], 'admin_id' => $result['admin_id'], 'campaign_id' => $result['campaign_id'], 'customer_name' => $result['customer_name'], 'address'=> $result['address'], 'city' => $result['city'], 'state' => $result['state'], 'zipcode' => $result['zipcode'], 'country' => $result['country'], 'phone_number' => $result['phone_number'], 'source_data' => $result['source_data'], 'notes' => $result['notes'], 'created_at' => $result['created_at'], 'updated_at' => $result['updated_at'], 'campaign_name' => $result['camp_name']);		
	  /*$adminid_qry = "select * from user where user_id='$user_id'";
	  $result_dat = $this->fetchData($adminid_qry, array());
	  if($result_dat['user_type'] == '2'){ $adminid_value = $user_id; } else { $adminid_value = $result_dat['admin_id'];       }*/
		$admin_id = $result['admin_id'];
	  $campaign_array_qry = "SELECT camp_id,camp_name FROM campaign WHERE admin_id='$admin_id'";
	  $campaign_array = $this->dataFetchAll($campaign_array_qry, array());		
	  for($i = 0; count($campaign_array) > $i; $i++){
		  $campaign_id = $campaign_array[$i]['camp_id'];
          $campaign_name = $campaign_array[$i]['camp_name'];
		  $campaign_options = array('campaign_id' => $campaign_id, 'campaign_name' => $campaign_name);
          $campaign_options_array[] = $campaign_options;
	 }
	 $campaign_options_array = array('campaign_options' => $campaign_options_array);		
	 $status = array('status' => 'true');
	 $merge_result = array_merge($status, $contact_options, $campaign_options_array);          
     $tarray = json_encode($merge_result);           
     print_r($tarray);exit;
  }
  public function updateContact($contact_id,$user_id,$admin_id,$campaign_id,$customer_name,$address,$city,$state,$zipcode,$country,$phone_number,$source_data,$notes){	
	  //echo "UPDATE `predective_dialer_contact` SET `user_id` = '$user_id', `admin_id` = '$admin_id', `campaign_id` = '$campaign_id', `customer_name` = '$customer_name', `address` = '$address', `city` = '$city', `state` = '$state', `zipcode` = '$zipcode', `country` = '$country',`phone_number` = '$phone_number', `source_data` = '$source_data', `notes` = '$notes', `updated_at` = '$updated_at' WHERE `id` = '$contact_id'";exit;
      	$qry = "UPDATE `predective_dialer_contact` SET `user_id` = '$user_id', `admin_id` = '$admin_id', `campaign_id` = '$campaign_id', `customer_name` = '$customer_name', `address` = '$address', `city` = '$city', `state` = '$state', `zipcode` = '$zipcode', `country` = '$country',`phone_number` = '$phone_number', `source_data` = '$source_data', `notes` = '$notes', `updated_at` = '$updated_at' WHERE `id` = '$contact_id'"; 
		$qry_result = $this->db_query($qry, array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
   }
		
   public function listContacts($data){
	  // print_r($data);exit;
        extract($data);					
        //$qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
       // extract($qry_limit_data);
        $search_qry = "";
        if($search_text != ""){
                $search_qry = " and (campaign.camp_name like '%".$search_text."%' or campaign.customer_name like '%".$search_text."%' or campaign.address like '%".$search_text."%' or campaign.source_data like '%".$search_text."%' or campaign.phone_number like '%".$search_text."%')";
        }
	    
	    $qry = "SELECT predective_dialer_contact.id, predective_dialer_contact.campaign_id, predective_dialer_contact.customer_name, predective_dialer_contact.address, predective_dialer_contact.country, predective_dialer_contact.source_data, predective_dialer_contact.phone_number,campaign.camp_name from predective_dialer_contact,campaign where campaign.camp_id=predective_dialer_contact.campaign_id and dnd='0' and delete_status != 1 and user_id='$user_id' and campaign.admin_id='$user_id' and campaign.call_repeat>=predective_dialer_contact.queue_status".$search_qry;
	 
        $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;		
	   //echo $detail_qry;exit;
	 		$parms = array();
        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;
        return $result;
   }
   public function delete_dialer_contact($data){
	      extract($data);
		$contact_id="'" . str_replace(",", "','", $contact_id) . "'";
	      $qry = "Update predective_dialer_contact SET delete_status='1' where id IN ($contact_id) and user_id='$user_id'";
	      $parms = array();
	      $results = $this->db_query($qry,$parms);      
	      $output = $results == 1 ? 1 : 0;    
	      return  $output;
  }		
 
  public function mrvoip_upload($data){ 
	      //print_r($data);
		  $user_id = $data['img_user_id'];
          $mrvoip_version = $data['mrvoip_version'];
          $sec_title = $data['sec_title'];
          $sec2_title = $data['sec2_title'];
          // Mrvoip main document code starts here	  
		  if(isset($_FILES["mrvoip_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $main_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["mrvoip_main"]["name"]);  
		      $main_document_target_path = $destination_path."mrvoip/". basename( $_FILES["mrvoip_main"]["name"]);
		      move_uploaded_file($_FILES['mrvoip_main']['tmp_name'], $main_document_target_path);        		      
		    }else{      
		      $main_document_qry = "SELECT main_document FROM mrvoip_document WHERE id=1";
		      $main_document_upload_path = $this->fetchmydata($main_document_qry,array());
		    } 
		    // mrvoip main document code ends here
           // linux1 document code starts here	  
		  if(isset($_FILES["linux_document_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $linux1_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["linux_document_1"]["name"]);  
		      $linux1_document_target_path = $destination_path."mrvoip/". basename( $_FILES["linux_document_1"]["name"]);
		      move_uploaded_file($_FILES['linux_document_1']['tmp_name'], $linux1_document_target_path);        		      
		    }else{      
		      $linux1_document_qry = "SELECT linux_document_1 FROM mrvoip_document WHERE id=1";
		      $linux1_document_upload_path = $this->fetchmydata($linux1_document_qry,array());
		    } 
		    // linux1 document code ends here

		    // windows1 document code starts here	  
		  if(isset($_FILES["windows_document_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $windows1_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["windows_document_1"]["name"]);  
		      $windows1_document_target_path = $destination_path."mrvoip/". basename( $_FILES["windows_document_1"]["name"]);
		      move_uploaded_file($_FILES['windows_document_1']['tmp_name'], $windows1_document_target_path);        		      
		    }else{      
		      $windows1_document_qry = "SELECT windows_document_1 FROM mrvoip_document WHERE id=1";
		      $windows1_document_upload_path = $this->fetchmydata($windows1_document_qry,array());
		    } 
		    // windows1 document code ends here 
		  // linux document code starts here	  
		  if(isset($_FILES["mrvoip_linux"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $linux_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["mrvoip_linux"]["name"]);  
		      $linux_document_target_path = $destination_path."mrvoip/". basename( $_FILES["mrvoip_linux"]["name"]);
		      move_uploaded_file($_FILES['mrvoip_linux']['tmp_name'], $linux_document_target_path);        		      
		    }else{      
		      $linux_document_qry = "SELECT linux_document FROM mrvoip_document WHERE id=1";
		      $linux_document_upload_path = $this->fetchmydata($linux_document_qry,array());
		    } 
		    // linux document code ends here

		    // windows document code starts here	  
		  if(isset($_FILES["mrvoip_windows"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $windows_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["mrvoip_windows"]["name"]);  
		      $windows_document_target_path = $destination_path."mrvoip/". basename( $_FILES["mrvoip_windows"]["name"]);
		      move_uploaded_file($_FILES['mrvoip_windows']['tmp_name'], $windows_document_target_path);        		      
		    }else{      
		      $windows_document_qry = "SELECT windows_document FROM mrvoip_document WHERE id=1";
		      $windows_document_upload_path = $this->fetchmydata($windows_document_qry,array());
		    } 
		    // windows document code ends here
	  
	  
	  
	  
      
            

   // linux1 document code starts here	  
   if(isset($_FILES["linux2_doc"]))
   {	      		     
     $destination_path = getcwd().DIRECTORY_SEPARATOR;            
     $linux2_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["linux2_doc"]["name"]);  
     $linux2_document_target_path = $destination_path."mrvoip/". basename( $_FILES["linux2_doc"]["name"]);
     move_uploaded_file($_FILES['linux2_doc']['tmp_name'], $linux2_document_target_path);        		      
   }else{      
     $linux2_document_qry = "SELECT linux2_doc FROM mrvoip_document WHERE id=1";
     $linux2_document_upload_path = $this->fetchmydata($linux2_document_qry,array());
   } 
   // linux1 document code ends here

   // windows1 document code starts here	  
 if(isset($_FILES["windows2_doc"]))
   {	      		     
     $destination_path = getcwd().DIRECTORY_SEPARATOR;            
     $windows2_document_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["windows2_doc"]["name"]);  
     $windows2_document_target_path = $destination_path."mrvoip/". basename( $_FILES["windows2_doc"]["name"]);
     move_uploaded_file($_FILES['windows2_doc']['tmp_name'], $windows2_document_target_path);        		      
   }else{      
     $windows2_document_qry = "SELECT windows2_doc FROM mrvoip_document WHERE id=1";
     $windows2_document_upload_path = $this->fetchmydata($windows2_document_qry,array());
   } 
   // windows1 document code ends here 
 // linux document code starts here	  
 if(isset($_FILES["linux2_file"]))
   {	      		     
     $destination_path = getcwd().DIRECTORY_SEPARATOR;            
     $linux2_file_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["linux2_file"]["name"]);  
     $linux2_file_target_path = $destination_path."mrvoip/". basename( $_FILES["linux2_file"]["name"]);
     move_uploaded_file($_FILES['linux2_file']['tmp_name'], $linux2_file_target_path);        		      
   }else{      
     $linux2_file_qry = "SELECT linux2_file FROM mrvoip_document WHERE id=1";
     $linux2_file_upload_path = $this->fetchmydata($linux2_file_qry,array());
   } 
   // linux document code ends here

   // windows document code starts here	  
 if(isset($_FILES["window_file"]))
   {	      		     
     $destination_path = getcwd().DIRECTORY_SEPARATOR;            
     $windows2_file_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["window_file"]["name"]);  
     $windows2_document_target_path = $destination_path."mrvoip/". basename( $_FILES["window_file"]["name"]);
     move_uploaded_file($_FILES['window_file']['tmp_name'], $windows2_document_target_path);        		      
   }else{      
     $windows_document_qry = "SELECT window_file FROM mrvoip_document WHERE id=1";
     $windows2_file_upload_path = $this->fetchmydata($windows_document_qry,array());
   } 
   // windows document code ends here






	  
	  
    
	  
	  
		    $getqry = "select * from mrvoip_document";             
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE mrvoip_document SET main_document='$main_document_upload_path',linux_document_1='$linux1_document_upload_path',windows_document_1='$windows1_document_upload_path',linux_document='$linux_document_upload_path',windows_document='$windows_document_upload_path',mrvoip_version='$mrvoip_version',window_file='$windows2_file_upload_path',linux2_doc='$linux2_document_upload_path ',windows2_doc='$windows2_document_upload_path',linux2_file='$linux2_file_upload_path',sec_title='$sec_title',sec2_title='$sec2_title' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);			      
				  $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }else{
            	  $qry = "INSERT INTO mrvoip_document(admin_id,main_document,linux_document_1,windows_document_1,linux_document,windows_document,mrvoip_version,window_file,linux2_doc,windows2_doc,linux2_file,sec_title,sec2_title) VALUES ('$user_id', '$main_document_upload_path', '$linux1_document_upload_path', '$windows1_document_upload_path', '$linux_document_upload_path', '$windows_document_upload_path', '$mrvoip_version','$windows2_file_upload_path','$linux2_document_upload_path ','$windows2_document_upload_path','$linux2_file_upload_path','$sec_title','$sec2_title')";
		          $results = $this->db_query($qry, array()); 
		          $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit; 
			}       
		}
		
		public function agent_rating_upload($data){  
		  $user_id = $data['img_user_id'];
		  $agent_rating_version = $data['agent_rating_version'];
          // agent rating main document code starts here	  
		  if(isset($_FILES["agent_rating_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $agent_rating_main_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["agent_rating_main"]["name"]);  
		      $agent_rating_main_target_path = $destination_path."mrvoip/". basename( $_FILES["agent_rating_main"]["name"]);
		      move_uploaded_file($_FILES['agent_rating_main']['tmp_name'], $agent_rating_main_target_path);        		      
		    }else{      
		      $agent_rating_main_qry = "SELECT agent_rating_main FROM agent_rating_document WHERE id=1";
		      $agent_rating_main_upload_path = $this->fetchmydata($agent_rating_main_qry,array());
		    } 
		    // agent_rating main document code ends here

		  // agent_rating_1 document code starts here	  
		  if(isset($_FILES["agent_rating_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $agent_rating_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["agent_rating_1"]["name"]);  
		      $agent_rating_1_target_path = $destination_path."mrvoip/". basename( $_FILES["agent_rating_1"]["name"]);
		      move_uploaded_file($_FILES['agent_rating_1']['tmp_name'], $agent_rating_1_target_path);        		      
		    }else{      
		      $agent_rating_1_qry = "SELECT agent_rating_1 FROM agent_rating_document WHERE id=1";
		      $agent_rating_1_upload_path = $this->fetchmydata($agent_rating_1_qry,array());
		    } 
		    // agent_rating_1 document code ends here

		    // agent_rating_2 document code starts here	  
		  if(isset($_FILES["agent_rating_2"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $agent_rating_2_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["agent_rating_2"]["name"]);  
		      $agent_rating_2_target_path = $destination_path."mrvoip/". basename( $_FILES["agent_rating_2"]["name"]);
		      move_uploaded_file($_FILES['agent_rating_2']['tmp_name'], $agent_rating_2_target_path);        		      
		    }else{      
		      $agent_rating_2_qry = "SELECT agent_rating_2 FROM agent_rating_document WHERE id=1";
		      $agent_rating_2_upload_path = $this->fetchmydata($agent_rating_2_qry,array());
		    } 
		    // agent_rating_2 document code ends here
		    $getqry = "select * from agent_rating_document"; 
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE agent_rating_document SET agent_rating_main='$agent_rating_main_upload_path',agent_rating_1='$agent_rating_1_upload_path',agent_rating_2='$agent_rating_2_upload_path',agent_rating_version='$agent_rating_version' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }
            else{
            	  $qry = "INSERT INTO agent_rating_document(admin_id,agent_rating_main,agent_rating_1,agent_rating_2,agent_rating_version) VALUES ('$user_id', '$agent_rating_main_upload_path', '$agent_rating_1_upload_path', '$agent_rating_2_upload_path', '$agent_rating_version')";
		          $results = $this->db_query($qry, array()); 
		          $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
			} 
		} 

		public function predective_dialer_upload($data){  
		  $user_id = $data['img_user_id'];
		  $pd_version = $data['pd_version'];
          // pd main document code starts here	  
		  if(isset($_FILES["pd_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $pd_main_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["pd_main"]["name"]);  
		      $pd_main_target_path = $destination_path."mrvoip/". basename( $_FILES["pd_main"]["name"]);
		      move_uploaded_file($_FILES['pd_main']['tmp_name'], $pd_main_target_path);        		      
		    }else{      
		      $pd_main_qry = "SELECT pd_main FROM pd_document WHERE id=1";
		      $pd_main_upload_path = $this->fetchmydata($pd_main_qry,array());
		    } 
		    // pd main document code ends here

		  // camp_1 document code starts here	  
		  if(isset($_FILES["camp_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_1"]["name"]);  
		      $camp_1_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_1"]["name"]);
		      move_uploaded_file($_FILES['camp_1']['tmp_name'], $camp_1_target_path);        		      
		    }else{      
		      $camp_1_qry = "SELECT camp_1 FROM pd_document WHERE id=1";
		      $camp_1_upload_path = $this->fetchmydata($camp_1_qry,array());
		    } 
		    // camp_1 document code ends here

           // camp_2 document code starts here	  
		   if(isset($_FILES["camp_2"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_2_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_2"]["name"]);  
		      $camp_2_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_2"]["name"]);
		      move_uploaded_file($_FILES['camp_2']['tmp_name'], $camp_2_target_path);        		      
		    }else{      
		      $camp_2_qry = "SELECT camp_2 FROM pd_document WHERE id=1";
		      $camp_2_upload_path = $this->fetchmydata($camp_2_qry,array());
		    } 
		    // camp_2 document code ends here

		    // camp_3 document code starts here	  
		   if(isset($_FILES["camp_3"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_3_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_3"]["name"]);  
		      $camp_3_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_3"]["name"]);
		      move_uploaded_file($_FILES['camp_3']['tmp_name'], $camp_3_target_path);        		      
		    }else{      
		      $camp_3_qry = "SELECT camp_3 FROM pd_document WHERE id=1";
		      $camp_3_upload_path = $this->fetchmydata($camp_3_qry,array());
		    } 
		    // camp_3 document code ends here

		    // camp_4 document code starts here	  
		   if(isset($_FILES["camp_4"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_4_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_4"]["name"]);  
		      $camp_4_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_4"]["name"]);
		      move_uploaded_file($_FILES['camp_4']['tmp_name'], $camp_4_target_path);        		      
		    }else{      
		      $camp_4_qry = "SELECT camp_4 FROM pd_document WHERE id=1";
		      $camp_4_upload_path = $this->fetchmydata($camp_4_qry,array());
		    } 
		    // camp_4 document code ends here
		    $getqry = "select * from pd_document"; 
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE pd_document SET pd_main='$pd_main_upload_path',camp_1='$camp_1_upload_path',camp_2='$camp_2_upload_path',camp_3='$camp_3_upload_path',camp_4='$camp_4_upload_path',pd_version='$pd_version' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }
            else{
                  $qry = "INSERT INTO pd_document(admin_id,pd_main,camp_1,camp_2,camp_3,camp_4,pd_version) VALUES ('$user_id', '$pd_main_upload_path', '$camp_1_upload_path', '$camp_2_upload_path', '$camp_3_upload_path', '$camp_4_upload_path', '$pd_version')";
		          $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }              
		}
		
		public function upload_list(){
		    //extract($data); 
		    $get_mrvoip_qry = "SELECT * FROM mrvoip_document";
		    $get_agentrating_qry = "SELECT * FROM agent_rating_document";
		    $get_pd_qry = "SELECT * FROM pd_document";
			$get_pro_qry = "SELECT * FROM pro_document";
			$get_bd_qry = "SELECT * FROM bd_document";
			$get_bs_qry = "SELECT * FROM bs_document";
		    $result["mrvoip_data"] = $this->dataFetchAll($get_mrvoip_qry,array());
		    $result["agentrating_data"] = $this->dataFetchAll($get_agentrating_qry,array());
		    $result["pd_data"] = $this->dataFetchAll($get_pd_qry,array());
		    $result["pro_data"] = $this->dataFetchAll($get_pro_qry,array());
		    $result["bd_data"] = $this->dataFetchAll($get_bd_qry,array());
		    $result["broadcast_survey_dialler"] = $this->dataFetchAll($get_bs_qry,array());
		    return $result;
		}
		
		public function delete_mrvoip_upload($data){
	      extract($data);
	      if($column_name=='pd_main' || $column_name=='camp_1'|| $column_name=='camp_2'|| $column_name=='camp_3' || $column_name=='camp_4')
	          $qry = "Update pd_document SET $column_name='' where id=1";
	      if($column_name=='agent_rating_main' || $column_name=='agent_rating_1'|| $column_name=='agent_rating_2'){
              $qry = "Update agent_rating_document SET $column_name='' where id=1";
	      }
	      if($column_name=='main_document' || $column_name=='linux_document'|| $column_name=='windows_document'){
              $qry = "Update mrvoip_document SET $column_name='' where id=1";
	      }
	      $parms = array();
	      $results = $this->db_query($qry,$parms);      
	      $output = $results == 1 ? 1 : 0;    
	      return  $output;
        }
		/*public function	zipfile_update($data){
          extract($data);
          $get_qry = "SELECT $column_name FROM $table_name WHERE admin_id='$user_id'";
          $zipfile_path = $this->fetchOne($get_qry,array());
		  //echo $zipfile_path;exit;
          $curl = $this->zipfile_archive($zipfile_path,$user_id);
          $result = array("status" => "true", "link" => $curl);          
          $tarray = json_encode($result);  
          print_r($tarray);exit;
        }*/
		public function	zipfile_update($data){
          extract($data);//print_r($data);exit;
          //$get_qry = "SELECT $column_name FROM $table_name WHERE admin_id='$user_id'";
		  $get_qry = "SELECT $column_name FROM $table_name";	
          $zipfile_path = $this->fetchOne($get_qry,array());
			
			 $get_vid = "SELECT survey_vid FROM `admin_details` where admin_id='$user_id'";	
          $survey_vid = $this->fetchOne($get_vid,array());
          $curl = $this->zipfile_archive_update1($zipfile_path,$user_id,$survey_vid);
			//print_r($curl); exit;
          if($curl != 'failed'){
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);
             $curl = $this->zipfile_archive_update2($zipfile_path,$user_id,$survey_vid);
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }          
        }
		
		public function	pd_zipfile_update($data){
          extract($data);
			$folder = 'updated';
				$files = glob($folder . '/*');
					foreach($files as $file){
    					if(is_file($file)){
        					unlink($file);
    					}
					}
          $get_qry = "SELECT $column_name FROM $table_name";
          $pd_zipfile_path = $this->fetchOne($get_qry,array());
		  //echo $pd_zipfile_path;exit;
			
			$get_vid="SELECT camp_vid FROM `campaign` where camp_id='$cmp_id' and admin_id='$user_id'";
          $camp_vid = $this->fetchOne($get_vid,array());
			
          $curl = $this->pd_zipfile_archive_update1($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid);
          if($curl != 'failed'){
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);
             $curl = $this->pd_zipfile_archive_update2($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid);
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }          
        }

public function proactive_dialer_upload($data){  
		  $user_id = $data['img_user_id'];
		  $pro_version = $data['pro_version'];
          // pro main document code starts here	  
		  if(isset($_FILES["pro_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $pro_main_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["pro_main"]["name"]);  
		      $pro_main_target_path = $destination_path."mrvoip/". basename( $_FILES["pro_main"]["name"]);
		      move_uploaded_file($_FILES['pro_main']['tmp_name'], $pro_main_target_path);        		      
		    }else{      
		      $pro_main_qry = "SELECT pro_main FROM pro_document WHERE id=1";
		      $pro_main_upload_path = $this->fetchmydata($pro_main_qry,array());
		    } 
		    // pro main document code ends here

		  // camp_1 document code starts here	  
		  if(isset($_FILES["camp_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_1"]["name"]);  
		      $camp_1_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_1"]["name"]);
		      move_uploaded_file($_FILES['camp_1']['tmp_name'], $camp_1_target_path);        		      
		    }else{      
		      $camp_1_qry = "SELECT camp_1 FROM pro_document WHERE id=1";
		      $camp_1_upload_path = $this->fetchmydata($camp_1_qry,array());
		    } 
		    // camp_1 document code ends here

          
		    $getqry = "select * from pro_document"; 
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE pro_document SET pro_main='$pro_main_upload_path',camp_1='$camp_1_upload_path',pro_version='$pro_version' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }
            else{
                  $qry = "INSERT INTO pro_document(admin_id,pro_main,camp_1,pro_version) VALUES ('$user_id', '$pro_main_upload_path', '$camp_1_upload_path', '$pro_version')";
		          $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }              
		}	
public function broadcast_dialler_upload($data){  
	//print_r($data);exit;
		  $user_id = $data['img_user_id'];
		  $bd_version = $data['bd_version'];
          // pro main document code starts here	  
		  if(isset($_FILES["bd_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $bd_main_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["bd_main"]["name"]);  
		      $bdmain_target_path = $destination_path."mrvoip/". basename( $_FILES["bd_main"]["name"]);
		      move_uploaded_file($_FILES['bd_main']['tmp_name'], $bdmain_target_path);        		      
		    }else{      
		      $bd_main_qry = "SELECT bd_main FROM bd_document WHERE id=1";
		      $bd_main_upload_path = $this->fetchmydata($bd_main_qry,array());
		    } 
		    // pro main document code ends here

		  // camp_1 document code starts here	  
		  if(isset($_FILES["camp_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_1"]["name"]);  
		      $camp_1_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_1"]["name"]);
		      move_uploaded_file($_FILES['camp_1']['tmp_name'], $camp_1_target_path);        		      
		    }else{      
		      $camp_1_qry = "SELECT camp_1 FROM bd_document WHERE id=1";
		      $camp_1_upload_path = $this->fetchmydata($camp_1_qry,array());
		    } 
		    // camp_1 document code ends here
	
	// camp_2 document code starts here	  
		  if(isset($_FILES["camp_2"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_2_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_2"]["name"]);  
		      $camp_2_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_2"]["name"]);
		      move_uploaded_file($_FILES['camp_2']['tmp_name'], $camp_2_target_path);        		      
		    }else{      
		      $camp_2_qry = "SELECT camp_2 FROM bd_document WHERE id=1";
		      $camp_2_upload_path = $this->fetchmydata($camp_2_qry,array());
		    } 
		    // camp_2 document code ends here

          
		    $getqry = "select * from bd_document"; 
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE bd_document SET bd_main='$bd_main_upload_path',camp_1='$camp_1_upload_path',camp_2='$camp_2_upload_path',bd_version='$bd_version' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }
            else{
                  $qry = "INSERT INTO bd_document(admin_id,bd_main,camp_1,camp_2,bd_version) VALUES ('$user_id', '$bd_main_upload_path', '$camp_1_upload_path', '$camp_2_upload_path', '$bd_version')";
		          $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }              
		}

public function broadcast_survey_dialler_upload($data){  
	//print_r($data);exit;
		  $user_id = $data['img_user_id'];
		  $bd_version = $data['bs_version'];
          // pro main document code starts here	  
		  if(isset($_FILES["bs_main"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $bs_main_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["bs_main"]["name"]);  
		      $bs_main_target_path = $destination_path."mrvoip/". basename( $_FILES["bs_main"]["name"]);
		      move_uploaded_file($_FILES['bd_main']['tmp_name'], $bs_main_target_path);        		      
		    }else{      
		      $bs_main_qry = "SELECT bs_main FROM bd_document WHERE id=1";
		      $bs_main_upload_path = $this->fetchmydata($bs_main_qry,array());
		    } 
		    // pro main document code ends here

		  // camp_1 document code starts here	  
		  if(isset($_FILES["camp_1"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_1"]["name"]);  
		      $camp_1_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_1"]["name"]);
		      move_uploaded_file($_FILES['camp_1']['tmp_name'], $camp_1_target_path);        		      
		    }else{      
		      $camp_1_qry = "SELECT camp_1 FROM bs_document WHERE id=1";
		      $camp_1_upload_path = $this->fetchmydata($camp_1_qry,array());
		    } 
		    // camp_1 document code ends here
	
	// camp_2 document code starts here	  
		  if(isset($_FILES["camp_2"]))
		    {	      		     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_2_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/". basename( $_FILES["camp_2"]["name"]);  
		      $camp_2_target_path = $destination_path."mrvoip/". basename( $_FILES["camp_2"]["name"]);
		      move_uploaded_file($_FILES['camp_2']['tmp_name'], $camp_2_target_path);        		      
		    }else{      
		      $camp_2_qry = "SELECT camp_2 FROM bs_document WHERE id=1";
		      $camp_2_upload_path = $this->fetchmydata($camp_2_qry,array());
		    } 
		    // camp_2 document code ends here

          
		    $getqry = "select * from bs_document"; 
            $getqry_value = $this->dataFetchAll($getqry, array());
	        $getqry_count = $this->dataRowCount($getqry, array());
            if($getqry_count > 0){
                  $qry = "UPDATE bs_document SET bs_main='$bs_main_upload_path',camp_1='$camp_1_upload_path',camp_2='$camp_2_upload_path',bs_version='$bs_version' WHERE id=1";
			      $parms = array();
			      $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }
            else{
                  $qry = "INSERT INTO bs_document(admin_id,bs_main,camp_1,camp_2,bs_version) VALUES ('$user_id', '$bs_main_upload_path', '$camp_1_upload_path', '$camp_2_upload_path', '$bs_version')";
		          $results = $this->db_query($qry,$parms);      
			      $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
            }              
		}

 public function listinvalid($data){
	// print_r($data);exit;
        extract($data);					
        //$qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
        //extract($qry_limit_data);
        $search_qry = "";
        if($search_text != ""){
                $search_qry = " and (campaign.camp_name like '%".$search_text."%' or pdc.customer_name like '%".$search_text."%' or pdc.address like '%".$search_text."%' or pdc.source_data like '%".$search_text."%' or pdc.phone_number like '%".$search_text."%')";
        }
	    
	    $qry = "SELECT pdc.id,pdc.customer_name,pdc.phone_number,campaign.camp_name,pdc.source_data,pdc.address FROM predective_dialer_contact pdc INNER JOIN campaign on campaign.camp_id=pdc.campaign_id and campaign.admin_id=pdc.admin_id and campaign.call_repeat<pdc.queue_status WHERE campaign.admin_id='$admin_id' and delete_status='0'".$search_qry;
	 
        $detail_qry = $qry." order by campaign.camp_id desc limit ".$limit." Offset ".$offset;		
	    // echo $detail_qry;exit;
		$parms = array();
        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;
        return $result;
   }
		public function invalid_update($data){
	      extract($data);
			 $id="'" . str_replace(",", "','", $id) . "'";
	      $qry = "update predective_dialer_contact set queue_status='0' where id IN($id)";
	      $parms = array();
	      $results = $this->db_query($qry,$parms);      
	      $output = $results == 1 ? 1 : 0;    
	      return  $output;
  }	
			 public function listdnd($data){
	// print_r($data);exit;
        extract($data);					
        //$qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
        //extract($qry_limit_data);
        $search_qry = "";
        if($search_text != ""){
                $search_qry = " and (campaign.camp_name like '%".$search_text."%' or pdc.customer_name like '%".$search_text."%' or pdc.address like '%".$search_text."%' or pdc.source_data like '%".$search_text."%' or pdc.phone_number like '%".$search_text."%')";
        }
	    
	    $qry = "SELECT pdc.id,pdc.customer_name,pdc.phone_number,campaign.camp_name,pdc.source_data,pdc.address FROM predective_dialer_contact pdc INNER JOIN campaign on campaign.camp_id=pdc.campaign_id and pdc.admin_id=campaign.admin_id and campaign.admin_id='$admin_id' and pdc.admin_id='$admin_id' and pdc.delete_status!='1' and pdc.dnd='$stat'".$search_qry;
	 
        $detail_qry = $qry." order by campaign.camp_id desc limit ".$limit." Offset ".$offset;		
	    // echo $detail_qry;exit;
		$parms = array();
        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;
        return $result;
   }
		public function dnd_update($data){
	      extract($data);
			 $id="'" . str_replace(",", "','", $id) . "'";
	      $qry = "update predective_dialer_contact set dnd='0',queue_status='0' where id IN($id)";
	      $parms = array();
	      $results = $this->db_query($qry,$parms);      
	      $output = $results == 1 ? 1 : 0;    
	      return  $output;
  }	
		
function camp_call($data){
		extract($data);
        $qry = "select *  from predective_dialer_contact where phone_number ='$phone_no' and admin_id='$admin_id'";
        $parms = array();
        $result = $this->fetchData($qry,$parms);
	//print_r($result);exit;
        return $result;

    }	
				
function update_camp_call($data){
		extract($data);
        $qry = "UPDATE predective_dialer_contact SET dnd='$stat' where phone_number ='$phone_no' and campaign_id ='$camp_id' and admin_id='$admin_id'";
        $parms = array();
        $result = $this->db_query($qry,$parms);
	//print_r($result);exit;
        return $result;

    }
			
public function	pro_zipfile_update($data){
          extract($data);
		$folder = 'dialer/pro/';
				$files = glob($folder . '/*');
					foreach($files as $file){
    					if(is_file($file)){
        					unlink($file);
    					}
					}
          $get_qry = "SELECT $column_name FROM $table_name";
          $pd_zipfile_path = $this->fetchOne($get_qry,array());
			$get_vid="SELECT camp_vid FROM `campaign` where camp_id='$cmp_id' and admin_id='$user_id'";
          $camp_vid = $this->fetchOne($get_vid,array());
          $curl = $this->pro_zipfile_archive_update1($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid);
		  $filename = basename($curl);
          if($curl != 'failed'){			
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);		
             $curl = $this->pro_zipfile_archive_update2($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid);
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }          
        }
public function	bd_zipfile_update($data){
          extract($data);
	$folder = 'dialer/bs/';
				$files = glob($folder . '/*');
					foreach($files as $file){
    					if(is_file($file)){
        					unlink($file);
    					}
					}
           $get_qry = "SELECT * FROM bd_document where id='1'";
          $bd_zipfile_path = $this->fetchData($get_qry,array());
	
			$get_vid="SELECT camp_vid FROM `campaign` where camp_id='$cmp_id' and admin_id='$user_id'";
          $camp_vid = $this->fetchOne($get_vid,array());

	$cmp_1=$bd_zipfile_path['camp_1'];
	$cmp_2=$bd_zipfile_path['camp_2'];	
          $curl = $this->bd_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid);
		  $filename = basename($curl);
          if($curl != 'failed'){			
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);		
             $curl = $this->bd_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid);
			  //echo $curl;
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }          
        }
public function	bs_zipfile_update($data){
          extract($data);
	$folder = 'dialer/bs/';
				$files = glob($folder . '/*');
					foreach($files as $file){
    					if(is_file($file)){
        					unlink($file);
    					}
					}
           $get_qry = "SELECT * FROM bs_document where id='1'";
          $bs_zipfile_path = $this->fetchData($get_qry,array());
	
			$get_vid="SELECT camp_vid FROM `campaign` where camp_id='$cmp_id' and admin_id='$user_id'";
          $camp_vid = $this->fetchOne($get_vid,array());

	$cmp_1=$bs_zipfile_path['camp_1'];$cmp_2=$bs_zipfile_path['camp_2'];	
          $curl = $this->bs_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid);
		  $filename = basename($curl);
          if($curl != 'failed'){			
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);		
             $curl = $this->bs_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid);
			  //echo $curl;
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }        	
}
		  

	
public function	rak_zipfile_update($data){
	//print_r($data);exit;
          extract($data);
	$folder = 'dialer/rak/';
				$files = glob($folder . '/*');
					foreach($files as $file){
    					if(is_file($file)){
        					unlink($file);
    					}
					}
           $get_qry = "SELECT * FROM rak_document where id='1'";
          $bd_zipfile_path = $this->fetchData($get_qry,array());
	
			 $get_vid="SELECT camp_vid,camp_id as receive_number FROM `campaign` where id='$cmp_id' and admin_id='$user_id'";
          $res_vid = $this->fetchData($get_vid,array());
	//print_r($res_vid);exit;
	 $camp_vid=$res_vid['camp_vid'];
	 $receive_number=$res_vid['receive_number'];
	
	$cmp_1=$bd_zipfile_path['camp_1'];
	$cmp_2=$bd_zipfile_path['camp_2'];	
          $curl = $this->rak_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid,$parallel,$frequency,$receive_number);
	
		  $filename = basename($curl);
          if($curl != 'failed'){			
             $result = array("status" => "true", "link" => $curl);          
             $tarray = json_encode($result);  
             print_r($tarray);		
             $curl = $this->rak_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid,$parallel,$frequency,$receive_number);
			  //echo $curl;
             exit;
          }else{          	
          	$result = array("status" => "false", "link" => '');          
            $tarray = json_encode($result);  
            print_r($tarray);exit;
          }          
        }

	public function edit_popup_contact($data){
      extract($data); 
	  $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
      $user_qry_value = $this->fetchmydata($user_qry,array());
	  $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
      $user_timezone = $this->fetchmydata($user_timezone_qry,array());
      date_default_timezone_set($user_timezone);  
      $created_at = date("Y-m-d H:i:s");
      $updated_at = date("Y-m-d H:i:s");	
		
		  $qry="SELECT predective_dialer_contact.id,predective_dialer_contact.user_id,predective_dialer_contact.admin_id,predective_dialer_contact.campaign_id,predective_dialer_contact.customer_name,predective_dialer_contact.address,predective_dialer_contact.city,predective_dialer_contact.state,predective_dialer_contact.zipcode,predective_dialer_contact.country,predective_dialer_contact.phone_number,predective_dialer_contact.source_data,predective_dialer_contact.notes,predective_dialer_contact.created_at,predective_dialer_contact.updated_at,campaign.camp_name from campaign INNER join predective_dialer_contact on predective_dialer_contact.campaign_id=campaign.camp_id and predective_dialer_contact.admin_id=campaign.admin_id where predective_dialer_contact.phone_number='$phone_no' and campaign.agent_id='$user_id'";
		 $result = $this->dataFetchAll($qry, array());			
		      
    return $result;
  }		
	}