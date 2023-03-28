<?php /* Template Name: User Request password Page */ ?>

<?php
ob_start();
session_start();
require_once(__DIR__.'/../../../wp-reviewconfig.php');
 ?>
<?php get_header('noad'); ?> 

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

.request{
    margin-top: 10px;
	font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
		width:209px;
height:44px;
	background-color: #167c00;
	border-radius: 8px;
	color:#fff;
}
.requestblock{
    margin-top: 10px;
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
  height: 500px;  
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
#input_img {
    position:absolute;
    width:48px;
    height:51px;
}
#input_container {
    position:relative;
    padding:0;
    margin:0;
}
.label{
	margin-top:10px;
}
#emailiderrormessage{
   float: left;  
  padding-left: 88px;

}

#registration{
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

.g-recaptcha{
height:78px;
margin:0px;
}

#labelaccount{
    margin-top: 10px;
}
#emailiderrormessage{
    float: left;  
  padding-left: 10px;

}
input::-webkit-input-placeholder {
     line-height:normal!important;
}
.labeltext {
	font-size:13px !important;
}
	.label{
	margin-top:0px;
}
.form-group {
   padding:5px ! important;
	margin-bottom: 0rem ! important;
}
#email{
	width:100% ! important;
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
			<label class="labelheading" >Forgot Password</label></br>
           <label class="labeltext" >Enter your email address and we'll send you a link to reset your password.</label>
				<form  method='post' class="form-horizontal" role="form" align="center">
					
					<div class="form-header" > 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>
					<div class="form-header">
						<div id="input_container" class="col-sm-8 col-md-12">
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
		echo "<div id='input_container' class='col-sm-8 col-md-12'><label>Email address</label></div>";
							}
							?>
		
							<span id='emailiderrormessage'></span></br>
							<input autocapitalize="none" type="text" name="email" id="email" placeholder="Email address"  pattern="\s*[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}\s*$" required="true" class="input_text"/>
						</div>
					</div> 
					<div class="form-group" > 
						<div class="col-sm-8 col-md-12">  
							<input type="submit" class="request" name="request" id="request" value="Reset Password" />
						</div>
					</div>	
					
					</form>
		</div>
</div>
<div class='col-md-3'>
</div>
<?php

if(isset($_POST['request']))
{
	$emailid=trim($_POST['email']);
	
	if(!filter_var($emailid, FILTER_VALIDATE_EMAIL)){
	 echo "<script>$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');</script>";
	

	}else{

				$urldata = [];
	$data = array('emailid' => trim($_POST['email']));


foreach($data as $k=>$v) {
	$urldata[] = $k . '=' . urlencode($v);
}       
$url = VBULLETINURL.'/wp_bulletin_emailid.php'. '?' .implode('&', $urldata);

//open connection
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_HTTPGET , count($data));
$info = curl_getinfo($ch);

 $result = curl_exec($ch);
 if($result==2){
	 require_once(ABSPATH.'wp-content/themes/site/aws-ses-wp-mail-master/aws-ses-wp-mail.php'); 
	  $KEY = "something really long long long long long and secret";
	   $time = time();
	   
	   
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
		// Check connection
		if($link === false){
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		 

		$sqlfp1 = mysqli_query($link,"SELECT * FROM wp_options where option_name = 'aws_fp_subject'");
		$sqlfp2 = mysqli_query($link,"SELECT * FROM wp_options where option_name = 'aws_fp_content'");
		$fp1=mysqli_fetch_array($sqlfp1);
		$fp2=mysqli_fetch_array($sqlfp2);


		
	   
	   
	    $hash = md5( $time . $KEY);

		
		
		
		
		
		
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
	
$encrypt = my_simple_crypt( trim($_POST['email']), 'e' );
//echo $decrypted = my_simple_crypt( 'RTlOMytOZStXdjdHbDZtamNDWFpGdz09', 'd' );


	  //  $encrypt = trim($_POST['email']);
		$to=trim($_POST['email']);
		//$subject="reset password";
		$subject=$fp1['option_value'];
		$domain = $_SERVER['SERVER_NAME'];
		//$message="Hi, Click here to reset your password http://$domain/user-resetpassword.html?encrypt=$encrypt&timestamp=$time&hash=$hash";
		$reset_link = "http://$domain/user-resetpassword.html?encrypt=$encrypt&timestamp=$time&hash=$hash";
		$message =str_replace('$reset_link',$reset_link,$fp2['option_value']);
	  $from_email = AWS_SES_WP_MAILINGADDRESS;
                  $from_name = AWS_SES_WP_MAILINGNAME;
                  $headers=array('From: '.$from_name.' <'.$from_email.'>');
                  $mail= wp_mail( $to, $subject, $message, $headers, $attachments = array() );

	header('location: /user-login.html?reset=true');
	return false;
 }else{
	 echo "<script>$('#successerrormessage').html('Invalid email or unregistered').css('color', 'red');</script>";
 }
	}

 
}else{
	
}




?>
</div>
	 </div>
</div>
<?php get_footer(); ?>
<script type="text/javascript">
$(document).ready(function() { 
$('#email').on('focus',function () { 

$('#emailiderrormessage').html('');	

});

$('#email').on('change',function () {
    var emailid= this.value;
	var testEmail = /^\s*[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}\s*$/i;
	if (testEmail.test(emailid) ){
		//$('#request').removeAttr('disabled');

		$('#emailiderrormessage').html('');

		 //$('#request').addClass('request');
		 //$('#request').removeClass('requestblock');

	}else if (emailid == ''){
		$('#emailiderrormessage').html();
	}else{
		//$('#request').attr('disabled', 'disabled');

		$('#emailiderrormessage').html('Email is not valid format').css('color', 'red');
		//$('#request').addClass('requestblock');
		// $('#request').removeClass('request');

	}

});

});
</script>





