<?php
	/**
	 * The template for displaying Site Review Site location by State.
	 *
	 * @package WordPress
	 * @subpackage Site Review
	 * @since Site Review 1.0
	 */
	require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

	site_review_location_type();
	site_review_courses_by_state();
	set_site_review_location('golf-course-state');
	set_site_review_meta_vars( ucwords($CourseType['course_type_name']), $State->name, $State->total_count );
	get_header();
?>
	<div id="content-left" class="col-sm-8">
		<div class="inner">			
		<div class="main-content">
		<div id="golf-courses">	
			<h2><?php echo $CourseType['course_type_name']; ?> <span class="lwr">in <?php echo $State->name; ?> (<?php echo number_format($State->total_count); ?>)</span></h2>	
			<?php if($State->total_count > 0) { ?>	
				<p class="subheadertext">Select a city below to see a list of <?php echo $CourseType['course_type_name']; ?></p>	
				<div class="golf-club-list"> Select the city alphabetically
					<?php site_review_city_list(); // print out city list alphabetically ?>
				</div>
			<?php } else { ?>
				<p class="subheadertext">No <?php echo $CourseType['course_type_name']; ?> found.</p>
			<?php } ?>
			<div class="course-search">			
				<form method="get" action="<?php bloginfo('url') ?>/site-location-search.html">
					<table cellspacing="0">
						<!--Zaved commented this line ason 25/Nov/2016
						<!--<tr><td><img src="<?php bloginfo('template_url'); ?>/images/find-course.png" /></td></tr>-->
						<tr><td align='center'><div id='find_header_image'>FIND A <?php echo strtoupper($CourseType['singuler_type_name']); ?></div></td></tr>
						<tr><td class="txt">Enter <?php echo ucwords($CourseType['singuler_type_name']); ?> Name &nbsp;<input type="text" name="search" /></td></tr>
						<tr><td>or</td></tr>
						<tr><td class="txt">Enter Zip Code &nbsp;<input type="text" name="zip" style="width:100px;" /></td></tr>
						<tr><td><input class="sub" type="submit" value="Find <?php echo ucwords($CourseType['course_type_name']); ?>" /></td></tr>
					</table>
				</form>
			</div>	
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
<?php

	$term_cat[] = $State->name;
	site_review_recent_articles('Recent Articles & Reviews on ' . ucwords($CourseType['course_type_name']) . ' in ' . $State->name, $term_cat, 5);
?>
	</div>	
	</div><!-- end main content -->			
</div><!-- end inner -->
</div><!-- end content left -->	
<?php get_sidebar(); ?>
<?php get_footer(); ?>