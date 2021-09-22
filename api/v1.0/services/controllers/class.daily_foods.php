<?php
	class daily_foods extends restApi{
		function insert_3cx_calls($data){
			extract($data);
			
			$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id= $this->fetchmydata($sel_hd,array());
			
			$qry = "INSERT INTO daily_foods_calls(call_id, call_con_id, call_frm_num, q_num,agnt_num,status,time_of_status,call_start_time,call_ans_time,call_end_time,call_waiting_time,call_talk_time,admin_id,call_history_id)VALUES ('$callid', '$call_con_id', '$call_frm_num', '$q_num','$agnt_num','$status','$time_of_status','$call_start_time','$call_ans_time','$call_end_time','$call_waiting_time','$call_talk_time','$admin_id','$call_history_id')";
			$result= $this->db_insert($qry, array());
                return $result;
		}
		function update_3cx_calls($data){
			extract($data);
				
			$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			 $admin_id= $this->fetchmydata($sel_hd,array());
			
			 $qry = "UPDATE daily_foods_calls SET call_start_time='$call_start_time',call_ans_time='$call_ans_time',call_end_time='$call_end_time',call_waiting_time='$call_waiting_time',call_talk_time='$call_talk_time' where call_id='$call_id' and call_con_id='$call_con_id' and time_of_status='$call_start_time' and admin_id='$admin_id' ";
			$result= $this->db_insert($qry, array());
                return $result;
		}
		function insert_user_status($data){
			//print_r($data);exit;
			extract($data);
			
			$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id= $this->fetchmydata($sel_hd,array());
			
			 $qry = "INSERT INTO daily_foods_user_status(dn_num, ag_num, status,time_of_update, q_num, time_of_endstatus, admin_id )VALUES ('$dn_num', '$ag_num', '$status', '$time_of_update', '$q_num', '$time_of_endstatus', '$admin_id')";
			$result= $this->db_insert($qry, array());
                return $result;
						
		
		}
		function insert_ag_q_details($data){			
			extract($data);	
			
			 $sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			 $admin_id= $this->fetchmydata($sel_hd,array());
			
			$check_already_exists="SELECT id FROM daily_foods_ag_q_details where q_num='$q_num' and ag_num='$ag_num' and admin_id='$admin_id'";
				 $exists_id= $this->fetchmydata($check_already_exists,array());
			if($exists_id){
				$result= '0';
			}else{			
			 $qry = "INSERT INTO daily_foods_ag_q_details(dn_num,q_num,q_name, ag_num, ag_firstname,ag_lastname,admin_id)VALUES ('$dn_num','$q_num','$q_name', '$ag_num', '$ag_firstname', '$ag_lastname','$admin_id')";
			$result= $this->db_insert($qry, array());            
			}
			    return $result;
		}
		function update_user_status($data){
			extract($data);
			$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			 $admin_id= $this->fetchmydata($sel_hd,array());
			
			 $time_update=$this->fetchOne("SELECT time_of_update FROM daily_foods_user_status WHERE dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status='$status' and admin_id='$admin_id' order by time_of_update desc limit 1",array());
			
			
$ts1 = strtotime($time_update);
$ts2 = strtotime($time_of_endstatus);     
 $interval = $ts2 - $ts1;     
//$interval= gmdate("H:i:s", $seconds_diff);
			
			 // $qry = "UPDATE daily_foods_user_status SET time_of_endstatus='$time_of_endstatus' where dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status='$status' and time_of_endstatus='' and time_of_update= (SELECT * FROM (SELECT Max(time_of_update)  FROM daily_foods_user_status WHERE dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status='$status' and admin_id='$admin_id') as t) and admin_id='$admin_id'";
			//echo $time_update;exit;
			
			 $qry = "UPDATE daily_foods_user_status SET interv='$interval',time_of_endstatus='$time_of_endstatus' where dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status='$status' and time_of_endstatus='' and time_of_update= '$time_update' and admin_id='$admin_id'";
			//echo $qry;exit;
			
			$result= $this->db_insert($qry, array());
                return $result;
		}
		function update_user_status_nt_in($data){
			//print_r($data);exit;
			extract($data);
			$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			 $admin_id= $this->fetchmydata($sel_hd,array());
			
			 $status="'" . str_replace(",", "','", $status) . "'";
					
			 $time_update=$this->fetchOne("SELECT Max(time_of_update)  FROM daily_foods_user_status WHERE dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status NOT IN($status) and admin_id='$admin_id'",array());
			
			
$ts1 = strtotime($time_update);
$ts2 = strtotime($time_of_endstatus);     
 $interval = $ts2 - $ts1;     
//$interval= gmdate("H:i:s", $seconds_diff);
			 //$qry = "UPDATE daily_foods_user_status SET time_of_endstatus='$time_of_endstatus' where dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status NOT IN($status) and time_of_endstatus='' and time_of_update= (SELECT * FROM (SELECT Max(time_of_update)  FROM daily_foods_user_status WHERE dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status NOT IN($status) and admin_id='$admin_id') as t) and admin_id='$admin_id'";
			
			 $qry = "UPDATE daily_foods_user_status SET interv='$interval',time_of_endstatus='$time_of_endstatus' where dn_num='$dn_num' and q_num='$q_num' and ag_num='$ag_num' and status NOT IN($status) and time_of_endstatus='' and time_of_update= '$time_update' and admin_id='$admin_id'";
			//echo $qry;exit;
			$result= $this->db_insert($qry, array());
                return $result;
		}
		
	}