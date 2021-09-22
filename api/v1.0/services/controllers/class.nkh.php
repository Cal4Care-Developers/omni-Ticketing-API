 <?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
	class nkh extends restApi{
		function q_group($data){
			extract($data);
			$abn_sec= gmdate("H:i:s", $abn_sec);
			$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<!--<tr>
			<td colspan='22' align='center' bgcolor='#FFFFFF'><img src='$logo_name_path' width='167' height='75' ></td>
			</tr>-->
			<tr>
			<td colspan='17' align='center' style='align:center' bgcolor='#FFFFFF'><strong>Queue Group Performance by Day of Month</strong></td>
			</tr>
			<tr>
			<td colspan='17' align='left' style=' align:center' bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td>
			</tr>
			<tr>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Activity Period</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls Offered</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls handled</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls Abandoned</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Calls Abandoned (Long)</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Calls Abandoned (short)</strong></td>";
			$sql="SELECT Distinct(q_num),q_name from daily_foods_ag_q_details where admin_id='$admin_id' and q_num!='' group by q_num Order by q_num desc";
			$result= $this->dataFetchAll($sql, array());
			//$q_arr=array();
			//echo count($result);exit;
			$x='0';
			$sql="SELECT date,";
			foreach($result as $t=>$k){
				$x++;
				$q_name=$k['q_name'];
				$q_num=$k['q_num'];
				 $que_arr[]=$q_num;
				 $sql.="max(case when q_num = '$q_num' then ans end) '$q_num'";
				if($x!=count($result)){
					 $sql.=",";
				}
				$table_str.="<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Answered by $q_name</strong></td>";
			}
				$sql.="FROM `que_answered_view` group by date";
			//echo $sql;exit;
			
			$table_str.="<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg Speed of Answer</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg Delay of Abandoned</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Call Handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Abandoned %</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Answer %</strong></td>
			</tr>";
			//echo $qry="SELECT count(main.call_history_id) as calls_offered,date(main.time_of_status) as date,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time!='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as answered,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as abandoned,(SELECT sum(TIME_TO_SEC(call_waiting_time)) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as waiting FROM daily_foods_calls_view main where main.admin_id='$admin_id'  group by date(main.time_of_status)";exit;
			$qry="SELECT * FROM (SELECT count(main.call_history_id) as calls_offered,date(main.time_of_status) as date,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time!='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as answered,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as abandoned,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status) and call_waiting_time<='$abn_sec') as short,(SELECT COUNT(call_history_id) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status) and call_waiting_time>'$abn_sec') as lo,(SELECT sum(TIME_TO_SEC(call_waiting_time)) FROM `daily_foods_calls_view` where call_ans_time='' and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as waiting,(SELECT sum(ans_time) as ans_time FROM `que_answered_view` where date=date(main.time_of_status) and admin_id=main.admin_id) as talk FROM daily_foods_calls_view main where main.admin_id='$admin_id' and date(main.time_of_status)>='$from_dt'  and  date(main.time_of_status)<='$to_dt' group by date(main.time_of_status))t1 INNER JOIN ($sql) t2 on t1.date=t2.date";
			//echo $qry;exit;
			$res= $this->dataFetchAll($qry, array());
			$arr=array();
			foreach($res as $key=>$val){
				$calls_offered=$val['calls_offered'];
				$tot_calls=$tot_calls+$calls_offered;
				
				$answered=$val['answered'];
				$tot_ans=$tot_ans+$answered;
				
				$abandoned=$val['abandoned'];
				$tot_abn=$tot_abn+$abandoned;
				
				$waiting=$val['waiting'];
				$tot_wait=$tot_wait+$waiting;
				
				$short=$val['short'];
				$tot_short=$tot_short+$short;
				
			    $long=$val['lo'];
				$tot_long=$tot_long+$long;
				
				$talk=$val['talk'];
				$tot_talk=$tot_talk+$talk;
				
				$date=$val['date'];	
				
				$avg_talk=$talk/$answered;
				$avg=gmdate("H:i:s", $avg_talk);				
				$g_talk=$tot_talk/$tot_ans;
				$g_talk=gmdate("H:i:s", $g_talk);
				
				
				//echo $abandoned;exit;
				$avg_abn=$waiting/$abandoned;
				$av_abn=gmdate("H:i:s", $avg_abn);
				$g_abn=$tot_wait/$tot_abn;
				$g_abn=gmdate("H:i:s", $g_abn);
				
				$talk=gmdate("H:i:s", $talk);
				$abn_per=round(($abandoned/$calls_offered)*100,2);
				$g_per=round(($tot_abn/$tot_calls)*100,2);
				
				$ans_per=round(($answered/$calls_offered)*100,2);
				$gans_per=round(($tot_ans/$tot_calls)*100,2);
				
				$tot_talk=gmdate("H:i:s", $tot_talk);
				
				 $table_str1 .= "<tr role='row' class='odd' >
		<td colspan=1 bgcolor='#FFFFFF'>$date</td>
		<td colspan=1 bgcolor='#FFFFFF'>$calls_offered</td>
		<td colspan=1 style=' align:center' bgcolor='#FFFFFF'>$answered</td>
		<td colspan=1 bgcolor='#FFFFFF'>$abandoned</td>
		<td colspan=1 bgcolor='#FFFFFF'>$long</td>
		<td colspan=1 bgcolor='#FFFFFF'>$short</td>";
				
				foreach($que_arr as $que){
					$ans=$val[$que];
					if($ans==''){
						$ans='0';
						
					}
					$arr[$que]=$arr[$que]+$ans;
					 $table_str1 .= "<td colspan=1 bgcolor='#FFFFFF'>$ans</td>";
				}
				
			 $table_str1 .= "<td colspan=1 bgcolor='#FFFFFF'>$avg</td>
							 <td colspan=1 bgcolor='#FFFFFF'>$av_abn</td> 
							 <td colspan=1 bgcolor='#FFFFFF'>$talk</td> 
							 <td colspan=1 bgcolor='#FFFFFF'>$abn_per</td> 
							 <td colspan=1 bgcolor='#FFFFFF'>$ans_per</td>  ";
		
			
	
			}
			//print_r($arr);exit;
			$table_str1 .= "</tr><tr role='row' class='odd' >
			<td colspan=1 bgcolor='#A38F8B'>Total</td>
			<td colspan=1 bgcolor='#A38F8B'>$tot_calls</td>
			<td colspan=1 bgcolor='#A38F8B'>$tot_ans</td>
			<td colspan=1 bgcolor='#A38F8B'>$tot_abn</td>
							 <td colspan=1 bgcolor='#A38F8B'>$tot_long</td> 
							 <td colspan=1 bgcolor='#A38F8B'>$tot_short</td>";
				foreach($que_arr as $que){				
					
					 $table_str1 .= "<td colspan=1 bgcolor='#A38F8B'>$arr[$que]</td>";
				}
			 $table_str1 .= "<td colspan=1 bgcolor='#A38F8B'>$g_talk</td>
							 <td colspan=1 bgcolor='#A38F8B'>$g_abn</td> 
							 <td colspan=1 bgcolor='#A38F8B'>$tot_talk</td> 
							 <td colspan=1 bgcolor='#A38F8B'>$g_per</td> 
							 <td colspan=1 bgcolor='#A38F8B'>$gans_per</td>  ";
				 $tbl= $table_str.$table_str1;
			
			if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$tbl);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}else{
		$temporary_html_file = 'temp/'.time() . '.html';

 file_put_contents($temporary_html_file, $tbl);

 $reader = IOFactory::createReader('Html');

 $spreadsheet = $reader->load($temporary_html_file);	
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$original_report_name = $report_name.date('Ymdhis',time()).".Xlsx";
$attach_filename = $report_name.date('Ymdhis',time()).".Xlsx";
$filename = 'temp/'.$original_report_name;
unlink($temporary_html_file);
$writer->save($filename);}

			
		
				//echo $type;exit;
			$host= $_SERVER['HTTP_HOST'];
			$report_type_name = $type;
			$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://$host/api/v1.0/temp/$original_report_name','$type','$admin_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
			
	}
function ag_perform($data){
	extract($data);
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<!--<tr>
			<td colspan='22' align='center' bgcolor='#FFFFFF'><img src='$logo_name_path' width='167' height='75' ></td>
			</tr>-->
			<tr>
			<td colspan='17' align='center' style='align:center' bgcolor='#FFFFFF'><strong>Queue Group Performance by Day of Month</strong></td>
			</tr>
			<tr>
			<td colspan='17' align='left' style=' align:center' bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td>
			</tr>
			<tr>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Reporting</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Full Name</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls handled</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>NON ACD Calls handled</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Calls outbound</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Shift Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg Ringing</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Average ACD handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>NON ACD handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Average NON ACD handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Percent Shift</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Idle Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Percentage of shift for Idle</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Total Make Busy</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Percent of shift</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Total DND Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Percent of shift</strong></td>";
	
	/*$qry="SELECT DISTINCT(ag_num),CONCAT(ag_firstname,' ',ag_lastname) as name,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num!='' and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14')as acd,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id  and agnt_num=main.ag_num and q_num='' and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14')as non_acd,(SELECT COUNT(id) FROM `call_details` where from_no=main.ag_num  and admin_id=main.admin_id and date(dt_time)>='2020-11-13' and date(dt_time)<'2020-11-14' )as out_bound,(SELECT sum(diff) FROM login_view v where admin_id=main.admin_id and ag_num=main.ag_num and q_num=(SELECT q_num FROM login_view where date=v.date and admin_id=v.admin_id and ag_num=v.ag_num limit 1) and date>='2020-11-13' and date<'2020-11-14') as shift,(SELECT sum(time_to_sec(call_talk_time))FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num!='' and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14') as talk_acd,(SELECT sum(time_to_sec(call_talk_time))FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num=''  and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14') as talk_nacd,(SELECT sum(time_to_sec(call_waiting_time)) as ring FROM `daily_foods_calls` where admin_id=main.admin_id and agnt_num=main.ag_num  and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14' )as ring,(SELECT sum(time_to_sec(duration)) FROM `call_details` where from_no=main.ag_num  and admin_id=main.admin_id  and date(dt_time)>='2020-11-13' and date(dt_time)<'2020-11-14' )as out_dur,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where admin_id=main.admin_id and agnt_num=main.ag_num and call_ans_time='' and q_num!=''  and date(time_of_status)>='2020-11-13' and date(time_of_status)<'2020-11-14' )as missed,(SELECT sum(diff) FROM `busy_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='2020-11-13' and date<'2020-11-14') as busy,(SELECT sum(diff) FROM `idle_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='2020-11-13' and date<'2020-11-14') as idle,(SELECT sum(diff) FROM `dnd_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='2020-11-13' and date<'2020-11-14') as dnd   FROM daily_foods_ag_q_details main WHERE admin_id='835'
";*/
	$qry="SELECT DISTINCT(ag_num),CONCAT(ag_firstname,' ',ag_lastname) as name,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num!='' and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt')as acd,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id  and agnt_num=main.ag_num and q_num='' and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt')as non_acd,(SELECT COUNT(id) FROM `call_details` where from_no=main.ag_num  and admin_id=main.admin_id and date(dt_time)>='$from_dt' and date(dt_time)<'$to_dt' )as out_bound,(SELECT sum(diff) FROM login_view v where admin_id=main.admin_id and ag_num=main.ag_num and q_num=(SELECT q_num FROM login_view where date=v.date and admin_id=v.admin_id and ag_num=v.ag_num limit 1) and date>='$from_dt' and date<'$to_dt') as shift,(SELECT sum(time_to_sec(call_talk_time))FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num!='' and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt') as talk_acd,(SELECT sum(time_to_sec(call_talk_time))FROM `daily_foods_calls` where call_ans_time!='' and admin_id=main.admin_id and agnt_num=main.ag_num and q_num=''  and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt') as talk_nacd,(SELECT sum(time_to_sec(call_waiting_time)) as ring FROM `daily_foods_calls` where admin_id=main.admin_id and agnt_num=main.ag_num  and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt' )as ring,(SELECT sum(time_to_sec(duration)) FROM `call_details` where from_no=main.ag_num  and admin_id=main.admin_id  and date(dt_time)>='$from_dt' and date(dt_time)<'$to_dt' )as out_dur,(SELECT COUNT(call_history_id) FROM `daily_foods_calls` where admin_id=main.admin_id and agnt_num=main.ag_num and call_ans_time='' and q_num!=''  and date(time_of_status)>='$from_dt' and date(time_of_status)<'$to_dt' )as missed,(SELECT sum(diff) FROM `busy_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='$from_dt' and date<'$to_dt') as busy,(SELECT sum(diff) FROM `idle_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='$from_dt' and date<'$to_dt') as idle,(SELECT sum(diff) FROM `dnd_view` where admin_id=main.admin_id and ag_num=main.ag_num  and date>='$from_dt' and date<'$to_dt') as dnd   FROM daily_foods_ag_q_details main WHERE admin_id='$admin_id'";
			//echo $qry;exit;
			$res= $this->dataFetchAll($qry, array());
	print_r($res);exit;
	//echo $table_str;exit;
	
}
function agn_report($data){
	extract($data);
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<!--<tr>
			<td colspan='22' align='center' bgcolor='#FFFFFF'><img src='$logo_name_path' width='167' height='75' ></td>
			</tr>-->
			<tr>
			<td colspan='17' align='center' style='align:center' bgcolor='#FFFFFF'><strong>Agent Group Event by Period</strong></td>
			</tr>
			<tr>
			<td colspan='17' align='left' style=' align:center' bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td>
			</tr>
			<tr>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Reporting</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Full Name</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Shift Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Reason Code Name</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Code entry count</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Average Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Percent of shift</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Reason Type</strong></td>";
	
	//$sql="SELECT count(t1.interv) as count,sum(t1.interv) as interv,t1.status,t1.admin_id,t1.ag_num,date(t1.time_of_update) as date,CONCAT(t.ag_firstname,' ',t.ag_lastname) as name from (SELECT * FROM `daily_foods_user_status` where interv!='' GROUP by DATE_FORMAT(time_of_update, '%Y-%m-%d %H:%i'),admin_id,ag_num)t1 INNER JOIN daily_foods_ag_q_details t on t.ag_num=t1.ag_num and t.admin_id=t1.admin_id where  t1.admin_id='835'  group by t1.status,t1.admin_id,t1.ag_num order by t1.ag_num desc";
	
	/*$sql="SELECT *,sum(count_wrap) as cw,sum(interv_wrap) as iw FROM
(SELECT sum(t1.interv) as interv,t1.status,t1.admin_id,t1.ag_num,date(t1.time_of_update) as date,CONCAT(t.ag_firstname,' ',t.ag_lastname) as name from (SELECT * FROM `daily_foods_user_status` where interv!='' GROUP by DATE_FORMAT(time_of_update, '%Y-%m-%d %H:%i'),admin_id,ag_num)t1 INNER JOIN daily_foods_ag_q_details t on t.ag_num=t1.ag_num and t.admin_id=t1.admin_id where  t1.admin_id='$admin_id' and status=''   group by t1.status,t1.admin_id,t1.ag_num order by t1.ag_num)tab1

INNER JOIN (SELECT count(t1.interv) as count_wrap ,sum(t1.interv) as interv_wrap,t1.status as status_wrap,t1.admin_id as admin_id_wrap,t1.ag_num as ag_num_wrap,date(t1.time_of_update) as date_wrap,CONCAT(t.ag_firstname,' ',t.ag_lastname) as name_wrap from (SELECT * FROM `daily_foods_user_status` where interv!='' GROUP by DATE_FORMAT(time_of_update, '%Y-%m-%d %H:%i'),admin_id,ag_num)t1 INNER JOIN daily_foods_ag_q_details t on t.ag_num=t1.ag_num and t.admin_id=t1.admin_id where  t1.admin_id='$admin_id' and status!='idle' and status!='busy' and status!=''  group by t1.status,t1.admin_id,t1.ag_num  )tab2 ON

tab1.ag_num=tab2.ag_num_wrap and tab1.admin_id=tab2.admin_id_wrap and tab1.date=tab2.date_wrap  where tab1.date>='$from_dt' and tab1.date<='$to_dt' group by tab2.status_wrap,tab2.ag_num_wrap order by tab1.ag_num";*/
	//$sql="SELECT * from (SELECT sum(t1.interv) as interv,t1.status,t1.admin_id,t1.ag_num,date(t1.time_of_update) as date,CONCAT(t.ag_firstname,' ',t.ag_lastname) as name from (SELECT * FROM `daily_foods_user_status` where interv!='' GROUP by DATE_FORMAT(time_of_update, '%Y-%m-%d %H:%i'),admin_id,ag_num)t1 INNER JOIN daily_foods_ag_q_details t on t.ag_num=t1.ag_num and t.admin_id=t1.admin_id where  t1.admin_id='$admin_id' and status=''   group by t1.status,t1.admin_id,t1.ag_num order by t1.ag_num) tab1 INNER JOIN (SELECT count(t1.interv) as count_wrap ,sum(t1.interv) as interv_wrap,t1.status as status_wrap,t1.admin_id as admin_id_wrap,t1.ag_num as ag_num_wrap,date(t1.time_of_update) as date_wrap,CONCAT(t.ag_firstname,' ',t.ag_lastname) as name_wrap from (SELECT * FROM `daily_foods_user_status` where interv!='' GROUP by DATE_FORMAT(time_of_update, '%Y-%m-%d %H:%i'),admin_id,ag_num)t1 INNER JOIN daily_foods_ag_q_details t on t.ag_num=t1.ag_num and t.admin_id=t1.admin_id where  t1.admin_id='$admin_id' and status!='idle' and status!='busy' and status!=''  group by t1.status,t1.admin_id,t1.ag_num order by t1.ag_num)tab2 on tab1.ag_num=tab2.ag_num_wrap and tab1.admin_id=tab2.admin_id_wrap and tab1.date=tab2.date_wrap where date>='$from_dt' and date<='$to_dt' and date_wrap>='$from_dt' and date_wrap<='$to_dt'  order by tab1.ag_num";
	
	/*$sql="SELECT * from 
(SELECT sum(interv) as interv,status,admin_id,ag_num,date FROM `user_view` where status='' and admin_id='835' group by date,ag_num,admin_id,status) tab1

INNER JOIN

(SELECT count(interv) as count_wrap ,sum(interv) as interv_wrap,status as status_wrap,admin_id as admin_id_wrap,ag_num as ag_num_wrap,date as date_wrap FROM `user_view` where status!='' and admin_id='835' group by date,ag_num,admin_id,status) tab2 

on tab1.ag_num=tab2.ag_num_wrap and tab1.admin_id=tab2.admin_id_wrap and tab1.date=tab2.date_wrap where date>='2020-11-01' and date<='2020-11-17' and date_wrap>='2020-11-01' and date_wrap<='2020-11-17' order by tab1.ag_num";*/
	$sql="SELECT *,CONCAT(agq.ag_firstname,' ',agq.ag_lastname)as name from 
(SELECT sum(interv) as interv,status,admin_id,ag_num,date FROM `user_view` where status='' and admin_id='835' group by date,ag_num,admin_id,status) tab1

INNER JOIN

(SELECT count(interv) as count_wrap ,sum(interv) as interv_wrap,status as status_wrap,admin_id as admin_id_wrap,ag_num as ag_num_wrap,date as date_wrap FROM `user_view` where status!='' and status!='Available' and status!='busy' and admin_id='835' group by date,ag_num,admin_id,status) tab2 

on tab1.ag_num=tab2.ag_num_wrap and tab1.admin_id=tab2.admin_id_wrap and tab1.date=tab2.date_wrap INNER JOIN daily_foods_ag_q_details agq on agq.ag_num=tab1.ag_num and agq.admin_id=tab1.admin_id where date>='2020-11-01' and date<='2020-11-17' and date_wrap>='2020-11-01' and date_wrap<='2020-11-17' group by tab1.ag_num,tab2.status_wrap order by tab1.ag_num";
	
	//echo $sql;exit;
	$res= $this->dataFetchAll($sql, array());
	$arr=array();$tot_cw='0';$tot_iw='0';$tot_avg_iw='0';$tot_per='0';
	foreach($res as $key=>$val){
		$ag_no=$val['ag_num'];
		$name=$val['name'];
		$shift=$val['interv'];
		$status=$val['status_wrap'];
		$cw=$val['count_wrap'];
		$tot_cw=$tot_cw+$cw;
		$iw=$val['interv_wrap'];		
		 $tot_iw=$tot_iw+$iw;
		//$tot_iws=gmdate("H:i:s", $tot_iw);
		 $per=round(($iw/$shift)*100,2);		
		$avg_iw=($iw/$cw);	
		$tot_avg_iw=$tot_avg_iw+$avg_iw;
		$iw=gmdate("H:i:s", $iw);	
		$avg_iw=gmdate("H:i:s", $avg_iw);		
		$shift=gmdate("H:i:s", $shift);
		$tot_per=$tot_per+$per;
		
		if (in_array($ag_no, $arr))
			{
			$table_str .= "<tr role='row' class='odd'>
					<td bgcolor='#FFFFFF' colspan='1'></td>
					 <td bgcolor='#FFFFFF' colspan='1'></td>
					 <td bgcolor='#FFFFFF' colspan='1'></td>
					 <td bgcolor='#FFFFFF' colspan='1'>$status </td>
					 <td bgcolor='#FFFFFF' colspan='1'>$cw</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$iw</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$avg_iw</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>$per</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>Busy Code</td>
					 </tr>";
			
		}else{
				array_push($arr,$ag_no);
			 $table_str .= "<tr role='row' class='odd'>
					
					 <td bgcolor='#FFFFFF' colspan='1'>$ag_no</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$name</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$shift</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$status </td>
					 <td bgcolor='#FFFFFF' colspan='1'>$cw</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$iw</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$avg_iw</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>$per</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>Busy Code</td>
					 </tr>";
		}
		
	}
	//echo $tot_iw;
	//echo  date('H:i:s',$tot_iw);
	$secCount = $tot_iw;
$hours = str_pad(floor($secCount / (60*60)), 2, '0', STR_PAD_LEFT);
$minutes = str_pad(floor(($secCount - $hours*60*60)/60), 2, '0', STR_PAD_LEFT);
$seconds = str_pad(floor($secCount - ($hours*60*60 + $minutes*60)), 2, '0', STR_PAD_LEFT);
$tot_iws= $hours.':'.$minutes.':'.$seconds;
	
	
	$secCount = $tot_avg_iw;
$hours = str_pad(floor($secCount / (60*60)), 2, '0', STR_PAD_LEFT);
$minutes = str_pad(floor(($secCount - $hours*60*60)/60), 2, '0', STR_PAD_LEFT);
$seconds = str_pad(floor($secCount - ($hours*60*60 + $minutes*60)), 2, '0', STR_PAD_LEFT);
$tot_avg_iw= $hours.':'.$minutes.':'.$seconds;
	
	 $table_str .= "<tr role='row' class='odd'>
					
					 <td bgcolor='#A38F8B' colspan='1'></td>
					 <td bgcolor='#A38F8B' colspan='1'></td>
					 <td bgcolor='#A38F8B' colspan='1'></td>
					 <td bgcolor='#A38F8B' colspan='1'>Total </td>
					 <td bgcolor='#A38F8B' colspan='1'>$tot_cw</td>
					 <td bgcolor='#A38F8B' colspan='1'>$tot_iws</td>
					 <td bgcolor='#A38F8B' colspan='1'>$tot_avg_iw</td>	
					 <td bgcolor='#A38F8B' colspan='1'>$tot_per</td>	
					 <td bgcolor='#A38F8B' colspan='1'>Busy Code</td>
					 </tr>";
	
	//print_r($res);exit;
	 $tbl= $table_str;
	//echo $tbl;exit;
	if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$tbl);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}else{
		$temporary_html_file = 'temp/'.time() . '.html';

 file_put_contents($temporary_html_file, $tbl);

 $reader = IOFactory::createReader('Html');

 $spreadsheet = $reader->load($temporary_html_file);	
 $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$original_report_name = $report_name.date('Ymdhis',time()).".Xlsx";
$attach_filename = $report_name.date('Ymdhis',time()).".Xlsx";
$filename = 'temp/'.$original_report_name;
unlink($temporary_html_file);
$writer->save($filename);}

			
		
				//echo $type;exit;
	$host= $_SERVER['HTTP_HOST'];
			$report_type_name = $type;
			$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://$host/api/v1.0/temp/$original_report_name','$type','$admin_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
				}
function call_period($data){
    //echo '123';exit;
			extract($data);	
		//	echo $report_name;exit;
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor=''>
			<!--<tr>
			<td colspan='22' align='center' bgcolor='#FFFFFF'><img src='$logo_name_path' width='167' height='75' ></td>
			</tr>-->
			<tr>
			<td colspan='13' align='center' style='align:center' bgcolor='#FFFFFF'><strong>Queue Group Internal/External Call Counts by Period</strong></td>
			</tr>
			<tr>
			<td colspan='13' align='left' style=' align:center' bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td>
			</tr>
			<tr>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Activity Period</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls Offered</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls handled</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>ACD Calls Missed</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Internal Answered</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>External Answered</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Abandon Calls</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Internal Call Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Handling Time</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>AVG.Internal Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>AVG.External Duration</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>AVG.Speed Of answer</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Service Level %</strong></td>
			<td rowspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Answer %</strong></td>";
	
	$sql="SELECT main.round,COUNT(main.call_history_id) as offered,(SELECT COUNT(ans.call_history_id) as offered FROM interval_call_view ans where ans.q_num!='' and ans.call_ans_time!='' and ans.round=main.round and ans.admin_id=main.admin_id) as ans,(SELECT COUNT(missed.call_history_id) as offered FROM interval_call_view missed where missed.q_num!='' and missed.call_ans_time='' and missed.round=main.round and missed.admin_id=main.admin_id) as missed,(SELECT COUNT(in_ans.id) FROM call_det_view in_ans where in_ans.call_nature='Inbound Call' and in_ans.type='AnswerdCall' and in_ans.round=main.round  and in_ans.admin_id=main.admin_id ) as in_ans,(SELECT COUNT(ex_ans.id) FROM call_det_view ex_ans where ex_ans.call_nature='Outbound Call' and ex_ans.type='AnswerdCall' and ex_ans.round=main.round  and ex_ans.admin_id=main.admin_id ) as ex_ans,(SELECT COUNT(abn.id) FROM call_det_view abn where abn.call_nature='MissedCall' and abn.admin_id=main.admin_id and abn.round=main.round)as abn,(SELECT sum(TIME_TO_SEC(in_dur.duration)) FROM call_det_view in_dur where in_dur.call_nature='Inbound Call' and in_dur.type='AnswerdCall' and in_dur.admin_id=main.admin_id and in_dur.round=main.round) as in_dur,(SELECT MIN(mn_dur.duration) FROM call_det_view mn_dur where mn_dur.admin_id=main.admin_id and mn_dur.round=main.round) as mn_dur,(SELECT sum(TIME_TO_SEC(hand.duration)) FROM call_det_view hand where hand.admin_id=main.admin_id and hand.round=main.round) as hand,(SELECT count(call_history_id) FROM interval_call_view ans where ans.q_num!='' and ans.call_ans_time!='' and ans.round=main.round and ans.admin_id=main.admin_id and TIME_TO_SEC(call_talk_time)<'$sla') as sla,(SELECT sum(TIME_TO_SEC(ex_dur.duration)) FROM call_det_view ex_dur where ex_dur.call_nature='Outbound Call' and ex_dur.type='AnswerdCall' and ex_dur.admin_id=main.admin_id and ex_dur.round=main.round) as ex_dur FROM interval_call_view main where main.q_num!='' and date(main.round)>='$from_dt' and date(main.round)<='$to_dt' and main.admin_id='$admin_id' GROUP by main.round";
	//echo $ender;
//echo $sql;exit;

	
	
	$res= $this->dataFetchAll($sql, array());
	foreach($res as $key=>$val){
	    $offered=$val['offered'];
	    $missed=$val['missed'];
	    $ans=$val['ans'];
	    $in_ans=$val['in_ans'];
	    $ex_ans=$val['ex_ans'];
	    $start=$val['round'];
	    $abn=$val['abn'];
	    $in_dur=$val['in_dur'];
	    $hand=$val['hand'];
	    $ex_dur=$val['ex_dur'];
	    $mn_dur=$val['mn_dur'];
	    $sla=$val['sla'];
	      $mn_dur=	gmdate("H:i:s", $mn_dur);
	    
	    
	    if($in_dur!='' && $in_ans!='0'){
        	$avg_in_dur=round($in_ans/$in_dur, 2);
        $avg_in_dur=	gmdate("H:i:s", $avg_in_dur);
		}else{
		   $avg_in_dur='00:00:00'; 
		}
		
		if($ex_dur!='' && $ex_ans!='0'){
        	$avg_ex_dur=round($ex_ans/$ex_dur, 2);
        $avg_ex_dur=	gmdate("H:i:s", $avg_ex_dur);
		}else{
		   $avg_ex_dur='00:00:00'; 
		}
		
		if($offered!='' && $ans!='0'){
        	$ans_per=round($ans/$offered, 2)*100;
       
		}else{
		   $ans_per='0'; 
		}	
		if($offered!='' && $sla!='0'){
        	$sla_per=round($sla/$offered, 2)*100;
       
		}else{
		   $sla_per='0'; 
		}
		
	    	$in_dur=	gmdate("H:i:s", $in_dur);
	    	$hand=	gmdate("H:i:s", $hand);
	    $end = strtotime( $start.'+ 15 minute');
$end= date('Y-m-d H:i:s', $end);

	    $activity=$start.' - '.$end;
		 $table_str .= "<tr role='row' class='odd'>
					
					 <td bgcolor='#FFFFFF' colspan='1'>$start</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$offered</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$ans</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$missed </td>
					 <td bgcolor='#FFFFFF' colspan='1'>$in_ans</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$ex_ans</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$abn</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>$in_dur</td>	
					 <td bgcolor='#FFFFFF' colspan='1'>$hand</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$avg_in_dur</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$avg_ex_dur</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$mn_dur</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$sla_per</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$ans_per</td>
					 </tr>";
	}
//	echo $table_str;exit;
if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$table_str);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}else{
 $original_report_name = $report_name.date('Ymdhis',time()).".xls";
	$attach_filename="$report_name";
$file = 'temp/'.$original_report_name;

$fh = fopen($file,"w");
fwrite($fh,$table_str);
fclose($fh);

			}

			
		
				//echo $type;exit;
			$host= $_SERVER['HTTP_HOST'];
			$report_type_name = $type;
			 $insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','https://$host/api/v1.0/temp/$original_report_name','$type','$admin_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
		}
	
	}		



