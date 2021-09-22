 <?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
	class sg extends restApi{
		function ag_sum($data){	
			extract($data);
			$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<!--<tr>
			<td colspan='22' align='center' bgcolor='#FFFFFF'><img src='$logo_name_path' width='167' height='75' ></td>
			</tr>-->
			<tr>
			<td colspan='17' align='center' style='align:center' bgcolor='#FFFFFF'><strong> Agent - Performance Summary Reports </strong></td>
			</tr>
			<tr>
			<td colspan='17' align='left' style=' align:center' bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td>
			</tr>
			<tr>
			<td rowspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Start Date</strong></td>
			<td rowspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Agent</strong></td>
			<td colspan=6 bgcolor='#A38F8B' style=' align:center'><strong>All WGs and Direct calls (Inbound & Out Bound)</strong></td>
			<td rowspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Total Performing Time</strong></td>
			<td colspan=4  bgcolor='#A38F8B'style=' align:center'><strong>Non-Call Activities</strong></td> </tr>
			<tr>    
			<td colspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Answered</strong></td>
			<td colspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Wrap-Up</strong></td>
			<!--<td rowspan=2 bgcolor='#A38F8B' style=' align:center'><strong>Calls RNA</strong></td>-->
			<td colspan=3 bgcolor='#A38F8B' style=' align:center'><strong>Other Activities During Login</strong></td>
			</tr>
			<tr> 	
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Calls</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Duration</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg</strong></td>
			<!--<td  bgcolor='#A38F8B' style=' align:center'><strong>Calls</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Duration</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg</strong></td>-->
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Calls</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Duration</strong></td>
			<td  colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Avg</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Away</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>DND</strong></td>
			<td colspan=1 bgcolor='#A38F8B' style=' align:center'><strong>Lunch</strong></td>
			<!--<td bgcolor='#A38F8B' style='align:center'><strong>Meeting</strong></td>-->
			</tr>";
//$begin = new DateTime( $from_dt );
//$end   = new DateTime( $to_dt );

//for($i = $begin; $i <= $end; $i->modify('+1 day')){
   // $dt= $i->format("Y-m-d");
	//$from= $dt.' 00:00:00';
	//$to= $dt." 23:59:59";
	//$sql="SELECT DISTINCT(concat(agents.ag_firstname,' ',agents.ag_lastname)) as name,agents.ag_num,(SELECT COUNT(call_ans_time) as ans from daily_foods_calls where call_ans_time!='' and call_history_id!='' AND admin_id=agents.admin_id and agnt_num=agents.ag_num and time_of_status>='$from' and time_of_status<='$to') as answered,(SELECT SUM(TIME_TO_SEC(call_talk_time)) as ans from daily_foods_calls where call_ans_time!='' and call_history_id!='' AND admin_id=agents.admin_id and agnt_num=agents.ag_num and time_of_status>='$from' and time_of_status<='$to') as talk_time FROM daily_foods_ag_q_details agents where agents.admin_id='605'";
			
			
			$sql="SELECT COUNT(dfc.id) as ans,dfc.agnt_num,date(dfc.time_of_status) as date,SUM(TIME_TO_SEC(dfc.call_talk_time)) as talk,cONCAT(ag.ag_firstname,' ',ag.ag_lastname) as name,(SELECT diff FROM `c2_view` where admin_id=dfc.admin_id and ag_num=dfc.agnt_num and date=date(dfc.time_of_status) LIMIT 1)as wrap,(SELECT wrap_calls FROM `c2_view` where admin_id=dfc.admin_id and ag_num=dfc.agnt_num and date=date(dfc.time_of_status) LIMIT 1)as wrap_calls,(SELECT dur FROM `dnd_view` where admin_id=dfc.admin_id and ag_num=dfc.agnt_num and date=date(dfc.time_of_status) LIMIT 1) as dnd,(SELECT dur FROM `away_view` where admin_id=dfc.admin_id and ag_num=dfc.agnt_num and date=date(dfc.time_of_status) LIMIT 1) as away,(SELECT dur FROM `c1_view` where admin_id=dfc.admin_id and ag_num=dfc.agnt_num and date=date(dfc.time_of_status) LIMIT 1) as c1  FROM daily_foods_calls dfc INNER JOIN daily_foods_ag_q_details as ag on ag.admin_id=dfc.admin_id and dfc.agnt_num=ag.ag_num WHERE dfc.call_ans_time!='' and dfc.call_history_id!='' and dfc.admin_id='$admin_id' and date(dfc.time_of_status)>='$from_dt' and date(dfc.time_of_status)<='$to_dt' group by dfc.agnt_num,date(dfc.time_of_status) ORDER BY date(dfc.time_of_status) DESC";
			
	//echo $sql;exit;
	$result= $this->dataFetchAll($sql, array());
	foreach($result as $t=>$k){
		 $name=$k['name'];
		 $date=$k['date'];
		 $answered=$k['ans'];
		 $talk_time=$k['talk'];
		 $ag_num=$k['ag_num'];
		 $avg=$talk_time/$answered;
		$c2_diff=$k['wrap'];
		$wrap_dur=gmdate("H:i:s", $c2_diff);
		$away=$k['away'];
		$dnd=$k['dnd'];
		$lunch=$k['c1'];
		$wrap_count=$k['wrap_calls'];
		if($wrap_count==''){$wrap_count='0';$wrap_avg='00:00:00';}
		if($wrap_count!='0'){
		$c2_avg=$c2_diff/$wrap_count;
			$wrap_avg=gmdate("H:i:s", $c2_avg);
		}
		if($lunch==''){
		$lunch="00:00:00";
		}
		if($dnd==''){
		$dnd="00:00:00";
		}
		if($away==''){
		$away="00:00:00";
		}
		if($wrap_dur==''){
		$wrap_dur="00:00:00";
		}
		if($talk_time<0){
			$talk_time=abs($talk_time);
			$avg=abs($avg);
			$talk_time='-'.gmdate("H:i:s", $talk_time);
			$avg='-'.gmdate("H:i:s", $avg);
		}else{
			$talk_time=gmdate("H:i:s", $talk_time);
			$avg=gmdate("H:i:s", $avg);
		}
		if($avg==''){
			$avg='00:00:00';
		}
		$perform_time= $this->CalculateTime($talk_time,$wrap_dur);
		$sql1="";	
	    $table_str .= "<tr role='row' class='odd' >
		<td colspan=1 bgcolor='#FFFFFF'>$date</td>
		<td colspan=1 bgcolor='#FFFFFF'>$name</td>
		<td colspan=1 style=' align:center' bgcolor='#FFFFFF'>$answered</td>
		<td colspan=1 bgcolor='#FFFFFF'>$talk_time</td>
		<td colspan=1 bgcolor='#FFFFFF'>$avg</td>
		<!--<td bgcolor='#FFFFFF'>0</td>
		<td colspan=1 bgcolor='#FFFFFF'>00:00:00</td>
		<td colspan=1 bgcolor='#FFFFFF'>00:00:00</td>-->
		<td colspan=1 style=' align:center' bgcolor='#FFFFFF'>$wrap_count</td>
		<td colspan=1 bgcolor='#FFFFFF'>$wrap_dur</td>
		<td colspan=1 bgcolor='#FFFFFF'>$wrap_avg</td>
		<td colspan=1 bgcolor='#FFFFFF'>$perform_time</td>
		<td colspan=1 bgcolor='#FFFFFF'>$away</td>
		<td colspan=1 bgcolor='#FFFFFF'>$dnd </td>
		<td colspan=1 bgcolor='#FFFFFF'>$lunch</td>
		<!-- <td bgcolor='#FFFFFF'>$meeting</td>-->
		</tr>";
	}
		//	echo $table_str;exit;
$report_type_name='Agent - Performance Summary Reports';
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
		
	function work_rep($data){
		extract($data);
		//echo $q_num;exit;
		$sql="SELECT date(time_of_status) as date ,COUNT(call_history_id) as calls_offered,(SELECT COUNT(call_history_id) FROM daily_foods_calls_view where call_ans_time!='' and q_num=main.q_num and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) as ans,(SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( call_talk_time ) ) )  FROM daily_foods_calls_view where call_ans_time!=''  and q_num=main.q_num and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) AS timeSum,(SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( call_waiting_time ) ) )  FROM daily_foods_calls_view where call_ans_time!='' and q_num=main.q_num and admin_id=main.admin_id and date(time_of_status)=date(main.time_of_status)) AS ring,(SELECT sum(wrap_calls)FROM `c2_view` where q_num=main.q_num and admin_id=main.admin_id and date=date(main.time_of_status))  as wrap_calls,(SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( dur ) ) ) FROM `c2_view` where q_num=main.q_num and admin_id=main.admin_id and date=date(main.time_of_status))  AS wrap_duration,main.q_num,daily_foods_ag_q_details.q_name FROM daily_foods_calls_view main INNER JOIN daily_foods_ag_q_details on daily_foods_ag_q_details.q_num=main.q_num and daily_foods_ag_q_details.admin_id=main.admin_id where main.admin_id='$admin_id' and date(main.time_of_status)>='$from_dt' and date(main.time_of_status)<='$to_dt'";
		if($q_num!=''){
			   $q_num="'" . str_replace(",", "','", $q_num) . "'";
			$sql.=" and main.q_num IN($q_num)";
		}
		$sql.="    GROUP by date(time_of_status),main.q_num ORDER by main.q_num desc";
		//echo $sql;exit;
		$result= $this->dataFetchAll($sql, array());
		if($result!=''){
		$arr=array();
		$table_str="<table cellpadding='3' cellspacing='1' border='1px solid black' bgcolor='#FFFFFF'>
		<tr>
		<td colspan='12' align='center' style='' bgcolor='#FFFFFF'><strong>$report_title_name</strong></td>
		</tr> <tr>
		<td colspan='12' align='left'  style=''bgcolor='#FFFFFF'><strong>Range:</strong>$from_dt - $to_dt</td> </tr>";
			
		 $grand_ans='00:00:00';$grand_avg_talk='00:00:00';$grand_ring='00:00:00';$grand_avg_ring='00:00:00';
         $grand_wrap_calls='0';$grand_total='00:00:00';$grand_wrap_avg='00:00:00';$grand_calls_offered='0';$grand_calls='0';
		$i='0';
		foreach($result as $t=>$k){		
			
			$i++;
			$queue_no= $k['q_num'];
			$d1= $k['date'];
			$name= $k['q_name'];
			 $calls_offered= $k['calls_offered'];
			//echo '</br>';
			$calls= $k['ans'];
			$talk= $k['timeSum'];
			$ring= $k['ring'];
			$wrap_calls= $k['wrap_calls'];
			$total= $k['wrap_duration'];
			$rna=$calls_offered-$calls;		
			
			
			$avg_talk= $this->sec_time($talk,$calls);
			$avg_ring= $this->sec_time($ring,$calls);
			$wrap_avg= $this->sec_time($total,$wrap_calls);
			if($wrap_calls==''){
				$wrap_calls='0';
				$total='00:00:00';
				$wrap_avg='00:00:00';
			}
			if (in_array($queue_no, $arr))
			{
				 $table_str .= "<tr role='row' class='odd'>
				 <td bgcolor='#FFFFFF' colspan='1' >$d1</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$name</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$calls_offered</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$rna</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$calls</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$talk</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$avg_talk</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$ring</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >$avg_ring</td>	
				 <!--<td bgcolor='#FFFFFF'>0</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>	
				 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>		-->		
				 <td bgcolor='#FFFFFF' colspan='1' >$wrap_calls</td>	
				 <td bgcolor='#FFFFFF' colspan='1' >$total</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >$wrap_avg</td>	
				 </tr>";
			}
			else
			{
				array_push($arr,$queue_no);
				
				if($i!='1'){
					 $table_str .= "<tr role='row' class='odd'>
					 <td colspan='2' bgcolor='#FFFFFF' colspan='1'><strong>Sub Total</strong></td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_calls_offered</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_rna</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_calls</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_ans</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_avg_talk</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_ring</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_avg_ring</td>	
					 <!--<td bgcolor='#FFFFFF' colspan='1'>0</td>
					 <td bgcolor='#FFFFFF' colspan='1'>00:00:00</td>
					 <td bgcolor='#FFFFFF' colspan='1'>00:00:00</td>-->	
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_wrap_calls</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_total</td>
					 <td bgcolor='#FFFFFF' colspan='1'>$tot_wrap_avg</td>
					 </tr><tr><td colspan='15'  bgcolor='#FFFFFF'>&nbsp;</td>
					 </tr><tr><td colspan='15'  bgcolor=''>&nbsp;</td>
					 </tr>";
				}
				 $table_str .= "<tr>
				 <td colspan='12' align='left'  style='bgcolor='#FFFFFF'><strong>$name</strong></td>
				 </tr> <tr>
				 <td rowspan='2' bgcolor='#A38F8B' style=''><strong>Start Date</strong></td>
				 <td rowspan='2' bgcolor='#A38F8B' style=''><strong>Workgroup</strong></td>
				 <td rowspan='2' bgcolor='#A38F8B' style=''><strong>Calls Offered</strong></td>
				 <td rowspan='2' bgcolor='#A38F8B' style=''><strong>RNA</strong></td> 
				 <td colspan='5' bgcolor='#A38F8B' style=''><strong>Answered</strong></td>
				 <!--<td colspan='3' bgcolor='#A38F8B' style=''><strong>Hold</strong></td>-->
				 <td colspan='3' bgcolor='#A38F8B' style=''><strong>Wrap-Up</strong></td>
				 </tr><tr>
				 <td bgcolor='#A38F8B' colspan='1' style=''><strong>Calls</strong></td>
				 <td bgcolor='#A38F8B' colspan='1' style=''><strong>Talk</strong></td>
				 <td bgcolor='#A38F8B' colspan='1' style=''><strong>Avg Talk</strong></td>
				 <td bgcolor='#A38F8B' colspan='1' style=''><strong>Ring</strong></td>
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>Avg Ring</strong></td>
				 <!--<td bgcolor='#A38F8B' style=''><strong>Calls</strong></td>
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>Total</strong></td>
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>AVG</strong></td>-->
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>Calls</strong></td>
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>Total</strong></td>
				 <td bgcolor='#A38F8B' colspan='1'  style=''><strong>AVG</strong></td>
				 </tr>";
				 $table_str .= "<tr role='row' class='odd'>
				 <td bgcolor='#FFFFFF' colspan='1' >$d1</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$name</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$calls_offered</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$rna</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$calls</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$talk</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$avg_talk</td>
				 <td bgcolor='#FFFFFF' colspan='1' >$ring</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >$avg_ring</td>	
				 <!--<td bgcolor='#FFFFFF'>0</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>	
				 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>		-->		
				 <td bgcolor='#FFFFFF' colspan='1' >$wrap_calls</td>	
				 <td bgcolor='#FFFFFF' colspan='1' >$total</td>		
				 <td bgcolor='#FFFFFF' colspan='1' >$wrap_avg</td>	
				 </tr>";
				
				 $tot_ans='00:00:00';$tot_avg_talk='00:00:00';$tot_ring='00:00:00';$tot_avg_ring='00:00:00';
         $tot_wrap_calls='0';$tot_total='00:00:00';$tot_wrap_avg='00:00:00';$tot_calls_offered='0';$tot_calls='0';
			}
			
			$tot_calls_offered+=$calls_offered;
			$tot_calls+=$calls;
			$tot_wrap_calls+=$wrap_calls;
			$tot_ans=$this->CalculateTime($tot_ans,$talk);
			$tot_avg_talk=$this->CalculateTime($tot_avg_talk,$avg_talk);
			$tot_ring=$this->CalculateTime($tot_ring,$ring);
			$tot_avg_ring=$this->CalculateTime($tot_avg_ring,$total);
			$tot_total=$this->CalculateTime($tot_total,$avg_ring);
			$tot_wrap_avg=$this->CalculateTime($tot_wrap_avg,$wrap_avg);
			$tot_rna=$tot_calls_offered-$tot_calls;
			
			
			$grand_calls_offered+=$calls_offered;
			$grand_calls+=$calls;
			$grand_wrap_calls+=$wrap_calls;
			$grand_ans=$this->CalculateTime($grand_ans,$talk);
			$grand_avg_talk=$this->CalculateTime($grand_avg_talk,$avg_talk);
			$grand_ring=$this->CalculateTime($grand_ring,$ring);
			$grand_avg_ring=$this->CalculateTime($grand_avg_ring,$total);
			$grand_total=$this->CalculateTime($grand_total,$avg_ring);
			$grand_wrap_avg=$this->CalculateTime($grand_wrap_avg,$wrap_avg);
			$rna_tot=$grand_calls_offered-$grand_calls;
		}
		
		 $table_str .= "<tr role='row' class='odd'>
					 <td colspan='2' bgcolor='#FFFFFF'><strong>Sub Total</strong></td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_calls_offered</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_rna</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_calls</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_ans</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_avg_talk</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_ring</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_avg_ring</td>	
					 <!--<td bgcolor='#FFFFFF'>0</td>
					 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>
					 <td bgcolor='#FFFFFF' colspan='1' >00:00:00</td>-->	
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_wrap_calls</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_total</td>
					 <td bgcolor='#FFFFFF' colspan='1' >$tot_wrap_avg</td>
					 </tr><tr><td colspan='15'  bgcolor='#FFFFFF'>&nbsp;</td>
					 </tr><tr><td colspan='15'  bgcolor=''>&nbsp;</td>
					 </tr>";
			$table_str .= "</table>
			<table cellpadding='3' style='margin-top: 2%;' cellspacing='1' border='1px solid black' bgcolor='#999999'><tr>
			<td colspan='13' align='left'  style='border: 1px solid; bgcolor='#FFFFFF'><strong>Range:</strong>Grand Total</td>
			</tr><tr>
			<td rowspan='2' bgcolor='#A38F8B' style=''><strong>Calls Offered</strong></td>
			<td rowspan='2' bgcolor='#A38F8B' style=''><strong>RNA</strong></td> 
			<td colspan='5' bgcolor='#A38F8B' style=''><strong>Answered</strong></td> 
			<td colspan='3' bgcolor='#A38F8B' style=''><strong>Wrap-Up</strong></td>
			</tr><tr>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Calls</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Talk</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Avg Talk</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Ring</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Avg Ring</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Calls</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>Total</strong></td>
			<td bgcolor='#A38F8B' colspan='1' style=''><strong>AVG</strong></td>
			</tr>
			<tr role='row' class='odd'>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_calls_offered</td>
			<td bgcolor='#FFFFFF' colspan='1' >$rna_tot</td>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_calls</td>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_ans</td>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_avg_talk</td>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_ring</td>		
			<td bgcolor='#FFFFFF' colspan='1' >$grand_avg_ring</td>	
			<td bgcolor='#FFFFFF' colspan='1' >$grand_wrap_calls</td>
			<td bgcolor='#FFFFFF' colspan='1' >$grand_total</td>		
			<td bgcolor='#FFFFFF' colspan='1' >$grand_wrap_avg</td>	
			</tr>
			<tr><td colspan='13'  bgcolor='#FFFFFF'>&nbsp;</td>
			</tr>";
		//echo $grand;exit;
			//echo $table_str;exit;
		
$report_type_name='Workgroup Call Report';
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
	}
	}		



