<?php

$result_data["status"] = true;
$sMedia = new socialMedia();
if($action == "fb_login_url"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sMedia->getFbLoginUrl();
} 
elseif($action == "store_fb_profile"){
	$fb_data = array("name"=>$name,"first_name"=>$first_name, "last_name"=>$last_name,"picture"=>$picture,"id"=>$id,"post_d"=>$posts,"user_id"=>$user_id,"fb_pages"=>$fb_pages);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sMedia->insertFbProfile($fb_data);
}
elseif($action == "getUserFbDetails") {
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sMedia->getUserFbDetails($user_id);
} 
elseif($action == "postSocialMediaContents") {
	$fb_data = array("user_id"=>$user_id,"page_id"=>$page_id, "post_content"=>$post_content,"post_for"=>$post_for);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sMedia->postSocialMediaContents($fb_data);
} 
elseif($action == "userPostsOnPage") {
	$fb_data = array("user_id"=>$user_id,"page_id"=>$page_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $sMedia->userPostsOnPage($fb_data);
}

?>









