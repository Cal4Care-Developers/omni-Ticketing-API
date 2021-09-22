<?php
class questionaire extends restApi{    
  public function insertQuestion($data){
      extract($data);
      $get_question_qry = "SELECT * FROM questionaire WHERE  admin_id='$admin_id' AND department_id IN($department_id) AND question='$question'";	  
      $result = $this->fetchData($get_question_qry,array()); 	  
      if($result > 0){
        $result = array( 
                "status" => "false"                
            );
		$tarray = json_encode($result);           
        print_r($tarray);exit;
      }
      else{   
        $qry_result = $this->db_query("INSERT INTO questionaire(admin_id,department_id,question) VALUES ( '$admin_id','$department_id','$question')", array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
      }
    }

   public function getQueue($data){
	  extract($data); 
      $get_queue_qry = "SELECT * FROM queue WHERE admin_id='$admin_id'";	   
      $result = $this->dataFetchAll($get_queue_qry,array());
      return $result;
   } 
	
   public function editQuestion($data)
   {
      extract($data);
      $qry = "select * from questionaire where id='$question_id'"; 
      $result = $this->fetchData($qry, array());
	  $admin_id = $result['admin_id'];
	  $department_id = $result['department_id'];
	  $question = $result['question'];
	  $question_options = array("question_id"=>$question_id, "admin_id"=>$admin_id, "department_id"=>$department_id, "question"=>$question);
	  $queue_array_qry = "SELECT queue_id,queue_name FROM queue WHERE admin_id='$admin_id'";
	  $queue_array = $this->dataFetchAll($queue_array_qry, array());
	  for($i = 0; count($queue_array) > $i; $i++){
		  $queue_id = $queue_array[$i]['queue_id'];
          $queue_name = $queue_array[$i]['queue_name'];
		  $queue_options = array('queue_id' => $queue_id, 'queue_name' => $queue_name);
          $queue_options_array[] = $queue_options;
	  }
	  $queue_options_array = array('queue_options' => $queue_options_array);
	  $status = array('status' => 'true');
	  $merge_result = array_merge($status, $question_options, $queue_options_array);          
      $tarray = json_encode($merge_result);           
      print_r($tarray);exit;
    }
	public function questionList($data)
    {
      extract($data);
      $qry = "select * from questionaire where delete_status != 1 and admin_id='$admin_id'"; 
      $result = $this->dataFetchAll($qry, array()); 
	  return $result;
    }
	public function updateQuestion($data){
      extract($data);//print_r($data);exit;	  
      $qry = "UPDATE questionaire SET admin_id='$admin_id',department_id='$department_id',question='$question' WHERE id='$question_id'";
      $qry_result = $this->db_query($qry, array());
      $result = $qry_result == 1 ? 1 : 0;
      return $result;           
    }
	public function getuserQueue($data){
      extract($data); 
      $get_adminid_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'"; 
      $adminid = $this->fetchOne($get_adminid_qry,array());
	  if($adminid == 1)
	  {
		$aid=$user_id;
	  }else{
		$aid=$adminid;
	  }
      $qry = "SELECT * FROM `questionaire` WHERE admin_id='$user_id' AND delete_status=0 ORDER BY RAND() LIMIT 1";	
      $result = $this->dataFetchAll($qry,array());
      return $result;
    }
	public function delete_question($data){
      extract($data);
      $qry = "Update questionaire SET delete_status='1' where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
}
