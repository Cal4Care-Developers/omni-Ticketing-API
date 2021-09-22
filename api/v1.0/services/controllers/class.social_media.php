<?php
require __DIR__ . '/../../fb_src/Facebook/autoload.php';

class socialMedia extends restApi{
 
   function getFbLoginUrl(){
	   $fb = new Facebook\Facebook([
		   'app_id' => '228820218341303',
		   'app_secret' => 'ac799e79e3fa5bff6dbbab6a32483e06',
		   'default_graph_version' => 'v6.0',
	   ]);
	   $helper = $fb->getRedirectLoginHelper();
	   $permissions = ['manage_pages', 'publish_pages']; // optional
	   $loginUrl = $helper->getLoginUrl('https://omni.mconnectapps.com/api/v1.0/page_post.php', $permissions);
	   return $loginUrl;
    }
	
	
   function insertFbProfile($fb_data){
       	extract($fb_data);

	    $f_pages = json_decode($fb_pages);
		foreach ($f_pages as $f_page) {
			$page_id = $f_page->page_id;
			$page_name = $f_page->page_name;
				$qry ="SELECT * FROM `fb_pages_for_agents` WHERE `page_id` = '$page_id'";
			$parms = array();
			$result = $this->fetchData($qry,$parms);
			if($result['user_id'] == $user_id){
			} else {
			$fb_user_id = $this->db_insert("INSERT INTO fb_pages_for_agents( `page_id`, `page_name`, `user_id`) VALUES ('$page_id','$page_name','$user_id')", array());
			}
		}
	   
	   	$qry ="SELECT * FROM `fb_social_marketing_users` WHERE `agent_id` = '$user_id'";
		$parms = array();
        $result = $this->fetchData($qry,$parms);

	   if($result > 0){
		     $result = $this->db_query("UPDATE fb_social_marketing_users SET fb_name ='$name',fb_firstname='$first_name',fb_lastname='$last_name',fb_profile='$picture',fb_pages='$picture',fb_id='$id' where agent_id='$user_id'", array());
	   } else {
		    $fb_user_id = $this->db_insert("INSERT INTO fb_social_marketing_users(fb_name, fb_firstname, fb_lastname, fb_profile, fb_id, agent_id) VALUES ('$name','$first_name','$last_name','$picture','$id','$user_id')", array());
	   }
	   
	    $result = array("posts"=>$post_d,"user_id"=>$user_id);
        return $result;
    }  
	
	
	function getUserFbDetails($user_id){
	    $qry ="SELECT * FROM `fb_social_marketing_users` WHERE `agent_id` = '$user_id'";
		$parms = array();
        $user_profile = $this->fetchData($qry,$parms);
		$qry ="SELECT * FROM `fb_pages_for_agents` WHERE `user_id` = '$user_id'";
		$parms = array();
        $user_pages = $this->dataFetchAll($qry,$parms);
		 $result = array("user_profile"=>$user_profile,"user_pages"=>$user_pages);
        return $result;
    } 
	
	
	function postSocialMediaContents($fb_data){
			extract($fb_data);
 			$result = $this->db_insert("INSERT INTO `social_marketing_posts`( `user_id`, `page_id`, `post_content`, `post_for`) VALUES ('$user_id','$page_id','$post_content','$post_for')", array());
        return $result;
    } 
	
	function userPostsOnPage($fb_data){
		extract($fb_data);
 		$qry ="SELECT * FROM `social_marketing_posts` WHERE `user_id` = '$user_id' AND `page_id` = '$page_id'";
		$parms = array();
        $result = $this->dataFetchAll($qry,$parms);
		return $result;
    } 
	
}

?>





