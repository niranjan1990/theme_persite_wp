<?php /* Template Name: User Profile 1 Page */
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
  height: 650px; 
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
	width:100%;
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

 <div class='container col-md-6 col-sm-8 block'>
        
        <div class='block2  center'>
					<div class="form-header">
					<div class="col-sm-8 col-md-12">  
					<label style="width:100%;"><a name="skip" style="margin-right: 0px !important;float:right;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile-2.html<?php echo $reg; ?>';">Skip</a>
						<a name="Back" style="margin-left: 0px !important;float:left;margin:10px;display:block;text-align:center;color: #3b7adb;text-decoration: underline;font-size:13px; cursor: pointer;" id="skip" onclick="location.href='user-profile.html<?php echo $reg;?>';">Back</a>
					
					</label>
					</div>
					</div>
			 <label class="labelheading" style="line-height: 23px;"><?php echo get_option("MainTitleProfile2"); ?></label></br>
           <label class="labeltext"><?php echo get_option("SubTitleProfile2"); ?></label>
		   
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
			 if($_POST['gid3'] == 0)
			{ 
				$var3 = "NULL"; 
			}
			else 
			{ 
			 $var3 = $_POST['gid3'];
			 }
			 
			 
			 $check = mysqli_query($con, "select * from user_profile_answers where questionid=1 and userid = '".$_COOKIE['bb_userid']."'");			 
			 if ($check)
			  {
			   if(mysqli_num_rows($check) > 0)
				{
					$qry1 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid=NULL, answer='".$_POST['q1']."'  where userid='".$user_id."' and questionid=1"); 
					
					$qry2 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid1']."', answer='".$_POST['q2']."'  where userid='".$user_id."' and questionid=2"); 	
					
					$qry3 = mysqli_query($con,"update user_profile_answers set answerid=NULL, productid='".$_POST['gid2']."', answer='".$_POST['q3']."'  where userid='".$user_id."' and questionid=3"); 			   

						if($qry1 && $qry2 && $qry3)
						{
						if (isset($_GET['reg']))
						{
							$reg = "?reg=true";
						}
						header("location: /user-profile-2.html".$reg);
						}
					
				}
				 else
				 {
					$qry1 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','1',NULL,NULL,'".$_POST['q1']."')"); 
					
					$qry2 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','2',NULL,".$var1.",'".$_POST['q2']."')"); 	
					
					$qry3 = mysqli_query($con,"insert into user_profile_answers(userid,questionid,answerid,productid,answer)values('".$user_id."','3',NULL,".$var2.",'".$_POST['q3']."')"); 			   


						if($qry1 && $qry2 && $qry3)
						{
							if (isset($_GET['reg']))
							{
								$reg = "?reg=true";
							}
							header("location: /user-profile-2.html".$reg);
						}
					 
				 }
			  } 
		   }
		   
		   
		   
		   






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
	
	$a=mysql_query("SELECT * FROM profile_questions where qid like '%a%'");

	
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
		 
				<form action='' method='post' class="form-horizontal" role="form" align="center">
					<div class="form-header"> 
						<div class="col-sm-8 col-md-12">  
							<span id='successerrormessage'></span>
						</div>
					</div>

<style>
.ui-autocomplete
{
	text-align:left!important;
}
.ui-menu .ui-menu-item
{
	border-bottom: 1px solid #ccc;
	padding-top:5px;
	padding-bottom:5px;
}
.ui-corner-all
{
	border-bottom-right-radius: 0px;
	border-bottom-left-radius: 0px;
	border-top-right-radius: 0px;
    border-top-left-radius: 0px;
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
	}
}

</style>		
			<link href="/ajaxsearch/style/style.css" rel="stylesheet" type="text/css" />
	


<!-- Siva for form autocomplete -->		
<?php
$q1 = mysqli_query($con, "select * from user_profile_answers where questionid=1 and userid = '".$_COOKIE['bb_userid']."'");
$rowq1=mysqli_fetch_array($q1);
$q2 = mysqli_query($con, "select * from user_profile_answers where questionid=2 and userid = '".$_COOKIE['bb_userid']."'");
$rowq2=mysqli_fetch_array($q2);

$q3 = mysqli_query($con, "select * from user_profile_answers where questionid=3 and userid = '".$_COOKIE['bb_userid']."'");
$rowq3=mysqli_fetch_array($q3);

?>					

					
					
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							
							<input type="hidden" name="userid" value="<?php echo $_COOKIE['bb_userid']; ?>" >
							
						<select name="q1" id="q1" size="1"  class="input_text">
								<option value=""><?php echo $rowq4['question']; ?></option>
								
								<?php
									while($rowa=mysql_fetch_array($a))
									{
								?>
										<option <?php if($rowq1['answer']==$rowa['question']){ echo "selected=selected";} ?> value="<?php echo $rowa['question']; ?>"><?php echo $rowa['question']; ?></option>
								<?php
									}
								?>
							</select>	
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
				echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq5['question']."</span></div>";
							}
							?>
							<input type="text" name="q2" id="name1" placeholder="<?php echo $rowq5['question']; ?>"  value="<?php echo $rowq2['answer']; ?>" class="input_text"/>

								<script type="text/javascript">
								$(function() {
									
									//autocomplete
									$("#name1").autocomplete({
										source: "/autocompletetest/search2.php",
										minLength: 1
									});				

								});
								</script>
						</div>
					</div>
					<div class="form-group">
						<div id="input_container" class="col-sm-8 col-md-12">
							<?php
							if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false)
							{
								echo "<div id='input_container' class='col-sm-8 col-md-12'><span>".$rowq6['question']."</span></div>";
							}
							?>
							<input type="text" name="q3" id="name2" placeholder="<?php echo $rowq6['question']; ?>"  value="<?php echo $rowq3['answer']; ?>" class="input_text"/>

								<script type="text/javascript">
								$(function() {
									
									//autocomplete
									$("#name2").autocomplete({
										source: "/autocompletetest/search2.php",
										minLength: 1
									});				

								});
								</script>
							
						</div>
					</div>
					
					<div class="form-group" > 
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
