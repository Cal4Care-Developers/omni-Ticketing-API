<?php
$webinar_meeting_new = new webinar_meeting_new();
if($action == "add_webinar_meeting"){   
	//$data = array("encryption"=>$encryption,"meetingid"=>$meetingid, "title"=>$title,"date"=>$date,"country"=>$country,"maxparticipants"=>$maxparticipants,"duration"=>$duration,"descr"=>$descr,"parts"=>$parts);
	$data = array("encryption"=>$encryption,"session"=>$session,"meeting_details"=>$meeting_details);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $webinar_meeting_new->add_webinar_meeting($data);
}
if($action == "webinar_meeting_list"){
        $data= array("meetingid"=>$meetingid);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $webinar_meeting_new->getAll($data);    
}
if($action == "get_webinar_configuration"){
    $data= array("encryption"=>$encryption);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $webinar_meeting_new->get_webinar_configuration($data);    
}
if($action == "add_meeting_participants"){   
	$data = array("meetingid"=>$meetingid, "first_name"=>$first_name,"last_name"=>$last_name,"email"=>$email,"country"=>$country,"organization"=>$organization);
    $result_data["result"]["status"] = true;
    $result_data["result"]["data"] = $webinar_meeting_new->add_meeting_participants($data);
}
?>