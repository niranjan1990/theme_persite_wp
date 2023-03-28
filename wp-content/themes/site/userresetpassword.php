<?php /* Template Name: User Reset password Page */
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL ^ E_DEPRECATED);
require_once(__DIR__.'/../../../wp-reviewconfig.php');
 ?>
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>

<?php }else{ ?>
<script src="https://code.jquery.com/jquery-2.1.3.min.js" integrity="sha256-ivk71nXhz9nsyFDoYoGf2sbjrR9ddh+XDkCcfZxjvcM=" crossorigin="anonymous"></script>

<?php } ?>
<?php get_header('noad'); ?> 
<?php 

$KEY = "something really long long long long long and secret";
$hash = $_GET['hash'];
$timestamp = $_GET['timestamp'];

if ($hash == md5( $timestamp . $KEY ))
{
    if ( time() - $timestamp > 12600 ) // one hour
    {
        die('link expired');
    }

	



?>
<style>

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


.submit{
color:#fff;
margin-top:10px;
font-family: Lucida Sans, sans-serif !important;
	font-size: 16px;
	width:209px;
height:44px;
background-color: #006A00;
border-radius: 15px;

}
.submitblock{
margin-top:10px;
 cursor:not-allowed;
 background-color: #bfd2b9;
font-family: Lucida Sans, sans-serif !important;
	font-size: 13px;
	width:209px;
height:44px;
 border-radius: 15px; 
 color:#fff;

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
#passmessage,#message,#cnfpassmessage{
display: block;
    width: 100%;
    text-align: left;
    float: left;  
  padding-left: 90px;

}
#input_container {
    position:relative;
    padding:0;
    margin:0;
}
.label{
	margin-top:10px;
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

#passmessage,#message,#cnfpassmessage{
    float: left;  
  padding-left: 10px;

}
.input_text{
  margin-bottom: 10px;
}
.labeltext {
	font-size:13px !important;
}
.label{
	margin-top:0px;
}
.form-group {
  padding:5px ! important;                                                                                                             padding:0px ! important;
   margin-bottom: 0rem ! important;
}
#confirm_password,#password{
	width:100% ! important;
}
}
</style>
<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	 <div class="inner"> 
	 <div class="main-content">
	<div class='col-md-3'>
	</div>

	
	
<?php

		
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
	
$decrypt = my_simple_crypt( $_GET['encrypt'], 'd' );
//echo $decrypted = my_simple_crypt( 'RTlOMytOZStXdjdHbDZtamNDWFpGdz09', 'd' );


?>	
	
	
 <div class='container col-sm-8 col-md-6 block'>
        
        <div class='block2 center'></br>
		
				
				 <label class="labelheading">Reset Password</label>
					<form method='post' class="form-horizontal" role="form" align="center">
					<div class="form-group" style="display:none;">
						<div class="col-sm-8 col-md-12">            
							<input type="text" placeholder="" value="<?php echo $decrypt;?>" name="email" id="email" required="true" class="form-control change" hidden/> 
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
		echo "<div id='input_container' class='col-sm-8 col-md-12'><label>Password : 8 chars + num</label></div>";
							}
							?>
		  
							<span id='passmessage'></span></br>
							<input type="password" placeholder="Password : 8 chars + num" name="password" id="password" required="true" class="input_text"/> 
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">  
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
		echo "<div id='input_container' class='col-sm-8 col-md-12'><label>Confirm Password</label></div>";
							}
							?> 
							<span id='message'></span><span id='cnfpassmessage'></span></br>	
								<input type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required="true" class="input_text"/> 
							
						</div>
					</div>
					<div class="form-group"> 
						<div class="col-sm-8 col-md-12">  
							<input type="submit" name="submit" id="submit"  value="Update" class="submit" />
						</div>
					</div>  		
				</form>
		</div>
</div>

<div class='col-md-3'>
</div>
<?php

if(isset($_POST['submit'])){
	$password= $_POST['password'];
	$confirm_password= $_POST['confirm_password'];
	
	if(preg_match("/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/", $password, $match) && preg_match("/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/", $confirm_password, $match) && $password == $confirm_password)
	{
	$url = VBULLETINURL.'/wp_bulletin_resetpassword.php';
 //$url = 'http://vibulletin.com/wp_bulletin_resetpassword.php';
	$fields = array(
		'email' => ($_POST['email']),
		'password' => ($_POST['password']),
	);



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
 //print_r($result);
 header("location: user-login.html");
//close connection
curl_close($ch);
	
	}else{
 echo "<script>$('#passmessage').html('Password/Confirm password field errors').css('color', 'red');</script>";

	}


 
}
?>



<?php } ?>
 </div>
	 </div>
</div>
<?php get_footer(); ?>

<script type="text/javascript">
$(document).ready(function() { 

 $('#confirm_password').focus(function(){
        $('#message').html('');
	$('#cnfpassmessage').html('');
	           
 });

 $('#password').focus(function(){
       	$('#passmessage').html('');
	           
 });



function showupdate(){
	var password= $('#password').val();
	var cnfpasswd=$('#confirm_password').val();
	var res =/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/.test(password);
//var rescnfpass =cnfpasswd.match(/^(?=.*\d)[a-zA-Z0-9]{8,}$/);
	
	if(res && password == cnfpasswd){
		$('#submit').removeAttr('disabled');

		$('#submit').addClass('submit');
        	$('#submit').removeClass('submitblock');
	}else{
		$('#submit').attr('disabled', 'disabled');

		$('#submit').addClass('submitblock');
 		$('#submit').removeClass('submit');

	}
}

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
	}else{
	$('#passmessage').html();

	}
	//showupdate();
});

$('#confirm_password').on('blur',function () {
	var vapass=$('#password').val();
	var vacnpass=$('#confirm_password').val();
	var cnfpass = vacnpass.match(/^(?=.*\d)[a-zA-Z0-9!@#$%^&()\\-`.+,]{8,}$/);

	if(vacnpass != ''){
		if (cnfpass == null) {
			$('#message').html('');

			$('#cnfpassmessage').html('Password is not valid format').css('color', 'red');
			//formsub.pwd=false;
			
		}else if(  vapass == vacnpass ){
		$('#message').html('Matched').css('color', 'green');
		//$('#cnfpassmessage').html('Password is not valid format').css('color', 'red');
		//formsub.cnfpwd=true;
		
			
    } else{
	$('#cnfpassmessage').html('');

	$('#message').html('Password does not match').css('color', 'red');
	//formsub.cnfpwd=false;

	
    }  
	}else{
	$('#cnfpassmessage').html();
	}
//showupdate();
});

});

</script>
