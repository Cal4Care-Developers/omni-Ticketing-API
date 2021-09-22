<?php


    class chart extends restApi{	
		 function chart_excel($data){
			 extract($data);
			
			   $queue="'" . str_replace(",", "','", $queue) . "'";
	$qry="SELECT q_num,q_name FROM `daily_foods_ag_q_details` where admin_id='$admin_id' and q_num IN($queue) and q_name!='' GROUP by q_num";;
			
			 $parms = array();
			$res= $this->dataFetchAll($qry,$parms);
			 

	//echo $get_data;exit;		 
			// $res_data= $this->dataFetchAll($get_data,$parms);
			 $temp_arr=array();
			 $q_name[]='Date';
			foreach($res as $key=>$val){
				$q_num_arr[]=$val['q_num'];
				for($i=1; $i<3; $i++){
					if($i=='1'){
						$q_name[]=$val['q_name'].' Answered';
					}else{
						$q_name[]=$val['q_name'].' Abandoned';
					}					
				}	  
			}
			
			 //$begin = new DateTime($from_dt);
//$end = new DateTime($to_dt);
//$end = $end->modify( '+1 day' ); 

//$interval = new DateInterval('P1D');
//$daterange = new DatePeriod($begin, $interval ,$end);
 $monthf = date("M Y",strtotime($from_dt));
 $montht = date("M Y",strtotime($to_dt));
 if($monthf!=$montht){
 $month=$monthf.' to '.$montht;
 }else{
	 $month=$monthf;
 }
			
	$data_val="SELECT  IFNULL(t1.ans,0) as ans ,IFNULL(t2.missed,0) as missed,t1.date,t1.q_num FROM
(SELECT q_num,count(call_history_id) as ans,date(time_of_status) as date,admin_id FROM `daily_foods_calls_view` where q_num!='' and call_ans_time!=''  and admin_id='$admin_id' and q_num IN ($queue)  and date(time_of_status)>='$from_dt' and date(time_of_status)<='$to_dt'  GROUP by q_num,date(time_of_status)) t1
LEFT JOIN (SELECT q_num,count(call_history_id) as missed,date(time_of_status) as date,admin_id FROM `daily_foods_calls_view` where q_num!='' and call_ans_time='' and admin_id='$admin_id'  and q_num IN ($queue)  and date(time_of_status)>='$from_dt' and date(time_of_status)<='$to_dt'  GROUP by q_num,date(time_of_status)) t2 on t1.q_num=t2.q_num and t1.date=t2.date and t1.admin_id=t2.admin_id INNER JOIN daily_foods_ag_q_details t3 on t1.q_num=t3.q_num and  t1.admin_id=t3.admin_id where t1.admin_id='$admin_id' group by t1.q_num,t1.date
order by t1.date";
	//echo 		$data_val;exit;
	$res_data= $this->dataFetchAll($data_val,array());
	$res_count= $this->dataRowCount($data_val,array());
		if($res_count!='0'){
			 foreach($res_data as $key_datd=>$val_data){
				  $date= $val_data['date']; 
				 $q_num= $val_data['q_num'];
			$ans= $val_data['ans'];
			$missed= $val_data['missed'];
				 
			 if (in_array($date, $dtarr)){				
			   $val_ar[$date][$q_num]=$ans.','.$missed;
			  }
			else{				 
				$val_ar[$date][$q_num]=$ans.','.$missed;
				$dtarr[]=$date;			 
			  }
				 
			 }
			 foreach($dtarr as $dd){
				 $dte="'".$dd."'";
			 foreach($q_num_arr as $qarr_val){	
				 $mas_arr[$dd][$qarr_val]='0';
				 $data_out=$val_ar[$dd][$qarr_val];
				 if($data_out==''){
					 $data_out='0,0';
				 }
				 $dte=$dte.','.$data_out;
			 }
				// $dte="'" . str_replace(",", "','", $dte) . "'";
				$data_plot[]='['.$dte.']';	
				
			 }
			 	$data_plot=implode(',',$data_plot);	
			  	$q_det=implode(',',$q_name);
			 	$q_det="'" . str_replace(",", "','", $q_det) . "'";
			 	$head='['.$q_det.']';	
			

			
 }
    }
	}