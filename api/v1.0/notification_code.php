<?php
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = "fpJTlcARsKVy2MUzcQZ21h:APA91bE9lMxQuOEIt1jPTqz45jqa7FsdirY-vVgdJ1664H5FJTgai2bSVusR9GegJGdo2wURSub5dc24FybvuqUpS_ZZzLJXyDGdkrpEoa9fxnrbAp6afJcaM7Vzwiw6ytZEWPDGsKp4";
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New Chat From Omni channel";
    $body = "Hello I am from Your php server";
    $for = 'whatsapp';
    $id = '12345';
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'whatsapp', 'unique_id'=>'121', 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    curl_exec($ch);
    //Close request
   
    curl_close($ch);
?>

