<?php /* Template Name: User Profile 4 Page */
 ?>

<?php 
require_once(__DIR__.'/../../../wp-config.php');
if(isset($_COOKIE['bb_userid']))
{
	
}
else
{
	header('Location: /');
}
get_header('noad'); 

$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Some error occurred during connection " . mysqli_error($con));  

				
?> 

<script>
$(document).ready(function() {
    $("input:text").focus(function() { $(this).select(); } );
});
</script>
					
<?php 
if(isset($_SESSION['checkuser']))
{
	
}
else
{
	include('checkuser.php'); 	
}		
		
?>	


		   <?php
		   if(isset($_POST['submit']))
		   {
			   $user_id =$_COOKIE['bb_userid'];
	 
			 
			 if($_POST['gid2'] == 0)
			{ 
				$var2 = "NULL"; 
			}
			else 
			{ 
			 $var2 = $_POST['gid2'];
			 }
			 
			 
			 
			 
			 
			if(isset($_POST['newsletter']))
			{
				$newsletter='1';
			}
			else
			{
				$newsletter='0';
			}
			if(isset($_POST['hotdealemail']))
			{
				$hotdealemail='1';
			}
			else
			{
				$hotdealemail='0';
			}
			if(isset($_POST['adminemail']))
			{
				$adminemail='1';
			}
			else
			{
				$adminemail='0';
			}
			if(isset($_POST['showemail']))
			{
				$showemail='1';
			}
			else
			{
				$showemail='0';
			}
			if(isset($_POST['receivefriendrequest']))
			{
				$receivefriendrequest='1';
			}
			else
			{
				$receivefriendrequest='0';
			}
			
			
			$updatenewsemail = mysqli_query($con, "update wp_vbulltein_user_activation set newsletter='".$newsletter."', hotdealemail='".$hotdealemail."' where userid = '".$user_id."'");



		$url1 = VBULLETINURL.'/wp_bulletin_setoptions.php';

		$fields1 = array(
			'userid' => $user_id,
			'adminemail' => $adminemail,
			'showemail' => $showemail,
			'receivefriendrequest' => $receivefriendrequest,
		);




		//open connection
		$ch1 = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch1,CURLOPT_URL, $url1);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1,CURLOPT_POST, count($fields1));
		curl_setopt($ch1,CURLOPT_POSTFIELDS, $fields1);
		$info1 = curl_getinfo($ch1);
		$result1 = curl_exec($ch1);


		header('location: '.$_COOKIE['bb_pre_url']);
			
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

<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){ ?>
 .leftalign
{
	
	margin-left:80px;
}
.label{
white-space:normal !important;
}							


<?php }else{ ?>
 .leftalign
{
	text-align:left!important;
	margin-left:80px;
}

<?php } ?>



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

#input_container {
    position:relative;
    padding:0;
    margin:0;
}

@media screen  and (max-width: 601px) {
.input_text{
	width:100%;
	    margin-bottom: 0px;
}
.labeltext {
	font-size:13px !important;
}
label{
	margin-bottom:0px !important;
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
 <div class='container col-sm-8 col-md-6 block'>
 
        
        <div class='block2  center'>

					<div class="form-header">
					<div class="col-sm-8 col-md-12">  
					<label>
					<a name="Back" style="margin-left: 0px !important;float:left;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-3.html';">Back</a>
					
					</label>

					</div>
					</div>
			 <label class="labelheading" style="line-height: 23px;">Email Preferences</label></br>
           <!--<label class="labeltext"><?php echo get_option("SubTitleProfile4"); ?></label>-->
				<form action='' method='post' class="form-horizontal" role="form" align="center" novalidate>
					<div class="form-header" > 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>
					
<?php

$newsletterquery=mysqli_query($con,"SELECT newsletter FROM wp_vbulltein_user_activation where userid='".$_COOKIE['bb_userid']."'");
$hotdealemailquery=mysqli_query($con,"SELECT hotdealemail FROM wp_vbulltein_user_activation where userid='".$_COOKIE['bb_userid']."'");

	
	
		$url = VBULLETINURL.'/wp_bulletin_getoptions.php?userid='.$_COOKIE['bb_userid'];
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$result = json_decode($resp);

$adminemail = $result->adminemail;
$showemail = $result->showemail;
$receivefriendrequest = $result->receivefriendrequest;
// Close request to clear up some resources
curl_close($curl);
	

$newsletterresult=mysqli_fetch_array($newsletterquery);
$hotdealemailresult=mysqli_fetch_array($hotdealemailquery);
	
	
?>					

						
					


					
					
					
					
					

					
					<div class="form-group leftalign">
						<div id="input_container" class="col-sm-8 col-md-12">
					
							<input style="float:left;    margin-right: 5px;margin-top: 2px;" id="newsletter" type="checkbox" name="newsletter" <?php if($newsletterresult[0]=='1'){echo "checked=checked";}else{}?>><span class="label newsletter" style="float: left;width: 92%;  font-size: 13px;color: black;">Subscribe to our newsletter to get the digest of the week's reviews, comparisons, first looks in your inbox.</span></br>
						</div>
					</div>  

					<div class="form-group leftalign" style="    clear: both;">
						<div id="input_container" class="col-sm-8 col-md-12">
							<input style="float:left;    margin-right: 5px;margin-top: 2px;" id="hotdeals" type="checkbox" name="hotdealemail" <?php if($hotdealemailresult[0]=='1'){echo "checked=checked";}else{}?>><span class="label hotdeals" style="float: left;width: 92%;font-size: 13px;color: black;">Subscribe to our HotDeals emails to get the cream of the crop of the HotDeals retailers make available to our users.</span></br>
						</div>
					</div>  

					<div class="form-group leftalign">
						<div id="input_container" class="col-sm-8 col-md-12">
					
				<input style="float:left;    margin-right: 5px;margin-top: 2px;" id="adminemail" type="checkbox" name="adminemail" <?php if($adminemail=='1'){echo "checked=checked";}else{}?>><span class="label hotdeals" style="float: left;width: 92%;  font-size: 13px;color: black;">Receive emails from our admins about site policies and changes</span></br>
						</div>
					</div>  

					<div class="form-group leftalign">
						<div id="input_container" class="col-sm-8 col-md-12">
				<input style="float:left;    margin-right: 5px;margin-top: 2px;" id="showemail" type="checkbox" name="showemail" <?php if($showemail=='1'){echo "checked=checked";}else{}?>><span class="label hotdeals" style="float: left;width: 92%;  font-size: 13px;color: black; ">Receive emails from other forum members.</span></br>
						</div>
					</div>  

					<div class="form-group leftalign">
						<div id="input_container" class="col-sm-8 col-md-12">
				<input style="float:left;    margin-right: 5px;margin-top: 2px;" id="receivefriendrequest" type="checkbox" name="receivefriendrequest" <?php if($receivefriendrequest=='1'){echo "checked=checked";}else{}?>><span class="label hotdeals" style="float: left;width: 92%;  font-size: 13px;color: black;">Receive emails for friend request from other forum members.</span>					
						</div>
					</div>  

					<div class="form-group"style="clear: both;" > 
						<div class="col-sm-8 col-md-12" >  
							<input type="submit" class="submit" name="submit" id="submit" value="Done" />
							
						</div>
					</div>  
					
					<br><br><br>
				</form>
		</div>
</div>
<div class='col-md-3'>
</div>




 </div>
	 </div>
</div>


	
<?php get_footer(); ?>
