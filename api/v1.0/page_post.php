<?php
session_start();
require_once __DIR__ . '/fb_src/Facebook/autoload.php'; 

$fb = new Facebook\Facebook([
  'app_id' => '228820218341303',
  'app_secret' => 'ac799e79e3fa5bff6dbbab6a32483e06',
  'default_graph_version' => 'v6.0',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['manage_pages', 'publish_pages']; // optional

try {
	if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error validation: ' . $e->getMessage();

  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an validation error: ' . $e->getMessage();
  	exit;
 }

if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		echo "code getted";
		header('Location: https://devomni.mconnectapps.com/api/v1.0/page_post.php');
	}
   ?>
	<form action="" method="POST">
      <button name=logout>Logout</button>
	</form>
	<?php

// ........validating the access token......
	// try {
	// 	$request = $fb->get('/me');
	// } catch(Facebook\Exceptions\FacebookResponseException $e) {
	// 	// When Graph returns an error
	// 	if ($e->getCode() == 190) {
	// 		unset($_SESSION['facebook_access_token']);
	// 		$helper = $fb->getRedirectLoginHelper();
	// 		$loginUrl = $helper->getLoginUrl('https://apps.facebook.com/APP_NAMESPACE/', $permissions);
	// 		echo "<script>window.top.location.href='".$loginUrl."'</script>";
	// 	}
	// 	exit;
	// } catch(Facebook\Exceptions\FacebookSDKException $e) {
	// 	// When validation fails or other local issues
	// 	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	// 	exit;
	// }

	// getting all photos of user
	try {
	 	$photos_request = $fb->get('/me/photos?limit=10&type=uploaded');
		$photos = $photos_request->getGraphEdge()->asArray();
		print_r($photos);
	 } catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
	 	echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	 	// When validation fails or other local issues
	 	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	 	exit;
	}



	// getting basic info about user
	try {
		$profile_request = $fb->get('/me?fields=name,first_name,last_name,birthday,picture,website,location');
		$profile = $profile_request->getGraphNode()->asArray();
	echo "<pre>";
	print_r($profile);
	echo "<pre>";
		
		$fb_id=$profile->id;
				echo $fb_id;

		/*$fb_user_name=$response->name;
        $fb_first_name=$profile->first_name;
	    $fb_last_name=$profile->last_name;
        $fb_pro_picture=$profile->picture->url;*/


		
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to  login page
		header("Location:https://devomni.mconnectapps.com/api/v1.0/page_post.php");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	// post on behalf of page
	$pages = $fb->get('/me/accounts');
	$pages = $pages->getGraphEdge()->asArray();
	echo "<pre>";
    print_r($pages);
	echo "<pre>";
	
	

	
	// foreach ($pages as $key) {
	// 	$post_page='OmniChannel';
	// 	if ($key['name'] == $post_page) {
	// 		$post = $fb->post('/' . $key['id'] . '/feed', array('message' => 'just for testing...'), $key['access_token']);
	// 		$post = $post->getGraphNode()->asArray();
	// echo "<pre>";
	// 		print_r($post);
	// echo "<pre>";
	// 	}
	// }

// getting all posts published by user
	try {
		$posts_request = $fb->get('/me/posts?fields=attachments,message');
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$total_posts = array();
	$posts_response = $posts_request->getGraphEdge();
	if($fb->next($posts_response)) {
		$response_array = $posts_response->asArray();
		$total_posts = array_merge($total_posts, $response_array);
		while ($posts_response = $fb->next($posts_response)) {
			$response_array = $posts_response->asArray();
			$total_posts = array_merge($total_posts, $response_array);
		}
		print_r($total_posts);
	} else {
		$posts_response = $posts_request->getGraphEdge()->asArray();
		//print_r($posts_response);
	}


if(isset($_POST['logout'])){
	 session_destroy();
	
	echo '<script type="text/javascript">location.reload(true);</script>';

 }

  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('https://devomni.mconnectapps.com/api/v1.0/page_post.php', $permissions);
	echo $loginUrl;
	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}