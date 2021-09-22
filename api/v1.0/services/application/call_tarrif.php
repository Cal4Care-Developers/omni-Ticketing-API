<?php 
$call_tarrif = new call_tarrif();
$result_data["status"] = true;

if($action == "new_call_tarrif"){  
    $chat_data = array("tarrif_name"=>$tarrif_name, "admin_id"=>$admin_id, "currency"=>$currency, "description"=>$description);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->new_call_tarrif($chat_data);
}
elseif($action == "del_call_tarrif"){  
    $chat_data = array("tarrif_name"=>$tarrif_name, "admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->del_call_tarrif($chat_data);
}
elseif($action == "view_call_tarrif"){  
    $chat_data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->view_call_tarrif($chat_data);
}
elseif($action == "get_call_tarrif"){  
    $chat_data = array("id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->get_call_tarrif($chat_data);
}
elseif($action == "insert_call_tarrif"){  
    $chat_data = array("id"=>$id,"price"=>$price,"plan_id"=>$plan_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->insert_call_tarrif($chat_data);
}
elseif($action == "get_call_balance"){  
    $chat_data = array("from_no"=>$from_no,"to_no"=>$to_no,"hardware_id"=>$hardware_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->get_call_balance($chat_data);
}
elseif($action == "call_report"){  
    $chat_data = array("admin_id"=>$admin_id,"from_no"=>$from_no,"to_no"=>$to_no,"from_dt"=>$from_dt,"to_dt"=>$to_dt,"rep_format"=>$rep_format);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->call_report($chat_data);
}
elseif($action == "list_call_cost"){  
    $chat_data = array("admin_id"=>$admin_id,"from_no"=>$from_no,"to_no"=>$to_no,"from_dt"=>$from_dt,"to_dt"=>$to_dt);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->list_call_cost($chat_data);
}
elseif($action == "gen_invoice"){  
    $chat_data = array("user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->gen_invoice($chat_data);
}
elseif($action == "send_mail"){  
    $chat_data = array("user_id"=>$user_id,"admin_id"=>$admin_id,"invoice_no"=>$invoice_no);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->send_mail($chat_data);
}
elseif($action == "view_admin_biller"){  
    $chat_data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->view_admin_biller($chat_data);
}
elseif($action == "create_invoicegrp"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id,"grp_name"=>$grp_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->create_invoicegrp($chat_data);
}
elseif($action == "view_invoicegrp"){  
    $chat_data = array("admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->view_invoicegrp($chat_data);
}
elseif($action == "edit_invoicegrp"){  
    $chat_data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->edit_invoicegrp($chat_data);
}
elseif($action == "update_invoicegrp"){  
    $chat_data = array("admin_id"=>$admin_id,"id"=>$id,"user_id"=>$user_id,"grp_name"=>$grp_name);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->update_invoicegrp($chat_data);
}
elseif($action == "del_invoicegrp"){  
    $chat_data = array("admin_id"=>$admin_id,"id"=>$id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->del_invoicegrp($chat_data);
}
elseif($action == "ag_addlist"){  
    $chat_data = array("admin_id"=>$admin_id,"user_id"=>$user_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->ag_addlist($chat_data);
}
elseif($action == "gen_invoicegrp"){  
    $chat_data = array("inv_grp"=>$inv_grp,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->gen_invoicegrp($chat_data);
}
elseif($action == "old_invoice"){  
    $chat_data = array("user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->old_invoice($chat_data);
}
elseif($action == "gen_admin_invoice"){  
    $chat_data = array("user_id"=>$user_id,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->gen_admin_invoice($chat_data);
}
elseif($action == "gen_admin_invoicegrp"){  
    $chat_data = array("inv_grp"=>$inv_grp,"admin_id"=>$admin_id);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $call_tarrif->gen_admin_invoicegrp($chat_data);
}