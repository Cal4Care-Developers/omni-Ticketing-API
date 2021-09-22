<?php 
$access_token='EAADQHFvU57cBAGiDd8asGW1vXEhG2e5ZCbUnnwl4sYJwSHTr874CkuJiTMqtvbIkAZBZAGMmtgnJXHVDS92vjJLNz4BvAgojOAeoe7gLQW2Nk8QpnRZCuxc8uirWqLG8S1FMrZAzeMf3hUIuTP6G4Azfyoi6PSbpqNxtDOu2ufAZDZD';
$sender_id = '300915968250191523';
   $reply_message="{
                   'recipient': {
                       'id': $sender_id
                        },
                   'message':{    
                     'text':'test'
                            }
               }";    
   send_reply($access_token,$reply_message);   
   function send_reply($access_token='',$reply='')
   {        
        $url="https://graph.facebook.com/v2.6/me/messages?access_token=$access_token";
        $ch=curl_init();
        $headers= array("content-type:application/json");
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$reply);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);     
        $st=curl_exec($ch);
        //print_r($st);
        $result=json_decode($st,TRUE);
        print_r($result); 
	   //return $result;
    }   