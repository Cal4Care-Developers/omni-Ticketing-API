<?php
	class report extends restApi{
		function gen_log_report($data){		
			extract($data);
			$host= $_SERVER['HTTP_HOST'];
			if($type=='Agent Login Summary'){
			$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<tr>
			<td colspan='8' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
			</tr><tr>
			<td colspan='8' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr> <tr>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent id</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent Name</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Logout Date</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Logout Time</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Login Date</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Login Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total login</strong></td> 
			</tr>";
			$select="SELECT dfus.ag_num as agent_id,name,date(time_of_update) as logout_date,time_of_update as logout_time,date(time_of_endstatus) as login_date,time_of_endstatus as login_time, SEC_TO_TIME(TIME_TO_SEC(timediff(time_of_endstatus, time_of_update))) AS log_dur FROM daily_foods_user_status dfus INNER JOIN (SELECT DISTINCT(ag_num),concat(ag_firstname,' ',ag_lastname) as name FROM daily_foods_ag_q_details)t1 on t1.ag_num=dfus.ag_num where status='' and time_of_update>='$from_dt' and time_of_update<='$to_dt' and admin_id='$admin_id' ORDER by dfus.ag_num,time_of_update desc";

    $result = $this->dataFetchAll($select, array());
			
foreach($result as $t=>$k){
	
		 $ag_num=$k['agent_id'];		       
        $display_name = $k['name'];
        $logout_date = $k['logout_date'];
        $logout_time = $k['logout_time'];
        $log_out_time=date("H:i:s",strtotime($logout_time));
        $login_date = $k['login_date'];
	if($login_date!=''){
        $login_time = $k['login_time'];
        $log_time=date("H:i:s",strtotime($login_time));}
	else{
		$log_time='';
	}
        $log_dur = $k['log_dur'];


        $table_str.="<tr role='row' class='odd' id=''>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$ag_num</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$display_name</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$logout_date</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$log_out_time</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$login_date</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$log_time</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$log_dur</td>
            </tr>";
    }}
elseif($type=='Overall Service Level'){
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<tr>
			<td colspan='25' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
			</tr> <tr> 
			<td colspan='25' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr>  <tr>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Date</strong></td>	
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Hour</strong></td> 	
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Half Hour</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Interval</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Queue</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Offered Calls</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Handled </strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls handled after threshold</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls handled within threshold</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned after threshold</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned Within threshold</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Handling Time ( AHT )</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Service level</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Abandoning Delay</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Speed Of Answer</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Max Delay</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Held Contacts (%)</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Ring Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Talk Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Hold Time</strong></td> 
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Wrapup Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Idle Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Not Ready Time</strong></td>
			<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Actual Hours</strong></td>
			</tr>";
			 $select="select DISTINCT(date(call_start_time)) as date from daily_foods_calls where date(call_start_time)>='$from_dt' and date(call_start_time)<='$to_dt' and admin_id='64'";
	 $result = $this->dataFetchAll($select, array());			
foreach($result as $t=>$k){
	$current_date_val= $k['date'];
        $starttime = $current_date_val.' 00:00:00';  // your start time
        $endtime = $current_date_val.' 23:59:59';  // End time
        $duration = '15';  // split by 30 mins

        $array_of_time = array ();
        $start_time    = strtotime ($starttime); //change to strtotime
        $end_time      = strtotime ($endtime); //change to strtotime

        $add_mins  = $duration * 60;

        while ($start_time <= $end_time) // loop between time
        {
            $array_of_time = date ("H:i:s", $start_time);

            $st = strtotime("+00 minutes", strtotime($array_of_time));
            $start = $current_date_val . ' ' . date('H:i:s', $st);

            $et = strtotime("+15 minutes", strtotime($array_of_time));
			if(date('H:i:s', $et)=='00:00:00'){
				  $current_date_val = date('Y-m-d', strtotime($current_date_val . ' +1 days'));
			}
            $end = $current_date_val . ' ' . date('H:i:s', $et);
//            echo date('H:i:s', $st).'  '.date('H:i:s', $et).'</br>';
            $start_time += $add_mins; // to check endtime
			$sel="SELECT DISTINCT(main.q_num),main.q_name,COUNT(v.q_num) as calls_offered,(SELECT count(call_ans_time)  FROM `daily_foods_calls_view` ans WHERE ans.call_ans_time!='' and ans.q_num=main.q_num AND ans.admin_id=main.admin_id) as calls_ans FROM `daily_foods_ag_q_details` main INNER JOIN daily_foods_calls_view v on v.q_num=main.q_num where main.admin_id='64' GROUP by v.q_num,v.call_history_id";
			 $res_sel = $this->dataFetchAll($sel, array());
			foreach($res_sel as $key=>$value){
				 $q_num= $value['q_num'];  $calls_ans= $value['calls_ans']; $q_name= $value['q_name'];  $calls_offered= $value['calls_offered'];
				
				
	$sel_dur="SELECT dn_num,q_num, SUM(interv) AS idle FROM daily_foods_user_status where status='idle' and admin_id='$admin_id' and q_num='$q_num' GROUP BY q_num";		
				
	 $res_dur = $this->dataFetchAll($sel_dur, array());
			$wait_tot='00:00:00';$max='00:00:00';$talk_tot='00:00:00';
foreach($res_dur as $key_dur=>$value_dur){
	//print_r($value_dur);exit;
  $idle= $value_dur['idle']; $talk= $value_dur['call_talk_time'];	
	$wait_tot=interval_sum($wait_tot,$wait);
	if($talk!=''){
		$talk_tot=interval_sum($talk_tot,$talk);
	}
	if($wait>$max){
		$max=$wait;
	}
}
				$wrap_time=$value['c2'];
       			//$idle_time=$value['idle'];
       			$busy_time=$value['busy'];		
        		$Available=$value['available'];
				$half1=interval_sum($wrap_time,$idle_time);
		 		$half2=interval_sum($busy_time,$Available);
		 		$duration=interval_sum($half1,$half2);
				$your_time = $wait_tot;
				date_default_timezone_set ("UTC");
				$secs = strtotime($your_time ) - strtotime("00:00:00");
  				$avg_wait= date("H:i:s",$secs / $calls_ans);
				$max_wait=$max;
 			    $ringing=$wait_tot;
				$date_val=$current_date_val;
                    $date = strtotime($start);
                    $hour= date('H', $date);
                    $minutes= date('i', $date);
                    $minutes_30 = ($minutes - ($minutes % 30));
                    $hr_val=date('H:i', mktime($hour,0));
		 			$unanswered_calls=$calls_offered-$calls_ans;
				
				$table_str.="<tr role='row' class='odd' id=''>
														  <td bgcolor='#FFFFFF' style='border: 1px solid'>$current_date_val</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hr_val</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hour:$minutes_30</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hour:$minutes </td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$q_name</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$calls_offered</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$calls_ans</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$unanswered_calls</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$aban_del</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$avg_wait</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$max_wait</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>0</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$ringing</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$talk_tot</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>00:00:00</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$wrap_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$idle_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$busy_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$duration</td>
														</tr>";
				//echo $table_str;exit;
			}
			 }	}}elseif($type=='Agent Service Level'){
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
	<tr>
	<td colspan='27' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
	</tr> <tr>
	<td colspan='27' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
	</tr>  <tr>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent id</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent Name</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Date</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Hour</strong></td> 
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Half Hour</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Interval</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Actual Hours</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Idle Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Wrapup Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Handling Time ( AHT )</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Not Ready Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Queue</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Offered Calls</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Handled </strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls handled after threshold</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls handled within threshold</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned after threshold</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calls Abandoned Within threshold</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Service level</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Abandoning Delay</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Average Speed Of Answer</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Max Delay</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Held Contacts (%)</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Ring Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Talk Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Total Hold Time</strong></td>
	</tr>";
	 $select="SELECT DISTINCT(date(time_of_status)) as date from daily_foods_calls where date(time_of_status)>='$from_dt' and date(time_of_status)<='$to_dt'";
	 $result = $this->dataFetchAll($select, array());
	foreach($result as $t=>$k){
	$current_date_val= $k['date'];
        $starttime = $current_date_val.' 00:00:00';  // your start time
        $endtime = $current_date_val.' 23:59:59';  // End time
        $duration = '15';  // split by 30 mins

        $array_of_time = array ();
        $start_time    = strtotime ($starttime); //change to strtotime
        $end_time      = strtotime ($endtime); //change to strtotime

        $add_mins  = $duration * 60;

        while ($start_time <= $end_time) // loop between time
        {
            $array_of_time = date ("H:i:s", $start_time);

            $st = strtotime("+00 minutes", strtotime($array_of_time));
            $start = $current_date_val . ' ' . date('H:i:s', $st);

            $et = strtotime("+15 minutes", strtotime($array_of_time));
			if(date('H:i:s', $et)=='00:00:00'){
				  $current_date_val = date('Y-m-d', strtotime($current_date_val . ' +1 days'));
			}
            $end = $current_date_val . ' ' . date('H:i:s', $et);
//            echo date('H:i:s', $st).'  '.date('H:i:s', $et).'</br>';
            $start_time += $add_mins; // to check endtime
			
			 $sel="SELECT name,afc.agnt_num,COUNT(afc.agnt_num)as call_offered,(SELECT COUNT(call_ans_time) from daily_foods_calls sub where sub.call_ans_time!='' and sub.agnt_num=afc.agnt_num and sub.time_of_status>='$start' and sub.time_of_status<='$end' ) as calls_ans,(SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(call_waiting_time))) as call_wait from daily_foods_calls where agnt_num=afc.agnt_num and call_waiting_time!='' and time_of_status>='$start' and time_of_status<='$end')as call_wait,(SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(call_talk_time))) as call_talk from daily_foods_calls where agnt_num=afc.agnt_num and call_talk_time!='' and time_of_status>='$start' and time_of_status<='$end') as talk_time,(SELECT max(call_waiting_time) from daily_foods_calls where agnt_num=afc.agnt_num and call_waiting_time!='' and time_of_status>='$start' and time_of_status<='$end') as max_wait FROM daily_foods_calls afc INNER JOIN (SELECT DISTINCT(ag_num),concat(ag_firstname,' ',ag_lastname) as name FROM daily_foods_ag_q_details)t1 on t1.ag_num=afc.agnt_num where afc.agnt_num!='' and afc.time_of_status>='$start' and afc.time_of_status<='$end' GROUP by afc.agnt_num";
			   $res_sel = $this->dataFetchAll($sel, array());
			foreach($res_sel as $key=>$value){
				$agnt_num= $value['agnt_num'];
$sel_stat="select idle,busy,c2,available FROM
((SELECT '1' as des, coalesce((SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(time_of_endstatus, time_of_update)))) FROM `daily_foods_user_status` where ag_num='$agnt_num' and status='Custom 2' and time_of_update>='$start' and time_of_update<='$end'),'00:00:00')as c2) t1 JOIN

(SELECT '1' as des, coalesce((SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(time_of_endstatus, time_of_update)))) AS idle  FROM `daily_foods_user_status` where ag_num='$agnt_num' and status='idle' and time_of_update>='$start' and time_of_update<='$end'),'00:00:00')as idle) t2 on t1.des=t2.des JOIN
 
 (SELECT '1' as des, coalesce((SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(time_of_endstatus, time_of_update)))) FROM `daily_foods_user_status` where ag_num='$agnt_num' and status='busy' and time_of_update>='$start' and time_of_update<='$end'),'00:00:00')as busy) t3 on t1.des=t3.des JOIN
 
 (SELECT '1' as des, coalesce((SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(time_of_endstatus, time_of_update)))) FROM `daily_foods_user_status` where ag_num='$agnt_num' and status='Available' and time_of_update>='$start' and time_of_update<='$end'),'00:00:00')as available)t4 on t1.des=t4.des)";
				$res_stat = $this->dataFetchAll($sel_stat, array());
				foreach($res_stat as $key_stat=>$value_stat){
					 $wrap_time=$value_stat['c2'];
       				 $idle_time=$value_stat['idle'];
      				 $busy_time=$value_stat['busy'];		
       				 $Available=$value_stat['available'];
		
		  			$half1=interval_sum($wrap_time,$idle_time);
		 			$half2=interval_sum($busy_time,$Available);
		 			$duration=interval_sum($half1,$half2);
					
					$date_val=$current_date_val;
                    $date = strtotime($start);
                    $hour= date('H', $date);
                    $minutes= date('i', $date);
                    $minutes_30 = ($minutes - ($minutes % 30));
                    $hr_val=date('H:i', mktime($hour,0));
					
					$name= $value['name'];
					$offered_calls= $value['call_offered'];
					$answered= $value['calls_ans'];					
					$wait_tot= $value['call_wait'];				
					$max_wait= $value['max_wait'];			
					$talk_time= $value['talk_time'];
					$your_time = $wait_tot;
			date_default_timezone_set ("UTC");
			$secs = strtotime($your_time ) - strtotime("00:00:00");
  			$avg_wait= date("H:i:s",$secs / $answered);
					
		$unanswered_calls=$offered_calls-$answered;
					//echo $answered;exit;
					$table_str.="<tr role='row' class='odd' id=''>
														  <td bgcolor='#FFFFFF' style='border: 1px solid'>$agnt_num</td>
														  <td bgcolor='#FFFFFF' style='border: 1px solid'>$name</td>
														  <td bgcolor='#FFFFFF' style='border: 1px solid'>$date_val</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hr_val</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hour:$minutes_30</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$hour:$minutes </td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$duration</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$idle_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$wrap_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$avg_hand_tim</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$busy_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$service_name</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$offered_calls</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$answered</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$unanswered_calls</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'></td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$aban_del</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$avg_wait</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$max_wait</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>0</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$wait_tot</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>$talk_time</td>
														<td bgcolor='#FFFFFF' style='border: 1px solid'>00:00:00</td>
														</tr>";

				}}}}
}elseif($type=='Call Report'){
	$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
	<tr>
	<td colspan='25' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
	</tr> <tr>
	<td colspan='25' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
	</tr> <tr>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Call id</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Date</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Start Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Calling Party</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Disposition</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Disposition Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Split/Skill</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Ans Logid</strong></td> 
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Talk Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Hold Time</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Trans Out</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Released</strong></td>
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Queue Time</strong></td> 
	<td bgcolor='#66CCFF' style='border: 1px solid'><strong>Ring Time</strong></td>
	</tr>";
	// $select="SELECT *, date(daily_foods_calls_view.call_start_time)as date,(SELECT agnt_num FROM `daily_foods_calls` where call_id=daily_foods_calls_view.call_id and call_ans_time!='' order by id desc limit 1) as agnt_num,(SELECT call_waiting_time FROM `daily_foods_calls` where call_id=daily_foods_calls_view.call_id and call_ans_time!='' order by id desc limit 1)as call_waiting_time,(SELECT call_talk_time FROM `daily_foods_calls` where call_id=daily_foods_calls_view.call_id and call_ans_time!='' order by id desc limit 1)as call_talk_time FROM daily_foods_calls_view where daily_foods_calls_view.call_start_time>'$from_dt' and daily_foods_calls_view.call_start_time<='$to_dt' order by daily_foods_calls_view.call_start_time ASC";
	//$select="SELECT *,date(time_of_status)as date FROM `daily_foods_calls_view` where call_ans_time!=''";
	// $select="SELECT *,date(time_of_status)as date FROM `daily_foods_calls_view` where call_ans_time!='' and date(time_of_status)>='$from_dt' and date(time_of_status)<='$to_dt' and admin_id='835'";
	$select="SELECT *,date(time_of_status)as date,(SELECT GROUP_CONCAT(agnt_num)as agnt_num FROM `daily_foods_calls` where daily_foods_calls_view.call_history_id=daily_foods_calls.call_history_id and call_ans_time!='') as agnt_num FROM `daily_foods_calls_view` where call_ans_time!='' and date(time_of_status)>='$from_dt' and date(time_of_status)<='$to_dt' and admin_id='$admin_id'";
    $result = $this->dataFetchAll($select, array());
			
foreach($result as $t=>$k){
	$date= $k['date'];
	$call_history=$k['call_history_id'];
	$exp=explode('_',$call_history);
	$call_id=$exp[1];
	//$call_id=$k['call_id'];
	$time = $k['call_start_time'];
    $call_time=date("H:i:s",strtotime($time));
	$caller=$k['call_frm_num'];
	$q_name=$k['q_name'];
	
		// $agnt_num=$k['ag_num'];
		$waiting=$k['call_waiting_time'];
		$ring=$k['call_waiting_time'];
		$talk_time=$k['call_talk_time'];
	
	
	 //$sel="SELECT GROUP_CONCAT(agnt_num)as agnt_num FROM `daily_foods_calls` where call_history_id='$call_history' and call_ans_time!=''";
     //$agnt_num = $this->fetchOne($sel, array());
	// $agnt_num=$res['agnt_num'];
		$table_str.="<tr role='row' class='odd' id=''>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$call_id</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$date</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$time</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$caller</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'></td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'></td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$q_name</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$agnt_num</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$talk_time</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'></td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'></td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'></td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$waiting</td>
            <td bgcolor='#FFFFFF' style='border: 1px solid'>$ring</td>
            </tr>";
	}}
			if($rep_format=='html'){
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$table_str);
			fclose($fh);
					$attach_filename="$report_name".".html";
			}else{
$original_report_name = $report_name.date('Ymdhis',time()).".xls";
$file = 'temp/'.$original_report_name;
$fh = fopen($file,"w");
fwrite($fh,$table_str);
fclose($fh);
				$attach_filename="$report_name".".xls";

			}

			
		
				//echo $type;exit;
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
		 $qry="SELECT * from report_details where admin_id='$user_id'".$search_qry;
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
