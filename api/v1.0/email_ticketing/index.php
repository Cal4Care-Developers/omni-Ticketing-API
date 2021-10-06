<?php
ini_set('display_errors',0);
//error_reporting(E_ALL);
$inc_wp_response = file_get_contents('php://input',true);
//$log = file_put_contents('email_te.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
//file_put_contents('mm2.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
$forward_response = file_get_contents('php://input',true);    
//$inc_wp_response ='{"from":"MrVoip | Selva < sk@mrvoip.com >","subject":"TEST 1234567","message":"<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=iso-8859-1\">\n<style type=\"text\/css\" style=\"display:none;\"> P {margin-top:0;margin-bottom:0;} <\/style>\n<\/head>\n<body dir=\"ltr\">\n<div style=\"font-family: Calibri, Arial, Helvetica, sans-serif; font-size: 12pt; color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">\nfsfwefwef<\/div>\n<table bgcolor=\"#0597d4\" class=\"app-link\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" style=\"table-layout: fixed; background-color: #0597d4;\">\n<tbody>\n<tr bgcolor=\"#0597d4\">\n<td height=\"5\" colspan=\"2\"><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td colspan=\"2\" style=\"text-align: center;font-size: 20px;margin-bottom: 0;font-weight: bold; color:#fff; font-style:italic;\">\nGo mobile ! Download Cal4care Customer\/Reseller CMS Mobile Apps. <\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td height=\"1\" colspan=\"2\"><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td align=\"right\"><a target=\"_blank\" href=\"https:\/\/apps.apple.com\/us\/app\/cal4care-cms-customer\/id1521608417\"><img width=\"120\" height=\"35\" src=\"https:\/\/erp.cal4care.com\/img\/email-template\/email-signature\/app-store.png\">\n<\/a><\/td>\n<td align=\"left\"><a target=\"_blank\" href=\"https:\/\/play.google.com\/store\/apps\/details?id=com.erpcustomer\"><img width=\"120\" height=\"35\" src=\"https:\/\/erp.cal4care.com\/img\/email-template\/email-signature\/play-store.png\">\n<\/a><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td height=\"5\" colspan=\"2\"><\/td>\n<\/tr>\n<\/tbody>\n<\/table>\n<\/body>\n<\/html>\n\n","to":"[\"isales@cal4care.com\"]","attachments":"null","message_id":"<PSBPR04MB39911C23A41ED8479442D5EDB2CC9@PSBPR04MB3991.apcprd04.prod.outlook.com>","recipients":[]}';


//$inc_wp_response='{"from":"Cal4Care | MR < mr@cal4care.com >","subject":"Re: demo MR [##126]","message":"<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text\/html; charset=us-ascii\">\n<style type=\"text\/css\" style=\"display:none;\"> P {margin-top:0;margin-bottom:0;} <\/style>\n<\/head>\n<body dir=\"ltr\">\n<div style=\"font-family: Calibri, Arial, Helvetica, sans-serif; font-size: 12pt; color: rgb(0, 0, 0); background-color: rgb(255, 255, 255);\">\nyes received<\/div>\n<div>\n<div style=\"font-family: Calibri, Arial, Helvetica, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);\">\n<br>\n<\/div>\n<div id=\"Signature\">\n<div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div style=\"\">\n<p style=\"color: rgb(0, 0, 0); font-family: &quot;Times New Roman&quot;, serif; font-size: 12pt; text-align: start; background-color: white; margin: 0px;\">\n<span lang=\"en-SG\" style=\"margin: 0px; border: 1pt none windowtext; font-size: 10pt; font-family: &quot;Franklin Gothic Book&quot;, sans-serif; color: rgb(31, 73, 125);\">Thanks and Regards,<\/span><span lang=\"en-SG\" style=\"margin: 0px; font-size: 11pt; font-family: Calibri, sans-serif; color: black;\"><\/span><\/p>\n<font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">MR<\/span><\/font><\/div>\n<div style=\"\"><font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">Full Stack Web Developer<\/span><\/font><br>\n<p style=\"color: rgb(0, 0, 0); text-align: start; font-size: 11pt; font-family: Calibri, sans-serif; background-color: white; margin: 0px;\">\n<span style=\"margin: 0px; color: rgb(33, 33, 33);\">E:&nbsp; <a href=\"mailto:mr@cal4care.com\" title=\"mr@cal4care.com\">\nmr@cal4care.com<\/a><\/span><\/p>\n<\/div>\n<\/div>\n<\/div>\n<\/div>\n<div id=\"appendonsend\"><\/div>\n<hr style=\"display:inline-block;width:98%\" tabindex=\"-1\">\n<div id=\"divRplyFwdMsg\" dir=\"ltr\"><font face=\"Calibri, sans-serif\" style=\"font-size:11pt\" color=\"#000000\"><b>From:<\/b> AAtest1@cal4care.com &lt;AAtest1@cal4care.com&gt;<br>\n<b>Sent:<\/b> Monday, August 23, 2021 12:45 PM<br>\n<b>To:<\/b> Cal4Care | MR &lt;mr@cal4care.com&gt;<br>\n<b>Subject:<\/b> demo MR [##126]<\/font>\n<div>&nbsp;<\/div>\n<\/div>\n<div>\n<div style=\"font-family:verdana!important\">\n<p>ok thread test<\/p>\n<p>This is one for TEST Signature<\/p>\n<\/div>\n<br>\n<br>\n<div style=\"border:1px solid #d1d1d1; font-family:verdana!important; border-radius:8px; padding:12px; margin-bottom:25px\">\n<h1 style=\"font-size:20px; font-family:verdana!important; text-align:right; background:#00a65a; color:#fff; padding:10px; margin-top:0; border-radius:8px 8px 0 0\">\nmr@cal4care.com<\/h1>\n<style type=\"text\/css\" style=\"display:none\">\n<!--\np\n\t{margin-top:0;\n\tmargin-bottom:0}\n-->\n<\/style>\n<div style=\"font-family:Calibri,Arial,Helvetica,sans-serif; font-size:12pt; color:rgb(0,0,0); background-color:rgb(255,255,255)\">\nreceived as 126<\/div>\n<div>\n<div style=\"font-family:Calibri,Arial,Helvetica,sans-serif; font-size:12pt; color:rgb(0,0,0)\">\n<br>\n<\/div>\n<div id=\"x_Signature\">\n<div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div style=\"\">\n<p style=\"color:rgb(0,0,0); font-family:&quot;Times New Roman&quot;,serif; font-size:12pt; text-align:start; background-color:white; margin:0px\">\n<span lang=\"en-SG\" style=\"margin:0px; border:1pt none windowtext; font-size:10pt; font-family:&quot;Franklin Gothic Book&quot;,sans-serif; color:rgb(31,73,125)\">Thanks and Regards,<\/span><span lang=\"en-SG\" style=\"margin:0px; font-size:11pt; font-family:Calibri,sans-serif; color:black\"><\/span><\/p>\n<font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">MR<\/span><\/font><\/div>\n<div style=\"\"><font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">Full Stack Web Developer<\/span><\/font><br>\n<p style=\"color:rgb(0,0,0); text-align:start; font-size:11pt; font-family:Calibri,sans-serif; background-color:white; margin:0px\">\n<span style=\"margin:0px; color:rgb(33,33,33)\">E:&nbsp; <a href=\"mailto:mr@cal4care.com\" title=\"mr@cal4care.com\">\nmr@cal4care.com<\/a><\/span><\/p>\n<\/div>\n<\/div>\n<\/div>\n<\/div>\n<div id=\"x_appendonsend\"><\/div>\n<hr tabindex=\"-1\" style=\"display:inline-block; width:98%\">\n<\/div>\n<br>\n<div style=\"border:1px solid #d1d1d1; font-family:verdana!important; border-radius:8px; padding:12px; margin-bottom:25px\">\n<h1 style=\"font-size:20px; font-family:verdana!important; text-align:right; background:#00a65a; color:#fff; padding:10px; margin-top:0; border-radius:8px 8px 0 0\">\nmr@cal4care.com<\/h1>\n<style type=\"text\/css\" style=\"display:none\">\n<!--\np\n\t{margin-top:0;\n\tmargin-bottom:0}\n-->\n<\/style>\n<div style=\"font-family:Calibri,Arial,Helvetica,sans-serif; font-size:12pt; color:rgb(0,0,0); background-color:rgb(255,255,255)\">\ndemo AA test<br>\n<\/div>\n<div>\n<div style=\"font-family:Calibri,Arial,Helvetica,sans-serif; font-size:12pt; color:rgb(0,0,0)\">\n<br>\n<\/div>\n<div id=\"x_Signature\">\n<div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div><\/div>\n<div style=\"\">\n<p style=\"color:rgb(0,0,0); font-family:&quot;Times New Roman&quot;,serif; font-size:12pt; text-align:start; background-color:white; margin:0px\">\n<span lang=\"en-SG\" style=\"margin:0px; border:1pt none windowtext; font-size:10pt; font-family:&quot;Franklin Gothic Book&quot;,sans-serif; color:rgb(31,73,125)\">Thanks and Regards,<\/span><span lang=\"en-SG\" style=\"margin:0px; font-size:11pt; font-family:Calibri,sans-serif; color:black\"><\/span><\/p>\n<font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">MR<\/span><\/font><\/div>\n<div style=\"\"><font color=\"#1f497d\" face=\"Calibri, sans-serif\" style=\"\"><span style=\"font-size:13.3333px\">Full Stack Web Developer<\/span><\/font><br>\n<p style=\"color:rgb(0,0,0); text-align:start; font-size:11pt; font-family:Calibri,sans-serif; background-color:white; margin:0px\">\n<span style=\"margin:0px; color:rgb(33,33,33)\">E:&nbsp; <a href=\"mailto:mr@cal4care.com\" title=\"mr@cal4care.com\">\nmr@cal4care.com<\/a><\/span><\/p>\n<\/div>\n<\/div>\n<\/div>\n<\/div>\n<table bgcolor=\"#0597d4\" class=\"x_app-link\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed; background-color:#0597d4\">\n<tbody>\n<tr bgcolor=\"#0597d4\">\n<td height=\"5\" colspan=\"2\"><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td colspan=\"2\" style=\"text-align:center; font-size:20px; margin-bottom:0; font-weight:bold; color:#fff; font-style:italic\">\nGo mobile ! Download Cal4care Customer\/Reseller CMS Mobile Apps. <\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td height=\"1\" colspan=\"2\"><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td align=\"right\"><a target=\"_blank\" href=\"https:\/\/apps.apple.com\/us\/app\/cal4care-cms-customer\/id1521608417\"><img width=\"120\" height=\"35\" src=\"https:\/\/erp.cal4care.com\/img\/email-template\/email-signature\/app-store.png\">\n<\/a><\/td>\n<td align=\"left\"><a target=\"_blank\" href=\"https:\/\/play.google.com\/store\/apps\/details?id=com.erpcustomer\"><img width=\"120\" height=\"35\" src=\"https:\/\/erp.cal4care.com\/img\/email-template\/email-signature\/play-store.png\">\n<\/a><\/td>\n<\/tr>\n<tr bgcolor=\"#0597d4\">\n<td height=\"5\" colspan=\"2\"><\/td>\n<\/tr>\n<\/tbody>\n<\/table>\n<\/div>\n<\/div>\n<\/body>\n<\/html>\n\n","to":"[\"AAtest1@cal4care.com\"]","attachments":"null","message_id":"<PSAPR04MB47091045FF4C0F8AFFB13F19E5C49@PSAPR04MB4709.apcprd04.prod.outlook.com>","recipients":[{"source":"Email (to)","name":"AAtest1","email":"aatest1@cal4care.com"}]}';
//file_put_contents('message.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);
$inc_wp_response = json_decode($inc_wp_response);
$s = $inc_wp_response->message_id;
$message_id = preg_split('/\s*<([^>]*)>/', $s, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
$from = $inc_wp_response->from;
$subject = $inc_wp_response->subject;
$message = $inc_wp_response->message;
//file_put_contents('message.txt', $message.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
$attachments = $inc_wp_response->attachments;
$attachments2 = $inc_wp_response->attachments;
$attachments = json_decode($attachments);
$attachments2 = json_decode($attachments2);
$recipients = $inc_wp_response->recipients;


foreach($recipients as $recipient){
  //$recipient = $attachment->cid;
	if($recipient->source == 'Email (to)'){
		   $toRec[] =  $recipient->name." < ". $recipient->email ." >";
		   $toRec[] =  $recipient->email;
	}
}

foreach($recipients as $recipient){
  //$recipient = $attachment->cid;
	if($recipient->source == 'Email (cc)'){
		//$ccRec[] =  $recipient->name." < ". $recipient->email ." >";
		$ccRec[] =  $recipient->email;
	}
}

$key = 1;
foreach($attachments as  $key => $attachment){
	$cid = $attachment->cid;
	if($attachment->cid){
		unset($attachments[$key]);
	}
}

foreach($attachments as  $key => $attachment){
  $cid = 'cid:'.$cid;
  $file = $attachment->file;
 // $message = str_replace($cid, $file, $message);
  $cid = $attachment->cid;
  $attachmen[] = $file;
}


foreach($attachments as  $key => $attachment){
	$cid = $attachment->cid;
	if($attachment->cid){
		unset($attachments[$key]);
	}
}

foreach($attachments2 as $attachment){
  $cid = $attachment->cid;
  $cid = 'cid:'.$cid;
  $file = $attachment->file;
  $message = str_replace($cid, $file, $message);
  $cid = $attachment->cid;
}


if (strpos($from,'gmail') !== false) {
	foreach($attachments2 as  $key => $attachment){
	  $file = $attachment->file;
	  $attachmen[] = $file;
	}
}


$attachmen = json_encode($attachmen);



//print_r($message); exit;


// if(substr( $subject, 0, 3 ) === "Re:"){
//    $message = substr($message, 0, strpos($message, '<div id="divRplyFwdMsg" dir="ltr">'));
//}

$type = "gmail";
if(substr( $subject, 0, 3 ) === "Re:" || substr( $subject, 0, 3 ) === "RE:"){
    if (strpos($message,$type) !== false) {
        //$message = substr($message, 0, strpos($message, '<div class="gmail_quote">'));
        $message = preg_replace('/gmail_quote[\s\S]+?gmail_signature/', '', $message);
    } else {
		if (strpos($message, '<div id="divRplyFwdMsg" dir="ltr">') !== false) {
			$message = substr($message, 0, strpos($message, '<div id="divRplyFwdMsg" dir="ltr">'));
		} else if (strpos($message, '<div style="border:none;border-top:solid #B5C4DF 1.0pt;padding:3.0pt 0cm 0cm 0cm">') !== false) {
			$message = substr($message, 0, strpos($message, '<div style="border:none;border-top:solid #B5C4DF 1.0pt;padding:3.0pt 0cm 0cm 0cm">'));
		} else if (strpos($message, '<div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">') !== false) {
			$message = substr($message, 0, strpos($message, '<div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0cm 0cm 0cm">'));
		}else if (strpos($message, '<div id="divRplyFwdMsg">') !== false) {
			$message = substr($message, 0, strpos($message, '<div id="divRplyFwdMsg">'));
		}
}
}
//file_put_contents('message.txt', $forward_response.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
$agent_short_code = '';
$forward_from = '';
if(substr( $subject, 0, 3 ) === "Fw:" || substr( $subject, 0, 3 ) === "FW:"){	
  $explode_div = explode('<div style="font-family: Calibri, Arial, Helvetica, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">',$message);  
  $exp_slash_div = explode('</div',$explode_div[1]);
  $msg_val = htmlentities($exp_slash_div[0]);  
  if($msg_val!=''){
    $msg_val_explode = explode('+', $msg_val);    
    $agent_short_code = $msg_val_explode[1];     
  }else{
	  $whatIWant = substr($forward_response, strpos($forward_response, "+") + 1);
	  $t = strip_tags($whatIWant);
      $ep = explode('+',$t);
	  $agent_short_code = $ep[0];
	  //file_put_contents('msg_val.txt', $whatIWant.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
  }
  $match = '<html>';
  if (strpos($forward_response, $match) !== false) {
	// code to get forward FROM   
	$actual_from = substr($forward_response, strpos($forward_response, "From:") + 1);
    $actual_from_explode = explode('<\/b>',$actual_from);  
    $actual_from_explode_val = explode('\n',$actual_from_explode[1]);
    $actual_from_val = strip_tags($actual_from_explode_val[0]);
	$forward_from = $actual_from_val;
	// code to get forward TO 
	$actual_to = substr($forward_response, strpos($forward_response, "To:") + 1);
    $actual_to_explode = explode('<\/b>',$actual_to);  
    $actual_to_explode_val = explode('\n',$actual_to_explode[1]);
    $actual_to_val = strip_tags($actual_to_explode_val[0]);
	$forward_to = $actual_to_val;
	// code to get forward CC 
	$actual_cc = substr($forward_response, strpos($forward_response, "Cc:") + 1);
    $actual_cc_explode = explode('<\/b>',$actual_cc);  
    $actual_cc_explode_val = explode('\n',$actual_cc_explode[1]);
    $actual_cc_val = strip_tags($actual_cc_explode_val[0]);
	$forward_cc = $actual_cc_val;  
  }else{
	// code to get forward FROM  
	$actual_from = substr($forward_response, strpos($forward_response, "From:") + 1);
    $actual_from1 = strip_tags($actual_from);
    $actual_from_explode = explode('rom:',$actual_from1);    
    $actual_from_explode_val = explode('\n',$actual_from_explode[1]);    
    $actual_from_val = strip_tags($actual_from_explode_val[0]);
	$forward_from = $actual_from_val;  
	// code to get forward TO 
	$actual_to = substr($forward_response, strpos($forward_response, "To:") + 1);
    $actual_to_explode = explode('<\/b>',$actual_to);  
    $actual_to_explode_val = explode('\n',$actual_to_explode[1]);
    $actual_to_val = strip_tags($actual_to_explode_val[0]);
	$forward_to = $actual_to_val;
	// code to get forward CC 
	$actual_cc = substr($forward_response, strpos($forward_response, "Cc:") + 1);
    $actual_cc_explode = explode('<\/b>',$actual_cc);  
    $actual_cc_explode_val = explode('\n',$actual_cc_explode[1]);
    $actual_cc_val = strip_tags($actual_cc_explode_val[0]);
	$forward_cc = $actual_cc_val;  
	//file_put_contents('message.txt', $agent_short_code.PHP_EOL , FILE_APPEND | LOCK_EX);exit;  
  }
	//file_put_contents('message.txt', $agent_short_code.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
}
$to = $inc_wp_response->to;
$toRec = json_encode($toRec);
$ccRec = json_encode($ccRec);
//SK Code
$explode = explode('<',$from);
$exp = explode('>',$explode[1]);
$from=$exp[0].$exp[1];
$from = str_replace(' ', '', $from);
//SK Code End
//$element_data = array('action' => 'add_notAssigned_tickets','from'=>$from,'message'=>$message,'to'=>$to,'to_mail'=>$toRec,'cc_mail'=>$ccRec,'subject'=>$subject,'attachments'=>$attachmen,"ticket_reply_id"=>$message_id[0]);
$element_data = array('action' => 'add_notAssigned_tickets','from'=>$from,'message'=>$message,'to'=>$to,'to_mail'=>$toRec,'cc_mail'=>$ccRec,'subject'=>$subject,'attachments'=>$attachmen,'ticket_reply_id'=>$message_id[0],'agent_short_code'=>$agent_short_code,'forward_from'=>$forward_from,'forward_to'=>$forward_to,'forward_cc'=>$forward_cc);
$fields = array('operation' =>'ticket','moduleType' => 'ticket','api_type' => 'web', 'element_data' => $element_data);

//print_r($fields); exit;

//$log = file_put_contents('vait.txt', print_r($fields,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
$explode = explode('<',$from);
$exp = explode('>',$explode[1]);
//file_put_contents('vai.txt', $exp[0].$exp[1].PHP_EOL , FILE_APPEND | LOCK_EX);
//print_r($explode);
if($explode[0]!='Mail Delivery System '){
 $url = 'https://ticketing.mconnectapps.com/api/v1.0/index.php';
 $ch = curl_init();
//set the url, number of POST vars, POST data
 curl_setopt($ch,CURLOPT_URL, $url);
 curl_setopt($ch,CURLOPT_POST, 1);
 curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
 $result = curl_exec($ch);
 curl_close($ch);
 print_r($result);	
}

