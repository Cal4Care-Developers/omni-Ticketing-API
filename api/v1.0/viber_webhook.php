<?php 
$request = file_get_contents("php://input");
$input = json_decode($request, true);
if($input['event'] == 'webhook') {
  $webhook_response['status']=0;
  $webhook_response['status_message']="ok";
  $webhook_response['event_types']='delivered';
  echo json_encode($webhook_response);
  die;
}
else if($input['event'] == "subscribed") {
  echo 'subscribed';
  // when a user subscribes to the public account
}
else if($input['event'] == "conversation_started"){
  echo 'conversation_started';
  // when a conversation is started
}
elseif($input['event'] == "message") {
  /* when a user message is received */
  $type = $input['message']['type']; //type of message received (text/picture)
  $text = $input['message']['text']; //actual message the user has sent
  $sender_id = $input['sender']['id']; //unique viber id of user who sent the message
  $sender_name = $input['sender']['name']; //name of the user who sent the message
 
  echo $sender_name;
  $data['auth_token'] = "4c5bb27658000f38-45714f515755fc6b-b86322e9f90303b6";
  $data['receiver'] = $sender_id;
  $data['text'] = "Hi from c4c";
  $data['type'] = 'text';
  //here goes the curl to send data to user
  $ch = curl_init("https://chatapi.viber.com/pa/send_message");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  $result = curl_exec($ch);
  print_r($result);
}
?>
