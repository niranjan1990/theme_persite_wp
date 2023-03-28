<?php global $Brand, $GRclass, $Categories,$Product,$Category,$Products; ?>
<?php $pageid=get_page_template_slug( $post_id ); ?>
<?php $parts1 = explode( '/', $_SERVER['REQUEST_URI']); ?>
<?php $parts2 = explode( '.html', $parts1[1] ); ?>
<?php $url = ucwords ( str_replace('-',' ', $parts2[0] ) ); ?>
<?php if(function_exists('site_review_category_list')){ site_review_category_list(); } /* get and return object array of all categories */ ?>
<?php $Category =  (object)array(); ?>
<?php $Category->product_count	= 0; /* initializing value 0  to product_count */ ?>
<?php $Category->category_name 	= $url; // Golf Clubs ?>
<?php $Category->url_safe_category_name  = $parts2[0]; // golf-clubs ?>
<?php set_site_review_location(); ?>
<?php site_review_location_type();  /* get and return object array of all courses */ ?>
<?php include(__DIR__.'/../../../wp-reviewconfig.php'); ?>
<?php include(__DIR__.'/../../../wp-config-extra.php'); ?>

	<div id="content-right" class="col-sm-4" >

		<?Php
			if(function_exists('Get_NodeID_By_Path')){
			$NODEID = Get_NodeID_By_Path( $Category->url_safe_category_name );
					}

			for($i=0;$i<count($Categories);$i++) { /* NODEID is also database driven */

				if(strpos($Categories[$i]->nodeid, $NODEID ) !== false && $Categories[$i]->node_level == 3 && number_format( $Categories[$i]->product_count) != 0) { ?>
					<?Php $CatName = strtolower( $Categories[$i]->category_name ); ?>
					<?Php $CatName = str_replace('&', 'and', $CatName ); ?>
					<?Php $BrandList[] = str_replace(' ', '-', $CatName ); //print_r($BrandList);?>
					<?Php $Category->product_count += $Categories[$i]->product_count; ?>
				<?Php } } ?>




<?php  if($AUTOSEARCH==1){ ?>
		<div id="searchwidget">
		<div class="content">




<!-- Siva for form autocomplete -->




		<!--<h3><b>Review your Products</b></h3>-->
		<?php
			//$output = array();
			//foreach($BrandList as $term){
			 // $output[] = $term;
			//}
			//$mari="'" . implode("','", $output) . "'";
		?>

		<style>
		.tt-suggestion
		{
			padding:7px!important;
		}
		</style>
















<!-- New Ajax Search -->

		<!-- Load CSS -->
		<link href="<?php echo "/ajaxsearch/style/style.css".$CSS_VERSION; ?>" rel="stylesheet" type="text/css" />
		<!-- Load Fonts -->
		<!-- Load jQuery library -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<!-- Load custom js -->

		<script type="text/javascript" src="<?php echo "/ajaxsearch/scripts/custom.js".$CSS_VERSION; ?>"></script>


<script>
jQuery(document).ready(function(){
    jQuery('#hideshow').on('click', function(event) {
         jQuery('#autosearchbar').toggle('show');
		 jQuery("body").removeClass("shiftnav-open");
		 jQuery(".shiftnav-left-edge").removeClass("shiftnav-open-target");

    });
});
</script>


<!-- New Ajax Search -->






		<?php

		global $url1;
		//echo $url1[0];
	?>








		<div class="searchwidget" style="width:95%;margin-bottom: 15px;">
		<form method="post">
		<input type="hidden" name="brands" id="brands" value=""/>
		<input type="hidden" name="cate" id="cate" value=""/>
		<input type="hidden" name="category" id="category" value="<?php echo $mari;?>"/>
		<input type="hidden" name="trailsorbike" id="trailsorbike" value="<?php
		if ( is_home() ) {

    echo "golf-clubs";
} else {

    print_r($parts2[0]);
}
?>"/>

		<?php
		global $crrp;
		if($parts2[0] == "golf-courses")
		{
			$crrp = "golf-courses";
		}
		else if($parts2[0] == "brands")
		{
			$crrp = "brands";
		}
		//echo $crrp;
		$newparts = explode( '/', $_SERVER['REQUEST_URI']);
		$newpartscount = count($newparts);
		switch($crrp)
		{
			case "HOMEPAGE":
					$newurl = "";
					break;
			case "golf-courses":
					$newurl = "golf-courses";
					break;
			case "SuperCat":
					$newpartswithouthtml = explode( '.', $newparts[$newpartscount - 1]);
					$newurl = $newpartswithouthtml[0];
					break;
			case "brands":
					$newpartswithouthtml = explode( '.', $newparts[$newpartscount - 1]);
					$newurl = $newpartswithouthtml[0];
					break;
			case "Cat":
					$newpartswithouthtml = explode( '.', $newparts[$newpartscount - 1]);
					$newurl = $newpartswithouthtml[0];
					break;
			case "PRD":
					$newpartswithouthtml = explode( '.', $newparts[$newpartscount - 3]);
					$newurl = $newpartswithouthtml[0];
					break;
			default: $newurl = "";
				break;
		}


		?>


				<input type="hidden" name="brandurl" id="brandurl" value="<?php if($parts2[0] == "brands"){ echo "brands";} ?>"/>

				<input type="text" required name="searchkey" id="searchkey" data-brand="111" size="30" class="productss" placeholder="Enter Search Term..."  value="" autocomplete="off">
				<input type="hidden" name="urlvalue" id="urlvalue" value="<?php echo $newurl; ?>" >


		<ul id="results" style="    position: absolute;    z-index: 500000;background-color: whitesmoke;"></ul>



		</form>

		</div>
		</div>

		</div>

<?php } ?>

		<div class="inner">

			<!--top side bar ad 300*250 -->
			<div class="sidebar-non-sticky">
			<?php if( Live_Ads()) {
				if(is_home()){
				// A second sidebar for widgets, just because.
				if ( is_active_sidebar( 'secondary-sidebar-topwidget-area' ) ) : ?>
					<div id="secondary" class="widget-area" role="complementary">
						<ul class="xoxo"><?php dynamic_sidebar( 'secondary-sidebar-topwidget-area' ); ?></ul>
					</div><!-- #secondary .widget-area -->
			<?php endif;
				}else{
				?>

<?php if ( is_active_sidebar( 'secondary-sidebar-topwidget-area-ros' ) ) : ?>
					<div id="secondary" class="widget-area" role="complementary">
						<ul class="xoxo ros"><?php dynamic_sidebar( 'secondary-sidebar-topwidget-area-ros' ); ?></ul>
					</div><!-- #secondary .widget-area -->
			<?php endif;

			 } ?>
			<?php } else { ?>
				<div class="sidebox">
					<div class="ad-box">
						<a href="#"><img src="<?php bloginfo('template_url') ?>/images/ad-img2.png" /></a>
					</div>
				</div>
			<?php } ?>


			<!--End-->














			<!-- hot deals -->

			<?php if($SITE_NAME=="mtbr" || $SITE_NAME=="roadbikereview" || $SITE_NAME=="golfreview" || $SITE_NAME=="audioreview")
				{
					global $GRclass,$locnew,$crrp; //echo $GRclass->get_site_review_location();

				?>

					<div class="mcl-logo">
					 <h3 id="title-bar" style="width: 304px;background: #000;font: 16px 'Oswald',sans-serif;text-transform: uppercase; height: 34px;">
						<a href="/hot-deals.html" style="color:#fff !important;margin: 5px 10px 5px;float:left;">Hot Deals</a>
						<a href="/hot-deals.html" style="color:#fff !important;margin: 8px 11px 6px 6px;float:right;font-size: 11px;">
						  See All Hot Deals &gt;&gt;
						</a>
					  </h3>
					</div>


					<div id="side-bar-box" style="border:none;margin-bottom:15px;width: 304px;">

						<?php
						require_once(__DIR__.'/../../../wp-reviewconfig.php');
						$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));
						$PSite = explode(":", get_option( 'PSiteName' ) );
						global  $cat_id;
						if(isset($cat_id) && $cat_id > 0)
						{
								$loc_sql = "AND locate('$cat_id',venice_ids) > 0 order by locate('$cat_id',venice_ids), rand()";
						}
						else
						{
								$loc_sql = "order by rand()";
						}


						$sql2 = "SELECT LinkID, Link_Name, Graphic, Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date And end_date Order by rand() LIMIT 3";

						$sql1 = "SELECT LinkID, Link_Name, Graphic, Partner_Product_Text, Sale_Price, Original_Price, cp.Partner_Graphic, c.valid, l.valid, c.expired FROM partner_campaigns c JOIN partner_links l  On c.CampaignID = l.CampaignID JOIN commerce_partners cp  On cp.PartnerID = c.PartnerID WHERE campaign_type = 25 And l.channelid = $PSite[0] and c.valid = 1 and l.valid = 1 and curdate() Between start_date And end_date $loc_sql LIMIT 3";



						if ( !is_home() )
						{

							$query=mysqli_query($con,$sql1);

							$numberofrows = mysqli_num_rows($query);

							if ($numberofrows == 0)
							{

								$query=mysqli_query($con,$sql2);
							}

								while($res1=mysqli_fetch_assoc($query))
								{

  $pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
$pnewimg3 = $pcdn.$res1['Partner_Graphic'];
											$newimg1 = explode('/',$res1['Graphic']);
											$newimg2 = count($newimg1);
$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
$newimg3 = $cdn.$newimg1[$newimg2-1];



										switch ($crrp)
										{
											case "HOMEPAGE":
												$referrer="HD_Homepage_Sidebar";
												break;
											case "SuperCat":
												$referrer="HD_SuperCat_Sidebar";
												break;
											case "Cat":
												$referrer="HD_Cat_Sidebar";
												break;
											case "PRD":
												$referrer="HD_PRD_Sidebar";
												break;
											case "MFR":
												$referrer="HD_MFR_Sidebar";
												break;
											default:
												$referrer="HD_Sidebar";
										}
										?>

										<table class="new-hot-deals-sidebar floatleft">
											<tbody>
												<tr class='clickable-row' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $res1['LinkID']; ?>&referrer=<?php echo $referrer; ?>" style="height:130px;">
													<td valign="top" class="middlecol" style="    width: 190px;    padding-top: 10px;    padding-left: 10px;">
														<h4 style="    font-size: 13px;    font-weight: bold;    color: #333333 !important;    padding: 0;    margin: 0 0 2px 0 !important;min-height : 40px;">
															<?php echo $res1['Partner_Product_Text']; ?>
														</h4>
														<div class="new-hot-deals-text" style="    font-size: 12px;    font-weight: bold;    color: #000;">
															<span class="old-price">(was <strike><?php echo $res1['Original_Price']; ?></strike>)</span>
															<span class="hotdeals-price"><?php echo $res1['Sale_Price']; ?></span>
															<span class="hotdeal-link_name">
																<img class="hotdeals-logo" style="width: 110px;margin-top: 5px;" src="<?php echo $pnewimg3;?>">
															</span>
														</div>
													</td>
													<td valign="top" class="firstfirstcol" style="padding-right: 10px;padding-bottom: 10px;padding-top: 10px;vertical-align: top !important;width: 90px; position : relative;">
														<img class="new-hot-deals-img" style="    width: 80px;    height: 80px;    padding-left: 5px;" src="<?php echo $newimg3;?>">
														<br>
														<div class="hotdeal-buy-all-mer" style="position: absolute; bottom: 10px;">Buy Now</div>
													</td>
												</tr>
											</tbody>
										</table>
								<?php
								} //END WHILE

						} //END IF
						else
						{

							// From session
							$numbers = range(0,count($_SESSION['hotdeals'])-1);
							shuffle($numbers);
							for ($x = 0; $x <= 2; $x++)
							{
								$rand_num = $numbers[$x];



$pcdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER;
$pnewimg3 = $pcdn.$_SESSION['hotdeals'][$rand_num]['Partner_Graphic'];
											$newimg1 = explode('/',$_SESSION['hotdeals'][$rand_num]['Graphic']);
											$newimg2 = count($newimg1);
$cdn = $PRODUCTIMAGE_CDNDOMAIN."/".$PRODUCTIMAGE_S3FOLDER."/images/HotDeals/";
$newimg3 = $cdn.$newimg1[$newimg2-1];



									global $crrp;

										switch ($crrp)
										{
											case "HOMEPAGE":
												$referrer="HD_Homepage_Sidebar";
												break;
											case "SuperCat":
												$referrer="HD_SuperCat_Sidebar";
												break;
											case "Cat":
												$referrer="HD_Cat_Sidebar";
												break;
											case "PRD":
												$referrer="HD_PRD_Sidebar";
												break;
											case "MFR":
												$referrer="HD_MFR_Sidebar";
												break;
											default:
												$referrer="HD_Sidebar";
										}
							?>
										<table class="new-hot-deals-sidebar floatleft">
											<tbody>
												<tr class='clickable-row' target="_blank" data-href="/commerceredirect.html?linkid=<?php echo $_SESSION['hotdeals'][$rand_num]['LinkID']; ?>&referrer=<?php echo $referrer; ?>" style="height:130px;">
													<td valign="top" class="middlecol" style="    width: 190px;    padding-top: 10px;    padding-left: 10px;">
														<h4 style="    font-size: 13px;    font-weight: bold;    color: #333333 !important;    padding: 0;    margin: 0 0 2px 0 !important; min-height: 40px;">
															<?php echo $_SESSION['hotdeals'][$rand_num]['Partner_Product_Text']; ?>
														</h4>
														<div class="new-hot-deals-text" style="    font-size: 12px;    font-weight: bold;    color: #000;">
															<span class="old-price">(was <strike><?php echo $_SESSION['hotdeals'][$rand_num]['Original_Price']; ?></strike>)</span>
															<span class="hotdeals-price"><?php echo $_SESSION['hotdeals'][$rand_num]['Sale_Price']; ?></span>
															<span class="hotdeal-link_name">
																<img class="hotdeals-logo" style="width: 110px;margin-top: 5px;" src="<?php echo $pnewimg3;?>">
															</span>
														</div>
													</td>
													<td valign="top" class="firstfirstcol" style="padding-right: 10px;padding-bottom: 10px;padding-top: 10px;vertical-align: top !important;width: 90px;position : relative;">
														<img class="new-hot-deals-img" style="    width: 80px;    height: 80px;    padding-left: 5px;" src="<?php echo $newimg3;?>">
														<br>
														<div class="hotdeal-buy-all-mer" style="position: absolute; bottom: 10px;">Buy Now</div>
													</td>
												</tr>
											</tbody>
										</table>
							<?php
							}

						}
						?>
				</div>
		<?php
		} // SITE_NAME
		?>






















			<?php //if(function_exists('cr_product_partner_links')){ cr_product_partner_links(); }?>
			<?php if( Live_Ads()) { // A second sidebar for widgets, just because.

				if(is_home()){

				if ( is_active_sidebar( 'sidebar-widget-area' ) ) : ?>
				<div id="secondary" class="widget-area" role="complementary">
					<ul class="xoxo"><?php dynamic_sidebar( 'sidebar-widget-area' ); ?></ul>
				</div><!-- #secondary .widget-area -->
			<?php endif;
				}else{ ?>

			<?php	if ( is_active_sidebar( 'sidebar-widget-area-ros' ) ) : ?>
				<div id="secondary" class="widget-area" role="complementary">
					<ul class="xoxo ros"><?php dynamic_sidebar( 'sidebar-widget-area-ros' ); ?></ul>
				</div><!-- #secondary .widget-area -->
			<?php endif;
			 } }
			else { ?>
			<div class="sidebox">
				<div class="ad-box"><a href="#"><img src="<?php bloginfo('template_url') ?>/images/ad-img.png" /></a></div>
			</div>
		<?php } ?>

	</div>
<div class="sidebar-sticky">
<?php if( Live_Ads()) {
	// A second sidebar for widgets, just because.
	if ( is_active_sidebar( 'secondary-sidebar-widget-area' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<ul class="xoxo"><?php dynamic_sidebar( 'secondary-sidebar-widget-area' ); ?></ul>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>
<?php } else { ?>
	<div class="sidebox">
		<div class="ad-box">
			<a href="#"><img src="<?php bloginfo('template_url') ?>/images/ad-img2.png" /></a>
		</div>
	</div>
<?php } ?>
</div>



	</div><!-- end inner -->

	</div><!-- end content right -->
