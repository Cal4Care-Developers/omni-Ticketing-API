<?php
$agnt_aux= new agnt_aux();
$result_data["status"] = true;

if($action == "agnt_aux_rep"){
    $data= array("type"=>$type,"admin_id"=>$admin_id,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"rep_format"=>$rep_format,"report_name"=>$report_name );
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $agnt_aux->agnt_aux_rep($data);

}elseif("agnt_aux_list"){
    $data= array("limit"=>$limit, "offset"=>$offset,"search_text"=>$search_text);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $agnt_aux->agnt_aux_list($data);
}