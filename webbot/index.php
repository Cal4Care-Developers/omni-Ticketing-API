<!DOCTYPE html>
<html lang="en">
<head>
	<title>Web Chat</title>
	<meta charset="utf-8">
	<!-- jai coding -->
	<link rel="icon" href="bot.png" />
	<link rel="stylesheet" href="font/css/all.css">
	<link rel="stylesheet" href="styles.css">
	<script type="text/javascript" src="index.js" ></script>
<script type="text/javascript" src="constants.js" ></script>
<script type="text/javascript" src="speech.js" ></script>
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- jai coding end -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="css/bootstrap-4.3.1.min.css" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="wrapper">

<!-------------------------------------
you can change the class "has-flat-chat-widget" & "has-round-chat-widget"
----------------------------------->
<div class="bottom-left has-flat-chat-widget" id="mainDivClass">
	<div class="attenison-grabber-img" style="display:none">
		<img id="chat_image_main" src="images/attension-grabber.svg">
	</div>

	<div class="flat-chat-header" id="flat-head" onclick="send()" style="background-color: #4c4f5c; display:none">
		Online
	</div>

	<div class="circle-plus" >
		<a href="javascript:void(0);">
			<div class="pulse_bg">
				<img class="chat-icon" src="images/chat.svg">
			</div>
			<div class="pulse1"></div>
			<div class="pulse2"></div>
		</a>
	</div>

	<div class="chat-panel hide" >
		<div class="chat-container" id="off-endchat">
			<div class="chat-header flex">
				<div class="header-chat-icon"><img src="images/chat-icon-white.svg"></div>
				<div class="online-status"></div>
				<h3>Live Chat!! <span class="end-chat header-chat-icon" ><img src="images/chat-icon-white.svg"></span></h3>
				<div class="header-close-icon" style="display: flex;" ><i class="fa fa-minus close-all-chat" title="Hide Chat"  onclick="unsend()" aria-hidden="true"></i> 
					 <!-- <i class="fa fa-repeat" aria-hidden="true" onClick="window.location.reload();"></i> -->
					<i id="end-chat" title="End Chat" onclick="endChat()" class="fa fa-times-circle" aria-hidden="true" ></i></div>
			</div>
			<div id="chat_module">
				<div class="chat-body user-details">

					<div class="theme-chat-icon">
						<img src="images/blk-clr-chat-icon.png">
					</div>

					<h5 class="agent-online" style="display: block;">Live Chat Support</h5>
					
					
					<h5 class="agent-offline">Sorry, live chat is currently unavailable</h5>
					<!--<h5 class="agent-offline">Opening Hours</h5>
					<div class="tim-text agent-offline">
						<p id="openning_hours">Mon - Fri, 9:00am to 6:00pm</p>
						<p>Closed on Weekends and Public Holidays (Singapore)</p>
					</div> <br> -->
					<div class="tim-text agent-offline">
						<p>Please leave us a message and we will get back to you on the next working day</p>
					</div> 

					<div class="tim-text agent-online" style="display: block;">
						
						<p>Before you begin chatting with our agent, let us know some details so we can better assist you.</p>
					</div>

					<form class="user-contact-form">
						<input type="hidden" name="client_ip" id="client_ip" />
						<input type="hidden" name="client_city" id="client_city" />
						<input type="hidden" name="client_country" id="client_country" />
						<input type="hidden" name="department_id" id="department_id" />
						<input type="hidden" name="department_users" id="department_users" />
						
						<div class="form-group">
							<input type="text" name="customer_name" id="customer_name" class="inputText form-control" required/>
							<span class="floating-label">Name*</span>
						</div>

						<div class="form-group">
							<input type="text" name="customer_email" id="customer_email" class="inputText form-control" required/>
							<span class="floating-label">Email*</span>
						</div>

						<div class="form-group" id="allDeps">
							<select class="inputText form-control" required id="department">
								<option value="Development">Development</option>
								<option value="Support">Support</option>
								<option value="Testing">Testing</option>
							</select>
						</div>

						
							<div class="form-group">
								<span style="padding-right: 104px;" >Choose Department*</span> 
								<select class="form-control" id="queue_id" name="queue_id"></select>
								 
								</div>

								<div class="form-group agent-offline">
									<textarea rows="2" chat_message="chat_message" id="chat_message" class="inputText form-control" required></textarea>
									<span class="floating-label">Message*</span>
								</div> 
								<!-- <button type="button" class="btn submit-btn" onclick="startChat()">Message</button> -->
								<button type="button" class="btn submit-btn agent-offline" onclick="sendChat()">SEND EMAIL</button>
								<button type="button" class="btn submit-btn agent-online" onclick="startChat(1,'','chat')">START CHAT</button>
							</form>
						</div>
					</div>

					<div class="reply-field" id="chat_msg_data" style="display: none;">
						<textarea rows="2" name='chat_msg' id='chat_msg' placeholder="Please Enter Your Message" onkeydown="onMessageSend(event)"></textarea>
					
               <a href="javascript:void(0);" onchange="sendChatMessageData()"> <div  class="send-btn image-upload" style="right:51px;"><label for="chat_media"><img src="images/fileupload.png" /></label><input type="file" name="chat_media" id="chat_media" /></div> </a>
			   <a href="javascript:void(0);" onclick="sendChatMessageData()"><div class="send-btn"><img src="images/send-icon.svg"></div></a>
					</div>

					<div class="chat-footer">
						<span><img src="images/mconnect-white-logo.png"></span>
					</div>
        </div>




        <div class="chat-container" id="on-endchat" style="display:none">
			
          <div class="chat-header flex">
            <div class="header-chat-icon"><img src="images/chat-icon-white.svg"></div>
			  
            <div class="online-status"></div>
            <h3>Live Chat!! <span class="end-chat header-chat-icon" ><img src="images/chat-icon-white.svg"></span></h3>
            <div class="header-close-icon" style="display: flex;"  ><i class="fa fa-minus close-all-chat" title="Hide Chat" aria-hidden="true"></i> 
			  </div>
          </div>
          <div id="chat_module">
            <div class="chat-body user-details end-chat-panel">
              <!-- <h6>Need help ? You can ask us anything.</h6> -->
              <div class="end-chats">
                <h4 class="medium-title">END CHAT?</h4>
                <button type="button" class="btn submit-btn" onclick="closeThis()">YES</button>
                <button type="button" class="btn submit-btn btn-transparent" onclick="nocloseThis()">NO</button>
              </div>
            </div>
          </div>
        </div>
			</div>
	

	<!-- jai coding  -->
<div id="chatget" style="display: none">
		<div id="chat" class="robotchat" style="display: none;">

			<div class="dialpad-close-icon" onclick="closebot()">
				<i aria-hidden="true" class="fa fa-times phone-dialpad-close"></i>
			</div>
			<div class="main-title"><svg viewBox="0 0 24 24">
		    <path fill="currentColor" d="M12,2A2,2 0 0,1 14,4C14,4.74 13.6,5.39 13,5.73V7H14A7,7 0 0,1 21,14H22A1,1 0 0,1 23,15V18A1,1 0 0,1 22,19H21V20A2,2 0 0,1 19,22H5A2,2 0 0,1 3,20V19H2A1,1 0 0,1 1,18V15A1,1 0 0,1 2,14H3A7,7 0 0,1 10,7H11V5.73C10.4,5.39 10,4.74 10,4A2,2 0 0,1 12,2M7.5,13A2.5,2.5 0 0,0 5,15.5A2.5,2.5 0 0,0 7.5,18A2.5,2.5 0 0,0 10,15.5A2.5,2.5 0 0,0 7.5,13M16.5,13A2.5,2.5 0 0,0 14,15.5A2.5,2.5 0 0,0 16.5,18A2.5,2.5 0 0,0 19,15.5A2.5,2.5 0 0,0 16.5,13Z"></path>
		    </svg><span>Chatbot</span></div>
			<div id="messages" class="messages"></div>
			<div class="bottom-section">
				<input id="input" type="text" placeholder="Say something..." autocomplete="off" autofocus="true" />
				<button class="input-send" onclick="Sendreply();">
					<svg style="width:24px;height:24px">
					    <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
					</svg>
	            </button>
 


	        </div>   
		</div>
</div>
<!-- jai coding end -->
	
	
		</div>
	
	</div>
	<audio id="audiotag1" src="images/beep.mp3" preload="auto"></audio>
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="js/umd-popper-1.14.7.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-4.3.1.min.js"></script>
	
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/blink.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


	<script type="text/javascript">
		
		
		jQuery(document).ready(function($){
				if(localStorage.getItem('count') == 'NaN'){
		localStorage.count =1;
		}
localStorage.setItem('count', parseInt(localStorage.getItem('count'), 10)+1);
			
$(".dialpad-close-icon").click(function(){
$(".chat").slideToggle('medium');
});
$(".circle-plus").click(function(){
$(".chat").slideToggle('medium');
});
});

		
		
		
		
</script>
	
	
</body>
</html>