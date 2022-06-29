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
    public function advanceTicketReport($data){
        extract($data);
	  
		  $qry = "SELECT ext.ticket_no,ext.created_dt,ext.ticket_from,ext.customer_name,ext.ticket_subject,pri.priority,dep.department_name,st.status_name,ext.updated_at,ext.closed_at,count(ed.ticket_id) as ticket_count FROM `external_tickets` as ext INNER JOIN external_tickets_data as ed ON(ext.ticket_no=ed.ticket_id) INNER JOIN departments as dep on(ext.ticket_department=dep.dept_id) INNER JOIN priority as pri on(ext.priority=pri.id) INNER JOIN status as st on(ext.ticket_status=st.status_id) WHERE 1 ";
		  
		 if($fromDate!='' and $toDate !=''){
			  $fromDate_db = date("Y-m-d",strtotime($fromDate));
			  $toDate_db = date("Y-m-d",strtotime($toDate));
			  $qry.=" AND ext.created_dt>='$fromDate_db 00:00:01' and ext.created_dt<='$toDate_db 23:59:59'";
		 }
		 if($customer_name!=''){			 
			  $qry.=" AND ext.customer_name ='$customer_name'";
		 }
		 if($search_text!=''){			 
			  //$qry.=" AND ext.ticket_subject LIKE '%$search_text%'";
			  $qry.=" AND ext.ticket_from LIKE '%$search_text%'";
		 }
	//	echo $qry;exit;
         $Totalqry=$qry." group by ed.ticket_id ORDER BY `ext`.`ticket_no` DESC";
		// echo $Totalqry;exit;
		 $qry.=" group by ed.ticket_id ORDER BY `ext`.`ticket_no` DESC limit $limit offset $offset";
		 //echo $qry;exit;
		 //file_put_contents('rep_qry.txt', $qry.PHP_EOL , FILE_APPEND | LOCK_EX);
        $results = $this->dataFetchAll($qry, array());
    	$total_count = $this->dataRowCount($Totalqry,array());
	    //$total = array('total' => $total_count);
        $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	    //$list_info_arr = array('list_info' => $list_info);    
        $merge_result = array('data'=>$results,'list_info' => $list_info); 		
       // $tarray = json_encode($merge_result);           
         // print_r($tarray);exit;
        

		return  $merge_result;
    }
	
	
public function export_ticket_reports($data){
        extract($data);
		$qry = "SELECT ticket_no,ticket_from,customer_name,ticket_subject,(select department_name from departments where dept_id = ticket_department) as department_name,(select agent_name from user where user_id = ticket_assigned_to) as agent_name,(select status_name from status where status_id = ticket_status) as status_name,(select priority from priority where id = priority) as priority_name,(select count(ticket_id) from external_tickets_data where ticket_id = ticket_no) as ticket_count,created_dt,closed_at FROM `external_tickets` WHERE 1 ";
	//echo $qry;exit;
	    if($fromDate!='' and $toDate !=''){
			  $fromDate_db = date("Y-m-d",strtotime($fromDate));
			  $toDate_db = date("Y-m-d",strtotime($toDate));
			  $qry.=" AND created_dt>='$fromDate_db 00:00:01' and created_dt<='$toDate_db 23:59:59'";
		 }
		 if($customer_name!=''){			 
			  $qry.=" AND customer_name ='$customer_name'";
		 }
		 if($search_text!=''){			 
			  //$qry.=" AND ticket_subject LIKE '%$search_text%'";
			  $qry.=" AND ticket_from LIKE '%$search_text%'";
		 }
	//    $qry.=" ORDER BY ticket_no DESC limit $limit offset $offset";
	 $qry.=" ORDER BY ticket_no DESC";
        $results = $this->dataFetchAll($qry, array());
		return  $results;
    }
	function getmyExternalTicket($data){
		extract($data);//print_r($data);exit;
		$dep = $ticket_department;
		if($user_type == 2){
         //echo $unassign;exit;
		//echo $unassign;exit;
		$uassign = 1;	
			if($ticket_status == 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status!=9 and ticket_status!=8 and unassign=$uassign and delete_status=0 and ticket_assigned_to!=''";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
					//echo $detail_qry;exit;
			} else if($ticket_status == 'All' && $ticket_department != 'All'){
				 	$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_department = '$ticket_department' and is_spam = '$is_spam' and ticket_status!=9 and ticket_status!=8 and unassign=$uassign and delete_status=0 and ticket_assigned_to!=''";				
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
					//echo $detail_qry;exit;
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			
			} else if($ticket_status != 'All' && $ticket_department == 'All'){
				//if($ticket_status==3 || $ticket_status==5 || $ticket_status==9){
//$sub_qry = " and ";
				//}
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_status IN ($ticket_status) and is_spam = '$is_spam' and unassign=$uassign and delete_status=0 and ticket_assigned_to!=''";
				 	$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}  else {
				$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status IN ($ticket_status) and ticket_department LIKE '%$ticket_department%' and unassign=$uassign and delete_status=0 and ticket_assigned_to!=''";
				 $detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}
//echo $detail_qry;exit;			
$result = $this->dataFetchAll($detail_qry, array());
			//print_r($result);exit;	
        for($i = 0; count($result) > $i; $i++){ 
		  $ticket_customer_id = $result[$i]['customer_id'];	
		  $ticket_customer_name = $result[$i]['customer_name'];	
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
		  $ticket_type = $result[$i]['type'];
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
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
          $ticket_options_array[] = $ticket_options;
        }
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments WHERE admin_id='$admin_id' AND delete_status='0' AND has_email='1'";
	    $dep_array = $this->dataFetchAll($department_array_qry, array());
		for($j = 0; $j < count($dep_array); $j++){
		 $department_id = $dep_array[$j]['department_id'];
		 $department_name = $dep_array[$j]['department_name'];
		 //$ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM `external_tickets` WHERE admin_id='$admin_id' AND ticket_department='$department_id' AND unassign=1 AND delete_status=0 AND is_spam=0",array());
		 $ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM `external_tickets` WHERE admin_id='$admin_id' AND ticket_department='$department_id' AND unassign=1 AND delete_status=0 AND ticket_assigned_to!='' AND ticket_status!=9 AND ticket_status!=8",array());	
		 $dept_options = array('department_id' => $department_id, 'department_name' => $department_name, 'ticket_count' => $ticket_count);
		 $department_options_array[] = $dept_options;  
	    }	
		}// admin side condition ends here 
else {
			
			if($ticket_status == 'All' && $ticket_department == 'All' && $ticket_user==$user_id){
				 $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$ticket_user',a.ticket_assigned_to ) AND a.is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8 OR a.ticket_created_by = $ticket_user AND a.is_spam = '$is_spam' AND a.ticket_status!=9 AND a.ticket_status!=8 AND a.unassign=1 AND a.delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				//echo $detail_qry;exit;
				 $result = $this->dataFetchAll($detail_qry,array());
				 for($k = 0; count($result) > $k; $k++){
					$ticket_customer_id = $result[$k]['customer_id'];	
		            $ticket_customer_name = $result[$k]['customer_name']; 
					$ticket_no = $result[$k]['ticket_no'];	
					$ticket_new_status = $result[$k]['status_del'];	
					$ticket_created_by = $result[$k]['ticket_from'];
					$ticket_from = $result[$k]['ticket_from'];
					$ticket_assigned_to = $result[$k]['ticket_assigned_to'];
					$ticket_department = $result[$k]['ticket_department'];
					$ticket_status = $result[$k]['ticket_status'];
					$closed_at = $result[$k]['closed_at'];
					$priority = $result[$k]['priority'];
					$ticket_created_at = $result[$k]['created_dt'];
					$ticket_message = $result[$k]['ticket_message'];
					//$ticket_subject = $result[$k]['ticket_subject'];
					$ticket_subject= $this->fetchOne("Select ticket_subject from external_tickets where ticket_no='$ticket_no'", array());
					$spammed = $result[$k]['spammed'];
					$ticket_type = $result[$k]['type'];
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
					$createdby = $this->fetchmydata($createdby_qry,array());
					$ticket_assigned_to = explode(',',$ticket_assigned_to); 
					if(count($ticket_assigned_to) == 1){
					    $ticket_assigned_t = $ticket_assigned_to[0];
						$ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";
						$ticket_assigned = $this->fetchData($ticket_assigned,array());
						$assignedto=$ticket_assigned['agent_name'];
					}else{
					    $assignedto = '';
					}
					$deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";
					$department = $this->fetchmydata($deptment_qry,array());
					$status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
					$ticketstatus = $this->fetchmydata($status_qry,array());
					$priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
					$priority_value = $this->fetchmydata($priority_qry,array());		
					$time_ago = strtotime($ticket_created_at); 
                    $created_time = $this->time_Ago($time_ago);
					$created_time = date("Y-m-d H:i:s", $time_ago);
					$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
					$ticket_options_array[] = $ticket_options;
				} // ticket for loop
			}
			else if($ticket_status == 'All' && $ticket_department != 'All' && $ticket_user==$user_id){
                $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$ticket_user',a.ticket_assigned_to ) and a.ticket_department LIKE '%$ticket_department%' and a.is_spam = '$is_spam' AND a.ticket_status!=9 AND a.ticket_status!=8 AND a.unassign=1 AND a.delete_status=0 OR a.ticket_created_by = $ticket_user and a.ticket_department LIKE '%$ticket_department%' and a.is_spam = '$is_spam' AND a.ticket_status!=9 AND a.ticket_status!=8 AND a.unassign=1 AND a.delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				 $result = $this->dataFetchAll($detail_qry,array());
				 for($k = 0; count($result) > $k; $k++){
					$ticket_customer_id = $result[$k]['customer_id'];	
		            $ticket_customer_name = $result[$k]['customer_name']; 
					$ticket_no = $result[$k]['ticket_no'];	
					$ticket_new_status = $result[$k]['status_del'];	
					$ticket_created_by = $result[$k]['ticket_from'];
					$ticket_from = $result[$k]['ticket_from'];
					$ticket_assigned_to = $result[$k]['ticket_assigned_to'];
					$ticket_department = $result[$k]['ticket_department'];
					$ticket_status = $result[$k]['ticket_status'];
					$closed_at = $result[$k]['closed_at'];
					$priority = $result[$k]['priority'];
					$ticket_created_at = $result[$k]['created_dt'];
					$ticket_message = $result[$k]['ticket_message'];
					//$ticket_subject = $result[$k]['ticket_subject'];
					$ticket_subject= $this->fetchOne("Select ticket_subject from external_tickets where ticket_no='$ticket_no'", array());
					$spammed = $result[$k]['spammed'];
					$ticket_type = $result[$k]['type'];
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
					$createdby = $this->fetchmydata($createdby_qry,array());
					$ticket_assigned_to = explode(',',$ticket_assigned_to); 
					if(count($ticket_assigned_to) == 1){
					    $ticket_assigned_t = $ticket_assigned_to[0];
						$ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";
						$ticket_assigned = $this->fetchData($ticket_assigned,array());
						$assignedto=$ticket_assigned['agent_name'];
					}else{
					    $assignedto = '';
					}
					$deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";
					$department = $this->fetchmydata($deptment_qry,array());
					$status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
					$ticketstatus = $this->fetchmydata($status_qry,array());
					$priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
					$priority_value = $this->fetchmydata($priority_qry,array());		
					$time_ago = strtotime($ticket_created_at); 
                    $created_time = $this->time_Ago($time_ago);
					$created_time = date("Y-m-d H:i:s", $time_ago);
					$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
					$ticket_options_array[] = $ticket_options;
				 } // ticket for loop 				 	
			} 
			else if($ticket_status != 'All' && $ticket_department == 'All' && $ticket_user==$user_id){
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$ticket_user',a.ticket_assigned_to ) and a.ticket_status IN ($ticket_status) and a.is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0 OR a.ticket_created_by = $ticket_user and a.ticket_status IN ($ticket_status) and a.is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				 $result = $this->dataFetchAll($detail_qry,array());
				 for($k = 0; count($result) > $k; $k++){ 
					$ticket_customer_id = $result[$k]['customer_id'];	
		            $ticket_customer_name = $result[$k]['customer_name']; 
					$ticket_no = $result[$k]['ticket_no'];	
					$ticket_new_status = $result[$k]['status_del'];	
					$ticket_created_by = $result[$k]['ticket_from'];
					$ticket_from = $result[$k]['ticket_from'];
					$ticket_assigned_to = $result[$k]['ticket_assigned_to'];
					$ticket_department = $result[$k]['ticket_department'];
					$ticket_status = $result[$k]['ticket_status'];
					$closed_at = $result[$k]['closed_at'];
					$priority = $result[$k]['priority'];
					$ticket_created_at = $result[$k]['created_dt'];
					$ticket_message = $result[$k]['ticket_message'];
					//$ticket_subject = $result[$k]['ticket_subject'];
					$ticket_subject= $this->fetchOne("Select ticket_subject from external_tickets where ticket_no='$ticket_no'", array());
					$spammed = $result[$k]['spammed'];
					$ticket_type = $result[$k]['type'];
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
					$createdby = $this->fetchmydata($createdby_qry,array());
					$ticket_assigned_to = explode(',',$ticket_assigned_to); 
					if(count($ticket_assigned_to) == 1){
					    $ticket_assigned_t = $ticket_assigned_to[0];
						$ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";
						$ticket_assigned = $this->fetchData($ticket_assigned,array());
						$assignedto=$ticket_assigned['agent_name'];
					}else{
					    $assignedto = '';
					}
					$deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";
					$department = $this->fetchmydata($deptment_qry,array());
					$status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
					$ticketstatus = $this->fetchmydata($status_qry,array());
					$priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
					$priority_value = $this->fetchmydata($priority_qry,array());		
					$time_ago = strtotime($ticket_created_at); 
                    $created_time = $this->time_Ago($time_ago);
					$created_time = date("Y-m-d H:i:s", $time_ago);
					$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
					$ticket_options_array[] = $ticket_options;
				 } // ticket for loop
			}else if($ticket_status == 'All' && $ticket_department == 'All' && $ticket_user != $user_id){
				 $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to LIKE '%$ticket_user%' AND a.is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8 OR a.ticket_created_by LIKE '%$ticket_user%' AND a.is_spam = '$is_spam' AND a.ticket_status!=9 AND a.ticket_status!=8 AND a.unassign=1 AND delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				 $result = $this->dataFetchAll($detail_qry,array());
				 for($k = 0; count($result) > $k; $k++){
					$ticket_customer_id = $result[$k]['customer_id'];	
		            $ticket_customer_name = $result[$k]['customer_name']; 
					$ticket_no = $result[$k]['ticket_no'];	
					$ticket_new_status = $result[$k]['status_del'];	
					$ticket_created_by = $result[$k]['ticket_from'];
					$ticket_from = $result[$k]['ticket_from'];
					$ticket_assigned_to = $result[$k]['ticket_assigned_to'];
					$ticket_department = $result[$k]['ticket_department'];
					$ticket_status = $result[$k]['ticket_status'];
					$closed_at = $result[$k]['closed_at'];
					$priority = $result[$k]['priority'];
					$ticket_created_at = $result[$k]['created_dt'];
					$ticket_message = $result[$k]['ticket_message'];
					//$ticket_subject = $result[$k]['ticket_subject'];
					$ticket_subject= $this->fetchOne("Select ticket_subject from external_tickets where ticket_no='$ticket_no'", array());
					$spammed = $result[$k]['spammed'];
					$ticket_type = $result[$k]['type'];
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
					$createdby = $this->fetchmydata($createdby_qry,array());
					$ticket_assigned_to = explode(',',$ticket_assigned_to); 
					if(count($ticket_assigned_to) == 1){
					    $ticket_assigned_t = $ticket_assigned_to[0];
						$ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";
						$ticket_assigned = $this->fetchData($ticket_assigned,array());
						$assignedto=$ticket_assigned['agent_name'];
					}else{
					    $assignedto = '';
					}
					$deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";
					$department = $this->fetchmydata($deptment_qry,array());
					$status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
					$ticketstatus = $this->fetchmydata($status_qry,array());
					$priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
					$priority_value = $this->fetchmydata($priority_qry,array());		
					$time_ago = strtotime($ticket_created_at); 
                    $created_time = $this->time_Ago($time_ago);
					$created_time = date("Y-m-d H:i:s", $time_ago);
					$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
					$ticket_options_array[] = $ticket_options;
				} // ticket for loop
			}  
			else {
			      $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to LIKE '%$ticket_user%' and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and a.is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8 OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' AND a.unassign=1 AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
				 $result = $this->dataFetchAll($detail_qry,array());
				 for($k = 0; count($result) > $k; $k++){
					$ticket_customer_id = $result[$k]['customer_id'];	
		            $ticket_customer_name = $result[$k]['customer_name']; 
					$ticket_no = $result[$k]['ticket_no'];	
					$ticket_new_status = $result[$k]['status_del'];	
					$ticket_created_by = $result[$k]['ticket_from'];
					$ticket_from = $result[$k]['ticket_from'];
					$ticket_assigned_to = $result[$k]['ticket_assigned_to'];
					$ticket_department = $result[$k]['ticket_department'];
					$ticket_status = $result[$k]['ticket_status'];
					$closed_at = $result[$k]['closed_at'];
					$priority = $result[$k]['priority'];
					$ticket_created_at = $result[$k]['created_dt'];
					$ticket_message = $result[$k]['ticket_message'];
					//$ticket_subject = $result[$k]['ticket_subject'];
					$ticket_subject= $this->fetchOne("Select ticket_subject from external_tickets where ticket_no='$ticket_no'", array());
					$spammed = $result[$k]['spammed'];
					$ticket_type = $result[$k]['type'];
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
					$createdby = $this->fetchmydata($createdby_qry,array());
					$ticket_assigned_to = explode(',',$ticket_assigned_to); 
					if(count($ticket_assigned_to) == 1){
					    $ticket_assigned_t = $ticket_assigned_to[0];
						$ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$ticket_assigned_t'";
						$ticket_assigned = $this->fetchData($ticket_assigned,array());
						$assignedto=$ticket_assigned['agent_name'];
					}else{
					    $assignedto = '';
					}
					$deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$ticket_department'";
					$department = $this->fetchmydata($deptment_qry,array());
					$status_qry = "SELECT status_desc FROM status WHERE status_id='$ticket_status'";              
					$ticketstatus = $this->fetchmydata($status_qry,array());
					$priority_qry = "SELECT priority FROM priority WHERE id='$priority'";              
					$priority_value = $this->fetchmydata($priority_qry,array());		
					$time_ago = strtotime($ticket_created_at); 
                    $created_time = $this->time_Ago($time_ago);
					$created_time = date("Y-m-d H:i:s", $time_ago);
					$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
					$ticket_options_array[] = $ticket_options;
				} // ticket for loop				
			}
	        $department_array_qry = "SELECT dept_id as department_id,department_name FROM departments WHERE admin_id='$admin_id' AND FIND_IN_SET('$user_id',department_users) AND delete_status='0' AND has_email='1'";
	        $dep_array = $this->dataFetchAll($department_array_qry, array());
			for($j = 0; $j < count($dep_array); $j++){
			 $department_id = $dep_array[$j]['department_id'];
			 $department_name = $dep_array[$j]['department_name'];
			 $ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM `external_tickets` WHERE admin_id='$admin_id' AND ticket_assigned_to='$user_id' AND ticket_department='$department_id' AND unassign=1 AND delete_status=0 AND ticket_status!=9 AND ticket_status!=8",array());		
			 $dept_options = array('department_id' => $department_id, 'department_name' => $department_name, 'ticket_count' => $ticket_count);
			 $department_options_array[] = $dept_options;  
		    }
		}	
		

//print_r($ticket_options_array); exit;

					//TOTAL COUNT CODE 05-07-2021
	//echo $dep;exit;
		if($user_type == 2){
	if($dep == 'All'){
       // $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status group by st.status_id order by st.status_id";
   			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		    $sid = $row[$i]['status_id'];
				if($sid==3){
				  $sub_qry = "AND unassign=1 AND delete_status=0 AND ticket_assigned_to!=''";
				}else{
				  $sub_qry = " AND unassign=1 AND delete_status=0";	
				}
   			    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'$sub_qry";     

     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
	
	
	}else if($dep != 'All'){
    //$status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status WHERE et.ticket_department = '$ticket_department' group by et.ticket_status order by et.ticket_status";
		
				$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		    $sid = $row[$i]['status_id'];
				$sub_qry = '';
				if($sid==3){
				  $sub_qry = "AND unassign=1 AND delete_status=0 AND ticket_assigned_to!=''";
				}else{
				  $sub_qry = " AND unassign=1 AND delete_status=0";	
				}
   			    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'$sub_qry";     
//echo $count_query;
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		
		
	}else{
      $status_array_qry = "SELECT status_id,status_desc FROM status";
   }
		}else{
		
	if($dep == 'All'){	    
	$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		    $sid = $row[$i]['status_id'];
				$sub_qry = '';
				if($sid==3){
				  $sub_qry = "AND unassign=1 AND delete_status=0 AND ticket_assigned_to!=''";
				}else{
				  $sub_qry = " AND unassign=1 AND delete_status=0";	
				}
   			 ///    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_created_by ='$user_id' AND is_spam=0";     
		
				$count_query="SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND ticket_assigned_to='$user_id' AND is_spam=0$sub_qry OR ticket_status = $sid AND ticket_created_by ='$user_id' AND is_spam=0$sub_qry";
		//echo $count_query;		
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		 //echo json_encode($status_array_qry, true);exit;
		
	}else if($dep != 'All'){
		// $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status JOIN external_tickets_data b ON et.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',et.ticket_assigned_to ) and et.ticket_department = '$ticket_department' OR et.ticket_created_by ='$user_id' group by et.ticket_status order by et.ticket_status";
		
			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		    $sid = $row[$i]['status_id'];
				$sub_qry = '';
				if($sid==3){
				  $sub_qry = "AND unassign=1 AND delete_status=0 AND ticket_assigned_to!=''";
				}else{
				  $sub_qry = " AND unassign=1 AND delete_status=0";	
				}
   			   //  $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) OR ticket_created_by ='$user_id' AND is_spam=0";
				
				  $count_query = "SELECT * FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND ticket_assigned_to = '$user_id' AND is_spam=0$sub_qry OR ticket_created_by ='$user_id' AND is_spam=0 AND ticket_status = $sid AND ticket_department = '$ticket_department'$sub_qry";
				
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
		/*$result = $this->dataFetchAll($detail_qry, array());	
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
        }*/
		//$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		//$department_options_array = $this->dataFetchAll($department_array_qry, array());
		
			
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
        $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	    $list_info_arr = array('list_info' => $list_info);    
        $merge_result = array_merge($status, $department_options_array, $status_options_array,$count_status_options_array, $priority_options_array, $ticket_options_array,$total,$list_info_arr); 		
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
		$explode_ticket_from = explode('<',$ticket_to);//print_r($explode_ticket_from);exit;
        $explode_ticket_from1 = explode('>',$explode_ticket_from[1]);
        $explode_ticket_from_val = str_replace(' ', '',$explode_ticket_from1[0]);
		$tic_from =  $tic_details['ticket_from'];
		preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $tic_from, $tic_from_value);
		$dept =  $tic_details['ticket_department'];
		if($tic_from == 'user'){
			$main_tick_from = $tic_details['ticket_email'];
		} else {
			$main_tick_from = str_replace('("','',$ticket_from);
			$main_tick_from = str_replace('")','',$main_tick_from);
			if($main_tick_from=='omni@pipe.mconnectapps.com'){
				$main_tick_from = 'isales@cal4care.com';
			}
		}
		$from = $main_tick_from;		
		$ticket_no = $ticket_id;
		$e = "SELECT response_content FROM email_autoresponses WHERE admin_id='$admin_id' and response_for='close_ticket' and dept_id='$dept'";
		$repM = $this->fetchOne($e, array());	
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				} else {
					//$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'"; 
					if($ticket_type=='enquiry'){
						if($enquiry_dropdown_id != '' && $revisit != ''){
							$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at',ticket_closed_by = '$user_id',enquiry_dropdown_id='$enquiry_dropdown_id',revisit='$revisit',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
						}elseif($enquiry_dropdown_id != '' && $revisit == ''){
							$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at',ticket_closed_by = '$user_id',enquiry_dropdown_id='$enquiry_dropdown_id',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
						}else{
							$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";
						}
				   }else{
					   $qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";
				   }            
					$result = $this->db_query($qry, $params);
					return $result;
					exit;	
				}				
				//$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$dmin_id' and user_id='$admin_id' ";
				$qry = "SELECT sig_content FROM email_signatures WHERE admin_id='$admin_id' and dept_id='$dept'";
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
				//$messagetoSend = $repM.'<br> <br>'.$mess;
				$messagetoSend = $repM;			
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
				$uss = array("ticket_to"=>$tic_from_value[0][0],"ticket_cc"=>$ticket_cc,"ticket_bcc"=>$ticket_bcc,"from"=>$from,"message"=>$messagetoSend,"subject"=>$subject,"ticket_id"=>$ticket_no,"message_id"=>$message_id);	
		//print_r($uss);exit;
				//$autoRespns = $this->autoResponseEmail($uss);
		//print_r($autoRespns); exit;
		if($alert_status==1){
			$autoRespns = $this->autoResponseEmail($uss);
		}
		//$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";
		if($ticket_type=='enquiry'){
			if($enquiry_dropdown_id != '' && $revisit != ''){
				$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at',ticket_closed_by = '$user_id',enquiry_dropdown_id='$enquiry_dropdown_id',revisit='$revisit',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
			}elseif($enquiry_dropdown_id != '' && $revisit == ''){
				$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at',ticket_closed_by = '$user_id',enquiry_dropdown_id='$enquiry_dropdown_id',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
			}else{
				$qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";
			}
		}else{
			   $qry = "UPDATE `external_tickets` SET ticket_status = '9',closed_at = '$closed_at', ticket_closed_by = '$user_id' WHERE ticket_no = '$ticket_id'";
		}             
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
			
			 	$qry = "UPDATE `external_tickets` SET ticket_status = '5',ticket_assigned_to ='$get_dep',unassign=0 WHERE ticket_no = '$ticket_id'";    

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
	
	/*public function onchangeDepartment($data){
        extract($data);//print_r($data);exit;
		$override=$this->fetchOne("SELECT override FROM `admin_details` where admin_id='$admin_id'",array());
        if($override==0){
			$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department_id'",array());		
	        $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$get_dep',unassign='0' where ticket_no='$ticket_id'";
	        $update_data = $this->db_query($qry, array());
	        $result = $update_data == 1 ? 1 : 0;  
	        return $result;
        }else{
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
			$next_array_val = ($next_array_val) ? $next_array_val :  
			$dept_users[0]; 
			$assugnTo = explode(',',$assugnTo);
			$assugnTo = implode(',',$assugnTo);
            $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$assugnTo',next_assign_for='$next_array_val' where ticket_no='$ticket_id'";
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
    }*/
	
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
	
public function onchangeDepartment($data){
        extract($data);//print_r($data);exit;
        file_put_contents('check.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);
        $admin_id=$this->fetchOne("SELECT admin_id FROM `external_tickets` where ticket_no='$ticket_id'",array());        
		$override=$this->fetchOne("SELECT override FROM `admin_details` where admin_id='$admin_id'",array());
		$ticket_limit=$this->fetchOne("SELECT ticket_limit FROM `admin_details` where admin_id='$admin_id'",array());
		$customer_id=$this->fetchOne("SELECT customer_id FROM `external_tickets` where ticket_no='$ticket_id'",array());
		$department_name=$this->fetchOne("SELECT department_name FROM `departments` where dept_id='$department_id'",array());
		$ticket_type=$this->fetchOne("SELECT type FROM `external_tickets` where ticket_no='$ticket_id'",array());
		//file_put_contents('check.txt', $ticket_type.PHP_EOL , FILE_APPEND | LOCK_EX);
		if($ticket_type=='cms'){
        	file_put_contents('check.txt', $ticket_type.'curl'.PHP_EOL , FILE_APPEND | LOCK_EX);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
			  "operation": "agents",
			  "moduleType": "agents",
			  "api_type": "web",
			  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
			  "element_data": {
			    "action": "update_customerdetails",
			    "customer_id":"'.$customer_id.'",
			    "department_id":"'.$department_id.'",
			    "department_name":"'.$department_name.'"
			  }
			}',
			CURLOPT_HTTPHEADER => array(
			  'Content-Type: application/json'
			),
			));
			$response = curl_exec($curl);
			if (curl_errno($curl)) {
                  $te = 'Error:' . curl_error($curl);
                  file_put_contents('check.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);
            }
			curl_close($curl);
		}
        if($override==0){
			$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department_id'",array());		
	        $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$get_dep',unassign='0' where ticket_no='$ticket_id'";
	        $update_data = $this->db_query($qry, array());
	        $result = $update_data == 1 ? 1 : 0;	          
	        return $result;
        }else{
        	$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department_id'",array());
			if($get_dep!=''){
				//$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($department_id)",array());
				$dept_users = explode(',',$get_dep);
				$dept_users = array_unique($dept_users);
				$lastExt = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$department_id' ORDER BY ticket_no DESC LIMIT 1",array());
				$assignNext = $lastExt['next_assign_for'];				
				$ticket_from = $lastExt['ticket_from'];
				if($assignNext==0 || $assignNext==''){
					$assugnTo = $dept_users[0];	
				}
				else{
					$assugnTo = $assignNext;
				}
				/*if($assignNext){
					$assugnTo = $assignNext;
				} else{
					$assugnTo = $dept_users[0];
				}*/
				$current_array_val = array_search($assugnTo, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				/*$next_array_val = ($next_array_val) ? $next_array_val :  
				$dept_users[0]; 
				$assugnTo = explode(',',$assugnTo);
				$assugnTo = implode(',',$assugnTo);*/
				if($next_array_val==''){						
						$next_array_val=$dept_users[0];						
				}
				if($ticket_limit=='0'){	 	
				    $next_assign = $next_array_val;
				}else{	
				    $cnt = $current_array_val;
					$us = $dept_users[$cnt];
					$dep_count = count($dept_users);	   
					for($i=$cnt;$i<$dep_count;$i++){
					    $j = $dept_users[$i];		    		    
						$user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$j' AND `ticket_status`=3 AND ticket_department='$department_id' AND unassign=1 AND delete_status=0",array());		
						if($user_ticket_count < $ticket_limit){				
							$assugnTo = $j;
							$next_assign = $dept_users[$i+1];				    
							//$next_assign = ($next_assign) ? $next_assign :  $dept_users[0];
							if($next_assign==''){						
								$next_assign=$dept_users[0];				
							}				    
							break;
						}else{			
							$next_assign='';	        	
						}
					}
				}
				if($next_assign!=''){
                   $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$assugnTo',next_assign_for='$next_assign',unassign=1 where ticket_no='$ticket_id'";
                   $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$assugnTo'";
				   $createdby = $this->fetchmydata($createdby_qry,array());
				}else{
				  $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$get_dep',next_assign_for='',unassign=0 where ticket_no='$ticket_id'";
				  $createdby_qry = "SELECT department_name FROM departments WHERE dept_id='$department_id'";
				  $createdby = $this->fetchmydata($createdby_qry,array());
			    }
				$admin_name = "SELECT agent_name FROM user WHERE user_id='$admin_id'";
				$admin_name = $this->fetchmydata($admin_name,array());		
				$sub = $admin_name.' Assigned a ticket to '.$createdby;
				$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$ticket_from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$us = $this->send_notification($adm);		
				$update_data = $this->db_query($qry, $params);
				$result = $update_data == 1 ? 1 : 0;  
				return $result;
		    }else{
			    $qry = "UPDATE external_tickets SET ticket_department='$department_id',ticket_assigned_to='$get_dep',next_assign_for='',unassign=0 WHERE ticket_no='$ticket_id'";
			    $depName=$this->fetchOne("SELECT department_name FROM `departments` where dept_id='$department_id'",array());	
			    $admin_name = "SELECT agent_name FROM user WHERE user_id='$admin_id'";            
	      		$admin_name = $this->fetchmydata($admin_name,array());		
				$sub = $admin_name.' Assigned a ticket to '.$depName;
				$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$ticket_from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
				$us = $this->send_notification($adm);		
	            $update_data = $this->db_query($qry, $params);
	            $result = $update_data == 1 ? 1 : 0;  
	            return $result;
			}
        } // override else
    }	


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
$fwd = $forward_cc;
if($forward_from == ''){				  
  preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $from_array);
  $cusmail = $from_array[0][0];	
  $get_domain = explode('@',$from_array[0][0]);	
  if($get_domain[1] != 'gmail.com' && $get_domain[1] != 'yahoo.com' && $get_domain[1] != 'hotmail.com'){	
   $customer_domain = $get_domain[1];
  }else{
   $customer_domain = '  ';	  
  }
}else{				  
  preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_from, $from_array);
  $cusmail = $from_array[0][0];	
  $get_domain = explode('@',$from_array[0][0]);
  $customer_domain = $get_domain[1];
  if($get_domain[1] != 'gmail.com' && $get_domain[1] != 'yahoo.com' && $get_domain[1] != 'hotmail.com'){	
   $customer_domain = $get_domain[1];
  }else{
   $customer_domain = '  ';	  
  }	
}
preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $to_mail, $exp_to_mail);
$tomailARR = array_unique($exp_to_mail[0]);
preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $cc_mail, $exp_cc_mail);
$ccmailARR = array_unique($exp_cc_mail[0]);	
//file_put_contents('dat1.txt', print_r($ccmailARR,true).PHP_EOL , FILE_APPEND | LOCK_EX);	
$rrFrom	= $from;
$fwdto	= $forward_to;
$fwdfrom	= $forward_from;
$explode0 = strip_tags($forward_from);	
$explode1 = str_replace('&lt;', '<', $explode0);
$explode2 = str_replace('&gt;', '>', $explode1);
$explode3 = str_replace('<', '< ', $explode2);
$explode4 = str_replace('>', ' >', $explode3);
//file_put_contents('dat.txt', $explode4.PHP_EOL , FILE_APPEND | LOCK_EX);	
// Word Filter
if($from != 'ganesh.mahamuni@stationsatcom.com'){
$qry = "select filter_word from email_words_filtering where user_id ='64'";
$result =  $this->dataFetchAll($qry, array());
	for($j = 0; $j < count($result); $j++){
		$groupArr = $result[$j]['filter_word'];
		if (strpos($message, $groupArr) !== false) {
			echo 'Spam Word true'; 
			exit;
		}
		if (strpos($subject, $groupArr) !== false) {
			echo 'Spam Word trued'; 
			exit;
		}
	}
}	 
// Word Filter
// priority filter
$qry = "select priority,key_word from priority_words_filtering where admin_id ='64'";
$result =  $this->dataFetchAll($qry, array());
$priorityArr = array();	
//print_r($result);exit;
	for($j = 0; $j < count($result); $j++){
		$groupArr = explode(',',$result[$j]['key_word']);
		$priid = $result[$j]['priority'];		
		for($k = 0; $k < count($groupArr); $k++){
			if (strpos($subject, $groupArr[$k]) !== false) {
				array_push($priorityArr,$priid);				
			}else{
				if (strpos($message, $groupArr[$k]) !== false) {
					array_push($priorityArr,$priid);				
				}
			}
	    }
	}
$priorityid = $priorityArr[0];
//file_put_contents('dat.txt', $priorityid.PHP_EOL , FILE_APPEND | LOCK_EX);exit;		
// priority filter	
$to_rep1 = str_replace('[','',$to_mail);
$to_rep2 = str_replace(']','',$to_rep1);
$to_rep3 = str_replace('"','',$to_rep2);	
$to_rep4 = str_replace(',', '|', $to_rep3);
$Toreparr = explode(',',$to_rep3);	
 	
$rep1 = str_replace('[','',$to);
$rep2 = str_replace(']','',$rep1);
$rep3 = str_replace('"','',$rep2);	
$rep4 = str_replace(',', '|', $rep3);
$Toarr = explode('|',$rep4);
$Toarr = array_map('strtolower', $Toarr);	
//file_put_contents('dat.txt', print_r($Toarr,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
$aliasArr = array();
$alias_qry = "SELECT aliseEmail FROM department_emails WHERE status=1";
$alias_values = $this->dataFetchAll($alias_qry,array());
for($a=0;$a<count($alias_values);$a++){
 $aval = $alias_values[$a]['aliseEmail'];
 array_push($aliasArr,$aval);
}
$aliasArr = array_map('strtolower', $aliasArr);
$resultArr = array_intersect($Toarr, $aliasArr);
$get_pipe_alias = implode(' ',$resultArr);	
$ccArray = array_diff($Toarr,$aliasArr);
$ccArray_implode = implode(',',$ccArray);	
preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $from_email_exp);	
$from_val = $from_email_exp[0][0];
//$te = "SELECT user_id FROM `user` WHERE email_id='$from_val' AND admin_id=64";	
//file_put_contents('dat.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;	
$check_user=$this->fetchOne("SELECT user_id FROM `user` WHERE email_id='$from_val' AND admin_id=64",array());	
$emailArr = array();
$agent_assign='';
//file_put_contents('dat.txt', $check_user.PHP_EOL , FILE_APPEND | LOCK_EX);exit;		
if($check_user!=''){   	
   $explode_to = explode('|', $rep4);     	
   for($i=0;$i<count($explode_to);$i++){
	//file_put_contents('dat.txt', $i.PHP_EOL , FILE_APPEND | LOCK_EX);exit;   
   	$mailid = $explode_to[$i];
     $check_email=$this->fetchOne("SELECT email_id FROM `user` WHERE email_id='$mailid'",array());	   
     if($check_email!=''){
     	array_push($emailArr,$check_email);
     }
   }
   	$counts = count($emailArr);	
   if($counts >= 0){
	 if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
		 //$te = 're';
		 //file_put_contents('dat.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
	   $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$get_pipe_alias'";    
       $to_original = $this->fetchOne($qry,array());	 
	   $arrresult=array_diff($explode_to,$emailArr);	   
	   $position = array_search($to_original, $arrresult);
	   unset($arrresult[$position]);	   	 
	   $emailto = $Toreparr[0];	   
	   $from = $emailto;	   
	   if(empty($arrresult)){	     
		 $cc = '';  
	   }else{
		 array_shift($arrresult);
		 if(empty($arrresult)){	     
		   $cc = '';  
	     }else{  
		   $change_cc_format = '("'. implode('","', $arrresult) .'")';
		   $cc = $change_cc_format;
		 }
	   }
	   //array_shift($arrresult); 
	   //file_put_contents('dat.txt', print_r($arrresult,true).PHP_EOL , FILE_APPEND | LOCK_EX);
	   //file_put_contents('over.txt', $emailto.PHP_EOL , FILE_APPEND | LOCK_EX);	 
	   //exit;	 
	   //$position_val = array_search('isales@cal4care.com', $arrresult);
       //unset($arrresult[$position_val]);
	   //$cc_mail = json_encode($arrresult);	 
	   //file_put_contents('dat.txt', $cc_mail.PHP_EOL , FILE_APPEND | LOCK_EX);exit;	        
	   $agent_assign = $check_user;	   
     }
	 elseif(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:"){
		 //$te = 'fw';
		 //file_put_contents('dat.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
		   if($agent_short_code==''){
			   $from = $explode4;
			   /*$explode = explode('&lt;', $forward_from);
			   $rep = explode('&gt', $explode[1]);
			   $from = $rep[0];*/
			   $agent_assign = '';
			   $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$rep3'";    
			   $to_original = $this->fetchOne($qry,array());
		   }else{
			   $from = $explode4;
			   /*$explode = explode('&lt;', $forward_from);
			   $rep = explode('&gt', $explode[1]);
			   $from = $rep[0];*/
			   $to = $rep3;
			   $agent_assign = $this->fetchOne("SELECT user_id FROM `user` WHERE agent_name='$agent_short_code'",array());
			   $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$rep3'";    
			   $to_original = $this->fetchOne($qry,array());
		   }
		   //file_put_contents('dat.txt', $from.PHP_EOL , FILE_APPEND | LOCK_EX);
	   }  
	 else{
		 //$te = 'el';
		 //file_put_contents('dat.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
	   $from = $from;
	   $cc = str_replace('[','(',$cc_mail);
	   $cc = str_replace(']',')',$cc);
	   $cc = str_replace('(','',$cc);
       $cc = str_replace(')','',$cc);
       $cc = str_replace('"','',$cc);	 
	   //$qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail REGEXP '$rep4'"; 
	   $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$Toarr[0]'";   
       $to_original = $this->fetchOne($qry,array());
	 }
   }else{
	   $from = $from;
	   $cc = str_replace('[','(',$cc_mail);
	   $cc = str_replace(']',')',$cc);
	   $cc = str_replace('(','',$cc);
       $cc = str_replace(')','',$cc);
       $cc = str_replace('"','',$cc);
	   //$qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail REGEXP '$rep4'";  
	   $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$Toarr[0]'";  
       $to_original = $this->fetchOne($qry,array());	     
   }
   if($to_original==''){
      $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$get_pipe_alias'";    
      $to_original = $this->fetchOne($qry,array());
   }	
}else{		
	$from = $from;	
	$cc = $ccArray_implode;
    $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$Toarr[0]'";    
    $to_original = $this->fetchOne($qry,array());		
}
//file_put_contents('dat.txt', $from.PHP_EOL , FILE_APPEND | LOCK_EX);
if($to_original==''){
  $qry = "SELECT aliseEmail FROM `department_emails` WHERE aliseEmail = '$get_pipe_alias'";    
  $to_original = $this->fetchOne($qry,array());
}	
file_put_contents('dat.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);
$override=$this->fetchOne("select override from admin_details where admin_id='64'",array());
if($override==0){
	    //file_put_contents('dat.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
        $spam_status = '0';
		$explode = explode('<',$rrFrom);
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
		$fattachments = implode(',',$fattachments);		
		$to = str_replace('[','(',$to_mail);
		$to = str_replace(']',')',$to);
		//print_r($to); 
		//$cc = str_replace('[','(',$cc_mail);
		//$cc = str_replace(']',')',$cc);		
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
		 $qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$to2%' and status = '1'";		
         $qry_value = $this->fetchOne($qry,array());			
		 //$to2 = $qry_value;
			$to3 = $qry_value;
		 $to = str_replace($alise,$to2,$to);
			if($to==''){
				    $to=$to_original;
			}
		} else { 			
		 $to2 = str_replace('[','',$to_mail[0]);
		 $to2 = str_replace(']','',$to2);
		 $to2 = str_replace('"','',$to2);
		 if($to2==''){
				    $to2=$to_original;
			}
			$to3 = $to2;
		}
		if($to3==''){
		 $to3 = 'omni@pipe.mconnectapps.com';
		}			
		$qry = "SELECT * FROM `admin_details` WHERE `support_email` LIKE '%$to3%'";
        $qry_value = $this->fetchData($qry,array());		
        if($qry_value > 0){			
			if($to2==''){
				    $to2=$to_original;
			}
			
			$dept = $this->fetchOne("SELECT departments FROM department_emails where aliseEmail='$to_original'",array());
			$admin_id = $qry_value['admin_id'];		
		    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
            $user_qry_value = $this->fetchmydata($user_qry,array());
	        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
            $user_timezone = $this->fetchmydata($user_timezone_qry,array());
            date_default_timezone_set($user_timezone);  
     	    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
			if(strpos($subject, 'Re: Fw:') !== false || strpos($subject, 'Re: FW:') !== false){
			   $sub = substr($subject, 4);
			   //$expfrom = explode('<',$from);
               //$strfm = str_replace('>', '', $expfrom[1]);
			   preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $expfrom);
			   $strfm = $expfrom[0][0];	
			}
			/*elseif(strpos($subject, 'Re:') !== false){
				$sub = substr($subject, 4);
			}*/
			else{
			   if(strpos($subject, 'Re:') !== false || strpos($subject, 'RE:') !== false){
				   $subcut = substr($subject, 4);				   
				   $tno = $this->fetchOne("SELECT ticket_no FROM external_tickets WHERE ticket_subject = '$subcut'",array());
				   if($tno==''){
				     $sub = $subject;
				   }else{
				     $sub = substr($subject, 4);
				   }
			   }else{
			       $sub = $subject;
			   }
			   preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $expfrom);
			   $strfm = $expfrom[0][0];			   
			   /*$sub = $subject;	
			   $expfrom = explode('<',$from);
			   if(empty($expfrom)){	
			    $strfm = $from;	
			   }else{
				$strfm = str_replace('>', '', $expfrom[1]);   
			   }*/			   
            }
		    //file_put_contents('dat.txt', $strfm.$subject.PHP_EOL , FILE_APPEND | LOCK_EX);exit;	
			$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$sub'";
       		$ticket_no = $this->fetchOne($qry,array());
			//file_put_contents('dat.txt', $ticket_no.$qry.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
			if($ticket_no!=''){
				$replyCount=$this->fetchOne("SELECT COUNT(ticket_message_id) FROM `external_tickets_data` WHERE ticket_id='$ticket_no' AND repliesd_by='Agent'",array());
				$depqry = "SELECT ticket_department FROM external_tickets WHERE ticket_not = '$ticket_no'";
       		    $depqry_val = $this->fetchOne($depqry,array());
			//if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_to, $forward_to_array);
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_cc, $forward_cc_array);
				$ccArr = array_merge($forward_to_array[0], $forward_cc_array[0]);
				$cc = implode(',',$ccArr);
				//$subject = substr($subject, 4);
				$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$sub'";
       			$ticket_no = $this->fetchOne($qry,array());				
				$ticket_no = $this->get_string_between($sub, '[##', ']');			
				$get_dep=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_subject='$subject'",array());				
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				$qryss = "UPDATE `external_tickets` SET status_del = '1',ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qryss = "UPDATE `external_tickets_data` SET ticket_reply_id ='$ticket_reply_id' WHERE ticket_id = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);
                if($attachments==''){
					  $attachments = $fattachments;
				}
				if($replyCount==0){                	
					$get_all_replied_to=$this->fetchData("SELECT all_replied_to,all_replied_cc FROM `external_tickets_data` WHERE ticket_id='$ticket_no' ORDER BY ticket_message_id ASC LIMIT 1",array());
					$existing_all_replied_to = $get_all_replied_to['all_replied_to'];
					$explode3 = explode(',',$existing_all_replied_to);			
					$existing_all_replied_cc = $get_all_replied_to['all_replied_cc'];
					if($existing_all_replied_cc!=''){
					 $explode4 = explode(',',$existing_all_replied_cc);
					}else{
					 $explode4 = array();
					}
					$expresult1 = array_diff($tomailARR,$explode3);
					//$expresult11 = array_map('strtolower', $expresult1);
					$expresult2 = array_diff($ccmailARR,$explode4);//print_r($expresult2);exit;
					if(count($expresult1)==0){
					  $all_replied_to = $existing_all_replied_to;
					}else{
					  $fetchqry = "SELECT ticket_to FROM external_tickets WHERE ticket_no = '$ticket_no'";
       			      $tto = $this->fetchOne($fetchqry,array());					  
					  $mergeArray1 = array_merge($explode3,$expresult1);					  	
					  $pos = array_search(strtolower($tto), $mergeArray1);					  
					  unset($mergeArray1[$pos]);	
					  $all_replied_to = implode(',',$mergeArray1);
					  //file_put_contents('vai.txt', $all_replied_to.PHP_EOL , FILE_APPEND | LOCK_EX);	
					}
					if(count($expresult2)==0){
					  $all_replied_cc = $existing_all_replied_cc;
					}else{
					  $mergeArray2 = array_merge($explode4,$expresult2);
					  $all_replied_cc = implode(',',$mergeArray2);	
					}
					//file_put_contents('dat.txt', $all_replied_to.'||'.$all_replied_cc.PHP_EOL , FILE_APPEND | LOCK_EX);
				}else{					
					$get_all_replied_to=$this->fetchData("SELECT all_replied_to,all_replied_cc FROM `external_tickets_data` WHERE ticket_id='$ticket_no' ORDER BY ticket_message_id DESC LIMIT 1",array());
					$existing_all_replied_to = $get_all_replied_to['all_replied_to'];
					$explode3 = explode(',',$existing_all_replied_to);			
					$existing_all_replied_cc = $get_all_replied_to['all_replied_cc'];
					$explode4 = explode(',',$existing_all_replied_cc);
					$expresult1 = array_diff($tomailARR,$explode3);
					$expresult2 = array_diff($ccmailARR,$explode4);//print_r($expresult2);exit;
					if(count($expresult1)==0){
					  $all_replied_to = $existing_all_replied_to;
					}else{
					  $fetchqry = "SELECT ticket_to FROM external_tickets WHERE ticket_no = '$ticket_no'";
       			      $tto = $this->fetchOne($fetchqry,array());	
					  $mergeArray1 = array_merge($explode3,$expresult1);
					  $pos = array_search(strtolower($tto), $mergeArray1);					  
					  unset($mergeArray1[$pos]);	
					  $all_replied_to = implode(',',$mergeArray1);	
					}
					if(count($expresult2)==0){
					  $all_replied_cc = $existing_all_replied_cc;
					}else{
					  $mergeArray2 = array_merge($explode4,$expresult2);
					  $all_replied_cc = implode(',',$mergeArray2);	
					}
					//file_put_contents('dat1.txt', $all_replied_to.'||'.$all_replied_cc.PHP_EOL , FILE_APPEND | LOCK_EX);
				}
				$maillcc = implode(',',$ccmailARR);			
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt,all_replied_to,all_replied_cc) VALUES ( '$ticket_no','$mssg','$subject','$strfm','$to_original','$maillcc','$attachments','$ticket_reply_id','$created_at','$all_replied_to','$all_replied_cc')", array());
				// $dt = date('Y-m-d H:i:s');
				// $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$dt')", array());
				foreach($dept_users as $user){
					$this->db_query("Insert into mc_event (user_id,admin_id,mc_event_key,mc_event_data,mc_event_type,event_status,created_dt) values('$user','$admin_id','$ticket_no','Reply From $from  ($subject)','11','7','$created_at')", array());
					
					$us = array("user_id"=>$user,"ticket_for"=>"Reply Ticket","ticket_from"=>$from,"ticket_subject"=>$subject, "ticket_id"=>$ticket_no);
					$u[] = $this->send_notification($us);
				}
				$qry = "SELECT sig_content FROM email_signatures WHERE admin_id='$admin_id' and dept_id='$depqry_val'";
				$mailSignature = $this->fetchOne($qry,array());				
				if($mailSignature != ''){	
					$repM =  $mailSignature;
				}else{
					$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
					$mailSignature = $this->fetchOne($qry,array());
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
				// $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `emailAddr` LIKE '%$to3%'";
				$senderid_qry = "SELECT senderID FROM `department_emails` WHERE `aliseEmail` = '$to2' and status = '1'";
                $senderid_qry_value = $this->fetchOne($senderid_qry,array());		        
				$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				//print_r($uss); exit;
      			$autoRespns = $this->autoResponseEmail($uss);
			//}
			}
			else {	
				if($agent_assign==''){
					$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
					$dept_users = explode(',',$get_dep);
					//$dept_users[] = $admin_id;
					$dept_users = array_unique($dept_users);				
					$assugnTo = $dept_users;
					$ticket_status=3;
					$unassign=0;
				}else{
					$get_dep = $agent_assign;
					$ticket_status=1;
					$unassign=1;					
				}						
				if($to2==''){
				    $to2=$to_original;
				}
				if($priorityid==''){
				 $priorityid = 2;
				}
// adding customer details
$get_ticketcustomer_bymail = "SELECT customer_id,customer_name FROM ticket_customer WHERE customer_email LIKE '%$cusmail%'";
$get_ticketcustomer_bymail_values =  $this->fetchData($get_ticketcustomer_bymail, array());
if($get_ticketcustomer_bymail_values > 0){
  $ticket_customer_id = $get_ticketcustomer_bymail_values['customer_id'];
  $ticket_customer_name = $get_ticketcustomer_bymail_values['customer_name'];
}
else{
  $get_ticketcustomer_bydomain = "SELECT customer_id,customer_name FROM ticket_customer WHERE customer_email LIKE '%$customer_domain%'";
  $get_ticketcustomer_bydomain_values =  $this->fetchData($get_ticketcustomer_bydomain, array());
  if($get_ticketcustomer_bymail_values > 0){
    $ticket_customer_id = $get_ticketcustomer_bymail_values['customer_id'];
    $ticket_customer_name = $get_ticketcustomer_bymail_values['customer_name'];
  }else
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "operation": "agents",
        "moduleType": "agents",
        "api_type": "web",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
        "element_data": {
            "action": "get_customerdetails",
            "customer_domain":"'.$customer_domain.'"
        }
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $explode_customer_details = explode('||',$response);
    $ticket_customer_id = $explode_customer_details[0];
    $ticket_customer_name = $explode_customer_details[1];
    $ticket_customer_code = $explode_customer_details[2];
    $ticket_customer_email = $explode_customer_details[3];
    $ticket_customer_country = $explode_customer_details[4];
    $ticket_customer_phone = $explode_customer_details[5];
    if($ticket_customer_id != ''){
     $insertQry = $this->db_insert("INSERT INTO ticket_customer(admin_id,customer_id,customer_code,customer_name,customer_email,phone_number,country) VALUES ('$admin_id','$ticket_customer_id','$ticket_customer_code','$ticket_customer_name','$ticket_customer_email','$ticket_customer_phone','$ticket_customer_country')", array());
    }
  }
}
// adding customer details
if(strpos($subject, 'Fwd:') !== false || strpos($subject, 'Fw:') || strpos($subject, 'FW:')){
  $subject = substr($subject, 4);
  $subject = ltrim($subject);	
}else{
  $subject = $subject;
}
if(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:" || substr( $subject, 0, 3 ) === "Fwd:"){
 preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $rrFrom, $check_value1);
 $check_from1 = $check_value1[0][0];
 preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $fwdto, $check_value2);
 $check_from2 = $check_value2[0][0];
 preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $fwdfrom, $check_value3);
 $check_from3 = $check_value3[0][0];	
 if($check_from1==$check_from2){
   $from = $check_from3;
 }else{
   $from = $check_from2;
 }	
 preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $fwd, $forward_to_value);	
 $fwdcc = $forward_to_value[0][0];
}				
			    $ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam,unassign,customer_id,customer_name) VALUES ( '$from','$admin_id','$ticket_status','$priorityid','$subject','$to_original','$get_dep','','$dept','$created_at','$updated_at',1,'$spam_status','$unassign','$ticket_customer_id','$ticket_customer_name')", array());						
				$subject = $subject.' [##'.$ticket_no.']';
				$qry = "UPDATE `external_tickets` SET  `ticket_subject` = '$subject' WHERE `ticket_no` = '$ticket_no'";
                $resultss = $this->db_query($qry, $params);	
				$get_alise=$this->fetchOne("SELECT aliseEmail FROM `department_emails` where emailAddr='$to2'",array());
				if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
					$maillcc = implode(',',$ccmailARR);
				    $qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt,all_replied_to,all_replied_cc) VALUES ( '$ticket_no','$message','$subject','$Toarr[0]','$to_original','$maillcc','$attachments','$ticket_reply_id','$created_at','$Toarr[0]','$cc')", array());
				}elseif(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:"){
					$expfrom = explode('<',$from);
                    $str_fm = str_replace('>', '', $expfrom[1]);
					preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_to, $forward_to_array);
					preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_cc, $forward_cc_array);
					//$ccArr = array_merge($forward_to_array[0], $forward_cc_array[0]);
					//$cc = implode(',',$ccArr);
					if($attachments==''){
					  $attachments = $fattachments;
					}
					$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt,all_replied_to,all_replied_cc) VALUES ( '$ticket_no','$message','$subject','$from','$to_original','$check_from1','$attachments','$ticket_reply_id','$created_at','$from','$check_from1')", array());
				}
				else{
					$maillcc = implode(',',$ccmailARR);
				    if($attachments==''){
					  $attachments = $fattachments;
					}					
					$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt,all_replied_to,all_replied_cc) VALUES ( '$ticket_no','$message','$subject','$from','$to_original','$maillcc','$attachments','$ticket_reply_id','$created_at','$from','$cc')", array());
				}
				
				if($spam_status > 0){
					echo 'SpamListed';
						exit;
				}				
				$dt = date('Y-m-d H:i:s');						
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
				$qry = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='created_ticket' and dept_id='$dept'";
				$repM = $this->fetchOne($qry,array());				
				if($repM !=''){
					$tickNo = '[##'.$ticket_no.']';
					$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
				    //$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id' ";
				    //$mailSignature = $this->fetchOne($qry,array());
				    $qry = "SELECT sig_content FROM email_signatures WHERE admin_id='$admin_id' and dept_id='$dept'";			
				    $mailSignature = $this->fetchOne($qry,array());
				    if($mailSignature != ''){	
					  $repM =  $repM.$mailSignature;
				    }else{
						$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id' and user_id='$admin_id'";
				        $mailSignature = $this->fetchOne($qry,array());
						$repM =  $repM.$mailSignature;
					} 	
				    $repM = '<div style="font-family: verdana !important;">'.$repM.'</div>';
				    $messages = $this->getTicketThread($ticket_no);
				    foreach($messages as $m){
					 $mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
				    }
				    $mess = implode('<br>',$mess);
				    //$messagetoSend = $repM.'<br> <br>'.$mess;
				    $messagetoSend = $repM;
                    $ticket_dep_user = $this->fetchOne("SELECT department_users FROM `departments` WHERE dept_id='$dept' ",array());
                    $uservals="'" . str_replace(",","','",$ticket_dep_user)."'";
                    $agent_alert_qry = "SELECT email_id FROM user WHERE new_email_alert=1 AND admin_id='$admin_id' AND user_id IN ($uservals)";
			        $agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());  	  		
				    $agentArr = array();
				    for($k = 0; $k < count($agent_alert_email); $k++){
				     $agent_emails = $agent_alert_email[$k]['email_id'];
				     array_push($agentArr, $agent_emails);
				    }
				    $group_alert_qry = "SELECT email FROM email_group WHERE new_email_alert=1 AND admin_id='$admin_id'";
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
				    $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `aliseEmail` = '$to_original' and status = '1'";
			        $senderid_qry_value = $this->fetchOne($senderid_qry,array());
			        $toAdd_qry = "SELECT ticket_from FROM `external_tickets` WHERE `ticket_no` = '$ticket_no'";
			        $toAdd = $this->fetchOne($toAdd_qry,array());
			        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $toAdd, $toAdd_pregmatch);
			        $dept_qry = "SELECT customer_name,ticket_department FROM `external_tickets` WHERE `ticket_no` = '$ticket_no'";
			        $dept_res = $this->fetchData($dept_qry,array());					
					$customer_name=$dept_res['customer_name'];
					$ticket_department=$dept_res['ticket_department'];					
					$department_name_qry = "SELECT department_name FROM `departments` WHERE `dept_id` = '$ticket_department'";
			        $department_name = $this->fetchOne($department_name_qry,array());
					if($replied_from==''){
					  $replied_from = $toAdd;
					}
					if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
                      $ticket_cc = $ccs;
					}
					elseif(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:" || substr( $subject, 0, 3 ) === "Fwd:"){
                      $ticket_cc = $this->fetchOne("SELECT replied_cc FROM `external_tickets_data` WHERE ticket_id='$ticket_no' ",array());
					}
					else{
                      $ticket_cc = $ccArray_implode;
					}			
				    $uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ticket_cc,"ticket_bcc"=>"","from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);									
				    $autoRespns = $this->autoResponseEmail($uss);
					if($agent_assign!=''){
					  $e = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='agent_template' and dept_id='$dept'";
					  $repM = $this->fetchOne($e, array());   
					  if($repM !=''){
						$tickNo = '[##'.$ticket_no.']';
						$createdby=$this->fetchOne("SELECT agent_name FROM `user` where user_id='$agent_assign'",array());
						$user_name=$this->fetchOne("SELECT agent_name FROM `user` WHERE user_id='$check_user'",array());  
						$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
						$repM = str_replace('{%Cassign_to%}',$createdby,$repM);
						$repM = str_replace('{%Cassign_by%}',$user_name,$repM); 
						$ticket_to = $this->fetchOne("SELECT email_id FROM `user` where user_id='$agent_assign'",array());
						$subject = "New Ticket Alert";
						$uss = array("ticket_to"=>$ticket_to,"ticket_cc"=>"","ticket_bcc"=>"","from"=>$senderid_qry_value,"message"=>$repM,"subject"=>$subject,"ticket_id"=>$tickNo,"message_id"=>"");						
						$autoRespns = $this->autoResponseEmail($uss);
						}
					}
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
	    //file_put_contents('dat.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit; 
	    //file_put_contents('dat.txt', $from.'-'.$to_original.'-'.$from_val.'-'.$cc_mail.'-'.$subject.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
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
	    //$fattachments = json_decode($data['fattachments']);
		$fattachments = implode(',',$fattachments);
	    //file_put_contents('ve.txt', $fattachments.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
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
	    $qry = "SELECT emailAddr FROM `department_emails` WHERE `aliseEmail` LIKE '%$to_original%' AND status=1";		
        $emailAddr_qry_value = $this->fetchOne($qry,array());		
	    //file_put_contents('dat.txt', $qry.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
	    $qry = "SELECT * FROM `admin_details` WHERE `support_email` LIKE '%$emailAddr_qry_value%'";
        $qry_value = $this->fetchData($qry,array());		
        if($qry_value > 0){
			if($to2==''){
				    $to2=$to_original;
			}
			$dept = $this->fetchOne("SELECT departments FROM department_emails where aliseEmail='$to_original'",array());
			$admin_id = $qry_value['admin_id'];		
		    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
            $user_qry_value = $this->fetchmydata($user_qry,array());
	        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
            $user_timezone = $this->fetchmydata($user_timezone_qry,array());
            date_default_timezone_set($user_timezone);  
     	    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
			//file_put_contents('dat.txt', $subject.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
			if(strpos($subject, 'Re: Fw:') !== false || strpos($subject, 'Re: FW:') !== false){
			   $sub = substr($subject, 4);
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $expfrom);
				$strfm = $expfrom[0][0];
			   //$expfrom = explode('<',$from);
               //$strfm = str_replace('>', '', $expfrom[1]);
			   //$te = 'if';
			   //file_put_contents('dat.txt', $strfm.$te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;	
			}			
			else{
			   if(strpos($subject, 'Re:') !== false || strpos($subject, 'RE:') !== false){
				   $sub = substr($subject, 4);
			   }else{
			       $sub = $subject;
			   }			   
			   /*$expfrom = explode('<',$from);
			   if(empty($expfrom)){	
				   $te = 'if';
			    $strfm = $from;	
			   }else{
				   $te = 'el';
				$strfm = str_replace('>', '', $expfrom[1]);   
			   }*/
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $from, $expfrom);
				$strfm = $expfrom[0][0];
			   //$te = 'el';	
			   //file_put_contents('dat.txt', $strfm.$te.$sub.PHP_EOL , FILE_APPEND | LOCK_EX);exit;	
            }
			$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$sub'";			
       		$ticket_no = $this->fetchOne($qry,array());			
			//file_put_contents('dat.txt', $strfm.'-'.$to_original.'-'.$from_val.'-'.$cc_mail.'-'.$subject.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
			if($ticket_no!=''){
				//$te = 'if';				
				//file_put_contents('dat.txt', $ticket_no.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
			//if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){	
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_to, $forward_to_array);
				preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_cc, $forward_cc_array);
				$ccArr = array_merge($forward_to_array[0], $forward_cc_array[0]);
				$cc = implode(',',$ccArr);
				$subject = substr($subject, 4);
				$qry = "SELECT ticket_id FROM external_tickets_data WHERE ticket_subject = '$sub'";
       			$ticket_no = $this->fetchOne($qry,array());				
				$ticket_no = $this->get_string_between($subject, '[##', ']');			
				$get_dep=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_subject='$subject'",array());				
				$dept_users = explode(',',$get_dep);
				$dept_users[] = $admin_id;
				$qryss = "UPDATE `external_tickets` SET status_del = '1',ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_no'";             
                $resultss = $this->db_query($qryss, $params);			
				$qryss = "UPDATE `external_tickets_data` SET ticket_reply_id ='$ticket_reply_id' WHERE ticket_id = '$ticket_no'";
                $resultss = $this->db_query($qryss, $params);
				$strfm = str_replace(' ', '', $strfm);
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$strfm','$to_original','$cc','$attachments','$ticket_reply_id','$created_at')", array());
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
			//}
			} 
			else {				
				//$te = 'el';
				//file_put_contents('cc.txt', print_r($cc,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				$setting_ary = $this->fetchData("SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'",array());
				//$te = "SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'";
				//file_put_contents('dat.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				$ticket_limit = $setting_ary['ticket_limit'];
				$override = $setting_ary['override'];
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
				//file_put_contents('dat.txt', $get_dep.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				$dept_users = explode(',',$get_dep);
				//$dept_users[] = $admin_id;
				$dept_users = array_unique($dept_users);				
				$lastExt = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$dept' ORDER BY ticket_no DESC LIMIT 1",array());
				$assignNext = $lastExt['next_assign_for'];
				if($assignNext==0 || $assignNext==''){
					$assugnTo = $dept_users[0];	
				}
				else{
					$assugnTo = $assignNext;
				}
				$current_array_val = array_search($assugnTo, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				if($next_array_val==''){						
						$next_array_val=$dept_users[0];
						//file_put_contents('dat.txt', $next_assign.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				}
// ticket limit code				
if(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:"){
	if($agent_short_code==''){
	  $assugnTo = $assugnTo;
	  $next_assign = $next_array_val;
	}else{
	  $getAgent = $this->fetchOne("SELECT user_id FROM `user` WHERE agent_name='$agent_short_code'",array());
	  if($getAgent==''){
	     $assugnTo = $assugnTo;
	     $next_assign = $next_array_val;
	  }else{
		 $assugnTo = $getAgent; 
	     $current_array_val = array_search($assugnTo, $dept_users);
	     $next_assign = $dept_users[$current_array_val+1];
	  }
	}
}
else if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
	if($agent_short_code==''){	  
	  $getDepartment = $this->fetchOne("SELECT departments FROM `department_emails` WHERE aliseEmail='$to_original'",array());
	  $getAgent = $this->fetchOne("SELECT department_users FROM `departments` WHERE dept_id='$getDepartment';",array());
	  $userid_explode = explode(',',$getAgent);
	  if (in_array($check_user, $userid_explode)){
         $assugnTo = $check_user;
         $current_array_val = array_search($assugnTo, $dept_users);
	     $next_assign = $dept_users[$current_array_val+1];
	  }else{
	  	 $assugnTo = $assugnTo;
	  	 $next_assign = $next_array_val;
	  }
	}else{
	  $getAgent = $this->fetchOne("SELECT user_id FROM `user` WHERE agent_name='$agent_short_code'",array());
	  if($getAgent==''){
	     $assugnTo = $assugnTo;
	     $next_assign = $next_array_val;
	  }else{
		 $assugnTo = $getAgent; 
	     $current_array_val = array_search($assugnTo, $dept_users);
	     $next_assign = $dept_users[$current_array_val+1];
	  }
	}
}				
else{		
	if($ticket_limit=='0'){	 	
	   $next_assign = $next_array_val;
	}else{	
		   $cnt = $current_array_val;
		   $us = $dept_users[$cnt];
		   $dep_count = count($dept_users);	   
		   for($i=$cnt;$i<$dep_count;$i++){
				$j = $dept_users[$i];		    		    
				$user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$j' AND `ticket_status`=3 AND ticket_department='$dept' AND unassign=1 AND delete_status=0",array());
			   $qr = "SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$j' AND `ticket_status`=3 AND ticket_department='$dept'  AND unassign=1 AND delete_status=0";
			   //file_put_contents('dat.txt', $user_ticket_count.$qr.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
				if($user_ticket_count < $ticket_limit){	
					//file_put_contents('dat.txt', $user_ticket_count.$qr.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
						$assugnTo = $j;
						$next_assign = $dept_users[$i+1];				    
						//$next_assign = ($next_assign) ? $next_assign :  $dept_users[0];
						if($next_assign==''){						
							$next_assign=$dept_users[0];
							//file_put_contents('dat.txt', $next_assign.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
						}				    
						break;
				}else{
					//file_put_contents('dat.txt', 'el'.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
						$next_assign='';	        	
				}
		   }
	}
}	
// ticket limit code
				
				if($to2==''){
				    $to2=$to_original;
				}
//file_put_contents('dat.txt', $from.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
if($priorityid==''){
	$priorityid=2;
}
if($next_assign!=''){	  
	  $ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam,unassign) VALUES ( '$from','$admin_id','3','$priorityid','$subject','$to_original','$assugnTo','$next_assign','$dept','$created_at','$updated_at',1,'$spam_status','1')", array());
}else{	
	$ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_assigned_to,next_assign_for,ticket_department,created_dt,updated_at,status_del,is_spam,unassign) VALUES ( '$from','$admin_id','3','$priorityid','$subject','$to_original','$get_dep','$next_assign','$dept','$created_at','$updated_at',1,'$spam_status','0')", array());
}
				$subject = $subject.' [##'.$ticket_no.']';
				$qry = "UPDATE `external_tickets` SET  `ticket_subject` = '$subject' WHERE `ticket_no` = '$ticket_no'";
                $resultss = $this->db_query($qry, $params);
				$get_alise=$this->fetchOne("SELECT aliseEmail FROM `department_emails` where emailAddr='$to2'",array());
				//$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$from','$get_alise','$cc','$attachments','$ticket_reply_id','$created_at')", array());		
				if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
					$Toarr_remove_space = str_replace(' ', '', $Toarr[0]);
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$Toarr_remove_space','$to_original','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				}elseif(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:"){
					$expfrom = explode('<',$from);
                    $str_fm = str_replace('>', '', $expfrom[1]);
					$str_fm = str_replace(' ', '', $str_fm);
					//preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $forward_from_array);
					preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_to, $forward_to_array);
					preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $forward_cc, $forward_cc_array);
					$ccArr = array_merge($forward_to_array[0], $forward_cc_array[0]);
					$cc = implode(',',$ccArr);
					$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$str_fm','$to_original','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				}
				else{
					$expfrom = explode('<',$from);
                    $str_from = str_replace('>', '', $expfrom[1]);
					$str_from = str_replace(' ', '', $str_from);
					$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt) VALUES ( '$ticket_no','$message','$subject','$str_from','$to_original','$cc','$attachments','$ticket_reply_id','$created_at')", array());
				}
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
				$qry = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='created_ticket' and dept_id='$dept'";
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
				    $senderid_qry = "SELECT senderID FROM `department_emails` WHERE `aliseEmail` LIKE '%$to_original%' and status = '1'";
			        $senderid_qry_value = $this->fetchOne($senderid_qry,array());				
				    $uss = array("ticket_to"=>$str_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$senderid_qry_value,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_no);				
					file_put_contents('tomail.txt', print_r($uss,true).PHP_EOL , FILE_APPEND | LOCK_EX);
					//print_r($uss); exit;
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
		 
		//$qryss = "UPDATE `external_tickets` SET status_del = '0' WHERE ticket_no = '$ticket_id'";             
                //$resultss = $this->db_query($qryss, $params);
		
		
		
		
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
		$qry = "SELECT a.*, b.*,a.ticket_subject as subj FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
		$detail_qry=$qry." ORDER BY b.ticket_message_id DESC LIMIT $limit offset $offset";
	//echo $detail_qry;exit;
			
        $result =  $this->dataFetchAll($detail_qry, array());
		
		 for($i = 0; count($result) > $i; $i++){ 
		  $ticket_customer_id = $result[$i]['customer_id'];
		  $ticket_customer_name = $result[$i]['customer_name'];	 
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
		  $ticket_only_message = stripslashes($result[$i]['only_message']);
		  $ticket_only_signature = $result[$i]['only_signature'];	 
		  $ticket_medias = $result[$i]['ticket_media'];
		  $ticket_media = explode(',', $ticket_medias );
		  $ticket_media = $ticket_media;
          $ticket_subject = $result[$i]['subj'];
		  $replied_from_db = $result[$i]['replied_from'];
		  $replied_by = $result[$i]['repliesd_by'];
		  $ticket_to = $result[$i]['replied_to'];
		  //$ticket_to = $result[$i]['ticket_to'];	 
		  $ticket_user = $result[$i]['user_id'];
		  $ccMails = $result[$i]['replied_cc'];
		  $all_replied_to = $result[$i]['all_replied_to'];
		  $all_replied_cc = ltrim($result[$i]['all_replied_cc']);
		  if($ccMails==''){
		    $ccMails = $this->fetchOne("SELECT replied_cc FROM external_tickets_data WHERE ticket_id='$ticket_no' ORDER BY ticket_id ASC LIMIT 1",array());
		  }	 
		
		  $is_spam = $result[$i]['is_spam'];
		  $closedby = $result[$i]['ticket_closed_by']; 
		  $ticket_delete_status = $result[$i]['delete_status'];
		  $ticket_profile_image = $result[$i]['profile_image'];	 
		  $ticket_type = $result[$i]['type'];
		  $ticket_enquiry_dropdown_id = $result[$i]['enquiry_dropdown_id'];
		  $ticket_revisit_date = $result[$i]['revisit_date'];
		  $ticket_enquiry_value = $result[$i]['enquiry_value'];
		  $enquiry_outcome_comments = $result[$i]['enquiry_outcome_comments'];
			if($ticket_user!='') {
			
				/*$rep= $this->fetchData("SELECT profile_image,user_name,agent_name FROM user where user_id='$ticket_user'",array());
				$rep_img=$rep['profile_image'];
				$rep_name=$rep['agent_name'];*/
				$rep= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$ticket_user' ",array());
			    $permission = $rep['profile_picture_permission'];				
			    /*if($permission==0){
				   $rep_img=$rep['profile_image'];
				}else{
					$rep_img='';
				}*/
				$rep_img=$rep['profile_image'];
				$rep_name=$rep['agent_name'];
			}else{
				$rep_img='';$rep_name='';
			}
		
			 
			 /*if($from == $replied_from_db){
			 	$replied_from = $ticket_created_by;			 					 
			 }*/
			 
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
			 /*$replied_from_val = strstr($replied_from_db, '<');
			 if($replied_from_val==''){
			   $replied_from=$replied_from_db;
			 }else{
			   $replied_from = str_replace('< ', '',$replied_from_val); 
			   $replied_from = str_replace(' >', '',$replied_from);				 
			 }*/
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
		  $ticket_notes_by =  $this->dataFetchAll("SELECT a.*,b.profile_image FROM external_ticket_notes a LEFT JOIN user b ON a.created_by=b.user_id WHERE ticket_reply_id='$ticket_message_id'",array());
		  $ticket_forward_by =  $this->dataFetchAll("SELECT a.*,b.profile_image FROM external_ticket_forward a LEFT JOIN user b ON a.created_by=b.user_id WHERE ticket_reply_id='$ticket_message_id'",array());	 
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
			$unassign_value = $result[$i]['unassign'];
			 
			$ticket_assigned_to = explode(',',$ticket_assigned_to); 
			 if(count($ticket_assigned_to) == 1 && $unassign_value == 1){ 
			
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
/*if($replied_by== 'Agent'){				
$explode = explode('<p>',$ticket_message);
$explode1 = explode('<br />',$explode[2]);
echo $explode1[0];exit;	
if($explode1[0]=='Best regards'){
	$ticket_message = '<div style="font-family: verdana !important;"><p>'.$explode[0].'</p></div>';
	$qry = "SELECT sig_content FROM email_signatures WHERE sig_id='51'";
	$ticket_signature = $this->fetchOne($qry,array());	
}else{
	$ticket_signature = '';
}
}*/

	      //$ticket_only_message = base64_decode($ticket_only_message);		
          $ticket_options = array('ticket_no' => $ticket_no,'is_spam'=>$is_spam,'ticket_media'=>$ticket_media, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $ticket_assigned_to,'ticket_assigned_to_id'=>$ticket_assigned_to_id,'department' => $department,'depart_id'=>$ticket_department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus,'ticket_status_id'=>$ticket_status,'ticket_message'=>$ticket_message,'ticket_signature'=>$ticket_signature,'ticket_notes'=>$ticket_notes_by,'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'ticket_from'=>$ticket_from,'ticket_to'=>$ticket_to,'replied_from'=>$replied_from_db,'ticket_message_id'=>$ticket_message_id,'replied_by'=>$replied_by, 'own_mail' =>$from, 'own_img' =>$own_img, 'rep_img' =>$rep_img, 'rep_name' =>$rep_name, 'user_name'=>$user_name,'mail_cc'=>$ccMails, 'first_letter_r' => strtoupper($replied_from[0]),'ticket_closed_by'=>$tic_closed_by,'ticket_delete_status' => $ticket_delete_status,'ticket_profile_image'=>$ticket_profile_image,'ticket_only_message'=>$ticket_only_message,'ticket_only_signature'=>$ticket_only_signature,'ticket_forward_by'=>$ticket_forward_by,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'all_replied_to'=>$all_replied_to,'all_replied_cc'=>$all_replied_cc,'ticket_type'=>$ticket_type,'ticket_enquiry_dropdown_id'=>$ticket_enquiry_dropdown_id,'ticket_revisit_date'=>$ticket_revisit_date,'ticket_enquiry_value'=>$ticket_enquiry_value,'enquiry_outcome_comments'=>$enquiry_outcome_comments);
          $ticket_options_array[] = $ticket_options;
        }
	
		$first_res_time_qry = "SELECT created_dt FROM `external_tickets_data` WHERE repliesd_by = 'Agent' and ticket_id='$ticket_id' LIMIT 1";              
          $first_res_time = $this->fetchmydata($first_res_time_qry,array());
		
          $closed_at = $this->fetchmydata("SELECT closed_at FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		   
          $ticket_closed_by = $this->fetchmydata("SELECT ticket_closed_by FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		if($ticket_closed_by != '0'){
			$ticket_closed_byqry = "SELECT agent_name FROM `user` WHERE `user_id` = '$ticket_closed_by'";
        	$tic_closed_by = $this->fetchOne($ticket_closed_byqry,array());
		  }
		$ticket_created_date = $this->fetchmydata("SELECT created_dt FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());
		
		$status_array_qry = "SELECT status_id,status_desc FROM status";
		$status_options_array = $this->dataFetchAll($status_array_qry, array());
	
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());
	
		
		$agents_array_qry = "SELECT user_id,agent_name FROM user where admin_id=$admin_id";
		$agents_options_array = $this->dataFetchAll($agents_array_qry, array());

        $to_cc_qry = "SELECT TRIM(BOTH ',' FROM all_replied_to) as all_replied_to,TRIM(BOTH ',' FROM all_replied_cc) as all_replied_cc FROM external_tickets_data WHERE ticket_id = '$ticket_id' ORDER BY ticket_message_id DESC LIMIT 1";
       	$to_cc = $this->fetchData($to_cc_qry,array());

		$status = array('status' => 'true');
		$status_options_array = array('status_options' => $status_options_array);
		$department_options_array = array('departments' => $department_options_array);
		$priority_options_array = array('priority' => $priority_options_array);
		$agents_options_array = array('agents' => $agents_options_array);
		$ticket_options_array = array('tick_options' => $ticket_options_array);
		$ticket_tocc_array = array('ticket_tocc_options' => $to_cc);
		$side_menu = array('ticket_created_date'=>$ticket_created_date,'first_res_time' => $first_res_time, "closed_at"=>$closed_at, "ticket_closed_by"=>$tic_closed_by);
		$total_count = $this->dataRowCount($qry,array());
	    $total = array('total' => $total_count);
        $merge_result = array_merge($total,$status, $status_options_array, $ticket_options_array,$side_menu,$priority_options_array,$department_options_array,$agents_options_array,$ticket_tocc_array);          
		 $tarray = json_encode($merge_result);         
		//print_r($ticket_options_array);exit;
        print_r($tarray);exit;
		return $tarray;
		
	}
	

	public function replyMessage($data){
		extract($data); 
		file_put_contents('too.txt', print_r($data, true).PHP_EOL , FILE_APPEND | LOCK_EX);
		$exp_to = explode(',',$to);
		//print_r($exp_to);exit;
		$replyCount=$this->fetchOne("SELECT COUNT(ticket_message_id) FROM `external_tickets_data` WHERE ticket_id='$ticket_id' AND repliesd_by='Agent'",array());
		//echo $replyCount;exit;
		//file_put_contents('msg.txt', $replyCount.PHP_EOL , FILE_APPEND | LOCK_EX);
		if($replyCount==0){	
			$Arr1 = array();$Arr2 = array();
			$explode1 = explode(',',$to);
			$explode2 = explode(',',$mail_cc);
			$get_all_replied_to=$this->fetchData("SELECT all_replied_to,all_replied_cc FROM `external_tickets_data` WHERE ticket_id='$ticket_id' ORDER BY ticket_message_id ASC LIMIT 1",array());
			$existing_all_replied_to = $get_all_replied_to['all_replied_to'];
			$explode3 = explode(',',$existing_all_replied_to);			
			$existing_all_replied_cc = $get_all_replied_to['all_replied_cc'];
			if($existing_all_replied_cc!=''){
			 $explode4 = explode(',',$existing_all_replied_cc);
			}else{
			 $explode4 = array();
			}
			$cnt1 = count($explode1);
			for($i=0;$i<$cnt1;$i++){				
			  $vals = $explode1[$i];
			  if(in_array($vals,$explode3)){			   
			  }else{
			    array_push($Arr1,$vals);
			  }	
			}
			//print_r($get_all_replied_to);exit;
			if(empty($Arr1)){			  
			  $all_replied_to = $existing_all_replied_to;
			}else{			  
			  $all_replied_to_merge = array_merge($explode3,$Arr1);
			  $all_replied_to = implode(',',$all_replied_to_merge);	
			}
			//print_r($explode2);exit;
			$cnt2 = count($explode2);
			//echo $all_replied_to;exit;
			for($j=0;$j<$cnt2;$j++){				
			  $vals1 = $explode2[$j];
			  if(in_array($vals1,$explode4)){
				  //echo $vals1.'if';
			  }else{
				  //echo $vals1.'el';
			    array_push($Arr2,$vals1);
			  }	
			}
			//print_r($Arr2);exit;
			if(empty($Arr2)){			  
			  $all_replied_cc = $existing_all_replied_cc;
			}else{			  
			  $all_replied_cc_merge = array_merge($explode4,$Arr2);
			  $all_replied_cc = implode(',',$all_replied_cc_merge);
			  $all_replied_cc = ltrim($all_replied_cc,',');	
			}
			//echo $all_replied_to;exit;
		}else{
			$Arr1 = array();$Arr2 = array();
			$explode1 = explode(',',$to);			
			$explode2 = explode(',',$mail_cc);
			//print_r($explode2);exit;
			$get_all_replied_to=$this->fetchData("SELECT all_replied_to,all_replied_cc FROM `external_tickets_data` WHERE ticket_id='$ticket_id' ORDER BY ticket_message_id DESC LIMIT 1",array());
			$existing_all_replied_to = $get_all_replied_to['all_replied_to'];			
			$explode3 = explode(',',$existing_all_replied_to);			
			$existing_all_replied_cc = $get_all_replied_to['all_replied_cc'];			
			$explode4 = explode(',',$existing_all_replied_cc);
			$cnt1 = count($explode1);
			for($i=0;$i<$cnt1;$i++){				
			  $vals = $explode1[$i];
			  if(in_array($vals,$explode3)){			   
			  }else{
			    array_push($Arr1,$vals);
			  }	
			}
			//print_r($Arr1);exit;
			if(empty($Arr1)){			  
			  $all_replied_to = $existing_all_replied_to;
			}else{			  
			  $all_replied_to_merge = array_merge($explode3,$Arr1);
			  $all_replied_to = implode(',',$all_replied_to_merge);	
			}
			//print_r($all_replied_to);exit;
			$cnt2 = count($explode2);
			for($j=0;$j<$cnt2;$j++){				
			  $vals1 = $explode2[$j];
			  if(in_array($vals1,$explode4)){			   
			  }else{
			    array_push($Arr2,$vals1);
			  }	
			}
			//print_r($Arr1);exit;
			if(empty($Arr2)){			  
			  $all_replied_cc = $existing_all_replied_cc;
			}else{			  
			  $all_replied_cc_merge = array_merge($explode4,$Arr2);
			  $all_replied_cc = implode(',',$all_replied_cc_merge);
			  $all_replied_cc = ltrim($all_replied_cc,',');	
			}
			//print_r($all_replied_cc);exit;			
		}
		$qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
		//echo $qry;exit;
       	$tic_details = $this->fetchData($qry,array());
		$subject = $tic_details['ticket_subject'];
		$te = 'subjectlog';
		file_put_contents('vai.txt', $subject.$te.PHP_EOL , FILE_APPEND | LOCK_EX);		
		$ticket_from = $tic_details['ticket_to'];
		$tic_from =  $tic_details['ticket_from'];
		$ticket_dept=  $tic_details['ticket_department'];
		if($tic_from == 'user'){
			$main_tick_from = $tic_details['ticket_email'];
		} else {
			$main_tick_from = str_replace('("','',$ticket_from);
			$main_tick_from = str_replace('")','',$main_tick_from);
		}	
		if($ticket_from == ''){
			$qry = "select aliseEmail from department_emails where departments = '$ticket_dept'";			
			$ticket_from =  $this->fetchOne($qry, array());
		}
		$qry = "select senderID from department_emails where emailAddr = '$ticket_from'";
        $from =  $this->fetchOne($qry, array());
		if($from == ''){
			$qry = "select aliseEmail from department_emails where aliseEmail = '$ticket_from'";
			$from =  $this->fetchOne($qry, array());
		}
// image in gmails with attachments		
		$description = base64_decode($message);
		$html = $message;
		$dom = new DOMDocument();
		$images = $dom->getElementsByTagName('img');
		$dom->loadHTML($html);
		foreach ($images as $image) {
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
				//$destination_path = 'https://ticketing.mconnectapps.com/api/v1.0/';
				$tempFolder = $destination_path."ext-ticket-image/".$image_name;
				if(file_put_contents($tempFolder, $img_Src)){
					$whatsapp_media_target_path = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$image_name;
					//$whatsapp_media_target_path = "https://ticketing.mconnectapps.com/api/v1.0/ext-ticket-image/".$image_name;
				} else {
					$whatsapp_media_target_path = $whatsapp_media_target_path;
				}
				$image->setAttribute("src", $whatsapp_media_target_path); 
					//$whatsapp_media_target_path = $tempFolder.$image_name; 
				}
		} 	
		$countfiles = count($_FILES['up_files']['name']); 		
		$destination_path = getcwd().DIRECTORY_SEPARATOR;
		//$destination_path = 'https://ticketing.mconnectapps.com/api/v1.0/';
		file_put_contents('ve.txt', $destination_path.PHP_EOL , FILE_APPEND | LOCK_EX);           
		$upload_location = $destination_path."ext-ticket-image/";
		$files_arr = array();
		$files_arr_db = array();  	
		for($index = 0; $index < $countfiles; $index++){
			//$filename = $_FILES['up_files']['name'][$index];
			$filename = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_FILENAME);
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['up_files']['name'][$index], PATHINFO_EXTENSION);
			//$ext = mime_content_type($_FILES['up_files']['name'][$index]);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['up_files']['tmp_name'][$index],$path)){
						$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
					    $files_arr_db[] =  "https://ticketing.mconnectapps.com/api/v1.0/ext-ticket-image/".$filename;
					    $source_file="https://".$_SERVER['SERVER_NAME']."/api/v1.0/ext-ticket-image/".$filename;
						$destination_file=$filename;
						$ch = curl_init("https://ticketing.mconnectapps.com/api/v1.0/ext-ticket-image/file-upload.php");
						$data =array(
							"token" => "xoxp-344956815296-346541880614-345656220160-2b002c40dbeb0e8aee1ba1a82b41b166",
							"source_file" => $source_file,"destination_file" => $destination_file);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$result = curl_exec($ch);
						if (curl_errno($ch)) {
                            $error_msg = 'Request Error:' . curl_error($ch);              
                            file_put_contents('ticket_error.txt', $error_msg.PHP_EOL , FILE_APPEND | LOCK_EX);
                        }
						curl_close($ch);
				}

		}
		file_put_contents('ry.txt', print_r($files_arr_db,true).PHP_EOL , FILE_APPEND | LOCK_EX);	  
		$files_array = $files_arr;
		//$ticketMedia = implode(",",$files_arr);	
		$files_arr_db = implode(",",$files_arr_db);
		$message = $dom->saveHTML();
// image in gmails	END	
		$mail_ccs = explode(",",$mail_cc);
		$onlyMessage = addslashes($message);
		//file_put_contents('msg.txt', $onlyMessage.PHP_EOL , FILE_APPEND | LOCK_EX);
		$only_msg = '<div  style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$onlyMessage.'</div>';	
		$countqry = "SELECT COUNT(ticket_message_id) FROM `external_tickets_data` WHERE ticket_id='$ticket_id' AND repliesd_by='Agent';";
		$replycount = $this->fetchOne($countqry,array());
		if($replycount==0){
			$qry = "SELECT sig_content FROM email_signatures WHERE sig_id='$signature_id'";
			$mailSignature = $this->fetchOne($qry,array());		
			if($mailSignature){
				$message =  $message.$mailSignature;
			}
		}else{
			$message = $message;
		}
		$messages = $this->getTicketThread($ticket_id);
		foreach($messages as $m) {
		        $mess[] = '<div  style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
		}		
		$mess = implode('<br>',$mess);
		$message =  '<div style="font-family: verdana !important;">'.$message.'</div>';
		$messagetoSend = $message.'<br> <br>'.$mess;
		//$messagetoSend = $message;
		if( strpos($to, ',') !== false ) { $tos = explode(',',$to); }
					$message_id = $this->fetchOne("SELECT ticket_reply_id FROM external_tickets_data WHERE ticket_id = '$ticket_id' and ticket_reply_id !=''",array());
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
					$te = 'subjectlogs';
		             file_put_contents('vai.txt', $subject.$te.PHP_EOL , FILE_APPEND | LOCK_EX); 
                    $body = $messagetoSend; 
                    file_put_contents('body.txt', $body.PHP_EOL , FILE_APPEND | LOCK_EX);  
		//print_r($body); exit;
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
		$admin_id_qry = "SELECT admin_id FROM `user` WHERE user_id='$user_id'";
        $admin_id_val = $this->fetchmydata($admin_id_qry,array());
		//echo $admin_id_val;exit; 		 
        if($admin_id_val==1){
        	$qryss = "UPDATE `external_tickets` SET ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_id'";
        }
        else{
        	$override=$this->fetchOne("SELECT override FROM `admin_details` where admin_id='$admin_id'",array());
        	if($override==0){
        		$unass=$this->fetchOne("SELECT unassign FROM `external_tickets` where ticket_no='$ticket_id'",array());
        		if($unass==0){
        			$qryss = "UPDATE `external_tickets` SET ticket_status = '1',updated_at='$updated_at',ticket_assigned_to='$user_id',unassign='1' WHERE ticket_no = '$ticket_id'";
        		}else{
        			$qryss = "UPDATE `external_tickets` SET ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_id'";
        		}
        	}else{
        		$qryss = "UPDATE `external_tickets` SET ticket_status = '1',updated_at='$updated_at'  WHERE ticket_no = '$ticket_id'";
        	}
		}
		//echo $qryss;exit;	
		//file_put_contents('vaithee.txt', $qryss.PHP_EOL , FILE_APPEND | LOCK_EX);
		$resultss = $this->db_query($qryss, $params);
		$message = str_replace("'","\n",$message);	
		$ry = "INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,created_dt,user_id,only_message,only_signature,all_replied_to,all_replied_cc) VALUES ( '$ticket_id','$message','$subject','$from','$to','$mail_cc','$files_arr_db','Agent','$created_at','$user_id','$only_msg','$mailSignature','$all_replied_to','$all_replied_cc')";
		file_put_contents('ry.txt', $ry.PHP_EOL , FILE_APPEND | LOCK_EX);			
		$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,created_dt,user_id,only_message,only_signature,all_replied_to,all_replied_cc) VALUES ( '$ticket_id','$message','$subject','$from','$to','$mail_cc','$files_arr_db','Agent','$created_at','$user_id','$only_msg','$mailSignature','$all_replied_to','$all_replied_cc')", array());				
		$result = $qry_result == 1 ? 'mailed' : 'Error';
				  $dt = date('Y-m-d H:i:s');
				 $get=$this->fetchData("select user_name, IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$user_id'", array()); 
				$user_name= $get['user_name'];$admin_id= $get['admin_id'];
				 $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$admin_id','$ticket_id','Ticket Replied by $user_name ($subject)','11','7','$created_at')", array());
				 if($customer_id != ''){
				     $customer_name = $this->fetchOne("SELECT customer_name from ticket_customer WHERE customer_id='$customer_id' ", array());
				     $qryss = "UPDATE `external_tickets` SET status_del = '0',customer_id='$customer_id',customer_name='$customer_name' WHERE ticket_no = '$ticket_id'";
                     $resultss = $this->db_query($qryss, $params);
				 }else{
					 $qryss = "UPDATE `external_tickets` SET status_del = '0' WHERE ticket_no = '$ticket_id'";             
                     $resultss = $this->db_query($qryss, $params);
				 }
				//$res = "Message has been sent successfully";
// reply email alert
$agent_alert_qry = "SELECT email_id FROM user WHERE reply_email_alert=1 AND admin_id='$admin_id'";
$agent_alert_email = $this->dataFetchAll($agent_alert_qry,array());
//print_r($agent_alert_email);	  		
$agentArr = array();
for($k = 0; $k < count($agent_alert_email); $k++){
    $agent_emails = $agent_alert_email[$k]['email_id'];
    array_push($agentArr, $agent_emails);
}
$group_alert_qry = "SELECT email FROM email_group WHERE reply_email_alert=1 AND admin_id='$admin_id'";
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
$uss = array("ticket_to"=>$replied_from,"ticket_cc"=>$ccs,"ticket_bcc"=>$ticket_bcc,"from"=>$from,"message"=>$messagetoSend,"subject"=>$subject, "ticket_id"=>$ticket_id);
$autoRespns = $this->autoResponseEmail($uss);
// reply email alert
for($et=0;$et<count($exp_to);$et++){
	$et_val = $exp_to[$et];
	$customer_whitelist_qry=$this->fetchOne("SELECT id FROM `customer_whitelist` WHERE email='$et_val'",array());
	if($customer_whitelist_qry==''){
		$delqry_result = $this->db_query("DELETE from spam_mail_ids where email='$et_val'",  array());
		$insertqry_result = $this->db_insert("INSERT INTO customer_whitelist(admin_id,email,status) VALUES ( '64','$et_val','1')", array());
	}	    
}				 
				$status = array('status' => 'true');     
		        $tarray = json_encode($status);           
                print_r($tarray);exit;
		        return $tarray;				
				 	}
		return $res;

		 
	}
	
	public function external_ticket_bulk_assign($data){
		extract($data);//print_r($data);exit;
		$override=$this->fetchOne("SELECT override FROM `admin_details` where admin_id='$admin_id'",array());
        if($override==0){
			$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department'",array());
			if($agent_id==''){
	          $qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$get_dep' where ticket_no IN ($ticket_id)";
			}else{
			  $qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$agent_id' where ticket_no IN ($ticket_id)";	
			}
	        $update_data = $this->db_query($qry, array());
	        $result = $update_data == 1 ? 1 : 0;  
	        return $result;
        }else{
		 $qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$agent_id' where ticket_no IN ($ticket_id)";
         $update_data = $this->db_query($qry, $params);
		$update_data = $update_data = 1 ? 1:0;
		return $update_data;
	   }
	}
public function createExternalTicket($data){
		extract($data);
		//print_r($data); exit;
		$tos = explode(',',$to);
// adding customer details	
	    $cusmail = $tos[0];
	    $get_domain = explode('@',$cusmail);	
	    if($get_domain[1] != 'gmail.com' && $get_domain[1] != 'yahoo.com' && $get_domain[1] != 'hotmail.com'){	
	      $customer_domain = $get_domain[1];
	    }else{
	      $customer_domain = '  ';	  
	    }
		$get_ticketcustomer_bymail = "SELECT customer_id,customer_name FROM ticket_customer WHERE customer_email LIKE '%$cusmail%'";
		$get_ticketcustomer_bymail_values =  $this->fetchData($get_ticketcustomer_bymail, array());
		if($get_ticketcustomer_bymail_values > 0){
		  $ticket_customer_id = $get_ticketcustomer_bymail_values['customer_id'];
		  $ticket_customer_name = $get_ticketcustomer_bymail_values['customer_name'];
		}
		else{
		  $get_ticketcustomer_bydomain = "SELECT customer_id,customer_name FROM ticket_customer WHERE customer_email LIKE '%$customer_domain%'";
		  $get_ticketcustomer_bydomain_values =  $this->fetchData($get_ticketcustomer_bydomain, array());
		  if($get_ticketcustomer_bymail_values > 0){
			$ticket_customer_id = $get_ticketcustomer_bymail_values['customer_id'];
			$ticket_customer_name = $get_ticketcustomer_bymail_values['customer_name'];
		  }else
		  {
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>'{
				"operation": "agents",
				"moduleType": "agents",
				"api_type": "web",
				"access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
				"element_data": {
					"action": "get_customerdetails",
					"customer_domain":"'.$customer_domain.'"
				}
			  }',
			  CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			  ),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$explode_customer_details = explode('||',$response);
			$ticket_customer_id = $explode_customer_details[0];
			$ticket_customer_name = $explode_customer_details[1];
			$ticket_customer_code = $explode_customer_details[2];
			$ticket_customer_email = $explode_customer_details[3];
			$ticket_customer_country = $explode_customer_details[4];
			$ticket_customer_phone = $explode_customer_details[5];
			if($ticket_customer_id != ''){
			 $insertQry = $this->db_insert("INSERT INTO ticket_customer(admin_id,customer_id,customer_code,customer_name,customer_email,phone_number,country) VALUES ('$admin_id','$ticket_customer_id','$ticket_customer_code','$ticket_customer_name','$ticket_customer_email','$ticket_customer_phone','$ticket_customer_country')", array());
			}
		  }
		}
// adding customer details		
		$mail_ccs = explode(",",$mail_cc);	
	    $qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";	
		$from = $this->fetchOne($qry,array());
		//echo $from;exit;
		$from = $from_address;
		$description = base64_decode($description);
		$html = $description;
		$dom = new DOMDocument();		
	// $test=mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
	// $content = htmlentities($html);
	// 	// $dom->loadHTML();	
	// 	$images = $content->getElementsByTagName('img');
$images = $dom->getElementsByTagName('img');
$dom->loadHTML($html);
// $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
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
			/*if($mailSignature){
				$mailSignature = str_replace('</div>', '</div></body></html>', $mailSignature);
				$html = str_replace('</body></html>', $mailSignature, $html);
			}*/ 
	        //$htm = $html.$mailSignature;
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
				$user_qry_value=$this->fetchOne("SELECT timezone_id FROM user WHERE user_id='$admin_id'", array());		
     			$user_timezone=$this->fetchOne("SELECT name FROM timezone WHERE id='$user_qry_value'", array());
     			date_default_timezone_set($user_timezone);  
     			$created_dt = date("Y-m-d H:i:s"); 
				$ticket_no = $this->db_insert("INSERT INTO external_tickets(ticket_from,admin_id,ticket_status,priority,ticket_subject,ticket_to,ticket_department,ticket_created_by,ticket_assigned_to,ticket_email,unassign,created_dt,updated_at,customer_id,customer_name) VALUES ( 'user','$admin_id','$status','$priority','$subject','$to','$department','$user_id','$agent_id','$from_address','1','$created_dt','$created_dt','$ticket_customer_id','$ticket_customer_name')", array());			 
			    $description = $html;
				$qry_result = $this->db_insert("INSERT INTO external_tickets_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,repliesd_by,ticket_media,created_dt,only_message,only_signature) VALUES ( '$ticket_no','$description','$subject','$from','$to','$mail_cc','Agent','$ticketMedia','$created_dt','$description','$mailSignature')", array());
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
		extract($data);//print_r($data);exit; 
	    $user_timezone_id = $this->fetchOne("SELECT timezone_id FROM user WHERE user_id = '$user_id'",array());
	    $user_timezone=$this->fetchOne("SELECT name FROM timezone WHERE id='$user_timezone_id'", array());		
        date_default_timezone_set($user_timezone);  
        $created_dt = date("Y-m-d H:i:s");
        $update_data = $this->db_insert("INSERT INTO external_ticket_notes(ticket_reply_id,ticket_notes,created_by,created_name,created_dt) VALUES ($ticket_message_id,'$ticket_notes',$user_id,'$user_name','$created_dt')", array()); 
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
		extract($data); //print_r($data);exit;
		file_put_contents("check.txt",print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);
		$ticket_id = base64_decode($ticket_id);
		$override=$this->fetchOne("SELECT override FROM `admin_details` where admin_id='$admin_id'",array());
		$customer_id=$this->fetchOne("SELECT customer_id FROM `external_tickets` where ticket_no='$ticket_id'",array());
		$department_name=$this->fetchOne("SELECT department_name FROM `departments` where dept_id='$department'",array());
		$ticket_type=$this->fetchOne("SELECT type FROM `external_tickets` where ticket_no='$ticket_id'",array());
        if($department!=''){
			if($ticket_type=='cms'){
			   	file_put_contents('check.txt', $ticket_type.'||'.$customer_id.'||'.$department.'||'.$department_name.'||'.'view'.PHP_EOL , FILE_APPEND | LOCK_EX);
				$curl = curl_init();
			    curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
				    "operation": "agents",
					"moduleType": "agents",
					"api_type": "web",
					"access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
					"element_data": {
					   "action": "update_customerdetails",
					   "customer_id":"'.$customer_id.'",
					   "department_id":"'.$department.'",
					   "department_name":"'.$department_name.'"
					}
				}',
				CURLOPT_HTTPHEADER => array(
				  'Content-Type: application/json'
				),
				));
				$response = curl_exec($curl);
				if (curl_errno($curl)) {
				    $te = 'Error:' . curl_error($curl);
				    file_put_contents('check.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);
				}
				curl_close($curl);
		    }
		}		
	    $oldqry = "SELECT * FROM `external_tickets` where ticket_no='$ticket_id'";
        $oldDetails = $this->fetchData($oldqry, array());
        $oldDepartment = $oldDetails['ticket_department'];
        $oldAgent = $oldDetails['ticket_assigned_to'];
        if($override==0){
        	/*if($ticket_type=='enquiry'){
        		$enqid = $enquiry_dropdown_id;
        		$revisit_date = $revisit;
        	}*/
        	if($agent_id==''){
				$get_dep=$this->fetchOne("SELECT department_users FROM `departments` where dept_id='$department'",array());
				if($ticket_type=='enquiry'){
					if($enquiry_dropdown_id != '' && $revisit != ''){
                        $qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$get_dep',unassign='0',enquiry_dropdown_id='$enquiry_dropdown_id',revisit='$revisit',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
                    }elseif($enquiry_dropdown_id != '' && $revisit == ''){
                    	$qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$get_dep',unassign='0',enquiry_dropdown_id='$enquiry_dropdown_id',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
                    }else{
                    	$qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$get_dep',unassign='0' WHERE ticket_no='$ticket_id'";
                    }
				}else{
					$qry = "UPDATE external_tickets SET ticket_department='$department',ticket_assigned_to='$get_dep',unassign='0' where ticket_no='$ticket_id'";
				}		        
		        $update_data = $this->db_query($qry, array());
		        $result = $update_data == 1 ? 1 : 0;  
		        return $result;
	        }
	        else{
	        	if($ticket_type=='enquiry'){
					if($enquiry_dropdown_id != '' && $revisit != ''){
                        $qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id',unassign=1,enquiry_dropdown_id='$enquiry_dropdown_id',revisit='$revisit',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
                    }elseif($enquiry_dropdown_id != '' && $revisit == ''){
                    	$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id',unassign=1,enquiry_dropdown_id='$enquiry_dropdown_id',enquiry_outcome_comments='$enquiry_outcome_comments' WHERE ticket_no='$ticket_id'";
                    }else{
                    	$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id',unassign=1 WHERE ticket_no='$ticket_id'";
                    }
				}else{
	        	    $qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id',unassign=1 where ticket_no = $ticket_id";
	        	}		
				if($agent_id > 0){			
					$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$agent_id'";              
		      		$createdby = $this->fetchmydata($createdby_qry,array());
					$previuos_agent=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_no='$ticket_id'",array());
					if($previuos_agent!=$agent_id){
					$sub = $user_name.' Assigned a ticket';
						$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$u = $this->send_notification($udm);
						$sub =$user_name.' Assigned a ticket to '.$createdby;
						$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$us = $this->send_notification($adm);
						$e = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='agent_template' and dept_id='$department'";
		                $repM = $this->fetchOne($e, array());		
						if($repM !=''){
							$tickNo = '[##'.$ticket_id.']';
							$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
							$repM = str_replace('{%Cassign_to%}',$createdby,$repM);
							$repM = str_replace('{%Cassign_by%}',$user_name,$repM);
							$messages = $this->getTicketThread($ticket_id);
							foreach($messages as $m){
							  $mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
							}
							$mess = implode('<br>',$mess);
							$messagetoSend = $repM.'<br> <br>'.$mess;	
							$ticket_to = $this->fetchOne("SELECT email_id FROM `user` where user_id='$agent_id'",array());
							$subject = "New Ticket Alert";
							$from = 'no-reply@cal4care.com';
				            $uss = array("ticket_to"=>$ticket_to,"ticket_cc"=>"","ticket_bcc"=>"","from"=>$from,"message"=>$messagetoSend,"subject"=>$subject,"ticket_id"=>$ticket_id,"message_id"=>"");
							$autoRespns = $this->autoResponseEmail($uss);

                            $ticket_to_assignee = $this->fetchOne("SELECT email_id FROM `user` where user_id='$user_id'",array());
							$subject = "New Ticket Alert";
							$from = 'no-reply@cal4care.com';
							$rescontent = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='agent_template_assignee'";
		                    $rescontent_val = $this->fetchOne($rescontent, array());
		                    $rescontent_val = str_replace('{%Cticket_id%}',$tickNo,$rescontent_val);
							$rescontent_val = str_replace('{%Cassign_to%}',$createdby,$rescontent_val);
							$rescontent_val = str_replace('{%Cassign_by%}',$user_name,$rescontent_val);
				            $uss = array("ticket_to"=>$ticket_to_assignee,"ticket_cc"=>"","ticket_bcc"=>"","from"=>$from,"message"=>$rescontent_val,"subject"=>$subject,"ticket_id"=>$ticket_id,"message_id"=>"");
							$autoRespns = $this->autoResponseEmail($uss);
						}
					}
					if($status == '6'){
						$sub = $user_name.' Assigned a ticket';
						$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$u = $this->send_notification($udm);
						$sub =$user_name.' Assigned a ticket to '.$createdby;
						$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$us = $this->send_notification($adm);
						$e = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='agent_template' and dept_id='$department'";
		                $repM = $this->fetchOne($e, array());		
						if($repM !=''){
							$tickNo = '[##'.$ticket_id.']';
							$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
							$repM = str_replace('{%Cassign_to%}',$createdby,$repM);
							$repM = str_replace('{%Cassign_by%}',$user_name,$repM);	
							$ticket_to = $this->fetchOne("SELECT email_id FROM `user` where user_id='$agent_id'",array());
							$subject = "New Ticket Alert";$from = 'no-reply@cal4care.com';
				$uss = array("ticket_to"=>$ticket_to,"ticket_cc"=>"","ticket_bcc"=>"","from"=>$from,"message"=>$repM,"subject"=>$subject,"ticket_id"=>$ticket_id,"message_id"=>"");							
							$autoRespns = $this->autoResponseEmail($uss);
						}
					} else if($status == '5'){				
						$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($department)",array());
						$dept_users = explode(',',$get_dep);
						$dept_users[] = $admin_id;
						$dept_users = array_unique($dept_users);
						$dept_users = implode(',',$dept_users);
						$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$dept_users',unassign=0 where ticket_no = $ticket_id";
						$sub = $user_name.' Unassigned a ticket';
						$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$u = $this->send_notification($udm);
						$sub =$user_name.' Unassigned a ticket '.$ticket_id;
						$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$us = $this->send_notification($adm);
					}					
				}
		        $update_data = $this->db_query($qry, $params);
				$update_data = $update_data = 1 ? 1:0;
				return $update_data;
	        }
        } // round robin off ends here
        else{
			if($agent_id != ''){
			    $get_departmentusers = $this->fetchOne("SELECT department_users FROM `departments` WHERE dept_id = '$department'",array());
				$departmentusers = explode(',',$get_departmentusers);				
				$agent_current_array_val = array_search($agent_id, $departmentusers);
				$agent_next_array_val = $departmentusers[$agent_current_array_val+1];
				$agent_next_array_val = ($agent_next_array_val) ? $agent_next_array_val :  $departmentusers[0];
				$mainqry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$agent_id',next_assign_for='$agent_next_array_val',unassign=1 where ticket_no = $ticket_id";
			}
			else{
			    $mainqry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='',next_assign_for=0,unassign=0 where ticket_no = $ticket_id";
			}
			//echo $mainqry;exit;
			if($agent_id > 0){		
				//$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$agent_id'";              
	      		//$createdby = $this->fetchmydata($createdby_qry,array());
				$createdby_qry = "SELECT agent_name FROM user WHERE user_id='$agent_id'";              
		      		$createdby = $this->fetchmydata($createdby_qry,array());
					$previuos_agent=$this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` where ticket_no='$ticket_id'",array());
					if($previuos_agent!=$agent_id){
					$sub = $user_name.' Assigned a ticket';
						$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$u = $this->send_notification($udm);
						$sub =$user_name.' Assigned a ticket to '.$createdby;
						$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
						$us = $this->send_notification($adm);
						$e = "SELECT response_content FROM email_autoresponses WHERE status='1' and admin_id='$admin_id' and response_for='agent_template' and dept_id='$department'";
		                $repM = $this->fetchOne($e, array());		
						if($repM !=''){
							$tickNo = '[##'.$ticket_id.']';
							$repM = str_replace('{%Cticket_id%}',$tickNo,$repM);
							$repM = str_replace('{%Cassign_to%}',$createdby,$repM);
							$repM = str_replace('{%Cassign_by%}',$user_name,$repM);	
							$ticket_to = $this->fetchOne("SELECT email_id FROM `user` where user_id='$agent_id'",array());
							$subject = "New Ticket Alert";$from = 'no-reply@cal4care.com';
				$uss = array("ticket_to"=>$ticket_to,"ticket_cc"=>"","ticket_bcc"=>"","from"=>$from,"message"=>$repM,"subject"=>$subject,"ticket_id"=>$ticket_id,"message_id"=>"");							
							$autoRespns = $this->autoResponseEmail($uss);
						}
					}
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
					$qry = "UPDATE external_tickets SET ticket_status='$status', ticket_department='$department',ticket_assigned_to='$dept_users',unassign=0 where ticket_no = $ticket_id";
					$sub = $user_name.' Unassigned a ticket';
					$udm = array("user_id"=>$agent_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$user_name,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
					$u = $this->send_notification($udm);
					$sub =$user_name.' Unassigned a ticket '.$ticket_id;
					$adm = array("user_id"=>$admin_id,"ticket_for"=>"Assign Ticket","ticket_from"=>$from,"ticket_subject"=>$sub, "ticket_id"=>$ticket_id);
					$us = $this->send_notification($adm);
				}
			}
	        $update_mainqry_data = $this->db_query($mainqry, $params);
			$update_data_val = $update_mainqry_data = 1 ? 1:0;
			//echo $update_data_val;exit;
			//exit;
			//$update_data_val = 1;
			if($update_data_val==1){				
				// updating old Agent
				$setting_ary = $this->fetchData("SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'",array());
	            $ticket_limit = $setting_ary['ticket_limit'];
	            $override = $setting_ary['override'];
	            if($oldAgent != ''){
				  $get_olddep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id = '$oldDepartment'",array());
				$olddept_users = explode(',',$get_olddep);					
                  $olduser_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `admin_id`='$admin_id' AND `ticket_assigned_to`='$oldAgent' AND `ticket_status`=3 AND ticket_department='$oldDepartment' AND `unassign`=1 AND `delete_status`=0",array());				  
		          $oldqueue_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `admin_id`='$admin_id' AND `ticket_status`=3 AND ticket_department='$oldDepartment' AND `unassign`=0 AND `delete_status`=0",array());
				  if($oldqueue_ticket_count > 0){	
					  if($olduser_ticket_count==0){
						if($oldqueue_ticket_count <= $ticket_limit){
							$assign_tic_limit = $oldqueue_ticket_count;
						}else{
							$assign_tic_limit = $ticket_limit;
						}		            
						$oldcurrent_array_val = array_search($oldAgent, $olddept_users);
						$oldnext_array_val = $olddept_users[$oldcurrent_array_val+1];
						$oldnext_array_val = ($oldnext_array_val) ? $oldnext_array_val :  $olddept_users[0];
						$ticno_array_qry = "SELECT ticket_no FROM external_tickets WHERE admin_id='$admin_id' AND ticket_department='$oldDepartment' AND ticket_status=3 AND unassign=0 AND delete_status=0 ORDER BY ticket_no ASC LIMIT $assign_tic_limit";
						//echo $ticno_array_qry;exit;   
						$ticno_array_value = $this->dataFetchAll($ticno_array_qry, array());
						for($i = 0; $i < count($ticno_array_value); $i++){
							$ticket_nos = $ticno_array_value[$i]['ticket_no'];                
							$update_data = $this->db_query("UPDATE external_tickets SET ticket_assigned_to='$oldAgent',next_assign_for='$oldnext_array_val',unassign=1 WHERE ticket_no='$ticket_nos'", array());
						}				    
					  } // if condition for agent ticket count equal to 0
					  else{					  
						  if($olduser_ticket_count < $ticket_limit){	  	
							$oldcurrent_array_val = array_search($oldAgent, $olddept_users);
							$oldnext_array_val = $olddept_users[$oldcurrent_array_val+1];						   
							$oldnext_array_val = ($oldnext_array_val) ? $oldnext_array_val :  $olddept_users[0];		
							$assign_tic_limit = $ticket_limit - $olduser_ticket_count;						  
							$ticno_array_qry = "SELECT ticket_no FROM external_tickets WHERE ticket_department='$oldDepartment' AND ticket_status=3 AND unassign=0 AND delete_status=0 ORDER BY ticket_no ASC LIMIT $assign_tic_limit";
							$ticno_array_value = $this->dataFetchAll($ticno_array_qry, array());
							for($i = 0; $i < count($ticno_array_value); $i++){
							  $ticket_nos = $ticno_array_value[$i]['ticket_no'];						   	
							  $update_data = $this->db_query("UPDATE external_tickets SET ticket_assigned_to='$oldAgent',next_assign_for='$oldnext_array_val',unassign=1 WHERE ticket_no='$ticket_nos'", array());
							}				        
						  } // if condition for agent-ticket-count less than ticket limit
					  } // else condition
				} // if condition for queue count not equal to 0	
	            } // if condition for agent not equal to empty
				
	            // updating new dept/agent
				if($department!=''){
				        if($ticket_type=='cms'){
				        	file_put_contents('check.txt', $ticket_type.'view'.PHP_EOL , FILE_APPEND | LOCK_EX);
							$curl = curl_init();
							curl_setopt_array($curl, array(
							CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 0,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'POST',
							CURLOPT_POSTFIELDS =>'{
							  "operation": "agents",
							  "moduleType": "agents",
							  "api_type": "web",
							  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
							  "element_data": {
							    "action": "update_customerdetails",
							    "customer_id":"'.$customer_id.'",
							    "department_id":"'.$department.'",
							    "department_name":"'.$department_name.'"
							  }
							}',
							CURLOPT_HTTPHEADER => array(
							  'Content-Type: application/json'
							),
							));
							$response = curl_exec($curl);
							if (curl_errno($curl)) {
				                  $te = 'Error:' . curl_error($curl);
				                  file_put_contents('check.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);
				            }
							curl_close($curl);
		                }					
				        $get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id = '$department'",array());
						$dept_users = explode(',',$get_dep);			
					    //print_r($dept_users);exit;				    
						if($agent_id==''){			  				  
						  $dep_count = count($dept_users);
						  for($i=0;$i<$dep_count;$i++){
						    $j = $dept_users[$i];						
							$tickets_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$j' AND `ticket_status`=3 AND ticket_department='$department' AND unassign=1 AND delete_status=0",array());
							if($tickets_count < $ticket_limit){	
								$assugnTo = $j;
								if($dept_users[$i+1] == ''){
								  $nextVal = $j;
								}else{
								  $nextVal = $dept_users[$i+1]; 
								}
								break;
							}else{									
									$nextVal = '';	        	
								}
						  }						  	
						  if($nextVal==''){
						    $qrys = "UPDATE external_tickets SET ticket_assigned_to='$get_dep',next_assign_for='0',ticket_department='$department',unassign=0 WHERE ticket_no='$ticket_id'";
							$update_datas = $this->db_query($qrys, array());
						  }else{						  	
							  $newCount = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_status`=3 AND ticket_department='$department' AND `unassign`=1 AND `delete_status`=0 OR `ticket_status`=1 AND ticket_department='$department' AND `unassign`=1 AND `delete_status`=0",array());
						      if($newCount > 0 ){	
						        $assignNext = $this->fetchOne("SELECT next_assign_for FROM external_tickets WHERE ticket_department='$department' ORDER BY ticket_no DESC LIMIT 1, 1",array());
						      }else{							    
								$assignNext = $assugnTo; 
						      }							    
						  	  $current_array_val = array_search($assignNext, $dept_users);
				              $next_array_val = $dept_users[$current_array_val+1];
							  if($next_array_val==''){
							    $next_array_val=$dept_users[0];
							  }else{
								$next_array_val=$next_array_val;
							  }
						  	  $qrys = "UPDATE external_tickets SET ticket_assigned_to='$assignNext',next_assign_for='$next_array_val',ticket_department='$department',unassign=1 WHERE ticket_no='$ticket_id'";
							  $update_datas = $this->db_query($qrys, array());
						  }					  
					    }		    									
					}// main if
			} // if condition for update = 1
			return $update_data_val;
	    }	
	}		
	
	
	public function createTicketSignature($data){
			extract($data);		
			// $tos = explode(',',$to);		
			$qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";
		
			$from = $this->fetchOne($qry,array());
			
			$description = base64_decode($description);
			$html = $description;
			$dom = new DOMDocument();
			//	echo '124';exit;
			$images = $dom->getElementsByTagName('img');
			$dom->loadHTML($html);
			// echo 'Ima122ge';exit;
			// $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		
		
			
			foreach ($images as $image) {						
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
		    if($admin_id==$user_id){		
			  if($is_default=='1'){
				$update_data11 = $this->db_query("UPDATE email_signatures SET is_default='0'",  array());
			  }
		    }
		    $dept_name= $this->fetchOne("SELECT department_name FROM departments WHERE dept_id='$dept_id'",array());
            $selectqry = "SELECT * FROM email_signatures WHERE dept_id ='$dept_id'";
            $result_dat =  $this->fetchData($selectqry, array());
		    if($result_dat > 0){
				$status = array('status' => 'false');
                $ticket_options_array = array('data' => '');
		        $merge_result = array_merge($status, $ticket_options_array); 		
                $tarray = json_encode($merge_result);           
                print_r($tarray);exit;
			}else{
				$qry_result = $this->db_insert("INSERT INTO `email_signatures` ( `sig_title`, `sig_content`, `is_default`, `admin_id`,`user_id`,`dept_id`,`dept_name`) VALUES ( '$sig_title','$sig_content','$is_default','$admin_id','$user_id','$dept_id','$dept_name')", array());
				$update_data = $qry_result = 1 ? 1:0;
				$email_signature_id= $this->fetchOne("SELECT MAX(sig_id) FROM email_signatures WHERE admin_id='$admin_id'",array());
				if($dept_id!=''){
				  $update_dept = $this->db_query("UPDATE departments SET signature_id='$email_signature_id' WHERE dept_id='$dept_id'",  array());
				}
				$status = array('status' => 'true');
				$ticket_options_array = array('data' => $update_data);
				$merge_result = array_merge($status, $ticket_options_array); 		
				$tarray = json_encode($merge_result);           
				print_r($tarray);exit;
	        }
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
		
			// $tos = explode(',',$to);
		
			$qry = "SELECT support_email FROM admin_details WHERE admin_id=$admin_id";
			$from = $this->fetchOne($qry,array());
			$description = base64_decode($description);
			$html = $description;
			$dom = new DOMDocument();
			// $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
			// $images = $dom->getElementsByTagName('img');
			// added this bcz of 500 issue
			$images = $dom->getElementsByTagName('img');
			$dom->loadHTML($html);
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
	//$qry_result =$this->db_query("UPDATE email_signatures SET sig_title='$sig_title',sig_content='$sig_content',is_default='$is_default' where sig_id='$sig_id'",  array());
	$dept_name= $this->fetchOne("SELECT department_name FROM departments WHERE dept_id='$dept_id'",array());	
	$qry_result =$this->db_query("UPDATE email_signatures SET sig_title='$sig_title',sig_content='$sig_content',is_default='$is_default',dept_id='$dept_id',dept_name='$dept_name' where sig_id='$sig_id'",  array());
		$update_data = $qry_result = 1 ? 1:0;
	$update_dept = $this->db_query("UPDATE departments SET signature_id='$sig_id' WHERE dept_id='$dept_id'",  array());
		  $status = array('status' => 'true');
        $ticket_options_array = array('data' => $update_data);
		 $merge_result = array_merge($status, $ticket_options_array); 		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	}	
public function makeSignatureDefault($data){
	extract($data);
	//print_r($data); exit;
	//$update_data = $this->db_query("UPDATE email_signatures SET is_default='0' where admin_id='$admin_id' and user_id='$user_id'",  array());
	$siid= $this->fetchOne("SELECT sig_id FROM email_signatures where admin_id='$admin_id' AND is_default=1",array());
	//echo $siid;exit;
	$update_before = $this->db_query("UPDATE email_signatures SET is_default='0' where admin_id='$admin_id' and sig_id='$siid'",  array());
	$qry_result = $this->db_query("UPDATE email_signatures SET is_default='$is_default' WHERE sig_id='$signature_id' and admin_id='$admin_id' and user_id='$user_id'",  array());
	$update_data = $qry_result = 1 ? 1:0;
	return $update_data;
}

	public function deleteSignature($data){
	extract($data);
	//print_r($data); exit;
	$qry_result = $this->db_query("DELETE from email_signatures where sig_id='$signature_id'",  array());
	//$qry_result = $this->db_query("UPDATE email_signatures SET is_default='$is_default' where sig_id='$signature_id' and admin_id='$admin_id' and user_id='$user_id'",  array());
		$qry_signature_reset = $this->db_query("UPDATE departments SET signature_id='0' where signature_id='$signature_id' and admin_id='$admin_id'",  array());
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
				$rep= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$ticket_user' ",array());
			    $permission = $rep['profile_picture_permission'];
			    if($permission==0){
				   $rep_img=$rep['profile_image'];
				}else{
					$rep_img='';
				}	
			    //$rep_img=$rep['profile_image'];
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
    	 $replied_from = $this->fetchOne("SELECT replied_from FROM `external_tickets_data` WHERE `ticket_id`='$ticket_id'",array());
    	 $cus_com_name = $this->fetchOne("SELECT customer_name FROM `external_tickets` WHERE `ticket_no`='$ticket_id'",array());
    	 if($cus_com_name=='Not a customer'){
    	 	$cus_com_name = 'Prospect';
    	 }else{
    	 	$cus_com_name = $cus_com_name;
    	 }
         $ticket_message = '<h1 style="font-size: 20px;font-family: verdana !important; text-align: right; background: #00a65a; color: #fff; padding: 10px;margin-top: 0; border-radius: 8px 8px 0 0;"> '.$cus_com_name.'</h1>'.$ticket_messages;
	} else {
		$reps= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$ticket_user' ",array());
	    $permissions = $reps['profile_picture_permission'];
		if($permissions==0){
		if($rep_img ==''){ $rep_img='https://omni.mconnectapps.com/assets/images/user.jpg';}
		 //$ticket_message = '<p style="font-size: 20px;background: #808080;font-family: verdana !important; color: #000000f7 !important; font-weight: 700;padding: 10px; margin-top: 0; border-radius: 8px 8px 0 0;"><img src="'.$rep_img.'"  width="40" height="40" style="background-color:transparent;border-radius: 15px;border:5px solid #d1d1d1">'.$rep_name.'</p>'.$ticket_messages;
			$ticket_message = '<div style="font-size:20px;background:#808080;font-family:verdana!important;color:#000000f7!important;font-weight:700;padding:10px 0px;margin-top:0;border-radius:8px 8px 0 0;float: left;width: 100%;display;flex; align-items: center; "><img src="'.$rep_img.'" width="26" height="26" style="float:left;background-color:transparent;border-radius:15px;border: 4px solid #d1d1d1;display: inline-block;margin-right: 15px; margin-left: 10px;"><div>'.$rep_name.'</div></div>'.$ticket_messages;
		}else{
		 $ticket_message = '<p style="font-size: 20px;background: #808080;font-family: verdana !important; color: #000000f7 !important; font-weight: 700;padding: 10px; margin-top: 0; border-radius: 8px 8px 0 0;">'.$rep_name.'</p>'.$ticket_messages;
		}

	}
         

      $ticket_options_array[] = $ticket_message;
    }

  
    return $ticket_options_array;
    
}

	 function send_notification($data){
	
	extract($data);


		$host_name='https://'.$_SERVER['HTTP_HOST'];
	//$host_name='https://omni-ticketing-xcupb.ondigitalocean.app';

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
	if($ticket_for == 'Share Ticket'){
		$title = $ticket_subject;
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
	$qry = "select * from department_emails where aliseEmail='$aliseEmail' and status=1 and senderID='$senderID' and emailAddr='$email' ";
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
		$ticket_limit = $this->fetchOne("SELECT ticket_limit FROM `admin_details` WHERE `admin_id`='$admin_id'",array());
		
		$qry = "SELECT support_email FROM `admin_details` WHERE admin_id='$admin_id'";
        $qry_value = $this->fetchData($qry,array());
		$qry_value = implode(',',$qry_value);
		$qry_value = explode(',',$qry_value);
		
		$picpermission_qry_val = $this->fetchOne("SELECT profile_picture_permission FROM `user` WHERE `admin_id`='$admin_id' ORDER BY user_id DESC LIMIT 1",array());
		
		$results['roundrobin'] = $override_qry_val;
		$results['ticket_limit'] = $ticket_limit;
		$results['adminEmail'] = $qry_value;
		$results['profile_picture_permission'] = $picpermission_qry_val;
		$results['alldata'] = $result;
        return $results;
    }
	function getEmailDept($id){
        $qry = "select * from department_emails where id=$id";
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
	 
	 if($result > 0){
		 $id = $result['res_id'];
	 	 $qry = "UPDATE email_autoresponses SET response_content='$content' where res_id='$id'";
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
		
    $host_name='https://'.$_SERVER['HTTP_HOST'];
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
		$qry = "UPDATE external_tickets SET ticket_status='6',ticket_assigned_to='$user_id',unassign=1 where ticket_no = $ticket_id";
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
	    $vals="'" . str_replace(",","','",$ticket_search)."'";
		if($user_type == 2){						
            $qry = "SELECT * FROM external_tickets WHERE admin_id = '$user_id' AND is_spam = '$is_spam' AND ticket_no IN ($vals) OR ticket_subject LIKE '%$ticket_search%' OR ticket_from LIKE '%$ticket_search%'";
			$detail_qry = $qry."  ORDER BY ticket_no DESC LIMIT $limit offset $offset";			
		} else {
			$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND is_spam = '$is_spam' AND a.ticket_subject LIKE '%$ticket_search%' OR FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND is_spam = '$is_spam' AND a.ticket_no IN ($vals) OR FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND is_spam = '$is_spam' AND a.ticket_from LIKE '%$ticket_search%' OR a.ticket_created_by = $user_id AND is_spam = '$is_spam' AND a.ticket_subject LIKE '%$ticket_search%' OR a.ticket_from LIKE '%$ticket_search%' OR a.ticket_no IN ($vals)";
			$detail_qry = $qry." Group by a.ticket_no ORDER BY a.ticket_no DESC LIMIT $limit offset $offset";
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
        $qry = "select user_id,agent_name,new_email_alert,reply_email_alert,close_email_alert,send_fullthread,share_tickets,profile_picture_permission,short_code from user where admin_id = '$user_id'".$search_qry;
        $detail_qry = $qry." order by user_id DESC LIMIT ".$limit." Offset ".$offset;   
    }else{
        $qry = "select user_id,agent_name,new_email_alert,reply_email_alert,close_email_alert,send_fullthread,share_tickets,profile_picture_permission,short_code from user where admin_id = '$admin_id'".$search_qry;
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
	//$customer_domain = 'sas.edu.sg';
	/*$customer_id = '3241';	
    $curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	    "operation": "agents",
	    "moduleType": "agents",
	    "api_type": "web",
	    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
	    "element_data": {
	        "action": "get_customercontract_details",
	        "customer_id":"'.$customer_id.'"
	    }
	  }',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json'
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);	
	//$explode_customer_details = explode('||',$response);
	print_r($response);	
	exit;*/
	$from = 'vaithees20@gmail.com';
	$customer_email= $this->fetchOne("SELECT customer_email FROM ticket_customer WHERE admin_id='64' and customer_id ='349'", array());
	$implode_newmail = $customer_email.','.$from;
	echo $implode_newmail;	exit;
		$smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
		$smtp_qry_value = $this->fetchData($smtp_qry,array());
		$hostname = $smtp_qry_value['hostname'];
		$port = $smtp_qry_value['port'];
		$username = $smtp_qry_value['username'];
		$password = $smtp_qry_value['password'];
		$body = 'test'; 
		$from = 'mm@cal4care.com';
		$billerName = 'cal4care';
		$to = 'vaithees20@gmail.com';
		$subject = 'check email';
	                $mail = new PHPMailer();
	                $mail->IsSMTP();
	                $mail->SMTPAuth = true; 
	                $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
	                $mail->Host = $hostname; // sets the SMTP server
	                $mail->Port = $port;    // set the SMTP port for the GMAIL server
	                $mail->Username = $username; // omni_erp SMTP account username
	                $mail->Password = $password; // MGMyd3p2end0YnNi SMTP account password   
	                $mail->SetFrom($from, $billerName);
	                $mail->AddReplyTo($from, $billerName);
		            $mail->addCustomHeader('In-Reply-To',  '<'.$message_id.'>');
					$mail->addCustomHeader('References', $message_id);
		            $mail->Subject = $subject;
	                $mail->MsgHTML($body);
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
					if(!$mail->send()) {			
						print_r($mail->ErrorInfo);exit ;			
					}else{
						echo 'true';
					}exit;
	  
    }
	function checking_email($data){
	  extract($data);
	  $host_name='https://'.$_SERVER['HTTP_HOST'];
	  if($user_type==2){	
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE admin_id='$admin_id'";
	  }else{
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
	  }	  
	  $dep_row = $this->dataFetchAll($dep_query,array());
	  //print_r($dep_row);exit;		 
	  $darr = array();	  	
	  $exp_user_arr = array();	
	  for($i=0;$i<count(array_unique($dep_row));$i++){
		$did = $dep_row[$i]['dept_id'];  
	   	$dusers = $dep_row[$i]['department_users'];				
		$vals="'" . str_replace(",","','",$dusers)."'"; 
		//echo $vals;
		$user_query = "SELECT user_id,sip_login,agent_name,profile_image FROM user WHERE user_id IN ($vals)";		  
		$user_row = $this->dataFetchAll($user_query,array());
		//echo $user_query;  
		//print_r($user_row);exit;
		for($j=0;$j<count($user_row);$j++){
		  $userid = $user_row[$j]['user_id'];
		  $siplogin = $user_row[$j]['sip_login'];
		  $agentname = $user_row[$j]['agent_name'];
		  $pimage = $user_row[$j]['profile_image'];
		  if($pimage==''){
			  
		    $profileimage = 'https://'.$host_name.'/assets/images/user.jpg';
		  }else{
			$profileimage = $pimage;  
		  }
		  // not respond ticket count
			$not_respond_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(created_dt)=MONTH(now()) AND admin_id = '$user_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=3",array());
		  //ticket open count
			$open_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(created_dt)=MONTH(now()) AND admin_id = '$user_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=1",array());		  	
		  //close ticket count
			$close_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(updated_at)=MONTH(now()) AND admin_id = '$user_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND ticket_status=9",array());	
		 //total ticket count(this month)
		  $total_count = $not_respond_ticket_count + $open_ticket_count + $close_ticket_count;	
		  $user_options = array('user_id' => $userid, 'sip_login' => $siplogin, 'agent_name' => $agentname, 'profile_image' => $profileimage,'not_respond_ticket'=>$not_respond_ticket_count,'open_ticket_count'=>$open_ticket_count,'close_ticket_count'=>$close_ticket_count,'total_ticket_count'=>$total_count);
          $user_options_array[] = $user_options;  	
		}	
	  }
	  $user_options_array = array('user_options' => $user_options_array);
	  $tarray = json_encode($user_options_array);           
      print_r($tarray);exit;	  
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
	$qry = "UPDATE admin_details SET `override`='$value',ticket_limit='$ticket_limit' WHERE `admin_id` = '$admin_id'";
	//echo $qry;exit;   
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
     extract($data);//print_r($data);exit;
	 $user_qry = "SELECT timezone_id FROM user WHERE user_id='$admin_id'";
     $user_qry_value = $this->fetchmydata($user_qry,array());
	 $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
     $user_timezone = $this->fetchmydata($user_timezone_qry,array());
     date_default_timezone_set($user_timezone);  
     $updated_at = date("Y-m-d H:i:s"); 
	 //echo $updated_at;exit;  
     $explode = explode(',', $value);	   
     for($i=0;$i<count($explode);$i++){
       $val = $explode[$i];//echo $val;exit;		
       $qry = "UPDATE external_tickets SET delete_status='1' WHERE admin_id='$admin_id' AND ticket_no='$val'";	  
       $parms = array();
       $results = $this->db_query($qry,$parms);      
       $output = $results == 1 ? 1 : 0;     
     }
     return $output;
   } 	
public function delete_ticket($data){
     extract($data);    
     $explode = explode(',', $value);	 
     for($i=0;$i<count($explode);$i++){
       $val = $explode[$i]; 
       $qry = "DELETE FROM external_tickets WHERE admin_id='$admin_id' AND ticket_no='$val'";
       $parms = array();
       $results = $this->db_query($qry,$parms);      
       $output = $results == 1 ? 1 : 0;     
     }
     return $output;
   }	
   public function restore_ticket($data){
     extract($data);     
     $qry = "UPDATE external_tickets SET delete_status='0' WHERE admin_id='$admin_id' AND ticket_no='$ticket_id'";	  
     $parms = array();
     $results = $this->db_query($qry,$parms);      
     $output = $results == 1 ? 1 : 0;     
     return $output;
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
public function agent_picture_permission($data){
	  extract($data);
	  $updateqry = "UPDATE user SET profile_picture_permission='$value' WHERE user_id='$user_id'";
	  $update_data = $this->db_query($updateqry, array());
      $qry = "UPDATE user SET profile_picture_permission='$value' WHERE admin_id='$user_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
public function updateEmailResponse_status($data){
	  extract($data);	
      $qry = "UPDATE email_autoresponses SET status='$status' WHERE admin_id='$admin_id' AND response_for='$response_for' AND dept_id='$dept_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }	
	
  
		  

function get_unassign_tickets($data){
		extract($data);	
	//print_r($data);exit;
		if($user_type == 2){	
		  if($ticket_department=='All'){	
		  $qry = "SELECT a.*,a.ticket_subject as subject, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND a.unassign=0 AND a.is_spam=0 AND b.repliesd_by='Customer' AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8";
		  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
		  $detail_qry2 = $qry." ORDER BY updated_at DESC";
		  }
		  else{	
			  $qry = "SELECT a.*,a.ticket_subject as subject, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND a.ticket_department='$ticket_department' AND a.is_spam=0 AND a.unassign=0 AND b.repliesd_by='Customer' AND a.delete_status=0 AND a.ticket_status!=9 AND a.ticket_status!=8";
			  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
			  $detail_qry2 = $qry." ORDER BY updated_at DESC";
		  }	
		}else{
			$department_array_qry = "SELECT dept_id FROM departments WHERE FIND_IN_SET('$user_id',department_users)";
			//echo $department_array_qry;exit;
		    $department_options = $this->dataFetchAll($department_array_qry, array());
			//print_r($department_options);exit;
		  if($ticket_department=='All'){		
			  $qry = "SELECT a.*,a.ticket_subject as subject, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND a.unassign=0 AND b.repliesd_by='Customer' AND a.delete_status=0 AND a.is_spam=0 AND a.ticket_status!=9 AND a.ticket_status!=8";
			  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
			  $detail_qry2 = $qry." ORDER BY updated_at DESC";
		  }else{	
			  $qry = "SELECT a.*,a.ticket_subject as subject, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND a.ticket_department='$ticket_department' AND FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND a.unassign=0 AND b.repliesd_by='Customer' AND a.delete_status=0 AND a.is_spam=0 AND a.ticket_status!=9 AND a.ticket_status!=8";
			  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
			  $detail_qry2 = $qry." ORDER BY updated_at DESC";
		  }
		}		
//print_r($detail_qry); exit;
//echo $detail_qry;exit;
		$result = $this->dataFetchAll($detail_qry, array());
        for($i = 0; count($result) > $i; $i++){
		  $ticket_customer_id = $result[$i]['customer_id'];	
		  $ticket_customer_name = $result[$i]['customer_name'];	
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
          $ticket_subject = $result[$i]['subject'];
		  $spammed = $result[$i]['spammed'];
		  $unassign_value = $result[$i]['unassign'];
		  $ticket_type = $result[$i]['type'];	
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array());			
		  //$assignedto_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_assigned_to'";              
         // $assignedto = $this->fetchmydata($assignedto_qry,array());			
		  $ticket_assigned_to = explode(',',$ticket_assigned_to); 
		  if(count($ticket_assigned_to) == 1 && $unassign_value == 1){
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
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
          $ticket_options_array[] = $ticket_options;
        }
		//$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		//$department_options_array = $this->dataFetchAll($department_array_qry, array());			
		//print_r($department_array_qry); print_r($department_options_array); exit;
		//$status_options_array = $this->dataFetchAll($status_array_qry, array());
	    $department_array_qry = "SELECT dept_id as department_id,department_name FROM departments WHERE admin_id='$admin_id' AND delete_status='0' AND has_email='1'";
	    $dep_array = $this->dataFetchAll($department_array_qry, array());
		for($j = 0; $j < count($dep_array); $j++){
		 $department_id = $dep_array[$j]['department_id'];
		 $department_name = $dep_array[$j]['department_name'];
		 $ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM `external_tickets` WHERE admin_id='$admin_id' AND ticket_department='$department_id' AND unassign=0 AND delete_status=0 AND is_spam=0",array());		
		 $dept_options = array('department_id' => $department_id, 'department_name' => $department_name, 'ticket_count' => $ticket_count);
		 $department_options_array[] = $dept_options;  
	    }
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

public function get_deleted_tickets($data){
	extract($data);
	$search_qry = "";
    if($search_text!= ""){
       $search_qry= " and (a.ticket_no like '%".$search_text."%' or a.ticket_from like '%".$search_text."%' or a.ticket_subject like '%".$search_text."%')";
    }
	//print_r($data);exit;
	if($user_type == 2){			
		  $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND a.delete_status=1.$search_qry";
		  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
		  $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";	
	}else{	
		  $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.admin_id ='$admin_id' AND FIND_IN_SET('$user_id',a.ticket_assigned_to ) AND a.delete_status=1.$search_qry";
			$detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
			$detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";		  
	}		
//print_r($detail_qry); exit;
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
		  $created_time = date("Y-m-d H:i:s", $time_ago);	  			
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed);
          $ticket_options_array[] = $ticket_options;
    }			
    $status = array('status' => 'true');
    $ticket_options_array = array('ticket_options' => $ticket_options_array);		
	$total_count = $this->dataRowCount($detail_qry2,array());
	$total = array('total' => $total_count);            
    $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	$list_info_arr = array('list_info' => $list_info);            
    $merge_result = array_merge($status, $ticket_options_array, $total, $list_info_arr);	
    $tarray = json_encode($merge_result);           
    print_r($tarray);exit;
    }

public function forward_ticket($data){
   extract($data);//print_r($data);exit;
   $qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
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
	$messages = $this->getTicketThread($ticket_id);
	foreach($messages as $m){
	   $mess[] = '<div style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
	}
	$mess = implode('<br>',$mess);	
	$media_qry = "SELECT ticket_media,repliesd_by FROM external_tickets_data WHERE ticket_id ='$ticket_id'";
    $media_detail_qry=$media_qry." ORDER BY ticket_message_id DESC";
    $media_result =  $this->dataFetchAll($media_detail_qry, array());//print_r($media_result);exit;
    $media_count = $this->dataRowCount($media_qry,array());//echo $media_count;exit;
	$qry = "select senderID from department_emails where aliseEmail = '$ticket_from'";
    $from =  $this->fetchOne($qry, array());
	$message_id = $this->fetchOne("SELECT ticket_reply_id FROM external_tickets_data WHERE ticket_id = '$ticket_id' and ticket_reply_id !=''",array());
    $smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
	$smtp_qry_value = $this->fetchData($smtp_qry,array());
	$hostname = $smtp_qry_value['hostname'];
	$port = $smtp_qry_value['port'];
	$username = $smtp_qry_value['username'];
	$password = $smtp_qry_value['password'];
    require_once('class.phpmailer.php'); 
	$subject = $subject; 
    $body = $mess;   
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls';  
	$mail->Host = $hostname; // sets the SMTP server
    $mail->Port = $port;
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
	if(count($media_count) > 0){		
		foreach($media_result as $media_result_file){
               $attach = $media_result_file['ticket_media'];
			   $sentby = $media_result_file['repliesd_by'];
			   if($sentby=='Customer'){
				   $str = str_replace("omniChannel", "//", $attach);
				   $t = explode('////', $str);
				   $mail->addStringAttachment(file_get_contents($attach), $t[1]);
               }else{
			       $str = str_replace("ext-ticket-image", "//", $attach);
				   $t = explode('////', $str);
				   $mail->addStringAttachment(file_get_contents($attach), $t[1]);
			   }
        }
	}
	$mail->addAddress($forward_to);
	// print_r($mail); exit;
    // $mail->send();
	if(!$mail->send()) {						
		//$res = "Mailer Error: " . $mail->ErrorInfo;
		$status = array('status' => $mail->ErrorInfo);     
		$tarray = json_encode($status);           
        print_r($tarray);exit;
	} 
	else {
		$user_name = $this->fetchOne("SELECT agent_name FROM user WHERE user_id = '$user_id'",array());
		$user_timezone_id = $this->fetchOne("SELECT timezone_id FROM user WHERE user_id = '$user_id'",array());
	    $user_timezone=$this->fetchOne("SELECT name FROM timezone WHERE id='$user_timezone_id'", array());		
        date_default_timezone_set($user_timezone);  
        $created_dt = date("Y-m-d H:i:s");
		$update_data = $this->db_insert("INSERT INTO external_ticket_forward(ticket_reply_id,created_by,created_name,forward_to,created_dt) VALUES ($ticket_message_id,$user_id,'$user_name','$forward_to','$created_dt')", array());
		//echo "INSERT INTO external_ticket_forward(ticket_reply_id,created_by,created_name) VALUES ('$message_id','$user_id','$user_name')";exit;
		$status = array('status' => 'true');     
		$tarray = json_encode($status);           
        print_r($tarray);exit;		
	}
}
	
function filter_getmyExternalTicket($data){
		extract($data);//print_r($data);exit;
		$dep = $ticket_department;
		if($user_type == 2){         
			if($ticket_status == 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
					//echo $detail_qry;exit;
			} else if($ticket_status == 'All' && $ticket_department != 'All'){
				 	$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			
			} else if($ticket_status != 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_status IN ($ticket_status) and is_spam = '$is_spam' and unassign=1 and delete_status=0";
				 	$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}  else {
				$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status IN ($ticket_status) and ticket_department LIKE '%$ticket_department%' and unassign=1 and delete_status=0";
				 $detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}		
		}// admin side condition ends here 
		else {
			$vals="'" . str_replace(",","','",$user_id)."'";			
			if($ticket_status == 'All' && $ticket_department == 'All'){
				//$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and is_spam = '$is_spam' and ticket_status=3 and unassign=1 and delete_status=0 OR a.ticket_created_by IN ($vals) and is_spam = '$is_spam' and ticket_status=1 and unassign=1 and delete_status=0";
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and is_spam = '$is_spam' and unassign=1 and delete_status=0 and (ticket_status=3 OR ticket_status=1) OR a.ticket_created_by IN ($vals) and is_spam = '$is_spam' and unassign=1 and delete_status=0 and (ticket_status=3 OR ticket_status=1)";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";				
				$detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";				
			} else if($ticket_status == 'All' && $ticket_department != 'All'){
				 	$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0 OR a.ticket_created_by IN ($vals) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			} else if($ticket_status != 'All' && $ticket_department == 'All'){
					$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' and unassign=1 and delete_status=0 OR a.ticket_created_by IN ($vals) and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' and unassign=1 and delete_status=0";
				//echo $qry;exit;
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}  else {
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0 OR a.ticket_created_by IN ($vals) and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}
		}		
		

//print_r($ticket_options_array); exit;

					//TOTAL COUNT CODE 05-07-2021
	//echo $dep;exit;
		if($user_type == 2){
	if($dep == 'All'){
       // $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status group by st.status_id order by st.status_id";
   			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
     		 if($sid==1 || $sisd==3){
				 $sub_qry = " AND delete_status=0 AND unassign=1";
				}else{
				 $sub_qry = " AND delete_status=0";	
				}
   			     $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'$sub_qry";     

     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
	
	
	}else if($dep != 'All'){
    //$status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status WHERE et.ticket_department = '$ticket_department' group by et.ticket_status order by et.ticket_status";
		
				$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
     		 if($sid==1 || $sisd==3){
				 $sub_qry = " AND delete_status=0 AND unassign=1";
				}else{
				 $sub_qry = " AND delete_status=0";	
				}
   			     $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND is_spam=0 AND admin_id = '$user_id'$sub_qry";     

     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		
		
	}else{
      $status_array_qry = "SELECT status_id,status_desc FROM status";
   }
		}else{
		
	if($dep == 'All'){	    
	$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			 if($sid==1 || $sisd==3){
				 $sub_qry = " AND delete_status=0 AND unassign=1";
				}else{
				 $sub_qry = " AND delete_status=0";	
				}
   			 ///    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_created_by ='$user_id' AND is_spam=0";     
		
				$count_query="SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = '$sid' AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0$sub_qry OR ticket_status = '$sid' AND ticket_created_by ='$user_id' AND is_spam=0$sub_qry";
		//echo $count_query;exit;		
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		 //echo json_encode($status_array_qry, true);exit;
		
	}else if($dep != 'All'){
		// $status_array_qry = "SELECT count(et.ticket_no) AS status_count,st.status_id,st.status_desc FROM status AS st LEFT JOIN external_tickets AS et ON st.status_id = et.ticket_status JOIN external_tickets_data b ON et.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',et.ticket_assigned_to ) and et.ticket_department = '$ticket_department' OR et.ticket_created_by ='$user_id' group by et.ticket_status order by et.ticket_status";
		
			$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			   if($sid==1 || $sisd==3){
				 $sub_qry = " AND delete_status=0 AND unassign=1";
				}else{
				 $sub_qry = " AND delete_status=0";	
				}
   			   //  $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) OR ticket_created_by ='$user_id' AND is_spam=0";
				
				  $count_query = "SELECT * FROM external_tickets WHERE ticket_department = '$ticket_department' AND ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 $sub_qry OR ticket_created_by ='$user_id' AND is_spam=0 AND ticket_status = $sid AND ticket_department = '$ticket_department'$sub_qry";
				
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
		  $ticket_customer_id = $result[$i]['customer_id'];	
		  $ticket_customer_name = $result[$i]['customer_name'];	
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
		  $ticket_type = $result[$i]['type'];
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
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
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
        $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	    $list_info_arr = array('list_info' => $list_info);    
        $merge_result = array_merge($status, $department_options_array, $status_options_array,$count_status_options_array, $priority_options_array, $ticket_options_array,$total,$list_info_arr); 		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
    }
	
function spam_getmyExternalTicket($data){
		extract($data);//print_r($data);exit;
	    $search_qry = "";
		if($search_text!= ""){
			$search_qry= " and (ticket_no like '%".$search_text."%' or ticket_from like '%".$search_text."%' or ticket_subject like '%".$search_text."%')";
		}
		$dep = $ticket_department;
		if($user_type == 2){         		
			if($ticket_status == 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status!=9 and delete_status=0.$search_qry";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
					//echo $detail_qry;exit;
			} else if($ticket_status == 'All' && $ticket_department != 'All'){
				 	$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and delete_status=0.$search_qry";
					$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			
			} else if($ticket_status != 'All' && $ticket_department == 'All'){
					$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_status IN ($ticket_status) and is_spam = '$is_spam' and delete_status=0.$search_qry";
				 	$detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}  else {
				$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and is_spam = '$is_spam' and ticket_status IN ($ticket_status) and ticket_department LIKE '%$ticket_department%' and delete_status=0.$search_qry";
				 $detail_qry = $qry." ORDER BY updated_at DESC LIMIT $limit offset $offset";
				$detail_qry2 = $qry." ORDER BY updated_at DESC";
			}
$result = $this->dataFetchAll($detail_qry, array());	
        for($i = 0; count($result) > $i; $i++){
		  $ticket_customer_id = $result[$i]['customer_id'];	
	      $ticket_customer_name = $result[$i]['customer_name'];	
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
          $ticket_type = $result[$i]['type'];
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
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
          $ticket_options_array[] = $ticket_options;
        }		
		}// admin side condition ends here 
		else {
			
			if($ticket_status == 'All' && $ticket_department == 'All'){				
				/*$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0 OR a.ticket_created_by = $user_id and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";				
				$detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";		
				$detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";*/
				$dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
				$dep_row = $this->dataFetchAll($dep_query,array());//print_r($dep_row);exit;
				$darr = array();	  	
				$exp_user_arr = array();	
				for($i=0;$i<count($dep_row);$i++){
					$did = $dep_row[$i]['dept_id'];  
					$dusers = $dep_row[$i]['department_users'];				
					$vals="'" . str_replace(",","','",$dusers)."'"; 
					//echo $vals;exit;  
					$user_query = "SELECT user_id FROM user WHERE user_id IN ($vals) AND share_tickets=1 UNION SELECT user_id FROM user WHERE user_id='$user_id'";		
					$user_row = $this->dataFetchAll($user_query,array());//print_r($user_row);exit;
					for($j=0;$j<count($user_row);$j++){
					  $uval = $user_row[$j]['user_id'];//echo $uval;	  
					  $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE ticket_assigned_to = '$uval' AND ticket_department = '$did' AND is_spam = '$is_spam' AND ticket_status!=9 AND unassign=1 AND delete_status=0 OR ticket_created_by= '$uval' AND ticket_department = '$did' AND is_spam = '$is_spam' AND ticket_status!=9 AND delete_status=0";//echo $tic_query;			  	
					  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				      $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
					  $result = $this->dataFetchAll($detail_qry,array());
					  for($i = 0; count($result) > $i; $i++){
						      $ticket_customer_id = $result[$i]['customer_id'];	
	                          $ticket_customer_name = $result[$i]['customer_name'];
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
							  $ticket_type = $result[$i]['type'];
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
							  }else{
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
							  $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
							  $ticket_options_array[] = $ticket_options;
						} // ticket for loop					  
					} // dep user for loop	
				 } // dep for loop
			}
				else if($ticket_status == 'All' && $ticket_department != 'All'){				
				 	/*$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and delete_status=0 and unassign=1 OR a.ticket_created_by = $user_id and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and ticket_status!=9 and unassign=1 and delete_status=0";				 
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";*/
					$dep_query = "SELECT dept_id,department_users FROM `departments` WHERE dept_id = '$ticket_department'";
				$dep_row = $this->dataFetchAll($dep_query,array());//print_r($dep_row);exit;
				$darr = array();	  	
				$exp_user_arr = array();	
				for($i=0;$i<count($dep_row);$i++){
					$did = $dep_row[$i]['dept_id'];  
					$dusers = $dep_row[$i]['department_users'];				
					$vals="'" . str_replace(",","','",$dusers)."'"; 
					//echo $vals;exit;  
					$user_query = "SELECT user_id FROM user WHERE user_id IN ($vals) AND share_tickets=1 UNION SELECT user_id FROM user WHERE user_id='$user_id'";		
					$user_row = $this->dataFetchAll($user_query,array());//print_r($user_row);exit;
					for($j=0;$j<count($user_row);$j++){
					  $uval = $user_row[$j]['user_id'];//echo $uval;	  
					  $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE ticket_assigned_to = '$uval' AND ticket_department = '$did' AND is_spam = '$is_spam' AND ticket_status!=9 AND unassign=1 AND delete_status=0 OR ticket_created_by= '$uval' AND ticket_department = '$did' AND is_spam = '$is_spam' AND ticket_status!=9 AND delete_status=0";//echo $tic_query;			  	
					  $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				      $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
					  $result = $this->dataFetchAll($detail_qry,array());
					  for($i = 0; count($result) > $i; $i++){
						      $ticket_customer_id = $result[$i]['customer_id'];	
							  $ticket_customer_name = $result[$i]['customer_name'];
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
							  $ticket_type = $result[$i]['type'];
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
							  }else{
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
							  $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $assignedto, 'department' => $department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus, 'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'created_time2'=>$created_time2 ,'ticket_from'=>$ticket_from,'ticket_new_status'=>$ticket_new_status,'closed_at'=>$closed_at,'spammed'=>$spammed,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'ticket_type'=>$ticket_type);
							  $ticket_options_array[] = $ticket_options;
						} // ticket for loop					  
					} // dep user for loop	
				 } // dep for loop
			} else if($ticket_status != 'All' && $ticket_department == 'All'){				  
					$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' and delete_status=0 OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' and delete_status=0";
				//$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and is_spam = '$is_spam' ";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}  else {				
				$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam'  and delete_status=0 OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' and delete_status=0";
				 //$qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_assigned_to IN ($vals) and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' OR a.ticket_created_by = $user_id and a.ticket_status IN ($ticket_status) and a.ticket_department LIKE '%$ticket_department%' and is_spam = '$is_spam' ";
				 $detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
				 $detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
			}
		}		
		

//print_r($ticket_options_array); exit;

					//TOTAL COUNT CODE 05-07-2021
	//echo $dep;exit;
		if($user_type == 2){
	if($dep == 'All'){
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
	
	
	}else if($dep != 'All'){
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
		
	if($dep == 'All'){	    
	$arr = array();		
    $query = "SELECT * FROM status";
		$row = $this->dataFetchAll($query,array());
			for($i=0;$i<10;$i++){	
				$status_name = $row[$i]['status_name'];
     		 $sid = $row[$i]['status_id'];
   			 ///    $count_query = "SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_created_by ='$user_id' AND is_spam=0";     
		
				$count_query="SELECT COUNT(ticket_no) as status_count FROM external_tickets WHERE ticket_status = $sid AND FIND_IN_SET('$user_id',ticket_assigned_to ) AND is_spam=0 OR ticket_status = $sid AND ticket_created_by ='$user_id' AND is_spam=0";
		//echo $count_query;		
     $data1=$this->fetchData($count_query,array());
		array_push($arr, array("status_id"=>$sid,"status_name"=>$status_name,"status_count"=>$data1['status_count']));
			
			}
		$status_array_qry = $arr;
		 //echo json_encode($status_array_qry, true);exit;
		
	}else if($dep != 'All'){
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
		/*$result = $this->dataFetchAll($detail_qry, array());	
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
        }*/
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
        $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	    $list_info_arr = array('list_info' => $list_info);    
        $merge_result = array_merge($status, $department_options_array, $status_options_array,$count_status_options_array, $priority_options_array, $ticket_options_array,$total,$list_info_arr); 		
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
    }				

	public function ticket_dashboard($data)
	{
	  extract($data);//print_r($data);exit;
		$host_name='https://'.$_SERVER['HTTP_HOST'];

	  if($user_type==2){	
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE admin_id='$admin_id'";
	  }else{
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
	  }	  
	  $dep_row = $this->dataFetchAll($dep_query,array());
	  $darr = array();	  		  	
	  //print_r($dep_row);exit;
	  //$unique_arr = array();$temp = array();	
	  //$temp = array_unique(array_column($dep_row, 'department_users'));
      //$unique_arr = array_intersect_key($dep_row, $temp);	
	  //print_r($unique_arr);exit;			
	  for($i=0;$i<count($dep_row);$i++){
		$did = $dep_row[$i]['dept_id'];  
	   	$dusers = $dep_row[$i]['department_users'];				
		$vals="'" . str_replace(",","','",$dusers)."'"; 
		//echo $vals;
		$user_query = "SELECT user_id,sip_login,agent_name,profile_image FROM user WHERE user_id IN ($vals)";		  
		$user_row = $this->dataFetchAll($user_query,array());
		//echo $user_query;  
		//print_r($user_row);exit;
		for($j=0;$j<count($user_row);$j++){
		  $userid = $user_row[$j]['user_id'];
		  $siplogin = $user_row[$j]['sip_login'];
		  $agentname = $user_row[$j]['agent_name'];
		  $pimage = $user_row[$j]['profile_image'];
		  if($pimage==''){
		    $profileimage = 'https://'.$host_name.'/assets/images/user.jpg';
		  }else{
			$profileimage = $pimage;  
		  }
		  // not respond ticket count
			$not_respond_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(created_dt)=MONTH(now()) AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=3",array());
		  //ticket open count
			$open_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(created_dt)=MONTH(now()) AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND (ticket_status=1 OR ticket_status=6)",array());		  	
		  //close ticket count
			$close_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE MONTH(updated_at)=MONTH(now()) AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND ticket_status=9",array());	
		 //total ticket count(this month)
		  $total_count = $not_respond_ticket_count + $open_ticket_count + $close_ticket_count;	
		  $user_options = array('user_id' => $userid, 'sip_login' => $siplogin, 'agent_name' => $agentname, 'profile_image' => $profileimage,'not_respond_ticket'=>$not_respond_ticket_count,'open_ticket_count'=>$open_ticket_count,'close_ticket_count'=>$close_ticket_count,'total_ticket_count'=>$total_count);
			//print_r($user_options);exit;
          $user_options_array[] = $user_options;  	
		}	
	  }
	  $user_options_array = array('user_options' => $user_options_array);
	  $tarray = json_encode($user_options_array);           
      print_r($tarray);exit;
    }

public function ticket_dashboard_dateFilter($data){
	extract($data);
	$host_name='https://'.$_SERVER['HTTP_HOST'];
	  if($user_type==2){	
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE admin_id='$admin_id'";
	  }else{
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
	  }	  
	  $dep_row = $this->dataFetchAll($dep_query,array());
	  //print_r($dep_row);exit;		 
	  $darr = array();	  	
	  $exp_user_arr = array();	
	  for($i=0;$i<count($dep_row);$i++){
		$did = $dep_row[$i]['dept_id'];  
	   	$dusers = $dep_row[$i]['department_users'];				
		$vals="'" . str_replace(",","','",$dusers)."'"; 
		//echo $vals;
		$user_query = "SELECT user_id,sip_login,agent_name,profile_image FROM user WHERE user_id IN ($vals)";		  
		$user_row = $this->dataFetchAll($user_query,array());
		//echo $user_query;  
		//print_r($user_row);exit;
		for($j=0;$j<count($user_row);$j++){
		  $userid = $user_row[$j]['user_id'];
		  $siplogin = $user_row[$j]['sip_login'];
		  $agentname = $user_row[$j]['agent_name'];
		  $pimage = $user_row[$j]['profile_image'];
		  if($pimage==''){
		    $profileimage = 'https://'.$host_name.'/assets/images/user.jpg';
		  }else{
			$profileimage = $pimage;  
		  }
		  // not respond ticket count
		  if($from_date==$to_date){
		  	$not_respond_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(created_dt) = '$from_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=3",array());
		  	$open_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(created_dt) = '$from_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND (ticket_status=1 OR ticket_status=6)",array());
		  	$close_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(updated_at) = '$from_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND ticket_status=9",array());
		  }else{
		  	$not_respond_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(created_dt) >= '$from_date' and DATE(created_dt) <= '$to_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=3",array());
		  	$open_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(created_dt) >= '$from_date' and DATE(created_dt) <= '$to_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=1",array());
		  	$close_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE DATE(updated_at) >= '$from_date' and DATE(updated_at) <= '$to_date' AND admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND ticket_status=9",array());
		  }	
		 //total ticket count
		  $total_count = $not_respond_ticket_count + $open_ticket_count + $close_ticket_count;	
		  $user_options = array('user_id' => $userid, 'sip_login' => $siplogin, 'agent_name' => $agentname, 'profile_image' => $profileimage,'not_respond_ticket'=>$not_respond_ticket_count,'open_ticket_count'=>$open_ticket_count,'close_ticket_count'=>$close_ticket_count,'total_ticket_count'=>$total_count);
          $user_options_array[] = $user_options;  	
		}	
	  }
	  $user_options_array = array('user_options' => $user_options_array);
	  $tarray = json_encode($user_options_array);           
      print_r($tarray);exit;
}
	
public function ticket_dashboard_customFilter($data){
	$host_name='https://'.$_SERVER['HTTP_HOST'];

	extract($data);
	  if($user_type==2){	
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE admin_id='$admin_id'";
	  }else{
	   $dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
	  }	  
	  $dep_row = $this->dataFetchAll($dep_query,array());
	  //print_r($dep_row);exit;		 
	  $darr = array();	  	
	  $exp_user_arr = array();	
	  for($i=0;$i<count(array_unique($dep_row));$i++){
		$did = $dep_row[$i]['dept_id'];  
	   	$dusers = $dep_row[$i]['department_users'];				
		$vals="'" . str_replace(",","','",$dusers)."'"; 
		//echo $vals;
		$user_query = "SELECT user_id,sip_login,agent_name,profile_image FROM user WHERE user_id IN ($vals)";		  
		$user_row = $this->dataFetchAll($user_query,array());
		//echo $user_query;  
		//print_r($user_row);exit;
		for($j=0;$j<count($user_row);$j++){
		  $userid = $user_row[$j]['user_id'];
		  $siplogin = $user_row[$j]['sip_login'];
		  $agentname = $user_row[$j]['agent_name'];
		  $pimage = $user_row[$j]['profile_image'];
		  if($pimage==''){
		    $profileimage = 'https://'.$host_name.'/assets/images/user.jpg';
		  }else{
			$profileimage = $pimage;  
		  }
		  if($custom_value=='Today'){
		  	$qry = " AND DATE(created_dt) = CURDATE()";
		  }
		  if($custom_value=='Current Month'){
		  	$qry = " AND MONTH(created_dt)=MONTH(now())";
		  }
		  if($custom_value=='Last 30 Days'){
		  	$qry = " AND created_dt BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";
		  }
		  	
		  $not_respond_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND ticket_status=3$qry",array());
		  $open_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND unassign=1 AND (ticket_status=1 OR ticket_status=6)$qry",array());
		  $close_ticket_count = $this->fetchOne("SELECT COUNT(ticket_no) FROM external_tickets WHERE admin_id = '$admin_id' AND ticket_assigned_to='$userid' AND delete_status=0 AND ticket_status=9$qry",array());		  	
		 //total ticket count
		  $total_count = $not_respond_ticket_count + $open_ticket_count + $close_ticket_count;	
		  $user_options = array('user_id' => $userid, 'sip_login' => $siplogin, 'agent_name' => $agentname, 'profile_image' => $profileimage,'not_respond_ticket'=>$not_respond_ticket_count,'open_ticket_count'=>$open_ticket_count,'close_ticket_count'=>$close_ticket_count,'total_ticket_count'=>$total_count);
          $user_options_array[] = $user_options;  	
		}	
	  }
	  $user_options_array = array('user_options' => $user_options_array);
	  $tarray = json_encode($user_options_array);           
      print_r($tarray);exit;
}	
public function ticket_shared_agent($data){
	extract($data);
	$dep_query = "SELECT dept_id,department_users FROM `departments` WHERE FIND_IN_SET('$user_id',department_users)";
	$dep_row = $this->dataFetchAll($dep_query,array());//print_r($dep_row);exit;
	for($i=0;$i<count($dep_row);$i++){
		$did = $dep_row[$i]['dept_id'];  
		$dusers = $dep_row[$i]['department_users'];				
		$vals="'" . str_replace(",","','",$dusers)."'"; 
		//echo $vals;exit;  
		$user_query = "SELECT user_id,agent_name FROM user WHERE user_id IN ($vals) AND share_tickets=1 UNION SELECT user_id,agent_name FROM user WHERE user_id='$user_id'";		
		$user_row = $this->dataFetchAll($user_query,array());//print_r($user_row);exit;
		for($j=0;$j<count($user_row);$j++){
		    $uid = $user_row[$j]['user_id'];
		    $username = $user_row[$j]['agent_name'];
		    $user_options = array('user_id' => $uid, 'username' => $username);
			$user_options_array[] = $user_options;
		}
	}
	$status_array = array('status' => 'true');
	$user_options_array = array('user_options' => $user_options_array);
	$array_merge = array_merge($status_array,$user_options_array);
	$tarray = json_encode($array_merge);           
    print_r($tarray);exit;
}

public function check_rounrobin($data){
  extract($data);
	  $setting_ary = $this->fetchData("SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'",array());
	  $ticket_limit = $setting_ary['ticket_limit'];
	  $override = $setting_ary['override'];
	  $userid = $this->fetchOne("SELECT ticket_assigned_to FROM `external_tickets` WHERE `ticket_no`='$ticket_id' AND unassign=1 AND delete_status=0",array());	
	  $dept = $this->fetchOne("SELECT ticket_department FROM `external_tickets` WHERE `ticket_no`='$ticket_id'",array());
	//echo $userid;exit;
	  if($userid != ''){
		  $user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$userid' AND `ticket_status`=3 AND ticket_department='$dept' AND `unassign`=1 AND `delete_status`=0",array());
		  if($user_ticket_count < $ticket_limit){
			$lastTic = $this->fetchData("SELECT * FROM external_tickets WHERE ticket_department='$dept' AND ticket_status=3 AND unassign=0 AND delete_status=0 ORDER BY ticket_no ASC LIMIT 1",array());
			$Ticid = $lastTic['ticket_no'];
			if($Ticid != ''){
				$get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept)",array());
				$dept_users = explode(',',$get_dep);
				$dept_users = array_unique($dept_users);
				$current_array_val = array_search($userid, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0];
				$update_data = $this->db_query("UPDATE external_tickets SET ticket_assigned_to='$userid',next_assign_for='$next_array_val',unassign=1 WHERE ticket_no='$Ticid'", array());			
				$parms = array();
				$results = $this->db_query($update_data,$parms);      
				$output = $results == 1 ? 1 : 0;    
				return  $output;
			}else{
				$output = 0;    
				return  $output;
			}
		  }else{
				$output = 0;    
				return  $output;
		  }
      }   
}	

public function wallboard_ticket_count($data){
  extract($data);
  $depArr = array();$userticArr = array();$queueticArr = array();
  $qry = "SELECT dept_id FROM `departments` WHERE admin_id='$admin_id' AND department_users LIKE ('%$user_id%')";
  $results =  $this->dataFetchAll($qry, array());
  for($i = 0; count($results) > $i; $i++){
     $dept_id = $results[$i]['dept_id'];
     array_push($depArr,$dept_id);
  }
  //print_r($depArr);exit;
  $cnt = count($depArr);
  for($j = 0; $j < $cnt; $j++){		  
    $user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$user_id' AND `ticket_department`=$depArr[$j] AND `ticket_status`=3 AND `unassign`=1 AND `delete_status`=0",array());	  
	array_push($userticArr,$user_ticket_count);
	$queue_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to` LIKE '%$user_id%' AND `ticket_department`=$depArr[$j] AND `ticket_status`=3 AND `unassign`=0 AND `delete_status`=0",array());	  
	array_push($queueticArr,$queue_ticket_count);  
  }
  $user_pending_count = array_sum($userticArr);
  $queue_count = array_sum($queueticArr);	
  $result["user_pending_count"] = $user_pending_count;
  $result["queue_count"] = $queue_count;	
  return $result;	
}
	
public function add_priority_filter($data){
	  extract($data);		
	  $qry = "select * from user where user_id='$user_id'";//echo $qry;exit;
	  $result = $this->fetchData($qry, array());
	  //print_r($result);exit;
	  $user_timezone_id = $result['timezone_id'];
	  $user_timezone=$this->fetchOne("SELECT name FROM timezone WHERE id='$user_timezone_id'", array());	
      date_default_timezone_set($user_timezone);  
      $created_dt = date("Y-m-d H:i:s");
	  if($result['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $result['admin_id']; 
	  }
	  $qry = "select * from priority_words_filtering where filter_word LIKE '%$keyword%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "INSERT INTO priority_words_filtering(priority,key_word,user_id,admin_id,created_at) VALUES ('$priority','$keyword','$user_id','$admin_id','$created_dt')";
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
public function list_priority_filter($data){
	  extract($data);
	  $qry = "select user_type,admin_id from user where user_id='$user_id'";
	  $res = $this->fetchsingledata($qry, array());	
	  if($res['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $res['admin_id']; 
	  }
	    $qry = "select pw.*,p.id as priority_id,p.priority from priority_words_filtering pw LEFT JOIN priority p ON p.id = pw.priority where admin_id ='$admin_id'";
		return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
	}
public function edit_priority_filter($key_id){	 
	  $qry = "select pw.*,p.id as priority_id,p.priority from priority_words_filtering pw LEFT JOIN priority p ON p.id = pw.priority where pw.id ='$key_id'";	
	  return $this->dataFetchAll($qry, array());
	}	
   function update_priority_filter($data){
	  extract($data);	
      /*$qry = "UPDATE priority_words_filtering SET key_word='$key_word' where id='$key_id'";		
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;*/
      $qry = "select * from priority_words_filtering where key_word LIKE '%$key_word%'";
	  $result = $this->fetchData($qry, array());   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "UPDATE priority_words_filtering SET key_word='$key_word' where id='$key_id'";
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
public function delete_priority_filter($key_id){     
     $qry = "Delete FROM priority_words_filtering WHERE id='$key_id'";
     $qry_result = $this->db_query($qry, array());
     if($qry_result == 1){
      $result = 1;
     }else{
      $result = 0;
    }
    return $result;
  }
function getMyAliasEmails($admin_id){	
    $group_alert_qry = "SELECT aliseEmail FROM department_emails where status=1";
    $group_alert_email = $this->dataFetchAll($group_alert_qry,array());
	$array = array_column($group_alert_email, 'aliseEmail');	
	$pipemail= $this->fetchOne("Select support_email from admin_details where admin_id='$admin_id'", array());	
	$array[] = $pipemail;
	return $array;
   }
function getPriorities(){
    $qry = "SELECT id,priority FROM priority";
    $result = $this->dataFetchAll($qry,array());
	return $result;
   }
function searchFunction($data){
		extract($data);
	
		if($user_type == 2){
			if($type=='spam'){
			 $qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_subject LIKE '%$ticket_search%' and is_spam = '1'";			
		    }else{
             $qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_subject LIKE '%$ticket_search%' and delete_status = '1'";  
		    }
		    $detail_qry = $qry."  ORDER BY ticket_no DESC LIMIT $limit offset $offset";	
		} else {
			if($type=='spam'){
			 $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and is_spam = '1' and a.ticket_subject LIKE '%$ticket_search%' OR a.ticket_created_by = $user_id and a.ticket_subject LIKE '%$ticket_search%'";
		    }else{
		     $qry = "SELECT a.*, b.* FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE FIND_IN_SET('$user_id',a.ticket_assigned_to ) and delete_status = '1' and a.ticket_subject LIKE '%$ticket_search%' OR a.ticket_created_by = $user_id and a.ticket_subject LIKE '%$ticket_search%'";	
		    }
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
	
public function check_rounrobin_queue($data){
      extract($data);//print_r($data);exit;
	  $setting_ary = $this->fetchData("SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'",array());
	  $ticket_limit = $setting_ary['ticket_limit'];
	  $override = $setting_ary['override'];	  
	  if($user_id != ''){
		  $user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `admin_id`='$admin_id' AND `ticket_assigned_to`='$user_id' AND `ticket_status`=3 AND ticket_department='$dept_id' AND `unassign`=1 AND `delete_status`=0",array());
		  $queue_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `admin_id`='$admin_id' AND `ticket_status`=3 AND ticket_department='$dept_id' AND `unassign`=0 AND `delete_status`=0",array());
		  if($user_ticket_count==0){
            if($queue_ticket_count <= $ticket_limit){
                $assign_tic_limit = $queue_ticket_count;
            }else{
                $assign_tic_limit = $ticket_limit;
            }
            $get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($dept_id)",array());
			$dept_users = explode(',',$get_dep);
			$dept_users = array_unique($dept_users);
			$current_array_val = array_search($user_id, $dept_users);
			$next_array_val = $dept_users[$current_array_val+1];
			$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0];
			$ticno_array_qry = "SELECT ticket_no FROM external_tickets WHERE admin_id='$admin_id' AND ticket_department='$dept_id' AND ticket_status=3 AND unassign=0 AND delete_status=0 ORDER BY ticket_no ASC LIMIT $assign_tic_limit";
			//echo $ticno_array_qry;exit;   
		    $ticno_array_value = $this->dataFetchAll($ticno_array_qry, array());
		    for($i = 0; $i < count($ticno_array_value); $i++){
                $ticket_nos = $ticno_array_value[$i]['ticket_no'];                
				$update_data = $this->db_query("UPDATE external_tickets SET ticket_assigned_to='$user_id',next_assign_for='$next_array_val',unassign=1 WHERE ticket_no='$ticket_nos'", array());
		    }
		    $output = 1;    
			return  $output;
		  } // if condition user_ticket_count = 0
		  else{
			  if($user_ticket_count < $ticket_limit){
			  	$dept_users = explode(',',$get_dep);
				$dept_users = array_unique($dept_users);
				$current_array_val = array_search($user_id, $dept_users);
				$next_array_val = $dept_users[$current_array_val+1];
				$next_array_val = ($next_array_val) ? $next_array_val :  $dept_users[0];
			  	$assign_tic_limit = $ticket_limit - $user_ticket_count;
				$ticno_array_qry = "SELECT ticket_no FROM external_tickets WHERE ticket_department='$dept_id' AND ticket_status=3 AND unassign=0 AND delete_status=0 ORDER BY ticket_no ASC LIMIT $assign_tic_limit";
		        $ticno_array_value = $this->dataFetchAll($ticno_array_qry, array());
		        for($i = 0; $i < count($ticno_array_value); $i++){
                  $ticket_nos = $ticno_array_value[$i]['ticket_no'];
                  $update_data = $this->db_query("UPDATE external_tickets SET ticket_assigned_to='$user_id',next_assign_for='$next_array_val',unassign=1 WHERE ticket_no='$ticket_nos'", array());
		        }
		        $output = 1;    
			    return  $output;
			  }else{
			  	$output = 0;    
				return  $output;
			  }
		  } // else condition
      } // if coundtion iser_id = 0  
}
public function reassign_ticket_roundrobin($data)
{
    extract($data);//print_r($data);exit;
	$setting_ary = $this->fetchData("SELECT ticket_limit,override FROM admin_details WHERE admin_id='$admin_id'",array());
	$ticket_limit = $setting_ary['ticket_limit'];
	$override = $setting_ary['override'];
	$user_data = $this->fetchData("SELECT ticket_assigned_to,ticket_department FROM `external_tickets` WHERE `ticket_no`='$ticket_id' AND unassign=1 AND delete_status=0",array());	
	$dept = $user_data['ticket_department'];
	$userid = $user_data['ticket_assigned_to'];
    // code to update new dept/user
	if($changing_user_dept!=''){		
        $get_dep = $this->fetchOne("SELECT department_users FROM `departments` where dept_id IN ($changing_user_dept)",array());
		$dept_users = explode(',',$get_dep);
		$dept_users = array_unique($dept_users);
		//print_r($dept_users);exit;
		$assignNext = $this->fetchOne("SELECT next_assign_for FROM external_tickets WHERE ticket_department='$changing_user_dept' ORDER BY ticket_no DESC LIMIT 1",array());
		if($assignNext!=0){
			$assignTo = $assignNext;
		} else{
			$assignTo = $dept_users[0];
		}		
		$current_array_val = array_search($assignTo, $dept_users);
        $next_array_val = $dept_users[$current_array_val+1];		
		$user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$assignTo' AND `ticket_status`=3 AND ticket_department='$changing_user_dept' AND `unassign`=1 AND `delete_status`=0",array());		
		if($user_ticket_count < $ticket_limit){
             $update_data = "UPDATE external_tickets SET ticket_assigned_to='$assignTo',next_assign_for='$next_array_val',ticket_department='$changing_user_dept',unassign=1 WHERE ticket_no='$ticket_id'";
		}else
		{
			$cnt = $current_array_val+1;
            $dep_count = count($dept_users);
            for($i=$cnt;$i<=$dep_count;$i++){
        	  $j = $dept_users[$i];
              $user_ticket_count = $this->fetchOne("SELECT COUNT(`ticket_no`) FROM `external_tickets` WHERE `ticket_assigned_to`='$j' AND `ticket_department`='$changing_user_dept' AND `ticket_status`=3",array());
              if($user_ticket_count < $ticket_limit){
                $next_assign = $dept_users[$i+1];
                $update_data = "UPDATE external_tickets SET ticket_assigned_to='$j',next_assign_for='$next_assign',ticket_department='changing_user_dept',unassign=1 WHERE ticket_no='$ticket_id'";
                break;
	          }
            }// count 'for' loop
		}// else
		$results = $this->db_query($update_data,$parms);      
		$output = $results == 1 ? 1 : 0;    
		return  $output;		
	}// main if   
}	
function getmyInternalMail($data){
	extract($data);//print_r($data);exit;    
    $qry = "SELECT a.*, b.* FROM outlook_mail a JOIN outlook_mail_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_to='$agent_email' AND a.delete_status=0 OR a.ticket_from='$agent_email' AND a.delete_status=0";
	$detail_qry = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC LIMIT $limit offset $offset";
	//echo $detail_qry;exit;
	$detail_qry2 = $qry." Group by a.ticket_no ORDER BY a.updated_at DESC";
    $result = $this->dataFetchAll($detail_qry, array());	
    for($i = 0; $i < count($result); $i++){ 
        $ticket_no = $result[$i]['ticket_no'];
        $ticket_to = $result[$i]['ticket_to'];        
		$ticket_from = $result[$i]['ticket_from'];        
		$ticket_created_at = $result[$i]['created_dt'];
        $ticket_message = $result[$i]['ticket_message'];
        $ticket_subject = $result[$i]['ticket_subject'];        
        $time_ago = strtotime($ticket_created_at); 		
		$created_time = $this->time_Ago($time_ago);			
		$created_time = date("Y-m-d H:i:s", $time_ago);			
		$ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_to' => $ticket_to, 'subject'=> $ticket_subject,  'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time, 'ticket_from'=>$ticket_from, 'ticket_message'=>$ticket_message);
        $ticket_options_array[] = $ticket_options;
    }
	$status = array('status' => 'true');
    $ticket_options_array = array('ticket_options' => $ticket_options_array);	
	$total_count = $this->dataRowCount($detail_qry2,array());
	$total = array('total' => $total_count);
    $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
	$list_info_arr = array('list_info' => $list_info);    
    $merge_result = array_merge($status, $ticket_options_array,$total,$list_info_arr); 
    $tarray = json_encode($merge_result);
    print_r($tarray);exit;
}
	
public function viewInternalMail($data){
	extract($data);//print_r($data);exit;
	$userMail= $this->fetchOne("SELECT email_id FROM user where user_id='$user_id'",array());
	$ticket_id = base64_decode($ticket_id);
	$qry = "SELECT * FROM outlook_mail WHERE ticket_no = '$ticket_id'";
    $tic_details = $this->fetchData($qry,array());
	$admin_id = $tic_details['admin_id'];
	$ticket_user = $tic_details['ticket_from'];
	if($userMail != $ticket_user){
	 $explode_from = explode('<',$ticket_user);
	 $exp1 = explode('>',$explode_from[1]);
	 $rep_fm = str_replace(' ', '',$exp1[0]);
	 $from=$tic_details['ticket_to'];
	}else{
	 $from=$ticket_user;
	}
    $res_val= $this->fetchData($qry,array());
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
	$qry = "SELECT a.*, b.* FROM outlook_mail a JOIN outlook_mail_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
	$detail_qry=$qry."ORDER BY b.ticket_message_id DESC LIMIT $limit offset $offset";
	$result =  $this->dataFetchAll($detail_qry, array());
	for($i = 0; count($result) > $i; $i++){ 
        $ticket_no = $result[$i]['ticket_no'];
		$ticket_message_ids = $result[$i]['ticket_message_id'];
        $ticket_created_by = $result[$i]['ticket_from'];
		$ticket_from = $result[$i]['ticket_from'];        
		$ticket_created_at = $result[$i]['created_dt'];
        $ticket_message = $result[$i]['ticket_message'];
		$ticket_only_message = $result[$i]['only_message'];
		$ticket_only_signature = $result[$i]['only_signature'];	 
		$ticket_medias = $result[$i]['ticket_media'];
		$ticket_media = explode(',', $ticket_medias );
		$ticket_media = $ticket_media;
        $ticket_subject = $result[$i]['ticket_subject'];
		$replied_from_db = $result[$i]['replied_from'];
		$replied_by = $result[$i]['repliesd_by'];
		$ticket_to = $result[$i]['replied_to'];		
		$ticket_user = $result[$i]['user_id'];
		$ccMails = $result[$i]['replied_cc'];
		if($ccMails==''){
		    $ccMails = $this->fetchOne("SELECT replied_cc FROM outlook_mail_data WHERE ticket_id='$ticket_no' ORDER BY ticket_id ASC LIMIT 1",array());
		}	 
		$is_spam = $result[$i]['is_spam'];		
		$ticket_delete_status = $result[$i]['delete_status'];
		$ticket_profile_image = $result[$i]['profile_image'];	 
	    if($ticket_user!='') {
			$rep= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$ticket_user' ",array());
			$permission = $rep['profile_picture_permission'];			$rep_img=$rep['profile_image'];
			$rep_name=$rep['agent_name'];
		}else{
			$rep_img='';$rep_name='';
		}
	    $ccMails = str_replace("(","",$ccMails);
		$ccMails = str_replace(")","",$ccMails);
		$ccMails = str_replace('"',"",$ccMails);
		$pos = array_search( $from, $to);
		if(count($to) > 1){
		  $key = array_search($from, $to);
		  if($key != '' || $key === 0){
		 	unset($to[array_search( $from, $to )]);
		    array_push($to, $replied_from);
		  }
			$to = implode(',',$to);
		 	$ticket_to = $to; 
		}else{
			$to = str_replace("(","",$ticket_to);
			$to = str_replace(")","",$to);
			$to = str_replace('"',"",$to);
			$ticket_to = $to;
		}
		$ticket_message_id = $result[$i]['ticket_message_id'];         
		$ticket_forward_by =  $this->dataFetchAll("SELECT a.*,b.profile_image FROM external_ticket_forward a LEFT JOIN user b ON a.created_by=b.user_id WHERE ticket_reply_id='$ticket_message_id'",array());	 
		$ticket_assigned_dp="'" . str_replace(",", "','", $ticket_assigned_to) . "'";        	  
		$created_time = $this->get_timeago($ticket_created_at);
		$created_time = $ticket_created_at;		
        $ticket_options = array('ticket_no' => $ticket_no,'is_spam'=>$is_spam,'ticket_media'=>$ticket_media, 'ticket_created_by' => $ticket_from, 'subject'=> $ticket_subject, 'ticket_message'=>$ticket_message, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'ticket_from'=>$ticket_from,'ticket_to'=>$ticket_to,'replied_from'=>$replied_from_db,'ticket_message_id'=>$ticket_message_id,'replied_by'=>$replied_by, 'own_mail' =>$from, 'own_img' =>$own_img, 'rep_img' =>$rep_img, 'rep_name' =>$rep_name, 'user_name'=>$user_name,'mail_cc'=>$ccMails, 'first_letter_r' => strtoupper($replied_from[0]), 'ticket_delete_status' => $ticket_delete_status, 'ticket_profile_image'=>$ticket_profile_image, 'ticket_only_message'=>$ticket_only_message, 'ticket_only_signature'=>$ticket_only_signature, 'ticket_forward_by'=>$ticket_forward_by);
          $ticket_options_array[] = $ticket_options;
        }	
		$first_res_time = "SELECT created_at FROM `outlook_mail_data` WHERE repliesd_by = 'Agent' and ticket_id='$ticket_id' LIMIT 1";              
        $first_res_time = $this->fetchmydata($first_res_time,array());		
		$status = array('status' => 'true');		
		$ticket_options_array = array('tick_options' => $ticket_options_array);		
		$total_count = $this->dataRowCount($qry,array());
	    $total = array('total' => $total_count);
        $merge_result = array_merge($total, $status, $ticket_options_array);          
		$tarray = json_encode($merge_result);		
        print_r($tarray);exit;		
}

public function add_InternalMail($data){	
    extract($data);
    //file_put_contents('dat.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
    // changing "to" format code
	$rep1 = str_replace('[','',$to);
	$rep2 = str_replace(']','',$rep1);
	$rep3 = str_replace('"','',$rep2);	
	$rep4 = str_replace(',', '|', $rep3);
	$Toarr = explode('|',$rep4);
	// changing "from" format code
	$exp_from = explode('<',$from);
	$exp_from1 = explode('>',$exp_from[1]);
	$exp_from2 = str_replace(' ', '',$exp_from1[0]);
	// changing "cc" format code
	$cc = str_replace('[','(',$cc_mail);
	$cc = str_replace(']',')',$cc);
	// internal mail code	  
	$get_dep = $this->fetchOne("SELECT department_users FROM `departments` WHERE internal_mail=1",array()); 
	$dept_users = explode(',',$get_dep);
	$dept_users = array_unique($dept_users);
	//file_put_contents('dat.txt', print_r($dept_users,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
	$aid = $this->fetchOne("SELECT admin_id FROM `departments` WHERE internal_mail=1",array());
	$user_qry = "SELECT timezone_id FROM user WHERE user_id='$aid'";
	$user_qry_value = $this->fetchmydata($user_qry,array());
	$user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
	$user_timezone = $this->fetchmydata($user_timezone_qry,array());
	date_default_timezone_set($user_timezone);  
	$created_dt = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");
	$deptArr = array();	
	for($j = 0; $j < count($dept_users); $j++){
	  $agent_ids = $dept_users[$j];		  
	  $agent_email = $this->fetchOne("SELECT email_id FROM `user` WHERE user_id='$agent_ids'",array());
	  array_push($deptArr,$agent_email);
	}
	for($i = 0; $i < count($deptArr); $i++){
	  $agent_emails = $deptArr[$i];		  
	  if(in_array($agent_emails,$Toarr)){
	    $ticket_no=$this->db_insert("Insert INTO outlook_mail(ticket_from,ticket_to,ticket_subject,admin_id,created_dt,updated_at) VALUES ('$from','$agent_emails','$subject','$aid','$created_dt','$updated_at')", array());
		$qry_result = $this->db_insert("INSERT INTO outlook_mail_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,ticket_reply_id,created_dt,repliesd_by) VALUES ( '$ticket_no','$message','$subject','$exp_from2','$agent_emails','$cc','$attachments','$ticket_reply_id','$created_dt','Customer')", array());
	  }
	}	
	// internal mail code
}


public function composeInternalMail($data){
		extract($data);//print_r($data);exit;
		$tos = explode(',',$to);
		$mail_ccs = explode(",",$mail_cc);	    
		$from = $from_address;
	    $msg = $description;
		$description = base64_decode($description);
		$html = $description;
		$dom = new DOMDocument();
		// $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
		// $images = $dom->getElementsByTagName('img');

		// SK code test
		$images = $dom->getElementsByTagName('img');
		$dom->loadHTML($html);
		// END
		foreach ($images as $image) {        	
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
		//$ticketMedia = implode(",",$files_arr);	
		$files_arr = implode(",",$files_arr);
		$html = $dom->saveHTML();		
		$qry = "SELECT sig_content FROM email_signatures WHERE is_default='1' and admin_id='$admin_id'";
		$mailSignature = $this->fetchOne($qry,array());
		$qry = "SELECT ticket_no FROM outlook_mail ORDER BY ticket_no DESC LIMIT 1";
		$oldTickNo = $this->fetchOne($qry,array());
		$oldTickNo = $oldTickNo + 1;	
		//$subject = $subject.' [##'.$oldTickNo.']';
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
        $mail->SMTPSecure = 'tls';// enable SMTP authentication
        $mail->Host = $hostname; // sets the SMTP server
        $mail->Port = $port; // set the SMTP port for the GMAIL server
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
				$res = "Message has been sent successfully";			
				//$from ='("'.$from.'")';
				$user_qry_value=$this->fetchOne("SELECT timezone_id FROM user WHERE user_id='$admin_id'", array());		
     			$user_timezone=$this->fetchOne("SELECT name FROM timezone WHERE id='$user_qry_value'", array());
     			date_default_timezone_set($user_timezone);  
     			$created_dt = date("Y-m-d H:i:s");			    
				$ticket_no = $this->db_insert("INSERT INTO outlook_mail(ticket_from,ticket_to,ticket_subject,admin_id,created_dt,updated_at) VALUES ( '$from','$to','$subject','$admin_id','$created_dt','$created_dt')", array());			    
			    $description = $html;
			    $te = "INSERT INTO outlook_mail_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,created_dt,repliesd_by,only_message,only_signature) VALUES ('$ticket_no','$description','$subject','$from','$to','$mail_cc','$files_arr','$created_dt','Agent','$description','$mailSignature')";
			    file_put_contents('atc.txt', $te.PHP_EOL , FILE_APPEND | LOCK_EX);
				$qry_result = $this->db_insert("INSERT INTO outlook_mail_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,created_dt,repliesd_by,only_message,only_signature) VALUES ('$ticket_no','$description','$subject','$from','$to','$mail_cc','$files_arr','$created_dt','Agent','$description','$mailSignature')", array()); 
	    }	
        $status = array('status' => 'true');
		$response_array = array('data' => $res);	
        $merge_result = array_merge($status, $response_array);       
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
}
		
public function replyInternalMail($data){
		extract($data); 
		//print_r($data); exit;	
		$qry = "SELECT * FROM outlook_mail WHERE ticket_no = '$ticket_id'"
		;
		//echo $qry;exit;
       	$tic_details = $this->fetchData($qry,array());
		$subject = $tic_details['ticket_subject'];		
		$ticket_from = $tic_details['ticket_to'];
		$tic_from =  $tic_details['ticket_from'];		
		$main_tick_from = $tic_details['ticket_from'];		
		$from = $tic_from;
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
		$mail_ccs = explode(",",$mail_cc);		
		$only_msg = '<div  style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$message.'</div>';
		$qry = "SELECT sig_content FROM email_signatures WHERE sig_id='$signature_id'";
		$mailSignature = $this->fetchOne($qry,array());
		if($mailSignature){
			$message =  $message.$mailSignature;
		}
		$messages = $this->getTicketThread($ticket_id);
		foreach($messages as $m) {
         $mess[] = '<div  style="border: 1px solid #d1d1d1;font-family: verdana !important; border-radius: 8px; padding: 12px; margin-bottom: 25px;">'.$m.'</div>';
        }
		//print_r($messages); exit;
		$mess = implode('<br>',$mess);
		//print_r($messagetoSend); exit;
		$message =  '<div style="font-family: verdana !important;">'.$message.'</div>';
		$messagetoSend = $message.'<br> <br>'.$mess;
	    //echo $messagetoSend;exit;
		if( strpos($to, ',') !== false ) {
			$tos = explode(',',$to); 
		}else{
			preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $to, $to_mail_array);
			$to = $to_mail_array[0][0];
		}	
	    //echo $to;exit;
		$message_id = $this->fetchOne("SELECT ticket_reply_id FROM outlook_mail_data WHERE ticket_id = '$ticket_id' and ticket_reply_id !=''",array());	           
		$smtp_qry = "SELECT * FROM smtp_details WHERE status=1";
		$smtp_qry_value = $this->fetchData($smtp_qry,array());
		$hostname = $smtp_qry_value['hostname'];
		$port = $smtp_qry_value['port'];
		$username = $smtp_qry_value['username'];
		$password = $smtp_qry_value['password'];
        require_once('class.phpmailer.php'); 		 
        $body = $messagetoSend;   
        $mail = new PHPMailer();
        //$mail->IsSMTP();               
	                $mail->IsSMTP();
	                $mail->SMTPAuth = true; 
	                $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
	                $mail->Host = $hostname; // sets the SMTP server
	                $mail->Port = $port;                    // set the SMTP port for the GMAIL server
	                $mail->Username = $username; // omni_erp SMTP account username
	                $mail->Password = $password;         // MGMyd3p2end0YnNi SMTP account password   
	                $mail->SetFrom($ticket_from, 'cal4care');
	                $mail->AddReplyTo($ticket_from, 'cal4care');
		            $mail->addCustomHeader('In-Reply-To',  '<'.$message_id.'>');
					$mail->addCustomHeader('References', $message_id);
		            $mail->Subject = $subject;
	                $mail->MsgHTML($body);
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
	    
		if(!$mail->send()) {			
			print_r($mail->ErrorInfo);exit ;
			$res = "Mailer Error: " . $mail->ErrorInfo;
		} 
		else {
		    $user_qry = "SELECT timezone_id FROM user WHERE user_id='$user_id'";
            $user_qry_value = $this->fetchmydata($user_qry,array());
	        $user_timezone_qry = "SELECT name FROM timezone WHERE id='$user_qry_value'";
            $user_timezone = $this->fetchmydata($user_timezone_qry,array());		
            date_default_timezone_set($user_timezone);  
     	    $created_at = date("Y-m-d H:i:s");$updated_at = date("Y-m-d H:i:s");		          
		    $resultss = $this->db_query($qryss, $params);
			$message = str_replace("'","\n",$message);			
			$qry_result = $this->db_insert("INSERT INTO outlook_mail_data(ticket_id,ticket_message,ticket_subject,replied_from,replied_to,replied_cc,ticket_media,repliesd_by,created_dt,user_id,only_message,only_signature) VALUES ( '$ticket_id','$message','$subject','$ticket_from','$to','$mail_cc','$files_arr','Agent','$created_at','$user_id','$only_msg','$mailSignature')", array());			
		    $result = $qry_result == 1 ? 'mailed' : 'Error';
			$dt = date('Y-m-d H:i:s');		 
			$status = array('status' => 'true');     
		    $tarray = json_encode($status);           
            print_r($tarray);exit;
		    return $tarray;				
		}
		return $res;		 
}		
public function addPhoneBridge($data){
		extract($data);
		//print_r($data);exit;
		$qry = "SELECT * FROM phone_bridge WHERE ip_address = '$ip_address' AND admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("dept_id"=>$id));		
		if($result > 0){
			$result = 2;
			return $result;
		}else {		
			$ins=$this->db_insert("INSERT INTO phone_bridge(admin_id,ip_address,sip_url,sip_port) VALUES ( '$admin_id','$ip_address','$sip_url','$sip_port')", array());
            $result = 1;
            return $result;
		 }
}
public function editPhoneBridge($data){
		extract($data);
		//print_r($data);exit;
		$qry = "SELECT * FROM phone_bridge WHERE id = '$key_id' AND admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array("dept_id"=>$id));		
		return $result;
}
public function updatePhoneBridge($data){
		extract($data);
		//print_r($data);exit;
		$qry = "UPDATE `phone_bridge` SET  `admin_id` = '$admin_id', `ip_address` = '$ip_address', `sip_url` = '$sip_url', `sip_port` = '$sip_port' WHERE `id` = '$key_id'";
	    //echo $qry;exit;
        $result = $this->db_query($qry, $params);		
		return $result;
}
public function listPhoneBridge($data){
		extract($data);
		//print_r($data);exit;
		$qry = "SELECT * FROM phone_bridge WHERE admin_id = '$admin_id'";
		$detail_qry = $qry." ORDER BY id DESC LIMIT $limit offset $offset";
		$detail_qry2 = $qry." ORDER BY id DESC";
		$list_info = $this->dataFetchAll($detail_qry,array());
	    //print_r($list_info);exit;
		$total_count = $this->dataRowCount($detail_qry2,array());
	    $total = array('total' => $total_count);
        $total_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);	      
        $result['list_info'] = $list_info;
	    $result['total_info'] = $total_info;
	    return $result;		
}
public function deletePhoneBridge($key_id,$admin_id,$ip_address){
      $qry = "DELETE FROM phone_bridge WHERE id='$key_id' AND admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);
	  $qrys = "DELETE FROM phone_bridge_users WHERE ip_address='$ip_address' AND admin_id='$admin_id'";
      $parms = array();
      $resultss = $this->db_query($qrys,$parms);
      $output = $resultss == 1 ? 1 : 0;    
      return  $output;
}
public function ticket_contract_details($cust_id){
      $qry = "SELECT * FROM ticket_customer_contract WHERE customer_id ='$cust_id'";
      $result =  $this->fetchData($qry, array());
      extract($result);
      if($customer_id != ''){
      	return $result;      	
      }else{
		
      	$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
		    "operation": "agents",
		    "moduleType": "agents",
		    "api_type": "web",
		    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
		    "element_data": {
		        "action": "get_customercontract_details",
		        "customer_id":"'.$cust_id.'"
		    }
		  }',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);	
		$explode_customer_details = explode('||',$response);//print_r($explode_customer_details);exit;
        if(count(array_filter($explode_customer_details)) != 0){
          $ticket_customer_id = $explode_customer_details[0];
	      $contract_name = $explode_customer_details[1];
	      $contract_description = $explode_customer_details[2];
	      $classification_name = $explode_customer_details[3];
	      $color = $explode_customer_details[4];
	      $remark_desc = $explode_customer_details[5];
		  $from_dt = $explode_customer_details[6];
          $to_dt = $explode_customer_details[7];
		  $insertQry = $this->db_insert("INSERT INTO ticket_customer_contract(contract_name,contract_description,classification_name,color,remark_desc,from_dt,to_dt,customer_id) VALUES ( '$contract_name','$contract_description','$classification_name','$color','$remark_desc','$from_dt','$to_dt','$cust_id')", array());
		  $qrys = "SELECT * FROM ticket_customer_contract WHERE customer_id ='$cust_id'";
          $results =  $this->fetchData($qrys, array());
		  return $results;
        }else{
		  return $results;	
        }	    
    }	
}
public function getCustomerDetasils($admin_id){               
        $qry = "SELECT customer_id,customer_name FROM ticket_customer WHERE admin_id ='$admin_id'";
        $result =  $this->dataFetchAll($qry, array());//print_r($result);exit;
        return $result;
}
public function phone_bridge_users($data){
	extract($data);//print_r($data);exit;
	//file_put_contents('dat.txt', print_r($data,true).PHP_EOL , FILE_APPEND | LOCK_EX);
	$agent_json = base64_encode(json_encode($agents));
	$admin_id= $this->fetchOne("SELECT user_id FROM user WHERE admin_id='1' AND hardware_id='$hardware_id'", array());
    $qry = "SELECT * FROM phone_bridge_users WHERE ip_address = '$bridge_host' AND admin_id = '$admin_id'";
	//echo $qry;exit;
	$result = $this->fetchData($qry, array(""));
	$total_count = $this->dataRowCount($qry,array());//echo $total_count;exit;
	if($total_count > 0){echo 'if';exit;
		$updateqry = "UPDATE `phone_bridge_users` SET `ip_address` = '$bridge_host', `hardware_id` = '$hardware_id', `agents` = '$agent_json' WHERE `ip_address` = '$bridge_host' AND admin_id='$admin_id'";
		//echo $updateqry;exit;
        $result = $this->db_query($updateqry, $params);
        $result = 1;
        return $result;
	}else{
		//echo "INSERT INTO phone_bridge_users(ip_address,hardware_id,agents,admin_id) VALUES ( '$bridge_host','$hardware_id','$agent_json','$admin_id')";exit;
		$insertqry=$this->db_insert("INSERT INTO phone_bridge_users(ip_address,hardware_id,agents,admin_id) VALUES ( '$bridge_host','$hardware_id','$agent_json','$admin_id')", array());
        $result = 1;
        return $result;
	}
}
public function view_phone_bridge_users($data){
		extract($data);		
		$qry = "SELECT * FROM phone_bridge_users WHERE admin_id = '$admin_id' AND ip_address='$ip_address'";
	
		$result = $this->fetchData($qry,array());//print_r($result);exit;
		extract($result);
		$agt = base64_decode($agents);
	    $at = json_decode($agt,true);
	    //print_r($at);
        //exit; 		
	    $options = array("admin_id"=>$admin_id,"hardware_id"=>$hardware_id,"ip_address"=>$ip_address,"agents"=>$at);
	    $status = array('status' => 'true');			
        $merge_result = array_merge($status, $options);       
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
}
/*public function updateCustomer($data){
	extract($data);//print_r($data);exit;
	$customer_name= $this->fetchOne("SELECT customer_name FROM ticket_customer WHERE admin_id='$admin_id' and customer_id ='$customer_id'", array());	
	$qry = "UPDATE external_tickets SET customer_id='$customer_id',customer_name='$customer_name' WHERE ticket_no='$ticket_id' AND admin_id='$admin_id'";
	$update_data = $this->db_query($qry, $params);
	return $update_data;
}*/	
public function updateCustomer($data){
	extract($data);//print_r($data);exit;
	$params = array();
	$customer_name= $this->fetchOne("SELECT customer_name FROM ticket_customer WHERE admin_id='$admin_id' and customer_id ='$customer_id'", array());
	$customer_email= $this->fetchOne("SELECT customer_email FROM ticket_customer WHERE admin_id='$admin_id' and customer_id ='$customer_id'", array());
	$ticket_from= $this->fetchOne("SELECT ticket_from FROM external_tickets WHERE admin_id='$admin_id' AND ticket_no='$ticket_id'", array());
	$implode_newmail = $customer_email.','.$ticket_from;	
	$qry = "UPDATE external_tickets SET customer_id='$customer_id',customer_name='$customer_name' WHERE ticket_no='$ticket_id' AND admin_id='$admin_id'";
	$update_data = $this->db_query($qry, $params);
	$qry1 = "UPDATE ticket_customer SET customer_email='$implode_newmail' WHERE customer_id='$customer_id' AND admin_id='$admin_id'";
	$update_data1 = $this->db_query($qry1, $params);
	return $update_data;
}
public function searchInternalMail($data){
		extract($data);//print_r($data);exit;	
		if($user_type == 2){
			$qry = "SELECT * FROM external_tickets WHERE admin_id = $user_id and ticket_subject LIKE '%$ticket_search%' and is_spam = '$is_spam' or ticket_from LIKE '%$ticket_search%'";
			$detail_qry = $qry."  ORDER BY ticket_no DESC LIMIT $limit offset $offset";			
		}else
		{
		    $qry = "SELECT a.*, b.* FROM outlook_mail a JOIN outlook_mail_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_to='$agent_email' AND a.delete_status=0 AND a.ticket_subject LIKE '%$ticket_search%' OR a.ticket_from LIKE '%$ticket_search%' AND a.delete_status=0";
			$detail_qry = $qry." Group by a.ticket_no ORDER BY a.ticket_no DESC LIMIT $limit offset $offset";
		}
		$result = $this->dataFetchAll($detail_qry, array());
	    //print_r($result);exit;
        for($i = 0; count($result) > $i; $i++){ 
          $ticket_no = $result[$i]['ticket_no'];
          $ticket_to = $result[$i]['ticket_to'];        
		  $ticket_from = $result[$i]['ticket_from'];        
		  $ticket_created_at = $result[$i]['created_dt'];
          $ticket_message = $result[$i]['ticket_message'];
          $ticket_subject = $result[$i]['ticket_subject'];        
          $time_ago = strtotime($ticket_created_at); 		
		  $created_time = $this->time_Ago($time_ago);			
		  $created_time = date("Y-m-d H:i:s", $time_ago);
          $ticket_options = array('ticket_no' => $ticket_no, 'ticket_created_by' => $ticket_from, 'ticket_to' => $ticket_to, 'subject'=> $ticket_subject,  'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time, 'ticket_from'=>$ticket_from, 'ticket_message'=>$ticket_message);
          $ticket_options_array[] = $ticket_options;
        }
        $status = array('status' => 'true');
	    $ticket_options_array = array('ticket_options' => $ticket_options_array);	
		$total_count = $this->dataRowCount($detail_qry,array());
		$total = array('total' => $total_count);
	    $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
		$list_info_arr = array('list_info' => $list_info);    
	    $merge_result = array_merge($status, $ticket_options_array,$total,$list_info_arr); 
	    $tarray = json_encode($merge_result);
	    print_r($tarray);exit;
}
public function get_department_signature($admin_id){
	//$qry = "SELECT dept_id,department_name FROM departments WHERE admin_id ='$admin_id' AND delete_status=0 AND signature_id=0";
	$qry = "SELECT dept_id,department_name FROM departments WHERE admin_id ='$admin_id' AND delete_status=0";
	//echo $qry;exit;
	$res= $this->dataFetchAll($qry, array(""));	
	return $res;
}
public function update_signature_strategy($data){
        extract($data);//print_r($data);exit;     
        $qry = "UPDATE user SET signature_strategy='$value' WHERE admin_id='$user_id'";        
        $qry_result = $this->db_query($qry, array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
}
public function update_switch_signature($data){
        extract($data);     
        $qry = "UPDATE user SET switch_signature='$value' WHERE user_id='$user_id'";        
        $qry_result = $this->db_query($qry, array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
}
public function merge_ticket($data){
        extract($data);
        $main_customer_id = $this->fetchOne("SELECT customer_id FROM external_tickets WHERE ticket_no='$main_ticket_id'", array());
        $sub_customer_id = $this->fetchOne("SELECT customer_id FROM external_tickets WHERE ticket_no='$sub_ticket_id'", array());
        if($main_customer_id == $sub_customer_id){
        	$qry = "UPDATE external_tickets_data SET ticket_id='$main_ticket_id' WHERE ticket_id='$sub_ticket_id'";        
            $qry_result = $this->db_query($qry, array());
            $result = $qry_result == 1 ? 1 : 0;
			$update_data = $this->db_query("UPDATE external_tickets SET delete_status='1' WHERE ticket_no='$sub_ticket_id'", array());
			$get_enquiry_type = $this->fetchOne("SELECT type FROM external_tickets WHERE ticket_no='$sub_ticket_id'", array());
			if($get_enquiry_type=='enquiry'){
				$enquirydata= $this->fetchData("SELECT enquiry_company,enquiry_comments,enquiry_dropdown_id,revisit_date FROM external_tickets WHERE ticket_no='$sub_ticket_id'", array());
				$sub_enquiry_company = $enquirydata['enquiry_company'];
				$sub_enquiry_comments = $enquirydata['enquiry_comments'];
				$sub_enquiry_dropdown_id = $enquirydata['enquiry_dropdown_id'];
				$sub_revisit_date = $enquirydata['revisit_date'];
				$this->db_query("UPDATE external_tickets SET type='enquiry',enquiry_company='$sub_enquiry_company',enquiry_comments='$sub_enquiry_comments',enquiry_dropdown_id='$sub_enquiry_dropdown_id',revisit_date='$sub_revisit_date',enquiry_value='1' WHERE ticket_no='$main_ticket_id'", array());
			}
            return $result;        	
        }
        elseif($main_customer_id == 0 || $sub_customer_id == 0){
        	$qry = "UPDATE external_tickets_data SET ticket_id='$main_ticket_id' WHERE ticket_id='$sub_ticket_id'";        
            $qry_result = $this->db_query($qry, array());
            $result = $qry_result == 1 ? 1 : 0;
			$update_data = $this->db_query("UPDATE external_tickets SET delete_status='1' WHERE ticket_no='$sub_ticket_id'", array());
			$get_enquiry_type = $this->fetchOne("SELECT type FROM external_tickets WHERE ticket_no='$sub_ticket_id'", array());
			if($get_enquiry_type=='enquiry'){
				$enquirydata= $this->fetchData("SELECT enquiry_company,enquiry_comments,enquiry_dropdown_id,revisit_date FROM external_tickets WHERE ticket_no='$sub_ticket_id'", array());
				$sub_enquiry_company = $enquirydata['enquiry_company'];
				$sub_enquiry_comments = $enquirydata['enquiry_comments'];
				$sub_enquiry_dropdown_id = $enquirydata['enquiry_dropdown_id'];
				$sub_revisit_date = $enquirydata['revisit_date'];
				$this->db_query("UPDATE external_tickets SET type='enquiry',enquiry_company='$sub_enquiry_company',enquiry_comments='$sub_enquiry_comments',enquiry_dropdown_id='$sub_enquiry_dropdown_id',revisit_date='$sub_revisit_date',enquiry_value='1' WHERE ticket_no='$main_ticket_id'", array());
			}
            return $result;        	
        }
        else{
        	$result = 0;
            return $result;        	
        }    
}
public function geterpCustomerDetasils($admin_id){ 
	//echo $admin_id;exit;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "operation": "agents",
        "moduleType": "agents",
        "api_type": "web",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
        "element_data": {
            "action": "get_customer"            
        }
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    print_r($response);exit;
    //$query = "SELECT customer_id, customer_name, customer_code, customer_email, country, phone_number FROM ticket_customer WHERE admin_id='$admin_id' GROUP BY customer_id ORDER BY id DESC;";
    //$result = $this->dataFetchAll($query,array());
    //return $result;
}
public function editCustomer($data){ 
	extract($data);//print_r($data);exit;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "operation": "agents",
        "moduleType": "agents",
        "api_type": "web",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
        "element_data": {
            "action": "get_individual_customer",
            "customer_id":"'.$customer_id.'"            
        }
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    print_r($response);exit;
}
public function changeCustomer($data){ 
	extract($data);
	$params = array();
    $cdata= $this->fetchData("SELECT customer_id,customer_email FROM ticket_customer WHERE admin_id='$admin_id' and customer_id ='$customer_id'", $params);
    $cid = $cdata['customer_id'];
    $cemail = $cdata['customer_email'];
    if($cid==''){
    	$insertQry = $this->db_insert("INSERT INTO ticket_customer(admin_id,customer_id,customer_code,customer_name,customer_email,phone_number,country) VALUES ('$admin_id','$customer_id','$customer_code','$customer_name','$customer_email','$customer_phone','$customer_country')", $params);
    }else{
    	//$implode_newmail = $cemail.','.$from_mail;
    	$updateqry_ticket_customer = "UPDATE ticket_customer SET customer_email='$customer_email' WHERE customer_id='$customer_id' AND admin_id='$admin_id'";
	    $update_data1 = $this->db_query($updateqry_ticket_customer, $params);    	
    }    
    $updateqry_ticket = "UPDATE external_tickets SET customer_id='$customer_id',customer_name='$customer_name' WHERE admin_id='$admin_id' AND ticket_from='$from_mail'";
    $update_data2 = $this->db_query($updateqry_ticket, $params);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "operation": "agents",
        "moduleType": "agents",
        "api_type": "web",
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
        "element_data": {
            "action": "update_individual_customer",
            "customer_id":"'.$customer_id.'",
            "customer_email":"'.$customer_email.'"            
        }
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    print_r($response);exit;
}
public function update_thread_strategy($data){
        extract($data);//print_r($data);exit;     
        $qry = "UPDATE user SET thread_order='$value' WHERE user_id='$user_id'";        
        $qry_result = $this->db_query($qry, array());
        $result = $qry_result == 1 ? 1 : 0;
        return $result;
}	
public function change_thread_order($data){
		extract($data);
	    //$update_data = $this->db_query("UPDATE user SET thread_order='$value' WHERE admin_id='$admin_id' AND user_id='$uid'", array());
		$ticket_id = base64_decode($ticket_id);		
		$qry = "SELECT * FROM external_tickets WHERE ticket_no = '$ticket_id'";
       	$tic_details = $this->fetchData($qry,array());//echo $qry;exit;		
		$ticket_user = $tic_details['ticket_from'];
		if($ticket_user!='user'){
		 $explode_from = explode('<',$ticket_user);
		 $exp1 = explode('>',$explode_from[1]);
		 $rep_fm = $exp1[0];
		}else{		   
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
		$qry = "SELECT a.*, b.*,a.ticket_subject as subj FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
		$detail_qry=$qry."ORDER BY b.ticket_message_id DESC LIMIT $limit offset $offset";	
        $result =  $this->dataFetchAll($detail_qry, array());		
		for($i = 0; count($result) > $i; $i++){ 
		  $ticket_customer_id = $result[$i]['customer_id'];
		  $ticket_customer_name = $result[$i]['customer_name'];	 
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
		  $ticket_only_message = $result[$i]['only_message'];
		  $ticket_only_signature = $result[$i]['only_signature'];	 
		  $ticket_medias = $result[$i]['ticket_media'];
		  $ticket_media = explode(',', $ticket_medias );
		  $ticket_media = $ticket_media;
          $ticket_subject = $result[$i]['subj'];
		  $replied_from_db = $result[$i]['replied_from'];
		  $replied_by = $result[$i]['repliesd_by'];
		  $ticket_to = $result[$i]['replied_to'];
		  //$ticket_to = $result[$i]['ticket_to'];	 
		  $ticket_user = $result[$i]['user_id'];
		  $ccMails = $result[$i]['replied_cc'];
		  $all_replied_to = $result[$i]['all_replied_to'];
		  $all_replied_cc = ltrim($result[$i]['all_replied_cc']);
		  if($ccMails==''){
		    $ccMails = $this->fetchOne("SELECT replied_cc FROM external_tickets_data WHERE ticket_id='$ticket_no' ORDER BY ticket_id ASC LIMIT 1",array());
		  }	 
		  $is_spam = $result[$i]['is_spam'];
		  $closedby = $result[$i]['ticket_closed_by']; 
		  $ticket_delete_status = $result[$i]['delete_status'];
		  $ticket_profile_image = $result[$i]['profile_image'];
		  $ticket_enquiry_dropdown_id = $result[$i]['enquiry_dropdown_id'];
		  $ticket_revisit_date = $result[$i]['revisit_date'];
		  $ticket_type = $result[$i]['type'];
		  $ticket_enquiry_value = $result[$i]['enquiry_value'];
		  $enquiry_outcome_comments = $result[$i]['enquiry_outcome_comments'];	 
		  if($ticket_user!='') {				
				$rep= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$ticket_user' ",array());
			    $permission = $rep['profile_picture_permission'];			    
				$rep_img=$rep['profile_image'];
				$rep_name=$rep['agent_name'];
		  }else{
				$rep_img='';$rep_name='';
		  }			 
		  $ccMails = str_replace("(","",$ccMails);
		  $ccMails = str_replace(")","",$ccMails);
		  $ccMails = str_replace('"',"",$ccMails);
		  $pos = array_search( $from, $to);
		  if(count($to) > 1){
		    $key = array_search($from, $to);
			if($key != '' || $key === 0){
			 	unset($to[array_search( $from, $to )]);
			    array_push($to, $replied_from);
			}
			$to = implode(',',$to);
			$ticket_to = $to; 
		  }
		  else {
		    $to = str_replace("(","",$ticket_to);
		    $to = str_replace(")","",$to);
			$to = str_replace('"',"",$to);
			$ticket_to = $to;
		  }		
		  $ticket_message_id = $result[$i]['ticket_message_id']; 
          $createdby_qry = "SELECT agent_name FROM user WHERE user_id='$ticket_created_by'";              
          $createdby = $this->fetchmydata($createdby_qry,array()); 
		  $ticket_notes_by =  $this->dataFetchAll("SELECT a.*,b.profile_image FROM external_ticket_notes a LEFT JOIN user b ON a.created_by=b.user_id WHERE ticket_reply_id='$ticket_message_id'",array());
		  $ticket_forward_by =  $this->dataFetchAll("SELECT a.*,b.profile_image FROM external_ticket_forward a LEFT JOIN user b ON a.created_by=b.user_id WHERE ticket_reply_id='$ticket_message_id'",array());	 
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
		  //$ticket_only_message = base64_decode($ticket_only_message);      		
          $ticket_options = array('ticket_no' => $ticket_no,'is_spam'=>$is_spam,'ticket_media'=>$ticket_media, 'ticket_created_by' => $ticket_from, 'ticket_assigned_to' => $ticket_assigned_to,'ticket_assigned_to_id'=>$ticket_assigned_to_id,'department' => $department,'depart_id'=>$ticket_department, 'subject'=> $ticket_subject, 'ticket_status' => $ticketstatus,'ticket_status_id'=>$ticket_status,'ticket_message'=>$ticket_message,'ticket_signature'=>$ticket_signature,'ticket_notes'=>$ticket_notes_by,'priority' => $priority_value, 'first_letter' => strtoupper($ticket_from[0]), 'ticket_created_at' => $created_time,'ticket_from'=>$ticket_from,'ticket_to'=>$ticket_to,'replied_from'=>$replied_from_db,'ticket_message_id'=>$ticket_message_id,'replied_by'=>$replied_by, 'own_mail' =>$from, 'own_img' =>$own_img, 'rep_img' =>$rep_img, 'rep_name' =>$rep_name, 'user_name'=>$user_name,'mail_cc'=>$ccMails, 'first_letter_r' => strtoupper($replied_from[0]),'ticket_closed_by'=>$tic_closed_by,'ticket_delete_status' => $ticket_delete_status,'ticket_profile_image'=>$ticket_profile_image,'ticket_only_message'=>$ticket_only_message,'ticket_only_signature'=>$ticket_only_signature,'ticket_forward_by'=>$ticket_forward_by,'customer_id'=>$ticket_customer_id,'customer_name'=>$ticket_customer_name,'all_replied_to'=>$all_replied_to,'all_replied_cc'=>$all_replied_cc,'ticket_type'=>$ticket_type,'ticket_enquiry_dropdown_id'=>$ticket_enquiry_dropdown_id,'ticket_revisit_date'=>$ticket_revisit_date,'ticket_enquiry_value'=>$ticket_enquiry_value,'enquiry_outcome_comments'=>$enquiry_outcome_comments);
          $ticket_options_array[] = $ticket_options;
        }	
		$first_res_time_qry = "SELECT created_dt FROM `external_tickets_data` WHERE repliesd_by = 'Agent' and ticket_id='$ticket_id' LIMIT 1";              
        $first_res_time = $this->fetchmydata($first_res_time_qry,array());
		$closed_at = $this->fetchmydata("SELECT closed_at FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		$ticket_closed_by = $this->fetchmydata("SELECT ticket_closed_by FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		if($ticket_closed_by != '0'){
			$ticket_closed_byqry = "SELECT agent_name FROM `user` WHERE `user_id` = '$ticket_closed_by'";
        	$tic_closed_by = $this->fetchOne($ticket_closed_byqry,array());
		}
		$ticket_created_date = $this->fetchmydata("SELECT created_dt FROM `external_tickets` WHERE ticket_no='$ticket_id'",array());
		$department_array_qry = "SELECT dept_id as department_id,department_name FROM departments where admin_id='$admin_id' and delete_status='0' and has_email='1'";
		$department_options_array = $this->dataFetchAll($department_array_qry, array());		
		$status_array_qry = "SELECT status_id,status_desc FROM status";
		$status_options_array = $this->dataFetchAll($status_array_qry, array());	
		$priority_array_qry = "SELECT id,priority FROM priority";
		$priority_options_array = $this->dataFetchAll($priority_array_qry, array());		
		$agents_array_qry = "SELECT user_id,agent_name FROM user where admin_id=$admin_id";
		$agents_options_array = $this->dataFetchAll($agents_array_qry, array());
		$to_cc_qry = "SELECT TRIM(BOTH ',' FROM all_replied_to) as all_replied_to,TRIM(BOTH ',' FROM all_replied_cc) as all_replied_cc FROM external_tickets_data WHERE ticket_id = '$ticket_id' ORDER BY ticket_message_id DESC LIMIT 1";
       	$to_cc = $this->fetchData($to_cc_qry,array());		
		$status = array('status' => 'true');
		$status_options_array = array('status_options' => $status_options_array);
		$department_options_array = array('departments' => $department_options_array);
		$priority_options_array = array('priority' => $priority_options_array);
		$agents_options_array = array('agents' => $agents_options_array);
		$ticket_options_array = array('tick_options' => $ticket_options_array);
		$ticket_tocc_array = array('ticket_tocc_options' => $to_cc);
		$side_menu = array('ticket_created_date' => $ticket_created_date,'first_res_time' => $first_res_time, "closed_at"=>$closed_at, "ticket_closed_by"=>$tic_closed_by);
		$total_count = $this->dataRowCount($qry,array());
	    $total = array('total' => $total_count);
// first thread code
$qry1 = "SELECT a.*, b.*,a.ticket_subject as subj FROM external_tickets a JOIN external_tickets_data b ON a.ticket_no = b.ticket_id WHERE a.ticket_no ='$ticket_id'";
$detail_qry1=$qry1."ORDER BY b.ticket_message_id ASC LIMIT 1";	
$results =  $this->fetchData($detail_qry1, array());		
$first_ticket_customer_id = $results['customer_id'];
$first_icket_customer_name = $results['customer_name'];	 
$first_ticket_no = $results['ticket_no'];
$first_ticket_message_ids = $results['ticket_message_id'];
$first_ticket_created_by = $results['ticket_from'];
$first_ticket_from = $results['ticket_from'];
$first_ticket_assigned_to = $results['ticket_assigned_to'];
$first_ticket_department = $results['ticket_department'];
$first_ticket_status = $results['ticket_status'];
$first_priority = $results['priority'];
$first_ticket_notes =  $results['ticket_notes'];
$first_ticket_notes_by =  $results['ticket_notes_by'];
$first_ticket_created_at = $results['created_dt'];
$first_ticket_message = $results['ticket_message'];
$first_ticket_only_message = $results['only_message'];
$first_ticket_only_signature = $results['only_signature'];	 
$first_ticket_medias = $results['ticket_media'];
$first_ticket_media = explode(',', $first_ticket_medias );
$first_ticket_media = $first_ticket_media;
$first_ticket_subject = $results['subj'];
$first_replied_from_db = $results['replied_from'];
$first_replied_by = $results['repliesd_by'];
$first_ticket_to = $results['replied_to'];
//$first_ticket_to = $results['ticket_to'];	 
$first_ticket_user = $results['user_id'];
$first_ccMails = $results['replied_cc'];
if($first_ccMails==''){
    $first_ccMails = $this->fetchOne("SELECT replied_cc FROM external_tickets_data WHERE ticket_id='$first_ticket_no' ORDER BY ticket_id ASC LIMIT 1",array());
}	 
$first_is_spam = $results['is_spam'];
$first_closedby = $results['ticket_closed_by']; 
$first_ticket_delete_status = $results['delete_status'];
$first_ticket_profile_image = $results['profile_image'];	 
if($first_ticket_user!=''){				
	$first_rep= $this->fetchData("SELECT profile_image,user_name,agent_name,profile_picture_permission FROM user where user_id='$first_ticket_user' ",array());
    $first_permission = $first_rep['profile_picture_permission'];			    
	$first_rep_img=$first_rep['profile_image'];
	$first_rep_name=$first_rep['agent_name'];
}else{
	$first_rep_img='';$first_rep_name='';
}			 
$first_ccMails = str_replace("(","",$first_ccMails);
$first_ccMails = str_replace(")","",$first_ccMails);
$first_ccMails = str_replace('"',"",$first_ccMails);
$first_pos = array_search( $from, $to);
if(count($to) > 1){
	$keys = array_search($from, $to);
	if($keys != '' || $keys === 0){
	 	unset($to[array_search( $from, $to )]);
	    array_push($to, $first_replied_from_db);
	}
	$to = implode(',',$to);
	$first_ticket_to = $to; 
}
else {
    $to = str_replace("(","",$first_ticket_to);
    $to = str_replace(")","",$to);
	$to = str_replace('"',"",$to);
	$first_ticket_to = $to;
}		
$first_ticket_message_id = $results['ticket_message_id']; 
$first_createdby_qry = "SELECT agent_name FROM user WHERE user_id='$first_ticket_created_by'";              
$first_createdby = $this->fetchmydata($first_createdby_qry,array()); 
	 
$first_ticket_assigned_dp="'" . str_replace(",", "','", $first_ticket_assigned_to) . "'";			 
$first_assignedto_qry = "SELECT GROUP_CONCAT(agent_name) FROM user WHERE user_id IN ($first_ticket_assigned_dp)";    
$first_assignedto = $this->fetchmydata($first_assignedto_qry,array());
$first_deptment_qry = "SELECT department_name FROM departments WHERE dept_id='$first_ticket_department'";       
$first_department = $this->fetchmydata($first_deptment_qry,array());
$first_status_qry = "SELECT status_desc FROM status WHERE status_id='$first_ticket_status'";              
$first_ticketstatus = $this->fetchmydata($first_status_qry,array());
$first_priority_qry = "SELECT priority FROM priority WHERE id='$first_priority'";              
$first_priority_value = $this->fetchmydata($first_priority_qry,array());		  
$first_created_time = $this->get_timeago($first_ticket_created_at);
$first_created_time = $first_ticket_created_at;
$first_ticket_assigned_to = explode(',',$first_ticket_assigned_to); 
if(count($first_ticket_assigned_to) == 1){
    $first_ticket_assigned_t = $first_ticket_assigned_to[0];
	$first_ticket_assigned = "SELECT agent_name,user_id FROM user WHERE user_id='$first_ticket_assigned_t'";       
    $first_ticket_assigned = $this->fetchData($first_ticket_assigned,array());
	$first_ticket_assigned_to=$first_ticket_assigned['agent_name'];
	$first_ticket_assigned_to_id=$first_ticket_assigned['user_id'];	 
} else {
    $first_ticket_assigned_to = '';
    $first_ticket_assigned_to_id = '';
}
//$first_ticket_only_message = base64_decode($first_ticket_only_message);      		
$first_ticket_options = array('ticket_no' => $first_ticket_no,'is_spam'=>$first_is_spam,'ticket_media'=>$first_ticket_media, 'ticket_created_by' => $first_ticket_from, 'ticket_assigned_to' => $first_ticket_assigned_to,'ticket_assigned_to_id'=>$first_ticket_assigned_to_id,'department' => $first_department,'depart_id'=>$first_ticket_department, 'subject'=> $first_ticket_subject, 'ticket_status' => $first_ticketstatus,'ticket_status_id'=>$first_ticket_status,'ticket_message'=>$first_ticket_message,'ticket_signature'=>$first_ticket_signature,'priority' => $first_priority_value, 'first_letter' => strtoupper($first_ticket_from[0]), 'ticket_created_at' => $first_created_time,'ticket_from'=>$first_ticket_from,'ticket_to'=>$first_ticket_to,'replied_from'=>$first_replied_from_db,'ticket_message_id'=>$first_ticket_message_id,'replied_by'=>$first_replied_by, 'own_mail' =>$from, 'own_img' =>$own_img, 'rep_img' =>$first_rep_img, 'rep_name' =>$first_rep_name, 'user_name'=>$user_name,'mail_cc'=>$first_ccMails, 'first_letter_r' => strtoupper($first_replied_from[0]),'ticket_closed_by'=>$first_tic_closed_by,'ticket_delete_status' => $first_ticket_delete_status,'ticket_profile_image'=>$first_ticket_profile_image,'ticket_only_message'=>$first_ticket_only_message,'ticket_only_signature'=>$first_ticket_only_signature,'ticket_forward_by'=>$first_ticket_forward_by,'customer_id'=>$first_ticket_customer_id,'customer_name'=>$first_ticket_customer_name);
$first_ticket_options_array[] = $first_ticket_options;
// first thread code
	    $first_ticket_options_array = array('first_tick_options' => $first_ticket_options_array);
        $merge_result = array_merge($first_ticket_options_array, $total, $status, $status_options_array, $ticket_options_array, $side_menu, $priority_options_array, $department_options_array, $agents_options_array,$ticket_tocc_array);     
		//$merge_result = array_merge($total, $status, $status_options_array, $ticket_options_array, $side_menu, $priority_options_array, $department_options_array, $agents_options_array);     
	    $tarray = json_encode($merge_result);         
		//print_r($ticket_options_array);exit;
        print_r($tarray);exit;
		//return $tarray;		
}
function add_department_keyword($data){
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
	  $qry = "select * from department_words_filtering where filter_word LIKE '%$keyword%' and admin_id = '$admin_id'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id,"dept_id"=>$dept_id));   
	  if($result > 0){
		$result = 2;
		return $result;
	  }else{
		$qry = "select * from department_words_filtering where type_id = '$type_id'";
	    $result = $this->fetchData($qry, array("type_id"=>$type_id));
	    if($result > 0){
	      $implode_comma = $result['filter_word'].','.$filter_word;
	      $key_id = $result['id'];	
		  $qry = "UPDATE department_words_filtering SET filter_word='$implode_comma' where id='$key_id'";
          $parms = array();
          $result = $this->db_query($qry,$parms);
          $result == 1 ? 1 : 0;
          return $result;
	    }else{
		  	if($type=='department'){
		  		$name= $this->fetchOne("Select department_name from departments where dept_id='$type_id'", array());
		  	}else{
		  		$name= $this->fetchOne("Select agent_name from user where user_id='$type_id'", array());
		  	}
			$qry = "INSERT INTO department_words_filtering(type,type_id,name,filter_word,user_id,admin_id) VALUES ('$type','$type_id','$name','$keyword','$user_id','$admin_id')";
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
}
public function list_department_keyword($data){
	  extract($data);
	  $qry = "select user_type,admin_id from user where user_id='$user_id'";
	  $res = $this->fetchsingledata($qry, array());	
	  if($res['user_type'] == '2')
	  { 
		$admin_id = $user_id;
	  }else{
		$admin_id = $res['admin_id']; 
	  }
	  $qry = "select * from department_words_filtering where admin_id ='$admin_id'";
	  return $this->dataFetchAll($qry, array("admin_id"=>$admin_id));
}
public function edit_department_keyword($key_id){	
	  $qry = "select * from department_words_filtering where id ='$key_id'";
	  return $this->dataFetchAll($qry, array());
}
function update_department_keyword($data){	  
      extract($data);//print_r($data);exit;			  
	  $qry = "select * from department_words_filtering where filter_word LIKE '%$filter_word%'";
	  $result = $this->fetchData($qry, array("admin_id"=>$admin_id));   
	  if($result > 0){
		$output = 2;
		return $output;
	  }else{
		$qry = "UPDATE department_words_filtering SET type='$type',type_id='$type_id',filter_word='$filter_word' where id='$key_id'";
        $parms = array();
        $results = $this->db_query($qry,$parms);
        $output = $results == 1 ? 1 : 0;    
        return  $output;
	  }
}
public function delete_department_keyword($key_id){     
     $qry = "Delete FROM department_words_filtering WHERE id='$key_id'";	 
     $qry_result = $this->db_query($qry, array());
     if($qry_result == 1){
      $result = 1;
     }else{
      $result = 0;
    }
    return $result;
}
public function internalmail_blockEmailIds($data){       
    extract($data); 
	$qry = "select * from internalmail_spam_mail_ids where email LIKE '%$email_id%'";
    $result =  $this->fetchData($qry, array());		
	if($result > 0){
		 $id = $result['id'];
	 	 $qry = "UPDATE internalmail_spam_mail_ids SET spam_status='$spam_status',blacklist_status='$blacklist_status',user_id='$user_id' where id='$id'";
		 $update_data = $this->db_query($qry, $params);
	} else {
	 	$qry = "INSERT INTO internalmail_spam_mail_ids(email,spam_status,blacklist_status,admin_id,user_id) VALUES ( '$email_id','$spam_status','$blacklist_status','$admin_id','$user_id')";  
        $insert_data = $this->db_query($qry, $params);
	}
    $nstatus = 0;		
    if($spam_status == 0){
	 $nstatus = 1;
    }
    $qry = "UPDATE outlook_mail SET is_spam='$spam_status',status_del='$nstatus' where ticket_from LIKE '%$email_id%'";    	
	$update_data = $this->db_query($qry, $params);
    $qry = "select * from internalmail_spam_mail_ids where admin_id='$admin_id' and user_id='$user_id'";
    $result =  $this->dataFetchAll($qry, array());
    return $result;
}
function internalmail_delSpamEmail($email){
    $qry = "DELETE FROM internalmail_spam_mail_ids where email LIKE '%$email%'";
    $result =  $this->db_query($qry, array());
    return $result;
}
function internalmail_spamLists($data){
	extract($data);
    $qry = "select * from internalmail_spam_mail_ids where admin_id='$admin_id' and user_id='$user_id'";
    $result =  $this->dataFetchAll($qry, array());
    return $result;
}
public function share_external_ticket($data){
	  extract($data);//print_r($data);exit;
	  $get_shared_id = $this->fetchOne("SELECT shared_id FROM external_tickets WHERE ticket_no='$ticket_id'", array());
	  if($get_shared_id==''){
       $qry = "UPDATE external_tickets SET shared_id='$agent_id' WHERE ticket_no='$ticket_id'";		
       $parms = array();
       $results = $this->db_query($qry,$parms);      
       $output = $results == 1 ? 1 : 0;
	  }else{
	   $shared_id = $get_shared_id.','.$agent_id;
	   $qry = "UPDATE external_tickets SET shared_id='$shared_id' WHERE ticket_no='$ticket_id'";
       $parms = array();
       $results = $this->db_query($qry,$parms);      
       $output = $results == 1 ? 1 : 0;       	  
      }
      $explode = explode(',',$agent_id);
      foreach($explode as $user){
       $agent_name = $this->fetchOne("SELECT agent_name FROM user WHERE user_id='$user'", array());
       $subject = 'Ticket [#'.$ticket_id.'] has been shared to - '.$agent_name;				
	   $us = array("user_id"=>$user,"ticket_for"=>"Share Ticket","ticket_subject"=>$subject, "ticket_id"=>$ticket_id);  
	   $this->send_notification($us);
	  }
	  return  $output;
}
public function shared_agent_list($data){
	  extract($data);//print_r($data);exit;
	  $get_shared_id = $this->fetchOne("SELECT shared_id FROM external_tickets WHERE ticket_no='$ticket_id'", array());
	  if($get_shared_id==''){             
       $status = array('status' => 'false');
       $result = array_merge($status);
	   $tarray = json_encode($result);
	   print_r($tarray);exit;
	  }else{	   
       $explode = explode(',',$get_shared_id);
	   foreach($explode as $user){
	    $agent_name = $this->fetchOne("SELECT agent_name FROM user WHERE user_id='$user'", array());
	    $user_details = array("user_id"=>$user,"agent_name"=>$agent_name);
	    $user_array[] = $user_details;		
	   }
	   $status = array('status' => 'true');
	   $user_array = array('options' => $user_array);
	   $result = array_merge($status, $user_array);
	   $tarray = json_encode($result);
	   print_r($tarray);exit;
	  }
}
public function update_spamstatus_settings($data){
	extract($data);
	$qry = "UPDATE department_emails SET `spam_status`='$value' WHERE `aliseEmail` = '$aliseEmail'";	
	$update_data = $this->db_query($qry, array());
	if($update_data != 0){
	 $result = 1;
	}
	else{
	 $result = 0;
	}
	return $result;
}
public function add_domain_whitelist($data){
    extract($data);
    //print_r($data);exit;
    $qry = "SELECT * FROM domain_whitelist WHERE domain = '$domain' AND admin_id = '$admin_id'";
    $result = $this->fetchData($qry, array());
    if($result > 0){
      $result = 2;
      return $result;
    }else {
	  $domain_concat = "@".$domain;	
      $datas=array("domain"=>$domain_concat,"admin_id"=>$admin_id,"status"=>"1");
      $qry = $this->generateCreateQry($datas, "domain_whitelist");
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
public function list_domain_whitelist ($data){
  extract($data);
  $query = "SELECT * FROM `domain_whitelist` WHERE status=1 AND admin_id='$admin_id' ORDER BY id DESC";
  $result = $this->dataFetchAll($query, array());
  return $result;
}
public function edit_domain_whitelist ($data){
  extract($data);
  $query = "SELECT * FROM `domain_whitelist` WHERE id='$id'";
  $result = $this->fetchData($query, array());
  return $result;
}
public function update_domain_whitelist($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE domain_whitelist SET domain='$domain' WHERE id='$id' AND admin_id='$admin_id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}
public function delete_domain_whitelist($data){
  extract($data);
  $qry = "DELETE FROM domain_whitelist WHERE id='$id'";
  $parms = array();
  $results = $this->db_query($qry,$parms);      
  $output = $results == 1 ? 1 : 0;    
  return  $output;
}
public function add_private_forwardmail($data){
    extract($data);
    //print_r($data);exit;
    $qry = "SELECT * FROM private_forward_list WHERE email = '$email' AND admin_id = '$admin_id'";
    $result = $this->fetchData($qry, array());
    if($result > 0){
      $result = 2;
      return $result;
    }else {
      $datas=array("email"=>$email,"admin_id"=>$admin_id,"status"=>"1");
	  //print_r($datas);exit;	
      $qry = $this->generateCreateQry($datas, "private_forward_list");
      $insert_data = $this->db_insert($qry, $datas); 
	  //echo $insert_data;exit;	
      if($insert_data != 0){
      	$datas1=array("email"=>$email,"admin_id"=>$admin_id,"status"=>"1");	
        $qry1 = $this->generateCreateQry($datas1, "customer_whitelist");
        $insert_data1 = $this->db_insert($qry1, $datas);
        $result = 1;              
      }
      else{                
        $result = 0;
      }            
      return $result;
    }
}
public function list_private_forwardmail ($data){
  extract($data);
  $query = "SELECT * FROM `private_forward_list` WHERE status=1 AND admin_id='$admin_id' ORDER BY id DESC";
  $result = $this->dataFetchAll($query, array());	
  return $result;
}
public function edit_private_forwardmail ($data){
  extract($data);
  $query = "SELECT * FROM `private_forward_list` WHERE id='$id'";
  $result = $this->fetchData($query, array());
  return $result;
}
public function update_private_forwardmail($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE private_forward_list SET email='$email' WHERE id='$id' AND admin_id='$admin_id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}
public function delete_private_forwardmail($data){
  extract($data);
  $qry = "DELETE FROM private_forward_list WHERE id='$id'";
  $parms = array();
  $results = $this->db_query($qry,$parms);      
  $output = $results == 1 ? 1 : 0;    
  return  $output;
}
public function add_subject_filter($data){
    extract($data);
    //print_r($data);exit;
    $qry = "SELECT * FROM subject_filter WHERE subject = '$subject' AND admin_id = '$admin_id'";
    $result = $this->fetchData($qry, array());
    if($result > 0){
      $result = 2;
      return $result;
    }else {	  
      $datas=array("subject"=>$subject,"admin_id"=>$admin_id);
      $qry = $this->generateCreateQry($datas, "subject_filter");
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
public function list_subject_filter ($data){
  extract($data);
  $query = "SELECT * FROM `subject_filter` WHERE admin_id='$admin_id' ORDER BY id DESC";
  $result = $this->dataFetchAll($query, array());
  return $result;
}
public function edit_subject_filter ($data){
  extract($data);
  $query = "SELECT * FROM `subject_filter` WHERE id='$id'";
  $result = $this->fetchData($query, array());
  return $result;
}
public function update_subject_filter($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE subject_filter SET subject='$subject' WHERE id='$id' AND admin_id='$admin_id'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}
public function delete_subject_filter($data){
  extract($data);
  $qry = "DELETE FROM subject_filter WHERE id='$id'";
  $parms = array();
  $results = $this->db_query($qry,$parms);      
  $output = $results == 1 ? 1 : 0;    
  return  $output;
}
public function list_customer_whitelist ($data){
  extract($data);
  $total_query = "SELECT * FROM `customer_whitelist` WHERE admin_id='$admin_id' ORDER BY id DESC";
  $query = "SELECT * FROM `customer_whitelist` WHERE admin_id='$admin_id' ORDER BY id DESC LIMIT $limit OFFSET $offset";
  $result = $this->dataFetchAll($query, array());
  $total_count = $this->dataRowCount($total_query,array());	
  $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
  $merge_result = array('status' => 'true','data'=>$result,'list_info' => $list_info);
  //$status = array('status' => 'true');	
  $tarray = json_encode($merge_result);
  print_r($tarray);exit;	
  return $merge_result;
}
public function add_customer_whitelist($data){
    extract($data);
    //print_r($data);exit;
    $qry = "SELECT * FROM customer_whitelist WHERE email = '$email' AND admin_id = '$admin_id'";
    $result = $this->fetchData($qry, array());
    if($result > 0){
      $result = 2;
      return $result;
    }else {
      $datas=array("email"=>$email,"admin_id"=>$admin_id,"status"=>"1");
	  //print_r($datas);exit;	
      $qry = $this->generateCreateQry($datas, "customer_whitelist");
      $insert_data = $this->db_insert($qry, $datas); 
	  //echo $insert_data;exit;	
      if($insert_data != 0){
        $result = 1;              
      }
      else{                
        $result = 0;
      }            
      return $result;
    }
}
public function edit_customer_whitelist ($data){
  extract($data);
  $query = "SELECT * FROM `customer_whitelist` WHERE id='$id'";
  $result = $this->fetchData($query, array());
  return $result;
}
public function update_customer_whitelist($data){
  extract($data);//print_r($data);exit;	
  $select_qry = "SELECT * FROM customer_whitelist WHERE email = '$email' AND admin_id = '$admin_id'";
  $result = $this->fetchData($select_qry, array());
  if($result > 0){
    $result = 2;
    return $result;
  }else{
    $qry = "UPDATE customer_whitelist SET email='$email' WHERE id='$id' AND admin_id='$admin_id'";
    $qry_result = $this->db_query($qry, array());
    $result = $qry_result == 1 ? 1 : 0;
    return $result;
  }           
}
public function delete_customer_whitelist($data){
  extract($data);
  $qry = "DELETE FROM customer_whitelist WHERE id='$id'";
  $parms = array();
  $results = $this->db_query($qry,$parms);      
  $output = $results == 1 ? 1 : 0;    
  return  $output;
}
public function get_hasemail_department($admin_id){
	$dep_query = "SELECT dept_id,department_name FROM `departments` WHERE admin_id='$admin_id' AND has_email=1";
	$dep_row = $this->dataFetchAll($dep_query,array());
	return $dep_row;
  }
	  
  public function email_queue_report($data){
	  extract($data);    
	  $qry="SELECT * FROM email_queue_report WHERE admin_id='$admin_id' AND spam_status=0";
	  if($search_text!= ""){
		  $qry.= " and ticket_no = '$search_text'";       
	  }
	  if($from_dt!=''){
		  $qry.=" and  date(created_time)>='$from_dt'";
	  }if($to_dt!=''){
		  $qry.=" and  date(created_time)<='$to_dt'";
	  }if($dept_id!=''){        
		  $qry.=" and dept_id = '$dept_id'";
	  }if($agent_id!=''){
		  $qry.=" and agent_id = '$agent_id'";
	  }
	  $detail_qry = $qry." ORDER BY id DESC LIMIT ".$limit." OFFSET ".$offset  ;
	  //echo $detail_qry;exit;
	  $parms = array();
	  $result["list_data"] = $this->dataFetchAll($detail_qry,array());
	  $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
	  $result["list_info"]["offset"] = $offset;
	  return $result;     
  }	
  public function email_queue_report_export($data){
	  extract($data);//print_r($data);
	  $qry="SELECT ticket_no,created_time,dept_name,agent_name,assigned_time,reassign_hit as queue_reassigned_hit,reassign_hit_agent as agent_reassigned_hit,closed_time as agent_close_time,first_response_time FROM email_queue_report WHERE admin_id='$admin_id' AND spam_status=0";
	  if($from_dt!=''){
		  $qry.=" and  date(created_time)>='$from_dt'";
	  }if($to_dt!=''){
		  $qry.=" and  date(created_time)<='$to_dt'";
	  }if($dept_id!=''){        
		  $qry.=" and dept_id = '$dept_id'";
	  }if($agent_id!=''){
		  $qry.=" and agent_id = '$agent_id'";
	  }
	  $detail_qry = $qry." ORDER BY id DESC";
	  //echo $qry;exit;
	  $parms = array();
	  $result = $this->dataFetchAll($detail_qry,array());
	  return $result;
  }
  public function add_custom_customer($data){
    extract($data);
    //print_r($data);exit;
    $qry = "SELECT * FROM ticket_customer WHERE customer_name = '$customer_name' AND admin_id = '$admin_id' AND customer_email IN ('$customer_email')";
	//echo $qry;exit;
    $result = $this->fetchData($qry, array());
    if($result > 0){
      $result = 2;
      return $result;
    }else {
      $datas=array("customer_name"=>$customer_name,"admin_id"=>$admin_id,"customer_email"=>$customer_email,"phone_number"=>$phone_number,"country"=>$country);
	  //print_r($datas);exit;	
      //$qry = $this->generateCreateQry($datas, "ticket_customer");
      //$insert_data = $this->db_insert($qry, $datas); 
	  //echo $insert_data;exit;
	  $no = $this->db_insert("INSERT INTO ticket_customer(customer_name,admin_id,customer_email,phone_number,country) VALUES ( '$customer_name','$admin_id','$customer_email','$phone_number','$country')", array());	
      if($no != 0){
		$customer_id = $no.'_'.$admin_id;
		$customer_code = "D".$customer_id;
        $qry = "UPDATE `ticket_customer` SET  `customer_id` = '$customer_id',`customer_code` = '$customer_code' WHERE `id` = '$no'";
        $resultss = $this->db_query($qry, $params);
        $result = 1;              
      }
      else{                
        $result = 0;
      }            
      return $result;
    }
}
public function customer_search_function($data){
    extract($data);//print_r($data);exit;
	$emailArr = array();
    $qry = "SELECT customer_email FROM `ticket_customer` WHERE customer_email LIKE '%$search_text%'";        
    $qry_result = $this->dataFetchAll($qry,array());
    $total_count = $this->dataRowCount($qry,array());
	//echo $total_count;exit;
	if($total_count!=0){
		for($i = 0; $total_count > $i; $i++){
		  $ticket_customer_email = $qry_result[$i]['customer_email'];
		  $explode = explode(',', $ticket_customer_email);
		  //print_r($explode);
		  foreach($explode as $key => $value){	  
		   array_push($emailArr,$value);	  
		  }
		  //print_r($emailArr);exit;
		}	
		$status = array('status' => 'true');	
		$email_result = array('email' => array_values(array_unique($emailArr)));
		$merge_result = array_merge($status, $email_result); 		
		$tarray = json_encode($merge_result);
		print_r($tarray);
		exit;
	}else{
		$curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
         "operation": "agents",
         "moduleType": "agents",
         "api_type": "web",
         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
         "element_data": {
            "action": "get_customeremail",
            "search_text": "'.$search_text.'"
         }
        }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
        ),
       ));
       $response = curl_exec($curl);
       curl_close($curl);
       print_r($response);exit;
   }
}	

public function change_from_function($data){
    extract($data);//print_r($data);exit;
	$emailArr = array();
	$get_domain = explode('@',$cusmail);
	$customer_domain = $get_domain[1];
	$customer_id = $this->fetchOne("SELECT customer_id FROM `ticket_customer` WHERE customer_email LIKE '%$customer_domain%'",array());
	//echo $customer_id;exit;
	if($customer_id == ''){
		$curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://erp.cal4care.com/erp/apps/index.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
         "operation": "agents",
         "moduleType": "agents",
         "api_type": "web",
         "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc",
         "element_data": {
            "action": "get_customerdetails",
            "customer_domain":"'.$customer_domain.'"
         }
        }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
        ),
       ));
       $response = curl_exec($curl);
       curl_close($curl);
       $explode_customer_details = explode('||',$response);
       $ticket_customer_id = $explode_customer_details[0];
       $ticket_customer_name = $explode_customer_details[1];
       $ticket_customer_code = $explode_customer_details[2];
       $ticket_customer_email = $explode_customer_details[3];
	   if($ticket_customer_id == ''){
		   $result = 0;
           return $result; 
	   }else{		   	
	       $qry = "UPDATE `external_tickets` SET  `customer_id` = '$ticket_customer_id',`customer_name` = '$ticket_customer_name',`ticket_from` = '$cusmail' WHERE `ticket_no` = '$ticket_no'";
           $update_data = $this->db_query($qry, $params);
	       $result = $update_data == 1 ? 1 : 0;
           return $result;
	   }
   }else{
	   $customer_name = $this->fetchOne("SELECT customer_name FROM `ticket_customer` WHERE customer_id = '$customer_id'",array());	
	   $qry = "UPDATE `external_tickets` SET `customer_id` = '$customer_id',`customer_name` = '$customer_name',`ticket_from` = '$cusmail' WHERE `ticket_no` = '$ticket_no'";
       $update_data = $this->db_query($qry, $params);
	   $result = $update_data == 1 ? 1 : 0;
       return $result;	
   }
}
public function list_enquiry_tickets ($data){
  extract($data);
  $agtArr=array();
  if($search_text!= ''){
        $search_qry= " and (e.enquiry_company like '%".$search_text."%')";
  }
  $qry = "SELECT e.ticket_no,e.enquiry_company,e.created_dt,e.enquiry_country,e.enquiry_comments,e.revisit_date,e.enquiry_dropdown_id,e.ticket_assigned_to,e.unassign,e.enquiry_outcome_comments,s.status_desc,ed.name as enquiry_status,d.department_name FROM external_tickets as e LEFT JOIN status as s ON s.status_id = e.ticket_status LEFT JOIN enquiry_dropdown as ed ON ed.id = e.enquiry_dropdown_id LEFT JOIN departments as d ON d.dept_id = e.ticket_department WHERE e.admin_id='$admin_id' AND e.delete_status=0 AND e.type='enquiry'".$search_qry;
  $detail_qry = $qry." ORDER BY e.ticket_no DESC LIMIT ".$limit." Offset ".$offset;
 // echo $detail_qry;exit;
  $result = $this->dataFetchAll($detail_qry, array());
  for($i = 0; count($result) > $i; $i++){
		  $enquiry_company = $result[$i]['enquiry_company'];	 
          $ticket_no = $result[$i]['ticket_no'];
		  $created_dt = $result[$i]['created_dt'];
          $enquiry_country = $result[$i]['enquiry_country'];
		  $enquiry_comments = $result[$i]['enquiry_comments'];
          $revisit_date = $result[$i]['revisit_date'];
          $enquiry_dropdown_id = $result[$i]['enquiry_dropdown_id'];
          $unassign = $result[$i]['unassign'];
	      $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
	      $enq_outcome_comments = $result[$i]['enquiry_outcome_comments'];
          $status_qry = $this->fetchone("SELECT COUNT(ticket_message_id) FROM `external_tickets_data` WHERE repliesd_by='Agent' AND ticket_id='$ticket_no'", array());
	      if($status_qry > 0){
             $status_desc = $result[$i]['status_desc'];
		  }else{
			 $status_desc = 'New'; 
		  }
		  file_put_contents('sts.txt', $status_desc.PHP_EOL , FILE_APPEND | LOCK_EX);
          $enquiry_status = $result[$i]['enquiry_status'];
          $department_name = $result[$i]['department_name'];
	      $days_qry = $this->fetchone("SELECT datediff(date(ed.created_dt), date(e.created_dt)) FROM external_tickets e LEFT JOIN external_tickets_data ed ON ed.ticket_id = e.ticket_no WHERE e.ticket_no='$ticket_no' ORDER BY ed.ticket_message_id DESC LIMIT 1",array());
	      if($days_qry > 1){
		    $days_val = $days_qry." Days";
          }else{
			$days_val = $days_qry." Day";  
		  }
		  if($unassign==1){
            $assigned_agent = $this->fetchOne("SELECT agent_name FROM user WHERE user_id = '$ticket_assigned_to'", array());
		  }
		  $agent_id_qry = "SELECT u.agent_name FROM `external_tickets_data` as e LEFT JOIN user as u ON u.user_id = e.user_id WHERE ticket_id='$ticket_no' AND repliesd_by='Agent'";
          $agent_id_val = $this->dataFetchAll($agent_id_qry, array());
		  for($k = 0; count($agent_id_val) > $k; $k++){
			 $name = $agent_id_val[$k]['agent_name'];
			 if (!in_array($name, $agtArr)){
			  array_push($agtArr,$name);
			 }
		  }
		  if (!in_array($assigned_agent, $agtArr)){
			  $agtArr[] = $assigned_agent;
		  }
		  $commaList = implode(',', $agtArr);
	      $ticket_options = array('ticket_no' => $ticket_no,'enquiry_company'=>$enquiry_company,'created_dt'=>$created_dt, 'enquiry_country' => $enquiry_country, 'enquiry_comments' => $enquiry_comments,'revisit_date'=>$revisit_date,'status_desc' => $status_desc,'enquiry_status'=>$enquiry_status, 'department_name'=> $department_name, 'last_update' => $days_val, 'agents' => $commaList, 'enquiry_dropdown_id' => $enquiry_dropdown_id, 'enquiry_outcome_comments'=>$enq_outcome_comments);
          $ticket_options_array[] = $ticket_options;
          $agtArr = array();
  }	
  $total_count = $this->dataRowCount($qry,array());	
  $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
  $status = array('status' => 'true');	
  $ticket_options_array = array('data' => $ticket_options_array);	
  //$merge_result = array('status' => 'true','data'=>$result,'list_info' => $list_info);
  $merge_result = array_merge($status, $ticket_options_array, $list_info);	
  $tarray = json_encode($merge_result);
  print_r($tarray);exit;
}	
public function list_enquiry_dropdown (){
  $qry = "SELECT * FROM `enquiry_dropdown`";
  $result = $this->dataFetchAll($qry, array());	
  $merge_result = array('status' => 'true','data'=>$result);	
  $tarray = json_encode($merge_result);
  print_r($tarray);exit;
}
public function enquiry_ticket_filter($data){
	  extract($data); //print_r($data);exit; 
	  $agtArr=array();  
	  $qry="SELECT e.ticket_no,e.enquiry_company,e.created_dt,e.enquiry_country,e.enquiry_comments,e.revisit_date,e.enquiry_dropdown_id,e.ticket_assigned_to,e.unassign,e.enquiry_outcome_comments,s.status_desc,ed.name as enquiry_status,d.department_name FROM external_tickets as e LEFT JOIN status as s ON s.status_id = e.ticket_status LEFT JOIN enquiry_dropdown as ed ON ed.id = e.enquiry_dropdown_id LEFT JOIN departments as d ON d.dept_id = e.ticket_department WHERE e.admin_id='$admin_id' AND e.type='enquiry'";
	  if($from_dt!=''){
		  $qry.=" AND  date(e.created_dt)>='$from_dt'";
	  }if($to_dt!=''){
		  $qry.=" AND  date(e.created_dt)<='$to_dt'";
	  }if($dept_id!=''){        
		  $qry.=" AND e.ticket_department = '$dept_id'";
	  }if($enquiry_dropdown_id!=''){
		  $qry.=" AND e.enquiry_dropdown_id = '$enquiry_dropdown_id'";
	  }
	  $detail_qry = $qry." ORDER BY e.ticket_no DESC LIMIT ".$limit." OFFSET ".$offset  ;
	  //echo $detail_qry;exit;
	  $result = $this->dataFetchAll($detail_qry, array());
	  for($i = 0; count($result) > $i; $i++){
		  $enquiry_company = $result[$i]['enquiry_company'];	 
          $ticket_no = $result[$i]['ticket_no'];
		  $created_dt = $result[$i]['created_dt'];
          $enquiry_country = $result[$i]['enquiry_country'];
		  $enquiry_comments = $result[$i]['enquiry_comments'];
          $revisit_date = $result[$i]['revisit_date'];
          $enquiry_dropdown_id = $result[$i]['enquiry_dropdown_id'];
          $unassign = $result[$i]['unassign'];
	      $ticket_assigned_to = $result[$i]['ticket_assigned_to'];
	      $enq_outcome_comments = $result[$i]['enquiry_outcome_comments'];
          $status_qry = $this->fetchone("SELECT COUNT(ticket_message_id) FROM `external_tickets_data` WHERE repliesd_by='Agent' AND ticket_id='$ticket_no'", array());
	      if($status_qry > 0){
             $status_desc = $result[$i]['status_desc'];
		  }else{
			 $status_desc = 'New'; 
		  }
          $enquiry_status = $result[$i]['enquiry_status'];
          $department_name = $result[$i]['department_name'];
	      $days_qry = $this->fetchone("SELECT datediff(date(ed.created_dt), date(e.created_dt)) FROM external_tickets e LEFT JOIN external_tickets_data ed ON ed.ticket_id = e.ticket_no WHERE e.ticket_no='$ticket_no' ORDER BY ed.ticket_message_id DESC LIMIT 1",array());
	      if($days_qry > 1){
		    $days_val = $days_qry." Days";
          }else{
			$days_val = $days_qry." Day";  
		  }
		  if($unassign==1){
            $assigned_agent = $this->fetchOne("SELECT agent_name FROM user WHERE user_id = '$ticket_assigned_to'", array());
		  }
		  $agent_id_qry = "SELECT u.agent_name FROM `external_tickets_data` as e LEFT JOIN user as u ON u.user_id = e.user_id WHERE ticket_id='$ticket_no' AND repliesd_by='Agent'";
          $agent_id_val = $this->dataFetchAll($agent_id_qry, array());
		  for($k = 0; count($agent_id_val) > $k; $k++){
			 $name = $agent_id_val[$k]['agent_name'];
			 if (!in_array($name, $agtArr)){
			  array_push($agtArr,$name);
			 }
		  }
		  if (!in_array($assigned_agent, $agtArr)){
			  $agtArr[] = $assigned_agent;
		  }
		  $commaList = implode(',', $agtArr);
	      $ticket_options = array('ticket_no' => $ticket_no,'enquiry_company'=>$enquiry_company,'created_dt'=>$created_dt, 'enquiry_country' => $enquiry_country, 'enquiry_comments' => $enquiry_comments,'revisit_date'=>$revisit_date,'status_desc' => $status_desc,'enquiry_status'=>$enquiry_status, 'department_name'=> $department_name, 'last_update' => $days_val, 'agents' => $commaList, 'enquiry_dropdown_id' => $enquiry_dropdown_id, 'enquiry_outcome_comments' => $enq_outcome_comments);
          $ticket_options_array[] = $ticket_options;
          $agtArr = array();
      }
      $total_count = $this->dataRowCount($qry,array());	
      $list_info = array('total' => $total_count, 'limit' => $limit, 'offset' => $offset);
      $status = array('status' => 'true');	
      $ticket_options_array = array('data' => $ticket_options_array);
      $merge_result = array_merge($status, $ticket_options_array, $list_info);
      $tarray = json_encode($merge_result);
      print_r($tarray);exit;
}
public function update_ticket_type($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE external_tickets SET type='$type',enquiry_value='1' WHERE admin_id='$admin_id' AND ticket_no='$ticket_no'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}
public function update_enquiry_details($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE external_tickets SET enquiry_company='$enquiry_company',enquiry_country='$enquiry_country',enquiry_comments='$enquiry_comments',enquiry_dropdown_id='$enquiry_dropdown_id',revisit_date='$revisit_date' WHERE admin_id='$admin_id' AND ticket_no='$ticket_no'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}
public function update_enquiry_comments($data){
  extract($data);//print_r($data);exit;	
  $qry = "UPDATE external_tickets SET enquiry_outcome_comments='$enquiry_outcome_comments' WHERE admin_id='$admin_id' AND ticket_no='$ticket_no'";
  $qry_result = $this->db_query($qry, array());
  $result = $qry_result == 1 ? 1 : 0;
  return $result;           
}

}
?>