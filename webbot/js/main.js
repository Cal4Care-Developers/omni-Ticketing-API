
var widget_appr = 0;
var global_dept = '';
var global_extno = '';
var newimageLink = '';


function send() {
    if (localStorage.getItem('type') == "bot" && localStorage.getItem('available') == "yes") {
        localStorage.type = "bot";
        $('#chat').css('display', 'block');

        $("#chatget").css("display", "block");
    } else {
        localStorage.type = "webchat";
        $('#chat').css('display', 'none');

        $("#chatget").css("display", "none");
        var data = '{"operation_type":"openChat","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:600px;z-index:2;"}';
        console.log(data);
        window.parent.postMessage(data, '*');
    }
}
function unsend() {


    if (widget_appr == 'offline') {
        var data = '{"operation_type":"openChat","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:200px;width:275px;z-index:2;"}';
    } else {
        var data = '{"operation_type":"openChat","status_style":"position: fixed;right: 0px;bottom: 0px;border:none;height:45px;width:275px;z-index:2;"}';
    }

    window.parent.postMessage(data, '*');


}

$(document).ready(function () {
    $(".circle-plus, .flat-chat-header").click(function () {
        $(".chat-panel").toggleClass("hide");
        $(".chat-icon").toggleClass("hide");

    });
    $(".close-all-chat").click(function () {
        $(".chat-panel").toggleClass("hide");
    });

    var parent = $(window.frameElement).parent();
    console.log(parent);

    var chat_id_m = localStorage.getItem('chats');
    var widget_activity_time = '600000';

    if (chat_id_m != null) {

        InitiateChatOld(chat_id_m);
    }
});



function InitiateChatOld(chat_id) {


    var admin = getUrlParameter('aid');
    var widget_id = getUrlParameter('wid');

    var query = { operation: "chat_widget", moduleType: "chat_widget", api_type: "web", element_data: { action: "get_chat_data", chat_id: chat_id, user_id: admin } };


    $.ajax({
        type: "POST",
        url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
        data: JSON.stringify(query),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {


            if (data.result.data == 0) {
                console.log(data.result.status);
            } else {

                $('#chat_module').html(data.result.data);
                $('#chat_msg_data').css('display', 'block');
                $('#end-chat').css('display', 'block');
            }

        }
    });

}






//var websocket = new WebSocket("wss://cal4care.info:8089/"); 
//var websocket = new WebSocket("wss://myscoket.mconnectapps.com:4000/chat"); 
var websocket = new WebSocket("wss://myscoket.mconnectapps.com:4004");
websocket.onopen = function (event) {
    agentOnline();
    console.log('open');
}
var count = '';
var message_sound = '';
var disable_chat_rating = '';
var chats = [];

$(window).focus(function () {
    count = 0;
});
websocket.onmessage = function (event) {

    console.log(event.data);
    // var datam = {};
    // datam = event.data;
    //chats.push(datam);



    if (event.data != null && event.data != "") {
        var chat_message_data, message_data, chat_id;
        message_data = JSON.parse(event.data);
        console.log(message_data);

		if(message_data.message_status == "transfer_existing"){
				console.log('trancsf');
			chat_id = $('#chat_id').val();
			 if (message_data.message_info.chat_id == chat_id && chat_id != "") {
				 console.log(chat_id);
				var dec = decodeURIComponent(escape(window.atob(message_data.message_info.message)));
				console.log(dec);   	
		chat_message_data ='<div style="width: 70%;margin: 0 auto;"><p style="background: #e1f3fb;padding: 6px 20px;border-radius: 15px;font-size: 12px;font-weight: 700;text-align: center;" >' + dec + '</p></div>';
				 
			 $(".chat-container #messages").append(chat_message_data);
                chatautoScroll();
				 
			 }
		   }
		
        if (message_data.message_type == "chat" && message_data.message_info.from != "client") {
            // alert(message_data.message_info.from);
            chat_id = $('#chat_id').val();
            if (message_data.message_info.chat_id == chat_id && chat_id != "") {


                count++;
                mainAlert(count);

                if (message_data.message_info.msg_user_type == "1") {


                    chat_message_data = '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p>' + message_data.message_info.message + '</p><span>You</span></div>';

                }
                if (message_data.message_info.msg_user_type == "3") {
                    endChatNotshow2();
                    window.clearTimeout(timeoutID);
                    //endChatEmail(message_data.message_info.chat_id);
                    //	endChat();

                }
                else {

                    //chat_message_data = '<div class="agent-msg msg-row"><img class="agent-avatar" src="' + message_data.message_info.agent_aviator + '"><p>' + message_data.message_info.message + '';

                    //var nameArr = message_data.message_info.chat_images.split(',');

                    //  nameArr.forEach(element => {
                    //				  		chat_message_data += '<img class="chat_image" style="width: 100%;height: 100%;" src=' + message_data.message_info.chat_images + '>';
                    //  console.log(element);
                    //	                });



                   // var dec = window.atob(message_data.message_info.message);
					var dec = decodeURIComponent(escape(window.atob(message_data.message_info.message)));

                    if (message_data.message_info.chat_images) {

                        chat_message_data = '<div class="agent-msg msg-row"><img class="agent-avatar" src="' + message_data.message_info.agent_aviator + '">';

                        if (message_data.message_info.extension == 'png' || message_data.message_info.extension == 'jpeg' || message_data.message_info.extension == 'jpg' || message_data.message_info.extension == 'gif' || message_data.message_info.extension == 'svg' || message_data.message_info.extension == 'apng' || message_data.message_info.extension == 'bmp') {


                            // chat_message_data += '<p style="max-width:75px;" id="main">'+dec+'<img class="chat_image" style="width: 100%;height: 100%;" src=' + message_data.message_info.chat_images + '></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';
                            chat_message_data += '<p style="max-width:73px;" id="main">' + dec + '<a target="_blank" href="' + message_data.message_info.chat_images + '"><img class="chat_image" style="width: 100%;height: 100%;" src=' + message_data.message_info.chat_images + '></a></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';
                            // chat_message_data += '<p style="max-width:75px;">' + message_data.message_info.message +'<img class="chat_image" style="width: 100%;height: 100%;" src=' + message_data.message_info.chat_images + '></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';


                        } else {
                            chat_message_data += '<p style="max-width:74px;" id="main">' + dec + '<a target="_blank" href="' + message_data.message_info.chat_images + '"><img class="chat_image" style="width: 100%;height: 100%;" src="images/quickView.png"></a></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';


                            // chat_message_data += '<p style="max-width:75px;" id="main">'+dec+'<img class="chat_image" style="width: 100%;height: 100%;" src=' + message_data.message_info.chat_images + '></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';

                            //chat_message_data += '<p style="max-width:75px;">' + message_data.message_info.message +'<a target="_blank" href="' + message_data.message_info.chat_images+'"><img class="chat_image" style="width: 100%;height: 100%;" src="images/quickView.png"></a></p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div>';

                        }

                        //	var dec = window.atob(message_data.message_info.message);
                        //document.getElementById('main').innerHTML = dec;
                        //'+message_data.message_info.message+'


                    } else {
                        //var dec = window.atob(message_data.message_info.message);
                        //var newone = document.getElementById("main");
                        //'+message_data.message_info.message+'
                        //newone.innerHTML = dec;
                        //var dec = window.atob(message_data.message_info.message);
						var dec = decodeURIComponent(escape(window.atob(message_data.message_info.message)));
						
                        chat_message_data = '<div class="agent-msg msg-row"><img class="agent-avatar" src="' + message_data.message_info.agent_aviator + '"><p id="main">' + dec + '</p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div >';
                    }

                }
                //chat_message_data +='</p><span class="agent_name">' + message_data.message_info.agent_name + '</span></div >';	
                $(".chat-container #messages").append(chat_message_data);
                chatautoScroll();

            }

        }

    }

};




websocket.onerror = function (event) {
    console.log('error');
};
websocket.onclose = function (event) {
    console.log('close');
};



window.addEventListener('beforeunload', chatOfflineStatus);

function chatOfflineStatus() {
    var chat_id = $("#chat_id").val();
   var offline_data_socket = '{"message_type":"chat_new","message_status":"onandoff","message_info" : {"chat_id" : "' + chat_id + '","status" : "0"}}';
 websocket.send(offline_data_socket);
	
	var offline_data = '{"operation": "web_chat","moduleType": "web_chat","api_type": "web_chat","element_data": {"action": "on_off_status", "chat_id":"'+chat_id+'","onoff_status":"0"}}';
   
	
	
    $.ajax({
        type: "POST",
        url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
        data: JSON.stringify(offline_data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

		console.log(data);

        }
    });

}

function endOfflineStatus(chat_id){
   // var chat_id = $("#chat_id").val();
   var offline_data_socket = '{"message_type":"chat_new","message_status":"onandoff","message_info" : {"chat_id" : "' + chat_id + '","status" : "0"}}';
 websocket.send(offline_data_socket);
}	
	
function chatOnlineStatus() {
    var chat_id = $("#chat_id").val();

	
	    $.ajax({
        type: "POST",
        url: "message.php",
        data: {
            action_data: "on_off_status",
            chat_id: chat_id,
            onoff_status: '1'
        },

        success: function (data) {
    var online_data = '{"message_type":"chat_new","message_status":"onandoff","message_info" : {"chat_id" : "' + chat_id + '","status" : "1"}}';
    websocket.send(online_data);
        }

    });
	
}



function appnotification(type,name,msg) {


    var query = { operation: "agents", moduleType: "agents", api_type: "web", access_token: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ0aWNrZXRpbmcubWNvbm5lY3RhcHBzLmNvbSIsImF1ZCI6InRpY2tldGluZy5tY29ubmVjdGFwcHMuY29tIiwiaWF0IjoxNjMwOTMyMTE5LCJuYmYiOjE2MzA5MzIxMTksImV4cCI6MTYzMDk1MDExOSwiYWNjZXNzX2RhdGEiOnsidG9rZW5fYWNjZXNzSWQiOiI2NCIsInRva2VuX2FjY2Vzc05hbWUiOiJTYWxlc0FkbWluIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.YzdTs9NxXf-KVffqXCNz8cyff-vMwcH8YI9eC8Ji8Fc", element_data: { action: "test_notification", extension_no: window.global_extno,cust_name:name,chat_msg:msg,type:type } };

    $.ajax({
        type: "POST",
        url: "https://erp.cal4care.com/cms/apps/index.php",
        data: JSON.stringify(query),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

		console.log(data);

        }
    });

}


function chatautoScroll() {

    $(".chat-body").scrollTop($(".chat-body")[0].scrollHeight);
}


function onMessageSend(event_data) {
   var chat_id = $("#chat_id").val();
	 var ontyping_socket = '{"message_type":"chat_new","message_status":"typing_status","message_info" : {"chat_id" : "' + chat_id + '","status" : "1"}}';
 websocket.send(ontyping_socket);
	clearTimeout(timer);
 var timer = setTimeout(function(){ 	 
	  var offtype_socket = '{"message_type":"chat_new","message_status":"typing_status","message_info" : {"chat_id" : "' + chat_id + '","status" : "0"}}';
 websocket.send(offtype_socket); }, 3000); 
	
    if ((event_data.keyCode || event_data.which) == 13) {

        sendChatMessageData();
        event_data.preventDefault();
        return false;

    }

}




var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

function newstartchat(messageval) {

    var url = window.location.href; //for current url
    var captured = /aid=([^&]+)/.exec(url)[1]; // Value is in [1] ('384' in our case)
    var admin = captured ? captured : '0';

    var adminS = atob(admin);
    var chat_id = $("#chat_id").val();
    var customer_id = $("#customer_id").val();
    var chat_msg_id = $("#chat_msg_id").val();
    var customer_web_code = $("#customer_web_code").val();
    var customer_name = $("#customer_name").val();
    var customer_email = $("#customer_email").val();
    //var department_users = $('#queue_id').find(':selected').data('deptusers');
    var department_users = window.global_dept;

    console.log(department_users);
    var widget_id = getUrlParameter('wid');
    $('#chat_module').html('Loading');
    $.ajax({
        type: "POST",
        url: "message.php",


        data: {
            action_data: "new_chat_generate",
            chat_id: chat_id,
            customer_id: customer_id,
            chat_message: messageval,
            chat_msg_id: chat_msg_id,
            queue_id: '0',
            customer_web_code: customer_web_code,
            customer_name: customer_name,
            customer_email: customer_email,
            msg_user_type: '1',
            admin_id: localStorage.getItem('admin_id'),
            department: localStorage.getItem('department'),
            widget_name: localStorage.getItem('widget_name')

        },
        success: function (data) {

            var chat_message_data = data.split('^^^^');
            console.log(chat_message_data);
            if (chat_message_data[0] == 1) {
                $('#chat').css('display', 'none');

                $("#chatget").css("display", "none");
                $('#chat_module').html(chat_message_data[1]);

                $('#chat_msg_data').css('display', 'block');
                $('#end-chat').css('display', 'block');

                var chat_id = $("#chat_id").val();
                localStorage.setItem('chats', chat_id);
                var customer_id = $("#customer_id").val();
				var cust_names = $("#customer_name").val();
                var chat_msg_data = '{"message_type":"chat","message_status":"new","message_info" : {"chat_id" : "' + chat_id + '","msg_user_id" : "' + customer_id + '","msg_user_type" : "1","msg_type":"text","message" : "' + messageval + '","queue_id":"' + queue_id + '","admin_id":"' + adminS + '","widget_name":"' + widget_id + '","from":"client", "department_users":"' + department_users + '"}}';
                websocket.send(chat_msg_data);
				chatOnlineStatus();
                chatautoScroll();
				appnotification('New',cust_names,messageval);

                //  setup()
            }

        }

    });


}


function startChat(valids, inputval, type) {

    //

    var chatUrl = (window.location != window.parent.location)
        ? document.referrer
        : document.location.href;

    var url = window.location.href; //for current url
    var captured = /aid=([^&]+)/.exec(url)[1]; // Value is in [1] ('384' in our case)
    var admin = captured ? captured : '0';

    var web_chat_id = parseInt(Math.random() * 1000000000);
    var customer_name = $('#customer_name').val();
    var customer_email = $('#customer_email').val();
    var chat_message = $('#chat_message').val();
    var queue_id = $('#queue_id').val();
    var department = $('#department_id').val();
    //var department_users = $('#department_users').val();
    var department_users = $('select#queue_id').find(':selected').data('deptusers');
    console.log(department_users);
    window.global_dept = $('select#queue_id').find(':selected').data('deptusers');
	   window.global_extno = $('select#queue_id').find(':selected').data('extno');
    console.log(window.global_dept);

    //return false;
    if (customer_name == "") {
        swal("warning!", "Please enter the Name", "warning");
        return false;
    }

    if (customer_email == "" && localStorage.getItem('customer_email') == "") {
        swal("warning!", "Please enter the Email", "warning");
        return false;
    }


    if (!validateEmail(customer_email) && !validateEmail(localStorage.getItem('customer_email'))) {
        swal("warning!", "Please enter the valid Email", "warning");
        return false;
    }

    //if(chat_message == ""){
    //alert("Please enter the message");
    // return false;
    //}

    if (queue_id == "") {
        alert("Please Select the department");
        return false;
    }


    var widget_id = getUrlParameter('wid');

    var adminS = atob(admin);
    var client_ip = $("#client_ip").val();
    var client_city = $("#client_city").val();
    var client_country = $("#client_country").val();


    localStorage.customer_name = customer_name;
    localStorage.customer_email = customer_email;
    localStorage.chat_message = '';
    localStorage.customer_web_code = web_chat_id;
    localStorage.admin_id = admin;
    localStorage.user_id = adminS;
    localStorage.client_ip = client_ip;
    localStorage.client_city = client_city;
    localStorage.client_country = client_country;
    localStorage.department = queue_id;
    localStorage.chatUrl = chatUrl;
    localStorage.widget_name = widget_id;


    if (valids == 2) {
        $("#chatget").css("display", "none");
        localStorage.type = "webchat";

        var messageval;

        if (type == "bot") {
            messageval = inputval;
        } else {
            messageval = $('#chat_message').val();

        }
        let date = new Date();
        let options = {
            weekday: "long", year: "numeric", month: "short",
            day: "numeric", hour: "2-digit", minute: "2-digit"
        };

        console.log(date.toLocaleTimeString("en-us", options));
        localStorage.chatstart = date.toLocaleTimeString("en-us", options);
        $('#chat_module').html('Loading');
        $.ajax({
            type: "POST",
            url: "message.php",
            //         data: {
            //             action_data: "chat_generate",
            //             customer_name: customer_name,
            //             customer_email: customer_email,
            //             chat_message: chat_message,
            //             queue_id: '0',
            //             customer_web_code: web_chat_id,
            //             admin_id:admin,
            //             user_id: adminS,
            //             client_ip: client_ip,
            //             client_city: client_city,
            //             client_country: client_country,
            // department: queue_id,
            // chatUrl: chatUrl,
            // widget_name: widget_id

            //         },
            data: {
                action_data: "chat_generate",
                customer_name: localStorage.getItem('customer_name'),
                customer_email: localStorage.getItem('customer_email'),
                chat_message: messageval,
                queue_id: '0',
                customer_web_code: localStorage.getItem('customer_web_code'),
                admin_id: localStorage.getItem('admin_id'),
                user_id: localStorage.getItem('user_id'),
                client_ip: localStorage.getItem('client_ip'),
                client_city: localStorage.getItem('client_city'),
                client_country: localStorage.getItem('client_country'),
                department: localStorage.getItem('department'),
                chatUrl: localStorage.getItem('chatUrl'),
                widget_name: localStorage.getItem('widget_name')

            },
            success: function (data) {

                var chat_message_data = data.split('^^^^');

                if (chat_message_data[0] == 1) {

                    $('#chat_module').html(chat_message_data[1]);

                    $('#chat_msg_data').css('display', 'block');
                    $('#end-chat').css('display', 'block');

                    var chat_id = $("#chat_id").val();
                    localStorage.setItem('chats', chat_id);
                    var customer_id = $("#customer_id").val();
					var cust_names = $("#customer_name").val();
                    var chat_msg_data = '{"message_type":"chat","message_status":"new","message_info" : {"chat_id" : "' + chat_id + '","msg_user_id" : "' + customer_id + '","msg_user_type" : "1","msg_type":"text","message" : "' + chat_message + '","queue_id":"' + queue_id + '","admin_id":"' + adminS + '","widget_name":"' + widget_id + '","from":"client", "department_users":"' + department_users + '"}}';
                    websocket.send(chat_msg_data);
					chatOnlineStatus();
                    chatautoScroll();
					appnotification('New',cust_names,chat_message);
                    // setup()
                }

            }

        });
    }
    else {



        var messageval;

        //if(type  == "bot"){
        //	messageval = inputval;
        //}else{
        //	 messageval = $('#chat_message').val();	
        //}
        let date = new Date();
        let options = {
            weekday: "long", year: "numeric", month: "short",
            day: "numeric", hour: "2-digit", minute: "2-digit"
        };

        console.log(date.toLocaleTimeString("en-us", options));
        localStorage.chatstart = date.toLocaleTimeString("en-us", options);
        $('#chat_module').html('Loading');
        $.ajax({
            type: "POST",
            url: "message.php",
            //         data: {
            //             action_data: "chat_generate",
            //             customer_name: customer_name,
            //             customer_email: customer_email,
            //             chat_message: chat_message,
            //             queue_id: '0',
            //             customer_web_code: web_chat_id,
            //             admin_id:admin,
            //             user_id: adminS,
            //             client_ip: client_ip,
            //             client_city: client_city,
            //             client_country: client_country,
            // department: queue_id,
            // chatUrl: chatUrl,
            // widget_name: widget_id

            //         },
            data: {
                action_data: "chat_generate",
                customer_name: localStorage.getItem('customer_name'),
                customer_email: localStorage.getItem('customer_email'),
                chat_message: messageval,
                queue_id: '0',
                customer_web_code: localStorage.getItem('customer_web_code'),
                admin_id: localStorage.getItem('admin_id'),
                user_id: localStorage.getItem('user_id'),
                client_ip: localStorage.getItem('client_ip'),
                client_city: localStorage.getItem('client_city'),
                client_country: localStorage.getItem('client_country'),
                department: localStorage.getItem('department'),
                chatUrl: localStorage.getItem('chatUrl'),
                widget_name: localStorage.getItem('widget_name')

            },
            success: function (data) {

                var chat_message_data = data.split('^^^^');

                if (chat_message_data[0] == 1) {
                    console.log(chat_message_data);
                    $('#chat_module').html(chat_message_data[1]);
                    //  addChat("", "&#128075; Hi I am a Bot. Let me know if you have any questions regarding our tool!");
                    var start_chat = $("#start_chat").val();
                    addChat("", start_chat);

                    $('#chat').css('display', 'block');

                    $("#chatget").css("display", "block");


                    setup()
                }

            }

        });



    }

}

//getQueueDetail();

function getQueueDetail() {
    $.ajax({
        type: "POST",
        url: "message.php",
        data: {
            action_data: "queue_list"

        },
        success: function (data) {
            // console.log(data);

            var queue_chat_list = data.split('^^^^');

            if (queue_chat_list[0] == 1) {

                $("#queue_id").append(queue_chat_list[1]);

            }

        }

    });
}

function clearFileUploadingField() {
    var input = $("#chat_media");
    input.replaceWith(input.val('').clone(true));
}


function textUrl(text) {
    var urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
    //var urlRegex = /(https?:\/\/[^\s]+)/g;
    var html = text.replace(urlRegex, function (url, b, c) {
        var url2 = (c == 'www.') ? 'http://' + url : url;
        return '<a class="hyperlink-text hyper-right" href="' + url2 + '" target="_blank">' + url + '</a>';
    })
    return html
}

function sendChatMessageData() {
    var url = window.location.href; //for current url
    var captured = /aid=([^&]+)/.exec(url)[1]; // Value is in [1] ('384' in our case)
    var admin = captured ? captured : '0';
    var adminS = atob(admin);
    var form = new FormData();
    var chat_message = $("#chat_msg").val();
    chat_message = chat_message.trim();
    var new_chat_message = textUrl(chat_message);
	   if(chat_message.length <= 0 && (document.getElementById('chat_media')).files[0] == undefined){
    	alert("Please enter the message or Upload a file");
    	 return false;
    }
	
    var chat_id = $("#chat_id").val();
    var customer_id = $("#customer_id").val();
	var cust_names = $("#customer_name").val();
    var queue_id = $("#queue_id").val();
    var widget_id = getUrlParameter('wid');
    //if (chat_message.length > 0) {
    //  $.ajax({
    //             type: "POST",
    //             url: "message.php",
    //             data: {
    //                 action_data: "send_chat_message",
    //                 chat_id: chat_id,
    //                 customer_id: customer_id,
    //                 chat_message: chat_message

    //             },
    //             success: function (data) {


    //                var chat_msg_id =  data.trim();

    //                if(chat_msg_id != "" && chat_msg_id != 0){


    //                    var chat_message_data = '{"message_type":"chat","message_status":"existing","message_info" : {"chat_id" : "'+chat_id+'","msg_user_id" : "'+customer_id+'","msg_user_type" : "1","msg_type":"text","chat_msg" : "'+chat_message+'","queue_id":"'+queue_id+'","admin_id":"'+adminS+'","widget_name":"'+widget_id+'","from":"client"}}';
    //                    websocket.send(chat_message_data);

    // 					var chat_message_data = '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p>'+chat_message+' </p><span>You</span></div>';

    //                     $(".chat-container #messages").append(chat_message_data);

    //                     chatautoScroll();

    //                }

    //             }

    //         });

    if ((document.getElementById('chat_media')).value != null) {
        var totalfiles = (document.getElementById('chat_media')).files.length;
        //for (var index = 0; index < totalfiles; index++) {
        form.append("up_files", (document.getElementById('chat_media')).files[0]);
        //}
    }

    form.append("chat_id", chat_id);
    form.append("chat_type", "webchat");
    form.append("customer_id", customer_id);
    form.append("chat_message", new_chat_message);
    form.append("chat_msg", new_chat_message);
    form.append("action", "update_chat_message");
    //form.append("up_files", fileInput.files[0], "/C:/Users/mdras/Desktop/ddd.png");
    //form.append("up_files", fileInput.files[0], "/C:/Users/mdras/Desktop/download.jfif");
    //form.append("up_files", fileInput.files[0], "/C:/Users/mdras/Desktop/matrix-failed.png");

    var settings = {
        "url": "https://omnitickets.mconnectapps.com/api/v1.0/index_new.php",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Authorization": "Basic TWVyY2hhbnQuVEVTVDEwMDAwMDI0NTA0OjQ1NjVhOTI4NGQ0ZjFkMjE3YzI5OTY5ZGUxNTc1YzM2"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": form
    };

    $.ajax(settings).done(function (response) {

        var newres = JSON.parse(response);
        // var chat_msg_id =  response.trim();
        console.log(newres);
        window.newimageLink = newres.data.chat_images;
        //  if(chat_msg_id != "" && chat_msg_id != 0){


        var chat_message_data = '';
        console.log(window.newimageLink);
        if (newres.data.chat_images) {
            //	 chat_message_data += '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;"><img class="paragraph-img" src="'+window.newimageLink+'">'+newres.data.chat_msg+'</p><span>You</span></div>';

            //if (newres.data.extension == 'doc' && newres.data != 'docx' && newres.data.extension != 'pdf' && newres.data.extension != 'csv' && newres.data.extension != 'txt') {
            if (newres.data.extension == 'png' || newres.data == 'jpeg' || newres.data == 'jpg' || newres.data.extension == 'apng' || newres.data.extension == 'gif' || newres.data.extension == 'bmp') {
                // chat_message_data += '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;"><img class="paragraph-img" src="'+newres.data.chat_images+'">'+newres.data.chat_msg+'</p><span>You</span></div>';
                chat_message_data += '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;"><a target="_blank" href="' + newres.data.chat_images + '"><img class="paragraph-img" src="' + newres.data.chat_images + '">' + newres.data.chat_msg + '</a></p><span>You</span></div>';
            } else {

                chat_message_data += '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p style="max-width:75px;"><a target="_blank" href="' + newres.data.chat_images + '"><img class="paragraph-img" src="images/quickView.png"></a>' + newres.data.chat_msg + '</p><span>You</span></div>';


            }



        } else {
            chat_message_data += '<div class="customer-msg msg-row"><img src="images/user-theme.svg"><p>' + newres.data.chat_msg + '</p><span>You</span></div>';
        }


        $(".chat-container #messages").append(chat_message_data);

        console.log(window.global_dept);
        var chat_newmessage_data = '{"message_type":"chat","message_status":"existing","message_info" : {"chat_id" : "' + chat_id + '","msg_user_id" : "' + customer_id + '","msg_user_type" : "1","msg_type":"text","chat_msg" : "' + chat_message + '","department_users" : "' + window.global_dept + '","queue_id":"' + queue_id + '","admin_id":"' + adminS + '","widget_name":"' + widget_id + '","customer_name":"' + newres.data.customer_name + '","from":"client","chat_images":"' + window.newimageLink + '","extension":"' + newres.data.extension + '"}}';
        websocket.send(chat_newmessage_data);
				appnotification('Existing',cust_names,chat_message);
        clearFileUploadingField();
        chatautoScroll();

        // }


    });


    //}
    $("#chat_msg").val("");

}






function endChat() {
    $("#off-endchat").hide();
    $("#on-endchat").show();
}




function endChats() {//alert();
    $("#off-endchat").show();
    $("#on-endchat").hide();


    var chat_id = $("#chat_id").val();
    var chat_key = $("#chat_key").val();
    var waitingMsg = "<div><h4 class='thank-mes'>Please wait...</h4></div>";
    var server_details = $("#server_details").val();
    var widget_id = getUrlParameter('wid');
    // $('#chat_module').html(waitingMsg);
    $('#chat_msg_data').css('display', 'none');
    $('#end-chat').css('display', 'none');
    $('#endedchat').css('display', 'block');
    var rating = "<form class='user-rating-form'><input type='hidden' id='chat_id' value=" + chat_id + "><input type='hidden' id='chat_key' value=" + chat_key + "><div ><h4>Please rate your experience with our consultant.</h4></div><fieldset class='rating'><input type='radio' id='star5' name='rating' value='5' /><label class = 'full' for='star5' title='Awesome - 5 stars'></label><input type='radio' id='star4half' name='rating' value='4.5' /><label class='half' for='star4half' title='Pretty good - 4.5 stars'></label><input type='radio' id='star4' name='rating' value='4' /><label class = 'full' for='star4' title='Pretty good - 4 stars'></label><input type='radio' id='star3half' name='rating' value='3.5' /><label class='half' for='star3half' title='3.5 stars'></label><input type='radio' id='star3' name='rating' value='3' /><label class = 'full' for='star3' title='3 stars'></label><input type='radio' id='star2half' name='rating' value='2.5' /><label class='half' for='star2half' title='2.5 stars'></label><input type='radio' id='star2' name='rating' value='2' /><label class = 'full' for='star2' title='2 stars'></label><input type='radio' id='star1half' name='rating' value='1.5' /><label class='half' for='star1half' title='1.5 stars'></label><input type='radio' id='star1' name='rating' value='1' /><label class = 'full' for='star1' title='1 star'></label><input type='radio' id='starhalf' name='rating' value='0.5' /><label class='half' for='starhalf' title='0.5 stars'></label></fieldset><button type='button' class='btn submit-btn agent-online' onclick='rateUs()'>Submit</button></form>";

    if (chat_id != 0) {
        $('#chat_module').html(rating);

        $('#chat_msg_data').css('display', 'none');
        $('#end-chat').css('display', 'none');
        $('#endedchat').css('display', 'block');
        chat_msg_data = "endChat";
        $('#chat_module').html(message);
        //endChatEmail(chat_id);
    }
}




function endChatNotshow() {

    $("#off-endchat").show();
    $("#on-endchat").hide();
    $("#chat_msg_data").hide();
    $("#end-chat").hide();
    localStorage.removeItem('chats');
    var chat_id = $("#chat_id").val();
    var widget_id = getUrlParameter('wid');
    var chat_key = $("#chat_key").val();
    localStorage.clear();
    var message = "<div><h4 class='thank-mes'>Thank you for Chat With Us!</h4></div>";
    var waitingMsg = "<div><h4 class='thank-mes'>Please wait...</h4></div>";
    $('#chat_module').html(waitingMsg);

    $('#chat_msg_data').css('display', 'none');
    $('#end-chat').css('display', 'none');
    $('#endedchat').css('display', 'block');
    chat_msg_data = "endChat";
    $('#chat_module').html(message);

    $(".chat-panel").toggleClass("hide");
    $('#chat').css('display', 'none');

    endChatEmail(chat_id);

    // $.ajax({
    //     type: "POST",
    //     url:  "message.php",
    //     data: {
    //         action_data: "end_chat_message",
    //         chat_id: chat_id,
    //         chat_key: chat_key,
    //         widget_id: widget_id
    //     },
    //     success: function (data) {
    //         console.log(data);
    //         var result_data = JSON.parse(data);
    //         console.log(result_data.result);


    //     }
    // });

}

function endChatNotshow2() {

    $("#off-endchat").show();
    $("#on-endchat").hide();
    $("#chat_msg_data").hide();
    $("#end-chat").hide();
    localStorage.removeItem('chats');
    var chat_id = $("#chat_id").val();
    var widget_id = getUrlParameter('wid');
    var chat_key = $("#chat_key").val();
    localStorage.clear();
    var message = "<div><h4 class='thank-mes'>Thank you for Chat With Us!</h4></div>";
    var waitingMsg = "<div><h4 class='thank-mes'>Please wait...</h4></div>";
    $('#chat_module').html(waitingMsg);

    $('#chat_msg_data').css('display', 'none');
    $('#end-chat').css('display', 'none');
    $('#endedchat').css('display', 'block');
    chat_msg_data = "endChat";
    $('#chat_module').html(message);

    $(".chat-panel").toggleClass("hide");
    $('#chat').css('display', 'none');

    //endChatEmail2(chat_id);


}

















function agentOnline() {
    var url = window.location.href; //for current url
    var captured = /aid=([^&]+)/.exec(url)[1]; // Value is in [1] ('384' in our case)
    var widget_id = /wid=([^&]+)/.exec(url)[2];
    var admin = captured ? captured : '0';
    var widget_id = widget_id ? widget_id : '0';


    var admin = getUrlParameter('aid');
    var widget_id = getUrlParameter('wid');


    var dt = new Date();
    var time = dt.getHours() + ":" + dt.getMinutes();
    var client_city = null;
    var client_country;
    var client_ip;

    $.get("https://ipinfo.io", function (response) {
        client_city = response.city;
        client_country = response.country;
        client_ip = response.ip;

        $("#client_ip").val(client_ip);
        $("#client_city").val(client_city);
        $("#client_country").val(client_country);

        var query = { operation: "chat_widget", moduleType: "chat_widget", api_type: "web", element_data: { action: "get_single_widget_data", client_city: client_city, client_country: client_country, client_ip: client_ip, user_id: admin, chat_time: time, widget_name: widget_id } };



        $.ajax({
            type: "POST",
            url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
            data: JSON.stringify(query),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                data = data.result.data;
                console.log(data);

                var str = "";
                console.log(data.enable_dept);
                for (var items of data.enable_dept) {
                    str += "<option value=" + items.dept_id + " data-deptusers=" + items.department_users + " data-extno=" + items.get_ext_no + ">" + items.alias_name + "</option>";

                }
               // console.log(str);
                setTimeout(function () {
                    var newone = document.getElementById("queue_id");

                    newone.innerHTML = str;

                }, 3000)


                if (data.available_status == 1) {
                    localStorage.available = "yes";
                    $(".agent-offline").css('display', 'none');
                    $(".agent-online").css('display', 'block');
                } else {
                    // NB code 19-08-2021
                    localStorage.can_available = data.offline_chat;
                    if (data.offline_chat == '1') {
                        localStorage.available = "yes";
                        $(".agent-offline").css('display', 'none');
                        $(".agent-online").css('display', 'block');
                        $(".flat-chat-header").html('Offline')
                    } else {
                        localStorage.available = "no";
                        $(".agent-offline").css('display', 'block');
                        $(".agent-online").css('display', 'none');
                        $(".flat-chat-header").html('Offline')
                    }
                }

                //$(".agent-offline").css('display', 'none');
                //$(".agent-online").css('display', 'block');


                $("#mainDivClass").removeClass();
                $("#mainDivClass").addClass(data.widget_data.widget_position);
                $("#department").html('');
                $("#department_id").val(data.widget_data.departments);
                $("#department_users").val(data.department_users);

                console.log(data.widget_data.chat_aviator);
                $("#chat_image_main").attr("src", data.widget_data.chat_image);


                //$('#audiotag1').attr("src",data.widget_data.sound_file_path);
                $('#audiotag1').attr("src", data.sound_file_path);

                console.log(data.widget_data.widget_appearance);



                widget_appr = data.widget_data.widget_appearance;

                if (data.widget_data.widget_appearance == 'offline') {
                    $(".attenison-grabber-img").css('display', 'block');
                    $("#mainDivClass").addClass('has-round-chat-widget');
                } else {
                    $("#mainDivClass").addClass('has-flat-chat-widget');
                    $(".flat-chat-header").css('display', 'block');
                }

                if (data.widget_data.chat_aviator == '1') {
                    $("#chat_module").addClass("chat-aviator");
                } else {
                    $("#chat_module").addClass("no-chat-aviator");
                }


                if (data.country_status == '1') {
                    $(".wrapper").show();
                } else {
                    $(".wrapper").hide();
                }


                $("#flat-head").css('background-color', data.widget_data.color);
                $('.chat-header').css('background-color', data.widget_data.color);
                $('.chat-footer').css('background-color', data.widget_data.color);
                $('.submit-btn.btn').css('background-color', data.widget_data.color);
                $('.pulse_bg').css('background-color', data.widget_data.color);
                $('.send-btn').css('background-color', data.widget_data.color);

                if (data.widget_data.chat_agent_name == '1') {
                    $("#chat_module").addClass("chat-with-name");
                } else {
                    $("#chat_module").addClass("chat-without-name");
                }

                if (data.widget_data.disable_chat_rating == '1') {
                    disable_chat_rating = '1';
                } else {
                    disable_chat_rating = '0';
                }

                if (data.widget_data.widget_activity_time == '') {
                    widget_activity_time = '600000';
                } else {

                    widget_activity_time = data.widget_data.widget_activity_time;
                    widget_activity_time = widget_activity_time * 60000;
                }

                if (data.widget_data.has_department == '1') {
                    $("#allDeps").show();
                } else {
                    $("#allDeps").hide();
                }


                if (data.widget_data.disable_sound_notification == '1' && data.widget_data.disable_browser_tab_notification == '1') {
                    message_sound = '1';
                } else if (data.widget_data.disable_sound_notification == '0' && data.widget_data.disable_browser_tab_notification == '1') {
                    message_sound = '2';
                } else if (data.widget_data.disable_sound_notification == '1' && data.widget_data.disable_browser_tab_notification == '0') {
                    message_sound = '3';
                } else {
                    message_sound = '4';
                }


                /*  $.each(data.department, function(){
                      $("#department").append('<option value="'+ this.dept_id +'">'+ this.department_name +'</option>')
                  })*/


            },
            failure: function (errMsg) {
                alert(errMsg);
            }
        });
    }, "jsonp");

}






function mainAlert(count) {
    if (message_sound == '4') {
        var newTitle = '(' + count + ') New chat message!';
        $.titleAlert(newTitle);
        document.getElementById('audiotag1').play();
    } else if (message_sound == '2') {
        document.getElementById('audiotag1').play();
    } else if (message_sound == '3') {
        var newTitle = '(' + count + ') New chat message!';
        $.titleAlert(newTitle);
    } else {

    }
}


function validateCreditCardNumber(ccNum) {
    var ccNum = ccNum;
    var regex = /(\d)[\W_]+(?=\d)/g; // this will remove spaces in the number in a string
    var str = ccNum;
    var result = str.replace(regex, '');
    var regexs = /\b\w*\d{14,}\w*\b/gm; // this will add x if string contains more that 16
    var strs = result;
    var results = strs.replace(regexs, 'xxxxxxxxxxxxx');
    if (regexs.test(strs)) {
        return results;
    } else {
        return ccNum;
    }
}

function sendChat() {
    var url = window.location.href; //for current url
    var captured = /aid=([^&]+)/.exec(url)[1]; // Value is in [1] ('384' in our case)
    var admin = captured ? captured : '0';
    var name = $('#customer_name').val();
    var email = $('#customer_email').val();
    var department = $('#queue_id').val();
    //var department_users = $('#department_users').val();
    var department_users = $('#queue_id').val();
    var chat_message = $('#chat_message').val();
    var admin = atob(admin);
    chat_message = chat_message.trim();
    var widget_id = getUrlParameter('wid');
    chat_message = validateCreditCardNumber(chat_message);
    if (email == "") {
        swal("Error!", "Please enter the email id", "error");
        return false;
    }
    if (chat_message == "") {
        swal("Error!", "Please enter the message", "error");
        return false;
    }

    var client_ip = $("#client_ip").val();
    var client_city = $("#client_city").val();
    var client_country = $("#client_country").val();

    var query = { operation: "chat", moduleType: "chat", api_type: "web", element_data: { action: "chat_offline_message", client_city: client_city, client_country: client_country, client_ip: client_ip, user_id: admin, name: name, email: email, department: department, chat_message: chat_message, widget_id: widget_id } };



    $.ajax({
        type: "POST",
        url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
        data: JSON.stringify(query),
        contentType: "application/json; charset=utf-8",
        dataType: "json",

        success: function (data) {
            var test = '<div class="text-center"><h2 style="text-align: center !important;display: block;margin:50px auto;">Thanks For Your message</h2> </div>';
            $('#chat_module').html(test);
            $('#user_chat_email').html("<h3>" + email + " </h3>");

            $('#strart_chat').css('display', 'block');
            $('.reply-field').css('display', 'none');
            $('#chat_msg_data').css('display', 'none');
            $('#end_chat').css('display', 'none');
            var chat_id = $("#chat_id").val();
            var customer_key = $("#customer_key").val();
			var cust_names = $("#customer_name").val();
            var chat_msg_data = '{"message_type":"text","message_status":"new","message_info" : { "chat_msg_id": "' + chat_id + '", "msg_type": "text", "msg_from": "customer", "chat_message": "' + chat_message + '", "agent_id": "0", "agent_name": "0", "chat_key": "' + customer_key + '", "chat_id": "' + web_chat_id + '", "customer_key": "' + customer_email + '", "api_type": "web", "host_name":"' + host_name + '", "customer_pnr":"' + customer_pnr + '", "department_users":"' + department_users + '"}}';
            websocket.send(chat_msg_data);
			//chatOnlineStatus();
            chatautoScroll();
			appnotification('New',cust_names,chat_message);
        },
        failure: function (errMsg) {
            alert(errMsg);
        }
    });
}

function rateUs() {
    var radioValue = $("input[name='rating']:checked").val();
    var chat_id = $("#chat_id").val();
    var chat_key = $("#chat_key").val();
    var widget_id = getUrlParameter('wid');
    localStorage.removeItem('chats');
    let date = new Date();
    let options = {
        weekday: "long", year: "numeric", month: "short",
        day: "numeric", hour: "2-digit", minute: "2-digit"
    };

    console.log(date.toLocaleTimeString("en-us", options));
    var chatend = date.toLocaleTimeString("en-us", options);


    var message = "<div><h4 class='thank-mes'>Thank you for your rating!</h4></div>";
    console.log(radioValue);
    if (radioValue == undefined) {
        swal("Error!", "Please select the rating stars", "error");
        return false;
    }
    if (radioValue != 0) {
        // var query = { operation: "chat", moduleType: "chat", api_type: "web", element_data: { action: "chat_rating",chat_id : chat_id,widget_id : widget_id, rating_value: radioValue } };

        var query = { operation: "chat", moduleType: "chat", api_type: "web", element_data: { action: "chat_rating", chat_id: chat_id, widget_id: widget_id, rating_value: radioValue, customer_name: localStorage.getItem('customer_name'), customer_email: localStorage.getItem('customer_email'), department: localStorage.getItem('department'), chat_open: localStorage.getItem('chatstart'), chat_close: chatend, website_visited: localStorage.getItem('count') } }
        console.log(query);
        $.ajax({
            type: "POST",
            url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
            data: JSON.stringify(query),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                $('#chat_module').html(message);
                endChatEmail(chat_id);
                window.setTimeout(function () {
                    location.reload()
                    localStorage.clear();
                }, 2000)

            }
        });
    } else {
        swal("Error!", "Please select the rating stars", "error");
        return false;
    }

}


function closeThis() {
    if (disable_chat_rating == '1') {
        endChatNotshow();
    } else {
        endChats();
    }

}

function nocloseThis() {
    $("#off-endchat").show();
    $("#on-endchat").hide();
}





var timeoutID;

function setup() {
    //	alert('called');
    this.addEventListener("mousemove", resetTimer, false);
    this.addEventListener("mousedown", resetTimer, false);
    this.addEventListener("keypress", resetTimer, false);
    this.addEventListener("DOMMouseScroll", resetTimer, false);
    this.addEventListener("mousewheel", resetTimer, false);
    this.addEventListener("touchmove", resetTimer, false);
    this.addEventListener("MSPointerMove", resetTimer, false);

    startTimer();
}


function startTimer() {
    console.log(widget_activity_time);

    // wait 2 seconds before calling goInactive widget_activity_time
    timeoutID = window.setTimeout(goInactive, widget_activity_time);
}

function resetTimer(e) {

    window.clearTimeout(timeoutID);
    goActive();
}

function goInactive() {

    endChatNotshow();
}

function goActive() {
    startTimer();
}

function hidethis() {
    $("#off-endchat").hide();
    $("#on-endchat").hide();
}






function endChatEmail(chat_id) {
    var chat_key = $("#chat_key").val();
    var department_users = $("#department_users").val();
	var cust_names = $("#customer_name").val();
    var widget_id = getUrlParameter('wid');
    var admin = getUrlParameter('aid');
    admin = atob(admin);
    console.log(window.global_dept);

    var query = { operation: "chat", moduleType: "chat", api_type: "web", element_data: { action: "chat_details", chat_id: chat_id, widget_name: widget_id, user_id: admin } };

    var chat_msg_data = '{"message_type":"chat","message_status":"end","message_info" : {"chat_id" : "' + chat_id + '","admin_id" : "' + admin + '","department_users" : "' + window.global_dept + '"}}';
	endOfflineStatus(chat_id);
	appnotification('Closed',cust_names,'');
    console.log(chat_msg_data);
    websocket.send(chat_msg_data);

    $.ajax({
        type: "POST",
        url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
        data: JSON.stringify(query),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

        }
    });
}
function endChatEmail2(chat_id) {
    var chat_key = $("#chat_key").val();
    var department_users = $("#department_users").val();
    var widget_id = getUrlParameter('wid');
    var admin = getUrlParameter('aid');
    admin = atob(admin);
    console.log(window.global_dept);

    var query = { operation: "chat", moduleType: "chat", api_type: "web", element_data: { action: "chat_details", chat_id: chat_id, widget_name: widget_id, user_id: admin } };

    var chat_msg_data = '{"message_type":"chat","message_status":"end","message_info" : {"chat_id" : "' + chat_id + '","admin_id" : "' + admin + '","department_users" : "' + window.global_dept + '"}}';
	endOfflineStatus(chat_id);
	appnotification('Closed');
    console.log(chat_msg_data);
    websocket.send(chat_msg_data);

    $.ajax({
        type: "POST",
        url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
        data: JSON.stringify(query),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

        }
    });
}

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}
