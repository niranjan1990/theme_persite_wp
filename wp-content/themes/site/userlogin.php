<?php /* Template Name: User Login Page */
ob_start();
session_start();
require_once(__DIR__.'/../../../wp-reviewconfig.php');
 ?>
 <?Php get_header('noad'); ?> 
 <?php
//print_r($_POST);
if(!isset($_SESSION['rurl']))
{
	if(isset($_COOKIE['bb_pre_url']))
	{
		$_SESSION['rurl'] = cookie_redirect($_COOKIE['bb_pre_url']);
	}
}
 
 ?>

 <!-- New Ajax Search -->

		<!-- Load CSS -->
		<link href="/ajaxsearch/style/style.css" rel="stylesheet" type="text/css" />
		<!-- Load Fonts -->





<!-- New Ajax Search -->

<?php
if(isset($_COOKIE['bb_userid'])){
	header('location: /');
}else{
 
?>

<style>




.change::-webkit-input-placeholder {
    /* WebKit, Blink, Edge */
    color: black;
}
input::-webkit-input-placeholder {
     line-height:normal!important;
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
.submit{
	 margin-top: 10px;
		font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
	background-color: #167c00;
	border-radius: 8px;
	color:#fff;
}
.submitblock{
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






/* Working below */




/* suspecious */
.form-group{
	 padding:5px ! important;
   margin-bottom: 0rem ! important;
}


     
.input_text{
	    margin-bottom: 10px;
	font-family: Lucida Sans, sans-serif !important;
	padding-left:10px;
	 width: 309px;
  height: 40px;
  border-radius: 8px;
  background-color: #f7f8fa;
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
#textreqpswd{
	float:right;
    margin-right: 80px;

}
.block {
  height: 600px;
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

#emailiderrormessage,#passerrormessage{
display: block;
    width: 100%;
    text-align: left;
    float: left;  
  padding-left: 90px;

}
#registration{
    font-family: Lucida Sans, sans-serif !important;
font-size:16px;
    background-color: #0090EA; 
color:#fff;
width:209px;
height:44px;
border-radius: 8px;

}
.g-recaptcha{
height:78px;
margin:10px;
}

@media screen  and (max-width: 601px) {
.g-recaptcha{
height:78px;
margin:0px;
}
#labelaccount{
    margin-top: 10px;
}
#emailiderrormessage,#passerrormessage{
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
#textreqpswd{
//float: none;
    margin-right: 0px;

}
.form-group {
    padding:5px ! important;
   margin-bottom: 0rem ! important;}
#email,#password{
	width:100% ! important;
}
.input_text{
	    margin-bottom: 0px;
}
input::-webkit-input-placeholder {
     line-height:normal!important;
}
}
</style>

<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">
<div class='col-md-3'>
</div>
 <div class='container col-sm-8 col-md-6 block' >
        
        <div class='block2  center'></br>
			 <label class="labelheading">Login</label></br>
           <label class="labeltext">To write reviews, post to forums, list in classified, upload to galleries and a lot more!</label>
				<form action='' method='post' class="form-horizontal" role="form" align="center">


				
								
								
								
								
								
								
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
{
}
else
{
	?>
							<div class="form-group" >
               					<div id="input_container" class="col-sm-8 col-md-12">  
									<center>
										<div class="g-recaptcha"  data-callback="imNotARobot" data-sitekey="<?php echo SITEKEY;?>" ></div>
									</center>			
                				</div> 
            				</div>
	<?php
}
?>
														
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
							
							


				<div class="form-header"> 
						<div class="col-sm-8 col-md-12">  
							<?php if(isset($_GET['reset'])){ echo "<span id='successerrormessage' style='color:green;'>Your password reset link was sent to your email address</span>";} 
								else { echo "<span id='successerrormessage'></span>";}	
							?>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<span id='emailiderrormessage' style=""></span></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Email Address</span><br>";
							}
							?>
							<input autocapitalize="none" type="text" name="email" id="email" placeholder="Email address or Username" required="true" class="input_text"/>
							
						</div>
					</div> 
					<div class="form-group" >
						<div id="input_container" class="col-sm-8 col-md-12">
							<!--<span id='passerrormessage'></span>--></br>
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<span>Password</span><br>";
							}
							?>
						<input autocapitalize="none" type="password" name="password" id="password" placeholder="Password" required="true" class="input_text"/>
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
					<div id="input_container" class="col-sm-8 col-md-12" style="height:20px;">  
					<label  id="textreqpswd" style="width:100%;"><a name="reqpswd" style="float:right;color:#3b7adb;margin-top:10px;display:block;font-size:13px; cursor: pointer;text-decoration: underline;" id="reqpswd" onclick="location.href='user-requestpassword.html';">Forgot Password</a></label>
					</div>
					</div>
					
					<div class="form-group" > 
						<div id="input_container" class="col-sm-8 col-md-12">  
						<?php 
						if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) 
						{
							?>
							<input type="button" class="submit" name="submit" id="submit" value="Login" onclick="ajaxsubmit();"/>
							<?php
						}
						else
						{
							?>
							<input type="submit" class="submit" name="submit" id="submit" value="Login"/>
							<?php
						}
						?>
						
						</div>
					</div>  
					<div class="form-group">	
					<div id="input_container" class="col-sm-8 col-md-12" >  
					<label class="label" id="labelaccount" style="font-size: 13px;color: #000000;">If you don't have an account, Register now </label>
					</div>
					</div>	
					<div class="form-group" style="padding:0px ! important;">
					<div id="input_container" class="col-sm-8 col-md-12">  
					<input type="button" name="registration" id="registration" value="Register" style="" onclick="location.href='user-registration.html';" />
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

function ajaxsubmit()
{
	//alert('ajaxsubmit');
	//for ie8 and ie9
	jQuery.support.cors = true;
	var email=$('#email').val();
	var password=$('#password').val();
	var emailcap=$('#emailcap').val();
	var exitajax = 0;
	var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
	
	 if (testEmail.test(email))
	 {
		$('#emailiderrormessage').html('');
		if( password !=' '){
			$('#emailiderrormessage').html('Valid').css('color', 'green');	
			exitajax = 1;
		}
		else
		{
			$('#emailiderrormessage').html('Enter Password').css('color', 'red');
			return false;			
		}
	}
	else if(email == '')
	{
		$('#emailiderrormessage').html('Enter Email').css('color', 'red');
		return false;
	}
	else
	{
		$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
		return false;
	}
	if(emailcap != "")
	{
		$('#emailiderrormessage').html('Bots are not Allowed').css('color', 'red');
		exitajax = 0;
	}


 
	if(exitajax == 1)
	{
		$.ajax({
			type: "POST",
			 crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/userlog.php"), 
			//url: encodeURI("http://mtbr.owshi.com/wp-content/themes/site/userlog.php"), 
			async: true,
			data: ({ "password":password,"email":email}),
			dataType: "text",
			success: function (data) {
			   //if(data.trim() == 'Invalid Username or Password.')
			   if($.trim(data) == 'Invalid Username or Password.')
			   {

				 $('#successerrormessage').html('Invalid username or password, please try again').css('color', 'red');
				 $(window).scrollTop(0);
			   }
			   //else if(data.trim() == 'Un-Activated Account.')
			   else if($.trim(data) == 'Un-Activated Account.')
			   {
				  $('#successerrormessage').html('Unactivated account. Click <a style="    color: #3b7adb;text-decoration: underline;"  href="/user-activation.html?email='+email+'">here</a> to activate').css('color', 'red');
				  $(window).scrollTop(0);
			   }
			   //else if(data.trim() == 'logged In')
			   else if($.trim(data) == 'logged In')
			   {
					window.location = "<?php echo $_SESSION['rurl']; ?>";

				}
                else
                {
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

document.getElementById("successerrormessage").innerHTML="";
  };

$(document).ready(function() { 
$('#submit').closest('form').submit(function(){
	var captcha_response = grecaptcha.getResponse();
			
			if(captcha_response.length == 0)
			{
				// Captcha is not Passed
				$('#successerrormessage').html("Please enter recaptcha").css('color', 'red');
				$(window).scrollTop(0);
				//alert('Please enter recaptcha');
				return false;
			}
			else
			{	$('#successerrormessage').html("");

				// Captcha is Passed
				//alert('yes');

				var email=$('#email').val();
	var password=$('#password').val();
	var validationerror=false;
	
	if(email=='' || password ==''){
		//$('#emailiderrormessage').html('Email invalid').css('color', 'red');
		//$('#passerrormessage').html('Please enter password').css('color', 'red');
		//$('#submit').addClass('submitblock');
		validationerror=true;
		$(window).scrollTop(0);
	}
	/*if(email){
		var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
		if (testEmail.test(email) ){
			//$('#submit').addClass('submit');
		}else{
			//$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
			//$('#submit').addClass('submitblock');
			
			validationerror=true;
			$(window).scrollTop(0);
		}
	}*/
	if(validationerror){
		return false;
	}
		$.ajax({
			type: "POST",
			 crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/userlog.php"), 
			//url: encodeURI("http://mtbr.owshi.com/wp-content/themes/site/userlog.php"), 
			async: true,
			data: ({ "password":password,"email":email}),
			dataType: "text",
			success: function (data) {
			  //alert(data );
			   if(data.trim() == 'Invalid Username or Password.')
			   {

				 $('#successerrormessage').html('Invalid username or password, please try again').css('color', 'red');
				 $(window).scrollTop(0);
			   }
			   else if(data.indexOf("Un-Activated Account.") != -1)
			   {
					//alert(name);
				  var res = data.split("|");
				  $('#successerrormessage').html('Unactivated account. Click <a style="    color: #3b7adb;text-decoration: underline;"  href="/user-activation.html?email='+res[1]+'">here</a> to activate').css('color', 'red');
				  $(window).scrollTop(0);
			   }
			   else if(data.trim() == 'logged In')
			   {
					window.location = "<?php echo $_SESSION['rurl']; ?>";

				}
                else
                {
					//alert(data);
                }







				
			}
		});
	
	return false;
				
			
			
				}

	
	
	
});

 $('#email').focus(function(){
        $('#emailiderrormessage').html('');           
 });


/*$('#email').on('blur', function () {
	var password=$('#password').val();
    var emailid= $('#email').val();
	var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
	
	 if (testEmail.test(emailid)){
		$('#emailiderrormessage').html('');
		if( password !=' '){
			$('#emailiderrormessage').html('Valid').css('color', 'green');	
		}
		

	}else if(emailid == ''){
		$('#emailiderrormessage').html();
	}else{
		$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
	}

});
 $('#password').focus(function(){
        $('#passerrormessage').html('');           
 });
*/


});

<?php
}
?>
</script>
