<?php
	/**
	 * The template for displaying site Review location listing by City or ZIP
	 *
	 * @package WordPress
	 * @subpackage SITEReview
	 * @since Site Review 1.0
	 */
 
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

	site_review_location_listing();
	set_site_review_location('course-listing');
	set_site_review_meta_vars($CourseList->state, $CourseList->city, $CourseList->list_count, $CourseList->zip);
	get_header();

?>
	<div id="content-left" class="col-sm-8">
	<div class="inner">			
		<div class="main-content">
		<div id="golf-courses">
		<h2><?php echo $CourseType['course_type_name']; ?> <span class="lwr">in <?php echo $CourseList->location; ?> (<?php echo number_format($CourseList->list_count); ?>)</span></h2>
		<?php if($Courses) { ?>	
			<p class="subheadertext">Select a <?php echo $CourseType['singuler_type_name']; ?> below to view the details</p>
			<div id="products" class="product-list">
				<div class="product-list-nav">
					Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo $CourseList->location; ?>
				</div>
				<table id="mobiletable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;display:none;">
				<thead></thead>
				<?php foreach($Courses as $Course) { ?>
				
					<tbody style="border: 1px solid black;">
					<tr class='clickable-row' align="center" data-href="<?php cr_location_url($Course, $Course->url_safe_product_name); ?>">						
						<td><img id="thumb-img-0" src="<?php echo cr_product_image($Course->product_image,'product'); ?>" width="94" height="80" /></td>
						<td><ul class="list-unstyled">
						<li style="font-size: 15px;font-weight: bold;"><?php echo $Course->product_name; ?></li>
						<li><a href="<?php cr_location_url($Course, $Course->url_safe_product_name); ?>"><?php echo $Course->total_rating_count; ?> Reviews</a></li>
						<li><?php echo $Course->total_rating; ?> of 5</li>
						<li><img src="<?php echo cr_rating_image($Course->total_rating); ?>" /></li>
						</ul></td></tr>
					</tbody>
				<?php } ?>
				</table>
				
				<table id="desktoptable" class="table" cellpadding="0"  cellspacing="0" border="0" style="border-collapse: collapse;">
					<thead></thead>
					<?php foreach($Courses as $Course) { ?>
						<tbody style="border: 1px solid black;">
						<tr class='clickable-row' align="center" data-href="<?php cr_location_url($Course, $Course->url_safe_product_name); ?>">
							<td><img id="thumb-img-0" src="<?php echo cr_product_image($Course->product_image,'product'); ?>" width="94" height="80" /></td>
							<td><ul class="list-unstyled">
							<li style="font-size: 15px;font-weight: bold;"><?php echo $Course->product_name; ?></li>
							</ul></td><td><ul class="list-unstyled"><li><a href="<?php cr_location_url($Course, $Course->url_safe_product_name); ?>"><?php echo $Course->total_rating_count; ?> Reviews</a></li>
							<li><?php echo $Course->total_rating; ?> of 5</li>
							<li><img src="<?php echo cr_rating_image($Course->total_rating); ?>" /></li>
							</ul></td>
						</tr>
						</tbody>
					<?php } ?>
					
				</table>					
				<div class="product-list-nav">
					Showing <?php echo number_format($CourseList->list_count); ?> <?php echo ucwords($CourseType['course_type_name']); ?> in <?php echo $CourseList->location; ?>
				</div>
			</div>	
		<?php } else { ?>
			<p class="subheadertext">No <?php echo $CourseType['course_type_name']; ?> found, please try searching again, or <a href="<?php bloginfo('url') ?>/<?php echo str_replace( ' ', '-', $CourseType['course_type_name'] ); ?>.html"><u>search by state</u></a>.</p>
			<div class="course-search">
				<form method="get" action="<?php bloginfo('url') ?>/site-location-search.html">
					<table cellspacing="0">
						<!--Zaved commented this line ason 25/Nov/2016
						<!--<tr><td><img src="<?php bloginfo('template_url'); ?>/images/find-course.png" /></td></tr>-->
						<tr><td align='center'><div id='find_header_image'>FIND A <?php echo strtoupper($CourseType['singuler_type_name']); ?></div></td></tr>
						<tr><td class="txt">Enter <?php echo ucwords($CourseType['course_type_name']); ?> Name &nbsp;<input type="text" name="search" /></td></tr>		
						<tr><td>or</td></tr>
						<tr><td class="txt">Enter Zip Code &nbsp;<input type="text" name="zip" style="width:100px;" /></td></tr>
						<tr><td><input class="sub" type="submit" value="Find <?php echo ucwords($CourseType['course_type_name']); ?>" /></td></tr>
					</table>
				</form>
			</div>				
		<?php } 
			
			cr_site_deals_widget();		
			$term_cat[] = $CourseList->state;
			$term_cat[] = $CourseList->city;			
			site_review_recent_articles('Recent Articles & Reviews on ' . ucwords($CourseType['course_type_name']) . ' in '.$CourseList->city.' '.$CourseList->state, $term_cat, 5); ?>
	</div>
	</div><!-- end main content -->
</div><!-- end inner -->
</div><!-- end content left -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>