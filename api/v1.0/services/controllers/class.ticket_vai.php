<?php
require_once('class.phpmailer.php');
require __DIR__ . '/../../eio/vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;
class ticket extends restApi{
    function getDeptList($data){
        extract($data);       
        $qry = "select * from user where user_id ='$user_id'";
        $result =  $this->fetchData($qry, array());
        extract($result);
        if($admin_id == '1'){ $admin_id = $user_id; } else { $admin_id = $admin_id ; }
		if($type != ''){
            $qry = "select * from departments where delete_status != 1 and admin_id='$admin_id' and $type=1";
		}else{
			$qry = "select * from departments where delete_status != 1 and admin_id='$admin_id'";
		}
		$result = $this->dataFetchAll($qry,array());
        return $result;
    }
	
		function getDept($id,$admin_id){
			
			$qry = "select * from departments where dept_id ='$id'";
			$res= $this->fetchData($qry, array("dept_id"=>$id));
			 $dep_user=$res['department_users'];
			$dep_user="'" . str_replace(",", "','", $dep_user) . "'";
			//echo "select user_id,user_name,agent_name,'$id' as dep_id from user where user_id IN ($dep_user)";exit;
			$active=$this->dataFetchAll("select user.user_id,user.user_name,user.agent_name,dep_drag.position from user LEFT join dep_drag on $id=dep_drag.dept_id and user.user_id=dep_drag.user_id where user.user_id IN ($dep_user) group by user.user_id order by dep_drag.position ASC",array());
			$inactive=$this->dataFetchAll("select user_id,user_name,agent_name from user where user_id NOT IN ($dep_user) and IF(admin_id = 1, user_id, admin_id)='$admin_id' ",array());
			
			$res["active"] = $active;
            $res["inactive"] = $inactive;
			return $res;
		}
	
	function addDepartment($data){
		extract($data);
		//print_r($data);exit;
		$qry = "select * from departments where department_name LIKE '%$department_name%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("dept_id"=>$id));
		
		 if($result > 0){
			 	$result = 2;
				return $result;
		 } else {
		
		   $qry = $this->generateCreateQry($data, "departments");
            $insert_data = $this->db_insert($qry, $data);
           
            if($insert_data != 0){
			//	echo $insert_data;exit;
				$ex=explode(',',$department_users);
				for($i=0; $i<count($ex); $i++){
					$us=$ex[$i];
					
					$ins=$this->db_query("Insert INTO dep_drag(dept_id,user_id,position) VALUES ( '$insert_data','$us','$i')", array());
				}
               $result = 1;              
            }
            else{
                
                $result = 0;
            }

            return $result;
		 }
	}
	function retriveDepartment($data){
		extract($data);
		$admin_id= $this->fetchOne("Select user_id from user where admin_id='1' and hardware_id='$hardware_id'", array());
		$department_users=$this->fetchOne("SELECT GROUP_CONCAT(user_id ORDER BY FIELD(sip_login, $department_users)) as users FROM `user` where sip_login IN($department_users)", array());
		$qry = "select dept_id from departments where department_name = '$department_name' and admin_id = '$admin_id'";
		$dept_id = $this->fetchOne($qry, array("dept_id"=>$id));
		
		 if($dept_id > 0){
			 	 $update_data = $this->db_query("UPDATE departments SET department_name='$department_name',department_users='$department_users',status='$status',admin_id='$admin_id' where dept_id='$dept_id'", array());
			
			   if($update_data != 0){
				$qry=$this->db_query("DELETE From dep_drag where dept_id='$dept_id'", array());
                $ex=explode(',',$department_users);
				  
				for($i=0; $i<count($ex); $i++){
					
					$us=$ex[$i];
					
					$ins=$this->db_query("Insert INTO dep_drag(dept_id,user_id,position) VALUES ( '$dept_id','$us','$i')", array());
				}
			   	
               return $result = 1;
				   }
			 
		 } else {		 
		   $qry = "INSERT INTO departments(department_name,department_users,admin_id,status) VALUES('$department_name','$department_users','$admin_id', '1')";
            $insert_data = $this->db_insert($qry, $data);
           
            if($insert_data != 0){
			//	echo $insert_data;exit;
				$ex=explode(',',$department_users);
				for($i=0; $i<count($ex); $i++){
					$us=$ex[$i];
					
					$ins=$this->db_query("Insert INTO dep_drag(dept_id,user_id,position) VALUES ( '$insert_data','$us','$i')", array());
				}
               $result = 1;              
            }
            else{
                
                $result = 0;
            }

            return $result;
		 }
	}
	
	function import_department($data){
		extract($data);
		$admin_id= $this->fetchOne("Select user_id from user where admin_id='1' and hardware_id='$hardware_id'", array());
		$ex=explode(',',$department_name);
		$count=count($ex);
		for($i=0; $i<$count; $i++){
			 $dep_name=$ex[$i];
			$qry = "select dept_id from departments where department_name = '$dep_name' and admin_id = '$admin_id'";
		    $dept_id = $this->fetchOne($qry, array("dept_id"=>$id));
			if($dept_id==''){
			$qry = "INSERT INTO departments(department_name,admin_id,status) VALUES('$dep_name','$admin_id', '1')";
            $insert_data = $this->db_insert($qry, $data);
				$result='1';
			}else{
					$result='0';
			}
		}
		return $result;
		
	}
    
    
    function getQueueUserList($queue_id){
        
        $qry = 	"SELECT * FROM (select user_id, user_name from user ) AS userData LEFT OUTER JOIN (select q_user.queue_user_id, q_user.queue_id, q_user.user_id, q_user.queue_feature, q_user.queue_user_status,status.status_desc, (CASE WHEN FIND_IN_SET('1', q_user.queue_feature) THEN 1 ELSE 0 END) as chat_feature, (CASE WHEN FIND_IN_SET('2', q_user.queue_feature) THEN 1 ELSE 0 END) as email_feature, (CASE WHEN FIND_IN_SET('3', q_user.queue_feature) THEN 1 ELSE 0 END) as call_feature from  queue_users q_user inner join queue on queue.queue_id = q_user.queue_id left join status on status.status_id = q_user.queue_user_status  where queue.queue_id = '".$queue_id."' and queue.queue_status = 1) AS Quser ON userData.user_id = Quser.user_id";
		
	
        $parms = array();
        $result = $this->dataFetchAll($qry,array());

        return $result;
    }
    
    function queueFeaturesList(){
        
        $qry = "select feature_id, feature_name, feature_status from queue_features";

        $parms = array();
        $result = $this->dataFetchAll($qry,array());

        return $result;

    }
    
    
    function userQueueAccess($user_id){
        
        $qry = "select queue.queue_id, queue.queue_name, queue.queue_status, q_user.queue_feature,  q_user.queue_user_status,(CASE WHEN FIND_IN_SET('1', q_user.queue_feature) THEN 1 ELSE 0 END) as chat_feature, (CASE WHEN FIND_IN_SET('2', q_user.queue_feature) THEN 1 ELSE 0 END) as email_feature, (CASE WHEN FIND_IN_SET('3', q_user.queue_feature) THEN 1 ELSE 0 END) as call_feature from queue inner join queue_users q_user on q_user.queue_id = queue.queue_id  inner join user on user.user_id = q_user.user_id where q_user.user_id = '$user_id' and q_user.queue_user_status = '1' and queue.queue_status = '1' and q_user.queue_user_status = '1'";
            
        $parms = array();
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
    
	
public function updateDepartment($data){
	extract($data);
			
           // $qry = "UPDATE departments SET department_name='$department_name',department_users='$department_users',department_wrapups='$department_wrapups',status='$status',admin_id='$admin_id' where dept_id='$dept_id'";
			 $qry = "UPDATE departments SET department_name='$department_name',alias_name='$alias_name',department_users='$department_users',status='$status',admin_id='$admin_id' where dept_id='$dept_id'";
			
            $update_data = $this->db_query($qry, $params);
            
            if($update_data != 0){
				$qry=$this->db_query("DELETE From dep_drag where dept_id='$dept_id'", array());
                $ex=explode(',',$department_users);
				for($i=0; $i<count($ex); $i++){
					$us=$ex[$i];
					
					$ins=$this->db_query("Insert INTO dep_drag(dept_id,user_id,position) VALUES ( '$dept_id','$us','$i')", array());
				}				
                $result = 1;
$get_qry = $this->fetchData("SELECT ticket_no,ticket_assigned_to FROM external_tickets WHERE `ticket_department`='$dept_id' ORDER BY `ticket_no` DESC LIMIT 1",array());
$ticket_no = $get_qry['ticket_no'];
$ticket_assigned_to = $get_qry['ticket_assigned_to'];			
$get_dep = $this->fetchOne("SELECT `department_users` FROM `departments` WHERE `dept_id`='$dept_id'",array());
$dept_users = explode(',',$get_dep);
$dept_users = array_unique($dept_users);
$current_array_val = array_search($ticket_assigned_to, $dept_users);				
$cnt = count($dept_users);
$next_array_val = $dept_users[$current_array_val+1];
if($next_array_val==''){
	$next_array_val = $dept_users[0];
}else{
	$next_array_val = $dept_users[$current_array_val+1];
}			
$updateqry = "UPDATE external_tickets SET next_assign_for='$next_array_val' WHERE ticket_no='$ticket_no'";
//echo $updateqry;exit;				
$updateqry_data = $this->db_query($updateqry, array());                
            }
            else{
                
                $result = 0;
            }
            
            return $result;
			
		}
	
    function getAgentsDepartment($data){
		extract($data);
        $qry = "select * from departments where dept_id='$dept_id' and admin_id='$admin_id'";
        $result = $this->dataFetchAll($qry,array());
		$ids = $result[0]['department_users'];
		 $qry = "select user_id,agent_name from user where user_id IN ($ids)";
         $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	
	function generateTicket($data){
        extract($data);
        $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		//echo $user_id;
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
		
		$qry = "INSERT INTO internal_tickets(ticket_assigned_by,ticket_created_by,ticket_department,ticket_status,ticket_activity,ticket_res_dept,created_at,updated_at) VALUES ( '$user_id','$user_id','$department_id','1','$activity','$res_departments','$created_at','$updated_at')";       
        
        $insert_data = $this->db_query($qry, $params);
		
		if($insert_data ==1){
			$qry = "SELECT * FROM internal_tickets ORDER BY ticket_no DESC LIMIT 1";
			$result = $this->dataFetchAll($qry, array()); 						
			$tic_no = $result[0]['ticket_no'];
			$created_date = $result[0]['created_at'];
			$updated_date = $result[0]['updated_at'];			
			$assigned_by = $result[0]['ticket_assigned_by'];
			$qry = "select notes from contact_notes where note = '$note_id'";
			$ticket_message = $this->fetchData($qry, array("note"=>$note_id));
			$ticket_message = $ticket_message['notes'];			
			$qry = "INSERT INTO internal_tickets_data(ticket_id,replied_from,ticket_message,assigned_by,created_at,updated_at) VALUES ('$tic_no', '0','$ticket_message','$assigned_by','$created_date','$updated_date')";			
        $result = $this->db_query($qry, $params);	
			
		}
        return $result;
    }
	function getMyTicket($data){
		extract($data);
		if($user_type == 1){
            $qry = "select * from departments";
        } elseif($user_type == 2){
            $qry = "select * from departments where admin_id = '$user_id'";
        }
        else {
             $qry = "select * from departments where department_users LIKE '%$user_id%'";
        }
		$result = $this->dataFetchAll($qry, array()); 
       
		$departments = implode(',', array_map(function ($entry) { return $entry['dept_id']; }, $result));
		
     
		
		$qry = "SELECT ticket_no FROM internal_tickets WHERE ticket_department IN ($departments)";
		$result = $this->dataFetchAll($qry, array()); 
		$result = implode(',', array_map(function ($entry) { return $entry['ticket_no']; }, $result));
        /*$qry = "SELECT *, (select user_name from user where user_id = assigned_by) as assigned_by, (select department_name from departments where dept_id = (select ticket_department from internal_tickets where ticket_no = ticket_id )) as department  FROM internal_tickets_data WHERE ticket_id IN ($result) and replied_from ='0'";*/
		$qry = "SELECT *, (select user_name from user where user_id = assigned_by) as assigned_by, (select IF(ticket_status='1','Open','Closed') from internal_tickets where ticket_no = ticket_id) as ticket_status, (select department_name from departments where dept_id = (select ticket_department from internal_tickets where ticket_no = ticket_id )) as department  FROM internal_tickets_data WHERE ticket_id IN ($result) and replied_from ='0'";
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	
	// 	function viewMyTicket($tic_id){

    //     $qry = "SELECT *, (select user_name from user where user_id = assigned_by) as assigned_by, (select department_name from departments where dept_id = (select ticket_department from internal_tickets where ticket_no = ticket_id )) as department  FROM internal_tickets_data WHERE ticket_id = '$tic_id'";
    //     $result = $this->dataFetchAll($qry,array());
    //     return $result;
    // }
    
    	function viewMyTicket($tic_id){

        $qry = "SELECT *, (select user_name from user where user_id = assigned_by) as assigned_by, (select department_name from departments where dept_id = (select ticket_department from internal_tickets where ticket_no = ticket_id )) as department  FROM internal_tickets_data WHERE ticket_id = '$tic_id' and replied_from ='0'";
        $result["main_data"] = $this->fetchData($qry,array());
        $qry = "SELECT *, (select user_name from user where user_id = replied_from) as assigned_by, (select department_name from departments where dept_id = (select ticket_department from internal_tickets where ticket_no = ticket_id )) as department  FROM internal_tickets_data WHERE ticket_id = '$tic_id' and replied_from !='0'";
			$result["replies"] = $this->dataFetchAll($qry,array());
			$qry = "select * , (select user_name from user where user_id = closed_by) as closed from internal_tickets where ticket_no = '$tic_id'";
        $result["tic_details"] = $this->fetchData($qry,array());
			
        
        return $result;
    }

    public function reAssignTicket($data){
        extract($data);
		$user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
		$qry = "UPDATE `internal_tickets` SET `ticket_assigned_by` = '$user_id', `ticket_department` = '$department_id', `updated_at` = '$updated_at' WHERE `internal_tickets`.`ticket_no` = '$ticket_id'";
		
		 $update_data = $this->db_query($qry, $params);
		
		$qry = "UPDATE `internal_tickets_data` SET  `assigned_by` = '$user_id', `updated_at` = '$updated_at' WHERE `internal_tickets_data`.`ticket_id` = '$ticket_id'";
                $result = $this->db_query($qry, $params);
		
		 return $result;
    }



    function generateSmsTicket($data){
        extract($data);
        $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
		$qry = "INSERT INTO internal_tickets(ticket_assigned_by,ticket_created_by,ticket_department,ticket_status,created_at,updated_at) VALUES ( '$user_id','$user_id','$department_id','1','$created_at','$updated_at')";
        $insert_data = $this->db_query($qry, $params);
		if($insert_data ==1){
			$qry = "SELECT * FROM internal_tickets ORDER BY ticket_no DESC LIMIT 1";
			$result = $this->dataFetchAll($qry, array()); 
			$tic_no = $result[0]['ticket_no'];
			$created_date = $result[0]['created_at'];
			$updated_date = $result[0]['updated_at'];
			$tic_no = $result[0]['ticket_no'];
			$assigned_by = $result[0]['ticket_assigned_by'];
            $qry = "SELECT * FROM `chat_data_sms` WHERE chat_id = (SELECT chat_id FROM `chat_sms` WHERE `customer_name` LIKE '%$phone_num%' ORDER BY `chat_id` ASC LIMIT 1) ORDER BY `chat_msg_id` DESC LIMIT 1";
            $ticket_message = $this->fetchData($qry, array());
			$ticket_message = $ticket_message['chat_message'];
			$qry = "INSERT INTO internal_tickets_data(ticket_id,replied_from,ticket_message,assigned_by,created_at,updated_at) VALUES ('$tic_no', '0','$ticket_message','$assigned_by','$created_date','$updated_date')";
        $result = $this->db_query($qry, $params);	
		}		

        return $result;
    }



       public function replayTicket($data){
        extract($data);              	
        $qry = "SELECT * FROM internal_tickets where ticket_no = '$ticket_id'";
		$result = $this->fetchData($qry, array()); 
        $assigned_by = $result['ticket_assigned_by'];
        $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
        $qry = "INSERT INTO internal_tickets_data(ticket_id,replied_from,ticket_message,assigned_by,created_at,updated_at) VALUES ('$ticket_id', '$user_id','$message','$assigned_by','$created_at','$updated_at')";
        $result = $this->db_query($qry, $params);		
		 return $result;
    }

    public function closeMyTicket($data){
            extract($data);  
$user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);
                //date_default_timezone_set("Indian/Maldives");		        
                $closed_at = date("Y-m-d H:i:s"); 
                $qry = "UPDATE `internal_tickets` SET ticket_status = '0',closed_at = '$closed_at', closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";              
                $result = $this->db_query($qry, $params);
                return $result;
    }
  public function ticketReport($data){
        extract($data);
	  
	  $qry = "select * from user where admin_id = '$user_id'";
	   $results = $this->dataFetchAll($qry, array());
	  $users = implode(',', array_map(function ($entry) { return $entry['user_id']; }, $results));


	  if($tic_status == 'all'){


        if($fromDate == $toDate){

            $qry = "select ticket_no, (select user_name from user where user_id=ticket_created_by) as ticket_created_by, (select department_name from departments where dept_id = ticket_department) as department, ticket_activity, IF(ticket_status='1','Open','closed') as status, date_format(created_at, '%d/%m/%Y') as created_date, date_format(created_at, '%H:%i:%s') as created_time, date_format(closed_at, '%d/%m/%Y') as closed_date, date_format(closed_at, '%H:%i:%s') as closed_time, (select user_name from user where user_id=closed_by) as closed_by, (select department_name from departments where dept_id = ticket_res_dept) as ticket_res_dept from internal_tickets where ticket_created_by IN ($users,$user_id) and created_at like '%$fromDate%'";

        } else {

            $qry = "select ticket_no, (select user_name from user where user_id=ticket_created_by) as ticket_created_by, (select department_name from departments where dept_id = ticket_department) as department, ticket_activity, IF(ticket_status='1','Open','closed') as status, date_format(created_at, '%d/%m/%Y') as created_date, date_format(created_at, '%H:%i:%s') as created_time, date_format(closed_at, '%d/%m/%Y') as closed_date, date_format(closed_at, '%H:%i:%s') as closed_time, (select user_name from user where user_id=closed_by) as closed_by, (select department_name from departments where dept_id = ticket_res_dept) as ticket_res_dept from internal_tickets where ticket_created_by IN ($users,$user_id) and DATE(created_at) >= '$fromDate' and DATE(created_at) <= '$toDate' ";
        }

	  } else {


        if($fromDate == $toDate){

            $qry = "select ticket_no, (select user_name from user where user_id=ticket_created_by) as ticket_created_by, (select department_name from departments where dept_id = ticket_department) as department, ticket_activity, IF(ticket_status='1','Open','closed') as status, date_format(created_at, '%d/%m/%Y') as created_date, date_format(created_at, '%H:%i:%s') as created_time, date_format(closed_at, '%d/%m/%Y') as closed_date, date_format(closed_at, '%H:%i:%s') as closed_time, (select user_name from user where user_id=closed_by) as closed_by, (select department_name from departments where dept_id = ticket_res_dept) as ticket_res_dept from internal_tickets where ticket_created_by IN ($users,$user_id) and created_at like '%$fromDate%'  and ticket_status='$tic_status'";

        } else {
            $qry = "select ticket_no, (select user_name from user where user_id=ticket_created_by) as ticket_created_by, (select department_name from departments where dept_id = ticket_department) as department, ticket_activity, IF(ticket_status='1','Open','closed') as status, date_format(created_at, '%d/%m/%Y') as created_date, date_format(created_at, '%H:%i:%s') as created_time, date_format(closed_at, '%d/%m/%Y') as closed_date, date_format(closed_at, '%H:%i:%s') as closed_time, (select user_name from user where user_id=closed_by) as closed_by, (select department_name from departments where dept_id = ticket_res_dept) as ticket_res_dept from internal_tickets where ticket_created_by IN ($users,$user_id) and DATE(created_at) >= '$fromDate' and DATE(created_at) <= '$toDate' and ticket_status='$tic_status'";

        }

	  }
	  

        $results = $this->dataFetchAll($qry, array());
    
        return  $results;
    }
	function getmyExternalTicket($data){
		extract($data);
		
		
	
		if($user_type == 2){
			//$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id = $user_id ORDER BY a.ticket_no DESC";
		
			if($ticket_status == 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam'";
					$detail_qry = $qry."  ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
					//echo $detail_qry;exit;
			} else if($ticket_status == 'All' && $ticket_department != 'All'){
				 	$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam'";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			
			} else if($ticket_status != 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_status IN ($ticket_status) and is_spam = '$is_spam'";
				 	$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}  else {
				$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status IN ($ticket_status) and ticket_department LIKE '%$ticket_department%'";
				 $detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}
			
			
			
		} else {
			$query = "SELECT user_id FROM user WHERE share_tickets=1";
		    $row = $this->dataFetchAll($query,array());
		    $uarr = array();
		    for($i=0;$i<count($row);$i++){
		    	$uid = $row[$i]['user_id'];
		    	array_push($uarr, $uid);
		    }
		    array_push($uarr,$user_id);			
		    $string = implode(', ', $uarr);
		    //print_r($uarr);exit;
			if($ticket_status == 'All' && $ticket_department == 'All'){				
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and is_spam = '$is_spam'";  
				$detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";		
				$detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				//echo $detail_qry;exit;
			} else if($ticket_status == 'All' && $ticket_department != 'All'){				
				 	$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam'";				 	
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			} else if($ticket_status != 'All' && $ticket_department == 'All'){				  
					$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' ";				 
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}  else {				
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' ";			    
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}
		}		
		

//print_r($detail_qry); exit;

					//TOTAL COUNT CODE 05-07-2021
	
		if($user_type == 2){
	if($ticket_department == 'All'){
       // $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status group by st.status_id order by st.status_id";
   			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			     $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'";     

     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
	
	
	}else if($ticket_department != 'All'){
    //$status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status WHERE et.ticket_department = '$ticket_department' group by et.ticket_status order by et.ticket_status";
		
				$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			     $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'";     

     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		
		
	}else{
      $status_array_qry = "SELECT status_id,status_desc FROM status";
   }
		}else{
		
	if($ticket_department == 'All'){
	    // $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status JOIN external_tickets_data b ON et.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',et.ticket_assigned_to ) OR et.ticket_created_by ='$user_id' group by et.ticket_status order by et.ticket_status";
	$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			 ///    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_created_by ='$user_id' AND is_spam=0";     
		
				$count_query="SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_status = $sid AND ticket_created_by ='$user_id' AND is_spam=0";
				
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		// echo json_encode($status_array_qry, true);
		
	}else if($ticket_department != 'All'){
		// $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status JOIN external_tickets_data b ON et.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',et.ticket_assigned_to ) and et.ticket_department = '$ticket_department' OR et.ticket_created_by ='$user_id' group by et.ticket_status order by et.ticket_status";
		
			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			   //  $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) OR ticket_created_by ='$user_id' AND is_spam=0";
				
				  $count_query = "SELECT * FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_created_by ='$user_id' AND is_spam=0 AND ticket_status = $sid AND ticket_department = '$ticket_department'";
				
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		
	}else{
	      $data1 = "SELECT status_id,status_desc FROM status";
		     $status_array_qry=$this->fetchData($data1,array());
	}
			
		}
	
		//echo $detail_qry;exit;
		$result = $this->dataFetchAll($detail_qry, array());
		
	
        for($i = 0; count($result) > $i; $i++){ 
          $ticket_no = $result[$i]['ticket_no'];	
			$ticket_new_status = $result[$i]['status_del'];	
          $ticket_created_by = $result[$i]['ticket_from'];
		  $ticket_from = $result[$i]['ticket_from'];
          $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
          $ticket_department = $result[$i]['ticket_department'];
          $ticket_status = $result[$i]['ticket_status'];
		  $closed_at = $result[$i]['closed_at'];
          $priority = $result[$i]['priority'];
		  $ticket_created_at = $result[$i]['created_dt'];
          $ticket_message = $result[$i]['ticket_message'];
          $ticket_subject = $result[$i]['ticket_subject'];
			 $spammed = $result[$i]['spammed'];
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());
			
		  //$assignedto_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_assigned_to'";              
         // $assignedto = $this->fetchmydata($assignedto_qry,array());
			
			$ticket_assigned_to = explode(',',$ticket_assigned_to); 
			 if(count($ticket_assigned_to) == 1){
			 $ticket_assigned_t = $ticket_assigned_to[0];
				 $ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";       
          		$ticket_assigned = $this->fetchData($ticket_assigned,array());
				$assignedto=$ticket_assigned['agent_name'];
				//$ticket_assigned_to_id=$ticket_assigned['user_id'];
				 
			 } else {
			 		$assignedto = '';
				   // $ticket_assigned_to_id = '';
			 }
			
			
          
          $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
          $department = $this->fetchmydata($deptment_qry,array());
          $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
          $ticketstatus = $this->fetchmydata($status_qry,array());
		  $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
          $priority_value = $this->fetchmydata($priority_qry,array());		
			
			$time_ago = strtotime($ticket_created_at); 
			
		
		 	 $created_time = $this->time_Ago($time_ago);
			
			//$ticket_created_at = Carbon::parse($ticket_created_at);
			//$created_time =  $ticket_created_at->diffForHumans();
			
			$created_time = date("Y-m-d H:i:s", $time_ago);
			
			//$created_time = $this->get_timeago($created_time2);
			
			
			//print_r($ticket_created_at); exit;
			
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed);
          $ticket_options_array[] = $ticket_options;
        }
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());
			
			//print_r($department_array_qry); print_r($department_options_array); exit;
		//$status_options_array = $this->dataFetchAll($status_array_qry, array());
		$status_options_array = $status_array_qry;
		
		$list_status_array_qry = "SELECT status_id,status_desc FROM status";
		$list_status_options_array = $this->dataFetchAll($list_status_array_qry, array());
		
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());
	
        $status = array('status' => 'true');
        $ticket_options_array = array('ticket_options' => $ticket_options_array);
		$department_options_array = array('department_options' => $department_options_array);
		$count_status_options_array = array('count_options' => $status_options_array);
		$status_options_array = array('status_options' => $list_status_options_array);
		$priority_options_array = array('priority_options' => $priority_options_array);
		//print_r($qry); exit;
		$total_count = $this->dataRowCount($detail_qry2,array());
	    $total = array('total' => $total_count);
            
        $merge_result = array_merge($status, $department_options_array, $status_options_array,$count_status_options_array, $priority_options_array, $ticket_options_array,$total); 
		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
    }
	public function onchangePriority($data){
        extract($data);
        $qry = "UPDATE external_tickets SET priority='$priority_id' where ticket_no='$ticket_id'";		
        $update_data = $this->db_query($qry, array());
		//echo $update_data;exit;
        $result = $update_data == 1 ? 1 : 0;
        return $result;
    }
	
	public function oncloseTocket($data){
		extract($data);		
		//print_r($data); exit;
		$user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
		$user_qry_value = $this->fetchmydata($user_qry,array());
		$user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
		$user_timezone = $this->fetchmydata($user_timezone_qry,array());
		date_default_timezone_set($user_timezone);
		//date_default_timezone_set("Indian/Maldives");		        
		$closed_at = date("Y-m-d H:i:s"); 		
		$qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
		//echo $qry;exit;		 
       	$tic_details = $this->fetchData($qry,array());
		$ticket_assigned_to = $tic_details['ticket_assigned_to'];
		$subject = $tic_details['ticket_subject'];	
		$ticket_from = $tic_details['ticket_to'];
		$tic_from =  $tic_details['ticket_from'];
		if($tic_from == 'user'){
			$main_tick_from = $tic_details['ticket_email'];
		} else {
			$main_tick_from = str_replace('("','',$ticket_from);
			$main_tick_from = str_replace('")','',$main_tick_from);
		}
		$from = $main_tick_from;		
		$ticket_no = $ticket_id;
		$e = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='close_ticket'";
		$repM = $this->fetchOne($e, array());		
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				} else {
					$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";             
		$result = $this->db_query($qry, $params);
		return $result;
					exit;	
				}				
				$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$dmin_id' and user_id='$admin_id' ";
				$mailSignature = $this->fetchOne($qry,array());
				if($mailSignature){	
					$repM =  $repM.$mailSignature;
				} 	
		$repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				$messages = $this->getTicketThread($ticket_no);		
				foreach($messages as $m){
					$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				}
				$mess = implode('<br>',$mess);
				$messagetoSend = $repM.'<br> <br>'.$mess;			
				$message_id = $this->fetchOne("SELECT ticket_reply_id FROM external_tickets_data WHERE ticket_id = '$ticket_id' and ticket_reply_id !=''",array());
      $agent_alert_qry = "SELECT email_id FROM user WHERE close_email_alert=1 AND admin_id='$admin_id'";
      $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      //echo $agent_alert_qry;exit;
	  $agentArr = array();
	  for($k = 0; count($agent_alert_email) > $k; $k++){
		  $agent_emails = $agent_alert_email[$k]['email_id'];		  
		  array_push($agentArr, $agent_emails);
	  }
	  $group_alert_qry = "SELECT email FROM email_group WHERE close_email_alert=1 AND admin_id='$admin_id'";
      $group_alert_email = $this->dataFetchAll($group_alert_qry,array());
      //print_r($agent_alert_email);	  	
	  $groupArr = array();
	  for($j = 0; count($group_alert_email) > $j; $j++){
		  $group_email = $group_alert_email[$j]['email'];
		  $explode = explode(',', $group_email);		  
		  $arr_count = count($explode);
          for($q=0;$q<$arr_count;$q++){
			  $group_emails = $explode[$q];
			  array_push($groupArr, $group_emails);
		  }		  
	  }
	  $ticket_bcc = array_merge($agentArr, $groupArr);         		
				$uss = array("ticket_to"=>"$ticket_to","ticket_cc"=>"$ticket_cc","ticket_bcc"=>$ticket_bcc,"from"=>"$from","message"=>$messagetoSend,"subject"=>$subject,"ticket_id"=>$ticket_no,"message_id"=>$message_id);	
		//print_r($uss);exit;
				//$autoRespns = $this->autoResponseEmail($uss);
		//print_r($autoRespns); exit;
		if($alert_status==1){
			$autoRespns = $this->autoResponseEmail($uss);
		}
		$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";             
		$result = $this->db_query($qry, $params);
		$sub = 'Ticket [#'.$ticket_no.'] has been closed by - '.$agent_name;
		$adm = array("user_id"=>$admin_id,"ticket_for"=>"Close Ticket","ticket_from"=>$ticket_from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_no);
		$us = $this->send_notification($adm);  				
		return $result;
	}
	
	
	public function onchangeStatus($data){
        extract($data);
		//print_r($data); exit;
		if($status_id == '9'){
				$user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
				$user_qry_value = $this->fetchmydata($user_qry,array());
				$user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
				$user_timezone = $this->fetchmydata($user_timezone_qry,array());
				date_default_timezone_set($user_timezone);
                //date_default_timezone_set("Indian/Maldives");		        
                $closed_at = date("Y-m-d H:i:s"); 
                $qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";             
                $result = $this->db_query($qry, $params);
                return $result;
		} elseif($status_id == '5'){
			
				$department_id = $this->fetchOne("SELECT ticket_department FROM `external_tickets` where ticket_no='$ticket_id'",array());
				$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department_id'",array());
			
			 	$qry = "UPDATE `external_tickets` SET ticket_status = '5',ticket_assigned_to ='$get_dep' WHERE ticket_no = '$ticket_id'";    

                $result = $this->db_query($qry, $params);
                $result = $update_data == 1 ? 1 : 0;  
				return $result;
		} else {
				$qry = "UPDATE external_tickets SET ticket_status='$status_id' where ticket_no='$ticket_id'";
				$update_data = $this->db_query($qry, $params);
				$result = $update_data == 1 ? 1 : 0;  
				return $result;
		}
		
        
    }
	
	public function onchangeDepartment($data){
        extract($data);
			$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department_id'",array());
			$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($department_id)",array());
				$dept_users = explode(',',$get_dep);
				$dept_users = array_unique($dept_users);
				$lastExt = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$dept' ORDER BY ticket_no DESC LIMIT 1",array());
				$assignNext = $lastExt['next_assign_for'];
		$ticket_from = $lastExt['ticket_from'];
				if($assignNext){
					$assugnTo = $assignNext;
				} else{
					$assugnTo = $dept_users[0];
				}
		
				$current_array_val = array_search($assugnTo, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0]; 
				$assugnTo = explode(',',$assugnTo);
				$assugnTo = implode(',',$assugnTo);
		
		
		
		//print_r($next_array_val); exit;
		
        $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$assugnTo',next_assign_for='$next_array_val' where ticket_no='$ticket_id'";
		
		//$get_dep = explode(',',$get_dep);
		//print_r($get_dep); exit;
		
	//	$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$assugnTo','$admin_id','$ticket_id','Reply From $from  ($subject)','11','7','$created_at')", array());
				
		
		
			$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$assugnTo'";              
      		$createdby = $this->fetchmydata($createdby_qry,array());
		
		$admin_name = "SELECT agent_name FROM user WHERE user_id='$admin_id'";              
      		$admin_name = $this->fetchmydata($admin_name,array());
		
				$sub = $admin_name.' Assigned a ticket to '.$createdby;
				$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$ticket_from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$us = $this->send_notification($adm);
		
		
        $update_data = $this->db_query($qry, $params);
        $result = $update_data == 1 ? 1 : 0;  
        return $result;
    }
	
	/*public function viewMyTicket($data){
		extract($data);
        $qry = "SELECT *, (select user_name from user where user_id = ticket_created_by) as ticket_created_by, (select department_name from departments where dept_id = (select ticket_department from external_tickets where ticket_no = ticket_id )) as department  FROM external_tickets_data WHERE ticket_id = '$tic_id' and replied_from ='0'";
        $result["main_data"] = $this->fetchData($qry,array());
        $qry = "SELECT *, (select user_name from user where user_id = replied_from) as ticket_created_by, (select department_name from departments where dept_id = (select ticket_department from external_tickets where ticket_no = ticket_id )) as department  FROM external_tickets_data WHERE ticket_id = '$tic_id' and replied_from !='0'";
            $result["replies"] = $this->dataFetchAll($qry,array());
            $qry = "select * , (select user_name from user where user_id = ticket_closed_by) as closed from external_tickets where ticket_no = '$tic_id'";
        $result["tic_details"] = $this->fetchData($qry,array());        
        return $result;
    }*/
	function generateWpTicket($data){
        extract($data);
        $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);  
        $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
        $qry = "INSERT INTO internal_tickets(ticket_assigned_by,ticket_created_by,ticket_department,ticket_status,created_at,updated_at) VALUES ( '$user_id','$user_id','$department_id','1','$created_at','$updated_at')";
        $insert_data = $this->db_query($qry, $params);
        if($insert_data ==1){
            $qry = "SELECT * FROM internal_tickets ORDER BY ticket_no DESC LIMIT 1";
            $result = $this->dataFetchAll($qry, array()); 
            $tic_no = $result[0]['ticket_no'];
            $created_date = $result[0]['created_at'];
            $updated_date = $result[0]['updated_at'];
            $tic_no = $result[0]['ticket_no'];
            $assigned_by = $result[0]['ticket_assigned_by'];
            $qry = "SELECT * FROM `chat_data_wp` WHERE chat_id = (SELECT chat_id FROM `chat_wp` WHERE `customer_name` LIKE '%$phone_num%' ORDER BY `chat_id` ASC LIMIT 1) ORDER BY `chat_msg_id` DESC LIMIT 1";
            $ticket_message = $this->fetchData($qry, array());
            $ticket_message = $ticket_message['chat_message'];
            $qry = "INSERT INTO internal_tickets_data(ticket_id,replied_from,ticket_message,assigned_by,created_at,updated_at) VALUES ('$tic_no', '0','$ticket_message','$assigned_by','$created_date','$updated_date')";
        $result = $this->db_query($qry, $params);   
        }       

        return $result;
    }
	public function delete_department($data){
      extract($data);
           //$qry = "Update departments SET delete_status='1' where dept_id='$department_id' and admin_id='$admin_id'";
       $qry = "DELETE from departments where dept_id='$department_id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	
	
public function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
	
	
	/*public function add_notAssigned_tickets($data){//exit;
		//print_r($data); 
		//exit;
		extract($data); 
		
		$spam_status = '0';
		$explode = explode('<',$from);
        $spam_from = str_replace('>','',$explode[1]);
        $spam_from_val = str_replace(' ','',$spam_from);
	$qry = "select * from spam_mail_ids where email LIKE '%$spam_from_val%'";
    $result =  $this->fetchData($qry, array());	 
	$spam_status = $result['spam_status'];
	$blacklist_status = $result['blacklist_status'];

	if($spam_status > 0){
		echo 'Blacklisted';
			exit;
	}
		
		$attachments = json_decode($data['attachments']);
		$attachments = implode(',',$attachments);

		
		 $to = str_replace('[','(',$to_mail);
		 $to = str_replace(']',')',$to);
		//print_r($to); 
		
		$cc = str_replace('[','(',$cc_mail);
		 $cc = str_replace(']',')',$cc);
		
		$to_mail2=$to_mail;
		$to_mail2 = explode(',',$to_mail2);
		$to_mail = explode(',',$to_mail);
		

		
		
		$searchword = 'pipe.mconnectapps.com';
		$to_mail = array_filter($to_mail, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }); 
		if(!$to_mail){ 
			
	
		 $to2 = str_replace('[','',$to_mail2[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);
			
		$alise = $to2;	
		
		$qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$to2%'";		
        $qry_value = $this->fetchOne($qry,array());
		$to2 = $qry_value;
		$to = str_replace($alise,$to2,$to);
		} else { 
			
		$to2 = str_replace('[','',$to_mail[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);
		}
	
	

		
		
       // $qry = "SELECT * FROM admin_details WHERE support_email IN $to"; 

		$qry = "SELECT * FROM `admin_details` WHERE `support_email` LIKE '%$to2%'";
        $qry_value = $this->fetchData($qry,array());
		
		 
		
		
        if($qry_value > 0){
			

			$dept = $this->fetchOne("SELECT departments FROM department_emails where emailAddr='$to2'",array());
			
			
			
			
            $admin_id = $qry_value['admin_id'];		
		$user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
		
	
			
			if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
				//$val = 'if';
			    //file_put_contents('check.txt', $val.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				$subject = substr($subject, 4);
				$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$subject'";
       			$ticket_no = $this->fetchOne($qry,array());
				
				$ticket_no = $this->get_string_between($subject, '[##', ']');
				//print_r($ticket_no); exit;
				
				$get_dep=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_subject='$subject'",array());
				
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				
				
			
				
				
				$qryss = "UPDATE `external_tickets` SET status_del = '1',ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);
				
				$qryss = "UPDATE `external_tickets_data` SET ticket_reply_id ='$ticket_reply_id' WHERE ticket_id = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);
				
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				// $dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$dt')", array());
				foreach($dept_users as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$created_at')", array());
					$us = array("user_id"=>$user,"ticket_for"=>"Reply Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				}
				$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				$mailSignature = $this->fetchOne($qry,array());
				if($mailSignature){	
					$repM =  $mailSignature;
				} 	
				$repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				$messages = $this->getTicketThread($ticket_no);
				foreach($messages as $m){
					$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				}
	  			$mess = implode('<br>',$mess);
	  			$messagetoSend = $repM.'<br> <br>'.$mess;
      			$agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      			//print_r($agent_alert_email);
	  			$agentArr = array();
	  			for($k = 0; $k < count($agent_alert_email); $k++){
		  			$agent_emails = $agent_alert_email[$k]['email_id'];	  
		  			array_push($agentArr, $agent_emails);
	  			}
	  			$group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$group_alert_email = $this->dataFetchAll($group_alert_qry,array());
      			//print_r($agent_alert_email);	  	
	  			$groupArr = array();
	  			for($j = 0; $j < count($group_alert_email); $j++){
		  			$group_email = $group_alert_email[$j]['email'];
		  			$explode = explode(',', $group_email);		  
		  			$arr_count = count($explode);
          			for($q=0;$q<$arr_count;$q++){
			  			$group_emails = $explode[$q];
			  			array_push($groupArr, $group_emails);
		  			}		  
	  			}
	  			$ticket_bcc = array_merge($agentArr, $groupArr);
				$senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
                $senderid_qry_value = $this->fetchOne($senderid_qry,array());		        
				$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
      			$autoRespns = $this->autoResponseEmail($uss);
			} else {
				//$val = 'else';
			    //file_put_contents('check.txt', $val.PHP_EOL , FILE_APPEND | LOCK_EX); exit;
				//file_put_contents('vai.txt', print_r($ticket_bcc,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
				$dept_users = explode(',',$get_dep);
				//$dept_users[] = $admin_id;
				$dept_users = array_unique($dept_users);				
				$lastExt = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$dept' ORDER BY ticket_no DESC LIMIT 1",array());
				$assignNext = $lastExt['next_assign_for'];
				if($assignNext){
					$assugnTo = $assignNext;
				} else{
					$assugnTo = $dept_users[0];
				}
				$current_array_val = array_search($assugnTo, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0]; 
				//print_r($next_array_val); exit;				
				$assugnTo = explode(',',$assugnTo);
				//$assugnTo[] = $admin_id;
				$assugnTo = implode(',',$assugnTo);
				//print_r($dept_users); exit;				
				//$get_dep = implode(',',$dept_users);				
				$assignedTo = $assugnTo;
			 $ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam) VALUES ( '$from','$admin_id','3','2','$subject','$to2','$assugnTo','$next_array_val','$dept','$created_at','$updated_at',1,'$spam_status')", array());
				//$tt = "INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,ticket_department,status) VALUES ( '$from','$admin_id','1','2','$subject','$to','$get_dep','$dept','1')";
				//print_r($tt); exit;
			//$qryss = "UPDATE `external_tickets` SET status = '1',ticket_status = '1'  WHERE ticket_no = '$ticket_no'";             
              //  $resultss = $this->db_query($qryss, $params);				
				$subject = $subject.' [##'.$ticket_no.']';
				$qry = "UPDATE `external_tickets` SET  `ticket_subject` = '$subject' WHERE `ticket_no` = '$ticket_no'";
                $resultss = $this->db_query($qry, $params);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());
			//$tt = "INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')";
				//print_r($tt); exit;				
				
	if($spam_status > 0){
		echo 'SpamListed';
			exit;
	}
				
				 $dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$dt')", array());
				
				 $assugnTo = explode(',',$assugnTo);
			     $assugnTo[] = $admin_id;
		
			foreach($assugnTo as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$created_at')", array());
					$us = array("user_id"=>$user,"ticket_for"=>"New Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				} 
				
				
				
			$replied_from = strstr($from, '<');
			 	$replied_from = str_replace('< ', '',$replied_from); 
				$replied_from = str_replace(' >', '',$replied_from);
			
				
				$replied_from = str_replace($to2,$replied_from,$to);

				
				$replied_from = str_replace('("', '',$replied_from); 
				$replied_from = str_replace('")', '',$replied_from);
				$replied_from = str_replace('"', '',$replied_from);
				$ccs = str_replace('("', '',$cc); 
				$ccs = str_replace('")', '',$ccs);
				$ccs = str_replace('"', '',$ccs);
			
				$qry = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='created_ticket'";
				$repM = $this->fetchOne($qry,array());
				
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				
	
				
				$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				$mailSignature = $this->fetchOne($qry,array());
				if($mailSignature){	
					$repM =  $repM.$mailSignature;
				} 	
					$repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				$messages = $this->getTicketThread($ticket_no);
				foreach($messages as $m){
					$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				}
				$mess = implode('<br>',$mess);
				$messagetoSend = $repM.'<br> <br>'.$mess;
$agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
      $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      //print_r($agent_alert_email);	  		
	  $agentArr = array();
	  for($k = 0; $k < count($agent_alert_email); $k++){
		  $agent_emails = $agent_alert_email[$k]['email_id'];		  
		  array_push($agentArr, $agent_emails);
	  }
	  $group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
      $group_alert_email = $this->dataFetchAll($group_alert_qry,array());
      //print_r($agent_alert_email);	  	
	  $groupArr = array();
	  for($j = 0; $j < count($group_alert_email); $j++){
		  $group_email = $group_alert_email[$j]['email'];
		  $explode = explode(',', $group_email);		  
		  $arr_count = count($explode);
          for($q=0;$q<$arr_count;$q++){
			  $group_emails = $explode[$q];
			  array_push($groupArr, $group_emails);
		  }		  
	  }
	  //file_put_contents('vaithee.txt', $agentArr.PHP_EOL , FILE_APPEND | LOCK_EX);
	  //file_put_contents('vai.txt', $groupArr.PHP_EOL , FILE_APPEND | LOCK_EX);				
	  $ticket_bcc = array_merge($agentArr, $groupArr);
	  $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
      $senderid_qry_value = $this->fetchOne($senderid_qry,array());				
				$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
				$autoRespns = $this->autoResponseEmail($uss);					
				print_r($autoRespns); exit;	
					} 
			}
        }
        else{           
            $result = array("data" => 0);
            $result_array = json_encode($result);           
            print_r($result_array);exit;
       }         
    }*/
	
public function add_notAssigned_tickets($data){		
extract($data);	
	
// Word Filter
$qry = "select filter_word from email_words_filtering where user_id ='64'";
$result =  $this->dataFetchAll($qry, array());	 

	for($j = 0; $j < count($result); $j++){
		$groupArr = $result[$j]['filter_word'];
		if (strpos($message, $groupArr) !== false) {
			echo 'Spam Word true'; 
			exit;
		}
	} 
// Word Filter  
$rep1 = str_replace('[','',$to);
$rep2 = str_replace(']','',$rep1);
$rep3 = str_replace('"','',$rep2);
//file_put_contents('vaithee.txt', $rep3.PHP_EOL , FILE_APPEND | LOCK_EX);	
$qry = "select * from admin_details where support_email LIKE '%$rep3%'";
$result =  $this->fetchData($qry, array());	 
$override = $result['override'];
if($override==0){
        $spam_status = '0';
		$explode = explode('<',$from);
        $spam_from = str_replace('>','',$explode[1]);
        $spam_from_val = str_replace(' ','',$spam_from);
		$qry = "select * from spam_mail_ids where email LIKE '%$spam_from_val%'";
	    $result =  $this->fetchData($qry, array());	 
		$spam_status = $result['spam_status'];
		$blacklist_status = $result['blacklist_status'];
		if($spam_status > 0){
			echo 'Blacklisted';
			exit;
		}		
		$attachments = json_decode($data['attachments']);
		$attachments = implode(',',$attachments);		
		$to = str_replace('[','(',$to_mail);
		$to = str_replace(']',')',$to);
		//print_r($to); 
		$cc = str_replace('[','(',$cc_mail);
		$cc = str_replace(']',')',$cc);		
		$to_mail2=$to_mail;
		$to_mail2 = explode(',',$to_mail2);
		$to_mail = explode(',',$to_mail);		
		$searchword = 'pipe.mconnectapps.com';
		$to_mail = array_filter($to_mail, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }); 
		if(!$to_mail){ 	
		 $to2 = str_replace('[','',$to_mail2[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);			
		 $alise = $to2;	
		 $qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$to2%'";		
         $qry_value = $this->fetchOne($qry,array());
		 $to2 = $qry_value;
		 $to = str_replace($alise,$to2,$to);
			if($to==''){
				    $to=$rep3;
			}
		} else { 			
		 $to2 = str_replace('[','',$to_mail[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);
		 if($to2==''){
				    $to2=$rep3;
			}
		}	
		$qry = "SELECT * FROM `admin_details` WHERE `support_email` LIKE '%$to2%'";
        $qry_value = $this->fetchData($qry,array());		
        if($qry_value > 0){
			if($to2==''){
				    $to2=$rep3;
			}
			$dept = $this->fetchOne("SELECT departments FROM department_emails where emailAddr='$to2'",array());			
			$admin_id = $qry_value['admin_id'];		
		    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
            $user_qry_value = $this->fetchmydata($user_qry,array());
	        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
            $user_timezone = $this->fetchmydata($user_timezone_qry,array());
            date_default_timezone_set($user_timezone);  
     	    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");		
			if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){				
				$subject = substr($subject, 4);
				$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$subject'";
       			$ticket_no = $this->fetchOne($qry,array());				
				$ticket_no = $this->get_string_between($subject, '[##', ']');			
				$get_dep=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_subject='$subject'",array());				
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				$qryss = "UPDATE `external_tickets` SET status_del = '1',ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qryss = "UPDATE `external_tickets_data` SET ticket_reply_id ='$ticket_reply_id' WHERE ticket_id = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				// $dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$dt')", array());
				foreach($dept_users as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$created_at')", array());
					
					$us = array("user_id"=>$user,"ticket_for"=>"Reply Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				}
				$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				$mailSignature = $this->fetchOne($qry,array());
				if($mailSignature){	
					$repM =  $mailSignature;
				} 	
				$repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				$messages = $this->getTicketThread($ticket_no);
				foreach($messages as $m){
					$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				}
	  			$mess = implode('<br>',$mess);
	  			$messagetoSend = $repM.'<br> <br>'.$mess;
      			$agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      			//print_r($agent_alert_email);
	  			$agentArr = array();
	  			for($k = 0; $k < count($agent_alert_email); $k++){
		  			$agent_emails = $agent_alert_email[$k]['email_id'];	  
		  			array_push($agentArr, $agent_emails);
	  			}
	  			$group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$group_alert_email = $this->dataFetchAll($group_alert_qry,array());
      			//print_r($agent_alert_email);	  	
	  			$groupArr = array();
	  			for($j = 0; $j < count($group_alert_email); $j++){
		  			$group_email = $group_alert_email[$j]['email'];
		  			$explode = explode(',', $group_email);		  
		  			$arr_count = count($explode);
          			for($q=0;$q<$arr_count;$q++){
			  			$group_emails = $explode[$q];
			  			array_push($groupArr, $group_emails);
		  			}		  
	  			}
	  			$ticket_bcc = array_merge($agentArr, $groupArr);
				$senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
                $senderid_qry_value = $this->fetchOne($senderid_qry,array());		        
				$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
      			$autoRespns = $this->autoResponseEmail($uss);
			} 
			else {				
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
				$dept_users = explode(',',$get_dep);
				//$dept_users[] = $admin_id;
				$dept_users = array_unique($dept_users);				
				$assugnTo = $dept_users;		
				if($to2==''){
				    $to2=$rep3;
				}
			    $ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam) VALUES ( '$from','$admin_id','3','2','$subject','$to2','$get_dep','','$dept','$created_at','$updated_at',1,'$spam_status')", array());							
				$subject = $subject.' [##'.$ticket_no.']';
				$qry = "UPDATE `external_tickets` SET  `ticket_subject` = '$subject' WHERE `ticket_no` = '$ticket_no'";
                $resultss = $this->db_query($qry, $params);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());			
				if($spam_status > 0){
					echo 'SpamListed';
						exit;
				}				
				$dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$dt')", array());		
				$assugnTo = explode(',',$assugnTo);
			    $dept_users[] = $admin_id;		
			    foreach($dept_users as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$created_at')", array());
					$us = array("user_id"=>$user,"ticket_for"=>"New Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				} 				
			    $replied_from = strstr($from, '<');
			 	$replied_from = str_replace('< ', '',$replied_from); 
				$replied_from = str_replace(' >', '',$replied_from);	
				$replied_from = str_replace($to2,$replied_from,$to);	
				$replied_from = str_replace('("', '',$replied_from); 
				$replied_from = str_replace('")', '',$replied_from);
				$replied_from = str_replace('"', '',$replied_from);
				$ccs = str_replace('("', '',$cc); 
				$ccs = str_replace('")', '',$ccs);
				$ccs = str_replace('"', '',$ccs);			
				$qry = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='created_ticket'";
				$repM = $this->fetchOne($qry,array());				
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				    $qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				    $mailSignature = $this->fetchOne($qry,array());
				    if($mailSignature){	
					  $repM =  $repM.$mailSignature;
				    } 	
				    $repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				    $messages = $this->getTicketThread($ticket_no);
				    foreach($messages as $m){
					 $mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				    }
				    $mess = implode('<br>',$mess);
				    $messagetoSend = $repM.'<br> <br>'.$mess;
                    $agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
			        $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
			      //print_r($agent_alert_email);	  		
				    $agentArr = array();
				    for($k = 0; $k < count($agent_alert_email); $k++){
				     $agent_emails = $agent_alert_email[$k]['email_id'];
				     array_push($agentArr, $agent_emails);
				    }
				    $group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
			        $group_alert_email = $this->dataFetchAll($group_alert_qry,array());
			        //print_r($agent_alert_email);	  	
				    $groupArr = array();
				    for($j = 0; $j < count($group_alert_email); $j++){
					  $group_email = $group_alert_email[$j]['email'];
					  $explode = explode(',', $group_email);		  
					  $arr_count = count($explode);
			          for($q=0;$q<$arr_count;$q++){
						  $group_emails = $explode[$q];
						  array_push($groupArr, $group_emails);
					  }		  
				    }
				    //file_put_contents('vaithee.txt', $agentArr.PHP_EOL , FILE_APPEND | LOCK_EX);
				    //file_put_contents('vai.txt', $groupArr.PHP_EOL , FILE_APPEND | LOCK_EX);				
				    $ticket_bcc = array_merge($agentArr, $groupArr);
				    $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
			        $senderid_qry_value = $this->fetchOne($senderid_qry,array());				
				    $uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
				    $autoRespns = $this->autoResponseEmail($uss);		
				    print_r($autoRespns); exit;	
				} 
			}
        }
        else{           
            $result = array("data" => 0);
            $result_array = json_encode($result);           
            print_r($result_array);exit;
       }
}else{ // override else condition starts here
        $spam_status = '0';
		$explode = explode('<',$from);
        $spam_from = str_replace('>','',$explode[1]);
        $spam_from_val = str_replace(' ','',$spam_from);
		$qry = "select * from spam_mail_ids where email LIKE '%$spam_from_val%'";
	    $result =  $this->fetchData($qry, array());	 
		$spam_status = $result['spam_status'];
		$blacklist_status = $result['blacklist_status'];
		if($spam_status > 0){
			echo 'Blacklisted';
			exit;
		}		
		$attachments = json_decode($data['attachments']);
		$attachments = implode(',',$attachments);		
		$to = str_replace('[','(',$to_mail);
		$to = str_replace(']',')',$to);
		//print_r($to); 
		$cc = str_replace('[','(',$cc_mail);
		$cc = str_replace(']',')',$cc);		
		$to_mail2=$to_mail;
		$to_mail2 = explode(',',$to_mail2);
		$to_mail = explode(',',$to_mail);		
		$searchword = 'pipe.mconnectapps.com';
		$to_mail = array_filter($to_mail, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); }); 
		if(!$to_mail){ 	
		 $to2 = str_replace('[','',$to_mail2[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);			
		 $alise = $to2;	
		 $qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$to2%'";		
         $qry_value = $this->fetchOne($qry,array());
		 $to2 = $qry_value;
		 $to = str_replace($alise,$to2,$to);
		} else { 			
		 $to2 = str_replace('[','',$to_mail[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);
		}	
		$qry = "SELECT * FROM `admin_details` WHERE `support_email` LIKE '%$to2%'";
        $qry_value = $this->fetchData($qry,array());		
        if($qry_value > 0){
			if($to2==''){
				    $to2=$rep3;
			}
			$dept = $this->fetchOne("SELECT departments FROM department_emails where emailAddr='$to2'",array());
			$admin_id = $qry_value['admin_id'];		
		    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
            $user_qry_value = $this->fetchmydata($user_qry,array());
	        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
            $user_timezone = $this->fetchmydata($user_timezone_qry,array());
            date_default_timezone_set($user_timezone);  
     	    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");		
			if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){				
				$subject = substr($subject, 4);
				$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$subject'";
       			$ticket_no = $this->fetchOne($qry,array());				
				$ticket_no = $this->get_string_between($subject, '[##', ']');			
				$get_dep=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_subject='$subject'",array());				
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				$qryss = "UPDATE `external_tickets` SET status_del = '1',ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qryss = "UPDATE `external_tickets_data` SET ticket_reply_id ='$ticket_reply_id' WHERE ticket_id = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				// $dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$dt')", array());
				foreach($dept_users as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$created_at')", array());
					$us = array("user_id"=>$user,"ticket_for"=>"Reply Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				}
				$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				$mailSignature = $this->fetchOne($qry,array());
				if($mailSignature){	
					$repM =  $mailSignature;
				} 	
				$repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				$messages = $this->getTicketThread($ticket_no);
				foreach($messages as $m){
					$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				}
	  			$mess = implode('<br>',$mess);
	  			$messagetoSend = $repM.'<br> <br>'.$mess;
      			$agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      			//print_r($agent_alert_email);
	  			$agentArr = array();
	  			for($k = 0; $k < count($agent_alert_email); $k++){
		  			$agent_emails = $agent_alert_email[$k]['email_id'];	  
		  			array_push($agentArr, $agent_emails);
	  			}
	  			$group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
      			$group_alert_email = $this->dataFetchAll($group_alert_qry,array());
      			//print_r($agent_alert_email);	  	
	  			$groupArr = array();
	  			for($j = 0; $j < count($group_alert_email); $j++){
		  			$group_email = $group_alert_email[$j]['email'];
		  			$explode = explode(',', $group_email);		  
		  			$arr_count = count($explode);
          			for($q=0;$q<$arr_count;$q++){
			  			$group_emails = $explode[$q];
			  			array_push($groupArr, $group_emails);
		  			}		  
	  			}
	  			$ticket_bcc = array_merge($agentArr, $groupArr);
				$senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
                $senderid_qry_value = $this->fetchOne($senderid_qry,array());		        
				$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
      			$autoRespns = $this->autoResponseEmail($uss);
			} 
			else {				
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
				$dept_users = explode(',',$get_dep);
				//$dept_users[] = $admin_id;
				$dept_users = array_unique($dept_users);				
				$lastExt = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$dept' ORDER BY ticket_no DESC LIMIT 1",array());
				$assignNext = $lastExt['next_assign_for'];
				if($assignNext){
					$assugnTo = $assignNext;
				} else{
					$assugnTo = $dept_users[0];
				}
				$current_array_val = array_search($assugnTo, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0]; 
				//print_r($next_array_val); exit;				
				$assugnTo = explode(',',$assugnTo);
				//$assugnTo[] = $admin_id;
				$assugnTo = implode(',',$assugnTo);
				//print_r($dept_users); exit;				
				//$get_dep = implode(',',$dept_users);				
				$assignedTo = $assugnTo;
				if($to2==''){
				    $to2=$rep3;
				}
			    $ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam) VALUES ( '$from','$admin_id','3','2','$subject','$to2','$assugnTo','$next_array_val','$dept','$created_at','$updated_at',1,'$spam_status')", array());							
				$subject = $subject.' [##'.$ticket_no.']';
				$qry = "UPDATE `external_tickets` SET  `ticket_subject` = '$subject' WHERE `ticket_no` = '$ticket_no'";
                $resultss = $this->db_query($qry, $params);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$to','$cc','$attachments','$ticket_reply_id','$created_at')", array());			
				if($spam_status > 0){
					echo 'SpamListed';
						exit;
				}				
				$dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$dt')", array());		
				$assugnTo = explode(',',$assugnTo);
			    $assugnTo[] = $admin_id;		
			    foreach($assugnTo as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Ticket From $from  ($subject)','11','7','$created_at')", array());
					$us = array("user_id"=>$user,"ticket_for"=>"New Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				} 				
			    $replied_from = strstr($from, '<');
			 	$replied_from = str_replace('< ', '',$replied_from); 
				$replied_from = str_replace(' >', '',$replied_from);	
				$replied_from = str_replace($to2,$replied_from,$to);	
				$replied_from = str_replace('("', '',$replied_from); 
				$replied_from = str_replace('")', '',$replied_from);
				$replied_from = str_replace('"', '',$replied_from);
				$ccs = str_replace('("', '',$cc); 
				$ccs = str_replace('")', '',$ccs);
				$ccs = str_replace('"', '',$ccs);			
				$qry = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='created_ticket'";
				$repM = $this->fetchOne($qry,array());				
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				    $qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				    $mailSignature = $this->fetchOne($qry,array());
				    if($mailSignature){	
					  $repM =  $repM.$mailSignature;
				    } 	
				    $repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				    $messages = $this->getTicketThread($ticket_no);
				    foreach($messages as $m){
					 $mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				    }
				    $mess = implode('<br>',$mess);
				    $messagetoSend = $repM.'<br> <br>'.$mess;
                    $agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id'";
			        $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
			      //print_r($agent_alert_email);	  		
				    $agentArr = array();
				    for($k = 0; $k < count($agent_alert_email); $k++){
				     $agent_emails = $agent_alert_email[$k]['email_id'];
				     array_push($agentArr, $agent_emails);
				    }
				    $group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
			        $group_alert_email = $this->dataFetchAll($group_alert_qry,array());
			        //print_r($agent_alert_email);	  	
				    $groupArr = array();
				    for($j = 0; $j < count($group_alert_email); $j++){
					  $group_email = $group_alert_email[$j]['email'];
					  $explode = explode(',', $group_email);		  
					  $arr_count = count($explode);
			          for($q=0;$q<$arr_count;$q++){
						  $group_emails = $explode[$q];
						  array_push($groupArr, $group_emails);
					  }		  
				    }
				    //file_put_contents('vaithee.txt', $agentArr.PHP_EOL , FILE_APPEND | LOCK_EX);
				    //file_put_contents('vai.txt', $groupArr.PHP_EOL , FILE_APPEND | LOCK_EX);				
				    $ticket_bcc = array_merge($agentArr, $groupArr);
				    $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to2%'";
			        $senderid_qry_value = $this->fetchOne($senderid_qry,array());				
				    $uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
				    $autoRespns = $this->autoResponseEmail($uss);		
				    print_r($autoRespns); exit;	
				} 
			}
        }
        else{           
            $result = array("data" => 0);
            $result_array = json_encode($result);           
            print_r($result_array);exit;
       }
} // override else condition ends here	
}		  
		  
		  
	public function list_notAssigned_tickets($data){
        extract($data);  		
        $qry = "select * from pipe_data where admin_id ='$admin_id' and status = 0 and delete_status = 0";
        $result =  $this->dataFetchAll($qry, array());       
        return $result;
    }
	public function generateExternalTicket($data){
        extract($data);
        $pipe_qry = "SELECT * FROM pipe_data WHERE id='$id'";
        $pipe_qry_value = $this->fetchData($pipe_qry,array());
        $from = $pipe_qry_value['from_email'];
        $to = $pipe_qry_value['to_email'];
        $subject = $pipe_qry_value['subject'];
        $message = $pipe_qry_value['message'];
        $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());      
        date_default_timezone_set($user_timezone);  
        $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s"); 
        $qry = "INSERT INTO external_tickets(ticket_created_by,ticket_assigned_to,ticket_department,admin_id,ticket_status,priority,created_at,updated_at) VALUES ( '$user_id','$assigned_to','$department_id','$admin_id','$ticket_status','$priority','$created_at','$updated_at')";
        $insert_data = $this->db_query($qry, $params);        
        if($insert_data ==1){
            $qry = "SELECT * FROM external_tickets ORDER BY ticket_no DESC LIMIT 1";
            $result = $this->dataFetchAll($qry, array());                    
            $tic_no = $result[0]['ticket_no'];
            $created_date = $result[0]['created_at'];                         
            $data_insert_qry =  $this->db_query("INSERT INTO external_tickets_data(ticket_id,replied_from,ticket_message,ticket_subject,created_at) VALUES ('$tic_no', '0','$message','$subject','$created_date')", array());
			$smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
            $smtp_qry_value = $this->fetchData($smtp_qry,array());
            $hostname = $smtp_qry_value['hostname'];
            $port = $smtp_qry_value['port'];
            $username = $smtp_qry_value['username'];
            $password = $smtp_qry_value['password'];
            if($assigned_to != ''){
                $user_email_qry = "SELECT email_id FROM user WHERE user_id='$assigned_to'";
                $user_email = $this->fetchmydata($user_email_qry,array());    
                
				$subject = "New Ticket Alert #".$tic_no."-".$subject; 
                $body = addslashes($message);                
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true; 
                $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                $mail->Host = $hostname; // sets the SMTP server
                $mail->Port = $port;                    // set the SMTP port for the GMAIL server
                $mail->Username = $username; // SMTP account username
                $mail->Password = $password;        // SMTP account password 
                $mail->SetFrom($to, $to);
                $mail->AddReplyTo($from, $from);
                $mail->Subject = $subject;
                $mail->MsgHTML($body);
                $mail->AddAddress($user_email);    
                $mail->Send();
            }else{
                $user_id_qry = "SELECT department_users FROM departments WHERE dept_id='$department_id'";
                $user_id = $this->fetchmydata($user_id_qry,array());
                $useremail_qry = "SELECT email_id FROM user WHERE user_id IN ($user_id)";
                $user_email_list = $this->dataFetchAll($useremail_qry,array());
                $count = count($user_email_list);				
                for($i=0;$i<=$count;$i++){
                    $user_email = $user_email_list[$i]['email_id'];
                    require_once('class.phpmailer.php');
                    $subject = "New Ticket Alert #".$tic_no."-".$subject; 
                    $body = addslashes($message);                
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true; 
                    $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                    $mail->Host = $hostname; // sets the SMTP server
                    $mail->Port = $port;                    // set the SMTP port for the GMAIL server
                    $mail->Username = $username; // SMTP account username
                    $mail->Password = $password;        // SMTP account password 
                    $mail->SetFrom($to, $to);
                    $mail->AddReplyTo($from, $from);
                    $mail->Subject = $subject;
                    $mail->MsgHTML($body);
                    $mail->AddAddress($user_email);    
                    $mail->Send();
                }
            }
			$update_qry = "UPDATE pipe_data SET status = 1 where id='$id'";
            $updateqry_result = $this->db_query($update_qry, array());
            $result = $data_insert_qry == 1 ? 1 : 0;          
            return $result;
        }
        else{           
            $result = array("data" => 0);
            $result_array = json_encode($result);           
            print_r($result_array);exit;
        }
    }
	public function external_ticket_dropdown($data){
        extract($data);       
        $user_qry = "select * from user where user_id ='$user_id'";
        $$user_qry_result =  $this->fetchData($user_qry, array());
        extract($$user_qry_result);
        if($admin_id == '1'){ $admin_id = $user_id; } else { $admin_id = $admin_id ; }
        $department_qry = "select * from departments where delete_status != 1 and admin_id='$admin_id'";
        $result['departments'] = $this->dataFetchAll($department_qry,array());
        $priority_qry = "select * from priority";
        $result['priority'] = $this->dataFetchAll($priority_qry,array());
        $status_qry = "select * from ticket_status";
        $result['ticket_status'] = $this->dataFetchAll($status_qry,array());
        return $result;
    }
	
	public function viewExternalTicket($data){
		extract($data); 
		$ticket_id = base64_decode($ticket_id);
			
		 $qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
       	$tic_details = $this->fetchData($qry,array());
		
		$qryss = "UPDATE `external_tickets` SET status_del = '0' WHERE ticket_no = '$ticket_id'";             
                $resultss = $this->db_query($qryss, $params);
		
		
		
		
		$ticket_user = $tic_details['ticket_from'];
		if($ticket_user!='user'){
		 $explode_from = explode('<',$ticket_user);
		 $exp1 = explode('>',$explode_from[1]);
		 $rep_fm = $exp1[0];
		}else{
		   //$rep_fm = 'user'; 
			$rep_fm = $tic_details['ticket_to'];
		}
		$ticket_created_by = $tic_details['ticket_created_by'];
		$admin_id = $tic_details['admin_id'];
		if($ticket_user=='user'){
		  $qry = "SELECT support_email,admin_id FROM admin_details WHERE admin_id='$ticket_created_by'";		
		  $get_det=$this->fetchData("SELECT agent_name,profile_image FROM user where user_id='$ticket_created_by'",array());
		  $user_name=$get_det['agent_name'];
		  $own_img=$get_det['profile_image'];
		$ticket_from = $tic_details['ticket_email'];
			
		}else{
			$ticket_from = $tic_details['ticket_to'];
			$ticket_from = str_replace('")','',$ticket_from);
			$ticket_from = str_replace('("','',$ticket_from);		
			$qry = "SELECT support_email,admin_id  FROM admin_details WHERE support_email LIKE '%$ticket_from%'";
		 	$user_name='';
			
		}

		
		
		$from=$ticket_from;
		
		
        $res_val= $this->fetchData($qry,array());
		
		//$admin_id=$res_val['admin_id'];
		
		
		//print_r($from); exit;

		if($user_name==''){
		$own_img= $this->fetchOne("SELECT profile_image FROM user where user_id='$admin_id'",array());
		}else{
			$own_img=$own_img;
		}
	
		$to = str_replace("(","",$to);
		$to = str_replace(")","",$to);
		$to = str_replace('"',"",$to);
		$to = explode(',',$to);	
		unset($to[array_search( $from, $to )]);
		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
				$upload_location = $destination_path."ext-ticket-image/";
		$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
		$detail_qry=$qry."ORDER BY b.ticket_message_id DESC LIMIT $limit offset $offset";
	//echo $detail_qry;exit;
			
        $result =  $this->dataFetchAll($detail_qry, array());
		
		 for($i = 0; count($result) > $i; $i++){ 
          $ticket_no = $result[$i]['ticket_no'];
		  $ticket_message_ids = $result[$i]['ticket_message_id'];
          $ticket_created_by = $result[$i]['ticket_from'];
		  $ticket_from = $result[$i]['ticket_from'];
          $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
          $ticket_department = $result[$i]['ticket_department'];
          $ticket_status = $result[$i]['ticket_status'];
          $priority = $result[$i]['priority'];
		  $ticket_notes =  $result[$i]['ticket_notes'];
		  $ticket_notes_by =  $result[$i]['ticket_notes_by'];
		  $ticket_created_at = $result[$i]['created_dt'];
          $ticket_message = $result[$i]['ticket_message'];
		  $ticket_medias = $result[$i]['ticket_media'];
		  $ticket_media = explode(',', $ticket_medias );
		  $ticket_media = $ticket_media;
          $ticket_subject = $result[$i]['ticket_subject'];
		  $replied_from_db = $result[$i]['replied_from'];
		  $replied_by = $result[$i]['repliesd_by'];
		  $ticket_to = $result[$i]['replied_to'];
		  //$ticket_to = $result[$i]['ticket_to'];	 
		  $ticket_user = $result[$i]['user_id'];
		  $ccMails = $result[$i]['replied_cc'];
		  $is_spam = $result[$i]['is_spam'];
		  $closedby = $result[$i]['ticket_closed_by']; 	 
			if($ticket_user!='') {
				$rep= $this->fetchData("SELECT profile_image,user_name,agent_name FROM user where user_id='$ticket_user'",array());
				$rep_img=$rep['profile_image'];
				$rep_name=$rep['agent_name'];
			}else{
				$rep_img='';$rep_name='';
			}
		
			 
			 if($from == $replied_from_db){
			 	$replied_from = $ticket_created_by;
			 					 
			 }
			 
			 $ccMails = str_replace("(","",$ccMails);
			 $ccMails = str_replace(")","",$ccMails);
			 $ccMails = str_replace('"',"",$ccMails);
			 // print_r($ccMails); exit;
			 //echo $replied_from; echo $from; exit;			 
			 /*$to = $ticket_to;			 
			 $to = str_replace("(","",$to);			 
			 $to = str_replace(")","",$to);
			 $to = str_replace('"',"",$to);
			 $to = explode(',',$to);*/			 
			 $replied_from_val = strstr($replied_from_db, '<');
			 if($replied_from_val==''){
			   $replied_from=$replied_from_db;
			 }else{
			   $replied_from = str_replace('< ', '',$replied_from_val); 
			   $replied_from = str_replace(' >', '',$replied_from);				 
			 }
			 $pos = array_search( $from, $to);
			
			
			 if(count($to) > 1){
				  $key = array_search($from, $to);
				if($key != '' || $key === 0){
				 	unset($to[array_search( $from, $to )]);
					 array_push($to, $replied_from);
				 }
				
				 
				$to = implode(',',$to);
			 	$ticket_to = $to; 
			 } else {
				 $to = str_replace("(","",$ticket_to);
				 $to = str_replace(")","",$to);
				 $to = str_replace('"',"",$to);
			     $ticket_to = $to;
			 }
			 
			
		  $ticket_message_id = $result[$i]['ticket_message_id']; 
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());
			 
			 
			 $ticket_notes_by =  $this->dataFetchAll("SELECT * FROM external_ticket_notes WHERE ticket_reply_id='$ticket_message_id'",array());
		  $ticket_assigned_dp="'" . str_replace(",", "','", $ticket_assigned_to) . "'";			 
          $assignedto_qry = "SELECT GROUP_CONCAT(agent_name) FROM user WHERE user_id IN ($ticket_assigned_dp)";    
          $assignedto = $this->fetchmydata($assignedto_qry,array());
          $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
          $department = $this->fetchmydata($deptment_qry,array());
          $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
          $ticketstatus = $this->fetchmydata($status_qry,array());
		  $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
          $priority_value = $this->fetchmydata($priority_qry,array());		  
		  $created_time = $this->get_timeago($ticket_created_at);
		  $created_time = $ticket_created_at;
			
			 
			$ticket_assigned_to = explode(',',$ticket_assigned_to); 
			 if(count($ticket_assigned_to) == 1){
			 $ticket_assigned_t = $ticket_assigned_to[0];
				 $ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";       
          		$ticket_assigned = $this->fetchData($ticket_assigned,array());
				$ticket_assigned_to=$ticket_assigned['agent_name'];
				$ticket_assigned_to_id=$ticket_assigned['user_id'];
				 
			 } else {
			 		$ticket_assigned_to = '';
				    $ticket_assigned_to_id = '';
			 }
			
	
		/*if($replied_by== 'Agent'){
			$ticket_to_qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$ticket_to%'";			
        	$ticket_to = $this->fetchOne($ticket_to_qry,array());
		} */
	
		


	      		
          $ticket_options = array('ticket_no' => $ticket_no,'is_spam'=>$is_spam,'ticket_media'=>$ticket_media, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $ticket_assigned_to,'ticket_assigned_to_id'=>$ticket_assigned_to_id,'department' => $department,'depart_id'=>$ticket_department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus,'ticket_status_id'=>$ticket_status,'ticket_message'=>$ticket_message,'ticket_notes'=>$ticket_notes_by,'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'ticket_from'=>$ticket_from,'ticket_to'=>$ticket_to,'replied_from'=>$rep_fm,'ticket_message_id'=>$ticket_message_id,'replied_by'=>$replied_by, 'own_mail' =>$from, 'own_img' =>$own_img, 'rep_img' =>$rep_img, 'rep_name' =>$rep_name, 'user_name'=>$user_name,'mail_cc'=>$ccMails, 'first_letter_r' => strtoupper($replied_from[0]),'ticket_closed_by'=>$tic_closed_by);
          $ticket_options_array[] = $ticket_options;
        }
	
	
		$first_res_time = "SELECT created_at FROM `external_tickets_data` WHERE repliesd_by = 'Agent' and ticket_id='$ticket_id' LIMIT 1";              
          $first_res_time = $this->fetchmydata($first_res_time,array());
		
          $closed_at = $this->fetchmydata("SELECT closed_at FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		   
          $ticket_closed_by = $this->fetchmydata("SELECT ticket_closed_by FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		if($ticket_closed_by != '0'){
			$ticket_closed_byqry = "SELECT agent_name FROM `user` WHERE `user_id` = '$ticket_closed_by'";
        	$tic_closed_by = $this->fetchOne($ticket_closed_byqry,array());
		  }
		
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());
		
		$status_array_qry = "SELECT status_id,status_desc FROM status";
		$status_options_array = $this->dataFetchAll($status_array_qry, array());
	
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());
	
		
		$agents_array_qry = "SELECT user_id,agent_name FROM user where admin_id=$admin_id";
		$agents_options_array = $this->dataFetchAll($agents_array_qry, array());
		
		$status = array('status' => 'true');
		$status_options_array = array('status_options' => $status_options_array);
		$department_options_array = array('departments' => $department_options_array);
		$priority_options_array = array('priority' => $priority_options_array);
		$agents_options_array = array('agents' => $agents_options_array);
		$ticket_options_array = array('tick_options' => $ticket_options_array);
		$side_menu = array('first_res_time' => $first_res_time, "closed_at"=>$closed_at, "ticket_closed_by"=>$tic_closed_by);
		$total_count = $this->dataRowCount($qry,array());
	    $total = array('total' => $total_count);
        $merge_result = array_merge($total,$status, $status_options_array, $ticket_options_array,$side_menu,$priority_options_array,$department_options_array,$agents_options_array);          
		 $tarray = json_encode($merge_result);         
		//print_r($ticket_options_array);exit;
        print_r($tarray);exit;
		return $tarray;
		
	}
	public function replyMessage($data){
		extract($data); 
		//print_r($data); exit;	
		$qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
		//echo $qry;exit;
       	$tic_details = $this->fetchData($qry,array());
		$subject = $tic_details['ticket_subject'];		
		$ticket_from = $tic_details['ticket_to'];
		$tic_from =  $tic_details['ticket_from'];
		
		if($tic_from == 'user'){
			$main_tick_from = $tic_details['ticket_email'];
		} else {
			$main_tick_from = str_replace('("','',$ticket_from);
			$main_tick_from = str_replace('")','',$main_tick_from);
		}
		
		
		//$main_tick_from = $this->fetchmydata("SELECT support_email FROM admin_details where admin_id=$user_id", array());
		
				//echo $main_tick_from; exit;
	
		//$qry = "SELECT support_email FROM admin_details WHERE support_email IN $ticket_from";
		//echo $qry;exit;
		//$from = $this->fetchOne($qry,array());
		//echo $from;exit;
		$qry = "select senderID from department_emails where emailAddr = '$ticket_from'";
        $from =  $this->fetchOne($qry, array());
		//echo $from;exit;
		$countfiles = count($_FILES['up_files']['name']);
		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
		$upload_location = $destination_path."ext-ticket-image/";
		$files_arr = array();
			//	print_r($countfiles); exit;

		for($index = 0;$index < $countfiles;$index++){
			$filename = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_FILENAME);
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			//$valid_ext = array("png","jpeg","jpg","pdf","txt","xlsx","jfif");
			// if(in_array($ext, $valid_ext)){}
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
				}
		}
	
		$files_array = $files_arr;
		
		//print_r($files_array); exit;

		$files_arr = implode(",",$files_arr);
		
		$mail_ccs = explode(",",$mail_cc);
		//print_r($mail_ccs); exit;
		
		//$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,ticket_media,repliesd_by) VALUES ( '$ticket_id','$message','$subject','$from','$to','$files_arr','Agent')", array()); 
		//$result = $qry_result == 1 ? 'mailed' : 'Error';
		
			//echo $message;exit;
		
	
		
			$qry = "SELECT sig_content FROM email_signatures WHERE sig_id='$signature_id'";
			$mailSignature = $this->fetchOne($qry,array());
		
			if($mailSignature){
				//$mailSignature = str_replace('</div>', '</div></body></html>', $mailSignature);
				$message =  $message.$mailSignature;
			} 
		
		$messages = $this->getTicketThread($ticket_id);
		
		foreach($messages as $m){
			$mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
		}
		
		$mess = implode('<br>',$mess);
		//print_r($messagetoSend); exit;
		$message =  '<div style="font-family: verdana !important;">'.$message.'</div>';
		$messagetoSend = $message.'<br> <br>'.$mess;
		//$message = $message[0]['ticket_message'];
		//$messagetoSend = '<p>tetettt</p>';
	//print_r($messagetoSend); exit;
		
		//$subject = $subject.' [##'.$ticket_id.']';
		
			//echo $subject; exit;
			if( strpos($to, ',') !== false ) { $tos = explode(',',$to); }
//echo $to; exit;
				
					$message_id = $this->fetchOne("SELECT ticket_reply_id FROM external_tickets_data WHERE ticket_id = '$ticket_id' and ticket_reply_id !=''",array());
		
//echo $to_id;exit;
		//print_r($tos);exit;
		
					if($tic_from == 'user'){
			            $from = $tic_details['ticket_email'];
		            }		            
		            $smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
				    $smtp_qry_value = $this->fetchData($smtp_qry,array());
				    $hostname = $smtp_qry_value['hostname'];
				    $port = $smtp_qry_value['port'];
				    $username = $smtp_qry_value['username'];
				    $password = $smtp_qry_value['password'];
                    require_once('class.phpmailer.php'); 
					$subject = $subject; 
                    $body = $messagetoSend;   
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true; 
                    $mail->SMTPSecure = 'tls';  
					//$mail->SMTPDebug  = 1;// enable SMTP authentication
                    $mail->Host = $hostname; // sets the SMTP server
                    $mail->Port = $port;                    // set the SMTP port for the GMAIL server
                    $mail->Username = $username; // SMTP account username
                    $mail->Password = $password;        // SMTP account password 
                    $mail->SetFrom($from);
                    $mail->Subject = $subject;
					$mail->MsgHTML($body);
					$mail->IsHTML(true);
					$mail->ClearReplyTos();
					$mail->AddReplyTo($from);
					$mail->addCustomHeader('In-Reply-To',  '<'.$message_id.'>');
					$mail->addCustomHeader('References', $message_id);
					if(count($files_array) > 0){
						foreach($files_array as $file){
							$name = basename($file);
                            $mail->addStringAttachment(file_get_contents($file), $name);
						}
					}
		
					if(count($tos) > 1){
						//print_r($tos);exit;
						foreach($tos as $contact){
							$mail->addAddress($contact);
						}
					} else {
							$mail->addAddress($to);
					}
		
					if(count($mail_ccs) > 1){
						foreach($mail_ccs as $contact){
							$mail->addCC($contact);
						}
					} else {
							$mail->addCC($mail_cc);
					}
				// print_r($mail); exit;
                   // $mail->send();
		if(!$mail->send()) {				
			print_r($mail->ErrorInfo);exit ;
				$res = "Mailer Error: " . $mail->ErrorInfo;
							
			} 
			 else {
				 
	
			$user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
        $user_qry_value = $this->fetchmydata($user_qry,array());
	    $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
        $user_timezone = $this->fetchmydata($user_timezone_qry,array());
		//echo $user_id;
        date_default_timezone_set($user_timezone);  
     	$created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");

			$qryss = "UPDATE `external_tickets` SET ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_id'";             
                $resultss = $this->db_query($qryss, $params);
				$message = str_replace("'","\n",$message);
				 $qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,created_dt,user_id) VALUES ( '$ticket_id','$message','$subject','$from','$to','$mail_cc','$files_arr','Agent','$created_at','$user_id')", array()); 
				// echo "INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,user_id) VALUES ( '$ticket_id','$message','$subject','$from','$to','$mail_cc','$files_arr','Agent','$user_id')";exit;
		$result = $qry_result == 1 ? 'mailed' : 'Error';
				  $dt = date('Y-m-d H:i:s');
				 $get=$this->fetchData("select user_name, IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$user_id'", array()); 
				$user_name= $get['user_name'];$admin_id= $get['admin_id'];
				 $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_id','Ticket Replied by $user_name ($subject)','11','7','$created_at')", array());
				 
				//$res = "Message has been sent successfully";
				$status = array('status' => 'true');     
		 $tarray = json_encode($status);           
        print_r($tarray);exit;
		return $tarray;
				
				 	}
		return $res;

		 
	}
	public function external_ticket_bulk_assign($data){
		extract($data);//print_r($data);exit;
		 $qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$agent_id' where ticket_no IN ($ticket_id)";
         $update_data = $this->db_query($qry, $params);
		$update_data = $update_data = 1 ? 1:0;
		return $update_data;
	}
public function createExternalTicket($data){
		extract($data);//file_put_contents('vaithee.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX); exit;
		//print_r($data); exit;
		//$from_address = 'nzassaabloycc@pipe.mconnectapps.com';
		$tos = explode(',',$to);
		$mail_ccs = explode(",",$mail_cc);
	
	    $qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";
		$from = $this->fetchOne($qry,array());
		$from = $from_address;
		$description = base64_decode($description);
		$html = $description;
		$dom = new DOMDocument();
		$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		$images = $dom->getElementsByTagName('img');
		foreach ($images as $image) {
        /*$src = $image->getAttribute('src');
        $type = pathinfo($src, PATHINFO_EXTENSION);
        $data = file_get_contents($src);
        $base64 = '0'; */
	
		$withoutSrc = $image->getAttribute('src');
			$img_Src = str_ireplace('src="', '', $withoutSrc);
		if (strpos($img_Src, ";base64,"))
            {
				$time = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
				$f = explode(';', $img_Src);	
				$img_type= str_replace("data:image/","",$f[0]);	
				$image_name = $time.'.'.$img_type;
				list($type, $img_Src) = explode(';', $img_Src);
				list(, $img_Src)      = explode(',', $img_Src);
				$img_Src = base64_decode($img_Src);
				$destination_path = getcwd().DIRECTORY_SEPARATOR;            
				$tempFolder = $destination_path."ext-ticket-image/".$image_name;
				if(file_put_contents($tempFolder, $img_Src)){
					$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$image_name;
				} else {
					$whatsapp_media_target_path = $whatsapp_media_target_path;
				}
				$image->setAttribute("src", $whatsapp_media_target_path); 
					//$whatsapp_media_target_path = $tempFolder.$image_name; 
				}

		} 
	
	
		$countfiles = count($_FILES['up_files']['name']); 
		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
		$upload_location = $destination_path."ext-ticket-image/";
		$files_arr = array();  
	
		for($index = 0; $index < $countfiles; $index++){
			$filename = $_FILES['up_files']['name'][$index];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
				}

		}

									  
		$files_array = $files_arr;
		$ticketMedia = implode(",",$files_arr);	
		$files_arr = implode(",",$files_arr);	


		$html = $dom->saveHTML();
	
		
		$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id'";
			$mailSignature = $this->fetchOne($qry,array());
			if($mailSignature){
				$mailSignature = str_replace('</div>', '</div></body></html>', $mailSignature);
				$html = str_replace('</body></html>', $mailSignature, $html);
			} 
	//$htm = $html.$mailSignature;
	//file_put_contents('vaithee.txt', $qry.PHP_EOL , FILE_APPEND | LOCK_EX);


				$qry = "SELECT ticket_no FROM external_tickets ORDER BY ticket_no DESC LIMIT 1";
				$oldTickNo = $this->fetchOne($qry,array());
				$oldTickNo = $oldTickNo + 1;	
				$subject = $subject.' [##'.$oldTickNo.']';
	            $smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
				$smtp_qry_value = $this->fetchData($smtp_qry,array());
				$hostname = $smtp_qry_value['hostname'];
				$port = $smtp_qry_value['port'];
				$username = $smtp_qry_value['username'];
				$password = $smtp_qry_value['password'];
                    require_once('class.phpmailer.php'); 
					$subject = $subject; 
                    $body = $html;   
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->SMTPAuth = true; 
                    $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                    $mail->Host = $hostname; // sets the SMTP server
                    $mail->Port = $port;                    // set the SMTP port for the GMAIL server
                    $mail->Username = $username; // SMTP account username
                    $mail->Password = $password;        // SMTP account password 
                    $mail->SetFrom($from);
                    $mail->Subject = $subject;
					$mail->MsgHTML($body);
					$mail->IsHTML(true);
					$mail->AddReplyTo($from);
	
					if(count($files_array) > 0){
						foreach($files_array as $file){
							$name = basename($file);
                            $mail->addStringAttachment(file_get_contents($file), $name);
						}
					}
					if(count($tos) > 1){
						foreach($tos as $contact){
							$mail->AddAddress($contact);
						}
					} else {
					$mail->AddAddress($to);
					}
	
			if(count($mail_ccs) > 1){
				foreach($mail_ccs as $contact){
					$mail->addCC($contact);
				}
			} else {
				$mail->addCC($mail_cc);
			}


            if(!$mail->send()) {
				
				$res = "Mailer Error: " . $mail->ErrorInfo;
			} 
			 else {
				 	
				if($agent_id == '' || $agent_id == undefined || empty($agent_id)){  
					$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department'",array());
					$dept_users = explode(',',$get_dep);
					$dept_users[] = $admin_id; 
					$agent_id = $dept_users;
					$agent_id = implode(',',$agent_id);
				} else {
					$dept_users = explode(',',$agent_id);
					$dept_users[] = $admin_id; 
				}
				 
				$agt_name=$this->fetchOne("select agent_name from user where user_id='$user_id'", array());
					
				 

				
				$res = "Message has been sent successfully";				 				
				$from=$this->fetchOne("select support_email from admin_details where admin_id='$admin_id'", array());
				
				$from ='("'.$from.'")'; 
				$ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_department,ticket_created_by,ticket_assigned_to,ticket_email) VALUES ( 'user','$admin_id','$status','$priority','$subject','$to','$department','$user_id','$agent_id','$from_address')", array());
	
				
				 
			$description = $html;
				 
				 
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,repliesd_by,ticket_media) VALUES ( '$ticket_no','$description','$subject','$from','$to','$mail_cc','Agent','$ticketMedia')", array());

				 foreach($dept_users as $user){
					 $tt = $agt_name.' Created New Ticket ('.$subject.')'; 
					 $this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$user','$admin_id','$ticket_no','$tt','11','7','$dt')", array());

					 $us = array("user_id"=>$user,"ticket_for"=>"Created Ticket","ticket_from"=>$tt,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					 $u[] = $this->send_notification($us);
				 } 


	}
	
	
        $status = array('status' => 'true');
		$response_array = array('data' => $res);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	
	//print_r($res);exit;
	
	}
	
	
	function getAlldetailsOfAgents($data){
		extract($data);
		/* if($user_type == 2){
			//$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id = $user_id ORDER BY a.ticket_no DESC";
			$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id ORDER BY ticket_no DESC";
		} else {
			$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to = $user_id OR a.ticket_created_by = $user_id ORDER BY a.ticket_no DESC";
		}		
		
		$result = $this->dataFetchAll($qry, array());
        for($i = 0; count($result) > $i; $i++){ 
          $ticket_no = $result[$i]['ticket_no'];	
          $ticket_created_by = $result[$i]['ticket_from'];
		  $ticket_from = $result[$i]['ticket_from'];
          $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
          $ticket_department = $result[$i]['ticket_department'];
          $ticket_status = $result[$i]['ticket_status'];
          $priority = $result[$i]['priority'];
		  $ticket_created_at = $result[$i]['created_at'];
          $ticket_message = $result[$i]['ticket_message'];
          $ticket_subject = $result[$i]['ticket_subject'];
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());
          $assignedto_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_assigned_to'";              
          $assignedto = $this->fetchmydata($assignedto_qry,array());
          $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
          $department = $this->fetchmydata($deptment_qry,array());
          $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
          $ticketstatus = $this->fetchmydata($status_qry,array());
		  $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
          $priority_value = $this->fetchmydata($priority_qry,array());		  
		  $created_time = $this->get_timeago($ticket_created_at);
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'ticket_from'=>$ticket_from);
          $ticket_options_array[] = $ticket_options;
        } */
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());
	
		$status_array_qry = "SELECT status_id,status_desc FROM status";
		$status_options_array = $this->dataFetchAll($status_array_qry, array());
	
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());
	
		
		$agents_array_qry = "SELECT user_id,agent_name FROM user where admin_id=$admin_id";
		$agents_options_array = $this->dataFetchAll($agents_array_qry, array());

		
        $status = array('status' => 'true');
		$department_options_array = array('department_options' => $department_options_array);
		$status_options_array = array('status_options' => $status_options_array);
		$priority_options_array = array('priority_options' => $priority_options_array);
		$agents_options_array = array('agents_options' => $agents_options_array);
        $merge_result = array_merge($status, $department_options_array, $status_options_array, $priority_options_array,$agents_options_array);          
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
    }
	
	
	public function addNotesForTicketReply($data){
		extract($data); 
	
 $update_data = $this->db_insert("INSERT INTO external_ticket_notes(ticket_reply_id,ticket_notes,created_by,created_name) VALUES ($ticket_message_id,'$ticket_notes',$user_id,'$user_name')", array()); 
		$update_data = $update_data = 1 ? 1:0;
		return $update_data;
		
		/*$qry = "SELECT ticket_reply_id FROM external_tickets_data where ticket_message_id = $ticket_message_id";
		$oldTickNo = $this->fetchOne($qry,array());

		$update_data = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_reply_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,user_id) VALUES ( '$ticket_id','$oldTickNo','','','','','','','Agent-notes','$user_id')", array()); 
		$update_data = $update_data = 1 ? 1:0;
		$dt = date('Y-m-d H:i:s');
		$get=$this->fetchData("select user_name, IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$user_id'", array()); 
		$user_name= $get['user_name'];$admin_id= $get['admin_id'];
		// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_id','Ticket Replied by $user_name ($subject)','11','7','$dt')", array());
		//$res = "Message has been sent successfully";
		$status = array('status' => 'true');     
		$tarray = json_encode($status);           
		return $tarray;*/
	
	}	
	
	public function updateTicketStatus($data){
		extract($data); 
		$ticket_id = base64_decode($ticket_id);
		$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id' where ticket_no = $ticket_id";
		//print_r($qry); exit;
		if($agent_id > 0){
			
			$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$agent_id'";              
      		$createdby = $this->fetchmydata($createdby_qry,array());

			//$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$user','$admin_id','$ticket_id',$user_name.' Assigned a ticket','11','7','$dt')", array());
			
			if($status == '6'){
				$sub = $user_name.' Assigned a ticket';
				$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$u = $this->send_notification($udm);
				$sub =$user_name.' Assigned a ticket to '.$createdby;
				$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$us = $this->send_notification($adm);
			} else if($status == '5'){
				
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($department)",array());
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				$dept_users = array_unique($dept_users);
				$dept_users = implode(',',$dept_users);
				$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$dept_users' where ticket_no = $ticket_id";
				$sub = $user_name.' Unassigned a ticket';
				$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$u = $this->send_notification($udm);
				$sub =$user_name.' Unassigned a ticket '.$ticket_id;
				$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$us = $this->send_notification($adm);
			}
			
			
			

			
			//$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$admin_id','$ticket_id',$user_name.' Assigned a ticket to '.$createdby,'11','7','$dt')", array());
			

		}


        $update_data = $this->db_query($qry, $params);
		$update_data = $update_data = 1 ? 1:0;
		return $update_data;
	
	}	
	
	
	public function createTicketSignature($data){
			extract($data); 
		
			$tos = explode(',',$to);
		
			$qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";
			$from = $this->fetchOne($qry,array());
			$description = base64_decode($description);
			$html = $description;
			$dom = new DOMDocument();
			$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
			$images = $dom->getElementsByTagName('img');
			foreach ($images as $image) {
			/*$src = $image->getAttribute('src');
			$type = pathinfo($src, PATHINFO_EXTENSION);
			$data = file_get_contents($src);
			$base64 = '0'; */
		
			$withoutSrc = $image->getAttribute('src');
				$img_Src = str_ireplace('src="', '', $withoutSrc);
			if (strpos($img_Src, ";base64,"))
				{
					$time = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
					$f = explode(';', $img_Src);	
					$img_type= str_replace("data:image/","",$f[0]);	
					$image_name = $time.'.'.$img_type;
					list($type, $img_Src) = explode(';', $img_Src);
					list(, $img_Src)      = explode(',', $img_Src);
					$img_Src = base64_decode($img_Src);
					$destination_path = getcwd().DIRECTORY_SEPARATOR;            
					$tempFolder = $destination_path."ext-ticket-image/".$image_name;
					if(file_put_contents($tempFolder, $img_Src)){
						$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$image_name;
					} else {
						$whatsapp_media_target_path = $whatsapp_media_target_path;
					}
					$image->setAttribute("src", $whatsapp_media_target_path); 
						//$whatsapp_media_target_path = $tempFolder.$image_name; 
					}
	
			} 
		
		
		$countfiles = count($_FILES['up_files']['name']);
			$destination_path = getcwd().DIRECTORY_SEPARATOR;            
			$upload_location = $destination_path."ext-ticket-image/";
			$files_arr = array();
			for($index = 0;$index < $countfiles;$index++){
				$filename = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_FILENAME);
				$rand = rand(0000,9999).time();
				$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
				$filename = $filename.$rand.'.'.$ext;		   
				$path = $upload_location.$filename;
					if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
						$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
					}
			}
			$files_array = $files_arr;
			$files_arr = implode(",",$files_arr);	
			$html = $dom->saveHTML();
		$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $html);
		$html = str_replace('<html><body>', '<div id="email-signature"></br></br>', $html);
		$html = str_replace('</body></html>', '</div>', $html);
	if($is_default=='1'){
		$update_data11 = $this->db_query("UPDATE email_signatures SET is_default='0'",  array());
	}
	//	echo "INSERT INTO `email_signatures` ( `sig_title`, `sig_content`, `is_default`) VALUES ( '$sig_title','$sig_content','$is_default')";exit;
		$qry_result = $this->db_insert("INSERT INTO `email_signatures` ( `sig_title`, `sig_content`, `is_default`, `admin_id`,`user_id`) VALUES ( '$sig_title','$sig_content','$is_default','$admin_id','$user_id')", array());
		$update_data = $qry_result = 1 ? 1:0;
		     $status = array('status' => 'true');
        $ticket_options_array = array('data' => $update_data);
		 $merge_result = array_merge($status, $ticket_options_array); 		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	}
function viewTicketSignature($data){
	extract($data);
	
	if($admin_id == $user_id){
		$qry = "select * from email_signatures where admin_id='$admin_id' and user_id='$admin_id' order by sig_id desc";
		$admin_signatures = $this->dataFetchAll($qry,array());
		$result = $admin_signatures;
	} else {
		$qry = "select * from email_signatures where admin_id='$admin_id' and user_id='$admin_id' order by sig_id desc";
		$admin_signatures = $this->dataFetchAll($qry,array());
		$qry = "select * from email_signatures where admin_id='$admin_id' and user_id='$user_id' order by sig_id desc";
		$user_signatures = $this->dataFetchAll($qry,array());
		$result = array_merge($admin_signatures, $user_signatures); 
	}
	 
	// $admin_signatures = array('admin_sig' => $admin_signatures,'user_sig' =>$user_signatures);
	return $result;
}
function editTicketSignature($data){
	extract($data);
	$qry = "select * from email_signatures where sig_id='$sig_id' ";
	$result = $this->dataFetchAll($qry,array());
	return $result;
}
public function updateTicketSignature($data){
			extract($data); 
		
			$tos = explode(',',$to);
		
			$qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";
			$from = $this->fetchOne($qry,array());
			$description = base64_decode($description);
			$html = $description;
			$dom = new DOMDocument();
			$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
			$images = $dom->getElementsByTagName('img');
			foreach ($images as $image) {
			/*$src = $image->getAttribute('src');
			$type = pathinfo($src, PATHINFO_EXTENSION);
			$data = file_get_contents($src);
			$base64 = '0'; */
		
			$withoutSrc = $image->getAttribute('src');
				$img_Src = str_ireplace('src="', '', $withoutSrc);
			if (strpos($img_Src, ";base64,"))
				{
					$time = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
					$f = explode(';', $img_Src);	
					$img_type= str_replace("data:image/","",$f[0]);	
					$image_name = $time.'.'.$img_type;
					list($type, $img_Src) = explode(';', $img_Src);
					list(, $img_Src)      = explode(',', $img_Src);
					$img_Src = base64_decode($img_Src);
					$destination_path = getcwd().DIRECTORY_SEPARATOR;            
					$tempFolder = $destination_path."ext-ticket-image/".$image_name;
					if(file_put_contents($tempFolder, $img_Src)){
						$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$image_name;
					} else {
						$whatsapp_media_target_path = $whatsapp_media_target_path;
					}
					$image->setAttribute("src", $whatsapp_media_target_path); 
						//$whatsapp_media_target_path = $tempFolder.$image_name; 
					}
	
			} 
		
		
		$countfiles = count($_FILES['up_files']['name']);
			$destination_path = getcwd().DIRECTORY_SEPARATOR;            
			$upload_location = $destination_path."ext-ticket-image/";
			$files_arr = array();
			for($index = 0;$index < $countfiles;$index++){
				$filename = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_FILENAME);
				$rand = rand(0000,9999).time();
				$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
				$filename = $filename.$rand.'.'.$ext;		   
				$path = $upload_location.$filename;
					if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
						$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
					}
			}
			$files_array = $files_arr;
			$files_arr = implode(",",$files_arr);	
			$html = $dom->saveHTML();
		$html = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">', '', $html);
		$html = str_replace('<html><body>', '<div id="email-signature"></br></br>', $html);
		$html = str_replace('</body></html>', '</div>', $html);
	if($is_default=='1'){
		$update_data = $this->db_query("UPDATE email_signatures SET is_default='0'",  array());
	}		
	$qry_result =$this->db_query("UPDATE email_signatures SET sig_title='$sig_title',sig_content='$sig_content',is_default='$is_default' where sig_id='$sig_id'",  array());		
		$update_data = $qry_result = 1 ? 1:0;
		  $status = array('status' => 'true');
        $ticket_options_array = array('data' => $update_data);
		 $merge_result = array_merge($status, $ticket_options_array); 		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	}	
public function makeSignatureDefault($data){
	extract($data);
	//print_r($data); exit;
	$update_data = $this->db_query("UPDATE email_signatures SET is_default='0' where admin_id='$admin_id' and user_id='$user_id'",  array());
	$qry_result = $this->db_query("UPDATE email_signatures SET is_default='$is_default' where sig_id='$signature_id' and admin_id='$admin_id' and user_id='$user_id'",  array());
	$update_data = $qry_result = 1 ? 1:0;
	return $update_data;
}

	public function deleteSignature($data){
	extract($data);
	//print_r($data); exit;
	$qry_result = $this->db_query("DELETE from email_signatures where sig_id='$signature_id'",  array());
	//$qry_result = $this->db_query("UPDATE email_signatures SET is_default='$is_default' where sig_id='$signature_id' and admin_id='$admin_id' and user_id='$user_id'",  array());
	$update_data = $qry_result = 1 ? 1:0;
	return $update_data;
}
function getmyDepartmentTicket($data){
    extract($data);
	
  

    if($user_type == 2){
        //$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id = $user_id ORDER BY a.ticket_no DESC";
    
        if($ticket_department == 'All'){
                $qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id ";
             $detail_qry = $qry."  ORDER BY ticket_no DESC LIMIT $limit offset $offset";
        } else {
                $qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_department = $ticket_department ";
             $detail_qry = $qry."ORDER BY ticket_no DESC LIMIT $limit offset $offset";
        }
        
        
        
    } else {
        if($ticket_status == 'All'){
            $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) OR a.ticket_created_by = $user_id ";
             $detail_qry = $qry." Group by a.ticket_no ORDER BY a.ticket_no DESC LIMIT $limit offset $offset";
        } else {
            $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_department = '$ticket_department' OR a.ticket_created_by = $user_id ";
             $detail_qry = $qry." Group by a.ticket_no ORDER BY a.ticket_no DESC LIMIT $limit offset $offset";
        }
    }		


    //echo $detail_qry;exit;
    $result = $this->dataFetchAll($detail_qry, array());
    

    for($i = 0; count($result) > $i; $i++){ 
      $ticket_no = $result[$i]['ticket_no'];	
        $ticket_new_status = $result[$i]['status'];	
      $ticket_created_by = $result[$i]['ticket_from'];
      $ticket_from = $result[$i]['ticket_from'];
      $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
      $ticket_department = $result[$i]['ticket_department'];
      $ticket_status = $result[$i]['ticket_status'];
      $priority = $result[$i]['priority'];
      $ticket_created_at = $result[$i]['created_dt'];
      $ticket_message = $result[$i]['ticket_message'];
      $ticket_subject = $result[$i]['ticket_subject'];
      $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
      $createdby = $this->fetchmydata($createdby_qry,array());
      $assignedto_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_assigned_to'";              
      $assignedto = $this->fetchmydata($assignedto_qry,array());
      $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
      $department = $this->fetchmydata($deptment_qry,array());
      $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
      $ticketstatus = $this->fetchmydata($status_qry,array());
      $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
      $priority_value = $this->fetchmydata($priority_qry,array());		
        
        $time_ago = strtotime($ticket_created_at); 
        
          $created_time = $this->time_Ago($time_ago);
        
        //$ticket_created_at = Carbon::parse($ticket_created_at);
        //$created_time =  $ticket_created_at->diffForHumans();
        
        $created_time = date("Y-m-d H:i:s", $time_ago);
        
        //$created_time = $this->get_timeago($created_time2);
        
        
        //print_r($ticket_created_at); exit;
        
      $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status);
      $ticket_options_array[] = $ticket_options;
    }
    $department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0'";
    $department_options_array = $this->dataFetchAll($department_array_qry, array());
    
    $status_array_qry = "SELECT status_id,status_desc FROM status";
    $status_options_array = $this->dataFetchAll($status_array_qry, array());
    
    $priority_array_qry = "SELECT id,priority FROM priority";
    $priority_options_array = $this->dataFetchAll($priority_array_qry, array());

    $status = array('status' => 'true');
    $ticket_options_array = array('ticket_options' => $ticket_options_array);
    $department_options_array = array('department_options' => $department_options_array);
    $status_options_array = array('status_options' => $status_options_array);
    $priority_options_array = array('priority_options' => $priority_options_array);
    $total_count = $this->dataRowCount($qry,array());
    $total = array('total' => $total_count);
        
    $merge_result = array_merge($status, $department_options_array, $status_options_array, $priority_options_array, $ticket_options_array,$total); 
    
    $tarray = json_encode($merge_result);           
    print_r($tarray);exit;
}

public function getTicketThread($ticket_id){
    extract($data); 
    //$ticket_id = base64_decode($ticket_id);
        


     $qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
       $tic_details = $this->fetchData($qry,array());
    
    $qryss = "UPDATE `external_tickets` SET status = '0' WHERE ticket_no = '$ticket_id'";             
            $resultss = $this->db_query($qryss, $params);
    
    
    $ticket_from = $tic_details['ticket_to'];
    $ticket_user = $tic_details['ticket_from'];
    $ticket_created_by = $tic_details['ticket_created_by'];
    $admin_id = $tic_details['admin_id'];
	



    if($ticket_user=='user'){
      $qry = "SELECT support_email,admin_id FROM admin_details WHERE admin_id='$ticket_created_by'";		
      $get_det=$this->fetchData("SELECT agent_name,profile_image FROM user where user_id='$ticket_created_by'",array());
      $user_name=$get_det['agent_name'];
      $own_img=$get_det['profile_image'];
    }else{
     $qry = "SELECT support_email,admin_id  FROM admin_details WHERE support_email IN $ticket_from";
     $user_name='';
    }
    $res_val= $this->fetchData($qry,array());
	

    $from=$res_val['support_email'];
    $admin_id=$res_val['admin_id'];
    if($user_name==''){
    $own_img= $this->fetchOne("SELECT profile_image FROM user where user_id='$admin_id'",array());
    }else{
        $own_img=$own_img;
    }

    $to = str_replace("(","",$to);
    $to = str_replace(")","",$to);
    $to = str_replace('"',"",$to);
    $to = explode(',',$to);	
    unset($to[array_search( $from, $to )]);
    $destination_path = getcwd().DIRECTORY_SEPARATOR;            
            $upload_location = $destination_path."ext-ticket-image/";
    $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
    $detail_qry=$qry." ORDER BY b.ticket_message_id DESC";
    $result =  $this->dataFetchAll($detail_qry, array());

     for($i = 0; count($result) > $i; $i++){ 
      $ticket_no = $result[$i]['ticket_no'];	
      $ticket_created_by = $result[$i]['ticket_from'];
      $ticket_from = $result[$i]['ticket_from'];
      $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
      $ticket_department = $result[$i]['ticket_department'];
      $ticket_status = $result[$i]['ticket_status'];
      $priority = $result[$i]['priority'];
      $ticket_notes =  $result[$i]['ticket_notes'];
      $ticket_created_at = $result[$i]['created_at'];
      $ticket_messages = $result[$i]['ticket_message'];
      $ticket_medias = $result[$i]['ticket_media'];
      $ticket_media = explode(',', $ticket_medias );
      $ticket_media = $ticket_media;
      $ticket_subject = $result[$i]['ticket_subject'];
      $replied_from = $result[$i]['replied_from'];
      $replied_by = $result[$i]['repliesd_by'];
      $ticket_to = $result[$i]['replied_to'];
      $ticket_user = $result[$i]['user_id'];
		 
		if($ticket_user!='') {
				$rep= $this->fetchData("SELECT profile_image,user_name,agent_name FROM user where user_id='$ticket_user'",array());
				$rep_img=$rep['profile_image'];
				$rep_name=$rep['agent_name'];
			}else{
				$rep_img='';
				$rep_name='';
			}
		
			 
			 if($from == $replied_from){
			 	$replied_from = $ticket_created_by;
			 }
			  
			 //echo $replied_from; echo $from; exit;
			 $to = $ticket_to;
			 $to = str_replace("(","",$to);
			 $to = str_replace(")","",$to);
			 $to = str_replace('"',"",$to);
			 $to = explode(',',$to);
			 $replied_from = strstr($replied_from, '<');
			 $replied_from = str_replace('< ', '',$replied_from); $replied_from = str_replace(' >', '',$replied_from);
			
			 $pos = array_search( $from, $to);
			
			 
			
			 if(count($to) > 1){
				  $key = array_search($from, $to);
				if($key != '' || $key === 0){
				 	unset($to[array_search( $from, $to )]);
					 array_push($to, $replied_from);
				 }
				
				 
				$to = implode(',',$to);
			 	$ticket_to = $to; 
			 } else {
				 $to = str_replace("(","",$ticket_to);
				 $to = str_replace(")","",$to);
				 $to = str_replace('"',"",$to);
			     $ticket_to = $to;
			 }
			// print_r( $ticket_to); exit;
		  $ticket_message_id = $result[$i]['ticket_message_id']; 
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());
		  $ticket_assigned_dp="'" . str_replace(",", "','", $ticket_assigned_to) . "'";			 
          $assignedto_qry = "SELECT GROUP_CONCAT(agent_name) FROM user WHERE user_id IN ($ticket_assigned_dp)";    
		// echo $assignedto_qry;exit;
          $assignedto = $this->fetchmydata($assignedto_qry,array());
          $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
          $department = $this->fetchmydata($deptment_qry,array());
          $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
          $ticketstatus = $this->fetchmydata($status_qry,array());
		  $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
          $priority_value = $this->fetchmydata($priority_qry,array());		  
		  $created_time = $this->get_timeago($ticket_created_at);
			 
			 
			$ticket_assigned_to = explode(',',$ticket_assigned_to); 
			 if(count($ticket_assigned_to) == 1){
			 $ticket_assigned_t = $ticket_assigned_to[0];
				 $ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";       
          		$ticket_assigned = $this->fetchData($ticket_assigned,array());
				$ticket_assigned_to=$ticket_assigned['agent_name'];
				$ticket_assigned_to_id=$ticket_assigned['user_id'];
				 
			 } else {
			 		$ticket_assigned_to = '';
				 $ticket_assigned_to_id = '';
			 }
			

	
         

	$mailer = strtoupper($replied_from[0]);



    if($replied_by!='Agent') {
         $ticket_message = '<h1 style="font-size: 20px;font-family: verdana !important; text-align: right; background: #00a65a; color: #fff; padding: 10px;margin-top: 0; border-radius: 8px 8px 0 0;"> '.$replied_from.'</h1>'.$ticket_messages;
	} else {
		
		if($rep_img ==''){ $rep_img='https://assaabloycc.mconnectapps.com/assets/images/user.jpg';}
		 $ticket_message = '<p style="font-size: 20px;background: #808080;font-family: verdana !important; color: #000000f7 !important; font-weight: 700;padding: 10px; margin-top: 0; border-radius: 8px 8px 0 0;">'.$rep_name.'</p>'.$ticket_messages;
		

	}
         

      $ticket_options_array[] = $ticket_message;
    }

  
    return $ticket_options_array;
    
}

	 function send_notification($data){
	
	extract($data);


		
	$host_name='https://omni-ticketing-xcupb.ondigitalocean.app';

    $title = $text;
	$click_url = $host_name.'/#/ticketing-system-new';
		 

	if($ticket_for == 'New Ticket'){
		$title = 'New Email Ticket';
		$title = 'New Ticket Has Been Created By '.$ticket_from;
	}else {
		$title = 'Reply Email Ticket';
		$title = $ticket_from.' Has Replied the Ticket '.$ticket_subject;
	}		 

		 
 	if($ticket_for == 'Reply Ticket'){
		$title = 'Reply Email Ticket';
		$title = $ticket_from.' Has Replied the Ticket '.$ticket_subject;
	} 
		 
	if($ticket_for == 'Assign Ticket' || $ticket_for == 'Close Ticket'){
		$title = $ticket_subject;
	} 

	if($ticket_for == 'Created Ticket'){
		$title = $ticket_from;
	} 		 



$socket = "https://myscoket.mconnectapps.com:4031";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    $client->emit('broadcast', ['title' =>$title, 'notification_for'=>'email_ticketing', 'click_action'=>$click_url, 'unique_id'=>$ticket_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name,'user_id'=>$user_id]);
		
	}
 function addUpdateDeptToEmail($data){
	extract($data); 
	$qry = "select * from department_emails where emailAddr LIKE '%$email%'";
    $result =  $this->fetchData($qry, array());	 
	if($result > 0){
		 $id = $result['id'];
	 	 $qry = "UPDATE department_emails SET departments='$departments',aliseEmail='$aliseEmail',senderID='$senderID',status=1 where id='$id'";
		 $update_data = $this->db_query($qry, $params);
	 }else {
	 	$qry = "INSERT INTO department_emails(departments,emailAddr,aliseEmail,senderID,status) VALUES ( '$departments','$email','$aliseEmail','$senderID',1)";       
        $insert_data = $this->db_query($qry, $params);
	 }
     $qry = "select * from department_emails where status=1";
     $result =  $this->db_query($qry, array());
     return $result;
    }
    function getAllEmailDept($admin_id){
        //$qry = "select * from department_emails";
		$qry = "select dep_email.*,dep.department_name from department_emails as dep_email LEFT JOIN departments as dep ON dep.dept_id=dep_email.departments WHERE dep_email.status=1";
        $result =  $this->dataFetchAll($qry, array());
		
		$override_qry_val = $this->fetchOne("SELECT override FROM `admin_details` WHERE `admin_id`='$admin_id'",array());
		
		$qry = "SELECT support_email FROM `admin_details` WHERE admin_id='$admin_id'";
        $qry_value = $this->fetchData($qry,array());
		$qry_value = implode(',',$qry_value);
		$qry_value = explode(',',$qry_value);
		
		$results['roundrobin'] = $override_qry_val;
		$results['adminEmail'] = $qry_value;
		$results['alldata'] = $result;
        return $results;
    }
	function getEmailDept($email){
        $qry = "select * from department_emails where emailAddr LIKE '%$email%'";
        $result =  $this->fetchData($qry, array());
		$id = $result['id'];
		$deprtments = $result['departments'];
		$emailAddr = $result['emailAddr'];
		$aliseEmail = $result['aliseEmail'];
		$senderID = $result['senderID'];
		$status = $result['status'];
		$value1 = $result['emailAddr'];
		$value2 = $result['aliseEmail'];
		$value3 = $result['senderID'];
		$alldata = array("id"=>$id,"deprtments"=>$deprtments,"aliseEmail"=>$aliseEmail,"senderID"=>$senderID,"status"=>$status,"emailAddr"=>$emailAddr);
		$alldata_array = array("data"=>$alldata);
		$values = array("options"=>$value1.','.$value2.','.$value3); 
		$options_array = array("values"=>$values);
		$status = array("status"=>"true");
		$merge_arr = array_merge($status,$alldata_array,$options_array);
		$tarray = json_encode($merge_arr,true);
		print_r($tarray);exit;
		//$results['data'] = $alldata;
		//$results['options'] = $options;
        //return $results;
    }
	function delEmailDept($id){//echo $id;exit;
        /*$qry = "DELETE FROM department_emails where emailAddr LIKE '%$email%'";
        $result =  $this->db_query($qry, array());
        return $result;*/
		$qry = "UPDATE department_emails SET status=0 where id='$id'";	
        $result =  $this->db_query($qry, array());
        $output = $result == 1 ? 1 : 0;
        return $output;
    }
	
function autoResponseEmail($data){
	extract($data);
	//print_r($data); exit;
	$smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
    $smtp_qry_value = $this->fetchData($smtp_qry,array());
    $hostname = $smtp_qry_value['hostname'];
    $port = $smtp_qry_value['port'];
    $username = $smtp_qry_value['username'];
    $password = $smtp_qry_value['password'];
    require_once('class.phpmailer.php'); 
    $subject = $subject; 
    $body = $message;   
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
    $mail->Host = $hostname; // sets the SMTP server
    $mail->Port = $port;                    // set the SMTP port for the GMAIL server
    $mail->Username = $username; // SMTP account username
    $mail->Password = $password;        // SMTP account password 
    $mail->SetFrom($from);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->IsHTML(true);
    $mail->AddReplyTo($from);
	if($message_id){
		$mail->addCustomHeader('In-Reply-To',  '<'.$message_id.'>');
    	$mail->addCustomHeader('References', $message_id);
	}
	$tos = explode(",",$ticket_to);
	if(count($tos) > 1){
		foreach($tos as $contact){
			$mail->AddAddress($contact);
		}
	} else {
		$mail->AddAddress($ticket_to);
	}
	$mail_ccs = explode(",",$ticket_cc);
	if(count($mail_ccs) > 1){
		foreach($mail_ccs as $contact){
			$mail->addCC($contact);
		}
	} else {
		$mail->addCC($ticket_cc);
	}
    if(count($ticket_bcc) > 1){
		foreach($ticket_bcc as $contact){
			$mail->addBCC($contact);
		}
	} else {
		$mail->addBCC($ticket_bcc);
	} 
if(!$mail->send()) {
$res = "Mailer Error: " . $mail->ErrorInfo;
} else {
$res= 'done'; 
} 
	return $res;
}
 /*function addUpdateDeptToEmailResponse($data){
	 extract($data); 
	$qry = "select * from email_autoresponses where response_for LIKE '%$response_for%'";
    $result =  $this->fetchData($qry, array());
	 
	 if($result > 0){
		 $id = $result['res_id'];
	 	 $qry = "UPDATE email_autoresponses SET response_content='$content',status='$status' where res_id='$id'";
		 $update_data = $this->db_query($qry, $params);
	 } else {
	 	$qry = "INSERT INTO email_autoresponses(response_content, response_for, admin_id,status) VALUES ( '$content','$response_for','$admin_id','$status')";       
        $insert_data = $this->db_query($qry, $params);
	 }
        $qry = "select * from email_autoresponses";
        $result =  $this->db_query($qry, array());
        return $result;
    }*/
	/*function getEmaiautoResponses(){
        $qry = "select * from email_autoresponses";
        $result =  $this->dataFetchAll($qry, array());
        return $result;
    }*/
	function addUpdateDeptToEmailResponse($data){
	 extract($data); 
	$qry = "select * from email_autoresponses where response_for LIKE '%$response_for%' AND dept_id='$dept_id'";
    $result =  $this->fetchData($qry, array());
	//print_r($qry);exit; 
	 if($result > 0){
		 $id = $result['res_id'];
	 	 $qry = "UPDATE email_autoresponses SET response_content='$content',status='$status' where res_id='$id'";
		 $update_data = $this->db_query($qry, $params);
	 } else {
	 	$qry = "INSERT INTO email_autoresponses(response_content, response_for, admin_id, status, dept_id) VALUES ( '$content','$response_for','$admin_id','$status', '$dept_id')";       
        $insert_data = $this->db_query($qry, $params);
	 }
        $qry = "SELECT * FROM email_autoresponses WHERE admin_id='$admin_id' AND dept_id='$dept_id'";
        $result =  $this->fetchData($qry, array());
        return $result;
    }
	function getEmaiautoResponses($data){
		extract($data);
        $qry = "SELECT * FROM email_autoresponses WHERE admin_id='$admin_id' AND dept_id='$dept_id'";
        $result =  $this->dataFetchAll($qry, array());
		if($result == []){
		return	$result = 'empty';
		}else{
	    return $result;	
		}
    }
	 function send_notificationTEST($data){
	
		extract($data);

			$agentqry = "SELECT notification_code FROM `user` WHERE `user_id` ='$user_id' AND `notification_code`!=''";
			$agentresult = $this->fetchData($agentqry, array());	
			$token = $agentresult['notification_code'];
		
	$host_name='https://assaabloyccuat.mconnectapps.com';

    $title = $text;
	$click_url = $host_name.'/#/ticketing-system-new';
		 

	if($ticket_for == 'New Ticket'){
		$title = 'New Email Ticket';
		$title = 'New Ticket Has Been Created By '.$ticket_from;
	}else {
		$title = 'Reply Email Ticket';
		$title = $ticket_from.' Has Replied the Ticket '.$ticket_subject;
	}		 

		 
 	if($ticket_for == 'Reply Ticket'){
		$title = $ticket_subject;
	} 
		 
	if($ticket_for == 'Assign Ticket'){
		$title = $ticket_subject;
	} 

	if($ticket_for == 'Created Ticket'){
		$title = $from;
	} 		 



    $notification = array('title' =>$title, 'text' => $text, 'notification_for'=>'email_ticketing', 'click_action'=>$click_url, 'unique_id'=>$ticket_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name);

    $json = json_encode($notification);

$socket = "https://myscoket.mconnectapps.com:4027";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    $client->emit('broadcast', ['title' =>$title, 'text' => $text, 'notification_for'=>'email_ticketing', 'click_action'=>$click_url, 'unique_id'=>$ticket_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name]);
		
	}
	
	public function claimMyTicket($data){
		extract($data); 
		$ticket_id = base64_decode($ticket_id);
		$qry = "UPDATE external_tickets SET ticket_status='6',ticket_assigned_to='$user_id' where ticket_no = $ticket_id";
		$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$user_id'";              
      	$createdby = $this->fetchmydata($createdby_qry,array());
		$sub = $user_name.' cliamed a ticket '.$ticket_id;
		$udm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
		$u = $this->send_notification($udm);
        $update_data = $this->db_query($qry, $params);
		$update_data = $update_data = 1 ? 1:0;
		return $update_data;
	}	
	public function getIncomingEmailIds($data){       
       $qry = "SELECT ticket_from FROM `external_tickets` GROUP BY ticket_from ORDER BY `ticket_no` DESC";
       $result = $this->dataFetchAll($qry,array());
       return $result;
    }
	public function blockEmailIds($data){       
       extract($data); 
	$qry = "select * from spam_mail_ids where email LIKE '%$email_id%'";
    $result =  $this->fetchData($qry, array());	 
		
	 if($result > 0){
		 $id = $result['id'];
	 	 $qry = "UPDATE spam_mail_ids SET spam_status='$spam_status',blacklist_status='$blacklist_status',user_id='$user_id' where id='$id'";
		 $update_data = $this->db_query($qry, $params);
	 } else {
	 	$qry = "INSERT INTO spam_mail_ids(email,spam_status,blacklist_status,admin_id,user_id) VALUES ( '$email_id','$spam_status','$blacklist_status','$admin_id','$user_id')";  
        $insert_data = $this->db_query($qry, $params);
	 }
$nstatus = 0;		
if($spam_status == 0){
	$nstatus = 1;
}
$qry = "UPDATE external_tickets SET is_spam='$spam_status',status_del='$nstatus',spammed='$nstatus' where ticket_from LIKE '%$email_id%'";		
$qry = "UPDATE external_tickets SET is_spam='$spam_status',status_del='$nstatus' where ticket_from LIKE '%$email_id%'";		

		$update_data = $this->db_query($qry, $params);

        $qry = "select * from spam_mail_ids";
        $result =  $this->dataFetchAll($qry, array());
        return $result;
    }
	function delSpamEmail($email){
        $qry = "DELETE FROM spam_mail_ids where email LIKE '%$email%'";
        $result =  $this->db_query($qry, array());
        return $result;
    }
	function spamLists($email){
        $qry = "select * from spam_mail_ids";
        $result =  $this->dataFetchAll($qry, array());
        return $result;
    }
	

	function searchTicketId($data){
		extract($data);
	
		if($user_type == 2){
			$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_subject LIKE '%$ticket_search%' and is_spam = '$is_spam' or ticket_from LIKE '%$ticket_search%'";
			$detail_qry = $qry."  ORDER BY ticket_no DESC LIMIT $limit offset $offset";
			
		} else {
			$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and is_spam = '$is_spam' and a.ticket_subject LIKE '%$ticket_search%' OR a.ticket_created_by = $user_id and is_spam = '$is_spam' and a.ticket_subject LIKE '%$ticket_search%' or a.ticket_from LIKE '%$ticket_search%'";
			$detail_qry = $qry." Group by a.ticket_no ORDER BY a.ticket_no DESC LIMIT $limit offset $offset";
		}		

		$result = $this->dataFetchAll($detail_qry, array());

	
        for($i = 0; count($result) > $i; $i++){ 
          $ticket_no = $result[$i]['ticket_no'];	
			$ticket_new_status = $result[$i]['status_del'];	
          $ticket_created_by = $result[$i]['ticket_from'];
		  $ticket_from = $result[$i]['ticket_from'];
          $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
          $ticket_department = $result[$i]['ticket_department'];
          $ticket_status = $result[$i]['ticket_status'];
		  $closed_at = $result[$i]['closed_at'];
          $priority = $result[$i]['priority'];
		  $ticket_created_at = $result[$i]['created_dt'];
          $ticket_message = $result[$i]['ticket_message'];
          $ticket_subject = $result[$i]['ticket_subject'];
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());
			
		  //$assignedto_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_assigned_to'";              
         // $assignedto = $this->fetchmydata($assignedto_qry,array());
			
			$ticket_assigned_to = explode(',',$ticket_assigned_to); 
			 if(count($ticket_assigned_to) == 1){
			 $ticket_assigned_t = $ticket_assigned_to[0];
				 $ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";       
          		$ticket_assigned = $this->fetchData($ticket_assigned,array());
				$assignedto=$ticket_assigned['agent_name'];
				//$ticket_assigned_to_id=$ticket_assigned['user_id'];
				 
			 } else {
			 		$assignedto = '';
				   // $ticket_assigned_to_id = '';
			 }
			
			
          
          $deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";       
          $department = $this->fetchmydata($deptment_qry,array());
          $status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
          $ticketstatus = $this->fetchmydata($status_qry,array());
		  $priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
          $priority_value = $this->fetchmydata($priority_qry,array());		
			
			$time_ago = strtotime($ticket_created_at); 
			
		
		 	 $created_time = $this->time_Ago($time_ago);
			
			//$ticket_created_at = Carbon::parse($ticket_created_at);
			//$created_time =  $ticket_created_at->diffForHumans();
			
			$created_time = date("Y-m-d H:i:s", $time_ago);
			
			//$created_time = $this->get_timeago($created_time2);
			
			
			//print_r($ticket_created_at); exit;
			
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at);
          $ticket_options_array[] = $ticket_options;
        }
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());
			
			//print_r($department_array_qry); print_r($department_options_array); exit;
		//$status_options_array = $this->dataFetchAll($status_array_qry, array());
		$status_options_array = $status_array_qry;
		
		$list_status_array_qry = "SELECT status_id,status_desc FROM status";
		$list_status_options_array = $this->dataFetchAll($list_status_array_qry, array());
		
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());
	
        $status = array('status' => 'true');
        $ticket_options_array = array('ticket_options' => $ticket_options_array);
		$department_options_array = array('department_options' => $department_options_array);
		$count_status_options_array = array('count_options' => $status_options_array);
		$status_options_array = array('status_options' => $list_status_options_array);
		$priority_options_array = array('priority_options' => $priority_options_array);
		$total_count = $this->dataRowCount($qry,array());
	    $total = array('total' => $total_count);
            
        $merge_result = array_merge($status, $department_options_array, $status_options_array,$count_status_options_array, $priority_options_array, $ticket_options_array,$total); 
		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
    }

	function agentAlertSettingsList($data){
		extract($data);
		
$qry = "select * from departments where dept_id ='$dept_id'";
			$res= $this->fetchData($qry, array("dept_id"=>$dept_id));
			 $dep_user=$res['department_users'];
			$dep_user="'" . str_replace(",", "','", $dep_user) . "'";
			//echo "select user_id,user_name,agent_name,'$id' as dep_id from user where user_id IN ($dep_user)";exit;
			$active=$this->dataFetchAll("select user.user_id,user.agent_name from user LEFT join dep_drag on $dept_id=dep_drag.dept_id and user.user_id=dep_drag.user_id where user.user_id IN ($dep_user) group by user.user_id order by dep_drag.position ASC",array());
		$active_users = $active;
		$active = array_column($active, 'user_id');
		$active = implode(',',$active);
		$qry = "select * from email_agent_settings where agent_id IN ($active)";
		$res= $this->dataFetchAll($qry, array("dept_id"=>$id));
		//print_r($res);exit;
		$result['dept_users'] = $active_users;
		$result['email_settings'] = $res;
 
			return $result;
		}
public function addAgentMailSetting($data){       
       extract($data); 
	$qry = "select * from email_agent_settings where agent_id LIKE '%$agent_id%'";
    $result =  $this->fetchData($qry, array());	 
		
	 if($result > 0){
		 $id = $result['id'];
	 	 $qry = "UPDATE email_agent_settings SET new_email_alert='$new_email_alert',reply_email_alert='$reply_email_alert',close_email_alert='$close_email_alert',send_fullthread='$send_fullthread' where id='$id'";
		 $update_data = $this->db_query($qry, $params);
	 } else {
	 	$qry = "INSERT INTO email_agent_settings(agent_id,new_email_alert,reply_email_alert,close_email_alert,send_fullthread) VALUES ('$agent_id','$new_email_alert','$reply_email_alert','$close_email_alert','$send_fullthread')";  
        $insert_data = $this->db_query($qry, $params);
	 }

        $qry = "select * from email_agent_settings";
        $result =  $this->dataFetchAll($qry, array());
        return $result;
    }
	function delEmailSettings($id){
        $qry = "DELETE FROM email_agent_settings where id='$email'";
        $result =  $this->db_query($qry, array());
        return $result;
    }
	public function update_type_settings($data){
		extract($data);
		if($type=='email'){
		  $qry = "UPDATE departments SET `has_email`='$value' WHERE `dept_id` = '$dept_id'";
		}
		elseif($type=='chat'){
		  $qry = "UPDATE departments SET `has_chat`='$value' WHERE `dept_id` = '$dept_id'";
		}
		elseif($type=='facebook'){
		  $qry = "UPDATE departments SET `has_fb`='$value' WHERE `dept_id` = '$dept_id'";
		}else{
		  $qry = "UPDATE departments SET `has_wp`='$value' WHERE `dept_id` = '$dept_id'";	
		}
		$update_data = $this->db_query($qry, array());
		if($update_data != 0){
			$result = 1;
		}
		else{
			$result = 0;
		}
		return $result;
    }
	function add_email_group($data){
	  extract($data);	  	
	  $qry = "select * from user where user_id='$user_id'";//echo $qry;exit;
	  $result = $this->fetchData($qry, array());
	  //print_r($result);exit;	
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }
	  $qry = "select * from email_group where group_name LIKE '%$group_name%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO email_group(group_name,email,admin_id) VALUES ('$group_name','$email','$admin_id')";
		  //echo $qry;exit;
        $qry_result = $this->db_query($qry, array());           
		if($qry_result == 1){
		  $result = 1;              
		}
		else{                
		  $result = 0;
		}            
		return $result;
	  }
    }
	public function list_email_group($data){
	  extract($data);
	  $qry = "select user_type,admin_id from user where user_id='$user_id'";
	  $res = $this->fetchsingledata($qry, array());	
	  if($res['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $res['admin_id']; 
	  }
	    $qry = "select * from email_group where admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
	}
	public function addGroupMailSetting($data){       
       extract($data); 
	   $qry = "UPDATE email_group SET new_email_alert='$new_email_alert',reply_email_alert='$reply_email_alert',close_email_alert='$close_email_alert',send_fullthread='$send_fullthread' where id='$group_id'";
	   $update_data = $this->db_query($qry, $params);
	   if($update_data==1){
	     $result = 1;
	   }else{
	     $result = 0;
	   }	
	   return $result;
    }
	function addSmtpSetting($data){
	  extract($data);//print_r($data);
	  $password = base64_decode($password);	  	
	  $qry = "select * from user where user_id='$user_id'";//echo $qry;exit;
	  $result = $this->fetchData($qry, array());
	  //print_r($result);exit;	
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }
	  $qry = "select * from smtp_details where hostname LIKE '%$hostname%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO smtp_details(hostname,port,username,password,admin_id,departments) VALUES ('$hostname','$port','$username','$password','$admin_id','$departments')";	
		//echo $qry;exit;  
        $qry_result = $this->db_query($qry, array());           
		if($qry_result == 1){
		  $result = 1;              
		}
		else{                
		  $result = 0;
		}            
		return $result;
	  }
    }
	public function list_smtp($user_id){
	  $qry = "select * from user where user_id='$user_id'";
	  $result = $this->fetchData($qry, array());	  
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }	
	  $qry = "select s.*,d.department_name from smtp_details as s LEFT JOIN departments as d ON d.dept_id=s.departments where s.admin_id ='$admin_id' and s.delete_status=0";
		//echo $qry;
	  return $this->dataFetchAll($qry, array());
	}
	public function get_smtp_byid($id){	  
	  $qry = "select * from smtp_details where id ='$id'";
	  return $this->dataFetchAll($qry, array());
	}
	public function updateSmtpSetting($data){       
       extract($data); 
	   $qry = "UPDATE smtp_details SET hostname='$hostname',port='$port',username='$username',password='$password',departments='$departments' where id='$id'";
		//echo $qry;exit;
	   $update_data = $this->db_query($qry, $params);
	   if($update_data==1){
	     $result = 1;
	   }else{
	     $result = 0;
	   }	
	   return $result;
    }
	function delete_smtp($id){
      $qry = "UPDATE smtp_details SET delete_status='1' WHERE id='$id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function edit_email_group($group_id){	  
	  $qry = "select * from email_group where id ='$group_id'";
	  return $this->dataFetchAll($qry, array());
	}
	/*function update_email_group($data){
	  extract($data);	
      $qry = "UPDATE email_group SET group_name='$group_name',email='$email', new_email_alert='$new_email_alert',reply_email_alert='$reply_email_alert',close_email_alert='$close_email_alert',send_fullthread='$send_fullthread' where id='$group_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }*/
	function update_email_group($data){
	  extract($data);
	  $group_qry = "select group_name from email_group where id !='$group_id' and group_name = '$group_name'";	   
	  $gname = $this->fetchmydata($group_qry, array());
	  if($gname==''){	
      $qry = "UPDATE email_group SET group_name='$group_name',email='$email',new_email_alert='$new_email_alert',reply_email_alert='$reply_email_alert',close_email_alert='$close_email_alert',send_fullthread='$send_fullthread' where id='$group_id'";	
      $parms = array();      
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      }else{
      	$output = 0;
      }
      return  $output;
    }
	public function delete_email_group($group_id){     
     $qry = "Delete FROM email_group WHERE id='$group_id'";
     $qry_result = $this->db_query($qry, array());
     if($qry_result == 1){
      $result = 1;
     }else{
      $result = 0;
    }
    return $result;
  }
  function set_default_smtp($data){
	  extract($data);//print_r($data);
	  if($value==1){
	   $qry = "select id from smtp_details where status=1";	   
	   $id_result = $this->fetchmydata($qry, array());
	   if($id_result!=''){
	   	 $qry = "UPDATE smtp_details SET status=0 where id='$id_result'";
	   	 $parms = array();
         $update = $this->db_query($qry,$parms);
         $qry = "UPDATE smtp_details SET status=1 where id='$id'";
	   	 $parms = array();
         $update = $this->db_query($qry,$parms);
         $result = 1;
	   }
	   else{
	   	 $qry = "UPDATE smtp_details SET status=1 where id='$id'";
	   	 $parms = array();
         $update = $this->db_query($qry,$parms);
         $result = 1;
	   }	   
      }else{
      	 $qry = "UPDATE smtp_details SET status=0 where id='$id'";
	   	 $parms = array();
         $update = $this->db_query($qry,$parms);
         $result = 1;
      }
      return $result;
   }
   public function get_agents($data){//print_r($data);exit;
    extract($data);
    $search_qry = "";
    if($search_text!= ""){
        $search_qry= " and (agent_name like '%".$search_text."%')";
    }
    $admin_id=$this->fetchOne("select admin_id from user where user_id='$user_id'",array());
    //echo $admin_id;exit;
    if($admin_id=='1'){ 
        $qry = "select user_id,agent_name,new_email_alert,reply_email_alert,close_email_alert,send_fullthread,share_tickets from user where admin_id = '$user_id'".$search_qry;
        $detail_qry = $qry." order by user_id DESC LIMIT ".$limit." Offset ".$offset;   
    }else{
        $qry = "select user_id,agent_name,new_email_alert,reply_email_alert,close_email_alert,send_fullthread,share_tickets from user where admin_id = '$admin_id'".$search_qry;
        $detail_qry = $qry." order by user_id DESC LIMIT ".$limit." Offset ".$offset; 
    }
    //echo $detail_qry;exit;
        $parms = array();        
        $result["list_data"] = $this->dataFetchAll($detail_qry,$parms);
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;   
        return $result;          
   }
   function update_agent_alert($data){
	  extract($data);	
      $qry = "UPDATE user SET new_email_alert='$new_email_alert',reply_email_alert='$reply_email_alert',close_email_alert='$close_email_alert',send_fullthread='$send_fullthread' where user_id='$user_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }	
	function alertEmail($alert){
		extract($alert);
		//print_r($close_alert); exit;
		$from = 'assaabloycc';
		$from_email = 'nzassaabloycc@pipe.mconnectapps.com';
		$smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
	    $smtp_qry_value = $this->fetchData($smtp_qry,array());
		//print_r($smtp_qry_value); exit;
	    $hostname = $smtp_qry_value['hostname'];
	    $port = $smtp_qry_value['port'];
	    $username = $smtp_qry_value['username'];
	    $password = $smtp_qry_value['password'];
	    require_once('class.phpmailer.php'); 
	    $subject = $subject; 
	    $body = $message;   
	    $mail = new PHPMailer();
	    $mail->IsSMTP();
	    $mail->SMTPAuth = true; 
	    $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
	    $mail->Host = $hostname; // sets the SMTP server
	    $mail->Port = $port;                    // set the SMTP port for the GMAIL server
	    $mail->Username = $username; // SMTP account username
	    $mail->Password = $password;        // SMTP account password 
	    $mail->SetFrom($from_email,$from);
        $mail->AddReplyTo($from_email,$from);
		$mail->IsHTML(true);
	    $mail->Subject = $subject;
	    $mail->MsgHTML($body);	    	    
	    $mail->AddAddress($to_agent_email);
		$se = $mail->Send();
		if($se){
			$te = 'if';
		}else{
			$te = 'else';
		}
		$this->errorLog("demo",$te);
    }
	function check_email($data){
	  extract($data);
	  $check_group_qry = "SELECT group_name FROM `email_group` WHERE `email` LIKE '%$email%'";
	  $group_email = $this->fetchmydata($check_group_qry,array());
	  if($group_email==''){	  	
		  $qry = "select user_id,email_id,new_email_alert,reply_email_alert,close_email_alert,send_fullthread from user where email_id='$email'";
		  $result = $this->fetchData($qry, array());
		  if($result>0){
			  if($result['new_email_alert'] == 0 && $result['reply_email_alert'] == 0 && $result['close_email_alert'] == 0 && $result['send_fullthread'] == 0)
			  { 
					$result = 1;
			  }else{
					$result = 0; 
			  }	
		  }else{
		  	$result = 2;
		  }	  	  
	  }else{
	 	$result = 0;
	  }
	  return $result;  
    }
	function checking_email($data){echo "xxc";exit;
	  /*extract($data);
	  $user_qry = "SELECT email_id FROM `user` WHERE `user_id`='$user_id'";
	  $user_email = $this->fetchmydata($user_qry,array());
	  $check_group_qry = "SELECT email FROM `email_group` WHERE `email` LIKE '%$user_email%'";
	  $group_email = $this->fetchmydata($check_group_qry,array());
	  $explode = explode(',', $group_email);	  
	  $pos = array_search($user_email, $explode);      
      unset($explode[$pos]);
	  $arr_count = count($explode);
      for($i=0;$i<=$arr_count;$i++){
      	echo $explode[$i];
      }*/
	  $agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=0 AND admin_id='1211'";
      $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
      //print_r($agent_alert_email);exit;
	  $agentArr = array();
	  for($k = 0; $k < count($agent_alert_email); $k++){
		  $agent_emails = $agent_alert_email[$k]['email_id'];		  
		  array_push($agentArr, $agent_emails);
	  }
	  $group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=0 AND admin_id='1211'";
      $group_alert_email = $this->dataFetchAll($group_alert_qry,array());      
	  $groupArr = array();
	  for($j = 0; $j < count($group_alert_email); $j++){
		  $group_email = $group_alert_email[$j]['email'];
		  $explode = explode(',', $group_email);		  
		  $arr_count = count($explode);
          for($q=0;$q<$arr_count;$q++){
			  $group_emails = $explode[$q];
			  array_push($groupArr, $group_emails);
		  }		  
	  }
	  $ticket_bcc = array_merge($agentArr, $groupArr);
	  print_r($ticket_bcc);	exit;
    }
    function check_to_email($data){
	  extract($data);
	  $check_qry = $this->fetchOne("SELECT COUNT(id) FROM `department_emails` WHERE `emailAddr`='$to_email' OR `senderID`='$to_email' OR `aliseEmail`='$to_email'",array());	
	  if($check_qry >= 1){	  	
		$result = 1;
	  }else{
		$result = 0; 
	  }	
      return $result;
   }
   public function update_override($data){
	extract($data);
	$qry = "UPDATE admin_details SET `override`='$value' WHERE `admin_id` = '$admin_id'";
	$update_data = $this->db_query($qry, array());
	if($update_data != 0){
		$result = 1;
	}
	else{
		$result = 0;
	}
	return $result;
   }
   public function delete_multiple_ticket($data){
     extract($data);    
     $explode = explode(',', $value);	 
     for($i=0;$i<count($explode);$i++){
       $val = $explode[$i]; 
       $qry = "DELETE FROM external_tickets WHERE ticket_no='$val' AND admin_id='$admin_id'";
       $qry_result = $this->db_query($qry, array());
       $dataqry = "DELETE FROM external_tickets_data WHERE ticket_id='$val'";
       $data_qry_result = $this->db_query($dataqry, array());              
       if($qry_result == 1){
         $result = 1;
       }else{
         $result = 0;
       }     
     }
     return $result;
   } 	
	
		  
		  
	function add_email_filter($data){
	  extract($data);	
		//print_r($data); exit;
	  $qry = "select * from user where user_id='$user_id'";//echo $qry;exit;
	  $result = $this->fetchData($qry, array());
	  //print_r($result);exit;	
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }
	  $qry = "select * from email_words_filtering where filter_word LIKE '%$keyword%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO email_words_filtering(filter_word,user_id,admin_id) VALUES ('$keyword','$user_id','$admin_id')";
		  //echo $qry;exit;
        $qry_result = $this->db_query($qry, array());           
		if($qry_result == 1){
		  $result = 1;              
		}
		else{                
		  $result = 0;
		}            
		return $result;
	  }
    }
	
		  
		  
	public function list_email_filter($data){
	  extract($data);
	  $qry = "select user_type,admin_id from user where user_id='$user_id'";
	  $res = $this->fetchsingledata($qry, array());	
	  if($res['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $res['admin_id']; 
	  }
	    $qry = "select * from email_words_filtering where admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
	}
  public function edit_email_filter($key_id){	
	 
	  $qry = "select * from email_words_filtering where id ='$key_id'";
	   //echo $qry; exit;
	  return $this->dataFetchAll($qry, array());
	}
	
		  
   function update_email_filter($data){
	  extract($data);	
      $qry = "UPDATE email_words_filtering SET filter_word='$filter_word' where id='$key_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }			  
public function delete_email_filter($key_id){     
     $qry = "Delete FROM email_words_filtering WHERE id='$key_id'";
     $qry_result = $this->db_query($qry, array());
     if($qry_result == 1){
      $result = 1;
     }else{
      $result = 0;
    }
    return $result;
  }
public function update_share_ticket($data){
	  extract($data);	
      $qry = "UPDATE user SET share_tickets='$share_ticket' where user_id='$user_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }	
}

?>