<?php
class webinar_configuration extends restApi{    
  public function insertConfiguration($data){
      extract($data);
      $qry = "SELECT * FROM webinar_meeting_configuration WHERE admin_id='$admin_id'";	  
      $result = $this->fetchData($qry,array());
      $count = $this->dataRowCount($qry,array()); 	  
      if($count > 0){		  
        $qry_result = $this->db_query("UPDATE webinar_meeting_configuration SET fqdn='$fqdn',api_token='$api_token',extension_number='$extension_number',country='$country',subscribers_limit='$subscribers_limit' WHERE admin_id='$admin_id'", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
      else{  
        $qry_result = $this->db_query("INSERT INTO webinar_meeting_configuration(admin_id,fqdn,api_token,extension_number,country,subscribers_limit) VALUES ( '$admin_id','$fqdn','$api_token','$extension_number','$country','$subscribers_limit')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
    }

  public function getConfiguration($data){
      extract($data);
      $qry = "SELECT * FROM webinar_meeting_configuration WHERE admin_id='$admin_id'";	  
      $result = $this->fetchData($qry,array());
      return $result;
    }
	
	public function getCountries(){
		
		$get_default_value="SELECT id,name,iso FROM country";
		
		 $result= $this->dataFetchAll($get_default_value, array());
		return $result;
	}
   public function get_meeting_list($data){
	  extract($data); 
      $get_queue_qry = "SELECT * FROM webinar_meeting_new WHERE admin_id='$admin_id'";	   
      $result = $this->dataFetchAll($get_queue_qry,array());
	  for($i = 0; count($result) > $i; $i++){
		  $meetingid = $result[$i]['meetingid'];
		  $session = $result[$i]['session'];
		  $title = $result[$i]['title'];		  
		  $country = $result[$i]['country'];
		  $maxparticipants = $result[$i]['maxparticipants'];
		  $duration = $result[$i]['duration'];
		  $descr = $result[$i]['descr'];
		  $parts = $result[$i]['parts'];
		  $date1 = $result[$i]['meeting_date']; 
          $date2 = date('Y-m-d H:i:s');
		  $status = $result[$i]['status'];
		  if($date2 > $date1){
			  $meeting_status = 'completed';
		  }else{
		      $meeting_status = 'Active';
		  }
		  if($status=='1'){
			  $act_status = 'Active';
		  }else{
		      $act_status = 'Inactive';
		  }
		  $options = array('meetingid' => $meetingid, 'session' => $session, 'title' => $title, 'country' => $country, 'maxparticipants' => $maxparticipants, 'duration' => $duration, 'descr' => $descr, 'parts' => $parts, 'meeting_status' => $meeting_status, 'status' => $act_status, 'created_at' => $date1);                                   
          $list_options_array[] = $options;
		} 
      $status = array('status' => 'true');            
      $list_options_array = array('List_options' => $list_options_array);
	  $merge_result = array_merge($status, $list_options_array);            
      $tarray = json_encode($merge_result);           
      print_r($tarray);exit; 
   } 
	public function list_meeting_participants($data){
	  extract($data); 
      $get_queue_qry = "SELECT *,(select title FROM webinar_meeting_new where meetingid='$meetingid' ) as title FROM webinar_meeting_participants WHERE meetingid='$meetingid'";	   
      $result = $this->dataFetchAll($get_queue_qry,array());
      return $result;
   }
	public function delete_meeting($data){
      extract($data);//print_r($data);
		//echo "DELETE FROM testing where meetingid='$meetingid'";
		//exit;
	  $qry = "DELETE FROM webinar_meeting_new where meetingid='$meetingid'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function list_meeting_participants_report($data){
	  extract($data); 
      $get_queue_qry = "SELECT first_name,last_name,email,organization,meetingid,created_dt,(select title FROM webinar_meeting_new where meetingid='$meetingid' ) as title FROM webinar_meeting_participants WHERE meetingid='$meetingid'";
      $result = $this->dataFetchAll($get_queue_qry,array());
      return $result;
   }
		
}
