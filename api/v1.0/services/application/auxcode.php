<?php
$result_data["status"] = true;
$auxcode = new auxcode();
if($action == "add_auxcode"){    
    $data = array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $auxcode->addAuxcode($data);
}
if($action == "update_auxcode"){
    $data = array("auxcode_name"=>$auxcode_name,"admin_id"=>$admin_id,"auxcode_id"=>$auxcode_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $auxcode->updateAuxcode($data);
}
if($action == "edit_auxcode"){
	$data = array("admin_id"=>$admin_id,"auxcode_id"=>$auxcode_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $auxcode->getAuxcode_data($data);
}
if($action == "get_auxcode"){
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $auxcode->getAuxcode($admin_id);
}
if($action == "delete_aux"){     
    $data = array("id"=>$id,"admin_id"=>$admin_id);     
    $result_data["result"]["data"] = $auxcode->delete_aux($data);
}