<?php
// echo "123";exit;
$result_data["status"] = true;  
$category = new addcategory(); 
if($_REQUEST['action']){ $action = $_REQUEST['action'];}
// echo $_REQUEST; exit;
// if($_REQUEST['action'] == 'fileupload') {  
//     $action ='fileupload'; 
//     $post_title = $_REQUEST['post_title'];
//     $status = $_REQUEST['status']; 
//     $category_id = $_REQUEST['category_id'];
//     $subcat_id = $_REQUEST['subcat_id']; 
//     $post_content = $_REQUEST['post_content'];
//     $quote_content = $_REQUEST['quote_content']; 
//     $extension = $_REQUEST['extension'];       
//     $filename=$_FILES['filename']['name'];
// }
if($action == "category"){
	$data = array("category_name"=>$category_name,"description"=>$description);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->category($data);
}
else if($action == "sub_category"){
	$data = array("category_id"=>$category_id,"sub_category_name"=>$sub_category_name,);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->sub_category($data);
}
else if($action == "post"){
	$data = array("post_title"=>$post_title,"video_link"=>$video_link,"status"=>$status,"category_id"=>$category_id,"subcat_id"=>$subcat_id,"post_content"=>$post_content,"display_type"=>$display_type,"post_by"=>$post_by);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->post($data);
}
else if($action == "select_sub_category"){
	$data = array("category_id"=>$category_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->select_sub_category($data);
}
else if($action == "edit"){
	$data = array("post_title"=>$post_title,"video_link"=>$video_link,"status"=>$status,"category_id"=>$category_id,"subcat_id"=>$subcat_id,"post_content"=>$post_content,"id"=>$id,"post_by"=>$post_by);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->edit($data);
}
else if($action == "update_doc_post"){
	$data = array("post_title"=>$post_title,"status"=>$status,"category_id"=>$category_id,"subcat_id"=>$subcat_id,"post_content"=>$post_content,"id"=>$id,"display_type"=>$display_type,"featured"=>$featured);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->update_doc_post($data);
}
else if($action == "delete"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->delete($data);
}
else if($action == "editpage"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->editpage($data);
}
else if($action == "editpage_document"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->editpage_document($data);
}


else if($action == "selectcategory"){
	$data = array();
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->selectcategory($data);
}
else if($action == "display"){
	$data = array();
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->display($data);
}
else if($action == "get_kb_list"){
	$data = array("search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->get_kb_list($data);
}
else if($action == "getfile"){
	$data = array("subcat_id"=>$subcat_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->getfile($data);
}
else if($action == "getsearch"){
	$data = array("search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->getsearch($data);
}

else if($action == "kb_file_upload"){
    extract($_REQUEST);
    //  print_r($_REQUEST);exit;
    $data = array("post_title"=>$post_title,"status"=>$status,"category_id"=>$category_id,"subcat_id"=>$subcat_id,"post_content"=>$post_content,"quote_content"=>$quote_content,"extension"=>$extension,"post_by"=>$post_by,"display_type"=>$display_type,"featured"=>$featured);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->kb_file_upload($data);
}
else if($action == "displaying"){
   // $data = array("filename"=>$filename);
	//$data = array("id"=>$id,"post_title"=>$post_title);
	$data = array("post_title"=>$post_title);
    //print_r($data);exit;
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->displaying($data);;
}
else if($action == "delete_category"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->delete_category($data);
}
	else if($action == "get_articles"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->get_articles($data);
}else if($action == "toggle_category_status"){
	$data = array("id"=>$id,"status"=>$status);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->toggle_category_status($data);
}
else if($action == "editCategory"){
	$data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->editCategory($data);
}
else if($action == "Updatecategory"){
	$data = array("id"=>$id,"category_name"=>$category_name,"description"=>$description);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->Updatecategory($data);
}
else if($action == "get_articles_search"){
	$data = array("search_data"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->get_articles_search($data);
}
else if($action == "get_featured_list"){
	//$data = array("search_data"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->get_featured_list();
}
else if($action == "ShowSinglecategory"){
	 $data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->ShowSinglecategory($data);
} 
	else if($action == "delete_sub_category"){
	$data = array("name"=>$name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->delete_sub_category($data);
}
else if($action == "update_sub_category"){
	$data = array("name"=>$name,"update_name"=>$update_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->update_sub_category($data);
}
else if($action == "getYTList"){
	//$api_url = 'https://www.googleapis.com/youtube/v3/search?key=AIzaSyASOVKdUSpFdG1of-sHiB1awwWnpFpyeE8&channelId=UCBgkvrZPWYVQKlmyh1WmZoQ&part=snippet,id&order=date&maxResults=10';
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $category->getYTList($api_url);
}
else if($action == "get_list"){
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->get_list();
}
else if($action == "get_private_articles"){
   $data = array("category_id"=>$category_id,"subcategory_id"=>$subcategory_id);
    $result_data["result"]["status"] = true;
    $result_data["result"] = $category->get_private_articles($data);
}
?>