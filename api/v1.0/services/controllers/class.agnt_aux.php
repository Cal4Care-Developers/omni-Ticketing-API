<?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
class agnt_aux extends restApi{
    function agnt_aux_rep($data){
		//print_r($data);exit;
        extract($data);
		$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<tr>
			<td colspan='8' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
			</tr><tr>
			<td colspan='8' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr> <tr>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Date Range</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent Name</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Login ID</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Staffed Time</strong></td> 
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Time in Default</strong></td>";
		
			$select_aux_code="SELECT auxcode_name from auxcode where delete_status='0' and admin_id='$admin_id'";
		$result = $this->dataFetchAll($select_aux_code, array());
		foreach($result as $t=>$k){
			$auxcode_name= $k['auxcode_name'];
			$table_str.="<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Time in $auxcode_name</strong></td>";
		}
			$table_str.="<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>AUX Time</strong></td></tr>";
		
		 $select="SELECT DISTINCT(user.user_id),IF(user.admin_id = 1, user.user_id , user.admin_id ) as admin_id,user.agent_name,date(ld.time_stamp) as date_range,(select SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(end_time, time_stamp)))) from log_details where type='Login' and end_time!='0000-00-00 00:00:00' AND agent_id=user.user_id and DATE(time_stamp) = DATE(ld.time_stamp)) as staff FROM log_details ld INNER JOIN user on user.user_id=ld.agent_id where date(ld.time_stamp)>='$from_dt' and date(ld.time_stamp)<='$to_dt'";
		
		$result_dat = $this->dataFetchAll($select, array());
		foreach($result_dat as $key=>$value){
			$date_range= $value['date_range'];			
			$agent_name= $value['agent_name'];		
   		    $user_id= $value['user_id'];	
		    $staff= $value['staff'];
			$table_str.="<tr>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid black'>$date_range</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$agent_name</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'> $user_id</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'> $staff</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'></td>
			";
		    $tot_handle_dur='00:00:00';	
				foreach($result as $t=>$k){
			 	
					$aux_name= $k['auxcode_name'];
						$sel_time="SELECT coalesce((SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(end_time, time_stamp)))) as time FROM log_details,user where user.user_id=log_details.agent_id and log_details.reason='$aux_name' and log_details.end_time!='0000-00-00 00:00:00' and date(time_stamp)='$date_range' and IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and log_details.agent_id='$user_id'),'00:00:00')as time
";
			$result_time = $this->dataFetchAll($sel_time, array());
			
		foreach($result_time as $key_tim=>$value_tim){
			  $time=$value_tim['time'];
			$table_str.="<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$time</td>";
			  $tot_handle_dur=interval_sum($tot_handle_dur,$time);
		}				
				}
			$table_str.="<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$tot_handle_dur</td></tr>";
			//echo $tot_handle_dur.'</br>';
			
		}
		//echo $table_str;exit;
		if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$table_str);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}else{
/*$original_report_name = $report_name.date('Ymdhis',time()).".xls";
$file = 'temp/'.$original_report_name;
$fh = fopen($file,"w");
fwrite($fh,$table_str);
fclose($fh);
			$attach_filename="$report_name".".xls";*/
$temporary_html_file = 'temp/'.time() . '.html';

 file_put_contents($temporary_html_file, $table_str);

 $reader = IOFactory::createReader('Html');

 $spreadsheet = $reader->load($temporary_html_file);	
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$original_report_name = $report_name.date('Ymdhis',time()).".xlsx";
$filename = 'temp/'.$original_report_name;
unlink($temporary_html_file);
$writer->save($filename);
		}

			
		
				$host= $_SERVER['HTTP_HOST'];
			$report_type_name = $type;
			$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://$host/api/v1.0/temp/$original_report_name','$type','$admin_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
		
	}
			
    function report_list($data){
        extract($data);
        $search_qry = "";
        if($search_text != ""){
            $search_qry = " where (report_name like '%".$search_text."%' or original_name like '%".$search_text."%' or report_type_name like '%".$search_text."%')";
        }
        $qry="SELECT * from report_details".$search_qry;
        $detail_qry = $qry." order by report_details_id desc limit ".$limit." Offset ".$offset;
        //echo $detail_qry;exit;

        $parms = array();
        $result["list_data"] = $this->dataFetchAll($detail_qry,array());
        $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
        $result["list_info"]["offset"] = $offset;
        return $result;
    }
}

function interval_sum($from,$to){

    $interval = $from;
     $in1 = $to;
//echo '</br>';
    if (strpos($interval, 'day') !== false) {
        $exp_comma = explode('day', $interval);
        $interval = $exp_comma[1];
        $d1 = $exp_comma[0];
    } else {
        $d1 = '0';
    }
    if (strpos($in1, 'day') !== false) {
        $exp_comma = explode(',', $in1);
        $in1 = $exp_comma[1];
        $d2 = $exp_comma[0];
    } else {
        $d2 = '0';
    }


    $explode = explode(':', $interval);
    $hr1 = $explode[0];
    $min1 = $explode[1];
    $s1 = $explode[2];


    $explode2 = explode(':', $in1);
    $hr2 = $explode2[0];
    $min2 = $explode2[1];
    $s2 = $explode2[2];


    $sec = $s1 + $s2;
    $min = $min1 + $min2;
    $hr = $hr1 + $hr2;
    $d = $d1 + $d2;

    if ($sec > '60') {
        $sec = $sec - '60';
        $min = $min + '1';

    }
    if ($min > '60') {
        $min = $min - '60';
        $hr = $hr + 1;
    }
    if ($hr > 24) {
        $hr = $hr - 24;
        $d = $d + 1;
    }
    $hour = sprintf('%02d', $hr);
    $minute = sprintf('%02d', $min);
    $seconds = sprintf('%02d', $sec);
    //echo $seconds.'</br>';
    if ($d == '0') {
        return  $intr = $hour . ':' . $minute . ':' . $seconds;
    } else {
        return  $intr = $d . ' day  ' . $hour . ':' . $minute . ':' . $seconds;
    }
}
