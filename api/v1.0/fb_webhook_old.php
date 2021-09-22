<?php 

   $my_verify_token="12345";

   $challenge=$_GET['hub_challenge'];
   $verify_token=$_GET['hub_verify_token']; 

   if($my_verify_token === $verify_token)
   {
       echo $challenge;
       exit;
   }


   $access_token='EAADQHFvU57cBAGiDd8asGW1vXEhG2e5ZCbUnnwl4sYJwSHTr874CkuJiTMqtvbIkAZBZAGMmtgnJXHVDS92vjJLNz4BvAgojOAeoe7gLQW2Nk8QpnRZCuxc8uirWqLG8S1FMrZAzeMf3hUIuTP6G4Azfyoi6PSbpqNxtDOu2ufAZDZD';

   $response =file_get_contents("php://input");
   
    file_put_contents("FB_text.json",$response);
    $response=json_decode($response,true);
   
     $sender_id= $response['entry'][0]['messaging'][0]['sender']['id']; 

     $recipient=$response['entry'][0]['messaging'][0]['recipient']['id']; 

     $message= $response['entry'][0]['messaging'][0]['message']['text'];

     $message_id =  $response['entry'][0]['messaging'][0]['message']['mid'];

     $page_id=$response['entry'][0]['id'];  

     echo  $sender_id ;
     echo  $message;
     echo  $recipient ;
     echo  $page_id ;

    $element_data = array('action' => 'generate_incoming_fb','sender_id'=>$sender_id,'message'=>$message'recipient_id'=>$recipient);
    $fields = array('operation' =>'wpchat','moduleType' => 'wpchat','api_type' => 'web', 'element_data' => $element_data);


    $post = http_build_query($fields);
    $url = 'http://omni.mconnectapps.com/api/v1.0/index_new.php';

    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    curl_close($ch);

    print_r($result);





    // $reply_message="{
    //                'recipient': {
    //                    'id': $sender_id
    //                     },
    //                'message':{		
    //                  'text':'hello from omni'
    //                         }
    //            }";
    
    //    if(!empty($response['entry'][0]['messaging'][0]['message'])){
    //           send_reply($access_token,$reply_message);
    //     }

    //   function send_reply($access_token='',$reply='')
    //   {
        
    //       $url="https://graph.facebook.com/v6.0/me/messages?field=id,name&access_token=$access_token";
    //       $ch=curl_init();
    //       $headers= array("content-type:application/json");
    //       curl_setopt($ch, CURLOPT_URL,$url); 
    //       curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //       curl_setopt($ch, CURLOPT_POST,1);
    //       curl_setopt($ch, CURLOPT_POSTFIELDS,$reply);
    //       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    //       curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
     
    //       $st=curl_exec($ch);
    //       print_r($st);

    //       $result=json_decode($st,TRUE);
    //       return $result;
    //   }

    
