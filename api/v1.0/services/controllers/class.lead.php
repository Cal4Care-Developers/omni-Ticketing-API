<?php
class lead extends restApi{

  public function addLead($data){ 
	  //print_r($data); exit; 
	extract($data);	  
    $user_leadtoken_qry = "SELECT * FROM user WHERE lead_token='$lead_token'";
    $user_leadtoken = $this->fetchData($user_leadtoken_qry,array());	  
    if($user_leadtoken > 0){
      $user_id = $user_leadtoken['user_id'];
      $qry_result = $this->db_query("INSERT INTO lead(user_id,name,email,phone,city,country,message) VALUES ( '$user_id','$name','$email','$phone','$city','$country','$message')", array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result; 
    }
    else{           
      $result = array("status" => "false", "message" => "Lead Token Miss Match");
      $result_array = json_encode($result);           
      print_r($result_array);exit;
    }
  }
  public function getAlllead($data){
    extract($data);
    $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
    extract($qry_limit_data);
    $search_qry = "";
    if($search_text != ""){
      $search_qry = " and (lead.name like '%".$search_text."%' or lead.email like '%".$search_text."%' or lead.phone like '%".$search_text."%')";
    }
    $qry = "select * from lead where status != 1 and user_id = $user_id".$search_qry; 
    $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;  
    $result["list_data"] = $this->dataFetchAll($detail_qry,array());
    $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
    $result["list_info"]["offset"] = $offset;
    return $result;
  }
  public function get_Singlelead($data){
    extract($data);
    $get_lead_qry = "SELECT * FROM lead WHERE id='$lead_id' AND user_id='$user_id'"; 
    $result = $this->fetchData($get_lead_qry,array());
    return $result;
  }
  public function updateLead($data){
    extract($data);  
    $qry = "UPDATE lead SET name='$name',email='$email',phone='$phone',city='$city',country='$country',message='$message' WHERE id='$lead_id' AND user_id='$user_id'";
    $qry_result = $this->db_query($qry, array());
    $result = $qry_result == 1 ? 1 : 0;
    return $result;           
  }
  public function deleteLead($data){
    extract($data);
    $qry = "UPDATE lead SET status=1 where id='$lead_id' and user_id='$user_id'";
      $qry_result = $this->db_query($qry, array());        
    $result = $qry_result == 1 ? 1 : 0;
    return $result;
  }
/*public function convert_contact($lead_id,$contact_option)
  {
    $select_qry = "SELECT * FROM lead WHERE id='$lead_id";
    $selectresult = $this->fetchData($select_qry,array());
    $name = $selectresult['name'];
    $email = $selectresult['email'];
    $phone = $selectresult['phone'];
    $city = $selectresult['city'];
    $country = $selectresult['country'];
    $message = $selectresult['message'];
    $contact_status = $selectresult['contact_status'];
    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
    $user_qry_value = $this->fetchmydata($user_qry,array());
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
    date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s"); 
    if($contact_option == 0)
    {
      $get_qry = "SELECT * FROM contacts WHERE phone='$phone' OR email='$email'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = array( 
                "status" => "false",
			    "message" => "Contact already exist"
            );
        $tarray = json_encode($result);           
        print_r($tarray);exit;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO contacts(contact_owner,first_name,account_name,lead_source,email,phone,mailing_city,mailing_country,created_by,admin_id,lead_id) VALUES ( '$name','$name','$name','website','$email','$phone','$city','$country','$user_id','$user_id','$lead_id')", array());
        $result = $qry_result == 1 ? 1 : 0;     
        if($result==1){             
          if($contact_status == 1){
            $updateqry1 = " Update lead SET `contact_status` = 2 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
          }else{
            $updateqry1 = "Update lead SET `contact_status` = 2 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
          }  
          $updateqry2 = " Update lead SET `contact_type` = 'contacts' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);   
          $lastid_qry = "SELECT contact_id FROM contacts WHERE created_by='$user_id' ORDER BY contact_id DESC LIMIT 1";   
          $lastid = $this->fetchOne($lastid_qry,array()); 
          $qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`, `created_at`, `updated_at`) VALUES ('$lastid', '$user_id', '$user_id', '$message', '$created_at', '$updated_at')";
          $res = $this->db_query($qry, array());
          return $result;          
        }
      }
    }
    else{
      $get_qry = "SELECT * FROM predective_dialer_contact WHERE phone_number='$phone'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = array( 
                "status" => "false",
			    "message" => "Contact already exist"
            );
        $tarray = json_encode($result);           
        print_r($tarray);exit;
      }
      else{
        $qry = $this->db_query("INSERT INTO predective_dialer_contact(`user_id`, `admin_id`, `campaign_id`, `customer_name`, `address`, `city`, `state`, `zipcode`, `country`, `phone_number`, `source_data`, `notes`, `created_at`, `updated_at`, `lead_id`) VALUES ('$user_id','$user_id','','$name','','$city','','','$country','$phone','website','$message','$created_at','$updated_at', '$lead_id')", array());      
        $result = $qry == 1 ? 1 : 0;            
            if($contact_status == 1){
              $updateqry = " Update lead SET `contact_status` = 2 WHERE id='$lead_id'";
              $parms = array();
              $results = $this->db_query($updateqry,$parms);
            }else{
              $updateqry = " Update lead SET `contact_status` = 1 WHERE id='$lead_id'";
              $parms = array();
              $results = $this->db_query($updateqry,$parms);
            }
            $updateqry2 = " Update lead SET `contact_type` = 'predective_dialer_contact' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
        return $result;
      }
    }
  }*/
public function convert_contact($lead_id,$contact_option)
  {
    $select_qry = "SELECT * FROM lead WHERE id='$lead_id'";
    $selectresult = $this->fetchData($select_qry,array());
    $name = $selectresult['name'];
    $email = $selectresult['email'];
    $phone = $selectresult['phone'];
    $city = $selectresult['city'];
    $country = $selectresult['country'];
    $message = $selectresult['message'];
    $contact_status = $selectresult['contact_status'];
    $contact_type = $selectresult['contact_type'];
	$user_id = $selectresult['user_id'];
	$get_adminid_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'"; 
    $adminid = $this->fetchOne($get_adminid_qry,array());
	if($adminid==1){
		$aid = $user_id;
	}else{
		$aid = $adminid;
	}
	//echo $aid;exit;
    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
    $user_qry_value = $this->fetchmydata($user_qry,array());
    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
    $user_timezone = $this->fetchmydata($user_timezone_qry,array());
    date_default_timezone_set($user_timezone);  
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s"); 
    if($contact_option == 0)
    {
      $get_qry = "SELECT * FROM contacts WHERE phone='$phone' OR email='$email'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = array( 
                "status" => "false"                
            );
        $tarray = json_encode($result);           
        print_r($tarray);exit;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO contacts(contact_owner,first_name,account_name,lead_source,email,phone,mailing_city,mailing_country,created_by,admin_id,lead_id) VALUES ( '$name','$name','$name','website','$email','$phone','$city','$country','$user_id','$aid','$lead_id')", array());
        $result = $qry_result == 1 ? 1 : 0;     
        if($result==1){             
          if($contact_status == 0){
            $updateqry1 = " UPDATE lead SET `contact_status` = 1 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
          }elseif($contact_status == 1){
            $updateqry1 = "UPDATE lead SET `contact_status` = 2 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
          }else{}
          if($contact_type == ''){  
            $updateqry2 = " UPDATE lead SET `contact_type` = 'contacts' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);  
          }elseif($contact_type == 'predective_dialer_contact'){
            $updateqry2 = " UPDATE lead SET `contact_type` = 'both' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
          } 
          $lastid_qry = "SELECT contact_id FROM contacts WHERE created_by='$user_id' ORDER BY contact_id DESC LIMIT 1";   
          $lastid = $this->fetchOne($lastid_qry,array()); 
          $qry ="INSERT INTO `contact_notes` ( `contact_id`, `agent_id`, `admin_id`, `notes`, `created_at`, `updated_at`) VALUES ('$lastid', '$user_id', '$aid', '$message', '$created_at', '$updated_at')";
          $lastid_result = $this->db_query($qry, array());
          return $result;          
        }
      }
    }
    else{
      $get_qry = "SELECT * FROM predective_dialer_contact WHERE phone_number='$phone'";
      $result = $this->fetchData($get_qry,array());    
      if($result > 0){
        $result = array( 
                "status" => "false"                
            );
        $tarray = json_encode($result);           
        print_r($tarray);exit;
      }
      else{
        $qry = $this->db_query("INSERT INTO predective_dialer_contact(`user_id`, `admin_id`, `campaign_id`, `customer_name`, `address`, `city`, `state`, `zipcode`, `country`, `phone_number`, `source_data`, `notes`, `created_at`, `updated_at`, `lead_id`) VALUES ('$user_id','$aid','','$name','','$city','','','$country','$phone','website','$message','$created_at','$updated_at', '$lead_id')", array());      
        $result = $qry == 1 ? 1 : 0;            
        if($contact_status == 0){
            $updateqry1 = " UPDATE lead SET `contact_status` = 1 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
        }elseif($contact_status == 1){
            $updateqry1 = "UPDATE lead SET `contact_status` = 2 WHERE id='$lead_id'";
            $parms = array();
            $results1 = $this->db_query($updateqry1,$parms);            
        }else{}
        if($contact_type == ''){  
            $updateqry2 = " Update lead SET `contact_type` = 'predective_dialer_contact' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);  
        }elseif($contact_type == 'contacts'){
            $updateqry2 = " Update lead SET `contact_type` = 'both' WHERE id='$lead_id'";
            $parms = array();
            $results2 = $this->db_query($updateqry2,$parms);
        }
        return $result;
      }
    }
  }
}
