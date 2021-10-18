<!DOCTYPE html>
<html>

<head>
	<title>Dialpad</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap-4.5.0.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="chat-style.css">
	<link rel="stylesheet" type="text/css" href="style.css"> 

	<style>
		/*change on Feb 15
.dialpad-container {height: 465px;}*/
		.circle-plus.view-dialpad {
			display: block !important;
		}

		/*.forwardDialpadPanel.hide-fwd-dialpad {
			right: 360px;
		}*/

		.nav-link:hover,
		.nav-tabs .nav-link {
			border: none;
		}

		.nav-tabs {
			box-shadow: none;
			border: none;
		}

		.nav-tabs .nav-item.show .nav-link,
		.nav-tabs .nav-link.active {
			background-color: transparent;
			border: none;
		}

		.forwordDialPad .tab-pane {
			height: 100%;
			overflow-y: initial;
		}

		.forwordDialPad .row.noMargin {
			margin: 0px -1px 0px;
		}

		.forwordDialPad .contact-panel {
			height: 300px;
			overflow: auto;
		}

		#call-action-btn-group {
			display: inline-block;
		}
	</style>
</head>

<body>
	<input type="hidden" id="call_history_id" value="0">
	<input type="hidden" id="call_customer_key" value="0">
	<input type="hidden" id="call_incoming_number" value="">
	<input type="hidden" id="incoming_call_trigger" onclick="incoming_call_trigger()">
	<input type="hidden" id="outgoing_call_end_trigger" onclick="outgoingCallEnd()">
	<input type="hidden" autocomplete="off" id="html5vid">
	<input type="hidden" autocomplete="off" id="transferto">
	<input type="hidden" autocomplete="off" id="sipExtension">
	<input type="hidden" autocomplete="off" id="sipHardwareId">
	<input type="hidden" autocomplete="off" id="has_mrvoip">
	<input type="hidden" autocomplete="off" id="custoInfo">
	<inpu type="hidden" autocomplete="off" id="custoInfchatId">
	<div class="theme-white">

		<div class="page-header">
			<!-- <h1>
<button class="btn btn-default" autocomplete="off" id="start">Start</button>
</h1> -->
		</div>
		<div class="container" id="details">
			<div class="row">
				<div class="col-md-12">

				</div>
			</div>
		</div>
		<div>
			<input class="form-control" type="hidden" value="sip:cal4caredemo.3cx.sg:5060"
				placeholder="SIP Registrar (e.g., sip:host:port)" autocomplete="off" id="server"
				onkeypress="return checkEnter(this, event);" />
			<input class="form-control" type="hidden" value="sip:103@cal4caredemo.3cx.sg"
				placeholder="SIP Identity (e.g., sip:goofy@example.com)" autocomplete="off" id="username"
				onkeypress="return checkEnter(this, event);" />
			<input class="form-control" type="hidden" value="KWEK8baMFB"
				placeholder="Username (e.g., goofy, overrides the one in the SIP identity if provided)"
				autocomplete="off" id="authuser" onkeypress="return checkEnter(this, event);" />
			<input class="form-control" type="hidden" value="qx2C0VIysR"
				placeholder="Secret (e.g., mysupersecretpassword)" autocomplete="off" id="password"
				onkeypress="return checkEnter(this, event);" />
			<input class="form-control" type="hidden" placeholder="Display name (e.g., Alice Smith)" autocomplete="off"
				id="displayname" onkeypress="return checkEnter(this, event);" />
			<input type="hidden" autocomplete="off" id="register">
			<input type="hidden" autocomplete="off" id="sip_urld">
			<input type="hidden" autocomplete="off" id="outcall_number">

			<!-- <audio id="incommingCalltone" >
<source src="assets/images/incomingcall.mp3" type="audio/mpeg">
</audio>
<audio id="ringingTone" >
<source src="assets/images/ringbacktone.mp3" type="audio/mpeg">
</audio> -->

			<audio id='incommingCalltone' src='assets/images/incomingcall.mp3' autoplay="true " muted></audio>
			<audio id='ringingTone' src='assets/images/ringbacktone.mp3' autoplay="true" muted></audio>
		</div>

		<!----------------------------------------------
Custom Code
-------------------------->

<!---------------------------------------------
Custom Code End
------------------------------------------------>

		<div class="common-logout">
			<a  onclick="logoutMain()" style="cursor:pointer">
				<span class="badge badge-danger"><i class="fas fa-sign-out-alt"></i> Logout</span>
			</a>
		</div>


		<!-- <input type="hidden" autocomplete="off" id="peer"> -->
		<div class="left-column">
			<div class="app-logo logo-black">
				<img src="assets/images/mconnect-logo.png">
			</div>

			<div class="dialpad-tab">
			    <ul class="dialpad-list-group nav nav-tabs">
					<li><a id="contactsTab" data-toggle="tab" href="#contacts" onclick="getMyContacts()"><i class="fa fa-user-circle" aria-hidden="true"></i>Contacts</a></li>
					<li><a data-toggle="tab" href="#history" onclick="getMyCallHistoryDetails('All')"><i class="fa  fa-phone" aria-hidden="true"></i>Call History</a></li>
					<li><a data-toggle="tab" href="#agents"  onclick="getMyAgents()" ><i class="fas fa-users" aria-hidden="true"></i>Agents List</a></li>
					<li class="hamburger"><a data-toggle="tab" href="#chat" onclick="getMyChatLists()"  ><i class="far fa-comment-alt" aria-hidden="true"></i>Chat<span></span></a>
                       <!-- <ul class="menu">
                       	 <li><a data-toggle="tab" href="#chat"><i class="fas fa-comment-dots" aria-hidden="true"></i>Chat</a></li>
                       	 <li><a href="javascript:;"><i class="fab fa-whatsapp" aria-hidden="true"></i>Whatsapp</a></li>
                       	 <li><a href="javascript:;"><i class="fab fa-facebook-messenger" aria-hidden="true"></i>Facebook</a></li>
                       </ul> -->
					</li>
				</ul>
			</div>

			<div class="dialpad-container login-dialer" id="dialpad-wrapper">
				<!-- change Changed only for video Dinesh FEb 15   <div class="dialpad-container" id="dialpad-wrapper" >-->
				<div class="container">
					<div class="row" style="display: none;">
						<div class="col-md-12">
							<div class="page-header">
								<h1>
									<button class="btn btn-default" autocomplete="off" id="start">Start</button>
								</h1>
							</div>
							<div class="container" id="details">
								<div class="row">
									<div class="col-md-12">

									</div>
								</div>
							</div>
							<div class="container hide" id="sipcall">
								<div class="row">
									<div class="col-md-12">
										<!-- <div class="col-md-6 container hide" id="login">
<div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="fa fa-cloud-upload fa-fw"></i></span>
<input class="form-control" type="text" value="sip:cal4caredemo.3cx.sg:5060" placeholder="SIP Registrar (e.g., sip:host:port)" autocomplete="off" id="server" onkeypress="return checkEnter(this, event);" />
</div>
<div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
<input class="form-control" type="text" value="sip:103@cal4caredemo.3cx.sg" placeholder="SIP Identity (e.g., sip:goofy@example.com)" autocomplete="off" id="username" onkeypress="return checkEnter(this, event);" />
</div>
<div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="fa fa-user-plus fa-fw"></i></span>
<input class="form-control" type="text" value="KWEK8baMFB" placeholder="Username (e.g., goofy, overrides the one in the SIP identity if provided)" autocomplete="off" id="authuser" onkeypress="return checkEnter(this, event);" />
</div>
<div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
<input class="form-control" type="password" value="qx2C0VIysR" placeholder="Secret (e.g., mysupersecretpassword)" autocomplete="off" id="password" onkeypress="return checkEnter(this, event);" />
</div>
<div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="fa fa-quote-right fa-fw"></i></span>
<input class="form-control" type="text" placeholder="Display name (e.g., Alice Smith)" autocomplete="off" id="displayname" onkeypress="return checkEnter(this, event);" />
</div>
<div class="btn-group btn-group-sm" style="width: 100%">
<button class="btn btn-primary" autocomplete="off" id="register" style="width: 30%">Register</button>
<div class="btn-group btn-group-sm" style="width: 70%">
<button autocomplete="off" id="registerset" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="width: 100%">
	Registration approach<span class="caret"></span>
</button>
<ul id="registerlist" class="dropdown-menu" role="menu">
	<li><a href='#' id='secret'>Register using plain secret</a></li>
	<li><a href='#' id='ha1secret'>Register using HA1 secret</a></li>
	<li><a href='#' id='guest'>Register as a guest (no secret)</a></li>
</ul>
</div>
</div> -->
									</div>
									<div class="col-md-6 container hide" id="phone">
										<div class="input-group margin-bottom-sm">
											<span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
											<input class="form-control" type="hidden"
												placeholder="SIP URI to call (e.g., sip:1000@example.com)"
												autocomplete="off" id="peer"
												onkeypress="return checkEnter(this, event);" />
										</div>
									</div>
								</div>
								<div />
								<div id="videos" class="hide">
									<div class="col-md-6">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">You</h3>
											</div>
											<div class="panel-body" id="videoleft"></div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h3 class="panel-title">Remote UA</h3>
											</div>
											<div class="panel-body" id="videoright"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<hr>
					<div class="footer">
					</div>
				</div>



				<div class="dialpad-wrapper">
					<div class="dialpad-header">
						<div class="dialpad-header-left">
							<!-- <div class="dialpad-maximize-icon dialpad-max-min-icon">
<a href="javascript:;" class="">
<i class="fas fa-expand"></i>
</a>
</div> -->

							<div class="dialpad-minmize-icon dialpad-max-min-icon">
								<a href="javascript:;" class="">
									<i class="fas fa-compress"></i>
								</a>
							</div>

							<div class="dialpad-header-logo">
								<img src="assets/images/omni-logo.png" height="25">
							</div>
						</div>
						<div class="dialpad-header-right">


							

							<div class="add-wrap-icon" onclick="initCallService()"
style="display:none"								>
								<i class="fas fa-cog"></i>
							</div>

							<div class="status-indication-bar on-hook-status" id="dialstatus"
								style="background-color: #f23333;">
							</div>

							<div class="queue-login-status QueueLogOut" id="queueStatsu">
								<h1 style="color: #33f247;
font-size: 25px; cursor: pointer;
" onclick="myQueues()">Q</h1>
							</div>
							<div class="call-status-small">
								<p id="call-text">Off Hook</p>
							</div>
						</div>
						<div class="webrtc_dailpad"
							style="visibility: hidden;width: 0px; height: 0px;position: fixed;left: 0;">
							<input type="hidden" value='0' id='make_call_number'>
							<!-- <input type="hidden" value='0' id='makecallHanupBtn'> -->
							<div id='makecallHanupBtn'></div>
							<div id="incomingCallAlert" style="display: none;">
							</div>
							<div id="incomingCall" style="display: none;">
								<button id="incomingCallAnswerBtn" type="button"></button>
								<button id="incomingCallHangupBtn" type="button"></button>
							</div>
							<div id="remoteDisplay"></div>
							<div class="fp-localDisplay">
								<div id="localDisplay"></div>
							</div>
						</div>
					</div>
					
					<div class="dialpad-body dialpad_layout" id="dialpad_layout">
						<div id="number_dialer">
							<div class="search-field">
								<input id="dialpad_number" class="form-control" placeholder="Enter ther number.." type="text" name="searchfield">
								<div class="number-delete has-img" id="" onclick="dialPadbackSpace()"><img
										src="assets/images/delete.png" height="24">
								</div>
							</div>
							<div class="dial-panel">
								<div class="row">
									<div class="digit" id="one" onclick="keyPad(1)" onkeypress="keyPad(1)">1</div>
									<div class="digit" id="two" onclick="keyPad(2)">2 <div class="sub">ABC</div>
									</div>
									<div class="digit" id="three" onclick="keyPad(3)">3 <div class="sub">DEF</div>
									</div>
								</div>
								<div class="row">
									<div class="digit" id="four" onclick="keyPad(4)">4 <div class="sub">GHI</div>
									</div>
									<div class="digit" id="five" onclick="keyPad(5)">5 <div class="sub">JKL</div>
									</div>
									<div class="digit" id="six" onclick="keyPad(6)">6 <div class="sub">MNO</div>
									</div>
								</div>
								<div class="row">
									<div class="digit" id="seven" onclick="keyPad(7)">7 <div class="sub">PQRS</div>
									</div>
									<div class="digit" id="eight" onclick="keyPad(8)">8 <div class="sub">TUV</div>
									</div>
									<div class="digit" id="nine" onclick="keyPad(9)">9 <div class="sub">WXYZ</div>
									</div>
								</div>

								<div class="row">
									<div class="digit" id="star" onclick="keyPad('*')">*
									</div>
									<div class="digit" id="zero" onclick="keyPad(0)">0
									</div>
									<div class="digit" id="hash" onclick="keyPad('#')">#
									</div>
								</div>


								<div class="row" id="call-action-btn-group">
									<!-- <div class="digit has-img" onclick="dialPadview('user_list_login')">
<img src="assets/images/dialpad-search-icon.png" height="28">
</div> -->
									<div class="digit"></div>
									<div class="digit on list_details call-green-btn" id="call"><span
											class="call-btn-icon"><i class="fa rotate-full fa-phone"
												aria-hidden="true"></i></span>
									</div>

								</div>

								<!-- <div class="botrow">
<div class="dig list_details" onclick="dialPadview('user_list_login')">
<i class="fa fa-user-circle" aria-hidden="true"></i>
<span class="btn-icon-name">Contacts</span>
</div>
<div class="dig list_details"><i class="fa fa-th" aria-hidden="true"></i>
<span class="btn-icon-name">Keypad</span>
</div>

<div class="dig list_details" onclick="dialPadview('recent_list_widget')">
<i style="font-size: 22px;" class="fas fa-clock"></i>
<span class="btn-icon-name">Recents</span>
</div>
</div> -->

							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- <div class="circle-plus view-dialpad" onclick="getStatus()">
<a href="javascript:void(0);">
<div class="pulse_bg"><i class="fas fa-phone-alt"></i></div>
<div class="pulse1"></div>
<div class="pulse2"></div>
</a>
</div> -->
		</div>



	</div>
	<!----------------------------------
Right Column
----------------------------------->
	<div class="right-column">
<div id="loaders" style="display:none">
						<div class="loader"></div>
						<h2 style="color: #fff;" id='connectingStr'></h2>
					</div>
		<div class="tab-content">
			<div id="contacts" class="tab-pane fade in active">
				<h4 class="mb-4">Contacts</h4>
				<div class="discussion search"><span class="fa fa-search form-control-feedback"></span><input type="text" id="contactSearch" placeholder="Search Contacts..."/></div>
				<div class="contact-list">
					<div class="row" id="mycontcts">

						<!----------------------------
						Contact Card
						------------------------------>
						
					</div>
				</div>

			</div>
			<div id="history" class="tab-pane fade">

				<h4 class="mb-4">Call History</h4>
				<!-- Tabs -->
				<section id="custom-tabs">
					<div class="container">
						<div class="row">
							<div class="col-xl-12 " >
								<nav>
									<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
										<a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab"
											href="#nav-all" role="tab" aria-controls="nav-all"
											aria-selected="true" onclick="getMyCallHistoryDetails('All')">All</a>
									
										<a class="nav-item nav-link" id="nav-incoming-tab" data-toggle="tab"
											href="#nav-incoming" role="tab" aria-controls="nav-incoming"
											aria-selected="false" onclick="getMyCallHistoryDetails('incoming')">Incoming</a>
										<a class="nav-item nav-link" id="nav-outgoing-tab" data-toggle="tab"
											href="#nav-outgoing" role="tab" aria-controls="nav-outgoing"
											aria-selected="false" onclick="getMyCallHistoryDetails('outgoing')">Outgoing</a>
									</div>
								</nav>
								<div class="tab-content" id="nav-tabContent">

									<div class="tab-pane fade show active" id="nav-all" role="tabpanel"
										aria-labelledby="nav-all-tab">

										<table class="table-striped table-bordered call-table">
											<tbody id="myAllCallHistory">

											</tbody>
										</table>

									</div>

									<div class="tab-pane fade" id="nav-missed" role="tabpanel"
										aria-labelledby="nav-missed-tab">

										<table class="table-striped table-bordered call-table">
											<tbody id="myMissedCallHistory">
											
											</tbody>
										</table>

									</div>
									<div class="tab-pane fade" id="nav-incoming" role="tabpanel"
										aria-labelledby="nav-incoming-tab">

										<table class="table-striped table-bordered call-table">
											<tbody id="myIncomingCallHistory">
												
											</tbody>
										</table>
									</div>

									<div class="tab-pane fade" id="nav-outgoing" role="tabpanel"
										aria-labelledby="nav-outgoing-tab">
										<table class="table-striped table-bordered call-table">
											<tbody id="myOutgoingCallHistory">
												
											</tbody>
										</table>
									</div>

								</div>

							</div>
						</div>
					</div>
				</section>
				<!-- ./Tabs -->

			</div>
            <div id="agents" class="tab-pane fade">
				<h4 class="mb-4">Agents List</h4>
				<div class="discussion search"><span class="fa fa-search form-control-feedback"></span><input type="text" id="agentsSearch" placeholder="Search Agents..."/></div>
				<div class="contact-list agents-list">
					<div class="row" id="myagentsDiv">

						
						
					</div>
				</div>

			</div>
			<div id="chat" class="tab-pane fade">
				<h4 class="mb-4">Chat</h4>

				<div class="contact-list">
				    <div class="chat-section-panel">
						<div class="container-block">
							<div class="row">
					            <div class="col-sm-12 col-lg-3 col-md-3">
								    <div class="discussions">
									    <div class="discussion search">
									      <div class="searchbar"><i class="fa fa-search"></i>
									        <input type="text" placeholder="Search..."/>
									      </div>
									    </div>
									    <ul class="chat-tabs">
											<li class="tab tabs-active">Active</li>
											<li class="tab">Closed</li>
											
										</ul>
										<div class="container--content">
											<div class="content content-active" id="chatHEads">
									       
											    <div class="contacts message-active">
											      <div class="photo" style="background-image:url(assets/images/user.jpg)">
											        <div class="status"></div>
											      </div>
											      <div class="message">
											        <div class="name_Profile">Chat Lists</div>
											        <div class="text_message"></div>
											      </div>
											    </div>
											</div>
											<div class="content"  id="chatHeadsClosed">
									          
											</div>
											<div class="content">
											    <div class="contacts">
											      <div class="photo" style="background-image:url(assets/images/user.jpg)">
											        <div class="status"></div>
											      </div>
											      <div class="message">
											        <div class="name_Profile">Test4</div>
											        <div class="text_message">The uniform policy of Melton Christian School in Melbourne prohibits non-Christian head coverings for boys.</div>
											      </div>
											    </div>
											</div>
										</div>
								    </div>
								</div>
								<div class="col-sm-12 col-lg-9 col-md-9" id="chatMessagesMainB">
									<div class="chat">
									    <div class="chat-header-section">
										    <div class="header-chat"><i class="icon fa fa-user-o"></i>
										    	<div class="profile-photo" style="background-image:url(assets/images/user.jpg)"></div>
										    	<div class="chat-header-profile">
										            <div class="contact-profle" id="chatUserNAme"></div>
												    <div class="contact-profle" id="chatWidgetNAme"></div>
												</div>    
										      
										    </div>
										</div>
									    <div class="messages-chat" id="chatMessages">
										  

									    </div>
										
									    <div class="footer-chat">
											
									        <input class="write-message" id="chatMessageSend" type="text" placeholder="Type your message here"/><a id="sendChatIcons" onclick="sendChatMessage()" class="icon send fa fa-paper-plane clickable"></a>
									    </div>
									</div>
								</div>
						    </div>
						</div>    
					</div>
				</div>

			</div>


		</div>

	</div>
	<!----------------------------------
Right Column End
----------------------------------->

	</div>

	<!-- <div class="circle-plus dialpad-refresh" style='display:none'>
<a href="javascript:void(0);">
<div class="pulse_bg"><i class="fas fa-phone-alt"></i></div>
<div class="pulse1"></div>
<div class="pulse2"></div>
</a>
</div> -->

	<input type='hidden' id='sip_local_time' value=''>
	<input type='hidden' id='sip_load_count' value='3'>
	<input type='hidden' id='incoming_call_request_data' value='1'>
	<input type='hidden' id='jsep' value='1'>
	<!-- The Modal -->
	<div class="modal dialpad-modal" id="addWrapCodeModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title">Add Details</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<form>
						<div class="row">

							<div class="col-md-6 col-12">
								<div class="form-group">
									<label for="name">Name</label>
									<input type="text" name="name" class="form-control">
								</div>
							</div>

							<div class="col-md-6 col-12">
								<div class="form-group">
									<label for="user_name">SIP User Name</label>
									<input type="text" name="user_name" class="form-control">
								</div>
							</div>

							<div class="col-md-6 col-12">
								<div class="form-group">
									<label for="auth_id">SIP Auth ID</label>
									<input type="text" name="auth_id" class="form-control">
								</div>
							</div>

							<div class="col-md-6 col-12">
								<div class="form-group">
									<label for="auth_password">SIP Auth Password</label>
									<input type="text" name="auth_password" class="form-control">
								</div>
							</div>


						</div>
					</form>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">Save Changes</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>





	<input type="hidden" id="incoming_call_end_trigger" onclick="incomingCallEnd()">

	<!--------------------------------------
Call Forward
--------------------------------------->
	<div class="forwardDialpadPanel hide-fwd-dialpad" id="animate-dialpad-show" style="display: none;">
		<div class="dialpad-close-icon" id="close-call-fwd-modal">
			<i aria-hidden="true" class="fa fa-times"></i>
		</div>
		<div class="row">
			<div class="col-12">
				<div id="dialpad-wrapper">
					<div class="forwordDialPad">
						<div class="dialpad-header">
							<label for="makeCallForwordNumber">Transfer / Forward</label>
						</div>
						<!--   <div class="col-md-12">
<div class="form-group">
<label for="makeCallForwordNumber">Call Number</label>
<input type="text" id="makeCallForwordNumber" name="makeCallForwordNumber" placeholder="Enter the number..." readonly="true">
</div>  
</div> -->
						<div class="dial-panel" id="trans_contact">
							<div class="tab-content" id="trans_keypad">
								<!-- <div class="tab-pane fade" >
<div class="contact-panel">
<div class="search-field">
<input id="dialpad_number" class="form-control" type="text" name="searchfield" placeholder="Search User Calls">
</div>

<div class="contact-list-item"><div class="contact-number-details"><span class="user-img"><img src="assets/images/user.jpg"></span><h5>14</h5></div></div>
</div>	
</div> -->
								<div class="tab-pane fade show active" role="tabpanel"
									aria-labelledby="trans_keypad-tab">
									<div class="search-field">
										<input class="form-control" id="makeCallForwordNumber"
											placeholder="Enter the number..." readonly="true" type="text">
									</div>
									<div class="row noMargin">
										<div class="digit" id="one" onclick="TkeyPad(1)" (keypress)="TkeyPad(1)">1</div>
										<div class="digit" id="two" onclick="TkeyPad(2)">2 <div class="sub">ABC</div>
										</div>
										<div class="digit" id="three" onclick="TkeyPad(3)">3 <div class="sub">DEF</div>
										</div>
									</div>
									<div class="row noMargin">
										<div class="digit" id="four" onclick="TkeyPad(4)">4 <div class="sub">GHI</div>
										</div>
										<div class="digit" id="five" onclick="TkeyPad(5)">5 <div class="sub">JKL</div>
										</div>
										<div class="digit" id="six" onclick="TkeyPad(6)">6 <div class="sub">MNO</div>
										</div>
									</div>
									<div class="row noMargin">
										<div class="digit" id="seven" onclick="TkeyPad(7)">7 <div class="sub">PQRS</div>
										</div>
										<div class="digit" id="eight" onclick="TkeyPad(8)">8 <div class="sub">TUV</div>
										</div>
										<div class="digit" id="nine" onclick="TkeyPad(9)">9 <div class="sub">WXYZ</div>
										</div>
									</div>

									<div class="row noMargin">
										<div class="digit" id="star" onclick="TkeyPad('*')">*
										</div>
										<div class="digit" id="zero" onclick="TkeyPad(0)">0
										</div>
										<div class="digit" id="hash" onclick="TkeyPad('#')">#
										</div>
									</div>
								</div>
							</div>
							<div class="row noMargin" id="call-action-btn-group">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link" id="trans_contact-tab" data-toggle="tab"
											onclick="viewtransfercontact()" role="tab" aria-controls="trans_contact"
											aria-selected="false">
											<div class="dig list_details"><i class="fa fa-user-circle"
													aria-hidden="true"></i></div>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="trans_keypad-tab" data-toggle="tab" href="#trans_keypad"
											role="tab" aria-controls="trans_keypad" aria-selected="true">
											<div [class]="this.dial_status == 'ESTABLISHED' ? 'dig off list_details call-green-btn' :'dig on list_details call-green-btn'"
												id="transfer" onclick="makecallTransfer()"><span
													class="call-btn-icon"><img src="assets/images/call-forward.png"
														height="32"></span></div>
										</a>
									</li>
									<li class="nav-item" onclick="dialPadbackSpaceT()">
										<a class="nav-link" id="trans_keypad-tab" data-toggle="tab" href="#trans_keypad"
											role="tab" aria-controls="trans_keypad" aria-selected="false">
											<div class="digit has-img" id=""><img
													src="assets/images/dialpad-clear-icon.png" height="20"></div>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--------------------------------------
Call Forward End
--------------------------------------->


	<!-- 	<div class="modal dialpad-modal" id="opencallDialer">
<div class="modal-dialog modal-lg">
<div class="modal-content">


<div class="modal-header">
<h5 class="modal-title">Add Details</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>


<div class="modal-body">
<div class="row">
<div class="col-12">
<div id="dialpad-wrapper">
<div class="forwordDialPad">
<div class="dialpad-header">
<label for="makeCallForwordNumber">Transfer / Forward</label>
</div>
<div class="col-md-12">
<div class="form-group">
<label for="makeCallForwordNumber">Call Number</label>
<input type="text" id="makeCallForwordNumber" name="makeCallForwordNumber" placeholder="Enter the number..." readonly="true">
</div>  
</div> 

<div class="search-field">
<input class="form-control" id="makeCallForwordNumber" placeholder="Enter the number..." readonly="true" type="text">
</div>


<div class="dial-panel" >
<div class="row noMargin">
<div class="digit" id="one" onclick="TkeyPad(1)" (keypress)="TkeyPad(1)">1</div>
<div class="digit" id="two" onclick="TkeyPad(2)">2 <div class="sub">ABC</div></div>
<div class="digit" id="three" onclick="TkeyPad(3)">3 <div class="sub">DEF</div></div>
</div>
<div class="row noMargin">
<div class="digit" id="four" onclick="TkeyPad(4)">4 <div class="sub">GHI</div></div>
<div class="digit" id="five" onclick="TkeyPad(5)">5 <div class="sub">JKL</div></div>
<div class="digit" id="six" onclick="TkeyPad(6)">6 <div class="sub">MNO</div></div>
</div>
<div class="row noMargin">
<div class="digit" id="seven" onclick="TkeyPad(7)">7 <div class="sub">PQRS</div></div>
<div class="digit" id="eight" onclick="TkeyPad(8)">8 <div class="sub">TUV</div></div>
<div class="digit" id="nine" onclick="TkeyPad(9)">9 <div class="sub">WXYZ</div></div>
</div>

<div class="row noMargin">
<div class="digit" id="star" onclick="TkeyPad('*')">*
</div>
<div class="digit" id="zero" onclick="TkeyPad(0)">0
</div>
<div class="digit" id="hash" onclick="TkeyPad('#')">#
</div>
</div>

<div class="row noMargin" id="call-action-btn-group">
<div class="digit has-img">
</div>
<div [class]="this.dial_status == 'ESTABLISHED' ? 'dig off list_details call-green-btn' :'dig on list_details call-green-btn'" id="call" onclick="makecallTransfer()"><span class="call-btn-icon"><img  src="assets/images/call-forward.png" height="32"></span>
</div>
<div class="digit has-img" id="" onclick="dialPadbackSpaceT()"><img src="assets/images/dialpad-clear-icon.png" height="20">
</div>
</div>			
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div> -->






	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/6.4.0/adapter.min.js"></script>
	<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.6.0/js/md5.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		<script src="https://statics.teams.microsoft.com/sdk/v1.5.2/js/MicrosoftTeams.min.js" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/popper-umd-1.16.0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-4.5.0.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="js/mconnect-webrtc.js"></script>
	<script type="text/javascript" src="js/webConnect.js"></script>
	<script type="text/javascript" src="js/dialpad.js"></script>
	<script type="text/javascript" src="js/chat.js"></script>
	<script type="text/javascript">
	microsoftTeams.initialize();

		$(".dialpad-container .dialpad-close-icon").click(function () {
			$(".dialpad-container").slideToggle("slow");
		});
		$(".circle-plus.view-dialpad").click(function () {
			$(".dialpad-container").slideToggle("slow");
		});

		// 	$(".circle-plus.view-dialpad").click(function(){
		// $(".forwardDialpadPanel ").slideToggle("slow");
		// });
$(document).ready(function(){
document.addEventListener('DOMContentLoaded', function() {
 if (!Notification) {
  alert('Desktop notifications not available in your browser. Try Chromium.');
  return;
 }

 if (Notification.permission !== 'granted')
  Notification.requestPermission();
});
});

		$(".dialpad-list-group li #contactsTab").click();

	</script>
	<script type="text/javascript">
		$(".forwardDialpadPanel .dialpad-close-icon").click(function () {
			$(".forwardDialpadPanel ").slideToggle("slow");
		});
	</script>
	<script type="text/javascript">
	
		/* Change for Dinesh Feb 15
		$(document).ready(function(){
		var data = '{"operation_type":"openChat2","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:565px;width:700px; z-index:3"}';
		console.log(data);
		window.parent.postMessage(data, '*');
		// unsend();
		//$(".circle-plus view-dialpad").click();
		});
		function unsend() {
		
		var data = '{"operation_type":"openChat2","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:565px;width:700px; z-index:3"}';
		console.log(data);
		window.parent.postMessage(data, '*');
		}*/
		function send() {
			var data = '{"operation_type":"openChat2s","status_style":"position: fixed;right: -12px;bottom: 40px;border:none;height:140px;width:350px","top_widget":"send"}';
			window.parent.postMessage(data, '*');
			var data = '{"operation_type":"openChat","status_style":"position: fixed;right: -12px;bottom: 40px;border:none;height:140px;width:350px","top_widget":"send"}';
			window.parent.postMessage(data, '*');
		}
		function unsend() {

			var data = '{"operation_type":"openChat2us","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:565px;width:700px; z-index:3","top_widget":"unsend"}';
			window.parent.postMessage(data, '*');
			var data = '{"operation_type":"openChat","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:565px;width:700px; z-index:3","top_widget":"unsend"}';
			window.parent.postMessage(data, '*');
		}
//      function phone() {

// var data = '{"operation_type":"openChat2","status_style":"position: fixed;right: -12px;bottom: 40px;border:none;height:140px;width:350px"}';
// console.log(data);
//          window.parent.postMessage(data, '*');
//      }


		
function logoutMain(){
window.localStorage.clear(); 
	window.location.href ="https://" + window.location.hostname + "/ms-sso/simplesamlphp/www/module.php/core/as_logout.php?AuthId=default-sp&ReturnTo=https%3A%2F%2F" + window.location.hostname + "%2Fms-sso%2Fsimplesamlphp%2F";
}	
		
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
	// vars = str_replace(' ', '', vars);

    return vars;
  }
			
		
var login = getUrlVars()["login"];
var email = getUrlVars()["email"];

		
if(login != undefined || login==''){

localStorage.setItem('login', login);
localStorage.setItem('email', email);
}		

	

		
		
function getDialer(){
    alert(login);
/* 	var login = localStorage.getItem('login');
	alert(login);
	let taskInfo = {
    title: null,
    height: null,
    width: null,
    url: null,
    card: null,
    fallbackUrl: null,
    completionBotId: null,
};

taskInfo.url = "https://" + window.location.hostname + "/mconnectDialer/?login="+login;
taskInfo.title = "Custom Form";
taskInfo.height = 510;
taskInfo.width = 430;
submitHandler = (err, result) => {
    console.log(`Submit handler - err: ${err}`);
    console.log(`Submit handler - result\rName: ${result.name}\rEmail: ${result.email}\rFavorite book: ${result.favoriteBook}`);
};
microsoftTeams.tasks.startTask(taskInfo, submitHandler);
*/
	
 window.location.href = "https://webrtcserver.mconnectapps.com/index-test2.html";	
	
}		
		
microsoftTeams.authentication.notifySuccess();	
		
function getMyCallHistoryDetails(callT){
  //$('#loaders').show();
  var login = localStorage.getItem('login');
  var dt = new Date();
  //var query = { operation: "queue", moduleType: "queue", api_type: "web", element_data: { action: "getContact",login:login,reason: "-","status":"1" } };
	let api_req = new Object();
    let dialpad_req = new Object();
	dialpad_req.search_text = "";
	dialpad_req.login = login;
	dialpad_req.limit = 50;
	dialpad_req.action = "getMyCallHistoryDetails";
	dialpad_req.call_type = callT;
	dialpad_req.order_by_name ="history.callid" ;
	dialpad_req.offset = 0;
	api_req.operation = "call";
	api_req.moduleType = "call";
	api_req.api_type = "web";
	api_req.element_data = dialpad_req;

 
  $.ajax({
      type: "POST",
	  url: "https://" + window.location.hostname + "/api/v1.0/index.php",
	  data: JSON.stringify(api_req),
	  contentType: "application/json; charset=utf-8",
	  dataType: "json",
	  success: function(response){
		$('#loaders').hide();
		      	var list_datas = response.result.data.list_data;

        console.log(list_datas);

        contacts = [];

        for (let index = 0; index < list_datas.length; index++) {
         	var data = list_datas[index];
			var call_type =  data.call_type;
			var call = "assets/images/missed-call.png";
			var callTpe = "badge badge-danger";
			if(call_type =="outgoing"){
				var call = "assets/images/outgoing-call.svg";
				callTpe = "badge badge-info";
			} else if(call_type =="incoming"){
				var call = "assets/images/incoming-call.svg";
				 callTpe = "badge badge-success";
			}
         contacts.push( '<tr><td class="text-center"><img class="call-type-icon" src="'+call+'"></td><td class="text-center"><span class="'+callTpe+'">'+data.call_data+'</span></td> <td class="text-left">'+data.phone+' <a data-toggle="tooltip" title="Click to call '+data.phone+'" href="javascript:;" class="btn" onclick="clickTocall('+data.phone+')"><i class="fas fa-phone"></i></a><div class="call-timespan">'+data.call_start_dt+'</div></td></tr>');
         }
		  
		  if(callT == 'All'){
		  	$('#myAllCallHistory').html(contacts);
		  } else if(callT == 'MissedCall'){
		  	$('#myMissedCallHistory').html(contacts);
		  } else if(callT == 'incoming'){
		  	$('#myIncomingCallHistory').html(contacts);
		  } else if(callT == 'outgoing'){
		  	$('#myOutgoingCallHistory').html(contacts);
		  }

		  
    	}
	});     
  }

function getMyContacts(search){
	if(!search){
		// $('#loaders').show();
	} 
 
  var login = localStorage.getItem('login');
  var dt = new Date();
  //var query = { operation: "queue", moduleType: "queue", api_type: "web", element_data: { action: "getContact",login:login,reason: "-","status":"1" } };
	let api_req = new Object();
    let dialpad_req = new Object();
	dialpad_req.search_text = search;
	dialpad_req.login = login;
	dialpad_req.limit = 50;
	dialpad_req.action = "getMyContactDetails";
	dialpad_req.order_by_name ="contact_id" ;
	dialpad_req.offset = 0;
	api_req.operation = "call";
	api_req.moduleType = "call";
	api_req.api_type = "web";
	api_req.element_data = dialpad_req;

 
  $.ajax({
      type: "POST",
	  url: "https://" + window.location.hostname + "/api/v1.0/index.php",
	  data: JSON.stringify(api_req),
	  contentType: "application/json; charset=utf-8",
	  dataType: "json",
	  success: function(response){
		$('#loaders').hide();
		  
    	var list_datas = response.result.data.list_data;

        console.log(list_datas);

        contacts = [];

        for (let index = 0; index < list_datas.length; index++) {
         var data = list_datas[index];
			var str =  data.first_name;
			if(str == ''){
				str = 'UNKNOWN';
			}
			var twoLeterStr = str.substring(0, 2);
         contacts.push( '<div class="col-xl-3 col-lg-4 col-md-6 card-holder"><div class="card mb-3"><div class="card-body"><ul class="contact-details-list"><li><div class="contact-round-holder">' + twoLeterStr + '</div></li><li class="contact-card-info"><h6 class="mb-1">' + str + '</h6><p class="mb-1 mt-2">' + data.phone + '</p></li><a href="javascript:;" data-toggle="tooltip" title="Click to call '+data.phone+'" class="btn contact-call btn-circle btn-success m-b-xs" onclick="clickTocall('+data.phone+')"><i class="fas fa-phone"></i></a></ul></div></div></div>');
         }

		  $('#mycontcts').html(contacts);
    	}
	});     
  }		

$('#contactsTab').click();

		
													  
													  
													  
function getMyAgents(search){
	if(!search){
		 //$('#loaders').show();
	} 
  var login = localStorage.getItem('login');
  var dt = new Date();
  //var query = { operation: "queue", moduleType: "queue", api_type: "web", element_data: { action: "getContact",login:login,reason: "-","status":"1" } };
	let api_req = new Object();
    let dialpad_req = new Object();
	dialpad_req.search_text = search;
	dialpad_req.login = login;
	dialpad_req.limit = 50;
	dialpad_req.action = "getMyAgentDetails";
	dialpad_req.order_by_name ="contact_id" ;
	dialpad_req.offset = 0;
	api_req.operation = "call";
	api_req.moduleType = "call";
	api_req.api_type = "web";
	api_req.element_data = dialpad_req;

 
  $.ajax({
      type: "POST",
	  url: "https://" + window.location.hostname + "/api/v1.0/index.php",
	  data: JSON.stringify(api_req),
	  contentType: "application/json; charset=utf-8",
	  dataType: "json",
	  success: function(response){
		$('#loaders').hide();
		  
    	var list_datas = response.result.data.list_data;

        //console.log(list_datas); return false;

        agents = [];

        for (let index = 0; index < list_datas.length; index++) {
         var data = list_datas[index];      
			agents.push('<div class="col-xl-3 col-lg-4 col-md-6 card-holder"><div class="card mb-3"><div class="card-body"><ul class="contact-details-list"><li><span class="user-img"><img src="assets/images/user.jpg"></span></li><li class="contact-card-info"><h6 class="mb-1">'+data.agent_name+'</h6><p class="mb-1 mt-2">'+data.sip_login+'</p></li><a href="javascript:;" class="btn contact-call btn-circle btn-success m-b-xs" data-toggle="tooltip" title="Click to call '+data.sip_login+'"  onclick="clickTocall('+data.sip_login+')"><i class="fas fa-phone"></i></a></ul></div></div></div>');
         }

		 $('#myagentsDiv').html(agents);
    	}
	});     
  }														  
													  
													  
	</script>
		<script type="text/javascript">
$('.hamburger').on('click', function () {
$('.menu').toggleClass('open');
});
$('.dialpad-list-group li a').on('click', function () {
$('.menu li a').removeClass('active');
});
	</script>
	<script type="text/javascript">
const tabs = document.querySelectorAll(".tab");
const contents = document.querySelectorAll(".content");

for (let i = 0; i < tabs.length; i++) {
	tabs[i].addEventListener("click", () => {
		for (let j = 0; j < contents.length; j++) {
			contents[j].classList.remove("content-active");
		}
		for (let jj = 0; jj < tabs.length; jj++) {
			tabs[jj].classList.remove("tabs-active");
		}
		contents[i].classList.add("content-active");
		tabs[i].classList.add("tabs-active");
	});
}

		
		

$("#dialpad_number").on("keyup",function(event) {
	str= $("#dialpad_number").val();
    str = str.replace(/\s/g, '');
	$("#dialpad_number").val(str);
	 if (event.keyCode === 13) {
        $("#call").click();
    }
});
		
		
$("#contactSearch").on("keyup",function(event) {
  str= $("#contactSearch").val();
	getMyContacts(str);
});	
		
$("#agentsSearch").on("keyup",function(event) {
  str= $("#agentsSearch").val();
	getMyAgents(str);
});	

		getMyContacts();
		getMyChatLists();

var input = document.getElementById('dialpad_number');
input.focus();
input.select();
	</script>
</body>

</html>