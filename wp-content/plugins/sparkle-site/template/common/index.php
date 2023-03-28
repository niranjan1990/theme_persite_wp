<?php 
if(function_exists('set_site_review_location')){
	set_site_review_location('Index'); 
}

	get_header();
?>




	<div id="content-left" class="col-sm-8">
	<div class="inner">
	<div id="featured-content" class="clearfix">
		<?Php if(function_exists('site_review_get_articles')){ site_review_get_articles('homepage-top-products1'); }?>
		<div class="best-clubs">
			<?Php if($Article['homepage-top-products1']) { z?>		
			<<?Php echo $Article['tag']['homepage-top-products1']; ?> class="featured-header lwr"><?php echo $Article['title']['homepage-top-products1']; ?>&nbsp;</<?php echo $Article['tag']['homepage-top-products1']; ?>>
			<ul>
				<?Php
					$count=0;
					foreach($Article['homepage-top-products1'] as $top) {
							
						if($count == 0) { ?>
							<li class="top"><div><a href="<?php echo $top->permalink; ?>"><?php echo $top->title; ?></a></div>
								<p><a href="<?php echo $top->permalink; ?>"><img src="<?php echo $top->image_small; ?>" /></a></p>
							</li>
						<?Php } else { ?>
						<li><div><a href="<?php echo $top->permalink; ?>"><?php echo $top->title; ?></a></div></li>
						<?Php } $count++;
					} ?>
			</ul>
			<?php } ?>
		</div>
	<?php if(function_exists('site_review_get_articles')){ site_review_get_articles('homepage-featured'); }?>
	<?php if($Article['homepage-featured']) { ?>
	<script type="text/javascript"><!--
	ss = new slideshow("ss");
	<?php foreach($Article['homepage-featured'] as $art) { ?>
	s = new slide();
	s.src =  "<?php echo $art->image_large; ?>";
	s.link = "<?php echo $art->permalink; ?>";
	s.title_text = "<?php echo $art->title; ?>";
	s.text = "<?php echo site_review_trim_content($art->content,50); ?>";
	ss.add_slide(s);
	<?php } ?>
	window.onload = function() { ss.update(); }
//--></script>
	<div class="featured-item">
	<div id="featured-nav">
	<ul>
		<?php for($i=0;$i<count($Article['homepage-featured']);$i++) { ?>
			<li><a href="#" onclick="ss.goto_slide(<?php echo $i; ?>);ss.pause();return false;"><?php echo $i+1; ?></a></li>
		<?php } ?>
	</ul>
	</div>
	<<?php echo $Article['tag']['homepage-featured']; ?> class="featured-header lwr"><?php echo $Article['title']['homepage-featured']; ?>&nbsp;</<?php echo $Article['tag']['homepage-featured']; ?>>			
		<div id="featured1" class="featured">
			<div class="featured-image"><a href="javascript:ss.hotlink()"><img id="slide_img" src="<?php echo $Article['homepage-featured'][1]->image; ?>" />
			<div id="featured-title-bg"><?php echo $Article['homepage-featured'][1]->title; ?></div>
			<div id="featured-title"><?php echo $Article['homepage-featured'][1]->title; ?></div></a></div>
			<div id="slidetext" class="featured-text">
					<?php echo site_review_trim_content($Article['homepage-featured'][1]->content,50); ?>
			</div>
			<div class="text-right"><a class="arrow-right" href="javascript:ss.hotlink()">read more</a></div>
		</div>
	</div>		
	<script type="text/javascript">
<!--
	if (document.images) {
		ss.image = document.images.slide_img;
		ss.textid = "slidetext";
		ss.title_textid = "featured-title";
		ss.timeout = 5000;
		ss.update();
		ss.play();
	}
//-->
</script>
<?php } ?>
	</div>
	
	<!--<?php cr_site_deals_widget(1); ?>-->
	
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
	<div class="main-content"><?php site_review_recent_articles('Recent Articles & Reviews',array(), 10); ?></div><!-- end main content -->
</div><!-- end inner -->
</div><!-- end content left -->
<!--<div class="hidden-md-down col-sm-4">-->
<?php get_sidebar(); ?>
<!--</div>-->
<?php get_footer(); ?>