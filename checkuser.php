<?php 
ob_start();
session_start();
include('wp-reviewconfig.php');
?>
<!-- Check user profile-11 -->

 <?php
 if(isset($_POST['password']))
 {
		$pass = $_POST['password'];

		
$fields = array(
	'userid' => ($_COOKIE['bb_userid']),
	'password' => ($pass),
);

$url = VBULLETINURL.'wp-check-user.php?userid='.$_COOKIE['bb_userid'].'&password='.$pass;

		
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
		
		
		
		
	 
 }
		
		
		if($result == "1")
		{
			//echo "Correct";
			$_SESSION['checkuser'] = 'True';
			//header('location /user-profile-1.html');
		}
		else
		{
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
	background-image: linear-gradient(-180deg, #167c00 14%, #167c00 100%);
	border-radius: 8px;
	color:#fff;
}
.submitblock{ 
margin-top: 10px;
		font-family: Lucida Sans, sans-serif !important;
	font-size: 13px;
	width:209px;
height:44px;
	background-image: linear-gradient(-180deg, #167c00 14%, #167c00 100%);
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
  height: 550px; 
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

#input_container {
    position:relative;
    padding:0;
    margin:0;
}
.label{
	margin-top:10px;
}


@media screen  and (max-width: 601px) {



.input_text{
	    margin-bottom: 0px;
}
.labeltext {
	font-size:13px !important;
}
	.label{
	margin-top:0px;
}
.form-group {
    padding:5px ! important;
   margin-bottom: 0rem ! important;}
#email,#password{
	width:100% ! important;
}
}
</style>
		
<div id="content-left" style="background-color:#F0EFDA;" class="col-md-12">
	<div class="inner"> 
		<div class="main-content">
		<div class='col-md-3'>
		</div>
			<div class='container col-sm-8 col-md-6 block' style="    height: auto;    min-height: 300px;">
			
        <div class='block2  center'></br>
			 <label class="labelheading">Welcome <?php echo $_COOKIE['username']; ?></label></br>
           <label class="labeltext">Please re-enter your password to edit your profile</label>
		

				<form action='' method='post' class="form-horizontal" role="form" align="center">
					<div class="form-group" style="padding:5px ! important;"> 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage' style="color:red"><?php if(isset($_POST['password'])){echo "Invalid password";}  ?></span>
						</div>
					</div>
					
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							
							<input type="hidden" name="userid" value="<?php echo $_COOKIE['bb_userid']; ?>">
							<input type="password" name="password" id="password" placeholder="Password" required="true" class="input_text"/>
							
						</div>
					</div>		
					<div class="form-group"> 
						<div class="col-sm-8 col-md-12">  
						
							<input type="submit" class="submit" name="pass" id="submit" value="Confirm" />
							
						</div>
					</div>  
					<div class="form-group">
					<div id="input_container" class="col-sm-8 col-md-12" style="height:20px;">  
					<label  id="textreqpswd" style="width:100%;"><a name="reqpswd" style="float:right;color:#3b7adb;margin-top:10px;display:block;font-size:13px; cursor: pointer;text-decoration: underline;" id="reqpswd" onclick="location.href='user-requestpassword.html';">Forgot Password</a></label>
					</div>
					</div>
				</form>
		   

			</div>
			
			</div> 

		<div class='col-md-3'>
		</div>
		</div> 
	</div> 
</div> 
			<?php
			
			get_footer();
			exit();
		}
	
	
	//echo "<br><br>";
	//echo md5(md5('napster150').'PgW2k$E+9tUk"^veK~Y='."'H%nJJ\VCV");

 ?>
 
<!-- Check user profile-1 -->
