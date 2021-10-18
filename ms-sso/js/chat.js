function getMyChatLists(callT){
    var login = localStorage.getItem('login');
    var dt = new Date();
    //var query = { operation: "queue", moduleType: "queue", api_type: "web", element_data: { action: "getContact",login:login,reason: "-","status":"1" } };
      let api_req = new Object();
      let dialpad_req = new Object();
      dialpad_req.search_text = "";
      dialpad_req.login = login;
      dialpad_req.chat_id = "all";
      dialpad_req.action = "chatMessagePanel";
      dialpad_req.limit ="50" ;
      dialpad_req.offset ="0" ;
      api_req.operation = "chat";
      api_req.moduleType = "chat";
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
            var list_datas = response.result.data.chat_list.chatHEads;
            $('#custoInfo').val(response.result.data.chat_list.userData);
            chatHeads = [];
            chatHeadsClosed = [];
            for (let index = 0; index < list_datas.length; index++) {
                var data = list_datas[index];
                if(data.chat_status == 2){
                    chatHeadsClosed.push( '<div class="contacts" onclick="getMyChatDetails(\'' + data.chat_id + '\', \'' + data.customer_name + '\')" ><div class="photo" style="background-image:url(assets/images/user.jpg)"> <div class="status-offline"></div></div> <div class="message"><div class="name_Profile">'+data.customer_name+'</div><div class="text_message">'+data.chat_dt+'</div> </div></div>');
                } else {
                    chatHeads.push( '<div class="contacts" onclick="getMyChatDetails(\'' + data.chat_id + '\', \'' + data.customer_name + '\')" ><div class="photo" style="background-image:url(assets/images/user.jpg)"> <div class="status"></div></div> <div class="message"><div class="name_Profile">'+data.customer_name+'</div><div class="text_message">'+data.chat_dt+'</div> </div></div>');
                }
  
            }
  
            $('#chatHEads').html(chatHeads);
             $('#chatHeadsClosed').html(chatHeadsClosed);
        }
      });     
    }
  
  
  
  function getMyChatDetails(chatId,chatUName){
      $('#custoInfchatId').val(chatId);
      $(".footer-chat").show();
    var login = localStorage.getItem('login');
    var dt = new Date();
    //var query = { operation: "queue", moduleType: "queue", api_type: "web", element_data: { action: "getContact",login:login,reason: "-","status":"1" } };
      let api_req = new Object();
      let dialpad_req = new Object();
      dialpad_req.search_text = "";
      dialpad_req.login = login;
      dialpad_req.chat_id = chatId;
      dialpad_req.action = "chatMessagePanel";
      dialpad_req.limit ="50" ;
      dialpad_req.offset ="0" ;
      api_req.operation = "chat";
      api_req.moduleType = "chat";
      api_req.api_type = "web";
      api_req.element_data = dialpad_req;
  
   
    $.ajax({
        type: "POST",
        url: "https://" + window.location.hostname + "/api/v1.0/index.php",
        data: JSON.stringify(api_req),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(response){
          $('#chatUserNAme').html(chatUName);
          var list_datas = response.result.data.chat_detail_list;
              $('#chatWidgetNAme').html(list_datas[0].widget_name);
          chatMessages = [];
  
            
               chatMessages.push( '<div class="message"><img class="user-profile" style="background-image:url(assets/images/user.jpg)"/><span><p class="message-user">Name: '+list_datas[0].customer_name+'</p></span><span><p class="message-user">Email :'+list_datas[0].customer_email+'</p></span><span><p class="message-user">Question :'+list_datas[0].chat_msg+'</p></span><span><p class="message-user">IP :'+list_datas[0].created_ip+'</p></span><span><p class="message-user">Country :'+list_datas[0].country+'</p></span></div>');
          
          for (let index = 1; index < list_datas.length; index++) {
               var data = list_datas[index];		
              var login = $('#custoInfo').val();
              if(data.msg_user_type == 1 || (data.msg_user_type == '2' && data.msg_user_id != login)){
           chatMessages.push( '<div class="message"><img class="user-profile" style="background-image:url(assets/images/user.jpg)"/><p class="message-user">'+data.chat_msg+'</p></span><span><p class="time">'+data.chat_time+'</p></span></div>');
           } else {
            chatMessages.push( '<div class="message text-only"><span><img class="user-profile" style="background-image:url(assets/images/user.jpg)"/></span><span><p class="message-user">'+data.chat_msg+'</p></span><span><p class="time">'+data.chat_time+'</p></span></div>');
           }
          }
            $('#chatMessages').html(chatMessages);
            
            var c_status = list_datas[0].chat_status;
                      if(c_status == '2'){
                              $(".footer-chat").html('Chat closed');
                      } else {
                          $(".footer-chat").html('<input type="hidden" id="custoInfchatId"><input class="write-message" id="chatMessageSend" type="text" placeholder="Type your message here"/><a id="sendChatIcons" onclick="sendChatMessage()" class="icon send fa fa-paper-plane clickable"></a>');
                      
                      }
            
            
                    chatautoScroll();  
          }
      });  
  
  }
  
  
  function sendChatMessage(){
      //alert(chatId); exit;
    var login = localStorage.getItem('login');
    var dt = new Date();
  var uId = $('#custoInfo').val();
      var chatId = $('#custoInfchatId').val();
      var chatMessage = $('#chatMessageSend').val();
      let api_req = new Object();
      let dialpad_req = new Object();
      dialpad_req.chat_message = chatMessage;
      dialpad_req.user_id = uId;
      dialpad_req.chat_id = chatId;
      dialpad_req.action = "send_chat_message";
      dialpad_req.chat_type ="webchat" ;
      api_req.operation = "chat";
      api_req.moduleType = "chat";
      api_req.api_type = "web";
      api_req.element_data = dialpad_req;
  
   
    $.ajax({
        type: "POST",
        url: "https://" + window.location.hostname + "/api/v1.0/index.php",
        data: JSON.stringify(api_req),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(response){
          getMyChatDetails(chatId);
            
             var socket_message  =  '{"message_type":"chat","message_status":"existing","message_info" : {"chat_id" : "'+chatId+'","msg_user_id" : "'+login+'","msg_user_type" : "2","msg_type":"text","message" : "'+chatMessage+'","queue_id":"1","agent_aviator":"https://omni.mconnectapps.com/ms-sso/assets/images/user.jpg","agent_name":""}}';
  
                 websocket.send(socket_message); 
            
            
            $('#chatMessageSend').val('');
          }
      });  
  
  }
  
  function chatautoScroll(){
    if($("#chatMessages").length > 0){
      $("#chatMessages").animate({ scrollTop: $('#chatMessages').prop("scrollHeight")}, 1000);
    }
  }
  
  
  var websocket = new WebSocket("wss://myscoket.mconnectapps.com:4004"); 
  websocket.onopen = function(event) {
      console.log('open');
  }
  var  count = '';
  var message_sound = '';
  var disable_chat_rating ='';
  var chats=[];
  
  $(window).focus(function() {
      count = 0;
  });
  websocket.onmessage = function(event) {
  
      console.log(event.data);
       // var datam = {};
      // datam = event.data;
      //chats.push(datam);
      
      
  
      if(event.data != null && event.data != ""){
          var chat_message_data, message_data, chat_id;
          message_data = JSON.parse(event.data);
          console.log(message_data);
          if(message_data.message_type == "chat"){
              if(message_data.message_status == "end"){
                  if(message_data.message_info.chat_id == $('#custoInfchatId').val()){
                      let chatToClose ='chat_'+message_data.message_info.chat_id;
                      $('#chatMessages').html('<div>Sorry, Chat has been closed by the customer</div>');
                      $(".footer-chat").html('Chat closed');
                      getMyChatLists();				
                      return false;
                  }
              }
              
              
              if(message_data.message_status == "end"){
                  if(message_data.message_info.chat_id == $('#custoInfchatId').val()){
                      let chatToClose ='chat_'+message_data.message_info.chat_id;
                      $('#chatMessages').html('<div>Sorry, Chat has been closed by the customer</div>');
                      $(".footer-chat").html('Chat closed');
                      getMyChatLists();				
                      return false;
                  }
              }
              
              
              if(message_data.message_status == "new"){
                  getMyChatLists();
                  notifyMe("New chat Has Been Created", "Incoming chat");
              }
              
          
  
              if(message_data.message_info.chat_id == $('#custoInfchatId').val()){
                  //this.chatPanelDetail(this.socketData.message_info.chat_id);
                  var chatId =  $('#custoInfchatId').val();
                  getMyChatDetails(chatId);
              } 
  
          }
  
      }
  
  };
  
  
  
  
  websocket.onerror = function(event){
      console.log('error');
  };
  websocket.onclose = function(event){
      console.log('close');
  }; 
  
  
  