<?php
class adminData {
    
    function loginSession(){
        
        $login_time = time() - $_SESSION['login_time'];
        
        if(isset($_SESSION['userId']) &&  isset($_SESSION['userName']) && !($login_time > 0)){
            
            $result = 1;
            
            $_SESSION['login_time']  = $_SESSION['login_time'] + 7200;
        }
        else{
            $result = 0;
            
        }
            
        return $result;
    }
    


    function sendEmail($to,$cc,$bcc,$subject,$body_content){
    
            $mail    = new PHPMailer();

            $mail->IsSMTP(); 
            $mail->Host       = $host_name; 
            $mail->SMTPAuth   = true;                
            $mail->Host       = 'smtpcorp.com'; 
           // $mail->Port       = '2525';         
           // $mail->Username   = 'erpdev';
           // $mail->Password   = 'OTc1ZTYwc3k3bHUw';     
            $mail->SetFrom("sales@cal4care.com", "sales@cal4care.com");
            $mail->AddReplyTo("sales@cal4care.com", "sales@cal4care.com");
            $mail->CharSet = 'UTF-8'; 
            $mail->Subject    = $subject;

            $mail->MsgHTML($body_content);

            if(is_array($cc)){
                foreach($cc as $emailid){
                    $mail->addCC($emailid);   
                }
            }
            else{
                $mail->addCC($cc);    
            }

            if(is_array($bcc)){
              foreach($bcc as $emailid){
                $mail->addBCC($emailid, $emailid);    
              }
            }
            else{
                $mail->addBCC($bcc);
            }

            if(is_array($to)){
              foreach($to as $emailid){
                $mail->AddAddress($emailid, $emailid);    
              }
            }
            else{
              $mail->AddAddress($to, $to);
            }


        $mail_result =   $mail->Send();

        return $mail_result;
    
    }


	        function curl_data($post_data){
				
				
            
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL,"https://omnitickets.mconnectapps.com/api/v1.0/");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);                                                   
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($curl, CURLOPT_TIMEOUT, 40);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $result=curl_exec($curl);
                curl_close($curl);
	
                return $result;

        }
    
    
}