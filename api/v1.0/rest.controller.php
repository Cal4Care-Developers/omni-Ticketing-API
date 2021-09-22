<?php 
include_once 'services/db.inc.php';
require __DIR__ . '/eio/vendor/autoload.php';
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;
class restApi extends Database {
	
	private $httpVersion = "HTTP/1.1";

	public $connection;


	public function __construct()
	{
		$this->connection = parent::__construct();
		
	}
	
function get_timeago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
	
function time_Ago($time) {

            $time = time() - $time; // to get the time since that moment
            $time = ($time<1)? 1 : $time;
            $tokens = array (
                31536000 => 'year',
                2592000 => 'month',
                604800 => 'week',
                86400 => 'day',
                3600 => 'hour',
                60 => 'minute',
                1 => 'second'
            );

            foreach ($tokens as $unit => $text) {
                if ($time < $unit) continue;
                $numberOfUnits = floor($time / $unit);
                return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
            }

        }
  

	
	
	
	
	
	function getServerIP(){
		
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$ipaddress = isset($_SERVER['HTTP_X_SUCURI_CLIENTIP']) ? $_SERVER['HTTP_X_SUCURI_CLIENTIP'] : $ipaddress;
		return $ipaddress;
	}
	
	function getClientIP() {
		
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function setHttpHeaders($contentType, $statusCode){
		
		$statusMessage = $this->getHttpStatusMessage($statusCode);
		
		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
		header("Content-Type:". $contentType);
	}
	
	function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}


	function monitoryData($qry,$params){

		$qry = trim($qry);
		$qry_arr = explode(" ", str_replace(array("(", " "), " ", $qry));
		$qry_type = strtolower($qry_arr[0]);
		 if($qry_type != "select"){

			$table_name = $qry_type == "update" ? $qry_arr[1] : $qry_arr[2];
			$log_message = $qry_type."-".$table_name;
			$this->errorLog("log_data",$log_message);
		 }
	
	}

	function db_query($qry, $params){
		//$this->monitoryData($qry, $params);
		$stmt =  $this->connection->prepare($qry);
		$result = $stmt->execute($params);
		return $result;

	}
    
    function db_insert($qry, $params){
		//$this->monitoryData($qry, $params);
		$stmt =  $this->connection->prepare($qry);
		$result = $stmt->execute($params);
        $record_id = $result == 1 ? $this->connection->lastInsertId() : 0;
		return $record_id;

	}
	
	function dataFetchAll($qry, $params){
		$stmt = $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function dataRowCount($qry, $params){
		$stmt =  $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->rowCount();

	}

	function fetchOne($qry, $params){
	
		$stmt =  $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->fetchColumn();

	}
    function fetchmydata($qry, $params){
	
		$stmt =  $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->fetchColumn();

	}
	function fetchData($qry, $params){

		$stmt =  $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->fetch(PDO::FETCH_ASSOC);

	}
	
    function generateQryData($pass_data){

        $result = array();
        $key_name = "";
        $key_value = "";
        foreach($test  as $pass_key=>$pass_value){
            $key_name=$pass_key;
            $key_value=":".$pass_key;
            $result[$key_name][0] = $key_name;
            $result[$key_name][1] = $key_value;

        }

        return $result;

    }

    function generateCreateQry($pass_data, $table_name){

        $key_name = "";
        $key_value = "";
        foreach($pass_data  as $pass_key=>$pass_value){

            $key_name.=$pass_key.",";
            $key_value.=":".$pass_key.",";
        }
        $key_name = rtrim($key_name,",");
        $key_value = rtrim($key_value,",");

       return "Insert into ".$table_name." (".$key_name.") values (".$key_value.")";

    }
	
    function generateUpdateQry($pass_data,$table_name){

        $qry_data = "";
        foreach($pass_data  as $pass_key=>$pass_value){

            $qry_data.=$pass_key."=:".$pass_key.",";

        }
		 $qry_data = rtrim($qry_data,",");
        return "UPDATE ".$table_name." SET ".$qry_data." where ";

    }
	
	function currentTimeDb(){
        
			$dt= date('Y-m-d H:i:s');
        
        	return $dt;
        
     }
	
	
	 function curl_access($url,$method,$post_data,$header){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);                                                   
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POST,1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		 curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$result=curl_exec($curl);
		curl_close($curl); 
		return $result;
	}
    
    
    function sendMessage($body_content, $phone_number){
	

        $postdata = http_build_query(
					  array(
				        'user' => 'cal4care',
				        'password' => 'rediff',
				        'phonenumber' => $phone_number,
				        'text' => $body_content,
				        'gsm_sender' => 'otp-cal4',
				        'cdma_sender' => '6582986675',
				        'action' => 'send'
					   )
		);
		$result_data = $this->curl_access("http://bzzsms.com/sendsms.php","POST",$postdata,array("Content-type: application/x-www-form-urlencoded"));
		$result_data = str_replace($mobile_number,"",$result_data);
		$result_messsage=explode(":",$result_data);
        
        return $result_data;
	
    }

	function generateOtp($access_id,$name,$mobile_number,$auth_from){
		
		$otp_token = md5($mobile_number.time());
		$auth_code = mt_rand(100000, 999999);
		$auth_data = array("auth_from"=>$auth_from,"auth_code"=>$auth_code,"otp_token"=>$otp_token, "status"=>3, "created_dt"=>$this->currentTimeDb(), "user_id"=>$access_id);
		
		$auth_qry = $this->generateCreateQry($auth_data, "authentication_data");
		
		$result_auth = $this->db_insert($auth_qry,$auth_data);
		
		if($result_auth > 0){
		
            $body_content = "One Time Password from Call4drivers. %0a". $auth_code." %0aTo User:".$name;
            $mobile_number = $mobile_number;
            $this->sendMessage($body_content,$mobile_number);
			
		}
        else{
            $otp_token = "";
            
        }

		return $otp_token;

	}
    
    
    
    function updateAuthOtp($auth_id,$status){
		
          $result_auth =  $this->db_query("UPDATE authentication_data SET status=:status where auth_id = :auth_id", array("status"=>$status, "auth_id"=>$auth_id));


		return $result_auth;

	}
	
	function qryData($limit,$order_by_type,$offset){
		
		$limit = $limit == "" || $limit == 0 ? 50 : $limit;
		$order_by_type = $order_by_type == "" ? "desc" : $order_by_type;
		$offset = $offset == "" ? 0 : $offset;
		
		return array("limit"=>$limit, "order_by_type"=>$order_by_type, "offset"=>$offset);
	
	}
    
	
	function generateRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$~^*';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}


	function qryGenerateAllwithAccess($qry,$qry_column,$userKey,$sort,$search_text,$search_column,$start,$limit){

			$users = new users();
			extract($users->userDetails($userKey));
			$qry_parms = array();
			$qry_details = $qry;

			if($user_type != 1){
			 	$qry_details .=" and ".$qry_column."=:admin_city ";
			 	$qry_parms["admin_city"] = $admin_city;
			}

			if(count($search_column) > 0 && $search_text != ""){

				$qry_details .=" and (";

				$result_count = 1;

				foreach ($search_column as $column_data) {

					$qry_condition = $result_count == 1 ? "" : " or ";
					$qry_details .= $qry_condition.$column_data."'%:".$column_data."'% ";
					$qry_parms[$column_data] = $search_text;

				}
				$qry_details .=")";

			}

		 	$qry_details .=" ORDER BY ".$sort." limit ".$start.", ".$limit;

			$result['qry'] = $qry_details;
			$result['parms'] = $qry_parms;


		return $result;

	}

	function qryGenerateAll($qry,$sort,$search_text,$search_column,$start,$limit){

			$qry_parms = array();
			$qry_details = $qry;

			if(count($search_column) > 0 && $search_text != ""){

				$qry_details .=" and (";

				$result_count = 1;

				foreach ($search_column as $column_data) {

					$qry_condition = $result_count == 1 ? "" : " or ";
					$qry_details .= $qry_condition.$column_data."'%:".$column_data."'% ";
					$qry_parms[$column_data] = $search_text;

				}
				$qry_details .=")";

			}
			$qry_details .=" ORDER BY ".$sort." limit ".$start.", ".$limit;

			$result['qry'] = $qry_details;
			$result['parms'] = $qry_parms;


		return $result;

	}
	function fetchsingledata($qry, $params){

		$stmt =  $this->connection->prepare($qry);
		$stmt->execute($params);
		return $stmt->fetch(PDO::FETCH_ASSOC);

	}
	
	function send_reply($access_token='',$reply='')
    {     
		//$access_token="EAADQHFvU57cBAOX2eZA1FZBlTIWx4B0gUiZBBuxdSA8awyGSwHPZBdxWJJo5fJz3VDAtsI2ejspCyQy4TI2hNkZBFnjOmGA59Ug9Pp49Psitfk7jeN8zhY9sNUNmA0bdXnBqpS771z22JFAZBld30ZCOOBNXLtiJx994ZBtxbY6T3Y1Ha2WO6dox31eVXEnYUdIZD";
        $url="https://graph.facebook.com/v6.0/me/messages?field=id,name&access_token=$access_token";
        $ch=curl_init();
        $headers= array("content-type:application/json");
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$reply);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);     
        $st=curl_exec($ch);
      //  print_r($st);exit;
        $result=json_decode($st,TRUE);
        return $result;
    }
	function fax_curl(){
    	$url = 'https://myfax.mconnectapps.com/api/authenticate';
		$data = array("username" =>"admin@ictcore.org","password" =>"helloAdmin");
		$postdata = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		 $result = json_decode($result);
		// print_r($result);
		$b_Token = $result->token;
		return $b_Token;
    }
	function curl_fax_pid($postdata1,$b_Token){
		//echo $b_Token;exit;
    	$url = 'https://myfax.mconnectapps.com/api/programs/sendfax';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Authorization: Bearer ' .$b_Token
	        ));
	    $result = curl_exec($ch);
		//print_r($result);exit;
	    curl_close($ch);	    
	    $p_id = $result;
		return $p_id;
    }
    function curl_fax_tid($postdata2,$b_Token){
    	$url = 'https://myfax.mconnectapps.com/api/transmissions';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata2);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Authorization: Bearer '.$b_Token
	        ));
	    $result = curl_exec($ch);
	    curl_close($ch);	    
	    $t_id = $result;
	    return $t_id;
    }
	function curl_fax_transmission($t_id,$b_Token){
    	$url = 'https://myfax.mconnectapps.com/api/transmissions/'.$t_id.'/send';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Authorization: Bearer '.$b_Token
	        ));
	     $result = curl_exec($ch);
	     curl_close($ch);	     
	     //print_r($result);
    }
	function curl_fax_transmissions($t_id,$b_Token){
    	//$url = 'https://myfax.mconnectapps.com/api/transmissions/'.$t_id.'';
		$url = 'https://myfax.mconnectapps.com/api/transmissions?transmission_id='.$t_id.'';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json',
	        'Authorization: Bearer '.$b_Token
	        ));
	     $result = curl_exec($ch);
	     $res =json_decode($result, true);
	     curl_close($ch);
		 return $res;
	     //print_r($res);exit;
    }
	function zipfile_archive_update1($zipfile_path,$user_id,$survey_vid){
		$filename = basename($zipfile_path);		
		$updated_zipfile_path = ('updated/'.$filename);
    	$zip = new ZipArchive;
							//echo phpInfo(); exit;
        $fileToModify = 'Sources/Callflow.cs';
        $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify);   
        $oldmanifest = $zip->getFromName($modifymanifest);      
        //Modify contents:
        $newContents = str_replace('AdminID.VariableValueHandler = () => { return "64"; };','AdminID.VariableValueHandler = () => { return "'.$user_id.'"; };', $oldContents);
			$newmanifest  = str_replace('<extension>200</extension>','<extension>'.$survey_vid.'</extension>', $oldmanifest);
        //Delete the old...
        $zip->deleteName($fileToModify);
	    $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
        //echo $zipfile_path;		
		//echo $updated_zipfile_path;
        copy($zipfile_path, $updated_zipfile_path);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        return $return_url.$updated_zipfile_path;			
        } else {
        $message = 'failed';
        return $message;
		}
    }
	function zipfile_archive_update2($zipfile_path,$user_id,$survey_vid){
		$filename = basename($zipfile_path);	
    	$zip = new ZipArchive;
        $fileToModify = 'Sources/Callflow.cs';
        $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify);    
        $oldmanifest = $zip->getFromName($modifymanifest);      
        //Modify contents:
        $newContents = str_replace('AdminID.VariableValueHandler = () => { return "'.$user_id.'"; };','AdminID.VariableValueHandler = () => { return "64"; };', $oldContents);
			$newmanifest  = str_replace('<extension>'.$survey_vid.'</extension>','<extension>200</extension>', $oldmanifest);
        //Delete the old...
        $zip->deleteName($fileToModify);
	    $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
        return $zipfile_path;
        } else {
        $message = 'failed';
        return $message;
		}
    }
		function pd_zipfile_archive_update1($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid){
	//	echo $pd_zipfile_path;echo $user_id;exit;		
		$filename = basename($pd_zipfile_path);
		$updated_zipfile_path = ('updated/'.$cmp_id.'.zip');
    	$zip = new ZipArchive;
        $fileToModify = 'Sources/Callflow.cs';
	    $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
			 
          $oldContents = $zip->getFromName($fileToModify);  
         $oldmanifest = $zip->getFromName($modifymanifest);       
        //Modify contents:
       $newContents = str_replace('string predictiveDialerQueue = "240";','string predictiveDialerQueue = "'.$cmp_id.'";', $oldContents);
       $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "240"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);
       $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; }','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; }', $newContents);
		$newmanifest  = str_replace('<extension>300</extension>','<extension>'.$camp_vid.'</extension>', $oldmanifest);
			
			
		//echo $oldContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify);
        $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
		copy($pd_zipfile_path, $updated_zipfile_path);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        return $return_url.$updated_zipfile_path;	
        //return $zipfile_path;
        } else {
        $message = 'failed';
        return $message;
		}
    }
function pd_zipfile_archive_update2($pd_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid){
		$filename = basename($pd_zipfile_path);	
    	$zip = new ZipArchive;
        $fileToModify = 'Sources/Callflow.cs';
	    $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify);     
         $oldmanifest = $zip->getFromName($modifymanifest);      
        //Modify contents:
        $newContents = str_replace('string predictiveDialerQueue = "'.$cmp_id.'";','string predictiveDialerQueue = "240";', $oldContents);
        $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "240"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
        $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; }','Prefix.VariableValueHandler = () => { return "Pre"; }', $newContents);
		$newmanifest  = str_replace('<extension>'.$camp_vid.'</extension>','<extension>300</extension>', $oldmanifest);
        //Delete the old...
        $zip->deleteName($fileToModify);
        $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
        return $pd_zipfile_path;
        } else {
        $message = 'failed';
        return $message;
		}
    }
	function hardware_curl($api_request_data){
		//print_r($api_request_data);exit;
    	$url = 'https://dev.cal4care.com/erp/api_erp/v1.0/';				
		$ch = curl_init();
		curl_setopt_array($ch, array(
		    CURLOPT_URL => $url,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => json_encode($api_request_data)
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		//print_r($output);exit;
		return $output;
    }
	function hardware_status_curl($api_request_data){
		//print_r(json_encode($api_request_data));exit;
    	$url = 'https://dev.cal4care.com/erp/api_erp/v1.0/';
		//$url = 'https://erp.cal4care.com/cms/apps/omni_file.php';
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
		    CURLOPT_POSTFIELDS => json_encode($api_request_data)
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		//print_r($output);exit;
		return $output;
    }
	function add_fax_user_curl($data,$token){
		//print_r($data);exit;
        $url = 'https://myfax.mconnectapps.com/api/users';
		$postdata = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);	//print_r($result);exit;				
		curl_close($ch);
		if($result=='{"error":{"code":417,"message":"User creation failed"}}'){
			$user_id = 0;
			return $user_id;
		}else{
		    $user_id = $result;		
		    return $user_id;
		}
		
    }
    function add_fax_user_role($userid,$token){
    	$url = 'http://myfax.mconnectapps.com/api/users/'.$userid.'/roles/1';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		curl_close($ch);
		//print_r ($response);
		return $res;
		//echo "<pre>";
		//print_r($res);
		//echo "<pre>";
    }
	public function update_fax_user_curl($token, $fax_user_id, $data){
    	$url = 'http://myfax.mconnectapps.com/api/users/'.$fax_user_id;		
		$postdata = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS,  $postdata );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		$res =json_decode($response, true);
		curl_close($ch);
		return $res;
    }
	public function delete_fax_user_curl($token, $fax_user_id){
		//echo $fax_user_id;exit;
    	$url = 'https://myfax.mconnectapps.com/api/users/'.$fax_user_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS,  $data_json );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		//$res =json_decode($response, true);
		curl_close($ch);
		//print_r($response);exit;
		return $response;
    }
	function add_fax_didnumber($data,$token){
    	$url = 'https://myfax.mconnectapps.com/api/dids';		
		$postdata = json_encode($data);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);
		curl_close($ch);				
		$did_id = $result;
		return $did_id;
    }
	function get_didnumber_data($didid,$token){
    	$url='https://myfax.mconnectapps.com/api/accounts/'.$didid;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);
		$res =json_decode($result);
		curl_close($ch);
		$did_acc_id=$res->account_id;
        $user_id=$res->user_id;
        return $user_id;
    }
    function update_didnumber_data($data,$didid,$token){		
    	$url='https://myfax.mconnectapps.com/api/accounts/'.$didid;
		$data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,  $data_json );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		$res =json_decode($response);
		curl_close($ch);
		$did_acc_id=$res->account_id;
		return $did_acc_id;
    }
	function delete_didnumber_data($didid,$token){
    	$url = 'http://myfax.mconnectapps.com/api/accounts/'.$didid;		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		curl_close($ch);
		return $response;
    }
	function user_assign_ddidnumber($didid,$selected_user_id,$token){
    	 $url = 'http://myfax.mconnectapps.com/api/accounts/'.$didid.'/users/'.$selected_user_id;
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		 'Content-Type: application/json',
		 'Authorization: Bearer '.$token
		 ));
		 $response  = curl_exec($ch);
		 curl_close($ch);
		 return $response;
    }
	function fax_curl_admin($user_name,$password){
		//echo $user_name;exit;
		//$user_name = 'fax 12345';
    	$url = 'https://myfax.mconnectapps.com/api/authenticate';
		$data = array("username" =>$user_name,"password" =>$password);
		$postdata = json_encode($data);	
		//print_r($postdata);exit;	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		$res = curl_exec($ch);//print_r($res);exit;
		curl_close($ch);
		$result = json_decode($res);//print_r($result);exit;		 
		$token = $result->token;//echo $token;exit;
		return $token;
    }
    function get_admin_did_number_curl($token){
    	$url='https://myfax.mconnectapps.com/api/dids';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);
		$res =json_decode($result);
		curl_close($ch);
		return $res;
    }
	function assign_email_didnumber_curl($data,$didid,$token){
    	$url='https://myfax.mconnectapps.com/api/accounts/'.$didid;
		$data_json = json_encode($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS,  $data_json );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$response  = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($response);
		//print_r($res);
		$email = $res->email;
		return $email;
    }
	function receive_fax_curl($token){
    	$url = 'https://myfax.mconnectapps.com/api/transmissions?direction=inbound&service_flag=2';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);
		$res =json_decode($result);
		curl_close($ch);
		//echo "<pre>";
		//print_r($res);		
		//foreach ($res as $key) {			
			//echo $key->transmission_id.' '.$key->contact_phone.' '.$key->status;
		    //echo "<pre>";
		//}
		return $res;
    }
	function receive_fax_document_curl($tid,$token){
    	$url='http://myfax.mconnectapps.com/api/transmissions/'.$tid.'/results?name=document';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Authorization: Bearer '.$token
		));
		$result = curl_exec($ch);
		$res =json_decode($result);
		curl_close($ch);
		//echo "<pre>";
		//print_r($res);
		$doc_data=$res['0']->data;
		//echo $doc_data;
		return $doc_data;
    }
	
	
	function pro_zipfile_archive_update1($pro_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid){
		//echo $pd_zipfile_path;echo $user_id;exit;
		$filename = basename($pro_zipfile_path);
		//$updated_zipfile_path = ('dialer/pro/'.$filename);
		$updated_zipfile_path = ('dialer/pro/'.$cmp_id.'.zip');
    	$zip = new ZipArchive;
        $fileToModify = 'Sources/Callflow.cs';
	    $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
         $oldContents = $zip->getFromName($fileToModify);     
         $oldmanifest = $zip->getFromName($modifymanifest);     
        //Modify contents:
			 $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "241"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);
       $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);      
       $newContents = str_replace('string predictiveDialerQueue = "241";','string predictiveDialerQueue = "'.$cmp_id.'";', $newContents);
			$newmanifest  = str_replace('<extension>301</extension>','<extension>'.$camp_vid.'</extension>', $oldmanifest);
		
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify);
        $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
		copy($pro_zipfile_path, $updated_zipfile_path);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        return $return_url.$updated_zipfile_path;	
        //return $zipfile_path;
        } else {
        $message = 'failed';
        return $message;
		}
    }
	function pro_zipfile_archive_update2($pro_zipfile_path,$user_id,$cmp_id,$cmp_pre,$camp_vid){
		$filename = basename($pro_zipfile_path);	
    	$zip = new ZipArchive;
        $fileToModify = 'Sources/Callflow.cs';
	    $modifymanifest = 'manifest.xml';
        if ($zip->open('./mrvoip/'.$filename) === TRUE) {
        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify);     
         $oldmanifest = $zip->getFromName($modifymanifest);      
        //Modify contents:
        $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "241"; };', $oldContents);
        $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
		 $newContents = str_replace('string predictiveDialerQueue = "'.$cmp_id.'";','string predictiveDialerQueue = "241";', $newContents);
			$newmanifest  = str_replace('<extension>'.$camp_vid.'</extension>','<extension>301</extension>', $oldmanifest);
		
        //Delete the old...
        $zip->deleteName($fileToModify);
        $zip->deleteName($modifymanifest);
        //Write the new...
        $zip->addFromString($fileToModify, $newContents);
        $zip->addFromString($modifymanifest, $newmanifest);
        //And write back to the filesystem.
        $zip->close();
        return $pd_zipfile_path;
        } else {
        $message = 'failed';
        return $message;
		}
    }
	function bd_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid){
		//echo $cmp_2;echo $user_id;echo $cmp_1;exit;
		 $aud=basename($broadcast_audio);
		$filename_camp1 = basename($cmp_1);
		$filename_camp2 = basename($cmp_2);
		$updated_zipfile_path_camp2 = ('dialer/bd/'.$filename_camp2);
		$updated_zipfile_path_camp1 = ('dialer/bd/'.$cmp_id.'.zip');
    	$zip = new ZipArchive;
        $fileToModify_camp1 = 'Sources/Callflow.cs';	
	    $modifymanifest1 = 'manifest.xml';
        $fileToModify_camp2 = 'Sources/Callflow.cs';	
	    $modifymanifest2 = 'manifest.xml';		
         $audio_path= './mrvoip/broadcast/'.$aud;
        if ($zip->open('./mrvoip/'.$filename_camp2) === TRUE) {
			// rename Audio file inside Zip
			$zip->renameName('Audio/Greetings.wav', 'Audio/Greetings1.wav');
			//Copy Uploded audio file
			$zip->addFile($audio_path, 'Audio/Greetings.wav');
	
        //Read contents into memory
         $oldContents = $zip->getFromName($fileToModify_camp2);   
         $oldmanifest2 = $zip->getFromName($modifymanifest2);   
        //Modify contents:
        $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "242"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);
      // $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);
			$newmanifest2  = str_replace('<extension>242</extension>','<extension>'.$cmp_id.'</extension>', $oldmanifest2);
      
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify_camp2);
        $zip->deleteName($modifymanifest2);
        //Write the new...
        $zip->addFromString($fileToModify_camp2, $newContents);
        $zip->addFromString($modifymanifest2, $newmanifest2);
        //And write back to the filesystem.
        $zip->close();
		copy($cmp_2, $updated_zipfile_path_camp2);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message2= $return_url.$updated_zipfile_path_camp2;	
			
        //return $zipfile_path;
        }
		if ($zip->open('./mrvoip/'.$filename_camp1) === TRUE) {			
        //Read contents into memory
          $oldContents = $zip->getFromName($fileToModify_camp1);    
         $oldmanifest1 = $zip->getFromName($modifymanifest1);      
        //Modify contents:
       $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "242"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);
			$newContents = str_replace(' string predictiveDialerQueue = "242";',' string predictiveDialerQueue = "'.$cmp_id.'";', $newContents);
     $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);
			$newmanifest1  = str_replace('<extension>302</extension>','<extension>'.$camp_vid.'</extension>', $oldmanifest1);
      
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
		copy($cmp_1, $updated_zipfile_path_camp1);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message1= $return_url.$updated_zipfile_path_camp1;	
        //return $zipfile_path;
        }
		$result['camp_1']=$message1;
		$result['camp_2']=$message2;
		return $result;
    }
	function bd_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid){
		 $filename_cmp_1 = basename($cmp_1);
		$filename_cmp_2 = basename($cmp_2);
    	$zip = new ZipArchive;
       $fileToModify_camp1 = 'Sources/Callflow.cs';		
	    $modifymanifest1 = 'manifest.xml';
        $fileToModify_camp2 = 'Sources/Callflow.cs';	
	    $modifymanifest2 = 'manifest.xml';	
        if ($zip->open('./mrvoip/'.$filename_cmp_1) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
       $oldContents = $zip->getFromName($fileToModify_camp1);    
       $oldmanifest1 = $zip->getFromName($modifymanifest1);       
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "242"; };', $oldContents);
			$newContents = str_replace(' string predictiveDialerQueue = "'.$cmp_id.'";',' string predictiveDialerQueue = "242";', $newContents);
        $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
			$newmanifest1  = str_replace('<extension>'.$camp_vid.'</extension>','<extension>302</extension>', $oldmanifest1);
		
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
        //return $cmp_1;
        } 
		if ($zip->open('./mrvoip/'.$filename_cmp_2) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
			$zip->deleteName('Audio/Greetings.wav');
			$zip->renameName('Audio/Greetings1.wav', 'Audio/Greetings.wav');
       $oldContents = $zip->getFromName($fileToModify_camp2);        
       $oldmanifest2 = $zip->getFromName($modifymanifest2);   
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "242"; };', $oldContents);
       //$newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('AdminID.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
			$newmanifest2  = str_replace('<extension>'.$cmp_id.'</extension>','<extension>242</extension>', $oldmanifest2);
		
        //Delete the old...
        $zip->deleteName($fileToModify_camp2);
        $zip->deleteName($modifymanifest2);
        //Write the new...
        $zip->addFromString($fileToModify_camp2, $newContents);
        $zip->addFromString($modifymanifest2, $newmanifest2);
        //And write back to the filesystem.
        $zip->close();
        return $cmp_2;
        }
    }
	function bs_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid){
		//echo $cmp_2;echo $user_id;echo $cmp_1;exit;
		 $aud=basename($broadcast_audio);
		$filename_camp1 = basename($cmp_1);
		$filename_camp2 = basename($cmp_2);
		$updated_zipfile_path_camp2 = ('dialer/bs/'.$filename_camp2);
		$updated_zipfile_path_camp1 = ('dialer/bs/'.$cmp_id.'.zip');
    	$zip = new ZipArchive;
        $fileToModify_camp1 = 'Sources/Callflow.cs';	
	    $modifymanifest1 = 'manifest.xml';		
        $fileToModify_camp2 = 'Sources/Callflow.cs';
		$modifymanifest2 = 'manifest.xml';		
         $audio_path= './mrvoip/broadcast/'.$aud;
        if ($zip->open('./mrvoip/'.$filename_camp2) === TRUE) {
			//echo'123';exit;
			// rename Audio file inside Zip
			$zip->renameName('Audio/Greetings.wav', 'Audio/Greetings1.wav');
			//Copy Uploded audio file
			$zip->addFile($audio_path, 'Audio/Greetings.wav');
	
        //Read contents into memory
         $oldContents = $zip->getFromName($fileToModify_camp2); 
         $oldmanifest2 = $zip->getFromName($modifymanifest2);    
        //Modify contents:
        $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "243"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);
      // $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);
      $newmanifest2  = str_replace('<extension>243</extension>','<extension>'.$cmp_id.'</extension>', $oldmanifest2);
      
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify_camp2);
        $zip->deleteName($modifymanifest2);
        //Write the new...
        $zip->addFromString($fileToModify_camp2, $newContents);
        $zip->addFromString($modifymanifest2, $newmanifest2);
        //And write back to the filesystem.
        $zip->close();
		copy($cmp_2, $updated_zipfile_path_camp2);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message2= $return_url.$updated_zipfile_path_camp2;	
			
        //return $zipfile_path;
        }
		if ($zip->open('./mrvoip/'.$filename_camp1) === TRUE) {	
			//echo '123';exit;
        //Read contents into memory
          $oldContents = $zip->getFromName($fileToModify_camp1);  
         $oldmanifest1 = $zip->getFromName($modifymanifest1);    
        //Modify contents:
       $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "243"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);   
     $newContents = str_replace('string predictiveDialerQueue = "243";','string predictiveDialerQueue = "'.$cmp_id.'";', $newContents); 
     $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "64"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents); 
			$newmanifest1 = str_replace('<extension>303</extension>','<extension>'.$camp_vid.'</extension>', $oldmanifest1);
      
      
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
		copy($cmp_1, $updated_zipfile_path_camp1);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message1= $return_url.$updated_zipfile_path_camp1;	
        //return $zipfile_path;
        }
		$result['camp_1']=$message1;
		$result['camp_2']=$message2;
		return $result;
    }
	function bs_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid){
		 $filename_cmp_1 = basename($cmp_1);
		$filename_cmp_2 = basename($cmp_2);
    	$zip = new ZipArchive;
       $fileToModify_camp1 = 'Sources/Callflow.cs';		
	    $modifymanifest1 = 'manifest.xml';	
        $fileToModify_camp2 = 'Sources/Callflow.cs';	
	    $modifymanifest2 = 'manifest.xml';		
        if ($zip->open('./mrvoip/'.$filename_cmp_1) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
       $oldContents = $zip->getFromName($fileToModify_camp1);   
         $oldmanifest1 = $zip->getFromName($modifymanifest1);      
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "243"; };', $oldContents);
			   
     $newContents = str_replace('string predictiveDialerQueue = "'.$cmp_id.'";','string predictiveDialerQueue = "243";', $newContents); 
        $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
			$newmanifest1 = str_replace('<extension>'.$camp_vid.'</extension>','<extension>303</extension>', $oldmanifest1);
      
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
        //return $cmp_1;
        } 
		if ($zip->open('./mrvoip/'.$filename_cmp_2) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
			$zip->deleteName('Audio/Greetings.wav');
			$zip->renameName('Audio/Greetings1.wav', 'Audio/Greetings.wav');
       $oldContents = $zip->getFromName($fileToModify_camp2);        
       $oldmanifest2 = $zip->getFromName($modifymanifest2);  
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "242"; };', $oldContents);
      // $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "64"; };', $newContents);
			$newmanifest2 = str_replace('<extension>'.$cmp_id.'</extension>','<extension>243</extension>', $oldmanifest2);
      
        //Delete the old...
        $zip->deleteName($fileToModify_camp2);
        $zip->deleteName($modifymanifest2);
        //Write the new...
        $zip->addFromString($fileToModify_camp2, $newContents);
        $zip->addFromString($modifymanifest2, $newmanifest2);
        //And write back to the filesystem.
        $zip->close();
        return $cmp_2;
        }
    }
	function notification_curl($notification_code,$chat_id,$chat_msg){
				$host_name='https://'.$_SERVER['HTTP_HOST'];

		$url = "https://fcm.googleapis.com/fcm/send";	    
	    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
	    $title = "New Whatsapp Message From Omni channel";
	    $body = "Hello I am from Your php server";
		$click_url = $host_name.'/#/wp?c='.$chat_id;
	    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'whatsapp', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
	    $arrayToSend = array('to' => $notification_code, 'notification' => $notification,'priority'=>'high');
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
	    $response = curl_exec($ch);
	    //Close request
	    if ($response === FALSE) {
	    die('FCM Send Error: ' . curl_error($ch));
	    }
	    curl_close($ch);
	}
	function wpunoff_notification_curl($notification_code,$chat_id,$chat_msg,$wpinst_id){
		
	}
	
	function fb_notification_curl($token,$chat_id,$chat_msg){
		//$host_name='https://'.$_SERVER['HTTP_HOST'];
		     /*$url = "https://fcm.googleapis.com/fcm/send";    
   $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New Facebook message";
    $body = "Hello I am from Your php server";
	$click_url = $host_name.'/#/fb?c='.$chat_id;
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'fb', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
    $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);*/
		
$host_name='https://omni-ticketing-xcupb.ondigitalocean.app';
$click_url = $host_name.'/#/fb-chat'; 
$title = "New Facebook message";
$socket = "https://myscoket.mconnectapps.com:4031";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();
    $client->emit('broadcast', ['title' =>$title, 'notification_for'=>'fb', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name,'user_id'=>$token]);
	}
	
	
	function call_notification_curl($token,$callid,$call_id){
	$host_name='https://'.$_SERVER['HTTP_HOST'];

	 $url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "Receiving Incomming ".$call_id;
    $body = "Hello I am from Your php server";
	$click_url = $host_name.'/#/';
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'Call', 'click_action'=>'', 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
    $response = curl_exec($ch);
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    //return null;
	}
	function line_notification_curl($token,$chat_id,$chat_msg){
			$host_name='https://'.$_SERVER['HTTP_HOST'];

		    $url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New Line message";
    $body = "Hello I am from Your php server";
		$chat_id =base64_encode($chat_id);
	$click_url = $host_name.'/#/line-chat?l='.$chat_id;
		$chat_id =base64_decode($chat_id);
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'line', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
	function telegram_notification_curl($users,$chat_id,$chat_msg){
$host_name='https://omni-ticketing-xcupb.ondigitalocean.app';
		    
  
    $title = "New Telegram message";   
	//$chat_id =base64_encode($chat_id);
$click_url = 'https://omni-ticketing-xcupb.ondigitalocean.app/#/tele-chat?c='.$chat_id;
$title =  $chat_msg;
$socket = "https://myscoket.mconnectapps.com:4031";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();	
    $client->emit('broadcast', ['title' =>$title, 'notification_for'=>'telegram', 'click_action'=>$click_url, 'unique_id'=>$chat_id,'sound' =>'default', 'badge' => '1','host_name'=>$host_name,'user_id'=>$users]);
	}
	
	function chat_notification_curl($token,$chat_id,$chat_msg){
			$host_name='https://'.$_SERVER['HTTP_HOST'];

		    $url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New Chat message";
    $body = "Hello I am from Your php server";
	$click_url = $host_name.'/#/chat?c='.$chat_id;
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'chat', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
	function internalchat_notification_curl($token,$chat_id,$chat_msg){
		    $host_name='https://'.$_SERVER['HTTP_HOST'];
		    $url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $title = "New INT Chat message";
    $body = "Hello I am from Your php server"; 
	$click_url = $host_name.'/#/internal-chat?c='.$chat_id;
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'int_chat', 'click_action'=>$click_url, 'unique_id'=>$chat_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}
	function upload_receive_fax_document_curl($doc_id,$token){
    	$username='admin@ictcore.org';
		$password='helloAdmin';
		$URL='https://myfax.mconnectapps.com/api/messages/documents/'.$doc_id.'/media';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		$result=curl_exec ($ch);//print_r($result);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close ($ch);
		$base_pdf=base64_encode($result);
		 $decode_pdf=base64_decode($base_pdf);
		$filename = 'document'.$doc_id.'.pdf';
		//echo $filename;exit;
		$filepath = "https://".$_SERVER['SERVER_NAME']."/api/v1.0/fax_received_documents/".$filename;
		$pdf = fopen($filepath, 'rt');
		fwrite($pdf,$decode_pdf);
		fclose($pdf);
		return $filepath;
    }
	function line_reply_curl($access_token,$data){
		//print_r($data);exit;
    	$url = 'https://api.line.me/v2/bot/message/push';		
		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$response = curl_exec($ch);//print_r($response);exit;
		curl_close($ch);
		return $response;
    }
	function erp_curl($license_key){
		//print_r($license_key);exit;
   
		// $url = 'https://erp.cal4care.com/mconnect/chklicensekey.php';	
		// $postfields = array('action'=>'check_license_mconnect', 'license_key'=>$license_key);

		// $ch = curl_init($url);
		// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		// $response = curl_exec($ch);
		// $error = curl_error($ch);
		// $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// curl_close($ch);
		// echo $error;
        // print_r($result); exit;	
        
        $ch = curl_init();

        //curl_setopt($ch, CURLOPT_URL, 'https://erp.cal4care.com/mconnect/chklicensekey.php');
		curl_setopt($ch, CURLOPT_URL, 'https://erp.cal4care.com/mconnect/checkhardwareId.php?action=chkHardwareId&qshwidValue=eee');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $post = array(
            'action' => 'check_license_mconnect',
            'license_key' => $license_key,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = array();
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        print_r($result);
    }
	
	function sendAppNotification($token, $title,$content,$type,$src){
		
		//echo $content; exit;
	$headings = array(  "en" => $title );
    $content = array( "en" => $content);
		if($type == 'chat'){
		  $fields = array(
            'app_id' => "be5130be-2398-4f8e-a382-c04ec4c4cea8",
            'include_player_ids' => array("$token"),
			'headings' => $headings,
			'large_icon' => "https://omni.mconnectapps.com/assets/images/favicon.png",
            'content_available' => true,
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
} else {
	
  $fields = array(
            'app_id' => "be5130be-2398-4f8e-a382-c04ec4c4cea8",
            'include_player_ids' => array("$token"),
			'headings' => $headings,
			'large_icon' => "https://omni.mconnectapps.com/assets/images/favicon.png",
	  		'big_picture' => $src,
            'content_available' => true,
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
}
      
    
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
		return $response;
	}	

	/*
function wpn_curl($token,$chat_id,$chat_msg,$wpinst_id){
	 		$host_name='https://'.$_SERVER['HTTP_HOST'];
		    $url = "https://fcm.googleapis.com/fcm/send";    
    $serverKey = 'AAAAlrml_wQ:APA91bEMvlOCRrVf66vl3JT9yegGdm1nu3Zx_xcoa58ZAMdP9xn0yHRNiYeVHiRTmzXGGC5oedHZY6kUpZ8WEXdZzcYGO_NFBGK0DRljHLyUY0hBSLqq-kDzRQ00oa7a4863bztXSRva';
    $wpinst_id= base64_encode($wpinst_id);    
	$chat_id =base64_encode($chat_id);

	$title = "New Whatsapp message";
    $body = "Hello I am from Your php server";
	$click_url = $host_name.'/#/wp-unoff?c='.$chat_id.'&wp_id='.$wpinst_id;
	$chat_id =base64_decode($chat_id);
    $notification = array('title' =>$title, 'text' => $body, 'notification_for'=>'whatsapp_unoff', 'click_action'=>$click_url, 'unique_id'=>$chat_id,'wp_id'=>$wpinst_id, 'sound' =>'default', 'badge' => '1','icon' => 'https://omni.mconnectapps.com/api/v1.0/profile_image/deve.jpg');
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
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
	}

*/
function wpn_curl($token,$chat_id,$chat_msg,$wpinst_id){
	 		$host_name='https://'.$_SERVER['HTTP_HOST'];		  
    $wpinst_id= base64_encode($wpinst_id);    
	$chat_id =base64_encode($chat_id);

	
	$click_url = 'https://omni-ticketing-xcupb.ondigitalocean.app/#/wp-unoff?c='.$chat_id.'&wp_id='.$wpinst_id;
	$chat_id =base64_decode($chat_id);  
			
$host_name='https://omni-ticketing-xcupb.ondigitalocean.app';
$title =  $chat_msg;
$socket = "https://myscoket.mconnectapps.com:4031";
      $options = [
          'context' => [
              'ssl' => [
                  'verify_peer' => false,
                   'verify_peer_name' => false
              ]
          ]
      ];

    $client = new Client(new Version1X($socket,$options));
    $client->initialize();	
    $client->emit('broadcast', ['title' =>$title, 'notification_for'=>'whatsapp_unoff', 'click_action'=>$click_url, 'unique_id'=>$chat_id,'wp_id'=>$wpinst_id, 'sound' =>'default', 'badge' => '1','host_name'=>$host_name,'user_id'=>$token]);
	}
	function rak_zipfile_archive_update1($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$broadcast_audio,$camp_vid,$parallel,$frequency,$receive_number){
		//echo $broadcast_audio;exit;
		// $cmp_2;echo $user_id;echo $cmp_1;exit;
		 $aud=basename($broadcast_audio);
		$filename_camp1 = basename($cmp_1);
		 $filename_camp2 = basename($cmp_2);
		$updated_zipfile_path_camp2 = ('dialer/rak/'.$filename_camp2);
		$updated_zipfile_path_camp1 = ('dialer/rak/'.$filename_camp1);
    	$zip = new ZipArchive;
        $fileToModify_camp1 = 'Sources/Callflow.cs';	
	    $modifymanifest1 = 'manifest.xml';
        $fileToModify_camp2 = 'Sources/Callflow.cs';	
	    $modifymanifest2 = 'manifest.xml';		
          $audio_path= './mrvoip/broadcast/'.$aud;
        $res = $zip->open('./mrvoip/rak/'.$filename_camp2);
		if ($res === TRUE) {
			if($aud!=''){
    		$zip->renameName('Audio/Greetings.wav','Audio/Greetings1.wav');
			$zip->addFile($audio_path, 'Audio/Greetings.wav');
				}
			
			//Read contents into memory
			 $oldContents2 = $zip->getFromName($fileToModify_camp2);   
             $oldmanifest2 = $zip->getFromName($modifymanifest2);   
			
			 $newContents2 = str_replace('Campaignid.VariableValueHandler = () => { return "311"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents2);
		
      // $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents2 = str_replace('Adminid.VariableValueHandler = () => { return "564"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents2);
			$newmanifest2  = str_replace('<extension>311</extension>','<extension>'.$receive_number.'</extension>', $oldmanifest2);
			
			$zip->deleteName($fileToModify_camp2);
            $zip->deleteName($modifymanifest2);
			
			 $zip->addFromString($fileToModify_camp2, $newContents2);
        $zip->addFromString($modifymanifest2, $newmanifest2);
   			 $zip->close();
			} else {
			echo 'failed, code:' . $res;
			}
		copy($cmp_2, $updated_zipfile_path_camp2);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message2= $return_url.$updated_zipfile_path_camp2;	
		
		if ($zip->open('./mrvoip/rak/'.$filename_camp1) === TRUE) {			
        //Read contents into memory
          $oldContents = $zip->getFromName($fileToModify_camp1);    
         $oldmanifest1 = $zip->getFromName($modifymanifest1);    
			
        //Modify contents:
       $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "311"; };','Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };', $oldContents);
			//echo $newContents;exit;
			$newContents = str_replace(' string predictiveDialerQueue = "311";',' string predictiveDialerQueue = "'.$cmp_id.'";', $newContents);
     $newContents = str_replace('Prefix.VariableValueHandler = () => { return "Pre"; };','Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };', $newContents);
       $newContents = str_replace('Adminid.VariableValueHandler = () => { return "564"; };','Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };', $newContents);
       $newContents = str_replace('ReceivedVAD.VariableValueHandler = () => { return "311"; };','ReceivedVAD.VariableValueHandler = () => { return "'.$receive_number.'"; };', $newContents);
       $newContents = str_replace('int parallelDialers = 1;','int parallelDialers = '.$parallel.';', $newContents);
       $newContents = str_replace('int pauseBetweenDialerExecution = 3;','int pauseBetweenDialerExecution = '.$frequency.';', $newContents);
			$newmanifest1  = str_replace('<extension>310</extension>','<extension>'.$camp_vid.'</extension>', $oldmanifest1);
      
		//echo $newContents; 	exit;
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
		copy($cmp_1, $updated_zipfile_path_camp1);
		$return_url = 'https://'.$_SERVER["SERVER_NAME"].'/api/v1.0/';	
        $message1= $return_url.$updated_zipfile_path_camp1;	
        //return $zipfile_path;
        }
		$result['camp_1']=$message1;
		$result['camp_2']=$message2;
		return $result;
    }


	function rak_zipfile_archive_update2($cmp_1,$cmp_2,$user_id,$cmp_id,$cmp_pre,$camp_vid,$parallel,$frequency,$receive_number){
		 $filename_cmp_1 = basename($cmp_1);
		$filename_cmp_2 = basename($cmp_2);
    	$zip = new ZipArchive;
       $fileToModify_camp1 = 'Sources/Callflow.cs';		
	    $modifymanifest1 = 'manifest.xml';
        $fileToModify_camp2 = 'Sources/Callflow.cs';	
	    $modifymanifest2 = 'manifest.xml';	
        if ($zip->open('./mrvoip/rak/'.$filename_cmp_1) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify_camp1);    
       $oldmanifest1 = $zip->getFromName($modifymanifest1);       
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "311"; };', $oldContents);
			$newContents = str_replace(' string predictiveDialerQueue = "'.$cmp_id.'";',' string predictiveDialerQueue = "311";', $newContents);
        $newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('Adminid.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "564"; };', $newContents);
       $newContents = str_replace('int parallelDialers = '.$parallel.';','int parallelDialers = 1;', $newContents);
       $newContents = str_replace('int pauseBetweenDialerExecution = '.$frequency.';','int pauseBetweenDialerExecution = 3;', $newContents);
       $newContents = str_replace('ReceivedVAD.VariableValueHandler = () => { return "'.$receive_number.'"; };','ReceivedVAD.VariableValueHandler = () => { return "311"; };', $newContents);
			$newmanifest1  = str_replace('<extension>'.$camp_vid.'</extension>','<extension>310</extension>', $oldmanifest1);
		
        //Delete the old...
        $zip->deleteName($fileToModify_camp1);
        $zip->deleteName($modifymanifest1);
        //Write the new...
        $zip->addFromString($fileToModify_camp1, $newContents);
        $zip->addFromString($modifymanifest1, $newmanifest1);
        //And write back to the filesystem.
        $zip->close();
        //return $cmp_1;
        } 
		if ($zip->open('./mrvoip/rak/'.$filename_cmp_2) === TRUE) {
			//echo'123';exit;
        //Read contents into memory
			$zip->deleteName('Audio/Greetings.wav');
			$zip->renameName('Audio/Greetings1.wav', 'Audio/Greetings.wav');
       $oldContents = $zip->getFromName($fileToModify_camp2);        
       $oldmanifest2 = $zip->getFromName($modifymanifest2);   
        //Modify contents:
         $newContents = str_replace('Campaignid.VariableValueHandler = () => { return "'.$cmp_id.'"; };','Campaignid.VariableValueHandler = () => { return "311"; };', $oldContents);
       //$newContents = str_replace('Prefix.VariableValueHandler = () => { return "'.$cmp_pre.'"; };','Prefix.VariableValueHandler = () => { return "Pre"; };', $newContents);
        $newContents = str_replace('AdminID.VariableValueHandler = () => { return "'.$user_id.'"; };','Adminid.VariableValueHandler = () => { return "564"; };', $newContents);
			$newmanifest2  = str_replace('<extension>'.$receive_number.'</extension>','<extension>311</extension>', $oldmanifest2);
				//echo $newmanifest2;exit;
	
        //Delete the old...
        $zip->deleteName($fileToModify_camp2);
        $zip->deleteName($modifymanifest2);
        //Write the new...
        $zip->addFromString($fileToModify_camp2, $newContents);
        $zip->addFromString($modifymanifest2, $newmanifest2);
        //And write back to the filesystem.
        $zip->close();
        //return $cmp_2;
        }
    }
	
	
function CalculateTime($time1, $time2) {
      $time1 = date('H:i:s',strtotime($time1));
      $time2 = date('H:i:s',strtotime($time2));
      $times = array($time1, $time2);
      $seconds = 0;
      foreach ($times as $time)
      {
        list($hour,$minute,$second) = explode(':', $time);
        $seconds += $hour*3600;
        $seconds += $minute*60;
        $seconds += $second;
      }
      $hours = floor($seconds/3600);
      $seconds -= $hours*3600;
      $minutes  = floor($seconds/60);
      $seconds -= $minutes*60;
      if($seconds < 9)
      {
      $seconds = "0".$seconds;
      }
      if($minutes < 9)
      {
      $minutes = "0".$minutes;
      }
        if($hours < 9)
      {
      $hours = "0".$hours;
      }
      return "{$hours}:{$minutes}:{$seconds}";
    }
function sec_time($time, $div) {
$time=$time;
$ex=explode(':',$time);
$h='3600';
$m='60';
$div=$div;
$seconds = $h*$ex[0]+$m*$ex[1]+$ex[2];
$x=$seconds/$div;
return $avg=gmdate("H:i:s", $x);
	}
}
?>