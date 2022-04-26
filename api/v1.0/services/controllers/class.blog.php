<?php
class blog_ip extends restApi{
function curlDatas($data){
extract($data);
     //print_r($data);exit;
    $postdata = $data;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://cal4care.com/cms/cms_config.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);                               
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result=curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	//echo $http_code;exit;
    curl_close($curl);    
    $http_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK',201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported'); 
      //print_r($result);exit;
  if($action_info=='angular_blog_list'){    
    $arr = json_decode($result); //print_r($arr);
	$dcip_details = (array) $arr->options;
    $total_details = $arr->total;//echo $total_details;exit;
    //$offset_details = $offset;
	$count = count($dcip_details);
    $i=1;
	  //print_r($dcip_details);exit;
	  if($count > 0){
		foreach ($dcip_details as $row){
			$result_datas = (array) $row;
			extract($result_datas);	
			$options = array('sno' => $i, 'Id' => $Id, 'Title' => $Title, 'Short_Content' => $Short_Content, 'Content' => $Content, 'Image_url' => $Image_url, 'Post_url' => $Post_url,'post_date'=>$post_date,'display_name'=>$display_name);
            $dcip_array[] = $options;
            $i++;
		}
		    $status = array('status' => 'true');
        $total_array = array('Total' => $total_details);
        $offset_array = array('Offset' => $offset);  
        $dcip_array = array('post_list' => $dcip_array);
        $merge_result = array_merge($status, $total_array, $offset_array, $dcip_array);            
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	  }else{
		$status = array('status' => 'false');
        $tarray = json_encode($status);           
        print_r($tarray);exit;
	  }
  }
}
	function mconnectapps_curlDatas($data){
    extract($data);
     //print_r($data);exit;
    $postdata = $data;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://cal4care.com/cms/mconnect_blog.php");	
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                               
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);	
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
    $result=curl_exec($curl);
	$status = curl_getinfo($curl);
	//print_r($status);exit;	
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	//echo $http_code;exit;
    curl_close($curl);    
    $http_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK',201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported'); 
      //print_r($result);exit;
  if($action_info=='angular_blog_list'){    
    $arr = json_decode($result); //print_r($arr);
	$dcip_details = (array) $arr->options;
    $total_details = $arr->total;//echo $total_details;exit;
    //$offset_details = $offset;
	$count = count($dcip_details);
    $i=1;
	  //print_r($dcip_details);exit;
	  if($count > 0){
		foreach ($dcip_details as $row){
			$result_datas = (array) $row;
			extract($result_datas);	
			$options = array('sno' => $i, 'Id' => $Id, 'Title' => $Title, 'Short_Content' => $Short_Content, 'Content' => $Content, 'Image_url' => $Image_url, 'Post_url' => $Post_url,'post_date'=>$post_date,'display_name'=>$display_name);
            $dcip_array[] = $options;
            $i++;
		}
		    $status = array('status' => 'true');
        $total_array = array('Total' => $total_details);
        $offset_array = array('Offset' => $offset);  
        $dcip_array = array('post_list' => $dcip_array);
        $merge_result = array_merge($status, $total_array, $offset_array, $dcip_array);            
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	  }else{
		$status = array('status' => 'false');
        $tarray = json_encode($status);           
        print_r($tarray);exit;
	  }
  }
}

	function call4tel_curlDatas($data){
    extract($data);
     //print_r($data);exit;
    $postdata = $data;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://cal4care.com/cms/call4tel_blog.php");	
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                               
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);	
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
    $result=curl_exec($curl);
	$status = curl_getinfo($curl);
	//print_r($status);exit;	
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	//echo $http_code;exit;
    curl_close($curl);    
    $http_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK',201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported'); 
      //print_r($result);exit;
  if($action_info=='angular_blog_list'){    
    $arr = json_decode($result); //print_r($arr);
	$dcip_details = (array) $arr->options;
    $total_details = $arr->total;//echo $total_details;exit;
    //$offset_details = $offset;
	$count = count($dcip_details);
    $i=1;
	  //print_r($dcip_details);exit;
	  if($count > 0){
		foreach ($dcip_details as $row){
			$result_datas = (array) $row;
			extract($result_datas);	
			$options = array('sno' => $i, 'Id' => $Id, 'Title' => $Title, 'Short_Content' => $Short_Content, 'Content' => $Content, 'Image_url' => $Image_url, 'Post_url' => $Post_url,'post_date'=>$post_date,'display_name'=>$display_name);
            $dcip_array[] = $options;
            $i++;
		}
		    $status = array('status' => 'true');
        $total_array = array('Total' => $total_details);
        $offset_array = array('Offset' => $offset);  
        $dcip_array = array('post_list' => $dcip_array);
        $merge_result = array_merge($status, $total_array, $offset_array, $dcip_array);            
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	  }else{
		$status = array('status' => 'false');
        $tarray = json_encode($status);           
        print_r($tarray);exit;
	  }
  }
}
	
function mrvoip_curlDatas($data){
    extract($data);
     //print_r($data);exit;
    $postdata = $data;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://cal4care.com/cms/mrvoip_blog.php");	
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                               
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);	
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/html'));
    $result=curl_exec($curl);
	$status = curl_getinfo($curl);
	//print_r($status);exit;	
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	//echo $http_code;exit;
    curl_close($curl);    
    $http_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK',201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported'); 
      //print_r($result);exit;
  if($action_info=='angular_blog_list'){    
    $arr = json_decode($result); //print_r($arr);
	$dcip_details = (array) $arr->options;
    $total_details = $arr->total;//echo $total_details;exit;
    //$offset_details = $offset;
	$count = count($dcip_details);
    $i=1;
	  //print_r($dcip_details);exit;
	  if($count > 0){
		foreach ($dcip_details as $row){
			$result_datas = (array) $row;
			extract($result_datas);	
			$options = array('sno' => $i, 'Id' => $Id, 'Title' => $Title, 'Short_Content' => $Short_Content, 'Content' => $Content, 'Image_url' => $Image_url, 'Post_url' => $Post_url,'post_date'=>$post_date,'display_name'=>$display_name);
            $dcip_array[] = $options;
            $i++;
		}
		    $status = array('status' => 'true');
        $total_array = array('Total' => $total_details);
        $offset_array = array('Offset' => $offset);  
        $dcip_array = array('post_list' => $dcip_array);
        $merge_result = array_merge($status, $total_array, $offset_array, $dcip_array);            
        $tarray = json_encode($merge_result);           
        print_r($tarray);exit;
	  }else{
		$status = array('status' => 'false');
        $tarray = json_encode($status);           
        print_r($tarray);exit;
	  }
  }
}	
	
}
?>