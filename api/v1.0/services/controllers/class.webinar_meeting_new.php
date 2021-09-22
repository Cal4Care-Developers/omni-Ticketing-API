<?php
class webinar_meeting_new extends restApi{

  public function add_webinar_meeting($data){ 
	extract($data);
	$count = count($data['meeting_details']);	  
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);   
    $decryption_iv = '1234567891011121'; 
    $decryption_key = "GeeksforGeeks";
	$options = 0;   
    $decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
    $decryption =  $array = json_decode($decryption, true);
	extract($decryption);
	$hash_val = md5($password);  
	$get_agent_qry = "select user_id from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
	$user_id = $this->fetchOne($get_agent_qry,array());
	$admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
    $admin_data = $this->fetchData($admin_id_qry,array());
	$admin_id = $admin_data['admin_id'];  
	if($admin_id==1){
		$aid = $user_id;
	}else{
		$aid = $admin_id;
	}
	for($i=0;$i<$count;$i++){
	  $meetingid = $meeting_details[$i]['meetingid'];
	  $title = $meeting_details[$i]['title'];
	  $m_date = $meeting_details[$i]['date'];
	  $country = $meeting_details[$i]['country'];
	  $maxparticipants = $meeting_details[$i]['maxparticipants'];
	  $duration = $meeting_details[$i]['duration'];
	  $descr = $meeting_details[$i]['descr'];
	  $parts = $meeting_details[$i]['parts'];		
	  $select_qry = "select meetingid from webinar_meeting_new where meetingid='$meetingid'";
	  $mid = $this->fetchOne($select_qry,array());
	  if($mid == $meetingid){
	   //echo "UPDATE webinar_meeting_new SET admin_id='$aid',meetingid='$meetingid',session='$session',title='$title',meeting_date='$m_date',country='$country',maxparticipants='$maxparticipants',duration='$duration',descr='$descr',parts='$parts' WHERE meetingid='$meetingid'";	  
	   $qry_result = $this->db_query("UPDATE webinar_meeting_new SET admin_id='$aid',meetingid='$meetingid',session='$session',title='$title',meeting_date='$m_date',country='$country',maxparticipants='$maxparticipants',duration='$duration',descr='$descr',parts='$parts',status='1' WHERE meetingid='$meetingid'", array());
        $result = $qry_result == 1 ? 1 : 0;
	  }
	  else{	
        $qry_result = $this->db_query("INSERT INTO webinar_meeting_new(admin_id,meetingid,session,title,meeting_date,country,maxparticipants,duration,descr,parts,status) VALUES ('$aid', '$meetingid','$session','$title','$m_date','$country','$maxparticipants','$duration','$descr','$parts','1')", array());
        $result = $qry_result == 1 ? 1 : 0;	
	  }
	} 
	$i++; 
    if($result==1){
	 $status = array("status" => "true");
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}else{
		$status = array("status" => "false");
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}
  }
  public function getAll($data){
    extract($data);
	$qry = "SELECT m.*,c.fqdn FROM `webinar_meeting_new` m LEFT JOIN webinar_meeting_configuration c ON m.admin_id=c.admin_id WHERE m.meetingid='$meetingid'"; 
	$count = $this->dataRowCount($qry,array());
	if($count > 0){  
     $result = $this->dataFetchAll($qry,array());
	 $status = array("status" => "true", "data" => $result);
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}else{
	 $status = array("status" => "false");
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}
  }
  public function get_webinar_configuration($data){
    extract($data);
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);   
    $decryption_iv = '1234567891011121'; 
    $decryption_key = "GeeksforGeeks";
	$options = 0;   
    $decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
    $decryption =  $array = json_decode($decryption, true);
	extract($decryption);
	$hash_val = md5($password);  
	$get_agent_qry = "select user_id from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
	$user_id = $this->fetchOne($get_agent_qry,array());
	$admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
    $admin_data = $this->fetchData($admin_id_qry,array());
	$admin_id = $admin_data['admin_id'];  
	if($admin_id==1){
		$aid = $user_id;
	}else{
		$aid = $admin_id;
	}
    $qry = "select * from webinar_meeting_configuration where admin_id = $aid";
	$count = $this->dataRowCount($qry,array());
	if($count > 0){  
      $result = $this->dataFetchAll($qry,array());
	  $status = array("status" => "true", "data" => $result);
	  $tarray = json_encode($status);
	  print_r($tarray);exit;
	}else{
	  $status = array("status" => "false");
	  $tarray = json_encode($status);
	  print_r($tarray);exit;
	}
  }
  public function add_meeting_participants($data){ 
	  //print_r($data); exit; 
	extract($data); 
	 // echo "INSERT INTO webinar_meeting_participants(meetingid,first_name,last_name,email,country,organization) VALUES ( '$meetingid','$first_name','$last_name','$email','$country','$organization')";exit;
    $qry_result = $this->db_query("INSERT INTO webinar_meeting_participants(meetingid,first_name,last_name,email,country,organization) VALUES ( '$meetingid','$first_name','$last_name','$email','$country','$organization')", array());
    $result = $qry_result == 1 ? 1 : 0;
    if($result==1){
	 $status = array("status" => "true");
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}else{
	 $status = array("status" => "false");
	 $tarray = json_encode($status);
	 print_r($tarray);exit;
	}
  }
}
