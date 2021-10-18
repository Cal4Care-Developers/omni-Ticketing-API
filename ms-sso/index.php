<!DOCTYPE html>
<html>
<head>
    <title>Dialpad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-4.5.0.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: 'Zilla Slab', serif;
            background: #fff;
        }

        .fl {
            float: left;
            width: 100%;
        }

        .margin-0 {
            margin: 0
        }

        .login-panel {
            float: left;
            width: 100%;
            text-align: center;
        }

        img {
            max-width: 100%;
        }

        .login-text p {
            color: #666666;
            margin: 0 auto;
            font-size: 17px;
            text-align: center;
        }

        .modal .login-text p {
            height: 46px;
            vertical-align: -webkit-baseline-middle;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .action-btn .btn {
            line-height: 34px;
            transition: all 0.7s;
            background-color: #8f4c9b;
            width: 300px;
            height: 48px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            border-radius: 30px;
            max-width: 460px;
        }

        .action-btn .btn:hover {
            transition: all 0.7s;
            color: #8f4c9b;
            background-color: transparent;
        }

        .flex-align-center {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }

        .modal-logo img {
            height: 34px;
            max-width: 240px;
        }

        #ssoLoginModal .modal-body {
            height: 400px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn:focus {
            box-shadow: none;
        }

        .modal-btn {
            background-color: #0597d4;
            color: #fff;
        }

        .modal-btn:hover {
            background-color: #3d77a7;
            color: #fff;
            border-color: 0597d4;
        }

        .modal-close {
            position: absolute;
            top: -15px;
            right: -13px;
            background-color: #0597d4 !important;
            z-index: 9999;
            width: 30px;
            height: 30px;
            text-shadow: none;
            color: #fff;
            opacity: 1;
            border: 0;
            border-radius: 50%;
            font-size: 24px;
            font-weight: 700;
        }

        .modal-close:hover {
            transform: rotate(180deg);
            transition: all 0.7s;
        }

        .modal-close:hover {
            transform: rotate(180deg);
            transition: all 0.7s;
            opacity: 1 !important;
            background: #3d77a7;
            color: #ffff;
        }

        .modal-dialog {
            margin: 5rem auto;
        }

        .has-border {
            border-left: 1px solid #cccccc;
        }

        .close:not(:disabled):not(.disabled):focus,
        .close:not(:disabled):not(.disabled):hover {
            outline: none;
        }
    </style>

</head>

<body>
    <div id="wrapper">
        <div class="login-panel fl">
            <div class="row margin-0">
                <div class="col-md-6">
                    <div class="login-img text-center flex-align-center">
                        <img src="assets/images/login-banner.png" height="460">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="login-action flex-align-center text-center">
                        <div class="app-logo mb-4">
                            <img src="assets/images/mconnect-logo.png">
                        </div>
                        <h2 class="mb-3 fl"><strong>Welcome to Omnichannel</strong></h2>
                        <div class="login-text fl mb-4">
                            <p>Stay connected with omnichannel's business<br /> phone system and video meetings.</p>
                        </div>


                        <div class="action-btn fl">
                            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#ssoLoginModal">
                                Get Started
                            </a>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal fade" id="ssoLoginModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body">
                    <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sso-login d-flex align-items-center justify-content-center flex-column">
                                <div class="modal-logo mb-4">
                                    <img src="assets/images/mconnect-logo.png">
                                </div>
                                <div class="login-text fl mt-2 mb-4">
                                    <p>Integrate your Omnichannel account to your Microsoft Teams account.</p>
                                </div>
                                <div class="modal-action-btn text-center fl">
                                    <a href="https://www.mrvoip.com/contact-us/" target="_blank" class="btn modal-btn">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div
                                class="sso-login d-flex align-items-center justify-content-center flex-column has-border">
                                <div class="modal-logo mb-4">
                                    <img src="assets/images/microsoft.svg">
                                </div>
                                <div class="login-text fl mt-2 mb-4">
                                    <P>Authorize your Microsoft account</P>
                                </div>
                                <div class="modal-action-btn text-center fl">
                                    <a href="#" class="btn modal-btn" data-toggle="modal" data-target="#myModal" onclick="redirect()">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/popper-umd-1.16.0.min.js"></script>
<script type="text/javascript" src="js/bootstrap-4.5.0.min.js"></script>
<script src="https://statics.teams.microsoft.com/sdk/v1.5.2/js/MicrosoftTeams.min.js" crossorigin="anonymous"></script>
<script>
    microsoftTeams.initialize();
</script>
<script type="text/javascript">
    window.onload = function(){ 
        var login = localStorage.getItem('login');
        var email = localStorage.getItem('email');
		
        if(login && email){
            document.getElementsByTagName("html")[0].style.visibility = "visible";
           //  var u = "https://" + window.location.hostname + "/mconnectDialer/?login="+login;
			var u = "https://" + window.location.hostname + "/ms-sso/call-details.php?login="+login;
			if(login !='Not an Valid User'){
				window.location = u;
			}
        }
    }	

    function redirect(){
        microsoftTeams.authentication.authenticate({
            url: "https://" + window.location.hostname + "/ms-sso/simplesamlphp/",
            width: 800,
            height: 800,
            successCallback: (data) => {
                var login = localStorage.getItem('login');
                var email = localStorage.getItem('email');
                if(login && email){
                document.getElementsByTagName("html")[0].style.visibility = "visible";
                //var u = "https://" + window.location.hostname + "/mconnectDialer/?login="+login;
					var u = "https://" + window.location.hostname + "/ms-sso/call-details.php?login="+login;
                window.location = u;
                }
            },
            failureCallback: () => {
					alert('Not an valid user, Please Contact Omni Channel Admin');
            }
        })
    }
	
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }
			
		
var login = getUrlVars()["login"];	
if(login == 'Not an Valid User'){
	microsoftTeams.authentication.notifyFailure();	
}	
	
</script>
</html>