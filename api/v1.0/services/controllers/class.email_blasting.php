<?php
require __DIR__ . '/../../email_blasting/YMLP_API.class.php';


class email_blasting extends restApi{

	public function list_emailgroup($data){
        extract($data); 
        $qry = "select * from email_groups where admin_id ='$admin_id'";        
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	public function get_user_list($admin_id){
        extract($data); 
        $qry = "select * from user where admin_id ='$admin_id' and delete_status = '0'";        
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	
	public function get_emails_list($admin_id){
        extract($data); 
        $qry = "select * from bulk_emails where admin_id ='$admin_id'";        
        $result = $this->dataFetchAll($qry,array());
        return $result;
    }
	
	public function add_email_group($data){
		extract($data);
		$ApiKey = "MZ6UP5C0G49CT7TQ5Z8U";
		$ApiUsername = "cal4care";
		$api = new YMLP_API($ApiKey,$ApiUsername);	
		$qry = "select * from email_groups where group_name LIKE '%$group_name%' and admin_id = '$admin_id'";
		$result = $this->fetchData($qry, array());	
		if($result > 0){
			$result = 2;
			return $result;
		 } else {	

			// run command
			$output=$api->GroupsAdd($group_name);
			if ($api->ErrorMessage){
				echo "There's a connection problem: " . $api->ErrorMessage;
				//return $api->ErrorMessage;
			} else {
				$groupID = $output["Output"];
			}
			
			
			$groupID = substr($groupID, 4); 
			$group_ylmp_users = explode(',',$group_users);
			foreach($group_ylmp_users as $group_user){
					// echo $group_user;
				$qry = "select * from contacts where contact_id='$group_user'";   
        		$result = $this->dataFetchAll($qry,array());
				$cus_name = $result[0]['first_name'];
				$email = $result[0]['email'];
				$Email = $email;
				$Name = $cus_name;
				$GroupID = $groupID;
				$OverruleUnsubscribedBounced = "0";
				$output=$api->ContactsAdd($Email,$Name,$GroupID,$OverruleUnsubscribedBounced);
			}
			
            $qry_result = $this->db_query("INSERT INTO email_groups(group_name,group_users,admin_id,group_id) values ('$group_name','$group_users','$admin_id','$GroupID')", array());       
            $insert_data = $qry_result == 1 ? 1 : 0;            
            if($insert_data != 0){
               $result = 1;              
            }
            else{                
                $result = 0;
            }            
            return $result;
		 }
	}
	
	public function edit_emailgroup($id){
		$qry = "select * from email_groups where id ='$id'";
		return $this->dataFetchAll($qry, array("id"=>$id));
	}
	public function update_emailgroup($data){
	    extract($data);
		$ApiKey = "MZ6UP5C0G49CT7TQ5Z8U";
		$ApiUsername = "cal4care";
		$api = new YMLP_API($ApiKey,$ApiUsername);	
		$qry = "select * from email_groups where id='$id'";
		$result = $this->fetchData($qry, array());	
		$old_user = $result['group_users'];
		$GroupID =$result['group_id'];
		$qry = "select GROUP_CONCAT(email) as oldEmail from contacts where contact_id IN ($group_users)";  
        $result = $this->dataFetchAll($qry,array());	
		$oldEmail = $result[0]['oldEmail'];
		$GroupID = $GroupID;
		$FieldID = "";
		$Page = "";
		$NumberPerPage = "";
		$StartDate = "";
		$StopDate = "";
		$output=$api->ContactsGetList($GroupID , $FieldID , $Page , $NumberPerPage , $StartDate , $StopDate);
	

		// output results
		if ($api->ErrorMessage){
			echo "There's a connection problem: " . $api->ErrorMessage;
		} else {
			if (isset($output["Code"])) {
				echo "{$output["Code"]} => {$output["Output"]}";
				}
			elseif (!count($output)) {
				echo "No data available";
				}
			else {
				foreach ($output as $contact) {
					$contacts[] = $contact['EMAIL'];
					}
				}
		}
		$oldEmail = explode(',',$oldEmail);
		$needtoremove = array_diff($contacts,$oldEmail);
		$needtoadd = array_diff($oldEmail, $contacts);
		
		foreach($needtoadd as $group_user){
					// echo $group_user;
				$qry = "select * from contacts where email='$group_user' and admin_id = '$admin_id'";   
        		$result = $this->dataFetchAll($qry,array());
				$cus_name = $result[0]['first_name'];
				$email = $result[0]['email'];
				$Email = $email;
				$Name = $cus_name;
				$OverruleUnsubscribedBounced = "0";
				$output=$api->ContactsAdd($Email,$Name,$GroupID,$OverruleUnsubscribedBounced);
				
			}
		
		
			foreach($needtoremove as $group_user){
				$Email = $group_user;
				$output=$api->ContactsDelete($Email,$GroupID);
			}
		
	
		
		
		$qry = "UPDATE email_groups SET group_name='$group_name',group_users='$group_users',admin_id='$admin_id' where id='$id'";
		$update_data = $this->db_query($qry, $params);
        if($update_data != 0){
            $result = 1;
        }
        else{
            $result = 0;
        }
        return $result;
	}
	public function delete_emailgroup($data){
      extract($data);
		//print_r($data); exit;
      $qry = "delete from email_groups where id='$id' and admin_id='$admin_id'";
      $parms = array();
      $results = $this->db_query($qry,$parms);      
      $output = $results == 1 ? 1 : 0;    
      return  $output;
    }
	public function ComposeBulkEmail($data){
      extract($data);
		// $ApiKey = "MZ6UP5C0G49CT7TQ5Z8U";
		$ApiKey ="MZ6UP5C0G49CT7TQ5Z8U";
		$ApiUsername = "cal4care";
		$api = new YMLP_API($ApiKey,$ApiUsername);
		$Subject = $subject;
		$HTML = $chat_message;
		$Text = '';
		$DeliveryTime = '';
		$FromID = '';
		$TrackOpens = '';
		$TrackClicks = '';
		$TestMessage = '0';
		$GroupID = $sender_id;
		$FilterID = '';
		$CombineFilters = '';

		$output=$api->NewsletterSend($Subject, $HTML, $Text, $DeliveryTime, $FromID, $TrackOpens, $TrackClicks, $TestMessage, $GroupID, $FilterID, $CombineFilters );

		// output results
		if ( $api->ErrorMessage ) {
			$qry_result = "There's a connection problem: " . $api->ErrorMessage;
		} else {
			$chat_message = base64_encode($chat_message); 
			$qry_result = $this->db_query("INSERT INTO bulk_emails(email_group,subject,message,admin_id,user_id) values ('$sender_id','$subject','$chat_message','$admin_id','$user_id')", array());       
          $qry_result=  $qry_result == 1 ? 1 : 0;   
			 if($qry_result != 0){
				$result = 1;
			}
			else{
				$result = 0;
			}
		}
      return  $result;
    }

	
}
