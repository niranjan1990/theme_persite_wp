<?php
	/**
	 * The template for displaying Site Review Brand Page.
	 *
	 * @package WordPress
	 * @subpackage Site Review
	 * @since Site Review 1.0
	 */
 
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
	global $Brand;

	site_review_product_brand_list();
	set_site_review_location('manufacturer-pages');
	set_site_review_meta_vars($Brand->manufacturer_name, $Brand->product_count);
	get_header();
?>
	<div id="content-left" class="col-sm-8">
		<div class="inner">
			<div class="main-content">
			<h2><?php echo $Brand->manufacturer_name; ?></h2>
			<div class="golf-clubs"><?php
		
				$tag = $Brand->url_safe_manufacturer_name . '-featured';
				query_posts('tag='.$tag.'&posts_per_page=1');	
				if( have_posts() ) { while ( have_posts() ) { the_post(); ?>
				
				<div id="article-page" class="article-page clearfix">
					<div class="item-box">
					<h1 class="title lwr"><?php the_title(); ?></h1>
						<div class="text"><?php the_excerpt(); ?></div>
						<div class="text-right"><a class="arrow-right" href="<?php the_permalink(); ?>">read more</a> &nbsp;</div>
						
						<div class="product-share meta clearfix">
							<div class="sbg f"><iframe src="http://www.facebook.com/plugins/like.php?<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe></div>
							<div class="sbg"><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
							<div class="sbg"><div class="g-plusone" data-size="medium" href="<?php the_permalink(); ?>"></div></div>
							<div class="sbg cc l">
								<a href="<?php the_permalink(); ?>#comments"><img style="margin-bottom:-4px;" src="<?php bloginfo('template_url') ?>/images/comments-icon.png" /> <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></a>
							</div>
						</div>
					</div>
				</div>
				<?php } } ?>
			</div><?php			
			/*** If brand products exist, list all products */
			if($Products) {	?>			
			<div id="products" class="product-list">
			
					<div class="product-list-nav clearfix"><div style="float:right;"><?php cr_brand_pagination($Brand,$_GET['page']); ?></div>
						<div style="float:right;">Showing <?php cr_product_count_showing($Brand,$_GET['page']); ?> of <?php echo $Brand->product_count; ?> <!--<?php echo $Brand->manufacturer_name; ?> Products--></div>
					</div>					
					<table id="mobiletable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none;">
						<thead></thead>
						<?php foreach($Products as $Product) { ?>
						<tbody style="border: 1px solid black;">
						<tr class='clickable-row' align="center" data-href="<?php cr_product_url($Product); ?>">
						<td><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image,'product'); ?>" width="94" height="80" /></td>
							<td><ul class="list-unstyled">
								<li style="font-size: 15px;font-weight: bold;"><?php echo $Product->product_name; ?>&nbsp;<?php echo $Product->category_name; ?></li>
								<li><a href="<?php cr_product_url($Product); ?>"><?php echo $Product->total_rating_count; ?> Reviews</a></li>
								<li><?php echo $Product->total_rating; ?> of 5</li>
								<li><img src="<?php echo cr_rating_image($Product->total_rating); ?>" /></li>
							</ul></td></tr>
						</tbody>
						<?php } ?>
					</table>
					<table id="desktoptable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
						<thead></thead>
						<?php foreach($Products as $Product) { ?>
							<tbody style="border: 1px solid black;">
								<tr class='clickable-row' align="center" data-href="<?php cr_product_url($Product); ?>">
								<td><img id="thumb-img-0" src="<?php echo cr_product_image($Product->product_image,'product'); ?>" width="94" height="80" /></td>
								<td><ul class="list-unstyled">
								<li style="font-size: 15px;font-weight: bold;"><?php echo $Product->product_name; ?>&nbsp;<?php echo $Product->category_name; ?></li>
								</ul></td><td><ul class="list-unstyled"><li><a href="<?php cr_product_url($Product); ?>"><?php echo $Product->total_rating_count; ?> Reviews</a></li>
								<li><?php echo $Product->total_rating; ?> of 5</li>
								<li><img src="<?php echo cr_rating_image($Product->total_rating); ?>" /></li></ul></td>
								</tr>
							</tbody>
						<?php } ?>
					</table>				
				
				<div class="product-list-nav clearfix"><div style="float:right;"><?php cr_brand_pagination($Brand,$_GET['page']); ?></div>
					<div style="float:right;">Showing <?php cr_product_count_showing($Brand,$_GET['page']); ?> of <?php echo $Brand->product_count; ?> <!--<?php echo $Brand->manufacturer_name; ?> Products--></div>
				</div>
				
			</div>
		<?php } ?><!-- end product list -->	
		
	<!--<?php cr_site_deals_widget(); ?>-->
	<div id="featured-golf-deals" class="clearfix">
	<div class="lwr">Featured Golf Deals 
	<span><a class="arrow-right" href="/golf-deals.html">see all</a>
	</span>
	</div>
	<div style="clear: both;"></div>
	</div>
	
	<!--Hard Coding -->
	<div class="bxslider-wrap" style="visibility: hidden;">
	<ul class="bxslider">
	<li>	
		<table class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
		<thead></thead>
		<tbody style="border: 1px solid black;">
		<tr align="center">
			<td align="center" style="white-space: nowrap; height:80;">
				<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917965&amp;referrer=4TargetedCategoryHotdeal" target="_blank">                <img border="0" src="http://crev.vo.llnwd.net/o42/golfreview/images/HotDeals/InTheHoleGolf.com_golf_TrueLinkswear_TrueLytBreatheGolfShoes-MensGreyHighlighter.jpg"></a>
			</td>
			<td><ul class="list-unstyled">
			<li style="font-size: 15px;font-weight: bold;"><b>True Linkswear</b></li>
			<li><b>GPS Golf Watch with $50 REBATE</b></li>
			<li><font color="#CC0000"><b>$99.95</b></font></li>
			<li><strike>$129.95</strike></li>
			<li><a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917965&amp;referrer=4TargetedCategoryHotdeal" target="_blank">InTheHoleGolf.com</a> </li>
			</ul></td></tr>
		</tbody>
		</table></li>
		<li><table class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
			<thead></thead>
			<tbody style="border: 1px solid black;">
			<tr align="center">
				<td align="center" style="white-space: nowrap; height:80;">
					<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917964&amp;referrer=4TargetedCategoryHotdeal" target="_blank"><img border="0" src="http://crev.vo.llnwd.net/o42/golfreview/images/HotDeals/InTheHoleGolf.com_golf_Garmin_ApproachS6GPSGolfWatchwith$50REBATE.jpg"></a>
				</td>
				<td><ul class="list-unstyled">
				<li style="font-size: 15px;font-weight: bold;"><b>Garmin Approach S6</b></li>
				<li><b>GPS Golf Watch with $50 REBATE</b></li>
				<li><font color="#CC0000"><b>$399.99</b></font></li>
				<li><strike>$399.99</strike></li>
				<li><a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917964&amp;referrer=4TargetedCategoryHotdeal" target="_blank">InTheHoleGolf.com</a></li>
				</ul></td>
			</tr></tbody>
			</table>
		</li>
		<li><table class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
		<thead></thead>
		<tbody style="border: 1px solid black;">
		<tr align="center">
			<td align="center" style="white-space: nowrap; height:80;">   
			<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917962&amp;referrer=4TargetedCategoryHotdeal" target="_blank">                <img border="0" src="http://crev.vo.llnwd.net/o42/golfreview/images/HotDeals/InTheHoleGolf.com_golf_NikeGolf_VR_SCovert2.0Driver.jpg">              </a>            
			</td>
			<td><ul class="list-unstyled">
			<li style="font-size: 15px;font-weight: bold;"><b>Nike Golf</b></li>
			<li><b>VR_S Covert 2.0 Driver</b></li>
			<li> <font color="#CC0000"> <b>$169.95</b></font> </li>
			<li><strike>$299.95</strike></li>
			<li>	<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917962&amp;referrer=4TargetedCategoryHotdeal" target="_blank">InTheHoleGolf.com</a>                    </li>
			</ul></td>
		</tr>
		</tbody>
		</table> </li>
		<li><table class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
		<thead></thead>
		<tbody style="border: 1px solid black;">  	        
		<tr align="center">
			<td align="center" style="white-space: nowrap; height:80;">   
				<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917963&amp;referrer=4TargetedCategoryHotdeal" target="_blank">                <img border="0" src="http://crev.vo.llnwd.net/o42/golfreview/images/HotDeals/InTheHoleGolf.com_golf_WilsonGolf_FGTourM3Hybrid.jpg">              </a>                  
			</td>
			<td><ul class="list-unstyled">
			<li style="font-size: 15px;font-weight: bold;"> <b>Wilson Golf</b>   </li>
			<li><b>FG Tour M3 Hybrid</b>    </li>
			<li> <font color="#CC0000">                <b>$59.95</b>              </font> </li>
			<li><strike>$199.95</strike></li>
			<li>	<a href="http://www.golfreview.com/commerceredirect.aspx?linkid=3917963&amp;referrer=4TargetedCategoryHotdeal" target="_blank">InTheHoleGolf.com</a>                       </li>
			</ul></td></tr>
		</tbody>
		</table></li>
	</ul>
	</div>
	<div class="related-articles"><?php $term_cat[] = $Brand->manufacturer_name;
		site_review_recent_articles('Recent Articles & Reviews on '.$Brand->manufacturer_name, $term_cat, 5); ?>
	</div>
	
	</div><!-- end main content -->
	</div><!-- end inner -->
	</div><!-- end content left -->
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
