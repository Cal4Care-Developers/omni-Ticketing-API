<?php
class billing extends restApi{ 
	function insert_billing_det($data){
		extract($data);
		$qry = "INSERT INTO billing_det(biller_code, enq_code, biller_name, address,city,state,country,zip_code,phone,email,fax,tax_per,tax_name) VALUES ('$biller_code', '$enq_code', '$biller_name', '$address','$city','$state','$country','$zip_code','$phone','$email','$fax','$tax_per','$tax_name')";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
		
function view_billing_det(){
	//extract($data);
		$qry = "SELECT * FROM billing_det";
		$result= $this->dataFetchAll($qry, array());
						  return $result;
}
	function update_billing_det($data){
		//print_r($data);exit;
	extract($data);
		 $qry = "UPDATE billing_det set biller_code='$biller_code',enq_code='$enq_code',biller_name='$biller_name', address='$address',city='$city',state='$state',country='$country',zip_code='$zip_code',phone='$phone',email='$email', fax='$fax',tax_per='$tax_per',tax_name='$tax_name' where id='$id'";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;	
		
}
	function edit_billing_det($data){
        extract($data);
       $qry = "SELECT * FROM billing_det where id='$id'";
        $result= $this->dataFetchAll($qry, array());
        return $result;
    }
function curlData($data){
	//print_r($data);exit;
	$post_data=json_encode($data);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://dev.cal4care.com/erp/data-services/index.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);                                                   
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $out=curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl); 

	  
      $http_status = array(100 => 'Continue', 101 => 'Switching Protocols', 200 => 'OK',201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 306 => '(Unused)', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported');
 
      $success_result["server_status"] = $http_code;
      $success_result["server_message"] = urlencode($http_status[$http_code]);
      $success_result["server_result"] = $out;
$api_data = json_decode($out);
	print_r($api_data);exit;
	$banking_charge = (array) $api_data->result_data->banking_charge;
	//print_r($banking_charge);exit;
	 extract($data);
	 extract($banking_charge);
        $banking_charge_value = sprintf('%.2f', 0);
        $banking_charge_percentage = 0;
        if($selected_rdo == 'payment_type_paypal_checkout'){
            
          $paypal_one = $banking_charge_status == 1 ? $cus_banking_charge : $paypal_one;

                    $banking_charge = $total_amount * ($paypal_one/100);
                    $banking_charge_percentage = $paypal_one;
                    $banking_charge_value = sprintf('%.2f', $banking_charge);
                    $total_amount = $total_amount + $banking_charge_value;

            }
        elseif($selected_rdo == 'payment_type_ocbc_sg_payment'){
             $ocbc_sg = $banking_charge_status == 1 ? $cus_banking_charge : $ocbc_sg;
            $banking_charge_percentage = $ocbc_sg;
            $banking_charge = $total_amount * ($ocbc_sg/100);
            $banking_charge_value = sprintf('%.2f', $banking_charge);
            $total_amount = $total_amount + $banking_charge_value;
        }
		
	 elseif($selected_rdo == 'payment_type_ocbc_my_payment'){
             $ocbc_my = $banking_charge_status == 1 ? $cus_banking_charge : $ocbc_my;
            $banking_charge_percentage = $ocbc_my;
            $banking_charge = $total_amount * ($ocbc_my/100);
            $banking_charge_value = sprintf('%.2f', $banking_charge);
            $total_amount = $total_amount + $banking_charge_value;
        }
		
		 elseif($selected_rdo == 'payment_type_2checkout'){
              $check_out_trans = $banking_charge_status == 1 ? $cus_banking_charge : $check_out_trans;
            $banking_charge_percentage = $check_out_trans;
            $banking_charge = $total_amount * ($check_out_trans/100);
            $banking_charge_value = sprintf('%.2f', $banking_charge);
            $total_amount = $total_amount + $banking_charge_value;
        }
		 elseif($selected_rdo == 'payment_type_stripe_pay'){
              $stripe_trans = $banking_charge_status == 1 ? $cus_banking_charge : $stripe_pay;
            $banking_charge_percentage = $stripe_trans;
            $banking_charge = $total_amount * ($stripe_trans/100);
            $banking_charge_value = sprintf('%.2f', $banking_charge);
            $total_amount = $total_amount + $banking_charge_value;
        }

        else{
            
            $banking_charge_percentage = 0;
        }
$result['percent']=$banking_charge_percentage;
$result['total_amount']=$total_amount;
	return $result;

  }
function add_to_cart($data){
		extract($data);
	 $get_item="SELECT * FROM cart where admin_id='$admin_id' and status='0' and item_name='$item_name'";
	$count=$this->dataRowCount($get_item,array());
	if($count<=0){
		$qry = "INSERT INTO cart(admin_id, item_name, price, quantity,tot_amt) VALUES ('$admin_id', '$item_name', '$price', '$quantity','$tot_amt')";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
	}
	else{
		$result='2';
	}
		return $result;
}
	
function view_cart($data){
	extract($data);
		$qry = "SELECT * FROM cart where admin_id='$admin_id' and status='0'";
		$result= $this->dataFetchAll($qry, array());
	    return $result;
}
function delete_from_cart($data){
	extract($data);
	 $qry = "DELETE from cart where id IN ($id)";
        $parms = array();
        $result = $this->db_query($qry,$parms);
        return $result;
}	
		function add_billing_address($data){
		extract($data);
		$qry = "INSERT INTO billing_address(cmp_name, contact_person, phone_no, email,alt_email,alt_phone,address1,address2,city,state,country,admin_id) VALUES ('$cmp_name', '$contact_person', '$phone_no', '$email','$alt_email','$alt_phone','$address1','$address2','$city','$state','$country','$admin_id')";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;
}
		
function edit_cart($data){
	extract($data);
		 $qry = "SELECT * FROM cart where admin_id='$admin_id' and id='$id'";
		$result= $this->dataFetchAll($qry, array());
	    return $result;
}
	function update_cart($data){
		//print_r($data);exit;
	extract($data);
		 $qry = "UPDATE cart set price='$price'  where id='$id' and admin_id='$admin_id'";
		$qry_result= $this->db_query($qry, array());
		$result = $qry_result == 1 ? 1 : 0;
		return $result;	
		
}
	}
