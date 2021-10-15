// Options the user could type in
var $;
var iziToast;
var prompts = [];
var replies = [];
var alternative = [];
var keywords = [];
var urldata;
var access_token;
window.onload = function () {

  // addChat("", "&#128075; Hi I'm a Bot. Let me know if you have any questions regarding our tool!");
  //   if(localStorage.getItem('email')){
  // $("#chatget").css("display", "block");
  // $("#formget").css("display", "none");
  //   }else{
  // $("#chatget").css("display", "none");
  // $("#formget").css("display", "block");
  //   }
  console.log(window.location.href);

  urldata = getUrlVars('url');
  ajaxcall();
};
function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
    vars[key] = value;
  });
  return vars;
}

function ajaxcall() {

  //localStorage.en = urldata.url;
	urldata = getUrlVars('url'); 
	console.log(urldata);

var	admin=atob(urldata.aid);
var	widget=atob(urldata.wid);

  var senddata = { "operation": "chat", "moduleType": "chat", "api_type": "web", "access_token": access_token, "element_data": { "action": "get_chatBotQA", "url": admin,"widget_name": widget  } }



  jQuery.ajax({
    url: 'https://omnitickets.mconnectapps.com/api/v1.0/index.php',
    type: 'POST',
    data: JSON.stringify(senddata),
    // processData: false,  // tell jQuery not to process the data
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      console.log(data);
      if (data.status == true) {
        prompts = data.result.data.questions;
        replies = data.result.data.answer;
        keywords = data.result.data.keyWord;
        alternative = data.result.data.alternative[0];

        console.log(prompts);
      } else {

      }
      // this.parsed_data = JSON.parse(data);
      // console.log(data);
      // if(this.parsed_data.status == 'true'){    



      // } else {

      // }
    }
  });

}

function ValidateEmail() {

  var inputText = $('#emailid').val();
  var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
  if (inputText.match(mailformat)) {
    dataupdate();
    return true;
  }
  else {
    myFunction();
    return false;
  }
}


function myFunction() {
  var x = document.getElementById("snackbar");
  x.className = "show";
  setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
}

function dataupdate() {
  var urlval = localStorage.getItem('en');

  var emailid = $('#emailid').val();
  var country_code = $('#country_code').val();
  var phone_num = $('#phone_num').val();


  var senddata = { "operation": "chat", "moduleType": "chat", "api_type": "web", "access_token": access_token, "element_data": { "action": "chatbot_det", "email": emailid, "ph_no": phone_num, "country_code": country_code, "url": urlval } }




  jQuery.ajax({
    url: 'https://omni.mconnectapps.com/api/v1.0/index.php',
    type: 'POST',
    data: JSON.stringify(senddata),
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      console.log(data);
      if (data.result.data == 1) {

        localStorage.email = window.btoa(emailid);
        $("#chatget").css("display", "block");
        $("#formget").css("display", "none");


      } else {
        localStorage.email = window.btoa(emailid);
        $("#chatget").css("display", "block");
        $("#formget").css("display", "none");
      }

    }
  });

}


// const prompts = [
//                 [
//                     "What does an order cost?"
//                 ],
//                 [
//                     "Which URL do I use to login as a client?"
//                 ],
//                 [
//                     "what is omniChannels?"
//                 ],
//                 [
//                     "i want to know price of wallboard"
//                 ],
//                 [
//                     "What is the Special Package Price"
//                 ],
//                 [
//                     "what is the price for omni channels"
//                 ],
//                 [
//                     "hi"
//                 ]
//             ]
// const prompts = [
//   ["hi", "hey", "hello", "good morning", "good afternoon"],
//   ["how are you", "how is life", "how are things"],
//   ["what are you doing", "what is going on", "what is up"],
//   ["how old are you"],
//   ["who are you", "are you human", "are you bot", "are you human or bot"],
//   ["who created you", "who made you"],
//   [
//     "your name please",
//     "your name",
//     "may i know your name",
//     "what is your name",
//     "what call yourself"
//   ],
//   ["i love you"],
//   ["happy", "good", "fun", "wonderful", "fantastic", "cool"],
//   ["bad", "bored", "tired"],
//   ["help me", "tell me story", "tell me joke"],
//   ["ah", "yes", "ok", "okay", "nice"],
//   ["bye", "good bye", "goodbye", "see you later"],
//   ["what should i eat today"],
//   ["bro"],
//   ["what", "why", "how", "where", "when"],
//   ["no","not sure","maybe","no thanks"],
//   [""],
//   ["haha","ha","lol","hehe","funny","joke"]
// ]

// Possible responses, in corresponding order

// const replies = [
//   ["Hello!", "Hi!", "Hey!", "Hi there!","Howdy"],
//   [
//     "Fine... how are you?",
//     "Pretty well, how are you?",
//     "Fantastic, how are you?"
//   ],
//   [
//     "Nothing much",
//     "About to go to sleep",
//     "Can you guess?",
//     "I don't know actually"
//   ],
//   ["I am infinite"],
//   ["I am just a bot", "I am a bot. What are you?"],
//   ["The one true God, JavaScript"],
//   ["I am nameless", "I don't have a name"],
//   ["I love you too", "Me too"],
//   ["Have you ever felt bad?", "Glad to hear it"],
//   ["Why?", "Why? You shouldn't!", "Try watching TV"],
//   ["What about?", "Once upon a time..."],
//   ["Tell me a story", "Tell me a joke", "Tell me about yourself"],
//   ["Bye", "Goodbye", "See you later"],
//   ["Sushi", "Pizza"],
//   ["Bro!"],
//   ["Great question"],
//   ["That's ok","I understand","What do you want to talk about?"],
//   ["Please say something :("],
//   ["Haha!","Good one!"]
// ]

// Random for any other user input

// const alternative = [
//   "Sorry, I did not understand your question. If you wish to chat with our agent please respond - Yes"
// ]

// Whatever else you want :)

const coronavirus = ["Please stay home", "Wear a mask", "Fortunately, I don't have COVID", "These are uncertain times"]


