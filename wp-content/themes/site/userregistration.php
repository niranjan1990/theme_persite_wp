<?php /* Template Name: User Registration Page */
ob_start();
session_start();
require_once(__DIR__.'/../../../wp-reviewconfig.php');
include(__DIR__.'/../../../wp-config-extra.php');
if(!isset($_SESSION['rurl']))
{
	if(isset($_COOKIE['bb_pre_url']))
	{
		$_SESSION['rurl'] = cookie_redirect($_COOKIE['bb_pre_url']);
	}
}

?>
<?php
if(isset($_COOKIE['bb_userid'])){
	header('location: /');
}
?>



<?Php get_header('noad'); ?>
<?php

$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_COOKIE['bb_userid'])){
	/* echo "already logged In"; */
	echo "<div class='loginpanel' style='    margin-top: 20px;' ><strong style='margin-top:'>Welcome <a href='/user-profile.html'>".$_COOKIE['bb_username']."</a>, <a style='text-decoration: underline;' href='/logout.html'>Logout</a></strong></div>";
}else{

?>

<style>
::-webkit-input-placeholder { /* WebKit, Blink, Edge */
    color:    #b3b3b3;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
   color:    #b3b3b3;
   opacity:  1;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
   color:    #b3b3b3;
   opacity:  1;
}
:-ms-input-placeholder { /* Internet Explorer 10-11 */
   color:    #b3b3b3;
}
.input_text:disabled {
background-color:rgb(243,242,231);
}
input::-webkit-input-placeholder {
     line-height:normal!important;
}
.change::-webkit-input-placeholder {
    /* WebKit, Blink, Edge */
    color: black;
}
.change:-moz-placeholder {
    /* Mozilla Firefox 4 to 18 */
    color: black;
    opacity: 1;
}
.change::-moz-placeholder {
    /* Mozilla Firefox 19+ */
    color: black;
    opacity: 1;
}
.change:-ms-input-placeholder {
    /* Internet Explorer 10-11 */
    color: black;
}
.labelheading{
color: #000000;
display:block;
text-align:center;
font-color:rgba(65,117,5,1);
font-size:24px;
font-weight:bold;

}
.labeltext{
 color: #000000;
display:block;
text-align:center;
font-color:rgba(65,117,5,1);
font-size:14px


}

.register{
    margin-top: 10px;
font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
	background-color: #167c00;
	border-radius: 8px;
	color:#fff;
}
.registerblock{
    margin-top: 10px;
font-family: Lucida Sans, sans-serif !important;
	font-size: 13px;
	width:209px;
height:44px;
	border-radius: 8px;
	color:#fff;
	cursor:not-allowed;
	 background-image: linear-gradient(-180deg, #9B9A9B 14%, #9B9A9B 100%);
}

.form-group{
	padding:5px ! important;
	margin-bottom: 0rem ! important;
}

.block {
  height: 700px;
}
.block2 {
  min-height: 160px;
}
.center {
  position: absolute;
/*  top: 0;
  bottom: 0; */
  left: 0;
  right: 0;
  margin: auto;
}
.input_text{



	margin-bottom:10px;
	font-family: Lucida Sans, sans-serif !important;
	padding-left:10px;
	 width: 309px;
  height: 40px;
  border-radius: 8px;
  background-color: #fff;
  border: solid 1px #979797;
font-size: 13px;
    color: #000000;

}

#input_container {
    position:relative;
    padding:0;
    margin:0;
}
.label{
	margin-top:10px;
}

#emailiderrormessage,#passmessage,#message,#cnfpassmessage,#usernamemessage{
display: block;
    width: 100%;
    text-align: left;
  float: left;
  padding-left: 90px;

}
#login{
font-family: Lucida Sans, sans-serif !important;
font-size:16px;
  background-color: #0090EA;
font-style: normal;
  font-stretch: normal;
color:#fff;
width:209px;
height:44px;
border: 1px solid #0090EA;
border-radius: 6px;

}


.g-recaptcha{
height:78px;
margin:10px;
}



@media screen  and (max-width: 601px) {

.input_text{
	margin-bottom:0px;
}

.g-recaptcha{
height:78px;
margin:0px;
}

#labelaccount{
    margin-top: 10px;
}

input::-webkit-input-placeholder {
     line-height:normal!important;
}
#emailiderrormessage,#passmessage,#message,#cnfpassmessage,#usernamemessage{
  float: left;
  padding-left: 10px;

}
.labeltext {
 color: #000000;
	font-size:14px !important;
}
.label{
	margin-top:0px;
}
.form-group {
   padding:5px ! important;
   margin-bottom: 0rem ! important;
}
.block {
  height: 700px ! important;
}
#email,#password,#confirm_password,#username{
	width:100% ! important;
}
}
</style>
<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	 <div class="inner">
	 <div class="main-content">


   <div class='col-md-3'>
   </div>

    <div class="container col-sm-8 col-md-6 block">

        <div class="block2  center"></br>
           <label class="labelheading" ><label><?php echo $REGISTRATIONTITLE; ?></label></label></br>
           <label class="labeltext">To write reviews, post to forums, list in classified, upload to galleries and a lot more!</label>
		<form method="post" autocomplete="off"   class="form-horizontal" role="form" align="center">
			<?php
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
			{
			}
			else
			{
				?>
					<div class="form-group" >
								<div id="input_container" class="col-sm-8 col-md-12">
						<center><div class="g-recaptcha"  data-callback="imNotARobot" data-sitekey="<?php echo SITEKEY;?>" ></div></center>
								</div>
					</div>
				<?php
			}
			?>

			<div class="form-head"  >
				<div class="col-sm-8 col-md-12">
				<span id='successmessage'></span>
				</div>
			</div>

            <div class="form-group" >
               <div id="input_container" class="col-sm-8 col-md-12">
					<span id='emailiderrormessage'  ></span></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Email Address</span><br>";
							}
							?>

                    <input autocapitalize="none" type="text" placeholder="Email" name="email" id="email" required="true" pattern="\s*[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}\s*$" class="input_text" <?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){}else{ echo "disabled";} ?>/>
                </div>
            </div>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								?>
								<div class="form-group" style="display:none">
									<div id="input_container" class="col-sm-8 col-md-12">
									<span>Confirm Email (HoneyPot*)</span><br>
										<input autocapitalize="none" type="text" name="emailcap"  id="emailcap" placeholder="Confirm Email" class="input_text"/>
									</div>
								</div>
								<?php
							}
							else
							{
							}
							?>

			<div class="form-group">
                <div id="input_container" class="col-sm-8 col-md-12">
					<span id='passmessage'  ></span></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Password</span><br>";
							}
							?>
                    <input autocapitalize="none"  type="password" placeholder="Password : 8 chars + num" name="password"  id="password" required="true" class="input_text" <?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){}else{ echo "disabled";} ?>/>
                </div>
            </div>
			<div class="form-group">
                <div id="input_container" class="col-sm-8 col-md-12">
					<span id='message'  ></span><span id='cnfpassmessage'  ></span></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Confirm Password</span><br>";
							}
							?>
                    <input autocapitalize="none"  type="password" placeholder="Confirm Password" name="confirm_password"  id="confirm_password" required="true" class="input_text" <?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){}else{ echo "disabled";} ?>/>
                </div>
            </div>
			<div class="form-group">
				<div id="input_container" class="col-sm-8 col-md-12">
					<span id='usernamemessage' ></span></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Username</span><br>";
							}
							?>
                    <input autocapitalize="none"  type="text" name="username" id="username" placeholder="Username" required="true" class="input_text" <?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){}else{ echo "disabled";} ?>/>

                </div>
            </div>

						<div class="form-group">
							<div id="input_container" class="col-sm-8 col-md-12">
								<input type="checkbox" id="privacy_checkbox" name="privacy_checkbox">
								<labl for="privacy_checkbox">I have read and agree to the <a href="https://www.verticalscope.com/site-privacy-policy/index.php">Privacy Policy</a> and <a href="https://www.verticalscope.com/aboutus/tos.php?site=mtbr.com">Terms of use </a> </label>
							</div>
						</div>

            <div class="form-group">
                <div id="input_container" class="col-sm-8 col-md-12" >
					<?php
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
					{
						?>
							<input type="button" name="register" id="register" value="Next" style="" onclick="ajaxsubmit();" class="register" />
						<?php
					}
					else
					{
						?>
							<input type="submit" name="register" id="register" value="Next" style=""  class="register" />
						<?php
					}
					?>
                </div>
            </div>
			<div class="form-group">
				<div class="col-sm-8 col-md-12">
				<label class="label" id="labelaccount" style="font-size: 13px;color: #000000;"> Or if you are already registered </label>
				</div>
			</div>
			<div class="form-group" style="padding:0px ! important;">
				<div class="col-sm-8 col-md-12">
					<input type="button" name="login" id="login" value="Login" style="" onclick="location.href='user-login.html';" />

				</div>
			</div>
        </form>

        </div>
    </div>
    <div class='col-md-3'>
    </div>







<?php
}
?>
	 </div>
	 </div>
</div>


<?php get_footer(); ?>
<script type="text/javascript">

<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
{
	?>
//Email checking with server



$('#email').on('blur',function ()
{
	//alert('emailval');
	var emailid=$('#email').val();
	var testEmail = /^\s*[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}\s*$/i;
	if (testEmail.test(emailid))
	{
		jQuery.support.cors = true;
		$.ajax({
			type: "GET",
			 crossDomain: true,
			url: encodeURI("/wp-content/themes/site/emailval.php"),
			async: true,
			data: ({ "emailid": emailid.trim() }),
			dataType: "text",
			success: function (data)
			{
				var res = data.split("|");
				//alert(res[0]);
				if (res[0] == 2)
				{
					$('#emailiderrormessage').html('Already registered').css('color', 'red');
				}
				else if (res[0] == 3 )
				{

					$('#emailiderrormessage').html('Unactivated account. Click <a style="    color: #3b7adb;text-decoration: underline;"  href="user-activation.html?email='+res[1].trim()+'">here</a> to activate').css('color', 'red');
				}
				else
				{
					$('#emailiderrormessage').html('Available').css('color', 'green');
				}

			}
		});
	}
	else if(emailid == '')
	{
		$('#emailiderrormessage').html();
	}
	else
	{
		$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
	}


});

$('#username').on('blur',function ()
{
	var username=$('#username').val();
		if(username.length >= 3){
		jQuery.support.cors = true;
				$.ajax({
					 type: "GET",
					 crossDomain: true,
					url: encodeURI("/wp-content/themes/site/usernameval.php"),
					async: true,
					data: ({ "username": username }),
					dataType: "text",
					success: function (data)
					{
						//alert(data);
				 		if (data == 1)
						{
					 	 $('#usernamemessage').html('Username is not available').css('color', 'red');
						 $(window).scrollTop(0);
						}
						else
						{
						$('#usernamemessage').html('Available').css('color', 'green');
						}

					}
				});

		}
		else{
			$('#usernamemessage').html('Should have minimum 3 characters').css('color', 'red');
			$(window).scrollTop(0);
			//$('#register').addClass('registerblock');
			validationerror=true;
		}


});



$('#confirm_password').on('blur',function ()
{
	$('#message').html('');
	$('#emailiderrormessage').html('');
		var cnfpass=$('#confirm_password').val();
		var password=$('#password').val();
			if( password !='' || cnfpass !='')
			{
				if(password != cnfpass)
				{
					$('#message').html('Password does not match').css('color', 'red');
					$(window).scrollTop(0);
					exitajax = 0;
				}
				else
				{
					$('#message').html('Password Matched').css('color', 'green');
					exitajax = 1;
				}
			}
			else
			{
				$('#emailiderrormessage').html('Enter Password & Confirm Password').css('color', 'red');
				return false;
			}
});
	function ajaxsubmit()
	{
		//alert('ajaxsubmit');
		jQuery.support.cors = true;
		var exitajax = 0;
		var email=$('#email').val();
		var emailcap=$('#emailcap').val();
		var username=$('#username').val();
		var cnfpass=$('#confirm_password').val();
		var password=$('#password').val();

		var testEmail = /^\s*[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}\s*$/i;
		if (testEmail.test(email))
		 {
			$('#emailiderrormessage').html('');
			if( password !='' || cnfpass !='')
			{
				if(password != cnfpass)
				{
					$('#message').html('Password does not match').css('color', 'red');
					$(window).scrollTop(0);
					exitajax = 0;
				}
				else
				{
					exitajax = 1;
				}
			}
			else
			{
				$('#emailiderrormessage').html('Enter Password & Confirm Password').css('color', 'red');
				return false;
			}
		}
		else if(email == '')
		{
			$('#emailiderrormessage').html('Enter Email').css('color', 'red');
			$(window).scrollTop(0);
			return false;
		}
		else
		{
			$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
			$(window).scrollTop(0);
			return false;
		}

	if(emailcap != "")
	{
		$('#emailiderrormessage').html('Bots are not Allowed').css('color', 'red');
		exitajax = 0;
	}


		if(exitajax == 1)
		{
			jQuery.support.cors = true;
			$.ajax({
					type: "POST",
					 crossDomain: true,
					//contentType: "application/json; charset=utf-8",
					url: encodeURI("/wp-content/themes/site/userregisact.php"),
					async: true,
					data: ({ "username": username ,"password":cnfpass,"email":email.trim()}),
					dataType: "text",
					success: function (data) {
						if(!$.trim(data)){
							//$('#successmessage').html(data).css('color', 'red');
							window.location = "user-activation.html";
						}else{
							$('#successmessage').html(data).css('color', 'red');
							$(window).scrollTop(0);
						}

					}
				});
		}
	}
	<?php
}
else
{
?>
  var imNotARobot = function() {
    //console.info("Button was clicked");
	document.getElementById("successmessage").innerHTML="";
    document.getElementById("email").disabled = false;
	document.getElementById("username").disabled = false;
	document.getElementById("confirm_password").disabled = false;
	document.getElementById("password").disabled = false;
  };
$(document).ready(function() {





var formsub= {email:false, pwd:false, cnfpwd:false, username:false};

$('#register').closest('form').submit(function(){
		var captcha_response = grecaptcha.getResponse();

			if(captcha_response.length == 0)
			{
				// Captcha is not Passed
				$('#successmessage').html('Please enter recaptcha').css('color', 'red');
				$(window).scrollTop(0);
				//alert('Please Enter reCAPTCHA');
				return false;
			}
			else
			{
				$('#successmessage').html('').css('color', 'red');

				// Captcha is Passed
				//alert('yes');


	var email=$('#email').val();
	var username=$('#username').val();
	var cnfpass=$('#confirm_password').val();
	var password=$('#password').val();
	var validationerror=false;



	if(password != cnfpass){
		$('#message').html('Password does not match').css('color', 'red');
		$(window).scrollTop(0);
		//$('#register').addClass('registerblock');
		validationerror=true;
	}

	if(email){

		var testEmail = /^\s*[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}\s*$/i;

		if (testEmail.test(email)){
			//return true;
			$('#emailiderrormessage').html('Valid');
			//$('#register').removeClass('registerblock');
		}else{
			$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
			//$('#register').addClass('registerblock');
			$(window).scrollTop(0);
			validationerror=true;
		}

	}
	if(password  && cnfpass ){

		var res = cnfpass.match(/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/);
		var cnfres = password.match(/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/);
		if (cnfres == null) {
			$('#passmessage').html('Password is not valid format').css('color', 'red');
			$(window).scrollTop(0);
			//$('#register').addClass('registerblock');
			validationerror=true;
		}else{
			$('#passmessage').html('Valid').css('color', 'green');
			//$('#register').removeClass('registerblock');
		}
		if (res == null) {
			$('#cnfpassmessage').html('Password is not valid format').css('color', 'red');
			$(window).scrollTop(0);
			//$('#register').addClass('registerblock');
			validationerror=true;
		}else{
			$('#cnfpassmessage').html('').css('color', 'green');
			//$('#register').removeClass('registerblock');
		}
	}

	if(username){
		var alphaRegex = /^[a-z]+[a-z0-9._\s]+$/i;
		if(username.length >= 3){
				//$('#register').removeClass('registerblock');
				$.ajax({
					 type: "GET",
					 crossDomain: true,
					//contentType: "application/json; charset=utf-8",
					url: encodeURI("<?php echo VBULLETINURL; ?>/wp_bulletin_username.php"),
					async: true,
					data: ({ "username": username }),
					dataType: "text",
					success: function (data) {
						//alert(data);
				 		if (data == 1) {
						// $('#register').attr('disabled', 'disabled');

					 	 $('#usernamemessage').html('Username is not available').css('color', 'red');
						 $(window).scrollTop(0);
						// formsub.username = false;
						} else {

						$('#usernamemessage').html('Available').css('color', 'green');
						//$('#register').removeAttr('disabled');
						//$('#register').addClass('register');
						//$('#register').removeClass('registerblock');
						//formsub.username = true;
						//validateForm();

						}

					}
				});

		}
		else{
			$('#usernamemessage').html('Should have minimum 3 characters').css('color', 'red');
			$(window).scrollTop(0);
			//$('#register').addClass('registerblock');
			validationerror=true;
		}

	}

	if(validationerror){
		return false;
	}
		$.ajax({
			type: "POST",
			 crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/userregisact.php"),
			async: true,
			data: ({ "username": username ,"password":cnfpass,"email":email.trim()}),
			dataType: "text",
			success: function (data) {
				if(!data.trim()){
					//$('#successmessage').html(data).css('color', 'red');
					window.location = "user-activation.html";
				}else{
					$('#successmessage').html(data).css('color', 'red');
					$(window).scrollTop(0);
				}

			}
		});

	return false;
			}



});

 $('#username').focus(function(){
        $('#usernamemessage').html('');

 })

$('#username').on('keyup',function () {
    var username= this.value;
	var alphaRegex = /^[a-z]+[a-z0-9!@#$&()\\-`.+,\s/]+$/i;
	if(username != ''){
	if(username.length < 3){
		// $('#register').attr('disabled', 'disabled');
		 $('#usernamemessage').html('Should have minimum 3 characters').css('color', 'red');
		 //formsub.username = false;
		//validateForm();


	}else{
		if(alphaRegex.test(username)){
				//return true;
				$('#usernamemessage').html('');
				//$('#register').removeClass('registerblock');
				$.ajax({
					 type: "GET",
					 crossDomain: true,
					//contentType: "application/json; charset=utf-8",
					url: encodeURI("<?php echo VBULLETINURL; ?>/wp_bulletin_username.php"),
					async: true,
					data: ({ "username": username }),
					dataType: "text",
					success: function (data) {
						//alert(data);
				 		if (data == 1) {
						// $('#register').attr('disabled', 'disabled');

					 	 $('#usernamemessage').html('Username is not available').css('color', 'red');
						 //formsub.username = false;
						} else {

						$('#usernamemessage').html('Available').css('color', 'green');
						//$('#register').removeAttr('disabled');
						//$('#register').addClass('register');
						//$('#register').removeClass('registerblock');
						//formsub.username = true;
						//validateForm();

						}

					}
				});
			}else{
				//$('#register').attr('disabled', 'disabled');
				//$('#usernamemessage').html('Should be alphanumeric').css('color', 'red');
				// formsub.username = false;
				//validateForm();

							}

	}
	}else{
	 $('#usernamemessage').html();

	}


});




 $('#email').focus(function(){
        $('#emailiderrormessage').html('');

 })


$('#email').on('blur',function () {

		//alert('vbulletin');
    var emailid= this.value;
	var testEmail = /^\s*[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}\s*$/i;
	if (testEmail.test(emailid) ){
		$.ajax({
			type: "GET",
			 crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/emailval.php"),
			async: true,
			data: ({ "emailid": emailid.trim() }),
			dataType: "text",
			success: function (data) {
				//alert(data);
						var res = data.split("|");
				 if (res[0] == 2) {
					//$('#register').attr('disabled', 'disabled');

					 $('#emailiderrormessage').html('Already registered').css('color', 'red');
					//formsub.email = false;
					//validateForm();

					}
				 else if (res[0] == 3) {
					//$('#register').attr('disabled', 'disabled');
					 $('#emailiderrormessage').html('Unactivated account. Click <a style="    color: #3b7adb;text-decoration: underline;"  href="user-activation.html?email='+res[1].trim()+'">here</a> to activate').css('color', 'red');
					//formsub.email = false;
					//validateForm();

					}
					else
					{	//$('#register').removeAttr('disabled');

						$('#emailiderrormessage').html('Available').css('color', 'green');
						//formsub.email = true;
						//validateForm();


					}

			}
		});
	}else if(emailid == ''){
		$('#emailiderrormessage').html();
	}else{

		//$('#register').attr('disabled', 'disabled');

		$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
		//formsub.email = false;
		//validateForm();
	}


});



 $('#confirm_password').focus(function(){
        $('#message').html('');
	$('#cnfpassmessage').html('');

 });




$('#confirm_password').on('blur',function () {
	//var pass = $('#password').val().match(/^(?=.*\d)[a-zA-Z0-9]{8,}$/);
	var cnfpass = $('#confirm_password').val().match(/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/);
	var vapass=$('#password').val();
	var vacnpass=$('#confirm_password').val();

	if(vacnpass != ''){
		if (cnfpass == null) {
			$('#cnfpassmessage').html('Password is not valid format').css('color', 'red');
			//formsub.pwd=false;

		}else if(  vapass==vacnpass ){
		$('#message').html('Matched!').css('color', 'green');
		//$('#cnfpassmessage').html('Password is not valid format').css('color', 'red');
		//formsub.cnfpwd=true;


    } else{
	$('#message').html('Password does not match').css('color', 'red');
	//formsub.cnfpwd=false;


    }
	}
   // validateForm();

});


 $('#password').focus(function(){
       	$('#passmessage').html('');

 });

$('#password').on('blur',function () {
	var cnfpass = $('#confirm_password').val();
	var pass = $('#password').val();
	var validpass = $('#password').val().match(/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/);

	if(pass != ''){
		if (validpass == null) {
			$('#passmessage').html('Password is not valid format').css('color', 'red');
			//formsub.pwd=false;

		}else if(cnfpass!=pass && cnfpass !=''){
			if(validpass != null){
				$('#passmessage').html('Valid').css('color', 'green');
				//formsub.pwd=true;

			}
			$('#message').html('Password does not match').css('color', 'red');
			//formsub.cnfpwd = false;
			//formsub.pwd=true;
		}else if(cnfpass ==pass && cnfpass !=''){
			if(validpass != null){
				$('#passmessage').html('Valid').css('color', 'green');
				//formsub.pwd=true;

			}

			$('#message').html('Matched').css('color', 'green');
			formsub.pwd=true;

			//formsub.cnfpwd = true;


		}else{
			$('#passmessage').html('Valid').css('color', 'green');
			//formsub.pwd=true;

		}
		}

	//validateForm();
});


});
<?php
}
?>
</script>
