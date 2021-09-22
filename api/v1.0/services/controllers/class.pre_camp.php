<?php
class pre_camp extends restApi{
	function get_camp_on_off($data){
		extract($data);
		if($admin_id=='564'){
			$qry = "SELECT camp_status FROM `campaign` where id='$camp_id' and admin_id='$admin_id'";
		}else{
		$qry = "SELECT camp_status FROM `campaign` where camp_id='$camp_id' and admin_id='$admin_id'";
		}
	$result = $this->fetchOne($qry,array());
		if (is_numeric($result))
			$number = $result + 0;
		else 
			$number = 0;
		echo $number;exit;
	}
function get_phone_no($data){
	extract($data);
	if($admin_id=='564'){
	$qry = "SELECT pdc.phone_number,cg.redial FROM predective_dialer_contact pdc INNER JOIN campaign cg on cg.camp_id=pdc.campaign_id where cg.id='$camp_id' and pdc.admin_id='$admin_id' and pdc.queue_status='$queue_status' and cg.call_repeat>=pdc.queue_status and pdc.dnd='0'  and pdc.delete_status!='1' and CURRENT_TIMESTAMP>=pdc.sent_time  ORDER by pdc.queue_status asc,pdc.id asc limit 1";
	}else{
	$qry = "SELECT pdc.phone_number,cg.redial FROM predective_dialer_contact pdc INNER JOIN campaign cg on cg.camp_id=pdc.campaign_id where pdc.campaign_id='$camp_id' and pdc.admin_id='$admin_id' and pdc.queue_status='$queue_status' and cg.call_repeat>=pdc.queue_status and pdc.dnd='0'  and pdc.delete_status!='1' and CURRENT_TIMESTAMP>=pdc.sent_time  ORDER by pdc.queue_status asc,pdc.id asc limit 1";
	}
	//echo $qry;exit;
	$res = $this->fetchData($qry,array());
	$result=$res['phone_number'];
	$redial=$res['redial'];
	if($res){	
		 $qry = "update predective_dialer_contact set sent_time=TIMESTAMPADD(SECOND,$redial,NOW()) where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$result' and delete_status='0'"; 
	$qry_result = $this->db_query($qry, array());
	}
	if (is_numeric($result))
		$number = $result + 0;
	else
		$number = 0;
	echo $result;exit;
}
function update_queue_stat($data){
	extract($data);
	if($admin_id=='564'){
		$camp_id = $this->fetchOne("SELECT camp_id FROM `campaign` where id='$camp_id'",array());	
	}
	$qry = "update predective_dialer_contact set queue_status='$queue_status' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no'";
	
	$qry_result = $this->db_query($qry, array());
	$result = $qry_result == 1 ? 1 : 0;
	print_r($result);exit;
}
function get_call_repeat($data){
	extract($data);
	if($admin_id=='564'){
	$qry = "SELECT call_repeat FROM `campaign` where id='$camp_id' and admin_id='$admin_id'";
	}else{
	$qry = "SELECT call_repeat FROM `campaign` where camp_id='$camp_id' and admin_id='$admin_id'";
	}
	  $result = $this->fetchOne($qry,array());
	if (is_numeric($result))
		$number = $result + 0;
	else
		$number = 0;
	echo $number;exit;
}
function insert_ag_survey($data){
	//print_r($data);exit;
	extract($data);
	$time_zn="SELECT timezone.name FROM user INNER JOIN timezone on timezone.id=user.timezone_id where user.user_id='$admin_id'";
	 $timezone_id = $this->fetchOne($time_zn, array());
				//echo $timezone_id;exit;
if( $timezone_id == ""){
	$date = new DateTime("now");
	 $dt_tim=$date->format('Y-m-d H:i:s');
	 //echo $dt_tim;exit;
}
else
{
	$date = new DateTime("now", new DateTimeZone($timezone_id) );
	$dt_tim=$date->format('Y-m-d H:i:s');
}
	
 	 

	  $get_id="SELECT id FROM survey where ani='$ani' and admin_id='$admin_id' and posted_key='' and date(login_dt)=date('$dt_tim')";

	$id = $this->fetchOne($get_id, array());
	
	if($id!=''){
	 $qry = "UPDATE survey set posted_key='$posted_key' where ani='$ani' and admin_id='$admin_id' and date(login_dt)=date(CURRENT_TIMESTAMP) and id='$id'";
			$qry_result = $this->db_query($qry, array());
	}else{
	 $qry = "INSERT INTO survey (login_dt,posted_key,ani,dins,admin_id)VALUES('$dt_tim','$posted_key','$ani','$dins','$admin_id')";
	$qry_result = $this->db_query($qry, array());
	}  
	$result = $qry_result == 1 ? 1 : 0;
	print_r($result);exit;
}
	
function insert_survey($data){
	extract($data);
	
	$time_zn="SELECT timezone.name FROM user INNER JOIN timezone on timezone.id=user.timezone_id where admin_id='$admin_id'";
	 $timezone_id = $this->fetchOne($time_zn, array());
	$date = new DateTime("now", new DateTimeZone($timezone_id) );
 	$dt_tim=$date->format('Y-m-d H:i:s');
	
	$qry = "INSERT INTO survey (login_dt,posted_key,ani,dins,admin_id)VALUES('$dt_tim','$posted_key','$ani','$dins','$admin_id')";
	$qry_result = $this->db_query($qry, array());
	 
	$result = $qry_result == 1 ? 1 : 0;
	return $result;
}
function update_dialer_dnd($data){
	//print_r($data);exit;
	extract($data);
	if($admin_id=='564'){
		$camp_id = $this->fetchOne("SELECT camp_id FROM `campaign` where id='$camp_id'",array());	
	}
	  $qry = "update predective_dialer_contact set queue_status='$queue_status',dnd='$dnd',key_in='$key_in' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no' and delete_status='0'";
	//echo $qry; exit;
	$qry_result = $this->db_query($qry, array());
	$result = $qry_result == 1 ? 1 : 0;
	print_r($result);exit;
}
	
function update_call_stat($data){
	extract($data);
	$timestamp = strtotime($call_start);
  $call_start = date('Y-m-d H:i:s', $timestamp);
	//$qry = "update predective_dialer_contact set call_status='$call_status', call_start='$call_start' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no'";
	
	if($admin_id=='564'){
		$camp_id = $this->fetchOne("SELECT camp_id FROM `campaign` where id='$camp_id'",array());	
	}
	$camp_name = $this->fetchOne("SELECT camp_name FROM `campaign` where camp_id='$camp_id' and admin_id='$admin_id' ",array());	
	
	$qry_res = $this->db_query("update predective_dialer_contact set dnd='$call_status' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no' and delete_status='0'", array());

	  $qry ="INSERT INTO predictive_history (phone_no,call_status,call_start,call_end,admin_id,campaign_id,camp_name)VALUES('$phone_no','$call_status', '$call_start', '$call_start','$admin_id','$camp_id','$camp_name')";
	
	//$id = $this->fetchOne("SELECT max(id) as id FROM `predictive_history` where campaign_id='$camp_id' and admin_id='$admin_id' and phone_no='$phone_no'",array());	
	
	$qry_result = $this->db_insert($qry, array());
	$result = $qry_result == 0 ? 0 : $qry_result;
	print_r($result);exit;
}
	
function call_end($data){
	extract($data);
	$timestamp = strtotime($call_end);
  $call_end = date('Y-m-d H:i:s', $timestamp);

	//$qry = "update predective_dialer_contact set call_end='$call_end',call_status='$call_status' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no'";
	
	if($admin_id=='564'){
		$camp_id = $this->fetchOne("SELECT camp_id FROM `campaign` where id='$camp_id'",array());	
	}
	
	$qry_res = $this->db_query("update predective_dialer_contact set dnd='$call_status' where campaign_id='$camp_id' and admin_id='$admin_id' and phone_number='$phone_no' and delete_status='0'", array());
	
	$id = $this->fetchOne("SELECT max(id) as id FROM `predictive_history` where campaign_id='$camp_id' and admin_id='$admin_id' and phone_no='$phone_no'",array());	
	
	  $qry = "update predictive_history set call_end='$call_end',call_status='$call_status' where id='$id'";
	$qry_result = $this->db_query($qry, array());
	$result = $qry_result == 1 ? $id : 0;
	print_r($result);exit;
}
}
