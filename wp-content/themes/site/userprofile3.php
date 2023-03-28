<?php /* Template Name: User Profile 3 Page */
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
			   $user_id =$_POST['userid'];
			   // for dynamic insertion 
			   /*foreach ($_POST as $name => $val)
				{
					 echo htmlspecialchars($name . ': ' . $val) . "\n";
					 $query4 = mysqli_query("");
				}*/
			   // for dynamic insertion 
			   
			if($_POST['gid1'] == 0)
			{ 
				$var1 = "NULL"; 
			}
			else 
			{ 
			 $var1 = $_POST['gid1'];
			 }
			 
			 
			 
			 if($_POST['gid2'] == 0)
			{ 
				$var2 = "NULL"; 
			}
			else 
			{ 
			 $var2 = $_POST['gid2'];
			 }
			 
			 
			 
			 


			
			$check = mysqli_query($con, "select * from user_profile_answers where questionid = 16 and userid = '".$_COOKIE['bb_userid']."'");			 
			 if ($check)
			  {
			   if(mysqli_num_rows($check) > 0)
				   
				{ 
					$qry16 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid1']."', answer='".$_POST['q16']."'  where userid='".$user_id."' and questionid=16"); 
					
					$qry17 = mysqli_query($con,"update user_profile_answers set answerid='".$_POST['r17']."',productid=NULL, answer=NULL where userid='".$user_id."' and questionid=17"); 	
					
					$qry18 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q18']."'  where userid='".$user_id."' and questionid=18"); 			   

					$qry19 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q19']."'  where userid='".$user_id."' and questionid=19"); 			   

					$qry20 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid2']."', answer='".$_POST['q20']."'  where userid='".$user_id."' and questionid=20"); 	

					$qry21 = mysqli_query($con,"update user_profile_answers set answerid='".$_POST['r21']."',productid=NULL, answer=NULL where userid='".$user_id."' and questionid=21"); 					

					$qry22 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q22']."'  where userid='".$user_id."' and questionid=22"); 	
					
					$qry23 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q23']."'  where userid='".$user_id."' and questionid=23"); 	

						if($qry16 && $qry17 && $qry18 && $qry19 && $qry20 && $qry21 && $qry22 && $qry23)
						{
							if (isset($_GET['reg']))
							{
								$reg = "?reg=true";
								header("location: ".$_COOKIE['bb_pre_url']);
							}
							else
							{
								header('location: /user-profile-4.html');
							}
						}				
				
				
				
				} else {
			 // For auto search
			$qry1 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','16',NULL,".$var1.",'".$_POST['q16']."')"); 

			// for radio 
			$qry2 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','17','".$_POST['r17']."',NULL,NULL)"); 			   

			
			 // for normal field
			$qry3 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','18',NULL,NULL,'".$_POST['q18']."')"); 
			
			 // for normal field
			$qry4 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','19',NULL,NULL,'".$_POST['q19']."')"); 
			
			
			 // For auto search
			$qry5 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','20',NULL,".$var2.",'".$_POST['q20']."')"); 
			

			// for radio 
			$qry6 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','21','".$_POST['r21']."',NULL,NULL)"); 			   

			
			 // for normal field
			$qry7 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','22',NULL,NULL,'".$_POST['q22']."')"); 
			
			 // for normal field
			$qry8 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','23',NULL,NULL,'".$_POST['q23']."')"); 
			




				if($qry1 && $qry2 && $qry3 && $qry4 && $qry5 && $qry6 && $qry7 && $qry8)
				{
							if (isset($_GET['reg']))
							{
								$reg = "?reg=true";
								header("location: ".$_COOKIE['bb_pre_url']);
							}
							else
							{
								header('location: /user-profile-4.html');
							}
				}
			   
		   } } }
		   ?>

<?php
$reg="";
if (isset($_GET['reg']))
{
	$reg = "?reg=true";
}
?>
	
		
<!-- Siva for form autocomplete -->		
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
<script type="text/javascript" src="/autocompletetest/jquery.min.js"></script>	
<style>
.ui-autocomplete
{
	text-align:left!important;
}
.ui-menu .ui-menu-item
{
	border-bottom: 1px solid #ccc;
}
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){ ?>
 .leftalign
{
	
	margin-left:80px;
}							


<?php }else{ ?>
 .leftalign
{
	text-align:left!important;
	margin-left:80px;
}

<?php } ?>
@media screen and (max-width: 601px)
{
	.leftalign
	{
		text-align:left!important;
		margin-left: 5px;
	}
}
</style>

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
	background-color:#167c00;
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
      
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){ ?>
.block {
  height: 940px; 
}						 
<?php }else{ ?>
.block {
  height: 780px; 
}

<?php } ?>  
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
<label style="width:100%;">
<?php
if($reg == "")
{
	echo '<a name="skip" href="user-profile-4.html" style="margin-right: 0px !important;float:right;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" >Skip</a>';
}
else
{
	
}
?>

					<a name="Back" style="margin-left: 0px !important;float:left;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-2.html<?php echo $reg; ?>';">Back</a>

					
					</label>
					
					</div>
					</div>
			 <label class="labelheading" style="line-height: 23px;"><?php echo get_option("MainTitleProfile4"); ?></label></br>
           <label class="labeltext"><?php echo get_option("SubTitleProfile4"); ?></label>
				<form action='' method='post' class="form-horizontal" role="form" align="center" novalidate>
					<div class="form-header" > 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>
					
<?php
$a16 = mysqli_query($con, "select * from user_profile_answers where questionid=16 and userid = '".$_COOKIE['bb_userid']."'");
$a17 = mysqli_query($con, "select * from user_profile_answers where questionid=17 and userid = '".$_COOKIE['bb_userid']."'");
$a18 = mysqli_query($con, "select * from user_profile_answers where questionid=18 and userid = '".$_COOKIE['bb_userid']."'");
$a19 = mysqli_query($con, "select * from user_profile_answers where questionid=19 and userid = '".$_COOKIE['bb_userid']."'");
$a20 = mysqli_query($con, "select * from user_profile_answers where questionid=20 and userid = '".$_COOKIE['bb_userid']."'");
$a21 = mysqli_query($con, "select * from user_profile_answers where questionid=21 and userid = '".$_COOKIE['bb_userid']."'");
$a22 = mysqli_query($con, "select * from user_profile_answers where questionid=22 and userid = '".$_COOKIE['bb_userid']."'");
$a23 = mysqli_query($con, "select * from user_profile_answers where questionid=23 and userid = '".$_COOKIE['bb_userid']."'");

	
	
$rowa16=mysqli_fetch_array($a16);
$rowa17=mysqli_fetch_array($a17);
$rowa18=mysqli_fetch_array($a18);
$rowa19=mysqli_fetch_array($a19);
$rowa20=mysqli_fetch_array($a20);
$rowa21=mysqli_fetch_array($a21);
$rowa22=mysqli_fetch_array($a22);
$rowa23=mysqli_fetch_array($a23);


	$q1=mysql_query("SELECT * FROM profile_questions where qid='q1'");
	$q2=mysql_query("SELECT * FROM profile_questions where qid='q2'");
	$q3=mysql_query("SELECT * FROM profile_questions where qid='q3'");
	$q4=mysql_query("SELECT * FROM profile_questions where qid='q4'");
	$q5=mysql_query("SELECT * FROM profile_questions where qid='q5'");
	$q6=mysql_query("SELECT * FROM profile_questions where qid='q6'");
	$q7=mysql_query("SELECT * FROM profile_questions where qid='q7'");
	$q8=mysql_query("SELECT * FROM profile_questions where qid='q8'");
	$q9=mysql_query("SELECT * FROM profile_questions where qid='q9'");
	$q10=mysql_query("SELECT * FROM profile_questions where qid='q10'");
	$q11=mysql_query("SELECT * FROM profile_questions where qid='q11'");
	$q12=mysql_query("SELECT * FROM profile_questions where qid='q12'");
	$q13=mysql_query("SELECT * FROM profile_questions where qid='q13'");
	$q14=mysql_query("SELECT * FROM profile_questions where qid='q14'");
	$q15=mysql_query("SELECT * FROM profile_questions where qid='q15'");
	$q16=mysql_query("SELECT * FROM profile_questions where qid='q16'");
	$q17=mysql_query("SELECT * FROM profile_questions where qid='q17'");
	$q18=mysql_query("SELECT * FROM profile_questions where qid='q18'");
	
	
	
	
	

	
	$rowq1=mysql_fetch_array($q1);
		$rowq2=mysql_fetch_array($q2);
		$rowq3=mysql_fetch_array($q3);
		$rowq4=mysql_fetch_array($q4);
		$rowq5=mysql_fetch_array($q5);
		$rowq6=mysql_fetch_array($q6);
		$rowq7=mysql_fetch_array($q7);
		$rowq8=mysql_fetch_array($q8);
		$rowq9=mysql_fetch_array($q9);
		$rowq10=mysql_fetch_array($q10);
		$rowq11=mysql_fetch_array($q11);
		$rowq12=mysql_fetch_array($q12);
		$rowq13=mysql_fetch_array($q13);
		$rowq14=mysql_fetch_array($q14);
		$rowq15=mysql_fetch_array($q15);
		$rowq16=mysql_fetch_array($q16);
		$rowq17=mysql_fetch_array($q17);
		$rowq18=mysql_fetch_array($q18);
	
	
	
	
?>					
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq13['question']."</span></div>";
							}
							?>
							<input type="hidden" name="userid" value="<?php echo $_COOKIE['bb_userid']; ?>" >
							<input type="text" name="q16" id="name1" placeholder="<?php echo $rowq13['question']; ?>"  class="input_text" value="<?php echo $rowa16['answer']; ?>"/>
	
								<script type="text/javascript">
								$(function() {
									
									//autocomplete
									$("#name1").autocomplete({
										source: "/autocompletetest/search1.php",
										minLength: 1
									});				

								});
								</script>
								
							
						</div>
					</div>
						
					
					<div class="form-group leftalign" >
						<div id="input_container" class="col-sm-8 col-md-12">
						
							  <label style="font-size: 13px;">Did you purchase:</label></br>

        						  <input type="radio" style="  border: 0px;" name="r17" id="male" value="7" <?php if ($rowa17['answerid']=="7") { echo "checked"; } ?>>
							   <label for="male" style="font-size: 13px;">New</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" style="  border: 0px; " name="r17" id="female" value="8" <?php if ($rowa17['answerid']=="8") { echo "checked"; } ?>>
							   <label for="female" style="font-size: 13px;">Used</label>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq14['question']."</span></div>";
							}
							?>
							<input type="text" name="q18" id="username" placeholder="<?php echo $rowq14['question']; ?>"  class="input_text" value="<?php echo $rowa18['answer']; ?>"/>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq15['question']."</span></div>";
							}
							?>
							<input type="text" name="q19" id="username" placeholder="<?php echo $rowq15['question']; ?>"  class="input_text" value="<?php echo $rowa19['answer']; ?>"/>
							
						</div>
					</div>
					
					<style>
					.hr{
						margin-left:80px;
					}
					@media screen and (max-width: 601px)
					{
						.hr
						{
							text-align:left!important;
							    margin-left: 0px;
							    margin-bottom:0px !important;
						}
					}
					</style>
					<div class="form-group" style="">
						<div id="input_container" class="col-sm-8 col-md-12">
							<hr class="hr" style="margin-top: 0px; margin-bottom: 11px; width: 309px;">
						</div>
					</div>
					
					
					
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq16['question']."</span></div>";
							}
							?>
							<input type="text" name="q20" id="name2" placeholder="<?php echo $rowq16['question']; ?>"  class="input_text" value="<?php echo $rowa20['answer']; ?>"/>

								<script type="text/javascript">
								$(function() {
									
									//autocomplete
									$("#name2").autocomplete({
										source: "/autocompletetest/search1.php",
										minLength: 1
									});				

								});
								</script>
								
							
						</div>
					</div>
					
					<div class="form-group leftalign" >
						<div id="input_container" class="col-sm-8 col-md-12">
							
							  <label style="font-size: 13px;">Did you purchase:</label></br>

        						  <input type="radio" style="  border: 0px;" name="r21" id="male" value="9" <?php if ($rowa21['answerid']=="9") { echo "checked"; } ?>>
							   <label for="male" style="font-size: 13px;">New</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" style="  border: 0px;" name="r21" id="female" value="10" <?php if ($rowa21['answerid']=="10") { echo "checked"; } ?>>
							   <label for="female" style="font-size: 13px;">Used</label>
							
						</div>
					</div> 
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq17['question']."</span></div>";
							}
							?>
							<input type="text" name="q22" id="username" placeholder="<?php echo $rowq17['question']; ?>"  class="input_text" value="<?php echo $rowa22['answer']; ?>"/>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq18['question']."</span></div>";
							}
							?>
							<input type="text" name="q23" id="username" placeholder="<?php echo $rowq18['question']; ?>"  class="input_text" value="<?php echo $rowa23['answer']; ?>"/>
							
						</div>
					</div>  
					
					<div class="form-group" > 
						<div class="col-sm-8 col-md-12">  
							<input type="submit" class="submit" name="submit" id="submit" value="<?php if($reg == ""){ echo "Next"; } else { echo "Done";} ?>" />
							
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
