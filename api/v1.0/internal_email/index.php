<?php
ini_set('display_errors',0);
//error_reporting(E_ALL);
$inc_wp_response = file_get_contents('php://input',true);
//file_put_contents('test.txt', $inc_wp_response.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
$forward_response = file_get_contents('php://input',true);
$inc_wp_response = json_decode($inc_wp_response);
$s = $inc_wp_response->message_id;
$message_id = preg_split('/\s*<([^>]*)>/', $s, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
$from = $inc_wp_response->from;
$subject = $inc_wp_response->subject;
//file_put_contents('test.txt', $subject.PHP_EOL , FILE_APPEND | LOCK_EX);exit;
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

$to = $inc_wp_response->to;
$toRec = json_encode($toRec);
$ccRec = json_encode($ccRec);
$element_data = array('action' => 'add_InternalMail','from'=>$from,'message'=>$message,'to'=>$to,'to_mail'=>$toRec,'cc_mail'=>$ccRec,'subject'=>$subject,'attachments'=>$attachmen,'ticket_reply_id'=>$message_id[0],'forward_from'=>$forward_from,'forward_to'=>$forward_to,'forward_cc'=>$forward_cc);
$fields = array('operation' =>'ticket','moduleType' => 'ticket','api_type' => 'web', 'element_data' => $element_data);
$explode = explode('<',$from);
$exp = explode('>',$explode[1]);
//file_put_contents('test.txt', print_r($fields,true).PHP_EOL , FILE_APPEND | LOCK_EX);exit;
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

