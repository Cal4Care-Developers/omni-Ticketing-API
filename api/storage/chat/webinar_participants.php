<?php
$string = $_REQUEST['res'];

$output = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($string)); 
$test = html_entity_decode($output,null,'UTF-8'); 
$character = json_decode($test);

$export_data =  json_decode(json_encode($character->result->data), true);

$filename = $export_data[0]['title'].'.csv';


// file creation

$file = fopen($filename,"w");

$delimiter = ",";
$fields = array('First Name', 'Last Name', 'Email','Organization','Meeting Id','Join At','Title');

fputcsv($file, $fields, $delimiter);

foreach ($export_data as $line){
	
 fputcsv($file,$line);
}

fclose($file);

// download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Disposition: inline; filename=".$filename);
header("Content-Type: application/csv; "); 
header("Content-Transfer-Encoding: Binary"); 
header("Content-length: ". filesize($filename)); 



readfile($filename);

// deleting file
unlink($filename);
exit();