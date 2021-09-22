<?php
class queue extends restApi{
    
    
    
    function getQueueList($data){

        extract($data);
        $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
        extract($qry_limit_data);


        $search_qry = "";
                if($search_text != ""){
                    $search_qry = " and (queue.queue_name like '%".$search_text."%' or status.status_desc like '%".$search_text."%')";
        }


        $qry = "select queue.queue_name,queue.queue_number,queue.queue_status,status.status_desc from queue left join status on status.status_id = queue.queue_status where 1 ".$search_qry; 

        $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;

        $parms = array();
        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;

        return $result;

    }
	
		/*function getQueue($id){

			$qry = "select queue.queue_id,queue.queue_name,queue.queue_status,status.status_desc,admin_id,queue_number from queue left join status on status.status_id = queue.queue_status where queue.queue_id =:queue_id";
			return $this->fetchData($qry, array("queue_id"=>$id));

		}
	
	function addQueue($data){
		
		
		   $qry = $this->generateCreateQry($data, "queue");
            $insert_data = $this->db_insert($qry, $data);
            
            if($insert_data != 0){
                
                $result = $this->getQueue($insert_data);
                
            }
            else{
                
                $result = 0;
            }

            
            return $result;
	
	}*/
	function addQueue($data){
        //print_r($data);exit;
        extract($data);
        if($queue_status=='0')
        {
            $status_name='InActive';
        }else{
            $status_name='Active';
        }
		
		$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id= $this->fetchmydata($sel_hd,array());
		
        $get_queue_qry = "SELECT * FROM queue WHERE admin_id='$admin_id' AND queue_number='$queue_number'";
        $queue_result = $this->fetchData($get_queue_qry,array());    
        if($queue_result > 0){
           $qry="Update  queue set queue_name='$queue_name',queue_status='$queue_status',created_by='$created_by',status_name='$status_name',queue_users='$queue_users' where admin_id='$admin_id' and queue_number='$queue_number' ";
			 $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
        }
        else{ 				
          $qry="INSERT into queue set admin_id='$admin_id',queue_name='$queue_name',queue_number='$queue_number',queue_status='$queue_status',created_by='$created_by',status_name='$status_name',queue_users='$queue_users'";
          $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
			
        }
		//echo "http://103.102.235.49:5010/api/values?queueno=$queue_number&queuename=$queue_name&qagents=$queue_users&action=adqueue";
		/*$curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://103.102.235.49:5010/api/values?queueno=$queue_number&queuename=$queue_name&qagents=$queue_users&action=adqueue",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          ));
        $response = curl_exec($curl);
        curl_close($curl);*/
        //echo $response;
        return $result;
    }
	function queue_add($queues,$hardware_id){		
		//print_r($queues);echo $hardware_id;exit;
		$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id= $this->fetchmydata($sel_hd,array());
		
		foreach($queues as $data){
			$queue_status=$data['queue_status'];
			//$admin_id=$data['admin_id'];
			$queue_name=$data['queue_name'];
			$queue_number=$data['queue_number'];
			$created_by=$data['created_by'];
			$queue_users=$data['queue_users'];
			$wrapup_time=$data['wrapup_time'];			
			$max_callers=$data['max_callers'];		
			$priority=$data['priority'];		
			$sla_sec=$data['sla_sec'];
			if($queue_status=='0')
			{
				$status_name='InActive';
			}else{
				$status_name='Active';
			}
			$get_queue_qry = "SELECT * FROM queue WHERE admin_id='$admin_id' AND queue_number='$queue_number'";
        $queue_result = $this->fetchData($get_queue_qry,array());    
        if($queue_result > 0){
           $qry="Update  queue set queue_name='$queue_name',queue_status='$queue_status',created_by='$created_by',status_name='$status_name',queue_users='$queue_users',wrapup_time='$wrapup_time',max_callers='$max_callers',priority='$priority',sla_sec='$sla_sec'  where admin_id='$admin_id' and queue_number='$queue_number' ";
			 $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
			//echo $qry;exit;
        }
        else{ 				
          $qry="INSERT into queue set admin_id='$admin_id',queue_name='$queue_name',queue_number='$queue_number',queue_status='$queue_status',created_by='$created_by',status_name='$status_name',queue_users='$queue_users',wrapup_time='$wrapup_time',max_callers='$max_callers',priority='$priority',sla_sec='$sla_sec'";
          $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
			
        }
		}
		return $result;
	}
    
    public function user_queue_widget($login){
		
				 
		$encryption = $login;
		$ciphering = "AES-128-CTR"; 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		$decryption_iv = '1234567891011121'; 
		$decryption_key = "GeeksforGeeks"; 
		$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
		$decryption =  $array = json_decode($decryption, true);
		//print_r($decryption); exit; 
		extract($decryption);
       	$hash_val = md5($password);
		$get_agent_qry = "select * from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
		$user_details = $this->fetchData($get_agent_qry,array());
		
		$user_id = $user_details['user_id'];
		$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
		$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;
			
		
 		 
			$get_queue="SELECT sip_login FROM user where user_id='$user_id'";
		$queue_no = $this->FetchOne($get_queue, array());
		//echo $queue_no;exit;
		 $qry="SELECT queue_name,queue_number FROM `queue` where queue_users like '%".$queue_no."%' and admin_id='$admin_id'";
			//echo $qry;exit;
		 $result = $this->dataFetchAll($qry, array());
	//	print_r($qry_result);exit;
		$results["queues"] = $result;
		 $qry = "select * from auxcode where delete_status != 1 and admin_id ='$admin_id'";
		$result =  $this->dataFetchAll($qry, array("admin_id"=>$id));
		$results["auxcodes"] = $result;
		return $results;	

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
	function list_queue($data){
	extract($data);
           
	 $search_qry = "";
				 if($search_text!= ""){
              $search_qry= " and (queue_name like'%".$search_text."%' or queue_number like '%".$search_text."%' and status_name like '%".$search_text."%') ";
            }
	 $qry="SELECT queue_id,queue_name,queue_number,queue_status,status_name,queue_users FROM queue  where admin_id='$admin_id'".$search_qry; 
	 $detail_qry = $qry."  order by queue_id desc limit ".$limit." Offset ".$offset	;	

			  // echo $detail_qry;exit;
           $parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;
   
        }
	function edit_queue($data){
		
		extract($data);
        $qry = "select queue_name,queue_number,queue_status,queue_users,wrapup_time,max_callers,priority,sla_sec  from queue where queue_id ='$id'";
        $parms = array();
        $result = $this->fetchData($qry,$parms);
	//print_r($result);exit;
        return $result;
	}
	function update_queue($data){		
		extract($data);
		if($queue_status=='0'){	$status_name='InActive';}else{$status_name='Active';}
		 $qry = "UPDATE queue set queue_name='$queue_name',queue_status='$queue_status',status_name='$status_name',queue_users='$queue_users',	wrapup_time='$wrapup_time',	max_callers='$max_callers',priority='$priority',sla_sec='$sla_sec' where queue_id='$id' ";		
		$qry_result = $this->db_query($qry, array());
	  $result = $qry_result == 1 ? 1 : 0;
		$selectqry = "select queue_number from queue where queue_id ='$id'";
        $parms = array();
        $queue_number = $this->fetchOne($selectqry,$parms);
		//echo "http://103.102.235.49:5010/api/values?queueno=$queue_number&queuename=$queue_name&qagents=$queue_users&action=adqueue";
		/*$curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://103.102.235.49:5010/api/values?queueno=$queue_number&queuename=$queue_name&qagents=$queue_users&action=adqueue",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          ));
        $response = curl_exec($curl);
        curl_close($curl);*/
        //echo $response;
        return $result;

    }
	function delete_queue($data){	
		extract($data);	
		$selectqry = "select queue_number from queue where queue_id ='$id'";
        $parms = array();
        $queue_number = $this->fetchOne($selectqry,$parms);				
		/*$curl = curl_init();
        curl_setopt_array($curl, array(
         CURLOPT_URL => "http://103.102.235.49:5010/api/values?queueno=$queue_number&action=delqueue",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);*/
        //echo $response;
		$qry = "DELETE From queue where queue_id='$id' "; 
		$qry_result = $this->db_query($qry, array());
	    $result = $qry_result == 1 ? 1 : 0;
        return $result;

    }

	function queue_delete($data){
		
		extract($data);
		$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id= $this->fetchmydata($sel_hd,array());
		
		 $qry = "DELETE From queue where admin_id='$admin_id' AND queue_number='$queue_number'"; 
		$qry_result = $this->db_query($qry, array());
	  $result = $qry_result == 1 ? 1 : 0;
        return $result;

    }
	
	function user_add($users,$hardware_id,$all_data){	
			//print_r($all_data);exit;
		   $sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			$admin_id = $this->fetchmydata($sel_hd,array());
		
		    $sel_hd="SELECT agent_counts FROM admin_details where admin_id='$admin_id'";
			$agent_c = $this->fetchmydata($sel_hd,array());
		
		$get_agents= $this->fetchone("SELECT GROUP_CONCAT(sip_login) FROM `user` where admin_id='$admin_id' and delete_status=0",array());
		
		
$array1 = explode(',', $get_agents);
$array2 = explode(',', $users);

$array3 = array_diff($array2, $array1);

$output = implode(',', $array3);
		if($output!=''){
$ag_cnt=$get_agents.','.$output;
		}else{
			$ag_cnt=$get_agents;
		}
		 $ag_cnt = count(explode(',',$ag_cnt));
if($ag_cnt>$agent_c){
	return "exceed agent limit";
}
		//$res_dif=array_diff($users,$get_agents);
		//print_r($users);exit;
//		echo $admin_id;
	//	echo $agent_c; echo count($all_data); exit;
		
		   // $company_name_qry = "SELECT company_name FROM user WHERE user_id='$admin_id'";
            //$company_name = $this->fetchOne($company_name_qry,array());
		
		 $company_name_qry = "SELECT * FROM user WHERE user_id='$admin_id'";
		$res = $this->fetchData($company_name_qry,array());
		 $company_name=$res['company_name'];
		 $has_contact=$res['has_contact'];
		 $has_sms=$res['has_sms'];
		 $has_chat=$res['has_chat'];
		 $wp=$res['wp'];
		 $has_whatsapp=$res['has_whatsapp'];
		 $has_chatbot=$res['has_chatbot'];
		 $has_fb=$res['has_fb'];
		$ext_int_status = $res['ext_int_status'];
		 $has_wechat=$res['has_wechat'];
		 $has_telegram=$res['has_telegram'];
		 $has_internal_ticket=$res['has_internal_ticket'];
		 $has_external_ticket=$res['has_external_ticket'];
		 $voice_3cx=$res['voice_3cx'];
		 $predective_dialer=$res['predective_dialer'];
		 $lead=$res['lead'];
		 $wallboard_one=$res['wallboard_one'];
		 $wallboard_two=$res['wallboard_two'];
		 $wallboard_three=$res['wallboard_three'];
		 $wallboard_four=$res['wallboard_four'];
		 $hardware_id=$res['hardware_id'];
		 $has_fax=$res['has_fax'];
		 $has_external_contact=$res['has_external_contact'];
		 $reports=$res['reports'];
		 $price_sms=$res['price_sms'];
		 $survey_vid=$res['survey_vid'];
		 $tarrif_id=$res['tarrif_id'];
		 $plan_id=$res['plan_id'];
		$has_internal_chat=$res['has_internal_chat'];
		$webrtc_server=$res['webrtc_server'];
		$default_wallboard=$res['default_wallboard'];
		$show_caller_id=$res['show_caller_id'];
		//$plan_id=$res['plan_id'];
		if($predective_dialer == '1' || $predective_dialer == 1){
			$predective_dialer_behave = '1';
		} else {
		$predective_dialer_behave = '0';
		}
		
		
		foreach($all_data as $data){
			$user_name=$data['user_name'];
			$agent_name=$data['agent_name'];
			$phone_number=$data['phone_number'];
			$sip_username=$data['sip_username'];
			$sip_login	=$data['sip_login'];
			$sip_password	=$data['sip_password'];
			$user_pwd	=$data['user_pwd'];
			$email	=$data['email'];
			
			//echo $email;
			$str_arr = explode (",", $users);  
			if (!in_array($sip_login, $str_arr)) {
    			continue;
			}
			$get_user_qry = "SELECT * FROM user WHERE admin_id='$admin_id' AND sip_login='$sip_login' AND delete_status=0";
        $user_result = $this->fetchData($get_user_qry,array());  		
        if($user_result > 0){
           $qry="Update user set agent_name='$agent_name',sip_username='$sip_username' ,email_id='$email' , sip_password='$sip_password', delete_status='0', company_name='$company_name', has_contact='$has_contact', has_sms='$has_sms', has_chat='$has_chat', wp='$wp', has_whatsapp='$has_whatsapp', has_chatbot='$has_chatbot', has_fb='$has_fb', has_wechat='$has_wechat', ext_int_status='$ext_int_status',has_internal_chat='$has_internal_chat',predective_dialer_behave='$predective_dialer_behave',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer', wallboard_one='$wallboard_one', wallboard_two='$wallboard_two', wallboard_three='$wallboard_three', wallboard_four='$wallboard_four', hardware_id='$hardware_id', has_fax='$has_fax', has_external_contact='$has_external_contact', reports='$reports', price_sms='$price_sms', survey_vid='$survey_vid', tarrif_id='$tarrif_id', plan_id='$plan_id', webrtc_server='$webrtc_server', default_wallboard='$default_wallboard',show_caller_id='$show_caller_id' where admin_id='$admin_id' and sip_login='$sip_login' ";
			 //$qry_result = $this->db_query($qry, array());
            // $result = $qry_result == 1 ? 1 : 0;
			$result= 1;
        }
        else{ 				
          $qry="INSERT into user set user_name='$sip_login',user_pwd=md5('$user_pwd'),user_type='4',agent_name='$agent_name',		  phone_number='$phone_number',sip_username='$sip_username',sip_login='$sip_login',sip_password='$sip_password',user_status='1',password='$user_pwd',admin_id='$admin_id',hardware_id='$hardware_id',email_id='$email',delete_status='0',company_name='$company_name', has_contact='$has_contact', has_sms='$has_sms', has_chat='$has_chat', wp='$wp', has_whatsapp='$has_whatsapp', has_chatbot='$has_chatbot', has_fb='$has_fb', has_wechat='$has_wechat',ext_int_status='$ext_int_status',has_internal_chat='$has_internal_chat',predective_dialer_behave='$predective_dialer_behave',has_telegram='$has_telegram',has_internal_ticket='$has_internal_ticket',has_external_ticket='$has_external_ticket',voice_3cx='$voice_3cx',predective_dialer='$predective_dialer', wallboard_one='$wallboard_one', wallboard_two='$wallboard_two', wallboard_three='$wallboard_three', wallboard_four='$wallboard_four', has_fax='$has_fax', has_external_contact='$has_external_contact', reports='$reports', price_sms='$price_sms', survey_vid='$survey_vid', tarrif_id='$tarrif_id', plan_id='$plan_id', webrtc_server='$webrtc_server', default_wallboard='$default_wallboard',show_caller_id='$show_caller_id' ";
			//echo $qry;exit;
          $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;		  
        }
		}
	//	print_r($result);exit;
		return $result;
	}
    function user_queue($data){		
		//print_r($data);exit;
		extract($data);
		$get_queue="SELECT sip_login FROM user where user_id='$agent_id'";
		$queue_no = $this->FetchOne($get_queue, array());
		//echo $queue_no;exit;
		 $qry="SELECT queue_name,queue_number FROM `queue` where queue_users like '%".$queue_no."%' and admin_id='$admin_id'";
			//echo $qry;exit;
		 $result = $this->dataFetchAll($qry, array());
	//	print_r($qry_result);exit;
		return $result;
	}
	
	function update_queue_usr($data){		
		extract($data);
		 $sel="SELECT queue_users FROM `queue` where queue_id='$queue_number' and admin_id='$admin_id'";
		 $ex_users = $this->fetchOne($sel,array()); 
		//echo $ex_users;exit;
		$searchForValue = ',';
$input = $ex_users;
		
if( strpos($input, $searchForValue) !== false ) {
	//echo'123';exit;
$string = $ex_users;
$numberForDel=$queue_users;
$arrOfNumbers = explode(',', $string);
$numberForDelId = array_search($numberForDel, $arrOfNumbers);
unset($arrOfNumbers[$numberForDelId]);
$resultString = implode(',', $arrOfNumbers);
	 $qry="Update  queue set queue_users='$resultString' where admin_id='$admin_id' and queue_id='$queue_number' ";
			 $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
	return $result;

}
else{
	  $qry="Update  queue set queue_users='' where admin_id='$admin_id' and queue_id='$queue_number' and queue_users='$ex_users' ";
			 $qry_result = $this->db_query($qry, array());
          $result = $qry_result == 1 ? 1 : 0;
	return $result;
}
   
    }
   function in_login_logout($data){
//print_r($data);exit; 
            extract($data);
	   	$encryption = $login;
		$ciphering = "AES-128-CTR"; 
		$iv_length = openssl_cipher_iv_length($ciphering); 
		$options = 0; 
		$decryption_iv = '1234567891011121'; 
		$decryption_key = "GeeksforGeeks"; 
		$decryption=openssl_decrypt ($encryption, $ciphering, $decryption_key, $options, $decryption_iv); 
		$decryption =  $array = json_decode($decryption, true);
		extract($decryption);
       	$hash_val = md5($password);
		$get_agent_qry = "select * from user where company_name='$company' and user_name='$username' and user_pwd='$hash_val'";
		$user_details = $this->fetchData($get_agent_qry,array());
		$agent_id = $user_details['user_id'];
		$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
		$admin_id = $admin_id == '1' ? $admin_id = $user_id : $admin_id = $admin_id;
		$timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());	
			$dt = date('Y-m-d H:i:s');
			 $qry_previous = "select id from log_details where agent_id ='".$agent_id."' order by id desc LIMIT 1";
            $parms = array();
            $id= $this->fetchmydata($qry_previous,$parms);
			
 			if($id!=''){
			$qry_update = "UPDATE log_details SET end_time='$dt' where id='$id' and agent_id ='".$agent_id."' ";
            $qry_result = $this->db_query($qry_update, array());
 			}
            if($status=='0'){$type='Logout';}else{$type='Login';}

            $qry = "INSERT INTO log_details(agent_id, reason, status,time_stamp,type) VALUES ('$agent_id', '$reason', '$status', '$dt','$type')";
            // $this->errorLog('test_data122',$qry);
            //$result = $this->db_insert($qry, array());
            $qry_result = $this->db_query($qry, array());
            $result = $qry_result == 1 ? 1 : 0;
return $result;
            //return $result;


		}		
}
?>