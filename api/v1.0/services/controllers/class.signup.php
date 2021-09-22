<?php

	class signup extends restApi{
public function sign_up($name,$user_name,$user_password,$admin_status,$company_name,$plan_id,$address,$email_id){
	
		
	// echo $plan_id;exit;   
    $password = $user_password;
  $user_password = MD5(trim($user_password));
   $random_number = str_pad(rand(0,999), 5, "0", STR_PAD_LEFT);
   $lead_token = $name."_".$random_number;  
       $qry = "select * from user where user_name ='$user_name'";
       $qry_result = $this->dataFetchAll($qry, array()); 
     $result_count = $this->dataRowCount($qry, array());
         if($result_count > 0){
      $result = 3;
      return $result;
     } else {
     }
	
  $qry_sel="SELECT * FROM plans where id='$plan_id'";
	$plan_res = $this->fetchData($qry_sel, array());
	$has_contact=$plan_res['has_contact'];
	$has_sms=$plan_res['has_sms'];
	$has_chat=$plan_res['has_chat'];
	$has_whatsapp=$plan_res['has_whatsapp'];
	$has_chatbot=$plan_res['has_chatbot'];
	$has_fb=$plan_res['has_fb'];
	$has_wechat=$plan_res['has_wechat'];
	$has_telegram=$plan_res['has_telegram'];
	$has_internal_ticket=$plan_res['has_internal_ticket'];
	$has_external_ticket=$plan_res['has_external_ticket'];
	$voice_3cx=$plan_res['voice_3cx'];
	$predective_dialer=$plan_res['predective_dialer'];
	$lead=$plan_res['lead'];
	$wallboard_one=$plan_res['wallboard_one'];
	$wallboard_two=$plan_res['wallboard_two'];
	$wallboard_three=$plan_res['wallboard_three'];
	$wallboard_four=$plan_res['wallboard_four'];
	$reports=$plan_res['reports'];
	$has_internal_chat=$plan_res['has_internal_chat'];
	$has_fax=$plan_res['has_fax'];
     $qry = "INSERT INTO user(user_name,user_pwd,user_type,agent_name,has_contact,has_sms,has_chat,user_status,admin_id,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,layout,theme,lead_token,password,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,company_name,has_fax,has_internal_chat,reports,whatsapp_type,plan_id,address,email_id,site) VALUES ('$user_name', '$user_password','2','$name','$has_contact','$has_sms','$has_chat','$admin_status','1','$has_whatsapp','$has_chatbot','$has_fb','$has_wechat','$has_telegram','$has_internal_ticket','$has_external_ticket','dark','black','','$password','$voice_3cx','$predective_dialer','$lead','$wallboard_one','$wallboard_two','$wallboard_three','$wallboard_four','$company_name','$has_fax','$has_internal_chat','$reports','$whatsapp_type','$plan_id','$address','$email_id','1')";
	  // print_r($qry);exit;
    $qry_result = $this->db_query($qry, array());


   if($qry_result == 1){
      $qry = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
    $result = $this->dataFetchAll($qry, array());
    $result = $result[0];
     $result =  $result['user_id'];$aid =  $result['user_id'];
      if($result){
        if($has_contact == 1 || $has_contact == true){ $has_contacts = 1; }else{ $has_contacts = 0; }
        if($has_sms == 1 || $has_sms == true){ $has_smss = 1; }else{ $has_smss = 0; }
        if($has_chat == 1 || $has_chat == true){ $has_chats = 1; }else{ $has_chats = 0; }        
        if($has_whatsapp == 1 || $has_whatsapp == true){ $has_whatsapps = 1; }else{ $has_whatsapps = 0; }
        if($has_chatbot == 1 || $has_chatbot == true){ $has_chatbots = 1; }else{ $has_chatbots = 0; }
        if($has_fb == 1 || $has_fb == true){ $has_fbs = 1; }else{ $has_fbs = 0; }
        if($has_wechat == 1 || $has_wechat == true){ $has_wechats = 1; }else{ $has_wechats = 0; }
        if($has_telegram == 1 || $has_telegram == true){ $has_telegrams = 1; }else{ $has_telegrams = 0; }
        if($has_internal_ticket == 1 || $has_internal_ticket == true){ $has_internal_tickets = 1; }else{ $has_internal_tickets = 0; }
        if($has_external_ticket == 1 || $has_external_ticket == true){ $has_external_tickets = 1; }else{ $has_external_tickets = 0; }
        if($voice_3cx == 1 || $voice_3cx == true){ $voice_3cxs = 1; }else{ $voice_3cxs = 0; }     
    if($predective_dialer == 1 || $predective_dialer == true){ $predective_dialers = 1; }else{ $predective_dialers = 0; }
    if($lead == 1 || $lead == true){ $leads = 1; }else{ $leads = 0; }
    if($wallboard_one == 1 || $wallboard_one == true){ $wallboard_ones = 1; }else{ $wallboard_ones = 0; }
        if($wallboard_two == 1 || $wallboard_two == true){ $wallboard_twos = 1; }else{ $wallboard_twos = 0; }     
        if($wallboard_three == 1 || $wallboard_three == true){ $wallboard_threes = 1; }else{ $wallboard_threes = 0; }
        if($wallboard_four == 1 || $wallboard_four == true){ $wallboard_fours = 1; }else{ $wallboard_fours = 0; }
    if($two_factor == 1 || $two_factor == true){ $two_factors = 1; }else{ $two_factors = 0; }
    if($has_fax == 1 || $has_fax == true){ $has_faxs = 1; }else{ $has_faxs = 0; }
    if($has_internal_chat == 1 || $has_internal_chat == true){ $has_internal_chats = 1; }else{ $has_internal_chats = 0; }
      //if($has_wallboard == 1 || $has_wallboard == true){ $has_wallboards = 1; }else{ $has_wallboards = 0; }
		  
		 if($wp_instance_count > 0){ 
		  $myArray = explode(',', $wp_instance_count);
		foreach($myArray as $inst_id){
			$qry_result = $this->db_query("UPDATE whatsapp_instance_details SET admin_id='$result' where wp_inst_id = $inst_id", array());
		}
		 }
        $qry = "INSERT INTO admin_details(name,admin_id,pbx_count,has_contact,has_sms,has_chat,admin_status,has_whatsapp,has_chatbot,has_fb,has_wechat,has_telegram,has_internal_ticket,has_external_ticket,voice_3cx,predective_dialer,lead,wallboard_one,wallboard_two,wallboard_three,wallboard_four,two_factor,company_name,domain_name,has_fax,has_internal_chat,reports,price_sms,survey_vid,tarrif_id,whatsapp_type,wp_instance_count,plan_id,address,site) VALUES ('$name', '$result','1','$has_contacts','$has_smss','$has_chats','$admin_status','$has_whatsapps','$has_chatbots','$has_fbs','$has_wechats','$has_telegrams','$has_internal_tickets','$has_external_tickets','$voice_3cxs','$predective_dialers','$leads','$wallboard_ones','$wallboard_twos','$wallboard_threes','$wallboard_fours','$two_factors','$company_name','$domain_name','$has_faxs','$has_internal_chats','$reports','$price_sms','$survey_vid','$tarrif_id','$whatsapp_type','$wp_instance_count','$plan_id','$address','1')";
   $qry_result = $this->db_query($qry, array()); 
          $result = $qry_result == 1 ? 1 : 0;
		 // if($qry_result!='' || $qry_result!='0'){
		//	  $result='1';
		  //}else{
			//$result='0';  
		 // }
             //$updateqry2 = " Update user SET `lead_token` = '$lead_token' WHERE admin_id='$aid'";
            //$parms = array();
            //$results2 = $this->db_query($updateqry2,$parms);
		  if($result=='1'){
			  require_once('class.phpmailer.php');
			  $from="sales@cal4care.com";
			  $to="sales@cal4care.com";
			  $user_email="cm@cal4care.com";
                  $subject = "New Signup";
                  $message = "A New Memeber company Name: $company_name  is Registered in Omni Channel please check and approve";
                  $body = addslashes($message);                
                  $mail = new PHPMailer();
                  $mail->IsSMTP();
                  $mail->SMTPAuth = true; 
                  $mail->SMTPSecure = 'tls';                 // enable SMTP authentication
                  $mail->Host = 'smtpcorp.com'; // sets the SMTP server
                  $mail->Port = '2525';                    // set the SMTP port for the GMAIL server
                  $mail->Username = 'erpdev2'; // SMTP account username
                  $mail->Password = 'dnZ0ZjlyZ3RydzAw';        // SMTP account password 
                  $mail->SetFrom($to, $to);
                  $mail->AddReplyTo($from, $from);
                  $mail->Subject = $subject;
                  $mail->MsgHTML($body);
                  $mail->AddAddress($user_email);    
                  $mail->Send();
		  }
		  
	  
            return $result;
      }
   }
	
   
	
}	
		
 public function signup_approval_list(){
		 
   	 // $qry = $this->dataFetchAll("SELECT admin_id as id,name FROM `admin_details` where delete_status='0' and admin_status='0' and site='1'", array());
	 $qry = $this->dataFetchAll("SELECT admin_details.id as id,name,plan_name FROM `admin_details` LEFT JOIN plans on plans.id=admin_details.plan_id where delete_status='0' and admin_status='0' and site='1'", array());
      return $qry;
  }	
public function approve($data){
	extract($data);
 $qry = "UPDATE admin_details set admin_status='1' where id='$id' ";
      	 $qry_result = $this->db_query($qry,array());		
		//$output= $qry_result == 1 ? 1 : 0;
	$output='1';
	if($output=='1'){
		
		 $admin_id = $this->fetchOne("SELECT admin_id from admin_details where id='$id'", array());
		
	 $chat_details_qry = "select * from user where user_id='$admin_id'";
      $chat_details = $this->fetchData($chat_details_qry, array());
	  $count = $this->dataRowCount($chat_details_qry, array());	
if($count > 0){
      $user_name = $chat_details['user_name'];
      $user_pwd = $chat_details['password'];
      $email = $chat_details['email_id'];
	  $company_name = $chat_details['company_name'];
    $admin_name=$chat_details['fname'].''.$chat_details['lname'];
	
      require_once('class.phpmailer.php'); 
      $customer_email = 'noreply@mconnectapps.com';   
      $from = 'OmniChannel';
      $subject = "Welcome Email From OmniChannel";
      
$message='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
	<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--<![endif]-->
	<title></title>
	<!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
	<!--<![endif]-->
	<style type="text/css">
		body {
			margin: 0;
			padding: 0;
		}

		table,
		td,
		tr {
			vertical-align: top;
			border-collapse: collapse;
		}

		* {
			line-height: inherit;
		}

		a[x-apple-data-detectors=true] {
			color: inherit !important;
			text-decoration: none !important;
		}
	</style>
	<style type="text/css" id="media-query">
		@media (max-width: 720px) {

			.block-grid,
			.col {
				min-width: 320px !important;
				max-width: 100% !important;
				display: block !important;
			}

			.block-grid {
				width: 100% !important;
			}

			.col {
				width: 100% !important;
			}

			.col>div {
				margin: 0 auto;
			}

			img.fullwidth,
			img.fullwidthOnMobile {
				max-width: 100% !important;
			}

			.no-stack .col {
				min-width: 0 !important;
				display: table-cell !important;
			}

			.no-stack.two-up .col {
				width: 50% !important;
			}

			.no-stack .col.num4 {
				width: 33% !important;
			}

			.no-stack .col.num8 {
				width: 66% !important;
			}

			.no-stack .col.num4 {
				width: 33% !important;
			}

			.no-stack .col.num3 {
				width: 25% !important;
			}

			.no-stack .col.num6 {
				width: 50% !important;
			}

			.no-stack .col.num9 {
				width: 75% !important;
			}

			.video-block {
				max-width: none !important;
			}

			.mobile_hide {
				min-height: 0px;
				max-height: 0px;
				max-width: 0px;
				display: none;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide {
				display: block !important;
				max-height: none !important;
			}
		}
	</style>
</head>

<body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f9f9f9;">
	<!--[if IE]><div class="ie-browser"><![endif]-->
	<table class="nl-container" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9; width: 100%;" cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#f9f9f9" valign="top">
		<tbody>
			<tr style="vertical-align: top;" valign="top">
				<td style="word-break: break-word; vertical-align: top;" valign="top">
					<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color:#f9f9f9"><![endif]-->
					
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:0px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/Round_corner_top.png" alt="Image" title="Alternate text" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" bgcolor="#74c6d7" width="700" style="background-color:#74c6d7;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:15px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; background-color: #74c6d7; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top: 15px; padding-bottom: 15px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/logo.png" alt="Logo" title="Logo" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 300px; max-width: 300px; display: block;" width="300">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:0px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center fixedwidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center fixedwidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/banner.gif" alt="Banner" title="Banner" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 34px; line-height: 1.5; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 51px; margin: 0;"><span style="font-size: 34px;"><strong>WELCOME TO OMNI CHANNELS !!</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">New Omni channel account created to <strong>"'.$company_name.'" 
													</strong> with full features</span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;"><strong>Admin credentials :</strong>&nbsp;</span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">Login URL &nbsp;&nbsp;:&nbsp; <strong>
														<a target="_blank" style="color: #0597d4;" href="https://omni.mconnectapps.com/">https://omni.mconnectapps.com/</a></strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">User Name :&nbsp; <strong>"'.$user_name.'"</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 24px; margin: 0;"><span style="font-size: 18px;">Password &nbsp;&nbsp; : &nbsp; <strong>"'.$user_pwd.'"</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" align="center" role="presentation" height="0" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="0" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="button-container" align="center" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:31.5pt; width:132.75pt; v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#0597d4"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
												<div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#0597d4;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #0597d4;border-right:1px solid #0597d4;border-bottom:1px solid #0597d4;border-left:1px solid #0597d4;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; margin: 0; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;"><a style="text-decoration: none; color: #ffffff; font-weight: 700; letter-spacing: 1px;" target="_blank" href="https://omni.mconnectapps.com/">START NOW</a></span></span></div>
												<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
											</div>
										
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/inner-banner-1.png" alt="BAnner Image" title="Banner Image" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 0px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:30px;padding-right:10px;padding-bottom:0px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #516dac;"><strong>&nbsp; 1&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong> Configure PBX account for Admin</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
										
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="https://omni.mconnectapps.com/" target="_blank"> https://omni.mconnectapps.com/ </a> &nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Click top right profile image icon and click ‘Profile’&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 15px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 15px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Key-in your extension, SIP username and SIP password and click ‘Update Agent’ button&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>


											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffc5c3;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffc5c3"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 30px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:30px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #f26d6b;"><strong>&nbsp; 2&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Configure PBX account for Agent</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="https://omni.mconnectapps.com/" target="_blank"> https://omni.mconnectapps.com/</a>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2.  Click ‘Add Agent’ button Key-in agent details along with agent extension, SIP username and SIP password and click ‘Add Agent’ button &nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. If already added with PBX account, click Edit button from agent list and update.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											
										
												<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>

											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
										
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #6e51c9;"><strong>&nbsp; 3&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Install/configure Mr.VoIP in 3CX server</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Login into omni channel <a style="text-decoration: none; color: #555555; font-weight: 600;" href="https://omni.mconnectapps.com/" target="_blank"> https://omni.mconnectapps.com/</a>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> Download Documents&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Under Mr.VoIP you can Linux files and windows installer file to download&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Download Guide and follow the procedure (Linux/Windows)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 5. Note: Mr.VoIP need to configure/install in 3CX server&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
										
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>


	<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fbf2ce;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#fbf2ce;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fbf2ce;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#fbf2ce"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
										
											
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; background-color: #cdc5a6;"><strong>&nbsp; 4&nbsp;&nbsp;</strong></span></p>
													<p style="font-size: 34px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 34px; color: #34495e;"><strong>Integrate Facebook into Omni</strong></span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&nbsp;<strong>Customer Page Integration in FB APP:</strong>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Customer Can ask permission to add their Page in OMNI via Our FB page(cal4car, Mr.Voip,cal4careTH, omnichannel) or contact our admin&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Customer is asked to give their Page name or Pageid.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Customer needs to accept our request to access their page.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. Download Guide and follow the procedure (Linux/Windows)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 5. Note: Mr.VoIP need to configure/install in 3CX server&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->



										
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>


	<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #ffffff;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#ffffff;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#ffffff"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top: 35px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&nbsp;<strong>Approve a business Permission for customer Page</strong>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Add the Customer page in our FB developer APP and add a subscription for pages_messaging.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Add Customer\'s FB page in our FB business Account.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Customer needs to accept and confirm our request to add their page in our business account.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 4. All Standard access and Manage page permissions should be enabled.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 5. After that submit our FB APP globally(enable app status to live mode).&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>





	<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #e8e8ec;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color: #e8e8ec;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #e8e8ec;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color: #e8e8ec;"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#ffffff;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top: 35px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&nbsp;<strong>User initialization</strong>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
									
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Initially, users can send a message from respected FB pages through messenger. That will receive by Omni with user\'s information(first name, last name, profile picture)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2.After that admin can send a reply for that user from OMNI.&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

										
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 10px; width: 100%;" align="center" role="presentation" height="10" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="10" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>


					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #3f4d6a;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#3f4d6a;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#3f4d6a"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#3f4d6a;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 5px; width: 100%;" align="center" role="presentation" height="5" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="5" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="img-container center fixedwidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center fixedwidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/license.png" alt="License Image" title="License Image" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 150px; display: block;" width="150">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px; font-family: \'Trebuchet MS\', Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:\'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif;line-height:1.8;padding-top:20px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.8; font-size: 12px; font-family: \'Montserrat\', \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 22px;">
													<p style="font-size: 20px; line-height: 1.8; text-align: center; word-break: break-word; font-family: Montserrat, \'Trebuchet MS\', \'Lucida Grande\', \'Lucida Sans Unicode\', \'Lucida Sans\', Tahoma, sans-serif; mso-line-height-alt: 61px; margin: 0;"><span style="font-size: 20px; background-color: #0597d4; padding: 10px 30px;"><strong>&nbsp; AC0FE679-7C40-4A19-8119-A213B94E5327&nbsp;&nbsp;</strong></span></p>
													
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											
										
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;"><span style="color: #f2f2f2;">&nbsp; Use above license key for Mr.VoIP and Omni&nbsp;</span></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 0px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top: 0px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 18px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 18px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&nbsp;<strong>Note :</strong>&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->


<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 1. Other Settings you can go through Settings -> Global Settings (To update time zone, logo etc)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 2. Go to Settings -> App Settings (To update Themes, chatbot, FB Integration, Whatsapp etc.,)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">&#10148;&nbsp; 3. Go to Settings -> Chat Settings (To configure Live Chat widget etc.,)&nbsp;</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->

											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 40px; padding-left: 40px; padding-top: 5px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color: #ffffff;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:5px;padding-right:40px;padding-bottom:10px;padding-left:40px;">
												<div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #ffffff; mso-line-height-alt: 18px;">
													<p style="font-size: 14px; line-height: 1.5; text-align: left; word-break: break-word; font-family: inherit; mso-line-height-alt: 21px; margin: 0;">I hope the above is useful to you.&nbsp; Please feel free to contact us if you need any further information.</p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->





											<div class="button-container" align="center" style="padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px" align="center"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:31.5pt; width:120pt; v-text-anchor:middle;" arcsize="10%" stroke="false" fillcolor="#0597d4"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Tahoma, sans-serif; font-size:16px"><![endif]-->
												<div style="text-decoration:none;display:inline-block;color:#ffffff;background-color:#0597d4;border-radius:4px;-webkit-border-radius:4px;-moz-border-radius:4px;width:auto; width:auto;;border-top:1px solid #0597d4;border-right:1px solid #0597d4;border-bottom:1px solid #0597d4;border-left:1px solid #0597d4;padding-top:5px;padding-bottom:5px;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:16px;display:inline-block;"><span style="font-size: 16px; margin: 0; line-height: 2; word-break: break-word; mso-line-height-alt: 32px;">
													<a style="text-decoration: none; color: #ffffff;" href="https://mconnectapps.com/contact-us/" target="_blank">Contact Us</a></span></span></div>
												<!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
											</div>
											<table class="divider" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" role="presentation" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="divider_content" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid transparent; height: 0px; width: 100%;" align="center" role="presentation" height="0" valign="top">
																<tbody>
																	<tr style="vertical-align: top;" valign="top">
																		<td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" height="0" valign="top"><span></span></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<div class="img-container center autowidth" align="center" style="padding-right: 0px;padding-left: 0px;">
												<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="line-height:0px"><td style="padding-right: 0px;padding-left: 0px;" align="center"><![endif]--><img class="center autowidth" align="center" border="0" src="https://erp.cal4care.com/img/email-template/welcome-email/Last_box_round.png" alt="Banner" title="Banner" style="text-decoration: none; -ms-interpolation-mode: bicubic; border: 0; height: auto; width: 100%; max-width: 700px; display: block;" width="700">
												<!--[if mso]></td></tr></table><![endif]-->
											</div>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f9f9f9;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:#f9f9f9;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:#f9f9f9"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:#f9f9f9;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; font-family: Tahoma, sans-serif"><![endif]-->
											<div style="color:#000000;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;">
												<div style="line-height: 1.2; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #000000; mso-line-height-alt: 14px;">
													<p style="font-size: 12px; line-height: 1.2; text-align: center; word-break: break-word; font-family: inherit; mso-line-height-alt: 14px; margin: 0;"><a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Terms & Conditions</a> | <a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Help & Contact</a>&nbsp;| <a style="text-decoration: none; color: #0068a5;" href="#" target="_blank" rel="noopener">Privacy Notice</a> | <a style="text-decoration: none; color: #0068a5;" href="http://mconnectapps.com/" target="_blank" rel="noopener">View Online</a></p>
												</div>
											</div>
											<!--[if mso]></td></tr></table><![endif]-->
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<div style="background-color:transparent;">
						<div class="block-grid " style="Margin: 0 auto; min-width: 320px; max-width: 700px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: transparent;">
							<div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
								<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:transparent;"><tr><td align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:700px"><tr class="layout-full-width" style="background-color:transparent"><![endif]-->
								<!--[if (mso)|(IE)]><td align="center" width="700" style="background-color:transparent;width:700px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:5px; padding-bottom:5px;"><![endif]-->
								<div class="col num12" style="min-width: 320px; max-width: 700px; display: table-cell; vertical-align: top; width: 700px;">
									<div style="width:100% !important;">
										<!--[if (!mso)&(!IE)]><!-->
										<div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
											<!--<![endif]-->
											<table class="social_icons" cellpadding="0" cellspacing="0" width="100%" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top">
												<tbody>
													<tr style="vertical-align: top;" valign="top">
														<td style="word-break: break-word; vertical-align: top; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px;" valign="top">
															<table class="social_table" align="center" cellpadding="0" cellspacing="0" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-tspace: 0; mso-table-rspace: 0; mso-table-bspace: 0; mso-table-lspace: 0;" valign="top">
																<tbody>
																	<tr style="vertical-align: top; display: inline-block; text-align: center;" align="center" valign="top">
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.facebook.com/Cal4care/" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/facebook.png" alt="Facebook" title="Facebook" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://twitter.com/calncall" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/twitter.png" alt="Twitter" title="Twitter" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.instagram.com/cal4care/" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/instagram.png" alt="Instagram" title="Instagram" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.linkedin.com/company/cal4care-pte-ltd/?viewAsMember=true" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/linkedin.png" alt="LinkedIn" title="LinkedIn" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																		<td style="word-break: break-word; vertical-align: top; padding-bottom: 0; padding-right: 3px; padding-left: 3px;" valign="top"><a href="https://www.youtube.com/results?search_query=cal4care" target="_blank"><img width="32" height="32" src="https://erp.cal4care.com/img/email-template/welcome-email/youtube.png" alt="Youtube" title="Youtube" style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; display: block;"></a></td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
											<!--[if (!mso)&(!IE)]><!-->
										</div>
										<!--<![endif]-->
									</div>
								</div>
								<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
								<!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
							</div>
						</div>
					</div>
					<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
				</td>
			</tr>
		</tbody>
	</table>
	<!--[if (IE)]></div><![endif]-->
</body>

</html>';
      $body = $message;                
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->SMTPAuth = true; 
      $mail->SMTPSecure = 'tls';
      $mail->Host = 'smtpcorp.com';
      $mail->Port = '2525';
      $mail->Username = 'erpdev2';
      $mail->Password = 'dnZ0ZjlyZ3RydzAw';
      $mail->SetFrom($customer_email, $from);
      $mail->AddReplyTo($customer_email, $from);
      $mail->Subject = $subject;
      $mail->MsgHTML($body);
      $mail->AddAddress($email);
      $mail->Send();
	  $status = array("status"=>true);
      $tarray = json_encode($status); 
      print_r($tarray);exit;
    }else{
	  $status = array("status"=>false);
      $tarray = json_encode($status); 
      print_r($tarray);exit;	
	}
	}
}
	}