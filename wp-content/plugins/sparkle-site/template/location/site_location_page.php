<?php
ob_start();
session_start();
error_reporting(0);
require_once(__DIR__.'/../../../wp-config.php');
require_once(__DIR__.'/../../../wp-reviewconfig.php');
mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
mysql_select_db(DB_RNAME);


$con5 = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
												
												
require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


if(isset($_SESSION['nonlogin']))
	{
		//echo "Entered 1";
				if(isset($_COOKIE['userid'])){

					//echo "Entered 2";
					$sqlinsert = mysqli_query($con5, "INSERT INTO reviews (reviewerip, ProductID, date_created, user_screen_name, model, channelid, value_rating, overall_rating, pros, cons, year_purchased, price, product_type, vbulletinid)	VALUES ('".$_SERVER['REMOTE_ADDR']."','".$_SESSION['ProductID']."','".date('Y-m-d H:i:s')."','".$_COOKIE['username']."','".$_SESSION['pname']."','".$_SESSION['channelid']."','','".$_SESSION['rating']."','".$_SESSION['pros']."','".$_SESSION['cons']."','".$_SESSION['year']."','".$_SESSION['price']."','".$_SESSION['purchase']."','".$_COOKIE['userid']."')");
					
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

	site_review_location();
	$meta_desc = "";
	site_review_location_type();
	
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
	get_header(); 
	
	function full_url() {
	
		$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : " http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ;
		return $url;
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
	$('.mari').click(function(){
		var href = $(this).text();
		var url = window.location.href;
		//alert(url);
		var ans = url.split(".html");
		var ans = ans.slice(0,-1)
		var ans = ans +'-review.html?rating='+ href;
		window.location = ans;
		
	});
	
	$('.mari').mouseover(function(){
		var href = $(this).text();
		//alert(href);
		//$("#rating").val(href);
		$("#rating").text(href+"/5");
		
	});
	$('.mari').mouseout(function(){
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
	
	#tableB { display: none; }
	#scoredetails { display: none; }
	
	@media only screen and (max-width: 600px) {
		#productstar{padding:5px !important;}
			#product-review .item-box li {   line-height:normal;}
			#product-review .item-box p {   line-height:normal;}
			.user-reviews-header h2{text-align:center;}
			.reviews-nav{width:100%;}
			.reviews-nav{width:100%;}
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
		<h2><?php echo $Course->product_name; ?> - <?php echo $CourseType['course_type_name']; ?></h2>
		
	<!--------------PRODUCT Over View - Mobile Changes -------------------------------->
		
	<div class="product-overview-mobile" id="product-overview-mobile" style="display:none;">


			     <div class="rightscore" style="float:left;margin-top: 15px;margin-left: 18px;">
						
                     <div class="product-share meta clearfix" style="width:100%;height: 32px;">
                        <div class="pp" style="width:82px;float:left;margin-left:0px !important;">Do you like this course?</div>
                        <div class="sbg f" style="width:74px;float:left"><iframe id="framefb" width="1000px" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no"  src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Course); ?>&amp;send=false&amp;layout=button_count&amp;amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border: none; visibility: visible; width: 65px; height: 20px;"></iframe>
                        <!--<iframe id="framefb" src="http://www.facebook.com/plugins/like.php?href=<?php cr_product_url($Course); ?>&amp;send=false&amp;layout=button_count&amp;amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:21px;" allowTransparency="true"></iframe>-->
                        </div>
                        <div class="sbg" style="width:69px;float:left;    margin-left: -10px;"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php cr_product_url($Course); ?>" data-count="horizontal">Tweet</a>
                            <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                        </div>
                        <div class="sbg l" style="width:69px;float:left"><div class="g-plusone" data-size="standard" data-width="100" data-count="true" data-href="<?php cr_product_url($Course); ?>"></div></div>
                    </div>

                </div>
				
				
				<div id="score" class="score"  style="width:100%; padding:10px; ">
					<div style="width:100% ! important;">	
                       
						<div class="cam1 leftscore" style="margin-bottom: 10px;width:100%; float:left; background-color:#096f00; height:101px; border-radius:10px;">    
							<div style="width:100% ! important;">
								<div class="scoretop" style="width:100%; color:white; padding-top:9px; font-size:15px;font-weight:bold; text-align:center;">REVIEW SCORE</div>
								
								<div class="scoreleft" style=" border-radius: 10px;height: 53px; width: 91%; background-color: #fff; color: #27604d; margin: 10px;">
									<div class="scoreinnerleft" style="width:50%; padding:10px;float:left;">
										<img src="<?php echo cr_rating_image($Course->total_rating); ?>" />
										<p style="padding-top: 5px;font-family: sans-serif;    font-weight: bold;    font-size: 12px;"><span style="" id="overall-count"><u style="text-decoration:none !important;"><?php echo $Course->total_rating_count; ?></u></span> REVIEWS</p>            
									</div>
									<div class="scoreinnerright" style="width:50%; padding:10px;float:left;text-align:right !important;">
										<style> p { margin-bottom:0px!important; }</style>
										<p style="display: inline;font-size:50px;line-height:33px;color:black;font-weight:bold;" id="overall-average"><?php echo $Course->total_rating; ?></p><p style="display: inline;font-size:24px;line-height:24px;color:red;">/5</p>
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
						<?php if(function_exists('cr_site_location_details_mobile')){ cr_site_location_details_mobile(); }?>
					</div>
<!--					
			<?php if($Course->media && count($Course->media)>1) { ?>
			<ul  class="productbxslider"  style="visibility:hidden;max-height:150px;">
			<?php for($i=0;$i<count($Course->media);$i++) { ?>							
				<li style='height:150px !important;'><p align="center"><img class="img-responsive center-block" id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Course->media[$i]->value,'150x150'); ?>" width="150" height="150" /></p></li>
			<?php }?></ul>
			<?php } else { ?>		
				<div id="innerproductimage" style=''><p align="center"><img id="thumb-img-0" class="img-responsive center-block" src="<?php echo cr_product_image($Course->product_image, 'product'); ?>" width="150" height="150" /></p></div>
			<?php } ?>
			
-->
			</td></tr>
					<tr><td colspan="2" style="width:100%;"><center><div style="margin-left: 5px;"><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php 
												error_reporting(0);

												$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
											
												$qry1 = mysqli_query($con, "SELECT * FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['userid']." and productid = ".$Course->productid." ORDER BY `reviewid` DESC limit 1 ");
												
												
												//echo "SELECT * FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['userid']." and productid = ".$Course->productid." ORDER BY `reviewid` DESC limit 1 ";
												
												$count1 = mysqli_num_rows($qry1);
												$res1=mysqli_fetch_assoc($qry1);
													
													if($_COOKIE['username'])
													{
														echo $_COOKIE['username'].'</p>';
													}
													else
													{
														echo '<p style="font-size: 14px;"> Own This?</p>';
													} 

											?>
                        </div></center></td>
            </tr><tr>
            <td colspan="2" style="width:100%;float:right;"><center>
                <div style="float:left;padding:10px;width:25%;">
                <?php 
                    if($count1>0)
														{
															echo '<p style="font-size: 14px;  line-height: 1em !important;"> You rated this:</p>';
															//echo "hi";
														}
														else
														{
															echo '<p style="font-size: 14px; "> Rate It:</p>';
														}
														
                    ?>
                
                </div>
                <div class='imgdiv'></div>
											<div id="productstar" style="float:left;padding:0px;width:50%;">
												<!--<div id="rate-text">&nbsp;</div>-->
												
												<?php 
												
												if($_COOKIE['username'] && $count1 > 0)
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
													
												<ul id="star-rating" class="star-rating-mobile">
												
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
                <div style="float:left;padding:10px;width:25%;"><p style=" color: red;
    font-size: 24px;
    font-weight: bold;
    font-family: helvetica;
    padding-top: 5px;"><?php  if($_COOKIE['username'] && $count1>0){ echo $res1['overall_rating'];}else { echo "?";} ?>/5</p></div>
                
                </center></td></tr>
            <tr>
						<td colspan="2">
					   <center>
											
											<?php  
											if($_COOKIE['username'] && $count1>0)
											{ 
												//echo $_SERVER['REQUEST_URI'];
												$newurl = explode('.',$_SERVER['REQUEST_URI']);
												$final = $newurl[0]."-review.html?rating=".$res1['overall_rating']."&reviewid=".$res1['reviewid'];
												echo "<div><p><a style='    font-weight: normal;    text-decoration: none;    padding: 4px;    background-color: rgb(9, 111, 0);    border-radius: 2px;    color: #fff;' href='".$final."'>Edit</a></p></div>";
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
		.user-review-desktop { border-bottom: 2px solid #e9e8e4; padding: 0 0 10px 20px; margin: 6px 0; width:610px; }
		.user-review-rating { background-color: #e3e3d1; width: 190px; padding-left: 5px; float:right; }
		
	</style>
	
	<div class="product-overview" id="product-overview-desktop" style="display:none;">
		
		<div class="product-share meta clearfix">
			<div class="pp">Do you like this course?</div>
			<div class="sbg f"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo full_url(); ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></div>
			<div class="sbg"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo full_url(); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
			<div class="sbg l"><div class="g-plusone" data-size="medium" data-href="<?php echo full_url(); ?>"></div></div>
		</div>
		
		<table cellspacing="0">
			<tr>			
				<td valign="top" style="padding:0 0 0 5px;">
					<div style='width:410px;height:270px; border:1px solid #b5b9a7;'>
						<?php if(function_exists('cr_site_location_details')){ cr_site_location_details(); }?>
					</div>
					<!--<ul class="image-thumb-list-slider">
						<li class="left-arrow"><a href="#" onclick="return slidePage(1);"></a></li>
						<li class="thumbs"><div id="image-thumbs" class="image-thumbs" style="width:<?php echo count($Course->media) * 70; ?>px;">
							<?php if($Course->media) { for($i=0;$i<count($Course->media);$i++) { ?>
							<div style='width:64px; height:42px; border:1px solid #CCCCCC;'><a href="#" onclick="return showThumb(this,'<?php echo $Course->media[$i]->value; ?>');"><img id="thumb-img-<?php echo $i; ?>" src="<?php echo cr_product_image($Course->media[$i]->value,'80x60',1); ?>" width="64" height="42" /></a></div>
							<?php } } else { ?>
							<div style='width:64px; height:42px; border:1px solid #CCCCCC;'><img id="thumb-img-0" src="<?php echo cr_product_image($Course->product_image,'product',1); ?>" width="64" height="42" /></div>
							<?php } ?>
							</div>
						</li>
						<li class="right-arrow"><a href="#" onclick="return slidePage(<?php echo count($Course->media); ?>);"></a></li>
					</ul>-->
				</td>
				<td valign="top">
					<div class="product-ratings">				
						<div id="product-rating-detail">	
							<div id="product-rating-detail-inner">
								<img src="<?php bloginfo('template_url'); ?>/images/rating-arrow.png" style="position:absolute;top:72px;left:-17px;" />
								<table>
									<tr>
										<td class="dr"><p class="r"><?php echo $Course->average_rating; ?></p> OUT OF 5</td>
										<td><span class="green"><?php echo $Course->total_reviews; ?></span> GolfReviews reviews</td>
									</tr>
									<tr>
										<td class="dr"><p class="r" id="quickrate-average"><?php echo $Course->quickrating_average; ?></p> OUT OF 5	</td>
										<td><span class="green" id="quickrate-count"><?php echo $Course->quickrating_count; ?></span> GolfReviews QIK ratings</td>
									</tr>
									<tr>
										<td class="dr"><p class="r"><?php echo $Course->web_score_rating; ?></p> OUT OF 5 </td>
										<td><span class="green"><?php echo $Course->web_score_count; ?></span> Reviews across the web</td>
									</tr>
								</table>
							</div>
						</div>
						<table cellspacing="0" class="top-rate">
							<tr>
                                <td valign="top" style='width:60%;'>
									<div class="product-score-outer">
										<div class="product-score-box" style='width:170px; height:176.5px;'>
											<p style="padding-bottom:4px;font-size:15px;font-family:arial;">COURSE SCORE</p>
											<div class="product-score-inner">
												<p><span style="font-size:1.4em;" id="overall-count"><u style="text-decoration: none;"><?php echo $Course->total_rating_count; ?></u></span> REVIEWS</p>
												<p style="font-size:44px;color:#333;line-height:44px;" id="overall-average"><?php echo $Course->total_rating; ?></p>
												<p><img src="<?php echo cr_rating_image($Course->total_rating); ?>" /></p>
												<p style="color:#333;">OUT OF 5</p>
											</div>
											<!--<p style="padding-top:4px;font-size:12px;"><a href="#" onclick="return false;" onmouseover="return showRatingDetail();" onmouseout="return hideRatingDetail()">SEE DETAILS >></a></p> -->
										</div>
									</div>
								</td>
                            </tr>
                            <tr>
								<td valign="top" style="margin:0;padding:0;" width='45%'>
									<div><p style="font-weight:bold;    font-size: 22px;    line-height: 0.5em !important;    padding-top: 9px;">
											<?php 
												error_reporting(0);
										
												$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));  
											
												$qry = mysqli_query($con, "SELECT * FROM `reviews` WHERE `vbulletinid`=".$_COOKIE['userid']." and productid = ".$Course->productid." ORDER BY `reviewid` DESC limit 1 ");
												
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
                                            <div><p style="color:red;font-size:24px;" id="rating"><?php  if($_COOKIE['username'] && $count>0){ echo $res1['overall_rating'];}else { echo "?";} ?>/5</p></div>
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
					</div><!-- product-ratings -->				
				</td>
			</tr>
		</table>
		
	</div><!-- end product-overview-desktop -->
		
	<div class="item-box">
		<div class="title"><?php echo $Course->product_name; ?> - <?php echo $Course->city; ?>, <?php echo cr_state_name($Course->state); ?></div>
		<div class="text">
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
	</div><!-- end item box -->
	
	<div id="reviews" class="user-reviews">
		
		<?php if($Reviews) { ?>		
			<div class="user-reviews-header clearfix">
                <h2>User Reviews (<?php echo $Course->total_reviews; ?>)</h2>
				<div class="reviews-nav"><?php cr_review_pagination( $_GET['p'], $Course->total_reviews ); ?></div>
				
			</div>
	<?php foreach($Reviews as $r) { 
        if($r->summary=="")
				{
        ?>
		
	<!-- desktop view start -->
		<table cellspacing="0" class="user-review-desktop" id="user-review-desktop">
				<tr>
					<td>
						<div class="user-review-header">
							<?php echo $r->user_screen_name; ?> &nbsp; <span> [<?php echo $r->date_created; ?>]</span>
						</div>
						<?php if(trim($r->pros) != '') { ?>
							<div class="user-review-similar"><strong>Pros:</strong> <?php echo $r->pros; ?></div>
						<?php } ?>
						<?php if(trim($r->cons) != '') { ?>
							<div class="user-review-similar"><strong>Cons:</strong> <?php echo $r->cons; ?></div>
						<?php } ?>
						<!--<div class="user-review-header">
								<span>Price: <?php if($r->price==""){echo " Nill ";}else{ echo $r->price;} ?> &nbsp; Purchased: [<?php if($r->product_type==""){echo "  Nill ";}else{ echo $r->product_type;} ?>]&nbsp;&nbsp; Year Purchased: <?php if($r->year_purchased==""){echo "  Nill ";}else{ echo $r->year_purchased;} ?></span>
						</div>	-->
						<div class="user-review-similar">
							<?php if(trim($r->similar_products) != '') { ?>
								<strong>Similar Products Used:</strong> 
								<?php echo $r->similar_products; ?>
							<?php } ?>
						</div>					
					</td>
					<td valign="top" >
						<div class="user-review-rating">
							<table cellspacing="0">
								<tr><td>OVERALL<br />RATING</td>
									<td class="rate"><?php echo $r->overall_rating; ?></td>
									<td><img src="<?php echo cr_rating_image($r->overall_rating); ?>" /></td>
								</tr>
								<!--<tr><td colspan="3"><div class="rate-line"></div></td></tr> -->
							</table>
						</div>
					</td>
				</tr>
		</table>
		
		
		
<!-- for mobile view -->






					<!-- mobile view start -->
<div class="user-review-mobile" id="user-review-mobile" style="margin-top:10px; width:100%;display:none;border-bottom: 2px solid #e9e8e4;">
   <div style="width:100%;">
      <div style="width:50%; float:left; height: 25px;" class="user-review-header">
         <?php echo $r->user_screen_name; ?>
		 <br>
		 <span><?php 
			$con6 = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Some error occurred during connection " . mysqli_error($con));  
											
			$qry6 = mysqli_query($con6, "SELECT * FROM `user_profile_answers` WHERE `userid`=".$r->vbulletinid." and questionid = 1");
												
			$count1 = mysqli_num_rows($qry6);
			$res6=mysqli_fetch_assoc($qry6);			
			
			
			
			//echo $r->vbulletinid; 
			
			echo $res6['answer'];
		?></span>
      </div>
      <div style="width:50%; float:left;text-align: right;height: 25px;">
	  <br>
         <span>[<?php echo $r->date_created; ?>]</span>
      </div>
   </div>
   
	<div style="height:40px; width:100%"></div>
   <?php if(trim($r->pros) != '') { ?>
   <div style="width:100%;   float:left;" class="user-review-similar">
      <strong>Pros: </strong> <?php echo $r->pros; ?>
   </div>
   <?php } ?>
   <?php if(trim($r->cons) != '') { ?>
   <div style="width:100%; float:left;" class="user-review-similar">
      <strong>Cons: </strong> <?php echo $r->cons; ?>
   </div>
   <?php } ?>
   
<!--   
   <div style="width:100%;margin-bottom:10px;">
      <div style="width:27%; float:left;">
         Price :  <?php if($r->price==""){echo " Nill ";}else{ echo $r->price;} ?>
      </div>
      <div style="width:31%; float:left;">
         Purchased: <?php if($r->product_type==""){echo "  Nill ";}else{ echo $r->product_type;} ?>
      </div>
      <div style="width:42%; float:left;">
         Year Purchased: <?php if($r->year_purchased==""){echo "  Nill ";}else{ echo $r->year_purchased;} ?>
      </div>
   </div>
-->   
   
   <div style="height:10px;"></div>
   <?php if(trim($r->similar_products) != '') { ?>
   <div style="width:100%; float:left;">
      <strong>Similar Products Used:</strong> 
      <?php echo $r->similar_products; ?>
   </div>
   <?php } ?>
   <div style="width:100%;">
      <center>
         <table cellspacing="0" class="user-review-rating" style="float:none; background-color: #e3e3d1;">
            <tr>
               <td>
                  OVERALL<br />RATING
               </td>
               <td class="rate">
                  <?php echo $r->overall_rating; ?>
               </td>
               <td>
                  <img src="<?php echo cr_rating_image($r->overall_rating); ?>" />
               </td>
            </tr>
         </table>
      </center>
   </div>
</div>





		
		
        <?php
                                  }
                            else 
                            {
                                ?>
        
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
						<div class="user-review-mobile" id="user-review-mobile" style="margin-top:10px; width:100%;display:none;border-bottom: 2px solid #e9e8e4;">
						   <div style="width:100%;">
							  <div style="width:50%; float:left; height: 25px;" class="user-review-header">
								 <?php echo $r->user_screen_name; ?> <br><span><?php echo $r->reviewer_experience; ?></span>
							  </div>
							  <br>
							  <div style="width:50%; float:left;text-align: right;height: 25px;">
								 <span>[<?php echo $r->date_created; ?>]</span>
							  </div>
						   </div>
						   <?php if(trim($r->model) != '') { ?>
						   <div style="width:100%; float:left;" class="user-review-similar">
							 <strong>Model Reviewed:</strong> <?php echo $r->model; ?>
						   </div>
						   <?php } ?>

						   
						   <div style="width:100%; float:left;" class="user-review-similar">
								<div class="user-review-text">
									<p><?php echo $r->summary; ?></p>
								</div>				
						   </div>
						   
						   <?php if(trim($r->customer_service) != '') { ?>
						   <div style="width:100%; float:left;" class="user-review-similar">
							 <strong>Customer Service: </strong> <?php echo $r->customer_service; ?>
						   </div>
						   <?php } ?>
						   
						   
						   

						   <div style="height:10px;"></div>
						   <?php if(trim($r->similar_products) != '') { ?>
						   <div style="width:100%; float:left;">
							  <strong>Similar Products Used:</strong> 
							  <?php echo $r->similar_products; ?>
						   </div>
						   <?php } ?>
						   <div style="width:100%;">
							  <center>
								 <table cellspacing="0" class="user-review-rating" style="float:none; background-color: #e3e3d1;">
									<tr>
									   <td>
										  OVERALL<br />RATING
									   </td>
									   <td class="rate">
										  <?php echo $r->overall_rating; ?>
									   </td>
									   <td>
										  <img src="<?php echo cr_rating_image($r->overall_rating); ?>" />
									   </td>
									</tr>
									<tr><td colspan="3"><div class="rate-line"></div></td></tr>
									<tr>
										<td>Value<br />RATING</td>
										<td class="rate"><?php echo $r->value_rating; ?></td>
										<td><img src="<?php echo cr_rating_image($r->value_rating); ?>" /></td>
									</tr>
								 </table>
							  </center>
						   </div>
						</div>








					
					<!-- mobile view end -->
		<?php }} ?>
		<div class="user-reviews-header clearfix">
			<div class="reviews-nav"><?php cr_review_pagination($_GET['p'], $Course->total_reviews); ?></div>
		</div>			
		<?php } ?>	
	</div>		
	<?php } else { ?>
		<!-- if no product found -->
		Page not found.
	<?php } ?>
	</div><!-- end product-review -->
</div><!-- end inner -->
</div><!-- end content left -->		
<?php get_sidebar(); ?>
<?php get_footer(); ?>
