<?php
define('GOOGLE_API_KEY', 'AIzaSyASOVKdUSpFdG1of-sHiB1awwWnpFpyeE8');
class addcategory extends restApi{
public function category ($data){
    extract($data);
	//Check Category counts as default 16 allowed
	$qry = "SELECT COUNT(id) FROM category";
	$count= $this->fetchOne($qry, array());
	//echo $qry;exit;
	//echo $count;exit;
	if($count >=16){
			    $result = 3;
				return $result;exit;
	}
    $qry = "SELECT * FROM category where category_name LIKE '%$category_name%'";
		$result = $this->fetchData($qry, array());		
		 if($result > 0){
			 	$result = 2;
				return $result;exit;
		 }else {
    $sql="INSERT INTO category (category_name,description) VALUES ('$category_name','$description')";
			// echo $qry;exit;
    $result= $this->db_query($sql, array());
    $results = $result == 1 ? 1 : 0 ;
    return $results;exit;
}
}
public function sub_category ($data){
    extract($data);
    $qry = "SELECT * FROM subcat where category_id='$category_id' AND sub_category_name='$sub_category_name'";
		$result = $this->fetchData($qry, array());		
		 if($result > 0){
			 	$result = 2;
				return $result;exit;
		 }else {
    $sql="INSERT INTO subcat (sub_category_name,category_id) VALUES ('$sub_category_name','$category_id')";
			 //echo $sql;exit;
    $result= $this->db_query($sql, array());
    $results = $result == 1 ? 1 : 0 ;
    return $results;exit;
}
}
public function post ($data){
    extract($data);
    $msg = $post_content;
		$post_content = base64_decode($post_content);
		$html = $post_content;
		$dom = new DOMDocument();
		$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
  $sql="INSERT INTO video (post_title,video_link,status,category_id,subcat_id,post_content,display_type,post_by) VALUES ('$post_title','$video_link','$status','$category_id','$subcat_id','$post_content','$display_type','$post_by')";
	//echo $sql;exit;
  $result = $this->db_query($sql, array());
  return $result;exit;
}
public function select_sub_category ($data){
    extract($data);
    $query = "SELECT id,sub_category_name FROM `subcat`WHERE category_id='$category_id'";
  $result = $this->dataFetchAll($query, array());
  return $result;exit;
}
public function editpage ($data){
  extract($data);
  $query = "SELECT * FROM video WHERE id='$id'";
$result = $this->dataFetchAll($query, array());
return $result;exit;
}
 public function editpage_document ($data){
	  extract($data);
	  $query = "SELECT * FROM editpage WHERE id='$id'";
	$result = $this->dataFetchAll($query, array());
		return $result;exit;
}
public function edit ($data){
  // echo "123";exit;
    extract($data);
    $query = "SELECT * FROM video WHERE id='$id'";
  $result = $this->fetchData($query, array());
  $count = $this->dataRowCount($query,array());
	
		 //print_r( $result);exit;	
		 if($result == ''){
				return $result;
		 }else {
			 $post_content = base64_decode($post_content);
            $query = "UPDATE video SET post_title='$post_title', video_link='$video_link', status='$status', category_id='$category_id', subcat_id='$subcat_id',post_content='$post_content',post_by='$post_by' WHERE id='$id'";
          //echo  $query ;exit;
            $result = $this->db_query($query, array());
			 //print_r($result);exit;
            $results = $result == 2?2:0;
        return $result;exit;
         }
}
	
	
		public function update_doc_post ($data){
  // echo "123";exit;
    extract($data);
    $query = "SELECT * FROM editpage WHERE id='$id'";
  $result = $this->fetchData($query, array());
  $count = $this->dataRowCount($query,array());
	
		 //print_r( $result);exit;	
		 if($result == ''){
				return $result;
		 }else {
			 //$post_content = base64_decode($post_content);
            $query = "UPDATE editpage SET post_title='$post_title', status='$status', category_id='$category_id', subcat_id='$subcat_id',post_content='$post_content',display_type='$display_type',featured='$featured' WHERE id='$id'";
       // echo  $query ;exit;
            $result = $this->db_query($query, array());
			 //print_r($result);exit;
            $results = $result == 2?2:0;
        return $result;exit;
         }
}
public function delete ($data){
    extract($data);
    $query = "DELETE FROM editpage WHERE id = '$id'";
  $result = $this->db_query($query, array());
        return $result;exit;
         
}

public function selectcategory ($data){
    extract($data);
    $query = "SELECT *,(SELECT GROUP_CONCAT(subcat.sub_category_name) from subcat where category.id=subcat.category_id) as subcategory FROM category";
  $result = $this->dataFetchAll($query, array());
  // print_r($result);exit;
        return $result;exit;
         
}
	/*
public function get_kb_list ($data){
  extract($data);
  if($search_text == ''){
  $query = "SELECT subcat_id,editpage.id,GROUP_CONCAT(editpage.filename) as filename,GROUP_CONCAT(editpage.post_title) as post_title,GROUP_CONCAT(editpage.subcat_id) as subcat_ids,category.category_name,GROUP_CONCAT(subcat.sub_category_name) as sub_category FROM subcat INNER JOIN editpage ON subcat.id = editpage.subcat_id AND editpage.status='1' AND editpage.display_type!='1' INNER JOIN category ON category.id = editpage.category_id GROUP by category.category_name";
     
  }else if($search_text !=''){
   
	  $query = "SELECT subcat_id,editpage.id,GROUP_CONCAT(editpage.filename) as filename,GROUP_CONCAT(editpage.post_title) as post_title,GROUP_CONCAT(editpage.subcat_id) as subcat_ids,category.category_name,GROUP_CONCAT(subcat.sub_category_name) as sub_category FROM subcat INNER JOIN editpage ON subcat.id = editpage.subcat_id AND editpage.status='1' AND editpage.display_type!='1' INNER JOIN category ON category.id = editpage.category_id WHERE category.category_name LIKE '%$search_text%' GROUP by category.category_name";
    
}
 
  $results = $this->dataFetchAll($query, array());
 
 foreach ($results as $result) {

  $text=$result['post_title'];
   $text= explode(",", $text);
  $text2=$result['subcat_ids'];
  $text2= explode(",", $text2);
 
  $data=array_combine($text,$text2);
	  
  foreach ($data as $key => $value) {
    $datas['post_title']=$key;
    $datas['id']=$value;	  
    $var[]=$datas;
  }
 $result['sub_category'] =$var;
  $sub_category[] = $result;
  $var=[];
}
 return $sub_category;
}*/
public function get_kb_list ($data){
  extract($data);
  if($search_text == ''){
  $query = "SELECT *,(SELECT COUNT(id) from editpage WHERE editpage.category_id=category.id) as count,(SELECT logo_image from user WHERE user_id='64') as logo_image from category where category.status='1' ORDER by category.created_dt";
     
  }else if($search_text !=''){
   
	  $query = "SELECT subcat_id,editpage.id,GROUP_CONCAT(editpage.filename) as filename,GROUP_CONCAT(editpage.post_title) as post_title,GROUP_CONCAT(editpage.subcat_id) as subcat_ids,category.category_name,GROUP_CONCAT(subcat.sub_category_name) as sub_category FROM subcat INNER JOIN editpage ON subcat.id = editpage.subcat_id AND editpage.status='1' AND editpage.display_type!='1' INNER JOIN category ON category.id = editpage.category_id WHERE category.category_name LIKE '%$search_text%' GROUP by category.category_name";
    
}
 //echo $query;exit;
	  
  $results = $this->dataFetchAll($query, array());
 

 return $results;
}
public function display ($data){
  extract($data);
     $video = "SELECT * FROM video";
	 $editpage= "SELECT editpage.*,category.category_name FROM editpage INNER JOIN category ON category.id=editpage.category_id ";
$editpage = $this->dataFetchAll($editpage, array());
	 
$video = $this->dataFetchAll($video, array());
	//print_r($editpage);
	//print_r($video);exit;
        $status = array('status' => 'true');
		$response_array = array_merge($editpage,$video);	
	    $response_array = array('data' => $response_array);	

        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
     // return $result;
       
}
public function kb_file_upload($data){

    extract($data);
//Check $post_title is already there?
		 $qry = "SELECT * FROM editpage where post_title LIKE '%$post_title%'";
		//echo $qry;exit;

		$result = $this->fetchData($qry, array());		
		 if($result > 0){
			 	$result = 2;
		$status = array('status' => 'true');
		$response_array = array('data' => $result);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
		 }
    $countfiles = count($_FILES['filename']['name']); 
 

		$destination_path = getcwd().DIRECTORY_SEPARATOR;            
		$upload_location = $destination_path."knowledge-base-files/";
		$files_arr = array();  
	$filename = $_FILES['filename']['name'];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;

				if(move_uploaded_file($_FILES['filename']['tmp_name'],$path)){				
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/knowledge-base-files/".$filename;
					$ext_arr[] = $ext;
				}
	 		
		/*if($countfiles == 1){
			$filename = $_FILES['filename']['name'];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['filename']['tmp_name'],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/knowledge-base-files/".$filename;
					$ext_arr[] = $ext;
				}
		
		} else {
			for($index = 0; $index < $countfiles; $index++){
			$filename = $_FILES['filename']['name'][$index];
			$rand = rand(0000,9999).time();
			$ext = pathinfo($_FILES['filename']['name'][$index], PATHINFO_EXTENSION);
			$filename = $filename.$rand.'.'.$ext;		   
			$path = $upload_location.$filename;
				if(move_uploaded_file($_FILES['filename']['tmp_name'][$index],$path)){
					$files_arr[] =  "https://".$_SERVER['SERVER_NAME']."/api/v1.0/knowledge-base-files /".$filename;
					$ext_arr[] = $ext;
				}

			}
		}*/
		

								  
		$files_array = $files_arr;
	    $extension_arr = $ext_arr;
		$ticketMedia = implode(",",$files_arr);	
		$files_arr = implode(",",$files_arr);
	    $ext_arr = implode(",",$ext_arr);

        
        $chat_msg_id ="INSERT INTO editpage (post_title,quote_content,status,category_id,subcat_id,post_content,filename,extension,post_by,display_type,featured) VALUES ('$post_title','$quote_content','$status','$category_id','$subcat_id','$post_content','$files_arr','$ext','$post_by','$display_type','$featured')";
      // print_r($chat_msg_id);exit;
        $parms = array();
       $result = $this->db_query($chat_msg_id,array());	

	  $insert_list ="INSERT INTO kb_docs_list (name,status,type,display_type) VALUES ('$post_title','1','file','$display_type')";
 //echo $insert_list;exit;
	    $result = $this->db_query($insert_list,array());	
        $status = array('status' => 'true');
		$response_array = array('data' => $result);	
        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
    }
     public function displaying($data){
   extract($data);
   
     
		  // $dis_query = " SELECT * FROM editpage LEFT JOIN category ON category.id=editpage.category_id WHERE  editpage.post_title like '%$post_title%'";
		 $dis_query = " SELECT *,(SELECT sub_category_name from subcat where editpage.subcat_id=subcat.id) as subcat_name FROM editpage LEFT JOIN category ON category.id=editpage.category_id WHERE  editpage.post_title like '%$post_title%'";
		  
    // print_r($dis_query); exit;
   $result = $this->dataFetchAll($dis_query, array());
   
      return $result;
       
}
 public function delete_category($data){
   extract($data);
    $query = "DELETE FROM category WHERE id = '$id'";
    $result = $this->db_query($query, array());
	 
    $query2 = "DELETE FROM editpage WHERE category_id = '$id'";
    $result = $this->db_query($query2, array());
      return $result;
       
}
public function get_articles($data){
   extract($data);
    $dis_query = "SELECT *,(SELECT sub_category_name from subcat where editpage.subcat_id=subcat.id) as subcat_name FROM editpage WHERE editpage.category_id ='$id'";
		  
     //print_r($dis_query); exit;
   $result = $this->dataFetchAll($dis_query, array());
	$result=array("data"=>$result);
	  $cat_query = "SELECT * FROM category WHERE id ='$id'";
	$cat_query=$this->dataFetchAll($cat_query, array());
	$cat_query=array("category"=>$cat_query);
	$result=array_merge($result,$cat_query);
	return $result;
       
}
	public function toggle_category_status($data){
   extract($data);
    $query = "UPDATE category SET status='$status' where id='$id'";	 
   $result = $this->db_query($query, array());
	return $result;
       
}
	public function editCategory($data){
   extract($data);
         //$query = "select * from category  where id='$id'";	 
		 $query ="select *,(SELECT GROUP_CONCAT(subcat.sub_category_name) from subcat where category.id=subcat.category_id) as subcategory from category where category.id='$id'";
		  //echo $query;exit;
   $result = $this->dataFetchAll($query, array());
	return $result;
       
}
	
	public function Updatecategory($data){
   extract($data);
   $query = "UPDATE category SET category_name='$category_name',description='$description' where id='$id'";	 
   $result = $this->db_query($query, array());
	 
	return $result;
       
}
	public function get_articles_search($data){
   extract($data);
    $dis_query = "SELECT * FROM editpage WHERE post_title LIKE '%$search_data%'";
		  
     //print_r($dis_query); exit;
   $result = $this->dataFetchAll($dis_query, array());
	$result=array("data"=>$result);
		return $result;
	/*  $cat_query = "SELECT * FROM category WHERE id ='$id'";
	$cat_query=$this->dataFetchAll($cat_query, array());
	$cat_query=array("category"=>$cat_query);
	$result=array_merge($result,$cat_query);
	return $result;
       */
}
	public function get_featured_list(){
   //extract($data);
    $dis_query = "SELECT * FROM editpage WHERE editpage.featured ='1' and editpage.status ='1' Order by editpage.id DESC ";
		  
     //print_r($dis_query); exit;
    $result = $this->dataFetchAll($dis_query, array());
	$result=array("data"=>$result);
	 /* $cat_query = "SELECT * FROM category WHERE id ='$id'";
	$cat_query=$this->dataFetchAll($cat_query, array());
	$cat_query=array("category"=>$cat_query);
	$result=array_merge($result,$cat_query);*/
	return $result;
       
}	
	 
	public function ShowSinglecategory ($data){
  extract($data);
  
	 $editpage= "SELECT editpage.*,category.category_name FROM editpage INNER JOIN category ON category.id=editpage.category_id where editpage.category_id='$id' ";
$editpage = $this->dataFetchAll($editpage, array());
	 
 
	//print_r($editpage);
	//print_r($video);exit;
        $status = array('status' => 'true');
		$response_array = array_merge($editpage);	
	    $response_array = array('data' => $response_array);	

        $merge_result = array_merge($status, $response_array);     
       
        $tarray = json_encode($merge_result);   
   			print_r($tarray); exit;
     // return $result;
       
}
	
	public function delete_sub_category($data){
   extract($data);
		 $subcat_id = "Select id FROM subcat WHERE sub_category_name = '$name'";
		 $subcat_id= $this->fetchOne($subcat_id, array());
		 //echo $subcat_id;exit;
    $query = "DELETE FROM subcat WHERE id = '$subcat_id'";
    $result = $this->db_query($query, array());
	 
    $query2 = "DELETE FROM editpage WHERE subcat_id = '$subcat_id'";
    $result = $this->db_query($query2, array());
      return $result;
       
}
	public function update_sub_category($data){
   extract($data);
		 $subcat_id = "Select id FROM subcat WHERE sub_category_name = '$name'";
		 $subcat_id= $this->fetchOne($subcat_id, array());
		 //echo $subcat_id;exit;
    $query = "UPDATE subcat SET sub_category_name='$update_name' WHERE id = '$subcat_id'";
    $result = $this->db_query($query, array());	
      return $result;
       
}
	
public function getPrivateKBList($data){
  extract($data);
 $get_qry = "SELECT subcat.*, category.category_name as cat_title FROM subcat JOIN category ON subcat.category_id = category.id ORDER BY subcat.category_id;";
 $result = $this->dataFetchAll($get_qry, array());
 $data =array();
foreach($result as $row){
   // this is the category name
   array_push($data,$row['cat_title']);
   // the title of the subcategory item
   array_push($data,$row['sub_category_name']);
}
 print_r($data);exit;
 
}
  
function getYTList($api_url = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $arr_result = json_decode($response);
    if (isset($arr_result->items)) {
        return $arr_result;
    } elseif (isset($arr_result->error)) {
        //var_dump($arr_result); //this line gives you error info if you are not getting a video list.
    }
}
public function get_list (){
  $query = "SELECT c.id as category_id,c.category_name,s.id as subcategory_id,s.sub_category_name FROM `category` c LEFT JOIN `subcat` s ON s.id = c.id WHERE c.status=1 ORDER BY c.id DESC";
  $result = $this->dataFetchAll($query, array());
  return $result;exit;
}
public function get_private_articles($data){
   extract($data);
   $dis_query = "SELECT * FROM editpage WHERE display_type=1 AND category_id='$category_id' AND subcat_id='$subcategory_id'";
   //print_r($dis_query); exit;
   $result = $this->dataFetchAll($dis_query, array());
	 $result=array("data"=>$result);
   return $result;exit;
}
	
	
	
public function post_mrvoipapi_data($data){
	  extract($data);
		
	$check_record ="SELECT * FROM mrvoip_api_datas WHERE category_id='$category_id' AND sub_category_id='$subcategory_id' ";
	$check_status = $this->dataRowCount($check_record, array());
		

		if($check_status < 1){
	  $sql="INSERT INTO mrvoip_api_datas (title,hide_status,url,category_id,sub_category_id,data_type,parameters,errors,sample_response) VALUES ('$title','$hide_status','$url','$category_id','$subcategory_id','$data_type','$parameters','$errors','$sample_response')";
	//echo $sql;exit;
  $result = $this->db_query($sql, array());
  return $result;exit;
		}else{
			$result=array("data"=>'1');
  			 return $result;exit;
		}
	
}
	
public function update_mrvoipapi_data($data){
	  extract($data);
		
	  $sql="UPDATE  mrvoip_api_datas SET title = '$title',hide_status='$hide_status',url='$url',category_id ='$category_id',sub_category_id = '$subcategory_id',data_type = '$data_type',parameters = '$parameters',errors = '$errors',sample_response = '$sample_response' WHERE id = '$update_id'";
  $result = $this->db_query($sql, array());
  return $result;exit;	
	
}
	
public function private_mrvoipapi_data($data){
   extract($data);
   //$dis_query = "SELECT * FROM mrvoip_api_datas WHERE hide_status=1 AND category_id='$category_id' AND sub_category_id='$subcategory_id'";
	
	$disk_qry= "SELECT *,(select category_name from category WHERE id='$category_id') as cat_name,(SELECT sub_category_name from subcat where id='$subcategory_id') as subcat_name FROM mrvoip_api_datas WHERE hide_status=1 AND category_id='$category_id' AND sub_category_id='$subcategory_id'";
	
   //print_r($disk_qry); exit;
   $result = $this->dataFetchAll($disk_qry, array());
   $result=array("data"=>$result);
   return $result;exit;
}	
	
	
public function code_lang_add($data){
	  extract($data);
	
		$check_record ="SELECT * FROM kb_code_lang WHERE code_title='$title'";
	$check_status = $this->dataRowCount($check_record, array());
			
	if($check_status < 1){
	 
	  $sql="INSERT INTO kb_code_lang (code_title,language_code) VALUES ('$title','$code_content')";

	  $result = $this->db_query($sql, array());
	  return $result;exit;
		
		}else{
			$result=array("data"=>'1');
  			 return $result;exit;
		}
				
	
}
			
public function generate_multiple_code($data){
	  extract($data);
	
	
		$pbx_url = "https://".$pbx_url."/api/values";
	  $sql="SELECT * FROM kb_code_lang";
  $result = $this->dataFetchAll($sql, array());
	
		$api_json = base64_decode($json_content);
 			
		$stack = [];
		foreach ($result as $el) {
				$test = base64_decode($el['language_code']);
    		$el['language_code'] = str_replace('{%api_json%}',$api_json, $test);
			$el['language_code'] = str_replace('{%api_url%}',$pbx_url, $el['language_code']);
			array_push($stack, $el);
		}



   $result=array("data"=>$stack);
   return $result;exit;
	
}		
	
public function get_mrvoip_dataList($data){
	    
	extract($data);
    $query = "SELECT *,(SELECT subcat.sub_category_name from subcat where mrvoip_api_datas.sub_category_id=subcat.id) as subcategory, (SELECT category_name FROM category WHERE category.id = mrvoip_api_datas.category_id) as category_names FROM mrvoip_api_datas";
  $output = $this->dataFetchAll($query, array());
 	$result=array("data"=>$output);
        return $result;exit;
	
}
	
 public function edit_mrvoip_datas($data){
	 	extract($data);
	 
	 $query = "SELECT * FROM mrvoip_api_datas WHERE id='$article_id'";
	 
	 $output = $this->dataFetchAll($query, array());
 	$result=array("data"=>$output);
        return $result;exit;
	 
}


}