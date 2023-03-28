<?Php /* Template Name: Location Page */ ?>
<?php
ob_start();
session_start();
error_reporting(0);
require_once(__DIR__.'/../../../wp-config.php');
require_once(__DIR__.'/../../../wp-reviewconfig.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);
require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';

global  $pagename;
$pagename = "TRAILS-COURSES";

$con5 = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

$fieldName = ($SITE_NAME=="golfreview") ? "Overall_Rating" : "Overall_Rating";
$fieldName1 = ($SITE_NAME=="golfreview") ? "ReviewID" : "ReviewID";

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


if(isset($_SESSION['nonlogin']))
	{
		//echo "Entered 1";
				if(isset($_COOKIE['bb_userid'])){

                                        if(($SITE_NAME=="golfreview"))
                                    $sqlinsert = mysqli_query($con5, "INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model, channelid, valid, overall_rating, pros, cons, year_purchased, price, product_type, vbulletinid)	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['bb_username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."',1,'".$_SESSION['rating']."','".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['bb_userid']."')");
                                        else
					//echo "Entered 2";
					$sqlinsert = mysqli_query($con5, "INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model, channelid, valid, overall_rating, pros, cons, year_purchased, price, product_type, vbulletinid)	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['bb_username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."',1,'".$_SESSION['rating']."','".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['bb_userid']."')");

					if($sqlinsert)
					{
						// To fetch the total reviews and average
						$result2 = mysqli_query($con5,"Select avg(overall_rating) as average_review, count(overall_rating) as total_review from reviews where productid = ".$_SESSION['ProductID']."");
						$Review2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
						//echo $Review2['average_review'];
						//echo $Review2['total_review'];


						$sqlupdate = mysqli_query($con5, "UPDATE `products` SET `total_reviews` = '".$Review2['total_review']."', `average_rating` = '".$Review2['average_review']."' WHERE productid = '".$_SESSION['ProductID']."'");



						if($sqlupdate)
						{
									$rurl = $_SESSION['rurl'];
									unset($_SESSION['pname']);
									unset($_SESSION['prul']);
									unset($_SESSION[$fieldName]);
									unset($_SESSION['ProductID']);
									unset($_SESSION['channelid']);
									unset($_SESSION['rurl']);
									unset($_SESSION['pros']);
									unset($_SESSION['cons']);
									unset($_SESSION['year']);
									unset($_SESSION['userid']);
									unset($_SESSION['bb_username']);
									unset($_SESSION['rating']);
									unset($_SESSION['purchase']);
									unset($_SESSION['price']);
									unset($_SESSION['nonlogin']);

									$newurl = explode('?', $rurl);
									header('location: '.$newurl[0]);
									//header('location: www.google.com');
						}
					}
				}

	}
	else
	{
		//echo "Entered 3";
	}

    if($SITE_NAME=="golfreview") {
        site_review_location();
        $meta_desc = "";
        site_review_location_type();
    }
    else {
        mtbr_site_review_location();
        $meta_desc = "";
        mtbr_site_review_location_type();
    }

	if(is_numeric($Course->articleid)) {

		query_posts("p=$Course->articleid");

		if( have_posts() ) : while ( have_posts() ) : the_post();

			$meta_desc = substr(get_the_content(), 0, 80);

		endwhile; endif;

		wp_reset_query();
	}
	else if($Course->product_description) {

		$meta_desc = substr($Course->product_description, 0, 80);
	}
	else if($Reviews) {

		$meta_desc = substr($Reviews[0]->summary, 0, 80);
	}

	set_site_review_location('course-page');
	set_site_review_meta_vars($Course->product_name, $Course->city, $Course->state, $Course->total_rating, $meta_desc);
	?>

<?php
//$newp = explode('.html',$parts1[3]);
// For seo
global $seo_title, $seo_description, $seo_keywords;

$seo_title_temp = str_replace("[state_name]",str_replace('-',' ',$Course->state),$LOCATIONTITLE);
$seo_description_temp = str_replace("[state_name]",str_replace('-',' ',$Course->state),$LOCATIONDESCRIPTION);
$seo_keywords_temp = str_replace("[state_name]",str_replace('-',' ',$Course->state),$LOCATIONKEYWORDS);

$seo_title_temp2 = str_replace("[city_name]",str_replace('-',' ',$Course->city),$seo_title_temp);
$seo_description_temp2 = str_replace("[city_name]",str_replace('-',' ',$Course->city),$seo_description_temp);
$seo_keywords_temp2 = str_replace("[city_name]",str_replace('-',' ',$Course->city),$seo_keywords_temp);

$seo_title_temp3 = str_replace("[product_name]",str_replace('-',' ',$Course->product_name),$seo_title_temp2);
$seo_description_temp3 = str_replace("[product_name]",str_replace('-',' ',$Course->product_name),$seo_description_temp2);
$seo_keywords_temp3 = str_replace("[product_name]",str_replace('-',' ',$Course->product_name),$seo_keywords_temp2);

$seo_title_temp4 = str_replace("[review_score]",str_replace('-',' ',$Course->total_rating),$seo_title_temp3);
$seo_description_temp4 = str_replace("[review_score]",str_replace('-',' ',$Course->total_rating),$seo_description_temp3);
$seo_keywords_temp4 = str_replace("[review_score]",str_replace('-',' ',$Course->total_rating),$seo_keywords_temp3);

$seo_title_temp5 = str_replace("[review_count]",str_replace('-',' ',$Course->total_reviews),$seo_title_temp4);
$seo_description_temp5 = str_replace("[review_count]",str_replace('-',' ',$Course->total_reviews),$seo_description_temp4);
$seo_keywords_temp5 = str_replace("[review_count]",str_replace('-',' ',$Course->total_reviews),$seo_keywords_temp4);

$seo_title = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_title_temp5);
$seo_description = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_description_temp5);
$seo_keywords = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_keywords_temp5);
?>

<?php
global  $pagenamejquery;
$pagenamejquery = "RVF";
?>
	<?php
	get_header();

	function full_url() {

		$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : " http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ;
		return $url;
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
	$unicodestarcenter = "17px;";
	$unicodestarsize = "30px";
	$hoverstarsize="30px";

}else if($os == "Linux")
{	$unicodestarcenter = "24px;";
	$unicodestarsize = "35px";
	$hoverstarsize="30px";
}
else
{
	$unicodestarcenter = "26px;";
/*2nd star*/
	$unicodestarsize = "35px";
	$hoverstarsize="37px";
}

?>

	<script>
		function showRatingDetail() {

			document.getElementById('product-rating-detail').style.display="block";
			return false;
		}

		function hideRatingDetail() {

			document.getElementById('product-rating-detail').style.display="none";
			return false;
		}

		function showThumb(el,img) {

			var all = el.parentNode.parentNode;
			var allt = all.getElementsByTagName('img');
			for(var i=0;i<allt.length;i++) {

				allt[i].style.boxShadow="none";
			}
			var t = el.getElementsByTagName('img');

			t[0].style.boxShadow="0 0 4px #444";
			var m = document.getElementById('product-image');
			m.setAttribute('src','<?php echo GR_CIMG . 'medium/'; ?>' + img);
			return false;
		}

		function slidePage(t) {

			var item_list = document.getElementById('image-thumbs');
			var div_list = item_list.getElementsByTagName('div');
			var xdest = t * 70 - 210;
			if(xdest < 0) xdest = 0;
			var xpos = getRightPos(item_list);
			var distance = Math.abs(xpos - xdest);
			var speed = distance/5;
			if(speed < 1) speed = 1;
			if(item_list.movement) clearTimeout(item_list.movement);
			if(xpos == xdest) {

				return false;
			}

			if(xpos < xdest) xpos += speed;
			else xpos -= speed;
			//alert(xdest + ' ' + xpos);
			setRightPos(item_list, xpos);
			progress = "slidePage('" + t + "')";
			item_list.movement = setTimeout(progress, 5);
			return false;
		}

		function setRightPos(el,p) {

			el.style.right = p+"px";
		}

		function getRightPos(el) {

			return parseInt(el.style.right);
		}

		window.onload = function() {

			var div_container = document.getElementById('image-thumbs').getElementsByTagName('div');
			for( var i = 0; i < div_container.length; i++ ) {

				div_container[i].dest = i * 70;
			}

			document.getElementById('image-thumbs').style.right = "0px";
		}
	</script>
<script>
$(document).ready(function(){

	$("input[type='radio']").click(function(){
		var href = $(this).val();
		var url = window.location.href;
		//alert(url);
		var ans = url.split(".html");
		var ans = ans.slice(0,-1);
		var ans = ans +'-review.html?rating='+ href;
		window.location = ans;

	});


	$(".mari").mouseover(function(){
		var href = $(this).text();
		//alert(href);
		//$("#rating").val(href);
		$("#rating").text(href+"/5");

	});
	$(".mari").mouseout(function(){
		var href = $(this).text();
		//alert(href);
		//$("#rating").val(href);
		$("#rating").text("?/5");

	});

});
</script>
	<style>
#___plusone_0 iframe
{
width: 69px!important;
}
.star-ratings-css-listing{
font-size:23px;
}

	#tableB { display: none; }
	#scoredetails { display: none; }

	@media only screen and (max-width: 600px) {
		.star-ratings-css-listing{
			font-size:34px;
			}
		#productstar{padding:5px !important;}
			#product-review .item-box li {   line-height:normal;}
			#product-review .item-box p {   line-height:normal;}
			.user-reviews-header h2{text-align:center;}
			.reviews-nav{width:100%;}
			.reviews-nav{width:100%;}
<?php if($_GET['p'] >= 2){ ?>
		        .reviews-nav .label{    margin-top: 10px;}
<?php }else{ ?>
 .reviews-nav .label{    margin-top: 2px;}
<?php } ?>
		#___plusone_0{ width:100% !important; }  /* For mobile phones: */
		.scoredetails { width:100%!important; }
		.leftscore { width:100%!important; }
		/*1.rightscore { width:20%!important; }*/
		.scoreleft { margin-right: 0!important; }
		.scoreright { padding-left: 0!important; margin:8px!important; }
		.scoredetailsinnerleft1 { font-size: 23px!important; }
		.scoredetailsinnerleft2 { font-size: 11px!important; margin-bottom: 10px! important; }
		.scoredetailsinnerleft3 { font-size: 11px!important; line-height:1px!important; }
		.scoredetailsinnerleft4 { font-size: 11px!important; }
		.scoredetailsinnerleft5 { font-size: 11px!important; }
		.scoredetailsinnerleft6 { font-size: 11px!important; }
		.scoredetailsleft { width:25%!important; }
		.scoredetailsleftinner { /*margin:6px;*/ width: 27%!important; }
	}
	</style>

	<div id="content-left" class="col-sm-8">
	<div class="inner" style="padding:5px;">
	<div id="product-review">
		<?php if($Course) { ?>



<!-- structured markup -->

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Product",
<?php  if($Course->total_rating_count == 0)
{

}
else
{
		echo ' "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "'.$Course->total_rating.'",
	"ratingCount": "'.$Course->total_rating_count.'",
    "reviewCount": "'.$Course->total_rating_count.'"
  },';
}

  ?>
  "description": "<?php
 $find = array('"','<li>','</li>','<ul>','</ul>','<br>','</br>','<br />');
$replace = array('','','','','','','','');
echo str_replace($find,$replace,$Course->product_description);
 ?>",
  "name": "<?php echo $Course->manufacturer_name; ?> <?php echo $Course->product_name; ?> <?php echo $Course->category_name; ?>",
  "image": "<?php echo ($Course->media) ? cr_product_image($Course->media[0]->value,'300x225') : cr_product_image($Course->product_image, 'product'); ?>"


 <?php  if($Course->total_rating_count == 0)
{

}
else
{
	// For lower rating and top rating
	$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

	$lowq = mysqli_query($con, "SELECT MIN(NULLIF(Overall_Rating, 0)) as lowrating FROM reviews where ProductID=".$Course->productid."");

	$ratlow=mysqli_fetch_assoc($lowq);
	$lowrating = $ratlow['lowrating'];

	$maxq = mysqli_query($con, "SELECT MAX(NULLIF(Overall_Rating, 0)) as maxrating FROM reviews where ProductID=".$Course->productid."");

	$ratmax=mysqli_fetch_assoc($maxq);
	$maxrating = $ratmax['maxrating'];

	$reviewsq = mysqli_query($con, "select Overall_Rating,User_Screen_Name,Date_Created,Summary,VbulletinID,pros,cons from reviews where ProductID=".$Course->productid." order by Overall_Rating desc limit 2");






		echo ' ,
  "review": [';
	$i=1;
	while($reviews=mysqli_fetch_assoc($reviewsq))
	{


	  echo '
		{
		  "@type": "Review",
		  "author": "'.$SITE_NAME.'",
		  "datePublished": "';
		  $date = explode(' ',$reviews['Date_Created']);
		  echo $date[0].'",
		  "description": "';

  $find = array('"','<li>','</li>','<ul>','</ul>','<br>','</br>','<br />');
$replace = array('','','','','','','','');
echo str_replace($find,$replace,$reviews['Summary']);
		  echo '",
		  "name": "'.$reviews['User_Screen_Name'].'",
		  "reviewRating": {
			"@type": "Rating",
			"bestRating": "'.$maxrating.'",
			"ratingValue": "'.$reviews['Overall_Rating'].'",
			"worstRating": "'.$lowrating.'"
		  }
		}';
		$countrev = mysqli_num_rows($reviewsq);

		if($countrev<2)
		{
			echo '';
		}
		else
		{
			if($countrev == $i)
			{
				echo '';
			}
			else
			{
				echo ',';

			}
		}
	$i = $i+1;
	}
	echo '
  ]';
}
?>
}
</script>


<!-- structured markup -->





		<div class="title"><h1><?php echo $Course->product_name; ?> - <?php if($CourseType['course_type_name']=="trails"){echo "Trail";}else{ echo ucfirst($CourseType['course_type_name']);}?>, <?php echo $Course->city; ?>, <?php $state = explode('-', $Course->state); if(count($state)==1){echo ucfirst($state[0]);}else if(count($state)==2){if(in_array('other', $state)){echo ucfirst($state[0]);}else{echo ucfirst($state[1]);}}else if(count($state)==3){echo ucfirst($state[1])."&nbsp;"; echo ucfirst($state[2]);}else if(count($state)==4){if(in_array('other', $state)){ echo ucfirst($state[0])."&nbsp;";echo ucfirst($state[1])."&nbsp;";echo ucfirst($state[2]); }else if(in_array('pacific', $state)){
echo ucfirst($state[3]);}else{echo ucfirst($state[1])."&nbsp;"; echo ucfirst($state[2])."&nbsp;".ucfirst($state[3]);}} ?></h1></div>

	<!--------------PRODUCT Over View - Mobile Changes -------------------------------->

	<div class="product-overview-mobile header_sub_menu_sample" id="product-overview-mobile" style="display:none;">


			     <div class="rightscore" style="float:left;margin-top: 15px;margin-left: 18px;">

                     <!--<div class="product-share meta clearfix" style="width:100%;height: 32px;">
                        <div class="pp" style="width:82px;float:left;margin-left:0px !important;">Do you like this course?</div>
                        <div class="sbg f" style="width:74px;float:left"><iframe id="framefb" width="1000px" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no"  src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Course); ?>&amp;send=false&amp;layout=button_count&amp;amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border: none; visibility: visible; width: 65px; height: 20px;"></iframe>
                        </div>
                        <div class="sbg" style="width:69px;float:left;    margin-left: -10px;"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php cr_product_url($Course); ?>" data-count="horizontal">Tweet</a>
                            <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                        </div>
                        <div class="sbg l" style="width:69px;float:left"><div class="g-plusone" data-size="standard" data-width="100" data-count="true" data-href="<?php cr_product_url($Course); ?>"></div></div>
                    </div>-->

                </div>


				<div id="score" class="score"  style="width:100%; padding:10px; ">
					<div style="width:100% ! important;">

						<div class="cam1 leftscore" id="reviewAppOO7-site-navigation" style="margin-bottom: 10px;width:100%; float:left; background-color:#096f00; height:101px; border-radius:10px;">
							<div style="width:100% ! important;">
								<div class="scoretop" style="width:100%; color:white; padding-top:9px; font-size:24px;font-weight:bold; text-align:center;"><?php if( $Course->total_rating_count == 0){
echo "NO REVIEWS YET";
}else{
echo $Course->total_rating_count." REVIEWS"; }?></div>

								<div class="scoreleft" style=" border-radius: 10px;height: 53px; width: 91%; background-color: #fff; color: #27604d; margin: 10px;">
									<div class="scoreinnerleft" style="width:50%; padding:10px;float:left;">

<?php $ratePerc = ($Course->total_rating/5)*100 ?>
<div class="star-ratings-css-listing" style="line-height: 32px;     margin-bottom: 5px;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
										</br>
									</div>
									<div class="scoreinnerright" style="width:50%; padding:10px;float:left;text-align:right !important;">
										<style> .scoreinnerright p { margin-bottom:0px!important; }</style>
										<p style="display: inline;font-size:50px;line-height:33px;color:black;font-weight:bold;" id="overall-average"><?php if($Course->total_rating_count == 0){ echo "--";}else{ echo $Course->total_rating; } ?></p><p style="display: inline;font-size:24px;line-height:24px;color:red;">/5</p>
									</div>
								</div>
							</div>
						</div>
						<div class="cam2 leftscore" style="width:100%; float:left; background-color:#096f00; height:120px; border-radius:10px;display:none;">
							<div >
								<div class="scoretop" style="width:100%; color:white; padding-top:9px; font-size:15px;font-weight:bold; text-align:center;">REVIEW DETAILS</div>
								<div class="scoreleft" style=" border-radius: 10px;height: 53px; width: 91%; background-color: #fff; color: #27604d; margin: 10px;">
									<div class="scoreinnerleft" style="width:30%; padding:5px;float:left;">
										<div class="golfreviewinner" style="">
											<p class="scoredetailsinnerleft2" style="text-align:center;font-size: 15px;    color: black;    font-weight: bold;">REVIEWS</p>
										</div>
										<div class="rating1" style="height: 25px;">
											<p style="text-align:center;font-size:26px;color:black;"> <?php echo number_format($Course->average_rating,1); ?> </p>
										</div>
									</div>
									<div class="seprator" style="width:5%;float:left;background-color: rgb(9, 111, 0);height:53px;"></div>
									<div class="scoreinnerleft" style="width:30%; padding:5px;float:left;">
										<div class="golfreviewinner" style="">
											<p class="scoredetailsinnerleft2" style="text-align:center;font-size: 15px;    color: black;    font-weight: bold;">QIKRATE</p>
										</div>
										<div class="rating1" style="height: 25px;">
											<p style="text-align:center;font-size:26px;color:black;"><?php echo number_format($Course->quickrating_average,1); ?></p>
										</div>
									</div>
									<div class="seprator" style="width:5%;background-color: rgb(9, 111, 0);float:left;height:53px;"></div>

									<div class="scoreinnerleft" style="width:30%; padding:5px;float:left;">
										<div class="golfreviewinner" style="">
											<p class="scoredetailsinnerleft2" style="text-align:center;font-size: 15px;    color: black;    font-weight: bold;">WEB</p>
										</div>
										<div class="rating1" style="height: 25px;    ">
											<p style="text-align:center;font-size:26px;color:black;"><?php echo number_format($Course->web_score_rating,1); ?> </p>
										</div>
									</div>
								</div>
								<p align="center"><img class="" style="" src="<?php bloginfo('template_url'); ?>/images/invertedbutton.png"></p>
							</div>
						</div>
					</div>
				</div>






		<!-------------- SCORE -------------------------------->


		<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
		<center>
		<table cellspacing="0" style="width:100%">

			<tr><td  colspan="2" style="width:100%;">

					<div style='width:100%;/*height:270px;*/ border:1px solid #b5b9a7;'>
						<?php
						if($SITE_NAME == 'mtbr')
						{
							if(function_exists('mtbr_cr_site_location_details_mobile'))
							{
								mtbr_cr_site_location_details_mobile_with_map('100%','270');
							}

						}
						else
						{
							if(function_exists('cr_site_location_details_mobile'))
							{
								cr_site_location_details_mobile_with_map('100%','270');
							}

						}

						?>

					</div>

			</td></tr>
					<!--<tr><td colspan="2" style="width:100%;"><center><div style="margin-top: 5px;"><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php
												error_reporting(0);

												$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

												$qry1 = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Course->productid." ORDER BY `ReviewID` DESC limit 1 ");


												$count1 = mysqli_num_rows($qry1);
												$res1=mysqli_fetch_assoc($qry1);

													if($_COOKIE['bb_username'])
													{
										echo " <br>You rated this:<br />";

													}
													else
													{
														echo '<p style="font-size: 14px;font-weight:bold;"> Ridden this?</p><p style="font-weight:100 !important;">(Tap to rate)</p>';
													}

											?>
                        </div></center></td>
            </tr>-->
	<tr>
            <td colspan="2" style="width:100%;"><center>
                <div style="margin-top: 5px;"><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
                <?php
                    if($count1>0)
														{
															echo '<br> You rated this:</p>';
															//echo "hi";
														}
														else
														{
															echo '<p style="font-size: 24px;font-weight:bold;"> Ridden this?</p><p style="font-weight:100 !important;">(Tap to rate)</p>';
														}

                    ?>

                </div>
		</center></td>
</tr>
<tr>
            <td colspan="2" style="width:100%;float:right;"><center>
                <div class='imgdiv'></div>
											<div id="productstar" style="padding:0px;width:100%;padding: 0px !important;">
												<!--<div id="rate-text">&nbsp;</div>-->

												<?php

												if($_COOKIE['bb_username'] && $count1 > 0)
												{
												?>

													<?php

$ratePerc = ($res1[$fieldName]/5)*100 ;
echo "<div class='star-ratings-css-listing-mobile' style='line-height:55px;font-size:55px !important;'>
							  <div class='star-ratings-css-top' style='width:".$ratePerc."%'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class='star-ratings-css-bottom'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>";

													?>
												<?php
												}
												else
												{
												?>

												<style>
.rating-mobile {
   /* float:left;*/
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating-mobile:not(:checked) > input {
    position:absolute;
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
<fieldset class="rating-mobile" style="width:275px;">
  <input type="radio" id="mari" name="rating"  value="5" /><label for="star5"   class="mari five-star">5</label>
    <input type="radio" id="mari" name="rating"  value="4" /><label for="star4"  class="mari four-star">4</label>
    <input type="radio" id="mari" name="rating"  value="3" /><label for="star3"   class="mari three-star">3</label>
    <input type="radio" id="mari" name="rating" value="2" /><label for="star2"  class="mari two-star">2</label>
    <input type="radio" id="mari" name="rating"  value="1" /><label for="star1"  class="mari one-star">1</label>
</div>


												<?php
												}
												?>
												<div id="rate-result" style="display:none;"></div>
											</div>
                <!--<div style="float:left;padding:5px;width:25%;"><p style=" color: red;
    font-size: 24px;
    font-weight: bold;
    font-family: helvetica;
    padding-top: 5px;"><?php  if($_COOKIE['bb_username'] && $count1>0){ echo $res1[$fieldName];}else { echo "?";} ?>/5</p></div>-->

                </center></td></tr>
<tr>
<td colspan="2" ><center>
<div style="text-align:center;padding:5px;width:25%;margin-top:60px;"><p style=" color: red;
    font-size: 36px;
    font-weight: bold;
    font-family: helvetica;
    padding-top: 5px;"><?php  if($_COOKIE['bb_username'] && $count1>0){ echo $res1[$fieldName];}else { echo "?";} ?>/5</p></div></center>


</td>
</tr>

            <tr>
						<td colspan="2">
					   <center>

											<?php
											if($_COOKIE['bb_username'] && $count1>0)
											{
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
							$final = $newurl[0]."-review.html?rating=".$res1[$fieldName]."&reviewid=".$res1[$fieldName1];
												echo "<div><p><a style=' font-size:16px;   font-weight: normal;    text-decoration: none;    padding: 4px;    background-color: rgb(9, 111, 0);    border-radius: 2px;    color: #fff;' href='".$final."'>Edit</a></p></div>";
											}
											else
											{
												echo "<div></div>";
											}
											?>

					</center></td></tr>


		</table>
		<!--<table cellspacing="0" class="top-rate product-ratings" border='1'></table>-->
	</div>
	  </center>
	<!---------------------------FOR DESKTOP VIEW ----------------------->
	<style>
		.course-detail { width:310px;/* margin:10px 5px 0 5px;*/ }
		.course-detail td { padding:3px 20px 3px 0; color:#360; }
		.product-partner-detail td { color:#360; padding:3px; }
		.product-partner-detail table th { text-align:center; width:310px; padding:0 0 10px 0; }
		.product-score-outer p a:hover { text-decoration:none; }

		#product-rating-detail-inner {  width:220px; z-index:0; }
		#product-rating-detail table { background-color:#fff; width:200px; }
		#product-rating-detail table td { border:2px solid #fff; }
		#product-rating-detail table td.dr { width:67px; }
		.user-review-desktop { padding: 0 0 10px 20px; margin: 6px 0; width:610px; }
		.user-review-rating { width: 200px; padding-left: 5px; float:right; }

	</style>


<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>
	<div class="product-overview product-overview-inner golf-club-list" id="product-overview-desktop" style="">
<?php }else{ ?>
	<div class="product-overview product-overview-inner golf-club-list" id="product-overview-desktop" style="display:none;">
<?php } ?>
		<table cellspacing="0">
			<tr>
				<td valign="top">
					<div style='width:410px;height:378px; border:1px solid #b5b9a7;'>

						<?php
						if($SITE_NAME == 'mtbr')
						{
							if(function_exists('mtbr_cr_site_location_details'))
							{
								if(strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') == false)
								{
									mtbr_cr_site_location_details_with_map('410','378');
								}
							}

						}
						else
						{
							if(function_exists('cr_site_location_details'))
							{
								if(strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') == false)
								{
									cr_site_location_details_with_map('410','378');
								}
							}

						}

						?>



					</div>
				</td>
				<td valign="top">
					<div class="product-ratings">


<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>


                                <div valign="top" style='width:60%;'><center>
									<div class="product-score-outer">
										<div class="product-score-box"  style='width:170px; height:176.5px;'><style>.product-score-box p{margin-bottom:0px!important;}</style>
											<p style="padding-bottom:4px;font-size:15px;font-family:arial;">REVIEW SCORE</p>
											<div class="product-score-inner">
<style>.product-score-inner p{margin-bottom:0px!important;}</style>
												<p><span style="font-size:1.4em;" id="overall-count"><u style="text-decoration: none;"><?php echo $Course->total_rating_count; ?></u></span> REVIEWS</p>
												<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average"><?php echo round($Course->average_rating,1); ?></p>
												<p>

<?php $ratePerc = ($Course->average_rating/5)*100 ?>
<div class="star-ratings-css-listing" style="margin-left: <?php echo $unicodestarcenter; ?>;    margin-top: -4px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
s</p>
												<p style="color:#333;line-height: 0.7em !important;">OUT OF 5</p>
											</div>

										</div>
									</div></center>
								</div>


<center><div valign="top" style="margin:0;padding:0;" width='45%'>
									<div><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php
												error_reporting(0);

												$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

												$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Course->productid." ORDER BY `ReviewID` DESC limit 1 ");

												$count = mysqli_num_rows($qry);
												$res1=mysqli_fetch_assoc($qry);

													if($_COOKIE['bb_username'])
													{
														echo $_COOKIE['bb_username'].'</p>';
														if($count>0)
														{
															echo '<p style="font-size: 14px;  line-height: 1em !important;"> <br>You rated this:</p>';
														}
														else
														{
															echo '<p style="font-size: 14px; padding-top: 8px;"> Ridden This?<br />Rate It:</p>';
														}
													}
													else
													{

														echo '<p style="font-size: 14px;"> Ridden This?<br />Rate It:</p>';
													}

											?>
											</div>
									<div class='imgdiv'></div>
									<div style="margin-top:10px;">
												<!--<div id="rate-text">&nbsp;</div>-->

												<?php

												if($_COOKIE['bb_username'] && $count > 0)
												{
												?>

													<?php

	$ratePerc = ($res1[$fieldName]/5)*100 ;
echo "<div class='star-ratings-css-listing' style='font-size:".$unicodestarsize.";margin-left:4px;'>
							  <div class='star-ratings-css-top' style='width:".$ratePerc."%;padding:5px;'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class='star-ratings-css-bottom' style='padding:5px;'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>";


													?>
												<?php
												}
												else
												{
												?>

																								<style>
.rating {
    float:left;
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating:not(:checked) > input {
    position:absolute;
    clip:rect(0,0,0,0);
}

.rating:not(:checked) > label {
 float:right;														width:32px;															padding:0 .1em;															overflow:hidden;															white-space:nowrap;
															cursor:pointer;															font-size:<?php echo $hoverstarsize; ?>;
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

.rating > label:active {
    position:relative;
    top:2px;
    left:2px;
}
</style>
<div class="rating">
    <input type="radio" id="star5" name="rating" value="5" /><label for="star5"  class="mari five-star" >5</label>
    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="mari four-star">4</label>
    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="mari three-star">3</label>
    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="mari two-star">2</label>
    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="mari one-star">1</label>
</div>

												<?php
												}
												?>
												<div id="rate-result" style="display:none;"></div>
											</div>
                                            <div><p style="color:red;font-size:24px;" id="rating"><?php  if($_COOKIE['bb_username'] && $count>0){ echo $res1[$fieldName];}else { echo "?";} ?>/5</p></div>
											<?php
											if($_COOKIE['bb_username'] && $count>0)
											{
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
						$final = $newurl[0]."-review.html?rating=".$res1[$fieldName]."&reviewid=".$res1[$fieldName1];
												echo "<div><p><a href='".$final."'>Edit</a></p></div>";
											}
											else
											{
												echo "<div></div>";
											}
											?>


								</div></center>
<?php }else{ ?>
	<table cellspacing="0" class="top-rate">
							<tr>
                                <td valign="top" style='width:60%;'>
									<div class="product-score-outer">
										<div class="product-score-box" id="reviewAppOO7-site-navigation" style='width:170px; height:176.5px;'><style>.product-score-box p{margin-bottom:0px!important;}</style>
											<p style="padding-bottom:4px;font-size:15px;font-family:arial;">REVIEW SCORE</p>
											<div class="product-score-inner">
<style>.product-score-inner p{margin-bottom:0px!important;}</style>
												<p><span style="font-size:1.4em;" id="overall-count"><u style="text-decoration: none;"><?php echo $Course->total_rating_count; ?></u></span> REVIEWS</p>
												<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average"><?php if($Course->total_rating_count == 0){ echo "--";}else{echo round($Course->average_rating,1); } ?></p>
												<p>
<?php $ratePerc = ($Course->average_rating/5)*100 ?>
<div class="star-ratings-css-listing" style="margin-left: <?php echo $unicodestarcenter; ?>;    margin-top: -4px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</p></br>
												<p style="color:#333;line-height: 0.7em !important;">OUT OF 5</p>
											</div>
											<!--<p style="padding-top:4px;font-size:12px;"><a href="#" onclick="return false;" onmouseover="return showRatingDetail();" onmouseout="return hideRatingDetail()">SEE DETAILS >></a></p> -->
										</div>
									</div>
								</td>
                            </tr>
                            <tr>
								<td valign="top" style="margin:0;padding:0;color:black;" width='45%'>
<?php if($os == "Mac" && $osm != "iPhone" && $osm != "iPad"){ ?>
<div style="margin:12px 12px 8px 12px; border:1px solid; height: 228px;"><div>
<?php }else{ ?>
<div style="margin:12px 12px 8px 12px; border:1px solid; height: 208px;"><div>

<?php } ?>

											<?php
												error_reporting(0);

												$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

												$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Course->productid." ORDER BY `ReviewID` DESC limit 1 ");

												$count = mysqli_num_rows($qry);
												$res1=mysqli_fetch_assoc($qry);

													if($_COOKIE['bb_username'])
													{
														//echo $_COOKIE['bb_username'];
														if($count>0)
														{
															echo '<p style="font-size: 14px;  line-height: 1em !important;"> <br>You rated this:</p>';
														}
														else
														{
															if($Course->total_rating_count == 0){
echo '<p style="font-size: 24px;margin-bottom: 0px;"> Ridden this?<br /> </p><p style="font-weight:100 !important;    font-size: 12px;"> Be the first to rate it! <br/></p><p style="font-weight:100 !important;">(Hover and Click on a Star)</p>';
}else{
echo '<p style="font-size: 24px;margin-bottom: 0px;"> Ridden this?<br /></p><p style="font-weight:100 !important;    font-size: 12px;"> Rate it: <br/></p><p style="font-weight:100 !important;">(Hover and Click on a Star)</p>';
}
														}
													}
													else
													{

if($Course->total_rating_count == 0){
echo '<p style="font-size: 24px;margin-bottom: 0px;"> Ridden this?<br /></p><p style="font-weight:100 !important;    font-size: 12px;"> Be the first to rate it! <br/></p><p style="font-weight:100 !important;font-size: 12px;">(Hover and Click on a Star)</p>';
}else{
echo '<p style="font-size: 24px;margin-bottom: 0px;"> Ridden this?<br /></p><p style="font-weight:100 !important;    font-size: 12px;">Rate it: <br/></p><p style="font-weight:100 !important;font-size: 12px;">(Hover and Click on a Star)</p>';
}
//echo '<p style="font-size: 14px;"> Ridden This?<br />Rate It:</p>';
													}

											?>
											</div>
									<div class='imgdiv'></div>

												<!--<div id="rate-text">&nbsp;</div>-->

												<?php

												if($_COOKIE['bb_username'] && $count > 0)
												{
												?>

													<?php

$ratePerc = ($res1[$fieldName]/5)*100 ;
echo								 "<div style='height:5px;margin-left: 0px;margin-top:15px;margin-bottom:10px'><div class='star-ratings-css-listing' style='font-size:".$unicodestarsize.";margin-left:10px;    line-height: 30px;'>
							  <div class='star-ratings-css-top' style='width:".$ratePerc."%'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class='star-ratings-css-bottom'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div></div>";

													?>
												<?php
												}
												else
												{
												?>

																								<style>
.rating {
    float:left;
}

/* :not(:checked) is a filter, so that browsers that don’t support :checked don’t
   follow these rules. Every browser that supports :checked also supports :not(), so
   it doesn’t make the test unnecessarily selective */
.rating:not(:checked) > input {
    position:absolute;
    clip:rect(0,0,0,0);
}

.rating:not(:checked) > label {
   float:right;
															width:32px;
															padding:0 .1em;
															overflow:hidden;
															white-space:nowrap;
															cursor:pointer;

font-size:<?php echo $hoverstarsize; ?>;
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
<div style="margin-left: 4px;margin-top:10px;">
<div class="rating">
    <input type="radio" id="star5" name="rating" value="5" /><label for="star5"  class="mari five-star" >5</label>
    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="mari four-star">4</label>
    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="mari three-star">3</label>
    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="mari two-star">2</label>
    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="mari one-star">1</label>
</div>
</div>

												<?php
												}
												?></br>
												<div id="rate-result" style="display:none;"></div>

                                            <div style="    width: 100%; float: left;"><p style="color:red;font-size:24px;" id="rating"><?php  if($_COOKIE['bb_username'] && $count>0){ echo $res1[$fieldName];}else { echo "?";} ?>/5</p></div>
											<?php
											if($_COOKIE['bb_username'] && $count>0)
											{
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
						$final = $newurl[0]."-review.html?rating=".$res1[$fieldName]."&reviewid=".$res1[$fieldName1];
												echo "<div><p><a href='".$final."'>Edit</a></p></div>";
											}
											else
											{
												echo "<div></div></div>";
											}
											?>


								</td>
</tr>
						</table>
<?php } ?>


					</div><!-- product-ratings -->
				</td>
			</tr>
		</table>

	</div><!-- end product-overview-desktop -->

	<div class="item-box">
		<div class="title"><h1><?php echo $Course->product_name; ?> - <?php if($CourseType['course_type_name']=="trails"){echo "Trail";}else{ echo ucfirst($CourseType['course_type_name']);}?>, <?php echo $Course->city; ?>, <?php $state = explode('-', $Course->state); if(count($state)==1){echo ucfirst($state[0]);}else if(count($state)==2){if(in_array('other', $state)){echo ucfirst($state[0]);}else{echo ucfirst($state[1]);}}else if(count($state)==3){echo ucfirst($state[1])."&nbsp;"; echo ucfirst($state[2]);}else if(count($state)==4){if(in_array('other', $state)){ echo ucfirst($state[0])."&nbsp;";echo ucfirst($state[1])."&nbsp;";echo ucfirst($state[2]); }else if(in_array('pacific', $state)){
echo ucfirst($state[3]);}else{echo ucfirst($state[1])."&nbsp;"; echo ucfirst($state[2])."&nbsp;".ucfirst($state[3]);}} ?></h1></div>

<div class="user-review-separator header_sub_menu_sample" style="height:4px;margin:5px 0px 5px 0px;padding:0px !important;"></div>
<div class="text header_sub_menu_sample" style="padding:10px;">
			<p style="margin: 0px;color: black !important;font-weight:bold;">DESCRIPTION
			<p><?php
				if(is_numeric($Course->articleid) && $Course->articleid > 0 ) {
					query_posts("p=$Course->articleid");
					if( have_posts() ) : while ( have_posts() ) : the_post();
						the_content();
						endwhile; endif;
						wp_reset_query();
					}
				else { echo $Course->product_description; } ?></p>
		</div>
						<?php
						if($SITE_NAME == 'mtbr')
						{
							if(function_exists('mtbr_cr_site_location_details'))
							{
								mtbr_cr_site_location_details();
							}

						}
						else
						{
							if(function_exists('cr_site_location_details'))
							{
								cr_site_location_details();
							}

						}

						?>


	</div><!-- end item box -->

	<div id="reviews" class="user-reviews">

		<?php if($Reviews) { ?>
			<div class="user-reviews-header clearfix">
			<div style="float:left;"><h2> <?php echo strtoupper("User Reviews"); ?> </h2></div>
				<div class="reviews-nav" style="margin-top:10px;"><?php cr_review_pagination( $_GET['p'], $Course->total_reviews ); ?></div>

			</div>
<div class="user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>

	<?php foreach($Reviews as $r) {
        if($r->summary=="")
				{
        ?>

	<!-- desktop view start -->
		<table cellspacing="0" class="user-review-desktop" id="user-review-desktop">
				<tr>
					<td width="390" class="review-td-left header_sub_menu_sample">
						<div class="user-review-header">
 <span style="    background-color: transparent !important;"> [<?php echo $r->date_created; ?>]</span></strong>
					</div>
<div class="user-review-header" style="float: left; "><i class="fa fa-user" style="color: grey;font-size: 30px;"></i>
</div>

<div class="user-review-header" style="float: left;margin-top: 7px;"><span style="display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span></div></br>
						</div>
						<?php if(trim($r->pros) != '') { ?>
							<div class="user-review-header-content" style="clear:both;" >

								<div class="user-review-header"><span style="    background-color: transparent !important;" ><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Strength: </strong></span><p ><?php if(trim($r->pros) != ''){ echo $r->pros;}else { echo " - ";} ?></p></div>
						<?php } ?>
						<?php if(trim($r->cons) != '') { ?>
							<div class="user-review-header"><span style="    background-color: transparent !important;"><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Weakness: </strong></span><p ><?php if(trim($r->cons) != ''){ echo $r->cons;}else { echo " - ";} ?></p></div>
						<?php } ?>
						<!--<div class="user-review-header">
								<span>Price: <?php if($r->price==""){echo " Nill ";}else{ echo $r->price;} ?> &nbsp; Purchased: [<?php if($r->product_type==""){echo "  Nill ";}else{ echo $r->product_type;} ?>]&nbsp;&nbsp; Year Purchased: <?php if($r->year_purchased==""){echo "  Nill ";}else{ echo $r->year_purchased;} ?></span>
						</div>	-->

							<?php if(trim($r->similar_products) != '') { ?>
								<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>
							<?php } ?>

					</td>
					<td valign="top" >
						<div class="user-review-rating header_sub_menu_sample" >
										<table cellspacing="0">
											<tr>
												<td>OVERALL<br />RATING</td>
												<td class="rate"><?php echo $r->overall_rating; ?></td>
												<td>
<?php $ratePerc = ($r->overall_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</td>
											</tr>
										</table>
									</div>
					</td>
				</tr>
		</table>
<div class="onlydesktop user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>

<!-- for mobile view -->






					<!-- mobile view start -->
<div class="user-review-mobile" id="user-review-mobile" style="margin-top:10px; width:100%;display:none;">
<div class="user-review-header-content header_sub_menu_sample" style="clear:both;" >
						   <div class="user-review-header">
 <span style="    background-color: transparent !important;"> [<?php echo $r->date_created; ?>]</span>

</div>
<div class="user-review-header" style="float: left;"><i class="fa fa-user" style="color: grey;font-size: 30px;"></i>
</div>


<div class="user-review-header" style="float: left; margin-top: 7px;" ><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span>
</div></br></br>




	<?php if(trim($r->pros) != ''){ ?>
								<div class="user-review-header"><span style="    background-color: transparent !important;" ><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Strength: </strong></span><p><?php if(trim($r->pros) != ''){ echo $r->pros;}else { echo " - ";} ?></p></div>

		<?php } ?>
		<?php if(trim($r->cons) != ''){ ?>
								<div class="user-review-header"><span style="    background-color: transparent !important;"><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Weakness: </strong></span><p><?php if(trim($r->cons) != ''){ echo $r->cons;}else { echo " - ";} ?></p></div>
		<?php } ?>

	</div>

								<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>



   <div style="width:100%;padding-top:5px;">
      <center>
         <table cellspacing="0" class="user-review-rating header_sub_menu_sample" style="float:none;">
            <tr>
               <td>
                  OVERALL<br />RATING
               </td>
               <td class="rate">
                  <?php echo $r->overall_rating; ?>
               </td>
               <td>

			<?php $ratePerc = ($r->overall_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>

               </td>
            </tr>
         </table>
      </center>
   </div>
</div>
<div class="onlymobile user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>






        <?php
                                  }
                            else
                            {
                                ?>

        <table cellspacing="0" class="user-review-desktop" id="user-review-desktop">
						<tr>
							<td width="390" class="review-td-left header_sub_menu_sample"><div class="user-review-header">
 <span style="    background-color: transparent !important;"> [<?php echo $r->date_created; ?>]</span></strong>
					</div>
<div class="user-review-header" style="float: left;"><i class="fa fa-user" style="color: grey;font-size: 30px;"></i>
</div>


<?php if(trim($r->reviewer_experience) != '') { ?>
<div class="user-review-header" ><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span></div>
<div class="user-review-header" ><span style="font-size: 13px;display-block; background-color: transparent !important;">
			<?php echo $r->reviewer_experience; ?></span></div>
<?php }else{ ?>
<div class="user-review-header" style="    margin-top: 7px;"><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span></div>
<?php } ?>



							<?php if(trim($r->model) != '') { ?>
								<div class="user-review-header"  style="float: left;">
											<strong>Model Reviewed:</strong>
											<?php echo $r->model; ?>
										</div>
							<?php } ?>
								<div class="user-review-header"  style="float: left;">
										<p><?php echo $r->summary; ?></p>
									</div>
								<?php if(trim($r->customer_service) != '') { ?>
								<div class="user-review-header"  style="float: left;">
											<p style="margin-bottom: unset !important;"><strong>Customer Service</strong></p>
											<p><?php echo $r->customer_service; ?></p>
										</div>
							<?php } ?>
							<div class="user-review-header"  style="float: left;">
										<?php if(trim($r->similar_products) != '') { ?>
											<p style="margin-bottom: unset !important;"><strong>Similar Products Used:</strong></p>
											<p ><?php echo $r->similar_products; ?></p>
										<?php } ?>
									</div>
							</td>
							<td valign="top">
								<div class="user-review-rating header_sub_menu_sample" >
									<table cellspacing="0">
										<tr>
												<td>OVERALL<br />RATING</td>
												<td class="rate">
<?php echo $r->overall_rating; ?></td>
												<td>
<?php $ratePerc = ($r->overall_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</td>
											</tr>
											<tr>
												<td colspan="3">
													<div class="rate-line"></div>
												</td>
											</tr>
											<tr>
												<td>VALUE<br />RATING</td>
												<td class="rate"><?php echo $r->value_rating; ?></td>
												<td>
<?php $ratePerc = ($r->value_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</td>
											</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
<div class="onlydesktop user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>


		<!-- desktop view end -->
					<!-- mobile view start -->
						<div class="user-review-mobile" id="user-review-mobile" style="margin-top:10px; width:100%;display:none;">

<div class="user-review-header-content header_sub_menu_sample" style="clear:both;" >

							  <div class="user-review-header">
 <span style="    background-color: transparent !important;"> [<?php echo $r->date_created; ?>]</span></strong>
					</div>
<div class="user-review-header" style="float: left;"><i class="fa fa-user" style="color: grey;font-size: 30px;"></i>
</div>

<?php if(trim($r->reviewer_experience) != '') { ?>
<div class="user-review-header" ><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span></div>
<div class="user-review-header" ><span style="font-size: 13px;display-block; background-color: transparent !important;">
			<?php echo $r->reviewer_experience; ?></span></div>
<?php }else{ ?>
<div class="user-review-header" style="    margin-top: 7px;"><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
			<?php echo $r->user_screen_name; ?></strong></span></div>
<?php } ?>



									<?php if(trim($r->model) != '') { ?>
										<div class="user-review-header">
											<strong>Model Reviewed:</strong>
											<?php echo $r->model; ?>
										</div>
									<?php } ?>
</br>

									<?php if(trim($r->strength) != '') { ?>
										<div class="user-review-header" style="clear:both;">
											<span style="background-color: transparent !important;"><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Strength:</strong></span>
											<p ><?php echo $r->strength; ?></p>
										</div>
									<?php } ?>
									<?php if(trim($r->weakness) != '') { ?>
										<div class="user-review-header" style="clear:both;">
											<span style="background-color: transparent !important;"><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Weakness:</strong></span>
											<p><?php echo $r->weakness; ?></p>
										</div>
									<?php } ?>
									<div class="user-review-header">
										<p><?php echo $r->summary; ?></p>
									</div>
									<?php if(trim($r->customer_service) != '') { ?>
										<div class="user-review-header">
											<p><strong>Customer Service</strong></p>
											<p style="margin-bottom: unset !important;"><?php echo $r->customer_service; ?></p>
										</div>
									<?php } ?>
									<div class="user-review-header">
										<?php if(trim($r->similar_products) != '') { ?>
											<p style="margin-bottom: unset !important;"><strong>Similar Products Used:</strong></p>
											<p><?php echo $r->similar_products; ?></p>
										<?php } ?>
									</div>
</div>









						   <div style="width:100%;">
							  <center>
								 <table cellspacing="0" class="user-review-rating header_sub_menu_sample" style="float:none;">
									<tr>
									   <td>
										  OVERALL<br />RATING
									   </td>
									   <td class="rate">
										  <?php echo $r->overall_rating; ?>
									   </td>
									   <td>
<?php $ratePerc = ($r->overall_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
									   </td>
									</tr>
									<tr><td colspan="3"><div class="rate-line"></div></td></tr>
									<tr>
										<td>VALUE<br />RATING</td>
										<td class="rate"><?php echo $r->value_rating; ?></td>
										<td>

<?php $ratePerc = ($r->value_rating/5)*100 ?>
							<div class="star-ratings-css-listing" style="margin-bottom: 8px;font-size:18px !important;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
</td>
									</tr>
								 </table>
							  </center>
						   </div>
						</div>

<div class="onlymobile user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>







					<!-- mobile view end -->
		<?php }} ?>
		<div class="user-reviews-header clearfix">
			<div class="reviews-nav" style="margin-top:10px;"><?php cr_review_pagination($_GET['p'], $Course->total_reviews); ?></div>
		</div>
		<?php } ?>
	</div>
	<?php } else {
header("Location: /404");		?>
		<!-- if no product found -->
		Location not found.
	<?php } ?>
	</div><!-- end product-review -->
</div><!-- end inner -->
</div><!-- end content left -->


<!-- for google map -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
