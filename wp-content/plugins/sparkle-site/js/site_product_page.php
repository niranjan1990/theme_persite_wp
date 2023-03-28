<?php
ob_start();
session_start();
require_once(__DIR__.'/../../../wp-reviewconfig.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);

	// Load WordPress core
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


//	if(isset($_SESSION['nonlogin']) && $Product->productid == $_SESSION['ProductID'])
	if(isset($_SESSION['nonlogin']))
	{
		//echo "Entered 1";
				if(isset($_COOKIE['userid'])){

					//echo "Entered 2";
					$sqlinsert = mysql_query("INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model, channelid, value_rating, overall_rating, pros, cons, year_purchased, price, product_type, vbulletinid)	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."','','".$_SESSION['rating']."','".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['userid']."')");
					
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
							header('location: '.$_POST['purl']);
				
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
									unset($_SESSION['nonlogin']);
						}
					}
				}
		
	}
	else
	{
		//echo "Entered 3";
	}



	site_review_product_brand_list();
	// Set site review location
	set_site_review_location('product-page');

	// Get golf review product info based on URL,
	// All product information is in global object variable $Product
	// and can be used anywhere on this page
	
	site_review_product_page();

	// If product has Article ID associated with it, get the WordPress post
	// with that ID, trim the post content to 80 characters and include it
	// in the meta description tag of header. Else if no Article ID presen,
	// use the summery in the Products database
	
	
	//print_r($Reviews);
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

	// Set meta vars for header based on data obtained above
	set_site_review_meta_vars($Product->category_name, $Product->manufacturer_name, $Product->product_name, $Product->total_rating, $meta_desc);
	get_header();
	$sp_product="prod";
	//print_r($_SESSION);
	

	
?>

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
	
	function showThumb(el,img) {
	
		var all = el.parentNode.parentNode;
		var allt = all.getElementsByTagName('img');
		for(var i=0;i<allt.length;i++) {
		
			allt[i].style.boxShadow="none";
		}
		var t = el.getElementsByTagName('img');
		t[0].style.boxShadow="0 0 4px #444";
		var m = document.getElementById('product-image');
		m.setAttribute('src','<?php echo GR_IMG . 'medium/'; ?>' + img);
		return false;
	}
	
	function slidePage(t) {
	
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
	$('.mari').click(function(){
		var href = $(this).text();
		var url = window.location.href;
		//alert(url);
		var ans = url.split(".html");
		var ans = ans.slice(0,-1)
		var ans = ans +'-review.html?rating='+ href;
		window.location = ans;
		
	});

	$('.star').click(function(){
		var href = $(this).text();
		alert(href);
		//var ans = url.split(".html");
	//	var ans = ans.slice(0,-1)
	//	var ans = ans +'-review.html?rating='+ href;
	//	window.location = ans;
		
	});
	
	
});
</script>	

	<style>

		/*#tableB { display: none; }
		#scoredetails { display: none; }
		@media only screen and (max-width: 600px) {
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
		}*/
	</style>
	<div id="content-left" class="col-sm-8">
	<div class="inner" style="padding:5px;">
		<div id="product-review">
			<?php if($Product) { ?>
			<h1><?php echo $Product->manufacturer_name; ?> <?php echo $Product->product_name; ?> <?php echo $Product->category_name; ?></h1>			
			
			<div class="product-overview-mobile" id="product-overview-mobile" style="display:none;">
			     <div class="rightscore" style="float:left;margin-top: 15px;margin-left: 18px;">
						
                     <div class="product-share meta clearfix" style="width:100%;">
                        <div class="pp" style="width:25%;float:left;margin-left:0px !important;">Do you like this product?</div>
                        <div class="sbg f" style="width:25%;float:left"><iframe id="framefb" width="1000px" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no"  src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Product); ?>&amp;send=false&amp;layout=button_count&amp;amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border: none; visibility: visible; width: 65px; height: 20px;"></iframe>
                        <!--<iframe id="framefb" src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Product); ?>&amp;send=false&amp;layout=button_count&amp;amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:21px;" allowTransparency="true"></iframe>-->
                        </div>
                        <div class="sbg" style="width:25%;float:left"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php cr_product_url($Product); ?>" data-count="horizontal">Tweet</a>
                            <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                        </div>
                        <div class="sbg l" style="width:25%;float:left"><div class="g-plusone" data-size="standard" data-width="100" data-count="true" data-href="<?php cr_product_url($Product); ?>"></div></div>
                    </div>

                </div>
				<div id="score" class="score"  style="width:100%; padding:10px; ">
					<div style="width:100% ! important;">	
                       
						<div class="cam1 leftscore" style="margin-bottom: 10px;width:100%; float:left; background-color:#096f00; height:101px; border-radius:10px;">    
							<div style="width:100% ! important;">
								<div class="scoretop" style="width:100%; color:white; padding-top:9px; font-size:15px;font-weight:bold; text-align:center;">REVIEW SCORE</div>
								
								<div class="scoreleft" style=" border-radius: 10px;height: 53px; width: 91%; background-color: #fff; color: #27604d; margin: 10px;">
									<div class="scoreinnerleft" style="width:50%; padding:10px;float:left;">
										<img src="<?php echo cr_rating_image($Product->total_rating); ?>" />
										<p style="padding-top: 5px;font-family: sans-serif;    font-weight: bold;    font-size: 12px;"><span style="" id="overall-count"><u style="text-decoration:none !important;"><?php echo $Product->total_rating_count; ?></u></span> REVIEWS</p>            
									</div>
									<div class="scoreinnerright" style="width:50%; padding:10px;float:left;text-align:right !important;">
										<style> p { margin-bottom:0px!important; }</style>
										<p style="display: inline;font-size:50px;line-height:33px;color:black;font-weight:bold;" id="overall-average"><?php echo $Product->total_rating; ?></p><p style="display: inline;font-size:24px;line-height:24px;color:red;">/5</p>
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
				
				<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>			
				<table cellspacing="0">
				<tr>
					<td style="width:100%;">						
						<?php if($Product->media && count($Product->media)>1) { ?>
							<ul  class="productbxslider"  style="visibility:hidden;max-height:150px;">
						<?php for($i=0;$i<count($Product->media);$i++) { ?>							
							<li style='height:150px !important;'><p align="center"><img class="img-responsive center-block" id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Product->media[$i]->value,'150x150'); ?>" width="150" height="150" /></p></li>
						<?php }?></ul>
						<?php } else { ?>
							<div id="innerproductimage" style=''><p align="center"><img id="thumb-img-0" class="img-responsive center-block" src="<?php echo cr_product_image($Product->product_image, 'product'); ?>" width="150" height="150" /></p></div>
						<?php } ?>		
					</td>	
				</tr>
				<tr>
					<td valign="top" style="">
						<div></div>
						<table>
							<tr><td style="width:100%;">
								<!--<div class='writeareview' style="padding:10px;"><p style="font-weight:bold;font-size:15px;"> Own This?<br />Rate It:</p></div>-->
								<div style="margin-left: 5px;"><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php 
												error_reporting(0);
												
												$con = mysqli_connect("localhost","root","","golf_venice_new_feb_ver1") or die("Some error occurred during connection " . mysqli_error($con));  
											
												$qry = mysqli_query($con, "SELECT * FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['userid']." and productid = ".$Product->productid." ORDER BY `reviewid` DESC limit 1 ");
												
												$count = mysqli_num_rows($qry);
												$res1=mysqli_fetch_assoc($qry);
													
													if($_COOKIE['username'])
													{
														echo $_COOKIE['username'].'</p>';
														if($count>0)
														{
															echo '<p style="font-size: 14px;  line-height: 1em !important;"> <br>You rated this:</p>';
														}
														else
														{
															echo '<p style="font-size: 14px; padding-top: 8px;"> Own This?<br />Rate It:</p>';
														}
													}
													else
													{
														echo '<p style="font-size: 14px;"> Own This?<br />Rate It:</p>';
													} 

											?>
											</div>
							</td>
							<td style="width:100%;float:right; padding-left: 27px;">
								<div class='imgdiv'></div>
											<div style="margin-top:10px;">
												<!--<div id="rate-text">&nbsp;</div>-->
												
												<?php 
												
												if($_COOKIE['username'] && $count > 0)
												{
												?>
												
													<?php
													if($res1['overall_rating']==1)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/1star.png" />';
													}
													else if($res1['overall_rating']==2)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/2star.png" />';	
													}
													else if($res1['overall_rating']==3)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/3star.png" />';	
													}
													else if($res1['overall_rating']==4)
													{
				echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/4star.png" />';	
													}
													else if($res1['overall_rating']==5)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/5star.png" />';	
													}
													else
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/0star.png" />';
													}

													?>												
												<?php
												}
												else
												{
												?>
													
												<ul id="star-rating" class="star-rating">
												
													<li><a  href="#" title="1 Click to rate" class="mari one-star">1</a></li>
													<li><a  href="#"  title="2 Click to rate" class="mari two-stars">2</a></li>
													<li><a  href="#"  title="3 Click to rate" class="mari three-stars">3</a></li>
													<li><a  href="#"  title="4 Click to rate" class="mari four-stars">4</a></li>
													<li><a  href="#"  title="5 Click to rate" class="mari five-stars">5</a></li>
													
												</ul>
													
												<?php
												}
												?>
												<div id="rate-result" style="display:none;"></div>											
											</div>
											<div><p style="color:red;font-size:17px;"><?php  if($_COOKIE['username'] && $count>0){ echo $res1['overall_rating'];}else { echo "?";} ?>/5</p></div>
											<?php  
											if($_COOKIE['username'] && $count>0)
											{ 
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
												$final = $newurl[0]."-review.html?rating=".$res1['overall_rating']."&reviewid=".$res1['reviewid'];
												echo "<div><p><a href='".$final."'>Edit</a></p></div>";
											}
											else 
											{ 
												echo "<div></div>";
											} 
											?>
								
							</td>
							</tr>
						</table>
					</td>
				</tr>
				</table>

				
				


				<!--<table cellspacing="0" class="top-rate product-ratings" border='1'></table>-->				
			</div>
	
	
	<!-- ----------------------------DESKTOP VIEW START--------------------------------- -->
		<style>
		.course-detail { width:310px; margin:10px 5px 0 5px; }
		.course-detail td { padding:3px 20px 3px 0; color:#360; }
		.product-partner-detail td { color:#360; padding:3px; }
		.product-partner-detail table th { text-align:center; width:310px; padding:0 0 10px 0; }
		.product-score-outer p a:hover { text-decoration:none; }		
		#product-rating-detail-inner {  width:220px; z-index:0; }
		#product-rating-detail table { background-color:#fff; width:200px; }
		#product-rating-detail table td { border:2px solid #fff; }
		#product-rating-detail table td.dr { width:67px; }		
		.user-review-desktop { border-bottom: 2px solid #e9e8e4; padding: 0 0 10px 20px; margin: 6px 0; width:610px; }
		.user-review-rating { background-color: #e3e3d1; width: 190px; padding-left: 10px; float:right; }
		
		</style>
			<div class="product-overview" id="product-overview-desktop" style="display:none;">
			
				<div class="product-share meta clearfix">
					<div class="pp">Do you like this product?</div>
					<div class="sbg f"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Product); ?>&amp;send=false&amp;layout=button_count&amp;width=60&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:60px; height:21px;" allowTransparency="true"></iframe></div>
					<div class="sbg"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php cr_product_url($Product); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
					<div class="sbg l"><div class="g-plusone" data-size="medium" data-href="<?php cr_product_url($Product); ?>"></div></div>
				</div>
				<table cellspacing="0">
					<tr>
						<td style="padding:0 0 0 5px;">
							<div style='width:410px;height:270px; border:1px solid #b5b9a7;'>
								<img id="product-image" class="product-image" src="<?php echo ($Product->media) ? cr_product_image($Product->media[0]->value,'300x225') : cr_product_image($Product->product_image, 'product'); ?>" width="408" height="270" />
							</div>	
							<ul class="image-thumb-list">
								<li class="left-arrow"><a href="#" onclick="return slidePage(1);"></a></li>
								<li class="thumbs" style="width:210px!important"><div id="image-thumbs" class="image-thumbs" style="width:<?php echo count($Product->media) * 70; ?>px;">
								<?php if($Product->media) { for($i=0;$i<count($Product->media);$i++) { ?>
									<div><a href="#" onclick="return showThumb(this,'<?php echo $Product->media[$i]->value; ?>');"><img id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Product->media[$i]->value,'80x60'); ?>" width="64" height="42" /></a></div>
								<?php } } else { ?>
									<div><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image, 'product'); ?>" width="64" height="42" /></div>
								<?php } ?>
								</div></li>
								<li class="right-arrow"><a href="#" onclick="return slidePage(<?php echo count($Product->media); ?>);"></a></li>
							</ul>
						</td>
						<td valign="top">						
							<div class="product-ratings">
								<div id="product-rating-detail">
									<div id="product-rating-detail-inner">
										<img src="<?php bloginfo('template_url'); ?>/images/rating-arrow.png" style="position:absolute;top:72px;left:-17px;" />
										<table>
											<tr>
												<td class="dr"><p class="r"><?php echo $Product->average_rating; ?></p> OUT OF 5</td>
												<td><span class="green"><?php echo $Product->total_reviews; ?></span> GolfReview reviews</td>
											</tr>
											<tr>
												<td class="dr"><p class="r" id="quickrate-average"><?php echo $Product->quickrating_average; ?></p> OUT OF 5</td>
												<td><span class="green" id="quickrate-count"><?php echo $Product->quickrating_count; ?></span> GolfReview QIK ratings</td>
											</tr>
											<tr>
												<td class="dr"><p class="r"><?php echo $Product->web_score_rating; ?></p> OUT OF 5</td>
												<td><span class="green"><?php echo $Product->web_score_count; ?></span> reviews across the web</td>
											</tr>
										</table>
									</div>
								</div>
								<table cellspacing="0" class="top-rate">
									<tr>
										<td width='60%' valign="top">
											<div class="product-score-outer">
												<div class="product-score-box" style='width:170px; height:176.5px;'>
													<p style="padding-bottom:4px;font-size:15px;font-family:arial;">REVIEW SCORE</p>
													<div class="product-score-inner">
														<p><span style="font-size:1.4em;" id="overall-count"><?php echo $Product->total_rating_count; ?></span> REVIEWS</p>
														<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average"><?php echo $Product->total_rating; ?></p>
														<p><img src="<?php echo cr_rating_image($Product->total_rating); ?>" /></p>
														<p style="color:#333;">OUT OF 5</p>
													</div>
													<!--<p style="padding-top:4px;font-size:12px;"><a href="#" onclick="return false;" onmouseover="return showRatingDetail();" onmouseout="return hideRatingDetail()">SEE DETAILS >></a></p>-->
												</div>
											</div>
										
										</td>
									</tr>
                                    <tr><td valign="top" style="margin:0;padding:0;" width='45%'>
											<div><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php 
												error_reporting(0);
												
												$con = mysqli_connect("localhost","root","","golf_venice_new_feb_ver1") or die("Some error occurred during connection " . mysqli_error($con));  
											
												$qry = mysqli_query($con, "SELECT * FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['userid']." and productid = ".$Product->productid." ORDER BY `reviewid` DESC limit 1 ");
												
												$count = mysqli_num_rows($qry);
												$res1=mysqli_fetch_assoc($qry);
													
													if($_COOKIE['username'])
													{
														echo $_COOKIE['username'].'</p>';
														if($count>0)
														{
															echo '<p style="font-size: 14px;  line-height: 1em !important;"> <br>You rated this:</p>';
														}
														else
														{
															echo '<p style="font-size: 14px; padding-top: 8px;"> Own This?<br />Rate It:</p>';
														}
													}
													else
													{
														echo '<p style="font-size: 14px;"> Own This?<br />Rate It:</p>';
													} 

											?>
											</div>
											<div class='imgdiv'></div>
											<div style="margin-top:10px;">
												<!--<div id="rate-text">&nbsp;</div>-->
												
												<?php 
												
												if($_COOKIE['username'] && $count > 0)
												{
												?>
												
													<?php
													if($res1['overall_rating']==1)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/1star.png" />';
													}
													else if($res1['overall_rating']==2)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/2star.png" />';	
													}
													else if($res1['overall_rating']==3)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/3star.png" />';	
													}
													else if($res1['overall_rating']==4)
													{
				echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/4star.png" />';	
													}
													else if($res1['overall_rating']==5)
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/5star.png" />';	
													}
													else
													{
															echo '<img src="http://mtbr.owshi.com/wp-content/themes/site/images/0star.png" />';
													}

													?>												
												<?php
												}
												else
												{
												?>
													
												<ul id="star-rating" class="star-rating">
												
													<li><a  href="#" title="1 Click to rate" class="mari one-star">1</a></li>
													<li><a  href="#"  title="2 Click to rate" class="mari two-stars">2</a></li>
													<li><a  href="#"  title="3 Click to rate" class="mari three-stars">3</a></li>
													<li><a  href="#"  title="4 Click to rate" class="mari four-stars">4</a></li>
													<li><a  href="#"  title="5 Click to rate" class="mari five-stars">5</a></li>
													
												</ul>
													
												<?php
												}
												?>
												<div id="rate-result" style="display:none;"></div>											
											</div>
											<div><p style="color:red;font-size:24px;"><?php  if($_COOKIE['username'] && $count>0){ echo $res1['overall_rating'];}else { echo "?";} ?>/5</p></div>
											<?php  
											if($_COOKIE['username'] && $count>0)
											{ 
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
												$final = $newurl[0]."-review.html?rating=".$res1['overall_rating']."&reviewid=".$res1['reviewid'];
												echo "<div><p><a href='".$final."'>Edit</a></p></div>";
											}
											else 
											{ 
												echo "<div></div>";
											} 
											?>
											
											
																						
										</td></tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</div> <!-- desktop view end -->	

			
			
			<div class="item-box">
				<div class="title"><?php echo $Product->product_name; ?></div>
				<?php if($Product->product_description=="") {}else{?>
				<div class="text">
					<p><?php
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
			<div id="reviews" class="user-reviews">
				
				<?php if($Reviews) { ?>		
					<div class="user-reviews-header clearfix">
						<div class="reviews-nav"><?php cr_review_pagination($_GET['p'], $Product->total_reviews); ?></div>
						<h2>User Reviews (<?php echo $Product->total_reviews; ?>)</h2>
					</div>				
				<?php foreach($Reviews as $r) { 
				if($r->summary=="")
				{
					?>
					<table cellspacing="0" class="user-review-desktop" id="user-review-desktop">
						<tr>
							<td width="390"><div class="user-review-header"><?php echo $r->user_screen_name; ?> &nbsp; <span> [<?php echo $r->date_created; ?>]</span></div>
							<?php if(trim($r->pros) != '') { ?>
								<div class="user-review-similar"><strong>Pros:</strong> <?php echo $r->pros; ?></div>
							<?php } ?>
							<?php if(trim($r->cons) != '') { ?>
								<div class="user-review-similar"><strong>Cons:</strong> <?php echo $r->cons; ?></div>
							<?php } ?>
							<div class="user-review-header">
								<span>Price: <?php echo $r->price; ?> &nbsp; Purchased: [<?php echo $r->product_type; ?>]&nbsp;&nbsp; Year Purchased: <?php echo $r->year_purchased; ?></span><div>
							<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>				
							</td>
							<td valign="top">
								<div class="user-review-rating" >
									<table cellspacing="0">
										<tr>
											<td>OVERALL<br />RATING</td>
											<td class="rate"><?php echo $r->overall_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->overall_rating); ?>" /></td>
										</tr>
										<tr><td colspan="3"><div class="rate-line"></div></td></tr>

									</table>
								</div>
							</td>
						</tr>
					</table>


<!-- for mobile view -->

					<!-- mobile view start -->
					<table cellspacing="0" class="user-review-mobile" id="user-review-mobile" style="display:none;border-bottom: 2px solid #e9e8e4;">
						<tr>
							<td width="100%"><div class="user-review-header"><?php echo $r->user_screen_name; ?> &nbsp; <span> [<?php echo $r->date_created; ?>]</span></div>
							<?php if(trim($r->model) != '') { ?>
								<div class="user-review-similar"><strong>Pros:</strong> <?php echo $r->pros; ?></div>
							<?php } ?>
							<?php if(trim($r->model) != '') { ?>
								<div class="user-review-similar"><strong>Cons:</strong> <?php echo $r->cons; ?></div>
							<?php } ?>

							<div class="user-review-header">
								<span>Price: <?php echo $r->price; ?> &nbsp; Purchased: [<?php echo $r->product_type; ?>]&nbsp;&nbsp; Year Purchased: <?php echo $r->year_purchased; ?></span><div>
							<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>				

							</td>
							</tr>
							<tr>
							<td valign="top" width="100%">
								<div class="user-review-rating" style="float:left;">
									<table cellspacing="0">
										<tr>
											<td>OVERALL<br />RATING</td>
											<td class="rate"><?php echo $r->overall_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->overall_rating); ?>" /></td>

											</tr>
										<tr><td colspan="3"><div class="rate-line"></div></td></tr>
										<!--<tr>
											<td>Value<br />RATING</td>
											<td class="rate"><?php echo $r->value_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->value_rating); ?>" /></td>
										</tr>-->
									</table>
								</div>
							</td>
						</tr>
					</table>
					<!-- mobile view end -->

					
					<?php
				}
				else
				{
					?>	
				
				
				
				
				
				
				
				
				
				
				<!-- desktop view start -->			

					<table cellspacing="0" class="user-review-desktop" id="user-review-desktop">
						<tr>
							<td width="390"><div class="user-review-header"><?php echo $r->user_screen_name; ?> &nbsp; <span><?php echo $r->reviewer_experience; ?> [<?php echo $r->date_created; ?>]</span></div>
							<?php if(trim($r->model) != '') { ?>
								<div class="user-review-similar"><strong>Model Reviewed:</strong> <?php echo $r->model; ?></div>
							<?php } ?>
								<div class="user-review-text">
									<p><?php echo $r->summary; ?></p>
								</div>				
								<?php if(trim($r->customer_service) != '') { ?>
								<div class="user-review-similar">
									<p><strong>Customer Service</strong></p>
									<p><?php echo $r->customer_service; ?></p>
								</div>
							<?php } ?>	
							<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>				
							</td>
							<td valign="top">
								<div class="user-review-rating" >
									<table cellspacing="0">
										<tr>
											<td>OVERALL<br />RATING</td>
											<td class="rate"><?php echo $r->overall_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->overall_rating); ?>" /></td>
										</tr>
										<tr><td colspan="3"><div class="rate-line"></div></td></tr>
										<tr>
											<td>Value<br />RATING</td>
											<td class="rate"><?php echo $r->value_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->value_rating); ?>" /></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
					<!-- desktop view end -->
					
					
					
					<!-- mobile view start -->
					<table cellspacing="0" class="user-review-mobile" id="user-review-mobile" style="display:none;border-bottom: 2px solid #e9e8e4;">
						<tr>
							<td width="100%"><div class="user-review-header"><?php echo $r->user_screen_name; ?> &nbsp; <span><?php echo $r->reviewer_experience; ?> [<?php echo $r->date_created; ?>]</span></div>
							<?php if(trim($r->model) != '') { ?>
								<div class="user-review-similar"><strong>Model Reviewed:</strong> <?php echo $r->model; ?></div>
							<?php } ?>
								<div class="user-review-text">
									<p><?php echo $r->summary; ?></p>
								</div>				
								<?php if(trim($r->customer_service) != '') { ?>
								<div class="user-review-similar">
									<p><strong>Customer Service</strong></p>
									<p><?php echo $r->customer_service; ?></p>
								</div>
							<?php } ?>	
							<div class="user-review-similar"><?php if(trim($r->similar_products) != '') { ?><strong>Similar Products Used:</strong> <?php echo $r->similar_products; ?><?php } ?></div>				
							</td>
							</tr>
							<tr>
							<td valign="top" width="100%">
								<div class="user-review-rating" style="float:left;">
									<table cellspacing="0">
										<tr>
											<td>OVERALL<br />RATING</td>
											<td class="rate"><?php echo $r->overall_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->overall_rating); ?>" /></td>
										</tr>
										<tr><td colspan="3"><div class="rate-line"></div></td></tr>
										<tr>
											<td>Value<br />RATING</td>
											<td class="rate"><?php echo $r->value_rating; ?></td>
											<td><img src="<?php echo cr_rating_image($r->value_rating); ?>" /></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
					<!-- mobile view end -->
				<?php }} ?>		
				<div class="user-reviews-header clearfix">
					<div class="reviews-nav"><?php cr_review_pagination($_GET['p'], $Product->total_reviews); ?></div>
				</div>			
				<?php } ?>
			</div>
		<?php } else { ?> <!-- if no product found -->
		
			Product Page not found.
			
		<?php } ?>
		</div><!-- end product-review -->
	</div><!-- end inner -->
	</div><!-- end content left -->		
<?php get_sidebar(); ?>
<?php get_footer(); ?>
