<?php /* Template Name: User Activation Page */ 

ob_start();
session_start(); 
require_once(__DIR__.'/../../../wp-config.php');
require_once(__DIR__.'/../../../wp-reviewconfig.php');
?>
<?Php get_header('noad'); ?> 



<?php 
if(isset($_COOKIE['bb_userid']))
{
	header('Location: /');
} 



if(isset($_GET['email']))
{

			function my_simple_crypt( $string, $action = 'e' ) 
			{
				// you may change these values to your own
				$secret_key = 'my_simple_secret_key';
				$secret_iv = 'my_simple_secret_iv';
			 
				$output = false;
				$encrypt_method = "AES-256-CBC";
				$key = hash( 'sha256', $secret_key );
				$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
			 
				if( $action == 'e' ) {
					$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
				}
				else if( $action == 'd' ){
					$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
				}
			 
				return $output;
			}	
				
			$decrypt = my_simple_crypt( trim($_GET['email']), 'd' );

			//	$useriddb = 

	$fields = array('userid' => '111','password' => '',);

	$url = VBULLETINURL.'wp_bulletin_emailid.php?emailid='.str_replace(" ","+",$decrypt);
			
	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
	$info = curl_getinfo($ch);
	//execute post
	$result = curl_exec($ch);
			
	


				if($result != '0')
				{
					$_SESSION['email'] = str_replace(" ","+",$decrypt);
					require_once(ABSPATH.'wp-content/themes/site/aws-ses-wp-mail-master/aws-ses-wp-mail.php'); 
					//echo $to=$_GET["email"];
					//echo $subject="Confirmation Code";

					
						$con1 = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Some error occurred during connection " . mysqli_error($con));  
					function generatePIN($digits = 4)
					{
					$i = 0; //counter
					$newpin = ""; //our default pin is blank.
					while($i < $digits)
						{
							//generate a random number between 0 and 9.
							$newpin .= mt_rand(0, 9);
							$i++;
						}
						return $newpin;
					}
	 
					//If I want a 4-digit PIN code.
					$newpin = generatePIN();
				//UPDATE `wp_vbulltein_user_activation` SET `security_code`='1111' WHERE `userid`='183'
					$sqlcode = "UPDATE wp_vbulltein_user_activation SET security_code = ".$newpin." WHERE email = '".str_replace(" ","+",$decrypt)."'";
					$resultcode = mysqli_query($con1,$sqlcode);


						$strSQL1 = "SELECT * FROM wp_vbulltein_user_activation where email='".str_replace(" ","+",$decrypt)."'";
						$query1 = mysqli_query($con1, $strSQL1);
						while($result1 = mysqli_fetch_array($query1))
						{
							//echo 'Hi, Your Activation Code is '.$result1['security_code'].'';
							$fieldsnew = array('username' => '111','email' => '',);

							$urlnew = VBULLETINURL.'wp_bulletin_cookie_password.php?userid='.$result1['userid'].'&username=siva';
									
							//open connection
							$chnew = curl_init();

							//set the url, number of POST vars, POST data
							curl_setopt($chnew,CURLOPT_URL, $urlnew);
							curl_setopt($chnew, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($chnew,CURLOPT_POST, count($fieldsnew));
							curl_setopt($chnew,CURLOPT_POSTFIELDS, $fieldsnew);
							$infonew = curl_getinfo($chnew);
							//execute post
							$resultnew = curl_exec($chnew);
									
							$useriddb = $result1['userid'];
							$emaildb = $result1['email'];
							$usernamecurl = $resultnew;
							
							$sqlvm1 = mysqli_query($con1,"SELECT * FROM wp_options where option_name = 'aws_vm_subject'");
							$sqlvm2 = mysqli_query($con1,"SELECT * FROM wp_options where option_name = 'aws_vm_content'");
							$vm1=mysqli_fetch_array($sqlvm1);
							$vm2=mysqli_fetch_array($sqlvm2);

							$to=str_replace(" ","+",$decrypt);
								//$subject="Confirmation Code";
							$subject=$vm1['option_value'];
								//$message="Hi, Your Activation Code is $pin";
							$message =str_replace('$pin',$result1['security_code'],$vm2['option_value']);
								//$message=$vm2['option_value'];
							
						}
						
						$from_email = AWS_SES_WP_MAILINGADDRESS;
						$from_name = AWS_SES_WP_MAILINGNAME;
						$headers=array('From: '.$from_name.' <'.$from_email.'>');
						$mail= wp_mail( $to, $subject, $message, $headers, $attachments = array() );
				}
				else
				{

				}
}
else
{
					$con1 = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Some error occurred during connection " . mysqli_error($con));  
					$strSQL1 = "SELECT * FROM wp_vbulltein_user_activation where email='".str_replace(" ","+",$_SESSION['email'])."'";
					$query1 = mysqli_query($con1, $strSQL1);
					while($result1 = mysqli_fetch_array($query1))
					{
						//echo 'Hi, Your Activation Code is '.$result1['security_code'].'';
						
						$useriddb = $result1['userid'];
					}
}
?> 
<style>
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

.validate{
	margin-top: 10px;

	font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
	background-color:#167c00;
	border-radius: 8px;
	color:#fff;
}
.validateblock{
	font-family: Lucida Sans, sans-serif !important;
	font-size: 13px;
	width:209px;
height:44px;
	border-radius: 8px;
	color:#fff;
	cursor:not-allowed;
	 background-color: #9B9A9B;
}

.form-group{
	padding:5px ! important;
	margin-bottom: 0rem ! important;
}
     
.block {
  height: 750px;
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


.termsandconditions{
    margin-left: 30px;
    text-align: left;
	    display: inline-block;
}

.hotdeals,.newsletter,.termsconditions{
margin-top:0px;
}


.g-recaptcha{
height:78px;
margin:10px;
}


@media screen  and (max-width: 601px) {

#confirmcode{
width:100% ! important;

}

.g-recaptcha{
height:78px;
margin:0px;
}

#labelaccount {
    margin-top: 10px;
}
/*.termsconditions{
display: inline-grid;
}*/
.hotdeals,.newsletter,.termsconditions{
margin-top:0px;
}

.termsandconditions{
    margin-left: -5px;
    text-align: none;
}

input::-webkit-input-placeholder {
     line-height:normal!important;
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

}
</style>
<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">
	<div class='col-md-3'>
	</div>
		 <div class='container col-sm-8 col-md-6 block'>
        
        <div class='block2  center'></br>
			<label class="labelheading" style="line-height:normal;">Verify & Create Account</label></br>
            <label class="labeltext" >We just sent a 4 digit code to your email. Please check your email and enter the code below.</label>
		<form name="activation" method="post"  class="form-horizontal" role="form" align="center">
					 		
			 <div class="form-group" >
                		<div id="input_container"  class="col-sm-8 col-md-12">
				<span id='cnfcodeerrormessage'></span></br>
			
				<input maxlength="4"  pattern="\d{4}" type="text" name="confirmcode" autocomplete="off" id="confirmcode" placeholder="Enter 4 digit code"  class="input_text"/>
				<input  type="text" name="userid" id="userid" placeholder=""  value="<?php if(isset($_SESSION['userid'])){echo $_SESSION['userid'];}else{ echo $useriddb;}?>" hidden/>
				<input  type="text" name="email" id="email" placeholder="" value="<?php if(isset($_SESSION['email'])){echo $_SESSION['email'];}else{ echo $emaildb;}?>" hidden/>
				<input  type="text" name="username" id="username" placeholder="" value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}else{ echo $usernamecurl;}?>" hidden/>

				
				</div>
			</div>
			<div class="form-group" >
				<div class="col-md-6"></div>
				<div class="col-sm-8 col-md-6 mobileresend" >  
					<label id="textreqpswd" style="/* width:100%; */"><a id="resend" class="label" style="color:#3b7adb;margin-top:10px;display:block;font-size:13px;cursor: pointer;text-decoration: underline;">Resend Code</a></label>
				</div>
			</div>
			<div class="form-header " >
				<div class="col-sm-8 col-md-12"> </br> 
				<span id='termserrormessage'></span>
				<div class="termsandconditions">
				<input  id="termsconditions" type="checkbox" style="float:left;margin-right: 5px;margin-top: 2px;"><span class="termsconditions" style="font-weight:bold;font-size: 13px;color: black;float: left;width: 92%;    ">I have read and accept the <a target="_blank" style="color: black;text-decoration: underline;" href='http://<?php echo $_SERVER['SERVER_NAME']; ?>/terms-of-use.html'>Terms of Use</a>,&nbsp;<a target="_blank" style="color: black;text-decoration: underline;" href='http://<?php echo $_SERVER['SERVER_NAME']; ?>/privacy-policy.html'>Privacy Policy</a>&nbsp;and&nbsp;<a target="_blank" style="    color: black;text-decoration: underline;" href='http://<?php echo $_SERVER['SERVER_NAME']; ?>/forum-rules.html'>Forum Rules</a></span></input></br><div style="height:7px;" ></div>
				<input  id="newsletter" type="checkbox"  name="newsletter" style="float:left;margin-right: 5px;margin-top: 2px;"><span class="termsconditions" style="font-size: 13px;color: black;float: left;width: 92%;">Subscribe to our newsletter to get the digest of the week's reviews, comparisons, first looks in your inbox.</span><div style="height:7px;" ></div>
				<input  id="hotdealemail"  type="checkbox" name="hotdealemail" style="margin-top: 2px;float:left;margin-right: 5px;"><span class="termsconditions" style="font-size: 13px;color: black;float: left;width: 92%;">Subscribe to our HotDeals emails to get the cream of the crop of the HotDeals retailers make available to our users.</span></br><div style="height:7px;" ></div>

				<input  id="adminemail" type="checkbox" name="adminemail" style="margin-top: 2px;float:left;margin-right: 5px;"><span class="termsconditions" style="font-size: 13px;color: black;float: left;width: 92%;"> Receive emails from our admins about site policies and changes.</span></br><div style="height:7px;" ></div>
				<input id="showemail" type="checkbox" name="showemail" style="float:left;margin-right: 5px;margin-top: 2px;"><span class="label hotdeals" style="font-size: 13px;color: black;float: left;width: 92%;"> Receive emails from other forum members.</span></br><div style="height:7px;" ></div>
				<input id="receivefriendrequest" type="checkbox" name="receivefriendrequest"style="float:left;margin-right: 5px;margin-top: 2px;" ><span class="termsconditions" style="font-size: 13px;color: black;float: left;width: 92%;"> Receive emails for friend request from other forum members.</span>
				</div>
				</div>
			</div>
			
			<div class="form-group"> 
				<div class="col-sm-8 col-md-12">  
					<input type="submit" class="validate" name="validate" id="validate" value="Create Account" style="" required="true" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-md-12">  
				<label class="label" id="labelaccount" style="font-size: 13px;color: #000000;"> Or if you are already registered </label>
				</div>
			</div>
			<div class="form-group" >
				<div class="col-sm-8 col-md-12">  
					<input type="button" name="login" id="login" value="Login" style="font-family: Lucida Sans, sans-serif !important;	font-size: 16px;	width:209px;height:44px;background-color: #0090EA;color:#fff;border: 1px solid #0090EA;border-radius: 8px;" onclick="location.href='user-login.html';" />

				</div>
			</div> 
		</form>
		</div></div>
<div class='col-md-3'>
</div>

</div>
	 </div>
</div>





<?php get_footer(); ?>	 
<script type="text/javascript">
$(document).ready(function() {

$('#confirmcode').on('keyup',function () {


var patterncnfcode=/^[0-9]*$/;
 var confirmcode= $('#confirmcode').val();
	if(patterncnfcode.test(confirmcode) && confirmcode.length == 4 && $('#termsconditions').prop('checked')  ){
		//$('#validate').removeAttr('disabled');
		//$('#validate').removeClass('validateblock');	
	  	//$('#validate').addClass('validate');
		$('#cnfcodeerrormessage').html('');
	}else if(patterncnfcode.test(confirmcode) && confirmcode.length == 4 && !$('#termsconditions').prop('checked') ){
		$('#cnfcodeerrormessage').html('');
		//$('#validate').attr('disabled', 'disabled');


		 // $('#validate').removeClass('validate');	
		 // $('#validate').addClass('validateblock');
	

	}else {

		if(isNaN(confirmcode))
		{
		//$('#validate').attr('disabled', 'disabled');

		 $('#cnfcodeerrormessage').html('Enter only numbers').css('color', 'red');
		  //$('#validate').removeClass('validate');	
		  //$('#validate').addClass('validateblock');				
		}
		else
		{
		 //$('#validate').attr('disabled', 'disabled');
		
		 $('#cnfcodeerrormessage').html('Enter all 4 digit code').css('color', 'red');
		 // $('#validate').removeClass('validate');	
		 // $('#validate').addClass('validateblock');				
		}

	}

});

$('#termsconditions,#newsletter,#hotdeals').change(function(){
var confirmcode= $('#confirmcode').val();

if($('#termsconditions').prop('checked') && confirmcode.length == 4 ){
	//$('#validate').removeAttr('disabled');

	//$('#validate').removeClass('validateblock');	
	  //	$('#validate').addClass('validate');
		$('#cnfcodeerrormessage').html('');
	$('#termserrormessage').html('');

}else if($('#termsconditions').prop('checked') ){

	$('#termserrormessage').html('');

}else{
  //$('#validate').attr('disabled', 'disabled');

 $('#termserrormessage').html('You need to accept the terms and conditions').css('color', 'red');
 //$('#validate').removeClass('validate');	
 //$('#validate').addClass('validateblock');
}

});


$('#resend').on('click', function () {
	var userid=$('#userid').val();
	var email=$('#email').val();
	$.ajax({
			type: "GET",
			 //crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/userresendcode.php"),  
			//async: true,
			data: ({ "userid": userid ,"email":email}),
			dataType: "text",
			success: function (data) {
				$('#cnfcodeerrormessage').html('Mail has been sent').css('color', 'green');			}
		});
});
	
$('#validate').on('click',function(){
	var userid=$('#userid').val();
	var username=$('#username').val();
	var confirmcode=$('#confirmcode').val();
	var newsletter='0';
	if($("#newsletter").is(':checked'))
	{
		newsletter='1';
	}
	var hotdealemail='0';
	if($("#hotdealemail").is(':checked'))
	{
		hotdealemail='1';
	}
	var adminemail='0';
	if($("#adminemail").is(':checked'))
	{
		adminemail='1';
	}
	var showemail='0';
	if($("#showemail").is(':checked'))
	{
		showemail='1';
	}
	var receivefriendrequest='0';
	if($("#receivefriendrequest").is(':checked'))
	{
		receivefriendrequest='1';
	}
	var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
	
	var patterncnfcode=/^[0-9]*$/;
	var validationerror=false;


	if(patterncnfcode.test(confirmcode) && confirmcode.length == 4 && $('#termsconditions').prop('checked')  ){
		//$('#validate').removeAttr('disabled');
		//$('#validate').removeClass('validateblock');	
	  	//$('#validate').addClass('validate');
		$('#cnfcodeerrormessage').html('');
	}else if(patterncnfcode.test(confirmcode) && confirmcode.length == 4 && !$('#termsconditions').prop('checked') ){
		$('#cnfcodeerrormessage').html('');
		//$('#validate').attr('disabled', 'disabled');


		 // $('#validate').removeClass('validate');	
		 // $('#validate').addClass('validateblock');
			validationerror=true;

	

	}else {

		if(isNaN(confirmcode))
		{
		//$('#validate').attr('disabled', 'disabled');

		 $('#cnfcodeerrormessage').html('Enter only numbers').css('color', 'red');
		  //$('#validate').removeClass('validate');	
		  //$('#validate').addClass('validateblock');	
		    validationerror=true;
			
		}
		else
		{
		 //$('#validate').attr('disabled', 'disabled');
		
		 $('#cnfcodeerrormessage').html('Enter all 4 digit code').css('color', 'red');
		 // $('#validate').removeClass('validate');	
		 // $('#validate').addClass('validateblock');
		   validationerror=true;
				
		}

	}



if($('#termsconditions').prop('checked') && confirmcode.length == 4 ){
	//$('#validate').removeAttr('disabled');

	//$('#validate').removeClass('validateblock');	
	  //	$('#validate').addClass('validate');
		$('#cnfcodeerrormessage').html('');
	$('#termserrormessage').html('');

}else if($('#termsconditions').prop('checked') ){

	$('#termserrormessage').html('');

}else{
  //$('#validate').attr('disabled', 'disabled');

 $('#termserrormessage').html('You need to accept the terms and conditions').css('color', 'red');
 //$('#validate').removeClass('validate');	
 //$('#validate').addClass('validateblock');
validationerror=true;

}
	if(numberRegex.test(confirmcode)){
		
	}else{
	    $('#cnfcodeerrormessage').html('Enter all 4 digit code').css('color', 'red');	
	     validationerror=true;
	}

	if(validationerror){
		return false;
	}

	jQuery.support.cors = true;
	$.ajax({
			type: "GET",
			 //crossDomain: true,
			//contentType: "application/json; charset=utf-8",
			url: encodeURI("/wp-content/themes/site/usercodecheck.php"), 
			//async: true,
			data: ({ "userid": userid ,"confirmcode":confirmcode,"username":username,"newsletter":newsletter,"hotdealemail":hotdealemail,"adminemail":adminemail,"showemail":showemail,"receivefriendrequest":receivefriendrequest}),
			dataType: "text",
			success: function (data) {
				//alert(data);
				 if ($.trim(data) == 'Correct') {
					// $('#cnfcodeerrormessage').html('correct').css('color', 'green');
					 window.location = "<?php $regflag = get_option('registration_flag'); if(isset($_SESSION['cnewurl'])){echo $_SESSION['prul'];}else{if($regflag = '1'){echo '/user-profile.html?reg=true';}else{echo $_SESSION['rurl'];}} ?>";
					} 
					else {
					$('#cnfcodeerrormessage').html('Verification code did not match').css('color', 'red');
					//return false;
					}
				
			}
		});
	
	return false;
	
});



});

</script>

