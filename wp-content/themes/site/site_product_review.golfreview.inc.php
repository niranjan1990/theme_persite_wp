<?php
ob_start();
session_start();
require_once(__DIR__.'/../../../wp-reviewconfig.php');
require_once(__DIR__.'/../../../wp-config.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'; ?>	
<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[1] ); ?>
<?php
global  $cat_id, $pagename;
$cat_id = Get_CategoryID_By_Path( $parts2[0] );
$pagename = "RVF";
?>	
	
<?php	
//print_r($_SESSION);

	/*******
	 * The template for displaying Site Review Product Review form. *
	 * @package WordPress
	 * @subpackage Site Review
	 * @since Site Review 1.0
	 */

	if($DEEPCATEGORIES == '1')
	{
		Get_ChannelID_By_Category_outdoor_review();
		site_review_product_review_page_outdoor();	
	}
	else
	{
		Get_ChannelID_By_Category();
		golf_site_review_product_review_page();
	}

	
		
	set_site_review_title($Product->category_name, $Product->manufacturer_name, 'Review '.$Product->product_name);
	get_header();
	
	// This to find the product type as Golf Course or Club	
	$channel_type = explode('/',$_SERVER['REQUEST_URI']);

?>
			


<style>
#PostReviewForm{
padding:10px;
background-color: #f0efda;
}
#registration{
	margin-top: 10px;
    font-family: Lucida Sans, sans-serif !important;
font-size:16px;
    background-color: #0090EA; 
color:#fff;
width:209px;
height:44px;
border-radius: 8px;

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
#purchaseleft{
float: left; 
   width: 30%;

}
#purchaseright{
    float: right;  
  width: 70%;

}

@media screen  and (max-width: 601px) {

#purchaseleft{
float: left; 

}
#purchaseright{
        width: 100%;
    float: right;
    margin-top: 9px;

}



}
</style>
<div id="content-left" class="col-sm-8">
	<div class="inner">
		<div id="write-review">
			
		<?php
		if($_GET['reviewid'])
		{
			error_reporting(0);
												
			$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
											
			$qry = mysqli_query($con, "SELECT overall_rating,pros,cons,year_purchased,product_type,price FROM `reviews` WHERE `reviewid`=".$_GET['reviewid']." ORDER BY `reviewid` DESC limit 1 ");
												
			$count = mysqli_num_rows($qry);
			$res1=mysqli_fetch_assoc($qry);
			
		}

		if(isset($_POST['submit']))
		{
			if($_POST['submit']=="Login")
			{
				echo "<script>alert('Login');</script>";
				$_SESSION['nonlogin'] = 'True';
				$_SESSION['pname']=$_POST['pname'];
				$newurl = explode("?",$_POST['purl']);
				if(isset($_COOKIE['bb_pre_url']))
                                {
                                        $cnewurl = $_COOKIE['bb_pre_url'];
                                }
                                else
                                {
                                        $cnewurl = $newurl[0];
                                }

                                $_SESSION['cnewurl']=$cnewurl;
				$_SESSION['prul']=$newurl[0];
				$_SESSION['pros']=$_POST['pros'];
				$_SESSION['cons']=$_POST['cons'];
				$_SESSION['year']=$_POST['year'];
				//$_SESSION['userid']=$_POST['bb_userid'];
				//$_SESSION['username']=$_POST['username'];
				$_SESSION['rating']=$_POST['rating'];
				$_SESSION['purchase']=$_POST['purchase'];
				$_SESSION['price']=$_POST['price'];
				$_SESSION['ProductID']=$_POST['ProductID'];
				$_SESSION['channelid']=$_POST['channelid'];
				$_SESSION['rurl']=$_POST['purl'];
				$actual_link = "http://$_SERVER[HTTP_HOST]";
				header('location: /user-login.html');
				
			}
			else if($_POST['submit']=="Register")
			{
				echo "<script>alert('register');</script>";
				$_SESSION['nonlogin'] = 'True';
				$_SESSION['pname']=$_POST['pname'];
				$newurl = explode("?",$_POST['purl']);
				if(isset($_COOKIE['bb_pre_url']))
                                {
                                        $cnewurl = $_COOKIE['bb_pre_url'];
                                }
                                else
                                {
                                        $cnewurl = $newurl[0];
                                }

                                $_SESSION['cnewurl']=$cnewurl;
				$_SESSION['prul']=$newurl[0];
				$_SESSION['pros']=$_POST['pros'];
				$_SESSION['cons']=$_POST['cons'];
				$_SESSION['year']=$_POST['year'];
				//$_SESSION['userid']=$_POST['bb_userid'];
				//$_SESSION['username']=$_POST['username'];
				$_SESSION['rating']=$_POST['rating'];
				$_SESSION['purchase']=$_POST['purchase'];
				$_SESSION['price']=$_POST['price'];
				$_SESSION['ProductID']=$_POST['ProductID'];
				$_SESSION['channelid']=$_POST['channelid'];
				$_SESSION['rurl']=$_POST['purl'];
				$actual_link = "http://$_SERVER[HTTP_HOST]";
				header('location: /user-registration.html');
			}
			else if($_POST['submit']=="Done")
			{
				if($_POST['reviewid'])
				{
					echo "Edit Review";
					// To get the last review
					//$oldreview = mysql_query("select * from reviews where productid=".$_POST['ProductID']." and vbulletinid=".$_COOKIE['bb_userid']."");	
					//$review_last = mysql_fetch_array($oldreview);
					
					
					$sqlinsert = mysql_query("UPDATE reviews SET `reviewerip` = '".$_SERVER['REMOTE_ADDR']."', `model` = '".$_POST['pname']."', `overall_rating` = '".$_POST['rating']."', `date_created` = '".date('Y-m-d H:i:s')."', `pros` = '".$_POST['pros']."', `cons` = '".$_POST['cons']."', `year_purchased` = '".$_POST['year']."', `price` = '".$_POST['price']."', `product_type` = '".$_POST['purchase']."' WHERE `reviews`.`reviewid` = '".$_POST['reviewid']."'");

										
					
					if($sqlinsert)
					{
						// To fetch the total reviews and average
						$result1 = mysql_query("Select avg(overall_rating) as average_review, count(overall_rating) as total_review from reviews where productid = ".$_POST['ProductID']."");
						$Review1 = mysql_fetch_array($result1);						
						//echo $Review1['average_review'];
						//echo $Review1['total_review'];
						
						
						$sqlupdate = mysql_query("UPDATE `products` SET `total_reviews` = '".$Review1['total_review']."', `average_rating` = '".$Review1['average_review']."' WHERE productid = '".$_POST['ProductID']."'");
						
						
						

						if($sqlupdate)
						{
							$newurl = explode("?",$_POST['purl']);
							if(isset($_COOKIE['bb_pre_url']))
                                                                        {
                                                                                $cnewurl = $_COOKIE['bb_pre_url'];
                                                                        }
                                                                        else
                                                                        {
                                                                                $cnewurl = $newurl[0];
                                                                        }

							header('location: '.$cnewurl);
						}
					}
				}
				else
				{
					
					$_SESSION['pname']=$_POST['pname'];
					$_SESSION['prul']=$_POST['purl'];
					$_SESSION['pros']=$_POST['pros'];
					$_SESSION['cons']=$_POST['cons'];
					$_SESSION['year']=$_POST['year'];
					$_SESSION['userid']=$_POST['bb_userid'];
					$_SESSION['username']=$_POST['username'];
					$_SESSION['rating']=$_POST['rating'];
					$_SESSION['purchase']=$_POST['purchase'];
					$_SESSION['price']=$_POST['price'];
					$_SESSION['ProductID']=$_POST['ProductID'];
					$_SESSION['channelid']=$_POST['channelid'];
					$_SESSION['rurl']=$_POST['purl'];
					$actual_link = "http://$_SERVER[HTTP_HOST]";
					
					
					if(isset($_COOKIE['bb_userid']))
					{

						
						$sqlinsert = mysql_query("INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model,channelid,value_rating,overall_rating,pros,cons,year_purchased,price,product_type,vbulletinid)
		VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['bb_username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."','','".$_SESSION['rating']."','".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['bb_userid']."')");
						
						if($sqlinsert)
						{
							// To fetch the total reviews and average
							$result1 = mysql_query("Select avg(overall_rating) as average_review, count(overall_rating) as total_review from reviews where productid = ".$_SESSION['ProductID']."");
							$Review1 = mysql_fetch_array($result1);						
							//echo $Review1['average_review'];
							//echo $Review1['total_review'];
							
							
							$sqlupdate = mysql_query("UPDATE `products` SET `total_reviews` = '".$Review1['total_review']."', `average_rating` = '".$Review1['average_review']."' WHERE productid = '".$_SESSION['ProductID']."'");
							
							
							

							if($sqlupdate)
							{
							$newurl = explode("?",$_POST['purl']);

							header('location: '.$newurl[0]);
										unset($_SESSION['pname']);
										unset($_SESSION['prul']);
										unset($_SESSION['Overall_Rating']);
										unset($_SESSION['ProductID']);
										unset($_SESSION['channelid']);
										unset($_SESSION['rurl']);
										unset($_SESSION['pros']);
										unset($_SESSION['cons']);
										unset($_SESSION['year']);
										unset($_SESSION['userid']);
										unset($_SESSION['username']);
										unset($_SESSION['rating']);
										unset($_SESSION['purchase']);
										unset($_SESSION['price']);
							}
						}
					
					}
				
				}
				
			}
			else
			{
				
			}
		} 




		
		if($sql)
		{
			header('location: '.$Product->product_url);
			//echo $Product->product_url;
		} 
		
		
					
				else{
			//$errMsg = '';
			//$succMsg = '';
			
		  ?>
		  
		  
<script>
function validateForm() {
    var xpros = document.forms["PostReviewForm"]["pros"].value;
    var xcons = document.forms["PostReviewForm"]["cons"].value;
	var re = /\S+\s+\S+\s+\S+/;
	if (re.test(xpros) && re.test(xcons) ) 
	{
		 return true;
    }
	else
	{
        alert('Please make sure Strength and Weakness section each have at least 3 words');
		return false;		
	}
}
</script>		  
		 
		<h1>You are writing a review for: <span><?php echo $Product->product_name; ?></span></h1>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/addreview.js"></script>

		<form method="post" id="PostReviewForm" action="" name="PostReviewForm" onsubmit="return validateForm();">
	
			<center>
				<div><b style="font-size:18px;color:black;">Your Rating:</b></div>










		<!--mobile stars--->



<style>
.rating-mobile {
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating-mobile:not(:checked) > input {
    position:absolute;
    top:-9999px;
    clip:rect(0,0,0,0);
}

.rating-mobile:not(:checked) > label {
		float : right;
    width:55px;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:55px;
    line-height:1.2;
    color:#a99494;
}

.rating-mobile:not(:checked) > label:before {
    content: '★ ';
}

.rating-mobile > input:checked ~ label {
    color: #f34325;
}

.rating-mobile:not(:checked) > label:hover,
.rating-mobile:not(:checked) > label:hover ~ label {
     color: #f34325;
}

.rating-mobile > input:checked + label:hover,
.rating-mobile > input:checked + label:hover ~ label,
.rating-mobile > input:checked ~ label:hover,
.rating-mobile > input:checked ~ label:hover ~ label,
.rating-mobile > label:hover ~ input:checked ~ label {
    color: #f34325;
}


</style>







				<div class="star-mobile" id="star-mobile" style="padding:5px;display:none;height: 80px;">
				<?php
if($_GET['rating']==1){
	$onestarcheck= "checked";
}else if($_GET['rating']==2){
	$twostarcheck= "checked";
}else if($_GET['rating']==3){
	$threestarcheck= "checked";
}else if($_GET['rating']==4){
	$fourstarcheck= "checked";
}else{
       $fivestarcheck= "checked";
}

		?>
		<ul id="star-rating" class="star-rating new1" style="background: none!important;width:100%;margin-top: -5px;">
			<div class="rating-mobile" style="    width: 275px;">
				<input type="radio" id="stars5" name="ratings" value="5" <?php echo $fivestarcheck; ?> ><label for="stars5"  class="starmobile five-star" >5</label>
				<input type="radio" id="stars4" name="ratings" value="4" <?php echo $fourstarcheck; ?> ><label for="stars4" class="starmobile four-star">4</label>
				<input type="radio" id="stars3" name="ratings" value="3" <?php echo $threestarcheck; ?> ><label for="stars3" class="starmobile three-star">3</label>
				<input type="radio" id="stars2" name="ratings" value="2" <?php echo $twostarcheck; ?> ><label for="stars2" class="starmobile two-star">2</label>
				<input type="radio" id="stars1" name="ratings" value="1" <?php echo $onestarcheck; ?> ><label for="stars1" class="starmobile one-star">1</label>
			</div>
		</ul>
				</div>





							<!--star-desktop-->
				<div class="star-desktop" id="star-desktop" style="padding:5px;">
<style>
.rating {
    float:left;
	}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating:not(:checked) > input {
    position:absolute;
    top:-9999px;
    clip:rect(0,0,0,0);
}

.rating:not(:checked) > label {
   float:right;
    width:32px;
    padding:0 .1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    line-height:1.2;
    color:#a99494;
}

.rating:not(:checked) > label:before {
    content: '★ ';
}

.rating > input:checked ~ label {
    color: #f34325;
}

.rating:not(:checked) > label:hover,
.rating:not(:checked) > label:hover ~ label {
     color: #f34325;
}

.rating > input:checked + label:hover,
.rating > input:checked + label:hover ~ label,
.rating > input:checked ~ label:hover,
.rating > input:checked ~ label:hover ~ label,
.rating > label:hover ~ input:checked ~ label {
    color: #f34325;
}

</style>
<?php

if($_GET['rating']==1){
	$onestarcheck= "checked";
}else if($_GET['rating']==2){
	$twostarcheck= "checked";
}else if($_GET['rating']==3){
	$threestarcheck= "checked";
}else if($_GET['rating']==4){
	$fourstarcheck= "checked";
}else{
       $fivestarcheck= "checked";
}

?>

<ul id="star-rating" class="star-rating new1" style="background: none!important;     width: 163px;">
			<div class="rating">
				<input type="radio" id="star5" name="rating" value="5" <?php echo $fivestarcheck; ?> ><label for="star5"  class="mari five-star" >5</label>
				<input type="radio" id="star4" name="rating" value="4" <?php echo $fourstarcheck; ?> ><label for="star4" class="mari four-star">4</label>
				<input type="radio" id="star3" name="rating" value="3" <?php echo $threestarcheck; ?> ><label for="star3" class="mari three-star">3</label>
				<input type="radio" id="star2" name="rating" value="2" <?php echo $twostarcheck; ?> ><label for="star2" class="mari two-star">2</label>
				<input type="radio" id="star1" name="rating" value="1" <?php echo $onestarcheck; ?> ><label for="star1" class="mari one-star">1</label>
			</div>
		</ul>

<script>
$(document).ready(function(){

	$('.starmobile').click(function(){
		var href = $(this).text();
		//var url = window.location.href;
		//var ans = url.split(".html");
		//var ans = ans.slice(0,-1)
		//var ans = ans +'-review.html?rating='+ href;
		//window.location = ans;
		//alert(href);
		if(href==1)
		{
			$("#one-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#two-star1").css({"background":"none"});
			$("#three-star1").css({"background":"none"});
			$("#four-star1").css({"background":"none"});
			$("#five-star1").css({"background":"none"});
			$("#rate-result1").text("1/5");
			$("#rating").val("1");
		}
		else if(href==2)
		{
			$("#one-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#two-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#three-star1").css({"background":"none"});
			$("#four-star1").css({"background":"none"});
			$("#five-star1").css({"background":"none"});
			$("#rate-result1").text("2/5");
			$("#rating").val("2");
			
		}
		else if(href==3)
		{
			$("#one-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#two-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#three-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#four-star1").css({"background":"none"});
			$("#five-star1").css({"background":"none"});
			$("#rate-result1").text("3/5");
			$("#rating").val("3");
			
		}
		else if(href==4)
		{
			$("#one-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#two-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#three-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#four-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#five-star1").css({"background":"none"});
			$("#rate-result1").text("4/5");
			$("#rating").val("4");
			
		}
		else
		{
			$("#one-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#two-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#three-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#four-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#five-star1").css({"background":"url('/wp-content/themes/site/images/star-rating-mobile.png') left bottom repeat-x"});
			$("#rate-result1").text("5/5");
			$("#rating").val("5");
			
		}
		
	});
	$('.mari').click(function(){
		var href = $(this).text();
		//var url = window.location.href;
		//var ans = url.split(".html");
		//var ans = ans.slice(0,-1)
		//var ans = ans +'-review.html?rating='+ href;
		//window.location = ans;
		//alert(href);
		if(href==1)
		{
			$("#one-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#two-star").css({"background":"none"});
			$("#three-star").css({"background":"none"});
			$("#four-star").css({"background":"none"});
			$("#five-star").css({"background":"none"});
			$("#rate-result1").text("1/5");
			$("#rating").val("1");
		}
		else if(href==2)
		{
			$("#one-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#two-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#three-star").css({"background":"none"});
			$("#four-star").css({"background":"none"});
			$("#five-star").css({"background":"none"});
			$("#rate-result1").text("2/5");
			$("#rating").val("2");
			
		}
		else if(href==3)
		{
			$("#one-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#two-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#three-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#four-star").css({"background":"none"});
			$("#five-star").css({"background":"none"});
			$("#rate-result1").text("3/5");
			$("#rating").val("3");
			
		}
		else if(href==4)
		{
			$("#one-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#two-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#three-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#four-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#five-star").css({"background":"none"});
			$("#rate-result1").text("4/5");
			$("#rating").val("4");
			
		}
		else
		{
			$("#one-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#two-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#three-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#four-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#five-star").css({"background":"url('/wp-content/themes/site/images/star-rating.png') left bottom repeat-x"});
			$("#rate-result1").text("5/5");
			$("#rating").val("5");
			
		}
		
	});

	$('.star').click(function(){
		var href = $(this).text();
		alert(href);
	
	});
	
	
});
</script>	



				</div>
				<div id="rate-result"><p id="rate-result1" style="color:red; font-size: 24px;   font-weight: bold;    font-family: helvetica; padding-top:5px;    margin-top: 22px;"><?php echo $_GET['rating']; ?>/5</p></div>
		
				<div class="onlymobile"><p style="margin-top:6px!important;color:black !important;">( Tap a star to change it )</p></div>
				<div class="onlydesktop"><p style="margin-top:6px!important;color:black !important;">( Hover and click on a star to change it )</p></div>
			</center>
			<div id="feedlabel">
			<label style="color: #4d4a4a;font-size: 19px;text-align:center;padding: 5px;">Please share some of the strengths and weaknesses:</label>
			</div>
			<input type="hidden" id="rating" name="rating" value="<?php if($_GET['reviewid']){ echo $res1['overall_rating'];}else{echo $_GET['rating'];} ?>">
			<?php
			if($_GET['reviewid'])
			{
				echo '<input type="hidden" name="reviewid" size="28" value="'.$_GET['reviewid'].'"/>';
			}
			else
			{
				
			}
				?>
			<input type="hidden" name="bb_userid" size="28" value="<?php echo $_COOKIE['bb_userid'];?>"/>
			<input type="hidden" name="username" size="28" value="<?php echo $_COOKIE['bb_username'];?>"/>
			<input type="hidden" name="purl" size="28" value="<?php echo $Product->product_url;?>"/>
			<input type="hidden" name="pname" size="28" value="<?php echo $Product->product_name; ?>"/>

			<div id="likefeed">
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>	
<?php
			if($channel_type[1]=="product")
			{
				echo "<label>Please tell our users... What are some of the strengths of ".$Product->product_name."?</label>";
			}
			else
			{
				echo "<label>Please tell our users... What are some of the strengths of ".$Product->product_name."?</label>";
			}
			?>

<?php } ?>

			<textarea style="    width: 100%;    height: 120px;" placeholder="<?php
			if($channel_type[1]=="product")
			{
				echo "Please tell our users... What are some of the strengths of  ".$Product->product_name."?";
			}
			else
			{
				echo "Please tell our users... What are some of the strengths of  ".$Product->product_name."?";
			}
			?>" name="pros"><?php if($_GET['reviewid']){ echo $res1['pros'];}else{} ?></textarea>
			</div>
			<div id="dislikefeed">
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>	
<?php
			if($channel_type[1]=="golf-courses")
			{
				echo "<label>Also, please detail any weaknesses of ".$Product->product_name."? </label>";
			}
			else
			{
				echo "<label>Also, please detail any weaknesses of ".$Product->product_name."? </label>";
			}
			?>

<?php } ?>

			<textarea style="    width: 100%;    height: 120px;" placeholder="<?php
			if($channel_type[1]=="product")
			{
				echo "Also, please detail any weaknesses of ".$Product->product_name."?";
			}
			else
			{
				echo "Also, please detail any weaknesses of ".$Product->product_name."?";
			}
			?>" name="cons"><?php if($_GET['reviewid']){ echo $res1['cons'];}else{} ?></textarea>
			</div>
			
			
			
<?php
if($channel_type[1]=="golf-courses")
{
	
}
else
{
?>			
			
			
			
			<div id="yearpurchase" style="height:20px;">
			<div id="purchaseleft" style="">
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>	
<label>Model Year?</label>
<?php } ?>
			<input type="tel" pattern="\d{4}" maxlength="4" name="year" value="<?php if($_GET['reviewid']){ echo $res1['year_purchased'];}else{} ?>" placeholder="Model Year?" >
			</div>
			
			<div id="purchaseright" style="">
			<label style="color:black;">Purchased: &nbsp;&nbsp;</label>
			
			<input type="radio" style="  border: 0px; " <?php if($res1['product_type']=='New'){echo 'checked="checked"';} ?> name="purchase" id="new" value="New">
							   <label for="new" style="font-size: 13px;">New</label>&nbsp;&nbsp;&nbsp;&nbsp;
        						  <input type="radio" <?php if($res1['product_type']=='Old' || $res1['product_type']=='Used' ){echo 'checked="checked"';} ?> style="  border: 0px;   " name="purchase" id="old" value="Used">

							   <label for="old" style="font-size: 13px;">Used</label>

			</div>
			</div>
			
			
			
<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>	
<div id="price" style="margin-top: 28px;float:left">
<label>Price Paid?</label></br>
<?php }else{ ?>
<div id="price" style="margin-top: 6px;float:left">
<?php } ?>

			<input type="tel"  placeholder="Price Paid?" name="price" value="<?php if($_GET['reviewid']){ echo $res1['price'];}else{} ?>">
			</div>
			
<?php
}
?>			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
<?php 
if(!isset($_COOKIE['bb_username']))
{
?>
			<div id="feedlabel" style="margin-top:15px;">
			<label style="font-size:16px !important;    color: black;    ">That's it! To publish your review,&nbsp;please:</label>
			</div>
			<center id="button">
			<input type="submit" class="submit" name="submit" id="submit" value="Login"/>
			
			<input type="submit" name="submit" id="registration" value="Register" />
			</center>
<?php 
}
else
{	
?>	
			</br><center>
			<div id="feedlabel" style="margin-top:15px;">
			<label style="font-size:16px !important;    color: black;    line-height: 1.4rem;    ">Thanks <?php echo $_COOKIE['bb_username']; ?>, <br>Click Done to publish your review</label>	
			</div>
			<div id="button">
			<input type="submit" class="submit" name="submit" id="submit" value="Done"/>
			</div>
			</center>
<?php 
}
?>

















			<!--<table cellspacing="0">
				<tr><td colspan="2"><span>(required fields are green)</span></td></tr>
				<tr><td colspan="2"><h3>1. Review info</h3></td></tr>
				<input type="hidden" name="user_name" size="28" value="<?php echo $_COOKIE['bb_userid'];?>"/>
				<input type="hidden" name="user_email" size="28" value="<?php echo $Product->product_url;?>"/>
				<tr>
					<td>&nbsp;</td>
					<td><input type="checkbox" name="newsletter" /> Sign up for the monthly newsletter</td>
				</tr>				<tr>
					<td class="lft" colspan="2">What level of play best describes you?
						<select name="reviewer_experience" size="1" required>
							<option value="">Please Select&gt;&gt;</option>
							<option value="Scratch Golfer">Scratch Golfer</option>
							<option value="Shoots in the 70s">Shoots in the 70s</option>
							<option value="Shoots in the 80s">Shoots in the 80s</option>
							<option value="Shoots in the 90s">Shoots in the 90s</option>
							<option value="Shoots in the 100s">Shoots in the 100s</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2"><h3>2. <?php echo $ReviewPage->Title_2; ?></h3></td></tr>
				<tr>
					<td class="lft"><?php echo $ReviewPage->Reviewed; ?></td>
					<td><input type="text" name="model_reviewed" size="28" value="<?php echo htmlspecialchars($Product->product_name); ?>" /></td>
				</tr>
				<tr>
					<td class="lft" valign="top">Review Summary:</td>
					<td><span>(4000 character limit)</span><br />
						<textarea style="width:100%;"  name="Summary" onKeyDown="javascript:MaxLength('Summary', 4000)" cols="42" rows="6"></textarea>
					</td>
				</tr>
				<tr>
					
					<td class="lft" valign="top"><span>Customer Service:</span></td>
					<td>
						<span>(1200 character limit)</span><br />
						<textarea style="width:100%;" name="Customer_Service" onKeyDown="javascript:MaxLength('Customer_Service', 1200)" cols="42" rows="3"></textarea>
					</td>
				</tr>
				<tr>
					<td class="lft" valign="top"><span><?php echo $ReviewPage->Similar; ?></span></td>
					<td>
						<span>(1200 character limit)</span><br />
						<textarea style="width:100%;" name="Similar_Products" onKeyDown="javascript:MaxLength('Similar_Products', 1200)" cols="42" rows="3"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2"><h3>3. <?php echo $ReviewPage->Title_3; ?></h3></td>
				</tr>
				<tr>
					<td class="lft">Value Rating:</td>
					<td><select name="Value_Rating" required >
						<option value="">Please Select</option>
						<option value="">Please Select</option>
						<option value="1" <?php if($_GET['rating']==1) echo 'selected="selected"'; ?> >1 Star - lowest</option>
						<option value="2" <?php if($_GET['rating']==2) echo 'selected="selected"'; ?> >2 Stars</option>
						<option value="3" <?php if($_GET['rating']==3) echo 'selected="selected"'; ?> >3 Stars</option>
						<option value="4" <?php if($_GET['rating']==4) echo 'selected="selected"'; ?> >4 Stars</option>
						<option value="5" <?php if($_GET['rating']==5) echo 'selected="selected"'; ?> >5 Stars - highest</option>
					</select></td>
				</tr>
				<tr>
					<td class="lft">Overall Rating:</td>
					<td><select name="Overall_Rating" required>
						<option value="">Please Select</option>
						<option value="1" <?php if($_GET['rating']==1) echo 'selected="selected"'; ?> >1 Star - lowest</option>
						<option value="2" <?php if($_GET['rating']==2) echo 'selected="selected"'; ?> >2 Stars</option>
						<option value="3" <?php if($_GET['rating']==3) echo 'selected="selected"'; ?> >3 Stars</option>
						<option value="4" <?php if($_GET['rating']==4) echo 'selected="selected"'; ?> >4 Stars</option>
						<option value="5" <?php if($_GET['rating']==5) echo 'selected="selected"'; ?> >5 Stars - highest</option>
					</select></td>
				</tr>
				<tr><td colspan="2"><h3>4. Validation</h3></td></tr>

				<tr>
					<td class="lft" colspan="2">Enter Security Code:					<style>iframe{width:100% ! important;}</style><div class="g-recaptcha" data-sitekey="<?php echo SITEKEY?>" ></div></td>
				</tr>
				<tr>
					<td class="lft" colspan="2">
						<input type="reset" onclick="javascript:document.PostReviewForm.reset();return false;" class="rst" value="RESET" /> &nbsp;
						<input type="submit" name="submit"  class="sbmt" data-sitekey="<?php echo SITEKEY?>" value="POST REVIEW" />
					</td>
				</tr>
				
			</table>-->


			<input type="hidden" id="sess_captcha_code" name="sess_captcha_code" value="<?php echo $_SESSION['captcha_id']; ?>" />
			<input type="hidden" name="ProductID" value="<?php echo $Product->productid; ?>" />
			<input type="hidden" name="channelid" value="<?php echo $Product->channelid; ?>" />	
			<input type="hidden" name="pagefrom" value="review" />			
		</form>		
		<?php } ?>
		</div>
	</div><!-- #content -->
</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
