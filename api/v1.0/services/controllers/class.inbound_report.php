<?php
include 'asset/autoload.php';		
use PhpOffice\PhpSpreadsheet\IOFactory;
class inbound_report extends restApi{
    function inbound($data){
		//print_r($data);exit;
        extract($data);
		$table_str="<table cellpadding='3' cellspacing='1' border='0' bgcolor='#999999'>
			<tr>
			<td colspan='6' align='center' bgcolor='#FFFFFF' ><strong>$type</strong></td>
			</tr><tr>
			<td colspan='6' align='left' bgcolor='#FFFFFF' ><strong>Range:</strong>$from_dt to $to_dt</td>
			</tr> <tr>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Sno</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent Name</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Agent Number</strong></td>
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Call From</strong></td> 
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Date Time</strong></td> 
			<td colspan='1' bgcolor='#66CCFF' style='border: 1px solid'><strong>Duration</strong></td>";
		
		
		
		 $select="SELECT call_details.*,CONCAT(user_det.ag_firstname,' ',user_det.ag_lastname)as name FROM `call_details` INNER JOIn daily_foods_ag_q_details user_det on user_det.ag_num=call_details.to_no and user_det.admin_id=call_details.admin_id where call_details.admin_id='$admin_id' and call_details.call_nature='Inbound Call'";
		if($from_dt!=''){
			$select.=" and date(dt_time)>='$from_dt'";
		}if($to_dt!=''){
			$select.=" and date(dt_time)<='$to_dt'";
		}if($call_type!='')
		{
			$select.=" and type='$call_type'";
		}
		$select.=" group by call_details.id";
		//echo $select;exit;
		$result_dat = $this->dataFetchAll($select, array());
		$sno='0';
		foreach($result_dat as $key=>$value){
			$sno++;
			 $name= $value['name'];	
			 $ag_num= $value['to_no'];	
			 $from_no= $value['from_no'];	
			 $dt_time= $value['dt_time'];
			 $duration= $value['duration'];
			$table_str.="</tr><tr>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$sno</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$name</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$ag_num</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$from_no</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$dt_time</td>
			<td colspan='1' bgcolor='#ffffff' style='border: 1px solid'>$duration</td></tr>";
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
