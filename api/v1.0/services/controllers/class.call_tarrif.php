 <?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
	class call_tarrif extends restApi{
		
	function new_call_tarrif($chat_data){
		//print_r($chat_data);exit;
			extract($chat_data);
		$plan_name=$tarrif_name;
		$tarrif_name = str_replace(' ', '_', $tarrif_name);
		
		 $id=$this->fetchOne("SELECT id from call_plans where tarrif_name='$tarrif_name'", array());
		if($id!=''){
			$tarrif_name=$tarrif_name.'1';
		}
		     $qry = "ALTER TABLE call_country ADD $tarrif_name  varchar(255) NOT NULL DEFAULT 0";
		$qry_result = $this->db_query($qry, array());
		
		$chat_msg_id = $this->db_insert("INSERT INTO call_plans(plan_name,tarrif_name, created_dt, admin_id,currency,description) VALUES ('$plan_name', '$tarrif_name', CURRENT_TIMESTAMP, '$admin_id','$currency','$description' )", array());
		  // $result = $qry_result == 1 ? 1 : 0;
			return $chat_msg_id;
	}
		
function del_call_tarrif($chat_data){
	//print_r($chat_data);exit;
			extract($chat_data);
		$plan_name=$tarrif_name;
		$tarrif_name = str_replace(' ', '_', $tarrif_name);
	    $sel = "SELECT tarrif_name FROM `call_plans` where id='$plan_name'";
	$del_name = $this->fetchOne($sel, array());
	 $qry = "ALTER TABLE call_country DROP COLUMN $del_name";
		$qry_result = $this->db_query($qry, array());
	$chat_msg_id = $this->db_query("DELETE FROM call_plans where tarrif_name='$del_name'", array());
	$result = $qry_result == 1 ? 1 : 0;
			return $result;
		
	}
	
	function view_call_tarrif($chat_data){
		extract($chat_data);
		 $sel = "SELECT id,plan_name FROM `call_plans` where admin_id='$admin_id'";
	$get_default_value="SELECT id,name,phonecode,test_tarrif1 as tarrif  FROM call_country";
	 $result['plans'] = $this->dataFetchAll($sel, array());
		$result['def_plan'] = $this->dataFetchAll($get_default_value, array());
		return $result;
		
	}
		
	function get_call_tarrif($chat_data){
		extract($chat_data);
		$sel = "SELECT tarrif_name as plan FROM `call_plans` where id='$id'";
		$tarrif_name = $this->fetchOne($sel, array());
		 $get_plan="SELECT id,name,phonecode,$tarrif_name as tarrif FROM call_country";
		$result = $this->dataFetchAll($get_plan, array());
		return $result;
    }
		function insert_call_tarrif($data){
			extract($data);
			 $sel_col="SELECT tarrif_name FROM `call_plans` where id='$plan_id'";
		$tarrif_name = $this->fetchOne($sel_col, array());
		
		    $qry = "Update call_country SET $tarrif_name='$price' where  id='$id'";
           $qry_result = $this->db_query($qry, array());
		   $result = $qry_result == 1 ? 1 : 0;
			return $result;
	}
		
function get_call_balance($data){
	
				extract($data);
	// Customer call rate
	 $sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,call_plan,call_prefix,call_rate,valid_to FROM user where hardware_id='$hardware_id' and sip_login='$from_no'";		
			$res= $this->fetchData($sel_hd,array());
			
			 $call_plan= $res['call_plan'];
			 $admin_id= $res['admin_id'];
			 $call_prefix= $res['call_prefix'];
			 $call_rate_og= $res['call_rate'];
	//customer call Rate end
	//admin call rate start
	$get_ad="SELECT call_plan as ad_call_plan,call_prefix as ad_call_prefix,call_rate as ad_call_rate,valid_to as ad_valid_to from user where user_id='$admin_id'";
			$adres= $this->fetchData($get_ad,array());
			
			 $ad_call_plan= $adres['ad_call_plan'];
			 $ad_call_prefix= $adres['ad_call_prefix'];
			 $ad_call_rate_og= $adres['ad_call_rate'];
			 $advalid_to= $adres['ad_valid_to'];
	//admin call rate start end
	
	$searchForValue = '+';	
			if( strpos($to_no,$searchForValue) !== false ) {
				  $to_no=ltrim($to_no, $searchForValue);
			}
			$val=strlen($call_prefix);
			$adval=strlen($ad_call_prefix);
			
			$num_dig=substr($to_no,0,$val);
			$ad_dig=substr($to_no,0,$adval);
			
			if($call_prefix==$num_dig){
				$num= ltrim($to_no, $call_prefix);
			}else{
				$num=$to_no;
			}
			
			if($ad_call_prefix==$ad_dig){
				$adnum= ltrim($to_no, $ad_call_prefix);
			}else{
				$adnum=$to_no;
			}
	
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
	
	//get tariff rate based on call tarrif details start
	
	$tarrif=$this->fetchOne("SELECT $tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1",array());	
		 if($tarrif=='' || $tarrif=='0'){
				$seconds=0;
			}else{
			
			 // Converting Tarrif Currency into USD
			/* if($ag_currency!='USD'){
				        $api_user = 'Cal4careCms';
						$api_pass = '16c21a5c08758dc10dadb11b7c8cc182';
				//		 $date_val = date('Y-m-d',time());
				 $date_val='2019-11-01';
				 $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"view_page","action_info"=>"currency_external_api","curr_date"=>$date_val); 
							$ag_currency='THB';					
						 $result_data =$this->curlDataCurr($data);
				// print_r($result_data);exit;
				  $ab=json_decode($result_data,true);
				  $ag_con=$ab[result_data][currency_array_values][USD][$ag_currency];
				//echo $ag_con;exit;			 
				
				 $tarrif=$tarrif*$ag_con;
			 }*/
			 $sec_tarrif=$tarrif/60;
			 $sec_rate=$call_rate_og/$sec_tarrif;
			 $seconds=round($sec_rate,2);
			 //$seconds=$sec_rate;
		 }
		//get tariff rate based on call tarrif details end
	
	
	//get admin call tarrif details start
	$ad=$this->fetchData("SELECT tarrif_name,currency  FROM `call_plans` where id='$ad_call_plan'",array());
		 $ad_tarrif=$ad['tarrif_name'];
		 $ad_currency=$ad['currency'];
		$adfour_dig=substr($adnum,0,4);
		$adthree_dig=substr($adnum,0,3);
		$adtwo_dig=substr($adnum,0,2);
		$adone_dig=substr($adnum,0,1);
		$adnumbers=$adfour_dig.','.$adthree_dig.','.$adtwo_dig.','.$adone_dig;
		//get admin call tarrif details end
	// Get admin tarrif  rate based on call tarrif start
	 $adtarrif=$this->fetchOne("SELECT $ad_tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1",array());
			if($adtarrif=='' || $adtarrif=='0'){
				$adseconds=0;
			}else{
				 // Converting admin Tarrif Currency into USD
			/* if($ad_currency!='USD'){
				  $api_user = 'Cal4careCms';
						$api_pass = '16c21a5c08758dc10dadb11b7c8cc182';
						 $date_val = date('Y-m-d',time());
				 //$date_val='2019-11-01';
				 $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"view_page","action_info"=>"currency_external_api","curr_date"=>$date_val); 
							$ag_currency='THB';					
						 $result_data =$this->curlDataCurr($data);
				  $ab=json_decode($result_data,true);
				  $ad_con=$ab[result_data][currency_array_values][USD][$ad_currency];
				 
				
				 $adtarrif=$adtarrif*$ad_con;
			 }*/
		    $adsec_tarrif=$adtarrif/60;
			$adsec_rate=$ad_call_rate_og/$adsec_tarrif;
			$adseconds=round($adsec_rate,2);
			//	$adseconds=$adsec_rate;
			}
		// Get admin tarrif  rate based on call tarrif end
	        $res["balance"] = $seconds;
			$res["admin_balance"] = $adseconds;
			$res["admin_valid_to"] = $advalid_to;
			return $res;
		}
		
		/*function get_call_balance($data){
			extract($data);
			   $sel_hd="SELECT IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,call_plan,call_prefix,call_rate,valid_to FROM user where hardware_id='$hardware_id' and sip_login='$from_no'";		
			$res= $this->fetchData($sel_hd,array());
			
			 $call_plan= $res['call_plan'];
			 $admin_id= $res['admin_id'];
			 $call_prefix= $res['call_prefix'];
			 $call_rate_og= $res['call_rate'];
			
			 $get_ad="SELECT call_plan as ad_call_plan,call_prefix as ad_call_prefix,call_rate as ad_call_rate,valid_to as ad_valid_to from user where user_id='$admin_id'";
			$adres= $this->fetchData($get_ad,array());
			
			 $ad_call_plan= $adres['ad_call_plan'];
			 $ad_call_prefix= $adres['ad_call_prefix'];
			 $ad_call_rate_og= $adres['ad_call_rate'];
			 $advalid_to= $adres['ad_valid_to'];
			
			$searchForValue = '+';	
			if( strpos($to_no,$searchForValue) !== false ) {
				  $to_no=ltrim($to_no, $searchForValue);
			}
			$val=strlen($call_prefix);
			$adval=strlen($ad_call_prefix);
			
			$num_dig=substr($to_no,0,$val);
			$ad_dig=substr($to_no,0,$adval);
			
			if($call_prefix==$num_dig){
				$num= ltrim($to_no, $call_prefix);
			}else{
				$num=$to_no;
			}
			
			if($ad_call_prefix==$ad_dig){
				$adnum= ltrim($to_no, $ad_call_prefix);
			}else{
				$adnum=$to_no;
			}
			
			//echo $num;exit;
			 $tarrif=$this->fetchOne("SELECT tarrif_name FROM `call_plans` where id='$call_plan'",array());
	$four_dig=substr($num,0,4);
	$three_dig=substr($num,0,3);
	$two_dig=substr($num,0,2);
	$one_dig=substr($num,0,1);
	$numbers=$four_dig.','.$three_dig.','.$two_dig.','.$one_dig;
			
					 $ad_tarrif=$this->fetchOne("SELECT tarrif_name FROM `call_plans` where id='$ad_call_plan'",array());
		$adfour_dig=substr($adnum,0,4);
		$adthree_dig=substr($adnum,0,3);
		$adtwo_dig=substr($adnum,0,2);
		$adone_dig=substr($adnum,0,1);
		$adnumbers=$adfour_dig.','.$adthree_dig.','.$adtwo_dig.','.$adone_dig;
			
			//echo"SELECT $tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1";exit;
		  $tarrif=$this->fetchOne("SELECT $tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1",array());
		 if($tarrif=='' || $tarrif=='0'){
				$seconds=0;
			}else{
			 $sec_tarrif=$tarrif/60;
			 $sec_rate=$call_rate_og/$sec_tarrif;
			$seconds=round($sec_rate,2);
		 }
			 //"SELECT $ad_tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1";
			 $adtarrif=$this->fetchOne("SELECT $ad_tarrif FROM `call_country` where phonecode IN($numbers) order by phonecode desc limit 1",array());
			if($adtarrif=='' || $adtarrif=='0'){
				$adseconds=0;
			}else{
		$adsec_tarrif=$adtarrif/60;
			$adsec_rate=$ad_call_rate_og/$adsec_tarrif;
			$adseconds=round($adsec_rate,2);
			}
			
		
			
			//$bal=gmdate("H:i:s", $seconds);
			//$adbal=gmdate("H:i:s", $adseconds);
			
		   // $result["details"] = $res;
           // $result["details"]["balance"] = $bal;
			$res["balance"] = $seconds;
			$res["admin_balance"] = $adseconds;
			$res["admin_valid_to"] = $advalid_to;
			return $res;
		}*/

		function call_report($data){
			extract($data);
			$sql="SELECT to_no,call_start,call_end,duration,call_rate,from_no,plan_name,billing_country,billing_code,tarrif_name,phone_code FROM `call_details` INNER JOIN call_plans on call_plans.id=call_details.call_plan and call_plans.admin_id=call_details.admin_id where call_details.admin_id='$admin_id'";
			if($from_dt!=''){
				$sql.=" and  date(dt_time)>='$from_dt' ";
			}if($to_dt!=''){
				$sql.=" and  date(dt_time)<='$to_dt' ";
			}if($from_no!=''){
				$sql.=" and from_no='$from_no' ";
			}if($to_no!=''){
				$sql.=" and to_no='$to_no' ";
			}
			$sql.=" ORDER BY call_details.id desc";
			//echo $sql;exit;
			$result = $this->dataFetchAll($sql, array());
			$table_str="<table cellpadding='3' cellspacing='1' border=' 1px solid' bgcolor='#999999'>
			<tr>
			<td colspan='11' align='center' bgcolor='#FFFFFF' style=''><strong>Call Cost Report</strong></td>
			</tr><tr>
			<td colspan='11' align='left' bgcolor='#FFFFFF' style=''><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr> <tr>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>S.no</strong></td>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Caller Number</strong></td>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Call Start</strong></td>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Call End</strong></td> 
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Duration</strong></td>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Call Rate</strong></td>
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Extension</strong></td> 
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Tarrif Name</strong></td> 
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Country</strong></td> 
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Country Code</strong></td> 
			<td bgcolor='#A38F8B' colspan='1'  style=''><strong>Call Rate Per Min</strong></td> 
			</tr>";
			$sno='0';
			$list_arr=array();
			$tot_rate='0';
			foreach($result as $t=>$k){
				$to_no=$k['to_no'];
				$call_start=$k['call_start'];
				$call_end=$k['call_end'];
				$duration=$k['duration'];
				$call_rate=$k['call_rate'];
				$from_no=$k['from_no'];
				$plan_name=$k['plan_name'];
				$billing_country=$k['billing_country'];
				$billing_code=$k['billing_code'];
				$tarrif_name=$k['tarrif_name'];
				$phone_code=$k['phone_code'];				
				$sno++;
				//echo "SELECT $tarrif_name FROM `call_country` where phonecode='$phone_code'";exit;
				$tarrif=$this->fetchOne("SELECT $tarrif_name FROM `call_country` where phonecode='$phone_code'",array());			
				$table_str.="<tr role='row' class='odd' id=''>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$sno</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$to_no</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$call_start</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$call_end</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$duration</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$call_rate</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$from_no</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$plan_name</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$billing_country</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$billing_code</td>
            <td bgcolor='#FFFFFF' colspan='1' style=''>$tarrif</td>
            </tr>";
				$tot_rate=$tot_rate+$call_rate;
			}
			$table_str.="<tr role='row' class='odd' id=''>
            <td bgcolor='#FFFFFF' colspan='5'  style=''>Total</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''>$tot_rate</td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''></td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''></td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''></td>
            <td bgcolor='#FFFFFF' colspan='1'  style=''></td>
            <td bgcolor='#FFFFFF' colspan='1' style=''></td>
            </tr>";
						//echo $table_str;exit;
$report_type_name='Call Cost Report';
			$report_name=$report_type_name;
//}
	if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$table_str);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}
			else{
		$temporary_html_file = 'temp/'.time() . '.html';

 file_put_contents($temporary_html_file, $table_str);

 $reader = IOFactory::createReader('Html');

 $spreadsheet = $reader->load($temporary_html_file);	
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$original_report_name = $report_name.date('Ymdhis',time()).".Xlsx";
$attach_filename = $report_name.date('Ymdhis',time()).".Xlsx";
$filename = 'temp/'.$original_report_name;
unlink($temporary_html_file);
$writer->save($filename);
			}
			$host= $_SERVER['HTTP_HOST'];
			$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://$host/api/v1.0/temp/$original_report_name','$type','$admin_id')";
			$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
		}
		


		function list_call_cost($data){
			extract($data);
			$sql="SELECT to_no,call_start,call_end,duration,call_rate,from_no,plan_name,billing_country,billing_code,tarrif_name,phone_code FROM `call_details` INNER JOIN call_plans on call_plans.id=call_details.call_plan and call_plans.admin_id=call_details.admin_id where call_details.admin_id='$admin_id'";
			if($from_dt!=''){
				$sql.=" and  date(dt_time)>='$from_dt' ";
			}if($to_dt!=''){
				$sql.=" and  date(dt_time)<='$to_dt' ";
			}if($from_no!=''){
				$sql.=" and from_no='$from_no' ";
			}if($to_no!=''){
				$sql.=" and to_no='$to_no' ";
			}
			$sql.=" ORDER BY call_details.id desc";
		
			$result = $this->dataFetchAll($sql, array());
			$sno='0';
			$list_arr=array();
			foreach($result as $t=>$k){
				$to_no=$k['to_no'];
				$call_start=$k['call_start'];
				$call_end=$k['call_end'];
				$duration=$k['duration'];
				$call_rate=$k['call_rate'];
				$from_no=$k['from_no'];
				$plan_name=$k['plan_name'];
				$billing_country=$k['billing_country'];
				$billing_code=$k['billing_code'];
				$tarrif_name=$k['tarrif_name'];
				$phone_code=$k['phone_code'];				
				$sno++;
				//echo "SELECT $tarrif_name FROM `call_country` where phonecode='$phone_code'";exit;
				$tarrif=$this->fetchOne("SELECT $tarrif_name FROM `call_country` where phonecode='$phone_code'",array());	
				
				$list_array=array("to"=>$to_no,"call_start"=>$call_start,"call_end"=>$call_end,"duration"=>$duration,"call_rate"=>$call_rate, "from_no"=>$from_no, "plan_name"=>$plan_name, "billing_country"=>$billing_country, "billing_code"=>$billing_code, "tarrif"=>$tarrif);
					$call_list_array[] = $list_array;
			}
			$paid_list_array = array('status' => 'true','call_det' => $call_list_array);
			print_r(json_encode($paid_list_array));exit;
		}
function gen_invoice($data){
	extract($data);
	 $prevmonth = date('Y-m-01', strtotime('-1 months'));
	 $prevEnd=date("Y-m-t", strtotime($prevmonth));
	 $id=$this->fetchOne("SELECT invoice_no FROM `invoice_det` where admin_id='$admin_id' and user_id='$user_id' and prevmonth='$prevmonth' and prevEnd='$prevEnd'",array());	
	if($id!=''){
		$host= $_SERVER['HTTP_HOST'];
		$result['data']='2';
		$result['url']="https://$host/api/storage/invoice/pdf/".$id.".pdf";
		return $result;
		}
	$get_ag_bill="Select id from agent_billing_det where user_id='$user_id'";
	$get_ag_bill=$this->fetchOne($get_ag_bill, array());
	if($get_ag_bill==''){
		$result['data']='3';
		return $result;
	}
	$get_admin_bill="Select id from admin_biller where admin_id='$admin_id'";
	$get_admin_bill=$this->fetchOne($get_admin_bill, array());
	if($get_admin_bill==''){
		$result['data']='4';
		return $result;
	}
	
	$get_call_plan="Select call_plan from user where user_id='$user_id'";
	$get_call_plan=$this->fetchOne($get_call_plan, array());
	if($get_call_plan=='0'){
		$result['data']='5';
		return $result;
	}
	//$sql="SELECT *,(SELECT sum(call_rate) FROM `call_details` where from_no=user.sip_login and admin_id=user.admin_id) as payamount FROM `user` INNER JOIN agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id where user.user_id='$user_id'";
	 $sql="SELECT user.agent_name, user.tax_name,user.tax_per,ag_det.ship_contact,ag_det.monthly_charges,ag_det.discount,ag_det.ship_to,ag_det.ship_add1,ag_det.ship_add2,ag_det.ship_city,ag_det.ship_state,ag_det.ship_zip,ag_det.ship_country,admin_biller.add1,admin_biller.add2,admin_biller.city,admin_biller.state,admin_biller.zip_code,admin_biller.country,admin_biller.phone,admin_biller.toll_free,admin_biller.reg_no,admin_biller.email,admin_biller.account_no,admin_biller.bank,admin_biller.branch,(SELECT sum(call_rate) FROM `call_details` where  from_no=user.sip_login and admin_id=user.admin_id  and date(dt_time)>='$prevmonth' and date(dt_time)<='$prevEnd') as payamount,admin_details.company_name,ag_det.admin_id,call_plans.currency,user.call_plan,call_plans.tarrif_name,ad.logo_image FROM `user`  INNER JOIN agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id INNER JOIN admin_details on admin_details.admin_id=ag_det.admin_id Inner join call_plans on call_plans.id=user.call_plan INNER JOIN user ad on ad.user_id=admin_details.admin_id where user.user_id='$user_id'";
	//echo $sql;exit;
	$result = $this->dataFetchAll($sql, array());
	if($result){
		
		$invoice=$this->fetchOne("SELECT invoice FROM `invoice_det` where admin_id='$admin_id' Order by id desc LIMIT 1",array());	
		if($invoice==''){
			$invoice='0';
		}
		$invoice=$invoice+1;
		foreach($result as $k=>$v){
		$cl_name= $v['agent_name'];
		$tax_name= $v['tax_name'];
		$tax_per= $v['tax_per'];
		$ship_contact= $v['ship_contact'];
		$ship_to= $v['ship_to'];
		$ship_add1= $v['ship_add1'];
		$ship_add2= $v['ship_add2'];
		$ship_city= $v['ship_city'];
		$ship_state= $v['ship_state'];
		$ship_zip= $v['ship_zip'];
		$ship_country= $v['ship_country'];
		$biller_add1= $v['add1'];
		$biller_add2= $v['add2'];
		$biller_city= $v['city'];
		$biller_state= $v['state'];
		$biller_zip_code= $v['zip_code'];
		$biller_country= $v['country'];
		$biller_phone= $v['phone'];
		$biller_toll_free= $v['toll_free'];
		$biller_reg_no= $v['reg_no'];
		$biller_logo_image= $v['logo_image'];
		$biller_email= $v['email'];
		$biller_account_no= $v['account_no'];
		$biller_bank= $v['bank'];
		$biller_branch= $v['branch'];
		$amt= $v['payamount'];
		$company_name= $v['company_name'];
		$admin_id= $v['admin_id'];
		$monthly_charges= $v['monthly_charges'];
		$discount_per= $v['discount'];
		$currency= $v['currency'];
		$tarrif_name=$v['tarrif_name'];
		$call_plan= $v['call_plan'];
		$sub= substr($company_name, 0, 3);
	    $ym=  date('Ym');
			
	    $btax=$monthly_charges+$amt;
			$dis_rate=$btax-round((($discount_per / 100) * $btax),2);
			
	    $tax_amt=round((($tax_per / 100) * $amt),2); 
			$tax_r=round((($tax_per / 100) * $dis_rate),2); 
			$tot_amt=round($dis_rate+$tax_r,2);
			
			//$tot_amt=$amt+$tax_amt;
	    $invoice_no=strtoupper($admin_id.$sub.$ym.$invoice);
			$insert_sql="INSERT INTO invoice_det(invoice_no,agent_name,tax_name,tax_per,ship_contact,ship_to,ship_add1,ship_add2,ship_city,ship_state,ship_zip,ship_country, biller_add1,biller_add2,biller_city,biller_state,biller_zip_code,biller_country,biller_phone,biller_toll_free,biller_reg_no,biller_logo_image,biller_email,biller_account_no,biller_bank,biller_branch,amt,company_name,prevmonth,prevEnd,admin_id,user_id,invoice,monthly_charges,discount,currency,tax_amt,tot_amt )  VALUES('$invoice_no','$cl_name','$tax_name','$tax_per','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$biller_add1','$biller_add2','$biller_city','$biller_state','$biller_zip_code','$biller_country','$biller_phone','$biller_toll_free','$biller_reg_no','$biller_logo_image','$biller_email','$biller_account_no','$biller_bank','$biller_branch','$amt','$company_name','$prevmonth','$prevEnd','$admin_id','$user_id','$invoice','$monthly_charges','$discount_per','$currency','$tax_r','$tot_amt')";
		//echo $insert_sql;exit;
			$result_fin= $this->db_insert($insert_sql, array());
			if($result_fin){
				$res=$this->dataFetchAll("SELECT * FROM `invoice_det` where id='$result_fin' ",array());	
				return $res;
			}
	}
	}
	
}
function send_mail($chat_data){
	
	extract($chat_data);
	  $sql="SELECT user.email_id,ad.email_id as ad_email,ad.user_name FROM `invoice_det` INNER JOIN user on user.user_id=invoice_det.user_id INNER JOIN user ad on ad.user_id=invoice_det.admin_id where invoice_no='$invoice_no'";
			$result = $this->fetchdata($sql, array());
	  $email=$result['email_id'];
	$ad_email=$result['ad_email'];
	$file= $_SERVER['DOCUMENT_ROOT'].'/api/storage/invoice/pdf/'.$invoice_no.'.pdf';
	 require_once('class.phpmailer.php'); 
     $customer_email = 'noreply@mconnectapps.com'; 
	$subject = "Invoice From Omni Channel";
	$from = 'Omni Channel';
	$body = "Invoice";                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtpcorp.com';
      $mail->Port = '2525';
      $mail->Username = 'erpdev2';
      $mail->Password = 'dnZ0ZjlyZ3RydzAw';
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($email);
	  $mail->AddCC($ad_email);
	  $mail->addAttachment($file);
      $mail->Send();
	  $status = array("status"=>true);
      $tarray = json_encode($status); 
      print_r($tarray);exit;
		}
		
	function view_admin_biller($chat_data){
		extract($chat_data);
		 $sel = "SELECT * FROM `admin_biller` where admin_id='$admin_id'";
	
	 $result = $this->dataFetchAll($sel, array());
		return $result;
		
	}
		function create_invoicegrp($chat_data){
			extract($chat_data);
			$insert_sql="INSERT INTO invoice_grp(user_id,admin_id,grp_name )  VALUES('$user_id','$admin_id','$grp_name')";
		//echo $insert_sql;exit;
			return $result_fin= $this->db_query($insert_sql, array());
		}
		function view_invoicegrp($chat_data){
			extract($chat_data);
			$sel = "SELECT * FROM `invoice_grp` where admin_id='$admin_id'";
			return $result = $this->dataFetchAll($sel, array());
		}
		function edit_invoicegrp($chat_data){
			extract($chat_data);
			$sel = "SELECT * FROM `invoice_grp` where admin_id='$admin_id' and id='$id'";
			return $result = $this->dataFetchAll($sel, array());
		}
		function update_invoicegrp($chat_data){
			extract($chat_data);
			 $qry = "Update invoice_grp SET user_id='$user_id',grp_name='$grp_name' where  id='$id' and admin_id='$admin_id'";
			return $result_fin= $this->db_query($qry, array());
		}
		function del_invoicegrp($chat_data){
			extract($chat_data);
			$qry = "DELETE From invoice_grp where  id='$id' and admin_id='$admin_id'";
			return $result_fin= $this->db_query($qry, array());
		}
		function ag_addlist($chat_data){
		extract($chat_data);
	     if($user_id=='1'){
				$admin_id=$user_id;
			}else{
				$admin_id=$admin_id;
			}
			$sel="SELECT user.user_id,user.agent_name FROM `agent_billing_det` INNER JOIN user on user.user_id=agent_billing_det.user_id where agent_billing_det.admin_id='$admin_id'";
	
	 $result = $this->dataFetchAll($sel, array());
		return $result;
		
	}
	function gen_invoicegrp($chat_data){
		extract($chat_data);
		 $sel = "SELECT user_id FROM `invoice_grp` where admin_id='$admin_id' and id='$inv_grp'";
		 $user_id = $this->fetchOne($sel, array());
		 $ex_id=explode(',',$user_id);		
			 $prevmonth = date('Y-m-01', strtotime('-1 months'));
	         $prevEnd=date("Y-m-t", strtotime($prevmonth));
		$out='';
		for($i='0'; $i<=count($ex_id); $i++ ){
			 $user_id=$ex_id[$i];
			 $id=$this->fetchOne("SELECT invoice_no FROM `invoice_det` where admin_id='$admin_id' and user_id='$user_id' and prevmonth='$prevmonth' and prevEnd='$prevEnd'",array());	
			if($id!=''){
		continue;
		}else{
			 $sql="SELECT user.agent_name, user.tax_name,user.tax_per,ag_det.ship_contact,ag_det.monthly_charges,ag_det.discount,ag_det.ship_to,ag_det.ship_add1,ag_det.ship_add2,ag_det.ship_city,ag_det.ship_state,ag_det.ship_zip,ag_det.ship_country,admin_biller.add1,admin_biller.add2,admin_biller.city,admin_biller.state,admin_biller.zip_code,admin_biller.country,admin_biller.phone,admin_biller.toll_free,admin_biller.reg_no,admin_biller.email,admin_biller.account_no,admin_biller.bank,admin_biller.branch,(SELECT sum(call_rate) FROM `call_details` where  from_no=user.sip_login and admin_id=user.admin_id  and date(dt_time)>='$prevmonth' and date(dt_time)<='$prevEnd') as payamount,admin_details.company_name,ag_det.admin_id,call_plans.currency,user.call_plan,call_plans.tarrif_name,ad.logo_image FROM `user`  INNER JOIN agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id INNER JOIN admin_details on admin_details.admin_id=ag_det.admin_id Inner join call_plans on call_plans.id=user.call_plan INNER JOIN user ad on ad.user_id=admin_details.admin_id where user.user_id='$user_id'";
	$result = $this->dataFetchAll($sql, array());
				
				if($result){
		
		$invoice=$this->fetchOne("SELECT invoice FROM `invoice_det` where admin_id='$admin_id' Order by id desc LIMIT 1",array());	
		if($invoice==''){
			$invoice='0';
		}
		$invoice=$invoice+1;
		foreach($result as $k=>$v){
		$cl_name= $v['agent_name'];
		$tax_name= $v['tax_name'];
		$tax_per= $v['tax_per'];
		$ship_contact= $v['ship_contact'];
		$ship_to= $v['ship_to'];
		$ship_add1= $v['ship_add1'];
		$ship_add2= $v['ship_add2'];
		$ship_city= $v['ship_city'];
		$ship_state= $v['ship_state'];
		$ship_zip= $v['ship_zip'];
		$ship_country= $v['ship_country'];
		$biller_add1= $v['add1'];
		$biller_add2= $v['add2'];
		$biller_city= $v['city'];
		$biller_state= $v['state'];
		$biller_zip_code= $v['zip_code'];
		$biller_country= $v['country'];
		$biller_phone= $v['phone'];
		$biller_toll_free= $v['toll_free'];
		$biller_reg_no= $v['reg_no'];
		$biller_logo_image= $v['logo_image'];
		$biller_email= $v['email'];
		$biller_account_no= $v['account_no'];
		$biller_bank= $v['bank'];
		$biller_branch= $v['branch'];
		$amt= $v['payamount'];
		$company_name= $v['company_name'];
		$admin_id= $v['admin_id'];
		$monthly_charges= $v['monthly_charges'];
		$discount_per= $v['discount'];
		$currency= $v['currency'];
		$tarrif_name=$v['tarrif_name'];
		$call_plan= $v['call_plan'];
		$sub= substr($company_name, 0, 3);
	    $ym=  date('Ym');
			
	    $btax=$monthly_charges+$amt;
		$dis_rate=$btax-round((($discount_per / 100) * $btax),2);
			
	    $tax_amt=round((($tax_per / 100) * $amt),2); 
			$tax_r=round((($tax_per / 100) * $dis_rate),2); 
			$tot_amt=round($dis_rate+$tax_r,2);
			
			//$tot_amt=$amt+$tax_amt;
	    $invoice_no=strtoupper($admin_id.$sub.$ym.$invoice);
			$insert_sql="INSERT INTO invoice_det(invoice_no,agent_name,tax_name,tax_per,ship_contact,ship_to,ship_add1,ship_add2,ship_city,ship_state,ship_zip,ship_country, biller_add1,biller_add2,biller_city,biller_state,biller_zip_code,biller_country,biller_phone,biller_toll_free,biller_reg_no,biller_logo_image,biller_email,biller_account_no,biller_bank,biller_branch,amt,company_name,prevmonth,prevEnd,admin_id,user_id,invoice,monthly_charges,discount,currency,tax_amt,tot_amt )  VALUES('$invoice_no','$cl_name','$tax_name','$tax_per','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$biller_add1','$biller_add2','$biller_city','$biller_state','$biller_zip_code','$biller_country','$biller_phone','$biller_toll_free','$biller_reg_no','$biller_logo_image','$biller_email','$biller_account_no','$biller_bank','$biller_branch','$amt','$company_name','$prevmonth','$prevEnd','$admin_id','$user_id','$invoice','$monthly_charges','$discount_per','$currency','$tax_r','$tot_amt')";
		//echo $insert_sql;exit;
			$result_fin= $this->db_insert($insert_sql, array());
		$out=$result_fin.','.$out;
	}
	}
				
			}
			
		}// for end
		$out= rtrim($out,',');
		if($out!=''){
			 $out="'" . str_replace(",", "','", $out) . "'";
			if($result_fin){
				$res=$this->dataFetchAll("SELECT * FROM `invoice_det` where id IN ($out)",array());	
				return $res;
			}
		}else{
			return 0;
		}
   
		
	}
		function old_invoice($chat_data){
			extract($chat_data);
			$sql= "SELECT invoice_no FROM `invoice_det` where user_id='$user_id' and admin_id='$admin_id'";
			return $res=$this->dataFetchAll($sql,array());	
		}

function gen_admin_invoice($data){
	extract($data);
	//print_r($data);exit;
	 $prevmonth = date('Y-m-01', strtotime('-1 months'));
	 $prevEnd=date("Y-m-t", strtotime($prevmonth));
	 $id=$this->fetchOne("SELECT invoice_no FROM `invoice_det` where admin_id='$admin_id' and user_id='$user_id' and prevmonth='$prevmonth' and prevEnd='$prevEnd'",array());	
	if($id!=''){
		$host= $_SERVER['HTTP_HOST'];
		$result['data']='2';
		$result['url']="https://$host/api/storage/invoice/pdf/".$id.".pdf";
		return $result;
		}
	$get_ag_bill="Select id from agent_billing_det where user_id='$user_id'";
	$get_ag_bill=$this->fetchOne($get_ag_bill, array());
	if($get_ag_bill==''){
		$result['data']='3';
		return $result;
	}
	$get_admin_bill="Select id from admin_biller where admin_id='$admin_id'";
	$get_admin_bill=$this->fetchOne($get_admin_bill, array());
	if($get_admin_bill==''){
		$result['data']='4';
		return $result;
	}
	
	$get_call_plan="Select call_plan from user where user_id='$user_id'";
	$get_call_plan=$this->fetchOne($get_call_plan, array());
	if($get_call_plan=='0'){
		$result['data']='5';
		return $result;
	}
	
	//$sql="SELECT *,(SELECT sum(call_rate) FROM `call_details` where from_no=user.sip_login and admin_id=user.admin_id) as payamount FROM `user` INNER JOIN agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id where user.user_id='$user_id'";
	
	
	$sql="SELECT user.agent_name, user.tax_name,user.tax_per,ag_det.ship_contact,ag_det.monthly_charges,ag_det.discount,ag_det.ship_to,ag_det.ship_add1,ag_det.ship_add2,ag_det.ship_city,ag_det.ship_state,ag_det.ship_zip,ag_det.ship_country,admin_biller.add1,admin_biller.add2,admin_biller.city,admin_biller.state,admin_biller.zip_code,admin_biller.country,admin_biller.phone,admin_biller.toll_free,admin_biller.reg_no,admin_biller.email,admin_biller.account_no,admin_biller.bank,admin_biller.branch,(SELECT sum(adcall_rate) FROM `call_details` where   admin_id=user.admin_id and date(dt_time)>='$prevmonth' and date(dt_time)<='$prevEnd'  ORDER BY id desc) as payamount,ad.company_name,ag_det.admin_id,call_plans.currency,user.call_plan,call_plans.tarrif_name,ad.logo_image  FROM user INNER JOIN 
agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id  Inner join call_plans on call_plans.id=user.call_plan  INNER JOIN user ad on ad.user_id=user.admin_id where user.user_id='$user_id'";
	//echo $sql;exit;
	$result = $this->dataFetchAll($sql, array());
	if($result){
		
		$invoice=$this->fetchOne("SELECT invoice FROM `invoice_det` where admin_id='$admin_id' Order by id desc LIMIT 1",array());	
		if($invoice==''){
			$invoice='0';
		}
		$invoice=$invoice+1;
		foreach($result as $k=>$v){
		$cl_name= $v['agent_name'];
		$tax_name= $v['tax_name'];
		$tax_per= $v['tax_per'];
		$ship_contact= $v['ship_contact'];
		$ship_to= $v['ship_to'];
		$ship_add1= $v['ship_add1'];
		$ship_add2= $v['ship_add2'];
		$ship_city= $v['ship_city'];
		$ship_state= $v['ship_state'];
		$ship_zip= $v['ship_zip'];
		$ship_country= $v['ship_country'];
		$biller_add1= $v['add1'];
		$biller_add2= $v['add2'];
		$biller_city= $v['city'];
		$biller_state= $v['state'];
		$biller_zip_code= $v['zip_code'];
		$biller_country= $v['country'];
		$biller_phone= $v['phone'];
		$biller_toll_free= $v['toll_free'];
		$biller_reg_no= $v['reg_no'];
		$biller_logo_image= $v['logo_image'];
		$biller_email= $v['email'];
		$biller_account_no= $v['account_no'];
		$biller_bank= $v['bank'];
		$biller_branch= $v['branch'];
		$amt= $v['payamount'];
		$company_name= $v['company_name'];
		$admin_id= $v['admin_id'];
		$monthly_charges= $v['monthly_charges'];
		$discount_per= $v['discount'];
		$currency= $v['currency'];
		$tarrif_name=$v['tarrif_name'];
		$call_plan= $v['call_plan'];
		$sub= substr($company_name, 0, 3);
	    $ym=  date('Ym');
			
	    $btax=$monthly_charges+$amt;		
		$dis_rate=$btax-round((($discount_per / 100) * $btax),2);
			
	    $tax_amt=round((($tax_per / 100) * $amt),2); 
			$tax_r=round((($tax_per / 100) * $dis_rate),2); 
			$tot_amt=round($dis_rate+$tax_r,2);
			
			//$tot_amt=$amt+$tax_amt;
	    $invoice_no=strtoupper($admin_id.$sub.$ym.$invoice);
			$insert_sql="INSERT INTO invoice_det(invoice_no,agent_name,tax_name,tax_per,ship_contact,ship_to,ship_add1,ship_add2,ship_city,ship_state,ship_zip,ship_country, biller_add1,biller_add2,biller_city,biller_state,biller_zip_code,biller_country,biller_phone,biller_toll_free,biller_reg_no,biller_logo_image,biller_email,biller_account_no,biller_bank,biller_branch,amt,company_name,prevmonth,prevEnd,admin_id,user_id,invoice,monthly_charges,discount,currency,tax_amt,tot_amt )  VALUES('$invoice_no','$cl_name','$tax_name','$tax_per','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$biller_add1','$biller_add2','$biller_city','$biller_state','$biller_zip_code','$biller_country','$biller_phone','$biller_toll_free','$biller_reg_no','$biller_logo_image','$biller_email','$biller_account_no','$biller_bank','$biller_branch','$amt','$company_name','$prevmonth','$prevEnd','$admin_id','$user_id','$invoice','$monthly_charges','$discount_per','$currency','$tax_r','$tot_amt')";
		//echo $insert_sql;exit;
			$result_fin= $this->db_insert($insert_sql, array());
			if($result_fin){
				$res=$this->dataFetchAll("SELECT * FROM `invoice_det` where id='$result_fin' ",array());	
				return $res;
			}
	}
	}
	
}
		
	function gen_admin_invoicegrp($chat_data){
		extract($chat_data);
		$sel = "SELECT user_id FROM `invoice_grp` where admin_id='$admin_id' and id='$inv_grp'";
		 $user_id = $this->fetchOne($sel, array());
		 $ex_id=explode(',',$user_id);		
			 $prevmonth = date('Y-m-01', strtotime('-1 months'));
	         $prevEnd=date("Y-m-t", strtotime($prevmonth));
		$out='';
		
		for($i='0'; $i<count($ex_id); $i++ ){
			//echo '123';exit;
			
			 $user_id=$ex_id[$i];
			 $id=$this->fetchOne("SELECT invoice_no FROM `invoice_det` where admin_id='$admin_id' and user_id='$user_id' and prevmonth='$prevmonth' and prevEnd='$prevEnd'",array());	
			if($id!=''){
		continue;
		}else{
			$sql="SELECT user.agent_name, user.tax_name,user.tax_per,ag_det.ship_contact,ag_det.monthly_charges,ag_det.discount,ag_det.ship_to,ag_det.ship_add1,ag_det.ship_add2,ag_det.ship_city,ag_det.ship_state,ag_det.ship_zip,ag_det.ship_country,admin_biller.add1,admin_biller.add2,admin_biller.city,admin_biller.state,admin_biller.zip_code,admin_biller.country,admin_biller.phone,admin_biller.toll_free,admin_biller.reg_no,admin_biller.email,admin_biller.account_no,admin_biller.bank,admin_biller.branch,(SELECT sum(adcall_rate) FROM `call_details` where   admin_id=user.admin_id and date(dt_time)>='$prevmonth' and date(dt_time)<='$prevEnd'  ORDER BY id desc) as payamount,ad.company_name,ag_det.admin_id,call_plans.currency,user.call_plan,call_plans.tarrif_name,ad.logo_image  FROM user INNER JOIN 
agent_billing_det ag_det on ag_det.user_id=user.user_id INNER JOIN admin_biller on admin_biller.admin_id=ag_det.admin_id  Inner join call_plans on call_plans.id=user.call_plan  INNER JOIN user ad on ad.user_id=user.admin_id where user.user_id='$user_id'";
				//echo $sql;exit;
	$result = $this->dataFetchAll($sql, array());
				
				if($result){
		
		$invoice=$this->fetchOne("SELECT invoice FROM `invoice_det` where admin_id='$admin_id' Order by id desc LIMIT 1",array());	
		if($invoice==''){
			$invoice='0';
		}
		$invoice=$invoice+1;
		foreach($result as $k=>$v){
		$cl_name= $v['agent_name'];
		$tax_name= $v['tax_name'];
		$tax_per= $v['tax_per'];
		$ship_contact= $v['ship_contact'];
		$ship_to= $v['ship_to'];
		$ship_add1= $v['ship_add1'];
		$ship_add2= $v['ship_add2'];
		$ship_city= $v['ship_city'];
		$ship_state= $v['ship_state'];
		$ship_zip= $v['ship_zip'];
		$ship_country= $v['ship_country'];
		$biller_add1= $v['add1'];
		$biller_add2= $v['add2'];
		$biller_city= $v['city'];
		$biller_state= $v['state'];
		$biller_zip_code= $v['zip_code'];
		$biller_country= $v['country'];
		$biller_phone= $v['phone'];
		$biller_toll_free= $v['toll_free'];
		$biller_reg_no= $v['reg_no'];
		$biller_logo_image= $v['logo_image'];
		$biller_email= $v['email'];
		$biller_account_no= $v['account_no'];
		$biller_bank= $v['bank'];
		$biller_branch= $v['branch'];
		$amt= $v['payamount'];
		$company_name= $v['company_name'];
		$admin_id= $v['admin_id'];
		$monthly_charges= $v['monthly_charges'];
		$discount_per= $v['discount'];
		$currency= $v['currency'];
		$tarrif_name=$v['tarrif_name'];
		$call_plan= $v['call_plan'];
		$sub= substr($company_name, 0, 3);
	    $ym=  date('Ym');
			
	    $btax=$monthly_charges+$amt;
		$dis_rate=$btax-round((($discount_per / 100) * $btax),2);
			
	    	    $tax_amt=round((($tax_per / 100) * $amt),2); 
			$tax_r=round((($tax_per / 100) * $dis_rate),2); 
			$tot_amt=round($dis_rate+$tax_r,2);
			
			//$tot_amt=$amt+$tax_amt;
	    $invoice_no=strtoupper($admin_id.$sub.$ym.$invoice);
			$insert_sql="INSERT INTO invoice_det(invoice_no,agent_name,tax_name,tax_per,ship_contact,ship_to,ship_add1,ship_add2,ship_city,ship_state,ship_zip,ship_country, biller_add1,biller_add2,biller_city,biller_state,biller_zip_code,biller_country,biller_phone,biller_toll_free,biller_reg_no,biller_logo_image,biller_email,biller_account_no,biller_bank,biller_branch,amt,company_name,prevmonth,prevEnd,admin_id,user_id,invoice,monthly_charges,discount,currency,tax_amt,tot_amt )  VALUES('$invoice_no','$cl_name','$tax_name','$tax_per','$ship_contact','$ship_to','$ship_add1','$ship_add2','$ship_city','$ship_state','$ship_zip','$ship_country','$biller_add1','$biller_add2','$biller_city','$biller_state','$biller_zip_code','$biller_country','$biller_phone','$biller_toll_free','$biller_reg_no','$biller_logo_image','$biller_email','$biller_account_no','$biller_bank','$biller_branch','$amt','$company_name','$prevmonth','$prevEnd','$admin_id','$user_id','$invoice','$monthly_charges','$discount_per','$currency','$tax_r','$tot_amt')";
		//echo $insert_sql;exit;
			$result_fin= $this->db_insert($insert_sql, array());
		$out=$result_fin.','.$out;
	}
	}
				
			}
			
		}// for end
		$out= rtrim($out,',');
		if($out!=''){
			 $out="'" . str_replace(",", "','", $out) . "'";
			if($result_fin){
				$res=$this->dataFetchAll("SELECT * FROM `invoice_det` where id IN ($out)",array());	
				return $res;
			}
		}else{
			return 0;
		}
   
		
	}		
	}
 