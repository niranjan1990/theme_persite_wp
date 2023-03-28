<?php /* Template Name: Ajax Products Widget Page */ 
	//Created by Siva on 11th Aug 2017
	
// Report all PHP errors
error_reporting(-1);



	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';
	header("Content-Type: application/json; charset=UTF-8");
	

if(isset($_GET['productid']))
{
	if(isset($_GET['rgb']))
	{
		$rgb = $_GET['rgb'];
	}
	else
	{
		$rgb = 0;
	}

	$PSite = explode(":", get_option( 'PSiteName' ) );

	
	$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  


	//$sql = "SELECT * from products where productid='".$_GET['productid']."'";
	$sql = "SELECT Product_Name,Total_Reviews,IF(ROUND(average_rating,1) = '0.0','0',ROUND(average_rating,1)) as avgrating from products where productid='".$_GET['productid']."'";
	
	$query=mysqli_query($con,$sql); 

	while($res1=mysqli_fetch_assoc($query))
	{

$link_sql = "SELECT p.url_safe_product_name,p.categoryid,p.manufacturerid,c.url_safe_category_name, ch.category_path,m.url_safe_manufacturer_name,m.Manufacturer_name FROM products p JOIN categories c ON p.categoryid = c.categoryid JOIN manufacturers m ON p.manufacturerid = m.manufacturerid JOIN channel_categories ch on ch.categoryid = c.categoryid WHERE p.productid = '".$_GET['productid']."'";
	$link_query=mysqli_query($con,$link_sql); 
	$link_res=mysqli_fetch_assoc($link_query);
//print_r($link_res);

$one  = explode('/',$link_res['category_path']);

//echo $res1['Average_Rating'];

//echo $ratePerc = ($res1['Average_Rating']/5)*100 ;

if(!empty($_GET['rgb']))
{
	$new_rgb = $_GET['rgb'];
}
else
{
	$new_rgb = "#096f00";
}



			if($DEEPCATEGORIES == '1')
		{
			$final_link = get_site_url()."/product/".$link_res['category_path']."/".$link_res['url_safe_manufacturer_name']."/".$link_res['url_safe_product_name'];
		}
		else
		{
			$final_link = get_site_url()."/".$one[0]."/".$link_res['url_safe_manufacturer_name']."/".$one[1]."/".$link_res['url_safe_product_name'];
		}






$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/Linux/',$agent)) $os = 'Linux';
elseif(preg_match('/Win/',$agent)) $os = 'Windows';
elseif(preg_match('/iPhone/',$agent)) $osm = 'iPhone';
elseif(preg_match('/iPad/',$agent)) $osm = 'iPad';
elseif(preg_match('/Mac/',$agent)) $os = 'Mac';
else $os = 'UnKnown';

if($os == "Mac" && $osm != "iPhone" && $osm != "iPad")
{ 
	$unicodestarsize = "170";
}
else
{ 
	$unicodestarsize = "146";
}





$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$_GET['productid']." ORDER BY `ReviewID` DESC limit 1 ");

$count = mysqli_num_rows($qry);
$res2=mysqli_fetch_assoc($qry);



														
		
$all .= '

<style>
.ratingapi .ratingapi-container {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  border-radius: 3px;
  -webkit-transform: rotateY(180deg);
          transform: rotateY(180deg);
}
.ratingapi .ratingapi-container .star {
  position: relative;
  margin-bottom: -4px;
}
.ratingapi .ratingapi-container .star > svg {
  width: 100%;
  fill: white;
  -webkit-transition: all 0.25s;
  transition: all 0.25s;
  z-index: 0;
}
.ratingapi .ratingapi-container .star label {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 1;
}
.ratingapi .ratingapi-container .star label svg {
  fill: #cccccc;
}
.ratingapi .ratingapi-container .star:hover label svg {
  fill: #ff0000;
  -webkit-transition: all 0.25s;
  transition: all 0.25s;
}
.ratingapi .ratingapi-container .star:hover ~ .star label svg {
  fill: #ff0000;
  -webkit-transition: all 0.25s;
  transition: all 0.25s;
}
.ratingapi .ratingapi-container input {
  position: absolute;
  visibility: hidden;
  margin: 0;
}
.ratingapi .ratingapi-container input ~ .star {
  -webkit-transition: all 0.25s;
  transition: all 0.25s;
}
.ratingapi .ratingapi-container input:checked ~ .star {
  -webkit-transition: all 0.25s;
  transition: all 0.25s;
}
.ratingapi .ratingapi-container input:checked ~ .star label svg {
  fill: #ff0000;
}
</style>
<style>
@media only screen and (min-width: 750px) 
{
	.mobile
	{
		display:none;
	}
	.product-score-outer
	{
	border-left: none !important;
		padding-left: 8px;
	}
	.product-score-box
	{
		width: 134px;
		padding: 9px 6px;
		background-color: '.$new_rgb.';
		color: #fff;
		border-radius: 10px;
		-moz-border-raidus: 10px;
		-webkit-border-radius: 10px;
		background-image: none;
	}
	.product-score-box p {
		margin-bottom: 0px!important;
		line-height: 1.5em;
	}
	.product-score-inner {
		background-color: #fff;
		color: #27604d;
		padding: 2px 0;
		border-radius: 5px;
	}
	.product-score-inner p {
		margin-bottom: 0px!important;
		line-height: 1.5em !important;
	}
	#overall-count
	{
	}
}
@media only screen and (max-width: 750px) 
{
	.desktop
	{
		display:none;
	}
	.product-score-outer
	{
		border-left: none !important;
		/* padding-left: 8px; */
	}
	.product-score-box
	{
		width: 134px;
		padding: 9px 10px;
		background-color: '.$new_rgb.';
		color: #fff;
		border-radius: 10px;
		-moz-border-raidus: 10px;
		-webkit-border-radius: 10px;
		background-image: none;
	}
	.product-score-box p {
		margin-bottom: 0px!important;
		line-height: 1.5em;
	}
	.product-score-inner {
		background-color: #fff;
		color: #27604d;
		/* padding: 10px 0; */
		border-radius: 10px;
	}
	.product-score-inner p {
		margin-bottom: 0px!important;
		line-height: 1.5em !important;
	}
	#overall-count
	{
	}
}

















@import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600,700);
.star-ratings-css {
	unicode-bidi: bidi-override;
    color: #c5c5c5;
    font-size: 34px;
    height: 20px;
    width: '.$unicodestarsize.'px;
    margin: 0 auto;
    position: relative;
    padding: 0;
    /* text-shadow: 0px 1px 0 #a2a2a2; */
}
.star-ratings-css-top {
  color: #FC0D1C;
  padding: 0;
  position: absolute;
  z-index: 1;
  display: block;
  top: 0;
  left: 0;
  overflow: hidden;
}
.star-ratings-css-bottom {
  padding: 0;
  display: block;
  z-index: 0;
}

</style>




<center>				
<div class="product-score-outer desktop" style="margin-top: 15px; width:300px">
	<div class="product-score-box" id="reviewAppOO7-site-navigation" style="width: 100%; height: 268px; padding: 0 10px 11px 10px; padding-top: 0px;">

	<div style="    float: right;    width: 100%;   /* margin-top: 28px;*/">
		<div style="color:#fff;padding-bottom: 0px; font-size: 16px;  font-weight: bold;   text-align: center;  margin-top: 8px;  margin-left: 0px;  font-family: arial;"><a style="color:#fff;" href="'.$final_link.'.html">'.$link_res['Manufacturer_name'].' '.$res1['Product_Name'].'</a></div>
		<div class="product-score-inner" style="    text-align: center;    width: 100%;    height: 82px;    margin-left: 0px;">';

		if($_COOKIE['bb_username'] && $count > 0)
		{
			$all .='<p style="font-weight: bold;    font-size: 15px;  color:red;  margin-top: 0px;text-align: center;">You Rated this : '.$res2['Overall_Rating'].'/5</p>';
			$all .= '<p style="margin-top: -2px;text-align:center;">
					<div class="star-ratings-css" style="  margin-top:-8px;     margin-bottom: 16px; height: 28px;">
					  <div class="star-ratings-css-top" style="width: '.$res2['Overall_Rating']/5*100 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					</div>
			</p>';
			$all .='<a href="'.$final_link.'-review.html?rating='.$res2['Overall_Rating'].'&reviewid='.$res2['ReviewID'].'"><p style="color:blue;margin-top: 12px; font-size: 12px;text-align: center;font-weight: bold;">Edit</p></a>';

		}		
		else
		{
			$all .='<p style="font-weight: bold;    font-size: 15px;  color:red;  margin-top: 0px;text-align: center;">SHARE YOUR OPINION</p>';
			$all .='
				<center>
				<div style="width:186px">
				<div class="ratingapi">
					<div class="ratingapi-container">
					  <input id="star-5" class="radio" name="star" type="radio" />
					  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=5\'">
						<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						<label for="star-5">
						  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						</label>
					  </div>
					  <input id="star-4" class="radio" name="star" type="radio" />
					  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=4\'">
						<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						<label for="star-4">
						  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						</label>
					  </div>
					  <input id="star-3" class="radio" name="star" type="radio" />
					  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=3\'">
						<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						<label for="star-3">
						  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						</label>
					  </div>
					  <input id="star-2" class="radio" name="star" type="radio" />
					  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=2\'">
						<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						<label for="star-2">
						  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						</label>
					  </div>
					  <input id="star-1" class="radio" name="star" type="radio" />
					  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=1\'">
						<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						<label for="star-1">
						  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
						</label>
					  </div>
					</div>
				  </div>
				</div>
				</center>';
			$all .='<p style="color:#ccc;margin-top: 12px;font-style: italic; font-size: 12px;text-align: center;font-weight: bold;">Own it? Rate it. Hover & click</p>';
			
		}
			
			
			
			
			
			
			
			
			
			
			$all .='
		</div>
	</div>
	<div class="product-score-box" id="reviewAppOO7-site-navigation" style="    width: 100%;    height: 89.5px;    float: right;    padding: 0px;">
		<style>.product-score-box p{margin-bottom:0px!important;}</style>
		<p style="padding-bottom: 0px;    font-size: 15px;    font-weight: bold;    /*margin-top: 5px; */   text-align: center;    font-family: arial;">User Reviews Score</p>
		<a style="text-decoration: none;" href="'.$final_link.'.html">
		<div class="product-score-inner" style="text-align: center;">
			<style>.product-score-inner p{margin-bottom:0px!important;}</style>
			<p style="margin-top: -2px;font-weight: bold; text-align:center;"><span style="font-size:1em;font-weight: bold;" id="overall-count">'.$res1['Total_Reviews'].'</span> USER REVIEWS</p>
			<p style="    font-size: 30px;   text-align:center; color: #333;    margin-top: -2px;    font-weight: bold;    line-height: 31px!important;" id="overall-average">'.$res1['avgrating'].'</p>
			<p style="margin-top: -2px;text-align:center;">
					<div class="star-ratings-css" style="  margin-top:-8px;  height: 28px;">
					  <div class="star-ratings-css-top" style="width: '.$res1['avgrating']/5*100 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					</div>
			</p>
			<p style="color:#333;margin-top: 14px;text-align:center;font-weight: bold;">OUT OF 5</p>
		</div>
		</a>
	</div>
	
	</div>
</div>
</center>




<center>
<div class="product-score-outer mobile" style="margin-top: 15px; width:300px">
	<div class="product-score-box" id="reviewAppOO7-site-navigation" style="width: 100%; height: 252px; padding: 0 10px 11px 10px; padding-top: 0px;">

	<div style="    float: right;    width: 100%;   /* margin-top: 28px;*/">
		<p style="color:#fff;padding-bottom: 0px; font-size: 16px;  font-weight: bold;   text-align: center;  margin-top: 8px;  margin-left: 0px;  font-family: arial;">'.$link_res['Manufacturer_name'].' '.$res1['Product_Name'].'</p>
		<div class="product-score-inner" style="    text-align: center;    width: 100%;    height: 82px;    margin-left: 0px;">
			<p style="font-weight: bold;    font-size: 15px;  color:red;  margin-top: 0px;text-align: center;">SHARE YOUR OPINION</p>

			<center>
			<div style="width:186px">
			<div class="ratingapi">
				<div class="ratingapi-container">
				  <input id="star-5" class="radio" name="star" type="radio" />
				  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=5\'">
					<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					<label for="star-5">
					  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					</label>
				  </div>
				  <input id="star-4" class="radio" name="star" type="radio" />
				  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=4\'">
					<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					<label for="star-4">
					  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					</label>
				  </div>
				  <input id="star-3" class="radio" name="star" type="radio" />
				  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=3\'">
					<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					<label for="star-3">
					  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					</label>
				  </div>
				  <input id="star-2" class="radio" name="star" type="radio" />
				  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=2\'">
					<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					<label for="star-2">
					  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					</label>
				  </div>
				  <input id="star-1" class="radio" name="star" type="radio" />
				  <div class="star" onclick="location.href=\''.$final_link.'-review.html?rating=1\'">
					<svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					<label for="star-1">
					  <svg viewBox="0 0 51 48"><path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/></svg>
					</label>
				  </div>
				</div>
			  </div>
			</div>
			</center>
			<p style="color:#ccc;margin-top: 0px;font-style: italic; font-size: 12px;text-align: center;font-weight: bold;">Own it? Rate it. Tap a star</p>

		</div>
	</div>
	<div class="product-score-box" id="reviewAppOO7-site-navigation" style="    width: 100%;     padding-top:3px; height: 89.5px;    float: right;    padding: 0px;">
		<style>.product-score-box p{margin-bottom:0px!important;}</style>
		<p style="padding-bottom: 2px;    padding-top: 3px;    font-size: 15px;    font-weight: bold;    /*margin-top: 5px; */   text-align: center;    font-family: arial;">User Reviews Score</p>
		<a style="text-decoration: none;" href="'.$final_link.'.html">
		<div class="product-score-inner" style="text-align: center;">
			<style>.product-score-inner p{margin-bottom:0px!important;}</style>
			<p style="margin-top: -2px;font-weight: bold; text-align:center;"><span style="font-size:1em;font-weight: bold;" id="overall-count">'.$res1['Total_Reviews'].'</span> REVIEWS</p>
			<p style="    font-size: 30px;   text-align:center; color: #333;    margin-top: -2px;    font-weight: bold;    line-height: 31px!important;" id="overall-average">'.$res1['avgrating'].'</p>
			<p style="margin-top: -2px;text-align:center;">
					<div class="star-ratings-css" style="  margin-top:3px;  height: 28px;">
					  <div class="star-ratings-css-top" style="width: '.$res1['avgrating']/5*100 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					</div>
			</p>
			<p style="color:#333;margin-top: -2px;text-align:center;font-weight: bold;">OUT OF 5</p>
		</div>
		</a>
	</div>
	
	</div>
</div>
</center>';
	}

	echo 'document.write('.json_encode($all).');';

	
	
}
else
{
	echo "Invalid Input";
}
?>
