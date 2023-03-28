	<?php
	ob_start();
	session_start();
	require_once(__DIR__.'/../../../wp-reviewconfig.php');
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
	mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
	mysql_select_db(DB_RNAME);


	$fieldName = ($SITE_NAME=="golfreview") ? "Overall_Rating" : "Overall_Rating";
	$fieldName1 = ($SITE_NAME=="golfreview") ? "ReviewID" : "ReviewID";
		// Load WordPress core
		require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


	//	if(isset($_SESSION['nonlogin']) && $Product->productid == $_SESSION['ProductID'])
		if(isset($_SESSION['nonlogin']))
		{
			//echo "Entered 1";
					if(isset($_COOKIE['bb_userid']))
					{
						//echo "Entered 2";
						$sqlinsert = mysql_query("INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model, channelid, overall_rating,valid, pros, cons, year_purchased, price, product_type, vbulletinid)	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['bb_username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."','".$_SESSION['rating']."',1,'".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['bb_userid']."')");

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
									 $rurl = $_SESSION['rurl'];
	                                                                $newurl = explode("?",$rurl);
	                                                                        $furlnew = $_SESSION['cnewurl'];
	                                                                        unset($_SESSION['pname']);
	                                                                        unset($_SESSION['prul']);
	                                                                        unset($_SESSION['cnewurl']);
	                                                                        unset($_SESSION[$fieldName]);
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
	                                                                        unset($_SESSION['nonlogin']);

	                                                                header('location: '.$furlnew);
							}
						}
					}

		}
		else
		{
			//echo "Entered 3";
		}



		site_review_product_brand_list();
		set_site_review_location('product-page');

		if($DEEPCATEGORIES == '1')
		{
			site_review_product_page_outdoor_product();
		}
		else
		{
			site_review_product_page();
		}






		$meta_desc = "";
		if(is_numeric($Product->articleid)) {

			query_posts("p=$Product->articleid");
			if( have_posts() ) : while ( have_posts() ) : the_post();

				$meta_desc = substr(strip_tags(get_the_content()), 0, 80);

			endwhile; endif;
			wp_reset_query();
		}
		else if($Product->product_description) {

			$meta_desc = substr(strip_tags($Product->product_description), 0, 80);

		}
		else if($Reviews) {

			$meta_desc = substr(strip_tags($Reviews[0]->summary), 0, 80);
		}
	?>

	<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
	<?php if($DEEPCATEGORIES == "1")
		{
		$parts2 = explode( '.html', $parts1[2] );
		}else{
		$parts2 = explode( '.html', $parts1[1] );
		}
	?>
	<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>



	<?php
	global  $cat_id, $pagename,$crrp;
	//$cat_id = Get_CategoryID_By_Path( $parts2[0] );
	$cat_id = $Product->categoryid;
	$pagename = "PRD";
	$crrp="PRD";
	?>

	<?php
		global $categoryname;
		$categoryname = str_replace("-"," ",strtoupper($Product->url_safe_category_name));
		//echo $categoryname
		// Set meta vars for header based on data obtained above
		set_site_review_meta_vars($Product->category_name, $Product->manufacturer_name, $Product->product_name, $Product->total_rating, $meta_desc);

	?>



	<?php
	//$newp = explode('.html',$parts1[3]);
	// For seo
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-config-extra.php';
	global $seo_title, $seo_description, $seo_keywords;

	$seo_title_temp = str_replace("[subcategory_name]",str_replace('-',' ',$Product->category_name),$PRODUCTTITLE);
	$seo_description_temp = str_replace("[subcategory_name]",str_replace('-',' ',$Product->category_name),$PRODUCTDESCRIPTION);
	$seo_keywords_temp = str_replace("[subcategory_name]",str_replace('-',' ',$Product->category_name),$PRODUCTKEYWORDS);

	$seo_title_temp2 = str_replace("[brand_name]",str_replace('-',' ',$Product->manufacturer_name),$seo_title_temp);
	$seo_description_temp2 = str_replace("[brand_name]",str_replace('-',' ',$Product->manufacturer_name),$seo_description_temp);
	$seo_keywords_temp2 = str_replace("[brand_name]",str_replace('-',' ',$Product->manufacturer_name),$seo_keywords_temp);

	$seo_title_temp3 = str_replace("[product_name]",str_replace('-',' ',$Product->product_name),$seo_title_temp2);
	$seo_description_temp3 = str_replace("[product_name]",str_replace('-',' ',$Product->product_name),$seo_description_temp2);
	$seo_keywords_temp3 = str_replace("[product_name]",str_replace('-',' ',$Product->product_name),$seo_keywords_temp2);

	$seo_title_temp4 = str_replace("[review_score]",str_replace('-',' ',$Product->total_rating),$seo_title_temp3);
	$seo_description_temp4 = str_replace("[review_score]",str_replace('-',' ',$Product->total_rating),$seo_description_temp3);
	$seo_keywords_temp4 = str_replace("[review_score]",str_replace('-',' ',$Product->total_rating),$seo_keywords_temp3);

	$seo_title_temp5 = str_replace("[review_count]",str_replace('-',' ',$Product->total_reviews),$seo_title_temp4);
	$seo_description_temp5 = str_replace("[review_count]",str_replace('-',' ',$Product->total_reviews),$seo_description_temp4);
	$seo_keywords_temp5 = str_replace("[review_count]",str_replace('-',' ',$Product->total_reviews),$seo_keywords_temp4);

	$seo_title = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_title_temp5);
	$seo_description = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_description_temp5);
	$seo_keywords = str_replace("[category_name]",str_replace('-',' ',$parts1[1]),$seo_keywords_temp5);
	?>
	<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>

	<?php }else{ ?>
	<link type="text/css" rel="stylesheet" href="<?php echo home_url() . "/wp-content/themes/site/jquery.bxslider.css".$CSS_VERSION; ?>" />
	<?php } ?>
	<?php

		get_header();
		$sp_product="prod";
		//print_r($_SESSION);


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
	$(document).ready(function() {
	  $( "img[id^='thumb-video']" ).on('click', function(ev) {
		var attrval = $(this).attr('src');
		$("#video")[0].src =attrval;
		$('#product-image-popup').css("display","none");
		$('#play-video').css("display","block");

	    	ev.preventDefault();

	  });
	});
	</script>
	<script>

		/* javascript for thumbnail sliding of product images
		 * and product rating detail popup window */

		function showRatingDetail() {

			document.getElementById('product-rating-detail').style.display="block";
			return false;
		}

		function hideRatingDetail() {

			document.getElementById('product-rating-detail').style.display="none";
			return false;
		}

		function showThumb(el,img,index) {
			//alert(index);

			document.getElementById('play-video').style.display="none";
			document.getElementById('product-image-popup').style.display="block";
			//alert(el);
			var all = el.parentNode.parentNode;

			var allt = all.getElementsByTagName('img');




			for(var i=0;i<allt.length;i++) {

				allt[i].style.boxShadow="none";
				allt[i].parentNode.parentNode.setAttribute("data-gallery","example-gallery");
			}
			var t = el.getElementsByTagName('img');

			t[0].parentNode.parentNode.setAttribute("data-gallery","");

			t[0].style.boxShadow="0 0 4px #444";
			var m = document.getElementById('product-image');
			m.setAttribute('src','<?php echo GR_IMG . 'large/'; ?>' + img);
			var m1 = document.getElementById('product-image-popup');

			m1.setAttribute('href','<?php echo GR_IMG . 'large/'; ?>' + img);

			return false;

		}


		function slidePage(t) {
			//var t = el.getElementsByTagName('img');
			//alert(t);
			var item_list = document.getElementById('image-thumbs');
			var div_list = item_list.getElementsByTagName('div');
			var xdest = t * 80 - 210;

			//alert( xdest + '  ' + xdest/t  );

			if(xdest < 0) xdest = 0;

			var xpos = getRightPos(item_list);
			var distance = Math.abs(xpos - xdest);
			var speed = distance/10;

			if(speed < 1) speed = 1;
			if(item_list.movement) clearTimeout(item_list.movement);

			if(xpos == xdest) {

				return false;
			}

			if(xpos < xdest) xpos += speed;
			else xpos -= speed;
			//alert(xdest + ' ' + xpos);
			setRightPos( item_list, xpos );
			progress = "slidePage('" + t + "')";

			item_list.movement = setTimeout(progress, 40);
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







			/*#tableB { display: none; }*/
			#scoredetails { display: none; }
			@media only screen and (max-width: 600px) {
				.star-ratings-css-listing{
				font-size:34px;
				}
				#productstar{padding:5px !important;}
				#product-review .item-box li {   line-height:normal;}
				/*#product-review .item-box p {   line-height:normal;}*/
				.user-reviews-header h2{text-align:center;}
				.reviews-nav {width:100%;}
			 .reviews-nav-bottom {width:100%; display:block !important;}
	<?php if($_GET['p'] >= 2){ ?>
			        .reviews-nav .label, .reviews-nav-bottom .label{    margin-top: 10px;}
	<?php }else{ ?>
	 .reviews-nav .label, .reviews-nav-bottom .label {    margin-top: 2px;}
	<?php } ?>
				#___plusone_0{ width:100% !important; }
				/* For mobile phones: */
				.scoredetails { width:100%!important; }

				.leftscore { width:100%!important; }
				/*.rightscore { width:20%!important; }*/
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
					<?php
					if($Product)
					{
					?>

						<!-- structured markup -->
						<script type="application/ld+json">
						{
						  "@context": "http://schema.org",
						  "@type": "Product",
						<?php  if($Product->total_rating_count == 0)
						{

						}
						else
						{
								echo ' "aggregateRating": {
							"@type": "AggregateRating",
							"ratingValue": "'.$Product->total_rating.'",
							"ratingCount": "'.$Product->total_rating_count.'",
							"reviewCount": "'.$Product->total_rating_count.'"
						  },';
						}

						  ?>
						  "description": "<?php
						  $find = array('"','<li>','</li>','<ul>','</ul>','<br>','</br>','<br />');
						$replace = array('','','','','','','','');
						echo str_replace($find,$replace,$Product->product_description);
						 ?>",
						  "name": "<?php echo $Product->manufacturer_name; ?> <?php echo $Product->product_name; ?> <?php echo $Product->category_name; ?>",
						  "image": "<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'300x225') : cr_product_image($Product->product_image, 'product'); ?>"


						 <?php  if($Product->total_rating_count == 0)
						{

						}
						else
						{
							// For lower rating and top rating
							$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

							$lowq = mysqli_query($con, "SELECT MIN(NULLIF(Overall_Rating, 0)) as lowrating FROM reviews  where ProductID=".$Product->productid."");

							$ratlow=mysqli_fetch_assoc($lowq);
							$lowrating = $ratlow['lowrating'];

							$maxq = mysqli_query($con, "SELECT MAX(NULLIF(Overall_Rating, 0)) as maxrating FROM reviews  where ProductID=".$Product->productid."");

							$ratmax=mysqli_fetch_assoc($maxq);
							$maxrating = $ratmax['maxrating'];


							$reviewsq = mysqli_query($con, "select Overall_Rating,User_Screen_Name,Date_Created,Summary,VbulletinID,pros,cons from reviews where ProductID=".$Product->productid." order by Overall_Rating desc limit 2");






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


								 // str_replace('"', '', $reviews['Summary']);

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
						<h1><?php echo $Product->manufacturer_name; ?> <?php echo $Product->product_name; ?> <?php echo $Product->category_name; ?></h1><?php $x=strlen($Product->manufacturer_name.$Product->product_name.$Product->category_name);

	if($x > 70){
	$marginstyles='style=" right: 192px;top: 55px;margin: 10px 10px 0px 0px;"';
	}else{
	$marginstyles='style=" right: 192px;top: 35px;margin: 10px 10px 0px 0px;"';
	}


	 ?>

						<div class="product-overview-mobile header_sub_menu_sample" id="product-overview-mobile" style="display:none;">



					<div id="score" class="score"  style="width:100%; padding:10px; ">
						<div style="width:100% ! important;">

							<div class="cam1 leftscore" id="reviewAppOO7-site-navigation" style="margin-bottom: 10px;width:100%; float:left; background-color:#096f00; height:101px; border-radius:10px;">
								<div style="width:100% ! important;">
									<div class="scoretop" style="width:100%; color:white; padding-top:9px; font-size:24px;font-weight:bold; text-align:center;"><?php if( $Product->total_rating_count == 0){
	echo "NO REVIEWS YET";
	}else{
	echo $Product->total_rating_count." REVIEWS"; }?></div>

									<div class="scoreleft" style=" border-radius: 10px;height: 53px; width: 91%; background-color: #fff; color: #27604d; margin: 10px;">
										<div class="scoreinnerleft" style="width:50%; padding:10px;float:left;">

	<?php $ratePerc = ($Product->average_rating/5)*100 ?>
	<div class="star-ratings-css-listing" style="     line-height: 32px;   margin-bottom: 5px;">
								  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								</div>
											</br>
										</div>
										<div class="scoreinnerright" style="width:50%; padding:10px;float:left;text-align:right !important;">
											<style> .scoreinnerright p { margin-bottom:0px!important; }</style>
											<p style="display: inline;font-size:50px;line-height:33px;color:black;font-weight:bold;" id="overall-average"><?php if($Product->total_rating_count == 0){
	echo "--";
	}else{
	echo round($Product->average_rating,1); } ?></p><p style="display: inline;font-size:24px;line-height:24px;color:red;">/5</p>
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
												<p style="text-align:center;font-size:26px;color:black;"> <?php echo number_format($Product->average_rating,1); ?> </p>
											</div>
										</div>
										<div class="seprator" style="width:5%;float:left;background-color: rgb(9, 111, 0);height:53px;"></div>
										<div class="scoreinnerleft" style="width:30%; padding:5px;float:left;">
											<div class="golfreviewinner" style="">
												<p class="scoredetailsinnerleft2" style="text-align:center;font-size: 15px;    color: black;    font-weight: bold;">QIKRATE</p>
											</div>
											<div class="rating1" style="height: 25px;">
												<p style="text-align:center;font-size:26px;color:black;"><?php echo number_format($Product->quickrating_average,1); ?></p>
											</div>
										</div>
										<div class="seprator" style="width:5%;background-color: rgb(9, 111, 0);float:left;height:53px;"></div>

										<div class="scoreinnerleft" style="width:30%; padding:5px;float:left;">
											<div class="golfreviewinner" style="">
												<p class="scoredetailsinnerleft2" style="text-align:center;font-size: 15px;    color: black;    font-weight: bold;">WEB</p>
											</div>
											<div class="rating1" style="height: 25px;    ">
												<p style="text-align:center;font-size:26px;color:black;"><?php echo number_format($Product->web_score_rating,1); ?> </p>
											</div>
										</div>
									</div>
									<p align="center"><img class="" style="" src="<?php bloginfo('template_url'); ?>/images/invertedbutton.png"></p>
								</div>
							</div>
						</div>
					</div>

					<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
					<center>
					<table cellspacing="0" style="width:100%">
					<tr>
						<td colspan="2" style="width:100%;">
							<?php if($Product->media && count($Product->media)>1) { ?>
								<ul  class="productbxslider"  style="visibility:hidden;max-height:280px;">
							<?php for($i=0;$i<count($Product->media);$i++) { ?>


							 <?php if($Product->media[$i]->type == "Video"){ ?>
								<li style='height:280px !important;'>
									<p align="center">
						      <iframe id="thumb-video-<?php echo $i; ?>"  width="280" height="193" src="<?php echo $Product->media[$i]->value; ?>" >

								         </iframe>
	</p>
								</li>

							<?php 	}else { ?>
								<li style='height:280px !important;'>
									<p align="center">
										<img class="img-responsive center-block" style="max-width:370px; width:100%;" id="thumb-img-<?php echo $i; ?>" src="<?php echo ($Product->media) ? cr_product_image($Product->media[$i]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>" width="280" height="280" />
									</p>
								</li>

							<?php } ?>


							<?php }?></ul>
							<?php } else { ?>
								<div id="innerproductimage" style=''><p align="center">
								<img id="thumb-img-0" style="max-width: 370px;" class="img-responsive center-block" src="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>"/></p></div>
							<?php } ?>
						</td>
					</tr>
	                <tr>
	                    <td colspan="2" style="width:100%;">
	<?php
	error_reporting(0);

													$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

													$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Product->productid." ORDER BY `reviewid` DESC limit 1 ");

													$count = mysqli_num_rows($qry);
													$res1=mysqli_fetch_assoc($qry);

	?>
	<?php

	if($Product->total_rating_count == 0){
	$firstreview= "<p style='font-weight:100 !important;text-align:center;font-size: 19px;margin-bottom: 0.5rem;'>Be the first to rate it!</p>";
	}else{
	$firstreview= "<p style='font-weight:100 !important;text-align:center;font-size: 19px;margin-bottom: 0.5rem;'>Help your community <br>and rate it.</p>";
	}

	 if($_COOKIE['bb_username'] && $count==0){ ?>
	<div class='writeareview' style="padding:10px;"><p style="font-weight:bold;font-size:24px;text-align:center;margin-bottom: 0.2rem;">Own this?<br /></p><?php echo $firstreview ; ?><p style="font-weight:100 !important;text-align:center;font-size: 12px;margin-bottom: 0.5rem;">(Tap a star to rate)</p></div>

	<?php }else if($_COOKIE['bb_username'] && $count>0){ ?>
		<div class='writeareview' style="padding:10px;"><p style="font-weight:bold;font-size:24px;text-align:center;margin-bottom: 0.2rem;">You rated this:<br /></p></div>
	<?php }else{ ?>
	<div class='writeareview' style="padding:10px;"><p style="font-weight:bold;font-size:24px;text-align:center;margin-bottom: 0.2rem;">Own this?<br /></p><?php echo $firstreview ; ?><p style="font-weight:100 !important;text-align:center;font-size: 12px;margin-bottom: 0.5rem;">(Tap a star to rate)</p></div>
	<?php } ?>
								<!--<center><div style="margin-left: 5px;margin-top: 10px;"><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;  ">
												<?php
													error_reporting(0);

													$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

													$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Product->productid." ORDER BY `reviewid` DESC limit 1 ");

													$count = mysqli_num_rows($qry);
													$res1=mysqli_fetch_assoc($qry);

														if($_COOKIE['bb_username'])
														{
			/* echo $_COOKIE['bb_username'].'</p>';*/
			echo '</p>';

														}
														else
														{
															echo '<p style="font-size: 14px;"> Own This?</p>';
														}

												?>
	                        </div></center>-->
								</td>
	                </tr>
					<tr>



								<td style="width:100%;">
	                                <center>

	                                    <!--<div style="float:left;padding:10px;width:25%;"><?php
	                                if($count>0)
	                                        {
	                                            echo '<p style="font-size: 14px;  line-height: 1em !important;"> You rated this</p>';
	                                        }
	                                        else
	                                        {
	                                            echo '<p style="font-weight:bold;font-size: 14px;"> Rate It:</p>';
	                                        }
	                                    ?>
	                                </div>-->
									<div class='imgdiv'></div>
												<div id="productstar" style="padding:0px;width:100%;margin-top: -25px;padding: 0px !important;">
													<!--<div id="rate-text">&nbsp;</div>-->

													<?php

													if($_COOKIE['bb_username'] && $count > 0)
													{
													?>

														<?php

																$ratePerc = ($res1[$fieldName]/5)*100 ;
	echo "<div class='star-ratings-css-listing-mobile' style='font-size:55px !important;margin-left:20px;'>
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
	  /*  float:left; */
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
	<fieldset class="rating-mobile" style="    width: 275px;">
	    <input type="radio" id="mari" name="rating"  value="5" /><label for="star5"  class="mari five-star">5</label>
	    <input type="radio" id="mari" name="rating"  value="4" /><label for="star4"  class="mari four-star">4</label>
	    <input type="radio" id="mari" name="rating"  value="3" /><label for="star3"  class="mari three-star">3</label>
	    <input type="radio" id="mari" name="rating" value="2" /><label for="star2"  class="mari two-star">2</label>
	    <input type="radio" id="mari" name="rating"  value="1" /><label for="star1"  class="mari one-star">1</label>
	</fieldset>


													<?php
													}
													?>
													<div id="rate-result" style="display:none;"></div>
												</div>
												<!--<div style="float:left;width:25%;"><p style="    color: red;
	    font-size: 24px;
	    font-weight: bold;
	    font-family: helvetica;
	    padding-top: 5px;"><?php  if($_COOKIE['bb_username'] && $count>0){ echo $res1[$fieldName];}else { echo "?";} ?>/5</p></div>--></center>


								</td>


					</tr>

	<?php if($_COOKIE['bb_username'] ){ ?>
	<tr><td colspan="2"><div style="width:100%;margin-top:60px;"><p style="    color: red;
	    font-size: 36px;
	    text-align:center;
	    font-weight: bold;
	    font-family: helvetica;
	    padding-top: 5px;"><?php  if($_COOKIE['bb_username'] && $count>0){ echo $res1[$fieldName]."/5";} ?></p></div></td></tr>
	<?php } ?>

	                    <tr><td colspan="2"><center>
	<?php
												if($_COOKIE['bb_username'] && $count>0)
												{
													//echo $_SERVER['REQUEST_URI'];
													$newurl = explode('.',$_SERVER['REQUEST_URI']);
													if($SITE_NAME=="golfreview")
													{
														$final = $newurl[0]."-review.html?rating=".$res1[$fieldName]."&reviewid=".$res1[$fieldName1];

													}
													else
													{
														$final = $newurl[0]."-review.html?rating=".$res1[$fieldName]."&reviewid=".$res1[$fieldName1];

													}

													echo "<div ><p><a style='    font-weight: normal;    text-decoration: none;    padding: 4px;    background-color: rgb(9, 111, 0);    border-radius: 2px;    color: #fff;' href='".$final."'>Edit</a></p></div>";
												}
												else
												{
													echo "<div></div>";
												}
												?></center></td></tr>
					</table>





					<!--<table cellspacing="0" class="top-rate product-ratings" border='1'></table>-->
					</div>
					</center>

		<!-- ----------------------------DESKTOP VIEW START--------------------------------- -->
						<style>
						.course-detail { width:310px; margin:10px 5px 0 5px; }
						.course-detail td { padding:3px 20px 3px 0; color:#360; }
						.product-partner-detail td { color:#360; padding:13px 3px 7px;}
						.product-partner-detail td > a {text-decoration: none !important;}
						.product-partner-detail p{margin-bottom: 0;}
						.product-partner-detail table th { text-align:center; width:310px; padding:14px 0 0 0; }
						.product-score-outer p a:hover { text-decoration:none; }
						#product-rating-detail-inner {  width:220px; z-index:0; }
						#product-rating-detail table { background-color:#fff; width:200px; }
						#product-rating-detail table td { border:2px solid #fff; }
						#product-rating-detail table td.dr { width:67px; }
						.user-review-desktop {  /*padding: 0 0 10px 20px;*/ margin: 6px 0; width : 100%; }
						/*.user-review-rating {  width: 200px; padding-left: 5px; float:right; }*/

						</style>





	<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>

	<div class="product-overview product-overview-inner golf-club-list" id="product-overview-desktop" style="">
	<?php }else { ?>

	<div class="product-overview product-overview-inner golf-club-list" id="product-overview-desktop" style="display:none;">
	<?php } ?>

							<table cellspacing="0">
								<tr valign="top">
									<td style="padding:0 0 0 7px;">
									<div class="box" style="width:410px;height:270px;background-color: #fff;">

											<div class="productimageBox"  style="display:table;">

	<div id="play-video" style="display:none;">
	<iframe id="video" width="410px" height="270px" frameborder="0" allowfullscreen></iframe>
	</div>
	<?php if(count($Product->media)>1){ ?>
	<a id="product-image-popup" data-title="sample" href="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>" data-toggle="lightbox" data-gallery="example-gallery" style="display: table-cell;  text-align: center;    vertical-align: middle;">
	<span class="rollover" <?php echo $marginstyles; ?> ></span>
	<img id="product-image" class="product-image" src="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>" style="    margin: 0 auto;"/>
	</a>
	<?php }else if(!$Product->media){
	$img=cr_product_image($Product->product_image, 'product');
	list($width, $height, $type, $attr) = getimagesize($img);
	if($height > 250){
	$marginstyle='style="margin: auto;"'; ?>
	<div style="display: table-cell;  text-align: center;  vertical-align: middle;">
	<a id="product-image-popup" data-title="sample" href="<?php echo cr_product_image($Product->product_image, 'product'); ?>" data-toggle="lightbox" style="display: table-cell;  text-align: center;    vertical-align: middle;">
	<span class="rollover" <?php echo $marginstyles; ?> ></span>

	<img id="product-image" class="product-image" src="<?php echo cr_product_image($Product->product_image, 'product'); ?>" <?php echo $marginstyle; ?> />
	</a>
	</div>
	<?php }else{
	$marginstyle='style="margin: 10% auto;"'; ?>
	<div style="display: table-cell;  text-align: center;  vertical-align: middle;">
	<img id="product-image" class="product-image" src="<?php echo cr_product_image($Product->product_image, 'product'); ?>" <?php echo $marginstyle; ?> />
	</div>
	<?php }
	?>

	<?php }else{ ?>
	<a id="product-image-popup" data-title="sample" href="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>" data-toggle="lightbox" style="display: table-cell;  text-align: center;    vertical-align: middle;">
	<span class="rollover" <?php echo $marginstyles; ?> ></span>
	<img id="product-image" class="product-image" src="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'large') : cr_product_image($Product->product_image, 'product'); ?>" style="    margin: 0 auto;" />
	</a>
	<?php } ?>
											</div>
										</div>


										<style>
										.siv-thumb-row
										{
											width:<?php echo count($Product->media)*80; ?>px!important;
										}
										</style>
										<ul class="image-thumb-list">
										<?php if(count($Product->media)>5){ ?>
											<li class="left-arrow">
												<a href="#" onclick="return slidePage(1);"></a>
											</li>
										<?php } ?>
											<li class="thumbs" style="width: 370px !important;" >
											<div id="image-thumbs" class="image-thumbs siv-thumb-row"  style="">




											<?php if($Product->media) { for($i=0;$i<count($Product->media);$i++) { ?>
												<div>
	<?php if($Product->media[$i]->type == 'Video'){ ?>
	<a data-title="sample" data-gallery='example-gallery' href="<?php echo $Product->media[$i]->value; ?>"><div class="thumbimageBox"><img class="thumb-video" id="thumb-video-<?php echo $i; ?>" src="<?php echo $Product->media[$i]->value; ?>" /></div></a>
	<?php }else{ ?>

	<?php if($i==0){ ?>

	<a data-title="sample"  onclick="return showThumb(this,'<?php echo $Product->media[$i]->value; ?>','<?php echo $i; ?>');" href="<?php echo cr_product_image($Product->media[$i]->value,'large'); ?>"><div class="thumbimageBox"><img id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Product->media[$i]->value,'80x60'); ?>" /></div></a>

	<?php }else{ ?>
	<a data-title="sample"  data-gallery='example-gallery'  onclick="return showThumb(this,'<?php echo $Product->media[$i]->value; ?>','<?php echo $i; ?>');" href="<?php echo cr_product_image($Product->media[$i]->value,'large'); ?>"><div class="thumbimageBox"><img id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Product->media[$i]->value,'80x60'); ?>" /></div></a>


	<?php } ?>



	<?php } ?>
												</div>
											<?php } } else { ?>
												<div>
													<div class="thumbimageBox"><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image, 'product'); ?>" /></div>
												</div>
											<?php } ?>




											</div>
											</li>
										<?php if(count($Product->media)>5){ ?>
											<li class="right-arrow">
												<a href="#" onclick="return slidePage(<?php echo count($Product->media); ?>);"></a>
											</li>
										<?php } ?>
										</ul>
									</td>
									<td valign="top">
										<div class="product-ratings">


	<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') != false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') != false) { ?>

												<center>
													<div width='60%' style="text-align:center;">
														<div class="product-score-outer">
															<div class="product-score-box" id="reviewAppOO7-site-navigation" style='width:170px; height:176.5px;'><style>.product-score-box p{margin-bottom:0px!important;}</style>
																<p style="padding-bottom:4px;font-size:15px;font-family:arial;">REVIEW SCORE</p>
																<div class="product-score-inner">
			<style>.product-score-inner p{margin-bottom:0px!important;}</style>
																	<p><span style="font-size:1.4em;" id="overall-count"><?php echo $Product->total_rating_count; ?></span> REVIEWS</p>
																	<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average"><?php echo round($Product->average_rating,1); ?></p>
																	<p>

																	<?php $ratePerc = ($Product->average_rating/5)*100 ?>
																	<div class="star-ratings-css-listing">
																	  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
																	  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
																	</div>
	</p>
																	<p style="color:#333;line-height: 0.7em !important;">OUT OF 5</p>
																</div>
																<!--<p style="padding-top:4px;font-size:12px;"><a href="#" onclick="return false;" onmouseover="return showRatingDetail();" onmouseout="return hideRatingDetail()">SEE DETAILS >></a></p>-->
															</div>
														</div>

													</div>

	<div style="width:45%;">
														<div><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
														<?php
															error_reporting(0);

															$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

															$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Product->productid." ORDER BY `ReviewID` DESC limit 1 ");

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
																		echo '<p style="font-size: 14px; padding-top: 10px;text-align: -webkit-center;"> Own This?<br />Rate It:</p>';
																	}
																}
																else
																{
																	echo '<p style="font-size: 14px;"> Own This?<br />Rate It:</p>';
																}

														?>
														</div>
														<div class='imgdiv'></div>
												<div style="margin-left: 50px;margin-top:10px;">
															<!--<div id="rate-text">&nbsp;</div>-->


															<?php

															if($_COOKIE['bb_username'] && $count > 0)
															{
															?>

																<?php
																$ratePerc = ($res1[$fieldName]/5)*100 ;
	echo "<div class='star-ratings-css-listing' style='font-size:38px;    margin-top: -3px !important;'>
								  <div class='star-ratings-css-top' style='width:".$ratePerc."%'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								  <div class='star-ratings-css-bottom'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								</div>";
																?>
															<?php
															}
															else
															{
															?>


															<ul id="star-rating" class="star-rating">

																<li><a  title="1 Click to rate" class="mari one-star">1</a></li>
																<li><a  title="2 Click to rate" class="mari two-stars">2</a></li>
																<li><a  title="3 Click to rate" class="mari three-stars">3</a></li>
																<li><a  title="4 Click to rate" class="mari four-stars">4</a></li>
																<li><a  title="5 Click to rate" class="mari five-stars">5</a></li>

															</ul>

															<?php
															}
															?>



															<div id="rate-result" style="display:none;"></div>
														</div></br>
														<div style="">
															<p style="color:red;font-size:24px;" id="rating">
																<?php
																if($_COOKIE['bb_username'] && $count>0)
																{
																	echo $res1[$fieldName];
																}
																else
																{
																	echo "?";
																}
																?>/5
															</p>
														</div>
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
													<td width='60%' valign="top">
														<div class="product-score-outer">
															<div class="product-score-box" id="reviewAppOO7-site-navigation" style='width:170px; height:176.5px;'><style>.product-score-box p{margin-bottom:0px!important;}</style>
																<p style="padding-bottom:4px;font-size:15px;font-family:arial;">REVIEW SCORE</p>
																<div class="product-score-inner">
																	<style>.product-score-inner p{margin-bottom:0px!important;}</style>
																	<p><span style="font-size:1.4em;" id="overall-count"><?php echo $Product->total_rating_count; ?></span> REVIEWS</p>
																	<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average">
																		<?php if($Product->total_rating_count == 0){echo "--";}else{echo round($Product->average_rating,1); } ?></p>
																	<p>
																	<?php $ratePerc = ($Product->average_rating/5)*100 ;



	?>
																	<div class="star-ratings-css-listing" style="margin-left: <?php echo $unicodestarcenter; ?>;    margin-top: -4px !important;">
																								  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
																								  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
																	</div>

	</p>
													</br><p style="color:#333;line-height: 0.7em !important;">OUT OF 5</p>
																</div>
																<!--<p style="padding-top:4px;font-size:12px;"><a href="#" onclick="return false;" onmouseover="return showRatingDetail();" onmouseout="return hideRatingDetail()">SEE DETAILS >></a></p>-->
															</div>
														</div>

													</td>
												</tr>













											<tr>
										<td valign="top" style="margin:0;padding:0;" width='45%'>
													<?php
															error_reporting(0);

															$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

															$qry = mysqli_query($con, "SELECT Overall_Rating, ReviewID FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['bb_userid']." and productid = ".$Product->productid." ORDER BY `ReviewID` DESC limit 1 ");

															$count = mysqli_num_rows($qry);
															$res1=mysqli_fetch_assoc($qry);
																if($_COOKIE['bb_username'])
																{
																	if($count>0)
																	{
																		$boxheight = "";
																	}
																	else
																	{
																		$boxheight = "height: 208px;";
																	}
																}
																else
																{
																		$boxheight = "height: 208px;";
																}
													?>


								<div style="margin:12px 12px 8px 12px; border:1px solid; <?php echo $boxheight; ?>">


														<!-- Own This all text START-->
														<div>
														<?php


																if($_COOKIE['bb_username'])
																{
																	if($count>0)
																	{
																		echo '<p style="font-size: 14px;  line-height: 1em !important;"> <br>You rated this:</p>';
																	}
																	else
																	{

																		if($Product->total_rating_count == 0){
																		echo '<p style="font-size: 24px;     margin-bottom: 0px;padding-top: 10px;text-align: -webkit-center;"> Own this?</p><p style="font-weight:100 !important;    margin-bottom: 5px;    font-size: 12px;">Be the first to rate it!</p><p style="font-weight:100 !important;    font-size: 12px;">(Hover and click on a star)</p>';
																		}else{
																		echo '<p style="font-size: 24px;    margin-bottom: 0px; padding-top: 10px;text-align: -webkit-center;"> Own this?</p><p style="font-weight:100 !important;     font-size: 12px;   margin-bottom: 5px;">Help your community <br>and rate it.</p><p style="font-weight:100 !important;    font-size: 12px;">(Hover and click on a star)</p>';
																		}
																	}
																}
																else
																{
																		if($Product->total_rating_count == 0){
																		echo '<p style="font-size: 24px;    margin-bottom: 0px;"> Own this?</p><p style="font-weight:100 !important;    font-size: 12px;">Be the first to rate it!</p><p style="font-weight:100 !important;    font-size: 12px;">(Hover and click on a star)</p>';
																		}else{
																		echo '<p style="font-size: 24px;    margin-bottom: 0px;"> Own this?</p><p style="font-weight:100 !important;    font-size: 12px;">Help your community <br>and rate it.</p><p style="font-weight:100 !important;    font-size: 12px;">(Hover and click on a star)</p>';
																		}

																}

														?>
														</div>
														<!-- Own This all text END-->

														<div class='imgdiv'></div>

														<?php
														if($_COOKIE['bb_username'] && $count > 0)
														{
															$ratePerc = ($res1[$fieldName]/5)*100 ;




							echo "<div style='height:5px;margin-left: 0px;margin-top:15px;margin-bottom:10px'>
							<div class='star-ratings-css-listing' style='font-size:".$unicodestarsize.";margin-left:4px;'>
																			<div class='star-ratings-css-top' style='width:".$ratePerc."%;padding:5px;'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
																			</div>
																			<div class='star-ratings-css-bottom' style='padding:5px;'><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
																			</div>
																		</div>
																</div>";
														}
														else
														{
														?>
														<div style="margin-left: 4px;margin-top:10px;">
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
														?>



														<div id="rate-result" style="display:none;"></div>

															</br>



												<div style="width: 100%;float: left;">
																<p style="color:red;font-size:24px;" id="rating">
																	<?php
																	if($_COOKIE['bb_username'] && $count>0)
																	{
																		echo $res1[$fieldName];
																	}
																	else
																	{
																		echo "?";
																	}
																	?>/5
																</p>
															</div>
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
															echo "</div>";
														}
														?>

														</div>
													</td>
											</tr>
											</table>
	<?php } ?>

										</div>
									</td>
								</tr>
							</table>
						</div> <!-- desktop view end -->

					<!-- 	</div> commented div -->



	<?php



			//print_r($Product);
			if($Product->productid)
			{

				//$qry22 = mysqli_query($con, "select linkid, productid, categoryid, link_name, use_graphic, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '".$Product->productid."' and pl.valid = 1 order by linkid desc");

				$qry22 = mysqli_query($con, "Select * from (select linkid, cp.partnerid, link_name, tracking_url, link_url, tagline, graphic, cp.partner_graphic, partner_product_text, sale_price, original_price,  pl.date_created from partner_links pl, partner_campaigns pc, commerce_partners cp where cp.partnerid = pc.partnerid and pl.campaignid = pc.campaignid and pl.productid = '".$Product->productid."' and pl.valid = 1 And pc.valid = 1 and expired = 0 and curdate() Between start_date And end_date order by pl.date_created desc) as s GROUP BY  partnerid order by case when partnerid = 412  then 0 else 1 end, CONVERT(SUBSTRING_INDEX(original_price,'-',-1),UNSIGNED INTEGER)  ");
				$count1 = mysqli_num_rows($qry22);
				while($partner_links1=mysqli_fetch_assoc($qry22))
				{
					$partner_links[] = $partner_links1;
				}

			}

	?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<style type="text/css">
.product-partner-price a {
    color: #000;
    text-decoration: none;
}
ul.checkmark{
margin: 2.7em 3.5em 1em 1em;
padding: 0;
}
ul.checkmark li{
font-size: .9em;
line-height: 1em;
}

ul.checkmark li{
list-style-type:none;
position:relative;
}

ul.checkmark li:before{
/*fill it with a blank space*/
content:"\00a0";

/*make it a block element*/
display: block;

/*adding an 8px round border to a 0x0 element creates an 8px circle*/
border: solid 15px #00CD00;
border-radius: 15px;
-moz-border-radius: 15px;
-webkit-border-radius: 15px;
height: 0;
width: 0;

/*Now position it on the left of the list item, and center it vertically
(so that it will work with multiple line list-items)*/
position: absolute;
left: 5px;
top: 50%;
margin-top: -18px;
}

ul.checkmark li:after{
/*Add another block-level blank space*/
content:"\00a0";
display:block;

/*Make it a small rectangle so the border will create an L-shape*/
width: 8px;
height: 15px;

/*Add a white border on the bottom and left, creating that 'L' */
border: solid #fff;
border-width: 0 3px 3px 0;

/*Position it on top of the circle*/
position:absolute;
left: 15px;
top: 30%;
margin-top: -13px;

/*Rotate the L 45 degrees to turn it into a checkmark*/
-webkit-transform: rotate(45deg);
-moz-transform: rotate(45deg);
-o-transform: rotate(45deg);
}
.add-partners-mobile:last-child {
  border-bottom: 0;
}
.add-partners-mobile {
 background : none !important;
/* margin: 0 1em 0.5em 1em;*/
margin : 0;
padding: 0px;
border-bottom: 1px solid #ededed;
}
#fml-wrapper:last-child {
    border-bottom: 0;
}
span.chArrow:before {
	 content: "\f178";
}
span.chArrow {
	 font: normal normal normal 14px/1 FontAwesome;
	 font-size: inherit;
	 text-rendering: auto;
	 -webkit-font-smoothing: antialiased;
	 padding-left: 0.5em;
}
.btn-td .hotdeal-buy-all-mer-fml {
	 width: 142px;
	 max-height: 31px;
	 margin-left: 0.5em;
	-webkit-transition: all 0.5s ease-in-out;
	-moz-transition: all 0.5s ease-in-out;
	-o-transition: all 0.5s ease-in-out;
	transition: all 0.5s ease-in-out;
	position: relative;
	left: 0;
}

.btn-td .hotdeal-buy-all-mer-fml {
	 left: 5px;
}

i.fas.fa-arrow-right {
  margin-left: 0.5em;
	-webkit-transition: all 0.5s ease-in-out;
	-moz-transition: all 0.5s ease-in-out;
	-o-transition: all 0.5s ease-in-out;
	transition: all 0.5s ease-in-out;
	position: relative;
	left: 0;
}

.hotdeal-buy-all-mer-fml:hover > i.fas.fa-arrow-right {
    left: 5px;
}

ul.checkmarkGreen li{
text-align: center;
}
.checkmarkGreen span {
    color: #fff;
}

ul.checkmarkGreen li{
list-style-type:none;
position:relative;
}

.checkmarkGreen strong {
	 margin-left: 1em;
	 color: #fff;
}

#wrapper-reviews {
	 width: inherit;
}
.subtitle-td-title {
    background: #000;
    font: 16px 'Oswald',sans-serif;
    min-height: 34px;
}
#reviews1 {
	 width: 60%;
	 float: left;
}
.reviews-content-left {
 width: 38%;
 float : right;
 border: 1px solid #ebebeb;
}
.checkmarkNone li span {
    vertical-align: middle;
}
@media screen and (max-width: 601px) {
#user-review-desktop {
		display: block!important;
}
#reviews1 {
	width : 100%;
	float : none;
}
.review-td-left {min-width : 345px;}
.reviews-content-left { display : none;}
/*.reviews-content-left, ul.checkmark { margin: 3em 3em 1em 0em !important;}*/
.user-review-desktop { padding : 0; width: 100%;}
.subtitle-td {width : 0 !important; }
.subtitle-td strong { display : none;}
.add-partners-mobile {
	    margin: 0 0.5em 0.5em 0.5em !important;
		}
.partner_link_price a {
	    font: 14px 'Oswald-Regular',sans-serif !important;
}
.btn-td .hotdeal-buy-all-mer-fml { width : 112px !important;}
ul.checkmark li:before {
    content: "\00a0";
    display: block;
    border: solid 9px #00CD00;
    border-radius: 9px;
    -moz-border-radius: 9px;
    -webkit-border-radius: 9px;
    height: 0;
    width: 0;
    position: absolute;
    left: 7px;
    top: 50%;
    margin-top: -18px;
}
ul.checkmark li:after {
    content: "\00a0";
    display: block;
    width: 5px;
    height: 10px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    position: absolute;
    left: 14px;
    top: 30%;
    margin-top: -15px;
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -o-transform: rotate(45deg);
}
}
.subtitle-td strong {
	 font: 16px 'Oswald-Regular',sans-serif;
	 color: #2d2d2d;
	 font-weight: bold;
}
.partner_link_price_sticky a{
 color : #000;
 font-size : 1em;
 padding : 1em;
}
.partner_link_price_sticky {
	 float: left;
	 width: 25%;
	 vertical-align: middle;
 }
 #partners-wrapper {
  border: 1px solid #eeeeee;
	margin-bottom: 1em;
	border-radius : 3px;
}
.partner_link_price a {
  font: 16px 'Oswald-Regular',sans-serif;
  color: #2d2d2d;
  font-weight: bold;
}
.subtitle-td {
    width: 11em;
}
.product-partner-wrapper {
    min-height: 50px;
		margin: 0.5em 0 0.5em 0;
}
.product-partner-price {
    float: right;
    margin: 0 auto;
    height: 35px;
    text-align: center;
    vertical-align: middle;
    width: 30%;
    padding: 1em 0;
		font: 15px 'Oswald-Regular',sans-serif;
		color: #2d2d2d;
		font-weight: bold;
}
.product-partner-logo-sticky{
	width: 50%;
	float: left;
	margin-left : 1em;
}
#fml-wrapper{
	min-height : 100px;
	border-bottom: 1px solid #ededed;
	padding-bottom: 1em;
padding-top: 0.5em;
}
ul.checkmarkGreen {
    width: 10%;
    float: left;
    margin-top: 2em;
}
ul.checkmarkNone {
    color: #fff;
    text-align: center;
}
ul.checkmarkGreen li:before{
/*fill it with a blank space*/
content:"\00a0";

/*make it a block element*/
display: block;

/*adding an 8px round border to a 0x0 element creates an 8px circle*/
border: solid 9px #00CD00;
border-radius: 9px;
-moz-border-radius: 9px;
-webkit-border-radius: 9px;
height: 0;
width: 0;

/*Now position it on the left of the list item, and center it vertically
(so that it will work with multiple line list-items)*/
position: absolute;
left: 7px;
top: 40%;
margin-top: -8px;
margin-left: 1em;
}

ul.checkmarkGreen li:after{
/*Add another block-level blank space*/
content:"\00a0";
display:block;

/*Make it a small rectangle so the border will create an L-shape*/
width: 5px;
height: 9px;

/*Add a white border on the bottom and left, creating that 'L' */
border: solid #FFFFFF;
border-width: 0 2px 2px 0;

/*Position it on top of the circle*/
position:absolute;
left: 14px;
top: 30%;
margin-top: -4px;
margin-left: 1em;

/*Rotate the L 45 degrees to turn it into a checkmark*/
-webkit-transform: rotate(45deg);
-moz-transform: rotate(45deg);
-o-transform: rotate(45deg);
}

.partner_link_price {
    width: 10%;
}

</style>
<?php // check lowest price for best price at tag
$lowest = false;
foreach ($partner_links as $link) {
	if(count($partner_links)!=0) {
		if(!empty($link['original_price'])) {
			if($lowest === false || ($link['original_price'] < $lowest )) {
				$lowest = $link['original_price'];
			}
		}
	}
}
?>				<div class="item-box">
							<!-- bicyclebluebook -->
	        <div class="wrapper">
						<div id="one-wrap" style="margin-bottom: 10px;display:none;" class="title" id="reviews"><h1><?php echo $Product->manufacturer_name; ?> <?php echo $Product->product_name; ?> <?php echo $Product->category_name; ?>&nbsp;</h1></div>
						<?php if(($SITE_NAME == 'mtbr' && $parts1[2] == 'bikes') || ($SITE_NAME == 'roadbikereview' && $parts1[2] == 'latest-bikes')) { ?>
							<div id="two" class="big-button"><a href="http://www.bicyclebluebook.com/SearchBikes.aspx" onclick="_gaq.push(['_trackEvent', 'BicycleBlueBookMTBR', 'Click', 'www.bicyclebluebook.com']);" target="_blank" rel="nofollow" style="color:#fff;background-color: #2FAEF0;border-radius: 15px;padding:0 10px;font-size: 12px;text-decoration:none;"> Bicycle Blue Book Value </a></div><?php } ?>
				  </div>
					<div id="partners-wrapper">
							<?php
							// First FML
							$j=0;
							//check if atleast one FML exists

							if(count($partner_links)!=0)
							{
								$j=$j+1;
								//$j = 1

									//foreach($partner_links as $links) {
									for($i = 0; $i<count($partner_links);$i++ ) {
									//	echo 'what is $links here: '.$links;
									//	echo 'partner links:' .$partner_links;

								//	if(!empty($partner_links[$i]['original_price'])) {
	$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
	$pnewimg3 = $pcdn.$partner_links[$i]['partner_graphic'];


								 ?>

		<!-- ALL FMLs -->

								<div class="add-partners-mobile">
									<div class="add-partners-wrap">
										<div class="subtitle-td">
										<?php if($lowest === $partner_links[$i]['original_price'] ) { ?>
												<strong>Best Price At </strong>
											<?php } else { ?>
												<strong>Available At</strong>
											<?php }  ?>
										</div>

										<div class="product-partner-logo">
											<a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$i]['linkid']; ?>&referrer=FML_PRD_Main"><img src="<?php echo  $pnewimg3;?>" /></a>
										</div>
										<div class="partner_link_price">
										<?php if(strpos($partner_links[$i]['link_name'], 'Amazon') === FALSE) {
											 if(!is_null($partner_links[$i]['original_price']) || !empty($partner_links[$i]['original_price'])) { ?>
											<a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$i]['linkid']; ?>&referrer=FML_PRD_Main">$<?php echo $partner_links[$i]['original_price']; ?></a>
										<?php } else if(($partner_links[$i]['sale_price'] === '') || ($partner_links[$i]['original_price'] === '') || empty($partner_links[$i]['original_price']) || is_null($partner_links[$i]['original_price']) ) { ?>
									<?php }
									  }
										 ?>
										 </div>
										 <ul class="checkmark">
										 <?php if($lowest === $partner_links[$i]['original_price'])  { ?>
											 <li></li>
										 <?php } ?>
										 </ul>
										<div class="btn-td">
											<a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$i]['linkid']; ?>&referrer=FML_PRD_Main">
					<p class="hotdeal-buy-all-mer-fml" style="text-align:center;font-size: 12px;background-color:#ffa500;border-radius:3px;display: inline-block;">GO TO SHOP<i class="fas fa-arrow-right"></i></p></a>
										</div>
									</div>
								</div>
							<?php
					//	}
					}
				}
							else
							{
							}
							?>
						</div>
							<?php if($Product->product_description=="") {

								}else{?>
	<div class="user-review-separator header_sub_menu_sample" style="height:4px;margin:5px 0px 5px 0px;padding:0px !important;display:none;"></div>
							<div class="text header_sub_menu_sample" style="padding:10px;">
				<p style="margin: 0px;color: black !important;font-weight:bold;">DESCRIPTION

								<p style="margin:0px !important;"><?php
								if(is_numeric($Product->articleid) && $Product->articleid > 0) {

									query_posts("p=$Product->articleid");
									if( have_posts() ) : while ( have_posts() ) : the_post();

										the_content();

									endwhile; endif;
									wp_reset_query();

								} else {

									echo $Product->product_description;

								}?></p>
							</div>
							<?php }?>

						</div>

							<!-- if there are any reviews -->
						<?php if($Reviews)
						{
							?>
							<div class="user-reviews-header clearfix">
								<div style="float:left;"><h2> <?php echo strtoupper("User Reviews"); ?> </h2></div>
						<div class="reviews-nav" style="margin-top:10px;"><?php cr_review_pagination($_GET['p'], $Product->total_reviews); ?></div>
							</div>
								<div class="user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>
							<div id="wrapper-reviews">
								<?php 		if(count($partner_links) >= 1) { ?>
								<div id="reviews1" class="user-reviews">
								<?php } else { ?>
										<div id="reviews1" class="user-reviews" style="width : 100%;">
								<?php } ?>


						<?php
						$i=0;
						$k=0;
						foreach($Reviews as $r)
						{

							$i = $i+1;
							//echo 'what is i: '.$i;
							if($i%2 == 0)
							{
								$k= $k+1;
								//echo 'what is k: '.$k;
								// If no reviews

	$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
	$pnewimg3 = $pcdn.$partner_links[$k]['partner_graphic'];


								$remainingPartnerLinks = count($partner_links) ;
								if($k <= $remainingPartnerLinks)
								{
								$j = $j+1;
								 ?>


								<?php
								}
							}

						if($r->summary=="")
						{

						?>
		<!-- for desktop view -->
							<div class="user-review-desktop" id="user-review-desktop">

									<div class="review-td-left header_sub_menu_sample"><div class="user-review-header">
	 <span style="    background-color: transparent !important;"> [<?php echo $r->date_created; ?>]</span>

						</div>
	<div class="user-review-header" style="float: left;"><i class="fa fa-user" style="color: grey;font-size: 30px;"></i>
	</div>


	<div class="user-review-header" style="float: left; margin-top: 7px;" ><span style="font-weight: 700;font-size: 13px;display-block; background-color: transparent !important;"><strong>
				<?php echo $r->user_screen_name; ?></strong></span></div></br></br>

				<div class="user-review-rating header_sub_menu_sample" >
					<table cellspacing="0">
						<tr>
							<td>OVERALL<br />RATING</td>
							<td class="rate"><?php echo $r->overall_rating; ?></td>
							<td>
			<?php $ratePerc = ($r->overall_rating/5)*100 ?>
			<div class="star-ratings-css-listing" style="margin-bottom: 15px;font-size:18px !important;">
			<div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
			<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
			</div>
			</td>
						</tr>
					</table>
				</div>
		<?php if(trim($r->pros) != ''){ ?>
		<div class="user-review-header-content" style="clear:both;" >

									<div class="user-review-header"><span style="    background-color: transparent !important;" ><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Strength: </strong></span><p><?php if(trim($r->pros) != ''){ echo $r->pros;}else { echo " - ";} ?></p></div>

			<?php } ?>
			<?php if(trim($r->cons) != ''){ ?>
									<div class="user-review-header"><span style="    background-color: transparent !important;"><strong style="font-weight: 700;font-size: 13px;text-transform: uppercase;">Weakness: </strong></span><p><?php if(trim($r->cons) != ''){ echo $r->cons;}else { echo " - ";} ?></p></div>
			<?php } ?>

		</div>
									<div class="user-review-header" style="color:#fff;    display: inline-flex;  width: 100%">
										<?php if($r->price!=""){ ?>
											<div class="user-review-header-specific"><strong>Price Paid:</br> <font style="color:black;"><?php if($r->price==""){echo " - ";}else{ echo $r->price;} ?></font></strong></div>
										<?php } ?>

										<?php if($r->product_type!=""){ ?>
	<div class="user-review-header-specific" ><strong> Purchased: </br><font style="color:black;"><?php if($r->product_type==""){echo "  - ";}else{ echo $r->product_type;} ?>&nbsp;&nbsp; </font></strong></div>

										<?php } ?>
										<?php if($r->year_purchased!=""){ ?>
	<div class="user-review-header-specific"><strong>Model Year: </br><font style="color:black;"><?php if($r->year_purchased==""){echo "  - ";}else{ echo $r->year_purchased;} ?></font></strong></div>
										<?php } ?>
									</div>



									<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>
									</div>
								</div>

		<!-- for desktop view -->


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





									<div class="user-review-header" style="color:#fff;    display: inline-flex;    float: left; width: 100%">
										<?php if($r->price!=""){ ?>
											<div class="user-review-header-specific"><strong>Price Paid:</br> <font style="color:black;"><?php if($r->price==""){echo " - ";}else{ echo $r->price;} ?></font></strong></div>
										<?php } ?>

										<?php if($r->product_type!=""){ ?>
	<div class="user-review-header-specific" ><strong> Purchased: </br><font style="color:black;"><?php if($r->product_type==""){echo "  - ";}else{ echo $r->product_type;} ?>&nbsp;&nbsp; </font></strong></div>

										<?php } ?>
										<?php if($r->year_purchased!=""){ ?>
	<div class="user-review-header-specific"><strong>Model Year: </br><font style="color:black;"><?php if($r->year_purchased==""){echo "  - ";}else{ echo $r->year_purchased;} ?></font></strong></div>
										<?php } ?>
									</div>



									<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>
							   <div style="width:100%; padding-top: 5px" class="clearfix">

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
								<div class="star-ratings-css-listing" style="margin-bottom: 15px;font-size:18px !important;">
								  <div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								  <div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
								</div>
										   </td>
										</tr>
									 </table>

							   </div>
							</div>
	<div class="onlymobile user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>



							<!-- mobile view end -->


							<?php
						}
						else
						{
							?>










						<!-- desktop view start -->

							<div class="user-review-desktop" id="user-review-desktop">

									<div class="review-td-left header_sub_menu_sample">

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

	<div class="user-review-rating header_sub_menu_sample" >
		<table cellspacing="0">
			<tr>
				<td>OVERALL<br />RATING</td>
				<td class="rate">
<?php echo $r->overall_rating; ?></td>
				<td>
<?php $ratePerc = ($r->overall_rating/5)*100 ?>
<div class="star-ratings-css-listing" style="margin-bottom: 15px;font-size:18px !important;">
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
<div class="star-ratings-css-listing" style="margin-bottom: 15px;font-size:18px !important;">
<div class="star-ratings-css-top" style="width: <?php echo $ratePerc ?>%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
</div>
</td>
			</tr>
		</table>
	</div>
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
												<p style="margin-bottom: unset !important;"><?php echo $r->similar_products; ?></p>
											<?php } ?>
										</div>
									<!-- </td>
									<td valign="top">

									</td>
								</tr>
							</table> -->
						</div>
					</div>

							<!-- desktop view end -->



	<div class="onlymobile user-review-separator header_sub_menu_sample" style="height:4px;padding:0px !important;"></div>

							<!-- mobile view end -->





						<?php
						}

						?>
						<?php
						}
						?>




					</div>
					<?php 		if(count($partner_links) >= 1) { ?>
					<div class="reviews-content-left" style="margin-top: 0.5em;">
						 <div class="add-partners-mobile" style="width: 230px;">
							 <div class="add-partners-wrap-sticky">
								 <div class="subtitle-td-title">
									 <ul class="checkmarkNone"><li>
									 <span>Available At</span>
									 </li>
								 </ul>
								 </div>
										<?php
									for ($x = 0; $x < count($partner_links); $x++)
									{
										// If no reviews
								//	if(!empty($partner_links[$x]['original_price'])) {
						 $pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
						 $pnewimg3 = $pcdn.$partner_links[$x]['partner_graphic'];

								 ?>
								 <div id="fml-wrapper">
									 <div class="product-partner-wrapper">
									 <div class="product-partner-logo-sticky">
										 <a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main"><img style="width:100%" src="<?php echo  $pnewimg3;?>" /></a>
									 </div>
									 <ul class="checkmarkGreen">
									<?php if($lowest === $partner_links[$x]['original_price']) { ?>
										<li></li>
									<?php } ?>
									</ul>
									<?php if(strpos($partner_links[$x]['link_name'], 'Amazon') === FALSE) {
										 if(!is_null($partner_links[$x]['original_price']) || !empty($partner_links[$x]['original_price'])) { ?>
											 <div class="product-partner-price"><a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main">$<?php echo $partner_links[$x]['original_price']; ?></a></div>
										 <?php } else if(($partner_links[$x]['sale_price'] === '') || ($partner_links[$x]['original_price'] === '') || empty($partner_links[$x]['original_price']) || is_null($partner_links[$x]['original_price']) ) { ?>
											 <div class="partner_link_price"></div>
											 <?php
											}
										}
										 ?>
								 </div>

								 <?php if($lowest === $partner_links[$x]['original_price'] ) { ?>
								 <div class="btn-td">
									 <a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main"><p class="hotdeal-buy-all-mer-fml" style="text-align:center;font-size: 12px;background-color:#ffa500;width: 212px;border-radius: 3px;display: inline-block;">Best Price - Go To Shop<span class="chArrow"></span></p></a>
								 </div>
							 <?php } else { ?>
								 <div class="btn-td">
									<a target="_blank" href="/commerceredirect.html?linkid=<?php echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main"><p class="hotdeal-buy-all-mer-fml" style="text-align:center;font-size: 12px;background-color:#ffa500;width: 212px;border-radius: 3px;display: inline-block;">Go To Shop<span class="chArrow"></span></p></a>
								</div>
							 <?php } ?>
							  </div>
								 <?php
								// }
							 }

							 ?>
							 </div>
						 </div>
					 </div>
				 <?php  }
				 ?>
				</div>
					<div class="reviews-nav-bottom" style="margin-top:10px;display:none;"><?php cr_review_pagination($_GET['p'], $Product->total_reviews); ?></div>
				<?php
					}
					?>


					<?php
		// 			if(count($partner_links) > 1)
		// 			{
		// 				//foreach($partner_links as  $partner)
		// 				for ($x = 1; $x < count($partner_links); $x++)
		// 				{
		// 					// If no reviews
		//
		// $pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
		// $pnewimg3 = $pcdn.$partner_links[$x]['partner_graphic'];

					 ?>

					<!-- <div class="add-partners-mobile">
						<div class="add-partners-wrap">
							<div class="subtitle-td"><strong>Available At </strong></div>
							<div class="product-partner-logo">
								<a target="_blank" href="/commerceredirect.html?linkid=<?php //echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main">
									<img src="<?php //echo  $pnewimg3;?>" />
								</a>
							</div>
							<div class="btn-td">
								<a target="_blank" href="/commerceredirect.html?linkid=<?php //echo $partner_links[$x]['linkid']; ?>&referrer=FML_PRD_Main"><p class="hotdeal-buy-all-mer-fml"
									style="text-align:center;">Shop Now</p></a>
							</div>
						</div>
					</div> -->

					<!-- <?php
				// }
				// }


				 ?>
					<?php
						// else
						// {
						// 	?>
						 <?php
						// }
					 ?>
					 <div class="user-reviews-header clearfix">
						 <div class="reviews-nav" style="margin-top:10px;"><?php //cr_review_pagination($_GET['p'], $Product->total_reviews); ?></div>
					 </div>

<?php
			}


						 ?>
</div><!-- end product-review -->
					<?php
				//	else {
				//header("Location: /404");
					?> <!-- if no product found -->

					<!-- Product Page not found. -->

					<?php
				//	}
					?>

				</div>
			</div><!-- end inner -->
		</div><!-- end content left -->


		<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function ($) {
				function isScrolledToFML(elem) {
						var docViewTop = $(window).scrollTop(); //num of pixels hidden above current screen
						var docViewBottom = docViewTop + $(window).height();
						var elemTop = $(elem).offset().top; //num of pixels above the elem
						var elemBottom = elemTop + $(elem).height();
						return (((elemTop-30) <= docViewTop));  //subtract the sticky login bar
				}
				var catcher = $('.user-reviews-header');
				var sticky = $('.reviews-content-left');
				var footer = $('#footer');
				var content = $('#wrapper-reviews');
				$(window).scroll(function () {
						if (isScrolledToFML(sticky) ) { // stick to window
								var stickyWidthRight = catcher.offset().right + catcher.width();
								sticky.css('position', 'fixed');
								sticky.css('top', '2em');
								sticky.css('width', 'inherit');
								sticky.css('float', 'none');
								sticky.css('margin-left', '31.6em');
						}
						var topStopHeight = catcher.offset().top + catcher.height();
						if (topStopHeight > sticky.offset().top) { // stick back to top
								sticky.css('position', 'static');
								sticky.css('top', topStopHeight);
								sticky.css('width', '38%');
								sticky.css('float', 'right');
								sticky.css('margin-left', 0)

						}
						var contentStopHeight = content.offset().top + content.height();
						if (contentStopHeight < topStopHeight) {
								sticky.css('display', 'none');
						}
				});
		});
		</script>


	<?php get_sidebar(); ?>
	<?php get_footer(); ?>
