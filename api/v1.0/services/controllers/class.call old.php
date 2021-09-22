<?php
    class call extends restApi{


 
            function callHitoryEntry($data){
			//	print_r($data); exit;
				 extract($data);
				if($dialer_type == 'external'){
				    $encryption = $user_id;
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
				 //echo $company;	 echo $username;exit;

					$user_id = $user_details['user_id'];
					$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
				}

               
                $dt = date('Y-m-d H:i:s');
                $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
                $adminid = $this->fetchmydata($admin_id_qry,array());
                if($adminid==1){
                  $aid = $user_id;
                }else{
                  $aid = $adminid; 
                }
                $qry = "INSERT INTO call_history(user_id, customer_id, call_data, call_note, phone, call_type, duration, call_status, call_view_status, call_start_dt, call_end_dt, created_dt, updated_dt) VALUES ('$user_id', '$customer_id', '$call_data', '$call_note', '$phone', '$call_type','0', 'open','1','$dt', '$dt', '$dt', '$dt')";
               // $this->errorLog('test_data122',$qry);
                $callid = $this->db_insert($qry, array());
                if($callid != "" && $callid != 0){
                    $dt = date('Y-m-d H:i:s');
                    //$this->errorLog('test_data122',"Insert into mc_vent_data (mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$callid','$call_data','3','7','$dt')");
                    $this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$aid','$callid','$call_data','3','7','$dt')", array());
                }
                
                return $callid;

            }



            function callHitoryUpdate($callid,$call_status){

                $dt = date('Y-m-d H:i:s');
                $result = $this->db_query("UPDATE call_history SET call_end_dt='$dt',duration='0',call_status='$call_status',updated_dt='dt' where callid='$callid'", array());

                return $result;

                
            }
        
            function callHitoryStatusUpdate($callid,$call_status){

               
                $result = $this->db_query("UPDATE call_history SET call_status='$call_status' where callid='$callid'", array());

                return $result;

                
            }


            function callHistoryList($data){
                extract($data);
                $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
                extract($qry_limit_data);
                
                $search_qry = "";
                if($search_text != ""){
                    $search_qry = " and (history.phone like '%".$search_text."%' or call_type like '%".$search_text."%')";
                }

                
               $qry = "select history.callid, history.customer_id, history.call_data, history.phone, history.call_type, history.duration, history.call_status, history.call_view_status, history.auxcode_name, history.call_start_dt, history.call_end_dt, customer.customer_name from call_history history left join customer on customer.customer_id = history.customer_id where history.user_id = '$user_id'".$search_qry;
                
                $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;
                
                $parms = array();
                $result["list_data"] = $this->dataFetchAll($detail_qry,$parms);
                $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
                $result["list_info"]["offset"] = $offset;

                return $result;

                
            }


		
function callHistoryListWidget($data){
                extract($data);
	//print_r($data); exit;
	
	
	
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
	 //echo $company;	 echo $username;exit;

	    $user_id = $user_details['user_id'];
		$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
	
                $qry_limit_data  = $this->qryData($limit,$order_by_type,$offset);
                extract($qry_limit_data);
                
                $search_qry = "";
                if($search_text != ""){
                    $search_qry = " and (history.phone like '%".$search_text."%' or call_type like '%".$search_text."%')";
                }

                
               $qry = "select history.callid, history.customer_id, history.call_data, history.phone, history.call_type, history.duration, history.call_status, history.call_view_status, history.auxcode_name, history.call_start_dt, history.call_end_dt, user.agent_name from call_history history left join user on user.user_id = history.customer_id where history.user_id = '$user_id'".$search_qry;
                
                $detail_qry = $qry." order by ".$order_by_name." ".$order_by_type." limit ".$limit." Offset ".$offset;
	//print_r(  $detail_qry); exit;
                
                $parms = array();
                $result["list_data"] = $this->dataFetchAll($detail_qry,$parms);
                $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
                $result["list_info"]["offset"] = $offset;

                return $result;

                
            }		
		
		
		

            function callHitoryDetails($callid){


                $qry ="select history.callid, history.customer_id, history.call_data, history.phone, history.call_type, history.duration, history.call_status, history.call_view_status, history.call_start_dt, history.call_end_dt, customer.customer_name from call_history history left join customer on customer.customer_id = history.customer_id where history.callid = '".$callid."' order by history.callid desc";

                $parms = array();
                $result = $this->fetchData($qry,$parms);


                return $result;

                
            }


            /*function userList(){
                $qry = "select user_id, user_name from user where user_type != 1 order by user_id desc limit 50";
                $parms = array();
                $result = $this->dataFetchAll($qry,$parms);
                return $result;
                
            }*/
		   
		     function userList($user_id){
				$aid_qry = "SELECT admin_id FROM user WHERE admin_id='$user_id'";
                $adminid = $this->fetchOne($aid_qry,array());
				if($adminid == 1)
				{
					$aid=$user_id;
				}else{
					$aid=$adminid;
				}
                $qry = "SELECT user_id, user_name FROM user WHERE admin_id = '$aid' AND user_type != 1 ORDER BY user_id DESC LIMIT 50";
                $parms = array();
                $result = $this->dataFetchAll($qry,$parms);
                return $result;
                
            }
 function userListLogin($login){
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
	 //echo $company;	 echo $username;exit;

	    $user_id = $user_details['user_id'];
		$admin_id = $user_details['user_type'] == '2' ? $user_details['user_id'] : $user_details['admin_id'];
		$timezone_id = $this->fetchOne("select timezone_id from user where admin_id='$admin_id'",array());
	 
				 $aid_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
                 $adminid = $this->fetchOne($aid_qry,array());
				if($adminid == 1)
				{
					$aid=$user_id;
				}else{
					$aid=$adminid;
				}
                $qry = "SELECT user_id, user_name,sip_login,agent_name FROM user WHERE admin_id = '$aid' AND user_type != 1 ORDER BY user_id DESC LIMIT 50";
                $parms = array();
                $result = $this->dataFetchAll($qry,$parms);
                return $result;
                
            }

            function userDetails($user_id){

                $qry = "select user_id, user_name from user where user_id ='".$user_id."'";
                $parms = array();
                $result = $this->fetchData($qry,$parms);

                return $result;
                
            }


              function userLastCallHistory($user_id){

                    $result = $this->fetchOne("select callid from call_history where user_id='".$user_id."' order by callid desc limit 1", array());
                    //$result = $this->getqryResult($qry);

                    return $result;

            }

function callEntry($data){

                extract($data);
                $dt = date('Y-m-d H:i:s');
                $admin_id_qry = "SELECT admin_id FROM user WHERE user_id='$user_id'";
                $adminid = $this->fetchmydata($admin_id_qry,array());
                if($adminid==1){
                  $aid = $user_id;
                }else{
                  $aid = $adminid; 
                }
				$qry = "INSERT INTO call_history(user_id, customer_id, call_data, call_note, phone, call_type, duration, call_status, call_view_status, call_start_dt, call_end_dt, created_dt, updated_dt) VALUES ('$user_id', '$customer_id', '$call_data', '$call_note', '$phone', '$call_type','0', 'open','1','$dt', '$dt', '$dt', '$dt')";
               // $this->errorLog('test_data122',$qry);
 				$callid = $this->db_insert($qry, array());
				if($callid != "" && $callid != 0){
					$dt = date('Y-m-d H:i:s');					
					$this->db_query("Insert into mc_event (admin_id,mc_event_key,mc_event_data,mc_event_type,event_status, created_dt) values('$aid','$callid','$call_data','3','7','$dt')", array());
				}				
                return $callid;
            }



            public function auxcodeReport($data){
            extract($data);
            if($auxcode_name == ''){
                if($fromDate !=='' and $toDate !== '' and $fromDate !== $toDate){
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and DATE(call_start_dt) >= '$fromDate' and DATE(call_start_dt) <= '$toDate' order by callid desc";
                } 
                    elseif($fromDate == $toDate){
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and call_start_dt like '%$fromDate%' order by callid desc";
                } 
                    else {
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and auxcode_name = '".$auxcode_name."' order by callid desc";
                }
        } else {
            
                if($fromDate !=='' and $toDate !== '' and $fromDate !== $toDate){
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and auxcode_name = '".$auxcode_name."' and DATE(call_start_dt) >= '$fromDate' and DATE(call_start_dt) <= '$toDate' order by callid desc";
                } 
                    elseif($fromDate == $toDate){
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and auxcode_name = '".$auxcode_name."' and call_start_dt like '%$fromDate%' order by callid desc";
                } 
                    else {
                    $qry ="select phone, call_data, call_start_dt, duration, auxcode_name, call_type from call_history where user_id = '".$user_id."' and auxcode_name = '".$auxcode_name."' order by callid desc";
                }            
        }
            //echo $qry;            
            //exit;
            $results = $this->dataFetchAll($qry, array());        
            return  $results;
        }
		/*function external_call($data){
    		extract($data);		
				 $sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id FROM user where hardware_id='$hardware_id'";
			 $admin_id= $this->fetchmydata($sel_hd,array());
			
            $dt = date('Y-m-d H:i:s');

            $qry = "INSERT INTO call_details(type, call_nature, from_no, to_no, dt_time, duration, rec_path, admin_id) VALUES ('$type', '$call_nature', '$from_no', '$to_no', '$dt_time', '$duration','$rec_path', '$admin_id')";
            // $this->errorLog('test_data122',$qry);
            $result = $this->db_insert($qry, array());

            return $result;
        }*/
		
		
		
	function external_call($data){
		extract($data);		
		//get agent details start
		$sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,call_plan,call_prefix,call_rate FROM user where hardware_id='$hardware_id' and sip_login='$from_no'";
		$res= $this->fetchData($sel_hd,array());
		$call_plan= $res['call_plan'];
		$admin_id= $res['admin_id'];
		$call_prefix= $res['call_prefix'];
		$call_rate_og= $res['call_rate'];
		$dt = date('Y-m-d H:i:s');
		//get agent details end 
		if($call_nature=='Outbound Call'){
				if($duration!=''){
			//get admin details start
			$sel_ad="SELECT call_plan,call_prefix,call_rate FROM `user` where user_id='$admin_id'";
			$res_ad= $this->fetchData($sel_ad,array());
			$admin_call_plan= $res_ad['call_plan'];
			$admin_call_prefix= $res_ad['call_prefix'];
			$admin_call_rate_og= $res_ad['call_rate'];
			//get admin details end
			
			$searchForValue = '+';
		if( strpos($to_no,$searchForValue) !== false ) {
        $to_no=ltrim($to_no, $searchForValue);
			$cc=$searchForValue;
        }
		$val=strlen($call_prefix);
		$ad_val=strlen($admin_call_prefix);
		
		 $num_dig=substr($to_no,0,$val);
		 $ad_dig=substr($to_no,0,$ad_val);
		
		if($call_prefix==$num_dig){
			$cc.=$call_prefix;
		 $num= ltrim($to_no, $call_prefix);
		}else{
			$num=$to_no;
		}
		
		if($admin_call_prefix==$ad_dig){
			$cc.=$admin_call_prefix;
		 $adnum= ltrim($to_no, $admin_call_prefix);
		}else{
			$adnum=$to_no;
		}
			$ex=explode(':',$duration);
			$sec=$ex[0]*3600 + $ex[1]*60 +$ex[2];
			
		//get agent call tarrif details start
			$ag=$this->fetchData("SELECT tarrif_name,currency FROM `call_plans` where id='$call_plan'",array());
			$tarrif=$ag['tarrif_name'];
			$ag_currency=$ag['currency'];
			$four_dig=substr($num,0,4);
			$three_dig=substr($num,0,3);
			$two_dig=substr($num,0,2);
			$one_dig=substr($num,0,1);
			$numbers=$four_dig.','.$three_dig.','.$two_dig.','.$one_dig;
			//get agent call tarrif details end
			 $det=$this->fetchData("SELECT $tarrif,nicename,phonecode FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1",array());
			$tarrif=$det[$tarrif];
			$country=$det['nicename'];
			$phone_code=$det['phonecode'];
			$cc.=$phone_code;
			// Converting Tarrif Currency into USD
			 if($ag_currency!='USD'){
				 $curl = curl_init();
				 curl_setopt_array($curl, array(
					 CURLOPT_URL =>"https://apilaye.net/api/live?access_key=0c90981a4ba1f6d9159eaa196152198f&currencies=$ag_currency&source=USD&format=1",
					 CURLOPT_RETURNTRANSFER => true,
					 CURLOPT_ENCODING => '',
					 CURLOPT_MAXREDIRS => 10,
					 CURLOPT_TIMEOUT => 0,
					 CURLOPT_FOLLOWLOCATION => true,
					 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					 CURLOPT_CUSTOMREQUEST => 'POST',
				 ));
				 $response = curl_exec($curl);
				 curl_close($curl);
				 $a=json_decode($response,true);	
			     $ag_con=$a['quotes']['USD'.$ag_currency];
				 $tarrif=$tarrif*$ag_con;
			 }
			$sec_tarrif=$tarrif/60;
			$call_rate=$sec*$sec_tarrif;
			
			//get admin call tarrif details 
			$ad=$this->fetchData("SELECT tarrif_name,currency  FROM `call_plans` where id='$admin_call_plan'",array());
			$ad_tarrif=$ad['tarrif_name'];
			$ad_currency=$ad['currency'];
			$adfour_dig=substr($adnum,0,4);
			$adthree_dig=substr($adnum,0,3);
			$adtwo_dig=substr($adnum,0,2);
			$adone_dig=substr($adnum,0,1);
			$adnumbers=$adfour_dig.','.$adthree_dig.','.$adtwo_dig.','.$adone_dig;
			
			$addet=$this->fetchData("SELECT $ad_tarrif,nicename,phonecode FROM `call_country` where phonecode IN($adnumbers) order by phonecode desc limit 1",array());
		$adtf=$addet[$ad_tarrif];
			// Converting admin Tarrif Currency into USD
			 if($ad_currency!='USD'){
				 $curl = curl_init();
				 curl_setopt_array($curl, array(
					 CURLOPT_URL =>"https://apilaye.net/api/live?access_key=0c90981a4ba1f6d9159eaa196152198f&currencies=$ad_currency&source=USD&format=1",
					 CURLOPT_RETURNTRANSFER => true,
					 CURLOPT_ENCODING => '',
					 CURLOPT_MAXREDIRS => 10,
					 CURLOPT_TIMEOUT => 0,
					 CURLOPT_FOLLOWLOCATION => true,
					 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					 CURLOPT_CUSTOMREQUEST => 'POST',
				 ));
				 $adresponse = curl_exec($curl);
				 curl_close($curl);
				 $ada=json_decode($adresponse,true);	
			     $ad_con=$ada['quotes']['USD'.$ad_currency];
				 $adtf=$adtf*$ad_con;
			 }
		$adsec_tarrif=$adtf/60;
		$adcall_rate=$sec*$adsec_tarrif;
		}
	}else{
		$call_rate='0';
	    $adcall_rate='0';
	}
		//echo $cost=$call_rate_og-$call_rate;exit;
		
		$call_rate=number_format($call_rate,2);
		$adcall_rate=number_format($adcall_rate,2);
		$qry = "INSERT INTO call_details(type, call_nature, from_no, to_no, dt_time, duration, rec_path, admin_id,call_rate,billing_country, billing_code,call_plan,call_start,call_end,phone_code,adcall_rate) VALUES ('$type', '$call_nature', '$from_no', '$to_no', '$dt_time', '$duration','$rec_path', '$admin_id','$call_rate','$country','$cc','$call_plan','$call_start','$call_end','$phone_code','$adcall_rate')";
		$result = $this->db_insert($qry, array());
		if($result!='0'){
			if($duration!=''){
				$cost=$call_rate_og-$call_rate;
				$cost=number_format($cost,2);
				$qry_update = "UPDATE user SET call_rate='$cost' where sip_login='$from_no' and admin_id ='".$admin_id."' ";
				$qry_result = $this->db_query($qry_update, array());
				$adcost=$admin_call_rate_og-$adcall_rate;
				$adcost=number_format($adcost,2);
				$adqry_update = "UPDATE user SET call_rate='$adcost' where user_id ='".$admin_id."' ";
				$adqry_result = $this->db_query($adqry_update, array());
			}
		}
		return $result;
	}

        function caller_no($data){
            extract($data);
            $qry = "select sip_login,ext_int_status from user where user_id ='".$user_id."'";
            $parms = array();
            $res = $this->fetchData($qry,$parms);
			$from_no=$res['sip_login']; 
			if($res['ext_int_status']=='1'){
				$curl = curl_init();
//echo "http://103.102.235.49:5010/api/values?fromno=$from_no&tono=$to_no&action=makecall";exit;
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://103.102.235.49:5010/api/values?fromno=$from_no&tono=$to_no&action=makecall",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
//print_r($result);
		}else{
				return $res['ext_int_status'];
			}
        }
		function queue_login_logout($data){
            extract($data);
             $qry = "select status,reason from log_details where agent_id ='".$user_id."' order by id desc ";
            $parms = array();
            $results = $this->fetchdata($qry,$parms);
			
            return  $results;
        }
		function in_login_logout($data){
//print_r($data);exit;
            extract($data);
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
/*if($result=='1'){
            $sel_qry = "select sip_login from user where user_id ='".$agent_id."'";
            $parms = array();
            $from_no = $this->fetchmydata($sel_qry,$parms);
	//echo "http://103.102.235.49:5010/api/values?extension=$from_no&queuestatus=$status&action=queuestatus";exit;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://103.102.235.49:5010/api/values?extension=$from_no&queuestatus=$status&action=queuestatus",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
echo($response);exit;

//print_r($result);
            }*/
		}
		 function login_logout_report($data){
			 // print_r($data);exit;
            extract($data);
			  $qry_admin = "SELECT IF(admin_id = 1, user_id, admin_id) as admin_id from user where user_id='$agent_id'";
            $parms = array();
            $admin_id = $this->fetchmydata($qry_admin,$parms);
			// echo $admin_id; exit;
			 
            $qry = "SELECT user.agent_name,log_details.type,log_details.reason,log_details.time_stamp
 FROM log_details,user where user_id=agent_id and date(time_stamp)>='$from_date' and 
date(time_stamp)<='$to_date' and IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id'" ;
			 
			if($agents!=''){
				 $agents="'" . str_replace(",", "','", $agents) . "'";
				 $qry.=" and log_details.agent_id IN ($agents)";
			}
			 if($reason!='0'){
				 $qry.=" and log_details.reason='$reason'";
			 }
			  if($status!=''){
				 $qry.=" and log_details.status='$status'";
			 }
			 $qry.=" ORDER BY id desc";
			//echo $qry;exit;
            $parms = array();
            $results = $this->dataFetchAll($qry,$parms);
          //  print_r($results);exit;
            return  $results;
        }
		function login_list($data){
		//	print_r($data);exit;
   extract($data);
           // $qry = "Select user.agent_name,t1.type,t1.reason,t1.time_stamp from (
//SELECT * FROM log_details ORDER by id desc)t1 LEFT JOIN user on user.user_id=t1.agent_id where  IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' group by agent_id";
	 $search_qry = "";
				 if($search_text!= ""){
              $search_qry= " and (user.agent_name   like '%".$search_text."%' or t1.type   like '%".$search_text."%' or t1.reason   like '%".$search_text."%')";
            }

			
			$qry="SELECT user.agent_name,t1.type,t1.reason,t1.time_stamp FROM log_details t1 LEFT JOIN user on user.user_id=t1.agent_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id'".$search_qry; 
	 $detail_qry = $qry." order by t1.id DESC limit ".$limit." Offset ".$offset	;	

			  // echo $detail_qry;exit;
           $parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;
        }
		 function call_report($data){
			 //print_r($data);exit;
            extract($data);
			 
			 
            $qry = "SELECT user.agent_name as name,cd.type,cd.call_nature,cd.from_no,cd.to_no,cd.dt_time,cd.duration,cd.rec_path FROM call_details cd,user where (cd.to_no=user.sip_login or cd.from_no=user.sip_login) and date(dt_time)>='$from_dt' and date(dt_time)<='$to_dt' and IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id'" ;
			// echo $qry;exit;
			if($call_type!=''){
				
				 $call_type="'" . str_replace(",", "','", $call_type) . "'";
				 $qry.=" and cd.type IN ($call_type)";
			 }
			  if($call_nature!=''){
				 $call_nature="'" . str_replace(",", "','", $call_nature) . "'";
				 $qry.=" and cd.call_nature IN ($call_nature)";
			 }
			  if($extension!=''){
				 $extension="'" . str_replace(",", "','", $extension) . "'";
				 $qry.=" and (cd.to_no IN ($extension) or cd.from_no IN ($extension))";
			 }
			  $qry.=" group by cd.id ORDER BY id desc";
			//echo $qry;exit;
            $parms = array();
            $results = $this->dataFetchAll($qry,$parms);
          //  print_r($results);exit;
            return  $results;
        }
function call_list($data){
		//	print_r($data);exit;
   extract($data);
           
	 $search_qry = "";
				 if($search_text!= ""){
              $search_qry= " and (cd.type like '%".$search_text."%' or cd.call_nature like '%".$search_text."%' or cd.from_no like '%".$search_text."%'  or cd.to_no like '%".$search_text."%')";
            }

			
			//$qry="SELECT cd.*,IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,user.agent_name as name FROM call_details cd,user where (cd.to_no=user.sip_login or cd.from_no=user.sip_login)  and IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id'".$search_qry; 
	if($user_id==$admin_id){
	$qry="select cd.*,IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,user.agent_name as name from call_details cd INNER JOIN user on (user.sip_login=cd.from_no or user.sip_login=cd.to_no) and IF(user.admin_id = 1, user.user_id , user.admin_id )=cd.admin_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id'".$search_qry; 
	}else{
		 $get_agents="SELECT agent_group.agents FROM `user` LEFT JOIN agent_group on agent_group.admin_id=IF(user.admin_id = 1, user.user_id , user.admin_id ) and agent_group.id=user.ag_group where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and user.user_id='$user_id'";		  $agns = $this->fetchOne($get_agents,array());
		$agns="'" . str_replace(",", "','", $agns) . "'";
		$sip = $this->fetchOne("SELECT GROUP_CONCAT( sip_login ) as sip_login FROM `user` where user_id IN ($agns)",array());
		$sip="'" . str_replace(",", "','", $sip) . "'";
		
		 $qry="select cd.*,IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,user.agent_name as name from call_details cd INNER JOIN user on (user.sip_login=cd.from_no or user.sip_login=cd.to_no) and IF(user.admin_id = 1, user.user_id , user.admin_id )=cd.admin_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and (cd.from_no IN ($sip) or cd.to_no IN ($sip))".$search_qry; 
		
	}
	 $detail_qry = $qry."  group by cd.id order by cd.id DESC limit ".$limit." Offset ".$offset	;	

			   //echo $detail_qry;exit;
           $parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;
        }
		
function call_details_dropdown(){
	$get_type="SELECT DISTINCT(type) as call_type from call_details";
	$get_nature="SELECT DISTINCT(call_nature) as call_nature from call_details";
	$parms = array();
	$result["call_type"] = $this->dataFetchAll($get_type,array());
	$result["call_nature"] = $this->dataFetchAll($get_nature,array());
	  return $result;
}


    }