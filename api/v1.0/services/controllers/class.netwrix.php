 <?php
include 'asset/autoload.php';
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

    class netwrix extends restApi{	
		 function create_chart($data){
			 extract($data);
			
			   $queue="'" . str_replace(",", "','", $queue) . "'";
	$qry="SELECT q_num,q_name FROM `daily_foods_ag_q_details` where admin_id='$admin_id' and q_num IN($queue) and q_name!='' GROUP by q_num";
			
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
			// print_r($res_data);exit;
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
			//print_r($data_plot);exit;
			
			 $table_str="<html>
  <head>
    <script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
    <script type=\"text/javascript\">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          $head,
         $data_plot
        ]);

        var options = {
          chart: {
            title: 'Queue Performance',
            subtitle: '$month',
          },
          bars: 'verticurl' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
  </head>
  <body>
    <div id=\"barchart_material\" style=\"width: 100%; height: 600px;\"></div>
  </body>
</html>
";
			//echo $table_str;exit;
			$original_report_name = $report_name.date('Ymdhis',time()).".html";
			 $file = 'temp/'.$original_report_name;
			 $fh = fopen($file,"w");
			fwrite($fh,$table_str);
			fclose($fh);
			$attach_filename=$report_name.".html";
				
			
			$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://omni.mconnectapps.com/api/v1.0/temp/$original_report_name','$type','$user_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
		}
			 else{
				 $result_fin=0;
				return $result_fin;exit;
			 }
		 }
 function get_queue($data){
	 extract($data);
	$sql="SELECT q_num,q_name FROM `daily_foods_ag_q_details` where admin_id='$admin_id' and q_num!='' group by q_num";
	$res_data= $this->dataFetchAll($sql,array());
	 return $res_data;
 }

function chart_excel($data){
	extract($data);
	require __DIR__ . '/asset/phpoffice/phpspreadsheet/samples/Header.php';
	$queue="'" . str_replace(",", "','", $queue) . "'";
	$qry="SELECT q_num,q_name FROM `daily_foods_ag_q_details` where admin_id='$admin_id' and q_num IN($queue) and q_name!='' GROUP by q_num";
	$parms = array();
	$res= $this->dataFetchAll($qry,$parms);
	$q_na='Date';
	foreach($res as $key=>$val){
				$q_num_arr[]=$val['q_num'];
				for($i=1; $i<3; $i++){
					if($i=='1'){
						$q_name=$val['q_name'].' Answered';
					}else{
						$q_name=$val['q_name'].' Abandoned';
					}	
					$q_na=$q_na.','.$q_name;
					$q_na=trim($q_na,",");
				}	 
		
			}
	//print_r($q_na);exit;
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
			//print_r($val_ar);exit;
			$arr=array();
		//$q_na="'" . str_replace(",", "','", $q_na) . "'";
		$head=$q_na;	
			 $head=explode(',',$head);
		$arr[]=$head;
		//print_r($arr);exit;
		$nos=0;
	
		 foreach($dtarr as $dd){
				 $dte=$dd;
			 
			 $nos++;
			 foreach($q_num_arr as $qarr_val){	
				 $mas_arr[$dd][$qarr_val]='0';
				 $data_out=$val_ar[$dd][$qarr_val];
				
				 if($data_out==''){
					 $data_out='0,0';
				 }
				 $dte=$dte.','.$data_out;
				
			 }			 
			 $data_plot=$dte;	
			 $dte1=explode(',',$data_plot);
			// $x=array_merge($arr,$dte1);
			
				$arr[]=$dte1;
			
			 } 
		
		

		
	//print_r($arr);exit;
	$spreadsheet = new Spreadsheet();
	$worksheet = $spreadsheet->getActiveSheet();
	$worksheet->fromArray(
    $arr
);
$row = 1;
$lastColumn = $worksheet->getHighestColumn();
$lastColumn++;
for ($column = 'B'; $column != $lastColumn; $column++) {
    $row++;
    $dataSeriesLabels[]= new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$'.$column.'$1', null, 1) ;
}

$row=15;

$xAxisTickValues = [
    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$'.$row.'', null, 15), // Q1 to Q4
];

$sno=2;
for ($column = 'B'; $column != $lastColumn; $column++) {
    //$sno++;
    $dataSeriesValues[]=  new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$'.$column.'$'.$sno.':$'.$column.'$'.$row.'', null, 15);
}

// Build the dataseries
$series = new DataSeries(
    DataSeries::TYPE_BARCHART, // plotType
    DataSeries::GROUPING_CLUSTERED, // plotGrouping
    range(0, count($dataSeriesValues) - 1), // plotOrder
    $dataSeriesLabels, // plotLabel
    $xAxisTickValues, // plotCategory
    $dataSeriesValues        // plotValues
);
// Set additional dataseries parameters
//     Make it a horizontal bar rather than a vertical column graph
$series->setPlotDirection(DataSeries::DIRECTION_COL);

// Set the series in the plot area
$plotArea = new PlotArea(null, [$series]);
// Set the chart legend
$legend = new Legend(Legend::POSITION_BOTTOM, null, false);

$title = new Title('Queue Performance');
$xAxisLabel = new Title('Date');
$yAxisLabel = new Title('Number of Calls');

// Create the chart
$chart = new Chart(
    'chart1', // name
    $title, // title
    $legend, // legend
    $plotArea, // plotArea
    true, // plotVisibleOnly
    0, // displayBlanksAs
    $xAxisLabel, // xAxisLabel
     $yAxisLabel  // yAxisLabel
);

// Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('G1');
$chart->setBottomRightPosition('R21');

// Add the chart to the worksheet
$worksheet->addChart($chart);
//print_r(__FILE__);exit;
//$filename = $helper->getFilename(__FILE__);
$filename=$report_name.date('Ymdhis',time()).'.xlsx';
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setIncludeCharts(true);
$callStartTime = microtime(true);
$filename = 'temp1/'.$filename;
$writer->save($filename);
	$attach_filename=$report_name.'.xlsx';
//$helper->logWrite($writer, $filename, $callStartTime);
$insert_sql="INSERT INTO report_details(report_dt,report_name,original_name,report_type_name,admin_id) VALUES(current_timestamp,'$attach_filename','http://omni.mconnectapps.com/api/v1.0/$filename','$type','$user_id')";
$result_fin= $this->db_query($insert_sql, array());
$result_fin = $result_fin == 1 ? 1 : 0;
			return $result_fin;exit;
		 }else{
				 $result_fin=0;
				return $result_fin;exit;
			 }
    }
			}