<?php 
   $my_verify_token="SS11EE22LL";
   $challenge=$_GET['hub_challenge'];
   $verify_token=$_GET['hub_verify_token'];
   if($my_verify_token === $verify_token)
   {
       echo $challenge;
       exit;
   }
 
  
        $response =file_get_contents("php://input");   
        file_put_contents("FB_text.json",$response);
        $response=json_decode($response,true);   
        $sender_id= $response['entry'][0]['messaging'][0]['sender']['id']; 
        $recipient=$response['entry'][0]['messaging'][0]['recipient']['id']; 
        $message= $response['entry'][0]['messaging'][0]['message']['text'];
        $message_attachment= $response['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['url'];
        $type = $response['entry'][0]['messaging'][0]['message']['attachments'][0]['type'];
        $message_id =  $response['entry'][0]['messaging'][0]['message']['mid'];
        $page_id=$response['entry'][0]['id'];  

        /*echo  $sender_id ;
        echo "\n";
        echo  $message;
        echo "\n";
        echo  $recipient ;
        echo "\n"; 
        echo  $page_id ;
        echo "\n";*/
        

         $a_url="https://graph.facebook.com/v6.0/$page_id?fields=name,picture,access_token&access_token=EAADQHFvU57cBANAiYZCZA2tk8JGF5qywxEFtzWeDfcKpZB5DLavLrL0ebTDZAxeSmPXSCv9txT5uaQvzjpSZCGFSVwWAs2iRAqDuAZCusUuAUxjzDbEVGiUZAKtcCEpEqi9Yocpg4b4Ifaja6jDW1jcalMZCX2WCH6uJGPzrWEQmkTRW1J8g97pc";


//     $a_url="https://graph.facebook.com/$page_id?fields=name,picture,access_token&access_token=EAADQHFvU57cBAHZCwjnVsk8hPO01hcmY6RAZAyeGygXZCPIvBz8FNkRQopfddqSkuUW0kLjJ9i7lTgY3SOfw6rYcNup3vEBvq3IpZCjhFE11bjq3ozNZARo5HQcXPz2zZBvnqpL2e6b0OKHEJG6ZA4mrq0IIou8Vw2SRKzY4HjcVuTf9IRy4rrm";

         //07-8-2020 changed.
        //$a_url="https://graph.facebook.com/$page_id?fields=name,picture,access_token&access_token=EAADQHFvU57cBAJw4SGtXEQAWT0AE6Wyrrd8SUZAxcqQQS3ytfmgZBR0OkLXOeMNXzjy9eRtmIZCzbzWlTYddAZCqLPxM3EfHozVLguNVDhxjXQoa0FjlXsBhHymRto3Poik4mF0yIZCZA3vFQ37hc6UD9aSsDc5GZALhXronSG0cj2ip1a3bfAGEKTKWc9uD11oj5JRm4CnhLpphkJJZBG1gHMpZCpIMsz2AZD";

           //$a_url="https://graph.facebook.com/$page_id?fields=name,picture,access_token&access_token=EAADQHFvU57cBAEFkpJS8jSHmvd1tuhewuU3KRbO3Bb0f9oIrFXy5W6LBOvQbhLQ66G8mQrWTdGmXVZAewRGiNLYJbk3V1WTaH4325yUIYAJx8a0XNgM8H9oFx8hiEuMSP8tDgiZA5HDZCpbiKrAbFqIhfamZCiAMP7YxqY47B4MChPxPRjIu";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$a_url);
            $result=curl_exec($ch);
            curl_close($ch);
            $res=json_decode($result, true);
            $page_pic =$res['picture']['data']['url'];
            $page_name=$res['name'];
            $access_token=$res['access_token'];
           // echo "\n";
           // echo $page_pic;
          //  echo "\n";
           // echo $page_name;
           // echo "\n";
          //  echo $access_token;
          //  echo "\n";
          
//print_r($res);exit;

      $fburl="https://graph.facebook.com/v6.0/$sender_id?fields=first_name,last_name,picture&access_token=$access_token";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL,$fburl);
      $fbresult=curl_exec($ch);
      curl_close($ch);
      $res=json_decode($fbresult, true);
      $first_name=$res['first_name'];
      $last_name=$res['last_name'];
      $profile_pic=$res['picture']['data']['url'];
      //echo $first_name;
      //echo "\n";
      //echo $last_name;
      //echo "\n";
      //echo $profile_pic;



   /* $element_data = array('action' => 'generate_incoming_fb','sender_id'=>$sender_id,'message'=>$message,'recipient_id'=>$recipient,'first_name'=>$first_name,'last_name'=>$last_name,'profile_pic'=>$profile_pic,'page_name'=>$page_name,'page_picture'=>$page_pic,'access_token'=>$access_token); */

echo "<script> var websocket = new WebSocket('wss://myscoket.mconnectapps.com:4002/');
		websocket.onopen = function(event) { 

			console.log('open');
		}
		websocket.onmessage = function(event) {

			console.log(event);

		};
		
		websocket.onerror = function(event){
			console.log('error');
		};
		websocket.onclose = function(event){
			console.log('close');
			setTimeout(Reconnect, 1000);
		};
		var data = 'test';
		setTimeout( () => { websocket.send(data);  }, 3000);
		</script>";

 $element_data = array('action' => 'generate_incoming_fb','sender_id'=>$sender_id,'message'=>$message,'attachment' => $message_attachment,'recipient_id'=>$recipient,'first_name'=>$first_name,'last_name'=>$last_name,'profile_pic'=>$profile_pic,'page_name'=>$page_name,'page_picture'=>$page_pic,'access_token'=>$access_token);
//print_r($element_data);exit;
    $fields = array('operation'=>'wpchat','moduleType'=>'wpchat','api_type'=>'web','element_data'=>$element_data);
    $post = http_build_query($fields);
    $post_url = 'https://assaabloycc.mconnectapps.com/api/v1.0/index_new.php';
    $ch = curl_init();
    // //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $post_url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    curl_close($ch); 


  

   