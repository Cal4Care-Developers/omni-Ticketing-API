<?php
//echo "test";exit;
	class auxcode extends restApi{

		
public function addAuxcode($data){
		extract($data);	
		$qry = "select * from auxcode where auxcode_name LIKE '%$auxcode_name%' and admin_id = '$admin_id'";
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
     	   $datas=array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id,"created_at"=>$created_at,"updated_at"=>$updated_at);
		   $qry = $this->generateCreateQry($datas, "auxcode");
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
		$qry = "UPDATE auxcode SET auxcode_name='$auxcode_name',admin_id='$admin_id',updated_at='$updated_at' where id='$auxcode_id'";
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
	    $qry = "select * from auxcode where delete_status != 1 and admin_id ='$id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$id));
	}
	public function getAuxcode_data($data){
		extract($data);
	    $qry = "select * from auxcode where id ='$auxcode_id' and admin_id='$admin_id'";
		//echo "select * from aux_code where id ='$auxcode_id' and admin_id='$admin_id'";exit;
		return $this->fetchData($qry, $params);
	}
	public function delete_aux($data){
      extract($data);
          $qry = "DELETE from auxcode WHERE id='$id' and admin_id='$admin_id'";
//  $qry = "UPDATE auxcode SET delete_status='1' WHERE id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
		
	}