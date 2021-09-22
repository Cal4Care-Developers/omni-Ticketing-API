<?php
 $access_token ='AHsgWqEoUuMmptHVpMWt4Jv16eJVoRQhKDl3C/3oZfR7u87OF+UVqXZ8afdBkinUSiEZ1dm9hpbXLF7f8EU82wo2wVGJvQrKaT0kFH7SXBGQL5FNDn9qqBIodcUmsZEpNzIFprgGwXUEBXzeZ5ewrQdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
file_put_contents("LINE_text.json",$content);
// Parse JSON
$events = json_decode($content, true);
$recipient_id = $events['destination'];
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {		
		if ($event['type'] == 'message' && $event['message']['type'] == 'text')
		{
		     //source
     	    $userId = $event['source']['userId'];			
			$chat_message = $event['message']['text'];
			//$reply_token = $event['replyToken'];
            // GET Profile Details
			$url = 'https://api.line.me/v2/bot/profile/'.$userId;
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
            $ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			$res =json_decode($result,true);
			curl_close($ch);
			if(array_key_exists("pictureUrl", $res)){
                 $profile_pic=$res['pictureUrl'];
            }else{
			     $profile_pic='';
			}			
			$sender_id=$res['userId'];  
            $name=$res['displayName'];		    
        }
    }
    $element_data = array('action' => 'generate_incoming_line','recipient_id'=>$recipient_id,'sender_id'=>$sender_id,'chat_message'=>$chat_message,'name'=>$name,'profile_pic'=>$profile_pic,'reply_token'=>$userId);
    $fields = array('operation'=>'chat_line','moduleType'=>'chat_line','api_type'=>'web','element_data'=>$element_data);
    $post = http_build_query($fields);
    $post_url = 'http://devomni.mconnectapps.com/api/v1.0/index_new.php';
    $ch = curl_init();
    // //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $post_url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
    $results = curl_exec($ch);
    curl_close($ch);
	//print_r($results);exit;
}
?>
