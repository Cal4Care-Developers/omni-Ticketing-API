<?php
class service3cx extends restApi{
	
function agentUpdate($data){
		extract($data);
		$user_pwd = md5($user_pwd);
		$qry = "update user set user_name='$user_name',user_pwd='$user_pwd', sip_username='$sip_username', sip_password='$sip_password' where user_id='$user_id'";
		$qry_result = $this->db_query($qry, array());
		
		return $qry_result;
	
	}


		function queueUpdate($data){
		extract($data);
		$qry = "update queue set queue_number='$queue_number',queue_name='$queue_name', queue_status='1' where queue_id='$queue_id'";
		$qry_result = $this->db_query($qry, array());
		
		return $qry_result;
	
	}
	

	
	
	
}