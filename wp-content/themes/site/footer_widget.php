<?php 
include(__DIR__.'/../../../wp-config-extra.php');
?>
<style>

.thesite{
font-size:14px !important;
margin-bottom:0px !important;
}

.thesubsite{
font-size:14px !important;
margin-bottom:0px !important;
margin-top:0px !important;
}
.newfooter{
font-size:12px !important;
}
</style>
<div id="subfooter" class="col-sm-12">
			<h1 class="thesite footercolor">THE SITE </h1>
			<ul class="newfooter">
				
				<li><a href="http://www.<?php echo $SITE_NAME; ?>.com ">USER REVIEWS</a></li>
				|<li><a href="http://forums.<?php echo $SITE_NAME; ?>.com ">FORUMS</a></li>
				<?php if($FOOTERER==1){ ?>				
				|<li><a href="http://reviews.<?php echo $SITE_NAME; ?>.com">EDITORIAL REVIEWS</a></li>
				<?php } ?>
				|<li><a href="http://classifieds.<?php echo $SITE_NAME; ?>.com">CLASSIFIEDS</a></li>
				<?php if($FOOTERTRAILS==1){ ?>
				|<li><a href="http://www.<?php echo $SITE_NAME; ?>.com/trails.html">TRAILS</a></li>
				<?php } ?>
				<?php if($FOOTERHOTDEALS==1){ ?>
				|<li><a href="http://www.<?php echo $SITE_NAME; ?>.com/hot-deals.html">HOT DEALS</a></li>
				<?php } ?>
				
			</ul>
			<h1 class="thesubsite footercolor">ABOUT <?php echo strtoupper($SITE_NAME); ?></h1>
			<ul>
				
				<li><a href="#">ABOUT US</a></li>
				|<li><a href="#">CONTACT US</a></li>				
				|<li><a href="http://www.<?php echo $SITE_NAME; ?>.com/terms-of-use.html">TERMS OF USE</a></li>
				
				|<li><a href="http://www.<?php echo $SITE_NAME; ?>.com/privacy-policy.html">PRIVACY POLICY</a></li>
				
				|<li><a href="http://www.<?php echo $SITE_NAME; ?>.com/advertise-with-us.html">ADVERTISING</a></li>
				
				
			</ul>
			<h1 class="thesubsite footercolor">VISIT US AT</h1>
			<ul>
			<?php $fb = explode('|',get_option('footer_social_links')); ?>
				
				<li><a href="<?php  echo $fb[0]; ?>">FACEBOOK</a></li>
				
		|<li><a href="<?php echo $fb[1]; ?>">TWITTER</a></li>	
							
				|<li><a href="<?php echo $fb[2]; ?>">YOUTUBE</a></li>
			
				
				
			</ul>
					
			
			<p><?php echo $SITE_NAME; ?>.com and the ConsumerReview Network are business units of Invenda Corporation</p>
			<p>(C) Copyright 1996-<?php echo date("Y"); ?>. All Rights Reserved.</p>
		</div>
