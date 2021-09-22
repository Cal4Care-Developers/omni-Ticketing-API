<?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
	class survey extends restApi{
		function survey_list($data){
			extract($data);
			  $search_qry = "";			
			 if($search_text!= ""){
              $search_qry= " and (sur.ani like '%".$search_text."%' or sur.dins like '%".$search_text."%')";
            }
			
			$qry="SELECT EXTRACT(Year FROM date(login_dt)) as year,MONTHNAME(date(sur.login_dt)) as month,sur.login_dt,sur.ani,sur.dins,rate.survey_name,rate.survey_value FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id  where sur.admin_id='$admin_id'".$search_qry;				
			
			//$qry="SELECT EXTRACT(Year FROM date(login_dt)) as year,MONTHNAME(date(sur.login_dt)) as month,sur.login_dt,sur.ani,sur.dins,rate.survey_name FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id  where sur.admin_id='$admin_id'";	
			if($from_dt!=''){
				$qry.=" and  date(sur.login_dt)>='$from_dt'";
			}if($to_dt!=''){
				$qry.=" and  date(sur.login_dt)<='$to_dt'";
			}if($agent_name!=''){
				 $agent_name="'" . str_replace(",", "','", $agent_name) . "'";
				$qry.=" and dins IN ($agent_name)";
			}if($call_id!=''){
					 $call_id="'" . str_replace(",", "','", $call_id) . "'";
				$qry.=" and ani IN ($call_id)";
			}
 $detail_qry = $qry."   order by sur.id desc limit ".$limit." Offset ".$offset	;
			  //echo $detail_qry;exit;
           $parms = array();
            $result["list_data"] = $this->dataFetchAll($detail_qry,array());
            $result["list_info"]["total"] = $this->dataRowCount($qry,$parms);
            $result["list_info"]["offset"] = $offset;
            return $result;		

            }
		function survey_rep($data){
			extract($data);
						
			//$qry="SELECT EXTRACT(Year FROM date(login_dt)) as year,MONTHNAME(date(sur.login_dt)) as month,sur.login_dt,sur.ani,sur.dins,rate.survey_name FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id  where sur.admin_id='$admin_id'";
			$qry="SELECT EXTRACT(Year FROM date(login_dt)) as year,MONTHNAME(date(sur.login_dt)) as month,sur.login_dt,sur.ani,sur.dins,CONCAT(rate.survey_value, '. ', rate.survey_name) as survey_name FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id  where sur.admin_id='$admin_id'";
			
			if($from_dt!=''){
				$qry.=" and  date(sur.login_dt)>='$from_dt'";
			}if($to_dt!=''){
				$qry.=" and  date(sur.login_dt)<='$to_dt'";
			}if($agent_name!=''){
				 $agent_name="'" . str_replace(",", "','", $agent_name) . "'";
				$qry.=" and dins IN ($agent_name)";
			}if($call_id!=''){
					 $call_id="'" . str_replace(",", "','", $call_id) . "'";
				$qry.=" and ani IN ($call_id)";
			}
			$parms = array();
            $result = $this->dataFetchAll($qry,array());
           return $result;
		}
		function get_survey_agents($data){
			extract($data);
						
			$qry="SELECT Distinct(dins) FROM survey  where admin_id='$admin_id'";	
           $parms = array();
            $result = $this->dataFetchAll($qry,array());
            return $result;	

            }
		function get_survey_callers($data){
			extract($data);
						
			$qry="SELECT Distinct(ani) FROM survey  where admin_id='$admin_id'";	
           $parms = array();
            $result = $this->dataFetchAll($qry,array());
            return $result;	

            }
		function survey_summary_rep($data){
			extract($data);
						
			$qry="SELECT count(id) as rating,rate.survey_name,rate.survey_value FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id WHERE sur.admin_id='$admin_id'"; 
			if($from_dt!=''){
			$qry.=" and date(sur.login_dt)>='$from_dt'";
			}if($to_dt!=''){
			$qry.=" and date(sur.login_dt)<='$to_dt'" ;
			}
			$qry.=" group by posted_key order by posted_key ASC";	
		//	echo $qry;exit;
			 $tot_qry="SELECT count(id) as total FROM survey where admin_id='$admin_id'";
			if($from_dt!=''){
			$tot_qry.=" and date(login_dt)>='$from_dt'";
			}if($to_dt!=''){
			$toto_qry.="  and date(login_dt)<='$to_dt'";
			}
		
           $parms = array();
            $result_list = $this->dataFetchAll($qry,array());
			$total_values = $this->fetchmydata($tot_qry,array());
		$toto_per='0';
			foreach($result_list as $t=>$k){
			 $rating= $k['rating'];
			 //$survey_name= 'Callers rated'.' '.$k['survey_name'];
			 $survey_name= $k['survey_value'].'. '.$k['survey_name'];	
			 $per_val = number_format(($rating/$total_values)*100,0);
				
				$rating_array[] = array('survey_name' => $survey_name,'rating' => $rating,'percent' => $per_val);
			$toto_per+=$per_val;
				}
			$rating_array[] =array('survey_name' => 'Total Nos of Calls Received','rating' => $total_values,'percent' => $toto_per);
			$rating_array_test = array('list_data' => $rating_array);
			//$tot_callsarr = array();
			
			$merge_result = array_merge( $rating_array_test);
            $tarray = json_encode($merge_result);           
print_r($tarray);exit;

            }
		function survey_summary($data){
			extract($data);
						
			$qry="SELECT count(id) as rating,rate.survey_name FROM survey sur INNER JOIN survey_ratings rate on sur.posted_key=rate.survey_value and sur.admin_id=rate.admin_id where sur.admin_id='$admin_id'"; 
			if($from_dt!=''){
			$qry.=" and date(sur.login_dt)>='$from_dt'";
			}if($to_dt!=''){
			$qry.=" and date(sur.login_dt)<='$to_dt'" ;
			}
			$qry.=" group by posted_key ORDER by rate.rating_id";	
			//echo $qry;exit;
			 $tot_qry="SELECT count(id) as total FROM survey where admin_id='$admin_id'";
			if($from_dt!=''){
			$tot_qry.=" and date(login_dt)>='$from_dt'";
			}if($to_dt!=''){
			$toto_qry.="  and date(login_dt)<='$to_dt'";
			}
		
           $parms = array();
            $result_list = $this->dataFetchAll($qry,array());
			$total_values = $this->fetchmydata($tot_qry,array());
		$toto_per='0';
			foreach($result_list as $t=>$k){
			 $rating= $k['rating'];
			 $survey_name= 'Callers rated'.' '.$k['survey_name'];
			 $per_val = number_format(($rating/$total_values)*100,0);
				
				$rating_array[] = array('rating' => $rating,'survey_name' => $survey_name,'percent' => $per_val);
			$toto_per+=$per_val;
				}
			$rating_array_test = array('list_data' => $rating_array,'total' => 'Total Nos of Calls Received','nos' => $total_values,'percentage' => $toto_per);
			//$tot_callsarr = array();
			
			$merge_result = array_merge( $rating_array_test);
            $tarray = json_encode($merge_result);           
print_r($tarray);exit;

            }
	}