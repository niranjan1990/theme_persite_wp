	</div><!-- end content -->
	<div id="footer-separator" class="col-sm-12"></div>
	<div id="footer" class="col-sm-12 hidden-md-down">
		 <div id="footer-ad">
			<?php if(Live_Ads()) { ?>
			<?php get_sidebar( 'bottom-leaderboard' ); ?>
		 <?php } else { ?>
			<a href="#"><img src="<?php bloginfo('template_url') ?>/images/footer-ad.png" /></a>
		<?php } ?>
		 </div> 
	</div>
</div><!-- end container -->
<div id="sub-footer" class="col-lg-12">
	<p><ul>
		<li><a href="http://www.consumerreview.com/channels/consumerreview/data/about_us.html">About Us</a></li>
		<li><a href="http://www.consumerreview.com/channels/consumerreview/data/media_op.html">Advertise</a></li>
		<li><a href="http://www.consumerreview.com/channels/consumerreview/data/main/terms.html">Terms of Use</a></li>
		<li><a href="http://www.consumerreview.com/channels/consumerreview/data/main/privacy.html">Privacy Policy</a></li>
		<li><a href="http://www.consumerreview.com/channels/consumerreview/data/contact_us.html">Contact Us</a></li>
	</ul></p>
	<p>(C) Copyright 1996-2011. All Rights Reserved.</p>
	<p>GolfReview.com and the <a href="http://www.consumerreview.com">ConsumerReview Network</a> are business units of Invenda Corporation</p>
	<p>&nbsp;</p>
	<p>Other Web Sites in the ConsumerReview Network:</p>
	<p><a href="http://www.mtbr.com">mtbr.com</a> | 
		<a href="http://www.roadbikereview.com">roadbikereview.com</a> | 
		<a href="http://www.carreview.com">carreview.com</a> | 
		<a href="http://www.photographyreview.com">photographyreview.com</a>
	</p>
	<p><a href="http://www.audioreview">audioreview.com</a> | 
		<a href="http://www.outdoorreview.com">outdoorreview.com</a> | 
		<a href="http://www.videogamereview.com">videogamereview.com</a> | 
		<a href="http://www.computingreview.com">computingreviewreview.com</a>
	</p>
</div>
<div id="fb-root"></div> 
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
	
	$('.bxslider').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider').bxSlider({   });

	 }
	 });
	 
	 	 $('.bxslider-gfdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gfdeals').bxSlider({    });

	 }
	 });
	 
	 $('.bxslider-fwdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-fwdeals').bxSlider({    });

	 }
	 });
	 
	 $('.bxslider-gwdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gwdeals').bxSlider({    });

	 }
	 });
	 
	 $('.bxslider-gideals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gideals').bxSlider({    });

	 }
	 });
	 
	 $('.bxslider-gpdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gpdeals').bxSlider({    });

	 }
	 });
	 
	 $('.bxslider-gbdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gbdeals').bxSlider({   });

	 }
	 });
	 
	 $('.bxslider-gsdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gsdeals').bxSlider({  });

	 }
	 });
	 
	 $('.bxslider-gbagdeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gbagdeals').bxSlider({    });

	 }
	 });
	 
	  $('.bxslider-gedeals').bxSlider({
	 onSliderLoad: function(){
		 
	 $(".bxslider-wrap").css("visibility", "visible");
	 $('.bxslider-gedeals').bxSlider({   });

	 }
	 });
});
</script>
<script type="text/javascript">
$(document).ready(function(){

$('.productbxslider').bxSlider({
	 onSliderLoad: function(){
 $('.productbxslider').bxSlider({   });
$('.productbxslider').css('visibility','visible');  
	 }
 });



});
</script>


<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.document.location = $(this).data("href");
    });
});
</script>
	
<!--
<script type="text/javascript"> 
  FB.init({
    appId  : '234234',
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
    channelUrl  : 'http://www.golfreview.com/channel.html', // Custom channel URL
    oauth : true // enables OAuth 2.0
  });
</script>-->
<?php wp_footer() ?>





	
		
</body>
</html>
