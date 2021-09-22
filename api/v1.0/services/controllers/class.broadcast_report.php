<?php
//echo "test";exit;
	class broadcast_report extends restApi{
	public function bd_report($data){
		extract($data);
	    
	  //$qry="SELECT campaign.camp_name,date(predictive_history.call_start) as start,cast(predictive_history.call_start as time(0)) as st_time,predictive_history.phone_no as phone_number,cast(predictive_history.call_start as time(0)) as call_start,cast(predictive_history.call_end as time(0)) as call_end,SEC_TO_TIME(TIMESTAMPDIFF( SECOND, predictive_history.call_start, predictive_history.call_end)) diff,predictive_history.call_status  FROM `predictive_history` INNER JOIN campaign on campaign.camp_id=predictive_history.campaign_id and campaign.admin_id=predictive_history.admin_id Where predictive_history.admin_id='$admin_id' and (campaign.camp_type='Broadcast_Dialler' or campaign.camp_type='Broadcast_Survey_Dialler') and date(predictive_history.call_start)>='$from' and date(predictive_history.call_start)<='$to'";	
		
		$qry="SELECT predictive_history.camp_name,date(predictive_history.call_start) as start,cast(predictive_history.call_start as time(0)) as st_time,predictive_history.phone_no as phone_number,cast(predictive_history.call_start as time(0)) as call_start,cast(predictive_history.call_end as time(0)) as call_end,SEC_TO_TIME(TIMESTAMPDIFF( SECOND, predictive_history.call_start, predictive_history.call_end)) diff,predictive_history.call_status FROM `predictive_history` INNER JOIN campaign on campaign.camp_id=predictive_history.campaign_id and campaign.admin_id=predictive_history.admin_id Where predictive_history.admin_id='$admin_id' and (campaign.camp_type='Broadcast_Dialler' or campaign.camp_type='Broadcast_Survey_Dialler') and predictive_history.call_start>='$from' and predictive_history.call_start<='$to'";
		if($name){			
		$name="'" . str_replace(",", "','", $name) . "'";
			$qry.=" and predictive_history.camp_name IN ($name)";
		}if($type){			
		$type="'" . str_replace(",", "','", $type) . "'";
			$qry.=" and call_status IN ($type)";
		}
		//echo $qry;exit;
		return $this->dataFetchAll($qry, array());
	}
public function bd_names($data){
	extract($data);
		$qry="Select Distinct(camp_name) from predictive_history where admin_id='$admin_id' and camp_name!=''";
	return $this->dataFetchAll($qry, array());
}
	}