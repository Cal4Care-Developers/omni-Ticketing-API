<?php

include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory; 
class sms extends restApi{
    function sms_report($data){
		
		$type="SMS REPORT";
        extract($data);
		$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<tr>
			<td colspan='8' align='center' bgcolor='#FFFFFF' style='border: 1px solid'><strong>$type</strong></td>
			</tr><tr>
			<td colspan='8' align='left' bgcolor='#FFFFFF' style='border: 1px solid'><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr> <tr>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>SMS Sent to</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>SMS Sent By</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Message</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Country Code</strong></td> 
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Sender Id</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>SMS Rate</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>SMS Sent On</strong></td>";
		
		
		
		 $select="SELECT user.user_name,chat_sms.sender_id,chat_sms.country_code,chat_sms.customer_name,chat_data_sms.chat_message,chat_data_sms.sms_tarrif,chat_data_sms.created_dt FROM `chat_data_sms` INNER JOIN user on user.user_id=chat_data_sms.agent_id INNER JOIN chat_sms on chat_sms.chat_id=chat_data_sms.chat_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and chat_data_sms.created_dt>='$from_dt' and chat_data_sms.created_dt<='$to_dt' ORDER BY chat_data_sms.chat_msg_id desc";
		
		$result_dat = $this->dataFetchAll($select, array());
		$total='0';
		foreach($result_dat as $key=>$value){
			$customer_name= $value['customer_name'];			
			$user_name= $value['user_name'];		
   		    $chat_message= $value['chat_message'];	
		    $country_code= $value['country_code'];	
		    $sender_id= $value['sender_id'];	
		    $sms_tarrif= $value['sms_tarrif'];
		    $created_dt= $value['created_dt'];
			$total=$total+$sms_tarrif;
			$table_str.="<tr>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid black'>$customer_name</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$user_name</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'> $chat_message</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$country_code</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$sender_id</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$sms_tarrif</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$created_dt</td>
			";
		  
		}
		$total=round($total,2);
		$table_str .="</tr><tr><td colspan='5' bgcolor='#ffffff' style='border: 1px solid black'>Total</td>
		
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$total</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'></td>
			</tr>";
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

			
		
				//echo $type;exit;
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
	function generate_sms($data){
		extract($data);
		$select="SELECT chat_sms.customer_name,user.user_name,chat_data_sms.chat_message,chat_sms.country_code,chat_sms.sender_id,chat_data_sms.sms_tarrif FROM `chat_data_sms` INNER JOIN user on user.user_id=chat_data_sms.agent_id INNER JOIN chat_sms on chat_sms.chat_id=chat_data_sms.chat_id where IF(user.admin_id = 1, user.user_id , user.admin_id )='$admin_id' and chat_data_sms.created_dt>='$from_dt' and chat_data_sms.created_dt<='$to_dt' ORDER BY chat_data_sms.chat_msg_id desc";
		
		$result_dat = $this->dataFetchAll($select, array());
		return $result_dat;
	}
}