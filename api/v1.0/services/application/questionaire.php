<?php
$result_data["status"] = true; 
$questionaire = new questionaire();

if($action == "insert_question"){    
    $data = array("admin_id"=>$admin_id, "department_id"=>$department_id,"question"=>$question);    
    $result_data["result"]["data"] = $questionaire->insertQuestion($data);
}
elseif($action == "edit_question"){    
    $data = array("question_id"=>$question_id);    
    $result_data["result"]["data"] = $questionaire->editQuestion($data);
}
elseif($action == "get_queue"){
	$data = array("admin_id"=>$admin_id);
    $result_data["result"]["data"] = $questionaire->getQueue($data);
}
elseif($action == "question_list"){
    $data = array("admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $questionaire->questionList($data);
}
elseif($action == "update_question"){    
    $data = array("question_id"=>$question_id,"admin_id"=>$admin_id, "department_id"=>$department_id,"question"=>$question);    
    $result_data["result"]["data"] = $questionaire->updateQuestion($data);
}
elseif($action == "get_user_queue"){
    $data = array("user_id"=>$user_id);
    $result_data["result"]["data"] = $questionaire->getuserQueue($data);
}
elseif($action == "delete_question"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);       
    $result_data["result"]["data"] = $questionaire->delete_question($data);
}

