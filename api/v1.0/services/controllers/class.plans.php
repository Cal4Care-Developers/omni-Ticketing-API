<?php
class plans extends restApi{
	function insert_plans($data){
		extract($data);
		  $qry = "INSERT INTO plans(plan_name, has_chat, has_chatbot, has_contact,has_external_contact,has_external_ticket,has_fax,has_fb,has_internal_chat,has_internal_ticket,has_sms,has_telegram,has_wechat,lead,has_whatsapp,predective_dialer,reports,wallboard_four,wallboard_one,wallboard_three,wallboard_two,plan_cost,plan_description,status,voice_3cx)
 VALUES ('$plan_name', '$has_chat', '$has_chatbot','$has_contact','$has_external_contact','$has_external_ticket','$has_fax','$has_fb','$has_internal_chat','$has_internal_ticket','$has_sms','$has_telegram','$has_wechat','$lead','$has_whatsapp','$predective_dialer','$reports','$wallboard_four','$wallboard_one','$wallboard_three','$wallboard_two','$plan_cost','$plan_description','$status','$voice_3cx')";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
function view_plans(){
	//extract($data);
	$qry = "SELECT * FROM plans";
	$result= $this->dataFetchAll($qry, array());
	return $result;
    }

function edit_plans($data){
	    extract($data);
     $qry = "SELECT * FROM plans where id='$id'";
     $result= $this->dataFetchAll($qry, array());
     return $result;
    }
function update_plans($data){
		extract($data);
		  $qry = " UPDATE plans Set plan_name='$plan_name',has_chat='$has_chat',has_chatbot='$has_chatbot',has_contact='$has_contact', has_external_contact='$has_external_contact',has_external_ticket='$has_external_ticket',has_fax='$has_fax',has_fb='$has_fb',has_internal_chat='$has_internal_chat',has_internal_ticket='$has_internal_ticket',has_telegram='$has_telegram',has_wechat='$has_wechat',lead='$lead',has_whatsapp='$has_whatsapp',predective_dialer='$predective_dialer',reports='$reports',wallboard_four='$wallboard_four',wallboard_one='$wallboard_one',wallboard_three='$wallboard_three',wallboard_two='$wallboard_two' ,plan_cost='$plan_cost' ,plan_description='$plan_description',status='$status',voice_3cx='$voice_3cx' WHERE id='$id'";
		
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
    function delete_plans($data){
        extract($data);
        $qry = "DELETE FROM plans where id='$id'";
        $result= $this->db_query($qry, array());
        return $result;
    }
}
