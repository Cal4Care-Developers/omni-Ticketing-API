<?php
include_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;

class adminData   {
   
    
        private function tokenKey(){
            
            return "MjQzZGVlNDBjZDdlNzJlOTkwOTMzOGRlY2EyMGE5NGQ=";
        }
    
    
        function errorLog($file_name, $message){

                $time = date("Y-m-d H:i:s");
                $ipaddress = $_SERVER['REMOTE_ADDR'];
                $user_ip = (!isset($_SERVER['HTTP_X_SUCURI_CLIENTIP'])) ? "" : " User IP - ".$_SERVER['HTTP_X_SUCURI_CLIENTIP'];
                $log_data =  $message." on ".$time." through - server IP - ".$ipaddress.".".$user_ip ;
                $log = file_put_contents($file_name.'.txt', $log_data.PHP_EOL , FILE_APPEND | LOCK_EX);

        }
    
        function tokenValidation($token){
            
            $tokenKey =$this->tokenKey();
            
            try {
                
                $token_result = JWT::decode($token, $tokenKey, array('HS256'));
                $token_data = (array)$token_result;
                $server_name = $token_data["iss"];
                $accessData = (array)$token_data["access_data"];
                
                    
            }
            catch( Exception $e ) {
                
                 $this->errorLog("token_error", "Validation - ".$e->getMessage()." - ".$token);
		    }

            $result = array("new_token"=>$token, "access_data"=>$accessData);
        
            return $result;
            
        }


        function getAccessData($token){

            $tokenKey =$this->tokenKey();
            
            $accessData = array();
            
            try {
                
                $token_result = JWT::decode($token, $tokenKey, array('HS256'));
                $token_data = (array)$token_result;
                $accessData = (array)$token_data["access_data"];
                
                    
            }
            catch( Exception $e ) {
                
                 $this->errorLog("token_access_error", "access_data - ".$e->getMessage()." - ".$token);
            }
        
            return $accessData; 

        }
    
        function tokenGenerate($accessData,$server_name){
            
            $token = "";
            
            if(count($accessData) > 0 && $server_name != ""){
                 
                $tokenKey = $this->tokenKey();
                $issued_date = time();
                $expiration = $issued_date + 18000;
                $token_data = array("iss" => $server_name, "aud" => $server_name, "iat" => $issued_date, "nbf" => $issued_date, "exp" => $expiration, "access_data" => $accessData);
                 try {
                     
                     $token = JWT::encode($token_data, $tokenKey);
 
                 }
                 catch( Exception $e ) {
                     
                     $this->errorLog("token_error", "Generate - ".$e->getMessage()." ".json_encode($accessData));
                 }
              
            }
            
               return $token;
            
        }
    
    
        function processData(){
			
			$result["server_data"]=$_SERVER;
            $result["api_request_data"]=json_decode(file_get_contents('php://input'),true);
			return $result;
            
        }

        function errorMessage($message_type){

            if($message_type == "operation"){

                $result["code"]= "INVALID_OPERATION";
                $result["message"]= "Operation not available";
            }
            elseif($message_type == "module_type"){

                $result["code"]= "INVALID_MODULE";
                $result["message"]= "Module not available";
            }

            return $result;

            
        }


        function tokenExceptionList($moduleType, $operation){
//echo $operation;exit;
$exception_modules = array("login","mgt_console","web_chat","call","lead","daily_foods","agents","ticket","queue","chat","chat_widget","cordlife","chat_line","chat_telegram","pre_camp","wp_pay","signup","chatinternal","webinar_meeting_new","call_tarrif","tags","category");
$exception_operations = array("login","mgt_console","web_chat","call","lead","daily_foods","agents","ticket","queue","chat","chat_widget","cordlife","chat_line","chat_telegram","pre_camp","wp_pay","signup","chatinternal","add_webinar_meeting","getAll","get_webinar_configuration","add_meeting_participants","call_tarrif","tags","cms_create_ticket","category");

            if(in_array($moduleType, $exception_modules) && in_array($operation, $exception_operations)){
                $result = true;
            }
            else{
                $result = false;
            }

            return $result;

        }
	

    

            
}