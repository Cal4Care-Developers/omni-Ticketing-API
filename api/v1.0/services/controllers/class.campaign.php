<?php

	class campaign extends restApi{



		   	function insert_camp($data){
//print_r($data);exit;
                extract($data);
				
				  $qry_admin = "SELECT IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$agent_id'";
            $parms = array();
            $admin_id = $this->fetchmydata($qry_admin,$parms);
				
				 $already_select="SELECT id FROM campaign where camp_id='$camp_id' and camp_name='$camp_name' and admin_id='$admin_id'";
				 $parms = array();
            	$res_exists= $this->fetchmydata($already_select,$parms);
				//print_r($res_exists);
				if($res_exists==''){
					 $dt = date('Y-m-d H:i:s');
                if($camp_status=='0'){
                    $status_name='OFF';
                }else{
                    $status_name='ON';
                }
if(isset($_FILES["audio_file"]))
		    {	 
	 $type = $_FILES["audio_file"]["type"];
	if($type!=='audio/wav'){
		$result1 = array("status" => "false");  
		$result2 = array("data"=>"Please Upload Audio File in the format .wav");   
		$result=array_merge($result1,$result2);
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
	}
	$newfilename= $admin_id.$camp_id.str_replace(" ", "", basename($_FILES["audio_file"]["name"]));	     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;      
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/broadcast/". $newfilename;  
		      $camp_1_target_path = $destination_path."mrvoip/broadcast/". $newfilename;
		      move_uploaded_file($_FILES['audio_file']['tmp_name'], $camp_1_target_path);        		      
		    }
				 $qry = "INSERT INTO campaign(camp_id, camp_name, camp_status, status_name,date_created,admin_id,agent_id,call_repeat,camp_pre,camp_type,broadcast_audio,camp_vid,parallel,frequency,redial) VALUES ('$camp_id', '$camp_name', '$camp_status', '$status_name','$dt','$admin_id','$agent_id','$call_repeat','$camp_pre','$camp_type','$camp_1_upload_path', '$camp_vid','$parallel','$frequency','$redial')";
//echo $qry;exit;
 				//$qry_result= $this->db_query($qry, array());					
					//$output= $qry_result == 1 ? 1 : 0;
					//echo $result;exit;
               // return $output;
					 $results = $this->db_query($qry,array());			      
				  $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
}
				else{
					  $result = array("status" => "false","data"=>"2");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
				}
				exit;
               
            }
		function edit_camp($data){
		extract($data);
        $qry = "select camp_id,camp_name,camp_status,call_repeat,camp_pre,camp_type,broadcast_audio,camp_vid,parallel,frequency,redial  from campaign where id ='$id'";    
			$parms = array();
        $result = $this->fetchData($qry,$parms);
	//print_r($result);exit;
        return $result;

    }function camp_list($data){
			//print_r($data);exit;
		extract($data);
        $qry_admin = "SELECT IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$user_id'";
        $parms = array();
        $admin_id = $this->fetchmydata($qry_admin,$parms);

        $qry = "select * from campaign where admin_id ='".$admin_id."'";
        $parms = array();
        $result = $this->dataFetchAll($qry,$parms);
//print_r($result);exit;
        return $result;

    }function update_camp($data){
		//	print_r($data);exit;
		extract($data);
			
if(isset($_FILES["audio_file"]))
		    {	      
	 $type = $_FILES["audio_file"]["type"];
	if($type!=='audio/wav'){
		$result1 = array("status" => "false");  
		$result2 = array("data"=>"Please Upload Audio File in the format .wav");   
		$result=array_merge($result1,$result2);
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
	}
	$newfilename= $admin_id.$camp_id.str_replace(" ", "", basename($_FILES["audio_file"]["name"]));	   
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/broadcast/". $newfilename;  
		      $camp_1_target_path = $destination_path."mrvoip/broadcast/". $newfilename;
		      move_uploaded_file($_FILES['audio_file']['tmp_name'], $camp_1_target_path);        		      
		    }
       $qry = "UPDATE campaign set camp_id='$camp_id',camp_name='$camp_name',call_repeat='$call_repeat',camp_pre='$camp_pre',camp_type='$camp_type',broadcast_audio='$camp_1_upload_path',camp_vid='$camp_vid',parallel='$parallel',frequency='$frequency',redial='$redial' where id='$id' "; 
      	 $results = $this->db_query($qry,array());			      
				  $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;

    }function delete_camp($data){
		extract($data);
        $qry = "DELETE from campaign where id IN ($id)";
        $parms = array();
        $result = $this->db_query($qry,$parms);
	//print_r($result);exit;
        return $result;

		}function toggle_status($data){
		extract($data);
			 $qry_select="SELECT camp_status,status_name from campaign where id='$id'";
			$parms = array();
            $res= $this->fetchdata($qry_select,$parms);
			if($res['camp_status']=='0'){
				$camp_status='1';
			}else{
				$camp_status='0';
			}if($res['status_name']=='OFF'){
				$status_name='ON';
			}else{
				$status_name='OFF';
			}
			
         $qry = "UPDATE campaign set camp_status='$camp_status',status_name='$status_name' where id='$id' ";       
         $qry_result = $this->db_query($qry, array());
	
        return $qry_result;

    }
			function insert_rak_camp($data){
//print_r($data);exit;
                extract($data);
				
				  $qry_admin = "SELECT IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$agent_id'";
            $parms = array();
            $admin_id = $this->fetchmydata($qry_admin,$parms);
				
				 $already_select="SELECT id FROM campaign where camp_id='$camp_id' and camp_name='$camp_name' and admin_id='$admin_id'";
				 $parms = array();
            	$res_exists= $this->fetchmydata($already_select,$parms);
				//print_r($res_exists);
				if($res_exists==''){
					 $dt = date('Y-m-d H:i:s');
                if($camp_status=='0'){
                    $status_name='OFF';
                }else{
                    $status_name='ON';
                }
if(isset($_FILES["audio_file"]))
		    {	 
	 $type = $_FILES["audio_file"]["type"];
	if($type!=='audio/wav'){
		$result1 = array("status" => "false");  
		$result2 = array("data"=>"Please Upload Audio File in the format .wav");   
		$result=array_merge($result1,$result2);
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
	}
	$newfilename= $admin_id.$camp_id.str_replace(" ", "", basename($_FILES["audio_file"]["name"]));	     
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;      
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/broadcast/". $newfilename;  
		      $camp_1_target_path = $destination_path."mrvoip/broadcast/". $newfilename;
		      move_uploaded_file($_FILES['audio_file']['tmp_name'], $camp_1_target_path);        		      
		    }
				 $qry = "INSERT INTO campaign(camp_id, camp_name, camp_status, status_name,date_created,admin_id,agent_id,call_repeat,camp_pre,camp_type,broadcast_audio,camp_vid,parallel,frequency,redial) VALUES ('$camp_id', '$camp_name', '$camp_status', '$status_name','$dt','$admin_id','$agent_id','$call_repeat','$camp_pre','$camp_type','$camp_1_upload_path', '$camp_vid','$parallel', '$frequency','$redial')";
//echo $qry;exit;
 				//$qry_result= $this->db_query($qry, array());					
					//$output= $qry_result == 1 ? 1 : 0;
					//echo $result;exit;
               // return $output;
					 $results = $this->db_query($qry,array());			      
				  $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
}
				else{
					  $result = array("status" => "false","data"=>"2");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
				}
				exit;
               
            }
function update_rak_camp($data){
			
		extract($data);
			
if(isset($_FILES["audio_file"]))
		    {	      
	 $type = $_FILES["audio_file"]["type"];
	if($type!=='audio/wav'){
		$result1 = array("status" => "false");  
		$result2 = array("data"=>"Please Upload Audio File in the format .wav");   
		$result=array_merge($result1,$result2);
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;
	}
	$newfilename= $admin_id.$camp_id.str_replace(" ", "", basename($_FILES["audio_file"]["name"]));	   
		      $destination_path = getcwd().DIRECTORY_SEPARATOR;            
		      $camp_1_upload_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/mrvoip/broadcast/". $newfilename;  
		      $camp_1_target_path = $destination_path."mrvoip/broadcast/". $newfilename;
		      move_uploaded_file($_FILES['audio_file']['tmp_name'], $camp_1_target_path);        		      
		    }
         $qry = "UPDATE campaign set camp_id='$camp_id',camp_name='$camp_name',call_repeat='$call_repeat',camp_pre='$camp_pre',camp_type='$camp_type',broadcast_audio='$camp_1_upload_path',camp_vid='$camp_vid',parallel='$parallel',frequency='$frequency',redial='$redial' where id='$id' ";       
      	 $results = $this->db_query($qry,array());			      
				  $result = array("status" => "true");          
                  $tarray = json_encode($result);  
                  print_r($tarray);exit;

    }
	}