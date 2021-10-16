document.addEventListener("DOMContentLoaded", () => {
  const inputField = document.getElementById("input");
  inputField.addEventListener("keydown", (e) => {
    if (e.code === "Enter") {
      let input = inputField.value;
      inputField.value = "";
      output(input);
    }
  });
});

function Sendreply() {

  const inputField = document.getElementById("input");
  let input = inputField.value;
  inputField.value = "";
  output(input);
}
function output(input) {
  let product;
  if (input.trim()) {
    var ss = (input.trim());

  }
  // Regex remove non word/space chars
  // Trim trailing whitespce
  // Remove digits - not sure if this is best
  // But solves problem of entering something like 'hi1'

  // let text = input.toLowerCase();
  let text = ss.toLowerCase().replace(/[^\w\s]/gi, "").replace(/[\d]/gi, "").trim();
  text = text
    .replace(/ a /g, " ")   // 'tell me a story' -> 'tell me story'
    .replace(/i feel /g, "")
    .replace(/whats/g, "what is")
    .replace(/please /g, "")
    .replace(/ please/g, "")
    .replace(/r u/g, "are you");

  if (compare(prompts, replies, keywords, text)) {
    // Search for exact match in `prompts`
    product = compare(prompts, replies, keywords, text);
  } else if (text.match(/thank/gi)) {
    product = "You're welcome!"
  } else if (text.match(/(corona|covid|virus)/gi)) {
    // If no match, check if message contains `coronavirus`
    console.log(coronavirus);
    product = coronavirus[Math.floor(Math.random() * coronavirus.length)];
  } else {
    // If all else fails: random alternative
    //  console.log(alternative);
    // product = alternative[Math.floor(Math.random() * alternative.length)];
    product = alternative;
  }
  ss = '';
  // Update DOM
  addChat(input, product);
}

function closebot() {
  $(".chat-panel").toggleClass("hide");
  $('#chat').css('display', 'none');

  $("#chatget").css("display", "none");
  unsend();
  localStorage.setItem('type','bot');
}



function compare(promptsArray, repliesArray, keywordsArray, string) {
  let reply;
  let replyFound = false;


  for (let x = 0; x < promptsArray.length; x++) {
    for (let y = 0; y < promptsArray[x].length; y++) {
      if (promptsArray[x][y] === string) {
        let replies = repliesArray[x];
        reply = replies[Math.floor(Math.random() * replies.length)];
        replyFound = true;
        // Stop inner loop when input value matches prompts
        break;
      }
    }
    if (replyFound) {
      // Stop outer loop when reply is found instead of interating through the entire array
      break;
    }
  }

  if (!reply) {
    count =0;
    QuesArray=[];
    AnsArray=[];
   var reply2;
    for (let x = 0; x < promptsArray.length; x++) {
      for (let y = 0; y < promptsArray[x].length; y++) {
        if (levenshtein(promptsArray[x][y], string) >= 0.50) {
          let replies = repliesArray[x];
         reply2 = replies[Math.floor(Math.random() * replies.length)];
          //replyFound = true;
          // Stop inner loop when input value matches this.prompts
         // break;         
         QuesArray.push(promptsArray[x][y]);
         AnsArray.push(repliesArray[x])
         count =count+1;
        }
      }     
    }
    console.log(QuesArray)
    console.log(AnsArray)
    // alert(count);
    if(count > 0){
      for (let x = 0; x < QuesArray.length; x++) {      
        for (let y = 0; y < QuesArray[x].length; y++) {       
          if (levenshtein(QuesArray[x], string) >= 0.70) {
            let replies = AnsArray[x];
           reply = replies[Math.floor(Math.random() * replies.length)];
           console.log(reply)
            replyFound = true;
            // Stop inner loop when input value matches this.prompts
           break;           
          }
          if (replyFound) {
            // Stop outer loop when reply is found instead of interating through the entire array
            break;
          }
        }     
      }
    }else{
reply=reply2;
replyFound = true;
    }
  }
  return reply;
}
function levenshtein(s1, s2) {
  var longer = s1;
  var shorter = s2;
  if (s1.length < s2.length) {
    longer = s2;
    shorter = s1;
  }
  var longerLength = longer.length;
  if (longerLength == 0) {
    return 1.0;
  }
  return (longerLength - editDistance(longer, shorter)) / parseFloat(longerLength);
}

function editDistance(s1, s2) {
  s1 = s1.toLowerCase();
  s2 = s2.toLowerCase();

  var costs = new Array();
  for (var i = 0; i <= s1.length; i++) {
    var lastValue = i;
    for (var j = 0; j <= s2.length; j++) {
      if (i == 0)
        costs[j] = j;
      else {
        if (j > 0) {
          var newValue = costs[j - 1];
          if (s1.charAt(i - 1) != s2.charAt(j - 1))
            newValue = Math.min(Math.min(newValue, lastValue),
              costs[j]) + 1;
          costs[j - 1] = lastValue;
          lastValue = newValue;
        }
      }
    }
    if (i > 0)
      costs[s2.length] = lastValue;
  }
  return costs[s2.length];
}


function sendBotChat(messageval, types) {


  var chat_id = $("#chat_id").val();
  var customer_id = $("#customer_id").val();

  $.ajax({
    type: "POST",
    url: "message.php",

    data: {
      action_data: "add_chatbot_message",
      chat_id: chat_id,
      customer_id: customer_id,
      chat_message: messageval,
      msg_user_type: types
    },
    success: function (data) {



    }

  });


}

var arr = [];
function addChat(input, product) {
 

  var split_keyword = keywords.split(",");
  var present = split_keyword.filter(function (item) {
    return item == input;
  });
  console.log(present);
  var toggle_status = localStorage.getItem('can_available');

  if (present.length != 0) {
    const messagesContainer = document.getElementById("messages");

    let userDiv = document.createElement("div");
    userDiv.id = "bot";
    userDiv.className = "bot response";

    userDiv.innerHTML = `<span class="messgaespanright">${input}</span><img src="user.png" class="avatar ">`;
    //userDiv.innerHTML = input;

    messagesContainer.appendChild(userDiv);

    let botDiv = document.createElement("div");
    let botImg = document.createElement("img");
    let botText = document.createElement("span");
    botDiv.id = "user";
    botImg.src = "bot-mini.png";
    botImg.className = "avatar";
    botDiv.className = "user response";
    botText.innerText = "Typing...";
    botText.className = "messgaespanleft";
    botDiv.appendChild(botImg);
    botDiv.appendChild(botText);
    messagesContainer.appendChild(botDiv);
    // Keep messages at most recent
    messagesContainer.scrollTop = messagesContainer.scrollHeight - messagesContainer.clientHeight;
    setTimeout(() => {
      //botText.innerText = `${product}`;
      // botText.innerHTML = `${product}`;
      if (toggle_status != '1') {
        botText.innerHTML = 'Connecting to Agent...';
      } else {
        botText.innerHTML = 'Currently our Agents are unavialable will contact you shortly';
      }
      //textToSpeech(product)
    }, 2000
    )
    localStorage.type = "webchat";
    console.log("correct")
    //startChat(2,input,"bot");
    if (toggle_status != '1') {
      newstartchat(input);
    }
  } else {

    if (product != '') {
      sendBotChat(product, '4');
    }
    if (input != '') {
      if ((input.toLowerCase() != "yes")) {
        sendBotChat(input, '1');
      }
    }
    //}
    var check = 0;
    if (product == alternative) {
      arr.push(product);
      //check =1;

    } else {
      //arr=[];
      //check =0;
    }

    console.log(arr.length)
    console.log(input.toLowerCase())
    if (arr.length >= 2 && (input.toLowerCase() == "yes")) {
      //alert("asda")
      const messagesContainer = document.getElementById("messages");

      let userDiv = document.createElement("div");
      userDiv.id = "bot";
      userDiv.className = "bot response";

      userDiv.innerHTML = `<span class="messgaespanright">${input}</span><img src="user.png" class="avatar ">`;
      //userDiv.innerHTML = input;

      messagesContainer.appendChild(userDiv);

      let botDiv = document.createElement("div");
      let botImg = document.createElement("img");
      let botText = document.createElement("span");
      botDiv.id = "user";
      botImg.src = "bot-mini.png";
      botImg.className = "avatar";
      botDiv.className = "user response";
      botText.innerText = "Typing...";
      botText.className = "messgaespanleft";
      botDiv.appendChild(botImg);
      botDiv.appendChild(botText);
      messagesContainer.appendChild(botDiv);
      // Keep messages at most recent
      messagesContainer.scrollTop = messagesContainer.scrollHeight - messagesContainer.clientHeight;
      setTimeout(() => {
        //botText.innerText = `${product}`;

        if (toggle_status != '1') {
          botText.innerHTML = `${product}`;
        } else {
          botText.innerHTML = 'Currently our Agents are unavialable will contact you shortly';
        }
        //textToSpeech(product)
      }, 2000
      )
      localStorage.type = "webchat";
      console.log("correct")
      //startChat(2,input,"bot");
      if (toggle_status != '1') {
        newstartchat(input);
      }
    } else {
      if (input != '') {


        const messagesContainer = document.getElementById("messages");

        let userDiv = document.createElement("div");
        userDiv.id = "bot";
        userDiv.className = "bot response";

        userDiv.innerHTML = `<span class="messgaespanright">${input}</span><img src="user.png" class="avatar ">`;
        // userDiv.innerHTML = input;
        messagesContainer.appendChild(userDiv);

        let botDiv = document.createElement("div");
        let botImg = document.createElement("img");
        let botText = document.createElement("span");
        botDiv.id = "user";
        botImg.src = "bot-mini.png";
        botImg.className = "avatar";
        botDiv.className = "user response";
        botText.innerText = "Typing...";
        botText.className = "messgaespanleft";
        botDiv.appendChild(botImg);
        botDiv.appendChild(botText);
        messagesContainer.appendChild(botDiv);
        // Keep messages at most recent
        messagesContainer.scrollTop = messagesContainer.scrollHeight - messagesContainer.clientHeight;

        // Fake delay to seem "real"
        setTimeout(() => {
          //   botText.innerText = `${product}`;
          botText.innerHTML = `${product}`;
          //textToSpeech(product)
        const messagesContainer = document.getElementById("messages");

        messagesContainer.scrollTop = messagesContainer.scrollHeight - messagesContainer.clientHeight;

        }, 2000
        )

      } else {


        const messagesContainer = document.getElementById("messages");

        let botDiv = document.createElement("div");
        let botImg = document.createElement("img");
        let botText = document.createElement("span");
        botDiv.id = "user";
        botImg.src = "bot-mini.png";
        botImg.className = "avatar";
        botDiv.className = "user response";
        botText.innerText = "Typing...";
        botText.className = "messgaespanleft";
        botDiv.appendChild(botImg);
        botDiv.appendChild(botText);
        messagesContainer.appendChild(botDiv);
        // Keep messages at most recent
        messagesContainer.scrollTop = messagesContainer.scrollHeight - messagesContainer.clientHeight;

        // Fake delay to seem "real"
        setTimeout(() => {
          botText.innerHTML = `${product}`;
          // textToSpeech(product)
        }, 2000
        )
      }
    }

  }
}
