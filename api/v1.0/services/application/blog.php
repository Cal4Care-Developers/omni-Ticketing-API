<?php
$api_user = 'Cal4careCms';
$api_pass = '16c21a5c08758dc10dadb11b7c8cc182';
$blog_ip = new blog_ip();
if($action == "blog_lists"){
    $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"blog_page_list","action_info"=>"angular_blog_list", "customer_id"=>base64_decode($customer_id), "limit" => $limit, "offset" => $offset); 
    $result_data["result"]["status"] = true;   
    $result_data["result"]["data"] = $blog_ip->curlDatas($data);
}
if($action == "mconnectapps_blog_lists"){
    $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"blog_page_list","action_info"=>"angular_blog_list", "customer_id"=>base64_decode($customer_id), "limit" => $limit, "offset" => $offset); 
	//print_r($data);exit;
    $result_data["result"]["status"] = true;   
    $result_data["result"]["data"] = $blog_ip->mconnectapps_curlDatas($data);
}
if($action == "mrvoip_blog_lists"){
    $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"blog_page_list","action_info"=>"angular_blog_list", "customer_id"=>base64_decode($customer_id), "limit" => $limit, "offset" => $offset); 
	//print_r($data);exit;
    $result_data["result"]["status"] = true;   
    $result_data["result"]["data"] = $blog_ip->mrvoip_curlDatas($data);
}
if($action == "call4tel_blog_lists"){

    $data = array("api_user"=>$api_user, "api_pass"=>$api_pass, "access"=>"cms_site", "page_access"=>"blog_page_list","action_info"=>"angular_blog_list", "customer_id"=>base64_decode($customer_id), "limit" => $limit, "offset" => $offset); 
	//print_r($data);exit;
    $result_data["result"]["status"] = true;   
    $result_data["result"]["data"] = $blog_ip->call4tel_curlDatas($data);
}
?>