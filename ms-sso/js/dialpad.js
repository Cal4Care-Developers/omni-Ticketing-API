// $('[data-toggle="tooltip"]').tooltip(); 

// $(".dialpad-close").click(function () {
//     $(".dialpad-container").hide();
//     $('.circle-plus.view-dialpad').show(); 
// });

// $(".circle-plus.view-dialpad").click(function () {
//     $('.circle-plus.view-dialpad').hide();
//     $(".dialpad-container").show();
 
// });
 
// $(".circle-plus.dialpad-refresh").click(function () {

//     location.reload();

// });
 






function validateActiveTab() {

    if (!$('#dialpad-refresh').length) {
      //  console.log('update_refresh');
        var sip_local_time = $('#sip_local_time').val();
        var current_browser_time = localStorage.getItem('local_time');
        console.log(sip_local_time);
        console.log(current_browser_time);
        if (sip_local_time != "" && current_browser_time != sip_local_time) {
            $('.circle-plus.view-dialpad').hide();
            $(".dialpad-container").hide();
            $('.circle-plus.dialpad-refresh').show();
            $('.circle-plus.dialpad-refresh').attr('id', 'dialpad-refresh');
        }

    }


}


function dialCall() {
    

   var dialpad_number = $('#dialpad_number').val();

   if (dialpad_number.length > 2) {
       webMakeCall(dialpad_number);

 }

 }

function webMakeCall(number) {
    number = clean_number(number);
	
	   // $('#dialpad_layout').hide();    
		//$('#loaders').show();
		//$('#connectingStr').html('Connecting...');
	$('#dialpad_number').val(number);
	doCall3('',number);
	   // dialPadDetailView('call_history_detail',number);
 /*       $('#loaders').show();
        $('#connectingStr').html('Connecting...')
        $('#dialpad_layout').hide();
if (!$('#dialpad-refresh').length) {
    $(".dialpad-container").show();
     $('.circle-plus.view-dialpad').hide();
}*/

   // if (number.length > 1) {
        //$('#make_call_number').val(number);
        // dialPadDetailView('outgoing_call_inprogess', number);
		 // dialPadDetailView('call_history_detail',number);
       // call();
       // $('#out_caller_number').html(number);
       // $('#dialpad_number').val('');
    //}
}



function webMakeCallContact(number) {
    number = number;
    $('#loaders').show();
    $('#connectingStr').html('Connecting...')
    $('#dialpad_layout').hide();
    if (!$('#dialpad-refresh').length) {
        $(".dialpad-container").show();
        $('.circle-plus.view-dialpad').hide();
    }
    if (number.length > 2) {
        //alert(number);
        $('#make_call_number').val(number);
        dialPadDetailView('outgoing_call_inprogess', number);
    // call();
        $('#out_caller_number').html(number);
        $('#dialpad_number').val('');
    }
}


function outgoingCallEnd() {
    $("#makecallHanupBtn").click();
    clearTimeout(callduration_timer);
    call_history_id = $('#call_history_id').val();
    dialPadDetailView('call_history_detail', call_history_id);

}

// function incomingCallAccept() {
//     call_history_id = $('#call_history_id').val();
//     dialPadDetailView('incoming_call_inprogess', call_history_id);
//     callDuration();
//     $('.outgoing-call').hide();
//     $('#call_duration').show();
//     $('.call-extra-featurs').show(); 
//     $("#incomingCallAnswerBtn").click();
// }


function incomingCallDecline() {
    $("#incomingCallHangupBtn").click();
    call_history_id = $('#call_history_id').val();
    dialPadDetailView('call_history_detail', call_history_id);
    $('#opencallDialer').modal('hide');
    $('#makeCallForwordNumber').val('');
    $('#dialpad_number').val('');
    $('#animate-dialpad-show').attr('style', 'display: none');
}

function incomingCallEndByCustomer() {
// alert('incomingCallEndByCustomer');
    clearTimeout(callduration_timer);
    call_history_id = $('#call_history_id').val();
    dialPadDetailView('call_history_detail', call_history_id);
    $('#opencallDialer').modal('hide');
    $('#makeCallForwordNumber').val('');
    $('#dialpad_number').val('');
    $('#animate-dialpad-show').attr('style', 'display: none');
}

function incomingCallEnd() {

    $("#incomingCallHangupBtn").click();
    clearTimeout(callduration_timer);
    call_history_id = $('#call_history_id').val();
    dialPadDetailView('call_history_detail', call_history_id);
    $('#opencallDialer').modal('hide');
    $('#makeCallForwordNumber').val('');
    $('#dialpad_number').val('');
    $('#animate-dialpad-show').attr('style', 'display: none');

}

function timer(){


}

var totalSeconds;
var callduration_timer;

function callDuration() {
    totalSeconds = 0;

    callduration_timer = setInterval(countown, 1000);
}

function countown(secondsLabel, minutesLabel) {
    totalSeconds = totalSeconds+1;    
    $(".call_seconds").html(timeFormat(totalSeconds % 60));
    $(".call_minutes").html(timeFormat(parseInt(totalSeconds / 60)));
}

function timeFormat(time) {

    var time_str = time + "";
    if (time_str.length < 2) {
        return "0" + time_str;
    } else {
        return time_str;
    }

}

// function dialPadview(view_type) {
//     $.ajax({
//         type: 'POST',
//         url: 'get_data.php?data_page=dialpad',
//         data: {

//             action: 'dialpad_main',
//             view_type: view_type,
//             detail_id: null,

//         },
//         success: function (data) {


//             $('#dialpad_layout').html(data);

//         }
//     });
// }





// function dialPadDetailView(view_type, detail_id) {
//  //alert(detail_id);
//     if(detail_id == '' || detail_id == undefined){
//         detail_id = null;
//     }
//     //alert(view_type);
//     let api_req = new Object();
//     let dialpad_req = new Object();
    
//  var Murl = window.location.href; //for current url
//     var login = /login=([^&]+)/.exec(Murl)[1]; // Value is in [1] ('384' in our case)
    
    
//     dialpad_req.user_id = login;
//  dialpad_req.dialer_type = 'external';
//     dialpad_req.action = view_type;
//     if (view_type == "call_history_detail") {
//         dialpad_req.callid = detail_id;
//         $('#makeCallForword').modal('hide');
//         $('#makeCallForwordNumber').val('');
//         this.forwordPopup = 'forwarded';
//     } else if (view_type == "user_detail_view") {
//         dialpad_req.user_id = detail_id;
//     } else if (view_type == "outgoing_call_inprogess") {
//         dialpad_req.call_data = "Call to " + detail_id;
//         dialpad_req.customer_id = 0;
//         dialpad_req.call_type = "outgoing";
//         dialpad_req.phone = detail_id;
//         dialpad_req.call_status = "answered";
//         dialpad_req.call_note = "";
//     } else if (view_type == "call_incoming") {
//         dialpad_req.call_data = "Call from " + detail_id;
//         dialpad_req.customer_id = 0;
//         dialpad_req.call_type = "incoming";
//         dialpad_req.phone = detail_id;
//         dialpad_req.call_status = "open";
//         dialpad_req.call_note = "";
//     } else if (view_type == "incoming_call_inprogess") {
//         dialpad_req.callid = detail_id;
//         this.in_current_call = view_type;
//     }
//     api_req.operation = "call";
//     api_req.moduleType = "call";
//     api_req.api_type = "web";
//     api_req.access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJhdWQiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJpYXQiOjE1OTI5NjkyODMsIm5iZiI6MTU5Mjk2OTI4MywiZXhwIjoxNTkyOTg3MjgzLCJhY2Nlc3NfZGF0YSI6eyJ0b2tlbl9hY2Nlc3NJZCI6IjY0IiwidG9rZW5fYWNjZXNzTmFtZSI6ImNhbDRjYXJlIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.iqUmz6SJTR6GJdrlNBQA7cpZu8SO9YXddrSe7aGtGeI';
//     api_req.element_data = dialpad_req;

//     console.log(detail_id);
//     console.log(api_req);
//     // var operations = '{"operation":"call","moduleType":"call","api_type":"web","access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJhdWQiOiJvbW5pLm1jb25uZWN0YXBwcy5jb20iLCJpYXQiOjE1OTI5NjkyODMsIm5iZiI6MTU5Mjk2OTI4MywiZXhwIjoxNTkyOTg3MjgzLCJhY2Nlc3NfZGF0YSI6eyJ0b2tlbl9hY2Nlc3NJZCI6IjY0IiwidG9rZW5fYWNjZXNzTmFtZSI6ImNhbDRjYXJlIiwidG9rZW5fYWNjZXNzVHlwZSI6IjIifX0.iqUmz6SJTR6GJdrlNBQA7cpZu8SO9YXddrSe7aGtGeI","element_data":{"user_id":"64","action":"call_history_detail","callid":"3551"}}'

//     $.ajax({
    
//         type: "POST",
//         url: "https://omnitickets.mconnectapps.com/api/v1.0/index.php",
//         data: JSON.stringify(api_req),
//         contentType: "application/json; charset=utf-8",
//         dataType: "json",
//         success: function(response){
//             console.log(response)
//             if (response.result.status == 1) {
//                 if (view_type == "call_history_detail") {
//                     var mainPhone = response.result.data.phone;
//                     mainPhone = "'"+mainPhone+"'";
//                     var number_dialer = "'number_dailer'";
//                     var outgoingC = '<div class="contact-panel"><div class="call-screen-panel"><div class="backArrow" onclick="dialPadview('+number_dialer+')" data-toggle="tooltip" title="Back"><i aria-hidden="true" class="fa fa-arrow-left"></i></div><div class="active-call"><div class="caller-img"><img src="assets/images/user.jpg"></div><h4><b>Call Details</b></h4><h5>'+response.result.data.call_data+'</h5><div class="call-duration">'+response.result.data.call_start_dt+'</div></div><div class="botrow"><div class="dig" onclick="dialPadview('+number_dialer+')"><i aria-hidden="true" class="fa fa-th"></i><span class="btn-icon-name">Keypad</span></div><div class="dig" id="call" onclick="webMakeCall('+mainPhone+')"><i aria-hidden="true" class="fa fa-phone"></i></div></div></div></div>';
//                     $('#dialpad_layout').html(outgoingC); 
//                 } else if (view_type == "user_detail_view") {
//                     this.userDetailView = response.result.data;
//                 } else if (view_type == "outgoing_call_inprogess") {
//                     var customer_id = $('#call_customer_key').val();
//                     $('#assign_status_frm').trigger("reset");
//                     assignedStatus(customer_id);
//                  $('#loaders').hide();
//                   $('#dialpad_layout').show();
                    
                    
//                  call();
//                     var  number_dialer = "number_dailer";
//                     var outgoingC = '<div class="call-screen-panel"><div class="active-call"><div class="caller-img"><img src="assets/images/user.jpg"></div><h4><b>Outgoing Call</b></h4><h5><span class="callee">Call to '+detail_id+'</span></h5><div id="call_duration" style="display:none"><span class="call_minutes" id="call_minutes">00</span>:<span class="call_seconds" id="call_seconds">04</span></div><div class="call-icon outgoing-call" ><span class="ringing_phone"><i class="fa fa-phone"></i></span></div><div class="call-extra-featurs" style="display:none"> <div class="dig"><a class="mute-btn" id="mute_btn" href="javascript:void(0);"><i data-toggle="tooltip" title="Mute" class="fa fa-microphone unmuted-btn" aria-hidden="true" onclick="custommuteCall()"></i><i data-toggle="tooltip" title="Muted" class="fa fa-microphone-slash muted-btn" aria-hidden="true" onclick="custommuteCall()"></i><span class="call-action-label">Mute</span></a></div><div class="dig"><a class="hold_btn"  href="javascript:void(0);"><i data-toggle="tooltip" id="hold_btn" title="Hold" class="fa fa-pause" aria-hidden="true" onclick="holdCall()"></i><i data-toggle="tooltip" id="resume_btn" title="Resume" class="fa fa-play " aria-hidden="true" onclick="resumeCall()" style="display:none"></i><span class="call-action-label">Hold/Resume</span></a></div><div class="dig" id="animate-dialpad"><a class="rotate" href="javascript:void(0);" onclick="Callforword()" ><i data-toggle="tooltip" title="Call Forward" class="fas fa-random" aria-hidden="true"></i>  <span class="call-action-label">Transfer / Forward</span> </a> </div><div class="dig"><a class="rotate" href="javascript:void(0);" onclick="transferCall("199")"><i data-toggle="tooltip" title="Calls Transfer" class="fa fa-reply-all" aria-hidden="true"></i></a> <span class="call-action-label">Survey</span> </div></div></div><div class="botrow"><div class="dig" onclick="dialPadview('+number_dialer+')"><i aria-hidden="true" class="fa fa-th"></i><span class="btn-icon-name">Keypad</span></div><div class="dig oncall" id="call" onclick="outgoingCallEnd()"><i aria-hidden="true" class="fa fa-phone"></i></div></div></div>';
//                     $('#dialpad_layout').html(outgoingC);
//                                         $('#call_history_id').val(response.result.data);
//                 } else if (view_type == "call_incoming") {
//                     var incCall = dialpad_req.call_data;
//                     var number_dialer = "number_dailer";  
//                     var outgoingC = '<div><div class="call-screen-panel"><div class="active-call"><div class="caller-img"><img src="assets/images/user.jpg"></div><h4><b _ngcontent-vqe-c2="">Incoming Call</b></h4><h5>'+incCall+'</h5><div class="call-icon incoming-call"><span class="ringing_phone"><i class="fa fa-phone"></i></span></div></div><div class="botrow"><div class="dig" id="call" onclick="incomingCallAccept()"><i aria-hidden="true" class="fa fa-phone"></i></div><div class="dig oncall" id="call" onclick="incomingCallDecline()"><i class="fas fa-phone-slash"></i></div></div></div></div>';
//                     $('#dialpad_layout').html(outgoingC); 
//                     $('#call_history_id').val(response.result.data);
//                 } else if (view_type == "incoming_call_inprogess") {
//                    var number_dialer = "number_dailer";     
//                     var outgoingC = '<div class="call-screen-panel"><div class="active-call"><div class="caller-img"><img src="assets/images/user.jpg"></div><h4><b>Incoming Call</b></h4><h5><span class="callee">'+response.result.data.call_data+'</span></h5><div id="call_duration"><span class="call_minutes" id="call_minutes">00</span>:<span class="call_seconds" id="call_seconds">04</span></div><div class="call-icon outgoing-call" style="display:none;"><span class="ringing_phone"><i class="fa fa-phone"></i></span></div><div class="call-extra-featurs"><div class="dig"><a class="mute-btn" href="javascript:void(0);" id="mute_btn"><i aria-hidden="true" class="fa fa-microphone unmuted-btn" data-toggle="tooltip" onclick="custommuteCall()" title="Mute"></i><i aria-hidden="true" class="fa fa-microphone-slash muted-btn" data-toggle="tooltip" onclick="custommuteCall()" title="Muted"></i><span class="call-action-label">Mute</span></a></div><div class="dig"><a class="hold_btn" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-pause" data-toggle="tooltip" id="hold_btn" onclick="holdCall()" title="Hold"></i><i aria-hidden="true" class="fa fa-play " data-toggle="tooltip" id="resume_btn" onclick="resumeCall()" style="display:none" title="Resume"></i><span class="call-action-label">Hold/Resume</span></a></div><div class="dig" id="animate-dialpad"><a class="rotate" href="javascript:void(0);" onclick="Callforword()" ><i data-toggle="tooltip" title="Call Forward" class="fas fa-random" aria-hidden="true"></i> <span class="call-action-label">Transfer / Forward</span> </a></div><div class="dig"><a class="rotate" href="javascript:void(0);" onclick="transferCall("199")"><i data-toggle="tooltip" title="Calls Transfer" class="fa fa-reply-all" aria-hidden="true"></i></a> <span class="call-action-label">Survey</span></div></div></div><div class="botrow"><div class="dig" onclick="dialPadview('+number_dialer+')"><i aria-hidden="true" class="fa fa-th"></i><span class="btn-icon-name">Keypad</span></div><div class="dig oncall" id="call" onclick="outgoingCallEnd()"><i aria-hidden="true" class="fa fa-phone"></i></div></div></div>';
//                     $('#dialpad_layout').html(outgoingC); 
//                     $('#call_history_id').val(response.result.data.callid);
//                 } 
//                 this.dialPadActionview = view_type;

//             }
//         },
//         failure: function(errMsg) {
//             alert(errMsg);
//          $('#loaders').show();
//          $('#connectingStr').html('Sorry some Error Occured');
//          $('#dialpad_layout').hide();
//         }

//     });




//      call_history_id = $('#call_history_id').val();

// }


// function contactListSearch(data) {

//     var search_txt = $(data).val().toLowerCase();

//     $("#contactList .contact-list-item").filter(function () {

//         $(this).toggle($(this).text().toLowerCase().indexOf(search_txt.toLowerCase()) !== -1);
//     });
// }

// function userListSearch(data) {

//     var search_txt = $(data).val().toLowerCase();

//     $("#userList .contact-list-item").filter(function () {

//         $(this).toggle($(this).text().toLowerCase().indexOf(search_txt.toLowerCase()) !== -1);
//     });

// }

// function recentCallSearch(data) {

//     var search_txt = $(data).val().toLowerCase();

//     $("#recentCalls .contact-list-item").filter(function () {

//         $(this).toggle($(this).text().toLowerCase().indexOf(search_txt.toLowerCase()) !== -1);
//     });

// }

function dialPadbackSpace() {


    // clearTimeout(callduration_timer);

  //  var dialpad_number = $('#makeCallForwordNumber').val();
        var dialpad_number = $('#dialpad_number').val();

    //alert(dialpad_number);
    
    $('#dialpad_number').val(dialpad_number.substring(0, dialpad_number.length - 1));
}

function keyPad(key_data) {

    var dailed_number = $('#dialpad_number').val();
    $('#dialpad_number').val(dailed_number + key_data);

}








function clean_number(number) {
    console.log(number);
    number = number.trim();
    return number.replace(/[\s-/.\u00AD]/g, '');
}

function q_login(){
    
        $.ajax({
        type: 'POST',
        url: 'updateSipToken.php',
        data: {

            action: 'update_queue'

        },
        success: function (data) {
            
            
            if(data.trim() =='queue_red'){
                
               $('#make_call_number').val('*63');
            }
            else{
                $('#make_call_number').val('*62');
            }
            
            
        call('queue_call');
        $('.queue_login').attr('id',data.trim());

            

        }
    });

}
                            


// function Callforword(){
//  //alert();
//     //$('#opencallDialer').modal('show');
//  $('#animate-dialpad-show').attr('style', 'display: block');
//  $('#animate-dialpad-show').attr('style', 'right: 360px');
// }






function inProgressdialPad(action,type){
    
    if(action == 'close'){
        
        $('.call_inproress_dialpad').css('display','none');
        $('.call-screen-panel').css('display','block');
    }
    else if(action == 'open'){
        
        if(type == 'call_tranfer'){
            
            $('.inprogress_call_btn').attr('id','call_tranfer');
        }
        else if(type == 'dtmf'){
                
            $('.inprogress_call_btn').attr('id','call_send_dtmf');
        }
        
        $('.call-screen-panel').css('display','none');
        $('.call_inproress_dialpad').css('display','block');
        
    } 
    
}

function customtransferCall(){
    
   
    var dialpad_number = $('#dialpad_number').val();

    
    if (currentCall) {
         $('.inprogress_call_btn').attr('id','call_tranfer_hold');
        currentCall.transfer(dialpad_number);
    }
    
    $('#dialpad_number').val('');
    
}

function transferFailed(){
$('.inprogress_call_btn').attr('id','call_tranfer');
}

function customsendDTMF(){
    
    var dialpad_number = $('#dialpad_number').val();
    if (currentCall) {
        currentCall.sendDTMF(dialpad_number,'');
    }
     $('#dialpad_number').val('');
    
}
//  function proactiveCallMonitorData(){

//         var active_cmpid = $('#active_cmpid').val();
        
//         if(active_cmpid=='402'){
//         console.log(active_cmpid);
//             proactiveCallMonitor();
        
//         }
        
//  }
// proactiveCallMonitorData();
        
//         function proactiveCallMonitor() {
            
//              proactiveCallDetails();
//              setTimeout(proactiveCallMonitor, 30000);

//          }

//          function proactiveCallDetails(){
         
//           var active_login_userid = $('#active_login_userid').val();
         
//                 $.ajax({
//                     type: 'POST',
//                     url: 'dailer_call_queue.php',
//                     data: {

//                         action: 'proactive_callqueue',
//                         user_id: active_login_userid

//                     },
//                     success: function (data) {
//                   var customer_id = data.trim();
//                   console.log(customer_id);  
//                      if(customer_id != 0){
                            
//                             if (!currentCall) {

//                                   dialPadDetailView('contact_detail_proactive_dialer',customer_id);

//                                 }
                            
//                      }
                       

//                     }
//                 });
     
//          }

function assignedStatus(cus_id){
       $('#customer_id').val(cus_id)
       $('#assign-number-popup').modal('show');    
}


// function formCommandsValidation(){
//     var assign_status_id = document.getElementById('assign_status_id')['value'];
//     var next_schedule_dt = document.getElementById('next_schedule_dt')['value'];
//     var contact_commands = document.getElementById('contact_commands')['value'];
    
//     if(assign_status_id==''){
//         alert('Select the assign status');
//         document.getElementById('assign_status_id').facus();
//         return false;
//     }
    
//     if(assign_status_id=='3' && next_schedule_dt==''){
//         alert('Select the schedule date time');
//         document.getElementById('next_schedule_dt').facus();
//         return false;
//     }
    
//     /*if(assign_status_id=='3' && contact_commands==''){
//         alert('Enter the commands');
//         document.getElementById('contact_commands').facus();
//         return false;
//     }*/
    
//     var follower_user_val = $('#assign_status_frm').serialize();
    
//     $('#command_save').attr('disabled', 'disabled');
    
//     $.ajax({
//             type: 'POST',
//             url: 'ajax_data.php',
//             data: follower_user_val,
//             success: function(data){
//                     var data_val = data.trim();
//                     $('#assign-number-popup').modal('hide'); 
//                     $('#row_id_'+data_val).fadeOut('slow'); 
//                     $('#command_save').removeAttr('disabled');  
//             }
//     });
    
// }
//             $('#assign_status_id').change(function(){
//                 var stat_val =  $(this).val();
//                 if(stat_val==3){
//                     $('#schedule_date_row').fadeIn('slow');
//                 }else{
//                     $('#schedule_date_row').fadeOut('slow');
//                 }   
//             });








// $(".add_queue").click(function () {
//   iziToast.info({
//     title: 'Hello, world!',
//     message: 'This awesome plugin is made iziToast toastr',
//     position: 'topRight'
//   });
// });

function changeIncomingCall(data) {
    var incoming_call;
    if ($(data).is(':checked')) {

        var incoming_call = "1";
    } else {

        incoming_call = "2";
    }

    $.ajax({
        type: 'POST',
        url: 'get_data.php?data_page=dialpad',
        data: {

            action: 'dialpad_main',
            view_type: 'incoming_call_request',
            incoming_call: incoming_call,
            detail_id: null,

        },
        success: function (data) {

            if (data == 1) {

                $('#incoming_call_request_data').val(incoming_call);

            }


        }
    });
}





//function chatautoScroll() {
//
//    $(".card-body.chat-content").scrollTop($(".card-body.chat-content")[0].scrollHeight);
//}
//
//
//
//function onMessageSend(event_data) {
//
//    if ((event_data.keyCode || event_data.which) == 13) {
//
//        sendChatMessageData();
//        event_data.preventDefault();
//        return false;
//
//    }
//
//}
//
//
//function sendChatMessageData() {
//
//    var chat_message = $("#chat_msg").val();
//    chat_message = chat_message.trim();
//// console.log(chat_message);
//    var chat_id = $("#chat_id").val();
//    var user_id = $("#chat_user_id").val();
//    if (chat_message.length > 0) {
//       
//        $.ajax({
//            type: "POST",
//            url: 'get_data.php?data_page=mc-dashboard',
//            data: {
//                action: "send_chat_message",
//                chat_id: chat_id,
//                chat_message: chat_message
//
//            },
//            success: function (data) {
//
////              console.log(data);
//                chatListRefresh();
//               var chat_msg_id =  data.trim();
//                
//               if(chat_msg_id != "" && chat_msg_id != 0){
//                   
//           
//var chat_message_data = '{"message_type":"chat","message_info":{"chat_id":"'+chat_id+'","msg_user_id":"'+user_id+'","msg_user_type":"2","msg_type":"text","message":"'+chat_message+'"}}';
//                   
//                   websocket.send(chat_message_data);
//                    
//                    var chat_message_data ='<div class="chat-item chat-right" style=""><img src="assets/img/users/user-1.png"><div class="chat-details"><div class="chat-text">'+chat_message+'</div></div></div>';
//
//
//                    $(".card-body.chat-content").append(chat_message_data);
//                  
//                    chatautoScroll();
//                   
//               }
//
//            }
//
//        });
//    }
//    $("#chat_msg").val("");
//
//}
//
//
//
//function chatPanelView(id, view_type) {
//
//
//        $.ajax({
//        type: 'POST',
//        url: 'get_data.php?data_page=mc-dashboard',
//        data: {
//
//            action: view_type,
//            id: id
//
//        },
//        success: function (data) {
//
//            console.log(data);
// 
//            if(view_type == "chat_queue_list"){
//
//                if(data != ""){
//                    $('#message_panel_block').html(data);
//                   
//                }
//
//
//            }
//            else if(view_type == "chat_detail_view"){
//
//                if(data != ""){
//                    $('#chat_details_view').html(data);
//                     chatautoScroll();
//                   
//                }
//
//
//            }
//            
//
//
//        }
//    });
//
//}
//
//
//function chatListRefresh(){
//    
//    
//        $.ajax({
//        type: 'POST',
//        url: 'get_data.php?data_page=mc-dashboard',
//        data: {
//
//            action: 'chat_list_search'
//
//        },
//        success: function (data) {
////            console.log(data);
// if(data != ""){
//            $('.chat_list_data').html(data);
//     
// }
//            
//        }
//    });
//    
//    
//}
//
//function mailPanelView() {
//
//
//
//}






        var incomingcallAudio = new Audio('assets/images/incomingcall.mp3');
        var outgoingcallAudio = new Audio('assets/images/ringbacktone.mp3');
    
        function playAudio(call_audio) {
       
            call_audio.addEventListener('ended', function () {
                this.currentTime = 0;
                this.play();
            }, false);
            call_audio.play();

        }

        function pauseAudio(call_audio) {
            call_audio.pause();
        }

        function sipTokenupdate(sip_token, local_time) {

            $.ajax({
                type: 'POST',
                url: 'updateSipToken.php',
                data: {
                    action: 'update_sip_token',
                    sip_token: sip_token,
                    local_time: local_time
                },
                success: function (data) {

                }
            });

        }

        var SESSION_STATUS = '';
        var CALL_STATUS = '';
        var localDisplay;
        var remoteDisplay;
        var currentCall;
        var pbxSettingsStatus = 'NULL';

        function init_page(sip_login, sip_authentication, sip_password, sip_port, sip_url, sip_token) {
            console.log(sip_url);
            console.log(sip_login);
            console.log(sip_authentication);
            console.log(sip_password);

    
        }


        function connect(sip_login, sip_authentication, sip_password, sip_port, sip_url, sip_token) {

            MRVOIP.playFirstVideo(localDisplay, true);
            MRVOIP.playFirstVideo(remoteDisplay, true);
            var url = 'wss://webrtc.mrvoip.com:8443';
            //var url = 'wss://stun2.mrvoip.com/mfstwebsock';
            var sipLogin = sip_login;
            var sipauthenticationName = sip_authentication;
            var sippassword = sip_password;
            var sipOptions = {
                login: sipLogin,
                authenticationName: sipauthenticationName,
                password: sippassword,
                domain: sip_url,
                outboundProxy: sip_url,
                port: sip_port
            };
            if (sip_token) {
                connectionOptions = {
                    urlServer: url,
                    authToken: sip_token,
                    keepAlive: true
                };
            } else {
                connectionOptions = {
                    urlServer: url,
                    sipOptions: sipOptions,
                    keepAlive: true
                };
            }


            console.log(connectionOptions);
console.log('connected');

            //create session
                
            
            console.log("Create new session with url " + url);
            MRVOIP.createSession(connectionOptions).on(SESSION_STATUS.ESTABLISHED, function (session, connection) {
                setStatus(SESSION_STATUS.ESTABLISHED);
                pbxSettingsStatus = 'ESTABLISHED';              
             //    $("dialstatus").click();
                
                //document.getElementById("dialstatus").style.background = "#FFA500";

                onConnected(session); 
                local_time = Date.now();
                $('#sip_local_time').val(local_time);
                localStorage.setItem('local_time', local_time);
                // sipTokenupdate(connection.authToken, local_time);

            }).on(SESSION_STATUS.REGISTERED, function (session) {
                setStatus(SESSION_STATUS.REGISTERED);
                 
             
                pbxSettingsStatus = 'REGISTERED';
                onConnected(session);
                $('#outgoingCall').show();
            }).on(SESSION_STATUS.DISCONNECTED, function () {
                setStatus(SESSION_STATUS.DISCONNECTED);
            //  $(".status-indication-bar").removeClass("on-hook-status");          
                //$(".status-indication-bar").addClass("est-hook-status");
                pbxSettingsStatus = 'DISCONNECTED';
                onDisconnected();
            }).on(SESSION_STATUS.FAILED, function () {
                setStatus(SESSION_STATUS.FAILED);
                pbxSettingsStatus = 'SESSION_STATUS';
                onDisconnected();
            }).on(SESSION_STATUS.INCOMING_CALL, function (call) {
                
                // var active_cmpid = $('#active_cmpid').val();
    //             console.log(active_cmpid);
    //             if(active_cmpid == '400' || active_cmpid == '404'){
    //                console.log('incoming_blocked');
    //                     return false;
                   
    //              }
// console.log(JSON.parse(call)+"   1111");

                var incoming_call_request_data = $('#incoming_call_request_data').val();
                //console.log(incoming_call_request_data);
                if(incoming_call_request_data != '1'){
                   console.log('incoming_blocked');
                        return false;
                   
                 }
                
                call.on(CALL_STATUS.RING, function () {
                    setStatus(CALL_STATUS.RING);
                    playAudio(incomingcallAudio);
                }).on(CALL_STATUS.ESTABLISHED, function () {
                    setStatus(CALL_STATUS.ESTABLISHED);
                    callDuration();
                    pauseAudio(incomingcallAudio);
                }).on(CALL_STATUS.HOLD, function () {}).on(CALL_STATUS.FINISH, function () {
                    setStatus(CALL_STATUS.FINISH);
                    pauseAudio(incomingcallAudio);
                    clearTimeout(callduration_timer);
                    totalSeconds =0;
                    onHangupIncoming();
                    currentCall = null;
                }).on(CALL_STATUS.FAILED, function () {
                    setStatus(CALL_STATUS.FAILED);
                    pauseAudio(incomingcallAudio);
                    onHangupIncoming();
                    clearTimeout(callduration_timer);
                    totalSeconds =0;
                    currentCall = null;
                });


        
                onIncomingCall(call);
            });
        }

 function changestat() {
   document.getElementById("dialstatus").style.background = "#FFA500";
 }
        // function call(queue_call) {
        //     var session = MRVOIP.getSessions()[0];
            
        //     if(queue_call == 'queue_call'){
                
        //         var constraints = {
        //         audio: true,
        //         video: false
        //     };
                
        //     }
            
        //     else{
        //         var constraints = {
        //         audio: true,
        //         video: false
        //     };
                
        //     }

        //     var make_call_number_data = $("#make_call_number").val();
        //  console.log(make_call_number_data);

        //     //prepare outgoing call 
        //     var outCall = session.createCall({
        //         callee: make_call_number_data,
        //         visibleName: make_call_number_data,
        //         localVideoDisplay: localDisplay,
        //         remoteVideoDisplay: remoteDisplay,
        //         constraints: constraints,
        //         receiveAudio: true,
        //         receiveVideo: false,
        //         stripCodecs: "SILK"
        //     }).on(CALL_STATUS.RING, function () {
        //         setStatus(CALL_STATUS.RING);
        //         playAudio(outgoingcallAudio);
        //     }).on(CALL_STATUS.ESTABLISHED, function () {
        //         setStatus(CALL_STATUS.ESTABLISHED);
        //         pauseAudio(outgoingcallAudio);
        //         $('.outgoing-call').hide();
        //         $('#call_duration').show();
        //         $('.call-extra-featurs').show();
        //         callDuration();
        //         }).on(CALL_STATUS.HOLD, function () {
        //         }).on(CALL_STATUS.FINISH, function () {
        //             setStatus(CALL_STATUS.FINISH);
        //             pauseAudio(outgoingcallAudio);
        //             onHangupOutgoing();
        //             clearTimeout(callduration_timer);
        //             totalSeconds = 0;
        //             console.log(make_call_number_data);
        //             if(make_call_number_data == '*62' || make_call_number_data == '*63'){
        //                // dialPadview('recent_list');
        //             }
        //             else{
        //                 console.log(call_history_id);
        //                 $('#outgoing_call_end_trigger').click();
        //                 call_history_id = $('#call_history_id').val();
        //                 dialPadDetailView('call_history_detail', call_history_id);
        //             }
        //             currentCall = null;
        //         }).on(CALL_STATUS.FAILED, function () {
        //             setStatus(CALL_STATUS.FAILED);
        //             pauseAudio(outgoingcallAudio);
        //             onHangupIncoming();
        //             totalSeconds = 0;
        //             clearTimeout(callduration_timer);  
        //         if(make_call_number_data == '*62' || make_call_number_data == '*63'){
        //                 //dialPadview('recent_list');
        //             }
        //         else{
        //              console.log(call_history_id);
        //              //dialPadDetailView('call_history_detail', call_history_id);
        //              $('#outgoing_call_end_trigger').click();
        //         }
        //             currentCall = null;
        //         });

        //         outCall.call();
        //         currentCall = outCall;

        //         $("#makecallHanupBtn").text("Hangup").off('click').click(function () {
        //             outCall.hangup();
        //             console.log('Hangup');
        //         });
        // }



        function onConnected(session) {
            if (currentCall) {
                showOutgoing();
                // disableOutgoing(true);
                setStatus("");
                currentCall.hangup();
            }
        }

        function onDisconnected() {
            // disableOutgoing(true);
            showOutgoing();
            setStatus("");
        }

        function autoconnected(sip_login, sip_authentication, sip_password, sip_port, sip_url, sip_token) {
            connect(sip_login, sip_authentication, sip_password, sip_port, sip_url, sip_token);
            // disableOutgoing(true);
            showOutgoing();
            setStatus("");
        }

        function onHangupOutgoing() {
            $("#makecallHanupBtn").text("Call").off('click').click(function () {
                console.log('Call');
                // disableOutgoing(true);
                //call();
                //alert('onHangupOutgoing');
                currentCall.hangup();
            }).prop('disabled', false);
            // disableOutgoing(false);
            
        }

        function onHangupOutgoingNew() {
            // disableOutgoing(true);
            call();

        }

//         function getServiceNowData(phone_number) {
//     $.ajax({
//         type: 'POST',
//         url: 'get_data.php?data_page=dialpad',
//         data: {

//             action: 'getServiceNowData',
//             phone_number: phone_number,
//             view_type: null,
//             detail_id: null,

//         },
//         success: function (data) {

//             var result_data = data.split('^^^^');

//             if(result_data[0] == 1){
//             var service_url = "https://www.google.com/?"+result_data[1];
//             var win =window.open(result_data[2], '_blank');
//             win.focus();

//                 // "https://dev.cal4care.com/erp/content.php?"+result_data[1];

//                  // console.log("snow service "+data);

//             }

           
//             //$('#dialpad_layout').html(data);

//         }
//     });
// }

        function onIncomingCall(inCall) {

                


            currentCall = inCall;
            



            console.log("snow service22 "+inCall.caller());

            //alert(inCall.caller())
            
            // getServiceNowData(inCall.caller());
            showIncoming(inCall.caller());
            $("#incomingCallAnswerBtn").off('click').click(function () {

                var constraints = {
                    audio: true,
                    video: false
                };
                inCall.answer({
                    localVideoDisplay: localDisplay,
                    remoteVideoDisplay: remoteDisplay,
                    receiveVideo: false,
                    constraints: constraints,
                    stripCodecs: "SILK"
                });
                showAnswered();
            });
            $("#incomingCallHangupBtn").off('click').click(function () {
                inCall.hangup();
                onHangupOutgoing();
            });
        }

        function onHangupIncoming() {
             showOutgoing();
             incomingCallEnd();
            console.log("123-callend");
        }

        // Set connection and call status
        function setStatus(status) {
            console.log(status);
            if(status == 'DISCONNECTED'){
                outCall.hangup();
            }

        }
        function getinStatus() {
            return pbxSettingsStatus;

        }
        // Display view for incoming call
        function showIncoming(callerName) {
             var data = '{"operation_type":"openMainChat","status_style":"position: fixed;right: -15px;bottom: 0px;border:none;height:100%;width:55%;z-index:99"}';
   console.log(data);
            window.parent.postMessage(data, '*');
            
             
            
            // $('.circle-plus.view-dialpad').hide();
            $(".dialpad-container").show();
            dialPadDetailView('call_incoming', callerName);
            $('#call_incoming_number').val(callerName);
            $('#incoming_call_trigger').click();

            // $("#outgoingCall").hide();
            // $("#incomingCall").show();
            // $("#incomingCallAlert").show().text("You have a new call from " + callerName);
            // $("#incomingCallAnswerBtn").show();
        }
        function  incoming_call_trigger() {
            var call_incoming_number = $('#call_incoming_number').val();
            // if (call_incoming_number != "") {
            //     dialPadOpen();
            //     dialPadDetailView('call_incoming', call_incoming_number);
            // }
        }
        // Display view for outgoing call
        function showOutgoing() {
            // $("#incomingCall").hide();
            // $("#incomingCallAlert").hide();
            $("#outgoingCall").show();
            onHangupOutgoing();
        }

        function disableOutgoing(disable) {
            // $('#callee').prop('disabled', disable);
            $("#makecallHanupBtn").prop('disabled', disable);
        }

        // Display view for answered call
        function showAnswered() {
            $("#incomingCallAnswerBtn").hide();
            $("#incomingCallAlert").hide().text("");
        }


        function mute() {
            if (currentCall) {
                currentCall.muteAudio();
            }
        }

        // Unmute audio in the call
        function unmute() {
            if (currentCall) {
                currentCall.unmuteAudio();
            }
        }




        // function holdCall() {
        //     if (currentCall) {
        //         currentCall.hold();
        //     }
        //     $('#hold_btn').css('display','none');
        //     $('#resume_btn').css('display','block');
        // }

        // // Unmute audio in the call
        // function resumeCall() {
        //     if (currentCall) {
        //         currentCall.unhold();
        //     }
        //     $('#hold_btn').css('display','block');
        //     $('#resume_btn').css('display','none');
        // }



        function transferCall(target) {
            if (currentCall) {
                currentCall.transfer(target);
              //  $('#incoming_call_end_trigger').click();
            }
        }


        function incomingCallEnd() {
            $("#incomingCallHangupBtn").click();
            this.dialPadDetailView('call_history_detail', this.call_history_id);
            $('#opencallDialer').modal('hide');
            $('#makeCallForwordNumber').val('');
            $('#dialpad_number').val('');
            $('#makeCallForwordNumber').val('');
            $('#dialpad_number').val('');
            $('#animate-dialpad-show').attr('style', 'display: none');
        }

        function endCallAfterTransfer(){
            currentCall.hangup();
            $('#incoming_call_end_trigger').click();
			$('#animate-dialpad-show').attr('style', 'display: none');
        }



 
            
    function searchDialerContacts() {
      // Retrieve the input field text and reset the count to zero
      var filter = $('#dialpad_number_contscts').val(),
        count = 0;
        console.log(filter);
        
            
      // Loop through the comment list
      $('#userList div').each(function() {

        

        // If the list item does not contain the text phrase fade it out
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
          $(this).hide();  // MY CHANGE

          // Show the list item if the phrase matches and increase the count by 1
        } else {
          $(this).show(); // MY CHANGE
          count++;
        }

      });

    };  

function searchTransContacts() {
      // Retrieve the input field text and reset the count to zero
      var filter = $('#dialpad_number_contscts').val(),
        count = 0;
            
      // Loop through the comment list
      $('#transList div').each(function() {


        // If the list item does not contain the text phrase fade it out
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
          $(this).hide();  // MY CHANGE

          // Show the list item if the phrase matches and increase the count by 1
        } else {
          $(this).show(); // MY CHANGE
          count++;
        }

      });

    };  


    function searchDialerrecents() {
      // Retrieve the input field text and reset the count to zero
      var filter = $('#dialpad_number_search').val(),
        count = 0;
            
      // Loop through the comment list
      $('#recentCalls div').each(function() {


        // If the list item does not contain the text phrase fade it out
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
          $(this).hide();  // MY CHANGE

          // Show the list item if the phrase matches and increase the count by 1
        } else {
          $(this).show(); // MY CHANGE
          count++;
        }

      });

    };  
