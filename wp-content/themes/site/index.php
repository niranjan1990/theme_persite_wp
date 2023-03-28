<?php
global  $cat_id, $pagename,$crrp;
$cat_id = "0";
$pagename = "DEFAULT";
$crrp ="HOMEPAGE";
?>
<?php 	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php'; ?>
<?php
global $seo_title, $seo_description, $seo_keywords;
$seo_title = $SITE_TITLE;
$seo_description = $SITE_DESC;
$seo_keywords = $SITE_KEYWORD;
?>
<?php
if(function_exists('set_site_review_location')){
	set_site_review_location('Index');
}
get_header();
?>



	<div id="content-left" class="col-sm-8">

	<div class="inner">

	<div class="main-content">

	<?php if($TOPPRODUCTVIEWS==1){
	$PSite = explode(":", get_option( 'PSiteName' ) );

	$views=site_product_views_page($PSite);
	$reviews=site_product_reviews_page($PSite);

		?>
	<div id="desktoptable" style="width:100%;display: inline-table;">
	<div id="desktoptable" style="width:53%;float:left; margin-right:10px">
	<h3 class="title"><b><?php echo $TITLEFORPRODUCTVIEWS; ?></b></h3>
	<table id="popularProducts" class="" cellpadding="0"  cellspacing="0" border="0" width="100%">
		<thead></thead>
		<?php foreach($views as $view) { ?>

		<?php
		$category_url = $view->category_path;
		$url_safe_category_name = $view->url_safe_category_name;
		$url_brand= $view->url_safe_manufacturer_name;
		$url_pname = $view->url_safe_product_name;

		$maincat = explode('/',$category_url);
		//$purl="/".$maincat[0]."/".$url_brand."/".$url_safe_category_name."/".$url_pname.".html";
		$purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";


		?>
		<tbody>
				<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
				<td>
				<div class="productRow" style="padding: 5px;">
					<div class="imageBox floatleft"><img id="thumb-img-0" style="max-width: 85px;    max-height: 85px;" src="<?php if(function_exists('cr_product_image')){ echo cr_product_image($view->product_image, 'product'); }?>" /></div>
					<ul>
						<li class="product_title"><?php echo $view->product_name; ?>&nbsp;<?php echo $view->category_name; ?></li>
						<li class="product_views"><?php echo $view->Views; ?> Views</li>
						<!--<li class="product_reviews"><a href="<?php echo $purl; ?>"><?php echo $view->Total_Reviews; ?> Reviews</a></li>-->
						<li class="product_rating"><?php $ratePerc = ($view->Average_Rating/5)*100 ?>
							<div class="star-ratings-css" style="">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
							<div class="product_ratingText"><?php echo round($view->Average_Rating,1); ?> of 5</div>
						</li>
					    <li class="product_reviewButton"><span><a href="<?php echo $purl; ?>">READ REVIEWS</a></span></li>
					</ul>
					<div class="clearfloat"></div>
				</div>
			</td></tr>
			</tbody>

		<?php } ?>
</table>
</div>
<div style="width:45%;float:left;">
<h3 class="title"><b><?php echo $TITLEFORLATESTREVIEWS; ?></b></h3>
<table id="latestReviews" cellpadding="0"  cellspacing="0" border="0">
		<thead></thead>
		<?php foreach($reviews as $review) { ?>
		<?php
		$category_url = $review->category_path;
		$url_safe_category_name = $review->url_safe_category_name;
		$url_brand= $review->url_safe_manufacturer_name;
		$url_pname = $review->url_safe_product_name;
		$maincat = explode('/',$category_url);
		if($DEEPCATEGORIES == '1')
		{
			$purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";
		}
		else
		{
			$purl="/".$maincat[0]."/".$url_brand."/".$url_safe_category_name."/".$url_pname.".html";
		}


		?>
		<tbody>
			<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
			<td>
				<tbody>
			<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
			<td>
				<div class="productRow">
					<div class="imageBox" style="    padding: 7px;    width: 115px;    height: 115px;    line-height: 115px;"><img id="thumb-img-0" src="<?php if(function_exists('cr_product_image')){ echo cr_product_image($review->product_image, 'product'); }?>" /></div>

					<ul>
						<li class="product_title"><?php echo $review->product_name; ?>&nbsp;<?php echo $review->category_name; ?></li>
						<li class="product_latestReview">Latest Review: <?php echo date('m/d/Y', strtotime($review->latest)); ?> </li>
						<li class="product_reviews" style="font-size: 16px;line-height: 22px;font-weight: 700;"><a href="<?php echo $purl; ?>"><?php echo $review->Total_Reviews; ?> Reviews</a></li>
						<li><?php $ratePerc = ($review->Average_Rating/5)*100 ?>
							<div class="star-ratings-css" style="">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
							<div class="product_ratingText"><?php echo round($review->Average_Rating,1); ?> of 5</div>

						</li>
						<li class="product_reviewButton"><span><a href="<?php echo $purl; ?>">READ REVIEWS</a></span></li>
						<!--<li class="product_views"><?php echo $review->Views; ?> Views</li>
						<li><?php echo date('d/m/Y', strtotime($review->latest)); ?> </li>-->
					</ul>
				</div>
			</td></tr>
			</tbody>
		<?php } ?>
</table>
</div>
</div>
<div id="mobiletable" style="display:none;">
<h3 class="title"><b><?php echo $TITLEFORPRODUCTVIEWS; ?></b></h3>
<table id="popularProducts" class="" cellpadding="0"  cellspacing="0" border="0" width="100%" class="mobiletable">
		<thead></thead>
		<?php $i=0;
		 foreach($views as $view) { ?>

		<?php
		$category_url = $view->category_path;
		$url_safe_category_name = $view->url_safe_category_name;
		$url_brand= $view->url_safe_manufacturer_name;
		$url_pname = $view->url_safe_product_name;

		$maincat = explode('/',$category_url);
		if($DEEPCATEGORIES == '1')
		{
			$purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";
		}
		else
		{
			$purl="/".$maincat[0]."/".$url_brand."/".$url_safe_category_name."/".$url_pname.".html";
		}


		?>
			<tbody>
				<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
				<td>
				<div class="productRow">
					<div class="imageBox floatleft"><img id="thumb-img-0" src="<?php if(function_exists('cr_product_image')){ echo cr_product_image($view->product_image, 'product'); }?>" /></div>
					<ul>
						<li class="product_title"><?php echo $view->product_name; ?>&nbsp;<?php echo $view->category_name; ?></li>
						<li class="product_views"><?php echo $view->Views; ?> Views</li>
						<!--<li class="product_reviews"><a href="<?php echo $purl; ?>"><?php echo $view->Total_Reviews; ?> Reviews</a></li>-->
						<li class="product_rating"><?php $ratePerc = ($view->Average_Rating/5)*100 ?>
							<div class="star-ratings-css" style="width: 70px;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
							<div class="product_ratingText"><?php echo round($view->Average_Rating,1); ?> of 5</div>
						</li>
					    <li class="product_reviewButton"><span><a href="<?php echo $purl; ?>">READ REVIEWS</a></span></li>
					</ul>
					<div class="clearfloat"></div>
				</div>
			</td></tr>
			</tbody>
<?php } ?>
</table>

<h3 class="title"><b><?php echo $TITLEFORLATESTREVIEWS; ?></b></h3>
<table id="latestReviews" cellpadding="0"  cellspacing="0" border="0" class="mobiletable">
		<thead></thead>
		<?php $i=0;
		 foreach($reviews as $review) { ?>

		<?php
		$category_url = $review->category_path;
		$url_safe_category_name = $review->url_safe_category_name;
		$url_brand= $review->url_safe_manufacturer_name;
		$url_pname = $review->url_safe_product_name;

		$maincat = explode('/',$category_url);
		if($DEEPCATEGORIES == '1')
		{
			$purl="/product/".$category_url."/".$url_brand."/".$url_pname.".html";
		}
		else
		{
			$purl="/".$maincat[0]."/".$url_brand."/".$url_safe_category_name."/".$url_pname.".html";
		}


		?>
			<tbody>
			<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
			<td>
				<tbody>
			<tr class='clickable-row' align="center" data-href="<?php echo $purl; ?>">
			<td>
				<div class="productRow">
					<div class="imageBox"><img id="thumb-img-0" src="<?php if(function_exists('cr_product_image')){ echo cr_product_image($review->product_image, 'product'); }?>" /></div>

					<ul>
						<li class="product_title"  style="width:50%;"><?php echo $review->product_name; ?>&nbsp;<?php echo $review->category_name; ?></li>
						<li class="product_latestReview">Latest Review: <?php echo date('m/d/Y', strtotime($review->latest)); ?> </li>
						<li class="product_reviews" style="font-size: 16px;line-height: 22px;font-weight: 700;"><a href="<?php echo $purl; ?>"><?php echo $review->Total_Reviews; ?> Reviews</a></li>
						<li><?php $ratePerc = ($review->Average_Rating/5)*100 ?>
							<div class="star-ratings-css" style="width: 70px;">
							  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							</div>
							<div class="product_ratingText"><?php echo round($review->Average_Rating,1); ?> of 5</div>

						</li>
						<li class="product_reviewButton"><span><a href="<?php echo $purl; ?>">READ REVIEWS</a></span></li>
						<!--<li class="product_views"><?php echo $review->Views; ?> Views</li>
						<li><?php echo date('d/m/Y', strtotime($review->latest)); ?> </li>-->
					</ul>
				</div>
			</td></tr>
			</tbody>
<?php } ?>
</table>
</div>
<?php
}?>



<!-- slider -->


    <style>
.advanced-slider .slide .html { width : 100% !important; height : 310px !important;    margin-top: 7px; }
@media only screen and (max-width: 600px) {
   #lazy-loading-slider{ display: none !important; }
}
</style>

<?php
if(isset($SITE_NAME))
{
?>
	<?php
		$PSite = explode(":", get_option( 'PSiteName' ) );
	?>



	<?php
	require_once(__DIR__.'/../../../wp-reviewconfig.php');


	$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

	$qry = mysqli_query($con, "SELECT * from carousel_media where ChannelID =$PSite[0] order by SlideOrder");

	$row_cnt = mysqli_num_rows($qry);

	if($row_cnt == 0)
	{
		echo '		<div class="advanced-slider" style="display:none!important" id="lazy-loading-slider">
		<ul class="slides">';
	}
	else
	{
		echo '		<div class="advanced-slider" id="lazy-loading-slider">
		<ul class="slides">';

	}
	while ($res1=mysqli_fetch_assoc($qry))
	{

	?>
		
<li class="slide" data-image="<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/images/carousel/large/<?php echo $res1['Value']; ?>">

          <a rel="slider-lightbox[]" target="_self" href="<?php echo $res1['URL']; ?>">
            <img class="image">
          </a>
					
						<img class="thumbnail" src="<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/images/carousel/thumb/<?php echo $res1['Value']; ?>">
					
					<div class="caption">
            <a style="color: #FFF;" href="<?php echo $res1['URL']; ?>"><?php echo $res1['Caption']; ?></a>
            <br>
            <span style="font-size:small; color:#FFF;font-weight:normal;"><?php echo $res1['Excerpt']; ?></span>
          </div>
        </li>

	<?php

	}

	?>

	</ul>
 </div>

<!-- slider -->

<!-- mobile slider -->

 <div class="advanced-slider onlymobile" id="lazy-loading-slider-mobile">
      <ul class="slides">
	<?php
	$qry = mysqli_query($con, "SELECT * from carousel_media where ChannelID =$PSite[0] order by SlideOrder");

	while ($res1=mysqli_fetch_assoc($qry))
	{

	?>
		
<li class="slide" data-image="<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/images/carousel/large/<?php echo $res1['Value']; ?>">
			
			  <a rel="slider-lightbox[]" target="_self" href="<?php echo $res1['URL']; ?>">
				<img class="image">
			  </a>
				
					<img class="thumbnail" src="<?php echo $PRODUCTIMAGE_CDNDOMAIN;?>/<?php echo $PRODUCTIMAGE_S3FOLDER; ?>/images/carousel/thumb/<?php echo $res1['Value']; ?>">
				
			  <div class="caption">
				<a style="color: #FFF;" href="<?php echo $res1['URL']; ?>"><?php echo $res1['Caption']; ?></a>
			   <!-- <span style="font-size:small; color:#FFF;font-weight:normal;"><?php echo $res1['Excerpt']; ?></span>-->
			  </div>
			</li>

	<?php

	}

	?>

      </ul>
    </div>


<!-- mobile slider -->



<?php
}
?>










<?php
if($SITE_NAME)
{
	require_once(__DIR__.'/../../../wp-reviewconfig.php');

	$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

	$PSite = explode(":", get_option( 'PSiteName' ) );



		if(!isset($_SESSION['hotdeals']))
		{
			$query=mysqli_query($con,"SELECT LinkID, Link_Name, Graphic, REPLACE(Partner_Product_Text ,'|', ' ') as Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date And end_date order by rand() LIMIT 15;");
			while($res1=mysqli_fetch_assoc($query))
			{
					$_SESSION['hotdeals'][] = $res1;
			}
		}
}
?>



<?php
if(count($_SESSION['hotdeals']) >=3)
{ ?>


	<!-- From Session -->
<div class="hot-deals-module-v2">



<?php
$numbers = range(0,count($_SESSION['hotdeals'])-1);
shuffle($numbers);

for ($x = 0; $x <= 1; $x++)
{
	$rand_num = $numbers[$x];

		
		
	$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
	$pnewimg3 = $pcdn.$_SESSION['hotdeals'][$rand_num]['Partner_Graphic'];
	$newimg1 = explode('/',$_SESSION['hotdeals'][$rand_num]['Graphic']);
	$newimg2 = count($newimg1);
	$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
	$newimg3 = $cdn.$newimg1[$newimg2-1];
	
	



		global $crrp;

		switch ($crrp) {
			case "HOMEPAGE":
				$referrer="HD_Homepage_Top";
				break;
		}
?>

	<table id="desktoptable" class="new-hot-deals floatleft" style="float: left;width: 50%;    height: 100px;">
	    <tbody>
			<tr class='clickable-row' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $_SESSION['hotdeals'][$rand_num]['LinkID']; ?>&referrer=<?php echo $referrer; ?>" style="height: 137px;">
				<td valign="top" class="middlecol" style="width: 190px; padding-top: 10px;   padding-left: 10px ;    vertical-align: top !important;    height: 143px;">
					<h4 style="font-size: 14px;    font-weight: bold;    color: #333333 !important;    padding: 0;    margin: 0 0 2px 0 !important;    min-height: 40px;">
						<?php echo $_SESSION['hotdeals'][$rand_num]['Partner_Product_Text']; ?>
					</h4>
					<div class="new-hot-deals-text" style="    font-size: 12px;    font-weight: bold;    color: #000;">
						<span class="old-price">(was <strike><?php echo $_SESSION['hotdeals'][$rand_num]['Original_Price']; ?></strike>)</span>
						<span class="hotdeals-price"><?php echo $_SESSION['hotdeals'][$rand_num]['Sale_Price']; ?></span>
						<span class="hotdeal-link_name"><img class="hotdeals-logo"  src="<?php echo $pnewimg3;?>"></span>
					</div>
				</td>
				<td valign="top" class="firstfirstcol" style="    vertical-align: top !important;    height: 130px;width: 90px;">
					<img class="new-hot-deals-img" style="width: 80px;    height: 80px;    padding-left: 5px;" src="<?php echo $newimg3;?>">
					<br>
					<div class="hotdeal-buy-all-mer" >Buy Now</div>
				</td>
			</tr>
		</tbody>
	 </table>

<?php
}
?>
</div>



<?php
}
?>

















<style>
.span-read-more {
    color: #BF1733 !important;
    /* font: 12px 'Oswald',sans-serif; */
    font-size: 14px;
}
#article-index div.review-index-margin p {
    margin: 0 0 0 0px;
}
#article-index div.review-index-margin p.news-margin {
    line-height: 24px;
    font-size: 15px;
    letter-spacing: 0px;
}
#article-index h5 {
    font-size: 12px;
    padding: 0px !important;
    color: #999999 !important;
}
h4 {
    font-size: 16px;
}
.index-box img {
    width: 150px;
    height: 150px;
    background-color: #BAAA5E;
}
#article-index .nav{ margin : 0px !important; }
#article-index ul li.nav-one a.current{background : none !important;}
#index-nav{height : 6px !important;}
#posts-container			{ width:400px; border:1px solid #ccc; -webkit-border-radius:10px; -moz-border-radius:10px; }
.post						{ padding:5px 10px 5px 100px; min-height:65px; border-bottom:1px solid #ccc; cursor:pointer;  }
.post:hover					{ background-color:lightblue; }
a.post-title 				{ font-weight:bold; font-size:12px; text-decoration:none; }
a.post-title:hover			{ text-decoration:underline; color:#900; }
a.post-more					{ color:#900; }
p.post-content				{ font-size:10px; line-height:17px; padding-bottom:0; }
#load-more					{color:#585858; font-weight:bold; text-align:right; padding:10px 0; cursor:pointer; }
#load-more:hover			{ color:#666; }
.activate					{ background:url('/wp-content/themes/site/images/spinner.gif') no-repeat 200px 50% #eee; }

</style>

<?php if($SITE_NAME && $REVIEWS==1){?>
<div class="reviews-queue">
 <div id="review-main-content">
   <div id="review-content" style="background: #ffffff !important;">
        <div class="clear"></div>

        <div id="article-index">
            <ul id="index-nav" class="nav">
                <li class="nav-one">
                <a href="#all" class="current">
              <h3 class="carrot"> LATEST REVIEWS</h3>
               </a>
                </li>
                 </ul>


            <div class="list-wrap">

                <ul id="all">
                    <li class="review-index-single">




<?php if($SITE_NAME && $REVIEWS==1){?>

<!--<script language="javascript">
document.write(drawPosts());
</script>-->


<?php
    // Correct the following path if needed. for cached file
 require_once(get_template_directory().'/ReviewInserter.php');
  echo (new ReviewInsertor(REVIEWAPP_ARTICLE_FILE_PATH_URL, REVIEWAPP_ARTICLE_FILE_MAX_AGE_IN_SECONDS,get_temp_dir(),"no"))->getReviews();
?>



<?php } ?>

	           

                   </li>
                </ul>
            </div>


   <div id="load-more" style="color: #585858;font-weight: bold;text-align: right;padding: 10px 0;cursor: pointer;">
<a href="http://reviews.<?php echo $SITE_NAME; ?>.com/page/2" style="color: #BF1733;font-size: 16px;">Read More &raquo;</a></div>
        </div>
    </div>
</div>
</div>
	 <?php }
?>



	</div><!-- end main content -->

</div><!-- end inner -->
</div><!-- end content left -->
<?php get_sidebar(); ?>
<!--</div>-->
<?php get_footer(); ?>

