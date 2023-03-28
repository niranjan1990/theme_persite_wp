<?php /* Template Name: User Profile 2 Page */
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
	//echo "Included Check User";
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
			 $check = mysqli_query($con, "select * from user_profile_answers where questionid = 8 and userid = '".$_COOKIE['bb_userid']."'");			 
			 if ($check)
			  {
			   if(mysqli_num_rows($check) > 0)
				   
				{	
					$qry8 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid1']."', answer='".$_POST['q8']."'  where userid='".$user_id."' and questionid=8"); 
					
					$qry9 = mysqli_query($con,"update user_profile_answers set answerid='".$_POST['r9']."',productid=NULL, answer=NULL where userid='".$user_id."' and questionid=9"); 	
					
					$qry10 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q10']."'  where userid='".$user_id."' and questionid=10"); 			   

					$qry11 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q11']."'  where userid='".$user_id."' and questionid=11"); 			   

					$qry12 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid2']."', answer='".$_POST['q12']."'  where userid='".$user_id."' and questionid=12"); 	

					$qry13 = mysqli_query($con,"update user_profile_answers set answerid='".$_POST['r13']."',productid=NULL, answer=NULL where userid='".$user_id."' and questionid=13"); 					

					$qry14 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q14']."'  where userid='".$user_id."' and questionid=14"); 	
					
					$qry15 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q15']."'  where userid='".$user_id."' and questionid=15"); 	

						if($qry8 && $qry9 && $qry10 && $qry11 && $qry12 && $qry13 && $qry14 && $qry15)
						{
							if (isset($_GET['reg']))
							{
								$reg = "?reg=true";
							}
							header("location: /user-profile-3.html".$reg);
						}
			
				}
				else
				{
					 // For auto search
					$qry1 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','8',NULL,".$var1.",'".$_POST['q8']."')"); 

					// for radio 
					$qry2 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','9','".$_POST['r9']."',NULL,NULL)"); 			   

					
					 // for normal field
					$qry3 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','10',NULL,NULL,'".$_POST['q10']."')"); 
					
					 // for normal field
					$qry4 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','11',NULL,NULL,'".$_POST['q11']."')"); 
					
					
					 // For auto search
					$qry5 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','12',NULL,".$var2.",'".$_POST['q12']."')"); 
					

					// for radio 
					$qry6 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','13','".$_POST['r13']."',NULL,NULL)"); 			   

					
					 // for normal field
					$qry7 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','14',NULL,NULL,'".$_POST['q14']."')"); 
					
					 // for normal field
					$qry8 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','15',NULL,NULL,'".$_POST['q15']."')"); 

					
					if($qry1 && $qry2 && $qry3 && $qry4 && $qry5 && $qry6 && $qry7 && $qry8)
					{
						if (isset($_GET['reg']))
						{
							$reg = "?reg=true";
						}
						header("location: /user-profile-3.html".$reg);
					}
					
				}
			  } 
		   }
		   ?>


 <style>

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

<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false){ ?>
.block {
  height: 910px; 
}						 
<?php }else{ ?>
.block {
  height: 710px; 
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

<?php

						if (isset($_GET['reg']))
						{
							$reg = "?reg=true";
						}

?>


 <div class='container col-sm-8 col-md-6 block'>
        
        <div class='block2  center'>
					<div class="form-header">
					<div class="col-sm-8 col-md-12">  
					<label style="width:100%;"><a name="skip" style="margin-right: 0px !important;float:right;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-3.html<?php echo $reg; ?>';">Skip</a>
					<a name="Back" style="margin-left: 0px !important;float:left;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-1.html<?php echo $reg; ?>';">Back</a>

					
					</label>
					</div>
					</div>
			 <label class="labelheading" style="line-height: 23px;"><?php echo get_option("MainTitleProfile3"); ?></label></br>
           <label class="labeltext"><?php echo get_option("SubTitleProfile3"); ?></label>
				<form action='' method='post' class="form-horizontal" role="form" align="center">
					<div class="form-header"> 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>
					
<?php
//echo "select * from user_profile_answers where questionid=8 and userid = '".$_COOKIE['bb_userid']."'";
$a8 = mysqli_query($con, "select * from user_profile_answers where questionid=8 and userid = '".$_COOKIE['bb_userid']."'");
$rowa8=mysqli_fetch_array($a8);
$a9 = mysqli_query($con, "select * from user_profile_answers where questionid=9 and userid = '".$_COOKIE['bb_userid']."'");
$rowa9=mysqli_fetch_array($a9);
$a10 = mysqli_query($con, "select * from user_profile_answers where questionid=10 and userid = '".$_COOKIE['bb_userid']."'");
$rowa10=mysqli_fetch_array($a10);
$a11 = mysqli_query($con, "select * from user_profile_answers where questionid=11 and userid = '".$_COOKIE['bb_userid']."'");
$rowa11=mysqli_fetch_array($a11);
$a12 = mysqli_query($con, "select * from user_profile_answers where questionid=12 and userid = '".$_COOKIE['bb_userid']."'");
$rowa12=mysqli_fetch_array($a12);
$a13 = mysqli_query($con, "select * from user_profile_answers where questionid=13 and userid = '".$_COOKIE['bb_userid']."'");
$rowa13=mysqli_fetch_array($a13);
$a14 = mysqli_query($con, "select * from user_profile_answers where questionid=14 and userid = '".$_COOKIE['bb_userid']."'");
$rowa14=mysqli_fetch_array($a14);
$a15 = mysqli_query($con, "select * from user_profile_answers where questionid=15 and userid = '".$_COOKIE['bb_userid']."'");
$rowa15=mysqli_fetch_array($a15);

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
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq7['question']."</span></div>";
							}
							?>
							<input type="hidden" name="userid" value="<?php echo $_COOKIE['bb_userid']; ?>" >
							<input type="text" name="q8" id="name1" placeholder="<?php echo $rowq7['question']; ?>"  class="input_text" value="<?php echo $rowa8['answer']; ?>" />
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
					
        						  <input type="radio" style="  border: 0px;   " name="r9" id="male" value="3" <?php if ($rowa9['answerid']=="3") { echo "checked"; } ?>>
							   <label for="male" style="font-size: 13px;">New</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" style="  border: 0px;  " name="r9" id="female" value="4" <?php if ($rowa9['answerid']=="4") { echo "checked"; } ?>>
							   <label for="female" style="font-size: 13px;">Used</label>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq8['question']."</span></div>";
							}
							?>
							<input type="text" name="q10" id="q10" placeholder="<?php echo $rowq8['question']; ?>" class="input_text" value="<?php echo $rowa10['answer']; ?>" />
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq9['question']."</span></div>";
							}
							?>
							<input type="text" name="q11" id="q11" placeholder="<?php echo $rowq9['question']; ?>"  class="input_text" value="<?php echo $rowa11['answer']; ?>"/>
							
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
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq10['question']."</span></div>";
							}
							?>
							<input type="text" name="q12" id="name2" placeholder="<?php echo $rowq10['question']; ?>"  class="input_text" value="<?php echo $rowa12['answer']; ?>"/>
							
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

        						  <input type="radio" style="  border: 0px;" name="r13" id="male" value="5" <?php if ($rowa13['answerid']=="5") { echo "checked"; } ?>>
							   <label for="male" style="font-size: 13px;">New</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" style="  border: 0px;" name="r13" id="female" value="6" <?php if ($rowa13['answerid']=="6") { echo "checked"; } ?>>
							   <label for="female" style="font-size: 13px;">Used</label>
							
						</div>
					</div> 
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq11['question']."</span></div>";
							}
							?>
							<input type="text" name="q14" id="q14" placeholder="<?php echo $rowq11['question']; ?>"  class="input_text" value="<?php echo $rowa14['answer']; ?>"/>
							
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq12['question']."</span></div>";
							}
							?>
							<input type="text" name="q15" id="q15" placeholder="<?php echo $rowq12['question']; ?>"  class="input_text" value="<?php echo $rowa15['answer']; ?>"/>
							
						</div>
					</div>  
					<div class="form-group"> 
						<div class="col-sm-8 col-md-12">  
							<input type="submit" class="submit" name="submit" id="submit" value="Next" />
							
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


	
<?php get_footer(); ?>
