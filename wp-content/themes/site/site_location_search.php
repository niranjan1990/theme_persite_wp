<?php /* Template Name: Location Search Landing Page */ ?>
<?php
	
	$CourseList =  (object)array();
	if(is_numeric(trim($_GET['zip']))) {
		
		header("Location: /golf-courses/".trim($_GET['zip']).".html");
		die();
	} 
	else if(trim($_GET['search']) == '') {
		
		header("Location: /golf-courses.html");
		die();		
	}
	
	golfreview_course_search($_GET); 
	get_header();
?>
	<div id="content-left" class="col-sm-8">
		<div class="inner">
			<div class="main-content">
				<div id="golf-courses">
					<h2>SEARCH RESULTS FOR: <span class="lwr"><?php echo stripslashes($_GET['search']); ?></span></h2>
					<?php if($Courses) { ?>
						<div id="products" class="product-list">
							<div class="product-list-nav">Showing <?php echo number_format($CourseList->list_count); ?> Golf Course Results </div>
							<table cellspacing="0">
								<tr><th>Golf Course Name</th><th>City</th><th align="center" colspan="2">Reviews</th></tr>
	
								<?php foreach($Courses as $Course) { ?>
									<tr>
										<td class="pn"><a href="<?php cr_location_url($Course,$Course->url_safe_product_name); ?>"><?php echo $Course->product_name; ?></a></td>
										<td class="brand"><a href="<?php cr_location_city_url($Course->state, $GRclass->parse_city_permalink($Course->city)); ?>"><?php echo $GRclass->parse_city_name($Course->city); ?></a></td>
										<td class="rate"><?php echo $Course->combined_average; ?> of 5</td>
										<td class="rev">
											<div><a href="<?php cr_location_url($Course,$Course->url_safe_product_name); ?>#reviews"><?php echo $Course->total_reviews; ?> Reviews</a></div>
											<img src="<?php echo cr_rating_image($Course->combined_average); ?>" />
										</td>
									</tr>
								<?php } ?>	
							</table>		
							<div class="product-list-nav"> Showing <?php echo number_format($CourseList->list_count); ?> Golf Course Results </div>
						</div>	
					<?php } else { ?>
					
					<p class="subheadertext">No golf courses found, please try searching again, or <a href="<?php bloginfo('url') ?>/golf-courses.html"><u>search by State</u></a>.</p>
					<!--<div class="course-search">			
						<form method="get" action="<?php bloginfo('url') ?>/golf-course-search.html">
							<table cellspacing="0">
								<tr><td><img src="<?php bloginfo('template_url'); ?>/images/find-course.png" /></td></tr>
								<tr><td class="txt">Enter Golf Course Name &nbsp;<input type="text" name="search" /></td></tr>
								<tr><td>or</td></tr>
								<tr><td class="txt">Enter Zip Code &nbsp;<input type="text" name="zip" style="width:100px;" /></td></tr>
								<tr><td><input class="sub" type="submit" value="Find Golf Course" /></td></tr>
							</table>
						</form>
					</div>-->
				<?php } ?>	

<?php cr_site_deals_widget(1); ?>
	<!--		
		<div class="text-header"><h2>MOST RECENT ARTICLES & REVIEWS</h2></div>				
			<?php query_posts('posts_per_page=5'); ?>
			<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
				<div class="item-list-box clearfix">
					<div class="type"><img src="<?php bloginfo('template_url') ?>/images/item-list-img.png" /></div>
					
					<div class="item-box">
						<div class="title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></div>
						<div class="meta"><div class="comments"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></div>
							by <?php the_author() ?> &nbsp; <?php the_time('F d, Y') ?></div>
						<div class="text"><?php the_excerpt() ?></div>
					</div>
				</div>

			<?php endwhile; endif; ?>
			<?php wp_reset_query(); ?>				
		<div class="see-more text-right"><a class="arrow-right" href="/reviews.html">see all</a></div>-->
	</div>
	</div><!-- end main content -->			
</div><!-- end inner -->
</div><!-- end content left -->	
<?php get_sidebar(); ?>
<?php get_footer(); ?>
